<?php
/**
 * Enhanced News Controller
 * Rwanda's Central Digital Information Platform
 * 
 * Features: Trending, Featured, Search, Categories, Recommendations,
 * Bookmarking, Analytics, Newsletter
 */

class NewsController extends Controller {

    /**
     * Homepage - All News with Featured Stories
     */
    public function index($page = 1, $category = null) {
        require_once ROOT_PATH . '/app/models/Post.php';
        require_once ROOT_PATH . '/app/models/Category.php';
        require_once ROOT_PATH . '/app/models/Scholarship.php';

        $postModel = new Post();
        $categoryModel = new Category();
        $scholarshipModel = new Scholarship();

        $limit = 12;
        $offset = ($page - 1) * $limit;

        // Get main posts
        $posts = $postModel->getPublished($limit, $offset);
        $totalPosts = $postModel->count('status', 'published');
        $totalPages = ceil($totalPosts / $limit);

        // Get featured posts (up to 3)
        $featuredPosts = $postModel->getFeatured(3);

        // Get trending posts (most viewed in last 7 days)
        $trendingPosts = $postModel->getTrending(10);

        // Get all categories
        $categories = $categoryModel->getAll();

        // Get recent scholarships for the news sidebar
        $scholarships = $scholarshipModel->getActiveScholarships(4);

        // Get breaking / new news items for the marquee
        $breakingNews = $postModel->getBreakingNews(8);

        $this->view('news/index', [
            'posts' => $posts,
            'featuredPosts' => $featuredPosts,
            'trendingPosts' => $trendingPosts,
            'breakingNews' => $breakingNews,
            'categories' => $categories,
            'scholarships' => $scholarships,
            'page' => $page,
            'totalPages' => $totalPages,
            'currentCategory' => $category,
            'page_title' => 'News & Opportunities | InfoHub Rwanda',
            'page_description' => 'Stay informed with Rwanda\'s latest news, jobs, scholarships, and business opportunities',
            'flash' => $this->getFlash(),
            'user' => $this->user
        ]);
    }

    /**
     * Search News Articles
     */
    public function search($query = '', $page = 1) {
        require_once ROOT_PATH . '/app/models/Post.php';

        if (empty($query) || strlen($query) < 2) {
            $this->redirect('/news');
            return;
        }

        $postModel = new Post();
        $limit = 20;
        $offset = ($page - 1) * $limit;

        // Search in title, content, and excerpt
        $results = $postModel->search($query, $limit, $offset);
        $totalResults = $postModel->searchCount($query);
        $totalPages = ceil($totalResults / $limit);

        // Log search
        if ($this->user) {
            Logger::logActivity($this->user['id'], 'search_news', 'news', 'Searched for: ' . $query);
        }

        $this->view('news/search', [
            'query' => htmlspecialchars($query),
            'results' => $results,
            'page' => $page,
            'totalPages' => $totalPages,
            'totalResults' => $totalResults,
            'page_title' => 'Search Results: ' . htmlspecialchars($query) . ' | InfoHub',
            'user' => $this->user
        ]);
    }

    /**
     * Category Archive
     */
    public function category($slug, $page = 1) {
        require_once ROOT_PATH . '/app/models/Post.php';
        require_once ROOT_PATH . '/app/models/Category.php';

        $categoryModel = new Category();
        $postModel = new Post();

        $category = $categoryModel->getBySlug($slug);
        if (!$category) {
            http_response_code(404);
            $this->view('errors/404');
            return;
        }

        $limit = 15;
        $offset = ($page - 1) * $limit;

        $posts = $postModel->getByCategory($category['id'], $limit, $offset);
        $totalPosts = $postModel->countByCategory($category['id']);
        $totalPages = ceil($totalPosts / $limit);

        // Get featured posts in this category
        $featuredPosts = $postModel->getFeaturedByCategory($category['id'], 3);

        $this->view('news/category', [
            'category' => $category,
            'posts' => $posts,
            'featuredPosts' => $featuredPosts,
            'page' => $page,
            'totalPages' => $totalPages,
            'totalPosts' => $totalPosts,
            'page_title' => htmlspecialchars($category['name']) . ' | InfoHub Rwanda',
            'page_description' => 'Latest ' . strtolower($category['name']) . ' from InfoHub Rwanda',
            'user' => $this->user
        ]);
    }

    /**
     * Single Article View
     */
    public function show($slug) {
        require_once ROOT_PATH . '/app/models/Post.php';

        $postModel = new Post();
        $post = $postModel->getBySlug($slug);

        if (!$post) {
            http_response_code(404);
            $this->view('errors/404');
            return;
        }

        // Only show published posts
        if ($post['status'] !== 'published') {
            http_response_code(403);
            $this->view('errors/403');
            return;
        }

        // Increment views
        $postModel->incrementViews($post['id']);

        // Log activity
        if ($this->user) {
            Logger::logActivity($this->user['id'], 'view_post', 'news', 'Viewed post: ' . $post['title']);
        }

        // Get related articles (same category, different post)
        $relatedArticles = $postModel->getRelated($post['category_id'], $post['id'], 5);

        $this->view('news/show', [
            'post' => $post,
            'relatedArticles' => $relatedArticles,
            'page_title' => htmlspecialchars($post['title']) . ' | InfoHub',
            'page_description' => $post['seo_meta_description'] ?? substr(strip_tags($post['content']), 0, 160),
            'og_image' => $post['featured_image'],
            'user' => $this->user
        ]);
    }

