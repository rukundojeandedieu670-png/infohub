<?php
/**
 * Authentication System Verification
 * Check all components are in place and working
 */

define('ROOT_PATH', __DIR__);
require_once ROOT_PATH . '/config/database.php';
require_once ROOT_PATH . '/core/Database.php';

echo "=== InfoHub Authentication System Verification ===\n\n";

// 1. Check database tables
echo "1. Checking database tables...\n";
try {
    $db = Database::getInstance();
    $dbName = DB_NAME;
    
    // Check users table exists
    $result = $db->prepare("SELECT COUNT(*) as count FROM information_schema.TABLES WHERE TABLE_SCHEMA = ? AND TABLE_NAME = 'users'")
        ->bind('s', $dbName)
        ->execute()
        ->single();
    
    if ($result && $result['count'] > 0) {
        echo "   ✓ users table exists\n";
    } else {
        echo "   ✗ users table NOT FOUND\n";
    }
    
    // Check password_reset_tokens table
    $result = $db->prepare("SELECT COUNT(*) as count FROM information_schema.TABLES WHERE TABLE_SCHEMA = ? AND TABLE_NAME = 'password_reset_tokens'")
        ->bind('s', $dbName)
        ->execute()
        ->single();
    
    if ($result && $result['count'] > 0) {
        echo "   ✓ password_reset_tokens table exists\n";
    } else {
        echo "   ✗ password_reset_tokens table NOT FOUND - Run: http://localhost/infohub/run_migration.php\n";
    }
    
    // Check auth_logs table
    $result = $db->prepare("SELECT COUNT(*) as count FROM information_schema.TABLES WHERE TABLE_SCHEMA = ? AND TABLE_NAME = 'auth_logs'")
        ->bind('s', $dbName)
        ->execute()
        ->single();
    
    if ($result && $result['count'] > 0) {
        echo "   ✓ auth_logs table exists\n";
    } else {
        echo "   ✗ auth_logs table NOT FOUND\n";
    }
} catch (Exception $e) {
    echo "   ✗ Database error: " . $e->getMessage() . "\n";
    exit(1);
}

// 2. Check required files
echo "\n2. Checking required files...\n";
$files = [
    'app/controllers/AuthController.php',
    'app/models/User.php',
    'app/views/auth/login.php',
    'app/views/auth/register.php',
    'app/views/auth/forgot-password.php',
    'app/views/auth/reset-password.php',
    'core/Logger.php',
    'core/Controller.php',
];

foreach ($files as $file) {
    $path = ROOT_PATH . '/' . $file;
    if (file_exists($path)) {
        echo "   ✓ $file\n";
    } else {
        echo "   ✗ $file NOT FOUND\n";
    }
}

// 3. Check routes in index.php
echo "\n3. Checking routes in index.php...\n";
$indexContent = file_get_contents(ROOT_PATH . '/index.php');
$routes = [
    "auth/login" => "/auth/login",
    "auth/register" => "/auth/register",
    "auth/forgot-password" => "/auth/forgot-password",
    "auth/reset-password" => "/auth/reset-password",
];

foreach ($routes as $name => $route) {
    if (strpos($indexContent, "'{$name}'") !== false || strpos($indexContent, "\"{$name}\"") !== false) {
        echo "   ✓ Route configured: {$name}\n";
    } else {
        echo "   ✗ Route missing: {$name}\n";
    }
}

// 4. Check User model methods
echo "\n4. Checking User model methods...\n";
require_once ROOT_PATH . '/app/models/User.php';
$methods = [
    'createPasswordResetToken',
    'verifyPasswordResetToken',
    'completePasswordReset',
    'emailExists',
    'countRecentPasswordResetAttempts',
];

$reflection = new ReflectionClass('User');
foreach ($methods as $method) {
    if ($reflection->hasMethod($method)) {
        echo "   ✓ User::{$method}()\n";
    } else {
        echo "   ✗ User::{$method}() NOT FOUND\n";
    }
}

// 5. Check AuthController methods
echo "\n5. Checking AuthController methods...\n";
require_once ROOT_PATH . '/core/Controller.php';
require_once ROOT_PATH . '/app/controllers/AuthController.php';
$methods = [
    'login',
    'handleLogin',
    'register',
    'handleRegister',
    'forgotPassword',
    'handleForgotPassword',
    'resetPassword',
    'handleResetPassword',
    'logout',
];

