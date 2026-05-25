<?php
/**
 * Job Model
 */

require_once __DIR__ . '/../../core/Model.php';

class Job extends Model {
    protected $table = 'jobs';

    /**
     * Get open jobs
     */
    public function getOpenJobs($limit = 10, $offset = 0) {
        $this->db->prepare("
            SELECT j.*, u.company AS company_name, c.name as category_name
            FROM {$this->table} j
            LEFT JOIN users u ON j.employer_id = u.id
            LEFT JOIN categories c ON j.category_id = c.id
            WHERE j.status = 'open' AND j.deadline > NOW()
            ORDER BY j.is_featured DESC, j.published_at DESC
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
            SELECT j.*, u.company AS company_name, u.email, u.phone, c.name as category_name
            FROM {$this->table} j
            LEFT JOIN users u ON j.employer_id = u.id
            LEFT JOIN categories c ON j.category_id = c.id
            WHERE j.slug = ?
        ");
        $this->db->bind('s', $slug);
        $this->db->execute();
        return $this->db->single();
    }

    /**
     * Get jobs by employer
     */
    public function getByEmployer($employerId, $limit = null, $offset = 0) {
        $query = "
            SELECT j.*, c.name as category_name
            FROM {$this->table} j
            LEFT JOIN categories c ON j.category_id = c.id
            WHERE j.employer_id = ?
            ORDER BY j.published_at DESC
        ";

        if ($limit) {
            $query .= " LIMIT ? OFFSET ?";
            $this->db->prepare($query);
            $this->db->bind('i', $employerId);
            $this->db->bind('i', $limit);
            $this->db->bind('i', $offset);
        } else {
            $this->db->prepare($query);
            $this->db->bind('i', $employerId);
        }

        $this->db->execute();
        return $this->db->resultSet();
    }

    /**
     * Create job
     */
    public function createJob($data) {
        $slug = $this->generateSlug($data['title']);

        return $this->insert([
            'title' => $data['title'],
            'slug' => $slug,
            'description' => $data['description'],
            'employer_id' => $data['employer_id'],
            'category_id' => $data['category_id'],
            'location' => $data['location'],
            'salary_min' => $data['salary_min'] ?? null,
            'salary_max' => $data['salary_max'] ?? null,
            'job_type' => $data['job_type'],
            'experience_level' => $data['experience_level'] ?? 'entry',
            'status' => 'open',
            'deadline' => $data['deadline'],
            'seo_meta_title' => $data['seo_meta_title'] ?? null,
            'seo_meta_description' => $data['seo_meta_description'] ?? null
        ]);
    }

    /**
     * Search jobs
     */
    public function search($keyword, $categoryId = null, $jobType = null, $limit = 10, $offset = 0) {
        $query = "
            SELECT j.*, u.company AS company_name, c.name as category_name
            FROM {$this->table} j
            LEFT JOIN users u ON j.employer_id = u.id
            LEFT JOIN categories c ON j.category_id = c.id
            WHERE j.status = 'open' AND j.deadline > NOW()
            AND (j.title LIKE ? OR j.description LIKE ? OR u.company LIKE ?)
        ";

        $searchTerm = '%' . $keyword . '%';
        $params = [$searchTerm, $searchTerm, $searchTerm];
        $types = 'sss';

        if ($categoryId) {
            $query .= " AND j.category_id = ?";
            $types .= 'i';
            $params[] = $categoryId;
        }

        if ($jobType) {
            $query .= " AND j.job_type = ?";
            $types .= 's';
            $params[] = $jobType;
        }

        $query .= " ORDER BY j.is_featured DESC, j.published_at DESC LIMIT ? OFFSET ?";
        $types .= 'ii';
        $params[] = $limit;
        $params[] = $offset;

        $this->db->prepare($query);
        $this->db->bindArray($types, $params);
        $this->db->execute();
        return $this->db->resultSet();
    }
}
