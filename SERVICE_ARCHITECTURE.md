# Service Architecture - Checkout System

## Overview

The checkout system now uses a **Service Layer** to separate business logic from controllers, making it easier to debug and maintain.

---

## Architecture Flow

```
┌─────────────────────────────────────────────────────────────────┐
│ FRONTEND: purchaseFrame.blade.php                               │
│ User fills checkout form and clicks "Print Receipt"             │
└────────────────────────┬────────────────────────────────────────┘
                         │
                         ↓ POST /api/checkout
┌─────────────────────────────────────────────────────────────────┐
│ CONTROLLER: CheckoutController::store()                         │
│ ✓ Receives request                                              │
│ ✓ Validates using CheckoutRequest                              │
│ ✓ Logs request received                                         │
│ ✓ Delegates to CheckoutService                                 │
│ ✓ Handles response (success/error)                             │
│ ✓ Redirects to receipt or shows error                          │
└────────────────────────┬────────────────────────────────────────┘
                         │
                         ↓ Calls processCheckout()
┌─────────────────────────────────────────────────────────────────┐
│ SERVICE: CheckoutService::processCheckout()                     │
│ ✓ Orchestrates checkout process                                │
│ ✓ Calls createPurchaseOrders()                                 │
│ ✓ Calls createPaymentMethod()                                  │
│ ✓ Logs all steps                                               │
│ ✓ Returns result with success status                           │
└────────────────────────┬────────────────────────────────────────┘
                         │
        ┌────────────────┴────────────────┐
        │                                 │
        ↓                                 ↓
┌──────────────────────┐      ┌──────────────────────┐
│ createPurchaseOrders │      │ createPaymentMethod  │
│ ✓ Loop through items │      │ ✓ Create payment     │
│ ✓ Create each order  │      │ ✓ Link to purchase   │
│ ✓ Log each creation  │      │ ✓ Log creation       │
│ ✓ Return array       │      │ ✓ Return model       │
└──────────┬───────────┘      └──────────┬───────────┘
           │                             │
           ↓                             ↓
┌──────────────────────────────────────────────────┐
│ DATABASE                                         │
│ ✓ customer_purchase_orders (3 records)          │
│ ✓ payment_methods (1 record)                    │
└──────────────────────────────────────────────────┘
```

---

## File Structure

```
app/
├── Http/
│   ├── Controllers/
│   │   └── CheckoutController.php (THIN - delegates to service)
│   └── Requests/
│       └── CheckoutRequest.php (validates data)
├── Services/
│   └── CheckoutService.php (BUSINESS LOGIC - creates data)
├── Models/
│   ├── Customer_Purchase_Order.php
│   └── Payment_Method.php
└── database/
    └── migrations/
        ├── 2025_11_22_042217_customer_purchase_order.php
        └── 2025_11_22_062000_create_payment_methods_table.php
```

---

## Data Flow with Logging

### Step 1: Frontend Sends Data

```javascript
// purchaseFrame.blade.php - handleCheckout()
POST /api/checkout
{
  customer_id: 5,
  payment_method: "Cash",
  amount: 5000,
  items: [
    { product_id: 10, quantity: 1, unit_price: 1000, total_price: 1000 },
    { product_id: 15, quantity: 2, unit_price: 1500, total_price: 3000 }
  ]
}
```

### Step 2: CheckoutController Receives Request

```php
// CheckoutController::store()
Log::info('CheckoutController::store() - Request received', [
    'customer_id' => 5,
    'items_count' => 2,
    'payment_method' => 'Cash',
    'amount' => 5000
]);
```

**Check Laravel logs:** `storage/logs/laravel.log`

### Step 3: CheckoutService Starts Processing

```php
// CheckoutService::processCheckout()
Log::info('=== CHECKOUT SERVICE STARTED ===', [
    'customer_id' => 5,
    'items_count' => 2,
    'payment_method' => 'Cash',
    'amount' => 5000
]);
```

### Step 4: Create Purchase Orders

```php
// CheckoutService::createPurchaseOrders()
Log::info('Creating purchase order 1/2', [
    'product_id' => 10,
    'customer_id' => 5,
    'quantity' => 1,
    'unit_price' => 1000,
    'total_price' => 1000
]);

// After creation
Log::info('✓ Purchase order 1 created', [
    'id' => 1,
    'product_id' => 10,
    'customer_id' => 5
]);

Log::info('Creating purchase order 2/2', [
    'product_id' => 15,
    'customer_id' => 5,
    'quantity' => 2,
    'unit_price' => 1500,
    'total_price' => 3000
]);

Log::info('✓ Purchase order 2 created', [
    'id' => 2,
    'product_id' => 15,
    'customer_id' => 5
]);

Log::info('✓ Purchase orders created', [
    'count' => 2,
    'ids' => [1, 2]
]);
```

### Step 5: Create Payment Method

```php
// CheckoutService::createPaymentMethod()
Log::info('Creating payment method', [
    'customer_purchase_order_id' => 1,
    'method_name' => 'Cash',
    'amount' => 5000
]);

// After creation
Log::info('✓ Payment method created', [
    'id' => 1,
    'customer_purchase_order_id' => 1,
    'method_name' => 'Cash',
    'amount' => 5000
]);
```

### Step 6: Service Completes

