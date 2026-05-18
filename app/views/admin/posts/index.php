<?php
// Admin posts management view - List all posts
$title = "Manage Posts";
$description = "Create, edit, and manage all news posts";
include ROOT_PATH . '/app/views/layouts/admin.php';
?>

<div style="padding: 30px;">
    <!-- Header -->
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px;">
        <div>
            <h1 style="margin: 0 0 5px 0; color: #1f2937;">Manage Posts</h1>
            <p style="margin: 0; color: #6b7280; font-size: 0.95rem;">
                Create, edit, and manage all news articles
            </p>
        </div>
        <a href="<?php echo APP_URL; ?>/admin/posts/create" class="btn btn-primary" style="padding: 12px 24px; font-size: 1rem;">
            ➕ Create New Post
        </a>
    </div>

    <!-- Messages -->
    <?php if (!empty($_SESSION['success'])): ?>
        <div class="alert alert-success" style="margin-bottom: 20px; padding: 15px; background: #d1fae5; color: #065f46; border-radius: 8px; display: flex; justify-content: space-between;">
            <span>✓ <?php echo htmlspecialchars($_SESSION['success']); ?></span>
            <button onclick="this.parentElement.style.display='none';" style="background: none; border: none; color: inherit; cursor: pointer;">×</button>
        </div>
        <?php unset($_SESSION['success']); ?>
    <?php endif; ?>

    <!-- Filters -->
    <div style="background: white; padding: 20px; border-radius: 12px; margin-bottom: 20px; border: 1px solid #e5e7eb;">
        <div style="display: grid; grid-template-columns: 1fr 1fr 1fr auto; gap: 15px; align-items: end;">
            <!-- Status Filter -->
            <div>
                <label style="display: block; margin-bottom: 8px; color: #374151; font-weight: 600; font-size: 0.9rem;">
                    Status
                </label>
                <select id="statusFilter" onchange="filterPosts()" style="width: 100%; padding: 8px 12px; border: 1px solid #d1d5db; border-radius: 6px; font-size: 0.9rem;">
                    <option value="">All Statuses</option>
                    <option value="draft">Draft</option>
                    <option value="published">Published</option>
                    <option value="archived">Archived</option>
                </select>
            </div>

            <!-- Category Filter -->
            <div>
                <label style="display: block; margin-bottom: 8px; color: #374151; font-weight: 600; font-size: 0.9rem;">
                    Category
                </label>
                <select id="categoryFilter" onchange="filterPosts()" style="width: 100%; padding: 8px 12px; border: 1px solid #d1d5db; border-radius: 6px; font-size: 0.9rem;">
                    <option value="">All Categories</option>
                    <?php foreach ($categories as $cat): ?>
                        <option value="<?php echo htmlspecialchars($cat['id']); ?>">
                            <?php echo htmlspecialchars($cat['name']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- Search -->
            <div>
                <label style="display: block; margin-bottom: 8px; color: #374151; font-weight: 600; font-size: 0.9rem;">
                    Search
                </label>
                <input type="text" id="searchInput" onkeyup="filterPosts()" placeholder="Search posts..." style="width: 100%; padding: 8px 12px; border: 1px solid #d1d5db; border-radius: 6px; font-size: 0.9rem;">
            </div>

            <!-- Reset -->
            <button onclick="resetFilters()" class="btn btn-secondary" style="padding: 8px 16px; font-size: 0.9rem;">
                🔄 Reset
            </button>
        </div>
    </div>

    <!-- Posts Table -->
    <div style="background: white; border-radius: 12px; border: 1px solid #e5e7eb; overflow: hidden;">
        <table style="width: 100%; border-collapse: collapse;">
            <thead>
                <tr style="background: #f8fafc; border-bottom: 1px solid #e5e7eb;">
                    <th style="padding: 15px; text-align: left; color: #374151; font-weight: 600; font-size: 0.9rem;">
                        TITLE
                    </th>
                    <th style="padding: 15px; text-align: left; color: #374151; font-weight: 600; font-size: 0.9rem;">
                        AUTHOR
                    </th>
                    <th style="padding: 15px; text-align: center; color: #374151; font-weight: 600; font-size: 0.9rem;">
                        STATUS
                    </th>
                    <th style="padding: 15px; text-align: center; color: #374151; font-weight: 600; font-size: 0.9rem;">
                        PUBLISHED
                    </th>
                    <th style="padding: 15px; text-align: center; color: #374151; font-weight: 600; font-size: 0.9rem;">
                        FEATURED
                    </th>
                    <th style="padding: 15px; text-align: right; color: #374151; font-weight: 600; font-size: 0.9rem;">
                        ACTIONS
                    </th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($posts)): ?>
                    <tr>
                        <td colspan="6" style="padding: 40px; text-align: center; color: #9ca3af;">
                            📭 No posts found
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($posts as $post): ?>
                        <tr style="border-bottom: 1px solid #e5e7eb;" class="post-row" data-status="<?php echo htmlspecialchars($post['status']); ?>" data-category="<?php echo htmlspecialchars($post['category_id']); ?>" data-title="<?php echo htmlspecialchars(strtolower($post['title'])); ?>">
                            <td style="padding: 15px;">
                                <div>
                                    <a href="<?php echo APP_URL; ?>/news/<?php echo htmlspecialchars($post['slug']); ?>" style="color: #16a34a; text-decoration: none; font-weight: 600; display: block; margin-bottom: 3px;">
                                        <?php echo htmlspecialchars(strlen($post['title']) > 50 ? substr($post['title'], 0, 50) . '...' : $post['title']); ?>
                                    </a>
                                    <small style="color: #6b7280; font-size: 0.85rem;">
                                        Views: <?php echo $post['view_count'] ?? 0; ?>
                                    </small>
                                </div>
                            </td>
                            <td style="padding: 15px;">
                                <span style="color: #6b7280;">
                                    <?php echo htmlspecialchars($post['author_name'] ?? 'Unknown'); ?>
                                </span>
                            </td>
                            <td style="padding: 15px; text-align: center;">
                                <span style="display: inline-block; padding: 4px 12px; border-radius: 999px; font-size: 0.8rem; font-weight: 600; background: <?php 
                                    echo $post['status'] == 'published' ? '#d1fae5' : 
                                         ($post['status'] == 'draft' ? '#fef08a' : '#fee2e2');
                                ?>; color: <?php 
                                    echo $post['status'] == 'published' ? '#065f46' : 
                                         ($post['status'] == 'draft' ? '#854d0e' : '#991b1b');
                                ?>;">
                                    <?php echo ucfirst(htmlspecialchars($post['status'])); ?>
                                </span>
                            </td>
                            <td style="padding: 15px; text-align: center; color: #6b7280; font-size: 0.9rem;">
                                <?php echo date('M j, Y', strtotime($post['published_at'])); ?>
                            </td>
                            <td style="padding: 15px; text-align: center;">
                                <?php if ($post['featured']): ?>
                                    <span style="color: #f59e0b; font-weight: 600;">⭐ Featured</span>
                                <?php else: ?>
                                    <span style="color: #d1d5db;">☆</span>
                                <?php endif; ?>
                            </td>
                            <td style="padding: 15px; text-align: right;">
                                <div style="display: flex; gap: 8px; justify-content: flex-end;">
                                    <a 
                                        href="<?php echo APP_URL; ?>/admin/posts/<?php echo htmlspecialchars($post['id']); ?>/edit" 
                                        class="btn btn-secondary"
                                        style="padding: 6px 12px; font-size: 0.85rem; text-decoration: none; background: #2563eb; color: white; border-radius: 6px;"
                                    >
                                        ✏️ Edit
                                    </a>
                                    <form method="POST" action="<?php echo APP_URL; ?>/admin/posts/<?php echo htmlspecialchars($post['id']); ?>/delete" style="display: inline; margin: 0;">
                                        <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token'] ?? ''; ?>">
                                        <button 
                                            type="submit" 
                                            class="btn"
                                            style="padding: 6px 12px; font-size: 0.85rem; background: #ef4444; color: white; border: none; border-radius: 6px; cursor: pointer;"
                                            onclick="return confirm('Delete this post? This cannot be undone.');"
                                        >
                                            🗑️ Delete
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <?php if ($total_pages > 1): ?>
        <div style="margin-top: 20px; display: flex; justify-content: center; gap: 10px;">
            <?php if ($current_page > 1): ?>
                <a href="?page=<?php echo $current_page - 1; ?>" class="btn btn-secondary" style="padding: 8px 16px;">← Previous</a>
            <?php endif; ?>

            <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                <a 
                    href="?page=<?php echo $i; ?>" 
                    class="btn"
                    style="padding: 8px 16px; background: <?php echo $i == $current_page ? '#16a34a' : '#f8fafc'; ?>; color: <?php echo $i == $current_page ? 'white' : '#374151'; ?>; text-decoration: none; border: 1px solid #e5e7eb; border-radius: 6px;"
                >
                    <?php echo $i; ?>
                </a>
            <?php endfor; ?>

            <?php if ($current_page < $total_pages): ?>
                <a href="?page=<?php echo $current_page + 1; ?>" class="btn btn-secondary" style="padding: 8px 16px;">Next →</a>
            <?php endif; ?>
        </div>
    <?php endif; ?>

    <!-- Stats -->
    <div style="margin-top: 30px; display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 15px;">
        <div style="background: white; padding: 20px; border-radius: 8px; border: 1px solid #e5e7eb;">
            <p style="margin: 0 0 8px 0; color: #6b7280; font-weight: 600;">Total Posts</p>
            <p style="margin: 0; font-size: 1.75rem; font-weight: 700; color: #16a34a;">
                <?php echo $total_posts; ?>
            </p>
        </div>
        <div style="background: white; padding: 20px; border-radius: 8px; border: 1px solid #e5e7eb;">
            <p style="margin: 0 0 8px 0; color: #6b7280; font-weight: 600;">Published</p>
            <p style="margin: 0; font-size: 1.75rem; font-weight: 700; color: #10b981;">
                <?php echo $published_count; ?>
            </p>
        </div>
        <div style="background: white; padding: 20px; border-radius: 8px; border: 1px solid #e5e7eb;">
            <p style="margin: 0 0 8px 0; color: #6b7280; font-weight: 600;">Drafts</p>
            <p style="margin: 0; font-size: 1.75rem; font-weight: 700; color: #f59e0b;">
                <?php echo $draft_count; ?>
            </p>
        </div>
        <div style="background: white; padding: 20px; border-radius: 8px; border: 1px solid #e5e7eb;">
            <p style="margin: 0 0 8px 0; color: #6b7280; font-weight: 600;">Featured</p>
            <p style="margin: 0; font-size: 1.75rem; font-weight: 700; color: #2563eb;">
                <?php echo $featured_count; ?>
            </p>
        </div>
    </div>
