<?php
declare(strict_types=1);

namespace Modules\Leave\Models;

use Core\Database;

class LeaveType
{
    public static function getAll(): array
    {
        return Database::fetchAll("SELECT * FROM leave_types ORDER BY id ASC");
    }

    public static function find(int $id): ?array
    {
        $result = Database::fetch("SELECT * FROM leave_types WHERE id = ?", [$id]);
        return $result ?: null;
    }

    public static function findBySlug(string $slug): ?array
    {
        $result = Database::fetch("SELECT * FROM leave_types WHERE slug = ?", [$slug]);
        return $result ?: null;
    }
}
