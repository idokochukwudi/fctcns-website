<?php
/**
 * Admin Area - Main Entry Point
 * Routes all admin requests to appropriate controllers
 */
// DEBUG: Log what's happening
error_log("Admin SPA Loaded");
error_log("REQUEST_METHOD: " . $_SERVER['REQUEST_METHOD']);
error_log("REQUEST_URI: " . $_SERVER['REQUEST_URI']);
error_log("POST data: " . print_r($_POST, true));
error_log("Session: " . print_r($_SESSION, true));

// Load constants file - FIXED PATH
// Since this file is at: C:\xampp\htdocs\fctcns-website\app\views\admin\index.php
// We need to go up 3 levels to reach the project root
$projectRoot = dirname(__DIR__, 3); // This gives: C:\xampp\htdocs\fctcns-website
require_once $projectRoot . '/app/config/constants.php';

// Load environment
if (file_exists(ROOT_PATH . '/.env')) {
    $env_lines = file(ROOT_PATH . '/.env', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($env_lines as $line) {
        if (strpos(trim($line), '#') === 0 || empty(trim($line))) {
            continue;
        }
        list($key, $value) = explode('=', $line, 2);
        $_ENV[trim($key)] = trim($value);
    }
}

// Load session
require_once APP_PATH . '/config/session.php';

// Get request path
$request_uri = $_SERVER['REQUEST_URI'];
$path = parse_url($request_uri, PHP_URL_PATH);

// Remove base path - handle multiple possible paths
$possible_paths = [
    '/fctcns-website/public/admin',
    '/fctcns-website/admin',
    '/admin'
];

foreach ($possible_paths as $base_path) {
    if (strpos($path, $base_path) === 0) {
        $path = substr($path, strlen($base_path));
        break;
    }
}

// Ensure path starts with /
if (empty($path) || $path[0] !== '/') {
    $path = '/' . $path;
}

// Route the request
$path_parts = explode('/', trim($path, '/'));
$action = !empty($path_parts[0]) ? $path_parts[0] : 'login';
$param1 = $path_parts[1] ?? null;
$param2 = $path_parts[2] ?? null;

// Route mapping - COMPLETE VERSION WITH ALL ROUTES
$routes = [
    // Authentication
    '' => ['controller' => 'AdminController', 'method' => 'login'],
    'login' => ['controller' => 'AdminController', 'method' => 'login'],
    'logout' => ['controller' => 'AdminController', 'method' => 'logout'],
    'dashboard' => ['controller' => 'AdminController', 'method' => 'dashboard'],
    
    // Debug
    'debug' => ['controller' => 'AdminController', 'method' => 'debug'],
    'db-inspect' => ['controller' => 'AdminController', 'method' => 'dbInspect'],
    'db/create-tables' => ['controller' => 'AdminController', 'method' => 'dbCreateTables'],
    
    // Applications - COMPLETE AND CORRECT
    'applications' => ['controller' => 'ApplicationController', 'method' => 'index'],
    'applications/create' => ['controller' => 'ApplicationController', 'method' => 'create'],
    'applications/view' => ['controller' => 'ApplicationController', 'method' => 'view'],
    'applications/edit' => ['controller' => 'ApplicationController', 'method' => 'edit'],
    'applications/store' => ['controller' => 'ApplicationController', 'method' => 'store'],
    'applications/update-status' => ['controller' => 'ApplicationController', 'method' => 'updateStatus'],
    'applications/dashboard' => ['controller' => 'ApplicationController', 'method' => 'dashboard'],
    'applications/settings' => ['controller' => 'ApplicationController', 'method' => 'settings'],
    'applications/jamb-import' => ['controller' => 'ApplicationController', 'method' => 'jambImport'],
    'applications/terms' => ['controller' => 'ApplicationController', 'method' => 'terms'],
    'applications/payments' => ['controller' => 'ApplicationController', 'method' => 'payments'],
    
    // Research - COMPLETE WITH VIEW ROUTE
    'research' => ['controller' => 'ResearchController', 'method' => 'index'],
    'research/create' => ['controller' => 'ResearchController', 'method' => 'create'],
    'research/edit' => ['controller' => 'ResearchController', 'method' => 'edit'],
    'research/view' => ['controller' => 'ResearchController', 'method' => 'show'],
    'research/store' => ['controller' => 'ResearchController', 'method' => 'store'],
    'research/update' => ['controller' => 'ResearchController', 'method' => 'update'],
    'research/toggle-status' => ['controller' => 'ResearchController', 'method' => 'toggleStatus'],
    'research/bulk-action' => ['controller' => 'ResearchController', 'method' => 'bulkAction'],
    'research/export' => ['controller' => 'ResearchController', 'method' => 'export'],
    
    // News
    'news' => ['controller' => 'NewsController', 'method' => 'index'],
    'news/create' => ['controller' => 'NewsController', 'method' => 'create'],
    'news/store' => ['controller' => 'NewsController', 'method' => 'store'],
    
    // Users
    'users' => ['controller' => 'UserController', 'method' => 'index'],
    'users/create' => ['controller' => 'UserController', 'method' => 'create'],
    'users/store' => ['controller' => 'UserController', 'method' => 'store'],
    
    // 404 Fallback (must be last)
    '404' => ['controller' => 'AdminController', 'method' => 'notFound'],
];

// SPECIAL HANDLING FOR applications/view/{id}, applications/edit/{id}, research/edit/{id}, research/view/{id}
$route_key = $action;
if ($action == 'applications' && $param1 == 'view' && $param2) {
    // This is /admin/applications/view/{id}
    $route_key = 'applications/view';
    // Set the ID in GET parameters so ApplicationController can read it
    $_GET['id'] = $param2;
} elseif ($action == 'applications' && $param1 == 'edit' && $param2) {
    // This is /admin/applications/edit/{id}
    $route_key = 'applications/edit';
    $_GET['id'] = $param2;
} elseif ($action == 'research' && $param1 == 'edit' && $param2) {
    // This is /admin/research/edit/{id}
    $route_key = 'research/edit';
    $_GET['id'] = $param2;
} elseif ($action == 'research' && $param1 == 'view' && $param2) {
    // This is /admin/research/view/{id}
    $route_key = 'research/view';
    $_GET['id'] = $param2;
} else {
    // Normal route matching
    if ($param1) {
        $route_key = $action . '/' . $param1;
        if ($param2) {
            $route_key = $action . '/' . $param1 . '/' . $param2;
        }
    }
}

// Debug: Show what route is being looked for
if (defined('APP_DEBUG') && APP_DEBUG) {
    error_log("Admin Router: Looking for route key: '$route_key'");
    error_log("Admin Router: Available routes: " . implode(', ', array_keys($routes)));
}

// Check if route exists
if (isset($routes[$route_key])) {
    $route = $routes[$route_key];
    $controller_file = APP_PATH . '/controllers/' . $route['controller'] . '.php';
    
    if (file_exists($controller_file)) {
        require_once $controller_file;

        $controller_class = $route['controller'];
        $method = $route['method'];

        if (class_exists($controller_class) && method_exists($controller_class, $method)) {
            $controller = new $controller_class();
            $controller->$method();
            exit;
        } else {
            error_log("Admin Router: Method $method not found in $controller_class");
        }
    } else {
        error_log("Admin Router: Controller file not found: $controller_file");
    }
} else {
    error_log("Admin Router: Route not found: $route_key");
}

// Route not found - show 404
http_response_code(404);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 - Admin Area</title>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .error-container {
            background: white;
            border-radius: 16px;
            padding: 3rem;
            text-align: center;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            max-width: 500px;
            width: 100%;
        }

        h1 {
            font-size: 6rem;
            color: #e53e3e;
            margin: 0;
            line-height: 1;
        }

        h2 {
            color: #2d3748;
            margin: 1rem 0;
        }

        p {
            color: #718096;
            margin-bottom: 2rem;
        }

        .btn {
            display: inline-block;
            padding: 0.875rem 1.5rem;
            background: #2c5282;
            color: white;
            text-decoration: none;
            border-radius: 8px;
            font-weight: 600;
            transition: all 0.2s;
        }

        .btn:hover {
            background: #1a365d;
            transform: translateY(-1px);
        }

        .btn-secondary {
            background: #718096;
            margin-left: 10px;
        }

        .btn-secondary:hover {
            background: #4a5568;
        }
    </style>
</head>
<body>
    <div class="error-container">
        <h1>404</h1>
        <h2>Admin Page Not Found</h2>
        <p>The requested admin page "<?php echo htmlspecialchars($route_key); ?>" could not be found.</p>
        <p>Available routes: <?php echo implode(', ', array_keys($routes)); ?></p>
        <a href="<?php echo BASE_URL; ?>/admin/dashboard" class="btn">Go to Dashboard</a>
        <a href="<?php echo BASE_URL; ?>/admin/debug" class="btn btn-secondary">Debug</a>
    </div>
</body>
</html>