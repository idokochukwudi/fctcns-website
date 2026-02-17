<?php
/**
 * Admin Application Controller
 * 
 * Handles admin management of applications, settings, and payments
 * 
 * @package FCT_CNS
 */

require_once CORE_PATH . '/Controller.php';

class AdminApplicationController extends Controller {
    
    private $db;
    private $applicantModel;
    private $applicationModel;
    private $paymentModel;
    private $settingsModel;
    private $termsModel;
    private $jambModel;
    
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
        
        // Check admin permission
        if (!in_array($_SESSION['user_role'] ?? '', ['admin', 'super_admin', 'admissions_officer'])) {
            $this->flash('error', 'You do not have permission to access this page.');
            $this->redirect('/admin/dashboard');
            return;
        }
        
        // Setup database
        require_once __DIR__ . '/../config/database.php';
        $database = Database::getInstance();
        $this->db = $database->getConnection();
        
        // Load models
        require_once MODELS_PATH . '/application/ApplicantModel.php';
        require_once MODELS_PATH . '/application/ApplicationModel.php';
        require_once MODELS_PATH . '/application/PaymentModel.php';
        require_once MODELS_PATH . '/application/SettingsModel.php';
        require_once MODELS_PATH . '/application/TermsModel.php';
        require_once MODELS_PATH . '/JambCandidateModel.php';
        
        $this->applicantModel = new ApplicantModel();
        $this->applicationModel = new ApplicationModel();
        $this->paymentModel = new PaymentModel();
        $this->settingsModel = new SettingsModel();
        $this->termsModel = new TermsModel();
        $this->jambModel = new JambCandidateModel();
        
