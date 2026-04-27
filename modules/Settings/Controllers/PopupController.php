<?php
declare(strict_types=1);

namespace Modules\Settings\Controllers;

use Core\Controller;
use Core\Database;
use Core\Uploader;
use Modules\Settings\Models\EntryPopup;

class PopupController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->requireRole('admin');
    }

    public function index(): void
    {
        $popups = EntryPopup::getAll();
        $this->renderWithLayout('Settings.Views.popups.index', 'themes.admin.layout', [
            'title' => 'จัดการ Entry Popup',
            'popups' => $popups
        ]);
    }

    public function create(): void
    {
        $this->renderWithLayout('Settings.Views.popups.create', 'themes.admin.layout', [
            'title' => 'เพิ่ม Entry Popup'
        ]);
    }

    public function store(): void
    {
        $imageUrl = '';
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $imageUrl = Uploader::uploadImage($_FILES['image'], 'popups');
        }

        EntryPopup::create([
            'title' => $_POST['title'],
            'image_url' => $imageUrl,
            'link_url' => $_POST['link_url'] ?? '',
            'is_active' => isset($_POST['is_active']) ? 1 : 0
        ]);

        $_SESSION['success'] = 'เพิ่ม Entry Popup เรียบร้อยแล้ว';
        $this->redirect('/settings/popups');
    }

    public function edit(int $id): void
    {
        $popup = EntryPopup::find($id);
        if (!$popup) {
            $this->redirect('/settings/popups');
        }

        $this->renderWithLayout('Settings.Views.popups.edit', 'themes.admin.layout', [
            'title' => 'แก้ไข Entry Popup',
            'popup' => $popup
        ]);
    }

    public function update(int $id): void
    {
        $popup = EntryPopup::find($id);
        if (!$popup) {
            $this->redirect('/settings/popups');
        }

        $imageUrl = $popup['image_url'];
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            // Delete old image
            if (!empty($imageUrl)) {
                $oldPath = ROOT_PATH . '/' . ltrim($imageUrl, '/');
                if (file_exists($oldPath)) @unlink($oldPath);
            }
            $imageUrl = Uploader::uploadImage($_FILES['image'], 'popups');
        }

        EntryPopup::update($id, [
            'title' => $_POST['title'],
            'image_url' => $imageUrl,
            'link_url' => $_POST['link_url'] ?? '',
            'is_active' => isset($_POST['is_active']) ? 1 : 0
        ]);

        $_SESSION['success'] = 'อัปเดต Entry Popup เรียบร้อยแล้ว';
        $this->redirect('/settings/popups');
    }

    public function delete(int $id): void
    {
        $popup = EntryPopup::find($id);
        if ($popup && !empty($popup['image_url'])) {
            $path = ROOT_PATH . '/' . ltrim($popup['image_url'], '/');
            if (file_exists($path)) @unlink($path);
        }
        EntryPopup::delete($id);
        $_SESSION['success'] = 'ลบ Entry Popup เรียบร้อยแล้ว';
        $this->redirect('/settings/popups');
    }
}
