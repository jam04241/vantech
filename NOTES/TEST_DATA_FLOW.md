# Test Data Flow - From Display to Database

## How the System Works

### Step 1: Item Display (purchaseOrderList - Line 57)
```html
<li data-product-id="10" 
    data-unit-price="45000" 
    data-quantity="1" 
    data-total-price="45000">
    <!-- Visual display of item -->
</li>
```

✅ **Source:** item_list.blade.php lines 270-275
✅ **Data Attributes:** All present and correct

---

### Step 2: Form Submission (handleCheckout - Line 299)
When user clicks "Print Receipt", `handleCheckout()` does:

```javascript
// 1. Extract data from DOM
const purchaseOrderListItems = document.querySelectorAll('#purchaseOrderList li');

// 2. For each item, create hidden form inputs
purchaseOrderListItems.forEach((li, index) => {
    const productId = li.getAttribute('data-product-id');
    const unitPrice = li.getAttribute('data-unit-price');
    const quantity = li.getAttribute('data-quantity');
    const totalPrice = li.getAttribute('data-total-price');
    
    // 3. Create hidden inputs
    const inputs = [
        { name: `items[${index}][product_id]`, value: productId },
        { name: `items[${index}][unit_price]`, value: unitPrice },
        { name: `items[${index}][quantity]`, value: quantity },
        { name: `items[${index}][total_price]`, value: totalPrice }
    ];
});

// 4. Submit form via AJAX
fetch('{{ route("checkout.store") }}', {
    method: 'POST',
    body: formData,
    headers: { 'Accept': 'application/json' }
});
```

✅ **Location:** purchaseFrame.blade.php lines 380-423
✅ **Hidden Inputs Container:** Line 164 (`id="itemsContainer"`)

---

### Step 3: Backend Receives Data (CheckoutController)
```php
// POST /api/checkout receives:
$request->input('customer_id')        // "5"
$request->input('payment_method')     // "Cash"
$request->input('amount')             // "46350.00"
$request->input('items')              // Array of items
// items[0][product_id] = 10
// items[0][unit_price] = 45000
// items[0][quantity] = 1
// items[0][total_price] = 45000
```

✅ **Route:** POST /api/checkout (web.php line 137)
✅ **Validation:** CheckoutRequest (validates all fields)

---

### Step 4: Database Storage
```php
// CheckoutController calls:
Customer_Purchase_OrderController::store($items, $customerId)
// Creates record:
// INSERT INTO customer_purchase_orders 
// (product_id, customer_id, quantity, unit_price, total_price, order_date, status)
// VALUES (10, 5, 1, 45000, 45000, '2025-11-26', 'Success')

Payment_MethodController::store($paymentData, $purchaseOrderId)
// Creates record:
// INSERT INTO payment_methods 
// (customer_purchase_order_id, method_name, payment_date, amount)
// VALUES (1, 'Cash', '2025-11-26', 46350)
```

✅ **Tables:** customer_purchase_orders, payment_methods
✅ **Models:** Have correct fillable attributes

---

## Verification Checklist

### ✅ Display Layer (purchaseOrderList - Line 57)
- [x] `<li>` elements have `data-product-id`
- [x] `<li>` elements have `data-unit-price`
- [x] `<li>` elements have `data-quantity`
- [x] `<li>` elements have `data-total-price`

### ✅ Form Layer (itemsContainer - Line 164)
- [x] Hidden inputs created dynamically
- [x] Inputs named `items[0][product_id]`, etc.
- [x] Values extracted from `data-*` attributes
- [x] Container appended to form before submission

### ✅ Submission Layer (handleCheckout - Line 299)
- [x] Extracts data from DOM `<li>` elements
- [x] Creates hidden form inputs
- [x] Submits via AJAX to `/api/checkout`
- [x] Includes CSRF token via `@csrf`

### ✅ Backend Layer (CheckoutController)
- [x] Receives POST request
- [x] Validates using CheckoutRequest
- [x] Delegates to Customer_Purchase_OrderController
- [x] Delegates to Payment_MethodController
- [x] Stores in database

### ✅ Database Layer
- [x] customer_purchase_orders table exists
- [x] payment_methods table exists
- [x] Models have correct table names
- [x] Models have correct fillable attributes

---

## How to Test

### Test 1: Check if data attributes are set
```javascript
// In browser console:
const items = document.querySelectorAll('#purchaseOrderList li');
items.forEach(li => {
    console.log({
        product_id: li.getAttribute('data-product-id'),
        unit_price: li.getAttribute('data-unit-price'),
        quantity: li.getAttribute('data-quantity'),
        total_price: li.getAttribute('data-total-price')
    });
});
```

**Expected:** Each item logs its data attributes

### Test 2: Check if hidden inputs are created
```javascript
// In browser console, after clicking "Print Receipt":
const hiddenInputs = document.querySelectorAll('#itemsContainer input');
hiddenInputs.forEach(input => {
    console.log(`${input.name} = ${input.value}`);
});
```

**Expected:** See all items[0][product_id], items[0][unit_price], etc.

### Test 3: Check if form data is sent
```javascript
// In browser console, open Network tab
// Click "Print Receipt"
// Look for POST request to /api/checkout
// Check Request Payload for items data
```

**Expected:** See FormData with items[0][product_id], items[0][unit_price], etc.

### Test 4: Check database
```sql
SELECT * FROM customer_purchase_orders ORDER BY id DESC LIMIT 1;
SELECT * FROM payment_methods ORDER BY id DESC LIMIT 1;
```

**Expected:** New records with correct data

---

## If Data Doesn't Store

### Check 1: Are data attributes being set?
```javascript
// In console after scanning product:
const li = document.querySelector('#purchaseOrderList li');
console.log(li.getAttribute('data-product-id'));
```

If empty → Problem in item_list.blade.php updateOrderDisplay()

### Check 2: Are hidden inputs being created?
```javascript
// In console after clicking "Print Receipt":
const container = document.getElementById('itemsContainer');
console.log(container.innerHTML);
```

If empty → Problem in purchaseFrame.blade.php handleCheckout()

### Check 3: Is form being submitted?
```javascript
// In console, look for:
// "Processing timer completed, submitting form via AJAX..."
// "Form data being sent:"
```

If not showing → Problem in handleCheckout() validation

### Check 4: Is backend receiving data?
Check Laravel logs: `storage/logs/laravel.log`

Look for:
```
=== CHECKOUT ORCHESTRATION STARTED ===
=== VALIDATION PASSED ===
✓ Purchase orders created successfully
✓ Payment method created successfully
```

If not showing → Problem in CheckoutController or routes

---

## Summary

The data flow is:
1. **Display** (purchaseOrderList) → Shows items with data-* attributes
2. **Form** (itemsContainer) → Creates hidden inputs from data-* attributes
3. **Submission** (handleCheckout) → Sends form via AJAX
4. **Backend** (CheckoutController) → Receives and validates data
5. **Database** → Stores in customer_purchase_orders and payment_methods

All components are in place. If data isn't storing, one of these steps is failing.

Use the tests above to identify which step is broken.
