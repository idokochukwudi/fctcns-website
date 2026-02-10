<?php
/**
 * Admin News Controller - COMPLETE FIXED VERSION
 * Handles BOTH news articles AND events (in single news table with type column)
 */
class AdminNewsController extends Controller {
    
    private $db;
    private $newsModel;
    
    public function __construct() {
        parent::__construct();
        
        error_log("=== ADMIN NEWS CONTROLLER CONSTRUCTOR ===");
        
        // Set admin layout
        $this->layout = 'admin';
        
        // Require authentication
        require_once __DIR__ . '/../middleware/AuthMiddleware.php';
        AuthMiddleware::authenticate();
        
        // Check permission
        $userRole = $_SESSION['user_role'] ?? '';
        if (!in_array($userRole, ['admin', 'editor'])) {
            $this->redirect('/admin/dashboard');
        }
        
        // Setup database
        require_once __DIR__ . '/../config/database.php';
        $database = Database::getInstance();
        $this->db = $database->getConnection();
        
        // Initialize NewsModel with database connection
        require_once APP_PATH . '/models/NewsModel.php';
        $this->newsModel = new NewsModel($this->db);
        
        // Common data
        $this->data = array_merge($this->data, [
            'user' => $_SESSION ?? [],
            'baseUrl' => BASE_URL,
            'currentPage' => 'news'
        ]);
    }
    
    /**
     * Generate CSRF token - COMPATIBLE VERSION
     */
    private function generateCsrfToken() {
        require_once APP_PATH . '/config/session.php';
        return Session::generateCSRFTokenMulti();
    }
    
    /**
     * Validate CSRF token - COMPATIBLE VERSION
     */
    protected function validateCsrfToken() {
        $token = $_POST['csrf_token'] ?? '';
        require_once APP_PATH . '/config/session.php';
        return Session::validateCSRFTokenMulti($token);
    }
    
    /**
     * Display ALL content (BOTH news AND events) - UPDATED WITH FIXES
     */
    public function index() {
        // ADD THIS DEBUG
        error_log("==========================================");
        error_log("=== ADMIN NEWS INDEX CALLED ===");
        error_log("GET parameters: " . print_r($_GET, true));
        
        try {
            // Get filter parameters
            $filters = [
                'status' => $_GET['status'] ?? '',
                'category' => $_GET['category'] ?? '',
                'search' => $_GET['search'] ?? '',
                'date_from' => $_GET['date_from'] ?? '',
                'date_to' => $_GET['date_to'] ?? '',
                'type' => $_GET['type'] ?? ''  // 'news' or 'event'
            ];
            
            // Clean empty filters
            foreach ($filters as $key => $value) {
                if (empty($value)) {
                    unset($filters[$key]);
                }
            }
            
            error_log("Filters after cleaning: " . print_r($filters, true));
            
            // Get pagination
            $page = max(1, (int)($_GET['page'] ?? 1));
            $limit = 20;
            $offset = ($page - 1) * $limit;
            
            // USE THE MODEL METHODS
            error_log("Calling Model methods...");
            $news = $this->newsModel->getAllWithFilters($filters, $limit, $offset);
            $total = $this->newsModel->countAllWithFilters($filters);
            $totalPages = ceil($total / $limit);
            
            // Get stats
            $stats = $this->getCombinedStats();
            
            // Get categories from news table (both news and events)
            $categories = $this->getAllCategories();
            
            // Prepare flash messages
            $flashSuccess = $_SESSION['flash_success'] ?? '';
            $flashError = $_SESSION['flash_error'] ?? '';
            unset($_SESSION['flash_success'], $_SESSION['flash_error']);
            
            // Use $this->data (parent Controller will handle extraction) - FIXED
            $this->data['news'] = $news;
            $this->data['stats'] = $stats;
            $this->data['categories'] = $categories;
            $this->data['filters'] = $filters;
            $this->data['pagination'] = [
                'current' => $page,
                'total' => $totalPages,
                'limit' => $limit,
                'totalCount' => $total
            ];
            $this->data['csrf_token'] = $this->generateCsrfToken();
            $this->data['flash_success'] = $flashSuccess;
            $this->data['flash_error'] = $flashError;
            
            error_log("Index method data set:");
            error_log("  - news count: " . count($news));
            error_log("  - stats total: " . ($stats['total'] ?? 0));
            
            // Render the admin news index view
            $this->render('admin/news/index');
            
        } catch (Exception $e) {
            error_log("AdminNewsController index error: " . $e->getMessage());
            
            // Prepare error fallback data
            $this->data['news'] = [];
            $this->data['stats'] = $this->getDefaultStats();
            $this->data['categories'] = [];
            $this->data['filters'] = [];
            $this->data['pagination'] = [
                'current' => 1,
                'total' => 0,
                'limit' => 20,
                'totalCount' => 0
            ];
            $this->data['csrf_token'] = $this->generateCsrfToken();
            $this->data['flash_error'] = 'Error loading content: ' . $e->getMessage();
            
            $this->render('admin/news/index');
        }
    }
    
    /**
     * Get combined stats for news and events - FIXED VERSION
     */
    private function getCombinedStats() {
        error_log("=== getCombinedStats called ===");
        
        $stats = [];
        
        $queries = [
            'total' => "SELECT COUNT(*) as total FROM news",
            'published' => "SELECT COUNT(*) as total FROM news WHERE is_published = 1",
            'draft' => "SELECT COUNT(*) as total FROM news WHERE is_published = 0",
            'featured' => "SELECT COUNT(*) as total FROM news WHERE is_featured = 1",
            // FIX: Include NULL type as news
            'news' => "SELECT COUNT(*) as total FROM news WHERE (type = 'news' OR type IS NULL) AND is_published = 1",
            'events' => "SELECT COUNT(*) as total FROM news WHERE type = 'event' AND is_published = 1",
            'breaking' => "SELECT COUNT(*) as total FROM news WHERE is_breaking = 1",
            'this_month' => "SELECT COUNT(*) as total FROM news WHERE MONTH(created_at) = MONTH(CURDATE()) AND YEAR(created_at) = YEAR(CURDATE())",
            'this_week' => "SELECT COUNT(*) as total FROM news WHERE YEARWEEK(created_at) = YEARWEEK(CURDATE())",
            'upcoming_events' => "SELECT COUNT(*) as total FROM news WHERE type = 'event' AND is_published = 1 AND (event_date >= CURDATE() OR event_date IS NULL)"
        ];
        
        foreach ($queries as $key => $sql) {
            try {
                error_log("Running stats query for $key: " . $sql);
                $stmt = $this->db->query($sql);
                $stats[$key] = $stmt->fetch()['total'] ?? 0;
                error_log("  Result: " . $stats[$key]);
            } catch (Exception $e) {
                error_log("Stats query error for $key: " . $e->getMessage());
                $stats[$key] = 0;
            }
        }
        
        error_log("Final stats: " . print_r($stats, true));
        return $stats;
    }
    
    /**
     * Get all categories from news table
     */
    private function getAllCategories() {
        try {
            $sql = "SELECT DISTINCT category FROM news 
                    WHERE category IS NOT NULL 
                    AND category != '' 
                    ORDER BY category";
            $stmt = $this->db->query($sql);
            $categories = $stmt->fetchAll(PDO::FETCH_COLUMN);
            
            return $categories ?: ['Announcements', 'Research', 'Academic News', 'Events', 'Student Life', 'Community'];
            
        } catch (Exception $e) {
            error_log("Error getting categories: " . $e->getMessage());
            return ['Announcements', 'Research', 'Academic News', 'Events', 'Student Life', 'Community'];
        }
    }
    
    /**
     * Default stats when there's an error
     */
    private function getDefaultStats() {
        return [
            'total' => 0,
            'published' => 0,
            'draft' => 0,
            'featured' => 0,
            'news' => 0,
            'events' => 0,
            'breaking' => 0,
            'this_month' => 0,
            'this_week' => 0,
            'upcoming_events' => 0
        ];
    }
    
