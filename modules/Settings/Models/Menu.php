<?php
declare(strict_types=1);

namespace Modules\Settings\Models;

use Core\Database;

class Menu
{
    public static function getAllActive(): array
    {
        $menus = Database::fetchAll(
            "SELECT * FROM menus WHERE is_active = 1 AND parent_id IS NULL ORDER BY sort_order ASC"
        );
        
        foreach ($menus as &$menu) {
            $menu['children'] = Database::fetchAll(
                "SELECT * FROM menus WHERE is_active = 1 AND parent_id = ? ORDER BY sort_order ASC",
                [$menu['id']]
            );
        }
        
        return $menus;
    }

    public static function getParents(): array
    {
        return Database::fetchAll(
            "SELECT * FROM menus WHERE parent_id IS NULL ORDER BY sort_order ASC"
        );
    }

    public static function getAll(): array
    {
        return Database::fetchAll(
            "SELECT * FROM menus ORDER BY sort_order ASC"
        );
    }

    public static function find(int $id): array|false
    {
        return Database::fetch("SELECT * FROM menus WHERE id = ?", [$id]);
    }

    public static function create(array $data): string|false
    {
        return Database::insert(
            "INSERT INTO menus (title, url, icon, parent_id, sort_order, is_active) VALUES (?, ?, ?, ?, ?, ?)",
            [
                $data['title'],
                $data['url'],
                $data['icon'] ?? '',
                !empty($data['parent_id']) ? (int)$data['parent_id'] : null,
                $data['sort_order'] ?? 0,
                $data['is_active'] ?? 1
            ]
        );
    }

    public static function update(int $id, array $data): bool
    {
        Database::query(
            "UPDATE menus SET title = ?, url = ?, icon = ?, parent_id = ?, sort_order = ?, is_active = ? WHERE id = ?",
            [
                $data['title'],
                $data['url'],
                $data['icon'] ?? '',
                !empty($data['parent_id']) ? (int)$data['parent_id'] : null,
                $data['sort_order'] ?? 0,
                $data['is_active'] ?? 1,
                $id
            ]
        );
        return true;
    }

    public static function delete(int $id): bool
    {
        Database::query("DELETE FROM menus WHERE id = ?", [$id]);
        return true;
    }
}
