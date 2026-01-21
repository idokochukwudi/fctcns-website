<?php
/**
 * Nominal Roll Controller
 * Handles Nominal Roll Management with Role-Based Access Control
 * 
 * @package FCT_CNS
 */

class NominalRollController extends Controller {
    
    /**
     * @var NominalRollModel
     */
    private $model;
    
    /**
     * @var array User role permissions
     */
    private $allowedRoles = ['admin', 'editor', 'viewer', 'nominal_roll_user']; // ADDED nominal_roll_user
    
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
        
        // Check if user has access to nominal roll
        $userRole = $_SESSION['user_role'] ?? '';
        if (!in_array($userRole, $this->allowedRoles)) {
            $this->showError("Access denied. You do not have permission to access Nominal Roll.");
            exit;
        }
        
        // Load model
        require_once __DIR__ . '/../models/NominalRollModel.php';
        $this->model = new NominalRollModel();
        
        // Check permissions using base controller method
        $userId = $_SESSION['user_id'] ?? 0;
        $hasCreatePermission = $this->checkPermission('nominal_roll_create', $userId);
        $hasEditPermission = $this->checkPermission('nominal_roll_edit', $userId);
        $hasViewPermission = $this->checkPermission('nominal_roll_view', $userId);
        $hasExportPermission = $this->checkPermission('nominal_roll_export', $userId);
        $hasBulkUploadPermission = $this->checkPermission('nominal_roll_bulk_upload', $userId);
        $hasDeletePermission = $this->checkPermission('nominal_roll_delete', $userId);
        $hasSettingsPermission = $this->checkPermission('nominal_roll_settings', $userId);
        $hasApprovePermission = $this->checkPermission('nominal_roll_approve', $userId);
        
