<?php
/**
 * Session Configuration
 * 
 * Handles session initialization and security
 * 
 * @package FCT_CNS
 */

// Prevent direct access
if (!defined('APP_PATH')) {
    die('Direct access not permitted');
}

// Include constants first - with safety check
$constantsFile = __DIR__ . '/constants.php';
if (!file_exists($constantsFile)) {
    // Try parent directory
    $constantsFile = dirname(__DIR__) . '/constants.php';
}

if (file_exists($constantsFile)) {
    require_once $constantsFile;
} else {
    // Emergency fallback - define minimal constants
    if (!defined('SESSION_LIFETIME')) {
        define('SESSION_LIFETIME', 7200);
    }
    if (!defined('CSRF_TOKEN_LIFETIME')) {
        define('CSRF_TOKEN_LIFETIME', 3600);
    }
    if (!defined('APP_DEBUG')) {
        define('APP_DEBUG', true);
    }
}

// Start session with secure settings
if (session_status() === PHP_SESSION_NONE) {
    // Session name (custom to avoid conflicts)
    session_name('FCT_CNS_SESSION');
    
    // Get secure flag - use HTTPS if available
    $isSecure = false;
    if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') {
        $isSecure = true;
    }
    
    // Get domain - remove port if present
    $domain = $_SERVER['HTTP_HOST'] ?? 'localhost';
    if (strpos($domain, ':') !== false) {
        $domain = substr($domain, 0, strpos($domain, ':'));
    }
    
    // Set session cookie parameters for security
    session_set_cookie_params([
        'lifetime' => defined('SESSION_LIFETIME') ? SESSION_LIFETIME : 7200,
        'path' => '/',
        'domain' => $domain,
        'secure' => $isSecure,
        'httponly' => true, // Prevent JavaScript access
        'samesite' => 'Strict' // CSRF protection
    ]);
    
    // Start the session
    session_start();

    // Regenerate session ID periodically to prevent fixation attacks
    if (!isset($_SESSION['created'])) {
        $_SESSION['created'] = time();
        $_SESSION['ip'] = $_SERVER['REMOTE_ADDR'] ?? '';
        $_SESSION['user_agent'] = $_SERVER['HTTP_USER_AGENT'] ?? '';
    } elseif (time() - $_SESSION['created'] > 1800) { // 30 minutes
        // Also check if IP or User Agent changed (security)
        $currentIp = $_SERVER['REMOTE_ADDR'] ?? '';
        $currentUserAgent = $_SERVER['HTTP_USER_AGENT'] ?? '';
        
        if ($_SESSION['ip'] !== $currentIp || $_SESSION['user_agent'] !== $currentUserAgent) {
            // Suspicious activity - destroy session
            session_destroy();
            session_start();
            $_SESSION['security_warning'] = 'Session invalidated due to security check';
        }
        
        session_regenerate_id(true);
        $_SESSION['created'] = time();
        $_SESSION['ip'] = $currentIp;
        $_SESSION['user_agent'] = $currentUserAgent;
    }
}

// CSRF Token Management and Session Helper Class
class Session {

