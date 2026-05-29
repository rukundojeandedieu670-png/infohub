<?php
/**
 * Admin Users Controller
 */

class UsersController extends Controller {

    public function index($page = 1) {
        $this->requireRole('Super Admin');

        $limit = 20;
        $offset = ($page - 1) * $limit;

        // Get users with role
        $this->db->prepare("
            SELECT u.*, r.name as role_name
            FROM users u
            LEFT JOIN roles r ON u.role_id = r.id
            ORDER BY u.created_at DESC
            LIMIT ? OFFSET ?
        ");
        $this->db->bind('i', $limit);
        $this->db->bind('i', $offset);
        $this->db->execute();
        $users = $this->db->resultSet();

        $this->db->prepare("SELECT COUNT(*) as count FROM users");
        $this->db->execute();
        $totalUsers = $this->db->single()['count'];
        $totalPages = ceil($totalUsers / $limit);

        Logger::logAdminAction($this->user['id'], 'view_users', 'users', 0);

        $this->view('admin/users/index', [
            'page_title' => 'Users | Admin Dashboard',
            'users' => $users,
            'page' => $page,
            'totalPages' => $totalPages,
            'user' => $this->user
        ]);
    }

    public function show($id) {
        $this->requireRole('Super Admin');

        require_once ROOT_PATH . '/app/models/User.php';
        require_once ROOT_PATH . '/app/models/Role.php';

        $userModel = new User();
        $roleModel = new Role();

        $user = $userModel->findById($id);

        if (!$user) {
            http_response_code(404);
            $this->view('errors/404');
            return;
        }

        if (!empty($user['role_id'])) {
            $roleRecord = $roleModel->findById($user['role_id']);
            $user['role_name'] = $roleRecord['name'] ?? 'Unknown';
        } else {
            $user['role_name'] = 'Unassigned';
        }

        $roles = $roleModel->findAll();

        $this->view('admin/users/show', [
            'page_title' => 'User Details | Admin Dashboard',
            'selectedUser' => $user,
            'roles' => $roles,
            'user' => $this->user,
            'csrf_token' => $this->generateCSRFToken()
        ]);
    }

    public function create() {
        $this->requireRole('Super Admin');

        require_once ROOT_PATH . '/app/models/Role.php';
        $roleModel = new Role();
        $roles = $roleModel->findAll();

        $this->view('admin/users/create', [
            'page_title' => 'Create User | Admin Dashboard',
            'roles' => $roles,
            'csrf_token' => $this->generateCSRFToken(),
            'user' => $this->user
        ]);
    }

    public function store() {
        $this->requireRole('Super Admin');

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect(APP_URL . '/admin/users/create');
        }

        $this->verifyCSRFToken($_POST['csrf_token'] ?? '');

        $firstName = $this->sanitize($_POST['first_name'] ?? '');
        $lastName = $this->sanitize($_POST['last_name'] ?? '');
        $email = $this->sanitize($_POST['email'] ?? '');
        $phone = $this->sanitize($_POST['phone'] ?? '');
        $roleId = intval($_POST['role_id'] ?? 0);
        $password = $_POST['password'] ?? '';
        $passwordConfirm = $_POST['password_confirm'] ?? '';
        $isActive = isset($_POST['is_active']) ? 1 : 0;

        $_SESSION['form_data'] = [
            'first_name' => $firstName,
            'last_name' => $lastName,
            'email' => $email,
            'phone' => $phone,
            'role_id' => $roleId,
            'is_active' => $isActive
        ];

        $errors = [];
        if (empty($firstName)) {
            $errors[] = 'First name is required';
        }
        if (empty($lastName)) {
            $errors[] = 'Last name is required';
        }
        if (empty($email)) {
            $errors[] = 'Email is required';
        } elseif (!$this->validateEmail($email)) {
            $errors[] = 'Invalid email address';
        }
        if (empty($password)) {
            $errors[] = 'Password is required';
        } elseif (strlen($password) < 8) {
            $errors[] = 'Password must be at least 8 characters';
        }
        if ($password !== $passwordConfirm) {
            $errors[] = 'Passwords do not match';
        }
        if ($roleId <= 0) {
            $errors[] = 'Please select a valid role';
        }

        require_once ROOT_PATH . '/app/models/User.php';
        $userModel = new User();
        if (!empty($email) && $userModel->findByEmail($email)) {
            $errors[] = 'Email is already registered';
        }

        if (!empty($errors)) {
            $_SESSION['errors'] = $errors;
            $this->redirect(APP_URL . '/admin/users/create');
        }

        try {
            $userModel->createUser([
                'first_name' => $firstName,
                'last_name' => $lastName,
                'email' => $email,
                'phone' => $phone,
                'password_hash' => $this->hashPassword($password),
                'role_id' => $roleId,
                'is_active' => $isActive
            ]);

            unset($_SESSION['form_data']);
            unset($_SESSION['errors']);
            $this->setFlash('success', 'User account created successfully');
            $this->redirect(APP_URL . '/admin/users');
        } catch (Exception $e) {
            Logger::logError('Create user failed', $e->getMessage());
            $this->setFlash('error', 'Unable to create user. Please try again.');
            $this->redirect(APP_URL . '/admin/users/create');
        }
    }

