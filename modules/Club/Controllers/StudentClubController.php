<?php
declare(strict_types=1);

namespace Modules\Club\Controllers;

use Core\Controller;
use Core\Database;
use Modules\Club\Models\Club;
use Modules\Club\Models\ClubMember;
use Modules\Club\Models\StudentAuth;

class StudentClubController extends Controller
{
    public function login(): void
    {
        if (isset($_SESSION['student_id'])) {
            $this->redirect('/club-dashboard');
        }
        $this->render('Club.Views.student.login', [
            'title' => 'เข้าสู่ระบบนักเรียน (ลงทะเบียนชุมนุม)'
        ]);
    }

    public function authenticate(): void
    {
        $studentCode = $_POST['student_code'] ?? '';
        $password = $_POST['password'] ?? '';

        $student = StudentAuth::authenticate($studentCode, $password);

        if ($student) {
            $_SESSION['student_id'] = $student['id'];
            $_SESSION['student_name'] = $student['first_name'] . ' ' . $student['last_name'];
            $_SESSION['student_grade'] = $student['class_level'];
            $this->redirect('/club-dashboard');
        } else {
            $_SESSION['error'] = 'รหัสนักเรียนหรือรหัสผ่านไม่ถูกต้อง';
            $this->redirect('/club-login');
        }
    }

    public function logout(): void
    {
        unset($_SESSION['student_id'], $_SESSION['student_name'], $_SESSION['student_grade']);
        $this->redirect('/club-login');
    }

    public function dashboard(): void
    {
        if (!isset($_SESSION['student_id'])) {
            $this->redirect('/club-login');
        }

        $studentId = (int)$_SESSION['student_id'];
        $grade = $_SESSION['student_grade'];

        // Check if system is enabled
        $enabled = Database::getSetting('club_registration_enabled', '0');
        
        $myClub = ClubMember::findByStudent($studentId);
        $availableClubs = [];

        if (!$myClub && $enabled === '1') {
            $availableClubs = Club::getAvailableForStudent($grade);
        }

        $this->render('Club.Views.student.dashboard', [
            'title' => 'หน้าจอนักเรียน - ลงทะเบียนชุมนุม',
            'student_name' => $_SESSION['student_name'],
            'student_grade' => $grade,
            'myClub' => $myClub,
            'availableClubs' => $availableClubs,
            'system_enabled' => $enabled === '1'
        ]);
    }

    public function register(): void
    {
        if (!isset($_SESSION['student_id'])) {
            $this->json(['success' => false, 'message' => 'กรุณาเข้าสู่ระบบ']);
        }

        $enabled = Database::getSetting('club_registration_enabled', '0');
        if ($enabled !== '1') {
            $this->json(['success' => false, 'message' => 'ระบบปิดรับสมัครแล้ว']);
        }

        $studentId = (int)$_SESSION['student_id'];
        $clubId = (int)($_POST['club_id'] ?? 0);

        // Validation
        $student = StudentAuth::getStudent($studentId);
        $club = Club::find($clubId);

        if (!$club) {
            $this->json(['success' => false, 'message' => 'ไม่พบข้อมูลชุมนุม']);
        }

        $targetGrades = json_decode($club['target_grades'], true) ?: [];
        if (!in_array($student['class_level'], $targetGrades)) {
            $this->json(['success' => false, 'message' => 'ชุมนุมนี้ไม่เปิดรับระดับชั้นของคุณ']);
        }

        if (ClubMember::register($clubId, $studentId)) {
            $this->json(['success' => true, 'message' => 'ลงทะเบียนสำเร็จ!']);
        } else {
            $this->json(['success' => false, 'message' => 'ลงทะเบียนไม่สำเร็จ (ชุมนุมอาจจะเต็มหรือคุณลงทะเบียนไปแล้ว)']);
        }
    }

    public function withdraw(): void
    {
        if (!isset($_SESSION['student_id'])) {
            $this->json(['success' => false, 'message' => 'กรุณาเข้าสู่ระบบ']);
        }

        $enabled = Database::getSetting('club_registration_enabled', '0');
        if ($enabled !== '1') {
            $this->json(['success' => false, 'message' => 'ระบบปิดรับสมัครแล้ว ไม่สามารถยกเลิกได้']);
        }

        $studentId = (int)$_SESSION['student_id'];

        if (ClubMember::withdraw($studentId)) {
            $this->json(['success' => true, 'message' => 'ยกเลิกการลงทะเบียนเรียบร้อยแล้ว']);
        } else {
            $this->json(['success' => false, 'message' => 'ไม่สามารถยกเลิกได้ หรือคุณไม่ได้ลงทะเบียนชุมนุมไว้']);
        }
    }
}
