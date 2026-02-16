<?php
/**
 * Application Base Controller
 * 
 * Base controller for all application-related controllers
 * 
 * @package FCT_CNS
 */

require_once CORE_PATH . '/Controller.php';

class ApplicationBaseController extends Controller {
    
    protected $applicantModel;
    protected $applicationModel;
    protected $paymentModel;
    protected $settingsModel;
    protected $sessionModel;
    
    /**
     * Constructor
     */
    public function __construct() {
        parent::__construct();
        
        // Load session fix helper for consistent session handling
        if (file_exists(APP_PATH . '/helpers/SessionFixHelper.php')) {
            require_once APP_PATH . '/helpers/SessionFixHelper.php';
            
            // Only initialize if session not already started
            if (session_status() === PHP_SESSION_NONE) {
                SessionFixHelper::init();
            }
        } else {
            // Fallback session start if helper doesn't exist
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }
        }
        
        // Debug session
        error_log("ApplicationBaseController - Session ID: " . session_id());
        error_log("ApplicationBaseController - Session data: " . print_r($_SESSION, true));
        
        // Load application models
        require_once MODELS_PATH . '/application/ApplicantModel.php';
        require_once MODELS_PATH . '/application/ApplicationModel.php';
        require_once MODELS_PATH . '/application/PaymentModel.php';
        require_once MODELS_PATH . '/application/SettingsModel.php';
        require_once MODELS_PATH . '/application/ApplicationSessionModel.php';
        
        $this->applicantModel = new ApplicantModel();
        $this->applicationModel = new ApplicationModel();
        $this->paymentModel = new PaymentModel();
        $this->settingsModel = new SettingsModel();
        $this->sessionModel = new ApplicationSessionModel();
        
