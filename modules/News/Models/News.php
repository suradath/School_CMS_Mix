<?php
declare(strict_types=1);

namespace Modules\News\Models;

use Core\Database;

class News
{
    /**
     * Get all news with category and author info
     */
    public static function getAll(): array
    {
        return Database::fetchAll(
            "SELECT n.*, c.name as category_name, u.full_name as author_name 
             FROM news n 
             LEFT JOIN news_categories c ON n.category_id = c.id 
             LEFT JOIN users u ON n.author_id = u.id 
             ORDER BY n.published_at DESC"
        );
    }

    /**
     * Get single news item
     */
    public static function find(int $id): array|false
    {
        return Database::fetch("SELECT * FROM news WHERE id = ?", [$id]);
    }

    /**
     * Create news
     */
    public static function create(array $data): string|false
    {
        return Database::insert(
            "INSERT INTO news (title, content, category_id, author_id, featured_image, status, published_at) 
             VALUES (?, ?, ?, ?, ?, ?, ?)",
            [
                $data['title'],
                $data['content'],
                $data['category_id'],
                $data['author_id'],
                $data['featured_image'],
                $data['status'] ?? 'published',
                $data['published_at'] ?? date('Y-m-d H:i:s')
            ]
        );
    }

    /**
     * Update news
     */
    public static function update(int $id, array $data): bool
    {
        $sql = "UPDATE news SET title = ?, content = ?, category_id = ?, status = ?, published_at = ?";
        $params = [
            $data['title'],
            $data['content'],
            $data['category_id'],
            $data['status'],
            $data['published_at']
        ];

        if (!empty($data['featured_image'])) {
            $sql .= ", featured_image = ?";
            $params[] = $data['featured_image'];
        }

        $sql .= " WHERE id = ?";
        $params[] = $id;

        Database::query($sql, $params);
        return true;
    }

    /**
     * Delete news
     */
    public static function delete(int $id): bool
    {
        Database::query("DELETE FROM news WHERE id = ?", [$id]);
        return true;
    }

    /**
     * Get categories
     */
    public static function getCategories(): array
    {
        return Database::fetchAll("SELECT * FROM news_categories ORDER BY name ASC");
    }
}
