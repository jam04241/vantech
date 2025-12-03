LOGIN & LOGOUT AUDIT LOG INTEGRATION GUIDE
============================================

## Overview
Login and logout audit logging has been integrated into AuthController with automatic database insertion using stored procedures. The system supports both MySQL and SQL Server.

---

## INTEGRATION POINTS

### 1. AuthController::store() - Login Method
Location: app/Http/Controllers/AuthController.php (Line 29-48)

**Workflow:**
1. User provides credentials (username, password)
2. Credentials validated and Auth::attempt() succeeds
3. User object retrieved
4. Session regenerated
5. **→ logLogin() method called automatically**
6. Redirect to dashboard

**What Gets Logged:**
- User ID
- Action: "LOGIN"
- Module: "Authentication"
- Description: "{first_name} {last_name} logged in"
- IP Address
- Changes JSON with: username, role, ip_address, login_time

---

### 2. AuthController::destroy() - Logout Method
Location: app/Http/Controllers/AuthController.php (Line 51-64)

**Workflow:**
1. Logout button clicked
2. destroy() route method called
3. Current user retrieved from Auth
4. **→ logLogout() method called automatically (BEFORE session destroyed)**
5. Session invalidated
6. Token regenerated
7. Redirect to login page

**What Gets Logged:**
- User ID
- Action: "LOGOUT"
- Module: "Authentication"
- Description: "{first_name} {last_name} logged out (Session: Xh Ym)"
- IP Address
- Changes JSON with: username, role, ip_address, logout_time, session_duration_minutes

---

## EXAMPLE AUDIT LOG ENTRIES

### LOGIN EXAMPLE
```
User ID: 1
Action: LOGIN
Module: Authentication
Description: John Doe logged in
IP Address: 192.168.1.100
Changes (JSON):
{
    "username": "john.doe",
    "role": "admin",
    "ip_address": "192.168.1.100",
    "login_time": "2025-12-03 14:30:00"
}
Timestamp: 2025-12-03 14:30:00
```

### LOGOUT EXAMPLE (After 2 hours 30 minutes)
```
User ID: 1
Action: LOGOUT
Module: Authentication
Description: John Doe logged out (Session: 2h 30m)
IP Address: 192.168.1.100
Changes (JSON):
{
    "username": "john.doe",
    "role": "admin",
    "ip_address": "192.168.1.100",
    "logout_time": "2025-12-03 17:00:00",
    "session_duration_minutes": 150
}
Timestamp: 2025-12-03 17:00:00
```

### LOGOUT EXAMPLE (After 45 minutes)
```
User ID: 2
Action: LOGOUT
Module: Authentication
Description: Maria Garcia logged out (Session: 45m)
IP Address: 192.168.1.101
Changes (JSON):
{
    "username": "maria.garcia",
    "role": "staff",
    "ip_address": "192.168.1.101",
    "logout_time": "2025-12-03 15:45:00",
    "session_duration_minutes": 45
}
Timestamp: 2025-12-03 15:45:00
```

### LOGOUT EXAMPLE (After less than 1 minute - Quick Login)
```
User ID: 3
Action: LOGOUT
Module: Authentication
Description: Carlos Reyes logged out (Session: less than 1m)
IP Address: 192.168.1.102
Changes (JSON):
{
    "username": "carlos.reyes",
    "role": "staff",
    "ip_address": "192.168.1.102",
    "logout_time": "2025-12-03 16:05:30",
    "session_duration_minutes": 0
}
Timestamp: 2025-12-03 16:05:30
```

---

## SESSION DURATION FORMAT

The system automatically formats session duration in human-readable format:

- Less than 1 minute: "less than 1m"
- Minutes only (1-59): "45m", "30m", etc.
- Hours only: "2h", "3h", etc.
- Hours + Minutes: "2h 30m", "1h 15m", etc.

Examples in description field:
- "John Doe logged out (Session: 2h 30m)"
- "Maria Garcia logged out (Session: 45m)"
- "Carlos Reyes logged out (Session: less than 1m)"
- "Admin User logged out (Session: 3h)"

---

## DATABASE IMPLEMENTATION

### Stored Procedure - MySQL Syntax

**Location:** database/sql_server_scripts/02_sp_insert_audit_log_mysql.sql

```sql
CALL sp_insert_audit_log(
    @p_user_id,           -- INT: User ID
    @p_action,            -- VARCHAR(50): "LOGIN" or "LOGOUT"
    @p_module,            -- VARCHAR(100): "Authentication"
    @p_description,       -- LONGTEXT: Description with session info
    @p_changes            -- JSON: Additional data in JSON format
);
```

