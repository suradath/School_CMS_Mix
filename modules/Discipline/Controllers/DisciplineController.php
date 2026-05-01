<?php
declare(strict_types=1);

namespace Modules\Discipline\Controllers;

use Core\Controller;
use Modules\Discipline\Models\Discipline;
use Modules\Students\Models\Student;

class DisciplineController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->requireRole(['admin', 'discipline_staff']);
    }

    /**
     * Dashboard: Summary of all students
     */
    public function index(): void
    {
        $class = $_GET['class'] ?? '';
        $room = (int)($_GET['room'] ?? 0);
        
        $students = Discipline::getStudentsWithScores($class, $room);
        
        $this->renderWithLayout('Discipline.Views.index', 'themes.admin.layout', [
            'title' => 'ระบบงานปกครองและพฤติกรรม',
            'students' => $students,
            'selectedClass' => $class,
            'selectedRoom' => $room
        ]);
    }

    /**
     * Manage Categories
     */
    public function categories(): void
    {
        $categories = Discipline::getCategories();
        $this->renderWithLayout('Discipline.Views.categories', 'themes.admin.layout', [
            'title' => 'จัดการประเภทพฤติกรรม',
            'categories' => $categories
        ]);
    }

    public function saveCategory(): void
    {
        $data = [
            'id' => (int)($_POST['id'] ?? 0),
            'name' => $_POST['name'] ?? '',
            'points' => (int)($_POST['points'] ?? 0),
            'type' => $_POST['type'] ?? 'bad'
        ];

        if ($data['name']) {
            Discipline::saveCategory($data);
            $_SESSION['success'] = "บันทึกข้อมูลเรียบร้อยแล้ว";
        }
        $this->redirect('/discipline/categories');
    }

    public function deleteCategory(): void
    {
        $id = (int)($_POST['id'] ?? 0);
        if ($id > 0) {
            Discipline::deleteCategory($id);
            $_SESSION['success'] = "ลบข้อมูลเรียบร้อยแล้ว";
        }
        $this->redirect('/discipline/categories');
    }

    /**
     * Record Behavior Form
     */
    public function record(): void
    {
        $studentId = (int)($_GET['student_id'] ?? 0);
        $student = null;
        if ($studentId > 0) {
            $student = Student::findWithDetails($studentId);
        }

        $categories = Discipline::getCategories();
        
        $this->renderWithLayout('Discipline.Views.record', 'themes.admin.layout', [
            'title' => 'บันทึกพฤติกรรม',
            'student' => $student,
            'categories' => $categories
        ]);
    }

    public function storeRecord(): void
    {
        $studentId = (int)($_POST['student_id'] ?? 0);
        $categoryId = (int)($_POST['category_id'] ?? 0);
        $remarks = $_POST['remarks'] ?? '';
        $date = $_POST['record_date'] ?? null;
        $createdBy = (int)$_SESSION['user_id'];

        if ($studentId && $categoryId) {
            Discipline::recordBehavior($studentId, $categoryId, $remarks, $createdBy, $date);
            $_SESSION['success'] = "บันทึกพฤติกรรมเรียบร้อยแล้ว";
        }

        $this->redirect('/discipline/history?student_id=' . $studentId);
    }

    /**
     * Student Detailed History
     */
    public function history(): void
    {
        $studentId = (int)($_GET['student_id'] ?? 0);
        if (!$studentId) {
            $this->redirect('/discipline');
        }

        $student = Student::findWithDetails($studentId);
        $logs = Discipline::getStudentLogs($studentId);
        $summary = Discipline::getStudentSummary($studentId);

        $this->renderWithLayout('Discipline.Views.history', 'themes.admin.layout', [
            'title' => 'ประวัติพฤติกรรมรายบุคคล',
            'student' => $student,
            'logs' => $logs,
            'summary' => $summary
        ]);
    }

    /**
     * Delete Log Entry
     */
    public function deleteLog(): void
    {
        $id = (int)($_POST['id'] ?? 0);
        $studentId = (int)($_POST['student_id'] ?? 0);
        
        if ($id > 0) {
            Discipline::deleteLog($id, (int)$_SESSION['user_id']);
            $_SESSION['success'] = "ลบรายการบันทึกเรียบร้อยแล้ว";
        }
        
        $this->redirect('/discipline/history?student_id=' . $studentId);
    }
}
