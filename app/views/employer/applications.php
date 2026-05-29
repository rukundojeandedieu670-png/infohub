<?php
extract($data ?? []);
ob_start();
?>
<div class="container" style="padding: 40px 20px; max-width: 1100px; margin: 0 auto;">
    <h1>Applications for <?php echo htmlspecialchars($job['title'] ?? 'this job'); ?></h1>
    <p>Review the candidate submissions and contact qualified applicants.</p>

    <?php if (!empty($applications)): ?>
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Applicant</th>
                        <th>Email</th>
                        <th>Submitted</th>
                        <th>Status</th>
                        <th>Cover Letter</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($applications as $app): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($app['first_name'] . ' ' . $app['last_name']); ?></td>
                            <td><?php echo htmlspecialchars($app['email']); ?></td>
                            <td><?php echo htmlspecialchars(date('Y-m-d', strtotime($app['applied_at']))); ?></td>
                            <td><?php echo htmlspecialchars(ucfirst($app['status'])); ?></td>
                            <td><?php echo nl2br(htmlspecialchars(substr($app['cover_letter'] ?? '', 0, 180))); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php else: ?>
        <div class="empty-state">
            <p>No applications have been submitted yet.</p>
        </div>
    <?php endif; ?>

    <a href="<?php echo APP_URL; ?>/employer/jobs" class="btn btn-outline">Back to jobs</a>
</div>

<?php
$content = ob_get_clean();
include __DIR__ . '/../layouts/main.php';
