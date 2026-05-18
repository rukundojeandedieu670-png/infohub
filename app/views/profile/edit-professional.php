<?php
// Profile Edit - Professional Information Page
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

<!-- Professional Information Form -->
<div class="card">
    <form method="POST" action="<?php echo APP_URL; ?>/profile/update" id="professionalForm">
        <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token'] ?? ''; ?>">
        <input type="hidden" name="form_type" value="professional">

        <h2 class="form-section-title">💼 Career & Expertise</h2>
        <p class="form-section-subtitle">Share your professional background</p>

        <div class="form-group">
            <label for="job_title">Job Title / Position</label>
            <input type="text" id="job_title" name="job_title" value="<?php echo htmlspecialchars($userProfile['job_title'] ?? ''); ?>" placeholder="e.g., Senior Software Engineer">
            <small>Your current or most recent job title</small>
        </div>

        <div class="form-group">
            <label for="company">Company / Organization</label>
            <input type="text" id="company" name="company" value="<?php echo htmlspecialchars($userProfile['company'] ?? ''); ?>" placeholder="e.g., TechHub Solutions">
            <small>Your current or most recent employer</small>
        </div>

        <div class="form-group">
            <label for="industry">Industry</label>
            <select id="industry" name="industry">
                <option value="">Select your industry</option>
                <option value="Technology" <?php echo ($userProfile['industry'] ?? '') === 'Technology' ? 'selected' : ''; ?>>Technology</option>
                <option value="Finance" <?php echo ($userProfile['industry'] ?? '') === 'Finance' ? 'selected' : ''; ?>>Finance & Banking</option>
                <option value="Healthcare" <?php echo ($userProfile['industry'] ?? '') === 'Healthcare' ? 'selected' : ''; ?>>Healthcare & Medical</option>
                <option value="Education" <?php echo ($userProfile['industry'] ?? '') === 'Education' ? 'selected' : ''; ?>>Education & Training</option>
                <option value="Retail" <?php echo ($userProfile['industry'] ?? '') === 'Retail' ? 'selected' : ''; ?>>Retail & E-commerce</option>
                <option value="Manufacturing" <?php echo ($userProfile['industry'] ?? '') === 'Manufacturing' ? 'selected' : ''; ?>>Manufacturing</option>
                <option value="Agriculture" <?php echo ($userProfile['industry'] ?? '') === 'Agriculture' ? 'selected' : ''; ?>>Agriculture & Food</option>
                <option value="Hospitality" <?php echo ($userProfile['industry'] ?? '') === 'Hospitality' ? 'selected' : ''; ?>>Hospitality & Tourism</option>
                <option value="Other" <?php echo ($userProfile['industry'] ?? '') === 'Other' ? 'selected' : ''; ?>>Other</option>
            </select>
            <small>Your professional industry</small>
        </div>

        <div class="form-group">
            <label for="experience_years">Years of Experience</label>
            <input type="number" id="experience_years" name="experience_years" min="0" max="70" value="<?php echo htmlspecialchars($userProfile['experience_years'] ?? ''); ?>" placeholder="5">
            <small>Total years of professional experience</small>
        </div>

        <div class="form-group">
            <label for="skills_text">Key Skills</label>
            <textarea id="skills_text" name="skills_text" rows="3" placeholder="e.g., PHP, JavaScript, React, Project Management, Leadership
(Separate multiple skills with commas)"><?php 
                $skills = $userProfile['skills'] ?? [];
                if (is_string($skills)) {
                    $skills = json_decode($skills, true) ?? [];
                }
                if (is_array($skills)) {
                    echo htmlspecialchars(implode(', ', $skills));
                }
            ?></textarea>
            <small>Separate multiple skills with commas - These help employers find you</small>
        </div>

        <div class="form-group">
            <label for="bio_professional">Professional Bio</label>
            <textarea id="bio_professional" name="bio_professional" rows="4" placeholder="Share your professional experience, achievements, and career goals.
Be specific about your expertise, projects, and impact..."><?php echo htmlspecialchars($userProfile['bio_professional'] ?? ''); ?></textarea>
            <small>This bio will be visible on your professional profile and to recruiters</small>
        </div>

        <div class="form-group">
            <label for="linkedin_url">LinkedIn Profile URL</label>
            <input type="url" id="linkedin_url" name="linkedin_url" value="<?php echo htmlspecialchars($userProfile['linkedin_url'] ?? ''); ?>" placeholder="https://linkedin.com/in/yourprofile">
            <small>Link to your LinkedIn profile (optional)</small>
        </div>

        <div class="form-group">
            <label for="portfolio_url">Portfolio / Website</label>
            <input type="url" id="portfolio_url" name="portfolio_url" value="<?php echo htmlspecialchars($userProfile['portfolio_url'] ?? ''); ?>" placeholder="https://yourportfolio.com">
            <small>Link to your personal website or portfolio (optional)</small>
        </div>

        <div style="margin-top: 24px; padding-top: 24px; border-top: 1px solid #e5e7eb;">
            <div class="checkbox-group">
                <input type="checkbox" id="is_job_seeker" name="is_job_seeker" value="1" <?php echo (!empty($userProfile['is_job_seeker'])) ? 'checked' : ''; ?>>
                <label for="is_job_seeker">I'm actively looking for job opportunities</label>
            </div>

            <div class="checkbox-group">
                <input type="checkbox" id="is_business_owner" name="is_business_owner" value="1" <?php echo (!empty($userProfile['is_business_owner'])) ? 'checked' : ''; ?>>
                <label for="is_business_owner">I own or operate a business</label>
            </div>
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
$page_title = 'Professional Information | InfoHub';
include ROOT_PATH . '/app/views/layouts/main.php';
?>
