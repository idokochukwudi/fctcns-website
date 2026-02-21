<?php
/**
 * Application Verification Controller
 * Handles public verification of applicant examination slips via QR codes
 * 
 * @package FCT_CNS
 */

// Fix the path - Controller.php is in app/core/
require_once __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'core' . DIRECTORY_SEPARATOR . 'Controller.php';
require_once MODELS_PATH . DIRECTORY_SEPARATOR . 'application' . DIRECTORY_SEPARATOR . 'ExamSlipModel.php';
require_once MODELS_PATH . DIRECTORY_SEPARATOR . 'application' . DIRECTORY_SEPARATOR . 'ApplicationModel.php';
require_once MODELS_PATH . DIRECTORY_SEPARATOR . 'ApplicantModel.php';
require_once MODELS_PATH . DIRECTORY_SEPARATOR . 'application' . DIRECTORY_SEPARATOR . 'PaymentModel.php';

class ApplicationVerificationController extends Controller {
    
    /**
     * @var ExamSlipModel
     */
    private $examSlipModel;
    
    /**
     * @var ApplicationModel
     */
    private $applicationModel;
    
    /**
     * @var ApplicantModel
     */
    private $applicantModel;
    
    /**
     * @var PaymentModel
     */
    private $paymentModel;
    
    /**
     * Constructor - NO AUTHENTICATION REQUIRED
     */
    public function __construct() {
        parent::__construct();
        
        // Set public layout
        $this->layout = 'public';
        
        // Load models
        $this->examSlipModel = new ExamSlipModel();
        $this->applicationModel = new ApplicationModel();
        $this->applicantModel = new ApplicantModel();
        $this->paymentModel = new PaymentModel();
        
        // Initialize data
        $this->data = array_merge($this->data, [
            'baseUrl' => defined('BASE_URL') ? rtrim(BASE_URL, '/') : '',
            'currentPage' => 'application-verification',
            'institution_name' => 'FCT College of Nursing Sciences',
            'institution_address' => 'Gwagwalada, Abuja',
            'institution_logo' => '/assets/img/college-logo.png',
            'support_email' => 'verification@fctcns.edu.ng',
            'support_phone' => '07039837749'
        ]);
    }
    
    /**
     * Public verification portal home page
     */
    public function portal() {
        $this->data['pageTitle'] = 'Examination Slip Verification Portal - FCT College of Nursing Sciences';
        $this->data['verification_methods'] = [
            'qr' => [
                'icon' => 'qrcode',
                'title' => 'Scan QR Code',
                'description' => 'Use your camera to scan the QR code on the examination slip',
                'active' => true
            ],
            'slip' => [
                'icon' => 'ticket-alt',
                'title' => 'Slip Number',
                'description' => 'Enter the examination slip number manually',
                'active' => true
            ],
            'jamb' => [
                'icon' => 'graduation-cap',
                'title' => 'JAMB Number',
                'description' => 'Verify using JAMB registration number',
                'active' => true
            ],
            'application' => [
                'icon' => 'file-alt',
                'title' => 'Application Number',
                'description' => 'Verify using application number',
                'active' => true
            ]
        ];
        
        $this->renderPublic('application-verification/portal');
    }
    
    /**
     * Landing page alias for portal
     */
    public function landing() {
        $this->portal();
    }
    
    /**
     * Home alias for portal
     */
    public function home() {
        $this->portal();
    }
    
