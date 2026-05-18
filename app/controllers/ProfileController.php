<?php
/**
 * Profile Controller
 */

class ProfileController extends Controller {

    public function show() {
        $this->requireLogin();
        $this->requirePermission('profile.view');

        require_once ROOT_PATH . '/app/models/User.php';
        $userModel = new User();
        $userProfile = $userModel->findById($this->user['id']);

        $this->view('profile/edit-combined', [
            'page_title' => 'Profile Management | InfoHub',
            'userProfile' => $userProfile,
            'user' => $this->user,
            'csrf_token' => $this->generateCSRFToken(),
            'flash' => $this->getFlash()
        ]);
    }

    public function edit() {
        $this->requireLogin();
        $this->requirePermission('profile.edit');
        
        // Redirect to single profile page
        $this->redirect(APP_URL . '/profile');
    }

    /**
     * Load profile page helper
     */
    private function loadProfilePage($viewName, $pageTitle) {
        $this->requireLogin();
        $this->requirePermission('profile.edit');

        require_once ROOT_PATH . '/app/models/User.php';
        $userModel = new User();
        $userProfile = $userModel->findById($this->user['id']);

        $this->view('profile/' . $viewName, [
            'page_title' => $pageTitle . ' | InfoHub',
            'userProfile' => $userProfile,
            'user' => $this->user,
            'csrf_token' => $this->generateCSRFToken()
        ]);
    }

    /**
     * Edit Personal Information Page (Sidebar Navigation)
     */
    public function editPersonal() {
        $this->requireLogin();
        $this->requirePermission('profile.edit');
        $this->redirect(APP_URL . '/profile');
    }

    /**
     * Edit Professional Information Page (Sidebar Navigation)
     */
    public function editProfessional() {
        $this->requireLogin();
        $this->requirePermission('profile.edit');
        $this->redirect(APP_URL . '/profile');
    }

    /**
     * Edit Security & Password Page (Sidebar Navigation)
     */
    public function editSecurity() {
        $this->requireLogin();
        $this->requirePermission('profile.edit');
        $this->redirect(APP_URL . '/profile');
    }

    /**
     * Edit Account Settings Page (Sidebar Navigation)
     */
    public function editAccount() {
        $this->requireLogin();
        $this->requirePermission('profile.edit');
        $this->redirect(APP_URL . '/profile');
    }

    public function delete() {
        $this->requireLogin();
        $this->requirePermission('profile.edit');

        require_once ROOT_PATH . '/app/models/User.php';
        $userModel = new User();
        $userProfile = $userModel->findById($this->user['id']);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->verifyCSRFToken($_POST['csrf_token'] ?? '');
            $confirmation = trim($_POST['confirmation'] ?? '');

            if ($confirmation !== 'DELETE') {
                $this->setFlash('error', 'Please type DELETE exactly to confirm account deletion.');
                $this->redirect(APP_URL . '/profile/delete');
            }

            $deleted = $userModel->delete($this->user['id']);
            if ($deleted) {
                Logger::logActivity($this->user['id'], 'delete_account', 'profile', 'User account deleted');
                $this->setFlash('success', 'Your account has been deleted successfully.');
                unset($_SESSION['user_id'], $_SESSION['user_email'], $_SESSION['user_role'], $_SESSION['user_first_name'], $_SESSION['user_last_name']);
                $this->user = null;
                $this->redirect(APP_URL . '/auth/login');
            }

            $this->setFlash('error', 'Unable to delete your account. Please try again later.');
            $this->redirect(APP_URL . '/profile/delete');
        }

