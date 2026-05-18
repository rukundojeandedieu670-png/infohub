<?php
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/core/Database.php';

$db = Database::getInstance();
$conn = $db->getConnection();

// Disable mysqli exception reporting for this script so duplicate-column checks work
mysqli_report(MYSQLI_REPORT_OFF);

// Read and execute the migration
$migrationSQL = file_get_contents(__DIR__ . '/database/add_work_profile_fields.sql');

// Split by semicolon and execute each statement
$statements = array_filter(array_map('trim', explode(';', $migrationSQL)), function($stmt) {
    return !empty($stmt) && !preg_match('/^--/', $stmt);
});

$successCount = 0;
$errorCount = 0;
$skippedCount = 0;

foreach ($statements as $i => $statement) {
    if (!empty(trim($statement))) {
        try {
            if ($conn->query($statement)) {
                $successCount++;
                echo "✓ Statement " . ($i + 1) . ": " . substr(trim($statement), 0, 60) . "...\n";
            } else {
                if (strpos($conn->error, 'Duplicate') !== false || strpos($conn->error, 'already exists') !== false) {
                    $skippedCount++;
                    echo "⊘ Statement " . ($i + 1) . " skipped (already exists)\n";
                } else {
                    $errorCount++;
                    echo "✗ Statement " . ($i + 1) . " error: " . $conn->error . "\n";
                }
            }
        } catch (mysqli_sql_exception $e) {
            if (strpos($e->getMessage(), 'Duplicate') !== false || strpos($e->getMessage(), 'already exists') !== false) {
                $skippedCount++;
                echo "⊘ Statement " . ($i + 1) . " skipped (already exists)\n";
            } else {
                $errorCount++;
                echo "✗ Statement " . ($i + 1) . " error: " . $e->getMessage() . "\n";
            }
        }
    }
}

echo "\n=== Migration Results ===\n";
echo "Successful: $successCount\n";
echo "Skipped (already exists): $skippedCount\n";
echo "Failed: $errorCount\n";

if ($errorCount === 0) {
    echo "\n✓ Work profile fields migration completed!\n";
    echo "Added/verified fields: job_title, company, industry, skills, experience_years, bio_professional, linkedin_url, portfolio_url, is_job_seeker, is_business_owner\n";
} else {
    exit(1);
}
