LOGIN/LOGOUT AUDIT LOGGING - SQL REFERENCE & TESTING
===================================================

## INSTALLATION COMMANDS

### For MySQL Database

#### Option 1: Using Command Line
```bash
cd j:\Vantech\TESTING\COMPUTERSHOP_INVENTORY
mysql -u root -p vantechdb < database/sql_server_scripts/02_sp_insert_audit_log_mysql.sql
```

#### Option 2: Using MySQL Workbench
1. Open MySQL Workbench
2. Connect to vantechdb
3. File → Open SQL Script
4. Select: `database/sql_server_scripts/02_sp_insert_audit_log_mysql.sql`
5. Execute (Ctrl+Shift+Enter)

#### Option 3: Manual Copy-Paste
```sql
USE vantechdb;

DROP PROCEDURE IF EXISTS sp_insert_audit_log;

DELIMITER $$

CREATE PROCEDURE sp_insert_audit_log(
    IN p_user_id INT,
    IN p_action VARCHAR(50),
    IN p_module VARCHAR(100),
    IN p_description LONGTEXT,
    IN p_changes JSON
)
BEGIN
    DECLARE EXIT HANDLER FOR SQLEXCEPTION
    BEGIN
        SELECT 'Error inserting audit log' AS error_message;
    END;
    
    INSERT INTO auditlogs (
        user_id,
        action,
        module,
        description,
        changes,
        created_at,
        updated_at
    )
    VALUES (
        p_user_id,
        p_action,
        p_module,
        p_description,
        p_changes,
        NOW(),
        NOW()
    );
END$$

DELIMITER ;
```

---

### For SQL Server Database

#### Option 1: Using SQL Server Management Studio (SSMS)
1. Open SQL Server Management Studio
2. Connect to your server
3. Click "New Query"
4. Copy and paste the entire content of: `database/sql_server_scripts/01_sp_insert_audit_log_sqlserver.sql`
5. Execute (F5)

#### Option 2: Using sqlcmd (Command Line)
```bash
sqlcmd -S your_server -U sa -P your_password -d vantechdb -i database\sql_server_scripts\01_sp_insert_audit_log_sqlserver.sql
```

#### Option 3: Manual Copy-Paste
```sql
USE vantechdb;
GO

IF EXISTS (SELECT * FROM sys.objects WHERE type = 'P' AND name = 'sp_insert_audit_log')
BEGIN
    DROP PROCEDURE sp_insert_audit_log;
END
GO

CREATE PROCEDURE sp_insert_audit_log
    @p_user_id INT,
    @p_action VARCHAR(50),
    @p_module VARCHAR(100),
    @p_description NVARCHAR(MAX),
    @p_changes NVARCHAR(MAX)
AS
BEGIN
    SET NOCOUNT ON;
    
    BEGIN TRY
        INSERT INTO auditlogs (
            user_id,
            action,
            module,
            description,
            changes,
            created_at,
            updated_at
        )
        VALUES (
            @p_user_id,
            @p_action,
            @p_module,
            @p_description,
            @p_changes,
            GETUTCDATE(),
            GETUTCDATE()
        );
        
        RETURN 1;
    END TRY
    BEGIN CATCH
        PRINT 'Error inserting audit log: ' + ERROR_MESSAGE();
        RETURN 0;
    END CATCH
END;
GO

GRANT EXECUTE ON sp_insert_audit_log TO PUBLIC;
GO
```

---

## VERIFICATION QUERIES

### Check if Stored Procedure Exists

**MySQL:**
```sql
SHOW PROCEDURE STATUS LIKE 'sp_insert_audit_log';
```

Expected output:
```
Db      | Name                    | Type      | Definer      | Modified            | Created             | Security_type
--------|-------------------------|-----------|--------------|---------------------|---------------------|---------------
vantechdb | sp_insert_audit_log   | PROCEDURE | root@localhost | 2025-12-03 14:30:00 | 2025-12-03 14:30:00 | DEFINER
```

**SQL Server:**
```sql
SELECT * FROM sys.procedures WHERE name = 'sp_insert_audit_log';
```

Expected output: 1 row with procedure_id and other details

---

### Test Stored Procedure Manually

#### Test 1: Direct Procedure Call (Login)

**MySQL:**
```sql
CALL sp_insert_audit_log(
    1,
    'LOGIN',
    'Authentication',
    'Test User logged in',
    '{"username":"test.user","role":"admin","ip_address":"127.0.0.1","login_time":"2025-12-03 14:30:00"}'
);
```

