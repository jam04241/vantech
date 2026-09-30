<?php

namespace Tests\Feature;

use App\Models\Brand;
use App\Models\Category;
use App\Models\CustomerPurchaseOrder;
use App\Models\Product;
use App\Models\Product_Stocks;
use App\Models\Purchase_Details;
use App\Models\Suppliers;
use App\Models\User;
use App\Models\WarrantyClaim;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
use Illuminate\Testing\TestResponse;
use Tests\TestCase;

/**
 * A sold item that comes back broken under warranty must stop counting as a
 * sale everywhere: dashboard, Sales page, Sales Reports and inventory report.
 */
class WarrantyClaimTest extends TestCase
{
    use RefreshDatabase;

    private User $owner;
    private Brand $brand;
    private Category $category;

    protected function setUp(): void
    {
        parent::setUp();

        $this->withoutVite();
        $this->owner = $this->makeUser('admin');
        $this->brand = Brand::create(['brand_name' => 'Test Brand']);
        $this->category = Category::create(['category_name' => 'Test Category']);
    }

    public function test_claim_takes_the_sale_and_its_good_cost_out_of_every_report(): void
    {
        $laptop = $this->makeProduct('Laptop', 10000, '1 year');
        $this->recordSupplierOrder(8000);
        [$line] = $this->sell([$laptop]);

        $this->assertSalesPage(revenue: 10000, goodCost: 8000, profit: 2000, discount: 0);
        $this->assertSame(10000.0, (float) $this->dashboard()['metrics']['daily_sales']);

        $this->claim($line, 8000)->assertOk()->assertJsonPath('success', true);

        $this->assertSame(CustomerPurchaseOrder::STATUS_WARRANTY_CLAIM, $line->fresh()->status);
        $this->assertSame(0, $laptop->stock->fresh()->stock_quantity, 'A broken item must not go back on sale.');

        $sales = $this->assertSalesPage(revenue: 0, goodCost: 0, profit: 0, discount: 0);
        $this->assertSame(1, $sales['warranty']['items']);
        $this->assertSame(10000.0, (float) $sales['warranty']['sales_removed']);
        $this->assertSame(8000.0, (float) $sales['warranty']['good_cost_removed']);
        $this->assertCount(0, $sales['top_products']);
        $this->assertSame(0.0, (float) $sales['recent_transactions'][0]['amount']);
        $this->assertSame(1, $sales['recent_transactions'][0]['warranty_items']);

        $dashboard = $this->dashboard();
        $this->assertSame(0.0, (float) $dashboard['metrics']['daily_sales']);
        $this->assertSame(10000.0, (float) $dashboard['metrics']['daily_warranty_returns']);
        $this->assertCount(0, $dashboard['top_products']);

        $report = $this->salesReport();
        $this->assertSame(0.0, (float) $report['summary']['revenue']);
        $this->assertSame(0, $report['summary']['total_orders']);
        $this->assertSame(0.0, (float) $report['transactions'][0]['amount']);

        $inventory = $this->actingAs($this->owner)->get(route('inventory.reports'))->assertOk();
        $this->assertSame(0, $inventory->viewData('totalSold'));
    }

    public function test_a_receipt_discount_is_shared_by_price_and_nothing_is_left_behind(): void
    {
        $monitor = $this->makeProduct('Monitor', 10000, '1 year');
        $keyboard = $this->makeProduct('Keyboard', 5000, '1 year');
        [$monitorLine, $keyboardLine] = $this->sell([$monitor, $keyboard], discount: 1500);

        // The monitor is two thirds of the receipt, so it carries two thirds of
        // the discount: the customer paid 9,000 of the 13,500 for it.
        $this->claim($monitorLine, 0)->assertOk();
        $this->assertSame('9000.00', WarrantyClaim::first()->sales_amount);

        $this->assertSalesPage(revenue: 5000, goodCost: 0, profit: 4500, discount: 500);
        $report = $this->salesReport();
        $this->assertSame(4500.0, (float) $report['summary']['revenue']);
        $this->assertSame(1, $report['summary']['total_orders']);
        $this->assertSame(4500.0, (float) $report['transactions'][0]['amount']);
        $this->assertSame(500.0, (float) $report['transactions'][0]['discount']);

        // Returning the rest of the receipt removes exactly what was paid.
        $this->claim($keyboardLine, 0)->assertOk();
        $this->assertSame(13500.0, (float) WarrantyClaim::sum('sales_amount'));
        $report = $this->salesReport();
        $this->assertSame(0.0, (float) $report['summary']['revenue']);
        $this->assertSame(0, $report['summary']['total_orders']);
    }

