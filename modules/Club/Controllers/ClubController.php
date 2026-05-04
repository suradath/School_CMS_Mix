<?php
declare(strict_types=1);

namespace Modules\Club\Controllers;

use Core\Controller;
use Core\Database;
use Core\Security;
use Modules\Club\Models\Club;
use Modules\Club\Models\ClubMember;

class ClubController extends Controller
{
    public function index(): void
    {
        $this->requireAuth();
        
        if (Security::hasRole('admin')) {
            $clubs = Club::getAll();
        } else {
            $personnelId = $_SESSION['personnel_id'] ?? 0;
            $myClub = Club::findByAdvisor((int)$personnelId);
            $clubs = $myClub ? [$myClub] : [];
        }

        $this->renderWithLayout('Club.Views.admin.index', 'themes.admin.layout', [
            'title' => 'จัดการชุมนุม',
            'clubs' => $clubs
        ]);
    }

    public function create(): void
    {
        $this->requireRole(['admin', 'teacher']);
        
        $personnelId = $_SESSION['personnel_id'] ?? 0;
        if (!Security::hasRole('admin')) {
            $existing = Club::findByAdvisor((int)$personnelId);
            if ($existing) {
                $_SESSION['error'] = 'คุณมีชุมนุมที่รับผิดชอบอยู่แล้ว';
                $this->redirect('/club');
            }
        }

        $personnel = Database::fetchAll("SELECT id, name FROM personnel ORDER BY name ASC");

        $this->renderWithLayout('Club.Views.admin.create', 'themes.admin.layout', [
            'title' => 'เพิ่มชุมนุมใหม่',
            'personnel' => $personnel,
            'myPersonnelId' => $personnelId
        ]);
    }

    public function store(): void
    {
        $this->requireRole(['admin', 'teacher']);
        
        $data = [
            'name' => $_POST['name'] ?? '',
            'advisor_id' => (int)($_POST['advisor_id'] ?? 0),
            'location' => $_POST['location'] ?? '',
            'capacity' => (int)($_POST['capacity'] ?? 0),
            'target_grades' => $_POST['target_grades'] ?? [],
            'status' => 'open'
        ];

        if (!Security::hasRole('admin')) {
            $data['advisor_id'] = (int)($_SESSION['personnel_id'] ?? 0);
        }

        if (Club::create($data)) {
            $_SESSION['success'] = 'เพิ่มชุมนุมเรียบร้อยแล้ว';
            $this->redirect('/club');
        } else {
            $_SESSION['error'] = 'ไม่สามารถเพิ่มชุมนุมได้ (คุณอาจจะเป็นครูประจำชุมนุมอื่นอยู่แล้ว)';
            $this->redirect('/club/create');
        }
    }

    public function edit(): void
    {
        $this->requireRole(['admin', 'teacher']);
        $id = (int)($_GET['id'] ?? 0);
        $club = Club::find($id);

        if (!$club) $this->redirect('/club');

        // Check permission
        if (!Security::hasRole('admin') && $club['advisor_id'] != ($_SESSION['personnel_id'] ?? 0)) {
            $this->redirect('/club');
        }

        $personnel = Database::fetchAll("SELECT id, name FROM personnel ORDER BY name ASC");

        $this->renderWithLayout('Club.Views.admin.edit', 'themes.admin.layout', [
            'title' => 'แก้ไขชุมนุม',
            'club' => $club,
            'personnel' => $personnel,
            'target_grades' => json_decode($club['target_grades'], true) ?: []
        ]);
    }

    public function update(): void
    {
        $this->requireRole(['admin', 'teacher']);
        $id = (int)($_POST['id'] ?? 0);
        $club = Club::find($id);

        if (!$club) $this->redirect('/club');

        if (!Security::hasRole('admin') && $club['advisor_id'] != ($_SESSION['personnel_id'] ?? 0)) {
            $this->redirect('/club');
        }

        $data = [
            'name' => $_POST['name'] ?? '',
            'advisor_id' => (int)($_POST['advisor_id'] ?? $club['advisor_id']),
            'location' => $_POST['location'] ?? '',
            'capacity' => (int)($_POST['capacity'] ?? 0),
            'target_grades' => $_POST['target_grades'] ?? [],
            'status' => $_POST['status'] ?? 'open'
        ];

        Club::update($id, $data);
        Club::updateCount($id); // Recalculate count and status

        $_SESSION['success'] = 'อัปเดตข้อมูลเรียบร้อยแล้ว';
        $this->redirect('/club');
    }

    public function delete(): void
    {
        $this->requireRole(['admin']);
        $id = (int)($_GET['id'] ?? 0);
        Club::delete($id);
        $_SESSION['success'] = 'ลบชุมนุมเรียบร้อยแล้ว';
        $this->redirect('/club');
    }

    public function attendance(): void
    {
        $this->requireRole(['admin', 'teacher']);
        $clubId = (int)($_GET['club_id'] ?? 0);
        $date = $_GET['date'] ?? date('Y-m-d');
        
        $club = Club::find($clubId);
        if (!$club) $this->redirect('/club');

        if (!Security::hasRole('admin') && $club['advisor_id'] != ($_SESSION['personnel_id'] ?? 0)) {
            $this->redirect('/club');
        }

        $members = ClubMember::getByClub($clubId);
        $attendance = ClubMember::getAttendance($clubId, $date);
        $attMap = [];
        foreach ($attendance as $a) {
            $attMap[$a['student_id']] = $a['status'];
        }

        $this->renderWithLayout('Club.Views.attendance.index', 'themes.admin.layout', [
            'title' => 'เช็คชื่อชุมนุม: ' . $club['name'],
            'club' => $club,
            'members' => $members,
            'date' => $date,
            'attMap' => $attMap
        ]);
    }

