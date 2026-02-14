<?php
/**
 * News Manager Controller
 * Handles news operations for news managers with appropriate permissions
 * - News Manager: Limited access to /admin/news-manager
 * 
 * Extends base Controller class and uses its methods
 */
class NewsManagerController extends Controller {
    
    private $db;
    private $newsModel;
    private $userRole;
    private $userId;
    private $permissions = [];
    
    public function __construct() {
        parent::__construct();
        
        error_log("=== NEWS MANAGER CONTROLLER CONSTRUCTOR ===");
        
        // Set default layout
        $this->layout = 'default';
        
        // Require authentication
        $this->requireAuth();
        
        // Setup database
        require_once APP_PATH . '/config/database.php';
        $database = Database::getInstance();
        $this->db = $database->getConnection();
        
        // Initialize NewsModel
        require_once APP_PATH . '/models/NewsModel.php';
        $this->newsModel = new NewsModel($this->db);
        
        // Get user info
        $this->userId = $_SESSION['user_id'] ?? 0;
        $this->userRole = $_SESSION['user_role'] ?? 'guest';
        
        // Load permissions
        $this->loadPermissions();
        
        // Common data
        $this->data = array_merge($this->data, [
            'user' => $_SESSION ?? [],
            'baseUrl' => $this->getBaseUrl(),
            'currentPage' => 'news-manager',
            'userRole' => $this->userRole,
            'permissions' => $this->permissions,
            'hasPermission' => [$this, 'hasPermission']
        ]);
    }
    
    /**
     * Load user permissions
     */
    private function loadPermissions() {
        try {
            // Get permissions from database
            $sql = "SELECT DISTINCT p.name FROM permissions p
                    JOIN role_permissions rp ON p.id = rp.permission_id
                    JOIN roles r ON rp.role_id = r.id
                    JOIN user_roles ur ON r.id = ur.role_id
                    WHERE ur.user_id = ?";
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$this->userId]);
            $this->permissions = $stmt->fetchAll(PDO::FETCH_COLUMN);
            
            // Add role-based permissions for news manager
            if ($this->userRole === 'news_manager') {
                $this->permissions = array_merge($this->permissions, [
                    'news_view', 'news_create', 'news_edit', 'news_manage_categories'
                ]);
            } elseif (in_array($this->userRole, ['admin', 'super_admin', 'editor'])) {
                // If they have higher roles, give them more permissions
                $this->permissions = array_merge($this->permissions, [
                    'news_view', 'news_create', 'news_edit', 'news_publish',
                    'news_manage_categories', 'news_upload_images'
                ]);
            }
            
