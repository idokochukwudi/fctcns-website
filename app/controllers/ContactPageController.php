<?php
/**
 * CONTACT PAGE CONTROLLER
 * File: /app/controllers/ContactPageController.php
 * 
 * Purpose: Manages all contact page functionality
 * - Displaying contact form
 * - Processing submissions
 * - Showing success page
 * 
 * This follows the Single Responsibility Principle:
 * ONE controller = ONE feature (Contact)
 */

class ContactPageController extends Controller {
    
    private $contactModel;
    
    /**
     * CONSTRUCTOR
     * Purpose: Initialize the controller and load required models
     * Runs automatically when class is instantiated
     */
    public function __construct() {
        // Call parent constructor (Controller.php)
        parent::__construct();
        
        // Use the main layout (header/footer)
        $this->layout = 'main';
        
        // Load the ContactModel
        require_once APP_PATH . '/models/ContactModel.php';
        $this->contactModel = new ContactModel();
    }
    
    /**
     * METHOD: index()
     * Route: GET /contact
     * Purpose: Display the contact form page
     * View: /app/views/pages/contact/contact.php
     */
    public function index() {
        try {
            // Get contact settings from database (phone, email, address)
            $contactSettings = $this->contactModel->getContactSettings();
            
            // FAQ data - hardcoded here because it's specific to contact page
            // In a larger app, this would come from a FAQ model
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
            
            // Prepare ALL data that the view will need
            $viewData = [
                'page_title' => 'Contact Us - FCT College of Nursing Sciences',
                'page_description' => 'Get in touch with our administration for inquiries about admissions, programs, and general information.',
                'currentPage' => 'contact',
                'csrf_token' => $this->csrfToken(),
                'contact_settings' => $contactSettings,
                'faqs' => $faqs,
                'baseUrl' => BASE_URL,
                // If there were previous form errors, pass them to the view
                'form_data' => $_SESSION['contact_form_data'] ?? [],
                'errors' => $_SESSION['contact_errors'] ?? null
            ];
            
            // Clear session data so it doesn't show again on refresh
            unset($_SESSION['contact_errors'], $_SESSION['contact_form_data']);
            
            // Merge our data with any parent controller data
            $this->data = array_merge($this->data, $viewData);
            
            // RENDER THE VIEW
            // Note the path: pages/contact/contact (no .php extension)
            $this->render('pages/contact/contact');
            
        } catch (Exception $e) {
            // Log error for debugging
            error_log("ContactPageController index error: " . $e->getMessage());
            
            // Show friendly error to user
            $this->flash('error', 'Failed to load contact page');
            $this->render('pages/contact/contact', ['error' => 'Unable to load contact form']);
        }
    }
    
    /**
     * METHOD: submit()
     * Route: POST /contact/submit
     * Purpose: Process the contact form submission
     * Flow: Validate → Save → Redirect to success
     */
    public function submit() {
        // SECURITY: Only accept POST requests
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/contact');
            return;
        }
        
        try {
            // SECURITY: Validate CSRF token
            $this->validateCsrf();
            
            // ----- EXTRACT AND CLEAN FORM DATA -----
            $name = trim($this->input('name', ''));
            $email = trim($this->input('email', ''));
            $subject = trim($this->input('subject', ''));
            $message = trim($this->input('message', ''));
            $department = $this->input('department', 'general');
            $phone = trim($this->input('phone', ''));
            
            // ----- VALIDATION -----
            $errors = [];
            
            // Name validation
            if (empty($name)) {
                $errors[] = 'Full name is required';
            } elseif (strlen($name) < 2) {
                $errors[] = 'Name must be at least 2 characters';
            }
            
            // Email validation
            if (empty($email)) {
                $errors[] = 'Email address is required';
            } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $errors[] = 'Please enter a valid email address';
            }
            
            // Subject validation
            if (empty($subject)) {
                $errors[] = 'Subject is required';
            } elseif (strlen($subject) < 3) {
                $errors[] = 'Subject must be at least 3 characters';
            }
            
            // Message validation
            if (empty($message)) {
                $errors[] = 'Message is required';
            } elseif (strlen($message) < 10) {
                $errors[] = 'Message must be at least 10 characters';
            } elseif (strlen($message) > 5000) {
                $errors[] = 'Message is too long (maximum 5000 characters)';
            }
            
            // Phone validation (optional field)
            if (!empty($phone)) {
                $cleanPhone = preg_replace('/[^\d+]/', '', $phone);
                if (!preg_match('/^[\+]?[\d]{10,15}$/', $cleanPhone)) {
                    $errors[] = 'Please enter a valid phone number (10-15 digits)';
                }
            }
            
            // ----- IF VALIDATION FAILS -----
            if (!empty($errors)) {
                // Store errors and form data in session
                $_SESSION['contact_errors'] = $errors;
                $_SESSION['contact_form_data'] = $_POST;
                
                // Flash message for user feedback
                $this->flash('error', implode('. ', $errors));
                
                // Redirect back to form
                $this->redirect('/contact');
                return;
            }
            
            // ----- VALIDATION PASSED - SAVE TO DATABASE -----
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
            
            // Save to database
            $saved = $this->contactModel->saveSubmission($data);
            
            if ($saved) {
                // FIX: saveSubmission() now returns the ID directly — no second call needed
                $submissionId = $saved;
                
                // Store submission data in session for the success page
                $_SESSION['last_submission'] = [
                    'id' => $submissionId,
                    'name' => $name,
                    'email' => $email,
                    'department' => $department,
                    'subject' => $subject,
                    'timestamp' => date('Y-m-d H:i:s')
                ];
                
                // Clear any old form data
                unset($_SESSION['contact_form_data'], $_SESSION['contact_errors']);
                
                // ----- SUCCESS - REDIRECT TO BEAUTIFUL SUCCESS PAGE -----
                $this->redirect('/contact/success');
                
            } else {
                throw new Exception('Failed to save submission to database');
            }
            
        } catch (Exception $e) {
            // Log the error
            error_log("Contact submission error: " . $e->getMessage());
            
            // User-friendly error message
            $_SESSION['contact_errors'] = ['An error occurred. Please try again later.'];
            $_SESSION['contact_form_data'] = $_POST;
            
            $this->redirect('/contact');
        }
    }
    
    /**
     * METHOD: success()
     * Route: GET /contact/success
     * Purpose: Display the thank you page after successful submission
     * View: /app/views/pages/contact/contact-success.php
     */
    public function success() {
        // Get submission data from session
        $submission = $_SESSION['last_submission'] ?? null;
        
        // SECURITY: If someone tries to access /contact/success directly
        // without submitting a form, redirect them to the contact page
        if (!$submission) {
            $this->redirect('/contact');
            return;
        }
        
        // Clear the session data AFTER retrieving it
        // This prevents showing the same success page twice
        unset($_SESSION['last_submission']);
        
        // Prepare data for the view
        $viewData = [
            'page_title' => 'Message Sent - FCT College of Nursing Sciences',
            'page_description' => 'Thank you for contacting us. Your message has been received.',
            'currentPage' => 'contact-success',
            'submission' => $submission,
            'baseUrl' => BASE_URL
        ];
        
        // Merge data and render the view
        $this->data = array_merge($this->data, $viewData);
        $this->render('pages/contact/contact-success');
    }
}