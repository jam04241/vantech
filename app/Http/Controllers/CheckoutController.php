<?php

namespace App\Http\Controllers;

use App\Http\Requests\CheckoutRequest;
use App\Services\CheckoutService;
use Illuminate\Support\Facades\Log;

class CheckoutController extends Controller
{
    private $checkoutService;

    public function __construct(CheckoutService $checkoutService)
    {
        $this->checkoutService = $checkoutService;
    }

    /**
     * Store checkout - delegates to CheckoutService
     * 
     * Step 1: Validate checkout data using CheckoutRequest
     * Step 2: Delegate to CheckoutService::processCheckout()
     * Step 3: Redirect to receipt or show error
     */
    public function store(CheckoutRequest $request)
    {
        // Validate data
        $validated = $request->validated();

        Log::info('CheckoutController::store() - Request received', [
            'customer_id' => $validated['customer_id'],
            'items_count' => count($validated['items']),
            'payment_method' => $validated['payment_method'],
            'amount' => $validated['amount']
        ]);

        // Delegate to service
        $result = $this->checkoutService->processCheckout($validated);

        if (!$result['success']) {
            Log::error('CheckoutController::store() - Checkout failed', [
                'error' => $result['message']
            ]);
            return back()->withErrors(['error' => $result['message']]);
        }

        Log::info('CheckoutController::store() - Checkout successful', [
            'purchase_orders_count' => count($result['purchase_orders']),
            'payment_method_id' => $result['payment_method']->id
        ]);

        // Redirect to receipt page with success message
        return redirect()->route('invetory')->with('success', 'Order processed successfully!');
    }
}
