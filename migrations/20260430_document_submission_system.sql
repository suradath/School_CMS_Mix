-- Document Submission System Tables
-- Created: 2026-04-30

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- 1. Submission Topics (Categories)
CREATE TABLE IF NOT EXISTS `submission_topics` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `title` VARCHAR(255) NOT NULL,
    `description` TEXT,
    `semester` ENUM('1', '2') NOT NULL,
    `academic_year` INT NOT NULL,
    `max_file_size` INT DEFAULT 20, -- in MB
    `status` ENUM('active', 'inactive') DEFAULT 'active',
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 2. Allowed File Extensions per Topic
CREATE TABLE IF NOT EXISTS `topic_allowed_files` (
    `topic_id` INT NOT NULL,
    `extension` VARCHAR(10) NOT NULL,
    PRIMARY KEY (`topic_id`, `extension`),
    FOREIGN KEY (`topic_id`) REFERENCES `submission_topics`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 3. Document Submissions
CREATE TABLE IF NOT EXISTS `document_submissions` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `topic_id` INT NOT NULL,
    `user_id` INT NOT NULL,
    `file_path` VARCHAR(255) NOT NULL,
    `original_filename` VARCHAR(255) NOT NULL,
    `mime_type` VARCHAR(100) NOT NULL,
    `status` ENUM('pending', 'approved', 'revision') DEFAULT 'pending',
    `feedback` TEXT,
    `submitted_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (`topic_id`) REFERENCES `submission_topics`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE,
    INDEX (`status`),
    INDEX (`topic_id`, `user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 4. Ensure 'academic' role exists
INSERT IGNORE INTO `roles` (`name`, `slug`, `description`) VALUES 
('Academic Staff', 'academic', 'Academic department management and monitoring');

SET FOREIGN_KEY_CHECKS = 1;
