<?php
declare(strict_types=1);

namespace Modules\Helpdesk\Controllers;

use Core\Controller;
use Modules\Helpdesk\Models\HelpdeskModel;
use Core\Uploader;

class HelpdeskController extends Controller
{
    private HelpdeskModel $model;

    public function __construct()
    {
        parent::__construct();
        if (!isset($_SESSION['user_id'])) {
            $this->redirect('/auth');
        }
        $this->model = new HelpdeskModel();
    }

    public function index(): void
    {
        $categories = $this->model->getCategories();
        $this->renderWithLayout('modules.Helpdesk.Views.index', 'themes.admin.layout', [
            'title' => 'แจ้งซ่อม/บำรุงรักษา',
            'categories' => $categories
        ]);
    }

    public function myRepairs(): void
    {
        $repairs = $this->model->getMyRepairs((int)$_SESSION['user_id']);
        $this->renderWithLayout('modules.Helpdesk.Views.my_repairs', 'themes.admin.layout', [
            'title' => 'ประวัติการแจ้งซ่อมของฉัน',
            'repairs' => $repairs
        ]);
    }

    public function store(): void
    {
        $categoryId = (int)($_POST['category_id'] ?? 0);
        $location = $_POST['location'] ?? '';
        $description = $_POST['description'] ?? '';
        
        if (!$categoryId || !$location || !$description) {
            $this->json(['success' => false, 'message' => 'กรุณากรอกข้อมูลให้ครบถ้วน']);
            return;
        }

        $photoPaths = [];
        if (!empty($_FILES['photos']['name'][0])) {
            $uploader = new Uploader('uploads/helpdesk');
            $files = $_FILES['photos'];
            
            // Handle multiple files
            for ($i = 0; $i < count($files['name']); $i++) {
                if ($i >= 3) break; // Limit to 3 photos
                
                $fileData = [
                    'name' => $files['name'][$i],
                    'type' => $files['type'][$i],
                    'tmp_name' => $files['tmp_name'][$i],
                    'error' => $files['error'][$i],
                    'size' => $files['size'][$i]
                ];
                
                $path = $uploader->upload($fileData);
                if ($path) {
                    $photoPaths[] = $path;
                }
            }
        }

        $success = $this->model->createRequest([
            'reporter_id' => $_SESSION['user_id'],
            'category_id' => $categoryId,
            'location' => $location,
            'description' => $description,
            'photos' => json_encode($photoPaths)
        ]);

        if ($success) {
            $this->json(['success' => true, 'message' => 'ส่งคำขอแจ้งซ่อมเรียบร้อยแล้ว']);
        } else {
            $this->json(['success' => false, 'message' => 'เกิดข้อผิดพลาดในการบันทึกข้อมูล']);
        }
    }
}
