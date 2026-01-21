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
    private const TABLE_BACKUPS = 'nominal_roll_backups';
    private const TABLE_REPORTS = 'nominal_roll_reports';
    
    /**
     * Constructor
     */
    public function __construct() {
        require_once __DIR__ . '/../config/database.php';
        $database = Database::getInstance();
        $this->db = $database->getConnection();
        
        // Test connection
        try {
            error_log("=== MODEL CONSTRUCTOR START ===");
            error_log("Database connection test: " . ($this->db ? "Connected" : "Not connected"));
            if ($this->db) {
                $stmt = $this->db->query("SELECT 1");
                error_log("Database query test: " . ($stmt ? "Success" : "Failed"));
                
                // Test table existence
                $tables = [
                    self::TABLE_EMPLOYEES,
                    self::TABLE_SETTINGS,
                    self::TABLE_ACTIVITY_LOGS,
                    self::TABLE_BULK_UPLOADS,
                    self::TABLE_BACKUPS,
                    self::TABLE_REPORTS
                ];
                
                foreach ($tables as $table) {
                    try {
                        $testStmt = $this->db->query("SELECT 1 FROM $table LIMIT 1");
                        error_log("Table $table test: " . ($testStmt ? "Exists" : "Does not exist"));
                    } catch (PDOException $e) {
                        error_log("Table $table test: Does not exist - " . $e->getMessage());
                    }
                }
            }
            error_log("=== MODEL CONSTRUCTOR END ===");
        } catch (Exception $e) {
            error_log("Database connection error: " . $e->getMessage());
        }
    }
    
    /**
     * ============================================
     * EMPLOYEE CRUD OPERATIONS
     * ============================================
     */
    
    /**
     * Create new employee record - FIXED VERSION for bulk upload
     */
    public function createEmployee($data, $userId = null) {
        error_log("=== MODEL createEmployee START (BULK UPLOAD VERSION) ===");
        error_log("Creating employee with data: " . print_r($data, true));
        
        try {
            // Generate employee number if not provided
            if (empty($data['employee_number'])) {
                $data['employee_number'] = $this->generateEmployeeNumber();
                error_log("Generated employee number: " . $data['employee_number']);
            }
            
            // Ensure required fields are set
            $data['status'] = $data['status'] ?? 'active';
            $data['is_draft'] = $data['is_draft'] ?? 0;
            $data['created_by'] = $userId;
            
            // Prepare SQL statement
            $fields = [];
            $placeholders = [];
            $values = [];
            
            // Map data to database columns
            $columnMapping = [
                'employee_number' => 'employee_number',
                'surname' => 'surname',
                'first_name' => 'first_name',
                'middle_name' => 'middle_name',
                'sex' => 'sex',
                'date_of_birth' => 'date_of_birth',
                'marital_status' => 'marital_status',
                'nationality' => 'nationality',
                'religion' => 'religion',
                'blood_group' => 'blood_group',
                'genotype' => 'genotype',
                'disability' => 'disability',
                'disability_type' => 'disability_type',
                'rank' => 'rank',
                'grade_level' => 'grade_level',
                'step' => 'step',
                'cadre' => 'cadre',
                'staff_type' => 'staff_type',
                'employment_type' => 'employment_type',
                'appointment_type' => 'appointment_type',
                'department' => 'department',
                'professional_certifications' => 'professional_certifications',
                'institution_attended' => 'institution_attended',
                'course_of_study' => 'course_of_study',
                'class_of_degree' => 'class_of_degree',
                'highest_qualification' => 'highest_qualification',
                'year_of_highest_qualification' => 'year_of_highest_qualification',
                'additional_qualifications' => 'additional_qualifications',
                'qualification_date' => 'qualification_date',
                'date_of_first_appointment' => 'date_of_first_appointment',
                'date_of_confirmation' => 'date_of_confirmation',
                'rank_on_first_appointment' => 'rank_on_first_appointment',
                'date_of_present_appointment' => 'date_of_present_appointment',
                'retirement_date' => 'retirement_date',
                'state' => 'state',
                'local_govt_area' => 'local_govt_area',
                'state_of_residence' => 'state_of_residence',
                'geopolitical_zone' => 'geopolitical_zone',
                'residential_address' => 'residential_address',
                'contact_address' => 'contact_address',
                'pf_number' => 'pf_number',
                'nin' => 'nin',
                'nhf_number' => 'nhf_number',
                'bank_name' => 'bank_name',
                'other_bank_name' => 'other_bank_name',
                'bank_branch' => 'bank_branch',
                'account_number' => 'account_number',
                'account_name' => 'account_name',
                'pension_fund_admin' => 'pension_fund_admin',
                'other_pension_fund_admin' => 'other_pension_fund_admin',
                'pension_number' => 'pension_number',
                'tin_number' => 'tin_number',
                'salary_structure' => 'salary_structure',
                'telephone_number' => 'telephone_number',
                'email' => 'email',
                'emergency_contact_name' => 'emergency_contact_name',
                'emergency_contact_phone' => 'emergency_contact_phone',
                'emergency_contact_relationship' => 'emergency_contact_relationship',
                'next_of_kin_name' => 'next_of_kin_name',
                'next_of_kin_phone' => 'next_of_kin_phone',
                'next_of_kin_address' => 'next_of_kin_address',
                'next_of_kin_relationship' => 'next_of_kin_relationship',
                'passport_photo' => 'passport_photo',
                'status' => 'status',
                'is_draft' => 'is_draft',
                'created_by' => 'created_by'
            ];
            
            foreach ($columnMapping as $field => $column) {
                if (isset($data[$field]) && $data[$field] !== '') {
                    $fields[] = $column;
                    $placeholders[] = '?';
                    $values[] = $data[$field];
                }
            }
            
            // Add created_at and updated_at
            $fields[] = 'created_at';
            $fields[] = 'updated_at';
            $placeholders[] = 'NOW()';
            $placeholders[] = 'NOW()';
            
            if (empty($fields)) {
                error_log("ERROR: No fields to insert");
                return false;
            }
            
            $sql = "INSERT INTO " . self::TABLE_EMPLOYEES . " (" . implode(', ', $fields) . ") 
                    VALUES (" . implode(', ', $placeholders) . ")";
            
            error_log("SQL: " . $sql);
            error_log("Values: " . print_r($values, true));
            
            $stmt = $this->db->prepare($sql);
            $result = $stmt->execute($values);
            
            if ($result) {
                $employeeId = $this->db->lastInsertId();
                error_log("Employee created successfully! ID: " . $employeeId);
                
                // Log activity
                if ($userId) {
                    $this->logActivity($employeeId, $userId, 'employee_created', 'Created employee via bulk upload', null, $data);
                }
                
                return $employeeId;
            } else {
                error_log("ERROR: Failed to execute SQL statement");
                $errorInfo = $stmt->errorInfo();
                error_log("SQL Error: " . print_r($errorInfo, true));
                return false;
            }
            
        } catch (Exception $e) {
            error_log("EXCEPTION in createEmployee: " . $e->getMessage());
            error_log("Stack trace: " . $e->getTraceAsString());
            return false;
        }
    }
    
    /**
     * Create new employee record - LEGACY VERSION for regular forms
     * (Keeping this for backward compatibility with existing form submissions)
     */
    public function createEmployeeLegacy($data, $userId = null) {
        try {
            error_log("=== MODEL createEmployeeLegacy START ===");
            error_log("Data to insert: " . print_r($data, true));
            error_log("User ID: " . $userId);
            
            $sql = "INSERT INTO " . self::TABLE_EMPLOYEES . " SET
                    employee_number = :employee_number,
                    surname = :surname,
                    first_name = :first_name,
                    middle_name = :middle_name,
                    sex = :sex,
                    date_of_birth = :date_of_birth,
                    marital_status = :marital_status,
                    nationality = :nationality,
                    religion = :religion,
                    `rank` = :rank,
                    grade_level = :grade_level,
                    step = :step,
                    cadre = :cadre,
                    staff_type = :staff_type,
                    employment_type = :employment_type,
                    appointment_type = :appointment_type,
                    department = :department,
                    highest_qualification = :highest_qualification,
                    year_of_highest_qualification = :year_of_highest_qualification,
                    institution_attended = :institution_attended,
                    course_of_study = :course_of_study,
                    class_of_degree = :class_of_degree,
                    professional_certifications = :professional_certifications,
                    additional_qualifications = :additional_qualifications,
                    date_of_first_appointment = :date_of_first_appointment,
                    date_of_confirmation = :date_of_confirmation,
                    rank_on_first_appointment = :rank_on_first_appointment,
                    date_of_present_appointment = :date_of_present_appointment,
                    state = :state,
                    local_govt_area = :local_govt_area,
                    geopolitical_zone = :geopolitical_zone,
                    state_of_residence = :state_of_residence,
                    residential_address = :residential_address,
                    contact_address = :contact_address,
                    pf_number = :pf_number,
                    nhf_number = :nhf_number,
                    nin = :nin,
                    telephone_number = :telephone_number,
                    email = :email,
                    blood_group = :blood_group,
                    genotype = :genotype,
                    disability = :disability,
                    disability_type = :disability_type,
                    bank_name = :bank_name,
                    other_bank_name = :other_bank_name,
                    bank_branch = :bank_branch,
                    account_number = :account_number,
                    account_name = :account_name,
                    pension_fund_admin = :pension_fund_admin,
                    other_pension_fund_admin = :other_pension_fund_admin,
                    pension_number = :pension_number,
                    tin_number = :tin_number,
                    salary_structure = :salary_structure,
                    emergency_contact_name = :emergency_contact_name,
                    emergency_contact_phone = :emergency_contact_phone,
                    emergency_contact_relationship = :emergency_contact_relationship,
                    next_of_kin_name = :next_of_kin_name,
                    next_of_kin_phone = :next_of_kin_phone,
                    next_of_kin_relationship = :next_of_kin_relationship,
                    next_of_kin_address = :next_of_kin_address,
                    passport_photo = :passport_photo,
                    is_draft = :is_draft,
                    status = :status,
                    created_by = :created_by,
                    created_at = NOW(),
                    updated_at = NOW()";
            
            error_log("SQL Query: " . $sql);
            
            $stmt = $this->db->prepare($sql);
            
            $params = [
                ':employee_number' => $data['employee_number'] ?? '',
                ':surname' => $data['surname'] ?? '',
                ':first_name' => $data['first_name'] ?? '',
                ':middle_name' => $data['middle_name'] ?? null,
                ':sex' => $data['sex'] ?? '',
                ':date_of_birth' => $data['date_of_birth'] ?? '',
                ':marital_status' => $data['marital_status'] ?? '',
                ':nationality' => $data['nationality'] ?? null,
                ':religion' => $data['religion'] ?? null,
                ':rank' => $data['rank'] ?? '',
                ':grade_level' => $data['grade_level'] ?? '',
                ':step' => $data['step'] ?? null,
                ':cadre' => $data['cadre'] ?? null,
                ':staff_type' => $data['staff_type'] ?? null,
                ':employment_type' => $data['employment_type'] ?? null,
                ':appointment_type' => $data['appointment_type'] ?? null,
                ':department' => $data['department'] ?? null,
                ':highest_qualification' => $data['highest_qualification'] ?? null,
                ':year_of_highest_qualification' => !empty($data['year_of_highest_qualification']) ? $data['year_of_highest_qualification'] : null,
                ':institution_attended' => $data['institution_attended'] ?? null,
                ':course_of_study' => $data['course_of_study'] ?? null,
                ':class_of_degree' => $data['class_of_degree'] ?? null,
                ':professional_certifications' => $data['professional_certifications'] ?? null,
                ':additional_qualifications' => $data['additional_qualifications'] ?? null,
                ':date_of_first_appointment' => $data['date_of_first_appointment'] ?? '',
                ':date_of_confirmation' => !empty($data['date_of_confirmation']) ? $data['date_of_confirmation'] : null,
                ':rank_on_first_appointment' => $data['rank_on_first_appointment'] ?? null,
                ':date_of_present_appointment' => !empty($data['date_of_present_appointment']) ? $data['date_of_present_appointment'] : null,
                ':state' => $data['state'] ?? '',
                ':local_govt_area' => $data['local_govt_area'] ?? '',
                ':geopolitical_zone' => $data['geopolitical_zone'] ?? null,
                ':state_of_residence' => $data['state_of_residence'] ?? null,
                ':residential_address' => $data['residential_address'] ?? null,
                ':contact_address' => $data['contact_address'] ?? null,
                ':pf_number' => $data['pf_number'] ?? null,
                ':nhf_number' => $data['nhf_number'] ?? null,
                ':nin' => $data['nin'] ?? null,
                ':telephone_number' => $data['telephone_number'] ?? null,
                ':email' => $data['email'] ?? null,
                ':blood_group' => $data['blood_group'] ?? null,
                ':genotype' => $data['genotype'] ?? null,
                ':disability' => $data['disability'] ?? 'No',
                ':disability_type' => $data['disability_type'] ?? null,
                ':bank_name' => $data['bank_name'] ?? null,
                ':other_bank_name' => $data['other_bank_name'] ?? null,
                ':bank_branch' => $data['bank_branch'] ?? null,
                ':account_number' => $data['account_number'] ?? null,
                ':account_name' => $data['account_name'] ?? null,
                ':pension_fund_admin' => $data['pension_fund_admin'] ?? null,
                ':other_pension_fund_admin' => $data['other_pension_fund_admin'] ?? null,
                ':pension_number' => $data['pension_number'] ?? null,
                ':tin_number' => $data['tin_number'] ?? null,
                ':salary_structure' => $data['salary_structure'] ?? null,
                ':emergency_contact_name' => $data['emergency_contact_name'] ?? null,
                ':emergency_contact_phone' => $data['emergency_contact_phone'] ?? null,
                ':emergency_contact_relationship' => $data['emergency_contact_relationship'] ?? null,
                ':next_of_kin_name' => $data['next_of_kin_name'] ?? null,
                ':next_of_kin_phone' => $data['next_of_kin_phone'] ?? null,
                ':next_of_kin_relationship' => $data['next_of_kin_relationship'] ?? null,
                ':next_of_kin_address' => $data['next_of_kin_address'] ?? null,
                ':passport_photo' => $data['passport_photo'] ?? null,
                ':is_draft' => $data['is_draft'] ?? 0,
                ':status' => $data['status'] ?? 'active',
                ':created_by' => $userId
            ];
            
            error_log("Parameters: " . print_r($params, true));
            
            $result = $stmt->execute($params);
            error_log("Execute result: " . ($result ? 'Success' : 'Failed'));
            
            if (!$result) {
                error_log("PDO Error: " . print_r($stmt->errorInfo(), true));
            }
            
            $employeeId = $this->db->lastInsertId();
            error_log("Last insert ID: " . $employeeId);
            
            // Log the activity
            if ($employeeId) {
                $this->logActivity($employeeId, $userId, 'employee_created', 'Employee record created', null, $data);
            }
            
            error_log("=== MODEL createEmployeeLegacy END ===");
            return $employeeId;
            
        } catch (PDOException $e) {
            error_log("PDOException in createEmployeeLegacy: " . $e->getMessage());
            error_log("Error Code: " . $e->getCode());
            error_log("SQL State: " . $e->errorInfo[0] ?? 'N/A');
            error_log("Driver Error: " . $e->errorInfo[1] ?? 'N/A');
            error_log("Driver Message: " . $e->errorInfo[2] ?? 'N/A');
            
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
            
            $employee = $stmt->fetch(PDO::FETCH_ASSOC);
            
            // Parse additional qualifications JSON
            if ($employee && !empty($employee['additional_qualifications'])) {
                $employee['additional_qualifications'] = json_decode($employee['additional_qualifications'], true);
            }
            
            return $employee;
            
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
            
            $employee = $stmt->fetch(PDO::FETCH_ASSOC);
            
            // Parse additional qualifications JSON
            if ($employee && !empty($employee['additional_qualifications'])) {
                $employee['additional_qualifications'] = json_decode($employee['additional_qualifications'], true);
            }
            
            return $employee;
            
        } catch (PDOException $e) {
            error_log("NominalRollModel getEmployeeByNumber error: " . $e->getMessage());
            throw new Exception("Failed to retrieve employee record");
        }
    }
    
    /**
     * Update employee record - DEBUG VERSION WITH EXTENSIVE LOGGING
     */
    public function updateEmployee($id, $data, $userId = null) {
        error_log("=== MODEL updateEmployee() called ===");
        error_log("Employee ID: $id");
        error_log("Data to update: " . print_r($data, true));
        error_log("User ID: " . $userId);
        
        try {
            error_log("=== Step 1: Getting old data for logging ===");
            // First, get the old data for logging
            $oldData = $this->getEmployee($id);
            error_log("Old data retrieved: " . ($oldData ? "Yes" : "No"));
            if ($oldData) {
                error_log("Old employee number: " . ($oldData['employee_number'] ?? 'N/A'));
                error_log("Old surname: " . ($oldData['surname'] ?? 'N/A'));
            } else {
                error_log("ERROR: Could not find employee with ID: $id");
                throw new Exception("Employee not found with ID: $id");
            }
            
            error_log("=== Step 2: Building SQL query ===");
            // FIXED: Removed 'qualification' field and added all new fields from the form
            $sql = "UPDATE " . self::TABLE_EMPLOYEES . " SET
                    employee_number = :employee_number,
                    surname = :surname,
                    first_name = :first_name,
                    middle_name = :middle_name,
                    sex = :sex,
                    date_of_birth = :date_of_birth,
                    marital_status = :marital_status,
                    nationality = :nationality,
                    religion = :religion,
                    `rank` = :rank,
                    grade_level = :grade_level,
                    step = :step,
                    cadre = :cadre,
                    staff_type = :staff_type,
                    employment_type = :employment_type,
                    appointment_type = :appointment_type,
                    department = :department,
                    highest_qualification = :highest_qualification,
                    year_of_highest_qualification = :year_of_highest_qualification,
                    institution_attended = :institution_attended,
                    course_of_study = :course_of_study,
                    class_of_degree = :class_of_degree,
                    professional_certifications = :professional_certifications,
                    additional_qualifications = :additional_qualifications,
                    date_of_first_appointment = :date_of_first_appointment,
                    date_of_confirmation = :date_of_confirmation,
                    rank_on_first_appointment = :rank_on_first_appointment,
                    date_of_present_appointment = :date_of_present_appointment,
                    state = :state,
                    local_govt_area = :local_govt_area,
                    geopolitical_zone = :geopolitical_zone,
                    state_of_residence = :state_of_residence,
                    residential_address = :residential_address,
                    contact_address = :contact_address,
                    pf_number = :pf_number,
                    nhf_number = :nhf_number,
                    nin = :nin,
                    telephone_number = :telephone_number,
                    email = :email,
                    blood_group = :blood_group,
                    genotype = :genotype,
                    disability = :disability,
                    disability_type = :disability_type,
                    bank_name = :bank_name,
                    other_bank_name = :other_bank_name,
                    bank_branch = :bank_branch,
                    account_number = :account_number,
                    account_name = :account_name,
                    pension_fund_admin = :pension_fund_admin,
                    other_pension_fund_admin = :other_pension_fund_admin,
                    pension_number = :pension_number,
                    tin_number = :tin_number,
                    salary_structure = :salary_structure,
                    emergency_contact_name = :emergency_contact_name,
                    emergency_contact_phone = :emergency_contact_phone,
                    emergency_contact_relationship = :emergency_contact_relationship,
                    next_of_kin_name = :next_of_kin_name,
                    next_of_kin_phone = :next_of_kin_phone,
                    next_of_kin_relationship = :next_of_kin_relationship,
                    next_of_kin_address = :next_of_kin_address,
                    passport_photo = :passport_photo,
                    is_draft = :is_draft,
                    status = :status,
                    updated_by = :updated_by,
                    updated_at = NOW()
                    WHERE id = :id";
            
            error_log("SQL Query: " . $sql);
            
            error_log("=== Step 3: Preparing SQL statement ===");
            $stmt = $this->db->prepare($sql);
            error_log("Statement prepared: " . ($stmt ? "Yes" : "No"));
            
            error_log("=== Step 4: Building parameters array ===");
            $params = [
                ':id' => $id,
                ':employee_number' => $data['employee_number'] ?? '',
                ':surname' => $data['surname'] ?? '',
                ':first_name' => $data['first_name'] ?? '',
                ':middle_name' => $data['middle_name'] ?? null,
                ':sex' => $data['sex'] ?? '',
                ':date_of_birth' => $data['date_of_birth'] ?? '',
                ':marital_status' => $data['marital_status'] ?? '',
                ':nationality' => $data['nationality'] ?? null,
                ':religion' => $data['religion'] ?? null,
                ':rank' => $data['rank'] ?? '',
                ':grade_level' => $data['grade_level'] ?? '',
                ':step' => $data['step'] ?? null,
                ':cadre' => $data['cadre'] ?? null,
                ':staff_type' => $data['staff_type'] ?? null,
                ':employment_type' => $data['employment_type'] ?? null,
                ':appointment_type' => $data['appointment_type'] ?? null,
                ':department' => $data['department'] ?? null,
                ':highest_qualification' => $data['highest_qualification'] ?? null,
                ':year_of_highest_qualification' => !empty($data['year_of_highest_qualification']) ? $data['year_of_highest_qualification'] : null,
                ':institution_attended' => $data['institution_attended'] ?? null,
                ':course_of_study' => $data['course_of_study'] ?? null,
                ':class_of_degree' => $data['class_of_degree'] ?? null,
                ':professional_certifications' => $data['professional_certifications'] ?? null,
                ':additional_qualifications' => $data['additional_qualifications'] ?? null,
                ':date_of_first_appointment' => $data['date_of_first_appointment'] ?? '',
                ':date_of_confirmation' => !empty($data['date_of_confirmation']) ? $data['date_of_confirmation'] : null,
                ':rank_on_first_appointment' => $data['rank_on_first_appointment'] ?? null,
                ':date_of_present_appointment' => !empty($data['date_of_present_appointment']) ? $data['date_of_present_appointment'] : null,
                ':state' => $data['state'] ?? '',
                ':local_govt_area' => $data['local_govt_area'] ?? '',
                ':geopolitical_zone' => $data['geopolitical_zone'] ?? null,
                ':state_of_residence' => $data['state_of_residence'] ?? null,
                ':residential_address' => $data['residential_address'] ?? null,
                ':contact_address' => $data['contact_address'] ?? null,
                ':pf_number' => $data['pf_number'] ?? null,
                ':nhf_number' => $data['nhf_number'] ?? null,
                ':nin' => $data['nin'] ?? null,
                ':telephone_number' => $data['telephone_number'] ?? null,
                ':email' => $data['email'] ?? null,
                ':blood_group' => $data['blood_group'] ?? null,
                ':genotype' => $data['genotype'] ?? null,
                ':disability' => $data['disability'] ?? 'No',
                ':disability_type' => $data['disability_type'] ?? null,
                ':bank_name' => $data['bank_name'] ?? null,
                ':other_bank_name' => $data['other_bank_name'] ?? null,
                ':bank_branch' => $data['bank_branch'] ?? null,
                ':account_number' => $data['account_number'] ?? null,
                ':account_name' => $data['account_name'] ?? null,
                ':pension_fund_admin' => $data['pension_fund_admin'] ?? null,
                ':other_pension_fund_admin' => $data['other_pension_fund_admin'] ?? null,
                ':pension_number' => $data['pension_number'] ?? null,
                ':tin_number' => $data['tin_number'] ?? null,
                ':salary_structure' => $data['salary_structure'] ?? null,
                ':emergency_contact_name' => $data['emergency_contact_name'] ?? null,
                ':emergency_contact_phone' => $data['emergency_contact_phone'] ?? null,
                ':emergency_contact_relationship' => $data['emergency_contact_relationship'] ?? null,
                ':next_of_kin_name' => $data['next_of_kin_name'] ?? null,
                ':next_of_kin_phone' => $data['next_of_kin_phone'] ?? null,
                ':next_of_kin_relationship' => $data['next_of_kin_relationship'] ?? null,
                ':next_of_kin_address' => $data['next_of_kin_address'] ?? null,
                ':passport_photo' => $data['passport_photo'] ?? null,
                ':is_draft' => $data['is_draft'] ?? 0,
                ':status' => $data['status'] ?? 'active',
                ':updated_by' => $userId
            ];
            
            error_log("Parameters count: " . count($params));
            error_log("Key parameters:");
            error_log("  - Employee Number: " . ($params[':employee_number'] ?? 'NULL'));
            error_log("  - Surname: " . ($params[':surname'] ?? 'NULL'));
            error_log("  - First Name: " . ($params[':first_name'] ?? 'NULL'));
            error_log("  - Rank: " . ($params[':rank'] ?? 'NULL'));
            error_log("  - Passport Photo: " . ($params[':passport_photo'] ?? 'NULL'));
            error_log("  - Additional Qualifications: " . ($params[':additional_qualifications'] ?? 'NULL'));
            
            error_log("=== Step 5: Executing update ===");
            $result = $stmt->execute($params);
            error_log("Execute result: " . ($result ? 'Success' : 'Failed'));
            
            if (!$result) {
                error_log("=== SQL ERROR DETAILS ===");
                error_log("PDO Error Info: " . print_r($stmt->errorInfo(), true));
                error_log("PDO Error Code: " . $stmt->errorCode());
                
                // Try to get more specific error
                $errorInfo = $stmt->errorInfo();
                if (isset($errorInfo[2])) {
                    error_log("SQL Error Message: " . $errorInfo[2]);
                }
                
                // Check for specific common errors
                if (strpos($errorInfo[2] ?? '', 'Column not found') !== false) {
                    error_log("ERROR TYPE: Column does not exist in database");
                } elseif (strpos($errorInfo[2] ?? '', 'Duplicate entry') !== false) {
                    error_log("ERROR TYPE: Duplicate entry (unique constraint violation)");
                } elseif (strpos($errorInfo[2] ?? '', 'Data too long') !== false) {
                    error_log("ERROR TYPE: Data too long for column");
                }
                
                throw new Exception("SQL Update failed: " . ($errorInfo[2] ?? 'Unknown error'));
            }
            
            error_log("=== Step 6: Getting updated data ===");
            // Get the new data for logging
            $newData = $this->getEmployee($id);
            error_log("New data retrieved: " . ($newData ? "Yes" : "No"));
            
            error_log("=== Step 7: Logging activity ===");
            // Log the activity
            if ($result) {
                $this->logActivity($id, $userId, 'employee_updated', 'Employee record updated', $oldData, $newData);
                error_log("Activity logged successfully");
            } else {
                error_log("Warning: Activity not logged (update failed)");
            }
            
            error_log("=== Step 8: Return result ===");
            error_log("Returning result: " . ($result ? 'TRUE' : 'FALSE'));
            error_log("=== MODEL updateEmployee() COMPLETE ===");
            
            return $result;
            
        } catch (PDOException $e) {
            error_log("=== MODEL updateEmployee PDOException ===");
            error_log("PDOException Message: " . $e->getMessage());
            error_log("PDOException Code: " . $e->getCode());
            error_log("PDOException File: " . $e->getFile() . ":" . $e->getLine());
            error_log("PDOException Trace: " . $e->getTraceAsString());
            
            if (isset($e->errorInfo)) {
                error_log("PDO Error Info: " . print_r($e->errorInfo, true));
                error_log("SQLSTATE: " . ($e->errorInfo[0] ?? 'N/A'));
                error_log("Driver Error Code: " . ($e->errorInfo[1] ?? 'N/A'));
                error_log("Driver Error Message: " . ($e->errorInfo[2] ?? 'N/A'));
            }
            
            // Re-throw with more context
            throw new Exception("Database error in updateEmployee: " . $e->getMessage());
            
        } catch (Exception $e) {
            error_log("=== MODEL updateEmployee General Exception ===");
            error_log("Exception Message: " . $e->getMessage());
            error_log("Exception Code: " . $e->getCode());
            error_log("Exception File: " . $e->getFile() . ":" . $e->getLine());
            error_log("Exception Trace: " . $e->getTraceAsString());
            
            // Re-throw
            throw $e;
        }
    }
    
    /**
     * Update employee status
     */
    public function updateEmployeeStatus($id, $status, $userId = null) {
        try {
            $oldData = $this->getEmployee($id);
            
            $sql = "UPDATE " . self::TABLE_EMPLOYEES . " SET
                    status = :status,
                    is_draft = :is_draft,
                    updated_by = :updated_by,
                    updated_at = NOW()
                    WHERE id = :id";
            
            $stmt = $this->db->prepare($sql);
            $result = $stmt->execute([
                ':id' => $id,
                ':status' => $status,
                ':is_draft' => $status === 'draft' ? 1 : 0,
                ':updated_by' => $userId
            ]);
            
            // Log the activity
            if ($result) {
                $newData = $this->getEmployee($id);
                $this->logActivity($id, $userId, 'status_updated', "Employee status changed to {$status}", $oldData, $newData);
            }
            
            return $result;
            
        } catch (PDOException $e) {
            error_log("NominalRollModel updateEmployeeStatus error: " . $e->getMessage());
            throw new Exception("Failed to update employee status: " . $e->getMessage());
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
                                      e.employee_number LIKE :search OR e.state LIKE :search OR
                                      e.email LIKE :search)";
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
                $whereConditions[] = "e.`rank` = :rank";
                $params[':rank'] = $filters['rank'];
            }
            
            if (!empty($filters['sex'])) {
                $whereConditions[] = "e.sex = :sex";
                $params[':sex'] = $filters['sex'];
            }
            
            if (!empty($filters['status'])) {
                $whereConditions[] = "e.status = :status";
                $params[':status'] = $filters['status'];
            }
            
            if (isset($filters['is_draft']) && $filters['is_draft'] !== '') {
                $whereConditions[] = "e.is_draft = :is_draft";
                $params[':is_draft'] = $filters['is_draft'];
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
            
            // Parse additional qualifications JSON for each employee
            foreach ($employees as &$employee) {
                if (!empty($employee['additional_qualifications'])) {
                    $employee['additional_qualifications'] = json_decode($employee['additional_qualifications'], true);
                }
            }
            
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
            // Use the view if it exists, otherwise calculate manually
            $sql = "SELECT * FROM nominal_roll_statistics LIMIT 1";
            $stmt = $this->db->query($sql);
            $stats = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if (!$stats) {
                // Calculate manually if view doesn't exist
                $stats = [];
                
                // Total employees
                $sql = "SELECT COUNT(*) as total FROM " . self::TABLE_EMPLOYEES;
                $stmt = $this->db->query($sql);
                $stats['total_employees'] = $stmt->fetch(PDO::FETCH_ASSOC)['total'];
                
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
                $sql = "SELECT `rank`, COUNT(*) as count FROM " . self::TABLE_EMPLOYEES . " GROUP BY `rank` ORDER BY count DESC LIMIT 10";
                $stmt = $this->db->query($sql);
                $stats['by_rank'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
                
                // Draft count
                $sql = "SELECT COUNT(*) as count FROM " . self::TABLE_EMPLOYEES . " WHERE is_draft = 1";
                $stmt = $this->db->query($sql);
                $stats['draft_count'] = $stmt->fetch(PDO::FETCH_ASSOC)['count'];
                
                // Photos count
                $sql = "SELECT COUNT(*) as count FROM " . self::TABLE_EMPLOYEES . " WHERE passport_photo IS NOT NULL";
                $stmt = $this->db->query($sql);
                $stats['photos_count'] = $stmt->fetch(PDO::FETCH_ASSOC)['count'];
            }
            
            return $stats;
            
        } catch (PDOException $e) {
            error_log("NominalRollModel getEmployeeStats error: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Get distinct values for filters - UPDATED
     */
    public function getFilterOptions() {
        try {
            $options = [];
            
            // States
            $sql = "SELECT DISTINCT state FROM " . self::TABLE_EMPLOYEES . " WHERE state IS NOT NULL AND state != '' ORDER BY state";
            $stmt = $this->db->query($sql);
            $options['states'] = $stmt->fetchAll(PDO::FETCH_COLUMN);
            
            // States of residence
            $sql = "SELECT DISTINCT state_of_residence FROM " . self::TABLE_EMPLOYEES . " WHERE state_of_residence IS NOT NULL AND state_of_residence != '' ORDER BY state_of_residence";
            $stmt = $this->db->query($sql);
            $options['states_of_residence'] = $stmt->fetchAll(PDO::FETCH_COLUMN);
            
            // Grade Levels
            $sql = "SELECT DISTINCT grade_level FROM " . self::TABLE_EMPLOYEES . " WHERE grade_level IS NOT NULL AND grade_level != '' ORDER BY grade_level DESC";
            $stmt = $this->db->query($sql);
            $options['grade_levels'] = $stmt->fetchAll(PDO::FETCH_COLUMN);
            
            // Steps
            $sql = "SELECT DISTINCT step FROM " . self::TABLE_EMPLOYEES . " WHERE step IS NOT NULL AND step != '' ORDER BY step";
            $stmt = $this->db->query($sql);
            $options['steps'] = $stmt->fetchAll(PDO::FETCH_COLUMN);
            
            // Ranks
            $sql = "SELECT DISTINCT `rank` FROM " . self::TABLE_EMPLOYEES . " WHERE `rank` IS NOT NULL AND `rank` != '' ORDER BY `rank`";
            $stmt = $this->db->query($sql);
            $options['ranks'] = $stmt->fetchAll(PDO::FETCH_COLUMN);
            
            // Cadres
            $sql = "SELECT DISTINCT cadre FROM " . self::TABLE_EMPLOYEES . " WHERE cadre IS NOT NULL AND cadre != '' ORDER BY cadre";
            $stmt = $this->db->query($sql);
            $options['cadres'] = $stmt->fetchAll(PDO::FETCH_COLUMN);
            
            // Staff Types
            $sql = "SELECT DISTINCT staff_type FROM " . self::TABLE_EMPLOYEES . " WHERE staff_type IS NOT NULL AND staff_type != '' ORDER BY staff_type";
            $stmt = $this->db->query($sql);
            $options['staff_types'] = $stmt->fetchAll(PDO::FETCH_COLUMN);
            
            // Highest Qualifications
            $sql = "SELECT DISTINCT highest_qualification FROM " . self::TABLE_EMPLOYEES . " WHERE highest_qualification IS NOT NULL AND highest_qualification != '' ORDER BY highest_qualification";
            $stmt = $this->db->query($sql);
            $options['highest_qualifications'] = $stmt->fetchAll(PDO::FETCH_COLUMN);
            
            // Sex options
            $options['sex_options'] = ['Male', 'Female'];
            
            // Marital status options
            $options['marital_status_options'] = ['Single', 'Married', 'Divorced', 'Widowed'];
            
            // Nationality options
            $options['nationality_options'] = ['Nigerian', 'Ghanaian', 'Other'];
            
            // Religion options
            $options['religion_options'] = ['Christianity', 'Islam', 'Traditional', 'Other'];
            
            // Status options
            $options['status_options'] = ['active', 'draft', 'inactive'];
            
            // Bank names
            $sql = "SELECT DISTINCT bank_name FROM " . self::TABLE_EMPLOYEES . " WHERE bank_name IS NOT NULL AND bank_name != '' ORDER BY bank_name";
            $stmt = $this->db->query($sql);
            $options['bank_names'] = $stmt->fetchAll(PDO::FETCH_COLUMN);
            
            // Pension fund admins
            $sql = "SELECT DISTINCT pension_fund_admin FROM " . self::TABLE_EMPLOYEES . " WHERE pension_fund_admin IS NOT NULL AND pension_fund_admin != '' ORDER BY pension_fund_admin";
            $stmt = $this->db->query($sql);
            $options['pension_fund_admins'] = $stmt->fetchAll(PDO::FETCH_COLUMN);
            
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
            $sql = "SELECT * FROM " . self::TABLE_SETTINGS . " ORDER BY setting_key";
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
    public function createBulkUpload($filename, $filePath, $totalRows, $userId = null, $importType = 'create', $updateExisting = 0, $skipDuplicates = 1) {
        try {
            $sql = "INSERT INTO " . self::TABLE_BULK_UPLOADS . " SET
                    filename = :filename,
                    file_path = :file_path,
                    import_type = :import_type,
                    update_existing = :update_existing,
                    skip_duplicates = :skip_duplicates,
                    total_rows = :total_rows,
                    uploaded_by = :uploaded_by,
                    status = :status,
                    created_at = NOW()";
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute([
                ':filename' => $filename,
                ':file_path' => $filePath,
                ':import_type' => $importType,
                ':update_existing' => $updateExisting,
                ':skip_duplicates' => $skipDuplicates,
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
     * Update bulk upload status - ROBUST VERSION
     */
    public function updateBulkUpload($id, $data) {
        error_log("=== MODEL updateBulkUpload START ===");
        error_log("Updating upload ID: $id");
        
        try {
            // Define ALL columns that should be in the table
            $allColumns = [
                'id', 'filename', 'import_type', 'update_existing', 'skip_duplicates',
                'file_path', 'total_rows', 'successful_imports', 'failed_imports',
                'skipped_imports', 'error_log', 'uploaded_by', 'status',
                'processing_results', 'created_at', 'completed_at'
            ];
            
            // Filter data - only include columns that might exist
            $fields = [];
            $params = [':id' => $id];
            
            foreach ($data as $key => $value) {
                if (in_array($key, $allColumns)) {
                    $fields[] = "`$key` = :$key";
                    $params[":$key"] = $value;
                    error_log("Including field: $key = " . (is_array($value) ? json_encode($value) : $value));
                } else {
                    error_log("Skipping unknown field: $key");
                }
            }
            
            if (empty($fields)) {
                error_log("ERROR: No valid fields to update");
                return false;
            }
            
            // Build SQL - using UPDATE IGNORE to skip errors on missing columns
            $sql = "UPDATE `nominal_roll_bulk_uploads` SET " . implode(', ', $fields) . " WHERE `id` = :id";
            error_log("SQL: " . $sql);
            
            $stmt = $this->db->prepare($sql);
            $result = $stmt->execute($params);
            
            if ($result) {
                error_log("✓ Successfully updated bulk upload record");
                error_log("Rows affected: " . $stmt->rowCount());
            } else {
                $errorInfo = $stmt->errorInfo();
                error_log("✗ SQL Error: " . print_r($errorInfo, true));
                
                // If there's a column error, try a simpler update
                if (isset($errorInfo[0]) && $errorInfo[0] === '42S22') {
                    error_log("Column error detected. Trying minimal update...");
                    return $this->updateBulkUploadMinimal($id, $data);
                }
                
                throw new Exception("Failed to update: " . ($errorInfo[2] ?? 'Unknown error'));
            }
            
            error_log("=== MODEL updateBulkUpload END ===");
            return true;
            
        } catch (Exception $e) {
            error_log("=== MODEL updateBulkUpload EXCEPTION ===");
            error_log("Error: " . $e->getMessage());
            
            // Last resort: update only status
            try {
                $simpleSql = "UPDATE `nominal_roll_bulk_uploads` SET `status` = 'completed', `completed_at` = NOW() WHERE `id` = :id";
                $simpleStmt = $this->db->prepare($simpleSql);
                $simpleStmt->execute([':id' => $id]);
                error_log("✓ Completed minimal update (status only)");
                return true;
            } catch (Exception $e2) {
                error_log("✗ Even minimal update failed: " . $e2->getMessage());
                return false;
            }
        }
    }

    /**
     * Minimal update fallback - updates only essential fields
     */
    private function updateBulkUploadMinimal($id, $data) {
        try {
            error_log("=== MINIMAL UPDATE FALLBACK ===");
            
            $fields = [];
            $params = [':id' => $id];
            
            // Always include these essential fields
            $essentialFields = ['status', 'completed_at', 'successful_imports', 'failed_imports'];
            
            foreach ($essentialFields as $field) {
                if (isset($data[$field])) {
                    $fields[] = "`$field` = :$field";
                    $params[":$field"] = $data[$field];
                }
            }
            
            // If no fields from data, set defaults
            if (empty($fields)) {
                $fields[] = "`status` = 'completed'";
                $fields[] = "`completed_at` = NOW()";
            }
            
            $sql = "UPDATE `nominal_roll_bulk_uploads` SET " . implode(', ', $fields) . " WHERE `id` = :id";
            error_log("Minimal SQL: " . $sql);
            
            $stmt = $this->db->prepare($sql);
            $result = $stmt->execute($params);
            
            error_log($result ? "✓ Minimal update successful" : "✗ Minimal update failed");
            return $result;
            
        } catch (Exception $e) {
            error_log("Minimal update error: " . $e->getMessage());
            return false;
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
     * ENHANCED PROCESS BULK UPLOAD DATA WITH FIXES
     * ============================================
     */
    
    /**
     * Process bulk upload data for CSV/Excel - UPDATED VERSION
     */
    public function processBulkUploadData($rows, $importType = 'create', $updateExisting = false, $skipDuplicates = true, $userId = null) {
        error_log("=== MODEL processBulkUploadData START ===");
        
        $results = [
            'success' => 0,
            'updated' => 0,
            'skipped' => 0,
            'failed' => 0,
            'errors' => []
        ];
        
        foreach ($rows as $index => $row) {
            $rowNumber = $index + 1;
            
            try {
                error_log("Processing row $rowNumber: " . print_r($row, true));
                
                // Clean and normalize
                $cleanedData = $this->cleanBulkUploadRow($row);
                error_log("Cleaned data: " . print_r($cleanedData, true));
                
                // Validate required fields
                $requiredErrors = $this->validateBulkUploadRow($cleanedData, $rowNumber);
                if (!empty($requiredErrors)) {
                    $results['errors'] = array_merge($results['errors'], $requiredErrors);
                    $results['failed']++;
                    continue;
                }
                
                // Check for existing employee
                $existingEmployee = $this->getEmployeeByNumber($cleanedData['employee_number']);
                
                if ($existingEmployee) {
                    // Handle existing employee...
                    error_log("Row $rowNumber: Employee exists - Employee Number: " . $cleanedData['employee_number']);
                    
                    // Handle duplicate based on settings
                    if ($skipDuplicates) {
                        $results['skipped']++;
                        $results['errors'][] = [
                            'row' => $rowNumber,
                            'message' => "Skipped duplicate employee: " . $cleanedData['employee_number'],
                            'employee_number' => $cleanedData['employee_number']
                        ];
                        error_log("Row $rowNumber skipped: Duplicate employee");
                        continue;
                    }
                    
                    // Update existing if allowed
                    if ($updateExisting && $importType === 'create') {
                        error_log("Row $rowNumber: Updating existing employee");
                        
                        // Prepare update data
                        $updateData = $this->prepareEmployeeData($cleanedData);
                        
                        // Update employee
                        $updateResult = $this->updateEmployee($existingEmployee['id'], $updateData, $userId);
                        if ($updateResult) {
                            $results['updated']++;
                            error_log("Row $rowNumber updated successfully");
                        } else {
                            $results['failed']++;
                            $results['errors'][] = [
                                'row' => $rowNumber,
                                'message' => "Failed to update existing employee",
                                'employee_number' => $cleanedData['employee_number']
                            ];
                            error_log("Row $rowNumber update failed");
                        }
                    } else {
                        $results['skipped']++;
                        $results['errors'][] = [
                            'row' => $rowNumber,
                            'message' => "Employee already exists and update not allowed: " . $cleanedData['employee_number'],
                            'employee_number' => $cleanedData['employee_number']
                        ];
                        error_log("Row $rowNumber skipped: Exists but update not allowed");
                    }
                } else {
                    // Create new employee
                    error_log("Row $rowNumber: Creating new employee");
                    
                    $employeeData = $this->prepareEmployeeData($cleanedData);
                    
                    // Set additional fields
                    $employeeData['is_draft'] = 0;
                    $employeeData['status'] = 'active';
                    $employeeData['created_at'] = date('Y-m-d H:i:s');
                    $employeeData['updated_at'] = date('Y-m-d H:i:s');
                    
                    // Log what we're inserting
                    error_log("Attempting to insert: " . print_r($employeeData, true));
                    
                    // Create employee (use the bulk upload version)
                    $employeeId = $this->createEmployee($employeeData, $userId);
                    
                    if ($employeeId) {
                        $results['success']++;
                        error_log("✓ Row $rowNumber created successfully with ID: " . $employeeId);
                    } else {
                        $results['failed']++;
                        $results['errors'][] = [
                            'row' => $rowNumber,
                            'message' => "Failed to create employee record",
                            'employee_number' => $cleanedData['employee_number']
                        ];
                        error_log("✗ Row $rowNumber creation failed");
                    }
                }
                
            } catch (Exception $e) {
                $results['failed']++;
                $results['errors'][] = [
                    'row' => $rowNumber,
                    'message' => "Error: " . $e->getMessage(),
                    'employee_number' => $row['employee_number'] ?? 'Unknown'
                ];
                error_log("Row $rowNumber exception: " . $e->getMessage());
            }
        }
        
        error_log("=== MODEL processBulkUploadData RESULTS ===");
        error_log("Success: " . $results['success']);
        error_log("Failed: " . $results['failed']);
        error_log("Errors: " . print_r($results['errors'], true));
        
        return $results;
    }
    
    /**
     * Clean and normalize bulk upload row data - UPDATED VERSION
     */
    private function cleanBulkUploadRow($row) {
        $cleaned = [];
        
        foreach ($row as $key => $value) {
            $cleanKey = strtolower(trim(str_replace([' ', '-', '.', '(', ')'], '_', $key)));
            $cleanValue = trim($value);
            
            // Handle ALL CSV column mappings
            switch ($cleanKey) {
                // Employee info
                case 'employee_number':
                case 'emp_number':
                    $cleaned['employee_number'] = $cleanValue;
                    break;
                    
                case 'surname':
                case 'last_name':
                    $cleaned['surname'] = $cleanValue;
                    break;
                    
                case 'first_name':
                case 'given_name':
                    $cleaned['first_name'] = $cleanValue;
                    break;
                    
                case 'middle_name':
                    $cleaned['middle_name'] = $cleanValue;
                    break;
                    
                case 'sex':
                case 'gender':
                    $cleaned['sex'] = $this->normalizeSex($cleanValue);
                    break;
                    
                case 'date_of_birth':
                case 'dob':
                    $cleaned['date_of_birth'] = $this->normalizeDate($cleanValue);
                    break;
                    
                case 'marital_status':
                    $cleaned['marital_status'] = $cleanValue;
                    break;
                    
                // Employment info  
                case 'rank':
                case 'position':
                    $cleaned['rank'] = $cleanValue;
                    break;
                    
                case 'grade_level_gl':
                case 'grade_level':
                case 'gl':
                    $cleaned['grade_level'] = $cleanValue;
                    break;
                    
                // Qualifications
                case 'highest_qualification':
                    $cleaned['highest_qualification'] = $cleanValue;
                    break;
                    
                case 'year_of_highest_qualification':
                    $cleaned['year_of_highest_qualification'] = $cleanValue;
                    break;
                    
                case 'additional_qualifications':
                    $cleaned['additional_qualifications'] = $this->normalizeQualifications($cleanValue);
                    break;
                    
                // Dates
                case 'date_of_1st_appt':
                case 'date_of_first_appointment':
                    $cleaned['date_of_first_appointment'] = $this->normalizeDate($cleanValue);
                    break;
                    
                case 'date_of_confirmation':
                    $cleaned['date_of_confirmation'] = $this->normalizeDate($cleanValue);
                    break;
                    
                case 'date_of_present_appt':
                case 'date_of_present_appointment':
                    $cleaned['date_of_present_appointment'] = $this->normalizeDate($cleanValue);
                    break;
                    
                // Location
                case 'state_of_origin':
                case 'state':
                    $cleaned['state'] = $cleanValue;
                    break;
                    
                case 'local_govt_area':
                case 'lga':
                    $cleaned['local_govt_area'] = $cleanValue;
                    break;
                    
                case 'state_of_residence':
                    $cleaned['state_of_residence'] = $cleanValue;
                    break;
                    
                // Contact info
                case 'telephone_no':
                case 'telephone_number':
                case 'phone':
                    $cleaned['telephone_number'] = $cleanValue;
                    break;
                    
                case 'email':
                    $cleaned['email'] = $cleanValue;
                    break;
                    
                // Financial
                case 'pf_no':
                case 'pf_number':
                    $cleaned['pf_number'] = $cleanValue;
                    break;
                    
                case 'nhf_no':
                case 'nhf_number':
                    $cleaned['nhf_number'] = $cleanValue;
                    break;
                    
                case 'account_no':
                case 'account_number':
                    $cleaned['account_number'] = $cleanValue;
                    break;
                    
                case 'pension_no':
                case 'pension_number':
                    $cleaned['pension_number'] = $cleanValue;
                    break;
                    
                // Add more mappings as needed
                default:
                    // Map other common fields
                    if ($cleanKey === 's_n' || $cleanKey === 'sn') {
                        // Skip S/N column
                        break;
                    }
                    
                    $cleaned[$cleanKey] = $cleanValue;
                    break;
            }
        }
        
        // Set default values for required fields not in CSV
        $defaults = [
            'nationality' => 'Nigerian',
            'status' => 'active',
            'is_draft' => 0,
            'disability' => 'No'
        ];
        
        foreach ($defaults as $field => $defaultValue) {
            if (!isset($cleaned[$field]) || empty($cleaned[$field])) {
                $cleaned[$field] = $defaultValue;
            }
        }
        
        return $cleaned;
    }
    
    /**
     * Normalize date field - UPDATED VERSION
     */
    private function normalizeDate($value) {
        if (empty($value)) {
            return null;
        }
        
        // Remove any time portion
        $value = preg_replace('/\s+.*$/', '', $value);
        
        // Try common date formats
        $formats = [
            'Y-m-d',      // 2024-01-15
            'd/m/Y',      // 15/01/2024
            'm/d/Y',      // 01/15/2024
            'd-m-Y',      // 15-01-2024
            'm-d-Y',      // 01-15-2024
            'Y/m/d',      // 2024/01/15
            'd.m.Y',      // 15.01.2024
            'Y.m.d',      // 2024.01.15
            'n/j/Y',      // 1/15/2024 (no leading zeros)
            'j/n/Y',      // 15/1/2024 (no leading zeros)
        ];
        
        foreach ($formats as $format) {
            $date = DateTime::createFromFormat($format, $value);
            if ($date !== false) {
                return $date->format('Y-m-d');
            }
        }
        
        // Try strtotime as last resort
        $timestamp = strtotime($value);
        if ($timestamp !== false) {
            return date('Y-m-d', $timestamp);
        }
        
        // Return original if can't parse
        return $value;
    }
    
    /**
     * Validate bulk upload data (more lenient) - NEW METHOD
     */
    private function validateBulkUploadRow($data, $rowNumber) {
        $errors = [];
        
        // Minimum required fields
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
                $errors[] = [
                    'row' => $rowNumber,
                    'message' => "$label is required",
                    'employee_number' => $data['employee_number'] ?? 'Unknown'
                ];
            }
        }
        
        return $errors;
    }
    
    /**
     * Prepare employee data for insertion
     */
    private function prepareEmployeeData($data) {
        $employeeData = [];
        
        // Map CSV fields to database fields
        $fieldMapping = [
            'employee_number' => 'employee_number',
            'surname' => 'surname',
            'first_name' => 'first_name',
            'middle_name' => 'middle_name',
            'sex' => 'sex',
            'date_of_birth' => 'date_of_birth',
            'marital_status' => 'marital_status',
            'rank' => 'rank',
            'grade_level' => 'grade_level',
            'highest_qualification' => 'highest_qualification',
            'year_of_highest_qualification' => 'year_of_highest_qualification',
            'additional_qualifications' => 'additional_qualifications',
            'date_of_first_appointment' => 'date_of_first_appointment',
            'date_of_confirmation' => 'date_of_confirmation',
            'rank_on_first_appointment' => 'rank_on_first_appointment',
            'date_of_present_appointment' => 'date_of_present_appointment',
            'state' => 'state',
            'local_govt_area' => 'local_govt_area',
            'state_of_residence' => 'state_of_residence',
            'residential_address' => 'residential_address',
            'pf_number' => 'pf_number',
            'nhf_number' => 'nhf_number',
            'bank_name' => 'bank_name',
            'bank_branch' => 'bank_branch',
            'other_bank_name' => 'other_bank_name',
            'account_number' => 'account_number',
            'pension_fund_admin' => 'pension_fund_admin',
            'other_pension_fund_admin' => 'other_pension_fund_admin',
            'pension_number' => 'pension_number',
            'telephone_number' => 'telephone_number',
            'email' => 'email'
        ];
        
        foreach ($fieldMapping as $csvField => $dbField) {
            if (isset($data[$csvField]) && $data[$csvField] !== '') {
                $employeeData[$dbField] = $data[$csvField];
            }
        }
        
        return $employeeData;
    }
    
    /**
     * Normalize sex value
     */
    private function normalizeSex($value) {
        $value = strtolower(trim($value));
        
        if (in_array($value, ['male', 'm', '1'])) {
            return 'Male';
        } elseif (in_array($value, ['female', 'f', '0'])) {
            return 'Female';
        } else {
            return $value;
        }
    }
    
    /**
     * Normalize qualifications field
     */
    private function normalizeQualifications($value) {
        if (empty($value)) {
            return null;
        }
        
        // Check if already JSON
        if ($this->isJson($value)) {
            return $value;
        }
        
        // Try to parse as array
        $qualifications = [];
        
        // Split by common delimiters
        $items = preg_split('/[;,\n]/', $value);
        
        foreach ($items as $item) {
            $item = trim($item);
            if (!empty($item)) {
                // Try to extract year in parentheses
                if (preg_match('/(.+?)\s*\((\d{4})\)/', $item, $matches)) {
                    $qualifications[] = [
                        'qualification' => trim($matches[1]),
                        'year' => trim($matches[2])
                    ];
                } else {
                    $qualifications[] = [
                        'qualification' => $item,
                        'year' => ''
                    ];
                }
            }
        }
        
        return !empty($qualifications) ? json_encode($qualifications) : null;
    }
    
    /**
     * Check if string is valid JSON
     */
    private function isJson($string) {
        json_decode($string);
        return json_last_error() === JSON_ERROR_NONE;
    }
    
    /**
     * ============================================
     * CSV PARSING METHOD FOR BULK UPLOAD VALIDATION
     * ============================================
     */
    
    /**
     * Simple CSV parsing for bulk upload validation
     */
    public function parseBulkUploadCSV($filePath) {
        try {
            error_log("Parsing CSV for bulk upload: " . $filePath);
            
            if (!file_exists($filePath)) {
                return ['error' => 'File not found'];
            }
            
            $data = [];
            $headers = [];
            
            if (($handle = fopen($filePath, 'r')) !== false) {
                // Read headers
                $headers = fgetcsv($handle, 1000, ',');
                
                if (!$headers) {
                    fclose($handle);
                    return ['error' => 'CSV file is empty or corrupted'];
                }
                
                // Clean headers
                $headers = array_map(function($header) {
                    // Remove BOM and trim
                    $header = preg_replace('/^\xEF\xBB\xBF/', '', $header);
                    $header = strtolower(trim($header));
                    $header = str_replace([' ', '-', '.'], '_', $header);
                    return $header;
                }, $headers);
                
                $rowCount = 0;
                while (($row = fgetcsv($handle, 1000, ',')) !== false) {
                    $rowCount++;
                    
                    // Skip empty rows
                    if (empty(array_filter($row))) {
                        continue;
                    }
                    
                    // Map row to headers
                    $rowData = [];
                    foreach ($headers as $index => $header) {
                        $rowData[$header] = isset($row[$index]) ? trim($row[$index]) : '';
                    }
                    
                    $data[] = $rowData;
                }
                
                fclose($handle);
            }
            
            return [
                'success' => true,
                'headers' => $headers,
                'data' => $data,
                'total_rows' => count($data)
            ];
            
        } catch (Exception $e) {
            error_log("CSV parsing error: " . $e->getMessage());
            return ['error' => 'Failed to parse CSV file: ' . $e->getMessage()];
        }
    }
    
    /**
     * ============================================
     * BACKUP OPERATIONS
     * ============================================
     */
    
    /**
     * Create backup
     */
    public function createBackup($type = 'manual', $userId = null) {
        try {
            // Create backup directory
            $backupDir = ROOT_PATH . '/storage/backups/nominal-roll/';
            if (!file_exists($backupDir)) {
                mkdir($backupDir, 0755, true);
            }
            
            // Generate filename
            $timestamp = date('Y-m-d_H-i-s');
            $filename = "nominal_roll_backup_{$timestamp}.sql";
            $filePath = $backupDir . $filename;
            
            // Get all employee data
            $employees = $this->exportEmployees([]);
            $totalRecords = count($employees);
            
            // Create SQL backup
            $sqlContent = "-- Nominal Roll Backup\n";
            $sqlContent .= "-- Generated: " . date('Y-m-d H:i:s') . "\n";
            $sqlContent .= "-- Total Records: " . $totalRecords . "\n\n";
            
            // Create table structure (simplified)
            $sqlContent .= "TRUNCATE TABLE " . self::TABLE_EMPLOYEES . ";\n\n";
            
            // Insert data
            foreach ($employees as $employee) {
                $columns = [];
                $values = [];
                
                foreach ($employee as $key => $value) {
                    if ($value !== null) {
                        $columns[] = "`$key`";
                        $values[] = "'" . addslashes($value) . "'";
                    }
                }
                
                $sqlContent .= "INSERT INTO " . self::TABLE_EMPLOYEES . " (" . implode(', ', $columns) . ") VALUES (" . implode(', ', $values) . ");\n";
            }
            
            // Save to file
            if (file_put_contents($filePath, $sqlContent) === false) {
                throw new Exception("Failed to write backup file");
            }
            
            $fileSize = filesize($filePath) / 1024 / 1024; // MB
            
            // Create backup record
            $sql = "INSERT INTO " . self::TABLE_BACKUPS . " SET
                    backup_type = :backup_type,
                    file_name = :file_name,
                    file_path = :file_path,
                    file_size = :file_size,
                    records_count = :records_count,
                    status = :status,
                    created_by = :created_by,
                    created_at = NOW()";
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute([
                ':backup_type' => $type,
                ':file_name' => $filename,
                ':file_path' => $filePath,
                ':file_size' => round($fileSize, 2),
                ':records_count' => $totalRecords,
                ':status' => 'success',
                ':created_by' => $userId
            ]);
            
            $backupId = $this->db->lastInsertId();
            
            // Clean up old backups if auto backup is enabled
            $this->cleanupOldBackups();
            
            return [
                'success' => true,
                'backup_id' => $backupId,
                'file_name' => $filename,
                'file_path' => $filePath,
                'file_size' => $fileSize,
                'records_count' => $totalRecords
            ];
            
        } catch (Exception $e) {
            error_log("NominalRollModel createBackup error: " . $e->getMessage());
            
            // Log failed backup
            $sql = "INSERT INTO " . self::TABLE_BACKUPS . " SET
                    backup_type = :backup_type,
                    file_name = :file_name,
                    file_path = :file_path,
                    status = :status,
                    created_by = :created_by,
                    created_at = NOW()";
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute([
                ':backup_type' => $type,
                ':file_name' => 'failed_backup_' . date('Y-m-d_H-i-s'),
                ':file_path' => '',
                ':status' => 'failed',
                ':created_by' => $userId
            ]);
            
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }
    
    /**
     * Get backup by ID
     */
    public function getBackup($id) {
        try {
            $sql = "SELECT b.*, u.username as created_by_name
                    FROM " . self::TABLE_BACKUPS . " b
                    LEFT JOIN users u ON b.created_by = u.id
                    WHERE b.id = :id";
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute([':id' => $id]);
            
            return $stmt->fetch(PDO::FETCH_ASSOC);
            
        } catch (PDOException $e) {
            error_log("NominalRollModel getBackup error: " . $e->getMessage());
            return null;
        }
    }
    
    /**
     * Get backup history
     */
    public function getBackups($limit = 20) {
        try {
            $sql = "SELECT b.*, u.username as created_by_name
                    FROM " . self::TABLE_BACKUPS . " b
                    LEFT JOIN users u ON b.created_by = u.id
                    ORDER BY b.created_at DESC
                    LIMIT :limit";
            
            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
            $stmt->execute();
            
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
            
        } catch (PDOException $e) {
            error_log("NominalRollModel getBackups error: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Restore from backup
     */
    public function restoreBackup($id, $userId = null) {
        try {
            $backup = $this->getBackup($id);
            
            if (!$backup || !file_exists($backup['file_path'])) {
                throw new Exception("Backup file not found");
            }
            
            // Read backup file
            $sqlContent = file_get_contents($backup['file_path']);
            if (!$sqlContent) {
                throw new Exception("Failed to read backup file");
            }
            
            // Execute SQL statements
            $statements = explode(';', $sqlContent);
            
            $this->db->beginTransaction();
            
            foreach ($statements as $statement) {
                $statement = trim($statement);
                if (!empty($statement)) {
                    $this->db->exec($statement);
                }
            }
            
            $this->db->commit();
            
            // Log the activity
            $this->logActivity(null, $userId, 'backup_restored', "Backup restored from {$backup['file_name']}", null, [
                'backup_id' => $id,
                'backup_file' => $backup['file_name']
            ]);
            
            return true;
            
        } catch (Exception $e) {
            $this->db->rollBack();
            error_log("NominalRollModel restoreBackup error: " . $e->getMessage());
            throw new Exception("Failed to restore backup: " . $e->getMessage());
        }
    }
    
    /**
     * Cleanup old backups
     */
    private function cleanupOldBackups() {
        try {
            $retentionDays = (int)$this->getSetting('backup_retention', '30');
            $cutoffDate = date('Y-m-d H:i:s', strtotime("-{$retentionDays} days"));
            
            // Get old backups
            $sql = "SELECT id, file_path FROM " . self::TABLE_BACKUPS . " 
                    WHERE created_at < :cutoff_date AND status = 'success'";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([':cutoff_date' => $cutoffDate]);
            $oldBackups = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            foreach ($oldBackups as $backup) {
                // Delete file
                if (file_exists($backup['file_path'])) {
                    unlink($backup['file_path']);
                }
                
                // Delete record
                $deleteStmt = $this->db->prepare("DELETE FROM " . self::TABLE_BACKUPS . " WHERE id = :id");
                $deleteStmt->execute([':id' => $backup['id']]);
            }
            
            return true;
            
        } catch (Exception $e) {
            error_log("NominalRollModel cleanupOldBackups error: " . $e->getMessage());
            return false;
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
     * Export employees
     */
    public function exportEmployees($filters = []) {
        try {
            // Build WHERE clause
            $whereConditions = [];
            $params = [];
            
            if (!empty($filters['search'])) {
                $whereConditions[] = "(surname LIKE :search OR first_name LIKE :search OR employee_number LIKE :search OR email LIKE :search)";
                $params[':search'] = '%' . $filters['search'] . '%';
            }
            
            if (!empty($filters['state'])) {
                $whereConditions[] = "state = :state";
                $params[':state'] = $filters['state'];
            }
            
            if (!empty($filters['grade_level'])) {
                $whereConditions[] = "grade_level = :grade_level";
                $params[':grade_level'] = $filters['grade_level'];
            }
            
            if (!empty($filters['rank'])) {
                $whereConditions[] = "`rank` = :rank";
                $params[':rank'] = $filters['rank'];
            }
            
            if (!empty($filters['sex'])) {
                $whereConditions[] = "sex = :sex";
                $params[':sex'] = $filters['sex'];
            }
            
            if (!empty($filters['status'])) {
                $whereConditions[] = "status = :status";
                $params[':status'] = $filters['status'];
            }
            
            if (isset($filters['is_draft']) && $filters['is_draft'] !== '') {
                $whereConditions[] = "is_draft = :is_draft";
                $params[':is_draft'] = $filters['is_draft'];
            }
            
            $whereClause = $whereConditions ? "WHERE " . implode(" AND ", $whereConditions) : "";
            
            $sql = "SELECT * FROM " . self::TABLE_EMPLOYEES . " $whereClause ORDER BY surname, first_name";
            $stmt = $this->db->prepare($sql);
            $stmt->execute($params);
            
            $employees = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            // Decode JSON fields
            foreach ($employees as &$employee) {
                if (!empty($employee['additional_qualifications'])) {
                    $employee['additional_qualifications'] = json_decode($employee['additional_qualifications'], true);
                }
            }
            
            return $employees;
            
        } catch (PDOException $e) {
            error_log("NominalRollModel exportEmployees error: " . $e->getMessage());
            throw new Exception("Failed to export employees: " . $e->getMessage());
        }
    }
    
    /**
     * Export employees to CSV (legacy method)
     */
    public function exportEmployeesToCSV($filters = []) {
        return $this->exportEmployees($filters);
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
     * VALIDATION & UTILITY METHODS - UPDATED
     * ============================================
     */
    
    /**
     * Validate employee data - UPDATED
     */
    public function validateEmployeeData($data, $isUpdate = false) {
        $errors = [];
        
        // Required fields - UPDATED with new fields
        $requiredFields = [
            'employee_number' => 'Employee Number',
            'surname' => 'Surname',
            'first_name' => 'First Name',
            'sex' => 'Sex',
            'date_of_birth' => 'Date of Birth',
            'marital_status' => 'Marital Status',
            'nationality' => 'Nationality',
            'rank' => 'Rank',
            'grade_level' => 'Grade Level',
            'highest_qualification' => 'Highest Qualification',
            'year_of_highest_qualification' => 'Year of Highest Qualification',
            'date_of_first_appointment' => 'Date of First Appointment',
            'state' => 'State of Origin',
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
        $dateFields = [
            'date_of_birth', 'date_of_first_appointment', 
            'date_of_confirmation', 'date_of_present_appointment',
            'qualification_date', 'retirement_date'
        ];
        
        foreach ($dateFields as $field) {
            if (!empty($data[$field])) {
                if (!$this->isValidDate($data[$field])) {
                    $errors[] = ucfirst(str_replace('_', ' ', $field)) . " must be a valid date (YYYY-MM-DD)";
                }
            }
        }
        
        // Validate year
        if (!empty($data['year_of_highest_qualification'])) {
            $year = (int)$data['year_of_highest_qualification'];
            $currentYear = (int)date('Y');
            
            if ($year < 1900 || $year > $currentYear) {
                $errors[] = "Year of Highest Qualification must be between 1900 and $currentYear";
            }
        }
        
        // Validate email
        if (!empty($data['email']) && !filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $errors[] = "Invalid email address format";
        }
        
        // Validate bank account number (if provided)
        if (!empty($data['account_number']) && !preg_match('/^[0-9]{10,20}$/', $data['account_number'])) {
            $errors[] = "Account Number must be 10-20 digits";
        }
        
        // Validate phone number (if provided)
        if (!empty($data['telephone_number']) && !preg_match('/^[0-9]{11}$/', $data['telephone_number'])) {
            $errors[] = "Telephone Number must be 11 digits (e.g., 08012345678)";
        }
        
        // Validate NIN (if provided)
        if (!empty($data['nin']) && !preg_match('/^[0-9]{11}$/', $data['nin'])) {
            $errors[] = "NIN must be 11 digits";
        }
        
        return $errors;
    }
    
    /**
     * Check if date is valid
     */
    private function isValidDate($date, $format = 'Y-m-d') {
        if (empty($date)) {
            return true;
        }
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
                    'S/N', 'Employee Number', 'Surname', 'First Name', 'Middle Name', 'Sex', 'Date of Birth',
                    'Marital Status', 'Rank', 'Grade Level (GL)', 'Qualification', 'Qualification Date',
                    'Highest Qualification', 'Year of Highest Qualification', 'Additional Qualifications',
                    'Date of 1st Appt.', 'Date of Confirmation', 'Rank on 1st Appt.',
                    'Date of Present. Appt.', 'State of Origin', 'Local Govt. Area', 'State of Residence',
                    'Residential Address', 'PF No', 'NHF No', 'Bank Name', 'Bank Branch', 'Other Bank Name',
                    'Account No', 'Pension Fund Admin', 'Other Pension Fund Admin', 'Pension No', 
                    'Telephone No', 'Email'
                ];
                
                // Clean headers (remove BOM if present)
                $headers = array_map(function($header) {
                    // Remove UTF-8 BOM if present
                    $header = preg_replace('/^\xEF\xBB\xBF/', '', $header);
                    return trim($header);
                }, $headers);
                
                // Check if headers match expected format
                if (count($headers) < 15) { // Minimum required fields
                    throw new Exception("CSV file must have proper column headers");
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
     * Parse Excel file
     */
    public function parseExcelFile($filePath) {
        try {
            // Check if PHPExcel/PhpSpreadsheet is available
            if (!class_exists('PhpOffice\PhpSpreadsheet\Spreadsheet')) {
                throw new Exception("PhpSpreadsheet library is not installed");
            }
            
            $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReaderForFile($filePath);
            $spreadsheet = $reader->load($filePath);
            $worksheet = $spreadsheet->getActiveSheet();
            
            $data = [];
            $errors = [];
            $rowCount = 0;
            $highestRow = $worksheet->getHighestRow();
            $highestColumn = $worksheet->getHighestColumn();
            
            // Get headers from first row
            $headers = [];
            for ($col = 1; $col <= \PhpOffice\PhpSpreadsheet\Cell\Coordinate::columnIndexFromString($highestColumn); $col++) {
                $cellValue = $worksheet->getCellByColumnAndRow($col, 1)->getValue();
                $headers[] = trim($cellValue);
            }
            
            // Process data rows
            for ($row = 2; $row <= $highestRow; $row++) {
                $rowCount++;
                $rowData = [];
                
                // Skip empty rows
                $isEmpty = true;
                for ($col = 1; $col <= count($headers); $col++) {
                    $cellValue = $worksheet->getCellByColumnAndRow($col, $row)->getValue();
                    if (!empty(trim($cellValue))) {
                        $isEmpty = false;
                    }
                }
                
                if ($isEmpty) {
                    continue;
                }
                
                // Get row values
                for ($col = 1; $col <= count($headers); $col++) {
                    $cellValue = $worksheet->getCellByColumnAndRow($col, $row)->getValue();
                    $header = $headers[$col - 1] ?? '';
                    
                    if (!empty($header)) {
                        $rowData[$header] = trim($cellValue);
                    }
                }
                
                // Parse and validate row
                $employeeData = $this->parseCSVRow(array_values($rowData), $headers, $rowCount);
                
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
            
            return [
                'data' => $data,
                'errors' => $errors,
                'total_rows' => $rowCount,
                'valid_rows' => count($data)
            ];
            
        } catch (Exception $e) {
            error_log("NominalRollModel parseExcelFile error: " . $e->getMessage());
            throw new Exception("Failed to parse Excel file: " . $e->getMessage());
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
            'Employee Number' => 'employee_number',
            'Surname' => 'surname',
            'First Name' => 'first_name',
            'Middle Name' => 'middle_name',
            'Sex' => 'sex',
            'Date of Birth' => 'date_of_birth',
            'Marital Status' => 'marital_status',
            'Rank' => 'rank',
            'Grade Level (GL)' => 'grade_level',
            'Qualification' => 'qualification',
            'Qualification Date' => 'qualification_date',
            'Highest Qualification' => 'highest_qualification',
            'Year of Highest Qualification' => 'year_of_highest_qualification',
            'Additional Qualifications' => 'additional_qualifications',
            'Date of 1st Appt.' => 'date_of_first_appointment',
            'Date of Confirmation' => 'date_of_confirmation',
            'Rank on 1st Appt.' => 'rank_on_first_appointment',
            'Date of Present. Appt.' => 'date_of_present_appointment',
            'State of Origin' => 'state',
            'Local Govt. Area' => 'local_govt_area',
            'State of Residence' => 'state_of_residence',
            'Residential Address' => 'residential_address',
            'PF No' => 'pf_number',
            'NHF No' => 'nhf_number',
            'Bank Name' => 'bank_name',
            'Bank Branch' => 'bank_branch',
            'Other Bank Name' => 'other_bank_name',
            'Account No' => 'account_number',
            'Pension Fund Admin' => 'pension_fund_admin',
            'Other Pension Fund Admin' => 'other_pension_fund_admin',
            'Pension No' => 'pension_number',
            'Telephone No' => 'telephone_number',
            'Email' => 'email'
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
                    case 'Qualification Date':
                    case 'Date of Present. Appt.':
                        $value = $this->normalizeDate($value);
                        break;
                        
                    case 'Additional Qualifications':
                        // Parse JSON or comma-separated list
                        if (!empty($value)) {
                            if (strpos($value, '[') === 0) {
                                // JSON format
                                $employeeData[$columnMapping[$header]] = $value;
                            } else {
                                // Comma-separated list
                                $quals = explode(',', $value);
                                $additionalQuals = [];
                                foreach ($quals as $qual) {
                                    $qual = trim($qual);
                                    if (!empty($qual)) {
                                        $additionalQuals[] = ['qualification' => $qual];
                                    }
                                }
                                if (!empty($additionalQuals)) {
                                    $employeeData[$columnMapping[$header]] = json_encode($additionalQuals);
                                }
                            }
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
        
        // Set default status
        $employeeData['is_draft'] = 0;
        $employeeData['status'] = 'active';
        
        // Validate the parsed data
        $validationErrors = $this->validateEmployeeData($employeeData);
        
        if (!empty($validationErrors)) {
            return ['error' => implode('; ', $validationErrors)];
        }
        
        return $employeeData;
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
     * ============================================
     * REPORTING METHODS FOR NOMINAL ROLL
     * ============================================
     */
    
    /**
     * Get all available fields for reporting with categories
     */
    public function getAvailableReportFields() {
        return [
            'basic_info' => [
                'label' => 'Basic Information',
                'fields' => [
                    'employee_number' => 'Employee Number',
                    'surname' => 'Surname',
                    'first_name' => 'First Name',
                    'middle_name' => 'Middle Name',
                    'sex' => 'Sex',
                    'date_of_birth' => 'Date of Birth',
                    'marital_status' => 'Marital Status',
                    'nationality' => 'Nationality',
                    'religion' => 'Religion'
                ]
            ],
            'employment' => [
                'label' => 'Employment Details',
                'fields' => [
                    'rank' => 'Rank',
                    'grade_level' => 'Grade Level',
                    'step' => 'Step',
                    'cadre' => 'Cadre',
                    'staff_type' => 'Staff Type',
                    'employment_type' => 'Employment Type',
                    'appointment_type' => 'Appointment Type',
                    'department' => 'Department',
                    'date_of_first_appointment' => 'Date of 1st Appointment',
                    'date_of_confirmation' => 'Date of Confirmation',
                    'rank_on_first_appointment' => 'Rank on 1st Appointment',
                    'date_of_present_appointment' => 'Date of Present Appointment'
                ]
            ],
            'qualifications' => [
                'label' => 'Qualifications',
                'fields' => [
                    'highest_qualification' => 'Highest Qualification',
                    'year_of_highest_qualification' => 'Year of Highest Qualification',
                    'institution_attended' => 'Institution Attended',
                    'course_of_study' => 'Course of Study',
                    'class_of_degree' => 'Class of Degree'
                ]
            ],
            'location' => [
                'label' => 'Location & Origin',
                'fields' => [
                    'state' => 'State of Origin',
                    'local_govt_area' => 'Local Govt. Area',
                    'geopolitical_zone' => 'Geopolitical Zone',
                    'state_of_residence' => 'State of Residence',
                    'residential_address' => 'Residential Address',
                    'contact_address' => 'Contact Address'
                ]
            ],
            'identification' => [
                'label' => 'Identification',
                'fields' => [
                    'pf_number' => 'PF Number',
                    'nhf_number' => 'NHF Number',
                    'nin' => 'NIN',
                    'telephone_number' => 'Telephone Number',
                    'email' => 'Email'
                ]
            ],
            'financial' => [
                'label' => 'Financial Information',
                'fields' => [
                    'bank_name' => 'Bank Name',
                    'bank_branch' => 'Bank Branch',
                    'account_number' => 'Account Number',
                    'account_name' => 'Account Name',
                    'pension_fund_admin' => 'Pension Fund Admin',
                    'pension_number' => 'Pension Number',
                    'tin_number' => 'TIN Number'
                ]
            ],
            'emergency' => [
                'label' => 'Emergency Contacts',
                'fields' => [
                    'emergency_contact_name' => 'Emergency Contact Name',
                    'emergency_contact_phone' => 'Emergency Contact Phone',
                    'emergency_contact_relationship' => 'Emergency Contact Relationship',
                    'next_of_kin_name' => 'Next of Kin Name',
                    'next_of_kin_phone' => 'Next of Kin Phone',
                    'next_of_kin_relationship' => 'Next of Kin Relationship',
                    'next_of_kin_address' => 'Next of Kin Address'
                ]
            ]
        ];
    }
    
    /**
     * Get default report fields
     */
    public function getDefaultReportFields() {
        return [
            'employee_number',
            'surname',
            'first_name',
            'sex',
            'rank',
            'grade_level',
            'state',
            'local_govt_area',
            'date_of_birth',
            'date_of_first_appointment',
            'telephone_number',
            'email'
        ];
    }
    
    /**
     * Save report configuration
     */
    public function saveReportConfig($data, $userId = null) {
        try {
            $sql = "INSERT INTO " . self::TABLE_REPORTS . " SET
                    report_name = :report_name,
                    report_type = :report_type,
                    selected_fields = :selected_fields,
                    filters = :filters,
                    sort_order = :sort_order,
                    page_orientation = :page_orientation,
                    page_size = :page_size,
                    include_photos = :include_photos,
                    include_summary = :include_summary,
                    is_public = :is_public,
                    created_by = :created_by,
                    created_at = NOW()";
            
            $stmt = $this->db->prepare($sql);
            
            $result = $stmt->execute([
                ':report_name' => $data['report_name'],
                ':report_type' => $data['report_type'] ?? 'custom',
                ':selected_fields' => json_encode($data['selected_fields']),
                ':filters' => isset($data['filters']) ? json_encode($data['filters']) : null,
                ':sort_order' => $data['sort_order'] ?? 'surname_asc',
                ':page_orientation' => $data['page_orientation'] ?? 'landscape',
                ':page_size' => $data['page_size'] ?? 'A4',
                ':include_photos' => $data['include_photos'] ?? 0,
                ':include_summary' => $data['include_summary'] ?? 1,
                ':is_public' => $data['is_public'] ?? 0,
                ':created_by' => $userId
            ]);
            
            return $result ? $this->db->lastInsertId() : false;
            
        } catch (PDOException $e) {
            error_log("Save report config error: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Get saved reports
     */
    public function getSavedReports($userId = null, $includePublic = true) {
        try {
            $conditions = [];
            $params = [];
            
            if ($userId) {
                if ($includePublic) {
                    $conditions[] = "(r.created_by = :user_id OR r.is_public = 1)";
                    $params[':user_id'] = $userId;
                } else {
                    $conditions[] = "r.created_by = :user_id";
                    $params[':user_id'] = $userId;
                }
            } elseif ($includePublic) {
                $conditions[] = "r.is_public = 1";
            }
            
            $whereClause = !empty($conditions) ? "WHERE " . implode(" AND ", $conditions) : "";
            
            $sql = "SELECT r.*, u.username as created_by_name
                    FROM " . self::TABLE_REPORTS . " r
                    LEFT JOIN users u ON r.created_by = u.id
                    $whereClause
                    ORDER BY r.created_at DESC";
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute($params);
            
            $reports = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            // Decode JSON fields
            foreach ($reports as &$report) {
                $report['selected_fields'] = json_decode($report['selected_fields'], true);
                if (!empty($report['filters'])) {
                    $report['filters'] = json_decode($report['filters'], true);
                }
            }
            
            return $reports;
            
        } catch (PDOException $e) {
            error_log("Get saved reports error: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Generate report data - OPTIMIZED VERSION with caching
     */
    public function generateReportData($selectedFields, $filters = [], $sortOrder = 'surname_asc', $useCache = true) {
        try {
            // Generate cache key
            $cacheKey = 'report_data_' . md5(serialize([$selectedFields, $filters, $sortOrder]));
            
            // Check cache if enabled
            if ($useCache && function_exists('apc_fetch')) {
                $cachedData = apc_fetch($cacheKey);
                if ($cachedData !== false) {
                    return $cachedData;
                }
            }
            
            // Start with required fields
            $selectFields = ['id'];
            
            // Optimize field selection - only select what's needed
            foreach ($selectedFields as $field) {
                // Handle reserved words and special cases
                if ($field === 'rank') {
                    $selectFields[] = "`rank`";
                } elseif (in_array($field, ['date_of_birth', 'date_of_first_appointment', 'date_of_confirmation', 'date_of_present_appointment'])) {
                    // Store dates as-is for client-side formatting
                    $selectFields[] = "`{$field}`";
                } else {
                    $selectFields[] = "`{$field}`";
                }
            }
            
            // Build WHERE clause with indexes in mind
            $whereConditions = [];
            $params = [];
            
            // Use indexed columns first for better performance
            if (!empty($filters['status'])) {
                $whereConditions[] = "status = :status";
                $params[':status'] = $filters['status'];
            } else {
                $whereConditions[] = "status = 'active'"; // Most common case
            }
            
            // Add other filters
            if (!empty($filters['state'])) {
                $whereConditions[] = "state = :state";
                $params[':state'] = $filters['state'];
            }
            
            if (!empty($filters['grade_level'])) {
                $whereConditions[] = "grade_level = :grade_level";
                $params[':grade_level'] = $filters['grade_level'];
            }
            
            if (!empty($filters['search'])) {
                // Use full-text search if available, otherwise use LIKE with optimizations
                $whereConditions[] = "(surname LIKE :search OR first_name LIKE :search OR employee_number = :exact_search)";
                $params[':search'] = '%' . $filters['search'] . '%';
                $params[':exact_search'] = $filters['search'];
            }
            
            // Add remaining filters
            $filterMap = [
                'rank' => 'rank',
                'sex' => 'sex',
                'department' => 'department'
            ];
            
            foreach ($filterMap as $key => $column) {
                if (!empty($filters[$key])) {
                    $whereConditions[] = "`{$column}` = :{$key}";
                    $params[":{$key}"] = $filters[$key];
                }
            }
            
            $whereClause = !empty($whereConditions) ? "WHERE " . implode(" AND ", $whereConditions) : "";
            
            // Sort order with indexes
            $orderByMap = [
                'surname_asc' => 'surname ASC, first_name ASC',
                'surname_desc' => 'surname DESC, first_name DESC',
                'employee_number_asc' => 'employee_number ASC',
                'employee_number_desc' => 'employee_number DESC',
                'grade_level_asc' => 'CAST(grade_level AS UNSIGNED) ASC, surname ASC',
                'grade_level_desc' => 'CAST(grade_level AS UNSIGNED) DESC, surname ASC',
                'state_asc' => 'state ASC, surname ASC',
                'date_of_first_appointment_asc' => 'date_of_first_appointment ASC',
                'date_of_first_appointment_desc' => 'date_of_first_appointment DESC'
            ];
            
            $orderBy = $orderByMap[$sortOrder] ?? 'surname ASC, first_name ASC';
            
            // Execute query with LIMIT for large datasets
            $sql = "SELECT " . implode(', ', $selectFields) . "
                    FROM " . self::TABLE_EMPLOYEES . " 
                    {$whereClause}
                    ORDER BY {$orderBy}
                    LIMIT 10000"; // Safety limit for export
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute($params);
            
            $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            // Process data efficiently
            foreach ($data as &$row) {
                // Ensure all selected fields exist
                foreach ($selectedFields as $field) {
                    if (!array_key_exists($field, $row)) {
                        $row[$field] = '';
                    }
                }
            }
            
            // Cache the result for 5 minutes if APC is available
            if ($useCache && function_exists('apc_store') && count($data) > 0) {
                apc_store($cacheKey, $data, 300); // 5 minutes cache
            }
            
            return $data;
            
        } catch (PDOException $e) {
            error_log("Generate report data error: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Get report by ID
     */
    public function getReport($id) {
        try {
            $sql = "SELECT r.*, u.username as created_by_name
                    FROM " . self::TABLE_REPORTS . " r
                    LEFT JOIN users u ON r.created_by = u.id
                    WHERE r.id = :id";
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute([':id' => $id]);
            
            $report = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($report) {
                $report['selected_fields'] = json_decode($report['selected_fields'], true);
                if (!empty($report['filters'])) {
                    $report['filters'] = json_decode($report['filters'], true);
                }
            }
            
            return $report;
            
        } catch (PDOException $e) {
            error_log("Get report error: " . $e->getMessage());
            return null;
        }
    }
    
    /**
     * Get report by ID with access control
     */
    public function getReportForUser($id, $userId) {
        try {
            $sql = "SELECT r.*, u.username as created_by_name
                    FROM " . self::TABLE_REPORTS . " r
                    LEFT JOIN users u ON r.created_by = u.id
                    WHERE r.id = :id AND (r.created_by = :user_id OR r.is_public = 1)";
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute([':id' => $id, ':user_id' => $userId]);
            
            $report = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($report) {
                $report['selected_fields'] = json_decode($report['selected_fields'], true);
                if (!empty($report['filters'])) {
                    $report['filters'] = json_decode($report['filters'], true);
                }
            }
            
            return $report;
            
        } catch (PDOException $e) {
            error_log("Get report for user error: " . $e->getMessage());
            return null;
        }
    }
    
    /**
     * Delete report
     */
    public function deleteReport($id, $userId = null) {
        try {
            if ($userId) {
                // Check ownership
                $report = $this->getReport($id);
                if (!$report || ($report['created_by'] != $userId && !$report['is_public'])) {
                    return false;
                }
            }
            
            $sql = "DELETE FROM " . self::TABLE_REPORTS . " WHERE id = :id";
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([':id' => $id]);
            
        } catch (PDOException $e) {
            error_log("Delete report error: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Get report statistics
     */
    public function getReportStatistics($data, $selectedFields) {
        $stats = [
            'total_records' => count($data),
            'summary' => []
        ];
        
        if (empty($data)) {
            return $stats;
        }
        
        // Count by sex
        if (in_array('sex', $selectedFields)) {
            $genderCount = [];
            foreach ($data as $row) {
                $gender = $row['sex'] ?? 'Unknown';
                $genderCount[$gender] = ($genderCount[$gender] ?? 0) + 1;
            }
            $stats['summary']['by_sex'] = $genderCount;
        }
        
        // Count by state
        if (in_array('state', $selectedFields)) {
            $stateCount = [];
            foreach ($data as $row) {
                $state = $row['state'] ?? 'Unknown';
                $stateCount[$state] = ($stateCount[$state] ?? 0) + 1;
            }
            arsort($stateCount);
            $stats['summary']['by_state'] = array_slice($stateCount, 0, 5);
        }
        
        // Count by grade level
        if (in_array('grade_level', $selectedFields)) {
            $gradeCount = [];
            foreach ($data as $row) {
                $grade = $row['grade_level'] ?? 'Unknown';
                $gradeCount[$grade] = ($gradeCount[$grade] ?? 0) + 1;
            }
            arsort($gradeCount);
            $stats['summary']['by_grade'] = $gradeCount;
        }
        
        return $stats;
    }
    
    /**
     * ============================================
     * NEW METHODS FOR CUSTOM EXPORT
     * ============================================
     */
    
    /**
     * Get employee data for export with selected fields
     */
    public function getExportData($selectedFields, $filters = []) {
        try {
            // Use the generateReportData method which already handles field selection
            return $this->generateReportData($selectedFields, $filters, 'surname_asc');
            
        } catch (Exception $e) {
            error_log("Get export data error: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Get employee counts for export summary
     */
    public function getExportSummary($filters = []) {
        try {
            $summary = [];
            
            // Total employees
            $sql = "SELECT COUNT(*) as total FROM " . self::TABLE_EMPLOYEES . " WHERE status = 'active'";
            $stmt = $this->db->query($sql);
            $summary['total_employees'] = $stmt->fetch(PDO::FETCH_ASSOC)['total'];
            
            // Count by sex
            $sql = "SELECT sex, COUNT(*) as count FROM " . self::TABLE_EMPLOYEES . " WHERE status = 'active' GROUP BY sex";
            $stmt = $this->db->query($sql);
            $summary['by_sex'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            // Count by state
            $sql = "SELECT state, COUNT(*) as count FROM " . self::TABLE_EMPLOYEES . " WHERE status = 'active' GROUP BY state ORDER BY count DESC LIMIT 5";
            $stmt = $this->db->query($sql);
            $summary['by_state'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            // Count by grade level
            $sql = "SELECT grade_level, COUNT(*) as count FROM " . self::TABLE_EMPLOYEES . " WHERE status = 'active' GROUP BY grade_level ORDER BY grade_level DESC";
            $stmt = $this->db->query($sql);
            $summary['by_grade'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            return $summary;
            
        } catch (PDOException $e) {
            error_log("Get export summary error: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Validate selected fields for export
     */
    public function validateExportFields($selectedFields) {
        $allFields = [];
        $availableFields = $this->getAvailableReportFields();
        
        // Flatten all available fields
        foreach ($availableFields as $category) {
            foreach ($category['fields'] as $key => $label) {
                $allFields[$key] = $label;
            }
        }
        
        // Check if all selected fields exist
        $invalidFields = [];
        foreach ($selectedFields as $field) {
            if (!isset($allFields[$field])) {
                $invalidFields[] = $field;
            }
        }
        
        if (!empty($invalidFields)) {
            throw new Exception("Invalid fields selected: " . implode(', ', $invalidFields));
        }
        
        return true;
    }
    
    /**
     * Get field labels for export headers
     */
    public function getFieldLabels($selectedFields) {
        $labels = [];
        $availableFields = $this->getAvailableReportFields();
        
        // Flatten all available fields
        foreach ($availableFields as $category) {
            foreach ($category['fields'] as $key => $label) {
                $labels[$key] = $label;
            }
        }
        
        // Return labels for selected fields
        $selectedLabels = [];
        foreach ($selectedFields as $field) {
            $selectedLabels[$field] = $labels[$field] ?? $field;
        }
        
        return $selectedLabels;
    }
    
    /**
     * Get bulk upload processing results summary
     */
    public function getBulkUploadResultsSummary($uploadId) {
        try {
            $sql = "SELECT * FROM " . self::TABLE_BULK_UPLOADS . " WHERE id = :id";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([':id' => $uploadId]);
            
            $upload = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($upload && !empty($upload['processing_results'])) {
                $upload['processing_results'] = json_decode($upload['processing_results'], true);
            }
            
            return $upload;
            
        } catch (PDOException $e) {
            error_log("Get bulk upload results summary error: " . $e->getMessage());
            return null;
        }
    }
}