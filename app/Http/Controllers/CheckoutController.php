<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\CustomerPurchaseOrder;
use App\Models\PaymentMethod;
use App\Models\Product;
use App\Models\Product_Stocks;
use App\Traits\LogsAuditTrail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CheckoutController extends Controller
{
    use LogsAuditTrail;
    public function store(Request $request)
    {
        Log::info('=== CHECKOUT PROCESS STARTED ===');
        Log::info('Request data:', $request->all());

        DB::beginTransaction();

        try {
            // Validate required fields
            $request->validate([
                'customer_id' => 'required|exists:customers,id',
                'payment_method' => 'required|string|max:255',
                'amount' => 'required|numeric|min:0',
                'items' => 'required|array|min:1',
                'items.*.product_id' => 'required|exists:products,id',
                'items.*.unit_price' => 'required|numeric|min:0',
                'items.*.quantity' => 'required|integer|min:1',
                'items.*.total_price' => 'required|numeric|min:0',
                'items.*.serial_number' => 'required|string',
            ]);

            $customerId = $request->customer_id;
            $paymentMethod = $request->payment_method;
            $amount = $request->amount;
            $items = $request->items;

            Log::info('Processing checkout for customer:', [
                'customer_id' => $customerId,
                'payment_method' => $paymentMethod,
                'amount' => $amount,
                'items_count' => count($items)
            ]);

            // Create customer purchase orders for each item
            $purchaseOrderIds = [];
            foreach ($items as $index => $item) {
                Log::info("Creating purchase order for item {$index}:", $item);

                $purchaseOrder = CustomerPurchaseOrder::create([
                    'customer_id' => $customerId,
                    'product_id' => $item['product_id'],
                    'serial_number' => $item['serial_number'],
                    'quantity' => $item['quantity'],
                    'unit_price' => $item['unit_price'],
                    'total_price' => $item['total_price'],
                    'order_date' => now()->format('Y-m-d'),
                    'status' => 'Success'
                ]);

                $purchaseOrderIds[] = $purchaseOrder->id;
                Log::info("Purchase order created with ID: {$purchaseOrder->id}");

                // Mark product as sold by setting stock to 0
                $this->markProductAsSold($item['product_id'], $item['serial_number']);
            }

            // Create payment method linked to the first purchase order
            if (!empty($purchaseOrderIds)) {
                Log::info('Creating payment method linked to purchase order:', [
                    'customer_purchase_order_id' => $purchaseOrderIds[0],
                    'method_name' => $paymentMethod,
                    'amount' => $amount
                ]);

                PaymentMethod::create([
                    'customer_purchase_order_id' => $purchaseOrderIds[0],
                    'method_name' => $paymentMethod,
                    'payment_date' => now()->format('Y-m-d'),
                    'amount' => $amount
                ]);

                Log::info('Payment method created successfully');
            }

            DB::commit();
            Log::info('=== CHECKOUT PROCESS COMPLETED SUCCESSFULLY ===');

            // Get customer for audit logging
            $customer = Customer::find($customerId);
            $totalQuantity = collect($items)->sum('quantity');
            $totalPrice = $amount;

            // Log the POS sale to audit trail
            $this->logSaleAudit('POS', $customer, $totalQuantity, $totalPrice, $request);

            // Store receipt data in session for receipt page
            $receiptData = [
                'customerName' => $customer->first_name . ' ' . $customer->last_name,
                'customerId' => $customerId,
                'paymentMethod' => $paymentMethod,
                'amount' => $amount,
                'subtotal' => $request->subtotal ?? 0,
                'discount' => $request->discount ?? 0,
                'total' => $amount,
                'items' => $this->getReceiptItemsData($items),
                'purchase_order_ids' => $purchaseOrderIds,
                'displayTotalOnly' => $request->displayTotalOnly === 'true' ? true : false
            ];

            session(['receiptData' => $receiptData]);

            // Return JSON response for SweetAlert
            return response()->json([
                'success' => true,
                'message' => 'Purchase completed successfully!',
                'redirect_url' => route('pos.purchasereceipt')
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Checkout failed:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Checkout failed: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Show purchase receipt
     */
    public function showReceipt()
    {
        $receiptData = session('receiptData');

        if (!$receiptData) {
            return redirect()->route('pos.itemlist')->with('error', 'No receipt data found. Please complete a purchase first.');
        }

        // Get customer contact info if available
        $customerContact = 'N/A';
        if (isset($receiptData['customerId'])) {
            $customer = Customer::find($receiptData['customerId']);
            if ($customer) {
                $customerContact = $customer->contact_no;
            }
        }

        // Get authenticated user's full name and role
        $authenticatedUser = Auth::user();
        $preparedBy = 'N/A';
        $preparedByRole = 'N/A';
        if ($authenticatedUser) {
            $preparedBy = trim($authenticatedUser->first_name . ' ' .
                ($authenticatedUser->middle_name ? $authenticatedUser->middle_name . ' ' : '') .
                $authenticatedUser->last_name);

            // Translate role based on conditions
            if ($authenticatedUser->role === 'admin') {
                $preparedByRole = $authenticatedUser->id === 1 ? 'Owner' : 'Co Owner';
            } else {
                $preparedByRole = ucfirst($authenticatedUser->role ?? 'N/A');
            }
        }

        return view('POS_SYSTEM.PurchaseReceipt', compact('receiptData', 'customerContact', 'preparedBy', 'preparedByRole'));
    }

    /**
     * Mark product as sold by setting its stock to 0
     */
    private function markProductAsSold($productId, $serialNumber)
    {
        Log::info("Marking product as sold:", [
            'product_id' => $productId,
            'serial_number' => $serialNumber
        ]);

        // Update product stock to 0 (sold)
        $stock = Product_Stocks::where('product_id', $productId)->first();
        if ($stock) {
            $stock->stock_quantity = 0;
            $stock->save();
            Log::info("Product stock set to 0 for product ID: {$productId}");
        }
    }

    /**
     * Get formatted items data for receipt
     */
    private function getReceiptItemsData($items)
    {
        $receiptItems = [];

        foreach ($items as $item) {
            $product = Product::find($item['product_id']);
            if ($product) {
                $receiptItems[] = [
                    'productName' => $product->product_name,
                    'price' => $item['unit_price'],
                    'warranty' => $product->warranty_period ?? '1 Year',
                    'quantity' => $item['quantity'],
                    'subtotal' => $item['total_price'],
                    'serialNumber' => $item['serial_number']
                ];
            }
        }

        return $receiptItems;
    }
}
