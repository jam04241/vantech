LOGIN & LOGOUT AUDIT LOGGING - ARCHITECTURE
=============================================

## SYSTEM FLOW DIAGRAM

```
┌─────────────────────────────────────────────────────────────────────┐
│                         LOGIN FLOW                                  │
└─────────────────────────────────────────────────────────────────────┘

    USER (Browser)
         │
         │ POST /login
         ├─ username: john.doe
         └─ password: ****
         
         ↓
    
    AuthController::store()
    ├─ validate($credentials)
    ├─ Auth::attempt($credentials)
    │   └─ Checks users table
    │       └─ Username + Password match ✓
    │
    ├─ $user = Auth::user()
    ├─ session()->regenerate()
    │
    ├─→ $this->logLogin($user, $request) [NEW]
    │   ├─ $ipAddress = $request->ip() → "192.168.1.100"
    │   ├─ $description = "John Doe logged in"
    │   ├─ $changes = JSON {
    │   │     "username": "john.doe",
    │   │     "role": "admin",
    │   │     "ip_address": "192.168.1.100",
    │   │     "login_time": "2025-12-03 14:30:00"
    │   │   }
    │   │
    │   ├─→ $this->callStoredProcedure('sp_insert_audit_log', [
    │   │       user_id: 1,
    │   │       action: "LOGIN",
    │   │       module: "Authentication",
    │   │       description: "John Doe logged in",
    │   │       changes: JSON
    │   │   ])
    │   │   │
    │   │   └─→ Database Selection
    │   │       ├─ MySQL: CALL sp_insert_audit_log(?,?,?,?,?)
    │   │       └─ SQL Server: EXEC sp_insert_audit_log @p1,@p2,@p3,@p4,@p5
    │   │           │
    │   │           ↓
    │   │       sp_insert_audit_log() Stored Procedure
    │   │           │
    │   │           └─ INSERT INTO auditlogs (
    │   │                 user_id: 1,
    │   │                 action: "LOGIN",
    │   │                 module: "Authentication",
    │   │                 description: "John Doe logged in",
    │   │                 changes: JSON,
    │   │                 created_at: NOW(),
    │   │                 updated_at: NOW()
    │   │             )
    │   │               ↓ Success ✓
    │   │               Record created in auditlogs table
    │   │
    │   ├─ EXCEPTION HANDLER
    │   │   └─ Fallback: AuditLog::create([...])
    │   │       (Direct database insert without procedure)
    │   │
    │   └─ FINAL EXCEPTION HANDLER
    │       └─ \Log::error('Failed to log user login: ...')
    │           (Log error but don't block authentication)
    │
    └─→ return redirect()->intended(route('dashboard'))
        
        ↓
    
    Dashboard Loaded ✓
    Login successful, audit logged


┌─────────────────────────────────────────────────────────────────────┐
│                         LOGOUT FLOW                                 │
└─────────────────────────────────────────────────────────────────────┘

    USER (Browser) - on Dashboard
         │
         │ Click "Logout" Button
         │
         ↓
    
    AuthController::destroy()
    ├─ $user = Auth::user() → Returns John Doe (admin)
    │
    ├─→ $this->logLogout($user, $request) [NEW]
    │   ├─ $ipAddress = $request->ip() → "192.168.1.100"
    │   ├─ $sessionStartTime = session('login_time') ?? now()
    │   │   └─ Assume logged in at 14:30, now 17:00
    │   ├─ $sessionDuration = now()->diffInMinutes($sessionStartTime)
    │   │   └─ Result: 150 minutes
    │   │
    │   ├─ $durationFormatted = $this->formatSessionDuration(150)
    │   │   └─ Result: "2h 30m"
    │   │
    │   ├─ $description = "John Doe logged out (Session: 2h 30m)"
    │   ├─ $changes = JSON {
    │   │     "username": "john.doe",
    │   │     "role": "admin",
    │   │     "ip_address": "192.168.1.100",
    │   │     "logout_time": "2025-12-03 17:00:00",
    │   │     "session_duration_minutes": 150
    │   │   }
    │   │
    │   ├─→ $this->callStoredProcedure('sp_insert_audit_log', [
    │   │       user_id: 1,
    │   │       action: "LOGOUT",
    │   │       module: "Authentication",
    │   │       description: "John Doe logged out (Session: 2h 30m)",
    │   │       changes: JSON
    │   │   ])
    │   │   │
    │   │   └─→ Database Selection
    │   │       ├─ MySQL: CALL sp_insert_audit_log(?,?,?,?,?)
    │   │       └─ SQL Server: EXEC sp_insert_audit_log @p1,@p2,@p3,@p4,@p5
    │   │           │
    │   │           ↓
    │   │       sp_insert_audit_log() Stored Procedure
    │   │           │
    │   │           └─ INSERT INTO auditlogs (
    │   │                 user_id: 1,
    │   │                 action: "LOGOUT",
    │   │                 module: "Authentication",
    │   │                 description: "John Doe logged out (Session: 2h 30m)",
    │   │                 changes: JSON,
    │   │                 created_at: NOW(),
    │   │                 updated_at: NOW()
    │   │             )
    │   │               ↓ Success ✓
    │   │               Record created in auditlogs table
    │   │
    │   ├─ EXCEPTION HANDLER
    │   │   └─ Fallback: AuditLog::create([...])
    │   │
    │   └─ FINAL EXCEPTION HANDLER
    │       └─ \Log::error('Failed to log user logout: ...')
    │
    ├─ Auth::guard('web')->logout()
    ├─ session()->invalidate()
    ├─ session()->regenerateToken()
    │
    └─→ return redirect()->route('login')
        
        ↓
    
    Login Page Loaded ✓
    Logout successful, audit logged


┌─────────────────────────────────────────────────────────────────────┐
│                    ERROR HANDLING FLOW                              │
└─────────────────────────────────────────────────────────────────────┘

    Attempt to log action (LOGIN or LOGOUT)
         │
         ├─→ Layer 1: Try Stored Procedure
         │   │
         │   ├─ SUCCESS ✓
         │   │   └─ Record created via sp_insert_audit_log()
         │   │       └─ Complete (Return)
         │   │
         │   └─ EXCEPTION ✗
         │       └─ Catch exception
         │           │
         │           └─→ Layer 2: Try Direct Insert
         │               │
         │               ├─ SUCCESS ✓
         │               │   └─ Record created via AuditLog::create()
         │               │       └─ Complete (Return)
         │               │
         │               └─ EXCEPTION ✗
         │                   └─ Catch exception
         │                       │
         │                       └─→ Layer 3: Log Error
         │                           │
         │                           └─ \Log::error('Failed to log...')
         │                               └─ Error saved to storage/logs/laravel.log
         │                                   └─ Complete (Return)
         │
         └─ Result: Authentication/Logout succeeds regardless
            (Logging is bonus, not requirement)


┌─────────────────────────────────────────────────────────────────────┐
│               DATABASE RECORD STRUCTURE                             │
└─────────────────────────────────────────────────────────────────────┘

auditlogs TABLE:
┌────────────────────────────────────────────────────────────────────┐
│ id          │ 42                                                   │
├────────────────────────────────────────────────────────────────────┤
│ user_id     │ 1 (FK → users.id)                                    │
├────────────────────────────────────────────────────────────────────┤
│ action      │ "LOGIN" or "LOGOUT"                                  │
├────────────────────────────────────────────────────────────────────┤
│ module      │ "Authentication"                                     │
├────────────────────────────────────────────────────────────────────┤
│ description │ "John Doe logged in"                                 │
│             │ OR                                                   │
│             │ "John Doe logged out (Session: 2h 30m)"              │
├────────────────────────────────────────────────────────────────────┤
│ changes     │ {                                                    │
│ (JSON)      │   "username": "john.doe",                            │
│             │   "role": "admin",                                   │
│             │   "ip_address": "192.168.1.100",                     │
│             │   "login_time": "2025-12-03 14:30:00",               │
│             │   "logout_time": "2025-12-03 17:00:00",              │
│             │   "session_duration_minutes": 150                    │
│             │ }                                                    │
├────────────────────────────────────────────────────────────────────┤
│ ip_address  │ "192.168.1.100"                                      │
├────────────────────────────────────────────────────────────────────┤
│ created_at  │ 2025-12-03 14:30:00 (for LOGIN)                      │
│             │ 2025-12-03 17:00:00 (for LOGOUT)                     │
├────────────────────────────────────────────────────────────────────┤
│ updated_at  │ 2025-12-03 14:30:00 (for LOGIN)                      │
│             │ 2025-12-03 17:00:00 (for LOGOUT)                     │
└────────────────────────────────────────────────────────────────────┘


┌─────────────────────────────────────────────────────────────────────┐
│           SESSION DURATION FORMAT EXAMPLES                          │
└─────────────────────────────────────────────────────────────────────┘

Input Minutes  →  Output Format
────────────────────────────────
0              →  "less than 1m"
1              →  "1m"
45             →  "45m"
59             →  "59m"
60             →  "1h"
90             →  "1h 30m"
120            →  "2h"
150            →  "2h 30m"
240            →  "4h"
270            →  "4h 30m"

All stored in description field:
"John Doe logged out (Session: 45m)"
"John Doe logged out (Session: 2h 30m)"
"John Doe logged out (Session: less than 1m)"


┌─────────────────────────────────────────────────────────────────────┐
│              MULTI-DATABASE SUPPORT                                 │
└─────────────────────────────────────────────────────────────────────┘

PHP Code detects database driver:
$driver = DB::getDriverName();

MySQL Flow:
  ├─ $placeholders = "?,?,?,?,?"
  └─ DB::statement("CALL sp_insert_audit_log(?,?,?,?,?)", $parameters)
     └─ Executes: CALL sp_insert_audit_log(@p1, @p2, @p3, @p4, @p5)

SQL Server Flow:
  ├─ $placeholders = "@param1,@param2,@param3,@param4,@param5"
  └─ DB::statement("EXEC sp_insert_audit_log @param1,@param2,@param3,@param4,@param5", $parameters)
     └─ Executes: EXEC sp_insert_audit_log 1, "LOGIN", "Authentication", "John Doe logged in", {...}

Both syntaxes parameterized to prevent SQL injection ✓


┌─────────────────────────────────────────────────────────────────────┐
│              CONTROLLER METHOD CALL STACK                           │
└─────────────────────────────────────────────────────────────────────┘

AuthController
├─ store($request)              [Routes: POST /login]
│  └─ Calls: logLogin($user, $request)
│     ├─ Calls: callStoredProcedure('sp_insert_audit_log', [...])
│     │  ├─ DB::getDriverName()
│     │  └─ DB::statement()
│     │     └─ sp_insert_audit_log (Database Procedure)
│     └─ Fallback: AuditLog::create([...])
│
├─ destroy($request)            [Routes: POST /logout]
│  └─ Calls: logLogout($user, $request)
│     ├─ Calls: formatSessionDuration($minutes)
│     ├─ Calls: callStoredProcedure('sp_insert_audit_log', [...])
│     │  ├─ DB::getDriverName()
│     │  └─ DB::statement()
│     │     └─ sp_insert_audit_log (Database Procedure)
│     └─ Fallback: AuditLog::create([...])
│
├─ callStoredProcedure($name, $params)
│  ├─ DB::getDriverName()
│  ├─ if MySQL: DB::statement("CALL...", $params)
│  └─ if SQL Server: DB::statement("EXEC...", $params)
│
└─ formatSessionDuration($minutes)
   └─ Return formatted string: "2h 30m" or "45m" or "less than 1m"


```