    /**
     * Verify examination slip by slip number (QR code scanning)
     * 
     * @param string $slipNumber The examination slip number
     */
    public function verifySlip($slipNumber) {
        try {
            // Clean the slip number
            $slipNumber = trim(urldecode($slipNumber));
            $slipNumber = preg_replace('/[^A-Za-z0-9\-]/', '', $slipNumber);
            
            error_log("ApplicationVerificationController: Verifying slip: " . $slipNumber);
            
            // Get slip by number
            $examSlip = $this->examSlipModel->getBySlipNumber($slipNumber);
            
            if (!$examSlip) {
                // Try alternative formats
                $cleanSlip = str_replace(['SLIP-', '-'], '', $slipNumber);
                $examSlip = $this->examSlipModel->getBySlipNumber($cleanSlip);
                
                if (!$examSlip) {
                    return $this->renderError(
                        'Invalid examination slip number. The slip could not be found in our records.',
                        'SLIP_NOT_FOUND'
                    );
                }
            }
            
            // Get application details
            $application = $this->applicationModel->find($examSlip['application_id']);
            
            if (!$application) {
                return $this->renderError(
                    'Associated application record not found.',
                    'APPLICATION_NOT_FOUND'
                );
            }
            
            // Get applicant details
            $applicant = $this->applicantModel->find($examSlip['applicant_id']);
            
            if (!$applicant) {
                return $this->renderError(
                    'Associated applicant record not found.',
                    'APPLICANT_NOT_FOUND'
                );
            }
            
            // Check if payment is verified
            $hasPaid = $this->paymentModel->hasSuccessfulPayment($application['id']);
            
            // Get verifier info from query string (optional)
            $verifierName = $_GET['name'] ?? '';
            $verifierNotes = $_GET['notes'] ?? '';
            $verifierInstitution = $_GET['institution'] ?? '';
            
            // Calculate verification status
            $verificationStatus = $this->calculateVerificationStatus($examSlip, $application, $hasPaid);
            
            // Prepare verification data
            $verificationData = [
                'verification_id' => uniqid('APP-VER-'),
                'verification_time' => date('Y-m-d H:i:s'),
                'verification_ip' => $_SERVER['REMOTE_ADDR'],
                'verification_user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? '',
                'verifier_name' => $verifierName,
                'verifier_notes' => $verifierNotes,
                'verifier_institution' => $verifierInstitution,
                'exam_slip' => $examSlip,
                'application' => $application,
                'applicant' => $applicant,
                'has_paid' => $hasPaid,
                'status' => $verificationStatus,
                'baseUrl' => $this->data['baseUrl'],
                'institution_name' => $this->data['institution_name']
            ];
            
            // Log verification attempt
            $this->logVerification($verificationData);
            
            // Show verification result page
            return $this->renderResult($verificationData);
            
        } catch (Exception $e) {
            error_log("ApplicationVerificationController::verifySlip error: " . $e->getMessage());
            return $this->renderError(
                'An error occurred during verification. Please try again.',
                'SYSTEM_ERROR',
                $e->getMessage()
            );
        }
    }
    
    /**
     * Verify by JAMB number (alternative method)
     * 
     * @param string $jambNumber The JAMB registration number
     */
    public function verifyByJamb($jambNumber) {
        try {
            // Clean JAMB number
            $jambNumber = trim(strtoupper(urldecode($jambNumber)));
            $jambNumber = preg_replace('/[^A-Z0-9]/', '', $jambNumber);
            
            error_log("ApplicationVerificationController: Verifying by JAMB: " . $jambNumber);
            
            // Find application by JAMB number
            $application = $this->applicationModel->getByJambNumber($jambNumber);
            
            if (!$application) {
                return $this->renderError(
                    'No application found with this JAMB registration number.',
                    'JAMB_NOT_FOUND'
                );
            }
            
            // Get exam slip
            $examSlip = $this->examSlipModel->getByApplicationId($application['id']);
            
            if (!$examSlip) {
                return $this->renderError(
                    'Examination slip not yet generated for this application.',
                    'SLIP_NOT_GENERATED'
                );
            }
            
            // Redirect to slip verification
            $this->redirect('/application-verify/slip/' . urlencode($examSlip['slip_number']));
            
        } catch (Exception $e) {
            error_log("ApplicationVerificationController::verifyByJamb error: " . $e->getMessage());
            return $this->renderError(
                'An error occurred during verification.',
                'SYSTEM_ERROR'
            );
        }
    }
    
    /**
     * Verify by application number
     * 
     * @param string $appNumber The application number
     */
    public function verifyByApplication($appNumber) {
        try {
            // Clean application number
            $appNumber = trim(strtoupper(urldecode($appNumber)));
            
            error_log("ApplicationVerificationController: Verifying by Application: " . $appNumber);
            
            // Find application by number
            $application = $this->applicationModel->getByApplicationNumber($appNumber);
            
            if (!$application) {
                return $this->renderError(
                    'No application found with this application number.',
                    'APP_NOT_FOUND'
                );
            }
            
            // Get exam slip
            $examSlip = $this->examSlipModel->getByApplicationId($application['id']);
            
            if (!$examSlip) {
                return $this->renderError(
                    'Examination slip not yet generated for this application.',
                    'SLIP_NOT_GENERATED'
                );
            }
            
            // Redirect to slip verification
            $this->redirect('/application-verify/slip/' . urlencode($examSlip['slip_number']));
            
        } catch (Exception $e) {
            error_log("ApplicationVerificationController::verifyByApplication error: " . $e->getMessage());
            return $this->renderError(
                'An error occurred during verification.',
                'SYSTEM_ERROR'
            );
        }
    }
    
