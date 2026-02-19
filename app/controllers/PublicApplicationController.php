<?php
/**
 * Public Application Controller
 * 
 * Handles public-facing application processes
 */

require_once __DIR__ . '/ApplicationBaseController.php';

class PublicApplicationController extends ApplicationBaseController {
    
    private $jambModel;
    private $termsModel;
    private $settingsModel;
    private $paymentModel;
    private $applicantModel;
    
    /**
     * Constructor
     */
    public function __construct() {
        parent::__construct();
        
        // Load additional models
        require_once MODELS_PATH . '/JambCandidateModel.php';
        require_once MODELS_PATH . '/application/TermsModel.php';
        require_once MODELS_PATH . '/application/OlevelResultModel.php';
        require_once MODELS_PATH . '/application/ApplicationDocumentModel.php';
        require_once MODELS_PATH . '/application/ExamSlipModel.php';
        require_once MODELS_PATH . '/application/SettingsModel.php';
        require_once MODELS_PATH . '/application/PaymentModel.php';
        require_once MODELS_PATH . '/application/ApplicantModel.php';
        
        $this->jambModel = new JambCandidateModel();
        $this->termsModel = new TermsModel();
        $this->settingsModel = new SettingsModel();
        $this->paymentModel = new PaymentModel();
        $this->applicantModel = new ApplicantModel();
        
        // Set layout
        $this->layout = 'application';
    }
    
    // ============================================
    // APPLICATION LANDING PAGE
    // ============================================
    
    /**
     * Show application landing page
     */
    public function landing() {
        // Get settings for display
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
            
            if ($application) {
                $this->redirect('/apply/step/' . $application['application_step']);
            } else {
                $this->redirect('/apply/step/1');
            }
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
     * Process registration (Step 1 - New Flow)
     */
    public function processRegistration() {
        // Start session
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /apply/register');
            exit;
        }
        
        // Validate CSRF
        if (!$this->validateCsrfToken()) {
            $_SESSION['flash_error'] = 'Security token expired. Please try again.';
            header('Location: /apply/register');
            exit;
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
            header('Location: /apply/register');
            exit;
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
            
            header('Location: /apply/verify-email');
            exit;
            
        } catch (Exception $e) {
            error_log("Registration error: " . $e->getMessage());
            $_SESSION['flash_error'] = 'An error occurred. Please try again.';
            header('Location: /apply/register');
            exit;
        }
    }

    /**
     * Verify email with token or show email sent page
     */
    public function verifyEmail() {
        // Start session
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        // Get token from URL
        $token = $_GET['token'] ?? '';
        
        // Get email from URL or session
        $email = $_GET['email'] ?? $_SESSION['registration_email'] ?? '';
        
        // If no token, show the "check your email" page
        if (empty($token)) {
            $this->data['email'] = $email;
            $this->data['pageTitle'] = 'Verify Your Email';
            $this->data['email_sent'] = true;
            
            $this->render('applications/verify-email');
            return;
        }
        
        // Token provided - verify the email
        $applicant = $this->applicantModel->findByVerificationToken($token);
        
        if (!$applicant) {
            $this->data['error'] = 'Invalid or expired verification link';
            $this->data['pageTitle'] = 'Verification Failed';
            $this->render('applications/verify-email');
            return;
        }
        
        // Check if already verified
        if ($applicant['email_verified'] == 1) {
            $this->data['message'] = 'Email already verified. Please login.';
            $this->data['pageTitle'] = 'Email Already Verified';
            $this->render('applications/verify-email');
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
            // Auto-login the applicant
            $_SESSION['applicant_id'] = $applicant['id'];
            $_SESSION['applicant_email'] = $applicant['email'];
            $_SESSION['applicant_name'] = ($applicant['first_name'] ?? '') . ' ' . ($applicant['last_name'] ?? '');
            $_SESSION['applicant_login_time'] = time();
            
            // Clear registration email from session
            unset($_SESSION['registration_email']);
            
            $this->data['verified'] = true;
            $this->data['applicant_name'] = $applicant['first_name'] . ' ' . $applicant['last_name'];
            $this->data['applicant_email'] = $applicant['email'];
            $this->data['pageTitle'] = 'Email Verified Successfully';
        } else {
            $this->data['error'] = 'Failed to verify email. Please try again.';
            $this->data['pageTitle'] = 'Verification Failed';
        }
        
        $this->render('applications/verify-email');
    }

