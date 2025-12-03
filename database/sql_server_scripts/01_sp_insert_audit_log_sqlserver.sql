-- SQL Server Stored Procedure for Audit Log Insertion
-- Database: vantechdb
-- Compatibility: SQL Server 2016+
-- Description: Inserts audit log records with proper error handling

USE vantechdb;
GO

-- Drop procedure if it exists
IF EXISTS (
    SELECT *
    FROM sys.objects
    WHERE
        type = 'P'
        AND name = 'sp_insert_audit_log'
) BEGIN
DROP PROCEDURE sp_insert_audit_log;

END

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
        -- Insert audit log record
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
        
        -- Return success
        RETURN 1;
    END TRY
    BEGIN CATCH
        -- Log error and return failure
        PRINT 'Error inserting audit log: ' + ERROR_MESSAGE();
        RETURN 0;
    END CATCH
END;
GO

-- Grant permissions
GRANT EXECUTE ON sp_insert_audit_log TO PUBLIC;
GO

-- ==========================================
-- Test the procedure (optional)
-- ==========================================
-- EXEC sp_insert_audit_log
--     @p_user_id = 1,
--     @p_action = 'LOGIN',
--     @p_module = 'Authentication',
--     @p_description = 'John Doe logged in',
--     @p_changes = '{"username":"john.doe","role":"admin","ip_address":"192.168.1.100","login_time":"2025-12-03 14:30:00"}';