    public function edit($id) {
        $this->requireRole('Super Admin');

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect(APP_URL . '/admin/users');
        }

        $this->verifyCSRFToken($_POST['csrf_token'] ?? '');

        $firstName = $this->sanitize($_POST['first_name'] ?? '');
        $lastName = $this->sanitize($_POST['last_name'] ?? '');
        $email = $this->sanitize($_POST['email'] ?? '');
        $phone = $this->sanitize($_POST['phone'] ?? '');
        $roleId = intval($_POST['role_id'] ?? 0);
        $isActive = isset($_POST['is_active']) ? 1 : 0;
        $password = $_POST['password'] ?? '';
        $passwordConfirm = $_POST['password_confirm'] ?? '';

        $errors = [];

        if (empty($firstName)) {
            $errors[] = 'First name is required';
        }
        if (empty($lastName)) {
            $errors[] = 'Last name is required';
        }
        if (empty($email)) {
            $errors[] = 'Email is required';
        } elseif (!$this->validateEmail($email)) {
            $errors[] = 'Invalid email address';
        }
        if ($roleId <= 0) {
            $errors[] = 'Please select a role';
        }
        if (!empty($password)) {
            if (strlen($password) < 8) {
                $errors[] = 'Password must be at least 8 characters';
            }
            if ($password !== $passwordConfirm) {
                $errors[] = 'Passwords do not match';
            }
        }

        require_once ROOT_PATH . '/app/models/User.php';
        $userModel = new User();
        $existingUser = $userModel->findByEmail($email);
        if ($existingUser && $existingUser['id'] != $id) {
            $errors[] = 'Email is already registered by another user';
        }

        if (!empty($errors)) {
            $_SESSION['errors'] = $errors;
            $this->redirect(APP_URL . '/admin/users/' . intval($id));
        }

        $updateData = [
            'first_name' => $firstName,
            'last_name' => $lastName,
            'email' => $email,
            'phone' => $phone,
            'role_id' => $roleId,
            'is_active' => $isActive
        ];

        if (!empty($password)) {
            $updateData['password_hash'] = $this->hashPassword($password);
        }

        try {
            $this->db->prepare("UPDATE users SET first_name = ?, last_name = ?, email = ?, phone = ?, role_id = ?, is_active = ?" . (isset($updateData['password_hash']) ? ", password_hash = ?" : "") . " WHERE id = ?");
            $this->db->bind('s', $updateData['first_name']);
            $this->db->bind('s', $updateData['last_name']);
            $this->db->bind('s', $updateData['email']);
            $this->db->bind('s', $updateData['phone']);
            $this->db->bind('i', $updateData['role_id']);
            $this->db->bind('i', $updateData['is_active']);

            if (isset($updateData['password_hash'])) {
                $this->db->bind('s', $updateData['password_hash']);
            }

            $this->db->bind('i', $id);
            $this->db->execute();

            Logger::logAdminAction($this->user['id'], 'edit_user', 'users', $id, json_encode(array_filter($updateData, fn($value, $key) => $key !== 'password_hash', ARRAY_FILTER_USE_BOTH)));

            $this->setFlash('success', 'User updated successfully');
            $this->redirect(APP_URL . '/admin/users/' . intval($id));
        } catch (Exception $e) {
            Logger::logError('User update failed', $e->getMessage());
            $this->setFlash('error', 'Unable to update user. Please try again.');
            $this->redirect(APP_URL . '/admin/users/' . intval($id));
        }
    }

    /**
     * Reveal password hash (Super Admin only) - logs audit and shows hash once
     */
    public function revealHash($id) {
        $this->requireRole('Super Admin');

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect(APP_URL . '/admin/users/' . intval($id));
        }

        $this->verifyCSRFToken($_POST['csrf_token'] ?? '');

        require_once ROOT_PATH . '/app/models/User.php';
        $userModel = new User();
        $user = $userModel->findById($id);

        if (!$user) {
            $this->setFlash('error', 'User not found');
            $this->redirect(APP_URL . '/admin/users');
        }

        // Log the reveal action for audit
        Logger::logAdminAction($this->user['id'], 'reveal_password_hash', 'users', $id);

        // Store one-time revealed hash in session and redirect back to show page
        $_SESSION['revealed_password_hash_for_user_' . intval($id)] = $user['password_hash'];

        $this->redirect(APP_URL . '/admin/users/' . intval($id));
    }

    public function delete($id) {
        $this->requireRole('Super Admin');

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect(APP_URL . '/admin/users');
        }

        // Prevent deleting self
        if ($id == $this->user['id']) {
            $this->setFlash('error', 'Cannot delete your own account');
            $this->redirect(APP_URL . '/admin/users');
        }

        $this->db->prepare("DELETE FROM users WHERE id = ?");
        $this->db->bind('i', $id);
        $this->db->execute();

        Logger::logAdminAction($this->user['id'], 'delete_user', 'users', $id);

        $this->setFlash('success', 'User deleted successfully');
        $this->redirect(APP_URL . '/admin/users');
    }
}
