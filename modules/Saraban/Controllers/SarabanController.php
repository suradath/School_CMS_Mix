<?php
declare(strict_types=1);

namespace Modules\Saraban\Controllers;

use Core\Controller;
use Core\Database;
use Core\Security;
use Modules\Saraban\Models\SarabanDocument;
use Modules\Saraban\Models\SarabanType;
use Modules\Saraban\Models\SarabanReceiver;
use Modules\Saraban\Models\SarabanMinute;

class SarabanController extends Controller
{
    public function index(): void
    {
        $this->requireAuth();
        $user = $_SESSION['user'] ?? null;
        $personnelId = (int)($_SESSION['personnel_id'] ?? 0);
        
        // Get department ID for this personnel
        $personnel = Database::fetch("SELECT department_id FROM personnel WHERE id = ?", [$personnelId]);
        $deptId = $personnel['department_id'] ?? 0;

        $inbox = SarabanDocument::getInbox($personnelId, (int)$deptId, $_GET);
        
        $isPrivileged = hasRole(['admin', 'officer']);
        if ($isPrivileged) {
            $stats = [
                'unread' => count(array_filter($inbox, fn($i) => $i['read_status'] === 'unread')),
                'total_inbound' => Database::fetch("SELECT COUNT(*) as count FROM saraban_documents d JOIN saraban_types t ON d.type_id = t.id WHERE t.slug = 'inbound'")['count'],
                'total_outbound' => Database::fetch("SELECT COUNT(*) as count FROM saraban_documents d JOIN saraban_types t ON d.type_id = t.id WHERE t.slug = 'outbound'")['count']
            ];
        } else {
            $stats = [
                'unread' => count(array_filter($inbox, fn($i) => $i['read_status'] === 'unread')),
                'total_inbound' => Database::fetch("SELECT COUNT(*) as count FROM saraban_documents d JOIN saraban_types t ON d.type_id = t.id JOIN saraban_receivers r ON d.id = r.document_id WHERE t.slug = 'inbound' AND (r.personnel_id = ? OR r.department_id = ?)", [$personnelId, $deptId])['count'],
                'total_outbound' => Database::fetch("SELECT COUNT(*) as count FROM saraban_documents d JOIN saraban_types t ON d.type_id = t.id JOIN saraban_receivers r ON d.id = r.document_id WHERE t.slug = 'outbound' AND (r.personnel_id = ? OR r.department_id = ?)", [$personnelId, $deptId])['count']
            ];
        }

        $this->renderWithLayout('Saraban.Views.dashboard', 'themes.admin.layout', [
            'title' => 'ระบบสารบรรณอิเล็กทรอนิกส์ (E-Saraban)',
            'inbox' => $inbox,
            'stats' => $stats,
            'userDeptId' => (int)$deptId
        ]);
    }

    public function inbound(): void
    {
        $this->requireAuth();
        $userAuth = [
            'is_privileged' => hasRole(['admin', 'officer']),
            'personnel_id' => $_SESSION['personnel_id'] ?? 0,
            'department_id' => $this->getUserDeptId()
        ];
        $items = SarabanDocument::getAllByType('inbound', $_GET, $userAuth);
        $this->renderWithLayout('Saraban.Views.list', 'themes.admin.layout', [
            'title' => 'ทะเบียนหนังสือรับ',
            'type' => 'inbound',
            'items' => $items
        ]);
    }
    
    private function getUserDeptId(): int
    {
        $personnelId = (int)($_SESSION['personnel_id'] ?? 0);
        $personnel = Database::fetch("SELECT department_id FROM personnel WHERE id = ?", [$personnelId]);
        return (int)($personnel['department_id'] ?? 0);
    }

    public function outbound(): void
    {
        $this->requireAuth();
        $userAuth = [
            'is_privileged' => hasRole(['admin', 'officer']),
            'personnel_id' => $_SESSION['personnel_id'] ?? 0,
            'department_id' => $this->getUserDeptId()
        ];
        $items = SarabanDocument::getAllByType('outbound', $_GET, $userAuth);
        $this->renderWithLayout('Saraban.Views.list', 'themes.admin.layout', [
            'title' => 'ทะเบียนหนังสือส่ง',
            'type' => 'outbound',
            'items' => $items
        ]);
    }

    public function orders(): void
    {
        $this->requireAuth();
        $userAuth = [
            'is_privileged' => hasRole(['admin', 'officer']),
            'personnel_id' => $_SESSION['personnel_id'] ?? 0,
            'department_id' => $this->getUserDeptId()
        ];
        $items = SarabanDocument::getAllByType('order', $_GET, $userAuth);
        $this->renderWithLayout('Saraban.Views.list', 'themes.admin.layout', [
            'title' => 'ทะเบียนคำสั่ง',
            'type' => 'order',
            'items' => $items
        ]);
    }

    public function announcements(): void
    {
        $this->requireAuth();
        $userAuth = [
            'is_privileged' => hasRole(['admin', 'officer']),
            'personnel_id' => $_SESSION['personnel_id'] ?? 0,
            'department_id' => $this->getUserDeptId()
        ];
        $items = SarabanDocument::getAllByType('announcement', $_GET, $userAuth);
        $this->renderWithLayout('Saraban.Views.list', 'themes.admin.layout', [
            'title' => 'ทะเบียนประกาศ',
            'type' => 'announcement',
            'items' => $items
        ]);
    }

    public function batchEndorse(): void
    {
        $this->requireRole(['admin', 'officer', 'director']);
        $docIds = $_POST['doc_ids'] ?? [];
        
        if (empty($docIds)) {
            $_SESSION['error'] = "กรุณาเลือกรายการที่ต้องการเกษียณหนังสือ";
            $this->redirect('/saraban');
        }

        $userId = (int)$_SESSION['user_id'];
        $count = 0;

        foreach ($docIds as $id) {
            $id = (int)$id;
            // Create a minute note "ทราบ"
            SarabanMinute::create([
                'document_id' => $id,
                'user_id' => $userId,
                'note' => 'ทราบ/ถือปฏิบัติ',
                'decision' => 'acknowledged'
            ]);
            // Update status
            SarabanMinute::updateDocumentStatus($id, 'processed');
            $count++;
        }

        $_SESSION['success'] = "เกษียณหนังสือแบบด่วนเรียบร้อยแล้ว $count รายการ";
        $this->redirect('/saraban');
    }
}
