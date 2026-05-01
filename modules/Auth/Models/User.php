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
     * Get all users with personnel info and roles
     */
    public static function getAll(): array
    {
        return Database::fetchAll("
            SELECT u.*, p.name as personnel_name, d.name as department_name,
                   GROUP_CONCAT(r.name SEPARATOR ', ') as roles_display,
                   GROUP_CONCAT(r.slug SEPARATOR ',') as roles_slugs
            FROM users u
            LEFT JOIN personnel p ON u.personnel_id = p.id
            LEFT JOIN departments d ON p.department_id = d.id
            LEFT JOIN user_roles ur ON u.id = ur.user_id
            LEFT JOIN roles r ON ur.role_id = r.id
            GROUP BY u.id
            ORDER BY u.id DESC
        ");
    }

    /**
     * Find user by ID with roles
     */
    public static function find(int $id): array|false
    {
        $user = Database::fetch("SELECT * FROM users WHERE id = ?", [$id]);
        if ($user) {
            $user['roles'] = self::getRoles($id);
        }
        return $user;
    }

    /**
     * Create new user with roles
     */
    public static function create(array $data): string|false
    {
        try {
            Database::beginTransaction();

            $userId = Database::insert("
                INSERT INTO users (username, password, email, full_name, personnel_id, student_id, status) 
                VALUES (?, ?, ?, ?, ?, ?, ?)
            ", [
                $data['username'],
                password_hash($data['password'], PASSWORD_DEFAULT),
                $data['email'],
                $data['full_name'],
                $data['personnel_id'] ?: null,
                $data['student_id'] ?: null,
                $data['status'] ?: 'active'
            ]);

            if ($userId && !empty($data['roles'])) {
                self::syncRoles((int)$userId, $data['roles']);
            }

            Database::commit();
            return $userId;
        } catch (\Exception $e) {
            Database::rollBack();
            return false;
        }
    }

    /**
     * Update user and roles
     */
    public static function update(int $id, array $data): bool
    {
        try {
            Database::beginTransaction();

            $params = [
                $data['email'],
                $data['full_name'],
                $data['personnel_id'] ?: null,
                $data['student_id'] ?: null,
                $data['status'],
                $id
            ];
            
            $sql = "UPDATE users SET email = ?, full_name = ?, personnel_id = ?, student_id = ?, status = ? WHERE id = ?";
            
            if (!empty($data['password'])) {
                $sql = "UPDATE users SET email = ?, full_name = ?, personnel_id = ?, student_id = ?, status = ?, password = ? WHERE id = ?";
                $params = [
                    $data['email'],
                    $data['full_name'],
                    $data['personnel_id'] ?: null,
                    $data['student_id'] ?: null,
                    $data['status'],
                    password_hash($data['password'], PASSWORD_DEFAULT),
                    $id
                ];
            }

            Database::query($sql, $params);

            if (isset($data['roles'])) {
                self::syncRoles($id, $data['roles']);
            }

            Database::commit();
            return true;
        } catch (\Exception $e) {
            Database::rollBack();
            return false;
        }
    }

    /**
     * Update last login time
     */
    public static function updateLastLogin(int $id): void
    {
        Database::query("UPDATE users SET last_login = NOW() WHERE id = ?", [$id]);
    }

    /**
     * Get user roles
     */
    public static function getRoles(int $userId): array
    {
        return Database::fetchAll("
            SELECT r.* FROM roles r
            JOIN user_roles ur ON r.id = ur.role_id
            WHERE ur.user_id = ?
        ", [$userId]);
    }

    /**
     * Get all available roles
     */
    public static function getAvailableRoles(): array
    {
        return Database::fetchAll("SELECT * FROM roles ORDER BY name ASC");
    }

    /**
     * Sync user roles
     */
    public static function syncRoles(int $userId, array $roleIds): void
    {
        // Remove old roles
        Database::query("DELETE FROM user_roles WHERE user_id = ?", [$userId]);

        // Insert new roles
        if (!empty($roleIds)) {
            foreach ($roleIds as $roleId) {
                Database::query("INSERT INTO user_roles (user_id, role_id) VALUES (?, ?)", [$userId, (int)$roleId]);
            }
        }
    }
}
