<?php
declare(strict_types=1);

namespace Modules\Students\Controllers;

use Core\Controller;
use Modules\Students\Models\Student;

class ImportController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        // Only admin and director can import data
        $this->requireRole(['admin', 'director']);
    }

    /**
     * Show Import Form
     */
    public function index(): void
    {
        $this->renderWithLayout('Students.Views.import', 'themes.admin.layout', [
            'title' => 'นำเข้าข้อมูลนักเรียน (DMC CSV)'
        ]);
    }

    /**
     * Process CSV Import
     */
    public function process(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || empty($_FILES['csv_file'])) {
            $this->redirect('/students/import');
        }

        $file = $_FILES['csv_file'];
        
        if ($file['error'] !== UPLOAD_ERR_OK) {
            $_SESSION['error'] = "เกิดข้อผิดพลาดในการอัปโหลดไฟล์";
            $this->redirect('/students/import');
        }

        $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        if ($ext !== 'csv') {
            $_SESSION['error'] = "กรุณาอัปโหลดไฟล์นามสกุล .csv เท่านั้น";
            $this->redirect('/students/import');
        }

        $handle = fopen($file['tmp_name'], 'r');
        if ($handle !== FALSE) {
            // Assume first row is header
            $header = fgetcsv($handle, 1000, ",");
            
            // Map DMC headers to our array keys (simplified for this example, assuming strict column order or mapping by index)
            // For a robust system, we should map by column name. We'll assume a standard DMC CSV structure.
            /* Expected columns roughly:
               0: รหัสโรงเรียน
               1: เลขประจำตัวประชาชน
               2: เลขประจำตัวนักเรียน
               3: คำนำหน้าชื่อ
               4: ชื่อ
               5: นามสกุล
               6: เพศ
               7: ชั้นเรียน
               8: ห้องเรียน
               9: วันเกิด
               10: กลุ่มเลือด
               ... and so on
            */
            
            $successCount = 0;
            $errorCount = 0;
            
            // Skip first row (header)
            $isFirstRow = true;

            while (($row = fgetcsv($handle, 1000, ",")) !== FALSE) {
                if ($isFirstRow) {
                    $isFirstRow = false;
                    continue;
                }

                // Helper to clean data (convert '-' to null)
                $clean = function($index) use ($row) {
                    $val = trim($row[$index] ?? '');
                    return ($val === '-' || $val === '') ? null : $val;
                };

                // Skip empty rows (no citizen ID or student code)
                if (empty($clean(2)) && empty($clean(5))) continue;

                // Parse Birth Date safely
                $birthDateStr = $clean(10);
                $birthDate = null;
                if (!empty($birthDateStr)) {
                    $birthDateStr = str_replace('/', '-', $birthDateStr);
                    $parts = explode('-', $birthDateStr);
                    if (count($parts) === 3) {
                        $p1 = (int)$parts[0];
                        $p2 = (int)$parts[1];
                        $p3 = (int)$parts[2];
                        // If year is first (YYYY-MM-DD)
                        if ($p1 > 1000) {
                            $y = $p1 > 2500 ? $p1 - 543 : $p1;
                            $birthDate = sprintf('%04d-%02d-%02d', $y, $p2, $p3);
                        } else {
                            // DD-MM-YYYY
                            $y = $p3 > 2500 ? $p3 - 543 : $p3;
                            $birthDate = sprintf('%04d-%02d-%02d', $y, $p2, $p1);
                        }
                    } else {
                        $ts = strtotime($birthDateStr);
                        if ($ts !== false) $birthDate = date('Y-m-d', $ts);
                    }
                }

                try {
                    $fName = trim(($clean(28) ?? '') . ' ' . ($clean(29) ?? ''));
                    $mName = trim(($clean(31) ?? '') . ' ' . ($clean(32) ?? ''));
                    $gName = trim(($clean(24) ?? '') . ' ' . ($clean(25) ?? ''));

                    $studentData = [
                        'school_id' => $clean(0),
                        'citizen_id' => $clean(2),
                        'student_code' => $clean(5),
                        'title' => $clean(7),
                        'first_name' => $clean(8),
                        'last_name' => $clean(9),
                        'gender' => $clean(6),
                        'class_level' => $clean(3),
                        'room_number' => (int)($clean(4) ?? 0),
                        'birth_date' => $birthDate,
                        'weight' => $clean(12) !== null ? (float)$clean(12) : null,
                        'height' => $clean(13) !== null ? (float)$clean(13) : null,
                        'blood_type' => $clean(14),
                        'religion' => $clean(15),
                        'ethnicity' => $clean(16),
                        'nationality' => $clean(17),
                        'disadvantage_status' => $clean(34),
                        
                        'address' => [
                            'address_no' => $clean(18),
                            'moo' => $clean(19),
                            'soi_road' => $clean(20),
                            'sub_district' => $clean(21),
                            'district' => $clean(22),
                            'province' => $clean(23),
                        ],
                        
                        'parents' => [
                            'father_name' => $fName === '' ? null : $fName,
                            'father_occupation' => $clean(30),
                            'mother_name' => $mName === '' ? null : $mName,
                            'mother_occupation' => $clean(33),
                            'guardian_name' => $gName === '' ? null : $gName,
                            'guardian_occupation' => $clean(26),
                            'guardian_relation' => $clean(27),
                        ]
                    ];

                    Student::insertOrUpdate($studentData);
                    $successCount++;
                } catch (\Exception $e) {
                    $errorCount++;
                    // Log error if needed
                }
            }
            fclose($handle);
            
            $_SESSION['success'] = "นำเข้าข้อมูลสำเร็จ {$successCount} รายการ, ผิดพลาด {$errorCount} รายการ";
        } else {
            $_SESSION['error'] = "ไม่สามารถอ่านไฟล์ CSV ได้";
        }

        $this->redirect('/students');
    }
}
