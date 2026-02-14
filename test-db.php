<?php
/**
 * Simple database test
 * Access: http://localhost/fctcns-website/test-db.php
 */

require_once 'config/database.php';

$database = Database::getInstance();
$db = $database->getConnection();

echo "<h1>Database Test</h1>";

// Check if article ID 12 exists
$stmt = $db->prepare("SELECT id, title, is_published FROM news WHERE id = 12");
$stmt->execute();
$article = $stmt->fetch(PDO::FETCH_ASSOC);

echo "<h2>Article ID 12:</h2>";
if ($article) {
    echo "<pre>";
    print_r($article);
    echo "</pre>";
    
    if ($article['is_published'] == 1) {
        echo "<p style='color:green'>✓ Article is published</p>";
    } else {
        echo "<p style='color:red'>✗ Article is NOT published</p>";
    }
} else {
    echo "<p style='color:red'>✗ Article ID 12 not found</p>";
}

// Test search directly
echo "<h2>Test Search:</h2>";
$searchTerm = "%College of Nursing Unveils%";
$stmt = $db->prepare("SELECT id, title FROM news WHERE is_published = 1 AND title LIKE ?");
$stmt->execute([$searchTerm]);
$results = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo "<p>Found " . count($results) . " results</p>";
if (count($results) > 0) {
    echo "<pre>";
    print_r($results);
    echo "</pre>";
} else {
    echo "<p style='color:red'>✗ No results found with direct SQL</p>";
}