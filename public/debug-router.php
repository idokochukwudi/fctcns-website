<?php
/**
 * Router Debug File
 * 
 * Use this to debug routing issues
 */

require_once dirname(__DIR__) . '/app/config/constants.php';

echo "<h1>Router Debug</h1>";

echo "<h2>Current Request Info:</h2>";
echo "<pre>";
echo "REQUEST_URI: " . ($_SERVER['REQUEST_URI'] ?? 'N/A') . "\n";
echo "SCRIPT_NAME: " . ($_SERVER['SCRIPT_NAME'] ?? 'N/A') . "\n";
echo "HTTP_HOST: " . ($_SERVER['HTTP_HOST'] ?? 'N/A') . "\n";
echo "Server Software: " . ($_SERVER['SERVER_SOFTWARE'] ?? 'N/A') . "\n";
echo "</pre>";

echo "<h2>Parsed URL Info:</h2>";
$uri = $_SERVER['REQUEST_URI'] ?? '/';
echo "Original URI: $uri<br>";

// Remove query string
$uri = parse_url($uri, PHP_URL_PATH);
echo "After parse_url: $uri<br>";

// Remove /fctcns-website prefix if present
if (strpos($uri, '/fctcns-website') === 0) {
    $uri = substr($uri, strlen('/fctcns-website'));
    echo "After removing /fctcns-website: $uri<br>";
}

// Final URI
if ($uri === '' || $uri === '/') {
    $uri = '/';
} else {
    $uri = rtrim($uri, '/');
}
echo "<strong>Final processed URI: $uri</strong>";

echo "<h2>Test Links:</h2>";
echo "<ul>";
echo "<li><a href='" . BASE_URL . "/'>Home</a></li>";
echo "<li><a href='" . BASE_URL . "/about'>About</a></li>";
echo "<li><a href='" . BASE_URL . "/programs'>Programs</a></li>";
echo "<li><a href='" . BASE_URL . "/contact'>Contact</a></li>";
echo "</ul>";
?>