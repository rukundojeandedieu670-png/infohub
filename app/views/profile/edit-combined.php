<?php
// Combined Profile Management Page - All sections in one page with sidebar tabs
ob_start();
?>

<style>
    /* Profile Management Layout */
    .profile-layout {
        display: grid;
        grid-template-columns: 250px 1fr;
        gap: 32px;
        padding: 32px 0;
    }

    .profile-sidebar {
        display: flex;
        flex-direction: column;
    }

    .profile-sidebar h2 {
        font-size: 1.25rem;
        font-weight: 700;
        color: #111827;
        margin: 0 0 20px 0;
    }

    .sidebar-nav {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .sidebar-nav li {
        margin: 0;
    }

    .sidebar-nav a {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 12px 16px;
        color: #6b7280;
        text-decoration: none;
        border-radius: 8px;
        transition: all 0.3s;
        font-weight: 500;
        cursor: pointer;
        user-select: none;
    }

    .sidebar-nav a:hover {
        background: #f3f4f6;
        color: #111827;
    }

    .sidebar-nav a.active {
        background: #ecfdf5;
        color: #059669;
        font-weight: 700;
        border-left: 3px solid #059669;
        padding-left: 13px;
    }

    .sidebar-icon {
        font-size: 1.25rem;
    }

    .profile-content {
        display: flex;
        flex-direction: column;
    }

    .tab-content {
        display: none;
    }

    .tab-content.active {
        display: block;
    }

    .card {
        background: white;
        border: 1px solid #e5e7eb;
        border-radius: 12px;
        padding: 32px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    }

    .info-card {
        background: white;
        border: 1px solid #e5e7eb;
        border-radius: 12px;
        padding: 24px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    }

    .info-item {
        display: grid;
        grid-template-columns: 200px 1fr;
        gap: 16px;
        padding: 16px 0;
        border-bottom: 1px solid #f3f4f6;
        align-items: center;
    }

    .info-item:last-child {
        border-bottom: none;
    }

    .info-label {
        font-weight: 600;
        color: #111827;
    }

    .info-value {
        color: #6b7280;
    }

    .form-section-title {
        font-size: 1.375rem;
        font-weight: 700;
        color: #111827;
        margin: 0 0 8px 0;
        padding-bottom: 12px;
    }

    .form-section-subtitle {
        color: #6b7280;
        font-size: 1rem;
        margin: 0 0 24px 0;
        font-weight: 400;
        line-height: 1.5;
    }

    .form-group {
        margin-bottom: 24px;
    }

    .form-group label {
        display: block;
        margin-bottom: 8px;
        color: #111827;
        font-weight: 600;
        font-size: 0.9375rem;
    }

    .form-group input,
    .form-group textarea,
    .form-group select {
        width: 100%;
        padding: 11px 13px;
        border: 1.5px solid #e5e7eb;
        border-radius: 8px;
        font-size: 1rem;
        font-family: inherit;
        resize: vertical;
        transition: all 0.3s;
        background: white;
        color: #111827;
    }

    .form-group input:focus,
    .form-group textarea:focus,
    .form-group select:focus {
        outline: none;
        border-color: #059669;
        box-shadow: 0 0 0 3px rgba(5, 150, 105, 0.1);
    }

    .form-group input:disabled {
        background: #f3f4f6;
        color: #9ca3af;
        cursor: not-allowed;
    }

    .form-group small {
        color: #6b7280;
        display: block;
        margin-top: 6px;
        font-size: 0.875rem;
    }

    .form-group small a {
        color: #059669;
        text-decoration: none;
        font-weight: 600;
    }

    .form-actions {
        display: flex;
        gap: 12px;
        margin-top: 32px;
        padding-top: 24px;
        border-top: 1px solid #e5e7eb;
    }

    .btn {
        padding: 11px 20px;
        border-radius: 8px;
        border: none;
        font-size: 1rem;
        font-weight: 600;
        cursor: pointer;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: all 0.3s;
    }

    .btn-primary {
        background: #059669;
        color: white;
    }

    .btn-primary:hover {
        background: #047857;
    }

    .btn-secondary {
        background: #e5e7eb;
        color: #6b7280;
    }

    .btn-secondary:hover {
        background: #d1d5db;
    }

    .btn-danger {
        background: #dc2626;
        color: white;
        padding: 12px 24px;
    }

    .btn-danger:hover {
        background: #b91c1c;
    }

    .alert {
        padding: 16px;
        border-radius: 8px;
        margin-bottom: 24px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .alert-error {
        background: #fee2e2;
        border: 1px solid #fecaca;
        color: #991b1b;
    }

    .alert-success {
        background: #dcfce7;
        border: 1px solid #bbf7d0;
        color: #166534;
    }

    .alert-close {
        background: none;
        border: none;
        font-size: 1.5rem;
        cursor: pointer;
        color: inherit;
    }

    .danger-zone {
        background: #fef2f2;
        border: 1px solid #fee2e2;
        padding: 24px;
        border-radius: 12px;
        margin-top: 24px;
    }

    .danger-zone h3 {
        font-size: 1.1rem;
        font-weight: 700;
        color: #991b1b;
        margin: 0 0 12px 0;
    }

    .danger-zone p {
        color: #7f1d1d;
        margin: 0 0 16px 0;
        line-height: 1.5;
    }

    @media (max-width: 768px) {
        .profile-layout {
            grid-template-columns: 1fr;
            gap: 24px;
        }

        .sidebar-nav {
            display: flex;
            gap: 8px;
            overflow-x: auto;
            padding-bottom: 8px;
        }

        .sidebar-nav li {
            flex-shrink: 0;
        }

        .sidebar-nav a {
            padding: 8px 12px;
            font-size: 0.875rem;
        }

        .card {
            padding: 20px;
        }

        .info-item {
            grid-template-columns: 1fr;
            gap: 8px;
        }
    }
</style>

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

<div class="profile-layout">
    <!-- Sidebar Navigation -->
    <aside class="profile-sidebar">
        <h2>Profile Management</h2>
        <ul class="sidebar-nav">
            <li><a onclick="showTab('personal')" class="tab-link active" data-tab="personal"><span class="sidebar-icon">👤</span> Personal Info</a></li>
            <li><a onclick="showTab('professional')" class="tab-link" data-tab="professional"><span class="sidebar-icon">💼</span> Professional</a></li>
            <li><a onclick="showTab('security')" class="tab-link" data-tab="security"><span class="sidebar-icon">🔒</span> Security</a></li>
            <li><a onclick="showTab('account')" class="tab-link" data-tab="account"><span class="sidebar-icon">⚙️</span> Account</a></li>
        </ul>
    </aside>

    <!-- Main Content Area -->
    <div class="profile-content">

        <!-- PERSONAL TAB -->
        <div id="personal-tab" class="tab-content active">
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
                        <textarea id="bio" name="bio" placeholder="Tell us about yourself, your interests, hobbies, or professional background...&#10;Keep it professional and concise (max 500 characters)." maxlength="500"><?php echo htmlspecialchars($userProfile['bio'] ?? ''); ?></textarea>
                        <small>Maximum 500 characters - This will be visible on your public profile</small>
                    </div>

                    <div class="form-actions">
                        <button type="submit" class="btn btn-primary">💾 Save Changes</button>
                        <a href="<?php echo APP_URL; ?>/profile" class="btn btn-secondary">✕ Cancel</a>
                    </div>
                </form>
            </div>
        </div>

        <!-- PROFESSIONAL TAB -->
        <div id="professional-tab" class="tab-content">
            <div class="card">
                <form method="POST" action="<?php echo APP_URL; ?>/profile/update" id="professionalForm">
                    <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token'] ?? ''; ?>">
                    <input type="hidden" name="form_type" value="professional">

                    <h2 class="form-section-title">💼 Career & Expertise</h2>
                    <p class="form-section-subtitle">Help employers and businesses discover your expertise</p>

                    <div class="form-group">
                        <label for="job_title">Job Title</label>
                        <input type="text" id="job_title" name="job_title" value="<?php echo htmlspecialchars($userProfile['job_title'] ?? ''); ?>" placeholder="e.g., Senior Software Engineer">
                        <small>Your current professional position</small>
                    </div>

                    <div class="form-group">
                        <label for="company">Company</label>
                        <input type="text" id="company" name="company" value="<?php echo htmlspecialchars($userProfile['company'] ?? ''); ?>" placeholder="e.g., TechHub Rwanda">
                        <small>Your current employer or organization</small>
                    </div>

                    <div class="form-group">
                        <label for="industry">Industry</label>
                        <select id="industry" name="industry">
                            <option value="">-- Select Industry --</option>
                            <option value="Technology" <?php echo ($userProfile['industry'] ?? '') === 'Technology' ? 'selected' : ''; ?>>Technology</option>
                            <option value="Finance" <?php echo ($userProfile['industry'] ?? '') === 'Finance' ? 'selected' : ''; ?>>Finance</option>
                            <option value="Healthcare" <?php echo ($userProfile['industry'] ?? '') === 'Healthcare' ? 'selected' : ''; ?>>Healthcare</option>
                            <option value="Education" <?php echo ($userProfile['industry'] ?? '') === 'Education' ? 'selected' : ''; ?>>Education</option>
                            <option value="Other" <?php echo ($userProfile['industry'] ?? '') === 'Other' ? 'selected' : ''; ?>>Other</option>
                        </select>
                        <small>Your industry sector</small>
                    </div>

                    <div class="form-group">
                        <label for="experience">Years of Experience</label>
                        <input type="number" id="experience" name="experience" value="<?php echo htmlspecialchars($userProfile['experience'] ?? ''); ?>" min="0" max="70" placeholder="e.g., 5">
                        <small>Total years of professional experience</small>
                    </div>

                    <div class="form-group">
                        <label for="skills">Skills</label>
                        <textarea id="skills" name="skills" placeholder="List your key skills separated by commas&#10;e.g., PHP, JavaScript, Database Design, Leadership" maxlength="500"><?php echo htmlspecialchars($userProfile['skills'] ?? ''); ?></textarea>
                        <small>Comma-separated list of your professional skills (max 500 characters)</small>
                    </div>

                    <div class="form-group">
                        <label for="professional_bio">Professional Bio</label>
                        <textarea id="professional_bio" name="professional_bio" placeholder="Tell us about your professional background, achievements, and goals..." maxlength="1000"><?php echo htmlspecialchars($userProfile['professional_bio'] ?? ''); ?></textarea>
                        <small>Your professional summary (max 1000 characters)</small>
                    </div>

                    <div class="form-group">
                        <label for="linkedin_url">LinkedIn Profile</label>
                        <input type="url" id="linkedin_url" name="linkedin_url" value="<?php echo htmlspecialchars($userProfile['linkedin_url'] ?? ''); ?>" placeholder="https://linkedin.com/in/yourprofile">
                        <small>Link to your LinkedIn profile</small>
                    </div>

                    <div class="form-group">
                        <label for="portfolio_url">Portfolio URL</label>
                        <input type="url" id="portfolio_url" name="portfolio_url" value="<?php echo htmlspecialchars($userProfile['portfolio_url'] ?? ''); ?>" placeholder="https://yourportfolio.com">
                        <small>Link to your portfolio or personal website</small>
                    </div>

                    <hr style="margin: 32px 0; border: none; border-top: 1px solid #e5e7eb;">

                    <h2 class="form-section-title">💼 Employment & Job Seeking</h2>
                    <p class="form-section-subtitle">Tell employers about your current status and availability</p>

                    <div class="form-group">
                        <label for="employment_status">Employment Status *</label>
                        <select id="employment_status" name="employment_status" required>
                            <option value="">-- Select Status --</option>
                            <option value="Employed" <?php echo ($userProfile['employment_status'] ?? '') === 'Employed' ? 'selected' : ''; ?>>Employed</option>
                            <option value="Unemployed" <?php echo ($userProfile['employment_status'] ?? '') === 'Unemployed' ? 'selected' : ''; ?>>Unemployed</option>
                            <option value="Self-employed" <?php echo ($userProfile['employment_status'] ?? '') === 'Self-employed' ? 'selected' : ''; ?>>Self-employed</option>
                            <option value="Freelancer" <?php echo ($userProfile['employment_status'] ?? '') === 'Freelancer' ? 'selected' : ''; ?>>Freelancer</option>
                            <option value="Student" <?php echo ($userProfile['employment_status'] ?? '') === 'Student' ? 'selected' : ''; ?>>Student</option>
                            <option value="Career Break" <?php echo ($userProfile['employment_status'] ?? '') === 'Career Break' ? 'selected' : ''; ?>>Career Break</option>
                        </select>
                        <small>Your current employment situation</small>
                    </div>

                    <div class="form-group">
                        <label for="job_seeking_status">Job Seeking Status</label>
                        <select id="job_seeking_status" name="job_seeking_status">
                            <option value="">-- Select Status --</option>
                            <option value="Actively Looking" <?php echo ($userProfile['job_seeking_status'] ?? '') === 'Actively Looking' ? 'selected' : ''; ?>>Actively Looking</option>
                            <option value="Open to Offers" <?php echo ($userProfile['job_seeking_status'] ?? '') === 'Open to Offers' ? 'selected' : ''; ?>>Open to Offers</option>
                            <option value="Not Looking" <?php echo ($userProfile['job_seeking_status'] ?? '') === 'Not Looking' ? 'selected' : ''; ?>>Not Looking</option>
                        </select>
                        <small>Are you interested in new job opportunities?</small>
                    </div>

                    <div class="form-group">
                        <label for="preferred_job_titles">Preferred Job Titles</label>
                        <textarea id="preferred_job_titles" name="preferred_job_titles" placeholder="e.g., Software Engineer, Data Analyst, Project Manager&#10;One per line or comma-separated" maxlength="500"><?php echo htmlspecialchars($userProfile['preferred_job_titles'] ?? ''); ?></textarea>
                        <small>Job titles you're interested in (helps employers find you)</small>
                    </div>

                    <div class="form-group">
                        <label for="preferred_location">Preferred Work Location</label>
                        <input type="text" id="preferred_location" name="preferred_location" value="<?php echo htmlspecialchars($userProfile['preferred_location'] ?? ''); ?>" placeholder="e.g., Kigali, Remote, Nationwide">
                        <small>Where you'd like to work (city, remote, or flexible)</small>
                    </div>

                    <div class="form-group">
                        <label for="availability">Availability</label>
                        <select id="availability" name="availability">
                            <option value="">-- Select Availability --</option>
                            <option value="Immediately Available" <?php echo ($userProfile['availability'] ?? '') === 'Immediately Available' ? 'selected' : ''; ?>>Immediately Available</option>
                            <option value="2 Weeks Notice" <?php echo ($userProfile['availability'] ?? '') === '2 Weeks Notice' ? 'selected' : ''; ?>>2 Weeks Notice</option>
                            <option value="1 Month Notice" <?php echo ($userProfile['availability'] ?? '') === '1 Month Notice' ? 'selected' : ''; ?>>1 Month Notice</option>
                            <option value="2 Months Notice" <?php echo ($userProfile['availability'] ?? '') === '2 Months Notice' ? 'selected' : ''; ?>>2 Months Notice</option>
                            <option value="3 Months Notice" <?php echo ($userProfile['availability'] ?? '') === '3 Months Notice' ? 'selected' : ''; ?>>3 Months Notice</option>
                        </select>
                        <small>When you could start a new position</small>
                    </div>

                    <div class="form-group">
                        <label for="salary_expectation">Salary Expectation (Monthly - RWF)</label>
                        <input type="number" id="salary_expectation" name="salary_expectation" value="<?php echo htmlspecialchars($userProfile['salary_expectation'] ?? ''); ?>" placeholder="e.g., 1500000" min="0">
                        <small>Your expected monthly salary in Rwandan Francs (RWF)</small>
                    </div>

                    <div class="form-group">
                        <label for="willing_to_relocate">
                            <input type="checkbox" id="willing_to_relocate" name="willing_to_relocate" value="1" <?php echo ($userProfile['willing_to_relocate'] ?? 0) ? 'checked' : ''; ?>>
                            I'm willing to relocate
                        </label>
                        <small>Indicates you're open to moving for work opportunities</small>
                    </div>

                    <div class="form-group">
                        <label for="open_to_opportunities">
                            <input type="checkbox" id="open_to_opportunities" name="open_to_opportunities" value="1" <?php echo ($userProfile['open_to_opportunities'] ?? 0) ? 'checked' : ''; ?>>
                            I'm open to new job opportunities
                        </label>
                        <small>Let employers know you're interested in opportunities</small>
                    </div>

                    <div class="form-actions">
                        <button type="submit" class="btn btn-primary">💾 Save Changes</button>
                        <a href="<?php echo APP_URL; ?>/profile" class="btn btn-secondary">✕ Cancel</a>
                    </div>
                </form>
            </div>
        </div>

        <!-- SECURITY TAB -->
        <div id="security-tab" class="tab-content">
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
                        <label for="new_password">New Password</label>
                        <input type="password" id="new_password" name="new_password" placeholder="At least 8 characters with mixed case and numbers">
                        <small>Leave blank to keep your current password</small>
                    </div>

                    <div class="form-group">
                        <label for="confirm_password">Confirm New Password</label>
                        <input type="password" id="confirm_password" name="confirm_password" placeholder="Re-enter your new password">
                        <small>Must match your new password above</small>
                    </div>

                    <div style="background: #fef3c7; border: 1px solid #fcd34d; padding: 16px; border-radius: 8px; margin: 24px 0;">
                        <strong>💡 Security Tip:</strong>
                        <p style="margin: 8px 0 0 0; font-size: 0.95rem; color: #92400e;">Use a unique password that you don't use on other websites. Consider using a mix of letters, numbers, and symbols for maximum security.</p>
                    </div>

                    <div class="form-actions">
                        <button type="submit" class="btn btn-primary">🔐 Update Password</button>
                        <a href="<?php echo APP_URL; ?>/profile" class="btn btn-secondary">✕ Cancel</a>
                    </div>
                </form>
            </div>
        </div>

        <!-- ACCOUNT TAB -->
        <div id="account-tab" class="tab-content">
            <div class="info-card">
                <h2 class="form-section-title">📋 Account Information</h2>
                <p class="form-section-subtitle">Your account details and status</p>

                <div class="info-item">
                    <span class="info-label">Account Email</span>
                    <span class="info-value"><?php echo htmlspecialchars($userProfile['email'] ?? ''); ?></span>
                </div>

                <div class="info-item">
                    <span class="info-label">Account Status</span>
                    <span class="info-value">✓ Active</span>
                </div>

                <div class="info-item">
                    <span class="info-label">Account Created</span>
                    <span class="info-value"><?php echo date('F d, Y', strtotime($userProfile['created_at'] ?? 'now')); ?></span>
                </div>

                <div class="info-item">
                    <span class="info-label">Last Updated</span>
                    <span class="info-value"><?php echo date('F d, Y \a\t g:i A', strtotime($userProfile['updated_at'] ?? 'now')); ?></span>
                </div>
            </div>

            <div class="info-card" style="margin-top: 24px;">
                <h2 class="form-section-title">⚙️ Preferences</h2>
                <p class="form-section-subtitle">Customize your experience</p>

                <div class="info-item">
                    <span class="info-label">Email Notifications</span>
                    <span class="info-value">Enabled</span>
                </div>

                <div class="info-item">
                    <span class="info-label">Job Alerts</span>
                    <span class="info-value">Disabled</span>
                </div>

                <div class="info-item">
                    <span class="info-label">Privacy</span>
                    <span class="info-value">Public Profile</span>
                </div>
            </div>

            <div class="danger-zone">
                <h3>⚠️ Danger Zone</h3>
                <p>Actions in this section cannot be undone. Deleting your account will permanently remove all your data and cannot be recovered. Please proceed with caution.</p>
                <a href="<?php echo APP_URL; ?>/profile/delete" class="btn btn-danger">🗑️ Delete My Account Permanently</a>
            </div>
        </div>

    </div>
</div>

<script>
    function showTab(tabName) {
        // Hide all tabs
        document.querySelectorAll('.tab-content').forEach(tab => tab.classList.remove('active'));
        
        // Remove active from all links
        document.querySelectorAll('.tab-link').forEach(link => link.classList.remove('active'));
        
        // Show selected tab
        const selectedTab = document.getElementById(tabName + '-tab');
        if (selectedTab) {
            selectedTab.classList.add('active');
        }
        
        // Activate clicked link
        event.target.closest('.tab-link').classList.add('active');
    }
</script>

<?php
$content = ob_get_clean();
include ROOT_PATH . '/app/views/layouts/main.php';
?>
