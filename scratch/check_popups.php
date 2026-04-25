<?php
define('ROOT_PATH', __DIR__ . '/..');
spl_autoload_register(function ($class) {
    $file = ROOT_PATH . '/' . str_replace('\\', '/', $class) . '.php';
    if (file_exists($file)) {
        require_once $file;
    }
});

if (file_exists(ROOT_PATH . '/config.php')) {
    $config = require_once ROOT_PATH . '/config.php';
    \Core\Database::setConfig($config['db']);
}

try {
    $active = \Modules\Settings\Models\EntryPopup::getActive();
    echo "Active Popup: " . ($active ? json_encode($active) : "NONE") . "\n";
    
    $all = \Modules\Settings\Models\EntryPopup::getAll();
    echo "Total Popups: " . count($all) . "\n";
    foreach ($all as $p) {
        echo "- ID: {$p['id']}, Active: {$p['is_active']}, Title: {$p['title']}\n";
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
