<?php
/**
 * Public Application Controller
 *
 * FIXED: initiatePayment - fee cast to string, payer name/phone fallbacks, improved Remita error surface
 * FIXED: verifyPayment - added missing CSRF validation, sanitised RRR input
 * FIXED: Extracted restoreJambSession(), autoLoginAndRedirect(), getRedirectStepUrl(),
 *        generatePaymentCsrfToken(), validatePaymentCsrfToken() as private helpers to remove duplication
 * FIXED: logout() - session re-started after destroy so flash message works
 * FIXED: processLogin() - uses match() for cleaner login-type error messages
 *
 * @package FCT_CNS
 */

require_once __DIR__ . '/ApplicationBaseController.php';

class PublicApplicationController extends ApplicationBaseController {

    private $jambModel;
    private $termsModel;

    public function __construct() {
        parent::__construct();

        require_once MODELS_PATH . '/JambCandidateModel.php';
        require_once MODELS_PATH . '/application/TermsModel.php';
        require_once MODELS_PATH . '/application/OlevelResultModel.php';
        require_once MODELS_PATH . '/application/ApplicationDocumentModel.php';
        require_once MODELS_PATH . '/application/ExamSlipModel.php';

        $this->jambModel  = new JambCandidateModel();
        $this->termsModel = new TermsModel();
        $this->layout     = 'application';
    }

    // =========================================================================
    // LANDING
    // =========================================================================

    public function landing() {
        if ($this->isApplicantLoggedIn()) {
            $application = $this->getApplication();
            if ($application) {
                $this->redirect($this->getRedirectStepUrl($application));
            } else {
                $this->redirect('/apply/step/1');
            }
            return;
        }

        $this->data = array_merge($this->data, [
            'pageTitle'   => 'Application Portal - FCT College of Nursing Sciences',
            'settings'    => $this->settingsModel->getAllSettings(),
            'portal_open' => $this->settingsModel->isPortalOpen(),
        ]);

        $this->render('applications/index');
    }

    // =========================================================================
    // REGISTRATION
    // =========================================================================

    public function showRegistration() {
        if (!$this->settingsModel->isPortalOpen()) {
            $this->data['portal_closed']  = true;
            $this->data['portal_message'] = $this->settingsModel->getPortalMessage();
            $this->render('applications/portal-closed');
            return;
        }

        if ($this->isApplicantLoggedIn()) {
            $app = $this->getApplication();
            $this->redirect($app ? '/apply/step/' . $app['application_step'] : '/apply/step/1');
            return;
        }

        $this->data = array_merge($this->data, [
            'pageTitle'          => 'Create Account - Step 1',
            'terms'              => $this->termsModel->getForAcceptance(),
            'has_accepted_terms' => $_SESSION['accepted_terms'] ?? false,
            'csrf_token'         => $this->csrfToken(),
        ]);

        $this->render('applications/register');
    }

    public function processRegistration() {
        if (session_status() === PHP_SESSION_NONE) session_start();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /apply/register'); exit;
        }

        if (!$this->validateCsrfToken()) {
            $_SESSION['flash_error'] = 'Security token expired. Please try again.';
            header('Location: /apply/register'); exit;
        }

        $email    = trim($_POST['email']            ?? '');
        $phone    = trim($_POST['phone']            ?? '');
        $password = $_POST['password']              ?? '';
        $confirm  = $_POST['confirm_password']      ?? '';
        $terms    = isset($_POST['terms']);

        $errors = [];
        if (!filter_var($email, FILTER_VALIDATE_EMAIL))       $errors[] = 'A valid email address is required';
        if (!preg_match('/^[0-9]{11}$/', $phone))             $errors[] = 'Valid 11-digit phone number is required';
        if (strlen($password) < 8)                             $errors[] = 'Password must be at least 8 characters';
        if ($password !== $confirm)                            $errors[] = 'Passwords do not match';
        if (!$terms)                                           $errors[] = 'You must accept the terms and conditions';
        if ($this->applicantModel->findByEmail($email))       $errors[] = 'Email address is already registered';
        if ($this->applicantModel->findByPhone($phone))       $errors[] = 'Phone number is already registered';

        if (!empty($errors)) {
            $_SESSION['flash_error'] = implode('<br>', $errors);
            header('Location: /apply/register'); exit;
        }