        // Check portal status (except for certain pages)
        $this->checkPortalStatus();
    }
    
    /**
     * Check if applicant is logged in
     */
    protected function isApplicantLoggedIn() {
        return isset($_SESSION['applicant_id']) && !empty($_SESSION['applicant_id']);
    }
    
    /**
     * Get logged in applicant ID
     */
    protected function getApplicantId() {
        return $_SESSION['applicant_id'] ?? null;
    }
    
    /**
     * Get logged in applicant data
     */
    protected function getApplicant() {
        $applicantId = $this->getApplicantId();
        
        if (!$applicantId) {
            return null;
        }
        
        return $this->applicantModel->find($applicantId);
    }
    
    /**
     * Get applicant's application
     */
    protected function getApplication() {
        $applicantId = $this->getApplicantId();
        
        if (!$applicantId) {
            return null;
        }
        
        return $this->applicationModel->getByApplicantId($applicantId);
    }
    
    /**
     * Require applicant login
     * 
     * This method checks if the applicant is logged in and validates their session
     * 
     * @return bool True if logged in and session valid, false otherwise
     */
    protected function requireApplicantLogin() {
        // Check if applicant is logged in
        if (!$this->isApplicantLoggedIn()) {
            error_log("requireApplicantLogin: No applicant_id in session, redirecting to login");
            $this->flash('error', 'Please log in to continue.');
            $this->redirect('/applicant/login');
            return false;
        }
        
        error_log("requireApplicantLogin: Applicant ID " . $_SESSION['applicant_id'] . " is logged in");
        
        // Get session ID and applicant ID
        $sessionId = session_id();
        $applicantId = $this->getApplicantId();
        
        // Skip session validation if sessionModel isn't available or we're in a test environment
        if (!$this->sessionModel) {
            return true;
        }
        
        // Get session record
        $session = $this->sessionModel->getSession($sessionId);
        
        // Check if session exists in database
        if (!$session) {
            // Session record missing - could be expired or invalid
            error_log("requireApplicantLogin: Session record missing for session ID: " . $sessionId);
            $this->applicantLogout();
            $this->flash('error', 'Your session has expired. Please log in again.');
            $this->redirect('/applicant/login');
            return false;
        }
        
        // Verify session belongs to this applicant
        if ($session['applicant_id'] != $applicantId) {
            // Session mismatch - possible session hijacking attempt
            error_log("requireApplicantLogin: Session mismatch - Session applicant: " . $session['applicant_id'] . ", Logged in applicant: " . $applicantId);
            $this->applicantLogout();
            $this->flash('error', 'Session validation failed. Please log in again.');
            $this->redirect('/applicant/login');
            return false;
        }
        
        // Get current application for step tracking
        $application = $this->getApplication();
        $currentStep = $application ? ($application['application_step'] ?? 1) : 1;
        
        // Update session activity
        $this->sessionModel->updateActivity($sessionId, [
            'last_activity' => date('Y-m-d H:i:s'),
            'current_step' => $currentStep,
            'ip_address' => $_SERVER['REMOTE_ADDR'] ?? '',
            'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? ''
        ]);
        
        return true;
    }
    
    /**
     * Applicant login
     * 
     * @param array $applicant Applicant data array
     * @return bool True on success, false on failure
     */
    protected function applicantLogin($applicant) {
        // Ensure we have a valid applicant array
        if (!$applicant || !isset($applicant['id'])) {
            error_log('ApplicantLogin: Invalid applicant data provided');
            return false;
        }
        
        // Regenerate session ID for security
        session_regenerate_id(true);
        
        // Set session variables
        $_SESSION['applicant_id'] = $applicant['id'];
        $_SESSION['applicant_jamb'] = $applicant['jamb_number'] ?? '';
        $_SESSION['applicant_name'] = ($applicant['first_name'] ?? '') . ' ' . ($applicant['last_name'] ?? '');
        $_SESSION['applicant_email'] = $applicant['email'] ?? '';
        $_SESSION['applicant_login_time'] = time();
        $_SESSION['applicant_ip'] = $_SERVER['REMOTE_ADDR'] ?? '';
        $_SESSION['applicant_user_agent'] = $_SERVER['HTTP_USER_AGENT'] ?? '';
        
        // Create session record in database if sessionModel exists
        if ($this->sessionModel) {
            try {
                $this->sessionModel->createSession(
                    session_id(),
                    $applicant['id'],
                    $_SERVER['REMOTE_ADDR'] ?? '',
                    $_SERVER['HTTP_USER_AGENT'] ?? ''
                );
            } catch (Exception $e) {
                error_log('ApplicantLogin: Failed to create session record - ' . $e->getMessage());
            }
        }
        
        // Log activity if applicantModel exists
        if ($this->applicantModel && method_exists($this->applicantModel, 'logActivity')) {
            try {
                $this->applicantModel->logActivity(
                    $applicant['id'],
                    null,
                    'login',
                    'Applicant logged in successfully'
                );
            } catch (Exception $e) {
                error_log('ApplicantLogin: Failed to log activity - ' . $e->getMessage());
            }
        }
        
        return true;
    }
    
    /**
     * Applicant logout
     */
    protected function applicantLogout() {
        $applicantId = $this->getApplicantId();
        
        if ($applicantId && $this->applicantModel && method_exists($this->applicantModel, 'logActivity')) {
            try {
                // Log activity
                $this->applicantModel->logActivity(
                    $applicantId,
                    null,
                    'logout',
                    'Applicant logged out'
                );
            } catch (Exception $e) {
                error_log('ApplicantLogout: Failed to log activity - ' . $e->getMessage());
            }
        }
        
        // Remove session record from database
        if ($this->sessionModel) {
            try {
                $this->sessionModel->deleteSession(session_id());
            } catch (Exception $e) {
                error_log('ApplicantLogout: Failed to delete session - ' . $e->getMessage());
            }
        }
        
        // Clear all session variables
        $_SESSION = array();
        
        // Destroy the session cookie
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $params["path"], $params["domain"],
                $params["secure"], $params["httponly"]
            );
        }
        
        // Destroy the session
        session_destroy();
        
        // Start a new session for flash messages
        session_start();
        session_regenerate_id(true);
    }
    
    /**
     * Check portal status
     */
    protected function checkPortalStatus() {
        // Skip for certain routes
        $skipRoutes = [
            '/applicant/login',
            '/applicant/logout',
            '/applicant/register',
            '/applicant/verify-jamb',
            '/applicant/forgot-password',
            '/applicant/reset-password',
            '/applicant/verify-email',
            '/payment/remita-response',
            '/payment/remita-notification',
            '/payment/initiate',
            '/payment/verify',
            '/payment/check-status'
        ];
        
        $currentRoute = $_SERVER['REQUEST_URI'] ?? '';
        foreach ($skipRoutes as $route) {
            if (strpos($currentRoute, $route) !== false) {
                return;
            }
        }
        
        // Check if portal is open
        if ($this->settingsModel && !$this->settingsModel->isPortalOpen()) {
            $this->data['portal_closed'] = true;
            $this->data['portal_message'] = $this->settingsModel->getPortalMessage();
            
            // If logged in, still allow access to application
            if (!$this->isApplicantLoggedIn()) {
                $this->render('applications/portal-closed');
                exit;
            }
        }
    }
    
    /**
     * Get payment status
     */
    protected function getPaymentStatus($applicationId) {
        if (!$this->paymentModel) {
            return [
                'status' => 'none',
                'payment' => null
            ];
        }
        
        $payments = $this->paymentModel->getByApplicationId($applicationId);
        
        foreach ($payments as $payment) {
            if ($payment['status'] === 'success') {
                return [
                    'status' => 'success',
                    'payment' => $payment
                ];
            }
        }
        
        // Check for pending payment
        foreach ($payments as $payment) {
            if ($payment['status'] === 'pending') {
                return [
                    'status' => 'pending',
                    'payment' => $payment
                ];
            }
        }
        
        return [
            'status' => 'none',
            'payment' => null
        ];
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
        
        // Add applicant data if logged in
        if ($this->isApplicantLoggedIn()) {
            $data['applicant'] = $this->getApplicant();
            $data['application'] = $this->getApplication();
            
            // Add payment status
            if ($data['application'] && isset($data['application']['id'])) {
                $data['payment_status'] = $this->getPaymentStatus($data['application']['id']);
            } else {
                $data['payment_status'] = ['status' => 'none', 'payment' => null];
            }
            
            // Add pending payment from session if exists
            if (isset($_SESSION['pending_payment'])) {
                $data['pending_payment'] = $_SESSION['pending_payment'];
            }
        }
        
        // Add settings
        $data['settings'] = $this->settingsModel ? $this->settingsModel->getAllSettings() : [];
        
        // Format currency
        $data['format_currency'] = function($amount) {
            return '₦' . number_format($amount, 2);
        };
        
        // Merge with controller data
        $this->data = array_merge($this->data, $data);
        
        // Use application layout
        $this->layout = 'application';
        
        parent::render($view);
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
        
        return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
    }
    
    /**
     * Flash message helper
     */
    protected function flash($key, $message) {
        $_SESSION['flash'][$key] = $message;
    }
    
    /**
     * Get flash message
     */
    protected function getFlash($key) {
        if (isset($_SESSION['flash'][$key])) {
            $message = $_SESSION['flash'][$key];
            unset($_SESSION['flash'][$key]);
            return $message;
        }
        return null;
    }
}