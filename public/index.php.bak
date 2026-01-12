<?php
// ============================================================================
// ERROR REPORTING - ADD THIS AT THE VERY TOP
// ============================================================================

// Enable ALL error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
ini_set('log_errors', 1);

// Create application error log
$logDir = __DIR__ . '/../storage/logs';
if (!file_exists($logDir)) {
    mkdir($logDir, 0755, true);
}
$errorLogFile = $logDir . '/app_errors.log';
ini_set('error_log', $errorLogFile);

// Log request start
error_log("\n=== " . date('Y-m-d H:i:s') . " - Request: " . $_SERVER['REQUEST_METHOD'] . " " . $_SERVER['REQUEST_URI'] . " ===");

// Rest of your code...
/**
 * FCT College of Nursing Sciences - Main Entry Point
 * 
 * This is the single entry point for all requests.
 * All URLs are routed through this file.
 * 
 * @package FCT_CNS
 */

// ============================================================================
// BOOTSTRAP THE APPLICATION
// ============================================================================

// Start output buffering
ob_start();

// Set timezone
date_default_timezone_set('Africa/Lagos');

// ============================================================================
// LOAD CONSTANTS - FIXED FOR BOTH LOCAL AND PRODUCTION
// ============================================================================

// Get the root directory - SMART VERSION for both local and production
$possibleRootPaths = [
    // Production path (Go54 hosting)
    '/home2/fctcnsed/fctcns-app',
    // Local development path (XAMPP)
    dirname(__DIR__, 2), // Goes up 2 levels from public/ to project root
    // Fallback
    dirname(__DIR__),
];

$constantsLoaded = false;
foreach ($possibleRootPaths as $rootDir) {
    $constantsPath = $rootDir . '/app/config/constants.php';
    if (file_exists($constantsPath)) {
        require_once $constantsPath;
        $constantsLoaded = true;
        break;
    }
}

if (!$constantsLoaded) {
    die('ERROR: Could not load constants.php. Check file paths.');
}

// ============================================================================
// SESSION CONFIGURATION
// ============================================================================

if (session_status() === PHP_SESSION_NONE) {
    // Set cookie parameters BEFORE starting session
    session_set_cookie_params([
        'lifetime' => 0,
        'path' => '/',
        'domain' => '',
        'secure' => isset($_SERVER['HTTPS']),
        'httponly' => true,
        'samesite' => 'Strict'
    ]);
    
    // Start the session AFTER setting parameters
    session_start();
}

// ============================================================================
// AUTOLOAD CLASSES
// ============================================================================

// Simple autoloader
spl_autoload_register(function ($className) {
    // Convert namespace separators to directory separators
    $className = str_replace('\\', DIRECTORY_SEPARATOR, $className);
    
    // Possible locations
    $paths = [
        APP_PATH . '/controllers/' . $className . '.php',
        APP_PATH . '/models/' . $className . '.php',
        APP_PATH . '/core/' . $className . '.php',
        APP_PATH . '/lib/' . $className . '.php',
        $className . '.php' // For absolute paths
    ];
    
    foreach ($paths as $path) {
        if (file_exists($path)) {
            require_once $path;
            return;
        }
    }
    
    // For controller classes
    if (strpos($className, 'Controller') !== false) {
        $controllerFile = APP_PATH . '/controllers/' . $className . '.php';
        if (file_exists($controllerFile)) {
            require_once $controllerFile;
            return;
        }
    }
});

// Load Router
require_once APP_PATH . '/core/Router.php';

// ============================================================================
// CREATE ROUTER AND DEFINE ROUTES
// ============================================================================

