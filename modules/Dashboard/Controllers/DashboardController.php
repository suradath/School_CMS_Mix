<?php
declare(strict_types=1);

namespace Modules\Dashboard\Controllers;

use Core\Controller;
use Core\Database;

class DashboardController extends Controller
{
    public function __construct()
    {
        if (!isset($_SESSION['user_id'])) {
            $this->redirect('/auth');
        }
    }

    public function index(): void
    {
        // Get some stats for the dashboard
        $stats = [
            'pages' => Database::fetch("SELECT COUNT(*) as count FROM pages")['count'],
            'personnel' => Database::fetch("SELECT COUNT(*) as count FROM personnel")['count'],
            'news' => Database::fetch("SELECT COUNT(*) as count FROM news")['count'],
            'visitors' => Database::fetch("SELECT COUNT(DISTINCT session_id) as count FROM visitor_counter")['count']
        ];

        $this->renderWithLayout('Dashboard.Views.index', 'themes.admin.layout', [
            'title' => 'แผงควบคุมหลัก',
            'stats' => $stats
        ]);
    }
}
