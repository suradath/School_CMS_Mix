<?php
declare(strict_types=1);

namespace Modules\Saraban\Controllers;

use Core\Controller;
use Core\Database;
use Core\Uploader;
use Core\Security;
use Modules\Saraban\Models\SarabanDocument;
use Modules\Saraban\Models\SarabanType;
use Modules\Saraban\Models\SarabanReceiver;
use Modules\Saraban\Models\SarabanMinute;

class DocumentController extends Controller
{
    public function create(string $typeSlug): void
    {
        $this->requireRole(['admin', 'officer']);
        $type = SarabanType::findBySlug($typeSlug);
        if (!$type) $this->redirect('/saraban');

        $budgetYear = (date('n') >= 10) ? (int)date('Y') + 543 + 1 : (int)date('Y') + 543;
        $nextNum = SarabanType::getNextNumber((int)$type['id'], $budgetYear);
        $docNo = str_pad((string)$nextNum, 3, '0', STR_PAD_LEFT) . '/' . $budgetYear;

        $departments = Database::fetchAll("SELECT * FROM departments");
        $personnel = Database::fetchAll("SELECT * FROM personnel");

        $suggestedBookNo = '';
        if ($type['slug'] !== 'inbound' && !empty($type['prefix'])) {
            $suggestedBookNo = $type['prefix'] . ' ' . $nextNum . '/' . $budgetYear;
        }

        $this->renderWithLayout('Saraban.Views.create', 'themes.admin.layout', [
            'title' => 'ลงทะเบียน' . $type['name'],
            'type' => $type,
            'doc_no' => $docNo,
            'suggested_book_no' => $suggestedBookNo,
            'budget_year' => $budgetYear,
            'departments' => $departments,
            'personnel' => $personnel
        ]);
    }

    public function store(): void
    {
        $this->requireRole(['admin', 'officer']);
        
        $typeId = (int)$_POST['type_id'];
        $budgetYear = (int)$_POST['budget_year'];
        $type = Database::fetch("SELECT * FROM saraban_types WHERE id = ?", [$typeId]);
        
        // Final check for numbering to prevent race conditions
        $nextNum = SarabanType::getNextNumber($typeId, $budgetYear);
        $docNo = str_pad((string)$nextNum, 3, '0', STR_PAD_LEFT) . '/' . $budgetYear;

        $fileUrl = '';
        if (!empty($_FILES['file']['name'])) {
            $subDir = 'saraban/' . $budgetYear . '/' . date('m');
            $fileUrl = Uploader::uploadFile($_FILES['file'], $subDir, ['pdf', 'jpg', 'jpeg', 'png'], 20);
        }

        $data = [
            'type_id' => $typeId,
            'doc_no' => $docNo,
            'book_no' => $_POST['book_no'] ?? null,
            'title' => $_POST['title'],
            'origin' => $_POST['origin'] ?? null,
            'priority' => $_POST['priority'] ?? 'normal',
            'doc_date' => $_POST['doc_date'] ?? date('Y-m-d'),
            'received_date' => $_POST['received_date'] ?? null,
            'file_url' => $fileUrl,
            'created_by' => $_SESSION['user_id'],
            'budget_year' => $budgetYear
        ];

        $docId = SarabanDocument::create($data);

        if ($docId) {
            // Distribute
            $receivers = [];
            if (!empty($_POST['dept_receivers'])) {
                foreach ($_POST['dept_receivers'] as $deptId) {
                    $receivers[] = ['department_id' => $deptId];
                }
            }
            if (!empty($_POST['person_receivers'])) {
                foreach ($_POST['person_receivers'] as $personId) {
                    $receivers[] = ['personnel_id' => $personId];
                }
            }
            if (!empty($receivers)) {
                SarabanReceiver::distribute($docId, $receivers);
            }

            // Save Initial Minute Note if provided
            if (!empty($_POST['initial_note'])) {
                SarabanMinute::create([
                    'document_id' => $docId,
                    'user_id' => (int)$_SESSION['user_id'],
                    'note' => $_POST['initial_note'],
                    'decision' => 'none'
                ]);
                SarabanMinute::updateDocumentStatus($docId, 'minuted');
            }

            $_SESSION['success'] = "ลงทะเบียนหนังสือเรียบร้อยแล้ว เลขที่ " . $docNo;
            $this->redirect('/saraban/' . $type['slug']);
        } else {
            $_SESSION['error'] = "เกิดข้อผิดพลาดในการบันทึกข้อมูล";
            $this->redirect('/saraban');
        }
    }

    public function view(int $id): void
    {
        $this->requireAuth();
        $doc = SarabanDocument::find($id);
        if (!$doc) $this->redirect('/saraban');

        $receivers = SarabanReceiver::getReceivers($id);
        $personnelId = (int)($_SESSION['personnel_id'] ?? 0);
        
        // Get user department ID
        $personnel = Database::fetch("SELECT department_id FROM personnel WHERE id = ?", [$personnelId]);
        $userDeptId = (int)($personnel['department_id'] ?? 0);

        // Security check: Only Admin, Officer, or Receiver (person/dept) can view
        $isReceiver = false;
        $myAcknowledge = null;
        foreach ($receivers as $r) {
            if ($r['personnel_id'] == $personnelId || ($r['department_id'] > 0 && $r['department_id'] == $userDeptId)) {
                $isReceiver = true;
                if ($r['personnel_id'] == $personnelId) {
                    $myAcknowledge = $r;
                }
            }
        }

        if (!hasRole(['admin', 'officer']) && !$isReceiver) {
            $_SESSION['error'] = "คุณไม่มีสิทธิ์เข้าถึงเอกสารฉบับนี้";
            $this->redirect('/saraban');
        }

        $minutes = SarabanMinute::getByDocumentId($id);

        $this->renderWithLayout('Saraban.Views.view', 'themes.admin.layout', [
            'title' => 'รายละเอียดเอกสาร',
            'doc' => $doc,
            'receivers' => $receivers,
            'myAcknowledge' => $myAcknowledge,
            'minutes' => $minutes,
            'userDeptId' => $userDeptId
        ]);
    }

