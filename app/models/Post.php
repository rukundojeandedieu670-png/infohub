<?php
/**
 * Post Model
 */

require_once __DIR__ . '/../../core/Model.php';

class Post extends Model {
    protected $table = 'posts';

    /**
     * Get published posts
     */
    public function getPublished($limit = 10, $offset = 0) {
        $this->db->prepare("
            SELECT p.*, u.first_name, u.last_name, c.name as category_name, c.slug as category_slug
            FROM {$this->table} p
            LEFT JOIN users u ON p.author_id = u.id
            LEFT JOIN categories c ON p.category_id = c.id
            WHERE p.status = 'published'
            ORDER BY p.published_at DESC
            LIMIT ? OFFSET ?
        ");
        $this->db->bind('i', $limit);
        $this->db->bind('i', $offset);
        $this->db->execute();
        return $this->db->resultSet();
    }

    /**
     * Get featured posts
     */
    public function getFeatured($limit = 5) {
        $this->db->prepare("
            SELECT p.*, u.first_name, u.last_name, c.name as category_name
            FROM {$this->table} p
            LEFT JOIN users u ON p.author_id = u.id
            LEFT JOIN categories c ON p.category_id = c.id
            WHERE p.status = 'published' AND p.is_featured = 1
            ORDER BY p.published_at DESC
            LIMIT ?
        ");
        $this->db->bind('i', $limit);
        $this->db->execute();
        return $this->db->resultSet();
    }

    /**
     * Get by slug with author and category
     */
    public function getBySlug($slug) {
        $this->db->prepare("
            SELECT p.*, u.first_name, u.last_name, c.name as category_name, c.slug as category_slug
            FROM {$this->table} p
            LEFT JOIN users u ON p.author_id = u.id
            LEFT JOIN categories c ON p.category_id = c.id
            WHERE p.slug = ? AND p.status = 'published'
        ");
        $this->db->bind('s', $slug);
        $this->db->execute();
        return $this->db->single();
    }

    /**
     * Increment views
     */
    public function incrementViews($postId) {
        $this->db->prepare("UPDATE {$this->table} SET views_count = views_count + 1 WHERE id = ?");
        $this->db->bind('i', $postId);
        $this->db->execute();
    }

    /**
     * Get by category
     */
    public function getByCategory($categorySlug, $limit = 10, $offset = 0) {
        $this->db->prepare("
            SELECT p.*, u.first_name, u.last_name, c.name as category_name
            FROM {$this->table} p
            LEFT JOIN users u ON p.author_id = u.id
            LEFT JOIN categories c ON p.category_id = c.id
            WHERE c.slug = ? AND p.status = 'published'
            ORDER BY p.published_at DESC
            LIMIT ? OFFSET ?
        ");
        $this->db->bind('s', $categorySlug);
        $this->db->bind('i', $limit);
        $this->db->bind('i', $offset);
        $this->db->execute();
        return $this->db->resultSet();
    }

    /**
     * Create post
     */
    public function createPost($data) {
        $slug = $data['slug'] ?? $this->generateSlug($data['title']);
        
        return $this->insert([
            'title' => $data['title'],
            'slug' => $slug,
            'excerpt' => $data['excerpt'] ?? '',
            'content' => $data['content'],
            'featured_image' => $data['featured_image'] ?? null,
            'category_id' => $data['category_id'] ?? null,
            'author_id' => $data['author_id'],
            'status' => $data['status'] ?? 'draft',
            'featured' => $data['featured'] ?? false,
            'published_at' => $data['published_at'] ?? null
        ]);
    }

    /**
     * Get all posts with author info
     */
    public function getAllWithAuthor($limit = 10, $offset = 0) {
        $this->db->prepare("
            SELECT p.*, u.name as author_name, c.name as category_name
            FROM {$this->table} p
            LEFT JOIN users u ON p.author_id = u.id
            LEFT JOIN categories c ON p.category_id = c.id
            ORDER BY p.created_at DESC
            LIMIT ? OFFSET ?
        ");
        $this->db->bind('i', $limit);
        $this->db->bind('i', $offset);
        $this->db->execute();
        return $this->db->resultSet();
    }

    /**
     * Count posts by status
     */
    public function countByStatus($status) {
        $this->db->prepare("SELECT COUNT(*) as count FROM {$this->table} WHERE status = ?");
        $this->db->bind('s', $status);
        $this->db->execute();
        $result = $this->db->single();
        return $result['count'] ?? 0;
    }

    /**
     * Count featured posts
     */
    public function countFeatured() {
        $this->db->prepare("SELECT COUNT(*) as count FROM {$this->table} WHERE featured = 1");
        $this->db->execute();
        $result = $this->db->single();
        return $result['count'] ?? 0;
    }

    /**
     * Generate SEO slug
     */
    protected function generateSlug($text) {
        $slug = strtolower($text);
        $slug = preg_replace('/[^a-z0-9]+/', '-', $slug);
        $slug = trim($slug, '-');
        return substr($slug, 0, 100);
    }

    /**
     * Get trending posts (most viewed in last N days)
     */
    public function getTrending($limit = 10, $days = 7) {
        $this->db->prepare("
            SELECT p.*, u.first_name, u.last_name, c.name as category_name, c.slug as category_slug
            FROM {$this->table} p
            LEFT JOIN users u ON p.author_id = u.id
            LEFT JOIN categories c ON p.category_id = c.id
            WHERE p.status = 'published' 
            AND p.published_at >= DATE_SUB(NOW(), INTERVAL ? DAY)
            ORDER BY p.views_count DESC, p.published_at DESC
            LIMIT ?
        ");
        $this->db->bind('i', $days);
        $this->db->bind('i', $limit);
        $this->db->execute();
        return $this->db->resultSet();
    }

    /**
     * Get breaking news (most recent featured post)
     */
    public function getBreakingNews($limit = 8) {
        $this->db->prepare("
            SELECT p.*, u.first_name, u.last_name, c.name as category_name, c.slug as category_slug
            FROM {$this->table} p
            LEFT JOIN users u ON p.author_id = u.id
            LEFT JOIN categories c ON p.category_id = c.id
            WHERE p.status = 'published'
            AND (
                p.is_featured = 1
                OR p.published_at >= DATE_SUB(NOW(), INTERVAL 7 DAY)
            )
            ORDER BY p.is_featured DESC, p.published_at DESC
            LIMIT ?
        ");
        $this->db->bind('i', $limit);
        $this->db->execute();
        return $this->db->resultSet();
    }

    /**
     * Search posts by title, content, or excerpt
     */
    public function search($query, $limit = 20, $offset = 0) {
        $searchTerm = '%' . $query . '%';
        $this->db->prepare("
            SELECT p.*, u.first_name, u.last_name, c.name as category_name, c.slug as category_slug
            FROM {$this->table} p
            LEFT JOIN users u ON p.author_id = u.id
            LEFT JOIN categories c ON p.category_id = c.id
            WHERE p.status = 'published' 
            AND (p.title LIKE ? OR p.content LIKE ? OR p.excerpt LIKE ?)
            ORDER BY p.published_at DESC
            LIMIT ? OFFSET ?
        ");
        $this->db->bind('s', $searchTerm);
        $this->db->bind('s', $searchTerm);
        $this->db->bind('s', $searchTerm);
        $this->db->bind('i', $limit);
        $this->db->bind('i', $offset);
        $this->db->execute();
        return $this->db->resultSet();
    }

    /**
     * Count search results
     */
    public function searchCount($query) {
        $searchTerm = '%' . $query . '%';
        $this->db->prepare("
            SELECT COUNT(*) as count FROM {$this->table}
            WHERE status = 'published' 
            AND (title LIKE ? OR content LIKE ? OR excerpt LIKE ?)
        ");
        $this->db->bind('s', $searchTerm);
        $this->db->bind('s', $searchTerm);
        $this->db->bind('s', $searchTerm);
        $this->db->execute();
        $result = $this->db->single();
        return $result['count'] ?? 0;
    }

    /**
     * Get featured posts by category
     */
    public function getFeaturedByCategory($categoryId, $limit = 3) {
        $this->db->prepare("
            SELECT p.*, u.first_name, u.last_name, c.name as category_name
            FROM {$this->table} p
            LEFT JOIN users u ON p.author_id = u.id
            LEFT JOIN categories c ON p.category_id = c.id
            WHERE p.status = 'published' AND p.is_featured = 1 AND p.category_id = ?
            ORDER BY p.published_at DESC
            LIMIT ?
        ");
        $this->db->bind('i', $categoryId);
        $this->db->bind('i', $limit);
        $this->db->execute();
        return $this->db->resultSet();
    }

    /**
     * Get related articles (by category, excluding current)
     */
    public function getRelated($categoryId, $excludePostId, $limit = 5) {
        $this->db->prepare("
            SELECT p.*, u.first_name, u.last_name, c.name as category_name
            FROM {$this->table} p
            LEFT JOIN users u ON p.author_id = u.id
            LEFT JOIN categories c ON p.category_id = c.id
            WHERE p.status = 'published' 
            AND p.category_id = ? 
            AND p.id != ?
            ORDER BY p.published_at DESC
            LIMIT ?
        ");
        $this->db->bind('i', $categoryId);
        $this->db->bind('i', $excludePostId);
        $this->db->bind('i', $limit);
        $this->db->execute();
        return $this->db->resultSet();
    }

    /**
     * Count posts by category
     */
    public function countByCategory($categoryId) {
        $this->db->prepare("
            SELECT COUNT(*) as count FROM {$this->table} 
            WHERE status = 'published' AND category_id = ?
        ");
        $this->db->bind('i', $categoryId);
        $this->db->execute();
        $result = $this->db->single();
        return $result['count'] ?? 0;
    }

    /**
     * Get by category ID (not slug)
     */
    public function getByCategoryId($categoryId, $limit = 10, $offset = 0) {
        $this->db->prepare("
            SELECT p.*, u.first_name, u.last_name, c.name as category_name
            FROM {$this->table} p
            LEFT JOIN users u ON p.author_id = u.id
            LEFT JOIN categories c ON p.category_id = c.id
            WHERE p.category_id = ? AND p.status = 'published'
            ORDER BY p.published_at DESC
            LIMIT ? OFFSET ?
        ");
        $this->db->bind('i', $categoryId);
        $this->db->bind('i', $limit);
        $this->db->bind('i', $offset);
        $this->db->execute();
        return $this->db->resultSet();
    }

    /**
     * Add bookmark
     */
    public function addBookmark($userId, $postId) {
        $this->db->prepare("
            INSERT IGNORE INTO post_bookmarks (user_id, post_id, created_at)
            VALUES (?, ?, NOW())
        ");
        $this->db->bind('i', $userId);
        $this->db->bind('i', $postId);
        return $this->db->execute();
    }

    /**
     * Remove bookmark
     */
    public function removeBookmark($userId, $postId) {
        $this->db->prepare("
            DELETE FROM post_bookmarks 
            WHERE user_id = ? AND post_id = ?
        ");
        $this->db->bind('i', $userId);
        $this->db->bind('i', $postId);
        return $this->db->execute();
    }

    /**
     * Check if post is bookmarked by user
     */
    public function isBookmarked($userId, $postId) {
        $this->db->prepare("
            SELECT id FROM post_bookmarks 
            WHERE user_id = ? AND post_id = ?
        ");
        $this->db->bind('i', $userId);
        $this->db->bind('i', $postId);
        $this->db->execute();
        return $this->db->single() !== null;
    }

    /**
     * Get user's bookmarked articles
     */
    public function getUserBookmarks($userId, $limit = 15, $offset = 0) {
        $this->db->prepare("
            SELECT p.*, u.first_name, u.last_name, c.name as category_name, c.slug as category_slug
            FROM {$this->table} p
            LEFT JOIN users u ON p.author_id = u.id
            LEFT JOIN categories c ON p.category_id = c.id
            INNER JOIN post_bookmarks pb ON p.id = pb.post_id
            WHERE pb.user_id = ? AND p.status = 'published'
            ORDER BY pb.created_at DESC
            LIMIT ? OFFSET ?
        ");
        $this->db->bind('i', $userId);
        $this->db->bind('i', $limit);
        $this->db->bind('i', $offset);
        $this->db->execute();
        return $this->db->resultSet();
    }

    /**
     * Count user's bookmarks
     */
    public function countUserBookmarks($userId) {
        $this->db->prepare("
            SELECT COUNT(*) as count FROM post_bookmarks 
            WHERE user_id = ?
        ");
        $this->db->bind('i', $userId);
        $this->db->execute();
        $result = $this->db->single();
        return $result['count'] ?? 0;
    }

    /**
     * Subscribe email to newsletter
     */
    public function subscribeNewsletter($email) {
        $this->db->prepare("
            INSERT INTO newsletter_subscribers (email, created_at)
            VALUES (?, NOW())
        ");
        $this->db->bind('s', $email);
        return $this->db->execute();
    }

    /**
     * Check if email is newsletter subscriber
     */
    public function isNewsletterSubscriber($email) {
        $this->db->prepare("
            SELECT id FROM newsletter_subscribers 
            WHERE email = ?
        ");
        $this->db->bind('s', $email);
        $this->db->execute();
        return $this->db->single() !== null;
    }

    /**
     * Get personalized recommendations for user
     */
    public function getPersonalizedRecommendations($userId, $limit = 10) {
        // Get categories user has read most
        $this->db->prepare("
            SELECT p.*, u.first_name, u.last_name, c.name as category_name, c.slug as category_slug,
                   COUNT(DISTINCT pv.id) as read_count
            FROM {$this->table} p
            LEFT JOIN users u ON p.author_id = u.id
            LEFT JOIN categories c ON p.category_id = c.id
            LEFT JOIN post_views pv ON p.id = pv.post_id AND pv.user_id = ?
            WHERE p.status = 'published'
            GROUP BY p.id
            ORDER BY read_count DESC, p.views_count DESC
            LIMIT ?
        ");
        $this->db->bind('i', $userId);
        $this->db->bind('i', $limit);
        $this->db->execute();
        return $this->db->resultSet();
    }

    /**
     * Report content (abuse/spam)
     */
    public function reportContent($postId, $userId, $reason, $message = '') {
        $this->db->prepare("
            INSERT INTO content_reports (post_id, user_id, reason, message, created_at)
            VALUES (?, ?, ?, ?, NOW())
        ");
        $this->db->bind('i', $postId);
        $this->db->bind('i', $userId);
        $this->db->bind('s', $reason);
        $this->db->bind('s', $message);
        return $this->db->execute();
    }

    /**
     * Get scheduled posts
     */
    public function getScheduledPosts($limit = 10) {
        $this->db->prepare("
            SELECT p.*, u.first_name, u.last_name, c.name as category_name
            FROM {$this->table} p
            LEFT JOIN users u ON p.author_id = u.id
            LEFT JOIN categories c ON p.category_id = c.id
            WHERE p.status = 'scheduled' 
            AND p.published_at > NOW()
            ORDER BY p.published_at ASC
            LIMIT ?
        ");
        $this->db->bind('i', $limit);
        $this->db->execute();
        return $this->db->resultSet();
    }
}
