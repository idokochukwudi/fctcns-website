<?php
/**
 * Nominal Roll Model
 * Handles all database operations for Nominal Roll Management
 * 
 * @package FCT_CNS
 */

class NominalRollModel {
    
    /**
     * @var PDO Database connection
     */
    private $db;
    
    /**
     * @var array Table name constants
     */
    private const TABLE_EMPLOYEES = 'nominal_roll_employees';
    private const TABLE_SETTINGS = 'nominal_roll_settings';
    private const TABLE_ACTIVITY_LOGS = 'nominal_roll_activity_logs';
    private const TABLE_BULK_UPLOADS = 'nominal_roll_bulk_uploads';
    
    /**
     * Constructor
     */
    public function __construct() {
        require_once __DIR__ . '/../config/database.php';
        $database = Database::getInstance();
        $this->db = $database->getConnection();
    }
    
    /**
     * ============================================
     * EMPLOYEE CRUD OPERATIONS
     * ============================================
     */
    
    /**
     * Create new employee record
     */
    public function createEmployee($data, $userId = null) {
        try {
            $sql = "INSERT INTO " . self::TABLE_EMPLOYEES . " SET
                    employee_number = :employee_number,
                    surname = :surname,
                    first_name = :first_name,
                    middle_name = :middle_name,
                    sex = :sex,
                    date_of_birth = :date_of_birth,
                    marital_status = :marital_status,
                    rank = :rank,
                    grade_level = :grade_level,
                    qualification = :qualification,
                    qualification_date = :qualification_date,
                    date_of_first_appointment = :date_of_first_appointment,
                    date_of_confirmation = :date_of_confirmation,
                    rank_on_first_appointment = :rank_on_first_appointment,
                    date_of_present_appointment = :date_of_present_appointment,
                    state = :state,
                    local_govt_area = :local_govt_area,
                    pf_number = :pf_number,
                    nhf_number = :nhf_number,
                    bank_name = :bank_name,
                    bank_branch = :bank_branch,
                    account_number = :account_number,
                    pension_fund_admin = :pension_fund_admin,
                    pension_number = :pension_number,
                    telephone_number = :telephone_number,
                    passport_photo = :passport_photo,
                    created_by = :created_by,
                    created_at = NOW(),
                    updated_at = NOW()";
            
            $stmt = $this->db->prepare($sql);
            
            // Bind parameters
            $params = [
                ':employee_number' => $data['employee_number'] ?? '',
                ':surname' => $data['surname'] ?? '',
                ':first_name' => $data['first_name'] ?? '',
                ':middle_name' => $data['middle_name'] ?? null,
                ':sex' => $data['sex'] ?? '',
                ':date_of_birth' => $data['date_of_birth'] ?? '',
                ':marital_status' => $data['marital_status'] ?? '',
                ':rank' => $data['rank'] ?? '',
                ':grade_level' => $data['grade_level'] ?? '',
                ':qualification' => $data['qualification'] ?? null,
                ':qualification_date' => !empty($data['qualification_date']) ? $data['qualification_date'] : null,
                ':date_of_first_appointment' => $data['date_of_first_appointment'] ?? '',
                ':date_of_confirmation' => !empty($data['date_of_confirmation']) ? $data['date_of_confirmation'] : null,
                ':rank_on_first_appointment' => $data['rank_on_first_appointment'] ?? null,
                ':date_of_present_appointment' => !empty($data['date_of_present_appointment']) ? $data['date_of_present_appointment'] : null,
                ':state' => $data['state'] ?? '',
                ':local_govt_area' => $data['local_govt_area'] ?? '',
                ':pf_number' => $data['pf_number'] ?? null,
                ':nhf_number' => $data['nhf_number'] ?? null,
                ':bank_name' => $data['bank_name'] ?? null,
                ':bank_branch' => $data['bank_branch'] ?? null,
                ':account_number' => $data['account_number'] ?? null,
                ':pension_fund_admin' => $data['pension_fund_admin'] ?? null,
                ':pension_number' => $data['pension_number'] ?? null,
                ':telephone_number' => $data['telephone_number'] ?? null,
                ':passport_photo' => $data['passport_photo'] ?? null,
                ':created_by' => $userId
            ];
            
            $stmt->execute($params);
            
            $employeeId = $this->db->lastInsertId();
            
            // Log the activity
            if ($employeeId) {
                $this->logActivity($employeeId, $userId, 'employee_created', 'Employee record created', null, $data);
            }
            
            return $employeeId;
            
        } catch (PDOException $e) {
            error_log("NominalRollModel createEmployee error: " . $e->getMessage());
            throw new Exception("Failed to create employee record: " . $e->getMessage());
        }
    }
    
