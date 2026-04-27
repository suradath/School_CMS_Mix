<?php
declare(strict_types=1);

namespace Modules\Saraban\Models;

use Core\Database;

class SarabanMinute
{
    public static function create(array $data): bool
    {
        return (bool)Database::query(
            "INSERT INTO saraban_minutes (document_id, user_id, note, decision) VALUES (?, ?, ?, ?)",
            [
                $data['document_id'],
                $data['user_id'],
                $data['note'],
                $data['decision'] ?? 'none'
            ]
        );
    }

    public static function getByDocumentId(int $docId): array
    {
        return Database::fetchAll("
            SELECT m.*, u.full_name, p.position 
            FROM saraban_minutes m
            JOIN users u ON m.user_id = u.id
            LEFT JOIN personnel p ON u.personnel_id = p.id
            WHERE m.document_id = ?
            ORDER BY m.created_at ASC
        ", [$docId]);
    }

    public static function updateDocumentStatus(int $docId, string $status): void
    {
        Database::query("UPDATE saraban_documents SET saraban_status = ? WHERE id = ?", [$status, $docId]);
    }
}
