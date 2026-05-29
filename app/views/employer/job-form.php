<?php
extract($data ?? []);
ob_start();
?>
<div class="container" style="padding: 40px 20px; max-width: 900px; margin: 0 auto;">
    <h1><?php echo isset($job) ? 'Edit Job' : 'Post a New Job'; ?></h1>
    <p>Share your vacancy with qualified applicants across InfoHub.</p>

    <?php if (!empty($flash)): ?>
        <div class="alert alert-<?php echo htmlspecialchars($flash['type']); ?>" style="margin-bottom: 1.5rem;">
            <?php echo htmlspecialchars($flash['message']); ?>
        </div>
    <?php endif; ?>

    <form action="<?php echo htmlspecialchars($formAction); ?>" method="POST">
        <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($this->generateCSRFToken()); ?>">

        <div class="form-group">
            <label for="title">Job Title</label>
            <input id="title" name="title" type="text" value="<?php echo htmlspecialchars($job['title'] ?? ''); ?>" required>
        </div>

        <div class="form-group">
            <label for="description">Job Description</label>
            <textarea id="description" name="description" rows="7" required><?php echo htmlspecialchars($job['description'] ?? ''); ?></textarea>
        </div>

        <div class="form-row" style="display:flex; gap:1rem; flex-wrap:wrap;">
            <div class="form-group" style="flex:1; min-width:220px;">
                <label for="category_id">Category</label>
                <select id="category_id" name="category_id" required>
                    <option value="">Choose category</option>
                    <?php foreach ($categories as $category): ?>
                        <option value="<?php echo intval($category['id']); ?>" <?php echo isset($job['category_id']) && $job['category_id'] == $category['id'] ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($category['name']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group" style="flex:1; min-width:220px;">
                <label for="location">Location</label>
                <input id="location" name="location" type="text" value="<?php echo htmlspecialchars($job['location'] ?? ''); ?>" required>
            </div>

            <div class="form-group" style="flex:1; min-width:220px;">
                <label for="job_type">Job Type</label>
                <select id="job_type" name="job_type" required>
                    <option value="">Select type</option>
                    <?php foreach (['full-time','part-time','contract','temporary','internship'] as $type): ?>
                        <option value="<?php echo $type; ?>" <?php echo isset($job['job_type']) && $job['job_type'] === $type ? 'selected' : ''; ?>><?php echo ucfirst(str_replace('-', ' ', $type)); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>

        <div class="form-row" style="display:flex; gap:1rem; flex-wrap:wrap;">
            <div class="form-group" style="flex:1; min-width:220px;">
                <label for="experience_level">Experience Level</label>
                <select id="experience_level" name="experience_level" required>
                    <?php foreach (['entry', 'mid', 'senior', 'manager'] as $level): ?>
                        <option value="<?php echo $level; ?>" <?php echo isset($job['experience_level']) && $job['experience_level'] === $level ? 'selected' : ''; ?>><?php echo ucfirst($level); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group" style="flex:1; min-width:220px;">
                <label for="deadline">Application Deadline</label>
                <input id="deadline" name="deadline" type="date" value="<?php echo htmlspecialchars($job['deadline'] ?? ''); ?>" required>
            </div>
        </div>

        <div class="form-group">
            <label for="seo_meta_title">SEO Title</label>
            <input id="seo_meta_title" name="seo_meta_title" type="text" value="<?php echo htmlspecialchars($job['seo_meta_title'] ?? ''); ?>">
        </div>

        <div class="form-group">
            <label for="seo_meta_description">SEO Description</label>
            <textarea id="seo_meta_description" name="seo_meta_description" rows="3"><?php echo htmlspecialchars($job['seo_meta_description'] ?? ''); ?></textarea>
        </div>

        <button type="submit" class="btn btn-primary"><?php echo isset($job) ? 'Update Job' : 'Create Job'; ?></button>
    </form>
</div>

<?php
$content = ob_get_clean();
include __DIR__ . '/../layouts/main.php';
