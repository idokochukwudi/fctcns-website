<?php
/**
 * Test Route Registration
 * Access: http://yourdomain.com/test_routes.php
 */

require_once 'app/core/Router.php';

$router = new Router();
$routes = $router->getRoutes();

echo "<h1>Testing QR Routes</h1>";
echo "<table border='1' cellpadding='8'>";
echo "<tr><th>Method</th><th>Path</th><th>Handler</th><th>Status</th></tr>";

$qrRoutes = [
    '/application-verify/generate-qr/{slipNumber}',
    '/application-verify/qr/{slipNumber}'
];

foreach ($routes as $route) {
    $isQrRoute = in_array($route['path'], $qrRoutes);
    $rowColor = $isQrRoute ? 'style="background-color: #d4edda;"' : '';
    
    echo "<tr $rowColor>";
    echo "<td>" . $route['method'] . "</td>";
    echo "<td>" . htmlspecialchars($route['path']) . "</td>";
    echo "<td>" . (is_string($route['handler']) ? $route['handler'] : 'Closure') . "</td>";
    echo "<td>" . ($isQrRoute ? '✅ FOUND' : '') . "</td>";
    echo "</tr>";
}

echo "</table>";

// Test direct access
$testSlip = 'SLIP-2025-00001';
echo "<h2>Test Direct Access</h2>";
echo "<ul>";
echo "<li><a href='/application-verify/generate-qr/" . $testSlip . "' target='_blank'>Test generate-qr route</a></li>";
echo "<li><a href='/application-verify/qr/" . $testSlip . "' target='_blank'>Test qr route</a></li>";
echo "<li><a href='/router-test' target='_blank'>Router Test Page</a></li>";
echo "</ul>";