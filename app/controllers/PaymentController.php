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
     */
    public function initiate() {
        // Set JSON header
        header('Content-Type: application/json');
        
        // Check if user is logged in
        if (!isset($_SESSION['applicant_id'])) {
            echo json_encode(['error' => 'Not logged in']);
            return;
        }
        
        $applicantId = $_SESSION['applicant_id'];
        
        // Load ApplicantModel
        require_once MODELS_PATH . '/application/ApplicantModel.php';
        $applicantModel = new ApplicantModel();
        
        // Get application
        $application = $this->applicationModel->getByApplicantId($applicantId);
        
        if (!$application) {
            echo json_encode(['error' => 'Application not found']);
            return;
        }
        
        // Get applicant details
        $applicant = $applicantModel->find($applicantId);
        
        if (!$applicant) {
            echo json_encode(['error' => 'Applicant not found']);
            return;
        }
        
        // Get application fee from settings
        $amount = $this->settingsModel->getApplicationFee();
        
        // Create payment record
        $payment = $this->paymentModel->createPayment(
            $application['id'],
            $applicantId,
            $amount
        );
        
        // Generate RRR from Remita
        $payerName = $applicant['first_name'] . ' ' . $applicant['last_name'];
        $result = $this->remitaModel->generateRRRRemita(
            $payment['order_id'],
            $amount,
            $payerName,
            $applicant['email'],
            $applicant['phone']
        );
        
        // Handle response
        if ($result['status'] === 'success') {
            // Update payment with RRR
            $this->paymentModel->updateRRR($payment['id'], $result['rrr']);
            
            // Create Remita transaction record
            $this->remitaModel->createTransaction(
                $payment['id'],
                $result['rrr'],
                $payment['order_id'],
                $amount
            );
            
            // Store in session
            $_SESSION['pending_payment'] = [
                'payment_id' => $payment['id'],
                'rrr' => $result['rrr'],
                'amount' => $amount,
                'created_at' => time()
            ];
            
            echo json_encode([
                'status' => 'success',
                'rrr' => $result['rrr'],
                'message' => 'RRR generated successfully',
                'payment_id' => $payment['id']
            ]);
        } else {
            error_log("Payment initiation failed: " . json_encode($result));
            
            echo json_encode([
                'status' => 'error',
                'message' => 'Failed to generate RRR. Please try again.'
            ]);
        }
    }
    
    /**
     * STEP B: Verify Payment (Called when user clicks "I've Paid, Verify")
     * URL: /payment/verify
     */
    public function verify() {
        header('Content-Type: application/json');
        
        $rrr = $_POST['rrr'] ?? $_GET['rrr'] ?? '';
        
        if (empty($rrr)) {
            echo json_encode(['error' => 'RRR is required']);
            return;
        }
        
        // Verify with Remita
        $verification = $this->remitaModel->verifyPayment($rrr);
        
        // Get payment record
        $payment = $this->paymentModel->getByRRR($rrr);
        
        if (!$payment) {
            echo json_encode(['error' => 'Payment record not found']);
            return;
        }
        
        // Handle verification result
        if ($verification['status'] === 'success') {
            // Update payment status
            $this->paymentModel->markAsSuccess($payment['id'], [
                'transaction_id' => $verification['payment_data']['transactionId'] ?? null,
                'payment_method' => 'remita',
                'payer_email' => $payment['email'] ?? null,
                'payer_name' => $payment['first_name'] . ' ' . $payment['last_name'] ?? null,
                'payment_details' => $verification['payment_data']
            ]);
            
            // Update transaction
            $transaction = $this->remitaModel->getByRRR($rrr);
            if ($transaction) {
                $this->remitaModel->updatePaymentData($transaction['id'], $verification['payment_data']);
                $this->remitaModel->updateStatus($transaction['id'], 'success');
            }
            
            // Update application step
            $this->applicationModel->updateStep($payment['application_id'], 4);
            
            // Generate exam slip
            $this->applicationModel->generateExamSlip($payment['application_id']);
            
            // Send payment confirmation email
            if (file_exists(APP_PATH . '/helpers/ApplicationEmailHelper.php')) {
                require_once APP_PATH . '/helpers/ApplicationEmailHelper.php';
                $emailHelper = new ApplicationEmailHelper();
                
                require_once MODELS_PATH . '/application/ApplicantModel.php';
                $applicantModel = new ApplicantModel();
                $applicant = $applicantModel->find($payment['applicant_id']);
                $application = $this->applicationModel->find($payment['application_id']);
                
                $emailHelper->sendPaymentConfirmation($applicant, $payment, $application);
            }
            
            // Clear pending payment from session
            unset($_SESSION['pending_payment']);
            
            echo json_encode([
                'status' => 'success',
                'message' => 'Payment verified successfully',
                'redirect' => '/apply/step/4'
            ]);
            
        } elseif ($verification['status'] === 'pending') {
            echo json_encode([
                'status' => 'pending',
                'message' => $verification['message'] ?? 'Payment is still processing'
            ]);
            
        } else {
            echo json_encode([
                'status' => 'failed',
                'message' => $verification['message'] ?? 'Payment verification failed'
            ]);
        }
    }
    
    /**
     * Remita response handler (after payment)
     */
    public function remitaResponse() {
        $rrr = $this->query('rrr', '');
        $status = $this->query('status', '');
        
        error_log("Remita Response: RRR={$rrr}, Status={$status}");
        
        if (empty($rrr)) {
            $this->flash('error', 'Invalid payment reference.');
            $this->redirect('/apply/step/3');
            return;
        }
        
        // Verify payment
        $verification = $this->remitaModel->checkStatus($rrr);
        
        if ($verification['status'] === 'success') {
            $transaction = $verification['transaction'];
            
            // Get payment record
            $payment = $this->paymentModel->getByRRR($rrr);
            
            if ($payment) {
                // Update payment status
                $this->paymentModel->markAsSuccess(
                    $payment['id'],
                    [
                        'transaction_id' => $transaction['rrr'],
                        'payment_method' => 'remita',
                        'payment_details' => $transaction['payment_data'] ?? []
                    ]
                );
                
                // Update application step
                $this->applicationModel->updateStep($payment['application_id'], 4);
                
                // Generate exam slip
                $this->applicationModel->generateExamSlip($payment['application_id']);
                
                // Set session for applicant if not logged in
                $this->setApplicantSessionFromPayment($payment);
                
                $this->flash('success', 'Payment successful! You can now download your exam slip.');
                $this->redirect('/apply/step/4');
            } else {
                $this->flash('error', 'Payment record not found.');
                $this->redirect('/apply/step/3');
            }
        } else {
            $this->flash('error', 'Payment verification failed. Please try again or contact support.');
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
        
        if (empty($rrr) || empty($transactionId)) {
            http_response_code(400);
            echo json_encode(['status' => 'error', 'message' => 'Missing required fields']);
            return;
        }
        
        // Find transaction
        $transaction = $this->remitaModel->getByRRR($rrr);
        
        if (!$transaction) {
            http_response_code(404);
            echo json_encode(['status' => 'error', 'message' => 'Transaction not found']);
            return;
        }
        
        // Update notification data
        $this->remitaModel->updateNotificationData($transaction['id'], $data);
        
        // Update transaction status based on Remita status
        if ($status === '00' || $status === 'success') {
            $this->remitaModel->updateStatus($transaction['id'], 'success');
            
            // Update payment record
            $payment = $this->paymentModel->find($transaction['payment_id']);
            if ($payment && $payment['status'] !== 'success') {
                $this->paymentModel->markAsSuccess(
                    $payment['id'],
                    [
                        'transaction_id' => $transactionId,
                        'payment_method' => 'remita',
                        'payment_details' => $data
                    ]
                );
                
                // Update application step
                $this->applicationModel->updateStep($payment['application_id'], 4);
                
                // Generate exam slip
                $this->applicationModel->generateExamSlip($payment['application_id']);
            }
        } else {
            $this->remitaModel->updateStatus($transaction['id'], 'failed');
            
            if ($transaction['payment_id']) {
                $this->paymentModel->markAsFailed($transaction['payment_id'], 'Remita notification: ' . $status);
            }
        }
        
        // Acknowledge receipt
        echo json_encode(['status' => 'success', 'message' => 'Notification received']);
    }
    
    /**
     * Check payment status (AJAX)
     */
    public function checkStatus() {
        // Only accept AJAX requests
        if (!$this->isAjax()) {
            http_response_code(403);
            echo json_encode(['error' => 'Forbidden']);
            return;
        }
        
        $rrr = $this->query('rrr', '');
        
        if (empty($rrr)) {
            echo json_encode(['error' => 'RRR required']);
            return;
        }
        
        $verification = $this->remitaModel->checkStatus($rrr);
        
        echo json_encode($verification);
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
        
        $paymentId = $this->input('payment_id');
        
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
        $_SESSION['applicant_jamb'] = $applicant['jamb_number'];
        $_SESSION['applicant_name'] = $applicant['first_name'] . ' ' . $applicant['last_name'];
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
     * Check if request is AJAX
     */
    private function isAjax() {
        return !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && 
               strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';
    }
    
    /**
     * Validate CSRF token
     */
    private function validateCsrfToken() {
        $token = $this->input('csrf_token') ?? $this->input('_token');
        return $token === ($_SESSION['csrf_token'] ?? '');
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