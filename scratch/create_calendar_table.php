<?php
// Manual setup for core to run the SQL
define('ROOT_PATH', dirname(__DIR__, 1));
define('CORE_PATH', ROOT_PATH . '/core');

spl_autoload_register(function ($class) {
    $file = ROOT_PATH . '/' . str_replace('\\', '/', $class) . '.php';
    if (file_exists($file)) {
        require_once $file;
    }
});

$config = require_once ROOT_PATH . '/config.php';
\Core\Database::setConfig($config['db']);

$sql = "
CREATE TABLE IF NOT EXISTS `academic_calendar` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `title` VARCHAR(255) NOT NULL,
    `description` TEXT DEFAULT NULL,
    `start_date` DATE NOT NULL,
    `end_date` DATE DEFAULT NULL,
    `start_time` TIME DEFAULT NULL,
    `end_time` TIME DEFAULT NULL,
    `responsible_person` VARCHAR(255) DEFAULT NULL,
    `color` VARCHAR(20) DEFAULT '#1d4ed8',
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX (`start_date`),
    INDEX (`end_date`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
";

try {
    \Core\Database::query($sql);
    echo "Table academic_calendar created successfully.\n";
} catch (Exception $e) {
    echo "Error creating table: " . $e->getMessage() . "\n";
}