        $this->view('profile/delete-confirm', [
            'page_title' => 'Delete Account | InfoHub',
            'userProfile' => $userProfile,
            'user' => $this->user,
            'csrf_token' => $this->generateCSRFToken(),
            'flash' => $this->getFlash()
        ]);
    }

    public function update() {
        $this->requireLogin();
        $this->requirePermission('profile.edit');

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect(APP_URL . '/profile');
        }

        $this->verifyCSRFToken($_POST['csrf_token'] ?? '');

        $formType = $_POST['form_type'] ?? 'all';

        try {
            if ($formType === 'personal' || $formType === 'all') {
                $this->updatePersonalInfo();
            }

            if ($formType === 'professional' || $formType === 'all') {
                $this->updateProfessionalInfo();
            }

            if ($formType === 'password' || $formType === 'all') {
                $this->updatePassword();
            }

            Logger::logActivity($this->user['id'], 'update_profile', 'profile', "Updated profile section: $formType");

            $this->setFlash('success', 'Profile updated successfully!');
            $this->redirect(APP_URL . '/profile/edit');

        } catch (Exception $e) {
            Logger::logError('Profile Update Error', $e->getMessage());
            $this->setFlash('error', 'Failed to update profile: ' . $e->getMessage());
            $this->redirect(APP_URL . '/profile/edit');
        }
    }

    private function updatePersonalInfo() {
        $firstName = $this->sanitize($_POST['first_name'] ?? '');
        $lastName = $this->sanitize($_POST['last_name'] ?? '');
        $phone = $this->sanitize($_POST['phone'] ?? '');
        $bio = $this->sanitize($_POST['bio'] ?? '');

        if (empty($firstName) || empty($lastName)) {
            throw new Exception('First name and last name are required');
        }

        $this->db->prepare("
            UPDATE users 
            SET first_name = ?, last_name = ?, phone = ?, bio = ?
            WHERE id = ?
        ");
        $this->db->bind('s', $firstName);
        $this->db->bind('s', $lastName);
        $this->db->bind('s', $phone);
        $this->db->bind('s', $bio);
        $this->db->bind('i', $this->user['id']);
        $this->db->execute();
    }

    private function updateProfessionalInfo() {
        $jobTitle = $this->sanitize($_POST['job_title'] ?? '');
        $company = $this->sanitize($_POST['company'] ?? '');
        $industry = $this->sanitize($_POST['industry'] ?? '');
        $experienceYears = !empty($_POST['experience_years']) ? (int)$_POST['experience_years'] : null;
        $bioProfessional = $this->sanitize($_POST['bio_professional'] ?? '');
        $linkedinUrl = $this->sanitize($_POST['linkedin_url'] ?? '');
        $portfolioUrl = $this->sanitize($_POST['portfolio_url'] ?? '');
        $isJobSeeker = !empty($_POST['is_job_seeker']) ? 1 : 0;
        $isBusinessOwner = !empty($_POST['is_business_owner']) ? 1 : 0;
        
        // Process skills (comma-separated to JSON array)
        $skillsText = $_POST['skills_text'] ?? '';
        $skills = !empty($skillsText) ? array_map('trim', explode(',', $skillsText)) : [];
        $skillsJson = json_encode($skills);

        $this->db->prepare("
            UPDATE users 
            SET job_title = ?, company = ?, industry = ?, skills = ?,
                experience_years = ?, bio_professional = ?, linkedin_url = ?,
                portfolio_url = ?, is_job_seeker = ?, is_business_owner = ?
            WHERE id = ?
        ");
        $this->db->bind('s', $jobTitle);
        $this->db->bind('s', $company);
        $this->db->bind('s', $industry);
        $this->db->bind('s', $skillsJson);
        $this->db->bind('i', $experienceYears);
        $this->db->bind('s', $bioProfessional);
        $this->db->bind('s', $linkedinUrl);
        $this->db->bind('s', $portfolioUrl);
        $this->db->bind('i', $isJobSeeker);
        $this->db->bind('i', $isBusinessOwner);
        $this->db->bind('i', $this->user['id']);
        $this->db->execute();
    }

    private function updatePassword() {
        $currentPassword = $_POST['current_password'] ?? '';
        $newPassword = $_POST['password'] ?? '';
        $confirmPassword = $_POST['password_confirm'] ?? '';

        // If no new password provided, skip password update
        if (empty($newPassword)) {
            return;
        }

        // Validate current password
        if (empty($currentPassword)) {
            throw new Exception('Current password is required to change your password');
        }

        // Verify current password
        require_once ROOT_PATH . '/app/models/User.php';
        $userModel = new User();
        $user = $userModel->findById($this->user['id']);

        if (!$user || !password_verify($currentPassword, $user['password_hash'])) {
            throw new Exception('Current password is incorrect');
        }

        // Validate new password
        if (strlen($newPassword) < 8) {
            throw new Exception('New password must be at least 8 characters long');
        }

        if ($newPassword !== $confirmPassword) {
            throw new Exception('New passwords do not match');
        }

        // Validate password strength
        if (!preg_match('/[A-Z]/', $newPassword) || 
            !preg_match('/[a-z]/', $newPassword) || 
            !preg_match('/[0-9]/', $newPassword)) {
            throw new Exception('Password must contain uppercase, lowercase, and numbers');
        }

        // Update password
        $passwordHash = password_hash($newPassword, PASSWORD_DEFAULT);

        $this->db->prepare("
            UPDATE users 
            SET password_hash = ?
            WHERE id = ?
        ");
        $this->db->bind('s', $passwordHash);
        $this->db->bind('i', $this->user['id']);
        $this->db->execute();
    }

}
