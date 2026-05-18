<?php
/**
 * Admin Dashboard Controller
 */

class DashboardController extends Controller {

    public function index() {
        $this->requireAdmin();

        // Get statistics
        $userCount = $this->db->prepare("SELECT COUNT(*) as count FROM users");
        $userCount->db->execute();
        $userCount = $userCount->single()['count'];

        $postCount = $this->db->prepare("SELECT COUNT(*) as count FROM posts WHERE status = 'published'");
        $postCount->db->execute();
        $postCount = $postCount->single()['count'];

        $jobCount = $this->db->prepare("SELECT COUNT(*) as count FROM jobs WHERE status = 'open'");
        $jobCount->db->execute();
        $jobCount = $jobCount->single()['count'];

        $businessCount = $this->db->prepare("SELECT COUNT(*) as count FROM businesses WHERE verification_status = 'verified'");
        $businessCount->db->execute();
        $businessCount = $businessCount->single()['count'];

        $pendingBusinesses = $this->db->prepare("SELECT COUNT(*) as count FROM businesses WHERE verification_status = 'pending'");
        $pendingBusinesses->db->execute();
        $pendingBusinesses = $pendingBusinesses->single()['count'];

        // Recent activities
        $recentActivities = $this->db->prepare("
            SELECT al.*, u.first_name, u.last_name
            FROM activity_logs al
            LEFT JOIN users u ON al.user_id = u.id
            ORDER BY al.created_at DESC
            LIMIT 10
        ");
        $recentActivities->db->execute();
        $recentActivities = $recentActivities->resultSet();

        Logger::logActivity($this->user['id'], 'access_dashboard', 'admin', 'Accessed admin dashboard');

        $this->view('admin/dashboard', [
            'page_title' => 'Admin Dashboard | InfoHub',
            'userCount' => $userCount,
            'postCount' => $postCount,
            'jobCount' => $jobCount,
            'businessCount' => $businessCount,
            'pendingBusinesses' => $pendingBusinesses,
            'recentActivities' => $recentActivities,
            'user' => $this->user
        ]);
    }
}
