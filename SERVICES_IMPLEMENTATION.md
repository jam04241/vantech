# Services Implementation - Complete Guide

## What Was Added

### New File: `app/Services/CheckoutService.php`

A service class that handles all checkout business logic:
- Creating purchase orders
- Creating payment methods
- Logging each step
- Error handling

### Updated File: `app/Http/Controllers/CheckoutController.php`

Now uses the service instead of doing logic directly:
- Receives request
- Validates data
- Delegates to service
- Handles response

---

## File Structure

```
app/
├── Http/
│   ├── Controllers/
│   │   ├── CheckoutController.php (UPDATED - now uses service)
│   │   ├── Customer_Purchase_OrderController.php
│   │   └── Payment_MethodController.php
│   └── Requests/
│       └── CheckoutRequest.php (validates checkout data)
│
├── Services/                          ← NEW FOLDER
│   └── CheckoutService.php            ← NEW FILE
│       ├── processCheckout()          - Main method
│       ├── createPurchaseOrders()     - Creates purchase orders
│       └── createPaymentMethod()      - Creates payment method
│
├── Models/
│   ├── Customer_Purchase_Order.php
│   └── Payment_Method.php
│
└── database/
    └── migrations/
        ├── 2025_11_22_042217_customer_purchase_order.php
        └── 2025_11_22_062000_create_payment_methods_table.php
```

---

## CheckoutService.php - Complete Code

```php
<?php

namespace App\Services;

use App\Models\Customer_Purchase_Order;
use App\Models\Payment_Method;
use Illuminate\Support\Facades\Log;

class CheckoutService
{
    /**
     * Process complete checkout
     * 
     * @param array $validated - Validated checkout data
     * @return array - Result with success status and data
     */
    public function processCheckout(array $validated)
    {
        try {
            Log::info('=== CHECKOUT SERVICE STARTED ===', [
                'customer_id' => $validated['customer_id'],
                'items_count' => count($validated['items']),
                'payment_method' => $validated['payment_method'],
                'amount' => $validated['amount']
            ]);

            // Step 1: Create purchase orders
            $purchaseOrders = $this->createPurchaseOrders(
                $validated['items'],
                $validated['customer_id']
            );

            if (empty($purchaseOrders)) {
                throw new \Exception('No purchase orders were created');
            }

            Log::info('✓ Purchase orders created', [
                'count' => count($purchaseOrders),
                'ids' => array_map(fn($po) => $po->id, $purchaseOrders)
            ]);

            // Step 2: Create payment method
            $paymentMethod = $this->createPaymentMethod(
                $purchaseOrders[0]->id,
                $validated['payment_method'],
                $validated['amount']
            );

            Log::info('✓ Payment method created', [
                'id' => $paymentMethod->id,
                'customer_purchase_order_id' => $paymentMethod->customer_purchase_order_id
            ]);

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
            Log::error('❌ CHECKOUT SERVICE ERROR ===', [
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

    /**
     * Create purchase orders for each item
     */
    private function createPurchaseOrders(array $items, int $customerId)
    {
        $purchaseOrders = [];

        foreach ($items as $index => $item) {
            try {
                $itemNumber = $index + 1;
                $totalItems = count($items);
                Log::info("Creating purchase order {$itemNumber}/{$totalItems}", [
                    'product_id' => $item['product_id'],
                    'customer_id' => $customerId,
                    'quantity' => $item['quantity'],
                    'unit_price' => $item['unit_price'],
                    'total_price' => $item['total_price']
                ]);

                $purchaseOrder = Customer_Purchase_Order::create([
                    'product_id' => $item['product_id'],
                    'customer_id' => $customerId,
                    'quantity' => $item['quantity'],
                    'unit_price' => $item['unit_price'],
                    'total_price' => $item['total_price'],
                    'order_date' => now()->toDateString(),
                    'status' => 'Success'
                ]);

                Log::info("✓ Purchase order {$itemNumber} created", [
                    'id' => $purchaseOrder->id,
                    'product_id' => $purchaseOrder->product_id,
                    'customer_id' => $purchaseOrder->customer_id
                ]);

                $purchaseOrders[] = $purchaseOrder;
            } catch (\Exception $e) {
                $itemNumber = $index + 1;
                Log::error("❌ Failed to create purchase order {$itemNumber}", [
                    'exception' => $e->getMessage(),
                    'item' => $item
                ]);
                throw $e;
            }
        }

        return $purchaseOrders;
    }

    /**
     * Create payment method linked to purchase order
     */
    private function createPaymentMethod(int $purchaseOrderId, string $methodName, float $amount)
    {
        try {
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

            Log::info('✓ Payment method created', [
                'id' => $paymentMethod->id,
                'customer_purchase_order_id' => $paymentMethod->customer_purchase_order_id,
                'method_name' => $paymentMethod->method_name,
                'amount' => $paymentMethod->amount
            ]);

            return $paymentMethod;
        } catch (\Exception $e) {
            Log::error('❌ Failed to create payment method', [
                'exception' => $e->getMessage(),
                'purchase_order_id' => $purchaseOrderId
            ]);
            throw $e;
        }
    }
}
```