        try {
            $token       = bin2hex(random_bytes(32));
            $applicantId = $this->applicantModel->insert([
                'email'              => $email,
                'phone'              => $phone,
                'password'           => password_hash($password, PASSWORD_DEFAULT),
                'verification_token' => $token,
                'email_verified'     => 0,
                'status'             => 'active',
                'created_at'         => date('Y-m-d H:i:s'),
                'updated_at'         => date('Y-m-d H:i:s'),
            ]);

            if (!$applicantId) throw new Exception('Failed to create account');

            $this->sendVerificationEmail($email, $token);
            $_SESSION['registration_email'] = $email;
            header('Location: /apply/verify-email'); exit;

        } catch (Exception $e) {
            error_log("Registration error: " . $e->getMessage());
            $_SESSION['flash_error'] = 'An error occurred. Please try again.';
            header('Location: /apply/register'); exit;
        }
    }

    // =========================================================================
    // EMAIL VERIFICATION
    // =========================================================================

    public function verifyEmail() {
        if (session_status() === PHP_SESSION_NONE) session_start();

        $token = $_GET['token'] ?? '';
        $email = $_GET['email'] ?? $_SESSION['registration_email'] ?? '';

        error_log("verifyEmail: token=$token email=$email");

        if (empty($token)) {
            $this->data = ['email' => $email, 'pageTitle' => 'Verify Your Email', 'email_sent' => true];
            $this->render('applications/verify-email');
            return;
        }

        $applicant = $this->applicantModel->findByVerificationToken($token);

        if (!$applicant) {
            if (!empty($email)) {
                $byEmail = $this->applicantModel->findByEmail($email);
                if ($byEmail && $byEmail['email_verified'] == 1) {
                    $this->autoLoginAndRedirect($byEmail, '/apply/step/1');
                }
            }
            $this->data = [
                'error'        => 'Invalid or expired verification link. Please request a new verification email.',
                'resend_email' => $email,
                'pageTitle'    => 'Verification Failed',
            ];
            $this->render('applications/verify-email');
            return;
        }

        if ($applicant['email_verified'] == 1) {
            $this->autoLoginAndRedirect($applicant, '/apply/step/1');
        }

        $updated = $this->applicantModel->update(
            ['email_verified' => 1, 'verification_token' => null, 'email_verified_at' => date('Y-m-d H:i:s')],
            'id = :id',
            ['id' => $applicant['id']]
        );

        if ($updated) {
            $this->autoLoginAndRedirect($applicant, '/apply/step/1');
        }

        $this->data = [
            'error'        => 'Failed to verify email. Please try again.',
            'resend_email' => $applicant['email'],
            'pageTitle'    => 'Verification Failed',
        ];
        $this->render('applications/verify-email');
    }

    public function resendVerification() {
        if (session_status() === PHP_SESSION_NONE) session_start();

        $email = $_GET['email'] ?? $_SESSION['registration_email'] ?? '';

        if (empty($email)) {
            $_SESSION['flash_error'] = 'Email address not found. Please register again.';
            header('Location: /apply/register'); exit;
        }

        $applicant = $this->applicantModel->findByEmail($email);

        if (!$applicant) {
            $_SESSION['flash_error'] = 'Email address not found in our records. Please register again.';
            header('Location: /apply/register'); exit;
        }

        if ($applicant['email_verified'] == 1) {
            $_SESSION['flash_success'] = 'Your email is already verified. Please login.';
            header('Location: /applicant/login'); exit;
        }

        $newToken = bin2hex(random_bytes(32));
        $this->applicantModel->update(['verification_token' => $newToken], 'id = :id', ['id' => $applicant['id']]);
        $this->sendVerificationEmail($email, $newToken);

        $_SESSION['registration_email'] = $email;
        $_SESSION['flash_success']      = 'Verification email resent. Please check your inbox.';
        header('Location: /apply/verify-email?email=' . urlencode($email)); exit;
    }

    private function sendVerificationEmail($email, $token) {
        $verificationLink = BASE_URL . '/apply/verify-email?token=' . $token;
        $resendLink       = BASE_URL . '/apply/resend-verification?email=' . urlencode($email);
        $subject          = "Verify Your Email - FCT College of Nursing Sciences";

        $message = "<!DOCTYPE html><html><head><style>
            body{font-family:Arial,sans-serif;line-height:1.6}
            .container{max-width:600px;margin:0 auto;padding:20px}
            .header{background:#6B4E9B;color:white;padding:20px;text-align:center}
            .content{padding:20px;background:#f9f9f9}
            .button{display:inline-block;padding:10px 20px;background:#6B4E9B;color:white;text-decoration:none;border-radius:5px}
            .footer{text-align:center;padding:20px;color:#666;font-size:12px}
            .info{background:#e8f4fd;padding:15px;border-radius:5px;margin:20px 0}
        </style></head><body>
        <div class='container'>
            <div class='header'><h2>FCT College of Nursing Sciences</h2><p>Email Verification</p></div>
            <div class='content'>
                <h3>Hello!</h3>
                <p>Thank you for registering. Please verify your email by clicking below:</p>
                <p style='text-align:center'><a href='{$verificationLink}' class='button'>Verify Email Address</a></p>
                <div class='info'>
                    <p><strong>Didn't receive this email?</strong></p>
                    <p>Copy and paste this link: <span style='word-break:break-all'>{$verificationLink}</span></p>
                    <p><a href='{$resendLink}'>Click here to resend</a></p>
                </div>
                <p><strong>Note:</strong> This link expires in 24 hours.</p>
            </div>
            <div class='footer'><p>&copy;" . date('Y') . " FCT College of Nursing Sciences</p></div>
        </div></body></html>";

        if (file_exists(APP_PATH . '/helpers/EmailHelper.php')) {
            require_once APP_PATH . '/helpers/EmailHelper.php';
            (new EmailHelper())->sendEmail($email, $subject, $message);
        } else {
            error_log("Verification email for $email: $verificationLink");
        }
    }

    // =========================================================================
    // STEP 2: APPLICATION FORM
    // =========================================================================

    public function showApplicationForm() {
        if (session_status() === PHP_SESSION_NONE) session_start();

        if (!isset($_SESSION['applicant_id'])) {
            $_SESSION['flash_error'] = 'Please login to continue';
            header('Location: /applicant/login'); exit;
        }

        $applicantId = $_SESSION['applicant_id'];
        $applicant   = $this->applicantModel->find($applicantId);

        if (!$applicant || !$applicant['email_verified']) {
            $_SESSION['flash_error'] = !$applicant ? 'Applicant not found' : 'Please verify your email first';
            header('Location: ' . (!$applicant ? '/applicant/login' : '/apply/verify-email')); exit;
        }

        $application = $this->applicationModel->getByApplicantId($applicantId);

        if (!$application || empty($application['jamb_number'])) {
            $_SESSION['flash_error'] = !$application ? 'Application not found. Please start over.' : 'Please verify your JAMB number first';
            header('Location: /apply/step/1'); exit;
        }

        if (!isset($_SESSION['jamb_verification'])) {
            $this->restoreJambSession($application);
        }

        header('Location: /apply/step/2'); exit;
    }

    public function saveApplication() {
        header('Content-Type: application/json');
        if (session_status() === PHP_SESSION_NONE) session_start();

        if (!isset($_SESSION['applicant_id'])) {
            echo json_encode(['success' => false, 'message' => 'Please login first']); return;
        }

        if (!isset($_POST['csrf_token']) || !$this->validateCsrfToken()) {
            echo json_encode(['success' => false, 'message' => 'Invalid security token']); return;
        }

        try {
            $applicantId   = $_SESSION['applicant_id'];
            $dateOfBirth   = $_POST['date_of_birth']  ?? '';
            $phone         = $_POST['phone']           ?? '';
            $address       = $_POST['address']         ?? '';
            $nationality   = $_POST['nationality']     ?? 'Nigerian';
            $programChoice = $_POST['program_choice']  ?? $_POST['program_choice_1'] ?? '';
            $email         = $_POST['email']           ?? '';

            $missing = [];
            if (empty($dateOfBirth))   $missing[] = 'date_of_birth';
            if (empty($phone))         $missing[] = 'phone';
            if (empty($address))       $missing[] = 'address';
            if (empty($programChoice)) $missing[] = 'program_choice';

            if (!empty($missing)) {
                echo json_encode(['success' => false, 'message' => 'Please fill all required fields: ' . implode(', ', $missing)]);
                return;
            }

            $application = $this->applicationModel->getByApplicantId($applicantId);
            if (!$application) {
                echo json_encode(['success' => false, 'message' => 'Application not found']); return;
            }

            $updateData = [
                'date_of_birth'    => $dateOfBirth,
                'phone'            => $phone,
                'email'            => $email,
                'address'          => $address,
                'nationality'      => $nationality,
                'program_choice_1' => $programChoice,
                'application_step' => 3,
                'updated_at'       => date('Y-m-d H:i:s'),
            ];

            // O'Level - delete first to prevent duplication
            if (isset($_POST['olevel']) && is_array($_POST['olevel'])) {
                $formatted = [];
                foreach ($_POST['olevel'] as $r) {
                    if (empty($r['exam_type']) || empty($r['exam_year'])) continue;
                    $formatted[] = [
                        'exam_type'         => $r['exam_type'],
                        'exam_year'         => $r['exam_year'],
                        'exam_number'       => $r['exam_number']       ?? '',
                        'sitting'           => $r['sitting']           ?? '1st',
                        'english_grade'     => $r['english_grade']     ?? '',
                        'mathematics_grade' => $r['mathematics_grade'] ?? '',
                        'biology_grade'     => $r['biology_grade']     ?? '',
                        'chemistry_grade'   => $r['chemistry_grade']   ?? '',
                        'physics_grade'     => $r['physics_grade']     ?? '',
                    ];
                }

                if (!empty($formatted)) {
                    $updateData['olevel_results'] = json_encode($formatted);
                    try {
                        require_once MODELS_PATH . '/application/OlevelResultModel.php';
                        $olevelModel = new OlevelResultModel();
                        $olevelModel->deleteByApplicationId($application['id']);
                        foreach ($formatted as $r) {
                            $r['application_id'] = $application['id'];
                            $olevelModel->insert($r);
                        }
                    } catch (Exception $e) {
                        error_log("O'Level table save error: " . $e->getMessage());
                    }
                }
            }

            // Passport upload
            $uploadErrors = [];
            if (isset($_FILES['passport']) && $_FILES['passport']['error'] === UPLOAD_ERR_OK) {
                $result = $this->uploadFile($_FILES['passport'], $applicantId, 'passport');
                if ($result['success']) {
                    $updateData['passport_photo'] = $result['path'];
                } else {
                    $uploadErrors[] = 'Passport: ' . $result['message'];
                }
            }

            if (!$this->applicationModel->updateApplication($application['id'], $updateData)) {
                echo json_encode(['success' => false, 'message' => 'Failed to update application']); return;
            }

            if (!empty($phone) || !empty($email)) {
                try {
                    $this->applicantModel->update(
                        ['phone' => $phone, 'email' => $email, 'updated_at' => date('Y-m-d H:i:s')],
                        'id = :id', ['id' => $applicantId]
                    );
                } catch (Exception $e) {
                    error_log("Applicant update error: " . $e->getMessage());
                }
            }

            $response = ['success' => true, 'message' => 'Application saved successfully', 'application_id' => $application['id']];
            if (!empty($uploadErrors))                                      $response['upload_errors'] = $uploadErrors;
            if (($_POST['action'] ?? '') === 'next')                        $response['redirect']      = '/apply/step/3';

            echo json_encode($response);

        } catch (Exception $e) {
            error_log("saveApplication error: " . $e->getMessage());
            echo json_encode(['success' => false, 'message' => 'Server error: ' . $e->getMessage()]);
        }
    }

    public function removeDocument() {
        header('Content-Type: application/json');
        if (session_status() === PHP_SESSION_NONE) session_start();

        if (!isset($_SESSION['applicant_id'])) {
            echo json_encode(['success' => false, 'message' => 'Please login first']); return;
        }

        $input = json_decode(file_get_contents('php://input'), true) ?? [];
        $token = $input['csrf_token'] ?? $_POST['csrf_token'] ?? '';

        if (!$this->validateCsrfToken($token)) {
            echo json_encode(['success' => false, 'message' => 'Invalid security token']); return;
        }

        try {
            $applicantId = $_SESSION['applicant_id'];
            $type        = $input['type']  ?? $_POST['type']  ?? '';
            $index       = isset($input['index']) ? (int) $input['index'] : (isset($_POST['index']) ? (int) $_POST['index'] : null);

            if (empty($type)) {
                echo json_encode(['success' => false, 'message' => 'File type not specified']); return;
            }

            $application = $this->applicationModel->getByApplicantId($applicantId);
            if (!$application) {
                echo json_encode(['success' => false, 'message' => 'Application not found']); return;
            }

            $updateData = [];
            $filePath   = null;

            switch ($type) {
                case 'passport':          $filePath = $application['passport_photo'];    $updateData['passport_photo']    = null; break;
                case 'jamb_result':       $filePath = $application['qualification_file']; $updateData['qualification_file'] = null; break;
                case 'birth_certificate': $filePath = $application['birth_certificate'];  $updateData['birth_certificate']  = null; break;
                case 'olevel':
                    if ($index !== null && !empty($application['olevel_file_paths'])) {
                        $files = json_decode($application['olevel_file_paths'], true);
                        if (is_array($files) && isset($files[$index])) {
                            $filePath = $files[$index];
                            array_splice($files, $index, 1);
                            $updateData['olevel_file_paths'] = !empty($files) ? json_encode($files) : null;
                        }
                    }
                    break;
                default:
                    echo json_encode(['success' => false, 'message' => 'Invalid file type']); return;
            }

            if ($filePath && file_exists(PUBLIC_PATH . $filePath)) {
                unlink(PUBLIC_PATH . $filePath);
            }

            if (!empty($updateData)) {
                $updateData['updated_at'] = date('Y-m-d H:i:s');
                if (!$this->applicationModel->updateApplication($application['id'], $updateData)) {
                    echo json_encode(['success' => false, 'message' => 'Failed to update application']); return;
                }
            }

            echo json_encode(['success' => true, 'message' => 'File removed successfully']);

        } catch (Exception $e) {
            error_log("removeDocument error: " . $e->getMessage());
            echo json_encode(['success' => false, 'message' => 'Server error: ' . $e->getMessage()]);
        }
    }

    private function uploadFile($file, $applicantId, $type) {
        $allowed = ['image/jpeg', 'image/png', 'image/jpg', 'application/pdf'];
        if (!in_array($file['type'], $allowed))          return ['success' => false, 'message' => 'Invalid file type. Allowed: JPG, PNG, PDF'];
        if ($file['size'] > 2 * 1024 * 1024)            return ['success' => false, 'message' => 'File too large. Max size: 2MB'];

        $dir = PUBLIC_PATH . "/uploads/applications/{$applicantId}/{$type}";
        if (!is_dir($dir) && !mkdir($dir, 0755, true))   return ['success' => false, 'message' => 'Failed to create upload directory'];

        $ext      = pathinfo($file['name'], PATHINFO_EXTENSION);
        $filename = $type . '_' . time() . '_' . bin2hex(random_bytes(4)) . '.' . $ext;

        if (!move_uploaded_file($file['tmp_name'], $dir . '/' . $filename)) {
            return ['success' => false, 'message' => 'Failed to save file'];
        }

        return ['success' => true, 'path' => "/uploads/applications/{$applicantId}/{$type}/{$filename}"];
    }

    // =========================================================================
    // STEP 3: PAYMENT
    // =========================================================================

    public function showPayment() {
        if (session_status() === PHP_SESSION_NONE) session_start();

        if (!isset($_SESSION['applicant_id'])) {
            $_SESSION['flash_error'] = 'Please login to continue';
            header('Location: /applicant/login'); exit;
        }

        $applicantId = $_SESSION['applicant_id'];
        $application = $this->applicationModel->getByApplicantId($applicantId);

        if (!$application) { $_SESSION['flash_error'] = 'Application not found'; header('Location: /apply/form'); exit; }

        if (!isset($_SESSION['jamb_verification']) && !empty($application['jamb_number'])) {
            $this->restoreJambSession($application);
        }

        if ($this->paymentModel->hasSuccessfulPayment($application['id'])) {
            header('Location: /apply/step/4'); exit;
        }

        $this->data = array_merge($this->data, [
            'pageTitle'     => 'Payment - Step 3',
            'application'   => $application,
            'fee'           => $this->settingsModel->getApplicationFee(),
            'currency'      => $this->settingsModel->getCurrency(),
            'formatted_fee' => $this->settingsModel->getFormattedFee(),
            'csrf_token'    => $this->generatePaymentCsrfToken(),
        ]);

        $this->render('applications/payment');
    }

    /**
     * Initiate payment — generate RRR via Remita API
     *
     * FIXES:
     *  1. Fee cast to string (Remita rejects numeric JSON type)
     *  2. Payer name falls back to email if names not yet populated
     *  3. Payer phone falls back to applicant record
     *  4. debug_hint from RemitaModel surfaced in error response
     *  5. CSRF validated via validatePaymentCsrfToken() which handles both token stores
     */
    public function initiatePayment() {
        header('Content-Type: application/json');
        if (session_status() === PHP_SESSION_NONE) session_start();

        error_log("=== INITIATE PAYMENT CALLED ===");

        if (!isset($_SESSION['applicant_id'])) {
            echo json_encode(['success' => false, 'message' => 'Please login first']); return;
        }

        $input     = json_decode(file_get_contents('php://input'), true) ?? [];
        $csrfToken = $input['csrf_token'] ?? $_POST['csrf_token'] ?? '';

        if (empty($csrfToken)) {
            echo json_encode(['success' => false, 'message' => 'Security token missing']); return;
        }

        if (!$this->validatePaymentCsrfToken($csrfToken)) {
            error_log("initiatePayment: CSRF failed for token=$csrfToken");
            echo json_encode(['success' => false, 'message' => 'Invalid or expired security token. Please refresh the page.']); return;
        }

        try {
            $applicantId = $_SESSION['applicant_id'];
            $application = $this->applicationModel->getByApplicantId($applicantId);

            if (!$application) {
                echo json_encode(['success' => false, 'message' => 'Application not found']); return;
            }

            if ($this->paymentModel->hasSuccessfulPayment($application['id'])) {
                echo json_encode(['success' => false, 'message' => 'Payment already completed']); return;
            }

            // FIX 1: Cast fee to string — Remita rejects numeric JSON type
            $fee       = (string) $this->settingsModel->getApplicationFee();
            $orderId   = 'ORD' . time() . rand(100, 999);
            $reference = 'REF' . time() . rand(1000, 9999);

            $applicant = $this->applicantModel->find($applicantId);

            // FIX 2: Safe payer name
            $payerName = trim(($application['first_name'] ?? '') . ' ' . ($application['last_name'] ?? ''));
            if (empty($payerName)) {
                $payerName = $applicant['email'] ?? 'Applicant';
            }

            $payerEmail = $applicant['email'] ?? '';

            // FIX 3: Safe payer phone
            $payerPhone = $application['phone'] ?? $applicant['phone'] ?? '';

            error_log("Remita call: orderId=$orderId amount=$fee payer=$payerName email=$payerEmail phone=$payerPhone");

            require_once MODELS_PATH . '/application/RemitaModel.php';
            $remitaModel = new RemitaModel();

            $result = $remitaModel->generateRRRRemita($orderId, $fee, $payerName, $payerEmail, $payerPhone);

            error_log("Remita result: " . json_encode($result));

            if ($result['status'] === 'success' && !empty($result['rrr'])) {
                $rrr = $result['rrr'];
                error_log("✅ RRR: $rrr");

                $paymentId = $this->paymentModel->insert([
                    'application_id' => $application['id'],
                    'applicant_id'   => $applicantId,
                    'reference'      => $reference,
                    'rrr'            => $rrr,
                    'order_id'       => $orderId,
                    'amount'         => $fee,
                    'payment_type'   => 'application_fee',
                    'status'         => 'pending',
                    'created_at'     => date('Y-m-d H:i:s'),
                    'updated_at'     => date('Y-m-d H:i:s'),
                ]);

                if (!$paymentId) {
                    echo json_encode(['success' => false, 'message' => 'Failed to create payment record']); return;
                }

                $_SESSION['pending_payment'] = ['payment_id' => $paymentId, 'rrr' => $rrr, 'amount' => $fee];

                echo json_encode([
                    'success'   => true,
                    'message'   => 'RRR generated successfully',
                    'rrr'       => $rrr,
                    'reference' => $reference,
                    'order_id'  => $orderId,
                    'amount'    => $fee,
                ]);

            } else {
                $errorMsg  = $result['message']    ?? 'Unknown error';
                $debugHint = $result['debug_hint'] ?? null;  // FIX 4

                error_log("❌ Remita failed: $errorMsg");

                $response = [
                    'success' => false,
                    'message' => 'Failed to generate payment reference. Please try again or contact support.',
                    'debug'   => $errorMsg,
                ];
                if ($debugHint) $response['debug_hint'] = $debugHint;

                echo json_encode($response);
            }

        } catch (Exception $e) {
            error_log("initiatePayment exception: " . $e->getMessage() . "\n" . $e->getTraceAsString());
            echo json_encode(['success' => false, 'message' => 'Server error: ' . $e->getMessage()]);
        }
    }

    /**
     * Verify payment (AJAX)
     *
     * FIXES:
     *  1. Added CSRF validation (was completely absent in original)
     *  2. Sanitised RRR input
     */
    public function verifyPayment() {
        header('Content-Type: application/json');
        if (session_status() === PHP_SESSION_NONE) session_start();

        error_log("=== VERIFY PAYMENT CALLED ===");

        if (!isset($_SESSION['applicant_id'])) {
            echo json_encode(['success' => false, 'message' => 'Please login first']); return;
        }

        $input     = json_decode(file_get_contents('php://input'), true) ?? [];
        $csrfToken = $input['csrf_token'] ?? $_POST['csrf_token'] ?? '';

        // FIX 1: CSRF validation was missing
        if (!empty($csrfToken) && !$this->validatePaymentCsrfToken($csrfToken)) {
            error_log("verifyPayment: CSRF invalid");
            echo json_encode(['success' => false, 'message' => 'Invalid security token']); return;
        }

        // FIX 2: Sanitise RRR
        $rrr = preg_replace('/[^0-9A-Z]/i', '', $input['rrr'] ?? $_POST['rrr'] ?? '');

        if (empty($rrr)) {
            echo json_encode(['success' => false, 'message' => 'RRR is required']); return;
        }

        try {
            $payment = $this->paymentModel->getByRRR($rrr);

            if (!$payment) {
                echo json_encode(['success' => false, 'message' => 'Payment record not found']); return;
            }

            if ($payment['applicant_id'] != $_SESSION['applicant_id']) {
                error_log("SECURITY: verifyPayment ownership mismatch RRR=$rrr");
                echo json_encode(['success' => false, 'message' => 'Unauthorized access']); return;
            }

            require_once MODELS_PATH . '/application/RemitaModel.php';
            $remitaModel        = new RemitaModel();
            $verificationResult = $remitaModel->verifyPayment($rrr);

            error_log("Remita verification: " . json_encode($verificationResult));

            if ($verificationResult['status'] === 'success') {
                $ok = $this->paymentModel->markAsSuccess($payment['id'], [
                    'transaction_id'  => $verificationResult['payment_data']['transactionId'] ?? ('TXN' . time()),
                    'payment_method'  => 'remita',
                    'payer_email'     => $verificationResult['payment_data']['payerEmail']    ?? null,
                    'payer_name'      => $verificationResult['payment_data']['payerName']     ?? null,
                    'payment_details' => json_encode($verificationResult['payment_data']),
                ]);

                if ($ok) {
                    $this->applicationModel->updateApplication($payment['application_id'], ['application_step' => 4]);
                    $this->generateExamSlip($payment['application_id']);

                    echo json_encode(['success' => true, 'message' => 'Payment verified successfully', 'redirect' => '/apply/step/4']);
                } else {
                    echo json_encode(['success' => false, 'message' => 'Failed to update payment status']);
                }

            } elseif ($verificationResult['status'] === 'pending') {
                echo json_encode(['success' => false, 'pending' => true, 'message' => 'Payment is still pending. Please wait and check again.']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Payment not found or not completed on Remita.']);
            }

        } catch (Exception $e) {
            error_log("verifyPayment exception: " . $e->getMessage());
            echo json_encode(['success' => false, 'message' => 'Server error occurred']);
        }
    }

    // =========================================================================
    // STEP 4: EXAM SLIP
    // =========================================================================

    public function showExamSlip() {
        if (session_status() === PHP_SESSION_NONE) session_start();
        if (!isset($_SESSION['applicant_id'])) { $_SESSION['flash_error'] = 'Please login to continue'; header('Location: /applicant/login'); exit; }

        $applicantId = $_SESSION['applicant_id'];
        $application = $this->applicationModel->getByApplicantId($applicantId);

        if (!$application)                                                { $_SESSION['flash_error'] = 'Application not found'; header('Location: /apply/form');  exit; }
        if (!$this->paymentModel->hasSuccessfulPayment($application['id'])) { header('Location: /apply/step/3'); exit; }

        require_once MODELS_PATH . '/application/ExamSlipModel.php';
        $examSlipModel = new ExamSlipModel();
        $examSlip      = $examSlipModel->getByApplicationId($application['id']) ?? $this->generateExamSlip($application['id']);

        $applicant      = $this->applicantModel->find($applicantId);
        $applicant_name = trim(($application['first_name'] ?? '') . ' ' . ($application['last_name'] ?? '')) ?: ($applicant['email'] ?? 'Applicant');

        $this->data = array_merge($this->data, [
            'pageTitle'      => 'Examination Slip - Step 4',
            'application'    => $application,
            'exam_slip'      => $examSlip,
            'applicant'      => $applicant,
            'applicant_name' => $applicant_name,
            'exam_details'   => [
                'date'           => $this->settingsModel->get('cbt_start_date', 'To be announced'),
                'venue'          => 'FCT College of Nursing Sciences, Gwagwalada (within UATH)',
                'reporting_time' => '8:00 AM',
            ],
        ]);

        $this->render('applications/step4');
    }

    public function downloadExamSlip() {
        if (session_status() === PHP_SESSION_NONE) session_start();
        if (!isset($_SESSION['applicant_id'])) { $_SESSION['flash_error'] = 'Please login to continue'; header('Location: /applicant/login'); exit; }

        $applicantId = $_SESSION['applicant_id'];
        $application = $this->applicationModel->getByApplicantId($applicantId);

        if (!$application) { $_SESSION['flash_error'] = 'Application not found'; header('Location: /apply/form'); exit; }

        require_once MODELS_PATH . '/application/ExamSlipModel.php';
        $examSlipModel = new ExamSlipModel();
        $examSlip      = $examSlipModel->getByApplicationId($application['id']);

        if (!$examSlip) { $_SESSION['flash_error'] = 'Exam slip not found'; header('Location: /apply/step/4'); exit; }

        $examSlipModel->update(
            ['download_count' => ($examSlip['download_count'] ?? 0) + 1, 'last_downloaded_at' => date('Y-m-d H:i:s'), 'last_downloaded_ip' => $_SERVER['REMOTE_ADDR'] ?? ''],
            'id = :id', ['id' => $examSlip['id']]
        );

        header('Content-Type: text/html');
        header('Content-Disposition: attachment; filename="exam-slip-' . $examSlip['slip_number'] . '.html"');
        echo $this->generateExamSlipHTML($examSlip, $application, $this->applicantModel->find($applicantId));
        exit;
    }

    private function generateExamSlipHTML($examSlip, $application, $applicant) {
        return '<!DOCTYPE html><html><head><title>Examination Slip</title><style>
        body{font-family:Arial,sans-serif;padding:20px}
        .slip{max-width:800px;margin:0 auto;border:2px solid #000;padding:20px}
        .header{text-align:center;border-bottom:2px solid #6B4E9B;padding-bottom:10px;margin-bottom:20px}
        .title{font-size:24px;font-weight:bold;color:#6B4E9B}
        .row{margin-bottom:15px;display:flex}
        .label{font-weight:bold;width:200px}
        .value{flex:1;border-bottom:1px dotted #999;padding-bottom:3px}
        .important{background:#f8f8f8;padding:15px;border-left:4px solid #6B4E9B;margin:20px 0}
        .footer{text-align:center;font-size:12px;color:#666;border-top:1px solid #ccc;padding-top:10px;margin-top:20px}
        </style></head><body>
        <div class="slip">
            <div class="header">
                <div class="title">FCT COLLEGE OF NURSING SCIENCES</div>
                <div>Gwagwalada, Abuja &mdash; 2025/2026 ADMISSIONS SCREENING</div>
                <div style="font-size:20px;margin-top:10px"><strong>EXAMINATION SLIP</strong></div>
            </div>
            <div>
                <div class="row"><div class="label">Slip Number:</div><div class="value">'         . htmlspecialchars($examSlip['slip_number']          ?? 'N/A') . '</div></div>
                <div class="row"><div class="label">Application Number:</div><div class="value">'   . htmlspecialchars($application['application_number']   ?? 'N/A') . '</div></div>
                <div class="row"><div class="label">JAMB Number:</div><div class="value">'          . htmlspecialchars($application['jamb_number']           ?? 'N/A') . '</div></div>
                <div class="row"><div class="label">Candidate Name:</div><div class="value">'       . htmlspecialchars(trim(($applicant['title'] ?? '') . ' ' . ($applicant['first_name'] ?? '') . ' ' . ($applicant['last_name'] ?? ''))) . '</div></div>
                <div class="row"><div class="label">Programme:</div><div class="value">'            . htmlspecialchars($application['program_choice_1']      ?? 'N/A') . '</div></div>
                <div class="row"><div class="label">Examination Date:</div><div class="value">'     . htmlspecialchars(date('l, jS F Y', strtotime($examSlip['exam_date'] ?? date('Y-m-d')))) . '</div></div>
                <div class="row"><div class="label">Examination Time:</div><div class="value">'     . htmlspecialchars($examSlip['exam_time']                ?? '10:00 AM') . '</div></div>
                <div class="row"><div class="label">Reporting Time:</div><div class="value">'       . htmlspecialchars($examSlip['reporting_time']            ?? '8:00 AM')  . '</div></div>
                <div class="row"><div class="label">Venue:</div><div class="value">'                . htmlspecialchars($examSlip['exam_venue']                ?? 'FCT College of Nursing Sciences, Gwagwalada') . '</div></div>
                <div class="row"><div class="label">Seat Number:</div><div class="value">'          . htmlspecialchars($examSlip['seat_number']               ?? 'To be assigned') . '</div></div>
            </div>
            <div class="important"><strong>Important Instructions:</strong><br>'
                . nl2br(htmlspecialchars($examSlip['instructions'] ?? "1. Arrive at least 1 hour before examination time\n2. Bring this slip and a valid means of identification\n3. Bring writing materials (biro, pencil, eraser)\n4. Mobile phones and electronic devices are not allowed"))
            . '</div>
            <div class="footer">
                <p>This slip is computer-generated and does not require signature.</p>
                <p>Generated on: ' . date('jS F Y, h:i A') . ' &mdash; Downloads: ' . ($examSlip['download_count'] ?? 0) . '</p>
            </div>
        </div></body></html>';
    }

    private function generateExamSlip($applicationId) {
        require_once MODELS_PATH . '/application/ExamSlipModel.php';
        $examSlipModel = new ExamSlipModel();

        $existing = $examSlipModel->getByApplicationId($applicationId);
        if ($existing) return $existing;

        $application = $this->applicationModel->find($applicationId);
        $id = $examSlipModel->insert([
            'application_id' => $applicationId,
            'applicant_id'   => $application['applicant_id'] ?? null,
            'slip_number'    => 'SLIP-' . date('Y') . '-' . str_pad($applicationId, 5, '0', STR_PAD_LEFT),
            'exam_date'      => $this->settingsModel->get('cbt_start_date', date('Y-m-d', strtotime('+7 days'))),
            'exam_time'      => '10:00 AM',
            'reporting_time' => '8:00 AM',
            'exam_venue'     => 'FCT College of Nursing Sciences, Gwagwalada (within UATH)',
            'seat_number'    => 'SEAT-' . rand(100, 999),
            'instructions'   => "1. Arrive at least 1 hour before examination time\n2. Bring this slip and a valid means of identification\n3. Bring writing materials (biro, pencil, eraser)\n4. Mobile phones and electronic devices are not allowed",
            'generated_at'   => date('Y-m-d H:i:s'),
            'created_at'     => date('Y-m-d H:i:s'),
        ]);

        return $id ? $examSlipModel->find($id) : null;
    }

    // =========================================================================
    // AUTHENTICATION
    // =========================================================================

    public function login() {
        if (session_status() === PHP_SESSION_NONE) session_start();

        unset($_SESSION['login_error'], $_SESSION['password_error']);

        if (isset($_SESSION['applicant_id'])) { header('Location: /apply/step/1'); exit; }

        $this->data = array_merge($this->data, [
            'pageTitle'  => 'Applicant Login - Application Portal',
            'csrf_token' => $this->csrfToken(),
        ]);

        $this->render('applications/login');
    }

    public function processLogin() {
        if (session_status() === PHP_SESSION_NONE) session_start();

        unset($_SESSION['login_error'], $_SESSION['password_error'], $_SESSION['login_value']);

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') { header('Location: /applicant/login'); exit; }

        if (!$this->validateCsrfToken()) {
            $_SESSION['flash_error'] = 'Security token expired. Please refresh and try again.';
            header('Location: /applicant/login'); exit;
        }

        $login    = trim($_POST['login']    ?? '');
        $password = $_POST['password'] ?? '';

        $_SESSION['login_value'] = $login;

        if (empty($login))    { $_SESSION['flash_error'] = $_SESSION['login_error']    = 'Please enter your email, phone, or JAMB number.'; header('Location: /applicant/login'); exit; }
        if (empty($password)) { $_SESSION['flash_error'] = $_SESSION['password_error'] = 'Please enter your password.'; header('Location: /applicant/login'); exit; }

        $loginType = filter_var($login, FILTER_VALIDATE_EMAIL) ? 'email' : (preg_match('/^[0-9]{11}$/', $login) ? 'phone' : 'jamb');

        $applicant = $this->applicantModel->findByEmail($login)
            ?? $this->applicantModel->findByPhone($login);

        if (!$applicant) {
            $app = $this->applicationModel->getByJambNumber($login);
            if ($app && !empty($app['applicant_id'])) {
                $applicant = $this->applicantModel->find($app['applicant_id']);
            }
        }

        if (!$applicant) {
            $msg = match($loginType) {
                'email' => 'Invalid email address or password.',
                'phone' => 'Invalid phone number or password.',
                default => 'Invalid JAMB number or password.',
            };
            $_SESSION['login_error'] = $_SESSION['flash_error'] = $msg;
            header('Location: /applicant/login'); exit;
        }

        if (empty($applicant['email_verified']) || $applicant['email_verified'] != 1) {
            $_SESSION['flash_error']        = 'Please verify your email before logging in.';
            $_SESSION['verification_email'] = $applicant['email'];
            header('Location: /applicant/login'); exit;
        }

        if (!password_verify($password, $applicant['password'])) {
            $msg = match($loginType) {
                'email' => 'Invalid email address or password.',
                'phone' => 'Invalid phone number or password.',
                default => 'Invalid JAMB number or password.',
            };
            $_SESSION['password_error'] = $_SESSION['flash_error'] = $msg;
            header('Location: /applicant/login'); exit;
        }

        if (!empty($applicant['status']) && $applicant['status'] !== 'active') {
            $_SESSION['flash_error'] = 'Your account is inactive. Please contact support at info@fctcns.edu.ng';
            header('Location: /applicant/login'); exit;
        }

        // --- Success ---
        unset($_SESSION['login_value'], $_SESSION['login_error'], $_SESSION['password_error']);

        $_SESSION['applicant_id']         = $applicant['id'];
        $_SESSION['applicant_email']      = $applicant['email'];
        $_SESSION['applicant_name']       = trim(($applicant['first_name'] ?? '') . ' ' . ($applicant['last_name'] ?? ''));
        $_SESSION['applicant_login_time'] = time();

        $application = $this->applicationModel->getByApplicantId($applicant['id']);
        $redirectUrl = '/apply/step/1';

        if ($application) {
            if (!isset($_SESSION['jamb_verification']) && !empty($application['jamb_number'])) {
                $this->restoreJambSession($application);
            }
            $redirectUrl = $this->getRedirectStepUrl($application);
        }

        $_SESSION['flash_success'] = 'Login successful! Welcome back, ' . $_SESSION['applicant_name'] . '.';
        header('Location: ' . $redirectUrl); exit;
    }

    public function logout() {
        if (session_status() === PHP_SESSION_NONE) session_start();

        $_SESSION = [];

        if (ini_get("session.use_cookies")) {
            $p = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000, $p["path"], $p["domain"], $p["secure"], $p["httponly"]);
        }

        session_destroy();

        // FIX: Re-start session so flash message is writable
        session_start();
        $_SESSION['flash_success'] = 'You have been logged out successfully.';
        header('Location: /applicant/login'); exit;
    }

    public function forgotPassword() {
        if (session_status() === PHP_SESSION_NONE) session_start();

        $this->data = array_merge($this->data, [
            'pageTitle'  => 'Forgot Password - Application Portal',
            'csrf_token' => $this->csrfToken(),
        ]);

        $this->render('applications/forgot-password');
    }

    public function processForgotPassword() {
        if (session_status() === PHP_SESSION_NONE) session_start();
        unset($_SESSION['email_value']);

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') { header('Location: /applicant/forgot-password'); exit; }

        if (!$this->validateCsrfToken()) {
            $_SESSION['flash_error'] = 'Security token expired. Please try again.';
            header('Location: /applicant/forgot-password'); exit;
        }

        $email = trim($_POST['email'] ?? '');
        $_SESSION['email_value'] = $email;

        if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $_SESSION['flash_error'] = empty($email) ? 'Please enter your email address.' : 'Please enter a valid email address.';
            header('Location: /applicant/forgot-password'); exit;
        }

        $applicant = $this->applicantModel->findByEmail($email);

        if (!$applicant) {
            $_SESSION['flash_success'] = 'If your email is registered, you will receive a password reset link shortly.';
            unset($_SESSION['email_value']);
            header('Location: /applicant/forgot-password'); exit;
        }

        try {
            $resetToken = bin2hex(random_bytes(32));
            $this->applicantModel->update(
                ['reset_token' => $resetToken, 'reset_expires' => date('Y-m-d H:i:s', strtotime('+1 hour'))],
                'id = :id', ['id' => $applicant['id']]
            );
            $this->sendPasswordResetEmail($email, $resetToken);
            $_SESSION['flash_success'] = 'Password reset instructions have been sent to your email.';
            unset($_SESSION['email_value']);
        } catch (Exception $e) {
            error_log("processForgotPassword error: " . $e->getMessage());
            $_SESSION['flash_error'] = 'An error occurred. Please try again later.';
        }

        header('Location: /applicant/forgot-password'); exit;
    }

    private function sendPasswordResetEmail($email, $token) {
        $resetLink = BASE_URL . '/applicant/reset-password?token=' . $token;
        $subject   = "Password Reset - FCT College of Nursing Sciences";

        $message = "<!DOCTYPE html><html><head><style>
            body{font-family:Arial,sans-serif;line-height:1.6}
            .container{max-width:600px;margin:0 auto;padding:20px}
            .header{background:#6B4E9B;color:white;padding:20px;text-align:center}
            .content{padding:20px;background:#f9f9f9}
            .button{display:inline-block;padding:10px 20px;background:#6B4E9B;color:white;text-decoration:none;border-radius:5px}
            .footer{text-align:center;padding:20px;color:#666;font-size:12px}
        </style></head><body>
        <div class='container'>
            <div class='header'><h2>FCT College of Nursing Sciences</h2><p>Password Reset Request</p></div>
            <div class='content'>
                <h3>Hello!</h3>
                <p>You recently requested to reset your password. Click the button below:</p>
                <p style='text-align:center'><a href='{$resetLink}' class='button'>Reset Password</a></p>
                <p>If you didn't request this, ignore this email. Link expires in 1 hour.</p>
            </div>
            <div class='footer'><p>&copy;" . date('Y') . " FCT College of Nursing Sciences &mdash; info@fctcns.edu.ng</p></div>
        </div></body></html>";

        if (file_exists(APP_PATH . '/helpers/EmailHelper.php')) {
            require_once APP_PATH . '/helpers/EmailHelper.php';
            (new EmailHelper())->sendEmail($email, $subject, $message);
        } else {
            error_log("Password reset email for $email: $resetLink");
        }
    }

    public function resetPassword() {
        if (session_status() === PHP_SESSION_NONE) session_start();

        $token = $_GET['token'] ?? '';
        if (empty($token)) { $_SESSION['flash_error'] = 'Invalid password reset link.'; header('Location: /applicant/forgot-password'); exit; }

        $applicant = $this->applicantModel->findByResetToken($token);
        if (!$applicant) { $_SESSION['flash_error'] = 'Invalid or expired reset link. Please request a new one.'; header('Location: /applicant/forgot-password'); exit; }

        $this->data = array_merge($this->data, [
            'pageTitle'  => 'Reset Password',
            'token'      => $token,
            'email'      => $applicant['email'],
            'csrf_token' => $this->csrfToken(),
        ]);

        $this->render('applications/reset-password');
    }

    public function processResetPassword() {
        if (session_status() === PHP_SESSION_NONE) session_start();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') { header('Location: /applicant/forgot-password'); exit; }

        if (!$this->validateCsrfToken()) {
            $_SESSION['flash_error'] = 'Security token expired. Please try again.';
            header('Location: /applicant/forgot-password'); exit;
        }

        $token    = $_POST['token']            ?? '';
        $password = $_POST['password']         ?? '';
        $confirm  = $_POST['confirm_password'] ?? '';

        if (empty($token))                    { $_SESSION['flash_error'] = 'Invalid reset token.';                         header('Location: /applicant/forgot-password'); exit; }
        if (empty($password))                 { $_SESSION['flash_error'] = 'Please enter a new password.';                 header('Location: /applicant/reset-password?token=' . urlencode($token)); exit; }
        if (strlen($password) < 8)            { $_SESSION['flash_error'] = 'Password must be at least 8 characters.';     header('Location: /applicant/reset-password?token=' . urlencode($token)); exit; }
        if ($password !== $confirm)           { $_SESSION['flash_error'] = 'Passwords do not match.';                      header('Location: /applicant/reset-password?token=' . urlencode($token)); exit; }

        $applicant = $this->applicantModel->findByResetToken($token);
        if (!$applicant) { $_SESSION['flash_error'] = 'Invalid or expired reset link. Please request a new one.'; header('Location: /applicant/forgot-password'); exit; }

        try {
            $this->applicantModel->update(
                ['password' => password_hash($password, PASSWORD_DEFAULT), 'reset_token' => null, 'reset_expires' => null],
                'id = :id', ['id' => $applicant['id']]
            );
            $_SESSION['flash_success'] = 'Password reset successfully. You can now login with your new password.';
            header('Location: /applicant/login'); exit;
        } catch (Exception $e) {
            error_log("processResetPassword error: " . $e->getMessage());
            $_SESSION['flash_error'] = 'An error occurred. Please try again.';
            header('Location: /applicant/reset-password?token=' . urlencode($token)); exit;
        }
    }

    // =========================================================================
    // STEP ROUTES
    // =========================================================================

    public function step1() {
        if (session_status() === PHP_SESSION_NONE) session_start();
        if (!isset($_SESSION['applicant_id'])) { $_SESSION['flash_error'] = 'Please login to continue'; header('Location: /applicant/login'); exit; }

        $applicantId = $_SESSION['applicant_id'];
        $application = $this->applicationModel->getByApplicantId($applicantId);

        if ($application && !empty($application['jamb_number'])) {
            $this->restoreJambSession($application);
            $this->data['jamb_verified']    = true;
            $this->data['jamb_data']        = $_SESSION['jamb_verification'];
            $this->data['application_step'] = $application['application_step'];

            if ($application['application_step'] == 2) {
                header('Location: /apply/step/2'); exit;
            }
        }

        $this->data = array_merge($this->data, [
            'pageTitle'  => 'Step 1: JAMB Verification',
            'terms'      => $this->termsModel->getForAcceptance(),
            'settings'   => $this->settingsModel->getAllSettings(),
            'csrf_token' => $this->csrfToken(),
        ]);

        $this->render('applications/step1');
    }

    public function step2() {
        if (session_status() === PHP_SESSION_NONE) session_start();
        if (!$this->isApplicantLoggedIn()) { $_SESSION['flash_error'] = 'Please login to continue'; header('Location: /applicant/login'); return; }

        $applicantId = $_SESSION['applicant_id'];

        if (!isset($_SESSION['jamb_verification'])) {
            $application = $this->applicationModel->getByApplicantId($applicantId);
            if ($application && !empty($application['jamb_number'])) {
                $this->restoreJambSession($application);
            } else {
                $_SESSION['flash_error'] = 'Please verify your JAMB number first';
                header('Location: /apply/step/1'); return;
            }
        }

        $application = $this->applicationModel->getByApplicantId($applicantId);
        if (!$application) { $_SESSION['flash_error'] = 'Application not found. Please start over.'; header('Location: /apply/step/1'); return; }

        require_once MODELS_PATH . '/application/OlevelResultModel.php';
        require_once MODELS_PATH . '/application/ApplicationDocumentModel.php';

        $olevelModel    = new OlevelResultModel();
        $docModel       = new ApplicationDocumentModel();

        $this->data = array_merge($this->data, [
            'pageTitle'      => 'Step 2: Application Form',
            'application'    => $application,
            'applicant'      => $this->applicantModel->find($applicantId),
            'jamb_data'      => $_SESSION['jamb_verification'],
            'olevel_results' => $olevelModel->getByApplicationId($application['id']),
            'passport'       => $docModel->getPassport($application['id']),
            'states'         => $this->getStates(),
            'programs'       => $this->getPrograms(),
            'csrf_token'     => $this->csrfToken(),
        ]);

        $this->render('applications/step2');
    }

    public function step3() {
        if (session_status() === PHP_SESSION_NONE) session_start();
        error_log("=== STEP 3 LOADED ===");

        if (!$this->isApplicantLoggedIn()) { $_SESSION['flash_error'] = 'Please login to continue'; header('Location: /applicant/login'); exit; }

        $applicantId = $_SESSION['applicant_id'];
        $application = $this->applicationModel->getByApplicantId($applicantId);

        if (!$application) { $_SESSION['flash_error'] = 'Application not found'; header('Location: /apply/step/1'); exit; }

        if (empty($application['date_of_birth']) || empty($application['phone']) || empty($application['address'])) {
            $_SESSION['flash_error'] = 'Please complete your application form first';
            header('Location: /apply/step/2'); exit;
        }

        if ($this->paymentModel->hasSuccessfulPayment($application['id'])) { header('Location: /apply/step/4'); exit; }

        if (!isset($_SESSION['jamb_verification']) && !empty($application['jamb_number'])) {
            $this->restoreJambSession($application);
        }

        $pending_payment = null;
        foreach ($this->paymentModel->getByApplicationId($application['id']) as $p) {
            if ($p['status'] === 'pending') { $pending_payment = $p; break; }
        }

        $applicant      = $this->applicantModel->find($applicantId);
        $applicant_name = trim(($application['first_name'] ?? '') . ' ' . ($application['last_name'] ?? '')) ?: ($applicant['email'] ?? 'Applicant');

        $this->data = array_merge($this->data, [
            'pageTitle'       => 'Payment - Step 3',
            'application'     => $application,
            'applicant'       => $applicant,
            'applicant_name'  => $applicant_name,
            'fee'             => $this->settingsModel->getApplicationFee(),
            'currency'        => $this->settingsModel->getCurrency(),
            'formatted_fee'   => $this->settingsModel->getFormattedFee(),
            'csrf_token'      => $this->generatePaymentCsrfToken(),
            'pending_payment' => $pending_payment,
            'baseUrl'         => defined('BASE_URL') ? BASE_URL : '/',
        ]);

        $this->render('applications/step3');
    }

    public function step4() {
        if (session_status() === PHP_SESSION_NONE) session_start();
        error_log("=== STEP 4 LOADED ===");

        if (!$this->isApplicantLoggedIn()) { $_SESSION['flash_error'] = 'Please login to continue'; header('Location: /applicant/login'); exit; }

        $applicantId = $_SESSION['applicant_id'];
        $application = $this->applicationModel->getByApplicantId($applicantId);

        if (!$application) { $_SESSION['flash_error'] = 'Application not found'; header('Location: /apply/step/1'); exit; }

        // Security layer 1: DB payment check
        if (!$this->paymentModel->hasSuccessfulPayment($application['id'])) {
            error_log("SECURITY: step4 attempt without payment - application {$application['id']}");
            $_SESSION['flash_error'] = 'Payment required. Please complete your payment first.';
            header('Location: /apply/step/3'); exit;
        }

        // Security layer 2: ownership check
        $validPayment = false;
        foreach ($this->paymentModel->getByApplicationId($application['id']) as $p) {
            if ($p['status'] === 'success' && $p['applicant_id'] == $applicantId) { $validPayment = true; break; }
        }

        if (!$validPayment) {
            error_log("SECURITY: step4 ownership mismatch - application {$application['id']}");
            $_SESSION['flash_error'] = 'Invalid payment record. Please contact support.';
            header('Location: /apply/step/3'); exit;
        }

        require_once MODELS_PATH . '/application/ExamSlipModel.php';
        $examSlipModel = new ExamSlipModel();
        $examSlip      = $examSlipModel->getByApplicationId($application['id']) ?? $this->generateExamSlip($application['id']);

        if (!$examSlip) {
            error_log("CRITICAL: Could not generate exam slip for application {$application['id']}");
            $_SESSION['flash_error'] = 'Error generating exam slip. Please contact support.';
            header('Location: /apply/step/3'); exit;
        }

        $this->data = array_merge($this->data, [
            'pageTitle'   => 'Examination Slip - Step 4',
            'application' => $application,
            'applicant'   => $this->applicantModel->find($applicantId),
            'exam_slip'   => $examSlip,
        ]);

        $this->render('applications/step4');
    }

    // =========================================================================
    // JAMB VERIFICATION (AJAX)
    // =========================================================================

    public function verifyJamb() {
        header('Content-Type: application/json');
        if (session_status() === PHP_SESSION_NONE) session_start();

        if (!isset($_SESSION['applicant_id']))    { echo json_encode(['success' => false, 'message' => 'Please login first']);          return; }
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') { echo json_encode(['success' => false, 'message' => 'Invalid request method']);      return; }
        if (!$this->validateCsrfToken())           { echo json_encode(['success' => false, 'message' => 'Security token expired']);      return; }

        $jambNumber = strtoupper(trim($_POST['jamb_number'] ?? ''));

        if (empty($jambNumber) || !preg_match('/^[0-9A-Z]{10,14}$/', $jambNumber)) {
            echo json_encode(['success' => false, 'message' => 'Invalid JAMB number format']); return;
        }

        $jambCandidate = $this->jambModel->findByJambNumber($jambNumber);

        if (!$jambCandidate)            { echo json_encode(['success' => false, 'message' => 'JAMB number not found in our records']); return; }
        if ($jambCandidate['is_used'])  { echo json_encode(['success' => false, 'message' => 'This JAMB number has already been used']); return; }

        $minScore = $this->settingsModel->get('min_utme_score', 170);
        if ($jambCandidate['aggregate_score'] < $minScore) {
            echo json_encode(['success' => false, 'message' => "Your score of {$jambCandidate['aggregate_score']} is below the minimum requirement of {$minScore}"]); return;
        }

        $applicantId         = $_SESSION['applicant_id'];
        $existingApplication = $this->applicationModel->getByApplicantId($applicantId);

        $jambData = [
            'jamb_number'       => $jambCandidate['jamb_number'],
            'jamb_candidate_id' => $jambCandidate['id'],
            'first_name'        => $jambCandidate['first_name'],
            'last_name'         => $jambCandidate['last_name'],
            'other_names'       => $jambCandidate['other_names'],
            'gender'            => $jambCandidate['gender'],
            'state_of_origin'   => $jambCandidate['state_of_origin'],
            'lga'               => $jambCandidate['lga'],
            'utme_score'        => $jambCandidate['aggregate_score'],
        ];

        try {
            if (!$existingApplication) {
                $id = $this->applicationModel->createApplication($applicantId, array_merge($jambData, [
                    'applicant_id'     => $applicantId,
                    'program'          => 'ND Nursing',
                    'program_choice_1' => 'ND Nursing',
                    'application_step' => 2,
                    'status'           => 'pending',
                ]));
                if (!$id) throw new Exception("Failed to create application record");
                error_log("Created application ID: $id for applicant: $applicantId");
            } else {
                if (!$this->applicationModel->updateApplication($existingApplication['id'], array_merge($jambData, ['application_step' => 2, 'updated_at' => date('Y-m-d H:i:s')]))) {
                    throw new Exception("Failed to update application record");
                }
                error_log("Updated application ID: {$existingApplication['id']} with JAMB data");
            }
        } catch (Exception $e) {
            error_log("verifyJamb error: " . $e->getMessage());
            echo json_encode(['success' => false, 'message' => $e->getMessage()]); return;
        }

        $_SESSION['jamb_verification'] = [
            'id'             => $jambCandidate['id'],
            'jamb_number'    => $jambCandidate['jamb_number'],
            'first_name'     => $jambCandidate['first_name'],
            'last_name'      => $jambCandidate['last_name'],
            'other_names'    => $jambCandidate['other_names'],
            'gender'         => $jambCandidate['gender'],
            'state_of_origin'=> $jambCandidate['state_of_origin'],
            'lga'            => $jambCandidate['lga'],
            'score'          => $jambCandidate['aggregate_score'],
        ];

        $this->jambModel->markAsUsed($jambCandidate['id'], $applicantId);

        echo json_encode([
            'success' => true,
            'data'    => [
                'jamb_number'    => $jambCandidate['jamb_number'],
                'name'           => $jambCandidate['first_name'] . ' ' . $jambCandidate['last_name'],
                'first_name'     => $jambCandidate['first_name'],
                'last_name'      => $jambCandidate['last_name'],
                'other_names'    => $jambCandidate['other_names'],
                'gender'         => $jambCandidate['gender'],
                'state_of_origin'=> $jambCandidate['state_of_origin'],
                'lga'            => $jambCandidate['lga'],
                'score'          => $jambCandidate['aggregate_score'],
            ],
        ]);
        exit;
    }

    // =========================================================================
    // PRIVATE HELPERS
    // =========================================================================

    /**
     * Restore JAMB data from an application record into $_SESSION['jamb_verification']
     */
    private function restoreJambSession(array $application): void {
        $_SESSION['jamb_verification'] = [
            'jamb_number'    => $application['jamb_number'],
            'first_name'     => $application['first_name'],
            'last_name'      => $application['last_name'],
            'other_names'    => $application['other_names'],
            'gender'         => $application['gender'],
            'state_of_origin'=> $application['state_of_origin'],
            'lga'            => $application['lga'],
            'score'          => $application['utme_score'],
        ];
    }

    /**
     * Log in an applicant and redirect, creating a blank application record if needed.
     * Used by verifyEmail() to remove duplicated login blocks.
     */
    private function autoLoginAndRedirect(array $applicant, string $redirectUrl): void {
        $_SESSION['applicant_id']         = $applicant['id'];
        $_SESSION['applicant_email']      = $applicant['email'];
        $_SESSION['applicant_name']       = trim(($applicant['first_name'] ?? '') . ' ' . ($applicant['last_name'] ?? ''));
        $_SESSION['applicant_login_time'] = time();

        unset($_SESSION['registration_email']);

        if (!$this->applicationModel->getByApplicantId($applicant['id'])) {
            $this->applicationModel->createEmptyApplication($applicant['id']);
        }

        header('Location: ' . $redirectUrl);
        exit;
    }

    /**
     * Generate and store a CSRF token in the payment-specific token array.
     * Prunes tokens older than 1 hour to avoid session bloat.
     */
    private function generatePaymentCsrfToken(): string {
        $token = bin2hex(random_bytes(32));

        if (!isset($_SESSION['csrf_tokens'])) $_SESSION['csrf_tokens'] = [];

        // Prune old tokens
        $now = time();
        foreach ($_SESSION['csrf_tokens'] as $t => $ts) {
            if ($now - $ts > 3600) unset($_SESSION['csrf_tokens'][$t]);
        }

        $_SESSION['csrf_tokens'][$token] = $now;
        $_SESSION['current_csrf_token']  = $token;

        return $token;
    }

    /**
     * Validate a payment CSRF token.
     * Checks the payment token array first, then falls back to the base controller's simple token.
     */
    private function validatePaymentCsrfToken(string $token): bool {
        // Check keyed payment token array
        if (isset($_SESSION['csrf_tokens'][$token])) {
            if (time() - $_SESSION['csrf_tokens'][$token] <= 3600) {
                unset($_SESSION['csrf_tokens'][$token]);
                return true;
            }
            unset($_SESSION['csrf_tokens'][$token]);
            return false;
        }

        // Fall back to base controller simple token
        if (!empty($_SESSION['csrf_token']) && $_SESSION['csrf_token'] === $token) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32)); // rotate
            return true;
        }

        return false;
    }

    /**
     * Determine the step URL an applicant should land on based on their application state.
     */
    private function getRedirectStepUrl(array $application): string {
        if (empty($application['jamb_number']))     return '/apply/step/1';

        if (empty($application['date_of_birth']) ||
            empty($application['phone'])          ||
            empty($application['address'])        ||
            empty($application['program_choice_1'])) {
            return '/apply/step/2';
        }

        if ($application['application_step'] >= 4) {
            return $this->paymentModel->hasSuccessfulPayment($application['id'])
                ? '/apply/step/4'
                : '/apply/step/3';
        }

        return '/apply/step/3';
    }

    // =========================================================================
    // PROTECTED HELPERS
    // =========================================================================

    protected function isApplicantLoggedIn(): bool {
        return !empty($_SESSION['applicant_id']);
    }

    protected function getApplication(): ?array {
        return $this->isApplicantLoggedIn()
            ? $this->applicationModel->getByApplicantId($_SESSION['applicant_id'])
            : null;
    }

    protected function getApplicant(): ?array {
        return $this->isApplicantLoggedIn()
            ? $this->applicantModel->find($_SESSION['applicant_id'])
            : null;
    }

    private function generateRandomPassword(int $length = 10): string {
        $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*()';
        return substr(str_shuffle($chars), 0, $length);
    }

    protected function getStates(): array {
        return [
            'Abia','Adamawa','Akwa Ibom','Anambra','Bauchi','Bayelsa','Benue',
            'Borno','Cross River','Delta','Ebonyi','Edo','Ekiti','Enugu',
            'FCT - Abuja','Gombe','Imo','Jigawa','Kaduna','Kano','Katsina',
            'Kebbi','Kogi','Kwara','Lagos','Nasarawa','Niger','Ogun','Ondo',
            'Osun','Oyo','Plateau','Rivers','Sokoto','Taraba','Yobe','Zamfara',
        ];
    }

    protected function getPrograms(): array {
        return ['ND Nursing', 'Post Basic Nursing', 'Midwifery', 'Public Health Nursing'];
    }
}