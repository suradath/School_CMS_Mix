-- 1. ตารางข้อมูลหลักของนักเรียน
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

-- 2. ตารางข้อมูลที่อยู่
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

-- 3. ตารางข้อมูลผู้ปกครองและครอบครัว
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
