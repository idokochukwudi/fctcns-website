<?php
/**
 * Public Application Controller
 * 
 * Handles public-facing application processes
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
     * Process registration (Step 1 - New Flow)
     */
    public function processRegistration() {
        // Start session if not already started
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
        
        if (empty($phone)) {
            $errors[] = 'Phone number is required';
        } elseif (!preg_match('/^[0-9]{11}$/', $phone)) {
            $errors[] = 'Phone number must be 11 digits';
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
            // Begin transaction
            $this->applicantModel->beginTransaction();
            
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
            
            // Commit transaction
            $this->applicantModel->commit();
            
            // Send verification email
            $this->sendVerificationEmail($email, $verificationToken);
            
            // Store email for display
            $_SESSION['registration_email'] = $email;
            
            // Redirect to verification page
            header('Location: /apply/verify-email');
            exit;
            
        } catch (Exception $e) {
            $this->applicantModel->rollback();
            error_log("Registration error: " . $e->getMessage());
            $_SESSION['flash_error'] = 'An error occurred. Please try again.';
            header('Location: /apply/register');
            exit;
        }
    }

    /**
     * Verify email (Step 1 - New Flow)
     */
    public function verifyEmail() {
        $token = $_GET['token'] ?? '';
        $email = $_SESSION['registration_email'] ?? '';
        
        if (!empty($token)) {
            // Verify the token
            $applicant = $this->applicantModel->findByVerificationToken($token);
            
            if ($applicant) {
                // Mark email as verified
                $this->applicantModel->update(
                    [
                        'email_verified' => 1,
                        'verification_token' => null,
                        'email_verified_at' => date('Y-m-d H:i:s')
                    ],
                    'id = :id',
                    ['id' => $applicant['id']]
                );
                
                $this->data['verified'] = true;
                unset($_SESSION['registration_email']);
                
                // Auto login after verification
                $_SESSION['applicant_id'] = $applicant['id'];
                $_SESSION['applicant_email'] = $applicant['email'];
                $_SESSION['applicant_name'] = ($applicant['first_name'] ?? '') . ' ' . ($applicant['last_name'] ?? '');
                $_SESSION['applicant_login_time'] = time();
                
                // Redirect to JAMB verification
                $this->redirect('/apply/step/1');
                return;
            } else {
                $this->data['error'] = 'Invalid or expired verification link';
            }
        } else {
            // Just show the "check your email" page
            $this->data['email'] = $email;
        }
        
        $this->data['pageTitle'] = 'Email Verification';
        $this->render('applications/verify-email');
    }

    /**
     * Resend verification email (Step 1 - New Flow)
     */
    public function resendVerification() {
        $email = $_GET['email'] ?? $_SESSION['registration_email'] ?? '';
        
        if (empty($email)) {
            header('Location: /apply/register');
            exit;
        }
        
        $applicant = $this->applicantModel->findByEmail($email);
        
        if ($applicant && !$applicant['email_verified']) {
            // Generate new token
            $newToken = bin2hex(random_bytes(32));
            
            $this->applicantModel->update(
                ['verification_token' => $newToken],
                'id = :id',
                ['id' => $applicant['id']]
            );
            
            // Resend email
            $this->sendVerificationEmail($email, $newToken);
            
            $_SESSION['flash_success'] = 'Verification email has been resent. Please check your inbox.';
        } else {
            $_SESSION['flash_error'] = 'Email not found or already verified.';
        }
        
        header('Location: /apply/verify-email');
        exit;
    }

    /**
     * Send verification email
     */
    private function sendVerificationEmail($email, $token) {
        $verificationLink = BASE_URL . '/apply/verify-email?token=' . $token;
        
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
                    
                    <p>If the button doesn't work, copy and paste this link into your browser:</p>
                    <p>{$verificationLink}</p>
                    
                    <p>This link will expire in 24 hours.</p>
                </div>
                <div class='footer'>
                    <p>&copy; " . date('Y') . " FCT College of Nursing Sciences</p>
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
        
        $applicant = $this->applicantModel->find($_SESSION['applicant_id']);
        
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
        
        // Check if JAMB has been verified
        if (!isset($_SESSION['jamb_verification']) || !$_SESSION['jamb_verification']) {
            $_SESSION['flash_error'] = 'Please verify your JAMB number first';
            header('Location: /apply/step/1');
            exit;
        }
        
        // Check if application exists
        $application = $this->applicationModel->getByApplicantId($applicant['id']);
        
        if (!$application) {
            // Create new application with JAMB data
            $jambData = $_SESSION['jamb_verification'];
            $applicationId = $this->applicationModel->insert([
                'applicant_id' => $applicant['id'],
                'application_number' => $this->applicationModel->generateApplicationNumber(),
                'jamb_number' => $jambData['jamb_number'],
                'first_name' => $jambData['first_name'],
                'last_name' => $jambData['last_name'],
                'other_names' => $jambData['other_names'],
                'gender' => $jambData['gender'],
                'state_of_origin' => $jambData['state_of_origin'],
                'lga' => $jambData['lga'],
                'utme_score' => $jambData['score'],
                'application_step' => 2,
                'status' => 'pending',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ]);
            
            if ($applicationId) {
                $application = $this->applicationModel->find($applicationId);
            }
        }
        
        if (!$application) {
            $_SESSION['flash_error'] = 'Failed to create application';
            header('Location: /apply/step/1');
            exit;
        }
        
        $this->data = array_merge($this->data, [
            'pageTitle' => 'Application Form - Step 2',
            'application' => $application,
            'applicant' => $applicant,
            'jamb_data' => $_SESSION['jamb_verification'],
            'states' => $this->getStates(),
            'programs' => $this->getPrograms(),
            'csrf_token' => $this->csrfToken()
        ]);
        
        $this->render('applications/application-form');
    }

    /**
     * Save application form
     */
    public function saveApplication() {
        // Start session
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /apply/form');
            exit;
        }
        
        // Check login
        if (!isset($_SESSION['applicant_id'])) {
            $_SESSION['flash_error'] = 'Please login to continue';
            header('Location: /applicant/login');
            exit;
        }
        
        // Validate CSRF
        if (!$this->validateCsrfToken()) {
            $_SESSION['flash_error'] = 'Security token expired. Please try again.';
            header('Location: /apply/form');
            exit;
        }
        
        $applicantId = $_SESSION['applicant_id'];
        $application = $this->applicationModel->getByApplicantId($applicantId);
        
        if (!$application) {
            $_SESSION['flash_error'] = 'Application not found';
            header('Location: /apply/form');
            exit;
        }
        
        // Update application data
        $updateData = [
            'first_name' => trim($_POST['first_name'] ?? ''),
            'last_name' => trim($_POST['last_name'] ?? ''),
            'other_names' => trim($_POST['other_names'] ?? ''),
            'date_of_birth' => $_POST['date_of_birth'] ?? null,
            'gender' => $_POST['gender'] ?? '',
            'marital_status' => $_POST['marital_status'] ?? '',
            'nationality' => $_POST['nationality'] ?? 'Nigerian',
            'state_of_origin' => $_POST['state_of_origin'] ?? '',
            'lga' => $_POST['lga'] ?? '',
            'address' => trim($_POST['address'] ?? ''),
            'city' => trim($_POST['city'] ?? ''),
            'program_choice_1' => $_POST['program_choice_1'] ?? '',
            'program_choice_2' => $_POST['program_choice_2'] ?? '',
            'program_choice_3' => $_POST['program_choice_3'] ?? '',
            'updated_at' => date('Y-m-d H:i:s')
        ];
        
        // Update email and phone in applicants table
        $email = trim($_POST['email'] ?? '');
        $phone = trim($_POST['phone'] ?? '');
        
        if (!empty($email) && filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->applicantModel->update(
                ['email' => $email, 'updated_at' => date('Y-m-d H:i:s')],
                'id = :id',
                ['id' => $applicantId]
            );
        }
        
        if (!empty($phone) && preg_match('/^[0-9]{11}$/', $phone)) {
            $this->applicantModel->update(
                ['phone' => $phone, 'updated_at' => date('Y-m-d H:i:s')],
                'id = :id',
                ['id' => $applicantId]
            );
        }
        
        // Update application
        $this->applicationModel->update($updateData, 'id = :id', ['id' => $application['id']]);
        
        // Handle O'Level results
        if (isset($_POST['olevel']) && is_array($_POST['olevel'])) {
            require_once MODELS_PATH . '/application/OlevelResultModel.php';
            $olevelModel = new OlevelResultModel();
            
            // Clear existing results
            $olevelModel->deleteByApplicationId($application['id']);
            
            // Save new results
            foreach ($_POST['olevel'] as $result) {
                if (!empty($result['subject']) && !empty($result['grade'])) {
                    $olevelModel->insert([
                        'application_id' => $application['id'],
                        'subject' => $result['subject'],
                        'grade' => $result['grade'],
                        'exam_type' => $result['exam_type'] ?? 'WAEC',
                        'exam_year' => $result['exam_year'] ?? date('Y'),
                        'exam_number' => $result['exam_number'] ?? '',
                        'created_at' => date('Y-m-d H:i:s')
                    ]);
                }
            }
        }
        
        // Handle passport upload
        if (isset($_FILES['passport']) && $_FILES['passport']['error'] === UPLOAD_ERR_OK) {
            $allowedTypes = ['image/jpeg', 'image/png', 'image/jpg'];
            $maxSize = 2 * 1024 * 1024; // 2MB
            
            if (in_array($_FILES['passport']['type'], $allowedTypes) && $_FILES['passport']['size'] <= $maxSize) {
                require_once MODELS_PATH . '/application/ApplicationDocumentModel.php';
                $docModel = new ApplicationDocumentModel();
                
                $uploadPath = UPLOADS_PATH . '/passports/' . $application['id'] . '/';
                if (!is_dir($uploadPath)) {
                    mkdir($uploadPath, 0777, true);
                }
                
                $filename = 'passport_' . time() . '_' . $_FILES['passport']['name'];
                $filepath = $uploadPath . $filename;
                
                if (move_uploaded_file($_FILES['passport']['tmp_name'], $filepath)) {
                    // Delete old passport if exists
                    $oldPassport = $docModel->getPassport($application['id']);
                    if ($oldPassport && file_exists(UPLOADS_PATH . '/' . $oldPassport['file_path'])) {
                        unlink(UPLOADS_PATH . '/' . $oldPassport['file_path']);
                        $docModel->delete($oldPassport['id']);
                    }
                    
                    // Save new passport
                    $docModel->insert([
                        'application_id' => $application['id'],
                        'document_type' => 'passport',
                        'file_name' => $filename,
                        'file_path' => 'passports/' . $application['id'] . '/' . $filename,
                        'file_size' => $_FILES['passport']['size'],
                        'mime_type' => $_FILES['passport']['type'],
                        'uploaded_at' => date('Y-m-d H:i:s')
                    ]);
                }
            }
        }
        
        // Handle JAMB result slip upload
        if (isset($_FILES['jamb_result']) && $_FILES['jamb_result']['error'] === UPLOAD_ERR_OK) {
            $allowedTypes = ['image/jpeg', 'image/png', 'image/jpg', 'application/pdf'];
            $maxSize = 2 * 1024 * 1024; // 2MB
            
            if (in_array($_FILES['jamb_result']['type'], $allowedTypes) && $_FILES['jamb_result']['size'] <= $maxSize) {
                require_once MODELS_PATH . '/application/ApplicationDocumentModel.php';
                $docModel = new ApplicationDocumentModel();
                
                $uploadPath = UPLOADS_PATH . '/jamb_results/' . $application['id'] . '/';
                if (!is_dir($uploadPath)) {
                    mkdir($uploadPath, 0777, true);
                }
                
                $filename = 'jamb_result_' . time() . '_' . $_FILES['jamb_result']['name'];
                $filepath = $uploadPath . $filename;
                
                if (move_uploaded_file($_FILES['jamb_result']['tmp_name'], $filepath)) {
                    $docModel->insert([
                        'application_id' => $application['id'],
                        'document_type' => 'jamb_result',
                        'file_name' => $filename,
                        'file_path' => 'jamb_results/' . $application['id'] . '/' . $filename,
                        'file_size' => $_FILES['jamb_result']['size'],
                        'mime_type' => $_FILES['jamb_result']['type'],
                        'uploaded_at' => date('Y-m-d H:i:s')
                    ]);
                }
            }
        }
        
        // Handle birth certificate upload
        if (isset($_FILES['birth_certificate']) && $_FILES['birth_certificate']['error'] === UPLOAD_ERR_OK) {
            $allowedTypes = ['image/jpeg', 'image/png', 'image/jpg', 'application/pdf'];
            $maxSize = 2 * 1024 * 1024; // 2MB
            
            if (in_array($_FILES['birth_certificate']['type'], $allowedTypes) && $_FILES['birth_certificate']['size'] <= $maxSize) {
                require_once MODELS_PATH . '/application/ApplicationDocumentModel.php';
                $docModel = new ApplicationDocumentModel();
                
                $uploadPath = UPLOADS_PATH . '/birth_certificates/' . $application['id'] . '/';
                if (!is_dir($uploadPath)) {
                    mkdir($uploadPath, 0777, true);
                }
                
                $filename = 'birth_certificate_' . time() . '_' . $_FILES['birth_certificate']['name'];
                $filepath = $uploadPath . $filename;
                
                if (move_uploaded_file($_FILES['birth_certificate']['tmp_name'], $filepath)) {
                    $docModel->insert([
                        'application_id' => $application['id'],
                        'document_type' => 'birth_certificate',
                        'file_name' => $filename,
                        'file_path' => 'birth_certificates/' . $application['id'] . '/' . $filename,
                        'file_size' => $_FILES['birth_certificate']['size'],
                        'mime_type' => $_FILES['birth_certificate']['type'],
                        'uploaded_at' => date('Y-m-d H:i:s')
                    ]);
                }
            }
        }
        
        $_SESSION['flash_success'] = 'Application saved successfully';
        
        // Determine redirect based on action
        $action = $_POST['action'] ?? 'save';
        if ($action === 'next') {
            header('Location: /apply/step/3');
        } else {
            header('Location: /apply/form');
        }
        exit;
    }

    // ============================================
    // STEP 3: PAYMENT
    // ============================================

    /**
     * Show payment page (Step 3 - New Flow)
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
        
        // Check if already paid
        $hasPaid = $this->paymentModel->hasSuccessfulPayment($application['id']);
        
        if ($hasPaid) {
            header('Location: /apply/step/4');
            exit;
        }
        
        $fee = $this->settingsModel->getApplicationFee();
        $currency = $this->settingsModel->getCurrency();
        
        $this->data = array_merge($this->data, [
            'pageTitle' => 'Payment - Step 3',
            'application' => $application,
            'fee' => $fee,
            'currency' => $currency,
            'formatted_fee' => $this->settingsModel->getFormattedFee(),
            'csrf_token' => $this->csrfToken()
        ]);
        
        $this->render('applications/payment');
    }

    /**
     * Initiate payment
     */
    public function initiatePayment() {
        // Implementation here - would redirect to payment gateway
        $_SESSION['flash_success'] = 'Payment initiated successfully';
        header('Location: /apply/verify-payment?rrr=TEST123456');
        exit;
    }

    /**
     * Verify payment
     */
    public function verifyPayment() {
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
        
        $rrr = $_GET['rrr'] ?? '';
        
        if (empty($rrr)) {
            $_SESSION['flash_error'] = 'Invalid payment reference';
            header('Location: /apply/step/3');
            exit;
        }
        
        $applicantId = $_SESSION['applicant_id'];
        $application = $this->applicationModel->getByApplicantId($applicantId);
        
        if (!$application) {
            $_SESSION['flash_error'] = 'Application not found';
            header('Location: /apply/form');
            exit;
        }
        
        // For demo purposes, simulate successful payment
        // In production, this would verify with payment gateway
        
        // Create payment record
        $this->paymentModel->insert([
            'application_id' => $application['id'],
            'applicant_id' => $applicantId,
            'amount' => $this->settingsModel->getApplicationFee(),
            'rrr' => $rrr,
            'order_id' => uniqid('ORD-'),
            'status' => 'success',
            'payment_method' => 'remita',
            'transaction_id' => 'TXN' . time(),
            'payment_date' => date('Y-m-d H:i:s'),
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ]);
        
        // Generate exam slip
        $this->generateExamSlip($application['id']);
        
        $_SESSION['flash_success'] = 'Payment verified successfully';
        header('Location: /apply/step/4');
        exit;
    }

    // ============================================
    // STEP 4: EXAM SLIP
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
            $slipNumber = 'SLIP-' . date('Y') . '-' . str_pad($application['id'], 5, '0', STR_PAD_LEFT);
            
            $examSlipId = $examSlipModel->insert([
                'application_id' => $application['id'],
                'slip_number' => $slipNumber,
                'exam_date' => $this->settingsModel->get('cbt_start_date', date('Y-m-d', strtotime('+7 days'))),
                'exam_time' => '10:00 AM',
                'reporting_time' => '8:00 AM',
                'exam_venue' => 'FCT College of Nursing Sciences, Gwagwalada (within UATH)',
                'seat_number' => 'SEAT-' . rand(100, 999),
                'instructions' => 'Bring this slip, valid ID, and writing materials.',
                'created_at' => date('Y-m-d H:i:s')
            ]);
            
            if ($examSlipId) {
                $examSlip = $examSlipModel->find($examSlipId);
            }
        }
        
        $this->data = array_merge($this->data, [
            'pageTitle' => 'Examination Slip - Step 4',
            'application' => $application,
            'exam_slip' => $examSlip,
            'exam_details' => [
                'date' => $this->settingsModel->get('cbt_start_date', 'To be announced'),
                'venue' => 'FCT College of Nursing Sciences, Gwagwalada (within UATH)',
                'reporting_time' => '8:00 AM'
            ]
        ]);
        
        $this->render('applications/exam-slip');
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
     * Generate exam slip (helper method)
     */
    private function generateExamSlip($applicationId) {
        require_once MODELS_PATH . '/application/ExamSlipModel.php';
        $examSlipModel = new ExamSlipModel();
        
        // Check if exam slip already exists
        $existing = $examSlipModel->getByApplicationId($applicationId);
        if ($existing) {
            return $existing;
        }
        
        // Generate slip number
        $slipNumber = 'SLIP-' . date('Y') . '-' . str_pad($applicationId, 5, '0', STR_PAD_LEFT);
        
        // Create exam slip
        $examSlipId = $examSlipModel->insert([
            'application_id' => $applicationId,
            'slip_number' => $slipNumber,
            'exam_date' => $this->settingsModel->get('cbt_start_date', date('Y-m-d', strtotime('+7 days'))),
            'exam_time' => '10:00 AM',
            'reporting_time' => '8:00 AM',
            'exam_venue' => 'FCT College of Nursing Sciences, Gwagwalada (within UATH)',
            'seat_number' => 'SEAT-' . rand(100, 999),
            'instructions' => "1. Arrive at least 1 hour before examination time\n2. Bring this slip and a valid means of identification\n3. Bring writing materials (biro, pencil, eraser)\n4. Mobile phones and electronic devices are not allowed",
            'created_at' => date('Y-m-d H:i:s')
        ]);
        
        return $examSlipId ? $examSlipModel->find($examSlipId) : null;
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
     * Process applicant login - FIXED REDIRECT
     */
    public function processLogin() {
        // Start session
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /applicant/login');
            exit;
        }
        
        // Validate CSRF
        if (!$this->validateCsrfToken()) {
            $_SESSION['flash_error'] = 'Security token expired. Please try again.';
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
        
        // Find by email or phone
        $applicant = $this->applicantModel->findByEmail($login);
        if (!$applicant) {
            $applicant = $this->applicantModel->findByPhone($login);
        }
        
        if (!$applicant) {
            $_SESSION['flash_error'] = 'Invalid login credentials.';
            header('Location: /applicant/login');
            exit;
        }
        
        // Check if email is verified
        if (!$applicant['email_verified']) {
            $_SESSION['flash_error'] = 'Please verify your email before logging in.';
            header('Location: /applicant/login');
            exit;
        }
        
        // Verify password
        if (!password_verify($password, $applicant['password'])) {
            $_SESSION['flash_error'] = 'Invalid login credentials.';
            header('Location: /applicant/login');
            exit;
        }
        
        // Set session
        $_SESSION['applicant_id'] = $applicant['id'];
        $_SESSION['applicant_email'] = $applicant['email'];
        $_SESSION['applicant_name'] = ($applicant['first_name'] ?? '') . ' ' . ($applicant['last_name'] ?? '');
        $_SESSION['applicant_login_time'] = time();
        
        // FIX: Redirect to STEP 1 for JAMB verification, not directly to form
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
        $this->data = array_merge($this->data, [
            'pageTitle' => 'Forgot Password - Application Portal',
            'csrf_token' => $this->csrfToken()
        ]);
        
        $this->render('applications/forgot-password');
    }
    
    /**
     * Process forgot password
     */
    public function processForgotPassword() {
        // Implementation here
        $_SESSION['flash_success'] = 'Password reset instructions have been sent to your email.';
        header('Location: /applicant/login');
        exit;
    }
    
    /**
     * Show reset password page
     */
    public function resetPassword() {
        $token = $_GET['token'] ?? '';
        
        $this->data = array_merge($this->data, [
            'pageTitle' => 'Reset Password',
            'token' => $token,
            'csrf_token' => $this->csrfToken()
        ]);
        
        $this->render('applications/reset-password');
    }
    
    /**
     * Process reset password
     */
    public function processResetPassword() {
        // Implementation here
        $_SESSION['flash_success'] = 'Password reset successfully. You can now login with your new password.';
        header('Location: /applicant/login');
        exit;
    }

    // ============================================
    // LEGACY METHODS (for backward compatibility)
    // ============================================

    /**
     * Show step 1: JAMB verification (Legacy Flow)
     */
    public function step1() {
        // Check if portal is open
        if (!$this->settingsModel->isPortalOpen()) {
            $this->data['portal_closed'] = true;
            $this->data['portal_message'] = $this->settingsModel->getPortalMessage();
            $this->render('applications/step1');
            return;
        }
        
        // If not logged in, redirect to login
        if (!$this->isApplicantLoggedIn()) {
            $_SESSION['flash_error'] = 'Please login to continue';
            $this->redirect('/applicant/login');
            return;
        }
        
        // Get terms and settings for display
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
     * Show step 2: Application form (Legacy Flow)
     */
    public function step2() {
        // Check login
        if (!$this->isApplicantLoggedIn()) {
            $_SESSION['flash_error'] = 'Please login to continue';
            header('Location: /applicant/login');
            return;
        }
        
        // Check if JAMB has been verified
        if (!isset($_SESSION['jamb_verification']) || !$_SESSION['jamb_verification']) {
            $_SESSION['flash_error'] = 'Please verify your JAMB number first';
            header('Location: /apply/step/1');
            return;
        }
        
        $this->data['pageTitle'] = 'Step 2: Application Form';
        $this->render('applications/step2');
    }

    /**
     * Show step 3: Payment (Legacy Flow)
     */
    public function step3() {
        // Check login
        if (!$this->isApplicantLoggedIn()) {
            $_SESSION['flash_error'] = 'Please login to continue';
            header('Location: /applicant/login');
            return;
        }
        
        $this->data['pageTitle'] = 'Step 3: Payment';
        $this->render('applications/step3');
    }

    /**
     * Show step 4: Exam Slip (Legacy Flow)
     */
    public function step4() {
        // Check login
        if (!$this->isApplicantLoggedIn()) {
            $_SESSION['flash_error'] = 'Please login to continue';
            header('Location: /applicant/login');
            return;
        }
        
        $this->data['pageTitle'] = 'Step 4: Examination Slip';
        $this->render('applications/step4');
    }

    // ============================================
    // JAMB VERIFICATION METHODS
    // ============================================

    /**
     * Verify JAMB number (AJAX endpoint) - FIXED VERSION
     */
    public function verifyJamb() {
        // Set header for JSON response
        header('Content-Type: application/json');
        
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
        
        // Start session if not already started
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
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
            'HND Nursing',
            'ND/HND Nursing (Non-terminal)',
            'Post-Basic Nursing',
            'Midwifery'
        ];
    }
}