<?php
/**
 * Test route matching
 * Access: http://localhost/fctcns-website/test-route.php
 */

define('APP_PATH', __DIR__);

require_once APP_PATH . '/app/core/Router.php';

$router = new Router();

echo "<h1>Route Testing</h1>";

$testUrls = [
    '/news/search?q=College+of+Nursing+Unveils',
    '/news/search',
    '/news/category/Academic-News',
    '/news',
    '/ultra-simple-test',
    '/router-test'
];

foreach ($testUrls as $url) {
    echo "<h2>Testing: $url</h2>";
    
    // Parse the URL
    $path = parse_url($url, PHP_URL_PATH);
    $query = parse_url($url, PHP_URL_QUERY);
    
    echo "<p>Path: " . htmlspecialchars($path) . "</p>";
    echo "<p>Query: " . htmlspecialchars($query ?? 'none') . "</p>";
    
    // Manually set the request URI
    $_SERVER['REQUEST_URI'] = $url;
    $_SERVER['REQUEST_METHOD'] = 'GET';
    
    $match = $router->match();
    
    if ($match) {
        echo "<p style='color:green'>✅ MATCHED: " . $match['route']['path'] . " -> " . $match['handler'] . "</p>";
        echo "<pre>";
        print_r($match);
        echo "</pre>";
    } else {
        echo "<p style='color:red'>❌ NO MATCH</p>";
    }
    
    echo "<hr>";
}

// Show all registered routes
echo "<h2>All Registered Routes</h2>";
$routes = $router->getRoutes();
echo "<table border='1' cellpadding='5'>";
echo "<tr><th>Method</th><th>Path</th><th>Pattern</th><th>Handler</th></tr>";
foreach ($routes as $route) {
    echo "<tr>";
    echo "<td>" . $route['method'] . "</td>";
    echo "<td>" . htmlspecialchars($route['path']) . "</td>";
    echo "<td>" . htmlspecialchars($route['pattern']) . "</td>";
    echo "<td>" . htmlspecialchars($route['handler']) . "</td>";
    echo "</tr>";
}
echo "</table>";