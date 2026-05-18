<?php
extract($__data ?? []);

ob_start();
?>

<div class="container-sm" style="margin: 3rem 0;">
    <div class="card">
        <div class="card-body">
            <h2 class="text-center mb-2">Welcome Back</h2>
            <p class="text-center text-muted mb-4">
                Sign in to your InfoHub account
            </p>

            <!-- Display flash messages (success/error) -->
            <?php 
            $flash = null;
            if (isset($_SESSION['flash'])) {
                $flash = $_SESSION['flash'];
                unset($_SESSION['flash']);
            }
            ?>
            <?php if ($flash): ?>
                <?php 
                $alertClass = $flash['type'] === 'error' ? 'alert-danger' : 'alert-success';
                $icon = $flash['type'] === 'error' ? '⚠' : '✓';
                ?>
                <div class="alert <?php echo $alertClass; ?>" style="margin-bottom: 1.5rem;">
                    <strong><?php echo $icon; ?></strong> <?php echo htmlspecialchars($flash['message']); ?>
                </div>
            <?php endif; ?>
            
            <form method="POST" action="<?php echo APP_URL; ?>/auth/login">
                <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($csrf_token); ?>">
                
                <div class="form-group">
                    <label for="email">Email Address</label>
                    <input 
                        type="email" 
                        id="email" 
                        name="email" 
                        required 
                        placeholder="your@email.com"
                        value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>"
                    >
                </div>
                
                <div class="form-group">
                    <label for="password">Password</label>
                    <input 
                        type="password" 
                        id="password" 
                        name="password" 
                        required 
                        placeholder="Enter your password"
                    >
                </div>
                
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
                    <label style="display: flex; align-items: center; gap: 0.5rem; font-weight: 400; margin: 0;">
                        <input type="checkbox" name="remember"> Remember me
                    </label>
                    <a href="<?php echo APP_URL; ?>/auth/forgot-password" style="font-size: 0.9rem; color: var(--primary); text-decoration: none;">
                        Forgot password?
                    </a>
                </div>
                
                <button type="submit" class="btn btn-primary btn-lg btn-block">
                    Sign In
                </button>
            </form>

            <!-- Google OAuth divider -->
            <div style="display: flex; align-items: center; margin: 2rem 0; gap: 1rem;">
                <div style="flex: 1; height: 1px; background: #ddd;"></div>
                <span style="color: #999; font-size: 0.9rem;">Or continue with</span>
                <div style="flex: 1; height: 1px; background: #ddd;"></div>
            </div>

            <!-- Google OAuth button -->
            <a href="<?php echo APP_URL; ?>/auth/google/login" class="btn btn-block" style="
                background: #fff;
                border: 1px solid #ddd;
                color: #333;
                display: flex;
                align-items: center;
                justify-content: center;
                gap: 0.75rem;
                padding: 0.75rem 1rem;
                font-weight: 500;
                border-radius: 0.375rem;
                text-decoration: none;
                transition: all 0.3s ease;
            " onmouseover="this.style.background='#f8f9fa'; this.style.borderColor='#999';" onmouseout="this.style.background='#fff'; this.style.borderColor='#ddd';">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" fill="#4285F4"/>
                    <path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"/>
                    <path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z" fill="#FBBC05"/>
                    <path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335"/>
                </svg>
                <span>Sign in with Google</span>
            </a>
            
            <p class="text-center mt-4 text-muted" style="margin-bottom: 0;">
                Don't have an account? 
                <a href="<?php echo APP_URL; ?>/auth/register" style="font-weight: 600; color: var(--primary);">
                    Sign up now
                </a>
            </p>
        </div>
    </div>
</div>

<?php
$content = ob_get_clean();
include __DIR__ . '/../layouts/auth.php';
?>
