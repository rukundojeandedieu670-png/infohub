<?php
/**
 * Business Controller
 */

class BusinessController extends Controller {

    public function index($page = 1) {
        require_once ROOT_PATH . '/app/models/Business.php';

        $businessModel = new Business();
        $limit = 12;
        $offset = ($page - 1) * $limit;

        $businesses = $businessModel->getVerified($limit, $offset);
        $totalBusinesses = $businessModel->count('verification_status', 'verified');
        $totalPages = ceil($totalBusinesses / $limit);

        $this->view('business/index', [
            'businesses' => $businesses,
            'page' => $page,
            'totalPages' => $totalPages,
            'page_title' => 'Businesses | InfoHub',
            'user' => $this->user
        ]);
    }

    public function show($slug) {
        require_once ROOT_PATH . '/app/models/Business.php';

        $businessModel = new Business();
        $business = $businessModel->getBySlug($slug);

        if (!$business) {
            http_response_code(404);
            $this->view('errors/404');
            return;
        }

        if ($this->user) {
            Logger::logActivity($this->user['id'], 'view_business', 'business', 'Viewed business: ' . $business['name']);
        }

        $this->view('business/show', [
            'business' => $business,
            'page_title' => htmlspecialchars($business['name']) . ' | InfoHub',
            'user' => $this->user
        ]);
    }
}
