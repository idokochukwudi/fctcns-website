<?php
/**
 * Application Controller
 * Handles student applications management
 * Extends the base Controller class for common functionality
 */
class ApplicationController extends Controller {
    
    private $db;
    
    /**
     * Constructor
     */
    public function __construct() {
        parent::__construct();
        
        // Set admin layout
        $this->layout = 'admin';
        
        // Require authentication first
        require_once __DIR__ . '/../middleware/AuthMiddleware.php';
        AuthMiddleware::authenticate();
        
        // Then setup database
        require_once __DIR__ . '/../config/database.php';
        $database = Database::getInstance();
        $this->db = $database->getConnection();
        
        // Initialize common data
        $this->data = array_merge($this->data, [
            'user' => $_SESSION ?? [],
            'baseUrl' => defined('BASE_URL') ? BASE_URL : '',
            'currentPage' => 'applications'
        ]);
    }
    
    /**
     * Display all applications
     */
    public function index() {
        try {
            // Get all applications
            $stmt = $this->db->query("
                SELECT a.*, 
                       (SELECT COUNT(*) FROM application_logs WHERE application_id = a.id) as log_count,
                       (SELECT status FROM application_payments WHERE application_id = a.id ORDER BY created_at DESC LIMIT 1) as payment_status
                FROM applications a 
                ORDER BY a.created_at DESC
            ");
            $applications = $stmt->fetchAll();
            
            // Get statistics
            $statsStmt = $this->db->query("
                SELECT 
                    COUNT(*) as total,
                    SUM(CASE WHEN status = 'pending' THEN 1 ELSE 0 END) as pending,
                    SUM(CASE WHEN status = 'reviewed' THEN 1 ELSE 0 END) as reviewed,
                    SUM(CASE WHEN status = 'accepted' THEN 1 ELSE 0 END) as accepted,
                    SUM(CASE WHEN status = 'rejected' THEN 1 ELSE 0 END) as rejected
                FROM applications
            ");
            $stats = $statsStmt->fetch();
            
            // Set data for view
            $this->data = array_merge($this->data, [
                'applications' => $applications,
                'stats' => $stats,
                'pageTitle' => 'Applications Management - FCT College of Nursing Sciences',
                'pageDescription' => 'Manage student applications'
            ]);
            
            // Render view
            $this->render('admin/applications/index');
            
        } catch (Exception $e) {
            error_log("ApplicationController index error: " . $e->getMessage());
            $this->showError("Failed to load applications.");
        }
    }
    
    /**
     * Display create application form
     */
    public function create() {
        $this->data = array_merge($this->data, [
            'pageTitle' => 'Create New Application - FCT College of Nursing Sciences',
            'pageDescription' => 'Create a new student application'
        ]);
        
        $this->render('admin/applications/create');
    }
    
    /**
     * Display single application
     */
    public function view($id = null) {
        if (!$id) {
            $id = $this->query('id', 0);
        }
        
        if (!$id) {
            $this->flash('error', 'Application ID is required.');
            $this->redirect('/admin/applications');
            return;
        }
        
        try {
            // Get application by ID
            $stmt = $this->db->prepare("SELECT * FROM applications WHERE id = ?");
            $stmt->execute([$id]);
            $application = $stmt->fetch();
            
            if (!$application) {
                $this->flash('error', 'Application not found.');
                $this->redirect('/admin/applications');
                return;
            }
            
            // Get application logs
            $logsStmt = $this->db->prepare("
                SELECT al.*, u.username as admin_name 
                FROM application_logs al 
                LEFT JOIN users u ON al.admin_id = u.id 
                WHERE al.application_id = ? 
                ORDER BY al.created_at DESC
            ");
            $logsStmt->execute([$id]);
            $logs = $logsStmt->fetchAll();
            
            // Get payment info
            $paymentStmt = $this->db->prepare("SELECT * FROM application_payments WHERE application_id = ? ORDER BY created_at DESC LIMIT 1");
            $paymentStmt->execute([$id]);
            $payment = $paymentStmt->fetch();
            
            // Get status history
            $statusStmt = $this->db->prepare("
                SELECT al.*, u.username as admin_name 
                FROM application_logs al 
                LEFT JOIN users u ON al.admin_id = u.id 
                WHERE al.application_id = ? AND al.old_status IS NOT NULL AND al.new_status IS NOT NULL
                ORDER BY al.created_at DESC
            ");
            $statusStmt->execute([$id]);
            $statusHistory = $statusStmt->fetchAll();
            
            // Set data for view
            $this->data = array_merge($this->data, [
                'application' => $application,
                'logs' => $logs,
                'payment' => $payment,
                'statusHistory' => $statusHistory,
                'pageTitle' => 'Application Details - ' . $application['first_name'] . ' ' . $application['last_name'],
                'pageDescription' => 'View application details'
            ]);
            
            $this->render('admin/applications/view');
            
        } catch (Exception $e) {
            error_log("ApplicationController view error: " . $e->getMessage());
            $this->showError("Failed to load application.");
        }
    }
    
    /**
     * Show single application (alias for view)
     */
    public function show($id) {
        $this->view($id);
    }
    
    /**
     * Display edit application form
     */
    public function edit($id) {
        try {
            // Get application by ID
            $stmt = $this->db->prepare("SELECT * FROM applications WHERE id = ?");
            $stmt->execute([$id]);
            $application = $stmt->fetch();
            
            if (!$application) {
                $this->flash('error', 'Application not found.');
                $this->redirect('/admin/applications');
                return;
            }
            
            // Set data for view
            $this->data = array_merge($this->data, [
                'application' => $application,
                'pageTitle' => 'Edit Application - ' . $application['first_name'] . ' ' . $application['last_name'],
                'pageDescription' => 'Edit student application'
            ]);
            
            $this->render('admin/applications/edit');
            
        } catch (Exception $e) {
            error_log("ApplicationController edit error: " . $e->getMessage());
            $this->showError("Failed to load application.");
        }
    }
    
    /**
     * Save new application
     */
    public function store() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                // Validate CSRF token
                $this->validateCsrf();
                
                $first_name = $this->input('first_name', '');
                $last_name = $this->input('last_name', '');
                $email = $this->input('email', '');
                $phone = $this->input('phone', '');
                $program = $this->input('program', '');
                $entry_year = $this->input('entry_year', date('Y'));
                $highest_qualification = $this->input('highest_qualification', '');
                $personal_statement = $this->input('personal_statement', '');
                $status = $this->input('status', 'pending');
                
                // Validate
                if (empty($first_name) || empty($last_name) || empty($email) || empty($program)) {
                    throw new Exception("First name, last name, email, and program are required.");
                }
                
                // Check if email already applied
                $checkStmt = $this->db->prepare("SELECT id FROM applications WHERE email = ?");
                $checkStmt->execute([$email]);
                if ($checkStmt->fetch()) {
                    throw new Exception("An application with this email already exists.");
                }
                
                // Prepare SQL
                $stmt = $this->db->prepare("
                    INSERT INTO applications (
                        first_name, last_name, email, phone, program, entry_year,
                        highest_qualification, personal_statement, status,
                        created_at, updated_at
                    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), NOW())
                ");
                
                // Execute
                $stmt->execute([
                    $first_name, $last_name, $email, $phone, $program, $entry_year,
                    $highest_qualification, $personal_statement, $status
                ]);
                
                // Get the new application ID
                $newAppId = $this->db->lastInsertId();
                
                // Log the creation
                $this->logApplicationAction($newAppId, 'created', 'Application created manually');
                
                // Set success message
                $this->flash('success', 'Application created successfully!');
                
                // Redirect to applications list
                $this->redirect('/admin/applications');
                
            } catch (Exception $e) {
                error_log("ApplicationController store error: " . $e->getMessage());
                
                // Set data with error for create form
                $this->data = array_merge($this->data, [
                    'pageTitle' => 'Create New Application - FCT College of Nursing Sciences',
                    'pageDescription' => 'Create a new student application',
                    'error' => $e->getMessage(),
                    'formData' => [
                        'first_name' => $this->input('first_name', ''),
                        'last_name' => $this->input('last_name', ''),
                        'email' => $this->input('email', ''),
                        'phone' => $this->input('phone', ''),
                        'program' => $this->input('program', ''),
                        'entry_year' => $this->input('entry_year', date('Y')),
                        'highest_qualification' => $this->input('highest_qualification', ''),
                        'personal_statement' => $this->input('personal_statement', ''),
                        'status' => $this->input('status', 'pending')
                    ]
                ]);
                
                $this->render('admin/applications/create');
            }
        } else {
            $this->redirect('/admin/applications/create');
        }
    }
    
    /**
     * Update application
     */
    public function update($id) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                // Validate CSRF token
                $this->validateCsrf();
                
                $first_name = $this->input('first_name', '');
                $last_name = $this->input('last_name', '');
                $email = $this->input('email', '');
                $phone = $this->input('phone', '');
                $program = $this->input('program', '');
                $entry_year = $this->input('entry_year', date('Y'));
                $highest_qualification = $this->input('highest_qualification', '');
                $personal_statement = $this->input('personal_statement', '');
                $status = $this->input('status', 'pending');
                
                // Validate
                if (empty($first_name) || empty($last_name) || empty($email) || empty($program)) {
                    throw new Exception("First name, last name, email, and program are required.");
                }
                
                // Check if email already exists (excluding current application)
                $checkStmt = $this->db->prepare("SELECT id FROM applications WHERE email = ? AND id != ?");
                $checkStmt->execute([$email, $id]);
                if ($checkStmt->fetch()) {
                    throw new Exception("An application with this email already exists.");
                }
                
                // Prepare SQL
                $stmt = $this->db->prepare("
                    UPDATE applications 
                    SET first_name = ?, last_name = ?, email = ?, phone = ?, program = ?, 
                        entry_year = ?, highest_qualification = ?, personal_statement = ?, status = ?,
                        updated_at = NOW()
                    WHERE id = ?
                ");
                
                // Execute
                $stmt->execute([
                    $first_name, $last_name, $email, $phone, $program, $entry_year,
                    $highest_qualification, $personal_statement, $status, $id
                ]);
                
                // Log the update
                $this->logApplicationAction($id, 'updated', 'Application updated');
                
                // Set success message
                $this->flash('success', 'Application updated successfully!');
                
                // Redirect to application view
                $this->redirect('/admin/applications/' . $id);
                
            } catch (Exception $e) {
                error_log("ApplicationController update error: " . $e->getMessage());
                
                // Get application data for the form
                try {
                    $stmt = $this->db->prepare("SELECT * FROM applications WHERE id = ?");
                    $stmt->execute([$id]);
                    $application = $stmt->fetch();
                    
                    $this->data = array_merge($this->data, [
                        'application' => $application,
                        'error' => $e->getMessage(),
                        'pageTitle' => 'Edit Application - ' . ($application['first_name'] ?? '') . ' ' . ($application['last_name'] ?? ''),
                        'pageDescription' => 'Edit student application'
                    ]);
                    
                    $this->render('admin/applications/edit');
                } catch (Exception $ex) {
                    $this->showError($e->getMessage());
                }
            }
        } else {
            $this->redirect('/admin/applications');
        }
    }
    
    /**
     * Delete application
     */
    public function destroy($id) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                // Validate CSRF token
                $this->validateCsrf();
                
                // Check if application exists
                $stmt = $this->db->prepare("SELECT id FROM applications WHERE id = ?");
                $stmt->execute([$id]);
                if (!$stmt->fetch()) {
                    throw new Exception("Application not found.");
                }
                
                // Delete application logs first
                $logsStmt = $this->db->prepare("DELETE FROM application_logs WHERE application_id = ?");
                $logsStmt->execute([$id]);
                
                // Delete application payments
                $paymentStmt = $this->db->prepare("DELETE FROM application_payments WHERE application_id = ?");
                $paymentStmt->execute([$id]);
                
                // Delete application
                $deleteStmt = $this->db->prepare("DELETE FROM applications WHERE id = ?");
                $deleteStmt->execute([$id]);
                
                // Log the deletion
                $this->logApplicationAction($id, 'deleted', 'Application deleted');
                
                // Set success message
                $this->flash('success', 'Application deleted successfully!');
                
                // Redirect to applications list
                $this->redirect('/admin/applications');
                
            } catch (Exception $e) {
                error_log("ApplicationController destroy error: " . $e->getMessage());
                $this->showError("Failed to delete application: " . $e->getMessage());
            }
        } else {
            $this->redirect('/admin/applications');
        }
    }
    
    /**
     * Update application status
     */
    public function updateStatus() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                // Validate CSRF token
                $this->validateCsrf();
                
                $id = $this->input('id', 0);
                $new_status = $this->input('status', '');
                $notes = $this->input('notes', '');
                $admin_id = $_SESSION['user_id'] ?? 1;
                
                if (!$id || !$new_status) {
                    throw new Exception("Application ID and status are required.");
                }
                
                // Get current status
                $stmt = $this->db->prepare("SELECT status FROM applications WHERE id = ?");
                $stmt->execute([$id]);
                $app = $stmt->fetch();
                
                if (!$app) {
                    throw new Exception("Application not found.");
                }
                
                $old_status = $app['status'];
                
                // Update application status
                $updateStmt = $this->db->prepare("
                    UPDATE applications 
                    SET status = ?, updated_at = NOW() 
                    WHERE id = ?
                ");
                $updateStmt->execute([$new_status, $id]);
                
                // Log the status change
                $logStmt = $this->db->prepare("
                    INSERT INTO application_logs (application_id, admin_id, old_status, new_status, notes, created_at)
                    VALUES (?, ?, ?, ?, ?, NOW())
                ");
                $logStmt->execute([$id, $admin_id, $old_status, $new_status, $notes]);
                
                // Also log to activity_logs
                $this->logApplicationAction($id, 'status_changed', "Status changed from {$old_status} to {$new_status}");
                
                // Set success message
                $this->flash('success', 'Application status updated successfully!');
                
                // Redirect back to application view
                $this->redirect('/admin/applications/' . $id);
                
            } catch (Exception $e) {
                error_log("ApplicationController updateStatus error: " . $e->getMessage());
                $this->showError("Failed to update application status: " . $e->getMessage());
            }
        } else {
            $this->redirect('/admin/applications');
        }
    }
    
    /**
     * Export applications to CSV
     */
    public function export() {
        try {
            // Get all applications
            $stmt = $this->db->query("
                SELECT a.*,
                       (SELECT status FROM application_payments WHERE application_id = a.id ORDER BY created_at DESC LIMIT 1) as payment_status
                FROM applications a 
                ORDER BY a.created_at DESC
            ");
            $applications = $stmt->fetchAll();
            
            // Set headers for CSV download
            header('Content-Type: text/csv; charset=utf-8');
            header('Content-Disposition: attachment; filename=applications_' . date('Y-m-d') . '.csv');
            
            // Create output stream
            $output = fopen('php://output', 'w');
            
            // Add CSV headers
            fputcsv($output, [
                'ID', 'First Name', 'Last Name', 'Email', 'Phone', 
                'Program', 'Entry Year', 'Highest Qualification',
                'Status', 'Payment Status', 'Created At', 'Updated At'
            ]);
            
            // Add data rows
            foreach ($applications as $app) {
                fputcsv($output, [
                    $app['id'],
                    $app['first_name'],
                    $app['last_name'],
                    $app['email'],
                    $app['phone'],
                    $app['program'],
                    $app['entry_year'],
                    $app['highest_qualification'],
                    $app['status'],
                    $app['payment_status'] ?? 'N/A',
                    $app['created_at'],
                    $app['updated_at']
                ]);
            }
            
            fclose($output);
            exit;
            
        } catch (Exception $e) {
            error_log("ApplicationController export error: " . $e->getMessage());
            $this->flash('error', 'Failed to export applications: ' . $e->getMessage());
            $this->redirect('/admin/applications');
        }
    }
    
    /**
     * Search applications
     */
    public function search() {
        $searchTerm = $this->query('q', '');
        
        if (empty($searchTerm)) {
            $this->redirect('/admin/applications');
            return;
        }
        
        try {
            $searchTerm = "%{$searchTerm}%";
            
            $stmt = $this->db->prepare("
                SELECT a.*, 
                       (SELECT COUNT(*) FROM application_logs WHERE application_id = a.id) as log_count,
                       (SELECT status FROM application_payments WHERE application_id = a.id ORDER BY created_at DESC LIMIT 1) as payment_status
                FROM applications a 
                WHERE a.first_name LIKE ? OR a.last_name LIKE ? OR a.email LIKE ? OR a.program LIKE ?
                ORDER BY a.created_at DESC
            ");
            $stmt->execute([$searchTerm, $searchTerm, $searchTerm, $searchTerm]);
            $applications = $stmt->fetchAll();
            
            // Set data for view
            $this->data = array_merge($this->data, [
                'applications' => $applications,
                'searchTerm' => $this->query('q', ''),
                'pageTitle' => 'Search Results - Applications',
                'pageDescription' => 'Search student applications'
            ]);
            
            $this->render('admin/applications/search');
            
        } catch (Exception $e) {
            error_log("ApplicationController search error: " . $e->getMessage());
            $this->showError("Failed to search applications.");
        }
    }
    
    /**
     * Log application action to activity_logs
     */
    private function logApplicationAction($application_id, $action, $description) {
        try {
            $user_id = $_SESSION['user_id'] ?? null;
            $ip_address = $_SERVER['REMOTE_ADDR'] ?? '';
            $user_agent = $_SERVER['HTTP_USER_AGENT'] ?? '';
            
            $stmt = $this->db->prepare("
                INSERT INTO activity_logs (user_id, action, description, table_name, record_id, ip_address, user_agent, created_at)
                VALUES (?, ?, ?, 'applications', ?, ?, ?, NOW())
            ");
            $stmt->execute([$user_id, $action, $description, $application_id, $ip_address, $user_agent]);
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
        
        // Merge with controller data
        $this->data = array_merge($this->data, $data);
        
        // Add flash messages
        $data['flash_success'] = $this->getFlash('success');
        $data['flash_error'] = $this->getFlash('error');
        
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