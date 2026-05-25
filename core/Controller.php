<?php
/**
 * Base Controller Class
 * All controllers extend this
 */

class Controller {
    protected $db;
    protected $user = null;
    protected $isAdmin = false;
    protected $currentRole = null;

    public function __construct() {
        $this->db = Database::getInstance();
        $this->checkUserSession();
    }

    /**
     * Check user session
     */
    protected function checkUserSession() {
        if (isset($_SESSION['user_id'])) {
            $this->user = [
                'id' => $_SESSION['user_id'],
                'email' => $_SESSION['user_email'],
                'role' => $_SESSION['user_role'],
                'role_id' => $_SESSION['user_role_id'] ?? null,
                'first_name' => $_SESSION['user_first_name'] ?? 'User',
                'last_name' => $_SESSION['user_last_name'] ?? ''
            ];

            $currentRole = $this->getCurrentRoleRecord();
            if ($currentRole) {
                require_once ROOT_PATH . '/app/models/Role.php';
                $roleModel = new Role();
                $this->isAdmin = $roleModel->canActAs($currentRole['id'], 'Admin');
            } else {
                $this->isAdmin = in_array($this->user['role'], ['Super Admin', 'Admin', 'Editor']);
            }
        }
    }

    /**
     * Get the current active role record for the signed-in user.
     */
    protected function getCurrentRoleRecord() {
        if ($this->currentRole !== null) {
            return $this->currentRole;
        }

        if (!$this->user) {
            return null;
        }

        require_once ROOT_PATH . '/app/models/Role.php';
        $roleModel = new Role();

        if (!empty($this->user['role_id'])) {
            $this->currentRole = $roleModel->findById((int)$this->user['role_id']);
        }

        if (empty($this->currentRole) && !empty($this->user['role'])) {
            $this->currentRole = $roleModel->getByName($this->user['role']);
        }

        return $this->currentRole;
    }

    /**
     * Load view
     */
    protected function view($view, $data = []) {
        extract($data);
        require_once __DIR__ . '/../app/views/' . $view . '.php';
    }

    /**
     * Require authentication
     */
    protected function requireLogin() {
        if (!$this->user) {
            $_SESSION['redirect_after_login'] = $this->getRelativeRequestPath();
            header('Location: ' . APP_URL . '/auth/login');
            exit;
        }
    }

    /**
     * Get the current request path relative to the application base URL
     */
    protected function getRelativeRequestPath() {
        $requestUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) ?: '/';
        $appPath = parse_url(APP_URL, PHP_URL_PATH) ?: '';

        if ($appPath !== '' && strpos($requestUri, $appPath) === 0) {
            $requestUri = substr($requestUri, strlen($appPath));
            if ($requestUri === '') {
                $requestUri = '/';
            }
        }

