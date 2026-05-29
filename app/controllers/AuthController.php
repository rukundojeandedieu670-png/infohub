<?php
/**
 * Authentication Controller
 */

class AuthController extends Controller {

    public function __construct() {
        parent::__construct();
        // Don't redirect here - let individual methods decide
    }

    /**
     * Show login form
     */
    public function login() {
        // If already logged in, redirect to the role dashboard or home
        if ($this->user) {
            header('Location: ' . $this->getRoleHomeUrl($this->user['role']));
            exit;
        }
        
        $this->view('auth/login', [
            'csrf_token' => $this->generateCSRFToken(),
            'flash' => $this->getFlash()
        ]);
    }

    /**
     * Handle login
     */
    public function handleLogin() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect(APP_URL . '/auth/login');
        }

        $this->verifyCSRFToken($_POST['csrf_token'] ?? '');

        $email = $this->sanitize($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';

        if (empty($email) || empty($password)) {
            $this->setFlash('error', 'Please fill in all fields');
            $this->redirect(APP_URL . '/auth/login');
        }

        if (!$this->validateEmail($email)) {
            Logger::logAuth($email, 'login_attempt', false);
            $this->setFlash('error', 'Invalid email address');
            $this->redirect(APP_URL . '/auth/login');
        }

        // Get user with role
        require_once ROOT_PATH . '/app/models/User.php';
        $userModel = new User();
        $user = $userModel->findByEmailWithRole($email);

        if (!$user) {
            Logger::logAuth($email, 'login_attempt', false);
            $this->setFlash('error', 'Email or password is incorrect');
            $this->redirect(APP_URL . '/auth/login');
        }

        if (!$user['is_active']) {
            Logger::logAuth($email, 'login_attempt_inactive', false);
            $this->setFlash('error', 'Your account has been disabled');
            $this->redirect(APP_URL . '/auth/login');
        }

        if (!$this->verifyPassword($password, $user['password_hash'])) {
            Logger::logAuth($email, 'login_attempt', false);
            $this->setFlash('error', 'Email or password is incorrect');
            $this->redirect(APP_URL . '/auth/login');
        }

        // Update last login
        $userModel->updateLastLogin($user['id']);

        // Set session
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_email'] = $user['email'];
        $_SESSION['user_role'] = $user['role_name'];
        $_SESSION['user_role_id'] = $user['role_id'] ?? null;
        $_SESSION['user_first_name'] = $user['first_name'];
        $_SESSION['user_last_name'] = $user['last_name'];

        // Log successful login
        Logger::logAuth($email, 'login_success', true);
        Logger::logActivity($user['id'], 'login', 'auth', 'User logged in');

        $this->setFlash('success', 'Welcome back!');

        $redirectAfterLogin = $_SESSION['redirect_after_login'] ?? null;
        unset($_SESSION['redirect_after_login']);

        if ($redirectAfterLogin) {
            if (strpos($redirectAfterLogin, APP_URL) === 0) {
                $this->redirect($redirectAfterLogin);
            }

            if (strpos($redirectAfterLogin, '/') === 0) {
                $this->redirect(APP_URL . $redirectAfterLogin);
            }
        }

        $this->redirect($this->getRoleHomeUrl($user['role_name'] ?? null));
    }

    /**
     * Show register form
     */
    public function register() {
        // If already logged in, redirect to home
        if ($this->user) {
            header('Location: ' . APP_URL);
            exit;
        }
        
        $this->view('auth/register', [
            'csrf_token' => $this->generateCSRFToken()
        ]);
    }

    /**
     * Handle registration
     */
    public function handleRegister() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect(APP_URL . '/auth/register');
        }

        $this->verifyCSRFToken($_POST['csrf_token'] ?? '');

        $firstName = $this->sanitize($_POST['first_name'] ?? '');
        $lastName = $this->sanitize($_POST['last_name'] ?? '');
        $email = $this->sanitize($_POST['email'] ?? '');
        $phone = $this->sanitize($_POST['phone'] ?? '');
        $password = $_POST['password'] ?? '';
        $passwordConfirm = $_POST['password_confirm'] ?? '';

        // Store form data in session for repopulation
        $_SESSION['form_data'] = [
            'first_name' => $firstName,
            'last_name' => $lastName,
            'email' => $email,
            'phone' => $phone
        ];

        // Validation
        $errors = [];

        if (empty($firstName)) $errors[] = 'First name is required';
        if (empty($lastName)) $errors[] = 'Last name is required';
        if (empty($email)) $errors[] = 'Email is required';
        if (!$this->validateEmail($email)) $errors[] = 'Invalid email address';
        if (empty($password)) $errors[] = 'Password is required';
        if (strlen($password) < 8) $errors[] = 'Password must be at least 8 characters';
        if ($password !== $passwordConfirm) $errors[] = 'Passwords do not match';

        if (!empty($errors)) {
            $_SESSION['errors'] = $errors;
            Logger::logError('Registration Validation Failed', 'Email: ' . $email . ' | Errors: ' . implode(', ', $errors));
            $this->redirect(APP_URL . '/auth/register');
        }

        // Check if email exists
        require_once ROOT_PATH . '/app/models/User.php';
        $userModel = new User();
        $existingUser = $userModel->findByEmail($email);

        if ($existingUser) {
            unset($_SESSION['form_data']);
            Logger::logAuth($email, 'registration_email_exists', false);
            $this->setFlash('error', 'Email is already registered');
            $this->redirect(APP_URL . '/auth/register');
        }

        // Create user
        try {
            $userId = $userModel->createUser([
                'first_name' => $firstName,
                'last_name' => $lastName,
                'email' => $email,
                'phone' => $phone,
                'password_hash' => $this->hashPassword($password),
                'role_id' => 7 // Default to Registered User role
            ]);

            unset($_SESSION['form_data']);

            Logger::logAuth($email, 'registration', true);
            Logger::logActivity($userId, 'register', 'auth', 'New user registered');

            $this->setFlash('success', 'Registration successful! Please log in.');
            $this->redirect(APP_URL . '/auth/login');

        } catch (Exception $e) {
            Logger::logError('Registration Error', $e->getMessage());
            $this->setFlash('error', 'Registration failed. Please try again.');
            $this->redirect(APP_URL . '/auth/register');
        }
    }

    /**
     * Handle logout
     */
    public function logout() {
        try {
            $userId = $this->user['id'] ?? null;
            
            if ($userId) {
                Logger::logActivity($userId, 'logout', 'auth', 'User logged out');
            }

            // Get session name and id before destroying
            $sessionName = session_name();
            $sessionId = session_id();
            
            // Clear all session data
            $_SESSION = [];
            
            // Delete the session file from disk if it exists
            $sessionPath = ini_get('session.save_path');
            if (empty($sessionPath)) {
                $sessionPath = sys_get_temp_dir();
            }
            $sessionFile = $sessionPath . '/' . 'sess_' . $sessionId;
            if (file_exists($sessionFile)) {
                @unlink($sessionFile);
            }
            
            // Destroy the session
            if (session_status() === PHP_SESSION_ACTIVE) {
                session_destroy();
            }
            
            // Delete the session cookie
            setcookie($sessionName, '', time() - 3600, '/', '', false, true);
            
        } catch (Exception $e) {
            Logger::logError('Logout Error', $e->getMessage());
        }
        
        // Redirect to home
        header('Location: ' . APP_URL);
        exit;
    }

    /**
     * Forgot password form
     */
    public function forgotPassword() {
        $this->view('auth/forgot-password', [
            'csrf_token' => $this->generateCSRFToken()
        ]);
    }

    /**
     * Return the landing URL for a role after login.
     */
    protected function getRoleHomeUrl($roleName) {
        if (empty($roleName) || !is_string($roleName)) {
            return APP_URL;
        }

        $roleName = trim($roleName);

        $map = [
            'Super Admin' => APP_URL . '/admin/dashboard',
            'Admin' => APP_URL . '/admin/dashboard',
            'Editor' => APP_URL . '/news',
            'Writer' => APP_URL . '/news',
            'Business Owner' => APP_URL . '/business-owner/dashboard',
            'Employer' => APP_URL . '/employer/jobs',
            'Registered User' => APP_URL . '/profile'
        ];

        return $map[$roleName] ?? APP_URL;
    }

    /**
     * Handle forgot password - securely send reset link
     * Does NOT reveal whether email exists (security best practice)
     */
    public function handleForgotPassword() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect(APP_URL . '/auth/forgot-password');
        }

        try {
            $this->verifyCSRFToken($_POST['csrf_token'] ?? '');
            
            $email = $this->sanitize($_POST['email'] ?? '');

            if (empty($email)) {
                $this->setFlash('error', 'Please enter your email address');
                $this->redirect(APP_URL . '/auth/forgot-password');
            }

            if (!$this->validateEmail($email)) {
                // Security: Always show same message whether email exists or not
                Logger::logAuth($email, 'forgot_password_invalid_email', false);
                $this->setFlash('info', 'If an account exists with this email, you will receive password reset instructions');
                $this->redirect(APP_URL . '/auth/login');
            }

            require_once ROOT_PATH . '/app/models/User.php';
            $userModel = new User();

            // Check rate limiting - max 3 attempts per 15 minutes
            $recentAttempts = $userModel->countRecentPasswordResetAttempts($email, 15);
            if ($recentAttempts >= 3) {
                Logger::logAuth($email, 'forgot_password_rate_limit', false);
                $this->setFlash('info', 'If an account exists with this email, you will receive password reset instructions');
                $this->redirect(APP_URL . '/auth/login');
            }

            // Check if user exists
            $user = $userModel->findByEmail($email);
            
            if (!$user) {
                // Security: Same response whether user exists or not
                Logger::logAuth($email, 'forgot_password_user_not_found', false);
                $this->setFlash('info', 'If an account exists with this email, you will receive password reset instructions');
                $this->redirect(APP_URL . '/auth/login');
            }

            // Generate reset token
            $plainToken = $userModel->createPasswordResetToken($user['id']);
            
            if (!$plainToken) {
                Logger::logError('Password Reset Token Generation Failed', "Email: {$email}");
                $this->setFlash('error', 'An error occurred. Please try again later.');
                $this->redirect(APP_URL . '/auth/forgot-password');
            }

            // Build reset link
            $resetLink = APP_URL . '/auth/reset-password?email=' . urlencode($email) . '&token=' . urlencode($plainToken);

            // TODO: Send email with reset link
            // For now, log it for development
            Logger::logAuth($email, 'forgot_password_token_generated', true);
            error_log("Password Reset Link for {$email}: {$resetLink}");

            // Security: Always show success message
            $this->setFlash('info', 'If an account exists with this email, you will receive password reset instructions');
            $this->redirect(APP_URL . '/auth/login');

        } catch (Exception $e) {
            Logger::logError('Forgot Password Error', $e->getMessage());
            $this->setFlash('error', 'An error occurred. Please try again later.');
            $this->redirect(APP_URL . '/auth/forgot-password');
        }
    }

    /**
     * Show reset password form with token
     */
    public function resetPassword() {
        $email = $_GET['email'] ?? '';
        $token = $_GET['token'] ?? '';

        if (empty($email) || empty($token)) {
            $this->setFlash('error', 'Invalid reset link');
            $this->redirect(APP_URL . '/auth/login');
        }

        $email = $this->sanitize($email);

        if (!$this->validateEmail($email)) {
            Logger::logAuth($email, 'reset_password_invalid_email', false);
            $this->setFlash('error', 'Invalid email address');
            $this->redirect(APP_URL . '/auth/login');
        }

        try {
            require_once ROOT_PATH . '/app/models/User.php';
            $userModel = new User();

            // Verify token is valid
            $userId = $userModel->verifyPasswordResetToken($email, $token);
            
            if (!$userId) {
                Logger::logAuth($email, 'reset_password_invalid_token', false);
                $this->setFlash('error', 'Invalid or expired reset link');
                $this->redirect(APP_URL . '/auth/login');
            }

            // Token is valid, show reset form
            $this->view('auth/reset-password', [
                'email' => $email,
                'token' => $token,
                'csrf_token' => $this->generateCSRFToken()
            ]);

        } catch (Exception $e) {
            Logger::logError('Reset Password Form Error', $e->getMessage());
            $this->setFlash('error', 'An error occurred. Please try again.');
            $this->redirect(APP_URL . '/auth/login');
        }
    }

    /**
     * Handle password reset - update password
     */
    public function handleResetPassword() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect(APP_URL . '/auth/login');
        }

        try {
            $this->verifyCSRFToken($_POST['csrf_token'] ?? '');

            $email = $this->sanitize($_POST['email'] ?? '');
            $token = $_POST['token'] ?? '';
            $password = $_POST['password'] ?? '';
            $passwordConfirm = $_POST['password_confirm'] ?? '';

            // Validation
            if (empty($email) || empty($token) || empty($password)) {
                $this->setFlash('error', 'All fields are required');
                $this->redirect(APP_URL . '/auth/reset-password?email=' . urlencode($email) . '&token=' . urlencode($token));
            }

            if (!$this->validateEmail($email)) {
                Logger::logAuth($email, 'reset_password_invalid_email', false);
                $this->setFlash('error', 'Invalid email address');
                $this->redirect(APP_URL . '/auth/login');
            }

            if (strlen($password) < 8) {
                $this->setFlash('error', 'Password must be at least 8 characters');
                $this->redirect(APP_URL . '/auth/reset-password?email=' . urlencode($email) . '&token=' . urlencode($token));
            }

            if ($password !== $passwordConfirm) {
                $this->setFlash('error', 'Passwords do not match');
                $this->redirect(APP_URL . '/auth/reset-password?email=' . urlencode($email) . '&token=' . urlencode($token));
            }

            require_once ROOT_PATH . '/app/models/User.php';
            $userModel = new User();

            // Verify token and update password
            $success = $userModel->completePasswordReset($email, $token, $password);

            if (!$success) {
                Logger::logAuth($email, 'reset_password_failed', false);
                $this->setFlash('error', 'Invalid or expired reset link. Please try again.');
                $this->redirect(APP_URL . '/auth/forgot-password');
            }

            Logger::logAuth($email, 'password_reset_success', true);
            $this->setFlash('success', 'Password reset successful! You can now log in with your new password.');
            $this->redirect(APP_URL . '/auth/login');

        } catch (Exception $e) {
            Logger::logError('Password Reset Error', $e->getMessage());
            $this->setFlash('error', 'An error occurred. Please try again later.');
            $this->redirect(APP_URL . '/auth/forgot-password');
        }
    }

    /**
     * Redirect to Google OAuth
     */
    public function googleLogin() {
        require_once ROOT_PATH . '/core/GoogleAuth.php';
        $googleAuth = new GoogleAuth();
        header('Location: ' . $googleAuth->getAuthorizationUrl());
        exit;
    }

    /**
     * Handle Google OAuth callback
     */
    public function googleCallback() {
        try {
            $code = $_GET['code'] ?? null;
            $state = $_GET['state'] ?? null;

            if (!$code || !$state) {
                throw new Exception('Missing authorization code or state');
            }

            require_once ROOT_PATH . '/core/GoogleAuth.php';
            $googleAuth = new GoogleAuth();

            // Verify state to prevent CSRF
            if (!$googleAuth->verifyState($state)) {
                throw new Exception('Invalid OAuth state');
            }

            // Exchange code for access token
            $tokenResponse = $googleAuth->getAccessToken($code);

            if (isset($tokenResponse['error'])) {
                throw new Exception('Failed to get access token: ' . $tokenResponse['error']);
            }

            $accessToken = $tokenResponse['access_token'];

            // Get user info
            $userInfo = $googleAuth->getUserInfo($accessToken);

            if (!isset($userInfo['email'])) {
                throw new Exception('Failed to get user email from Google');
            }

            // Clear OAuth state
            $googleAuth->clearState();

            // Check if user exists
            require_once ROOT_PATH . '/app/models/User.php';
            $userModel = new User();
            $existingUser = $userModel->findByEmail($userInfo['email']);

            if ($existingUser) {
                // Login existing user
                $user = $userModel->findByEmailWithRole($userInfo['email']);
                
                if (!$user['is_active']) {
                    Logger::logAuth($userInfo['email'], 'login_attempt_inactive', false);
                    $this->setFlash('error', 'Your account has been disabled');
                    $this->redirect(APP_URL . '/auth/login');
                }

                // Update last login
                $userModel->updateLastLogin($user['id']);

                // Set session
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_email'] = $user['email'];
                $_SESSION['user_role'] = $user['role_name'];
                $_SESSION['user_role_id'] = $user['role_id'] ?? null;
                $_SESSION['user_first_name'] = $user['first_name'];
                $_SESSION['user_last_name'] = $user['last_name'];

                Logger::logAuth($userInfo['email'], 'google_login_success', true);
                $this->setFlash('success', 'Welcome back!');
                $this->redirect(APP_URL);
            } else {
                // Create new user from Google info
                $firstName = $userInfo['given_name'] ?? 'User';
                $lastName = $userInfo['family_name'] ?? '';
                $email = $userInfo['email'];

                // Get default role (Registered User)
                $db = Database::getInstance();
                $db->prepare("SELECT id FROM roles WHERE name = 'Registered User' LIMIT 1");
                $db->execute();
                $role = $db->single();
                $role_id = $role['id'] ?? 7;

                // Create user with random password (they won't use it with Google OAuth)
                $randomPassword = bin2hex(random_bytes(16));
                $passwordHash = password_hash($randomPassword, PASSWORD_BCRYPT, ['cost' => 12]);

                $db->prepare("INSERT INTO users (first_name, last_name, email, password_hash, role_id, is_active) 
                              VALUES (?, ?, ?, ?, ?, 1)");
                $db->bindArray('ssssi', [$firstName, $lastName, $email, $passwordHash, $role_id]);
                $db->execute();

                $newUserId = $db->lastInsertId();

                // Set session
                $_SESSION['user_id'] = $newUserId;
                $_SESSION['user_email'] = $email;
                $_SESSION['user_role'] = 'Registered User';
                $_SESSION['user_role_id'] = $role_id;
                $_SESSION['user_first_name'] = $firstName;
                $_SESSION['user_last_name'] = $lastName;

                Logger::logAuth($email, 'google_signup_success', true);
                $this->setFlash('success', 'Account created successfully! Welcome to InfoHub!');
                $this->redirect(APP_URL . '/profile/edit');
            }

        } catch (Exception $e) {
            Logger::logError('Google OAuth Error', $e->getMessage());
            $this->setFlash('error', 'Google login failed. Please try again.');
            $this->redirect(APP_URL . '/auth/login');
        }
    }
}
