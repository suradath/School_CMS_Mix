<?php
declare(strict_types=1);

namespace Modules\Submissions\Models;

use Core\Database;

class Submission
{
    /**
     * Get all submission topics
     */
    public static function getAllTopics(array $filters = []): array
    {
        $sql = "SELECT * FROM submission_topics WHERE 1=1";
        $params = [];

        if (!empty($filters['status'])) {
            $sql .= " AND status = ?";
            $params[] = $filters['status'];
        }

        if (!empty($filters['academic_year'])) {
            $sql .= " AND academic_year = ?";
            $params[] = $filters['academic_year'];
        }

        if (!empty($filters['semester'])) {
            $sql .= " AND semester = ?";
            $params[] = $filters['semester'];
        }

        $sql .= " ORDER BY academic_year DESC, semester DESC, id DESC";
        $topics = Database::fetchAll($sql, $params);

        foreach ($topics as &$topic) {
            $topic['allowed_files'] = self::getTopicAllowedFiles((int)$topic['id']);
        }

        return $topics;
    }

    /**
     * Get a single topic by ID
     */
    public static function getTopic(int $id): array|false
    {
        $topic = Database::fetch("SELECT * FROM submission_topics WHERE id = ?", [$id]);
        if ($topic) {
            $topic['allowed_files'] = self::getTopicAllowedFiles($id);
        }
        return $topic;
    }

    /**
     * Get allowed file extensions for a topic
     */
    public static function getTopicAllowedFiles(int $topicId): array
    {
        $results = Database::fetchAll("SELECT extension FROM topic_allowed_files WHERE topic_id = ?", [$topicId]);
        return array_column($results, 'extension');
    }

    /**
     * Create or Update Topic
     */
    public static function saveTopic(array $data): int|bool
    {
        if (!empty($data['id'])) {
            // Update
            Database::query("
                UPDATE submission_topics 
                SET title = ?, description = ?, semester = ?, academic_year = ?, max_file_size = ?, status = ? 
                WHERE id = ?
            ", [
                $data['title'],
                $data['description'],
                $data['semester'],
                $data['academic_year'],
                $data['max_file_size'],
                $data['status'],
                $data['id']
            ]);
            $topicId = (int)$data['id'];
        } else {
            // Insert
            $topicId = (int)Database::insert("
                INSERT INTO submission_topics (title, description, semester, academic_year, max_file_size, status) 
                VALUES (?, ?, ?, ?, ?, ?)
            ", [
                $data['title'],
                $data['description'],
                $data['semester'],
                $data['academic_year'],
                $data['max_file_size'],
                $data['status']
            ]);
        }

        if ($topicId && isset($data['allowed_files'])) {
            self::syncTopicFiles($topicId, $data['allowed_files']);
        }

        return $topicId;
    }

    /**
     * Sync allowed file extensions
     */
    public static function syncTopicFiles(int $topicId, array $extensions): void
    {
        Database::query("DELETE FROM topic_allowed_files WHERE topic_id = ?", [$topicId]);
        foreach ($extensions as $ext) {
            Database::query("INSERT INTO topic_allowed_files (topic_id, extension) VALUES (?, ?)", [$topicId, trim($ext, '.')]);
        }
    }

    /**
     * Delete topic
     */
    public static function deleteTopic(int $id): bool
    {
        return (bool)Database::query("DELETE FROM submission_topics WHERE id = ?", [$id]);
    }

    /**
     * Get submission history for a user
     */
    public static function getUserSubmissions(int $userId): array
    {
        return Database::fetchAll("
            SELECT s.*, t.title as topic_title, t.semester, t.academic_year 
            FROM document_submissions s
            JOIN submission_topics t ON s.topic_id = t.id
            WHERE s.user_id = ?
            ORDER BY s.submitted_at DESC
        ", [$userId]);
    }

    /**
     * Get specific submission
     */
    public static function getSubmission(int $id): array|false
    {
        return Database::fetch("
            SELECT s.*, t.title as topic_title, u.full_name as user_name 
            FROM document_submissions s
            JOIN submission_topics t ON s.topic_id = t.id
            JOIN users u ON s.user_id = u.id
            WHERE s.id = ?
        ", [$id]);
    }

    /**
     * Get all submissions for monitor (Academic/Director)
     */
    public static function getAllSubmissions(int $topicId = null): array
    {
        $sql = "
            SELECT u.id as user_id, u.full_name, p.position, d.name as department,
                   s.id as submission_id, s.status, s.submitted_at, s.file_path, s.original_filename
            FROM users u
            LEFT JOIN personnel p ON u.personnel_id = p.id
            LEFT JOIN departments d ON p.department_id = d.id
            LEFT JOIN document_submissions s ON u.id = s.user_id " . ($topicId ? " AND s.topic_id = ?" : "") . "
            WHERE u.status = 'active'
            ORDER BY u.full_name ASC
        ";
        
        $params = $topicId ? [$topicId] : [];
        return Database::fetchAll($sql, $params);
    }

    /**
     * Save submission
     */
    public static function saveSubmission(array $data): int|bool
    {
        if (!empty($data['id'])) {
            // Update (Teacher re-submitting or Academic reviewing)
            $sql = "UPDATE document_submissions SET status = ?, feedback = ?, updated_at = NOW()";
            $params = [$data['status'], $data['feedback'] ?? null];
            
            if (isset($data['file_path'])) {
                $sql .= ", file_path = ?, original_filename = ?, mime_type = ?";
                $params[] = $data['file_path'];
                $params[] = $data['original_filename'];
                $params[] = $data['mime_type'];
            }
            
            $sql .= " WHERE id = ?";
            $params[] = $data['id'];
            
            Database::query($sql, $params);
            return (int)$data['id'];
        } else {
            // New Submission
            return (int)Database::insert("
                INSERT INTO document_submissions (topic_id, user_id, file_path, original_filename, mime_type, status) 
                VALUES (?, ?, ?, ?, ?, ?)
            ", [
                $data['topic_id'],
                $data['user_id'],
                $data['file_path'],
                $data['original_filename'],
                $data['mime_type'],
                $data['status'] ?? 'pending'
            ]);
        }
    }

    /**
     * Get status label and color
     */
    public static function getStatusInfo(string $status): array
    {
        switch ($status) {
            case 'approved':
                return ['label' => 'อนุมัติแล้ว', 'color' => 'green', 'bg' => 'bg-emerald-100 text-emerald-700'];
            case 'revision':
                return ['label' => 'ปรับปรุง', 'color' => 'red', 'bg' => 'bg-red-100 text-red-700'];
            case 'pending':
                return ['label' => 'ส่งแล้ว/รอตรวจ', 'color' => 'orange', 'bg' => 'bg-amber-100 text-amber-700'];
            default:
                return ['label' => 'ยังไม่ส่ง', 'color' => 'gray', 'bg' => 'bg-gray-100 text-gray-500'];
        }
    }
}
