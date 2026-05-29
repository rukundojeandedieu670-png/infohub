<?php
extract($data ?? []);
ob_start();
?>
<div class="container" style="padding: 40px 20px; max-width: 1000px; margin: 0 auto;">
    <h1>Business Owner Dashboard</h1>
    <p>Manage your business profile and overview your company details.</p>

    <?php if ($business): ?>
        <div class="card" style="padding: 24px; margin-top: 20px;">
            <h2><?php echo htmlspecialchars($business['name']); ?></h2>
            <p><?php echo htmlspecialchars($business['description'] ?? 'No description provided.'); ?></p>
            <p><strong>Category:</strong> <?php echo htmlspecialchars($business['category_name'] ?? 'Unspecified'); ?></p>
            <p><strong>Location:</strong> <?php echo htmlspecialchars($business['location'] ?? 'Not set'); ?></p>
            <p><strong>Contact:</strong> <?php echo htmlspecialchars($business['phone'] ?? $user['email']); ?></p>

            <div style="margin-top: 20px; display:flex; flex-wrap:wrap; gap:12px;">
                <a href="<?php echo APP_URL; ?>/business-owner/profile" class="btn btn-primary">Edit Business Profile</a>
                <a href="<?php echo APP_URL; ?>/business" class="btn btn-outline">View Public Business Listing</a>
            </div>
        </div>
    <?php else: ?>
        <div class="empty-state" style="margin-top: 20px;">
            <p>You don’t have a business profile yet. Fill in your business details to get started.</p>
            <a href="<?php echo APP_URL; ?>/business-owner/profile" class="btn btn-primary">Create Business Profile</a>
        </div>
    <?php endif; ?>
</div>

<?php
$content = ob_get_clean();
include __DIR__ . '/../layouts/main.php';
