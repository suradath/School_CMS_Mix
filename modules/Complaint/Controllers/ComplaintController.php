<?php
declare(strict_types=1);

namespace Modules\Complaint\Controllers;

use Core\Controller;
use Core\Database;
use Core\Security;
use Core\Uploader;
use Modules\Complaint\Models\Complaint;

class ComplaintController extends Controller
{
    /**
     * Public Complaint Form
     */
    public function index(): void
    {
        $siteName = Database::getSetting('site_name', 'School CMS');
        
        // Simple Math CAPTCHA
        $num1 = rand(1, 10);
        $num2 = rand(1, 10);
        $_SESSION['captcha_answer'] = $num1 + $num2;

        $this->renderWithLayout('Modules.Complaint.Views.form', 'themes.default.layout', [
            'site_name' => $siteName,
            'title' => 'ส่งเรื่องร้องเรียน / ข้อเสนอแนะ',
            'captcha_question' => "$num1 + $num2 = ?"
        ]);
    }

    /**
     * Process Public Submission
     */
    public function store(): void
    {
        if (!Security::validate_csrf()) {
            die("Invalid CSRF Token");
        }

        // Validate CAPTCHA
        $userAnswer = (int)($_POST['captcha'] ?? 0);
        if ($userAnswer !== ($_SESSION['captcha_answer'] ?? -1)) {
            $_SESSION['error'] = 'คำตอบ CAPTCHA ไม่ถูกต้อง';
            header("Location: " . url('/complaint'));
            exit;
        }

        $topic = trim($_POST['topic'] ?? '');
        $details = trim($_POST['details'] ?? '');

        if (empty($topic) || empty($details)) {
            $_SESSION['error'] = 'กรุณากรอกหัวข้อและรายละเอียด';
            header("Location: " . url('/complaint'));
            exit;
        }

        // Handle File Upload
        $attachment = null;
        if (isset($_FILES['attachment']) && $_FILES['attachment']['error'] === UPLOAD_ERR_OK) {
            $uploadedPath = Uploader::uploadImage($_FILES['attachment'], 'complaints', 5);
            if ($uploadedPath) {
                $attachment = $uploadedPath;
            } else {
                $_SESSION['error'] = 'ไม่สามารถอัปโหลดรูปภาพได้ (อนุญาตเฉพาะ .jpg, .png ขนาดไม่เกิน 5MB)';
                header("Location: " . url('/complaint'));
                exit;
            }
        }

        $data = [
            'topic' => $topic,
            'details' => $details,
            'attachment' => $attachment,
            'contact_name' => $_POST['contact_name'] ?? null,
            'contact_info' => $_POST['contact_info'] ?? null
        ];

        if (Complaint::save($data)) {
            $_SESSION['success'] = 'ส่งข้อมูลเรียบร้อยแล้ว ขอบคุณสำหรับข้อเสนอแนะของคุณ';
        } else {
            $_SESSION['error'] = 'เกิดข้อผิดพลาดในการบันทึกข้อมูล';
        }

        header("Location: " . url('/complaint'));
        exit;
    }

    /**
     * Admin: List Complaints
     */
    public function adminIndex(): void
    {
        if (!Security::hasRole(['admin', 'director'])) {
            $this->render('themes.admin.error_403');
            return;
        }

        $complaints = Complaint::getAll();
        
        $this->renderWithLayout('Modules.Complaint.Views.admin.index', 'themes.admin.layout', [
            'title' => 'ระบบรับเรื่องร้องเรียน',
            'complaints' => $complaints
        ]);
    }

    /**
     * Admin: View Detail
     */
    public function adminView(int $id): void
    {
        if (!Security::hasRole(['admin', 'director'])) {
            $this->render('themes.admin.error_403');
            return;
        }

        $item = Complaint::getById($id);
        if (!$item) {
            header("Location: " . url('/admin/complaints'));
            exit;
        }

        // Auto-mark as read if unread
        if ($item['status'] === 'unread') {
            Complaint::updateStatus($id, 'read', (int)$_SESSION['user_id']);
            $item['status'] = 'read';
        }

        $this->renderWithLayout('Modules.Complaint.Views.admin.view', 'themes.admin.layout', [
            'title' => 'รายละเอียดข้อร้องเรียน',
            'item' => $item
        ]);
    }

    /**
     * Admin: Update Status (AJAX)
     */
    public function adminUpdateStatus(): void
    {
        if (!Security::hasRole(['admin', 'director'])) {
            echo json_encode(['success' => false, 'message' => 'Unauthorized']);
            return;
        }

        $id = (int)($_POST['id'] ?? 0);
        $status = $_POST['status'] ?? '';
        
        if ($id > 0 && in_array($status, ['read', 'in_progress', 'resolved'])) {
            Complaint::updateStatus($id, $status);
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Invalid data']);
        }
    }

    /**
     * Admin: Delete
     */
    public function adminDelete(): void
    {
        if (!Security::hasRole('admin')) {
            echo json_encode(['success' => false, 'message' => 'Unauthorized']);
            return;
        }

        $id = (int)($_POST['id'] ?? 0);
        if ($id > 0) {
            Complaint::delete($id);
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Invalid ID']);
        }
    }
}
