<?php
/**
 * Base Controller Class
 * 
 * Provides common functionality for all controllers.
 * All application controllers should extend this class.
 * Handles view rendering, data passing, layout management.
 * 
 * @package FCT_CNS
 * @version 2.0
 */

class Controller {
    /**
     * @var array $data Data to pass to views
     */
    protected $data = [];
    
    /**
     * @var string $layout Layout template name
     */
    protected $layout = 'main';
    
    /**
     * @var string $view View name being rendered
     */
    protected $view;
    
    /**
     * Constructor
     * 
     * Initializes common controller functionality.
     */
    public function __construct() {
        // Session is already started by index.php - DO NOT start it here
        
        // Initialize default data
        $this->data = [
            'baseUrl' => defined('BASE_URL') ? BASE_URL : '/fctcns-website',
            'currentPage' => 'home',
            'csrf_token' => $this->csrfToken(),
        ];
    }
    
    /**
     * Render a view with optional layout
     * 
     * @param string|null $view View name (without .php extension)
     * @param array $data Additional data to pass to view
     * @throws Exception If view file not found
     */
    protected function render($view = null, $data = []) {
        // Use provided view or determine from called method
        $this->view = $view ?? $this->getDefaultViewName();
        
        // DEBUG
        error_log("Controller render: Trying to render view: '{$this->view}'");
        
        // Merge provided data with controller data
        $this->data = array_merge($this->data, $data);
        
        // Add flash messages to all views
        $this->data['flash_success'] = $this->getFlash('success');
        $this->data['flash_error'] = $this->getFlash('error');
        
        // Extract data for use in view
        extract($this->data);
        
        // Start output buffering to capture view content
        ob_start();
        
        // Find and include the view file
        $viewPath = $this->findViewFile($this->view);
        
        if (!$viewPath) {
            // Log error and throw exception
            error_log("View file not found: '{$this->view}'. Searched paths: " . 
                     implode(', ', $this->getViewPaths($this->view)));
            
            if (APP_DEBUG) {
                throw new Exception("View file not found: '{$this->view}'. Searched paths: " . 
                                   implode(', ', $this->getViewPaths($this->view)));
            } else {
                $this->notFound();
                return;
            }
        }
        
        // DEBUG: Log found view path
        error_log("Controller render: Found view at: $viewPath");
        
        // Include the view - variables from extract() are available
        include $viewPath;
        
        // Get the captured view content
        $content = ob_get_clean();
        
        // DEBUG: Log content length
        error_log("Controller render: View content captured (" . strlen($content) . " bytes)");
        
        // Store content in data for layout
        $this->data['content'] = $content;
        
        // Include layout if specified
        if ($this->layout) {
            $layoutPath = $this->findLayoutFile($this->layout);
            if ($layoutPath) {
                // DEBUG: Log layout path
                error_log("Controller render: Using layout: {$this->layout} at $layoutPath");
                
                // Pass data to layout via extract()
                extract($this->data);
                include $layoutPath;
            } else {
                // DEBUG: Log no layout found
                error_log("Controller render: Layout not found: {$this->layout}");
                
                // No layout found, output content directly
                echo $content;
            }
        } else {
            // DEBUG: Log no layout specified
            error_log("Controller render: No layout specified");
            
            // No layout specified, output content directly
            echo $content;
        }
    }
    
    /**
     * Find view file in multiple possible locations
     * 
     * @param string $view View name
     * @return string|false Full path to view file or false if not found
     */
    protected function findViewFile($view) {
        $possiblePaths = $this->getViewPaths($view);
        
        foreach ($possiblePaths as $path) {
            if (file_exists($path)) {
                // DEBUG: Log found path
                error_log("Controller findViewFile: Found '{$view}' at: $path");
                return $path;
            }
        }
        
        // DEBUG: Log not found
        error_log("Controller findViewFile: View '{$view}' not found in any path");
        return false;
    }
    
    /**
     * Generate list of possible view file paths
     * 
     * @param string $view View name
     * @return array List of possible file paths
     */
    protected function getViewPaths($view) {
        return [
            // Pages: home -> app/views/pages/home.php
            PAGES_PATH . '/' . $view . '.php',
            // Direct: home -> app/views/home.php
            VIEWS_PATH . '/' . $view . '.php',
            // Full path provided
            $view,
        ];
    }
    
    /**
     * Find layout file
     * 
     * @param string $layout Layout name
     * @return string|false Full path to layout file or false if not found
     */
    protected function findLayoutFile($layout) {
        $paths = [
            LAYOUTS_PATH . '/' . $layout . '.php',
            ADMIN_LAYOUTS_PATH . '/' . $layout . '.php',
            VIEWS_PATH . '/layouts/' . $layout . '.php',
        ];
        
        foreach ($paths as $path) {
            if (file_exists($path)) {
                // DEBUG: Log found layout path
                error_log("Controller findLayoutFile: Found layout '{$layout}' at: $path");
                return $path;
            }
        }
        
        // DEBUG: Log not found
        error_log("Controller findLayoutFile: Layout '{$layout}' not found in any path");
        return false;
    }
    
    /**
     * Get default view name based on called method
     * 
     * @return string Default view name
     */
    protected function getDefaultViewName() {
        // Get called method from backtrace
        $trace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 3);
        $method = $trace[2]['function'];
        
        // Convert camelCase to lowercase: homeAction -> home
        $method = preg_replace('/Action$/', '', $method);
        $viewName = strtolower(preg_replace('/(?<!^)[A-Z]/', '-$0', $method));
        
