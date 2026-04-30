<?php
declare(strict_types=1);

namespace Modules\Submissions\Controllers;

use Core\Controller;
use Core\Security;
use Modules\Submissions\Models\Submission;

class SubmissionsController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->requireAuth();
    }

    /**
     * Teacher Dashboard: View available topics and upload docs
     */
    public function index(): void
    {
        $userId = (int)$_SESSION['user_id'];
        $topics = Submission::getAllTopics(['status' => 'active']);
        $mySubmissions = Submission::getUserSubmissions($userId);
        
        // Group submissions by topic_id for easy lookup
        $submissionsByTopic = [];
        foreach ($mySubmissions as $sub) {
            $submissionsByTopic[$sub['topic_id']] = $sub;
        }

        $this->renderWithLayout('Submissions.Views.teacher.dashboard', 'themes.admin.layout', [
            'title' => 'ระบบส่งเอกสารและผลงานวิชาการ',
            'topics' => $topics,
            'submissions' => $submissionsByTopic
        ]);
    }

    /**
     * Admin: Manage Submission Topics
     */
    public function topics(): void
    {
        $this->requireRole(['admin', 'academic']);
        $topics = Submission::getAllTopics();
        
        $this->renderWithLayout('Submissions.Views.admin.topics', 'themes.admin.layout', [
            'title' => 'จัดการหัวข้อการส่งเอกสาร',
            'topics' => $topics
        ]);
    }

    /**
     * Admin/Academic: Save or Update Topic
     */
    public function storeTopic(): void
    {
        $this->requireRole(['admin', 'academic']);
        if (!Security::validate_csrf()) {
            $_SESSION['error'] = "Security Token Invalid.";
            $this->redirect('/submissions/topics');
        }
        
        $data = [
            'id' => !empty($_POST['id']) ? (int)$_POST['id'] : null,
            'title' => $_POST['title'] ?? '',
            'description' => $_POST['description'] ?? '',
            'semester' => $_POST['semester'] ?? '1',
            'academic_year' => (int)($_POST['academic_year'] ?? date('Y') + 543),
            'max_file_size' => (int)($_POST['max_file_size'] ?? 20),
            'status' => $_POST['status'] ?? 'active',
            'allowed_files' => $_POST['allowed_files'] ?? []
        ];

        if (Submission::saveTopic($data)) {
            $_SESSION['success'] = "บันทึกหัวข้อเรียบร้อยแล้ว";
        } else {
            $_SESSION['error'] = "เกิดข้อผิดพลาดในการบันทึก";
        }

        $this->redirect('/submissions/topics');
    }

    /**
     * Admin/Academic: Delete Topic
     */
    public function deleteTopic(): void
    {
        $this->requireRole(['admin', 'academic']);
        if (!Security::validate_csrf()) {
            $_SESSION['error'] = "Security Token Invalid.";
            $this->redirect('/submissions/topics');
        }
        $id = (int)($_POST['id'] ?? 0);
        if ($id && Submission::deleteTopic($id)) {
            $_SESSION['success'] = "ลบหัวข้อเรียบร้อยแล้ว";
        }
        $this->redirect('/submissions/topics');
    }

    /**
     * Teacher: Handle File Upload
     */
    public function submit(): void
    {
        if (!Security::validate_csrf()) {
            $_SESSION['error'] = "Security Token Invalid. Please try again.";
            $this->redirect('/submissions');
        }

        $topicId = (int)($_POST['topic_id'] ?? 0);
        $userId = (int)$_SESSION['user_id'];
        
        if (!$topicId || empty($_FILES['file']['name'])) {
            $_SESSION['error'] = "กรุณาเลือกไฟล์ที่ต้องการส่ง";
            $this->redirect('/submissions');
        }

        $topic = Submission::getTopic($topicId);
        if (!$topic || $topic['status'] !== 'active') {
            $_SESSION['error'] = "หัวข้อนี้ปิดรับการส่งแล้ว";
            $this->redirect('/submissions');
        }

        $file = $_FILES['file'];
        
        if ($file['error'] !== UPLOAD_ERR_OK) {
            $_SESSION['error'] = "เกิดข้อผิดพลาดในการอัปโหลด (Error Code: {$file['error']})";
            $this->redirect('/submissions');
        }

        $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        
        // Validation: Extension
        if (!in_array($ext, $topic['allowed_files'])) {
            $_SESSION['error'] = "ประเภทไฟล์ไม่รองรับ (รองรับ: " . implode(', ', $topic['allowed_files']) . ")";
            $this->redirect('/submissions');
        }

        // Validation: Size
        if ($file['size'] > ($topic['max_file_size'] * 1024 * 1024)) {
            $_SESSION['error'] = "ขนาดไฟล์เกินขีดจำกัด ({$topic['max_file_size']}MB)";
            $this->redirect('/submissions');
        }

        // Secure MIME check
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mime = finfo_file($finfo, $file['tmp_name']);
        finfo_close($finfo);

        // Security check: Don't allow executable types
        $forbiddenMimes = ['application/x-php', 'text/x-php', 'application/x-msdownload', 'application/x-sh', 'text/html'];
        if (in_array($mime, $forbiddenMimes)) {
            $_SESSION['error'] = "ไฟล์ไม่ปลอดภัย";
            $this->redirect('/submissions');
        }

        // Directory structure: uploads/submissions/{year}/{semester}/{topic_id}/
        $uploadPath = 'uploads' . DIRECTORY_SEPARATOR . 'submissions' . DIRECTORY_SEPARATOR . 
                     $topic['academic_year'] . DIRECTORY_SEPARATOR . 
                     $topic['semester'] . DIRECTORY_SEPARATOR . 
                     $topic['id'];
        
        $fullPath = ROOT_PATH . DIRECTORY_SEPARATOR . $uploadPath;
        
        if (!is_dir($fullPath)) {
            if (!mkdir($fullPath, 0777, true)) {
                $_SESSION['error'] = "ไม่สามารถสร้างโฟลเดอร์สำหรับเก็บไฟล์ได้ (Permission Denied)";
                $this->redirect('/submissions');
            }
        }

        // Naming: [topic_id]_[user_id]_[timestamp].[ext]
        $filename = "{$topic['id']}_{$userId}_" . time() . ".{$ext}";
        $targetFile = $fullPath . DIRECTORY_SEPARATOR . $filename;
        $dbPath = $uploadPath . '/' . $filename; // Use forward slashes for DB path to keep it web-friendly

        if (move_uploaded_file($file['tmp_name'], $targetFile)) {
            // Save to DB
            $subData = [
                'topic_id' => $topicId,
                'user_id' => $userId,
                'file_path' => $dbPath,
                'original_filename' => $file['name'],
                'mime_type' => $mime,
                'status' => 'pending'
            ];
            
            // Check if already has a submission (for revision/overwrite)
            $existing = Submission::getUserSubmissions($userId);
            foreach ($existing as $ex) {
                if ($ex['topic_id'] == $topicId) {
                    $subData['id'] = $ex['id'];
                    break;
                }
            }

            Submission::saveSubmission($subData);
            $_SESSION['success'] = "ส่งเอกสารเรียบร้อยแล้ว";
        } else {
            $_SESSION['error'] = "เกิดข้อผิดพลาดในการอัปโหลดไฟล์";
        }

        $this->redirect('/submissions');
    }

    /**
     * Academic: Monitor Submissions Dashboard
     */
    public function monitor(): void
    {
        $this->requireRole(['admin', 'academic', 'director']);
        $topicId = (int)($_GET['topic_id'] ?? 0);
        $topics = Submission::getAllTopics();
        
        $submissions = [];
        $selectedTopic = null;
        if ($topicId) {
            $selectedTopic = Submission::getTopic($topicId);
            $submissions = Submission::getAllSubmissions($topicId);
        }

        $this->renderWithLayout('Submissions.Views.academic.monitor', 'themes.admin.layout', [
            'title' => 'ติดตามการส่งเอกสาร',
            'topics' => $topics,
            'selectedTopic' => $selectedTopic,
            'submissions' => $submissions
        ]);
    }

    /**
     * Academic/Director: Update submission status and feedback
     */
    public function updateStatus(): void
    {
        $this->requireRole(['admin', 'academic', 'director']);
        if (!Security::validate_csrf()) {
            $_SESSION['error'] = "Security Token Invalid.";
            $this->redirect('/submissions/monitor');
        }
        
        $id = (int)($_POST['id'] ?? 0);
        $status = $_POST['status'] ?? 'pending';
        $feedback = $_POST['feedback'] ?? '';

        if ($id && in_array($status, ['approved', 'revision', 'pending'])) {
            Submission::saveSubmission([
                'id' => $id,
                'status' => $status,
                'feedback' => $feedback
            ]);
            $_SESSION['success'] = "อัปเดตสถานะเรียบร้อยแล้ว";
        }

        $topicId = $_POST['topic_id'] ?? 0;
        $this->redirect('/submissions/monitor?topic_id=' . $topicId);
    }

    /**
     * Export Topic Status to Excel
     */
    public function export(): void
    {
        $this->requireRole(['admin', 'academic', 'director']);
        $topicId = (int)($_GET['topic_id'] ?? 0);
        if (!$topicId) $this->redirect('/submissions/monitor');

        $topic = Submission::getTopic($topicId);
        $submissions = Submission::getAllSubmissions($topicId);

        if (!class_exists('\PhpOffice\PhpSpreadsheet\Spreadsheet')) {
            die("PhpSpreadsheet not found. Please install it via composer.");
        }

        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Header Style
        $sheet->setCellValue('A1', 'รายงานการส่งเอกสาร');
        $sheet->mergeCells('A1:G1');
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(16);
        
        $sheet->setCellValue('A2', "หัวข้อ: {$topic['title']} (ภาคเรียนที่ {$topic['semester']} ปีการศึกษา {$topic['academic_year']})");
        $sheet->mergeCells('A2:G2');

        // Table Headers
        $headers = ['ลำดับ', 'ชื่อ-นามสกุล', 'ตำแหน่ง', 'ฝ่าย/กลุ่มสาระ', 'วันที่ส่ง', 'สถานะ', 'หมายเหตุ/ข้อเสนอแนะ'];
        $col = 'A';
        foreach ($headers as $header) {
            $sheet->setCellValue($col . '4', $header);
            $sheet->getStyle($col . '4')->getFont()->setBold(true);
            $sheet->getStyle($col . '4')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('EEEEEE');
            $col++;
        }

        $row = 5;
        foreach ($submissions as $index => $s) {
            $statusInfo = Submission::getStatusInfo($s['status'] ?? '');
            $sheet->setCellValue('A' . $row, $index + 1);
            $sheet->setCellValue('B' . $row, $s['full_name']);
            $sheet->setCellValue('C' . $row, $s['position'] ?? '-');
            $sheet->setCellValue('D' . $row, $s['department'] ?? '-');
            $sheet->setCellValue('E' . $row, $s['submitted_at'] ? date('d/m/Y H:i', strtotime($s['submitted_at'])) : 'ยังไม่ส่ง');
            $sheet->setCellValue('F' . $row, $statusInfo['label']);
            $sheet->setCellValue('G' . $row, $s['feedback'] ?? '');
            
            // Status Color
            if ($s['status'] === 'approved') {
                $sheet->getStyle('F' . $row)->getFont()->getColor()->setARGB('FF008000');
            } elseif ($s['status'] === 'revision') {
                $sheet->getStyle('F' . $row)->getFont()->getColor()->setARGB('FFFF0000');
            }
            
            $row++;
        }

        // Auto width
        foreach (range('A', 'G') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        $filename = "Submissions_" . str_replace(' ', '_', $topic['title']) . "_" . date('Ymd_Hi') . ".xlsx";
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');

        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        $writer->save('php://output');
        exit;
    }
}
