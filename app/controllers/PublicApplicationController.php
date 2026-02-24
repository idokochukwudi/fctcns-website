<?php
/**
 * Public Application Controller
 * 
 * Handles public-facing application processes
 * COMPREHENSIVE SECURITY FIXES:
 * - Step access validation prevents URL manipulation
 * - JAMB verification lock after first verification
 * - Payment completion locks all previous steps
 * - Session fingerprint to prevent hijacking
 * - Session timeout (30 minutes)
 * - CSRF protection on all state-changing operations
 * - Application ownership validation
 * - Prevention of multiple applications per session
 * - O'Level check after result combination
 * 
 * FIXED: RemitaModel initialization in constructor
 * FIXED: Payment initiation - removed non-existent 'raw_rrr' column
 * 
 * @package FCT_CNS
 */

require_once __DIR__ . '/ApplicationBaseController.php';

class PublicApplicationController extends ApplicationBaseController {
    
    private $jambModel;
    private $termsModel;
    private $remitaModel; // Added property declaration
    
    /**
     * Session timeout in seconds (30 minutes)
     */
    const SESSION_TIMEOUT = 1800;
    
    /**
     * Steps that require payment
     */
    const PAID_STEPS = [4, 5];
    
    /**
     * Required O'Level subjects
     */
    const REQUIRED_OLEVEL_SUBJECTS = ['english', 'mathematics', 'biology', 'chemistry', 'physics'];
    
    /**
     * Minimum credit grade
     */
    const MINIMUM_CREDIT_GRADE = 'C6';
    
    /**
     * Constructor - Initialize all models
     */
    public function __construct() {
        parent::__construct();
        
        // Load all required models
        $this->loadModels();
        
        // Set layout
        $this->layout = 'application';
        
        // Initialize security for all requests
        $this->initSecurity();
    }
    
    /**
     * Load all required models
     */
    private function loadModels() {
        try {
            // Define model paths
            $modelPaths = [
                'JambCandidateModel' => MODELS_PATH . '/JambCandidateModel.php',
                'TermsModel' => MODELS_PATH . '/application/TermsModel.php',
                'OlevelResultModel' => MODELS_PATH . '/application/OlevelResultModel.php',
                'ApplicationDocumentModel' => MODELS_PATH . '/application/ApplicationDocumentModel.php',
                'ExamSlipModel' => MODELS_PATH . '/application/ExamSlipModel.php',
                'RemitaModel' => MODELS_PATH . '/application/RemitaModel.php'
            ];
            
            // Load each model file
            foreach ($modelPaths as $modelName => $path) {
                if (file_exists($path)) {
                    require_once $path;
                    error_log("Loaded model: $modelName from $path");
                } else {
                    error_log("WARNING: Model file not found: $path");
                }
            }
            
            // Initialize all models
            $this->jambModel = new JambCandidateModel();
            $this->termsModel = new TermsModel();
            $this->remitaModel = new RemitaModel(); // CRITICAL: Initialize remitaModel
            
            // Verify remitaModel is initialized
            if ($this->remitaModel) {
                error_log("✓ RemitaModel initialized successfully in PublicApplicationController");
            } else {
                error_log("✗ RemitaModel is NULL after initialization");
            }
            
        } catch (Exception $e) {
            error_log("ERROR loading models: " . $e->getMessage());
        }
    }
    
    /**
     * Initialize security measures for all requests
     */
    private function initSecurity() {
        // Start session if not started
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        // Set session fingerprint if not exists
        if (!isset($_SESSION['security_fingerprint'])) {
            $_SESSION['security_fingerprint'] = $this->generateSessionFingerprint();
            $_SESSION['created_at'] = time();
            $_SESSION['last_activity'] = time();
        }
        
        // Validate session for authenticated users
        if (isset($_SESSION['applicant_id'])) {
            // Check session fingerprint
            if (!$this->validateSessionFingerprint()) {
                error_log("SECURITY: Session fingerprint mismatch - User: " . $_SESSION['applicant_id']);
                $this->logout();
                $this->redirect('/applicant/login?error=session_invalid');
                return;
            }
            
            // Check session timeout
            if (!$this->checkSessionTimeout()) {
                error_log("SECURITY: Session timeout - User: " . $_SESSION['applicant_id']);
                $this->logout();
                $this->redirect('/applicant/login?error=session_expired');
                return;
            }
            
            // Update last activity
            $_SESSION['last_activity'] = time();
        }
    }
    
    /**
     * Generate unique session fingerprint to prevent hijacking
     */
    private function generateSessionFingerprint() {
        return hash('sha256', 
            ($_SERVER['HTTP_USER_AGENT'] ?? '') . 
            ($_SERVER['REMOTE_ADDR'] ?? '') . 
            session_id()
        );
    }
    
    /**
     * Validate session fingerprint
     */
    private function validateSessionFingerprint() {
        if (!isset($_SESSION['security_fingerprint'])) {
            return false;
        }
        
        $current = $this->generateSessionFingerprint();
        return hash_equals($_SESSION['security_fingerprint'], $current);
    }
    
    /**
     * Check session timeout
     */
    private function checkSessionTimeout() {
        if (!isset($_SESSION['last_activity'])) {
            return true;
        }
        
        if (time() - $_SESSION['last_activity'] > self::SESSION_TIMEOUT) {
            return false;
        }
        
        return true;
    }
    
    /**
     * Validate CSRF token - UPDATED to work with SecurityHelper
     * 
     * @param string|null $token Optional token to validate
     * @return bool
     */
    protected function validateCsrfToken($token = null) {
        // If token not provided, get from request
        if ($token === null) {
            $token = $_POST['csrf_token'] ?? $_GET['csrf_token'] ?? '';
        }
        
        // Use SecurityHelper if available
        if (class_exists('SecurityHelper')) {
            return SecurityHelper::validateCsrfToken($token);
        }
        
        // Fallback to Session class
        if (class_exists('Session')) {
            return Session::validateCSRFTokenMulti($token);
        }
        
        // Final fallback - simple session check
        if (isset($_SESSION['csrf_tokens'][$token])) {
            $tokenTime = $_SESSION['csrf_tokens'][$token];
            if (time() - $tokenTime < 3600) {
                return true;
            }
            unset($_SESSION['csrf_tokens'][$token]);
        }
        
        return false;
    }
    
    /**
     * Generate CSRF token - UPDATED to work with SecurityHelper
     * 
     * @return string
     */
    protected function csrfToken() {
        // Use SecurityHelper if available
        if (class_exists('SecurityHelper')) {
            return SecurityHelper::getCsrfToken();
        }
        
        // Fallback to Session class
        if (class_exists('Session')) {
            return Session::generateCSRFTokenMulti();
        }
        
        // Final fallback
        if (!isset($_SESSION['csrf_tokens'])) {
            $_SESSION['csrf_tokens'] = [];
        }
        
        $token = bin2hex(random_bytes(32));
        $_SESSION['csrf_tokens'][$token] = time();
        
        return $token;
    }
    
    /**
     * Validate O'Level results for minimum credits
     * 
     * @param array $olevelResults Array of O'Level results
     * @return array ['success' => bool, 'message' => string, 'missing' => array]
     */
    private function validateOlevelCredits($olevelResults) {
        if (empty($olevelResults)) {
            return [
                'success' => false,
                'message' => 'No O\'Level results provided',
                'missing' => self::REQUIRED_OLEVEL_SUBJECTS
            ];
        }
        
        // Track best grades across sittings
        $bestGrades = [];
        $gradeOrder = ['A1', 'B2', 'B3', 'C4', 'C5', 'C6', 'D7', 'E8', 'F9'];
        
        foreach ($olevelResults as $result) {
            foreach (self::REQUIRED_OLEVEL_SUBJECTS as $subject) {
                $gradeKey = $subject . '_grade';
                if (!empty($result[$gradeKey])) {
                    $grade = $result[$gradeKey];
                    
                    // If we don't have a grade for this subject yet, or current grade is better
                    if (!isset($bestGrades[$subject]) || 
                        array_search($grade, $gradeOrder) < array_search($bestGrades[$subject], $gradeOrder)) {
                        $bestGrades[$subject] = $grade;
                    }
                }
            }
        }
        
        // Check which subjects have credit passes (C6 or better)
        $creditGrades = array_slice($gradeOrder, 0, 6); // A1 through C6
        $missingSubjects = [];
        $passedSubjects = [];
        
        foreach (self::REQUIRED_OLEVEL_SUBJECTS as $subject) {
            if (isset($bestGrades[$subject]) && in_array($bestGrades[$subject], $creditGrades)) {
                $passedSubjects[] = $subject;
            } else {
                $missingSubjects[] = $subject;
            }
        }
        
        if (empty($missingSubjects)) {
            return [
                'success' => true,
                'message' => 'All required subjects have credit passes',
                'grades' => $bestGrades
            ];
        } else {
            return [
                'success' => false,
                'message' => 'Missing credit passes in: ' . implode(', ', $missingSubjects),
                'missing' => $missingSubjects,
                'grades' => $bestGrades
            ];
        }
    }
    
    /**
     * Validate and re-index O'Level results to ensure sequential indices
     * 
     * @param array $olevelData Raw O'Level data from POST
     * @return array Re-indexed and validated O'Level data
     */
    private function validateAndReindexOlevelData($olevelData) {
        $validatedResults = [];
        $index = 0;
        
        if (!is_array($olevelData)) {
            return $validatedResults;
        }
        
        foreach ($olevelData as $key => $result) {
            // Skip if required fields are missing
            if (empty($result['exam_type']) || empty($result['exam_year'])) {
                continue;
            }
            
            // Validate grades - ensure they're in the allowed list
            $allowedGrades = ['A1', 'B2', 'B3', 'C4', 'C5', 'C6', 'D7', 'E8', 'F9'];
            $gradeFields = ['english_grade', 'mathematics_grade', 'biology_grade', 'chemistry_grade', 'physics_grade'];
            
            foreach ($gradeFields as $field) {
                if (!empty($result[$field]) && !in_array($result[$field], $allowedGrades)) {
                    // Invalid grade, set to empty
                    $result[$field] = '';
                }
            }
            
            // Store with sequential index
            $validatedResults[$index] = [
                'exam_type' => $result['exam_type'],
                'exam_year' => $result['exam_year'],
                'exam_number' => $result['exam_number'] ?? '',
                'sitting' => $result['sitting'] ?? '1st',
                'english_grade' => $result['english_grade'] ?? '',
                'mathematics_grade' => $result['mathematics_grade'] ?? '',
                'biology_grade' => $result['biology_grade'] ?? '',
                'chemistry_grade' => $result['chemistry_grade'] ?? '',
                'physics_grade' => $result['physics_grade'] ?? ''
            ];
            
            $index++;
        }
        
        // Re-index to ensure they are sequential 0,1,2...
        return array_values($validatedResults);
    }
    
    /**
     * Validate step access based on application state
     * 
     * @param array $application Application data
     * @param int $requestedStep The step being accessed
     * @param string $method Called from which method (for logging)
     * @return bool True if access is allowed
     */
    private function validateStepAccess($application, $requestedStep, $method = 'unknown') {
        // If no application, only step 1 is allowed
        if (!$application) {
            $allowed = ($requestedStep == 1);
            if (!$allowed) {
                error_log("SECURITY: Step access denied - No application, requested step: $requestedStep, method: $method");
            }
            return $allowed;
        }
        
        // SECURITY: Check if payment has been made
        $hasPaid = false;
        if (!empty($application['id'])) {
            $hasPaid = $this->paymentModel->hasSuccessfulPayment($application['id']);
        }
        
        // If payment made, ONLY allow steps 4 and 5
        if ($hasPaid) {
            $allowed = in_array($requestedStep, self::PAID_STEPS);
            if (!$allowed) {
                error_log("SECURITY: Attempted to access pre-payment step after payment - User: " . 
                         ($_SESSION['applicant_id'] ?? 'unknown') . ", Step: $requestedStep, Application: " . $application['id']);
            }
            return $allowed;
        }
        
        // For unpaid applications, validate based on completion
        switch ($requestedStep) {
            case 1:
                // Step 1 always accessible if not paid, but show warning if JAMB already verified
                return true;
                
            case 2:
                // Can access step 2 if JAMB is verified
                $allowed = !empty($application['jamb_number']);
                if (!$allowed) {
                    error_log("SECURITY: Attempted to access step 2 without JAMB verification - Application: " . $application['id']);
                }
                return $allowed;
                
            case 3:
                // Can access step 3 if JAMB is verified AND O'Level credits are sufficient
                if (empty($application['jamb_number'])) {
                    error_log("SECURITY: Attempted to access step 3 without JAMB verification - Application: " . $application['id']);
                    $_SESSION['flash_error'] = 'Please verify your JAMB number first.';
                    return false;
                }

                // FIX 2b: Check O'Level credits using the detailed summary method
                require_once MODELS_PATH . '/application/OlevelResultModel.php';
                $olevelModel   = new OlevelResultModel();
                $creditSummary = $olevelModel->getCreditCheckSummary($application['id']);

                if (!$creditSummary['meets_requirement']) {
                    error_log("O'LEVEL GATE BLOCKED: Application " . $application['id'] . " - " . $creditSummary['message']);

                    // Build a detailed, user-friendly flash message
                    $blockMsg = '⚠ You cannot proceed to payment. O\'Level requirement not met. '
                              . 'You have ' . $creditSummary['credits_achieved'] . '/5 required credits. '
                              . $creditSummary['message'];

                    $_SESSION['flash_error']     = $blockMsg;
                    $_SESSION['olevel_error']    = $creditSummary['message'];
                    $_SESSION['olevel_missing']  = $creditSummary['missing_subjects'];
                    $_SESSION['olevel_failed']   = $creditSummary['failed_subjects'];
                    $_SESSION['olevel_credits']  = $creditSummary['credits_achieved'];
                    $_SESSION['olevel_summary']  = $creditSummary;
                    return false;
                }

                return true;
                
            case 4:
            case 5:
                // Paid steps - never allowed without payment
                error_log("SECURITY: Attempted to access paid step without payment - Application: " . $application['id'] . ", Step: $requestedStep");
                return false;
                
            default:
                error_log("SECURITY: Invalid step requested: $requestedStep");
                return false;
        }
    }
    
