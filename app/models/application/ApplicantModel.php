<?php
/**
 * Applicant Model
 * 
 * Handles applicant (user) data operations
 * 
 * @package FCT_CNS
 * @subpackage Application
 */

require_once MODELS_PATH . '/BaseModel.php';

class ApplicantModel extends BaseModel {
    
    protected $table = 'applicants';
    protected $primaryKey = 'id';
    
    /**
     * Constructor
     */
    public function __construct() {
        parent::__construct();
    }
    
    /**
     * Create applicant from JAMB data
     * 
     * @param array $jambData JAMB candidate data
     * @param string $password Plain text password
     * @return int|false Created applicant ID or false on failure
     */
    public function createFromJamb($jambData, $password) {
        try {
            // Start transaction
            $this->beginTransaction();
            
            $data = [
                'jamb_number' => $jambData['jamb_number'],
                'jamb_candidate_id' => $jambData['id'] ?? null,
                'first_name' => $jambData['first_name'],
                'last_name' => $jambData['last_name'],
                'other_names' => $jambData['other_names'] ?? null,
                'email' => null,  // Set to NULL, will be collected in step 2
                'phone' => null,   // Set to NULL, will be collected in step 2
                'date_of_birth' => null,  // Will be collected in step 2
                'gender' => $jambData['gender'] ?? null,
                'state_of_origin' => $jambData['state_of_origin'] ?? '',
                'lga' => $jambData['lga'] ?? '',
                'address' => '',  // Will be collected in step 2
                'password' => password_hash($password, PASSWORD_BCRYPT),
                'verification_token' => bin2hex(random_bytes(32)),
                'email_verified' => 0,
                'status' => 'active',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ];
            
            $applicantId = $this->insert($data);
            
            if (!$applicantId) {
                throw new Exception("Failed to insert applicant");
            }
            
            if (!empty($jambData['id'])) {
                // Load JambCandidateModel and update
                require_once MODELS_PATH . '/JambCandidateModel.php';
                $jambModel = new JambCandidateModel();
                
                $jambModel->update(
                    [
                        'is_used' => 1, 
                        'used_at' => date('Y-m-d H:i:s'), 
                        'used_by_application_id' => $applicantId
                    ],
                    'id = :id',
                    ['id' => $jambData['id']]
                );
            }
            
            $this->commit();
            return $applicantId;
            
        } catch (Exception $e) {
            $this->rollback();
            error_log("ApplicantModel::createFromJamb - Error: " . $e->getMessage());
            throw $e;
        }
    }
    
    /**
     * Create applicant from registration (Step 2 - Email/Phone collection)
     * 
     * @param array $data Registration data
     * @return int|false Created applicant ID or false on failure
     */
    public function createFromRegistration($data) {
        $insertData = [
            'email' => $data['email'],
            'phone' => $data['phone'],
            'password' => password_hash($data['password'], PASSWORD_BCRYPT),
            'verification_token' => $data['verification_token'],
            'email_verified' => 0,
            'status' => 'active',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ];
        
        return $this->insert($insertData);
    }
    
    /**
     * Find applicant by JAMB number
     * 
     * @param string $jambNumber JAMB registration number
     * @return array|false Applicant data or false
     */
    public function findByJambNumber($jambNumber) {
        return $this->fetchOne(
            "SELECT * FROM {$this->table} WHERE jamb_number = :jamb_number",
            ['jamb_number' => $jambNumber]
        );
    }
    
    /**
     * Find applicant by email
     * 
     * @param string $email Email address
     * @return array|false Applicant data or false
     */
    public function findByEmail($email) {
        return $this->fetchOne(
            "SELECT * FROM {$this->table} WHERE email = :email",
            ['email' => $email]
        );
    }
    
    /**
     * Find applicant by phone
     * 
     * @param string $phone Phone number
     * @return array|false Applicant data or false
     */
    public function findByPhone($phone) {
        return $this->fetchOne(
            "SELECT * FROM {$this->table} WHERE phone = :phone",
            ['phone' => $phone]
        );
    }
    
    /**
     * Find applicant by verification token
     * 
     * @param string $token Verification token
     * @return array|false Applicant data or false
     */
    public function findByVerificationToken($token) {
        return $this->fetchOne(
            "SELECT * FROM {$this->table} WHERE verification_token = :token",
            ['token' => $token]
        );
    }
    
    /**
     * Find applicant by password reset token
     * 
     * @param string $token Password reset token
     * @return array|false Applicant data or false
     */
    public function findByResetToken($token) {
        return $this->fetchOne(
            "SELECT * FROM {$this->table} WHERE password_reset_token = :token AND password_reset_expires > NOW()",
            ['token' => $token]
        );
    }
    
