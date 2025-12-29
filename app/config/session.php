<?php
/**
 * Session Configuration
 * 
 * Handles session initialization and security
 * 
 * @package FCT_CNS
 */

// Include constants first - with safety check
$constantsFile = __DIR__ . '/constants.php';
if (file_exists($constantsFile)) {
    require_once $constantsFile;
} else {
    // If constants.php doesn't exist in expected location, try to find it
    $rootPath = dirname(__DIR__, 2);
    $constantsFile = $rootPath . '/app/config/constants.php';
    if (file_exists($constantsFile)) {
        require_once $constantsFile;
    } else {
        // Emergency fallback - define minimal constants
        define('SESSION_LIFETIME', 7200);
        define('CSRF_TOKEN_LIFETIME', 3600);
    }
}

// Start session with secure settings
if (session_status() === PHP_SESSION_NONE) {
    // Session name (custom to avoid conflicts)
    session_name('FCT_CNS_SESSION');
    
    // Set session cookie parameters for security
    session_set_cookie_params([
        'lifetime' => defined('SESSION_LIFETIME') ? SESSION_LIFETIME : 7200,
        'path' => '/',
        'domain' => $_SERVER['HTTP_HOST'] ?? 'localhost',
        'secure' => isset($_SERVER['HTTPS']), // Only over HTTPS if available
        'httponly' => true, // Prevent JavaScript access
        'samesite' => 'Strict' // CSRF protection
    ]);
    
    // Start the session
    session_start();

    // Regenerate session ID periodically to prevent fixation attacks
    if (!isset($_SESSION['created'])) {
        $_SESSION['created'] = time();
    } elseif (time() - $_SESSION['created'] > 1800) { // 30 minutes
        session_regenerate_id(true);
        $_SESSION['created'] = time();
    }
}

// CSRF Token Management
class Session {

    /**
     * Generate and store a CSRF token
     */
    public static function generateCSRFToken() {
        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
            $_SESSION['csrf_token_time'] = time();
        }
        return $_SESSION['csrf_token'];
    }

    /**
     * Validate a CSRF token
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
     * Clear CSRF token
     */
    public static function clearCSRFToken() {
        unset($_SESSION['csrf_token'], $_SESSION['csrf_token_time']);
    }

    /**
     * Set a flash message (temporary message shown once)
     */
    public static function setFlash($type, $message) {
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
     * Check if user is authenticated
     */
    public static function isAuthenticated() {
        return isset($_SESSION['user_id']) && isset($_SESSION['user_role']);
    }

    /**
     * Check if user has specific role
     */
    public static function hasRole($role) {
        return isset($_SESSION['user_role']) && $_SESSION['user_role'] === $role;
    }

    /**
     * Set user session after login
     */
    public static function loginUser($userId, $username, $role) {
        $_SESSION['user_id'] = $userId;
        $_SESSION['username'] = $username;
        $_SESSION['user_role'] = $role;
        $_SESSION['login_time'] = time();

        // Regenerate session ID on login
        session_regenerate_id(true);
    }

    /**
     * Clear user session on logout
     */
    public static function logout() {
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
}

// Initialize CSRF token for forms
Session::generateCSRFToken();
?>