        return $requestUri;
    }

    /**
     * Require admin access
     */
    protected function requireAdmin() {
        $this->requireLogin();

        require_once ROOT_PATH . '/app/models/Role.php';
        $roleModel = new Role();
        $currentRole = $this->getCurrentRoleRecord();

        if (!$currentRole || !$roleModel->canActAs($currentRole['id'], 'Admin')) {
            http_response_code(403);
            $this->view('errors/403');
            exit;
        }
    }

    /**
     * Require specific role
     */
    protected function requireRole($role) {
        $this->requireLogin();

        require_once ROOT_PATH . '/app/models/Role.php';
        $roleModel = new Role();
        $currentRole = $this->getCurrentRoleRecord();

        if ($currentRole && $roleModel->canActAs($currentRole['id'], $role)) {
            return;
        }

        if ($this->user['role'] === $role) {
            return;
        }

        http_response_code(403);
        $this->view('errors/403');
        exit;
    }

    /**
     * Generate CSRF token
     */
    protected function generateCSRFToken() {
        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['csrf_token'];
    }

    /**
     * Verify CSRF token
     */
    protected function verifyCSRFToken($token) {
        if (empty($_SESSION['csrf_token']) || $token !== $_SESSION['csrf_token']) {
            Logger::logError('CSRF Violation', 'Invalid CSRF token');
            http_response_code(403);
            die('CSRF token validation failed');
        }
    }

    /**
     * Sanitize input
     */
    protected function sanitize($data) {
        if (is_array($data)) {
            return array_map([$this, 'sanitize'], $data);
        }
        return htmlspecialchars(trim($data), ENT_QUOTES, 'UTF-8');
    }

    /**
     * Validate email
     */
    protected function validateEmail($email) {
        return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
    }

    /**
     * Hash password
     */
    protected function hashPassword($password) {
        return password_hash($password, PASSWORD_BCRYPT, ['cost' => BCRYPT_COST]);
    }

    /**
     * Verify password
     */
    protected function verifyPassword($password, $hash) {
        return password_verify($password, $hash);
    }

    /**
     * JSON response
     */
    protected function jsonResponse($data, $statusCode = 200) {
        http_response_code($statusCode);
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }

    /**
     * Redirect
     */
    protected function redirect($url) {
        header('Location: ' . $url);
        exit;
    }

    /**
     * Set flash message
     */
    protected function setFlash($type, $message) {
        $_SESSION['flash'] = [
            'type' => $type,
            'message' => $message
        ];
    }

    /**
     * Get flash message
     */
    protected function getFlash() {
        if (isset($_SESSION['flash'])) {
            $flash = $_SESSION['flash'];
            unset($_SESSION['flash']);
            return $flash;
        }
        return null;
    }

    /**
     * Check if user has permission for an action
     * Based on role-based access control
     */
    protected function hasPermission($action) {
        if (!$this->user) {
            return false;
        }

        $roleRecord = $this->getCurrentRoleRecord();

        if ($roleRecord) {
            require_once ROOT_PATH . '/app/models/Role.php';
            $roleModel = new Role();
            $permissions = $roleModel->getEffectivePermissions($roleRecord['id']);

            foreach ($permissions as $permission) {
                if ($roleModel->permissionMatchesAction($permission, $action)) {
                    return true;
                }
            }

            return false;
        }

        $role = $this->user['role'];
        
        // Fallback permission matrix based on roles
        $permissions = [
            'Super Admin' => ['*'],
            'Admin' => ['users.manage', 'content.manage', 'businesses.manage', 'jobs.manage', 'profile.edit', 'profile.view'],
            'Editor' => ['posts.create', 'posts.edit_own', 'posts.delete_own', 'comments.manage', 'profile.edit', 'profile.view'],
            'Writer' => ['posts.create', 'posts.edit_own', 'posts.delete_own', 'profile.edit', 'profile.view'],
            'Business Owner' => ['businesses.create', 'businesses.edit_own', 'businesses.delete_own', 'profile.edit', 'profile.view'],
            'Employer' => ['jobs.create', 'jobs.edit_own', 'jobs.delete_own', 'applications.view', 'profile.edit', 'profile.view'],
            'Registered User' => ['profile.edit', 'profile.view', 'comments.create', 'bookmarks.create']
        ];

        if (!isset($permissions[$role])) {
            return false;
        }

        $rolePermissions = $permissions[$role];

        if (in_array('*', $rolePermissions)) {
            return true;
        }

        return in_array($action, $rolePermissions);
    }

    /**
     * Check if user can edit their own resource
     * Allows users to edit their own profile/posts/businesses/jobs
     */
    protected function canEditOwn($resourceId) {
        $this->requireLogin();
        
        // Super Admin can edit anything
        if ($this->user['role'] === 'Super Admin') {
            return true;
        }

        // Check if the resource belongs to the user
        return $resourceId == $this->user['id'];
    }

    /**
     * Check if user can delete their own resource
     */
    protected function canDeleteOwn($resourceId) {
        return $this->canEditOwn($resourceId);
    }

    /**
     * Check if user can manage users (admin only)
     */
    protected function canManageUsers() {
        $this->requireLogin();
        return $this->hasPermission('users.manage');
    }

    /**
     * Check if user can create content
     */
    protected function canCreateContent() {
        $this->requireLogin();
        return $this->hasPermission('posts.create');
    }

    /**
     * Check if user can edit profile
     */
    protected function canEditProfile($userId = null) {
        $this->requireLogin();
        
        // Can only edit own profile unless super admin
        if ($this->user['role'] === 'Super Admin') {
            return true;
        }

        $userId = $userId ?? $this->user['id'];
        return $userId == $this->user['id'] && $this->hasPermission('profile.edit');
    }

    /**
     * Check if user can view profile
     */
    protected function canViewProfile() {
        $this->requireLogin();
        return $this->hasPermission('profile.view');
    }

    /**
     * Check if user can perform an action (generic)
     */
    protected function requirePermission($action) {
        $this->requireLogin();
        
        if (!$this->hasPermission($action)) {
            Logger::logError('Permission Denied', 'User ' . $this->user['id'] . ' attempted unauthorized action: ' . $action);
            http_response_code(403);
            $this->view('errors/403');
            exit;
        }
    }
}
