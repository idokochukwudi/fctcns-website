<?php
/**
 * Router Class - Enhanced Version with Fixed Route Matching
 * 
 * Handles URL routing - maps URLs to controller methods
 * 
 * @package FCT_CNS
 */

class Router {
    
    /**
     * @var array $routes - Stores all registered routes
     */
    private $routes = [];

    /**
     * @var array $params - Stores route parameters
     */
    private $params = [];

    /**
     * Constructor - Register routes on initialization
     */
    public function __construct() {
        $this->registerRoutes();
    }

    /**
     * Register all application routes
     */
    private function registerRoutes() {
        // Register admission routes
        $this->get('/viewadmissionlist', 'AdmissionController@index');
        $this->get('/admission', 'AdmissionController@index');
        $this->get('/admission/search', 'AdmissionController@search');
        $this->get('/admission/check', 'AdmissionController@check');
        
        // Admin routes
        $this->get('/admin/admission/update', 'AdmissionController@adminUpdate');
        $this->post('/admin/admission/update', 'AdmissionController@adminUpdate');
        $this->get('/admin/admission/manual-correction', 'AdmissionController@manualCorrection');
        $this->post('/admin/admission/manual-correction', 'AdmissionController@manualCorrection');
        
        // Nominal Roll Routes
        $this->get('/admin/nominal-roll', 'NominalRollController@index');
        $this->get('/admin/nominal-roll/create', 'NominalRollController@create');
        $this->post('/admin/nominal-roll/store', 'NominalRollController@store');
        $this->get('/admin/nominal-roll/view/{id}', 'NominalRollController@view');
        $this->get('/admin/nominal-roll/edit/{id}', 'NominalRollController@edit');
        $this->post('/admin/nominal-roll/update/{id}', 'NominalRollController@update');
        $this->post('/admin/nominal-roll/delete/{id}', 'NominalRollController@destroy');
        $this->get('/admin/nominal-roll/bulk-upload', 'NominalRollController@bulkUpload');
        $this->post('/admin/nominal-roll/process-bulk-upload', 'NominalRollController@processBulkUpload');
        $this->get('/admin/nominal-roll/download-template', 'NominalRollController@downloadTemplate');
        $this->get('/admin/nominal-roll/export', 'NominalRollController@export');
        
        // PDF Export Routes - ADDED
        $this->get('/admin/nominal-roll/export/pdf/(:num)', 'NominalRollController@exportPdf/$1');
        $this->get('/admin/nominal-roll/export/pdf', 'NominalRollController@exportPdf');
        
        // PRINT ROUTES - ADDED
        // Standard print routes
        $this->get('/admin/nominal-roll/print/{id}', 'NominalRollController@printView');
        $this->get('/admin/nominal-roll/print', 'NominalRollController@printView'); // For query string

        // Direct print routes (auto-prints)
        $this->get('/admin/nominal-roll/print/direct/{id}', 'NominalRollController@printDirect');

        // Print with options
        $this->get('/admin/nominal-roll/print/with-audit/{id}', 'NominalRollController@printWithAudit');
        
        // QR CODE VERIFICATION ROUTES - ADDED
        $this->get('/verify/employee/{id}', 'NominalRollController@verifyEmployee');
        $this->get('/verify/document/{ref}', 'NominalRollController@verifyDocument');
        
        // Settings Routes
        $this->get('/admin/nominal-roll/settings', 'NominalRollController@settings');
        $this->post('/admin/nominal-roll/update-settings', 'NominalRollController@updateSettings');
        $this->post('/admin/nominal-roll/toggle-editing', 'NominalRollController@toggleEditing');
        
        // Drafts Routes
        $this->get('/admin/nominal-roll/drafts', 'NominalRollController@drafts');
        $this->post('/admin/nominal-roll/approve-draft/{id}', 'NominalRollController@approveDraft');
        
        // Backup Routes
        $this->post('/admin/nominal-roll/create-backup', 'NominalRollController@createBackup');
        $this->post('/admin/nominal-roll/restore-backup/{id}', 'NominalRollController@restoreBackup');
        $this->get('/admin/nominal-roll/download-backup/{id}', 'NominalRollController@downloadBackup');
        
        // Passport Photo Route
        $this->get('/admin/nominal-roll/passport-photo/{id}', 'NominalRollController@viewPassportPhoto');
        
        // ============================================
        // REPORTING ROUTES - UPDATED WITH FIXED EXPORT
        // ============================================
        $this->get('/admin/nominal-roll/reports', 'NominalRollController@reports');
        $this->post('/admin/nominal-roll/generate-report', 'NominalRollController@generateReport');
        $this->get('/admin/nominal-roll/report-preview', 'NominalRollController@reportPreview'); // ADDED
        $this->post('/admin/nominal-roll/save-report', 'NominalRollController@saveReport');
        $this->get('/admin/nominal-roll/load-report/{id}', 'NominalRollController@loadReport');
        $this->post('/admin/nominal-roll/delete-report/{id}', 'NominalRollController@deleteReport');
        
        // FIXED: Added POST route for export-excel (was missing)
        $this->post('/admin/nominal-roll/export-excel', 'NominalRollController@exportExcel');
        // Keep the original GET route too
        $this->get('/admin/nominal-roll/export-excel', 'NominalRollController@exportExcel');
        
        // FIXED: Added POST route for export-csv (was missing)
        $this->post('/admin/nominal-roll/export-csv', 'NominalRollController@exportCsv');
        // Keep the original GET route too
        $this->get('/admin/nominal-roll/export-csv', 'NominalRollController@exportCsv');
        
        // ============================================
        // NEW ROUTES FOR EXPORTING PREVIEW DATA - ADDED
        // ============================================
        $this->get('/admin/nominal-roll/export-preview-excel', 'NominalRollController@exportExcelFromPreview');
        $this->get('/admin/nominal-roll/export-preview-csv', 'NominalRollController@exportCsvFromPreview');
        
        // ============================================
        // NEW REPORT PREVIEW AJAX ROUTE - ADDED
        // ============================================
        $this->post('/admin/nominal-roll/generate-preview', 'NominalRollController@generatePreview');
        
        // User Management Routes - ADDED
        $this->get('/admin/users', 'UserManagementController@index');
        $this->get('/admin/users/create', 'UserManagementController@create');
        $this->post('/admin/users/store', 'UserManagementController@store');
        $this->get('/admin/users/view/{id}', 'UserManagementController@view');
        $this->get('/admin/users/edit/{id}', 'UserManagementController@edit');
        $this->post('/admin/users/update/{id}', 'UserManagementController@update');
        $this->post('/admin/users/delete/{id}', 'UserManagementController@destroy');
        $this->post('/admin/users/toggle-status/{id}', 'UserManagementController@toggleStatus');
        $this->post('/admin/users/reset-password/{id}', 'UserManagementController@resetPassword');
        $this->get('/admin/users/export', 'UserManagementController@export');
        $this->get('/admin/users/profile', 'UserManagementController@profile');
        $this->post('/admin/users/update-profile', 'UserManagementController@updateProfile');
        
        // Candidate admission check portal (simple page for candidates)
        // FIXED: Both GET and POST go to candidatePortal() method
        $this->get('/admission/check-portal', 'AdmissionController@candidatePortal');
        $this->post('/admission/check-portal', 'AdmissionController@candidatePortal');
        
        // AJAX API endpoint for checking status (optional - if you need separate API)
        // $this->post('/api/admission/check', 'AdmissionController@checkStatus');

        // Same page accessible from multiple URLs
        $this->get('/admissions/2025-2026', 'AdmissionController@index');
        $this->get('/admission-list', 'AdmissionController@index');
        
        // ============================================
        // ADD MISSING DEFAULT ROUTES FROM YOUR ERROR MESSAGE
        // ============================================
        $this->get('/', 'PageController@home');
        $this->get('/login', 'AuthController@login');
        $this->post('/login', 'AuthController@login');
        $this->get('/logout', 'AuthController@logout');
        $this->get('/dashboard', 'DashboardController@index');
        $this->get('/debug', 'DebugController@index');
        $this->get('/db-inspect', 'DebugController@dbInspect');
        $this->get('/db/create-tables', 'DebugController@createTables');
        
        // Applications routes
        $this->get('/admin/applications', 'ApplicationsController@index');
        $this->get('/admin/applications/create', 'ApplicationsController@create');
        $this->post('/admin/applications/store', 'ApplicationsController@store');
        $this->get('/admin/applications/view/{id}', 'ApplicationsController@view');
        $this->get('/admin/applications/edit/{id}', 'ApplicationsController@edit');
        $this->post('/admin/applications/update-status/{id}', 'ApplicationsController@updateStatus');
        
        // Research routes
        $this->get('/admin/research', 'ResearchController@index');
        $this->get('/admin/research/create', 'ResearchController@create');
        $this->get('/admin/research/edit/{id}', 'ResearchController@edit');
        $this->get('/admin/research/view/{id}', 'ResearchController@view');
        $this->post('/admin/research/store', 'ResearchController@store');
        $this->post('/admin/research/update/{id}', 'ResearchController@update');
        $this->post('/admin/research/toggle-status/{id}', 'ResearchController@toggleStatus');
        $this->post('/admin/research/bulk-action', 'ResearchController@bulkAction');
        $this->get('/admin/research/export', 'ResearchController@export');
        
        // News routes
        $this->get('/admin/news', 'NewsController@index');
        $this->get('/admin/news/create', 'NewsController@create');
        $this->post('/admin/news/store', 'NewsController@store');
        
        // 404 route - should be last
        $this->get('/404', 'PageController@notFound');
        
        if (defined('APP_DEBUG') && APP_DEBUG) {
            error_log("Router: All routes registered including Reporting, User Management, PDF export, Print routes, and QR Verification routes");
            error_log("Router: Export routes fixed - added POST methods for export-excel and export-csv");
            error_log("Router: Preview export routes added - export-preview-excel and export-preview-csv");
            error_log("Router: QR Verification routes added - /verify/employee/{id} and /verify/document/{ref}");
        }
    }

