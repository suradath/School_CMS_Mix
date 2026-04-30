-- 1. Create pivot table for many-to-many relationship
CREATE TABLE IF NOT EXISTS `user_roles` (
    `user_id` INT NOT NULL,
    `role_id` INT NOT NULL,
    PRIMARY KEY (`user_id`, `role_id`),
    CONSTRAINT `fk_user_roles_user` FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE,
    CONSTRAINT `fk_user_roles_role` FOREIGN KEY (`role_id`) REFERENCES `roles`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 2. Ensure required roles exist in the roles table
-- Note: 'admin', 'editor', 'teacher' usually exist. Adding more common ones.
INSERT IGNORE INTO `roles` (`name`, `slug`, `description`) VALUES 
('Administrator', 'admin', 'Full access to the system'),
('Teacher', 'teacher', 'General teacher access'),
('Department Head', 'dept_head', 'Head of department access'),
('Staff/Officer', 'staff', 'General staff or officer access'),
('Editor', 'editor', 'Content editor access'),
('HR Manager', 'hr', 'Human Resources management access'),
('School Director', 'director', 'School director access');

-- 3. Migrate existing role data from users table to user_roles table
-- We match the string 'role' in users table with 'slug' in roles table
INSERT INTO user_roles (user_id, role_id)
SELECT u.id, r.id 
FROM users u
JOIN roles r ON u.role = r.slug
ON DUPLICATE KEY UPDATE role_id = r.id;

-- 4. After migration, the 'role' column in users table can be safely removed
-- We will do this via a separate step or just keep it for now but ignore it in code.
-- ALTER TABLE users DROP COLUMN role;
