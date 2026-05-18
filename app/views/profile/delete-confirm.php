<?php
// Account deletion confirmation page
ob_start();
?>

<div class="container" style="max-width: 700px; margin: 60px auto 80px auto;">
    <div class="card" style="padding: 32px; background: #fff5f5; border-color: #fecaca;">
        <h1 style="margin-bottom: 16px; color: #991b1b;">Delete Your Account</h1>
        <p style="color: #7f1d1d; font-size: 1rem; line-height: 1.8; margin-bottom: 24px;">
            This action is permanent and cannot be undone. Your profile, settings, applications, and all associated data will be permanently removed from InfoHub.
        </p>

        <div style="background: #fef2f2; border: 1px solid #fecaca; border-radius: 12px; padding: 20px; margin-bottom: 24px;">
            <strong style="display: block; margin-bottom: 10px; color: #991b1b;">Confirm deletion</strong>
            <p style="margin: 0; color: #7f1d1d;">Type <strong>DELETE</strong> below to confirm that you want to permanently remove your account.</p>
        </div>

        <form method="POST" action="<?php echo APP_URL; ?>/profile/delete">
            <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($csrf_token); ?>">

            <div class="form-group">
                <label for="confirmation" style="font-weight: 700; color: #111827;">Confirmation text</label>
                <input id="confirmation" name="confirmation" type="text" placeholder="Type DELETE to confirm" style="width: 100%; padding: 12px 14px; font-size: 1rem; border: 1px solid #f5c2c7; border-radius: 10px; background: #fff5f5; color: #7f1d1d;" required>
            </div>

            <div style="display: flex; flex-wrap: wrap; gap: 12px; margin-top: 24px;">
                <button type="submit" class="btn btn-danger" style="background: #b91c1c; border-color: #b91c1c;">🗑️ Permanently Delete Account</button>
                <a href="<?php echo APP_URL; ?>/profile" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>

<?php
$content = ob_get_clean();
include ROOT_PATH . '/app/views/layouts/main.php';
?>