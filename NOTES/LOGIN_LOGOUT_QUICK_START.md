LOGIN/LOGOUT AUDIT LOGGING - QUICK START
=========================================

## What Changed?

### AuthController.php
✓ Added AuditLog model import
✓ Added DB facade import
✓ Modified store() method to call logLogin() after successful authentication
✓ Modified destroy() method to call logLogout() before destroying session
✓ Added logLogin() method - creates audit entry with login details
✓ Added logLogout() method - creates audit entry with logout details + session duration
✓ Added callStoredProcedure() method - works with MySQL and SQL Server
✓ Added formatSessionDuration() method - converts minutes to readable format (2h 30m)

---

## How It Works

### LOGIN FLOW
```
User → Login Form
   ↓
AuthController::store()
   ↓
Auth::attempt() succeeds ✓
   ↓
$user = Auth::user()
   ↓
session()->regenerate()
   ↓
→ logLogin($user, $request) [NEW]
   ├─ Get IP address
   ├─ Create description: "John Doe logged in"
   ├─ Create JSON with: username, role, ip, login_time
   ├─ Call stored procedure sp_insert_audit_log
   ├─ If fails, fallback to AuditLog::create()
   └─ If fails, log error to storage/logs/laravel.log
   ↓
return redirect(dashboard)
```

### LOGOUT FLOW
```
User → Clicks Logout Button
   ↓
AuthController::destroy()
   ↓
$user = Auth::user()
   ↓
→ logLogout($user, $request) [NEW]
   ├─ Get IP address
   ├─ Calculate session duration (in minutes)
   ├─ Format: "45m" or "2h 30m"
   ├─ Create description: "John Doe logged out (Session: 2h 30m)"
   ├─ Create JSON with: username, role, ip, logout_time, session_duration_minutes
   ├─ Call stored procedure sp_insert_audit_log
   ├─ If fails, fallback to AuditLog::create()
   └─ If fails, log error to storage/logs/laravel.log
   ↓
Auth::logout()
   ↓
session()->invalidate()
   ↓
return redirect(login)
```

---

## EXAMPLE AUDIT LOG ENTRIES

### When User Logs In
```
User: John Doe (admin)
Action: LOGIN
Module: Authentication
Description: John Doe logged in
IP: 192.168.1.100
Time: 2025-12-03 14:30:00
```

### When User Logs Out (After 2h 30m)
```
User: John Doe (admin)
Action: LOGOUT
Module: Authentication
Description: John Doe logged out (Session: 2h 30m)
IP: 192.168.1.100
Time: 2025-12-03 17:00:00
Session Duration: 150 minutes
```

---

## FILES MODIFIED

1. **app/Http/Controllers/AuthController.php**
   - Added imports for AuditLog and DB
   - Added login logging to store() method
   - Added logout logging to destroy() method
   - Added 3 helper methods: logLogin(), logLogout(), callStoredProcedure()
   - Added 1 utility method: formatSessionDuration()

## FILES CREATED

1. **database/sql_server_scripts/01_sp_insert_audit_log_sqlserver.sql**
   - Stored procedure for SQL Server
   - Creates records in auditlogs table
   - Ready to run in SSMS

2. **database/sql_server_scripts/02_sp_insert_audit_log_mysql.sql**
   - Stored procedure for MySQL
   - Creates records in auditlogs table
   - Ready to run in MySQL client

3. **NOTES/LOGIN_LOGOUT_AUDIT_INTEGRATION.md**
   - Complete guide with examples
   - Installation steps
   - Troubleshooting

---

## SETUP REQUIRED

### 1. Create Stored Procedure in Database

**For MySQL:**
```bash
mysql -u vantechdb -p vantechdb < database/sql_server_scripts/02_sp_insert_audit_log_mysql.sql
```

**For SQL Server:**
Copy and paste contents of `database/sql_server_scripts/01_sp_insert_audit_log_sqlserver.sql` into SQL Server Management Studio and execute.

### 2. Test It

1. Visit login page
2. Login with valid credentials
3. You should be on dashboard
4. Visit Audit Logs page (/Audit)
5. Filter by Module: "Authentication"
6. You should see a LOGIN entry
7. Click logout
8. Visit Audit Logs again
9. You should see a LOGOUT entry with session duration