</div>

<script>
    function filterPosts() {
        const statusFilter = document.getElementById('statusFilter').value;
        const categoryFilter = document.getElementById('categoryFilter').value;
        const searchInput = document.getElementById('searchInput').value.toLowerCase();

        const rows = document.querySelectorAll('.post-row');
        rows.forEach(row => {
            const status = row.getAttribute('data-status');
            const category = row.getAttribute('data-category');
            const title = row.getAttribute('data-title');

            const statusMatch = !statusFilter || status === statusFilter;
            const categoryMatch = !categoryFilter || category === categoryFilter;
            const titleMatch = title.includes(searchInput);

            row.style.display = (statusMatch && categoryMatch && titleMatch) ? '' : 'none';
        });
    }

    function resetFilters() {
        document.getElementById('statusFilter').value = '';
        document.getElementById('categoryFilter').value = '';
        document.getElementById('searchInput').value = '';
        filterPosts();
    }

    // Auto-hide alerts
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(alert => {
        setTimeout(() => {
            alert.style.opacity = '0';
            alert.style.transition = 'opacity 0.3s';
            setTimeout(() => alert.remove(), 300);
        }, 5000);
    });

    // Responsive table
    if (window.innerWidth < 768) {
        // Hide certain columns on mobile
        document.querySelectorAll('td:nth-child(4), th:nth-child(4), td:nth-child(5), th:nth-child(5)').forEach(el => {
            el.style.display = 'none';
        });
    }
</script>

<style>
    @media (max-width: 768px) {
        div[style*="grid-template-columns: 1fr 1fr 1fr"] {
            grid-template-columns: 1fr 1fr !important;
        }

        table {
            font-size: 0.9rem !important;
        }

        table td, table th {
            padding: 10px !important;
        }
    }
</style>
