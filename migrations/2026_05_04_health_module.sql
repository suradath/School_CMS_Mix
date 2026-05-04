-- 24. School Clinic System (Health Module)
INSERT IGNORE INTO `roles` (`id`, `name`, `slug`, `description`) VALUES
(11, 'เจ้าหน้าที่พยาบาล (Nurse)', 'nurse', 'ผู้ดูแลระบบงานพยาบาลและสต๊อกยา');

-- Update Students table for chronic diseases and allergies
ALTER TABLE `students` ADD COLUMN `chronic_disease` TEXT DEFAULT NULL AFTER `nationality`;
ALTER TABLE `students` ADD COLUMN `medication_allergy` TEXT DEFAULT NULL AFTER `chronic_disease`;

-- Medicine Inventory Table
CREATE TABLE IF NOT EXISTS `medicines` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `code` VARCHAR(50) UNIQUE COMMENT 'รหัสยา',
    `name` VARCHAR(255) NOT NULL COMMENT 'ชื่อยา',
    `properties` TEXT DEFAULT NULL COMMENT 'สรรพคุณ',
    `stock_quantity` INT NOT NULL DEFAULT 0 COMMENT 'จำนวนคงเหลือ',
    `min_stock_level` INT NOT NULL DEFAULT 10 COMMENT 'จุดสั่งซื้อ/ขั้นต่ำ',
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Health Records (Treatment Logs)
CREATE TABLE IF NOT EXISTS `health_records` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `student_id` INT NOT NULL,
    `symptoms` TEXT NOT NULL COMMENT 'อาการเบื้องต้น',
    `treatment` TEXT DEFAULT NULL COMMENT 'การประเมิน/การรักษา',
    `is_referral` TINYINT(1) DEFAULT 0 COMMENT 'ส่งต่อโรงพยาบาลหรือไม่',
    `referral_hospital` VARCHAR(255) DEFAULT NULL COMMENT 'ชื่อโรงพยาบาลที่ส่งต่อ',
    `referral_reason` TEXT DEFAULT NULL COMMENT 'สาเหตุการส่งต่อ',
    `created_by` INT NOT NULL COMMENT 'ผู้บันทึก (Nurse/Admin)',
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (`student_id`) REFERENCES `students`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`created_by`) REFERENCES `users`(`id`),
    INDEX (`student_id`),
    INDEX (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Health Prescriptions (Medicine Dispensing)
CREATE TABLE IF NOT EXISTS `health_prescriptions` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `record_id` INT NOT NULL,
    `medicine_id` INT NOT NULL,
    `quantity` INT NOT NULL DEFAULT 1 COMMENT 'จำนวนที่จ่าย',
    FOREIGN KEY (`record_id`) REFERENCES `health_records`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`medicine_id`) REFERENCES `medicines`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Sample Medicines
INSERT IGNORE INTO `medicines` (`code`, `name`, `properties`, `stock_quantity`, `min_stock_level`) VALUES
('MED001', 'Paracetamol 500mg', 'ลดไข้ แก้ปวด', 500, 50),
('MED002', 'Chlorpheniramine', 'แก้แพ้ ลดน้ำมูก', 200, 20),
('MED003', 'Antacid', 'ลดกรดในกระเพาะอาหาร', 100, 10),
('MED004', 'ORS', 'เกลือแร่แก้ท้องเสีย', 50, 5);
