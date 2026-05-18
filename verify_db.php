<?php
define('ROOT_PATH', __DIR__);
require 'config/database.php';

try {
    $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME, DB_PORT);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Get tables
    $result = $conn->query("SHOW TABLES");
    echo "Tables in database:\n";
    while ($row = $result->fetch_row()) {
        echo "  - " . $row[0] . "\n";
    }

    // Get jobs table structure
    echo "\nJobs table structure:\n";
    $result = $conn->query("DESCRIBE jobs");
    while ($row = $result->fetch_assoc()) {
        echo "  - " . $row['Field'] . " (" . $row['Type'] . ")\n";
    }

    // Count jobs
    $result = $conn->query("SELECT COUNT(*) as count FROM jobs WHERE status='open' AND deadline > NOW()");
    $row = $result->fetch_assoc();
    echo "\nOpen jobs count: " . $row['count'] . "\n";

} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
?>
