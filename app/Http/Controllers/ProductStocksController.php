<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Product_Stocks;
use App\Traits\LogsAuditTrail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductStocksController extends Controller
{
    use LogsAuditTrail;

    /**
     * Update price for a product and the identical items sold alongside it.
     *
     * Brand New stock of the same name/brand/category is interchangeable, so the
     * whole group is repriced together. Second Hand units are graded individually
     * and priced per unit, so only the ones currently carrying the same price are
     * touched — otherwise repricing one used item silently overwrote the price of
     * every other used item with the same name.
     */
    public function updatePrice(Request $request, Product $product)
    {
        $validated = $request->validate([
            'price' => 'required|numeric|min:0',
        ]);

        $product->loadMissing('stock');

        $oldPrice = $product->stock?->price ?? 0;
        $newPrice = (float) $validated['price'];
        $updatedCount = 0;

        DB::transaction(function () use ($product, $oldPrice, $newPrice, &$updatedCount) {
            $query = Product::query()
                ->where('product_name', $product->product_name)
                ->where('brand_id', $product->brand_id)
                ->where('category_id', $product->category_id)
                ->where('product_condition', $product->product_condition);

            if ($product->product_condition !== 'Brand New') {
                $query->whereHas('stock', function ($stock) use ($oldPrice) {
                    $stock->where('price', $oldPrice);
                });
            }

            foreach ($query->with('stock')->get() as $prod) {
                if ($prod->stock) {
                    $prod->stock->price = $newPrice;
                    $prod->stock->save();
                    $updatedCount++;
                }
            }
        });

        // Audit log
        $priceAction = $oldPrice > $newPrice ? 'Decrease' : 'Increase';
        $description = "{$priceAction} all price for {$product->product_name} = ₱{$oldPrice} => ₱{$newPrice}";
        $this->logUpdateAudit(
            'UPDATE',
            'Inventory',
            $description,
            ['price' => $oldPrice],
            ['price' => $newPrice],
            $request
        );

        $message = "Price updated for {$updatedCount} " . \Illuminate\Support\Str::plural('item', $updatedCount) . '.';

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => $message,
            ]);
        }

        return back()->with('success', $message);
    }
}