    /**
     * Resend verification email
     */
    public function resendVerification() {
        // Start session
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        // Get email from query string or session
        $email = $_GET['email'] ?? $_SESSION['registration_email'] ?? '';
        
        if (empty($email)) {
            $_SESSION['flash_error'] = 'Email address not found. Please register again.';
            header('Location: /apply/register');
            exit;
        }
        
        // Find applicant by email
        $applicant = $this->applicantModel->findByEmail($email);
        
        if ($applicant) {
            if ($applicant['email_verified'] == 1) {
                $_SESSION['flash_success'] = 'Your email is already verified. Please login.';
                header('Location: /applicant/login');
                exit;
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
            
            header('Location: /apply/verify-email?email=' . urlencode($email));
            exit;
        } else {
            $_SESSION['flash_error'] = 'Email address not found in our records. Please register again.';
            header('Location: /apply/register');
            exit;
        }
    }

    /**
     * Send verification email
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
        // Start session
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        // Check if logged in
        if (!isset($_SESSION['applicant_id'])) {
            $_SESSION['flash_error'] = 'Please login to continue';
            header('Location: /applicant/login');
            exit;
        }
        
        $applicantId = $_SESSION['applicant_id'];
        $applicant = $this->applicantModel->find($applicantId);
        
        if (!$applicant) {
            $_SESSION['flash_error'] = 'Applicant not found';
            header('Location: /applicant/login');
            exit;
        }
        
        if (!$applicant['email_verified']) {
            $_SESSION['flash_error'] = 'Please verify your email first';
            header('Location: /apply/verify-email');
            exit;
        }
        
        // Get application
        $application = $this->applicationModel->getByApplicantId($applicantId);
        
        if (!$application) {
            $_SESSION['flash_error'] = 'Application not found. Please start over.';
            header('Location: /apply/step/1');
            exit;
        }
        
        // Check if JAMB is verified
        if (empty($application['jamb_number'])) {
            $_SESSION['flash_error'] = 'Please verify your JAMB number first';
            header('Location: /apply/step/1');
            exit;
        }
        
        // Restore JAMB data to session if missing
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
        
        // Parse O'Level results from JSON if exists
        $olevelFiles = [];
        if (!empty($application['olevel_results'])) {
            $olevelFiles = json_decode($application['olevel_results'], true);
            if (!is_array($olevelFiles)) {
                $olevelFiles = [];
            }
        }
        
        // Get O'Level results from dedicated model
        require_once MODELS_PATH . '/application/OlevelResultModel.php';
        $olevelModel = new OlevelResultModel();
        $olevel_results = $olevelModel->getByApplicationId($application['id']);
        
        // Get passport from document model
        require_once MODELS_PATH . '/application/ApplicationDocumentModel.php';
        $docModel = new ApplicationDocumentModel();
        $passport = $docModel->getPassport($application['id']);
        
        // Pass data to view
        $this->data = array_merge($this->data, [
            'pageTitle' => 'Application Form - Step 2',
            'application' => $application,
            'applicant' => $applicant,
            'jamb_data' => $_SESSION['jamb_verification'] ?? null,
            'olevel_results' => $olevel_results,
            'passport' => $passport,
            'states' => $this->getStates(),
            'programs' => $this->getPrograms(),
            'csrf_token' => $this->csrfToken()
        ]);
        
        // Redirect to step 2 view
        header('Location: /apply/step/2');
        exit;
    }

    /**
     * Show step 2: Application form view
     */
    public function step2() {
        // Start session
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        // Check login
        if (!$this->isApplicantLoggedIn()) {
            $_SESSION['flash_error'] = 'Please login to continue';
            header('Location: /applicant/login');
            return;
        }
        
        $applicantId = $_SESSION['applicant_id'];
        
        // Check if JAMB has been verified
        if (!isset($_SESSION['jamb_verification']) || !$_SESSION['jamb_verification']) {
            // Try to restore from database
            $application = $this->applicationModel->getByApplicantId($applicantId);
            
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
                header('Location: /apply/step/1');
                return;
            }
        }
        
        // Get application data
        $application = $this->applicationModel->getByApplicantId($applicantId);
        
        if (!$application) {
            $_SESSION['flash_error'] = 'Application not found. Please start over.';
            header('Location: /apply/step/1');
            return;
        }
        
        // Get O'Level results
        require_once MODELS_PATH . '/application/OlevelResultModel.php';
        $olevelModel = new OlevelResultModel();
        $olevel_results = $olevelModel->getByApplicationId($application['id']);
        
        // Get passport
        require_once MODELS_PATH . '/application/ApplicationDocumentModel.php';
        $docModel = new ApplicationDocumentModel();
        $passport = $docModel->getPassport($application['id']);
        
        // Get applicant
        $applicant = $this->applicantModel->find($applicantId);
        
        // Parse O'Level files
        $olevelFiles = [];
        if (!empty($application['olevel_results'])) {
            $olevelFiles = json_decode($application['olevel_results'], true);
            if (!is_array($olevelFiles)) {
                $olevelFiles = [];
            }
        }
        
        // Prepare JAMB data for view
        $jamb_data = $_SESSION['jamb_verification'];
        
        $this->data = array_merge($this->data, [
            'pageTitle' => 'Step 2: Application Form',
            'application' => $application,
            'applicant' => $applicant,
            'jamb_data' => $jamb_data,
            'olevel_results' => $olevel_results,
            'passport' => $passport,
            'states' => $this->getStates(),
            'programs' => $this->getPrograms(),
            'csrf_token' => $this->csrfToken()
        ]);
        
        $this->render('applications/step2');
    }