    /**
     * Add a GET route
     */
    public function get($path, $handler) {
        $this->addRoute('GET', $path, $handler);
    }

    /**
     * Add a POST route
     */
    public function post($path, $handler) {
        $this->addRoute('POST', $path, $handler);
    }

    /**
     * Add any route
     */
    private function addRoute($method, $path, $handler) {
        // Convert route to regex pattern using the FIXED version
        $pattern = $this->pathToRegex($path);
        
        $this->routes[] = [
            'method' => $method,
            'path' => $path,
            'pattern' => $pattern,
            'handler' => $handler
        ];
        
        if (defined('APP_DEBUG') && APP_DEBUG) {
            error_log("Route registered: $method $path -> " . (is_string($handler) ? $handler : 'Closure'));
        }
    }

    /**
     * Convert route path to regex pattern - FIXED VERSION
     * Enhanced to handle both normal paths and regex patterns correctly
     */
    private function pathToRegex($path) {
        // Special handling for root
        if ($path === '/') {
            return '#^/$#';
        }
        
        // DEBUG: Log what we're converting
        if (defined('APP_DEBUG') && APP_DEBUG) {
            error_log("Router: Converting path to regex: '$path'");
        }
        
        // Check if this is already a regex pattern (contains regex special chars)
        $isRegexPattern = false;
        if (strpos($path, '(') !== false && strpos($path, ')') !== false) {
            // Contains parentheses - likely a regex pattern
            $isRegexPattern = true;
        }
        if (strpos($path, '.*') !== false) {
            // Contains wildcard - definitely a regex pattern
            $isRegexPattern = true;
        }
        
        if ($isRegexPattern) {
            // This is already a regex pattern (like /admin/(.*))
            // Don't escape it with preg_quote!
            
            // Ensure it has proper delimiters
            $pattern = $path;
            
            // If it doesn't start with #^, add it
            if (strpos($pattern, '#^') !== 0) {
                // Check if it already starts with ^
                if (strpos($pattern, '^') === 0) {
                    $pattern = '#' . $pattern;
                } else {
                    $pattern = '#^' . $pattern;
                }
            }
            
            // If it doesn't end with $#, add it
            if (substr($pattern, -2) !== '$#') {
                // Check if it already ends with $
                if (substr($pattern, -1) === '$') {
                    $pattern .= '#';
                } else {
                    $pattern .= '$#';
                }
            }
            
            if (defined('APP_DEBUG') && APP_DEBUG) {
                error_log("Router: Treating as regex pattern: '$path' -> '$pattern'");
            }
            
            return $pattern;
        }
        
        // Normal path (like /about, /contact, /login, /dashboard etc.)
        // Escape regex special characters
        $pattern = preg_quote($path, '#');
        
        // Replace parameter placeholders with regex patterns
        // After preg_quote, curly braces are escaped, so we need to match \{ and \}
        $pattern = preg_replace('#\\\\\{([^}]+)\\\\}#', '([^/]+)', $pattern);
        
        // Allow for optional trailing slash
        $pattern = '#^' . $pattern . '/?$#';
        
        if (defined('APP_DEBUG') && APP_DEBUG) {
            error_log("Router: Converted normal path: '$path' -> '$pattern'");
        }
        
        return $pattern;
    }