    /**
     * Get application state as readable string
     */
    private function getApplicationState($application) {
        if (!$application) {
            return 'NO_APPLICATION';
        }
        
        // Check payment first (highest priority)
        if (!empty($application['id'])) {
            $hasPaid = $this->paymentModel->hasSuccessfulPayment($application['id']);
            if ($hasPaid) {
                // Check if exam slip generated
                if ($this->hasExamSlip($application['id'])) {
                    return 'EXAM_SLIP_GENERATED';
                }
                return 'PAYMENT_COMPLETE';
            }
            
            // Check for pending payment
            $payments = $this->paymentModel->getByApplicationId($application['id']);
            foreach ($payments as $payment) {
                if ($payment['status'] === 'pending') {
                    return 'PAYMENT_PENDING';
                }
            }
        }
        
        // Check O'Level credits using new summary method
        require_once MODELS_PATH . '/application/OlevelResultModel.php';
        $olevelModel = new OlevelResultModel();
        $creditSummary = $olevelModel->getCreditCheckSummary($application['id']);
        
        if (!$creditSummary['meets_requirement']) {
            return 'OLEVEL_INCOMPLETE';
        }
        
        // Check form completion
        if (!empty($application['date_of_birth']) && 
            !empty($application['phone']) && 
            !empty($application['address']) &&
            !empty($application['program_choice_1'])) {
            return 'FORM_COMPLETE';
        }
        
        // Check JAMB verification
        if (!empty($application['jamb_number'])) {
            return 'JAMB_VERIFIED';
        }
        
        return 'JAMB_PENDING';
    }
    
    /**
     * Redirect to appropriate step based on application state
     * 
     * @param array $application Application data
     */
    private function redirectToProperStep($application) {
        $state = $this->getApplicationState($application);
        
        switch ($state) {
            case 'EXAM_SLIP_GENERATED':
                $this->redirect('/apply/step/4'); // Step 4 shows exam slip
                break;
                
            case 'PAYMENT_COMPLETE':
                $this->redirect('/apply/step/4');
                break;
                
            case 'PAYMENT_PENDING':
            case 'FORM_COMPLETE':
                $this->redirect('/apply/step/3');
                break;
                
            case 'OLEVEL_INCOMPLETE':
                $_SESSION['flash_warning'] = 'Please complete your O\'Level results with at least 5 credits including English, Mathematics, Biology, Chemistry, and Physics.';
                $this->redirect('/apply/step/2');
                break;
                
            case 'JAMB_VERIFIED':
                $this->redirect('/apply/step/2');
                break;
                
            case 'JAMB_PENDING':
            default:
                $this->redirect('/apply/step/1');
                break;
        }
    }
    
