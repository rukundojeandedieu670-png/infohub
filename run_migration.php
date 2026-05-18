<?php
/**
 * Run Password Reset Migration
 */

define('ROOT_PATH', __DIR__);
require_once ROOT_PATH . '/config/database.php';
require_once ROOT_PATH . '/core/Database.php';

try {
    $db = Database::getInstance();
    $sql = file_get_contents(ROOT_PATH . '/database/password_reset_migration.sql');
    
    // Split by semicolon and execute each statement
    $statements = array_filter(array_map('trim', explode(';', $sql)));
    
    foreach ($statements as $statement) {
        if (!empty($statement) && strpos(trim($statement), '--') !== 0) {
            $db->query($statement);
        }
    }
    
    echo "✓ Password reset tokens table created successfully!\n";
} catch (Exception $e) {
    echo "✗ Error: " . $e->getMessage() . "\n";
    exit(1);
}
