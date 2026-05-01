<?php
declare(strict_types=1);

namespace Modules\Helpdesk\Controllers;

use Core\Controller;
use Modules\Helpdesk\Models\HelpdeskModel;

class AdminHelpdeskController extends Controller
{
    private HelpdeskModel $model;

    public function __construct()
    {
        parent::__construct();
        // Check for admin or staff role
        $this->requireRole(['admin', 'staff', 'officer']);
        $this->model = new HelpdeskModel();
    }

    public function dashboard(): void
    {
        $requests = $this->model->getAllRequests();
        $this->renderWithLayout('modules.Helpdesk.Views.admin.dashboard', 'themes.admin.layout', [
            'title' => 'จัดการรายการแจ้งซ่อม',
            'requests' => $requests
        ]);
    }

    public function updateStatus(): void
    {
        $id = (int)($_POST['id'] ?? 0);
        $status = $_POST['status'] ?? '';
        $remarks = $_POST['remarks'] ?? null;

        if (!$id || !in_array($status, ['pending', 'in_progress', 'fixed', 'cancelled'])) {
            $this->json(['success' => false, 'message' => 'ข้อมูลสถานะไม่ถูกต้อง']);
            return;
        }

        try {
            $this->model->updateStatus($id, $status, $remarks);
            $this->json(['success' => true]);
        } catch (\Exception $e) {
            $this->json(['success' => false, 'message' => 'เกิดข้อผิดพลาด: ' . $e->getMessage()]);
        }
    }

    public function deleteRequest(): void
    {
        $id = (int)($_POST['id'] ?? 0);
        if (!$id) {
            $this->json(['success' => false, 'message' => 'ID ไม่ถูกต้อง']);
            return;
        }

        $success = $this->model->deleteRequest($id);
        $this->json(['success' => $success]);
    }

    public function categories(): void
    {
        $categories = $this->model->getCategories();
        $this->renderWithLayout('modules.Helpdesk.Views.admin.categories', 'themes.admin.layout', [
            'title' => 'จัดการประเภทงานซ่อม',
            'categories' => $categories
        ]);
    }

    public function storeCategory(): void
    {
        $name = $_POST['name'] ?? '';
        if (!$name) {
            $this->json(['success' => false, 'message' => 'กรุณาระบุชื่อประเภท']);
            return;
        }

        $slug = strtolower(str_replace(' ', '-', $name));
        $success = $this->model->addCategory($name, $slug);
        
        $this->json(['success' => $success]);
    }

    public function deleteCategory(): void
    {
        $id = (int)($_POST['id'] ?? 0);
        $success = $this->model->deleteCategory($id);
        
        if ($success) {
            $this->json(['success' => true]);
        } else {
            $this->json(['success' => false, 'message' => 'ไม่สามารถลบได้ เนื่องจากมีรายการแจ้งซ่อมที่ใช้ประเภทนี้อยู่']);
        }
    }
}
