<?php
/**
 * Scholarship Model
 */

require_once __DIR__ . '/../../core/Model.php';

class Scholarship extends Model {
    protected $table = 'scholarships';

    public function getActiveScholarships($limit = 10, $offset = 0) {
        $this->db->prepare("\n            SELECT s.*, u.first_name, u.last_name,
                CONCAT(COALESCE(u.first_name, ''), ' ', COALESCE(u.last_name, '')) AS posted_by_name
            FROM {$this->table} s
            LEFT JOIN users u ON s.posted_by = u.id
            WHERE s.is_active = 1
            ORDER BY s.application_deadline ASC, s.views_count DESC, s.created_at DESC
            LIMIT ? OFFSET ?
        ");
        $this->db->bind('i', $limit);
        $this->db->bind('i', $offset);
        $this->db->execute();
        return $this->db->resultSet();
    }

    public function getBySlug($slug) {
        $this->db->prepare("\n            SELECT s.*, u.first_name, u.last_name,
                CONCAT(COALESCE(u.first_name, ''), ' ', COALESCE(u.last_name, '')) AS posted_by_name
            FROM {$this->table} s
            LEFT JOIN users u ON s.posted_by = u.id
            WHERE s.slug = ? AND s.is_active = 1
        ");
        $this->db->bind('s', $slug);
        $this->db->execute();
        return $this->db->single();
    }

    public function incrementViews($id) {
        $this->db->prepare("UPDATE {$this->table} SET views_count = views_count + 1 WHERE id = ?");
        $this->db->bind('i', $id);
        $this->db->execute();
    }
}
