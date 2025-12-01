-- ============================================================================
-- SQL SERVER AUDIT LOG SYSTEM FOR SERVICES MODULE
-- ============================================================================
-- This script creates the necessary tables and triggers to audit all
-- service operations (CREATE, UPDATE, DELETE) in SQL Server

-- ============================================================================
-- 1. CREATE AUDIT TABLE
-- ============================================================================
CREATE TABLE IF NOT EXISTS services_audit_log (
    audit_id BIGINT PRIMARY KEY IDENTITY (1, 1),
    service_id BIGINT,
    action VARCHAR(50) NOT NULL, -- 'INSERT', 'UPDATE', 'DELETE'
    old_values NVARCHAR (MAX), -- JSON format of old values
    new_values NVARCHAR (MAX), -- JSON format of new values
    changed_by NVARCHAR (255), -- User who made the change
    changed_at DATETIME DEFAULT GETDATE (),
    ip_address NVARCHAR (50),
    affected_columns NVARCHAR (MAX)
);

-- Create indexes for better performance
CREATE INDEX idx_audit_service_id ON services_audit_log (service_id);

CREATE INDEX idx_audit_action ON services_audit_log (action);

CREATE INDEX idx_audit_changed_at ON services_audit_log (changed_at);

-- ============================================================================
-- 2. CREATE TRIGGER FOR INSERT OPERATIONS
-- ============================================================================
CREATE TRIGGER tr_services_insert
ON services
AFTER INSERT
AS
BEGIN
    SET NOCOUNT ON;
    
    INSERT INTO services_audit_log (
        service_id,
        action,
        old_values,
        new_values,
        changed_by,
        changed_at,
        affected_columns
    )
    SELECT
        inserted.id,
        'INSERT',
        NULL,
        (SELECT 
            'customer_name', inserted.customer_name,
            'service_type', inserted.service_type,
            'description', inserted.description,
            'status', inserted.status,
            'priority', inserted.priority,
            'user_id', inserted.user_id,
            'created_at', inserted.created_at
        FOR JSON PATH, WITHOUT_ARRAY_WRAPPER),
        ISNULL(SYSTEM_USER, 'UNKNOWN'),
        GETDATE(),
        'customer_name, service_type, description, status, priority, user_id'
    FROM inserted;
END;

-- ============================================================================
-- 3. CREATE TRIGGER FOR UPDATE OPERATIONS
-- ============================================================================
CREATE TRIGGER tr_services_update
ON services
AFTER UPDATE
AS
BEGIN
    SET NOCOUNT ON;
    
    INSERT INTO services_audit_log (
        service_id,
        action,
        old_values,
        new_values,
        changed_by,
        changed_at,
        affected_columns
    )
    SELECT
        inserted.id,
        'UPDATE',
        (SELECT 
            'customer_name', deleted.customer_name,
            'service_type', deleted.service_type,
            'description', deleted.description,
            'status', deleted.status,
            'priority', deleted.priority,
            'user_id', deleted.user_id,
            'updated_at', deleted.updated_at
        FOR JSON PATH, WITHOUT_ARRAY_WRAPPER),
        (SELECT 
            'customer_name', inserted.customer_name,
            'service_type', inserted.service_type,
            'description', inserted.description,
            'status', inserted.status,
            'priority', inserted.priority,
            'user_id', inserted.user_id,
            'updated_at', inserted.updated_at
        FOR JSON PATH, WITHOUT_ARRAY_WRAPPER),
        ISNULL(SYSTEM_USER, 'UNKNOWN'),
        GETDATE(),
        CASE 
            WHEN deleted.customer_name <> inserted.customer_name THEN 'customer_name, '
            ELSE ''
        END +
        CASE 
            WHEN deleted.service_type <> inserted.service_type THEN 'service_type, '
            ELSE ''
        END +
        CASE 
            WHEN deleted.description <> inserted.description THEN 'description, '
            ELSE ''
        END +
        CASE 
            WHEN deleted.status <> inserted.status THEN 'status, '
            ELSE ''
        END +
        CASE 
            WHEN deleted.priority <> inserted.priority THEN 'priority, '
            ELSE ''
        END +
        CASE 
            WHEN ISNULL(deleted.user_id, 0) <> ISNULL(inserted.user_id, 0) THEN 'user_id'
            ELSE ''
        END
    FROM inserted
    JOIN deleted ON inserted.id = deleted.id;
