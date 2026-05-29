<?php
/**
 * Admin Dashboard Controller
 */

class DashboardController extends Controller {

    public function index() {
        $this->requireAdmin();
        // Differentiate Super Admin and Admin: Super Admin sees full stats; Admin sees limited view
        $isSuperAdmin = $this->isSuperAdmin;

        if ($isSuperAdmin) {
            // Full statistics for Super Admin
            $userCount = $this->db->prepare("SELECT COUNT(*) as count FROM users")->execute()->single()['count'];
            $postCount = $this->db->prepare("SELECT COUNT(*) as count FROM posts WHERE status = 'published'")->execute()->single()['count'];
            $jobCount = $this->db->prepare("SELECT COUNT(*) as count FROM jobs WHERE status = 'open'")->execute()->single()['count'];
            $businessCount = $this->db->prepare("SELECT COUNT(*) as count FROM businesses WHERE verification_status = 'verified'")->execute()->single()['count'];
            $pendingBusinesses = $this->db->prepare("SELECT COUNT(*) as count FROM businesses WHERE verification_status = 'pending'")->execute()->single()['count'];
            $paymentCount = $this->db->prepare("SELECT COUNT(*) as count FROM payments")->execute()->single()['count'];
            $paymentVolume = $this->db->prepare("SELECT COALESCE(SUM(amount), 0) as total_amount FROM payments WHERE status = 'completed'")->execute()->single()['total_amount'];
            $donationVolume = $this->db->prepare("SELECT COALESCE(SUM(amount), 0) as total_amount FROM payments WHERE payment_type = 'donation' AND status = 'completed'")->execute()->single()['total_amount'];

            // Recent activities
            $recentActivities = $this->db->prepare("\n                SELECT al.*, u.first_name, u.last_name\n                FROM activity_logs al\n                LEFT JOIN users u ON al.user_id = u.id\n                ORDER BY al.created_at DESC\n                LIMIT 10\n            ")->execute()->resultSet();
        } else {
            // Limited statistics for regular Admins (no user counts or financials)
            $userCount = null;
            $postCount = $this->db->prepare("SELECT COUNT(*) as count FROM posts WHERE status = 'published'")->execute()->single()['count'];
            $jobCount = $this->db->prepare("SELECT COUNT(*) as count FROM jobs WHERE status = 'open'")->execute()->single()['count'];
            $businessCount = $this->db->prepare("SELECT COUNT(*) as count FROM businesses WHERE verification_status = 'verified'")->execute()->single()['count'];
            $pendingBusinesses = $this->db->prepare("SELECT COUNT(*) as count FROM businesses WHERE verification_status = 'pending'")->execute()->single()['count'];
            $paymentCount = null;
            $paymentVolume = null;
            $donationVolume = null;

            // For admins, show only recent activities related to their actions (optional)
            $currentUserId = $this->user['id'] ?? null;
            if ($currentUserId) {
                $this->db->prepare("\n                    SELECT al.*, u.first_name, u.last_name\n                    FROM activity_logs al\n                    LEFT JOIN users u ON al.user_id = u.id\n                    WHERE al.user_id = ?\n                    ORDER BY al.created_at DESC\n                    LIMIT 10\n                ");
                $this->db->bind('i', $currentUserId);
                $this->db->execute();
                $recentActivities = $this->db->resultSet();
            } else {
                $recentActivities = [];
            }
        }

        Logger::logActivity($this->user['id'], 'access_dashboard', 'admin', 'Accessed admin dashboard');

        $this->view('admin/dashboard', [
            'page_title' => 'Admin Dashboard | InfoHub',
            'userCount' => $userCount,
            'postCount' => $postCount,
            'jobCount' => $jobCount,
            'businessCount' => $businessCount,
            'pendingBusinesses' => $pendingBusinesses,
            'paymentCount' => $paymentCount,
            'paymentVolume' => $paymentVolume,
            'donationVolume' => $donationVolume,
            'recentActivities' => $recentActivities,
            'user' => $this->user
        ]);
    }
}
