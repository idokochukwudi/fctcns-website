<?php
/**
 * PARAMETER DIAGNOSTIC SCRIPT
 * 
 * Place this file in your web root (same directory as index.php)
 * Access it via: http://localhost/test_params.php?page=2&limit=25&search=test
 * 
 * This will help verify if PHP is correctly receiving URL parameters
 */

// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Start output
echo "<!DOCTYPE html>
<html>
<head>
    <title>Parameter Diagnostic Test</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        .section { margin: 20px 0; padding: 15px; border: 1px solid #ddd; border-radius: 5px; }
        .success { background: #d4edda; border-color: #c3e6cb; }
        .error { background: #f8d7da; border-color: #f5c6cb; }
        .info { background: #d1ecf1; border-color: #bee5eb; }
        pre { background: #f5f5f5; padding: 10px; border-radius: 3px; overflow-x: auto; }
        h2 { margin-top: 0; }
        .test-link { display: inline-block; margin: 5px; padding: 8px 15px; background: #007bff; color: white; text-decoration: none; border-radius: 3px; }
        .test-link:hover { background: #0056b3; }
    </style>
</head>
<body>
    <h1>🔍 Parameter Diagnostic Test</h1>
";

// Test 1: Check if $_GET is populated
echo "<div class='section " . (empty($_GET) ? "error" : "success") . "'>
    <h2>Test 1: \$_GET Array</h2>";

if (empty($_GET)) {
    echo "<p><strong>❌ FAIL:</strong> No GET parameters received!</p>";
    echo "<p>Try these test links:</p>";
    echo "<a href='?page=1' class='test-link'>Test: ?page=1</a>";
    echo "<a href='?page=2&limit=25' class='test-link'>Test: ?page=2&limit=25</a>";
    echo "<a href='?page=3&limit=50&search=test' class='test-link'>Test: ?page=3&limit=50&search=test</a>";
} else {
    echo "<p><strong>✅ PASS:</strong> GET parameters received successfully!</p>";
    echo "<pre>" . print_r($_GET, true) . "</pre>";
}

echo "</div>";

// Test 2: Check parameter reading with validation
echo "<div class='section info'>
    <h2>Test 2: Parameter Reading (Like Controller Should Do)</h2>";

$page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
$limit = isset($_GET['limit']) ? max(5, min(100, (int)$_GET['limit'])) : 10;
$search = isset($_GET['search']) ? trim($_GET['search']) : '';

echo "<table style='width:100%; border-collapse: collapse;'>
    <tr style='background:#f0f0f0;'>
        <th style='padding:8px; text-align:left; border:1px solid #ddd;'>Parameter</th>
        <th style='padding:8px; text-align:left; border:1px solid #ddd;'>Raw Value</th>
        <th style='padding:8px; text-align:left; border:1px solid #ddd;'>Processed Value</th>
    </tr>
    <tr>
        <td style='padding:8px; border:1px solid #ddd;'>\$_GET['page']</td>
        <td style='padding:8px; border:1px solid #ddd;'>" . ($_GET['page'] ?? 'NOT SET') . "</td>
        <td style='padding:8px; border:1px solid #ddd;'>$page</td>
    </tr>
    <tr>
        <td style='padding:8px; border:1px solid #ddd;'>\$_GET['limit']</td>
        <td style='padding:8px; border:1px solid #ddd;'>" . ($_GET['limit'] ?? 'NOT SET') . "</td>
        <td style='padding:8px; border:1px solid #ddd;'>$limit</td>
    </tr>
    <tr>
        <td style='padding:8px; border:1px solid #ddd;'>\$_GET['search']</td>
        <td style='padding:8px; border:1px solid #ddd;'>" . ($_GET['search'] ?? 'NOT SET') . "</td>
        <td style='padding:8px; border:1px solid #ddd;'>" . ($search ?: 'empty') . "</td>
    </tr>
</table>";

echo "</div>";

// Test 3: Calculate offset like the model should
echo "<div class='section info'>
    <h2>Test 3: SQL LIMIT/OFFSET Calculation</h2>";

$offset = ($page - 1) * $limit;

echo "<p><strong>Page:</strong> $page</p>";
echo "<p><strong>Limit:</strong> $limit</p>";
echo "<p><strong>Offset:</strong> $offset</p>";
echo "<p><strong>SQL would be:</strong></p>";
echo "<pre>SELECT * FROM nominal_roll_employees 
ORDER BY surname ASC 
LIMIT $limit OFFSET $offset;</pre>";

echo "<p><strong>Expected Records:</strong> ";
if ($page == 1) {
    echo "Records 1-$limit (Abdullahi, Adewale, etc.)";
} else {
    $start = $offset + 1;
    $end = $offset + $limit;
    echo "Records $start-$end";
}
echo "</p>";

echo "</div>";

// Test 4: Server variables
echo "<div class='section info'>
    <h2>Test 4: Server Variables</h2>
    <p><strong>REQUEST_URI:</strong> " . ($_SERVER['REQUEST_URI'] ?? 'NOT SET') . "</p>
    <p><strong>QUERY_STRING:</strong> " . ($_SERVER['QUERY_STRING'] ?? 'NOT SET') . "</p>
    <p><strong>REQUEST_METHOD:</strong> " . ($_SERVER['REQUEST_METHOD'] ?? 'NOT SET') . "</p>
</div>";

// Test 5: Generate pagination URLs
echo "<div class='section info'>
    <h2>Test 5: Pagination URL Generation</h2>";

function buildTestUrl($newPage, $currentLimit, $currentFilters) {
    $params = $currentFilters;
    $params['page'] = $newPage;
    $params['limit'] = $currentLimit;
    $params = array_filter($params, fn($v) => $v !== '' && $v !== null);
    return '/admin/nominal-roll?' . http_build_query($params);
}

$filters = [];
if (!empty($_GET['search'])) $filters['search'] = $_GET['search'];
if (!empty($_GET['state'])) $filters['state'] = $_GET['state'];

echo "<p><strong>Current page:</strong> $page</p>";
echo "<p><strong>Pagination URLs:</strong></p>";
echo "<ul>";
echo "<li>Page 1: <code>" . buildTestUrl(1, $limit, $filters) . "</code></li>";
echo "<li>Page 2: <code>" . buildTestUrl(2, $limit, $filters) . "</code></li>";
echo "<li>Page 3: <code>" . buildTestUrl(3, $limit, $filters) . "</code></li>";
echo "</ul>";

echo "</div>";

// Summary
echo "<div class='section " . (empty($_GET) ? "error" : "success") . "'>
    <h2>📋 Summary</h2>";

if (empty($_GET)) {
    echo "<p><strong>❌ Problem Detected:</strong> No parameters in \$_GET array</p>";
    echo "<p><strong>Likely Cause:</strong></p>";
    echo "<ul>
        <li>URL rewriting is stripping query parameters</li>
        <li>.htaccess is not preserving QUERY_STRING</li>
        <li>Web server configuration issue</li>
    </ul>";
    echo "<p><strong>Solution:</strong> Check your .htaccess file has <code>QSA</code> flag:</p>";
    echo "<pre>RewriteRule ^(.*)$ index.php?url=\$1 [QSA,L]</pre>";
} else {
    echo "<p><strong>✅ Parameters Working!</strong></p>";
    echo "<p>If pagination still doesn't work in your app, the problem is in the controller code not reading these parameters.</p>";
    echo "<p><strong>Make sure your controller has:</strong></p>";
    echo "<pre>\$page = isset(\$_GET['page']) ? max(1, (int)\$_GET['page']) : 1;
\$limit = isset(\$_GET['limit']) ? max(5, min(100, (int)\$_GET['limit'])) : 10;</pre>";
}

echo "</div>";

echo "</body></html>";
