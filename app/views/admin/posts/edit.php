<?php
// Admin post edit/create form
$title = isset($post) ? "Edit Post" : "Create New Post";
$description = isset($post) ? "Edit this news article" : "Create a new news article";
include ROOT_PATH . '/app/views/layouts/admin.php';
?>

<div style="padding: 30px; max-width: 1200px; margin: 0 auto;">
    <!-- Header -->
    <div style="margin-bottom: 30px;">
        <h1 style="margin: 0 0 5px 0; color: #1f2937;">
            <?php echo isset($post) ? "Edit Post" : "Create New Post"; ?>
        </h1>
        <p style="margin: 0; color: #6b7280; font-size: 0.95rem;">
            <?php echo isset($post) ? "Update article information" : "Publish a new news article"; ?>
        </p>
    </div>

    <!-- Messages -->
    <?php if (!empty($_SESSION['error'])): ?>
        <div class="alert alert-error" style="margin-bottom: 20px; padding: 15px; background: #fee2e2; color: #991b1b; border-radius: 8px;">
            ⚠️ <?php echo htmlspecialchars($_SESSION['error']); ?>
        </div>
        <?php unset($_SESSION['error']); ?>
    <?php endif; ?>

    <div style="display: grid; grid-template-columns: 1fr 350px; gap: 30px;">
        <!-- Main Form -->
        <form method="POST" action="<?php echo isset($post) ? APP_URL . '/admin/posts/' . htmlspecialchars($post['id']) . '/update' : APP_URL . '/admin/posts/store'; ?>" enctype="multipart/form-data">
            <!-- CSRF Token -->
            <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token'] ?? ''; ?>">

            <!-- Title -->
            <div class="card" style="padding: 25px; margin-bottom: 20px;">
                <label style="display: block; margin-bottom: 8px; color: #374151; font-weight: 600;">
                    Article Title
                </label>
                <input 
                    type="text" 
                    name="title" 
                    value="<?php echo isset($post) ? htmlspecialchars($post['title']) : ''; ?>"
                    placeholder="Enter article title..."
                    required
                    onkeyup="generateSlug()"
                    style="width: 100%; padding: 12px; border: 1px solid #d1d5db; border-radius: 8px; font-size: 1rem; box-sizing: border-box; margin-bottom: 5px;"
                >
                <small style="color: #6b7280;">
                    SEO-friendly title (recommended 60 characters max)
                </small>
            </div>

            <!-- Slug -->
            <div class="card" style="padding: 25px; margin-bottom: 20px;">
                <label style="display: block; margin-bottom: 8px; color: #374151; font-weight: 600;">
                    URL Slug
                </label>
                <div style="display: flex; gap: 10px;">
                    <input 
                        type="text" 
                        name="slug" 
                        id="slug"
                        value="<?php echo isset($post) ? htmlspecialchars($post['slug']) : ''; ?>"
                        placeholder="article-title-slug"
                        required
                        style="flex: 1; padding: 12px; border: 1px solid #d1d5db; border-radius: 8px; font-size: 1rem; box-sizing: border-box;"
                    >
                    <button type="button" onclick="generateSlug()" style="padding: 12px 20px; background: #2563eb; color: white; border: none; border-radius: 8px; cursor: pointer; font-weight: 600;">
                        🔄 Generate
                    </button>
                </div>
                <small style="color: #6b7280; display: block; margin-top: 5px;">
                    Used in URL: /news/<strong id="slugPreview"></strong>
                </small>
            </div>

            <!-- Excerpt -->
            <div class="card" style="padding: 25px; margin-bottom: 20px;">
                <label style="display: block; margin-bottom: 8px; color: #374151; font-weight: 600;">
                    Excerpt (Preview)
                </label>
                <textarea 
                    name="excerpt" 
                    placeholder="Short summary shown in lists..."
                    rows="3"
                    maxlength="200"
                    style="width: 100%; padding: 12px; border: 1px solid #d1d5db; border-radius: 8px; font-size: 1rem; box-sizing: border-box; font-family: inherit;"
                ><?php echo isset($post) ? htmlspecialchars($post['excerpt']) : ''; ?></textarea>
                <small style="color: #6b7280; display: block; margin-top: 5px;">
                    <span id="charCount">0</span>/200 characters
                </small>
            </div>

            <!-- Content -->
            <div class="card" style="padding: 25px; margin-bottom: 20px;">
                <label style="display: block; margin-bottom: 8px; color: #374151; font-weight: 600;">
                    Article Content
                </label>
                <textarea 
                    name="content" 
                    id="content"
                    placeholder="Write your article content here... You can use HTML tags."
                    rows="12"
                    required
                    style="width: 100%; padding: 12px; border: 1px solid #d1d5db; border-radius: 8px; font-size: 1rem; box-sizing: border-box; font-family: monospace;"
                ><?php echo isset($post) ? htmlspecialchars($post['content']) : ''; ?></textarea>
                <small style="color: #6b7280; display: block; margin-top: 8px;">
                    ✓ You can use <strong>HTML</strong> formatting<br>
                    ✓ Line breaks are preserved<br>
                    ✓ No script tags allowed for security
                </small>
            </div>

            <!-- Featured Image -->
            <div class="card" style="padding: 25px; margin-bottom: 20px;">
                <label style="display: block; margin-bottom: 8px; color: #374151; font-weight: 600;">
                    Featured Image
                </label>
                
                <?php if (isset($post) && !empty($post['featured_image'])): ?>
                    <div style="margin-bottom: 15px;">
                        <img src="<?php echo htmlspecialchars($post['featured_image']); ?>" alt="Current image" style="max-width: 100%; height: 200px; object-fit: cover; border-radius: 8px; border: 1px solid #e5e7eb;">
                        <p style="margin: 8px 0 0 0; color: #6b7280; font-size: 0.9rem;">
                            Current image - Upload a new one to replace
                        </p>
                    </div>
                <?php endif; ?>

                <div style="position: relative; border: 2px dashed #d1d5db; border-radius: 8px; padding: 30px; text-align: center; background: #f8fafc; cursor: pointer;" 
                     id="imageDropZone"
                     onmouseover="this.style.borderColor='#16a34a';"
                     onmouseout="this.style.borderColor='#d1d5db';">
                    <input type="file" name="featured_image" id="imageInput" accept="image/*" style="display: none;">
                    <div style="color: #6b7280;">
                        <div style="font-size: 2rem; margin-bottom: 10px;">🖼️</div>
                        <p style="margin: 0 0 5px 0; font-weight: 600;">
                            Drop image here or click to browse
                        </p>
                        <p style="margin: 0; font-size: 0.9rem;">
                            JPG, PNG (Max 5MB)
                        </p>
                    </div>
                </div>
            </div>

            <!-- Buttons -->
            <div style="display: flex; gap: 10px; margin-bottom: 20px;">
                <button 
                    type="submit" 
                    name="status" 
                    value="draft"
                    class="btn btn-secondary"
                    style="flex: 1; padding: 12px; font-size: 1rem; background: #f59e0b;"
                >
                    📋 Save as Draft
                </button>
                <button 
                    type="submit" 
                    name="status" 
                    value="published"
                    class="btn btn-primary"
                    style="flex: 1; padding: 12px; font-size: 1rem;"
                >
                    📤 Publish Now
                </button>
            </div>
        </form>

        <!-- Sidebar -->
        <div>
            <!-- Category -->
            <div class="card" style="padding: 20px; margin-bottom: 20px;">
                <h3 style="margin: 0 0 15px 0; color: #1f2937; font-size: 1rem;">Category</h3>
                <select id="category" name="category_id" form="postForm" style="width: 100%; padding: 10px; border: 1px solid #d1d5db; border-radius: 6px;">
                    <option value="">Select category...</option>
                    <?php foreach ($categories as $cat): ?>
                        <option value="<?php echo htmlspecialchars($cat['id']); ?>" 
                            <?php echo (isset($post) && $post['category_id'] == $cat['id']) ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($cat['name']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- Featured -->
            <div class="card" style="padding: 20px; margin-bottom: 20px;">
                <h3 style="margin: 0 0 15px 0; color: #1f2937; font-size: 1rem;">Featured</h3>
                <label style="display: flex; align-items: center; gap: 10px; cursor: pointer;">
                    <input 
                        type="checkbox" 
                        name="featured" 
                        value="1"
                        <?php echo (isset($post) && $post['featured']) ? 'checked' : ''; ?>
                    >
                    <span style="color: #6b7280;">Show on homepage</span>
                </label>
                <small style="color: #9ca3af; display: block; margin-top: 8px;">
                    ⭐ Featured posts appear in the homepage banner
                </small>
            </div>

            <!-- Meta Info -->
            <div class="card" style="padding: 20px; margin-bottom: 20px;">
                <h3 style="margin: 0 0 15px 0; color: #1f2937; font-size: 1rem;">Info</h3>
                <div style="display: flex; flex-direction: column; gap: 10px; font-size: 0.9rem; color: #6b7280;">
                    <?php if (isset($post)): ?>
                        <div>
                            <p style="margin: 0 0 3px 0; font-weight: 600;">Created</p>
                            <p style="margin: 0;"><?php echo date('M j, Y H:i', strtotime($post['created_at'])); ?></p>
                        </div>
                        <div>
                            <p style="margin: 0 0 3px 0; font-weight: 600;">Updated</p>
                            <p style="margin: 0;"><?php echo date('M j, Y H:i', strtotime($post['updated_at'])); ?></p>
                        </div>
                        <div>
                            <p style="margin: 0 0 3px 0; font-weight: 600;">Views</p>
                            <p style="margin: 0; color: #16a34a; font-weight: 600;"><?php echo $post['view_count'] ?? 0; ?></p>
                        </div>
                    <?php else: ?>
                        <p style="margin: 0; color: #9ca3af; font-style: italic;">
                            Publishing date will be set on publish
                        </p>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Preview -->
            <div class="card" style="padding: 20px;">
                <h3 style="margin: 0 0 15px 0; color: #1f2937; font-size: 1rem;">Preview</h3>
                <div id="preview" style="background: #f8fafc; padding: 15px; border-radius: 8px; border: 1px solid #e5e7eb;">
                    <h4 style="margin: 0 0 5px 0; color: #1f2937; font-size: 0.95rem;" id="previewTitle">Article Title</h4>
                    <p style="margin: 0; color: #6b7280; font-size: 0.85rem;" id="previewExcerpt">Preview text appears here...</p>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Character count for excerpt
    const excerpt = document.querySelector('textarea[name="excerpt"]');
    if (excerpt) {
        excerpt.addEventListener('keyup', function() {
            document.getElementById('charCount').textContent = this.value.length;
        });
        document.getElementById('charCount').textContent = excerpt.value.length;
    }

    // Auto-generate slug
    function generateSlug() {
        const title = document.querySelector('input[name="title"]').value;
        const slug = title
            .toLowerCase()
            .trim()
            .replace(/[^\w\s-]/g, '')
            .replace(/[\s_-]+/g, '-')
            .replace(/^-+|-+$/g, '');
        
        document.getElementById('slug').value = slug;
        document.getElementById('slugPreview').textContent = slug || 'article-slug';
        updatePreview();
    }

    // Update live preview
    function updatePreview() {
        const title = document.querySelector('input[name="title"]').value || 'Article Title';
        const excerpt = document.querySelector('textarea[name="excerpt"]').value || 'Preview text appears here...';
        
        document.getElementById('previewTitle').textContent = title;
        document.getElementById('previewExcerpt').textContent = excerpt;
    }

    document.querySelector('input[name="title"]').addEventListener('keyup', updatePreview);
    document.querySelector('textarea[name="excerpt"]').addEventListener('keyup', updatePreview);

    // Image upload handling
    const imageDropZone = document.getElementById('imageDropZone');
    const imageInput = document.getElementById('imageInput');

    imageDropZone.addEventListener('click', () => imageInput.click());

    imageDropZone.addEventListener('dragover', (e) => {
        e.preventDefault();
        imageDropZone.style.borderColor = '#16a34a';
    });

    imageDropZone.addEventListener('dragleave', () => {
        imageDropZone.style.borderColor = '#d1d5db';
    });

    imageDropZone.addEventListener('drop', (e) => {
        e.preventDefault();
        imageDropZone.style.borderColor = '#d1d5db';
        
        if (e.dataTransfer.files.length > 0) {
            imageInput.files = e.dataTransfer.files;
        }
    });

    // Initialize
    updatePreview();
</script>

<style>
    @media (max-width: 768px) {
        [style*="grid-template-columns: 1fr 350px"] {
            grid-template-columns: 1fr !important;
        }
    }
</style>
