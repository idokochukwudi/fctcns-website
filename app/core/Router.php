<?php
/**
 * Router Class - Enhanced Version with Fixed Route Matching
 * 
 * Handles URL routing - maps URLs to controller methods
 * 
 * @package FCT_CNS
 */

class Router {
    
    /**
     * @var array $routes - Stores all registered routes
     */
    private $routes = [];

    /**
     * @var array $params - Stores route parameters
     */
    private $params = [];

    /**
     * Constructor - Register routes on initialization
     */
    public function __construct() {
        $this->registerRoutes();
    }

    /**
     * Helper function for safe string operations
     */
    private function safeStr($value) {
        return is_string($value) ? $value : '';
    }

    /**
     * Register all application routes
     */
    private function registerRoutes() {
        // ============================================
        // DEBUG ROUTES - ADD THESE FIRST
        // ============================================
        $this->get('/router-test', function() {
            echo "<h1>Router Test</h1>";
            echo "<p>If you see this, the router is working!</p>";
            
            echo "<h3>All Registered Routes:</h3>";
            echo "<table border='1' cellpadding='5'>";
            echo "<tr><th>Method</th><th>Path</th><th>Handler</th></tr>";
            
            $routes = $this->getRoutes();
            foreach ($routes as $route) {
                echo "<tr>";
                echo "<td>" . $route['method'] . "</td>";
                echo "<td>" . htmlspecialchars($route['path']) . "</td>";
                echo "<td>" . (is_string($route['handler']) ? htmlspecialchars($route['handler']) : 'Closure') . "</td>";
                echo "</tr>";
            }
            echo "</table>";
            
            echo "<h3>Current Request:</h3>";
            echo "<pre>";
            echo "REQUEST_URI: " . ($_SERVER['REQUEST_URI'] ?? 'NOT SET') . "\n";
            echo "SCRIPT_NAME: " . ($_SERVER['SCRIPT_NAME'] ?? 'NOT SET') . "\n";
            echo "PHP_SELF: " . ($_SERVER['PHP_SELF'] ?? 'NOT SET') . "\n";
            echo "BASE_URL: " . (defined('BASE_URL') ? BASE_URL : 'NOT DEFINED') . "\n";
            echo "</pre>";
            
            exit;
        });
        
        $this->get('/ultra-simple-test', function() {
            echo "<h1>ULTRA SIMPLE TEST</h1>";
            echo "<p>This is a closure, not a controller method.</p>";
            
            echo "<h3>Testing NewsController instantiation:</h3>";
            try {
                require_once APP_PATH . '/controllers/NewsController.php';
                
                if (class_exists('NewsController')) {
                    echo "<p style='color: green;'>✓ NewsController class exists</p>";
                    
                    $controller = new NewsController();
                    echo "<p style='color: green;'>✓ NewsController instantiated</p>";
                    
                    if (method_exists($controller, 'simpleTest')) {
                        echo "<p style='color: green;'>✓ simpleTest() method exists</p>";
                    } else {
                        echo "<p style='color: red;'>✗ simpleTest() method NOT found</p>";
                    }
                } else {
                    echo "<p style='color: red;'>✗ NewsController class NOT found</p>";
                }
            } catch (Exception $e) {
                echo "<p style='color: red;'>✗ Error: " . $e->getMessage() . "</p>";
            }
            
            exit;
        });
        
        $this->post('/admin/news/debug-post', function() {
            error_log("=== DEBUG POST ROUTE HIT ===");
            error_log("POST data: " . print_r($_POST, true));
            error_log("FILES data: " . print_r($_FILES, true));
            
            echo json_encode([
                'status' => 'success',
                'post_data' => $_POST,
                'files_data' => array_keys($_FILES)
            ]);
            exit;
        });

        // ============================================
        // PUBLIC PAGES ROUTES
        // ============================================
        $this->get('/', 'PageController@home');
        $this->get('/about', 'PageController@about');
        $this->get('/programs', 'PageController@programs');
        $this->get('/admission', 'AdmissionController@index');
        $this->get('/faculty', 'PageController@faculty');
        $this->get('/alumni', 'PageController@alumni');
        $this->get('/student-life', 'PageController@studentLife');
        $this->get('/library', 'PageController@library');
        $this->get('/faq', 'FaqController@index');
        $this->get('/facilities', 'PageController@facilities');
        $this->get('/resources', 'PageController@resources');
    
        // ============================================
        // CONTACT ROUTES - ORGANIZED FOLDER STRUCTURE
        // ============================================
        $this->get('/contact', 'ContactPageController@index');
        $this->post('/contact/submit', 'ContactPageController@submit');
        $this->get('/contact/success', 'ContactPageController@success');
        $this->get('/contact/thank-you', 'ContactPageController@success');
        
        // ============================================
        // NEWSLETTER ROUTES
        // ============================================
        $this->post('/newsletter/subscribe', 'NewsletterController@subscribe');
        $this->get('/newsletter/confirm', 'NewsletterController@confirm');
        $this->get('/newsletter/unsubscribe', 'NewsletterController@unsubscribe');
        
        // ============================================
        // ADMIN AUTHENTICATION ROUTES
        // ============================================
        $this->get('/admin/login', 'AdminController@login');
        $this->post('/admin/login', 'AdminController@processLogin');
        $this->get('/admin/logout', 'AdminController@logout');
        $this->get('/admin/dashboard', 'AdminController@dashboard');
        
        // ============================================
        // RESEARCH MODULE ROUTES
        // ============================================
        $this->get('/admin/research', 'ResearchController@index');
        $this->get('/admin/research/create', 'ResearchController@create');
        $this->post('/admin/research/store', 'ResearchController@store');
        
        if (defined('APP_DEBUG') && APP_DEBUG === true) {
            $this->get('/admin/research/test-direct-create', 'ResearchController@testDirectCreate');
        }
        
        $this->get('/admin/research/{id}/edit', 'ResearchController@edit');
        $this->post('/admin/research/{id}/update', 'ResearchController@update');
        $this->get('/admin/research/{id}', 'ResearchController@show');
        $this->post('/admin/research/{id}/delete', 'ResearchController@destroy');
        $this->post('/admin/research/{id}/toggle', 'ResearchController@toggleStatus');
        $this->post('/admin/research/bulk-action', 'ResearchController@bulkAction');
        
        $this->get('/research', 'ResearchController@publicIndex');
        $this->get('/research/{id}', 'ResearchController@publicShow');
        $this->get('/research/{id}/download', 'ResearchController@download');

        // ============================================
        // CONSOLIDATED NEWS ROUTES (Using ONE controller)
        // ============================================
        
        // PUBLIC NEWS ROUTES
        $this->get('/news', 'NewsController@index');
        $this->get('/news/search', 'NewsController@search');
        $this->get('/news/category/{category}', 'NewsController@category');
        $this->get('/news/archive/{year}/{month}', 'NewsController@archive');
        $this->get('/news/debug', 'NewsController@debug');
        $this->get('/news/test', 'NewsController@test');
        $this->get('/news/direct-test', 'NewsController@directTest');
        $this->get('/news/simple-test', 'NewsController@simpleTest');
        $this->get('/news/{slug}', 'NewsController@show');
        
        // ADMIN NEWS ROUTES (Full access for admins/editors)
        $this->get('/admin/news', 'NewsController@adminIndex');
        $this->get('/admin/news/create', 'NewsController@create');
        $this->post('/admin/news/store', 'NewsController@store');
        $this->get('/admin/news/{id}', 'NewsController@show');
        $this->get('/admin/news/{id}/edit', 'NewsController@edit');
        $this->post('/admin/news/update/{id}', 'NewsController@update');
        $this->post('/admin/news/delete/{id}', 'NewsController@destroy');
        $this->post('/admin/news/bulk-action', 'NewsController@bulkAction');
        $this->get('/admin/news/export', 'NewsController@export');
        $this->get('/admin/news/test-edit-direct', 'NewsController@testEditDirect');
        $this->get('/admin/news/test-data-flow', 'NewsController@testDataFlow');
        $this->get('/admin/news/test-both', 'NewsController@testBothInserts');
        $this->get('/admin/news/test-create', 'NewsController@testDirectCreate');
        $this->post('/admin/news/test-endpoint', 'NewsController@testEndpoint');
        $this->get('/admin/news/test-simple-query', 'NewsController@testSimpleQuery');
        $this->get('/admin/news/test-fixes', 'NewsController@testFixes');
        $this->get('/admin/news/test-images', 'NewsController@testImagePaths');
        
        // NEWS MANAGER ROUTES (Limited access for news managers)
        $this->get('/admin/news-manager', 'NewsController@managerIndex');
        $this->get('/admin/news-manager/create', 'NewsController@create');
        $this->post('/admin/news-manager/store', 'NewsController@store');
        $this->get('/admin/news-manager/{id}', 'NewsController@show');
        $this->get('/admin/news-manager/{id}/edit', 'NewsController@edit');
        $this->post('/admin/news-manager/update/{id}', 'NewsController@update');
        $this->post('/admin/news-manager/delete/{id}', 'NewsController@destroy');
        $this->post('/admin/news-manager/{id}/toggle-publish', 'NewsController@togglePublish');
        $this->post('/admin/news-manager/{id}/toggle-featured', 'NewsController@toggleFeatured');
        $this->get('/admin/news-manager/categories', 'NewsController@categories');
        $this->post('/admin/news-manager/categories/add', 'NewsController@addCategory');
        $this->post('/admin/news-manager/categories/edit', 'NewsController@editCategory');
        $this->post('/admin/news-manager/categories/delete', 'NewsController@deleteCategory');

        // ============================================
        // ADMIN EVENTS MODULE ROUTES
        // ============================================
        $this->get('/admin/events', 'EventsController@index');
        $this->get('/admin/events/create', 'EventsController@create');
        $this->post('/admin/events', 'EventsController@store');
        $this->get('/admin/events/{id}', 'EventsController@show');
        $this->get('/admin/events/{id}/edit', 'EventsController@edit');
        $this->put('/admin/events/{id}', 'EventsController@update');
        $this->delete('/admin/events/{id}', 'EventsController@destroy');
        $this->post('/admin/events/{id}/register', 'EventsController@register');
        $this->get('/admin/events/registrations/{id}', 'EventsController@registrations');

        // ============================================
        // PUBLIC EVENTS ROUTES
        // ============================================
        $this->get('/events', 'EventsController@publicIndex');
        $this->get('/events/calendar', 'EventsController@calendar');
        $this->get('/events/{slug}', 'EventsController@publicShow');
        $this->post('/events/{id}/register', 'EventsController@publicRegister');

        // ============================================
        // ADMISSION ROUTES
        // ============================================
        $this->get('/admission', 'AdmissionController@index');
        $this->get('/viewadmissionlist', 'AdmissionController@index');
        $this->get('/admission/search', 'AdmissionController@search');
        $this->get('/admission/check', 'AdmissionController@check');
        $this->get('/admission/check-portal', 'AdmissionController@candidatePortal');
        $this->post('/admission/check-portal', 'AdmissionController@candidatePortal');
        $this->get('/admissions/2025-2026', 'AdmissionController@index');
        $this->get('/admission-list', 'AdmissionController@index');
        
        $this->get('/admin/admission/update', 'AdmissionController@adminUpdate');
        $this->post('/admin/admission/update', 'AdmissionController@adminUpdate');
        $this->get('/admin/admission/manual-correction', 'AdmissionController@manualCorrection');
        $this->post('/admin/admission/manual-correction', 'AdmissionController@manualCorrection');

        // ============================================
        // APPLICATION ROUTES - COMPLETE FLOW
        // ============================================
        
        // Landing page
        $this->get('/apply', 'PublicApplicationController@landing');

        // Step 1: Account Creation
        $this->get('/apply/register', 'PublicApplicationController@showRegistration');
        $this->post('/apply/register', 'PublicApplicationController@processRegistration');
        $this->get('/apply/verify-email', 'PublicApplicationController@verifyEmail');
        $this->get('/apply/resend-verification', 'PublicApplicationController@resendVerification');

        // Step 2: Application Form (requires login)
        $this->get('/apply/form', 'PublicApplicationController@showApplicationForm');
        $this->post('/apply/verify-jamb', 'PublicApplicationController@verifyJamb');
        $this->post('/apply/save-application', 'PublicApplicationController@saveApplication');
        // NEW ROUTE FOR REMOVING DOCUMENTS
        $this->post('/apply/remove-document', 'PublicApplicationController@removeDocument');

        // Step 3: Payment
        $this->get('/apply/payment', 'PublicApplicationController@showPayment');
        $this->post('/apply/initiate-payment', 'PublicApplicationController@initiatePayment');
        $this->get('/apply/verify-payment', 'PublicApplicationController@verifyPayment');

        // Step 4: Exam Slip
        $this->get('/apply/exam-slip', 'PublicApplicationController@showExamSlip');
        $this->get('/apply/download-slip', 'PublicApplicationController@downloadExamSlip');

        // Applicant authentication
        $this->get('/applicant/login', 'PublicApplicationController@login');
        $this->post('/applicant/login', 'PublicApplicationController@processLogin');
        $this->get('/applicant/logout', 'PublicApplicationController@logout');
        $this->get('/applicant/forgot-password', 'PublicApplicationController@forgotPassword');
        $this->post('/applicant/forgot-password', 'PublicApplicationController@processForgotPassword');
        $this->get('/applicant/reset-password', 'PublicApplicationController@resetPassword');
        $this->post('/applicant/reset-password', 'PublicApplicationController@processResetPassword');

        // Success/Failure pages
        $this->get('/apply/success', 'PublicApplicationController@verificationSuccess');
        $this->get('/apply/failed', 'PublicApplicationController@verificationFailed');
        
        // Legacy application routes (keeping for backward compatibility)
        $this->get('/apply/step/1', 'PublicApplicationController@step1');
        $this->get('/apply/step/2', 'PublicApplicationController@step2');
        $this->get('/apply/step/3', 'PublicApplicationController@step3');
        $this->get('/apply/step/4', 'PublicApplicationController@step4');
        $this->get('/apply/download-exam-slip', 'PublicApplicationController@downloadExamSlip');
        
        // ============================================
        // PAYMENT ROUTES - UPDATED WITH ALL REQUIRED ENDPOINTS
        // ============================================
        $this->post('/payment/initiate', 'PaymentController@initiate');
        $this->post('/payment/verify', 'PaymentController@verify');
        // Note: '/payment/status' route removed to avoid conflict with parent Controller
        $this->get('/payment/remita-response', 'PaymentController@remitaResponse');
        $this->post('/payment/remita-notification', 'PaymentController@remitaNotification');
        $this->get('/payment/check-status', 'PaymentController@checkStatus'); // FIXED: Using checkStatus instead of status
        $this->post('/payment/admin/verify', 'PaymentController@adminVerify');
        
        // ============================================
        // ADMIN CONTACT MANAGEMENT ROUTES
        // ============================================
        $this->get('/admin/contact', 'ContactController@index');
        $this->get('/admin/contact/view/{id}', 'ContactController@view');
        $this->post('/admin/contact/update/{id}', 'ContactController@update');
        $this->post('/admin/contact/delete/{id}', 'ContactController@delete');
        $this->get('/admin/contact/export', 'ContactController@export');
        $this->get('/admin/contact/settings', 'ContactController@settings');
        $this->post('/admin/contact/save-settings', 'ContactController@saveSettings');
        $this->post('/admin/contact/quick-update/{id}', 'ContactController@quickUpdate');
        
        // ============================================
        // ADMIN CAROUSEL MANAGEMENT ROUTES
        // ============================================
        $this->get('/admin/carousel', 'AdminCarouselController@index');
        $this->get('/admin/carousel/create', 'AdminCarouselController@create');
        $this->post('/admin/carousel/store', 'AdminCarouselController@store');
        $this->get('/admin/carousel/edit/{id}', 'AdminCarouselController@edit');
        $this->post('/admin/carousel/update/{id}', 'AdminCarouselController@update');
        $this->post('/admin/carousel/delete/{id}', 'AdminCarouselController@delete');
        $this->post('/admin/carousel/toggle/{id}', 'AdminCarouselController@toggle');
        $this->post('/admin/carousel/upload-image', 'AdminCarouselController@uploadImage');
        
        // ============================================
        // NOMINAL ROLL ROUTES
        // ============================================
        $this->get('/admin/nominal-roll', 'NominalRollController@index');
        $this->get('/admin/nominal-roll/create', 'NominalRollController@create');
        $this->post('/admin/nominal-roll/store', 'NominalRollController@store');
        $this->get('/admin/nominal-roll/view/{id}', 'NominalRollController@view');
        $this->get('/admin/nominal-roll/edit/{id}', 'NominalRollController@edit');
        $this->post('/admin/nominal-roll/update/{id}', 'NominalRollController@update');
        $this->post('/admin/nominal-roll/delete/{id}', 'NominalRollController@destroy');
        $this->get('/admin/nominal-roll/bulk-upload', 'NominalRollController@bulkUpload');
        $this->post('/admin/nominal-roll/validate-bulk-upload', 'NominalRollController@validateBulkUpload');
        $this->post('/admin/nominal-roll/bulk-upload-process', 'NominalRollController@processBulkUpload');
        $this->get('/admin/nominal-roll/download-template', 'NominalRollController@downloadTemplate');
        $this->get('/admin/nominal-roll/export', 'NominalRollController@export');
        $this->get('/admin/nominal-roll/export/pdf', 'NominalRollController@exportPdf');
        $this->get('/admin/nominal-roll/export/pdf/{id}', 'NominalRollController@exportPdf');
        $this->get('/admin/nominal-roll/print/{id}', 'NominalRollController@printView');
        $this->get('/admin/nominal-roll/print', 'NominalRollController@printView');
        $this->get('/admin/nominal-roll/print/direct/{id}', 'NominalRollController@printDirect');
        $this->get('/admin/nominal-roll/print/with-audit/{id}', 'NominalRollController@printWithAudit');
        $this->get('/admin/nominal-roll/qualification-report/{program}/{year}', 'NominalRollController@quickQualificationReport');
        $this->get('/admin/nominal-roll/test-db-insert', 'NominalRollController@testDatabaseInsert');
        $this->get('/admin/nominal-roll/test-exact-csv', 'NominalRollController@testExactCSV');
        $this->get('/admin/nominal-roll/settings', 'NominalRollController@settings');
        $this->post('/admin/nominal-roll/update-settings', 'NominalRollController@updateSettings');
        $this->post('/admin/nominal-roll/toggle-editing', 'NominalRollController@toggleEditing');
        $this->get('/admin/nominal-roll/drafts', 'NominalRollController@drafts');
        $this->post('/admin/nominal-roll/approve-draft/{id}', 'NominalRollController@approveDraft');
        $this->post('/admin/nominal-roll/create-backup', 'NominalRollController@createBackup');
        $this->post('/admin/nominal-roll/restore-backup/{id}', 'NominalRollController@restoreBackup');
        $this->get('/admin/nominal-roll/download-backup/{id}', 'NominalRollController@downloadBackup');
        $this->get('/admin/nominal-roll/passport-photo/{id}', 'NominalRollController@viewPassportPhoto');
        $this->get('/admin/nominal-roll/reports', 'NominalRollController@reports');
        $this->post('/admin/nominal-roll/generate-report', 'NominalRollController@generateReport');
        $this->get('/admin/nominal-roll/report-preview', 'NominalRollController@reportPreview');
        $this->post('/admin/nominal-roll/save-report', 'NominalRollController@saveReport');
        $this->get('/admin/nominal-roll/load-report/{id}', 'NominalRollController@loadReport');
        $this->post('/admin/nominal-roll/delete-report/{id}', 'NominalRollController@deleteReport');
        $this->post('/admin/nominal-roll/export-excel', 'NominalRollController@exportExcel');
        $this->get('/admin/nominal-roll/export-excel', 'NominalRollController@exportExcel');
        $this->post('/admin/nominal-roll/export-csv', 'NominalRollController@exportCsv');
        $this->get('/admin/nominal-roll/export-csv', 'NominalRollController@exportCsv');
        $this->get('/admin/nominal-roll/export-preview-excel', 'NominalRollController@exportExcelFromPreview');
        $this->get('/admin/nominal-roll/export-preview-csv', 'NominalRollController@exportCsvFromPreview');
        $this->post('/admin/nominal-roll/generate-preview', 'NominalRollController@generatePreview');

        // ============================================
        // QR CODE VERIFICATION ROUTES
        // ============================================
        $this->get('/verify/employee/{id}', 'VerificationController@verifyEmployee');
        $this->get('/verify/passport/{id}', 'VerificationController@getPassportPhoto');
        $this->get('/verify/document/{ref}', 'VerificationController@verifyDocument');

        // ============================================
        // USER MANAGEMENT ROUTES
        // ============================================
        $this->get('/admin/users', 'UserManagementController@index');
        $this->get('/admin/users/create', 'UserManagementController@create');
        $this->post('/admin/users/store', 'UserManagementController@store');
        $this->get('/admin/users/view/{id}', 'UserManagementController@view');
        $this->get('/admin/users/edit/{id}', 'UserManagementController@edit');
        $this->post('/admin/users/update/{id}', 'UserManagementController@update');
        $this->post('/admin/users/delete/{id}', 'UserManagementController@destroy');
        $this->post('/admin/users/toggle-status/{id}', 'UserManagementController@toggleStatus');
        $this->post('/admin/users/reset-password/{id}', 'UserManagementController@resetPassword');
        $this->get('/admin/users/change-password', 'UserManagementController@changePassword');
        $this->post('/admin/users/change-password', 'UserManagementController@processPasswordChange');
        $this->get('/admin/users/export', 'UserManagementController@export');
        $this->get('/admin/users/profile', 'UserManagementController@profile');
        $this->post('/admin/users/update-profile', 'UserManagementController@updateProfile');

        // ============================================
        // APPLICATIONS MANAGEMENT ROUTES
        // ============================================
        $this->get('/admin/applications', 'ApplicationsController@index');
        $this->get('/admin/applications/create', 'ApplicationsController@create');
        $this->post('/admin/applications/store', 'ApplicationsController@store');
        $this->get('/admin/applications/view/{id}', 'ApplicationsController@view');
        $this->get('/admin/applications/edit/{id}', 'ApplicationsController@edit');
        $this->post('/admin/applications/update-status/{id}', 'ApplicationsController@updateStatus');

        // ============================================
        // ADMIN APPLICATION MANAGEMENT ROUTES
        // ============================================
        $this->get('/admin/applications/dashboard', 'AdminApplicationController@dashboard');
        $this->post('/admin/applications/status', 'AdminApplicationController@updateStatus');
        $this->get('/admin/applications/settings', 'AdminApplicationController@settings');
        $this->post('/admin/applications/settings', 'AdminApplicationController@settings');
        $this->get('/admin/applications/terms', 'AdminApplicationController@terms');
        $this->post('/admin/applications/terms/create', 'AdminApplicationController@createTerms');
        $this->post('/admin/applications/terms/edit/{id}', 'AdminApplicationController@editTerms');
        $this->post('/admin/applications/terms/activate/{id}', 'AdminApplicationController@activateTerms');
        $this->get('/admin/applications/jamb-import', 'AdminApplicationController@jambImport');
        $this->post('/admin/applications/jamb-import', 'AdminApplicationController@processJambImport');
        $this->get('/admin/applications/jamb-template', 'AdminApplicationController@downloadJambTemplate');
        $this->get('/admin/applications/payments', 'AdminApplicationController@payments');
        $this->get('/admin/applications/payments/{id}', 'AdminApplicationController@viewPayment');
        $this->get('/admin/applications/export', 'AdminApplicationController@export');

        // ============================================
        // AUTHENTICATION ROUTES
        // ============================================
        $this->get('/login', 'AuthController@login');
        $this->post('/login', 'AuthController@login');
        $this->get('/logout', 'AuthController@logout');

        // ============================================
        // DEBUG ROUTES
        // ============================================
        if (defined('APP_DEBUG') && APP_DEBUG) {
            $this->get('/debug', 'DebugController@index');
            $this->get('/db-inspect', 'DebugController@dbInspect');
            $this->get('/db/create-tables', 'DebugController@createTables');
            $this->get('/debug-permission', function() {
                require_once APP_PATH . '/config/database.php';
                $database = Database::getInstance();
                $db = $database->getConnection();
                
                $userId = $_SESSION['user_id'] ?? 0;
                $stmt = $db->prepare("SELECT permission FROM user_permissions WHERE user_id = ?");
                $stmt->execute([$userId]);
                $permissions = $stmt->fetchAll(PDO::FETCH_COLUMN);
                
                echo "<h1>Debug Permission Check</h1>";
                echo "<p>User ID: $userId</p>";
                echo "<p>User Role: " . ($_SESSION['user_role'] ?? 'none') . "</p>";
                echo "<h3>Permissions in database:</h3>";
                echo "<pre>" . print_r($permissions, true) . "</pre>";
                
                $stmt = $db->prepare("SELECT COUNT(*) as count FROM user_permissions WHERE user_id = ? AND permission = 'nominal_roll_create'");
                $stmt->execute([$userId]);
                $hasCreate = $stmt->fetch()['count'] > 0;
                
                echo "<p>Has 'nominal_roll_create' permission: " . ($hasCreate ? 'YES' : 'NO') . "</p>";
                exit;
            });
            
            $this->get('/debug-test', function() {
                echo "<h1>Debug Test Route</h1>";
                echo "<p>This route works!</p>";
                echo "<pre>";
                echo "REQUEST_URI: " . ($_SERVER['REQUEST_URI'] ?? 'NOT SET') . "\n";
                echo "SCRIPT_NAME: " . ($_SERVER['SCRIPT_NAME'] ?? 'NOT SET') . "\n";
                echo "PHP_SELF: " . ($_SERVER['PHP_SELF'] ?? 'NOT SET') . "\n";
                echo "</pre>";
            });
            
            $this->get('/test-validate-route', function() {
                echo "<h1>Test Validate Bulk Upload Route</h1>";
                
                $routes = $this->getRoutes();
                $found = false;
                
                foreach ($routes as $route) {
                    if ($route['path'] === '/admin/nominal-roll/validate-bulk-upload' && $route['method'] === 'POST') {
                        $found = true;
                        echo "<p style='color: green;'>✓ Route found: POST /admin/nominal-roll/validate-bulk-upload -> " . $route['handler'] . "</p>";
                        break;
                    }
                }
                
                if (!$found) {
                    echo "<p style='color: red;'>✗ Route NOT found: POST /admin/nominal-roll/validate-bulk-upload</p>";
                }
                
                $foundProcess = false;
                foreach ($routes as $route) {
                    if ($route['path'] === '/admin/nominal-roll/bulk-upload-process' && $route['method'] === 'POST') {
                        $foundProcess = true;
                        echo "<p style='color: green;'>✓ Route found: POST /admin/nominal-roll/bulk-upload-process -> " . $route['handler'] . "</p>";
                        break;
                    }
                }
                
                if (!$foundProcess) {
                    echo "<p style='color: red;'>✗ Route NOT found: POST /admin/nominal-roll/bulk-upload-process</p>";
                }
                
                echo "<p>All nominal roll routes:</p>";
                echo "<ul>";
                foreach ($routes as $route) {
                    if (strpos($route['path'], 'nominal-roll') !== false) {
                        echo "<li>{$route['method']} {$route['path']} -> {$route['handler']}</li>";
                    }
                }
                echo "</ul>";
                exit;
            });
            
            $this->get('/debug-bulk-upload-test', function() {
                echo "<!DOCTYPE html><html><head><title>Bulk Upload Test</title></head><body>";
                echo "<h1>Bulk Upload Route Debug</h1>";
                
                echo "<h3>Testing CSRF Token Generation:</h3>";
                if (!isset($_SESSION['csrf_tokens'])) {
                    $_SESSION['csrf_tokens'] = [];
                }
                $testToken = bin2hex(random_bytes(32));
                $_SESSION['csrf_tokens'][$testToken] = time();
                echo "<p>Generated CSRF Token: " . substr($testToken, 0, 20) . "...</p>";
                echo "<p>Total tokens in session: " . count($_SESSION['csrf_tokens']) . "</p>";
                
                echo "<h3>Test Forms:</h3>";
                echo "<h4>1. Test Validation Route:</h4>";
                echo "<form action='/admin/nominal-roll/validate-bulk-upload' method='POST' enctype='multipart/form-data'>";
                echo "<input type='hidden' name='csrf_token' value='{$testToken}'>";
                echo "<input type='file' name='file'><br>";
                echo "<input type='submit' value='Test Validation'>";
                echo "</form>";
                
                echo "<h4>2. Test Bulk Upload Route:</h4>";
                echo "<form action='/admin/nominal-roll/bulk-upload-process' method='POST' enctype='multipart/form-data'>";
                echo "<input type='hidden' name='csrf_token' value='{$testToken}'>";
                echo "<input type='file' name='file'><br>";
                echo "<input type='submit' value='Test Bulk Upload'>";
                echo "</form>";
                
                echo "</body></html>";
                exit;
            });
        }

        // ============================================
        // SETUP AND INSTALLATION ROUTES
        // ============================================
        $this->get('/setup', function() {
            $path = PUBLIC_PATH . '/pages/setup.php';
            if (file_exists($path)) {
                include $path;
            } else {
                echo "<h1>Setup</h1>";
            }
        });
        
        $this->get('/database/install', function() {
            $path = ROOT_PATH . '/database/install.php';
            if (file_exists($path)) {
                include $path;
            } else {
                echo "<h1>Database Installation</h1>";
            }
        });
        
        $this->get('/database/test', function() {
            $path = ROOT_PATH . '/database/test.php';
            if (file_exists($path)) {
                include $path;
            } else {
                echo "<h1>Database Test</h1>";
            }
        });

        // ============================================
        // API ROUTES
        // ============================================
        $this->get('/api/carousel', function() {
            header('Content-Type: application/json');
            echo json_encode(['status' => 'success', 'message' => 'API endpoint']);
        });
        
        $this->get('/api/news/latest', function() {
            header('Content-Type: application/json');
            echo json_encode(['status' => 'success', 'data' => []]);
        });

        // ============================================
        // 404 ROUTE - MUST BE LAST
        // ============================================
        $this->get('/404', 'PageController@notFound');
        
        if (defined('APP_DEBUG') && APP_DEBUG) {
            error_log("Router: All routes registered - Total: " . count($this->routes));
        }
    }

