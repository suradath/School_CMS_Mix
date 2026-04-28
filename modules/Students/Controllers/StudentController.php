<?php
declare(strict_types=1);

namespace Modules\Students\Controllers;

use Core\Controller;
use Modules\Students\Models\Student;

class StudentController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        // Allow access to admin, director, and teacher
        $this->requireRole(['admin', 'director', 'teacher']);
    }

    /**
     * Dashboard with Statistics
     */
    public function index(): void
    {
        $stats = Student::getDashboardStats();
        
        $this->renderWithLayout('Students.Views.dashboard', 'themes.admin.layout', [
            'title' => 'ระบบสารสนเทศนักเรียน (SIS Dashboard)',
            'stats' => $stats
        ]);
    }

    /**
     * Data Table view for Classroom
     */
    public function classroom(): void
    {
        $filters = [
            'class_level' => $_GET['class_level'] ?? '',
            'room_number' => $_GET['room_number'] ?? '',
            'search' => $_GET['search'] ?? ''
        ];

        $students = Student::getAll($filters);
        $classes = Student::getUniqueClasses();

        $this->renderWithLayout('Students.Views.classroom', 'themes.admin.layout', [
            'title' => 'ข้อมูลนักเรียนรายห้อง',
            'students' => $students,
            'classes' => $classes,
            'filters' => $filters
        ]);
    }

    /**
     * Individual Student Profile
     */
    public function profile($id = null): void
    {
        $id = ($id !== null) ? (int)$id : (int)($_GET['id'] ?? 0);
        
        if (!$id) {
            $_SESSION['error'] = 'ไม่พบรหัสนักเรียนที่ระบุ';
            header('Location: /students');
            exit;
        }

        $student = Student::findWithDetails($id);
        
        if (!$student) {
            $this->redirect('/students/classroom');
        }

        $this->renderWithLayout('Students.Views.profile', 'themes.admin.layout', [
            'title' => 'ประวัตินักเรียน: ' . $student['first_name'] . ' ' . $student['last_name'],
            'student' => $student
        ]);
    }

    /**
     * Clear all student data
     */
    public function clear(): void
    {
        // Only admin and director can clear data
        if (!\Core\Security::checkRole(['admin', 'director'])) {
            header("HTTP/1.1 403 Forbidden");
            $this->renderWithLayout('themes.admin.error_403', 'themes.admin.layout', [
                'title' => 'Access Denied'
            ]);
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (Student::clearAllData()) {
                $_SESSION['success'] = "ล้างข้อมูลนักเรียนทั้งหมดเรียบร้อยแล้ว";
            } else {
                $_SESSION['error'] = "เกิดข้อผิดพลาดในการล้างข้อมูล";
            }
        }
        $this->redirect('/students');
    }
}
