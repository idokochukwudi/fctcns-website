<?php
// Test router fix
require_once dirname(__DIR__) . '/app/config/constants.php';
require_once APP_PATH . '/core/Router.php';

$router = new Router();

// Test URL processing
$testUrls = [
    '/index.php/about',
    '/index.php/programs',
    '/index.php',
    '/about',
    '/'
];

foreach ($testUrls as $url) {
    // Simulate what router does
    $requestUri = parse_url($url, PHP_URL_PATH);
    
    if (strpos($requestUri, '/index.php') === 0) {
        $requestUri = substr($requestUri, 10);
    }
    
    if ($requestUri === '') {
        $requestUri = '/';
    } else {
        $requestUri = rtrim($requestUri, '/');
    }
    
    echo "Original: $url → Processed: $requestUri<br>";
}
?>