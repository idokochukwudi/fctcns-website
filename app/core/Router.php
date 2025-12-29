<?php
/**
 * Router Class - Simplified Working Version
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
        // Convert route to regex pattern
        $pattern = $this->pathToRegex($path);
        
        $this->routes[] = [
            'method' => $method,
            'path' => $path,
            'pattern' => $pattern,
            'handler' => $handler
        ];
    }

    /**
     * Convert route path to regex pattern
     */
    private function pathToRegex($path) {
        // Special handling for root
        if ($path === '/') {
            return '#^/$#';
        }
        
        // If path contains regex patterns like (.*), we need to handle it differently
        // Check if it's a regex pattern by looking for parentheses
        if (strpos($path, '(') !== false || strpos($path, '[') !== false || strpos($path, '{') !== false) {
            // It's already a regex-like pattern
            // Use # as delimiter instead of / to avoid conflict with path slashes
            return '#^' . $path . '$#';
        }
        
        // For normal paths, escape special regex characters
        $pattern = preg_quote($path, '#');
        
        // Convert {param} to regex capture (if using that syntax)
        $pattern = preg_replace('#\\{([^}]+)\\}#', '([^/]+)', $pattern);
        
        return '#^' . $pattern . '$#';
    }

    /**
     * Match current request to a route
     */
    public function match() {
        $requestMethod = $_SERVER['REQUEST_METHOD'];
        $requestUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        
        // Remove /fctcns-website from the beginning if present
        $basePath = '/fctcns-website';
        if (strpos($requestUri, $basePath) === 0) {
            $requestUri = substr($requestUri, strlen($basePath));
        }
        
        // If empty path, set to home
        if ($requestUri === '' || $requestUri === '/') {
            $requestUri = '/';
        } else {
            // Remove trailing slash (except for homepage)
            $requestUri = rtrim($requestUri, '/');
        }
        
        // Debug logging
        if (defined('APP_DEBUG') && APP_DEBUG) {
            error_log("Router: Processing URI: '$requestUri', Method: '$requestMethod'");
        }
        
        foreach ($this->routes as $route) {
            // Check if method matches
            if ($route['method'] !== $requestMethod) {
                continue;
            }
            
            // Debug: Show what pattern is being tested
            if (defined('APP_DEBUG') && APP_DEBUG) {
                error_log("Router: Testing pattern: '{$route['pattern']}' against URI: '$requestUri'");
            }
            
            // Check if path matches pattern
            if (preg_match($route['pattern'], $requestUri, $matches)) {
                // Remove full match from matches array
                array_shift($matches);
                
                // Store parameters
                $this->params = $matches;
                
                if (defined('APP_DEBUG') && APP_DEBUG) {
                    error_log("Router: Matched route '{$route['path']}' with params: " . print_r($matches, true));
                }
                
                return [
                    'handler' => $route['handler'],
                    'params' => $matches
                ];
            }
        }
        
        if (defined('APP_DEBUG') && APP_DEBUG) {
            error_log("Router: No route matched for URI: '$requestUri'");
        }
        
        return null;
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
        $params = $match['params'];

        if (is_callable($handler)) {
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
    }

    /**
     * Call a controller method
     */
    private function callController($controller, $method, $params) {
        $controllerClass = ucfirst($controller) . 'Controller';
        $controllerFile = APP_PATH . "/controllers/{$controllerClass}.php";

        if (file_exists($controllerFile)) {
            require_once $controllerFile;

            if (class_exists($controllerClass)) {
                $instance = new $controllerClass();
                if (method_exists($instance, $method)) {
                    call_user_func_array([$instance, $method], $params);
                    return;
                }
            }
        }

        error_log("Controller not found: {$controllerClass}@{$method}");
        $this->notFound();
    }

    /**
     * Render a view file
     */
    private function renderView($viewPath, $data = []) {
        // Try multiple possible locations
        $possiblePaths = [
            PUBLIC_PATH . '/' . $viewPath,  // public/pages/home.php
            PUBLIC_PATH . '/pages/' . basename($viewPath), // public/pages/home.php (if viewPath is just 'home.php')
            APP_PATH . '/views/' . $viewPath, // app/views/pages/home.php
            APP_PATH . '/views/' . str_replace('pages/', 'admin/', $viewPath), // For admin views
        ];
        
        foreach ($possiblePaths as $fullPath) {
            if (file_exists($fullPath)) {
                extract($data);
                include $fullPath;
                return;
            }
        }
        
        // View not found
        if (defined('APP_DEBUG') && APP_DEBUG) {
            echo "<h3>View Not Found: $viewPath</h3>";
            echo "<p>Tried locations:</p><ul>";
            foreach ($possiblePaths as $path) {
                echo "<li>$path - " . (file_exists($path) ? "Exists" : "Not found") . "</li>";
            }
            echo "</ul>";
        } else {
            $this->notFound();
        }
    }

    /**
     * Handle 404 Not Found
     */
    private function notFound() {
        http_response_code(404);

        $errorPage = PUBLIC_PATH . '/pages/404.php';
        if (file_exists($errorPage)) {
            include $errorPage;
        } else {
            echo "<h1>404 - Page Not Found</h1>";
            echo "<p>The page you requested could not be found.</p>";
            echo "<p><a href='" . BASE_URL . "'>Return to Homepage</a></p>";
        }

        exit;
    }

    /**
     * Handle 500 Internal Server Error
     */
    public static function serverError($error) {
        http_response_code(500);

        if (APP_DEBUG) {
            echo "<h1>500 - Internal Server Error</h1>";
            echo "<pre>" . htmlspecialchars($error) . "</pre>";
        } else {
            echo "<h1>500 - Internal Server Error</h1>";
            echo "<p>Something went wrong. Please try again later.</p>";
        }

        exit;
    }
    
    /**
     * Debug method to see current path processing
     */
    public function debugCurrentPath() {
        $requestPath = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $scriptDir = dirname($_SERVER['SCRIPT_NAME']);
        
        echo "<pre style='background: #ffeb3b; padding: 10px;'>";
        echo "=== ROUTER PATH DEBUG ===\n";
        echo "REQUEST_URI: " . $_SERVER['REQUEST_URI'] . "\n";
        echo "Parsed Path: " . $requestPath . "\n";
        echo "SCRIPT_NAME: " . $_SERVER['SCRIPT_NAME'] . "\n";
        echo "Script Dir: " . $scriptDir . "\n";
        
        // What the router is doing
        if ($scriptDir !== '/' && strpos($requestPath, $scriptDir) === 0) {
            $processedPath = substr($requestPath, strlen($scriptDir));
            echo "After removing script dir: " . $processedPath . "\n";
        } else {
            echo "Script dir not removed (condition not met)\n";
        }
        
        echo "\nRegistered Routes:\n";
        foreach ($this->routes as $route) {
            echo "  " . $route['method'] . " " . $route['path'] . "\n";
        }
        echo "=== END DEBUG ===</pre>";
    }
}
?>