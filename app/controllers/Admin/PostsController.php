<?php
/**
 * Admin Post Management Controller
 * 
 * Handles CRUD operations for news posts in admin dashboard
 * Includes post creation, editing, deletion, and publishing
 */
class PostsController extends Controller {
    
    /**
     * List all posts with pagination
     */
    public function index($page = 1) {
        $this->requireAdmin();
        
        require_once ROOT_PATH . '/app/models/Post.php';
        require_once ROOT_PATH . '/app/models/Category.php';
        
        $postModel = new Post();
        $categoryModel = new Category();
        
        $per_page = 10;
        $offset = ($page - 1) * $per_page;
        
        // Get posts with pagination
        $posts = $postModel->getAllWithAuthor($per_page, $offset);
        
        // Get counts
        $total_posts = $postModel->count();
        $published_count = $postModel->countByStatus('published');
        $draft_count = $postModel->countByStatus('draft');
        $featured_count = $postModel->countFeatured();
        
        // Get categories
        $categories = $categoryModel->getActive();
        
        $total_pages = ceil($total_posts / $per_page);
        
        $this->view('admin/posts/index', [
            'posts' => $posts,
            'categories' => $categories,
            'current_page' => $page,
            'total_pages' => $total_pages,
            'total_posts' => $total_posts,
            'published_count' => $published_count,
            'draft_count' => $draft_count,
            'featured_count' => $featured_count
        ]);
    }
    
    /**
     * Show create post form
     */
    public function create() {
        $this->requireAdmin();
        
        require_once ROOT_PATH . '/app/models/Category.php';
        $categoryModel = new Category();
        $categories = $categoryModel->getActive();
        
        $this->view('admin/posts/edit', [
            'categories' => $categories
        ]);
    }
    
