<?php
// Test if logout route works
$url = 'http://localhost/infohub/auth/logout';
$response = file_get_contents($url);
echo "Logout response received\n";

// Now check session
session_start();
echo "Session after logout: " . json_encode($_SESSION) . "\n";
