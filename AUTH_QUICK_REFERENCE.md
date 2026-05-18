# Authentication System - Developer Quick Reference

## Routes

| HTTP | Route | Controller Method | Purpose |
|------|-------|------------------|---------|
| GET | `/auth/login` | `login()` | Show login form |
| POST | `/auth/login` | `handleLogin()` | Process login |
| GET | `/auth/register` | `register()` | Show registration form |
| POST | `/auth/register` | `handleRegister()` | Process registration |
| GET | `/auth/logout` | `logout()` | Clear session & logout |
| GET | `/auth/forgot-password` | `forgotPassword()` | Show forgot password form |
| POST | `/auth/forgot-password` | `handleForgotPassword()` | Send password reset email |
| GET | `/auth/reset-password` | `resetPassword()` | Show reset form (with token) |
| POST | `/auth/reset-password` | `handleResetPassword()` | Complete password reset |

## User Model Methods

```php
// Find user by email
$user = $userModel->findByEmail($email);

// Find user with role
$user = $userModel->findByEmailWithRole($email);

// Create new user
$userId = $userModel->createUser([
    'first_name' => 'John',
    'last_name' => 'Doe',
    'email' => 'john@example.com',
    'phone' => '+250788123456',
    'password_hash' => password_hash($pass, PASSWORD_BCRYPT),
    'role_id' => 7
]);

// Generate password reset token (returns plain token)
$plainToken = $userModel->createPasswordResetToken($userId);

// Verify password reset token
$userId = $userModel->verifyPasswordResetToken($email, $plainToken);

// Complete password reset
$success = $userModel->completePasswordReset($email, $plainToken, $newPassword);

// Check if email exists
$exists = $userModel->emailExists($email);

// Count recent password reset attempts
$count = $userModel->countRecentPasswordResetAttempts($email, 15); // Last 15 minutes
```

## Session Variables

```php
$_SESSION['user_id']           // User ID
$_SESSION['user_email']        // User email
$_SESSION['user_role']         // User role name
$_SESSION['user_first_name']   // First name
$_SESSION['user_last_name']    // Last name
$_SESSION['csrf_token']        // CSRF token
$_SESSION['flash']             // Flash message ['type' => 'success/error', 'message' => '...']
$_SESSION['errors']            // Validation errors (array)
$_SESSION['form_data']         // Form data on error
```

## Controller Methods

```php
// Generate CSRF token
$token = $this->generateCSRFToken();

// Verify CSRF token
$this->verifyCSRFToken($_POST['csrf_token']);

// Check user is logged in
$this->user; // null if not logged in

// Require login
$this->requireLogin();

// Require admin
$this->requireAdmin();

// Sanitize input
$email = $this->sanitize($_POST['email']);

// Validate email
$valid = $this->validateEmail($email);

// Hash password
$hash = $this->hashPassword($password);

// Verify password
$valid = $this->verifyPassword($password, $hash);

// Set flash message
$this->setFlash('success', 'Logged in successfully!');

// Redirect
$this->redirect(APP_URL . '/auth/login');
```

## Database Queries

```sql
-- Get user with role
SELECT u.*, r.name as role_name 
FROM users u 
LEFT JOIN roles r ON u.role_id = r.id 
WHERE u.email = ?;

-- Create password reset token
INSERT INTO password_reset_tokens 
(user_id, token_hash, expires_at, ip_address, user_agent)
VALUES (?, ?, ?, ?, ?);

-- Verify password reset token
SELECT prt.*, u.id as user_id, u.email 
FROM password_reset_tokens prt
JOIN users u ON prt.user_id = u.id
WHERE u.email = ? 
AND prt.token_hash = ? 
AND prt.expires_at > NOW() 
AND prt.used_at IS NULL;

-- Mark token as used
UPDATE password_reset_tokens 
SET used_at = NOW() 
WHERE token_hash = ? AND used_at IS NULL;

-- Count recent password reset attempts
SELECT COUNT(*) as count 
FROM password_reset_tokens prt
JOIN users u ON prt.user_id = u.id
WHERE u.email = ? 
AND prt.created_at > DATE_SUB(NOW(), INTERVAL 15 MINUTE);

-- Get auth logs for user
SELECT * FROM auth_logs 
WHERE email = ? 
ORDER BY created_at DESC;
```

## Error Messages

| Condition | Message |
|-----------|---------|
| Email invalid | "Invalid email address" |
| Email required | "Email is required" |
| Email exists | "Email is already registered" |
| Password invalid | "Password must be at least 8 characters" |
| Passwords mismatch | "Passwords do not match" |
| Password required | "Password is required" |
| Bad credentials | "Email or password is incorrect" |
| Account inactive | "Your account has been disabled" |
| Missing fields | "Please fill in all fields" |
| Invalid reset link | "Invalid or expired reset link" |
| CSRF failed | "CSRF token validation failed" |

## Logging

```php
// Log successful login
Logger::logAuth($email, 'login_success', true);

// Log failed login
Logger::logAuth($email, 'login_attempt', false);

// Log registration
Logger::logAuth($email, 'registration', true);

// Log user activity
Logger::logActivity($userId, 'login', 'auth', 'User logged in');

// Log error
Logger::logError('Registration Error', $e->getMessage());
```

