<?php
declare(strict_types=1);

namespace Modules\Pages\Models;

use Core\Database;

class Page
{
    /**
     * Get all pages/posts
     */
    public static function getAll(string $type = 'page'): array
    {
        return Database::fetchAll(
            "SELECT p.*, u.full_name as author_name 
             FROM pages p 
             LEFT JOIN users u ON p.author_id = u.id 
             WHERE p.type = ? 
             ORDER BY p.created_at DESC",
            [$type]
        );
    }

    /**
     * Get single page by ID
     */
    public static function find(int $id): array|false
    {
        return Database::fetch("SELECT * FROM pages WHERE id = ?", [$id]);
    }

    /**
     * Get single page by slug
     */
    public static function findBySlug(string $slug): array|false
    {
        return Database::fetch("SELECT * FROM pages WHERE slug = ? AND status = 'published'", [$slug]);
    }

    /**
     * Create new page
     */
    public static function create(array $data): string|false
    {
        return Database::insert(
            "INSERT INTO pages (title, slug, content, author_id, status, type, meta_description, featured_image) 
             VALUES (?, ?, ?, ?, ?, ?, ?, ?)",
            [
                $data['title'],
                $data['slug'],
                $data['content'],
                $data['author_id'],
                $data['status'] ?? 'published',
                $data['type'] ?? 'page',
                $data['meta_description'] ?? '',
                $data['featured_image'] ?? ''
            ]
        );
    }

    /**
     * Update page
     */
    public static function update(int $id, array $data): bool
    {
        $sql = "UPDATE pages SET title = ?, slug = ?, content = ?, status = ?, meta_description = ?";
        $params = [
            $data['title'],
            $data['slug'],
            $data['content'],
            $data['status'],
            $data['meta_description']
        ];

        if (isset($data['featured_image']) && !empty($data['featured_image'])) {
            $sql .= ", featured_image = ?";
            $params[] = $data['featured_image'];
        }

        $sql .= " WHERE id = ?";
        $params[] = $id;

        Database::query($sql, $params);
        return true;
    }

    /**
     * Delete page
     */
    public static function delete(int $id): bool
    {
        Database::query("DELETE FROM pages WHERE id = ?", [$id]);
        return true;
    }
}