    /**
     * Generate and store a CSRF token (single token version - legacy)
     */
    public static function generateCSRFToken() {
        if (empty($_SESSION['csrf_token']) || empty($_SESSION['csrf_token_time'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
            $_SESSION['csrf_token_time'] = time();
        }
        return $_SESSION['csrf_token'];
    }

    /**
     * Validate a CSRF token (single token version - legacy)
     */
    public static function validateCSRFToken($token) {
        if (empty($_SESSION['csrf_token']) || empty($token)) {
            return false;
        }

        // Check if token matches
        if (!hash_equals($_SESSION['csrf_token'], $token)) {
            return false;
        }

        // Check if token is expired
        if (time() - $_SESSION['csrf_token_time'] > CSRF_TOKEN_LIFETIME) {
            self::clearCSRFToken();
            return false;
        }

        return true;
    }

    /**
     * Generate and store a CSRF token (multi-token version for controllers)
     * This maintains backward compatibility while supporting multiple tokens
     */
    public static function generateCSRFTokenMulti() {
        if (!isset($_SESSION['csrf_tokens'])) {
            $_SESSION['csrf_tokens'] = [];
        }
        
        // Generate new token
        $token = bin2hex(random_bytes(32));
        
        // Store with timestamp
        $_SESSION['csrf_tokens'][$token] = time();
        
        // Clean up old tokens (older than 1 hour)
        self::cleanupOldCSRFTokens();
        
        return $token;
    }

    /**
     * Validate a CSRF token (multi-token version)
     */
    public static function validateCSRFTokenMulti($token) {
        if (empty($token) || !isset($_SESSION['csrf_tokens'][$token])) {
            return false;
        }
        
        $tokenTime = $_SESSION['csrf_tokens'][$token];
        
        // Check if token is expired (1 hour)
        if (time() - $tokenTime > (defined('CSRF_TOKEN_LIFETIME') ? CSRF_TOKEN_LIFETIME : 3600)) {
            unset($_SESSION['csrf_tokens'][$token]);
            return false;
        }
        
        return true;
    }

    /**
     * Clean up old CSRF tokens
     */
    public static function cleanupOldCSRFTokens() {
        if (isset($_SESSION['csrf_tokens'])) {
            $lifetime = defined('CSRF_TOKEN_LIFETIME') ? CSRF_TOKEN_LIFETIME : 3600;
            foreach ($_SESSION['csrf_tokens'] as $token => $timestamp) {
                if (time() - $timestamp > $lifetime) {
                    unset($_SESSION['csrf_tokens'][$token]);
                }
            }
        }
    }

    /**
     * Remove a specific CSRF token after use
     */
    public static function removeCSRFToken($token) {
        if (isset($_SESSION['csrf_tokens'][$token])) {
            unset($_SESSION['csrf_tokens'][$token]);
        }
    }

    /**
     * Get CSRF token for forms (legacy compatibility)
     */
    public static function getCSRFToken() {
        return self::generateCSRFToken();
    }

    /**
     * Clear CSRF token (legacy single token)
     */
    public static function clearCSRFToken() {
        unset($_SESSION['csrf_token'], $_SESSION['csrf_token_time']);
    }

    /**
     * Check session security
     * Validates IP and User Agent to prevent session hijacking
     */
    public static function checkSessionSecurity() {
        $currentIp = $_SERVER['REMOTE_ADDR'] ?? '';
        $currentUserAgent = $_SERVER['HTTP_USER_AGENT'] ?? '';
        
        // Get stored session security data
        $storedIp = $_SESSION['ip'] ?? '';
        $storedUserAgent = $_SESSION['user_agent'] ?? '';
        
        // If no security data is stored yet, store it now
        if (empty($storedIp) || empty($storedUserAgent)) {
            $_SESSION['ip'] = $currentIp;
            $_SESSION['user_agent'] = $currentUserAgent;
            return true;
        }
        
        // Check if IP or User Agent changed
        if ($storedIp !== $currentIp || $storedUserAgent !== $currentUserAgent) {
            // Log the security violation
            error_log("Session security violation detected. IP changed from $storedIp to $currentIp or User Agent changed.");
            
            // Destroy the session
            self::logout();
            
            // Redirect to login with error message
            self::setFlash('error', 'Security violation detected. Please login again.');
            header('Location: /admin');
            exit;
        }
        
        return true;
    }

    /**
     * Set a flash message (temporary message shown once)
     */
    public static function setFlash($type, $message) {
        if (!isset($_SESSION['flash'])) {
            $_SESSION['flash'] = [];
        }
        $_SESSION['flash'][$type] = $message;
    }

    /**
     * Get and clear flash messages
     */
    public static function getFlash($type) {
        if (isset($_SESSION['flash'][$type])) {
            $message = $_SESSION['flash'][$type];
            unset($_SESSION['flash'][$type]);
            return $message;
        }
        return null;
    }

    /**
     * Get all flash messages
     */
    public static function getAllFlash() {
        $messages = $_SESSION['flash'] ?? [];
        unset($_SESSION['flash']);
        return $messages;
    }

    /**
     * Set a flash message (temporary message shown once)
     * Alias for setFlash with flexible interface
     */
    public static function flash($key, $message = null) {
        if ($message === null) {
            // Get and clear flash message
            $message = $_SESSION['flash'][$key] ?? null;
            unset($_SESSION['flash'][$key]);
            return $message;
        }
        
        // Set flash message
        if (!isset($_SESSION['flash'])) {
            $_SESSION['flash'] = [];
        }
        $_SESSION['flash'][$key] = $message;
    }

    /**
     * Check if a flash message exists
     */
    public static function has($key) {
        return isset($_SESSION['flash'][$key]);
    }

    /**
     * Keep old form data
     */
    public static function old($key, $default = '') {
        return $_SESSION['old'][$key] ?? $default;
    }

    /**
     * Set old form data
     */
    public static function setOld($data) {
        $_SESSION['old'] = $data;
    }

    /**
     * Clear old form data
     */
    public static function clearOld() {
        unset($_SESSION['old']);
    }

    /**
     * Check if user is authenticated
     */
    public static function isAuthenticated() {
        // First check session security
        if (!self::checkSessionSecurity()) {
            return false;
        }
        
        return isset($_SESSION['user_id']) && isset($_SESSION['user_role']) && isset($_SESSION['login_time']);
    }

    /**
     * Check if user has specific role
     */
    public static function hasRole($role) {
        if (!self::checkSessionSecurity()) {
            return false;
        }
        
        return isset($_SESSION['user_role']) && $_SESSION['user_role'] === $role;
    }

    /**
     * Check if user has any of the specified roles
     */
    public static function hasAnyRole($roles) {
        if (!self::checkSessionSecurity()) {
            return false;
        }
        
        if (!isset($_SESSION['user_role'])) {
            return false;
        }
        
        $roles = is_array($roles) ? $roles : [$roles];
        return in_array($_SESSION['user_role'], $roles);
    }

    /**
     * Set user session after login
     */
    public static function loginUser($userId, $username, $role, $additionalData = []) {
        $_SESSION['user_id'] = $userId;
        $_SESSION['username'] = $username;
        $_SESSION['user_role'] = $role;
        $_SESSION['login_time'] = time();
        
        // Store security data
        $_SESSION['ip'] = $_SERVER['REMOTE_ADDR'] ?? '';
        $_SESSION['user_agent'] = $_SERVER['HTTP_USER_AGENT'] ?? '';
        
        // Store additional user data if provided
        if (!empty($additionalData) && is_array($additionalData)) {
            foreach ($additionalData as $key => $value) {
                $_SESSION['user_' . $key] = $value;
            }
        }

        // Regenerate session ID on login (security)
        session_regenerate_id(true);
        
        // Update session creation time
        $_SESSION['created'] = time();
    }

    /**
     * Get user ID
     */
    public static function getUserId() {
        if (!self::checkSessionSecurity()) {
            return null;
        }
        
        return $_SESSION['user_id'] ?? null;
    }

    /**
     * Get username
     */
    public static function getUsername() {
        if (!self::checkSessionSecurity()) {
            return null;
        }
        
        return $_SESSION['username'] ?? null;
    }

    /**
     * Get user role
     */
    public static function getUserRole() {
        if (!self::checkSessionSecurity()) {
            return null;
        }
        
        return $_SESSION['user_role'] ?? null;
    }

    /**
     * Get user data
     */
    public static function getUserData($key = null) {
        if (!self::checkSessionSecurity()) {
            return null;
        }
        
        if ($key === null) {
            // Return all user data
            $userData = [];
            foreach ($_SESSION as $sessionKey => $value) {
                if (strpos($sessionKey, 'user_') === 0) {
                    $userData[substr($sessionKey, 5)] = $value;
                }
            }
            return $userData;
        }
        
        return $_SESSION['user_' . $key] ?? null;
    }

    /**
     * Enhanced logout to clear all security data
     */
    public static function logout() {
        // Clear all user-related session variables
        $userKeys = ['user_id', 'username', 'user_role', 'login_time', 'ip', 'user_agent', 'created'];
        foreach ($userKeys as $key) {
            unset($_SESSION[$key]);
        }
        
        // Clear any additional user data
        foreach ($_SESSION as $key => $value) {
            if (strpos($key, 'user_') === 0) {
                unset($_SESSION[$key]);
            }
        }
        
        // Clear flash messages
        unset($_SESSION['flash']);
        
        // Clear old form data
        unset($_SESSION['old']);
        
        // Clear CSRF tokens (both single and multi-token versions)
        unset($_SESSION['csrf_token'], $_SESSION['csrf_token_time']);
        unset($_SESSION['csrf_tokens']);
        
        // Regenerate session ID after logout
        session_regenerate_id(true);
    }

    /**
     * Destroy session completely
     */
    public static function destroy() {
        // Clear all session variables
        $_SESSION = [];

        // Delete session cookie
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $params["path"], $params["domain"],
                $params["secure"], $params["httponly"]
            );
        }

        // Destroy session
        session_destroy();
    }

    /**
     * Set session data
     */
    public static function set($key, $value) {
        $_SESSION[$key] = $value;
    }

    /**
     * Get session data
     */
    public static function get($key, $default = null) {
        return $_SESSION[$key] ?? $default;
    }

    /**
     * Remove session data
     */
    public static function remove($key) {
        unset($_SESSION[$key]);
    }

    /**
     * Check if session has key (for general session data)
     */
    public static function hasKey($key) {
        return isset($_SESSION[$key]);
    }
}

// Initialize CSRF token for forms if not exists (legacy single token)
if (!isset($_SESSION['csrf_token'])) {
    Session::generateCSRFToken();
}

// Initialize CSRF tokens array if not exists (multi-token version)
if (!isset($_SESSION['csrf_tokens'])) {
    $_SESSION['csrf_tokens'] = [];
}