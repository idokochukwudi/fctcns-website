<?php
/**
 * Public Application Controller - Enhanced Multi-Step Version
 * Handles student applications from the public with multi-step process
 * 
 * @package FCT_CNS
 * @version 2.0
 */

class PublicApplicationController extends Controller {
    
    private $db;
    private $currentStep = 1;
    private $totalSteps = 4;
    private $sessionKey = 'application_data';
    
    /**
     * Constructor
     */
    public function __construct() {
        parent::__construct();
        
        // Set public layout
        $this->layout = 'main';
        
        // Setup database
        require_once __DIR__ . '/../config/database.php';
        $database = Database::getInstance();
        $this->db = $database->getConnection();
        
        // Initialize session for multi-step form
        $this->initApplicationSession();
        
        // Initialize common data
        $this->data = array_merge($this->data, [
            'baseUrl' => defined('BASE_URL') ? BASE_URL : '/fctcns-website',
            'currentPage' => 'apply',
            'page_title' => 'Online Application - FCT College of Nursing Sciences',
            'page_description' => 'Apply online for nursing programs at FCT College of Nursing Sciences'
        ]);
    }
    
    /**
     * Initialize application session
     */
    private function initApplicationSession() {
        if (!isset($_SESSION[$this->sessionKey])) {
            $_SESSION[$this->sessionKey] = [
                'current_step' => 1,
                'completed_steps' => [],
                'form_data' => [],
                'errors' => [],
                'started_at' => date('Y-m-d H:i:s')
            ];
        }
        
        $this->currentStep = $_SESSION[$this->sessionKey]['current_step'] ?? 1;
    }
    
    /**
     * Show application form (entry point - redirects to current step)
     */
    public function showApplicationForm() {
        $currentStep = $_SESSION[$this->sessionKey]['current_step'] ?? 1;
        $this->redirect('/apply/step/' . $currentStep);
    }
    
    /**
     * Show specific step
     */
    public function showStep($step = 1) {
        $step = intval($step);
        
        // Validate step number
        if ($step < 1 || $step > $this->totalSteps) {
            $step = 1;
        }
        
        // Check if previous steps are completed
        if ($step > 1 && !$this->isStepCompleted($step - 1)) {
            $this->redirect('/apply/step/' . ($step - 1));
            return;
        }
        
        // Update current step in session
        $_SESSION[$this->sessionKey]['current_step'] = $step;
        $this->currentStep = $step;
        
        // Get data for this step
        $viewData = $this->getStepData($step);
        
        // Set data for view
        $this->data = array_merge($this->data, $viewData, [
            'currentStep' => $step,
            'totalSteps' => $this->totalSteps,
            'progressPercentage' => (($step - 1) / $this->totalSteps) * 100,
            'csrf_token' => $this->csrfToken(),
            'formData' => $_SESSION[$this->sessionKey]['form_data'] ?? [],
            'errors' => $_SESSION[$this->sessionKey]['errors'] ?? []
        ]);
        
        // Clear errors for this step
        if (isset($_SESSION[$this->sessionKey]['errors'])) {
            unset($_SESSION[$this->sessionKey]['errors']);
        }
        
        // Render the step view
        $this->render('application/step' . $step);
    }
    
    /**
     * Process step submission
     */
    public function processStep($step) {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/apply/step/' . $step);
            return;
        }
        