**SQL Server:**
```sql
EXEC sp_insert_audit_log
    @p_user_id = 1,
    @p_action = 'LOGIN',
    @p_module = 'Authentication',
    @p_description = 'Test User logged in',
    @p_changes = '{"username":"test.user","role":"admin","ip_address":"127.0.0.1","login_time":"2025-12-03 14:30:00"}';
```

#### Test 2: Direct Procedure Call (Logout)

**MySQL:**
```sql
CALL sp_insert_audit_log(
    1,
    'LOGOUT',
    'Authentication',
    'Test User logged out (Session: 2h 30m)',
    '{"username":"test.user","role":"admin","ip_address":"127.0.0.1","logout_time":"2025-12-03 17:00:00","session_duration_minutes":150}'
);
```

**SQL Server:**
```sql
EXEC sp_insert_audit_log
    @p_user_id = 1,
    @p_action = 'LOGOUT',
    @p_module = 'Authentication',
    @p_description = 'Test User logged out (Session: 2h 30m)',
    @p_changes = '{"username":"test.user","role":"admin","ip_address":"127.0.0.1","logout_time":"2025-12-03 17:00:00","session_duration_minutes":150}';
```

**Both will return:** Success message or error in output

---

## VIEW LOGGED DATA

### View All Authentication Logs

**MySQL:**
```sql
SELECT 
    id,
    user_id,
    action,
    module,
    description,
    ip_address,
    created_at
FROM auditlogs
WHERE module = 'Authentication'
ORDER BY created_at DESC
LIMIT 20;
```

**SQL Server:**
```sql
SELECT TOP 20
    id,
    user_id,
    action,
    module,
    description,
    ip_address,
    created_at
FROM auditlogs
WHERE module = 'Authentication'
ORDER BY created_at DESC;
```

Expected output:
```
id  | user_id | action | module        | description                           | ip_address    | created_at
----|---------|--------|---------------|---------------------------------------|---------------|-------------------
2   | 1       | LOGOUT | Authentication| Test User logged out (Session: 2h 30m)| 127.0.0.1     | 2025-12-03 17:00:00
1   | 1       | LOGIN  | Authentication| Test User logged in                   | 127.0.0.1     | 2025-12-03 14:30:00
```

---

### View Login History for Specific User

**MySQL:**
```sql
SELECT 
    id,
    user_id,
    action,
    description,
    created_at
FROM auditlogs
WHERE module = 'Authentication'
  AND user_id = 1  -- Change to your user ID
ORDER BY created_at DESC;
```

**SQL Server:**
```sql
SELECT 
    id,
    user_id,
    action,
    description,
    created_at
FROM auditlogs
WHERE module = 'Authentication'
  AND user_id = 1  -- Change to your user ID
ORDER BY created_at DESC;
```

---

### View Login/Logout Pairs with Session Duration

**MySQL:**
```sql
SELECT 
    user_id,
    action,
    description,
    JSON_EXTRACT(changes, '$.session_duration_minutes') AS session_minutes,
    created_at
FROM auditlogs
WHERE module = 'Authentication'
ORDER BY user_id, created_at DESC;
```

**SQL Server:**
```sql
SELECT 
    user_id,
    action,
    description,
    JSON_VALUE(changes, '$.session_duration_minutes') AS session_minutes,
    created_at
FROM auditlogs
WHERE module = 'Authentication'
ORDER BY user_id, created_at DESC;
```

---

### View Complete Record with JSON Data

**MySQL:**
```sql
SELECT 
    id,
    user_id,
    action,
    module,
    description,
    changes,
    ip_address,
    created_at
FROM auditlogs
WHERE module = 'Authentication'
  AND action = 'LOGIN'
ORDER BY created_at DESC
LIMIT 1;
```

Expected output includes full JSON:
```json
{
  "username": "john.doe",
  "role": "admin",
  "ip_address": "192.168.1.100",
  "login_time": "2025-12-03 14:30:00"
}
```

**SQL Server:**
```sql
SELECT 
    id,
    user_id,
    action,
    module,
    description,
    changes,
    ip_address,
    created_at
FROM auditlogs
WHERE module = 'Authentication'
  AND action = 'LOGIN'
ORDER BY created_at DESC;
```

---

### Count Login/Logout per User

**MySQL:**
```sql
SELECT 
    u.first_name,
    u.last_name,
    COUNT(CASE WHEN a.action = 'LOGIN' THEN 1 END) AS login_count,
    COUNT(CASE WHEN a.action = 'LOGOUT' THEN 1 END) AS logout_count,
    MAX(a.created_at) AS last_activity
FROM users u
LEFT JOIN auditlogs a ON u.id = a.user_id AND a.module = 'Authentication'
GROUP BY u.id, u.first_name, u.last_name
ORDER BY MAX(a.created_at) DESC;
```

