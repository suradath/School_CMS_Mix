<?php
declare(strict_types=1);

namespace Modules\Calendar\Controllers;

use Core\Controller;
use Modules\Calendar\Models\Calendar;

class CalendarController extends Controller
{
    public function __construct()
    {
        if (!isset($_SESSION['user_id'])) {
            $this->redirect('/auth');
        }
    }

    /**
     * Admin: List events
     */
    public function index(): void
    {
        $events = Calendar::getAll();
        $this->renderWithLayout('Calendar.Views.admin.index', 'themes.admin.layout', [
            'title' => 'ปฏิทินวิชาการ',
            'events' => $events
        ]);
    }

    /**
     * Admin: Create event form
     */
    public function create(): void
    {
        $this->renderWithLayout('Calendar.Views.admin.create', 'themes.admin.layout', [
            'title' => 'เพิ่มกิจกรรมใหม่'
        ]);
    }

    /**
     * Admin: Store event
     */
    public function store(): void
    {
        $data = [
            'title' => $_POST['title'] ?? '',
            'description' => $_POST['description'] ?? '',
            'start_date' => $_POST['start_date'] ?? '',
            'end_date' => !empty($_POST['end_date']) ? $_POST['end_date'] : null,
            'start_time' => !empty($_POST['start_time']) ? $_POST['start_time'] : null,
            'end_time' => !empty($_POST['end_time']) ? $_POST['end_time'] : null,
            'responsible_person' => $_POST['responsible_person'] ?? '',
            'color' => $_POST['color'] ?? '#1d4ed8'
        ];

        if (empty($data['title']) || empty($data['start_date'])) {
            $_SESSION['error'] = 'กรุณากรอกชื่อกิจกรรมและวันที่เริ่มต้น';
            $this->redirect('/calendar/create');
            return;
        }

        Calendar::create($data);
        $_SESSION['success'] = 'เพิ่มกิจกรรมเรียบร้อยแล้ว';
        $this->redirect('/calendar');
    }

    /**
     * Admin: Edit event form
     */
    public function edit(int $id): void
    {
        $event = Calendar::find($id);
        if (!$event) {
            $this->redirect('/calendar');
        }

        $this->renderWithLayout('Calendar.Views.admin.edit', 'themes.admin.layout', [
            'title' => 'แก้ไขกิจกรรม',
            'event' => $event
        ]);
    }

    /**
     * Admin: Update event
     */
    public function update(int $id): void
    {
        $data = [
            'title' => $_POST['title'] ?? '',
            'description' => $_POST['description'] ?? '',
            'start_date' => $_POST['start_date'] ?? '',
            'end_date' => !empty($_POST['end_date']) ? $_POST['end_date'] : null,
            'start_time' => !empty($_POST['start_time']) ? $_POST['start_time'] : null,
            'end_time' => !empty($_POST['end_time']) ? $_POST['end_time'] : null,
            'responsible_person' => $_POST['responsible_person'] ?? '',
            'color' => $_POST['color'] ?? '#1d4ed8'
        ];

        Calendar::update($id, $data);
        $_SESSION['success'] = 'อัปเดตกิจกรรมเรียบร้อยแล้ว';
        $this->redirect('/calendar');
    }

    /**
     * Admin: Delete event
     */
    public function delete(int $id): void
    {
        Calendar::delete($id);
        $_SESSION['success'] = 'ลบกิจกรรมเรียบร้อยแล้ว';
        $this->redirect('/calendar');
    }

    /**
     * API: Get events JSON
     */
    public function getEvents(): void
    {
        $events = Calendar::getForCalendar();
        header('Content-Type: application/json');
        echo json_encode($events);
        exit;
    }
}
