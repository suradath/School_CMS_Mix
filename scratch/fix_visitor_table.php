<?php
declare(strict_types=1);

define('ROOT_PATH', dirname(__DIR__));

// Simple PSR-4 Autoloader
spl_autoload_register(function ($class) {
    $file = ROOT_PATH . '/' . str_replace('\\', '/', $class) . '.php';
    if (file_exists($file)) {
        require_once $file;
    }
});

// Load Configuration
if (file_exists(ROOT_PATH . '/config.php')) {
    $config = require_once ROOT_PATH . '/config.php';
    \Core\Database::setConfig($config['db']);
} else {
    die("Config file not found. Please install the system first.\n");
}

try {
    echo "Updating database schema...\n";
    
    // Roles Table
    echo "Creating roles table...\n";
    \Core\Database::query("
        CREATE TABLE IF NOT EXISTS `roles` (
            `id` INT AUTO_INCREMENT PRIMARY KEY,
            `name` VARCHAR(50) NOT NULL UNIQUE,
            `slug` VARCHAR(50) NOT NULL UNIQUE,
            `description` TEXT DEFAULT NULL
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
    ");

    \Core\Database::query("
        INSERT IGNORE INTO `roles` (`name`, `slug`, `description`) VALUES 
        ('Administrator', 'admin', 'Full access to the system'),
        ('Editor/Staff', 'editor', 'Manage content'),
        ('Teacher/User', 'teacher', 'Regular user');
    ");

    // Journals Table
    echo "Creating journals table...\n";
    \Core\Database::query("
        CREATE TABLE IF NOT EXISTS `journals` (
            `id` INT AUTO_INCREMENT PRIMARY KEY,
            `title` VARCHAR(255) NOT NULL,
            `image_url` VARCHAR(255) DEFAULT NULL,
            `sort_order` INT DEFAULT 0,
            `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
    ");

    // Personnel Leave Quotas
    echo "Creating personnel_leave_quotas table...\n";
    \Core\Database::query("
        CREATE TABLE IF NOT EXISTS `personnel_leave_quotas` (
            `id` INT AUTO_INCREMENT PRIMARY KEY,
            `personnel_id` INT NOT NULL,
            `leave_type_id` INT NOT NULL,
            `quota` INT NOT NULL,
            `year` INT NOT NULL,
            UNIQUE KEY `unique_quota` (`personnel_id`, `leave_type_id`, `year`),
            FOREIGN KEY (`personnel_id`) REFERENCES `personnel`(`id`) ON DELETE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
    ");

    // Update leave_types if columns missing
    echo "Checking leave_types table...\n";
    try {
        \Core\Database::query("ALTER TABLE `leave_types` ADD COLUMN `default_quota` INT DEFAULT 30 AFTER `slug` ");
        \Core\Database::query("ALTER TABLE `leave_types` ADD COLUMN `color` VARCHAR(20) DEFAULT '#3b82f6' AFTER `default_quota` ");
        \Core\Database::query("ALTER TABLE `leave_types` ADD COLUMN `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP AFTER `color` ");
    } catch (Exception $e) {}

    // Update leave_requests if columns missing
    echo "Checking leave_requests table...\n";
    try {
        \Core\Database::query("ALTER TABLE `leave_requests` MODIFY COLUMN `status` ENUM('pending', 'approved', 'rejected', 'cancelled') DEFAULT 'pending' ");
        \Core\Database::query("ALTER TABLE `leave_requests` ADD COLUMN `dept_head_comment` TEXT DEFAULT NULL AFTER `status` ");
        \Core\Database::query("ALTER TABLE `leave_requests` ADD COLUMN `admin_comment` TEXT DEFAULT NULL AFTER `dept_head_comment` ");
        \Core\Database::query("ALTER TABLE `leave_requests` ADD COLUMN `approved_by` INT DEFAULT NULL AFTER `admin_comment` ");
        \Core\Database::query("ALTER TABLE `leave_requests` ADD COLUMN `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP AFTER `created_at` ");
    } catch (Exception $e) {}

    echo "Success! Database has been updated.\n";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
