-- 1. System Settings for Club (Global toggle)
INSERT IGNORE INTO `settings` (`setting_key`, `setting_value`, `category`) VALUES 
('club_registration_enabled', '0', 'club');

-- 2. Clubs Table
CREATE TABLE IF NOT EXISTS `clubs` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(255) NOT NULL,
    `advisor_id` INT NOT NULL COMMENT 'Personnel ID of the advisor',
    `location` VARCHAR(255) DEFAULT NULL,
    `capacity` INT NOT NULL DEFAULT 0,
    `current_count` INT NOT NULL DEFAULT 0,
    `target_grades` JSON NOT NULL COMMENT 'Array of class levels e.g. ["ม.1", "ม.2"]',
    `status` ENUM('open', 'closed', 'full') DEFAULT 'open',
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (`advisor_id`) REFERENCES `personnel`(`id`) ON DELETE CASCADE,
    UNIQUE KEY `unique_advisor` (`advisor_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 3. Club Members (Registrations)
CREATE TABLE IF NOT EXISTS `club_members` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `club_id` INT NOT NULL,
    `student_id` INT NOT NULL,
    `registration_date` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `status` ENUM('active', 'withdrawn') DEFAULT 'active',
    FOREIGN KEY (`club_id`) REFERENCES `clubs`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`student_id`) REFERENCES `students`(`id`) ON DELETE CASCADE,
    UNIQUE KEY `unique_student_registration` (`student_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 4. Club Attendance
CREATE TABLE IF NOT EXISTS `club_attendance` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `club_id` INT NOT NULL,
    `student_id` INT NOT NULL,
    `check_date` DATE NOT NULL,
    `status` ENUM('present', 'absent', 'leave') NOT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (`club_id`) REFERENCES `clubs`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`student_id`) REFERENCES `students`(`id`) ON DELETE CASCADE,
    UNIQUE KEY `unique_attendance` (`club_id`, `student_id`, `check_date`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 5. Club Evaluations
CREATE TABLE IF NOT EXISTS `club_evaluations` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `club_id` INT NOT NULL,
    `student_id` INT NOT NULL,
    `semester` ENUM('1', '2') NOT NULL,
    `academic_year` INT NOT NULL,
    `result` ENUM('P', 'F') COMMENT 'P = Pass (ผ), F = Fail (มผ)',
    `remarks` TEXT,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (`club_id`) REFERENCES `clubs`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`student_id`) REFERENCES `students`(`id`) ON DELETE CASCADE,
    UNIQUE KEY `unique_evaluation` (`club_id`, `student_id`, `semester`, `academic_year`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
