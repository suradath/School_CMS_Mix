-- Migration: Create Visitor Counter Table
-- Date: 2024-04-29

CREATE TABLE IF NOT EXISTS `visitor_counter` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `page_url` TEXT NOT NULL,
    `ip_address` VARCHAR(50) DEFAULT NULL,
    `session_id` VARCHAR(100) DEFAULT NULL,
    `user_agent` TEXT DEFAULT NULL,
    `visited_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX (`session_id`),
    INDEX (`visited_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