    /**
     * Test the query directly
     */
    public function testSimpleQuery() {
        error_log("=== TEST SIMPLE QUERY ===");
        
        try {
            // Test 1: Simple query without filters
            $sql1 = "SELECT id, title, is_published FROM news ORDER BY created_at DESC LIMIT 5";
            $results1 = $this->db->query($sql1)->fetchAll(PDO::FETCH_ASSOC);
            error_log("Simple query results: " . count($results1) . " records");
            
            // Test 2: Query with the same JOIN as getContentFromNewsTable
            $sql2 = "SELECT n.id, n.title, n.is_published 
                    FROM news n 
                    LEFT JOIN users u ON n.author_id = u.id 
                    ORDER BY n.created_at DESC 
                    LIMIT 5";
            $results2 = $this->db->query($sql2)->fetchAll(PDO::FETCH_ASSOC);
            error_log("JOIN query results: " . count($results2) . " records");
            
            // Test 3: Check if is_published values
            $publishedStats = $this->db->query("SELECT is_published, COUNT(*) as count FROM news GROUP BY is_published")->fetchAll();
            error_log("Published stats: " . print_r($publishedStats, true));
            
            echo json_encode([
                'simple_query' => $results1,
                'join_query' => $results2,
                'published_stats' => $publishedStats,
                'message' => 'Check PHP error log for details'
            ]);
            
        } catch (Exception $e) {
            error_log("Test error: " . $e->getMessage());
            echo json_encode(['error' => $e->getMessage()]);
        }
        
        exit;
    }
    
    /**
     * Show create form for news OR event - UPDATED FOR CONSISTENCY
     */
    public function create() {
        error_log("=== ADMIN NEWS CREATE METHOD CALLED ===");
        
        try {
            $categories = $this->getAllCategories();
            
            // Get type from query string (default to 'news')
            $type = $_GET['type'] ?? 'news';
            
            // Check for preserved form data from failed submission
            $preservedData = $_SESSION['form_data'] ?? null;
            if ($preservedData) {
                $newsData = $preservedData;
                unset($_SESSION['form_data']);
            } else {
                $newsData = [
                    'id' => 0,
                    'title' => '',
                    'slug' => '',
                    'excerpt' => '',
                    'content' => '',
                    'category' => '',
                    'tags' => '',
                    'featured_image' => '',
                    'is_published' => 1,
                    'is_featured' => 0,
                    'is_breaking' => 0,
                    'meta_title' => '',
                    'meta_description' => '',
                    'meta_keywords' => '',
                    'type' => $type,
                    'event_date' => date('Y-m-d'),
                    'event_end_date' => '',
                    'event_time' => '',
                    'event_location' => ''
                ];
            }
            
            // Use $this->data array (parent Controller will handle it) - FIXED
            $this->data['categories'] = $categories;
            $this->data['type'] = $type;
            $this->data['news'] = $newsData;
            $this->data['csrf_token'] = $this->generateCsrfToken();
            
            error_log("Create method data set:");
            error_log("  - type: " . $this->data['type']);
            error_log("  - categories count: " . count($this->data['categories']));
            
            $this->render('admin/news/create');
            
        } catch (Exception $e) {
            error_log("AdminNewsController create error: " . $e->getMessage());
            $this->setFlash('error', 'Failed to load create form: ' . $e->getMessage());
            $this->redirect('/admin/news');
        }
    }
    
    /**
     * Show edit form - PERMANENT FIX
     */
    public function edit($id) {
        error_log("=== ADMIN NEWS EDIT METHOD CALLED for ID: $id ===");
        
        try {
            // Get the news article
            $news = $this->newsModel->getById($id);
            
            if (!$news) {
                throw new Exception('Article not found');
            }
            
            error_log("Article found: ID {$news['id']}, Title: {$news['title']}");
            
            // Get categories
            $categories = $this->getAllCategories();
            $type = $news['type'] ?? 'news';
            
            // IMPORTANT: Use $this->data which will be merged by parent Controller
            // Add our specific data to the controller's data array
            $this->data['news'] = $news;
            $this->data['categories'] = $categories;
            $this->data['type'] = $type;
            $this->data['error'] = ''; // Clear any errors
            $this->data['csrf_token'] = $this->generateCsrfToken();
            
            // Log what we're passing
            error_log("Controller data being set:");
            error_log("  - news title: " . ($this->data['news']['title'] ?? 'NOT SET'));
            error_log("  - news id: " . ($this->data['news']['id'] ?? 'NOT SET'));
            error_log("  - categories count: " . count($this->data['categories']));
            error_log("  - type: " . $this->data['type']);
            
            // Render the view - let the parent Controller handle it
            // It will extract $this->data and make variables available in the view
            $this->render('admin/news/edit');
            
        } catch (Exception $e) {
            error_log("AdminNewsController edit error: " . $e->getMessage());
            
            // Set error and redirect back
            $this->setFlash('error', $e->getMessage());
            $this->redirect('/admin/news');
        }
    }
    
    /**
     * Handle image upload - UNIVERSAL VERSION (Works on both local and shared hosting)
     */
    private function handleImageUpload() {
        error_log("=== UNIVERSAL IMAGE UPLOAD ===");
        error_log("Server: " . $_SERVER['SERVER_NAME']);
        error_log("Document Root: " . $_SERVER['DOCUMENT_ROOT']);
        
        // 1. Check if removing existing image
        if (isset($_POST['remove_image']) && $_POST['remove_image'] == '1') {
            error_log("User requested to remove image");
            return '';
        }
        
        // 2. Check if file was uploaded
        if (isset($_FILES['featured_image_upload']) && $_FILES['featured_image_upload']['error'] === UPLOAD_ERR_OK) {
            $file = $_FILES['featured_image_upload'];
            $type = $_POST['type'] ?? 'news';
            
            error_log("File upload detected:");
            error_log("  - Name: " . $file['name']);
            error_log("  - Size: " . $file['size']);
            error_log("  - Type: " . ($file['type'] ?? 'unknown'));
            
            // Validate file type
            $allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp'];
            if (!in_array($file['type'], $allowedTypes)) {
                error_log("Invalid file type: " . $file['type']);
                return $_POST['featured_image'] ?? '';
            }
            
            // Validate file size (max 5MB)
            if ($file['size'] > 5 * 1024 * 1024) {
                error_log("File too large: " . $file['size'] . " bytes");
                return $_POST['featured_image'] ?? '';
            }
            
            // Get upload directory that works for both environments
            require_once APP_PATH . '/helpers/image_helper.php';
            $uploadDir = getUploadPath($type);
            
            // Ensure directory exists
            if (!is_dir($uploadDir)) {
                if (mkdir($uploadDir, 0755, true)) {
                    error_log("Created upload directory: " . $uploadDir);
                } else {
                    error_log("Failed to create directory: " . $uploadDir);
                    return $_POST['featured_image'] ?? '';
                }
            }
            
            // Check if directory is writable
            if (!is_writable($uploadDir)) {
                error_log("Directory not writable: " . $uploadDir);
                // Try to fix permissions
                if (chmod($uploadDir, 0755)) {
                    error_log("Fixed directory permissions");
                } else {
                    return $_POST['featured_image'] ?? '';
                }
            }
            
            // Generate safe filename
            $originalName = pathinfo($file['name'], PATHINFO_FILENAME);
            $extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
            
            // Clean filename
            $safeName = preg_replace('/[^a-zA-Z0-9_-]/', '', $originalName);
            $filename = $safeName . '_' . time() . '.' . $extension;
            $destination = rtrim($uploadDir, '/') . '/' . $filename;
            
            error_log("Upload Details:");
            error_log("  - Safe Name: " . $filename);
            error_log("  - Destination: " . $destination);
            error_log("  - Dir exists: " . (is_dir($uploadDir) ? 'YES' : 'NO'));
            error_log("  - Dir writable: " . (is_writable($uploadDir) ? 'YES' : 'NO'));
            
            // Move uploaded file
            if (move_uploaded_file($file['tmp_name'], $destination)) {
                // Set proper permissions
                chmod($destination, 0644);
                
                // Get relative path for database
                $relativePath = getRelativeUploadPath($filename, $type);
                
                error_log("✅ UPLOAD SUCCESS!");
                error_log("  - Saved to: " . $destination);
                error_log("  - Relative path: " . $relativePath);
                error_log("  - File size on disk: " . filesize($destination) . " bytes");
                
                // Verify file was saved
                if (file_exists($destination) && filesize($destination) > 0) {
                    return $relativePath;
                } else {
                    error_log("❌ File not found after move or zero size");
                    return $_POST['featured_image'] ?? '';
                }
            } else {
                error_log("❌ FAILED to move uploaded file");
                $error = error_get_last();
                error_log("  - Error: " . ($error['message'] ?? 'Unknown'));
                error_log("  - is_uploaded_file: " . (is_uploaded_file($file['tmp_name']) ? 'YES' : 'NO'));
                error_log("  - File exists: " . (file_exists($file['tmp_name']) ? 'YES' : 'NO'));
                
                return $_POST['featured_image'] ?? '';
            }
        } else {
            // Log upload error if any
            if (isset($_FILES['featured_image_upload'])) {
                $error = $_FILES['featured_image_upload']['error'];
                if ($error !== UPLOAD_ERR_OK && $error !== UPLOAD_ERR_NO_FILE) {
                    $errors = [
                        UPLOAD_ERR_INI_SIZE => 'File exceeds upload_max_filesize',
                        UPLOAD_ERR_FORM_SIZE => 'File exceeds MAX_FILE_SIZE in form',
                        UPLOAD_ERR_PARTIAL => 'File partially uploaded',
                        UPLOAD_ERR_NO_FILE => 'No file uploaded',
                        UPLOAD_ERR_NO_TMP_DIR => 'Missing temp directory',
                        UPLOAD_ERR_CANT_WRITE => 'Failed to write to disk',
                        UPLOAD_ERR_EXTENSION => 'PHP extension stopped upload'
                    ];
                    error_log("File upload error (" . $error . "): " . ($errors[$error] ?? 'Unknown'));
                }
            }
        }
        
        // 3. Keep existing image if no new upload
        if (isset($_POST['featured_image']) && !empty($_POST['featured_image'])) {
            error_log("Keeping existing image: " . $_POST['featured_image']);
            return $_POST['featured_image'];
        }
        
        error_log("No image uploaded");
        return '';
    }
    
