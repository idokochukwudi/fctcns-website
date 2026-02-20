<?php
/**
 * Payment Controller
 * 
 * Handles all payment-related operations including Remita integration
 * FIXED: Real RRR generation using Remita API (no fake DEMO RRRs)
 * FIXED: Proper payment verification through Remita
 * FIXED: CSRF token validation
 * FIXED: Added missing helper methods
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
        require_once MODELS_PATH . '/application/ExamSlipModel.php';
        
        $this->paymentModel = new PaymentModel();
        $this->remitaModel = new RemitaModel();
        $this->applicationModel = new ApplicationModel();
        $this->settingsModel = new SettingsModel();
    }
    
    /**
     * Initialize payment - Generate REAL RRR using Remita API
     * URL: POST /payment/initiate
     */
    public function initiate() {
        // Set header to JSON
        header('Content-Type: application/json');
        
        // Start session if not started
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        // Log session for debugging
        error_log("=== PAYMENT INITIATE (REAL Remita) ===");
        error_log("Session ID: " . session_id());
        error_log("Session applicant_id: " . ($_SESSION['applicant_id'] ?? 'not set'));
        
        // Check if user is logged in
        if (!isset($_SESSION['applicant_id'])) {
            error_log("ERROR: User not logged in");
            echo json_encode([
                'success' => false, 
                'message' => 'Please login first'
            ]);
            return;
        }
        
        try {
            // Get CSRF token from request
            $input = json_decode(file_get_contents('php://input'), true);
            $csrfToken = $input['csrf_token'] ?? '';
            
            error_log("CSRF Token received: " . ($csrfToken ? substr($csrfToken, 0, 10) . '...' : 'EMPTY'));
            
            // Validate CSRF token
            if (empty($csrfToken)) {
                error_log("CSRF validation failed: Token is empty");
                echo json_encode([
                    'success' => false, 
                    'message' => 'Security token missing'
                ]);
                return;
            }
            
            // Check if token exists in session
            if (!isset($_SESSION['csrf_tokens'][$csrfToken])) {
                error_log("CSRF validation failed: Token not found in session");
                error_log("Session tokens: " . print_r(array_keys($_SESSION['csrf_tokens'] ?? []), true));
                echo json_encode([
                    'success' => false, 
                    'message' => 'Invalid security token'
                ]);
                return;
            }
            
            // Check token expiration (1 hour)
            $tokenTime = $_SESSION['csrf_tokens'][$csrfToken];
            if (time() - $tokenTime > 3600) {
                unset($_SESSION['csrf_tokens'][$csrfToken]);
                error_log("CSRF validation failed: Token expired");
                echo json_encode([
                    'success' => false, 
                    'message' => 'Security token expired. Please refresh the page.'
                ]);
                return;
            }
            
            error_log("CSRF validation successful");
            
            $applicantId = $_SESSION['applicant_id'];
            
            // Get application
            $application = $this->applicationModel->getByApplicantId($applicantId);
            
            if (!$application) {
                error_log("Application not found for applicant: $applicantId");
                echo json_encode([
                    'success' => false, 
                    'message' => 'Application not found'
                ]);
                return;
            }
            
            error_log("Application found: ID=" . $application['id']);
            
            // Check if already paid
            if ($this->paymentModel->hasSuccessfulPayment($application['id'])) {
                error_log("Payment already completed for application: " . $application['id']);
                echo json_encode([
                    'success' => false, 
                    'message' => 'Payment already completed'
                ]);
                return;
            }
            
            // Get fee
            $fee = $this->settingsModel->getApplicationFee();
            error_log("Application fee: " . $fee);
            
            // Get applicant details for Remita
            $applicant = $this->getApplicant();
            
            // Get application details for name
            $payerName = trim(($application['first_name'] ?? '') . ' ' . ($application['last_name'] ?? ''));
            if (empty($payerName)) {
                $payerName = $applicant['email'] ?? 'Applicant';
            }
            
            $payerEmail = $applicant['email'] ?? '';
            $payerPhone = $application['phone'] ?? $applicant['phone'] ?? '';
            
            // Generate Order ID (unique)
            $orderId = 'ORD' . time() . rand(100, 999);
            
            error_log("Calling Remita API with: OrderID=$orderId, Amount=$fee, Payer=$payerName, Email=$payerEmail");
            
            // FIXED: Use RemitaModel to generate REAL RRR
            $result = $this->remitaModel->generateRRRRemita(
                $orderId,
                $fee,
                $payerName,
                $payerEmail,
                $payerPhone
            );
            
            error_log("Remita API Result: " . print_r($result, true));
            
            if ($result['status'] === 'success' && isset($result['rrr'])) {
                $rrr = $result['rrr'];
                
                error_log("✅ REAL RRR generated from Remita API: " . $rrr);
                
                // Create payment record in database
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
                
                if (!$paymentId) {
                    error_log("Failed to create payment record");
                    echo json_encode([
                        'success' => false,
                        'message' => 'Failed to create payment record'
                    ]);
                    return;
                }
                
                error_log("Payment record created with ID: $paymentId");
                
                // Store in session for later use
                $_SESSION['pending_payment'] = [
                    'payment_id' => $paymentId,
                    'rrr' => $rrr,
                    'amount' => $fee,
                    'created_at' => time()
                ];
                
                // Optionally remove the used token (can be kept for multiple attempts)
                // unset($_SESSION['csrf_tokens'][$csrfToken]);
                
                echo json_encode([
                    'success' => true,
                    'rrr' => $rrr,
                    'payment_id' => $paymentId,
                    'message' => 'RRR generated successfully'
                ]);
                
            } else {
                // API call failed
                $errorMsg = $result['message'] ?? 'Unknown error';
                error_log("❌ Remita API failed: " . $errorMsg);
                
                echo json_encode([
                    'success' => false,
                    'message' => 'Failed to generate RRR. Please try again or contact support.',
                    'debug' => $errorMsg
                ]);
            }
            
        } catch (Exception $e) {
            error_log("=== PAYMENT INITIATE EXCEPTION ===");
            error_log("Error message: " . $e->getMessage());
            error_log("Error file: " . $e->getFile() . " on line " . $e->getLine());
            error_log("Stack trace: " . $e->getTraceAsString());
            
            echo json_encode([
                'success' => false,
                'message' => 'Server error occurred: ' . $e->getMessage()
            ]);
        }
    }
    
    /**
     * Verify payment with Remita
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
            $csrfToken = $input['csrf_token'] ?? $_POST['csrf_token'] ?? '';
            
            // Validate CSRF token
            if (empty($csrfToken)) {
                echo json_encode(['success' => false, 'message' => 'Security token missing']);
                return;
            }
            
            if (!isset($_SESSION['csrf_tokens'][$csrfToken])) {
                echo json_encode(['success' => false, 'message' => 'Invalid security token']);
                return;
            }
            
            // Check token expiration
            if (time() - $_SESSION['csrf_tokens'][$csrfToken] > 3600) {
                unset($_SESSION['csrf_tokens'][$csrfToken]);
                echo json_encode(['success' => false, 'message' => 'Security token expired']);
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
            
            // Verify ownership
            if ($payment['applicant_id'] != $_SESSION['applicant_id']) {
                echo json_encode(['success' => false, 'message' => 'Unauthorized access to payment']);
                return;
            }
            
            // Call Remita to verify payment status
            $verificationResult = $this->remitaModel->verifyPayment($rrr);
            
            error_log("Remita verification result: " . print_r($verificationResult, true));
            
            if ($verificationResult['status'] === 'success') {
                // Payment is confirmed by Remita
                $updateResult = $this->paymentModel->markAsSuccess($payment['id'], [
                    'transaction_id' => $verificationResult['payment_data']['transactionId'] ?? 'TXN' . time(),
                    'payment_method' => 'remita',
                    'payer_email' => $verificationResult['payment_data']['payerEmail'] ?? null,
                    'payer_name' => $verificationResult['payment_data']['payerName'] ?? null,
                    'payment_details' => json_encode($verificationResult['payment_data'])
                ]);
                
                if ($updateResult) {
                    // Update application step to 4 (Payment Complete)
                    $this->applicationModel->updateApplication($payment['application_id'], [
                        'application_step' => 4
                    ]);
                    
                    // Generate exam slip
                    $this->generateExamSlip($payment['application_id']);
                    
                    // Clear pending payment from session
                    unset($_SESSION['pending_payment']);
                    
                    // Remove used token
                    unset($_SESSION['csrf_tokens'][$csrfToken]);
                    
                    echo json_encode([
                        'success' => true,
                        'message' => 'Payment verified successfully',
                        'redirect' => '/apply/step/4'
                    ]);
                } else {
                    echo json_encode([
                        'success' => false,
                        'message' => 'Failed to update payment status'
                    ]);
                }
            } elseif ($verificationResult['status'] === 'pending') {
                echo json_encode([
                    'success' => false,
                    'message' => 'Payment is still pending on Remita. Please check again later.',
                    'pending' => true
                ]);
            } else {
                echo json_encode([
                    'success' => false,
                    'message' => 'Payment not found or not completed on Remita.'
                ]);
            }
            
        } catch (Exception $e) {
            error_log("Payment verification error: " . $e->getMessage());
            echo json_encode([
                'success' => false,
                'message' => 'Server error occurred'
            ]);
        }
    }
    
    /**
     * Check payment status without verification
     * URL: GET /payment/check-status
     */
    public function checkStatus() {
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
     * Remita response handler (return URL after payment)
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
            // Verify with Remita API to confirm
            $verificationResult = $this->remitaModel->verifyPayment($rrr);
            
            if ($verificationResult['status'] === 'success') {
                // Update payment status
                $this->paymentModel->markAsSuccess($payment['id'], [
                    'transaction_id' => $_GET['transactionId'] ?? ('TXN' . time()),
                    'payment_method' => 'remita',
                    'payment_details' => json_encode(['response_status' => $status, 'verification' => $verificationResult])
                ]);
                
                // Update application step
                $this->applicationModel->updateApplication($payment['application_id'], [
                    'application_step' => 4
                ]);
                
                // Generate exam slip
                $this->generateExamSlip($payment['application_id']);
                
                $this->flash('success', 'Payment successful! You can now download your exam slip.');
                $this->redirect('/apply/step/4');
            } else {
                $this->flash('error', 'Payment verification failed. Please contact support.');
                $this->redirect('/apply/step/3');
            }
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
            
            $this->applicationModel->updateApplication($payment['application_id'], [
                'application_step' => 4
            ]);
            
            $this->generateExamSlip($payment['application_id']);
        }
        
        echo json_encode(['status' => 'success']);
    }
    
    /**
     * Admin manual verification
     * URL: POST /payment/admin/verify
     */
    public function adminVerify() {
        header('Content-Type: application/json');
        
        // Start session
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
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
        
        // Validate CSRF token
        $csrfToken = $_POST['csrf_token'] ?? '';
        if (empty($csrfToken)) {
            echo json_encode(['error' => 'Security token missing']);
            return;
        }
        
        if (!isset($_SESSION['csrf_tokens'][$csrfToken])) {
            echo json_encode(['error' => 'Invalid CSRF token']);
            return;
        }
        
        // Check token expiration
        if (time() - $_SESSION['csrf_tokens'][$csrfToken] > 3600) {
            unset($_SESSION['csrf_tokens'][$csrfToken]);
            echo json_encode(['error' => 'Security token expired']);
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
        $result = $this->paymentModel->markAsSuccess($paymentId, [
            'payment_method' => 'manual',
            'payment_details' => json_encode([
                'verified_by' => $_SESSION['user_id'],
                'verified_at' => date('Y-m-d H:i:s')
            ])
        ]);
        
        if ($result) {
            // Update application
            $this->applicationModel->updateApplication($payment['application_id'], [
                'application_step' => 4
            ]);
            
            $this->generateExamSlip($payment['application_id']);
            
            // Log activity
            $this->logPaymentAction($paymentId, 'manual_verification', "Payment manually verified by admin");
            
            // Remove used token
            unset($_SESSION['csrf_tokens'][$csrfToken]);
            
            echo json_encode(['success' => true, 'message' => 'Payment verified successfully']);
        } else {
            echo json_encode(['error' => 'Failed to verify payment']);
        }
    }
    
    /**
     * Generate exam slip (helper method)
     */
    private function generateExamSlip($applicationId) {
        require_once MODELS_PATH . '/application/ExamSlipModel.php';
        $examSlipModel = new ExamSlipModel();
        
        // Check if exam slip already exists
        $existing = $examSlipModel->getByApplicationId($applicationId);
        if ($existing) {
            error_log("Exam slip already exists for application: " . $applicationId);
            return $existing;
        }
        
        // Generate slip number
        $slipNumber = 'SLIP-' . date('Y') . '-' . str_pad($applicationId, 5, '0', STR_PAD_LEFT);
        
        // Get application for additional data
        $application = $this->applicationModel->find($applicationId);
        
        // Create exam slip
        $examSlipId = $examSlipModel->insert([
            'application_id' => $applicationId,
            'applicant_id' => $application['applicant_id'] ?? null,
            'slip_number' => $slipNumber,
            'exam_date' => $this->settingsModel->get('cbt_start_date', date('Y-m-d', strtotime('+7 days'))),
            'exam_time' => '10:00 AM',
            'reporting_time' => '8:00 AM',
            'exam_venue' => 'FCT College of Nursing Sciences, Gwagwalada (within UATH)',
            'seat_number' => 'SEAT-' . rand(100, 999),
            'instructions' => "1. Arrive at least 1 hour before examination time\n2. Bring this slip and a valid means of identification\n3. Bring writing materials (biro, pencil, eraser)\n4. Mobile phones and electronic devices are not allowed",
            'generated_at' => date('Y-m-d H:i:s'),
            'created_at' => date('Y-m-d H:i:s')
        ]);
        
        if ($examSlipId) {
            error_log("Exam slip created with ID: " . $examSlipId . " for application: " . $applicationId);
            return $examSlipModel->find($examSlipId);
        }
        
        error_log("Failed to create exam slip for application: " . $applicationId);
        return null;
    }
    
    /**
     * Get current applicant from session
     */
    private function getApplicant() {
        if (!isset($_SESSION['applicant_id'])) {
            return null;
        }
        
        require_once MODELS_PATH . '/application/ApplicantModel.php';
        $applicantModel = new ApplicantModel();
        return $applicantModel->find($_SESSION['applicant_id']);
    }
    
    /**
     * Set applicant session from payment (for webhook/redirect)
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
        $_SESSION['applicant_email'] = $applicant['email'] ?? '';
        $_SESSION['applicant_name'] = ($applicant['first_name'] ?? '') . ' ' . ($applicant['last_name'] ?? '');
        $_SESSION['applicant_login_time'] = time();
    }
    
    /**
     * Log payment action for admin audit
     */
    private function logPaymentAction($paymentId, $action, $description) {
        try {
            // Check if activity_logs table exists
            $stmt = $this->db->prepare("SHOW TABLES LIKE 'activity_logs'");
            $stmt->execute();
            
            if ($stmt->rowCount() > 0) {
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
            }
        } catch (Exception $e) {
            error_log("Failed to log payment action: " . $e->getMessage());
        }
    }
    
    /**
     * Override render method
     */
    protected function render($view = null, $data = []) {
        // Do NOT regenerate CSRF token here - use the one already set in $this->data
        // Only generate if not already set
        if (!isset($this->data['csrf_token'])) {
            $this->data['csrf_token'] = $this->csrfToken();
        }
        
        // Add flash messages
        $data['flash_success'] = $this->getFlash('success');
        $data['flash_error'] = $this->getFlash('error');
        
        // Merge with controller data
        $this->data = array_merge($this->data, $data);
        
        parent::render($view);
    }
}