<?php
/**
 * Session Fix Helper - Ensures session persistence across requests
 * 
 * This helper ensures consistent session handling across the application
 * by properly configuring session parameters and providing methods
 * for managing applicant sessions.
 * 
 * @package FCT_CNS
 * @subpackage Helpers
 */

class SessionFixHelper {
    
    /**
     * Initialize session properly
     * 
     * Sets secure cookie parameters and starts the session if not already started.
     * This should be called at the beginning of every request that needs session access.
     */
    public static function init() {
        // Only set cookie parameters if session is not already active
        if (session_status() === PHP_SESSION_NONE) {
            // Set session cookie parameters for better persistence
            session_set_cookie_params([
                'lifetime' => 86400, // 24 hours
                'path' => '/',
                'domain' => '',
                'secure' => false, // Set to true if using HTTPS
                'httponly' => true, // Prevents JavaScript access to session cookie
                'samesite' => 'Lax' // CSRF protection
            ]);
            
            // Start session
            session_start();
        }
        // If session is already active, don't try to set cookie parameters again
    }
    
    /**
     * Set session variables for an applicant and ensure they persist
     * 
     * This method should be called after successful verification or login.
     * It regenerates the session ID for security and sets all necessary
     * session variables for the applicant.
     * 
     * @param array $applicant Applicant data array containing id, jamb_number, first_name, last_name
     */
    public static function setApplicantSession($applicant) {
        // Ensure session is started
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        // Regenerate session ID for security to prevent session fixation
        session_regenerate_id(true);
        
        // Set all session variables
        $_SESSION['applicant_id'] = $applicant['id'];
        $_SESSION['applicant_jamb'] = $applicant['jamb_number'] ?? '';
        $_SESSION['applicant_name'] = ($applicant['first_name'] ?? '') . ' ' . ($applicant['last_name'] ?? '');
        $_SESSION['applicant_login_time'] = time();
        $_SESSION['applicant_ip'] = $_SERVER['REMOTE_ADDR'] ?? '';
        $_SESSION['applicant_user_agent'] = $_SERVER['HTTP_USER_AGENT'] ?? '';
        
        // Force session to write and close
        session_write_close();
        
        // Log for debugging
        error_log("SessionFixHelper: Session set for applicant ID: " . ($applicant['id'] ?? 'unknown'));
        
        // Note: Don't restart session here - let the next request handle it
        // This prevents issues with trying to set cookies on already sent headers
    }
    
    /**
     * Verify session is still valid
     * 
     * Checks if the applicant is still logged in and if the session hasn't expired.
     * 
     * @return bool True if session is valid, false otherwise
     */
    public static function verifySession() {
        // Ensure session is started
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        // Check if applicant_id exists
        if (!isset($_SESSION['applicant_id'])) {
            return false;
        }
        
        // Check if session is too old (24 hours)
        if (isset($_SESSION['applicant_login_time'])) {
            $sessionAge = time() - $_SESSION['applicant_login_time'];
            if ($sessionAge > 86400) { // 24 hours in seconds
                // Session expired, destroy it
                self::destroy();
                return false;
            }
        }
        
        return true;
    }
    
    /**
     * Get session data safely
     * 
     * Retrieves a value from the session with an optional default.
     * 
     * @param string $key Session key to retrieve
     * @param mixed $default Default value if key doesn't exist
     * @return mixed Session value or default
     */
    public static function get($key, $default = null) {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        return $_SESSION[$key] ?? $default;
    }
    
    /**
     * Set session data
     * 
     * Stores a value in the session.
     * 
     * @param string $key Session key
     * @param mixed $value Value to store
     */
    public static function set($key, $value) {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $_SESSION[$key] = $value;
    }
    
    /**
     * Clear session data
     * 
     * Removes a specific key from the session.
     * 
     * @param string $key Session key to remove
     */
    public static function clear($key) {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        unset($_SESSION[$key]);
    }
    
    /**
     * Destroy the current session completely
     * 
     * Clears all session data, destroys the session, and removes the cookie.
     */
    public static function destroy() {
        // Ensure session is started
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        // Clear all session variables
        $_SESSION = array();
        
        // Delete the session cookie
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(
                session_name(),
                '',
                time() - 42000,
                $params["path"],
                $params["domain"],
                $params["secure"],
                $params["httponly"]
            );
        }
        
        // Destroy the session
        session_destroy();
    }
}