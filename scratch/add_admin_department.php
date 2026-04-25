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

$sql = "INSERT INTO `departments` (`name`, `sort_order`) VALUES ('ฝ่ายบริหาร', 0) ON DUPLICATE KEY UPDATE name=name;";

try {
    \Core\Database::query($sql);
    echo "Department 'ฝ่ายบริหาร' added successfully.\n";
} catch (Exception $e) {
    echo "Error adding department: " . $e->getMessage() . "\n";
}
