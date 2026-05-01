-- Migration: Add Booking System (Rooms & Vehicles)
-- Created: 2026-05-01

SET FOREIGN_KEY_CHECKS = 0;

-- 1. Resources Table
CREATE TABLE IF NOT EXISTS `booking_resources` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `type` ENUM('room', 'vehicle') NOT NULL,
    `name` VARCHAR(255) NOT NULL,
    `description` TEXT DEFAULT NULL,
    `capacity` INT DEFAULT 0,
    `license_plate` VARCHAR(50) DEFAULT NULL COMMENT 'Only for vehicles',
    `status` ENUM('available', 'maintenance') DEFAULT 'available',
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX (`type`),
    INDEX (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 2. Bookings Table
CREATE TABLE IF NOT EXISTS `bookings` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `user_id` INT NOT NULL,
    `resource_id` INT NOT NULL,
    `title` VARCHAR(255) NOT NULL COMMENT 'Purpose/Topic',
    `details` TEXT DEFAULT NULL,
    `start_time` DATETIME NOT NULL,
    `end_time` DATETIME NOT NULL,
    `participants_count` INT DEFAULT 0,
    `status` ENUM('pending', 'approved', 'rejected') DEFAULT 'pending',
    `rejection_reason` TEXT DEFAULT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`resource_id`) REFERENCES `booking_resources`(`id`) ON DELETE CASCADE,
    INDEX (`status`),
    INDEX (`start_time`),
    INDEX (`end_time`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 3. Initial Resource Types/Categories (Optional if using ENUM, but good for display)
-- Insert some sample data
INSERT IGNORE INTO `booking_resources` (`type`, `name`, `description`, `capacity`, `status`) VALUES
('room', 'ห้องประชุมอาคาร 1', 'ห้องประชุมขนาดใหญ่ พร้อมโปรเจคเตอร์', 50, 'available'),
('room', 'ห้องสมุด', 'โซนเงียบสงบ', 30, 'available');

INSERT IGNORE INTO `booking_resources` (`type`, `name`, `description`, `capacity`, `license_plate`, `status`) VALUES
('vehicle', 'รถตู้โรงเรียน (1)', 'รถตู้ Toyota Commuter', 12, 'กข-1234', 'available'),
('vehicle', 'รถบัส (1)', 'รถบัสรับส่งนักเรียน', 40, 'มฐ-5678', 'available');

SET FOREIGN_KEY_CHECKS = 1;
