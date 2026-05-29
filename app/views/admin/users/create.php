<?php
extract($__data ?? []);

ob_start();

$formData = $_SESSION['form_data'] ?? [];
$errors = $_SESSION['errors'] ?? [];
if (isset($_SESSION['errors'])) {
    unset($_SESSION['errors']);
}

$firstName = htmlspecialchars($formData['first_name'] ?? '');
$lastName = htmlspecialchars($formData['last_name'] ?? '');
$email = htmlspecialchars($formData['email'] ?? '');
$phone = htmlspecialchars($formData['phone'] ?? '');
$selectedRole = intval($formData['role_id'] ?? 0);
$isActiveChecked = isset($formData['is_active']) ? ($formData['is_active'] ? 'checked' : '') : 'checked';

?>

<div class="container-sm" style="margin: 3rem 0; max-width: 720px;">
    <div class="card">
        <div class="card-body">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
                <div>
                    <h2 style="margin: 0 0 0.5rem 0;">Create Admin User</h2>
                    <p class="text-muted" style="margin: 0;">Create a secure account with a selected role and strong password.</p>
                </div>
                <a href="<?php echo APP_URL; ?>/admin/users" class="btn btn-ghost">Back to users</a>
            </div>

            <?php if (!empty($errors)): ?>
                <div class="alert alert-danger" style="margin-bottom: 1.5rem;">
                    <strong>There were some problems with your submission:</strong>
                    <ul style="margin: 0.75rem 0 0 1.25rem;">
                        <?php foreach ($errors as $error): ?>
                            <li><?php echo htmlspecialchars($error); ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>

            <form method="POST" action="<?php echo APP_URL; ?>/admin/users/store">
                <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($csrf_token); ?>">

                <div class="grid grid-2" style="gap: 1rem; margin-bottom: 1rem;">
                    <div class="form-group">
                        <label for="first_name">First Name</label>
                        <input id="first_name" name="first_name" type="text" required value="<?php echo $firstName; ?>" placeholder="John">
                    </div>
                    <div class="form-group">
                        <label for="last_name">Last Name</label>
                        <input id="last_name" name="last_name" type="text" required value="<?php echo $lastName; ?>" placeholder="Doe">
                    </div>
                </div>

                <div class="form-group" style="margin-bottom: 1rem;">
                    <label for="email">Email Address</label>
                    <input id="email" name="email" type="email" required value="<?php echo $email; ?>" placeholder="user@example.com">
                </div>

                <div class="form-group" style="margin-bottom: 1rem;">
                    <label for="phone">Phone Number</label>
                    <input id="phone" name="phone" type="tel" value="<?php echo $phone; ?>" placeholder="+250 788 123 456">
                </div>

                <div class="form-group" style="margin-bottom: 1rem;">
                    <label for="role_id">Assign Role</label>
                    <select id="role_id" name="role_id" required>
                        <option value="">Select role</option>
                        <?php foreach ($roles as $roleItem): ?>
                            <option value="<?php echo (int)$roleItem['id']; ?>" <?php echo $selectedRole === (int)$roleItem['id'] ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($roleItem['name']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-group" style="margin-bottom: 1rem;">
                    <label for="password">Password</label>
                    <input id="password" name="password" type="password" required placeholder="At least 8 characters">
                    <small class="form-text">Use a strong password with letters, numbers, and symbols.</small>
                </div>

                <div class="form-group" style="margin-bottom: 1rem;">
                    <label for="password_confirm">Confirm Password</label>
                    <input id="password_confirm" name="password_confirm" type="password" required placeholder="Confirm password">
                </div>

                <div class="form-group" style="margin-bottom: 1.5rem; display: flex; align-items: center; gap: 0.5rem;">
                    <input id="is_active" name="is_active" type="checkbox" <?php echo $isActiveChecked; ?>>
                    <label for="is_active" style="margin: 0;">Activate account immediately</label>
                </div>

                <button type="submit" class="btn btn-primary btn-block">Create User</button>
            </form>
        </div>
    </div>
</div>

<?php
$content = ob_get_clean();
$flash = $flash ?? null;
include __DIR__ . '/../../layouts/admin.php';
?>