<?php
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/core/Database.php';

try {
    $db = Database::getInstance();
    
    // Get the role_id for 'Registered User' role
    $db->prepare("SELECT id FROM roles WHERE name = 'Registered User' LIMIT 1");
    $db->execute();
    $role = $db->single();
    $role_id = $role['id'] ?? 7;
    
    // Create a test user
    $email = 'testuser@example.com';
    $password_hash = password_hash('TestPass123!', PASSWORD_BCRYPT);
    
    $db->prepare("INSERT INTO users (first_name, last_name, email, password_hash, role_id, is_active) 
                  VALUES (?, ?, ?, ?, ?, 1)");
    $db->bindArray('ssssi', [
        'Test',
        'User',
        $email,
        $password_hash,
        $role_id
    ]);
    $db->execute();
    
    echo "Test user created successfully!\n";
    echo "Email: $email\n";
    echo "Password: TestPass123!\n";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
