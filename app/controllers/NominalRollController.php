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
    private $allowedRoles = ['admin', 'editor', 'viewer'];
    
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
        
        // Initialize common data
        $this->data = array_merge($this->data, [
            'user' => $_SESSION ?? [],
            'baseUrl' => defined('BASE_URL') ? BASE_URL : '',
            'currentPage' => 'nominal-roll',
            'isSuperAdmin' => ($_SESSION['user_role'] ?? '') === 'admin',
            'isEditor' => in_array($_SESSION['user_role'] ?? '', ['admin', 'editor']),
            'isViewer' => ($_SESSION['user_role'] ?? '') === 'viewer',
            'editingEnabled' => $this->model->isEditingEnabled()
        ]);
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
            $limit = $this->model->getSetting('records_per_page', 20);
            
            // Get filters
            $filters = [
                'search' => $this->input('search', ''),
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
            
            // Set data for view
            $this->data = array_merge($this->data, [
                'employees' => $result['employees'],
                'pagination' => [
                    'total' => $result['total'],
                    'page' => $result['page'],
                    'limit' => $result['limit'],
                    'total_pages' => $result['total_pages']
                ],
                'filters' => $filters,
                'filterOptions' => $filterOptions,
                'stats' => $stats,
                'pageTitle' => 'Nominal Roll Management - FCT College of Nursing Sciences',
                'pageDescription' => 'Manage employee records and details'
            ]);
            
            // Render view
            $this->render('admin/nominal-roll/index');
            
        } catch (Exception $e) {
            error_log("NominalRollController index error: " . $e->getMessage());
            $this->showError("Failed to load nominal roll data.");
        }
    }
    
    /**
     * Display create employee form
     */
    public function create() {
        // Check if user has permission to create
        if (!$this->data['isEditor'] || (!$this->data['editingEnabled'] && !$this->data['isSuperAdmin'])) {
            $this->flash('error', 'Editing is currently disabled. Only Super Admin can modify records.');
            $this->redirect('/admin/nominal-roll');
            return;
        }
        
        try {
            // Get filter options for dropdowns
            $filterOptions = $this->model->getFilterOptions();
            
            // Generate employee number
            $employeeNumber = $this->model->generateEmployeeNumber();
            
            $this->data = array_merge($this->data, [
                'filterOptions' => $filterOptions,
                'employeeNumber' => $employeeNumber,
                'pageTitle' => 'Add New Employee - Nominal Roll',
                'pageDescription' => 'Add a new employee to the nominal roll'
            ]);
            
            $this->render('admin/nominal-roll/create');
            
        } catch (Exception $e) {
            error_log("NominalRollController create error: " . $e->getMessage());
            $this->showError("Failed to load create form.");
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
        if (!$this->data['isEditor'] || (!$this->data['editingEnabled'] && !$this->data['isSuperAdmin'])) {
            $this->flash('error', 'Editing is currently disabled. Only Super Admin can modify records.');
            $this->redirect('/admin/nominal-roll/create');
            return;
        }
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/admin/nominal-roll/create');
            return;
        }
        
        try {
            // Validate CSRF token
            $this->validateCsrf();
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
            
            if (!empty($errors)) {
                throw new Exception(implode('<br>', $errors));
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
            
            // Get filter options for re-display
            $filterOptions = $this->model->getFilterOptions();
            
            $this->data = array_merge($this->data, [
                'filterOptions' => $filterOptions,
                'error' => $e->getMessage(),
                'formData' => $this->getFormData(true),
                'pageTitle' => 'Add New Employee - Nominal Roll',
                'pageDescription' => 'Add a new employee to the nominal roll'
            ]);
            
            $this->render('admin/nominal-roll/create');
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
            $this->showError("Failed to load employee details.");
        }
    }
    
    /**
     * View passport photo (for img src)
     */
    public function viewPassportPhoto($id) {
        try {
            $employee = $this->model->getEmployee($id);
            
            if (!$employee || empty($employee['passport_photo'])) {
                // Serve a default image
                $defaultImage = ROOT_PATH . '/assets/images/default-avatar.png';
                header('Content-Type: image/png');
                readfile($defaultImage);
                exit;
            }
            
            $photoPath = ROOT_PATH . '/' . $employee['passport_photo'];
            
            if (!file_exists($photoPath)) {
                // Fallback to default if file is missing
                $defaultImage = ROOT_PATH . '/assets/images/default-avatar.png';
                header('Content-Type: image/png');
                readfile($defaultImage);
                exit;
            }
            
            // Determine MIME type and output image
            $mimeType = mime_content_type($photoPath);
            header('Content-Type: ' . $mimeType);
            header('Content-Length: ' . filesize($photoPath));
            readfile($photoPath);
            exit;
            
        } catch (Exception $e) {
            error_log("Passport photo error for ID {$id}: " . $e->getMessage());
            http_response_code(404);
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
            $this->showError("Failed to load print view.");
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
            'isEditor' => in_array($_SESSION['user_role'] ?? '', ['admin', 'editor']),
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
     * Display edit employee form
     */
    public function edit($id) {
        // Check if user has permission to edit
        if (!$this->data['isEditor'] || (!$this->data['editingEnabled'] && !$this->data['isSuperAdmin'])) {
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
            $this->showError("Failed to load edit form.");
        }
    }
    
    /**
     * Update employee record - UPDATED VERSION WITH CONSISTENT QUALIFICATIONS PROCESSING
     */
    public function update($id) {
        // Check if user has permission
        if (!$this->data['isEditor'] || (!$this->data['editingEnabled'] && !$this->data['isSuperAdmin'])) {
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
            $this->validateCsrf();
            
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
        // Check if user has permission (only super admin can delete)
        if (!$this->data['isSuperAdmin']) {
            $this->flash('error', 'Only Super Admin can delete employee records.');
            $this->redirect('/admin/nominal-roll/view/' . $id);
            return;
        }
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/admin/nominal-roll/view/' . $id);
            return;
        }
        
        try {
            // Validate CSRF token
            $this->validateCsrf();
            
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
                $this->flash('error', 'Security error: ' . $e->getMessage());
            } else {
                $this->flash('error', 'Failed to delete employee: ' . $e->getMessage());
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
            $limit = $this->model->getSetting('records_per_page', 20);
            
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
            
            $this->data = array_merge($this->data, [
                'employees' => $result['employees'],
                'pagination' => [
                    'total' => $result['total'],
                    'page' => $result['page'],
                    'limit' => $result['limit'],
                    'total_pages' => $result['total_pages']
                ],
                'filters' => $filters,
                'filterOptions' => $filterOptions,
                'pageTitle' => 'Draft Employees - Nominal Roll',
                'pageDescription' => 'Review and manage draft employee records'
            ]);
            
            $this->render('admin/nominal-roll/drafts');
            
        } catch (Exception $e) {
            error_log("NominalRollController drafts error: " . $e->getMessage());
            $this->showError("Failed to load draft employees.");
        }
    }
    
    /**
     * Approve draft employee
     */
    public function approveDraft($id) {
        // Check if user has permission
        if (!$this->data['isEditor'] || (!$this->data['editingEnabled'] && !$this->data['isSuperAdmin'])) {
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
            $this->flash('error', 'Failed to approve draft: ' . $e->getMessage());
            $this->redirect('/admin/nominal-roll/drafts');
        }
    }
    
    /**
     * ============================================
     * BULK UPLOAD FUNCTIONALITY
     * ============================================
     */
    
    /**
     * Display bulk upload form
     */
    public function bulkUpload() {
        // Check if user has permission (only admin and editor)
        if (!$this->data['isEditor']) {
            $this->flash('error', 'You do not have permission to upload bulk data.');
            $this->redirect('/admin/nominal-roll');
            return;
        }
        
        try {
            // Get bulk upload history
            $uploadHistory = $this->model->getBulkUploads(10);
            
            $this->data = array_merge($this->data, [
                'uploadHistory' => $uploadHistory,
                'pageTitle' => 'Bulk Upload Employees - Nominal Roll',
                'pageDescription' => 'Upload multiple employee records via CSV/Excel'
            ]);
            
            $this->render('admin/nominal-roll/bulk-upload');
            
        } catch (Exception $e) {
            error_log("NominalRollController bulkUpload error: " . $e->getMessage());
            $this->showError("Failed to load bulk upload form.");
        }
    }
    
    /**
     * Process bulk upload
     */
    public function processBulkUpload() {
        // Check if user has permission
        if (!$this->data['isEditor']) {
            $this->jsonResponse(['error' => 'You do not have permission to upload bulk data.']);
            return;
        }
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->jsonResponse(['error' => 'Invalid request method.']);
            return;
        }
        
        try {
            // Validate CSRF token
            $this->validateCsrf();
            
            // Check if file was uploaded
            if (!isset($_FILES['bulk_file']) || $_FILES['bulk_file']['error'] !== UPLOAD_ERR_OK) {
                throw new Exception("Please select a valid file to upload.");
            }
            
            $file = $_FILES['bulk_file'];
            
            // Validate file type
            $allowedTypes = ['text/csv', 'application/vnd.ms-excel', 'application/csv', 'text/x-csv', 'application/x-csv', 
                           'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'];
            $fileType = mime_content_type($file['tmp_name']);
            
            if (!in_array($fileType, $allowedTypes) && !in_array($file['type'], $allowedTypes)) {
                throw new Exception("Please upload a valid CSV or Excel file.");
            }
            
            // Validate file size (max 10MB)
            $maxSize = 10 * 1024 * 1024; // 10MB
            if ($file['size'] > $maxSize) {
                throw new Exception("File size must be less than 10MB.");
            }
            
            // Create upload directory if not exists
            $uploadDir = ROOT_PATH . '/storage/uploads/nominal-roll/';
            if (!file_exists($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }
            
            // Generate unique filename
            $fileExt = pathinfo($file['name'], PATHINFO_EXTENSION);
            $filename = 'bulk_upload_' . time() . '_' . uniqid() . '.' . $fileExt;
            $filePath = $uploadDir . $filename;
            
            // Move uploaded file
            if (!move_uploaded_file($file['tmp_name'], $filePath)) {
                throw new Exception("Failed to save uploaded file.");
            }
            
            // Get import options
            $importType = $this->input('import_type', 'create');
            $updateExisting = $this->input('update_existing', 0);
            $skipDuplicates = $this->input('skip_duplicates', 1);
            
            // Parse file based on type
            if ($fileExt === 'xlsx' || $fileExt === 'xls') {
                $parseResult = $this->model->parseExcelFile($filePath);
            } else {
                $parseResult = $this->model->parseCSVFile($filePath);
            }
            
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
            
            // Process in background (for better performance with large files)
            // For now, we'll process immediately
            $processResult = $this->processBulkData($uploadId, $parseResult['data'], $importType, $updateExisting, $skipDuplicates);
            
            $this->jsonResponse([
                'success' => true,
                'message' => 'Bulk upload completed successfully!',
                'data' => [
                    'total_rows' => $parseResult['total_rows'],
                    'valid_rows' => $parseResult['valid_rows'],
                    'processed_rows' => $processResult['processed'],
                    'successful' => $processResult['successful'],
                    'failed' => $processResult['failed'],
                    'skipped' => $processResult['skipped'],
                    'error_count' => count($parseResult['errors']),
                    'upload_id' => $uploadId
                ],
                'errors' => array_merge($parseResult['errors'], $processResult['errors'])
            ]);
            
        } catch (Exception $e) {
            error_log("NominalRollController processBulkUpload error: " . $e->getMessage());
            $this->jsonResponse(['error' => $e->getMessage()]);
        }
    }
    
    /**
     * Process bulk data (import to database)
     */
    private function processBulkData($uploadId, $data, $importType, $updateExisting, $skipDuplicates) {
        try {
            $successful = 0;
            $failed = 0;
            $skipped = 0;
            $errors = [];
            
            foreach ($data as $index => $employeeData) {
                try {
                    // Validate data
                    $validationErrors = $this->model->validateEmployeeData($employeeData);
                    
                    if (!empty($validationErrors)) {
                        throw new Exception(implode('; ', $validationErrors));
                    }
                    
                    // Check if employee already exists
                    $existing = $this->model->getEmployeeByNumber($employeeData['employee_number']);
                    
                    if ($existing) {
                        if ($skipDuplicates && $importType === 'create') {
                            $skipped++;
                            continue;
                        }
                        
                        if ($updateExisting) {
                            // Update existing record
                            $this->model->updateEmployee($existing['id'], $employeeData, $_SESSION['user_id'] ?? null);
                            $successful++;
                        } else {
                            $skipped++;
                        }
                    } else {
                        // Create new record
                        $this->model->createEmployee($employeeData, $_SESSION['user_id'] ?? null);
                        $successful++;
                    }
                    
                } catch (Exception $e) {
                    $failed++;
                    $errors[] = "Row " . ($index + 1) . ": " . $e->getMessage();
                }
            }
            
            // Update bulk upload record
            $this->model->updateBulkUpload($uploadId, [
                'successful_imports' => $successful,
                'failed_imports' => $failed,
                'skipped_imports' => $skipped,
                'error_log' => json_encode($errors),
                'status' => 'completed',
                'completed_at' => date('Y-m-d H:i:s')
            ]);
            
            return [
                'processed' => count($data),
                'successful' => $successful,
                'failed' => $failed,
                'skipped' => $skipped,
                'errors' => $errors
            ];
            
        } catch (Exception $e) {
            error_log("NominalRollController processBulkData error: " . $e->getMessage());
            
            // Update bulk upload record with error
            $this->model->updateBulkUpload($uploadId, [
                'status' => 'failed',
                'error_log' => json_encode([$e->getMessage()])
            ]);
            
            return [
                'processed' => 0,
                'successful' => 0,
                'failed' => count($data),
                'skipped' => 0,
                'errors' => [$e->getMessage()]
            ];
        }
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
     * EXPORT FUNCTIONALITY
     * ============================================
     */
    
    /**
     * Export employees data in various formats
     */
    public function export() {
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
            echo '<th>' . htmlspecialchars($header) . '</th>';
        }
        echo '</tr>';
        
        // Data rows
        foreach ($employees as $employee) {
            echo '<tr>';
            foreach ($headers as $header) {
                $field = strtolower(str_replace(' ', '_', $header));
                echo '<td>' . htmlspecialchars($employee[$field] ?? '') . '</td>';
            }
            echo '</tr>';
        }
        
        echo '</table>';
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
                <title>Employee Record - <?php echo htmlspecialchars($employee['employee_number']); ?></title>
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
                        <?php echo htmlspecialchars($employee['surname'] . ', ' . $employee['first_name'] . ' ' . ($employee['middle_name'] ?? '')); ?>
                    </h2>
                    <div style="font-size: 14px; margin-bottom: 5px;">
                        <strong><?php echo htmlspecialchars($employee['rank']); ?></strong>
                        <span style="margin-left: 10px;">GL <?php echo htmlspecialchars($employee['grade_level']); ?></span>
                    </div>
                    <div class="pf-number">
                        PF Number: <?php echo htmlspecialchars($employee['pf_number'] ?? 'Not specified'); ?>
                    </div>
                </div>
                
                <div class="section">
                    <div class="section-title">PERSONAL INFORMATION</div>
                    <div class="info-grid">
                        <div class="info-row">
                            <div class="info-label">Employee Number:</div>
                            <div class="info-value"><?php echo htmlspecialchars($employee['employee_number']); ?></div>
                        </div>
                        <div class="info-row">
                            <div class="info-label">Sex:</div>
                            <div class="info-value"><?php echo htmlspecialchars($employee['sex']); ?></div>
                        </div>
                        <div class="info-row">
                            <div class="info-label">Date of Birth:</div>
                            <div class="info-value"><?php echo !empty($employee['date_of_birth']) ? date('M d, Y', strtotime($employee['date_of_birth'])) : 'N/A'; ?></div>
                        </div>
                        <div class="info-row">
                            <div class="info-label">Marital Status:</div>
                            <div class="info-value"><?php echo htmlspecialchars($employee['marital_status'] ?? 'N/A'); ?></div>
                        </div>
                        <div class="info-row">
                            <div class="info-label">Telephone:</div>
                            <div class="info-value"><?php echo htmlspecialchars($employee['telephone_number'] ?? 'N/A'); ?></div>
                        </div>
                        <div class="info-row">
                            <div class="info-label">Email:</div>
                            <div class="info-value"><?php echo htmlspecialchars($employee['email'] ?? 'N/A'); ?></div>
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
                            <div class="info-value"><?php echo htmlspecialchars($employee['rank_on_first_appointment'] ?? 'N/A'); ?></div>
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
                                <?php echo htmlspecialchars($employee['highest_qualification'] ?? 'N/A'); ?>
                                <?php if (!empty($employee['year_of_highest_qualification'])): ?>
                                (<?php echo htmlspecialchars($employee['year_of_highest_qualification']); ?>)
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
                                • <?php echo htmlspecialchars($qual['qualification'] ?? $qual ?? ''); ?>
                                <?php if (!empty($qual['year'])): ?>
                                (<?php echo htmlspecialchars($qual['year']); ?>)
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
                            <div class="info-value"><?php echo htmlspecialchars($employee['state']); ?></div>
                        </div>
                        <div class="info-row">
                            <div class="info-label">Local Government Area:</div>
                            <div class="info-value"><?php echo htmlspecialchars($employee['local_govt_area']); ?></div>
                        </div>
                        <div class="info-row">
                            <div class="info-label">State of Residence:</div>
                            <div class="info-value"><?php echo htmlspecialchars($employee['state_of_residence'] ?? 'Same as Origin'); ?></div>
                        </div>
                        <div class="info-row">
                            <div class="info-label">Residential Address:</div>
                            <div class="info-value"><?php echo nl2br(htmlspecialchars($employee['residential_address'] ?? 'N/A')); ?></div>
                        </div>
                    </div>
                </div>
                
                <div class="section">
                    <div class="section-title">FINANCIAL INFORMATION</div>
                    <div class="info-grid">
                        <div class="info-row">
                            <div class="info-label">Bank Name:</div>
                            <div class="info-value"><?php echo htmlspecialchars($employee['bank_name'] ?? 'N/A'); ?></div>
                        </div>
                        <div class="info-row">
                            <div class="info-label">Bank Branch:</div>
                            <div class="info-value"><?php echo htmlspecialchars($employee['bank_branch'] ?? 'N/A'); ?></div>
                        </div>
                        <div class="info-row">
                            <div class="info-label">Account Number:</div>
                            <div class="info-value"><?php echo !empty($employee['account_number']) ? '****' . substr($employee['account_number'], -4) : 'N/A'; ?></div>
                        </div>
                        <div class="info-row">
                            <div class="info-label">NHF Number:</div>
                            <div class="info-value"><?php echo htmlspecialchars($employee['nhf_number'] ?? 'N/A'); ?></div>
                        </div>
                        <div class="info-row">
                            <div class="info-label">Pension Number:</div>
                            <div class="info-value"><?php echo htmlspecialchars($employee['pension_number'] ?? 'N/A'); ?></div>
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
                            <td><?php echo htmlspecialchars($emp['employee_number']); ?></td>
                            <td><?php echo htmlspecialchars($emp['surname'] . ', ' . $emp['first_name']); ?></td>
                            <td><?php echo htmlspecialchars($emp['pf_number'] ?? '-'); ?></td>
                            <td><?php echo htmlspecialchars($emp['sex']); ?></td>
                            <td><?php echo htmlspecialchars($emp['rank']); ?></td>
                            <td><?php echo htmlspecialchars($emp['grade_level']); ?></td>
                            <td><?php echo htmlspecialchars($emp['state']); ?></td>
                            <td><?php echo !empty($emp['date_of_birth']) ? date('d/m/Y', strtotime($emp['date_of_birth'])) : '-'; ?></td>
                            <td><?php echo !empty($emp['date_of_first_appointment']) ? date('d/m/Y', strtotime($emp['date_of_first_appointment'])) : '-'; ?></td>
                            <td><?php echo htmlspecialchars($emp['highest_qualification'] ?? '-'); ?></td>
                            <td><?php echo htmlspecialchars($emp['telephone_number'] ?? '-'); ?></td>
                            <td><?php echo htmlspecialchars($emp['email'] ?? '-'); ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                
                <div class="footer">
                    <p>Official Document - <?php echo count($employees); ?> employee(s) listed</p>
                    <p>Generated by: <?php echo htmlspecialchars($_SESSION['username'] ?? 'System'); ?> on <?php echo date('F j, Y \a\t H:i:s'); ?></p>
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
     * ============================================
     * SETTINGS & ADMIN FUNCTIONS
     * ============================================
     */
    
    /**
     * Display settings page (Super Admin only)
     */
    public function settings() {
        // Check if user is super admin
        if (!$this->data['isSuperAdmin']) {
            $this->flash('error', 'Only Super Admin can access settings.');
            $this->redirect('/admin/nominal-roll');
            return;
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
            $this->showError("Failed to load settings.");
        }
    }
    
    /**
     * Update settings (Super Admin only)
     */
    public function updateSettings() {
        // Check if user is super admin
        if (!$this->data['isSuperAdmin']) {
            $this->jsonResponse(['error' => 'Only Super Admin can update settings.']);
            return;
        }
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->jsonResponse(['error' => 'Invalid request method.']);
            return;
        }
        
        try {
            // Validate CSRF token
            $this->validateCsrf();
            
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
        // Check if user is super admin
        if (!$this->data['isSuperAdmin']) {
            $this->jsonResponse(['error' => 'Only Super Admin can update settings.']);
            return;
        }
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->jsonResponse(['error' => 'Invalid request method.']);
            return;
        }
        
        try {
            // Validate CSRF token
            $this->validateCsrf();
            
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
     * Toggle editing mode (Super Admin only)
     */
    public function toggleEditing() {
        // Check if user is super admin
        if (!$this->data['isSuperAdmin']) {
            $this->jsonResponse(['error' => 'Only Super Admin can toggle editing mode.']);
            return;
        }
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->jsonResponse(['error' => 'Invalid request method.']);
            return;
        }
        
        try {
            // Validate CSRF token
            $this->validateCsrf();
            
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
        // Check if user is super admin
        if (!$this->data['isSuperAdmin']) {
            $this->jsonResponse(['error' => 'Only Super Admin can create backups.']);
            return;
        }
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->jsonResponse(['error' => 'Invalid request method.']);
            return;
        }
        
        try {
            // Validate CSRF token
            $this->validateCsrf();
            
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
        // Check if user is super admin
        if (!$this->data['isSuperAdmin']) {
            $this->flash('error', 'Only Super Admin can restore backups.');
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
        // Check if user is super admin
        if (!$this->data['isSuperAdmin']) {
            $this->jsonResponse(['error' => 'Only Super Admin can clear activity logs.']);
            return;
        }
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->jsonResponse(['error' => 'Invalid request method.']);
            return;
        }
        
        try {
            // Validate CSRF token
            $this->validateCsrf();
            
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
        // Check if user is super admin
        if (!$this->data['isSuperAdmin']) {
            $this->jsonResponse(['error' => 'Only Super Admin can delete backups.']);
            return;
        }
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->jsonResponse(['error' => 'Invalid request method.']);
            return;
        }
        
        try {
            // Validate CSRF token
            $this->validateCsrf();
            
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
        // Check if user is super admin
        if (!$this->data['isSuperAdmin']) {
            $this->jsonResponse(['error' => 'Only Super Admin can reset settings.']);
            return;
        }
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->jsonResponse(['error' => 'Invalid request method.']);
            return;
        }
        
        try {
            // Validate CSRF token
            $this->validateCsrf();
            
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
     * Upload passport photo - VERIFIED VERSION
     */
    private function uploadPassportPhoto() {
        try {
            $file = $_FILES['passport_photo'];
            
            error_log("=== UPLOAD PASSPORT PHOTO START ===");
            error_log("File details: " . print_r($file, true));
            
            // Check for upload errors
            if ($file['error'] !== UPLOAD_ERR_OK) {
                $photoRequired = $this->model->getSetting('photo_required', '0') === '1';
                if ($photoRequired && $file['error'] === UPLOAD_ERR_NO_FILE) {
                    throw new Exception("Passport photo is required.");
                }
                error_log("Upload error code: " . $file['error']);
                return null;
            }
            
            // Get settings
            $maxSize = (int)$this->model->getSetting('passport_max_size', '2097152'); // 2MB default
            $allowedTypes = ['jpg', 'jpeg', 'png', 'gif'];
            
            // Validate file size
            if ($file['size'] > $maxSize) {
                throw new Exception("File size must be less than " . ($maxSize / 1024 / 1024) . "MB");
            }
            
            // Validate file type
            $fileExt = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
            error_log("File extension: " . $fileExt);
            
            if (!in_array($fileExt, $allowedTypes)) {
                throw new Exception("Only " . implode(', ', $allowedTypes) . " files are allowed");
            }
            
            // Validate MIME type for extra security
            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            $mimeType = finfo_file($finfo, $file['tmp_name']);
            finfo_close($finfo);
            
            $allowedMimes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif'];
            if (!in_array($mimeType, $allowedMimes)) {
                throw new Exception("Invalid file type. Only images are allowed.");
            }
            
            // Create upload directory if not exists
            $uploadDir = ROOT_PATH . '/storage/uploads/passports/';
            error_log("Upload directory: " . $uploadDir);
            
            if (!file_exists($uploadDir)) {
                error_log("Creating upload directory...");
                if (!mkdir($uploadDir, 0755, true)) {
                    throw new Exception("Failed to create upload directory");
                }
                error_log("Upload directory created successfully");
            }
            
            // Verify directory is writable
            if (!is_writable($uploadDir)) {
                throw new Exception("Upload directory is not writable");
            }
            
            // Generate unique filename
            $filename = 'passport_' . time() . '_' . uniqid() . '.' . $fileExt;
            $filePath = $uploadDir . $filename;
            
            error_log("Generated filename: " . $filename);
            error_log("Full file path: " . $filePath);
            
            // Move uploaded file
            if (!move_uploaded_file($file['tmp_name'], $filePath)) {
                throw new Exception("Failed to save uploaded file");
            }
            
            error_log("File moved successfully to: " . $filePath);
            
            // Verify file was actually created
            if (!file_exists($filePath)) {
                throw new Exception("File was not created at expected location");
            }
            
            $fileSize = filesize($filePath);
            error_log("Verified file exists, size: " . $fileSize . " bytes");
            
            // Create thumbnail
            try {
                $this->createPassportThumbnail($filePath);
                error_log("Thumbnail created successfully");
            } catch (Exception $e) {
                error_log("Thumbnail creation failed: " . $e->getMessage());
                // Don't fail upload if thumbnail fails
            }
            
            // CRITICAL: Return the relative path for database storage
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
     * Send JSON response
     */
    private function jsonResponse($data) {
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }
    
    /**
     * Show error message
     */
    private function showError($message) {
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
            echo '<p>' . htmlspecialchars($message) . '</p>';
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