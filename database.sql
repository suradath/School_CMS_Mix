-- Database Schema for School CMS Mix V2.0
-- Updated: 2026-04-27 (Latest E-Saraban & Leave Module)

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- 1. Users & Authentication
CREATE TABLE IF NOT EXISTS `users` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `username` VARCHAR(50) NOT NULL UNIQUE,
    `password` VARCHAR(255) NOT NULL,
    `email` VARCHAR(100) NOT NULL UNIQUE,
    `full_name` VARCHAR(100) NOT NULL,
    `role` ENUM('admin', 'editor', 'teacher', 'officer', 'hr', 'director') DEFAULT 'teacher',
    `personnel_id` INT DEFAULT NULL,
    `last_login` DATETIME DEFAULT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX (`username`),
    INDEX (`role`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 2. Page & Content Management
CREATE TABLE IF NOT EXISTS `pages` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `title` VARCHAR(255) NOT NULL,
    `slug` VARCHAR(255) NOT NULL UNIQUE,
    `content` LONGTEXT DEFAULT NULL,
    `author_id` INT DEFAULT NULL,
    `status` ENUM('published', 'draft', 'archived') DEFAULT 'published',
    `type` ENUM('page', 'post') DEFAULT 'page',
    `meta_description` TEXT DEFAULT NULL,
    `featured_image` VARCHAR(255) DEFAULT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (`author_id`) REFERENCES `users`(`id`) ON DELETE SET NULL,
    INDEX (`slug`),
    INDEX (`status`),
    INDEX (`type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 3. Departments
CREATE TABLE IF NOT EXISTS `departments` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(150) NOT NULL UNIQUE,
    `description` TEXT DEFAULT NULL,
    `sort_order` INT DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 4. Personnel Management
CREATE TABLE IF NOT EXISTS `personnel` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(150) NOT NULL,
    `position` VARCHAR(150) DEFAULT NULL,
    `department_id` INT DEFAULT NULL,
    `image_url` VARCHAR(255) DEFAULT NULL,
    `email` VARCHAR(100) DEFAULT NULL,
    `phone` VARCHAR(20) DEFAULT NULL,
    `bio` TEXT DEFAULT NULL,
    `sort_order` INT DEFAULT 0,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (`department_id`) REFERENCES `departments`(`id`) ON DELETE SET NULL,
    INDEX (`department_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 5. News Categories
CREATE TABLE IF NOT EXISTS `news_categories` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(100) NOT NULL UNIQUE,
    `slug` VARCHAR(100) NOT NULL UNIQUE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 6. News & Events
CREATE TABLE IF NOT EXISTS `news` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `title` VARCHAR(255) NOT NULL,
    `content` TEXT DEFAULT NULL,
    `category_id` INT DEFAULT NULL,
    `author_id` INT DEFAULT NULL,
    `featured_image` VARCHAR(255) DEFAULT NULL,
    `status` ENUM('published', 'draft') DEFAULT 'published',
    `published_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (`category_id`) REFERENCES `news_categories`(`id`) ON DELETE SET NULL,
    FOREIGN KEY (`author_id`) REFERENCES `users`(`id`) ON DELETE SET NULL,
    INDEX (`status`),
    INDEX (`published_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 7. Media Gallery
CREATE TABLE IF NOT EXISTS `gallery_albums` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `title` VARCHAR(255) NOT NULL,
    `description` TEXT DEFAULT NULL,
    `cover_image` VARCHAR(255) DEFAULT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `gallery_images` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `album_id` INT NOT NULL,
    `image_url` VARCHAR(255) NOT NULL,
    `caption` VARCHAR(255) DEFAULT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (`album_id`) REFERENCES `gallery_albums`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 8. Academic Calendar
CREATE TABLE IF NOT EXISTS `academic_calendar` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `title` VARCHAR(255) NOT NULL,
    `description` TEXT DEFAULT NULL,
    `start_date` DATE NOT NULL,
    `end_date` DATE DEFAULT NULL,
    `color` VARCHAR(20) DEFAULT '#1d4ed8',
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 9. Entry Popups
CREATE TABLE IF NOT EXISTS `entry_popups` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `title` VARCHAR(255) NOT NULL,
    `image_url` VARCHAR(255) DEFAULT NULL,
    `link_url` VARCHAR(255) DEFAULT NULL,
    `is_active` TINYINT(1) DEFAULT 1,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 10. Leave Management
CREATE TABLE IF NOT EXISTS `leave_types` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(100) NOT NULL,
    `slug` VARCHAR(50) NOT NULL UNIQUE,
    `quota` INT DEFAULT 30
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `leave_requests` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `personnel_id` INT NOT NULL,
    `leave_type_id` INT NOT NULL,
    `title` VARCHAR(255) NOT NULL,
    `start_date` DATE NOT NULL,
    `end_date` DATE NOT NULL,
    `total_days` DECIMAL(5,1) NOT NULL,
    `reason` TEXT,
    `attachment_url` VARCHAR(255) DEFAULT NULL,
    `status` ENUM('pending', 'approved', 'rejected') DEFAULT 'pending',
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (`personnel_id`) REFERENCES `personnel`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`leave_type_id`) REFERENCES `leave_types`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 11. E-Saraban (Electronic Document Management)
CREATE TABLE IF NOT EXISTS `saraban_types` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(100) NOT NULL,
    `slug` VARCHAR(50) NOT NULL UNIQUE,
    `prefix` VARCHAR(50) DEFAULT NULL,
    `last_number` INT DEFAULT 0,
    `budget_year` INT DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `saraban_documents` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `type_id` INT NOT NULL,
    `doc_no` VARCHAR(50) NOT NULL,
    `book_no` VARCHAR(100) DEFAULT NULL,
    `title` VARCHAR(255) NOT NULL,
    `origin` VARCHAR(255) DEFAULT NULL,
    `priority` ENUM('normal', 'urgent', 'very_urgent') DEFAULT 'normal',
    `doc_date` DATE DEFAULT NULL,
    `received_date` DATE DEFAULT NULL,
    `file_url` VARCHAR(255) DEFAULT NULL,
    `status` ENUM('active', 'archived') DEFAULT 'active',
    `saraban_status` ENUM('pending', 'minuted', 'processed') DEFAULT 'pending',
    `created_by` INT NOT NULL,
    `budget_year` INT NOT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (`type_id`) REFERENCES `saraban_types`(`id`),
    FOREIGN KEY (`created_by`) REFERENCES `users`(`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `saraban_receivers` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `document_id` INT NOT NULL,
    `personnel_id` INT DEFAULT NULL,
    `department_id` INT DEFAULT NULL,
    `status` ENUM('unread', 'read') DEFAULT 'unread',
    `acknowledged_at` DATETIME DEFAULT NULL,
    FOREIGN KEY (`document_id`) REFERENCES `saraban_documents`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `saraban_minutes` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `document_id` INT NOT NULL,
    `user_id` INT NOT NULL,
    `note` TEXT NOT NULL,
    `decision` ENUM('none', 'approved', 'acknowledged', 'forwarded', 'rejected') DEFAULT 'none',
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (`document_id`) REFERENCES `saraban_documents`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`user_id`) REFERENCES `users`(`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 12. Menus & Settings
CREATE TABLE IF NOT EXISTS `menus` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `title` VARCHAR(100) NOT NULL,
    `url` VARCHAR(255) NOT NULL,
    `icon` VARCHAR(100) DEFAULT NULL,
    `parent_id` INT DEFAULT NULL,
    `sort_order` INT DEFAULT 0,
    `is_active` TINYINT(1) DEFAULT 1,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (`parent_id`) REFERENCES `menus`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `settings` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `setting_key` VARCHAR(100) NOT NULL UNIQUE,
    `setting_value` LONGTEXT DEFAULT NULL,
    `category` VARCHAR(50) DEFAULT 'general',
    INDEX (`setting_key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Initial Data
INSERT INTO `leave_types` (`name`, `slug`, `quota`) VALUES 
('ลาป่วย', 'sick', 30),
('ลากิจส่วนตัว', 'personal', 15),
('ลาพักผ่อน', 'vacation', 10),
('ลาคลอดบุตร', 'maternity', 90),
('ลาอุปสมบท', 'ordination', 120);

INSERT INTO `saraban_types` (`name`, `slug`, `prefix`) VALUES 
('ทะเบียนหนังสือรับ', 'inbound', ''),
('ทะเบียนหนังสือส่ง', 'outbound', 'ที่ ศธ 05.../'),
('ทะเบียนคำสั่ง/ประกาศ', 'orders', 'คำสั่งโรงเรียนที่');

INSERT INTO `menus` (`title`, `url`, `icon`, `sort_order`) VALUES 
('หน้าแรก', '/', 'fa-home', 1),
('ข่าวสาร', '/news-all', 'fa-newspaper-o', 2),
('บุคลากร', '/personnel-view', 'fa-users', 3),
('ภาพกิจกรรม', '/gallery-view', 'fa-picture-o', 4);

INSERT INTO `settings` (`setting_key`, `setting_value`, `category`) VALUES 
('site_name', 'โรงเรียนของเรา', 'general'),
('primary_color', '#1d4ed8', 'general'),
('site_logo', '', 'general');

INSERT INTO `departments` (`id`, `name`, `description`, `sort_order`) VALUES
(1, 'ฝ่ายบริหาร', NULL, 0),
(2, 'กลุ่มสาระการเรียนรู้ภาษาไทย', NULL, 1),
(3, 'กลุ่มสาระการเรียนรู้คณิตศาสตร์', NULL, 2),
(4, 'กลุ่มสาระการเรียนรู้วิทยาศาสตร์และเทคโนโลยี', NULL, 3),
(5, 'กลุ่มสาระการเรียนรู้สังคมศึกษา ศาสนา และวัฒนธรรม', NULL, 4),
(6, 'กลุ่มสาระการเรียนรู้สุขศึกษาและพลศึกษา', NULL, 5),
(7, 'กลุ่มสาระการเรียนรู้ศิลปะ', NULL, 6),
(8, 'กลุ่มสาระการเรียนรู้การงานอาชีพ', NULL, 7),
(9, 'กลุ่มสาระการเรียนรู้ภาษาต่างประเทศ', NULL, 8),
(10, 'กิจกรรมพัฒนาผู้เรียน', NULL, 9),
(11, 'ฝ่ายบริหารงานวิชาการ', NULL, 10),
(12, 'ฝ่ายบริหารงบประมาณ', NULL, 11),
(13, 'ฝ่ายบริหารงานบุคคล', NULL, 12),
(14, 'ฝ่ายบริหารทั่วไป', NULL, 13);


SET FOREIGN_KEY_CHECKS = 1;