    /**
     * Authenticate applicant
     * 
     * @param string $login Email, phone, or JAMB number
     * @param string $password Plain text password
     * @return array|false Applicant data or false on failure
     */
    public function authenticate($login, $password) {
        $applicant = $this->fetchOne(
            "SELECT * FROM {$this->table} 
             WHERE (email = :login OR phone = :login OR jamb_number = :login) 
             AND status = 'active'",
            ['login' => $login]
        );
        
        if (!$applicant) {
            return false;
        }
        
        // Check if account is locked
        if (!empty($applicant['locked_until']) && strtotime($applicant['locked_until']) > time()) {
            return ['error' => 'account_locked', 'locked_until' => $applicant['locked_until']];
        }
        
        // Verify password
        if (password_verify($password, $applicant['password'])) {
            // Reset login attempts on successful login
            $this->update(
                ['login_attempts' => 0, 'locked_until' => null, 'last_login' => date('Y-m-d H:i:s')],
                'id = :id',
                ['id' => $applicant['id']]
            );
            
            return $applicant;
        }
        
        // Increment login attempts
        $attempts = $applicant['login_attempts'] + 1;
        $lockedUntil = null;
        
        if ($attempts >= 5) {
            $lockedUntil = date('Y-m-d H:i:s', strtotime('+30 minutes'));
        }
        
        $this->update(
            ['login_attempts' => $attempts, 'locked_until' => $lockedUntil],
            'id = :id',
            ['id' => $applicant['id']]
        );
        
        return false;
    }
    
    /**
     * Generate password reset token
     * 
     * @param string $email Email address
     * @return array|false Token data or false
     */
    public function generateResetToken($email) {
        $applicant = $this->findByEmail($email);
        
        if (!$applicant) {
            return false;
        }
        
        $token = bin2hex(random_bytes(32));
        $expires = date('Y-m-d H:i:s', strtotime('+1 hour'));
        
        $this->update(
            ['password_reset_token' => $token, 'password_reset_expires' => $expires],
            'id = :id',
            ['id' => $applicant['id']]
        );
        
        return ['token' => $token, 'email' => $applicant['email']];
    }
    
    /**
     * Reset password
     * 
     * @param string $token Reset token
     * @param string $password New password
     * @return bool Success
     */
    public function resetPassword($token, $password) {
        $applicant = $this->findByResetToken($token);
        
        if (!$applicant) {
            return false;
        }
        
        return $this->update(
            [
                'password' => password_hash($password, PASSWORD_BCRYPT),
                'password_reset_token' => null,
                'password_reset_expires' => null,
                'login_attempts' => 0,
                'locked_until' => null
            ],
            'id = :id',
            ['id' => $applicant['id']]
        );
    }
    
    /**
     * Verify email
     * 
     * @param string $token Verification token
     * @return bool Success
     */
    public function verifyEmail($token) {
        $applicant = $this->findByVerificationToken($token);
        
        if (!$applicant) {
            return false;
        }
        
        return $this->update(
            [
                'email_verified' => 1,
                'email_verified_at' => date('Y-m-d H:i:s'),
                'verification_token' => null
            ],
            'id = :id',
            ['id' => $applicant['id']]
        );
    }
    
    /**
     * Change password
     * 
     * @param int $applicantId Applicant ID
     * @param string $oldPassword Current password
     * @param string $newPassword New password
     * @return bool Success
     */
    public function changePassword($applicantId, $oldPassword, $newPassword) {
        $applicant = $this->find($applicantId);
        
        if (!$applicant || !password_verify($oldPassword, $applicant['password'])) {
            return false;
        }
        
        return $this->update(
            ['password' => password_hash($newPassword, PASSWORD_BCRYPT)],
            'id = :id',
            ['id' => $applicantId]
        );
    }
    
    /**
     * Get applicant with application
     * 
     * @param int $applicantId Applicant ID
     * @return array|false Applicant with application data
     */
    public function getWithApplication($applicantId) {
        return $this->fetchOne(
            "SELECT a.*, app.id as application_id, app.application_number, app.status as application_status,
                    app.application_step, app.submitted_at, app.passport_photo
             FROM {$this->table} a
             LEFT JOIN applications app ON a.id = app.applicant_id
             WHERE a.id = :id",
            ['id' => $applicantId]
        );
    }
    
