<?php
$conn = new mysqli('localhost', 'root', '', 'infohub');
if ($conn->connect_error) die('Connection failed: ' . $conn->connect_error);

$password = 'TestPassword123';
$hash = password_hash($password, PASSWORD_DEFAULT);

$query = "UPDATE users SET password_hash = '" . $conn->real_escape_string($hash) . "' WHERE email = 'testuser@example.com'";
if ($conn->query($query)) {
    echo 'Password updated successfully!<br>';
    echo 'Email: testuser@example.com<br>';
    echo 'Password: TestPassword123<br>';
} else {
    echo 'Error: ' . $conn->error;
}
$conn->close();
?>