    /**
     * Add a GET route
     */
    public function get($path, $handler) {
        $this->addRoute('GET', $path, $handler);
    }

    /**
     * Add a POST route
     */
    public function post($path, $handler) {
        $this->addRoute('POST', $path, $handler);
    }

    /**
     * Add a PUT route
     */
    public function put($path, $handler) {
        $this->addRoute('PUT', $path, $handler);
    }

    /**
     * Add a DELETE route
     */
    public function delete($path, $handler) {
        $this->addRoute('DELETE', $path, $handler);
    }

    /**
     * Add any route
     */
    private function addRoute($method, $path, $handler) {
        // Check for duplicate routes before adding
        foreach ($this->routes as $route) {
            if ($route['method'] === $method && $route['path'] === $path) {
                if (defined('APP_DEBUG') && APP_DEBUG) {
                    error_log("Router: DUPLICATE ROUTE SKIPPED - $method $path");
                }
                return; // Skip duplicate route
            }
        }
        
        $pattern = $this->pathToRegex($path);
        
        $this->routes[] = [
            'method' => $method,
            'path' => $path,
            'pattern' => $pattern,
            'handler' => $handler
        ];
        
        if (defined('APP_DEBUG') && APP_DEBUG) {
            error_log("Route registered: $method $path -> " . (is_string($handler) ? $handler : 'Closure'));
        }
    }

