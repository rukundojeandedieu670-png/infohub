<?php
/**
 * Payment Model
 */

require_once __DIR__ . '/../../core/Model.php';

class Payment extends Model {
    protected $table = 'payments';

    public function getAllWithUser($limit = 50, $offset = 0) {
        $this->db->prepare(
            "SELECT p.*, u.first_name, u.last_name, u.email
            FROM {$this->table} p
            LEFT JOIN users u ON p.user_id = u.id
            ORDER BY p.created_at DESC
            LIMIT ? OFFSET ?"
        );
        $this->db->bind('i', $limit);
        $this->db->bind('i', $offset);
        $this->db->execute();
        return $this->db->resultSet();
    }

    public function getStatusSummary() {
        $this->db->prepare(
            "SELECT status, COUNT(*) as count, COALESCE(SUM(amount), 0) as total_amount
            FROM {$this->table}
            GROUP BY status"
        );
        $this->db->execute();
        return $this->db->resultSet();
    }

    public function getDonationSummary() {
        $this->db->prepare(
            "SELECT COUNT(*) as count, COALESCE(SUM(amount), 0) as total_amount
            FROM {$this->table}
            WHERE payment_type = 'donation'"
        );
        $this->db->execute();
        return $this->db->single();
    }
}
