<?php
/**
 * Payment Controller
 * 
 * Handles all payment-related operations including Remita integration
 * 
 * @package FCT_CNS
 */

require_once CORE_PATH . '/Controller.php';

class PaymentController extends Controller {
    
    private $db;
    private $paymentModel;
    private $remitaModel;
    private $applicationModel;
    private $settingsModel;
    
    /**
     * Constructor
     */
    public function __construct() {
        parent::__construct();
        
        // Setup database
        require_once __DIR__ . '/../config/database.php';
        $database = Database::getInstance();
        $this->db = $database->getConnection();
        
        // Load models
        require_once MODELS_PATH . '/application/PaymentModel.php';
        require_once MODELS_PATH . '/application/RemitaModel.php';
        require_once MODELS_PATH . '/application/ApplicationModel.php';
        require_once MODELS_PATH . '/application/SettingsModel.php';
        require_once MODELS_PATH . '/application/ApplicantModel.php';
        
        $this->paymentModel = new PaymentModel();
        $this->remitaModel = new RemitaModel();
        $this->applicationModel = new ApplicationModel();
        $this->settingsModel = new SettingsModel();
    }
    
    /**
     * STEP A: Initialize Payment (Called when user clicks "Pay Now")
     * URL: /payment/initiate
     * DEBUG VERSION: Enhanced logging to identify 500 error
     */
    public function initiate() {
        // Enable error reporting for this request
        error_reporting(E_ALL);
        ini_set('display_errors', 1);
        
        // Set JSON header first
        header('Content-Type: application/json');
        
        // Log everything
        error_log("=== PAYMENT INITIATE STARTED ===");
        
        try {
            // Start session if not started
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }
            
            error_log("Session: " . print_r($_SESSION, true));
            
            // Check if user is logged in
            if (!isset($_SESSION['applicant_id'])) {
                error_log("ERROR: User not logged in");
                echo json_encode([
                    'success' => false, 
                    'message' => 'Please login first'
                ]);
                return;
            }
            
            error_log("Applicant ID: " . $_SESSION['applicant_id']);
            
            // Get input data (could be JSON or form data)
            $input = json_decode(file_get_contents('php://input'), true);
            error_log("Input data: " . print_r($input, true));
            
            $csrfToken = $input['csrf_token'] ?? $_POST['csrf_token'] ?? '';
            error_log("CSRF Token received: " . ($csrfToken ? 'YES (length: ' . strlen($csrfToken) . ')' : 'NO'));
            
            // Check if models are loaded
            if (!isset($this->applicationModel)) {
                error_log("ERROR: applicationModel not loaded");
                echo json_encode([
                    'success' => false, 
                    'message' => 'Application model not loaded'
                ]);
                return;
            }
            
            if (!isset($this->paymentModel)) {
                error_log("ERROR: paymentModel not loaded");
                echo json_encode([
                    'success' => false, 
                    'message' => 'Payment model not loaded'
                ]);
                return;
            }
            
            if (!isset($this->settingsModel)) {
                error_log("ERROR: settingsModel not loaded");
                echo json_encode([
                    'success' => false, 
                    'message' => 'Settings model not loaded'
                ]);
                return;
            }
            
            // Get application
            error_log("Getting application for applicant ID: " . $_SESSION['applicant_id']);
            $application = $this->applicationModel->getByApplicantId($_SESSION['applicant_id']);
            error_log("Application result: " . ($application ? 'FOUND' : 'NOT FOUND'));
            
            if (!$application) {
                error_log("ERROR: Application not found");
                echo json_encode([
                    'success' => false, 
                    'message' => 'Application not found'
                ]);
                return;
            }
            
            error_log("Application ID: " . $application['id']);
            
            // Check if already paid
            error_log("Checking if already paid for application ID: " . $application['id']);
            $hasPaid = $this->paymentModel->hasSuccessfulPayment($application['id']);
            error_log("Has paid: " . ($hasPaid ? 'YES' : 'NO'));
            
            if ($hasPaid) {
                error_log("ERROR: Payment already completed");
                echo json_encode([
                    'success' => false, 
                    'message' => 'Payment already completed'
                ]);
                return;
            }
            
            // Get fee from settings
            $fee = $this->settingsModel->getApplicationFee();
            error_log("Application fee: " . $fee);
            
            // Generate RRR (demo)
            $rrr = 'DEMO' . time() . rand(1000, 9999);
            $orderId = 'ORD' . time() . rand(100, 999);
            
            error_log("Generated RRR: " . $rrr);
            error_log("Generated Order ID: " . $orderId);
            
            // Create payment record
            $paymentData = [
                'application_id' => $application['id'],
                'applicant_id' => $_SESSION['applicant_id'],
                'rrr' => $rrr,
                'order_id' => $orderId,
                'amount' => $fee,
                'status' => 'pending',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ];
            
            error_log("Payment data: " . print_r($paymentData, true));
            
            $paymentId = $this->paymentModel->insert($paymentData);
            error_log("Payment insert result: " . ($paymentId ? "SUCCESS (ID: $paymentId)" : "FAILED"));
            
            if ($paymentId) {
                // Store in session for later use
                $_SESSION['pending_payment'] = [
                    'payment_id' => $paymentId,
                    'rrr' => $rrr,
                    'amount' => $fee,
                    'created_at' => time()
                ];
                
                error_log("SUCCESS: Payment record created with ID: $paymentId");
                
                // Return success response
                $response = [
                    'success' => true,
                    'rrr' => $rrr,
                    'payment_id' => $paymentId,
                    'message' => 'RRR generated successfully'
                ];
                error_log("Response: " . json_encode($response));
                echo json_encode($response);
                
            } else {
                error_log("ERROR: Failed to create payment record");
                echo json_encode([
                    'success' => false,
                    'message' => 'Failed to generate RRR. Please try again.'
                ]);
            }
            
        } catch (Exception $e) {
            // Log the full error details
            error_log("=== PAYMENT INITIATE EXCEPTION ===");
            error_log("Error message: " . $e->getMessage());
            error_log("Error file: " . $e->getFile() . " on line " . $e->getLine());
            error_log("Error code: " . $e->getCode());
            error_log("Stack trace: " . $e->getTraceAsString());
            
            // Also log previous exception if exists
            if ($e->getPrevious()) {
                error_log("Previous exception: " . $e->getPrevious()->getMessage());
            }
            
            echo json_encode([
                'success' => false,
                'message' => 'An error occurred while processing your request. Please try again.'
            ]);
        }
        
