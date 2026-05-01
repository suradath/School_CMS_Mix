<?php
declare(strict_types=1);

namespace Modules\Home\Controllers;

use Core\Controller;
use Core\Database;
use Modules\Pages\Models\Page;
use Modules\News\Models\News;
use Modules\Personnel\Models\Personnel;

class HomeController extends Controller
{
    /**
     * Public Homepage
     */
    public function index(): void
    {
        $siteName = Database::fetch("SELECT setting_value FROM settings WHERE setting_key = 'site_name'")['setting_value'] ?? 'School CMS Mix V2.7';
        
        $latestNews = Database::fetchAll("SELECT * FROM news WHERE status = 'published' ORDER BY published_at DESC LIMIT 6");
        $personnelCount = Database::fetch("SELECT COUNT(*) as count FROM personnel")['count'];
        
        // Fetch personnel from 'ฝ่ายบริหาร' (Administration) department
        $adminDept = Database::fetch("SELECT id FROM departments WHERE name = 'ฝ่ายบริหาร'");
        $adminDeptId = $adminDept['id'] ?? 0;
        $featuredPersonnel = Database::fetchAll("SELECT * FROM personnel WHERE department_id = ? ORDER BY sort_order ASC, id ASC", [$adminDeptId]);
        
        // Fetch 4 latest gallery albums
        $latestAlbums = Database::fetchAll("SELECT * FROM gallery_albums ORDER BY created_at DESC LIMIT 4");

        // Fetch journals
        $journals = Database::fetchAll("SELECT * FROM journals ORDER BY sort_order ASC, created_at DESC LIMIT 4");

        // Fetch some stats
        $studentCount = Database::getSetting('stat_student_count', '1200');
        $classroomCount = Database::getSetting('stat_classroom_count', '40');
        $visitorCount = Database::fetch("SELECT COUNT(DISTINCT session_id) as count FROM visitor_counter")['count'] ?? 0;

        $this->renderWithLayout('Home.Views.index', 'themes.default.layout', [
            'site_name' => $siteName,
            'latest_news' => $latestNews,
            'personnel_count' => $personnelCount,
            'featured_personnel' => $featuredPersonnel,
            'latest_albums' => $latestAlbums,
            'journals' => $journals,
            'student_count' => $studentCount,
            'classroom_count' => $classroomCount,
            'visitor_count' => $visitorCount,
            'home_hero_title' => Database::getSetting('home_hero_title', 'ปลูกฝังความรู้ สู่อนาคตที่ยั่งยืน'),
            'home_hero_subtitle' => Database::getSetting('home_hero_subtitle', ''),
            'home_hero_button_text' => Database::getSetting('home_hero_button_text', 'ติดตามข่าวสารล่าสุด'),
            'home_hero_button_url' => Database::getSetting('home_hero_button_url', '/news-all'),
            'home_cover_image' => Database::getSetting('home_cover_image', ''),
            'home_header_mode' => Database::getSetting('home_header_mode', 'single'),
            'home_carousel_data' => json_decode(Database::getSetting('home_carousel_data', '[]'), true),
            'home_about_title' => Database::getSetting('home_about_title', 'มุ่งมั่นสร้างสรรค์ อนาคตที่ยั่งยืนให้เยาวชน'),
            'home_about_content' => Database::getSetting('home_about_content', 'โรงเรียนของเราเป็นสถาบันการศึกษาชั้นนำที่มุ่งเน้นการพัฒนาผู้เรียนให้มีความรู้คู่คุณธรรม พร้อมทักษะที่จำเป็นในโลกยุคดิจิทัล'),
            'home_about_button_text' => Database::getSetting('home_about_button_text', 'อ่านประวัติโรงเรียนเพิ่มเติม'),
            'home_about_button_url' => Database::getSetting('home_about_button_url', '/about-us'),
            'home_about_image' => Database::getSetting('home_about_image', ''),
            'home_about_features' => json_decode(Database::getSetting('home_about_features', '["เทคโนโลยีทันสมัย", "สภาพแวดล้อมปลอดภัย", "เน้นคุณธรรม จริยธรรม", "กิจกรรมเสริมทักษะ"]'), true),
            'home_custom_content' => json_decode(Database::getSetting('home_custom_content', '[]'), true),
            'socials' => [
                'facebook' => Database::getSetting('social_facebook', ''),
                'line' => Database::getSetting('social_line', ''),
                'youtube' => Database::getSetting('social_youtube', ''),
                'tiktok' => Database::getSetting('social_tiktok', ''),
                'twitter' => Database::getSetting('social_twitter', ''),
            ]
        ]);
    }

