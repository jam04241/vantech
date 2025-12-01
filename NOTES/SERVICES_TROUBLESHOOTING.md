# Services Module - Troubleshooting & FAQ

## 🔍 Common Issues & Solutions

### Issue 1: Services Not Showing After Migration

**Symptoms:**
- Empty service list after visiting `/services`
- No cards displayed
- "No services found" message

**Solutions:**

1. **Verify Migration Ran**
```bash
php artisan migrate:status
# Look for: 2024_01_17_create_services_table ✓
```

2. **Check Database Connection**
```bash
php artisan tinker
> DB::connection()->getPdo();
# Should not throw error
```

3. **Verify Table Exists**
```bash
php artisan tinker
> Schema::hasTable('services');
# Should return true
```

4. **Reset and Re-migrate**
```bash
php artisan migrate:reset
php artisan migrate
```

---

### Issue 2: Form Not Submitting

**Symptoms:**
- Click Save but nothing happens
- No error message appears
- Browser console shows errors

**Solutions:**

1. **Check CSRF Token**
```blade
<!-- Verify in Services.blade.php -->
<meta name="csrf-token" content="{{ csrf_token() }}">
```

2. **Check Browser Console**
- Open DevTools (F12)
- Go to Console tab
- Look for error messages
- Check Network tab for failed requests

3. **Verify API Endpoint**
- Network tab → check /api/services request
- Should show 201 status for POST
- 422 if validation fails
- 500 if server error

4. **Test API Directly**
```bash
curl -X POST http://yourdomain.com/api/services \
  -H "Content-Type: application/json" \
  -H "X-CSRF-TOKEN: your-token" \
  -d '{"customer_name":"Test","service_type":"Hardware Repair","description":"Test"}'
```

---

### Issue 3: Search/Filter Not Working

**Symptoms:**
- Type in search but nothing filters
- Dropdown filters don't work
- All cards stay visible

**Solutions:**

1. **Check JavaScript Execution**
```javascript
// In browser console
typeof filterServices  // Should be 'function'
typeof displayServices  // Should be 'function'
```

2. **Verify Event Listeners**
```javascript
// Test search filter
document.getElementById('serviceSearch').dispatchEvent(new Event('input'));
```

3. **Check Data Loading**
```javascript
// In console
allServices  // Should contain array of services
console.log(allServices.length);  // Should show count
```

4. **Force Reload**
- Hard refresh: Ctrl+F5 (Windows) or Cmd+Shift+R (Mac)
- Clear cache: DevTools → Application → Clear storage

---

### Issue 4: API Returns 422 Validation Error

**Symptoms:**
```json
{
  "success": false,
  "message": "Validation failed",
  "errors": { ... }
}
```

**Solutions:**

1. **Check Required Fields**
- `customer_name` - Must not be empty
- `service_type` - Must be one of 7 options
- `description` - Must not be empty

2. **Verify Enum Values**
```javascript
// Valid service types:
const validTypes = [
  'Hardware Repair',
  'Software Support',
  'Network Setup',
  'Data Recovery',
  'Maintenance',
  'Installation',
  'Troubleshooting'
];

// Valid statuses:
const validStatuses = [
  'Pending',
  'In Progress',
  'Completed',
  'On Hold'
];

// Valid priorities:
const validPriorities = [
  'Low',
  'Medium',
  'High',
  'Urgent'
];
```

3. **Field Length Validation**
- `customer_name` - Max 255 characters
- Check actual field content in request

4. **Debug Response**
```javascript
fetch('/api/services', { ... })
  .then(res => res.json())
  .then(data => {
    if (!data.success) {
      console.log('Errors:', data.errors);
    }
  });
```

---

### Issue 5: Audit Logs Not Being Created

**Symptoms:**
- Services created but no audit logs
- Triggers not executing
- `services_audit_log` table empty

**Solutions:**

1. **Verify Triggers Exist**
```sql
-- In SQL Server Management Studio
SELECT * FROM sys.triggers 
WHERE name LIKE 'tr_services%';
-- Should show 3 results: insert, update, delete
```

