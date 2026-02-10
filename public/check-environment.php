<?php
/**
 * Environment Check Script - Helps debug both local and shared hosting
 */
echo "<h1>Environment Check</h1>";

// Server info
echo "<h2>Server Information</h2>";
echo "<pre>";
echo "Server Name: " . ($_SERVER['SERVER_NAME'] ?? 'N/A') . "\n";
echo "Document Root: " . ($_SERVER['DOCUMENT_ROOT'] ?? 'N/A') . "\n";
echo "Script Path: " . ($_SERVER['SCRIPT_FILENAME'] ?? 'N/A') . "\n";
echo "Current Dir: " . __DIR__ . "\n";
echo "PHP Version: " . phpversion() . "\n";
echo "</pre>";

// Upload settings
echo "<h2>PHP Upload Settings</h2>";
echo "<pre>";
echo "upload_max_filesize: " . ini_get('upload_max_filesize') . "\n";
echo "post_max_size: " . ini_get('post_max_size') . "\n";
echo "max_file_uploads: " . ini_get('max_file_uploads') . "\n";
echo "memory_limit: " . ini_get('memory_limit') . "\n";
echo "</pre>";

// Directory check
echo "<h2>Directory Check</h2>";
$dirs = [
    '/uploads/',
    '/uploads/news/',
    '/uploads/events/',
    '/public/uploads/',
    '/public/uploads/news/'
];

foreach ($dirs as $dir) {
    $fullPath = $_SERVER['DOCUMENT_ROOT'] . $dir;
    echo "<p>" . $dir . " - ";
    echo "Exists: " . (is_dir($fullPath) ? '✅' : '❌') . " - ";
    echo "Writable: " . (is_writable($fullPath) ? '✅' : '❌');
    echo "</p>";
}

// Test file paths
echo "<h2>Test File Path Generation</h2>";
echo "<pre>";

// Test getUploadPath
if (!function_exists('getUploadPath')) {
    function getUploadPath($type = 'news') {
        $possiblePaths = [
            $_SERVER['DOCUMENT_ROOT'] . '/uploads/' . $type . '/',
            dirname($_SERVER['DOCUMENT_ROOT'], 1) . '/public/uploads/' . $type . '/',
            dirname(__DIR__, 2) . '/public/uploads/' . $type . '/',
        ];
        
        foreach ($possiblePaths as $path) {
            echo "Checking: $path - ";
            if (is_dir($path) || is_writable(dirname($path))) {
                echo "✅ SELECTED\n";
                return $path;
            }
            echo "❌\n";
        }
        
        return $_SERVER['DOCUMENT_ROOT'] . '/uploads/' . $type . '/';
    }
}

echo "\nUpload path for 'news': " . getUploadPath('news');
echo "\nUpload path for 'events': " . getUploadPath('events');

echo "</pre>";

// Quick upload test form
echo "<h2>Quick Upload Test</h2>";
echo '<form method="POST" enctype="multipart/form-data" action="?test=upload">';
echo '<input type="file" name="test_file" accept="image/*">';
echo '<button type="submit">Test Upload</button>';
echo '</form>';

if (isset($_GET['test']) && $_GET['test'] === 'upload' && isset($_FILES['test_file'])) {
    echo "<h3>Upload Test Results</h3>";
    
    $type = 'news';
    $uploadDir = getUploadPath($type);
    
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0755, true);
    }
    
    $filename = 'test_' . time() . '_' . basename($_FILES['test_file']['name']);
    $destination = rtrim($uploadDir, '/') . '/' . $filename;
    
    if (move_uploaded_file($_FILES['test_file']['tmp_name'], $destination)) {
        echo "<p style='color: green;'>✅ Upload successful!</p>";
        echo "<p>Saved to: $destination</p>";
        echo "<p>Relative path: /uploads/$type/$filename</p>";
        
        // Test URL generation
        $baseUrl = 'http://' . $_SERVER['HTTP_HOST'];
        $url = $baseUrl . '/uploads/' . $type . '/' . $filename;
        echo "<p>Access URL: <a href='$url' target='_blank'>$url</a></p>";
        echo "<img src='$url' style='max-width: 300px;'>";
    } else {
        echo "<p style='color: red;'>❌ Upload failed</p>";
        echo "<p>Error: " . error_get_last()['message'] . "</p>";
    }
}
?>