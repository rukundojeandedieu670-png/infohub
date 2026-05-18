<?php
// Profile Edit - Account Settings Page
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

<!-- Account Information -->
<div class="info-card">
    <h2 class="form-section-title">📋 Account Information</h2>
    <p class="form-section-subtitle">Your account details and status</p>

    <div class="info-item">
        <span class="info-label">Account Email</span>
        <span class="info-value"><?php echo htmlspecialchars($userProfile['email'] ?? ''); ?></span>
    </div>

    <div class="info-item">
        <span class="info-label">Account Status</span>
        <span class="info-value" style="color: #059669; font-weight: 600;">✓ Active</span>
    </div>

    <div class="info-item">
        <span class="info-label">Account Created</span>
        <span class="info-value">
            <?php 
                if (!empty($userProfile['created_at'])) {
                    echo htmlspecialchars(date('F j, Y', strtotime($userProfile['created_at'])));
                } else {
                    echo 'N/A';
                }
            ?>
        </span>
    </div>

    <div class="info-item">
        <span class="info-label">Last Updated</span>
        <span class="info-value">
            <?php 
                if (!empty($userProfile['updated_at'])) {
                    echo htmlspecialchars(date('F j, Y \a\t g:i A', strtotime($userProfile['updated_at'])));
                } else {
                    echo 'Never';
                }
            ?>
        </span>
    </div>
</div>

<!-- Account Preferences -->
<div class="info-card">
    <h2 class="form-section-title">⚙️ Preferences</h2>
    <p class="form-section-subtitle">Customize your experience</p>

    <div class="info-item">
        <span class="info-label">Email Notifications</span>
        <span class="info-value">Enabled</span>
    </div>

    <div class="info-item">
        <span class="info-label">Job Alerts</span>
        <span class="info-value">
            <?php echo (!empty($userProfile['is_job_seeker'])) ? '✓ Enabled' : 'Disabled'; ?>
        </span>
    </div>

    <div class="info-item">
        <span class="info-label">Privacy</span>
        <span class="info-value">Public Profile</span>
    </div>
</div>

<!-- Danger Zone -->
<div class="danger-zone">
    <h3>⚠️ Danger Zone</h3>
    <p>Actions in this section cannot be undone. Deleting your account will permanently remove all your data and cannot be recovered. Please proceed with caution.</p>
    
    <a href="<?php echo APP_URL; ?>/profile/delete" class="btn btn-danger" onclick="return confirm('⚠️ Are you absolutely sure? This action cannot be undone. Your account and ALL associated data will be permanently deleted from our system. Please type DELETE to confirm.' + String.fromCharCode(10) + String.fromCharCode(10) + 'This includes: your profile information, work history, applications, messages, and all other data.');">
        🗑️ Delete My Account Permanently
    </a>
</div>

<script>
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
</script>

<?php
$content = ob_get_clean();
include ROOT_PATH . '/app/views/layouts/profile-sidebar.php';
?>

<?php echo $content; ?>

<?php
include ROOT_PATH . '/app/views/layouts/profile-sidebar-end.php';
$page_title = 'Account Settings | InfoHub';
include ROOT_PATH . '/app/views/layouts/main.php';
?>
