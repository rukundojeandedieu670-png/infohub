<?php
define('ROOT_PATH', __DIR__);
require_once ROOT_PATH . '/config/database.php';
require_once ROOT_PATH . '/core/Database.php';

// Create a test user for testing job-seeking fields
$db = Database::getInstance();

// Test credentials
$testEmail = 'test.jobseeker@infohub.rw';
$testPassword = 'TestPass123!';
$passwordHash = password_hash($testPassword, PASSWORD_BCRYPT);

// Check if user exists
$db->prepare('SELECT id FROM users WHERE email = ?');
$db->bind('s', $testEmail);
$db->execute();
$result = $db->single();

if (!$result) {
    // Create new test user
    $firstName = 'Test';
    $lastName = 'JobSeeker';
    $roleId = 7; // Regular user role
    $isActive = 1;
    
    $db->prepare('INSERT INTO users (first_name, last_name, email, password_hash, role_id, is_active) VALUES (?, ?, ?, ?, ?, ?)');
    $db->bind('s', $firstName);
    $db->bind('s', $lastName);
    $db->bind('s', $testEmail);
    $db->bind('s', $passwordHash);
    $db->bind('i', $roleId);
    $db->bind('i', $isActive);
    $db->execute();
    
    echo "✓ Test user created\n";
    echo "Email: $testEmail\n";
    echo "Password: $testPassword\n";
} else {
    echo "✓ Test user already exists\n";
    echo "Email: $testEmail\n";
    echo "Password: $testPassword\n";
}
?>