    /**
     * Convert route path to regex pattern - FIXED VERSION
     */
    private function pathToRegex($path) {
        if ($path === '/') {
            return '#^/$#';
        }
        
        if (defined('APP_DEBUG') && APP_DEBUG) {
            error_log("Router: Converting path to regex: '$path'");
        }
        
        $isRegexPattern = false;
        if (strpos($path, '(') !== false && strpos($path, ')') !== false) {
            $isRegexPattern = true;
        }
        if (strpos($path, '.*') !== false) {
            $isRegexPattern = true;
        }
        
        if ($isRegexPattern) {
            $pattern = $path;
            
            if (strpos($pattern, '#^') !== 0) {
                if (strpos($pattern, '^') === 0) {
                    $pattern = '#' . $pattern;
                } else {
                    $pattern = '#^' . $pattern;
                }
            }
            
            if (substr($pattern, -2) !== '$#') {
                if (substr($pattern, -1) === '$') {
                    $pattern .= '#';
                } else {
                    $pattern .= '$#';
                }
            }
            
            return $pattern;
        }
        
        $pattern = preg_quote($path, '#');
        $pattern = preg_replace('#\\\\\{([^}]+)\\\\}#', '([^/]+)', $pattern);
        $pattern = '#^' . $pattern . '/?$#';
        
        return $pattern;
    }

