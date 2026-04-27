<?php
declare(strict_types=1);

namespace Modules\Auth\Controllers;

use Core\Controller;
use Core\Database;
use Modules\Auth\Models\User;

class UserManagementController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->requireRole('admin');
    }

    /**
     * List all users
     */
    public function index(): void
    {
        $users = User::getAll();
        $this->renderWithLayout('Auth.Views.management.index', 'themes.admin.layout', [
            'title' => 'จัดการผู้ใช้งาน',
            'users' => $users
        ]);
    }

    /**
     * Show create form
     */
    public function create(): void
    {
        $personnel = Database::fetchAll("SELECT id, name FROM personnel ORDER BY name ASC");
        $this->renderWithLayout('Auth.Views.management.edit', 'themes.admin.layout', [
            'title' => 'เพิ่มผู้ใช้งานใหม่',
            'personnel' => $personnel
        ]);
    }

    /**
     * Handle user storage
     */
    public function store(): void
    {
        $data = [
            'username' => $_POST['username'],
            'password' => $_POST['password'],
            'email' => $_POST['email'],
            'full_name' => $_POST['full_name'],
            'personnel_id' => $_POST['personnel_id'],
            'role' => $_POST['role'],
            'status' => $_POST['status']
        ];

        User::create($data);
        $this->redirect('/admin/users');
    }

    /**
     * Show edit form
     */
    public function edit(int $id): void
    {
        $user = User::find($id);
        if (!$user) {
            $this->redirect('/admin/users');
        }

        $personnel = Database::fetchAll("SELECT id, name FROM personnel ORDER BY name ASC");
        $this->renderWithLayout('Auth.Views.management.edit', 'themes.admin.layout', [
            'title' => 'แก้ไขผู้ใช้งาน',
            'user' => $user,
            'personnel' => $personnel
        ]);
    }

    /**
     * Handle user update
     */
    public function update(int $id): void
    {
        $data = [
            'email' => $_POST['email'],
            'full_name' => $_POST['full_name'],
            'personnel_id' => $_POST['personnel_id'],
            'role' => $_POST['role'],
            'status' => $_POST['status'],
            'password' => $_POST['password'] ?? ''
        ];

        User::update($id, $data);
        $this->redirect('/admin/users');
    }

    /**
     * Handle user deletion
     */
    public function delete(int $id): void
    {
        // Prevent deleting self
        if ($id === (int)$_SESSION['user_id']) {
            $_SESSION['error'] = 'คุณไม่สามารถลบบัญชีของตัวเองได้';
            $this->redirect('/admin/users');
        }

        Database::query("DELETE FROM users WHERE id = ?", [$id]);
        $this->redirect('/admin/users');
    }
}
