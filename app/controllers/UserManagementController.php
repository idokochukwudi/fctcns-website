<?php
/**
 * User Management Controller
 * Complete user management for Nominal Roll system
 */
class UserManagementController extends Controller {
    
    private $db;
    private $perPage = 20;
    
    public function __construct() {
        parent::__construct();
        $this->layout = 'admin';
        
        // Require authentication
        require_once APP_PATH . '/middleware/AuthMiddleware.php';
        AuthMiddleware::authenticate();
        
        // Check if user is admin
        if (!in_array($_SESSION['user_role'], ['admin', 'super_admin'])) {
            $this->flash('error', 'Access denied. Admin privileges required.');
            $this->redirect('/admin/dashboard');
        }
        
        // Setup database
        require_once APP_PATH . '/config/database.php';
        $database = Database::getInstance();
        $this->db = $database->getConnection();
        
        // Initialize data
        $this->data = array_merge($this->data, [
            'user' => $_SESSION ?? [],
            'baseUrl' => BASE_URL,
            'currentPage' => 'user-management'
        ]);
    }
    
    /**
     * List all users with filters
     */
    public function index() {
        try {
            // Get filter parameters
            $page = max(1, (int)($this->query('page', 1)));
            $search = $this->query('search', '');
            $role = $this->query('role', '');
            $status = $this->query('status', '');
            $department = $this->query('department', '');
            
            // Build query
            $whereClauses = [];
            $params = [];
            
            if ($search) {
                $whereClauses[] = "(u.username LIKE ? OR u.email LIKE ? OR u.full_name LIKE ?)";
                $searchTerm = "%{$search}%";
                $params[] = $searchTerm;
                $params[] = $searchTerm;
                $params[] = $searchTerm;
            }
            
            if ($role) {
                $whereClauses[] = "u.role = ?";
                $params[] = $role;
            }
            
            if ($status !== '') {
                $whereClauses[] = "u.is_active = ?";
                $params[] = ($status === 'active') ? 1 : 0;
            }
            
            if ($department) {
                $whereClauses[] = "u.department = ?";
                $params[] = $department;
            }
            
            $where = $whereClauses ? 'WHERE ' . implode(' AND ', $whereClauses) : '';
            
            // Get total count
            $countSql = "SELECT COUNT(*) as total FROM users u $where";
            $countStmt = $this->db->prepare($countSql);
            $countStmt->execute($params);
            $total = $countStmt->fetch()['total'];
            
            // Calculate pagination
            $totalPages = ceil($total / $this->perPage);
            $offset = ($page - 1) * $this->perPage;
            
            // Get users
            $sql = "SELECT u.*, 
                           COUNT(DISTINCT up.permission) as permission_count,
                           COUNT(DISTINCT al.id) as activity_count,
                           (SELECT COUNT(*) FROM nominal_roll_employees WHERE created_by = u.id) as nominal_roll_count
                    FROM users u
                    LEFT JOIN user_permissions up ON u.id = up.user_id
                    LEFT JOIN activity_logs al ON u.id = al.user_id
                    $where
                    GROUP BY u.id
                    ORDER BY u.created_at DESC
                    LIMIT ? OFFSET ?";
            
            $params[] = $this->perPage;
            $params[] = $offset;
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute($params);
            $users = $stmt->fetchAll();
            
            // Get statistics
            $stats = $this->getUserStatistics();
            
            // Get unique departments
            $deptStmt = $this->db->query("SELECT DISTINCT department FROM users WHERE department IS NOT NULL ORDER BY department");
            $departments = $deptStmt->fetchAll(PDO::FETCH_COLUMN);
            
            // Get available roles
            $roles = $this->getAvailableRoles();
            
            $this->data = array_merge($this->data, [
                'users' => $users,
                'stats' => $stats,
                'departments' => $departments,
                'roles' => $roles,
                'pagination' => [
                    'current' => $page,
                    'total' => $totalPages,
                    'total_items' => $total,
                    'per_page' => $this->perPage
                ],
                'filters' => [
                    'search' => $search,
                    'role' => $role,
                    'status' => $status,
                    'department' => $department
                ],
                'pageTitle' => 'User Management - Nominal Roll System',
                'pageDescription' => 'Manage system users and permissions'
            ]);
            
            $this->render('admin/users/index');
            
        } catch (Exception $e) {
            error_log("UserManagementController index error: " . $e->getMessage());
            $this->showError("Failed to load users: " . $e->getMessage());
        }
    }
    
