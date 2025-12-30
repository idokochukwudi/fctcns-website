<?php
/**
 * Request Class
 * 
 * Handles HTTP request data (GET, POST, files, etc.)
 * Provides a clean interface for accessing request data
 * 
 * @package FCT_CNS
 */

class Request {
    
    /**
     * Singleton instance
     */
    private static $instance = null;
    
    /**
     * Request data storage
     */
    private $data = [];
    
    /**
     * Get singleton instance
     */
    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    /**
     * Constructor - collects all request data
     */
    private function __construct() {
        // Merge all input data
        $this->data = array_merge($_GET, $_POST, $_FILES);
        
        // Handle JSON input
        $input = file_get_contents('php://input');
        if (!empty($input) && $this->isJson($input)) {
            $jsonData = json_decode($input, true);
            if (is_array($jsonData)) {
                $this->data = array_merge($this->data, $jsonData);
            }
        }
    }
    
    /**
     * Get a GET parameter
     */
    public static function get($key, $default = null) {
        return $_GET[$key] ?? $default;
    }
    
    /**
     * Get a POST parameter
     */
    public static function post($key, $default = null) {
        return $_POST[$key] ?? $default;
    }
    
    /**
     * Get request data (GET or POST based on method)
     */
    public static function input($key = null, $default = null) {
        $instance = self::getInstance();
        
        if ($key === null) {
            return $instance->data;
        }
        
        return $instance->data[$key] ?? $default;
    }
    
    /**
     * Get all request data
     */
    public static function all() {
        return self::getInstance()->data;
    }
    
    /**
     * Get only specified keys from request
     */
    public static function only($keys) {
        $keys = is_array($keys) ? $keys : func_get_args();
        $data = self::all();
        
        return array_filter($data, function($key) use ($keys) {
            return in_array($key, $keys);
        }, ARRAY_FILTER_USE_KEY);
    }
    
    /**
     * Get all except specified keys
     */
    public static function except($keys) {
        $keys = is_array($keys) ? $keys : func_get_args();
        $data = self::all();
        
        return array_filter($data, function($key) use ($keys) {
            return !in_array($key, $keys);
        }, ARRAY_FILTER_USE_KEY);
    }
    
    /**
     * Check if request has a parameter
     */
    public static function has($key) {
        $data = self::all();
        return isset($data[$key]);
    }
    
    /**
     * Check if request has all specified parameters
     */
    public static function hasAll($keys) {
        $keys = is_array($keys) ? $keys : func_get_args();
        
        foreach ($keys as $key) {
            if (!self::has($key)) {
                return false;
            }
        }
        
        return true;
    }
    
    /**
     * Check if request has any of the specified parameters
     */
    public static function hasAny($keys) {
        $keys = is_array($keys) ? $keys : func_get_args();
        
        foreach ($keys as $key) {
            if (self::has($key)) {
                return true;
            }
        }
        
        return false;
    }
    
    /**
     * Get uploaded file
     */
    public static function file($key) {
        return $_FILES[$key] ?? null;
    }
    
    /**
     * Get request method
     */
    public static function method() {
        return $_SERVER['REQUEST_METHOD'];
    }
    
    /**
     * Get request URI
     */
    public static function uri() {
        return parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    }
    
    /**
     * Get full URL
     */
    public static function fullUrl() {
        $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
        return $protocol . '://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
    }
    
    /**
     * Get query string
     */
    public static function queryString() {
        return $_SERVER['QUERY_STRING'] ?? '';
    }
    
    /**
     * Check if request is POST
     */
    public static function isPost() {
        return self::method() === 'POST';
    }
    
    /**
     * Check if request is GET
     */
    public static function isGet() {
        return self::method() === 'GET';
    }
    
    /**
     * Check if request is PUT
     */
    public static function isPut() {
        return self::method() === 'PUT';
    }
    
    /**
     * Check if request is DELETE
     */
    public static function isDelete() {
        return self::method() === 'DELETE';
    }
    
    /**
     * Check if request is AJAX
     */
    public static function isAjax() {
        return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && 
               strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
    }
    
    /**
     * Check if request expects JSON
     */
    public static function expectsJson() {
        $accept = $_SERVER['HTTP_ACCEPT'] ?? '';
        return strpos($accept, 'application/json') !== false || 
               strpos($accept, 'json') !== false;
    }
    
