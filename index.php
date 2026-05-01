<?php
declare(strict_types=1);

/**
 * School CMS Mix V2.7 Entry Point
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

// 3.0 Define Base Path and URL Helper
$scriptName = $_SERVER['SCRIPT_NAME'] ?? '';
$basePath = str_replace('\\', '/', dirname($scriptName));
if ($basePath === '/') $basePath = '';
define('BASE_PATH', $basePath);

function url(string $path = ''): string
{
    $path = ltrim($path, '/');
    return BASE_PATH . '/' . $path;
}

/**
 * Check if the current user has specific role(s)
 * @param string|array $roles
 * @return bool
 */
function hasRole(string|array $roles): bool
{
    return \Core\Security::hasRole($roles);
}

// 3.1 Load Composer Autoloader (if exists)
if (file_exists(ROOT_PATH . '/vendor/autoload.php')) {
    require_once ROOT_PATH . '/vendor/autoload.php';
}

// 4. Load Configuration (if exists)
if (file_exists(ROOT_PATH . '/config.php')) {
    $config = require_once ROOT_PATH . '/config.php';
    \Core\Database::setConfig($config['db']);
} else {
    // If no config, redirect to installer (unless we are already in installer)
    $uri = $_SERVER['REQUEST_URI'];
    if (strpos($uri, 'install.php') === false) {
        $scriptName = $_SERVER['SCRIPT_NAME'];
        $basePath = str_replace('\\', '/', dirname($scriptName));
        if ($basePath === '/') $basePath = '';
        header("Location: " . $basePath . "/install.php");
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

// User Management Routes
$router->add('admin/users', 'Modules\Auth\Controllers\UserManagementController@index');
$router->add('admin/users/create', 'Modules\Auth\Controllers\UserManagementController@create');
$router->add('admin/users/store', 'Modules\Auth\Controllers\UserManagementController@store');
$router->add('admin/users/edit', 'Modules\Auth\Controllers\UserManagementController@edit');
$router->add('admin/users/update', 'Modules\Auth\Controllers\UserManagementController@update');
$router->add('admin/users/delete', 'Modules\Auth\Controllers\UserManagementController@delete');

$router->add('p', 'Modules\Home\Controllers\HomeController@viewPage');

// Journal Routes
$router->add('journal', 'Modules\Journal\Controllers\JournalController@index');
$router->add('journal/store', 'Modules\Journal\Controllers\JournalController@store');
$router->add('journal/delete', 'Modules\Journal\Controllers\JournalController@delete');

// E-Saraban Routes
$router->add('saraban', 'Modules\Saraban\Controllers\SarabanController@index');
$router->add('saraban/inbound', 'Modules\Saraban\Controllers\SarabanController@inbound');
$router->add('saraban/outbound', 'Modules\Saraban\Controllers\SarabanController@outbound');
$router->add('saraban/orders', 'Modules\Saraban\Controllers\SarabanController@orders');
$router->add('saraban/announcements', 'Modules\Saraban\Controllers\SarabanController@announcements');
$router->add('saraban/create', 'Modules\Saraban\Controllers\DocumentController@create');
$router->add('saraban/store', 'Modules\Saraban\Controllers\DocumentController@store');
$router->add('saraban/view', 'Modules\Saraban\Controllers\DocumentController@view');
$router->add('saraban/batch-endorse', 'Modules\Saraban\Controllers\SarabanController@batchEndorse');
$router->add('saraban/minute/add', 'Modules\Saraban\Controllers\DocumentController@addMinute');
$router->add('saraban/minute/print', 'Modules\Saraban\Controllers\DocumentController@printMinute');
$router->add('saraban/acknowledge', 'Modules\Saraban\Controllers\DocumentController@acknowledge');
$router->add('saraban/delete', 'Modules\Saraban\Controllers\DocumentController@delete');
$router->add('saraban/minute/delete', 'Modules\Saraban\Controllers\DocumentController@deleteMinute');
$router->add('saraban/file', 'Modules\Saraban\Controllers\FileController@serve');

// Leave Management Routes
$router->add('leave', 'Modules\Leave\Controllers\LeaveController@index');
$router->add('leave/create', 'Modules\Leave\Controllers\LeaveController@create');
$router->add('leave/store', 'Modules\Leave\Controllers\LeaveController@store');
$router->add('leave/review', 'Modules\Leave\Controllers\LeaveController@review');
$router->add('leave/updateStatus', 'Modules\Leave\Controllers\LeaveController@updateStatus');
$router->add('leave/reports', 'Modules\Leave\Controllers\LeaveController@reports');
// Student Information System Routes
$router->add('students', 'Modules\Students\Controllers\StudentController@index');
$router->add('students/classroom', 'Modules\Students\Controllers\StudentController@classroom');
$router->add('students/profile', 'Modules\Students\Controllers\StudentController@profile');
$router->add('students/import', 'Modules\Students\Controllers\ImportController@index');
$router->add('students/import-process', 'Modules\Students\Controllers\ImportController@process');
$router->add('students/clear', 'Modules\Students\Controllers\StudentController@clear');

// Health & Nutrition Routes
$router->add('health', 'Modules\Health\Controllers\HealthController@index');
$router->add('health/data', 'Modules\Health\Controllers\HealthController@data');

// Attendance System Routes
$router->add('attendance', 'Modules\Attendance\Controllers\AttendanceController@index');
$router->add('attendance/setup', 'Modules\Attendance\Controllers\AttendanceController@setup');
$router->add('attendance/setup/store', 'Modules\Attendance\Controllers\AttendanceController@storeCourse');
$router->add('attendance/setup/link', 'Modules\Attendance\Controllers\AttendanceController@linkClassroom');
$router->add('attendance/setup/unlink', 'Modules\Attendance\Controllers\AttendanceController@unlinkClassroom');
$router->add('attendance/setup/delete', 'Modules\Attendance\Controllers\AttendanceController@deleteCourse');
$router->add('attendance/get-students', 'Modules\Attendance\Controllers\AttendanceController@ajaxGetStudents');
$router->add('attendance/get-report', 'Modules\Attendance\Controllers\AttendanceController@ajaxGetReport');
$router->add('attendance/get-student-calendar', 'Modules\Attendance\Controllers\AttendanceController@ajaxStudentCalendar');
$router->add('attendance/report', 'Modules\Attendance\Controllers\AttendanceController@report');
$router->add('attendance/save', 'Modules\Attendance\Controllers\AttendanceController@store');
$router->add('attendance/export', 'Modules\Attendance\Controllers\AttendanceController@export');

// Document Submission System Routes
$router->add('submissions', 'Modules\Submissions\Controllers\SubmissionsController@index');
$router->add('submissions/submit', 'Modules\Submissions\Controllers\SubmissionsController@submit');
$router->add('submissions/topics', 'Modules\Submissions\Controllers\SubmissionsController@topics');
$router->add('submissions/topics/store', 'Modules\Submissions\Controllers\SubmissionsController@storeTopic');
$router->add('submissions/topics/delete', 'Modules\Submissions\Controllers\SubmissionsController@deleteTopic');
$router->add('submissions/monitor', 'Modules\Submissions\Controllers\SubmissionsController@monitor');
$router->add('submissions/update-status', 'Modules\Submissions\Controllers\SubmissionsController@updateStatus');
$router->add('submissions/export', 'Modules\Submissions\Controllers\SubmissionsController@export');

// Complaint System Routes
$router->add('complaint', 'Modules\Complaint\Controllers\ComplaintController@index');
$router->add('complaint/store', 'Modules\Complaint\Controllers\ComplaintController@store');
$router->add('admin/complaints', 'Modules\Complaint\Controllers\ComplaintController@adminIndex');
$router->add('admin/complaints/view', 'Modules\Complaint\Controllers\ComplaintController@adminView');
$router->add('admin/complaints/update-status', 'Modules\Complaint\Controllers\ComplaintController@adminUpdateStatus');
$router->add('admin/complaints/delete', 'Modules\Complaint\Controllers\ComplaintController@adminDelete');

// Resource Booking System Routes
$router->add('booking', 'Modules\Booking\Controllers\BookingController@index');
$router->add('booking/events', 'Modules\Booking\Controllers\BookingController@events');
$router->add('booking/resourcesByType', 'Modules\Booking\Controllers\BookingController@resourcesByType');
$router->add('booking/store', 'Modules\Booking\Controllers\BookingController@store');
$router->add('booking/myBookings', 'Modules\Booking\Controllers\BookingController@myBookings');
$router->add('booking/cancel', 'Modules\Booking\Controllers\BookingController@cancel');

// Admin Resource Booking Routes
$router->add('adminBooking/approvals', 'Modules\Booking\Controllers\AdminBookingController@approvals');
$router->add('adminBooking/updateStatus', 'Modules\Booking\Controllers\AdminBookingController@updateStatus');
$router->add('adminBooking/resources', 'Modules\Booking\Controllers\AdminBookingController@resources');
$router->add('adminBooking/storeResource', 'Modules\Booking\Controllers\AdminBookingController@storeResource');
$router->add('adminBooking/updateResource', 'Modules\Booking\Controllers\AdminBookingController@updateResource');
$router->add('adminBooking/deleteResource', 'Modules\Booking\Controllers\AdminBookingController@deleteResource');

// 7. Resolve Request
// Helpdesk / Maintenance Routes
$router->add('helpdesk', 'Modules\Helpdesk\Controllers\HelpdeskController@index');
$router->add('helpdesk/my-repairs', 'Modules\Helpdesk\Controllers\HelpdeskController@myRepairs');
$router->add('helpdesk/store', 'Modules\Helpdesk\Controllers\HelpdeskController@store');
$router->add('admin/helpdesk', 'Modules\Helpdesk\Controllers\AdminHelpdeskController@dashboard');
$router->add('admin/helpdesk/update-status', 'Modules\Helpdesk\Controllers\AdminHelpdeskController@updateStatus');
$router->add('admin/helpdesk/delete-request', 'Modules\Helpdesk\Controllers\AdminHelpdeskController@deleteRequest');
$router->add('admin/helpdesk/categories', 'Modules\Helpdesk\Controllers\AdminHelpdeskController@categories');
$router->add('admin/helpdesk/categories/store', 'Modules\Helpdesk\Controllers\AdminHelpdeskController@storeCategory');
$router->add('admin/helpdesk/categories/delete', 'Modules\Helpdesk\Controllers\AdminHelpdeskController@deleteCategory');

$router->resolve();