    public function test_shares_that_do_not_divide_evenly_still_add_up_to_what_was_paid(): void
    {
        $products = [
            $this->makeProduct('Fan A', 100, '1 year'),
            $this->makeProduct('Fan B', 100, '1 year'),
            $this->makeProduct('Fan C', 100, '1 year'),
        ];
        $lines = $this->sell($products, discount: 100);

        foreach ($lines as $line) {
            $this->claim($line, 0)->assertOk();
        }

        $this->assertSame(['66.67', '66.67', '66.66'], WarrantyClaim::orderBy('id')->pluck('sales_amount')->all());
        $this->assertSame(200.0, round((float) WarrantyClaim::sum('sales_amount'), 2));
    }

    public function test_only_sold_items_still_under_warranty_can_be_claimed_once(): void
    {
        $mouse = $this->makeProduct('Mouse', 500, '7 days');
        $cable = $this->makeProduct('Cable', 100, Product::NO_WARRANTY);
        [$mouseLine, $cableLine] = $this->sell([$mouse, $cable]);

        $this->claim($cableLine, 0)
            ->assertStatus(422)
            ->assertJsonPath('errors.claim.0', 'This item was sold without a warranty.');

        $this->actingAs($this->owner)
            ->postJson(route('warranty-claims.store', $mouseLine), ['good_cost' => -1])
            ->assertStatus(422)
            ->assertJsonValidationErrors(['good_cost', 'reason']);

        // The last covered day still counts; the day after does not.
        $this->travel(7)->days();
        $this->assertNull(app(\App\Services\WarrantyClaimService::class)->ineligibilityReason($mouseLine->fresh()));
        $this->travel(1)->days();
        $this->claim($mouseLine, 0)
            ->assertStatus(422)
            ->assertJsonPath('errors.claim.0', 'The warranty expired on ' . now()->subDay()->format('M d, Y') . '.');
        $this->travelBack();

        $this->claim($mouseLine, 0)->assertOk();
        $this->claim($mouseLine, 0)
            ->assertStatus(422)
            ->assertJsonPath('errors.claim.0', 'This item already has a warranty claim.');
        $this->assertSame(1, WarrantyClaim::count());

        $this->actingAs($this->owner)
            ->postJson(route('warranty-claims.store', 999999), ['good_cost' => 0, 'reason' => 'x'])
            ->assertNotFound();
    }

    public function test_staff_can_record_a_claim_but_only_the_owner_can_undo_it(): void
    {
        $laptop = $this->makeProduct('Laptop', 10000, '1 year');
        [$line] = $this->sell([$laptop]);
        $staff = $this->makeUser('staff');

        $this->claim($line, 7000, $staff)->assertOk();
        $claim = WarrantyClaim::firstOrFail();
        $this->assertSame($staff->id, $claim->recorded_by);

        $this->actingAs($staff)
            ->deleteJson(route('warranty-claims.destroy', $claim))
            ->assertForbidden();
        $this->assertModelExists($claim);

        $this->actingAs($this->owner)
            ->deleteJson(route('warranty-claims.destroy', $claim))
            ->assertOk();

        $this->assertModelMissing($claim);
        $this->assertSame(CustomerPurchaseOrder::STATUS_SUCCESS, $line->fresh()->status);
        $this->assertSalesPage(revenue: 10000, goodCost: 0, profit: 10000, discount: 0);
    }

    public function test_stock_out_page_offers_the_claim_then_shows_it(): void
    {
        $laptop = $this->makeProduct('Gaming Laptop', 10000, '1 year');
        $cable = $this->makeProduct('HDMI Cable', 100, Product::NO_WARRANTY);
        [$laptopLine] = $this->sell([$laptop, $cable]);
        $claimUrl = route('warranty-claims.store', $laptopLine);

        $this->actingAs($this->owner)->get(route('inventory.stock-out'))
            ->assertOk()
            ->assertSee($claimUrl, false)
            ->assertSee('No warranty');

        $this->claim($laptopLine, 0)->assertOk();

        $this->actingAs($this->owner)->get(route('inventory.stock-out'))
            ->assertOk()
            ->assertSee('View Claim')
            ->assertSee('undoWarrantyClaim(this)', false)
            ->assertDontSee($claimUrl, false);

        $this->actingAs($this->owner)->get(route('inventory.stock-out', ['status' => 'claimed']))
            ->assertSee('Gaming Laptop')
            ->assertDontSee('HDMI Cable');

        // Staff see the claim but not the owner-only undo.
        $this->actingAs($this->makeUser('staff'))->get(route('inventory.stock-out'))
            ->assertSee('View Claim')
            ->assertDontSee('undoWarrantyClaim(this)', false);
    }

