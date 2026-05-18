<?php
/**
 * Business Model
 */

require_once __DIR__ . '/../../core/Model.php';

class Business extends Model {
    protected $table = 'businesses';

    /**
     * Get verified businesses
     */
    public function getVerified($limit = 10, $offset = 0) {
        $this->db->prepare("
            SELECT b.*, u.first_name, u.last_name, c.name as category_name
            FROM {$this->table} b
            LEFT JOIN users u ON b.owner_id = u.id
            LEFT JOIN categories c ON b.category_id = c.id
            WHERE b.verification_status = 'verified' AND b.is_active = 1
            ORDER BY b.is_featured DESC, b.created_at DESC
            LIMIT ? OFFSET ?
        ");
        $this->db->bind('i', $limit);
        $this->db->bind('i', $offset);
        $this->db->execute();
        return $this->db->resultSet();
    }

    /**
     * Get by slug
     */
    public function getBySlug($slug) {
        $this->db->prepare("
            SELECT b.*, u.first_name, u.last_name, u.phone, u.email, c.name as category_name
            FROM {$this->table} b
            LEFT JOIN users u ON b.owner_id = u.id
            LEFT JOIN categories c ON b.category_id = c.id
            WHERE b.slug = ?
        ");
        $this->db->bind('s', $slug);
        $this->db->execute();
        return $this->db->single();
    }

    /**
     * Get by owner
     */
    public function getByOwner($ownerId) {
        return $this->findBy('owner_id', $ownerId);
    }

    /**
     * Get pending verification
     */
    public function getPending($limit = null, $offset = 0) {
        $query = "
            SELECT b.*, u.first_name, u.last_name, c.name as category_name
            FROM {$this->table} b
            LEFT JOIN users u ON b.owner_id = u.id
            LEFT JOIN categories c ON b.category_id = c.id
            WHERE b.verification_status = 'pending'
            ORDER BY b.created_at ASC
        ";

        if ($limit) {
            $query .= " LIMIT ? OFFSET ?";
            $this->db->prepare($query);
            $this->db->bind('i', $limit);
            $this->db->bind('i', $offset);
        } else {
            $this->db->prepare($query);
        }

        $this->db->execute();
        return $this->db->resultSet();
    }

    /**
     * Create business
     */
    public function createBusiness($data) {
        $slug = $this->generateSlug($data['name']);

        return $this->insert([
            'name' => $data['name'],
            'slug' => $slug,
            'description' => $data['description'] ?? null,
            'owner_id' => $data['owner_id'],
            'category_id' => $data['category_id'],
            'location' => $data['location'] ?? null,
            'phone' => $data['phone'] ?? null,
            'email' => $data['email'] ?? null,
            'website' => $data['website'] ?? null,
            'logo' => $data['logo'] ?? null,
            'business_registration' => $data['business_registration'] ?? null,
            'tax_id' => $data['tax_id'] ?? null,
            'employees_count' => $data['employees_count'] ?? null,
            'founded_year' => $data['founded_year'] ?? null,
            'is_active' => true
        ]);
    }

    /**
     * Verify business
     */
    public function verify($businessId, $adminId) {
        $this->db->prepare("
            UPDATE {$this->table} 
            SET verification_status = 'verified', verified_by = ?, verified_at = NOW() 
            WHERE id = ?
        ");
        $this->db->bind('i', $adminId);
        $this->db->bind('i', $businessId);
        $this->db->execute();
    }

    /**
     * Reject business
     */
    public function reject($businessId, $adminId, $reason) {
        $this->db->prepare("
            UPDATE {$this->table} 
            SET verification_status = 'rejected', verified_by = ?, verified_at = NOW(), rejection_reason = ? 
            WHERE id = ?
        ");
        $this->db->bind('i', $adminId);
        $this->db->bind('s', $reason);
        $this->db->bind('i', $businessId);
        $this->db->execute();
    }
}
