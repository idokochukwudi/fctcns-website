<?php
/**
 * SEARCH DIAGNOSTIC TOOL
 * Place this file in your project ROOT folder (same level as app/, config/, public/)
 * Access: http://localhost/fctcns-website/search-diagnostic.php
 */

// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Define APP_PATH if not defined
if (!defined('APP_PATH')) {
    define('APP_PATH', __DIR__);
}

require_once APP_PATH . '/config/database.php';
require_once APP_PATH . '/models/NewsModel.php';

$database = Database::getInstance();
$db = $database->getConnection();
$newsModel = new NewsModel($db);

$searchQuery = "College of Nursing Unveils";

echo "<!DOCTYPE html>
<html>
<head>
    <title>Search Diagnostic</title>
    <style>
        body { font-family: Arial; padding: 20px; background: #f5f5f5; }
        .section { background: white; padding: 20px; margin: 20px 0; border-radius: 8px; box-shadow: 0 2px 5px rgba(0,0,0,0.1); }
        h2 { color: #5D4A8A; margin-top: 0; }
        pre { background: #f0f0f0; padding: 10px; border-radius: 4px; overflow: auto; }
        .success { color: green; font-weight: bold; }
        .error { color: red; font-weight: bold; }
        table { border-collapse: collapse; width: 100%; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background: #5D4A8A; color: white; }
    </style>
</head>
<body>
    <h1>🔍 Search Diagnostic Tool</h1>";

echo "<div class='section'>";
echo "<h2>Test 1: Check if Article ID 12 Exists and is Published</h2>";
$stmt = $db->prepare("SELECT id, title, is_published, category, content FROM news WHERE id = 12");
$stmt->execute();
$article = $stmt->fetch(PDO::FETCH_ASSOC);

if ($article) {
    echo "<p class='success'>✅ Article ID 12 FOUND</p>";
    echo "<pre>";
    print_r($article);
    echo "</pre>";
    
    if ($article['is_published'] == 1) {
        echo "<p class='success'>✅ Article IS published (is_published = 1)</p>";
    } else {
        echo "<p class='error'>❌ Article is NOT published (is_published = " . $article['is_published'] . ")</p>";
    }
} else {
    echo "<p class='error'>❌ Article ID 12 NOT FOUND in database</p>";
}
echo "</div>";

echo "<div class='section'>";
echo "<h2>Test 2: All Published Articles</h2>";
$stmt = $db->query("SELECT id, title, is_published FROM news WHERE is_published = 1 ORDER BY id DESC");
$allPublished = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo "<p>Total published articles: " . count($allPublished) . "</p>";
if (count($allPublished) > 0) {
    echo "<table>";
    echo "<tr><th>ID</th><th>Title</th><th>Status</th></tr>";
    foreach ($allPublished as $article) {
        $status = $article['is_published'] ? 'Published' : 'Draft';
        $highlight = (strpos($article['title'], 'College of Nursing') !== false) ? 'style="background: #ffffcc;"' : '';
        echo "<tr $highlight>";
        echo "<td>" . $article['id'] . "</td>";
        echo "<td>" . htmlspecialchars($article['title']) . "</td>";
        echo "<td>" . $status . "</td>";
        echo "</tr>";
    }
    echo "</table>";
} else {
    echo "<p class='error'>❌ No published articles found!</p>";
}
echo "</div>";

echo "<div class='section'>";
echo "<h2>Test 3: Direct SQL Search with Different Fields</h2>";

$searchTerm = '%' . $searchQuery . '%';

// Test 1: Title only
$stmt = $db->prepare("SELECT id, title FROM news WHERE is_published = 1 AND title LIKE ?");
$stmt->execute([$searchTerm]);
$titleResults = $stmt->fetchAll(PDO::FETCH_ASSOC);
echo "<h3>Title Search: " . count($titleResults) . " results</h3>";
if (count($titleResults) > 0) {
    echo "<pre>";
    print_r($titleResults);
    echo "</pre>";
}

// Test 2: Content only
$stmt = $db->prepare("SELECT id, title FROM news WHERE is_published = 1 AND content LIKE ?");
$stmt->execute([$searchTerm]);
$contentResults = $stmt->fetchAll(PDO::FETCH_ASSOC);
echo "<h3>Content Search: " . count($contentResults) . " results</h3>";

// Test 3: Category only
$stmt = $db->prepare("SELECT id, title FROM news WHERE is_published = 1 AND category LIKE ?");
$stmt->execute([$searchTerm]);
$categoryResults = $stmt->fetchAll(PDO::FETCH_ASSOC);
echo "<h3>Category Search: " . count($categoryResults) . " results</h3>";

echo "</div>";

echo "<div class='section'>";
echo "<h2>Test 4: Your Current Model's searchNews() Method</h2>";

if (method_exists($newsModel, 'searchNews')) {
    echo "<p>✅ searchNews() method exists</p>";
    
    $modelResults = $newsModel->searchNews($searchQuery, 10, 0);
    
    echo "<p>Model returned: " . count($modelResults) . " results</p>";
    
    if (count($modelResults) > 0) {
        echo "<pre>";
        print_r($modelResults);
        echo "</pre>";
    } else {
        echo "<p class='error'>❌ Model returned 0 results</p>";
    }
} else {
    echo "<p class='error'>❌ searchNews() method does NOT exist in your NewsModel!</p>";
}
echo "</div>";

echo "<div class='section'>";
echo "<h2>IMMEDIATE SOLUTION</h2>";
echo "<p>Copy and paste this into your <strong>/models/NewsModel.php</strong> file:</p>";
echo "<pre>
/**
 * SEARCH NEWS - SIMPLIFIED WORKING VERSION
 */
public function searchNews(\$query, \$limit = 10, \$offset = 0) {
    error_log(\"=== searchNews() called with: \$query ===\");
    
    try {
        \$searchTerm = '%' . \$query . '%';
        
        \$sql = \"SELECT 
                    n.*,
                    u.full_name as author_name,
                    u.role as author_role
                FROM news n 
                LEFT JOIN users u ON n.author_id = u.id 
                WHERE n.is_published = 1 
                AND n.title LIKE ? 
                ORDER BY n.created_at DESC 
                LIMIT ? OFFSET ?\";
        
        \$stmt = \$this->db->prepare(\$sql);
        \$stmt->execute([\$searchTerm, \$limit, \$offset]);
        
        \$results = \$stmt->fetchAll(PDO::FETCH_ASSOC);
        error_log(\"Found \" . count(\$results) . \" results\");
        
        return \$results;
        
    } catch (Exception \$e) {
        error_log(\"Search error: \" . \$e->getMessage());
        return [];
    }
}

/**
 * COUNT SEARCH RESULTS - SIMPLIFIED WORKING VERSION
 */
public function countSearchResults(\$query) {
    error_log(\"=== countSearchResults() called with: \$query ===\");
    
    try {
        \$searchTerm = '%' . \$query . '%';
        
        \$sql = \"SELECT COUNT(*) as total 
                FROM news 
                WHERE is_published = 1 
                AND title LIKE ?\";
        
        \$stmt = \$this->db->prepare(\$sql);
        \$stmt->execute([\$searchTerm]);
        
        \$result = \$stmt->fetch(PDO::FETCH_ASSOC);
        \$total = \$result['total'] ?? 0;
        
        error_log(\"Total results: \" . \$total);
        return \$total;
        
    } catch (Exception \$e) {
        error_log(\"Count error: \" . \$e->getMessage());
        return 0;
    }
}
</pre>";
echo "</div>";

echo "</body></html>";