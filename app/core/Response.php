<?php
/**
 * Response Class
 * 
 * Handles HTTP responses (redirects, JSON, views, etc.)
 * Provides a fluent interface for building responses
 * 
 * @package FCT_CNS
 */

class Response {
    
    /**
     * @var int HTTP status code
     */
    private $statusCode = 200;
    
    /**
     * @var array HTTP headers
     */
    private $headers = [];
    
    /**
     * @var mixed Response data
     */
    private $data;
    
    /**
     * Create new response instance
     */
    public function __construct($data = null, $statusCode = 200) {
        $this->data = $data;
        $this->statusCode = $statusCode;
    }
    
    /**
     * Redirect to a URL
     */
    public static function redirect($url, $statusCode = 302) {
        if (strpos($url, 'http') !== 0 && $url[0] !== '/') {
            $url = BASE_URL . '/' . ltrim($url, '/');
        }
        
        if (!headers_sent()) {
            header("Location: $url", true, $statusCode);
        } else {
            echo "<script>window.location.href='$url';</script>";
        }
        exit;
    }
    
    /**
     * Redirect back to previous page
     */
    public static function back() {
        $referrer = Request::referrer();
        if (!empty($referrer)) {
            self::redirect($referrer);
        } else {
            self::redirect(BASE_URL);
        }
    }
    
    /**
     * Redirect to route
     */
    public static function route($route, $params = []) {
        // Simple route URL generation
        $url = BASE_URL . '/' . ltrim($route, '/');
        
        if (!empty($params)) {
            $url .= '?' . http_build_query($params);
        }
        
        self::redirect($url);
    }
    
    /**
     * Set HTTP status code
     */
    public function status($code) {
        $this->statusCode = $code;
        return $this;
    }
    
    /**
     * Set HTTP header
     */
    public function header($name, $value) {
        $this->headers[$name] = $value;
        return $this;
    }
    
    /**
     * Send JSON response
     */
    public static function json($data, $statusCode = 200) {
        http_response_code($statusCode);
        header('Content-Type: application/json; charset=utf-8');
        
        $options = JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES;
        
        if (defined('APP_DEBUG') && APP_DEBUG) {
            $options |= JSON_PRETTY_PRINT;
        }
        
        echo json_encode($data, $options);
        exit;
    }
    
    /**
     * Send JSON response from instance
     */
    public function jsonResponse() {
        return self::json($this->data, $this->statusCode);
    }
    
    /**
     * Send plain text response
     */
    public static function text($text, $statusCode = 200) {
        http_response_code($statusCode);
        header('Content-Type: text/plain; charset=utf-8');
        echo $text;
        exit;
    }
    
    /**
     * Send HTML response
     */
    public static function html($html, $statusCode = 200) {
        http_response_code($statusCode);
        header('Content-Type: text/html; charset=utf-8');
        echo $html;
        exit;
    }
    
    /**
     * Send view response
     */
    public static function view($view, $data = [], $statusCode = 200) {
        // Extract data for view
        extract($data);
        
        // Find view file
        $viewPath = APP_PATH . '/views/' . $view . '.php';
        
        if (!file_exists($viewPath)) {
            throw new Exception("View not found: $view");
        }
        
        // Start output buffering
        ob_start();
        
        // Include view
        include $viewPath;
        
        // Get content
        $content = ob_get_clean();
        
        // Send HTML response
        return self::html($content, $statusCode);
    }
    
    /**
     * Set flash message and redirect
     */
    public static function withMessage($type, $message, $url = null) {
        // Use Session class if available
        if (class_exists('Session')) {
            Session::setFlash($type, $message);
        } else {
            // Fallback to direct session
            if (!isset($_SESSION['flash'])) {
                $_SESSION['flash'] = [];
            }
            $_SESSION['flash'][$type] = $message;
        }
        
        if ($url) {
            self::redirect($url);
        } else {
            self::back();
        }
    }
    
    /**
     * Send success response
     */
    public static function success($message, $data = null, $redirect = null) {
        if (Request::isAjax() || Request::expectsJson()) {
            self::json([
                'success' => true,
                'message' => $message,
                'data' => $data
            ]);
        } elseif ($redirect) {
            self::withMessage('success', $message, $redirect);
        } else {
            self::withMessage('success', $message);
        }
    }
    
