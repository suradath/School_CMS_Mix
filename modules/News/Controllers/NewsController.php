<?php
declare(strict_types=1);

namespace Modules\News\Controllers;

use Core\Controller;
use Core\Uploader;
use Modules\News\Models\News;

class NewsController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->requireAuth();
    }

    /**
     * Check if user has permission to manage this news item
     */
    private function checkNewsPermission(int $newsId = null, int $authorId = null): void
    {
        if (\Core\Security::checkRole('admin')) return;

        // Teacher: Can only edit their own news
        if (\Core\Security::checkRole('teacher')) {
            if ($authorId === (int)$_SESSION['user_id']) return;
        }

        // Editor: Can manage news from their department
        if (\Core\Security::checkRole('editor')) {
            // Fetch author's department
            $authorDept = \Core\Database::fetch("
                SELECT p.department_id FROM users u 
                LEFT JOIN personnel p ON u.personnel_id = p.id 
                WHERE u.id = ?
            ", [$authorId]);
            
            if ($authorDept && $authorDept['department_id'] === (int)($_SESSION['department_id'] ?? 0)) return;
            
            // If creating (no author yet), editors can create
            if ($authorId === null) return;
        }

        header("HTTP/1.1 403 Forbidden");
        die("Access Denied: You do not have permission to manage this news item.");
    }

    /**
     * List all news
     */
    public function index(): void
    {
        $news = News::getAll();
        $this->renderWithLayout('News.Views.index', 'themes.admin.layout', [
            'title' => 'จัดการข่าวประชาสัมพันธ์',
            'news' => $news
        ]);
    }

    /**
     * Show creation form
     */
    public function create(): void
    {
        $this->checkNewsPermission();
        $categories = News::getCategories();
        $this->renderWithLayout('News.Views.create', 'themes.admin.layout', [
            'title' => 'ลงประกาศข่าวใหม่',
            'categories' => $categories
        ]);
    }

    /**
     * Handle creation submission
     */
    public function store(): void
    {
        if (!\Core\Security::validate_csrf()) {
            die("Invalid CSRF Token");
        }

        $featuredImage = '';
        if (isset($_FILES['featured_image']) && $_FILES['featured_image']['error'] === UPLOAD_ERR_OK) {
            $featuredImage = Uploader::uploadImage($_FILES['featured_image'], 'news');
        }

        News::create([
            'title' => $_POST['title'],
            'content' => $_POST['content'],
            'category_id' => (int)$_POST['category_id'],
            'author_id' => $_SESSION['user_id'],
            'featured_image' => $featuredImage,
            'status' => $_POST['status'],
            'published_at' => $_POST['published_at'] ?: date('Y-m-d H:i:s')
        ]);

        $this->redirect('/news');
    }

    /**
     * Show edit form
     */
    public function edit(string $id): void
    {
        $item = News::find((int)$id);
        if (!$item) {
            $this->redirect('/news');
        }

        $this->checkNewsPermission((int)$item['id'], (int)$item['author_id']);
        
        $categories = News::getCategories();
        $this->renderWithLayout('News.Views.edit', 'themes.admin.layout', [
            'title' => 'แก้ไขข่าวประชาสัมพันธ์',
            'item' => $item,
            'categories' => $categories
        ]);
    }

    /**
     * Handle update submission
     */
    public function update(string $id): void
    {
        $item = News::find((int)$id);
        if (!$item) {
            $this->redirect('/news');
        }

        $this->checkNewsPermission((int)$item['id'], (int)$item['author_id']);

        if (!\Core\Security::validate_csrf()) {
            die("Invalid CSRF Token");
        }

        $data = [
            'title' => $_POST['title'],
            'content' => $_POST['content'],
            'category_id' => (int)$_POST['category_id'],
            'status' => $_POST['status'],
            'published_at' => $_POST['published_at'] ?: date('Y-m-d H:i:s'),
            'featured_image' => ''
        ];

        if (isset($_FILES['featured_image']) && $_FILES['featured_image']['error'] === UPLOAD_ERR_OK) {
            $data['featured_image'] = Uploader::uploadImage($_FILES['featured_image'], 'news');
        }

        News::update((int)$id, $data);
        $this->redirect('/news');
    }

    /**
     * Delete news
     */
    public function delete(string $id): void
    {
        $item = News::find((int)$id);
        if ($item) {
            $this->checkNewsPermission((int)$item['id'], (int)$item['author_id']);
            News::delete((int)$id);
        }
        $this->redirect('/news');
    }
}
