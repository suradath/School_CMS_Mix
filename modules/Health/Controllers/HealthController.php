<?php
declare(strict_types=1);

namespace Modules\Health\Controllers;

use Core\Controller;
use Core\Database;
use Modules\Health\Models\Health;

class HealthController extends Controller
{
    public function index(): void
    {
        $this->requireAuth();
        
        // Get unique class levels for filters
        $classes = Database::fetchAll("SELECT DISTINCT class_level FROM students WHERE class_level IS NOT NULL ORDER BY class_level");
        
        $this->renderWithLayout('Health.Views.index', 'themes.admin.layout', [
            'title' => 'ระบบสุขภาพและโภชนาการ',
            'classes' => $classes
        ]);
    }

    /**
     * AJAX Endpoint for Health Data
     */
    public function data(): void
    {
        $this->requireAuth();
        
        $filters = [
            'class_level' => $_GET['class_level'] ?? '',
            'room_number' => $_GET['room_number'] ?? ''
        ];

        $distribution = Health::getDistribution($filters);
        $classStats = Health::getClassComparison();
        $students = Health::getStudentHealthList($filters);

        header('Content-Type: application/json');
        echo json_encode([
            'distribution' => [
                'labels' => array_keys($distribution),
                'values' => array_values($distribution),
                'colors' => ['#f59e0b', '#10b981', '#f87171', '#b91c1c']
            ],
            'class_comparison' => [
                'labels' => array_keys($classStats),
                'normal' => array_column($classStats, 'Normal'),
                'at_risk' => array_column($classStats, 'AtRisk')
            ],
            'students' => $students
        ]);
    }
}