    /**
     * Get employee by ID
     */
    public function getEmployee($id) {
        try {
            $sql = "SELECT e.*, 
                    u1.username as created_by_name, 
                    u2.username as updated_by_name
                    FROM " . self::TABLE_EMPLOYEES . " e
                    LEFT JOIN users u1 ON e.created_by = u1.id
                    LEFT JOIN users u2 ON e.updated_by = u2.id
                    WHERE e.id = :id";
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute([':id' => $id]);
            
            return $stmt->fetch(PDO::FETCH_ASSOC);
            
        } catch (PDOException $e) {
            error_log("NominalRollModel getEmployee error: " . $e->getMessage());
            throw new Exception("Failed to retrieve employee record");
        }
    }
    
    /**
     * Get employee by employee number
     */
    public function getEmployeeByNumber($employeeNumber) {
        try {
            $sql = "SELECT * FROM " . self::TABLE_EMPLOYEES . " 
                    WHERE employee_number = :employee_number";
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute([':employee_number' => $employeeNumber]);
            
            return $stmt->fetch(PDO::FETCH_ASSOC);
            
        } catch (PDOException $e) {
            error_log("NominalRollModel getEmployeeByNumber error: " . $e->getMessage());
            throw new Exception("Failed to retrieve employee record");
        }
    }
    
    /**
     * Update employee record
     */
    public function updateEmployee($id, $data, $userId = null) {
        try {
            // First, get the old data for logging
            $oldData = $this->getEmployee($id);
            
            $sql = "UPDATE " . self::TABLE_EMPLOYEES . " SET
                    employee_number = :employee_number,
                    surname = :surname,
                    first_name = :first_name,
                    middle_name = :middle_name,
                    sex = :sex,
                    date_of_birth = :date_of_birth,
                    marital_status = :marital_status,
                    rank = :rank,
                    grade_level = :grade_level,
                    qualification = :qualification,
                    qualification_date = :qualification_date,
                    date_of_first_appointment = :date_of_first_appointment,
                    date_of_confirmation = :date_of_confirmation,
                    rank_on_first_appointment = :rank_on_first_appointment,
                    date_of_present_appointment = :date_of_present_appointment,
                    state = :state,
                    local_govt_area = :local_govt_area,
                    pf_number = :pf_number,
                    nhf_number = :nhf_number,
                    bank_name = :bank_name,
                    bank_branch = :bank_branch,
                    account_number = :account_number,
                    pension_fund_admin = :pension_fund_admin,
                    pension_number = :pension_number,
                    telephone_number = :telephone_number,
                    passport_photo = :passport_photo,
                    updated_by = :updated_by,
                    updated_at = NOW()
                    WHERE id = :id";
            
            $stmt = $this->db->prepare($sql);
            
            $params = [
                ':id' => $id,
                ':employee_number' => $data['employee_number'] ?? '',
                ':surname' => $data['surname'] ?? '',
                ':first_name' => $data['first_name'] ?? '',
                ':middle_name' => $data['middle_name'] ?? null,
                ':sex' => $data['sex'] ?? '',
                ':date_of_birth' => $data['date_of_birth'] ?? '',
                ':marital_status' => $data['marital_status'] ?? '',
                ':rank' => $data['rank'] ?? '',
                ':grade_level' => $data['grade_level'] ?? '',
                ':qualification' => $data['qualification'] ?? null,
                ':qualification_date' => !empty($data['qualification_date']) ? $data['qualification_date'] : null,
                ':date_of_first_appointment' => $data['date_of_first_appointment'] ?? '',
                ':date_of_confirmation' => !empty($data['date_of_confirmation']) ? $data['date_of_confirmation'] : null,
                ':rank_on_first_appointment' => $data['rank_on_first_appointment'] ?? null,
                ':date_of_present_appointment' => !empty($data['date_of_present_appointment']) ? $data['date_of_present_appointment'] : null,
                ':state' => $data['state'] ?? '',
                ':local_govt_area' => $data['local_govt_area'] ?? '',
                ':pf_number' => $data['pf_number'] ?? null,
                ':nhf_number' => $data['nhf_number'] ?? null,
                ':bank_name' => $data['bank_name'] ?? null,
                ':bank_branch' => $data['bank_branch'] ?? null,
                ':account_number' => $data['account_number'] ?? null,
                ':pension_fund_admin' => $data['pension_fund_admin'] ?? null,
                ':pension_number' => $data['pension_number'] ?? null,
                ':telephone_number' => $data['telephone_number'] ?? null,
                ':passport_photo' => $data['passport_photo'] ?? null,
                ':updated_by' => $userId
            ];
            
            $result = $stmt->execute($params);
            
            // Log the activity
            if ($result) {
                $newData = $this->getEmployee($id);
                $this->logActivity($id, $userId, 'employee_updated', 'Employee record updated', $oldData, $newData);
            }
            
            return $result;
            
        } catch (PDOException $e) {
            error_log("NominalRollModel updateEmployee error: " . $e->getMessage());
            throw new Exception("Failed to update employee record: " . $e->getMessage());
        }
    }
    
