# InfoHub Authentication System Documentation

## Overview

The InfoHub authentication system provides secure, production-ready authentication for user login, registration, and password recovery. The system follows security best practices and includes comprehensive error handling.

## Architecture

### Components

1. **AuthController** (`app/controllers/AuthController.php`)
   - Handles all authentication operations
   - Manages login, registration, password recovery
   - Implements CSRF protection and input validation

2. **User Model** (`app/models/User.php`)
   - Database operations for user management
   - Password reset token generation and verification
   - Rate limiting for password reset attempts

3. **Password Reset Tokens Table** (`password_reset_tokens`)
   - Stores secure reset tokens
   - Tracks token expiration (1 hour)
   - Records IP address and user agent for security

4. **Logger** (`core/Logger.php`)
   - Logs all authentication events
   - Records failed/successful login attempts
   - Tracks registration and password reset activities

## Security Features

### 1. Password Hashing
- **Algorithm**: bcrypt (PASSWORD_BCRYPT)
- **Cost Factor**: 12 (configurable via BCRYPT_COST)
- **Protection**: One-way hashing, resistant to brute force attacks

```php
$hash = password_hash($password, PASSWORD_BCRYPT, ['cost' => 12]);
$isValid = password_verify($password, $hash);
```

### 2. CSRF Protection
- Unique tokens generated per session
- Verified on all state-changing operations (POST requests)
- Prevents cross-site request forgery attacks

### 3. Password Reset Tokens
- **Generation**: 32 bytes of cryptographically secure random data
- **Storage**: Hashed using SHA-256 (token_hash column)
- **Plain Token**: Only sent to user, never stored in plain text
- **Expiration**: 1 hour from generation
- **One-time Use**: Token marked as used after password reset
- **Rate Limiting**: Maximum 3 reset attempts per 15 minutes per email

### 4. Input Validation & Sanitization
- Email validation using PHP's built-in filter
- HTML entity encoding for output
- Database prepared statements (prevent SQL injection)
- Password length minimum: 8 characters

### 5. Error Handling Strategy
- **Principle**: Do not reveal whether email exists (security best practice)
- **Forgot Password**: Same response whether email exists or not
- **Error Logging**: All auth failures logged with timestamps
- **User Feedback**: Generic but helpful error messages

### 6. Session Management
- User data stored in `$_SESSION`
- User ID, email, role, name stored upon login
- Session destroyed on logout
- Form data preserved on validation errors

## Database Schema

### Password Reset Tokens Table

```sql
CREATE TABLE password_reset_tokens (
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
);
```

## API Documentation

### Login

**Endpoint**: `POST /auth/login`

**Form Fields**:
- `email` (required, string): User email address
- `password` (required, string): User password
- `csrf_token` (required, string): CSRF token from session
- `remember` (optional, checkbox): Remember me functionality

**Flow**:
1. Verify CSRF token
2. Validate input (email format)
3. Fetch user from database
4. Verify password using bcrypt
5. Check if account is active
6. Update last_login_at timestamp
7. Create session with user data
8. Log successful/failed attempt

**Responses**:
- ✅ Success: Redirect to homepage with "Welcome back!" message
- ❌ Failure: Redirect back with error message

**Logged Events**:
- `login_success` - Successful login
- `login_attempt` - Failed login
- `login_attempt_inactive` - Account disabled

### Registration

**Endpoint**: `POST /auth/register`

**Form Fields**:
- `first_name` (required, string): User first name
- `last_name` (required, string): User last name
- `email` (required, string): User email address
- `phone` (optional, string): Phone number
- `password` (required, string): Password (min 8 chars)
- `password_confirm` (required, string): Confirm password
- `agree_terms` (required, checkbox): Accept terms
- `csrf_token` (required, string): CSRF token

**Validation Rules**:
- First name: Required, non-empty
- Last name: Required, non-empty
- Email: Required, valid format, unique in database
- Phone: Optional
- Password: Required, minimum 8 characters
- Passwords: Must match
- Terms: Must be accepted

**Flow**:
1. Verify CSRF token
2. Validate all inputs
3. Check if email already exists
4. Hash password with bcrypt
5. Create user record in database
6. Assign default role (Registered User - role_id: 7)
7. Log registration event
8. Redirect to login page

**Responses**:
- ✅ Success: Redirect to login with success message
- ❌ Validation Errors: Redirect back with error array, form data preserved
- ❌ Email Exists: Redirect back with error message

