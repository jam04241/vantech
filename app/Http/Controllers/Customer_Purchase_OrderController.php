<?php

namespace App\Http\Controllers;

use App\Models\Customer_Purchase_Order;
use App\Models\Product;
use App\Http\Requests\Customer_purchaseOrderedRequest;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class Customer_Purchase_OrderController extends Controller
{
    /**
     * Retrieve all customer purchase orders
     */
    public function index()
    {
        $Customer_Purchase_Orders = Customer_Purchase_Order::all();
        return response()->json($Customer_Purchase_Orders);
    }

    /**
     * Store customer purchase orders from checkout
     * Uses Eloquent ORM for reliability and maintainability
     */
    public function store($items, $customerId)
    {
        try {
            Log::info('Customer_Purchase_OrderController::store() - Processing purchase orders', [
                'customer_id' => $customerId,
                'items_count' => count($items)
            ]);

            $purchaseOrders = [];

            foreach ($items as $item) {
                Log::info('Creating purchase order:', [
                    'product_id' => $item['product_id'],
                    'customer_id' => $customerId,
                    'quantity' => $item['quantity'],
                    'unit_price' => $item['unit_price'],
                    'total_price' => $item['total_price']
                ]);

                // Create using Eloquent ORM
                $purchaseOrder = Customer_Purchase_Order::create([
                    'product_id' => $item['product_id'],
                    'customer_id' => $customerId,
                    'quantity' => $item['quantity'],
                    'unit_price' => $item['unit_price'],
                    'total_price' => $item['total_price'],
                    'order_date' => now()->toDateString(),
                    'status' => 'Success'
                ]);

                Log::info('✓ CUSTOMER_PURCHASE_ORDER CREATED', [
                    'id' => $purchaseOrder->id,
                    'product_id' => $purchaseOrder->product_id,
                    'customer_id' => $purchaseOrder->customer_id,
                    'total_price' => $purchaseOrder->total_price
                ]);

                $purchaseOrders[] = $purchaseOrder;
            }

            return [
                'success' => true,
                'message' => 'Purchase orders created successfully',
                'purchase_orders' => $purchaseOrders
            ];
        } catch (\Exception $e) {
            Log::error('Customer_Purchase_OrderController::store() error:', [
                'exception' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return [
                'success' => false,
                'message' => 'Error creating purchase orders: ' . $e->getMessage()
            ];
        }
    }

    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        //
    }

    public function update($id)
    {
        //
    }

    public function destroy($id)
    {
        //
    }
}
