<?php
// Test how the ID flows through the system
echo "<h2>Debug: ID Flow Test</h2>";

// Simulate the URL: /admin/applications/view/1
$_SERVER['REQUEST_URI'] = '/fctcns-website/admin/applications/view/1';

// Parse the URL like your admin router does
$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// Remove base paths
$possible_paths = ['/fctcns-website/public/admin', '/fctcns-website/admin', '/admin'];
foreach ($possible_paths as $base_path) {
    if (strpos($path, $base_path) === 0) {
        $path = substr($path, strlen($base_path));
        break;
    }
}

echo "Parsed path: $path<br>";

// Get parts
$path_parts = explode('/', trim($path, '/'));
$action = !empty($path_parts[0]) ? $path_parts[0] : 'login';
$param1 = $path_parts[1] ?? null;
$param2 = $path_parts[2] ?? null;

echo "Action: $action<br>";
echo "Param1: $param1<br>";
echo "Param2: $param2<br>";

// Your special handling logic
if ($action == 'applications' && $param1 == 'view' && $param2) {
    echo "✓ Special handling triggered!<br>";
    echo "Setting \$_GET['id'] = $param2<br>";
    $_GET['id'] = $param2;
}

echo "<br>Current \$_GET: ";
print_r($_GET);
