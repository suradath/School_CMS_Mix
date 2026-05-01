<?php
declare(strict_types=1);

namespace Modules\Helpdesk\Models;

use Core\Database;
use PDO;

class HelpdeskModel
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    public function getCategories(): array
    {
        return $this->db->query("SELECT * FROM repair_categories ORDER BY name ASC")->fetchAll(PDO::FETCH_ASSOC);
    }

    public function createRequest(array $data): bool
    {
        $sql = "INSERT INTO repair_requests (reporter_id, category_id, location, description, photos, status) 
                VALUES (:reporter_id, :category_id, :location, :description, :photos, 'pending')";
        
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            'reporter_id' => $data['reporter_id'],
            'category_id' => $data['category_id'],
            'location' => $data['location'],
            'description' => $data['description'],
            'photos' => $data['photos'] // Expecting JSON string
        ]);
    }

    public function getMyRepairs(int $userId): array
    {
        $sql = "SELECT r.*, c.name as category_name 
                FROM repair_requests r 
                JOIN repair_categories c ON r.category_id = c.id 
                WHERE r.reporter_id = :user_id 
                ORDER BY r.created_at DESC";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['user_id' => $userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getAllRequests(): array
    {
        return $this->db->query("
            SELECT r.*, c.name as category_name, u.full_name as reporter_name 
            FROM repair_requests r 
            JOIN repair_categories c ON r.category_id = c.id 
            JOIN users u ON r.reporter_id = u.id 
            ORDER BY r.created_at DESC
        ")->fetchAll(PDO::FETCH_ASSOC);
    }

    public function updateStatus(int $id, string $status, ?string $remarks = null): bool
    {
        $this->db->beginTransaction();
        try {
            $sql = "UPDATE repair_requests 
                    SET status = :status, remarks = :remarks, 
                        resolved_at = CASE WHEN :status_check = 'fixed' THEN CURRENT_TIMESTAMP ELSE resolved_at END,
                        updated_at = CURRENT_TIMESTAMP 
                    WHERE id = :id";
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute([
                'status' => $status,
                'status_check' => $status,
                'remarks' => $remarks,
                'id' => $id
            ]);
            $this->db->commit();
            return true;
        } catch (\Exception $e) {
            if ($this->db->inTransaction()) {
                $this->db->rollBack();
            }
            throw $e; // Rethrow to be caught by controller
        }
    }

    public function addCategory(string $name, string $slug): bool
    {
        $stmt = $this->db->prepare("INSERT INTO repair_categories (name, slug) VALUES (:name, :slug)");
        return $stmt->execute(['name' => $name, 'slug' => $slug]);
    }

    public function deleteCategory(int $id): bool
    {
        // Check if category has requests
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM repair_requests WHERE category_id = :id");
        $stmt->execute(['id' => $id]);
        if ((int)$stmt->fetchColumn() > 0) {
            return false;
        }

        $stmt = $this->db->prepare("DELETE FROM repair_categories WHERE id = :id");
        return $stmt->execute(['id' => $id]);
    }

    public function deleteRequest(int $id): bool
    {
        // Get photos first to delete them
        $stmt = $this->db->prepare("SELECT photos FROM repair_requests WHERE id = :id");
        $stmt->execute(['id' => $id]);
        $photos = json_decode((string)$stmt->fetchColumn(), true);
        
        if ($photos) {
            foreach ($photos as $p) {
                $fullPath = ROOT_PATH . '/' . $p;
                if (file_exists($fullPath)) unlink($fullPath);
            }
        }

        $stmt = $this->db->prepare("DELETE FROM repair_requests WHERE id = :id");
        return $stmt->execute(['id' => $id]);
    }
}