**Logged Events**:
- `registration` - Successful registration
- `registration_email_exists` - Email already registered
- `registration_validation_failed` - Validation errors

### Forgot Password

**Endpoint**: `POST /auth/forgot-password`

**Form Fields**:
- `email` (required, string): User email address
- `csrf_token` (required, string): CSRF token

**Security Features**:
- Always shows same message whether email exists or not
- Rate limited to 3 attempts per 15 minutes
- Token expires in 1 hour
- IP address and user agent recorded
- Email address removed from response

**Flow**:
1. Verify CSRF token
2. Validate email format
3. Check rate limit (max 3 attempts per 15 minutes)
4. Look up user by email
5. Generate secure random token (32 bytes)
6. Create token_hash using SHA-256
7. Store token record with 1 hour expiration
8. Delete any existing unused tokens for user
9. Build reset link (token sent in URL)
10. TODO: Send email with reset link (currently logged to error_log)

**Reset Link Format**:
```
https://localhost/infohub/auth/reset-password?email=user@example.com&token=<64-char-hex>
```

**Responses**:
- Always: "If an account exists with this email, you will receive password reset instructions"

**Logged Events**:
- `forgot_password_token_generated` - Token successfully generated
- `forgot_password_user_not_found` - Email not found
- `forgot_password_invalid_email` - Invalid email format
- `forgot_password_rate_limit` - Too many attempts

### Reset Password (GET)

**Endpoint**: `GET /auth/reset-password`

**Query Parameters**:
- `email` (required, string): User email
- `token` (required, string): Reset token from email

**Flow**:
1. Extract and validate email/token from query params
2. Verify token hasn't expired
3. Verify token matches user email
4. Verify token hasn't been used
5. Display password reset form

**Responses**:
- ✅ Valid Token: Show reset password form
- ❌ Invalid Token: Redirect to login with error

**Logged Events**:
- `reset_password_invalid_email` - Invalid email format
- `reset_password_invalid_token` - Token invalid/expired

### Reset Password (POST)

**Endpoint**: `POST /auth/reset-password`

**Form Fields**:
- `email` (required, string): User email
- `token` (required, string): Reset token
- `password` (required, string): New password (min 8 chars)
- `password_confirm` (required, string): Confirm password
- `csrf_token` (required, string): CSRF token

**Validation Rules**:
- Email: Valid format
- Token: Valid and not expired
- Password: Minimum 8 characters
- Passwords: Must match

**Flow**:
1. Verify CSRF token
2. Validate all inputs
3. Verify token is still valid
4. Hash new password with bcrypt
5. Update user's password_hash
6. Mark token as used (set used_at = NOW())
7. Log successful password reset
8. Redirect to login

**Responses**:
- ✅ Success: Redirect to login with "Password reset successful!" message
- ❌ Invalid Token: Redirect to forgot-password with error
- ❌ Validation Errors: Redirect back with errors

**Logged Events**:
- `password_reset_success` - Successful password reset
- `password_reset_failed` - Reset failed

## Logging & Audit Trail

All authentication events are logged to the database with timestamps for security audit purposes:

### Auth Logs Table

The system logs:
- Successful/failed login attempts
- Registration activities
- Password reset requests
- IP addresses and user agents
- Timestamps of all events

```sql
INSERT INTO auth_logs (email, action, success, ip_address, user_agent, created_at) 
VALUES (?, ?, ?, ?, ?, NOW())
```

## Error Messages

### User-Facing Messages

| Error | Condition | Message |
|-------|-----------|---------|
| Invalid Email | Email format invalid | "Invalid email address" |
| Missing Fields | Required field empty | "Please fill in all fields" |
| Inactive Account | Account disabled | "Your account has been disabled" |
| Bad Credentials | Email/password incorrect | "Email or password is incorrect" |
| Email Exists | Email already registered | "Email is already registered" |
| Password Too Short | Password < 8 characters | "Password must be at least 8 characters" |
| Passwords Don't Match | Confirm password mismatch | "Passwords do not match" |
| First/Last Name Missing | Name fields empty | "First name/Last name is required" |
| Rate Limited | Too many attempts | "If an account exists with this email..." |
| Invalid Reset Link | Token expired/invalid | "Invalid or expired reset link" |

### Internal Logging

All errors are logged to `error_logs` table with:
- Error type (Registration Error, Password Reset Error, etc.)
- Error message
- File and line number
- Timestamp
- IP address (for password reset tokens)

