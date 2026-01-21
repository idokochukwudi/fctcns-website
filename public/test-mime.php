<?php
// test-mime.php - Place in public_html
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h2>Testing MIME Type Detection</h2>";

// Create a test CSV file
$csvContent = "Name,Email,Phone\nJohn,john@test.com,123456\nJane,jane@test.com,789012";
$tempFile = tempnam(sys_get_temp_dir(), 'test') . '.csv';
file_put_contents($tempFile, $csvContent);

// Test our detection method
function detectFileMimeType($filePath, $fileName) {
    $extension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
    
    $mimeMap = ['csv' => 'text/csv', 'txt' => 'text/plain'];
    
    if (isset($mimeMap[$extension])) {
        return $mimeMap[$extension];
    }
    
    if (function_exists('mime_content_type')) {
        return mime_content_type($filePath);
    }
    
    return 'text/csv'; // Default for CSV
}

echo "File: test.csv<br>";
echo "Detected MIME: " . detectFileMimeType($tempFile, 'test.csv') . "<br>";
echo "Function mime_content_type exists: " . (function_exists('mime_content_type') ? 'YES' : 'NO');

unlink($tempFile);
?>