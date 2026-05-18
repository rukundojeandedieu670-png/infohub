<?php
$host = 'localhost';
$user = 'root';
$pass = '';
$db = 'infohub';

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) die('Connection failed: ' . $conn->connect_error);

$result = $conn->query("SELECT id, first_name, email, password_hash FROM users WHERE email='testuser@example.com'");
$row = $result->fetch_assoc();
if ($row) {
    echo 'User: ' . $row['first_name'] . ' (' . $row['email'] . ')' . PHP_EOL;
    echo 'Hash: ' . $row['password_hash'] . PHP_EOL;
    
    // Test with the password "TestPassword123"
    $password = 'TestPassword123';
    if (password_verify($password, $row['password_hash'])) {
        echo 'Password matches!' . PHP_EOL;
    } else {
        echo 'Password does not match!' . PHP_EOL;
        // Try to create a new hash to compare
        echo 'Testing hash: ' . password_hash($password, PASSWORD_BCRYPT, ['cost' => 12]) . PHP_EOL;
    }
} else {
    echo 'User not found' . PHP_EOL;
}
