<?php
// Test Research Module
require_once 'app/config/constants.php';
require_once 'app/config/database.php';
require_once 'app/config/session.php';
require_once 'app/models/ResearchModel.php';

echo "<h1>Research Module Test</h1>";

// Test Database Connection
echo "<h2>1. Database Connection Test</h2>";
$db = Database::getInstance();
try {
    $test = $db->fetchOne("SELECT 1 as test");
    if ($test && $test['test'] == 1) {
        echo "<p style='color: green;'>✓ Database connection successful</p>";
    } else {
        echo "<p style='color: red;'>✗ Database test query failed</p>";
    }
} catch (Exception $e) {
    echo "<p style='color: red;'>✗ Database connection failed: " . htmlspecialchars($e->getMessage()) . "</p>";
}

// Test ResearchModel
echo "<h2>2. ResearchModel Test</h2>";
$model = new ResearchModel();

// Test categories
$categories = $model->getCategories();
echo "<p>Categories found: " . count($categories) . "</p>";
if ($categories) {
    echo "<ul>";
    foreach ($categories as $cat) {
        echo "<li>" . htmlspecialchars($cat['name']) . " (slug: " . htmlspecialchars($cat['slug']) . ")</li>";
    }
    echo "</ul>";
}

// Test publications
$publications = $model->getAll(['limit' => 5]);
echo "<p>Publications found: " . count($publications) . "</p>";

// Test stats
$stats = $model->getStats();
echo "<h2>3. Statistics Test</h2>";
echo "<pre>" . print_r($stats, true) . "</pre>";

echo "<h2>4. Directory Check</h2>";
$uploadDir = UPLOADS_PATH . '/research/';
$thumbnailDir = UPLOADS_PATH . '/research/thumbnails/';

if (is_dir($uploadDir)) {
    echo "<p style='color: green;'>✓ Upload directory exists: " . htmlspecialchars($uploadDir) . "</p>";
} else {
    echo "<p style='color: red;'>✗ Upload directory missing: " . htmlspecialchars($uploadDir) . "</p>";
}

if (is_dir($thumbnailDir)) {
    echo "<p style='color: green;'>✓ Thumbnail directory exists: " . htmlspecialchars($thumbnailDir) . "</p>";
} else {
    echo "<p style='color: red;'>✗ Thumbnail directory missing: " . htmlspecialchars($thumbnailDir) . "</p>";
}

echo "<h2>5. Test Complete</h2>";
echo "<p>If all tests pass, you can access:</p>";
echo "<ul>";
echo "<li><a href='/admin/research'>Admin Research Dashboard</a> (requires login)</li>";
echo "<li><a href='/research'>Public Research Page</a></li>";
echo "</ul>";