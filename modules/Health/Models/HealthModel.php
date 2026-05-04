<?php
declare(strict_types=1);

namespace Modules\Health\Models;

use Core\Database;
use PDO;
use Exception;

class HealthModel
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    /**
     * Get students by class and room for CSV export
     */
    public function getStudentsForExport(string $classLevel, int $roomNumber): array
    {
        $sql = "SELECT student_code, first_name, last_name, weight, height 
                FROM students 
                WHERE class_level = ? AND room_number = ? 
                ORDER BY student_code ASC";
        return Database::fetchAll($sql, [$classLevel, $roomNumber]);
    }

    /**
     * Update student weight and height from CSV
     */
    public function updateBMI(string $studentCode, float $weight, float $height): bool
    {
        $sql = "UPDATE students SET weight = ?, height = ? WHERE student_code = ?";
        return Database::query($sql, [$weight, $height, $studentCode])->rowCount() > 0;
    }

    /**
     * Get medicines stock list
     */
    public function getMedicines(): array
    {
        return Database::fetchAll("SELECT * FROM medicines ORDER BY name ASC");
    }

    /**
     * Get low stock medicines
     */
    public function getLowStockMedicines(): array
    {
        return Database::fetchAll("SELECT * FROM medicines WHERE stock_quantity <= min_stock_level ORDER BY stock_quantity ASC");
    }

    /**
     * Record a treatment and dispense medicine using Transaction
     */
    public function recordTreatment(array $data, array $prescriptions): bool
    {
        $this->db->beginTransaction();
        try {
            // 1. Insert treatment log
            $sql = "INSERT INTO health_records (student_id, symptoms, treatment, is_referral, referral_hospital, referral_reason, created_by) 
                    VALUES (:student_id, :symptoms, :treatment, :is_referral, :referral_hospital, :referral_reason, :created_by)";
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute([
                'student_id' => $data['student_id'],
                'symptoms' => $data['symptoms'],
                'treatment' => $data['treatment'] ?? null,
                'is_referral' => $data['is_referral'] ? 1 : 0,
                'referral_hospital' => $data['referral_hospital'] ?? null,
                'referral_reason' => $data['referral_reason'] ?? null,
                'created_by' => $data['created_by']
            ]);
            
            $recordId = (int)$this->db->lastInsertId();

            // 2. Handle prescriptions and stock deduction
            foreach ($prescriptions as $p) {
                $medicineId = (int)$p['medicine_id'];
                $qty = (int)$p['quantity'];

                if ($qty <= 0) continue;

                // Check stock availability
                $med = Database::fetch("SELECT stock_quantity FROM medicines WHERE id = ? FOR UPDATE", [$medicineId]);
                if (!$med || $med['stock_quantity'] < $qty) {
                    throw new Exception("ยามีไม่เพียงพอในสต๊อก");
                }

                // Insert prescription
                $sqlP = "INSERT INTO health_prescriptions (record_id, medicine_id, quantity) VALUES (?, ?, ?)";
                $this->db->prepare($sqlP)->execute([$recordId, $medicineId, $qty]);

                // Deduct stock
                $sqlU = "UPDATE medicines SET stock_quantity = stock_quantity - ? WHERE id = ?";
                $this->db->prepare($sqlU)->execute([$qty, $medicineId]);
            }

            $this->db->commit();
            return true;
        } catch (Exception $e) {
            $this->db->rollBack();
            throw $e;
        }
    }

    /**
     * Get treatment history for dashboard
     */
    public function getStats(string $period = 'daily'): array
    {
        $dateFilter = $period === 'daily' ? 'DATE(created_at) = CURRENT_DATE' : 'MONTH(created_at) = MONTH(CURRENT_DATE) AND YEAR(created_at) = YEAR(CURRENT_DATE)';
        
        return [
            'total_visits' => Database::fetch("SELECT COUNT(*) as count FROM health_records WHERE $dateFilter")['count'],
            'referrals' => Database::fetch("SELECT COUNT(*) as count FROM health_records WHERE is_referral = 1 AND $dateFilter")['count'],
            'medicines_dispensed' => Database::fetch("SELECT SUM(quantity) as count FROM health_prescriptions hp JOIN health_records hr ON hp.record_id = hr.id WHERE $dateFilter")['count'] ?? 0
        ];
    }

    public function getRecentRecords(int $limit = 10): array
    {
        $sql = "SELECT r.*, s.first_name, s.last_name, s.student_code, s.class_level, s.room_number 
                FROM health_records r 
                JOIN students s ON r.student_id = s.id 
                ORDER BY r.created_at DESC LIMIT $limit";
        return Database::fetchAll($sql);
    }
    
    public function updateStudentHealthInfo(int $studentId, ?string $chronic, ?string $allergy): bool
    {
        $sql = "UPDATE students SET chronic_disease = ?, medication_allergy = ? WHERE id = ?";
        return Database::query($sql, [$chronic, $allergy, $studentId])->rowCount() > 0;
    }
}
