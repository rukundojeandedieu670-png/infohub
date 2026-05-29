<?php
extract($__data ?? []);

ob_start();
?>

<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 1.5rem; margin-bottom: 2rem;">
    <?php if (!empty($isSuperAdmin)): ?>
        <div class="stat-card">
            <h3>Total Users</h3>
            <div class="stat-value"><?php echo number_format($userCount ?? 0); ?></div>
        </div>
    <?php endif; ?>

    <div class="stat-card">
        <h3>Published Posts</h3>
        <div class="stat-value"><?php echo number_format($postCount ?? 0); ?></div>
    </div>

    <div class="stat-card">
        <h3>Open Jobs</h3>
        <div class="stat-value"><?php echo number_format($jobCount ?? 0); ?></div>
    </div>

    <div class="stat-card">
        <h3>Verified Businesses</h3>
        <div class="stat-value"><?php echo number_format($businessCount ?? 0); ?></div>
    </div>

    <?php if (!empty($isSuperAdmin)): ?>
        <div class="stat-card">
            <h3>Payment Transactions</h3>
            <div class="stat-value"><?php echo number_format($paymentCount ?? 0); ?></div>
        </div>

        <div class="stat-card">
            <h3>Completed Revenue</h3>
            <div class="stat-value">PHP <?php echo number_format($paymentVolume ?? 0, 2); ?></div>
        </div>

        <div class="stat-card">
            <h3>Donation Revenue</h3>
            <div class="stat-value">PHP <?php echo number_format($donationVolume ?? 0, 2); ?></div>
        </div>
    <?php endif; ?>
</div>

<?php if ($pendingBusinesses > 0): ?>
    <div class="alert alert-warning" style="margin-bottom: 2rem;">
        <strong><?php echo $pendingBusinesses; ?> business(es) pending verification</strong>
        <a href="<?php echo APP_URL; ?>/admin/businesses" style="margin-left: 1rem;">Review now →</a>
    </div>
<?php endif; ?>

<h2 style="margin-bottom: 1.5rem;">Recent Activity</h2>

<div class="card">
    <?php if (empty($recentActivities)): ?>
        <div style="padding: 1.5rem; color: #555;">No recent activity available<?php echo empty($isSuperAdmin) ? ' (showing only your activity)' : ''; ?>.</div>
    <?php else: ?>
        <table class="admin-table">
            <thead>
                <tr>
                    <th>User</th>
                    <th>Action</th>
                    <th>Module</th>
                    <th>Date</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($recentActivities as $activity): ?>
                    <tr>
                        <td><?php echo htmlspecialchars(($activity['first_name'] ?? 'Unknown') . ' ' . ($activity['last_name'] ?? '')); ?></td>
                        <td><?php echo htmlspecialchars($activity['action']); ?></td>
                        <td><span class="status-badge status-info"><?php echo htmlspecialchars($activity['module']); ?></span></td>
                        <td><?php echo date('M d, Y H:i', strtotime($activity['created_at'])); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>

<?php
$content = ob_get_clean();
include __DIR__ . '/../layouts/admin.php';
?>