        error_log("=== PAYMENT INITIATE ENDED ===");
    }
    
    /**
     * STEP B: Verify Payment (Called when user clicks "I've Paid, Verify")
     * URL: /payment/verify
     */
    public function verify() {
        header('Content-Type: application/json');
        
        // Start session if not started
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        error_log("=== PAYMENT VERIFY STARTED ===");
        
        try {
            // Get CSRF token
            $csrfToken = $_POST['csrf_token'] ?? $_GET['csrf_token'] ?? '';
            
            // Validate CSRF (optional for debugging)
            if (!empty($csrfToken) && !$this->validateCsrfToken($csrfToken)) {
                error_log("CSRF validation failed in verify");
                // Continue for debugging, but log it
            }
            
            $rrr = $_POST['rrr'] ?? $_GET['rrr'] ?? '';
            error_log("Verifying payment for RRR: " . $rrr);
            
            if (empty($rrr)) {
                error_log("ERROR: RRR is empty");
                echo json_encode([
                    'success' => false,
                    'message' => 'RRR is required'
                ]);
                return;
            }
            
            // Get payment record
            $payment = $this->paymentModel->getByRRR($rrr);
            error_log("Payment record found: " . ($payment ? 'YES' : 'NO'));
            
            if (!$payment) {
                error_log("ERROR: Payment record not found for RRR: $rrr");
                echo json_encode([
                    'success' => false,
                    'message' => 'Payment record not found'
                ]);
                return;
            }
            
            error_log("Payment ID: " . $payment['id']);
            error_log("Payment status: " . $payment['status']);
            
            // For demo purposes, simulate successful verification
            // In production, this would call Remita API to verify
            
            // Update payment status
            $updateResult = $this->paymentModel->markAsSuccess($payment['id'], [
                'transaction_id' => 'TXN' . time(),
                'payment_method' => 'remita',
                'payment_details' => json_encode(['verified_at' => date('Y-m-d H:i:s')])
            ]);
            error_log("Payment update result: " . ($updateResult ? 'SUCCESS' : 'FAILED'));
            
            // Update application step
            $stepUpdate = $this->applicationModel->updateStep($payment['application_id'], 4);
            error_log("Application step update: " . ($stepUpdate ? 'SUCCESS' : 'FAILED'));
            
            // Generate exam slip
            $slipGenerated = $this->applicationModel->generateExamSlip($payment['application_id']);
            error_log("Exam slip generated: " . ($slipGenerated ? 'YES' : 'NO'));
            
            // Clear pending payment from session
            unset($_SESSION['pending_payment']);
            
            error_log("SUCCESS: Payment verified for RRR: $rrr");
            
            echo json_encode([
                'success' => true,
                'message' => 'Payment verified successfully',
                'redirect' => '/apply/step/4'
            ]);
            
        } catch (Exception $e) {
            error_log("=== PAYMENT VERIFY EXCEPTION ===");
            error_log("Error message: " . $e->getMessage());
            error_log("Stack trace: " . $e->getTraceAsString());
            
            echo json_encode([
                'success' => false,
                'message' => 'An error occurred during verification'
            ]);
        }
        
        error_log("=== PAYMENT VERIFY ENDED ===");
    }
    
    /**
     * Remita response handler (after payment)
     */
    public function remitaResponse() {
        $rrr = $_GET['rrr'] ?? '';
        $status = $_GET['status'] ?? '';
        
        error_log("Remita Response: RRR={$rrr}, Status={$status}");
        
        if (empty($rrr)) {
            $this->flash('error', 'Invalid payment reference.');
            $this->redirect('/apply/step/3');
            return;
        }
        
        // Get payment record
        $payment = $this->paymentModel->getByRRR($rrr);
        
        if ($payment) {
            if ($status === 'success' || $status === '00') {
                // Update payment status
                $this->paymentModel->markAsSuccess($payment['id'], [
                    'transaction_id' => 'TXN' . time(),
                    'payment_method' => 'remita',
                    'payment_details' => json_encode(['response_status' => $status])
                ]);
                
                // Update application step
                $this->applicationModel->updateStep($payment['application_id'], 4);
                
                // Generate exam slip
                $this->applicationModel->generateExamSlip($payment['application_id']);
                
                $this->flash('success', 'Payment successful! You can now download your exam slip.');
                $this->redirect('/apply/step/4');
            } else {
                $this->flash('error', 'Payment was not successful. Please try again.');
                $this->redirect('/apply/step/3');
            }
        } else {
            $this->flash('error', 'Payment record not found.');
            $this->redirect('/apply/step/3');
        }
    }
    
    /**
     * Remita notification handler (server-to-server)
     */
    public function remitaNotification() {
        // Get raw POST data
        $input = file_get_contents('php://input');
        $data = json_decode($input, true);
        
        error_log("Remita Notification: " . print_r($data, true));
        
        if (empty($data)) {
            http_response_code(400);
            echo json_encode(['status' => 'error', 'message' => 'Invalid notification data']);
            return;
        }
        
        $rrr = $data['rrr'] ?? '';
        $transactionId = $data['transactionId'] ?? '';
        $status = $data['status'] ?? '';
        
        if (empty($rrr)) {
            http_response_code(400);
            echo json_encode(['status' => 'error', 'message' => 'Missing RRR']);
            return;
        }
        
        // Find payment
        $payment = $this->paymentModel->getByRRR($rrr);
        
        if (!$payment) {
            http_response_code(404);
            echo json_encode(['status' => 'error', 'message' => 'Payment not found']);
            return;
        }
        
        // Update based on status
        if ($status === '00' || $status === 'success') {
            $this->paymentModel->markAsSuccess($payment['id'], [
                'transaction_id' => $transactionId,
                'payment_method' => 'remita',
                'payment_details' => json_encode($data)
            ]);
            
            // Update application step
            $this->applicationModel->updateStep($payment['application_id'], 4);
            
            // Generate exam slip
            $this->applicationModel->generateExamSlip($payment['application_id']);
            
            error_log("Payment $rrr marked as success via notification");
        } else {
            $this->paymentModel->markAsFailed($payment['id'], 'Remita notification: ' . $status);
            error_log("Payment $rrr marked as failed via notification");
        }
        
        // Acknowledge receipt
        echo json_encode(['status' => 'success', 'message' => 'Notification received']);
    }
    
    /**
     * Check payment status (AJAX)
     */
    public function checkStatus() {
        header('Content-Type: application/json');
        
        $rrr = $_GET['rrr'] ?? '';
        
        if (empty($rrr)) {
            echo json_encode(['success' => false, 'message' => 'RRR required']);
            return;
        }
        
        $payment = $this->paymentModel->getByRRR($rrr);
        
        if (!$payment) {
            echo json_encode(['success' => false, 'message' => 'Payment not found']);
            return;
        }
        
        echo json_encode([
            'success' => true,
            'status' => $payment['status'],
            'message' => $payment['status'] === 'success' ? 'Payment completed' : 'Payment pending'
        ]);
    }
    
    /**
     * Verify payment (manual verification by admin)
     */
    public function adminVerify() {
        // Check if user is admin
        if (!in_array($_SESSION['user_role'] ?? '', ['admin', 'super_admin'])) {
            http_response_code(403);
            echo json_encode(['error' => 'Unauthorized']);
            return;
        }
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['error' => 'Method not allowed']);
            return;
        }
        
        // Validate CSRF
        if (!$this->validateCsrfToken()) {
            echo json_encode(['error' => 'Invalid CSRF token']);
            return;
        }
        
        $paymentId = $_POST['payment_id'] ?? '';
        
        if (empty($paymentId)) {
            echo json_encode(['error' => 'Payment ID required']);
            return;
        }
        
        $payment = $this->paymentModel->find($paymentId);
        
        if (!$payment) {
            echo json_encode(['error' => 'Payment not found']);
            return;
        }
        
        if ($payment['status'] === 'success') {
            echo json_encode(['error' => 'Payment already verified']);
            return;
        }
        
        // Mark as success manually
        $this->paymentModel->markAsSuccess($paymentId, [
            'payment_method' => 'manual',
            'payment_details' => json_encode(['verified_by' => $_SESSION['user_id'], 'verified_at' => date('Y-m-d H:i:s')])
        ]);
        
        // Update application
        $this->applicationModel->updateStep($payment['application_id'], 4);
        
        // Generate exam slip
        $this->applicationModel->generateExamSlip($payment['application_id']);
        
        // Log activity
        $this->logPaymentAction($paymentId, 'manual_verification', "Payment manually verified by admin");
        
        echo json_encode(['success' => true, 'message' => 'Payment verified successfully']);
    }
    
    /**
     * Set applicant session from payment
     */
    private function setApplicantSessionFromPayment($payment) {
        // Get application and applicant
        $application = $this->applicationModel->find($payment['application_id']);
        
        if (!$application) {
            return;
        }
        
        $applicantModel = new ApplicantModel();
        $applicant = $applicantModel->find($payment['applicant_id']);
        
        if (!$applicant) {
            return;
        }
        
        // Set session
        session_regenerate_id(true);
        
        $_SESSION['applicant_id'] = $applicant['id'];
        $_SESSION['applicant_jamb'] = $applicant['jamb_number'] ?? '';
        $_SESSION['applicant_name'] = ($applicant['first_name'] ?? '') . ' ' . ($applicant['last_name'] ?? '');
        $_SESSION['applicant_login_time'] = time();
    }
    
    /**
     * Log payment action
     */
    private function logPaymentAction($paymentId, $action, $description) {
        try {
            $stmt = $this->db->prepare("
                INSERT INTO activity_logs (user_id, action, description, table_name, record_id, ip_address, user_agent, created_at)
                VALUES (:user_id, :action, :description, 'payments', :record_id, :ip_address, :user_agent, NOW())
            ");
            
            $stmt->execute([
                'user_id' => $_SESSION['user_id'] ?? null,
                'action' => $action,
                'description' => $description,
                'record_id' => $paymentId,
                'ip_address' => $_SERVER['REMOTE_ADDR'] ?? '',
                'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? ''
            ]);
        } catch (Exception $e) {
            error_log("Failed to log payment action: " . $e->getMessage());
        }
    }
    
    /**
     * Validate CSRF token
     */
    private function validateCsrfToken($token = null) {
        if ($token === null) {
            $token = $_POST['csrf_token'] ?? $_GET['csrf_token'] ?? '';
        }
        
        // If no token in session, generate one (for debugging)
        if (!isset($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
            error_log("Generated new CSRF token: " . $_SESSION['csrf_token']);
        }
        
        // Log for debugging
        $isValid = ($token === $_SESSION['csrf_token']);
        if (!$isValid) {
            error_log("CSRF validation failed: Token='$token', Session='{$_SESSION['csrf_token']}'");
        }
        
        return $isValid;
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
        
        // Merge with controller data
        $this->data = array_merge($this->data, $data);
        
        parent::render($view);
    }
}