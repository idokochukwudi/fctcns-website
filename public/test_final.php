<?php
// Final test
echo "<h2>Final Test</h2>";

// Test paths
$root = dirname(__DIR__);
echo "Root: $root<br>";

// Try to load constants
$constantsPath = $root . '/app/config/constants.php';
echo "Constants path: $constantsPath<br>";
echo "Exists: " . (file_exists($constantsPath) ? 'YES' : 'NO') . "<br>";

if (file_exists($constantsPath)) {
    require_once $constantsPath;
    
    echo "BASE_URL: " . BASE_URL . "<br>";
    echo "APP_PATH: " . APP_PATH . "<br>";
    
    // Test Router
    $routerPath = APP_PATH . '/core/Router.php';
    echo "Router path: $routerPath<br>";
    echo "Exists: " . (file_exists($routerPath) ? 'YES' : 'NO') . "<br>";
    
    if (file_exists($routerPath)) {
        require_once $routerPath;
        echo "✓ Router class loaded successfully<br>";
    }
}
?>