    /**
     * Check if exam slip exists for application
     * 
     * @param int $applicationId
     * @return bool
     */
    private function hasExamSlip($applicationId) {
        try {
            require_once MODELS_PATH . '/application/ExamSlipModel.php';
            $examSlipModel = new ExamSlipModel();
            $examSlip = $examSlipModel->getByApplicationId($applicationId);
            return !empty($examSlip);
        } catch (Exception $e) {
            error_log("Error checking exam slip: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Check if applicant is logged in with security validation
     */
    protected function isApplicantLoggedIn() {
        if (!isset($_SESSION['applicant_id']) || empty($_SESSION['applicant_id'])) {
            return false;
        }
        
        // Validate session fingerprint
        if (!$this->validateSessionFingerprint()) {
            error_log("SECURITY: Session fingerprint validation failed for user: " . $_SESSION['applicant_id']);
            $this->logout();
            return false;
        }
        
        // Check session timeout
        if (!$this->checkSessionTimeout()) {
            error_log("SECURITY: Session timeout for user: " . $_SESSION['applicant_id']);
            $this->logout();
            return false;
        }
        
        return true;
    }
    
    // ============================================
    // APPLICATION LANDING PAGE
    // ============================================
    
    /**
     * Show application landing page - FIXED: Redirects logged-in users appropriately
     */
    public function landing() {
        // Initialize security
        $this->initSecurity();
        
        // Check if user is already logged in
        if ($this->isApplicantLoggedIn()) {
            // If logged in, check application progress
            $application = $this->getApplication();
            $this->redirectToProperStep($application);
            return;
        }
        
        // Not logged in - show landing page with register button
        $settings = $this->settingsModel->getAllSettings();
        
        $this->data = array_merge($this->data, [
            'pageTitle' => 'Application Portal - FCT College of Nursing Sciences',
            'settings' => $settings,
            'portal_open' => $this->settingsModel->isPortalOpen()
        ]);
        
        $this->render('applications/index');
    }

    // ============================================
    // STEP 1: ACCOUNT CREATION / REGISTRATION
    // ============================================

    /**
     * Show registration form (Step 1 - New Flow)
     */
    public function showRegistration() {
        // Initialize security
        $this->initSecurity();
        
        // Check if portal is open
        if (!$this->settingsModel->isPortalOpen()) {
            $this->data['portal_closed'] = true;
            $this->data['portal_message'] = $this->settingsModel->getPortalMessage();
            $this->render('applications/portal-closed');
            return;
        }
        
        // If already logged in, redirect to appropriate step
        if ($this->isApplicantLoggedIn()) {
            $application = $this->getApplication();
            $this->redirectToProperStep($application);
            return;
        }
        
        // Get terms for acceptance
        $terms = $this->termsModel->getForAcceptance();
        
        $this->data = array_merge($this->data, [
            'pageTitle' => 'Create Account - Step 1',
            'terms' => $terms,
            'has_accepted_terms' => isset($_SESSION['accepted_terms']) ? $_SESSION['accepted_terms'] : false,
            'csrf_token' => $this->csrfToken()
        ]);
        
        $this->render('applications/register');
    }

    /**
     * Process registration (Step 1 - New Flow) - FIXED with CSRF validation
     * Redirects to email sent page, not verification with token
     */
    public function processRegistration() {
        // Initialize security
        $this->initSecurity();
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/apply/register');
            return;
        }
        
        // Validate CSRF
        if (!$this->validateCsrfToken()) {
            $_SESSION['flash_error'] = 'Security token expired. Please try again.';
            $this->redirect('/apply/register');
            return;
        }
        
        // Get form data
        $email = trim($_POST['email'] ?? '');
        $phone = trim($_POST['phone'] ?? '');
        $password = $_POST['password'] ?? '';
        $confirm = $_POST['confirm_password'] ?? '';
        $terms = isset($_POST['terms']);
        
        // Validation
        $errors = [];
        
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'A valid email address is required';
        }
        
        if (empty($phone) || !preg_match('/^[0-9]{11}$/', $phone)) {
            $errors[] = 'Valid 11-digit phone number is required';
        }
        
        if (strlen($password) < 8) {
            $errors[] = 'Password must be at least 8 characters';
        }
        
        if ($password !== $confirm) {
            $errors[] = 'Passwords do not match';
        }
        
        if (!$terms) {
            $errors[] = 'You must accept the terms and conditions';
        }
        
        // Check if email already exists
        $existing = $this->applicantModel->findByEmail($email);
        if ($existing) {
            $errors[] = 'Email address is already registered';
        }
        
        // Check if phone already exists
        $existingPhone = $this->applicantModel->findByPhone($phone);
        if ($existingPhone) {
            $errors[] = 'Phone number is already registered';
        }
        
        if (!empty($errors)) {
            $_SESSION['flash_error'] = implode('<br>', $errors);
            $this->redirect('/apply/register');
            return;
        }
        
        try {
            // Create verification token
            $verificationToken = bin2hex(random_bytes(32));
            
            // Create applicant
            $applicantId = $this->applicantModel->insert([
                'email' => $email,
                'phone' => $phone,
                'password' => password_hash($password, PASSWORD_DEFAULT),
                'verification_token' => $verificationToken,
                'email_verified' => 0,
                'status' => 'active',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ]);
            
            if (!$applicantId) {
                throw new Exception('Failed to create account');
            }
            
            // Send verification email
            $this->sendVerificationEmail($email, $verificationToken);
            
            // Store email in session for display
            $_SESSION['registration_email'] = $email;
            
            // Redirect to email sent page
            $this->redirect('/apply/verify-email');
            
        } catch (Exception $e) {
            error_log("Registration error: " . $e->getMessage());
            $_SESSION['flash_error'] = 'An error occurred. Please try again.';
            $this->redirect('/apply/register');
        }
    }

    /**
     * Verify email with token or show email sent page - FIXED
     * Now properly shows verification success page before JAMB verification
     */
    public function verifyEmail() {
        // Initialize security
        $this->initSecurity();
        
        // Get token from URL
        $token = $_GET['token'] ?? '';
        
        // Get email from URL or session
        $email = $_GET['email'] ?? $_SESSION['registration_email'] ?? '';
        
        // Debug log
        error_log("=== verifyEmail called ===");
        error_log("Token: " . $token);
        error_log("Email from URL: " . ($_GET['email'] ?? 'not set'));
        error_log("Email from session: " . ($_SESSION['registration_email'] ?? 'not set'));
        
        // If no token, show the "check your email" page
        if (empty($token)) {
            // Show email sent page
            $this->data['email'] = $email;
            $this->data['pageTitle'] = 'Verify Your Email';
            $this->data['email_sent'] = true;
            
            $this->render('applications/verify-email');
            return;
        }
        
        // Token provided - verify the email
        $applicant = $this->applicantModel->findByVerificationToken($token);
        
        if (!$applicant) {
            // Token not found or expired
            error_log("No applicant found with token: " . $token);
            
            if (!empty($email)) {
                // Try to find by email
                $applicantByEmail = $this->applicantModel->findByEmail($email);
                
                if ($applicantByEmail && $applicantByEmail['email_verified'] == 1) {
                    // Email already verified, just log them in
                    error_log("Email already verified for: " . $email . " - logging in");
                    
                    // Auto-login the applicant
                    $_SESSION['applicant_id'] = $applicantByEmail['id'];
                    $_SESSION['applicant_email'] = $applicantByEmail['email'];
                    $_SESSION['applicant_name'] = ($applicantByEmail['first_name'] ?? '') . ' ' . ($applicantByEmail['last_name'] ?? '');
                    
                    // Clear registration email from session
                    unset($_SESSION['registration_email']);
                    
                    // SHOW VERIFICATION SUCCESS PAGE
                    $this->data['pageTitle'] = 'Email Verified Successfully';
                    $this->data['verified'] = true;
                    $this->data['applicant_name'] = $_SESSION['applicant_name'];
                    $this->data['applicant'] = $applicantByEmail;
                    $this->data['email'] = $applicantByEmail['email'];
                    $this->render('applications/verification-success');
                    return;
                }
            }
            
            // Show error page
            $this->data['error'] = 'Invalid or expired verification link. Please request a new verification email.';
            $this->data['resend_email'] = $email;
            $this->data['pageTitle'] = 'Verification Failed';
            $this->render('applications/verify-email');
            return;
        }
        
        error_log("Found applicant: ID=" . $applicant['id'] . ", Email=" . $applicant['email'] . ", Verified=" . $applicant['email_verified']);
        
        // Check if already verified
        if ($applicant['email_verified'] == 1) {
            error_log("Applicant already verified: " . $applicant['id']);
            
            // Auto-login the applicant
            $_SESSION['applicant_id'] = $applicant['id'];
            $_SESSION['applicant_email'] = $applicant['email'];
            $_SESSION['applicant_name'] = ($applicant['first_name'] ?? '') . ' ' . ($applicant['last_name'] ?? '');
            
            // Clear registration email from session
            unset($_SESSION['registration_email']);
            
            // SHOW VERIFICATION SUCCESS PAGE
            $this->data['pageTitle'] = 'Email Verified Successfully';
            $this->data['verified'] = true;
            $this->data['applicant_name'] = $_SESSION['applicant_name'];
            $this->data['applicant'] = $applicant;
            $this->data['email'] = $applicant['email'];
            $this->render('applications/verification-success');
            return;
        }
        
        // Update applicant as verified
        $updated = $this->applicantModel->update(
            [
                'email_verified' => 1,
                'verification_token' => null,
                'email_verified_at' => date('Y-m-d H:i:s')
            ],
            'id = :id',
            ['id' => $applicant['id']]
        );
        
        if ($updated) {
            error_log("Successfully verified applicant ID: " . $applicant['id']);
            
            // Auto-login the applicant
            $_SESSION['applicant_id'] = $applicant['id'];
            $_SESSION['applicant_email'] = $applicant['email'];
            $_SESSION['applicant_name'] = ($applicant['first_name'] ?? '') . ' ' . ($applicant['last_name'] ?? '');
            
            // Clear registration email from session
            unset($_SESSION['registration_email']);
            
            // SHOW VERIFICATION SUCCESS PAGE
            $this->data['pageTitle'] = 'Email Verified Successfully';
            $this->data['verified'] = true;
            $this->data['applicant_name'] = $_SESSION['applicant_name'];
            $this->data['applicant'] = $applicant;
            $this->data['email'] = $applicant['email'];
            $this->render('applications/verification-success');
            return;
            
        } else {
            // Failed to update
            error_log("Failed to update applicant ID: " . $applicant['id']);
            $this->data['error'] = 'Failed to verify email. Please try again.';
            $this->data['resend_email'] = $applicant['email'];
            $this->data['pageTitle'] = 'Verification Failed';
            $this->render('applications/verify-email');
        }
    }

    /**
     * Resend verification email (Step 1 - New Flow) - FIXED
     */
    public function resendVerification() {
        // Initialize security
        $this->initSecurity();
        
        // Get email from query string or session
        $email = $_GET['email'] ?? $_SESSION['registration_email'] ?? '';
        
        error_log("Resend verification called with email: " . $email);
        
        if (empty($email)) {
            $_SESSION['flash_error'] = 'Email address not found. Please register again.';
            $this->redirect('/apply/register');
            return;
        }
        
        // Find applicant by email
        $applicant = $this->applicantModel->findByEmail($email);
        
        if ($applicant) {
            if ($applicant['email_verified'] == 1) {
                // Already verified
                $_SESSION['flash_success'] = 'Your email is already verified. Please login.';
                $this->redirect('/applicant/login');
                return;
            }
            
            // Generate new token
            $newToken = bin2hex(random_bytes(32));
            
            // Update applicant with new token
            $this->applicantModel->update(
                ['verification_token' => $newToken],
                'id = :id',
                ['id' => $applicant['id']]
            );
            
            // Resend email
            $this->sendVerificationEmail($email, $newToken);
            
            // Store email in session for display
            $_SESSION['registration_email'] = $email;
            $_SESSION['flash_success'] = 'Verification email has been resent. Please check your inbox.';
            
            // Redirect back to verification page WITH email parameter
            $this->redirect('/apply/verify-email?email=' . urlencode($email));
            
        } else {
            // Applicant not found with this email
            $_SESSION['flash_error'] = 'Email address not found in our records. Please register again.';
            $this->redirect('/apply/register');
        }
    }

    /**
     * Send verification email - UPDATED with resend link
     */
    private function sendVerificationEmail($email, $token) {
        $verificationLink = BASE_URL . '/apply/verify-email?token=' . $token;
        $resendLink = BASE_URL . '/apply/resend-verification?email=' . urlencode($email);
        
        $subject = "Verify Your Email - FCT College of Nursing Sciences";
        
        $message = "
        <!DOCTYPE html>
        <html>
        <head>
            <style>
                body { font-family: Arial, sans-serif; line-height: 1.6; }
                .container { max-width: 600px; margin: 0 auto; padding: 20px; }
                .header { background: #6B4E9B; color: white; padding: 20px; text-align: center; }
                .content { padding: 20px; background: #f9f9f9; }
                .button { display: inline-block; padding: 10px 20px; background: #6B4E9B; color: white; text-decoration: none; border-radius: 5px; }
                .footer { text-align: center; padding: 20px; color: #666; font-size: 12px; }
                .info { background: #e8f4fd; padding: 15px; border-radius: 5px; margin: 20px 0; }
            </style>
        </head>
        <body>
            <div class='container'>
                <div class='header'>
                    <h2>FCT College of Nursing Sciences</h2>
                    <p>Email Verification</p>
                </div>
                <div class='content'>
                    <h3>Hello!</h3>
                    <p>Thank you for registering. Please verify your email address by clicking the button below:</p>
                    
                    <p style='text-align: center;'>
                        <a href='{$verificationLink}' class='button'>Verify Email Address</a>
                    </p>
                    
                    <div class='info'>
                        <p><strong>Didn't receive this email?</strong></p>
                        <p>If the button doesn't work, copy and paste this link into your browser:</p>
                        <p style='word-break: break-all;'>{$verificationLink}</p>
                        
                        <p style='margin-top: 20px;'>
                            <a href='{$resendLink}'>Click here to resend verification email</a>
                        </p>
                    </div>
                    
                    <p><strong>Note:</strong> This link will expire in 24 hours.</p>
                </div>
                <div class='footer'>
                    <p>&copy; " . date('Y') . " FCT College of Nursing Sciences</p>
                    <p>This is an automated message, please do not reply.</p>
                </div>
            </div>
        </body>
        </html>
        ";
        
        // Use your email helper
        if (file_exists(APP_PATH . '/helpers/EmailHelper.php')) {
            require_once APP_PATH . '/helpers/EmailHelper.php';
            $emailHelper = new EmailHelper();
            $emailHelper->sendEmail($email, $subject, $message);
        } else {
            // Fallback - log the email
            error_log("Verification email would be sent to: $email with link: $verificationLink");
        }
    }

    // ============================================
    // STEP 2: APPLICATION FORM
    // ============================================

    /**
     * Show application form (Step 2 - New Flow)
     */
    public function showApplicationForm() {
        // Initialize security
        $this->initSecurity();
        
        // Check if logged in
        if (!$this->isApplicantLoggedIn()) {
            $_SESSION['flash_error'] = 'Please login to continue';
            $this->redirect('/applicant/login');
            return;
        }
        
        // Check registration complete (email verified)
        if (!$this->checkRegistrationComplete()) {
            return; // checkRegistrationComplete already redirects
        }
        
        $applicantId = $_SESSION['applicant_id'];
        $applicant = $this->applicantModel->find($applicantId);
        
        if (!$applicant) {
            $_SESSION['flash_error'] = 'Applicant not found';
            $this->redirect('/applicant/login');
            return;
        }
        
        if (!$applicant['email_verified']) {
            $_SESSION['flash_error'] = 'Please verify your email first';
            $this->redirect('/apply/verify-email');
            return;
        }
        
        // Get application
        $application = $this->applicationModel->getByApplicantId($applicantId);
        
        if (!$application) {
            $_SESSION['flash_error'] = 'Application not found. Please start over.';
            $this->redirect('/apply/step/1');
            return;
        }
        
        // SECURITY: Validate step access
        if (!$this->validateStepAccess($application, 2, 'showApplicationForm')) {
            $this->redirectToProperStep($application);
            return;
        }
        
        // SECURITY: If JAMB already verified, show message but allow access
        if (!empty($application['jamb_number'])) {
            $this->data['jamb_verified'] = true;
            $this->data['jamb_number'] = $application['jamb_number'];
        }
        
        // Restore JAMB data to session if missing but application has it
        if (!isset($_SESSION['jamb_verification']) && !empty($application['jamb_number'])) {
            $_SESSION['jamb_verification'] = [
                'jamb_number' => $application['jamb_number'],
                'first_name' => $application['first_name'],
                'last_name' => $application['last_name'],
                'other_names' => $application['other_names'],
                'gender' => $application['gender'],
                'state_of_origin' => $application['state_of_origin'],
                'lga' => $application['lga'],
                'score' => $application['utme_score'],
                'verified_at' => time()
            ];
            error_log("Restored JAMB data to session from database in showApplicationForm for applicant: " . $applicantId);
        }
        
        // Parse O'Level results from JSON if exists
        $olevelFiles = [];
        if (!empty($application['olevel_results'])) {
            $olevelFiles = json_decode($application['olevel_results'], true);
            if (!is_array($olevelFiles)) {
                $olevelFiles = [];
            }
        }
        
        // Get O'Level results from dedicated model if exists
        require_once MODELS_PATH . '/application/OlevelResultModel.php';
        $olevelModel = new OlevelResultModel();
        $olevel_results = $olevelModel->getByApplicationId($application['id']);
        
        // IMPORTANT: Ensure O'Level results are indexed sequentially
        // This prevents any gaps in indices that might cause issues
        if (!empty($olevel_results)) {
            // Re-index to ensure sequential 0,1,2...
            $olevel_results = array_values($olevel_results);
        }
        
        // Get passport from document model if exists
        require_once MODELS_PATH . '/application/ApplicationDocumentModel.php';
        $docModel = new ApplicationDocumentModel();
        $passport = $docModel->getPassport($application['id']);
        
        // Check O'Level validation status using legacy method
        $olevelValidation = $this->validateOlevelCredits($olevel_results);
        
        // FIX 2c: Get detailed credit summary for the view
        $creditSummary = $olevelModel->getCreditCheckSummary($application['id']);

        // Carry over any O'Level session errors
        $olevelSessionError = $_SESSION['olevel_error'] ?? null;
        unset($_SESSION['olevel_error'], $_SESSION['olevel_missing'], $_SESSION['olevel_failed'],
              $_SESSION['olevel_credits'], $_SESSION['olevel_summary']);
        
        // Pass data to view including file paths
        $this->data = array_merge($this->data, [
            'pageTitle'          => 'Application Form - Step 2',
            'application'        => $application,
            'applicant'          => $applicant,
            'jamb_data'          => $_SESSION['jamb_verification'] ?? null,
            'olevel_results'     => $olevel_results,
            'olevel_validation'  => $olevelValidation,
            'credit_summary'     => $creditSummary,
            'olevel_session_error' => $olevelSessionError,
            'passport'           => $passport,
            'states'             => $this->getStates(),
            'programs'           => $this->getPrograms(),
            'csrf_token'         => $this->csrfToken(),
            'existing_passport' => !empty($application['passport_photo']) ? [
                'file_path' => $application['passport_photo'],
                'id' => 'passport'
            ] : null,
            'existing_olevel' => array_map(function($path, $index) {
                return [
                    'file_path' => $path,
                    'id' => 'olevel_' . $index
                ];
            }, $olevelFiles, array_keys($olevelFiles)),
            'existing_jamb_result' => !empty($application['qualification_file']) ? [
                'file_path' => $application['qualification_file'],
                'id' => 'jamb_result'
            ] : null,
            'existing_birth_certificate' => !empty($application['birth_certificate']) ? [
                'file_path' => $application['birth_certificate'],
                'id' => 'birth_certificate'
            ] : null
        ]);
        
        // Redirect to step 2 instead of rendering form
        $this->redirect('/apply/step/2');
    }

    /**
     * Save application form - FIXED with proper O'Level data handling and security checks
     */
    public function saveApplication() {
        // Set header to JSON first thing
        header('Content-Type: application/json');
        
        // Initialize security
        $this->initSecurity();
        
        // Check if user is logged in
        if (!isset($_SESSION['applicant_id'])) {
            echo json_encode(['success' => false, 'message' => 'Please login first']);
            return;
        }
        
        // Validate CSRF
        if (!isset($_POST['csrf_token']) || !$this->validateCsrfToken($_POST['csrf_token'])) {
            echo json_encode(['success' => false, 'message' => 'Invalid security token']);
            return;
        }
        
        try {
            $applicantId = $_SESSION['applicant_id'];
            
            // Check registration complete (email verified)
            $applicant = $this->applicantModel->find($applicantId);
            if (!$applicant || !isset($applicant['email_verified']) || $applicant['email_verified'] != 1) {
                echo json_encode(['success' => false, 'message' => 'Please verify your email first', 'redirect' => '/apply/verify-email']);
                return;
            }
            
            // Get existing application
            $application = $this->applicationModel->getByApplicantId($applicantId);
            
            if (!$application) {
                echo json_encode(['success' => false, 'message' => 'Application not found']);
                return;
            }
            
            // SECURITY: Check if payment has been made
            $hasPaid = $this->paymentModel->hasSuccessfulPayment($application['id']);
            if ($hasPaid) {
                echo json_encode(['success' => false, 'message' => 'Cannot modify application after payment']);
                return;
            }
            
            // SECURITY: Validate step access
            if (!$this->validateStepAccess($application, 2, 'saveApplication')) {
                echo json_encode(['success' => false, 'message' => 'Invalid operation for current application state']);
                return;
            }
            
            // Get editable fields
            $dateOfBirth = $_POST['date_of_birth'] ?? '';
            $phone = $_POST['phone'] ?? '';
            $address = $_POST['address'] ?? '';
            $nationality = $_POST['nationality'] ?? 'Nigerian';
            $programChoice = $_POST['program_choice'] ?? $_POST['program_choice_1'] ?? '';
            $email = $_POST['email'] ?? '';
            
            // Validate required fields
            $missingFields = [];
            if (empty($dateOfBirth)) $missingFields[] = 'date_of_birth';
            if (empty($phone)) $missingFields[] = 'phone';
            if (empty($address)) $missingFields[] = 'address';
            if (empty($programChoice)) $missingFields[] = 'program_choice';
            
            if (!empty($missingFields)) {
                echo json_encode([
                    'success' => false, 
                    'message' => 'Please fill all required fields: ' . implode(', ', $missingFields)
                ]);
                return;
            }
            
            // Begin transaction - FIXED: Check if already in transaction
            $ownTransaction = false;
            if (!$this->applicationModel->getConnection()->inTransaction()) {
                $this->applicationModel->beginTransaction();
                $ownTransaction = true;
            }
            
            // Prepare update data
            $updateData = [
                'date_of_birth' => $dateOfBirth,
                'phone' => $phone,
                'email' => $email,
                'address' => $address,
                'nationality' => $nationality,
                'program_choice_1' => $programChoice,
                'application_step' => 3,
                'updated_at' => date('Y-m-d H:i:s')
            ];
            
            // FIX: Handle O'Level subject data with proper indexing
            $olevelValidation = ['success' => false];
            $formattedResults = [];
            
            if (isset($_POST['olevel']) && is_array($_POST['olevel'])) {
                error_log("O'Level data received: " . count($_POST['olevel']) . " entries");
                
                // Use the validation helper to ensure proper indexing
                $formattedResults = $this->validateAndReindexOlevelData($_POST['olevel']);
                
                if (!empty($formattedResults)) {
                    // Validate O'Level credits
                    $olevelValidation = $this->validateOlevelCredits($formattedResults);
                    
                    if (!$olevelValidation['success']) {
                        // If validation fails, return error but don't stop saving
                        error_log("O'Level validation warning: " . $olevelValidation['message']);
                    }
                    
                    // Save to olevel_results field in JSON format
                    $updateData['olevel_results'] = json_encode($formattedResults);
                    
                    // Also save to the dedicated olevel_results table
                    try {
                        require_once MODELS_PATH . '/application/OlevelResultModel.php';
                        $olevelModel = new OlevelResultModel();
                        
                        // Delete existing records first to prevent duplication
                        $olevelModel->deleteByApplicationId($application['id']);
                        error_log("Deleted existing O'Level records for application ID: " . $application['id']);
                        
                        // Save each sitting to the database with sequential IDs
                        foreach ($formattedResults as $result) {
                            $result['application_id'] = $application['id'];
                            $olevelModel->insert($result);
                        }
                        error_log("Saved " . count($formattedResults) . " new O'Level results with sequential indices");
                    } catch (Exception $e) {
                        error_log("Error saving to olevel_results table: " . $e->getMessage());
                        // Don't fail the whole request, just log the error
                    }
                }
            } else {
                // If no O'Level data in POST, try to preserve existing data
                error_log("No O'Level data in POST, preserving existing data if any");
                
                // Get existing O'Level results from database
                try {
                    require_once MODELS_PATH . '/application/OlevelResultModel.php';
                    $olevelModel = new OlevelResultModel();
                    $existingResults = $olevelModel->getByApplicationId($application['id']);
                    
                    if (!empty($existingResults)) {
                        $formattedResults = $existingResults;
                        $updateData['olevel_results'] = json_encode($existingResults);
                        error_log("Preserved " . count($existingResults) . " existing O'Level results");
                    }
                } catch (Exception $e) {
                    error_log("Error preserving O'Level data: " . $e->getMessage());
                }
            }
            
            // Handle file uploads
            $uploadErrors = [];
            
            // Upload passport
            if (isset($_FILES['passport']) && $_FILES['passport']['error'] === UPLOAD_ERR_OK) {
                $passportResult = $this->uploadFile($_FILES['passport'], $applicantId, 'passport');
                if ($passportResult['success']) {
                    $updateData['passport_photo'] = $passportResult['path'];
                    error_log("Passport uploaded: " . $passportResult['path']);
                } else {
                    $uploadErrors[] = 'Passport: ' . $passportResult['message'];
                }
            }
            
            // Update existing application
            $updated = $this->applicationModel->updateApplication($application['id'], $updateData);
            
            if (!$updated) {
                throw new Exception("Failed to update application");
            }
            
            error_log("Updated existing application ID: " . $application['id']);
            
            // Update applicant phone and email if needed
            if (!empty($phone) || !empty($email)) {
                try {
                    $this->applicantModel->update(
                        ['phone' => $phone, 'email' => $email, 'updated_at' => date('Y-m-d H:i:s')],
                        'id = :id',
                        ['id' => $applicantId]
                    );
                    error_log("Updated applicant ID: " . $applicantId);
                } catch (Exception $e) {
                    error_log("Failed to update applicant: " . $e->getMessage());
                }
            }
            
            // Commit only if we started the transaction
            if ($ownTransaction) {
                $this->applicationModel->commit();
            }
            
            // FIX 2a: Re-validate O'Level using the dedicated model method for accurate summary
            require_once MODELS_PATH . '/application/OlevelResultModel.php';
            $olevelModelCheck = new OlevelResultModel();
            $creditSummary = $olevelModelCheck->getCreditCheckSummary($application['id']);

            // Prepare success response
            $response = [
                'success'        => true,
                'message'        => 'Application saved successfully',
                'application_id' => $application['id'],
                'olevel_summary' => $creditSummary,
                'olevel_count'   => count($formattedResults) // Send back count for debugging
            ];

            if (!empty($uploadErrors)) {
                $response['upload_errors'] = $uploadErrors;
            }

            // FIX 2a: If action is 'next', check O'Level before allowing redirect to payment
            if (isset($_POST['action']) && $_POST['action'] === 'next') {
                if (!$creditSummary['meets_requirement']) {
                    // Do NOT redirect — return error so JS can show the alert
                    $response['olevel_blocked'] = true;
                    $response['olevel_message'] = $creditSummary['message'];
                    $response['missing_subjects'] = $creditSummary['missing_subjects'];
                    $response['failed_subjects']  = $creditSummary['failed_subjects'];
                    $response['credits_achieved'] = $creditSummary['credits_achieved'];
                    // No redirect key - stays on step 2
                } else {
                    $response['redirect'] = '/apply/step/3';
                }
            }
            
            echo json_encode($response);
            
        } catch (Exception $e) {
            // Rollback only if we started the transaction
            if (isset($ownTransaction) && $ownTransaction && $this->applicationModel->getConnection()->inTransaction()) {
                $this->applicationModel->rollback();
            }
            error_log("Save application error: " . $e->getMessage());
            error_log("Stack trace: " . $e->getTraceAsString());
            echo json_encode([
                'success' => false,
                'message' => 'Server error: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Remove document - FIXED with security checks
     */
    public function removeDocument() {
        // Set header to JSON first thing
        header('Content-Type: application/json');
        
        // Initialize security
        $this->initSecurity();
        
        // Check if user is logged in
        if (!isset($_SESSION['applicant_id'])) {
            echo json_encode(['success' => false, 'message' => 'Please login first']);
            return;
        }
        
        // Validate CSRF
        $input = json_decode(file_get_contents('php://input'), true);
        $token = $input['csrf_token'] ?? $_POST['csrf_token'] ?? '';
        
        if (!$this->validateCsrfToken($token)) {
            echo json_encode(['success' => false, 'message' => 'Invalid security token']);
            return;
        }
        
        try {
            $applicantId = $_SESSION['applicant_id'];
            $type = $input['type'] ?? $_POST['type'] ?? '';
            $index = isset($input['index']) ? intval($input['index']) : (isset($_POST['index']) ? intval($_POST['index']) : null);
            
            if (empty($type)) {
                echo json_encode(['success' => false, 'message' => 'File type not specified']);
                return;
            }
            
            // Get application
            $application = $this->applicationModel->getByApplicantId($applicantId);
            
            if (!$application) {
                echo json_encode(['success' => false, 'message' => 'Application not found']);
                return;
            }
            
            // SECURITY: Check if payment has been made
            $hasPaid = $this->paymentModel->hasSuccessfulPayment($application['id']);
            if ($hasPaid) {
                echo json_encode(['success' => false, 'message' => 'Cannot modify application after payment']);
                return;
            }
            
            $updateData = [];
            $filePath = null;
            
            switch ($type) {
                case 'passport':
                    $filePath = $application['passport_photo'];
                    $updateData['passport_photo'] = null;
                    break;
                    
                case 'jamb_result':
                    $filePath = $application['qualification_file'];
                    $updateData['qualification_file'] = null;
                    break;
                    
                case 'birth_certificate':
                    $filePath = $application['birth_certificate'];
                    $updateData['birth_certificate'] = null;
                    break;
                    
                case 'olevel':
                    if ($index !== null && !empty($application['olevel_file_paths'])) {
                        $olevelFiles = json_decode($application['olevel_file_paths'], true);
                        if (is_array($olevelFiles) && isset($olevelFiles[$index])) {
                            $filePath = $olevelFiles[$index];
                            array_splice($olevelFiles, $index, 1);
                            $updateData['olevel_file_paths'] = !empty($olevelFiles) ? json_encode($olevelFiles) : null;
                        }
                    }
                    break;
                    
                default:
                    echo json_encode(['success' => false, 'message' => 'Invalid file type']);
                    return;
            }
            
            // Delete physical file if it exists
            if ($filePath) {
                $fullPath = PUBLIC_PATH . $filePath;
                if (file_exists($fullPath)) {
                    unlink($fullPath);
                    error_log("Deleted file: " . $fullPath);
                }
            }
            
            // Update database if we have changes
            if (!empty($updateData)) {
                $updateData['updated_at'] = date('Y-m-d H:i:s');
                
                // Use updateApplication method for consistency
                $updated = $this->applicationModel->updateApplication($application['id'], $updateData);
                
                if (!$updated) {
                    echo json_encode(['success' => false, 'message' => 'Failed to update application']);
                    return;
                }
            }
            
            echo json_encode(['success' => true, 'message' => 'File removed successfully']);
            
        } catch (Exception $e) {
            error_log("Remove document error: " . $e->getMessage());
            echo json_encode(['success' => false, 'message' => 'Server error: ' . $e->getMessage()]);
        }
    }

    /**
     * Upload file helper - UPDATED to return file path
     */
    private function uploadFile($file, $applicantId, $type) {
        $allowedTypes = ['image/jpeg', 'image/png', 'image/jpg', 'application/pdf'];
        $maxSize = 2 * 1024 * 1024; // 2MB
        
        if (!in_array($file['type'], $allowedTypes)) {
            return ['success' => false, 'message' => 'Invalid file type. Allowed: JPG, PNG, PDF'];
        }
        
        if ($file['size'] > $maxSize) {
            return ['success' => false, 'message' => 'File too large. Max size: 2MB'];
        }
        
        // Create upload directory
        $uploadDir = PUBLIC_PATH . "/uploads/applications/{$applicantId}/{$type}";
        if (!is_dir($uploadDir)) {
            if (!mkdir($uploadDir, 0755, true)) {
                return ['success' => false, 'message' => 'Failed to create upload directory'];
            }
        }
        
        // Generate filename
        $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
        $filename = $type . '_' . time() . '_' . bin2hex(random_bytes(4)) . '.' . $extension;
        $filepath = $uploadDir . '/' . $filename;
        
        if (move_uploaded_file($file['tmp_name'], $filepath)) {
            // Return the web-accessible path
            $webPath = '/uploads/applications/' . $applicantId . '/' . $type . '/' . $filename;
            return ['success' => true, 'path' => $webPath];
        }
        
        return ['success' => false, 'message' => 'Failed to save file'];
    }

    // ============================================
    // STEP 3: PAYMENT - COMPLETELY FIXED WITH PROPER RRR HANDLING
    // ============================================

    /**
     * Show payment page (Step 3 - New Flow)
     */
    public function showPayment() {
        // Initialize security
        $this->initSecurity();
        
        // Check if logged in
        if (!$this->isApplicantLoggedIn()) {
            $_SESSION['flash_error'] = 'Please login to continue';
            $this->redirect('/applicant/login');
            return;
        }
        
        // Check registration complete (email verified)
        if (!$this->checkRegistrationComplete()) {
            return; // checkRegistrationComplete already redirects
        }
        
        $applicantId = $_SESSION['applicant_id'];
        $application = $this->applicationModel->getByApplicantId($applicantId);
        
        if (!$application) {
            $_SESSION['flash_error'] = 'Application not found';
            $this->redirect('/apply/form');
            return;
        }
        
        // SECURITY: Validate step access (FIX 2b is already in validateStepAccess)
        if (!$this->validateStepAccess($application, 3, 'showPayment')) {
            $this->redirectToProperStep($application);
            return;
        }
        
        // Check if already paid
        $hasPaid = $this->paymentModel->hasSuccessfulPayment($application['id']);
        
        if ($hasPaid) {
            $this->redirect('/apply/step/4');
            return;
        }
        
        // Check if application is complete enough for payment
        if (empty($application['date_of_birth']) || empty($application['phone']) || empty($application['address'])) {
            $_SESSION['flash_error'] = 'Please complete your application form first';
            $this->redirect('/apply/step/2');
            return;
        }
        
        // Restore JAMB data if needed
        if (!isset($_SESSION['jamb_verification']) && !empty($application['jamb_number'])) {
            $_SESSION['jamb_verification'] = [
                'jamb_number' => $application['jamb_number'],
                'first_name' => $application['first_name'],
                'last_name' => $application['last_name'],
                'other_names' => $application['other_names'],
                'gender' => $application['gender'],
                'state_of_origin' => $application['state_of_origin'],
                'lga' => $application['lga'],
                'score' => $application['utme_score']
            ];
            error_log("Restored JAMB data to session from application ID: " . $application['id']);
        }
        
        // Generate a fresh CSRF token
        $csrfToken = $this->csrfToken();
        
        $fee = $this->settingsModel->getApplicationFee();
        $currency = $this->settingsModel->getCurrency();
        
        $this->data = array_merge($this->data, [
            'pageTitle' => 'Payment - Step 3',
            'application' => $application,
            'fee' => $fee,
            'currency' => $currency,
            'formatted_fee' => $this->settingsModel->getFormattedFee(),
            'csrf_token' => $csrfToken
        ]);
        
        $this->render('applications/payment');
    }

    /**
     * INITIATE PAYMENT - Generate RRR via Remita API
     * URL: POST /apply/initiate-payment
     */
    public function initiatePayment() {
        // Set header to JSON
        header('Content-Type: application/json');
        
        error_log("=== INITIATE PAYMENT CALLED ===");
        
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
            $csrfToken = $input['csrf_token'] ?? $_POST['csrf_token'] ?? '';
            
            // Validate CSRF token
            if (empty($csrfToken)) {
                error_log("CSRF validation failed: Token is empty");
                echo json_encode([
                    'success' => false, 
                    'message' => 'Security token missing'
                ]);
                return;
            }
            
            if (!isset($_SESSION['csrf_tokens'][$csrfToken])) {
                error_log("CSRF validation failed: Token not found in session");
                echo json_encode([
                    'success' => false, 
                    'message' => 'Invalid security token'
                ]);
                return;
            }
            
            // Check token expiration (1 hour)
            if (time() - $_SESSION['csrf_tokens'][$csrfToken] > 3600) {
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
            
            // Get applicant details
            $applicant = $this->applicantModel->find($applicantId);
            
            if (!$applicant) {
                error_log("Applicant not found: $applicantId");
                echo json_encode([
                    'success' => false,
                    'message' => 'Applicant details not found'
                ]);
                return;
            }
            
            // Prepare payer details
            $payerName = trim(($applicant['first_name'] ?? '') . ' ' . ($applicant['last_name'] ?? ''));
            if (empty($payerName)) {
                $payerName = $applicant['email'] ?? 'Applicant';
            }
            
            $payerEmail = $applicant['email'] ?? '';
            $payerPhone = $applicant['phone'] ?? '';
            
            // Generate Order ID (unique)
            $orderId = 'ORD' . time() . rand(100, 999);
            
            error_log("Calling Remita API with: OrderID=$orderId, Amount=$fee, Payer=$payerName, Email=$payerEmail");
            
            // Verify remitaModel is initialized
            if (!$this->remitaModel) {
                error_log("CRITICAL ERROR: remitaModel is NULL");
                echo json_encode([
                    'success' => false,
                    'message' => 'Payment system configuration error. Please contact support.'
                ]);
                return;
            }
            
            // Call Remita API to generate REAL RRR
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
                
                // Format RRR with dashes for display (12-digit format)
                $formattedRrr = $rrr;
                if (strlen($rrr) == 12) {
                    $formattedRrr = substr($rrr, 0, 4) . '-' . substr($rrr, 4, 4) . '-' . substr($rrr, 8, 4);
                }
                
                // Create payment record in database
                // FIXED: Removed non-existent 'raw_rrr' column
                $paymentData = [
                    'application_id' => $application['id'],
                    'applicant_id' => $applicantId,
                    'rrr' => $formattedRrr,
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
                    'rrr' => $formattedRrr,
                    'amount' => $fee,
                    'created_at' => time()
                ];
                
                echo json_encode([
                    'success' => true,
                    'rrr' => $formattedRrr,
                    'payment_id' => $paymentId,
                    'message' => 'RRR generated successfully'
                ]);
                
            } else {
                // API call failed
                $errorMsg = $result['message'] ?? 'Unknown error';
                $httpCode = $result['http_code'] ?? 500;
                
                error_log("❌ Remita API failed: " . $errorMsg . " (HTTP: $httpCode)");
                
                // Return appropriate error message based on HTTP status
                $userMessage = 'Failed to generate RRR. Please try again or contact support.';
                
                if ($httpCode === 302) {
                    $userMessage = 'Remita API endpoint configuration error. Please contact support.';
                } elseif ($httpCode === 400) {
                    $userMessage = 'Invalid request to Remita. Please try again.';
                } elseif ($httpCode === 401 || $httpCode === 403) {
                    $userMessage = 'Remita authentication failed. Please contact support.';
                } elseif ($httpCode === 500) {
                    $userMessage = 'Remita service temporarily unavailable. Please try again later.';
                }
                
                echo json_encode([
                    'success' => false,
                    'message' => $userMessage,
                    'debug' => $errorMsg  // optional, remove in production
                ]);
            }
            
        } catch (Exception $e) {
            error_log("=== PAYMENT INITIATE EXCEPTION ===");
            error_log("Error message: " . $e->getMessage());
            error_log("Error file: " . $e->getFile() . " on line " . $e->getLine());
            error_log("Stack trace: " . $e->getTraceAsString());
            
            echo json_encode([
                'success' => false,
                'message' => 'Server error occurred. Please try again later.'
            ]);
        }
    }

    /**
     * Generate secure Remita payment URL (server-side)
     * FIXED: Use the EXACT RRR from database, don't modify it
     * 
     * @param string $rrr The RRR to use in the URL (should be raw, without dashes)
     * @return string The complete payment URL
     */
    private function generatePaymentUrl($rrr) {
        // Clean RRR - remove any non-numeric characters (dashes, spaces, etc.) just to be safe
        $cleanRrr = preg_replace('/[^0-9]/', '', $rrr);
        
        // IMPORTANT: Log what RRR we're using for debugging
        error_log("generatePaymentUrl: Using RRR: $cleanRrr (original: $rrr)");
        
        // Determine the correct base URL based on environment
        $environment = defined('REMITA_ENVIRONMENT') ? REMITA_ENVIRONMENT : 'demo';
        
        if ($environment === 'live') {
            $baseUrl = 'https://login.remita.net/remita/onepage/payment/init.reg';
        } else {
            // Demo environment - use remitademo.net (per Remita support)
            $baseUrl = 'https://remitademo.net/remita/onepage/payment/init.reg';
        }
        
        // Build URL with proper encoding - use the EXACT same RRR
        $params = http_build_query([
            'rrr' => $cleanRrr,
            'channel' => 'CARD,USSD,ENAIRA,TRANSFER'
        ]);
        
        $fullUrl = $baseUrl . '?' . $params;
        error_log("generatePaymentUrl: Generated URL: $fullUrl");
        
        return $fullUrl;
    }
    
    /**
     * Verify payment (AJAX endpoint) - FIXED: Properly generates exam slip
     */
    public function verifyPayment() {
        header('Content-Type: application/json');
        
        // Initialize security
        $this->initSecurity();
        
        error_log("=== VERIFY PAYMENT CALLED ===");
        
        if (!isset($_SESSION['applicant_id'])) {
            echo json_encode(['success' => false, 'message' => 'Please login first']);
            return;
        }
        
        // Get RRR from request
        $input = json_decode(file_get_contents('php://input'), true);
        $rrr = $input['rrr'] ?? $_POST['rrr'] ?? '';
        $csrfToken = $input['csrf_token'] ?? $_POST['csrf_token'] ?? '';
        
        if (empty($rrr)) {
            echo json_encode(['success' => false, 'message' => 'RRR is required']);
            return;
        }
        
        // Validate CSRF
        if (!$this->validateCsrfToken($csrfToken)) {
            echo json_encode(['success' => false, 'message' => 'Invalid security token']);
            return;
        }
        
        try {
            // Get payment by RRR (try both formatted and raw)
            $payment = $this->paymentModel->getByRRR($rrr);
            
            if (!$payment) {
                echo json_encode(['success' => false, 'message' => 'Payment record not found']);
                return;
            }
            
            // Verify ownership
            if ($payment['applicant_id'] != $_SESSION['applicant_id']) {
                error_log("SECURITY: Unauthorized payment verification attempt - User: " . $_SESSION['applicant_id'] . 
                         ", Payment Owner: " . $payment['applicant_id']);
                echo json_encode(['success' => false, 'message' => 'Unauthorized access']);
                return;
            }
            
            // Get the raw RRR for API call (use raw_rrr if available, otherwise clean the formatted one)
            $rawRrr = $payment['raw_rrr'] ?? preg_replace('/[^0-9]/', '', $payment['rrr']);
            
            // Verify remitaModel is initialized
            if (!$this->remitaModel) {
                error_log("CRITICAL ERROR: remitaModel is NULL in verifyPayment");
                echo json_encode([
                    'success' => false,
                    'message' => 'Payment verification system error. Please contact support.'
                ]);
                return;
            }
            
            // Call Remita to verify payment status
            $verificationResult = $this->remitaModel->verifyPayment($rawRrr);
            
            error_log("Remita verification result: " . print_r($verificationResult, true));
            
            if ($verificationResult['status'] === 'success') {
                // Begin transaction - FIXED: Check if already in transaction
                $ownTransaction = false;
                if (!$this->paymentModel->getConnection()->inTransaction()) {
                    $this->paymentModel->beginTransaction();
                    $ownTransaction = true;
                }
                
                try {
                    // Payment is confirmed by Remita
                    $updateResult = $this->paymentModel->markAsSuccess($payment['id'], [
                        'transaction_id' => $verificationResult['payment_data']['transactionId'] ?? $verificationResult['payment_data']['transactionRef'] ?? 'TXN' . time(),
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
                        $examSlip = $this->generateExamSlip($payment['application_id']);
                        
                        if (!$examSlip) {
                            error_log("Failed to generate exam slip for application: " . $payment['application_id']);
                        } else {
                            error_log("Exam slip generated successfully for application: " . $payment['application_id']);
                        }
                        
                        // Commit only if we started the transaction
                        if ($ownTransaction) {
                            $this->paymentModel->commit();
                        }
                        
                        // Clear any pending payment from session
                        unset($_SESSION['pending_payment']);
                        
                        echo json_encode([
                            'success' => true,
                            'message' => 'Payment verified successfully',
                            'redirect' => '/apply/step/4'
                        ]);
                    } else {
                        throw new Exception("Failed to update payment status");
                    }
                } catch (Exception $e) {
                    // Rollback only if we started the transaction
                    if ($ownTransaction && $this->paymentModel->getConnection()->inTransaction()) {
                        $this->paymentModel->rollback();
                    }
                    throw $e;
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
            error_log("Verify payment error: " . $e->getMessage());
            echo json_encode([
                'success' => false,
                'message' => 'Server error occurred'
            ]);
        }
    }

    // ============================================
    // STEP 4: EXAM SLIP - ENHANCED SECURITY
    // ============================================

    /**
     * Show exam slip page (Step 4 - New Flow)
     */
    public function showExamSlip() {
        // Initialize security
        $this->initSecurity();
        
        // Check if logged in
        if (!$this->isApplicantLoggedIn()) {
            $_SESSION['flash_error'] = 'Please login to continue';
            $this->redirect('/applicant/login');
            return;
        }
        
        // Check registration complete (email verified)
        if (!$this->checkRegistrationComplete()) {
            return; // checkRegistrationComplete already redirects
        }
        
        $applicantId = $_SESSION['applicant_id'];
        $application = $this->applicationModel->getByApplicantId($applicantId);
        
        if (!$application) {
            $_SESSION['flash_error'] = 'Application not found';
            $this->redirect('/apply/form');
            return;
        }
        
        // SECURITY: Validate step access
        if (!$this->validateStepAccess($application, 4, 'showExamSlip')) {
            $this->redirectToProperStep($application);
            return;
        }
        
        // Check if payment is successful
        $hasPaid = $this->paymentModel->hasSuccessfulPayment($application['id']);
        
        if (!$hasPaid) {
            error_log("SECURITY: Attempted to access exam slip without payment - Application: " . $application['id']);
            $_SESSION['flash_error'] = 'Payment required. Please complete your payment first.';
            $this->redirect('/apply/step/3');
            return;
        }
        
        // Get exam slip
        require_once MODELS_PATH . '/application/ExamSlipModel.php';
        $examSlipModel = new ExamSlipModel();
        $examSlip = $examSlipModel->getByApplicationId($application['id']);
        
        if (!$examSlip) {
            // Generate exam slip if not exists
            error_log("Exam slip not found for application: " . $application['id'] . ". Generating...");
            $examSlip = $this->generateExamSlip($application['id']);
        }
        
        // Get O'Level results for display on exam slip
        require_once MODELS_PATH . '/application/OlevelResultModel.php';
        $olevelModel = new OlevelResultModel();
        $olevel_results = $olevelModel->getByApplicationId($application['id']);
        
        // IMPORTANT: Ensure O'Level results are indexed sequentially
        if (!empty($olevel_results)) {
            $olevel_results = array_values($olevel_results);
        }
        
        // Get applicant for name display
        $applicant = $this->applicantModel->find($applicantId);
        $applicant_name = trim(
            ($application['first_name'] ?? '') . ' ' . 
            ($application['last_name'] ?? '')
        );
        if (empty($applicant_name)) {
            $applicant_name = $applicant['email'] ?? 'Applicant';
        }
        
        $this->data = array_merge($this->data, [
            'pageTitle' => 'Examination Slip - Step 4',
            'application' => $application,
            'exam_slip' => $examSlip,
            'applicant' => $applicant,
            'applicant_name' => $applicant_name,
            'olevel_results' => $olevel_results,
            'has_exam_slip' => true,
            'exam_details' => [
                'date' => $this->settingsModel->get('cbt_start_date', 'To be announced'),
                'venue' => 'FCT College of Nursing Sciences, Gwagwalada (within UATH)',
                'reporting_time' => '8:00 AM'
            ]
        ]);
        
        $this->render('applications/step4');
    }

    /**
     * Print exam slip - uses print-optimized view
     */
    public function printExamSlip() {
        // Initialize security
        $this->initSecurity();
        
        // Check if logged in
        if (!$this->isApplicantLoggedIn()) {
            $this->redirect('/applicant/login');
            return;
        }
        
        $applicantId = $_SESSION['applicant_id'];
        $application = $this->applicationModel->getByApplicantId($applicantId);
        
        if (!$application) {
            $this->redirect('/apply/step/1');
            return;
        }
        
        // Check if payment is successful
        $hasPaid = $this->paymentModel->hasSuccessfulPayment($application['id']);
        
        if (!$hasPaid) {
            $this->redirect('/apply/step/3');
            return;
        }
        
        // Get exam slip
        require_once MODELS_PATH . '/application/ExamSlipModel.php';
        $examSlipModel = new ExamSlipModel();
        $examSlip = $examSlipModel->getByApplicationId($application['id']);
        
        if (!$examSlip) {
            $_SESSION['flash_error'] = 'Exam slip not found';
            $this->redirect('/apply/step/4');
            return;
        }
        
        // Get O'Level results
        require_once MODELS_PATH . '/application/OlevelResultModel.php';
        $olevelModel = new OlevelResultModel();
        $olevel_results = $olevelModel->getByApplicationId($application['id']);
        
        // IMPORTANT: Ensure O'Level results are indexed sequentially
        if (!empty($olevel_results)) {
            $olevel_results = array_values($olevel_results);
        }
        
        // Get applicant
        $applicant = $this->applicantModel->find($applicantId);
        
        // Set baseUrl properly
        $baseUrl = defined('BASE_URL') ? rtrim(BASE_URL, '/') : '';
        
        // Disable layout
        $this->layout = false;
        
        // Load the print-optimized view
        $viewPath = APP_PATH . '/views/applications/partials/exam-slip-print.php';
        
        if (!file_exists($viewPath)) {
            die("Print view not found");
        }
        
        // Extract data for the view
        extract([
            'application' => $application,
            'exam_slip' => $examSlip,
            'applicant' => $applicant,
            'olevel_results' => $olevel_results,
            'baseUrl' => $baseUrl
        ]);
        
        // Include the view directly
        include $viewPath;
        exit;
    }

    /**
     * Download exam slip
     */
    public function downloadExamSlip() {
        // Initialize security
        $this->initSecurity();
        
        // Check if logged in
        if (!$this->isApplicantLoggedIn()) {
            $_SESSION['flash_error'] = 'Please login to continue';
            $this->redirect('/applicant/login');
            return;
        }
        
        $applicantId = $_SESSION['applicant_id'];
        $application = $this->applicationModel->getByApplicantId($applicantId);
        
        if (!$application) {
            $_SESSION['flash_error'] = 'Application not found';
            $this->redirect('/apply/form');
            return;
        }
        
        // Get exam slip
        require_once MODELS_PATH . '/application/ExamSlipModel.php';
        $examSlipModel = new ExamSlipModel();
        $examSlip = $examSlipModel->getByApplicationId($application['id']);
        
        if (!$examSlip) {
            $_SESSION['flash_error'] = 'Exam slip not found';
            $this->redirect('/apply/step/4');
            return;
        }
        
        // Record download
        $examSlipModel->update(
            [
                'download_count' => ($examSlip['download_count'] ?? 0) + 1,
                'last_downloaded_at' => date('Y-m-d H:i:s'),
                'last_downloaded_ip' => $_SERVER['REMOTE_ADDR'] ?? ''
            ],
            'id = :id',
            ['id' => $examSlip['id']]
        );
        
        // Generate HTML for download
        header('Content-Type: text/html');
        header('Content-Disposition: attachment; filename="exam-slip-' . $examSlip['slip_number'] . '.html"');
        
        $applicant = $this->applicantModel->find($applicantId);
        
        echo $this->generateExamSlipHTML($examSlip, $application, $applicant);
        exit;
    }

    /**
     * Generate exam slip HTML
     */
    private function generateExamSlipHTML($examSlip, $application, $applicant) {
        $html = '<!DOCTYPE html>
        <html>
        <head>
            <title>Examination Slip</title>
            <style>
                body { font-family: Arial, sans-serif; padding: 20px; }
                .slip { max-width: 800px; margin: 0 auto; border: 2px solid #000; padding: 20px; }
                .header { text-align: center; border-bottom: 2px solid #6B4E9B; padding-bottom: 10px; margin-bottom: 20px; }
                .title { font-size: 24px; font-weight: bold; color: #6B4E9B; }
                .subtitle { font-size: 18px; margin: 5px 0; }
                .content { padding: 20px 0; }
                .row { margin-bottom: 15px; display: flex; }
                .label { font-weight: bold; width: 200px; }
                .value { flex: 1; border-bottom: 1px dotted #999; padding-bottom: 3px; }
                .qr { text-align: center; margin: 30px 0; }
                .footer { text-align: center; font-size: 12px; color: #666; border-top: 1px solid #ccc; padding-top: 10px; margin-top: 20px; }
                .important { background: #f8f8f8; padding: 15px; border-left: 4px solid #6B4E9B; margin: 20px 0; }
            </style>
        </head>
        <body>
            <div class="slip">
                <div class="header">
                    <div class="title">FCT COLLEGE OF NURSING SCIENCES</div>
                    <div class="subtitle">Gwagwalada, Abuja</div>
                    <div class="subtitle">2025/2026 ADMISSIONS SCREENING</div>
                    <div style="font-size: 20px; margin-top: 10px;"><strong>EXAMINATION SLIP</strong></div>
                </div>
                
                <div class="content">
                    <div class="row">
                        <div class="label">Slip Number:</div>
                        <div class="value">' . htmlspecialchars($examSlip['slip_number'] ?? 'N/A') . '</div>
                    </div>
                    <div class="row">
                        <div class="label">Application Number:</div>
                        <div class="value">' . htmlspecialchars($application['application_number'] ?? 'N/A') . '</div>
                    </div>
                    <div class="row">
                        <div class="label">JAMB Number:</div>
                        <div class="value">' . htmlspecialchars($application['jamb_number'] ?? 'N/A') . '</div>
                    </div>
                    <div class="row">
                        <div class="label">Candidate Name:</div>
                        <div class="value">' . htmlspecialchars(($applicant['title'] ?? '') . ' ' . ($applicant['first_name'] ?? '') . ' ' . ($applicant['last_name'] ?? '')) . '</div>
                    </div>
                    <div class="row">
                        <div class="label">Programme:</div>
                        <div class="value">' . htmlspecialchars($application['program_choice_1'] ?? 'N/A') . '</div>
                    </div>
                    <div class="row">
                        <div class="label">Examination Date:</div>
                        <div class="value">' . htmlspecialchars(date('l, jS F Y', strtotime($examSlip['exam_date'] ?? date('Y-m-d')))) . '</div>
                    </div>
                    <div class="row">
                        <div class="label">Examination Time:</div>
                        <div class="value">' . htmlspecialchars($examSlip['exam_time'] ?? '10:00 AM') . '</div>
                    </div>
                    <div class="row">
                        <div class="label">Reporting Time:</div>
                        <div class="value">' . htmlspecialchars($examSlip['reporting_time'] ?? '8:00 AM') . '</div>
                    </div>
                    <div class="row">
                        <div class="label">Venue:</div>
                        <div class="value">' . htmlspecialchars($examSlip['exam_venue'] ?? 'FCT College of Nursing Sciences, Gwagwalada') . '</div>
                    </div>
                    <div class="row">
                        <div class="label">Seat Number:</div>
                        <div class="value">' . htmlspecialchars($examSlip['seat_number'] ?? 'To be assigned') . '</div>
                    </div>
                </div>
                
                <div class="important">
                    <strong>Important Instructions:</strong><br>
                    ' . nl2br(htmlspecialchars($examSlip['instructions'] ?? '1. Arrive at least 1 hour before examination time
                    2. Bring this slip and a valid means of identification
                    3. Bring writing materials (biro, pencil, eraser)
                    4. Mobile phones and electronic devices are not allowed')) . '
                </div>
                
                <div class="qr">
                    <!-- QR Code placeholder -->
                    <div style="width: 120px; height: 120px; background: #f0f0f0; margin: 0 auto; display: flex; align-items: center; justify-content: center; border: 1px solid #ccc;">
                        QR Code<br>Verification
                    </div>
                </div>
                
                <div class="footer">
                    <p>This slip is computer-generated and does not require signature.</p>
                    <p>Generated on: ' . date('jS F Y, h:i A') . '</p>
                    <p>Download count: ' . ($examSlip['download_count'] ?? 0) . '</p>
                </div>
            </div>
        </body>
        </html>';
        
        return $html;
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
        
        // Get application for additional data if needed
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

    // ============================================
    // HELPER METHODS
    // ============================================
    
    /**
     * Get current application
     */
    protected function getApplication() {
        if (!$this->isApplicantLoggedIn()) {
            return null;
        }
        
        return $this->applicationModel->getByApplicantId($_SESSION['applicant_id']);
    }
    
    /**
     * Get current applicant
     */
    protected function getApplicant() {
        if (!$this->isApplicantLoggedIn()) {
            return null;
        }
        
        return $this->applicantModel->find($_SESSION['applicant_id']);
    }
    
    /**
     * Generate random password
     */
    private function generateRandomPassword($length = 10) {
        $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*()';
        return substr(str_shuffle($chars), 0, $length);
    }
    
    /**
     * Get states of Nigeria
     */
    protected function getStates() {
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
    protected function getPrograms() {
        return [
            'ND Nursing',
            'Post Basic Nursing',
            'Midwifery',
            'Public Health Nursing'
        ];
    }

    /**
     * Check if user has completed required registration steps
     * Redirects to appropriate page if not
     * 
     * @return bool True if user can proceed, false if redirected
     */
    private function checkRegistrationComplete() {
        if (!$this->isApplicantLoggedIn()) {
            $this->redirect('/applicant/login');
            return false;
        }
        
        $applicantId = $_SESSION['applicant_id'];
        $applicant = $this->applicantModel->find($applicantId);
        
        if (!$applicant) {
            $this->logout();
            $this->redirect('/apply/register');
            return false;
        }
        
        // Check if email is verified
        if (!isset($applicant['email_verified']) || $applicant['email_verified'] != 1) {
            $_SESSION['flash_error'] = 'Please verify your email address before proceeding.';
            $_SESSION['verification_email'] = $applicant['email'];
            $this->redirect('/apply/verify-email?email=' . urlencode($applicant['email']));
            return false;
        }
        
        return true;
    }

    // ============================================
    // APPLICANT AUTHENTICATION
    // ============================================

    /**
     * Show applicant login page
     */
    public function login() {
        // Initialize security
        $this->initSecurity();
        
        // Clear any existing login errors from previous attempts
        unset($_SESSION['login_error']);
        unset($_SESSION['password_error']);
        
        // If already logged in, redirect
        if (isset($_SESSION['applicant_id'])) {
            $application = $this->getApplication();
            $this->redirectToProperStep($application);
            return;
        }
        
        $this->data = array_merge($this->data, [
            'pageTitle' => 'Applicant Login - Application Portal',
            'csrf_token' => $this->csrfToken()
        ]);
        
        $this->render('applications/login');
    }
    
    /**
     * Process applicant login - ENHANCED with specific error messages
     */
    public function processLogin() {
        // Initialize security
        $this->initSecurity();
        
        // Clear any existing form errors
        unset($_SESSION['login_error']);
        unset($_SESSION['password_error']);
        unset($_SESSION['login_value']);
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/applicant/login');
            return;
        }
        
        // Validate CSRF
        if (!$this->validateCsrfToken()) {
            $_SESSION['flash_error'] = 'Security token expired. Please refresh the page and try again.';
            $this->redirect('/applicant/login');
            return;
        }
        
        $login = trim($_POST['login'] ?? '');
        $password = $_POST['password'] ?? '';
        
        // Store login value to repopulate form on error
        $_SESSION['login_value'] = $login;
        
        // Validate input
        if (empty($login)) {
            $_SESSION['login_error'] = 'Please enter your email, phone, or JAMB number.';
            $_SESSION['flash_error'] = 'Please enter your login details.';
            $this->redirect('/applicant/login');
            return;
        }
        
        if (empty($password)) {
            $_SESSION['password_error'] = 'Please enter your password.';
            $_SESSION['flash_error'] = 'Please enter your password.';
            $this->redirect('/applicant/login');
            return;
        }
        
        // Determine the type of login (email, phone, or JAMB)
        $loginType = 'unknown';
        if (filter_var($login, FILTER_VALIDATE_EMAIL)) {
            $loginType = 'email';
        } elseif (preg_match('/^[0-9]{11}$/', $login)) {
            $loginType = 'phone';
        } elseif (preg_match('/^[0-9]{10,14}$/', $login) || preg_match('/^[0-9]{10,14}$/', str_replace('-', '', $login))) {
            $loginType = 'jamb';
        }
        
        // Try to find by email first
        $applicant = $this->applicantModel->findByEmail($login);
        
        // If not found by email, try by phone
        if (!$applicant) {
            $applicant = $this->applicantModel->findByPhone($login);
        }
        
        // If still not found, try by JAMB number through application
        if (!$applicant) {
            $application = $this->applicationModel->getByJambNumber($login);
            if ($application && !empty($application['applicant_id'])) {
                $applicant = $this->applicantModel->find($application['applicant_id']);
            }
        }
        
        // If no applicant found
        if (!$applicant) {
            error_log("Login failed: No applicant found with login: " . $login);
            
            $errorMessage = '';
            switch ($loginType) {
                case 'email':
                    $errorMessage = 'Invalid email address or password. Please check your email and try again.';
                    break;
                case 'phone':
                    $errorMessage = 'Invalid phone number or password. Please check your phone number and try again.';
                    break;
                case 'jamb':
                    $errorMessage = 'Invalid JAMB number or password. Please check your JAMB registration number and try again.';
                    break;
                default:
                    $errorMessage = 'Invalid login credentials. Please check your details and try again.';
            }
            
            $_SESSION['login_error'] = $errorMessage;
            $_SESSION['flash_error'] = $errorMessage;
            $this->redirect('/applicant/login');
            return;
        }
        
        // Check if email is verified
        if (!isset($applicant['email_verified']) || $applicant['email_verified'] != 1) {
            error_log("Login failed: Email not verified for applicant ID: " . $applicant['id']);
            $_SESSION['flash_error'] = 'Please verify your email before logging in. Check your inbox for the verification link.';
            $_SESSION['verification_email'] = $applicant['email'];
            $this->redirect('/applicant/login');
            return;
        }
        
        // Verify password
        if (!password_verify($password, $applicant['password'])) {
            error_log("Login failed: Invalid password for applicant ID: " . $applicant['id']);
            
            $errorMessage = '';
            switch ($loginType) {
                case 'email':
                    $errorMessage = 'Invalid email address or password. Please check your email and password and try again.';
                    break;
                case 'phone':
                    $errorMessage = 'Invalid phone number or password. Please check your phone number and password and try again.';
                    break;
                case 'jamb':
                    $errorMessage = 'Invalid JAMB number or password. Please check your JAMB number and password and try again.';
                    break;
                default:
                    $errorMessage = 'Invalid login credentials. Please check your password and try again.';
            }
            
            $_SESSION['password_error'] = $errorMessage;
            $_SESSION['flash_error'] = $errorMessage;
            $this->redirect('/applicant/login');
            return;
        }
        
        // Check if account is active
        if (isset($applicant['status']) && $applicant['status'] !== 'active') {
            error_log("Login failed: Account inactive for applicant ID: " . $applicant['id']);
            $_SESSION['flash_error'] = 'Your account is inactive. Please contact support at info@fctcns.edu.ng';
            $this->redirect('/applicant/login');
            return;
        }
        
        // Clear login value from session on successful login
        unset($_SESSION['login_value']);
        unset($_SESSION['login_error']);
        unset($_SESSION['password_error']);
        
        // Set session
        $_SESSION['applicant_id'] = $applicant['id'];
        $_SESSION['applicant_email'] = $applicant['email'];
        $_SESSION['applicant_name'] = trim(($applicant['first_name'] ?? '') . ' ' . ($applicant['last_name'] ?? ''));
        
        // Set security fingerprint
        $_SESSION['security_fingerprint'] = $this->generateSessionFingerprint();
        $_SESSION['last_activity'] = time();
        
        // Log successful login
        error_log("Login successful for applicant ID: " . $applicant['id'] . ", Email: " . $applicant['email']);
        
        // Get the application for this applicant
        $application = $this->applicationModel->getByApplicantId($applicant['id']);
        
        // Redirect to appropriate step
        $this->redirectToProperStep($application);
    }
    
    /**
     * Applicant logout
     */
    public function logout() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        // Clear all session data
        $_SESSION = array();
        
        // Destroy session cookie
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $params["path"], $params["domain"],
                $params["secure"], $params["httponly"]
            );
        }
        
        // Destroy session
        session_destroy();
        
        $_SESSION['flash_success'] = 'You have been logged out successfully.';
        $this->redirect('/applicant/login');
    }
    
    /**
     * Show forgot password page
     */
    public function forgotPassword() {
        // Initialize security
        $this->initSecurity();
        
        $this->data = array_merge($this->data, [
            'pageTitle' => 'Forgot Password - Application Portal',
            'csrf_token' => $this->csrfToken()
        ]);
        
        $this->render('applications/forgot-password');
    }
    
    /**
     * Process forgot password - Send reset link to email
     */
    public function processForgotPassword() {
        // Initialize security
        $this->initSecurity();
        
        // Clear any existing email value
        unset($_SESSION['email_value']);
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/applicant/forgot-password');
            return;
        }
        
        // Validate CSRF
        if (!$this->validateCsrfToken()) {
            $_SESSION['flash_error'] = 'Security token expired. Please try again.';
            $this->redirect('/applicant/forgot-password');
            return;
        }
        
        $email = trim($_POST['email'] ?? '');
        
        // Store email to repopulate form on error
        $_SESSION['email_value'] = $email;
        
        // Validate email
        if (empty($email)) {
            $_SESSION['flash_error'] = 'Please enter your email address.';
            $this->redirect('/applicant/forgot-password');
            return;
        }
        
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $_SESSION['flash_error'] = 'Please enter a valid email address.';
            $this->redirect('/applicant/forgot-password');
            return;
        }
        
        // Find applicant by email
        $applicant = $this->applicantModel->findByEmail($email);
        
        if (!$applicant) {
            // For security, don't reveal that email doesn't exist
            error_log("Password reset requested for non-existent email: " . $email);
            $_SESSION['flash_success'] = 'If your email is registered, you will receive a password reset link shortly.';
            unset($_SESSION['email_value']);
            $this->redirect('/applicant/forgot-password');
            return;
        }
        
        try {
            // Generate reset token
            $resetToken = bin2hex(random_bytes(32));
            $resetExpiry = date('Y-m-d H:i:s', strtotime('+1 hour'));
            
            // Save reset token to database
            $this->applicantModel->update(
                [
                    'reset_token' => $resetToken,
                    'reset_expires' => $resetExpiry
                ],
                'id = :id',
                ['id' => $applicant['id']]
            );
            
            // Send reset email
            $this->sendPasswordResetEmail($email, $resetToken);
            
            $_SESSION['flash_success'] = 'Password reset instructions have been sent to your email.';
            unset($_SESSION['email_value']);
            
        } catch (Exception $e) {
            error_log("Password reset error: " . $e->getMessage());
            $_SESSION['flash_error'] = 'An error occurred. Please try again later.';
        }
        
        $this->redirect('/applicant/forgot-password');
    }
    
    /**
     * Send password reset email
     */
    private function sendPasswordResetEmail($email, $token) {
        $resetLink = BASE_URL . '/applicant/reset-password?token=' . $token;
        
        $subject = "Password Reset - FCT College of Nursing Sciences";
        
        $message = "
        <!DOCTYPE html>
        <html>
        <head>
            <style>
                body { font-family: Arial, sans-serif; line-height: 1.6; }
                .container { max-width: 600px; margin: 0 auto; padding: 20px; }
                .header { background: #6B4E9B; color: white; padding: 20px; text-align: center; }
                .content { padding: 20px; background: #f9f9f9; }
                .button { display: inline-block; padding: 10px 20px; background: #6B4E9B; color: white; text-decoration: none; border-radius: 5px; }
                .footer { text-align: center; padding: 20px; color: #666; font-size: 12px; }
            </style>
        </head>
        <body>
            <div class='container'>
                <div class='header'>
                    <h2>FCT College of Nursing Sciences</h2>
                    <p>Password Reset Request</p>
                </div>
                <div class='content'>
                    <h3>Hello!</h3>
                    <p>You recently requested to reset your password. Click the button below to proceed:</p>
                    
                    <p style='text-align: center;'>
                        <a href='{$resetLink}' class='button'>Reset Password</a>
                    </p>
                    
                    <p>If you didn't request this, please ignore this email. The link will expire in 1 hour.</p>
                    
                    <p><strong>Note:</strong> For security, never share this link with anyone.</p>
                </div>
                <div class='footer'>
                    <p>&copy; " . date('Y') . " FCT College of Nursing Sciences</p>
                    <p>Contact: info@fctcns.edu.ng | Support: 07039837749</p>
                </div>
            </div>
        </body>
        </html>
        ";
        
        // Use your email helper
        if (file_exists(APP_PATH . '/helpers/EmailHelper.php')) {
            require_once APP_PATH . '/helpers/EmailHelper.php';
            $emailHelper = new EmailHelper();
            $emailHelper->sendEmail($email, $subject, $message);
        } else {
            // Fallback - log the email
            error_log("Password reset email would be sent to: $email with link: $resetLink");
        }
    }
    
    /**
     * Show reset password page
     */
    public function resetPassword() {
        // Initialize security
        $this->initSecurity();
        
        $token = $_GET['token'] ?? '';
        
        if (empty($token)) {
            $_SESSION['flash_error'] = 'Invalid password reset link.';
            $this->redirect('/applicant/forgot-password');
            return;
        }
        
        // Verify token
        $applicant = $this->applicantModel->findByResetToken($token);
        
        if (!$applicant) {
            $_SESSION['flash_error'] = 'Invalid or expired reset link. Please request a new one.';
            $this->redirect('/applicant/forgot-password');
            return;
        }
        
        $this->data = array_merge($this->data, [
            'pageTitle' => 'Reset Password',
            'token' => $token,
            'email' => $applicant['email'],
            'csrf_token' => $this->csrfToken()
        ]);
        
        $this->render('applications/reset-password');
    }
    
    /**
     * Process reset password
     */
    public function processResetPassword() {
        // Initialize security
        $this->initSecurity();
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/applicant/forgot-password');
            return;
        }
        
        // Validate CSRF
        if (!$this->validateCsrfToken()) {
            $_SESSION['flash_error'] = 'Security token expired. Please try again.';
            $this->redirect('/applicant/forgot-password');
            return;
        }
        
        $token = $_POST['token'] ?? '';
        $password = $_POST['password'] ?? '';
        $confirm = $_POST['confirm_password'] ?? '';
        
        if (empty($token)) {
            $_SESSION['flash_error'] = 'Invalid reset token.';
            $this->redirect('/applicant/forgot-password');
            return;
        }
        
        // Validate password
        if (empty($password)) {
            $_SESSION['flash_error'] = 'Please enter a new password.';
            $this->redirect('/applicant/reset-password?token=' . urlencode($token));
            return;
        }
        
        if (strlen($password) < 8) {
            $_SESSION['flash_error'] = 'Password must be at least 8 characters long.';
            $this->redirect('/applicant/reset-password?token=' . urlencode($token));
            return;
        }
        
        if ($password !== $confirm) {
            $_SESSION['flash_error'] = 'Passwords do not match.';
            $this->redirect('/applicant/reset-password?token=' . urlencode($token));
            return;
        }
        
        // Verify token
        $applicant = $this->applicantModel->findByResetToken($token);
        
        if (!$applicant) {
            $_SESSION['flash_error'] = 'Invalid or expired reset link. Please request a new one.';
            $this->redirect('/applicant/forgot-password');
            return;
        }
        
        try {
            // Update password
            $this->applicantModel->update(
                [
                    'password' => password_hash($password, PASSWORD_DEFAULT),
                    'reset_token' => null,
                    'reset_expires' => null
                ],
                'id = :id',
                ['id' => $applicant['id']]
            );
            
            $_SESSION['flash_success'] = 'Password reset successfully. You can now login with your new password.';
            $this->redirect('/applicant/login');
            
        } catch (Exception $e) {
            error_log("Password reset error: " . $e->getMessage());
            $_SESSION['flash_error'] = 'An error occurred. Please try again.';
            $this->redirect('/applicant/reset-password?token=' . urlencode($token));
        }
    }

    // ============================================
    // LEGACY METHODS (for backward compatibility)
    // ============================================

    /**
     * Show step 1: JAMB verification - SECURITY FIXED
     * Now checks if user has completed registration and email verification
     */
    public function step1() {
        // Initialize security
        $this->initSecurity();
        
        // Check if user is logged in
        if (!$this->isApplicantLoggedIn()) {
            $_SESSION['flash_error'] = 'Please login to continue';
            $_SESSION['redirect_after_login'] = '/apply/step/1';
            $this->redirect('/applicant/login');
            return;
        }
        
        $applicantId = $_SESSION['applicant_id'];
        
        // CRITICAL: Check if email is verified
        $applicant = $this->applicantModel->find($applicantId);
        
        if (!$applicant) {
            $_SESSION['flash_error'] = 'Applicant record not found. Please register again.';
            $this->logout();
            $this->redirect('/apply/register');
            return;
        }
        
        // Check if email is verified
        if (!isset($applicant['email_verified']) || $applicant['email_verified'] != 1) {
            error_log("SECURITY: Attempted to access JAMB verification without email verification - User: " . $applicantId);
            
            // Store email in session for resend
            $_SESSION['verification_email'] = $applicant['email'];
            
            // Set appropriate flash message
            $_SESSION['flash_error'] = 'Please verify your email address before proceeding with JAMB verification. Check your inbox for the verification link.';
            
            // Redirect to email verification page
            $this->redirect('/apply/verify-email?email=' . urlencode($applicant['email']));
            return;
        }
        
        // Get application
        $application = $this->applicationModel->getByApplicantId($applicantId);
        
        // SECURITY: Validate step access
        if (!$this->validateStepAccess($application, 1, 'step1')) {
            $this->redirectToProperStep($application);
            return;
        }
        
        // Check if JAMB already verified
        if ($application && !empty($application['jamb_number'])) {
            $this->data['jamb_already_verified'] = true;
            $this->data['jamb_number'] = $application['jamb_number'];
            $this->data['jamb_name'] = ($application['first_name'] ?? '') . ' ' . ($application['last_name'] ?? '');
            $this->data['jamb_verified'] = true;
            $this->data['jamb_data'] = [
                'jamb_number' => $application['jamb_number'],
                'first_name' => $application['first_name'],
                'last_name' => $application['last_name'],
                'other_names' => $application['other_names'],
                'gender' => $application['gender'],
                'state_of_origin' => $application['state_of_origin'],
                'lga' => $application['lga'],
                'score' => $application['utme_score']
            ];
        }
        
        $terms = $this->termsModel->getForAcceptance();
        $settings = $this->settingsModel->getAllSettings();
        
        $this->data = array_merge($this->data, [
            'pageTitle' => 'Step 1: JAMB Verification',
            'terms' => $terms,
            'settings' => $settings,
            'csrf_token' => $this->csrfToken(),
            'applicant' => $applicant,
            'email_verified' => true
        ]);
        
        $this->render('applications/step1');
    }

    /**
     * Show step 2: Application form (Legacy Flow) - FIXED 2c with credit summary
     */
    public function step2() {
        // Initialize security
        $this->initSecurity();
        
        // Check login
        if (!$this->isApplicantLoggedIn()) {
            $_SESSION['flash_error'] = 'Please login to continue';
            $this->redirect('/applicant/login');
            return;
        }
        
        // Check registration complete (email verified)
        if (!$this->checkRegistrationComplete()) {
            return; // checkRegistrationComplete already redirects
        }
        
        $applicantId = $_SESSION['applicant_id'];
        
        // Get application
        $application = $this->applicationModel->getByApplicantId($applicantId);
        
        // SECURITY: Validate step access
        if (!$this->validateStepAccess($application, 2, 'step2')) {
            $this->redirectToProperStep($application);
            return;
        }
        
        // Check if JAMB has been verified
        if (!isset($_SESSION['jamb_verification']) || !$_SESSION['jamb_verification']) {
            // Try to restore from database
            if ($application && !empty($application['jamb_number'])) {
                $_SESSION['jamb_verification'] = [
                    'jamb_number' => $application['jamb_number'],
                    'first_name' => $application['first_name'],
                    'last_name' => $application['last_name'],
                    'other_names' => $application['other_names'],
                    'gender' => $application['gender'],
                    'state_of_origin' => $application['state_of_origin'],
                    'lga' => $application['lga'],
                    'score' => $application['utme_score']
                ];
            } else {
                $_SESSION['flash_error'] = 'Please verify your JAMB number first';
                $this->redirect('/apply/step/1');
                return;
            }
        }
        
        if (!$application) {
            $_SESSION['flash_error'] = 'Application not found. Please start over.';
            $this->redirect('/apply/step/1');
            return;
        }
        
        // Get O'Level results
        require_once MODELS_PATH . '/application/OlevelResultModel.php';
        $olevelModel = new OlevelResultModel();
        $olevel_results = $olevelModel->getByApplicationId($application['id']);
        
        // IMPORTANT: Ensure O'Level results are indexed sequentially
        if (!empty($olevel_results)) {
            $olevel_results = array_values($olevel_results);
        }
        
        // Check O'Level validation status using legacy method
        $olevelValidation = $this->validateOlevelCredits($olevel_results);
        
        // FIX 2c: Get detailed credit summary for the view
        $creditSummary = $olevelModel->getCreditCheckSummary($application['id']);

        // Carry over any O'Level session errors
        $olevelSessionError = $_SESSION['olevel_error'] ?? null;
        unset($_SESSION['olevel_error'], $_SESSION['olevel_missing'], $_SESSION['olevel_failed'],
              $_SESSION['olevel_credits'], $_SESSION['olevel_summary']);
        
        // Get passport
        require_once MODELS_PATH . '/application/ApplicationDocumentModel.php';
        $docModel = new ApplicationDocumentModel();
        $passport = $docModel->getPassport($application['id']);
        
        // Get applicant details
        $applicant = $this->applicantModel->find($applicantId);
        
        $this->data = array_merge($this->data, [
            'pageTitle'          => 'Step 2: Application Form',
            'application'        => $application,
            'applicant'          => $applicant,
            'jamb_data'          => $_SESSION['jamb_verification'],
            'olevel_results'     => $olevel_results,
            'olevel_validation'  => $olevelValidation,
            'credit_summary'     => $creditSummary,
            'olevel_session_error' => $olevelSessionError,
            'passport'           => $passport,
            'states'             => $this->getStates(),
            'programs'           => $this->getPrograms(),
            'csrf_token'         => $this->csrfToken()
        ]);
        
        $this->render('applications/step2');
    }
    
    /**
     * Show step 3: Payment (Legacy Flow) - SECURITY FIXED
     */
    public function step3() {
        // Initialize security
        $this->initSecurity();
        
        error_log("=== STEP 3 LOADED ===");
        
        // Check login
        if (!$this->isApplicantLoggedIn()) {
            $_SESSION['flash_error'] = 'Please login to continue';
            $this->redirect('/applicant/login');
            return;
        }
        
        // Check registration complete (email verified)
        if (!$this->checkRegistrationComplete()) {
            return; // checkRegistrationComplete already redirects
        }
        
        $applicantId = $_SESSION['applicant_id'];
        
        // Get application
        $application = $this->applicationModel->getByApplicantId($applicantId);
        
        if (!$application) {
            $_SESSION['flash_error'] = 'Application not found';
            $this->redirect('/apply/step/1');
            return;
        }
        
        // SECURITY: Validate step access (FIX 2b is already in validateStepAccess)
        if (!$this->validateStepAccess($application, 3, 'step3')) {
            $this->redirectToProperStep($application);
            return;
        }
        
        // Check if already paid
        $hasPaid = $this->paymentModel->hasSuccessfulPayment($application['id']);
        if ($hasPaid) {
            error_log("Already paid, redirecting to step 4");
            $this->redirect('/apply/step/4');
            return;
        }
        
        // Check if application is complete enough for payment
        if (empty($application['date_of_birth']) || empty($application['phone']) || empty($application['address'])) {
            $_SESSION['flash_error'] = 'Please complete your application form first';
            $this->redirect('/apply/step/2');
            return;
        }
        
        // Restore JAMB data if needed
        if (!isset($_SESSION['jamb_verification']) && !empty($application['jamb_number'])) {
            $_SESSION['jamb_verification'] = [
                'jamb_number' => $application['jamb_number'],
                'first_name' => $application['first_name'],
                'last_name' => $application['last_name'],
                'other_names' => $application['other_names'],
                'gender' => $application['gender'],
                'state_of_origin' => $application['state_of_origin'],
                'lga' => $application['lga'],
                'score' => $application['utme_score']
            ];
        }
        
        // Generate CSRF token
        $csrfToken = $this->csrfToken();
        
        // Get fee settings
        $fee = $this->settingsModel->getApplicationFee();
        $currency = $this->settingsModel->getCurrency();
        
        // Check for pending payment
        $pending_payment = null;
        $payments = $this->paymentModel->getByApplicationId($application['id']);
        if (!empty($payments)) {
            foreach ($payments as $payment) {
                if ($payment['status'] === 'pending') {
                    $pending_payment = $payment;
                    break;
                }
            }
        }
        
        // Get applicant for name display
        $applicant = $this->applicantModel->find($applicantId);
        $applicant_name = trim(
            ($application['first_name'] ?? '') . ' ' . 
            ($application['last_name'] ?? '')
        );
        if (empty($applicant_name)) {
            $applicant_name = $applicant['email'] ?? 'Applicant';
        }
        
        // Check if exam slip exists
        $hasExamSlip = $this->hasExamSlip($application['id']);
        
        $this->data = array_merge($this->data, [
            'pageTitle' => 'Payment - Step 3',
            'application' => $application,
            'applicant' => $applicant,
            'applicant_name' => $applicant_name,
            'fee' => $fee,
            'currency' => $currency,
            'formatted_fee' => $this->settingsModel->getFormattedFee(),
            'csrf_token' => $csrfToken,
            'pending_payment' => $pending_payment,
            'baseUrl' => defined('BASE_URL') ? BASE_URL : '/',
            'has_exam_slip' => $hasExamSlip
        ]);
        
        error_log("Rendering applications/step3 for applicant: " . $applicantId);
        $this->render('applications/step3');
    }

    /**
     * Show step 4: Exam Slip (Legacy Flow) - ENHANCED SECURITY
     */
    public function step4() {
        // Initialize security
        $this->initSecurity();
        
        error_log("=== STEP 4 LOADED ===");
        
        // Check login
        if (!$this->isApplicantLoggedIn()) {
            $_SESSION['flash_error'] = 'Please login to continue';
            $this->redirect('/applicant/login');
            return;
        }
        
        // Check registration complete (email verified)
        if (!$this->checkRegistrationComplete()) {
            return; // checkRegistrationComplete already redirects
        }
        
        $applicantId = $_SESSION['applicant_id'];
        
        // Get and validate application
        $application = $this->applicationModel->getByApplicantId($applicantId);
        
        if (!$application) {
            $_SESSION['flash_error'] = 'Application not found';
            $this->redirect('/apply/step/1');
            return;
        }
        
        // SECURITY: Validate step access
        if (!$this->validateStepAccess($application, 4, 'step4')) {
            $this->redirectToProperStep($application);
            return;
        }
        
        // VERIFY PAYMENT STATUS IN DATABASE
        $hasPaid = $this->paymentModel->hasSuccessfulPayment($application['id']);
        
        if (!$hasPaid) {
            error_log("SECURITY: Unauthorized attempt to access step 4 - No payment found for application: " . $application['id']);
            $_SESSION['flash_error'] = 'Payment required. Please complete your payment first.';
            $this->redirect('/apply/step/3');
            return;
        }
        
        // Double-check payment ownership
        $payments = $this->paymentModel->getByApplicationId($application['id']);
        $validPayment = false;
        
        foreach ($payments as $payment) {
            if ($payment['status'] === 'success' && $payment['applicant_id'] == $applicantId) {
                $validPayment = true;
                break;
            }
        }
        
        if (!$validPayment) {
            error_log("SECURITY: Invalid payment ownership for application: " . $application['id']);
            $_SESSION['flash_error'] = 'Invalid payment record. Please contact support.';
            $this->redirect('/apply/step/3');
            return;
        }
        
        // Get exam slip
        require_once MODELS_PATH . '/application/ExamSlipModel.php';
        $examSlipModel = new ExamSlipModel();
        $examSlip = $examSlipModel->getByApplicationId($application['id']);
        
        // Generate exam slip if it doesn't exist
        if (!$examSlip) {
            error_log("Generating exam slip for verified payment - Application: " . $application['id']);
            $examSlip = $this->generateExamSlip($application['id']);
            
            if (!$examSlip) {
                error_log("CRITICAL: Failed to generate exam slip despite valid payment");
                $_SESSION['flash_error'] = 'Error generating exam slip. Please contact support.';
                $this->redirect('/apply/step/3');
                return;
            }
        }
        
        // Get O'Level results for display
        require_once MODELS_PATH . '/application/OlevelResultModel.php';
        $olevelModel = new OlevelResultModel();
        $olevel_results = $olevelModel->getByApplicationId($application['id']);
        
        // IMPORTANT: Ensure O'Level results are indexed sequentially
        if (!empty($olevel_results)) {
            $olevel_results = array_values($olevel_results);
        }
        
        // Get applicant for display
        $applicant = $this->applicantModel->find($applicantId);
        
        $this->data = array_merge($this->data, [
            'pageTitle' => 'Examination Slip - Step 4',
            'application' => $application,
            'applicant' => $applicant,
            'exam_slip' => $examSlip,
            'olevel_results' => $olevel_results,
            'has_exam_slip' => true
        ]);
        
        error_log("Granting access to step 4 for verified applicant: " . $applicantId);
        $this->render('applications/step4');
    }

    // ============================================
    // JAMB VERIFICATION METHODS - SECURITY FIXED
    // ============================================

    /**
     * Verify JAMB number (AJAX endpoint) - COMPLETELY FIXED VERSION
     * Now with multiple security layers including email verification check
     */
    public function verifyJamb() {
        // Set header for JSON response FIRST - before ANY output
        header('Content-Type: application/json');
        
        // Initialize security
        $this->initSecurity();
        
        // Check if user is logged in
        if (!isset($_SESSION['applicant_id'])) {
            echo json_encode(['success' => false, 'message' => 'Please login first']);
            return;
        }
        
        // Only accept POST requests
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['success' => false, 'message' => 'Invalid request method']);
            return;
        }
        
        $applicantId = $_SESSION['applicant_id'];
        
        // Get the JAMB number - support both JSON and form data
        $input = json_decode(file_get_contents('php://input'), true);
        $jambNumber = '';
        $csrfToken = '';
        
        if ($input && is_array($input)) {
            // JSON input
            $jambNumber = trim($input['jamb_number'] ?? '');
            $csrfToken = $input['csrf_token'] ?? '';
        } else {
            // Form data
            $jambNumber = trim($_POST['jamb_number'] ?? '');
            $csrfToken = $_POST['csrf_token'] ?? '';
        }
        
        // Validate CSRF token
        if (!$this->validateCsrfToken($csrfToken)) {
            error_log("JAMB verification: CSRF validation failed for applicant $applicantId");
            echo json_encode(['success' => false, 'message' => 'Security token expired. Please refresh the page and try again.']);
            return;
        }
        
        // Validate JAMB number
        if (empty($jambNumber)) {
            echo json_encode(['success' => false, 'message' => 'JAMB number is required']);
            return;
        }
        
        // Clean the JAMB number
        $jambNumber = strtoupper(trim(preg_replace('/[^a-zA-Z0-9]/', '', $jambNumber)));
        
        if (strlen($jambNumber) < 10 || strlen($jambNumber) > 14) {
            echo json_encode(['success' => false, 'message' => 'Invalid JAMB number format. Should be 10-14 characters.']);
            return;
        }
        
        try {
            // STEP 1: Get applicant record
            $applicant = $this->applicantModel->find($applicantId);
            
            if (!$applicant) {
                error_log("JAMB verification failed: Applicant not found - ID: " . $applicantId);
                echo json_encode([
                    'success' => false, 
                    'message' => 'Applicant record not found. Please complete registration first.'
                ]);
                return;
            }
            
            // STEP 2: Check if email is verified
            if (!isset($applicant['email_verified']) || $applicant['email_verified'] != 1) {
                error_log("SECURITY: JAMB verification attempted without email verification - User: " . $applicantId);
                
                echo json_encode([
                    'success' => false,
                    'email_not_verified' => true,
                    'message' => 'Please verify your email address before verifying JAMB. Check your inbox for the verification link.',
                    'redirect' => '/apply/verify-email?email=' . urlencode($applicant['email'])
                ]);
                return;
            }
            
            // STEP 3: Get application
            $application = $this->applicationModel->getByApplicantId($applicantId);
            
            // STEP 4: Check if JAMB already verified (can't change)
            if ($application && !empty($application['jamb_number'])) {
                echo json_encode([
                    'success' => false, 
                    'message' => 'JAMB number has already been verified. You cannot change it.'
                ]);
                return;
            }
            
            // STEP 5: Check if payment has been made (can't modify after payment)
            if ($application && !empty($application['id'])) {
                $hasPaid = $this->paymentModel->hasSuccessfulPayment($application['id']);
                if ($hasPaid) {
                    echo json_encode([
                        'success' => false, 
                        'message' => 'Cannot modify application after payment.'
                    ]);
                    return;
                }
            }
            
            // STEP 6: Load JambCandidateModel if not already loaded
            if (!isset($this->jambModel)) {
                require_once MODELS_PATH . '/JambCandidateModel.php';
                $this->jambModel = new JambCandidateModel();
            }
            
            // STEP 7: Find JAMB candidate
            $jambCandidate = $this->jambModel->findByJambNumber($jambNumber);
            
            if (!$jambCandidate) {
                echo json_encode([
                    'success' => false, 
                    'message' => 'JAMB number not found in our records. Please check and try again.'
                ]);
                return;
            }
            
            // STEP 8: Check if already used
            if (!empty($jambCandidate['is_used']) && $jambCandidate['is_used'] == 1) {
                echo json_encode([
                    'success' => false, 
                    'message' => 'This JAMB number has already been used for another application'
                ]);
                return;
            }
            
            // STEP 9: Check score requirement
            $minScore = $this->settingsModel->get('min_utme_score', 170);
            if ($jambCandidate['aggregate_score'] < $minScore) {
                echo json_encode([
                    'success' => false, 
                    'message' => "Your score of {$jambCandidate['aggregate_score']} is below the minimum requirement of {$minScore}"
                ]);
                return;
            }
            
            // STEP 10: Begin transaction
            $ownTransaction = false;
            if (!$this->applicationModel->getConnection()->inTransaction()) {
                $this->applicationModel->beginTransaction();
                $ownTransaction = true;
            }
            
            if (!$application) {
                // Create new application
                $applicationData = [
                    'applicant_id' => $applicantId,
                    'jamb_number' => $jambCandidate['jamb_number'],
                    'jamb_candidate_id' => $jambCandidate['id'],
                    'first_name' => $jambCandidate['first_name'],
                    'last_name' => $jambCandidate['last_name'],
                    'other_names' => $jambCandidate['other_names'] ?? '',
                    'gender' => $jambCandidate['gender'],
                    'state_of_origin' => $jambCandidate['state_of_origin'],
                    'lga' => $jambCandidate['lga'],
                    'utme_score' => $jambCandidate['aggregate_score'],
                    'program_choice_1' => 'ND Nursing',
                    'application_step' => 2,
                    'status' => 'pending',
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s')
                ];
                
                $applicationId = $this->applicationModel->createApplication($applicantId, $applicationData);
                
                if (!$applicationId) {
                    throw new Exception("Failed to create application record");
                }
                
                error_log("Created new application ID: " . $applicationId . " for applicant: " . $applicantId);
                
            } else {
                // Update existing application
                $updateData = [
                    'jamb_number' => $jambCandidate['jamb_number'],
                    'jamb_candidate_id' => $jambCandidate['id'],
                    'first_name' => $jambCandidate['first_name'],
                    'last_name' => $jambCandidate['last_name'],
                    'other_names' => $jambCandidate['other_names'] ?? '',
                    'gender' => $jambCandidate['gender'],
                    'state_of_origin' => $jambCandidate['state_of_origin'],
                    'lga' => $jambCandidate['lga'],
                    'utme_score' => $jambCandidate['aggregate_score'],
                    'application_step' => 2,
                    'updated_at' => date('Y-m-d H:i:s')
                ];
                
                $updated = $this->applicationModel->updateApplication($application['id'], $updateData);
                
                if (!$updated) {
                    throw new Exception("Failed to update application record");
                }
                
                error_log("Updated existing application ID: " . $application['id'] . " with JAMB data");
            }
            
            // STEP 11: Mark JAMB candidate as used (permanent lock)
            $marked = $this->jambModel->markAsUsed($jambCandidate['id'], $applicantId);
            
            if (!$marked) {
                throw new Exception("Failed to mark JAMB as used");
            }
            
            // STEP 12: Store in session
            $_SESSION['jamb_verification'] = [
                'id' => $jambCandidate['id'],
                'jamb_number' => $jambCandidate['jamb_number'],
                'first_name' => $jambCandidate['first_name'],
                'last_name' => $jambCandidate['last_name'],
                'other_names' => $jambCandidate['other_names'] ?? '',
                'gender' => $jambCandidate['gender'],
                'state_of_origin' => $jambCandidate['state_of_origin'],
                'lga' => $jambCandidate['lga'],
                'score' => $jambCandidate['aggregate_score'],
                'verified_at' => time()
            ];
            
            // Commit only if we started the transaction
            if ($ownTransaction) {
                $this->applicationModel->commit();
            }
            
            echo json_encode([
                'success' => true,
                'message' => 'JAMB verified successfully',
                'data' => [
                    'jamb_number' => $jambCandidate['jamb_number'],
                    'name' => trim($jambCandidate['first_name'] . ' ' . $jambCandidate['last_name']),
                    'first_name' => $jambCandidate['first_name'],
                    'last_name' => $jambCandidate['last_name'],
                    'other_names' => $jambCandidate['other_names'] ?? '',
                    'gender' => $jambCandidate['gender'],
                    'state_of_origin' => $jambCandidate['state_of_origin'],
                    'lga' => $jambCandidate['lga'],
                    'score' => $jambCandidate['aggregate_score']
                ]
            ]);
            
        } catch (Throwable $e) {
            // Rollback only if we started the transaction
            if (isset($ownTransaction) && $ownTransaction && $this->applicationModel->getConnection()->inTransaction()) {
                $this->applicationModel->getConnection()->rollBack();
            }
            error_log("JAMB verification error: " . $e->getMessage());
            error_log("Stack trace: " . $e->getTraceAsString());
            
            echo json_encode([
                'success' => false, 
                'message' => 'An error occurred. Please try again or contact support.'
            ]);
        }
    }

    /**
     * Check payment status (AJAX endpoint)
     */
    public function checkPaymentStatus() {
        header('Content-Type: application/json');
        
        // Initialize security
        $this->initSecurity();
        
        if (!isset($_SESSION['applicant_id'])) {
            echo json_encode(['success' => false, 'message' => 'Please login first']);
            return;
        }
        
        $rrr = $_GET['rrr'] ?? '';
        
        if (empty($rrr)) {
            echo json_encode(['success' => false, 'message' => 'RRR required']);
            return;
        }
        
        try {
            $payment = $this->paymentModel->getByRRR($rrr);
            
            if (!$payment) {
                echo json_encode(['success' => false, 'message' => 'Payment not found']);
                return;
            }
            
            // Verify ownership
            if ($payment['applicant_id'] != $_SESSION['applicant_id']) {
                echo json_encode(['success' => false, 'message' => 'Unauthorized access']);
                return;
            }
            
            echo json_encode([
                'success' => true,
                'status' => $payment['status'],
                'payment_date' => $payment['payment_date'] ?? null,
                'message' => $payment['status'] === 'success' ? 'Payment completed' : 'Payment pending'
            ]);
            
        } catch (Exception $e) {
            error_log("Check payment status error: " . $e->getMessage());
            echo json_encode(['success' => false, 'message' => 'Server error occurred']);
        }
    }

    /**
     * Redirect helper - overrides parent for consistency
     * 
     * @param string $url URL to redirect to
     * @param int $statusCode HTTP status code (default 302)
     * @return void
     */
    protected function redirect($url, $statusCode = 302) {
        if (!headers_sent()) {
            header('Location: ' . $url, true, $statusCode);
            exit;
        }
        
        echo '<script>window.location.href="' . $url . '";</script>';
        exit;
    }
}