---

## COMPONENT RELATIONSHIPS

```
┌─────────────────────────────────────────────────────────────────┐
│                       APPLICATION                              │
└─────────────────────────────────────────────────────────────────┘
           │
           │ Uses
           ↓
┌─────────────────────────────────────────────────────────────────┐
│                 AuthController                                  │
│  ┌──────────────────────────────────────────────────────────┐  │
│  │ Methods:                                                 │  │
│  │ - store() [LOGIN]                                        │  │
│  │ - destroy() [LOGOUT]                                     │  │
│  │ - logLogin() [NEW]                                       │  │
│  │ - logLogout() [NEW]                                      │  │
│  │ - callStoredProcedure() [NEW]                            │  │
│  │ - formatSessionDuration() [NEW]                          │  │
│  └──────────────────────────────────────────────────────────┘  │
└─────────────────────────────────────────────────────────────────┘
           │
           ├─ Uses
           ├──→ Auth Facade (check credentials)
           │
           ├─ Uses
           ├──→ AuditLog Model (fallback database insert)
           │    └─ belongsTo(User::class)
           │
           ├─ Uses
           ├──→ DB Facade (stored procedure calls)
           │
           └─ Uses
            └──→ Request (get IP address)

┌─────────────────────────────────────────────────────────────────┐
│                    Database Layer                               │
├─────────────────────────────────────────────────────────────────┤
│                                                                 │
│  MySQL               SQL Server         Laravel Fallback       │
│  ────────────────    ──────────────     ─────────────────      │
│  sp_insert_audit_log sp_insert_audit_log AuditLog::create()   │
│  └─ CALL syntax      └─ EXEC syntax      └─ Eloquent ORM     │
│     (Procedure)         (Procedure)          (Direct Insert)   │
│     ↓                   ↓                     ↓               │
│     INSERT             INSERT                INSERT           │
│     INTO               INTO                  INTO             │
│     auditlogs          auditlogs             auditlogs        │
│     table              table                 table            │
│                                                                 │
└─────────────────────────────────────────────────────────────────┘


```

