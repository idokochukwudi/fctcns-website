<?php
/**
 * Session Configuration - COMPATIBLE VERSION
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
        'samesite' => 'Lax' // Changed to Lax for better compatibility
    ]);
    
    // Start the session
    session_start();

    // Regenerate session ID periodically to prevent fixation attacks
    if (!isset($_SESSION['created'])) {
        $_SESSION['created'] = time();
        // Don't store IP/user agent on initial session creation
        // This allows login from different devices/browsers
    } elseif (time() - $_SESSION['created'] > 1800) { // 30 minutes
        session_regenerate_id(true);
        $_SESSION['created'] = time();
    }
}

// CSRF Token Management and Session Helper Class
class Session {

    /**
     * Generate and store a CSRF token (single token version - legacy)
     * FOR LOGIN FORMS AND OLD FORMS
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
     * FOR LOGIN FORMS AND OLD FORMS
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
        $lifetime = defined('CSRF_TOKEN_LIFETIME') ? CSRF_TOKEN_LIFETIME : 3600;
        if (time() - $_SESSION['csrf_token_time'] > $lifetime) {
            self::clearCSRFToken();
            return false;
        }

        return true;
    }

    /**
     * Generate and store a CSRF token (multi-token version for controllers)
     * FOR NEW FORMS LIKE ADMIN NEWS
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
     * FOR NEW FORMS LIKE ADMIN NEWS
     */
    public static function validateCSRFTokenMulti($token) {
        if (empty($token)) {
            return false;
        }
        
        // First check multi-token system
        if (isset($_SESSION['csrf_tokens'][$token])) {
            $tokenTime = $_SESSION['csrf_tokens'][$token];
            
            // Check if token is expired (1 hour)
            $lifetime = defined('CSRF_TOKEN_LIFETIME') ? CSRF_TOKEN_LIFETIME : 3600;
            if (time() - $tokenTime > $lifetime) {
                unset($_SESSION['csrf_tokens'][$token]);
                return false;
            }
            
            return true;
        }
        
        // Fallback: also check legacy token for backward compatibility
        if (isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token)) {
            $lifetime = defined('CSRF_TOKEN_LIFETIME') ? CSRF_TOKEN_LIFETIME : 3600;
            if (time() - $_SESSION['csrf_token_time'] > $lifetime) {
                self::clearCSRFToken();
                return false;
            }
            return true;
        }
        
        return false;
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
        
        // Also clear legacy token if it matches
        if (isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token)) {
            self::clearCSRFToken();
        }
    }

    /**
     * Get CSRF token for forms (compatibility method)
     * This returns the legacy token for login forms
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
     * Check session security - RELAXED VERSION
     * Only validates for logged-in users
     */
    public static function checkSessionSecurity() {
        // Only check security for authenticated users
        if (!isset($_SESSION['user_id'])) {
            return true;
        }
        
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
        
        // For logged-in users, check for significant changes
        // Allow minor changes like browser updates
        if ($storedIp !== $currentIp) {
            // Log the security warning but don't destroy session
            error_log("Session security warning: IP changed from $storedIp to $currentIp for user " . ($_SESSION['user_id'] ?? 'unknown'));
            
            // Update stored IP
            $_SESSION['ip'] = $currentIp;
        }
        
        // Simple user agent check - only log changes, don't destroy
        if ($storedUserAgent !== $currentUserAgent) {
            error_log("Session security warning: User Agent changed for user " . ($_SESSION['user_id'] ?? 'unknown'));
            $_SESSION['user_agent'] = $currentUserAgent;
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
     * Check if user is authenticated - RELAXED VERSION
     */
    public static function isAuthenticated() {
        // Don't call checkSessionSecurity - it was causing login issues
        // Only check if user has login data
        
        if (!isset($_SESSION['user_id']) || !isset($_SESSION['user_role']) || !isset($_SESSION['login_time'])) {
            return false;
        }
        
        // Check if session is too old
        $maxAge = defined('SESSION_LIFETIME') ? SESSION_LIFETIME : 7200;
        if (time() - $_SESSION['login_time'] > $maxAge) {
            self::logout();
            return false;
        }
        
        return true;
    }

    /**
     * Check if user has specific role
     */
    public static function hasRole($role) {
        if (!self::isAuthenticated()) {
            return false;
        }
        
        return isset($_SESSION['user_role']) && $_SESSION['user_role'] === $role;
    }

    /**
     * Check if user has any of the specified roles
     */
    public static function hasAnyRole($roles) {
        if (!self::isAuthenticated()) {
            return false;
        }
        
        if (!isset($_SESSION['user_role'])) {
            return false;
        }
        
        $roles = is_array($roles) ? $roles : [$roles];
        return in_array($_SESSION['user_role'], $roles);
    }

    /**
     * Set user session after login - UPDATED VERSION
     */
    public static function loginUser($userId, $username, $role, $additionalData = []) {
        // Clear any existing session data
        session_regenerate_id(true);
        
        $_SESSION['user_id'] = $userId;
        $_SESSION['username'] = $username;
        $_SESSION['user_role'] = $role;
        $_SESSION['login_time'] = time();
        
        // Store security data AFTER successful login
        $_SESSION['ip'] = $_SERVER['REMOTE_ADDR'] ?? '';
        $_SESSION['user_agent'] = $_SERVER['HTTP_USER_AGENT'] ?? '';
        $_SESSION['created'] = time();
        
        // Store additional user data if provided
        if (!empty($additionalData) && is_array($additionalData)) {
            foreach ($additionalData as $key => $value) {
                $_SESSION['user_' . $key] = $value;
            }
        }
    }

    /**
     * Get user ID
     */
    public static function getUserId() {
        if (!self::isAuthenticated()) {
            return null;
        }
        
        return $_SESSION['user_id'] ?? null;
    }

    /**
     * Get username
     */
    public static function getUsername() {
        if (!self::isAuthenticated()) {
            return null;
        }
        
        return $_SESSION['username'] ?? null;
    }

    /**
     * Get user role
     */
    public static function getUserRole() {
        if (!self::isAuthenticated()) {
            return null;
        }
        
        return $_SESSION['user_role'] ?? null;
    }

    /**
     * Get user data
     */
    public static function getUserData($key = null) {
        if (!self::isAuthenticated()) {
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

    /**
     * Check if user has specific permission
     */
    public static function hasPermission($permission) {
        if (!self::isAuthenticated()) {
            return false;
        }
        
        // Admin always has all permissions
        $userRole = self::getUserRole();
        if (in_array($userRole, ['admin', 'super_admin'])) {
            return true;
        }
        
        // Check if permissions are cached in session
        if (!isset($_SESSION['user_permissions'])) {
            self::loadUserPermissions();
        }
        
        return isset($_SESSION['user_permissions'][$permission]) && $_SESSION['user_permissions'][$permission];
    }

    /**
     * Get all user permissions
     */
    public static function getUserPermissions() {
        if (!self::isAuthenticated()) {
            return [];
        }
        
        if (!isset($_SESSION['user_permissions'])) {
            self::loadUserPermissions();
        }
        
        return $_SESSION['user_permissions'] ?? [];
    }

    /**
     * Load user permissions from database into session
     */
    public static function loadUserPermissions($userId = null) {
        if ($userId === null) {
            $userId = self::getUserId();
        }
        
        if (!$userId) {
            $_SESSION['user_permissions'] = [];
            return;
        }
        
        try {
            require_once APP_PATH . '/config/database.php';
            $database = Database::getInstance();
            $db = $database->getConnection();
            
            $stmt = $db->prepare("
                SELECT permission, is_allowed 
                FROM user_permissions 
                WHERE user_id = ?
            ");
            $stmt->execute([$userId]);
            $permissions = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            $_SESSION['user_permissions'] = [];
            foreach ($permissions as $perm) {
                $_SESSION['user_permissions'][$perm['permission']] = (bool)$perm['is_allowed'];
            }
            
        } catch (Exception $e) {
            error_log("Failed to load user permissions: " . $e->getMessage());
            $_SESSION['user_permissions'] = [];
        }
    }

    /**
     * Clear user permissions from session
     */
    public static function clearUserPermissions() {
        unset($_SESSION['user_permissions']);
    }
}

// Initialize both CSRF systems for compatibility
// Multi-token system for new forms
if (!isset($_SESSION['csrf_tokens'])) {
    $_SESSION['csrf_tokens'] = [];
}

// Legacy single token system for login forms
if (!isset($_SESSION['csrf_token'])) {
    Session::generateCSRFToken();
}