<?php
declare(strict_types=1);

namespace Modules\Complaint\Models;

use Core\Database;

class Complaint
{
    /**
     * Save a new complaint
     */
    public static function save(array $data): string|false
    {
        return Database::insert("
            INSERT INTO complaints (topic, details, attachment, contact_name, contact_info, status)
            VALUES (?, ?, ?, ?, ?, 'unread')
        ", [
            $data['topic'],
            $data['details'],
            $data['attachment'] ?? null,
            $data['contact_name'] ?? null,
            $data['contact_info'] ?? null
        ]);
    }

    /**
     * Get all complaints for admin
     */
    public static function getAll(): array
    {
        return Database::fetchAll("SELECT * FROM complaints ORDER BY created_at DESC");
    }

    /**
     * Get a single complaint by ID
     */
    public static function getById(int $id): array|false
    {
        return Database::fetch("SELECT * FROM complaints WHERE id = ?", [$id]);
    }

    /**
     * Update status and mark who read it
     */
    public static function updateStatus(int $id, string $status, ?int $userId = null): bool
    {
        if ($userId !== null) {
            // Only update read_by if it's currently null (first time read)
            Database::query("
                UPDATE complaints 
                SET status = ?, read_by = IFNULL(read_by, ?) 
                WHERE id = ?
            ", [$status, $userId, $id]);
        } else {
            Database::query("UPDATE complaints SET status = ? WHERE id = ?", [$status, $id]);
        }
        return true;
    }

    /**
     * Get unread count for badges
     */
    public static function getUnreadCount(): int
    {
        return (int)Database::fetchColumn("SELECT COUNT(*) FROM complaints WHERE status = 'unread'");
    }

    /**
     * Delete a complaint
     */
    public static function delete(int $id): bool
    {
        Database::query("DELETE FROM complaints WHERE id = ?", [$id]);
        return true;
    }
}