**SQL Server:**
```sql
SELECT 
    u.first_name,
    u.last_name,
    COUNT(CASE WHEN a.action = 'LOGIN' THEN 1 END) AS login_count,
    COUNT(CASE WHEN a.action = 'LOGOUT' THEN 1 END) AS logout_count,
    MAX(a.created_at) AS last_activity
FROM users u
LEFT JOIN auditlogs a ON u.id = a.user_id AND a.module = 'Authentication'
GROUP BY u.id, u.first_name, u.last_name
ORDER BY MAX(a.created_at) DESC;
```

Expected output:
```
first_name | last_name | login_count | logout_count | last_activity
-----------|-----------|-------------|--------------|-------------------
John       | Doe       | 5           | 4            | 2025-12-03 17:00:00
Maria      | Garcia    | 3           | 3            | 2025-12-03 16:45:00
```

---

### Find Sessions Longer Than 4 Hours

**MySQL:**
```sql
SELECT 
    u.first_name,
    u.last_name,
    a.description,
    JSON_EXTRACT(a.changes, '$.session_duration_minutes') AS minutes,
    ROUND(JSON_EXTRACT(a.changes, '$.session_duration_minutes') / 60, 2) AS hours,
    a.created_at
FROM auditlogs a
JOIN users u ON a.user_id = u.id
WHERE a.module = 'Authentication'
  AND a.action = 'LOGOUT'
  AND JSON_EXTRACT(a.changes, '$.session_duration_minutes') > 240
ORDER BY JSON_EXTRACT(a.changes, '$.session_duration_minutes') DESC;
```

**SQL Server:**
```sql
SELECT 
    u.first_name,
    u.last_name,
    a.description,
    CAST(JSON_VALUE(a.changes, '$.session_duration_minutes') AS INT) AS minutes,
    ROUND(CAST(JSON_VALUE(a.changes, '$.session_duration_minutes') AS INT) / 60.0, 2) AS hours,
    a.created_at
FROM auditlogs a
JOIN users u ON a.user_id = u.id
WHERE a.module = 'Authentication'
  AND a.action = 'LOGOUT'
  AND CAST(JSON_VALUE(a.changes, '$.session_duration_minutes') AS INT) > 240
ORDER BY CAST(JSON_VALUE(a.changes, '$.session_duration_minutes') AS INT) DESC;
```

---

### Find Very Short Sessions (Less Than 5 Minutes)

**MySQL:**
```sql
SELECT 
    u.first_name,
    u.last_name,
    a.description,
    JSON_EXTRACT(a.changes, '$.session_duration_minutes') AS minutes,
    a.created_at
FROM auditlogs a
JOIN users u ON a.user_id = u.id
WHERE a.module = 'Authentication'
  AND a.action = 'LOGOUT'
  AND JSON_EXTRACT(a.changes, '$.session_duration_minutes') < 5
ORDER BY a.created_at DESC;
```

**SQL Server:**
```sql
SELECT 
    u.first_name,
    u.last_name,
    a.description,
    CAST(JSON_VALUE(a.changes, '$.session_duration_minutes') AS INT) AS minutes,
    a.created_at
FROM auditlogs a
JOIN users u ON a.user_id = u.id
WHERE a.module = 'Authentication'
  AND a.action = 'LOGOUT'
  AND CAST(JSON_VALUE(a.changes, '$.session_duration_minutes') AS INT) < 5
ORDER BY a.created_at DESC;
```

---

### Clean Up Test Records

**MySQL:**
```sql
DELETE FROM auditlogs
WHERE module = 'Authentication'
  AND description LIKE 'Test User%';
```

**SQL Server:**
```sql
DELETE FROM auditlogs
WHERE module = 'Authentication'
  AND description LIKE 'Test User%';
```

---

## REAL-WORLD SCENARIOS

### Scenario: Track Multiple Users in One Day

```sql
SELECT 
    DATE(created_at) AS login_date,
    u.first_name,
    u.last_name,
    a.action,
    a.description,
    a.created_at
FROM auditlogs a
JOIN users u ON a.user_id = u.id
WHERE a.module = 'Authentication'
  AND DATE(created_at) = CURDATE()  -- Today's date
ORDER BY a.created_at DESC;
```

Output shows all logins/logouts for today.

### Scenario: Generate Session Report

