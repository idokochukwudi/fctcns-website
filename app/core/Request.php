<?php
/**
 * Request Class
 * 
 * Handles HTTP request data (GET, POST, files, etc.)
 * 
 * @package FCT_CNS
 */

class Request {
    
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
    public static function input($key, $default = null) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            return self::post($key, $default);
        }
        return self::get($key, $default);
    }
    
    /**
     * Get all request data
     */
    public static function all() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            return $_POST;
        }
        return $_GET;
    }
    
    /**
     * Check if request has a parameter
     */
    public static function has($key) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            return isset($_POST[$key]);
        }
        return isset($_GET[$key]);
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
     * Check if request is AJAX
     */
    public static function isAjax() {
        return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && 
               strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
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
     * Get request URI
     */
    public static function uri() {
        return $_SERVER['REQUEST_URI'] ?? '/';
    }
    
    /**
     * Get referrer
     */
    public static function referrer() {
        return $_SERVER['HTTP_REFERER'] ?? '';
    }
    
    /**
     * Validate CSRF token from request
     */
    public static function validateCSRF() {
        require_once APP_PATH . '/config/session.php';
        
        $token = self::post('csrf_token') ?? self::get('csrf_token');
        return Session::validateCSRFToken($token);
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
        
        // Remove whitespace
        $data = trim($data);
        
        // Convert special characters to HTML entities
        $data = htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
        
        return $data;
    }
}
?>