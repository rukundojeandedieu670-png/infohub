<?php
extract($data ?? []);
ob_start();
?>
<div class="container" style="padding: 40px 20px; max-width: 1000px; margin: 0 auto;">
    <h1>Business Owner Profile</h1>
    <p>Update your business and contact information for customers and partners.</p>

    <?php if (!empty($flash)): ?>
        <div class="alert alert-<?php echo htmlspecialchars($flash['type']); ?>" style="margin-bottom: 1.5rem;">
            <?php echo htmlspecialchars($flash['message']); ?>
        </div>
    <?php endif; ?>

    <form action="<?php echo APP_URL; ?>/business-owner/profile/update" method="POST" style="display:grid; gap:1rem;">
        <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($csrf_token); ?>">

        <div class="form-group">
            <label for="company">Business Name</label>
            <input id="company" name="company" type="text" value="<?php echo htmlspecialchars($userProfile['company'] ?? $business['name'] ?? ''); ?>" required>
        </div>

        <div class="form-row" style="display:flex; gap:1rem; flex-wrap:wrap;">
            <div class="form-group" style="flex:1; min-width:220px;">
                <label for="industry">Industry</label>
                <input id="industry" name="industry" type="text" value="<?php echo htmlspecialchars($userProfile['industry'] ?? ''); ?>">
            </div>
            <div class="form-group" style="flex:1; min-width:220px;">
                <label for="location">Location</label>
                <input id="location" name="location" type="text" value="<?php echo htmlspecialchars($business['location'] ?? ''); ?>">
            </div>
        </div>

        <div class="form-row" style="display:flex; gap:1rem; flex-wrap:wrap;">
            <div class="form-group" style="flex:1; min-width:220px;">
                <label for="phone">Phone</label>
                <input id="phone" name="phone" type="text" value="<?php echo htmlspecialchars($business['phone'] ?? $userProfile['phone'] ?? ''); ?>">
            </div>
            <div class="form-group" style="flex:1; min-width:220px;">
                <label for="website">Website</label>
                <input id="website" name="website" type="url" value="<?php echo htmlspecialchars($business['website'] ?? ''); ?>">
            </div>
        </div>

        <div class="form-group">
            <label for="category_id">Business Category</label>
            <select id="category_id" name="category_id">
                <option value="">Select Category</option>
                <?php foreach ($categories as $category): ?>
                    <option value="<?php echo intval($category['id']); ?>" <?php echo isset($business['category_id']) && $business['category_id'] == $category['id'] ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($category['name']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="form-group">
            <label for="description">Business Description</label>
            <textarea id="description" name="description" rows="6"><?php echo htmlspecialchars($business['description'] ?? ''); ?></textarea>
        </div>

        <button type="submit" class="btn btn-primary">Save Business Profile</button>
    </form>
</div>

<?php
$content = ob_get_clean();
include __DIR__ . '/../layouts/main.php';
