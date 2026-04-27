-- Migration: Add RBAC and User-Personnel Integration
-- Date: 2024-04-27

SET FOREIGN_KEY_CHECKS = 0;

-- 1. Create Roles Table (Optional but requested "roles" table)
CREATE TABLE IF NOT EXISTS `roles` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(50) NOT NULL UNIQUE,
    `slug` VARCHAR(50) NOT NULL UNIQUE,
    `description` TEXT DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insert default roles
INSERT IGNORE INTO `roles` (`name`, `slug`, `description`) VALUES 
('Administrator', 'admin', 'Full access to the system'),
('Editor/Staff', 'editor', 'Manage news, gallery and personnel in their department'),
('Teacher/User', 'teacher', 'Manage own profile and contributions');

-- 2. Update Users Table
ALTER TABLE `users` 
    ADD COLUMN `personnel_id` INT NULL AFTER `full_name`,
    ADD COLUMN `status` ENUM('active', 'inactive') DEFAULT 'active' AFTER `role`,
    MODIFY COLUMN `role` ENUM('admin', 'editor', 'teacher') DEFAULT 'teacher';

-- Add Foreign Key for personnel_id
ALTER TABLE `users` 
    ADD CONSTRAINT `fk_user_personnel` 
    FOREIGN KEY (`personnel_id`) REFERENCES `personnel`(`id`) 
    ON DELETE SET NULL;

-- 3. Update existing users (Map admin role)
UPDATE `users` SET `role` = 'admin' WHERE `role` = 'admin';
UPDATE `users` SET `role` = 'editor' WHERE `role` = 'staff';

SET FOREIGN_KEY_CHECKS = 1;
