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
        if (!isset($_SESSION['user_id'])) {
            $this->redirect('/auth');
        }
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
        if (!\Core\Security::validate_csrf()) {
            die("Invalid CSRF Token");
        }

        News::delete((int)$id);
        $this->redirect('/news');
    }
}
