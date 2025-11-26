# Checkout Data Storage - Debugging Guide

## Quick Diagnosis Steps

### Step 1: Click "Debug Checkout" Button
1. Open POS system
2. Scan at least 1 product
3. Select customer
4. Select payment method
5. Enter amount
6. **Click the RED "Debug Checkout" button**

### Step 2: Check Browser Console (F12)
Look for:
```
=== DEBUG CHECKOUT ===
Sending to: /debug/checkout-test
Form data:
  _token: xxx
  customer_id: 5
  payment_method: Cash
  amount: 100.00
  items[0][product_id]: 10
  items[0][serial_number]: SN123
  items[0][unit_price]: 100
  items[0][quantity]: 1
  items[0][total_price]: 100
✓ Debug response: {...}
```

### Step 3: Check Laravel Logs
```bash
# In terminal, view latest logs
tail -f storage/logs/laravel.log
```

Look for:
```
=== DEBUG CHECKOUT TEST ===
{
  "timestamp": "2025-11-26 12:50:00",
  "all_request_data": {...},
  "customer_id": "5",
  "payment_method": "Cash",
  "amount": "100.00",
  "items_count": 1,
  "items": [...]
}
```

---

## What Each Button Does

### Test Data Button (Yellow)
- Sends form data to `/debug/test-checkout`
- Shows if basic form submission works
- Doesn't validate or save data

### Debug Checkout Button (Red)
- Sends form data to `/debug/checkout-test`
- Shows exactly what data is being sent
- Logs to Laravel logs
- Doesn't validate or save data

### Print Receipt Button (Blue)
- Sends form data to `/api/checkout`
- Validates using CheckoutRequest
- Creates purchase orders
- Creates payment method
- Saves to database
- Redirects to receipt

---

## Troubleshooting

### Issue 1: Debug Button Shows Error
**Error:** "Debug Failed"

**Solution:**
1. Check if form has all required fields:
   - Customer name filled
   - Customer ID set (check DevTools → Elements → find `<input id="customerId">`)
   - Payment method selected
   - Amount entered
   - At least 1 item in order

2. Check browser console for specific error message

### Issue 2: Debug Shows Data But Print Receipt Fails
**Symptoms:**
- Debug Checkout works fine
- Print Receipt shows error

**Causes:**
1. **Validation Error** - Check Laravel logs for validation errors
2. **Customer doesn't exist** - Verify customer ID is valid
3. **Product doesn't exist** - Verify product IDs are valid
4. **ORM Issue** - Check if models are configured correctly

**Solution:**
1. Check Laravel logs: `tail -f storage/logs/laravel.log`
2. Look for error messages starting with "❌"
3. Check database:
   ```sql
   SELECT * FROM customers WHERE id = 5;
   SELECT * FROM products WHERE id = 10;
   ```

### Issue 3: Data Sent But Not Saved to Database
**Symptoms:**
- Print Receipt redirects successfully
- No data in database tables

**Causes:**
1. **Controllers not being called** - Check logs for "Delegating to..."
2. **Transaction rolled back** - Check logs for "ROLLBACK"
3. **Silent validation failure** - Check logs for validation errors
4. **ORM fillable issue** - Check if all fields are in `$fillable`

**Solution:**
1. Check Laravel logs for full error trace
2. Verify models have correct `$fillable` attributes:
   ```php
   // Customer_Purchase_Order.php
   protected $fillable = [
       'product_id',
       'customer_id',
       'quantity',
       'unit_price',
       'total_price',
       'order_date',
       'status',
   ];
   
   // Payment_Method.php
   protected $fillable = [
       'customer_purchase_order_id',
       'method_name',
       'payment_date',
       'amount',
   ];
   ```

### Issue 4: CSRF Token Error
**Error:** "419 Page Expired" or "CSRF token mismatch"

**Cause:** Form doesn't have CSRF token or it's expired

**Solution:**
1. Verify form has `@csrf` directive:
   ```blade
   <form id="checkoutForm" action="{{ route('checkout.store') }}" method="POST">
       @csrf
       ...
   </form>
   ```

