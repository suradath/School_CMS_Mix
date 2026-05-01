<?php
declare(strict_types=1);

namespace Modules\PLC\Controllers;

use Core\Controller;
use Core\Database;
use Modules\PLC\Models\PLC;

class PLCGroupController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        if (!isset($_SESSION['user_id'])) {
            $this->redirect('/auth');
        }
    }

    /**
     * List all groups or manage groups
     */
    public function groups(): void
    {
        $allGroups = PLC::getAllGroups();
        $myMemberships = Database::fetchAll("SELECT group_id, status FROM plc_group_members WHERE user_id = ?", [$_SESSION['user_id']]);
        $myGroupsIds = array_column($myMemberships, 'status', 'group_id');

        $this->renderWithLayout('PLC.Views.group.index', 'themes.admin.layout', [
            'title' => 'จัดการกลุ่ม PLC',
            'groups' => $allGroups,
            'myGroupsIds' => $myGroupsIds
        ]);
    }

    /**
     * Store new group
     */
    public function storeGroup(): void
    {
        $name = $_POST['name'] ?? '';
        $year = $_POST['academic_year'] ?? (date('Y') + 543);
        $desc = $_POST['description'] ?? '';
        $goal = (int)($_POST['target_goal'] ?? 50);

        if ($name) {
            $groupId = Database::insert("
                INSERT INTO plc_groups (name, description, target_goal, academic_year, created_by)
                VALUES (?, ?, ?, ?, ?)
            ", [$name, $desc, $goal, $year, $_SESSION['user_id']]);

            if ($groupId) {
                // Add creator as Head
                Database::query("
                    INSERT INTO plc_group_members (group_id, user_id, role, status)
                    VALUES (?, ?, 'head', 'approved')
                ", [$groupId, $_SESSION['user_id']]);
                
                $_SESSION['success'] = "สร้างกลุ่ม PLC เรียบร้อยแล้ว";
            }
        }
        $this->redirect('/plc/groups');
    }

    /**
     * View Group Workspace
     */
    public function view(int $id): void
    {
        $group = PLC::getGroup($id);
        if (!$group) $this->redirect('/plc');

        $userId = (int)$_SESSION['user_id'];
        $members = PLC::getGroupMembers($id);
        $meetings = PLC::getMeetings($id);
        $materials = PLC::getGroupMaterials($id);
        $allUsers = Database::fetchAll("SELECT id, full_name, username FROM users WHERE status = 'active' ORDER BY full_name ASC");

        // Check user role in group
        $myMembership = Database::fetch("SELECT * FROM plc_group_members WHERE group_id = ? AND user_id = ?", [$id, $userId]);
        
        $this->renderWithLayout('PLC.Views.group.view', 'themes.admin.layout', [
            'title' => $group['name'],
            'group' => $group,
            'members' => $members,
            'meetings' => $meetings,
            'materials' => $materials,
            'allUsers' => $allUsers,
            'myMembership' => $myMembership
        ]);
    }

    /**
     * Add member to group
     */
    public function addMember(): void
    {
        $groupId = (int)($_POST['group_id'] ?? 0);
        $userId = (int)($_POST['user_id'] ?? 0);
        $role = $_POST['role'] ?? 'member';

        if ($groupId && $userId) {
            try {
                Database::query("
                    INSERT INTO plc_group_members (group_id, user_id, role, status)
                    VALUES (?, ?, ?, 'approved')
                ", [$groupId, $userId, $role]);
                $_SESSION['success'] = "เพิ่มสมาชิกเรียบร้อยแล้ว";
            } catch (\Exception $e) {
                $_SESSION['error'] = "สมาชิกอยู่ในกลุ่มนี้แล้ว";
            }
        }
        $this->redirect('/plc/group/view/' . $groupId);
    }

    /**
     * Store Meeting Log
     */
    public function storeMeeting(): void
    {
        $groupId = (int)$_POST['group_id'];
        $data = [
            'group_id' => $groupId,
            'topic' => $_POST['topic'],
            'problem_topic' => $_POST['problem_topic'],
            'solution' => $_POST['solution'],
            'result' => $_POST['result'],
            'hours' => (float)$_POST['hours'],
            'date' => $_POST['date'],
            'created_by' => (int)$_SESSION['user_id']
        ];

        $meetingId = PLC::recordMeeting($data);
        if ($meetingId && !empty($_FILES['materials']['name'][0])) {
            $this->handleUploads((int)$meetingId);
        }

        $_SESSION['success'] = "บันทึกกิจกรรม PLC เรียบร้อยแล้ว (รอการตรวจสอบ)";
        $this->redirect('/plc/group/view/' . $groupId);
    }

    /**
     * Verify/Approve Meeting
     */
    public function approveMeeting(): void
    {
        $meetingId = (int)$_POST['meeting_id'];
        $groupId = (int)$_POST['group_id'];
        $status = $_POST['status'] ?? 'approved';

        // Check if user is Head or Admin
        $isHead = Database::fetch("SELECT id FROM plc_group_members WHERE group_id = ? AND user_id = ? AND role = 'head'", [$groupId, $_SESSION['user_id']]);
        $isAdmin = \Core\Security::hasRole('admin');

        if ($isHead || $isAdmin) {
            PLC::updateMeetingStatus($meetingId, $status, (int)$_SESSION['user_id']);
            $_SESSION['success'] = "ดำเนินการอนุมัติชั่วโมงเรียบร้อยแล้ว";
        } else {
            $_SESSION['error'] = "คุณไม่มีสิทธิ์ดำเนินการนี้";
        }
        $this->redirect('/plc/group/view/' . $groupId);
    }

    /**
     * Request to join group
     */
    public function requestJoin(): void
    {
        $groupId = (int)($_POST['group_id'] ?? 0);
        $userId = (int)$_SESSION['user_id'];

        if ($groupId && $userId) {
            PLC::joinGroup($groupId, $userId, 'member', 'pending');
            $_SESSION['success'] = "ส่งคำขอเข้าร่วมกลุ่มเรียบร้อยแล้ว (รอการอนุมัติ)";
        }
        $this->redirect('/plc/groups');
    }

    /**
     * Approve/Reject member
     */
    public function approveMember(): void
    {
        $groupId = (int)($_POST['group_id'] ?? 0);
        $userId = (int)($_POST['user_id'] ?? 0);
        $status = $_POST['status'] ?? 'approved';

        // Check if head
        $isHead = Database::fetch("SELECT id FROM plc_group_members WHERE group_id = ? AND user_id = ? AND role = 'head'", [$groupId, $_SESSION['user_id']]);
        if ($isHead || \Core\Security::hasRole('admin')) {
            PLC::updateMemberStatus($groupId, $userId, $status);
            $_SESSION['success'] = "ดำเนินการเรียบร้อยแล้ว";
        }
        $this->redirect('/plc/group/view/' . $groupId);
    }

    /**
     * Export PLC Summary to Excel (CSV)
     */
    public function exportExcel(): void
    {
        $userId = (int)($_GET['user_id'] ?? $_SESSION['user_id']);
        $year = $_GET['year'] ?? (date('Y') + 543);
        
        $sql = "SELECT m.*, g.name as group_name
                FROM plc_meetings m
                JOIN plc_groups g ON m.group_id = g.id
                JOIN plc_group_members mem ON g.id = mem.group_id
                WHERE mem.user_id = ? AND g.academic_year = ? AND m.status = 'approved'
                ORDER BY m.date ASC";
        $logs = Database::fetchAll($sql, [$userId, $year]);

        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename=PLC_Report_' . $year . '.csv');
        
        $output = fopen('php://output', 'w');
        fputs($output, $bom = (chr(0xEF) . chr(0xBB) . chr(0xBF))); // UTF-8 BOM for Excel
        
        fputcsv($output, ['วันที่', 'กลุ่ม PLC', 'หัวข้อ/กิจกรรม', 'ประเด็นปัญหา', 'วิธีแก้ปัญหา', 'ผลลัพธ์', 'จำนวนชั่วโมง']);
        
        foreach ($logs as $log) {
            fputcsv($output, [
                $log['date'],
                $log['group_name'],
                $log['topic'],
                $log['problem_topic'],
                $log['solution'],
                $log['result'],
                $log['hours']
            ]);
        }
        fclose($output);
        exit;
    }

    private function handleUploads(int $meetingId): void
    {
        $uploadDir = ROOT_PATH . '/uploads/plc/';
        if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);

        foreach ($_FILES['materials']['tmp_name'] as $key => $tmpName) {
            if ($_FILES['materials']['error'][$key] === UPLOAD_ERR_OK) {
                $fileName = $_FILES['materials']['name'][$key];
                $extension = pathinfo($fileName, PATHINFO_EXTENSION);
                $newName = uniqid('plc_') . '.' . $extension;
                $targetPath = $uploadDir . $newName;

                if (move_uploaded_file($tmpName, $targetPath)) {
                    PLC::saveMaterial($meetingId, $fileName, 'uploads/plc/' . $newName, $extension, (int)$_SESSION['user_id']);
                }
            }
        }
    }
}
