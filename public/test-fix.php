// Create /test-fix.php in your public folder
<?php
require_once '../app/core/Router.php';

$router = new Router();

// Test the fixed pathToRegex
echo "<h3>Testing pathToRegex:</h3>";
echo "/about → " . htmlspecialchars($router->pathToRegex('/about')) . "<br>";
echo "/admin/(.*) → " . htmlspecialchars($router->pathToRegex('/admin/(.*)')) . "<br>";
echo "/ → " . htmlspecialchars($router->pathToRegex('/')) . "<br>";

// Test pattern matching
echo "<h3>Testing pattern matching:</h3>";
$pattern = $router->pathToRegex('/about');
$testUri = '/about';

if (preg_match($pattern, $testUri)) {
    echo "<span style='color:green'>✓ Pattern '$pattern' matches '$testUri'</span>";
} else {
    echo "<span style='color:red'>✗ Pattern '$pattern' does NOT match '$testUri'</span>";
}
?>