try {
    // Create router instance
    $router = new Router();
    
    // ============================================================================
    // DEBUG ROUTE - ADDED FOR TROUBLESHOOTING
    // ============================================================================
    
    // Debug route
    $router->get('/debug-test', function() {
        echo "<h1>Debug Test Route</h1>";
        echo "<p>This route works!</p>";
        echo "<pre>";
        echo "REQUEST_URI: " . $_SERVER['REQUEST_URI'] . "\n";
        echo "SCRIPT_NAME: " . $_SERVER['SCRIPT_NAME'] . "\n";
        echo "PHP_SELF: " . $_SERVER['PHP_SELF'] . "\n";
        echo "</pre>";
    });
    
    // ============================================================================
    // PUBLIC ROUTES - Using Controller Methods
    // ============================================================================
    
    // Homepage - Use PageController
    $router->get('/', 'PageController@home');
    
    // Static pages
    $router->get('/about', 'PageController@about');
    $router->get('/programs', 'PageController@programs');
    $router->get('/admissions', 'PageController@admissions');
    $router->get('/research', 'PageController@research');
    $router->get('/contact', 'PageController@contact');
    
    // Additional pages
    $router->get('/news', 'PageController@news');
    $router->get('/faculty', 'PageController@faculty');
    $router->get('/alumni', 'PageController@alumni');
    $router->get('/student-life', 'PageController@studentLife');
    $router->get('/library', 'PageController@library');
    
    // Contact form submission
    $router->post('/contact/submit', 'PageController@submitContact');
    
    // ============================================================================
    // APPLICATION ROUTES - Scalable Multi-step Application
    // ============================================================================

    // Online application form (multi-step)
    $router->get('/apply', 'PublicApplicationController@showApplicationForm');
    $router->post('/apply/step/{step}', 'PublicApplicationController@processStep');
    $router->get('/apply/step/{step}', 'PublicApplicationController@showStep');
    $router->post('/apply/submit', 'PublicApplicationController@submitApplication');
    $router->get('/apply/success', 'PublicApplicationController@applicationSuccess');
    $router->get('/apply/reset', 'PublicApplicationController@resetApplication');
    
    // ============================================================================
    // ADMIN CONTACT MANAGEMENT ROUTES
    // ============================================================================
    
    // Admin contact management routes
    $router->get('/admin/contact', 'ContactController@index');
    $router->get('/admin/contact/view/{id}', 'ContactController@view');
    $router->post('/admin/contact/update/{id}', 'ContactController@update');
    $router->post('/admin/contact/delete/{id}', 'ContactController@delete');
    $router->get('/admin/contact/export', 'ContactController@export');
    $router->get('/admin/contact/settings', 'ContactController@settings');
    $router->post('/admin/contact/save-settings', 'ContactController@saveSettings');
    
    // Optional: Quick update routes
    $router->post('/admin/contact/quick-update/{id}', 'ContactController@quickUpdate');
    
    // ============================================================================
    // ADMIN CAROUSEL MANAGEMENT ROUTES
    // ============================================================================
    
    // Carousel management routes
    $router->get('/admin/carousel', 'AdminCarouselController@index');
    $router->get('/admin/carousel/create', 'AdminCarouselController@create');
    $router->post('/admin/carousel/store', 'AdminCarouselController@store');
    $router->get('/admin/carousel/edit/{id}', 'AdminCarouselController@edit');
    $router->post('/admin/carousel/update/{id}', 'AdminCarouselController@update');
    $router->post('/admin/carousel/delete/{id}', 'AdminCarouselController@delete');
    $router->post('/admin/carousel/toggle/{id}', 'AdminCarouselController@toggle');
    
    // ============================================================================
    // CAROUSEL IMAGE UPLOAD ROUTE - ADD THIS BEFORE ADMIN CATCH-ALL
    // ============================================================================
    
    // Image upload route for AJAX uploads
    $router->post('/admin/carousel/upload-image', 'AdminCarouselController@uploadImage');
    
    // ============================================================================
    // SETUP AND INSTALLATION ROUTES
    // ============================================================================
    
    $router->get('/setup', function() {
        $path = PUBLIC_PATH . '/pages/setup.php';
        if (file_exists($path)) {
            include $path;
        } else {
            echo "<h1>Setup</h1>";
        }
    });
    
    $router->get('/database/install', function() {
        $path = ROOT_PATH . '/database/install.php';
        if (file_exists($path)) {
            include $path;
        } else {
            echo "<h1>Database Installation</h1>";
        }
    });
    
    $router->get('/database/test', function() {
        $path = ROOT_PATH . '/database/test.php';
        if (file_exists($path)) {
            include $path;
        } else {
            echo "<h1>Database Test</h1>";
        }
    });
    
    // ============================================================================
    // ADMIN ROUTES
    // ============================================================================

    // Admin area - ALL admin routes go to the admin SPA index
    $router->get('/admin', function() {
        $path = APP_PATH . '/views/admin/index.php';
        if (file_exists($path)) {
            include $path;
        } else {
            echo "<h1>Admin Area</h1>";
        }
    });

    // IMPORTANT: Catch-all for admin SPA routes must come AFTER specific admin routes
    // This allows the specific contact routes above to work before falling back to SPA
    $router->get('/admin/(.*)', function($any) {
        $path = APP_PATH . '/views/admin/index.php';
        if (file_exists($path)) {
            include $path;
        } else {
            echo "<h1>Admin Area</h1>";
        }
    });

    $router->post('/admin/(.*)', function($any) {
        $path = APP_PATH . '/views/admin/index.php';
        if (file_exists($path)) {
            include $path;
        } else {
            echo "<h1>Admin Area</h1>";
        }
    });
    
    // ============================================================================
    // API ROUTES
    // ============================================================================
    
    $router->get('/api/carousel', function() {
        header('Content-Type: application/json');
        echo json_encode(['status' => 'success', 'message' => 'API endpoint']);
    });
    
    $router->get('/api/news/latest', function() {
        header('Content-Type: application/json');
        echo json_encode(['status' => 'success', 'data' => []]);
    });
    
    // ============================================================================
    // DISPATCH THE REQUEST
    // ============================================================================
    
    // Match current request to a route
    $match = $router->match();
    
    if ($match === null) {
        // No route matched - show 404
        http_response_code(404);
        
        // Try MVC 404 page first
        $errorPage = APP_PATH . '/views/pages/404.php';
        if (file_exists($errorPage)) {
            include $errorPage;
        } else {
            // Fall back to legacy 404 page
            $legacyErrorPage = PUBLIC_PATH . '/pages/404.php';
            if (file_exists($legacyErrorPage)) {
                include $legacyErrorPage;
            } else {
                // Default 404 page
                echo "<!DOCTYPE html>
                <html>
                <head>
                    <title>404 - Page Not Found</title>
                    <style>
                        body { font-family: Arial, sans-serif; padding: 40px; text-align: center; }
                        h1 { color: #6B4E9B; }
                    </style>
                </head>
                <body>
                    <h1>404 - Page Not Found</h1>
                    <p>The page you requested could not be found.</p>
                    <p><a href='" . BASE_URL . "'>Return to Homepage</a></p>
                </body>
                </html>";
            }
        }
        exit;
    } else {
        // Dispatch the matched route
        $router->dispatch($match);
    }
    
} catch (Exception $e) {
    // Handle routing errors
    error_log("Routing Error: " . $e->getMessage());
    
    if (defined('APP_DEBUG') && APP_DEBUG) {
        echo "<h1>Routing Error</h1>";
        echo "<p>" . htmlspecialchars($e->getMessage()) . "</p>";
        echo "<pre>" . htmlspecialchars($e->getTraceAsString()) . "</pre>";
    } else {
        http_response_code(500);
        echo "<h1>Internal Server Error</h1>";
        echo "<p>Please try again later.</p>";
    }
}

// Clean output buffer
if (ob_get_level() > 0) {
    ob_end_flush();
}
?>