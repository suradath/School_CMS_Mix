<?php
declare(strict_types=1);

namespace Modules\Saraban\Models;

use Core\Database;

class SarabanDocument
{
    public static function find(int $id): ?array
    {
        return Database::fetch("SELECT d.*, t.name as type_name, t.slug as type_slug 
                                FROM saraban_documents d 
                                JOIN saraban_types t ON d.type_id = t.id 
                                WHERE d.id = ?", [$id]);
    }

    public static function create(array $data): int|false
    {
        $sql = "INSERT INTO saraban_documents (type_id, doc_no, book_no, title, origin, priority, doc_date, received_date, file_url, created_by, budget_year) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        
        $params = [
            $data['type_id'],
            $data['doc_no'],
            $data['book_no'] ?? null,
            $data['title'],
            $data['origin'] ?? null,
            $data['priority'] ?? 'normal',
            $data['doc_date'] ?? null,
            $data['received_date'] ?? null,
            $data['file_url'] ?? null,
            $data['created_by'],
            $data['budget_year']
        ];

        if (Database::query($sql, $params)) {
            $id = (int)Database::getInstance()->lastInsertId();
            SarabanType::incrementNumber((int)$data['type_id']);
            return $id;
        }

        return false;
    }

    public static function getInbox(int $personnelId, int $departmentId = 0, array $filters = []): array
    {
        $sql = "SELECT d.*, t.name as type_name, r.status as read_status, r.acknowledged_at
                FROM saraban_documents d
                JOIN saraban_types t ON d.type_id = t.id
                JOIN saraban_receivers r ON d.id = r.document_id
                WHERE (r.personnel_id = ? OR r.department_id = ?)
                AND d.status = 'active'";
        
        $params = [$personnelId, $departmentId];

        if (!empty($filters['q'])) {
            $sql .= " AND (d.title LIKE ? OR d.doc_no LIKE ? OR d.book_no LIKE ?)";
            $params[] = '%' . $filters['q'] . '%';
            $params[] = '%' . $filters['q'] . '%';
            $params[] = '%' . $filters['q'] . '%';
        }

        if (!empty($filters['start_date'])) {
            $sql .= " AND d.doc_date >= ?";
            $params[] = $filters['start_date'];
        }

        if (!empty($filters['end_date'])) {
            $sql .= " AND d.doc_date <= ?";
            $params[] = $filters['end_date'];
        }

        $sql .= " ORDER BY d.created_at DESC";
        return Database::fetchAll($sql, $params);
    }

    public static function getAllByType(string $typeSlug, array $filters = [], array $userAuth = []): array
    {
        $sql = "SELECT d.*, t.name as type_name 
                FROM saraban_documents d
                JOIN saraban_types t ON d.type_id = t.id";
        
        $params = [];
        $where = ["t.slug = ?"];
        $params[] = $typeSlug;

        // Restriction logic: if not admin or officer, only see what's addressed to them
        if (!empty($userAuth) && !$userAuth['is_privileged']) {
            $sql .= " JOIN saraban_receivers r ON d.id = r.document_id";
            $where[] = "(r.personnel_id = ? OR r.department_id = ?)";
            $params[] = $userAuth['personnel_id'];
            $params[] = $userAuth['department_id'];
        }

        if (!empty($filters['q'])) {
            $where[] = "(d.title LIKE ? OR d.doc_no LIKE ? OR d.book_no LIKE ?)";
            $params[] = '%' . $filters['q'] . '%';
            $params[] = '%' . $filters['q'] . '%';
            $params[] = '%' . $filters['q'] . '%';
        }

        if (!empty($filters['start_date'])) {
            $where[] = "d.doc_date >= ?";
            $params[] = $filters['start_date'];
        }

        if (!empty($filters['end_date'])) {
            $where[] = "d.doc_date <= ?";
            $params[] = $filters['end_date'];
        }

        if (!empty($filters['status'])) {
            $where[] = "d.status = ?";
            $params[] = $filters['status'];
        }

        $sql .= " WHERE " . implode(" AND ", $where);
        $sql .= " ORDER BY d.created_at DESC";
        
        return Database::fetchAll($sql, $params);
    }

    public static function delete(int $id): bool
    {
        return (bool)Database::query("DELETE FROM saraban_documents WHERE id = ?", [$id]);
    }
}
