<?php
/**
 * Role Redirect Middleware
 * Redirects users to appropriate page based on their role
 */
class RoleRedirectMiddleware {
    
    public static function redirect() {
        if (!isset($_SESSION['user_role'])) {
            return '/admin/login';  // CHANGED: removed BASE_URL .
        }
        
        $role = $_SESSION['user_role'];
        
        // Role-based landing pages
        switch ($role) {
            case 'research_manager':
                return '/admin/research';  // CHANGED: removed BASE_URL .
                
            case 'nominal_roll_user':
                return '/admin/nominal-roll';  // CHANGED: removed BASE_URL .
                
            case 'admin':
            case 'editor':
            case 'viewer':
            case 'moderator':
            case 'supervisor':
            default:
                return '/admin/dashboard';  // CHANGED: removed BASE_URL .
        }
    }
    
    /**
     * Check if user's current route is allowed for their role
     * Returns true if allowed, false if should redirect
     */
    public static function isAllowedRoute($currentRoute) {
        if (!isset($_SESSION['user_role'])) {
            return false;
        }
        
        $role = $_SESSION['user_role'];
        
        // Define restricted routes per role
        $restrictions = [
            'research_manager' => [
                'allowed_prefixes' => ['/admin/research', '/admin/logout', '/admin/users/profile'],
                'blocked_prefixes' => ['/admin/dashboard', '/admin/users', '/admin/nominal-roll']
            ],
            'nominal_roll_user' => [
                'allowed_prefixes' => ['/admin/nominal-roll', '/admin/logout', '/admin/users/profile'],
                'blocked_prefixes' => ['/admin/dashboard', '/admin/users', '/admin/research']
            ]
        ];
        
        // If no restrictions for this role, allow all
        if (!isset($restrictions[$role])) {
            return true;
        }
        
        $roleRestrictions = $restrictions[$role];
        
        // Check if route is explicitly blocked
        foreach ($roleRestrictions['blocked_prefixes'] as $blocked) {
            if (strpos($currentRoute, $blocked) === 0) {
                return false;
            }
        }
        
        // Check if route is in allowed list
        foreach ($roleRestrictions['allowed_prefixes'] as $allowed) {
            if (strpos($currentRoute, $allowed) === 0) {
                return true;
            }
        }
        
        // Default: block if not in allowed list for restricted roles
        return false;
    }
}