        // DEBUG: Log default view name determination
        error_log("Controller getDefaultViewName: Method '{$method}' -> view '{$viewName}'");
        
        return $viewName;
    }
    
    /**
     * Send JSON response
     * 
     * @param mixed $data Data to encode as JSON
     * @param int $statusCode HTTP status code
     */
    protected function json($data, $statusCode = 200) {
        http_response_code($statusCode);
        header('Content-Type: application/json');
        echo json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        exit;
    }
    
    /**
     * Redirect to another URL
     * 
     * @param string $url URL to redirect to
     * @param int $statusCode HTTP status code
     */
    protected function redirect($url, $statusCode = 302) {
        // DEBUG: Log redirect
        error_log("Controller redirect: Redirecting to: $url");
        
        // Convert relative URLs to absolute
        if (strpos($url, 'http') !== 0) {
            $url = rtrim($this->data['baseUrl'], '/') . '/' . ltrim($url, '/');
        }
        header("Location: $url", true, $statusCode);
        exit;
    }
    
    /**
     * Set a flash message for next request
     * 
     * @param string $key Message key
     * @param string $message Message content
     */
    protected function flash($key, $message) {
        if (!isset($_SESSION['flash'])) {
            $_SESSION['flash'] = [];
        }
        $_SESSION['flash'][$key] = $message;
    }
    
    /**
     * Get and clear a flash message
     * 
     * @param string $key Message key
     * @return string|null Message content or null if not set
     */
    protected function getFlash($key) {
        if (isset($_SESSION['flash'][$key])) {
            $message = $_SESSION['flash'][$key];
            unset($_SESSION['flash'][$key]);
            return $message;
        }
        return null;
    }
    
    /**
     * Get POST input value
     * 
     * @param string|null $key Input key
     * @param mixed $default Default value
     * @return mixed Input value
     */
    protected function input($key = null, $default = null) {
        if ($key === null) {
            return $_POST;
        }
        return $_POST[$key] ?? $default;
    }
    
    /**
     * Get GET query parameter
     * 
     * @param string|null $key Parameter key
     * @param mixed $default Default value
     * @return mixed Parameter value
     */
    protected function query($key = null, $default = null) {
        if ($key === null) {
            return $_GET;
        }
        return $_GET[$key] ?? $default;
    }
    
    /**
     * Generate CSRF token for forms
     * 
     * @return string CSRF token
     */
    protected function csrfToken() {
        if (!isset($_SESSION['csrf_tokens'])) {
            $_SESSION['csrf_tokens'] = [];
        }
        
        // Generate new token
        $token = bin2hex(random_bytes(32));
        
        // Store with timestamp (for expiration)
        $_SESSION['csrf_tokens'][$token] = time();
        
        // Clean up old tokens (older than 1 hour)
        $this->cleanupOldCsrfTokens();
        
        return $token;
    }
    
    /**
     * Validate CSRF token from POST data
     * 
     * @throws Exception If token is invalid
     */
    protected function validateCsrf() {
        // Check both possible field names
        $token = $this->input('_csrf_token') ?? $this->input('csrf_token');
        
        if (empty($token)) {
            throw new Exception('CSRF token is missing');
        }
        
        if (!isset($_SESSION['csrf_tokens'][$token])) {
            throw new Exception('Invalid CSRF token');
        }
        
        $tokenTime = $_SESSION['csrf_tokens'][$token];
        
        // Token expires after 1 hour (3600 seconds)
        if (time() - $tokenTime > 3600) {
            unset($_SESSION['csrf_tokens'][$token]);
            throw new Exception('CSRF token has expired');
        }
        
        // Remove token after single use (prevents replay attacks)
        unset($_SESSION['csrf_tokens'][$token]);
        
        return true;
    }
    
    /**
     * Clean up old CSRF tokens (older than 1 hour)
     */
    private function cleanupOldCsrfTokens() {
        if (isset($_SESSION['csrf_tokens'])) {
            foreach ($_SESSION['csrf_tokens'] as $token => $timestamp) {
                if (time() - $timestamp > 3600) {
                    unset($_SESSION['csrf_tokens'][$token]);
                }
            }
        }
    }
    
    /**
     * Check if request is AJAX
     * 
     * @return bool True if AJAX request
     */
    protected function isAjax() {
        return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && 
               strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
    }
    
    /**
     * Set HTTP status code
     * 
     * @param int $code HTTP status code
     * @return $this For method chaining
     */
    protected function status($code) {
        http_response_code($code);
        return $this;
    }
    
    /**
     * Set HTTP header
     * 
     * @param string $name Header name
     * @param string $value Header value
     * @return $this For method chaining
     */
    protected function header($name, $value) {
        header("$name: $value");
        return $this;
    }
    
    /**
     * Show 404 Not Found page
     */
    public function notFound() {
        $this->status(404);
        $this->data['page_title'] = '404 - Page Not Found';
        $this->render('404');
    }
    
    /**
     * Show 500 Internal Server Error page
     * 
     * @param Exception $exception Exception that caused the error
     */
    public function serverError($exception) {
        $this->status(500);
        $this->data = [
            'page_title' => '500 - Internal Server Error',
            'error' => APP_DEBUG ? $exception->getMessage() : 'An unexpected error occurred.',
            'trace' => APP_DEBUG ? $exception->getTraceAsString() : null,
        ];
        $this->render('500');
    }
}