    /**
     * View user details
     */
    public function view($id) {
        try {
            $user = $this->getUserById($id);
            
            if (!$user) {
                $this->flash('error', 'User not found.');
                $this->redirect('/admin/users');
                return;
            }
            
            // Get user permissions
            $permissionsStmt = $this->db->prepare("
                SELECT permission, is_allowed 
                FROM user_permissions 
                WHERE user_id = ? 
                ORDER BY permission
            ");
            $permissionsStmt->execute([$id]);
            $permissions = $permissionsStmt->fetchAll();
            
            // Get user activity logs
            $activityStmt = $this->db->prepare("
                SELECT * FROM activity_logs 
                WHERE user_id = ? 
                ORDER BY created_at DESC 
                LIMIT 20
            ");
            $activityStmt->execute([$id]);
            $activities = $activityStmt->fetchAll();
            
            // Get login history
            $loginStmt = $this->db->prepare("
                SELECT * FROM user_login_history 
                WHERE user_id = ? 
                ORDER BY login_time DESC 
                LIMIT 10
            ");
            $loginStmt->execute([$id]);
            $loginHistory = $loginStmt->fetchAll();
            
            // Get nominal roll created by this user
            $nominalStmt = $this->db->prepare("
                SELECT id, employee_number, CONCAT(surname, ', ', first_name) as name, 
                       created_at, status
                FROM nominal_roll_employees 
                WHERE created_by = ? 
                ORDER BY created_at DESC 
                LIMIT 10
            ");
            $nominalStmt->execute([$id]);
            $nominalRecords = $nominalStmt->fetchAll();
            
            $this->data = array_merge($this->data, [
                'user' => $user,
                'permissions' => $permissions,
                'activities' => $activities,
                'loginHistory' => $loginHistory,
                'nominalRecords' => $nominalRecords,
                'availablePermissions' => $this->getAvailablePermissions(),
                'pageTitle' => 'User Details - ' . $user['full_name'],
                'pageDescription' => 'View user details and activities'
            ]);
            
            $this->render('admin/users/view');
            
        } catch (Exception $e) {
            error_log("UserManagementController view error: " . $e->getMessage());
            $this->showError($e->getMessage());
        }
    }
    
    /**
     * Create user form
     */
    public function create() {
        $roles = $this->getAvailableRoles();
        $departments = $this->getDepartments();
        $permissions = $this->getAvailablePermissions();
        
        $this->data = array_merge($this->data, [
            'roles' => $roles,
            'departments' => $departments,
            'permissions' => $permissions,
            'pageTitle' => 'Create New User',
            'pageDescription' => 'Create a new user account'
        ]);
        
        $this->render('admin/users/create');
    }
    
    /**
     * Store new user
     */
    public function store() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/admin/users/create');
            return;
        }

        try {
            $this->validateCsrf();
            
            $data = [
                'username' => $this->input('username', ''),
                'email' => $this->input('email', ''),
                'password' => $this->input('password', ''),
                'confirm_password' => $this->input('confirm_password', ''),
                'full_name' => $this->input('full_name', ''),
                'phone' => $this->input('phone', ''),
                'role' => $this->input('role', 'editor'),
                'department' => $this->input('department', ''),
                'is_active' => $this->input('is_active', 1) ? 1 : 0,
                'must_change_password' => $this->input('must_change_password', 0) ? 1 : 0,
                'permissions' => $this->input('permissions', [])
            ];
            
            // Validate
            $errors = $this->validateUserData($data, true);
            
            if (!empty($errors)) {
                throw new Exception(implode('<br>', $errors));
            }
            
            // Check if username or email exists
            $stmt = $this->db->prepare("SELECT id FROM users WHERE username = ? OR email = ?");
            $stmt->execute([$data['username'], $data['email']]);
            if ($stmt->fetch()) {
                throw new Exception("Username or email already exists.");
            }
            
            // Hash password
            $password_hash = password_hash($data['password'], PASSWORD_DEFAULT);
            
            $this->db->beginTransaction();
            
            try {
                // Insert user
                $stmt = $this->db->prepare("
                    INSERT INTO users (username, email, password_hash, full_name, phone, 
                                      role, department, is_active, must_change_password,
                                      password_changed_at, created_at, updated_at) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), NOW(), NOW())
                ");
                
                $stmt->execute([
                    $data['username'], $data['email'], $password_hash, $data['full_name'],
                    $data['phone'], $data['role'], $data['department'], $data['is_active'],
                    $data['must_change_password']
                ]);
                
                $userId = $this->db->lastInsertId();
                
                // Assign permissions
                $this->assignPermissions($userId, $data['permissions'], $data['role']);
                
                // Log activity
                $this->logActivity('user_created', "User '{$data['username']}' created with role '{$data['role']}'", $userId);
                
                $this->db->commit();
                
                $this->flash('success', 'User created successfully!');
                $this->redirect('/admin/users');
                
            } catch (Exception $e) {
                $this->db->rollBack();
                throw $e;
            }
            
        } catch (Exception $e) {
            error_log("UserManagementController store error: " . $e->getMessage());
            
            $roles = $this->getAvailableRoles();
            $departments = $this->getDepartments();
            $permissions = $this->getAvailablePermissions();
            
            $this->data = array_merge($this->data, [
                'roles' => $roles,
                'departments' => $departments,
                'permissions' => $permissions,
                'error' => $e->getMessage(),
                'formData' => $_POST,
                'pageTitle' => 'Create New User',
                'pageDescription' => 'Create a new user account'
            ]);
            
            $this->render('admin/users/create');
        }
    }
    
    /**
     * Edit user form
     */
    public function edit($id) {
        try {
            $user = $this->getUserById($id);
            
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
            
            $roles = $this->getAvailableRoles();
            $departments = $this->getDepartments();
            $permissions = $this->getAvailablePermissions();
            
            // Get user permissions
            $userPermStmt = $this->db->prepare("
                SELECT permission, is_allowed 
                FROM user_permissions 
                WHERE user_id = ? 
                ORDER BY permission
            ");
            $userPermStmt->execute([$id]);
            $userPermissions = $userPermStmt->fetchAll();
            
            $this->data = array_merge($this->data, [
                'user' => $user,
                'roles' => $roles,
                'departments' => $departments,
                'permissions' => $permissions,
                'userPermissions' => $userPermissions,
                'pageTitle' => 'Edit User - ' . $user['full_name'],
                'pageDescription' => 'Edit user details and permissions'
            ]);
            
            $this->render('admin/users/edit');
            
        } catch (Exception $e) {
            error_log("UserManagementController edit error: " . $e->getMessage());
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
            $this->validateCsrf();
            
            $data = [
                'username' => $this->input('username', ''),
                'email' => $this->input('email', ''),
                'full_name' => $this->input('full_name', ''),
                'phone' => $this->input('phone', ''),
                'role' => $this->input('role', 'editor'),
                'department' => $this->input('department', ''),
                'is_active' => $this->input('is_active', 0) ? 1 : 0,
                'must_change_password' => $this->input('must_change_password', 0) ? 1 : 0,
                'permissions' => $this->input('permissions', [])
            ];
            
            // Don't allow editing own account through this interface
            if ($id == $_SESSION['user_id']) {
                throw new Exception("Please use profile settings to edit your own account.");
            }
            
            // Validate required fields
            if (empty($data['username']) || empty($data['email']) || empty($data['full_name'])) {
                throw new Exception("Username, email, and full name are required.");
            }
            
            // Validate email
            if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
                throw new Exception("Please enter a valid email address.");
            }
            
            // Check if username or email already exists
            $stmt = $this->db->prepare("SELECT id FROM users WHERE (username = ? OR email = ?) AND id != ?");
            $stmt->execute([$data['username'], $data['email'], $id]);
            if ($stmt->fetch()) {
                throw new Exception("Username or email already exists.");
            }
            
            // Handle profile picture upload
            if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] === UPLOAD_ERR_OK) {
                $uploadResult = $this->handleProfilePictureUpload($id, $data);
                if ($uploadResult) {
                    $data['profile_picture'] = $uploadResult;
                }
            }
            
            // Check if updating password
            $password = $this->input('password', '');
            $confirm_password = $this->input('confirm_password', '');
            
            $this->db->beginTransaction();
            
            try {
                if (!empty($password)) {
                    if ($password !== $confirm_password) {
                        throw new Exception("Passwords do not match.");
                    }
                    
                    if (strlen($password) < 6) {
                        throw new Exception("Password must be at least 6 characters.");
                    }
                    
                    $password_hash = password_hash($password, PASSWORD_DEFAULT);
                    
                    if (isset($data['profile_picture'])) {
                        $stmt = $this->db->prepare("
                            UPDATE users 
                            SET username = ?, email = ?, password_hash = ?, full_name = ?, phone = ?,
                                role = ?, department = ?, is_active = ?, must_change_password = ?,
                                profile_picture = ?, password_changed_at = NOW(), updated_at = NOW()
                            WHERE id = ?
                        ");
                        $stmt->execute([
                            $data['username'], $data['email'], $password_hash, $data['full_name'], $data['phone'],
                            $data['role'], $data['department'], $data['is_active'], $data['must_change_password'],
                            $data['profile_picture'], $id
                        ]);
                    } else {
                        $stmt = $this->db->prepare("
                            UPDATE users 
                            SET username = ?, email = ?, password_hash = ?, full_name = ?, phone = ?,
                                role = ?, department = ?, is_active = ?, must_change_password = ?,
                                password_changed_at = NOW(), updated_at = NOW()
                            WHERE id = ?
                        ");
                        $stmt->execute([
                            $data['username'], $data['email'], $password_hash, $data['full_name'], $data['phone'],
                            $data['role'], $data['department'], $data['is_active'], $data['must_change_password'], $id
                        ]);
                    }
                } else {
                    if (isset($data['profile_picture'])) {
                        $stmt = $this->db->prepare("
                            UPDATE users 
                            SET username = ?, email = ?, full_name = ?, phone = ?,
                                role = ?, department = ?, is_active = ?, must_change_password = ?,
                                profile_picture = ?, updated_at = NOW()
                            WHERE id = ?
                        ");
                        $stmt->execute([
                            $data['username'], $data['email'], $data['full_name'], $data['phone'],
                            $data['role'], $data['department'], $data['is_active'], $data['must_change_password'],
                            $data['profile_picture'], $id
                        ]);
                    } else {
                        $stmt = $this->db->prepare("
                            UPDATE users 
                            SET username = ?, email = ?, full_name = ?, phone = ?,
                                role = ?, department = ?, is_active = ?, must_change_password = ?,
                                updated_at = NOW()
                            WHERE id = ?
                        ");
                        $stmt->execute([
                            $data['username'], $data['email'], $data['full_name'], $data['phone'],
                            $data['role'], $data['department'], $data['is_active'], $data['must_change_password'], $id
                        ]);
                    }
                }
                
                // Update permissions
                $this->updatePermissions($id, $data['permissions'], $data['role']);
                
                // Log activity
                $this->logActivity('user_updated', "User #{$id} '{$data['username']}' updated", $id);
                
                $this->db->commit();
                
                $this->flash('success', 'User updated successfully!');
                $this->redirect('/admin/users');
                
            } catch (Exception $e) {
                $this->db->rollBack();
                throw $e;
            }
            
        } catch (Exception $e) {
            error_log("UserManagementController update error: " . $e->getMessage());
            
            // Get user data for the form
            try {
                $user = $this->getUserById($id);
                $roles = $this->getAvailableRoles();
                $departments = $this->getDepartments();
                $permissions = $this->getAvailablePermissions();
                
                // Get user permissions
                $userPermStmt = $this->db->prepare("
                    SELECT permission, is_allowed 
                    FROM user_permissions 
                    WHERE user_id = ? 
                    ORDER BY permission
                ");
                $userPermStmt->execute([$id]);
                $userPermissions = $userPermStmt->fetchAll();
                
                $this->data = array_merge($this->data, [
                    'user' => $user,
                    'roles' => $roles,
                    'departments' => $departments,
                    'permissions' => $permissions,
                    'userPermissions' => $userPermissions,
                    'error' => $e->getMessage(),
                    'formData' => $_POST,
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
            $this->validateCsrf();
            
            // Don't allow deleting yourself
            if ($id == $_SESSION['user_id']) {
                throw new Exception("You cannot delete your own account.");
            }
            
            $user = $this->getUserById($id);
            
            if (!$user) {
                throw new Exception("User not found.");
            }
            
            // Check if user has created any nominal roll records
            $stmt = $this->db->prepare("SELECT COUNT(*) as count FROM nominal_roll_employees WHERE created_by = ?");
            $stmt->execute([$id]);
            $nominalCount = $stmt->fetch()['count'];
            
            if ($nominalCount > 0) {
                throw new Exception("Cannot delete user who has created nominal roll records. Consider deactivating instead.");
            }
            
            $this->db->beginTransaction();
            
            try {
                // Delete profile picture if exists
                if ($user['profile_picture']) {
                    $uploadDir = PUBLIC_PATH . '/uploads/profiles/';
                    $filePath = $uploadDir . $user['profile_picture'];
                    if (file_exists($filePath)) {
                        unlink($filePath);
                    }
                }
                
                // Delete related records
                $this->db->prepare("DELETE FROM user_permissions WHERE user_id = ?")->execute([$id]);
                $this->db->prepare("DELETE FROM activity_logs WHERE user_id = ?")->execute([$id]);
                $this->db->prepare("DELETE FROM user_login_history WHERE user_id = ?")->execute([$id]);
                
                // Delete user
                $stmt = $this->db->prepare("DELETE FROM users WHERE id = ?");
                $stmt->execute([$id]);
                
                // Log activity
                $this->logActivity('user_deleted', "User '{$user['username']}' deleted", null, $user);
                
                $this->db->commit();
                
                $this->flash('success', 'User deleted successfully!');
                
            } catch (Exception $e) {
                $this->db->rollBack();
                throw $e;
            }
            
        } catch (Exception $e) {
            error_log("UserManagementController destroy error: " . $e->getMessage());
            $this->flash('error', 'Failed to delete user: ' . $e->getMessage());
        }

        $this->redirect('/admin/users');
    }
    
    /**
     * Toggle user status
     */
    public function toggleStatus($id) {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/admin/users');
            return;
        }

        try {
            $this->validateCsrf();
            
            $value = $this->input('value', 0);
            
            // Don't allow deactivating yourself
            if ($id == $_SESSION['user_id'] && !$value) {
                throw new Exception("You cannot deactivate your own account.");
            }
            
            $user = $this->getUserById($id);
            
            $stmt = $this->db->prepare("UPDATE users SET is_active = ?, updated_at = NOW() WHERE id = ?");
            $stmt->execute([$value, $id]);
            
            $status = $value ? 'activated' : 'deactivated';
            
            // Log activity
            $this->logActivity('user_status_changed', "User #{$id} {$status}", $id);
            
            $this->flash('success', "User {$status} successfully!");
            
        } catch (Exception $e) {
            error_log("UserManagementController toggleStatus error: " . $e->getMessage());
            $this->flash('error', 'Failed to update user status: ' . $e->getMessage());
        }

        $this->redirect('/admin/users');
    }
    
    /**
     * Reset user password
     */
    public function resetPassword($id) {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/admin/users');
            return;
        }

        try {
            $this->validateCsrf();
            
            $user = $this->getUserById($id);
            
            if (!$user) {
                throw new Exception("User not found.");
            }
            
            // Generate random password
            $newPassword = bin2hex(random_bytes(8)); // 16 characters
            $password_hash = password_hash($newPassword, PASSWORD_DEFAULT);
            
            $stmt = $this->db->prepare("
                UPDATE users 
                SET password_hash = ?, must_change_password = 1, 
                    password_changed_at = NULL, updated_at = NOW() 
                WHERE id = ?
            ");
            $stmt->execute([$password_hash, $id]);
            
            // Log activity
            $this->logActivity('password_reset', "Password reset for user #{$id}", $id);
            
            $this->flash('success', "Password reset successfully! New password: <code>{$newPassword}</code>");
            
        } catch (Exception $e) {
            error_log("UserManagementController resetPassword error: " . $e->getMessage());
            $this->flash('error', 'Failed to reset password: ' . $e->getMessage());
        }

        $this->redirect('/admin/users');
    }
    
    /**
     * Export users to CSV
     */
    public function export() {
        try {
            // Get filter parameters
            $search = $this->query('search', '');
            $role = $this->query('role', '');
            $status = $this->query('status', '');
            
            // Build query
            $whereClauses = [];
            $params = [];
            
            if ($search) {
                $whereClauses[] = "(u.username LIKE ? OR u.email LIKE ? OR u.full_name LIKE ?)";
                $searchTerm = "%{$search}%";
                $params[] = $searchTerm;
                $params[] = $searchTerm;
                $params[] = $searchTerm;
            }
            
            if ($role) {
                $whereClauses[] = "u.role = ?";
                $params[] = $role;
            }
            
            if ($status !== '') {
                $whereClauses[] = "u.is_active = ?";
                $params[] = ($status === 'active') ? 1 : 0;
            }
            
            $where = $whereClauses ? 'WHERE ' . implode(' AND ', $whereClauses) : '';
            
            // Get users
            $sql = "SELECT u.*, 
                           (SELECT COUNT(*) FROM user_permissions WHERE user_id = u.id) as permission_count,
                           (SELECT COUNT(*) FROM nominal_roll_employees WHERE created_by = u.id) as nominal_roll_count
                FROM users u
                $where
                ORDER BY u.created_at DESC";
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute($params);
            $users = $stmt->fetchAll();
            
            // Set headers
            $this->header('Content-Type', 'text/csv; charset=utf-8');
            $this->header('Content-Disposition', 'attachment; filename=users_' . date('Y-m-d_H-i-s') . '.csv');
            
            // Create output
            $output = fopen('php://output', 'w');
            
            // Add BOM for UTF-8
            fputs($output, $bom = (chr(0xEF) . chr(0xBB) . chr(0xBF)));
            
            // Add headers
            fputcsv($output, [
                'ID', 'Username', 'Email', 'Full Name', 'Phone', 'Role', 'Department',
                'Status', 'Last Login', 'Login Count', 'Created At', 'Permissions Count',
                'Nominal Roll Records', 'Must Change Password'
            ]);
            
            // Add data
            foreach ($users as $user) {
                fputcsv($output, [
                    $user['id'],
                    $user['username'],
                    $user['email'],
                    $user['full_name'],
                    $user['phone'],
                    $user['role'],
                    $user['department'],
                    $user['is_active'] ? 'Active' : 'Inactive',
                    $user['last_login'] ? date('Y-m-d H:i:s', strtotime($user['last_login'])) : 'Never',
                    $user['login_count'],
                    date('Y-m-d H:i:s', strtotime($user['created_at'])),
                    $user['permission_count'],
                    $user['nominal_roll_count'],
                    $user['must_change_password'] ? 'Yes' : 'No'
                ]);
            }
            
            fclose($output);
            exit;
            
        } catch (Exception $e) {
            error_log("UserManagementController export error: " . $e->getMessage());
            $this->flash('error', 'Failed to export users.');
            $this->redirect('/admin/users');
        }
    }
    
    /**
     * Profile settings for current user
     */
    public function profile() {
        try {
            $userId = $_SESSION['user_id'];
            $user = $this->getUserById($userId);
            
            if (!$user) {
                $this->flash('error', 'User not found.');
                $this->redirect('/admin/dashboard');
                return;
            }
            
            $this->data = array_merge($this->data, [
                'user' => $user,
                'pageTitle' => 'My Profile',
                'pageDescription' => 'Update your profile information'
            ]);
            
            $this->render('admin/users/profile');
            
        } catch (Exception $e) {
            error_log("UserManagementController profile error: " . $e->getMessage());
            $this->showError($e->getMessage());
        }
    }
    
    /**
     * Update profile
     */
    public function updateProfile() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/admin/users/profile');
            return;
        }

        try {
            $this->validateCsrf();
            
            $userId = $_SESSION['user_id'];
            $data = [
                'full_name' => $this->input('full_name', ''),
                'phone' => $this->input('phone', ''),
                'email' => $this->input('email', ''),
                'current_password' => $this->input('current_password', ''),
                'new_password' => $this->input('new_password', ''),
                'confirm_password' => $this->input('confirm_password', '')
            ];
            
            // Validate
            if (empty($data['full_name']) || empty($data['email'])) {
                throw new Exception("Full name and email are required.");
            }
            
            if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
                throw new Exception("Please enter a valid email address.");
            }
            
            // Check if email already exists
            $stmt = $this->db->prepare("SELECT id FROM users WHERE email = ? AND id != ?");
            $stmt->execute([$data['email'], $userId]);
            if ($stmt->fetch()) {
                throw new Exception("Email already exists.");
            }
            
            $user = $this->getUserById($userId);
            
            // Handle profile picture upload
            if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] === UPLOAD_ERR_OK) {
                $uploadResult = $this->handleProfilePictureUpload($userId, $data);
                if ($uploadResult) {
                    $profilePicture = $uploadResult;
                }
            }
            
            // Check current password if changing password
            if (!empty($data['new_password'])) {
                if (empty($data['current_password'])) {
                    throw new Exception("Current password is required to change password.");
                }
                
                if (!password_verify($data['current_password'], $user['password_hash'])) {
                    throw new Exception("Current password is incorrect.");
                }
                
                if ($data['new_password'] !== $data['confirm_password']) {
                    throw new Exception("New passwords do not match.");
                }
                
                if (strlen($data['new_password']) < 6) {
                    throw new Exception("New password must be at least 6 characters.");
                }
                
                $password_hash = password_hash($data['new_password'], PASSWORD_DEFAULT);
                
                if (isset($profilePicture)) {
                    $stmt = $this->db->prepare("
                        UPDATE users 
                        SET full_name = ?, phone = ?, email = ?, password_hash = ?, profile_picture = ?,
                            password_changed_at = NOW(), updated_at = NOW()
                        WHERE id = ?
                    ");
                    $stmt->execute([$data['full_name'], $data['phone'], $data['email'], $password_hash, $profilePicture, $userId]);
                } else {
                    $stmt = $this->db->prepare("
                        UPDATE users 
                        SET full_name = ?, phone = ?, email = ?, password_hash = ?,
                            password_changed_at = NOW(), updated_at = NOW()
                        WHERE id = ?
                    ");
                    $stmt->execute([$data['full_name'], $data['phone'], $data['email'], $password_hash, $userId]);
                }
                
                // Log activity
                $this->logActivity('password_changed', "User changed their password", $userId);
                
                $this->flash('success', 'Profile and password updated successfully!');
            } else {
                if (isset($profilePicture)) {
                    $stmt = $this->db->prepare("
                        UPDATE users 
                        SET full_name = ?, phone = ?, email = ?, profile_picture = ?, updated_at = NOW()
                        WHERE id = ?
                    ");
                    $stmt->execute([$data['full_name'], $data['phone'], $data['email'], $profilePicture, $userId]);
                } else {
                    $stmt = $this->db->prepare("
                        UPDATE users 
                        SET full_name = ?, phone = ?, email = ?, updated_at = NOW()
                        WHERE id = ?
                    ");
                    $stmt->execute([$data['full_name'], $data['phone'], $data['email'], $userId]);
                }
                
                $this->flash('success', 'Profile updated successfully!');
            }
            
            // Update session
            $_SESSION['username'] = $user['username'];
            $_SESSION['user_email'] = $data['email'];
            
            $this->redirect('/admin/users/profile');
            
        } catch (Exception $e) {
            error_log("UserManagementController updateProfile error: " . $e->getMessage());
            
            $user = $this->getUserById($_SESSION['user_id']);
            
            $this->data = array_merge($this->data, [
                'user' => $user,
                'error' => $e->getMessage(),
                'formData' => $_POST,
                'pageTitle' => 'My Profile',
                'pageDescription' => 'Update your profile information'
            ]);
            
            $this->render('admin/users/profile');
        }
    }
    
    /**
     * Remove profile picture
     */
    public function removeProfilePicture($id = null) {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/admin/users/profile');
            return;
        }

        try {
            $this->validateCsrf();
            
            $userId = $id ?: $_SESSION['user_id'];
            
            // Don't allow removing other users' profile pictures unless admin
            if ($userId != $_SESSION['user_id'] && !in_array($_SESSION['user_role'], ['admin', 'super_admin'])) {
                throw new Exception("You don't have permission to remove this profile picture.");
            }
            
            $user = $this->getUserById($userId);
            
            if (!$user || empty($user['profile_picture'])) {
                throw new Exception("No profile picture found to remove.");
            }
            
            // Delete file
            $uploadDir = PUBLIC_PATH . '/uploads/profiles/';
            $filePath = $uploadDir . $user['profile_picture'];
            
            if (file_exists($filePath)) {
                unlink($filePath);
            }
            
            // Update database
            $stmt = $this->db->prepare("UPDATE users SET profile_picture = NULL, updated_at = NOW() WHERE id = ?");
            $stmt->execute([$userId]);
            
            // Log activity
            $action = ($userId == $_SESSION['user_id']) ? 'profile_picture_removed' : 'user_profile_picture_removed';
            $this->logActivity($action, "Profile picture removed for user #{$userId}", $userId);
            
            $message = ($userId == $_SESSION['user_id']) 
                ? 'Profile picture removed successfully!' 
                : 'User profile picture removed successfully!';
            
            $this->flash('success', $message);
            
        } catch (Exception $e) {
            error_log("UserManagementController removeProfilePicture error: " . $e->getMessage());
            $this->flash('error', 'Failed to remove profile picture: ' . $e->getMessage());
        }

        if ($id) {
            $this->redirect('/admin/users/' . $id . '/edit');
        } else {
            $this->redirect('/admin/users/profile');
        }
    }
    
    // ====================================================================
    // HELPER METHODS
    // ====================================================================
    
    /**
     * Handle profile picture upload
     */
    private function handleProfilePictureUpload($userId, &$data) {
        if (!isset($_FILES['profile_picture']) || $_FILES['profile_picture']['error'] !== UPLOAD_ERR_OK) {
            return null;
        }
        
        $uploadDir = PUBLIC_PATH . '/uploads/profiles/';
        
        // Create directory if it doesn't exist
        if (!file_exists($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }
        
        // Generate unique filename
        $fileExtension = strtolower(pathinfo($_FILES['profile_picture']['name'], PATHINFO_EXTENSION));
        $fileName = 'profile_' . $userId . '_' . time() . '.' . $fileExtension;
        $uploadPath = $uploadDir . $fileName;
        
        // Validate image
        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
        $fileType = mime_content_type($_FILES['profile_picture']['tmp_name']);
        
        if (!in_array($fileType, $allowedTypes)) {
            throw new Exception("Invalid file type. Only JPG, PNG, GIF, and WebP images are allowed.");
        }
        
        // Check file size (max 5MB)
        $maxFileSize = 5 * 1024 * 1024; // 5MB
        if ($_FILES['profile_picture']['size'] > $maxFileSize) {
            throw new Exception("File is too large. Maximum size is 5MB.");
        }
        
        // Validate image dimensions
        $imageInfo = getimagesize($_FILES['profile_picture']['tmp_name']);
        if (!$imageInfo) {
            throw new Exception("Invalid image file.");
        }
        
        // Resize image if needed (max 500x500)
        $maxWidth = 500;
        $maxHeight = 500;
        list($width, $height) = $imageInfo;
        
        if ($width > $maxWidth || $height > $maxHeight) {
            $resizedImage = $this->resizeImage($_FILES['profile_picture']['tmp_name'], $maxWidth, $maxHeight, $fileExtension);
            if ($resizedImage) {
                // Save resized image
                switch ($fileExtension) {
                    case 'jpg':
                    case 'jpeg':
                        imagejpeg($resizedImage, $uploadPath, 90);
                        break;
                    case 'png':
                        imagepng($resizedImage, $uploadPath, 9);
                        break;
                    case 'gif':
                        imagegif($resizedImage, $uploadPath);
                        break;
                    case 'webp':
                        imagewebp($resizedImage, $uploadPath, 90);
                        break;
                }
                imagedestroy($resizedImage);
            } else {
                // If resize failed, use original
                if (!move_uploaded_file($_FILES['profile_picture']['tmp_name'], $uploadPath)) {
                    throw new Exception("Failed to upload profile picture.");
                }
            }
        } else {
            // Move original file
            if (!move_uploaded_file($_FILES['profile_picture']['tmp_name'], $uploadPath)) {
                throw new Exception("Failed to upload profile picture.");
            }
        }
        
        // Delete old profile picture if exists
        $user = $this->getUserById($userId);
        if ($user && $user['profile_picture']) {
            $oldPath = $uploadDir . $user['profile_picture'];
            if (file_exists($oldPath)) {
                unlink($oldPath);
            }
        }
        
        // Return filename
        return $fileName;
    }
    
    /**
     * Resize image
     */
    private function resizeImage($sourcePath, $maxWidth, $maxHeight, $fileExtension) {
        list($origWidth, $origHeight) = getimagesize($sourcePath);
        
        // Calculate new dimensions
        $ratio = $origWidth / $origHeight;
        
        if ($maxWidth / $maxHeight > $ratio) {
            $newWidth = $maxHeight * $ratio;
            $newHeight = $maxHeight;
        } else {
            $newWidth = $maxWidth;
            $newHeight = $maxWidth / $ratio;
        }
        
        // Create image resource
        switch (strtolower($fileExtension)) {
            case 'jpg':
            case 'jpeg':
                $sourceImage = imagecreatefromjpeg($sourcePath);
                break;
            case 'png':
                $sourceImage = imagecreatefrompng($sourcePath);
                break;
            case 'gif':
                $sourceImage = imagecreatefromgif($sourcePath);
                break;
            case 'webp':
                $sourceImage = imagecreatefromwebp($sourcePath);
                break;
            default:
                return null;
        }
        
        if (!$sourceImage) {
            return null;
        }
        
        // Create new image
        $newImage = imagecreatetruecolor($newWidth, $newHeight);
        
        // Preserve transparency for PNG and GIF
        if ($fileExtension == 'png' || $fileExtension == 'gif') {
            imagecolortransparent($newImage, imagecolorallocatealpha($newImage, 0, 0, 0, 127));
            imagealphablending($newImage, false);
            imagesavealpha($newImage, true);
        }
        
        // Resize image
        imagecopyresampled($newImage, $sourceImage, 0, 0, 0, 0, $newWidth, $newHeight, $origWidth, $origHeight);
        
        imagedestroy($sourceImage);
        
        return $newImage;
    }
    
    /**
     * Get user by ID
     */
    private function getUserById($id) {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }
    
    /**
     * Get user statistics
     */
    private function getUserStatistics() {
        $stats = [];
        
        $queries = [
            'total' => "SELECT COUNT(*) as count FROM users",
            'active' => "SELECT COUNT(*) as count FROM users WHERE is_active = 1",
            'inactive' => "SELECT COUNT(*) as count FROM users WHERE is_active = 0",
            'admin_count' => "SELECT COUNT(*) as count FROM users WHERE role = 'admin'",
            'editor_count' => "SELECT COUNT(*) as count FROM users WHERE role = 'editor'",
            'viewer_count' => "SELECT COUNT(*) as count FROM users WHERE role = 'viewer'",
            'today_logins' => "SELECT COUNT(*) as count FROM users WHERE DATE(last_login) = CURDATE()",
            'must_change_password' => "SELECT COUNT(*) as count FROM users WHERE must_change_password = 1"
        ];
        
        foreach ($queries as $key => $sql) {
            $stmt = $this->db->query($sql);
            $stats[$key] = $stmt->fetch()['count'];
        }
        
        return $stats;
    }
    
    /**
     * Get available roles
     */
    private function getAvailableRoles() {
        return [
            'admin' => 'Administrator',
            'editor' => 'Editor',
            'viewer' => 'Viewer',
            'moderator' => 'Moderator',
            'supervisor' => 'Supervisor'
        ];
    }
    
    /**
     * Get available permissions
     */
    private function getAvailablePermissions() {
        return [
            // Nominal Roll permissions
            'nominal_roll_view' => 'View Nominal Roll',
            'nominal_roll_create' => 'Create Nominal Roll',
            'nominal_roll_edit' => 'Edit Nominal Roll',
            'nominal_roll_delete' => 'Delete Nominal Roll',
            'nominal_roll_bulk_upload' => 'Bulk Upload',
            'nominal_roll_export' => 'Export Data',
            'nominal_roll_settings' => 'Manage Settings',
            'nominal_roll_approve' => 'Approve Drafts',
            
            // User management permissions
            'user_view' => 'View Users',
            'user_create' => 'Create Users',
            'user_edit' => 'Edit Users',
            'user_delete' => 'Delete Users',
            
            // Application permissions
            'application_view' => 'View Applications',
            'application_edit' => 'Edit Applications',
            'application_delete' => 'Delete Applications',
            
            // System permissions
            'system_settings' => 'Manage System Settings',
            'system_backup' => 'Backup System',
            'system_reports' => 'View Reports'
        ];
    }
    
    /**
     * Get departments
     */
    private function getDepartments() {
        $stmt = $this->db->query("
            SELECT DISTINCT department 
            FROM users 
            WHERE department IS NOT NULL AND department != ''
            ORDER BY department
        ");
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }
    
    /**
     * Validate user data
     */
    private function validateUserData($data, $isNew = false) {
        $errors = [];
        
        if (empty($data['username'])) {
            $errors[] = "Username is required.";
        }
        
        if (empty($data['email'])) {
            $errors[] = "Email is required.";
        } elseif (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $errors[] = "Please enter a valid email address.";
        }
        
        if (empty($data['full_name'])) {
            $errors[] = "Full name is required.";
        }
        
        if ($isNew) {
            if (empty($data['password'])) {
                $errors[] = "Password is required.";
            } elseif (strlen($data['password']) < 6) {
                $errors[] = "Password must be at least 6 characters.";
            } elseif ($data['password'] !== $data['confirm_password']) {
                $errors[] = "Passwords do not match.";
            }
        }
        
        return $errors;
    }
    
    /**
     * Assign permissions to user
     */
    private function assignPermissions($userId, $selectedPermissions, $role) {
        // Clear existing permissions
        $this->db->prepare("DELETE FROM user_permissions WHERE user_id = ?")->execute([$userId]);
        
        // Add role-based default permissions
        $defaultPermissions = $this->getDefaultPermissionsForRole($role);
        
        // Merge with selected permissions
        $allPermissions = array_unique(array_merge($defaultPermissions, $selectedPermissions));
        
        foreach ($allPermissions as $permission) {
            $stmt = $this->db->prepare("
                INSERT INTO user_permissions (user_id, permission, is_allowed, created_at, updated_at) 
                VALUES (?, ?, 1, NOW(), NOW())
            ");
            $stmt->execute([$userId, $permission]);
        }
    }
    
    /**
     * Update permissions for user
     */
    private function updatePermissions($userId, $selectedPermissions, $role) {
        // Clear existing permissions
        $this->db->prepare("DELETE FROM user_permissions WHERE user_id = ?")->execute([$userId]);
        
        // Add selected permissions
        foreach ($selectedPermissions as $permission) {
            $stmt = $this->db->prepare("
                INSERT INTO user_permissions (user_id, permission, is_allowed, created_at, updated_at) 
                VALUES (?, ?, 1, NOW(), NOW())
            ");
            $stmt->execute([$userId, $permission]);
        }
    }
    
    /**
     * Get default permissions for role
     */
    private function getDefaultPermissionsForRole($role) {
        $defaults = [
            'admin' => [
                'nominal_roll_view', 'nominal_roll_create', 'nominal_roll_edit', 'nominal_roll_delete',
                'nominal_roll_bulk_upload', 'nominal_roll_export', 'nominal_roll_settings', 'nominal_roll_approve',
                'user_view', 'user_create', 'user_edit', 'user_delete',
                'application_view', 'application_edit', 'application_delete',
                'system_settings', 'system_backup', 'system_reports'
            ],
            'editor' => [
                'nominal_roll_view', 'nominal_roll_create', 'nominal_roll_edit',
                'nominal_roll_bulk_upload', 'nominal_roll_export',
                'application_view', 'application_edit'
            ],
            'viewer' => [
                'nominal_roll_view',
                'application_view'
            ],
            'moderator' => [
                'nominal_roll_view', 'nominal_roll_edit', 'nominal_roll_approve',
                'application_view', 'application_edit'
            ],
            'supervisor' => [
                'nominal_roll_view', 'nominal_roll_create', 'nominal_roll_edit',
                'application_view', 'system_reports'
            ]
        ];
        
        return $defaults[$role] ?? [];
    }
    
    /**
     * Log activity
     */
    private function logActivity($action, $description, $targetUserId = null, $extraData = null) {
        try {
            $userId = $_SESSION['user_id'] ?? null;
            $ip_address = $_SERVER['REMOTE_ADDR'] ?? '';
            $user_agent = $_SERVER['HTTP_USER_AGENT'] ?? '';
            
            // Log to activity_logs
            $stmt = $this->db->prepare("
                INSERT INTO activity_logs 
                (user_id, action, description, ip_address, user_agent, created_at)
                VALUES (?, ?, ?, ?, ?, NOW())
            ");
            $stmt->execute([$userId, $action, $description, $ip_address, $user_agent]);
            
            // Also log to nominal_roll_activity_logs if related to nominal roll
            if (strpos($action, 'nominal_roll') !== false || strpos($action, 'user') !== false) {
                $stmt = $this->db->prepare("
                    INSERT INTO nominal_roll_activity_logs 
                    (user_id, action, description, ip_address, user_agent, created_at)
                    VALUES (?, ?, ?, ?, ?, NOW())
                ");
                $stmt->execute([$userId, $action, $description, $ip_address, $user_agent]);
            }
            
        } catch (Exception $e) {
            error_log("Failed to log activity: " . $e->getMessage());
        }
    }
    
    /**
     * Show error
     */
    private function showError($message) {
        $this->data = array_merge($this->data, [
            'error' => $message,
            'pageTitle' => 'Error - FCT College of Nursing Sciences'
        ]);
        
        $errorViewPath = APP_PATH . '/views/admin/error.php';
        if (file_exists($errorViewPath)) {
            $this->render('admin/error');
        } else {
            echo '<div class="alert alert-danger">' . htmlspecialchars($message) . '</div>';
        }
    }
}