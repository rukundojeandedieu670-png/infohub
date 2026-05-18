<?php
extract($__data ?? []);

ob_start();
?>

<h2 style="margin-bottom: 2rem;">Activity Logs</h2>

<div class="card">
    <table class="admin-table">
        <thead>
            <tr>
                <th>User</th>
                <th>Action</th>
                <th>Module</th>
                <th>IP Address</th>
                <th>Date</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($logs as $log): ?>
                <tr>
                    <td><?php echo htmlspecialchars(($log['first_name'] ?? 'Unknown') . ' ' . ($log['last_name'] ?? '')); ?></td>
                    <td><?php echo htmlspecialchars($log['action']); ?></td>
                    <td><span class="status-badge status-info"><?php echo htmlspecialchars($log['module']); ?></span></td>
                    <td><small><?php echo htmlspecialchars($log['ip_address']); ?></small></td>
                    <td><?php echo date('M d, Y H:i', strtotime($log['created_at'])); ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php
$content = ob_get_clean();
include __DIR__ . '/../../layouts/admin.php';
?>
