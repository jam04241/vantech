-- MySQL Stored Procedure for Audit Log Insertion
-- Database: vantechdb
-- Compatibility: MySQL 5.7+
-- Description: Inserts audit log records with proper error handling

USE vantechdb;

-- Drop procedure if it exists
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
        -- Handle any errors gracefully
        SELECT 'Error inserting audit log' AS error_message;
    END;
    
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
        p_user_id,
        p_action,
        p_module,
        p_description,
        p_changes,
        NOW(),
        NOW()
    );
END$$

DELIMITER;

-- ==========================================
-- Test the procedure (optional)
-- ==========================================
-- CALL sp_insert_audit_log(
--     1,
--     'LOGIN',
--     'Authentication',
--     'John Doe logged in',
--     '{"username":"john.doe","role":"admin","ip_address":"192.168.1.100","login_time":"2025-12-03 14:30:00"}'
-- );

-- ==========================================
-- To list all parameters of the procedure:
-- ==========================================
-- CALL INFORMATION_SCHEMA.ROUTINES WHERE ROUTINE_NAME = 'sp_insert_audit_log';