    /**
     * Log applicant activity
     * 
     * @param int $applicantId Applicant ID
     * @param int $applicationId Application ID
     * @param string $action Action performed
     * @param string $description Description
     * @param mixed $oldData Old data
     * @param mixed $newData New data
     * @return bool Success
     */
    public function logActivity($applicantId, $applicationId, $action, $description, $oldData = null, $newData = null) {
        $sql = "INSERT INTO application_activity_logs 
                (applicant_id, application_id, action, description, ip_address, user_agent, old_data, new_data, created_at) 
                VALUES 
                (:applicant_id, :application_id, :action, :description, :ip_address, :user_agent, :old_data, :new_data, NOW())";
        
        $params = [
            'applicant_id' => $applicantId,
            'application_id' => $applicationId,
            'action' => $action,
            'description' => $description,
            'ip_address' => $_SERVER['REMOTE_ADDR'] ?? null,
            'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? null,
            'old_data' => $oldData ? json_encode($oldData) : null,
            'new_data' => $newData ? json_encode($newData) : null
        ];
        
        $stmt = $this->db->prepare($sql);
        return $stmt->execute($params);
    }
    
    /**
     * Get applicant activity logs
     * 
     * @param int $applicantId Applicant ID
     * @param int $limit Limit
     * @return array Activity logs
     */
    public function getActivityLogs($applicantId, $limit = 50) {
        return $this->fetchAll(
            "SELECT * FROM application_activity_logs 
             WHERE applicant_id = :applicant_id 
             ORDER BY created_at DESC 
             LIMIT :limit",
            ['applicant_id' => $applicantId, 'limit' => $limit]
        );
    }
    
    /**
     * Update last activity timestamp
     * 
     * @param int $applicantId Applicant ID
     * @return bool Success
     */
    public function updateLastActivity($applicantId) {
        return $this->update(
            ['last_activity' => date('Y-m-d H:i:s')],
            'id = :id',
            ['id' => $applicantId]
        );
    }
    
    /**
     * Count total applicants
     * 
     * @return int Total count
     */
    public function countTotal() {
        return $this->fetchColumn(
            "SELECT COUNT(*) FROM {$this->table}"
        );
    }
    
    /**
     * Get applicants registered today
     * 
     * @return array Today's registrations
     */
    public function getTodayRegistrations() {
        return $this->fetchAll(
            "SELECT * FROM {$this->table} WHERE DATE(created_at) = CURDATE() ORDER BY created_at DESC"
        );
    }
    
    /**
     * Search applicants
     * 
     * @param string $query Search query
     * @return array Matching applicants
     */
    public function search($query) {
        $search = '%' . $query . '%';
        
        return $this->fetchAll(
            "SELECT * FROM {$this->table} 
             WHERE jamb_number LIKE :search 
                OR first_name LIKE :search 
                OR last_name LIKE :search 
                OR email LIKE :search 
                OR phone LIKE :search
             ORDER BY created_at DESC",
            ['search' => $search]
        );
    }
    
    /**
     * Deactivate applicant account
     * 
     * @param int $applicantId Applicant ID
     * @return bool Success
     */
    public function deactivate($applicantId) {
        return $this->update(
            ['status' => 'inactive'],
            'id = :id',
            ['id' => $applicantId]
        );
    }
    
    /**
     * Activate applicant account
     * 
     * @param int $applicantId Applicant ID
     * @return bool Success
     */
    public function activate($applicantId) {
        return $this->update(
            ['status' => 'active', 'login_attempts' => 0, 'locked_until' => null],
            'id = :id',
            ['id' => $applicantId]
        );
    }
    
    /**
     * Check if email exists
     * 
     * @param string $email Email address
     * @param int|null $excludeId Applicant ID to exclude
     * @return bool True if exists
     */
    public function emailExists($email, $excludeId = null) {
        $sql = "SELECT COUNT(*) FROM {$this->table} WHERE email = :email";
        $params = ['email' => $email];
        
        if ($excludeId) {
            $sql .= " AND id != :exclude_id";
            $params['exclude_id'] = $excludeId;
        }
        
        return $this->fetchColumn($sql, $params) > 0;
    }
    
    /**
     * Check if phone exists
     * 
     * @param string $phone Phone number
     * @param int|null $excludeId Applicant ID to exclude
     * @return bool True if exists
     */
    public function phoneExists($phone, $excludeId = null) {
        $sql = "SELECT COUNT(*) FROM {$this->table} WHERE phone = :phone";
        $params = ['phone' => $phone];
        
        if ($excludeId) {
            $sql .= " AND id != :exclude_id";
            $params['exclude_id'] = $excludeId;
        }
        
        return $this->fetchColumn($sql, $params) > 0;
    }
    
    /**
     * Check if JAMB number exists
     * 
     * @param string $jambNumber JAMB registration number
     * @param int|null $excludeId Applicant ID to exclude
     * @return bool True if exists
     */
    public function jambNumberExists($jambNumber, $excludeId = null) {
        $sql = "SELECT COUNT(*) FROM {$this->table} WHERE jamb_number = :jamb_number";
        $params = ['jamb_number' => $jambNumber];
        
        if ($excludeId) {
            $sql .= " AND id != :exclude_id";
            $params['exclude_id'] = $excludeId;
        }
        
        return $this->fetchColumn($sql, $params) > 0;
    }
}