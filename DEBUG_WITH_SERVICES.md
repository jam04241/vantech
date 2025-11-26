# Debug Checkout with Services - Quick Guide

## New Architecture

```
purchaseFrame.blade.php (Frontend)
  ↓ POST /api/checkout
CheckoutController (Thin - just delegates)
  ↓ Calls processCheckout()
CheckoutService (Business Logic - creates data)
  ├─ createPurchaseOrders() - Creates customer_purchase_orders records
  └─ createPaymentMethod() - Creates payment_methods record
  ↓
Database (customer_purchase_orders + payment_methods)
```

---

## Step-by-Step Debug

### 1. Open Laravel Logs

```bash
# In terminal, watch logs in real-time
tail -f storage/logs/laravel.log
```

### 2. Try Checkout

In POS system:
1. Scan 2-3 products
2. Select customer
3. Select payment method
4. Enter amount
5. Click "Print Receipt"

### 3. Watch Logs Appear

You should see logs like:

```
[2025-11-26 14:00:00] local.INFO: CheckoutController::store() - Request received
[2025-11-26 14:00:00] local.INFO: === CHECKOUT SERVICE STARTED ===
[2025-11-26 14:00:00] local.INFO: Creating purchase order 1/2
[2025-11-26 14:00:00] local.INFO: ✓ Purchase order 1 created
[2025-11-26 14:00:00] local.INFO: Creating purchase order 2/2
[2025-11-26 14:00:00] local.INFO: ✓ Purchase order 2 created
[2025-11-26 14:00:00] local.INFO: ✓ Purchase orders created
[2025-11-26 14:00:00] local.INFO: Creating payment method
[2025-11-26 14:00:00] local.INFO: ✓ Payment method created
[2025-11-26 14:00:00] local.INFO: === CHECKOUT SERVICE COMPLETED ===
[2025-11-26 14:00:00] local.INFO: CheckoutController::store() - Checkout successful
```

### 4. Check Database

```sql
-- Check if records were created
SELECT * FROM customer_purchase_orders ORDER BY id DESC LIMIT 5;
SELECT * FROM payment_methods ORDER BY id DESC LIMIT 5;

-- Verify foreign key relationship
SELECT 
    po.id as purchase_order_id,
    po.product_id,
    po.customer_id,
    pm.id as payment_method_id,
    pm.customer_purchase_order_id
FROM customer_purchase_orders po
LEFT JOIN payment_methods pm ON pm.customer_purchase_order_id = po.id
ORDER BY po.id DESC LIMIT 5;
```

---

## If Data Isn't Storing

### Check 1: Do You See Logs?

**If YES logs appear:**
- Data is being processed
- Check database for records
- If no records, there's a database issue

**If NO logs appear:**
- Request isn't reaching controller
- Check browser Network tab
- Check if form is submitting

### Check 2: Do You See Errors?

Look for error logs like:

```
❌ CHECKOUT SERVICE ERROR
❌ Failed to create purchase order
❌ Failed to create payment method
```

**If you see errors:**
- Copy the error message
- Check what field is failing
- Verify that field has correct data

### Check 3: Common Errors

#### Error: "Foreign key constraint fails"
```
Cause: product_id or customer_id doesn't exist
Fix: Verify product and customer exist in database
```

#### Error: "Column not found"
```
Cause: Table structure is wrong
Fix: Run migrations again: php artisan migrate
```

#### Error: "No purchase orders were created"
```
Cause: Items array is empty
Fix: Verify items are being sent from frontend
```

---

## Log Locations

### Laravel Logs
```
storage/logs/laravel.log
```

### Browser Console
```
F12 → Console tab
Look for: "=== CHECKOUT PROCESS STARTED ==="
```

### Browser Network Tab
```
F12 → Network tab
Look for: POST /api/checkout
Check Response for errors
```

---

## Database Verification

### Check Tables Exist
```sql
SHOW TABLES LIKE 'customer_purchase_orders';
SHOW TABLES LIKE 'payment_methods';
```

### Check Table Structure
```sql
DESCRIBE customer_purchase_orders;
DESCRIBE payment_methods;
```

### Check Foreign Keys
```sql
SELECT CONSTRAINT_NAME, TABLE_NAME, COLUMN_NAME, REFERENCED_TABLE_NAME
FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE
WHERE TABLE_NAME IN ('customer_purchase_orders', 'payment_methods');
```

### Check Data
```sql
-- Last 5 purchase orders
SELECT * FROM customer_purchase_orders ORDER BY id DESC LIMIT 5;

-- Last 5 payment methods
SELECT * FROM payment_methods ORDER BY id DESC LIMIT 5;

-- Verify relationship
SELECT 
    po.id,
    po.product_id,
    pm.id as payment_id,
    pm.customer_purchase_order_id
FROM customer_purchase_orders po
LEFT JOIN payment_methods pm ON pm.customer_purchase_order_id = po.id
ORDER BY po.id DESC LIMIT 5;
```

---

## Service Methods Explained

### CheckoutService::processCheckout()
- **What it does:** Orchestrates entire checkout
- **Calls:** createPurchaseOrders() + createPaymentMethod()
- **Returns:** Success/error with data

### CheckoutService::createPurchaseOrders()
- **What it does:** Creates purchase order for each item
- **Logs:** Each item creation
- **Returns:** Array of created models

### CheckoutService::createPaymentMethod()
- **What it does:** Creates payment method linked to purchase order
- **Logs:** Payment method creation
- **Returns:** Created payment method model

---

## Flow Diagram

```
Frontend sends checkout data
  ↓
CheckoutController::store()
  ├─ Logs: "Request received"
  ├─ Validates data
  ├─ Calls CheckoutService::processCheckout()
  │
  └─→ CheckoutService::processCheckout()
      ├─ Logs: "CHECKOUT SERVICE STARTED"
      ├─ Calls createPurchaseOrders()
      │   ├─ Logs: "Creating purchase order 1/X"
      │   ├─ Creates record in database
      │   ├─ Logs: "✓ Purchase order 1 created"
      │   └─ Repeats for each item
      │
      ├─ Logs: "✓ Purchase orders created"
      ├─ Calls createPaymentMethod()
      │   ├─ Logs: "Creating payment method"
      │   ├─ Creates record in database
      │   └─ Logs: "✓ Payment method created"
      │
      ├─ Logs: "CHECKOUT SERVICE COMPLETED"
      └─ Returns result
  
  ├─ Checks if success
  ├─ Logs: "Checkout successful"
  └─ Redirects to receipt
```

---

## Quick Checklist

- [ ] Service file created: `app/Services/CheckoutService.php`
- [ ] CheckoutController updated to use service
- [ ] Laravel logs show all steps
- [ ] Database has new records
- [ ] Foreign keys are correct
- [ ] Payment method linked to purchase order
- [ ] Redirected to receipt page

---

## Summary

✅ **Service Layer** separates business logic
✅ **Detailed Logging** shows every step
✅ **Easy to Debug** - follow logs to find issues
✅ **Clear Error Messages** - know exactly what failed

Now you can see exactly where data is being created or where it fails!
