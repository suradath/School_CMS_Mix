<?php
declare(strict_types=1);

namespace Modules\Health\Controllers;

use Core\Controller;
use Core\Database;
use Modules\Health\Models\HealthModel;

class AdminHealthController extends Controller
{
    private HealthModel $model;

    public function __construct()
    {
        parent::__construct();
        // Restrict to Admin and Nurse roles
        $this->requireRole(['admin', 'nurse']);
        $this->model = new HealthModel();
    }

    /**
     * Search students for treatment form (AJAX)
     */
    public function searchStudents(): void
    {
        $q = $_GET['q'] ?? '';
        if (strlen($q) < 2) {
            $this->json([]);
        }

        $sql = "SELECT id, student_code, first_name, last_name, class_level, room_number, chronic_disease, medication_allergy 
                FROM students 
                WHERE student_code LIKE ? OR first_name LIKE ? OR last_name LIKE ? 
                LIMIT 10";
        $students = Database::fetchAll($sql, ["%$q%", "%$q%", "%$q%"]);
        $this->json($students);
    }


    /**
     * Health Dashboard
     */
    public function dashboard(): void
    {
        $stats = $this->model->getStats('monthly');
        $lowStock = $this->model->getLowStockMedicines();
        $recent = $this->model->getRecentRecords(10);

        $this->renderWithLayout('modules.Health.Views.admin.dashboard', 'themes.admin.layout', [
            'title' => 'ระบบงานพยาบาล - Dashboard',
            'stats' => $stats,
            'lowStock' => $lowStock,
            'recent' => $recent
        ]);
    }

    /**
     * BMI Management Page (Export/Import)
     */
    public function bmi(): void
    {
        // Get unique classes for filter
        $classes = Database::fetchAll("SELECT DISTINCT class_level FROM students ORDER BY class_level ASC");
        
        $this->renderWithLayout('modules.Health.Views.admin.bmi', 'themes.admin.layout', [
            'title' => 'จัดการข้อมูลน้ำหนัก-ส่วนสูง (BMI)',
            'classes' => $classes
        ]);
    }

    /**
     * Export Student CSV for BMI
     */
    public function exportCSV(): void
    {
        $class = $_GET['class'] ?? '';
        $room = (int)($_GET['room'] ?? 0);

        if (!$class || !$room) {
            $_SESSION['error'] = 'กรุณาระบุชั้นและห้องเรียน';
            $this->redirect('/admin/health/bmi');
        }

        $students = $this->model->getStudentsForExport($class, $room);

        $filename = "ข้อมูลน้ำหนักส่วนสูงห้อง_" . $class . "_" . $room . ".csv";
        
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename="' . $filename . '"');

        $output = fopen('php://output', 'w');
        
        // Add BOM for Thai characters in Excel
        fprintf($output, chr(0xEF).chr(0xBB).chr(0xBF));

        // Header
        fputcsv($output, ['รหัสนักเรียน', 'ชื่อ-สกุล', 'น้ำหนัก', 'ส่วนสูง']);

        foreach ($students as $s) {
            fputcsv($output, [
                $s['student_code'],
                $s['first_name'] . ' ' . $s['last_name'],
                $s['weight'] ?? 0,
                $s['height'] ?? 0
            ]);
        }

        fclose($output);
        exit;
    }

    /**
     * Import BMI CSV
     */
    public function importCSV(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || empty($_FILES['csv_file']['tmp_name'])) {
            $this->redirect('/admin/health/bmi');
        }

        $file = $_FILES['csv_file']['tmp_name'];
        $handle = fopen($file, "r");
        
        // Skip BOM if present
        $bom = fread($handle, 3);
        if ($bom !== "\xEF\xBB\xBF") {
            rewind($handle);
        }

        // Skip header
        fgetcsv($handle);

        $updatedCount = 0;
        while (($data = fgetcsv($handle)) !== FALSE) {
            $studentCode = $data[0] ?? '';
            $weight = (float)($data[2] ?? 0);
            $height = (float)($data[3] ?? 0);

            if ($studentCode) {
                if ($this->model->updateBMI($studentCode, $weight, $height)) {
                    $updatedCount++;
                }
            }
        }

