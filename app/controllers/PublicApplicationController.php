<?php
/**
 * Public Application Controller
 * 
 * Handles public-facing application processes
 * ENHANCED: Added application creation during JAMB verification, specific login error messages, password reset functionality
 * FIXED: Redirect from application form to step 2, proper JAMB data restoration, saveApplication field mapping and update method
 * FIXED: O'Level results handling in saveApplication method - prevents duplication on re-login
 * FIXED: Exam slip generation and step 4 display - enhanced security
 * 
 * @package FCT_CNS
 */

require_once __DIR__ . '/ApplicationBaseController.php';

class PublicApplicationController extends ApplicationBaseController {
    
    private $jambModel;
    private $termsModel;
    
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
        
        $this->jambModel = new JambCandidateModel();
        $this->termsModel = new TermsModel();
        
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
     * Process registration (Step 1 - New Flow) - FIXED
     * Redirects to email sent page, not verification with token
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
            
            // IMPORTANT: Redirect to email sent page, NOT to verify-email with token
            header('Location: /apply/verify-email');  // This shows "check your email" page
            exit;
            
        } catch (Exception $e) {
            error_log("Registration error: " . $e->getMessage());
            $_SESSION['flash_error'] = 'An error occurred. Please try again.';
            header('Location: /apply/register');
            exit;
        }
    }

    /**
     * Verify email with token or show email sent page - FIXED
     * Handles two scenarios:
     * 1. No token - show "check your email" page
     * 2. With token - verify the email
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
        
        // Debug log
        error_log("=== verifyEmail called ===");
        error_log("Token: " . $token);
        error_log("Email from URL: " . ($_GET['email'] ?? 'not set'));
        error_log("Email from session: " . ($_SESSION['registration_email'] ?? 'not set'));
        error_log("Final email: " . $email);
        
        // If no token, show the "check your email" page
        if (empty($token)) {
            // Show email sent page
            $this->data['email'] = $email;
            $this->data['pageTitle'] = 'Verify Your Email';
            $this->data['email_sent'] = true; // Add this flag
            
            // Don't clear session email yet - keep it for resend
            // But we'll use it in the view
            
            $this->render('applications/verify-email');
            return;
        }
        
        // Token provided - verify the email
        $applicant = $this->applicantModel->findByVerificationToken($token);
        
        if (!$applicant) {
            // Token not found or expired
            error_log("No applicant found with token: " . $token);
            $this->data['error'] = 'Invalid or expired verification link';
            $this->data['pageTitle'] = 'Verification Failed';
            $this->render('applications/verify-email');
            return;
        }
        
        error_log("Found applicant: ID=" . $applicant['id'] . ", Email=" . $applicant['email'] . ", Verified=" . $applicant['email_verified']);
        
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
            error_log("Successfully verified applicant ID: " . $applicant['id']);
            
            // Auto-login the applicant
            $_SESSION['applicant_id'] = $applicant['id'];
            $_SESSION['applicant_email'] = $applicant['email'];
            $_SESSION['applicant_name'] = ($applicant['first_name'] ?? '') . ' ' . ($applicant['last_name'] ?? '');
            $_SESSION['applicant_login_time'] = time();
            
            // Clear registration email from session
            unset($_SESSION['registration_email']);
            
            // Show success page
            $this->data['verified'] = true;
            $this->data['applicant_name'] = $applicant['first_name'] . ' ' . $applicant['last_name'];
            $this->data['applicant_email'] = $applicant['email'];
            $this->data['pageTitle'] = 'Email Verified Successfully';
        } else {
            // Failed to update
            error_log("Failed to update applicant ID: " . $applicant['id']);
            $this->data['error'] = 'Failed to verify email. Please try again.';
            $this->data['pageTitle'] = 'Verification Failed';
        }
        
        $this->render('applications/verify-email');
    }

    /**
     * Resend verification email (Step 1 - New Flow) - FIXED
     * Now properly handles email parameter and redirects with email in URL
     */
    public function resendVerification() {
        // Start session
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        // Get email from query string or session
        $email = $_GET['email'] ?? $_SESSION['registration_email'] ?? '';
        
        error_log("Resend verification called with email: " . $email);
        
        if (empty($email)) {
            $_SESSION['flash_error'] = 'Email address not found. Please register again.';
            header('Location: /apply/register');
            exit;
        }
        
        // Find applicant by email
        $applicant = $this->applicantModel->findByEmail($email);
        
        if ($applicant) {
            if ($applicant['email_verified'] == 1) {
                // Already verified
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
            
            // Redirect back to verification page WITH email parameter
            header('Location: /apply/verify-email?email=' . urlencode($email));
            exit;
        } else {
            // Applicant not found with this email
            $_SESSION['flash_error'] = 'Email address not found in our records. Please register again.';
            header('Location: /apply/register');
            exit;
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
     * FIXED: Redirect to step 2 instead of rendering form
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
        
        // CRITICAL: Check if JAMB is verified in database, even if not in session
        if (empty($application['jamb_number'])) {
            $_SESSION['flash_error'] = 'Please verify your JAMB number first';
            header('Location: /apply/step/1');
            exit;
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
                'score' => $application['utme_score']
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
        
        // Get passport from document model if exists
        require_once MODELS_PATH . '/application/ApplicationDocumentModel.php';
        $docModel = new ApplicationDocumentModel();
        $passport = $docModel->getPassport($application['id']);
        
        // Pass data to view including file paths
        $this->data = array_merge($this->data, [
            'pageTitle' => 'Application Form - Step 2',
            'application' => $application,
            'applicant' => $applicant,
            'jamb_data' => $_SESSION['jamb_verification'] ?? null,
            'olevel_results' => $olevel_results,
            'passport' => $passport,
            'states' => $this->getStates(),
            'programs' => $this->getPrograms(),
            'csrf_token' => $this->csrfToken(),
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
        
        // FIXED: Redirect to step 2 instead of rendering form
        header('Location: /apply/step/2');
        exit;
    }

    /**
     * Save application form - FIXED with proper O'Level data handling
     * Now properly saves O'Level subjects data to olevel_results field and prevents duplication
     */
    public function saveApplication() {
        // Set header to JSON first thing
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
        if (!isset($_POST['csrf_token']) || !$this->validateCsrfToken()) {
            echo json_encode(['success' => false, 'message' => 'Invalid security token']);
            return;
        }
        
        try {
            $applicantId = $_SESSION['applicant_id'];
            
            // Get JAMB data from hidden fields
            $jambNumber = $_POST['jamb_number'] ?? '';
            $firstName = $_POST['first_name'] ?? '';
            $lastName = $_POST['last_name'] ?? '';
            $otherNames = $_POST['other_names'] ?? '';
            $gender = $_POST['gender'] ?? '';
            $stateOfOrigin = $_POST['state_of_origin'] ?? '';
            $lga = $_POST['lga'] ?? '';
            $utmeScore = $_POST['utme_score'] ?? '';
            
            // Get editable fields
            $dateOfBirth = $_POST['date_of_birth'] ?? '';
            $phone = $_POST['phone'] ?? '';
            $address = $_POST['address'] ?? '';
            $nationality = $_POST['nationality'] ?? 'Nigerian';
            $programChoice = $_POST['program_choice'] ?? $_POST['program_choice_1'] ?? '';
            $email = $_POST['email'] ?? '';
            
            // DEBUG: Log all POST data
            error_log("=== SAVE APPLICATION DEBUG ===");
            error_log("POST keys: " . implode(', ', array_keys($_POST)));
            
            // Validate required fields
            $missingFields = [];
            if (empty($dateOfBirth)) $missingFields[] = 'date_of_birth';
            if (empty($phone)) $missingFields[] = 'phone';
            if (empty($address)) $missingFields[] = 'address';
            if (empty($programChoice)) $missingFields[] = 'program_choice';
            
            if (!empty($missingFields)) {
                error_log("Missing fields: " . implode(', ', $missingFields));
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
            
            // FIXED: Handle O'Level subject data from the form - PREVENTS DUPLICATION
            if (isset($_POST['olevel']) && is_array($_POST['olevel'])) {
                error_log("O'Level data received: " . count($_POST['olevel']) . " entries");
                
                // Format the O'Level data for storage
                $formattedResults = [];
                
                foreach ($_POST['olevel'] as $index => $result) {
                    // Skip if required fields are missing
                    if (empty($result['exam_type']) || empty($result['exam_year'])) {
                        continue;
                    }
                    
                    // Build the result entry
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
                    // Save to olevel_results field in JSON format (overwrites existing)
                    $updateData['olevel_results'] = json_encode($formattedResults);
                    error_log("Formatted O'Level data being saved: " . count($formattedResults) . " entries");
                    
                    // Also save to the dedicated olevel_results table - BUT FIRST DELETE EXISTING TO PREVENT DUPLICATION
                    try {
                        require_once MODELS_PATH . '/application/OlevelResultModel.php';
                        $olevelModel = new OlevelResultModel();
                        
                        // IMPORTANT: Delete existing records first to prevent duplication
                        $olevelModel->deleteByApplicationId($application['id']);
                        error_log("Deleted existing O'Level records for application ID: " . $application['id']);
                        
                        // Save each sitting to the database (fresh insert)
                        foreach ($formattedResults as $result) {
                            $result['application_id'] = $application['id'];
                            $olevelModel->insert($result);
                        }
                        error_log("Saved " . count($formattedResults) . " new O'Level results to olevel_results table");
                    } catch (Exception $e) {
                        error_log("Error saving to olevel_results table: " . $e->getMessage());
                        // Don't fail the whole request, just log the error
                    }
                }
            } else {
                error_log("No O'Level data found in POST");
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
                error_log("Failed to update application ID: " . $application['id']);
                echo json_encode(['success' => false, 'message' => 'Failed to update application']);
                return;
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
            
            // Prepare success response
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
            error_log("Stack trace: " . $e->getTraceAsString());
            echo json_encode([
                'success' => false,
                'message' => 'Server error: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Remove document - FIXED with correct update method handling
     */
    public function removeDocument() {
        // Set header to JSON first thing
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
    // STEP 3: PAYMENT
    // ============================================

    /**
     * Show payment page (Step 3 - New Flow)
     * FIXED: Generate fresh CSRF token and store in session, restore JAMB data if needed
     */
    public function showPayment() {
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
        $application = $this->applicationModel->getByApplicantId($applicantId);
        
        if (!$application) {
            $_SESSION['flash_error'] = 'Application not found';
            header('Location: /apply/form');
            exit;
        }
        
        // RESTORE JAMB DATA TO SESSION IF MISSING BUT APPLICATION HAS IT
        if (!isset($_SESSION['jamb_verification']) && $application['jamb_number']) {
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
        
        // Check if already paid
        $hasPaid = $this->paymentModel->hasSuccessfulPayment($application['id']);
        
        if ($hasPaid) {
            header('Location: /apply/step/4');
            exit;
        }
        
        // Generate a fresh CSRF token and store it in session
        $csrfToken = bin2hex(random_bytes(32));
        
        // Initialize csrf_tokens array if it doesn't exist
        if (!isset($_SESSION['csrf_tokens'])) {
            $_SESSION['csrf_tokens'] = [];
        }
        
        // Store the token with timestamp
        $_SESSION['csrf_tokens'][$csrfToken] = time();
        $_SESSION['current_csrf_token'] = $csrfToken; // Store current token for reference
        
        $fee = $this->settingsModel->getApplicationFee();
        $currency = $this->settingsModel->getCurrency();
        
        $this->data = array_merge($this->data, [
            'pageTitle' => 'Payment - Step 3',
            'application' => $application,
            'fee' => $fee,
            'currency' => $currency,
            'formatted_fee' => $this->settingsModel->getFormattedFee(),
            'csrf_token' => $csrfToken // Use the fresh token
        ]);
        
        $this->render('applications/payment');
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
        
        // Check if user is logged in
        if (!isset($_SESSION['applicant_id'])) {
            echo json_encode(['success' => false, 'message' => 'Please login first']);
            return;
        }
        
        // Get CSRF token from request
        $input = json_decode(file_get_contents('php://input'), true);
        $csrfToken = $input['csrf_token'] ?? $_POST['csrf_token'] ?? '';
        
        // Validate CSRF token
        if (empty($csrfToken)) {
            error_log("CSRF validation failed: Token is empty");
            echo json_encode(['success' => false, 'message' => 'Security token missing']);
            return;
        }
        
        // Check token in session - FIXED: Check both possible locations
        $validToken = false;
        
        // Check in csrf_tokens array (your current method)
        if (isset($_SESSION['csrf_tokens']) && isset($_SESSION['csrf_tokens'][$csrfToken])) {
            // Check token expiration (1 hour)
            if (time() - $_SESSION['csrf_tokens'][$csrfToken] <= 3600) {
                $validToken = true;
                // Remove used token
                unset($_SESSION['csrf_tokens'][$csrfToken]);
            } else {
                unset($_SESSION['csrf_tokens'][$csrfToken]);
                error_log("CSRF validation failed: Token expired");
                echo json_encode(['success' => false, 'message' => 'Security token expired']);
                return;
            }
        }
        // Check in simple csrf_token (from ApplicationBaseController)
        elseif (isset($_SESSION['csrf_token']) && $_SESSION['csrf_token'] === $csrfToken) {
            $validToken = true;
            // Generate new token for next request
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        
        if (!$validToken) {
            error_log("CSRF validation failed: Token not found in session");
            echo json_encode(['success' => false, 'message' => 'Invalid security token']);
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
            echo json_encode([
                'success' => false,
                'message' => 'Server error occurred'
            ]);
        }
    }

    /**
     * Verify payment (AJAX endpoint) - FIXED: Requires actual payment verification
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
            
            // CRITICAL: Check if payment is already successful
            if ($payment['status'] === 'success') {
                // Payment already verified - check if exam slip exists
                $examSlip = $this->generateExamSlip($payment['application_id']);
                
                echo json_encode([
                    'success' => true,
                    'message' => 'Payment already verified',
                    'redirect' => '/apply/step/4'
                ]);
                return;
            }
            
            // For DEMO/TESTING ONLY - Uncomment this section if you want to simulate payment
            /*
            // SIMULATE PAYMENT VERIFICATION (FOR TESTING ONLY)
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
                $examSlip = $this->generateExamSlip($payment['application_id']);
                
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
            return;
            */
            
            // PRODUCTION: Check with Remita API if payment is actually completed
            // This is where you would integrate with Remita's verification API
            $remitaVerified = $this->verifyWithRemita($rrr);
            
            if ($remitaVerified) {
                // Payment confirmed by Remita - mark as success
                $updateResult = $this->paymentModel->markAsSuccess($payment['id'], [
                    'transaction_id' => $remitaVerified['transactionId'] ?? 'TXN' . time(),
                    'payment_method' => 'remita',
                    'payer_email' => $remitaVerified['payerEmail'] ?? null,
                    'payer_name' => $remitaVerified['payerName'] ?? null
                ]);
                
                if ($updateResult) {
                    // Update application step
                    $this->applicationModel->updateApplication($payment['application_id'], [
                        'application_step' => 4
                    ]);
                    
                    // Generate exam slip
                    $examSlip = $this->generateExamSlip($payment['application_id']);
                    
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
            } else {
                // Payment not completed on Remita
                echo json_encode([
                    'success' => false,
                    'message' => 'Payment not completed. Please complete your payment on Remita.',
                    'pending' => true
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

    /**
     * Verify payment with Remita API - Add this helper method
     */
    private function verifyWithRemita($rrr) {
        // TODO: Implement actual Remita API verification here
        // This is a placeholder that always returns false for production
        // In production, you would call Remita's API to check payment status
        
        // For now, always return false to force actual payment
        return false;
        
        /*
        // Example Remita API integration (commented out)
        try {
            $remitaModel = new RemitaModel();
            $result = $remitaModel->verifyPayment($rrr);
            
            if ($result['status'] === 'success' && $result['payment_data']['paymentStatus'] === 'PAID') {
                return $result['payment_data'];
            }
            
            return false;
        } catch (Exception $e) {
            error_log("Remita verification error: " . $e->getMessage());
            return false;
        }
        */
    }

    // ============================================
    // STEP 4: EXAM SLIP - ENHANCED SECURITY
    // ============================================

    /**
     * Show exam slip page (Step 4 - New Flow)
     */
    public function showExamSlip() {
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
        $application = $this->applicationModel->getByApplicantId($applicantId);
        
        if (!$application) {
            $_SESSION['flash_error'] = 'Application not found';
            header('Location: /apply/form');
            exit;
        }
        
        // Check if payment is successful
        $hasPaid = $this->paymentModel->hasSuccessfulPayment($application['id']);
        
        if (!$hasPaid) {
            header('Location: /apply/step/3');
            exit;
        }
        
        // Get exam slip
        require_once MODELS_PATH . '/application/ExamSlipModel.php';
        $examSlipModel = new ExamSlipModel();
        $examSlip = $examSlipModel->getByApplicationId($application['id']);
        
        if (!$examSlip) {
            // Generate exam slip if not exists
            error_log("Exam slip not found for application: " . $application['id'] . ". Attempting to generate...");
            $examSlip = $this->generateExamSlip($application['id']);
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
            'exam_details' => [
                'date' => $this->settingsModel->get('cbt_start_date', 'To be announced'),
                'venue' => 'FCT College of Nursing Sciences, Gwagwalada (within UATH)',
                'reporting_time' => '8:00 AM'
            ]
        ]);
        
        $this->render('applications/step4');
    }

    /**
     * Download exam slip
     */
    public function downloadExamSlip() {
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
        $application = $this->applicationModel->getByApplicantId($applicantId);
        
        if (!$application) {
            $_SESSION['flash_error'] = 'Application not found';
            header('Location: /apply/form');
            exit;
        }
        
        // Get exam slip
        require_once MODELS_PATH . '/application/ExamSlipModel.php';
        $examSlipModel = new ExamSlipModel();
        $examSlip = $examSlipModel->getByApplicationId($application['id']);
        
        if (!$examSlip) {
            $_SESSION['flash_error'] = 'Exam slip not found';
            header('Location: /apply/step/4');
            exit;
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
     * Generate exam slip (helper method) - FIXED: Removed 'instructions' field
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
        
        // Create exam slip - REMOVED 'instructions' field
        $examSlipId = $examSlipModel->insert([
            'application_id' => $applicationId,
            'applicant_id' => $application['applicant_id'] ?? null,
            'slip_number' => $slipNumber,
            'exam_date' => $this->settingsModel->get('cbt_start_date', date('Y-m-d', strtotime('+7 days'))),
            'exam_time' => '10:00:00',
            'reporting_time' => '08:00:00',
            'exam_venue' => 'FCT College of Nursing Sciences, Gwagwalada (within UATH)',
            'seat_number' => 'SEAT-' . rand(100, 999),
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
    // APPLICANT AUTHENTICATION
    // ============================================

    /**
     * Show applicant login page
     */
    public function login() {
        // Start session
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        // Clear any existing login errors from previous attempts
        unset($_SESSION['login_error']);
        unset($_SESSION['password_error']);
        
        // If already logged in, redirect
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
     * Process applicant login - ENHANCED with specific error messages
     * FIXED: Updated redirect URLs to use step2 instead of form
     */
    public function processLogin() {
        // Start session
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        // Clear any existing form errors
        unset($_SESSION['login_error']);
        unset($_SESSION['password_error']);
        unset($_SESSION['login_value']);
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /applicant/login');
            exit;
        }
        
        // Validate CSRF
        if (!$this->validateCsrfToken()) {
            $_SESSION['flash_error'] = 'Security token expired. Please refresh the page and try again.';
            header('Location: /applicant/login');
            exit;
        }
        
        $login = trim($_POST['login'] ?? '');
        $password = $_POST['password'] ?? '';
        
        // Store login value to repopulate form on error
        $_SESSION['login_value'] = $login;
        
        // Validate input
        if (empty($login)) {
            $_SESSION['login_error'] = 'Please enter your email, phone, or JAMB number.';
            $_SESSION['flash_error'] = 'Please enter your login details.';
            header('Location: /applicant/login');
            exit;
        }
        
        if (empty($password)) {
            $_SESSION['password_error'] = 'Please enter your password.';
            $_SESSION['flash_error'] = 'Please enter your password.';
            header('Location: /applicant/login');
            exit;
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
        
        // If no applicant found - show specific error based on login type
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
            header('Location: /applicant/login');
            exit;
        }
        
        // Check if email is verified
        if (!isset($applicant['email_verified']) || $applicant['email_verified'] != 1) {
            error_log("Login failed: Email not verified for applicant ID: " . $applicant['id']);
            $_SESSION['flash_error'] = 'Please verify your email before logging in. Check your inbox for the verification link.';
            $_SESSION['verification_email'] = $applicant['email'];
            header('Location: /applicant/login');
            exit;
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
            header('Location: /applicant/login');
            exit;
        }
        
        // Check if account is active
        if (isset($applicant['status']) && $applicant['status'] !== 'active') {
            error_log("Login failed: Account inactive for applicant ID: " . $applicant['id'] . ", Status: " . ($applicant['status'] ?? 'unknown'));
            $_SESSION['flash_error'] = 'Your account is inactive. Please contact support at info@fctcns.edu.ng';
            header('Location: /applicant/login');
            exit;
        }
        
        // Clear login value from session on successful login
        unset($_SESSION['login_value']);
        unset($_SESSION['login_error']);
        unset($_SESSION['password_error']);
        
        // Set session
        $_SESSION['applicant_id'] = $applicant['id'];
        $_SESSION['applicant_email'] = $applicant['email'];
        $_SESSION['applicant_name'] = trim(($applicant['first_name'] ?? '') . ' ' . ($applicant['last_name'] ?? ''));
        $_SESSION['applicant_login_time'] = time();
        
        // Log successful login
        error_log("Login successful for applicant ID: " . $applicant['id'] . ", Email: " . $applicant['email']);
        
        // Get the application for this applicant
        $application = $this->applicationModel->getByApplicantId($applicant['id']);
        
        // Determine redirect URL based on application status
        $redirectUrl = '/apply/step/1'; // Default to JAMB verification
        
        if ($application) {
            // Restore JAMB data to session if not present
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
            
            // Determine step based on application progress - FIXED: Use step2 instead of form
            if (empty($application['jamb_number'])) {
                $redirectUrl = '/apply/step/1';
            } elseif (empty($application['date_of_birth']) || 
                      empty($application['phone']) || 
                      empty($application['address']) || 
                      empty($application['program_choice_1'])) {
                $redirectUrl = '/apply/step/2'; // Changed from /apply/form to /apply/step/2
            } elseif ($application['application_step'] >= 3) {
                // Check if payment is complete
                $hasPaid = $this->paymentModel->hasSuccessfulPayment($application['id']);
                if ($hasPaid) {
                    $redirectUrl = '/apply/step/4';
                } else {
                    $redirectUrl = '/apply/step/3';
                }
            } else {
                $redirectUrl = '/apply/step/2'; // Changed from /apply/form to /apply/step/2
            }
        }
        
        // Set success flash message
        $_SESSION['flash_success'] = 'Login successful! Welcome back, ' . $_SESSION['applicant_name'] . '.';
        
        header('Location: ' . $redirectUrl);
        exit;
    }
    
    /**
     * Applicant logout
     */
    public function logout() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        // Clear JAMB verification if exists
        unset($_SESSION['jamb_verification']);
        
        // Clear session
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
        header('Location: /applicant/login');
        exit;
    }
    
    /**
     * Show forgot password page
     */
    public function forgotPassword() {
        // Start session
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
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
        // Start session
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        // Clear any existing email value
        unset($_SESSION['email_value']);
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /applicant/forgot-password');
            exit;
        }
        
        // Validate CSRF
        if (!$this->validateCsrfToken()) {
            $_SESSION['flash_error'] = 'Security token expired. Please try again.';
            header('Location: /applicant/forgot-password');
            exit;
        }
        
        $email = trim($_POST['email'] ?? '');
        
        // Store email to repopulate form on error
        $_SESSION['email_value'] = $email;
        
        // Validate email
        if (empty($email)) {
            $_SESSION['flash_error'] = 'Please enter your email address.';
            header('Location: /applicant/forgot-password');
            exit;
        }
        
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $_SESSION['flash_error'] = 'Please enter a valid email address.';
            header('Location: /applicant/forgot-password');
            exit;
        }
        
        // Find applicant by email
        $applicant = $this->applicantModel->findByEmail($email);
        
        if (!$applicant) {
            // For security, don't reveal that email doesn't exist
            error_log("Password reset requested for non-existent email: " . $email);
            $_SESSION['flash_success'] = 'If your email is registered, you will receive a password reset link shortly.';
            unset($_SESSION['email_value']);
            header('Location: /applicant/forgot-password');
            exit;
        }
        
        try {
            // Generate reset token
            $resetToken = bin2hex(random_bytes(32));
            $resetExpiry = date('Y-m-d H:i:s', strtotime('+1 hour'));
            
            // Save reset token to database (you'll need to add this column to applicants table)
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
        
        header('Location: /applicant/forgot-password');
        exit;
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
        // Start session
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        $token = $_GET['token'] ?? '';
        
        if (empty($token)) {
            $_SESSION['flash_error'] = 'Invalid password reset link.';
            header('Location: /applicant/forgot-password');
            exit;
        }
        
        // Verify token (you'll need to implement this in your model)
        $applicant = $this->applicantModel->findByResetToken($token);
        
        if (!$applicant) {
            $_SESSION['flash_error'] = 'Invalid or expired reset link. Please request a new one.';
            header('Location: /applicant/forgot-password');
            exit;
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
        // Start session
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /applicant/forgot-password');
            exit;
        }
        
        // Validate CSRF
        if (!$this->validateCsrfToken()) {
            $_SESSION['flash_error'] = 'Security token expired. Please try again.';
            header('Location: /applicant/forgot-password');
            exit;
        }
        
        $token = $_POST['token'] ?? '';
        $password = $_POST['password'] ?? '';
        $confirm = $_POST['confirm_password'] ?? '';
        
        if (empty($token)) {
            $_SESSION['flash_error'] = 'Invalid reset token.';
            header('Location: /applicant/forgot-password');
            exit;
        }
        
        // Validate password
        if (empty($password)) {
            $_SESSION['flash_error'] = 'Please enter a new password.';
            header('Location: /applicant/reset-password?token=' . urlencode($token));
            exit;
        }
        
        if (strlen($password) < 8) {
            $_SESSION['flash_error'] = 'Password must be at least 8 characters long.';
            header('Location: /applicant/reset-password?token=' . urlencode($token));
            exit;
        }
        
        if ($password !== $confirm) {
            $_SESSION['flash_error'] = 'Passwords do not match.';
            header('Location: /applicant/reset-password?token=' . urlencode($token));
            exit;
        }
        
        // Verify token
        $applicant = $this->applicantModel->findByResetToken($token);
        
        if (!$applicant) {
            $_SESSION['flash_error'] = 'Invalid or expired reset link. Please request a new one.';
            header('Location: /applicant/forgot-password');
            exit;
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
            header('Location: /applicant/login');
            
        } catch (Exception $e) {
            error_log("Password reset error: " . $e->getMessage());
            $_SESSION['flash_error'] = 'An error occurred. Please try again.';
            header('Location: /applicant/reset-password?token=' . urlencode($token));
        }
        
        exit;
    }

    // ============================================
    // LEGACY METHODS (for backward compatibility)
    // ============================================

    /**
     * Show step 1: JAMB verification - UPDATED to check existing application
     */
    public function step1() {
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
        
        // Check if application exists and has JAMB already
        $application = $this->applicationModel->getByApplicantId($applicantId);
        
        if ($application && !empty($application['jamb_number'])) {
            // JAMB already verified, restore to session and show form
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
            
            error_log("Restored JAMB data to session from application in step1 for applicant: " . $applicantId);
            
            // Pass to view that JAMB is already verified
            $this->data['jamb_verified'] = true;
            $this->data['jamb_data'] = $_SESSION['jamb_verification'];
            $this->data['application_step'] = $application['application_step'];
            
            // If application step is 2, redirect to form
            if ($application['application_step'] == 2) {
                error_log("Application step 2 detected, redirecting to step2");
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
     * Show step 2: Application form (Legacy Flow) - FIXED
     * This method now properly restores JAMB data from database and renders the form
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
        
        // Check if JAMB has been verified in session
        if (!isset($_SESSION['jamb_verification']) || !$_SESSION['jamb_verification']) {
            // Try to restore from database
            $application = $this->applicationModel->getByApplicantId($applicantId);
            
            if ($application && !empty($application['jamb_number'])) {
                // Restore JAMB data to session
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
                
                error_log("Restored JAMB data to session from database in step2 for applicant: " . $applicantId);
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
        
        // Get O'Level results from database if they exist
        require_once MODELS_PATH . '/application/OlevelResultModel.php';
        $olevelModel = new OlevelResultModel();
        $olevel_results = $olevelModel->getByApplicationId($application['id']);
        
        // Get passport if exists
        require_once MODELS_PATH . '/application/ApplicationDocumentModel.php';
        $docModel = new ApplicationDocumentModel();
        $passport = $docModel->getPassport($application['id']);
        
        // Get applicant details
        $applicant = $this->applicantModel->find($applicantId);
        
        // Parse O'Level results from JSON if exists
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
        
        // Render the step2 view
        $this->render('applications/step2');
    }
    
    /**
     * Show step 3: Payment (Legacy Flow) - FIXED - RENDERS CORRECT VIEW
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
        
        // IMPORTANT: Render step3.php, NOT payment.php
        error_log("Rendering applications/step3 for applicant: " . $applicantId);
        $this->render('applications/step3');
    }

    /**
     * Show step 4: Exam Slip (Legacy Flow) - ENHANCED SECURITY
     * Multiple layers of verification to prevent unauthorized access
     */
    public function step4() {
        // Start session
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        error_log("=== STEP 4 LOADED ===");
        
        // Layer 1: Check login
        if (!$this->isApplicantLoggedIn()) {
            $_SESSION['flash_error'] = 'Please login to continue';
            header('Location: /applicant/login');
            exit;
        }
        
        $applicantId = $_SESSION['applicant_id'];
        
        // Layer 2: Get and validate application
        $application = $this->applicationModel->getByApplicantId($applicantId);
        
        if (!$application) {
            $_SESSION['flash_error'] = 'Application not found';
            header('Location: /apply/step/1');
            exit;
        }
        
        // Layer 3: VERIFY PAYMENT STATUS IN DATABASE (not session)
        // This is critical - always check the database, not session
        $hasPaid = $this->paymentModel->hasSuccessfulPayment($application['id']);
        
        if (!$hasPaid) {
            error_log("SECURITY: Unauthorized attempt to access step 4 - No payment found for application: " . $application['id']);
            $_SESSION['flash_error'] = 'Payment required. Please complete your payment first.';
            header('Location: /apply/step/3');
            exit;
        }
        
        // Layer 4: Double-check payment ownership and status
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
            header('Location: /apply/step/3');
            exit;
        }
        
        // Layer 5: Get exam slip - only proceed if all checks pass
        require_once MODELS_PATH . '/application/ExamSlipModel.php';
        $examSlipModel = new ExamSlipModel();
        $examSlip = $examSlipModel->getByApplicationId($application['id']);
        
        // Generate exam slip if it doesn't exist (first time only)
        if (!$examSlip) {
            error_log("Generating exam slip for verified payment - Application: " . $application['id']);
            $examSlip = $this->generateExamSlip($application['id']);
            
            if (!$examSlip) {
                error_log("CRITICAL: Failed to generate exam slip despite valid payment");
                $_SESSION['flash_error'] = 'Error generating exam slip. Please contact support.';
                header('Location: /apply/step/3');
                exit;
            }
        }
        
        // Get applicant for display
        $applicant = $this->applicantModel->find($applicantId);
        
        $this->data = array_merge($this->data, [
            'pageTitle' => 'Examination Slip - Step 4',
            'application' => $application,
            'applicant' => $applicant,
            'exam_slip' => $examSlip
        ]);
        
        error_log("Granting access to step 4 for verified applicant: " . $applicantId);
        $this->render('applications/step4');
    }

    // ============================================
    // JAMB VERIFICATION METHODS - FIXED
    // ============================================

    /**
     * Verify JAMB number (AJAX endpoint) - FIXED to create application record
     */
    public function verifyJamb() {
        // Set header for JSON response
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
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['success' => false, 'message' => 'Invalid request method']);
            return;
        }
        
        // Validate CSRF
        if (!$this->validateCsrfToken()) {
            echo json_encode(['success' => false, 'message' => 'Security token expired']);
            return;
        }
        
        $jambNumber = trim($_POST['jamb_number'] ?? '');
        
        // Validate JAMB number
        if (empty($jambNumber) || !preg_match('/^[0-9A-Z]{10,14}$/', strtoupper($jambNumber))) {
            echo json_encode(['success' => false, 'message' => 'Invalid JAMB number format']);
            return;
        }
        
        // Convert to uppercase for consistency
        $jambNumber = strtoupper($jambNumber);
        
        // Find JAMB candidate
        $jambCandidate = $this->jambModel->findByJambNumber($jambNumber);
        
        if (!$jambCandidate) {
            echo json_encode(['success' => false, 'message' => 'JAMB number not found in our records']);
            return;
        }
        
        // Check if already used
        if ($jambCandidate['is_used']) {
            echo json_encode(['success' => false, 'message' => 'This JAMB number has already been used']);
            return;
        }
        
        // Check score requirement
        $minScore = $this->settingsModel->get('min_utme_score', 170);
        if ($jambCandidate['aggregate_score'] < $minScore) {
            echo json_encode([
                'success' => false, 
                'message' => "Your score of {$jambCandidate['aggregate_score']} is below the minimum requirement of {$minScore}"
            ]);
            return;
        }
        
        // Get applicant ID from session
        $applicantId = $_SESSION['applicant_id'];
        
        // Check if application already exists for this applicant
        $existingApplication = $this->applicationModel->getByApplicantId($applicantId);
        
        if (!$existingApplication) {
            // Create new application record
            try {
                $applicationData = [
                    'applicant_id' => $applicantId,
                    'jamb_number' => $jambCandidate['jamb_number'],
                    'jamb_candidate_id' => $jambCandidate['id'],
                    'first_name' => $jambCandidate['first_name'],
                    'last_name' => $jambCandidate['last_name'],
                    'other_names' => $jambCandidate['other_names'],
                    'gender' => $jambCandidate['gender'],
                    'state_of_origin' => $jambCandidate['state_of_origin'],
                    'lga' => $jambCandidate['lga'],
                    'utme_score' => $jambCandidate['aggregate_score'],
                    'program' => 'ND Nursing',
                    'program_choice_1' => 'ND Nursing',
                    'application_step' => 2,
                    'status' => 'pending'
                ];
                
                $applicationId = $this->applicationModel->createApplication($applicantId, $applicationData);
                
                if (!$applicationId) {
                    throw new Exception("Failed to create application record");
                }
                
                error_log("Created new application ID: " . $applicationId . " for applicant: " . $applicantId);
                
            } catch (Exception $e) {
                error_log("Error creating application: " . $e->getMessage());
                echo json_encode(['success' => false, 'message' => 'Failed to create application record. Please try again.']);
                return;
            }
        } else {
            // Update existing application with JAMB data
            try {
                $updateData = [
                    'jamb_number' => $jambCandidate['jamb_number'],
                    'jamb_candidate_id' => $jambCandidate['id'],
                    'first_name' => $jambCandidate['first_name'],
                    'last_name' => $jambCandidate['last_name'],
                    'other_names' => $jambCandidate['other_names'],
                    'gender' => $jambCandidate['gender'],
                    'state_of_origin' => $jambCandidate['state_of_origin'],
                    'lga' => $jambCandidate['lga'],
                    'utme_score' => $jambCandidate['aggregate_score'],
                    'application_step' => 2,
                    'updated_at' => date('Y-m-d H:i:s')
                ];
                
                // Use updateApplication method for consistency
                $updated = $this->applicationModel->updateApplication($existingApplication['id'], $updateData);
                
                if (!$updated) {
                    throw new Exception("Failed to update application record");
                }
                
                error_log("Updated existing application ID: " . $existingApplication['id'] . " with JAMB data");
                
            } catch (Exception $e) {
                error_log("Error updating application: " . $e->getMessage());
                echo json_encode(['success' => false, 'message' => 'Failed to update application record. Please try again.']);
                return;
            }
        }
        
        // Store in session
        $_SESSION['jamb_verification'] = [
            'id' => $jambCandidate['id'],
            'jamb_number' => $jambCandidate['jamb_number'],
            'first_name' => $jambCandidate['first_name'],
            'last_name' => $jambCandidate['last_name'],
            'other_names' => $jambCandidate['other_names'],
            'gender' => $jambCandidate['gender'],
            'state_of_origin' => $jambCandidate['state_of_origin'],
            'lga' => $jambCandidate['lga'],
            'score' => $jambCandidate['aggregate_score']
        ];
        
        // Mark JAMB candidate as used
        $this->jambModel->markAsUsed($jambCandidate['id'], $applicantId);
        
        // Return complete data
        echo json_encode([
            'success' => true,
            'data' => [
                'jamb_number' => $jambCandidate['jamb_number'],
                'name' => $jambCandidate['first_name'] . ' ' . $jambCandidate['last_name'],
                'first_name' => $jambCandidate['first_name'],
                'last_name' => $jambCandidate['last_name'],
                'other_names' => $jambCandidate['other_names'],
                'gender' => $jambCandidate['gender'],
                'state_of_origin' => $jambCandidate['state_of_origin'],
                'lga' => $jambCandidate['lga'],
                'score' => $jambCandidate['aggregate_score']
            ]
        ]);
        exit;
    }

    // ============================================
    // HELPER METHODS
    // ============================================
    
    /**
     * Check if applicant is logged in
     */
    protected function isApplicantLoggedIn() {
        return isset($_SESSION['applicant_id']) && !empty($_SESSION['applicant_id']);
    }
    
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
}