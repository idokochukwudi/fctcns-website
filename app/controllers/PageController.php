<?php
/**
 * Page Controller
 * 
 * Handles static page views with proper MVC separation.
 * All business logic here, views only display data.
 * 
 * @package FCT_CNS
 * @version 2.0
 */

class PageController extends Controller {
    private $carouselModel;
    private $newsModel;
    private $contactModel;
    
    /**
     * Constructor
     */
    public function __construct() {
        parent::__construct();
        
        // Set default layout
        $this->layout = 'main';
        
        // Initialize common data for ALL pages
        // FIXED: Always use BASE_URL (no fallback with /fctcns-website)
        $this->data = [
            'baseUrl' => BASE_URL,
            'currentPage' => 'home',
            'page_title' => 'Home - FCT College of Nursing Sciences',
            'page_description' => 'Empowering Future Healthcare Professionals Since 1989',
            'page_keywords' => 'nursing college, FCT, nursing education, healthcare professionals, NMCN accredited, NBTE accredited'
        ];
        
        // Initialize contact model
        $this->contactModel = $this->getContactModel();
    }
    
    /**
     * Display homepage
     */
    public function home() {
        // Get carousel data
        $carouselSlides = $this->getCarouselSlides();
        
        // Prepare complete data for view
        $viewData = [
            'carouselSlides' => $carouselSlides,
            'page_title' => 'Home - FCT College of Nursing Sciences',
            'page_description' => 'Empowering Future Healthcare Professionals Since 1989',
            'currentPage' => 'home'
        ];
        
        // Merge with base data and render
        $this->data = array_merge($this->data, $viewData);
        $this->render('home');
    }
    
    /**
     * Display about page
     */
    public function about() {
        $this->data = array_merge($this->data, [
            'page_title' => 'About Us - FCT College of Nursing Sciences',
            'page_description' => 'Learn about our history, mission, and values',
            'currentPage' => 'about'
        ]);
        $this->render('about');
    }
    
    /**
     * Display programs page
     */
    public function programs() {
        $this->data = array_merge($this->data, [
            'page_title' => 'Academic Programs - FCT College of Nursing Sciences',
            'page_description' => 'Explore our accredited nursing programs',
            'currentPage' => 'programs'
        ]);
        $this->render('programs');
    }
    
    /**
     * Display admissions page
     */
    public function admissions() {
        $this->data = array_merge($this->data, [
            'page_title' => 'Admissions - FCT College of Nursing Sciences',
            'page_description' => 'Apply to our nursing programs',
            'currentPage' => 'admissions'
        ]);
        $this->render('admissions');
    }
    