    /**
     * Store new content (news OR event) - UPDATED WITH UNIVERSAL IMAGE UPLOAD
     */
    public function store() {
        error_log("==========================================");
        error_log("=== CONTROLLER store() METHOD CALLED ===");
        error_log("POST method: " . $_SERVER['REQUEST_METHOD']);
        error_log("POST data received:");
        
        // Log all POST data (except sensitive ones)
        foreach ($_POST as $key => $value) {
            if ($key === 'csrf_token' || $key === 'featured_image_data') {
                error_log("  $key: " . (strlen($value) > 0 ? "PRESENT (" . strlen($value) . " chars)" : "EMPTY"));
            } else if ($key === 'content') {
                error_log("  $key: length=" . strlen($value) . " chars");
            } else {
                error_log("  $key: " . (strlen($value) > 0 ? "'$value'" : "EMPTY"));
            }
        }
        
        // Log FILES data
        error_log("FILES data:");
        foreach ($_FILES as $key => $file) {
            error_log("  $key: " . ($file['name'] ?? 'NO FILE'));
        }
        
        error_log("=== ADMIN NEWS STORE METHOD START (WITH UNIVERSAL IMAGE UPLOAD) ===");
        error_log("POST Data: " . json_encode($_POST, JSON_PRETTY_PRINT));
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/admin/news/create');
            return;
        }
        
