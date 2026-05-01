<?php
declare(strict_types=1);

namespace Modules\PLC\Models;

use Core\Database;

class PLC
{
    /**
     * Get all PLC groups a user belongs to
     */
    public static function getUserGroups(int $userId): array
    {
        return Database::fetchAll("
            SELECT g.*, m.role, m.status as member_status
            FROM plc_groups g
            JOIN plc_group_members m ON g.id = m.group_id
            WHERE m.user_id = ?
        ", [$userId]);
    }

    /**
     * Get all PLC groups (Admin view)
     */
    public static function getAllGroups(): array
    {
        return Database::fetchAll("
            SELECT g.*, u.full_name as creator_name,
                   (SELECT COUNT(*) FROM plc_group_members WHERE group_id = g.id) as member_count
            FROM plc_groups g
            LEFT JOIN users u ON g.created_by = u.id
            ORDER BY g.academic_year DESC, g.created_at DESC
        ");
    }

    /**
     * Get single group details
     */
    public static function getGroup(int $groupId): array|false
    {
        return Database::fetch("SELECT * FROM plc_groups WHERE id = ?", [$groupId]);
    }

    /**
     * Get group members
     */
    public static function getGroupMembers(int $groupId): array
    {
        return Database::fetchAll("
            SELECT m.*, u.full_name, u.username, p.position as position_name, d.name as department_name
            FROM plc_group_members m
            JOIN users u ON m.user_id = u.id
            LEFT JOIN personnel p ON u.personnel_id = p.id
            LEFT JOIN departments d ON p.department_id = d.id
            WHERE m.group_id = ?
        ", [$groupId]);
    }

    /**
     * Get meeting logs for a group
     */
    public static function getMeetings(int $groupId): array
    {
        return Database::fetchAll("
            SELECT m.*, u.full_name as creator_name, app.full_name as approver_name
            FROM plc_meetings m
            LEFT JOIN users u ON m.created_by = u.id
            LEFT JOIN users app ON m.approved_by = app.id
            WHERE m.group_id = ?
            ORDER BY m.date DESC, m.created_at DESC
        ", [$groupId]);
    }

    /**
     * Get total approved hours for a user in an academic year
     */
    public static function getUserTotalHours(int $userId, string $year): float
    {
        $sql = "SELECT SUM(m.hours) as total
                FROM plc_meetings m
                JOIN plc_group_members mem ON m.group_id = mem.group_id
                JOIN plc_groups g ON m.group_id = g.id
                WHERE mem.user_id = ? AND g.academic_year = ? AND m.status = 'approved'";
        $res = Database::fetch($sql, [$userId, $year]);
        return (float)($res['total'] ?? 0);
    }

    /**
     * Get summary hours for a user across all groups in a year
     */
    public static function getUserSummary(int $userId, string $year): array
    {
        $sql = "SELECT g.name as group_name, g.id as group_id, SUM(m.hours) as approved_hours,
                       (SELECT role FROM plc_group_members WHERE group_id = g.id AND user_id = ?) as role
                FROM plc_groups g
                JOIN plc_meetings m ON g.id = m.group_id
                JOIN plc_group_members mem ON g.id = mem.group_id
                WHERE mem.user_id = ? AND g.academic_year = ? AND m.status = 'approved'
                GROUP BY g.id";
        return Database::fetchAll($sql, [$userId, $userId, $year]);
    }

    /**
     * Join a group (request or direct)
     */
    public static function joinGroup(int $groupId, int $userId, string $role = 'member', string $status = 'pending'): bool
    {
        try {
            Database::query("
                INSERT INTO plc_group_members (group_id, user_id, role, status)
                VALUES (?, ?, ?, ?)
                ON DUPLICATE KEY UPDATE status = VALUES(status)
            ", [$groupId, $userId, $role, $status]);
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Approve membership
     */
    public static function updateMemberStatus(int $groupId, int $userId, string $status): bool
    {
        Database::query("UPDATE plc_group_members SET status = ? WHERE group_id = ? AND user_id = ?", [$status, $groupId, $userId]);
        return true;
    }

    /**
     * Get summary hours for ALL users in the school (Admin view)
     */
    public static function getAllUsersHoursSummary(string $year): array
    {
        return Database::fetchAll("
            SELECT u.id, u.full_name, p.position as position_name, d.name as department_name,
                   (SELECT SUM(m.hours) 
                    FROM plc_meetings m 
                    JOIN plc_group_members mem ON m.group_id = mem.group_id 
                    JOIN plc_groups g ON m.group_id = g.id
                    WHERE mem.user_id = u.id AND g.academic_year = ? AND m.status = 'approved') as total_hours
            FROM users u
            LEFT JOIN personnel p ON u.personnel_id = p.id
            LEFT JOIN departments d ON p.department_id = d.id
            WHERE u.status = 'active'
            ORDER BY total_hours DESC, u.full_name ASC
        ", [$year]);
    }

    /**
     * Update group details
     */
    public static function updateGroup(int $id, array $data): bool
    {
        return Database::query("
            UPDATE plc_groups SET name = ?, description = ?, target_goal = ?, academic_year = ?
            WHERE id = ?
        ", [$data['name'], $data['description'], $data['target_goal'], $data['academic_year'], $id]);
    }

    /**
     * Delete group
     */
    public static function deleteGroup(int $id): bool
    {
        return Database::query("DELETE FROM plc_groups WHERE id = ?", [$id]);
    }

    /**
     * Record a meeting
     */
    public static function recordMeeting(array $data): int|false
    {
        return (int)Database::insert("
            INSERT INTO plc_meetings (group_id, topic, problem_topic, solution, result, hours, date, created_by)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)
        ", [
            $data['group_id'], $data['topic'], $data['problem_topic'], $data['solution'],
            $data['result'], $data['hours'], $data['date'], $data['created_by']
        ]);
    }

    /**
     * Approve/Reject a meeting
     */
    public static function updateMeetingStatus(int $meetingId, string $status, int $approvedBy): bool
    {
        $sql = "UPDATE plc_meetings SET status = ?, approved_by = ?, approved_at = NOW() WHERE id = ?";
        Database::query($sql, [$status, $approvedBy, $meetingId]);
        return true;
    }

    /**
     * Save materials
     */
    public static function saveMaterial(int $meetingId, string $name, string $path, string $type, int $userId): bool
    {
        $sql = "INSERT INTO plc_meeting_materials (meeting_id, file_name, file_path, file_type, uploaded_by) 
                VALUES (?, ?, ?, ?, ?)";
        Database::query($sql, [$meetingId, $name, $path, $type, $userId]);
        return true;
    }

    /**
     * Get materials for a group (Material Library)
     */
    public static function getGroupMaterials(int $groupId): array
    {
        return Database::fetchAll("
            SELECT mat.*, mt.topic as meeting_topic, u.full_name as uploader_name
            FROM plc_meeting_materials mat
            JOIN plc_meetings mt ON mat.meeting_id = mt.id
            JOIN users u ON mat.uploaded_by = u.id
            WHERE mt.group_id = ?
            ORDER BY mat.uploaded_at DESC
        ", [$groupId]);
    }
}
