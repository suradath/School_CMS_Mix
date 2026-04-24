<?php
declare(strict_types=1);

namespace Modules\Gallery\Models;

use Core\Database;

class Gallery
{
    /**
     * Get all albums
     */
    public static function getAlbums(): array
    {
        return Database::fetchAll("SELECT * FROM gallery_albums ORDER BY created_at DESC");
    }

    /**
     * Get single album
     */
    public static function getAlbum(int $id): array|false
    {
        return Database::fetch("SELECT * FROM gallery_albums WHERE id = ?", [$id]);
    }

    /**
     * Get images in an album
     */
    public static function getImages(int $albumId): array
    {
        return Database::fetchAll("SELECT * FROM gallery_images WHERE album_id = ?", [$albumId]);
    }

    /**
     * Create album
     */
    public static function createAlbum(string $title, string $description, string $coverImage = ''): string|false
    {
        return Database::insert(
            "INSERT INTO gallery_albums (title, description, cover_image) VALUES (?, ?, ?)",
            [$title, $description, $coverImage]
        );
    }

    /**
     * Add image to album
     */
    public static function addImage(int $albumId, string $imageUrl, string $caption = ''): string|false
    {
        return Database::insert(
            "INSERT INTO gallery_images (album_id, image_url, caption) VALUES (?, ?, ?)",
            [$albumId, $imageUrl, $caption]
        );
    }

    /**
     * Get single image
     */
    public static function getImage(int $id): array|false
    {
        return Database::fetch("SELECT * FROM gallery_images WHERE id = ?", [$id]);
    }

    /**
     * Delete image
     */
    public static function deleteImage(int $id): bool
    {
        $image = self::getImage($id);
        if ($image) {
            $path = ROOT_PATH . $image['image_url'];
            if (file_exists($path)) {
                unlink($path);
            }
        }
        return Database::query("DELETE FROM gallery_images WHERE id = ?", [$id])->rowCount() > 0;
    }

    /**
     * Delete album and its images
     */
    public static function deleteAlbum(int $id): bool
    {
        $album = self::getAlbum($id);
        if ($album) {
            // Delete cover image
            if ($album['cover_image']) {
                $path = ROOT_PATH . $album['cover_image'];
                if (file_exists($path)) {
                    unlink($path);
                }
            }
            
            // Delete all images in album
            $images = self::getImages($id);
            foreach ($images as $img) {
                $path = ROOT_PATH . $img['image_url'];
                if (file_exists($path)) {
                    unlink($path);
                }
            }
        }
        return Database::query("DELETE FROM gallery_albums WHERE id = ?", [$id])->rowCount() > 0;
    }
}
