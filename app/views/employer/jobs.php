<?php
extract($data ?? []);
ob_start();
?>
<div class="container" style="padding: 40px 20px; max-width: 1100px; margin: 0 auto;">
    <h1>Employer Dashboard</h1>
    <p>Manage your posted jobs, applications and company listings.</p>

    <div style="margin: 24px 0; display: flex; justify-content: space-between; gap: 12px; flex-wrap: wrap;">
        <a href="<?php echo APP_URL; ?>/employer/jobs/create" class="btn btn-primary">Post a New Job</a>
        <span style="align-self: center; color: #555;">Signed in as <?php echo htmlspecialchars($user['first_name'] ?? 'Employer'); ?></span>
    </div>

    <?php if (!empty($jobs)): ?>
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>Category</th>
                        <th>Location</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($jobs as $job): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($job['title']); ?></td>
                            <td><?php echo htmlspecialchars($job['category_name'] ?? 'Uncategorized'); ?></td>
                            <td><?php echo htmlspecialchars($job['location']); ?></td>
                            <td><?php echo htmlspecialchars(ucfirst($job['status'])); ?></td>
                            <td style="white-space: nowrap;">
                                <a href="<?php echo APP_URL; ?>/employer/jobs/<?php echo intval($job['id']); ?>/edit" class="btn btn-sm btn-secondary">Edit</a>
                                <a href="<?php echo APP_URL; ?>/employer/jobs/<?php echo intval($job['id']); ?>/applications" class="btn btn-sm btn-outline">Applications</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php else: ?>
        <div class="empty-state">
            <p>You haven’t posted any jobs yet. Use the button above to create your first listing.</p>
        </div>
    <?php endif; ?>
</div>

<?php
$content = ob_get_clean();
include __DIR__ . '/../layouts/main.php';