---

## INTEGRATION WITH EXISTING SYSTEMS

```
┌──────────────────────────────┐
│   USER LOGIN FORM            │
│   (resources/views/...)      │
└──────────┬───────────────────┘
           │ POST /login
           ↓
┌──────────────────────────────┐
│   Web Routes (routes/web.php) │
│   Route::post('/login', ...)  │
└──────────┬───────────────────┘
           │ Calls
           ↓
┌──────────────────────────────────────┐
│   AuthController::store()            │
│   (app/Http/Controllers/)            │
│   - Auth::attempt()                  │
│   - logLogin() ← NEW AUDIT LOGGING   │
└──────────┬───────────────────────────┘
           │ Calls
           ↓
┌──────────────────────────────────────┐
│   sp_insert_audit_log()              │
│   (database/sql_server_scripts/)     │
│   - Stored Procedure                 │
└──────────┬───────────────────────────┘
           │ Inserts
           ↓
┌──────────────────────────────────────┐
│   auditlogs Table                    │
│   (database/migrations/)             │
│   - Records all login/logout events  │
└──────────────────────────────────────┘
           │ Viewed In
           ↓
┌──────────────────────────────────────┐
│   Audit Logs Page                    │
│   (/Audit)                           │
│   - Filter by Authentication         │
│   - See all login/logout history     │
└──────────────────────────────────────┘

```

