<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="<?php echo htmlspecialchars($meta_description ?? 'InfoHub - Rwanda national digital information system'); ?>">
    <meta property="og:title" content="<?php echo htmlspecialchars($og_title ?? 'InfoHub'); ?>">
    <meta property="og:description" content="<?php echo htmlspecialchars($og_description ?? 'InfoHub - Rwanda national digital information system'); ?>">
    <meta property="og:image" content="<?php echo htmlspecialchars($og_image ?? APP_URL . '/public/assets/img/og-image.png'); ?>">
    
    <title><?php echo htmlspecialchars($page_title ?? 'InfoHub'); ?></title>
    
    <link rel="stylesheet" href="<?php echo APP_URL; ?>/public/assets/css/style.css">
    <?php if (isset($extra_css)) echo $extra_css; ?>
</head>
<body>
    <?php
        $requestPath = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $isActiveNav = function ($path) use ($requestPath) {
            return strpos($requestPath, $path) === 0 ? 'active' : '';
        };
    ?>
    <!-- Navigation -->
    <nav class="navbar">
        <div class="navbar-container">
            <div class="navbar-brand">
                <a href="<?php echo APP_URL; ?>">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="12" cy="12" r="1"></circle>
                        <path d="M12 1v6m0 6v6M4.22 4.22l4.24 4.24m5.08 5.08l4.24 4.24M1 12h6m6 0h6M4.22 19.78l4.24-4.24m5.08-5.08l4.24-4.24"></path>
                    </svg>
                    InfoHub
                </a>
            </div>
            
            <div class="navbar-menu">
                <a href="<?php echo APP_URL; ?>/news" class="<?php echo $isActiveNav('/news'); ?>">News</a>
                <a href="<?php echo APP_URL; ?>/jobs" class="<?php echo $isActiveNav('/jobs'); ?>">Jobs</a>
                <a href="<?php echo APP_URL; ?>/business" class="<?php echo $isActiveNav('/business'); ?>">Business</a>
            </div>

            <div class="navbar-auth">
                <?php if ($user): ?>
                    <a href="<?php echo APP_URL; ?>/profile" class="btn btn-outline <?php echo strpos($requestPath, '/profile') === 0 ? 'active-link' : ''; ?>">Profile</a>
                    <a href="<?php echo APP_URL; ?>/auth/logout" class="btn btn-primary">Logout</a>
                <?php else: ?>
                    <a href="<?php echo APP_URL; ?>/auth/login" class="btn btn-outline">Login</a>
                    <a href="<?php echo APP_URL; ?>/auth/register" class="btn btn-primary">Register</a>
                <?php endif; ?>
            </div>
        </div>
    </nav>

    <!-- Flash Messages -->
    <?php if (isset($flash) && $flash): ?>
        <div class="container mt-4">
            <div class="alert alert-<?php echo htmlspecialchars($flash['type']); ?>">
                <?php echo htmlspecialchars($flash['message']); ?>
            </div>
        </div>
    <?php endif; ?>

    <!-- Errors -->
    <?php if (isset($_SESSION['errors']) && !empty($_SESSION['errors'])): ?>
        <div class="container mt-4">
            <?php foreach ($_SESSION['errors'] as $error): ?>
                <div class="alert alert-danger">
                    <?php echo htmlspecialchars($error); ?>
                </div>
            <?php endforeach; ?>
        </div>
        <?php unset($_SESSION['errors']); ?>
    <?php endif; ?>

    <!-- Main Content -->
    <main>
        <?php echo $content ?? ''; ?>
    </main>

    <!-- Footer -->
    <footer>
        <div class="footer-content">
            <div class="footer-section">
                <h4>About InfoHub</h4>
                <p>Rwanda's national digital information system connecting people with news, jobs, and businesses.</p>
            </div>
            
            <div class="footer-section">
                <h4>Quick Links</h4>
                <ul>
                    <li><a href="<?php echo APP_URL; ?>/news">News</a></li>
                    <li><a href="<?php echo APP_URL; ?>/jobs">Jobs</a></li>
                    <li><a href="<?php echo APP_URL; ?>/business">Businesses</a></li>
                    <li><a href="<?php echo APP_URL; ?>">Home</a></li>
                </ul>
            </div>
            
            <div class="footer-section">
                <h4>Support</h4>
                <ul>
                    <li><a href="#">Help Center</a></li>
                    <li><a href="#">Contact Us</a></li>
                    <li><a href="#">Privacy Policy</a></li>
                    <li><a href="#">Terms of Service</a></li>
                </ul>
            </div>
            
            <div class="footer-section">
                <h4>Follow Us</h4>
                <ul>
                    <li><a href="#">Facebook</a></li>
                    <li><a href="#">Twitter</a></li>
                    <li><a href="#">LinkedIn</a></li>
                    <li><a href="#">Instagram</a></li>
                </ul>
            </div>
        </div>
        
        <div class="footer-bottom">
            <p>&copy; 2024 InfoHub. All rights reserved. Made with ❤️ for Rwanda.</p>
        </div>
    </footer>

    <script src="<?php echo APP_URL; ?>/public/assets/js/main.js"></script>
    <?php if (isset($extra_js)) echo $extra_js; ?>
</body>
</html>
