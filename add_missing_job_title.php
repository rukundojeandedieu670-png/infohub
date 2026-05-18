<?php
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/core/Database.php';

$db = Database::getInstance()->getConnection();

echo "Adding missing job_title field...\n";
if ($db->query('ALTER TABLE users ADD COLUMN job_title VARCHAR(100) DEFAULT NULL AFTER bio')) {
    echo "✓ job_title field added successfully\n";
} else {
    echo "✗ Error: " . $db->error . "\n";
}

echo "\n=== Final Schema Verification ===\n";
$result = $db->query('DESCRIBE users');
$fields = [];
while ($row = $result->fetch_assoc()) {
    $fields[] = $row['Field'];
}

$workFields = ['job_title', 'company', 'industry', 'skills', 'experience_years', 'bio_professional', 'linkedin_url', 'portfolio_url', 'is_job_seeker', 'is_business_owner'];
foreach ($workFields as $field) {
    $exists = in_array($field, $fields) ? '✓' : '✗';
    echo "$exists $field\n";
}

if (count(array_diff($workFields, $fields)) === 0) {
    echo "\n✓ All work profile fields successfully added!\n";
    echo "\nMigration complete. Users can now:\n";
    echo "  • Add job titles and company information\n";
    echo "  • Specify industry and years of experience\n";
    echo "  • Add professional bio and skills\n";
    echo "  • Link LinkedIn and portfolio profiles\n";
    echo "  • Mark themselves as job seekers or business owners\n";
}
?>
