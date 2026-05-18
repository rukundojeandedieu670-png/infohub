<?php
/**
 * Job Detail View
 */
ob_start();
?>

<div class="detail-container">
    <div class="detail-header">
        <div class="flex-between mb-2">
            <h1><?php echo htmlspecialchars($job['title']); ?></h1>
            <?php if ($job['is_featured']): ?>
                <span class="salary-badge">⭐ Featured</span>
            <?php endif; ?>
        </div>
        <div class="detail-meta">
            <span>🏢 <?php echo htmlspecialchars($job['first_name'] . ' ' . $job['last_name']); ?></span>
            <span>📍 <?php echo htmlspecialchars($job['location']); ?></span>
            <span><?php echo ucfirst(str_replace('-', ' ', $job['job_type'])); ?></span>
            <span><?php echo htmlspecialchars($job['category_name'] ?? 'General'); ?></span>
        </div>
    </div>

    <div class="detail-body">
        <?php if ($job['salary_min'] && $job['salary_max']): ?>
            <div class="salary-badge">
                💰 <?php echo number_format($job['salary_min'], 0); ?> - <?php echo number_format($job['salary_max'], 0); ?> <?php echo htmlspecialchars($job['currency'] ?? 'RWF'); ?>/month
            </div>
        <?php endif; ?>

        <div class="detail-section">
            <h3>Job Description</h3>
            <?php echo $job['description']; ?>
        </div>

        <?php if (!empty($job['requirements'])): ?>
        <div class="detail-section">
            <h3>Requirements</h3>
            <ul class="job-requirements">
                <?php foreach (explode('\n', $job['requirements']) as $req): ?>
                    <?php if (trim($req)): ?>
                        <li><?php echo htmlspecialchars(trim($req)); ?></li>
                    <?php endif; ?>
                <?php endforeach; ?>
            </ul>
        </div>
        <?php endif; ?>

        <?php if (!empty($job['benefits'])): ?>
        <div class="detail-section">
            <h3>Benefits</h3>
            <ul class="job-benefits">
                <?php foreach (explode('\n', $job['benefits']) as $benefit): ?>
                    <?php if (trim($benefit)): ?>
                        <li><?php echo htmlspecialchars(trim($benefit)); ?></li>
                    <?php endif; ?>
                <?php endforeach; ?>
            </ul>
        </div>
        <?php endif; ?>

        <div class="detail-section">
            <h3>Details</h3>
            <div class="detail-info-item">
                <span class="detail-info-label">Experience Level:</span>
                <span class="detail-info-value"><?php echo ucfirst($job['experience_level'] ?? 'Not specified'); ?></span>
            </div>
            <div class="detail-info-item">
                <span class="detail-info-label">Application Deadline:</span>
                <span class="detail-info-value" style="color: var(--danger);">⏰ <?php echo date('F d, Y', strtotime($job['deadline'])); ?></span>
            </div>
            <div class="detail-info-item">
                <span class="detail-info-label">Posted:</span>
                <span class="detail-info-value"><?php echo date('M d, Y', strtotime($job['published_at'])); ?></span>
            </div>
            <div class="detail-info-item">
                <span class="detail-info-label">Applications:</span>
                <span class="detail-info-value"><?php echo intval($job['application_count']); ?></span>
            </div>
            <div class="detail-info-item">
                <span class="detail-info-label">Views:</span>
                <span class="detail-info-value"><?php echo intval($job['views_count']); ?></span>
            </div>
        </div>

        <div class="detail-section">
            <h3>Contact Employer</h3>
            <?php if (!empty($job['email'])): ?>
                <p>📧 <a href="mailto:<?php echo htmlspecialchars($job['email']); ?>"><?php echo htmlspecialchars($job['email']); ?></a></p>
            <?php endif; ?>
            <?php if (!empty($job['phone'])): ?>
                <p>📞 <a href="tel:<?php echo htmlspecialchars($job['phone']); ?>"><?php echo htmlspecialchars($job['phone']); ?></a></p>
            <?php endif; ?>
        </div>

        <?php if ($user): ?>
            <?php if ($userApplied): ?>
                <div class="alert alert-success">
                    ✓ You have already applied for this job
                </div>
            <?php else: ?>
                <a href="<?php echo APP_URL; ?>/jobs/<?php echo $job['id']; ?>/apply" class="btn btn-primary btn-lg apply-button">
                    Apply Now
                </a>
            <?php endif; ?>
        <?php else: ?>
            <div class="alert alert-info">
                <a href="<?php echo APP_URL; ?>/auth/login">Login</a> or <a href="<?php echo APP_URL; ?>/auth/register">Register</a> to apply for this job
            </div>
        <?php endif; ?>
    </div>
</div>

<div style="text-align: center; margin-top: 2rem; margin-bottom: 2rem;">
    <a href="<?php echo APP_URL; ?>/jobs" class="btn btn-ghost">
        ← Back to Jobs
    </a>
</div>

.job-detail {
    background: white;
    border-radius: 8px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    padding: 2.5rem;
    margin-bottom: 2rem;
}

.job-header {
    margin-bottom: 2rem;
    padding-bottom: 2rem;
    border-bottom: 2px solid #f0f0f0;
}

.job-title-section {
    display: flex;
    align-items: center;
    gap: 1rem;
    margin-bottom: 1.5rem;
}

.job-title {
    font-size: 2rem;
    color: #1a1a1a;
    margin: 0;
}

.badge-featured {
    background: #fff3cd;
    color: #856404;
    padding: 0.5rem 1rem;
    border-radius: 20px;
    font-size: 0.9rem;
    white-space: nowrap;
}

.job-meta {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 1rem;
    margin: 1.5rem 0;
}

.meta-item {
    display: flex;
    flex-direction: column;
    gap: 0.3rem;
}

.meta-item .label {
    font-weight: bold;
    color: #666;
    font-size: 0.9rem;
}

.meta-item .value {
    color: #1a1a1a;
    font-size: 1rem;
}

.job-section {
    margin: 2rem 0;
    padding: 1.5rem;
    background: #f9f9f9;
    border-radius: 8px;
}

.job-section h2 {
    color: #1a1a1a;
    margin-bottom: 1rem;
    font-size: 1.3rem;
}

.section-content {
    color: #333;
    line-height: 1.7;
}

.section-content p {
    margin-bottom: 1rem;
}

.apply-section {
    margin: 2rem 0;
    padding: 1.5rem;
    background: #f0f8ff;
    border-left: 4px solid #1976d2;
    border-radius: 8px;
}

.contact-section p {
    margin: 0.5rem 0;
}

.contact-section a {
    color: #1976d2;
    text-decoration: none;
}

.contact-section a:hover {
    text-decoration: underline;
}

.btn-back:hover {
    color: #1565c0;
}

@media (max-width: 768px) {
    .job-detail {
        padding: 1.5rem;
    }

    .job-title {
        font-size: 1.5rem;
    }

    .job-title-section {
        flex-direction: column;
        align-items: flex-start;
    }

    .job-meta {
        grid-template-columns: 1fr;
    }
}
<?php
$content = ob_get_clean();
require_once ROOT_PATH . '/app/views/layouts/main.php';
?>
