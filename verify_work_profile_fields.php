<?php
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/core/Database.php';

$db = Database::getInstance()->getConnection();
$result = $db->query('DESCRIBE users');

echo "=== Users Table Schema ===\n\n";
$fields = [];
while ($row = $result->fetch_assoc()) {
    $fields[] = $row['Field'];
    echo ($result->num_rows > 0 || count($fields) > 1 ? "" : "") . $row['Field'] . " (" . $row['Type'] . ")\n";
}

echo "\n=== Work Profile Fields Status ===\n";
$workFields = ['job_title', 'company', 'industry', 'skills', 'experience_years', 'bio_professional', 'linkedin_url', 'portfolio_url', 'is_job_seeker', 'is_business_owner'];
foreach ($workFields as $field) {
    $exists = in_array($field, $fields) ? '✓' : '✗';
    echo "$exists $field\n";
}

$missing = array_diff($workFields, $fields);
if (empty($missing)) {
    echo "\n✓ All work profile fields are present in the database!\n";
} else {
    echo "\n⚠ Missing fields: " . implode(', ', $missing) . "\n";
}
?>
