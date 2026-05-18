<?php
// Test registration with fixed bindArray

$ch = curl_init('http://localhost/infohub/auth/register');
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query([
    'first_name' => 'John',
    'last_name' => 'Doe',
    'email' => 'johndoe123@example.com',
    'password' => 'SecurePass123!',
    'password_confirm' => 'SecurePass123!'
]));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_HEADER, 1);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

echo "HTTP Code: $httpCode\n";
echo str_repeat("=", 80) . "\n";
echo "Response:\n";
echo $response . "\n";