    /**
     * Match current request to a route - FIXED VERSION
     */
    public function match() {
        $requestMethod = $_SERVER['REQUEST_METHOD'] ?? 'GET';
        
        // FIX: Ensure request URI is a string
        $requestUri = parse_url($_SERVER['REQUEST_URI'] ?? '', PHP_URL_PATH);
        $requestUri = $this->safeStr($requestUri);
        
        if (empty($requestUri)) {
            $requestUri = '/';
        }
        
        $scriptDir = dirname($_SERVER['SCRIPT_NAME'] ?? '');
        $scriptDir = $this->safeStr($scriptDir);
        
        if ($scriptDir !== '/' && $scriptDir !== '\\' && strpos($requestUri, $scriptDir) === 0) {
            $requestUri = substr($requestUri, strlen($scriptDir));
        }
        
        if ($requestUri === '' || $requestUri === false) {
            $requestUri = '/';
        }
        
        if ($requestUri !== '/') {
            $requestUri = rtrim($requestUri, '/');
        }
        
        error_log("==========================================");
        error_log("ROUTER MATCHING:");
        error_log("  Request Method: $requestMethod");
        error_log("  Request URI: $requestUri");
        error_log("  Routes count: " . count($this->routes));
        
        foreach ($this->routes as $route) {
            // Check method match
            $methodMatches = false;
            
            if ($route['method'] === 'PUT' || $route['method'] === 'DELETE') {
                if ($requestMethod === 'POST' && isset($_POST['_method'])) {
                    $methodMatches = ($_POST['_method'] === $route['method']);
                } else {
                    $methodMatches = ($requestMethod === $route['method']);
                }
            } else {
                $methodMatches = ($requestMethod === $route['method']);
            }
            
            if (!$methodMatches) {
                continue;
            }
            
            if (preg_match($route['pattern'], $requestUri, $matches)) {
                error_log("  ✓ MATCHED: {$route['path']}");
                
                array_shift($matches);
                $this->params = $matches;
                
                return [
                    'handler' => $route['handler'],
                    'params' => $matches,
                    'route' => $route
                ];
            }
        }
        
        error_log("  ✗ NO ROUTE MATCHED");
        error_log("==========================================");
        return null;
    }

