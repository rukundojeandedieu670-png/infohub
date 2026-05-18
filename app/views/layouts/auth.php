<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($page_title ?? 'InfoHub'); ?></title>
    <link rel="stylesheet" href="<?php echo APP_URL; ?>/public/assets/css/style.css">
</head>
<body style="background-color: var(--bg-light); display: flex; flex-direction: column; min-height: 100vh;">
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
                <a href="<?php echo APP_URL; ?>/news">News</a>
                <a href="<?php echo APP_URL; ?>/jobs">Jobs</a>
                <a href="<?php echo APP_URL; ?>/business">Business</a>
            </div>

            <div class="navbar-auth">
                <a href="<?php echo APP_URL; ?>/auth/login" class="btn btn-outline">Login</a>
                <a href="<?php echo APP_URL; ?>/auth/register" class="btn btn-primary">Register</a>
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

    <!-- Content -->
    <main style="flex: 1; display: flex; align-items: center; justify-content: center; padding: 2rem 1rem;">
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

    <style>
        .navbar {
            background: white;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            padding: 1rem 0;
            position: sticky;
            top: 0;
            z-index: 100;
        }

        .navbar-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 1rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .navbar-brand a {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            color: #16a34a;
            text-decoration: none;
            font-weight: 700;
            font-size: 1.3rem;
        }

        .navbar-menu {
            display: flex;
            gap: 2rem;
        }

        .navbar-menu a {
            color: #333;
            text-decoration: none;
            transition: color 0.3s;
        }

        .navbar-menu a:hover {
            color: #16a34a;
        }

        .navbar-auth {
            display: flex;
            gap: 1rem;
        }

        .btn {
            padding: 0.75rem 1.5rem;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s;
            border: none;
            cursor: pointer;
            display: inline-block;
        }

        .btn-primary {
            background: #16a34a;
            color: white;
        }

        .btn-primary:hover {
            background: #15803d;
        }

        .btn-outline {
            border: 2px solid #16a34a;
            color: #16a34a;
            background: transparent;
        }

        .btn-outline:hover {
            background: #f0f0f0;
        }

        @media (max-width: 768px) {
            .navbar-container {
                flex-direction: column;
                gap: 1rem;
            }

            .navbar-menu {
                gap: 1rem;
                font-size: 0.9rem;
            }

            .navbar-auth {
                width: 100%;
                justify-content: space-between;
            }

            .btn {
                padding: 0.6rem 1rem;
                font-size: 0.9rem;
            }
        }
    </style>

    <script src="<?php echo APP_URL; ?>/public/assets/js/main.js"></script>
