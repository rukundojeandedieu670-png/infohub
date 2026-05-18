<?php
/**
 * Admin Logs Controller
 */

class LogsController extends Controller {

    public function index() {
        $this->requireAdmin();
        $this->redirect(APP_URL . '/admin/logs/activity');
    }

    public function activity($page = 1) {
        $this->requireAdmin();

        $limit = 50;
        $offset = ($page - 1) * $limit;

        $this->db->prepare("
            SELECT al.*, u.first_name, u.last_name, u.email
            FROM activity_logs al
            LEFT JOIN users u ON al.user_id = u.id
            ORDER BY al.created_at DESC
            LIMIT ? OFFSET ?
        ");
        $this->db->bind('i', $limit);
        $this->db->bind('i', $offset);
        $this->db->execute();
        $logs = $this->db->resultSet();

        $this->db->prepare("SELECT COUNT(*) as count FROM activity_logs");
        $this->db->execute();
        $totalLogs = $this->db->single()['count'];
        $totalPages = ceil($totalLogs / $limit);

        $this->view('admin/logs/activity', [
            'page_title' => 'Activity Logs | Admin Dashboard',
            'logs' => $logs,
            'page' => $page,
            'totalPages' => $totalPages,
            'user' => $this->user
        ]);
    }

    public function auth($page = 1) {
        $this->requireAdmin();

        $limit = 50;
        $offset = ($page - 1) * $limit;

        $this->db->prepare("
            SELECT * FROM auth_logs
            ORDER BY created_at DESC
            LIMIT ? OFFSET ?
        ");
        $this->db->bind('i', $limit);
        $this->db->bind('i', $offset);
        $this->db->execute();
        $logs = $this->db->resultSet();

        $this->db->prepare("SELECT COUNT(*) as count FROM auth_logs");
        $this->db->execute();
        $totalLogs = $this->db->single()['count'];
        $totalPages = ceil($totalLogs / $limit);

        $this->view('admin/logs/auth', [
            'page_title' => 'Auth Logs | Admin Dashboard',
            'logs' => $logs,
            'page' => $page,
            'totalPages' => $totalPages,
            'user' => $this->user
        ]);
    }

    public function errors($page = 1) {
        $this->requireAdmin();

        $limit = 50;
        $offset = ($page - 1) * $limit;

        $this->db->prepare("
            SELECT * FROM error_logs
            ORDER BY created_at DESC
            LIMIT ? OFFSET ?
        ");
        $this->db->bind('i', $limit);
        $this->db->bind('i', $offset);
        $this->db->execute();
        $logs = $this->db->resultSet();

        $this->db->prepare("SELECT COUNT(*) as count FROM error_logs");
        $this->db->execute();
        $totalLogs = $this->db->single()['count'];
        $totalPages = ceil($totalLogs / $limit);

        $this->view('admin/logs/errors', [
            'page_title' => 'Error Logs | Admin Dashboard',
            'logs' => $logs,
            'page' => $page,
            'totalPages' => $totalPages,
            'user' => $this->user
        ]);
    }
}