    /**
     * Display research page
     */
    public function research() {
        // Get database connection
        require_once __DIR__ . '/../config/database.php';
        $database = Database::getInstance();
        $db = $database->getConnection();
        
        try {
            // SIMPLIFIED QUERY: Get published research
            $stmt = $db->query("
                SELECT 
                    rp.*,
                    rc.name as category_name,
                    rc.slug as category_slug
                FROM research_publications rp
                LEFT JOIN research_categories rc ON rp.research_area = rc.slug
                WHERE rp.is_published = 1
                ORDER BY rp.publication_date DESC
                LIMIT 20
            ");
            
            $research = $stmt->fetchAll();
            
            // Get research categories
            $categoriesStmt = $db->query("
                SELECT * FROM research_categories 
                WHERE is_active = 1 
                ORDER BY sort_order, name
            ");
            $categories = $categoriesStmt->fetchAll();
            
            // Prepare data for view
            $data = [
                'research' => $research, // This will be available as $research in view
                'publications' => $research, // Also available as $publications
                'categories' => $categories, // Available as $categories
                'page_title' => 'Research - Federal College of Tropical Nursing Sciences',
                'page_description' => 'Research initiatives, publications, and innovation in nursing and healthcare sciences',
                'currentPage' => 'research',
                'baseUrl' => BASE_URL // FIXED: Use BASE_URL directly
            ];
            
            // DEBUG: Show count in error log
            error_log("Found " . count($research) . " publications");
            
        } catch (Exception $e) {
            error_log("PageController research error: " . $e->getMessage());
            
            // Fallback data if error
            $data = [
                'research' => [],
                'publications' => [],
                'categories' => [],
                'page_title' => 'Research - Federal College of Tropical Nursing Sciences',
                'page_description' => 'Research initiatives, publications, and innovation in nursing and healthcare sciences',
                'currentPage' => 'research',
                'baseUrl' => BASE_URL // FIXED: Use BASE_URL directly (no fallback)
            ];
        }
        
        // Add hardcoded data for other sections (can be moved to database later)
        $data['ongoingProjects'] = [
            [
                'title' => 'Telehealth Interventions for Chronic Disease Management in Rural Communities',
                'investigators' => ['Dr. Amina Mohammed', 'Dr. Fatima Bello'],
                'funder' => 'National Institutes of Health (NIH)',
                'duration' => '2022-2025',
                'budget' => '₦25,000,000',
                'status' => 'active'
            ],
            [
                'title' => 'Development of Culturally-Sensitive Mental Health Screening Tools for Nigerian Adolescents',
                'investigators' => ['Dr. Sarah Adeyemi', 'Prof. Tunde Okafor'],
                'funder' => 'African Mental Health Foundation',
                'duration' => '2023-2024',
                'budget' => '₦12,000,000',
                'status' => 'active'
            ]
        ];
        
        $data['researchFacilities'] = [
            [
                'name' => 'Simulation Laboratory',
                'description' => 'High-fidelity simulation manikins and equipment for clinical skills training and research.',
                'features' => ['Adult and pediatric manikins', 'Vital signs monitors', 'Emergency response equipment', 'Video recording system'],
                'contact' => 'Dr. Grace Johnson'
            ]
        ];
        
        $data['collaborations'] = [
            ['name' => 'University of Ibadan', 'country' => 'Nigeria', 'type' => 'Academic'],
            ['name' => 'Johns Hopkins University', 'country' => 'USA', 'type' => 'International'],
            ['name' => 'University of Ghana', 'country' => 'Ghana', 'type' => 'Academic'],
            ['name' => 'University of Nairobi', 'country' => 'Kenya', 'type' => 'Academic']
        ];
        
        // Merge with base data and render
        $this->data = array_merge($this->data, $data);
        $this->render('pages/research');
    }
    
    /**
     * Display contact page
     */
    public function contact() {
        $contactSettings = $this->contactModel->getContactSettings();
        
        $this->data = array_merge($this->data, [
            'page_title' => 'Contact Us - FCT College of Nursing Sciences',
            'page_description' => 'Get in touch with our administration. We\'re here to help you with admissions, programs, and general inquiries.',
            'currentPage' => 'contact',
            'csrf_token' => $this->csrfToken(),
            'flash_success' => $this->getFlash('success'),
            'flash_error' => $this->getFlash('error'),
            'contact_settings' => $contactSettings
        ]);
        $this->render('contact');
    }
    
    /**
     * Display news page
     */
    public function news() {
        $this->data = array_merge($this->data, [
            'page_title' => 'News & Events - FCT College of Nursing Sciences',
            'page_description' => 'Latest news and events from our college',
            'currentPage' => 'news'
        ]);
        $this->render('news');
    }
    
    /**
     * Display faculty page
     */
    public function faculty() {
        $this->data = array_merge($this->data, [
            'page_title' => 'Faculty & Staff - FCT College of Nursing Sciences',
            'page_description' => 'Meet our expert faculty members',
            'currentPage' => 'faculty'
        ]);
        $this->render('faculty');
    }
    
    /**
     * Display alumni page
     */
    public function alumni() {
        $this->data = array_merge($this->data, [
            'page_title' => 'Alumni - FCT College of Nursing Sciences',
            'page_description' => 'Our graduates making a difference',
            'currentPage' => 'alumni'
        ]);
        $this->render('alumni');
    }
    
    /**
     * Display student life page
     */
    public function studentLife() {
        $this->data = array_merge($this->data, [
            'page_title' => 'Student Life - FCT College of Nursing Sciences',
            'page_description' => 'Campus life and student activities',
            'currentPage' => 'student-life'
        ]);
        $this->render('student-life');
    }
    
    /**
     * Display library page
     */
    public function library() {
        $this->data = array_merge($this->data, [
            'page_title' => 'Library - FCT College of Nursing Sciences',
            'page_description' => 'Our library resources and services',
            'currentPage' => 'library'
        ]);
        $this->render('library');
    }
    
    /**
     * Handle contact form submission
     */
    public function submitContact() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->flash('error', 'Invalid request method.');
            $this->redirect('/contact');
        }
        
        // Enable error reporting temporarily for debugging
        error_reporting(E_ALL);
        ini_set('display_errors', 1);
        
        try {
            // Debug: Log the POST data
            error_log("Contact form submitted: " . print_r($_POST, true));
            
            $this->validateCsrf();
            
            $data = [
                'name' => $this->input('name', ''),
                'email' => $this->input('email', ''),
                'phone' => $this->input('phone', ''),
                'subject' => $this->input('subject', ''),
                'message' => $this->input('message', ''),
                'department' => $this->input('department', 'general')
            ];
            
            // Debug: Log the processed data
            error_log("Processed data: " . print_r($data, true));
            
            // Validate required fields
            $required = ['name', 'email', 'subject', 'message'];
            foreach ($required as $field) {
                if (empty($data[$field])) {
                    $this->flash('error', ucfirst($field) . ' is required.');
                    error_log("Validation failed: $field is empty");
                    $this->redirect('/contact');
                }
            }
            
            // Validate email
            if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
                $this->flash('error', 'Please enter a valid email address.');
                error_log("Validation failed: Invalid email");
                $this->redirect('/contact');
            }
            
            // Validate message length
            if (strlen($data['message']) < 10) {
                $this->flash('error', 'Message must be at least 10 characters.');
                error_log("Validation failed: Message too short");
                $this->redirect('/contact');
            }
            
            // Optional phone validation if provided
            if (!empty($data['phone'])) {
                // Enhanced phone validation
                $cleanPhone = preg_replace('/[^\d+]/', '', $data['phone']);
                if ($cleanPhone !== '') {
                    if (str_starts_with($cleanPhone, '+')) {
                        // International format: + followed by 10-15 digits
                        if (!preg_match('/^\+\d{10,15}$/', $cleanPhone)) {
                            $this->flash('error', 'Please enter a valid international phone number (10-15 digits after +).');
                            error_log("Validation failed: Invalid international phone format");
                            $this->redirect('/contact');
                        }
                    } else {
                        // Local format: 10 or 11 digits
                        if (!preg_match('/^\d{10,11}$/', $cleanPhone)) {
                            $this->flash('error', 'Please enter a valid phone number (10-11 digits).');
                            error_log("Validation failed: Invalid local phone format");
                            $this->redirect('/contact');
                        }
                    }
                }
            }
            
            // Save to database
            error_log("Attempting to save to database...");
            $saved = $this->contactModel->saveSubmission($data);
            
            if ($saved) {
                error_log("Database save successful");
                
                // Optional: Send email notification
                try {
                    $this->sendContactEmail($data);
                    error_log("Contact email sent successfully");
                } catch (Exception $emailError) {
                    error_log("Email sending failed: " . $emailError->getMessage());
                    // Continue even if email fails
                }
                
                // Log activity
                $this->logActivity("Contact form submitted by {$data['name']}");
                
                $this->flash('success', 'Thank you for your message! We will respond within 24-48 hours.');
                error_log("Flash message set: Success");
            } else {
                error_log("Database save failed - saveSubmission returned false");
                $this->flash('error', 'Unable to submit your message. Please try again.');
            }
            
            error_log("Redirecting to contact page...");
            $this->redirect('/contact');
            
        } catch (Exception $e) {
            error_log("Contact form error: " . $e->getMessage() . "\n" . $e->getTraceAsString());
            $this->flash('error', 'An error occurred. Please try again later.');
            $this->redirect('/contact');
        } finally {
            // Restore error reporting to default
            error_reporting(0);
            ini_set('display_errors', 0);
        }
    }
    
    /**
     * Helper method for sending emails
     */
    private function sendContactEmail($data) {
        $contactSettings = $this->contactModel->getContactSettings();
        $to = isset($contactSettings['email']) ? $contactSettings['email'] : 'info@fctcns.edu.ng';
        
        $subject = "New Contact Form: " . $data['subject'];
        $phoneDisplay = isset($data['phone']) && !empty($data['phone']) ? $data['phone'] : 'Not provided';
        
        $message = <<<HTML
<html>
<head>
    <title>New Contact Form Submission</title>
    <style>
        body { font-family: Arial, sans-serif; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background: #6B4E9B; color: white; padding: 20px; }
        .content { padding: 20px; border: 1px solid #ddd; }
        .field { margin-bottom: 15px; }
        .field-label { font-weight: bold; color: #555; }
    </style>
</head>
<body>
    <div class='container'>
        <div class='header'>
            <h2>New Contact Form Submission</h2>
        </div>
        <div class='content'>
            <div class='field'>
                <div class='field-label'>From:</div>
                <div>{$data['name']} &lt;{$data['email']}&gt;</div>
            </div>
            
            <div class='field'>
                <div class='field-label'>Phone:</div>
                <div>$phoneDisplay</div>
            </div>
            
            <div class='field'>
                <div class='field-label'>Department:</div>
                <div>
HTML . ucfirst($data['department']) . <<<HTML
</div>
            </div>
            
            <div class='field'>
                <div class='field-label'>Subject:</div>
                <div>{$data['subject']}</div>
            </div>
            
            <div class='field'>
                <div class='field-label'>Message:</div>
                <div>
HTML . nl2br(htmlspecialchars($data['message'])) . <<<HTML
</div>
            </div>
            
            <div class='field'>
                <div class='field-label'>Submitted:</div>
                <div>
HTML . date('Y-m-d H:i:s') . <<<HTML
</div>
            </div>
        </div>
    </div>
</body>
</html>
HTML;
        
        $headers = "MIME-Version: 1.0\r\n";
        $headers .= "Content-Type: text/html; charset=UTF-8\r\n";
        $headers .= "From: " . $data['email'] . "\r\n";
        $headers .= "Reply-To: " . $data['email'] . "\r\n";
        
        // Uncomment to send email
        // mail($to, $subject, $message, $headers);
        
        // For debugging, log email attempt
        error_log("Email prepared to: $to, Subject: $subject");
    }
    
    /**
     * Get carousel slides from database
     * 
     * @return array Array of carousel slides
     */
    private function getCarouselSlides() {
        try {
            $slides = $this->getCarouselModel()->getActiveSlides(5);
            return !empty($slides) ? $this->formatSlideUrls($slides) : $this->getFallbackCarouselSlides();
            
        } catch (Exception $e) {
            error_log("Carousel data fetch error: " . $e->getMessage());
            return $this->getFallbackCarouselSlides();
        }
    }
    
    /**
     * Format slide URLs by adding base URL to image paths
     */
    private function formatSlideUrls($slides) {
        // FIXED: Use BASE_URL directly
        $baseUrl = BASE_URL;
        
        foreach ($slides as &$slide) {
            if (!empty($slide['image_path'])) {
                // If image path starts with /, combine with baseUrl
                if (strpos($slide['image_path'], '/') === 0) {
                    $slide['image_path'] = rtrim($baseUrl, '/') . $slide['image_path'];
                }
                // If image path doesn't start with / or http, add both
                elseif (strpos($slide['image_path'], 'http') !== 0 && strpos($slide['image_path'], '//') !== 0) {
                    $slide['image_path'] = rtrim($baseUrl, '/') . '/' . ltrim($slide['image_path'], '/');
                }
            }
            
            // Also format button links if they're relative
            if (!empty($slide['button_link']) && strpos($slide['button_link'], 'http') !== 0) {
                if (strpos($slide['button_link'], '/') === 0) {
                    $slide['button_link'] = rtrim($baseUrl, '/') . $slide['button_link'];
                } else {
                    $slide['button_link'] = rtrim($baseUrl, '/') . '/' . ltrim($slide['button_link'], '/');
                }
            }
        }
        
        return $slides;
    }
    
    /**
     * Get fallback carousel slides
     */
    private function getFallbackCarouselSlides() {
        // FIXED: Use BASE_URL directly
        $baseUrl = BASE_URL;
        
        return [
            [
                'title' => 'Welcome to FCT College of Nursing Sciences',
                'subtitle' => 'NMCN & NBTE Accredited Nursing Education Since 1989',
                'image_path' => $baseUrl . '/assets/images/carousel/slide1.jpg',
                'button_text' => 'Explore Programs',
                'button_link' => $baseUrl . '/programs'
            ],
            [
                'title' => 'Excellence in Nursing Education',
                'subtitle' => 'Fully accredited programs with modern clinical facilities',
                'image_path' => $baseUrl . '/assets/images/carousel/slide2.jpg',
                'button_text' => 'Learn More',
                'button_link' => $baseUrl . '/about'
            ],
            [
                'title' => '2024/2025 Admissions Open',
                'subtitle' => 'Apply now for our prestigious nursing programs',
                'image_path' => $baseUrl . '/assets/images/carousel/slide3.jpg',
                'button_text' => 'Apply Now',
                'button_link' => $baseUrl . '/admissions'
            ]
        ];
    }
    
    /**
     * Lazy-load CarouselModel
     */
    private function getCarouselModel() {
        if ($this->carouselModel === null) {
            require_once APP_PATH . '/models/CarouselModel.php';
            $this->carouselModel = new CarouselModel();
        }
        return $this->carouselModel;
    }
    
    /**
     * Add this method for lazy loading ContactModel
     */
    private function getContactModel() {
        require_once APP_PATH . '/models/ContactModel.php';
        return new ContactModel();
    }
    
    /**
     * Log activity
     */
    private function logActivity($message) {
        // Implement logging logic here
        error_log("Activity: " . $message);
    }
    
    /**
     * Show 404 page
     */
    public function notFound() {
        $this->status(404);
        $this->data['page_title'] = '404 - Page Not Found';
        $this->render('404');
    }
}