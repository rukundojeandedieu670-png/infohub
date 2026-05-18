<?php
/**
 * Database Configuration
 * InfoHub Platform
 */

define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'infohub');
define('DB_PORT', 3306);
define('DB_CHARSET', 'utf8mb4');

// Application Settings
define('APP_NAME', 'InfoHub');
define('APP_URL', 'http://localhost/infohub');
define('APP_ENV', 'development');
define('APP_DEBUG', true);

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
