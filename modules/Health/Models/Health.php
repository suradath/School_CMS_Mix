<?php
declare(strict_types=1);

namespace Modules\Health\Models;

use Core\Database;

class Health
{
    /**
     * Calculate BMI and return status with color
     */
    public static function getBmiInfo(?float $weight, ?float $height): array
    {
        if (!$weight || !$height || $height <= 0) {
            return [
                'bmi' => 0,
                'status' => 'ไม่มีข้อมูล',
                'color' => 'gray',
                'bg_class' => 'bg-gray-100 text-gray-800'
            ];
        }

        $heightInMeters = $height / 100;
        $bmi = round($weight / ($heightInMeters * $heightInMeters), 2);

        if ($bmi < 18.5) {
            return [
                'bmi' => $bmi,
                'status' => 'ต่ำกว่าเกณฑ์',
                'color' => '#f59e0b', // Yellow/Orange
                'bg_class' => 'bg-amber-100 text-amber-700'
            ];
        } elseif ($bmi < 25) {
            return [
                'bmi' => $bmi,
                'status' => 'สมส่วน',
                'color' => '#10b981', // Green
                'bg_class' => 'bg-emerald-100 text-emerald-700'
            ];
        } elseif ($bmi < 30) {
            return [
                'bmi' => $bmi,
                'status' => 'น้ำหนักเกิน',
                'color' => '#f87171', // Light Red
                'bg_class' => 'bg-red-100 text-red-600'
            ];
        } else {
            return [
                'bmi' => $bmi,
                'status' => 'อ้วน',
                'color' => '#b91c1c', // Dark Red
                'bg_class' => 'bg-red-200 text-red-800'
            ];
        }
    }

    /**
     * Get overall BMI distribution for charts
     */
    public static function getDistribution(array $filters = []): array
    {
        $sql = "SELECT weight, height FROM students WHERE weight > 0 AND height > 0";
        $params = [];

        if (!empty($filters['class_level'])) {
            $sql .= " AND class_level = ?";
            $params[] = $filters['class_level'];
        }
        if (!empty($filters['room_number'])) {
            $sql .= " AND room_number = ?";
            $params[] = $filters['room_number'];
        }

        $students = Database::fetchAll($sql, $params);
        
        $stats = [
            'ต่ำกว่าเกณฑ์' => 0,
            'สมส่วน' => 0,
            'น้ำหนักเกิน' => 0,
            'อ้วน' => 0
        ];

        foreach ($students as $s) {
            $info = self::getBmiInfo((float)$s['weight'], (float)$s['height']);
            if (isset($stats[$info['status']])) {
                $stats[$info['status']]++;
            }
        }

        return $stats;
    }

    /**
     * Get comparison stats by class level
     */
    public static function getClassComparison(): array
    {
        $sql = "SELECT class_level, weight, height FROM students WHERE weight > 0 AND height > 0 ORDER BY class_level";
        $students = Database::fetchAll($sql);
        
        $levels = [];
        foreach ($students as $s) {
            $level = $s['class_level'];
            if (!isset($levels[$level])) {
                $levels[$level] = [
                    'Normal' => 0,
                    'AtRisk' => 0
                ];
            }
            
            $info = self::getBmiInfo((float)$s['weight'], (float)$s['height']);
            if ($info['status'] === 'สมส่วน') {
                $levels[$level]['Normal']++;
            } else {
                $levels[$level]['AtRisk']++;
            }
        }
        
        return $levels;
    }

    /**
     * Get students with health data for DataTables
     */
    public static function getStudentHealthList(array $filters = []): array
    {
        $sql = "SELECT id, student_code, title, first_name, last_name, class_level, room_number, weight, height 
                FROM students WHERE 1=1";
        $params = [];

        if (!empty($filters['class_level'])) {
            $sql .= " AND class_level = ?";
            $params[] = $filters['class_level'];
        }
        if (!empty($filters['room_number'])) {
            $sql .= " AND room_number = ?";
            $params[] = $filters['room_number'];
        }

        $students = Database::fetchAll($sql, $params);
        
        $result = [];
        foreach ($students as $s) {
            $bmiInfo = self::getBmiInfo((float)$s['weight'], (float)$s['height']);
            $s['bmi'] = $bmiInfo['bmi'];
            $s['status'] = $bmiInfo['status'];
            $s['status_bg'] = $bmiInfo['bg_class'];
            $result[] = $s;
        }

        return $result;
    }
}
