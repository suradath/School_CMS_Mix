<?php
declare(strict_types=1);

namespace Modules\Personnel\Controllers;

use Core\Controller;
use Modules\Personnel\Models\Department;

class DepartmentController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->requireRole(['admin', 'editor']);
    }

    public function index(): void
    {
        $departments = Department::getAll();
        $this->renderWithLayout('Personnel.Views.departments.index', 'themes.admin.layout', [
            'title' => 'จัดการกลุ่มสาระฯ / ฝ่ายงาน',
            'departments' => $departments
        ]);
    }

    public function create(): void
    {
        $this->renderWithLayout('Personnel.Views.departments.create', 'themes.admin.layout', [
            'title' => 'เพิ่มกลุ่มสาระฯ / ฝ่ายงานใหม่'
        ]);
    }

    public function store(): void
    {
        Department::create([
            'name' => $_POST['name'],
            'description' => $_POST['description'] ?? '',
            'sort_order' => (int)($_POST['sort_order'] ?? 0)
        ]);
        $_SESSION['success'] = 'เพิ่มกลุ่มสาระฯ / ฝ่ายงาน เรียบร้อยแล้ว';
        $this->redirect('/personnel/departments');
    }

    public function edit(int $id): void
    {
        $department = Department::find($id);
        if (!$department) {
            $this->redirect('/personnel/departments');
        }
        $this->renderWithLayout('Personnel.Views.departments.edit', 'themes.admin.layout', [
            'title' => 'แก้ไขกลุ่มสาระฯ / ฝ่ายงาน',
            'department' => $department
        ]);
    }

    public function update(int $id): void
    {
        Department::update($id, [
            'name' => $_POST['name'],
            'description' => $_POST['description'] ?? '',
            'sort_order' => (int)($_POST['sort_order'] ?? 0)
        ]);
        $_SESSION['success'] = 'อัปเดตข้อมูลเรียบร้อยแล้ว';
        $this->redirect('/personnel/departments');
    }

    public function delete(int $id): void
    {
        if (Department::delete($id)) {
            $_SESSION['success'] = 'ลบข้อมูลเรียบร้อยแล้ว';
        } else {
            $_SESSION['error'] = 'ไม่สามารถลบได้ เนื่องจากมีบุคลากรอยู่ในกลุ่มสาระฯ / ฝ่ายงานนี้';
        }
        $this->redirect('/personnel/departments');
    }
}
