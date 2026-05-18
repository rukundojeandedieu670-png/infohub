<?php
// Profile view - Display user profile information
$title = $userProfile['first_name'] . ' ' . $userProfile['last_name'] . "'s Profile";
$description = "View " . $userProfile['first_name'] . " " . $userProfile['last_name'] . "'s profile information";

// Start output buffering to capture content before layout renders
ob_start();
?>

<div class="profile-page container">
    <div class="profile-header">
        <h1><?php echo htmlspecialchars($userProfile['first_name'] . ' ' . $userProfile['last_name']); ?>'s Profile</h1>
        <div class="profile-breadcrumb">
            <a href="<?php echo APP_URL; ?>">Home</a>
            <span>›</span>
            <a href="<?php echo APP_URL; ?>/profile">Profile</a>
            <span>›</span>
            <span class="breadcrumb-current">Overview</span>
        </div>
        <div class="profile-subnav">
            <a href="<?php echo APP_URL; ?>/profile" class="active">Overview</a>
            <a href="<?php echo APP_URL; ?>/profile/edit/personal">Personal</a>
            <a href="<?php echo APP_URL; ?>/profile/edit/professional">Professional</a>
            <a href="<?php echo APP_URL; ?>/profile/edit/security">Security</a>
        </div>
    </div>

    <div class="grid-2 profile-grid">
        <!-- Profile Card -->
        <div class="card profile-card">
            <div class="profile-card-top">
                <div class="profile-avatar">
                    <?php if (!empty($userProfile['avatar'])): ?>
                        <img src="<?php echo htmlspecialchars($userProfile['avatar']); ?>" alt="<?php echo htmlspecialchars($userProfile['first_name'] . ' ' . $userProfile['last_name']); ?>" style="width: 100%; height: 100%; object-fit: cover;">
                    <?php else: ?>
                        <div style="font-size: 50px; color: #9ca3af;">👤</div>
                    <?php endif; ?>
                </div>
                <h2>
                    <?php echo htmlspecialchars($userProfile['first_name'] . ' ' . $userProfile['last_name']); ?>
                </h2>
                <p>
                    <?php echo htmlspecialchars($userProfile['email']); ?>
                </p>
            </div>

            <hr class="divider">

            <div class="profile-meta">
                <div class="profile-field">
                    <label class="profile-label">Full Name</label>
                    <p class="profile-value">
                        <?php echo htmlspecialchars($userProfile['first_name'] . ' ' . $userProfile['last_name']); ?>
                    </p>
                </div>

                <div class="profile-field">
                    <label class="profile-label">Email Address</label>
                    <p class="profile-value">
                        <?php echo htmlspecialchars($userProfile['email']); ?>
                    </p>
                </div>

                <div class="profile-field">
                    <label class="profile-label">Phone Number</label>
                    <p class="profile-value">
                        <?php echo !empty($userProfile['phone']) ? htmlspecialchars($userProfile['phone']) : '<em style="color: #9ca3af;">Not provided</em>'; ?>
                    </p>
                </div>

                <div class="profile-field">
                    <label class="profile-label">Account Status</label>
                    <p class="profile-value">
                        <span class="profile-status-pill" style="background: <?php echo $userProfile['is_active'] ? '#d1fae5' : '#fee2e2'; ?>; color: <?php echo $userProfile['is_active'] ? '#065f46' : '#991b1b'; ?>;">
                            <?php echo $userProfile['is_active'] ? 'Active' : 'Inactive'; ?>
                        </span>
                    </p>
                </div>

                <div class="profile-field">
                    <label class="profile-label">Member Since</label>
                    <p class="profile-value">
                        <?php echo date('F j, Y', strtotime($userProfile['created_at'])); ?>
                    </p>
                </div>
            </div>

            <hr class="divider">

            <div class="profile-actions">
                <a href="<?php echo APP_URL; ?>/profile/edit/personal" class="btn btn-primary" style="flex: 1; text-align: center;">
                    ✏️ Manage Profile
                </a>
                <a href="<?php echo APP_URL; ?>" class="btn btn-secondary" style="flex: 1; text-align: center;">
                    ← Back Home
                </a>
            </div>
        </div>

        <!-- Biography & Additional Info -->
        <div>
            <div class="card" style="padding: 30px; margin-bottom: 20px;">
                <h3 style="margin: 0 0 15px 0; color: #1f2937;">About You</h3>
                <div style="color: #4b5563; line-height: 1.6;">
                    <?php if (!empty($userProfile['bio'])): ?>
                        <p><?php echo nl2br(htmlspecialchars($userProfile['bio'])); ?></p>
                    <?php else: ?>
                        <p style="color: #9ca3af; font-style: italic;">No bio added yet. <a href="<?php echo APP_URL; ?>/profile/edit/personal" style="color: #16a34a; text-decoration: none; font-weight: 600;">Add one now</a></p>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Quick Stats -->
            <div class="card profile-panel profile-panel--compact">
                <h3>Quick Info</h3>
                <div class="profile-panel-grid">
                    <div class="profile-info-row">
                        <span style="color: #6b7280;">Profile Completeness</span>
                        <span style="font-weight: 600; color: #16a34a;">75%</span>
                    </div>
                    <div class="profile-info-row">
                        <span style="color: #6b7280;">Last Updated</span>
                        <span style="font-weight: 600; color: #1f2937;">
                            <?php 
                            $updated = strtotime($userProfile['updated_at']);
                            $now = time();
                            $days = floor(($now - $updated) / 86400);
                            if ($days == 0) echo 'Today';
                            elseif ($days == 1) echo '1 day ago';
                            else echo $days . ' days ago';
                            ?>
                        </span>
                    </div>
                </div>
            </div>

            <!-- Professional Information (if available) -->
            <?php if (!empty($userProfile['job_title']) || !empty($userProfile['company']) || !empty($userProfile['bio_professional']) || !empty($userProfile['is_job_seeker']) || !empty($userProfile['is_business_owner'])): ?>
            <div class="card profile-panel profile-panel--professional">
                <h3>💼 Professional Information</h3>
                
                <div class="profile-panel-grid">
                    <?php if (!empty($userProfile['job_title']) || !empty($userProfile['company'])): ?>
                    <div class="profile-info-row">
                        <?php if (!empty($userProfile['job_title'])): ?>
                        <div>
                            <p class="profile-label">Position</p>
                            <p class="profile-value" style="font-weight: 600;">
                                <?php echo htmlspecialchars($userProfile['job_title']); ?>
                            </p>
                        </div>
                        <?php endif; ?>
                        <?php if (!empty($userProfile['company'])): ?>
                        <div>
                            <p class="profile-label">Company</p>
                            <p class="profile-value" style="font-weight: 600;">
                                <?php echo htmlspecialchars($userProfile['company']); ?>
                            </p>
                        </div>
                        <?php endif; ?>
                    </div>
                    <?php endif; ?>

                    <?php if (!empty($userProfile['industry']) || !empty($userProfile['experience_years'])): ?>
                    <div class="profile-info-row">
                        <?php if (!empty($userProfile['industry'])): ?>
                        <div>
                            <p class="profile-label">Industry</p>
                            <p class="profile-value" style="font-weight: 600;">
                                <?php echo htmlspecialchars($userProfile['industry']); ?>
                            </p>
                        </div>
                        <?php endif; ?>
                        <?php if (!empty($userProfile['experience_years'])): ?>
                        <div>
                            <p class="profile-label">Experience</p>
                            <p class="profile-value" style="font-weight: 600;">
                                <?php echo htmlspecialchars($userProfile['experience_years']); ?> years
                            </p>
                        </div>
                        <?php endif; ?>
                    </div>
                    <?php endif; ?>

                    <?php if (!empty($userProfile['bio_professional'])): ?>
                    <div class="profile-info-row">
                        <div style="width: 100%;">
                            <p class="profile-label">Professional Bio</p>
                            <p class="profile-value" style="line-height: 1.6;">
                                <?php echo nl2br(htmlspecialchars($userProfile['bio_professional'])); ?>
                            </p>
                        </div>
                    </div>
                    <?php endif; ?>

                    <?php 
                    $skills = $userProfile['skills'] ?? [];
                    if (is_string($skills)) {
                        $skills = json_decode($skills, true) ?? [];
                    }
                    if (!empty($skills) && is_array($skills)): 
                    ?>
                    <div class="profile-info-row">
                        <div style="width: 100%;">
                            <p class="profile-label">Skills</p>
                            <div class="profile-tags">
                                <?php foreach ($skills as $skill): ?>
                                    <span class="profile-tag">
                                        <?php echo htmlspecialchars(trim($skill)); ?>
                                    </span>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>

                    <?php if (!empty($userProfile['linkedin_url']) || !empty($userProfile['portfolio_url'])): ?>
                    <div class="profile-info-row">
                        <div style="width: 100%;">
                            <p class="profile-label">Online Profiles</p>
                            <div style="display: flex; flex-direction: column; gap: 8px;">
                                <?php if (!empty($userProfile['linkedin_url'])): ?>
                                <a href="<?php echo htmlspecialchars($userProfile['linkedin_url']); ?>" target="_blank" rel="noopener noreferrer" style="display: inline-flex; align-items: center; gap: 8px; color: #0a66c2; text-decoration: none; font-weight: 600;">
                                    <span>🔗</span> LinkedIn Profile
                                </a>
                                <?php endif; ?>
                                <?php if (!empty($userProfile['portfolio_url'])): ?>
                                <a href="<?php echo htmlspecialchars($userProfile['portfolio_url']); ?>" target="_blank" rel="noopener noreferrer" style="display: inline-flex; align-items: center; gap: 8px; color: #0066cc; text-decoration: none; font-weight: 600;">
                                    <span>🌐</span> Portfolio Website
                                </a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>

                    <!-- Role Indicators -->
                    <div class="profile-tags">
                        <?php if (!empty($userProfile['is_job_seeker'])): ?>
                            <span class="profile-tag">
                                🔍 Job Seeker
                            </span>
                        <?php endif; ?>
                        <?php if (!empty($userProfile['is_business_owner'])): ?>
                            <span class="profile-tag" style="background: #fef3c7; color: #92400e;">
                                🏢 Business Owner
                            </span>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Session Management -->
    <div class="card profile-panel profile-panel--security">
        <h3>Security</h3>
        <p class="profile-value" style="margin-bottom: 15px; font-size: 0.95rem;">
            Manage your account security and active sessions.
        </p>
        <div class="profile-actions">
            <a href="<?php echo APP_URL; ?>/profile/edit/security" class="btn btn-secondary" style="font-size: 0.9rem;">
                🔒 Change Password
            </a>
            <a href="<?php echo APP_URL; ?>/auth/logout" class="btn" style="background: #ef4444; color: white; padding: 10px 20px; border-radius: 12px; text-decoration: none; font-weight: 600; display: inline-block;">
                🚪 Logout
            </a>
        </div>
    </div>
</div>

<style>
    @media (max-width: 768px) {
        .grid-2 {
            grid-template-columns: 1fr !important;
        }
        
        .profile-header h1 {
            font-size: 1.75rem !important;
        }
    }
</style>

<?php
// Capture the profile content
$content = ob_get_clean();

// Now include the layout which will render with $content variable
include ROOT_PATH . '/app/views/layouts/main.php';
?>
