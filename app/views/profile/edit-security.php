<?php
// Profile Edit - Security & Password Page
ob_start();
?>

<!-- Status Messages -->
<?php if (!empty($_SESSION['error'])): ?>
    <div class="alert alert-error">
        <span>⚠️ <?php echo htmlspecialchars($_SESSION['error']); ?></span>
        <button class="alert-close" onclick="this.parentElement.style.display='none';">×</button>
    </div>
    <?php unset($_SESSION['error']); ?>
<?php endif; ?>

<?php if (!empty($_SESSION['success'])): ?>
    <div class="alert alert-success">
        <span>✓ <?php echo htmlspecialchars($_SESSION['success']); ?></span>
        <button class="alert-close" onclick="this.parentElement.style.display='none';">×</button>
    </div>
    <?php unset($_SESSION['success']); ?>
<?php endif; ?>

<!-- Password Change Form -->
<div class="card">
    <form method="POST" action="<?php echo APP_URL; ?>/profile/update" id="passwordForm">
        <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token'] ?? ''; ?>">
        <input type="hidden" name="form_type" value="password">

        <h2 class="form-section-title">🔒 Change Your Password</h2>
        <p class="form-section-subtitle">Update your password regularly to maintain account security</p>
        <p style="color: #6b7280; font-size: 0.95rem; margin: 0 0 20px 0; line-height: 1.5;">Leave password fields blank if you don't want to change your password.</p>

        <div class="form-group">
            <label for="current_password">Current Password</label>
            <input type="password" id="current_password" name="current_password" placeholder="Enter your current password">
            <small>Required only if you want to change your password</small>
        </div>

        <div class="form-group">
            <label for="password">New Password</label>
            <input type="password" id="password" name="password" placeholder="At least 8 characters with mixed case and numbers">
            <small>Leave blank to keep your current password</small>
        </div>

        <div class="form-group">
            <label for="password_confirm">Confirm New Password</label>
            <input type="password" id="password_confirm" name="password_confirm" placeholder="Re-enter your new password">
            <small>Must match your new password above</small>
        </div>

        <div class="password-help" id="passwordHelp">
            <h4>Password Requirements:</h4>
            <ul>
                <li>✓ Minimum 8 characters in length</li>
                <li>✓ At least one uppercase letter (A-Z)</li>
                <li>✓ At least one lowercase letter (a-z)</li>
                <li>✓ At least one number (0-9)</li>
            </ul>
        </div>

        <div class="security-tip">
            <strong>💡 Security Tip:</strong> Use a unique password that you don't use on other websites. Consider using a mix of letters, numbers, and symbols for maximum security.
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-primary">🔐 Update Password</button>
            <a href="<?php echo APP_URL; ?>/profile" class="btn btn-secondary">✕ Cancel</a>
        </div>
    </form>
</div>

<script>
    // Show password requirements when password field is focused
    const passwordInput = document.getElementById('password');
    const passwordHelp = document.getElementById('passwordHelp');
    
    if (passwordInput && passwordHelp) {
        passwordInput.addEventListener('focus', function() {
            passwordHelp.style.display = 'block';
        });
        
        passwordInput.addEventListener('blur', function() {
            if (!this.value) {
                passwordHelp.style.display = 'none';
            }
        });
    }

    // Auto-hide success/error messages after 6 seconds
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(alert => {
        setTimeout(() => {
            const style = alert.style;
            style.transition = 'opacity 0.3s ease-out';
            style.opacity = '0';
            setTimeout(() => {
                alert.style.display = 'none';
            }, 300);
        }, 6000);
    });

    // Handle form submission loading state
    const submitButton = document.querySelector('.btn-primary');
    if (submitButton) {
        submitButton.addEventListener('click', function() {
            if (this.type === 'submit') {
                const originalText = this.textContent;
                this.textContent = '⏳ Processing...';
                this.disabled = true;
                
                setTimeout(() => {
                    this.disabled = false;
                    this.textContent = originalText;
                }, 10000);
            }
        });
    }
</script>

<?php
$content = ob_get_clean();
include ROOT_PATH . '/app/views/layouts/profile-sidebar.php';
?>

<?php echo $content; ?>

<?php
include ROOT_PATH . '/app/views/layouts/profile-sidebar-end.php';
$page_title = 'Security & Password | InfoHub';
include ROOT_PATH . '/app/views/layouts/main.php';
?>
