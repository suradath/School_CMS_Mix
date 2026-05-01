<?php
declare(strict_types=1);

namespace Modules\Booking\Controllers;

use Core\Controller;
use Modules\Booking\Models\BookingModel;

class BookingController extends Controller
{
    private BookingModel $bookingModel;

    public function __construct()
    {
        parent::__construct();
        $this->bookingModel = new BookingModel();
    }

    /**
     * Display the main calendar view
     */
    public function index(): void
    {
        $this->requireAuth();
        
        $this->renderWithLayout('modules.Booking.Views.index', 'themes.admin.layout', [
            'title' => 'ระบบจองทรัพยากร (ห้อง/ยานพาหนะ)',
            'user_name' => $_SESSION['user_name'] ?? null
        ]);
    }

    /**
     * API Endpoint for FullCalendar events
     */
    public function events(): void
    {
        try {
            $this->requireAuth();
            
            $start = $_GET['start'] ?? null;
            $end = $_GET['end'] ?? null;

            if (!$start || !$end) {
                $this->json(['error' => 'Invalid parameters']);
                return;
            }

            $events = $this->bookingModel->getEvents($start, $end);
            $this->json($events);
        } catch (\Exception $e) {
            $this->json(['error' => $e->getMessage()]);
        }
    }

    /**
     * Get resources by type (AJAX)
     */
    public function resourcesByType(): void
    {
        try {
            $this->requireAuth();
            $type = $_GET['type'] ?? '';
            
            $db = \Core\Database::getInstance();
            $stmt = $db->prepare("SELECT id, name, capacity FROM booking_resources WHERE type = :type AND status = 'available'");
            $stmt->execute(['type' => $type]);
            $this->json($stmt->fetchAll(\PDO::FETCH_ASSOC));
        } catch (\Exception $e) {
            $this->json(['error' => $e->getMessage()]);
        }
    }

    /**
     * Store a new booking request
     */
    public function store(): void
    {
        $this->requireAuth();
        
        $resourceId = (int)($_POST['resource_id'] ?? 0);
        $startTime = $_POST['start_time'] ?? '';
        $endTime = $_POST['end_time'] ?? '';
        $title = $_POST['title'] ?? '';
        $participants = (int)($_POST['participants_count'] ?? 0);
        $details = $_POST['details'] ?? '';

        if (!$resourceId || !$startTime || !$endTime || !$title) {
            $this->json(['success' => false, 'message' => 'กรุณากรอกข้อมูลให้ครบถ้วน']);
            return;
        }

        // Validate time
        if (strtotime($startTime) < time()) {
            $this->json(['success' => false, 'message' => 'ไม่สามารถจองเวลาย้อนหลังได้']);
            return;
        }

        if (strtotime($startTime) >= strtotime($endTime)) {
            $this->json(['success' => false, 'message' => 'เวลาเริ่มต้นต้องมาก่อนเวลาสิ้นสุด']);
            return;
        }

        // Check for double booking
        if (!$this->bookingModel->isSlotAvailable($resourceId, $startTime, $endTime)) {
            $this->json(['success' => false, 'message' => 'ทรัพยากรนี้ถูกจองแล้วในช่วงเวลาดังกล่าว']);
            return;
        }

        $db = \Core\Database::getInstance();
        try {
            $db->beginTransaction();
            $stmt = $db->prepare("
                INSERT INTO bookings (user_id, resource_id, title, details, start_time, end_time, participants_count, status)
                VALUES (:user_id, :resource_id, :title, :details, :start_time, :end_time, :participants_count, 'pending')
            ");

            $success = $stmt->execute([
                'user_id' => $_SESSION['user_id'],
                'resource_id' => $resourceId,
                'title' => $title,
                'details' => $details,
                'start_time' => $startTime,
                'end_time' => $endTime,
                'participants_count' => $participants
            ]);
            $db->commit();
            $this->json(['success' => true]);
        } catch (\Exception $e) {
            if ($db->inTransaction()) $db->rollBack();
            $this->json(['success' => false, 'message' => 'ไม่สามารถบันทึกข้อมูลได้: ' . $e->getMessage()]);
        }
    }

    /**
     * My Bookings View
     */
    public function myBookings(): void
    {
        $this->requireAuth();
        
        $db = \Core\Database::getInstance();
        $stmt = $db->prepare("
            SELECT b.*, r.name as resource_name, r.type as resource_type
            FROM bookings b
            JOIN booking_resources r ON b.resource_id = r.id
            WHERE b.user_id = :user_id
            ORDER BY b.created_at DESC
        ");
        $stmt->execute(['user_id' => $_SESSION['user_id']]);
        $bookings = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        $this->renderWithLayout('modules.Booking.Views.my_bookings', 'themes.admin.layout', [
            'title' => 'การจองของฉัน',
            'bookings' => $bookings
        ]);
    }

    /**
     * Cancel a pending booking
     */
    public function cancel(): void
    {
        $this->requireAuth();
        $id = (int)($_POST['id'] ?? 0);

        if (!$id) {
            $this->json(['success' => false, 'message' => 'ID ไม่ถูกต้อง']);
            return;
        }

        $db = \Core\Database::getInstance();
        // Only allow cancelling if it's their own and still pending
        $stmt = $db->prepare("DELETE FROM bookings WHERE id = :id AND user_id = :user_id AND status = 'pending'");
        $success = $stmt->execute([
            'id' => $id,
            'user_id' => $_SESSION['user_id']
        ]);

        if ($success && $stmt->rowCount() > 0) {
            $this->json(['success' => true]);
        } else {
            $this->json(['success' => false, 'message' => 'ไม่สามารถยกเลิกได้ (อาจจะถูกอนุมัติไปแล้ว)']);
        }
    }
}
