<?php
/**
 * Verification Controller
 * Handles public verification of documents via QR codes
 * 
 * @package FCT_CNS
 */

class VerificationController extends Controller {
    
    /**
     * @var NominalRollModel
     */
    private $model;
    
    /**
     * Constructor - NO AUTHENTICATION REQUIRED
     */
    public function __construct() {
        parent::__construct();
        
        // Set public layout (no admin layout)
        $this->layout = 'public';
        
        // Load model
        require_once __DIR__ . '/../models/NominalRollModel.php';
        $this->model = new NominalRollModel();
        
        // Initialize data
        $this->data = array_merge($this->data, [
            'baseUrl' => defined('BASE_URL') ? BASE_URL : '',
            'currentPage' => 'verification'
        ]);
    }
    
    /**
     * Verify employee via QR code - PUBLIC ACCESS
     */
    public function verifyEmployee($id) {
        try {
            // Get employee data
            $employee = $this->model->getEmployee($id);
            
            if (!$employee) {
                // Show error page
                return $this->renderVerificationError('Employee record not found or has been deleted.');
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
                'baseUrl' => $this->data['baseUrl']
            ];
            
            // Log verification attempt
            $this->logVerification($verificationData);
            
            // Show verification confirmation page
            return $this->renderVerificationConfirmation($verificationData);
            
        } catch (Exception $e) {
            error_log("Employee verification error: " . $e->getMessage());
            return $this->renderVerificationError('An error occurred during verification.');
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
                return $this->renderVerificationError('Invalid document reference format.');
            }
            
            // Redirect to employee verification
            $this->redirect('/verify/employee/' . $employeeId . '?ref=' . urlencode($ref));
            
        } catch (Exception $e) {
            error_log("Document verification error: " . $e->getMessage());
            return $this->renderVerificationError('An error occurred during verification.');
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
            'pageTitle' => 'Document Verification - FCT College of Nursing Sciences'
        ]);
        
        // Load verification view directly (no layout)
        $this->renderPublic('verification/confirmation');
    }
    
    /**
     * Render verification error page
     */
    private function renderVerificationError($message) {
        $this->data = array_merge($this->data, [
            'errorMessage' => $message,
            'pageTitle' => 'Verification Error - FCT College of Nursing Sciences'
        ]);
        
        $this->renderPublic('verification/error');
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
                "[%s] VERIFICATION - ID: %s, Employee: %s (%s), Valid: %s, IP: %s\n",
                date('Y-m-d H:i:s'),
                $data['verificationId'],
                $data['employee']['employee_number'] ?? 'N/A',
                $data['employee']['surname'] . ', ' . $data['employee']['first_name'],
                $data['isValid'] ? 'YES' : 'NO',
                $data['ipAddress']
            );
            
            // Write to log file
            file_put_contents($logFile, $logEntry, FILE_APPEND);
            
        } catch (Exception $e) {
            error_log("Failed to log verification: " . $e->getMessage());
        }
    }
    
    /**
     * Render view without layout (for public access)
     */
    protected function renderPublic($view) {
        // Get full view path
        $viewPath = APP_PATH . '/views/' . $view . '.php';
        
        if (!file_exists($viewPath)) {
            throw new Exception("View not found: $view");
        }
        
        // Extract data for view
        extract($this->data);
        
        // Include view directly
        include $viewPath;
    }
}