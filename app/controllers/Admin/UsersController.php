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
        $userModel = new User();
        $user = $userModel->findById($id);

        if (!$user) {
            http_response_code(404);
            $this->view('errors/404');
            return;
        }

        $this->view('admin/users/show', [
            'page_title' => 'User Details | Admin Dashboard',
            'user' => $user,
            'user' => $this->user
        ]);
    }

    public function edit($id) {
        $this->requireRole('Super Admin');

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect(APP_URL . '/admin/users');
        }

        $isActive = isset($_POST['is_active']) ? 1 : 0;
        $roleId = intval($_POST['role_id']);

        $this->db->prepare("UPDATE users SET is_active = ?, role_id = ? WHERE id = ?");
        $this->db->bind('i', $isActive);
        $this->db->bind('i', $roleId);
        $this->db->bind('i', $id);
        $this->db->execute();

        Logger::logAdminAction($this->user['id'], 'edit_user', 'users', $id, json_encode(['is_active' => $isActive, 'role_id' => $roleId]));

        $this->setFlash('success', 'User updated successfully');
        $this->redirect(APP_URL . '/admin/users');
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
