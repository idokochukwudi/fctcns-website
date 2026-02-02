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
     * Register all application routes
     */
    private function registerRoutes() {
        // ============================================
        // ADMIN AUTHENTICATION ROUTES - ADDED
        // ============================================
        $this->get('/admin/login', 'AdminController@login');
        $this->post('/admin/login', 'AdminController@processLogin');
        $this->get('/admin/logout', 'AdminController@logout');
        $this->get('/admin/dashboard', 'AdminController@dashboard');
        
        // ============================================
        // RESEARCH MODULE ROUTES - ADDED AS REQUESTED
        // ============================================
        // Admin Research Routes
        $this->get('/admin/research', 'ResearchController@index');
        $this->get('/admin/research/create', 'ResearchController@create');
        $this->post('/admin/research/store', 'ResearchController@store');
        
        // Secure test route - only in development/debug mode
        if (defined('APP_DEBUG') && APP_DEBUG === true) {
            $this->get('/admin/research/test-direct-create', 'ResearchController@testDirectCreate');
        }
        
        $this->get('/admin/research/{id}/edit', 'ResearchController@edit');
        $this->post('/admin/research/{id}/update', 'ResearchController@update');
        $this->get('/admin/research/{id}', 'ResearchController@show');
        $this->post('/admin/research/{id}/delete', 'ResearchController@destroy');
        $this->post('/admin/research/{id}/toggle', 'ResearchController@toggleStatus');
        $this->post('/admin/research/bulk-action', 'ResearchController@bulkAction');
        
        // Public Research Routes
        $this->get('/research', 'ResearchController@publicIndex');
        $this->get('/research/{id}', 'ResearchController@publicShow');
        $this->get('/research/{id}/download', 'ResearchController@download');

        // ============================================
        // PUBLIC PAGES ROUTES - ADDED
        // ============================================
        $this->get('/', 'PageController@home');
        $this->get('/about', 'PageController@about');
        $this->get('/programs', 'PageController@programs');
        $this->get('/admissions', 'PageController@admissions');
        $this->get('/contact', 'PageController@contact');
        $this->get('/news', 'PageController@news');
        $this->get('/faculty', 'PageController@faculty');
        $this->get('/alumni', 'PageController@alumni');
        $this->get('/student-life', 'PageController@studentLife');
        $this->get('/library', 'PageController@library');
        
        // Contact form submission
        $this->post('/contact/submit', 'PageController@submitContact');
        
        // ============================================
        // APPLICATION ROUTES - ADDED
        // ============================================
        $this->get('/apply', 'PublicApplicationController@showApplicationForm');
        $this->post('/apply/step/{step}', 'PublicApplicationController@processStep');
        $this->get('/apply/step/{step}', 'PublicApplicationController@showStep');
        $this->post('/apply/submit', 'PublicApplicationController@submitApplication');
        $this->get('/apply/success', 'PublicApplicationController@applicationSuccess');
        $this->get('/apply/reset', 'PublicApplicationController@resetApplication');
        
        // ============================================
        // ADMIN CONTACT MANAGEMENT ROUTES - ADDED
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
        // ADMIN CAROUSEL MANAGEMENT ROUTES - ADDED
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
        // SETUP AND INSTALLATION ROUTES - ADDED
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

        // Register admission routes
        $this->get('/viewadmissionlist', 'AdmissionController@index');
        $this->get('/admission', 'AdmissionController@index');
        $this->get('/admission/search', 'AdmissionController@search');
        $this->get('/admission/check', 'AdmissionController@check');
        
        // Admin routes
        $this->get('/admin/admission/update', 'AdmissionController@adminUpdate');
        $this->post('/admin/admission/update', 'AdmissionController@adminUpdate');
        $this->get('/admin/admission/manual-correction', 'AdmissionController@manualCorrection');
        $this->post('/admin/admission/manual-correction', 'AdmissionController@manualCorrection');
        
        // ============================================
        // NOMINAL ROLL ROUTES - VERIFIED AND FIXED
        // ============================================
        $this->get('/admin/nominal-roll', 'NominalRollController@index');
        $this->get('/admin/nominal-roll/create', 'NominalRollController@create');
        $this->post('/admin/nominal-roll/store', 'NominalRollController@store');
        $this->get('/admin/nominal-roll/view/{id}', 'NominalRollController@view');
        $this->get('/admin/nominal-roll/edit/{id}', 'NominalRollController@edit');
        $this->post('/admin/nominal-roll/update/{id}', 'NominalRollController@update');
        $this->post('/admin/nominal-roll/delete/{id}', 'NominalRollController@destroy');
        $this->get('/admin/nominal-roll/bulk-upload', 'NominalRollController@bulkUpload');
        $this->post('/admin/nominal-roll/bulk-upload', 'NominalRollController@processBulkUpload'); // ADDED: POST route for bulk upload
  
        // FIXED: Keep only these two admin routes for bulk upload (removed conflicting non-admin routes)
        $this->post('/admin/nominal-roll/validate-bulk-upload', 'NominalRollController@validateBulkUpload');
        $this->post('/admin/nominal-roll/bulk-upload-process', 'NominalRollController@processBulkUpload');
        
        // REMOVED: The following conflicting routes have been deleted as per instructions:
        // $this->post('/nominal-roll/validate-bulk-upload', 'NominalRollController@validateBulkUpload');
        // $this->post('/nominal-roll/bulk-upload-process', 'NominalRollController@processBulkUpload');
        
        $this->get('/admin/nominal-roll/download-template', 'NominalRollController@downloadTemplate');
        $this->get('/admin/nominal-roll/export', 'NominalRollController@export');
        
        // PDF Export Routes - ADDED
        $this->get('/admin/nominal-roll/export/pdf/(:num)', 'NominalRollController@exportPdf/$1');
        $this->get('/admin/nominal-roll/export/pdf', 'NominalRollController@exportPdf');
        // Add this line to your admin routes
        $this->get('/admin/nominal-roll/debug-verification', 'NominalRollController@debugVerification');
        $this->get('/admin/nominal-roll/debug-verification-detailed', 'NominalRollController@debugVerificationDetailed');
        // PRINT ROUTES - ADDED
        // Standard print routes
        $this->get('/admin/nominal-roll/print/{id}', 'NominalRollController@printView');
        $this->get('/admin/nominal-roll/print', 'NominalRollController@printView'); // For query string

        // Direct print routes (auto-prints)
        $this->get('/admin/nominal-roll/print/direct/{id}', 'NominalRollController@printDirect');

        // Print with options
        $this->get('/admin/nominal-roll/print/with-audit/{id}', 'NominalRollController@printWithAudit');
        
        // ============================================
        // TEST DATABASE INSERT ROUTE - ADDED AS REQUESTED
        // ============================================
        $this->get('/admin/nominal-roll/test-db-insert', 'NominalRollController@testDatabaseInsert');

        // ============================================
        // TEST EXACT CSV ROUTE - ADDED
        // ============================================
        $this->get('/admin/nominal-roll/test-exact-csv', 'NominalRollController@testExactCSV');
        
        // ============================================
        // QR CODE VERIFICATION ROUTES - UPDATED AS REQUESTED
        // ============================================
        // Public QR Code Verification Routes - EXACT routes as requested
        $this->get('/verify/employee/{id}', 'VerificationController@verifyEmployee');
        $this->get('/verify/passport/{id}', 'VerificationController@getPassportPhoto');
        
        // Keep existing route for backward compatibility
        $this->get('/verify/document/{ref}', 'VerificationController@verifyDocument');
        
        // Settings Routes
        $this->get('/admin/nominal-roll/settings', 'NominalRollController@settings');
        $this->post('/admin/nominal-roll/update-settings', 'NominalRollController@updateSettings');
        $this->post('/admin/nominal-roll/toggle-editing', 'NominalRollController@toggleEditing');
        
        // Drafts Routes
        $this->get('/admin/nominal-roll/drafts', 'NominalRollController@drafts');
        $this->post('/admin/nominal-roll/approve-draft/{id}', 'NominalRollController@approveDraft');
        
        // Backup Routes
        $this->post('/admin/nominal-roll/create-backup', 'NominalRollController@createBackup');
        $this->post('/admin/nominal-roll/restore-backup/{id}', 'NominalRollController@restoreBackup');
        $this->get('/admin/nominal-roll/download-backup/{id}', 'NominalRollController@downloadBackup');
        
        // Passport Photo Route
        $this->get('/admin/nominal-roll/passport-photo/{id}', 'NominalRollController@viewPassportPhoto');
        
        // ============================================
        // REPORTING ROUTES - UPDATED WITH FIXED EXPORT
        // ============================================
        $this->get('/admin/nominal-roll/reports', 'NominalRollController@reports');
        $this->post('/admin/nominal-roll/generate-report', 'NominalRollController@generateReport');
        $this->get('/admin/nominal-roll/report-preview', 'NominalRollController@reportPreview'); // ADDED
        $this->post('/admin/nominal-roll/save-report', 'NominalRollController@saveReport');
        $this->get('/admin/nominal-roll/load-report/{id}', 'NominalRollController@loadReport');
        $this->post('/admin/nominal-roll/delete-report/{id}', 'NominalRollController@deleteReport');
        
        // FIXED: Added POST route for export-excel (was missing)
        $this->post('/admin/nominal-roll/export-excel', 'NominalRollController@exportExcel');
        // Keep the original GET route too
        $this->get('/admin/nominal-roll/export-excel', 'NominalRollController@exportExcel');
        
        // FIXED: Added POST route for export-csv (was missing)
        $this->post('/admin/nominal-roll/export-csv', 'NominalRollController@exportCsv');
        // Keep the original GET route too
        $this->get('/admin/nominal-roll/export-csv', 'NominalRollController@exportCsv');
        
        // ============================================
        // NEW ROUTES FOR EXPORTING PREVIEW DATA - ADDED
        // ============================================
        $this->get('/admin/nominal-roll/export-preview-excel', 'NominalRollController@exportExcelFromPreview');
        $this->get('/admin/nominal-roll/export-preview-csv', 'NominalRollController@exportCsvFromPreview');
        
        // ============================================
        // NEW REPORT PREVIEW AJAX ROUTE - ADDED
        // ============================================
        $this->post('/admin/nominal-roll/generate-preview', 'NominalRollController@generatePreview');
        
        // ============================================
        // USER MANAGEMENT ROUTES - ADDED WITH PASSWORD CHANGE
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
        
        // ============================================
        // PASSWORD CHANGE ROUTES - ADDED AS REQUESTED
        // ============================================
        $this->get('/admin/users/change-password', 'UserManagementController@changePassword');
        $this->post('/admin/users/change-password', 'UserManagementController@processPasswordChange');
        
        $this->get('/admin/users/export', 'UserManagementController@export');
        $this->get('/admin/users/profile', 'UserManagementController@profile');
        $this->post('/admin/users/update-profile', 'UserManagementController@updateProfile');
        
        // ============================================
        // DEBUG ROUTES FOR TESTING - ADDED AS REQUESTED
        // ============================================
        $this->get('/admin/users-debug', function() {
            echo '<h1>Step 1: Router Test</h1>';
            echo '<p>If you see this, router is working</p>';
            exit;
        });
        
        $this->get('/admin/users-test2', function() {
            echo '<h1>Step 2: Controller Test</h1>';
            
            try {
                // Check if controller file exists
                $controllerPath = APP_PATH . '/controllers/UserManagementController.php';
                if (!file_exists($controllerPath)) {
                    echo '<p style="color: red;">✗ Controller file not found at: ' . $controllerPath . '</p>';
                    exit;
                }
                
                echo '<p style="color: green;">✓ Controller file exists</p>';
                
                // Load the controller
                require_once $controllerPath;
                
                // Check if class exists
                if (!class_exists('UserManagementController')) {
                    echo '<p style="color: red;">✗ UserManagementController class not found in file</p>';
                    exit;
                }
                
                echo '<p style="color: green;">✓ UserManagementController class exists</p>';
                
                // Try to instantiate it
                $controller = new UserManagementController();
                echo '<p style="color: green;">✓ Controller instantiated successfully</p>';
                
                // Check if index method exists
                if (method_exists($controller, 'index')) {
                    echo '<p style="color: green;">✓ index() method exists</p>';
                } else {
                    echo '<p style="color: red;">✗ index() method NOT found</p>';
                }
                
                // Check if changePassword method exists
                if (method_exists($controller, 'changePassword')) {
                    echo '<p style="color: green;">✓ changePassword() method exists</p>';
                } else {
                    echo '<p style="color: red;">✗ changePassword() method NOT found</p>';
                }
                
                // Check if processPasswordChange method exists
                if (method_exists($controller, 'processPasswordChange')) {
                    echo '<p style="color: green;">✓ processPasswordChange() method exists</p>';
                } else {
                    echo '<p style="color: red;">✗ processPasswordChange() method NOT found</p>';
                }
                
            } catch (Exception $e) {
                echo '<p style="color: red;">✗ Error: ' . $e->getMessage() . '</p>';
                echo '<pre>' . $e->getTraceAsString() . '</pre>';
            }
            
            exit;
        });
        
        $this->get('/admin/users-test3', function() {
            echo '<h1>Step 3: Route Matching Test</h1>';
            
            // Check if /admin/users route exists in router
            $routes = $this->getRoutes();
            $userRouteFound = false;
            
            foreach ($routes as $route) {
                if ($route['path'] === '/admin/users' && $route['method'] === 'GET') {
                    $userRouteFound = true;
                    echo '<p style="color: green;">✓ Found route: GET /admin/users -> ' . $route['handler'] . '</p>';
                    break;
                }
            }
            
            if (!$userRouteFound) {
                echo '<p style="color: red;">✗ Route GET /admin/users NOT found in router</p>';
                echo '<p>Available routes with "user":</p>';
                foreach ($routes as $route) {
                    if (strpos($route['path'], 'user') !== false) {
                        echo '<p>' . $route['method'] . ' ' . $route['path'] . ' -> ' . $route['handler'] . '</p>';
                    }
                }
            }
            
            // Check if password change routes exist
            $passwordChangeRoutes = [];
            foreach ($routes as $route) {
                if (strpos($route['path'], 'change-password') !== false) {
                    $passwordChangeRoutes[] = $route;
                }
            }
            
            if (count($passwordChangeRoutes) > 0) {
                echo '<p style="color: green;">✓ Found password change routes:</p>';
                foreach ($passwordChangeRoutes as $route) {
                    echo '<p>' . $route['method'] . ' ' . $route['path'] . ' -> ' . $route['handler'] . '</p>';
                }
            } else {
                echo '<p style="color: red;">✗ No password change routes found!</p>';
            }
            
            // Also check if there are multiple /admin/users routes causing conflict
            $count = 0;
            foreach ($routes as $route) {
                if ($route['path'] === '/admin/users') {
                    $count++;
                }
            }
            
            if ($count > 1) {
                echo '<p style="color: red;">✗ Found ' . $count . ' /admin/users routes (conflict!)</p>';
            }
            
            exit;
        });
        
        // ============================================
        // DEBUG PERMISSION ROUTE - ADDED AS REQUESTED
        // ============================================
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
            
            // Check specific permission
            $stmt = $db->prepare("SELECT COUNT(*) as count FROM user_permissions WHERE user_id = ? AND permission = 'nominal_roll_create'");
            $stmt->execute([$userId]);
            $hasCreate = $stmt->fetch()['count'] > 0;
            
            echo "<p>Has 'nominal_roll_create' permission: " . ($hasCreate ? 'YES' : 'NO') . "</p>";
            exit;
        });
        
        // Candidate admission check portal (simple page for candidates)
        // FIXED: Both GET and POST go to candidatePortal() method
        $this->get('/admission/check-portal', 'AdmissionController@candidatePortal');
        $this->post('/admission/check-portal', 'AdmissionController@candidatePortal');
        
        // AJAX API endpoint for checking status (optional - if you need separate API)
        // $this->post('/api/admission/check', 'AdmissionController@checkStatus');

        // Same page accessible from multiple URLs
        $this->get('/admissions/2025-2026', 'AdmissionController@index');
        $this->get('/admission-list', 'AdmissionController@index');
        
        // ============================================
        // ADD MISSING DEFAULT ROUTES FROM YOUR ERROR MESSAGE
        // ============================================
        $this->get('/login', 'AuthController@login');
        $this->post('/login', 'AuthController@login');
        $this->get('/logout', 'AuthController@logout');
        $this->get('/debug', 'DebugController@index');
        $this->get('/db-inspect', 'DebugController@dbInspect');
        $this->get('/db/create-tables', 'DebugController@createTables');
        
        // Applications routes
        $this->get('/admin/applications', 'ApplicationsController@index');
        $this->get('/admin/applications/create', 'ApplicationsController@create');
        $this->post('/admin/applications/store', 'ApplicationsController@store');
        $this->get('/admin/applications/view/{id}', 'ApplicationsController@view');
        $this->get('/admin/applications/edit/{id}', 'ApplicationsController@edit');
        $this->post('/admin/applications/update-status/{id}', 'ApplicationsController@updateStatus');
        
        // News routes
        $this->get('/admin/news', 'NewsController@index');
        $this->get('/admin/news/create', 'NewsController@create');
        $this->post('/admin/news/store', 'NewsController@store');
        
        // ============================================
        // API ROUTES - ADDED
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
        // DEBUG ROUTE - ADDED
        // ============================================
        $this->get('/debug-test', function() {
            echo "<h1>Debug Test Route</h1>";
            echo "<p>This route works!</p>";
            echo "<pre>";
            echo "REQUEST_URI: " . $_SERVER['REQUEST_URI'] . "\n";
            echo "SCRIPT_NAME: " . $_SERVER['SCRIPT_NAME'] . "\n";
            echo "PHP_SELF: " . $_SERVER['PHP_SELF'] . "\n";
            echo "</pre>";
        });
        
        // Test route specifically for nominal roll validation
        $this->get('/test-validate-route', function() {
            echo "<h1>Test Validate Bulk Upload Route</h1>";
            echo "<p>Checking if the POST route for validate-bulk-upload exists...</p>";
            
            $routes = $this->getRoutes();
            $found = false;
            
            foreach ($routes as $route) {
                // Now checking for the new fixed route
                if ($route['path'] === '/admin/nominal-roll/validate-bulk-upload' && $route['method'] === 'POST') {
                    $found = true;
                    echo "<p style='color: green;'>✓ Route found: POST /admin/nominal-roll/validate-bulk-upload -> " . $route['handler'] . "</p>";
                    break;
                }
            }
            
            if (!$found) {
                echo "<p style='color: red;'>✗ Route NOT found: POST /admin/nominal-roll/validate-bulk-upload</p>";
            }
            
            // Also check for the process-bulk-upload route
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
        });

        // ============================================
        // DEBUG ROUTE FOR BULK UPLOAD - ADD THIS
        // ============================================
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
        
        // 404 route - should be last
        $this->get('/404', 'PageController@notFound');
        
        if (defined('APP_DEBUG') && APP_DEBUG) {
            error_log("Router: All routes registered including RESEARCH MODULE routes");
            error_log("Router: Admin Authentication Routes:");
            error_log("Router:   /admin/login -> AdminController@login");
            error_log("Router:   POST /admin/login -> AdminController@processLogin");
            error_log("Router:   /admin/logout -> AdminController@logout");
            error_log("Router:   /admin/dashboard -> AdminController@dashboard");
            error_log("Router: Nominal Roll routes FIXED - removed conflicting routes:");
            error_log("Router:   ✓ POST /admin/nominal-roll/validate-bulk-upload -> NominalRollController@validateBulkUpload");
            error_log("Router:   ✓ POST /admin/nominal-roll/bulk-upload-process -> NominalRollController@processBulkUpload");
            error_log("Router:   ✗ DELETED: POST /nominal-roll/validate-bulk-upload (conflicting)");
            error_log("Router:   ✗ DELETED: POST /nominal-roll/bulk-upload-process (conflicting)");
            error_log("Router:   ✓ ADDED GET /admin/nominal-roll/test-db-insert -> NominalRollController@testDatabaseInsert");
            error_log("Router:   ✓ ADDED GET /admin/nominal-roll/test-exact-csv -> NominalRollController@testExactCSV");
            error_log("Router:   ✓ ADDED GET /admin/users/change-password -> UserManagementController@changePassword");
            error_log("Router:   ✓ ADDED POST /admin/users/change-password -> UserManagementController@processPasswordChange");
            error_log("Router:   ✓ ADDED GET /debug-bulk-upload-test -> Debug route for bulk upload testing");
            error_log("Router:   ✓ ADDED POST /admin/nominal-roll/bulk-upload -> NominalRollController@processBulkUpload");
            error_log("Router:   ✓ ADDED POST /admin/nominal-roll/export-excel -> NominalRollController@exportExcel");
            error_log("Router:   ✓ ADDED POST /admin/nominal-roll/export-csv -> NominalRollController@exportCsv");
            error_log("Router:   ✓ ADDED POST /admin/nominal-roll/generate-report -> NominalRollController@generateReport");
            error_log("Router:   ✓ ADDED POST /admin/nominal-roll/save-report -> NominalRollController@saveReport");
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
     * Add any route
     */
    private function addRoute($method, $path, $handler) {
        // Convert route to regex pattern using the FIXED version
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
     * Enhanced to handle both normal paths and regex patterns correctly
     */
    private function pathToRegex($path) {
        // Special handling for root
        if ($path === '/') {
            return '#^/$#';
        }
        
        // DEBUG: Log what we're converting
        if (defined('APP_DEBUG') && APP_DEBUG) {
            error_log("Router: Converting path to regex: '$path'");
        }
        
        // Check if this is already a regex pattern (contains regex special chars)
        $isRegexPattern = false;
        if (strpos($path, '(') !== false && strpos($path, ')') !== false) {
            // Contains parentheses - likely a regex pattern
            $isRegexPattern = true;
        }
        if (strpos($path, '.*') !== false) {
            // Contains wildcard - definitely a regex pattern
            $isRegexPattern = true;
        }
        
        if ($isRegexPattern) {
            // This is already a regex pattern (like /admin/(.*))
            // Don't escape it with preg_quote!
            
            // Ensure it has proper delimiters
            $pattern = $path;
            
            // If it doesn't start with #^, add it
            if (strpos($pattern, '#^') !== 0) {
                // Check if it already starts with ^
                if (strpos($pattern, '^') === 0) {
                    $pattern = '#' . $pattern;
                } else {
                    $pattern = '#^' . $pattern;
                }
            }
            
            // If it doesn't end with $#, add it
            if (substr($pattern, -2) !== '$#') {
                // Check if it already ends with $
                if (substr($pattern, -1) === '$') {
                    $pattern .= '#';
                } else {
                    $pattern .= '$#';
                }
            }
            
            if (defined('APP_DEBUG') && APP_DEBUG) {
                error_log("Router: Treating as regex pattern: '$path' -> '$pattern'");
            }
            
            return $pattern;
        }
        
        // Normal path (like /about, /contact, /login, /dashboard etc.)
        // Escape regex special characters
        $pattern = preg_quote($path, '#');
        
        // Replace parameter placeholders with regex patterns
        // After preg_quote, curly braces are escaped, so we need to match \{ and \}
        $pattern = preg_replace('#\\\\\{([^}]+)\\\\}#', '([^/]+)', $pattern);
        
        // Allow for optional trailing slash
        $pattern = '#^' . $pattern . '/?$#';
        
        if (defined('APP_DEBUG') && APP_DEBUG) {
            error_log("Router: Converted normal path: '$path' -> '$pattern'");
        }
        
        return $pattern;
    }

    /**
     * Match current request to a route
     */
    public function match() {
        $requestMethod = $_SERVER['REQUEST_METHOD'];

        // Get clean request URI - SIMPLIFIED
        $requestUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        
        // Remove trailing slash (except for root)
        if ($requestUri !== '/') {
            $requestUri = rtrim($requestUri, '/');
        }
        
        // Debug logging - ADDED
        error_log("Router matching: $requestMethod $requestUri");
        
        foreach ($this->routes as $route) {
            if ($route['method'] !== $requestMethod) {
                continue;
            }
            
            // Debug: log each route being checked - ADDED
            error_log("Checking route: {$route['path']} against $requestUri");
            
            if (preg_match($route['pattern'], $requestUri, $matches)) {
                // Debug: log when matched - ADDED
                error_log("MATCHED: {$route['path']} with pattern {$route['pattern']}");
                
                array_shift($matches);
                $this->params = $matches;
                
                return [
                    'handler' => $route['handler'],
                    'params' => $matches,
                    'route' => $route
                ];
            }
        }
        
        // Debug: log when no match found - ADDED
        error_log("NO ROUTE MATCHED for: $requestUri");
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
            $this->notFound();
            return;
        }

        // STEP I1: ADD THIS SECTION - Route Access Control
        if (isset($_SESSION['user_role'])) {
            require_once APP_PATH . '/middleware/RoleRedirectMiddleware.php';
            
            $currentRoute = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
            
            // Check if route is allowed for user's role
            if (!RoleRedirectMiddleware::isAllowedRoute($currentRoute)) {
                // Get user's proper landing page
                $redirectUrl = RoleRedirectMiddleware::redirect();
                
                // Set flash message
                require_once APP_PATH . '/config/session.php';
                Session::setFlash('error', 'Access denied. You don\'t have permission to access that page.');
                
                // Redirect to their allowed landing page
                header('Location: ' . $redirectUrl);
                exit;
            }
        }
        // END OF ROUTE ACCESS CONTROL

        $handler = $match['handler'];
        $params = $match['params'] ?? [];

        try {
            if (is_callable($handler)) {
                // Call the closure directly
                call_user_func_array($handler, $params);
            } elseif (is_string($handler)) {
                // Check if it's a controller method
                if (strpos($handler, '@') !== false) {
                    list($controller, $method) = explode('@', $handler);
                    $this->callController($controller, $method, $params);
                } else {
                    // Assume it's a file path - render view
                    $this->renderView($handler, $params);
                }
            }
        } catch (Exception $e) {
            $this->handleError($e);
        }
    }

    /**
     * Call a controller method
     */
    private function callController($controller, $method, $params) {
        // Construct controller class name
        $controllerClass = ucfirst($controller);
        
        // Add Controller suffix if not present
        if (substr($controllerClass, -10) !== 'Controller') {
            $controllerClass .= 'Controller';
        }
        
        if (defined('APP_DEBUG') && APP_DEBUG) {
            error_log("Router: Loading controller: $controllerClass::$method()");
            error_log("Router: With params: " . print_r($params, true));
        }
        
        // Find controller file
        $controllerFile = APP_PATH . "/controllers/{$controllerClass}.php";
        
        if (!file_exists($controllerFile)) {
            // Try alternative naming
            $controllerFile = APP_PATH . "/controllers/{$controller}.php";
        }
        
        if (file_exists($controllerFile)) {
            require_once $controllerFile;
            
            // Check if class exists
            if (!class_exists($controllerClass)) {
                throw new Exception("Controller class not found: $controllerClass");
            }
            
            $instance = new $controllerClass();
            if (method_exists($instance, $method)) {
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
        // Try multiple possible locations
        $possiblePaths = [
            PUBLIC_PATH . '/' . $viewPath,
            APP_PATH . '/views/' . $viewPath,
            APP_PATH . '/views/pages/' . basename($viewPath),
            $viewPath,
        ];
        
        foreach ($possiblePaths as $fullPath) {
            if (file_exists($fullPath)) {
                extract($data);
                include $fullPath;
                return;
            }
        }
        
        throw new Exception("View not found: $viewPath");
    }

    /**
     * Handle 404 Not Found
     */
    private function notFound() {
        http_response_code(404);
        
        // Try to use MVC 404 page
        if (class_exists('PageController')) {
            try {
                $controller = new PageController();
                $controller->notFound();
                return;
            } catch (Exception $e) {
                // Fall through to default
            }
        }
        
        // Try to find 404 view
        $possiblePaths = [
            APP_PATH . '/views/pages/404.php',
            PUBLIC_PATH . '/pages/404.php',
        ];
        
        foreach ($possiblePaths as $errorPage) {
            if (file_exists($errorPage)) {
                include $errorPage;
                return;
            }
        }
        
        // Default 404 page
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
            <p><a href='" . (defined('BASE_URL') ? BASE_URL : '/') . "'>Return to Homepage</a></p>
        </body>
        </html>";

        exit;
    }

    /**
     * Handle errors
     */
    private function handleError($exception) {
        error_log("Router Error: " . $exception->getMessage());
        
        if (defined('APP_DEBUG') && APP_DEBUG) {
            echo "<h1>Router Error</h1>";
            echo "<p>" . htmlspecialchars($exception->getMessage()) . "</p>";
            echo "<pre>" . htmlspecialchars($exception->getTraceAsString()) . "</pre>";
        } else {
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