<?php
declare(strict_types=1);

namespace Modules\Calendar\Models;

use Core\Database;

class Calendar
{
    /**
     * Get all events
     */
    public static function getAll(): array
    {
        return Database::fetchAll("SELECT * FROM academic_calendar ORDER BY start_date ASC, start_time ASC");
    }

    /**
     * Find event by ID
     */
    public static function find(int $id): array|false
    {
        return Database::fetch("SELECT * FROM academic_calendar WHERE id = ?", [$id]);
    }

    /**
     * Create new event
     */
    public static function create(array $data): string|false
    {
        $sql = "INSERT INTO academic_calendar 
                (title, description, start_date, end_date, start_time, end_time, responsible_person, color) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        
        return Database::insert($sql, [
            $data['title'],
            $data['description'] ?? null,
            $data['start_date'],
            $data['end_date'] ?? null,
            $data['start_time'] ?? null,
            $data['end_time'] ?? null,
            $data['responsible_person'] ?? null,
            $data['color'] ?? '#1d4ed8'
        ]);
    }

    /**
     * Update event
     */
    public static function update(int $id, array $data): bool
    {
        $sql = "UPDATE academic_calendar SET 
                title = ?, 
                description = ?, 
                start_date = ?, 
                end_date = ?, 
                start_time = ?, 
                end_time = ?, 
                responsible_person = ?, 
                color = ? 
                WHERE id = ?";
        
        Database::query($sql, [
            $data['title'],
            $data['description'] ?? null,
            $data['start_date'],
            $data['end_date'] ?? null,
            $data['start_time'] ?? null,
            $data['end_time'] ?? null,
            $data['responsible_person'] ?? null,
            $data['color'] ?? '#1d4ed8',
            $id
        ]);
        
        return true;
    }

    /**
     * Delete event
     */
    public static function delete(int $id): bool
    {
        Database::query("DELETE FROM academic_calendar WHERE id = ?", [$id]);
        return true;
    }

    /**
     * Get events for FullCalendar (API format)
     */
    public static function getForCalendar(): array
    {
        $events = self::getAll();
        $formatted = [];
        
        foreach ($events as $event) {
            // FullCalendar needs ISO dates. 
            // If end_date exists, FullCalendar end is exclusive for all-day events, 
            // but for timed events it works fine.
            $formatted[] = [
                'id' => $event['id'],
                'title' => $event['title'],
                'start' => $event['start_date'] . ($event['start_time'] ? 'T' . $event['start_time'] : ''),
                'end' => $event['end_date'] ? $event['end_date'] . ($event['end_time'] ? 'T' . $event['end_time'] : '') : null,
                'color' => $event['color'],
                'extendedProps' => [
                    'description' => $event['description'],
                    'responsible' => $event['responsible_person'],
                    'startTime' => $event['start_time'],
                    'endTime' => $event['end_time'],
                    'startDate' => $event['start_date'],
                    'endDate' => $event['end_date']
                ]
            ];
        }
        
        return $formatted;
    }
}
