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
        
        error_log("Router matching: $requestMethod $requestUri");
        
        foreach ($this->routes as $route) {
            if ($route['method'] !== $requestMethod) {
                continue;
            }
            
            if (preg_match($route['pattern'], $requestUri, $matches)) {
                error_log("Route matched: {$route['path']}");
                
                array_shift($matches);
                $this->params = $matches;
                
                return [
                    'handler' => $route['handler'],
                    'params' => $matches,
                    'route' => $route
                ];
            }
        }
        
        error_log("No route matched for: $requestUri");
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