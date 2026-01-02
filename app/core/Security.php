<?php
/**
 * Security Class
 * Handles security-related functionality including CSRF protection
 * 
 * @package FCT_CNS
 * @version 2.0
 */

class Security
{
    /**
     * Generate a CSRF token and store it in session
     * 
     * @return string The generated CSRF token
     */
    public static function generateCSRFToken()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        // Generate token if not exists or expired
        if (!isset($_SESSION['csrf_token']) || !isset($_SESSION['csrf_token_expiry']) || 
            time() > $_SESSION['csrf_token_expiry']) {
            
            // Generate random token
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
            
            // Set token expiry (1 hour)
            $_SESSION['csrf_token_expiry'] = time() + 3600;
        }
        
        return $_SESSION['csrf_token'];
    }
    
    /**
     * Validate CSRF token
     * 
     * @param string $token The token to validate
     * @return bool True if token is valid, false otherwise
     */
    public static function validateCSRFToken($token)
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        // Check if token exists in session
        if (!isset($_SESSION['csrf_token'])) {
            return false;
        }
        
        // Check if token has expired
        if (isset($_SESSION['csrf_token_expiry']) && time() > $_SESSION['csrf_token_expiry']) {
            // Token expired, clear it
            unset($_SESSION['csrf_token'], $_SESSION['csrf_token_expiry']);
            return false;
        }
        
        // Compare tokens (timing-safe comparison)
        return hash_equals($_SESSION['csrf_token'], $token);
    }
    
    /**
     * Generate and output a CSRF token hidden field
     * 
     * @return void
     */
    public static function csrfField()
    {
        $token = self::generateCSRFToken();
        echo '<input type="hidden" name="csrf_token" value="' . htmlspecialchars($token) . '">';
    }
    
    /**
     * Get CSRF token as a string
     * 
     * @return string CSRF token
     */
    public static function getToken()
    {
        return self::generateCSRFToken();
    }
    
    /**
     * Check if request has valid CSRF token
     * 
     * @return bool True if valid, false otherwise
     */
    public static function checkCSRF()
    {
        $method = $_SERVER['REQUEST_METHOD'] ?? 'GET';
        
        // Only check POST, PUT, PATCH, DELETE requests
        if (in_array($method, ['POST', 'PUT', 'PATCH', 'DELETE'])) {
            $token = $_POST['csrf_token'] ?? '';
            
            if (!self::validateCSRFToken($token)) {
                // Log CSRF attempt
                self::logSecurityEvent('CSRF token validation failed', [
                    'ip' => $_SERVER['REMOTE_ADDR'] ?? 'unknown',
                    'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? 'unknown',
                    'method' => $method,
                    'uri' => $_SERVER['REQUEST_URI'] ?? 'unknown'
                ]);
                
                return false;
            }
        }
        
        return true;
    }
    
    /**
     * Log security events
     * 
     * @param string $event Event description
     * @param array $data Additional data to log
     * @return void
     */
    private static function logSecurityEvent($event, $data = [])
    {
        $logDir = APP_PATH . '/logs';
        if (!file_exists($logDir)) {
            mkdir($logDir, 0755, true);
        }
        
        $logFile = $logDir . '/security.log';
        
        $logEntry = sprintf(
            "[%s] %s - IP: %s - User Agent: %s - URI: %s - Method: %s\n",
            date('Y-m-d H:i:s'),
            $event,
            $data['ip'] ?? 'unknown',
            $data['user_agent'] ?? 'unknown',
            $data['uri'] ?? 'unknown',
            $data['method'] ?? 'unknown'
        );
        
        error_log($logEntry, 3, $logFile);
    }
    
    /**
     * Sanitize input data
     * 
     * @param mixed $data Data to sanitize
     * @return mixed Sanitized data
     */
    public static function sanitize($data)
    {
        if (is_array($data)) {
            return array_map([self::class, 'sanitize'], $data);
        }
        
        // Trim whitespace
        $data = trim($data ?? '');
        
        // Remove slashes if magic quotes are on
        if (get_magic_quotes_gpc()) {
            $data = stripslashes($data);
        }
        
        // Convert special characters to HTML entities
        return htmlspecialchars($data, ENT_QUOTES | ENT_HTML5, 'UTF-8');
    }
    
    /**
     * Sanitize output for display
     * 
     * @param mixed $data Data to escape
     * @return mixed Escaped data
     */
    public static function escape($data)
    {
        if (is_array($data)) {
            return array_map([self::class, 'escape'], $data);
        }
        
        return htmlspecialchars($data ?? '', ENT_QUOTES | ENT_HTML5, 'UTF-8');
    }
    
    /**
     * Generate a secure random string
     * 
     * @param int $length Length of the string
     * @return string Random string
     */
    public static function randomString($length = 32)
    {
        try {
            return bin2hex(random_bytes($length));
        } catch (Exception $e) {
            // Fallback if random_bytes is not available
            $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
            $randomString = '';
            for ($i = 0; $i < $length; $i++) {
                $randomString .= $characters[random_int(0, strlen($characters) - 1)];
            }
            return hash('sha256', $randomString);
        }
    }
}
?>