        try {
            // Validate CSRF token
            $this->validateCsrf();
            
            // Process the step data
            $stepData = $this->processStepData($step, $_POST);
            
            // Store in session
            $_SESSION[$this->sessionKey]['form_data'] = array_merge(
                $_SESSION[$this->sessionKey]['form_data'] ?? [],
                $stepData
            );
            
            // Mark step as completed
            $_SESSION[$this->sessionKey]['completed_steps'][] = $step;
            
            // Determine next action
            if ($step < $this->totalSteps) {
                // Go to next step
                $nextStep = $step + 1;
                $_SESSION[$this->sessionKey]['current_step'] = $nextStep;
                $this->redirect('/apply/step/' . $nextStep);
            } else {
                // All steps completed, go to review/submit
                $this->redirect('/apply/review');
            }
            
        } catch (Exception $e) {
            // Store error in session
            $_SESSION[$this->sessionKey]['errors'] = [$e->getMessage()];
            error_log("Step $step error: " . $e->getMessage());
            
            // Redirect back to current step with error
            $this->redirect('/apply/step/' . $step);
        }
    }
    
    /**
     * Process step data with validation
     */
    private function processStepData($step, $data) {
        $processedData = [];
        
        switch ($step) {
            case 1: // Personal Information
                $processedData = $this->processStep1($data);
                break;
                
            case 2: // Educational Background
                $processedData = $this->processStep2($data);
                break;
                
            case 3: // Documents & Uploads
                $processedData = $this->processStep3($data);
                break;
                
            case 4: // Review & Payment
                $processedData = $this->processStep4($data);
                break;
                
            default:
                throw new Exception("Invalid step: $step");
        }
        
        return $processedData;
    }
    
    /**
     * Process Step 1: Personal Information
     */
    private function processStep1($data) {
        $errors = [];
        $processed = [];
        
        // Required fields for step 1
        $required = ['first_name', 'last_name', 'email', 'phone', 'date_of_birth', 'gender', 'state_of_origin', 'address'];
        
        foreach ($required as $field) {
            if (empty($data[$field])) {
                $errors[] = ucfirst(str_replace('_', ' ', $field)) . ' is required';
            }
        }
        
        // Validate email
        if (!empty($data['email']) && !filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'Please enter a valid email address';
        }
        
        // Validate phone
        if (!empty($data['phone']) && !preg_match('/^[0-9\-\+\s\(\)]{10,15}$/', $data['phone'])) {
            $errors[] = 'Please enter a valid phone number';
        }
        
        // Validate date of birth (must be at least 16 years old)
        if (!empty($data['date_of_birth'])) {
            $birthDate = new DateTime($data['date_of_birth']);
            $today = new DateTime();
            $age = $today->diff($birthDate)->y;
            
            if ($age < 16) {
                $errors[] = 'You must be at least 16 years old to apply';
            }
        }
        
        // Check if email already exists in database
        if (!empty($data['email'])) {
            try {
                $checkStmt = $this->db->prepare("SELECT id FROM applications WHERE email = ?");
                $checkStmt->execute([$data['email']]);
                if ($checkStmt->fetch()) {
                    $errors[] = 'An application with this email already exists';
                }
            } catch (Exception $e) {
                error_log("Email check error: " . $e->getMessage());
            }
        }
        
        // If there are errors, throw exception
        if (!empty($errors)) {
            throw new Exception(implode(', ', $errors));
        }
        
        // Sanitize and store data
        foreach ($data as $key => $value) {
            if (in_array($key, $required) || in_array($key, ['middle_name', 'lga', 'marital_status', 'religion'])) {
                $processed[$key] = $this->sanitizeInput($value);
            }
        }
        
        return $processed;
    }
    
    /**
     * Process Step 2: Educational Background
     */
    private function processStep2($data) {
        $errors = [];
        $processed = [];
        
        // Required fields for step 2
        $required = ['highest_qualification', 'secondary_school', 'graduation_year', 'program', 'entry_year'];
        
        foreach ($required as $field) {
            if (empty($data[$field])) {
                $errors[] = ucfirst(str_replace('_', ' ', $field)) . ' is required';
            }
        }
        
        // If there are errors, throw exception
        if (!empty($errors)) {
            throw new Exception(implode(', ', $errors));
        }
        
        // Sanitize and store data
        foreach ($data as $key => $value) {
            if (in_array($key, $required) || in_array($key, ['other_qualifications', 'jamb_reg_no', 'jamb_score'])) {
                $processed[$key] = $this->sanitizeInput($value);
            }
        }
        
        return $processed;
    }
    
    /**
     * Process Step 3: Documents & Uploads
     */
    private function processStep3($data) {
        $processed = [];
        
        // Handle file uploads
        $uploads = $this->handleFileUploads();
        if ($uploads) {
            $processed['uploads'] = $uploads;
        }
        
        // Process other document data
        if (!empty($data['personal_statement'])) {
            $processed['personal_statement'] = $this->sanitizeInput($data['personal_statement']);
            
            // Validate personal statement length
            if (strlen($processed['personal_statement']) < 100) {
                throw new Exception('Personal statement must be at least 100 characters');
            }
        }
        
        // Process referee information
        $refereeFields = ['referee1_name', 'referee1_phone', 'referee1_email', 
                         'referee2_name', 'referee2_phone', 'referee2_email'];
        
        foreach ($refereeFields as $field) {
            if (!empty($data[$field])) {
                $processed[$field] = $this->sanitizeInput($data[$field]);
            }
        }
        
        return $processed;
    }
    
    /**
     * Handle file uploads
     */
    private function handleFileUploads() {
        $uploads = [];
        
        // Define allowed file types
        $allowedImageTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif'];
        $allowedDocTypes = ['application/pdf', 'application/msword', 
                           'application/vnd.openxmlformats-officedocument.wordprocessingml.document'];
        
        // Maximum file size (2MB for images, 5MB for documents)
        $maxImageSize = 2 * 1024 * 1024; // 2MB
        $maxDocSize = 5 * 1024 * 1024; // 5MB
        
        // Create uploads directory if it doesn't exist
        $uploadDir = ROOT_PATH . '/public/uploads/applications/';
        if (!file_exists($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }
        
        // Handle passport photo upload
        if (isset($_FILES['passport_photo']) && $_FILES['passport_photo']['error'] === UPLOAD_ERR_OK) {
            $file = $_FILES['passport_photo'];
            
            // Validate file type
            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            $mimeType = finfo_file($finfo, $file['tmp_name']);
            finfo_close($finfo);
            
            if (!in_array($mimeType, $allowedImageTypes)) {
                throw new Exception('Passport photo must be a JPG, PNG, or GIF image');
            }
            
            // Validate file size
            if ($file['size'] > $maxImageSize) {
                throw new Exception('Passport photo must be less than 2MB');
            }
            
            // Generate unique filename
            $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
            $filename = 'passport_' . uniqid() . '.' . $extension;
            $filepath = $uploadDir . $filename;
            
            // Move uploaded file
            if (move_uploaded_file($file['tmp_name'], $filepath)) {
                $uploads['passport_photo'] = [
                    'filename' => $filename,
                    'original_name' => $file['name'],
                    'path' => '/uploads/applications/' . $filename,
                    'size' => $file['size'],
                    'type' => $mimeType
                ];
            } else {
                throw new Exception('Failed to upload passport photo');
            }
        }
        
        // Handle other document uploads
        $documentFields = ['waec_result', 'birth_certificate', 'local_gov_cert', 'medical_cert'];
        
        foreach ($documentFields as $field) {
            if (isset($_FILES[$field]) && $_FILES[$field]['error'] === UPLOAD_ERR_OK) {
                $file = $_FILES[$field];
                
                // Validate file type
                $finfo = finfo_open(FILEINFO_MIME_TYPE);
                $mimeType = finfo_file($finfo, $file['tmp_name']);
                finfo_close($finfo);
                
                if (!in_array($mimeType, $allowedDocTypes)) {
                    throw new Exception(ucfirst(str_replace('_', ' ', $field)) . ' must be a PDF or Word document');
                }
                
                // Validate file size
                if ($file['size'] > $maxDocSize) {
                    throw new Exception(ucfirst(str_replace('_', ' ', $field)) . ' must be less than 5MB');
                }
                
                // Generate unique filename
                $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
                $filename = $field . '_' . uniqid() . '.' . $extension;
                $filepath = $uploadDir . $filename;
                
                // Move uploaded file
                if (move_uploaded_file($file['tmp_name'], $filepath)) {
                    $uploads[$field] = [
                        'filename' => $filename,
                        'original_name' => $file['name'],
                        'path' => '/uploads/applications/' . $filename,
                        'size' => $file['size'],
                        'type' => $mimeType
                    ];
                } else {
                    throw new Exception('Failed to upload ' . str_replace('_', ' ', $field));
                }
            }
        }
        
        return $uploads;
    }
    
    /**
     * Process Step 4: Review & Payment
     */
    private function processStep4($data) {
        $processed = [];
        
        // Process payment method selection
        if (!empty($data['payment_method'])) {
            $processed['payment_method'] = $this->sanitizeInput($data['payment_method']);
        }
        
        // Process declaration agreement
        if (!empty($data['declaration_agreed'])) {
            $processed['declaration_agreed'] = true;
        } else {
            throw new Exception('You must agree to the declaration to continue');
        }
        
        return $processed;
    }
    
    /**
     * Get data for specific step
     */
    private function getStepData($step) {
        $data = [];
        
        switch ($step) {
            case 1: // Personal Information
                $data = [
                    'stepTitle' => 'Personal Information',
                    'stepDescription' => 'Tell us about yourself',
                    'programs' => $this->getProgramsList(),
                    'states' => $this->getNigerianStates(),
                    'qualifications' => $this->getQualificationList(),
                    'entryYears' => $this->getEntryYears()
                ];
                break;
                
            case 2: // Educational Background
                $data = [
                    'stepTitle' => 'Educational Background',
                    'stepDescription' => 'Tell us about your education'
                ];
                break;
                
            case 3: // Documents & Uploads
                $data = [
                    'stepTitle' => 'Documents & Uploads',
                    'stepDescription' => 'Upload required documents'
                ];
                break;
                
            case 4: // Review & Payment
                $data = [
                    'stepTitle' => 'Review & Payment',
                    'stepDescription' => 'Review your application and make payment',
                    'applicationSummary' => $this->getApplicationSummary(),
                    'paymentMethods' => $this->getPaymentMethods()
                ];
                break;
        }
        
        return $data;
    }
    
    /**
     * Get programs list
     */
    private function getProgramsList() {
        try {
            $stmt = $this->db->query("
                SELECT DISTINCT program FROM applications 
                WHERE program IS NOT NULL AND program != ''
                ORDER BY program
            ");
            $programs = $stmt->fetchAll(PDO::FETCH_COLUMN);
            
            if (empty($programs)) {
                $programs = [
                    'Basic Nursing',
                    'Post Basic Nursing', 
                    'National Diploma Nursing',
                    'Community Health Nursing',
                    'Psychiatric Nursing',
                    'Paediatric Nursing'
                ];
            }
            
            return $programs;
        } catch (Exception $e) {
            error_log("Get programs error: " . $e->getMessage());
            return ['Basic Nursing', 'Post Basic Nursing', 'National Diploma Nursing', 'Community Health Nursing'];
        }
    }
    
    /**
     * Get Nigerian states
     */
    private function getNigerianStates() {
        return [
            'Abia', 'Adamawa', 'Akwa Ibom', 'Anambra', 'Bauchi', 'Bayelsa',
            'Benue', 'Borno', 'Cross River', 'Delta', 'Ebonyi', 'Edo',
            'Ekiti', 'Enugu', 'FCT', 'Gombe', 'Imo', 'Jigawa',
            'Kaduna', 'Kano', 'Katsina', 'Kebbi', 'Kogi', 'Kwara',
            'Lagos', 'Nasarawa', 'Niger', 'Ogun', 'Ondo', 'Osun',
            'Oyo', 'Plateau', 'Rivers', 'Sokoto', 'Taraba', 'Yobe', 'Zamfara'
        ];
    }
    
    /**
     * Get qualification list
     */
    private function getQualificationList() {
        return [
            'SSCE/WASSCE',
            'NECO',
            'GCE O\'Level',
            'NABTEB',
            'Diploma',
            'Degree',
            'Other'
        ];
    }
    
    /**
     * Get entry years
     */
    private function getEntryYears() {
        $currentYear = date('Y');
        return [$currentYear, $currentYear + 1, $currentYear + 2];
    }
    
    /**
     * Get payment methods
     */
    private function getPaymentMethods() {
        return [
            'bank_transfer' => 'Bank Transfer',
            'online_payment' => 'Online Payment (Card)',
            'bank_deposit' => 'Bank Deposit'
        ];
    }
    
    /**
     * Get application summary
     */
    private function getApplicationSummary() {
        $formData = $_SESSION[$this->sessionKey]['form_data'] ?? [];
        $summary = [];
        
        // Personal Information
        if (!empty($formData['first_name']) || !empty($formData['last_name'])) {
            $summary['personal_info'] = [
                'Name' => trim($formData['first_name'] . ' ' . ($formData['middle_name'] ?? '') . ' ' . $formData['last_name']),
                'Email' => $formData['email'] ?? '',
                'Phone' => $formData['phone'] ?? '',
                'Date of Birth' => $formData['date_of_birth'] ?? '',
                'Gender' => $formData['gender'] ?? '',
                'State of Origin' => $formData['state_of_origin'] ?? ''
            ];
        }
        
        // Educational Background
        if (!empty($formData['program'])) {
            $summary['education'] = [
                'Program' => $formData['program'] ?? '',
                'Entry Year' => $formData['entry_year'] ?? '',
                'Highest Qualification' => $formData['highest_qualification'] ?? '',
                'Secondary School' => $formData['secondary_school'] ?? ''
            ];
        }
        
        // Documents
        if (!empty($formData['uploads'])) {
            $uploads = $formData['uploads'];
            $docSummary = [];
            
            foreach ($uploads as $key => $upload) {
                $docName = str_replace('_', ' ', ucfirst($key));
                $docSummary[$docName] = $upload['original_name'] ?? 'Uploaded';
            }
            
            $summary['documents'] = $docSummary;
        }
        
        return $summary;
    }
    
    /**
     * Check if step is completed
     */
    private function isStepCompleted($step) {
        $completedSteps = $_SESSION[$this->sessionKey]['completed_steps'] ?? [];
        return in_array($step, $completedSteps);
    }
    
    /**
     * Submit final application
     */
    public function submitApplication() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/apply');
            return;
        }
        
        try {
            // Validate CSRF token
            $this->validateCsrf();
            
            // Check if all steps are completed
            if (!$this->areAllStepsCompleted()) {
                throw new Exception('Please complete all application steps before submitting');
            }
            
            // Get all form data from session
            $formData = $_SESSION[$this->sessionKey]['form_data'] ?? [];
            
            if (empty($formData)) {
                throw new Exception('No application data found. Please start over.');
            }
            
            // Prepare application data for database
            $applicationData = $this->prepareApplicationData($formData);
            
            // Save to database
            $applicationId = $this->saveApplication($applicationData);
            
            // Generate reference number
            $referenceNumber = 'APP-' . date('Y') . '-' . str_pad($applicationId, 5, '0', STR_PAD_LEFT);
            
            // Update with reference number
            $this->updateReferenceNumber($applicationId, $referenceNumber);
            
            // Save upload information
            if (!empty($formData['uploads'])) {
                $this->saveUploads($applicationId, $formData['uploads']);
            }
            
            // Generate payment reference (for payment integration)
            $paymentReference = 'PAY-' . date('Ymd') . '-' . $applicationId;
            
            // Process payment (you can integrate payment gateway here)
            // $paymentResult = $this->processPayment($applicationId, $paymentReference);
            
            // Clear session data
            $this->clearApplicationSession();
            
            // Store success data in session for success page
            $_SESSION['application_success'] = [
                'application_id' => $applicationId,
                'reference_number' => $referenceNumber,
                'payment_reference' => $paymentReference,
                'name' => $formData['first_name'] . ' ' . $formData['last_name'],
                'email' => $formData['email'],
                'program' => $formData['program'],
                'amount' => 5000, // Application fee amount
                'payment_status' => 'pending' // You can update this after payment
            ];
            
            // Redirect to success page
            $this->redirect('/apply/success');
            
        } catch (Exception $e) {
            error_log("Application submission error: " . $e->getMessage());
            $_SESSION[$this->sessionKey]['errors'] = [$e->getMessage()];
            $this->redirect('/apply/step/' . $this->totalSteps);
        }
    }
    
    /**
     * Check if all steps are completed
     */
    private function areAllStepsCompleted() {
        $completedSteps = $_SESSION[$this->sessionKey]['completed_steps'] ?? [];
        
        for ($i = 1; $i <= $this->totalSteps; $i++) {
            if (!in_array($i, $completedSteps)) {
                return false;
            }
        }
        
        return true;
    }
    
    /**
     * Prepare application data for database
     */
    private function prepareApplicationData($formData) {
        $data = [
            'first_name' => $formData['first_name'] ?? '',
            'last_name' => $formData['last_name'] ?? '',
            'middle_name' => $formData['middle_name'] ?? '',
            'email' => $formData['email'] ?? '',
            'phone' => $formData['phone'] ?? '',
            'date_of_birth' => $formData['date_of_birth'] ?? '',
            'gender' => $formData['gender'] ?? '',
            'state_of_origin' => $formData['state_of_origin'] ?? '',
            'lga' => $formData['lga'] ?? '',
            'address' => $formData['address'] ?? '',
            'marital_status' => $formData['marital_status'] ?? '',
            'religion' => $formData['religion'] ?? '',
            'program' => $formData['program'] ?? '',
            'entry_year' => $formData['entry_year'] ?? date('Y'),
            'highest_qualification' => $formData['highest_qualification'] ?? '',
            'secondary_school' => $formData['secondary_school'] ?? '',
            'graduation_year' => $formData['graduation_year'] ?? '',
            'other_qualifications' => $formData['other_qualifications'] ?? '',
            'jamb_reg_no' => $formData['jamb_reg_no'] ?? '',
            'jamb_score' => $formData['jamb_score'] ?? '',
            'personal_statement' => $formData['personal_statement'] ?? '',
            'referee1_name' => $formData['referee1_name'] ?? '',
            'referee1_phone' => $formData['referee1_phone'] ?? '',
            'referee1_email' => $formData['referee1_email'] ?? '',
            'referee2_name' => $formData['referee2_name'] ?? '',
            'referee2_phone' => $formData['referee2_phone'] ?? '',
            'referee2_email' => $formData['referee2_email'] ?? '',
            'status' => 'pending',
            'payment_status' => 'pending',
            'payment_method' => $formData['payment_method'] ?? '',
            'ip_address' => $_SERVER['REMOTE_ADDR'] ?? '',
            'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? ''
        ];
        
        return $data;
    }
    
    /**
     * Save application to database
     */
    private function saveApplication($data) {
        try {
            // Prepare SQL (adjust based on your table structure)
            $sql = "
                INSERT INTO applications (
                    first_name, last_name, middle_name, email, phone, 
                    date_of_birth, gender, state_of_origin, lga, address,
                    marital_status, religion, program, entry_year,
                    highest_qualification, secondary_school, graduation_year,
                    other_qualifications, jamb_reg_no, jamb_score,
                    personal_statement, referee1_name, referee1_phone, referee1_email,
                    referee2_name, referee2_phone, referee2_email, status,
                    payment_status, payment_method, ip_address, user_agent,
                    created_at, updated_at
                ) VALUES (
                    :first_name, :last_name, :middle_name, :email, :phone,
                    :date_of_birth, :gender, :state_of_origin, :lga, :address,
                    :marital_status, :religion, :program, :entry_year,
                    :highest_qualification, :secondary_school, :graduation_year,
                    :other_qualifications, :jamb_reg_no, :jamb_score,
                    :personal_statement, :referee1_name, :referee1_phone, :referee1_email,
                    :referee2_name, :referee2_phone, :referee2_email, :status,
                    :payment_status, :payment_method, :ip_address, :user_agent,
                    NOW(), NOW()
                )
            ";
            
            $stmt = $this->db->prepare($sql);
            
            // Execute with parameters
            $stmt->execute($data);
            
            // Get the new application ID
            $applicationId = $this->db->lastInsertId();
            
            // Log the application
            $this->logApplicationAction($applicationId, 'submitted', 'Application submitted via multi-step form');
            
            return $applicationId;
            
        } catch (Exception $e) {
            error_log("Database save error: " . $e->getMessage());
            
            // Check for specific database errors
            if (strpos($e->getMessage(), 'Column not found') !== false) {
                throw new Exception('Database configuration error. Please contact administrator.');
            }
            
            throw new Exception('Failed to save application to database. Please try again.');
        }
    }
    
    /**
     * Update reference number
     */
    private function updateReferenceNumber($applicationId, $referenceNumber) {
        try {
            $stmt = $this->db->prepare("
                UPDATE applications 
                SET reference_number = ? 
                WHERE id = ?
            ");
            $stmt->execute([$referenceNumber, $applicationId]);
        } catch (Exception $e) {
            error_log("Update reference error: " . $e->getMessage());
        }
    }
    
    /**
     * Save uploads information
     */
    private function saveUploads($applicationId, $uploads) {
        try {
            foreach ($uploads as $type => $upload) {
                $stmt = $this->db->prepare("
                    INSERT INTO application_documents (
                        application_id, document_type, filename, original_name,
                        file_path, file_size, mime_type, created_at
                    ) VALUES (?, ?, ?, ?, ?, ?, ?, NOW())
                ");
                
                $stmt->execute([
                    $applicationId,
                    $type,
                    $upload['filename'],
                    $upload['original_name'],
                    $upload['path'],
                    $upload['size'],
                    $upload['type']
                ]);
            }
        } catch (Exception $e) {
            error_log("Save uploads error: " . $e->getMessage());
        }
    }
    
    /**
     * Show application success page
     */
    public function applicationSuccess() {
        // Check if success data exists in session
        if (!isset($_SESSION['application_success'])) {
            $this->redirect('/apply');
            return;
        }
        
        $successData = $_SESSION['application_success'];
        
        // Clear the session data so it doesn't show again on refresh
        $successDataCopy = $successData;
        unset($_SESSION['application_success']);
        
        $this->data = array_merge($this->data, [
            'reference_number' => $successDataCopy['reference_number'],
            'payment_reference' => $successDataCopy['payment_reference'],
            'name' => $successDataCopy['name'],
            'email' => $successDataCopy['email'],
            'program' => $successDataCopy['program'],
            'amount' => $successDataCopy['amount'],
            'payment_status' => $successDataCopy['payment_status'],
            'application_id' => $successDataCopy['application_id'],
            'page_title' => 'Application Submitted Successfully - FCT College of Nursing Sciences',
            'page_description' => 'Your application has been received successfully'
        ]);
        
        $this->render('application/success');
    }
    
    /**
     * Reset application (start over)
     */
    public function resetApplication() {
        // Clear session data
        $this->clearApplicationSession();
        
        // Redirect to first step
        $this->redirect('/apply/step/1');
    }
    
    /**
     * Clear application session
     */
    private function clearApplicationSession() {
        if (isset($_SESSION[$this->sessionKey])) {
            unset($_SESSION[$this->sessionKey]);
        }
    }
    
    /**
     * Log application action
     */
    private function logApplicationAction($application_id, $action, $description) {
        try {
            $ip_address = $_SERVER['REMOTE_ADDR'] ?? '';
            $user_agent = $_SERVER['HTTP_USER_AGENT'] ?? '';
            
            $stmt = $this->db->prepare("
                INSERT INTO application_logs (application_id, action, description, ip_address, user_agent, created_at)
                VALUES (?, ?, ?, ?, ?, NOW())
            ");
            $stmt->execute([$application_id, $action, $description, $ip_address, $user_agent]);
        } catch (Exception $e) {
            error_log("Failed to log application action: " . $e->getMessage());
        }
    }
    
    /**
     * Sanitize input data
     */
    private function sanitizeInput($data) {
        if (is_array($data)) {
            return array_map([$this, 'sanitizeInput'], $data);
        }
        return htmlspecialchars(trim($data), ENT_QUOTES, 'UTF-8');
    }
}