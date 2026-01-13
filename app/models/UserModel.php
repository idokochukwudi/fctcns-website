<?php
/**
 * User Model
 * Handles user-related database operations
 * 
 * @package FCT_CNS
 */

class UserModel {
    
    /**
     * @var PDO Database connection
     */
    private $db;
    
    /**
     * Constructor
     */
    public function __construct() {
        require_once APP_PATH . '/config/database.php';
        $database = Database::getInstance();
        $this->db = $database->getConnection();
    }
    
    /**
     * Get all users
     */
    public function getAllUsers() {
        $stmt = $this->db->prepare("
            SELECT u.*, 
                   COUNT(up.permission) as permission_count
            FROM users u
            LEFT JOIN user_permissions up ON u.id = up.user_id
            GROUP BY u.id
            ORDER BY u.created_at DESC
        ");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    /**
     * Get user by ID
     */
    public function getUser($id) {
        $stmt = $this->db->prepare("
            SELECT u.*, 
                   GROUP_CONCAT(up.permission) as permissions
            FROM users u
            LEFT JOIN user_permissions up ON u.id = up.user_id AND up.is_allowed = 1
            WHERE u.id = ?
            GROUP BY u.id
        ");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    /**
     * Create new user
     */
    public function createUser($data) {
        $stmt = $this->db->prepare("
            INSERT INTO users (username, email, password, user_role, is_active, created_at, updated_at)
            VALUES (?, ?, ?, ?, ?, NOW(), NOW())
        ");
        
        return $stmt->execute([
            $data['username'],
            $data['email'],
            $data['password'],
            $data['role'],
            $data['is_active'] ?? 1
        ]);
    }
    
    /**
     * Update user
     */
    public function updateUser($id, $data) {
        $fields = [];
        $values = [];
        
        foreach (['username', 'email', 'user_role', 'is_active'] as $field) {
            if (isset($data[$field])) {
                $fields[] = "$field = ?";
                $values[] = $data[$field];
            }
        }
        
        if (isset($data['password'])) {
            $fields[] = "password = ?";
            $values[] = $data['password'];
        }
        
        $fields[] = "updated_at = NOW()";
        $values[] = $id;
        
        $sql = "UPDATE users SET " . implode(', ', $fields) . " WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        
        return $stmt->execute($values);
    }
    
    /**
     * Delete user
     */
    public function deleteUser($id) {
        // First delete user permissions
        $stmt = $this->db->prepare("DELETE FROM user_permissions WHERE user_id = ?");
        $stmt->execute([$id]);
        
        // Then delete user
        $stmt = $this->db->prepare("DELETE FROM users WHERE id = ?");
        return $stmt->execute([$id]);
    }
    
    /**
     * Check if username or email exists
     */
    public function exists($field, $value, $excludeId = null) {
        $sql = "SELECT COUNT(*) as count FROM users WHERE $field = ?";
        $params = [$value];
        
        if ($excludeId) {
            $sql .= " AND id != ?";
            $params[] = $excludeId;
        }
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        $result = $stmt->fetch();
        
        return $result['count'] > 0;
    }
    
    /**
     * Get user permissions
     */
    public function getUserPermissions($userId) {
        $stmt = $this->db->prepare("
            SELECT permission FROM user_permissions 
            WHERE user_id = ? AND is_allowed = 1
        ");
        $stmt->execute([$userId]);
        return $stmt->fetchAll(PDO::FETCH_COLUMN, 0);
    }
    
    /**
     * Update user permissions
     */
    public function updateUserPermissions($userId, $permissions) {
        // Start transaction
        $this->db->beginTransaction();
        
        try {
            // Delete existing permissions
            $stmt = $this->db->prepare("DELETE FROM user_permissions WHERE user_id = ?");
            $stmt->execute([$userId]);
            
            // Insert new permissions
            if (!empty($permissions)) {
                $stmt = $this->db->prepare("
                    INSERT INTO user_permissions (user_id, permission, is_allowed, created_at, updated_at)
                    VALUES (?, ?, 1, NOW(), NOW())
                ");
                
                foreach ($permissions as $permission) {
                    $stmt->execute([$userId, $permission]);
                }
            }
            
            $this->db->commit();
            return true;
            
        } catch (Exception $e) {
            $this->db->rollBack();
            throw $e;
        }
    }
}