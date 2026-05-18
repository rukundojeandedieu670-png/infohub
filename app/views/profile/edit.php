<?php
// Profile edit view - Professional senior-level design
// Independent form sections with responsive design
ob_start();
?>

<style>
    * {
        box-sizing: border-box;
    }

    :root {
        --primary-color: #059669;
        --primary-dark: #047857;
        --primary-light: #ecfdf5;
        --text-primary: #111827;
        --text-secondary: #6b7280;
        --text-tertiary: #9ca3af;
        --border-color: #e5e7eb;
        --border-light: #f3f4f6;
        --bg-success: #d1fae5;
        --bg-error: #fee2e2;
        --shadow-sm: 0 1px 2px 0 rgb(0 0 0 / 0.05);
        --shadow-md: 0 4px 6px -1px rgb(0 0 0 / 0.1);
        --shadow-lg: 0 10px 15px -3px rgb(0 0 0 / 0.1);
        --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    body {
        margin: 0;
        padding: 0;
        font-family: inherit;
        background-color: #f9fafb;
    }

    .profile-edit-container {
        max-width: 820px;
        margin: 32px auto;
        padding: 0 20px;
    }

    .profile-header {
        margin-bottom: 40px;
        padding-bottom: 24px;
        border-bottom: 1px solid var(--border-color);
    }

    .profile-header h1 {
        color: var(--text-primary);
        margin: 0 0 8px 0;
        font-size: 2.25rem;
        font-weight: 700;
        letter-spacing: -0.02em;
    }

    .profile-header p {
        color: var(--text-secondary);
        margin: 0;
        font-size: 1.0625rem;
        font-weight: 400;
        line-height: 1.5;
    }

    /* Alerts */
    .alert {
        margin-bottom: 24px;
        padding: 16px 20px;
        border-radius: 10px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        animation: slideDown 0.3s ease-out;
        border-left: 4px solid;
        font-weight: 500;
        font-size: 1rem;
    }

    @keyframes slideDown {
        from {
            opacity: 0;
            transform: translateY(-15px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .alert-error {
        background: #fef2f2;
        color: #991b1b;
        border-left-color: #dc2626;
    }

    .alert-success {
        background: #ecfdf5;
        color: #065f46;
        border-left-color: #059669;
    }

    .alert-close {
        background: none;
        border: none;
        color: inherit;
        cursor: pointer;
        font-size: 1.3rem;
        padding: 0;
        width: 32px;
        height: 32px;
        display: flex;
        align-items: center;
        justify-content: center;
        opacity: 0.6;
        transition: var(--transition);
    }

    .alert-close:hover {
        opacity: 1;
    }

    /* Cards */
    .card {
        background: white;
        border: 1px solid var(--border-color);
        border-radius: 12px;
        padding: 32px;
        margin-bottom: 28px;
        box-shadow: var(--shadow-sm);
        transition: var(--transition);
    }

    .card:hover {
        box-shadow: var(--shadow-md);
    }

    .form-section-title {
        font-size: 1.375rem;
        font-weight: 700;
        color: var(--text-primary);
        margin: 0 0 8px 0;
        padding-bottom: 12px;
        letter-spacing: -0.01em;
    }

    .form-section-subtitle {
        color: var(--text-secondary);
        font-size: 1rem;
        margin: 0 0 24px 0;
        font-weight: 400;
        line-height: 1.5;
    }

    /* Form Groups */
    .form-group {
        margin-bottom: 24px;
    }

    .form-group label {
        display: block;
        margin-bottom: 8px;
        color: var(--text-primary);
        font-weight: 600;
        font-size: 0.9375rem;
        letter-spacing: -0.005em;
    }

    .form-group input[type="text"],
    .form-group input[type="email"],
    .form-group input[type="tel"],
    .form-group input[type="url"],
    .form-group input[type="password"],
    .form-group input[type="number"],
    .form-group select,
    .form-group textarea {
        width: 100%;
        padding: 11px 13px;
        border: 1.5px solid var(--border-color);
        border-radius: 8px;
        font-size: 1rem;
        font-family: inherit;
        resize: vertical;
        transition: var(--transition);
        background: white;
        color: var(--text-primary);
    }

    .form-group input[type="text"]:focus,
    .form-group input[type="email"]:focus,
    .form-group input[type="tel"]:focus,
    .form-group input[type="url"]:focus,
    .form-group input[type="password"]:focus,
    .form-group input[type="number"]:focus,
    .form-group select:focus,
    .form-group textarea:focus {
        outline: none;
        border-color: var(--primary-color);
        box-shadow: 0 0 0 3px rgba(5, 150, 105, 0.1);
        background: white;
    }

    .form-group input:disabled {
        background: var(--border-light);
        color: var(--text-tertiary);
        cursor: not-allowed;
    }

    .form-group small {
        color: var(--text-secondary);
        display: block;
        margin-top: 6px;
        font-size: 0.875rem;
        line-height: 1.5;
    }

    .form-group small a {
        color: var(--primary-color);
        text-decoration: none;
        font-weight: 600;
        transition: var(--transition);
    }

    .form-group small a:hover {
        color: var(--primary-dark);
        text-decoration: underline;
    }

    /* Checkboxes */
    .checkbox-group {
        display: flex;
        align-items: center;
        gap: 12px;
        margin-bottom: 16px;
        padding: 12px 14px;
        background: var(--border-light);
        border-radius: 8px;
        transition: var(--transition);
    }

    .checkbox-group:hover {
        background: #e5e7eb;
    }

    .checkbox-group input[type="checkbox"] {
        width: 20px;
        height: 20px;
        cursor: pointer;
        accent-color: var(--primary-color);
        flex-shrink: 0;
    }

    .checkbox-group label {
        margin: 0;
        cursor: pointer;
        color: var(--text-primary);
        font-weight: 500;
        font-size: 1rem;
    }

    /* Form Actions */
    .form-actions {
        display: flex;
        gap: 12px;
        margin-top: 32px;
        padding-top: 24px;
        border-top: 1px solid var(--border-color);
    }

    .btn {
        flex: 1;
        padding: 12px 20px;
        border: none;
        border-radius: 8px;
        font-size: 1rem;
        font-weight: 600;
        cursor: pointer;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        transition: var(--transition);
        letter-spacing: -0.005em;
    }

    .btn-primary {
        background: var(--primary-color);
        color: white;
        box-shadow: var(--shadow-sm);
    }

    .btn-primary:hover {
        background: var(--primary-dark);
        box-shadow: var(--shadow-md);
        transform: translateY(-2px);
    }

    .btn-primary:active {
        transform: translateY(0);
    }

    .btn-secondary {
        background: var(--border-light);
        color: var(--text-primary);
        border: 1px solid var(--border-color);
    }

    .btn-secondary:hover {
        background: #e5e7eb;
        border-color: #d1d5db;
    }

    .btn-danger {
        background: #dc2626;
        color: white;
        box-shadow: var(--shadow-sm);
    }

    .btn-danger:hover {
        background: #b91c1c;
        box-shadow: var(--shadow-md);
        transform: translateY(-2px);
    }

    .btn-danger:active {
        transform: translateY(0);
    }

    /* Danger Zone */
    .danger-zone {
        background: #fef2f2;
        border: 1.5px solid #fee2e2;
        border-radius: 12px;
        padding: 28px;
        margin-top: 40px;
    }

    .danger-zone h3 {
        color: #991b1b;
        margin: 0 0 8px 0;
        font-size: 1.125rem;
        font-weight: 700;
    }

    .danger-zone p {
        color: #7c2d12;
        margin: 0 0 20px 0;
        font-size: 0.9375rem;
        line-height: 1.5;
    }

    /* Password Help */
    .password-help {
        background: #eff6ff;
        border: 1px solid #bfdbfe;
        border-radius: 8px;
        padding: 14px 16px;
        margin-top: 12px;
        display: none;
        animation: slideDown 0.2s ease-out;
    }

    .password-help h4 {
        color: #1e40af;
        margin: 0 0 8px 0;
        font-size: 0.875rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }

    .password-help ul {
        margin: 0;
        padding-left: 20px;
        color: #1e40af;
        font-size: 0.9rem;
        line-height: 1.6;
    }

    .password-help li {
        margin-bottom: 4px;
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        .profile-edit-container {
            padding: 0 16px;
            margin: 24px auto;
        }

        .profile-header {
            margin-bottom: 32px;
        }

        .profile-header h1 {
            font-size: 1.875rem;
        }

        .card {
            padding: 24px;
            margin-bottom: 20px;
        }

        .form-section-title {
            font-size: 1.25rem;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-actions {
            flex-direction: column;
            gap: 12px;
        }

        .btn {
            width: 100%;
        }

        .form-group input[type="text"],
        .form-group input[type="email"],
        .form-group input[type="tel"],
        .form-group input[type="url"],
        .form-group input[type="password"],
        .form-group input[type="number"],
        .form-group select,
        .form-group textarea {
            padding: 13px 14px;
            font-size: 16px;
        }

        .checkbox-group {
            flex-direction: column;
            align-items: flex-start;
            padding: 10px 12px;
        }
    }

    @media (max-width: 480px) {
        .profile-edit-container {
            padding: 0 12px;
            margin: 16px auto;
        }

        .profile-header {
            margin-bottom: 24px;
            padding-bottom: 16px;
        }

        .profile-header h1 {
            font-size: 1.5rem;
            margin-bottom: 4px;
        }

        .profile-header p {
            font-size: 0.95rem;
        }

        .card {
            padding: 20px;
            margin-bottom: 16px;
        }

        .form-section-title {
            font-size: 1.125rem;
            margin-bottom: 4px;
        }

        .form-section-subtitle {
            font-size: 0.9375rem;
            margin-bottom: 16px;
        }

        .form-group {
            margin-bottom: 18px;
        }

        .form-group label {
            font-size: 0.9rem;
        }

        .form-group small {
            font-size: 0.8125rem;
        }

        .btn {
            padding: 11px 16px;
            font-size: 0.9375rem;
        }

        .alert {
            padding: 12px 16px;
            font-size: 0.95rem;
            margin-bottom: 16px;
        }

        .danger-zone {
            padding: 20px;
        }

        .danger-zone h3 {
            font-size: 1rem;
        }

        .danger-zone p {
            font-size: 0.875rem;
        }
    }
</style>

<div class="profile-edit-container">
    <!-- Page Header -->
    <div class="profile-header">
        <h1>Profile Settings</h1>
        <p>Manage your personal, professional, and security preferences</p>
    </div>

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

    <!-- Personal Information Section -->
    <div class="card">
        <form method="POST" action="<?php echo APP_URL; ?>/profile/update" id="personalForm">
            <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token'] ?? ''; ?>">
            <input type="hidden" name="form_type" value="personal">

            <h2 class="form-section-title">👤 Personal Information</h2>
            <p class="form-section-subtitle">Basic details about you</p>

            <div class="form-group">
                <label for="first_name">First Name *</label>
                <input type="text" id="first_name" name="first_name" value="<?php echo htmlspecialchars($userProfile['first_name'] ?? ''); ?>" required placeholder="John">
            </div>

            <div class="form-group">
                <label for="last_name">Last Name *</label>
                <input type="text" id="last_name" name="last_name" value="<?php echo htmlspecialchars($userProfile['last_name'] ?? ''); ?>" required placeholder="Doe">
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
                <button type="submit" class="btn btn-primary">💾 Save Personal Info</button>
                <a href="<?php echo APP_URL; ?>/profile" class="btn btn-secondary">✕ Cancel</a>
            </div>
        </form>
    </div>

    <!-- Professional Information Section -->
    <div class="card">
        <form method="POST" action="<?php echo APP_URL; ?>/profile/update" id="professionalForm">
            <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token'] ?? ''; ?>">
            <input type="hidden" name="form_type" value="professional">

            <h2 class="form-section-title">💼 Professional Information</h2>
            <p class="form-section-subtitle">Help employers and businesses discover your expertise</p>

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

            <div style="margin-top: 24px; padding-top: 24px; border-top: 1px solid var(--border-color);">
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
                <button type="submit" class="btn btn-primary">💾 Save Professional Info</button>
                <a href="<?php echo APP_URL; ?>/profile" class="btn btn-secondary">✕ Cancel</a>
            </div>
        </form>
    </div>

    <!-- Security Section - Password Change -->
    <div class="card">
        <form method="POST" action="<?php echo APP_URL; ?>/profile/update" id="passwordForm">
            <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token'] ?? ''; ?>">
            <input type="hidden" name="form_type" value="password">

            <h2 class="form-section-title">🔒 Security & Password</h2>
            <p class="form-section-subtitle">Change your password to keep your account secure</p>
            <p style="color: var(--text-secondary); font-size: 0.95rem; margin: 0 0 20px 0; line-height: 1.5;">Leave password fields blank if you don't want to change your password.</p>

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
            </div>

            <div class="password-help" id="passwordHelp">
                <h4>Password Requirements:</h4>
                <ul>
                    <li>Minimum 8 characters in length</li>
                    <li>At least one uppercase letter (A-Z)</li>
                    <li>At least one lowercase letter (a-z)</li>
                    <li>At least one number (0-9)</li>
                </ul>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-primary">🔐 Update Password</button>
                <a href="<?php echo APP_URL; ?>/profile" class="btn btn-secondary">✕ Cancel</a>
            </div>
        </form>
    </div>

    <!-- Danger Zone -->
    <div class="danger-zone">
        <h3>⚠️ Danger Zone</h3>
        <p>Actions in this section cannot be undone. Please proceed with caution. Deleting your account will permanently remove all your data from our system.</p>
        <a href="<?php echo APP_URL; ?>/profile/delete" class="btn btn-danger" onclick="return confirm('Are you absolutely sure? This action cannot be undone. Your account and all associated data will be permanently deleted.');">
            🗑️ Delete My Account
        </a>
    </div>
</div>

<script>
    /**
     * Profile Edit Form - JavaScript Enhancements
     * Provides UX improvements for form interactions and validation feedback
     */

    // Password field behavior
    const passwordInput = document.getElementById('password');
    const passwordHelp = document.getElementById('passwordHelp');
    
    if (passwordInput && passwordHelp) {
        // Show password requirements on focus
        passwordInput.addEventListener('focus', function() {
            passwordHelp.style.display = 'block';
        });
        
        // Hide if empty on blur
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
    
    // Form validation feedback
    const forms = document.querySelectorAll('form');
    forms.forEach(form => {
        form.addEventListener('submit', function(e) {
            const inputs = this.querySelectorAll('input[required], textarea[required]');
            let hasErrors = false;
            
            inputs.forEach(input => {
                if (!input.value.trim()) {
                    input.style.borderColor = '#dc2626';
                    hasErrors = true;
                } else {
                    input.style.borderColor = '';
                }
            });
            
            if (hasErrors) {
                e.preventDefault();
            }
        });
    });

    // Handle form submission loading state
    const submitButtons = document.querySelectorAll('.btn-primary');
    submitButtons.forEach(button => {
        button.addEventListener('click', function() {
            if (this.type === 'submit') {
                const originalText = this.textContent;
                this.textContent = '⏳ Processing...';
                this.disabled = true;
                
                // Re-enable button after 10 seconds (in case of network issues)
                setTimeout(() => {
                    this.disabled = false;
                    this.textContent = originalText;
                }, 10000);
            }
        });
    });
</script>

<?php
$content = ob_get_clean();
$page_title = 'Edit Profile | InfoHub';
include ROOT_PATH . '/app/views/layouts/main.php';
?>
