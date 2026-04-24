<?php
declare(strict_types=1);

namespace Modules\Pages\Controllers;

use Core\Controller;
use Modules\Pages\Models\Page;

class PagesController extends Controller
{
    public function __construct()
    {
        if (!isset($_SESSION['user_id'])) {
            $this->redirect('/auth');
        }
    }

    /**
     * List all pages
     */
    public function index(): void
    {
        $pages = Page::getAll('page');
        $this->renderWithLayout('Pages.Views.index', 'themes.admin.layout', [
            'title' => 'จัดการหน้าเว็บคงที่',
            'pages' => $pages
        ]);
    }

    /**
     * Show creation form
     */
    public function create(): void
    {
        $this->renderWithLayout('Pages.Views.create', 'themes.admin.layout', [
            'title' => 'สร้างหน้าใหม่'
        ]);
    }

    /**
     * Handle creation submission
     */
    public function store(): void
    {
        $title = $_POST['title'];
        $slug = !empty($_POST['slug']) ? $_POST['slug'] : $this->slugify($title);
        
        $featuredImage = '';
        if (isset($_FILES['featured_image']) && $_FILES['featured_image']['error'] === UPLOAD_ERR_OK) {
            $featuredImage = \Core\Uploader::uploadImage($_FILES['featured_image'], 'pages');
        }

        Page::create([
            'title' => $title,
            'slug' => $slug,
            'content' => $_POST['content'],
            'author_id' => $_SESSION['user_id'],
            'status' => $_POST['status'],
            'type' => 'page',
            'meta_description' => $_POST['meta_description'],
            'featured_image' => $featuredImage
        ]);

        $this->redirect('/pages');
    }

    /**
     * Helper to create URL-friendly slug
     */
    private function slugify(string $text): string
    {
        // Simple slugify for Thai/English
        $text = preg_replace('~[^\pL\d]+~u', '-', $text);
        $text = trim($text, '-');
        $text = mb_strtolower($text);
        return $text ?: 'n-a';
    }

    /**
     * Show edit form
     */
    public function edit(string $id): void
    {
        $page = Page::find((int)$id);
        if (!$page) {
            $this->redirect('/pages');
        }

        $this->renderWithLayout('Pages.Views.edit', 'themes.admin.layout', [
            'title' => 'แก้ไขหน้าเว็บ',
            'page' => $page
        ]);
    }

    /**
     * Handle update submission
     */
    public function update(string $id): void
    {
        $title = $_POST['title'];
        $slug = !empty($_POST['slug']) ? $_POST['slug'] : $this->slugify($title);
        
        $data = [
            'title' => $title,
            'slug' => $slug,
            'content' => $_POST['content'],
            'status' => $_POST['status'],
            'meta_description' => $_POST['meta_description']
        ];

        if (isset($_FILES['featured_image']) && $_FILES['featured_image']['error'] === UPLOAD_ERR_OK) {
            $data['featured_image'] = \Core\Uploader::uploadImage($_FILES['featured_image'], 'pages');
        }

        Page::update((int)$id, $data);

        $this->redirect('/pages');
    }

    /**
     * Delete page
     */
    public function delete(string $id): void
    {
        // Assuming there is a delete method in the Model or direct query
        // Let me check Page.php again to see if delete exists.
        // If not, I'll add it or use direct query.
        \Core\Database::query("DELETE FROM pages WHERE id = ?", [(int)$id]);
        $this->redirect('/pages');
    }
}
