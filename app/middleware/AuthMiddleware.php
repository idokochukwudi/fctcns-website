<?php
/**
 * Authentication Middleware
 * Protects admin routes
 */
class AuthMiddleware {
    
    /**
     * Check if user is authenticated
     */
    public static function authenticate() {
        require_once __DIR__ . '/../config/session.php';
        
        if (!Session::isAuthenticated()) {
            Session::setFlash('error', 'Please login to access admin area');
            header('Location: /admin');
            exit;
        }
    }
    
    /**
     * Check if user has specific role
     */
    public static function requireRole($role) {
        self::authenticate();
        
        if (!Session::hasRole($role)) {
            http_response_code(403);
            die('<h1>403 - Forbidden</h1><p>You do not have permission to access this page.</p>');
        }
    }
    
    /**
     * Check if user has any of the specified roles
     */
    public static function requireAnyRole($roles) {
        self::authenticate();
        
        $hasRole = false;
        foreach ($roles as $role) {
            if (Session::hasRole($role)) {
                $hasRole = true;
                break;
            }
        }
        
        if (!$hasRole) {
            http_response_code(403);
            die('<h1>403 - Forbidden</h1><p>You do not have permission to access this page.</p>');
        }
    }
}
?>