            $this->permissions = array_unique($this->permissions);
            
        } catch (Exception $e) {
            error_log("Error loading permissions: " . $e->getMessage());
            $this->permissions = ['news_view', 'news_create', 'news_edit'];
        }
    }
    
    /**
     * Check if user has specific permission
     */
    private function hasPermission($permission) {
        // Admin always has all permissions
        if (in_array($this->userRole, ['admin', 'super_admin', 'editor'])) {
            return true;
        }
        
        return in_array($permission, $this->permissions);
    }
    
    /**
     * Check authentication
     */
    private function requireAuth() {
        if (!isset($_SESSION['user_id']) || !$_SESSION['user_id']) {
            if ($this->isAjax()) {
                $this->json(['error' => 'Unauthorized'], 401);
                exit;
            }
            $this->flash('error', 'Please login to access this page');
            $this->redirect('/login');
            exit;
        }
    }
    
    /**
     * Generate CSRF token
     */
    private function generateCsrfToken() {
        if (function_exists('csrf_token')) {
            return csrf_token();
        }
        
        if (!isset($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['csrf_token'];
    }
    
    /**
     * Validate CSRF token
     */
    protected function validateCsrfToken() {
        $token = $_POST['csrf_token'] ?? '';
        $sessionToken = $_SESSION['csrf_token'] ?? '';
        
        if (empty($token) || empty($sessionToken)) {
            return false;
        }
        
        return hash_equals($sessionToken, $token);
    }
    
    /**
     * Get base URL
     */
    private function getBaseUrl() {
        $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' ? 'https' : 'http';
        $host = $_SERVER['HTTP_HOST'];
        $scriptDir = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME']));
        return rtrim($protocol . '://' . $host . $scriptDir, '/');
    }
    
    /**
     * Check if AJAX request
     */
    private function isAjax() {
        return !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && 
               strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';
    }
    
    /**
     * Set flash message
     */
    private function setFlash($type, $message) {
        $_SESSION["flash_{$type}"] = $message;
        $this->data["flash_{$type}"] = $message;
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
                $this->userId,
                $action,
                $description,
                $_SERVER['REMOTE_ADDR'] ?? '',
                $_SERVER['HTTP_USER_AGENT'] ?? ''
            ]);
        } catch (Exception $e) {
            error_log("Failed to log activity: " . $e->getMessage());
        }
    }
    
    // ============================================
    // MAIN DASHBOARD - /admin/news-manager
    // ============================================
    
    /**
     * News manager dashboard with full CRUD functionality
     */
    public function index() {
        error_log("=== NEWS MANAGER INDEX CALLED ===");
        
        try {
            // Check permission
            if (!$this->hasPermission('news_view')) {
                $this->setFlash('error', 'Access denied');
                $this->redirect('/dashboard');
                return;
            }
            
            // Get filter parameters (same as admin)
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
            
            error_log("Filters: " . print_r($filters, true));
            
            // Get pagination
            $page = max(1, (int)($_GET['page'] ?? 1));
            $limit = 15; // Slightly smaller than admin for news manager
            $offset = ($page - 1) * $limit;
            
            // Get content with filters
            $content = $this->newsModel->getAllWithFilters($filters, $limit, $offset);
            $total = $this->newsModel->countAllWithFilters($filters);
            $totalPages = ceil($total / $limit);
            
            // Get comprehensive stats (same as admin but filtered for news manager's scope)
            $stats = $this->getManagerStats();
            
            // Get categories with counts
            $categories = $this->getCategoriesWithCounts();
            
            // Get draft articles
            $draftFilters = ['status' => 'draft'];
            $draftArticles = $this->newsModel->getAllWithFilters($draftFilters, 5, 0);
            
            // Get popular articles
            $popularArticles = $this->newsModel->getPopularNews(5);
            
            // Get upcoming events
            $upcomingEvents = $this->newsModel->getUpcomingEvents(5);
            
            // Get recent activity
            $recentActivity = $this->getRecentActivity();
            
            // Prepare flash messages
            $flashSuccess = $_SESSION['flash_success'] ?? '';
            $flashError = $_SESSION['flash_error'] ?? '';
            unset($_SESSION['flash_success'], $_SESSION['flash_error']);
            
            // Set all data for the view (mirroring admin pattern)
            $this->data = array_merge($this->data, [
                'content' => $content,
                'stats' => $stats,
                'categories' => $categories,
                'draftArticles' => $draftArticles,
                'popularArticles' => $popularArticles,
                'upcomingEvents' => $upcomingEvents,
                'recentActivity' => $recentActivity,
                'filters' => $filters,
                'pagination' => [
                    'current' => $page,
                    'total' => $totalPages,
                    'limit' => $limit,
                    'totalCount' => $total
                ],
                'csrf_token' => $this->generateCsrfToken(),
                'flash_success' => $flashSuccess,
                'flash_error' => $flashError,
                'pageTitle' => 'News Manager Dashboard',
                'canCreate' => $this->hasPermission('news_create'),
                'canEdit' => $this->hasPermission('news_edit'),
                'canDelete' => $this->hasPermission('news_delete'),
                'canPublish' => $this->hasPermission('news_publish'),
                'canManageCategories' => $this->hasPermission('news_manage_categories')
            ]);
            
            error_log("Data prepared for view:");
            error_log("  - content count: " . count($content));
            error_log("  - stats total: " . ($stats['total'] ?? 0));
            error_log("  - total pages: " . $totalPages);
            
            // Render the news manager index view
            $this->render('news-manager/index');
            
        } catch (Exception $e) {
            error_log("NewsManagerController index error: " . $e->getMessage());
            
            // Prepare error fallback data
            $this->data = array_merge($this->data, [
                'content' => [],
                'stats' => $this->getDefaultStats(),
                'categories' => [],
                'draftArticles' => [],
                'popularArticles' => [],
                'upcomingEvents' => [],
                'recentActivity' => [],
                'filters' => [],
                'pagination' => [
                    'current' => 1,
                    'total' => 0,
                    'limit' => 15,
                    'totalCount' => 0
                ],
                'csrf_token' => $this->generateCsrfToken(),
                'flash_error' => 'Error loading dashboard: ' . $e->getMessage(),
                'pageTitle' => 'News Manager Dashboard'
            ]);
            
            $this->render('news-manager/index');
        }
    }
    
    /**
     * Get comprehensive stats for news manager (mirrors admin but with manager scope)
     */
    private function getManagerStats() {
        error_log("=== getManagerStats called ===");
        
        $stats = [];
        
        $queries = [
            'total' => "SELECT COUNT(*) as total FROM news",
            'published' => "SELECT COUNT(*) as total FROM news WHERE is_published = 1",
            'draft' => "SELECT COUNT(*) as total FROM news WHERE is_published = 0",
            'featured' => "SELECT COUNT(*) as total FROM news WHERE is_featured = 1",
            'news' => "SELECT COUNT(*) as total FROM news WHERE (type = 'news' OR type IS NULL) AND is_published = 1",
            'events' => "SELECT COUNT(*) as total FROM news WHERE type = 'event' AND is_published = 1",
            'breaking' => "SELECT COUNT(*) as total FROM news WHERE is_breaking = 1",
            'views' => "SELECT COALESCE(SUM(views_count), 0) as total FROM news",
            'this_month' => "SELECT COUNT(*) as total FROM news WHERE MONTH(created_at) = MONTH(CURDATE()) AND YEAR(created_at) = YEAR(CURDATE())",
            'this_week' => "SELECT COUNT(*) as total FROM news WHERE YEARWEEK(created_at) = YEARWEEK(CURDATE())",
            'categories' => "SELECT COUNT(DISTINCT category) as total FROM news WHERE category IS NOT NULL AND category != ''",
            'upcoming_events' => "SELECT COUNT(*) as total FROM news WHERE type = 'event' AND is_published = 1 AND (event_date >= CURDATE() OR event_date IS NULL)"
        ];
        
        foreach ($queries as $key => $sql) {
            try {
                error_log("Running stats query for $key");
                $stmt = $this->db->query($sql);
                $stats[$key] = (int)($stmt->fetch()['total'] ?? 0);
                error_log("  Result: " . $stats[$key]);
            } catch (Exception $e) {
                error_log("Stats query error for $key: " . $e->getMessage());
                $stats[$key] = 0;
            }
        }
        
        return $stats;
    }
    
    /**
     * Get default stats for error fallback
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
            'views' => 0,
            'this_month' => 0,
            'this_week' => 0,
            'categories' => 0,
            'upcoming_events' => 0
        ];
    }
    
    /**
     * Get categories with counts
     */
    private function getCategoriesWithCounts() {
        try {
            $sql = "SELECT 
                        category,
                        COUNT(*) as count,
                        SUM(CASE WHEN is_published = 1 THEN 1 ELSE 0 END) as published_count
                    FROM news 
                    WHERE category IS NOT NULL AND category != ''
                    GROUP BY category 
                    ORDER BY category";
            $stmt = $this->db->query($sql);
            $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            return $categories ?: [];
            
        } catch (Exception $e) {
            error_log("Error getting categories with counts: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Get recent activity for dashboard
     */
    private function getRecentActivity($limit = 10) {
        try {
            $sql = "SELECT 
                        n.id,
                        n.title,
                        n.type,
                        n.created_at,
                        n.updated_at,
                        'created' as action,
                        u.full_name as user_name,
                        u.username
                    FROM news n
                    LEFT JOIN users u ON n.author_id = u.id
                    WHERE n.created_at >= DATE_SUB(NOW(), INTERVAL 30 DAY)
                    ORDER BY n.created_at DESC
                    LIMIT ?";
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$limit]);
            $activities = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            $formatted = [];
            foreach ($activities as $act) {
                $type = ucfirst($act['type'] ?? 'article');
                $user = $act['user_name'] ?? $act['username'] ?? 'System';
                $title = strlen($act['title'] ?? '') > 40 ? substr($act['title'], 0, 40) . '...' : ($act['title'] ?? 'Untitled');
                
                $formatted[] = [
                    'id' => $act['id'],
                    'text' => "{$user} created {$type}: {$title}",
                    'time' => $this->timeAgo($act['created_at']),
                    'icon' => 'plus-circle',
                    'type' => 'create'
                ];
            }
            
            // Also get recent updates
            $sql = "SELECT 
                        n.id,
                        n.title,
                        n.type,
                        n.updated_at,
                        'updated' as action,
                        u.full_name as user_name,
                        u.username
                    FROM news n
                    LEFT JOIN users u ON n.author_id = u.id
                    WHERE n.updated_at >= DATE_SUB(NOW(), INTERVAL 30 DAY)
                    AND n.updated_at > n.created_at
                    ORDER BY n.updated_at DESC
                    LIMIT ?";
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$limit]);
            $updates = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            foreach ($updates as $act) {
                $type = ucfirst($act['type'] ?? 'article');
                $user = $act['user_name'] ?? $act['username'] ?? 'System';
                $title = strlen($act['title'] ?? '') > 40 ? substr($act['title'], 0, 40) . '...' : ($act['title'] ?? 'Untitled');
                
                $formatted[] = [
                    'id' => $act['id'],
                    'text' => "{$user} updated {$type}: {$title}",
                    'time' => $this->timeAgo($act['updated_at']),
                    'icon' => 'pencil-alt',
                    'type' => 'update'
                ];
            }
            
            // Sort by time
            usort($formatted, function($a, $b) {
                return strtotime($b['time']) - strtotime($a['time']);
            });
            
            return array_slice($formatted, 0, $limit);
            
        } catch (Exception $e) {
            error_log("getRecentActivity error: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Time ago helper
     */
    private function timeAgo($datetime) {
        $time = strtotime($datetime);
        $now = time();
        $diff = $now - $time;
        
        if ($diff < 60) return 'just now';
        if ($diff < 3600) return floor($diff / 60) . ' minutes ago';
        if ($diff < 86400) return floor($diff / 3600) . ' hours ago';
        if ($diff < 2592000) return floor($diff / 86400) . ' days ago';
        
        return date('M d, Y', $time);
    }
    
    // ============================================
    // CREATE METHODS (mirroring admin)
    // ============================================
    
    /**
     * Show create form - /admin/news-manager/create
     */
    public function create() {
        error_log("=== NEWS MANAGER CREATE METHOD CALLED ===");
        
        if (!$this->hasPermission('news_create')) {
            $this->setFlash('error', 'Access denied');
            $this->redirect('/admin/news-manager');
            return;
        }
        
        try {
            $type = $_GET['type'] ?? 'news';
            $categories = $this->getAllCategories();
            
            // Check for preserved form data
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
                    'type' => $type,
                    'event_date' => date('Y-m-d'),
                    'event_end_date' => '',
                    'event_time' => '',
                    'event_location' => '',
                    'meta_title' => '',
                    'meta_description' => '',
                    'meta_keywords' => ''
                ];
            }
            
            $this->data = array_merge($this->data, [
                'categories' => $categories,
                'type' => $type,
                'news' => $newsData,
                'csrf_token' => $this->generateCsrfToken(),
                'pageTitle' => $type === 'event' ? 'Create Event' : 'Create News'
            ]);
            
            $this->render('news-manager/create');
            
        } catch (Exception $e) {
            error_log("Create error: " . $e->getMessage());
            $this->setFlash('error', 'Failed to load form: ' . $e->getMessage());
            $this->redirect('/admin/news-manager');
        }
    }
    
    /**
     * Store new content - /admin/news-manager/store
     */
    public function store() {
        error_log("=== NEWS MANAGER STORE METHOD CALLED ===");
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/admin/news-manager');
            return;
        }
        
        if (!$this->hasPermission('news_create')) {
            $this->setFlash('error', 'Access denied');
            $this->redirect('/admin/news-manager');
            return;
        }
        
        try {
            // Validate CSRF token
            if (!$this->validateCsrfToken()) {
                error_log("CSRF VALIDATION FAILED in store!");
                $this->setFlash('error', 'Invalid security token. Please try again.');
                $this->redirect('/admin/news-manager/create?type=' . ($_POST['type'] ?? 'news'));
                return;
            }
            
            $type = $_POST['type'] ?? 'news';
            $isEvent = ($type === 'event');
            $isDraft = isset($_POST['save_draft']) && $_POST['save_draft'] == '1';
            
            // Prepare data
            $data = [
                'title' => trim($_POST['title'] ?? ''),
                'slug' => $this->generateSlug($_POST['title'] ?? ''),
                'excerpt' => trim($_POST['excerpt'] ?? ''),
                'content' => trim($_POST['content'] ?? ''),
                'category' => $_POST['category'] ?? '',
                'tags' => $this->processTags($_POST['tags'] ?? ''),
                'is_published' => $isDraft ? 0 : 1,
                'is_featured' => isset($_POST['is_featured']) ? 1 : 0,
                'is_breaking' => isset($_POST['is_breaking']) ? 1 : 0,
                'meta_title' => $_POST['meta_title'] ?? '',
                'meta_description' => $_POST['meta_description'] ?? '',
                'meta_keywords' => $_POST['meta_keywords'] ?? '',
                'type' => $type,
                'author_id' => $this->userId
            ];
            
            // Handle image upload
            $data['featured_image'] = $this->handleImageUpload();
            
            // Add event fields
            if ($isEvent) {
                $data['event_date'] = $_POST['event_date'] ?? null;
                $data['event_end_date'] = $_POST['event_end_date'] ?? null;
                $data['event_time'] = $_POST['event_time'] ?? '';
                $data['event_location'] = $_POST['event_location'] ?? '';
            }
            
            // Validate
            if (empty($data['title'])) throw new Exception('Title is required');
            if (empty($data['content'])) throw new Exception('Content is required');
            if ($isEvent && empty($data['event_date'])) throw new Exception('Event date is required');
            
            $id = $this->newsModel->create($data);
            
            if ($id) {
                $contentType = $isEvent ? 'Event' : 'News article';
                $message = $isDraft ? 
                    ucfirst($contentType) . ' saved as draft successfully!' : 
                    ucfirst($contentType) . ' created successfully!';
                
                $this->logActivity('content_created', "Created {$contentType} #{$id}: {$data['title']}");
                $this->setFlash('success', $message);
                
                // Redirect based on action
                if (isset($_POST['save_and_continue'])) {
                    $this->redirect('/admin/news-manager/' . $id . '/edit');
                } else {
                    $this->redirect('/admin/news-manager');
                }
            } else {
                throw new Exception('Failed to create content');
            }
            
        } catch (Exception $e) {
            error_log("Store error: " . $e->getMessage());
            
            // Preserve form data
            $_SESSION['form_data'] = $_POST;
            $this->setFlash('error', $e->getMessage());
            $this->redirect('/admin/news-manager/create?type=' . ($_POST['type'] ?? 'news'));
        }
    }
    
    // ============================================
    // EDIT METHODS
    // ============================================
    
    /**
     * Show edit form - /admin/news-manager/{id}/edit
     */
    public function edit($id) {
        error_log("=== NEWS MANAGER EDIT METHOD CALLED for ID: $id ===");
        
        if (!$this->hasPermission('news_edit')) {
            $this->setFlash('error', 'Access denied');
            $this->redirect('/admin/news-manager');
            return;
        }
        
        try {
            $news = $this->newsModel->getById($id);
            
            if (!$news) {
                throw new Exception('Content not found');
            }
            
            $categories = $this->getAllCategories();
            $type = $news['type'] ?? 'news';
            
            $this->data = array_merge($this->data, [
                'news' => $news,
                'categories' => $categories,
                'type' => $type,
                'csrf_token' => $this->generateCsrfToken(),
                'pageTitle' => 'Edit: ' . $news['title']
            ]);
            
            $this->render('news-manager/edit');
            
        } catch (Exception $e) {
            error_log("Edit error: " . $e->getMessage());
            $this->setFlash('error', $e->getMessage());
            $this->redirect('/admin/news-manager');
        }
    }
    
    /**
     * Update content - /admin/news-manager/update/{id}
     */
    public function update($id) {
        error_log("=== NEWS MANAGER UPDATE METHOD CALLED for ID: $id ===");
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/admin/news-manager/' . $id . '/edit');
            return;
        }
        
        if (!$this->hasPermission('news_edit')) {
            $this->setFlash('error', 'Access denied');
            $this->redirect('/admin/news-manager');
            return;
        }
        
        try {
            // Validate CSRF token
            if (!$this->validateCsrfToken()) {
                error_log("CSRF VALIDATION FAILED in update!");
                $this->setFlash('error', 'Invalid security token. Please try again.');
                $this->redirect('/admin/news-manager/' . $id . '/edit');
                return;
            }
            
            $existing = $this->newsModel->getById($id);
            if (!$existing) {
                throw new Exception('Content not found');
            }
            
            $type = $_POST['type'] ?? $existing['type'];
            $isEvent = ($type === 'event');
            $isDraft = isset($_POST['save_draft']) && $_POST['save_draft'] == '1';
            
            $data = [
                'title' => trim($_POST['title'] ?? ''),
                'slug' => $this->generateSlug($_POST['slug'] ?? $_POST['title'] ?? '', $id),
                'excerpt' => trim($_POST['excerpt'] ?? ''),
                'content' => trim($_POST['content'] ?? ''),
                'category' => $_POST['category'] ?? '',
                'tags' => $this->processTags($_POST['tags'] ?? ''),
                'is_published' => $isDraft ? 0 : ($_POST['is_published'] ?? 1),
                'is_featured' => isset($_POST['is_featured']) ? 1 : 0,
                'is_breaking' => isset($_POST['is_breaking']) ? 1 : 0,
                'meta_title' => $_POST['meta_title'] ?? '',
                'meta_description' => $_POST['meta_description'] ?? '',
                'meta_keywords' => $_POST['meta_keywords'] ?? '',
                'type' => $type,
                'updated_at' => date('Y-m-d H:i:s')
            ];
            
            // Handle image upload
            $imagePath = $this->handleImageUpload();
            if ($imagePath !== null) {
                $data['featured_image'] = $imagePath;
            }
            
            if ($isEvent) {
                $data['event_date'] = $_POST['event_date'] ?? null;
                $data['event_end_date'] = $_POST['event_end_date'] ?? null;
                $data['event_time'] = $_POST['event_time'] ?? '';
                $data['event_location'] = $_POST['event_location'] ?? '';
            }
            
            // Validate
            if (empty($data['title'])) throw new Exception('Title is required');
            if (empty($data['content'])) throw new Exception('Content is required');
            if ($isEvent && empty($data['event_date'])) throw new Exception('Event date is required');
            
            $success = $this->newsModel->update($id, $data);
            
            if ($success) {
                $contentType = $isEvent ? 'Event' : 'News article';
                $message = $isDraft ? 
                    ucfirst($contentType) . ' saved as draft successfully!' : 
                    ucfirst($contentType) . ' updated successfully!';
                
                $this->logActivity('content_updated', "Updated {$contentType} #{$id}: {$data['title']}");
                $this->setFlash('success', $message);
                
                if (isset($_POST['save_and_continue'])) {
                    $this->redirect('/admin/news-manager/' . $id . '/edit');
                } else {
                    $this->redirect('/admin/news-manager');
                }
            } else {
                throw new Exception('Failed to update content');
            }
            
        } catch (Exception $e) {
            error_log("Update error: " . $e->getMessage());
            
            // Preserve form data
            $_SESSION['form_data'] = $_POST;
            $this->setFlash('error', $e->getMessage());
            $this->redirect('/admin/news-manager/' . $id . '/edit');
        }
    }
    
    // ============================================
    // DELETE METHODS
    // ============================================
    
    /**
     * Delete content - /admin/news-manager/delete/{id}
     */
    public function destroy($id) {
        error_log("=== NEWS MANAGER DESTROY METHOD CALLED for ID: $id ===");
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/admin/news-manager');
            return;
        }
        
        if (!$this->hasPermission('news_delete')) {
            $this->setFlash('error', 'Access denied');
            $this->redirect('/admin/news-manager');
            return;
        }
        
        try {
            // Validate CSRF token
            if (!$this->validateCsrfToken()) {
                error_log("CSRF VALIDATION FAILED in destroy!");
                $this->setFlash('error', 'Invalid security token. Please try again.');
                $this->redirect('/admin/news-manager');
                return;
            }
            
            $existing = $this->newsModel->getById($id);
            if (!$existing) {
                throw new Exception('Content not found');
            }
            
            $success = $this->newsModel->delete($id);
            
            if ($success) {
                $contentType = isset($existing['type']) && $existing['type'] === 'event' ? 'event' : 'article';
                $this->logActivity('content_deleted', "Deleted {$contentType} #{$id}: {$existing['title']}");
                $this->setFlash('success', ucfirst($contentType) . ' deleted successfully!');
            } else {
                throw new Exception('Failed to delete content');
            }
            
        } catch (Exception $e) {
            error_log("Destroy error: " . $e->getMessage());
            $this->setFlash('error', $e->getMessage());
        }
        
        $this->redirect('/admin/news-manager');
    }
    
    // ============================================
    // CATEGORY MANAGEMENT
    // ============================================
    
    /**
     * Categories management - /admin/news-manager/categories
     */
    public function categories() {
        error_log("=== NEWS MANAGER CATEGORIES METHOD CALLED ===");
        
        if (!$this->hasPermission('news_manage_categories')) {
            $this->setFlash('error', 'Access denied');
            $this->redirect('/admin/news-manager');
            return;
        }
        
        try {
            $categories = $this->getCategoriesWithCounts();
            
            $this->data = array_merge($this->data, [
                'categories' => $categories,
                'csrf_token' => $this->generateCsrfToken(),
                'pageTitle' => 'Manage Categories'
            ]);
            
            $this->render('news-manager/categories');
            
        } catch (Exception $e) {
            error_log("Categories error: " . $e->getMessage());
            $this->setFlash('error', 'Failed to load categories');
            $this->redirect('/admin/news-manager');
        }
    }
    
    // ============================================
    // AJAX METHODS
    // ============================================
    
    /**
     * Toggle publish status (AJAX) - /admin/news-manager/toggle-publish/{id}
     */
    public function togglePublish($id) {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->json(['success' => false, 'message' => 'Method not allowed'], 405);
            return;
        }
        
        if (!$this->hasPermission('news_publish')) {
            $this->json(['success' => false, 'message' => 'Permission denied'], 403);
            return;
        }
        
        try {
            // Validate CSRF token
            $csrfToken = $_POST['csrf_token'] ?? '';
            if (!$this->validateCsrfToken()) {
                $this->json(['success' => false, 'message' => 'Invalid CSRF token'], 403);
                return;
            }
            
            $news = $this->newsModel->getById($id);
            if (!$news) {
                $this->json(['success' => false, 'message' => 'Content not found'], 404);
                return;
            }
            
            $newStatus = $news['is_published'] ? 0 : 1;
            $success = $this->newsModel->update($id, ['is_published' => $newStatus]);
            
            $this->json([
                'success' => $success,
                'newStatus' => $newStatus,
                'message' => $success ? 'Status updated' : 'Failed to update'
            ]);
            
        } catch (Exception $e) {
            error_log("Toggle publish error: " . $e->getMessage());
            $this->json(['success' => false, 'message' => 'Server error'], 500);
        }
    }
    
    /**
     * Toggle featured status (AJAX) - /admin/news-manager/toggle-featured/{id}
     */
    public function toggleFeatured($id) {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->json(['success' => false, 'message' => 'Method not allowed'], 405);
            return;
        }
        
        if (!$this->hasPermission('news_edit')) {
            $this->json(['success' => false, 'message' => 'Permission denied'], 403);
            return;
        }
        
        try {
            // Validate CSRF token
            $csrfToken = $_POST['csrf_token'] ?? '';
            if (!$this->validateCsrfToken()) {
                $this->json(['success' => false, 'message' => 'Invalid CSRF token'], 403);
                return;
            }
            
            $news = $this->newsModel->getById($id);
            if (!$news) {
                $this->json(['success' => false, 'message' => 'Content not found'], 404);
                return;
            }
            
            $newStatus = $news['is_featured'] ? 0 : 1;
            $success = $this->newsModel->update($id, ['is_featured' => $newStatus]);
            
            $this->json([
                'success' => $success,
                'newStatus' => $newStatus,
                'message' => $success ? 'Featured status updated' : 'Failed to update'
            ]);
            
        } catch (Exception $e) {
            error_log("Toggle featured error: " . $e->getMessage());
            $this->json(['success' => false, 'message' => 'Server error'], 500);
        }
    }
    
    /**
     * Bulk actions - /admin/news-manager/bulk-action
     */
    public function bulkAction() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/admin/news-manager');
            return;
        }
        
        try {
            // Validate CSRF token
            if (!$this->validateCsrfToken()) {
                $this->setFlash('error', 'Invalid security token. Please try again.');
                $this->redirect('/admin/news-manager');
                return;
            }
            
            $action = $_POST['action'] ?? '';
            $ids = $_POST['ids'] ?? [];
            
            if (empty($action) || empty($ids)) {
                throw new Exception('No action or items selected');
            }
            
            // Check permissions based on action
            if ($action === 'delete' && !$this->hasPermission('news_delete')) {
                throw new Exception('You do not have permission to delete content');
            }
            
            if (in_array($action, ['publish', 'unpublish']) && !$this->hasPermission('news_publish')) {
                throw new Exception('You do not have permission to publish content');
            }
            
            if (in_array($action, ['feature', 'unfeature']) && !$this->hasPermission('news_edit')) {
                throw new Exception('You do not have permission to feature content');
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
            error_log("Bulk action error: " . $e->getMessage());
            $this->setFlash('error', 'Error: ' . $e->getMessage());
        }
        
        $this->redirect('/admin/news-manager');
    }
    
    // ============================================
    // HELPER METHODS
    // ============================================
    
    /**
     * Get all categories
     */
    private function getAllCategories() {
        try {
            $sql = "SELECT DISTINCT category FROM news 
                    WHERE category IS NOT NULL 
                    AND category != '' 
                    ORDER BY category";
            $stmt = $this->db->query($sql);
            $categories = $stmt->fetchAll(PDO::FETCH_COLUMN);
            
            return $categories ?: ['Announcements', 'Research', 'Academic News', 'Events', 'Student Life'];
            
        } catch (Exception $e) {
            error_log("Error getting categories: " . $e->getMessage());
            return ['Announcements', 'Research', 'Academic News', 'Events', 'Student Life'];
        }
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
     * Process tags (convert comma-separated to JSON)
     */
    private function processTags($tags) {
        if (empty($tags)) {
            return json_encode([]);
        }
        
        // If it's already JSON
        if (is_string($tags) && strpos($tags, '[') === 0) {
            return $tags;
        }
        
        // Convert comma-separated to array
        $tagArray = array_map('trim', explode(',', $tags));
        $tagArray = array_filter($tagArray);
        
        return json_encode(array_values($tagArray));
    }
    
    /**
     * Handle image upload
     */
    private function handleImageUpload() {
        // Check if removing existing image
        if (isset($_POST['remove_image']) && $_POST['remove_image'] == '1') {
            return '';
        }
        
        // Check if file was uploaded
        if (isset($_FILES['featured_image_upload']) && $_FILES['featured_image_upload']['error'] === UPLOAD_ERR_OK) {
            $file = $_FILES['featured_image_upload'];
            $type = $_POST['type'] ?? 'news';
            
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
            
            // Get upload directory
            $uploadDir = $this->getUploadPath($type);
            
            // Ensure directory exists
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }
            
            // Generate filename
            $extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
            $filename = uniqid() . '_' . time() . '.' . $extension;
            $destination = $uploadDir . '/' . $filename;
            
            // Move uploaded file
            if (move_uploaded_file($file['tmp_name'], $destination)) {
                chmod($destination, 0644);
                return '/uploads/' . $type . '/' . $filename;
            } else {
                error_log("Failed to move uploaded file");
                return $_POST['featured_image'] ?? '';
            }
        }
        
        // Keep existing image
        return $_POST['featured_image'] ?? '';
    }
    
    /**
     * Get upload path
     */
    private function getUploadPath($type = 'news') {
        // Try to use helper function if available
        if (function_exists('getProjectRootPath')) {
            return getProjectRootPath() . '/public/uploads/' . $type;
        }
        
        // Fallback
        $basePath = dirname(dirname(dirname(__FILE__)));
        return $basePath . '/public/uploads/' . $type;
    }
    
    /**
     * JSON response helper
     */
    private function json($data, $statusCode = 200) {
        http_response_code($statusCode);
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }
}