        // Initialize common data
        $this->data = array_merge($this->data, [
            'user' => $_SESSION ?? [],
            'baseUrl' => defined('BASE_URL') ? BASE_URL : '',
            'currentPage' => 'admin-applications'
        ]);
    }
    
    /**
     * Dashboard - Applications overview
     */
    public function dashboard() {
        $stats = $this->applicationModel->getStats();
        $paymentStats = $this->paymentModel->getStats();
        
        // Get recent applications
        $recentApplications = $this->db->fetchAll(
            "SELECT a.*, app.first_name, app.last_name, app.email, app.phone,
                    p.status as payment_status
             FROM applications a
             JOIN applicants app ON a.applicant_id = app.id
             LEFT JOIN application_payments p ON a.id = p.application_id AND p.status = 'success'
             ORDER BY a.created_at DESC
             LIMIT 10"
        );
        
        // Get recent payments
        $recentPayments = $this->db->fetchAll(
            "SELECT p.*, a.application_number, a.jamb_number,
                    app.first_name, app.last_name
             FROM application_payments p
             JOIN applications a ON p.application_id = a.id
             JOIN applicants app ON p.applicant_id = app.id
             ORDER BY p.created_at DESC
             LIMIT 10"
        );
        
        $this->data = array_merge($this->data, [
            'pageTitle' => 'Applications Dashboard',
            'stats' => $stats,
            'paymentStats' => $paymentStats,
            'recentApplications' => $recentApplications,
            'recentPayments' => $recentPayments
        ]);
        
        $this->render('admin/applications/dashboard');
    }
    
    /**
     * List all applications - CORRECTED VERSION
     */
    public function index() {
        $page = intval($this->query('page', 1));
        $limit = intval($this->query('limit', 20));
        $status = $this->query('status', '');
        $paymentStatus = $this->query('payment', '');
        $search = $this->query('search', '');
        
        $offset = ($page - 1) * $limit;
        
        $filters = [
            'status' => $status,
            'payment_status' => $paymentStatus,
            'search' => $search
        ];
        
        // Get all applications with filters
        $applications = $this->applicationModel->getAllWithPaymentStatus($filters);
        
        // Calculate pagination
        $total = count($applications);
        $applications = array_slice($applications, $offset, $limit);
        $totalPages = ceil($total / $limit);
        
        // Build pagination array for view
        $pagination = [
            'page' => $page,
            'limit' => $limit,
            'total' => $total,
            'totalPages' => $totalPages,
            'status' => $status,
            'payment' => $paymentStatus,
            'search' => $search
        ];
        
        $this->data = array_merge($this->data, [
            'pageTitle' => 'Manage Applications',
            'applications' => $applications,
            'pagination' => $pagination  // Make sure this is set
        ]);
        
        $this->render('admin/applications/index');
    }
    
    /**
     * View single application
     */
    public function view($id) {
        $application = $this->applicationModel->getFullApplication($id);
        
        if (!$application) {
            $this->flash('error', 'Application not found.');
            $this->redirect('/admin/applications');
            return;
        }
        
        // Get activity logs
        $logs = $this->db->fetchAll(
            "SELECT * FROM application_activity_logs 
             WHERE application_id = :id OR applicant_id = :applicant_id
             ORDER BY created_at DESC",
            ['id' => $id, 'applicant_id' => $application['applicant_id']]
        );
        
        $this->data = array_merge($this->data, [
            'pageTitle' => 'Application Details - ' . $application['application_number'],
            'application' => $application,
            'logs' => $logs
        ]);
        
        $this->render('admin/applications/view');
    }
    
    /**
     * Edit application
     */
    public function edit($id) {
        $application = $this->applicationModel->getFullApplication($id);
        
        if (!$application) {
            $this->flash('error', 'Application not found.');
            $this->redirect('/admin/applications');
            return;
        }
        
        $this->data = array_merge($this->data, [
            'pageTitle' => 'Edit Application - ' . $application['application_number'],
            'application' => $application,
            'states' => $this->getStates(),
            'programs' => $this->getPrograms(),
            'statusOptions' => [
                'pending' => 'Pending',
                'reviewed' => 'Reviewed',
                'accepted' => 'Accepted',
                'rejected' => 'Rejected',
                'waitlisted' => 'Waitlisted'
            ]
        ]);
        
        $this->render('admin/applications/edit');
    }
    
    /**
     * Update application
     */
    public function update($id) {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/admin/applications');
            return;
        }
        
        // Validate CSRF
        if (!$this->validateCsrfToken()) {
            $this->flash('error', 'Security token expired. Please try again.');
            $this->redirect('/admin/applications/edit/' . $id);
            return;
        }
        
        $application = $this->applicationModel->find($id);
        
        if (!$application) {
            $this->flash('error', 'Application not found.');
            $this->redirect('/admin/applications');
            return;
        }
        
        try {
            $updateData = [
                'first_name' => $this->input('first_name'),
                'last_name' => $this->input('last_name'),
                'other_names' => $this->input('other_names'),
                'email' => $this->input('email'),
                'phone' => $this->input('phone'),
                'date_of_birth' => $this->input('date_of_birth'),
                'gender' => $this->input('gender'),
                'state_of_origin' => $this->input('state_of_origin'),
                'lga' => $this->input('lga'),
                'address' => $this->input('address'),
                'program_choice_1' => $this->input('program_choice_1'),
                'program_choice_2' => $this->input('program_choice_2'),
                'program_choice_3' => $this->input('program_choice_3'),
                'status' => $this->input('status'),
                'notes' => $this->input('notes')
            ];
            
            $this->applicationModel->update($updateData, 'id = :id', ['id' => $id]);
            
            // Update applicant email/phone if changed
            if ($application['applicant_id']) {
                $this->applicantModel->update(
                    [
                        'email' => $this->input('email'),
                        'phone' => $this->input('phone')
                    ],
                    'id = :id',
                    ['id' => $application['applicant_id']]
                );
            }
            
            // Log activity
            $this->logAdminAction('application_updated', "Updated application #{$id}");
            
            $this->flash('success', 'Application updated successfully.');
            $this->redirect('/admin/applications/view/' . $id);
            
        } catch (Exception $e) {
            error_log("AdminApplicationController::update - Error: " . $e->getMessage());
            $this->flash('error', 'Failed to update application.');
            $this->redirect('/admin/applications/edit/' . $id);
        }
    }
    
    /**
     * Update application status
     */
    public function updateStatus() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/admin/applications');
            return;
        }
        
        // Validate CSRF
        if (!$this->validateCsrfToken()) {
            $this->flash('error', 'Security token expired. Please try again.');
            $this->redirect('/admin/applications');
            return;
        }
        
        $id = $this->input('id');
        $status = $this->input('status');
        $notes = $this->input('notes', '');
        
        if (empty($id) || empty($status)) {
            $this->flash('error', 'Invalid request.');
            $this->redirect('/admin/applications');
            return;
        }
        
        $application = $this->applicationModel->find($id);
        
        if (!$application) {
            $this->flash('error', 'Application not found.');
            $this->redirect('/admin/applications');
            return;
        }
        
        try {
            $oldStatus = $application['status'];
            
            $this->applicationModel->update(
                ['status' => $status, 'notes' => $notes],
                'id = :id',
                ['id' => $id]
            );
            
            // Log status change
            $this->logAdminAction(
                'status_changed',
                "Application #{$id} status changed from {$oldStatus} to {$status}"
            );
            
            // Log in application activity
            $this->applicantModel->logActivity(
                $application['applicant_id'],
                $id,
                'status_changed',
                "Status changed from {$oldStatus} to {$status} by admin",
                ['old_status' => $oldStatus],
                ['new_status' => $status]
            );
            
            // Send email notification if needed
            if (in_array($status, ['accepted', 'rejected'])) {
                $this->sendStatusNotification($application, $status, $notes);
            }
            
            $this->flash('success', 'Application status updated successfully.');
            $this->redirect('/admin/applications/view/' . $id);
            
        } catch (Exception $e) {
            error_log("AdminApplicationController::updateStatus - Error: " . $e->getMessage());
            $this->flash('error', 'Failed to update status.');
            $this->redirect('/admin/applications/view/' . $id);
        }
    }
    
    /**
     * Manage settings
     */
    public function settings() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Validate CSRF
            if (!$this->validateCsrfToken()) {
                $this->flash('error', 'Security token expired. Please try again.');
                $this->redirect('/admin/applications/settings');
                return;
            }
            
            try {
                // Update general settings
                $generalSettings = [
                    'application_fee' => $this->input('application_fee'),
                    'application_currency' => $this->input('application_currency'),
                    'application_start_date' => $this->input('application_start_date'),
                    'application_end_date' => $this->input('application_end_date'),
                    'cbt_start_date' => $this->input('cbt_start_date'),
                    'cbt_end_date' => $this->input('cbt_end_date'),
                    'min_utme_score' => $this->input('min_utme_score'),
                    'min_age' => $this->input('min_age'),
                    'max_olevel_sittings' => $this->input('max_olevel_sittings'),
                    'portal_status' => $this->input('portal_status'),
                    'portal_message' => $this->input('portal_message')
                ];
                
                foreach ($generalSettings as $key => $value) {
                    $this->settingsModel->set($key, $value);
                }
                
                // Update support settings
                $supportSettings = [
                    'support_phone_1' => $this->input('support_phone_1'),
                    'support_phone_2' => $this->input('support_phone_2'),
                    'support_whatsapp' => $this->input('support_whatsapp'),
                    'support_email' => $this->input('support_email'),
                    'support_hours' => $this->input('support_hours'),
                    'institution_address' => $this->input('institution_address'),
                    'office_hours' => $this->input('office_hours')
                ];
                
                foreach ($supportSettings as $key => $value) {
                    $this->settingsModel->set($key, $value);
                }
                
                $this->logAdminAction('settings_updated', 'Application settings updated');
                
                $this->flash('success', 'Settings updated successfully.');
                $this->redirect('/admin/applications/settings');
                
            } catch (Exception $e) {
                error_log("AdminApplicationController::settings - Error: " . $e->getMessage());
                $this->flash('error', 'Failed to update settings.');
                $this->redirect('/admin/applications/settings');
            }
        }
        
        $settings = $this->settingsModel->getAllSettings();
        
        $this->data = array_merge($this->data, [
            'pageTitle' => 'Application Settings',
            'settings' => $settings
        ]);
        
        $this->render('admin/applications/settings');
    }
    
    /**
     * Manage terms and conditions
     */
    public function terms() {
        $terms = $this->termsModel->getAllVersions();
        
        $this->data = array_merge($this->data, [
            'pageTitle' => 'Manage Terms and Conditions',
            'terms' => $terms
        ]);
        
        $this->render('admin/applications/terms');
    }
    
    /**
     * Create new terms version
     */
    public function createTerms() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/admin/applications/terms');
            return;
        }
        
        // Validate CSRF
        if (!$this->validateCsrfToken()) {
            $this->flash('error', 'Security token expired. Please try again.');
            $this->redirect('/admin/applications/terms');
            return;
        }
        
        $title = $this->input('title');
        $content = $this->input('content');
        $effectiveDate = $this->input('effective_date');
        $version = $this->input('version');
        
        if (empty($title) || empty($content) || empty($effectiveDate)) {
            $this->flash('error', 'All fields are required.');
            $this->redirect('/admin/applications/terms');
            return;
        }
        
        try {
            $this->termsModel->createVersion(
                $title,
                $content,
                $effectiveDate,
                $_SESSION['user_id'] ?? 1,
                $version
            );
            
            $this->logAdminAction('terms_created', 'New terms and conditions created');
            
            $this->flash('success', 'Terms and conditions created successfully.');
            $this->redirect('/admin/applications/terms');
            
        } catch (Exception $e) {
            error_log("AdminApplicationController::createTerms - Error: " . $e->getMessage());
            $this->flash('error', 'Failed to create terms.');
            $this->redirect('/admin/applications/terms');
        }
    }
    
    /**
     * Edit terms
     */
    public function editTerms($id) {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/admin/applications/terms');
            return;
        }
        
        // Validate CSRF
        if (!$this->validateCsrfToken()) {
            $this->flash('error', 'Security token expired. Please try again.');
            $this->redirect('/admin/applications/terms');
            return;
        }
        
        $title = $this->input('title');
        $content = $this->input('content');
        $effectiveDate = $this->input('effective_date');
        $isActive = $this->input('is_active') ? 1 : 0;
        
        if (empty($title) || empty($content) || empty($effectiveDate)) {
            $this->flash('error', 'All fields are required.');
            $this->redirect('/admin/applications/terms');
            return;
        }
        
        try {
            $updateData = [
                'title' => $title,
                'content' => $content,
                'effective_date' => $effectiveDate,
                'is_active' => $isActive
            ];
            
            $this->termsModel->updateTerms($id, $updateData, $_SESSION['user_id'] ?? 1);
            
            $this->logAdminAction('terms_updated', 'Terms and conditions updated');
            
            $this->flash('success', 'Terms and conditions updated successfully.');
            $this->redirect('/admin/applications/terms');
            
        } catch (Exception $e) {
            error_log("AdminApplicationController::editTerms - Error: " . $e->getMessage());
            $this->flash('error', 'Failed to update terms.');
            $this->redirect('/admin/applications/terms');
        }
    }
    
    /**
     * Activate terms
     */
    public function activateTerms($id) {
        // Validate CSRF
        if (!$this->validateCsrfToken()) {
            $this->flash('error', 'Security token expired. Please try again.');
            $this->redirect('/admin/applications/terms');
            return;
        }
        
        try {
            $this->termsModel->activate($id);
            
            $this->logAdminAction('terms_activated', 'Terms and conditions activated');
            
            $this->flash('success', 'Terms activated successfully.');
            $this->redirect('/admin/applications/terms');
            
        } catch (Exception $e) {
            error_log("AdminApplicationController::activateTerms - Error: " . $e->getMessage());
            $this->flash('error', 'Failed to activate terms.');
            $this->redirect('/admin/applications/terms');
        }
    }
    
    /**
     * JAMB import page
     */
    public function jambImport() {
        // Get recent imports using the jambModel
        $imports = $this->db->fetchAll(
            "SELECT * FROM jamb_import_logs ORDER BY created_at DESC LIMIT 20"
        );
        
        // Get stats using the jambModel
        $stats = $this->db->fetchOne(
            "SELECT 
                COUNT(*) as total,
                SUM(is_used) as used,
                SUM(CASE WHEN is_imported = 1 THEN 1 ELSE 0 END) as imported
             FROM jamb_candidates"
        );
        
        $this->data = array_merge($this->data, [
            'pageTitle' => 'Import JAMB Data',
            'imports' => $imports,
            'stats' => $stats
        ]);
        
        $this->render('admin/applications/jamb-import');
    }
    
    /**
     * Process JAMB import
     */
    public function processJambImport() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/admin/applications/jamb-import');
            return;
        }
        
        // Validate CSRF
        if (!$this->validateCsrfToken()) {
            $this->flash('error', 'Security token expired. Please try again.');
            $this->redirect('/admin/applications/jamb-import');
            return;
        }
        
        // Check file upload
        if (!isset($_FILES['jamb_file']) || $_FILES['jamb_file']['error'] !== UPLOAD_ERR_OK) {
            $this->flash('error', 'Please upload a valid file.');
            $this->redirect('/admin/applications/jamb-import');
            return;
        }
        
        $file = $_FILES['jamb_file'];
        
        // Check file type
        $extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        if (!in_array($extension, ['csv', 'txt', 'json'])) {
            $this->flash('error', 'Please upload a CSV, TXT, or JSON file.');
            $this->redirect('/admin/applications/jamb-import');
            return;
        }
        
        // Check file size (max 5MB)
        if ($file['size'] > 5 * 1024 * 1024) {
            $this->flash('error', 'File size exceeds 5MB limit.');
            $this->redirect('/admin/applications/jamb-import');
            return;
        }
        
        try {
            $results = [
                'total' => 0,
                'success' => 0,
                'failed' => 0,
                'errors' => []
            ];
            
            if ($extension === 'json') {
                $results = $this->importJambJson($file['tmp_name']);
            } else {
                $results = $this->importJambCsv($file['tmp_name']);
            }
            
            // Log import using direct database insert
            $this->logJambImport(
                $file['name'],
                $results['total'],
                $results['success'],
                $results['failed'],
                $results['errors']
            );
            
            if ($results['failed'] > 0) {
                $this->flash('warning', "Import completed with errors: {$results['success']} imported, {$results['failed']} failed.");
            } else {
                $this->flash('success', "Import completed successfully: {$results['success']} records imported.");
            }
            
            $this->redirect('/admin/applications/jamb-import');
            
        } catch (Exception $e) {
            error_log("AdminApplicationController::processJambImport - Error: " . $e->getMessage());
            $this->flash('error', 'Failed to import file: ' . $e->getMessage());
            $this->redirect('/admin/applications/jamb-import');
        }
    }
    
    /**
     * Import JAMB CSV - UPDATED to match template format
     */
    private function importJambCsv($filePath) {
        $handle = fopen($filePath, 'r');
        
        if (!$handle) {
            throw new Exception("Failed to open file");
        }
        
        // Get headers
        $headers = fgetcsv($handle);
        
        if (!$headers) {
            fclose($handle);
            throw new Exception("Empty or invalid CSV file");
        }
        
        // Map your template headers to database fields
        $fieldMap = [
            'jambId' => 'jamb_number',
            'lastName' => 'last_name',
            'firstName' => 'first_name',
            'otherNames' => 'other_names',
            'gender' => 'gender',
            'state' => 'state_of_origin',
            'lga' => 'lga',
            'aggregateScore' => 'aggregate_score'
        ];
        
        $results = [
            'total' => 0,
            'success' => 0,
            'failed' => 0,
            'errors' => []
        ];
        
        $rowNumber = 1;
        while (($data = fgetcsv($handle)) !== false) {
            $rowNumber++;
            $results['total']++;
            
            try {
                if (count($data) < count($headers)) {
                    throw new Exception("Incomplete data");
                }
                
                $row = array_combine($headers, $data);
                
                // Check required fields
                if (empty($row['jambId'])) {
                    throw new Exception("Missing JAMB ID");
                }
                
                if (empty($row['lastName'])) {
                    throw new Exception("Missing last name");
                }
                
                if (empty($row['firstName'])) {
                    throw new Exception("Missing first name");
                }
                
                // Check if already exists
                $existing = $this->jambModel->findByJambNumber($row['jambId']);
                if ($existing) {
                    $results['failed']++;
                    $results['errors'][] = "Row {$rowNumber}: JAMB number {$row['jambId']} already exists";
                    continue;
                }
                
                // Map data to database fields
                $candidateData = [
                    'jamb_number' => $row['jambId'],
                    'last_name' => $row['lastName'],
                    'first_name' => $row['firstName'],
                    'other_names' => $row['otherNames'] ?? null,
                    'gender' => strtoupper(substr($row['gender'] ?? 'M', 0, 1)),
                    'state_of_origin' => $row['state'] ?? '',
                    'lga' => $row['lga'] ?? '',
                    'aggregate_score' => intval($row['aggregateScore'] ?? 0),
                    'program_applied' => 'ND Nursing', // Default program
                    'institution' => 'FCT College of Nursing Sciences',
                    'email' => null, // Not provided in template
                    'phone' => null, // Not provided in template
                    'date_of_birth' => null, // Not provided in template
                    'exam_year' => 2025,
                    'is_imported' => 1,
                    'is_used' => 0,
                    'created_at' => date('Y-m-d H:i:s')
                ];
                
                $this->jambModel->insert($candidateData);
                $results['success']++;
                
            } catch (Exception $e) {
                $results['failed']++;
                $results['errors'][] = "Row {$rowNumber}: " . $e->getMessage();
            }
        }
        
        fclose($handle);
        return $results;
    }
    
    /**
     * Import JAMB JSON - UPDATED to match template format
     */
    private function importJambJson($filePath) {
        $content = file_get_contents($filePath);
        $data = json_decode($content, true);
        
        if (!is_array($data)) {
            throw new Exception("Invalid JSON format");
        }
        
        $fieldMap = [
            'jambId' => 'jamb_number',
            'lastName' => 'last_name',
            'firstName' => 'first_name',
            'otherNames' => 'other_names',
            'gender' => 'gender',
            'state' => 'state_of_origin',
            'lga' => 'lga',
            'aggregateScore' => 'aggregate_score'
        ];
        
        $results = [
            'total' => count($data),
            'success' => 0,
            'failed' => 0,
            'errors' => []
        ];
        
        foreach ($data as $index => $row) {
            try {
                if (empty($row['jambId'])) {
                    throw new Exception("Missing JAMB ID");
                }
                
                // Check if already exists
                $existing = $this->jambModel->findByJambNumber($row['jambId']);
                if ($existing) {
                    $results['failed']++;
                    $results['errors'][] = "Row " . ($index + 1) . ": JAMB number already exists";
                    continue;
                }
                
                $candidateData = [
                    'jamb_number' => $row['jambId'],
                    'last_name' => $row['lastName'] ?? '',
                    'first_name' => $row['firstName'] ?? '',
                    'other_names' => $row['otherNames'] ?? null,
                    'gender' => strtoupper(substr($row['gender'] ?? 'M', 0, 1)),
                    'state_of_origin' => $row['state'] ?? '',
                    'lga' => $row['lga'] ?? '',
                    'aggregate_score' => intval($row['aggregateScore'] ?? 0),
                    'program_applied' => 'ND Nursing',
                    'institution' => 'FCT College of Nursing Sciences',
                    'email' => null,
                    'phone' => null,
                    'date_of_birth' => null,
                    'exam_year' => 2025,
                    'is_imported' => 1,
                    'is_used' => 0,
                    'created_at' => date('Y-m-d H:i:s')
                ];
                
                $this->jambModel->insert($candidateData);
                $results['success']++;
                
            } catch (Exception $e) {
                $results['failed']++;
                $results['errors'][] = "Row " . ($index + 1) . ": " . $e->getMessage();
            }
        }
        
        return $results;
    }
    
    /**
     * Log JAMB import to database
     */
    private function logJambImport($filename, $total, $success, $failed, $errors) {
        try {
            $stmt = $this->db->prepare("
                INSERT INTO jamb_import_logs 
                (filename, total_records, successful_imports, failed_imports, import_date, imported_by, log_details, created_at)
                VALUES (:filename, :total, :success, :failed, NOW(), :imported_by, :log_details, NOW())
            ");
            
            $stmt->execute([
                'filename' => $filename,
                'total' => $total,
                'success' => $success,
                'failed' => $failed,
                'imported_by' => $_SESSION['username'] ?? 'Admin',
                'log_details' => json_encode($errors)
            ]);
        } catch (Exception $e) {
            error_log("Failed to log JAMB import: " . $e->getMessage());
        }
    }
    
    /**
     * Download JAMB template - UPDATED to match your format
     */
    public function downloadJambTemplate() {
        $headers = [
            'jambId',
            'lastName',
            'firstName',
            'otherNames',
            'gender',
            'state',
            'lga',
            'aggregateScore'
        ];
        
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="jamb_import_template.csv"');
        
        $output = fopen('php://output', 'w');
        fputcsv($output, $headers);
        
        // Sample row
        fputcsv($output, [
            '202550805685FF',
            'Omoleye',
            'Enoch',
            'Gbemisoke',
            'M',
            'Ekiti',
            'Ikole',
            '299'
        ]);
        
        fclose($output);
        exit;
    }
    
    /**
     * Payments list
     */
    public function payments() {
        $page = intval($this->query('page', 1));
        $limit = intval($this->query('limit', 20));
        $status = $this->query('status', '');
        $search = $this->query('search', '');
        
        $offset = ($page - 1) * $limit;
        
        $sql = "SELECT p.*, a.application_number, a.jamb_number,
                       app.first_name, app.last_name, app.email, app.phone
                FROM application_payments p
                JOIN applications a ON p.application_id = a.id
                JOIN applicants app ON p.applicant_id = app.id
                WHERE 1=1";
        
        $params = [];
        
        if (!empty($status)) {
            $sql .= " AND p.status = :status";
            $params['status'] = $status;
        }
        
        if (!empty($search)) {
            $sql .= " AND (p.rrr LIKE :search OR p.reference LIKE :search OR p.order_id LIKE :search 
                        OR a.application_number LIKE :search OR a.jamb_number LIKE :search
                        OR app.first_name LIKE :search OR app.last_name LIKE :search)";
            $params['search'] = '%' . $search . '%';
        }
        
        $sql .= " ORDER BY p.created_at DESC LIMIT :offset, :limit";
        
        $params['offset'] = $offset;
        $params['limit'] = $limit;
        
        $payments = $this->db->fetchAll($sql, $params);
        
        // Get total count
        $countSql = "SELECT COUNT(*) as total FROM application_payments p";
        $countParams = [];
        
        if (!empty($status) || !empty($search)) {
            $countSql .= " WHERE 1=1";
            if (!empty($status)) {
                $countSql .= " AND p.status = :status";
                $countParams['status'] = $status;
            }
            if (!empty($search)) {
                $countSql .= " AND (p.rrr LIKE :search OR p.reference LIKE :search)";
                $countParams['search'] = '%' . $search . '%';
            }
        }
        
        $total = $this->db->fetchOne($countSql, $countParams)['total'];
        $totalPages = ceil($total / $limit);
        
        $stats = $this->paymentModel->getStats();
        
        $this->data = array_merge($this->data, [
            'pageTitle' => 'Payment Transactions',
            'payments' => $payments,
            'stats' => $stats,
            'pagination' => [
                'page' => $page,
                'limit' => $limit,
                'total' => $total,
                'totalPages' => $totalPages,
                'status' => $status,
                'search' => $search
            ]
        ]);
        
        $this->render('admin/applications/payments');
    }
    
    /**
     * View payment details
     */
    public function viewPayment($id) {
        $payment = $this->db->fetchOne(
            "SELECT p.*, a.application_number, a.jamb_number,
                    app.first_name, app.last_name, app.email, app.phone,
                    r.*
             FROM application_payments p
             JOIN applications a ON p.application_id = a.id
             JOIN applicants app ON p.applicant_id = app.id
             LEFT JOIN remita_transactions r ON p.id = r.payment_id
             WHERE p.id = :id",
            ['id' => $id]
        );
        
        if (!$payment) {
            $this->flash('error', 'Payment not found.');
            $this->redirect('/admin/applications/payments');
            return;
        }
        
        $this->data = array_merge($this->data, [
            'pageTitle' => 'Payment Details - ' . $payment['rrr'],
            'payment' => $payment
        ]);
        
        $this->render('admin/applications/view-payment');
    }
    
    /**
     * Export applications
     */
    public function export() {
        $format = $this->query('format', 'csv');
        $status = $this->query('status', '');
        $paymentStatus = $this->query('payment', '');
        
        $filters = [
            'status' => $status,
            'payment_status' => $paymentStatus
        ];
        
        $applications = $this->applicationModel->getAllWithPaymentStatus($filters);
        
        if ($format === 'csv') {
            $this->exportApplicationsCsv($applications);
        } else {
            $this->exportApplicationsExcel($applications);
        }
    }
    
    /**
     * Export applications as CSV
     */
    private function exportApplicationsCsv($applications) {
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="applications_' . date('Y-m-d') . '.csv"');
        
        $output = fopen('php://output', 'w');
        
        // Headers
        fputcsv($output, [
            'Application Number',
            'JAMB Number',
            'First Name',
            'Last Name',
            'Other Names',
            'Email',
            'Phone',
            'Date of Birth',
            'Gender',
            'State of Origin',
            'LGA',
            'Program Choice 1',
            'Program Choice 2',
            'Program Choice 3',
            'UTME Score',
            'Status',
            'Payment Status',
            'Payment Amount',
            'Payment Date',
            'Submitted At',
            'Created At'
        ]);
        
        // Data
        foreach ($applications as $app) {
            fputcsv($output, [
                $app['application_number'],
                $app['jamb_number'],
                $app['first_name'],
                $app['last_name'],
                $app['other_names'],
                $app['email'],
                $app['phone'],
                $app['date_of_birth'],
                $app['gender'],
                $app['state_of_origin'],
                $app['lga'],
                $app['program_choice_1'],
                $app['program_choice_2'],
                $app['program_choice_3'],
                $app['utme_score'],
                $app['status'],
                $app['payment_status'] ?? 'unpaid',
                $app['amount'] ?? '',
                $app['payment_date'] ?? '',
                $app['submitted_at'],
                $app['created_at']
            ]);
        }
        
        fclose($output);
        exit;
    }
    
    /**
     * Export applications as Excel
     */
    private function exportApplicationsExcel($applications) {
        // Simple HTML table export
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment; filename="applications_' . date('Y-m-d') . '.xls"');
        
        echo '<table border="1">';
        echo '<tr>';
        echo '<th>Application Number</th>';
        echo '<th>JAMB Number</th>';
        echo '<th>First Name</th>';
        echo '<th>Last Name</th>';
        echo '<th>Other Names</th>';
        echo '<th>Email</th>';
        echo '<th>Phone</th>';
        echo '<th>Date of Birth</th>';
        echo '<th>Gender</th>';
        echo '<th>State of Origin</th>';
        echo '<th>LGA</th>';
        echo '<th>Program Choice 1</th>';
        echo '<th>Program Choice 2</th>';
        echo '<th>Program Choice 3</th>';
        echo '<th>UTME Score</th>';
        echo '<th>Status</th>';
        echo '<th>Payment Status</th>';
        echo '<th>Payment Amount</th>';
        echo '<th>Payment Date</th>';
        echo '<th>Submitted At</th>';
        echo '<th>Created At</th>';
        echo '</tr>';
        
        foreach ($applications as $app) {
            echo '<tr>';
            echo '<td>' . htmlspecialchars($app['application_number']) . '</td>';
            echo '<td>' . htmlspecialchars($app['jamb_number']) . '</td>';
            echo '<td>' . htmlspecialchars($app['first_name']) . '</td>';
            echo '<td>' . htmlspecialchars($app['last_name']) . '</td>';
            echo '<td>' . htmlspecialchars($app['other_names']) . '</td>';
            echo '<td>' . htmlspecialchars($app['email']) . '</td>';
            echo '<td>' . htmlspecialchars($app['phone']) . '</td>';
            echo '<td>' . htmlspecialchars($app['date_of_birth']) . '</td>';
            echo '<td>' . htmlspecialchars($app['gender']) . '</td>';
            echo '<td>' . htmlspecialchars($app['state_of_origin']) . '</td>';
            echo '<td>' . htmlspecialchars($app['lga']) . '</td>';
            echo '<td>' . htmlspecialchars($app['program_choice_1']) . '</td>';
            echo '<td>' . htmlspecialchars($app['program_choice_2']) . '</td>';
            echo '<td>' . htmlspecialchars($app['program_choice_3']) . '</td>';
            echo '<td>' . htmlspecialchars($app['utme_score']) . '</td>';
            echo '<td>' . htmlspecialchars($app['status']) . '</td>';
            echo '<td>' . htmlspecialchars($app['payment_status'] ?? 'unpaid') . '</td>';
            echo '<td>' . htmlspecialchars($app['amount'] ?? '') . '</td>';
            echo '<td>' . htmlspecialchars($app['payment_date'] ?? '') . '</td>';
            echo '<td>' . htmlspecialchars($app['submitted_at']) . '</td>';
            echo '<td>' . htmlspecialchars($app['created_at']) . '</td>';
            echo '</tr>';
        }
        
        echo '</table>';
        exit;
    }
    
    /**
     * Send status notification email
     */
    private function sendStatusNotification($application, $status, $notes) {
        // Implement email sending
        // Use EmailHelper
    }
    
    /**
     * Log admin action
     */
    private function logAdminAction($action, $description) {
        try {
            $stmt = $this->db->prepare("
                INSERT INTO activity_logs (user_id, action, description, ip_address, user_agent, created_at)
                VALUES (:user_id, :action, :description, :ip_address, :user_agent, NOW())
            ");
            
            $stmt->execute([
                'user_id' => $_SESSION['user_id'] ?? null,
                'action' => $action,
                'description' => $description,
                'ip_address' => $_SERVER['REMOTE_ADDR'] ?? '',
                'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? ''
            ]);
        } catch (Exception $e) {
            error_log("Failed to log admin action: " . $e->getMessage());
        }
    }
    
    /**
     * Get states
     */
    private function getStates() {
        return [
            'Abia', 'Adamawa', 'Akwa Ibom', 'Anambra', 'Bauchi', 'Bayelsa', 'Benue',
            'Borno', 'Cross River', 'Delta', 'Ebonyi', 'Edo', 'Ekiti', 'Enugu',
            'FCT - Abuja', 'Gombe', 'Imo', 'Jigawa', 'Kaduna', 'Kano', 'Katsina',
            'Kebbi', 'Kogi', 'Kwara', 'Lagos', 'Nasarawa', 'Niger', 'Ogun', 'Ondo',
            'Osun', 'Oyo', 'Plateau', 'Rivers', 'Sokoto', 'Taraba', 'Yobe', 'Zamfara'
        ];
    }
    
    /**
     * Get programs
     */
    private function getPrograms() {
        return [
            'ND Nursing',
            'HND Nursing',
            'ND/HND Nursing (Non-terminal)',
            'Post-Basic Nursing',
            'Midwifery'
        ];
    }
    
    /**
     * Generate CSRF token
     */
    protected function csrfToken() {
        if (!isset($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['csrf_token'];
    }
    
    /**
     * Validate CSRF token
     */
    protected function validateCsrfToken($token = null) {
        if ($token === null) {
            $token = $_POST['csrf_token'] ?? $_SERVER['HTTP_X_CSRF_TOKEN'] ?? '';
        }
        
        return hash_equals($_SESSION['csrf_token'] ?? '', $token);
    }
    
    /**
     * Override render method
     */
    protected function render($view = null, $data = []) {
        // Add CSRF token
        $data['csrf_token'] = $this->csrfToken();
        
        // Add flash messages
        $data['flash_success'] = $this->getFlash('success');
        $data['flash_error'] = $this->getFlash('error');
        $data['flash_info'] = $this->getFlash('info');
        
        // Merge with controller data
        $this->data = array_merge($this->data, $data);
        
        parent::render($view);
    }
    
    /**
     * Get flash message
     */
    private function getFlash($type) {
        if (isset($_SESSION['flash_' . $type])) {
            $message = $_SESSION['flash_' . $type];
            unset($_SESSION['flash_' . $type]);
            return $message;
        }
        return null;
    }
    
    /**
     * Input helper
     */
    protected function input($key, $default = '') {
        return $_POST[$key] ?? $_GET[$key] ?? $default;
    }
    
    /**
     * Query helper
     */
    protected function query($key, $default = '') {
        return $_GET[$key] ?? $default;
    }
}