<?php
extract($__data ?? []);

ob_start();
?>

<h2 style="margin-bottom: 2rem;">Users Management</h2>

<div class="card">
    <table class="admin-table">
        <thead>
            <tr>
                <th>Name</th>
                <th>Email</th>
                <th>Role</th>
                <th>Status</th>
                <th>Joined</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($users as $u): ?>
                <tr>
                    <td><?php echo htmlspecialchars($u['first_name'] . ' ' . $u['last_name']); ?></td>
                    <td><?php echo htmlspecialchars($u['email']); ?></td>
                    <td><?php echo htmlspecialchars($u['role_name']); ?></td>
                    <td>
                        <?php if ($u['is_active']): ?>
                            <span class="status-badge status-success">Active</span>
                        <?php else: ?>
                            <span class="status-badge status-danger">Inactive</span>
                        <?php endif; ?>
                    </td>
                    <td><?php echo date('M d, Y', strtotime($u['created_at'])); ?></td>
                    <td>
                        <a href="<?php echo APP_URL; ?>/admin/users/<?php echo $u['id']; ?>" class="btn btn-sm btn-ghost">View</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php if ($totalPages > 1): ?>
    <div class="pagination" style="margin-top: 2rem;">
        <?php for ($p = 1; $p <= $totalPages; $p++): ?>
            <?php if ($p === $page): ?>
                <span class="active"><?php echo $p; ?></span>
            <?php else: ?>
                <a href="<?php echo APP_URL; ?>/admin/users?page=<?php echo $p; ?>"><?php echo $p; ?></a>
            <?php endif; ?>
        <?php endfor; ?>
    </div>
<?php endif; ?>

<?php
$content = ob_get_clean();
$flash = $flash ?? null;
include __DIR__ . '/../../layouts/admin.php';
?>
