<?php
extract($__data ?? []);

ob_start();
?>

<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 1.5rem; margin-bottom: 2rem;">
    <div class="stat-card">
        <h3>Total Users</h3>
        <div class="stat-value"><?php echo number_format($userCount); ?></div>
    </div>
    
    <div class="stat-card">
        <h3>Published Posts</h3>
        <div class="stat-value"><?php echo number_format($postCount); ?></div>
    </div>
    
    <div class="stat-card">
        <h3>Open Jobs</h3>
        <div class="stat-value"><?php echo number_format($jobCount); ?></div>
    </div>
    
    <div class="stat-card">
        <h3>Verified Businesses</h3>
        <div class="stat-value"><?php echo number_format($businessCount); ?></div>
    </div>
</div>

<?php if ($pendingBusinesses > 0): ?>
    <div class="alert alert-warning" style="margin-bottom: 2rem;">
        <strong><?php echo $pendingBusinesses; ?> business(es) pending verification</strong>
        <a href="<?php echo APP_URL; ?>/admin/businesses" style="margin-left: 1rem;">Review now →</a>
    </div>
<?php endif; ?>

<h2 style="margin-bottom: 1.5rem;">Recent Activity</h2>

<div class="card">
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
</div>

<?php
$content = ob_get_clean();
include __DIR__ . '/../../layouts/admin.php';
?>
