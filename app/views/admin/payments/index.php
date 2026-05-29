<?php
extract($__data ?? []);

ob_start();
?>

<h2 style="margin-bottom: 2rem;">Payments & Donations</h2>

<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 1.5rem; margin-bottom: 2rem;">
    <div class="stat-card">
        <h3>Total Transactions</h3>
        <div class="stat-value"><?php echo number_format($paymentCount); ?></div>
    </div>
    <div class="stat-card">
        <h3>Donation Total</h3>
        <div class="stat-value">PHP <?php echo number_format($donationSummary['total_amount'] ?? 0, 2); ?></div>
    </div>
    <div class="stat-card">
        <h3>Pending Payments</h3>
        <div class="stat-value"><?php echo number_format(array_reduce($statusSummary, fn($carry, $item) => $carry + ($item['status'] === 'pending' ? $item['count'] : 0), 0)); ?></div>
    </div>
</div>

<div class="card" style="margin-bottom: 2rem;">
    <table class="admin-table">
        <thead>
            <tr>
                <th>Date</th>
                <th>User</th>
                <th>Type</th>
                <th>Amount</th>
                <th>Status</th>
                <th>Method</th>
                <th>Reference</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($payments as $payment): ?>
                <tr>
                    <td><?php echo htmlspecialchars(date('M d, Y H:i', strtotime($payment['created_at']))); ?></td>
                    <td><?php echo htmlspecialchars(($payment['first_name'] ?? 'Unknown') . ' ' . ($payment['last_name'] ?? '')); ?> <br><small><?php echo htmlspecialchars($payment['email'] ?? ''); ?></small></td>
                    <td><?php echo htmlspecialchars($payment['payment_type']); ?></td>
                    <td><?php echo number_format($payment['amount'], 2); ?> <?php echo htmlspecialchars($payment['currency'] ?? ''); ?></td>
                    <td><span class="status-badge status-<?php echo $payment['status'] === 'completed' ? 'success' : ($payment['status'] === 'pending' ? 'warning' : ($payment['status'] === 'failed' ? 'danger' : 'info')); ?>"><?php echo htmlspecialchars(ucfirst($payment['status'])); ?></span></td>
                    <td><?php echo htmlspecialchars($payment['payment_method']); ?></td>
                    <td><?php echo htmlspecialchars($payment['transaction_id']); ?></td>
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
                <a href="<?php echo APP_URL; ?>/admin/payments?page=<?php echo $p; ?>"><?php echo $p; ?></a>
            <?php endif; ?>
        <?php endfor; ?>
    </div>
<?php endif; ?>

<?php
$content = ob_get_clean();
include __DIR__ . '/../../layouts/admin.php';
?>
