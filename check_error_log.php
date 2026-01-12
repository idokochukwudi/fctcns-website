<?php
echo "<h1>PHP Error Log Check</h1>";

// Get error log path
$errorLogPath = ini_get('error_log');
echo "<p>Error log path: <strong>" . htmlspecialchars($errorLogPath) . "</strong></p>";

// Common error log locations
$commonLocations = [
    '/var/log/apache2/error.log',
    '/var/log/httpd/error_log',
    '/var/log/php/error.log',
    'C:/xampp/apache/logs/error.log',
    'C:/xampp/php/logs/php_error.log',
    '/Applications/XAMPP/xamppfiles/logs/php_error_log',
    $errorLogPath
];

echo "<h2>Checking common locations:</h2>";
foreach ($commonLocations as $location) {
    if (file_exists($location)) {
        echo "<h3>Found: $location</h3>";
        $lastLines = `tail -50 "$location" 2>/dev/null || type "$location"`;
        echo "<pre style='background:#f0f0f0;padding:10px;'>" . htmlspecialchars($lastLines) . "</pre>";
    }
}

// Test logging
error_log("=== TEST ERROR LOG MESSAGE ===");
echo "<p>Test error logged. Check above logs for 'TEST ERROR LOG MESSAGE'.</p>";

// Show current PHP settings
echo "<h2>PHP Settings:</h2>";
echo "<p>display_errors: " . ini_get('display_errors') . "</p>";
echo "<p>error_reporting: " . error_reporting() . "</p>";
echo "<p>log_errors: " . ini_get('log_errors') . "</p>";
?>