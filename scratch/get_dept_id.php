<?php
define('ROOT_PATH', dirname(__DIR__, 1));
spl_autoload_register(function ($class) {
    $file = ROOT_PATH . '/' . str_replace('\\', '/', $class) . '.php';
    if (file_exists($file)) { require_once $file; }
});
$config = require_once ROOT_PATH . '/config.php';
\Core\Database::setConfig($config['db']);
$dept = \Core\Database::fetch("SELECT id FROM departments WHERE name = 'ฝ่ายบริหาร'");
echo $dept['id'] ?? 'Not found';
