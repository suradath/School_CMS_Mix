<?php
declare(strict_types=1);

namespace Modules\Journal\Models;

use Core\Database;

class Journal
{
    public static function getAll(): array
    {
        return Database::fetchAll("SELECT * FROM journals ORDER BY sort_order ASC, created_at DESC");
    }

    public static function create(array $data): bool
    {
        $sql = "INSERT INTO journals (title, image_url, sort_order) VALUES (?, ?, ?)";
        return (bool)Database::query($sql, [
            $data['title'],
            $data['image_url'],
            $data['sort_order'] ?? 0
        ]);
    }

    public static function delete(int $id): bool
    {
        $item = Database::fetch("SELECT image_url FROM journals WHERE id = ?", [$id]);
        if ($item && !empty($item['image_url'])) {
            $path = ROOT_PATH . '/' . ltrim($item['image_url'], '/');
            if (file_exists($path)) {
                unlink($path);
            }
        }
        return (bool)Database::query("DELETE FROM journals WHERE id = ?", [$id]);
    }
}
