<?php
/**
 * Home Controller
 */

class HomeController extends Controller {

    public function index() {
        require_once ROOT_PATH . '/app/models/Post.php';
        require_once ROOT_PATH . '/app/models/Job.php';
        require_once ROOT_PATH . '/app/models/Business.php';

        $postModel = new Post();
        $jobModel = new Job();
        $businessModel = new Business();

        // Get featured posts
        $featured_posts = $postModel->getFeatured(3);

        // Get featured jobs
        $featured_jobs = [];
        try {
            $db = Database::getInstance();
            $db->prepare("
                SELECT j.*, c.name as category_name
                FROM jobs j
                LEFT JOIN categories c ON j.category_id = c.id
                WHERE j.is_featured = 1 AND j.status = 'open' AND j.deadline > NOW()
                ORDER BY j.published_at DESC
                LIMIT 6
            ");
            $db->execute();
            $featured_jobs = $db->resultSet();
        } catch (Exception $e) {
            // Silent fail
        }

        // Get featured businesses
        $featured_businesses = [];
        try {
            $db = Database::getInstance();
            $db->prepare("
                SELECT b.*, c.name as category_name
                FROM businesses b
                LEFT JOIN categories c ON b.category_id = c.id
                WHERE b.is_featured = 1 AND b.verification_status = 'verified' AND b.is_active = 1
                LIMIT 3
            ");
            $db->execute();
            $featured_businesses = $db->resultSet();
        } catch (Exception $e) {
            // Silent fail
        }

        // Get flash message
        $flash = $this->getFlash();

        $this->view('home', [
            'featured_posts' => $featured_posts,
            'featured_jobs' => $featured_jobs,
            'featured_businesses' => $featured_businesses,
            'flash' => $flash,
            'page_title' => 'Home | InfoHub',
            'user' => $this->user
        ]);
    }
}
