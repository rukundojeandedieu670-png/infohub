<?php
/**
 * Database Configuration
 * InfoHub Platform
 */

function env($key, $default = null) {
    $value = getenv($key);
    return $value !== false ? $value : $default;
}

define('DB_HOST', env('DB_HOST', 'localhost'));
define('DB_USER', env('DB_USER', 'root'));
define('DB_PASS', env('DB_PASS', ''));
define('DB_NAME', env('DB_NAME', 'infohub'));
define('DB_PORT', env('DB_PORT', 3306));
define('DB_CHARSET', env('DB_CHARSET', 'utf8mb4'));

// Application Settings
define('APP_NAME', env('APP_NAME', 'InfoHub'));
define('APP_URL', env('APP_URL', 'http://localhost/infohub'));
define('APP_ENV', env('APP_ENV', 'development'));
define('APP_DEBUG', filter_var(env('APP_DEBUG', 'true'), FILTER_VALIDATE_BOOLEAN));

// Security
define('JWT_SECRET', 'your-super-secret-jwt-key-change-in-production');
define('SESSION_TIMEOUT', 3600); // 1 hour
define('BCRYPT_COST', 12);

// Upload Settings
define('UPLOAD_DIR', __DIR__ . '/../public/uploads/');
define('MAX_FILE_SIZE', 5242880); // 5MB
define('ALLOWED_EXTENSIONS', ['jpg', 'jpeg', 'png', 'pdf', 'doc', 'docx']);

// Email Configuration
define('MAIL_HOST', 'smtp.mailtrap.io');
define('MAIL_PORT', 465);
define('MAIL_USER', 'your-email@example.com');
define('MAIL_PASS', 'your-password');
define('MAIL_FROM', 'noreply@infohub.rw');

// Timezone
date_default_timezone_set('Africa/Kigali');
