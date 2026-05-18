<?php
extract($__data ?? []);

ob_start();
?>

<div style="min-height: 100vh; display: flex; align-items: center; justify-content: center; padding: 2rem;">
    <div class="container-sm">
        <div class="card">
            <div class="card-body">
                <h2 style="text-align: center; margin-bottom: 0.5rem;">Reset Your Password</h2>
                <p style="text-align: center; margin-bottom: 2rem; color: var(--text-secondary);">
                    Enter your email to receive password reset instructions
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
                
                <form method="POST" action="<?php echo APP_URL; ?>/auth/forgot-password">
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
                    
                    <button type="submit" class="btn btn-primary btn-lg btn-block">
                        Send Reset Link
                    </button>
                </form>
                
                <p style="text-align: center; margin-top: 2rem;">
                    <a href="<?php echo APP_URL; ?>/auth/login" style="color: var(--text-secondary);">
                        Back to login
                    </a>
                </p>
            </div>
        </div>
    </div>
</div>

<?php
$content = ob_get_clean();
include __DIR__ . '/../layouts/auth.php';
?>