    /**
     * Get request IP address
     */
    public static function ip() {
        $ipKeys = [
            'HTTP_CLIENT_IP',
            'HTTP_X_FORWARDED_FOR',
            'HTTP_X_FORWARDED',
            'HTTP_FORWARDED_FOR',
            'HTTP_FORWARDED',
            'REMOTE_ADDR'
        ];
        
        foreach ($ipKeys as $key) {
            if (isset($_SERVER[$key]) && !empty($_SERVER[$key])) {
                $ip = $_SERVER[$key];
                if (strpos($ip, ',') !== false) {
                    $ips = explode(',', $ip);
                    $ip = trim($ips[0]);
                }
                if (filter_var($ip, FILTER_VALIDATE_IP)) {
                    return $ip;
                }
            }
        }
        
        return $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
    }
    
    /**
     * Get user agent
     */
    public static function userAgent() {
        return $_SERVER['HTTP_USER_AGENT'] ?? '';
    }
    
    /**
     * Get referrer
     */
    public static function referrer() {
        return $_SERVER['HTTP_REFERER'] ?? '';
    }
    
    /**
     * Get route parameters (for use with router)
     */
    public static function route($key = null, $default = null) {
        // This would be populated by the router
        // For now, we'll use a static variable
        static $routeParams = [];
        
        if ($key === null) {
            return $routeParams;
        }
        
        return $routeParams[$key] ?? $default;
    }
    
    /**
     * Set route parameters (called by router)
     */
    public static function setRouteParams($params) {
        static $routeParams = [];
        $routeParams = $params;
    }
    
    /**
     * Validate CSRF token from request
     */
    public static function validateCSRF() {
        if (!class_exists('Session')) {
            require_once APP_PATH . '/config/session.php';
        }
        
        $token = self::input('csrf_token') ?? self::input('_token');
        
        if (empty($token)) {
            return false;
        }
        
        if (class_exists('Session')) {
            return Session::validateCSRFToken($token);
        }
        
        // Fallback if Session class doesn't exist
        return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
    }
    
    /**
     * Sanitize input data
     */
    public static function sanitize($data) {
        if (is_array($data)) {
            foreach ($data as $key => $value) {
                $data[$key] = self::sanitize($value);
            }
            return $data;
        }
        
        if (!is_string($data)) {
            return $data;
        }
        
        // Remove whitespace
        $data = trim($data);
        
        // Convert special characters to HTML entities
        $data = htmlspecialchars($data, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
        
        return $data;
    }
    
    /**
     * Get sanitized input
     */
    public static function sanitized($key = null, $default = null) {
        $data = self::input($key, $default);
        
        if ($key === null) {
            return self::sanitize($data);
        }
        
        return self::sanitize($data);
    }
    
    /**
     * Check if string is valid JSON
     */
    private function isJson($string) {
        json_decode($string);
        return json_last_error() === JSON_ERROR_NONE;
    }
    
    /**
     * Get bearer token from Authorization header
     */
    public static function bearerToken() {
        $headers = self::getAuthorizationHeader();
        
        if (!empty($headers)) {
            if (preg_match('/Bearer\s(\S+)/', $headers, $matches)) {
                return $matches[1];
            }
        }
        
        return null;
    }
    
    /**
     * Get Authorization header
     */
    private static function getAuthorizationHeader() {
        $headers = null;
        
        if (isset($_SERVER['Authorization'])) {
            $headers = trim($_SERVER['Authorization']);
        } elseif (isset($_SERVER['HTTP_AUTHORIZATION'])) {
            $headers = trim($_SERVER['HTTP_AUTHORIZATION']);
        } elseif (function_exists('apache_request_headers')) {
            $requestHeaders = apache_request_headers();
            $requestHeaders = array_combine(
                array_map('ucwords', array_keys($requestHeaders)),
                array_values($requestHeaders)
            );
            
            if (isset($requestHeaders['Authorization'])) {
                $headers = trim($requestHeaders['Authorization']);
            }
        }
        
        return $headers;
    }
    
    /**
     * Get request content type
     */
    public static function contentType() {
        return $_SERVER['CONTENT_TYPE'] ?? '';
    }
    
    /**
     * Get request scheme (http/https)
     */
    public static function scheme() {
        return (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
    }
    
    /**
     * Get host name
     */
    public static function host() {
        return $_SERVER['HTTP_HOST'] ?? 'localhost';
    }
    
    /**
     * Check if request is secure (HTTPS)
     */
    public static function isSecure() {
        return self::scheme() === 'https';
    }
}