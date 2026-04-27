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
        return Database::fetch("
            SELECT u.*, p.department_id 
            FROM users u
            LEFT JOIN personnel p ON u.personnel_id = p.id
            WHERE u.username = ?
        ", [$username]);
    }

    /**
     * Get all users with personnel info
     */
    public static function getAll(): array
    {
        return Database::fetchAll("
            SELECT u.*, p.name as personnel_name, d.name as department_name 
            FROM users u
            LEFT JOIN personnel p ON u.personnel_id = p.id
            LEFT JOIN departments d ON p.department_id = d.id
            ORDER BY u.id DESC
        ");
    }

    /**
     * Find user by ID
     */
    public static function find(int $id): array|false
    {
        return Database::fetch("SELECT * FROM users WHERE id = ?", [$id]);
    }

    /**
     * Create new user
     */
    public static function create(array $data): string|false
    {
        return Database::insert("
            INSERT INTO users (username, password, email, full_name, personnel_id, role, status) 
            VALUES (?, ?, ?, ?, ?, ?, ?)
        ", [
            $data['username'],
            password_hash($data['password'], PASSWORD_DEFAULT),
            $data['email'],
            $data['full_name'],
            $data['personnel_id'] ?: null,
            $data['role'] ?: 'teacher',
            $data['status'] ?: 'active'
        ]);
    }

    /**
     * Update user
     */
    public static function update(int $id, array $data): bool
    {
        $params = [
            $data['email'],
            $data['full_name'],
            $data['personnel_id'] ?: null,
            $data['role'],
            $data['status'],
            $id
        ];
        
        $sql = "UPDATE users SET email = ?, full_name = ?, personnel_id = ?, role = ?, status = ? WHERE id = ?";
        
        if (!empty($data['password'])) {
            $sql = "UPDATE users SET email = ?, full_name = ?, personnel_id = ?, role = ?, status = ?, password = ? WHERE id = ?";
            $params = [
                $data['email'],
                $data['full_name'],
                $data['personnel_id'] ?: null,
                $data['role'],
                $data['status'],
                password_hash($data['password'], PASSWORD_DEFAULT),
                $id
            ];
        }

        Database::query($sql, $params);
        return true;
    }

    /**
     * Update last login time
     */
    public static function updateLastLogin(int $id): void
    {
        Database::query("UPDATE users SET last_login = NOW() WHERE id = ?", [$id]);
    }
}
