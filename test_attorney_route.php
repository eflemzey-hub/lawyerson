<?php
// Test file to debug attorney routing
require_once 'config/database.php';
require_once 'models/Attorney.php';

echo "<h2>Testing Attorney Model</h2>";

$attorneyModel = new Attorney($pdo ?? null);

echo "<h3>Test 1: Get All Attorneys</h3>";
$allAttorneys = $attorneyModel->getAll();
echo "<pre>";
print_r($allAttorneys);
echo "</pre>";

echo "<h3>Test 2: Get Attorney by ID (ID=1)</h3>";
$attorney1 = $attorneyModel->getById(1);
echo "<pre>";
print_r($attorney1);
echo "</pre>";

echo "<h3>Test 3: Check if 'id' key exists in first attorney</h3>";
if (!empty($allAttorneys) && isset($allAttorneys[0]['id'])) {
    echo "✅ ID exists: " . $allAttorneys[0]['id'];
} else {
    echo "❌ ID does NOT exist in array";
}

echo "<h3>Test 4: Test Attorney Link</h3>";
echo '<a href="index.php?page=attorney&id=1" style="font-size: 20px; color: blue;">Click here to test attorney profile link (ID=1)</a>';
?>
