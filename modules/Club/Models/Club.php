<?php
declare(strict_types=1);

namespace Modules\Club\Models;

use Core\Database;

class Club
{
    public static function getAll(): array
    {
        return Database::fetchAll("
            SELECT c.*, p.name as advisor_name 
            FROM clubs c
            LEFT JOIN personnel p ON c.advisor_id = p.id
            ORDER BY c.id DESC
        ");
    }

    public static function find(int $id): array|false
    {
        return Database::fetch("SELECT * FROM clubs WHERE id = ?", [$id]);
    }

    public static function findByAdvisor(int $advisorId): array|false
    {
        return Database::fetch("SELECT * FROM clubs WHERE advisor_id = ?", [$advisorId]);
    }

    public static function create(array $data): string|false
    {
        return Database::insert("
            INSERT INTO clubs (name, advisor_id, location, capacity, target_grades, status) 
            VALUES (?, ?, ?, ?, ?, ?)
        ", [
            $data['name'],
            $data['advisor_id'],
            $data['location'],
            $data['capacity'],
            json_encode($data['target_grades']),
            $data['status'] ?? 'open'
        ]);
    }

    public static function update(int $id, array $data): bool
    {
        Database::query("
            UPDATE clubs SET name = ?, advisor_id = ?, location = ?, capacity = ?, target_grades = ?, status = ? 
            WHERE id = ?
        ", [
            $data['name'],
            $data['advisor_id'],
            $data['location'],
            $data['capacity'],
            json_encode($data['target_grades']),
            $data['status'],
            $id
        ]);
        return true;
    }

    public static function delete(int $id): bool
    {
        Database::query("DELETE FROM clubs WHERE id = ?", [$id]);
        return true;
    }

    /**
     * Get clubs filtered by student grade level
     */
    public static function getAvailableForStudent(string $gradeLevel): array
    {
        $clubs = Database::fetchAll("
            SELECT c.*, p.name as advisor_name 
            FROM clubs c
            LEFT JOIN personnel p ON c.advisor_id = p.id
            WHERE c.status = 'open'
        ");

        return array_filter($clubs, function($club) use ($gradeLevel) {
            $grades = json_decode($club['target_grades'], true) ?: [];
            return in_array($gradeLevel, $grades);
        });
    }

    public static function updateCount(int $id): void
    {
        $count = Database::fetchColumn("SELECT COUNT(*) FROM club_members WHERE club_id = ? AND status = 'active'", [$id]);
        $club = self::find($id);
        
        $status = 'open';
        if ($count >= $club['capacity']) {
            $status = 'full';
        }

        Database::query("UPDATE clubs SET current_count = ?, status = ? WHERE id = ?", [$count, $status, $id]);
    }
}