    /**
     * Get all registered routes (for debugging)
     */
    public function getRoutes() {
        return $this->routes;
    }

    /**
     * Get route parameters
     */
    public function getParams() {
        return $this->params;
    }

    /**
     * Dispatch the matched route
     */
    public function dispatch($match) {
        if ($match === null) {
            error_log("ROUTER: No match found, showing 404");
            $this->notFound();
            return;
        }

        if (isset($_SESSION['user_role'])) {
            require_once APP_PATH . '/middleware/RoleRedirectMiddleware.php';
            
            $currentRoute = parse_url($_SERVER['REQUEST_URI'] ?? '', PHP_URL_PATH);
            $currentRoute = $this->safeStr($currentRoute);
            
            if (!RoleRedirectMiddleware::isAllowedRoute($currentRoute)) {
                $redirectUrl = RoleRedirectMiddleware::redirect();
                
                require_once APP_PATH . '/config/session.php';
                Session::setFlash('error', 'Access denied. You don\'t have permission to access that page.');
                
                header('Location: ' . $redirectUrl);
                exit;
            }
        }

        $handler = $match['handler'];
        $params = $match['params'] ?? [];

        error_log("ROUTER: Dispatching handler: " . (is_string($handler) ? $handler : 'Closure'));

        try {
            if (is_callable($handler)) {
                call_user_func_array($handler, $params);
            } elseif (is_string($handler)) {
                if (strpos($handler, '@') !== false) {
                    list($controller, $method) = explode('@', $handler);
                    error_log("ROUTER: Calling controller: $controller::$method()");
                    $this->callController($controller, $method, $params);
                } else {
                    error_log("ROUTER: Rendering view: $handler");
                    $this->renderView($handler, $params);
                }
            }
        } catch (Exception $e) {
            error_log("ROUTER ERROR: " . $e->getMessage());
            $this->handleError($e);
        }
    }

