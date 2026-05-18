# 🔐 InfoHub Authentication - Secure Error Handling Guide

## Overview

This guide documents the secure error handling strategy implemented in the InfoHub authentication system. The system follows **OWASP security principles** to prevent information leakage while providing helpful feedback to legitimate users.

---

## Security Principles

### 1. **No User Enumeration**
- Never reveal whether an email is registered or not
- Use identical responses for "user not found" vs "password incorrect"
- Prevent attackers from building email lists

### 2. **Generic User Messages**
- Show user-friendly messages without technical details
- Hide database errors, system errors, file paths
- Only log detailed errors on server side

### 3. **Detailed Internal Logging**
- Log comprehensive details for debugging and auditing
- Include IP address, user agent, timestamp
- Track all failed attempts and suspicious activity

### 4. **No Information Leakage**
- Never reveal if email exists or doesn't exist
- Never show password requirements when wrong
- Never confirm which field is invalid in login

---

## Message Strategy by Scenario

### Login Form

| Scenario | User Sees | Server Logs | Purpose |
|----------|-----------|------------|---------|
| Email doesn't exist | "Email or password is incorrect" | `login_attempt: false, email: [email]` | Prevents user enumeration |
| Wrong password | "Email or password is incorrect" | `login_attempt: false, email: [email]` | Prevents account targeting |
| Account disabled | "Your account has been disabled" | `login_attempt_inactive: false` | Specific feedback (non-security) |
| Success | "Welcome back!" | `login_success: true, user_id: [id]` | Confirms successful login |
| Missing fields | "Please fill in all fields" | `validation_error: missing_fields` | Helpful user feedback |
| Invalid email format | "Invalid email address" | `validation_error: invalid_email` | Helpful user feedback |

### Registration Form

| Scenario | User Sees | Server Logs | Purpose |
|----------|-----------|------------|---------|
| Email already exists | "Email is already registered" | `registration_attempt: failed, email: [email], reason: email_exists` | Specific feedback (non-sensitive) |
| Password too short | "Password must be at least 8 characters" | `validation_error: password_too_short` | Helpful user feedback |
| Passwords don't match | "Passwords do not match" | `validation_error: passwords_mismatch` | Helpful user feedback |
| Missing fields | "All fields are required" | `validation_error: missing_fields` | Helpful user feedback |
| Success | "Account created! Redirecting to login..." | `registration_success: true, email: [email]` | Confirms registration |

### Forgot Password Form

| Scenario | User Sees | Server Logs | Purpose |
|----------|-----------|------------|---------|
| Email doesn't exist | "If an account exists..." (generic) | `password_reset_request: email_not_found, email: [email]` | Prevents user enumeration |
| Rate limit exceeded | "If an account exists..." (generic) | `password_reset_request: rate_limited, email: [email]` | Blocks brute force silently |
| Success | "If an account exists..." (generic) | `password_reset_request: token_sent, email: [email]` | Same response always |
| CSRF token invalid | 403 Forbidden | `security_violation: csrf_token_invalid` | Security error |

### Password Reset Form

| Scenario | User Sees | Server Logs | Purpose |
|----------|-----------|------------|---------|
| Invalid token | "Invalid or expired reset link" | `password_reset: invalid_token, token: [hash]` | Prevents token misuse |
| Expired token | "Invalid or expired reset link" | `password_reset: token_expired, token: [hash]` | Prevents old token reuse |
| Token already used | "Invalid or expired reset link" | `password_reset: token_used, token: [hash]` | One-time use enforcement |
| Password too short | "Password must be at least 8 characters" | `validation_error: password_too_short` | Helpful user feedback |
| Passwords don't match | "Passwords do not match" | `validation_error: passwords_mismatch` | Helpful user feedback |
| Success | "Password reset successful! Redirecting..." | `password_reset: success, user_id: [id]` | Confirms reset |

---

## Code Implementation

### Secure Error Message Pattern

```php
// ❌ WRONG - Reveals sensitive information
if (!$user) {
    $this->setFlash('error', 'No user found with this email');
    $this->redirect(APP_URL . '/auth/login');
}

// ✅ CORRECT - Generic message, detailed logging
if (!$user) {
    Logger::logAuth($email, 'login_attempt', false);
    $this->setFlash('error', 'Email or password is incorrect');
    $this->redirect(APP_URL . '/auth/login');
}
```

