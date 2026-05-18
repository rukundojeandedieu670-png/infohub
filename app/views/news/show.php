<?php
/**
 * News Article Detail View
 */
ob_start();
?>

<div class="detail-container">
    <div class="detail-header">
        <div class="detail-meta">
            <span>📁 <?php echo htmlspecialchars($post['category_name'] ?? 'General'); ?></span>
            <span>📅 <?php echo date('F d, Y', strtotime($post['published_at'])); ?></span>
            <span>👁️ <?php echo intval($post['views_count']); ?> views</span>
        </div>
        <h1><?php echo htmlspecialchars($post['title']); ?></h1>
        <p class="text-muted">By <?php echo htmlspecialchars($post['first_name'] . ' ' . $post['last_name']); ?></p>
    </div>

    <div class="detail-body">
        <?php if (!empty($post['featured_image'])): ?>
            <img src="<?php echo htmlspecialchars($post['featured_image']); ?>" alt="<?php echo htmlspecialchars($post['title']); ?>">
        <?php else: ?>
            <div style="width: 100%; height: 400px; background: linear-gradient(135deg, #16a34a, #2563eb); border-radius: var(--radius-md); display: flex; align-items: center; justify-content: center; color: white; font-size: 4rem; margin-bottom: 1.5rem;">
                📰
            </div>
        <?php endif; ?>
        
        <div>
            <?php echo $post['content']; ?>
        </div>

        <?php if (!empty($post['tags'])): ?>
            <div class="tags mt-4">
                <?php foreach (explode(',', $post['tags']) as $tag): ?>
                    <span class="tag"><?php echo htmlspecialchars(trim($tag)); ?></span>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>

    <section class="related-articles">
        <h2>Related Articles</h2>
        <div class="related-articles-grid">
            <p class="text-muted">More articles coming soon</p>
        </div>
    </section>
</div>


<?php
$content = ob_get_clean();
require_once ROOT_PATH . '/app/views/layouts/main.php';
?>
