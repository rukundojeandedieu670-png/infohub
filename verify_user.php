<?php
// Verify the user was created in the database
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/core/Database.php';

try {
    $db = Database::getInstance();
    $query = "SELECT id, first_name, last_name, email, role_id FROM users ORDER BY id DESC LIMIT 1";
    $db->prepare($query)->execute();
    $lastUser = $db->single();
    
    echo "Last registered user:\n";
    echo json_encode($lastUser, JSON_PRETTY_PRINT) . "\n";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