    /**
     * Store new post
     */
    public function store() {
        $this->requireAdmin();
        
        // CSRF validation
        if (empty($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
            $_SESSION['error'] = 'Invalid security token. Please try again.';
            $this->redirect(APP_URL . '/admin/posts/create');
            return;
        }
        
        // Validate inputs
        $title = htmlspecialchars($_POST['title'] ?? '');
        $slug = htmlspecialchars($_POST['slug'] ?? '');
        $excerpt = htmlspecialchars($_POST['excerpt'] ?? '');
        $content = $_POST['content'] ?? ''; // Keep HTML but filter script tags
        $category_id = intval($_POST['category_id'] ?? 0);
        $featured = isset($_POST['featured']) ? 1 : 0;
        $status = $_POST['status'] ?? 'draft';
        
        // Validate
        if (empty($title) || empty($content)) {
            $_SESSION['error'] = 'Title and content are required.';
            $this->redirect(APP_URL . '/admin/posts/create');
            return;
        }
        
        // Remove dangerous tags
        $content = str_ireplace(['<script', '</script>'], '', $content);
        
        // Handle featured image upload
        $featured_image = '';
        if (!empty($_FILES['featured_image']['name'])) {
            $featured_image = $this->handleImageUpload($_FILES['featured_image']);
            if (!$featured_image) {
                $_SESSION['error'] = 'Failed to upload image. Please check file size (max 5MB).';
                $this->redirect(APP_URL . '/admin/posts/create');
                return;
            }
        }
        
        // Create post
        require_once ROOT_PATH . '/app/models/Post.php';
        $postModel = new Post();
        
        $post_data = [
            'title' => $title,
            'slug' => $slug,
            'excerpt' => $excerpt,
            'content' => $content,
            'category_id' => $category_id > 0 ? $category_id : null,
            'author_id' => $_SESSION['user_id'],
            'featured_image' => $featured_image,
            'is_featured' => $featured,
            'status' => $status,
            'published_at' => $status === 'published' ? date('Y-m-d H:i:s') : null
        ];
        
        $post_id = $postModel->createPost($post_data);
        
        if ($post_id) {
            Logger::logActivity($_SESSION['user_id'], 'post_created', 'posts', json_encode([
                'id' => $post_id,
                'title' => $title,
                'status' => $status
            ]));
            
            Logger::logAdminAction($_SESSION['user_id'], 'create_post', 'posts', $post_id, 'New post created: ' . $title);
            
            $_SESSION['success'] = 'Post created successfully!';
            $this->redirect(APP_URL . '/admin/posts');
        } else {
            $_SESSION['error'] = 'Failed to create post. Please try again.';
            $this->redirect(APP_URL . '/admin/posts/create');
        }
    }
    
    /**
     * Show edit post form
     */
    public function edit($id) {
        $this->requireAdmin();
        
        require_once ROOT_PATH . '/app/models/Post.php';
        require_once ROOT_PATH . '/app/models/Category.php';
        
        $postModel = new Post();
        $categoryModel = new Category();
        
        $post = $postModel->findById($id);
        if (!$post) {
            http_response_code(404);
            $this->view('errors/404');
            return;
        }
        
        $categories = $categoryModel->getActive();
        
        $this->view('admin/posts/edit', [
            'post' => $post,
            'categories' => $categories
        ]);
    }
    
    /**
     * Update post
     */
    public function update($id) {
        $this->requireAdmin();
        
        // CSRF validation
        if (empty($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
            $_SESSION['error'] = 'Invalid security token.';
            $this->redirect(APP_URL . '/admin/posts/' . $id . '/edit');
            return;
        }
        
        require_once ROOT_PATH . '/app/models/Post.php';
        $postModel = new Post();
        
        $post = $postModel->findById($id);
        if (!$post) {
            $_SESSION['error'] = 'Post not found.';
            $this->redirect(APP_URL . '/admin/posts');
            return;
        }
        
        // Validate inputs
        $title = htmlspecialchars($_POST['title'] ?? '');
        $slug = htmlspecialchars($_POST['slug'] ?? '');
        $excerpt = htmlspecialchars($_POST['excerpt'] ?? '');
        $content = $_POST['content'] ?? '';
        $category_id = intval($_POST['category_id'] ?? 0);
        $featured = isset($_POST['featured']) ? 1 : 0;
        $status = $_POST['status'] ?? $post['status'];
        
        if (empty($title) || empty($content)) {
            $_SESSION['error'] = 'Title and content are required.';
            $this->redirect(APP_URL . '/admin/posts/' . $id . '/edit');
            return;
        }
        
        // Remove dangerous tags
        $content = str_ireplace(['<script', '</script>'], '', $content);
        
        // Handle image upload
        $featured_image = $post['featured_image'];
        if (!empty($_FILES['featured_image']['name'])) {
            $new_image = $this->handleImageUpload($_FILES['featured_image']);
            if ($new_image) {
                $featured_image = $new_image;
                // Delete old image if exists
                if (!empty($post['featured_image']) && file_exists(ROOT_PATH . '/public' . $post['featured_image'])) {
                    unlink(ROOT_PATH . '/public' . $post['featured_image']);
                }
            }
        }
        
        // Update post
        $update_data = [
            'title' => $title,
            'slug' => $slug,
            'excerpt' => $excerpt,
            'content' => $content,
            'category_id' => $category_id > 0 ? $category_id : null,
            'featured_image' => $featured_image,
            'is_featured' => $featured,
            'status' => $status
        ];
        
        // Set published_at if publishing for first time
        if ($status === 'published' && $post['status'] !== 'published') {
            $update_data['published_at'] = date('Y-m-d H:i:s');
        }
        
        $postModel->update($id, $update_data);
        
        Logger::logActivity($_SESSION['user_id'], 'post_updated', 'posts', json_encode([
            'id' => $id,
            'title' => $title
        ]));
        
        Logger::logAdminAction($_SESSION['user_id'], 'update_post', 'posts', $id, 'Post updated: ' . $title);
        
        $_SESSION['success'] = 'Post updated successfully!';
        $this->redirect(APP_URL . '/admin/posts');
    }
    
    /**
     * Delete post
     */
    public function delete($id) {
        $this->requireAdmin();
        
        // CSRF validation
        if (empty($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
            $_SESSION['error'] = 'Invalid security token.';
            $this->redirect(APP_URL . '/admin/posts');
            return;
        }
        
        require_once ROOT_PATH . '/app/models/Post.php';
        $postModel = new Post();
        
        $post = $postModel->findById($id);
        if (!$post) {
            $_SESSION['error'] = 'Post not found.';
            $this->redirect(APP_URL . '/admin/posts');
            return;
        }
        
        // Delete featured image if exists
        if (!empty($post['featured_image']) && file_exists(ROOT_PATH . '/public' . $post['featured_image'])) {
            unlink(ROOT_PATH . '/public' . $post['featured_image']);
        }
        
        // Delete post
        $postModel->delete($id);
        
        Logger::logAdminAction($_SESSION['user_id'], 'delete_post', 'posts', $id, 'Post deleted: ' . $post['title']);
        
        $_SESSION['success'] = 'Post deleted successfully!';
        $this->redirect(APP_URL . '/admin/posts');
    }
    
    /**
     * Handle image upload
     */
    private function handleImageUpload($file) {
        if (empty($file['name'])) {
            return false;
        }
        
        // Validate file
        $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
        if (!in_array($file['type'], $allowed_types)) {
            return false;
        }
        
        // Check file size (5MB max)
        if ($file['size'] > 5 * 1024 * 1024) {
            return false;
        }
        
        // Create upload directory if it doesn't exist
        $upload_dir = ROOT_PATH . '/public/uploads/posts';
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0755, true);
        }
        
        // Generate unique filename
        $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
        $filename = time() . '_' . bin2hex(random_bytes(4)) . '.' . $ext;
        $filepath = '/uploads/posts/' . $filename;
        
        // Move uploaded file
        if (move_uploaded_file($file['tmp_name'], $upload_dir . '/' . $filename)) {
            return $filepath;
        }
        
        return false;
    }
}
