<?php
echo "<h1>Path Test</h1>";

echo "<p>Current directory: " . __DIR__ . "</p>";
echo "<p>Parent directory: " . dirname(__DIR__) . "</p>";

$headerPath = dirname(__DIR__) . '/includes/header.php';
echo "<p>Header path: " . $headerPath . "</p>";
echo "<p>Header exists: " . (file_exists($headerPath) ? 'YES' : 'NO') . "</p>";

$footerPath = dirname(__DIR__) . '/includes/footer.php';
echo "<p>Footer path: " . $footerPath . "</p>";
echo "<p>Footer exists: " . (file_exists($footerPath) ? 'YES' : 'NO') . "</p>";

// Try to include
if (file_exists($headerPath)) {
    echo "<p style='color: green;'>✓ Header file found!</p>";
    include $headerPath;
} else {
    echo "<p style='color: red;'>✗ Header file NOT found</p>";
}
?>