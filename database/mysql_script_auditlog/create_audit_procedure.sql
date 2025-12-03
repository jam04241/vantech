-- Database: vantechDB
-- Description: Stored Procedure to insert audit logs
-- for phpMyAmin
USE vantechDB;

DROP PROCEDURE IF EXISTS sp_insert_audit_log;

DELIMITER $$

CREATE PROCEDURE sp_insert_audit_log(
    IN p_user_id INT,
    IN p_action VARCHAR(50),
    IN p_module VARCHAR(100),
    IN p_description TEXT,
    IN p_changes JSON
)
BEGIN
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

-- Test the procedure
-- CALL sp_insert_audit_log(1, 'LOGIN', 'Authentication', 'User logged in', NULL);