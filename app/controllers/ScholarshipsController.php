<?php
/**
 * Scholarships Controller
 */

class ScholarshipsController extends Controller {

    public function index($page = 1) {
        require_once ROOT_PATH . '/app/models/Scholarship.php';
        require_once ROOT_PATH . '/app/models/Post.php';

        $scholarshipModel = new Scholarship();
        $postModel = new Post();
        $limit = 12;
        $offset = ($page - 1) * $limit;

        $scholarships = $scholarshipModel->getActiveScholarships($limit, $offset);
        $totalScholarships = $scholarshipModel->count('is_active', 1);
        $totalPages = max(1, ceil($totalScholarships / $limit));
        $breakingNews = $postModel->getBreakingNews(8);

        $this->view('scholarships/index', [
            'scholarships' => $scholarships,
            'page' => $page,
            'totalPages' => $totalPages,
            'totalScholarships' => $totalScholarships,
            'breakingNews' => $breakingNews,
            'page_title' => 'Scholarships | InfoHub Rwanda',
            'page_description' => 'Browse active scholarship opportunities for Rwandan students and professionals.',
            'user' => $this->user
        ]);
    }

    public function show($slug) {
        require_once ROOT_PATH . '/app/models/Scholarship.php';
        require_once ROOT_PATH . '/app/models/Post.php';

        $scholarshipModel = new Scholarship();
        $postModel = new Post();
        $scholarship = $scholarshipModel->getBySlug($slug);

        if (!$scholarship) {
            http_response_code(404);
            $this->view('errors/404');
            return;
        }

        $breakingNews = $postModel->getBreakingNews(8);
        $scholarshipModel->incrementViews($scholarship['id']);

        $this->view('scholarships/show', [
            'scholarship' => $scholarship,
            'breakingNews' => $breakingNews,
            'page_title' => htmlspecialchars($scholarship['title']) . ' | Scholarships | InfoHub',
            'page_description' => htmlspecialchars(substr($scholarship['description'], 0, 160)),
            'user' => $this->user
        ]);
    }
}
