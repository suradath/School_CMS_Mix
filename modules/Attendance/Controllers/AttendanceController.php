<?php
declare(strict_types=1);

namespace Modules\Attendance\Controllers;

use Core\Controller;
use Modules\Attendance\Models\Attendance;

class AttendanceController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        // Allow all authenticated users to have their own attendance records
        $this->requireAuth();
    }

    /**
     * Main Attendance Tracking Page
     */
    public function index(): void
    {
        $courses = Attendance::getCoursesWithClasses((int)$_SESSION['user_id']);
        $this->renderWithLayout('Attendance.Views.index', 'themes.admin.layout', [
            'title' => 'ระบบเช็คชื่อเข้าเรียน',
            'courses' => $courses
        ]);
    }

    /**
     * Course Setup Page
     */
    public function setup(): void
    {
        $teacherId = (int)$_SESSION['user_id'];
        $allCourses = Attendance::getCourses($teacherId);
        $linkedCourses = Attendance::getCoursesWithClasses($teacherId);
        $this->renderWithLayout('Attendance.Views.setup', 'themes.admin.layout', [
            'title' => 'ตั้งค่ารายวิชาและห้องเรียน',
            'allCourses' => $allCourses,
            'linkedCourses' => $linkedCourses
        ]);
    }

    /**
     * AJAX Fetch Student List
     */
    public function ajaxGetStudents(): void
    {
        $courseId = (int)($_GET['course_id'] ?? 0);
        $level = $_GET['class_level'] ?? '';
        $room = (int)($_GET['room_number'] ?? 0);
        $date = $_GET['date'] ?? date('Y-m-d');

        if (!$courseId || !$level || !$room) {
            echo "<div class='p-4 text-red-500'>กรุณาเลือกวิชาและห้องเรียนให้ครบถ้วน</div>";
            exit;
        }

        $students = Attendance::getStudentsWithStatus($courseId, $level, $room, $date);
        
        // Check if already checked (if any student has a status)
        $isEditMode = false;
        foreach ($students as $s) {
            if ($s['status'] !== null) {
                $isEditMode = true;
                break;
            }
        }

        $this->render('Attendance.Views.student_list', [
            'students' => $students,
            'isEditMode' => $isEditMode,
            'date' => $date,
            'courseId' => $courseId,
            'level' => $level,
            'room' => $room
        ]);
    }

    /**
     * Store Attendance Records
     */
    public function store(): void
    {
        $date = $_POST['date'] ?? '';
        $courseId = (int)($_POST['course_id'] ?? 0);
        $level = $_POST['class_level'] ?? '';
        $room = (int)($_POST['room_number'] ?? 0);
        $attendance = $_POST['attendance'] ?? []; // student_id => status

        if (!$date || !$courseId || empty($attendance)) {
            $_SESSION['error'] = "ข้อมูลไม่ครบถ้วน";
            $this->redirect('/attendance');
        }

        try {
            Attendance::saveAttendance($date, $courseId, $level, $room, $attendance);
            $_SESSION['success'] = "บันทึกข้อมูลเรียบร้อยแล้ว";
        } catch (\Exception $e) {
            $_SESSION['error'] = "เกิดข้อผิดพลาด: " . $e->getMessage();
        }

        $this->redirect('/attendance?date=' . $date . '&course_id=' . $courseId . '&class_level=' . urlencode($level) . '&room_number=' . $room);
    }

    /**
     * Store Course Setup
     */
    public function storeCourse(): void
    {
        $code = $_POST['course_code'] ?? '';
        $name = $_POST['course_name'] ?? '';
        $level = $_POST['class_level'] ?? '';
        $room = (int)($_POST['room_number'] ?? 0);

        if ($code && $name) {
            $courseId = Attendance::createCourse((int)$_SESSION['user_id'], $code, $name);
            if ($level && $room) {
                Attendance::linkClassroom($courseId, $level, $room);
            }
            $_SESSION['success'] = "เพิ่มรายวิชาเรียบร้อยแล้ว";
        }
        $this->redirect('/attendance/setup');
    }

    /**
     * Unlink Classroom
     */
    public function unlinkClassroom(): void
    {
        $id = (int)($_POST['id'] ?? 0);
        if ($id > 0) {
            Attendance::unlinkClassroom($id);
            $_SESSION['success'] = "ยกเลิกการผูกห้องเรียนเรียบร้อยแล้ว";
        }
        header('Location: /attendance/setup');
    }

    /**
     * Delete Course
     */
    public function deleteCourse(): void
    {
        $id = (int)($_POST['id'] ?? 0);
        if ($id) {
            Attendance::deleteCourse($id, (int)$_SESSION['user_id']);
            $_SESSION['success'] = "ลบรายวิชาเรียบร้อยแล้ว";
        }
        $this->redirect('/attendance/setup');
    }

    /**
     * Link Classroom to existing course
     */
    public function linkClassroom(): void
    {
        $courseId = (int)($_POST['course_id'] ?? 0);
        $level = $_POST['class_level'] ?? '';
        $room = (int)($_POST['room_number'] ?? 0);

        if ($courseId && $level && $room) {
            Attendance::linkClassroom($courseId, $level, $room);
            $_SESSION['success'] = "ผูกห้องเรียนเรียบร้อยแล้ว";
        }
        $this->redirect('/attendance/setup');
    }

    /**
     * Attendance Report Page
     */
    public function report(): void
    {
        $courses = Attendance::getCoursesWithClasses((int)$_SESSION['user_id']);
        $this->renderWithLayout('Attendance.Views.report', 'themes.admin.layout', [
            'title' => 'รายงานสรุปการเข้าเรียน',
            'courses' => $courses
        ]);
    }

    /**
     * AJAX Fetch Report Data
     */
    public function ajaxGetReport(): void
    {
        $courseId = (int)($_GET['course_id'] ?? 0);
        $level = $_GET['class_level'] ?? '';
        $room = (int)($_GET['room_number'] ?? 0);

        if (!$courseId || !$level || !$room) {
            echo "<div class='p-4 text-amber-500'>กรุณาเลือกวิชาเพื่อดูรายงาน</div>";
            exit;
        }

        $reportData = Attendance::getSummaryReport($courseId, $level, $room);

        $this->render('Attendance.Views.report_table', [
            'reportData' => $reportData,
            'courseId' => $courseId,
            'level' => $level,
            'room' => $room
        ]);
    }

    /**
     * AJAX Fetch Student Calendar View
     */
    public function ajaxStudentCalendar(): void
    {
        $studentId = (int)($_GET['student_id'] ?? 0);
        $courseId = (int)($_GET['course_id'] ?? 0);

        if (!$studentId || !$courseId) {
            echo "Invalid parameters";
            exit;
        }

        $student = \Modules\Students\Models\Student::findWithDetails($studentId);
        $history = Attendance::getStudentAttendanceHistory($studentId, $courseId);
        
        $this->render('Attendance.Views.student_calendar', [
            'student' => $student,
            'history' => $history,
            'courseId' => $courseId
        ]);
    }

    /**
     * Export to Excel
     */
    public function export(): void
    {
        $courseId = (int)($_GET['course_id'] ?? 0);
        $level = $_GET['class_level'] ?? '';
        $room = (int)($_GET['room_number'] ?? 0);
        $date = $_GET['date'] ?? date('Y-m-d');

        if (!$courseId) die("Invalid Course");

        $students = Attendance::getStudentsWithStatus($courseId, $level, $room, $date);
        
        // Find course name for title
        $courses = Attendance::getCoursesWithClasses((int)$_SESSION['user_id']);
        $courseInfo = null;
        foreach ($courses as $c) {
            if ($c['id'] == $courseId) {
                $courseInfo = $c;
                break;
            }
        }

        $courseName = $courseInfo ? $courseInfo['course_name'] : 'วิชา';
        $courseCode = $courseInfo ? $courseInfo['course_code'] : '';

        // Check if PhpSpreadsheet is available
        if (!class_exists('\PhpOffice\PhpSpreadsheet\Spreadsheet')) {
            die("Error: PhpSpreadsheet library not found. Please install it via composer.");
        }

        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Headers
        $sheet->setCellValue('A1', 'รายงานการเข้าเรียน');
        $sheet->setCellValue('A2', "วิชา: {$courseCode} - {$courseName}");
        $sheet->setCellValue('A3', "วันที่: {$date} | ห้อง: {$level}/{$room}");

        $sheet->setCellValue('A5', 'รหัสนักเรียน');
        $sheet->setCellValue('B5', 'ชื่อ-นามสกุล');
        $sheet->setCellValue('C5', 'สถานะ');

        $statusMap = [
            'present' => 'มาเรียน',
            'late' => 'มาสาย',
            'truant' => 'หนีเรียน',
            'absent' => 'ขาดเรียน',
            'personal_leave' => 'ลากิจ',
            'sick_leave' => 'ลาป่วย'
        ];

        $rowNum = 6;
        foreach ($students as $s) {
            $fullName = ($s['title'] ?? '') . $s['first_name'] . ' ' . $s['last_name'];
            $statusStr = $statusMap[$s['status']] ?? 'ยังไม่ได้เช็ค';

            $sheet->setCellValue('A' . $rowNum, $s['student_code']);
            $sheet->setCellValue('B' . $rowNum, $fullName);
            $sheet->setCellValue('C' . $rowNum, $statusStr);
            $rowNum++;
        }

        // Output to browser
        $filename = "Attendance_{$courseCode}_{$date}.xlsx";
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');

        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        $writer->save('php://output');
        exit;
    }
}
