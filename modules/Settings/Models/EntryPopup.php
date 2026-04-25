<?php
declare(strict_types=1);

namespace Modules\Settings\Models;

use Core\Database;

class EntryPopup
{
    public static function getAll(): array
    {
        return Database::fetchAll("SELECT * FROM entry_popups ORDER BY created_at DESC");
    }

    public static function getActive(): array|false
    {
        return Database::fetch("SELECT * FROM entry_popups WHERE is_active = 1 ORDER BY created_at DESC LIMIT 1");
    }

    public static function find(int $id): array|false
    {
        return Database::fetch("SELECT * FROM entry_popups WHERE id = ?", [$id]);
    }

    public static function create(array $data): string|false
    {
        return Database::insert("INSERT INTO entry_popups (title, image_url, link_url, is_active) VALUES (?, ?, ?, ?)", [
            $data['title'],
            $data['image_url'],
            $data['link_url'] ?? null,
            $data['is_active'] ?? 1
        ]);
    }

    public static function update(int $id, array $data): bool
    {
        Database::query("UPDATE entry_popups SET title = ?, image_url = ?, link_url = ?, is_active = ? WHERE id = ?", [
            $data['title'],
            $data['image_url'],
            $data['link_url'] ?? null,
            $data['is_active'] ?? 1,
            $id
        ]);
        return true;
    }

    public static function delete(int $id): bool
    {
        Database::query("DELETE FROM entry_popups WHERE id = ?", [$id]);
        return true;
    }
}