    public function saveAttendance(): void
    {
        $this->requireRole(['admin', 'teacher']);
        $clubId = (int)($_POST['club_id'] ?? 0);
        $date = $_POST['date'] ?? date('Y-m-d');
        $records = $_POST['attendance'] ?? [];

        ClubMember::saveAttendance($clubId, $records, $date);
        
        $_SESSION['success'] = 'บันทึกการเช็คชื่อเรียบร้อยแล้ว';
        $this->redirect('/club/attendance?club_id=' . $clubId . '&date=' . $date);
    }

    public function evaluation(): void
    {
        $this->requireRole(['admin', 'teacher']);
        $clubId = (int)($_GET['club_id'] ?? 0);
        $semester = $_GET['semester'] ?? '1';
        $year = (int)($_GET['year'] ?? (date('Y') + 543));

        $club = Club::find($clubId);
        if (!$club) $this->redirect('/club');

        if (!Security::hasRole('admin') && $club['advisor_id'] != ($_SESSION['personnel_id'] ?? 0)) {
            $this->redirect('/club');
        }

        $members = ClubMember::getByClub($clubId);
        $evaluations = ClubMember::getEvaluations($clubId, $semester, $year);
        $evalMap = [];
        foreach ($evaluations as $e) {
            $evalMap[$e['student_id']] = ['result' => $e['result'], 'remarks' => $e['remarks']];
        }

        $this->renderWithLayout('Club.Views.evaluation.index', 'themes.admin.layout', [
            'title' => 'ประเมินผลชุมนุม: ' . $club['name'],
            'club' => $club,
            'members' => $members,
            'semester' => $semester,
            'year' => $year,
            'evalMap' => $evalMap
        ]);
    }

    public function saveEvaluation(): void
    {
        $this->requireRole(['admin', 'teacher']);
        $clubId = (int)($_POST['club_id'] ?? 0);
        $semester = $_POST['semester'] ?? '1';
        $year = (int)($_POST['year'] ?? 0);
        $results = $_POST['result'] ?? [];
        $remarks = $_POST['remarks'] ?? [];

        $records = [];
        foreach ($results as $studentId => $res) {
            $records[$studentId] = [
                'result' => $res,
                'remarks' => $remarks[$studentId] ?? ''
            ];
        }

        ClubMember::saveEvaluation($clubId, $records, $semester, $year);

        $_SESSION['success'] = 'บันทึกการประเมินผลเรียบร้อยแล้ว';
        $this->redirect('/club/evaluation?club_id=' . $clubId . '&semester=' . $semester . '&year=' . $year);
    }

    public function settings(): void
    {
        $this->requireRole(['admin']);
        $enabled = Database::getSetting('club_registration_enabled', '0');

        $this->renderWithLayout('Club.Views.admin.settings', 'themes.admin.layout', [
            'title' => 'ตั้งค่าระบบชุมนุม',
            'enabled' => $enabled
        ]);
    }

    public function updateSettings(): void
    {
        $this->requireRole(['admin']);
        $enabled = $_POST['enabled'] ?? '0';
        Database::updateSetting('club_registration_enabled', $enabled);
        
        $_SESSION['success'] = 'อัปเดตการตั้งค่าเรียบร้อยแล้ว';
        $this->redirect('/club/settings');
    }

    public function export(): void
    {
        $this->requireRole(['admin', 'teacher']);
        $clubId = (int)($_GET['club_id'] ?? 0);
        $club = Club::find($clubId);
        if (!$club) die('Club not found');

        $members = ClubMember::getByClub($clubId);
        
        header("Content-Type: application/vnd.ms-excel");
        header("Content-Disposition: attachment; filename=club_members_" . $clubId . ".xls");
        header("Pragma: no-cache");
        header("Expires: 0");

        echo '<table border="1">';
        echo '<tr><th colspan="5">รายชื่อนักเรียนชุมนุม: ' . $club['name'] . '</th></tr>';
        echo '<tr><th>รหัสนักเรียน</th><th>ชื่อ-นามสกุล</th><th>ชั้น</th><th>ห้อง</th><th>หมายเหตุ</th></tr>';
        foreach ($members as $m) {
            echo '<tr>';
            echo '<td>' . $m['student_code'] . '</td>';
            echo '<td>' . $m['first_name'] . ' ' . $m['last_name'] . '</td>';
            echo '<td>' . $m['class_level'] . '</td>';
            echo '<td>' . $m['room_number'] . '</td>';
            echo '<td></td>';
            echo '</tr>';
        }
        echo '</table>';
        exit;
    }

    public function summary(): void
    {
        $this->requireRole(['admin', 'teacher']);
        $clubId = (int)($_GET['club_id'] ?? 0);
        $club = Club::find($clubId);
        if (!$club) $this->redirect('/club');

        if (!Security::hasRole('admin') && $club['advisor_id'] != ($_SESSION['personnel_id'] ?? 0)) {
            $this->redirect('/club');
        }

        $summary = ClubMember::getAttendanceSummary($clubId);

        $this->renderWithLayout('Club.Views.admin.summary', 'themes.admin.layout', [
            'title' => 'สรุปการเข้าเรียน: ' . $club['name'],
            'club' => $club,
            'summary' => $summary
        ]);
    }
}