    /**
     * Get Trending Posts (AJAX)
     */
    public function trending() {
        require_once ROOT_PATH . '/app/models/Post.php';

        $postModel = new Post();
        $trending = $postModel->getTrending(15);

        header('Content-Type: application/json');
        echo json_encode([
            'success' => true,
            'data' => $trending
        ]);
    }

    /**
     * Get Featured Posts (AJAX)
     */
    public function featured() {
        require_once ROOT_PATH . '/app/models/Post.php';

        $postModel = new Post();
        $featured = $postModel->getFeatured(5);

        header('Content-Type: application/json');
        echo json_encode([
            'success' => true,
            'data' => $featured
        ]);
    }

    /**
     * Save Article for Later
     */
    public function bookmark($postId) {
        if (!$this->user) {
            http_response_code(401);
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => 'Not authenticated']);
            return;
        }

        require_once ROOT_PATH . '/app/models/Post.php';

        $postModel = new Post();

        // Check if already bookmarked
        $existing = $postModel->isBookmarked($this->user['id'], $postId);

        if ($existing) {
            // Remove bookmark
            $postModel->removeBookmark($this->user['id'], $postId);
            $message = 'Removed from bookmarks';
            $saved = false;
        } else {
            // Add bookmark
            $postModel->addBookmark($this->user['id'], $postId);
            $message = 'Added to bookmarks';
            $saved = true;
        }

        Logger::logActivity($this->user['id'], 'bookmark_post', 'news', 'Bookmarked post ID: ' . $postId);

        header('Content-Type: application/json');
        echo json_encode([
            'success' => true,
            'message' => $message,
            'saved' => $saved
        ]);
    }

    /**
     * Get User's Bookmarks
     */
    public function bookmarks($page = 1) {
        if (!$this->user) {
            $this->redirect('/auth/login');
            return;
        }

        require_once ROOT_PATH . '/app/models/Post.php';

        $postModel = new Post();
        $limit = 15;
        $offset = ($page - 1) * $limit;

        $bookmarks = $postModel->getUserBookmarks($this->user['id'], $limit, $offset);
        $totalBookmarks = $postModel->countUserBookmarks($this->user['id']);
        $totalPages = ceil($totalBookmarks / $limit);

        $this->view('news/bookmarks', [
            'bookmarks' => $bookmarks,
            'page' => $page,
            'totalPages' => $totalPages,
            'totalBookmarks' => $totalBookmarks,
            'page_title' => 'My Saved Articles | InfoHub',
            'user' => $this->user
        ]);
    }

    /**
     * Subscribe to Newsletter
     */
    public function newsletter() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            return;
        }

        $email = trim($_POST['email'] ?? '');

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            http_response_code(400);
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => 'Invalid email']);
            return;
        }

        require_once ROOT_PATH . '/app/models/Post.php';

        $postModel = new Post();

        // Check if already subscribed
        if ($postModel->isNewsletterSubscriber($email)) {
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => 'Already subscribed']);
            return;
        }

        // Add subscriber
        $postModel->subscribeNewsletter($email);

        Logger::logActivity($this->user['id'] ?? 0, 'subscribe_newsletter', 'news', 'Newsletter subscription: ' . $email);

        header('Content-Type: application/json');
        echo json_encode([
            'success' => true,
            'message' => 'Successfully subscribed to newsletter!'
        ]);
    }

    /**
     * Get Recommendations (AJAX)
     */
    public function recommendations() {
        require_once ROOT_PATH . '/app/models/Post.php';

        $postModel = new Post();

        // Get personalized recommendations based on user's reading history
        if ($this->user) {
            $recommendations = $postModel->getPersonalizedRecommendations($this->user['id'], 10);
        } else {
            // For non-authenticated users, show popular posts
            $recommendations = $postModel->getTrending(10);
        }

        header('Content-Type: application/json');
        echo json_encode([
            'success' => true,
            'data' => $recommendations
        ]);
    }

    /**
     * Report Content
     */
    public function report() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            return;
        }

        $postId = intval($_POST['post_id'] ?? 0);
        $reason = trim($_POST['reason'] ?? '');
        $message = trim($_POST['message'] ?? '');

        if (!$postId || !$reason) {
            http_response_code(400);
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => 'Missing required fields']);
            return;
        }

        require_once ROOT_PATH . '/app/models/Post.php';

        $postModel = new Post();

        // Save report
        $postModel->reportContent($postId, $this->user['id'] ?? 0, $reason, $message);

        Logger::logActivity($this->user['id'] ?? 0, 'report_content', 'news', "Reported post $postId for: $reason");

        header('Content-Type: application/json');
        echo json_encode([
            'success' => true,
            'message' => 'Thank you. Our team will review this report.'
        ]);
    }
}
