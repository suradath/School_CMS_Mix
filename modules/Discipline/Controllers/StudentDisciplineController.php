<?php
declare(strict_types=1);

namespace Modules\Discipline\Controllers;

use Core\Controller;
use Modules\Discipline\Models\Discipline;
use Modules\Students\Models\Student;
use Core\Database;

class StudentDisciplineController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->requireAuth();
    }

    /**
     * My Discipline History (Student View)
     */
    public function index(): void
    {
        $userId = (int)$_SESSION['user_id'];
        
        // Find associated student_id (Feature removed per request)
        $studentId = 0; // Previously: $user = Database::fetch("SELECT student_id FROM users WHERE id = ?", [$userId]);

        if (!$studentId) {
            $this->renderWithLayout('Discipline.Views.no_link', 'themes.admin.layout', [
                'title' => 'ประวัติพฤติกรรม'
            ]);
            return;
        }

        $student = Student::findWithDetails($studentId);
        $logs = Discipline::getStudentLogs($studentId);
        $summary = Discipline::getStudentSummary($studentId);

        $this->renderWithLayout('Discipline.Views.student_view', 'themes.admin.layout', [
            'title' => 'ประวัติพฤติกรรมของฉัน',
            'student' => $student,
            'logs' => $logs,
            'summary' => $summary
        ]);
    }
}
