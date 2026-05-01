<?php
declare(strict_types=1);

namespace Modules\Booking\Models;

use Core\Database;
use PDO;

class BookingModel
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    /**
     * Get all bookings for FullCalendar
     */
    public function getEvents(string $start, string $end): array
    {
        $stmt = $this->db->prepare("
            SELECT 
                b.id,
                b.title,
                b.start_time as start,
                b.end_time as end,
                b.status,
                r.name as resource_name,
                r.type as resource_type,
                u.full_name as user_name,
                b.participants_count,
                b.details,
                b.rejection_reason
            FROM bookings b
            JOIN booking_resources r ON b.resource_id = r.id
            JOIN users u ON b.user_id = u.id
            WHERE (b.start_time >= :s1 AND b.start_time <= :e1)
               OR (b.end_time >= :s2 AND b.end_time <= :e2)
               OR (b.start_time <= :s3 AND b.end_time >= :e3)
        ");

        $stmt->execute([
            's1' => $start,
            'e1' => $end,
            's2' => $start,
            'e2' => $end,
            's3' => $start,
            'e3' => $end
        ]);
        $bookings = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $events = [];
        foreach ($bookings as $b) {
            // Color coding based on status or resource type
            $color = '#3b82f6'; // Default Blue
            if ($b['status'] === 'pending')
                $color = '#f59e0b'; // Amber
            if ($b['status'] === 'rejected')
                $color = '#ef4444'; // Red
            if ($b['status'] === 'approved') {
                $color = ($b['resource_type'] === 'room') ? '#10b981' : '#8b5cf6'; // Green for room, Purple for vehicle
            }

            $events[] = [
                'id' => $b['id'],
                'title' => $b['resource_name'] . ": " . $b['title'] . " [" . $b['participants_count'] . " คน]",
                'start' => $b['start'],
                'end' => $b['end'],
                'color' => $color,
                'extendedProps' => [
                    'resource' => $b['resource_name'],
                    'user' => $b['user_name'],
                    'status' => $b['status'],
                    'details' => $b['details'],
                    'participants' => $b['participants_count'],
                    'rejection_reason' => $b['rejection_reason']
                ]
            ];
        }

        return $events;
    }

    /**
     * Check for double booking
     */
    public function isSlotAvailable(int $resourceId, string $start, string $end, ?int $excludeId = null): bool
    {
        $sql = "
            SELECT COUNT(*) 
            FROM bookings 
            WHERE resource_id = :resource_id 
            AND status IN ('pending', 'approved')
            AND (
                (start_time < :end AND end_time > :start)
            )
        ";

        if ($excludeId) {
            $sql .= " AND id != :exclude_id";
        }

        $stmt = $this->db->prepare($sql);
        $params = [
            'resource_id' => $resourceId,
            'start' => $start,
            'end' => $end
        ];
        if ($excludeId)
            $params['exclude_id'] = $excludeId;

        $stmt->execute($params);
        return (int) $stmt->fetchColumn() === 0;
    }
}
