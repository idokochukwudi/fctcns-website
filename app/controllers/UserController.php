<?php
/**
 * User Controller
 * Handles user management operations
 * Extends the base Controller class for common functionality
 */
class UserController extends Controller {
    
    private $db;
    
    /**
     * Constructor
     */
    public function __construct() {
        parent::__construct();
        
        // Set admin layout
        $this->layout = 'admin';
        
        // Require authentication
        require_once __DIR__ . '/../middleware/AuthMiddleware.php';
        AuthMiddleware::authenticate();
        
        // Check if user is admin
        if (($_SESSION['user_role'] ?? '') !== 'admin') {
            $this->showError("Access denied. Admin privileges required.");
            exit;
        }
        
        // Setup database
        require_once __DIR__ . '/../config/database.php';
        $database = Database::getInstance();
        $this->db = $database->getConnection();
        
        // Initialize common data
        $this->data = array_merge($this->data, [
            'user' => $_SESSION ?? [],
            'baseUrl' => defined('BASE_URL') ? BASE_URL : '',
            'currentPage' => 'users'
        ]);
    }
    
    /**
     * Display all users
     */
    public function index() {
        try {
            // Get all users with permissions count and last login info
            $stmt = $this->db->query("
                SELECT u.*, 
                       (SELECT COUNT(*) FROM user_permissions WHERE user_id = u.id) as permission_count,
                       (SELECT COUNT(*) FROM activity_logs WHERE user_id = u.id) as activity_count
                FROM users u 
                ORDER BY u.created_at DESC
            ");
            $users = $stmt->fetchAll();
            
            // Get statistics
            $statsStmt = $this->db->query("
                SELECT 
                    COUNT(*) as total,
                    SUM(CASE WHEN is_active = 1 THEN 1 ELSE 0 END) as active,
                    COUNT(DISTINCT role) as roles_count,
                    COUNT(DISTINCT DATE(last_login)) as active_days_count
                FROM users
            ");
            $stats = $statsStmt->fetch();
            
            // Get role distribution
            $roleStmt = $this->db->query("
                SELECT role, COUNT(*) as count 
                FROM users 
                GROUP BY role 
                ORDER BY count DESC
            ");
            $roleDistribution = $roleStmt->fetchAll();
            
            // Set data for view
            $this->data = array_merge($this->data, [
                'users' => $users,
                'stats' => $stats,
                'roleDistribution' => $roleDistribution,
                'pageTitle' => 'User Management - FCT College of Nursing Sciences',
                'pageDescription' => 'Manage system users and permissions'
            ]);
            
            // Render view
            $this->render('admin/users/index');
            
        } catch (Exception $e) {
            error_log("UserController index error: " . $e->getMessage());
            $this->showError("Failed to load users.");
        }
    }
    
    /**
     * Display create user form
     */
    public function create() {
        // Get available roles
        $roles = $this->getAvailableRoles();
        
        // Set data for view
        $this->data = array_merge($this->data, [
            'roles' => $roles,
            'pageTitle' => 'Create New User - FCT College of Nursing Sciences',
            'pageDescription' => 'Create a new system user'
        ]);
        
        $this->render('admin/users/create');
    }
    
    /**
     * Save new user
     */
    public function store() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/admin/users/create');
            return;
        }

        try {
            // Validate CSRF token
            $this->validateCsrf();
            
            $username = $this->input('username', '');
            $email = $this->input('email', '');
            $password = $this->input('password', '');
            $confirm_password = $this->input('confirm_password', '');
            $full_name = $this->input('full_name', '');
            $role = $this->input('role', 'editor');
            $is_active = $this->input('is_active', 1) ? 1 : 0;
            
            // Validate
            if (empty($username) || empty($email) || empty($password) || empty($full_name)) {
                throw new Exception("All fields are required.");
            }
            
            if ($password !== $confirm_password) {
                throw new Exception("Passwords do not match.");
            }
            
            if (strlen($password) < 6) {
                throw new Exception("Password must be at least 6 characters.");
            }
            
            // Validate email
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                throw new Exception("Please enter a valid email address.");
            }
            
            // Check if username or email already exists
            $stmt = $this->db->prepare("SELECT id FROM users WHERE username = ? OR email = ?");
            $stmt->execute([$username, $email]);
            if ($stmt->fetch()) {
                throw new Exception("Username or email already exists.");
            }
            
            // Hash password
            $password_hash = password_hash($password, PASSWORD_DEFAULT);
            
            // Start transaction
            $this->db->beginTransaction();
            
            try {
                // Insert user
                $stmt = $this->db->prepare("
                    INSERT INTO users (username, email, password_hash, full_name, role, is_active, created_at, updated_at) 
                    VALUES (?, ?, ?, ?, ?, ?, NOW(), NOW())
                ");
                
                $stmt->execute([$username, $email, $password_hash, $full_name, $role, $is_active]);
                
                // Get the new user ID
                $newUserId = $this->db->lastInsertId();
                
                // Assign default permissions based on role
                $this->assignDefaultPermissions($newUserId, $role);
                
                // Log activity
                $this->logActivity('user_created', "User '{$username}' ({$full_name}) created with role '{$role}'");
                
                // Commit transaction
                $this->db->commit();
                
                // Set success message
                $this->flash('success', 'User created successfully!');
                
                // Redirect to users list
                $this->redirect('/admin/users');
                
            } catch (Exception $e) {
                // Rollback on error
                $this->db->rollBack();
                throw $e;
            }
            
        } catch (Exception $e) {
            error_log("UserController store error: " . $e->getMessage());
            
            // Get available roles
            $roles = $this->getAvailableRoles();
            
            // Set data with error for create form
            $this->data = array_merge($this->data, [
                'roles' => $roles,
                'error' => $e->getMessage(),
                'formData' => [
                    'username' => $this->input('username', ''),
                    'email' => $this->input('email', ''),
                    'full_name' => $this->input('full_name', ''),
                    'role' => $this->input('role', 'editor'),
                    'is_active' => $this->input('is_active', 1)
                ],
                'pageTitle' => 'Create New User - FCT College of Nursing Sciences',
                'pageDescription' => 'Create a new system user'
            ]);
            
            $this->render('admin/users/create');
        }
    }
    
    /**
     * Display edit user form
     */
    public function edit($id) {
        try {
            $stmt = $this->db->prepare("SELECT * FROM users WHERE id = ?");
            $stmt->execute([$id]);
            $user = $stmt->fetch();
            
            if (!$user) {
                $this->flash('error', 'User not found.');
                $this->redirect('/admin/users');
                return;
            }
            
            // Don't allow editing own account through this interface
            if ($user['id'] == $_SESSION['user_id']) {
                $this->flash('error', 'Please use profile settings to edit your own account.');
                $this->redirect('/admin/users');
                return;
            }
            
            // Get available roles
            $roles = $this->getAvailableRoles();
            
            // Get user permissions
            $permissionsStmt = $this->db->prepare("
                SELECT permission, is_allowed 
                FROM user_permissions 
                WHERE user_id = ? 
                ORDER BY permission
            ");
            $permissionsStmt->execute([$id]);
            $permissions = $permissionsStmt->fetchAll();
            
            // Set data for view
            $this->data = array_merge($this->data, [
                'user' => $user,
                'roles' => $roles,
                'permissions' => $permissions,
                'availablePermissions' => $this->getAvailablePermissions(),
                'pageTitle' => 'Edit User - ' . $user['full_name'],
                'pageDescription' => 'Edit user details and permissions'
            ]);
            
            $this->render('admin/users/edit');
            
        } catch (Exception $e) {
            error_log("UserController edit error: " . $e->getMessage());
            $this->showError($e->getMessage());
        }
    }
    
    /**
     * Update user
     */
    public function update($id) {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->flash('error', 'Invalid request method.');
            $this->redirect('/admin/users/' . $id . '/edit');
            return;
        }

        try {
            // Validate CSRF token
            $this->validateCsrf();
            
            $username = $this->input('username', '');
            $email = $this->input('email', '');
            $full_name = $this->input('full_name', '');
            $role = $this->input('role', 'editor');
            $is_active = $this->input('is_active', 0) ? 1 : 0;
            
            // Check if updating password
            $password = $this->input('password', '');
            $confirm_password = $this->input('confirm_password', '');
            
            // Validate required fields
            if (empty($username) || empty($email) || empty($full_name)) {
                throw new Exception("Username, email, and full name are required.");
            }
            
            // Validate email
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                throw new Exception("Please enter a valid email address.");
            }
            
            // Check if username or email already exists (excluding current user)
            $stmt = $this->db->prepare("SELECT id FROM users WHERE (username = ? OR email = ?) AND id != ?");
            $stmt->execute([$username, $email, $id]);
            if ($stmt->fetch()) {
                throw new Exception("Username or email already exists.");
            }
            
            // Don't allow editing own account through this interface
            $checkStmt = $this->db->prepare("SELECT id FROM users WHERE id = ?");
            $checkStmt->execute([$id]);
            $user = $checkStmt->fetch();
            
            if ($user && $user['id'] == $_SESSION['user_id']) {
                throw new Exception("Please use profile settings to edit your own account.");
            }
            
            // Start transaction
            $this->db->beginTransaction();
            
            try {
                // Update user
                if (!empty($password)) {
                    if ($password !== $confirm_password) {
                        throw new Exception("Passwords do not match.");
                    }
                    
                    if (strlen($password) < 6) {
                        throw new Exception("Password must be at least 6 characters.");
                    }
                    
                    $password_hash = password_hash($password, PASSWORD_DEFAULT);
                    $stmt = $this->db->prepare("
                        UPDATE users 
                        SET username = ?, email = ?, password_hash = ?, full_name = ?, 
                            role = ?, is_active = ?, updated_at = NOW()
                        WHERE id = ?
                    ");
                    $stmt->execute([$username, $email, $password_hash, $full_name, $role, $is_active, $id]);
                } else {
                    $stmt = $this->db->prepare("
                        UPDATE users 
                        SET username = ?, email = ?, full_name = ?, 
                            role = ?, is_active = ?, updated_at = NOW()
                        WHERE id = ?
                    ");
                    $stmt->execute([$username, $email, $full_name, $role, $is_active, $id]);
                }
                
                // Update permissions if provided
                $permissions = $this->input('permissions', []);
                if (!empty($permissions) && is_array($permissions)) {
                    // Clear existing permissions
                    $this->db->prepare("DELETE FROM user_permissions WHERE user_id = ?")->execute([$id]);
                    
                    // Add new permissions
                    foreach ($permissions as $permission) {
                        $permStmt = $this->db->prepare("
                            INSERT INTO user_permissions (user_id, permission, is_allowed, created_at, updated_at) 
                            VALUES (?, ?, 1, NOW(), NOW())
                        ");
                        $permStmt->execute([$id, $permission]);
                    }
                }
                
                // Log activity
                $this->logActivity('user_updated', "User #{$id} '{$username}' updated");
                
                // Commit transaction
                $this->db->commit();
                
                // Set success message
                $this->flash('success', 'User updated successfully!');
                
                // Redirect to users list
                $this->redirect('/admin/users');
                
            } catch (Exception $e) {
                // Rollback on error
                $this->db->rollBack();
                throw $e;
            }
            
        } catch (Exception $e) {
            error_log("UserController update error: " . $e->getMessage());
            
            // Get user data for the form
            try {
                $stmt = $this->db->prepare("SELECT * FROM users WHERE id = ?");
                $stmt->execute([$id]);
                $user = $stmt->fetch();
                
                // Get available roles
                $roles = $this->getAvailableRoles();
                
                // Get user permissions
                $permissionsStmt = $this->db->prepare("
                    SELECT permission, is_allowed 
                    FROM user_permissions 
                    WHERE user_id = ? 
                    ORDER BY permission
                ");
                $permissionsStmt->execute([$id]);
                $permissions = $permissionsStmt->fetchAll();
                
                $this->data = array_merge($this->data, [
                    'user' => $user,
                    'roles' => $roles,
                    'permissions' => $permissions,
                    'availablePermissions' => $this->getAvailablePermissions(),
                    'error' => $e->getMessage(),
                    'pageTitle' => 'Edit User - ' . ($user['full_name'] ?? 'Unknown'),
                    'pageDescription' => 'Edit user details and permissions'
                ]);
                
                $this->render('admin/users/edit');
            } catch (Exception $ex) {
                $this->showError($e->getMessage());
            }
        }
    }
    
    /**
     * Delete user
     */
    public function destroy($id) {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->flash('error', 'Invalid request method.');
            $this->redirect('/admin/users');
            return;
        }

        try {
            // Validate CSRF token
            $this->validateCsrf();
            
            // Don't allow deleting yourself
            if ($id == $_SESSION['user_id']) {
                throw new Exception("You cannot delete your own account.");
            }
            
            // Get user info before deletion for logging
            $stmt = $this->db->prepare("SELECT username, full_name FROM users WHERE id = ?");
            $stmt->execute([$id]);
            $user = $stmt->fetch();
            
            if (!$user) {
                throw new Exception("User not found.");
            }
            
            // Start transaction
            $this->db->beginTransaction();
            
            try {
                // Delete user permissions first
                $stmt = $this->db->prepare("DELETE FROM user_permissions WHERE user_id = ?");
                $stmt->execute([$id]);
                
                // Delete user activity logs
                $stmt = $this->db->prepare("DELETE FROM activity_logs WHERE user_id = ?");
                $stmt->execute([$id]);
                
                // Delete user
                $stmt = $this->db->prepare("DELETE FROM users WHERE id = ?");
                $stmt->execute([$id]);
                
                // Log activity
                $this->logActivity('user_deleted', "User '{$user['username']}' ({$user['full_name']}) deleted");
                
                // Commit transaction
                $this->db->commit();
                
                // Set success message
                $this->flash('success', 'User deleted successfully!');
                
            } catch (Exception $e) {
                // Rollback on error
                $this->db->rollBack();
                throw $e;
            }
            
        } catch (Exception $e) {
            error_log("UserController destroy error: " . $e->getMessage());
            $this->flash('error', 'Failed to delete user: ' . $e->getMessage());
        }

        $this->redirect('/admin/users');
    }
    
    /**
     * Toggle user status (active/inactive)
     */
    public function toggleStatus($id) {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/admin/users');
            return;
        }

        try {
            // Validate CSRF token
            $this->validateCsrf();
            
            $value = $this->input('value', 0);
            
            // Don't allow deactivating yourself
            if ($id == $_SESSION['user_id'] && !$value) {
                throw new Exception("You cannot deactivate your own account.");
            }
            
            $stmt = $this->db->prepare("UPDATE users SET is_active = ?, updated_at = NOW() WHERE id = ?");
            $stmt->execute([$value, $id]);
            
            $status = $value ? 'activated' : 'deactivated';
            
            // Log activity
            $this->logActivity('user_status_changed', "User #{$id} {$status}");
            
            $this->flash('success', "User {$status} successfully!");
            
        } catch (Exception $e) {
            error_log("UserController toggleStatus error: " . $e->getMessage());
            $this->flash('error', 'Failed to update user status: ' . $e->getMessage());
        }

        $this->redirect('/admin/users');
    }
    
    /**
     * Bulk operations on users
     */
    public function bulkAction() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/admin/users');
            return;
        }

        try {
            // Validate CSRF token
            $this->validateCsrf();
            
            $action = $this->input('action', '');
            $userIds = $this->input('user_ids', []);
            
            if (empty($userIds) || !is_array($userIds)) {
                throw new Exception("No users selected.");
            }
            
            // Filter out current user from selected users
            $userIds = array_filter($userIds, function($id) {
                return $id != $_SESSION['user_id'];
            });
            
            if (empty($userIds)) {
                throw new Exception("Cannot perform bulk action on your own account.");
            }
            
            $ids = implode(',', array_map('intval', $userIds));
            
            switch ($action) {
                case 'activate':
                    $this->db->exec("UPDATE users SET is_active = 1, updated_at = NOW() WHERE id IN ({$ids})");
                    $message = "Selected users activated successfully!";
                    break;
                    
                case 'deactivate':
                    $this->db->exec("UPDATE users SET is_active = 0, updated_at = NOW() WHERE id IN ({$ids})");
                    $message = "Selected users deactivated successfully!";
                    break;
                    
                case 'delete':
                    $this->db->exec("DELETE FROM user_permissions WHERE user_id IN ({$ids})");
                    $this->db->exec("DELETE FROM activity_logs WHERE user_id IN ({$ids})");
                    $this->db->exec("DELETE FROM users WHERE id IN ({$ids})");
                    $message = "Selected users deleted successfully!";
                    break;
                    
                default:
                    throw new Exception("Invalid action specified.");
            }
            
            // Log activity
            $this->logActivity('users_bulk_action', "Bulk action '{$action}' performed on users");
            
            $this->flash('success', $message);
            
        } catch (Exception $e) {
            error_log("UserController bulkAction error: " . $e->getMessage());
            $this->flash('error', 'Failed to perform bulk action: ' . $e->getMessage());
        }

        $this->redirect('/admin/users');
    }
    
