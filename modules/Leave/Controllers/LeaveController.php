<?php
declare(strict_types=1);

namespace Modules\Leave\Controllers;

use Core\Controller;
use Core\Database;
use Core\DateHelper;
use Core\Uploader;
use Core\Security;
use Modules\Leave\Models\LeaveRequest;
use Modules\Leave\Models\LeaveType;

class LeaveController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->requireAuth();
    }

    /**
     * Dashboard for the logged in user
     */
    public function index(): void
    {
        $personnelId = $_SESSION['personnel_id'] ?? null;
        if (!$personnelId) {
            $this->renderWithLayout('Leave.Views.error', 'themes.admin.layout', [
                'title' => 'ไม่สามารถเข้าใช้งานได้',
                'message' => 'บัญชีผู้ใช้งานของคุณยังไม่ได้เชื่อมโยงกับ "ข้อมูลบุคลากร" จึงไม่สามารถใช้งานระบบการลาได้'
            ]);
            return;
        }

        $year = (int)date('Y');
        $quotaStats = LeaveRequest::getQuotaStats((int)$personnelId, $year);
        $history = LeaveRequest::getAll(['personnel_id' => $personnelId]);

        $this->renderWithLayout('Leave.Views.index', 'themes.admin.layout', [
            'title' => 'ระบบการลาออนไลน์',
            'quotaStats' => $quotaStats,
            'history' => $history
        ]);
    }

    /**
     * Show leave form
     */
    public function create(): void
    {
        $leaveTypes = LeaveType::getAll();
        $this->renderWithLayout('Leave.Views.create', 'themes.admin.layout', [
            'title' => 'เขียนใบลาออนไลน์',
            'leaveTypes' => $leaveTypes
        ]);
    }

    /**
     * Store leave request
     */
    public function store(): void
    {
        if (!Security::validate_csrf()) {
            die("Invalid CSRF Token");
        }

        $personnelId = (int)$_SESSION['personnel_id'];
        $leaveTypeId = (int)$_POST['leave_type_id'];
        $startDate = $_POST['start_date'];
        $endDate = $_POST['end_date'];
        $reason = $_POST['reason'];

        // 1. Check overlap
        if (LeaveRequest::hasOverlap($personnelId, $startDate, $endDate)) {
            $_SESSION['error'] = "ขออภัย คุณมีการลาในช่วงเวลาดังกล่าวอยู่แล้ว";
            $this->redirect('/leave/create');
            return;
        }

        // 2. Calculate working days
        $totalDays = DateHelper::countWorkingDays($startDate, $endDate);
        if ($totalDays <= 0) {
            $_SESSION['error'] = "วันที่เลือกไม่มีวันทำการ (เสาร์-อาทิตย์)";
            $this->redirect('/leave/create');
            return;
        }

        // 3. Handle attachment
        $attachmentUrl = null;
        if (isset($_FILES['attachment']) && $_FILES['attachment']['error'] === UPLOAD_ERR_OK) {
            $attachmentUrl = Uploader::uploadFile($_FILES['attachment'], 'leaves/' . $personnelId);
        }

        // 4. Save
        $success = LeaveRequest::create([
            'personnel_id' => $personnelId,
            'leave_type_id' => $leaveTypeId,
            'start_date' => $startDate,
            'end_date' => $endDate,
            'total_days' => $totalDays,
            'reason' => $reason,
            'attachment_url' => $attachmentUrl
        ]);

        if ($success) {
            $_SESSION['success'] = "ส่งคำขอลาเรียบร้อยแล้ว";
            $this->redirect('/leave');
        } else {
            $_SESSION['error'] = "เกิดข้อผิดพลาดในการบันทึกข้อมูล";
            $this->redirect('/leave/create');
        }
    }

    /**
     * Review requests for Managers/Admins
     */
    public function review(): void
    {
        $filters = [];
        if (!Security::checkRole(['admin', 'hr'])) {
            // Editor/Officer sees their department
            $this->requireRole(['editor', 'officer']);
            $filters['department_id'] = $_SESSION['department_id'] ?? 0;
        } else {
            $this->requireRole(['admin', 'hr']);
        }

        $requests = LeaveRequest::getAll($filters);

        $this->renderWithLayout('Leave.Views.review', 'themes.admin.layout', [
            'title' => 'พิจารณาคำขอลา',
            'requests' => $requests
        ]);
    }

    /**
     * Update leave status (Approve/Reject)
     */
    public function updateStatus(int $id): void
    {
        if (!Security::validate_csrf()) die("Invalid CSRF");

        $request = LeaveRequest::find($id);
        if (!$request) $this->redirect('/leave/review');

        // Check permission
        if (!Security::checkRole('admin')) {
            if ($request['department_id'] != $_SESSION['department_id']) {
                die("Access Denied");
            }
        }

        $status = $_POST['status'];
        $comments = [
            'dept_head_comment' => $_POST['dept_head_comment'] ?? $request['dept_head_comment'],
            'admin_comment' => $_POST['admin_comment'] ?? $request['admin_comment']
        ];

        $approvedBy = null;
        if ($status === 'approved' && Security::checkRole('admin')) {
            $approvedBy = (int)$_SESSION['user_id'];
        }

        LeaveRequest::updateStatus($id, $status, $comments, $approvedBy);
        $_SESSION['success'] = "อัปเดตสถานะใบลาเรียบร้อยแล้ว";
        $this->redirect('/leave/review');
    }

    /**
     * Admin Reports with Chart.js
     */
    public function reports(): void
    {
        $this->requireRole(['admin', 'officer', 'hr']);

        $year = (int)date('Y');
        
        // Stats for Chart.js
        $statsByType = Database::fetchAll("
            SELECT lt.name, SUM(lr.total_days) as total 
            FROM leave_requests lr
            JOIN leave_types lt ON lr.leave_type_id = lt.id
            WHERE lr.status = 'approved' AND YEAR(lr.start_date) = ?
            GROUP BY lt.id
        ", [$year]);

        $statsByMonth = Database::fetchAll("
            SELECT MONTH(start_date) as month, SUM(total_days) as total 
            FROM leave_requests 
            WHERE status = 'approved' AND YEAR(start_date) = ?
            GROUP BY month ORDER BY month ASC
        ", [$year]);

        // 1. All Leave Requests
        $allRequests = LeaveRequest::getAll();

        // 2. Summary per Person
        $personnelSummary = Database::fetchAll("
            SELECT p.id, p.name, d.name as department_name,
                   SUM(CASE WHEN lt.slug = 'sick' THEN lr.total_days ELSE 0 END) as sick_days,
                   SUM(CASE WHEN lt.slug = 'personal' THEN lr.total_days ELSE 0 END) as personal_days,
                   SUM(CASE WHEN lt.slug = 'vacation' THEN lr.total_days ELSE 0 END) as vacation_days,
                   SUM(lr.total_days) as total_days
            FROM personnel p
            LEFT JOIN departments d ON p.department_id = d.id
            LEFT JOIN leave_requests lr ON p.id = lr.personnel_id AND lr.status = 'approved' AND YEAR(lr.start_date) = ?
            LEFT JOIN leave_types lt ON lr.leave_type_id = lt.id
            GROUP BY p.id
            ORDER BY total_days DESC
        ", [$year]);

        $this->renderWithLayout('Leave.Views.reports', 'themes.admin.layout', [
            'title' => 'รายงานสรุปการลาภาพรวม',
            'statsByType' => $statsByType,
            'statsByMonth' => $statsByMonth,
            'allRequests' => $allRequests,
            'personnelSummary' => $personnelSummary
        ]);
    }
}
