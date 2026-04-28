-- 1. ตารางรายวิชา
CREATE TABLE IF NOT EXISTS `attendance_courses` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `course_code` VARCHAR(50) NOT NULL COMMENT 'รหัสวิชา',
    `course_name` VARCHAR(255) NOT NULL COMMENT 'ชื่อวิชา',
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 2. ตารางผูกวิชากับห้องเรียน
CREATE TABLE IF NOT EXISTS `attendance_course_classes` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `course_id` INT NOT NULL,
    `class_level` VARCHAR(20) NOT NULL COMMENT 'ชั้นเรียน',
    `room_number` INT NOT NULL COMMENT 'ห้องเรียน',
    FOREIGN KEY (`course_id`) REFERENCES `attendance_courses`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 3. ตารางบันทึกการเช็คชื่อ
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
    FOREIGN KEY (`student_id`) REFERENCES `students`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