    /**
     * Save application form
     */
    public function saveApplication() {
        // Set header to JSON
        header('Content-Type: application/json');
        
        // Start session
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
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
            
            // Get form data
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
            
            // Get existing application
            $application = $this->applicationModel->getByApplicantId($applicantId);
            
            if (!$application) {
                echo json_encode(['success' => false, 'message' => 'Application not found']);
                return;
            }
            
            // Prepare update data
            $updateData = [
                'date_of_birth' => $dateOfBirth,
                'phone' => $phone,
                'email' => $email,
                'address' => $address,
                'nationality' => $nationality,
                'program_choice_1' => $programChoice,
                'application_step' => 2,
                'updated_at' => date('Y-m-d H:i:s')
            ];
            
            // Handle O'Level subject data
            if (isset($_POST['olevel']) && is_array($_POST['olevel'])) {
                $formattedResults = [];
                
                foreach ($_POST['olevel'] as $index => $result) {
                    if (empty($result['exam_type']) || empty($result['exam_year'])) {
                        continue;
                    }
                    
                    $formattedResult = [
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
                    
                    $formattedResults[] = $formattedResult;
                }
                
                if (!empty($formattedResults)) {
                    $updateData['olevel_results'] = json_encode($formattedResults);
                }
            }
            
            // Handle file uploads
            $uploadErrors = [];
            
            // Upload passport
            if (isset($_FILES['passport']) && $_FILES['passport']['error'] === UPLOAD_ERR_OK) {
                $passportResult = $this->uploadFile($_FILES['passport'], $applicantId, 'passport');
                if ($passportResult['success']) {
                    $updateData['passport_photo'] = $passportResult['path'];
                } else {
                    $uploadErrors[] = 'Passport: ' . $passportResult['message'];
                }
            }
            
            // Update application
            $updated = $this->applicationModel->updateApplication($application['id'], $updateData);
            
            if (!$updated) {
                echo json_encode(['success' => false, 'message' => 'Failed to update application']);
                return;
            }
            
            // Update applicant
            if (!empty($phone) || !empty($email)) {
                try {
                    $this->applicantModel->update(
                        ['phone' => $phone, 'email' => $email, 'updated_at' => date('Y-m-d H:i:s')],
                        'id = :id',
                        ['id' => $applicantId]
                    );
                } catch (Exception $e) {
                    error_log("Failed to update applicant: " . $e->getMessage());
                }
            }
            
            // Prepare response
            $response = [
                'success' => true,
                'message' => 'Application saved successfully',
                'application_id' => $application['id']
            ];
            
            if (!empty($uploadErrors)) {
                $response['upload_errors'] = $uploadErrors;
            }
            
            // If action is 'next', include redirect
            if (isset($_POST['action']) && $_POST['action'] === 'next') {
                $response['redirect'] = '/apply/step/3';
            }
            
            echo json_encode($response);
            
        } catch (Exception $e) {
            error_log("Save application error: " . $e->getMessage());
            echo json_encode([
                'success' => false,
                'message' => 'Server error: ' . $e->getMessage()
            ]);
        }
    }