---

## DATA FLOW EXAMPLE

```
TIME: 2025-12-03 14:30:00

Step 1: User clicks Login
────────────────────────
POST /login
{
  username: "john.doe",
  password: "password123"
}

Step 2: AuthController::store() receives request
────────────────────────────────────────────────
- Validates credentials
- Auth::attempt() succeeds
- $user = User object {
    id: 1,
    first_name: "John",
    last_name: "Doe",
    username: "john.doe",
    role: "admin"
  }
- session()->regenerate()
- Calls: $this->logLogin($user, $request)

Step 3: logLogin() builds audit data
────────────────────────────────────
- $ipAddress = "192.168.1.100"
- $description = "John Doe logged in"
- $changes = {
    "username": "john.doe",
    "role": "admin",
    "ip_address": "192.168.1.100",
    "login_time": "2025-12-03 14:30:00"
  }

Step 4: callStoredProcedure() executes query
──────────────────────────────────────────────
Database detected: MySQL
SQL: CALL sp_insert_audit_log(?, ?, ?, ?, ?)
Parameters: [1, "LOGIN", "Authentication", "John Doe logged in", {...}]

Step 5: sp_insert_audit_log() inserts record
─────────────────────────────────────────────
INSERT INTO auditlogs (
  user_id: 1,
  action: "LOGIN",
  module: "Authentication",
  description: "John Doe logged in",
  changes: {...},
  created_at: "2025-12-03 14:30:00",
  updated_at: "2025-12-03 14:30:00"
);

Result: ✓ Row 42 created in auditlogs table

Step 6: Return to user
──────────────────────
redirect()->intended(route('dashboard'))
→ User sees dashboard


TIME: 2025-12-03 17:00:00 (2h 30m later)

Step 1: User clicks Logout
──────────────────────────
POST /logout (via SweetAlert confirmation)

Step 2: AuthController::destroy() receives request
────────────────────────────────────────────────
- $user = Auth::user() (still John Doe, ID 1)
- Calls: $this->logLogout($user, $request)

Step 3: logLogout() builds audit data with session info
───────────────────────────────────────────────────────
- $ipAddress = "192.168.1.100"
- $sessionDuration = 150 minutes
- $durationFormatted = "2h 30m" (via formatSessionDuration())
- $description = "John Doe logged out (Session: 2h 30m)"
- $changes = {
    "username": "john.doe",
    "role": "admin",
    "ip_address": "192.168.1.100",
    "logout_time": "2025-12-03 17:00:00",
    "session_duration_minutes": 150
  }

Step 4: callStoredProcedure() executes query
────────────────────────────────────────────
Database detected: MySQL
SQL: CALL sp_insert_audit_log(?, ?, ?, ?, ?)
Parameters: [1, "LOGOUT", "Authentication", "John Doe logged out (Session: 2h 30m)", {...}]

Step 5: sp_insert_audit_log() inserts record
──────────────────────────────────────────────
INSERT INTO auditlogs (
  user_id: 1,
  action: "LOGOUT",
  module: "Authentication",
  description: "John Doe logged out (Session: 2h 30m)",
  changes: {...},
  created_at: "2025-12-03 17:00:00",
  updated_at: "2025-12-03 17:00:00"
);

Result: ✓ Row 43 created in auditlogs table

Step 6: Destroy session and redirect
─────────────────────────────────────
Auth::logout()
session()->invalidate()
session()->regenerateToken()
redirect()->route('login')
→ User sees login page

```

---

## AUDIT LOG VIEWING

```
Admin User at /Audit page:

┌──────────────────────────────────────────────────────────┐
│           AUDIT LOGS - AUTHENTICATION                    │
├──────────────────────────────────────────────────────────┤
│ Search: [________]  Module: [Authentication ▼]           │
│ Action: [All ▼]     Sort: [Date ▼] [DESC ▼]             │
├──────────────────────────────────────────────────────────┤
│ User           │ Module      │ Action  │ Description     │ Time
├────────────────────────────────────────────────────────────┤
│ John Doe       │ Auth        │ LOGOUT  │ Logged out      │ 17:00
│                │             │         │ (Session: 2h30m)│ 2025-12-03
├────────────────────────────────────────────────────────────┤
│ John Doe       │ Auth        │ LOGIN   │ Logged in       │ 14:30
│                │             │         │                 │ 2025-12-03
├────────────────────────────────────────────────────────────┤
│ Maria Garcia   │ Auth        │ LOGOUT  │ Logged out      │ 16:00
│                │             │         │ (Session: 45m)  │ 2025-12-03
└────────────────────────────────────────────────────────────┘

Click any row to see full JSON data in 'changes' field
```

---

END OF ARCHITECTURE DOCUMENT
