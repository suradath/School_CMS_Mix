-- Database Schema for School CMS Mix V2.8
-- Updated: 2026-05-01 (Added Student Discipline & PLC Systems)

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- 1. Roles & Permissions
CREATE TABLE IF NOT EXISTS `roles` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(50) NOT NULL UNIQUE,
    `slug` VARCHAR(50) NOT NULL UNIQUE,
    `description` TEXT DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 2. Users & Authentication
CREATE TABLE IF NOT EXISTS `users` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `username` VARCHAR(50) NOT NULL UNIQUE,
    `password` VARCHAR(255) NOT NULL,
    `email` VARCHAR(100) NOT NULL UNIQUE,
    `full_name` VARCHAR(100) NOT NULL,
    `role` ENUM('admin', 'editor', 'teacher', 'officer', 'hr', 'director', 'academic') DEFAULT 'teacher',
    `status` ENUM('active', 'inactive') DEFAULT 'active',
    `personnel_id` INT DEFAULT NULL,
    `last_login` DATETIME DEFAULT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX (`username`),
    INDEX (`role`),
    FOREIGN KEY (`personnel_id`) REFERENCES `personnel`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 3. Page & Content Management
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

INSERT INTO `pages` (`id`, `title`, `slug`, `content`, `author_id`, `status`, `type`, `meta_description`, `featured_image`, `created_at`, `updated_at`) VALUES
(1, 'ประวัติความเป็นมา', 'history', '<p>ประวัติโรงเรียนลำปลายมาศ</p>', NULL, 'published', 'page', '', NULL, '2026-04-22 03:23:01', '2026-04-22 13:56:44'),
(2, 'วิสัยทัศน์และพันธกิจ', 'vision', '<h1>วิสัยทัศน์</h1><p>มุ่งมั่นสู่ความเป็นเลิศ...</p>', NULL, 'published', 'page', NULL, NULL, '2026-04-22 03:23:01', '2026-04-22 03:23:01'),
(3, 'เกี่ยวกับเรา', 'about-us', '<h1>เกี่ยวกับโรงเรียนของเรา</h1><p>ยินดีต้อนรับสู่หน้าข้อมูลโรงเรียน เรามีความมุ่งมั่นในการจัดการศึกษา...</p>', NULL, 'published', 'page', NULL, NULL, '2026-04-22 03:23:01', '2026-04-22 03:23:01'),
(4, 'ติดต่อสอบถาม', 'contact-us', '<h1>ติดต่อเรา</h1><p>ที่อยู่: 123 ถนนการเรียนรู้...</p>', NULL, 'published', 'page', NULL, NULL, '2026-04-22 03:23:01', '2026-04-22 03:23:01');


-- 4. Departments
CREATE TABLE IF NOT EXISTS `departments` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(150) NOT NULL UNIQUE,
    `description` TEXT DEFAULT NULL,
    `sort_order` INT DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 5. Personnel Management
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

-- 6. News Categories
CREATE TABLE IF NOT EXISTS `news_categories` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(100) NOT NULL UNIQUE,
    `slug` VARCHAR(100) NOT NULL UNIQUE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `news_categories` (`id`, `name`, `slug`) VALUES
(1, 'ข่าวประชาสัมพันธ์', 'announcement'),
(2, 'ข่าวกิจกรรม', 'activities'),
(3, 'ข่าววิชาการ', 'academic'),
(4, 'ข่าวรับสมัครงาน/นักเรียน', 'recruitment');

-- 7. News & Events
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

-- 8. Media Gallery
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

-- 9. Academic Calendar
CREATE TABLE IF NOT EXISTS `academic_calendar` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `start_date` date NOT NULL,
  `end_date` date DEFAULT NULL,
  `start_time` time DEFAULT NULL,
  `end_time` time DEFAULT NULL,
  `responsible_person` varchar(255) DEFAULT NULL,
  `color` varchar(20) DEFAULT '#1d4ed8',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 10. Entry Popups
