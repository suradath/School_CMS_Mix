<?php
declare(strict_types=1);

namespace Modules\Club\Models;

use Core\Database;

class ClubMember
{
    public static function getByClub(int $clubId): array
    {
        return Database::fetchAll("
            SELECT cm.*, s.student_code, s.first_name, s.last_name, s.class_level, s.room_number 
            FROM club_members cm
            JOIN students s ON cm.student_id = s.id
            WHERE cm.club_id = ? AND cm.status = 'active'
            ORDER BY s.class_level ASC, s.room_number ASC, s.student_code ASC
        ", [$clubId]);
    }

    public static function findByStudent(int $studentId): array|false
    {
        return Database::fetch("
            SELECT cm.*, c.name as club_name, c.location 
            FROM club_members cm
            JOIN clubs c ON cm.club_id = c.id
            WHERE cm.student_id = ? AND cm.status = 'active'
        ", [$studentId]);
    }

    public static function register(int $clubId, int $studentId): bool
    {
        try {
            Database::beginTransaction();

            // Row level locking for club capacity check
            $club = Database::fetch("SELECT * FROM clubs WHERE id = ? FOR UPDATE", [$clubId]);
            
            if (!$club || $club['status'] !== 'open' || $club['current_count'] >= $club['capacity']) {
                Database::rollBack();
                return false;
            }

            // Check if student already registered
            $exists = Database::fetch("SELECT id FROM club_members WHERE student_id = ? AND status = 'active'", [$studentId]);
            if ($exists) {
                Database::rollBack();
                return false;
            }

            // Insert registration
            Database::insert("INSERT INTO club_members (club_id, student_id) VALUES (?, ?)", [$clubId, $studentId]);

            // Update club count
            $newCount = $club['current_count'] + 1;
            $status = ($newCount >= $club['capacity']) ? 'full' : 'open';
            Database::query("UPDATE clubs SET current_count = ?, status = ? WHERE id = ?", [$newCount, $status, $clubId]);

            Database::commit();
            return true;
        } catch (\Exception $e) {
            Database::rollBack();
            return false;
        }
    }

    public static function saveAttendance(int $clubId, array $records, string $date): void
    {
        foreach ($records as $studentId => $status) {
            Database::query("
                INSERT INTO club_attendance (club_id, student_id, check_date, status) 
                VALUES (?, ?, ?, ?)
                ON DUPLICATE KEY UPDATE status = VALUES(status)
            ", [$clubId, $studentId, $date, $status]);
        }
    }

    public static function getAttendance(int $clubId, string $date): array
    {
        return Database::fetchAll("SELECT * FROM club_attendance WHERE club_id = ? AND check_date = ?", [$clubId, $date]);
    }

    public static function saveEvaluation(int $clubId, array $records, string $semester, int $year): void
    {
        foreach ($records as $studentId => $data) {
            Database::query("
                INSERT INTO club_evaluations (club_id, student_id, semester, academic_year, result, remarks) 
                VALUES (?, ?, ?, ?, ?, ?)
                ON DUPLICATE KEY UPDATE result = VALUES(result), remarks = VALUES(remarks)
            ", [
                $clubId, 
                $studentId, 
                $semester, 
                $year, 
                $data['result'], 
                $data['remarks'] ?? null
            ]);
        }
    }

    public static function getEvaluations(int $clubId, string $semester, int $year): array
    {
        return Database::fetchAll("
            SELECT * FROM club_evaluations 
            WHERE club_id = ? AND semester = ? AND academic_year = ?
        ", [$clubId, $semester, $year]);
    }

    public static function getAttendanceSummary(int $clubId): array
    {
        return Database::fetchAll("
            SELECT 
                s.student_code, 
                s.first_name, 
                s.last_name, 
                s.class_level, 
                s.room_number,
                COUNT(CASE WHEN ca.status = 'present' THEN 1 END) as present_count,
                COUNT(CASE WHEN ca.status = 'absent' THEN 1 END) as absent_count,
                COUNT(CASE WHEN ca.status = 'leave' THEN 1 END) as leave_count,
                COUNT(ca.id) as total_days
            FROM club_members cm
            JOIN students s ON cm.student_id = s.id
            LEFT JOIN club_attendance ca ON cm.student_id = ca.student_id AND cm.club_id = ca.club_id
            WHERE cm.club_id = ? AND cm.status = 'active'
            GROUP BY s.id
            ORDER BY s.class_level ASC, s.room_number ASC, s.student_code ASC
        ", [$clubId]);
    }

    public static function withdraw(int $studentId): bool
    {
        try {
            Database::beginTransaction();

            $reg = Database::fetch("SELECT * FROM club_members WHERE student_id = ? AND status = 'active' FOR UPDATE", [$studentId]);
            if (!$reg) {
                Database::rollBack();
                return false;
            }

            $clubId = $reg['club_id'];

            // Mark as withdrawn (or delete if you prefer, but status exists)
            Database::query("UPDATE club_members SET status = 'withdrawn' WHERE id = ?", [$reg['id']]);

            // Update club count
            $club = Database::fetch("SELECT * FROM clubs WHERE id = ? FOR UPDATE", [$clubId]);
            if ($club) {
                $newCount = max(0, $club['current_count'] - 1);
                $status = ($club['status'] === 'full') ? 'open' : $club['status'];
                Database::query("UPDATE clubs SET current_count = ?, status = ? WHERE id = ?", [$newCount, $status, $clubId]);
            }

            Database::commit();
            return true;
        } catch (\Exception $e) {
            Database::rollBack();
            return false;
        }
    }
}