    /**
     * Public News Listing
     */
    public function newsView(): void
    {
        $siteName = Database::fetch("SELECT setting_value FROM settings WHERE setting_key = 'site_name'")['setting_value'] ?? 'School CMS Mix V2.7';
        $news = News::getAll(); // Existing model method

        $this->renderWithLayout('Home.Views.news_all', 'themes.default.layout', [
            'site_name' => $siteName,
            'title' => 'ข่าวประชาสัมพันธ์ทั้งหมด',
            'news' => $news
        ]);
    }

    /**
     * Public News Detail
     */
    public function newsDetail(int $id): void
    {
        $siteName = Database::fetch("SELECT setting_value FROM settings WHERE setting_key = 'site_name'")['setting_value'] ?? 'School CMS Mix V2.7';
        $item = News::find($id);

        if (!$item) {
            header("HTTP/1.0 404 Not Found");
            echo "<h1>404 News Not Found</h1>";
            exit;
        }

        $this->renderWithLayout('Home.Views.news_detail', 'themes.default.layout', [
            'site_name' => $siteName,
            'title' => $item['title'],
            'item' => $item
        ]);
    }

    /**
     * Public Personnel Listing
     */
    public function personnelView(): void
    {
        $siteName = Database::fetch("SELECT setting_value FROM settings WHERE setting_key = 'site_name'")['setting_value'] ?? 'School CMS Mix V2.7';
        $departments = Personnel::getAllByDepartment();

        $this->renderWithLayout('Home.Views.personnel', 'themes.default.layout', [
            'site_name' => $siteName,
            'title' => 'บุคลากรของเรา',
            'departments' => $departments
        ]);
    }

    /**
     * Public Gallery Listing (Albums)
     */
    public function galleryView(): void
    {
        $siteName = Database::fetch("SELECT setting_value FROM settings WHERE setting_key = 'site_name'")['setting_value'] ?? 'School CMS Mix V2.7';
        $albums = \Modules\Gallery\Models\Gallery::getAlbums();

        $this->renderWithLayout('Home.Views.gallery_all', 'themes.default.layout', [
            'site_name' => $siteName,
            'title' => 'ภาพกิจกรรมโรงเรียน',
            'albums' => $albums
        ]);
    }

    /**
     * Public Gallery Detail (Images in album)
     */
    public function galleryDetail(int $id): void
    {
        $siteName = Database::fetch("SELECT setting_value FROM settings WHERE setting_key = 'site_name'")['setting_value'] ?? 'School CMS Mix V2.7';
        $album = \Modules\Gallery\Models\Gallery::getAlbum($id);
        $images = \Modules\Gallery\Models\Gallery::getImages($id);

        if (!$album) {
            header("HTTP/1.0 404 Not Found");
            echo "<h1>404 Album Not Found</h1>";
            exit;
        }

        $this->renderWithLayout('Home.Views.gallery_detail', 'themes.default.layout', [
            'site_name' => $siteName,
            'title' => $album['title'],
            'album' => $album,
            'images' => $images
        ]);
    }

    /**
     * View Static Page by Slug
     */
    public function viewPage(string $slug): void
    {
        $page = Page::findBySlug($slug);
        
        if (!$page) {
            header("HTTP/1.0 404 Not Found");
            echo "<h1>404 Page Not Found</h1>";
            exit;
        }

        $siteName = Database::fetch("SELECT setting_value FROM settings WHERE setting_key = 'site_name'")['setting_value'] ?? 'School CMS Mix V2.7';

        $this->renderWithLayout('Home.Views.page', 'themes.default.layout', [
            'site_name' => $siteName,
            'title' => $page['title'],
            'page' => $page
        ]);
    }
}
