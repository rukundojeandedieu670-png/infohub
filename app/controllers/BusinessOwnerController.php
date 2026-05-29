<?php
/**
 * Business Owner Controller
 */

class BusinessOwnerController extends Controller {
    public function dashboard() {
        $this->requireRole('Business Owner');

        require_once ROOT_PATH . '/app/models/Business.php';

        $businessModel = new Business();
        $business = $businessModel->getByOwner($this->user['id']);

        $this->view('business-owner/dashboard', [
            'user' => $this->user,
            'business' => $business,
            'page_title' => 'Business Owner Dashboard | InfoHub'
        ]);
    }

    public function profile() {
        $this->requireRole('Business Owner');

        require_once ROOT_PATH . '/app/models/User.php';
        require_once ROOT_PATH . '/app/models/Business.php';
        require_once ROOT_PATH . '/app/models/Category.php';

        $userModel = new User();
        $businessModel = new Business();
        $categoryModel = new Category();

        $business = $businessModel->getByOwner($this->user['id']);
        $userProfile = $userModel->findById($this->user['id']);

        $this->view('business-owner/profile', [
            'user' => $this->user,
            'userProfile' => $userProfile,
            'business' => $business,
            'categories' => $categoryModel->getAll(),
            'page_title' => 'Business Owner Profile | InfoHub',
            'csrf_token' => $this->generateCSRFToken(),
            'flash' => $this->getFlash()
        ]);
    }

    public function updateProfile() {
        $this->requireRole('Business Owner');

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect(APP_URL . '/business-owner/profile');
        }

        $this->verifyCSRFToken($_POST['csrf_token'] ?? '');

        require_once ROOT_PATH . '/app/models/User.php';
        require_once ROOT_PATH . '/app/models/Business.php';

        $userModel = new User();
        $businessModel = new Business();
        $userProfile = $userModel->findById($this->user['id']);
        $business = $businessModel->getByOwner($this->user['id']);

        $company = $this->sanitize($_POST['company'] ?? '');
        $industry = $this->sanitize($_POST['industry'] ?? '');
        $location = $this->sanitize($_POST['location'] ?? '');
        $phone = $this->sanitize($_POST['phone'] ?? '');
        $website = $this->sanitize($_POST['website'] ?? '');
        $description = $this->sanitize($_POST['description'] ?? '');
        $categoryId = intval($_POST['category_id'] ?? 0);

        try {
            $userModel->update($this->user['id'], [
                'company' => $company,
                'industry' => $industry,
                'phone' => $phone
            ]);

            if ($business) {
                $businessModel->update($business['id'], [
                    'name' => $company,
                    'description' => $description,
                    'category_id' => $categoryId,
                    'location' => $location,
                    'phone' => $phone,
                    'website' => $website
                ]);
            } else {
                $businessModel->createBusiness([
                    'name' => $company,
                    'slug' => strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $company), '-')),
                    'description' => $description,
                    'owner_id' => $this->user['id'],
                    'category_id' => $categoryId,
                    'location' => $location,
                    'phone' => $phone,
                    'email' => $this->user['email'],
                    'website' => $website
                ]);
            }

            $this->setFlash('success', 'Business profile updated successfully.');
            $this->redirect(APP_URL . '/business-owner/profile');

        } catch (Exception $e) {
            Logger::logError('Business Owner Profile Update Error', $e->getMessage());
            $this->setFlash('error', 'Unable to update business profile. Please try again.');
            $this->redirect(APP_URL . '/business-owner/profile');
        }
    }
}
