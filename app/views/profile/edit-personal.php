<?php
// Profile Edit - Personal Information Page
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

<!-- Personal Information Form -->
<div class="card">
    <form method="POST" action="<?php echo APP_URL; ?>/profile/update" id="personalForm">
        <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token'] ?? ''; ?>">
        <input type="hidden" name="form_type" value="personal">

        <h2 class="form-section-title">👤 Personal Details</h2>
        <p class="form-section-subtitle">Basic information about you</p>

        <div class="form-group">
            <label for="first_name">First Name *</label>
            <input type="text" id="first_name" name="first_name" value="<?php echo htmlspecialchars($userProfile['first_name'] ?? ''); ?>" required placeholder="John">
            <small>Your first name</small>
        </div>

        <div class="form-group">
            <label for="last_name">Last Name *</label>
            <input type="text" id="last_name" name="last_name" value="<?php echo htmlspecialchars($userProfile['last_name'] ?? ''); ?>" required placeholder="Doe">
            <small>Your last name</small>
        </div>

        <div class="form-group">
            <label for="email">Email Address</label>
            <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($userProfile['email'] ?? ''); ?>" disabled>
            <small>Your email is verified and cannot be changed. <a href="#">Contact support</a> if needed.</small>
        </div>

        <div class="form-group">
            <label for="phone">Phone Number</label>
            <input type="tel" id="phone" name="phone" value="<?php echo htmlspecialchars($userProfile['phone'] ?? ''); ?>" placeholder="+250 788 123 456">
            <small>Optional - Used for important notifications and job opportunities</small>
        </div>

        <div class="form-group">
            <label for="bio">About You</label>
            <textarea id="bio" name="bio" rows="4" placeholder="Tell us about yourself, your interests, hobbies, or professional background...
Keep it professional and concise (max 500 characters)."><?php echo htmlspecialchars($userProfile['bio'] ?? ''); ?></textarea>
            <small>Maximum 500 characters - This will be visible on your public profile</small>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-primary">💾 Save Changes</button>
            <a href="<?php echo APP_URL; ?>/profile" class="btn btn-secondary">✕ Cancel</a>
        </div>
    </form>
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
$page_title = 'Personal Information | InfoHub';
include ROOT_PATH . '/app/views/layouts/main.php';
?>
