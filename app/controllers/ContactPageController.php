<?php
/**
 * CONTACT PAGE CONTROLLER
 * File: /app/controllers/ContactPageController.php
 * 
 * Purpose: Manages all contact page functionality
 * - Displaying contact form
 * - Processing submissions
 * - Showing success page
 */

class ContactPageController extends Controller {
    
    private $contactModel;
    
    /**
     * CONSTRUCTOR
     */
    public function __construct() {
        parent::__construct();
        $this->layout = 'main';
        
        require_once APP_PATH . '/models/ContactModel.php';
        $this->contactModel = new ContactModel();
    }
    
    /**
     * METHOD: index()
     * Route: GET /contact
     */
    public function index() {
        try {
            $contactSettings = $this->contactModel->getContactSettings();
            
            $faqs = [
                [
                    'question' => 'What programs does the college currently offer?',
                    'answer' => 'The college has transitioned to the collegial system. We no longer offer Basic Nursing or Basic Midwifery. Currently, we offer the ND/HND Nursing Programme (non-terminal). ND Nursing lasts 2 years, followed by HND Nursing for another 2 years, making a total of 4 years.'
                ],
                [
                    'question' => 'Does the college still offer Basic Nursing or Basic Midwifery?',
                    'answer' => 'No. We have fully transitioned to the collegial system and no longer offer Basic Nursing or Basic Midwifery programs. We now offer only the ND/HND Nursing Programme.'
                ],
                [
                    'question' => 'What are the admission requirements for the ND/HND Nursing Programme?',
                    'answer' => 'Candidates must: • Score a minimum of 170 in the current UTME • Select FCT College of Nursing Sciences, Gwagwalada as First Choice institution • Have at least 5 O\'Level credits (English Language, Mathematics, Biology, Chemistry, Physics) in not more than 2 sittings (WAEC/NECO/NABTEB) • Be 16 years of age or above'
                ],
                [
                    'question' => 'When is the application period?',
                    'answer' => 'Application periods vary each year. Please check the admissions page or official portal for current dates and deadlines.'
                ],
                [
                    'question' => 'How do I apply?',
                    'answer' => 'Applications are submitted online via the official portal. Visit our admissions page for the complete step-by-step application guide, portal access, and detailed instructions.'
                ],
                [
                    'question' => 'Is there accommodation on campus?',
                    'answer' => 'Limited hostel accommodation is available on a first-come, first-served basis.'
                ],
                [
                    'question' => 'Are there scholarship opportunities?',
                    'answer' => 'Yes, merit-based and need-based scholarships are available. Contact the admissions office for current opportunities.'
                ]
            ];
            
            // FIX: Properly format baseUrl WITHOUT trailing slash
            $baseUrl = defined('BASE_URL') ? rtrim(BASE_URL, '/') : '';
            
            $viewData = [
                'page_title' => 'Contact Us - FCT College of Nursing Sciences',
                'page_description' => 'Get in touch with our administration for inquiries about admissions, programs, and general information.',
                'currentPage' => 'contact',
                'csrf_token' => $this->csrfToken(),
                'contact_settings' => $contactSettings,
                'faqs' => $faqs,
                'baseUrl' => $baseUrl,
                'form_data' => $_SESSION['contact_form_data'] ?? [],
                'errors' => $_SESSION['contact_errors'] ?? null
            ];
            
            unset($_SESSION['contact_errors'], $_SESSION['contact_form_data']);
            
            $this->data = array_merge($this->data, $viewData);
            $this->render('pages/contact/contact');
            
        } catch (Exception $e) {
            error_log("ContactPageController index error: " . $e->getMessage());
            $this->flash('error', 'Failed to load contact page');
            $this->render('pages/contact/contact', ['error' => 'Unable to load contact form']);
        }
    }
    
    /**
     * METHOD: submit()
     * Route: POST /contact/submit
     */
    public function submit() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/contact');
            return;
        }
        
        try {
            $this->validateCsrf();
            
            $name = trim($this->input('name', ''));
            $email = trim($this->input('email', ''));
            $subject = trim($this->input('subject', ''));
            $message = trim($this->input('message', ''));
            $department = $this->input('department', 'general');
            $phone = trim($this->input('phone', ''));
            
            $errors = [];
            
            if (empty($name)) {
                $errors[] = 'Full name is required';
            } elseif (strlen($name) < 2) {
                $errors[] = 'Name must be at least 2 characters';
            }
            
            if (empty($email)) {
                $errors[] = 'Email address is required';
            } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $errors[] = 'Please enter a valid email address';
            }
            
            if (empty($subject)) {
                $errors[] = 'Subject is required';
            } elseif (strlen($subject) < 3) {
                $errors[] = 'Subject must be at least 3 characters';
            }
            
            if (empty($message)) {
                $errors[] = 'Message is required';
            } elseif (strlen($message) < 10) {
                $errors[] = 'Message must be at least 10 characters';
            } elseif (strlen($message) > 5000) {
                $errors[] = 'Message is too long (maximum 5000 characters)';
            }
            
            if (!empty($phone)) {
                $cleanPhone = preg_replace('/[^\d+]/', '', $phone);
                if (!preg_match('/^[\+]?[\d]{10,15}$/', $cleanPhone)) {
                    $errors[] = 'Please enter a valid phone number (10-15 digits)';
                }
            }
            
            if (!empty($errors)) {
                $_SESSION['contact_errors'] = $errors;
                $_SESSION['contact_form_data'] = $_POST;
                $this->flash('error', implode('. ', $errors));
                $this->redirect('/contact');
                return;
            }
            
            $data = [
                'name' => $name,
                'email' => $email,
                'phone' => $phone,
                'subject' => $subject,
                'message' => $message,
                'department' => $department,
                'ip_address' => $_SERVER['REMOTE_ADDR'] ?? null,
                'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? null,
                'submitted_at' => date('Y-m-d H:i:s')
            ];
            
            $saved = $this->contactModel->saveSubmission($data);
            
            if ($saved) {
                $submissionId = $saved;
                
                $_SESSION['last_submission'] = [
                    'id' => $submissionId,
                    'name' => $name,
                    'email' => $email,
                    'department' => $department,
                    'subject' => $subject,
                    'timestamp' => date('Y-m-d H:i:s')
                ];
                
                unset($_SESSION['contact_form_data'], $_SESSION['contact_errors']);
                $this->redirect('/contact/success');
            } else {
                throw new Exception('Failed to save submission to database');
            }
            
        } catch (Exception $e) {
            error_log("Contact submission error: " . $e->getMessage());
            $_SESSION['contact_errors'] = ['An error occurred. Please try again later.'];
            $_SESSION['contact_form_data'] = $_POST;
            $this->redirect('/contact');
        }
    }
    
    /**
     * METHOD: success()
     * Route: GET /contact/success
     */
    public function success() {
        $submission = $_SESSION['last_submission'] ?? null;
        
        if (!$submission) {
            $this->redirect('/contact');
            return;
        }
        
        unset($_SESSION['last_submission']);
        
        // FIX: Properly format baseUrl WITHOUT trailing slash
        $baseUrl = defined('BASE_URL') ? rtrim(BASE_URL, '/') : '';
        
        $viewData = [
            'page_title' => 'Message Sent - FCT College of Nursing Sciences',
            'page_description' => 'Thank you for contacting us. Your message has been received.',
            'currentPage' => 'contact-success',
            'submission' => $submission,
            'baseUrl' => $baseUrl
        ];
        
        $this->data = array_merge($this->data, $viewData);
        $this->render('pages/contact/contact-success');
    }
}