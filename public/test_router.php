<?php
// Test if requests are reaching the router
echo "<h2>Router Test</h2>";

echo "REQUEST_URI: " . $_SERVER['REQUEST_URI'] . "<br>";
echo "SCRIPT_NAME: " . $_SERVER['SCRIPT_NAME'] . "<br>";
echo "SCRIPT_FILENAME: " . $_SERVER['SCRIPT_FILENAME'] . "<br>";
echo "PATH_INFO: " . ($_SERVER['PATH_INFO'] ?? 'NOT SET') . "<br>";
echo "PHP_SELF: " . $_SERVER['PHP_SELF'] . "<br>";

// Check if .htaccess is working
echo "<hr>";
echo "<h3>.htaccess Test</h3>";

// Try to access this file via different URLs:
// https://fctcns.edu.ng/test_router.php (should work)
// https://fctcns.edu.ng/test_router (should NOT work if .htaccess is working)
// https://fctcns.edu.ng/test_router/test (should NOT work if .htaccess is working)

echo "<p>If .htaccess is working, accessing /test_router (without .php) should show this same content.</p>";
echo "<p>If .htaccess is NOT working, accessing /test_router will show 404.</p>";
?>