### Logging Pattern

```php
// Always log attempts with details
Logger::logAuth($email, 'login_attempt', false);

// Password mismatch
if (!$this->verifyPassword($password, $user['password_hash'])) {
    Logger::logAuth($email, 'login_attempt', false);
    $this->setFlash('error', 'Email or password is incorrect');
    $this->redirect(APP_URL . '/auth/login');
}
```

### Rate Limiting Pattern

```php
// Check rate limit
$recentAttempts = $userModel->countRecentPasswordResetAttempts($email, 15);
if ($recentAttempts >= 3) {
    // Don't reveal rate limit was exceeded
    Logger::logAuth($email, 'password_reset_rate_limited', false);
    $this->setFlash('info', 'If an account exists with this email...');
    $this->redirect(APP_URL . '/auth/login');
}
```

---

## Flash Message Display

All authentication views use the secure flash messaging system:

```php
<!-- Retrieve flash message (if exists) -->
<?php 
$flash = null;
if (isset($_SESSION['flash'])) {
    $flash = $_SESSION['flash'];
    unset($_SESSION['flash']);  // Remove after reading
}
?>

<!-- Display with appropriate styling -->
<?php if ($flash): ?>
    <?php 
    $alertClass = $flash['type'] === 'error' ? 'alert-danger' : 'alert-success';
    $icon = $flash['type'] === 'error' ? '⚠' : '✓';
    ?>
    <div class="alert <?php echo $alertClass; ?>">
        <strong><?php echo $icon; ?></strong> 
        <?php echo htmlspecialchars($flash['message']); ?>
    </div>
<?php endif; ?>
```

