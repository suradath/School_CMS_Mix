<?php
declare(strict_types=1);

/**
 * School CMS Mix V1.2 Entry Point
 */

// 1. Error Reporting
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

// 2. Constants
define('ROOT_PATH', __DIR__);
define('CORE_PATH', ROOT_PATH . '/core');
define('MODULES_PATH', ROOT_PATH . '/modules');
define('THEMES_PATH', ROOT_PATH . '/themes');

// 3. Simple PSR-4 Autoloader
spl_autoload_register(function ($class) {
    // Convert namespace to file path
    $file = ROOT_PATH . '/' . str_replace('\\', '/', $class) . '.php';
    if (file_exists($file)) {
        require_once $file;
    }
});

// 4. Load Configuration (if exists)
if (file_exists(ROOT_PATH . '/config.php')) {
    $config = require_once ROOT_PATH . '/config.php';
    \Core\Database::setConfig($config['db']);
} else {
    // If no config, redirect to installer (unless we are already in installer)
    $uri = $_SERVER['REQUEST_URI'];
    if (strpos($uri, 'install.php') === false) {
        header("Location: /install.php");
        exit;
    }
}

// 5. Start Session & Set Security Headers
session_start();
\Core\Security::setSecurityHeaders();

// 6. Bootstrap Router
$router = new \Core\Router();

// 7. Log Visit
\Core\Visitor::logVisit();

// Define core public routes
$router->add('news-all', 'Modules\Home\Controllers\HomeController@newsView');
$router->add('news-detail', 'Modules\Home\Controllers\HomeController@newsDetail');
$router->add('personnel-view', 'Modules\Home\Controllers\HomeController@personnelView');
$router->add('gallery-view', 'Modules\Home\Controllers\HomeController@galleryView');
$router->add('gallery-detail', 'Modules\Home\Controllers\HomeController@galleryDetail');

// Admin Settings Routes
$router->add('settings/menu', 'Modules\Settings\Controllers\MenuController@index');
$router->add('settings/menu/create', 'Modules\Settings\Controllers\MenuController@create');
$router->add('settings/menu/store', 'Modules\Settings\Controllers\MenuController@store');
$router->add('settings/menu/edit', 'Modules\Settings\Controllers\MenuController@edit');
$router->add('settings/menu/update', 'Modules\Settings\Controllers\MenuController@update');
$router->add('settings/menu/delete', 'Modules\Settings\Controllers\MenuController@delete');
$router->add('settings/home-editor', 'Modules\Settings\Controllers\SettingsController@homeEditor');
$router->add('settings/update-home', 'Modules\Settings\Controllers\SettingsController@updateHome');
$router->add('settings/footer-editor', 'Modules\Settings\Controllers\SettingsController@footerEditor');
$router->add('settings/update-footer', 'Modules\Settings\Controllers\SettingsController@updateFooter');

// Entry Popup Routes
$router->add('settings/popups', 'Modules\Settings\Controllers\PopupController@index');
$router->add('settings/popups/create', 'Modules\Settings\Controllers\PopupController@create');
$router->add('settings/popups/store', 'Modules\Settings\Controllers\PopupController@store');
$router->add('settings/popups/edit', 'Modules\Settings\Controllers\PopupController@edit');
$router->add('settings/popups/update', 'Modules\Settings\Controllers\PopupController@update');
$router->add('settings/popups/delete', 'Modules\Settings\Controllers\PopupController@delete');

// Personnel & Department Routes
$router->add('personnel', 'Modules\Personnel\Controllers\PersonnelController@index');
$router->add('personnel/create', 'Modules\Personnel\Controllers\PersonnelController@create');
$router->add('personnel/store', 'Modules\Personnel\Controllers\PersonnelController@store');
$router->add('personnel/edit', 'Modules\Personnel\Controllers\PersonnelController@edit');
$router->add('personnel/update', 'Modules\Personnel\Controllers\PersonnelController@update');
$router->add('personnel/delete', 'Modules\Personnel\Controllers\PersonnelController@delete');

$router->add('personnel/departments', 'Modules\Personnel\Controllers\DepartmentController@index');
$router->add('personnel/departments/create', 'Modules\Personnel\Controllers\DepartmentController@create');
$router->add('personnel/departments/store', 'Modules\Personnel\Controllers\DepartmentController@store');
$router->add('personnel/departments/edit', 'Modules\Personnel\Controllers\DepartmentController@edit');
$router->add('personnel/departments/update', 'Modules\Personnel\Controllers\DepartmentController@update');
$router->add('personnel/departments/delete', 'Modules\Personnel\Controllers\DepartmentController@delete');

// Academic Calendar Routes
$router->add('calendar', 'Modules\Calendar\Controllers\CalendarController@index');
$router->add('calendar/create', 'Modules\Calendar\Controllers\CalendarController@create');
$router->add('calendar/store', 'Modules\Calendar\Controllers\CalendarController@store');
$router->add('calendar/edit', 'Modules\Calendar\Controllers\CalendarController@edit');
$router->add('calendar/update', 'Modules\Calendar\Controllers\CalendarController@update');
$router->add('calendar/delete', 'Modules\Calendar\Controllers\CalendarController@delete');
$router->add('api/calendar/events', 'Modules\Calendar\Controllers\CalendarController@getEvents');

$router->add('p', 'Modules\Home\Controllers\HomeController@viewPage');

// 7. Resolve Request
$router->resolve();