        fclose($handle);
        $_SESSION['success'] = "นำเข้าข้อมูลสำเร็จ $updatedCount รายการ";
        $this->redirect('/admin/health/bmi');
    }

    /**
     * Medicine Inventory Management
     */
    public function inventory(): void
    {
        $medicines = $this->model->getMedicines();
        $this->renderWithLayout('modules.Health.Views.admin.inventory', 'themes.admin.layout', [
            'title' => 'จัดการคลังยา',
            'medicines' => $medicines
        ]);
    }

    /**
     * Store new medicine
     */
    public function storeMedicine(): void
    {
        $code = $_POST['code'] ?? '';
        $name = $_POST['name'] ?? '';
        $qty = (int)($_POST['stock_quantity'] ?? 0);
        $min = (int)($_POST['min_stock_level'] ?? 10);

        if (!$code || !$name) {
            $_SESSION['error'] = 'กรุณากรอกรหัสและชื่อยา';
            $this->redirect('/admin/health/inventory');
        }

        $sql = "INSERT INTO medicines (code, name, properties, stock_quantity, min_stock_level) VALUES (?, ?, ?, ?, ?)";
        Database::query($sql, [$code, $name, $_POST['properties'] ?? '', $qty, $min]);

        $_SESSION['success'] = 'เพิ่มรายการยาเรียบร้อยแล้ว';
        $this->redirect('/admin/health/inventory');
    }

    /**
     * Update medicine stock/details
     */
    public function updateMedicine(): void
    {
        $id = (int)$_POST['id'];
        $qty = (int)($_POST['stock_quantity'] ?? 0);
        $min = (int)($_POST['min_stock_level'] ?? 10);

        $sql = "UPDATE medicines SET name = ?, properties = ?, stock_quantity = ?, min_stock_level = ? WHERE id = ?";
        Database::query($sql, [$_POST['name'], $_POST['properties'], $qty, $min, $id]);

        $_SESSION['success'] = 'อัปเดตข้อมูลยาเรียบร้อยแล้ว';
        $this->redirect('/admin/health/inventory');
    }

    /**
     * Delete medicine
     */
    public function deleteMedicine(): void
    {
        $id = (int)($_GET['id'] ?? 0);
        Database::query("DELETE FROM medicines WHERE id = ?", [$id]);
        $_SESSION['success'] = 'ลบรายการยาเรียบร้อยแล้ว';
        $this->redirect('/admin/health/inventory');
    }

    /**
     * Update Student Health Info (Chronic disease/Allergy)
     */
    public function updateStudentHealth(): void
    {
        $studentId = (int)$_POST['student_id'];
        $chronic = $_POST['chronic_disease'] ?? '';
        $allergy = $_POST['medication_allergy'] ?? '';

        if ($this->model->updateStudentHealthInfo($studentId, $chronic, $allergy)) {
            $_SESSION['success'] = 'อัปเดตข้อมูลสุขภาพนักเรียนเรียบร้อยแล้ว';
        } else {
            $_SESSION['error'] = 'ไม่สามารถอัปเดตข้อมูลได้ หรือข้อมูลไม่มีการเปลี่ยนแปลง';
        }

        $this->redirect('/admin/health/create-treatment?student_id=' . $studentId);
    }


    /**
     * New Treatment Form
     */
    public function createTreatment(): void
    {
        $medicines = $this->model->getMedicines();
        $this->renderWithLayout('modules.Health.Views.admin.treatment_form', 'themes.admin.layout', [
            'title' => 'บันทึกการรักษาใหม่',
            'medicines' => $medicines
        ]);
    }

    /**
     * Store Treatment Record
     */
    public function storeTreatment(): void
    {
        $studentId = (int)$_POST['student_id'];
        $symptoms = $_POST['symptoms'] ?? '';
        
        if (!$studentId || !$symptoms) {
            $_SESSION['error'] = 'กรุณาระบุข้อมูลนักเรียนและอาการ';
            $this->redirect('/admin/health/create-treatment');
        }

        $data = [
            'student_id' => $studentId,
            'symptoms' => $symptoms,
            'treatment' => $_POST['treatment'] ?? '',
            'is_referral' => isset($_POST['is_referral']),
            'referral_hospital' => $_POST['referral_hospital'] ?? null,
            'referral_reason' => $_POST['referral_reason'] ?? null,
            'created_by' => $_SESSION['user_id']
        ];

        $prescriptions = [];
        if (!empty($_POST['medicines'])) {
            foreach ($_POST['medicines'] as $medId => $qty) {
                if ((int)$qty > 0) {
                    $prescriptions[] = [
                        'medicine_id' => $medId,
                        'quantity' => (int)$qty
                    ];
                }
            }
        }

        try {
            $this->model->recordTreatment($data, $prescriptions);
            $_SESSION['success'] = 'บันทึกการรักษาเรียบร้อยแล้ว';
            $this->redirect('/admin/health/dashboard');
        } catch (\Exception $e) {
            $_SESSION['error'] = 'เกิดข้อผิดพลาด: ' . $e->getMessage();
            $this->redirect('/admin/health/create-treatment');
        }
    }
}
