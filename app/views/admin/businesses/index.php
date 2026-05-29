<?php
extract($__data ?? []);

ob_start();
?>

<h2 style="margin-bottom: 2rem;">Business Verification</h2>

<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 1.5rem; margin-bottom: 2rem;">
    <div class="stat-card">
        <h3>Pending Review</h3>
        <div class="stat-value"><?php echo number_format(count($pendingBusinesses)); ?></div>
    </div>
    <div class="stat-card">
        <h3>Verified Businesses</h3>
        <div class="stat-value"><?php echo number_format(count($verifiedBusinesses)); ?></div>
    </div>
</div>

<div class="card" style="margin-bottom: 2rem;">
    <h3 style="margin-bottom: 1rem;">Pending Verifications</h3>
    <?php if (empty($pendingBusinesses)): ?>
        <p>No businesses are pending verification at the moment.</p>
    <?php else: ?>
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Business</th>
                    <th>Owner</th>
                    <th>Category</th>
                    <th>Submitted</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($pendingBusinesses as $business): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($business['name']); ?></td>
                        <td><?php echo htmlspecialchars($business['first_name'] . ' ' . $business['last_name']); ?></td>
                        <td><?php echo htmlspecialchars($business['category_name']); ?></td>
                        <td><?php echo htmlspecialchars(date('M d, Y', strtotime($business['created_at']))); ?></td>
                        <td>
                            <form method="POST" action="<?php echo APP_URL; ?>/admin/businesses/<?php echo intval($business['id']); ?>/verify" style="display:inline-block;">
                                <button type="submit" class="btn btn-sm btn-primary">Verify</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>

<div class="card">
    <h3 style="margin-bottom: 1rem;">Recently Verified</h3>
    <table class="admin-table">
        <thead>
            <tr>
                <th>Business</th>
                <th>Owner</th>
                <th>Category</th>
                <th>Verified At</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($verifiedBusinesses as $business): ?>
                <tr>
                    <td><?php echo htmlspecialchars($business['name']); ?></td>
                    <td><?php echo htmlspecialchars($business['first_name'] . ' ' . $business['last_name']); ?></td>
                    <td><?php echo htmlspecialchars($business['category_name']); ?></td>
                    <td><?php echo htmlspecialchars(date('M d, Y', strtotime($business['verified_at'] ?? $business['created_at']))); ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php
$content = ob_get_clean();
include __DIR__ . '/../../layouts/admin.php';
?>
