<?php
/**
 * User Controller
 * Handles user management operations
 */
class UserController {
    
    private $db;
    
    public function __construct() {
        // Require authentication first
        require_once __DIR__ . '/../middleware/AuthMiddleware.php';
        AuthMiddleware::authenticate();
        
        // Check if user is admin
        if ($_SESSION['user_role'] !== 'admin') {
            $this->showError("Access denied. Admin privileges required.");
            exit;
        }
        
        // Then setup database
        require_once __DIR__ . '/../config/database.php';
        $database = Database::getInstance();
        $this->db = $database->getConnection();
    }
    
    /**
     * Display all users - UPDATED FOR YOUR SCHEMA
     */
    public function index() {
        try {
            // Get all users with permissions count
            $stmt = $this->db->query("
                SELECT u.*, 
                       (SELECT COUNT(*) FROM user_permissions WHERE user_id = u.id) as permission_count
                FROM users u 
                ORDER BY u.created_at DESC
            ");
            $users = $stmt->fetchAll();
            
            // Load view with data
            $this->loadView('admin/users', ['users' => $users]);
            
        } catch (Exception $e) {
            error_log("UserController index error: " . $e->getMessage());
            $this->showError("Failed to load users.");
        }
    }
    
    /**
     * Display create user form
     */
    public function create() {
        $this->loadView('admin/users_create', []);
    }
    
    /**
     * Save new user - UPDATED FOR YOUR SCHEMA
     */
    public function store() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                $username = $_POST['username'] ?? '';
                $email = $_POST['email'] ?? '';
                $password = $_POST['password'] ?? '';
                $confirm_password = $_POST['confirm_password'] ?? '';
                $full_name = $_POST['full_name'] ?? '';
                $role = $_POST['role'] ?? 'editor';
                $is_active = isset($_POST['is_active']) ? 1 : 1;
                
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
                
                // Check if username or email already exists
                $stmt = $this->db->prepare("SELECT id FROM users WHERE username = ? OR email = ?");
                $stmt->execute([$username, $email]);
                if ($stmt->fetch()) {
                    throw new Exception("Username or email already exists.");
                }
                
                // Hash password - matches your column name 'password_hash'
                $password_hash = password_hash($password, PASSWORD_DEFAULT);
                
                // Prepare SQL - matches your table structure exactly
                $stmt = $this->db->prepare("
                    INSERT INTO users (username, email, password_hash, full_name, role, is_active, created_at, updated_at) 
                    VALUES (?, ?, ?, ?, ?, ?, NOW(), NOW())
                ");
                
                // Execute
                $stmt->execute([$username, $email, $password_hash, $full_name, $role, $is_active]);
                
                // Get the new user ID
                $newUserId = $this->db->lastInsertId();
                
                // Assign default permissions based on role
                $this->assignDefaultPermissions($newUserId, $role);
                
                // Redirect to users list
                header("Location: " . BASE_URL . "/admin/users");
                exit;
                
            } catch (Exception $e) {
                error_log("UserController store error: " . $e->getMessage());
                $this->loadView('admin/users_create', ['error' => $e->getMessage()]);
            }
        } else {
            header("Location: " . BASE_URL . "/admin/users/create");
            exit;
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
                throw new Exception("User not found.");
            }
            