2. **Check Trigger Status**
```sql
-- Enable triggers if disabled
ENABLE TRIGGER tr_services_insert ON services;
ENABLE TRIGGER tr_services_update ON services;
ENABLE TRIGGER tr_services_delete ON services;
```

3. **Verify Table Exists**
```sql
SELECT * FROM INFORMATION_SCHEMA.TABLES 
WHERE TABLE_NAME = 'services_audit_log';
```

4. **Test Manually**
```sql
-- Insert test service
INSERT INTO services (customer_name, service_type, description, status, priority)
VALUES ('Test', 'Hardware Repair', 'Test description', 'Pending', 'Medium');

-- Check audit log
SELECT * FROM services_audit_log WHERE action = 'INSERT';
```

5. **Re-create Triggers**
- Re-execute: `services_audit_triggers.sql`
- Ensure no errors during script execution

---

### Issue 6: Sticky Form Not Staying Visible

**Symptoms:**
- Form scrolls with page
- Form position changes
- Not sticky on scroll

**Solutions:**

1. **Check CSS Class**
```blade
<!-- Verify in FormServices.blade.php -->
<div class="lg:col-span-1 sticky top-6 h-fit ...">
```

2. **Clear Browser Cache**
- Hard refresh: Ctrl+F5
- Tailwind CSS might need rebuild

3. **Rebuild Tailwind** (if custom build)
```bash
npm run dev
# or
npm run prod
```

---

### Issue 7: Delete Not Working

**Symptoms:**
- Click delete button, nothing happens
- No confirmation dialog
- Service still exists

**Solutions:**

1. **Check Delete Button Visibility**
```javascript
// Console
document.getElementById('deleteBtn').style.display
// Should be 'block' when form is populated
```

2. **Select Service First**
- Click a service card to select it
- Delete button should appear
- Form should populate

3. **Check Confirmation Dialog**
```javascript
// Should use SweetAlert
// Verify SweetAlert library is included
typeof Swal  // Should be 'function'
```

4. **Debug Delete Request**
```javascript
// Check console for network errors
// Network tab → DELETE request should show 200 status
```

---

### Issue 8: Card Selection Not Highlighting

**Symptoms:**
- Click card but no visual feedback
- Form doesn't populate
- Multiple cards can be selected at once

**Solutions:**

1. **Check Ring CSS**
```javascript
// Verify CSS classes applied
element.classList.contains('ring-2')
element.classList.contains('ring-[#151F28]')
```

2. **Test Selection Manually**
```javascript
// Get first card and click
const card = document.querySelector('.service-card');
card.click();
```

3. **Force Refresh**
- Close browser tab and reopen
- Clear browser cache completely

---

## 🧪 Testing Procedures

### Unit Test: Create Service
```javascript
// Test creating a service
const testData = {
  customer_name: 'John Doe',
  service_type: 'Hardware Repair',
  description: 'Test service',
  status: 'Pending',
  priority: 'High'
};

fetch('/api/services', {
  method: 'POST',
  headers: {
    'Content-Type': 'application/json',
    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
  },
  body: JSON.stringify(testData)
})
.then(res => res.json())
.then(data => {
  console.assert(data.success === true, 'Create failed');
  console.assert(data.data.id > 0, 'No ID returned');
  console.log('✅ Create test passed');
});
```

### Unit Test: Update Service
```javascript
// Test updating a service
const updateData = {
  status: 'In Progress',
  priority: 'Low'
};

fetch('/api/services/1', {
  method: 'PUT',
  headers: {
    'Content-Type': 'application/json',
    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
  },
  body: JSON.stringify(updateData)
})
.then(res => res.json())
.then(data => {
  console.assert(data.success === true, 'Update failed');
  console.assert(data.data.status === 'In Progress', 'Status not updated');
  console.log('✅ Update test passed');
});
```

### Unit Test: Delete Service
```javascript
// Test deleting a service
fetch('/api/services/1', {
  method: 'DELETE',
  headers: {
    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
  }
})
.then(res => res.json())
.then(data => {
  console.assert(data.success === true, 'Delete failed');
  console.log('✅ Delete test passed');
});
```

---

## 📊 Database Verification