END;

-- ============================================================================
-- 4. CREATE TRIGGER FOR DELETE OPERATIONS
-- ============================================================================
CREATE TRIGGER tr_services_delete
ON services
AFTER DELETE
AS
BEGIN
    SET NOCOUNT ON;
    
    INSERT INTO services_audit_log (
        service_id,
        action,
        old_values,
        new_values,
        changed_by,
        changed_at,
        affected_columns
    )
    SELECT
        deleted.id,
        'DELETE',
        (SELECT 
            'customer_name', deleted.customer_name,
            'service_type', deleted.service_type,
            'description', deleted.description,
            'status', deleted.status,
            'priority', deleted.priority,
            'user_id', deleted.user_id,
            'created_at', deleted.created_at
        FOR JSON PATH, WITHOUT_ARRAY_WRAPPER),
        NULL,
        ISNULL(SYSTEM_USER, 'UNKNOWN'),
        GETDATE(),
        'ALL'
    FROM deleted;
END;

-- ============================================================================
-- 5. STORED PROCEDURE TO RETRIEVE AUDIT LOGS
-- ============================================================================
CREATE PROCEDURE sp_get_services_audit_log
    @service_id BIGINT = NULL,
    @action VARCHAR(50) = NULL,
    @start_date DATETIME = NULL,
    @end_date DATETIME = NULL,
    @page INT = 1,
    @page_size INT = 50
AS
BEGIN
    DECLARE @offset INT = (@page - 1) * @page_size;
    
    SELECT 
        audit_id,
        service_id,
        action,
        old_values,
        new_values,
        changed_by,
        changed_at,
        affected_columns
    FROM services_audit_log
    WHERE 
        (service_id = @service_id OR @service_id IS NULL)
        AND (action = @action OR @action IS NULL)
        AND (changed_at >= @start_date OR @start_date IS NULL)
        AND (changed_at <= @end_date OR @end_date IS NULL)
    ORDER BY changed_at DESC
    OFFSET @offset ROWS
    FETCH NEXT @page_size ROWS ONLY;
END;

-- ============================================================================
-- 6. STORED PROCEDURE TO GET AUDIT SUMMARY
-- ============================================================================
CREATE PROCEDURE sp_get_audit_summary
    @service_id BIGINT = NULL,
    @start_date DATETIME = NULL,
    @end_date DATETIME = NULL
AS
BEGIN
    SELECT 
        action,
        COUNT(*) as count,
        MIN(changed_at) as first_change,
        MAX(changed_at) as last_change,
        COUNT(DISTINCT changed_by) as users_involved
    FROM services_audit_log
    WHERE 
        (service_id = @service_id OR @service_id IS NULL)
        AND (changed_at >= @start_date OR @start_date IS NULL)
        AND (changed_at <= @end_date OR @end_date IS NULL)
    GROUP BY action;
END;

-- ============================================================================
-- 7. STORED PROCEDURE TO PURGE OLD AUDIT LOGS (Monthly Maintenance)
-- ============================================================================
CREATE PROCEDURE sp_purge_old_audit_logs
    @days_to_keep INT = 365
AS
BEGIN
    DECLARE @cutoff_date DATETIME = DATEADD(DAY, -@days_to_keep, GETDATE());
    
    DELETE FROM services_audit_log
    WHERE changed_at < @cutoff_date;
    
    SELECT @@ROWCOUNT as rows_deleted, @cutoff_date as purge_date;
END;

-- ============================================================================
-- 8. USAGE EXAMPLES
-- ============================================================================
/*

-- Example 1: Get all audit logs for a specific service
EXEC sp_get_services_audit_log 
@service_id = 1;

-- Example 2: Get all updates made to services
EXEC sp_get_services_audit_log 
@action = 'UPDATE';

-- Example 3: Get audit logs within a date range
EXEC sp_get_services_audit_log 
@start_date = '2024-01-01',
@end_date = '2024-01-31';

-- Example 4: Get summary of all changes
EXEC sp_get_audit_summary;

-- Example 5: Purge logs older than 365 days
EXEC sp_purge_old_audit_logs @days_to_keep = 365;

-- Example 6: View audit logs directly
SELECT TOP 100 * FROM services_audit_log ORDER BY changed_at DESC;

*/