        // Initialize common data
        $this->data = array_merge($this->data, [
            'user' => $_SESSION ?? [],
            'baseUrl' => defined('BASE_URL') ? BASE_URL : '',
            'currentPage' => 'nominal-roll',
            'isSuperAdmin' => ($_SESSION['user_role'] ?? '') === 'admin',
            
            // PERMISSION-BASED FLAGS (NOT role-based)
            'hasCreatePermission' => $hasCreatePermission,
            'hasEditPermission' => $hasEditPermission,
            'hasViewPermission' => $hasViewPermission,
            'hasExportPermission' => $hasExportPermission,
            'hasBulkUploadPermission' => $hasBulkUploadPermission,
            'hasDeletePermission' => $hasDeletePermission,
            'hasSettingsPermission' => $hasSettingsPermission,
            'hasApprovePermission' => $hasApprovePermission,
            
            // Legacy role-based flags (for compatibility)
            'isEditor' => in_array($_SESSION['user_role'] ?? '', ['admin', 'editor', 'nominal_roll_user']),
            'isViewer' => ($_SESSION['user_role'] ?? '') === 'viewer',
            'editingEnabled' => $this->model->isEditingEnabled()
        ]);
    }
    
    /**
     * ============================================
     * UTILITY METHODS - MIME TYPE DETECTION
     * ============================================
     */
    
    /**
     * Get MIME type using multiple methods for maximum compatibility
     * Works on all PHP environments including shared hosting
     */
    private function getMimeType($filePath) {
        // Method 1: Use finfo if available (PHP 5.3+, most reliable)
        if (function_exists('finfo_open')) {
            try {
                $finfo = finfo_open(FILEINFO_MIME_TYPE);
                if ($finfo) {
                    $mimeType = finfo_file($finfo, $filePath);
                    finfo_close($finfo);
                    if ($mimeType && $mimeType !== false && $mimeType !== 'application/octet-stream') {
                        return $mimeType;
                    }
                }
            } catch (Exception $e) {
                error_log("finfo method failed: " . $e->getMessage());
            }
        }
        
        // Method 2: Try mime_content_type() if available
        if (function_exists('mime_content_type')) {
            try {
                $mimeType = mime_content_type($filePath);
                if ($mimeType && $mimeType !== false && $mimeType !== 'application/octet-stream') {
                    return $mimeType;
                }
            } catch (Exception $e) {
                error_log("mime_content_type method failed: " . $e->getMessage());
            }
        }
        
        // Method 3: Use file extension as fallback
        $extension = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));
        $mimeTypes = [
            'csv' => 'text/csv',
            'txt' => 'text/plain',
            'xls' => 'application/vnd.ms-excel',
            'xlsx' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'jpg' => 'image/jpeg',
            'jpeg' => 'image/jpeg',
            'png' => 'image/png',
            'gif' => 'image/gif',
            'pdf' => 'application/pdf',
            'doc' => 'application/msword',
            'docx' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            'ppt' => 'application/vnd.ms-powerpoint',
            'pptx' => 'application/vnd.openxmlformats-officedocument.presentationml.presentation',
            'zip' => 'application/zip',
            'rar' => 'application/x-rar-compressed',
            'tar' => 'application/x-tar',
            'gz' => 'application/gzip',
            '7z' => 'application/x-7z-compressed'
        ];
        
        return isset($mimeTypes[$extension]) ? $mimeTypes[$extension] : 'application/octet-stream';
    }
    
    /**
     * Detect MIME type for images - enhanced version
     */
    private function detectMimeType($filePath) {
        // Use our new getMimeType method
        $mimeType = $this->getMimeType($filePath);
        
        // Special handling for images - verify with getimagesize if needed
        if (strpos($mimeType, 'image/') === 0 && function_exists('getimagesize')) {
            $imageInfo = @getimagesize($filePath);
            if ($imageInfo !== false && isset($imageInfo['mime'])) {
                return $imageInfo['mime'];
            }
        }
        
        return $mimeType;
    }
    
    /**
     * ============================================
     * DEBUG METHODS
     * ============================================
     */
    
    /**
     * Test database insertion - for debugging
     */
    public function testDatabaseInsert() {
        header('Content-Type: application/json');
        
        try {
            error_log("=== TEST DATABASE INSERT ===");
            
            // Test data
            $testData = [
                'employee_number' => 'TEST' . time(),
                'surname' => 'Test',
                'first_name' => 'User',
                'sex' => 'Male',
                'date_of_birth' => '1990-01-01',
                'marital_status' => 'Single',
                'rank' => 'Test Rank',
                'grade_level' => '10',
                'department' => 'Testing',
                'email' => 'test@example.com',
                'telephone_number' => '08000000000',
                'status' => 'active',
                'is_draft' => 0,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ];
            
            error_log("Test data: " . print_r($testData, true));
            
            // Try to insert
            $result = $this->model->createEmployee($testData, $_SESSION['user_id'] ?? null);
            
            if ($result) {
                echo json_encode([
                    'success' => true,
                    'message' => 'Test record inserted successfully',
                    'employee_id' => $result
                ]);
            } else {
                echo json_encode([
                    'success' => false,
                    'message' => 'Failed to insert test record'
                ]);
            }
            
        } catch (Exception $e) {
            error_log("Test error: " . $e->getMessage());
            echo json_encode([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ]);
        }
        
        exit;
    }
    
    /**
     * Test exact CSV format
     */
    public function testExactCSV() {
        error_log("=== TEST EXACT CSV START ===");
        
        // Clear any output buffers FIRST
        while (ob_get_level()) {
            ob_end_clean();
        }
        
        // Set JSON header FIRST, before any output
        header('Content-Type: application/json');
        
        try {
            // Simulate your exact CSV row
            $csvRow = [
                'S/N' => '1',
                'Employee Number' => 'EMP20240009',
                'Surname' => 'Doe',
                'First Name' => 'John',
                'Middle Name' => 'Michael',
                'Sex' => 'Male',
                'Date of Birth' => '2/2/1990',
                'Marital Status' => 'Married',
                'Rank' => 'Senior Lecturer',
                'Grade Level (GL)' => '15',
                'Qualification' => 'B.Sc Nursing',
                'Qualification Date' => '5/20/2010',
                'Highest Qualification' => 'PhD in Nursing',
                'Year of Highest Qualification' => '2020',
                'Additional Qualifications' => '[{"qualification":"M.Sc Nursing","year":"2015"},{"qualification":"PGDE","year":"2016"}]',
                'Date of 1st Appt.' => '3/1/2015',
                'Date of Confirmation' => '3/1/2016',
                'Rank on 1st Appt.' => 'Lecturer II',
                'Date of Present. Appt.' => '1/15/2023',
                'State of Origin' => 'FCT',
                'Local Govt. Area' => 'Gwagwalada',
                'State of Residence' => 'FCT',
                'Residential Address' => 'Plot 123, Gwagwalada, Abuja',
                'PF No' => 'PF123456',
                'NHF No' => 'NHF789012',
                'Bank Name' => 'First Bank',
                'Bank Branch' => 'Gwagwalada',
                'Other Bank Name' => '',
                'Account No' => '1234567890',
                'Pension Fund Admin' => 'PENCOM',
                'Other Pension Fund Admin' => '',
                'Pension No' => 'PEN123456',
                'Telephone No' => '8012345678',
                'Email' => 'john.doe@fcns.edu.ng'
            ];
            
            error_log("Testing CSV row cleaning...");
            
            // FIXED: Check if the method exists and call it directly in the model
            $cleanedData = [];
            
            // Try to call the method - it might be private, so let's handle it differently
            // First, let's manually clean the data like the model would
            error_log("Manually cleaning CSV row data...");
            
            // Basic cleaning - convert CSV headers to database fields
            $cleanedData = $this->cleanCSVRowManually($csvRow);
            error_log("Manually cleaned data: " . print_r($cleanedData, true));
            
            // Check what fields were extracted
            $expectedFields = ['employee_number', 'surname', 'first_name', 'grade_level', 'state', 'local_govt_area'];
            $missingFields = [];
            
            foreach ($expectedFields as $field) {
                if (empty($cleanedData[$field])) {
                    $missingFields[] = $field;
                }
            }
            
            if (!empty($missingFields)) {
                echo json_encode([
                    'success' => false,
                    'error' => 'Missing fields after cleaning: ' . implode(', ', $missingFields),
                    'cleaned_data' => $cleanedData
                ]);
                exit;
            }
            
            // Try to insert
            error_log("Attempting to insert cleaned data...");
            
            // Prepare data for insertion
            $employeeData = [
                'employee_number' => $cleanedData['employee_number'] ?? 'TEST' . time(),
                'surname' => $cleanedData['surname'] ?? 'Test',
                'first_name' => $cleanedData['first_name'] ?? 'User',
                'middle_name' => $cleanedData['middle_name'] ?? '',
                'sex' => $cleanedData['sex'] ?? 'Male',
                'date_of_birth' => $cleanedData['date_of_birth'] ?? '1990-01-01',
                'marital_status' => $cleanedData['marital_status'] ?? 'Single',
                'rank' => $cleanedData['rank'] ?? 'Test Rank',
                'grade_level' => $cleanedData['grade_level'] ?? '10',
                'qualification' => $cleanedData['qualification'] ?? 'B.Sc Test',
                'highest_qualification' => $cleanedData['highest_qualification'] ?? 'PhD Test',
                'year_of_highest_qualification' => $cleanedData['year_of_highest_qualification'] ?? '2020',
                'additional_qualifications' => $cleanedData['additional_qualifications'] ?? null,
                'date_of_first_appointment' => $cleanedData['date_of_first_appointment'] ?? date('Y-m-d'),
                'date_of_confirmation' => $cleanedData['date_of_confirmation'] ?? date('Y-m-d'),
                'rank_on_first_appointment' => $cleanedData['rank_on_first_appointment'] ?? 'Lecturer I',
                'date_of_present_appointment' => $cleanedData['date_of_present_appointment'] ?? date('Y-m-d'),
                'state' => $cleanedData['state'] ?? 'FCT',
                'local_govt_area' => $cleanedData['local_govt_area'] ?? 'Gwagwalada',
                'state_of_residence' => $cleanedData['state_of_residence'] ?? 'FCT',
                'residential_address' => $cleanedData['residential_address'] ?? 'Test Address',
                'pf_number' => $cleanedData['pf_number'] ?? 'PF123456',
                'nhf_number' => $cleanedData['nhf_number'] ?? 'NHF789012',
                'bank_name' => $cleanedData['bank_name'] ?? 'First Bank',
                'bank_branch' => $cleanedData['bank_branch'] ?? 'Test Branch',
                'account_number' => $cleanedData['account_number'] ?? '1234567890',
                'pension_fund_admin' => $cleanedData['pension_fund_admin'] ?? 'PENCOM',
                'pension_number' => $cleanedData['pension_number'] ?? 'PEN123456',
                'telephone_number' => $cleanedData['telephone_number'] ?? '08012345678',
                'email' => $cleanedData['email'] ?? 'test@example.com',
                'status' => 'active',
                'is_draft' => 0,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ];
            
            // Create employee using the model's public method
            $employeeId = $this->model->createEmployee($employeeData, $_SESSION['user_id'] ?? 1);
            
            if ($employeeId) {
                // Verify
                $employee = $this->model->getEmployee($employeeId);
                
                echo json_encode([
                    'success' => true,
                    'employee_id' => $employeeId,
                    'employee_number' => $employee['employee_number'] ?? null,
                    'cleaned_data' => $cleanedData,
                    'employee_data' => $employeeData,
                    'employee' => $employee
                ]);
            } else {
                echo json_encode([
                    'success' => false,
                    'error' => 'Failed to create employee',
                    'cleaned_data' => $cleanedData,
                    'employee_data' => $employeeData
                ]);
            }
            
        } catch (Exception $e) {
            error_log("Test exact CSV error: " . $e->getMessage());
            echo json_encode([
                'success' => false,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
        }
        
        error_log("=== TEST EXACT CSV END ===");
        exit;
    }
    
    /**
     * Manually clean CSV row (since cleanBulkUploadRow might be private)
     */
    private function cleanCSVRowManually($csvRow) {
        $cleanedData = [];
        
        // Map CSV headers to database fields
        $fieldMappings = [
            'S/N' => 'id',
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
        
        foreach ($csvRow as $csvHeader => $value) {
            if (isset($fieldMappings[$csvHeader])) {
                $dbField = $fieldMappings[$csvHeader];
                
                // Clean the value
                $cleanedValue = trim($value);
                
                // Handle special cases
                if (empty($cleanedValue)) {
                    $cleanedData[$dbField] = null;
                    continue;
                }
                
                // Handle date fields
                if (strpos($dbField, 'date') !== false || strpos($csvHeader, 'Date') !== false) {
                    // Try to convert date format (m/d/Y to Y-m-d)
                    try {
                        $date = DateTime::createFromFormat('n/j/Y', $cleanedValue);
                        if ($date) {
                            $cleanedData[$dbField] = $date->format('Y-m-d');
                        } else {
                            // Try other date formats
                            $date = strtotime($cleanedValue);
                            if ($date !== false) {
                                $cleanedData[$dbField] = date('Y-m-d', $date);
                            } else {
                                $cleanedData[$dbField] = $cleanedValue; // Keep original if can't parse
                            }
                        }
                    } catch (Exception $e) {
                        $cleanedData[$dbField] = $cleanedValue;
                    }
                } 
                // Handle sex field
                elseif ($dbField === 'sex') {
                    $cleanedValue = strtolower($cleanedValue);
                    if ($cleanedValue === 'm' || $cleanedValue === 'male') {
                        $cleanedData[$dbField] = 'Male';
                    } elseif ($cleanedValue === 'f' || $cleanedValue === 'female') {
                        $cleanedData[$dbField] = 'Female';
                    } else {
                        $cleanedData[$dbField] = ucfirst($cleanedValue);
                    }
                }
                // Handle JSON field
                elseif ($dbField === 'additional_qualifications') {
                    // Check if it's already JSON
                    if (strpos($cleanedValue, '[') === 0) {
                        // Try to decode to validate
                        $json = json_decode($cleanedValue, true);
                        if ($json !== null) {
                            $cleanedData[$dbField] = $cleanedValue;
                        } else {
                            $cleanedData[$dbField] = null;
                        }
                    } else {
                        $cleanedData[$dbField] = null;
                    }
                }
                // Handle other fields
                else {
                    $cleanedData[$dbField] = $cleanedValue;
                }
            }
        }
        
        return $cleanedData;
    }
    
    /**
     * ============================================
     * MAIN CRUD PAGES
     * ============================================
     */
    
    /**
     * Display all employees with pagination
     */
    public function index() {
        try {
            // Get current page
            $page = $this->input('page', 1);
            // Changed default from 20 to 5, but allow user to override via GET parameter
            $limit = $this->input('limit', $this->model->getSetting('records_per_page', 5));
            
            // Get filters - search will query: employee_number, name, ID, state, department
            $filters = [
                'search' => $this->input('search', ''), // Searches: employee_number, name, id, state, department
                'state' => $this->input('state', ''),
                'grade_level' => $this->input('grade_level', ''),
                'rank' => $this->input('rank', ''),
                'sex' => $this->input('sex', ''),
                'status' => $this->input('status', 'active'),
                'is_draft' => $this->input('is_draft', '')
            ];
            
            // Get employees with pagination
            $result = $this->model->getAllEmployees($page, $limit, $filters);
            
            // Get filter options
            $filterOptions = $this->model->getFilterOptions();
            
            // Get statistics
            $stats = $this->model->getEmployeeStats();
            
            // ========================================
            // FIX 5 APPLIED: Add error checking to pagination
            // ========================================
            // Make sure $pagination is always set with valid values
            if (!isset($result['pagination']) || !is_array($result['pagination'])) {
                $pagination = [];
            } else {
                $pagination = $result['pagination'];
            }

            // Ensure required keys exist with defaults
            $pagination['page'] = isset($pagination['page']) ? (int)$pagination['page'] : 1;
            $pagination['total_pages'] = isset($pagination['total_pages']) ? (int)$pagination['total_pages'] : 1;
            $pagination['total'] = isset($pagination['total']) ? (int)$pagination['total'] : 0;
            $pagination['limit'] = isset($pagination['limit']) ? (int)$pagination['limit'] : 5;

            // Make sure total_pages is at least 1
            if ($pagination['total_pages'] < 1) {
                $pagination['total_pages'] = 1;
            }
            
            // Now load your view with the validated pagination
            $this->data = array_merge($this->data, [
                'employees' => $result['employees'],
                'pagination' => $pagination, // Use validated pagination
                'filters' => $filters,
                'filterOptions' => $filterOptions,
                'stats' => $stats,
                'currentLimit' => $limit, // ADDED: for the records per page selector
                'pageTitle' => 'Nominal Roll Management - FCT College of Nursing Sciences',
                'pageDescription' => 'Manage employee records and details'
            ]);
            
            // Render view
            $this->render('admin/nominal-roll/index');
            
        } catch (Exception $e) {
            error_log("NominalRollController index error: " . $e->getMessage());
            $this->showNominalRollError("Failed to load nominal roll data.");
        }
    }
    
    /**
     * Display create employee form
     */
    public function create() {
        // Check if user has permission to create
        if (!$this->data['hasCreatePermission'] || (!$this->data['editingEnabled'] && !$this->data['isSuperAdmin'])) {
            echo json_encode(['success' => false, 'error' => 'You do not have permission to create employee records.']);
            exit;
        }
        
        try {
            // Get filter options for dropdowns
            $filterOptions = $this->model->getFilterOptions();
            
            // Generate employee number
            $employeeNumber = $this->model->generateEmployeeNumber();
            
            // Check for stored form data (from failed validation)
            $formData = [];
            $formErrors = [];
            
            if (isset($_SESSION['form_data'])) {
                $formData = $_SESSION['form_data'];
                unset($_SESSION['form_data']);
            }
            
            if (isset($_SESSION['form_errors'])) {
                $formErrors = $_SESSION['form_errors'];
                unset($_SESSION['form_errors']);
            }
            
            $this->data = array_merge($this->data, [
                'filterOptions' => $filterOptions,
                'employeeNumber' => $employeeNumber,
                'formData' => $formData,
                'formErrors' => $formErrors,
                'pageTitle' => 'Add New Employee - Nominal Roll',
                'pageDescription' => 'Add a new employee to the nominal roll'
            ]);
            
            $this->render('admin/nominal-roll/create');
            
        } catch (Exception $e) {
            error_log("NominalRollController create error: " . $e->getMessage());
            $this->showNominalRollError("Failed to load create form.");
        }
    }
    
    /**
     * Save new employee - FIXED VERSION
     */
    public function store() {
        error_log("=== NOMINAL ROLL STORE METHOD CALLED ===");
        error_log("POST data: " . print_r($_POST, true));
        error_log("FILES data: " . print_r($_FILES, true));
        
        // Check if user has permission
        if (!$this->data['hasCreatePermission'] || (!$this->data['editingEnabled'] && !$this->data['isSuperAdmin'])) {
            $this->flash('error', 'You do not have permission to create employee records.');
            $this->redirect('/admin/nominal-roll/create');
            return;
        }
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/admin/nominal-roll/create');
            return;
        }
        
        try {
            // Validate CSRF token
            if (!$this->validateCsrfToken()) {
                $this->flash('error', 'Invalid or expired CSRF token. Please try again.');
                $this->redirect('/admin/nominal-roll/create');
                return;
            }
            
            error_log("CSRF validation passed");
            
            // Get form data
            $data = $this->getFormData();
            error_log("Form data parsed: " . print_r($data, true));
            
            // Handle draft status
            $isDraft = $this->input('save_as_draft', 0);
            $data['is_draft'] = $isDraft ? 1 : 0;
            $data['status'] = $isDraft ? 'draft' : 'active';
            error_log("Draft status: " . ($isDraft ? 'Draft' : 'Active'));
            
            // Auto-approve if enabled
            if (!$isDraft && $this->model->getSetting('auto_approve_new', '1') === '1') {
                $data['status'] = 'active';
            }
            error_log("Final status: " . $data['status']);
            
            // ========================================
            // FIXED: Handle passport photo upload for NEW employee
            // ========================================
            if (!empty($_FILES['passport_photo']['name']) && $_FILES['passport_photo']['error'] === UPLOAD_ERR_OK) {
                error_log("Passport photo found for new employee, attempting upload");
                try {
                    $photoPath = $this->uploadPassportPhoto();
                    if ($photoPath) {
                        $data['passport_photo'] = $photoPath;
                        error_log("Photo uploaded successfully to: " . $photoPath);
                    } else {
                        error_log("Photo upload returned null");
                    }
                } catch (Exception $e) {
                    error_log("Photo upload exception: " . $e->getMessage());
                    // Don't fail the entire submission if photo is optional
                    $photoRequired = $this->model->getSetting('photo_required', '0') === '1';
                    if ($photoRequired) {
                        throw $e; // Re-throw if photo is required
                    } else {
                        error_log("Photo upload failed but continuing (photo is optional)");
                    }
                }
            } else {
                error_log("No passport photo uploaded for new employee");
                if (!empty($_FILES['passport_photo']['error'])) {
                    error_log("Upload error code: " . $_FILES['passport_photo']['error']);
                }
                // For new employees, set to null if no photo uploaded
                $data['passport_photo'] = null;
            }
            
            // Validate employee data
            $errors = $this->model->validateEmployeeData($data, false);
            error_log("Validation errors: " . print_r($errors, true));
            
            // If there are validation errors, store form data in session for repopulation
            if (!empty($errors)) {
                // Store the form data in session
                $_SESSION['form_data'] = $data;
                
                // Also store errors in session
                $_SESSION['form_errors'] = $errors;
                
                // Redirect back to create form
                $this->redirect('/admin/nominal-roll/create');
                return;
            }
            
            // Get user ID
            $userId = $_SESSION['user_id'] ?? null;
            error_log("User ID: " . ($userId ?? 'Not found'));
            
            // Create employee
            error_log("Attempting to create employee in database...");
            error_log("Data being sent to model: " . print_r($data, true));
            
            $employeeId = $this->model->createEmployee($data, $userId);
            
            if ($employeeId) {
                error_log("Employee created successfully! ID: " . $employeeId);
                
                // Verify the photo was saved
                $savedEmployee = $this->model->getEmployee($employeeId);
                error_log("Saved employee passport_photo: " . ($savedEmployee['passport_photo'] ?? 'NULL'));
                
                // Set flash message in session
                $_SESSION['flash_success'] = $isDraft ? 'Employee draft saved successfully!' : 'Employee record created successfully!';
                
                // Clear form data from session if exists
                if (isset($_SESSION['form_data'])) {
                    unset($_SESSION['form_data']);
                }
                
                error_log("Flash message set: " . $_SESSION['flash_success']);
                
                // Redirect based on draft status
                if ($isDraft) {
                    error_log("Redirecting to drafts page");
                    $this->redirect('/admin/nominal-roll/drafts');
                } else {
                    error_log("Redirecting to view page for ID: " . $employeeId);
                    $this->redirect('/admin/nominal-roll/view/' . $employeeId);
                }
                return;
            } else {
                error_log("Employee creation failed - no ID returned");
                throw new Exception("Failed to create employee record.");
            }
            
        } catch (Exception $e) {
            error_log("STORE METHOD EXCEPTION: " . $e->getMessage());
            error_log("Exception trace: " . $e->getTraceAsString());
            
            // Check if it's a CSRF error
            if (strpos($e->getMessage(), 'CSRF') !== false) {
                $this->flash('error', 'Security error: ' . $e->getMessage());
                $this->redirect('/admin/nominal-roll/create');
                return;
            }
            
            // Store form data in session for error display
            if (isset($data)) {
                $_SESSION['form_data'] = $data;
                $_SESSION['form_errors'] = [$e->getMessage()];
            }
            
            $this->redirect('/admin/nominal-roll/create');
        }
        
        error_log("=== NOMINAL ROLL STORE END ===");
    }
    
    /**
     * View employee details
     */
    public function view($id) {
        try {
            $employee = $this->model->getEmployee($id);
            
            if (!$employee) {
                $this->flash('error', 'Employee not found.');
                $this->redirect('/admin/nominal-roll');
                return;
            }
            
            // Get activity logs
            $activityLogs = $this->model->getActivityLogs($id, 20);
            
            $this->data = array_merge($this->data, [
                'employee' => $employee,
                'activityLogs' => $activityLogs,
                'pageTitle' => $employee['surname'] . ', ' . $employee['first_name'] . ' - Employee Details',
                'pageDescription' => 'View employee record details'
            ]);
            
            $this->render('admin/nominal-roll/view');
            
        } catch (Exception $e) {
            error_log("NominalRollController view error: " . $e->getMessage());
            $this->showNominalRollError("Failed to load employee details.");
        }
    }
    
    /**
     * View passport photo (for img src) - UNIVERSAL FIX
     */
    public function viewPassportPhoto($id) {
        try {
            $employee = $this->model->getEmployee($id);
            
            if (!$employee || empty($employee['passport_photo'])) {
                // Serve a default image
                $defaultImage = ROOT_PATH . '/assets/images/default-avatar.png';
                if (file_exists($defaultImage)) {
                    header('Content-Type: image/png');
                    readfile($defaultImage);
                } else {
                    // Create a simple default image
                    header('Content-Type: image/svg+xml');
                    echo '<?xml version="1.0" encoding="UTF-8"?>
                    <svg width="200" height="200" xmlns="http://www.w3.org/2000/svg">
                        <rect width="200" height="200" fill="#f0f0f0"/>
                        <text x="100" y="100" text-anchor="middle" font-family="Arial" font-size="14" fill="#666">No Photo</text>
                    </svg>';
                }
                exit;
            }
            
            $photoPath = ROOT_PATH . '/' . $employee['passport_photo'];
            
            if (!file_exists($photoPath)) {
                // Fallback to default if file is missing
                $defaultImage = ROOT_PATH . '/assets/images/default-avatar.png';
                if (file_exists($defaultImage)) {
                    header('Content-Type: image/png');
                    readfile($defaultImage);
                } else {
                    header('Content-Type: image/svg+xml');
                    echo '<?xml version="1.0" encoding="UTF-8"?>
                    <svg width="200" height="200" xmlns="http://www.w3.org/2000/svg">
                        <rect width="200" height="200" fill="#f0f0f0"/>
                        <text x="100" y="100" text-anchor="middle" font-family="Arial" font-size="14" fill="#666">Photo Missing</text>
                    </svg>';
                }
                exit;
            }
            
            // UNIVERSAL MIME TYPE DETECTION using our new method
            $mimeType = $this->getMimeType($photoPath);
            
            // Output image
            header('Content-Type: ' . $mimeType);
            header('Content-Length: ' . filesize($photoPath));
            header('Cache-Control: public, max-age=86400'); // Cache for 24 hours
            readfile($photoPath);
            exit;
            
        } catch (Exception $e) {
            error_log("Passport photo error for ID {$id}: " . $e->getMessage());
            
            // Return a simple error image
            header('Content-Type: image/svg+xml');
            echo '<?xml version="1.0" encoding="UTF-8"?>
            <svg width="200" height="200" xmlns="http://www.w3.org/2000/svg">
                <rect width="200" height="200" fill="#ffe6e6"/>
                <text x="100" y="100" text-anchor="middle" font-family="Arial" font-size="12" fill="#cc0000">Error Loading Image</text>
            </svg>';
            exit;
        }
    }
    
    /**
     * ============================================
     * INDUSTRIAL STANDARD PRINT FUNCTIONS
     * ============================================
     */
    
    /**
     * Print employee record (deprecated - use printView instead)
     */
    public function print($id) {
        try {
            $employee = $this->model->getEmployee($id);
            
            if (!$employee) {
                $this->flash('error', 'Employee not found.');
                $this->redirect('/admin/nominal-roll');
                return;
            }
            
            $this->data = array_merge($this->data, [
                'employee' => $employee,
                'pageTitle' => 'Print Employee Record'
            ]);
            
            $this->render('admin/nominal-roll/print');
            
        } catch (Exception $e) {
            error_log("NominalRollController print error: " . $e->getMessage());
            $this->showNominalRollError("Failed to load print view.");
        }
    }
    
    /**
     * Industrial Standard Print View
     */
    public function printView($id = null)
    {
        // Get employee ID
        $employeeId = $id ?? ($_GET['id'] ?? null);
        
        if (!$employeeId) {
            Session::set('error', 'Employee ID is required');
            $this->redirect('/admin/nominal-roll');
            return;
        }
        
        // Load employee data
        $employee = $this->model->getEmployee($employeeId);
        
        if (!$employee) {
            $this->flash('error', 'Employee not found');
            $this->redirect('/admin/nominal-roll');
            return;
        }
        
        // Prepare data for view
        $data = [
            'employee' => $employee,
            'baseUrl' => BASE_URL,
            'showAudit' => $_GET['audit'] ?? false,
            'autoPrint' => $_GET['autoprint'] ?? false,
            'documentId' => 'EMP-' . $employee['id'] . '-' . date('YmdHis'),
            'confidential' => true,
            'pageTitle' => 'Print Employee Record - ' . $employee['surname'] . ', ' . $employee['first_name'],
            'user' => $_SESSION ?? [],
            'isSuperAdmin' => ($_SESSION['user_role'] ?? '') === 'admin',
            'hasEditPermission' => $this->data['hasEditPermission'],
            'editingEnabled' => $this->model->isEditingEnabled()
        ];
        
        // Merge with controller data
        $this->data = array_merge($this->data, $data);
        
        // Load print view
        $this->render('admin/nominal-roll/print-industrial');
    }

    /**
     * Direct Print (auto-prints on load)
     */
    public function printDirect($id = null)
    {
        $this->printView($id);
        // Auto-print is handled in the view via $autoPrint flag
    }

    /**
     * Print with Audit Trail
     */
    public function printWithAudit($id = null)
    {
        $_GET['audit'] = true;
        $_GET['autoprint'] = true;
        $this->printView($id);
    }
    
    /**
     * ============================================
     * VERIFICATION METHODS (NEW)
     * ============================================
     */
    
    /**
     * Verify employee via QR code - Shows nice confirmation
     */
    public function verifyEmployee($id) {
        try {
            // Get employee data
            $employee = $this->model->getEmployee($id);
            
            if (!$employee) {
                // Show error page
                $this->renderVerificationError('Employee record not found or has been deleted.');
                return;
            }
            
            // Get document reference from query string
            $documentRef = $_GET['ref'] ?? '';
            $verifierName = $_GET['name'] ?? '';
            $verifierNotes = $_GET['notes'] ?? '';
            
            // Prepare verification data
            $verificationData = [
                'employee' => $employee,
                'documentRef' => $documentRef,
                'expectedRef' => 'EMP-' . $employee['id'] . '-' . date('Ymd', strtotime($employee['updated_at'] ?? 'now')),
                'isValid' => strpos($documentRef, 'EMP-' . $employee['id']) === 0,
                'verificationDate' => date('Y-m-d H:i:s'),
                'verificationId' => uniqid('VER-'),
                'ipAddress' => $_SERVER['REMOTE_ADDR'],
                'userAgent' => $_SERVER['HTTP_USER_AGENT'] ?? '',
                'verifierName' => $verifierName,
                'verifierNotes' => $verifierNotes,
                'baseUrl' => $this->data['baseUrl'] ?? BASE_URL
            ];
            
            // Log verification attempt
            $this->logVerification($verificationData);
            
            // Show verification confirmation page
            $this->renderVerificationConfirmation($verificationData);
            
        } catch (Exception $e) {
            error_log("Employee verification error: " . $e->getMessage());
            $this->renderVerificationError('An error occurred during verification.');
        }
    }

    /**
     * Verify document by reference
     */
    public function verifyDocument($ref) {
        try {
            // Extract employee ID from document reference
            $parts = explode('-', $ref);
            $employeeId = isset($parts[1]) ? $parts[1] : null;
            
            if (!$employeeId) {
                $this->renderVerificationError('Invalid document reference format.');
                return;
            }
            
            // Redirect to employee verification
            $this->redirect('/verify/employee/' . $employeeId . '?ref=' . urlencode($ref));
            
        } catch (Exception $e) {
            error_log("Document verification error: " . $e->getMessage());
            $this->renderVerificationError('An error occurred during verification.');
        }
    }

    /**
     * Render verification confirmation page
     */
    private function renderVerificationConfirmation($data) {
        // Set verification data
        $this->data = array_merge($this->data, [
            'verificationData' => $data,
            'employee' => $data['employee'],
            'pageTitle' => 'Document Verification - FCT College of Nursing Sciences',
            'layout' => 'verification' // Use a special layout for verification
        ]);
        
        // Load verification view
        $this->render('verification/confirmation');
    }

    /**
     * Render verification error page
     */
    private function renderVerificationError($message) {
        $this->data = array_merge($this->data, [
            'errorMessage' => $message,
            'pageTitle' => 'Verification Error - FCT College of Nursing Sciences',
            'layout' => 'verification'
        ]);
        
        $this->render('verification/error');
    }

    /**
     * Log verification attempt
     */
    private function logVerification($data) {
        try {
            // Create logs directory if it doesn't exist
            $logDir = ROOT_PATH . '/storage/logs/verifications/';
            if (!file_exists($logDir)) {
                mkdir($logDir, 0755, true);
            }
            
            // Log file path
            $logFile = $logDir . date('Y-m-d') . '.log';
            
            // Create log entry
            $logEntry = sprintf(
                "[%s] VERIFICATION - ID: %s, Employee: %s (%s), Valid: %s, IP: %s, User-Agent: %s\n",
                date('Y-m-d H:i:s'),
                $data['verificationId'],
                $data['employee']['employee_number'] ?? 'N/A',
                $data['employee']['surname'] . ', ' . $data['employee']['first_name'],
                $data['isValid'] ? 'YES' : 'NO',
                $data['ipAddress'],
                substr($data['userAgent'], 0, 100)
            );
            
            // Write to log file
            file_put_contents($logFile, $logEntry, FILE_APPEND);
            
        } catch (Exception $e) {
            error_log("Failed to log verification: " . $e->getMessage());
        }
    }
    
    /**
     * Display edit employee form
     */
    public function edit($id) {
        // Check if user has permission to edit
        if (!$this->data['hasEditPermission'] || (!$this->data['editingEnabled'] && !$this->data['isSuperAdmin'])) {
            $this->flash('error', 'Editing is currently disabled. Only Super Admin can modify records.');
            $this->redirect('/admin/nominal-roll/view/' . $id);
            return;
        }
        
        try {
            $employee = $this->model->getEmployee($id);
            
            if (!$employee) {
                $this->flash('error', 'Employee not found.');
                $this->redirect('/admin/nominal-roll');
                return;
            }
            
            // Get filter options
            $filterOptions = $this->model->getFilterOptions();
            
            $this->data = array_merge($this->data, [
                'employee' => $employee,
                'filterOptions' => $filterOptions,
                'pageTitle' => 'Edit Employee - ' . $employee['surname'] . ', ' . $employee['first_name'],
                'pageDescription' => 'Edit employee record details'
            ]);
            
            $this->render('admin/nominal-roll/edit');
            
        } catch (Exception $e) {
            error_log("NominalRollController edit error: " . $e->getMessage());
            $this->showNominalRollError("Failed to load edit form.");
        }
    }
    
    /**
     * Update employee record - UPDATED VERSION WITH CONSISTENT QUALIFICATIONS PROCESSING
     */
    public function update($id) {
        // Check if user has permission
        if (!$this->data['hasEditPermission'] || (!$this->data['editingEnabled'] && !$this->data['isSuperAdmin'])) {
            $this->flash('error', 'Editing is currently disabled. Only Super Admin can modify records.');
            $this->redirect('/admin/nominal-roll/view/' . $id);
            return;
        }
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/admin/nominal-roll/edit/' . $id);
            return;
        }
        
        try {
            error_log("=== UPDATE METHOD START FOR EMPLOYEE ID: $id ===");
            
            // Validate CSRF token
            if (!$this->validateCsrfToken()) {
                $this->flash('error', 'Invalid or expired CSRF token. Please try again.');
                $this->redirect('/admin/nominal-roll/edit/' . $id);
                return;
            }
            
            // Check if employee exists
            $employee = $this->model->getEmployee($id);
            if (!$employee) {
                throw new Exception("Employee not found.");
            }
            
            error_log("Existing employee found: " . $employee['employee_number']);
            error_log("Existing passport photo: " . ($employee['passport_photo'] ?? 'NULL'));
            
            // Debug info
            $this->debugPhotoInfo($employee, $_FILES);
            
            // Get form data (uses getFormData() which processes qualifications consistently)
            $data = $this->getFormData();
            
            // Log what data we have
            error_log("Form data received (before photo handling):");
            error_log("Additional qualifications JSON: " . ($data['additional_qualifications'] ?? 'NULL'));
            
            // Handle draft status
            $isDraft = $this->input('save_as_draft', 0);
            $data['is_draft'] = $isDraft ? 1 : 0;
            $data['status'] = $isDraft ? 'draft' : 'active';
            
            // FIX: Updated validation call with proper logic
            $validationData = $data;
            
            // If employee number is the same, skip unique check
            if (isset($employee['employee_number']) && $validationData['employee_number'] === $employee['employee_number']) {
                $errors = [];
                // Still validate other fields
                $tempErrors = $this->model->validateEmployeeData($validationData, true);
                // Remove employee number duplicate error if present
                foreach ($tempErrors as $error) {
                    if (strpos($error, 'Employee Number already exists') === false) {
                        $errors[] = $error;
                    }
                }
            } else {
                $errors = $this->model->validateEmployeeData($validationData, true);
            }
            
            if (!empty($errors)) {
                throw new Exception(implode('<br>', $errors));
            }
            
            // ========== CRITICAL PASSPORT PHOTO FIX START ==========
            $passportPhotoHandled = false;
            
            error_log("Checking for uploaded passport photo...");
            error_log("FILES array for passport_photo: " . print_r($_FILES['passport_photo'] ?? [], true));
            
            if (!empty($_FILES['passport_photo']['name']) && $_FILES['passport_photo']['error'] === UPLOAD_ERR_OK) {
                error_log("New passport photo detected for upload");
                try {
                    $photoPath = $this->uploadPassportPhoto();
                    if ($photoPath) {
                        error_log("New photo uploaded successfully to: " . $photoPath);
                        $data['passport_photo'] = $photoPath;
                        $passportPhotoHandled = true;
                        
                        // Delete old passport photo if exists
                        if (!empty($employee['passport_photo'])) {
                            error_log("Deleting old passport photo: " . $employee['passport_photo']);
                            $this->deleteFile($employee['passport_photo']);
                        }
                    } else {
                        error_log("Photo upload returned null/empty path");
                    }
                } catch (Exception $e) {
                    error_log("Photo upload exception: " . $e->getMessage());
                    // Don't fail the entire update if photo upload fails
                    $photoRequired = $this->model->getSetting('photo_required', '0') === '1';
                    if ($photoRequired) {
                        throw $e;
                    } else {
                        $passportPhotoHandled = false;
                    }
                }
            } else {
                error_log("No new passport photo uploaded");
                if (!empty($_FILES['passport_photo']['error'])) {
                    error_log("Upload error code: " . $_FILES['passport_photo']['error']);
                }
            }
            
            // If no new photo was uploaded, preserve the existing one
            if (!$passportPhotoHandled) {
                error_log("No new photo uploaded, checking for existing photo...");
                if (!empty($employee['passport_photo'])) {
                    error_log("Preserving existing passport photo: " . $employee['passport_photo']);
                    $data['passport_photo'] = $employee['passport_photo'];
                } else {
                    error_log("No existing passport photo found, setting to NULL");
                    $data['passport_photo'] = null;
                }
            }
            
            error_log("Final passport_photo value for update: " . ($data['passport_photo'] ?? 'NULL'));
            // ========== CRITICAL PASSPORT PHOTO FIX END ==========
            
            // Get user ID
            $userId = $_SESSION['user_id'] ?? null;
            
            // Update employee
            error_log("Calling model->updateEmployee with data...");
            $result = $this->model->updateEmployee($id, $data, $userId);
            
            if ($result) {
                $message = $isDraft ? 'Employee draft updated successfully!' : 'Employee record updated successfully!';
                $_SESSION['flash_success'] = $message;
                error_log("Update successful, redirecting to view page");
                $this->redirect('/admin/nominal-roll/view/' . $id);
            } else {
                throw new Exception("Failed to update employee record.");
            }
            
            error_log("=== UPDATE METHOD END ===");
            
        } catch (Exception $e) {
            error_log("NominalRollController update error: " . $e->getMessage());
            error_log("Exception trace: " . $e->getTraceAsString());
            
            // Check if it's a CSRF error
            if (strpos($e->getMessage(), 'CSRF') !== false) {
                $this->flash('error', 'Security error: ' . $e->getMessage());
                $this->redirect('/admin/nominal-roll/edit/' . $id);
                return;
            }
            
            // Get employee and filter options for re-display
            $employee = $this->model->getEmployee($id);
            $filterOptions = $this->model->getFilterOptions();
            
            $this->data = array_merge($this->data, [
                'employee' => $employee,
                'filterOptions' => $filterOptions,
                'error' => $e->getMessage(),
                'formData' => $this->getFormData(true),
                'pageTitle' => 'Edit Employee - ' . $employee['surname'] . ', ' . $employee['first_name'],
                'pageDescription' => 'Edit employee record details'
            ]);
            
            $this->render('admin/nominal-roll/edit');
        }
    }
    
    /**
     * Delete employee record
     */
    public function destroy($id) {
        // Check if user has permission (only users with delete permission can delete)
        if (!$this->data['hasDeletePermission']) {
            $this->flash('error', 'You do not have permission to delete employee records.');
            $this->redirect('/admin/nominal-roll/view/' . $id);
            return;
        }
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/admin/nominal-roll/view/' . $id);
            return;
        }
        
        try {
            // Validate CSRF token
            if (!$this->validateCsrfToken()) {
                $this->flash('error', 'Security error: Invalid or expired CSRF token.');
                $this->redirect('/admin/nominal-roll/view/' . $id);
                return;
            }
            
            // Check if employee exists
            $employee = $this->model->getEmployee($id);
            if (!$employee) {
                throw new Exception("Employee not found.");
            }
            
            // Get user ID
            $userId = $_SESSION['user_id'] ?? null;
            
            // Delete passport photo if exists
            if (!empty($employee['passport_photo'])) {
                $this->deleteFile($employee['passport_photo']);
            }
            
            // Delete employee
            $result = $this->model->deleteEmployee($id, $userId);
            
            if ($result) {
                $_SESSION['flash_success'] = 'Employee record deleted successfully!';
                $this->redirect('/admin/nominal-roll');
            } else {
                throw new Exception("Failed to delete employee record.");
            }
            
        } catch (Exception $e) {
            error_log("NominalRollController destroy error: " . $e->getMessage());
            
            // Check if it's a CSRF error
            if (strpos($e->getMessage(), 'CSRF') !== false) {
                $this->flash('error', 'Security error: " . $e->getMessage() . "');
            } else {
                $this->flash('error', 'Failed to delete employee: " . $e->getMessage() . "');
            }
            
            $this->redirect('/admin/nominal-roll/view/' . $id);
        }
    }
    
    /**
     * View draft employees
     */
    public function drafts() {
        try {
            // Get current page
            $page = $this->input('page', 1);
            $limit = $this->model->getSetting('records_per_page', 5); // Changed from 20 to 5
            
            // Get filters
            $filters = [
                'search' => $this->input('search', ''),
                'state' => $this->input('state', ''),
                'grade_level' => $this->input('grade_level', ''),
                'rank' => $this->input('rank', ''),
                'sex' => $this->input('sex', ''),
                'status' => 'draft',
                'is_draft' => 1
            ];
            
            // Get draft employees
            $result = $this->model->getAllEmployees($page, $limit, $filters);
            
            // Get filter options
            $filterOptions = $this->model->getFilterOptions();
            
            // ========================================
            // FIX 5 APPLIED: Add error checking to pagination for drafts too
            // ========================================
            if (!isset($result['pagination']) || !is_array($result['pagination'])) {
                $pagination = [];
            } else {
                $pagination = $result['pagination'];
            }

            // Ensure required keys exist with defaults
            $pagination['page'] = isset($pagination['page']) ? (int)$pagination['page'] : 1;
            $pagination['total_pages'] = isset($pagination['total_pages']) ? (int)$pagination['total_pages'] : 1;
            $pagination['total'] = isset($pagination['total']) ? (int)$pagination['total'] : 0;
            $pagination['limit'] = isset($pagination['limit']) ? (int)$pagination['limit'] : 5;

            // Make sure total_pages is at least 1
            if ($pagination['total_pages'] < 1) {
                $pagination['total_pages'] = 1;
            }
            
            $this->data = array_merge($this->data, [
                'employees' => $result['employees'],
                'pagination' => $pagination, // Use validated pagination
                'filters' => $filters,
                'filterOptions' => $filterOptions,
                'currentLimit' => $limit, // ADDED: for the records per page selector
                'pageTitle' => 'Draft Employees - Nominal Roll',
                'pageDescription' => 'Review and manage draft employee records'
            ]);
            
            $this->render('admin/nominal-roll/drafts');
            
        } catch (Exception $e) {
            error_log("NominalRollController drafts error: " . $e->getMessage());
            $this->showNominalRollError("Failed to load draft employees.");
        }
    }
    
    /**
     * Approve draft employee
     */
    public function approveDraft($id) {
        // Check if user has permission
        if (!$this->data['hasApprovePermission']) {
            $this->flash('error', 'You do not have permission to approve drafts.');
            $this->redirect('/admin/nominal-roll/drafts');
            return;
        }
        
        try {
            // Check if employee exists and is a draft
            $employee = $this->model->getEmployee($id);
            if (!$employee || $employee['status'] !== 'draft') {
                $this->flash('error', 'Employee not found or not in draft status.');
                $this->redirect('/admin/nominal-roll/drafts');
                return;
            }
            
            // Get user ID
            $userId = $_SESSION['user_id'] ?? null;
            
            // Update status to active
            $result = $this->model->updateEmployeeStatus($id, 'active', $userId);
            
            if ($result) {
                $_SESSION['flash_success'] = 'Draft approved successfully! Employee is now active.';
                $this->redirect('/admin/nominal-roll/view/' . $id);
            } else {
                throw new Exception("Failed to approve draft.");
            }
            
        } catch (Exception $e) {
            error_log("NominalRollController approveDraft error: " . $e->getMessage());
            $this->flash('error', 'Failed to approve draft: " . $e->getMessage() . "');
            $this->redirect('/admin/nominal-roll/drafts');
        }
    }
    
    /**
     * ============================================
     * BULK UPLOAD FUNCTIONALITY - FIXED VERSION
     * ============================================
     */
    
    /**
     * Display bulk upload form
     */
    public function bulkUpload() {
        // Check if user has permission
        if (!$this->data['hasBulkUploadPermission']) {
            echo json_encode(['success' => false, 'error' => 'You do not have permission to upload bulk data.']);
            exit;
        }
        
        try {
            // Get bulk upload history
            $uploadHistory = $this->model->getBulkUploads(10);
            
            // ==============================
            // FIX APPLIED: Generate CSRF token properly
            // ==============================
            $csrfToken = Session::generateCSRFTokenMulti();
            
            $this->data = array_merge($this->data, [
                'uploadHistory' => $uploadHistory,
                'csrfToken' => $csrfToken, // Pass to view
                'pageTitle' => 'Bulk Upload Employees - Nominal Roll',
                'pageDescription' => 'Upload multiple employee records via CSV/Excel'
            ]);
            
            $this->render('admin/nominal-roll/bulk-upload');
            
        } catch (Exception $e) {
            error_log("NominalRollController bulkUpload error: " . $e->getMessage());
            $this->showNominalRollError("Failed to load bulk upload form.");
        }
    }
    
    /**
     * Validate bulk upload file via AJAX - FIXED VERSION
     */
    public function validateBulkUpload() {
        error_log("=== VALIDATE BULK UPLOAD START ===");
        
        // Set headers for JSON response
        header('Content-Type: application/json');
        
        try {
            // STEP 1: Get CSRF token from POST
            $csrfToken = $_POST['csrf_token'] ?? null;
            
            error_log("CSRF token received: " . ($csrfToken ? substr($csrfToken, 0, 10) . "..." : "NULL"));
            
            if (!$csrfToken) {
                error_log("ERROR: No CSRF token in POST data");
                echo json_encode([
                    'success' => false, 
                    'error' => 'No CSRF token provided. Please refresh the page and try again.'
                ]);
                exit;
            }
            
            // STEP 2: Validate the CSRF token
            if (!Session::validateCSRFTokenMulti($csrfToken)) {
                error_log("ERROR: CSRF token validation failed");
                echo json_encode([
                    'success' => false, 
                    'error' => 'Invalid or expired CSRF token. Please refresh the page and try again.'
                ]);
                exit;
            }
            
            error_log("CSRF token validation passed");
            
            // STEP 3: Check if file was uploaded
            if (empty($_FILES['file']) || $_FILES['file']['error'] !== UPLOAD_ERR_OK) {
                echo json_encode(['success' => false, 'error' => 'No file uploaded or upload error']);
                exit;
            }
            $file = $_FILES['file'];
            
            // STEP 4: Check for upload errors
            if ($file['error'] !== UPLOAD_ERR_OK) {
                error_log("ERROR: Upload error code: " . $file['error']);
                $errorMessages = [
                    UPLOAD_ERR_INI_SIZE => 'File exceeds upload_max_filesize',
                    UPLOAD_ERR_FORM_SIZE => 'File exceeds MAX_FILE_SIZE',
                    UPLOAD_ERR_PARTIAL => 'File only partially uploaded',
                    UPLOAD_ERR_NO_FILE => 'No file was uploaded',
                    UPLOAD_ERR_NO_TMP_DIR => 'Missing temporary folder',
                    UPLOAD_ERR_CANT_WRITE => 'Failed to write file to disk',
                    UPLOAD_ERR_EXTENSION => 'A PHP extension stopped the file upload'
                ];
                $errorMsg = $errorMessages[$file['error']] ?? 'Unknown upload error';
                echo json_encode(['success' => false, 'error' => $errorMsg]);
                exit;
            }
            
            error_log("File upload successful. Name: " . $file['name'] . ", Size: " . $file['size']);
            
            // STEP 5: Validate file type
            $fileExt = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
            error_log("File extension: " . $fileExt);
            
            if ($fileExt !== 'csv') {
                echo json_encode(['success' => false, 'error' => 'Please upload CSV files only.']);
                exit;
            }
            
            // STEP 6: Validate file size (max 10MB)
            $maxSize = 10 * 1024 * 1024;
            if ($file['size'] > $maxSize) {
                echo json_encode(['success' => false, 'error' => 'File size must be less than 10MB.']);
                exit;
            }
            
            error_log("File validation passed. Starting to parse CSV...");
            
            // STEP 7: Parse CSV file
            $csvData = $this->parseCSVFile($file['tmp_name']);
            
            if (isset($csvData['error'])) {
                echo json_encode(['success' => false, 'error' => $csvData['error']]);
                exit;
            }
            
            // STEP 8: Validate CSV content
            $validationResult = $this->validateCSVData($csvData);
            
            // STEP 9: Prepare success response
            $response = [
                'success' => true,
                'message' => 'Validation completed successfully',
                'total_records' => $validationResult['total_records'],
                'valid_records' => $validationResult['valid_records'],
                'error_count' => count($validationResult['errors']),
                'errors' => $validationResult['errors'],
                'duplicate_count' => count($validationResult['duplicates']),
                'duplicates' => $validationResult['duplicates'],
                'file_name' => $file['name'],
                'file_size' => $file['size']
            ];
            
            error_log("=== VALIDATE BULK UPLOAD END ===");
            error_log("Response: " . json_encode($response));
            
            echo json_encode($response);
            exit;
            
        } catch (Exception $e) {
            error_log("EXCEPTION in validateBulkUpload: " . $e->getMessage());
            echo json_encode(['success' => false, 'error' => 'Server error: " . $e->getMessage()']);
            exit;
        }
    }

    /**
     * Parse CSV file
     */
    private function parseCSVFile($filePath) {
        error_log("Parsing CSV file: " . $filePath);
        
        if (!file_exists($filePath)) {
            return ['error' => 'File not found'];
        }
        
        $data = [];
        
        if (($handle = fopen($filePath, 'r')) !== false) {
            // Read and validate headers
            $headers = fgetcsv($handle, 1000, ',');
            
            if (!$headers || empty($headers[0])) {
                fclose($handle);
                return ['error' => 'CSV file has no headers or is empty'];
            }
            
            // Clean headers
            $headers = array_map(function($header) {
                // Remove UTF-8 BOM if present
                $header = preg_replace('/^\xEF\xBB\xBF/', '', $header);
                // Convert to lowercase and replace spaces with underscores
                $header = strtolower(trim($header));
                $header = str_replace([' ', '-', '.'], '_', $header);
                return $header;
            }, $headers);
            
            error_log("Headers found: " . implode(', ', $headers));
            
            $rowCount = 0;
            while (($row = fgetcsv($handle, 1000, ',')) !== false) {
                $rowCount++;
                
                // Skip empty rows
                if (empty(array_filter($row))) {
                    continue;
                }
                
                // Map row data to headers
                $rowData = [];
                foreach ($headers as $index => $header) {
                    if (isset($row[$index])) {
                        $rowData[$header] = trim($row[$index]);
                    } else {
                        $rowData[$header] = '';
                    }
                }
                
                $data[] = $rowData;
            }
            
            fclose($handle);
            
            error_log("Parsed " . count($data) . " rows from CSV");
            
            return [
                'headers' => $headers,
                'data' => $data,
                'total_rows' => $rowCount
            ];
        }
        
        return ['error' => 'Failed to open CSV file'];
    }

    /**
     * Validate CSV data
     */
    private function validateCSVData($csvData) {
        $errors = [];
        $duplicates = [];
        $employeeNumbers = [];
        $validCount = 0;
        
        foreach ($csvData['data'] as $index => $row) {
            $rowNumber = $index + 2; // +2 for header row
            
            $rowErrors = [];
            
            // Check required fields
            $requiredFields = [
                'employee_number' => 'Employee Number',
                'surname' => 'Surname', 
                'first_name' => 'First Name',
                'sex' => 'Sex',
                'date_of_birth' => 'Date of Birth'
            ];
            
            foreach ($requiredFields as $field => $label) {
                if (empty($row[$field])) {
                    $rowErrors[] = [
                        'row' => $rowNumber,
                        'field' => $field,
                        'message' => "$label is required",
                        'value' => $row[$field] ?? ''
                    ];
                }
            }
            
            // Check duplicate employee numbers in file
            if (!empty($row['employee_number'])) {
                if (in_array($row['employee_number'], $employeeNumbers)) {
                    $duplicates[] = [
                        'employee_number' => $row['employee_number'],
                        'name' => ($row['surname'] ?? '') . ', ' . ($row['first_name'] ?? ''),
                        'exists' => false,
                        'row' => $rowNumber
                    ];
                } else {
                    $employeeNumbers[] = $row['employee_number'];
                }
            }
            
            // Validate email format
            if (!empty($row['email']) && !filter_var($row['email'], FILTER_VALIDATE_EMAIL)) {
                $rowErrors[] = [
                    'row' => $rowNumber,
                    'field' => 'email',
                    'message' => 'Invalid email format',
                    'value' => $row['email']
                ];
            }
            
            // Validate date format
            if (!empty($row['date_of_birth']) && !$this->isValidDate($row['date_of_birth'])) {
                $rowErrors[] = [
                    'row' => $rowNumber,
                    'field' => 'date_of_birth',
                    'message' => 'Date must be in YYYY-MM-DD format',
                    'value' => $row['date_of_birth']
                ];
            }
            
            // Validate sex
            if (!empty($row['sex'])) {
                $sex = strtolower($row['sex']);
                if (!in_array($sex, ['male', 'female', 'm', 'f'])) {
                    $rowErrors[] = [
                        'row' => $rowNumber,
                        'field' => 'sex',
                        'message' => 'Sex must be Male or Female',
                        'value' => $row['sex']
                    ];
                }
            }
            
            if (empty($rowErrors)) {
                $validCount++;
            } else {
                $errors = array_merge($errors, $rowErrors);
            }
        }
        
        return [
            'total_records' => count($csvData['data']),
            'valid_records' => $validCount,
            'errors' => $errors,
            'duplicates' => $duplicates
        ];
    }

    /**
     * Check if date is valid
     */
    private function isValidDate($date, $format = 'Y-m-d') {
        $d = DateTime::createFromFormat($format, $date);
        return $d && $d->format($format) === $date;
    }
    
    /**
     * Process bulk upload - COMPLETELY FIXED VERSION WITH MIME TYPE FIX
     */
    public function processBulkUpload() {
        // ========================================
        // CRITICAL FIX 1: Start output buffering and set JSON header IMMEDIATELY
        // ========================================
        ob_start(); // Start output buffering to catch any stray output
        
        header('Content-Type: application/json; charset=utf-8');
        
        // Clear any existing output buffers
        while (ob_get_level()) {
            ob_end_clean();
        }
        
        // Start fresh output buffer
        ob_start();
        
        // ========================================
        // END CRITICAL FIX 1
        // ========================================
        
        error_log("=== PROCESS BULK UPLOAD START ===");
        
        try {
            // Check if user has permission
            if (!$this->data['hasBulkUploadPermission']) {
                error_log("Permission denied - user lacks bulk upload permission");
                echo json_encode(['success' => false, 'error' => 'You do not have permission to upload bulk data.']);
                ob_end_flush();
                exit;
            }
            
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                error_log("Invalid request method: " . $_SERVER['REQUEST_METHOD']);
                echo json_encode(['success' => false, 'error' => 'Invalid request method.']);
                ob_end_flush();
                exit;
            }
            
            // Validate CSRF token
            error_log("Checking CSRF token...");
            $csrfToken = $_POST['csrf_token'] ?? '';
            if (!$this->validateCsrfToken($csrfToken)) {
                error_log("CSRF token validation failed");
                echo json_encode(['success' => false, 'error' => 'Invalid or expired CSRF token. Please try again.']);
                ob_end_flush();
                exit;
            }
            error_log("CSRF token validated");
            
            // Check for uploaded file
            $file = null;
            if (!empty($_FILES['file']['name']) && $_FILES['file']['error'] === UPLOAD_ERR_OK) {
                $file = $_FILES['file'];
            } elseif (!empty($_FILES['bulk_file']['name']) && $_FILES['bulk_file']['error'] === UPLOAD_ERR_OK) {
                $file = $_FILES['bulk_file'];
            }

            if (!$file) {
                error_log("No file received in bulk upload. FILES = " . print_r($_FILES, true));
                echo json_encode(['success' => false, 'error' => 'No valid CSV file received']);
                ob_end_flush();
                exit;
            }
            
            error_log("File uploaded: " . $file['name'] . ", Size: " . $file['size'] . ", Error: " . $file['error']);
            
            // ========================================
            // FIXED: Use our new getMimeType() method instead of mime_content_type()
            // ========================================
            $allowedTypes = ['text/csv', 'application/vnd.ms-excel', 'application/csv', 'text/x-csv', 'application/x-csv'];
            $fileType = $this->getMimeType($file['tmp_name']);
            error_log("File type detected using getMimeType(): " . $fileType . ", File type from FILES: " . $file['type']);
            
            // Validate file type
            $isValidType = false;
            if (in_array($fileType, $allowedTypes)) {
                $isValidType = true;
            } elseif (in_array($file['type'], $allowedTypes)) {
                $isValidType = true;
            }
            
            if (!$isValidType) {
                $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
                if (strtolower($extension) !== 'csv') {
                    error_log("Invalid file type or extension: " . $extension);
                    echo json_encode(['success' => false, 'error' => 'Please upload a valid CSV file (.csv extension).']);
                    ob_end_flush();
                    exit;
                }
                error_log("Allowing CSV file despite mime type detection issue - extension is .csv");
            }
            
            // Validate file size (max 10MB)
            $maxSize = 10 * 1024 * 1024; // 10MB
            if ($file['size'] > $maxSize) {
                error_log("File size too large: " . $file['size'] . " bytes");
                echo json_encode(['success' => false, 'error' => 'File size must be less than 10MB.']);
                ob_end_flush();
                exit;
            }
            
            // Create upload directory if not exists
            $uploadDir = ROOT_PATH . '/storage/uploads/nominal-roll/';
            if (!file_exists($uploadDir)) {
                error_log("Creating upload directory: " . $uploadDir);
                if (!mkdir($uploadDir, 0755, true)) {
                    error_log("Failed to create upload directory");
                    echo json_encode(['success' => false, 'error' => 'Failed to create upload directory.']);
                    ob_end_flush();
                    exit;
                }
            }
            
            // Generate unique filename
            $fileExt = pathinfo($file['name'], PATHINFO_EXTENSION);
            $filename = 'bulk_upload_' . time() . '_' . uniqid() . '.' . $fileExt;
            $filePath = $uploadDir . $filename;
            
            // Move uploaded file
            if (!move_uploaded_file($file['tmp_name'], $filePath)) {
                error_log("Failed to move uploaded file to: " . $filePath);
                echo json_encode(['success' => false, 'error' => 'Failed to save uploaded file.']);
                ob_end_flush();
                exit;
            }
            
            error_log("File saved successfully to: " . $filePath);
            
            // Get import options from POST
            $importType = $_POST['import_type'] ?? 'create';
            $updateExisting = isset($_POST['update_existing']) ? (int)$_POST['update_existing'] : 0;
            $skipDuplicates = isset($_POST['skip_duplicates']) ? (int)$_POST['skip_duplicates'] : 1;
            
            error_log("Import options - Type: $importType, Update: $updateExisting, Skip Dupes: $skipDuplicates");
            
            // Parse the CSV file
            error_log("Parsing CSV file...");
            $parseResult = $this->parseCSVFileForUpload($filePath);
            
            if (isset($parseResult['error'])) {
                error_log("CSV parsing error: " . $parseResult['error']);
                echo json_encode(['success' => false, 'error' => 'Failed to parse CSV file: ' . $parseResult['error']]);
                ob_end_flush();
                exit;
            }
            
            error_log("CSV parsed successfully. Total rows: " . $parseResult['total_rows']);
            
            // Create bulk upload record
            $uploadId = $this->model->createBulkUpload(
                $file['name'],
                $filePath,
                $parseResult['total_rows'],
                $_SESSION['user_id'] ?? null,
                $importType,
                $updateExisting,
                $skipDuplicates
            );
            
            error_log("Bulk upload record created with ID: " . $uploadId);
            
            // Process data
            $processResult = $this->processBulkData($uploadId, $parseResult['data'], $importType, $updateExisting, $skipDuplicates);
            
            error_log("Processing complete: " . print_r($processResult, true));
            
            // Return success response
            $response = [
                'success' => true,
                'message' => 'Bulk upload completed successfully!',
                'data' => [
                    'total_rows' => $parseResult['total_rows'],
                    'valid_rows' => $parseResult['valid_rows'],
                    'processed_rows' => $processResult['processed'],
                    'successful' => $processResult['successful'],
                    'failed' => $processResult['failed'],
                    'skipped' => $processResult['skipped'],
                    'upload_id' => $uploadId
                ],
                'errors' => $processResult['errors']
            ];
            
            error_log("=== PROCESS BULK UPLOAD END - SUCCESS ===");
            echo json_encode($response);
            
        } catch (Exception $e) {
            error_log("=== PROCESS BULK UPLOAD END - ERROR ===");
            error_log("Exception: " . $e->getMessage());
            error_log("Trace: " . $e->getTraceAsString());
            
            // Make sure we return valid JSON even on error
            $errorResponse = json_encode(['success' => false, 'error' => 'Server error: ' . $e->getMessage()]);
            
            if ($errorResponse === false) {
                // If json_encode fails, send a simple error
                echo '{"success":false,"error":"Unknown server error occurred"}';
            } else {
                echo $errorResponse;
            }
        }
        
        // ========================================
        // CRITICAL FIX 2: Clean output and exit
        // ========================================
        $output = ob_get_contents();
        ob_end_clean();
        
        // Only output if it's valid JSON, otherwise output error
        if (strpos($output, '{') === 0) {
            echo $output;
        } else {
            error_log("Invalid output detected (not JSON): " . substr($output, 0, 200));
            echo json_encode(['success' => false, 'error' => 'Server returned invalid response format.']);
        }
        
        exit;
        // ========================================
        // END CRITICAL FIX 2
        // ========================================
    }

    /**
     * Parse CSV file for upload processing
     */
    private function parseCSVFileForUpload($filePath) {
        error_log("Parsing CSV file: " . $filePath);
        
        if (!file_exists($filePath)) {
            return ['error' => 'File not found'];
        }
        
        $data = [];
        $validRows = 0;
        $errors = [];
        
        if (($handle = fopen($filePath, 'r')) !== false) {
            // Read and validate headers
            $headers = fgetcsv($handle, 1000, ',');
            
            if (!$headers || empty($headers[0])) {
                fclose($handle);
                return ['error' => 'CSV file has no headers or is empty'];
            }
            
            // Clean headers
            $cleanedHeaders = [];
            foreach ($headers as $header) {
                // Remove UTF-8 BOM if present
                $header = preg_replace('/^\xEF\xBB\xBF/', '', $header);
                // Convert to lowercase and replace spaces with underscores
                $header = strtolower(trim($header));
                $header = str_replace([' ', '-', '.', '(', ')'], '_', $header);
                // Remove multiple underscores
                $header = preg_replace('/_+/', '_', $header);
                // Remove trailing underscores
                $header = rtrim($header, '_');
                $cleanedHeaders[] = $header;
            }
            
            error_log("Cleaned headers: " . implode(', ', $cleanedHeaders));
            
            $rowCount = 0;
            while (($row = fgetcsv($handle, 1000, ',')) !== false) {
                $rowCount++;
                
                // Skip empty rows
                if (empty(array_filter($row))) {
                    continue;
                }
                
                // Map row data to headers
                $rowData = [];
                foreach ($cleanedHeaders as $index => $header) {
                    if (isset($row[$index])) {
                        $rowData[$header] = trim($row[$index]);
                    } else {
                        $rowData[$header] = '';
                    }
                }
                
                // Validate required fields
                $requiredFields = ['employee_number', 'surname', 'first_name'];
                $rowErrors = [];
                
                foreach ($requiredFields as $field) {
                    if (empty($rowData[$field])) {
                        $rowErrors[] = "Row $rowCount: Missing required field '$field'";
                    }
                }
                
                if (empty($rowErrors)) {
                    $data[] = $rowData;
                    $validRows++;
                } else {
                    $errors = array_merge($errors, $rowErrors);
                }
            }
            
            fclose($handle);
            
            error_log("Parsed $rowCount rows, $validRows valid rows, " . count($errors) . " errors");
            
            return [
                'headers' => $cleanedHeaders,
                'data' => $data,
                'total_rows' => $rowCount,
                'valid_rows' => $validRows,
                'errors' => $errors
            ];
        }
        
        return ['error' => 'Failed to open CSV file'];
    }

    /**
     * Process bulk data (import to database) - FIXED VERSION
     */
    private function processBulkData($uploadId, $data, $importType, $updateExisting, $skipDuplicates) {
        error_log("Processing bulk data for upload ID: $uploadId");
        
        // Delegate ALL processing to the model (proper MVC)
        $result = $this->model->processBulkUploadData(
            $data,
            $importType,
            $updateExisting,
            $skipDuplicates,
            $_SESSION['user_id'] ?? null
        );
        
        // Update bulk upload record with results
        $updateData = [
            'successful_imports' => $result['success'] + $result['updated'],
            'failed_imports' => $result['failed'],
            'skipped_imports' => $result['skipped'],
            'status' => 'completed',
            'completed_at' => date('Y-m-d H:i:s')
        ];
        
        if (!empty($result['errors'])) {
            $updateData['error_log'] = json_encode($result['errors']);
        }
        
        $this->model->updateBulkUpload($uploadId, $updateData);
        
        return [
            'processed' => count($data),
            'successful' => $result['success'] + $result['updated'],
            'failed' => $result['failed'],
            'skipped' => $result['skipped'],
            'errors' => $result['errors']
        ];
    }
    
    /**
     * Download bulk upload template
     */
    public function downloadTemplate() {
        try {
            // CSV template headers (updated with new fields)
            $headers = [
                'S/N', 'Employee Number', 'Surname', 'First Name', 'Middle Name', 'Sex', 'Date of Birth',
                'Marital Status', 'Rank', 'Grade Level (GL)', 'Qualification', 'Qualification Date',
                'Highest Qualification', 'Year of Highest Qualification', 'Additional Qualifications',
                'Date of 1st Appt.', 'Date of Confirmation', 'Rank on 1st Appt.',
                'Date of Present. Appt.', 'State of Origin', 'Local Govt. Area', 'State of Residence',
                'Residential Address', 'PF No', 'NHF No', 'Bank Name', 'Bank Branch', 'Other Bank Name',
                'Account No', 'Pension Fund Admin', 'Other Pension Fund Admin', 'Pension No', 
                'Telephone No', 'Email'
            ];
            
            // Sample data
            $sampleData = [
                [
                    '1', 'EMP20240001', 'Doe', 'John', 'Michael', 'Male', '1990-05-15',
                    'Married', 'Senior Lecturer', '15', 'B.Sc Nursing', '2010-05-20',
                    'PhD in Nursing', '2020', '[{"qualification":"M.Sc Nursing","year":"2015"},{"qualification":"PGDE","year":"2016"}]',
                    '2015-03-01', '2016-03-01', 'Lecturer II',
                    '2023-01-15', 'FCT', 'Gwagwalada', 'FCT', 'Plot 123, Gwagwalada, Abuja',
                    'PF123456', 'NHF789012', 'First Bank', 'Gwagwalada', '', '1234567890',
                    'PENCOM', '', 'PEN123456', '08012345678', 'john.doe@fcns.edu.ng'
                ]
            ];
            
            // Set headers for CSV download
            header('Content-Type: text/csv; charset=utf-8');
            header('Content-Disposition: attachment; filename=nominal_roll_template_' . date('Y-m-d') . '.csv');
            
            $output = fopen('php://output', 'w');
            
            // Add UTF-8 BOM for Excel compatibility
            fputs($output, $bom = (chr(0xEF) . chr(0xBB) . chr(0xBF)));
            
            // Add headers
            fputcsv($output, $headers);
            
            // Add sample data
            foreach ($sampleData as $row) {
                fputcsv($output, $row);
            }
            
            fclose($output);
            exit;
            
        } catch (Exception $e) {
            error_log("NominalRollController downloadTemplate error: " . $e->getMessage());
            $this->flash('error', 'Failed to download template.');
            $this->redirect('/admin/nominal-roll/bulk-upload');
        }
    }
    
    /**
     * ============================================
     * EXPORT FUNCTIONALITY - UPDATED VERSION
     * ============================================
     */
    
    /**
     * Export employees data in various formats
     */
    public function export() {
        // Check if user has permission to export
        if (!$this->data['hasExportPermission']) {
            $this->flash('error', 'You do not have permission to export data.');
            $this->redirect('/admin/nominal-roll');
            return;
        }
        
        try {
            $format = $this->input('format', 'csv');
            $id = $this->input('id', null);
            
            if ($format === 'pdf') {
                $this->exportPdf($id);
            } else if ($format === 'excel') {
                // Get filters
                $filters = [
                    'search' => $this->input('search', ''),
                    'state' => $this->input('state', ''),
                    'grade_level' => $this->input('grade_level', ''),
                    'rank' => $this->input('rank', ''),
                    'sex' => $this->input('sex', ''),
                    'status' => $this->input('status', ''),
                    'is_draft' => $this->input('is_draft', '')
                ];
                
                // Get employees data
                $employees = $this->model->exportEmployees($filters);
                $this->exportToExcel($employees);
            } else {
                // Get filters
                $filters = [
                    'search' => $this->input('search', ''),
                    'state' => $this->input('state', ''),
                    'grade_level' => $this->input('grade_level', ''),
                    'rank' => $this->input('rank', ''),
                    'sex' => $this->input('sex', ''),
                    'status' => $this->input('status', ''),
                    'is_draft' => $this->input('is_draft', '')
                ];
                
                // Get employees data
                $employees = $this->model->exportEmployees($filters);
                $this->exportToCSV($employees);
            }
            
        } catch (Exception $e) {
            error_log("NominalRollController export error: " . $e->getMessage());
            $this->flash('error', 'Failed to export data: ' . $e->getMessage());
            
            if ($id) {
                $this->redirect('/admin/nominal-roll/view/' . $id);
            } else {
                $this->redirect('/admin/nominal-roll');
            }
        }
    }
    
    /**
     * Export to CSV format
     */
    private function exportToCSV($employees) {
        // Set headers for CSV download
        header('Content-Type: text/csv; charset=utf-8');
        $filename = $this->model->getSetting('export_filename', 'employees_{date}.csv');
        $filename = str_replace('{date}', date('Y-m-d_H-i'), $filename);
        header('Content-Disposition: attachment; filename=' . $filename);
        
        $output = fopen('php://output', 'w');
        
        // Add UTF-8 BOM for Excel compatibility
        fputs($output, $bom = (chr(0xEF) . chr(0xBB) . chr(0xBF)));
        
        // Get export fields setting
        $exportFields = json_decode($this->model->getSetting('export_fields', '[]'), true);
        
        if (empty($exportFields)) {
            // Default fields if not configured
            $csvHeaders = [
                'Employee Number', 'Surname', 'First Name', 'Middle Name', 'Sex', 'Date of Birth',
                'Marital Status', 'Rank', 'Grade Level', 'Qualification', 'Qualification Date',
                'Highest Qualification', 'Year of Highest Qualification', 'Additional Qualifications',
                'Date of 1st Appointment', 'Date of Confirmation', 'Rank on 1st Appointment',
                'Date of Present Appointment', 'State of Origin', 'Local Govt. Area', 'State of Residence',
                'Residential Address', 'PF Number', 'NHF Number', 'Bank Name', 'Bank Branch', 'Other Bank Name',
                'Account Number', 'Pension Fund Admin', 'Other Pension Fund Admin', 'Pension Number',
                'Telephone Number', 'Email', 'Status', 'Created At', 'Updated At'
            ];
        } else {
            $csvHeaders = $exportFields;
        }
        
        fputcsv($output, $csvHeaders);
        
        // Add data rows
        foreach ($employees as $employee) {
            $row = [];
            
            foreach ($csvHeaders as $header) {
                $field = strtolower(str_replace(' ', '_', $header));
                $row[] = $employee[$field] ?? '';
            }
            
            fputcsv($output, $row);
        }
        
        // Add summary section
        fputcsv($output, []); // Empty row
        fputcsv($output, ['EXPORT SUMMARY']);
        fputcsv($output, ['Total Employees:', count($employees)]);
        fputcsv($output, ['Generated On:', date('Y-m-d H:i:s')]);
        fputcsv($output, ['Generated By:', $_SESSION['username'] ?? 'System']);
        
        fclose($output);
        exit;
    }
    
    /**
     * Export to Excel format (simple CSV with .xls extension)
     */
    private function exportToExcel($employees) {
        // Set headers for Excel download
        header('Content-Type: application/vnd.ms-excel');
        $filename = $this->model->getSetting('export_filename', 'employees_{date}.xls');
        $filename = str_replace('{date}', date('Y-m-d_H-i'), $filename);
        header('Content-Disposition: attachment; filename=' . $filename);
        
        echo '<table border="1">';
        
        // Headers
        echo '<tr>';
        $headers = [
            'Employee Number', 'Surname', 'First Name', 'Middle Name', 'Sex', 'Date of Birth',
            'Marital Status', 'Rank', 'Grade Level', 'Qualification', 'Qualification Date',
            'Highest Qualification', 'Year of Highest Qualification',
            'Date of 1st Appointment', 'Date of Confirmation',
            'State of Origin', 'Local Govt. Area', 'State of Residence',
            'PF Number', 'NHF Number', 'Bank Name', 'Account Number',
            'Pension Fund Admin', 'Pension Number', 'Telephone Number', 'Email', 'Status'
        ];
        
        foreach ($headers as $header) {
            echo '<th>' . htmlspecialchars($header, ENT_COMPAT | ENT_HTML401, 'UTF-8', true) . '</th>';
        }
        echo '</tr>';
        
        // Data rows
        foreach ($employees as $employee) {
            echo '<tr>';
            foreach ($headers as $header) {
                $field = strtolower(str_replace(' ', '_', $header));
                echo '<td>' . htmlspecialchars($employee[$field] ?? '', ENT_COMPAT | ENT_HTML401, 'UTF-8', true) . '</td>';
            }
            echo '</tr>';
        }
        
        echo '</table>';
        exit;
    }
    
    /**
     * ============================================
     * REPORTING METHODS - UPDATED WITH FIXES
     * ============================================
     */
    
    /**
     * Display reports page
     */
    public function reports() {
        try {
            $availableFields = $this->model->getAvailableReportFields();
            $defaultFields = $this->model->getDefaultReportFields();
            $savedReports = $this->model->getSavedReports($_SESSION['user_id'] ?? null);
            
            $this->data = [
                'availableFields' => $availableFields,
                'defaultFields' => $defaultFields,
                'savedReports' => $savedReports,
                'pageTitle' => 'Nominal Roll Reports',
                'pageDescription' => 'Generate custom reports'
            ];
            
            $this->render('admin/nominal-roll/reports');
            
        } catch (Exception $e) {
            error_log("Reports error: " . $e->getMessage());
            $this->flash('error', 'Failed to load reports');
            $this->redirect('/admin/nominal-roll');
        }
    }
    
    /**
     * Generate report preview via AJAX - DEBUG VERSION
     */
    public function generatePreview() {
        header('Content-Type: application/json');
        
        error_log("=== GENERATE PREVIEW DEBUG ===");
        error_log("POST data: " . print_r($_POST, true));
        error_log("CSRF token in POST: " . ($_POST['csrf_token'] ?? 'NOT FOUND'));
        error_log("Session tokens: " . print_r($_SESSION['csrf_tokens'] ?? [], true));
        
        try {
            // Check if it's an AJAX request
            if (empty($_SERVER['HTTP_X_REQUESTED_WITH']) || 
                strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) !== 'xmlhttprequest') {
                error_log("ERROR: Not an AJAX request");
                throw new Exception('Invalid request type');
            }
            
            // Get POST data
            $postData = $_POST;
            
            // Get CSRF token
            $csrfToken = $postData['csrf_token'] ?? '';
            
            if (empty($csrfToken)) {
                error_log("ERROR: CSRF token empty in POST");
                throw new Exception('No CSRF token provided. Please refresh the page and try again.');
            }
            
            // Validate CSRF token using controller method
            if (!$this->validateCsrfToken()) {
                error_log("ERROR: CSRF token validation failed");
                
                // Try direct validation as fallback
                if (isset($_SESSION['csrf_tokens'][$csrfToken])) {
                    $tokenTime = $_SESSION['csrf_tokens'][$csrfToken];
                    if (time() - $tokenTime <= 3600) {
                        error_log("WARNING: Token found in session but controller validation failed");
                        // Token exists and is not expired - maybe validation logic issue
                    } else {
                        error_log("ERROR: Token expired in session");
                    }
                }
                
                throw new Exception('Invalid or expired CSRF token. Please refresh the page and try again.');
            }
            
            error_log("SUCCESS: CSRF token validated");
            
            // Rest of your code...
            $selectedFields = $postData['selected_fields'] ?? [];
            
            if (empty($selectedFields)) {
                throw new Exception('Please select at least one field');
            }
            
            // Get filters
            $filters = [
                'search' => $postData['search'] ?? '',
                'state' => $postData['filter_state'] ?? '',
                'department' => $postData['filter_department'] ?? '',
                'grade_level' => $postData['filter_grade_level'] ?? '',
                'sex' => $postData['filter_sex'] ?? '',
                'rank' => $postData['filter_rank'] ?? '',
                'status' => $postData['filter_status'] ?? 'active'
            ];
            
            $sortOrder = $postData['sort_order'] ?? 'surname_asc';
            
            // Generate report data
            $reportData = $this->model->generateReportData($selectedFields, $filters, $sortOrder);
            
            // Get field labels
            $availableFields = $this->model->getAvailableReportFields();
            $fieldLabels = [];
            foreach ($availableFields as $category) {
                foreach ($category['fields'] as $key => $label) {
                    $fieldLabels[$key] = $label;
                }
            }
            
            // Get preview limit
            $previewLimit = isset($postData['preview_limit']) ? (int)$postData['preview_limit'] : 20;
            
            // Get preview data
            if ($previewLimit <= 0 || $previewLimit > count($reportData)) {
                $previewData = $reportData;
            } else {
                $previewData = array_slice($reportData, 0, $previewLimit);
            }
            
            // Store in session
            $_SESSION['current_report_data'] = [
                'full_data' => $reportData,
                'preview_data' => $previewData,
                'selected_fields' => $selectedFields,
                'field_labels' => $fieldLabels,
                'filters' => $filters,
                'sort_order' => $sortOrder,
                'total_records' => count($reportData),
                'preview_records' => count($previewData),
                'preview_limit' => $previewLimit,
                'statistics' => $this->model->getReportStatistics($reportData, $selectedFields),
                'generated_at' => date('Y-m-d H:i:s')
            ];
            
            error_log("=== GENERATE PREVIEW SUCCESS: " . count($previewData) . " preview records ===");
            
            echo json_encode([
                'success' => true,
                'fields' => $selectedFields,
                'fieldLabels' => $fieldLabels,
                'data' => $previewData,
                'fullData' => $reportData,
                'totalRecords' => count($reportData),
                'previewRecords' => count($previewData),
                'previewLimit' => $previewLimit,
                'config' => [
                    'selected_fields' => $selectedFields,
                    'sort_order' => $sortOrder,
                    'filters' => $filters
                ]
            ]);
            
        } catch (Exception $e) {
            error_log("=== GENERATE PREVIEW ERROR: " . $e->getMessage() . " ===");
            echo json_encode([
                'success' => false,
                'error' => $e->getMessage()
            ]);
        }
        
        exit;
    }
    
    /**
     * Generate report - UPDATED VERSION (redirects to reportPreview)
     */
    public function generateReport() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/admin/nominal-roll/reports');
            return;
        }
        
        try {
            // Validate CSRF token
            if (!$this->validateCsrfToken()) {
                $this->flash('error', 'Invalid or expired CSRF token. Please try again.');
                $this->redirect('/admin/nominal-roll/reports');
                return;
            }
            
            $selectedFields = $_POST['selected_fields'] ?? [];
            
            if (empty($selectedFields)) {
                throw new Exception('Please select at least one field');
            }
            
            $filters = [
                'search' => $_POST['search'] ?? '',
                'state' => $_POST['filter_state'] ?? '',
                'department' => $_POST['filter_department'] ?? '',
                'grade_level' => $_POST['filter_grade_level'] ?? '',
                'sex' => $_POST['filter_sex'] ?? '',
                'rank' => $_POST['filter_rank'] ?? '',
                'status' => $_POST['filter_status'] ?? 'active'
            ];
            
            $sortOrder = $_POST['sort_order'] ?? 'surname_asc';
            
            // Generate report data
            $reportData = $this->model->generateReportData($selectedFields, $filters, $sortOrder);
            
            // Get field labels
            $availableFields = $this->model->getAvailableReportFields();
            $fieldLabels = [];
            foreach ($availableFields as $category) {
                foreach ($category['fields'] as $key => $label) {
                    $fieldLabels[$key] = $label;
                }
            }
            
            // Get statistics
            $statistics = $this->model->getReportStatistics($reportData, $selectedFields);
            
            // Store in session for export
            $_SESSION['current_report'] = [
                'data' => $reportData,
                'config' => [
                    'selected_fields' => $selectedFields,
                    'filters' => $filters,
                    'sort_order' => $sortOrder
                ],
                'field_labels' => $fieldLabels,
                'statistics' => $statistics,
                'generated_at' => date('Y-m-d H:i:s')
            ];
            
            // Redirect to preview page instead of rendering
            $this->redirect('/admin/nominal-roll/report-preview');
            
        } catch (Exception $e) {
            error_log("Generate report error: " . $e->getMessage());
            $this->flash('error', $e->getMessage());
            $this->redirect('/admin/nominal-roll/reports');
        }
    }
    
    /**
     * Display report preview page - NEW METHOD
     */
    public function reportPreview() {
        try {
            // Check if report data exists in session
            if (!isset($_SESSION['current_report'])) {
                $this->flash('error', 'No report data found. Please generate a report first.');
                $this->redirect('/admin/nominal-roll/reports');
                return;
            }
            
            $report = $_SESSION['current_report'];
            
            $this->data = [
                'reportData' => $report['data'],
                'reportConfig' => $report['config'],
                'fieldLabels' => $report['field_labels'],
                'statistics' => $report['statistics'],
                'pageTitle' => 'Report Preview',
                'pageDescription' => 'Preview generated report'
            ];
            
            $this->render('admin/nominal-roll/report-preview');
            
        } catch (Exception $e) {
            error_log("Report preview error: " . $e->getMessage());
            $this->flash('error', 'Failed to load report preview: ' . $e->getMessage());
            $this->redirect('/admin/nominal-roll/reports');
        }
    }
    
    /**
     * ============================================
     * EXPORT EXCEL AND CSV METHODS - FIXED VERSION
     * ============================================
     */
    
    /**
     * Export to Excel with selected fields - FIXED VERSION
     */
    public function exportExcel() {
        // Check if user has permission to export
        if (!$this->data['hasExportPermission']) {
            echo json_encode(['success' => false, 'error' => 'You do not have permission to export data.']);
            exit;
        }
        
        try {
            // Validate CSRF token
            if (!$this->validateCsrfToken()) {
                $this->showNominalRollError('Invalid CSRF token');
                return;
            }
            
            // Get POST data directly from $_POST since $this->request doesn't exist
            $autoFormat = isset($_POST['auto_format']) ? $_POST['auto_format'] == '1' : false;
            $includeSummary = isset($_POST['include_summary']) ? $_POST['include_summary'] == '1' : false;
            
            // Get selected fields
            $selectedFields = isset($_POST['selected_fields']) ? (array)$_POST['selected_fields'] : [];
            
            if (empty($selectedFields)) {
                $this->showNominalRollError('Please select at least one field to export.');
                return;
            }
            
            // Get filters
            $filters = [
                'search' => isset($_POST['search']) ? $_POST['search'] : '',
                'state' => isset($_POST['filter_state']) ? $_POST['filter_state'] : '',
                'department' => isset($_POST['filter_department']) ? $_POST['filter_department'] : '',
                'grade_level' => isset($_POST['filter_grade_level']) ? $_POST['filter_grade_level'] : '',
                'sex' => isset($_POST['filter_sex']) ? $_POST['filter_sex'] : '',
                'rank' => isset($_POST['filter_rank']) ? $_POST['filter_rank'] : '',
                'status' => isset($_POST['filter_status']) ? $_POST['filter_status'] : 'active'
            ];
            
            $sortOrder = isset($_POST['sort_order']) ? $_POST['sort_order'] : 'surname_asc';
            
            // Fetch data from database
            $data = $this->fetchReportData($selectedFields, $filters, $sortOrder);
            
            if (empty($data)) {
                $this->showNominalRollError('No records found with the current filters.');
                return;
            }
            
            // Get available fields for labels
            $availableFields = $this->model->getAvailableReportFields();
            $fieldLabels = [];
            foreach ($availableFields as $category) {
                foreach ($category['fields'] as $key => $label) {
                    $fieldLabels[$key] = $label;
                }
            }
            
            // Generate Excel file
            $this->generateExcelFile($data, $selectedFields, $fieldLabels, $filters, [
                'auto_format' => $autoFormat,
                'include_summary' => $includeSummary
            ]);
            
        } catch (Exception $e) {
            error_log("Export Excel error: " . $e->getMessage());
            $this->showNominalRollError('Failed to export to Excel: ' . $e->getMessage());
        }
    }

    /**
     * Export to CSV with selected fields - FIXED VERSION
     */
    public function exportCsv() {
        // Check if user has permission to export
        if (!$this->data['hasExportPermission']) {
            echo json_encode(['success' => false, 'error' => 'You do not have permission to export data.']);
            exit;
        }
        
        try {
            // Validate CSRF token
            if (!$this->validateCsrfToken()) {
                $this->showNominalRollError('Invalid CSRF token');
                return;
            }
            
            // Get POST data directly from $_POST
            $selectedFields = isset($_POST['selected_fields']) ? (array)$_POST['selected_fields'] : [];
            
            if (empty($selectedFields)) {
                $this->showNominalRollError('Please select at least one field to export.');
                return;
            }
            
            // Get filters
            $filters = [
                'search' => isset($_POST['search']) ? $_POST['search'] : '',
                'state' => isset($_POST['filter_state']) ? $_POST['filter_state'] : '',
                'department' => isset($_POST['filter_department']) ? $_POST['filter_department'] : '',
                'grade_level' => isset($_POST['filter_grade_level']) ? $_POST['filter_grade_level'] : '',
                'sex' => isset($_POST['filter_sex']) ? $_POST['filter_sex'] : '',
                'rank' => isset($_POST['filter_rank']) ? $_POST['filter_rank'] : '',
                'status' => isset($_POST['filter_status']) ? $_POST['filter_status'] : 'active'
            ];
            
            $sortOrder = isset($_POST['sort_order']) ? $_POST['sort_order'] : 'surname_asc';
            
            // Fetch data from database
            $data = $this->fetchReportData($selectedFields, $filters, $sortOrder);
            
            if (empty($data)) {
                $this->showNominalRollError('No records found with the current filters.');
                return;
            }
            
            // Get available fields for labels
            $availableFields = $this->model->getAvailableReportFields();
            $fieldLabels = [];
            foreach ($availableFields as $category) {
                foreach ($category['fields'] as $key => $label) {
                    $fieldLabels[$key] = $label;
                }
            }
            
            // Generate CSV file
            $this->generateCsvFile($data, $selectedFields, $fieldLabels, $filters);
            
        } catch (Exception $e) {
            error_log("Export CSV error: " . $e->getMessage());
            $this->showNominalRollError('Failed to export to CSV: ' . $e->getMessage());
        }
    }

    /**
     * Fetch report data from database
     */
    private function fetchReportData($selectedFields, $filters, $sortOrder)
    {
        // Generate report data using model
        $reportData = $this->model->generateReportData($selectedFields, $filters, $sortOrder);
        return $reportData;
    }

    /**
     * Generate Excel file with HTML
     */
    private function generateExcelFile($data, $selectedFields, $fieldLabels, $filters, $options)
    {
        // Generate timestamp for filename
        $timestamp = date('Y-m-d_H-i-s');
        $filename = "Nominal_Roll_Report_{$timestamp}";
        
        // HTML that Excel can open
        $html = '<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Nominal Roll Report</title>
    <style>
        body { font-family: Calibri, Arial, sans-serif; font-size: 11pt; margin: 20px; }
        table { border-collapse: collapse; width: 100%; margin-bottom: 30px; }
        th { background-color: #2C5AA0; color: white; font-weight: bold; text-align: center; padding: 10px; border: 1px solid #1C3A6F; font-size: 12pt; }
        td { padding: 8px; border: 1px solid #ddd; vertical-align: middle; }
        tr:nth-child(even) { background-color: #f8f9fa; }
        .summary { margin-top: 40px; padding: 20px; border: 2px solid #2C5AA0; background-color: #E3F2FD; }
        .summary-title { font-size: 14pt; font-weight: bold; color: #2C5AA0; margin-bottom: 15px; }
        .number { text-align: center; font-weight: bold; }
        .date { white-space: nowrap; }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .bold { font-weight: bold; }
    </style>
</head>
<body>';
        
        // Add report title
        $html .= '<h1 style="color: #2C5AA0;">Nominal Roll Report</h1>';
        $html .= '<p><strong>Generated:</strong> ' . date('F j, Y H:i:s') . '</p>';
        
        // Add filter info
        $html .= '<div style="margin-bottom: 20px; padding: 10px; background-color: #f0f8ff; border-left: 4px solid #2C5AA0;">';
        $html .= '<strong>Filter Criteria:</strong><br>';
        
        $activeFilters = [];
        if (!empty($filters['search'])) $activeFilters[] = "Search: {$filters['search']}";
        if (!empty($filters['state'])) $activeFilters[] = "State: {$filters['state']}";
        if (!empty($filters['department'])) $activeFilters[] = "Department: {$filters['department']}";
        if (!empty($filters['grade_level'])) $activeFilters[] = "Grade Level: {$filters['grade_level']}";
        if (!empty($filters['sex'])) $activeFilters[] = "Gender: {$filters['sex']}";
        if (!empty($filters['rank'])) $activeFilters[] = "Rank: {$filters['rank']}";
        if (!empty($filters['status'])) $activeFilters[] = "Status: " . ucfirst($filters['status']);
        
        if (empty($activeFilters)) {
            $html .= 'No filters applied (showing all records)';
        } else {
            $html .= implode(' | ', $activeFilters);
        }
        $html .= '</div>';
        
        // Create table
        $html .= '<table>
        <thead>
            <tr>
                <th width="50" class="text-center">S/N</th>';
        
        foreach ($selectedFields as $field) {
            $label = $fieldLabels[$field] ?? ucfirst(str_replace('_', ' ', $field));
            $html .= '<th>' . htmlspecialchars($label) . '</th>';
        }
        
        $html .= '</tr>
        </thead>
        <tbody>';
        
        // Add data rows
        $rowNumber = 1;
        foreach ($data as $row) {
            $html .= '<tr>
            <td class="number">' . $rowNumber++ . '</td>';
            
            foreach ($selectedFields as $field) {
                $value = $row[$field] ?? '';
                $cellClass = '';
                $formattedValue = htmlspecialchars($value);
                
                // Apply formatting based on field type
                if (strpos($field, 'date') !== false && $value && $value !== '0000-00-00') {
                    $cellClass = 'date';
                    try {
                        $date = date_create($value);
                        if ($date) {
                            $formattedValue = date_format($date, 'd/m/Y');
                        }
                    } catch (Exception $e) {
                        // Keep original value
                    }
                } elseif (is_numeric($value) && ($field === 'grade_level' || $field === 'employee_number')) {
                    $cellClass = 'number';
                } elseif ($field === 'sex' || $field === 'gender') {
                    $cellClass = 'text-center';
                    if (strtolower($value) === 'male') {
                        $formattedValue = '<span style="background-color: #d1ecf1; color: #0c5460; padding: 3px 8px; border-radius: 4px; font-weight: bold;">' . $formattedValue . '</span>';
                    } elseif (strtolower($value) === 'female') {
                        $formattedValue = '<span style="background-color: #f8d7da; color: #721c24; padding: 3px 8px; border-radius: 4px; font-weight: bold;">' . $formattedValue . '</span>';
                    }
                } elseif ($field === 'status') {
                    $cellClass = 'text-center';
                    if (strtolower($value) === 'active') {
                        $formattedValue = '<span style="background-color: #d4edda; color: #155724; padding: 3px 8px; border-radius: 4px; font-weight: bold;">' . $formattedValue . '</span>';
                    } elseif (strtolower($value) === 'inactive') {
                        $formattedValue = '<span style="background-color: #f8d7da; color: #721c24; padding: 3px 8px; border-radius: 4px; font-weight: bold;">' . $formattedValue . '</span>';
                    }
                }
                
                $html .= '<td class="' . $cellClass . '">' . $formattedValue . '</td>';
            }
            
            $html .= '</tr>';
        }
        
        $html .= '</tbody>
    </table>';
        
        // Add summary if requested
        if ($options['include_summary']) {
            $html .= '<div class="summary">
            <div class="summary-title">Report Summary</div>
            <table style="width: 100%; border: none;">
                <tr>
                    <td style="border: none; padding: 5px; width: 200px;"><strong>Report Name:</strong></td>
                    <td style="border: none; padding: 5px;">Nominal Roll Report</td>
                </tr>
                <tr>
                    <td style="border: none; padding: 5px;"><strong>Generated On:</strong></td>
                    <td style="border: none; padding: 5px;">' . date('Y-m-d H:i:s') . '</td>
                </tr>
                <tr>
                    <td style="border: none; padding: 5px;"><strong>Total Records:</strong></td>
                    <td style="border: none; padding: 5px;">' . count($data) . '</td>
                </tr>
                <tr>
                    <td style="border: none; padding: 5px;"><strong>Fields Included:</strong></td>
                    <td style="border: none; padding: 5px;">' . count($selectedFields) . '</td>
                </tr>
                <tr>
                    <td style="border: none; padding: 5px;"><strong>Generated By:</strong></td>
                    <td style="border: none; padding: 5px;">' . htmlspecialchars($_SESSION['user_name'] ?? 'System') . '</td>
                </tr>
            </table>
            
            <div style="margin-top: 15px; font-weight: bold;">Included Fields:</div>
            <ul>';
            
            foreach ($selectedFields as $field) {
                $label = $fieldLabels[$field] ?? ucfirst(str_replace('_', ' ', $field));
                $html .= '<li>' . htmlspecialchars($label) . '</li>';
            }
            
            $html .= '</ul>
        </div>';
        }
        
        $html .= '</body>
</html>';
        
        // Output as Excel file
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment; filename="' . $filename . '.xls"');
        header('Cache-Control: max-age=0');
        
        echo $html;
        exit;
    }

    /**
     * Generate CSV file
     */
    private function generateCsvFile($data, $selectedFields, $fieldLabels, $filters)
    {
        // Generate timestamp for filename
        $timestamp = date('Y-m-d_H-i-s');
        $filename = "Nominal_Roll_Report_{$timestamp}";
        
        // Start output
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename="' . $filename . '.csv"');
        header('Cache-Control: max-age=0');
        
        // Open output stream
        $output = fopen('php://output', 'w');
        
        // Add UTF-8 BOM for Excel
        fwrite($output, "\xEF\xBB\xBF");
        
        // Add headers
        $headers = ['S/N'];
        foreach ($selectedFields as $field) {
            $headers[] = $fieldLabels[$field] ?? ucfirst(str_replace('_', ' ', $field));
        }
        fputcsv($output, $headers);
        
        // Add data rows
        $rowNumber = 1;
        foreach ($data as $row) {
            $row = [$rowNumber++];
            foreach ($selectedFields as $field) {
                $value = $row[$field] ?? '';
                // Clean value for CSV
                $value = strip_tags($value);
                $value = html_entity_decode($value, ENT_QUOTES | ENT_HTML5, 'UTF-8');
                $row[] = $value;
            }
            fputcsv($output, $row);
        }
        
        // Add summary section
        fputcsv($output, []); // Empty row
        fputcsv($output, ['==== REPORT SUMMARY ====']);
        fputcsv($output, ['Generated On:', date('Y-m-d H:i:s')]);
        fputcsv($output, ['Total Records:', count($data)]);
        fputcsv($output, ['Fields Included:', count($selectedFields)]);
        fputcsv($output, ['Generated By:', $_SESSION['user_name'] ?? 'System']);
        
        // Add filter info
        fputcsv($output, []);
        fputcsv($output, ['Filter Criteria:']);
        
        $filterRows = [];
        if (!empty($filters['search'])) $filterRows[] = ['Search:', $filters['search']];
        if (!empty($filters['state'])) $filterRows[] = ['State:', $filters['state']];
        if (!empty($filters['department'])) $filterRows[] = ['Department:', $filters['department']];
        if (!empty($filters['grade_level'])) $filterRows[] = ['Grade Level:', $filters['grade_level']];
        if (!empty($filters['sex'])) $filterRows[] = ['Gender:', $filters['sex']];
        if (!empty($filters['rank'])) $filterRows[] = ['Rank:', $filters['rank']];
        if (!empty($filters['status'])) $filterRows[] = ['Status:', ucfirst($filters['status'])];
        
        if (empty($filterRows)) {
            fputcsv($output, ['No filters applied (showing all records)']);
        } else {
            foreach ($filterRows as $filterRow) {
                fputcsv($output, $filterRow);
            }
        }
        
        fclose($output);
        exit;
    }
    
    /**
     * Handle export from preview
     */
    private function handleExport($format) {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/admin/nominal-roll/reports');
            return;
        }
        
        try {
            // Validate CSRF token
            if (!$this->validateCsrfToken()) {
                throw new Exception('Invalid CSRF token');
            }
            
            // Get selected fields from POST
            $selectedFields = $_POST['selected_fields'] ?? [];
            
            if (empty($selectedFields)) {
                throw new Exception('No fields selected for export');
            }
            
            // Get filters from POST
            $filters = [
                'search' => $this->input('search', ''),
                'state' => $this->input('filter_state', ''),
                'department' => $this->input('filter_department', ''),
                'grade_level' => $this->input('filter_grade_level', ''),
                'sex' => $this->input('filter_sex', ''),
                'rank' => $this->input('filter_rank', ''),
                'status' => $this->input('filter_status', 'active')
            ];
            
            $sortOrder = $this->input('sort_order', 'surname_asc');
            
            // Generate report data with selected fields - IMPORTANT: Use the same method as preview
            $reportData = $this->model->generateReportData($selectedFields, $filters, $sortOrder);
            
            // Get field labels
            $availableFields = $this->model->getAvailableReportFields();
            $fieldLabels = [];
            foreach ($availableFields as $category) {
                foreach ($category['fields'] as $key => $label) {
                    $fieldLabels[$key] = $label;
                }
            }
            
            // Export with selected fields
            if ($format === 'excel') {
                $this->exportToExcelCustom($reportData, $selectedFields, $fieldLabels);
            } else {
                $this->exportToCsvCustom($reportData, $selectedFields, $fieldLabels);
            }
            
        } catch (Exception $e) {
            error_log("NominalRollController handleExport error: " . $e->getMessage());
            
            // Store error in session
            $_SESSION['flash_error'] = 'Failed to export data: ' . $e->getMessage();
            
            // Redirect back to reports page
            if ($this->input('from_preview')) {
                $this->redirect('/admin/nominal-roll/report-preview');
            } else {
                $this->redirect('/admin/nominal-roll/reports');
            }
        }
    }

    /**
     * Custom Excel export with selected fields
     */
    private function exportToExcelCustom($data, $selectedFields, $fieldLabels) {
        // Set headers for Excel download
        header('Content-Type: application/vnd.ms-excel');
        $filename = 'nominal_roll_report_' . date('Y-m-d_His') . '.xls';
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        
        echo '<html>';
        echo '<head>';
        echo '<meta charset="UTF-8">';
        echo '<style>';
        echo 'td { border: 1px solid #ddd; padding: 5px; }';
        echo 'th { background-color: #f2f2f2; border: 1px solid #ddd; padding: 8px; font-weight: bold; }';
        echo '</style>';
        echo '</head>';
        echo '<body>';
        
        // Report title
        echo '<h2>Nominal Roll Report - Custom Export</h2>';
        echo '<p>Generated: ' . date('d/m/Y H:i:s') . '</p>';
        echo '<p>Total Records: ' . count($data) . '</p>';
        echo '<p>Fields: ' . count($selectedFields) . ' selected</p>';
        
        echo '<table>';
        
        // Headers
        echo '<tr>';
        echo '<th>S/N</th>';
        foreach ($selectedFields as $field) {
            $label = $fieldLabels[$field] ?? ucwords(str_replace('_', ' ', $field));
            echo '<th>' . htmlspecialchars($label, ENT_COMPAT | ENT_HTML401, 'UTF-8', true) . '</th>';
        }
        echo '</tr>';
        
        // Data rows
        $rowNumber = 1;
        foreach ($data as $row) {
            echo '<tr>';
            echo '<td>' . $rowNumber++ . '</td>';
            foreach ($selectedFields as $field) {
                $value = $row[$field] ?? '';
                
                // Format dates
                if (strpos($field, 'date') !== false && !empty($value)) {
                    $value = date('d/m/Y', strtotime($value));
                }
                
                // Format gender
                if ($field === 'sex') {
                    if ($value === 'M' || strtolower($value) === 'male') {
                        $value = 'Male';
                    } else if ($value === 'F' || strtolower($value) === 'female') {
                        $value = 'Female';
                    }
                }
                
                echo '<td>' . htmlspecialchars($value, ENT_COMPAT | ENT_HTML401, 'UTF-8', true) . '</td>';
            }
            echo '</tr>';
        }
        
        echo '</table>';
        
        echo '</body></html>';
        exit;
    }

    /**
     * Custom CSV export with selected fields
     */
    private function exportToCsvCustom($data, $selectedFields, $fieldLabels) {
        // Set headers for CSV download
        header('Content-Type: text/csv; charset=utf-8');
        $filename = 'nominal_roll_report_' . date('Y-m-d_His') . '.csv';
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        
        $output = fopen('php://output', 'w');
        
        // Add UTF-8 BOM for Excel compatibility
        fputs($output, $bom = (chr(0xEF) . chr(0xBB) . chr(0xBF)));
        
        // Headers
        $headers = ['S/N'];
        foreach ($selectedFields as $field) {
            $headers[] = $fieldLabels[$field] ?? ucwords(str_replace('_', ' ', $field));
        }
        fputcsv($output, $headers);
        
        // Data rows
        $rowNumber = 1;
        foreach ($data as $row) {
            $csvRow = [$rowNumber++];
            foreach ($selectedFields as $field) {
                $value = $row[$field] ?? '';
                
                // Format dates
                if (strpos($field, 'date') !== false && !empty($value)) {
                    $value = date('d/m/Y', strtotime($value));
                }
                
                // Format gender
                if ($field === 'sex') {
                    if ($value === 'M' || strtolower($value) === 'male') {
                        $value = 'Male';
                    } else if ($value === 'F' || strtolower($value) === 'female') {
                        $value = 'Female';
                    }
                }
                
                $csvRow[] = $value;
            }
            fputcsv($output, $csvRow);
        }
        
        // Add summary
        fputcsv($output, []); // Empty row
        fputcsv($output, ['EXPORT SUMMARY']);
        fputcsv($output, ['Total Records:', count($data)]);
        fputcsv($output, ['Generated On:', date('Y-m-d H:i:s')]);
        fputcsv($output, ['Generated By:', $_SESSION['username'] ?? 'System']);
        
        fclose($output);
        exit;
    }
    
    /**
     * Export to Excel from PREVIEW data - RELIABLE VERSION
     */
    public function exportExcelFromPreview() {
        try {
            // Clear output buffers
            while (ob_get_level()) {
                ob_end_clean();
            }
            
            // Check if preview data exists in session
            if (!isset($_SESSION['current_report_data'])) {
                header('Content-Type: text/html');
                echo "<h1>Error: No preview data found</h1>";
                echo "<p>Please generate a preview first before exporting.</p>";
                echo '<a href="/admin/nominal-roll/reports">Back to Reports</a>';
                exit;
            }
            
            $reportData = $_SESSION['current_report_data'];
            
            // Validate we have data
            if (empty($reportData['full_data'])) {
                throw new Exception('No report data available for export');
            }
            
            // Set headers for Excel download
            header('Content-Type: application/vnd.ms-excel');
            $filename = 'nominal_roll_report_' . date('Y-m-d_His') . '.xls';
            header('Content-Disposition: attachment; filename="' . $filename . '"');
            
            // Start output
            echo '<html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40">';
            echo '<head>';
            echo '<meta charset="UTF-8">';
            echo '<meta name=ProgId content=Excel.Sheet>';
            echo '<meta name=Generator content="Microsoft Excel 11">';
            echo '<style>';
            echo 'body { font-family: Arial, sans-serif; font-size: 11px; }';
            echo 'table { border-collapse: collapse; width: 100%; }';
            echo 'th { background-color: #f2f2f2; border: 1px solid #000; padding: 8px; font-weight: bold; text-align: left; }';
            echo 'td { border: 1px solid #ccc; padding: 6px; }';
            echo '.header { text-align: center; margin-bottom: 20px; }';
            echo '.summary { margin: 20px 0; padding: 10px; background-color: #f9f9f9; border: 1px solid #ddd; }';
            echo '</style>';
            echo '</head>';
            echo '<body>';
            
            // Report header
            echo '<div class="header">';
            echo '<h2>NOMINAL ROLL REPORT</h2>';
            echo '<h3>FCT College of Nursing Sciences</h3>';
            echo '<p>Generated: ' . date('d/m/Y H:i:s') . '</p>';
            echo '<p>Total Records: ' . count($reportData['full_data']) . '</p>';
            echo '<p>Generated By: ' . ($_SESSION['username'] ?? 'System') . '</p>';
            echo '<p>Preview Generated At: ' . ($reportData['generated_at'] ?? date('Y-m-d H:i:s')) . '</p>';
            echo '</div>';
            
            // Data table
            echo '<table>';
            
            // Headers
            echo '<tr>';
            echo '<th>S/N</th>';
            foreach ($reportData['selected_fields'] as $field) {
                $label = $reportData['field_labels'][$field] ?? ucwords(str_replace('_', ' ', $field));
                echo '<th>' . htmlspecialchars($label, ENT_QUOTES, 'UTF-8') . '</th>';
            }
            echo '</tr>';
            
            // Data rows
            $rowNumber = 1;
            foreach ($reportData['full_data'] as $row) {
                echo '<tr>';
                echo '<td>' . $rowNumber++ . '</td>';
                foreach ($reportData['selected_fields'] as $field) {
                    $value = $row[$field] ?? '';
                    
                    // Format dates
                    if (strpos($field, 'date') !== false && !empty($value)) {
                        try {
                            $date = new DateTime($value);
                            $value = $date->format('d/m/Y');
                        } catch (Exception $e) {
                            // Keep original value if date parsing fails
                        }
                    }
                    
                    // Format gender
                    if ($field === 'sex') {
                        if ($value === 'M' || strtolower($value) === 'male') {
                            $value = 'Male';
                        } else if ($value === 'F' || strtolower($value) === 'female') {
                            $value = 'Female';
                        }
                    }
                    
                    // Format empty values
                    if (empty($value)) {
                        $value = '-';
                    }
                    
                    echo '<td>' . htmlspecialchars($value, ENT_QUOTES, 'UTF-8') . '</td>';
                }
                echo '</tr>';
            }
            
            echo '</table>';
            
            // Summary
            echo '<div class="summary">';
            echo '<p><strong>Export Summary:</strong></p>';
            echo '<p>Total Records Exported: ' . count($reportData['full_data']) . '</p>';
            echo '<p>Fields Exported: ' . count($reportData['selected_fields']) . '</p>';
            echo '<p>Preview Records: ' . ($reportData['preview_records'] ?? 'N/A') . '</p>';
            echo '<p>Preview Limit: ' . ($reportData['preview_limit'] ?? 'N/A') . '</p>';
            echo '<p>Generated On: ' . date('d/m/Y H:i:s') . '</p>';
            echo '</div>';
            
            echo '</body></html>';
            exit;
            
        } catch (Exception $e) {
            // Clear any output
            while (ob_get_level()) {
                ob_end_clean();
            }
            
            header('Content-Type: text/html');
            echo "<h1>Export Error</h1>";
            echo "<p>" . htmlspecialchars($e->getMessage()) . "</p>";
            echo '<a href="/admin/nominal-roll/reports">Back to Reports</a>';
            exit;
        }
    }

    /**
     * Export to CSV from PREVIEW data - RELIABLE VERSION
     */
    public function exportCsvFromPreview() {
        try {
            // Clear output buffers
            while (ob_get_level()) {
                ob_end_clean();
            }
            
            // Check if preview data exists in session
            if (!isset($_SESSION['current_report_data'])) {
                throw new Exception('No preview data found. Please generate a preview first.');
            }
            
            $reportData = $_SESSION['current_report_data'];
            
            // Validate we have data
            if (empty($reportData['full_data'])) {
                throw new Exception('No report data available for export');
            }
            
            // Set headers for CSV download
            header('Content-Type: text/csv; charset=utf-8');
            $filename = 'nominal_roll_report_' . date('Y-m-d_His') . '.csv';
            header('Content-Disposition: attachment; filename="' . $filename . '"');
            
            // Open output stream
            $output = fopen('php://output', 'w');
            
            // Add UTF-8 BOM for Excel compatibility
            fputs($output, $bom = (chr(0xEF) . chr(0xBB) . chr(0xBF)));
            
            // Headers
            $headers = ['S/N'];
            foreach ($reportData['selected_fields'] as $field) {
                $headers[] = $reportData['field_labels'][$field] ?? ucwords(str_replace('_', ' ', $field));
            }
            fputcsv($output, $headers);
            
            // Data rows
            $rowNumber = 1;
            foreach ($reportData['full_data'] as $row) {
                $csvRow = [$rowNumber++];
                foreach ($reportData['selected_fields'] as $field) {
                    $value = $row[$field] ?? '';
                    
                    // Format dates
                    if (strpos($field, 'date') !== false && !empty($value)) {
                        try {
                            $date = new DateTime($value);
                            $value = $date->format('d/m/Y');
                        } catch (Exception $e) {
                            // Keep original value if date parsing fails
                        }
                    }
                    
                    // Format gender
                    if ($field === 'sex') {
                        if ($value === 'M' || strtolower($value) === 'male') {
                            $value = 'Male';
                        } else if ($value === 'F' || strtolower($value) === 'female') {
                            $value = 'Female';
                        }
                    }
                    
                    // Format empty values
                    if (empty($value)) {
                        $value = '-';
                    }
                    
                    $csvRow[] = $value;
                }
                fputcsv($output, $csvRow);
            }
            
            // Add summary
            fputcsv($output, []); // Empty row
            fputcsv($output, ['EXPORT SUMMARY']);
            fputcsv($output, ['Total Records:', count($reportData['full_data'])]);
            fputcsv($output, ['Fields Exported:', count($reportData['selected_fields'])]);
            fputcsv($output, ['Preview Generated At:', $reportData['generated_at'] ?? date('Y-m-d H:i:s')]);
            fputcsv($output, ['Exported On:', date('Y-m-d H:i:s')]);
            fputcsv($output, ['Exported By:', $_SESSION['username'] ?? 'System']);
            
            fclose($output);
            exit;
            
        } catch (Exception $e) {
            // Clear any output
            while (ob_get_level()) {
                ob_end_clean();
            }
            
            header('Content-Type: text/html');
            echo "<h1>CSV Export Error</h1>";
            echo "<p>" . htmlspecialchars($e->getMessage()) . "</p>";
            echo '<a href="/admin/nominal-roll/reports">Back to Reports</a>';
            exit;
        }
    }
    
    /**
     * Save report configuration - UPDATED WITH CSRF VALIDATION
     */
    public function saveReport() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/admin/nominal-roll/reports');
            return;
        }
        
        try {
            // Validate CSRF token
            if (!$this->validateCsrfToken()) {
                $this->flash('error', 'Invalid or expired CSRF token. Please try again.');
                $this->redirect('/admin/nominal-roll/reports');
                return;
            }
            
            $reportId = $this->model->saveReportConfig([
                'report_name' => $_POST['report_name'],
                'selected_fields' => $_POST['selected_fields'] ?? [],
                'filters' => $_POST['filters'] ?? [],
                'sort_order' => $_POST['sort_order'] ?? 'surname_asc',
                'is_public' => $_POST['is_public'] ?? 0
            ], $_SESSION['user_id'] ?? null);
            
            if ($reportId) {
                $this->flash('success', 'Report configuration saved!');
            } else {
                $this->flash('error', 'Failed to save report');
            }
            
        } catch (Exception $e) {
            $this->flash('error', 'Error: ' . $e->getMessage());
        }
        
        $this->redirect('/admin/nominal-roll/reports');
    }
    
    /**
     * Load saved report - UPDATED VERSION (redirects to reportPreview)
     */
    public function loadReport($id) {
        try {
            $report = $this->model->getReportForUser($id, $_SESSION['user_id'] ?? null);
            
            if (!$report) {
                throw new Exception('Report not found or you do not have permission to access it');
            }
            
            // Generate data
            $reportData = $this->model->generateReportData(
                $report['selected_fields'],
                $report['filters'] ?? [],
                $report['sort_order']
            );
            
            // Get field labels
            $availableFields = $this->model->getAvailableReportFields();
            $fieldLabels = [];
            foreach ($availableFields as $category) {
                foreach ($category['fields'] as $key => $label) {
                    $fieldLabels[$key] = $label;
                }
            }
            
            // Get statistics
            $statistics = $this->model->getReportStatistics($reportData, $report['selected_fields']);
            
            // Store in session
            $_SESSION['current_report'] = [
                'data' => $reportData,
                'config' => [
                    'selected_fields' => $report['selected_fields'],
                    'filters' => $report['filters'] ?? [],
                    'sort_order' => $report['sort_order']
                ],
                'field_labels' => $fieldLabels,
                'statistics' => $statistics,
                'generated_at' => date('Y-m-d H:i:s')
            ];
            
            $this->flash('success', 'Report loaded successfully');
            $this->redirect('/admin/nominal-roll/report-preview');
            
        } catch (Exception $e) {
            $this->flash('error', $e->getMessage());
            $this->redirect('/admin/nominal-roll/reports');
        }
    }
    
    /**
     * Delete saved report
     */
    public function deleteReport($id) {
        try {
            $result = $this->model->deleteReport($id, $_SESSION['user_id'] ?? null);
            
            if ($result) {
                $this->flash('success', 'Report deleted successfully');
            } else {
                $this->flash('error', 'Failed to delete report or insufficient permissions');
            }
            
        } catch (Exception $e) {
            $this->flash('error', $e->getMessage());
        }
        
        $this->redirect('/admin/nominal-roll/reports');
    }
    
    /**
     * ============================================
     * SETTINGS & ADMIN FUNCTIONS
     * ============================================
     */
    
    /**
     * Display settings page
     */
    public function settings() {
        // Check if user has permission (super admin OR has settings permission)
        if (!$this->data['isSuperAdmin'] && !$this->data['hasSettingsPermission']) {
            echo json_encode(['success' => false, 'error' => 'You do not have permission to access settings.']);
            exit;
        }
        
        try {
            // Get settings
            $settings = $this->model->getSettings();
            
            // Get activity logs
            $activityLogs = $this->model->getActivityLogs(null, 50);
            
            // Get backup history
            $backups = $this->model->getBackups(20);
            
            $this->data = array_merge($this->data, [
                'settings' => $settings,
                'activityLogs' => $activityLogs,
                'backups' => $backups,
                'pageTitle' => 'Nominal Roll Settings - Super Admin',
                'pageDescription' => 'Configure nominal roll system settings'
            ]);
            
            $this->render('admin/nominal-roll/settings');
            
        } catch (Exception $e) {
            error_log("NominalRollController settings error: " . $e->getMessage());
            $this->showNominalRollError("Failed to load settings.");
        }
    }
    
    /**
     * Update settings
     */
    public function updateSettings() {
        // Check if user is super admin OR has settings permission
        if (!$this->data['isSuperAdmin'] && !$this->data['hasSettingsPermission']) {
            $this->jsonResponse(['error' => 'You do not have permission to update settings.']);
            return;
        }
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->jsonResponse(['error' => 'Invalid request method.']);
            return;
        }
        
        try {
            // Validate CSRF token
            if (!$this->validateCsrfToken()) {
                $this->jsonResponse(['error' => 'Invalid or expired CSRF token. Please try again.']);
                return;
            }
            
            $key = $this->input('key', '');
            $value = $this->input('value', '');
            
            if (empty($key)) {
                throw new Exception("Setting key is required.");
            }
            
            // Get user ID
            $userId = $_SESSION['user_id'] ?? null;
            
            // Update setting
            $result = $this->model->updateSetting($key, $value, $userId);
            
            if ($result) {
                $this->jsonResponse([
                    'success' => true,
                    'message' => 'Setting updated successfully!'
                ]);
            } else {
                throw new Exception("Failed to update setting.");
            }
            
        } catch (Exception $e) {
            error_log("NominalRollController updateSettings error: " . $e->getMessage());
            $this->jsonResponse(['error' => $e->getMessage()]);
        }
    }
    
    /**
     * Update multiple settings at once
     */
    public function updateMultipleSettings() {
        // Check if user is super admin OR has settings permission
        if (!$this->data['isSuperAdmin'] && !$this->data['hasSettingsPermission']) {
            $this->jsonResponse(['error' => 'You do not have permission to update settings.']);
            return;
        }
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->jsonResponse(['error' => 'Invalid request method.']);
            return;
        }
        
        try {
            // Validate CSRF token
            if (!$this->validateCsrfToken()) {
                $this->jsonResponse(['error' => 'Invalid or expired CSRF token. Please try again.']);
                return;
            }
            
            $settings = $this->input('settings', []);
            
            if (empty($settings)) {
                throw new Exception("No settings provided.");
            }
            
            // Get user ID
            $userId = $_SESSION['user_id'] ?? null;
            
            // Update settings
            $results = [];
            foreach ($settings as $key => $value) {
                $results[$key] = $this->model->updateSetting($key, $value, $userId);
            }
            
            $this->jsonResponse([
                'success' => true,
                'message' => 'Settings updated successfully!',
                'results' => $results
            ]);
            
        } catch (Exception $e) {
            error_log("NominalRollController updateMultipleSettings error: " . $e->getMessage());
            $this->jsonResponse(['error' => $e->getMessage()]);
        }
    }
    
    /**
     * Toggle editing mode
     */
    public function toggleEditing() {
        // Check if user is super admin OR has settings permission
        if (!$this->data['isSuperAdmin'] && !$this->data['hasSettingsPermission']) {
            $this->jsonResponse(['error' => 'You do not have permission to toggle editing mode.']);
            return;
        }
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->jsonResponse(['error' => 'Invalid request method.']);
            return;
        }
        
        try {
            // Validate CSRF token
            if (!$this->validateCsrfToken()) {
                $this->jsonResponse(['error' => 'Invalid or expired CSRF token. Please try again.']);
                return;
            }
            
            // Get current setting
            $currentValue = $this->model->getSetting('editing_enabled', '1');
            $newValue = $currentValue === '1' ? '0' : '1';
            
            // Get user ID
            $userId = $_SESSION['user_id'] ?? null;
            
            // Update setting
            $result = $this->model->updateSetting('editing_enabled', $newValue, $userId);
            
            if ($result) {
                $this->jsonResponse([
                    'success' => true,
                    'message' => 'Editing mode ' . ($newValue === '1' ? 'enabled' : 'disabled') . '!',
                    'new_value' => $newValue,
                    'status_text' => $newValue === '1' ? 'Enabled' : 'Disabled'
                ]);
            } else {
                throw new Exception("Failed to toggle editing mode.");
            }
            
        } catch (Exception $e) {
            error_log("NominalRollController toggleEditing error: " . $e->getMessage());
            $this->jsonResponse(['error' => $e->getMessage()]);
        }
    }
    
    /**
     * Create manual backup
     */
    public function createBackup() {
        // Check if user is super admin OR has settings permission
        if (!$this->data['isSuperAdmin'] && !$this->data['hasSettingsPermission']) {
            $this->jsonResponse(['error' => 'You do not have permission to create backups.']);
            return;
        }
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->jsonResponse(['error' => 'Invalid request method.']);
            return;
        }
        
        try {
            // Validate CSRF token
            if (!$this->validateCsrfToken()) {
                $this->jsonResponse(['error' => 'Invalid or expired CSRF token. Please try again.']);
                return;
            }
            
            $backupType = $this->input('type', 'manual');
            $backupResult = $this->model->createBackup($backupType, $_SESSION['user_id'] ?? null);
            
            if ($backupResult['success']) {
                $this->jsonResponse([
                    'success' => true,
                    'message' => 'Backup created successfully!',
                    'data' => $backupResult
                ]);
            } else {
                throw new Exception($backupResult['error'] ?? 'Failed to create backup.');
            }
            
        } catch (Exception $e) {
            error_log("NominalRollController createBackup error: " . $e->getMessage());
            $this->jsonResponse(['error' => $e->getMessage()]);
        }
    }
    
    /**
     * Restore from backup
     */
    public function restoreBackup($id) {
        // Check if user is super admin OR has settings permission
        if (!$this->data['isSuperAdmin'] && !$this->data['hasSettingsPermission']) {
            $this->flash('error', 'You do not have permission to restore backups.');
            $this->redirect('/admin/nominal-roll/settings');
            return;
        }
        
        try {
            $result = $this->model->restoreBackup($id, $_SESSION['user_id'] ?? null);
            
            if ($result) {
                $this->flash('success', 'Backup restored successfully!');
            } else {
                $this->flash('error', 'Failed to restore backup.');
            }
            
        } catch (Exception $e) {
            error_log("NominalRollController restoreBackup error: " . $e->getMessage());
            $this->flash('error', 'Failed to restore backup: ' . $e->getMessage());
        }
        
        $this->redirect('/admin/nominal-roll/settings');
    }
    
    /**
     * Download backup file
     */
    public function downloadBackup($id) {
        try {
            $backup = $this->model->getBackup($id);
            
            if (!$backup || !file_exists($backup['file_path'])) {
                $this->flash('error', 'Backup file not found.');
                $this->redirect('/admin/nominal-roll/settings');
                return;
            }
            
            header('Content-Description: File Transfer');
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename="' . basename($backup['file_path']) . '"');
            header('Expires: 0');
            header('Cache-Control: must-revalidate');
            header('Pragma: public');
            header('Content-Length: ' . filesize($backup['file_path']));
            
            readfile($backup['file_path']);
            exit;
            
        } catch (Exception $e) {
            error_log("NominalRollController downloadBackup error: " . $e->getMessage());
            $this->flash('error', 'Failed to download backup: ' . $e->getMessage());
            $this->redirect('/admin/nominal-roll/settings');
        }
    }
    
    /**
     * ============================================
     * NEWLY ADDED ADMIN METHODS
     * ============================================
     */
    
    /**
     * Clear activity logs
     */
    public function clearActivityLogs() {
        // Check if user is super admin OR has settings permission
        if (!$this->data['isSuperAdmin'] && !$this->data['hasSettingsPermission']) {
            $this->jsonResponse(['error' => 'You do not have permission to clear activity logs.']);
            return;
        }
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->jsonResponse(['error' => 'Invalid request method.']);
            return;
        }
        
        try {
            // Validate CSRF token
            if (!$this->validateCsrfToken()) {
                $this->jsonResponse(['error' => 'Invalid or expired CSRF token. Please try again.']);
                return;
            }
            
            // Clear logs older than 90 days
            $result = $this->model->clearOldActivityLogs(90);
            
            if ($result) {
                $this->jsonResponse([
                    'success' => true,
                    'message' => 'Activity logs cleared successfully!',
                    'cleaned_count' => $result
                ]);
            } else {
                throw new Exception("Failed to clear activity logs.");
            }
            
        } catch (Exception $e) {
            error_log("NominalRollController clearActivityLogs error: " . $e->getMessage());
            $this->jsonResponse(['error' => $e->getMessage()]);
        }
    }
    
    /**
     * Delete backup
     */
    public function deleteBackup($id) {
        // Check if user is super admin OR has settings permission
        if (!$this->data['isSuperAdmin'] && !$this->data['hasSettingsPermission']) {
            $this->jsonResponse(['error' => 'You do not have permission to delete backups.']);
            return;
        }
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->jsonResponse(['error' => 'Invalid request method.']);
            return;
        }
        
        try {
            // Validate CSRF token
            if (!$this->validateCsrfToken()) {
                $this->jsonResponse(['error' => 'Invalid or expired CSRF token. Please try again.']);
                return;
            }
            
            $result = $this->model->deleteBackup($id);
            
            if ($result) {
                $this->jsonResponse([
                    'success' => true,
                    'message' => 'Backup deleted successfully!'
                ]);
            } else {
                throw new Exception("Failed to delete backup.");
            }
            
        } catch (Exception $e) {
            error_log("NominalRollController deleteBackup error: " . $e->getMessage());
            $this->jsonResponse(['error' => $e->getMessage()]);
        }
    }
    
    /**
     * Reset all settings to defaults
     */
    public function resetAllSettings() {
        // Check if user is super admin OR has settings permission
        if (!$this->data['isSuperAdmin'] && !$this->data['hasSettingsPermission']) {
            $this->jsonResponse(['error' => 'You do not have permission to reset settings.']);
            return;
        }
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->jsonResponse(['error' => 'Invalid request method.']);
            return;
        }
        
        try {
            // Validate CSRF token
            if (!$this->validateCsrfToken()) {
                $this->jsonResponse(['error' => 'Invalid or expired CSRF token. Please try again.']);
                return;
            }
            
            $result = $this->model->resetAllSettings($_SESSION['user_id'] ?? null);
            
            if ($result) {
                $this->jsonResponse([
                    'success' => true,
                    'message' => 'All settings reset to defaults successfully!'
                ]);
            } else {
                throw new Exception("Failed to reset settings.");
            }
            
        } catch (Exception $e) {
            error_log("NominalRollController resetAllSettings error: " . $e->getMessage());
            $this->jsonResponse(['error' => $e->getMessage()]);
        }
    }
    
    /**
     * ============================================
     * UTILITY METHODS
     * ============================================
     */
    
    /**
     * Get form data from POST - CORRECTED VERSION for additional qualifications
     */
    private function getFormData($sanitize = false) {
        // Get all form fields
        $data = [
            'employee_number' => $this->input('employee_number', ''),
            'surname' => $this->input('surname', ''),
            'first_name' => $this->input('first_name', ''),
            'middle_name' => $this->input('middle_name', ''),
            'sex' => $this->input('sex', ''),
            'date_of_birth' => $this->input('date_of_birth', ''),
            'marital_status' => $this->input('marital_status', ''),
            'nationality' => $this->input('nationality', ''),
            'religion' => $this->input('religion', ''),
            'rank' => $this->input('rank', ''),
            'grade_level' => $this->input('grade_level', ''),
            'step' => $this->input('step', ''),
            'cadre' => $this->input('cadre', ''),
            'staff_type' => $this->input('staff_type', ''),
            'employment_type' => $this->input('employment_type', ''),
            'appointment_type' => $this->input('appointment_type', ''),
            'department' => $this->input('department', ''),
            'highest_qualification' => $this->input('highest_qualification', ''),
            'year_of_highest_qualification' => $this->input('year_of_highest_qualification', ''),
            'institution_attended' => $this->input('institution_attended', ''),
            'course_of_study' => $this->input('course_of_study', ''),
            'class_of_degree' => $this->input('class_of_degree', ''),
            'professional_certifications' => $this->input('professional_certifications', ''),
            'date_of_first_appointment' => $this->input('date_of_first_appointment', ''),
            'date_of_confirmation' => $this->input('date_of_confirmation', ''),
            'rank_on_first_appointment' => $this->input('rank_on_first_appointment', ''),
            'date_of_present_appointment' => $this->input('date_of_present_appointment', ''),
            'state' => $this->input('state', ''),
            'local_govt_area' => $this->input('local_govt_area', ''),
            'geopolitical_zone' => $this->input('geopolitical_zone', ''),
            'state_of_residence' => $this->input('state_of_residence', ''),
            'residential_address' => $this->input('residential_address', ''),
            'contact_address' => $this->input('contact_address', ''),
            'pf_number' => $this->input('pf_number', ''),
            'nhf_number' => $this->input('nhf_number', ''),
            'nin' => $this->input('nin', ''),
            'telephone_number' => $this->input('telephone_number', ''),
            'email' => $this->input('email', ''),
            'blood_group' => $this->input('blood_group', ''),
            'genotype' => $this->input('genotype', ''),
            'disability' => $this->input('disability', 'No'),
            'disability_type' => $this->input('disability_type', ''),
            'bank_name' => $this->input('bank_name', ''),
            'other_bank_name' => $this->input('other_bank_name', ''),
            'bank_branch' => $this->input('bank_branch', ''),
            'account_number' => $this->input('account_number', ''),
            'account_name' => $this->input('account_name', ''),
            'pension_fund_admin' => $this->input('pension_fund_admin', ''),
            'other_pension_fund_admin' => $this->input('other_pension_fund_admin', ''),
            'pension_number' => $this->input('pension_number', ''),
            'tin_number' => $this->input('tin_number', ''),
            'salary_structure' => $this->input('salary_structure', ''),
            'emergency_contact_name' => $this->input('emergency_contact_name', ''),
            'emergency_contact_phone' => $this->input('emergency_contact_phone', ''),
            'emergency_contact_relationship' => $this->input('emergency_contact_relationship', ''),
            'next_of_kin_name' => $this->input('next_of_kin_name', ''),
            'next_of_kin_phone' => $this->input('next_of_kin_phone', ''),
            'next_of_kin_address' => $this->input('next_of_kin_address', ''),
            'next_of_kin_relationship' => $this->input('next_of_kin_relationship', ''),
        ];
        
        // ========================================
        // CORRECTED: Process additional qualifications - BOTH CREATE AND UPDATE USE THIS SAME LOGIC
        // ========================================
        $additionalQualifications = [];
        
        if (isset($_POST['qualification_name']) && isset($_POST['qualification_year'])) {
            $names = $_POST['qualification_name'];
            $years = $_POST['qualification_year'];
            
            error_log("Processing qualifications from POST:");
            error_log("qualification_names: " . print_r($names, true));
            error_log("qualification_years: " . print_r($years, true));
            
            for ($i = 0; $i < count($names); $i++) {
                $name = trim($names[$i] ?? '');
                $year = trim($years[$i] ?? '');
                
                // Only save if both fields are filled (as per your specification)
                if (!empty($name) && !empty($year)) {
                    $additionalQualifications[] = [
                        'qualification' => $name,
                        'year' => $year
                    ];
                    error_log("Added qualification: {$name} (Year: {$year})");
                } else if (!empty($name) || !empty($year)) {
                    error_log("Skipped incomplete qualification: {$name} (Year: {$year})");
                }
            }
        }
        
        // Convert to JSON
        $additionalQualificationsJson = !empty($additionalQualifications) 
            ? json_encode($additionalQualifications) 
            : null;
        
        $data['additional_qualifications'] = $additionalQualificationsJson;
        error_log("Final additional_qualifications JSON: " . $data['additional_qualifications']);
        // ========================================
        // END CORRECTION
        // ========================================
        
        // IMPORTANT: DO NOT initialize passport_photo here
        // It will be handled separately in create/store and update methods
        
        // Handle disability type visibility
        if ($data['disability'] !== 'Yes') {
            $data['disability_type'] = null;
        }
        
        // Handle other bank name visibility
        if ($data['bank_name'] !== 'Other') {
            $data['other_bank_name'] = null;
        }
        
        // Handle other PFA visibility
        if ($data['pension_fund_admin'] !== 'Other') {
            $data['other_pension_fund_admin'] = null;
        }
        
        // Handle draft status
        $data['is_draft'] = $this->input('save_as_draft', 0) ? 1 : 0;
        $data['status'] = $data['is_draft'] ? 'draft' : 'active';
        
        if ($sanitize) {
            foreach ($data as $key => $value) {
                if (is_string($value)) {
                    $data[$key] = htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
                }
            }
        }
        
        return $data;
    }
    
    /**
     * Debug helper method
     */
    private function debugPhotoInfo($employee, $files) {
        error_log("=== DEBUG PHOTO INFO ===");
        error_log("Existing employee photo: " . ($employee['passport_photo'] ?? 'NULL'));
        error_log("Files uploaded: " . (!empty($files['passport_photo']['name']) ? 'YES' : 'NO'));
        if (!empty($files['passport_photo'])) {
            error_log("File details: " . print_r($files['passport_photo'], true));
        }
        error_log("=== END DEBUG ===");
    }
    
    /**
     * Upload passport photo - UNIVERSAL FIX (works on all servers)
     */
    private function uploadPassportPhoto() {
        try {
            $file = $_FILES['passport_photo'];
            
            error_log("=== UPLOAD PASSPORT PHOTO START ===");
            error_log("File details: " . print_r($file, true));
            
            // 1. Check for upload errors FIRST
            if ($file['error'] !== UPLOAD_ERR_OK) {
                $photoRequired = $this->model->getSetting('photo_required', '0') === '1';
                if ($photoRequired && $file['error'] === UPLOAD_ERR_NO_FILE) {
                    throw new Exception("Passport photo is required.");
                }
                error_log("Upload error code: " . $file['error']);
                return null;
            }
            
            // 2. Validate file size
            $maxSize = (int)$this->model->getSetting('passport_max_size', '2097152');
            if ($file['size'] > $maxSize) {
                throw new Exception("File size must be less than " . ($maxSize / 1024 / 1024) . "MB");
            }
            
            // 3. Get and validate file extension
            $fileExt = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
            error_log("File extension: " . $fileExt);
            
            $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'];
            if (!in_array($fileExt, $allowedExtensions)) {
                throw new Exception("Only " . implode(', ', $allowedExtensions) . " files are allowed");
            }
            
            // 4. UNIVERSAL MIME TYPE VALIDATION using our new method
            $isValidImage = false;
            
            // Method 1: Use our getMimeType method
            $mimeType = $this->getMimeType($file['tmp_name']);
            error_log("MIME type detected via getMimeType(): " . $mimeType);
            
            $allowedMimes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif'];
            if (in_array($mimeType, $allowedMimes)) {
                $isValidImage = true;
            }
            
            // Method 2: Try getimagesize() as fallback - ALWAYS AVAILABLE
            if (!$isValidImage) {
                $imageInfo = @getimagesize($file['tmp_name']);
                if ($imageInfo !== false) {
                    error_log("Image validated via getimagesize. Type: " . $imageInfo[2]);
                    $validImageTypes = [IMAGETYPE_JPEG, IMAGETYPE_PNG, IMAGETYPE_GIF];
                    if (in_array($imageInfo[2], $validImageTypes)) {
                        $isValidImage = true;
                    }
                }
            }
            
            // Method 3: Check file type from $_FILES (least reliable but works as fallback)
            if (!$isValidImage && !empty($file['type'])) {
                error_log("Checking file type from FILES array: " . $file['type']);
                if (in_array($file['type'], $allowedMimes)) {
                    $isValidImage = true;
                }
            }
            
            // Final validation check
            if (!$isValidImage) {
                throw new Exception("File is not a valid image or could not be verified.");
            }
            
            // 5. Create upload directory
            $uploadDir = ROOT_PATH . '/storage/uploads/passports/';
            error_log("Upload directory: " . $uploadDir);
            
            if (!file_exists($uploadDir)) {
                error_log("Creating upload directory...");
                if (!mkdir($uploadDir, 0755, true)) {
                    throw new Exception("Failed to create upload directory");
                }
            }
            
            // 6. Verify directory is writable
            if (!is_writable($uploadDir)) {
                throw new Exception("Upload directory is not writable");
            }
            
            // 7. Generate unique filename
            $filename = 'passport_' . time() . '_' . uniqid() . '.' . $fileExt;
            $filePath = $uploadDir . $filename;
            
            error_log("Generated filename: " . $filename);
            error_log("Full file path: " . $filePath);
            
            // 8. Move uploaded file
            if (!move_uploaded_file($file['tmp_name'], $filePath)) {
                throw new Exception("Failed to save uploaded file");
            }
            
            error_log("File moved successfully to: " . $filePath);
            
            // 9. Verify file was actually created
            if (!file_exists($filePath)) {
                throw new Exception("File was not created at expected location");
            }
            
            $fileSize = filesize($filePath);
            error_log("Verified file exists, size: " . $fileSize . " bytes");
            
            // 10. Create thumbnail if GD available (optional)
            if (function_exists('gd_info')) {
                try {
                    $this->createPassportThumbnail($filePath);
                    error_log("Thumbnail created successfully");
                } catch (Exception $e) {
                    error_log("Thumbnail creation failed: " . $e->getMessage());
                    // Don't fail upload if thumbnail fails
                }
            }
            
            // 11. Return relative path
            $relativePath = 'storage/uploads/passports/' . $filename;
            
            error_log("=== UPLOAD SUCCESS ===");
            error_log("Returning relative path: " . $relativePath);
            error_log("=== UPLOAD PASSPORT PHOTO END ===");
            
            return $relativePath;
            
        } catch (Exception $e) {
            error_log("=== UPLOAD FAILED ===");
            error_log("uploadPassportPhoto error: " . $e->getMessage());
            error_log("Stack trace: " . $e->getTraceAsString());
            throw new Exception("Passport photo upload failed: " . $e->getMessage());
        }
    }
    
    /**
     * Create passport thumbnail
     */
    private function createPassportThumbnail($filePath) {
        try {
            // Check if GD library is available
            if (!function_exists('gd_info')) {
                return false;
            }
            
            // Get image info
            $imageInfo = getimagesize($filePath);
            if (!$imageInfo) {
                return false;
            }
            
            $width = $imageInfo[0];
            $height = $imageInfo[1];
            $mime = $imageInfo['mime'];
            
            // Create image from file
            switch ($mime) {
                case 'image/jpeg':
                    $image = imagecreatefromjpeg($filePath);
                    break;
                case 'image/png':
                    $image = imagecreatefrompng($filePath);
                    break;
                default:
                    return false;
            }
            
            if (!$image) {
                return false;
            }
            
            // Create thumbnail (300x300)
            $thumbWidth = 300;
            $thumbHeight = 300;
            
            // Calculate aspect ratio
            $srcRatio = $width / $height;
            $thumbRatio = $thumbWidth / $thumbHeight;
            
            if ($srcRatio > $thumbRatio) {
                // Source is wider
                $newHeight = $thumbHeight;
                $newWidth = $thumbHeight * $srcRatio;
            } else {
                // Source is taller
                $newWidth = $thumbWidth;
                $newHeight = $thumbWidth / $srcRatio;
            }
            
            // Create thumbnail image
            $thumbnail = imagecreatetruecolor($thumbWidth, $thumbHeight);
            
            // Fill with white background
            $white = imagecolorallocate($thumbnail, 255, 255, 255);
            imagefill($thumbnail, 0, 0, $white);
            
            // Calculate positioning for center crop
            $srcX = ($newWidth - $thumbWidth) / 2;
            $srcY = ($newHeight - $thumbHeight) / 2;
            
            // Resize and crop
            imagecopyresampled($thumbnail, $image, 
                -$srcX, -$srcY, 0, 0, 
                $newWidth, $newHeight, $width, $height);
            
            // Save thumbnail
            $thumbPath = str_replace('.', '_thumb.', $filePath);
            
            switch ($mime) {
                case 'image/jpeg':
                    imagejpeg($thumbnail, $thumbPath, 85);
                    break;
                case 'image/png':
                    imagepng($thumbnail, $thumbPath, 8);
                    break;
            }
            
            // Free memory
            imagedestroy($image);
            imagedestroy($thumbnail);
            
            return true;
            
        } catch (Exception $e) {
            error_log("NominalRollController createPassportThumbnail error: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Delete file
     */
    private function deleteFile($filePath) {
        try {
            $fullPath = ROOT_PATH . '/' . ltrim($filePath, '/');
            
            if (file_exists($fullPath)) {
                unlink($fullPath);
            }
            
            // Also delete thumbnail if exists
            $thumbPath = str_replace('.', '_thumb.', $fullPath);
            if (file_exists($thumbPath)) {
                unlink($thumbPath);
            }
            
            return true;
            
        } catch (Exception $e) {
            error_log("NominalRollController deleteFile error: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Render HTML for PDF generation
     */
    private function renderPdfHtml($data) {
        extract($data);
        
        ob_start();
        
        if ($single_employee) {
            // Single employee PDF template
            ?>
            <!DOCTYPE html>
            <html>
            <head>
                <meta charset="UTF-8">
                <title>Employee Record - <?php echo htmlspecialchars($employee['employee_number'], ENT_COMPAT | ENT_HTML401, 'UTF-8', true); ?></title>
                <style>
                    body { font-family: DejaVu Sans, sans-serif; font-size: 12px; line-height: 1.4; }
                    .header { text-align: center; margin-bottom: 30px; border-bottom: 2px solid #000; padding-bottom: 15px; }
                    .header h1 { margin: 0; font-size: 20px; }
                    .header .subtitle { margin: 5px 0; font-size: 14px; color: #666; }
                    .employee-photo { float: right; width: 100px; height: 120px; margin-left: 20px; }
                    .employee-photo img { width: 100px; height: 120px; object-fit: cover; border: 1px solid #000; }
                    .pf-number { font-size: 14px; font-weight: bold; background: #f0f0f0; padding: 5px; margin: 10px 0; }
                    .section { margin: 20px 0; page-break-inside: avoid; }
                    .section-title { font-size: 14px; font-weight: bold; border-bottom: 1px solid #000; padding-bottom: 5px; margin-bottom: 10px; }
                    .info-grid { display: table; width: 100%; }
                    .info-row { display: table-row; }
                    .info-label, .info-value { display: table-cell; padding: 5px 10px 5px 0; vertical-align: top; }
                    .info-label { font-weight: bold; width: 35%; }
                    .footer { margin-top: 40px; text-align: center; font-size: 10px; color: #666; border-top: 1px solid #ccc; padding-top: 10px; }
                    @page { margin: 20mm; }
                </style>
            </head>
            <body>
                <div class="header">
                    <h1>FCT COLLEGE OF NURSING SCIENCES</h1>
                    <div class="subtitle">EMPLOYEE NOMINAL ROLL RECORD</div>
                    <div class="subtitle">Generated on: <?php echo date('F j, Y'); ?></div>
                </div>
                
                <?php if (!empty($employee['passport_photo'])): ?>
                <div class="employee-photo">
                    <img src="<?php echo ROOT_PATH . '/' . $employee['passport_photo']; ?>" alt="Passport Photo">
                </div>
                <?php endif; ?>
                
                <div style="margin-bottom: 15px;">
                    <h2 style="margin: 0 0 5px 0; font-size: 16px;">
                        <?php echo htmlspecialchars($employee['surname'] . ', ' . $employee['first_name'] . ' ' . ($employee['middle_name'] ?? ''), ENT_COMPAT | ENT_HTML401, 'UTF-8', true); ?>
                    </h2>
                    <div style="font-size: 14px; margin-bottom: 5px;">
                        <strong><?php echo htmlspecialchars($employee['rank'], ENT_COMPAT | ENT_HTML401, 'UTF-8', true); ?></strong>
                        <span style="margin-left: 10px;">GL <?php echo htmlspecialchars($employee['grade_level'], ENT_COMPAT | ENT_HTML401, 'UTF-8', true); ?></span>
                    </div>
                    <div class="pf-number">
                        PF Number: <?php echo htmlspecialchars($employee['pf_number'] ?? 'Not specified', ENT_COMPAT | ENT_HTML401, 'UTF-8', true); ?>
                    </div>
                </div>
                
                <div class="section">
                    <div class="section-title">PERSONAL INFORMATION</div>
                    <div class="info-grid">
                        <div class="info-row">
                            <div class="info-label">Employee Number:</div>
                            <div class="info-value"><?php echo htmlspecialchars($employee['employee_number'], ENT_COMPAT | ENT_HTML401, 'UTF-8', true); ?></div>
                        </div>
                        <div class="info-row">
                            <div class="info-label">Sex:</div>
                            <div class="info-value"><?php echo htmlspecialchars($employee['sex'], ENT_COMPAT | ENT_HTML401, 'UTF-8', true); ?></div>
                        </div>
                        <div class="info-row">
                            <div class="info-label">Date of Birth:</div>
                            <div class="info-value"><?php echo !empty($employee['date_of_birth']) ? date('M d, Y', strtotime($employee['date_of_birth'])) : 'N/A'; ?></div>
                        </div>
                        <div class="info-row">
                            <div class="info-label">Marital Status:</div>
                            <div class="info-value"><?php echo htmlspecialchars($employee['marital_status'] ?? 'N/A', ENT_COMPAT | ENT_HTML401, 'UTF-8', true); ?></div>
                        </div>
                        <div class="info-row">
                            <div class="info-label">Telephone:</div>
                            <div class="info-value"><?php echo htmlspecialchars($employee['telephone_number'] ?? 'N/A', ENT_COMPAT | ENT_HTML401, 'UTF-8', true); ?></div>
                        </div>
                        <div class="info-row">
                            <div class="info-label">Email:</div>
                            <div class="info-value"><?php echo htmlspecialchars($employee['email'] ?? 'N/A', ENT_COMPAT | ENT_HTML401, 'UTF-8', true); ?></div>
                        </div>
                    </div>
                </div>
                
                <div class="section">
                    <div class="section-title">EMPLOYMENT INFORMATION</div>
                    <div class="info-grid">
                        <div class="info-row">
                            <div class="info-label">Date of 1st Appointment:</div>
                            <div class="info-value"><?php echo !empty($employee['date_of_first_appointment']) ? date('M d, Y', strtotime($employee['date_of_first_appointment'])) : 'N/A'; ?></div>
                        </div>
                        <div class="info-row">
                            <div class="info-label">Date of Confirmation:</div>
                            <div class="info-value"><?php echo !empty($employee['date_of_confirmation']) ? date('M d, Y', strtotime($employee['date_of_confirmation'])) : 'N/A'; ?></div>
                        </div>
                        <div class="info-row">
                            <div class="info-label">Rank on 1st Appointment:</div>
                            <div class="info-value"><?php echo htmlspecialchars($employee['rank_on_first_appointment'] ?? 'N/A', ENT_COMPAT | ENT_HTML401, 'UTF-8', true); ?></div>
                        </div>
                        <div class="info-row">
                            <div class="info-label">Date of Present Appointment:</div>
                            <div class="info-value"><?php echo !empty($employee['date_of_present_appointment']) ? date('M d, Y', strtotime($employee['date_of_present_appointment'])) : 'N/A'; ?></div>
                        </div>
                    </div>
                </div>
                
                <div class="section">
                    <div class="section-title">QUALIFICATIONS</div>
                    <div class="info-grid">
                        <div class="info-row">
                            <div class="info-label">Highest Qualification:</div>
                            <div class="info-value">
                                <?php echo htmlspecialchars($employee['highest_qualification'] ?? 'N/A', ENT_COMPAT | ENT_HTML401, 'UTF-8', true); ?>
                                <?php if (!empty($employee['year_of_highest_qualification'])): ?>
                                (<?php echo htmlspecialchars($employee['year_of_highest_qualification'], ENT_COMPAT | ENT_HTML401, 'UTF-8', true); ?>)
                                <?php endif; ?>
                            </div>
                        </div>
                        <?php
                        $additional_qualifications = [];
                        if (!empty($employee['additional_qualifications'])) {
                            if (is_string($employee['additional_qualifications'])) {
                                $additional_qualifications = json_decode($employee['additional_qualifications'], true);
                            }
                        }
                        if (!empty($additional_qualifications) && is_array($additional_qualifications)):
                        ?>
                        <div class="info-row">
                            <div class="info-label">Additional Qualifications:</div>
                            <div class="info-value">
                                <?php foreach ($additional_qualifications as $qual): ?>
                                • <?php echo htmlspecialchars($qual['qualification'] ?? $qual ?? '', ENT_COMPAT | ENT_HTML401, 'UTF-8', true); ?>
                                <?php if (!empty($qual['year'])): ?>
                                (<?php echo htmlspecialchars($qual['year'], ENT_COMPAT | ENT_HTML401, 'UTF-8', true); ?>)
                                <?php endif; ?>
                                <br>
                                <?php endforeach; ?>
                            </div>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
                
                <div class="section">
                    <div class="section-title">LOCATION INFORMATION</div>
                    <div class="info-grid">
                        <div class="info-row">
                            <div class="info-label">State of Origin:</div>
                            <div class="info-value"><?php echo htmlspecialchars($employee['state'], ENT_COMPAT | ENT_HTML401, 'UTF-8', true); ?></div>
                        </div>
                        <div class="info-row">
                            <div class="info-label">Local Government Area:</div>
                            <div class="info-value"><?php echo htmlspecialchars($employee['local_govt_area'], ENT_COMPAT | ENT_HTML401, 'UTF-8', true); ?></div>
                        </div>
                        <div class="info-row">
                            <div class="info-label">State of Residence:</div>
                            <div class="info-value"><?php echo htmlspecialchars($employee['state_of_residence'] ?? 'Same as Origin', ENT_COMPAT | ENT_HTML401, 'UTF-8', true); ?></div>
                        </div>
                        <div class="info-row">
                            <div class="info-label">Residential Address:</div>
                            <div class="info-value"><?php echo nl2br(htmlspecialchars($employee['residential_address'] ?? 'N/A', ENT_COMPAT | ENT_HTML401, 'UTF-8', true)); ?></div>
                        </div>
                    </div>
                </div>
                
                <div class="section">
                    <div class="section-title">FINANCIAL INFORMATION</div>
                    <div class="info-grid">
                        <div class="info-row">
                            <div class="info-label">Bank Name:</div>
                            <div class="info-value"><?php echo htmlspecialchars($employee['bank_name'] ?? 'N/A', ENT_COMPAT | ENT_HTML401, 'UTF-8', true); ?></div>
                        </div>
                        <div class="info-row">
                            <div class="info-label">Bank Branch:</div>
                            <div class="info-value"><?php echo htmlspecialchars($employee['bank_branch'] ?? 'N/A', ENT_COMPAT | ENT_HTML401, 'UTF-8', true); ?></div>
                        </div>
                        <div class="info-row">
                            <div class="info-label">Account Number:</div>
                            <div class="info-value"><?php echo !empty($employee['account_number']) ? '****' . substr($employee['account_number'], -4) : 'N/A'; ?></div>
                        </div>
                        <div class="info-row">
                            <div class="info-label">NHF Number:</div>
                            <div class="info-value"><?php echo htmlspecialchars($employee['nhf_number'] ?? 'N/A', ENT_COMPAT | ENT_HTML401, 'UTF-8', true); ?></div>
                        </div>
                        <div class="info-row">
                            <div class="info-label">Pension Number:</div>
                            <div class="info-value"><?php echo htmlspecialchars($employee['pension_number'] ?? 'N/A', ENT_COMPAT | ENT_HTML401, 'UTF-8', true); ?></div>
                        </div>
                    </div>
                </div>
                
                <div class="footer">
                    <p>This is an official document from FCT College of Nursing Sciences</p>
                    <p>Generated on <?php echo date('F j, Y \a\t H:i:s'); ?></p>
                </div>
            </body>
            </html>
            <?php
        } else {
            // Multiple employees PDF template
            ?>
            <!DOCTYPE html>
            <html>
            <head>
                <meta charset="UTF-8">
                <title>Nominal Roll - <?php echo date('Y-m-d'); ?></title>
                <style>
                    body { font-family: DejaVu Sans, sans-serif; font-size: 10px; }
                    .header { text-align: center; margin-bottom: 20px; }
                    .header h1 { margin: 0; font-size: 16px; }
                    .header .subtitle { margin: 5px 0; font-size: 12px; color: #666; }
                    table { width: 100%; border-collapse: collapse; margin: 10px 0; }
                    th { background: #f0f0f0; font-weight: bold; padding: 6px; border: 1px solid #000; text-align: left; }
                    td { padding: 5px; border: 1px solid #ccc; }
                    .footer { margin-top: 20px; text-align: center; font-size: 9px; color: #666; }
                    @page { margin: 15mm; }
                    @page { size: landscape; }
                </style>
            </head>
            <body>
                <div class="header">
                    <h1>FCT COLLEGE OF NURSING SCIENCES - NOMINAL ROLL</h1>
                    <div class="subtitle">Generated on: <?php echo date('F j, Y'); ?></div>
                    <div class="subtitle">Total Employees: <?php echo count($employees); ?></div>
                </div>
                
                <table>
                    <thead>
                        <tr>
                            <th>S/N</th>
                            <th>Employee No.</th>
                            <th>Name</th>
                            <th>PF Number</th>
                            <th>Sex</th>
                            <th>Rank</th>
                            <th>GL</th>
                            <th>State</th>
                            <th>DOB</th>
                            <th>1st Appt.</th>
                            <th>Qualification</th>
                            <th>Phone</th>
                            <th>Email</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($employees as $index => $emp): ?>
                        <tr>
                            <td><?php echo $index + 1; ?></td>
                            <td><?php echo htmlspecialchars($emp['employee_number'], ENT_COMPAT | ENT_HTML401, 'UTF-8', true); ?></td>
                            <td><?php echo htmlspecialchars($emp['surname'] . ', ' . $emp['first_name'], ENT_COMPAT | ENT_HTML401, 'UTF-8', true); ?></td>
                            <td><?php echo htmlspecialchars($emp['pf_number'] ?? '-', ENT_COMPAT | ENT_HTML401, 'UTF-8', true); ?></td>
                            <td><?php echo htmlspecialchars($emp['sex'], ENT_COMPAT | ENT_HTML401, 'UTF-8', true); ?></td>
                            <td><?php echo htmlspecialchars($emp['rank'], ENT_COMPAT | ENT_HTML401, 'UTF-8', true); ?></td>
                            <td><?php echo htmlspecialchars($emp['grade_level'], ENT_COMPAT | ENT_HTML401, 'UTF-8', true); ?></td>
                            <td><?php echo htmlspecialchars($emp['state'], ENT_COMPAT | ENT_HTML401, 'UTF-8', true); ?></td>
                            <td><?php echo !empty($emp['date_of_birth']) ? date('d/m/Y', strtotime($emp['date_of_birth'])) : '-'; ?></td>
                            <td><?php echo !empty($emp['date_of_first_appointment']) ? date('d/m/Y', strtotime($emp['date_of_first_appointment'])) : '-'; ?></td>
                            <td><?php echo htmlspecialchars($emp['highest_qualification'] ?? '-', ENT_COMPAT | ENT_HTML401, 'UTF-8', true); ?></td>
                            <td><?php echo htmlspecialchars($emp['telephone_number'] ?? '-', ENT_COMPAT | ENT_HTML401, 'UTF-8', true); ?></td>
                            <td><?php echo htmlspecialchars($emp['email'] ?? '-', ENT_COMPAT | ENT_HTML401, 'UTF-8', true); ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                
                <div class="footer">
                    <p>Official Document - <?php echo count($employees); ?> employee(s) listed</p>
                    <p>Generated by: <?php echo htmlspecialchars($_SESSION['username'] ?? 'System', ENT_COMPAT | ENT_HTML401, 'UTF-8', true); ?> on <?php echo date('F j, Y \a\t H:i:s'); ?></p>
                </div>
            </body>
            </html>
            <?php
        }
        
        return ob_get_clean();
    }
    
    /**
     * Generate PDF from HTML
     */
    private function generatePdf($html, $filename) {
        // Try to use mPDF if available
        if (class_exists('Mpdf\Mpdf')) {
            $mpdf = new \Mpdf\Mpdf([
                'mode' => 'utf-8',
                'format' => 'A4',
                'default_font_size' => 10,
                'default_font' => 'dejavusans',
                'margin_left' => 15,
                'margin_right' => 15,
                'margin_top' => 20,
                'margin_bottom' => 20,
                'margin_header' => 10,
                'margin_footer' => 10
            ]);
            
            $mpdf->WriteHTML($html);
            $mpdf->Output($filename, 'D');
        } else {
            // Fallback: Output as HTML with print styling
            header('Content-Type: application/pdf');
            header('Content-Disposition: attachment; filename="' . $filename . '"');
            
            // Simple HTML that browsers can print as PDF
            echo $html;
        }
        exit;
    }
    
    /**
     * Export to PDF format
     */
    public function exportPdf($id = null) {
        try {
            if ($id) {
                // Single employee PDF
                $employee = $this->model->getEmployee($id);
                
                if (!$employee) {
                    throw new Exception("Employee not found.");
                }
                
                $this->data = array_merge($this->data, [
                    'employee' => $employee,
                    'single_employee' => true
                ]);
                
                $html = $this->renderPdfHtml($this->data);
                $filename = "employee_{$employee['employee_number']}.pdf";
                
            } else {
                // Multiple employees PDF
                $filters = [
                    'search' => $this->input('search', ''),
                    'state' => $this->input('state', ''),
                    'grade_level' => $this->input('grade_level', ''),
                    'rank' => $this->input('rank', ''),
                    'sex' => $this->input('sex', ''),
                    'status' => $this->input('status', 'active'),
                    'is_draft' => $this->input('is_draft', '')
                ];
                
                $employees = $this->model->exportEmployees($filters);
                
                $this->data = array_merge($this->data, [
                    'employees' => $employees,
                    'single_employee' => false,
                    'filters' => $filters
                ]);
                
                $html = $this->renderPdfHtml($this->data);
                $filename = "nominal_roll_" . date('Y-m-d') . ".pdf";
            }
            
            // Use mPDF if available, otherwise fallback to simple PDF
            $this->generatePdf($html, $filename);
            
        } catch (Exception $e) {
            error_log("NominalRollController exportPdf error: " . $e->getMessage());
            $this->flash('error', 'Failed to generate PDF: ' . $e->getMessage());
            
            if ($id) {
                $this->redirect('/admin/nominal-roll/view/' . $id);
            } else {
                $this->redirect('/admin/nominal-roll');
            }
        }
    }
    
    /**
     * Send JSON response
     */
    private function jsonResponse($data) {
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }
    
    /**
     * Show error message - RENAMED METHOD to avoid conflict with parent class
     */
    private function showNominalRollError($message) {
        $this->data = array_merge($this->data, [
            'error' => $message,
            'pageTitle' => 'Error - Nominal Roll',
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
            echo '<p>' . htmlspecialchars($message, ENT_COMPAT | ENT_HTML401, 'UTF-8', true) . '</p>';
            echo '<p><a href="' . ($this->data['baseUrl'] ?? '') . '/admin/dashboard">Back to Dashboard</a></p>';
            echo '</div>';
        }
    }
    
    /**
     * Override render method
     */
    protected function render($view = null, $data = []) {
        // Generate new CSRF token for each form
        $data['csrf_token'] = $this->csrfToken();
        
        // Add flash messages
        $data['flash_success'] = $this->getFlash('success');
        $data['flash_error'] = $this->getFlash('error');
        
        // Merge with controller data
        $this->data = array_merge($this->data, $data);
        
        // Call parent render method
        parent::render($view, $data);
    }
}