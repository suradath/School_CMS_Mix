-- Database Schema for School CMS Mix V1.2
-- PHP 8 (Strict Types) Compatible

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- 1. Users & Authentication
CREATE TABLE IF NOT EXISTS `users` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `username` VARCHAR(50) NOT NULL UNIQUE,
    `password` VARCHAR(255) NOT NULL,
    `email` VARCHAR(100) NOT NULL UNIQUE,
    `full_name` VARCHAR(100) NOT NULL,
    `role` ENUM('admin', 'staff') DEFAULT 'staff',
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

-- 3. Departments (กลุ่มสาระการเรียนรู้ / ฝ่ายงาน)
CREATE TABLE IF NOT EXISTS `departments` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(150) NOT NULL UNIQUE,
    `description` TEXT DEFAULT NULL,
    `sort_order` INT DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 4. Personnel Management (บุคลากร)
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

-- 8. Visitor Counter (Session-based)
CREATE TABLE IF NOT EXISTS `visitor_counter` (
    `id` BIGINT AUTO_INCREMENT PRIMARY KEY,
    `page_url` VARCHAR(255) NOT NULL,
    `ip_address` VARCHAR(45) NOT NULL,
    `session_id` VARCHAR(100) NOT NULL,
    `user_agent` TEXT DEFAULT NULL,
    `visited_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX (`visited_at`),
    INDEX (`page_url`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 10. Dynamic Menu Management
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

-- Initial Menu Data
INSERT INTO `menus` (`title`, `url`, `icon`, `sort_order`) VALUES 
('หน้าแรก', '/', 'fa-home', 1),
('ข่าวสาร', '/news-all', 'fa-newspaper-o', 2),
('บุคลากร', '/personnel-view', 'fa-users', 3),
('ภาพกิจกรรม', '/gallery-view', 'fa-picture-o', 4);

-- 9. System Settings
CREATE TABLE IF NOT EXISTS `settings` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `setting_key` VARCHAR(100) NOT NULL UNIQUE,
    `setting_value` LONGTEXT DEFAULT NULL,
    `category` VARCHAR(50) DEFAULT 'general',
    INDEX (`setting_key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `settings` (`setting_key`, `setting_value`, `category`) VALUES 
('site_name', 'โรงเรียนของเรา', 'general'),
('primary_color', '#1d4ed8', 'general'),
('secondary_color', '#3b82f6', 'general'),
('site_logo', '', 'general'),
('site_favicon', '', 'general'),
('footer_text', 'School CMS Mix V1.2 Application', 'general'),
('school_address', '123 ถ.วิทยพัฒนา ต.ในเมือง อ.เมือง จ.ขอนแก่น 40000', 'general'),
('school_phone', '043-xxx-xxxx', 'general'),
('social_facebook', '', 'social'),
('social_line', '', 'social'),
('social_youtube', '', 'social'),
('social_tiktok', '', 'social'),
('social_twitter', '', 'social'),
('stat_student_count', '1200', 'stats'),
('stat_classroom_count', '40', 'stats'),
('home_hero_title', 'ปลูกฝังความรู้ สู่อนาคตที่ยั่งยืน', 'homepage'),
('home_hero_subtitle', 'ระบบบริหารจัดการเนื้อหาสำหรับโรงเรียนที่เน้นความทันสมัย ใช้งานง่าย และรองรับการแสดงผลทุกอุปกรณ์', 'homepage'),
('home_hero_button_text', 'ติดตามข่าวสารล่าสุด', 'homepage'),
('home_hero_button_url', '/news-all', 'homepage'),
('home_cover_image', '', 'homepage'),
('home_header_mode', 'single', 'homepage'),
('home_carousel_data', '[]', 'homepage'),
('home_about_title', 'มุ่งมั่นสร้างสรรค์ อนาคตที่ยั่งยืนให้เยาวชน', 'homepage'),
('home_about_content', 'โรงเรียนของเราเป็นสถาบันการศึกษาชั้นนำที่มุ่งเน้นการพัฒนาผู้เรียนให้มีความรู้คู่คุณธรรม พร้อมทักษะที่จำเป็นในโลกยุคดิจิทัล', 'homepage'),
('home_about_image', '', 'homepage'),
('home_about_features', '["เทคโนโลยีทันสมัย", "สภาพแวดล้อมปลอดภัย", "เน้นคุณธรรม จริยธรรม", "กิจกรรมเสริมทักษะ"]', 'homepage'),
('home_about_button_text', 'อ่านประวัติโรงเรียนเพิ่มเติม', 'homepage'),
('home_about_button_url', '/about-us', 'homepage'),
('home_custom_content', '[]', 'homepage'),
('footer_description', 'ยกระดับการศึกษาด้วยเทคโนโลยีที่สมัย ระบบบริหารจัดการเนื้อหาโรงเรียน (School CMS Mix V1.2) ที่ออกแบบมาเพื่อความง่ายและประสิทธิภาพสูงสุด', 'footer'),
('footer_copyright', '© 2024 School CMS Mix V1.2. All rights reserved.', 'footer');

-- Initial Data (Seeding)
INSERT INTO `departments` (`name`, `sort_order`) VALUES 
('กลุ่มสาระการเรียนรู้ภาษาไทย', 1),
('กลุ่มสาระการเรียนรู้คณิตศาสตร์', 2),
('กลุ่มสาระการเรียนรู้วิทยาศาสตร์และเทคโนโลยี', 3),
('กลุ่มสาระการเรียนรู้สังคมศึกษา ศาสนา และวัฒนธรรม', 4),
('กลุ่มสาระการเรียนรู้สุขศึกษาและพลศึกษา', 5),
('กลุ่มสาระการเรียนรู้ศิลปะ', 6),
('กลุ่มสาระการเรียนรู้การงานอาชีพ', 7),
('กลุ่มสาระการเรียนรู้ภาษาต่างประเทศ', 8),
('กิจกรรมพัฒนาผู้เรียน', 9),
('ฝ่ายบริหารงานวิชาการ', 10),
('ฝ่ายบริหารงบประมาณ', 11),
('ฝ่ายบริหารงานบุคคล', 12),
('ฝ่ายบริหารทั่วไป', 13);

INSERT INTO `news_categories` (`name`, `slug`) VALUES 
('ข่าวประชาสัมพันธ์', 'announcement'),
('ข่าวกิจกรรม', 'activities'),
('ข่าววิชาการ', 'academic'),
('ข่าวรับสมัครงาน/นักเรียน', 'recruitment');

INSERT INTO `pages` (`title`, `slug`, `content`, `status`, `type`) VALUES 
('ประวัติความเป็นมา', 'history', '<h1>ประวัติโรงเรียน</h1><p>โรงเรียนของเราก่อตั้งเมื่อปี...</p>', 'published', 'page'),
('วิสัยทัศน์และพันธกิจ', 'vision', '<h1>วิสัยทัศน์</h1><p>มุ่งมั่นสู่ความเป็นเลิศ...</p>', 'published', 'page'),
('เกี่ยวกับเรา', 'about-us', '<h1>เกี่ยวกับโรงเรียนของเรา</h1><p>ยินดีต้อนรับสู่หน้าข้อมูลโรงเรียน เรามีความมุ่งมั่นในการจัดการศึกษา...</p>', 'published', 'page'),
('ติดต่อสอบถาม', 'contact-us', '<h1>ติดต่อเรา</h1><p>ที่อยู่: 123 ถนนการเรียนรู้...</p>', 'published', 'page');

SET FOREIGN_KEY_CHECKS = 1;
