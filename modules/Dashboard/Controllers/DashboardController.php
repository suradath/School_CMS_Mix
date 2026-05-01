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
            'visitors' => Database::fetch("SELECT COUNT(DISTINCT session_id) as count FROM visitor_counter")['count'],
            'pending_leaves' => Database::fetch("SELECT COUNT(*) as count FROM leave_requests WHERE status = 'pending'")['count'],
            'unread_saraban' => Database::fetch("
                SELECT COUNT(*) as count 
                FROM saraban_receivers r
                JOIN saraban_documents d ON r.document_id = d.id
                WHERE (r.personnel_id = ? OR r.department_id = (SELECT department_id FROM personnel WHERE id = ?)) 
                AND r.status = 'unread' 
                AND d.status = 'active'", 
                [$_SESSION['personnel_id'] ?? 0, $_SESSION['personnel_id'] ?? 0]
            )['count'],
            'pending_bookings' => (function() {
                try {
                    return \Core\Database::fetch("SELECT COUNT(*) as count FROM bookings WHERE status = 'pending'")['count'];
                } catch (\Exception $e) {
                    return 0;
                }
            })(),
            'pending_repairs' => (function() {
                try {
                    return \Core\Database::fetch("SELECT COUNT(*) as count FROM repair_requests WHERE status = 'pending'")['count'];
                } catch (\Exception $e) {
                    return 0;
                }
            })()
        ];

        $this->renderWithLayout('Dashboard.Views.index', 'themes.admin.layout', [
            'title' => 'แผงควบคุมหลัก',
            'stats' => $stats
        ]);
    }
}