    /**
     * Call a controller method
     */
    private function callController($controller, $method, $params) {
        $controllerClass = ucfirst($controller);
        
        if (substr($controllerClass, -10) !== 'Controller') {
            $controllerClass .= 'Controller';
        }
        
        error_log("ROUTER: Loading controller: $controllerClass::$method()");
        
        $controllerFile = APP_PATH . "/controllers/{$controllerClass}.php";
        
        if (!file_exists($controllerFile)) {
            $controllerFile = APP_PATH . "/controllers/{$controller}.php";
        }
        
        if (file_exists($controllerFile)) {
            error_log("ROUTER: Controller file found: $controllerFile");
            require_once $controllerFile;
            
            if (!class_exists($controllerClass)) {
                throw new Exception("Controller class not found: $controllerClass");
            }
            
            $instance = new $controllerClass();
            if (method_exists($instance, $method)) {
                error_log("ROUTER: Method exists, calling it");
                call_user_func_array([$instance, $method], $params);
                return;
            } else {
                throw new Exception("Controller method not found: $controllerClass::$method()");
            }
        }

        throw new Exception("Controller file not found: $controllerFile");
    }

    /**
     * Render a view file
     */
    private function renderView($viewPath, $data = []) {
        error_log("ROUTER: Rendering view: $viewPath");
        
        $possiblePaths = [
            PUBLIC_PATH . '/' . $viewPath,
            APP_PATH . '/views/' . $viewPath,
            APP_PATH . '/views/pages/' . basename($viewPath),
            $viewPath,
        ];
        
        foreach ($possiblePaths as $fullPath) {
            if (file_exists($fullPath)) {
                error_log("ROUTER: View found at: $fullPath");
                extract($data);
                include $fullPath;
                return;
            }
        }
        
        throw new Exception("View not found: $viewPath");
    }

