<?php
declare(strict_types=1);

namespace Modules\Saraban\Models;

use Core\Database;

class SarabanType
{
    public static function getAll(): array
    {
        return Database::fetchAll("SELECT * FROM saraban_types");
    }

    public static function findBySlug(string $slug): ?array
    {
        return Database::fetch("SELECT * FROM saraban_types WHERE slug = ?", [$slug]);
    }

    public static function getNextNumber(int $id, int $year): int
    {
        $type = Database::fetch("SELECT last_number, budget_year FROM saraban_types WHERE id = ?", [$id]);
        if (!$type) return 1;

        if ($type['budget_year'] != $year) {
            // Reset for new budget year
            Database::query("UPDATE saraban_types SET last_number = 0, budget_year = ? WHERE id = ?", [$year, $id]);
            return 1;
        }

        return (int)$type['last_number'] + 1;
    }

    public static function incrementNumber(int $id): void
    {
        Database::query("UPDATE saraban_types SET last_number = last_number + 1 WHERE id = ?", [$id]);
    }
}
