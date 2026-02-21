<?php
echo "<h1>File Existence Check</h1>";

$paths = [
    'Current directory' => __DIR__,
    'Parent directory' => dirname(__DIR__),
    'app/core/Controller.php' => file_exists(dirname(__DIR__) . '/app/core/Controller.php') ? '✅ EXISTS' : '❌ MISSING',
    'app/controllers/ApplicationVerificationController.php' => file_exists(dirname(__DIR__) . '/app/controllers/ApplicationVerificationController.php') ? '✅ EXISTS' : '❌ MISSING',
];

echo "<table border='1'>";
foreach ($paths as $path => $status) {
    echo "<tr><td>$path</td><td>$status</td></tr>";
}
echo "</table>";

echo "<h2>PHP Configuration</h2>";
echo "GD Extension: " . (extension_loaded('gd') ? '✅ Loaded' : '❌ Not Loaded') . "<br>";
echo "cURL: " . (function_exists('curl_init') ? '✅ Available' : '❌ Not Available') . "<br>";
