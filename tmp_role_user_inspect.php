<?php
require 'config/database.php';
$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME, DB_PORT);
if ($conn->connect_error) {
    echo 'ERROR: ' . $conn->connect_error . "\n";
    exit(1);
}
$res = $conn->query('SELECT id, name, permissions, parent_id FROM roles ORDER BY id');
while ($row = $res->fetch_assoc()) {
    echo $row['id'] . ' | ' . $row['name'] . ' | ' . ($row['parent_id'] ?? 'NULL') . ' | ' . substr($row['permissions'], 0, 120) . "\n";
}
echo "---\n";
$res2 = $conn->query('SELECT id, email, first_name, last_name, role_id, is_active FROM users ORDER BY id LIMIT 20');
while ($row = $res2->fetch_assoc()) {
    echo $row['id'] . ' | ' . $row['email'] . ' | ' . $row['first_name'] . ' | ' . $row['last_name'] . ' | ' . $row['role_id'] . ' | ' . $row['is_active'] . "\n";
}
