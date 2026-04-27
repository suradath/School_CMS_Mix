<?php
declare(strict_types=1);

namespace Modules\Leave\Models;

use Core\Database;
use Core\DateHelper;

class LeaveRequest
{
    public static function getAll(array $filters = []): array
    {
        $sql = "SELECT lr.*, lt.name as leave_type_name, lt.color as leave_type_color, p.name as personnel_name, p.image_url, d.name as department_name 
                FROM leave_requests lr
                JOIN leave_types lt ON lr.leave_type_id = lt.id
                JOIN personnel p ON lr.personnel_id = p.id
                JOIN departments d ON p.department_id = d.id";
        
        $params = [];
        $where = [];

        if (isset($filters['personnel_id'])) {
            $where[] = "lr.personnel_id = ?";
            $params[] = $filters['personnel_id'];
        }

        if (isset($filters['department_id'])) {
            $where[] = "p.department_id = ?";
            $params[] = $filters['department_id'];
        }

        if (isset($filters['status'])) {
            $where[] = "lr.status = ?";
            $params[] = $filters['status'];
        }

        if (!empty($where)) {
            $sql .= " WHERE " . implode(" AND ", $where);
        }

        $sql .= " ORDER BY lr.created_at DESC";

        return Database::fetchAll($sql, $params);
    }

    public static function find(int $id): ?array
    {
        $sql = "SELECT lr.*, lt.name as leave_type_name, lt.slug as leave_type_slug, p.name as personnel_name, p.department_id
                FROM leave_requests lr
                JOIN leave_types lt ON lr.leave_type_id = lt.id
                JOIN personnel p ON lr.personnel_id = p.id
                WHERE lr.id = ?";
        $result = Database::fetch($sql, [$id]);
        return $result ?: null;
    }

    public static function create(array $data): bool
    {
        $sql = "INSERT INTO leave_requests (personnel_id, leave_type_id, start_date, end_date, total_days, reason, attachment_url, status) 
                VALUES (?, ?, ?, ?, ?, ?, ?, 'pending')";
        
        return (bool)Database::query($sql, [
            $data['personnel_id'],
            $data['leave_type_id'],
            $data['start_date'],
            $data['end_date'],
            $data['total_days'],
            $data['reason'],
            $data['attachment_url'] ?? null
        ]);
    }

    public static function updateStatus(int $id, string $status, array $comments = [], int $approvedBy = null): bool
    {
        $sql = "UPDATE leave_requests SET status = ?, dept_head_comment = ?, admin_comment = ?, approved_by = ? WHERE id = ?";
        return (bool)Database::query($sql, [
            $status,
            $comments['dept_head_comment'] ?? null,
            $comments['admin_comment'] ?? null,
            $approvedBy,
            $id
        ]);
    }

    public static function hasOverlap(int $personnelId, string $start, string $end, int $excludeId = null): bool
    {
        $sql = "SELECT * FROM leave_requests WHERE personnel_id = ? AND status != 'cancelled' AND status != 'rejected'";
        if ($excludeId) {
            $sql .= " AND id != " . $excludeId;
        }
        
        $requests = Database::fetchAll($sql, [$personnelId]);
        
        foreach ($requests as $req) {
            if (DateHelper::isOverlapping($start, $end, $req['start_date'], $req['end_date'])) {
                return true;
            }
        }
        
        return false;
    }

    public static function getQuotaStats(int $personnelId, int $year): array
    {
        $types = LeaveType::getAll();
        $stats = [];

        foreach ($types as $type) {
            // Get override quota if exists
            $quota = Database::fetch("SELECT quota FROM personnel_leave_quotas WHERE personnel_id = ? AND leave_type_id = ? AND year = ?", [
                $personnelId, $type['id'], $year
            ]);
            
            $totalQuota = $quota ? (int)$quota['quota'] : (int)$type['default_quota'];
            
            // Get used days
            $used = Database::fetch("SELECT SUM(total_days) as used FROM leave_requests 
                                     WHERE personnel_id = ? AND leave_type_id = ? AND status = 'approved' 
                                     AND YEAR(start_date) = ?", [
                $personnelId, $type['id'], $year
            ]);
            
            $usedDays = $used ? (float)$used['used'] : 0.0;
            
            $stats[] = [
                'type_id' => $type['id'],
                'name' => $type['name'],
                'slug' => $type['slug'],
                'color' => $type['color'],
                'quota' => $totalQuota,
                'used' => $usedDays,
                'remaining' => max(0, $totalQuota - $usedDays)
            ];
        }

        return $stats;
    }
}