**Key Security Features**:
- Messages are HTML-escaped with `htmlspecialchars()`
- Flash is unset after display (can't be reused)
- Type indicator shows error or success
- No technical details in user messages

---

## Logging Levels

### Detailed Log (Server-Side Only)

```
2026-05-16 14:32:15 | LOGIN_ATTEMPT | FAILED | email=test@example.com | ip=192.168.1.1 | reason=invalid_password
2026-05-16 14:32:20 | LOGIN_ATTEMPT | FAILED | email=test@example.com | ip=192.168.1.1 | reason=user_not_found
2026-05-16 14:32:25 | LOGIN_SUCCESS | email=user@example.com | ip=192.168.1.1 | user_id=5
2026-05-16 14:35:10 | PASSWORD_RESET_REQUEST | email=user@example.com | ip=192.168.1.1 | action=token_sent
2026-05-16 14:35:12 | PASSWORD_RESET_REQUEST | email=attacker@example.com | ip=192.168.1.2 | action=rate_limited | attempts=3
```

### User Sees (Sanitized)

```
Error: Email or password is incorrect
Success: Welcome back!
Info: If an account exists with this email, check your inbox
```

---

## Real System Behavior

### Realistic User Experience

✅ **Registration Flow**:
1. User fills form with invalid data → Shows specific error (e.g., "Password too short")
2. User corrects and submits → Shows success message
3. Redirected to login page

✅ **Login Flow**:
1. User enters wrong password → Shows generic "Email or password is incorrect"
2. User tries non-existent email → Shows same generic message (prevents enumeration)
3. User enters correct credentials → Shows "Welcome back!"

✅ **Password Recovery Flow**:
1. User requests reset for non-existent email → Shows generic "If an account exists..."
2. User requests reset for real account → Shows same generic message
3. User clicks reset link → Form validates new password
4. User enters mismatched passwords → Shows "Passwords do not match"
5. User resets successfully → Shows "Password reset successful!"

### Attack Prevention Examples

**Attack: Email Enumeration**
```
Attacker tries: test@example.com     → "Email or password is incorrect"
Attacker tries: newuser@test.com     → "Email or password is incorrect"
Result: Both return same message - can't determine which emails exist ✓
```

**Attack: Brute Force Password Reset**
```
Attacker requests reset 1x           → "If an account exists..." (rate limit check passes)
Attacker requests reset 2x           → "If an account exists..." (rate limit check passes)
Attacker requests reset 3x           → "If an account exists..." (rate limit check passes - limit reached silently)
Attacker requests reset 4x           → "If an account exists..." (silently blocked, same response)
Result: Attacker has no feedback they've hit the limit ✓
```

**Attack: Token Reuse**
```
Attacker gets reset link: /auth/reset?token=ABC123
Attacker successfully resets password (token marked as used)
Attacker clicks link again with same token → "Invalid or expired reset link"
Result: Token can only be used once ✓
```

---

## Security Checklist

- ✅ No user enumeration (same response for found/not found)
- ✅ No password hints (don't tell users requirements when wrong)
- ✅ No system errors (no stack traces, file paths, database errors)
- ✅ Rate limiting (3 attempts per 15 minutes)
- ✅ CSRF protection (tokens on all POST requests)
- ✅ One-time token use (password reset tokens)
- ✅ Token expiration (1 hour maximum lifetime)
- ✅ Detailed logging (IP, user agent, timestamp)
- ✅ HTML escaping (prevent XSS in flash messages)
- ✅ Session cleanup (flash messages unset after use)

---

## Testing Secure Error Messages

### Test 1: User Enumeration Prevention
```
Test: Login with non-existent email
Expected: "Email or password is incorrect"
Log Check: logs "login_attempt: false"
Result: ✓ PASS
```

### Test 2: Brute Force Protection
```
Test: Request password reset 4 times for same email in 15 minutes
Expected: All 4 show "If an account exists..."
Log Check: 4th attempt shows "rate_limited"
Result: ✓ PASS
```

### Test 3: One-Time Token Use
```
Test: Reset password, then click link again
Expected: Second attempt shows "Invalid or expired reset link"
Log Check: Second attempt shows "token_used"
Result: ✓ PASS
```

### Test 4: CSRF Protection
```
Test: POST without CSRF token
Expected: 403 Forbidden error
Log Check: logs "CSRF Violation"
Result: ✓ PASS
```

### Test 5: XSS Prevention
```
Test: Submit form with <script>alert('xss')</script>
Expected: No JavaScript executed
Log Check: Message displayed with script tags escaped
Result: ✓ PASS
```

---

## Production Checklist

Before deploying to production, verify:

- [ ] All auth views use secure flash messaging
- [ ] All error messages are generic (no user enumeration)
- [ ] All POST requests verify CSRF tokens
- [ ] Rate limiting is enforced (3/15 min)
- [ ] All auth events are logged with IP/user agent
- [ ] Password reset tokens expire after 1 hour
- [ ] Tokens are one-time use only
- [ ] All user messages are HTML-escaped
- [ ] No debug mode enabled in production
- [ ] HTTPS is enabled for all auth pages
- [ ] Secure cookies configured (HttpOnly, Secure, SameSite)
- [ ] Error logs are only readable by admins
- [ ] Monitor for suspicious patterns (multiple attempts, etc.)

---

## FAQ

**Q: Why show "Email or password is incorrect" for both?**  
A: Attackers can't determine which part is wrong or build email lists.

**Q: Why show the same message for rate-limited password resets?**  
A: Prevents attackers from knowing they've hit the limit and adjusting tactics.

**Q: Why log detailed info if users don't see it?**  
A: For administrators to debug issues, monitor for attacks, and audit activity.

**Q: Is it confusing for real users?**  
A: No - legitimate users will use password reset or contact support for help.

**Q: Can an attacker guess password reset tokens?**  
A: No - tokens are 32 bytes (256 bits) of cryptographically secure random data.

**Q: What if someone loses their email?**  
A: They should contact support. The system doesn't confirm email existence for security.

---

## Additional Resources

- [OWASP Authentication Cheat Sheet](https://cheatsheetseries.owasp.org/cheatsheets/Authentication_Cheat_Sheet.html)
- [OWASP Error Handling Cheat Sheet](https://cheatsheetseries.owasp.org/cheatsheets/Error_Handling_Cheat_Sheet.html)
- [CWE-640: Weak Password Recovery Mechanism](https://cwe.mitre.org/data/definitions/640.html)
- [CWE-349: Acceptance of Extraneous Untrusted Data](https://cwe.mitre.org/data/definitions/349.html)

---

**Last Updated**: May 16, 2026  
**Version**: 1.0  
**Status**: Production Ready
