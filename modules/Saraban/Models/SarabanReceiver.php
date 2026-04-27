<?php
declare(strict_types=1);

namespace Modules\Saraban\Models;

use Core\Database;

class SarabanReceiver
{
    public static function distribute(int $documentId, array $receivers): void
    {
        foreach ($receivers as $receiver) {
            $sql = "INSERT INTO saraban_receivers (document_id, personnel_id, department_id) VALUES (?, ?, ?)";
            Database::query($sql, [
                $documentId,
                $receiver['personnel_id'] ?? null,
                $receiver['department_id'] ?? null
            ]);
        }
    }

    public static function acknowledge(int $documentId, int $personnelId): bool
    {
        $sql = "UPDATE saraban_receivers 
                SET status = 'read', acknowledged_at = CURRENT_TIMESTAMP 
                WHERE document_id = ? AND (personnel_id = ? OR department_id IN (SELECT department_id FROM personnel WHERE id = ?)) 
                AND acknowledged_at IS NULL";
        return (bool)Database::query($sql, [$documentId, $personnelId, $personnelId]);
    }

    public static function getReceivers(int $documentId): array
    {
        $sql = "SELECT r.*, p.name as personnel_name, d.name as department_name
                FROM saraban_receivers r
                LEFT JOIN personnel p ON r.personnel_id = p.id
                LEFT JOIN departments d ON r.department_id = d.id
                WHERE r.document_id = ?";
        return Database::fetchAll($sql, [$documentId]);
    }

    public static function resetStatus(int $documentId): void
    {
        $sql = "UPDATE saraban_receivers SET status = 'unread', acknowledged_at = NULL WHERE document_id = ?";
        Database::query($sql, [$documentId]);
    }
}
