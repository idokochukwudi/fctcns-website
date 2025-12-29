<?php
// Simple admin test
echo "<h1>Admin System Test</h1>";

// Check if constants are defined
echo "<h3>Constants Check:</h3>";
echo "ROOT_PATH: " . (defined('ROOT_PATH') ? ROOT_PATH : 'NOT DEFINED') . "<br>";
echo "APP_PATH: " . (defined('APP_PATH') ? APP_PATH : 'NOT DEFINED') . "<br>";
echo "BASE_URL: " . (defined('BASE_URL') ? BASE_URL : 'NOT DEFINED') . "<br>";

// Test links
echo "<h3>Test Links:</h3>";
echo "<p><a href='http://localhost/fctcns-website/admin'>Admin Login</a></p>";
echo "<p><a href='http://localhost/fctcns-website/logout.php'>Direct Logout</a></p>";

// Check admin router
echo "<h3>Check Admin Router:</h3>";
$admin_index = __DIR__ . '/app/views/admin/index.php';
if (file_exists($admin_index)) {
    echo "Admin router exists at: $admin_index<br>";
    
    // Check first few lines
    $lines = file($admin_index, FILE_IGNORE_NEW_LINES);
    echo "First 15 lines:<pre>";
    for ($i = 0; $i < 15 && $i < count($lines); $i++) {
        echo ($i+1) . ": " . htmlspecialchars($lines[$i]) . "\n";
    }
    echo "</pre>";
} else {
    echo "Admin router NOT FOUND!";
}
?>