## Configuration

### Password Settings

```php
// In config/database.php or .env
define('BCRYPT_COST', 12);  // Higher = more secure but slower
```

### Token Expiration

```php
// In User.php - createPasswordResetToken()
$expiresAt = date('Y-m-d H:i:s', strtotime('+1 hour'));
```

### Rate Limiting

```php
// In User.php - countRecentPasswordResetAttempts()
$minutes = 15;  // Check last 15 minutes
$maxAttempts = 3;  // Maximum 3 attempts
```

## Testing the System

### 1. Test Registration

```
1. Go to http://localhost/infohub/auth/register
2. Enter valid details
3. Check password validation (< 8 chars should fail)
4. Check password match validation
5. Submit with valid data
6. Should redirect to login with success message
7. Try registering same email again - should show error
```

### 2. Test Login

```
1. Go to http://localhost/infohub/auth/login
2. Try with wrong password - should fail
3. Try with non-existent email - should fail (generic message)
4. Try with valid credentials - should redirect to homepage
5. Check session is set correctly ($_SESSION['user_id'], etc.)
6. Verify logged in state by checking navbar user menu
```

### 3. Test Password Reset

```
1. Go to http://localhost/infohub/auth/forgot-password
2. Enter email and submit
3. Check error_log for reset link (development mode)
4. Copy token from error_log
5. Visit: /auth/reset-password?email=xxx@xxx.com&token=xxxxxx
6. Should show reset password form
7. Enter new password and submit
8. Should redirect to login with success message
9. Try logging in with new password - should work
10. Try using same reset link again - should fail (token already used)
```

### 4. Test Security Features

```
1. CSRF Protection:
   - Remove csrf_token from form - should fail with 403
   
2. Rate Limiting:
   - Request password reset 4 times in 15 minutes - 4th should be blocked
   
3. Password Hashing:
   - Don't store passwords in plain text - check database
   - Hash should start with $2y$ (bcrypt)
   
4. Token Security:
   - Token should be cryptographically random
   - Token_hash should be SHA-256
   - Same email shouldn't generate same token
```

## Future Enhancements

1. **Email Notification**
   - Send password reset link via email
   - Send registration confirmation email
   - Send login attempt notifications

2. **Two-Factor Authentication**
   - SMS OTP verification
   - TOTP (authenticator app) support

3. **Remember Me**
   - Implement persistent login tokens
   - Secure cookie storage

4. **Social Login**
   - Google OAuth integration
   - Facebook Login integration

5. **Account Recovery**
   - Security questions
   - Backup codes

6. **Enhanced Rate Limiting**
   - Exponential backoff
   - IP-based blocking
   - Account lockout after N failed attempts

## Troubleshooting

### "CSRF token validation failed"
- **Cause**: CSRF token missing or mismatched
- **Solution**: Ensure form includes csrf_token hidden field

### "Password reset link not working"
- **Cause**: Token expired (1 hour limit)
- **Solution**: Request new password reset link

### "Account says disabled"
- **Cause**: is_active = false in database
- **Solution**: Admin needs to activate account

### "Email already registered"
- **Cause**: Email exists in database
- **Solution**: Use forgot password to reset, or register with different email

### "Passwords do not match"
- **Cause**: Password and confirm password fields different
- **Solution**: Type passwords carefully, use password visibility toggle

## Security Checklist

- ✅ Passwords hashed with bcrypt (cost 12)
- ✅ CSRF tokens on all POST forms
- ✅ SQL injection prevention via prepared statements
- ✅ XSS prevention via htmlspecialchars()
- ✅ Rate limiting on password reset
- ✅ Reset tokens expire after 1 hour
- ✅ Reset tokens are one-time use
- ✅ Email existence not leaked
- ✅ All auth events logged
- ✅ IP address and user agent recorded
- ✅ Password minimum 8 characters enforced
- ✅ Email format validation
- ✅ Input sanitization on all user input
- ✅ Account activation status checked

## References

- [OWASP Authentication Cheat Sheet](https://cheatsheetseries.owasp.org/cheatsheets/Authentication_Cheat_Sheet.html)
- [PHP Password Hashing](https://www.php.net/manual/en/function.password-hash.php)
- [CSRF Prevention](https://owasp.org/www-community/attacks/csrf)
- [Rate Limiting](https://cheatsheetseries.owasp.org/cheatsheets/Denial_of_Service_Prevention_Cheat_Sheet.html)
