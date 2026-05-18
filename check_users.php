<?php
require_once 'config/database.php';

$db = new Database();
$result = $db->query('SELECT id, email, first_name, last_name FROM users LIMIT 5');

if ($result) {
    foreach ($result as $user) {
        echo 'ID: ' . $user['id'] . ', Email: ' . $user['email'] . ', Name: ' . $user['first_name'] . ' ' . $user['last_name'] . "\n";
    }
} else {
    echo 'No users found';
}
?>