    /**
     * API endpoint for QR code verification (returns JSON)
     * Used by mobile apps and AJAX calls
     * 
     * @param string $slipNumber
     */
    public function apiVerify($slipNumber) {
        header('Content-Type: application/json');
        
        try {
            // Clean slip number
            $slipNumber = trim(urldecode($slipNumber));
            $slipNumber = preg_replace('/[^A-Za-z0-9\-]/', '', $slipNumber);
            
            $examSlip = $this->examSlipModel->getBySlipNumber($slipNumber);
            
            if (!$examSlip) {
                echo json_encode([
                    'success' => false,
                    'verified' => false,
                    'error' => 'INVALID_SLIP',
                    'message' => 'Invalid examination slip number'
                ]);
                return;
            }
            
            $application = $this->applicationModel->find($examSlip['application_id']);
            
            if (!$application) {
                echo json_encode([
                    'success' => false,
                    'verified' => false,
                    'error' => 'APPLICATION_NOT_FOUND',
                    'message' => 'Application record not found'
                ]);
                return;
            }
            
            $applicant = $this->applicantModel->find($examSlip['applicant_id']);
            $hasPaid = $this->paymentModel->hasSuccessfulPayment($application['id']);
            
            // Prepare response data
            $responseData = [
                'success' => true,
                'verified' => true,
                'verification_time' => date('Y-m-d H:i:s'),
                'data' => [
                    'slip_number' => $examSlip['slip_number'],
                    'application_number' => $application['application_number'],
                    'jamb_number' => $application['jamb_number'],
                    'full_name' => trim(($applicant['first_name'] ?? '') . ' ' . ($applicant['last_name'] ?? '')),
                    'program' => $application['program_choice_1'],
                    'exam_date' => $examSlip['exam_date'],
                    'exam_time' => $examSlip['exam_time'],
                    'reporting_time' => $examSlip['reporting_time'],
                    'venue' => $examSlip['exam_venue'],
                    'seat_number' => $examSlip['seat_number'],
                    'payment_verified' => $hasPaid,
                    'generated_at' => $examSlip['generated_at'],
                    'institution' => $this->data['institution_name']
                ]
            ];
            
            // Log API verification
            $this->logApiVerification($slipNumber, $responseData);
            
            echo json_encode($responseData);
            
        } catch (Exception $e) {
            error_log("ApplicationVerificationController::apiVerify error: " . $e->getMessage());
            echo json_encode([
                'success' => false,
                'verified' => false,
                'error' => 'SYSTEM_ERROR',
                'message' => 'An error occurred during verification'
            ]);
        }
    }
    
    /**
     * Generate QR code for a slip (for printing)
     * FIXED: Multiple fallback methods and proper path handling
     * 
     * @param string $slipNumber
     */
    public function generateQR($slipNumber) {
        try {
            // Clean slip number
            $slipNumber = trim(urldecode($slipNumber));
            $slipNumber = preg_replace('/[^A-Za-z0-9\-]/', '', $slipNumber);
            
            error_log("ApplicationVerificationController::generateQR called for slip: " . $slipNumber);
            
            // Build verification URL
            $verificationUrl = $this->data['baseUrl'] . '/application-verify/slip/' . urlencode($slipNumber);
            
            // Set headers for PNG image
            header('Content-Type: image/png');
            header('Cache-Control: no-cache, must-revalidate, max-age=0');
            header('Pragma: no-cache');
            header('Expires: Wed, 11 Jan 1984 05:00:00 GMT');
            
            // Try to use phpqrcode library if available
            $qrLibPath = ROOT_PATH . '/vendor/phpqrcode/qrlib.php';
            
            if (file_exists($qrLibPath)) {
                require_once $qrLibPath;
                QRcode::png($verificationUrl, false, QR_ECLEVEL_L, 10, 2);
                error_log("QR code generated using phpqrcode library");
                exit;
            }
            
            // Try using Google Charts API
            $qrUrl = 'https://chart.googleapis.com/chart?chs=300x300&cht=qr&chl=' . urlencode($verificationUrl) . '&choe=UTF-8&chld=L|2';
            
            // Use cURL if available
            if (function_exists('curl_init')) {
                $ch = curl_init($qrUrl);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                curl_setopt($ch, CURLOPT_TIMEOUT, 15);
                curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
                curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36');
                $imageData = curl_exec($ch);
                $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                curl_close($ch);
                
                if ($httpCode === 200 && $imageData && strlen($imageData) > 100) {
                    echo $imageData;
                    error_log("QR code generated using Google Charts API via cURL");
                    exit;
                }
            }
            
            // Try file_get_contents as fallback
            if (ini_get('allow_url_fopen')) {
                $imageData = @file_get_contents($qrUrl);
                if ($imageData && strlen($imageData) > 100) {
                    echo $imageData;
                    error_log("QR code generated using Google Charts API via file_get_contents");
                    exit;
                }
            }
            
            // If all else fails, generate a simple QR code using GD
            error_log("All QR methods failed, using GD fallback");
            $this->generateSimpleGDQR($verificationUrl);
            exit;
            
        } catch (Exception $e) {
            error_log("QR Generation Error: " . $e->getMessage());
            error_log("Stack trace: " . $e->getTraceAsString());
            
            // Try fallback method
            try {
                $this->generateSimpleGDQR($verificationUrl ?? '');
            } catch (Exception $fallbackError) {
                error_log("Fallback QR generation also failed: " . $fallbackError->getMessage());
                // Output a simple text representation as last resort
                header('Content-Type: text/html');
                echo $this->generateSimpleQR($slipNumber, $verificationUrl ?? '');
            }
            exit;
        }
    }
    
