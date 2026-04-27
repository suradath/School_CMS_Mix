-- Leave Management Module Migration
-- Created: 2024-04-27

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- 1. Leave Types Table
CREATE TABLE IF NOT EXISTS `leave_types` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(100) NOT NULL,
    `slug` VARCHAR(50) NOT NULL UNIQUE,
    `default_quota` INT DEFAULT 30,
    `color` VARCHAR(20) DEFAULT '#3b82f6',
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 2. Leave Requests Table
CREATE TABLE IF NOT EXISTS `leave_requests` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `personnel_id` INT NOT NULL,
    `leave_type_id` INT NOT NULL,
    `start_date` DATE NOT NULL,
    `end_date` DATE NOT NULL,
    `total_days` DECIMAL(5,1) NOT NULL,
    `reason` TEXT,
    `attachment_url` VARCHAR(255) DEFAULT NULL,
    `status` ENUM('pending', 'approved', 'rejected', 'cancelled') DEFAULT 'pending',
    `dept_head_comment` TEXT DEFAULT NULL,
    `admin_comment` TEXT DEFAULT NULL,
    `approved_by` INT DEFAULT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (`personnel_id`) REFERENCES `personnel`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`leave_type_id`) REFERENCES `leave_types`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`approved_by`) REFERENCES `users`(`id`) ON DELETE SET NULL,
    INDEX (`status`),
    INDEX (`start_date`),
    INDEX (`personnel_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 3. Personnel Leave Quotas (Overrides)
CREATE TABLE IF NOT EXISTS `personnel_leave_quotas` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `personnel_id` INT NOT NULL,
    `leave_type_id` INT NOT NULL,
    `quota` INT NOT NULL,
    `year` INT NOT NULL,
    UNIQUE KEY `unique_quota` (`personnel_id`, `leave_type_id`, `year`),
    FOREIGN KEY (`personnel_id`) REFERENCES `personnel`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`leave_type_id`) REFERENCES `leave_types`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Initial Leave Types Data
INSERT INTO `leave_types` (`name`, `slug`, `default_quota`, `color`) VALUES 
('ลาป่วย', 'sick', 30, '#ef4444'),
('ลากิจ', 'personal', 15, '#f59e0b'),
('ลาพักผ่อน', 'vacation', 10, '#10b981'),
('ลาคลอด', 'maternity', 90, '#ec4899'),
('ลาอุปสมบท', 'ordination', 120, '#f97316');

SET FOREIGN_KEY_CHECKS = 1;
