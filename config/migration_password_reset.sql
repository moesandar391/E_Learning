-- Adds password-reset support to the users table.
-- Run once in phpMyAdmin (or: mysql -u root -P 3308 e_learning < migration_password_reset.sql)

ALTER TABLE users
    ADD COLUMN reset_token VARCHAR(64) DEFAULT NULL,
    ADD COLUMN reset_token_expires DATETIME DEFAULT NULL;
