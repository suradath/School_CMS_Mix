<?php
declare(strict_types=1);

namespace Core;

/**
 * File Uploader Utility
 */
class Uploader
{
    private static string $uploadDir = ROOT_PATH . '/uploads';

    public static function uploadImage(array $file, string $subDir = '', int $maxSizeMB = 2): string|false
    {
        if ($file['error'] !== UPLOAD_ERR_OK) {
            return false;
        }

        $extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        $allowed = ['jpg', 'jpeg', 'png', 'gif', 'webp'];

        if (!in_array($extension, $allowed)) {
            return false;
        }

        // Check file size
        if ($file['size'] > ($maxSizeMB * 1024 * 1024)) {
            return false;
        }

        // Security: Verify MIME type
        $check = getimagesize($file['tmp_name']);
        if ($check === false) {
            return false; // Not a valid image
        }
        
        $mime = $check['mime'];
        $allowedMime = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
        if (!in_array($mime, $allowedMime)) {
            return false;
        }

        $fileName = uniqid('img_', true) . '.' . $extension;
        $targetDir = self::$uploadDir . ($subDir ? '/' . $subDir : '');

        if (!is_dir($targetDir)) {
            mkdir($targetDir, 0755, true);
        }

        $targetPath = $targetDir . '/' . $fileName;

        if (move_uploaded_file($file['tmp_name'], $targetPath)) {
            // Check file size and compress if it's an image
            self::processImage($targetPath, $extension, $maxSizeMB);
            return '/uploads' . ($subDir ? '/' . $subDir : '') . '/' . $fileName;
        }

        return false;
    }

    /**
     * Upload any file (PDF, Doc, etc.)
     */
    public static function uploadFile(array $file, string $subDir = '', array $allowedExts = ['pdf', 'jpg', 'jpeg', 'png'], int $maxSizeMB = 5): string|false
    {
        if ($file['error'] !== UPLOAD_ERR_OK) return false;

        $extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        if (!in_array($extension, $allowedExts)) return false;

        // Check size
        if ($file['size'] > ($maxSizeMB * 1024 * 1024)) return false;

        $fileName = uniqid('file_', true) . '.' . $extension;
        $targetDir = self::$uploadDir . ($subDir ? '/' . $subDir : '');

        if (!is_dir($targetDir)) {
            mkdir($targetDir, 0755, true);
        }

        $targetPath = $targetDir . '/' . $fileName;

        if (move_uploaded_file($file['tmp_name'], $targetPath)) {
            return '/uploads' . ($subDir ? '/' . $subDir : '') . '/' . $fileName;
        }

        return false;
    }

    /**
     * Process image: Resize and Compress
     */
    private static function processImage(string $path, string $ext, int $maxSizeMB): void
    {
        $fileSize = filesize($path);
        $maxSizeBytes = $maxSizeMB * 1024 * 1024;

        // If file is already small enough, just return
        if ($fileSize <= $maxSizeBytes && $fileSize < 1024 * 1024) {
            return;
        }

        // Load image based on extension
        $image = null;
        switch ($ext) {
            case 'jpg':
            case 'jpeg': $image = imagecreatefromjpeg($path); break;
            case 'png': $image = imagecreatefrompng($path); break;
            case 'webp': $image = imagecreatefromwebp($path); break;
            case 'gif': $image = imagecreatefromgif($path); break;
        }

        if (!$image) return;

        // Get original dimensions
        $width = imagesx($image);
        $height = imagesy($image);

        // If image is very large in resolution, resize it first
        $maxDimension = 1920; // Max width or height
        if ($width > $maxDimension || $height > $maxDimension) {
            if ($width > $height) {
                $newWidth = $maxDimension;
                $newHeight = (int)($height * ($maxDimension / $width));
            } else {
                $newHeight = $maxDimension;
                $newWidth = (int)($width * ($maxDimension / $height));
            }

            $newImage = imagecreatetruecolor($newWidth, $newHeight);
            
            // Preserve transparency for PNG/WebP
            if ($ext === 'png' || $ext === 'webp') {
                imagealphablending($newImage, false);
                imagesavealpha($newImage, true);
                $transparent = imagecolorallocatealpha($newImage, 255, 255, 255, 127);
                imagefilledrectangle($newImage, 0, 0, $newWidth, $newHeight, $transparent);
            }

            imagecopyresampled($newImage, $image, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);
            imagedestroy($image);
            $image = $newImage;
        }

        // Save back with compression
        switch ($ext) {
            case 'jpg':
            case 'jpeg': imagejpeg($image, $path, 60); break; // 60% quality
            case 'png': 
                imagepng($image, $path, 7); // Increased compression for PNG
                break;
            case 'webp': imagewebp($image, $path, 60); break; // 60% quality
            case 'gif': imagegif($image, $path); break;
        }

        imagedestroy($image);
    }
}