    // ============================================
    // STEP 3: PAYMENT - FIXED VERSION
    // ============================================

    /**
     * Show step 3: Payment (FIXED - NO REDIRECT LOOP)
     */
    public function step3() {
        // Start session
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        error_log("=== STEP 3 LOADED ===");
        error_log("Session ID: " . session_id());
        error_log("Applicant ID: " . ($_SESSION['applicant_id'] ?? 'not set'));
        
        // Check login
        if (!$this->isApplicantLoggedIn()) {
            $_SESSION['flash_error'] = 'Please login to continue';
            header('Location: /applicant/login');
            exit;
        }
        
        $applicantId = $_SESSION['applicant_id'];
        
        // Get application
        $application = $this->applicationModel->getByApplicantId($applicantId);
        
        if (!$application) {
            $_SESSION['flash_error'] = 'Application not found';
            header('Location: /apply/step/1');
            exit;
        }
        
        error_log("Application found: ID=" . $application['id'] . ", Step=" . $application['application_step']);
        
        // Check if application is complete enough for payment
        if (empty($application['date_of_birth']) || empty($application['phone']) || empty($application['address'])) {
            $_SESSION['flash_error'] = 'Please complete your application form first';
            header('Location: /apply/step/2');
            exit;
        }
        
        // Check if already paid
        $hasPaid = $this->paymentModel->hasSuccessfulPayment($application['id']);
        if ($hasPaid) {
            error_log("Already paid, redirecting to step 4");
            header('Location: /apply/step/4');
            exit;
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
        $csrfToken = bin2hex(random_bytes(32));
        if (!isset($_SESSION['csrf_tokens'])) {
            $_SESSION['csrf_tokens'] = [];
        }
        $_SESSION['csrf_tokens'][$csrfToken] = time();
        $_SESSION['current_csrf_token'] = $csrfToken;
        
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
        
        // Prepare data for view
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
            'baseUrl' => defined('BASE_URL') ? BASE_URL : '/'
        ]);
        
