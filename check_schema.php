<?php
define('ROOT_PATH', __DIR__);
require 'config/database.php';
require 'core/Database.php';

$db = Database::getInstance();
$db->prepare('DESCRIBE jobs');
$result = $db->resultSet();

echo "Columns in jobs table:\n";
foreach ($result as $col) {
    echo "  - " . $col['Field'] . " (" . $col['Type'] . ")\n";
}
?>
