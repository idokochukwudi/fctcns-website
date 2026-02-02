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
     * FIXED: Verify employee via QR code - PUBLIC ACCESS
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
            
            // FIXED: Updated validation for FCTCNS0001 format
            $isValid = false;
            $employeeNumber = $employee['employee_number'] ?? '';
            
            if (!empty($documentRef) && !empty($employeeNumber)) {
                // Check if document reference starts with employee number
                if (strpos($documentRef, $employeeNumber) === 0) {
                    $isValid = true;
                }
                // Also accept just the employee number
                elseif ($documentRef === $employeeNumber) {
                    $isValid = true;
                }
                // For backward compatibility, also check EMP- format
                elseif (strpos($documentRef, 'EMP-' . $employee['id']) === 0) {
                    $isValid = true;
                }
            }
            
            // Prepare verification data
            $verificationData = [
                'employee' => $employee,
                'documentRef' => $documentRef,
                'expectedRef' => $employeeNumber . '-' . 
                    date('Ymd', strtotime($employee['updated_at'] ?? 'now')),
                'isValid' => $isValid,
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
     * FIXED: Verify document by reference
     */
    public function verifyDocument($ref) {
        try {
            // Try to extract employee ID from different formats
            
            // Format 1: FCTCNS0001-20260202
            if (preg_match('/^FCTCNS\d+/', $ref)) {
                $parts = explode('-', $ref);
                $employeePrefix = $parts[0]; // FCTCNS0001
                
                // Find employee by employee_number
                $employee = $this->model->getEmployeeByNumber($employeePrefix);
                
                if ($employee) {
                    // Redirect to employee verification
                    $this->redirect('/verify/employee/' . $employee['id'] . '?ref=' . urlencode($ref));
                    return;
                }
            }
            
            // Format 2: EMP-45-20260202 (old format)
            $parts = explode('-', $ref);
            if (count($parts) >= 2 && $parts[0] === 'EMP') {
                $employeeId = $parts[1];
                $this->redirect('/verify/employee/' . $employeeId . '?ref=' . urlencode($ref));
                return;
            }
            
            return $this->renderVerificationError('Invalid document reference format.');
            
        } catch (Exception $e) {
            error_log("Document verification error: " . $e->getMessage());
            return $this->renderVerificationError('An error occurred during verification.');
        }
    }
    
    /**
     * Get passport photo - PUBLIC ACCESS
     * Add this method to your existing VerificationController
     */
    public function getPassportPhoto($id) {
        try {
            // Get employee data
            $employee = $this->model->getEmployee($id);
            
            if (!$employee) {
                $this->serveDefaultPhoto();
                return;
            }
            
            // Check if photo exists in database
            if (empty($employee['passport_photo'])) {
                $this->serveDefaultPhoto();
                return;
            }
            
            $photoPath = ROOT_PATH . '/' . $employee['passport_photo'];
            
            // Check if file exists on server
            if (!file_exists($photoPath)) {
                error_log("Photo file not found: " . $photoPath);
                $this->serveDefaultPhoto();
                return;
            }
            
            // Determine MIME type from extension
            $ext = strtolower(pathinfo($photoPath, PATHINFO_EXTENSION));
            $mimeTypes = [
                'jpg' => 'image/jpeg',
                'jpeg' => 'image/jpeg',
                'png' => 'image/png',
                'gif' => 'image/gif',
                'svg' => 'image/svg+xml'
            ];
            
            $contentType = $mimeTypes[$ext] ?? 'image/jpeg';
            
            // Output the image
            header('Content-Type: ' . $contentType);
            header('Content-Length: ' . filesize($photoPath));
            header('Cache-Control: public, max-age=86400'); // Cache for 1 day
            readfile($photoPath);
            exit;
            
        } catch (Exception $e) {
            error_log("Passport photo error: " . $e->getMessage());
            $this->serveDefaultPhoto();
        }
    }
    
    /**
     * Serve default photo when no photo exists
     * Add this method too
     */
    private function serveDefaultPhoto() {
        // Create a simple SVG placeholder
        header('Content-Type: image/svg+xml');
        echo '<?xml version="1.0" encoding="UTF-8"?>
        <svg width="150" height="180" xmlns="http://www.w3.org/2000/svg">
            <rect width="150" height="180" fill="#f0f0f0"/>
            <circle cx="75" cy="70" r="40" fill="#ccc"/>
            <rect x="40" y="120" width="70" height="50" fill="#ccc" rx="5"/>
            <text x="75" y="170" text-anchor="middle" font-family="Arial" font-size="12" fill="#666">No Photo</text>
        </svg>';
        exit;
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