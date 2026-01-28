<?php
/**
 * Authentication Middleware
 * Protects admin routes
 */
class AuthMiddleware {
    
    /**
     * Check if user is authenticated with security checks
     */
    public static function authenticate() {
        require_once __DIR__ . '/../config/session.php';
        
        // First check session security
        Session::checkSessionSecurity();
        
        if (!Session::isAuthenticated()) {
            Session::setFlash('error', 'Please login to access admin area');
            header('Location: /admin');
            exit;
        }
        
        // Optional: Check if session is too old (e.g., 8 hours)
        $loginTime = $_SESSION['login_time'] ?? 0;
        if (time() - $loginTime > 28800) { // 8 hours in seconds
            Session::logout();
            Session::setFlash('error', 'Session expired. Please login again.');
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
    
    /**
     * Check if user has specific permission
     */
    public static function requirePermission($permission) {
        self::authenticate();
        
        if (!Session::hasPermission($permission)) {
            Session::setFlash('error', 'Access denied. You do not have the required permission.');
            header('Location: ' . BASE_URL . '/admin/dashboard');
            exit;
        }
    }
    
    /**
     * Check if user has any of the specified permissions
     */
    public static function requireAnyPermission($permissions) {
        self::authenticate();
        
        $permissions = is_array($permissions) ? $permissions : [$permissions];
        
        foreach ($permissions as $permission) {
            if (Session::hasPermission($permission)) {
                return; // User has at least one permission
            }
        }
        
        Session::setFlash('error', 'Access denied. You do not have the required permissions.');
        header('Location: ' . BASE_URL . '/admin/dashboard');
        exit;
    }
    
    /**
     * Check route access based on user role
     * Returns true if allowed, false otherwise
     */
    public static function checkRouteAccess($allowedRoles) {
        self::authenticate();
        
        $userRole = Session::getUserRole();
        $allowedRoles = is_array($allowedRoles) ? $allowedRoles : [$allowedRoles];
        
        return in_array($userRole, $allowedRoles);
    }
}
?>