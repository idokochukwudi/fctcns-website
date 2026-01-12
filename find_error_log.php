<?php
echo "<h1>Find Error Log</h1>";

// Check various locations
$locations = [
    // Windows
    'C:/xampp/php/logs/php_error_log',
    'C:/xampp/apache/logs/error.log',
    'C:/xampp/apache/logs/error.log.old',
    
    // Alternative XAMPP paths
    'C:/Program Files/xampp/php/logs/php_error_log',
    'C:/Program Files (x86)/xampp/php/logs/php_error_log',
    
    // Linux/Mac
    '/var/log/apache2/error.log',
    '/var/log/httpd/error_log',
    '/usr/local/var/log/httpd/error_log',
    
    // Check php.ini location
    php_ini_loaded_file(),
    
    // Temporary file for testing
    __DIR__ . '/test_error.log'
];

// Create a test log file
$testLog = __DIR__ . '/test_error.log';
error_log("TEST: This is a test error message", 3, $testLog);
echo "<p>Created test log at: $testLog</p>";

foreach ($locations as $location) {
    if (file_exists($location)) {
        $size = filesize($location);
        echo "<h3>✅ Found: $location (Size: " . number_format($size) . " bytes)</h3>";
        
        // Read last 20 lines
        $lines = `tail -20 "$location" 2>nul`;
        if (!empty($lines)) {
            echo "<pre style='background:#f0f0f0;padding:10px;max-height:300px;overflow:auto;'>" . 
                 htmlspecialchars($lines) . "</pre>";
        } else {
            echo "<p>File is empty or cannot be read.</p>";
        }
    }
}

// Show PHP info
echo "<h2>PHP Configuration:</h2>";
echo "<p>PHP Version: " . phpversion() . "</p>";
echo "<p>Loaded php.ini: " . (php_ini_loaded_file() ?: 'Not found') . "</p>";

// Create a simple form to test
echo '<h2>Test Form Submission</h2>';
echo '<form method="POST">
    <input type="text" name="test_field" value="test">
    <button type="submit">Submit Test</button>
</form>';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    error_log("=== FORM SUBMITTED ===");
    error_log("POST Data: " . print_r($_POST, true));
    echo "<p>Form submitted. Check error logs above.</p>";
}
?>