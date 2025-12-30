#!/bin/bash

echo "í´§ Fixing Admin Router to Handle /applications/view/{id}"
echo "=========================================================="

# Backup the file
cp app/views/admin/index.php app/views/admin/index.php.backup

echo "1. Current routes array in admin/index.php:"
grep -n "'applications/view'" app/views/admin/index.php

echo ""
echo "2. Adding support for applications/view/{id} pattern..."

# Create the fixed file
cat > temp-fix.php << 'PHPFIX'
<?php
/**
 * Admin Area - Main Entry Point
 * Routes all admin requests to appropriate controllers
 */

// Load constants file - FIXED PATH
// Since this file is at: C:\xampp\htdocs\fctcns-website\app\views\admin\index.php
// We need to go up 3 levels to reach the project root
\$projectRoot = dirname(__DIR__, 3); // This gives: C:\xampp\htdocs\fctcns-website
require_once \$projectRoot . '/app/config/constants.php';

// Load environment
if (file_exists(ROOT_PATH . '/.env')) {
    \$env_lines = file(ROOT_PATH . '/.env', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach (\$env_lines as \$line) {
        if (strpos(trim(\$line), '#') === 0 || empty(trim(\$line))) {
            continue;
        }
        list(\$key, \$value) = explode('=', \$line, 2);
        \$_ENV[trim(\$key)] = trim(\$value);
    }
}

// Load session
require_once APP_PATH . '/config/session.php';

// Get request path
\$request_uri = \$_SERVER['REQUEST_URI'];
\$path = parse_url(\$request_uri, PHP_URL_PATH);

// Remove base path - handle multiple possible paths
\$possible_paths = [
    '/fctcns-website/public/admin',
    '/fctcns-website/admin',
    '/admin'
];

foreach (\$possible_paths as \$base_path) {
    if (strpos(\$path, \$base_path) === 0) {
        \$path = substr(\$path, strlen(\$base_path));
        break;
    }
}

// Ensure path starts with /
if (empty(\$path) || \$path[0] !== '/') {
    \$path = '/' . \$path;
}

// Route the request
\$path_parts = explode('/', trim(\$path, '/'));
\$action = !empty(\$path_parts[0]) ? \$path_parts[0] : 'login';
\$param1 = \$path_parts[1] ?? null;
\$param2 = \$path_parts[2] ?? null;

// Route mapping - COMPLETE VERSION WITH ALL ROUTES
\$routes = [
    // Authentication
    '' => ['controller' => 'AdminController', 'method' => 'login'],
    'login' => ['controller' => 'AdminController', 'method' => 'login'],
    'logout' => ['controller' => 'AdminController', 'method' => 'logout'],
    'dashboard' => ['controller' => 'AdminController', 'method' => 'dashboard'],
    
    // Debug
    'debug' => ['controller' => 'AdminController', 'method' => 'debug'],
    'db-inspect' => ['controller' => 'AdminController', 'method' => 'dbInspect'],
    'db/create-tables' => ['controller' => 'AdminController', 'method' => 'dbCreateTables'],
    
    // Applications
    'applications' => ['controller' => 'ApplicationController', 'method' => 'index'],
    'applications/create' => ['controller' => 'ApplicationController', 'method' => 'create'],
    'applications/view' => ['controller' => 'ApplicationController', 'method' => 'view'],
    'applications/store' => ['controller' => 'ApplicationController', 'method' => 'store'],
    'applications/update-status' => ['controller' => 'ApplicationController', 'method' => 'updateStatus'],
    
    // Add these dynamic routes for applications
    'applications/view/{id}' => ['controller' => 'ApplicationController', 'method' => 'view'],
    'applications/edit/{id}' => ['controller' => 'ApplicationController', 'method' => 'edit'],
    'applications/delete/{id}' => ['controller' => 'ApplicationController', 'method' => 'destroy'],
    
    // Research
    'research' => ['controller' => 'ResearchController', 'method' => 'index'],
    'research/create' => ['controller' => 'ResearchController', 'method' => 'create'],
    'research/edit' => ['controller' => 'ResearchController', 'method' => 'edit'],
    'research/store' => ['controller' => 'ResearchController', 'method' => 'store'],
    'research/update' => ['controller' => 'ResearchController', 'method' => 'update'],
    
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

// Find matching route with parameter support
\$route_key = \$action;
\$route_params = [];

// Check for patterns with {id}
if (\$param1) {
    // Try exact match first
    \$exact_key = \$action . '/' . \$param1;
    if (isset(\$routes[\$exact_key])) {
        \$route_key = \$exact_key;
    } else if (\$param2) {
        // Try pattern with id: applications/view/{id}
        \$pattern_key = \$action . '/' . \$param1 . '/{id}';
        if (isset(\$routes[\$pattern_key])) {
            \$route_key = \$pattern_key;
            \$route_params['id'] = \$param2;
        }
    }
}

// Debug: Show what route is being looked for
if (defined('APP_DEBUG') && APP_DEBUG) {
    error_log("Admin Router: Looking for route key: '\$route_key'");
    error_log("Admin Router: Route params: " . print_r(\$route_params, true));
}

// Check if route exists
if (isset(\$routes[\$route_key])) {
    \$route = \$routes[\$route_key];
    \$controller_file = APP_PATH . '/controllers/' . \$route['controller'] . '.php';
    
    if (file_exists(\$controller_file)) {
        require_once \$controller_file;

        \$controller_class = \$route['controller'];
        \$method = \$route['method'];

        if (class_exists(\$controller_class) && method_exists(\$controller_class, \$method)) {
            \$controller = new \$controller_class();
            
            // Pass parameters to method
            if (!empty(\$route_params)) {
                if (\$method == 'view' || \$method == 'edit' || \$method == 'destroy') {
                    // For methods that expect an ID parameter
                    \$controller->\$method(\$route_params['id']);
                } else {
                    \$controller->\$method();
                }
            } else {
                \$controller->\$method();
            }
            exit;
        } else {
            error_log("Admin Router: Method \$method not found in \$controller_class");
        }
    } else {
        error_log("Admin Router: Controller file not found: \$controller_file");
    }
} else {
    error_log("Admin Router: Route not found: \$route_key");
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
    </style>
</head>
<body>
    <div class="error-container">
        <h1>404</h1>
        <h2>Admin Page Not Found</h2>
        <p>The requested admin page "<?php echo htmlspecialchars(\$route_key); ?>" could not be found.</p>
        <p>Available routes: <?php echo implode(', ', array_keys(\$routes)); ?></p>
        <a href="<?php echo BASE_URL; ?>/admin/dashboard" class="btn">Go to Dashboard</a>
        <a href="<?php echo BASE_URL; ?>/admin/debug" class="btn" style="background: #718096; margin-left: 10px;">Debug</a>
    </div>
</body>
</html>
PHPFIX

# Apply the fix
cp temp-fix.php app/views/admin/index.php
rm temp-fix.php

echo "âœ… Fixed! The admin router now supports:"
echo "   â€¢ /admin/applications/view/{id}"
echo "   â€¢ /admin/applications/edit/{id}" 
echo "   â€¢ /admin/applications/delete/{id}"
echo ""
echo "í³‹ Test these URLs:"
echo "1. http://localhost/fctcns-website/admin/applications/view/1"
echo "2. http://localhost/fctcns-website/admin/applications/edit/1"
echo "3. http://localhost/fctcns-website/admin/applications"