---

## CheckoutController.php - Updated Code

```php
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
```

---

## Why Services?

### Before (Logic in Controller)
```php
// CheckoutController - 50+ lines of logic
public function store(CheckoutRequest $request)
{
    $validated = $request->validated();
    
    // Create purchase orders
    foreach ($validated['items'] as $item) {
        Customer_Purchase_Order::create([...]);
    }
    
    // Create payment method
    Payment_Method::create([...]);
    
    return redirect(...);
}
```

**Problems:**
- Hard to test
- Hard to reuse
- Hard to debug
- Mixed concerns

### After (Logic in Service)
```php
// CheckoutController - Clean and simple
public function store(CheckoutRequest $request)
{
    $validated = $request->validated();
    $result = $this->checkoutService->processCheckout($validated);
    
    if (!$result['success']) {
        return back()->withErrors(['error' => $result['message']]);
    }
    
    return redirect(...);
}

// CheckoutService - Business logic
public function processCheckout(array $validated)
{
    $purchaseOrders = $this->createPurchaseOrders(...);
    $paymentMethod = $this->createPaymentMethod(...);
    return [...];
}
```

**Benefits:**
- Easy to test
- Easy to reuse
- Easy to debug
- Clear separation of concerns

---

## How to Use

### 1. Service is Auto-Injected

```php
public function __construct(CheckoutService $checkoutService)
{
    $this->checkoutService = $checkoutService;
}
```

Laravel automatically creates the service and injects it.

### 2. Call Service Method

```php
$result = $this->checkoutService->processCheckout($validated);
```

### 3. Handle Result

```php
if ($result['success']) {
    // Success
    $purchaseOrders = $result['purchase_orders'];
    $paymentMethod = $result['payment_method'];
} else {
    // Error
    $error = $result['message'];
}
```

---

## Testing the Service

### Manual Test

```php
// In tinker or test
$service = app(CheckoutService::class);

$data = [
    'customer_id' => 1,
    'payment_method' => 'Cash',
    'amount' => 5000,
    'items' => [
        ['product_id' => 1, 'quantity' => 1, 'unit_price' => 1000, 'total_price' => 1000],
        ['product_id' => 2, 'quantity' => 2, 'unit_price' => 2000, 'total_price' => 4000]
    ]
];

$result = $service->processCheckout($data);

if ($result['success']) {
    echo "Success! Created " . count($result['purchase_orders']) . " purchase orders";
} else {
    echo "Error: " . $result['message'];
}
```

---

## Debugging with Services

### Check Logs

```bash
tail -f storage/logs/laravel.log
```

You'll see:
```
CheckoutController::store() - Request received
=== CHECKOUT SERVICE STARTED ===
Creating purchase order 1/2
✓ Purchase order 1 created
Creating purchase order 2/2
✓ Purchase order 2 created
✓ Purchase orders created
Creating payment method
✓ Payment method created
=== CHECKOUT SERVICE COMPLETED ===
CheckoutController::store() - Checkout successful
```

### If Error Occurs

```
❌ CHECKOUT SERVICE ERROR
Exception: Foreign key constraint fails
```

You know exactly where it failed!

---

## Summary

✅ **Service Layer** - Business logic separated from controller
✅ **Easy to Debug** - Detailed logging at each step
✅ **Easy to Test** - Can test service independently
✅ **Easy to Maintain** - Clear structure and responsibilities
✅ **Easy to Extend** - Can add more methods to service

The service architecture makes your checkout system professional and maintainable!