            $this->loadView('admin/users_edit', ['user' => $user]);
            
        } catch (Exception $e) {
            error_log("UserController edit error: " . $e->getMessage());
            $this->showError($e->getMessage());
        }
    }
    
    /**
     * Update user
     */
    public function update($id) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                $username = $_POST['username'] ?? '';
                $email = $_POST['email'] ?? '';
                $full_name = $_POST['full_name'] ?? '';
                $role = $_POST['role'] ?? 'editor';
                $is_active = isset($_POST['is_active']) ? 1 : 0;
                
                // Check if updating password
                $password = $_POST['password'] ?? '';
                $confirm_password = $_POST['confirm_password'] ?? '';
                
                // Validate required fields
                if (empty($username) || empty($email) || empty($full_name)) {
                    throw new Exception("Username, email, and full name are required.");
                }
                
                // Check if username or email already exists (excluding current user)
                $stmt = $this->db->prepare("SELECT id FROM users WHERE (username = ? OR email = ?) AND id != ?");
                $stmt->execute([$username, $email, $id]);
                if ($stmt->fetch()) {
                    throw new Exception("Username or email already exists.");
                }
                
                // Prepare SQL
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
                
                // Redirect to users list
                header("Location: " . BASE_URL . "/admin/users");
                exit;
                
            } catch (Exception $e) {
                error_log("UserController update error: " . $e->getMessage());
                
                // Get user data for the form
                try {
                    $stmt = $this->db->prepare("SELECT * FROM users WHERE id = ?");
                    $stmt->execute([$id]);
                    $user = $stmt->fetch();
                    
                    $this->loadView('admin/users_edit', [
                        'user' => $user,
                        'error' => $e->getMessage()
                    ]);
                } catch (Exception $ex) {
                    $this->showError($e->getMessage());
                }
            }
        } else {
            header("Location: " . BASE_URL . "/admin/users");
            exit;
        }
    }
    
    /**
     * Delete user
     */
    public function destroy($id) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                // Don't allow deleting yourself
                if ($id == $_SESSION['user_id']) {
                    throw new Exception("You cannot delete your own account.");
                }
                
                $stmt = $this->db->prepare("DELETE FROM users WHERE id = ?");
                $stmt->execute([$id]);
                
                // Also delete user permissions
                $stmt = $this->db->prepare("DELETE FROM user_permissions WHERE user_id = ?");
                $stmt->execute([$id]);
                
                // Redirect to users list
                header("Location: " . BASE_URL . "/admin/users");
                exit;
                
            } catch (Exception $e) {
                error_log("UserController destroy error: " . $e->getMessage());
                $this->showError($e->getMessage());
            }
        } else {
            header("Location: " . BASE_URL . "/admin/users");
            exit;
        }
    }
    
    /**
     * Assign default permissions based on role
     */
    private function assignDefaultPermissions($userId, $role) {
        $defaultPermissions = [];
        
        switch ($role) {
            case 'admin':
                $defaultPermissions = ['manage_users', 'manage_applications', 'manage_research', 'manage_news', 'view_reports'];
                break;
            case 'editor':
                $defaultPermissions = ['manage_applications', 'manage_research', 'manage_news'];
                break;
            case 'viewer':
                $defaultPermissions = ['view_applications', 'view_research', 'view_news'];
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
     * Helper method to load views
     */
    private function loadView($view, $data = []) {
        // Define APP_PATH if not defined
        if (!defined('APP_PATH')) {
            define('APP_PATH', dirname(__DIR__));
        }
        
        // Define BASE_URL if not defined
        if (!defined('BASE_URL')) {
            // Try to get BASE_URL from constants file
            $constantsPath = APP_PATH . '/config/constants.php';
            if (file_exists($constantsPath)) {
                require_once $constantsPath;
            } else {
                // Fallback definition
                define('BASE_URL', 'http://localhost/fctcns-website');
            }
        }
        
        // Extract data for the view
        extract($data);
        
        // Include the view file
        $viewPath = APP_PATH . '/views/' . $view . '.php';
        
        if (file_exists($viewPath)) {
            require_once $viewPath;
        } else {
            // Fallback error
            echo "<h1>View not found</h1>";
            echo "<p>View file not found: " . htmlspecialchars($viewPath) . "</p>";
            echo "<p>Looking for: " . htmlspecialchars($view) . ".php</p>";
            echo "<p><a href='" . BASE_URL . "/admin/dashboard'>Return to Dashboard</a></p>";
        }
    }
    
    /**
     * Show error message
     */
    private function showError($message) {
        // Ensure BASE_URL is defined
        if (!defined('BASE_URL')) {
            define('BASE_URL', 'http://localhost/fctcns-website');
        }
        
        echo '<div style="padding: 20px; background: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; border-radius: 5px; margin: 20px;">';
        echo '<h3>Error</h3>';
        echo '<p>' . htmlspecialchars($message) . '</p>';
        echo '<p><a href="' . BASE_URL . '/admin/dashboard">Back to Dashboard</a></p>';
        echo '</div>';
    }
}