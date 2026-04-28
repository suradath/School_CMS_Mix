<?php
declare(strict_types=1);

namespace Modules\Students\Models;

use Core\Database;

class Student
{
    /**
     * Get dashboard statistics
     */
    public static function getDashboardStats(): array
    {
        $stats = [
            'total' => 0,
            'gender' => ['Male' => 0, 'Female' => 0],
            'classes' => []
        ];

        // Total
        $totalRow = Database::fetch("SELECT COUNT(id) as count FROM students");
        $stats['total'] = $totalRow['count'] ?? 0;

        // Gender split
        $genders = Database::fetchAll("SELECT gender, COUNT(id) as count FROM students GROUP BY gender");
        foreach ($genders as $g) {
            if ($g['gender'] === 'ช') $stats['gender']['Male'] = $g['count'];
            if ($g['gender'] === 'ญ') $stats['gender']['Female'] = $g['count'];
        }

        // Class breakdown
        $classes = Database::fetchAll("SELECT class_level, COUNT(id) as count FROM students GROUP BY class_level ORDER BY class_level");
        foreach ($classes as $c) {
            $stats['classes'][$c['class_level']] = $c['count'];
        }

        // Blood Type
        $stats['blood_type'] = [];
        $bloodTypes = Database::fetchAll("SELECT blood_type, COUNT(id) as count FROM students WHERE blood_type IS NOT NULL AND blood_type != '' GROUP BY blood_type");
        foreach ($bloodTypes as $b) {
            $stats['blood_type'][$b['blood_type']] = $b['count'];
        }

        // Religion
        $stats['religion'] = [];
        $religions = Database::fetchAll("SELECT religion, COUNT(id) as count FROM students WHERE religion IS NOT NULL AND religion != '' GROUP BY religion");
        foreach ($religions as $r) {
            $stats['religion'][$r['religion']] = $r['count'];
        }

        // Nationality
        $stats['nationality'] = [];
        $nationalities = Database::fetchAll("SELECT nationality, COUNT(id) as count FROM students WHERE nationality IS NOT NULL AND nationality != '' GROUP BY nationality");
        foreach ($nationalities as $n) {
            $stats['nationality'][$n['nationality']] = $n['count'];
        }

        // Parent Occupations (Exclusively from guardian_occupation)
        $stats['parent_occupations'] = [];
        $occupations = Database::fetchAll("
            SELECT guardian_occupation as occupation, COUNT(*) as count 
            FROM student_parents 
            WHERE guardian_occupation IS NOT NULL AND guardian_occupation != '' AND guardian_occupation != '-'
            GROUP BY guardian_occupation 
            ORDER BY count DESC 
            LIMIT 10
        ");
        foreach ($occupations as $o) {
            $stats['parent_occupations'][$o['occupation']] = $o['count'];
        }

        // Disadvantage Status
        $stats['disadvantage'] = [];
        $disadvantages = Database::fetchAll("SELECT disadvantage_status, COUNT(id) as count FROM students WHERE disadvantage_status IS NOT NULL AND disadvantage_status != '' GROUP BY disadvantage_status");
        foreach ($disadvantages as $d) {
            $stats['disadvantage'][$d['disadvantage_status']] = $d['count'];
        }

        // Location Stats (Grouping by Address, excluding moo)
        $stats['locations'] = Database::fetchAll("
            SELECT province, district, sub_district, COUNT(student_id) as student_count 
            FROM student_address 
            GROUP BY province, district, sub_district 
            ORDER BY student_count DESC, province, district, sub_district
        ");

        return $stats;
    }

    /**
     * Get all students with optional filtering
     */
    public static function getAll(array $filters = []): array
    {
        $sql = "SELECT * FROM students WHERE 1=1";
        $params = [];

        if (!empty($filters['class_level'])) {
            $sql .= " AND class_level = ?";
            $params[] = $filters['class_level'];
        }
        if (!empty($filters['room_number'])) {
            $sql .= " AND room_number = ?";
            $params[] = $filters['room_number'];
        }
        if (!empty($filters['search'])) {
            $sql .= " AND (first_name LIKE ? OR last_name LIKE ? OR student_code LIKE ?)";
            $searchTerm = '%' . $filters['search'] . '%';
            $params[] = $searchTerm;
            $params[] = $searchTerm;
            $params[] = $searchTerm;
        }

        $sql .= " ORDER BY class_level ASC, room_number ASC, first_name ASC";

        return Database::fetchAll($sql, $params);
    }

    /**
     * Get unique class levels for filtering dropdowns
     */
    public static function getUniqueClasses(): array
    {
        return Database::fetchAll("SELECT DISTINCT class_level FROM students WHERE class_level IS NOT NULL ORDER BY class_level");
    }

    /**
     * Get single student with address and parents details
     */
    public static function findWithDetails(int $id): array|false
    {
        $student = Database::fetch("SELECT * FROM students WHERE id = ?", [$id]);
        if (!$student) return false;

        $student['address'] = Database::fetch("SELECT * FROM student_address WHERE student_id = ?", [$id]);
        $student['parents'] = Database::fetch("SELECT * FROM student_parents WHERE student_id = ?", [$id]);

        return $student;
    }

    /**
     * Insert or update a student record (Used during import)
     * Duplicate check by citizen_id or student_code
     */
    public static function insertOrUpdate(array $data): bool
    {
        try {
            Database::getInstance()->beginTransaction();

            // 1. Check if exists
            $existing = null;
            if (!empty($data['citizen_id'])) {
                $existing = Database::fetch("SELECT id FROM students WHERE citizen_id = ?", [$data['citizen_id']]);
            }
            if (!$existing && !empty($data['student_code'])) {
                $existing = Database::fetch("SELECT id FROM students WHERE student_code = ?", [$data['student_code']]);
            }

            $studentId = $existing ? $existing['id'] : null;

            // Prepare student core data
            $studentData = [
                $data['school_id'] ?? null,
                $data['citizen_id'] ?? null,
                $data['student_code'] ?? null,
                $data['title'] ?? null,
                $data['first_name'] ?? null,
                $data['last_name'] ?? null,
                $data['gender'] ?? null,
                $data['class_level'] ?? null,
                $data['room_number'] ?? null,
                $data['birth_date'] ?? null,
                $data['blood_type'] ?? null,
                $data['religion'] ?? null,
                $data['ethnicity'] ?? null,
                $data['nationality'] ?? null,
                $data['weight'] ?? null,
                $data['height'] ?? null,
                $data['disadvantage_status'] ?? null
            ];

            if ($studentId) {
                // Update existing
                $studentData[] = $studentId;
                Database::query("UPDATE students SET 
                    school_id=?, citizen_id=?, student_code=?, title=?, first_name=?, last_name=?, gender=?, 
                    class_level=?, room_number=?, birth_date=?, blood_type=?, religion=?, ethnicity=?, nationality=?, 
                    weight=?, height=?, disadvantage_status=?
                    WHERE id=?", $studentData);
            } else {
                // Insert new
                Database::query("INSERT INTO students (
                    school_id, citizen_id, student_code, title, first_name, last_name, gender, 
                    class_level, room_number, birth_date, blood_type, religion, ethnicity, nationality, 
                    weight, height, disadvantage_status
                ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)", $studentData);
                $studentId = Database::getInstance()->lastInsertId();
            }

            // 2. Handle Address
            if (!empty($data['address'])) {
                $addr = $data['address'];
                $addrExists = Database::fetch("SELECT student_id FROM student_address WHERE student_id = ?", [$studentId]);
                if ($addrExists) {
                    Database::query("UPDATE student_address SET address_no=?, moo=?, soi_road=?, sub_district=?, district=?, province=? WHERE student_id=?", 
                        [$addr['address_no'], $addr['moo'], $addr['soi_road'], $addr['sub_district'], $addr['district'], $addr['province'], $studentId]
                    );
                } else {
                    Database::query("INSERT INTO student_address (student_id, address_no, moo, soi_road, sub_district, district, province) VALUES (?, ?, ?, ?, ?, ?, ?)", 
                        [$studentId, $addr['address_no'], $addr['moo'], $addr['soi_road'], $addr['sub_district'], $addr['district'], $addr['province']]
                    );
                }
            }

            // 3. Handle Parents
            if (!empty($data['parents'])) {
                $par = $data['parents'];
                $parExists = Database::fetch("SELECT student_id FROM student_parents WHERE student_id = ?", [$studentId]);
                if ($parExists) {
                    Database::query("UPDATE student_parents SET father_name=?, father_occupation=?, mother_name=?, mother_occupation=?, guardian_name=?, guardian_occupation=?, guardian_relation=? WHERE student_id=?", 
                        [$par['father_name'], $par['father_occupation'], $par['mother_name'], $par['mother_occupation'], $par['guardian_name'], $par['guardian_occupation'], $par['guardian_relation'], $studentId]
                    );
                } else {
                    Database::query("INSERT INTO student_parents (student_id, father_name, father_occupation, mother_name, mother_occupation, guardian_name, guardian_occupation, guardian_relation) VALUES (?, ?, ?, ?, ?, ?, ?, ?)", 
                        [$studentId, $par['father_name'], $par['father_occupation'], $par['mother_name'], $par['mother_occupation'], $par['guardian_name'], $par['guardian_occupation'], $par['guardian_relation']]
                    );
                }
            }

            Database::getInstance()->commit();
            return true;
        } catch (\Exception $e) {
            Database::getInstance()->rollBack();
            throw $e;
        }
    }

    /**
     * Clear all student data (Truncate tables)
     */
    public static function clearAllData(): bool
    {
        try {
            Database::getInstance()->beginTransaction();
            
            // Truncate ignores foreign key constraints if we disable them temporarily
            Database::query("SET FOREIGN_KEY_CHECKS = 0");
            Database::query("TRUNCATE TABLE student_address");
            Database::query("TRUNCATE TABLE student_parents");
            Database::query("TRUNCATE TABLE students");
            Database::query("SET FOREIGN_KEY_CHECKS = 1");

            Database::getInstance()->commit();
            return true;
        } catch (\Exception $e) {
            Database::getInstance()->rollBack();
            Database::query("SET FOREIGN_KEY_CHECKS = 1");
            return false;
        }
    }
}