## Security Checklist

- [ ] Always hash passwords with `password_hash($pass, PASSWORD_BCRYPT)`
- [ ] Always verify with `password_verify($pass, $hash)`
- [ ] Always include CSRF token in forms: `<input type="hidden" name="csrf_token">`
- [ ] Always sanitize output with `htmlspecialchars()`
- [ ] Always use prepared statements for queries
- [ ] Never reveal if email exists on forgot password
- [ ] Always verify CSRF token on POST requests
- [ ] Never log passwords or tokens in plain text
- [ ] Always check `is_active` flag on login
- [ ] Always expire password reset tokens (1 hour)
- [ ] Always rate limit password reset (3 per 15 min)
- [ ] Always update `last_login_at` on successful login

## Common Code Patterns

### Check if user is logged in
```php
if ($this->user) {
    // User is logged in
    echo $this->user['email'];
}
```

### Require authentication
```php
$this->requireLogin(); // Redirects to login if not authenticated
```

### Show error message
```php
$this->setFlash('error', 'Something went wrong');
$this->redirect(APP_URL . '/page');
```

### Show success message
```php
$this->setFlash('success', 'Action completed!');
$this->redirect(APP_URL . '/page');
```

### Hash and verify password
```php
// Hash on registration
$hash = $this->hashPassword($password);

// Verify on login
if ($this->verifyPassword($password, $hash)) {
    // Password is correct
}
```

### Prevent CSRF attacks
```php
// In form
<input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">

// In controller
$this->verifyCSRFToken($_POST['csrf_token'] ?? '');
```

### Preserve form on error
```php
// Store in session
$_SESSION['form_data'] = ['email' => $email, 'name' => $name];

// Retrieve in view
value="<?php echo $_SESSION['form_data']['email'] ?? ''; ?>"

// Clear after display
unset($_SESSION['form_data']);
```

## Testing Commands

```php
// Test password hashing
$hash = password_hash('testpass', PASSWORD_BCRYPT, ['cost' => 12]);
var_dump(password_verify('testpass', $hash)); // true

// Test email validation
var_dump(filter_var('test@example.com', FILTER_VALIDATE_EMAIL)); // string
var_dump(filter_var('invalid', FILTER_VALIDATE_EMAIL)); // false

// Test CSRF token
session_start();
$token = bin2hex(random_bytes(32));
echo $token;
```

## Performance Tips

- Passwords take ~200ms to hash (bcrypt cost 12)
- Use indexes on frequently queried columns
- Cache user data in session to avoid repeated DB queries
- Use prepared statements (prevents SQL injection AND is faster)
- Clean up expired password reset tokens monthly

## Configuration Constants

```php
define('BCRYPT_COST', 12);  // Password hashing cost
define('APP_URL', 'http://localhost/infohub');  // Application URL
define('ROOT_PATH', __DIR__);  // Root directory
```

## Forms HTML Template

```php
<!-- Login Form -->
<form method="POST" action="<?php echo APP_URL; ?>/auth/login">
    <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($csrf_token); ?>">
    <input type="email" name="email" placeholder="Email" required>
    <input type="password" name="password" placeholder="Password" required>
    <button type="submit">Login</button>
</form>

<!-- Register Form -->
<form method="POST" action="<?php echo APP_URL; ?>/auth/register">
    <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($csrf_token); ?>">
    <input type="text" name="first_name" placeholder="First Name" required>
    <input type="text" name="last_name" placeholder="Last Name" required>
    <input type="email" name="email" placeholder="Email" required>
    <input type="tel" name="phone" placeholder="Phone (optional)">
    <input type="password" name="password" placeholder="Password (min 8)" required>
    <input type="password" name="password_confirm" placeholder="Confirm Password" required>
    <input type="checkbox" name="agree_terms" required> I agree to terms
    <button type="submit">Register</button>
</form>

<!-- Forgot Password Form -->
<form method="POST" action="<?php echo APP_URL; ?>/auth/forgot-password">
    <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($csrf_token); ?>">
    <input type="email" name="email" placeholder="Email" required>
    <button type="submit">Send Reset Link</button>
</form>

<!-- Reset Password Form -->
<form method="POST" action="<?php echo APP_URL; ?>/auth/reset-password">
    <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($csrf_token); ?>">
    <input type="hidden" name="email" value="<?php echo htmlspecialchars($email); ?>">
    <input type="hidden" name="token" value="<?php echo htmlspecialchars($token); ?>">
    <input type="password" name="password" placeholder="New Password" required>
    <input type="password" name="password_confirm" placeholder="Confirm Password" required>
    <button type="submit">Reset Password</button>
</form>
```

## Quick Fixes

| Issue | Fix |
|-------|-----|
| Password hash failing | Ensure PASSWORD_BCRYPT is available (PHP 5.3+) |
| Session not persisting | Check php.ini session settings |
| Redirect not working | Ensure no output sent before header() |
| CSRF token invalid | Token case-sensitive, check $_SESSION['csrf_token'] |
| Email not found | Verify DB connection and user exists |
| Rate limit not working | Check DB query for recent attempts |

---

**Last Updated**: May 16, 2026
**Version**: 1.0
**Status**: Production Ready