        // IMPORTANT: Render the view directly - NO REDIRECT
        error_log("Rendering step3.php");
        $this->render('applications/step3');
    }

    /**
     * Initiate payment - Generate RRR (AJAX endpoint)
     */
    public function initiatePayment() {
        // Set header for JSON response
        header('Content-Type: application/json');
        
        // Start session
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        error_log("=== INITIATE PAYMENT CALLED ===");
        
        // Check login
        if (!isset($_SESSION['applicant_id'])) {
            error_log("ERROR: User not logged in");
            echo json_encode(['success' => false, 'message' => 'Please login first']);
            return;
        }
        
        // Get CSRF token from request
        $input = json_decode(file_get_contents('php://input'), true);
        $csrfToken = $input['csrf_token'] ?? $_POST['csrf_token'] ?? '';
        
        // Validate CSRF token
        if (empty($csrfToken) || !isset($_SESSION['csrf_tokens'][$csrfToken])) {
            error_log("CSRF validation failed");
            echo json_encode(['success' => false, 'message' => 'Invalid security token']);
            return;
        }
        
        // Check token expiration (1 hour)
        if (time() - $_SESSION['csrf_tokens'][$csrfToken] > 3600) {
            unset($_SESSION['csrf_tokens'][$csrfToken]);
            echo json_encode(['success' => false, 'message' => 'Security token expired']);
            return;
        }
        
        try {
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
            
            // Generate RRR
            $rrr = 'DEMO' . time() . rand(1000, 9999);
            $orderId = 'ORD' . time() . rand(100, 999);
            $reference = 'REF' . time() . rand(1000, 9999);
            
            // Create payment record
            $paymentData = [
                'application_id' => $application['id'],
                'applicant_id' => $applicantId,
                'reference' => $reference,
                'rrr' => $rrr,
                'order_id' => $orderId,
                'amount' => $fee,
                'payment_type' => 'application_fee',
                'status' => 'pending',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ];
            
            $paymentId = $this->paymentModel->insert($paymentData);
            
            if (!$paymentId) {
                echo json_encode(['success' => false, 'message' => 'Failed to create payment record']);
                return;
            }
            
            // Store in session
            $_SESSION['pending_payment'] = [
                'payment_id' => $paymentId,
                'rrr' => $rrr,
                'amount' => $fee
            ];
            
            // Remove used token
            unset($_SESSION['csrf_tokens'][$csrfToken]);
            
            echo json_encode([
                'success' => true,
                'message' => 'RRR generated successfully',
                'rrr' => $rrr,
                'reference' => $reference,
                'order_id' => $orderId,
                'amount' => $fee
            ]);
            
        } catch (Exception $e) {
            error_log("Initiate payment error: " . $e->getMessage());
            echo json_encode(['success' => false, 'message' => 'Server error occurred']);
        }
    }

    /**
     * Verify payment (AJAX endpoint)
     */
    public function verifyPayment() {
        header('Content-Type: application/json');
        
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        error_log("=== VERIFY PAYMENT CALLED ===");
        
        if (!isset($_SESSION['applicant_id'])) {
            echo json_encode(['success' => false, 'message' => 'Please login first']);
            return;
        }
        
        // Get RRR from request
        $input = json_decode(file_get_contents('php://input'), true);
        $rrr = $input['rrr'] ?? $_POST['rrr'] ?? '';
        $csrfToken = $input['csrf_token'] ?? $_POST['csrf_token'] ?? '';
        
        // Validate CSRF
        if (empty($csrfToken) || !isset($_SESSION['csrf_tokens'][$csrfToken])) {
            echo json_encode(['success' => false, 'message' => 'Invalid security token']);
            return;
        }
        
        if (empty($rrr)) {
            echo json_encode(['success' => false, 'message' => 'RRR is required']);
            return;
        }
        
        try {
            // Get payment by RRR
            $payment = $this->paymentModel->getByRRR($rrr);
            
            if (!$payment) {
                echo json_encode(['success' => false, 'message' => 'Payment record not found']);
                return;
            }
            
            // Verify ownership
            if ($payment['applicant_id'] != $_SESSION['applicant_id']) {
                echo json_encode(['success' => false, 'message' => 'Unauthorized access']);
                return;
            }
            
            // For demo, mark as success
            $updateResult = $this->paymentModel->markAsSuccess($payment['id'], [
                'transaction_id' => 'TXN' . time(),
                'payment_method' => 'remita',
                'payer_email' => $payment['payer_email'] ?? null,
                'payer_name' => $payment['payer_name'] ?? null
            ]);
            
            if ($updateResult) {
                // Update application step
                $this->applicationModel->updateApplication($payment['application_id'], [
                    'application_step' => 4
                ]);
                
                // Generate exam slip
                $this->generateExamSlip($payment['application_id']);
                
                // Clear pending payment
                unset($_SESSION['pending_payment']);
                unset($_SESSION['csrf_tokens'][$csrfToken]);
                
                echo json_encode([
                    'success' => true,
                    'message' => 'Payment verified successfully',
                    'redirect' => '/apply/step/4'
                ]);
            } else {
                echo json_encode([
                    'success' => false,
                    'message' => 'Failed to verify payment'
                ]);
            }
            
        } catch (Exception $e) {
            error_log("Verify payment error: " . $e->getMessage());
            echo json_encode(['success' => false, 'message' => 'Server error occurred']);
        }
    }

    /**
     * Check payment status (AJAX endpoint)
     */
    public function checkPaymentStatus() {
        header('Content-Type: application/json');
        
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
            
            echo json_encode([
                'success' => true,
                'status' => $payment['status'],
                'paid' => ($payment['status'] === 'success'),
                'payment_date' => $payment['payment_date'] ?? null
            ]);
            
        } catch (Exception $e) {
            error_log("Check status error: " . $e->getMessage());
            echo json_encode(['success' => false, 'message' => 'Server error']);
        }
    }

    /**
     * Show step 4: Exam Slip
     */
    public function step4() {
        // Start session
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        if (!$this->isApplicantLoggedIn()) {
            $_SESSION['flash_error'] = 'Please login to continue';
            header('Location: /applicant/login');
            return;
        }
        
        $applicantId = $_SESSION['applicant_id'];
        $application = $this->applicationModel->getByApplicantId($applicantId);
        
        if (!$application) {
            $_SESSION['flash_error'] = 'Application not found';
            header('Location: /apply/step/1');
            return;
        }
        
        // Check if payment is successful
        $hasPaid = $this->paymentModel->hasSuccessfulPayment($application['id']);
        
        if (!$hasPaid) {
            header('Location: /apply/step/3');
            return;
        }
        
        // Get exam slip
        require_once MODELS_PATH . '/application/ExamSlipModel.php';
        $examSlipModel = new ExamSlipModel();
        $examSlip = $examSlipModel->getByApplicationId($application['id']);
        
        if (!$examSlip) {
            $this->generateExamSlip($application['id']);
            $examSlip = $examSlipModel->getByApplicationId($application['id']);
        }
        
        $this->data = array_merge($this->data, [
            'pageTitle' => 'Examination Slip - Step 4',
            'application' => $application,
            'exam_slip' => $examSlip
        ]);
        
        $this->render('applications/step4');
    }

    // ============================================
    // JAMB VERIFICATION
    // ============================================

    /**
     * Show step 1: JAMB verification
     */
    public function step1() {
        // Start session
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        if (!$this->isApplicantLoggedIn()) {
            $_SESSION['flash_error'] = 'Please login to continue';
            header('Location: /applicant/login');
            exit;
        }
        
        $applicantId = $_SESSION['applicant_id'];
        $application = $this->applicationModel->getByApplicantId($applicantId);
        
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
            
            $this->data['jamb_verified'] = true;
            $this->data['jamb_data'] = $_SESSION['jamb_verification'];
            $this->data['application_step'] = $application['application_step'];
            
            if ($application['application_step'] == 2) {
                header('Location: /apply/step/2');
                exit;
            }
        }
        
        $terms = $this->termsModel->getForAcceptance();
        $settings = $this->settingsModel->getAllSettings();
        
        $this->data = array_merge($this->data, [
            'pageTitle' => 'Step 1: JAMB Verification',
            'terms' => $terms,
            'settings' => $settings,
            'csrf_token' => $this->csrfToken()
        ]);
        
        $this->render('applications/step1');
    }

    /**
     * Verify JAMB number (AJAX endpoint)
     */
    public function verifyJamb() {
        header('Content-Type: application/json');
        
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        if (!isset($_SESSION['applicant_id'])) {
            echo json_encode(['success' => false, 'message' => 'Please login first']);
            return;
        }
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['success' => false, 'message' => 'Invalid request method']);
            return;
        }
        
        if (!$this->validateCsrfToken()) {
            echo json_encode(['success' => false, 'message' => 'Security token expired']);
            return;
        }
        
        $jambNumber = trim($_POST['jamb_number'] ?? '');
        
        if (empty($jambNumber)) {
            echo json_encode(['success' => false, 'message' => 'JAMB number is required']);
            return;
        }
        
        // For demo, accept any JAMB number
        $applicantId = $_SESSION['applicant_id'];
        $application = $this->applicationModel->getByApplicantId($applicantId);
        
        if (!$application) {
            // Create application
            $applicationId = $this->applicationModel->createApplication($applicantId, [
                'jamb_number' => $jambNumber,
                'first_name' => 'Demo',
                'last_name' => 'User',
                'gender' => 'M',
                'state_of_origin' => 'FCT',
                'lga' => 'Abuja'
            ]);
        } else {
            // Update application
            $this->applicationModel->updateApplication($application['id'], [
                'jamb_number' => $jambNumber,
                'application_step' => 2
            ]);
        }
        
        $_SESSION['jamb_verification'] = [
            'jamb_number' => $jambNumber,
            'first_name' => 'Demo',
            'last_name' => 'User',
            'gender' => 'M',
            'state_of_origin' => 'FCT',
            'lga' => 'Abuja',
            'score' => 250
        ];
        
        echo json_encode([
            'success' => true,
            'data' => [
                'jamb_number' => $jambNumber,
                'name' => 'Demo User',
                'first_name' => 'Demo',
                'last_name' => 'User',
                'gender' => 'M',
                'state_of_origin' => 'FCT',
                'lga' => 'Abuja',
                'score' => 250
            ]
        ]);
        exit;
    }

    // ============================================
    // APPLICANT AUTHENTICATION
    // ============================================

    /**
     * Show applicant login page
     */
    public function login() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        if (isset($_SESSION['applicant_id'])) {
            header('Location: /apply/step/1');
            exit;
        }
        
        $this->data = array_merge($this->data, [
            'pageTitle' => 'Applicant Login - Application Portal',
            'csrf_token' => $this->csrfToken()
        ]);
        
        $this->render('applications/login');
    }
    
    /**
     * Process applicant login
     */
    public function processLogin() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /applicant/login');
            exit;
        }
        
        if (!$this->validateCsrfToken()) {
            $_SESSION['flash_error'] = 'Security token expired. Please refresh and try again.';
            header('Location: /applicant/login');
            exit;
        }
        
        $login = trim($_POST['login'] ?? '');
        $password = $_POST['password'] ?? '';
        
        if (empty($login) || empty($password)) {
            $_SESSION['flash_error'] = 'Please enter your login details and password.';
            header('Location: /applicant/login');
            exit;
        }
        
        // For demo, accept any credentials
        $_SESSION['applicant_id'] = 1;
        $_SESSION['applicant_email'] = $login;
        $_SESSION['applicant_name'] = 'Demo User';
        $_SESSION['applicant_login_time'] = time();
        
        header('Location: /apply/step/1');
        exit;
    }
    
    /**
     * Applicant logout
     */
    public function logout() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        unset($_SESSION['jamb_verification']);
        $_SESSION = array();
        
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $params["path"], $params["domain"],
                $params["secure"], $params["httponly"]
            );
        }
        
        session_destroy();
        
        $_SESSION['flash_success'] = 'You have been logged out successfully.';
        header('Location: /applicant/login');
        exit;
    }

    // ============================================
    // HELPER METHODS
    // ============================================
    
    /**
     * Upload file helper
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
        
        $uploadDir = PUBLIC_PATH . "/uploads/applications/{$applicantId}/{$type}";
        if (!is_dir($uploadDir)) {
            if (!mkdir($uploadDir, 0755, true)) {
                return ['success' => false, 'message' => 'Failed to create upload directory'];
            }
        }
        
        $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
        $filename = $type . '_' . time() . '_' . bin2hex(random_bytes(4)) . '.' . $extension;
        $filepath = $uploadDir . '/' . $filename;
        
        if (move_uploaded_file($file['tmp_name'], $filepath)) {
            $webPath = '/uploads/applications/' . $applicantId . '/' . $type . '/' . $filename;
            return ['success' => true, 'path' => $webPath];
        }
        
        return ['success' => false, 'message' => 'Failed to save file'];
    }

    /**
     * Generate exam slip
     */
    private function generateExamSlip($applicationId) {
        try {
            require_once MODELS_PATH . '/application/ExamSlipModel.php';
            $examSlipModel = new ExamSlipModel();
            
            $existing = $examSlipModel->getByApplicationId($applicationId);
            if ($existing) {
                return $existing;
            }
            
            $application = $this->applicationModel->find($applicationId);
            
            $slipNumber = 'SLIP-' . date('Y') . '-' . str_pad($applicationId, 5, '0', STR_PAD_LEFT);
            $examDate = $this->settingsModel->get('cbt_start_date', date('Y-m-d', strtotime('+7 days')));
            
            $slipData = [
                'application_id' => $applicationId,
                'applicant_id' => $application['applicant_id'],
                'slip_number' => $slipNumber,
                'exam_date' => $examDate,
                'exam_time' => '10:00:00',
                'exam_venue' => 'FCT College of Nursing Sciences, Gwagwalada (within UATH)',
                'reporting_time' => '08:00:00',
                'seat_number' => 'SEAT-' . rand(100, 999),
                'instructions' => "1. Arrive 1 hour before exam\n2. Bring this slip and valid ID\n3. No phones allowed",
                'created_at' => date('Y-m-d H:i:s')
            ];
            
            $slipId = $examSlipModel->insert($slipData);
            
            if ($slipId) {
                $this->applicationModel->updateApplication($applicationId, [
                    'exam_slip_generated' => 1,
                    'application_step' => 4
                ]);
            }
            
            return $slipId ? $examSlipModel->find($slipId) : null;
            
        } catch (Exception $e) {
            error_log("Generate exam slip error: " . $e->getMessage());
            return null;
        }
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
}