    /**
     * Handle 404 Not Found - FIXED VERSION
     */
    private function notFound() {
        http_response_code(404);
        
        error_log("ROUTER: Showing 404 page");
        
        if (class_exists('PageController')) {
            try {
                $controller = new PageController();
                $controller->notFound();
                return;
            } catch (Exception $e) {
                error_log("PageController notFound failed: " . $e->getMessage());
            }
        }
        
        $possiblePaths = [
            APP_PATH . '/views/pages/404.php',
            APP_PATH . '/views/404.php',
            PUBLIC_PATH . '/pages/404.php',
            PUBLIC_PATH . '/404.php',
        ];
        
        foreach ($possiblePaths as $errorPage) {
            if (file_exists($errorPage)) {
                include $errorPage;
                return;
            }
        }
        
        $baseUrl = defined('BASE_URL') ? BASE_URL : '/';
        echo "<!DOCTYPE html>
        <html>
        <head>
            <title>404 - Page Not Found</title>
            <style>
                body { font-family: Arial, sans-serif; padding: 40px; text-align: center; }
                h1 { color: #6B4E9B; }
            </style>
        </head>
        <body>
            <h1>404 - Page Not Found</h1>
            <p>The page you requested could not be found.</p>
            <p>Requested URL: " . htmlspecialchars($_SERVER['REQUEST_URI'] ?? '') . "</p>
            <p><a href='" . $baseUrl . "'>Return to Homepage</a></p>
        </body>
        </html>";
        exit;
    }

    /**
     * Handle errors - FIXED VERSION
     */
    private function handleError($exception) {
        error_log("Router Error: " . $exception->getMessage());
        
        if (defined('APP_DEBUG') && APP_DEBUG) {
            echo "<h1>Router Error</h1>";
            echo "<p>" . htmlspecialchars($exception->getMessage()) . "</p>";
            echo "<pre>" . htmlspecialchars($exception->getTraceAsString()) . "</pre>";
        } else {
            $possiblePaths = [
                APP_PATH . '/views/pages/error.php',
                APP_PATH . '/views/error.php',
                PUBLIC_PATH . '/pages/error.php',
                PUBLIC_PATH . '/error.php',
            ];
            
            foreach ($possiblePaths as $errorPage) {
                if (file_exists($errorPage)) {
                    include $errorPage;
                    return;
                }
            }
            
            http_response_code(500);
            echo "<h1>Internal Server Error</h1>";
            echo "<p>Please try again later.</p>";
        }
        exit;
    }
    
    /**
     * Redirect helper method
     */
    public function redirect($url, $permanent = false) {
        if ($permanent) {
            header("HTTP/1.1 301 Moved Permanently");
        }
        header("Location: " . $url);
        exit;
    }
}