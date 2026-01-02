<?php
// Simple debug - don't rely on constants yet
echo "<h2>Path Debug - Raw Info</h2>";
echo "Document Root: " . $_SERVER['DOCUMENT_ROOT'] . "<br>";
echo "Request URI: " . $_SERVER['REQUEST_URI'] . "<br>";
echo "Server Name: " . $_SERVER['SERVER_NAME'] . "<br>";

// Try to load constants
$constants_path = dirname(__DIR__) . '/app/config/constants.php';
if (file_exists($constants_path)) {
    require_once $constants_path;
    echo "<hr><h3>From Constants.php</h3>";
    echo "BASE_URL: " . (defined('BASE_URL') ? BASE_URL : 'NOT DEFINED') . "<br>";
    echo "ASSETS_URL: " . (defined('ASSETS_URL') ? ASSETS_URL : 'NOT DEFINED') . "<br>";
    
    // Test the function
    if (function_exists('asset_url')) {
        echo "asset_url('test.jpg'): " . asset_url('test.jpg') . "<br>";
    }
}

// Test actual file paths
echo "<hr><h3>File Existence Check</h3>";
$test_files = [
    '/assets/images/placeholder/person-placeholder.jpg',
    '/public/assets/images/placeholder/person-placeholder.jpg',
];

foreach ($test_files as $file) {
    $full_path = $_SERVER['DOCUMENT_ROOT'] . $file;
    echo "Checking: $file<br>";
    echo "Full path: $full_path<br>";
    echo "Exists: " . (file_exists($full_path) ? '✅ YES' : '❌ NO') . "<br><br>";
}
?>