    /**
     * Export users to CSV
     */
    public function export() {
        try {
            // Get all users
            $stmt = $this->db->query("
                SELECT u.*, 
                       (SELECT GROUP_CONCAT(permission) FROM user_permissions WHERE user_id = u.id) as permissions
                FROM users u 
                ORDER BY u.created_at DESC
            ");
            $users = $stmt->fetchAll();
            
            // Set headers for CSV download
            $this->header('Content-Type', 'text/csv; charset=utf-8');
            $this->header('Content-Disposition', 'attachment; filename=users_' . date('Y-m-d') . '.csv');
            
            // Create output stream
            $output = fopen('php://output', 'w');
            
            // Add CSV headers
            fputcsv($output, [
                'ID', 'Username', 'Email', 'Full Name', 'Role', 'Status',
                'Last Login', 'Created At', 'Updated At', 'Permissions'
            ]);
            
            // Add data rows
            foreach ($users as $user) {
                fputcsv($output, [
                    $user['id'],
                    $user['username'],
                    $user['email'],
                    $user['full_name'],
                    $user['role'],
                    $user['is_active'] ? 'Active' : 'Inactive',
                    $user['last_login'] ? date('Y-m-d H:i', strtotime($user['last_login'])) : 'Never',
                    date('Y-m-d', strtotime($user['created_at'])),
                    date('Y-m-d', strtotime($user['updated_at'])),
                    $user['permissions'] ?? ''
                ]);
            }
            
            fclose($output);
            exit;
            
        } catch (Exception $e) {
            error_log("UserController export error: " . $e->getMessage());
            $this->flash('error', 'Failed to export users.');
            $this->redirect('/admin/users');
        }
    }
    
    /**
     * Get available roles
     */
    private function getAvailableRoles() {
        return [
            'admin' => 'Administrator',
            'editor' => 'Editor',
            'viewer' => 'Viewer',
            'moderator' => 'Moderator'
        ];
    }
    
    /**
     * Get available permissions
     */
    private function getAvailablePermissions() {
        return [
            'manage_users' => 'Manage Users',
            'manage_applications' => 'Manage Applications',
            'manage_research' => 'Manage Research',
            'manage_news' => 'Manage News',
            'manage_settings' => 'Manage Settings',
            'view_reports' => 'View Reports',
            'view_analytics' => 'View Analytics',
            'export_data' => 'Export Data'
        ];
    }
    
    /**
     * Assign default permissions based on role
     */
    private function assignDefaultPermissions($userId, $role) {
        $defaultPermissions = [];
        
        switch ($role) {
            case 'admin':
                $defaultPermissions = array_keys($this->getAvailablePermissions());
                break;
            case 'editor':
                $defaultPermissions = ['manage_applications', 'manage_research', 'manage_news', 'export_data'];
                break;
            case 'viewer':
                $defaultPermissions = ['view_applications', 'view_research', 'view_news', 'view_reports'];
                break;
            case 'moderator':
                $defaultPermissions = ['manage_applications', 'manage_news', 'view_reports', 'export_data'];
                break;
        }
        
        foreach ($defaultPermissions as $permission) {
            try {
                $stmt = $this->db->prepare("
                    INSERT INTO user_permissions (user_id, permission, is_allowed, created_at, updated_at) 
                    VALUES (?, ?, 1, NOW(), NOW())
                ");
                $stmt->execute([$userId, $permission]);
            } catch (Exception $e) {
                error_log("Failed to assign permission '{$permission}' to user {$userId}: " . $e->getMessage());
            }
        }
    }
    
    /**
     * Log activity
     */
    private function logActivity($action, $description) {
        try {
            $user_id = $_SESSION['user_id'] ?? null;
            $ip_address = $_SERVER['REMOTE_ADDR'] ?? '';
            $user_agent = $_SERVER['HTTP_USER_AGENT'] ?? '';
            
            $stmt = $this->db->prepare("
                INSERT INTO activity_logs 
                (user_id, action, description, ip_address, user_agent, created_at)
                VALUES (?, ?, ?, ?, ?, NOW())
            ");
            $stmt->execute([$user_id, $action, $description, $ip_address, $user_agent]);
        } catch (Exception $e) {
            error_log("Failed to log activity: " . $e->getMessage());
        }
    }
    
    /**
     * Override render method for admin-specific views
     */
    protected function render($view = null, $data = []) {
        // Add CSRF token to all forms
        $data['csrf_token'] = $this->csrfToken();
        
        // Add flash messages
        $data['flash_success'] = $this->getFlash('success');
        $data['flash_error'] = $this->getFlash('error');
        
        // Merge with controller data
        $this->data = array_merge($this->data, $data);
        
        // Call parent render method
        parent::render($view);
    }
    
    /**
     * Show error message
     */
    private function showError($message) {
        $this->data = array_merge($this->data, [
            'error' => $message,
            'pageTitle' => 'Error - FCT College of Nursing Sciences',
            'pageDescription' => 'An error occurred'
        ]);
        
        // Try to render error view
        $errorViewPath = APP_PATH . '/views/admin/error.php';
        if (file_exists($errorViewPath)) {
            $this->render('admin/error');
        } else {
            // Fallback error display
            echo '<div style="padding: 20px; background: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; border-radius: 5px; margin: 20px;">';
            echo '<h3>Error</h3>';
            echo '<p>' . htmlspecialchars($message) . '</p>';
            echo '<p><a href="' . ($this->data['baseUrl'] ?? '') . '/admin/dashboard">Back to Dashboard</a></p>';
            echo '</div>';
        }
    }
}