<?php
declare(strict_types=1);

namespace Modules\Discipline\Models;

use Core\Database;

class Discipline
{
    /**
     * Get all behavior categories
     */
    public static function getCategories(): array
    {
        return Database::fetchAll("SELECT * FROM discipline_categories ORDER BY type DESC, points DESC");
    }

    /**
     * Save or Update a category
     */
    public static function saveCategory(array $data): bool
    {
        if (isset($data['id']) && $data['id'] > 0) {
            $sql = "UPDATE discipline_categories SET name = ?, points = ?, type = ? WHERE id = ?";
            Database::query($sql, [$data['name'], $data['points'], $data['type'], $data['id']]);
        } else {
            $sql = "INSERT INTO discipline_categories (name, points, type) VALUES (?, ?, ?)";
            Database::query($sql, [$data['name'], $data['points'], $data['type']]);
        }
        return true;
    }

    /**
     * Delete a category
     */
    public static function deleteCategory(int $id): bool
    {
        Database::query("DELETE FROM discipline_categories WHERE id = ?", [$id]);
        return true;
    }

    /**
     * Get all students with their current total discipline score
     */
    public static function getStudentsWithScores(string $class = '', int $room = 0): array
    {
        $params = [];
        $whereClause = "";
        
        if ($class) {
            $whereClause .= " AND s.class_level = ?";
            $params[] = $class;
        }
        if ($room > 0) {
            $whereClause .= " AND s.room_number = ?";
            $params[] = $room;
        }

        $sql = "SELECT s.id, s.student_code, s.title, s.first_name, s.last_name, s.class_level, s.room_number,
                       COALESCE(SUM(l.points_affected), 0) as total_score
                FROM students s
                LEFT JOIN student_discipline_logs l ON s.id = l.student_id AND l.deleted_at IS NULL
                WHERE 1=1 $whereClause
                GROUP BY s.id
                ORDER BY s.class_level ASC, s.room_number ASC, s.student_code ASC";
        
        return Database::fetchAll($sql, $params);
    }

    /**
     * Get single student score summary
     */
    public static function getStudentSummary(int $studentId): array
    {
        $sql = "SELECT 
                    COALESCE(SUM(CASE WHEN points_affected > 0 THEN points_affected ELSE 0 END), 0) as positive_points,
                    COALESCE(SUM(CASE WHEN points_affected < 0 THEN points_affected ELSE 0 END), 0) as negative_points,
                    COALESCE(SUM(points_affected), 0) as total_score
                FROM student_discipline_logs 
                WHERE student_id = ? AND deleted_at IS NULL";
        return Database::fetch($sql, [$studentId]);
    }

    /**
     * Get behavior logs for a specific student
     */
    public static function getStudentLogs(int $studentId): array
    {
        $sql = "SELECT l.*, c.name as category_name, u.full_name as recorder_name
                FROM student_discipline_logs l
                LEFT JOIN discipline_categories c ON l.category_id = c.id
                LEFT JOIN users u ON l.created_by = u.id
                WHERE l.student_id = ? AND l.deleted_at IS NULL
                ORDER BY l.created_at DESC";
        return Database::fetchAll($sql, [$studentId]);
    }

    /**
     * Manual record behavior
     */
    public static function recordBehavior(int $studentId, int $categoryId, string $remarks, int $createdBy, ?string $date = null): bool
    {
        $category = Database::fetch("SELECT points FROM discipline_categories WHERE id = ?", [$categoryId]);
        if (!$category) return false;

        $createdAt = $date ? $date . ' ' . date('H:i:s') : date('Y-m-d H:i:s');

        $sql = "INSERT INTO student_discipline_logs (student_id, category_id, points_affected, remarks, created_by, created_at) 
                VALUES (?, ?, ?, ?, ?, ?)";
        Database::query($sql, [$studentId, $categoryId, $category['points'], $remarks, $createdBy, $createdAt]);
        return true;
    }

    /**
     * Delete log (Soft Delete)
     */
    public static function deleteLog(int $logId, int $userId): bool
    {
        $sql = "UPDATE student_discipline_logs SET deleted_at = CURRENT_TIMESTAMP, deleted_by = ? WHERE id = ?";
        Database::query($sql, [$userId, $logId]);
        return true;
    }

    /**
     * Auto-Deduction Logic (Daily Rule Check)
     */
    public static function triggerAutoDeduction(int $studentId, string $attendanceStatus, string $date): bool
    {
        // Map status to category ID (as inserted in migration)
        $categoryId = match($attendanceStatus) {
            'late' => 1,
            'absent' => 2,
            default => null
        };

        if (!$categoryId) return false;

        try {
            $pdo = Database::getInstance();
            // We use the existing transaction if one is already started by saveAttendance
            $isInternalTransaction = false;
            if (!$pdo->inTransaction()) {
                $pdo->beginTransaction();
                $isInternalTransaction = true;
            }

            // 1. Check if today already logged for this student & category
            $checkSql = "SELECT COUNT(*) FROM student_discipline_logs 
                         WHERE student_id = ? AND category_id = ? AND DATE(created_at) = ?";
            $alreadyLogged = (int)Database::fetchColumn($checkSql, [$studentId, $categoryId, $date]);

            if ($alreadyLogged > 0) {
                if ($isInternalTransaction) $pdo->rollBack();
                return true;
            }

            // 2. Get category points
            $category = Database::fetch("SELECT points FROM discipline_categories WHERE id = ?", [$categoryId]);
            $points = $category['points'] ?? 0;

            // 3. Insert Log
            $sql = "INSERT INTO student_discipline_logs (student_id, category_id, points_affected, remarks, is_auto) 
                    VALUES (?, ?, ?, ?, 1)";
            Database::query($sql, [$studentId, $categoryId, $points, "หักคะแนนอัตโนมัติจากระบบเช็คชื่อ ($attendanceStatus)"]);

            if ($isInternalTransaction) $pdo->commit();
            return true;
        } catch (\Exception $e) {
            if (isset($pdo) && $pdo->inTransaction()) $pdo->rollBack();
            throw $e;
        }
    }
}
