<?php
/**
 * Category Model
 */

require_once __DIR__ . '/../../core/Model.php';

class Category extends Model {
    protected $table = 'categories';

    /**
     * Get active categories
     */
    public function getActive() {
        return $this->findAllBy('is_active', true);
    }

    /**
     * Get by slug
     */
    public function getBySlug($slug) {
        return $this->findBy('slug', $slug);
    }

    /**
     * Get all categories ordered by name
     * Used for: navigation, category listings, dropdowns
     * 
     * @return array Array of all categories
     */
    public function getAll() {
        $this->db->prepare("SELECT * FROM {$this->table} ORDER BY name ASC");
        $this->db->execute();
        return $this->db->resultSet();
    }

    /**
     * Get category statistics (post count per category)
     * Used for: admin dashboard, category overview, trending categories
     * 
     * @return array Array of categories with post_count field
     */
    public function getStats() {
        $this->db->prepare("
            SELECT c.*, COUNT(p.id) as post_count
            FROM {$this->table} c
            LEFT JOIN posts p ON c.id = p.category_id AND p.status = 'published'
            GROUP BY c.id
            ORDER BY post_count DESC
        ");
        $this->db->execute();
        return $this->db->resultSet();
    }
}
