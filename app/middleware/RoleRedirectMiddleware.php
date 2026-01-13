<?php
/**
 * Role Redirect Middleware
 * Redirects users to appropriate page based on their role
 */
class RoleRedirectMiddleware {
    
    public static function redirect() {
        if (!isset($_SESSION['user_role'])) {
            return '/admin/login';
        }
        
        $role = $_SESSION['user_role'];
        
        // Redirect nominal roll users directly to nominal roll
        if ($role === 'nominal_roll_user') {
            return BASE_URL . '/admin/nominal-roll';
        }
        
        // All other users go to dashboard
        return BASE_URL . '/admin/dashboard';
    }
}