        try {
            // Validate CSRF token
            $csrfToken = $_POST['csrf_token'] ?? '';
            
            if (!$this->validateCsrfToken()) {
                error_log("CSRF VALIDATION FAILED in store!");
                $this->setFlash('error', 'Invalid security token. Please try again.');
                $this->redirect('/admin/news/create');
                return;
            }
            
            error_log("CSRF validation passed!");
            
            // Remove the used token to prevent replay attacks
            require_once APP_PATH . '/config/session.php';
            Session::removeCSRFToken($csrfToken);
            
            $type = $_POST['type'] ?? 'news';
            $isEvent = ($type === 'event');
            
            // Check if it's a draft save
            $isDraft = isset($_POST['save_draft']) && $_POST['save_draft'] == '1';
            
            // Prepare data for insertion
            $data = [
                'title' => trim($_POST['title'] ?? ''),
                'slug' => $this->generateSlug($_POST['title'] ?? ''),
                'excerpt' => trim($_POST['excerpt'] ?? ''),
                'content' => trim($_POST['content'] ?? ''),
                'category' => $_POST['category'] ?? '',
                'tags' => $_POST['tags'] ?? '',
                'is_published' => $isDraft ? 0 : 1,
                'is_featured' => isset($_POST['is_featured']) ? 1 : 0,
                'is_breaking' => isset($_POST['is_breaking']) ? 1 : 0,
                'meta_title' => $_POST['meta_title'] ?? '',
                'meta_description' => $_POST['meta_description'] ?? '',
                'meta_keywords' => $_POST['meta_keywords'] ?? '',
                'type' => $type,
                'author_id' => $_SESSION['user_id'] ?? 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ];
            
            // ✅ APPLIED FIX: Convert comma-separated tags to JSON format for database constraint
            if (!empty($data['tags'])) {
                $tagsArray = array_map('trim', explode(',', $data['tags']));
                $data['tags'] = json_encode($tagsArray);
            } else {
                $data['tags'] = json_encode([]);
            }
            
            // Add event-specific fields
            if ($isEvent) {
                $data['event_date'] = !empty($_POST['event_date']) ? $_POST['event_date'] : null;
                $data['event_end_date'] = !empty($_POST['event_end_date']) ? $_POST['event_end_date'] : null;
                $data['event_time'] = $_POST['event_time'] ?? '';
                $data['event_location'] = $_POST['event_location'] ?? '';
            }
            
            // ✅ APPLIED UNIVERSAL IMAGE UPLOAD FIX
            $imagePath = $this->handleImageUpload();
            $data['featured_image'] = $imagePath;
            
            // Validation
            if (empty($data['title'])) {
                throw new Exception('Title is required');
            }
            
            if (empty($data['content'])) {
                throw new Exception('Content is required');
            }
            
            if ($isEvent && empty($data['event_date'])) {
                throw new Exception('Event date is required');
            }
            
            // Insert into database using NewsModel
            $id = $this->newsModel->create($data);
            
            if ($id) {
                $contentType = $isEvent ? 'event' : 'news article';
                $message = $isDraft ? 
                    ucfirst($contentType) . ' saved as draft successfully!' : 
                    ucfirst($contentType) . ' created successfully!';
                
                $this->logActivity('content_created', "Created {$contentType} #{$id}: {$data['title']}");
                $this->setFlash('success', $message);
                
                // Redirect to appropriate page
                if ($isDraft) {
                    $this->redirect('/admin/news?status=draft');
                } else {
                    $this->redirect('/admin/news');
                }
            } else {
                throw new Exception('Failed to create ' . ($isEvent ? 'event' : 'news article'));
            }
            
        } catch (Exception $e) {
            error_log("✗✗✗ AdminNewsController store error: " . $e->getMessage());
            error_log("Error trace: " . $e->getTraceAsString());
            
            // Preserve form data for re-population
            $_SESSION['form_data'] = $_POST;
            
            $this->setFlash('error', $e->getMessage());
            $this->redirect('/admin/news/create');
        }
    }
    
    /**
     * Update content (news OR event) - UPDATED WITH UNIVERSAL IMAGE UPLOAD
     */
    public function update($id) {
        error_log("=== UPDATE METHOD CALLED for ID: $id ===");
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect("/admin/news/edit/{$id}");
            return;
        }
        
        try {
            // Validate CSRF token - USING THE FIXED METHOD
            $csrfToken = $_POST['csrf_token'] ?? '';
            
            error_log("CSRF Token from form (update): " . ($csrfToken ? substr($csrfToken, 0, 20) . "..." : "EMPTY"));
            
            if (!$this->validateCsrfToken()) {
                error_log("CSRF VALIDATION FAILED in update!");
                $this->setFlash('error', 'Invalid security token. Please try again.');
                $this->redirect("/admin/news/edit/{$id}");
                return;
            }
            
            error_log("CSRF validation passed!");
            
            // Remove the used token to prevent replay attacks
            require_once APP_PATH . '/config/session.php';
            Session::removeCSRFToken($csrfToken);
            
            $type = $_POST['type'] ?? 'news';
            $isEvent = ($type === 'event');
            
            // Check if it's a draft save
            $isDraft = isset($_POST['save_draft']) && $_POST['save_draft'] == '1';
            
            $data = [
                'title' => trim($_POST['title'] ?? ''),
                'slug' => $this->generateSlug($_POST['slug'] ?? $_POST['title'] ?? '', $id),
                'excerpt' => trim($_POST['excerpt'] ?? ''),
                'content' => trim($_POST['content'] ?? ''),
                'category' => $_POST['category'] ?? '',
                'tags' => $_POST['tags'] ?? '',
                'is_published' => $isDraft ? 0 : 1,
                'is_featured' => isset($_POST['is_featured']) ? 1 : 0,
                'is_breaking' => isset($_POST['is_breaking']) ? 1 : 0,
                'meta_title' => $_POST['meta_title'] ?? '',
                'meta_description' => $_POST['meta_description'] ?? '',
                'meta_keywords' => $_POST['meta_keywords'] ?? '',
                'type' => $type,
                'updated_at' => date('Y-m-d H:i:s')
            ];
            
            // ✅ APPLIED FIX: Convert comma-separated tags to JSON format
            if (!empty($data['tags'])) {
                $tagsArray = array_map('trim', explode(',', $data['tags']));
                $data['tags'] = json_encode($tagsArray);
            } else {
                $data['tags'] = json_encode([]);
            }
            
            // ✅ APPLIED UNIVERSAL IMAGE UPLOAD FIX
            $imagePath = $this->handleImageUpload();
            $data['featured_image'] = $imagePath;
            
            // Add event-specific fields
            if ($isEvent) {
                $data['event_date'] = !empty($_POST['event_date']) ? $_POST['event_date'] : null;
                $data['event_end_date'] = !empty($_POST['event_end_date']) ? $_POST['event_end_date'] : null;
                $data['event_time'] = $_POST['event_time'] ?? '';
                $data['event_location'] = $_POST['event_location'] ?? '';
            }
            
            if (empty($data['title'])) {
                throw new Exception('Title is required');
            }
            
            if (empty($data['content'])) {
                throw new Exception('Content is required');
            }
            
            if ($isEvent && empty($data['event_date'])) {
                throw new Exception('Event date is required');
            }
            
            $success = $this->newsModel->update($id, $data);
            
            if ($success) {
                $contentType = $isEvent ? 'event' : 'news article';
                $message = $isDraft ? 
                    ucfirst($contentType) . ' saved as draft successfully!' : 
                    ucfirst($contentType) . ' updated successfully!';
                
                $this->logActivity('content_updated', "Updated {$contentType} #{$id}: {$data['title']}");
                $this->setFlash('success', $message);
                
                // Redirect to appropriate page
                if ($isDraft) {
                    $this->redirect('/admin/news?status=draft');
                } else {
                    $this->redirect('/admin/news');
                }
            } else {
                throw new Exception('Failed to update ' . ($isEvent ? 'event' : 'news article'));
            }
            
        } catch (Exception $e) {
            error_log("AdminNewsController update error: " . $e->getMessage());
            
            try {
                $categories = $this->getAllCategories();
            } catch (Exception $catError) {
                error_log("Failed to get categories: " . $catError->getMessage());
                $categories = [];
            }
            
            $type = $_POST['type'] ?? 'news';
            
            // Preserve form data
            $newsData = [
                'id' => $id,
                'title' => $_POST['title'] ?? '',
                'slug' => $_POST['slug'] ?? '',
                'excerpt' => $_POST['excerpt'] ?? '',
                'content' => $_POST['content'] ?? '',
                'category' => $_POST['category'] ?? '',
                'tags' => $_POST['tags'] ?? '',
                'featured_image' => $_POST['featured_image'] ?? '',
                'is_published' => isset($_POST['is_published']) ? 1 : 0,
                'is_featured' => isset($_POST['is_featured']) ? 1 : 0,
                'is_breaking' => isset($_POST['is_breaking']) ? 1 : 0,
                'meta_title' => $_POST['meta_title'] ?? '',
                'meta_description' => $_POST['meta_description'] ?? '',
                'meta_keywords' => $_POST['meta_keywords'] ?? '',
                'type' => $type,
                'event_date' => $_POST['event_date'] ?? '',
                'event_end_date' => $_POST['event_end_date'] ?? '',
                'event_time' => $_POST['event_time'] ?? '',
                'event_location' => $_POST['event_location'] ?? ''
            ];
            
            $this->data = array_merge($this->data, [
                'categories' => $categories,
                'type' => $type,
                'error' => $e->getMessage(),
                'news' => $newsData,
                'csrf_token' => $this->generateCsrfToken() // Generate new multi-token
            ]);
            
            $this->setFlash('error', $e->getMessage());
            $this->render('admin/news/edit');
        }
    }
    
    /**
     * Show single content item
     */
    public function show($id) {
        try {
            $news = $this->newsModel->getById($id);
            
            if (!$news) {
                throw new Exception('Content not found');
            }
            
            $this->data = array_merge($this->data, [
                'news' => $news
            ]);
            
            $this->render('admin/news/show');
            
        } catch (Exception $e) {
            error_log("AdminNewsController show error: " . $e->getMessage());
            $this->setFlash('error', $e->getMessage());
            $this->redirect('/admin/news');
        }
    }
    
    /**
     * Delete content item - FIXED CSRF VERSION
     */
    public function destroy($id) {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/admin/news');
            return;
        }
        
        try {
            // Validate CSRF token - USING THE FIXED METHOD
            $csrfToken = $_POST['csrf_token'] ?? '';
            
            if (!$this->validateCsrfToken()) {
                error_log("CSRF VALIDATION FAILED in destroy!");
                $this->setFlash('error', 'Invalid security token. Please try again.');
                $this->redirect('/admin/news');
                return;
            }
            
            // Remove the used token to prevent replay attacks
            require_once APP_PATH . '/config/session.php';
            Session::removeCSRFToken($csrfToken);
            
            $news = $this->newsModel->getById($id);
            
            if (!$news) {
                throw new Exception('Content not found');
            }
            
            $success = $this->newsModel->delete($id);
            
            if ($success) {
                $contentType = isset($news['type']) && $news['type'] === 'event' ? 'event' : 'news article';
                $this->logActivity('content_deleted', "Deleted {$contentType} #{$id}: {$news['title']}");
                $this->setFlash('success', ucfirst($contentType) . ' deleted successfully!');
            } else {
                throw new Exception('Failed to delete content');
            }
            
        } catch (Exception $e) {
            error_log("AdminNewsController destroy error: " . $e->getMessage());
            $this->setFlash('error', $e->getMessage());
        }
        
        $this->redirect('/admin/news');
    }
    
    /**
     * Toggle publish status (AJAX endpoint) - FIXED CSRF VERSION
     */
    public function togglePublish($id) {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['success' => false, 'message' => 'Method not allowed']);
            return;
        }
        
        try {
            // Validate CSRF token for AJAX too - USING THE FIXED METHOD
            $csrfToken = $_POST['csrf_token'] ?? '';
            
            if (!$this->validateCsrfToken()) {
                error_log("CSRF VALIDATION FAILED in togglePublish!");
                echo json_encode(['success' => false, 'message' => 'Invalid CSRF token']);
                return;
            }
            
            // Remove the used token to prevent replay attacks
            require_once APP_PATH . '/config/session.php';
            Session::removeCSRFToken($csrfToken);
            
            $news = $this->newsModel->getById($id);
            if (!$news) {
                http_response_code(404);
                echo json_encode(['success' => false, 'message' => 'Content not found']);
                return;
            }
            
            $newStatus = $news['is_published'] ? 0 : 1;
            $success = $this->newsModel->update($id, ['is_published' => $newStatus]);
            
            echo json_encode([
                'success' => $success,
                'newStatus' => $newStatus,
                'message' => $success ? 'Status updated' : 'Failed to update'
            ]);
            
        } catch (Exception $e) {
            error_log("AdminNewsController togglePublish error: " . $e->getMessage());
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => 'Server error']);
        }
    }
    
    /**
     * Toggle featured status (AJAX endpoint) - FIXED CSRF VERSION
     */
    public function toggleFeature($id) {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['success' => false, 'message' => 'Method not allowed']);
            return;
        }
        
        try {
            // Validate CSRF token for AJAX too - USING THE FIXED METHOD
            $csrfToken = $_POST['csrf_token'] ?? '';
            
            if (!$this->validateCsrfToken()) {
                error_log("CSRF VALIDATION FAILED in toggleFeature!");
                echo json_encode(['success' => false, 'message' => 'Invalid CSRF token']);
                return;
            }
            
            // Remove the used token to prevent replay attacks
            require_once APP_PATH . '/config/session.php';
            Session::removeCSRFToken($csrfToken);
            
            $news = $this->newsModel->getById($id);
            if (!$news) {
                http_response_code(404);
                echo json_encode(['success' => false, 'message' => 'Content not found']);
                return;
            }
            
            $newStatus = $news['is_featured'] ? 0 : 1;
            $success = $this->newsModel->update($id, ['is_featured' => $newStatus]);
            
            echo json_encode([
                'success' => $success,
                'newStatus' => $newStatus,
                'message' => $success ? 'Feature status updated' : 'Failed to update'
            ]);
            
        } catch (Exception $e) {
            error_log("AdminNewsController toggleFeature error: " . $e->getMessage());
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => 'Server error']);
        }
    }
    
    /**
     * Bulk actions - FIXED CSRF VERSION
     */
    public function bulkAction() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/admin/news');
            return;
        }
        
        try {
            // Validate CSRF token - USING THE FIXED METHOD
            $csrfToken = $_POST['csrf_token'] ?? '';
            
            if (!$this->validateCsrfToken()) {
                error_log("CSRF VALIDATION FAILED in bulkAction!");
                $this->setFlash('error', 'Invalid security token. Please try again.');
                $this->redirect('/admin/news');
                return;
            }
            
            // Remove the used token to prevent replay attacks
            require_once APP_PATH . '/config/session.php';
            Session::removeCSRFToken($csrfToken);
            
            $action = $_POST['action'] ?? '';
            $ids = $_POST['ids'] ?? [];
            
            if (empty($action) || empty($ids)) {
                throw new Exception('No action or items selected');
            }
            
            $successCount = 0;
            
            foreach ($ids as $id) {
                $id = (int)$id;
                if ($id <= 0) continue;
                
                switch ($action) {
                    case 'publish':
                        $success = $this->newsModel->update($id, ['is_published' => 1]);
                        break;
                    case 'unpublish':
                        $success = $this->newsModel->update($id, ['is_published' => 0]);
                        break;
                    case 'feature':
                        $success = $this->newsModel->update($id, ['is_featured' => 1]);
                        break;
                    case 'unfeature':
                        $success = $this->newsModel->update($id, ['is_featured' => 0]);
                        break;
                    case 'delete':
                        $success = $this->newsModel->delete($id);
                        break;
                    default:
                        $success = false;
                }
                
                if ($success) $successCount++;
            }
            
            $this->setFlash('success', "Bulk action completed. $successCount item(s) updated.");
            
        } catch (Exception $e) {
            error_log("AdminNewsController bulkAction error: " . $e->getMessage());
            $this->setFlash('error', 'Error: ' . $e->getMessage());
        }
        
        $this->redirect('/admin/news');
    }
    
    /**
     * Generate slug from title
     */
    private function generateSlug($text, $excludeId = null) {
        if (empty($text)) return '';
        
        $slug = strtolower(trim($text));
        $slug = preg_replace('/[^a-z0-9-]/', '-', $slug);
        $slug = preg_replace('/-+/', '-', $slug);
        $slug = trim($slug, '-');
        
        // Check if slug exists in news table
        $counter = 1;
        $originalSlug = $slug;
        
        while ($this->slugExists($slug, $excludeId)) {
            $slug = $originalSlug . '-' . $counter;
            $counter++;
        }
        
        return $slug;
    }
    
    /**
     * Check if slug exists
     */
    private function slugExists($slug, $excludeId = null) {
        try {
            if ($excludeId) {
                $stmt = $this->db->prepare("SELECT COUNT(*) as count FROM news WHERE slug = ? AND id != ?");
                $stmt->execute([$slug, $excludeId]);
            } else {
                $stmt = $this->db->prepare("SELECT COUNT(*) as count FROM news WHERE slug = ?");
                $stmt->execute([$slug]);
            }
            
            $result = $stmt->fetch();
            return ($result['count'] > 0);
        } catch (Exception $e) {
            error_log("Error checking slug: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Set flash message
     */
    private function setFlash($type, $message) {
        $_SESSION["flash_$type"] = $message;
    }
    
    /**
     * Log activity
     */
    private function logActivity($action, $description) {
        try {
            $stmt = $this->db->prepare("
                INSERT INTO activity_logs 
                (user_id, action, description, ip_address, user_agent, created_at)
                VALUES (?, ?, ?, ?, ?, NOW())
            ");
            $stmt->execute([
                $_SESSION['user_id'] ?? null,
                $action,
                $description,
                $_SERVER['REMOTE_ADDR'] ?? '',
                $_SERVER['HTTP_USER_AGENT'] ?? ''
            ]);
        } catch (Exception $e) {
            error_log("Failed to log activity: " . $e->getMessage());
        }
    }
    
    /**
     * Test endpoint to verify image uploads
     */
    public function testImagePaths() {
        error_log("=== TEST IMAGE PATHS ===");
        
        echo "<h1>Image Upload System Test</h1>";
        
        // Test upload directory
        $uploadDir = getProjectRootPath() . '/public/uploads/news/';
        echo "<h2>1. Upload Directory</h2>";
        echo "<p><strong>Path:</strong> " . htmlspecialchars($uploadDir) . "</p>";
        echo "<p><strong>Exists:</strong> " . (is_dir($uploadDir) ? '✅ Yes' : '❌ No') . "</p>";
        echo "<p><strong>Writable:</strong> " . (is_writable($uploadDir) ? '✅ Yes' : '❌ No') . "</p>";
        
        if (is_dir($uploadDir)) {
            $files = scandir($uploadDir);
            $fileCount = count($files) - 2;
            echo "<p><strong>Files found:</strong> " . $fileCount . "</p>";
            
            if ($fileCount > 0) {
                echo "<h3>Existing Files:</h3>";
                echo "<table border='1' cellpadding='5'>";
                echo "<tr><th>Filename</th><th>Size</th><th>URL</th><th>Preview</th></tr>";
                
                foreach ($files as $file) {
                    if ($file !== '.' && $file !== '..') {
                        $filePath = $uploadDir . $file;
                        $fileUrl = BASE_URL . '/uploads/news/' . $file;
                        $fileSize = filesize($filePath);
                        
                        echo "<tr>";
                        echo "<td>" . htmlspecialchars($file) . "</td>";
                        echo "<td>" . number_format($fileSize) . " bytes</td>";
                        echo "<td><a href='" . $fileUrl . "' target='_blank'>" . $fileUrl . "</a></td>";
                        echo "<td><img src='" . $fileUrl . "' style='max-width: 100px; max-height: 60px;'></td>";
                        echo "</tr>";
                    }
                }
                echo "</table>";
            }
        }
        
        // Test database images
        echo "<h2>2. Database Images</h2>";
        try {
            $sql = "SELECT id, title, featured_image, created_at 
                    FROM news 
                    WHERE featured_image IS NOT NULL 
                    AND featured_image != '' 
                    ORDER BY id DESC 
                    LIMIT 10";
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            $articles = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            echo "<p><strong>Articles with images:</strong> " . count($articles) . "</p>";
            
            if (count($articles) > 0) {
                echo "<table border='1' cellpadding='5'>";
                echo "<tr><th>ID</th><th>Title</th><th>Image Path</th><th>Accessible</th><th>Preview</th></tr>";
                
                foreach ($articles as $article) {
                    $imagePath = $article['featured_image'];
                    $fullPath = getProjectRootPath() . '/public' . $imagePath;
                    $imageUrl = BASE_URL . $imagePath;
                    $fileExists = file_exists($fullPath);
                    $isReadable = is_readable($fullPath);
                    
                    echo "<tr>";
                    echo "<td>" . $article['id'] . "</td>";
                    echo "<td>" . htmlspecialchars(substr($article['title'], 0, 30)) . "...</td>";
                    echo "<td>" . htmlspecialchars($imagePath) . "</td>";
                    echo "<td>" . ($fileExists ? '✅ Yes' : '❌ No') . " / " . ($isReadable ? '✅ Readable' : '❌ Not readable') . "</td>";
                    echo "<td>";
                    if ($fileExists && $isReadable) {
                        echo "<img src='" . $imageUrl . "' style='max-width: 80px; max-height: 60px;'>";
                    } else {
                        echo "❌ Cannot display";
                    }
                    echo "</td>";
                    echo "</tr>";
                }
                echo "</table>";
            }
        } catch (Exception $e) {
            echo "<p style='color: red;'>Database error: " . htmlspecialchars($e->getMessage()) . "</p>";
        }
        
        // Test form
        echo "<h2>3. Test Image Upload</h2>";
        echo "<form method='POST' action='" . BASE_URL . "/admin/news/store' enctype='multipart/form-data' style='border: 1px solid #ccc; padding: 20px;'>";
        echo "<input type='hidden' name='csrf_token' value='" . $this->generateCsrfToken() . "'>";
        echo "<h3>Test Form</h3>";
        echo "<p><strong>Title:</strong> <input type='text' name='title' value='Test Article " . date('Y-m-d H:i') . "' required></p>";
        echo "<p><strong>Content:</strong> <textarea name='content' rows='3'>Test content for image upload</textarea></p>";
        echo "<p><strong>Category:</strong> <input type='text' name='category' value='Test'></p>";
        echo "<p><strong>Tags:</strong> <input type='text' name='tags' value='nursing,education'></p>";
        echo "<p><strong>Upload Image:</strong> <input type='file' name='featured_image_upload' id='testImage' accept='image/*'></p>";
        echo "<div id='imagePreview' style='margin-top: 10px;'></div>";
        echo "<button type='submit'>Create Test Article</button>";
        echo "</form>";
        
        exit;
    }

    /**
     * Test endpoint to verify all fixes
     */
    public function testFixes() {
        error_log("=== TESTING ALL FIXES ===");
        
        echo "<h1>News System Fixes Test</h1>";
        
        // Test routes
        echo "<h2>1. Route Testing</h2>";
        echo "<ul>";
        echo "<li>Edit Route: <code>/admin/news/{id}/edit</code> - " . 
             "<a href='" . BASE_URL . "/admin/news/1/edit' target='_blank'>Test</a></li>";
        echo "<li>Update Route: <code>/admin/news/update/{id}</code> - POST only</li>";
        echo "<li>Delete Route: <code>/admin/news/delete/{id}</code> - POST only</li>";
        echo "</ul>";
        
        // Quick test form
        echo "<h2>2. Quick Test Form</h2>";
        echo "<form method='POST' action='" . BASE_URL . "/admin/news/delete/1' style='border: 1px solid #ccc; padding: 15px; margin-bottom: 20px;'>";
        echo "<input type='hidden' name='csrf_token' value='" . $this->generateCsrfToken() . "'>";
        echo "<p>Test delete form (will ask for confirmation)</p>";
        echo "<button type='submit' onclick='return confirm(\"Test delete?\")'>Test Delete Button</button>";
        echo "</form>";
        
        echo "<h3>Test Links:</h3>";
        echo "<ul>";
        echo "<li><a href='" . BASE_URL . "/admin/news/test-images'>Test Image Uploads</a></li>";
        echo "<li><a href='" . BASE_URL . "/admin/news'>Back to News List</a></li>";
        echo "</ul>";
        
        exit;
    }
    
    /**
     * Test database connection and insert
     */
    public function testDb() {
        echo "<h1>Database Test</h1>";
        
        try {
            // Test 1: Direct connection
            $testQuery = $this->db->query("SELECT 1 as test");
            $result = $testQuery->fetch();
            echo "<p style='color: green;'>✓ Database connection successful</p>";
            
            // Test 2: Check news table structure
            echo "<h2>News Table Structure</h2>";
            $stmt = $this->db->query("DESCRIBE news");
            $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            echo "<table border='1' cellpadding='5'>";
            echo "<tr><th>Field</th><th>Type</th><th>Null</th><th>Key</th><th>Default</th><th>Extra</th></tr>";
            foreach ($columns as $col) {
                echo "<tr>";
                echo "<td>" . $col['Field'] . "</td>";
                echo "<td>" . $col['Type'] . "</td>";
                echo "<td>" . $col['Null'] . "</td>";
                echo "<td>" . $col['Key'] . "</td>";
                echo "<td>" . $col['Default'] . "</td>";
                echo "<td>" . $col['Extra'] . "</td>";
                echo "</tr>";
            }
            echo "</table>";
            
            // Test 3: Try direct insert
            echo "<h2>Test Direct Insert</h2>";
            $testData = [
                'title' => 'Test Article ' . time(),
                'slug' => 'test-article-' . time(),
                'excerpt' => 'This is a test article',
                'content' => '<p>Test content</p>',
                'author_id' => 1,
                'category' => 'Test',
                'type' => 'news',
                'tags' => json_encode(['test']),
                'featured_image' => '',
                'is_published' => 1,
                'is_featured' => 0,
                'is_breaking' => 0,
                'meta_title' => 'Test',
                'meta_description' => 'Test',
                'meta_keywords' => 'test'
            ];
            
            $sql = "INSERT INTO news (
                title, slug, excerpt, content, author_id, category, type,
                tags, featured_image, is_published, is_featured, is_breaking,
                meta_title, meta_description, meta_keywords,
                created_at, updated_at
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), NOW())";
            
            $stmt = $this->db->prepare($sql);
            $values = [
                $testData['title'],
                $testData['slug'],
                $testData['excerpt'],
                $testData['content'],
                $testData['author_id'],
                $testData['category'],
                $testData['type'],
                $testData['tags'],
                $testData['featured_image'],
                $testData['is_published'],
                $testData['is_featured'],
                $testData['is_breaking'],
                $testData['meta_title'],
                $testData['meta_description'],
                $testData['meta_keywords']
            ];
            
            echo "<p>SQL: " . htmlspecialchars($sql) . "</p>";
            echo "<p>Values: " . htmlspecialchars(json_encode($values)) . "</p>";
            
            if ($stmt->execute($values)) {
                $id = $this->db->lastInsertId();
                echo "<p style='color: green;'>✓ Direct insert successful! ID: $id</p>";
                
                // Clean up
                $this->db->exec("DELETE FROM news WHERE id = $id");
                echo "<p>Test data cleaned up</p>";
            } else {
                $error = $stmt->errorInfo();
                echo "<p style='color: red;'>✗ Direct insert failed: " . $error[2] . "</p>";
            }
            
            // Test 4: Check NewsModel
            echo "<h2>Test NewsModel</h2>";
            if (method_exists($this->newsModel, 'create')) {
                echo "<p>✓ NewsModel::create() method exists</p>";
                
                // Test with simple data
                $testModelData = [
                    'title' => 'Model Test ' . time(),
                    'slug' => 'model-test-' . time(),
                    'excerpt' => 'Model test',
                    'content' => '<p>Model test content</p>',
                    'author_id' => 1,
                    'category' => 'Test',
                    'type' => 'news',
                    'tags' => json_encode(['test']),
                    'featured_image' => '',
                    'is_published' => 1,
                    'is_featured' => 0,
                    'is_breaking' => 0,
                    'meta_title' => 'Test',
                    'meta_description' => 'Test',
                    'meta_keywords' => 'test'
                ];
                
                $modelId = $this->newsModel->create($testModelData);
                if ($modelId) {
                    echo "<p style='color: green;'>✓ NewsModel::create() successful! ID: $modelId</p>";
                    
                    // Clean up
                    $this->db->exec("DELETE FROM news WHERE id = $modelId");
                    echo "<p>Test data cleaned up</p>";
                } else {
                    echo "<p style='color: red;'>✗ NewsModel::create() failed</p>";
                }
            } else {
                echo "<p style='color: red;'>✗ NewsModel::create() method NOT FOUND</p>";
                echo "<p>Available methods: " . implode(', ', get_class_methods($this->newsModel)) . "</p>";
            }
            
        } catch (Exception $e) {
            echo "<p style='color: red;'>Error: " . $e->getMessage() . "</p>";
            echo "<pre>" . $e->getTraceAsString() . "</pre>";
        }
        
        exit;
    }

    /**
     * Direct database insertion as fallback
     */
    private function insertDirectly($data) {
        error_log("=== FALLBACK: Direct database insertion ===");
        
        try {
            // Build SQL query
            $sql = "INSERT INTO news (
                title, slug, excerpt, content, author_id, category, type,
                tags, featured_image, is_published, is_featured, is_breaking,
                meta_title, meta_description, meta_keywords,
                event_date, event_end_date, event_time, event_location,
                created_at, updated_at
            ) VALUES (
                :title, :slug, :excerpt, :content, :author_id, :category, :type,
                :tags, :featured_image, :is_published, :is_featured, :is_breaking,
                :meta_title, :meta_description, :meta_keywords,
                :event_date, :event_end_date, :event_time, :event_location,
                NOW(), NOW()
            )";
            
            error_log("Direct insert SQL: " . $sql);
            error_log("Data for insert: " . json_encode($data));
            
            $stmt = $this->db->prepare($sql);
            
            // Bind parameters
            $params = [
                ':title' => $data['title'],
                ':slug' => $data['slug'],
                ':excerpt' => $data['excerpt'],
                ':content' => $data['content'],
                ':author_id' => $data['author_id'],
                ':category' => $data['category'],
                ':type' => $data['type'],
                ':tags' => $data['tags'],
                ':featured_image' => $data['featured_image'],
                ':is_published' => $data['is_published'],
                ':is_featured' => $data['is_featured'],
                ':is_breaking' => $data['is_breaking'],
                ':meta_title' => $data['meta_title'],
                ':meta_description' => $data['meta_description'],
                ':meta_keywords' => $data['meta_keywords'],
                ':event_date' => $data['event_date'],
                ':event_end_date' => $data['event_end_date'],
                ':event_time' => $data['event_time'],
                ':event_location' => $data['event_location']
            ];
            
            $success = $stmt->execute($params);
            
            if ($success) {
                $id = $this->db->lastInsertId();
                error_log("Direct insert SUCCESS! ID: $id");
                return $id;
            } else {
                error_log("Direct insert FAILED. Error: " . print_r($stmt->errorInfo(), true));
                return false;
            }
            
        } catch (Exception $e) {
            error_log("Direct insertion error: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Test endpoint to verify model works
     */
    public function testModel() {
        error_log("=== TEST MODEL ENDPOINT ===");
        
        echo "<h1>Model Test Results</h1>";
        
        try {
            // Test 1: Create a test news article
            $testNewsData = [
                'title' => 'Test News ' . date('Y-m-d H:i:s'),
                'slug' => 'test-news-' . time(),
                'excerpt' => 'This is a test news article',
                'content' => '<p>Test content for news article</p>',
                'author_id' => $_SESSION['user_id'] ?? 1,
                'category' => 'Test',
                'type' => 'news',
                'tags' => json_encode(['test', 'debug']),
                'featured_image' => '',
                'is_published' => 1,
                'is_featured' => 0,
                'is_breaking' => 0,
                'meta_title' => 'Test News',
                'meta_description' => 'Test news article',
                'meta_keywords' => 'test'
            ];
            
            error_log("Testing NewsModel with data: " . json_encode($testNewsData));
            
            $newsId = $this->newsModel->create($testNewsData);
            
            if ($newsId) {
                echo "<p style='color: green;'>✓ News created successfully! ID: $newsId</p>";
                
                // Verify it exists
                $check = $this->db->prepare("SELECT id, title FROM news WHERE id = ?");
                $check->execute([$newsId]);
                $result = $check->fetch();
                
                if ($result) {
                    echo "<p>✓ Verified in database: {$result['title']}</p>";
                    
                    // Clean up test data
                    $delete = $this->db->prepare("DELETE FROM news WHERE id = ?");
                    $delete->execute([$newsId]);
                    echo "<p>✓ Test data cleaned up</p>";
                } else {
                    echo "<p style='color: red;'>✗ News not found in database after creation</p>";
                }
            } else {
                echo "<p style='color: red;'>✗ Failed to create news</p>";
            }
            
            // Test 2: Create a test event
            $testEventData = [
                'title' => 'Test Event ' . date('Y-m-d H:i:s'),
                'slug' => 'test-event-' . time(),
                'excerpt' => 'This is a test event',
                'content' => '<p>Test content for event</p>',
                'author_id' => $_SESSION['user_id'] ?? 1,
                'category' => 'Test',
                'type' => 'event',
                'tags' => json_encode(['test', 'event']),
                'featured_image' => '',
                'is_published' => 1,
                'is_featured' => 0,
                'is_breaking' => 0,
                'meta_title' => 'Test Event',
                'meta_description' => 'Test event description',
                'meta_keywords' => 'test,event',
                'event_date' => date('Y-m-d'),
                'event_end_date' => date('Y-m-d', strtotime('+1 day')),
                'event_time' => '14:00:00',
                'event_location' => 'Test Location'
            ];
            
            error_log("Testing NewsModel with event data: " . json_encode($testEventData));
            
            $eventId = $this->newsModel->create($testEventData);
            
            if ($eventId) {
                echo "<p style='color: green;'>✓ Event created successfully! ID: $eventId</p>";
                
                // Verify it exists
                $check = $this->db->prepare("SELECT id, title FROM news WHERE id = ? AND type = 'event'");
                $check->execute([$eventId]);
                $result = $check->fetch();
                
                if ($result) {
                    echo "<p>✓ Verified in database: {$result['title']}</p>";
                    
                    // Clean up test data
                    $delete = $this->db->prepare("DELETE FROM news WHERE id = ?");
                    $delete->execute([$eventId]);
                    echo "<p>✓ Test data cleaned up</p>";
                } else {
                    echo "<p style='color: red;'>✗ Event not found in database after creation</p>";
                }
            } else {
                echo "<p style='color: red;'>✗ Failed to create event</p>";
            }
            
            // Test 3: Check tables
            echo "<h2>Database Status</h2>";
            
            $tables = ['news'];
            foreach ($tables as $table) {
                $count = $this->db->query("SELECT COUNT(*) as count FROM $table")->fetch()['count'];
                $newsCount = $this->db->query("SELECT COUNT(*) as count FROM $table WHERE type = 'news' OR type IS NULL")->fetch()['count'];
                $eventCount = $this->db->query("SELECT COUNT(*) as count FROM $table WHERE type = 'event'")->fetch()['count'];
                
                echo "<p><strong>$table table:</strong> $count total records</p>";
                echo "<p>  - News articles: $newsCount</p>";
                echo "<p>  - Events: $eventCount</p>";
            }
            
            // Test 4: Test model methods
            echo "<h2>Model Method Tests</h2>";
            
            if (method_exists($this->newsModel, 'countPublishedNews')) {
                $publishedCount = $this->newsModel->countPublishedNews();
                echo "<p>Published news count: $publishedCount</p>";
            }
            
            if (method_exists($this->newsModel, 'getAllCategories')) {
                $categories = $this->newsModel->getAllCategories();
                echo "<p>Categories found: " . implode(', ', $categories) . "</p>";
            }
            
            if (method_exists($this->newsModel, 'getStats')) {
                $stats = $this->newsModel->getStats();
                echo "<p>Total content: " . $stats['total'] . "</p>";
            }
            
        } catch (Exception $e) {
            echo "<p style='color: red;'>Error: " . $e->getMessage() . "</p>";
            echo "<pre>" . $e->getTraceAsString() . "</pre>";
        }
        
        exit;
    }
    
    /**
     * Test database insert
     */
    public function testDbInsert() {
        try {
            $testData = [
                'title' => 'Test Article ' . time(),
                'slug' => 'test-article-' . time(),
                'excerpt' => 'This is a test article',
                'content' => '<p>This is test content</p>',
                'category' => 'Test',
                'type' => 'news',
                'featured_image' => '',
                'is_published' => 1,
                'is_featured' => 0,
                'is_breaking' => 0,
                'author_id' => $_SESSION['user_id'] ?? 1,
                'meta_title' => 'Test Article',
                'meta_description' => 'Test description',
                'meta_keywords' => 'test',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ];
            
            $id = $this->newsModel->create($testData);
            
            if ($id) {
                echo "Success! Inserted test article with ID: $id";
            } else {
                echo "Failed to insert test article";
            }
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
        }
        exit;
    }

    /**
     * Direct test bypassing form validation
     */
    public function testDirectCreate() {
        echo "<h1>Direct Create Test</h1>";
        
        try {
            // Direct test data
            $testData = [
                'title' => 'Direct Test ' . date('Y-m-d H:i:s'),
                'slug' => 'direct-test-' . time(),
                'excerpt' => 'Test excerpt',
                'content' => '<p>Test content</p>',
                'author_id' => $_SESSION['user_id'] ?? 1,
                'category' => 'Test',
                'type' => 'news',
                'tags' => json_encode(['test']),
                'featured_image' => '',
                'is_published' => 1,
                'is_featured' => 0,
                'is_breaking' => 0,
                'meta_title' => 'Test',
                'meta_description' => 'Test description',
                'meta_keywords' => 'test',
                'featured_image_data' => '',
                'featured_image_filename' => ''
            ];
            
            echo "<h2>Test Data:</h2>";
            echo "<pre>" . htmlspecialchars(print_r($testData, true)) . "</pre>";
            
            // Test 1: Direct model call
            echo "<h2>Test 1: Direct Model create()</h2>";
            $id1 = $this->newsModel->create($testData);
            echo "<p>Result: " . ($id1 ? "Success - ID: $id1" : "Failed") . "</p>";
            
            // Test 2: Direct createNews() call
            echo "<h2>Test 2: Direct createNews()</h2>";
            $id2 = $this->newsModel->createNews($testData);
            echo "<p>Result: " . ($id2 ? "Success - ID: $id2" : "Failed") . "</p>";
            
            // Test 3: Direct SQL
            echo "<h2>Test 3: Direct SQL Insert</h2>";
            $sql = "INSERT INTO news (
                title, slug, excerpt, content, author_id, category, type,
                tags, featured_image, is_published, is_featured, is_breaking,
                meta_title, meta_description, meta_keywords,
                created_at, updated_at
            ) VALUES (
                'SQL Test " . time() . "',
                'sql-test-" . time() . "',
                'SQL excerpt',
                '<p>SQL content</p>',
                1,
                'Test',
                'news',
                '" . json_encode(['test']) . "',
                '',
                1,
                0,
                0,
                'Test',
                'Test desc',
                'test',
                NOW(),
                NOW()
            )";
            
            echo "<p>SQL: <code>" . htmlspecialchars($sql) . "</code></p>";
            
            $stmt = $this->db->prepare($sql);
            $success = $stmt->execute();
            
            if ($success) {
                $id3 = $this->db->lastInsertId();
                echo "<p style='color: green;'>✓ SQL insert successful! ID: $id3</p>";
                
                // Verify
                $check = $this->db->prepare("SELECT id, title FROM news WHERE id = ?");
                $check->execute([$id3]);
                $result = $check->fetch();
                
                if ($result) {
                    echo "<p>✓ Verified: ID {$result['id']}, Title: {$result['title']}</p>";
                }
                
                // Clean up
                $delete = $this->db->prepare("DELETE FROM news WHERE id = ?");
                $delete->execute([$id3]);
                echo "<p>✓ Test data cleaned up</p>";
            } else {
                $error = $stmt->errorInfo();
                echo "<p style='color: red;'>✗ SQL insert failed:</p>";
                echo "<pre>" . print_r($error, true) . "</pre>";
            }
            
            // Check current count
            $count = $this->db->query("SELECT COUNT(*) as count FROM news")->fetch()['count'];
            echo "<h2>Current Database Status</h2>";
            echo "<p>Total articles in database: $count</p>";
            
        } catch (Exception $e) {
            echo "<p style='color: red;'>Error: " . $e->getMessage() . "</p>";
            echo "<pre>" . $e->getTraceAsString() . "</pre>";
        }
        
        exit;
    }

    /**
     * FIX 4: Test database insertion for both news and events
     */
    public function testBothInserts() {
        error_log("=== TEST BOTH INSERTS ===");
        
        echo "<h1>Testing Database Insertion</h1>";
        
        try {
            // Test 1: Create news article
            $newsData = [
                'title' => 'Test News ' . date('Y-m-d H:i:s'),
                'slug' => 'test-news-' . time(),
                'excerpt' => 'This is a test news article',
                'content' => '<p>Test content for news</p>',
                'author_id' => $_SESSION['user_id'] ?? 1,
                'category' => 'Test',
                'type' => 'news',
                'tags' => json_encode(['test', 'news']),
                'featured_image' => '',
                'is_published' => 1,
                'is_featured' => 0,
                'is_breaking' => 0,
                'meta_title' => 'Test News',
                'meta_description' => 'Test news description',
                'meta_keywords' => 'test'
            ];
            
            echo "<h2>Test 1: Creating News Article</h2>";
            $newsId = $this->newsModel->create($newsData);
            
            if ($newsId) {
                echo "<p style='color: green;'>✓ News created successfully! ID: $newsId</p>";
                
                // Verify
                $check = $this->db->prepare("SELECT id, title, type FROM news WHERE id = ?");
                $check->execute([$newsId]);
                $result = $check->fetch();
                
                if ($result) {
                    echo "<p>✓ Verified: ID {$result['id']}, Title: {$result['title']}, Type: {$result['type']}</p>";
                }
                
                // Clean up
                $delete = $this->db->prepare("DELETE FROM news WHERE id = ?");
                $delete->execute([$newsId]);
                echo "<p>✓ Test news cleaned up</p>";
            } else {
                echo "<p style='color: red;'>✗ Failed to create news</p>";
            }
            
            // Test 2: Create event
            $eventData = [
                'title' => 'Test Event ' . date('Y-m-d H:i:s'),
                'slug' => 'test-event-' . time(),
                'excerpt' => 'This is a test event',
                'content' => '<p>Test content for event</p>',
                'author_id' => $_SESSION['user_id'] ?? 1,
                'category' => 'Test',
                'type' => 'event',
                'tags' => json_encode(['test', 'event']),
                'featured_image' => '',
                'is_published' => 1,
                'is_featured' => 0,
                'is_breaking' => 0,
                'meta_title' => 'Test Event',
                'meta_description' => 'Test event description',
                'meta_keywords' => 'test,event',
                'event_date' => date('Y-m-d'),
                'event_end_date' => date('Y-m-d', strtotime('+1 day')),
                'event_time' => '14:00:00',
                'event_location' => 'Test Auditorium'
            ];
            
            echo "<h2>Test 2: Creating Event</h2>";
            $eventId = $this->newsModel->create($eventData);
            
            if ($eventId) {
                echo "<p style='color: green;'>✓ Event created successfully! ID: $eventId</p>";
                
                // Verify
                $check = $this->db->prepare("SELECT id, title, type, event_date, event_location FROM news WHERE id = ? AND type = 'event'");
                $check->execute([$eventId]);
                $result = $check->fetch();
                
                if ($result) {
                    echo "<p>✓ Verified: ID {$result['id']}, Title: {$result['title']}, Type: {$result['type']}</p>";
                    echo "<p>✓ Event Date: {$result['event_date']}, Location: {$result['event_location']}</p>";
                }
                
                // Clean up
                $delete = $this->db->prepare("DELETE FROM news WHERE id = ?");
                $delete->execute([$eventId]);
                echo "<p>✓ Test event cleaned up</p>";
            } else {
                echo "<p style='color: red;'>✗ Failed to create event</p>";
            }
            
            // Test 3: Check current data
            echo "<h2>Current Database Status</h2>";
            
            $totalNews = $this->db->query("SELECT COUNT(*) as count FROM news WHERE type = 'news' OR type IS NULL")->fetch()['count'];
            $totalEvents = $this->db->query("SELECT COUNT(*) as count FROM news WHERE type = 'event'")->fetch()['count'];
            
            echo "<p>Total news articles: $totalNews</p>";
            echo "<p>Total events: $totalEvents</p>";
            
            // Show recent entries
            $recent = $this->db->query("SELECT id, title, type, created_at FROM news ORDER BY created_at DESC LIMIT 5")->fetchAll();
            
            echo "<h3>Recent Entries:</h3>";
            echo "<table border='1' cellpadding='5'>";
            echo "<tr><th>ID</th><th>Title</th><th>Type</th><th>Created</th></tr>";
            foreach ($recent as $row) {
                echo "<tr>";
                echo "<td>{$row['id']}</td>";
                echo "<td>{$row['title']}</td>";
                echo "<td>{$row['type']}</td>";
                echo "<td>{$row['created_at']}</td>";
                echo "</tr>";
            }
            echo "</table>";
            
        } catch (Exception $e) {
            echo "<p style='color: red;'>Error: " . $e->getMessage() . "</p>";
            echo "<pre>" . $e->getTraceAsString() . "</pre>";
        }
        
        exit;
    }
}