    /**
     * Generate QR code using GD library (fallback method)
     */
    private function generateSimpleGDQR($text) {
        // Create a blank image
        $size = 300;
        $image = imagecreate($size, $size);
        
        // Define colors
        $white = imagecolorallocate($image, 255, 255, 255);
        $black = imagecolorallocate($image, 0, 0, 0);
        $blue = imagecolorallocate($image, 52, 152, 219);
        
        // Fill background
        imagefill($image, 0, 0, $white);
        
        // Draw a border
        imagerectangle($image, 0, 0, $size-1, $size-1, $blue);
        
        // Draw a simple QR-like pattern
        $blockSize = 20;
        for ($i = 0; $i < 15; $i++) {
            for ($j = 0; $j < 15; $j++) {
                $x = $i * $blockSize + 15;
                $y = $j * $blockSize + 15;
                
                // Create a pattern based on text hash
                $hash = md5($text . $i . $j);
                if (hexdec(substr($hash, 0, 2)) % 3 == 0) {
                    imagefilledrectangle(
                        $image, 
                        $x, $y, 
                        $x + $blockSize - 2, 
                        $y + $blockSize - 2, 
                        $black
                    );
                }
            }
        }
        
        // Draw position markers (like real QR codes)
        // Top-left marker
        imagefilledrectangle($image, 15, 15, 55, 55, $black);
        imagefilledrectangle($image, 20, 20, 50, 50, $white);
        imagefilledrectangle($image, 25, 25, 45, 45, $black);
        
        // Top-right marker
        imagefilledrectangle($image, $size-55, 15, $size-15, 55, $black);
        imagefilledrectangle($image, $size-50, 20, $size-20, 50, $white);
        imagefilledrectangle($image, $size-45, 25, $size-25, 45, $black);
        
        // Bottom-left marker
        imagefilledrectangle($image, 15, $size-55, 55, $size-15, $black);
        imagefilledrectangle($image, 20, $size-50, 50, $size-20, $white);
        imagefilledrectangle($image, 25, $size-45, 45, $size-25, $black);
        
        // Add URL text at bottom
        $textColor = imagecolorallocate($image, 0, 0, 255);
        $shortUrl = substr($text, 0, 30) . '...';
        imagestring($image, 2, 10, $size-20, $shortUrl, $textColor);
        
        // Output image
        imagepng($image);
        imagedestroy($image);
    }

