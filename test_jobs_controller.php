<?php
/**
 * Test Jobs Controller Directly
 */

error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();

// Define root path
define('ROOT_PATH', __DIR__);

// Load configuration
require_once ROOT_PATH . '/config/database.php';

// Load core classes
require_once ROOT_PATH . '/core/Database.php';
require_once ROOT_PATH . '/core/Logger.php';
require_once ROOT_PATH . '/core/Controller.php';
require_once ROOT_PATH . '/core/Model.php';

echo "=== JOBS CONTROLLER TEST ===\n\n";

try {
    // Load and instantiate JobsController
    require_once ROOT_PATH . '/app/controllers/JobsController.php';
    $controller = new JobsController();
    
    echo "✓ JobsController instantiated successfully\n";
    echo "✓ Testing index() method...\n\n";
    
    // Call the index method (this would normally be done by the router)
    ob_start();
    $controller->index(1);
    $output = ob_get_clean();
    
    if (strlen($output) > 0) {
        echo "✓ Index method executed\n";
        echo "Output length: " . strlen($output) . " bytes\n\n";
        // Show first 500 chars of output
        echo "First 500 chars of output:\n";
        echo substr($output, 0, 500) . "...\n";
    } else {
        echo "✗ No output from index method\n";
    }
    
} catch (Throwable $e) {
    echo "✗ Error occurred:\n";
    echo "  Message: " . $e->getMessage() . "\n";
    echo "  File: " . $e->getFile() . "\n";
    echo "  Line: " . $e->getLine() . "\n\n";
    echo "  Stack trace:\n";
    foreach (explode("\n", $e->getTraceAsString()) as $line) {
        echo "    " . $line . "\n";
    }
}

echo "\n=== END TEST ===\n";
?>