    public function test_warranty_end_date_follows_the_period_without_spilling_into_the_next_month(): void
    {
        $product = new Product(['warranty_period' => '7 days']);
        $this->assertSame('2026-10-07', $product->warrantyExpiresOn('2026-09-30')->toDateString());

        $product->warranty_period = '1 year';
        $this->assertSame('2025-02-28', $product->warrantyExpiresOn('2024-02-29')->toDateString());

        $product->warranty_period = '6 months';
        $this->assertSame('2026-02-28', $product->warrantyExpiresOn('2025-08-31')->toDateString());

        $product->warranty_period = Product::NO_WARRANTY;
        $this->assertNull($product->warrantyExpiresOn('2026-09-30'));
    }

    private function makeUser(string $role): User
    {
        return User::create([
            'first_name' => ucfirst($role),
            'last_name' => 'Tester',
            'username' => $role . '_' . Str::lower(Str::random(8)),
            'password' => 'secret-password',
            'role' => $role,
            'status' => User::STATUS_ACTIVE,
        ]);
    }

    private function makeProduct(string $name, float $price, string $warranty): Product
    {
        $product = Product::create([
            'product_name' => $name,
            'brand_id' => $this->brand->id,
            'category_id' => $this->category->id,
            'warranty_period' => $warranty,
            'serial_number' => 'SN-' . Str::upper(Str::random(10)),
            'product_condition' => 'Brand New',
        ]);

        Product_Stocks::create(['product_id' => $product->id, 'stock_quantity' => 1, 'price' => $price]);

        return $product;
    }

    private function recordSupplierOrder(float $total): void
    {
        $supplier = Suppliers::create([
            'supplier_name' => 'Supplier',
            'company_name' => 'Supplier Co.',
            'contact_phone' => '09170000000',
        ]);

        Purchase_Details::create([
            'supplier_id' => $supplier->id,
            'quantity_ordered' => 1,
            'unit_price' => $total,
            'total_price' => $total,
            'order_date' => today(),
            'status' => 'Received',
        ]);
    }

    /**
     * Sell through the real POS checkout. Returns the sale lines in product order.
     *
     * @return CustomerPurchaseOrder[]
     */
    private function sell(array $products, float $discount = 0): array
    {
        $items = collect($products)->map(fn (Product $product) => [
            'product_id' => $product->id,
            'serial_number' => $product->serial_number,
            'unit_price' => $product->stock->price,
            'quantity' => 1,
            'total_price' => $product->stock->price,
        ])->all();
        $subtotal = collect($items)->sum('total_price');

        $this->actingAs($this->owner)->postJson(route('checkout.store'), [
            'payment_method' => 'Cash',
            'amount' => $subtotal - $discount,
            'subtotal' => $subtotal,
            'discount' => $discount,
            'total' => $subtotal - $discount,
            'items' => $items,
        ])->assertOk()->assertJsonPath('success', true);

        return collect($products)
            ->map(fn (Product $product) => CustomerPurchaseOrder::where('product_id', $product->id)->latest('id')->firstOrFail())
            ->all();
    }

    private function claim(CustomerPurchaseOrder $line, float $goodCost, ?User $user = null): TestResponse
    {
        return $this->actingAs($user ?? $this->owner)->postJson(route('warranty-claims.store', $line), [
            'good_cost' => $goodCost,
            'reason' => 'Stopped powering on after a week of normal use.',
        ]);
    }

    private function assertSalesPage(float $revenue, float $goodCost, float $profit, float $discount): array
    {
        $today = today()->toDateString();
        $data = $this->actingAs($this->owner)
            ->getJson("/api/sales/data?start_date={$today}&end_date={$today}")
            ->assertOk()
            ->json('data');

        $this->assertSame($revenue, (float) $data['revenue'], 'Total Sales');
        $this->assertSame($goodCost, (float) $data['total_good_cost'], 'Total Good Cost');
        $this->assertSame($profit, (float) $data['profit'], 'Profit');
        $this->assertSame($discount, (float) $data['discount'], 'Total Discount');

        return $data;
    }

    private function dashboard(): array
    {
        // The dashboard caches its figures for a second.
        Cache::forget('dashboard_data');

        return $this->actingAs($this->owner)->getJson('/api/dashboard/data')->assertOk()->json('data');
    }

    private function salesReport(): array
    {
        $today = today()->toDateString();

        return $this->actingAs($this->owner)
            ->getJson(route('sales.report.data', ['start_date' => $today, 'end_date' => $today]))
            ->assertOk()
            ->json('data');
    }
}
