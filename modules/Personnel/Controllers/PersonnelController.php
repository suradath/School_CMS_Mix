<?php
declare(strict_types=1);

namespace Modules\Personnel\Controllers;

use Core\Controller;
use Core\Database;
use Core\Uploader;
use Modules\Personnel\Models\Personnel;

class PersonnelController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->requireAuth();
    }

    /**
     * Check if user has permission to manage this person
     */
    private function checkPersonnelPermission(int $personId = null, int $deptId = null): void
    {
        if (\Core\Security::checkRole('admin')) return;

        // Editor: Can manage personnel in their department
        if (\Core\Security::checkRole('editor')) {
            if ($deptId === (int)($_SESSION['department_id'] ?? 0)) return;
        }

        // Teacher: Can only edit their own profile
        if (\Core\Security::checkRole('teacher')) {
            if ($personId === (int)($_SESSION['personnel_id'] ?? 0)) return;
        }

        header("HTTP/1.1 403 Forbidden");
        die("Access Denied: You do not have permission to manage this personnel record.");
    }

    /**
     * List all personnel
     */
    public function index(): void
    {
        $departments = Personnel::getAllByDepartment();
        
        $this->renderWithLayout('Personnel.Views.index', 'themes.admin.layout', [
            'title' => 'จัดการบุคลากร',
            'departments' => $departments
        ]);
    }

    /**
     * Show creation form
     */
    public function create(): void
    {
        $this->requireRole(['admin', 'editor']);
        $departments = Database::fetchAll("SELECT * FROM departments ORDER BY sort_order ASC");
        
        $this->renderWithLayout('Personnel.Views.create', 'themes.admin.layout', [
            'title' => 'เพิ่มบุคลากรใหม่',
            'departments' => $departments
        ]);
    }

    /**
     * Handle creation submission
     */
    public function store(): void
    {
        $this->requireRole(['admin', 'editor']);
        $imageUrl = '';
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $imageUrl = Uploader::uploadImage($_FILES['image'], 'personnel');
        }

        Personnel::create([
            'name' => $_POST['name'],
            'position' => $_POST['position'],
            'department_id' => (int)$_POST['department_id'],
            'image_url' => $imageUrl,
            'email' => $_POST['email'],
            'phone' => $_POST['phone'],
            'bio' => $_POST['bio'],
            'sort_order' => (int)$_POST['sort_order']
        ]);

        $this->redirect('/personnel');
    }

    /**
     * Show edit form
     */
    public function edit(int $id): void
    {
        $person = Personnel::find($id);
        if (!$person) {
            $this->redirect('/personnel');
        }

        $this->checkPersonnelPermission((int)$person['id'], (int)$person['department_id']);
        
        $departments = Database::fetchAll("SELECT * FROM departments ORDER BY sort_order ASC");

        $this->renderWithLayout('Personnel.Views.edit', 'themes.admin.layout', [
            'title' => 'แก้ไขข้อมูลบุคลากร',
            'person' => $person,
            'departments' => $departments
        ]);
    }

    /**
     * Handle update submission
     */
    public function update(int $id): void
    {
        $person = Personnel::find($id);
        if (!$person) {
            $this->redirect('/personnel');
        }

        $this->checkPersonnelPermission((int)$person['id'], (int)$person['department_id']);

        $imageUrl = $person['image_url'];
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            // Delete old image if exists
            if (!empty($imageUrl)) {
                $oldPath = ROOT_PATH . '/' . ltrim($imageUrl, '/');
                if (file_exists($oldPath)) unlink($oldPath);
            }
            $imageUrl = Uploader::uploadImage($_FILES['image'], 'personnel');
        }

        Personnel::update($id, [
            'name' => $_POST['name'],
            'position' => $_POST['position'],
            'department_id' => (int)$_POST['department_id'],
            'image_url' => $imageUrl,
            'email' => $_POST['email'],
            'phone' => $_POST['phone'],
            'bio' => $_POST['bio'],
            'sort_order' => (int)$_POST['sort_order']
        ]);

        $this->redirect('/personnel');
    }

    /**
     * Handle deletion
     */
    public function delete(int $id): void
    {
        $person = Personnel::find($id);
        if ($person) {
            $this->checkPersonnelPermission((int)$person['id'], (int)$person['department_id']);
            Personnel::delete($id);
        }
        $this->redirect('/personnel');
    }
}
