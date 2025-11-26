<?php

namespace App\Http\Controllers;

use App\Models\Payment_Method;
use App\Http\Requests\PaymentMethodRequest;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class Payment_MethodController extends Controller
{
    /**
     * Store payment method record from checkout
     * Uses Eloquent ORM for reliability and maintainability
     */
    public function store($paymentData, $purchaseOrderId = null)
    {
        try {
            Log::info('Payment_MethodController::store() - Processing payment method', [
                'method_name' => $paymentData['method_name'],
                'amount' => $paymentData['amount'],
                'purchase_order_id' => $purchaseOrderId
            ]);

            // Create using Eloquent ORM
            $paymentMethod = Payment_Method::create([
                'customer_purchase_order_id' => $purchaseOrderId,
                'method_name' => $paymentData['method_name'],
                'payment_date' => now()->toDateString(),
                'amount' => $paymentData['amount']
            ]);

            Log::info('✓ PAYMENT_METHOD CREATED', [
                'id' => $paymentMethod->id,
                'customer_purchase_order_id' => $paymentMethod->customer_purchase_order_id,
                'method_name' => $paymentMethod->method_name,
                'amount' => $paymentMethod->amount
            ]);

            return [
                'success' => true,
                'message' => 'Payment method created successfully',
                'payment_method' => $paymentMethod
            ];
        } catch (\Exception $e) {
            Log::error('Payment_MethodController::store() error:', [
                'exception' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return [
                'success' => false,
                'message' => 'Error creating payment method: ' . $e->getMessage()
            ];
        }
    }
}
