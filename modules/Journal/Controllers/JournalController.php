<?php
declare(strict_types=1);

namespace Modules\Journal\Controllers;

use Core\Controller;
use Core\Database;
use Core\Uploader;
use Core\Security;
use Modules\Journal\Models\Journal;

class JournalController extends Controller
{
    public function index(): void
    {
        $this->requireAuth();
        $items = Journal::getAll();
        
        $this->renderWithLayout('Journal.Views.index', 'themes.admin.layout', [
            'title' => 'จัดการวารสาร',
            'items' => $items
        ]);
    }

    public function store(): void
    {
        $this->requireAuth();

        $title = $_POST['title'] ?? '';
        $sort_order = (int)($_POST['sort_order'] ?? 0);
        $image_url = '';

        if (!empty($_FILES['image']['name'])) {
            $image_url = Uploader::uploadImage($_FILES['image'], 'journals');
            
            if (!$image_url) {
                $_SESSION['error'] = "อัปโหลดรูปภาพไม่สำเร็จ";
                $this->redirect('/journal');
            }
        } else {
            $_SESSION['error'] = "กรุณาเลือกรูปภาพ";
            $this->redirect('/journal');
        }

        Journal::create([
            'title' => $title,
            'image_url' => $image_url,
            'sort_order' => $sort_order
        ]);

        $_SESSION['success'] = "เพิ่มวารสารเรียบร้อยแล้ว";
        $this->redirect('/journal');
    }

    public function delete(int $id): void
    {
        $this->requireAuth();
        Journal::delete($id);
        $_SESSION['success'] = "ลบวารสารเรียบร้อยแล้ว";
        $this->redirect('/journal');
    }
}