CREATE TABLE IF NOT EXISTS `entry_popups` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `title` VARCHAR(255) NOT NULL,
    `image_url` VARCHAR(255) DEFAULT NULL,
    `link_url` VARCHAR(255) DEFAULT NULL,
    `is_active` TINYINT(1) DEFAULT 1,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
	`updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 11. Leave Management
CREATE TABLE IF NOT EXISTS `leave_types` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(100) NOT NULL,
    `slug` VARCHAR(50) NOT NULL UNIQUE,
    `default_quota` INT DEFAULT 30,
    `color` VARCHAR(20) DEFAULT '#3b82f6',
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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

-- 12. E-Saraban (Electronic Document Management)
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
	`created_at` timestamp NOT NULL DEFAULT current_timestamp(),
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

-- 13. Document Submission System
CREATE TABLE IF NOT EXISTS `submission_topics` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `title` VARCHAR(255) NOT NULL,
    `description` TEXT DEFAULT NULL,
    `semester` ENUM('1', '2') DEFAULT '1',
    `academic_year` INT NOT NULL,
    `max_file_size` INT DEFAULT 20 COMMENT 'Max size in MB',
    `status` ENUM('active', 'inactive') DEFAULT 'active',
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `topic_allowed_files` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `topic_id` INT NOT NULL,
    `extension` VARCHAR(10) NOT NULL,
    FOREIGN KEY (`topic_id`) REFERENCES `submission_topics`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `document_submissions` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `topic_id` INT NOT NULL,
    `user_id` INT NOT NULL,
    `file_path` VARCHAR(255) NOT NULL,
    `original_filename` VARCHAR(255) NOT NULL,
    `mime_type` VARCHAR(100) DEFAULT NULL,
    `status` ENUM('pending', 'approved', 'revision') DEFAULT 'pending',
    `feedback` TEXT DEFAULT NULL,
    `submitted_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (`topic_id`) REFERENCES `submission_topics`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE,
    INDEX (`status`),
    INDEX (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 14. Menus & Settings
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
INSERT INTO `roles` (`id`, `name`, `slug`, `description`) VALUES
(1, 'ผู้ดูแลระบบ (Admin)', 'admin', 'ผู้ใช้ที่สามารถจัดการระบบได้ทั้งหมด'),
(2, 'เจ้าหน้าที่ระบบ (Editor)', 'editor', 'ผู้ดูแลข่าวสาร,แกลอรี่และบุคลากรในหน่วยงานของตน'),
(3, 'ครู/บุคลากร (Teacher)', 'teacher', 'จัดการโปรไฟล์ของตนเองและผลงาน'),
(5, 'หัวหน้ากลุ่มสาระฯ (Dept Head)', 'dept_head', 'ผู้บริหารกลุ่มสาระการเรียนรู้'),
(6, 'ฝ่ายบริหารงานทั่วไป (Staff)', 'staff', 'ผู้ดูแลระบบสารบรรณและอื่นๆ'),
(7, 'เจ้าหน้าที่งานบุคคล (HR)', 'hr', 'เจ้าหน้าที่งานบุคคล'),
(8, 'ผู้อำนวยการ (Director)', 'director', 'ผู้อำนวยการสถานศึกษา'),
(9, 'เจ้าหน้าที่ธุรการ', 'officer', 'จัดการระบบสารบรรณโดยเฉพาะ'),
(10, 'ฝ่ายบริหารวิชาการ (Academic)', 'academic', 'ฝ่ายบริหารวิชาการ');


INSERT INTO `leave_types` (`name`, `slug`, `default_quota`, `color`) VALUES 
('ลาป่วย', 'sick', 30, '#ef4444'),
('ลากิจส่วนตัว', 'personal', 15, '#f59e0b'),
('ลาพักผ่อน', 'vacation', 10, '#10b981'),
('ลาคลอดบุตร', 'maternity', 90, '#ec4899'),
('ลาอุปสมบท', 'ordination', 120, '#f97316');

INSERT INTO `saraban_types` (`name`, `slug`, `prefix`) VALUES 
('ทะเบียนหนังสือรับ', 'inbound', ''),
('ทะเบียนหนังสือส่ง', 'outbound', 'ที่ ศธ 05.../'),
('ทะเบียนคำสั่ง/ประกาศ', 'orders', 'คำสั่งโรงเรียนที่');

INSERT INTO `menus` (`title`, `url`, `icon`, `sort_order`) VALUES 
('หน้าแรก', '/', 'fa-home', 1),
('ข่าวสาร', '/news-all', 'fa-newspaper-o', 2),
('บุคลากร', '/personnel-view', 'fa-users', 3),
('ภาพกิจกรรม', '/gallery-view', 'fa-picture-o', 4);
('รับเรื่องร้องเรียน', '/complaint', 'fa-commenting-o', 5);

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


-- 15. Students Information
CREATE TABLE IF NOT EXISTS `students` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `school_id` VARCHAR(20) COMMENT 'รหัสโรงเรียน',
    `citizen_id` VARCHAR(13) UNIQUE COMMENT 'เลขประจำตัวประชาชน',
    `student_code` VARCHAR(20) UNIQUE COMMENT 'เลขประจำตัวนักเรียน',
    `title` VARCHAR(20) COMMENT 'คำนำหน้าชื่อ',
    `first_name` VARCHAR(100) COMMENT 'ชื่อ',
    `last_name` VARCHAR(100) COMMENT 'นามสกุล',
    `gender` ENUM('ช', 'ญ') COMMENT 'เพศ',
    `class_level` VARCHAR(20) COMMENT 'ชั้นเรียน เช่น ม.1',
    `room_number` INT COMMENT 'ห้องเรียน',
    `birth_date` DATE COMMENT 'วันเกิด',
    `blood_type` VARCHAR(5) COMMENT 'กลุ่มเลือด',
    `religion` VARCHAR(50) COMMENT 'ศาสนา',
    `ethnicity` VARCHAR(50) COMMENT 'เชื้อชาติ',
    `nationality` VARCHAR(50) COMMENT 'สัญชาติ',
    `weight` DECIMAL(5,2) COMMENT 'น้ำหนัก',
    `height` DECIMAL(5,2) COMMENT 'ส่วนสูง',
    `disadvantage_status` VARCHAR(255) COMMENT 'ความด้อยโอกาส',
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX `idx_class_room` (`class_level`, `room_number`),
    INDEX `idx_name` (`first_name`, `last_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `student_address` (
    `student_id` INT PRIMARY KEY,
    `address_no` VARCHAR(50) COMMENT 'บ้านเลขที่',
    `moo` VARCHAR(20) COMMENT 'หมู่',
    `soi_road` VARCHAR(100) COMMENT 'ถนน/ซอย',
    `sub_district` VARCHAR(100) COMMENT 'ตำบล',
    `district` VARCHAR(100) COMMENT 'อำเภอ',
    `province` VARCHAR(100) COMMENT 'จังหวัด',
    FOREIGN KEY (`student_id`) REFERENCES `students`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `student_parents` (
    `student_id` INT PRIMARY KEY,
    `father_name` VARCHAR(200) COMMENT 'ชื่อ-นามสกุลบิดา',
    `father_occupation` VARCHAR(100) COMMENT 'อาชีพของบิดา',
    `mother_name` VARCHAR(200) COMMENT 'ชื่อ-นามสกุลมารดา',
    `mother_occupation` VARCHAR(100) COMMENT 'อาชีพของมารดา',
    `guardian_name` VARCHAR(200) COMMENT 'ชื่อ-นามสกุลผู้ปกครอง',
    `guardian_occupation` VARCHAR(100) COMMENT 'อาชีพของผู้ปกครอง',
    `guardian_relation` VARCHAR(100) COMMENT 'ความเกี่ยวข้องกับนักเรียน',
    FOREIGN KEY (`student_id`) REFERENCES `students`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- 16. Attendance System
CREATE TABLE IF NOT EXISTS `attendance_courses` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `teacher_id` INT NOT NULL COMMENT 'ID ของครูเจ้าของวิชา',
    `course_code` VARCHAR(50) NOT NULL COMMENT 'รหัสวิชา',
    `course_name` VARCHAR(255) NOT NULL COMMENT 'ชื่อวิชา',
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY `unique_teacher_course` (`teacher_id`, `course_code`),
    INDEX `idx_teacher` (`teacher_id`),
    INDEX `idx_course_code` (`course_code`),
    FOREIGN KEY (`teacher_id`) REFERENCES `users`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `attendance_course_classes` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `course_id` INT NOT NULL,
    `class_level` VARCHAR(20) NOT NULL COMMENT 'ชั้นเรียน',
    `room_number` INT NOT NULL COMMENT 'ห้องเรียน',
    FOREIGN KEY (`course_id`) REFERENCES `attendance_courses`(`id`) ON DELETE CASCADE,
    INDEX `idx_link_course` (`course_id`),
    INDEX `idx_link_class` (`class_level`, `room_number`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `attendance_records` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `check_date` DATE NOT NULL,
    `course_id` INT NOT NULL,
    `class_level` VARCHAR(20) NOT NULL,
    `room_number` INT NOT NULL,
    `student_id` INT NOT NULL,
    `status` ENUM('present', 'late', 'truant', 'absent', 'personal_leave', 'sick_leave') NOT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    UNIQUE KEY `unique_attendance` (`check_date`, `course_id`, `student_id`),
    FOREIGN KEY (`course_id`) REFERENCES `attendance_courses`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`student_id`) REFERENCES `students`(`id`) ON DELETE CASCADE,
    INDEX `idx_record_date` (`check_date`),
    INDEX `idx_record_course` (`course_id`),
    INDEX `idx_record_student` (`student_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 17. Visitor Counter
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

-- 18. Journals
CREATE TABLE IF NOT EXISTS `journals` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `title` VARCHAR(255) NOT NULL,
    `image_url` VARCHAR(255) DEFAULT NULL,
    `sort_order` INT DEFAULT 0,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 19. Complaint System
CREATE TABLE IF NOT EXISTS `complaints` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `topic` VARCHAR(255) NOT NULL,
    `details` TEXT NOT NULL,
    `attachment` VARCHAR(255) DEFAULT NULL,
    `contact_name` VARCHAR(255) DEFAULT NULL,
    `contact_info` VARCHAR(255) DEFAULT NULL,
    `status` ENUM('unread', 'read', 'in_progress', 'resolved') DEFAULT 'unread',
    `read_by` INT DEFAULT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (`read_by`) REFERENCES `users`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 20. Booking System (Rooms & Vehicles)
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

INSERT IGNORE INTO `booking_resources` (`type`, `name`, `description`, `capacity`, `status`) VALUES
('room', 'ห้องประชุมอาคาร 1', 'ห้องประชุมขนาดใหญ่ พร้อมโปรเจคเตอร์', 50, 'available'),
('room', 'ห้องสมุด', 'โซนเงียบสงบ', 30, 'available');

INSERT IGNORE INTO `booking_resources` (`type`, `name`, `description`, `capacity`, `license_plate`, `status`) VALUES
('vehicle', 'รถตู้โรงเรียน (1)', 'รถตู้ Toyota Commuter', 12, 'กข-1234', 'available'),
('vehicle', 'รถบัส (1)', 'รถบัสรับส่งนักเรียน', 40, 'มฐ-5678', 'available');

-- 21. Student Discipline System
INSERT IGNORE INTO `roles` (`name`, `slug`, `description`) 
VALUES ('เจ้าหน้าที่ฝ่ายปกครอง', 'discipline_staff', 'ผู้ดูแลระบบงานปกครองและพฤติกรรมนักเรียน');

CREATE TABLE IF NOT EXISTS `discipline_categories` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(255) NOT NULL,
    `points` INT NOT NULL,
    `type` ENUM('good', 'bad') NOT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `student_discipline_logs` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `student_id` INT NOT NULL,
    `category_id` INT DEFAULT NULL,
    `points_affected` INT NOT NULL,
    `remarks` TEXT DEFAULT NULL,
    `is_auto` TINYINT(1) DEFAULT 0,
    `created_by` INT DEFAULT NULL,
    `deleted_at` TIMESTAMP NULL DEFAULT NULL,
    `deleted_by` INT DEFAULT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (`student_id`) REFERENCES `students`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`category_id`) REFERENCES `discipline_categories`(`id`) ON DELETE SET NULL,
    FOREIGN KEY (`created_by`) REFERENCES `users`(`id`) ON DELETE SET NULL,
    FOREIGN KEY (`deleted_by`) REFERENCES `users`(`id`) ON DELETE SET NULL,
    INDEX (`student_id`),
    INDEX (`category_id`),
    INDEX (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT IGNORE INTO `discipline_categories` (`id`, `name`, `points`, `type`) VALUES 
(1, 'มาสาย (อัตโนมัติ)', -5, 'bad'),
(2, 'ขาดเรียน (อัตโนมัติ)', -10, 'bad'),
(3, 'จิตอาสาทำความสะอาด', 5, 'good'),
(4, 'ช่วยงานห้องสมุด', 10, 'good'),
(5, 'ทะเลาะวิวาท', -20, 'bad');

-- 22. PLC (Professional Learning Community) System
CREATE TABLE IF NOT EXISTS `plc_groups` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(255) NOT NULL,
    `description` TEXT,
    `target_goal` INT DEFAULT 50,
    `academic_year` VARCHAR(10) NOT NULL,
    `created_by` INT NOT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (`created_by`) REFERENCES `users`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `plc_group_members` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `group_id` INT NOT NULL,
    `user_id` INT NOT NULL,
    `role` ENUM('head', 'member') DEFAULT 'member',
    `status` ENUM('pending', 'approved') DEFAULT 'approved',
    `joined_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (`group_id`) REFERENCES `plc_groups`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE,
    UNIQUE KEY `unique_membership` (`group_id`, `user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `plc_meetings` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `group_id` INT NOT NULL,
    `topic` VARCHAR(255) NOT NULL,
    `problem_topic` TEXT,
    `solution` TEXT,
    `result` TEXT,
    `hours` DECIMAL(5,2) NOT NULL,
    `date` DATE NOT NULL,
    `status` ENUM('pending', 'approved', 'rejected') DEFAULT 'pending',
    `approved_by` INT DEFAULT NULL,
    `approved_at` DATETIME DEFAULT NULL,
    `created_by` INT NOT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (`group_id`) REFERENCES `plc_groups`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`approved_by`) REFERENCES `users`(`id`) ON DELETE SET NULL,
    FOREIGN KEY (`created_by`) REFERENCES `users`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `plc_meeting_materials` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `meeting_id` INT NOT NULL,
    `file_name` VARCHAR(255) NOT NULL,
    `file_path` VARCHAR(255) NOT NULL,
    `file_type` VARCHAR(50),
    `uploaded_by` INT NOT NULL,
    `uploaded_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (`meeting_id`) REFERENCES `plc_meetings`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`uploaded_by`) REFERENCES `users`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

SET FOREIGN_KEY_CHECKS = 1;



-- Helpdesk / Maintenance Tables
CREATE TABLE IF NOT EXISTS `repair_categories` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(100) NOT NULL,
    `slug` VARCHAR(100) NOT NULL UNIQUE,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `repair_requests` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `reporter_id` INT NOT NULL,
    `category_id` INT NOT NULL,
    `location` VARCHAR(255) NOT NULL,
    `description` TEXT NOT NULL,
    `photos` JSON DEFAULT NULL,
    `status` ENUM('pending', 'in_progress', 'fixed', 'cancelled') DEFAULT 'pending',
    `remarks` TEXT DEFAULT NULL,
    `resolved_at` DATETIME DEFAULT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (`reporter_id`) REFERENCES `users`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`category_id`) REFERENCES `repair_categories`(`id`) ON DELETE CASCADE,
    INDEX (`status`),
    INDEX (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT IGNORE INTO `repair_categories` (`name`, `slug`) VALUES
('ระบบไฟฟ้า', 'electrical'),
('คอมพิวเตอร์/ไอที', 'it'),
('ระบบเครือข่าย/เน็ตเวิร์ก', 'network'),
('อุปกรณ์สำนักงาน/ครุภัณฑ์', 'office-supplies'),
('อาคารสถานที่', 'building'),
('ระบบประปา', 'plumbing');


CREATE TABLE `user_roles` (
  `user_id` int(11) NOT NULL,
  `role_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `user_roles`
--

INSERT INTO `user_roles` (`user_id`, `role_id`) VALUES
(1, 1),
(2, 2),
(2, 3);