    public function addMinute(): void
    {
        $this->requireAuth();
        $docId = (int)$_POST['document_id'];
        
        // 1. Check if user is a receiver
        $receivers = SarabanReceiver::getReceivers($docId);
        $personnelId = (int)($_SESSION['personnel_id'] ?? 0);
        $personnel = Database::fetch("SELECT department_id FROM personnel WHERE id = ?", [$personnelId]);
        $userDeptId = (int)($personnel['department_id'] ?? 0);
        
        $isReceiver = false;
        foreach ($receivers as $r) {
            if ($r['personnel_id'] == $personnelId || ($r['department_id'] > 0 && $r['department_id'] == $userDeptId)) {
                $isReceiver = true;
                break;
            }
        }

        if (!hasRole(['admin', 'officer']) && !$isReceiver) {
            $_SESSION['error'] = "คุณไม่มีสิทธิ์เกษียณหนังสือฉบับนี้";
            $this->redirect('/saraban/view/' . $docId);
        }

        // 2. Check if already minuted
        $minutes = SarabanMinute::getByDocumentId($docId);
        foreach ($minutes as $m) {
            if ($m['user_id'] == $_SESSION['user_id']) {
                $_SESSION['error'] = "คุณได้เกษียณหนังสือฉบับนี้ไปแล้ว";
                $this->redirect('/saraban/view/' . $docId);
            }
        }

        $note = $_POST['note'] ?? '';
        $decision = $_POST['decision'] ?? 'none';
        
        if (empty($note)) {
            $_SESSION['error'] = "กรุณากรอกข้อความเกษียณหนังสือ";
            $this->redirect('/saraban/view/' . $docId);
        }

        $success = SarabanMinute::create([
            'document_id' => $docId,
            'user_id' => (int)$_SESSION['user_id'],
            'note' => $note,
            'decision' => $decision
        ]);

        if ($success) {
            // Update document status based on role and decision
            if (hasRole('director')) {
                SarabanMinute::updateDocumentStatus($docId, 'processed');
                // Notify back to staff by resetting unread status
                SarabanReceiver::resetStatus($docId);
            } else {
                SarabanMinute::updateDocumentStatus($docId, 'minuted');
            }
            $_SESSION['success'] = "บันทึกข้อความเกษียณเรียบร้อยแล้ว";
        } else {
            $_SESSION['error'] = "เกิดข้อผิดพลาดในการบันทึกข้อมูล";
        }

        $this->redirect('/saraban/view/' . $docId);
    }

    public function deleteMinute(): void
    {
        $this->requireAuth();
        $id = (int)$_POST['id'];
        $minute = SarabanMinute::find($id);
        
        if (!$minute) $this->redirect('/saraban');

        // Only owner or admin can delete
        if ($minute['user_id'] != $_SESSION['user_id'] && !hasRole(['admin', 'officer'])) {
            die("Access Denied");
        }

        if (SarabanMinute::delete($id)) {
            $_SESSION['success'] = "ยกเลิกข้อความเกษียณเรียบร้อยแล้ว";
        }
        $this->redirect('/saraban/view/' . $minute['document_id']);
    }

    public function printMinute(int $id): void
    {
        $this->requireAuth();
        $doc = SarabanDocument::find($id);
        if (!$doc) die("Document not found");

        $minutes = SarabanMinute::getByDocumentId($id);
        
        $this->render('Saraban.Views.print_minute', [
            'doc' => $doc,
            'minutes' => $minutes
        ]);
    }

    public function acknowledge(int $id): void
    {
        $this->requireAuth();
        $personnelId = (int)($_SESSION['personnel_id'] ?? 0);
        if (SarabanReceiver::acknowledge($id, $personnelId)) {
            $_SESSION['success'] = "บันทึกการรับทราบเรียบร้อยแล้ว";
        }
        $this->redirect('/saraban/view/' . $id);
    }

    public function delete(int $id): void
    {
        $this->requireRole(['admin', 'officer']);
        
        $doc = SarabanDocument::find($id);
        if (!$doc) $this->redirect('/saraban');

        // Delete file if exists
        if (!empty($doc['file_url'])) {
            $filePath = ROOT_PATH . $doc['file_url'];
            if (file_exists($filePath)) {
                unlink($filePath);
            }
        }

        if (SarabanDocument::delete($id)) {
            $_SESSION['success'] = "ลบเอกสารเรียบร้อยแล้ว";
        } else {
            $_SESSION['error'] = "เกิดข้อผิดพลาดในการลบเอกสาร";
        }

        $this->redirect('/saraban/' . $doc['type_slug']);
    }
}