### Check Services Table
```sql
-- Count services
SELECT COUNT(*) FROM services;

-- View all services
SELECT * FROM services ORDER BY created_at DESC;

-- Check specific service
SELECT * FROM services WHERE customer_name = 'John Doe';
```

### Check Audit Log
```sql
-- Count audit records
SELECT COUNT(*) FROM services_audit_log;

-- View all audits
SELECT * FROM services_audit_log ORDER BY changed_at DESC;

-- Audit by action
SELECT action, COUNT(*) FROM services_audit_log GROUP BY action;

-- Recent changes
SELECT TOP 10 * FROM services_audit_log ORDER BY changed_at DESC;
```

---

## 🔧 Debug Mode

### Enable Detailed Logging

1. **Laravel Debug**
```php
// config/app.php
'debug' => true,  // Set to true for development
```

2. **Browser Console Debug**
```javascript
// Add at start of Services.blade.php script
const DEBUG = true;

function log(message, data = null) {
  if (DEBUG) {
    console.log(`[Services] ${message}`, data);
  }
}

// Then use:
log('Loading services');
```

3. **Network Inspection**
- Open DevTools → Network tab
- Perform action
- Check request/response
- Look for errors in headers/body

---

## ⚡ Performance Tips

### Slow Load Time?
1. Check database indexes exist
2. Reduce service count for initial test
3. Check network tab for slow requests
4. Profile with browser DevTools

### Many Services Slow UI?
1. Implement pagination (currently unlimited)
2. Load services in batches
3. Virtual scrolling for cards
4. Debounce search/filter

---

## 📞 Getting Help

### Check These First
1. ✅ Has migration been run? → `php artisan migrate:status`
2. ✅ Is table in database? → SQL Server Management Studio
3. ✅ Are triggers created? → Check `sys.triggers`
4. ✅ Any JavaScript errors? → Browser DevTools Console
5. ✅ API responses OK? → Network tab check

### Collect Debug Info
```bash
# Laravel version
php artisan --version

# Database check
php artisan tinker
> DB::select('SELECT 1');

# Table info
> Schema::getColumns('services');
```

---

## 🆘 Error Messages Explained

| Error | Meaning | Solution |
|-------|---------|----------|
| 422 Validation Error | Invalid input data | Check field values match requirements |
| 404 Service Not Found | Service ID doesn't exist | Verify service ID in database |
| 500 Server Error | Backend error | Check server logs, restart PHP |
| CSRF Token Mismatch | Security token invalid | Reload page, try again |
| Network Error | Connection failed | Check internet, server status |

---

## 📝 FAQ

**Q: Can I have multiple services per customer?**
A: Yes, each service is independent. Multiple services can share same customer_name.

**Q: How long is the audit trail kept?**
A: By default indefinitely. Run `sp_purge_old_audit_logs` to delete old records.

**Q: Can I edit the service types?**
A: Yes, update in controller validation and migration enum values.

**Q: Is there a limit to description length?**
A: No specific limit in form, but database stores as LONGTEXT (max ~2GB).

**Q: Can I assign services to employees?**
A: Currently no, but can add `employee_id` field to schema.

**Q: How do I export services?**
A: Use API endpoint `/api/services` and convert JSON to CSV/Excel.

**Q: Is there pagination?**
A: Not currently, but can be added to API and form.

**Q: Can I customize service types?**
A: Yes, update in migration and controller validation rules.

**Q: Does it support multi-user editing?**
A: Yes, audit log records who changed what.

**Q: Can I add custom fields?**
A: Yes, add columns to migration and form.

---

## 🎓 Advanced Troubleshooting

### Enable SQL Server Query Logging
```sql
-- Check last queries
SELECT * FROM sys.dm_exec_query_stats
ORDER BY creation_time DESC;
```

### Monitor Trigger Performance
```sql
-- Check if triggers are blocking
SELECT * FROM sys.dm_exec_requests
WHERE status = 'running';
```

### Rebuild Indexes
```sql
-- Optimize table performance
ALTER INDEX ALL ON services REBUILD;
ALTER INDEX ALL ON services_audit_log REBUILD;
```

---

**Last Updated**: January 17, 2024  
**Version**: 1.0.0

For additional help, contact the development team or review the comprehensive documentation files.