    /**
     * Match current request to a route
     */
    public function match() {
        $requestMethod = $_SERVER['REQUEST_METHOD'];

        // Get clean request URI - SIMPLIFIED
        $requestUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        
        // Remove trailing slash (except for root)
        if ($requestUri !== '/') {
            $requestUri = rtrim($requestUri, '/');
        }
        
        if (defined('APP_DEBUG') && APP_DEBUG) {
            error_log("Router matching: $requestMethod $requestUri");
        }
        
        foreach ($this->routes as $route) {
            if ($route['method'] !== $requestMethod) {
                continue;
            }
            
            if (preg_match($route['pattern'], $requestUri, $matches)) {
                if (defined('APP_DEBUG') && APP_DEBUG) {
                    error_log("Route matched: {$route['path']}");
                    error_log("Route pattern: {$route['pattern']}");
                    error_log("Request URI: $requestUri");
                }
                
                array_shift($matches);
                $this->params = $matches;
                
                return [
                    'handler' => $route['handler'],
                    'params' => $matches,
                    'route' => $route
                ];
            }
        }
        
        if (defined('APP_DEBUG') && APP_DEBUG) {
            error_log("No route matched for: $requestUri");
        }
        return null;
    }

    /**
     * Get all registered routes (for debugging)
     */
    public function getRoutes() {
        return $this->routes;
    }

