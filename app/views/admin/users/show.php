<?php
extract($__data ?? []);
ob_start();

$errors = $_SESSION['errors'] ?? [];
if (isset($_SESSION['errors'])) {
    unset($_SESSION['errors']);
}
?>

<div class="admin-page" style="padding: 30px; max-width: 1000px; margin: 0 auto;">
    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:20px;">
        <div>
            <h1>User Details</h1>
            <p style="color:#555;">Manage this Super Admin account safely. You may change role, status, email, or reset password.</p>
        </div>
        <a href="<?php echo APP_URL; ?>/admin/users" class="btn btn-outline">Back to users</a>
    </div>

    <?php if (!empty($errors)): ?>
        <div class="alert alert-danger" style="margin-bottom: 1.5rem;">
            <strong>Please correct the following:</strong>
            <ul style="margin: 0.75rem 0 0 1.25rem;">
                <?php foreach ($errors as $error): ?>
                    <li><?php echo htmlspecialchars($error); ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <div class="card" style="padding: 24px;">
        <form method="POST" action="<?php echo APP_URL; ?>/admin/users/<?php echo intval($selectedUser['id']); ?>/edit">
            <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($csrf_token); ?>">

            <div style="display:grid; grid-template-columns: repeat(2, minmax(240px, 1fr)); gap: 1rem; margin-bottom: 1.5rem;">
                <div class="form-group">
                    <label for="first_name">First Name</label>
                    <input id="first_name" name="first_name" type="text" required value="<?php echo htmlspecialchars($selectedUser['first_name']); ?>">
                </div>
                <div class="form-group">
                    <label for="last_name">Last Name</label>
                    <input id="last_name" name="last_name" type="text" required value="<?php echo htmlspecialchars($selectedUser['last_name']); ?>">
                </div>
                <div class="form-group">
                    <label for="email">Email Address</label>
                    <input id="email" name="email" type="email" required value="<?php echo htmlspecialchars($selectedUser['email']); ?>">
                </div>
                <div class="form-group">
                    <label for="phone">Phone</label>
                    <input id="phone" name="phone" type="tel" value="<?php echo htmlspecialchars($selectedUser['phone'] ?? ''); ?>">
                </div>
            </div>

            <div style="display:grid; grid-template-columns: repeat(2, minmax(240px, 1fr)); gap: 1rem; margin-bottom: 1.5rem;">
                <div class="form-group">
                    <label for="role_id">Role</label>
                    <select id="role_id" name="role_id" required>
                        <option value="">Select role</option>
                        <?php foreach ($roles as $roleItem): ?>
                            <option value="<?php echo (int)$roleItem['id']; ?>" <?php echo $selectedUser['role_id'] == $roleItem['id'] ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($roleItem['name']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group" style="display:flex; align-items:flex-end; gap: 0.75rem;">
                    <label style="width:100%;">
                        <span style="display:block; margin-bottom:0.5rem;">Account status</span>
                        <label style="display:flex; align-items:center; gap:0.5rem; font-weight:400;">
                            <input id="is_active" name="is_active" type="checkbox" <?php echo $selectedUser['is_active'] ? 'checked' : ''; ?>>
                            Active
                        </label>
                    </label>
                </div>
            </div>

            <div style="border-top:1px solid #eee; padding-top: 1.5rem; margin-bottom: 1.5rem;">
                <h3>Security</h3>
                <div style="margin-top:1rem;">
                            <div class="form-group">
                                <label>Password Hash</label>
                                <div style="display:flex; align-items:center; gap:0.75rem;">
                                    <?php
                                        $revealKey = 'revealed_password_hash_for_user_' . intval($selectedUser['id']);
                                        $revealed = $_SESSION[$revealKey] ?? null;
                                    ?>
                                    <?php if ($revealed): ?>
                                        <input type="text" readonly value="<?php echo htmlspecialchars($revealed); ?>" style="flex:1;">
                                        <?php unset($_SESSION[$revealKey]); ?>
                                    <?php else: ?>
                                        <input type="text" readonly value="******** (hidden)" style="flex:1;">
                                        </div>
                                        <small class="form-text">Password hashes are hidden. Reveal only when necessary.</small>
                                    <?php endif; ?>
                            </div>

                    <div style="display:grid; grid-template-columns: repeat(2, minmax(240px, 1fr)); gap: 1rem; margin-top:1rem;">
                        <div class="form-group">
                            <label for="password">New Password</label>
                            <input id="password" name="password" type="password" placeholder="Enter new password">
                        </div>
                        <div class="form-group">
                            <label for="password_confirm">Confirm New Password</label>
                            <input id="password_confirm" name="password_confirm" type="password" placeholder="Confirm new password">
                        </div>
                    </div>
                </div>
            </div>

            <div style="display:flex; gap: 0.75rem; flex-wrap: wrap; align-items:center;">
                <button type="submit" class="btn btn-primary">Save changes</button>
                <a href="<?php echo APP_URL; ?>/admin/users" class="btn btn-outline">Cancel</a>
                <form method="POST" action="<?php echo APP_URL; ?>/admin/users/<?php echo intval($selectedUser['id']); ?>/delete" onsubmit="return confirm('Are you sure you want to delete this user?');" style="display:inline-block; margin:0;">
                    <button type="submit" class="btn btn-danger">Delete User</button>
                </form>
            </div>
        </form>
        
        <!-- Reveal hash form (outside of edit form to avoid nested forms) -->
        <div style="margin-top: 1rem;">
            <form method="POST" action="<?php echo APP_URL; ?>/admin/users/<?php echo intval($selectedUser['id']); ?>/reveal-hash" style="display:inline-block;">
                <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($csrf_token); ?>">
                <button type="submit" class="btn btn-ghost" onclick="return confirm('Reveal the stored password hash for this user? This action is audited.');">Reveal password hash</button>
            </form>
        </div>
    </div>
</div>

<?php
$content = ob_get_clean();
include __DIR__ . '/../../layouts/admin.php';
?>