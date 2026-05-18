<?php
/**
 * Diagnostic Script - Check Database and Routing
 */

error_reporting(E_ALL);
ini_set('display_errors', 1);

// Define root path
define('ROOT_PATH', __DIR__);

// Load configuration
require_once ROOT_PATH . '/config/database.php';

// Load core classes
require_once ROOT_PATH . '/core/Database.php';

echo "=== INFOHUB DIAGNOSTIC REPORT ===\n\n";

// Test 1: Database Connection
echo "1. Testing Database Connection...\n";
try {
    $db = Database::getInstance();
    echo "   ✓ Database connection successful\n\n";
    
    // Test 2: Check if tables exist
    echo "2. Checking Tables...\n";
    $result = mysqli_query(mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME), "SHOW TABLES");
    $tableCount = mysqli_num_rows($result);
    echo "   ✓ Found $tableCount tables\n\n";
    
    // Test 3: Check jobs table
    echo "3. Testing Jobs Table Query...\n";
    $stmt = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    $query = "SELECT COUNT(*) as count FROM jobs WHERE status = 'open'";
    $result = mysqli_query($stmt, $query);
    
    if ($result) {
        $row = mysqli_fetch_assoc($result);
        echo "   ✓ Query successful - Found {$row['count']} open jobs\n\n";
    } else {
        echo "   ✗ Query failed: " . mysqli_error($stmt) . "\n\n";
    }
    
    // Test 4: Check Jobs Model
    echo "4. Testing Job Model...\n";
    if (file_exists(ROOT_PATH . '/app/models/Job.php')) {
        require_once ROOT_PATH . '/core/Model.php';
        require_once ROOT_PATH . '/app/models/Job.php';
        echo "   ✓ Job model loaded\n\n";
    } else {
        echo "   ✗ Job model not found\n\n";
    }
    
    // Test 5: Check Controller
    echo "5. Testing JobsController...\n";
    if (file_exists(ROOT_PATH . '/app/controllers/JobsController.php')) {
        echo "   ✓ JobsController found\n\n";
    } else {
        echo "   ✗ JobsController not found\n\n";
    }
    
    // Test 6: Check View
    echo "6. Testing Jobs View...\n";
    if (file_exists(ROOT_PATH . '/app/views/jobs/index.php')) {
        echo "   ✓ Jobs view found\n\n";
    } else {
        echo "   ✗ Jobs view not found\n\n";
    }
    
} catch (Exception $e) {
    echo "   ✗ Error: " . $e->getMessage() . "\n\n";
}

echo "=== END DIAGNOSTIC REPORT ===\n";
?>