```sql
SELECT 
    u.first_name,
    u.last_name,
    COUNT(CASE WHEN a.action = 'LOGIN' THEN 1 END) AS sessions,
    ROUND(AVG(JSON_EXTRACT(a.changes, '$.session_duration_minutes')), 2) AS avg_session_minutes,
    MAX(JSON_EXTRACT(a.changes, '$.session_duration_minutes')) AS longest_session_minutes
FROM auditlogs a
JOIN users u ON a.user_id = u.id
WHERE a.module = 'Authentication'
  AND a.action = 'LOGOUT'
  AND created_at >= DATE_SUB(NOW(), INTERVAL 7 DAY)  -- Last 7 days
GROUP BY u.id
ORDER BY sessions DESC;
```

Output:
```
first_name | last_name | sessions | avg_session_minutes | longest_session_minutes
-----------|-----------|----------|---------------------|------------------------
John       | Doe       | 15       | 180.50              | 480
Maria      | Garcia    | 12       | 120.25              | 300
```

---

## GRANT PERMISSIONS (If Needed)

### MySQL Permissions

```sql
-- Grant EXECUTE permission on stored procedure
GRANT EXECUTE ON vantechdb.sp_insert_audit_log TO 'vantechdb_user'@'localhost';

-- Grant SELECT permission on auditlogs table
GRANT SELECT ON vantechdb.auditlogs TO 'vantechdb_user'@'localhost';

-- Apply changes
FLUSH PRIVILEGES;
```

### SQL Server Permissions

```sql
-- Grant EXECUTE permission on stored procedure
GRANT EXECUTE ON sp_insert_audit_log TO [domain\username];
-- or
GRANT EXECUTE ON sp_insert_audit_log TO public;

-- Grant SELECT permission on auditlogs table
GRANT SELECT ON auditlogs TO [domain\username];
```

---

## TROUBLESHOOTING QUERIES

### Check Database Connection

**MySQL:**
```sql
SELECT 'MySQL Connection OK' AS status;
```

**SQL Server:**
```sql
SELECT 'SQL Server Connection OK' AS status;
```

### Verify Table Structure

**MySQL:**
```sql
DESC auditlogs;
```

**SQL Server:**
```sql
EXEC sp_columns @table_name = 'auditlogs';
```

### Check Recent Errors

**MySQL:**
```sql
SHOW ERRORS;
SHOW WARNINGS;
```

**SQL Server:**
```sql
SELECT * FROM sys.messages WHERE severity > 10;
```

### Monitor Stored Procedure Execution

**MySQL:**
```sql
-- Enable general query log to see procedure calls
SET GLOBAL general_log = 'ON';
SHOW VARIABLES LIKE 'general_log%';
```

**SQL Server:**
```sql
-- Run SQL Profiler to track procedure calls
-- In SSMS: Tools → SQL Server Profiler
```

---

## AUTOMATED TESTING SCRIPT

### Test Login/Logout Cycle (MySQL)

```sql
-- Insert test user
INSERT INTO users (username, password, first_name, last_name, role)
VALUES ('test.user', MD5('password'), 'Test', 'User', 'staff');

-- Get inserted user ID
SET @user_id = LAST_INSERT_ID();

-- Test LOGIN
CALL sp_insert_audit_log(
    @user_id,
    'LOGIN',
    'Authentication',
    CONCAT('Test User logged in'),
    JSON_OBJECT(
        'username', 'test.user',
        'role', 'staff',
        'ip_address', '192.168.1.100',
        'login_time', NOW()
    )
);

-- Wait 1 second
SELECT SLEEP(1);

-- Test LOGOUT
CALL sp_insert_audit_log(
    @user_id,
    'LOGOUT',
    'Authentication',
    'Test User logged out (Session: less than 1m)',
    JSON_OBJECT(
        'username', 'test.user',
        'role', 'staff',
        'ip_address', '192.168.1.100',
        'logout_time', NOW(),
        'session_duration_minutes', 1
    )
);

-- View results
SELECT * FROM auditlogs
WHERE user_id = @user_id
  AND module = 'Authentication'
ORDER BY created_at DESC;

-- Cleanup
DELETE FROM auditlogs WHERE user_id = @user_id;
DELETE FROM users WHERE id = @user_id;
```

---

## SUCCESSFUL INSTALLATION CHECKLIST

- [ ] Stored procedure created in database
- [ ] Test procedure call returned no errors
- [ ] Manual INSERT works when running procedure
- [ ] Audit logs table has new records
- [ ] Can SELECT records from auditlogs table
- [ ] AuthController.php has been updated with new methods
- [ ] Test login succeeds and audit log is created
- [ ] Test logout succeeds and audit log is created
- [ ] Audit Logs page (/Audit) displays records
- [ ] Can filter by "Authentication" module
- [ ] Can see LOGIN and LOGOUT actions
- [ ] Session duration appears in descriptions (for LOGOUT)

---

That's your complete SQL reference! Use these queries to verify installation, test functionality, and troubleshoot any issues.
