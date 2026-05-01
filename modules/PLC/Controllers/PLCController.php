<?php
declare(strict_types=1);

namespace Modules\PLC\Controllers;

use Core\Controller;
use Modules\PLC\Models\PLC;

class PLCController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        if (!isset($_SESSION['user_id'])) {
            $this->redirect('/auth');
        }
    }

    /**
     * Personal PLC Dashboard
     */
    public function index(): void
    {
        $userId = (int)$_SESSION['user_id'];
        $currentYearBE = (int)date('Y') + 543;
        $academicYear = $_GET['year'] ?? (string)$currentYearBE;
        if (date('m') < 5 && !isset($_GET['year'])) {
            $academicYear = (string)($currentYearBE - 1);
        }

        $myGroups = PLC::getUserGroups($userId);
        $totalHours = PLC::getUserTotalHours($userId, $academicYear);
        $summary = PLC::getUserSummary($userId, $academicYear);
        
        $this->renderWithLayout('PLC.Views.dashboard', 'themes.admin.layout', [
            'title' => 'ระบบ PLC - แผงควบคุม',
            'myGroups' => $myGroups,
            'totalHours' => $totalHours,
            'summary' => $summary,
            'academicYear' => $academicYear,
            'targetGoal' => 50
        ]);
    }

    /**
     * Printable Report
     */
    public function report(): void
    {
        $userId = (int)($_GET['user_id'] ?? $_SESSION['user_id']);
        $academicYear = $_GET['year'] ?? date('Y');
        
        // Fetch user info for report
        $user = \Core\Database::fetch("
            SELECT u.*, p.position as position_name, d.name as department_name
            FROM users u
            LEFT JOIN personnel p ON u.personnel_id = p.id
            LEFT JOIN departments d ON p.department_id = d.id
            WHERE u.id = ?
        ", [$userId]);
        
        if (!$user) $this->redirect('/plc');

        $summary = PLC::getUserSummary($userId, $academicYear);
        $totalHours = PLC::getUserTotalHours($userId, $academicYear);

        // Detailed logs for the report
        $sql = "SELECT m.*, g.name as group_name
                FROM plc_meetings m
                JOIN plc_groups g ON m.group_id = g.id
                JOIN plc_group_members mem ON g.id = mem.group_id
                WHERE mem.user_id = ? AND g.academic_year = ? AND m.status = 'approved'
                ORDER BY m.date ASC";
        $logs = \Core\Database::fetchAll($sql, [$userId, $academicYear]);

        $this->render('PLC.Views.report.print', [
            'title' => 'รายงานสรุปชั่วโมง PLC',
            'user' => $user,
            'summary' => $summary,
            'totalHours' => $totalHours,
            'logs' => $logs,
            'academicYear' => $academicYear
        ]);
    }

    /**
     * Admin Global Hours Report
     */
    public function adminReports(): void
    {
        if (!\Core\Security::hasRole(['admin', 'hr', 'director', 'academic'])) {
            $this->redirect('/plc');
        }

        $academicYear = $_GET['year'] ?? (string)(date('Y') + 543);
        $summary = PLC::getAllUsersHoursSummary($academicYear);

        $this->renderWithLayout('PLC.Views.admin.reports', 'themes.admin.layout', [
            'title' => 'รายงานสรุปชั่วโมง PLC ทั้งโรงเรียน',
            'summary' => $summary,
            'academicYear' => $academicYear,
            'targetGoal' => 50
        ]);
    }
}
