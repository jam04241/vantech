<?php

namespace App\Services;

use App\Models\Customer_Purchase_Order;
use App\Models\Payment_Method;
use App\Models\Product_Stocks;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CheckoutService
{
    public function processCheckout(array $validated)
    {
        DB::beginTransaction();

        try {
            Log::info('=== CHECKOUT SERVICE STARTED ===', $validated);

            // Step 1: Create purchase orders
            $purchaseOrders = $this->createPurchaseOrders(
                $validated['items'],
                $validated['customer_id']
            );

            if (empty($purchaseOrders)) {
                throw new \Exception('No purchase orders were created');
            }

            // Step 2: Create payment method
            $paymentMethod = $this->createPaymentMethod(
                $purchaseOrders[0]->id,
                $validated['payment_method'],
                $validated['amount']
            );

            // Step 3: Update product stock
            $this->updateProductStock($validated['items']);

            DB::commit();

            Log::info('=== CHECKOUT SERVICE COMPLETED ===', [
                'purchase_orders_count' => count($purchaseOrders),
                'payment_method_id' => $paymentMethod->id
            ]);

            return [
                'success' => true,
                'message' => 'Checkout processed successfully',
                'purchase_orders' => $purchaseOrders,
                'payment_method' => $paymentMethod
            ];
        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('❌ CHECKOUT SERVICE ERROR', [
                'exception' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return [
                'success' => false,
                'message' => 'Checkout failed: ' . $e->getMessage(),
                'error' => $e->getMessage()
            ];
        }
    }

    private function createPurchaseOrders(array $items, int $customerId)
    {
        $purchaseOrders = [];

        foreach ($items as $index => $item) {
            $itemNumber = $index + 1;

            Log::info("Creating purchase order {$itemNumber}", $item);

            $purchaseOrder = Customer_Purchase_Order::create([
                'customer_id' => $customerId,
                'product_id' => $item['product_id'],
                'serial_number' => $item['serial_number'],
                'quantity' => $item['quantity'],
                'unit_price' => $item['unit_price'],
                'total_price' => $item['total_price'],
                'order_date' => now()->toDateString(),
                'status' => 'Success'
            ]);

            Log::info("✅ Purchase order {$itemNumber} created", [
                'id' => $purchaseOrder->id,
                'product_id' => $purchaseOrder->product_id,
                'serial_number' => $purchaseOrder->serial_number
            ]);

            $purchaseOrders[] = $purchaseOrder;
        }

        return $purchaseOrders;
    }

    private function createPaymentMethod(int $purchaseOrderId, string $methodName, float $amount)
    {
        Log::info('Creating payment method', [
            'customer_purchase_order_id' => $purchaseOrderId,
            'method_name' => $methodName,
            'amount' => $amount
        ]);

        $paymentMethod = Payment_Method::create([
            'customer_purchase_order_id' => $purchaseOrderId,
            'method_name' => $methodName,
            'payment_date' => now()->toDateString(),
            'amount' => $amount
        ]);

        Log::info('✅ Payment method created', [
            'id' => $paymentMethod->id,
            'customer_purchase_order_id' => $paymentMethod->customer_purchase_order_id
        ]);

        return $paymentMethod;
    }

    private function updateProductStock(array $items)
    {
        foreach ($items as $item) {
            $productStock = Product_Stocks::where('product_id', $item['product_id'])->first();

            if ($productStock) {
                $currentStock = (int)$productStock->stock_quantity;
                $newStock = $currentStock - $item['quantity'];

                if ($newStock < 0) {
                    throw new \Exception("Insufficient stock for product ID: {$item['product_id']}");
                }

                $productStock->update(['stock_quantity' => (string)$newStock]);

                Log::info('📦 Updated product stock', [
                    'product_id' => $item['product_id'],
                    'old_stock' => $currentStock,
                    'new_stock' => $newStock,
                    'quantity_sold' => $item['quantity']
                ]);
            } else {
                Log::warning('Product stock not found for product ID: ' . $item['product_id']);
            }
        }
    }
}