---

## KEY FEATURES

✓ **Dual Database Support**
- Works with MySQL automatically
- Works with SQL Server automatically
- Detects driver and uses correct syntax

✓ **Error Handling**
- Stored procedure first attempt
- Falls back to direct database insert if procedure fails
- Falls back to error logging if both fail
- Never blocks login/logout process

✓ **Session Tracking**
- Captures login time
- Captures logout time
- Calculates session duration
- Formats as human-readable (2h 30m)

✓ **User Context**
- Captures user ID
- Captures username
- Captures user role
- Captures IP address

✓ **Data Integrity**
- IP address stored and logged
- JSON format for changes/metadata
- Proper timestamps on all records
- Indexed for fast queries

---

## AUDIT LOG EXAMPLES IN DATABASE

### Example 1: Quick Login/Logout
```sql
-- LOGIN
INSERT INTO auditlogs (user_id, action, module, description, changes, ip_address, created_at)
VALUES (1, 'LOGIN', 'Authentication', 'John Doe logged in', 
    '{"username":"john.doe","role":"admin","ip_address":"192.168.1.100","login_time":"2025-12-03 14:30:00"}',
    '192.168.1.100', '2025-12-03 14:30:00');

-- LOGOUT (1 minute later)
INSERT INTO auditlogs (user_id, action, module, description, changes, ip_address, created_at)
VALUES (1, 'LOGOUT', 'Authentication', 'John Doe logged out (Session: less than 1m)', 
    '{"username":"john.doe","role":"admin","ip_address":"192.168.1.100","logout_time":"2025-12-03 14:31:00","session_duration_minutes":1}',
    '192.168.1.100', '2025-12-03 14:31:00');
```

### Example 2: Long Session
```sql
-- LOGIN
INSERT INTO auditlogs (user_id, action, module, description, changes, ip_address, created_at)
VALUES (2, 'LOGIN', 'Authentication', 'Maria Garcia logged in', 
    '{"username":"maria.garcia","role":"staff","ip_address":"192.168.1.101","login_time":"2025-12-03 09:00:00"}',
    '192.168.1.101', '2025-12-03 09:00:00');

-- LOGOUT (8 hours later)
INSERT INTO auditlogs (user_id, action, module, description, changes, ip_address, created_at)
VALUES (2, 'LOGOUT', 'Authentication', 'Maria Garcia logged out (Session: 8h)', 
    '{"username":"maria.garcia","role":"staff","ip_address":"192.168.1.101","logout_time":"2025-12-03 17:00:00","session_duration_minutes":480}',
    '192.168.1.101', '2025-12-03 17:00:00');
```

---

## TO VIEW AUDIT LOGS

1. Login as admin
2. Go to Audit Logs (left sidebar)
3. Filter by Module: "Authentication"
4. You should see all login/logout records
5. Search for specific user by name
6. Sort by date to see most recent first

---

## NEXT INTEGRATION POINTS

Once login/logout is working, integrate the same pattern into:

1. **POS Module** - Log when sales are created
   ```php
   AuditLog::create([
       'user_id' => Auth::id(),
       'action' => 'CREATE',
       'module' => 'POS',
       'description' => 'Sold 3 items to Customer (Total: ₱2,500)',
       'changes' => json_encode([...]),
       'ip_address' => $request->ip()
   ]);
   ```

2. **Inventory Module** - Log product create/update/delete
3. **Services Module** - Log service operations
4. **Customer Module** - Log customer changes
5. **Staff Module** - Log staff management

---

## ERROR DEBUGGING

If login/logout not being logged:

1. Check Laravel logs: `storage/logs/laravel.log`
2. Run query: `SELECT * FROM auditlogs WHERE module='Authentication' ORDER BY created_at DESC;`
3. Verify stored procedure exists: `SHOW PROCEDURE STATUS LIKE 'sp_insert_audit_log';`
4. Check user permissions on stored procedure
5. Enable query logging in .env for debugging

---

That's it! You're now logging all login and logout activity to the audit logs table.