    /**
     * Delete employee record
     */
    public function deleteEmployee($id, $userId = null) {
        try {
            // First, get the old data for logging
            $oldData = $this->getEmployee($id);
            
            $sql = "DELETE FROM " . self::TABLE_EMPLOYEES . " WHERE id = :id";
            $stmt = $this->db->prepare($sql);
            $result = $stmt->execute([':id' => $id]);
            
            // Log the activity
            if ($result) {
                $this->logActivity($id, $userId, 'employee_deleted', 'Employee record deleted', $oldData, null);
            }
            
            return $result;
            
        } catch (PDOException $e) {
            error_log("NominalRollModel deleteEmployee error: " . $e->getMessage());
            throw new Exception("Failed to delete employee record: " . $e->getMessage());
        }
    }
    
    /**
     * Get all employees with pagination
     */
    public function getAllEmployees($page = 1, $limit = 20, $filters = []) {
        try {
            $offset = ($page - 1) * $limit;
            
            // Build WHERE clause
            $whereConditions = [];
            $params = [];
            
            if (!empty($filters['search'])) {
                $whereConditions[] = "(e.surname LIKE :search OR e.first_name LIKE :search OR 
                                      e.employee_number LIKE :search OR e.state LIKE :search)";
                $params[':search'] = '%' . $filters['search'] . '%';
            }
            
            if (!empty($filters['state'])) {
                $whereConditions[] = "e.state = :state";
                $params[':state'] = $filters['state'];
            }
            
            if (!empty($filters['grade_level'])) {
                $whereConditions[] = "e.grade_level = :grade_level";
                $params[':grade_level'] = $filters['grade_level'];
            }
            
            if (!empty($filters['rank'])) {
                $whereConditions[] = "e.rank = :rank";
                $params[':rank'] = $filters['rank'];
            }
            
            if (!empty($filters['sex'])) {
                $whereConditions[] = "e.sex = :sex";
                $params[':sex'] = $filters['sex'];
            }
            
            $whereClause = $whereConditions ? "WHERE " . implode(" AND ", $whereConditions) : "";
            
            // Count total records
            $countSql = "SELECT COUNT(*) as total FROM " . self::TABLE_EMPLOYEES . " e $whereClause";
            $countStmt = $this->db->prepare($countSql);
            $countStmt->execute($params);
            $total = $countStmt->fetch(PDO::FETCH_ASSOC)['total'];
            
            // Get paginated data
            $sql = "SELECT e.*, 
                    u1.username as created_by_name,
                    u2.username as updated_by_name
                    FROM " . self::TABLE_EMPLOYEES . " e
                    LEFT JOIN users u1 ON e.created_by = u1.id
                    LEFT JOIN users u2 ON e.updated_by = u2.id
                    $whereClause
                    ORDER BY e.surname, e.first_name
                    LIMIT :limit OFFSET :offset";
            
            $stmt = $this->db->prepare($sql);
            
            // Bind parameters
            foreach ($params as $key => $value) {
                $stmt->bindValue($key, $value);
            }
            $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
            $stmt->bindValue(':offset', (int)$offset, PDO::PARAM_INT);
            
            $stmt->execute();
            $employees = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            return [
                'employees' => $employees,
                'total' => $total,
                'page' => $page,
                'limit' => $limit,
                'total_pages' => ceil($total / $limit)
            ];
            
        } catch (PDOException $e) {
            error_log("NominalRollModel getAllEmployees error: " . $e->getMessage());
            throw new Exception("Failed to retrieve employees: " . $e->getMessage());
        }
    }
    
    /**
     * Get employee statistics
     */
    public function getEmployeeStats() {
        try {
            $stats = [];
            
            // Total employees
            $sql = "SELECT COUNT(*) as total FROM " . self::TABLE_EMPLOYEES;
            $stmt = $this->db->query($sql);
            $stats['total'] = $stmt->fetch(PDO::FETCH_ASSOC)['total'];
            
            // Count by sex
            $sql = "SELECT sex, COUNT(*) as count FROM " . self::TABLE_EMPLOYEES . " GROUP BY sex";
            $stmt = $this->db->query($sql);
            $stats['by_sex'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            // Count by state
            $sql = "SELECT state, COUNT(*) as count FROM " . self::TABLE_EMPLOYEES . " GROUP BY state ORDER BY count DESC";
            $stmt = $this->db->query($sql);
            $stats['by_state'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            // Count by grade level
            $sql = "SELECT grade_level, COUNT(*) as count FROM " . self::TABLE_EMPLOYEES . " GROUP BY grade_level ORDER BY grade_level DESC";
            $stmt = $this->db->query($sql);
            $stats['by_grade'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            // Count by rank
            $sql = "SELECT rank, COUNT(*) as count FROM " . self::TABLE_EMPLOYEES . " GROUP BY rank ORDER BY count DESC LIMIT 10";
            $stmt = $this->db->query($sql);
            $stats['by_rank'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            // Recent updates (last 7 days)
            $sql = "SELECT COUNT(*) as count FROM " . self::TABLE_EMPLOYEES . " 
                    WHERE updated_at >= DATE_SUB(NOW(), INTERVAL 7 DAY)";
            $stmt = $this->db->query($sql);
            $stats['recent_updates'] = $stmt->fetch(PDO::FETCH_ASSOC)['count'];
            
            return $stats;
            
        } catch (PDOException $e) {
            error_log("NominalRollModel getEmployeeStats error: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Get distinct values for filters
     */
    public function getFilterOptions() {
        try {
            $options = [];
            
            // States
            $sql = "SELECT DISTINCT state FROM " . self::TABLE_EMPLOYEES . " WHERE state IS NOT NULL AND state != '' ORDER BY state";
            $stmt = $this->db->query($sql);
            $options['states'] = $stmt->fetchAll(PDO::FETCH_COLUMN);
            
            // Grade Levels
            $sql = "SELECT DISTINCT grade_level FROM " . self::TABLE_EMPLOYEES . " WHERE grade_level IS NOT NULL AND grade_level != '' ORDER BY grade_level DESC";
            $stmt = $this->db->query($sql);
            $options['grade_levels'] = $stmt->fetchAll(PDO::FETCH_COLUMN);
            
            // Ranks
            $sql = "SELECT DISTINCT rank FROM " . self::TABLE_EMPLOYEES . " WHERE rank IS NOT NULL AND rank != '' ORDER BY rank";
            $stmt = $this->db->query($sql);
            $options['ranks'] = $stmt->fetchAll(PDO::FETCH_COLUMN);
            
            // Sex options
            $options['sex_options'] = ['Male', 'Female'];
            
            // Marital status options
            $options['marital_status_options'] = ['Single', 'Married', 'Divorced', 'Widowed'];
            
            return $options;
            
        } catch (PDOException $e) {
            error_log("NominalRollModel getFilterOptions error: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * ============================================
     * SETTINGS OPERATIONS
     * ============================================
     */
    
    /**
     * Get nominal roll settings
     */
    public function getSettings() {
        try {
            $sql = "SELECT * FROM " . self::TABLE_SETTINGS;
            $stmt = $this->db->query($sql);
            $settings = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            $result = [];
            foreach ($settings as $setting) {
                $result[$setting['setting_key']] = $setting['setting_value'];
            }
            
            return $result;
            
        } catch (PDOException $e) {
            error_log("NominalRollModel getSettings error: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Get specific setting
     */
    public function getSetting($key, $default = null) {
        try {
            $sql = "SELECT setting_value FROM " . self::TABLE_SETTINGS . " WHERE setting_key = :key";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([':key' => $key]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            
            return $result ? $result['setting_value'] : $default;
            
        } catch (PDOException $e) {
            error_log("NominalRollModel getSetting error: " . $e->getMessage());
            return $default;
        }
    }
    
    /**
     * Update setting
     */
    public function updateSetting($key, $value, $userId = null) {
        try {
            $sql = "INSERT INTO " . self::TABLE_SETTINGS . " (setting_key, setting_value, updated_by, updated_at)
                    VALUES (:key, :value, :updated_by, NOW())
                    ON DUPLICATE KEY UPDATE
                    setting_value = :value,
                    updated_by = :updated_by,
                    updated_at = NOW()";
            
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([
                ':key' => $key,
                ':value' => $value,
                ':updated_by' => $userId
            ]);
            
        } catch (PDOException $e) {
            error_log("NominalRollModel updateSetting error: " . $e->getMessage());
            throw new Exception("Failed to update setting: " . $e->getMessage());
        }
    }
    
    /**
     * Check if editing is enabled for non-super-admin users
     */
    public function isEditingEnabled() {
        $enabled = $this->getSetting('editing_enabled', '1');
        return $enabled === '1';
    }
    
    /**
     * ============================================
     * BULK UPLOAD OPERATIONS
     * ============================================
     */
    
    /**
     * Create bulk upload record
     */
    public function createBulkUpload($filename, $filePath, $totalRows, $userId = null) {
        try {
            $sql = "INSERT INTO " . self::TABLE_BULK_UPLOADS . " SET
                    filename = :filename,
                    file_path = :file_path,
                    total_rows = :total_rows,
                    uploaded_by = :uploaded_by,
                    status = :status,
                    created_at = NOW()";
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute([
                ':filename' => $filename,
                ':file_path' => $filePath,
                ':total_rows' => $totalRows,
                ':uploaded_by' => $userId,
                ':status' => 'processing'
            ]);
            
            return $this->db->lastInsertId();
            
        } catch (PDOException $e) {
            error_log("NominalRollModel createBulkUpload error: " . $e->getMessage());
            throw new Exception("Failed to create bulk upload record: " . $e->getMessage());
        }
    }
    
    /**
     * Update bulk upload status
     */
    public function updateBulkUpload($id, $data) {
        try {
            $fields = [];
            $params = [':id' => $id];
            
            foreach ($data as $key => $value) {
                $fields[] = "$key = :$key";
                $params[":$key"] = $value;
            }
            
            if (empty($fields)) {
                return false;
            }
            
            $sql = "UPDATE " . self::TABLE_BULK_UPLOADS . " SET " . implode(', ', $fields) . 
                   " WHERE id = :id";
            
            $stmt = $this->db->prepare($sql);
            return $stmt->execute($params);
            
        } catch (PDOException $e) {
            error_log("NominalRollModel updateBulkUpload error: " . $e->getMessage());
            throw new Exception("Failed to update bulk upload record: " . $e->getMessage());
        }
    }
    
    /**
     * Get bulk upload history
     */
    public function getBulkUploads($limit = 20) {
        try {
            $sql = "SELECT bu.*, u.username as uploaded_by_name
                    FROM " . self::TABLE_BULK_UPLOADS . " bu
                    LEFT JOIN users u ON bu.uploaded_by = u.id
                    ORDER BY bu.created_at DESC
                    LIMIT :limit";
            
            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
            $stmt->execute();
            
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
            
        } catch (PDOException $e) {
            error_log("NominalRollModel getBulkUploads error: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * ============================================
     * ACTIVITY LOG OPERATIONS
     * ============================================
     */
    
    /**
     * Log activity
     */
    private function logActivity($employeeId, $userId, $action, $description, $oldData = null, $newData = null) {
        try {
            $sql = "INSERT INTO " . self::TABLE_ACTIVITY_LOGS . " SET
                    employee_id = :employee_id,
                    user_id = :user_id,
                    action = :action,
                    description = :description,
                    old_data = :old_data,
                    new_data = :new_data,
                    ip_address = :ip_address,
                    user_agent = :user_agent,
                    created_at = NOW()";
            
            $stmt = $this->db->prepare($sql);
            
            $oldDataJson = $oldData ? json_encode($oldData, JSON_UNESCAPED_UNICODE) : null;
            $newDataJson = $newData ? json_encode($newData, JSON_UNESCAPED_UNICODE) : null;
            
            return $stmt->execute([
                ':employee_id' => $employeeId,
                ':user_id' => $userId,
                ':action' => $action,
                ':description' => $description,
                ':old_data' => $oldDataJson,
                ':new_data' => $newDataJson,
                ':ip_address' => $_SERVER['REMOTE_ADDR'] ?? '',
                ':user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? ''
            ]);
            
        } catch (PDOException $e) {
            error_log("NominalRollModel logActivity error: " . $e->getMessage());
            // Don't throw exception for logging errors
            return false;
        }
    }
    
    /**
     * Get activity logs
     */
    public function getActivityLogs($employeeId = null, $limit = 50) {
        try {
            $params = [];
            $whereClause = '';
            
            if ($employeeId) {
                $whereClause = "WHERE al.employee_id = :employee_id";
                $params[':employee_id'] = $employeeId;
            }
            
            $sql = "SELECT al.*, e.employee_number, e.surname, e.first_name, u.username as user_name
                    FROM " . self::TABLE_ACTIVITY_LOGS . " al
                    LEFT JOIN " . self::TABLE_EMPLOYEES . " e ON al.employee_id = e.id
                    LEFT JOIN users u ON al.user_id = u.id
                    $whereClause
                    ORDER BY al.created_at DESC
                    LIMIT :limit";
            
            $stmt = $this->db->prepare($sql);
            
            foreach ($params as $key => $value) {
                $stmt->bindValue($key, $value);
            }
            $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
            
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
            
        } catch (PDOException $e) {
            error_log("NominalRollModel getActivityLogs error: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * ============================================
     * EXPORT OPERATIONS
     * ============================================
     */
    
    /**
     * Export employees to CSV
     */
    public function exportEmployeesToCSV($filters = []) {
        try {
            // Build WHERE clause
            $whereConditions = [];
            $params = [];
            
            if (!empty($filters['search'])) {
                $whereConditions[] = "(surname LIKE :search OR first_name LIKE :search OR employee_number LIKE :search)";
                $params[':search'] = '%' . $filters['search'] . '%';
            }
            
            if (!empty($filters['state'])) {
                $whereConditions[] = "state = :state";
                $params[':state'] = $filters['state'];
            }
            
            $whereClause = $whereConditions ? "WHERE " . implode(" AND ", $whereConditions) : "";
            
            $sql = "SELECT * FROM " . self::TABLE_EMPLOYEES . " $whereClause ORDER BY surname, first_name";
            $stmt = $this->db->prepare($sql);
            $stmt->execute($params);
            
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
            
        } catch (PDOException $e) {
            error_log("NominalRollModel exportEmployeesToCSV error: " . $e->getMessage());
            throw new Exception("Failed to export employees: " . $e->getMessage());
        }
    }
    
    /**
     * Get export statistics
     */
    public function getExportStats($data) {
        $stats = [
            'total' => count($data),
            'by_sex' => [],
            'by_state' => [],
            'by_grade' => []
        ];
        
        foreach ($data as $employee) {
            // Count by sex
            $sex = $employee['sex'] ?? 'Unknown';
            $stats['by_sex'][$sex] = ($stats['by_sex'][$sex] ?? 0) + 1;
            
            // Count by state
            $state = $employee['state'] ?? 'Unknown';
            $stats['by_state'][$state] = ($stats['by_state'][$state] ?? 0) + 1;
            
            // Count by grade level
            $grade = $employee['grade_level'] ?? 'Unknown';
            $stats['by_grade'][$grade] = ($stats['by_grade'][$grade] ?? 0) + 1;
        }
        
        return $stats;
    }
    
    /**
     * ============================================
     * VALIDATION & UTILITY METHODS
     * ============================================
     */
    
    /**
     * Validate employee data
     */
    public function validateEmployeeData($data, $isUpdate = false) {
        $errors = [];
        
        // Required fields
        $requiredFields = [
            'employee_number' => 'Employee Number',
            'surname' => 'Surname',
            'first_name' => 'First Name',
            'sex' => 'Sex',
            'date_of_birth' => 'Date of Birth',
            'marital_status' => 'Marital Status',
            'rank' => 'Rank',
            'grade_level' => 'Grade Level',
            'date_of_first_appointment' => 'Date of First Appointment',
            'state' => 'State',
            'local_govt_area' => 'Local Government Area'
        ];
        
        foreach ($requiredFields as $field => $label) {
            if (empty($data[$field])) {
                $errors[] = "$label is required";
            }
        }
        
        // Validate employee number uniqueness (for create only)
        if (!$isUpdate && !empty($data['employee_number'])) {
            $existing = $this->getEmployeeByNumber($data['employee_number']);
            if ($existing) {
                $errors[] = "Employee Number already exists";
            }
        }
        
        // Validate dates
        $dateFields = ['date_of_birth', 'date_of_first_appointment', 'date_of_confirmation', 'qualification_date', 'date_of_present_appointment'];
        foreach ($dateFields as $field) {
            if (!empty($data[$field])) {
                if (!$this->isValidDate($data[$field])) {
                    $errors[] = ucfirst(str_replace('_', ' ', $field)) . " must be a valid date (YYYY-MM-DD)";
                }
            }
        }
        
        // Validate bank account number (if provided)
        if (!empty($data['account_number']) && !preg_match('/^[0-9]{10,20}$/', $data['account_number'])) {
            $errors[] = "Account Number must be 10-20 digits";
        }
        
        // Validate phone number (if provided)
        if (!empty($data['telephone_number']) && !preg_match('/^[0-9\+\-\s\(\)]{7,20}$/', $data['telephone_number'])) {
            $errors[] = "Telephone Number must be valid";
        }
        
        return $errors;
    }
    
    /**
     * Check if date is valid
     */
    private function isValidDate($date, $format = 'Y-m-d') {
        $d = DateTime::createFromFormat($format, $date);
        return $d && $d->format($format) === $date;
    }
    
    /**
     * Generate next employee number
     */
    public function generateEmployeeNumber($prefix = 'EMP') {
        try {
            $year = date('Y');
            $sql = "SELECT COUNT(*) as count FROM " . self::TABLE_EMPLOYEES . " 
                    WHERE employee_number LIKE :prefix";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([':prefix' => $prefix . $year . '%']);
            $count = $stmt->fetch(PDO::FETCH_ASSOC)['count'];
            
            $nextNumber = str_pad($count + 1, 4, '0', STR_PAD_LEFT);
            return $prefix . $year . $nextNumber;
            
        } catch (PDOException $e) {
            error_log("NominalRollModel generateEmployeeNumber error: " . $e->getMessage());
            return $prefix . date('Y') . '0001';
        }
    }
    
    /**
     * Parse bulk upload CSV file
     */
    public function parseCSVFile($filePath) {
        try {
            $data = [];
            $errors = [];
            $rowCount = 0;
            
            if (($handle = fopen($filePath, 'r')) !== false) {
                $headers = fgetcsv($handle, 1000, ',');
                
                // Validate headers
                $expectedHeaders = [
                    'S/N', 'Surname', 'First Name', 'Middle Name', 'Sex', 'Date of Birth',
                    'Marital Status', 'Rank', 'Grade Level (GL)', 'Qualification with date',
                    'Date of 1st Appt.', 'Date of Confirmation', 'Rank on 1st Appt.',
                    'Date of Present. Appt.', 'State', 'Local Govt. Area', 'PF No',
                    'NHF No', 'Bank/Branch', 'Acct. No', 'Pension Fund Adm.', 'Pen. No', 'Tel No'
                ];
                
                // Clean headers (remove BOM if present)
                $headers = array_map(function($header) {
                    // Remove UTF-8 BOM if present
                    $header = preg_replace('/^\xEF\xBB\xBF/', '', $header);
                    return trim($header);
                }, $headers);
                
                if (count($headers) < count($expectedHeaders)) {
                    throw new Exception("CSV file must have at least " . count($expectedHeaders) . " columns");
                }
                
                while (($row = fgetcsv($handle, 1000, ',')) !== false) {
                    $rowCount++;
                    
                    // Skip empty rows
                    if (empty(array_filter($row))) {
                        continue;
                    }
                    
                    // Parse and validate row
                    $employeeData = $this->parseCSVRow($row, $headers, $rowCount);
                    
                    if (isset($employeeData['error'])) {
                        $errors[] = "Row $rowCount: " . $employeeData['error'];
                    } else {
                        $data[] = $employeeData;
                    }
                    
                    // Limit for safety
                    if ($rowCount > 1000) {
                        $errors[] = "File exceeds maximum allowed rows (1000)";
                        break;
                    }
                }
                
                fclose($handle);
            }
            
            return [
                'data' => $data,
                'errors' => $errors,
                'total_rows' => $rowCount,
                'valid_rows' => count($data)
            ];
            
        } catch (Exception $e) {
            error_log("NominalRollModel parseCSVFile error: " . $e->getMessage());
            throw new Exception("Failed to parse CSV file: " . $e->getMessage());
        }
    }
    
    /**
     * Parse single CSV row
     */
    private function parseCSVRow($row, $headers, $rowNumber) {
        $employeeData = [];
        $errors = [];
        
        // Map CSV columns to database fields
        $columnMapping = [
            'S/N' => 'serial_number',
            'Surname' => 'surname',
            'First Name' => 'first_name',
            'Middle Name' => 'middle_name',
            'Sex' => 'sex',
            'Date of Birth' => 'date_of_birth',
            'Marital Status' => 'marital_status',
            'Rank' => 'rank',
            'Grade Level (GL)' => 'grade_level',
            'Qualification with date' => 'qualification',
            'Date of 1st Appt.' => 'date_of_first_appointment',
            'Date of Confirmation' => 'date_of_confirmation',
            'Rank on 1st Appt.' => 'rank_on_first_appointment',
            'Date of Present. Appt.' => 'date_of_present_appointment',
            'State' => 'state',
            'Local Govt. Area' => 'local_govt_area',
            'PF No' => 'pf_number',
            'NHF No' => 'nhf_number',
            'Bank/Branch' => 'bank_info',
            'Acct. No' => 'account_number',
            'Pension Fund Adm.' => 'pension_fund_admin',
            'Pen. No' => 'pension_number',
            'Tel No' => 'telephone_number'
        ];
        
        // Process each column
        foreach ($headers as $index => $header) {
            if (isset($columnMapping[$header]) && isset($row[$index])) {
                $value = trim($row[$index]);
                
                // Special handling for certain fields
                switch ($header) {
                    case 'Sex':
                        $value = $this->normalizeSex($value);
                        break;
                        
                    case 'Marital Status':
                        $value = $this->normalizeMaritalStatus($value);
                        break;
                        
                    case 'Date of Birth':
                    case 'Date of 1st Appt.':
                    case 'Date of Confirmation':
                    case 'Date of Present. Appt.':
                        $value = $this->normalizeDate($value);
                        break;
                        
                    case 'Bank/Branch':
                        // Split bank name and branch
                        $bankParts = explode('/', $value, 2);
                        $employeeData['bank_name'] = trim($bankParts[0] ?? '');
                        $employeeData['bank_branch'] = trim($bankParts[1] ?? '');
                        continue 2; // Skip to next iteration
                        
                    case 'Qualification with date':
                        // Extract qualification and date
                        if (preg_match('/(.*?)\s*(\d{4}-\d{2}-\d{2}|\d{2}\/\d{2}\/\d{4})$/', $value, $matches)) {
                            $employeeData['qualification'] = trim($matches[1]);
                            $employeeData['qualification_date'] = $this->normalizeDate(trim($matches[2]));
                        } else {
                            $employeeData['qualification'] = $value;
                            $employeeData['qualification_date'] = null;
                        }
                        continue 2; // Skip to next iteration
                }
                
                $fieldName = $columnMapping[$header];
                $employeeData[$fieldName] = $value;
            }
        }
        
        // Generate employee number if not provided
        if (empty($employeeData['employee_number'])) {
            $employeeData['employee_number'] = $this->generateEmployeeNumber();
        }
        
        // Validate the parsed data
        $validationErrors = $this->validateEmployeeData($employeeData);
        
        if (!empty($validationErrors)) {
            return ['error' => implode('; ', $validationErrors)];
        }
        
        return $employeeData;
    }
    
    /**
     * Normalize sex value
     */
    private function normalizeSex($value) {
        $value = strtolower(trim($value));
        if (in_array($value, ['m', 'male', '1'])) {
            return 'Male';
        } elseif (in_array($value, ['f', 'female', '2'])) {
            return 'Female';
        }
        return ucfirst($value);
    }
    
    /**
     * Normalize marital status
     */
    private function normalizeMaritalStatus($value) {
        $value = strtolower(trim($value));
        $statusMap = [
            'single' => 'Single',
            'married' => 'Married',
            'divorced' => 'Divorced',
            'widowed' => 'Widowed',
            'sep' => 'Separated',
            'seperated' => 'Separated'
        ];
        
        return $statusMap[$value] ?? ucfirst($value);
    }
    
    /**
     * Normalize date format
     */
    private function normalizeDate($value) {
        if (empty($value)) {
            return null;
        }
        
        // Try different date formats
        $formats = ['Y-m-d', 'd/m/Y', 'd-m-Y', 'm/d/Y', 'Y/m/d'];
        
        foreach ($formats as $format) {
            $date = DateTime::createFromFormat($format, $value);
            if ($date !== false) {
                return $date->format('Y-m-d');
            }
        }
        
        return null;
    }
}