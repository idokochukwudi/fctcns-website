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
    private $db;
    
    /**
     * Constructor
     */
    public function __construct() {
        parent::__construct();
        
        // Set default layout
        $this->layout = 'main';
        
        // Initialize common data for ALL pages
        $this->data = [
            'baseUrl' => BASE_URL,
            'currentPage' => 'home',
            'page_title' => 'Home - FCT College of Nursing Sciences',
            'page_description' => 'Empowering Future Healthcare Professionals Since 1989',
            'page_keywords' => 'nursing college, FCT, nursing education, healthcare professionals, NMCN accredited, NBTE accredited'
        ];
        
        // Get database connection
        require_once APP_PATH . '/config/database.php';
        $database = Database::getInstance();
        $this->db = $database->getConnection();
        
        // Initialize models
        $this->newsModel = $this->getNewsModel();
    }
    
    /**
     * Display homepage
     */
    public function home() {
        try {
            // Get carousel data
            $carouselSlides = $this->getCarouselSlides();
            
            // Get news data for homepage
            $newsData = $this->getHomepageNews();
            
            // Get quick stats
            $quickStats = $this->getQuickStats();
            
            // Prepare complete data for view
            $viewData = [
                'carouselSlides' => $carouselSlides,
                'latestNews' => $newsData['latest'],
                'featuredNews' => $newsData['featured'],
                'upcomingEvents' => $newsData['events'],
                'quickStats' => $quickStats,
                'page_title' => 'Home - FCT College of Nursing Sciences',
                'page_description' => 'Empowering Future Healthcare Professionals Since 1989',
                'currentPage' => 'home'
            ];
            
            // Merge with base data and render
            $this->data = array_merge($this->data, $viewData);
            $this->render('home');
            
        } catch (Exception $e) {
            error_log("PageController home error: " . $e->getMessage());
            $this->renderError('Failed to load home page');
        }
    }
    
    /**
     * Display about page
     */
    public function about() {
        try {
            // Get leadership team data
            $leadership = $this->getLeadershipTeam();
            
            // Get accreditation data
            $accreditations = $this->getAccreditations();
            
            // Get history milestones
            $milestones = $this->getHistoryMilestones();
            
            $viewData = [
                'leadership' => $leadership,
                'accreditations' => $accreditations,
                'milestones' => $milestones,
                'page_title' => 'About Us - FCT College of Nursing Sciences',
                'page_description' => 'Learn about our history, mission, values, leadership team, and accreditations',
                'currentPage' => 'about'
            ];
            
            $this->data = array_merge($this->data, $viewData);
            $this->render('about');
            
        } catch (Exception $e) {
            error_log("PageController about error: " . $e->getMessage());
            $this->renderError('Failed to load about page');
        }
    }
    
    /**
     * Display programs page
     */
    public function programs() {
        try {
            // Get programs data
            $programs = $this->getProgramsData();
            
            // Get admission requirements
            $requirements = $this->getAdmissionRequirements();
            
            // Get career opportunities
            $careers = $this->getCareerOpportunities();
            
            $viewData = [
                'programs' => $programs,
                'requirements' => $requirements,
                'careers' => $careers,
                'page_title' => 'Academic Programs - FCT College of Nursing Sciences',
                'page_description' => 'Explore our accredited nursing programs including Basic Nursing, Post-Basic Nursing, and Midwifery',
                'currentPage' => 'programs'
            ];
            
            $this->data = array_merge($this->data, $viewData);
            $this->render('programs');
            
        } catch (Exception $e) {
            error_log("PageController programs error: " . $e->getMessage());
            $this->renderError('Failed to load programs page');
        }
    }
    
    /**
     * Display admissions page
     */
    public function admissions() {
        try {
            // Get admission data
            $admissionData = $this->getAdmissionData();
            
            // Get application deadlines
            $deadlines = $this->getApplicationDeadlines();
            
            // Get tuition information
            $tuition = $this->getTuitionInformation();
            
            $viewData = [
                'admissionData' => $admissionData,
                'deadlines' => $deadlines,
                'tuition' => $tuition,
                'page_title' => 'Admissions - FCT College of Nursing Sciences',
                'page_description' => 'Apply to our nursing programs. Check requirements, deadlines, and application process',
                'currentPage' => 'admissions'
            ];
            
            $this->data = array_merge($this->data, $viewData);
            $this->render('admissions');
            
        } catch (Exception $e) {
            error_log("PageController admissions error: " . $e->getMessage());
            $this->renderError('Failed to load admissions page');
        }
    }
    
    /**
     * Display research page
     */
    public function research() {
        try {
            // Get database connection
            require_once __DIR__ . '/../config/database.php';
            $database = Database::getInstance();
            $db = $database->getConnection();
            
            // Get published research
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
            $viewData = [
                'research' => $research,
                'publications' => $research,
                'categories' => $categories,
                'ongoingProjects' => $this->getOngoingProjects(),
                'researchFacilities' => $this->getResearchFacilities(),
                'collaborations' => $this->getResearchCollaborations(),
                'page_title' => 'Research - Federal College of Tropical Nursing Sciences',
                'page_description' => 'Research initiatives, publications, and innovation in nursing and healthcare sciences',
                'currentPage' => 'research'
            ];
            
            // DEBUG: Show count in error log
            error_log("Found " . count($research) . " publications");
            
            // Merge with base data and render
            $this->data = array_merge($this->data, $viewData);
            $this->render('pages/research');
            
        } catch (Exception $e) {
            error_log("PageController research error: " . $e->getMessage());
            $this->renderError('Failed to load research page');
        }
    }
    
    /**
     * Display news page (redirects to NewsController)
     */
    public function news() {
        // Redirect to NewsController
        $this->redirect('/news');
    }
    
    /**
     * Display faculty page
     */
    public function faculty() {
        try {
            // Get faculty data
            $faculty = $this->getFacultyData();
            
            // Get departments
            $departments = $this->getFacultyDepartments();
            
            $viewData = [
                'faculty' => $faculty,
                'departments' => $departments,
                'page_title' => 'Faculty & Staff - FCT College of Nursing Sciences',
                'page_description' => 'Meet our expert faculty members and dedicated staff',
                'currentPage' => 'faculty'
            ];
            
            $this->data = array_merge($this->data, $viewData);
            $this->render('faculty');
            
        } catch (Exception $e) {
            error_log("PageController faculty error: " . $e->getMessage());
            $this->renderError('Failed to load faculty page');
        }
    }
    
    /**
     * Display alumni page
     */
    public function alumni() {
        try {
            // Get alumni data
            $alumniData = $this->getAlumniData();
            
            // Get success stories
            $successStories = $this->getAlumniSuccessStories();
            
            // Get alumni events
            $events = $this->getAlumniEvents();
            
            $viewData = [
                'alumniData' => $alumniData,
                'successStories' => $successStories,
                'events' => $events,
                'page_title' => 'Alumni - FCT College of Nursing Sciences',
                'page_description' => 'Our graduates making a difference in healthcare worldwide',
                'currentPage' => 'alumni'
            ];
            
            $this->data = array_merge($this->data, $viewData);
            $this->render('alumni');
            
        } catch (Exception $e) {
            error_log("PageController alumni error: " . $e->getMessage());
            $this->renderError('Failed to load alumni page');
        }
    }
    
    /**
     * Display student life page
     */
    public function studentLife() {
        try {
            // Get student life data
            $studentLifeData = $this->getStudentLifeData();
            
            // Get clubs and organizations
            $clubs = $this->getStudentClubs();
            
            // Get campus facilities
            $facilities = $this->getCampusFacilities();
            
            $viewData = [
                'studentLifeData' => $studentLifeData,
                'clubs' => $clubs,
                'facilities' => $facilities,
                'page_title' => 'Student Life - FCT College of Nursing Sciences',
                'page_description' => 'Campus life, student activities, clubs, and facilities',
                'currentPage' => 'student-life'
            ];
            
            $this->data = array_merge($this->data, $viewData);
            $this->render('student-life');
            
        } catch (Exception $e) {
            error_log("PageController studentLife error: " . $e->getMessage());
            $this->renderError('Failed to load student life page');
        }
    }
    
    /**
     * Display library page
     */
    public function library() {
        try {
            // Get library data
            $libraryData = $this->getLibraryData();
            
            // Get online resources
            $onlineResources = $this->getLibraryResources();
            
            // Get opening hours
            $openingHours = $this->getLibraryHours();
            
            $viewData = [
                'libraryData' => $libraryData,
                'onlineResources' => $onlineResources,
                'openingHours' => $openingHours,
                'page_title' => 'Library - FCT College of Nursing Sciences',
                'page_description' => 'Our library resources, services, and online databases',
                'currentPage' => 'library'
            ];
            
            $this->data = array_merge($this->data, $viewData);
            $this->render('library');
            
        } catch (Exception $e) {
            error_log("PageController library error: " . $e->getMessage());
            $this->renderError('Failed to load library page');
        }
    }
    
    /**
     * Display privacy policy page
     */
    public function privacy() {
        $this->data['page_title'] = 'Privacy Policy - FCT College of Nursing Sciences';
        $this->data['currentPage'] = 'privacy';
        $this->render('pages/privacy');
    }
    
    /**
     * Display terms of service page
     */
    public function terms() {
        $this->data['page_title'] = 'Terms of Service - FCT College of Nursing Sciences';
        $this->data['currentPage'] = 'terms';
        $this->render('pages/terms');
    }
    
    /**
     * =========================================================================
     * HELPER METHODS
     * =========================================================================
     */
    
    /**
     * Get carousel slides from database
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
     * Get homepage news data - FIXED METHOD
     */
    private function getHomepageNews() {
        try {
            // Use direct SQL queries since NewsModel methods don't exist
            $latest = $this->getLatestNews(6);
            $featured = $this->getFeaturedNews(3);
            $events = $this->getUpcomingEvents(5);
            
            return [
                'latest' => $latest,
                'featured' => $featured,
                'events' => $events
            ];
            
        } catch (Exception $e) {
            error_log("Homepage news error: " . $e->getMessage());
            return [
                'latest' => [],
                'featured' => [],
                'events' => []
            ];
        }
    }
    
    /**
     * Get latest news articles (replacement for getLatest)
     */
    private function getLatestNews($limit = 6) {
        try {
            $sql = "SELECT n.*, u.username as author_name 
                    FROM news n 
                    LEFT JOIN users u ON n.author_id = u.id 
                    WHERE n.is_published = 1 AND n.type = 'news'
                    ORDER BY n.created_at DESC 
                    LIMIT ?";
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$limit]);
            return $stmt->fetchAll();
            
        } catch (Exception $e) {
            error_log("Error getting latest news: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Get featured news articles (replacement for getFeatured)
     */
    private function getFeaturedNews($limit = 3) {
        try {
            $sql = "SELECT n.*, u.username as author_name 
                    FROM news n 
                    LEFT JOIN users u ON n.author_id = u.id 
                    WHERE n.is_published = 1 AND n.is_featured = 1 AND n.type = 'news'
                    ORDER BY n.created_at DESC 
                    LIMIT ?";
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$limit]);
            return $stmt->fetchAll();
            
        } catch (Exception $e) {
            error_log("Error getting featured news: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Get upcoming events (replacement for getUpcomingEvents)
     */
    private function getUpcomingEvents($limit = 5) {
        try {
            $sql = "SELECT n.*, u.username as author_name 
                    FROM news n 
                    LEFT JOIN users u ON n.author_id = u.id 
                    WHERE n.is_published = 1 AND n.type = 'event'
                    AND (n.event_date IS NULL OR n.event_date >= CURDATE())
                    ORDER BY n.event_date ASC, n.created_at DESC 
                    LIMIT ?";
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$limit]);
            return $stmt->fetchAll();
            
        } catch (Exception $e) {
            error_log("Error getting upcoming events: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Get quick stats for homepage
     */
    private function getQuickStats() {
        return [
            ['number' => '35+', 'label' => 'Years Experience'],
            ['number' => '5,000+', 'label' => 'Graduates'],
            ['number' => '98%', 'label' => 'Pass Rate'],
            ['number' => '200+', 'label' => 'Clinical Partners']
        ];
    }
    
    /**
     * Get leadership team data
     */
    private function getLeadershipTeam() {
        return [
            [
                'name' => 'Dr. Amina Mohammed',
                'title' => 'Principal',
                'qualification' => 'PhD in Nursing Education',
                'experience' => '25 years',
                'image' => BASE_URL . '/assets/images/leadership/principal.jpg'
            ],
            [
                'name' => 'Prof. Tunde Okafor',
                'title' => 'Vice Principal Academics',
                'qualification' => 'Professor of Nursing',
                'experience' => '30 years',
                'image' => BASE_URL . '/assets/images/leadership/vp-academics.jpg'
            ]
        ];
    }
    
    /**
     * Get accreditation data
     */
    private function getAccreditations() {
        return [
            ['name' => 'Nursing and Midwifery Council of Nigeria (NMCN)', 'logo' => BASE_URL . '/assets/images/accreditations/nmcn.png'],
            ['name' => 'National Board for Technical Education (NBTE)', 'logo' => BASE_URL . '/assets/images/accreditations/nbte.png'],
            ['name' => 'West African Health Organization (WAHO)', 'logo' => BASE_URL . '/assets/images/accreditations/waho.png']
        ];
    }
    
    /**
     * Get history milestones
     */
    private function getHistoryMilestones() {
        return [
            ['year' => '1989', 'event' => 'College Established'],
            ['year' => '1995', 'event' => 'First Accreditation by NMCN'],
            ['year' => '2005', 'event' => 'NBTE Full Accreditation'],
            ['year' => '2015', 'event' => 'New Campus Completed'],
            ['year' => '2020', 'event' => 'ISO 9001:2015 Certified']
        ];
    }
    
    /**
     * Get programs data
     */
    private function getProgramsData() {
        return [
            [
                'name' => 'Basic Nursing',
                'duration' => '3 Years',
                'qualification' => 'Registered Nurse (RN)',
                'requirements' => ['5 O\'Level Credits', 'Mathematics & English', 'Science Subjects'],
                'description' => 'Comprehensive nursing education program'
            ],
            [
                'name' => 'Post-Basic Nursing',
                'duration' => '18 Months',
                'qualification' => 'Registered Nurse (RN) with Specialization',
                'requirements' => ['RN Certificate', '1 Year Experience', 'Valid Practicing License'],
                'description' => 'Advanced nursing specialization'
            ],
            [
                'name' => 'Midwifery',
                'duration' => '3 Years',
                'qualification' => 'Registered Midwife (RM)',
                'requirements' => ['5 O\'Level Credits', 'Mathematics & English', 'Science Subjects'],
                'description' => 'Maternal and child healthcare'
            ]
        ];
    }
    
    /**
     * Get ongoing research projects
     */
    private function getOngoingProjects() {
        return [
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
    }
    
    /**
     * Get research facilities
     */
    private function getResearchFacilities() {
        return [
            [
                'name' => 'Simulation Laboratory',
                'description' => 'High-fidelity simulation manikins and equipment for clinical skills training and research.',
                'features' => ['Adult and pediatric manikins', 'Vital signs monitors', 'Emergency response equipment', 'Video recording system'],
                'contact' => 'Dr. Grace Johnson'
            ]
        ];
    }
    
    /**
     * Get research collaborations
     */
    private function getResearchCollaborations() {
        return [
            ['name' => 'University of Ibadan', 'country' => 'Nigeria', 'type' => 'Academic'],
            ['name' => 'Johns Hopkins University', 'country' => 'USA', 'type' => 'International'],
            ['name' => 'University of Ghana', 'country' => 'Ghana', 'type' => 'Academic'],
            ['name' => 'University of Nairobi', 'country' => 'Kenya', 'type' => 'Academic']
        ];
    }
    
    /**
     * Format slide URLs by adding base URL to image paths
     */
    private function formatSlideUrls($slides) {
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
     * Lazy-load NewsModel
     */
    private function getNewsModel() {
        if ($this->newsModel === null) {
            require_once APP_PATH . '/models/NewsModel.php';
            $this->newsModel = new NewsModel($this->db);
        }
        return $this->newsModel;
    }
    
    /**
     * Log activity
     */
    private function logActivity($message) {
        // Implement logging logic here
        error_log("Activity: " . $message);
    }
    
    /**
     * Get data methods for other pages (stubs - implement as needed)
     */
    private function getAdmissionRequirements() { return []; }
    private function getCareerOpportunities() { return []; }
    private function getAdmissionData() { return []; }
    private function getApplicationDeadlines() { return []; }
    private function getTuitionInformation() { return []; }
    private function getFacultyData() { return []; }
    private function getFacultyDepartments() { return []; }
    private function getAlumniData() { return []; }
    private function getAlumniSuccessStories() { return []; }
    private function getAlumniEvents() { return []; }
    private function getStudentLifeData() { return []; }
    private function getStudentClubs() { return []; }
    private function getCampusFacilities() { return []; }
    private function getLibraryData() { return []; }
    private function getLibraryResources() { return []; }
    private function getLibraryHours() { return []; }
    
    /**
     * =========================================================================
     * ERROR HANDLING METHODS
     * =========================================================================
     */
    
    /**
     * Render error page
     */
    private function renderError($message) {
        $errorData = [
            'error' => $message,
            'page_title' => 'Error - FCT College of Nursing Sciences',
            'page_description' => 'An error occurred while loading the page'
        ];
        
        $this->data = array_merge($this->data, $errorData);
        $this->render('pages/error');
    }
    
    /**
     * Show 404 page
     */
    public function notFound() {
        $this->status(404);
        $this->data['page_title'] = '404 - Page Not Found';
        $this->render('pages/404');
    }
    
    /**
     * Show server error page - FIXED: Added parameter to match parent class
     */
    public function serverError($exception = null) {
        $this->status(500);
        
        $errorData = [
            'page_title' => '500 - Server Error',
            'error_message' => $exception ? $exception->getMessage() : 'An internal server error occurred',
            'error_trace' => $exception ? $exception->getTraceAsString() : ''
        ];
        
        $this->data = array_merge($this->data, $errorData);
        $this->render('pages/500');
    }
    
    // ============================================
    // DEPRECATED METHODS - MOVED TO CONTACTPAGECONTROLLER
    // ============================================
    
    /**
     * @deprecated Since v2.0 - Use ContactPageController@index instead
     * @see ContactPageController::index()
     */
    /*
    public function contact() {
        $this->redirect('/contact');
    }
    */
    
    /**
     * @deprecated Since v2.0 - Use ContactPageController@submit instead
     * @see ContactPageController::submit()
     */
    /*
    public function submitContact() {
        $this->redirect('/contact');
    }
    */
}