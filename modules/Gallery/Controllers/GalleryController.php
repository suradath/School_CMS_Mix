<?php
declare(strict_types=1);

namespace Modules\Gallery\Controllers;

use Core\Controller;
use Core\Uploader;
use Modules\Gallery\Models\Gallery;

class GalleryController extends Controller
{
    public function __construct()
    {
        if (!isset($_SESSION['user_id'])) {
            $this->redirect('/auth');
        }
    }

    /**
     * List all albums
     */
    public function index(): void
    {
        $albums = Gallery::getAlbums();
        $this->renderWithLayout('Gallery.Views.index', 'themes.admin.layout', [
            'title' => 'อัลบั้มภาพกิจกรรม',
            'albums' => $albums
        ]);
    }

    /**
     * Show album images
     */
    public function view(int $id): void
    {
        $album = Gallery::getAlbum($id);
        $images = Gallery::getImages($id);
        
        $this->renderWithLayout('Gallery.Views.view', 'themes.admin.layout', [
            'title' => 'อัลบั้ม: ' . ($album['title'] ?? 'ไม่พบ'),
            'album' => $album,
            'images' => $images
        ]);
    }

    /**
     * Create album form
     */
    public function create(): void
    {
        $this->renderWithLayout('Gallery.Views.create', 'themes.admin.layout', [
            'title' => 'สร้างอัลบั้มใหม่'
        ]);
    }

    /**
     * Store new album
     */
    public function store(): void
    {
        $coverImage = '';
        if (isset($_FILES['cover_image']) && $_FILES['cover_image']['error'] === UPLOAD_ERR_OK) {
            $coverImage = Uploader::uploadImage($_FILES['cover_image'], 'gallery');
        }

        Gallery::createAlbum($_POST['title'], $_POST['description'], $coverImage);
        $this->redirect('/gallery');
    }

    /**
     * Add photo to album
     */
    /**
     * Add photos to album (Support multiple)
     */
    public function addPhoto(int $albumId): void
    {
        if (isset($_FILES['photos'])) {
            $files = $_FILES['photos'];
            $count = count($files['name']);
            
            for ($i = 0; $i < $count; $i++) {
                if ($files['error'][$i] === UPLOAD_ERR_OK) {
                    $file = [
                        'name' => $files['name'][$i],
                        'type' => $files['type'][$i],
                        'tmp_name' => $files['tmp_name'][$i],
                        'error' => $files['error'][$i],
                        'size' => $files['size'][$i],
                    ];
                    
                    // Upload with 3MB max size limit and auto-compression
                    $imageUrl = Uploader::uploadImage($file, 'gallery/album_' . $albumId, 3);
                    
                    if ($imageUrl) {
                        Gallery::addImage($albumId, $imageUrl, $_POST['caption'] ?? '');
                    }
                }
            }
        }
        $this->redirect('/gallery/view/' . $albumId);
    }

    /**
     * Delete an album
     */
    public function delete(int $id): void
    {
        Gallery::deleteAlbum($id);
        $this->redirect('/gallery');
    }

    /**
     * Delete a single photo
     */
    public function deletePhoto(int $id): void
    {
        $image = Gallery::getImage($id);
        if ($image) {
            $albumId = $image['album_id'];
            Gallery::deleteImage($id);
            $this->redirect('/gallery/view/' . $albumId);
        } else {
            $this->redirect('/gallery');
        }
    }
}
