<?php
/**
 * Business Detail View
 */
ob_start();
?>

<div class="detail-container">
    <div class="detail-header">
        <div class="flex-between mb-3">
            <div>
                <h1><?php echo htmlspecialchars($business['name']); ?></h1>
                <p class="text-muted" style="margin: 0.5rem 0;">
                    <?php echo htmlspecialchars($business['category_name'] ?? 'General'); ?> • 
                    📍 <?php echo htmlspecialchars($business['location']); ?>
                </p>
            </div>
            <?php if ($business['is_featured']): ?>
                <span class="salary-badge" style="align-self: flex-start;">⭐ Featured</span>
            <?php endif; ?>
        </div>
        <div class="detail-meta">
            <?php if ($business['verification_status'] === 'verified'): ?>
                <span style="color: var(--success); font-weight: 600;">✓ Verified</span>
            <?php endif; ?>
            <span>🏢 Founded <?php echo intval($business['founded_year'] ?? 'N/A'); ?></span>
            <span>👥 <?php echo intval($business['employees_count'] ?? 0); ?> employees</span>
        </div>
    </div>

    <div class="detail-body">
        <?php if (!empty($business['logo'])): ?>
            <img src="<?php echo htmlspecialchars($business['logo']); ?>" alt="<?php echo htmlspecialchars($business['name']); ?>" class="business-logo">
        <?php else: ?>
            <div style="width: 120px; height: 120px; background: var(--bg-gray); border-radius: var(--radius-md); display: flex; align-items: center; justify-content: center; font-size: 2.5rem; margin-bottom: 1rem;">
                🏢
            </div>
        <?php endif; ?>

        <div class="detail-section">
            <h3>About <?php echo htmlspecialchars($business['name']); ?></h3>
            <?php echo htmlspecialchars($business['description']); ?>
        </div>

        <div class="detail-section">
            <h3>Contact Information</h3>
            <div class="business-info-grid">
                <?php if (!empty($business['phone'])): ?>
                    <div class="business-info-card">
                        <strong>📞 Phone</strong>
                        <a href="tel:<?php echo htmlspecialchars($business['phone']); ?>">
                            <?php echo htmlspecialchars($business['phone']); ?>
                        </a>
                    </div>
                <?php endif; ?>
                
                <?php if (!empty($business['email'])): ?>
                    <div class="business-info-card">
                        <strong>✉️ Email</strong>
                        <a href="mailto:<?php echo htmlspecialchars($business['email']); ?>">
                            <?php echo htmlspecialchars($business['email']); ?>
                        </a>
                    </div>
                <?php endif; ?>
                
                <?php if (!empty($business['website'])): ?>
                    <div class="business-info-card">
                        <strong>🌐 Website</strong>
                        <a href="<?php echo htmlspecialchars($business['website']); ?>" target="_blank">
                            Visit Website
                        </a>
                    </div>
                <?php endif; ?>
                
                <div class="business-info-card">
                    <strong>📍 Address</strong>
                    <?php echo htmlspecialchars($business['location']); ?>
                </div>
            </div>
        </div>

        <?php if (!empty($business['business_registration']) || !empty($business['tax_id'])): ?>
        <div class="detail-section">
            <h3>Business Information</h3>
            <div class="detail-info-item">
                <span class="detail-info-label">Business Registration:</span>
                <span class="detail-info-value"><code style="background: var(--bg-light); padding: 0.25rem 0.5rem; border-radius: 4px; font-family: monospace;"><?php echo htmlspecialchars($business['business_registration'] ?? 'N/A'); ?></code></span>
            </div>
            <div class="detail-info-item">
                <span class="detail-info-label">Tax ID:</span>
                <span class="detail-info-value"><code style="background: var(--bg-light); padding: 0.25rem 0.5rem; border-radius: 4px; font-family: monospace;"><?php echo htmlspecialchars($business['tax_id'] ?? 'N/A'); ?></code></span>
            </div>
        </div>
        <?php endif; ?>

        <div class="detail-section">
            <h3>Statistics</h3>
            <div class="detail-info-item">
                <span class="detail-info-label">Views:</span>
                <span class="detail-info-value"><?php echo intval($business['views'] ?? 0); ?></span>
            </div>
            <div class="detail-info-item">
                <span class="detail-info-label">Member Since:</span>
                <span class="detail-info-value"><?php echo date('M Y', strtotime($business['created_at'])); ?></span>
            </div>
            <div class="detail-info-item">
                <span class="detail-info-label">Status:</span>
                <span class="detail-info-value" style="color: var(--success); font-weight: 600;"><?php echo ucfirst($business['verification_status'] ?? 'pending'); ?></span>
            </div>
            <?php if ($business['verification_status'] === 'verified' && !empty($business['verified_at'])): ?>
            <div class="detail-info-item">
                <span class="detail-info-label">Verified On:</span>
                <span class="detail-info-value"><?php echo date('F d, Y', strtotime($business['verified_at'])); ?></span>
            </div>
            <?php endif; ?>
        </div>

        <?php if (!empty($business['first_name'])): ?>
        <div class="detail-section" style="background: var(--bg-light); border-left: 4px solid var(--primary);">
            <h3>Business Owner</h3>
            <div class="detail-info-item">
                <span class="detail-info-label">Name:</span>
                <span class="detail-info-value"><?php echo htmlspecialchars($business['first_name'] . ' ' . $business['last_name']); ?></span>
            </div>
            <?php if (!empty($business['phone'])): ?>
            <div class="detail-info-item">
                <span class="detail-info-label">Contact:</span>
                <span class="detail-info-value">
                    <a href="tel:<?php echo htmlspecialchars($business['phone']); ?>">
                        📞 <?php echo htmlspecialchars($business['phone']); ?>
                    </a>
                </span>
            </div>
            <?php endif; ?>
        </div>
        <?php endif; ?>
    </div>
</div>

<div style="text-align: center; margin-top: 2rem; margin-bottom: 2rem;">
    <a href="<?php echo APP_URL; ?>/business" class="btn btn-ghost">
        ← Back to Businesses
    </a>
</div>


<?php
$content = ob_get_clean();
require_once ROOT_PATH . '/app/views/layouts/main.php';
?>
