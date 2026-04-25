<?php
declare(strict_types=1);

namespace Modules\Personnel\Models;

use Core\Database;

class Department
{
    public static function getAll(): array
    {
        return Database::fetchAll("SELECT * FROM departments ORDER BY sort_order ASC");
    }

    public static function find(int $id): array|false
    {
        return Database::fetch("SELECT * FROM departments WHERE id = ?", [$id]);
    }

    public static function create(array $data): string|false
    {
        return Database::insert("INSERT INTO departments (name, description, sort_order) VALUES (?, ?, ?)", [
            $data['name'],
            $data['description'] ?? null,
            $data['sort_order'] ?? 0
        ]);
    }

    public static function update(int $id, array $data): bool
    {
        Database::query("UPDATE departments SET name = ?, description = ?, sort_order = ? WHERE id = ?", [
            $data['name'],
            $data['description'] ?? null,
            $data['sort_order'] ?? 0,
            $id
        ]);
        return true;
    }

    public static function delete(int $id): bool
    {
        // Check if there are personnel in this department
        $count = Database::fetch("SELECT COUNT(*) as count FROM personnel WHERE department_id = ?", [$id])['count'];
        if ($count > 0) {
            return false;
        }
        
        Database::query("DELETE FROM departments WHERE id = ?", [$id]);
        return true;
    }
}