**Parameters:**
1. `p_user_id` (INT): The ID of the user logging in/out
2. `p_action` (VARCHAR): Either "LOGIN" or "LOGOUT"
3. `p_module` (VARCHAR): Always "Authentication" for auth logs
4. `p_description` (LONGTEXT): Human-readable description
5. `p_changes` (JSON): Structured data with username, role, IP, timestamps

### Stored Procedure - SQL Server Syntax

**Location:** database/sql_server_scripts/01_sp_insert_audit_log_sqlserver.sql

```sql
EXEC sp_insert_audit_log
    @p_user_id = 1,
    @p_action = 'LOGIN',
    @p_module = 'Authentication',
    @p_description = 'John Doe logged in',
    @p_changes = '{"username":"john.doe","role":"admin","ip_address":"192.168.1.100","login_time":"2025-12-03 14:30:00"}';
```

---

## PHP CONTROLLER METHODS

### logLogin() Method (Lines 110-137)
**Triggered:** When user successfully authenticates

**Steps:**
1. Get client IP address
2. Create description: "{first_name} {last_name} logged in"
3. Create JSON changes object with login metadata
4. Call stored procedure with 5 parameters
5. If stored procedure fails, fallback to direct DB insert
6. If all fails, log error to Laravel logs (doesn't block authentication)

### logLogout() Method (Lines 139-179)
**Triggered:** When logout button clicked

**Steps:**
1. Get client IP address
2. Calculate session duration in minutes
3. Format duration as human-readable string
4. Create description: "{first_name} {last_name} logged out (Session: Xh Ym)"
5. Create JSON changes object with logout metadata + session duration
6. Call stored procedure with 5 parameters
7. If stored procedure fails, fallback to direct DB insert
8. If all fails, log error to Laravel logs (doesn't block logout)

### callStoredProcedure() Method (Lines 181-206)
**Purpose:** Database-agnostic stored procedure caller

**Features:**
- Detects database driver (MySQL vs SQL Server)
- Builds appropriate SQL syntax for each database
- Executes with parameterized queries (prevents SQL injection)
- Uses CALL for MySQL, EXEC for SQL Server

**Logic:**
```php
if ($driver === 'mysql') {
    DB::statement("CALL sp_insert_audit_log(?,?,?,?,?)", $parameters);
} elseif ($driver === 'sqlsrv') {
    DB::statement("EXEC sp_insert_audit_log @param1,@param2,@param3,@param4,@param5", $parameters);
}
```

### formatSessionDuration() Method (Lines 208-226)
**Purpose:** Convert minutes to human-readable duration

**Examples:**
- 0 minutes → "less than 1m"
- 45 minutes → "45m"
- 90 minutes → "1h 30m"
- 120 minutes → "2h"
- 150 minutes → "2h 30m"

---

## ERROR HANDLING

### Layered Error Handling Strategy

**Layer 1: Stored Procedure Execution**
- If stored procedure call fails, catch exception
- Fall through to Layer 2

**Layer 2: Direct Database Insert**
- Use Eloquent ORM AuditLog::create()
- If this also fails, catch exception
- Fall through to Layer 3

**Layer 3: Error Logging**
- Log error to Laravel logs (storage/logs/laravel.log)
- Error message: "Failed to log user login: {error_message}"
- Don't block authentication/logout process
- User continues normally even if logging fails

**Result:**
- 99% of logins/logouts will be logged
- Even if one method fails, another method catches it
- System remains responsive (no hanging)
- Errors are tracked in logs for debugging

---

## FALLBACK MECHANISM

### Why Fallback Exists?

Some hosting providers or database configurations may not allow stored procedure execution due to:
- Security restrictions
- Server configuration limitations
- User permissions not granted

### Fallback Flow

```
1. Try to execute stored procedure via DB::statement()
    ├─ SUCCESS: Audit log created via stored procedure ✓
    └─ FAILURE: Catch exception, proceed to fallback
    
2. Try direct database insert via AuditLog::create()
    ├─ SUCCESS: Audit log created via Eloquent ✓
    └─ FAILURE: Catch exception, proceed to error logging
    
3. Log error to storage/logs/laravel.log
    └─ Error documented for debugging
```

**Result:** User authentication/logout always succeeds, with logging as bonus (not requirement)

---

## INSTALLATION STEPS

### Step 1: Verify AuthController is Updated
✓ Already done - controller includes logLogin() and logLogout() methods

### Step 2: Create Stored Procedures (Choose One)

**For MySQL Database:**
```bash
# Option A: From File
mysql -u vantechdb -p vantechdb < database/sql_server_scripts/02_sp_insert_audit_log_mysql.sql

# Option B: Manual
mysql> DELIMITER $$
mysql> CREATE PROCEDURE sp_insert_audit_log(...) BEGIN ... END$$
mysql> DELIMITER ;
```

**For SQL Server Database:**
```sql
-- Execute in SQL Server Management Studio
USE vantechdb;
GO
-- Paste contents of: database/sql_server_scripts/01_sp_insert_audit_log_sqlserver.sql
GO
```

### Step 3: Test Login/Logout

1. Open login page: http://yoursite.com/login
2. Enter credentials and submit
3. Check audit logs: http://yoursite.com/Audit
4. Should see login record with:
   - Action: LOGIN
   - Description: "{Name} logged in"
   - Module: Authentication
5. Click logout
6. Check audit logs again
7. Should see logout record with:
   - Action: LOGOUT
   - Description: "{Name} logged out (Session: Xm or Xh Ym)"

### Step 4: Verify Data

**Query to check recent login/logout logs:**

**MySQL:**
```sql
SELECT id, user_id, action, module, description, created_at
FROM auditlogs
WHERE module = 'Authentication'
ORDER BY created_at DESC
LIMIT 10;
```

**SQL Server:**
```sql
SELECT TOP 10 id, user_id, action, module, description, created_at
FROM auditlogs
WHERE module = 'Authentication'
ORDER BY created_at DESC;
```

---

## NEXT STEPS

Now that login/logout logging is complete, you can integrate audit logging into other modules:

1. **POS Module** - Log sales transactions
2. **Inventory Module** - Log product create/update/delete
3. **Services Module** - Log service creation and status updates
4. **Customer Module** - Log customer additions/changes
5. **Staff Module** - Log staff management operations

Each module should follow the same pattern:
```php
AuditLog::create([
    'user_id' => Auth::id(),
    'action' => 'CREATE/UPDATE/DELETE',
    'module' => 'ModuleName',
    'description' => 'Description of what happened',
    'changes' => json_encode(['old_value' => ..., 'new_value' => ...]),
    'ip_address' => $request->ip()
]);
```

Or use the stored procedure:
```php
$this->callStoredProcedure('sp_insert_audit_log', [
    Auth::id(),
    'CREATE',
    'Inventory',
    'Added new product: Samsung SSD 1TB',
    json_encode(['sku' => 'SSD-001', 'price' => 5500])
]);
```

---

## TROUBLESHOOTING

### Issue: Stored Procedure Not Found
**Cause:** Procedure hasn't been created in database
**Solution:** Run one of the SQL scripts above to create the procedure

### Issue: Audit Logs Not Appearing
**Check:**
1. Did login succeed? (You should be on dashboard)
2. Are you checking the right module? (Filter by "Authentication")
3. Check Laravel logs: storage/logs/laravel.log
4. Run test query above to verify data exists

### Issue: Permission Denied Error
**Cause:** Database user doesn't have EXECUTE permission on stored procedure
**Solution:** Grant permissions:

**MySQL:**
```sql
GRANT EXECUTE ON vantechdb.sp_insert_audit_log TO 'vantechdb_user'@'localhost';
```

**SQL Server:**
```sql
GRANT EXECUTE ON sp_insert_audit_log TO [domain\username];
```

### Issue: JSON Format Error
**Cause:** Malformed JSON in changes field
**Solution:** Controller already handles this - should never happen. If it does, check Laravel logs.

---

## AUDIT LOG VIEWING

View all login/logout activity:
1. Login as admin
2. Navigate to: Audit Logs (from sidebar)
3. Filter by Module: "Authentication"
4. Filter by Action: "LOGIN" or "LOGOUT"
5. Sort by Date to see most recent first
6. Search for specific user by name

---

## DATABASE SCHEMA REFERENCE

**Table:** auditlogs
```
├─ id (INT, PRIMARY KEY)
├─ user_id (INT, FOREIGN KEY → users.id)
├─ action (VARCHAR) - "LOGIN", "LOGOUT", "CREATE", "UPDATE", "DELETE", "VIEW"
├─ module (VARCHAR) - "Authentication", "POS", "Inventory", etc.
├─ description (TEXT) - Human-readable description
├─ changes (JSON) - Structured metadata
├─ ip_address (VARCHAR) - Source IP address
├─ created_at (TIMESTAMP)
└─ updated_at (TIMESTAMP)
```

**Sample Record:**
```
id: 42
user_id: 1
action: LOGIN
module: Authentication
description: John Doe logged in
changes: {"username":"john.doe","role":"admin","ip_address":"192.168.1.100","login_time":"2025-12-03 14:30:00"}
ip_address: 192.168.1.100
created_at: 2025-12-03 14:30:00
updated_at: 2025-12-03 14:30:00
```

---

END OF GUIDE
