<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="<?php echo APP_URL; ?>/public/favicons/favicon.svg" type="image/svg+xml">
    <link rel="shortcut icon" href="<?php echo APP_URL; ?>/public/favicons/favicon.svg">
    <title><?php echo htmlspecialchars($page_title ?? 'Admin | InfoHub'); ?></title>
    <link rel="stylesheet" href="<?php echo APP_URL; ?>/public/assets/css/style.css">
    <link rel="stylesheet" href="<?php echo APP_URL; ?>/public/assets/css/admin.css">
</head>
<body>
    <!-- Admin Navbar -->
    <div class="admin-navbar">
        <h1 style="margin: 0; font-size: 1.25rem;">
            🔐 InfoHub Admin
        </h1>
        <div style="display: flex; align-items: center; gap: 2rem;">
            <span><?php echo htmlspecialchars($user['first_name'] . ' ' . $user['last_name']); ?></span>
            <a href="<?php echo APP_URL; ?>/auth/logout">Logout</a>
        </div>
    </div>

    <div style="display: flex; min-height: 100vh;">
        <!-- Sidebar -->
        <aside class="admin-sidebar" style="width: 250px;">
            <a href="<?php echo APP_URL; ?>/admin/dashboard" class="admin-sidebar-item">
                Dashboard
            </a>
            <a href="<?php echo APP_URL; ?>/admin/users" class="admin-sidebar-item">
                Users
            </a>
            <a href="<?php echo APP_URL; ?>/admin/news" class="admin-sidebar-item">
                News & Posts
            </a>
            <a href="<?php echo APP_URL; ?>/admin/businesses" class="admin-sidebar-item">
                Businesses
            </a>
            <a href="<?php echo APP_URL; ?>/admin/logs" class="admin-sidebar-item">
                System Logs
            </a>
            <a href="<?php echo APP_URL; ?>" class="admin-sidebar-item" style="margin-top: 2rem; border-top: 1px solid var(--border); padding-top: 2rem;">
                ← Back to Site
            </a>
        </aside>

        <!-- Main Content -->
        <main style="flex: 1; padding: 2rem;">
            <div class="container-lg">
                <?php if (isset($flash) && $flash): ?>
                    <div class="alert alert-<?php echo htmlspecialchars($flash['type']); ?>" style="margin-bottom: 2rem;">
                        <?php echo htmlspecialchars($flash['message']); ?>
                    </div>
                <?php endif; ?>

                <?php echo $content ?? ''; ?>
            </div>
        </main>
    </div>

    <script src="<?php echo APP_URL; ?>/public/assets/js/main.js"></script>
</body>
</html>
