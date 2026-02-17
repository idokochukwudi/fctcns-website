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
     * Initialize payment - Generate RRR
     * URL: POST /payment/initiate
     */
    public function initiate() {
        // Set header to JSON
        header('Content-Type: application/json');
        
        // Start session if not started (parent doesn't always start it)
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        // Check if user is logged in
        if (!isset($_SESSION['applicant_id'])) {
            echo json_encode(['success' => false, 'message' => 'Please login first']);
            return;
        }
        
        try {
            // Get CSRF token from request and manually set it in POST for parent validation
            $input = json_decode(file_get_contents('php://input'), true);
            
            // IMPORTANT: Set the CSRF token in POST so parent validateCsrfToken() can find it
            if (isset($input['csrf_token'])) {
                $_POST['csrf_token'] = $input['csrf_token'];
            }
            
            // Validate CSRF using parent method (no parameters needed)
            if (!$this->validateCsrfToken()) {
                echo json_encode(['success' => false, 'message' => 'Invalid security token']);
                return;
            }
            
            $applicantId = $_SESSION['applicant_id'];
            
            // Get application
            $application = $this->applicationModel->getByApplicantId($applicantId);
            
            if (!$application) {
                echo json_encode(['success' => false, 'message' => 'Application not found']);
                return;
            }
            
            // Check if already paid
            if ($this->paymentModel->hasSuccessfulPayment($application['id'])) {
                echo json_encode(['success' => false, 'message' => 'Payment already completed']);
                return;
            }
            
            // Get fee
            $fee = $this->settingsModel->getApplicationFee();
            
            // Generate RRR (demo)
            $rrr = 'DEMO' . time() . rand(1000, 9999);
            $orderId = 'ORD' . time() . rand(100, 999);
            
            // Create payment record
            $paymentData = [
                'application_id' => $application['id'],
                'applicant_id' => $applicantId,
                'rrr' => $rrr,
                'order_id' => $orderId,
                'amount' => $fee,
                'status' => 'pending',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ];
            
            $paymentId = $this->paymentModel->insert($paymentData);
            
            if ($paymentId) {
                // Store in session for later use
                $_SESSION['pending_payment'] = [
                    'payment_id' => $paymentId,
                    'rrr' => $rrr,
                    'amount' => $fee,
                    'created_at' => time()
                ];
                
                echo json_encode([
                    'success' => true,
                    'rrr' => $rrr,
                    'payment_id' => $paymentId,
                    'message' => 'RRR generated successfully'
                ]);
            } else {
                echo json_encode([
                    'success' => false,
                    'message' => 'Failed to generate RRR'
                ]);
            }
            
        } catch (Exception $e) {
            error_log("Payment initiation error: " . $e->getMessage());
            echo json_encode([
                'success' => false,
                'message' => 'Server error occurred'
            ]);
        }
    }
    
    /**
     * Verify payment
     * URL: POST /payment/verify
     */
    public function verify() {
        header('Content-Type: application/json');
        
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        if (!isset($_SESSION['applicant_id'])) {
            echo json_encode(['success' => false, 'message' => 'Please login first']);
            return;
        }
        
        try {
            $input = json_decode(file_get_contents('php://input'), true);
            $rrr = $input['rrr'] ?? $_POST['rrr'] ?? '';
            
            // Set CSRF token in POST for parent validation
            if (isset($input['csrf_token'])) {
                $_POST['csrf_token'] = $input['csrf_token'];
            }
            
            // Validate CSRF using parent method
            if (!$this->validateCsrfToken()) {
                echo json_encode(['success' => false, 'message' => 'Invalid security token']);
                return;
            }
            
            if (empty($rrr)) {
                echo json_encode(['success' => false, 'message' => 'RRR is required']);
                return;
            }
            
            // Get payment record
            $payment = $this->paymentModel->getByRRR($rrr);
            
            if (!$payment) {
                echo json_encode(['success' => false, 'message' => 'Payment record not found']);
                return;
            }
            
            // For demo purposes, simulate successful verification
            // In production, this would call Remita API to verify
            
            // Update payment status
            $updateResult = $this->paymentModel->markAsSuccess($payment['id'], [
                'transaction_id' => 'TXN' . time(),
                'payment_method' => 'remita',
                'payment_details' => json_encode(['verified_at' => date('Y-m-d H:i:s')])
            ]);
            
            // Update application step
            $this->applicationModel->updateStep($payment['application_id'], 4);
            
            // Generate exam slip
            $this->applicationModel->generateExamSlip($payment['application_id']);
            
            // Clear pending payment from session
            unset($_SESSION['pending_payment']);
            
            echo json_encode([
                'success' => true,
                'message' => 'Payment verified successfully',
                'redirect' => '/apply/step/4'
            ]);
            
        } catch (Exception $e) {
            error_log("Payment verification error: " . $e->getMessage());
            echo json_encode([
                'success' => false,
                'message' => 'Server error occurred'
            ]);
        }
    }
    
    /**
     * Check payment status
     * URL: GET /payment/status
     */
    public function status() {
        header('Content-Type: application/json');
        
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
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
            'payment_date' => $payment['payment_date'] ?? null,
            'message' => $payment['status'] === 'success' ? 'Payment completed' : 'Payment pending'
        ]);
    }
    
    /**
     * Remita response handler
     * URL: GET /payment/remita-response
     */
    public function remitaResponse() {
        $rrr = $_GET['rrr'] ?? '';
        $status = $_GET['status'] ?? '';
        
        error_log("Remita Response: RRR={$rrr}, Status={$status}");
        
        if (empty($rrr)) {
            $this->flash('error', 'Invalid payment reference');
            $this->redirect('/apply/step/3');
            return;
        }
        
        // Find payment
        $payment = $this->paymentModel->getByRRR($rrr);
        
        if (!$payment) {
            $this->flash('error', 'Payment record not found');
            $this->redirect('/apply/step/3');
            return;
        }
        
        if ($status === 'success' || $status === '00') {
            // Update payment status
            $this->paymentModel->markAsSuccess($payment['id'], [
                'transaction_id' => $_GET['transactionId'] ?? ('TXN' . time()),
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
            $this->flash('error', 'Payment failed. Please try again.');
            $this->redirect('/apply/step/3');
        }
    }
    
    /**
     * Remita notification handler (webhook)
     * URL: POST /payment/remita-notification
     */
    public function remitaNotification() {
        $input = file_get_contents('php://input');
        $data = json_decode($input, true);
        
        error_log("Remita Notification: " . print_r($data, true));
        
        if (empty($data) || !isset($data['rrr'])) {
            http_response_code(400);
            echo json_encode(['error' => 'Invalid notification']);
            return;
        }
        
        $rrr = $data['rrr'];
        $status = $data['status'] ?? '';
        
        $payment = $this->paymentModel->getByRRR($rrr);
        
        if (!$payment) {
            http_response_code(404);
            echo json_encode(['error' => 'Payment not found']);
            return;
        }
        
        if ($status === '00' || $status === 'success') {
            $this->paymentModel->markAsSuccess($payment['id'], [
                'transaction_id' => $data['transactionId'] ?? '',
                'payment_method' => 'remita',
                'payment_details' => json_encode($data)
            ]);
            
            $this->applicationModel->updateStep($payment['application_id'], 4);
            $this->applicationModel->generateExamSlip($payment['application_id']);
        }
        
        echo json_encode(['status' => 'success']);
    }
    
    /**
     * Check payment status (AJAX endpoint)
     * URL: GET /payment/check-status
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
     * Admin manual verification
     * URL: POST /payment/admin/verify
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
        
        // Validate CSRF using parent method
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
            'payment_details' => json_encode([
                'verified_by' => $_SESSION['user_id'],
                'verified_at' => date('Y-m-d H:i:s')
            ])
        ]);
        
        // Update application
        $this->applicationModel->updateStep($payment['application_id'], 4);
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