<?php
/**
 * Admin Business Controller
 */

class BusinessController extends Controller {
    public function index($page = 1) {
        $this->requireAdmin();

        require_once ROOT_PATH . '/app/models/Business.php';
        $businessModel = new Business();

        $pendingBusinesses = $businessModel->getPending(50, 0);
        $verifiedBusinesses = $businessModel->getVerified(50, 0);

        $this->view('admin/businesses/index', [
            'page_title' => 'Business Verification | Admin Dashboard',
            'pendingBusinesses' => $pendingBusinesses,
            'verifiedBusinesses' => $verifiedBusinesses,
            'user' => $this->user
        ]);
    }

    public function verify($id) {
        $this->requireAdmin();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect(APP_URL . '/admin/businesses');
        }

        require_once ROOT_PATH . '/app/models/Business.php';
        $businessModel = new Business();
        $business = $businessModel->findById($id);

        if (!$business) {
            $this->setFlash('error', 'Business not found');
            $this->redirect(APP_URL . '/admin/businesses');
        }

        $businessModel->verify($id, $this->user['id']);
        Logger::logAdminAction($this->user['id'], 'verify_business', 'businesses', $id);

        $this->setFlash('success', 'Business verified successfully');
        $this->redirect(APP_URL . '/admin/businesses');
    }
}
