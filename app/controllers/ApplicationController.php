<?php
/**
 * Application Controller
 * Handles student applications management
 */
class ApplicationController {
    
    private $db;
    
    public function __construct() {
        // Require authentication first
        require_once __DIR__ . '/../middleware/AuthMiddleware.php';
        AuthMiddleware::authenticate();
        
        // Then setup database
        require_once __DIR__ . '/../config/database.php';
        $database = Database::getInstance();
        $this->db = $database->getConnection();
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
            
            // Load view with data
            $this->loadView('admin/applications', [
                'applications' => $applications,
                'stats' => $stats
            ]);
            
        } catch (Exception $e) {
            error_log("ApplicationController index error: " . $e->getMessage());
            $this->showError("Failed to load applications.");
        }
    }
    
    /**
     * Display create application form
     */
    public function create() {
        $this->loadView('admin/applications_create', []);
    }
    
    /**
     * Display single application
     */
    public function view($id = null) {
        if (!$id) {
            $id = $_GET['id'] ?? 0;
        }
        
        if (!$id) {
            $this->showError("Application ID is required.");
            return;
        }
        
        try {
            // Get application by ID
            $stmt = $this->db->prepare("SELECT * FROM applications WHERE id = ?");
            $stmt->execute([$id]);
            $application = $stmt->fetch();
            
            if (!$application) {
                $this->showError("Application not found.");
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
            
            // Load view with data
            $this->loadView('admin/applications_view', [
                'application' => $application,
                'logs' => $logs,
                'payment' => $payment
            ]);
            
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
                $this->showError("Application not found.");
                return;
            }
            
            $this->loadView('admin/applications_edit', [
                'application' => $application
            ]);
            
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
                $first_name = $_POST['first_name'] ?? '';
                $last_name = $_POST['last_name'] ?? '';
                $email = $_POST['email'] ?? '';
                $phone = $_POST['phone'] ?? '';
                $program = $_POST['program'] ?? '';
                $entry_year = $_POST['entry_year'] ?? date('Y');
                $highest_qualification = $_POST['highest_qualification'] ?? '';
                $personal_statement = $_POST['personal_statement'] ?? '';
                $status = $_POST['status'] ?? 'pending';
                
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
                
                // Redirect to applications list
                header("Location: " . BASE_URL . "/admin/applications");
                exit;
                
            } catch (Exception $e) {
                error_log("ApplicationController store error: " . $e->getMessage());
                $this->loadView('admin/applications_create', ['error' => $e->getMessage()]);
            }
        } else {
            header("Location: " . BASE_URL . "/admin/applications/create");
            exit;
        }
    }
    
    /**
     * Update application
     */
    public function update($id) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                $first_name = $_POST['first_name'] ?? '';
                $last_name = $_POST['last_name'] ?? '';
                $email = $_POST['email'] ?? '';
                $phone = $_POST['phone'] ?? '';
                $program = $_POST['program'] ?? '';
                $entry_year = $_POST['entry_year'] ?? date('Y');
                $highest_qualification = $_POST['highest_qualification'] ?? '';
                $personal_statement = $_POST['personal_statement'] ?? '';
                $status = $_POST['status'] ?? 'pending';
                
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
                
                // Redirect to application view
                header("Location: " . BASE_URL . "/admin/applications/" . $id);
                exit;
                
            } catch (Exception $e) {
                error_log("ApplicationController update error: " . $e->getMessage());
                
                // Get application data for the form
                try {
                    $stmt = $this->db->prepare("SELECT * FROM applications WHERE id = ?");
                    $stmt->execute([$id]);
                    $application = $stmt->fetch();
                    
                    $this->loadView('admin/applications_edit', [
                        'application' => $application,
                        'error' => $e->getMessage()
                    ]);
                } catch (Exception $ex) {
                    $this->showError($e->getMessage());
                }
            }
        } else {
            header("Location: " . BASE_URL . "/admin/applications");
            exit;
        }
    }
    
    /**
     * Delete application
     */
    public function destroy($id) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
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
                
                // Redirect to applications list
                header("Location: " . BASE_URL . "/admin/applications");
                exit;
                
            } catch (Exception $e) {
                error_log("ApplicationController destroy error: " . $e->getMessage());
                $this->showError("Failed to delete application: " . $e->getMessage());
            }
        } else {
            header("Location: " . BASE_URL . "/admin/applications");
            exit;
        }
    }
    
    /**
     * Update application status
     */
    public function updateStatus() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                $id = $_POST['id'] ?? 0;
                $new_status = $_POST['status'] ?? '';
                $notes = $_POST['notes'] ?? '';
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
                
                // Redirect back to application view
                header("Location: " . BASE_URL . "/admin/applications/" . $id);
                exit;
                
            } catch (Exception $e) {
                error_log("ApplicationController updateStatus error: " . $e->getMessage());
                $this->showError("Failed to update application status: " . $e->getMessage());
            }
        } else {
            header("Location: " . BASE_URL . "/admin/applications");
            exit;
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