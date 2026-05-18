<?php
session_start();

echo "Session ID: " . session_id() . "\n";
echo "Session Variables: " . json_encode($_SESSION) . "\n";
echo "User ID: " . ($_SESSION['user_id'] ?? 'Not set') . "\n";
echo "User Role: " . ($_SESSION['user_role'] ?? 'Not set') . "\n";