```php
// CheckoutService::processCheckout()
Log::info('=== CHECKOUT SERVICE COMPLETED ===', [
    'purchase_orders_count' => 2,
    'payment_method_id' => 1
]);
```

### Step 7: Controller Handles Response

```php
// CheckoutController::store()
Log::info('CheckoutController::store() - Checkout successful', [
    'purchase_orders_count' => 2,
    'payment_method_id' => 1
]);

// Redirect
return redirect()->route('invetory')->with('success', 'Order processed successfully!');
```

---

## Debugging Guide

### If Data Isn't Storing

**Step 1: Check Laravel Logs**
```bash
tail -f storage/logs/laravel.log
```

Look for:
- `CheckoutController::store() - Request received` ✓
- `=== CHECKOUT SERVICE STARTED ===` ✓
- `Creating purchase order 1/X` ✓
- `✓ Purchase order 1 created` ✓
- `Creating payment method` ✓
- `✓ Payment method created` ✓
- `=== CHECKOUT SERVICE COMPLETED ===` ✓

**Step 2: If You See Error Logs**

Look for:
- `❌ CHECKOUT SERVICE ERROR` - Service failed
- `❌ Failed to create purchase order X` - Item creation failed
- `❌ Failed to create payment method` - Payment creation failed

**Step 3: Check Database**

```sql
-- Check if records exist
SELECT * FROM customer_purchase_orders ORDER BY id DESC LIMIT 5;
SELECT * FROM payment_methods ORDER BY id DESC LIMIT 5;

-- Check foreign key relationships
SELECT * FROM customer_purchase_orders WHERE id = 1;
SELECT * FROM payment_methods WHERE customer_purchase_order_id = 1;
```

---

## Service Methods

### CheckoutService::processCheckout(array $validated)

**Input:**
```php
[
    'customer_id' => 5,
    'payment_method' => 'Cash',
    'amount' => 5000,
    'items' => [
        ['product_id' => 10, 'quantity' => 1, 'unit_price' => 1000, 'total_price' => 1000],
        ['product_id' => 15, 'quantity' => 2, 'unit_price' => 1500, 'total_price' => 3000]
    ]
]
```

**Output:**
```php
[
    'success' => true,
    'message' => 'Checkout processed successfully',
    'purchase_orders' => [
        Customer_Purchase_Order(id=1, product_id=10, ...),
        Customer_Purchase_Order(id=2, product_id=15, ...)
    ],
    'payment_method' => Payment_Method(id=1, customer_purchase_order_id=1, ...)
]
```

### CheckoutService::createPurchaseOrders(array $items, int $customerId)

**Input:**
```php
[
    ['product_id' => 10, 'quantity' => 1, 'unit_price' => 1000, 'total_price' => 1000],
    ['product_id' => 15, 'quantity' => 2, 'unit_price' => 1500, 'total_price' => 3000]
],
5
```

**Output:**
```php
[
    Customer_Purchase_Order(id=1, product_id=10, customer_id=5, ...),
    Customer_Purchase_Order(id=2, product_id=15, customer_id=5, ...)
]
```

### CheckoutService::createPaymentMethod(int $purchaseOrderId, string $methodName, float $amount)

**Input:**
```php
1,
'Cash',
5000
```

**Output:**
```php
Payment_Method(id=1, customer_purchase_order_id=1, method_name='Cash', amount=5000)
```

---

## Error Handling

### If Purchase Order Creation Fails

```php
// In createPurchaseOrders()
Log::error('❌ Failed to create purchase order 1', [
    'exception' => 'Foreign key constraint fails',
    'item' => ['product_id' => 999, ...]
]);

// Throws exception
// Service catches it and returns error
return [
    'success' => false,
    'message' => 'Checkout failed: Foreign key constraint fails',
    'error' => 'Foreign key constraint fails'
];

// Controller catches it
return back()->withErrors(['error' => 'Foreign key constraint fails']);
```

### If Payment Method Creation Fails

```php
// In createPaymentMethod()
Log::error('❌ Failed to create payment method', [
    'exception' => 'Foreign key constraint fails',
    'purchase_order_id' => 999
]);

// Throws exception
// Service catches it and returns error
return [
    'success' => false,
    'message' => 'Checkout failed: Foreign key constraint fails',
    'error' => 'Foreign key constraint fails'
];

// Controller catches it
return back()->withErrors(['error' => 'Foreign key constraint fails']);
```

---

## Testing Checklist

- [ ] Open Laravel logs: `tail -f storage/logs/laravel.log`
- [ ] Add items to order (scan barcodes)
- [ ] Select customer
- [ ] Select payment method
- [ ] Enter amount
- [ ] Click "Print Receipt"
- [ ] Check logs for all steps
- [ ] Check database for records
- [ ] Verify foreign key relationships
- [ ] Check if redirected to receipt page

---

## Summary

✅ **Service Layer** - Business logic separated from controller
✅ **Clear Logging** - Every step logged for debugging
✅ **Error Handling** - Exceptions caught and logged
✅ **Database Storage** - Purchase orders and payment methods created
✅ **Foreign Keys** - Payment method linked to purchase order
✅ **Easy to Debug** - Follow logs to find where data gets stuck

The service architecture makes it easy to see exactly where data is being created or where it fails!