$reflection = new ReflectionClass('AuthController');
foreach ($methods as $method) {
    if ($reflection->hasMethod($method)) {
        echo "   ✓ AuthController::{$method}()\n";
    } else {
        echo "   ✗ AuthController::{$method}() NOT FOUND\n";
    }
}

// 6. Check database indexes
echo "\n6. Checking database indexes...\n";
try {
    // First check if table exists
    $dbName = DB_NAME;
    $tableCheck = $db->prepare("SELECT COUNT(*) as count FROM information_schema.TABLES WHERE TABLE_SCHEMA = ? AND TABLE_NAME = 'password_reset_tokens'")
        ->bind('s', $dbName)
        ->execute()
        ->single();
    
    if ($tableCheck && $tableCheck['count'] > 0) {
        $indexes = $db->prepare("SELECT DISTINCT INDEX_NAME FROM information_schema.STATISTICS WHERE TABLE_SCHEMA = ? AND TABLE_NAME = 'password_reset_tokens'")
            ->bind('s', $dbName)
            ->execute()
            ->resultSet();
        
        $indexNames = array_column($indexes, 'INDEX_NAME');
        $requiredIndexes = ['PRIMARY', 'idx_user_id', 'idx_token_hash', 'idx_expires_at', 'idx_used_at'];
        
        foreach ($requiredIndexes as $idx) {
            if (in_array($idx, $indexNames)) {
                echo "   ✓ Index: {$idx}\n";
            } else {
                echo "   ⚠ Index missing: {$idx} (performance may be affected)\n";
            }
        }
    } else {
        echo "   ⚠ password_reset_tokens table not found - skipping index check\n";
    }
} catch (Exception $e) {
    echo "   ⚠ Could not verify indexes: " . $e->getMessage() . "\n";
}

// 7. Test password hashing
echo "\n7. Testing password hashing...\n";
$testPassword = "TestPassword123";
$hash = password_hash($testPassword, PASSWORD_BCRYPT, ['cost' => 12]);
if (password_verify($testPassword, $hash)) {
    echo "   ✓ Password hashing works (bcrypt)\n";
} else {
    echo "   ✗ Password hashing failed\n";
}

// Check hash is bcrypt
if (strpos($hash, '$2y$') === 0) {
    echo "   ✓ Using bcrypt algorithm ($2y$)\n";
} else {
    echo "   ✗ Not using bcrypt algorithm\n";
}

// 8. Test token generation
echo "\n8. Testing token generation...\n";
try {
    $randomBytes = random_bytes(32);
    if (strlen($randomBytes) === 32) {
        echo "   ✓ Random token generation works (32 bytes)\n";
    } else {
        echo "   ✗ Random token generation failed\n";
    }
    
    $tokenHash = hash('sha256', $randomBytes);
    if (strlen($tokenHash) === 64) {
        echo "   ✓ SHA-256 hashing works (64 chars)\n";
    } else {
        echo "   ✗ SHA-256 hashing failed\n";
    }
} catch (Exception $e) {
    echo "   ✗ Token generation error: " . $e->getMessage() . "\n";
}

// 9. Check CSRF support
echo "\n9. Checking CSRF protection...\n";
if (function_exists('hash_algos') && in_array('sha256', hash_algos())) {
    echo "   ✓ Hash algorithms available\n";
} else {
    echo "   ✗ Hash algorithms not available\n";
}

if (function_exists('random_bytes')) {
    echo "   ✓ random_bytes() function available\n";
} else {
    echo "   ✗ random_bytes() function not available\n";
}

// 10. Check session support
echo "\n10. Checking session support...\n";
if (extension_loaded('session')) {
    echo "   ✓ Session extension loaded\n";
} else {
    echo "   ✗ Session extension not loaded\n";
}

// Test session start
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (session_status() === PHP_SESSION_ACTIVE) {
    echo "   ✓ Session can be started\n";
} else {
    echo "   ✗ Session could not be started\n";
}
echo "\n=== Verification Complete ===\n";
echo "\nNext steps:\n";
echo "1. Run migration: http://localhost/infohub/run_migration.php\n";
echo "2. Test login: http://localhost/infohub/auth/login\n";
echo "3. Test registration: http://localhost/infohub/auth/register\n";
echo "4. Test forgot password: http://localhost/infohub/auth/forgot-password\n";
echo "\nFor detailed setup instructions, see AUTH_SETUP_GUIDE.md\n";
?>
