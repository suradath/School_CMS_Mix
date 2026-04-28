<?php
declare(strict_types=1);

define('ROOT_PATH', dirname(__DIR__));

spl_autoload_register(function ($class) {
    $file = ROOT_PATH . '/' . str_replace('\\', '/', $class) . '.php';
    if (file_exists($file)) {
        require_once $file;
    }
});

if (file_exists(ROOT_PATH . '/config.php')) {
    $config = require_once ROOT_PATH . '/config.php';
    \Core\Database::setConfig($config['db']);
} else {
    die("Config file not found.\n");
}

try {
    $sql = file_get_contents(ROOT_PATH . '/migrations/2024_04_28_add_students_tables.sql');
    
    // We can execute multiple statements by running them raw
    $pdo = \Core\Database::getInstance();
    $pdo->exec($sql);
    
    echo "Migration executed successfully.\n";
} catch (\Exception $e) {
    echo "Error executing migration: " . $e->getMessage() . "\n";
}