    /**
     * Send error response
     */
    public static function error($message, $errors = [], $statusCode = 400, $redirect = null) {
        if (Request::isAjax() || Request::expectsJson()) {
            self::json([
                'success' => false,
                'message' => $message,
                'errors' => $errors
            ], $statusCode);
        } elseif ($redirect) {
            self::withMessage('error', $message, $redirect);
        } else {
            self::withMessage('error', $message);
        }
    }
    
    /**
     * Send validation error response
     */
    public static function validationError($errors, $message = 'Validation failed') {
        return self::error($message, $errors, 422);
    }
    
    /**
     * Send not found response
     */
    public static function notFound($message = 'Resource not found') {
        if (Request::isAjax() || Request::expectsJson()) {
            return self::error($message, [], 404);
        }
        
        http_response_code(404);
        
        $errorView = APP_PATH . '/views/pages/404.php';
        if (file_exists($errorView)) {
            include $errorView;
        } else {
            echo "<h1>404 - Not Found</h1>";
            echo "<p>$message</p>";
        }
        exit;
    }
    
    /**
     * Send unauthorized response
     */
    public static function unauthorized($message = 'Unauthorized') {
        if (Request::isAjax() || Request::expectsJson()) {
            return self::error($message, [], 401);
        }
        
        http_response_code(401);
        
        $errorView = APP_PATH . '/views/pages/401.php';
        if (file_exists($errorView)) {
            include $errorView;
        } else {
            echo "<h1>401 - Unauthorized</h1>";
            echo "<p>$message</p>";
        }
        exit;
    }
    
    /**
     * Send forbidden response
     */
    public static function forbidden($message = 'Forbidden') {
        if (Request::isAjax() || Request::expectsJson()) {
            return self::error($message, [], 403);
        }
        
        http_response_code(403);
        
        $errorView = APP_PATH . '/views/pages/403.php';
        if (file_exists($errorView)) {
            include $errorView;
        } else {
            echo "<h1>403 - Forbidden</h1>";
            echo "<p>$message</p>";
        }
        exit;
    }
    
    /**
     * Download file
     */
    public static function download($filePath, $fileName = null, $headers = []) {
        if (!file_exists($filePath)) {
            self::notFound('File not found');
        }
        
        if ($fileName === null) {
            $fileName = basename($filePath);
        }
        
        // Set default headers
        $defaultHeaders = [
            'Content-Description' => 'File Transfer',
            'Content-Type' => 'application/octet-stream',
            'Content-Disposition' => 'attachment; filename="' . $fileName . '"',
            'Expires' => '0',
            'Cache-Control' => 'must-revalidate',
            'Pragma' => 'public',
            'Content-Length' => filesize($filePath)
        ];
        
        // Merge with custom headers
        $headers = array_merge($defaultHeaders, $headers);
        
        // Send headers
        foreach ($headers as $name => $value) {
            header("$name: $value");
        }
        
        // Clear output buffer
        if (ob_get_level()) {
            ob_end_clean();
        }
        
        // Read file
        readfile($filePath);
        exit;
    }
    
    /**
     * Send file as inline
     */
    public static function file($filePath, $fileName = null, $headers = []) {
        if (!file_exists($filePath)) {
            self::notFound('File not found');
        }
        
        if ($fileName === null) {
            $fileName = basename($filePath);
        }
        
        // Get file mime type
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mimeType = finfo_file($finfo, $filePath);
        finfo_close($finfo);
        
        // Set default headers
        $defaultHeaders = [
            'Content-Type' => $mimeType,
            'Content-Disposition' => 'inline; filename="' . $fileName . '"',
            'Content-Length' => filesize($filePath)
        ];
        
        // Merge with custom headers
        $headers = array_merge($defaultHeaders, $headers);
        
        // Send headers
        foreach ($headers as $name => $value) {
            header("$name: $value");
        }
        
        // Clear output buffer
        if (ob_get_level()) {
            ob_end_clean();
        }
        
        // Read file
        readfile($filePath);
        exit;
    }
    
    /**
     * Send response with headers and exit
     */
    public function send() {
        // Set status code
        http_response_code($this->statusCode);
        
        // Set headers
        foreach ($this->headers as $name => $value) {
            header("$name: $value");
        }
        
        // Output data
        if ($this->data !== null) {
            echo $this->data;
        }
        
        exit;
    }
    
    /**
     * Create new response instance (fluent interface)
     */
    public static function make($data = null, $statusCode = 200) {
        return new self($data, $statusCode);
    }
}