2. Check if FormData includes CSRF token:
   - Open DevTools → Network tab
   - Click "Print Receipt"
   - Find POST request to `/api/checkout`
   - Check if `_token` parameter is included

### Issue 5: JSON Response Error
**Error:** "Unexpected token < in JSON at position 0"

**Cause:** Server returned HTML instead of JSON (likely an error page)

**Solution:**
1. Check Laravel logs for PHP errors
2. Check if route exists: `POST /api/checkout`
3. Check if CheckoutController exists and has `store` method

---

## Database Verification

### Check if Data is Being Saved

```sql
-- Check purchase orders
SELECT * FROM customer_purchase_orders ORDER BY created_at DESC LIMIT 5;

-- Check payment methods
SELECT * FROM payment_methods ORDER BY created_at DESC LIMIT 5;

-- Check counts
SELECT COUNT(*) as total_orders FROM customer_purchase_orders;
SELECT COUNT(*) as total_payments FROM payment_methods;
```

### Check Relationships

```sql
-- Verify foreign key relationships
SELECT 
    cpo.id,
    cpo.product_id,
    cpo.customer_id,
    pm.id as payment_id,
    pm.customer_purchase_order_id
FROM customer_purchase_orders cpo
LEFT JOIN payment_methods pm ON pm.customer_purchase_order_id = cpo.id
ORDER BY cpo.created_at DESC
LIMIT 5;
```

---

## Log File Analysis

### Location
```
storage/logs/laravel.log
```

### Key Log Entries to Look For

#### Success Flow
```
=== CHECKOUT REQUEST RECEIVED ===
=== VALIDATION PASSED ===
📋 STEP 1️⃣ - Delegating to Customer_Purchase_OrderController::store()...
✓ Purchase orders created successfully
💳 STEP 2️⃣ - Delegating to Payment_MethodController::store()...
✓ Payment method created successfully
=== CHECKOUT ORCHESTRATION COMPLETED ===
```

#### Failure Points
```
❌ VALIDATION ERROR ===
❌ CHECKOUT ERROR ===
Failed to create purchase order
Failed to create payment method
```

### How to Read Logs

```bash
# View last 50 lines
tail -50 storage/logs/laravel.log

# Follow logs in real-time
tail -f storage/logs/laravel.log

# Search for errors
grep "❌" storage/logs/laravel.log

# Search for specific checkout
grep "CHECKOUT REQUEST" storage/logs/laravel.log
```

---

## Testing Checklist

- [ ] **Step 1:** Open POS system
- [ ] **Step 2:** Scan at least 1 product (should appear in order list)
- [ ] **Step 3:** Select customer from dropdown
- [ ] **Step 4:** Verify customer ID is set (DevTools → Elements)
- [ ] **Step 5:** Select payment method
- [ ] **Step 6:** Enter amount
- [ ] **Step 7:** Click "Debug Checkout" button
- [ ] **Step 8:** Check browser console for success message
- [ ] **Step 9:** Check Laravel logs for debug output
- [ ] **Step 10:** Click "Print Receipt" button
- [ ] **Step 11:** Check browser console for success or error
- [ ] **Step 12:** Check Laravel logs for checkout flow
- [ ] **Step 13:** Check database for new records
- [ ] **Step 14:** Verify payment is linked to purchase order

---

## Common Issues & Solutions

| Issue | Cause | Solution |
|-------|-------|----------|
| Data not saving | Controllers not called | Check logs for "Delegating to..." |
| Validation fails | Missing required fields | Ensure customer_id, items, payment_method, amount are set |
| CSRF error | Token missing or expired | Verify `@csrf` in form |
| JSON error | Server error | Check Laravel logs for PHP errors |
| Customer not found | Invalid customer ID | Verify customer exists in database |
| Product not found | Invalid product ID | Verify product exists in database |
| Redirect loop | Receipt page error | Check if `/Receipt/Purchase` route exists |
| Silent failure | ORM issue | Check model `$fillable` attributes |

---

## Next Steps

1. **Click "Debug Checkout" button** and check console
2. **Check Laravel logs** for any errors
3. **Verify database** for new records
4. **If still failing:** Share the error message from logs

The debug buttons will help pinpoint exactly where the issue is!