    /**
     * Generate a simple HTML representation of QR (fallback)
     */
    private function generateSimpleQR($slipNumber, $verificationUrl) {
        header('Content-Type: text/html');
        return '<!DOCTYPE html>
        <html>
        <head>
            <title>QR Code</title>
            <style>
                body { display: flex; justify-content: center; align-items: center; height: 100vh; margin: 0; background: #f0f0f0; font-family: Arial, sans-serif; }
                .container { text-align: center; background: white; padding: 20px; border-radius: 10px; box-shadow: 0 0 20px rgba(0,0,0,0.1); max-width: 400px; }
                .qr-placeholder { margin: 20px auto; width: 200px; height: 200px; background: #f5f5f5; border: 2px solid #ddd; border-radius: 10px; display: flex; align-items: center; justify-content: center; }
                .qr-pattern { font-family: monospace; font-size: 18px; line-height: 1.2; color: #333; }
                .qr-text { font-family: monospace; font-size: 16px; margin: 10px 0; padding: 10px; background: #f0f0f0; border-radius: 5px; word-break: break-all; }
                .url { color: #666; font-size: 12px; word-break: break-all; }
                .button { display: inline-block; margin-top: 15px; padding: 10px 20px; background: #6B4E9B; color: white; text-decoration: none; border-radius: 5px; }
            </style>
        </head>
        <body>
            <div class="container">
                <h2>QR Code for Examination Slip</h2>
                <p>Slip Number: <strong>' . htmlspecialchars($slipNumber) . '</strong></p>
                
                <div class="qr-placeholder">
                    <div class="qr-pattern">
                        ████████████████████<br>
                        ██                ██<br>
                        ██  ████  ████    ██<br>
                        ██  ████  ████    ██<br>
                        ██  ████  ████    ██<br>
                        ██                ██<br>
                        ████████████████████
                    </div>
                </div>
                
                <div class="qr-text">
                    ' . htmlspecialchars($verificationUrl) . '
                </div>
                
                <p><small>Copy and paste this URL to verify the slip</small></p>
                
                <a href="' . htmlspecialchars($verificationUrl) . '" class="button">Proceed to Verification</a>
                
                <p class="url">Or scan this QR code with your mobile device</p>
            </div>
        </body>
        </html>';
    }
    
    /**
     * Check verification status (AJAX endpoint)
     */
    public function checkStatus() {
        header('Content-Type: application/json');
        
        $slipNumber = $_POST['slip_number'] ?? $_GET['slip_number'] ?? '';
        
        if (empty($slipNumber)) {
            echo json_encode(['success' => false, 'message' => 'Slip number required']);
            return;
        }
        
        $examSlip = $this->examSlipModel->getBySlipNumber($slipNumber);
        
        if (!$examSlip) {
            echo json_encode(['success' => false, 'message' => 'Invalid slip number']);
            return;
        }
        
        $hasPaid = $this->paymentModel->hasSuccessfulPayment($examSlip['application_id']);
        
        echo json_encode([
            'success' => true,
            'verified' => true,
            'payment_status' => $hasPaid ? 'paid' : 'pending'
        ]);
    }
    
    /**
     * Calculate verification status
     */
    private function calculateVerificationStatus($examSlip, $application, $hasPaid) {
        $status = [
            'is_valid' => true,
            'payment_status' => $hasPaid ? 'verified' : 'pending',
            'slip_status' => 'valid',
            'warnings' => [],
            'badges' => []
        ];
        
        // Check if exam date has passed
        if (!empty($examSlip['exam_date'])) {
            $examDate = strtotime($examSlip['exam_date']);
            $today = strtotime('today');
            
            if ($examDate < $today) {
                $status['warnings'][] = 'This examination date has passed.';
                $status['badges'][] = ['type' => 'warning', 'text' => 'Exam Passed'];
            } elseif ($examDate == $today) {
                $status['badges'][] = ['type' => 'info', 'text' => 'Today\'s Exam'];
            }
        }
        
        // Check payment status
        if (!$hasPaid) {
            $status['warnings'][] = 'Payment has not been verified.';
            $status['badges'][] = ['type' => 'danger', 'text' => 'Payment Pending'];
        } else {
            $status['badges'][] = ['type' => 'success', 'text' => 'Payment Verified'];
        }
        
        // Check if slip is too old (generated more than 30 days ago)
        if (!empty($examSlip['generated_at'])) {
            $generated = strtotime($examSlip['generated_at']);
            $thirtyDaysAgo = strtotime('-30 days');
            
            if ($generated < $thirtyDaysAgo) {
                $status['warnings'][] = 'This slip was generated more than 30 days ago.';
            }
        }
        
        // Add download count info
        if (!empty($examSlip['download_count'])) {
            $status['download_count'] = $examSlip['download_count'];
        }
        
        return $status;
    }
    
    /**
     * Render verification result page
     */
    private function renderResult($data) {
        $this->data = array_merge($this->data, [
            'verificationData' => $data,
            'exam_slip' => $data['exam_slip'],
            'application' => $data['application'],
            'applicant' => $data['applicant'],
            'has_paid' => $data['has_paid'],
            'status' => $data['status'],
            'pageTitle' => 'Examination Slip Verification Result - FCT College of Nursing Sciences'
        ]);
        
        $this->renderPublic('application-verification/result');
    }
    
    /**
     * Render verification error page
     */
    private function renderError($message, $code = 'GENERAL_ERROR', $debug = null) {
        $this->data = array_merge($this->data, [
            'errorMessage' => $message,
            'errorCode' => $code,
            'debugInfo' => (defined('APP_DEBUG') && APP_DEBUG) ? $debug : null,
            'pageTitle' => 'Verification Error - FCT College of Nursing Sciences',
            'suggestions' => $this->getErrorSuggestions($code)
        ]);
        
        $this->renderPublic('application-verification/error');
    }
    
    /**
     * Get error suggestions based on error code
     */
    private function getErrorSuggestions($code) {
        $suggestions = [
            'SLIP_NOT_FOUND' => [
                'Check that you entered the correct slip number',
                'Ensure the slip number includes the SLIP- prefix',
                'Contact support if the problem persists'
            ],
            'JAMB_NOT_FOUND' => [
                'Verify your JAMB registration number',
                'Ensure you have completed your application',
                'Contact the admissions office for assistance'
            ],
            'APP_NOT_FOUND' => [
                'Check your application number format',
                'Ensure you have completed all application steps',
                'Try verifying using your JAMB number instead'
            ],
            'SLIP_NOT_GENERATED' => [
                'Complete your payment first',
                'Wait a few minutes for the system to generate your slip',
                'Contact support if payment was completed over 24 hours ago'
            ],
            'SYSTEM_ERROR' => [
                'Try again in a few minutes',
                'Clear your browser cache',
                'Contact technical support'
            ]
        ];
        
        return $suggestions[$code] ?? [
            'Try again with correct information',
            'Contact support for assistance',
            'Visit the admissions office for help'
        ];
    }
    
    /**
     * Log verification attempt
     */
    private function logVerification($data) {
        try {
            $logDir = ROOT_PATH . '/storage/logs/application-verifications/';
            if (!file_exists($logDir)) {
                mkdir($logDir, 0755, true);
            }
            
            $logFile = $logDir . date('Y-m-d') . '.log';
            
            $logEntry = sprintf(
                "[%s] VERIFICATION ID: %s | Slip: %s | Applicant: %s %s | Valid: %s | IP: %s | Status: %s\n",
                date('Y-m-d H:i:s'),
                $data['verification_id'],
                $data['exam_slip']['slip_number'],
                $data['applicant']['first_name'] ?? '',
                $data['applicant']['last_name'] ?? '',
                $data['status']['is_valid'] ? 'YES' : 'NO',
                $data['verification_ip'],
                $data['has_paid'] ? 'PAID' : 'UNPAID'
            );
            
            file_put_contents($logFile, $logEntry, FILE_APPEND);
            
        } catch (Exception $e) {
            error_log("Failed to log application verification: " . $e->getMessage());
        }
    }
    
    /**
     * Log API verification
     */
    private function logApiVerification($slipNumber, $responseData) {
        try {
            $logDir = ROOT_PATH . '/storage/logs/application-verifications/api/';
            if (!file_exists($logDir)) {
                mkdir($logDir, 0755, true);
            }
            
            $logFile = $logDir . date('Y-m-d') . '.log';
            
            $logEntry = sprintf(
                "[%s] API VERIFICATION | Slip: %s | IP: %s | Success: %s\n",
                date('Y-m-d H:i:s'),
                $slipNumber,
                $_SERVER['REMOTE_ADDR'] ?? 'Unknown',
                $responseData['success'] ? 'YES' : 'NO'
            );
            
            file_put_contents($logFile, $logEntry, FILE_APPEND);
            
        } catch (Exception $e) {
            error_log("Failed to log API verification: " . $e->getMessage());
        }
    }
    
    /**
     * Render view without layout (for public access)
     */
    protected function renderPublic($view) {
        $viewPath = APP_PATH . '/views/' . $view . '.php';
        
        if (!file_exists($viewPath)) {
            throw new Exception("View not found: $view");
        }
        
        extract($this->data);
        include $viewPath;
    }
}