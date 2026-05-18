<?php
/**
 * Test News Controller
 */
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();

define('ROOT_PATH', __DIR__);
require_once ROOT_PATH . '/config/database.php';
require_once ROOT_PATH . '/core/Database.php';
require_once ROOT_PATH . '/core/Logger.php';
require_once ROOT_PATH . '/core/Controller.php';
require_once ROOT_PATH . '/core/Model.php';

echo "=== NEWS CONTROLLER TEST ===\n\n";

try {
    require_once ROOT_PATH . '/app/controllers/NewsController.php';
    $controller = new NewsController();
    
    echo "✓ NewsController instantiated successfully\n";
    echo "✓ Testing index() method...\n\n";
    
    ob_start();
    $controller->index(1);
    $output = ob_get_clean();
    
    if (strlen($output) > 0) {
        echo "✓ Index method executed\n";
        echo "Output length: " . strlen($output) . " bytes\n";
    }
    
} catch (Throwable $e) {
    echo "✗ Error: " . $e->getMessage() . "\n";
    echo "   File: " . $e->getFile() . " Line: " . $e->getLine() . "\n";
}

echo "\n=== BUSINESS CONTROLLER TEST ===\n\n";

try {
    require_once ROOT_PATH . '/app/controllers/BusinessController.php';
    $controller = new BusinessController();
    
    echo "✓ BusinessController instantiated successfully\n";
    echo "✓ Testing index() method...\n\n";
    
    ob_start();
    $controller->index(1);
    $output = ob_get_clean();
    
    if (strlen($output) > 0) {
        echo "✓ Index method executed\n";
        echo "Output length: " . strlen($output) . " bytes\n";
    }
    
} catch (Throwable $e) {
    echo "✗ Error: " . $e->getMessage() . "\n";
    echo "   File: " . $e->getFile() . " Line: " . $e->getLine() . "\n";
}
?>
