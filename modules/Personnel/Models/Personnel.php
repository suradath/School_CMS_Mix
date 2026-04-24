<?php
declare(strict_types=1);

namespace Modules\Personnel\Models;

use Core\Database;

class Personnel
{
    /**
     * Get all personnel by department
     */
    public static function getAllByDepartment(): array
    {
        $departments = Database::fetchAll("SELECT * FROM departments ORDER BY sort_order ASC");
        foreach ($departments as &$dept) {
            $dept['members'] = Database::fetchAll(
                "SELECT * FROM personnel WHERE department_id = ? ORDER BY sort_order ASC",
                [$dept['id']]
            );
        }
        return $departments;
    }

    /**
     * Get single personnel
     */
    public static function find(int $id): array|false
    {
        return Database::fetch("SELECT * FROM personnel WHERE id = ?", [$id]);
    }

    /**
     * Create new personnel
     */
    public static function create(array $data): string|false
    {
        return Database::insert(
            "INSERT INTO personnel (name, position, department_id, image_url, email, phone, bio, sort_order) 
             VALUES (?, ?, ?, ?, ?, ?, ?, ?)",
            [
                $data['name'], 
                $data['position'], 
                $data['department_id'], 
                $data['image_url'], 
                $data['email'], 
                $data['phone'], 
                $data['bio'], 
                $data['sort_order'] ?? 0
            ]
        );
    }

    /**
     * Update personnel
     */
    public static function update(int $id, array $data): bool
    {
        Database::query(
            "UPDATE personnel SET name = ?, position = ?, department_id = ?, image_url = ?, email = ?, phone = ?, bio = ?, sort_order = ? 
             WHERE id = ?",
            [
                $data['name'], 
                $data['position'], 
                $data['department_id'], 
                $data['image_url'], 
                $data['email'], 
                $data['phone'], 
                $data['bio'], 
                $data['sort_order'],
                $id
            ]
        );
        return true;
    }

    /**
     * Delete personnel
     */
    public static function delete(int $id): bool
    {
        // Get image URL first to delete file
        $person = self::find($id);
        if ($person && !empty($person['image_url'])) {
            $filePath = ROOT_PATH . '/' . ltrim($person['image_url'], '/');
            if (file_exists($filePath)) {
                unlink($filePath);
            }
        }
        
        Database::query("DELETE FROM personnel WHERE id = ?", [$id]);
        return true;
    }
}
