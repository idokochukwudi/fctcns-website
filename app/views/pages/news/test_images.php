<?php
// Test image paths
require_once dirname(__DIR__, 2) . '/helpers/image_helper.php';

$baseUrl = 'http://localhost/fctcns-website';
$testPaths = [
    '/uploads/news/news_698afd5901750_1770716505.jpg',
    'uploads/news/news_698afd5901750_1770716505.jpg',
    'news_698afd5901750_1770716505.jpg',
    'wrong_path.jpg'
];

echo "<h1>Image URL Test</h1>";

foreach ($testPaths as $path) {
    echo "<h2>Testing: " . htmlspecialchars($path) . "</h2>";
    $url = getImageUrl($path);
    echo "<p>Generated URL: <a href='$url' target='_blank'>$url</a></p>";
    
    // Try to display
    echo "<p><img src='$url' style='max-width: 200px; border: 2px solid " . 
         (checkImageExists($path) ? 'green' : 'red') . 
         ";' onerror='this.style.display=\"none\"; this.parentNode.innerHTML+=\"<span style=color:red>Image failed to load</span>\";'></p>";
    echo "<hr>";
}
?>