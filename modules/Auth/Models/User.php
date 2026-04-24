<?php
declare(strict_types=1);

namespace Modules\Auth\Models;

use Core\Database;

class User
{
    /**
     * Find user by username
     */
    public static function findByUsername(string $username): array|false
    {
        return Database::fetch("SELECT * FROM users WHERE username = ?", [$username]);
    }

    /**
     * Update last login time
     */
    public static function updateLastLogin(int $id): void
    {
        Database::query("UPDATE users SET last_login = NOW() WHERE id = ?", [$id]);
    }
}