    /**
     * Get route parameters
     */
    public function getParams() {
        return $this->params;
    }

    /**
     * Dispatch the matched route
     */
    public function dispatch($match) {
        if ($match === null) {
            $this->notFound();
            return;
        }

        $handler = $match['handler'];
        $params = $match['params'] ?? [];

        try {
            if (is_callable($handler)) {
                // Call the closure directly
                call_user_func_array($handler, $params);
            } elseif (is_string($handler)) {
                // Check if it's a controller method
                if (strpos($handler, '@') !== false) {
                    list($controller, $method) = explode('@', $handler);
                    $this->callController($controller, $method, $params);
                } else {
                    // Assume it's a file path - render view
                    $this->renderView($handler, $params);
                }
            }
        } catch (Exception $e) {
            $this->handleError($e);
        }
    }

    /**
     * Call a controller method
     */
    private function callController($controller, $method, $params) {
        // Construct controller class name
        $controllerClass = ucfirst($controller);
        
        // Add Controller suffix if not present
        if (substr($controllerClass, -10) !== 'Controller') {
            $controllerClass .= 'Controller';
        }
        
        if (defined('APP_DEBUG') && APP_DEBUG) {
            error_log("Router: Loading controller: $controllerClass::$method()");
            error_log("Router: With params: " . print_r($params, true));
        }
        
        // Find controller file
        $controllerFile = APP_PATH . "/controllers/{$controllerClass}.php";
        
        if (!file_exists($controllerFile)) {
            // Try alternative naming
            $controllerFile = APP_PATH . "/controllers/{$controller}.php";
        }
        
        if (file_exists($controllerFile)) {
            require_once $controllerFile;
            
            // Check if class exists
            if (!class_exists($controllerClass)) {
                throw new Exception("Controller class not found: $controllerClass");
            }
            
            $instance = new $controllerClass();
            if (method_exists($instance, $method)) {
                call_user_func_array([$instance, $method], $params);
                return;
            } else {
                throw new Exception("Controller method not found: $controllerClass::$method()");
            }
        }

        throw new Exception("Controller file not found: $controllerFile");
    }

    /**
     * Render a view file
     */
    private function renderView($viewPath, $data = []) {
        // Try multiple possible locations
        $possiblePaths = [
            PUBLIC_PATH . '/' . $viewPath,
            APP_PATH . '/views/' . $viewPath,
            APP_PATH . '/views/pages/' . basename($viewPath),
            $viewPath,
        ];
        
        foreach ($possiblePaths as $fullPath) {
            if (file_exists($fullPath)) {
                extract($data);
                include $fullPath;
                return;
            }
        }
        
        throw new Exception("View not found: $viewPath");
    }

    /**
     * Handle 404 Not Found
     */
    private function notFound() {
        http_response_code(404);
        
        // Try to use MVC 404 page
        if (class_exists('PageController')) {
            try {
                $controller = new PageController();
                $controller->notFound();
                return;
            } catch (Exception $e) {
                // Fall through to default
            }
        }
        
        // Try to find 404 view
        $possiblePaths = [
            APP_PATH . '/views/pages/404.php',
            PUBLIC_PATH . '/pages/404.php',
        ];
        
        foreach ($possiblePaths as $errorPage) {
            if (file_exists($errorPage)) {
                include $errorPage;
                return;
            }
        }
        
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
            <p><a href='" . (defined('BASE_URL') ? BASE_URL : '/') . "'>Return to Homepage</a></p>
        </body>
        </html>";

        exit;
    }

    /**
     * Handle errors
     */
    private function handleError($exception) {
        error_log("Router Error: " . $exception->getMessage());
        
        if (defined('APP_DEBUG') && APP_DEBUG) {
            echo "<h1>Router Error</h1>";
            echo "<p>" . htmlspecialchars($exception->getMessage()) . "</p>";
            echo "<pre>" . htmlspecialchars($exception->getTraceAsString()) . "</pre>";
        } else {
            http_response_code(500);
            echo "<h1>Internal Server Error</h1>";
            echo "<p>Please try again later.</p>";
        }
        exit;
    }
    
    /**
     * Redirect helper method
     */
    public function redirect($url, $permanent = false) {
        if ($permanent) {
            header("HTTP/1.1 301 Moved Permanently");
        }
        header("Location: " . $url);
        exit;
    }
}