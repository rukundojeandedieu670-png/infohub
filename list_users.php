<?php
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/core/Database.php';

$db = Database::getInstance()->getConnection();
$result = $db->query("SELECT id, email, first_name, last_name, role_id FROM users LIMIT 5");

echo "=== Test Users ===\n";
while ($row = $result->fetch_assoc()) {
    echo $row['id'] . ": " . $row['email'] . " (" . $row['first_name'] . " " . $row['last_name'] . ") - Role: " . $row['role_id'] . "\n";
}

// Count total users
$countResult = $db->query("SELECT COUNT(*) as count FROM users");
$count = $countResult->fetch_assoc()['count'];
echo "\nTotal users: $count\n";
?>
