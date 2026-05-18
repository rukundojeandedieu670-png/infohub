# Authentication System - Quick Setup & Testing Guide

## Setup

### 1. Create Password Reset Table

Run this migration to create the password_reset_tokens table:

**Option A: Via Browser**
```
Visit: http://localhost/infohub/run_migration.php
```

**Option B: Via MySQL Workbench/CLI**
```sql
-- Copy and paste the contents of database/password_reset_migration.sql
CREATE TABLE IF NOT EXISTS password_reset_tokens (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  user_id INT UNSIGNED NOT NULL,
  token VARCHAR(255) NOT NULL UNIQUE,
  token_hash VARCHAR(255) NOT NULL,
  expires_at TIMESTAMP NOT NULL,
  used_at TIMESTAMP NULL,
  ip_address VARCHAR(45),
  user_agent VARCHAR(500),
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
  INDEX idx_user_id (user_id),
  INDEX idx_token_hash (token_hash),
  INDEX idx_expires_at (expires_at),
  INDEX idx_used_at (used_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

### 2. Updated Routes

The following routes have been configured:

```php
// Login/Register
POST /auth/login
GET /auth/login
POST /auth/register
GET /auth/register

// Password Recovery
GET /auth/forgot-password
POST /auth/forgot-password
GET /auth/reset-password (with email & token query params)
POST /auth/reset-password
```

### 3. Key Files Modified

- ✅ `app/controllers/AuthController.php` - Added handleForgotPassword, resetPassword, handleResetPassword
- ✅ `app/models/User.php` - Added password reset token methods
- ✅ `index.php` - Added password reset routes
- ✅ `app/views/auth/reset-password.php` - Created new view
- ✅ `app/views/auth/*.php` - Enhanced with error display
- ✅ `database/password_reset_migration.sql` - Updated with complete table
- ✅ `core/Logger.php` - Fixed pass-by-reference binding issues

## Testing Scenarios

### Test 1: User Registration

**Steps:**
1. Go to http://localhost/infohub/auth/register
2. Fill in the form:
   - First Name: John
   - Last Name: Doe
   - Email: john@example.com
   - Phone: +250788123456
   - Password: SecurePass123
   - Confirm Password: SecurePass123
   - Check "I agree to terms"
3. Click "Create Account"

**Expected Result:**
- ✅ Form validates all fields
- ✅ Shows specific error for invalid password (< 8 chars)
- ✅ Shows error if passwords don't match
- ✅ Shows error if email already exists
- ✅ Redirects to login with "Registration successful!" message
- ✅ User can now log in with new credentials

**Database Check:**
```sql
SELECT id, email, password_hash, created_at FROM users WHERE email = 'john@example.com';
-- Should show bcrypt hash starting with $2y$
```

---

### Test 2: User Login

**Steps:**
1. Go to http://localhost/infohub/auth/login
2. Enter email: john@example.com
3. Enter password: SecurePass123
4. Click "Sign In"

**Expected Result:**
- ✅ Session is created
- ✅ Redirects to homepage
- ✅ Shows "Welcome back!" message
- ✅ User menu shows in navbar
- ✅ Can access protected pages

**Failed Login Test:**
1. Enter wrong password: WrongPass123
2. Click "Sign In"

**Expected Result:**
- ✅ Shows "Email or password is incorrect" (doesn't reveal which is wrong)
- ✅ Stays on login page
- ✅ Email field is repopulated

**Database Check:**
```sql
SELECT * FROM auth_logs WHERE email = 'john@example.com' ORDER BY created_at DESC;
-- Should show login_success and/or login_attempt records
```

---

### Test 3: Forgot Password Flow

**Steps:**
1. Go to http://localhost/infohub/auth/forgot-password
2. Enter email: john@example.com
3. Click "Send Reset Link"

**Expected Result:**
- ✅ Shows "If an account exists with this email, you will receive password reset instructions"
- ✅ Redirects to login page
- ✅ Token is generated and stored (1 hour expiration)

**In Development (Check Error Log):**
1. Open PHP error log or check browser console
2. Look for password reset link like:
   ```
   http://localhost/infohub/auth/reset-password?email=john@example.com&token=abc123def456...
   ```

**Database Check:**
```sql
SELECT * FROM password_reset_tokens WHERE user_id = 1 ORDER BY created_at DESC;
-- Should show latest token with expires_at in future
-- token_hash should be SHA-256 hash
-- used_at should be NULL
```

---

### Test 4: Reset Password with Valid Token

**Steps:**
1. From the reset link URL above, visit:
   ```
   http://localhost/infohub/auth/reset-password?email=john@example.com&token=<full-token>
   ```

**Expected Result:**
- ✅ Reset password form displays
- ✅ Shows password requirements (min 8 chars)
- ✅ No errors

**If Token Invalid/Expired:**
- ✅ Shows "Invalid or expired reset link"
- ✅ Redirects to login

2. Enter new password:
   - Password: NewSecurePass789
   - Confirm: NewSecurePass789
3. Click "Reset Password"

**Expected Result:**
- ✅ Form validates passwords
- ✅ Shows "Password reset successful! You can now log in with your new password."
- ✅ Redirects to login page
- ✅ Token is marked as used (used_at = NOW())

**Test Login with New Password:**
1. Go back to login
2. Enter: john@example.com / NewSecurePass789
3. Should successfully log in ✅

**Database Check:**
```sql
SELECT * FROM password_reset_tokens WHERE user_id = 1 ORDER BY created_at DESC LIMIT 1;
-- Should show: used_at = current timestamp
-- Try using same link again - should fail
```

---

### Test 5: Security - Rate Limiting

**Steps:**
1. Request password reset 4 times within 15 minutes
   - Request 1: ✅ Success
   - Request 2: ✅ Success
   - Request 3: ✅ Success
   - Request 4: ⚠️ Rate limited

**Expected Result:**
- ✅ First 3 requests generate tokens
- ✅ 4th request shows same generic message
- ✅ But doesn't generate token

**Database Check:**
```sql
SELECT COUNT(*) as attempts FROM password_reset_tokens 
WHERE user_id = 1 
AND created_at > DATE_SUB(NOW(), INTERVAL 15 MINUTE);
-- Should show 3
```

---

### Test 6: Security - CSRF Protection

**Steps:**
1. Create a test form that POSTs to /auth/login WITHOUT csrf_token
2. Or use browser dev tools to remove csrf_token from form

**Expected Result:**
- ✅ Shows "CSRF token validation failed" 403 error
- ✅ Request is rejected

---

### Test 7: Security - Token Reuse Prevention

**Steps:**
1. Get a valid password reset token
2. Use it to reset password successfully
3. Try using the SAME token again

**Expected Result:**
- ✅ First use: ✅ Password resets successfully
- ✅ Second use: "Invalid or expired reset link"

**Database Check:**
```sql
SELECT * FROM password_reset_tokens WHERE token_hash = 'xxx' LIMIT 1;
-- Should show: used_at is NOT NULL after first use
```

---

### Test 8: Email Privacy (Email Not Found)

**Steps:**
1. Go to forgot-password
2. Enter email that doesn't exist: nonexistent@example.com
3. Click "Send Reset Link"

**Expected Result:**
- ✅ Shows "If an account exists with this email, you will receive password reset instructions"
- ✅ Same message as when email exists (doesn't leak user existence)
- ✅ No token generated

**Database Check:**
```sql
-- No new token should be in password_reset_tokens
```

---

### Test 9: Account Disabled

**Steps:**
1. Manually set user to inactive:
   ```sql
   UPDATE users SET is_active = FALSE WHERE email = 'john@example.com';
   ```
2. Try to log in with that user

**Expected Result:**
- ✅ Shows "Your account has been disabled"
- ✅ Redirects to login

---

### Test 10: Form Data Persistence on Error

**Steps:**
1. Go to registration page
2. Fill in form with invalid data:
   - First Name: John
   - Email: invalid-email (not @ format)
   - Password: short
3. Click submit

**Expected Result:**
- ✅ Shows validation errors
- ✅ Form fields are repopulated with entered data (except password)
- ✅ User doesn't have to re-type everything

---

## Email Integration (TODO)

Currently, password reset links are logged to PHP error_log for development.

To implement email sending:

1. **Install email library:**
   ```bash
   composer require phpmailer/phpmailer
   ```

2. **Update `handleForgotPassword()` in AuthController:**
   ```php
   // Send email instead of logging
   $mail = new PHPMailer(true);
   $mail->addAddress($email);
   $mail->Subject = 'Password Reset Request';
   $mail->Body = "Click here to reset: {$resetLink}";
   $mail->send();
   ```

3. **Configure SMTP in config:**
   - Set SMTP host, port, username, password
   - Or use SendGrid/Mailgun API

---

## Debugging Tips

### Check Auth Logs
```sql
SELECT * FROM auth_logs 
WHERE email = 'john@example.com' 
ORDER BY created_at DESC LIMIT 10;
```

### Check Password Reset Tokens
```sql
SELECT prt.*, u.email, u.first_name 
FROM password_reset_tokens prt
JOIN users u ON prt.user_id = u.id
ORDER BY prt.created_at DESC LIMIT 5;
```

### Check User Session
```php
// In any controller or view
echo "<pre>";
print_r($_SESSION);
echo "</pre>";
```

### Check Password Hash
```php
$hash = '$2y$12$abc123...';
$password = 'testpass';
var_dump(password_verify($password, $hash)); // Should return true
```

---

## Performance Considerations

- **Password hashing**: bcrypt cost 12 is ~200ms per hash
- **Rate limiting**: Database query for last 15 minutes of attempts
- **Token lookup**: Indexed on token_hash for fast verification
- **Session**: Stored in browser cookie (default PHP session handler)

---

## Production Checklist

- [ ] Update BCRYPT_COST based on server performance
- [ ] Implement email sending for password reset links
- [ ] Enable HTTPS for all auth pages
- [ ] Configure secure cookie settings in php.ini
- [ ] Set up log rotation for error_logs table
- [ ] Monitor auth_logs for brute force attempts
- [ ] Implement 2FA for admin accounts
- [ ] Set up email notifications for suspicious activity
- [ ] Test backup/recovery procedures
- [ ] Configure automated password_reset_tokens cleanup

---

## Files Changed Summary

```
✅ app/controllers/AuthController.php - 60+ new lines for password reset
✅ app/models/User.php - 80+ new lines for token management
✅ app/views/auth/login.php - Error display enhancement
✅ app/views/auth/register.php - Error display + form persistence
✅ app/views/auth/forgot-password.php - Error display enhancement
✅ app/views/auth/reset-password.php - NEW FILE
✅ core/Logger.php - Fixed pass-by-reference issues
✅ index.php - Added 2 new routes
✅ database/password_reset_migration.sql - Updated migration
✅ run_migration.php - Migration runner
✅ AUTHENTICATION_GUIDE.md - NEW DOCUMENTATION
```

**Total Changes**: 400+ lines of code
**Total Lines of Documentation**: 500+ lines
**Security Features**: 8+ implemented
**Test Scenarios**: 10+ covered

---

## Support

For issues or questions:
1. Check AUTHENTICATION_GUIDE.md for detailed API docs
2. Review error_logs table for system errors
3. Check auth_logs table for authentication events
4. Enable debug mode and check browser console

