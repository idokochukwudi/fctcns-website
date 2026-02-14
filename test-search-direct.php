<?php
/**
 * Direct test of NewsController search method
 * Access: http://localhost/fctcns-website/test-search-direct.php?q=College+of+Nursing+Unveils
 */

define('APP_PATH', __DIR__);

require_once APP_PATH . '/config/database.php';
require_once APP_PATH . '/models/NewsModel.php';
require_once APP_PATH . '/controllers/NewsController.php';

// Simulate the request
$_GET['q'] = $_GET['q'] ?? 'College of Nursing Unveils';
$_GET['page'] = $_GET['page'] ?? 1;

echo "<h1>Direct Search Test</h1>";
echo "<p>Searching for: <strong>" . htmlspecialchars($_GET['q']) . "</strong></p>";

try {
    $controller = new NewsController();
    
    // Use reflection to access the private method or create a public test method
    echo "<h2>Testing database directly:</h2>";
    
    $db = Database::getInstance()->getConnection();
    $searchTerm = '%' . $_GET['q'] . '%';
    
    $sql = "SELECT id, title, is_published FROM news WHERE is_published = 1 AND title LIKE ?";
    $stmt = $db->prepare($sql);
    $stmt->execute([$searchTerm]);
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo "<p>Direct SQL found: " . count($results) . " results</p>";
    
    if (count($results) > 0) {
        echo "<pre>";
        print_r($results);
        echo "</pre>";
    }
    
    echo "<h2>Testing NewsModel searchNews():</h2>";
    
    $newsModel = new NewsModel($db);
    $modelResults = $newsModel->searchNews($_GET['q'], 10, 0);
    
    echo "<p>Model found: " . count($modelResults) . " results</p>";
    
    if (count($modelResults) > 0) {
        echo "<pre>";
        print_r($modelResults);
        echo "</pre>";
    }
    
} catch (Exception $e) {
    echo "<p style='color:red'>Error: " . $e->getMessage() . "</p>";
    echo "<pre>" . $e->getTraceAsString() . "</pre>";
}