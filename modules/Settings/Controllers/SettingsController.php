<?php
declare(strict_types=1);

namespace Modules\Settings\Controllers;

use Core\Controller;
use Core\Database;
use Core\Uploader;

class SettingsController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->requireRole('admin');
    }

    /**
     * Show settings page
     */
    public function index(): void
    {
        $settings = [
            'site_name' => Database::getSetting('site_name', 'School CMS Mix V2.5'),
            'primary_color' => Database::getSetting('primary_color', '#1d4ed8'),
            'secondary_color' => Database::getSetting('secondary_color', '#3b82f6'),
            'site_logo' => Database::getSetting('site_logo', ''),
            'site_favicon' => Database::getSetting('site_favicon', ''),
            'footer_text' => Database::getSetting('footer_text', 'School CMS Mix V2.5 Application'),
            'school_address' => Database::getSetting('school_address', ''),
            'school_phone' => Database::getSetting('school_phone', ''),
            'social_facebook' => Database::getSetting('social_facebook', ''),
            'social_line' => Database::getSetting('social_line', ''),
            'social_youtube' => Database::getSetting('social_youtube', ''),
            'social_tiktok' => Database::getSetting('social_tiktok', ''),
            'social_twitter' => Database::getSetting('social_twitter', ''),
            'stat_student_count' => Database::getSetting('stat_student_count', '500'),
            'stat_classroom_count' => Database::getSetting('stat_classroom_count', '20'),
        ];

        $this->renderWithLayout('Settings.Views.index', 'themes.admin.layout', [
            'title' => 'ตั้งค่าระบบและอัตลักษณ์',
            'settings' => $settings
        ]);
    }

    /**
     * Update settings
     */
    public function update(): void
    {
        if (!\Core\Security::validate_csrf()) {
            die("Invalid CSRF Token");
        }

        // 1. Text Settings
        $fields = [
            'site_name', 'primary_color', 'secondary_color', 'footer_text', 'school_address', 'school_phone',
            'social_facebook', 'social_line', 'social_youtube', 'social_tiktok', 'social_twitter',
            'stat_student_count', 'stat_classroom_count'
        ];
        foreach ($fields as $field) {
            if (isset($_POST[$field])) {
                Database::updateSetting($field, $_POST[$field]);
            }
        }

        // 2. Logo Upload
        if (isset($_FILES['site_logo']) && $_FILES['site_logo']['error'] === UPLOAD_ERR_OK) {
            $logoUrl = Uploader::uploadImage($_FILES['site_logo'], 'identity');
            Database::updateSetting('site_logo', $logoUrl);
        }

        // 3. Favicon Upload
        if (isset($_FILES['site_favicon']) && $_FILES['site_favicon']['error'] === UPLOAD_ERR_OK) {
            $favUrl = Uploader::uploadImage($_FILES['site_favicon'], 'identity');
            Database::updateSetting('site_favicon', $favUrl);
        }

        $this->redirect('/settings');
    }

    /**
     * Homepage Content Editor
     */
    public function homeEditor(): void
    {
        $settings = [
            'home_hero_title' => Database::getSetting('home_hero_title', 'ปลูกฝังความรู้ สู่อนาคตที่ยั่งยืน'),
            'home_hero_subtitle' => Database::getSetting('home_hero_subtitle', ''),
            'home_hero_button_text' => Database::getSetting('home_hero_button_text', 'ติดตามข่าวสารล่าสุด'),
            'home_hero_button_url' => Database::getSetting('home_hero_button_url', '/news-all'),
            'home_cover_image' => Database::getSetting('home_cover_image', ''),
            'home_header_mode' => Database::getSetting('home_header_mode', 'single'),
            'home_carousel_data' => json_decode(Database::getSetting('home_carousel_data', '[]'), true),
            'home_about_title' => Database::getSetting('home_about_title', 'มุ่งมั่นสร้างสรรค์ อนาคตที่ยั่งยืนให้เยาวชน'),
            'home_about_content' => Database::getSetting('home_about_content', ''),
            'home_about_button_text' => Database::getSetting('home_about_button_text', 'อ่านประวัติโรงเรียนเพิ่มเติม'),
            'home_about_button_url' => Database::getSetting('home_about_button_url', '/about-us'),
            'home_about_image' => Database::getSetting('home_about_image', ''),
            'home_about_features' => json_decode(Database::getSetting('home_about_features', '[]'), true),
            'home_custom_content' => json_decode(Database::getSetting('home_custom_content', '[]'), true),
        ];

        $this->renderWithLayout('Settings.Views.home_editor', 'themes.admin.layout', [
            'title' => 'จัดการเนื้อหาหน้าแรก',
            'settings' => $settings
        ]);
    }

    /**
     * Update Homepage Settings
     */
    public function updateHome(): void
    {
        if (!\Core\Security::validate_csrf()) {
            die("Invalid CSRF Token");
        }

        // 1. Text Fields
        $fields = [
            'home_hero_title', 'home_hero_subtitle', 'home_hero_button_text', 'home_hero_button_url', 'home_header_mode',
            'home_about_title', 'home_about_content', 'home_about_button_text', 'home_about_button_url'
        ];
        foreach ($fields as $field) {
            if (isset($_POST[$field])) {
                Database::updateSetting($field, $_POST[$field]);
            }
        }

        // Handle About Features (Array to JSON)
        if (isset($_POST['home_about_features'])) {
            Database::updateSetting('home_about_features', json_encode($_POST['home_about_features'], JSON_UNESCAPED_UNICODE));
        }

        // 2. Cover Image Upload & Deletion
        if (isset($_POST['delete_cover']) && $_POST['delete_cover'] == '1') {
            Database::updateSetting('home_cover_image', '');
        } elseif (isset($_FILES['home_cover_image']) && $_FILES['home_cover_image']['error'] === UPLOAD_ERR_OK) {
            $coverUrl = Uploader::uploadImage($_FILES['home_cover_image'], 'homepage');
            if ($coverUrl) {
                Database::updateSetting('home_cover_image', $coverUrl);
            }
        }

        // Handle About Image
        if (isset($_FILES['home_about_image']) && $_FILES['home_about_image']['error'] === UPLOAD_ERR_OK) {
            $aboutUrl = Uploader::uploadImage($_FILES['home_about_image'], 'homepage');
            if ($aboutUrl) {
                Database::updateSetting('home_about_image', $aboutUrl);
            }
        }

        // 3. Carousel Management
        if (isset($_POST['carousel'])) {
            $carouselData = [];
            foreach ($_POST['carousel'] as $index => $item) {
                $slide = [
                    'title' => $item['title'] ?? '',
                    'subtitle' => $item['subtitle'] ?? '',
                    'image' => $item['existing_image'] ?? ''
                ];

                // Check if new image is uploaded for this slide
                if (isset($_FILES['carousel_image']['name'][$index]) && $_FILES['carousel_image']['error'][$index] === UPLOAD_ERR_OK) {
                    $file = [
                        'name' => $_FILES['carousel_image']['name'][$index],
                        'type' => $_FILES['carousel_image']['type'][$index],
                        'tmp_name' => $_FILES['carousel_image']['tmp_name'][$index],
                        'error' => $_FILES['carousel_image']['error'][$index],
                        'size' => $_FILES['carousel_image']['size'][$index]
                    ];
                    $uploadUrl = Uploader::uploadImage($file, 'carousel');
                    if ($uploadUrl) {
                        $slide['image'] = $uploadUrl;
                    }
                }

                if (!empty($slide['image'])) {
                    $carouselData[] = $slide;
                }
            }
            Database::updateSetting('home_carousel_data', json_encode($carouselData, JSON_UNESCAPED_UNICODE));
        } else {
            Database::updateSetting('home_carousel_data', '[]');
        }

        // 4. Custom Content (Drag & Drop Blocks)
        if (isset($_POST['custom_blocks'])) {
            $blocks = [];
            foreach ($_POST['custom_blocks'] as $index => $block) {
                $item = [
                    'type' => $block['type'] ?? 'text',
                    'title' => $block['title'] ?? '',
                    'content' => $block['content'] ?? '',
                    'image' => $block['existing_image'] ?? '',
                    'images' => $block['existing_images'] ?? [], // For carousel type
                    'button_text' => $block['button_text'] ?? '',
                    'button_url' => $block['button_url'] ?? '',
                    'cols' => $block['cols'] ?? 2,
                    'items' => $block['items'] ?? [],
                    'embed_code' => $block['embed_code'] ?? '',
                    'height' => $block['height'] ?? '500',
                    'background' => $block['background'] ?? 'white'
                ];

                // 4.1 Handle single image upload
                if (isset($_FILES['block_image']['name'][$index]) && $_FILES['block_image']['error'][$index] === UPLOAD_ERR_OK) {
                    $file = [
                        'name' => $_FILES['block_image']['name'][$index],
                        'type' => $_FILES['block_image']['type'][$index],
                        'tmp_name' => $_FILES['block_image']['tmp_name'][$index],
                        'error' => $_FILES['block_image']['error'][$index],
                        'size' => $_FILES['block_image']['size'][$index]
                    ];
                    $uploadUrl = Uploader::uploadImage($file, 'homepage_blocks');
                    if ($uploadUrl) {
                        $item['image'] = $uploadUrl;
                    }
                }

                // 4.2 Handle multiple carousel images upload
                if (isset($_FILES['block_carousel_images']['name'][$index])) {
                    $cFiles = $_FILES['block_carousel_images'];
                    foreach ($cFiles['name'][$index] as $cIdx => $cName) {
                        if ($cFiles['error'][$index][$cIdx] === UPLOAD_ERR_OK) {
                            $file = [
                                'name' => $cFiles['name'][$index][$cIdx],
                                'type' => $cFiles['type'][$index][$cIdx],
                                'tmp_name' => $cFiles['tmp_name'][$index][$cIdx],
                                'error' => $cFiles['error'][$index][$cIdx],
                                'size' => $cFiles['size'][$index][$cIdx]
                            ];
                            $uploadUrl = Uploader::uploadImage($file, 'homepage_blocks/carousel');
                            if ($uploadUrl) {
                                $item['images'][] = $uploadUrl;
                            }
                        }
                    }
                }

                if (!empty($item['embed_code'])) {
                    $item['embed_code'] = $this->sanitizeEmbedCode($item['embed_code']);
                }

                $blocks[] = $item;
            }
            Database::updateSetting('home_custom_content', json_encode($blocks, JSON_UNESCAPED_UNICODE));
        } else {
            Database::updateSetting('home_custom_content', '[]');
        }

        $this->redirect('/settings/home-editor');
    }

    /**
     * Sanitize Embed Code (Allow only iframes from trusted sources)
     */
    private function sanitizeEmbedCode(string $html): string
    {
        // Simple allow-list for iframes from common providers
        if (preg_match('/<iframe.*src="https:\/\/(www\.youtube\.com|www\.google\.com\/maps).*".*><\/iframe>/i', $html, $matches)) {
            return $matches[0];
        }
        
        // If not matching, strip all tags for safety
        return strip_tags($html);
    }

    /**
     * Footer Content Editor
     */
    public function footerEditor(): void
    {
        $settings = [
            'footer_description' => Database::getSetting('footer_description', 'ยกระดับการศึกษาด้วยเทคโนโลยีที่ทันสมัย ระบบบริหารจัดการเนื้อหาโรงเรียน (School CMS Mix V2.5) ที่ออกแบบมาเพื่อความง่ายและประสิทธิภาพสูงสุด'),
            'school_address' => Database::getSetting('school_address', '123 ถ.วิทยพัฒนา ต.ในเมือง อ.เมือง จ.ขอนแก่น 40000'),
            'school_phone' => Database::getSetting('school_phone', '043-xxx-xxxx'),
            'footer_copyright' => Database::getSetting('footer_copyright', '© ' . date('Y') . ' School CMS Mix V2.5. All rights reserved.'),
        ];

        $this->renderWithLayout('Settings.Views.footer_editor', 'themes.admin.layout', [
            'title' => 'จัดการเนื้อหาส่วนท้าย (Footer)',
            'settings' => $settings
        ]);
    }

    /**
     * Update Footer Settings
     */
    public function updateFooter(): void
    {
        if (!\Core\Security::validate_csrf()) {
            die("Invalid CSRF Token");
        }

        $fields = ['footer_description', 'school_address', 'school_phone', 'footer_copyright'];
        foreach ($fields as $field) {
            if (isset($_POST[$field])) {
                Database::updateSetting($field, $_POST[$field]);
            }
        }

        $this->redirect('/settings/footer-editor');
    }
}
