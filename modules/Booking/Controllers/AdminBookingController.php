<?php
declare(strict_types=1);

namespace Modules\Booking\Controllers;

use Core\Controller;
use Core\Database;
use PDO;

class AdminBookingController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        // Require admin or general_admin (officer/editor might be suitable too depending on project roles)
        $this->requireRole(['admin', 'officer', 'editor']);
    }

    /**
     * Dashboard for Approvals
     */
    public function approvals(): void
    {
        $db = Database::getInstance();
        $stmt = $db->query("
            SELECT b.*, r.name as resource_name, r.type as resource_type, u.full_name as user_name
            FROM bookings b
            JOIN booking_resources r ON b.resource_id = r.id
            JOIN users u ON b.user_id = u.id
            ORDER BY b.created_at DESC
        ");
        $bookings = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $this->renderWithLayout('modules.Booking.Views.admin.approvals', 'themes.admin.layout', [
            'title' => 'จัดการคำขอจอง',
            'bookings' => $bookings
        ]);
    }

    /**
     * Update Booking Status (AJAX)
     */
    public function updateStatus(): void
    {
        $id = (int)($_POST['id'] ?? 0);
        $status = $_POST['status'] ?? '';
        $reason = $_POST['rejection_reason'] ?? null;

        if (!$id || !in_array($status, ['approved', 'rejected'])) {
            $this->json(['success' => false, 'message' => 'ข้อมูลไม่ถูกต้อง']);
            return;
        }

        $db = Database::getInstance();
        try {
            $db->beginTransaction();
            $stmt = $db->prepare("
                UPDATE bookings 
                SET status = :status, rejection_reason = :reason, updated_at = CURRENT_TIMESTAMP 
                WHERE id = :id
            ");
            
            $success = $stmt->execute([
                'status' => $status,
                'reason' => $reason,
                'id' => $id
            ]);
            $db->commit();
            $this->json(['success' => true]);
        } catch (\Exception $e) {
            if ($db->inTransaction()) $db->rollBack();
            $this->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    /**
     * Resource Management
     */
    public function resources(): void
    {
        $db = Database::getInstance();
        $stmt = $db->query("SELECT * FROM booking_resources ORDER BY type, name");
        $resources = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $this->renderWithLayout('modules.Booking.Views.admin.resources', 'themes.admin.layout', [
            'title' => 'จัดการทรัพยากร',
            'resources' => $resources
        ]);
    }

    /**
     * Store new resource
     */
    public function storeResource(): void
    {
        $type = $_POST['type'] ?? '';
        $name = $_POST['name'] ?? '';
        $capacity = (int)($_POST['capacity'] ?? 0);
        $license = $_POST['license_plate'] ?? null;
        $status = $_POST['status'] ?? 'available';
        $desc = $_POST['description'] ?? null;

        if (!$type || !$name) {
            $this->json(['success' => false, 'message' => 'กรุณากรอกข้อมูลให้ครบถ้วน']);
            return;
        }

        $db = Database::getInstance();
        $stmt = $db->prepare("
            INSERT INTO booking_resources (type, name, capacity, license_plate, status, description)
            VALUES (:type, :name, :capacity, :license, :status, :desc)
        ");
        
        $success = $stmt->execute([
            'type' => $type,
            'name' => $name,
            'capacity' => $capacity,
            'license' => $license,
            'status' => $status,
            'desc' => $desc
        ]);

        $this->json(['success' => $success]);
    }

    /**
     * Update existing resource
     */
    public function updateResource(): void
    {
        $id = (int)($_POST['id'] ?? 0);
        $type = $_POST['type'] ?? '';
        $name = $_POST['name'] ?? '';
        $capacity = (int)($_POST['capacity'] ?? 0);
        $license = $_POST['license_plate'] ?? null;
        $status = $_POST['status'] ?? 'available';
        $desc = $_POST['description'] ?? null;

        if (!$id || !$type || !$name) {
            $this->json(['success' => false, 'message' => 'ข้อมูลไม่ถูกต้อง']);
            return;
        }

        $db = Database::getInstance();
        $stmt = $db->prepare("
            UPDATE booking_resources 
            SET type = :type, name = :name, capacity = :capacity, license_plate = :license, status = :status, description = :desc, updated_at = CURRENT_TIMESTAMP
            WHERE id = :id
        ");
        
        $success = $stmt->execute([
            'type' => $type,
            'name' => $name,
            'capacity' => $capacity,
            'license' => $license,
            'status' => $status,
            'desc' => $desc,
            'id' => $id
        ]);

        $this->json(['success' => $success]);
    }

    /**
     * Delete resource
     */
    public function deleteResource(): void
    {
        $id = (int)($_POST['id'] ?? 0);
        if (!$id) {
            $this->json(['success' => false, 'message' => 'ID ไม่ถูกต้อง']);
            return;
        }

        $db = Database::getInstance();
        $stmt = $db->prepare("DELETE FROM booking_resources WHERE id = :id");
        $success = $stmt->execute(['id' => $id]);

        $this->json(['success' => $success]);
    }
}
