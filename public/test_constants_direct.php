<?php
echo "<h2>Direct Constants Test</h2>";

// Test 1: Try the path from your original index.php
$path1 = dirname(__DIR__) . '/app/config/constants.php';
echo "Test 1 (dirname __DIR__):<br>";
echo "Path: $path1<br>";
echo "Exists: " . (file_exists($path1) ? '✅ YES' : '❌ NO') . "<br>";

// Test 2: Try production app path
$path2 = '/home2/fctcnsed/fctcns-app/app/config/constants.php';
echo "<br>Test 2 (Production path):<br>";
echo "Path: $path2<br>";
echo "Exists: " . (file_exists($path2) ? '✅ YES' : '❌ NO') . "<br>";

// Test 3: Try local-style path (2 levels up)
$path3 = dirname(__DIR__, 2) . '/app/config/constants.php';
echo "<br>Test 3 (2 levels up):<br>";
echo "Path: $path3<br>";
echo "Exists: " . (file_exists($path3) ? '✅ YES' : '❌ NO') . "<br>";

// Try to load if any exists
$loaded = false;
foreach ([$path1, $path2, $path3] as $path) {
    if (file_exists($path)) {
        echo "<br>⏳ Loading: $path<br>";
        require_once $path;
        $loaded = true;
        
        // Check if constants were defined
        echo "BASE_URL defined: " . (defined('BASE_URL') ? '✅ YES = ' . BASE_URL : '❌ NO') . "<br>";
        echo "ASSETS_URL defined: " . (defined('ASSETS_URL') ? '✅ YES = ' . ASSETS_URL : '❌ NO') . "<br>";
        break;
    }
}

if (!$loaded) {
    echo "<br>❌ ERROR: Could not find constants.php at any path!";
}
?>