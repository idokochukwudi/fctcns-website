<?php
/**
 * Unified News Controller
 * Handles ALL news operations with role-based access:
 * - Admin/Editor: Full access to /admin/news
 * - News Manager: Limited access to /admin/news-manager
 * - Public: Access to /news
 * 
 * Extends base Controller class and uses its methods
 */
class NewsController extends Controller {
    
    private $db;
    private $newsModel;
    private $userRole;
    private $userId;
    private $permissions = [];
    
    public function __construct() {
        parent::__construct();
        
        error_log("=== UNIFIED NEWS CONTROLLER CONSTRUCTOR ===");
        
        // Setup database
        require_once APP_PATH . '/config/database.php';
        $database = Database::getInstance();
        $this->db = $database->getConnection();
        
        // Initialize NewsModel
        require_once APP_PATH . '/models/NewsModel.php';
        $this->newsModel = new NewsModel($this->db);
        
        // Get user info if logged in
        $this->userId = $_SESSION['user_id'] ?? 0;
        $this->userRole = $_SESSION['user_role'] ?? 'guest';
        
        // Load permissions if user is logged in
        if ($this->userId) {
            $this->loadPermissions();
        }
        
        // Set common data
        $this->data = array_merge($this->data, [
            'userRole' => $this->userRole,
            'permissions' => $this->permissions,
            'hasPermission' => [$this, 'hasPermission'],
            'baseUrl' => $this->getBaseUrl()
        ]);
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
            
            // Add role-based permissions
            if (in_array($this->userRole, ['admin', 'super_admin'])) {
                $this->permissions = array_merge($this->permissions, [
                    'news_view', 'news_create', 'news_edit', 'news_delete',
                    'news_publish', 'news_manage_categories', 'news_upload_images'
                ]);
            } elseif ($this->userRole === 'editor') {
                $this->permissions = array_merge($this->permissions, [
                    'news_view', 'news_create', 'news_edit', 'news_publish',
                    'news_manage_categories', 'news_upload_images'
                ]);
            } elseif ($this->userRole === 'news_manager') {
                $this->permissions = array_merge($this->permissions, [
                    'news_view', 'news_create', 'news_edit', 'news_manage_categories'
                ]);
            }
            
            $this->permissions = array_unique($this->permissions);
            
        } catch (Exception $e) {
            error_log("Error loading permissions: " . $e->getMessage());
            $this->permissions = [];
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
     * Check authentication for admin routes
     */
    private function requireAuth() {
        if (!$this->userId) {
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
     * Set flash message (wrapper for parent flash method)
     */
    private function setFlash($type, $message) {
        $this->flash($type, $message);
    }
    
    // ============================================
    // PUBLIC ROUTES
    // ============================================
    
    /**
     * Public news listing - /news
     */
    public function index() {
        error_log("=== PUBLIC NEWS INDEX ===");
        
        try {
            $page = max(1, (int)($this->query('page', 1)));
            $limit = 10;
            $offset = ($page - 1) * $limit;
            
            $news = $this->newsModel->getPublishedNews($limit, $offset);
            $total = $this->newsModel->countPublishedNews();
            $featuredNews = $this->newsModel->getFeaturedNews(3);
            $categories = $this->newsModel->getCategoriesWithCounts();
            $popularNews = $this->newsModel->getPopularNews(5);
            
            $this->data = array_merge($this->data, [
                'news' => $news,
                'featuredNews' => $featuredNews,
                'categories' => $categories,
                'popularNews' => $popularNews,
                'pagination' => [
                    'current' => $page,
                    'total' => ceil($total / $limit),
                    'totalCount' => $total
                ],
                'pageTitle' => 'News & Updates'
            ]);
            
            $this->layout = 'main';
            $this->render('pages/news/index');
            
        } catch (Exception $e) {
            error_log("Public index error: " . $e->getMessage());
            $this->notFound();
        }
    }
    
    /**
     * Public news detail - /news/{slug}
     */
    public function show($slug) {
        error_log("=== PUBLIC NEWS SHOW: $slug ===");
        
        try {
            $news = $this->newsModel->getBySlug($slug);
            
            if (!$news || !$news['is_published']) {
                $this->notFound();
                return;
            }
            
            // Increment views
            $this->newsModel->incrementViews($news['id']);
            
            // Get related news
            $relatedNews = $this->newsModel->getRelatedNews($news['id'], $news['category'] ?? '', 3);
            
            // Get sidebar data
            $categories = $this->newsModel->getCategoriesWithCounts();
            $popularNews = $this->newsModel->getPopularNews(5);
            
            $this->data = array_merge($this->data, [
                'news' => $news,
                'relatedNews' => $relatedNews,
                'categories' => $categories,
                'popularNews' => $popularNews,
                'pageTitle' => $news['title']
            ]);
            
            $this->layout = 'main';
            $this->render('pages/news/show');
            
        } catch (Exception $e) {
            error_log("Public show error: " . $e->getMessage());
            $this->notFound();
        }
    }
    
    /**
     * News search - /news/search
     * FIXED: Enhanced with better error handling and logging
     */
    public function search() {
        error_log("=== PUBLIC NEWS SEARCH CONTROLLER ===");
        
        try {
            $query = trim($this->query('q', ''));
            $page = max(1, (int)($this->query('page', 1)));
            $limit = 10;
            $offset = ($page - 1) * $limit;
            
            error_log("Search query received: '" . $query . "'");
            error_log("Page: $page, Limit: $limit, Offset: $offset");
            
            if (empty($query)) {
                error_log("Empty query - redirecting to news");
                $this->redirect('/news');
                return;
            }
            
            // Get search results
            $results = $this->newsModel->searchNews($query, $limit, $offset);
            $total = $this->newsModel->countSearchResults($query);
            
            error_log("Results found: " . count($results));
            error_log("Total results count: " . $total);
            
            // Debug: Log the first result if any
            if (!empty($results)) {
                error_log("First result title: " . $results[0]['title']);
                error_log("First result ID: " . $results[0]['id']);
                error_log("First result category: " . ($results[0]['category'] ?? 'none'));
            }
            
            // Get sidebar data for the view
            $categories = $this->newsModel->getCategoriesWithCounts();
            $popularNews = $this->newsModel->getPopularNews(5);
            
            // Prepare data for view
            $this->data = array_merge($this->data, [
                'news' => $results,
                'searchQuery' => $query,  // Use 'searchQuery' consistently
                'query' => $query,         // Keep 'query' for backward compatibility
                'categories' => $categories,
                'popularNews' => $popularNews,
                'pagination' => [
                    'current' => $page,
                    'total' => ceil($total / $limit),
                    'totalCount' => $total,
                    'limit' => $limit
                ],
                'pageTitle' => 'Search Results: ' . $query,
                'pageDescription' => 'Search results for "' . $query . '"'
            ]);
            
            error_log("Data prepared for view. News count: " . count($this->data['news']));
            
            $this->layout = 'main';
            $this->render('pages/news/search');
            
        } catch (Exception $e) {
            error_log("Search controller error: " . $e->getMessage());
            error_log("Error trace: " . $e->getTraceAsString());
            $this->setFlash('error', 'Search failed: ' . $e->getMessage());
            $this->redirect('/news');
        }
    }
    
    /**
     * News by category - /news/category/{category}
     * ✅ FIXED: Converts URL-friendly slugs back to original category format
     */
    public function category($category) {
        error_log("=== PUBLIC NEWS CATEGORY: $category ===");
        
        try {
            $page = max(1, (int)($this->query('page', 1)));
            $limit = 10;
            $offset = ($page - 1) * $limit;
            
            // Convert URL-friendly category back to original format
            // Replace hyphens with spaces and capitalize words
            $originalCategory = str_replace('-', ' ', $category);
            $originalCategory = ucwords($originalCategory);
            
            error_log("URL Category: $category");
            error_log("Database Category: $originalCategory");
            
            // Get news for this category
            $news = $this->newsModel->getNewsByCategory($originalCategory, $limit, $offset);
            $total = $this->newsModel->countNewsByCategory($originalCategory);
            $totalPages = ceil($total / $limit);
            
            // Get sidebar data
            $categories = $this->newsModel->getCategoriesWithCounts();
            $popularNews = $this->newsModel->getPopularNews(5);
            $archiveMonths = $this->newsModel->getArchiveMonths();
            
            $this->data = array_merge($this->data, [
                'news' => $news,
                'category' => $originalCategory,
                'category_slug' => $category,
                'categories' => $categories,
                'popularNews' => $popularNews,
                'archiveMonths' => $archiveMonths,
                'pagination' => [
                    'current' => $page,
                    'total' => $totalPages,
                    'totalCount' => $total,
                    'limit' => $limit
                ],
                'pageTitle' => 'Category: ' . $originalCategory
            ]);
            
            $this->layout = 'main';
            $this->render('pages/news/category');
            
        } catch (Exception $e) {
            error_log("Category error: " . $e->getMessage());
            $this->setFlash('error', 'Failed to load category: ' . $e->getMessage());
            $this->redirect('/news');
        }
    }
    
    /**
     * News archive - /news/archive/{year}/{month}
     */
    public function archive($year, $month) {
        error_log("=== PUBLIC NEWS ARCHIVE: $year/$month ===");
        
        try {
            $page = max(1, (int)($this->query('page', 1)));
            $limit = 10;
            $offset = ($page - 1) * $limit;
            
            $news = $this->newsModel->getNewsByMonth($year, $month, $limit, $offset);
            $total = $this->newsModel->countNewsByMonth($year, $month);
            $totalPages = ceil($total / $limit);
            
            $monthName = date('F', mktime(0, 0, 0, $month, 1));
            
            // Get sidebar data
            $categories = $this->newsModel->getCategoriesWithCounts();
            $popularNews = $this->newsModel->getPopularNews(5);
            
            $this->data = array_merge($this->data, [
                'news' => $news,
                'year' => $year,
                'month' => $month,
                'monthName' => $monthName,
                'categories' => $categories,
                'popularNews' => $popularNews,
                'pagination' => [
                    'current' => $page,
                    'total' => $totalPages,
                    'totalCount' => $total,
                    'limit' => $limit
                ],
                'pageTitle' => 'Archive: ' . $monthName . ' ' . $year
            ]);
            
            $this->layout = 'main';
            $this->render('pages/news/archive');
            
        } catch (Exception $e) {
            error_log("Archive error: " . $e->getMessage());
            $this->setFlash('error', 'Failed to load archive: ' . $e->getMessage());
            $this->redirect('/news');
        }
    }
    
    // ============================================
    // ADMIN ROUTES (Full access)
    // ============================================
    
    /**
     * Admin news listing - /admin/news
     */
    public function adminIndex() {
        $this->requireAuth();
        
        if (!$this->hasPermission('news_view')) {
            $this->setFlash('error', 'Access denied');
            $this->redirect('/admin/dashboard');
            return;
        }
        
        error_log("=== ADMIN NEWS INDEX ===");
        
        try {
            // Get filters
            $filters = [
                'status' => $this->query('status', ''),
                'type' => $this->query('type', ''),
                'category' => $this->query('category', ''),
                'search' => $this->query('search', ''),
                'date_from' => $this->query('date_from', ''),
                'date_to' => $this->query('date_to', '')
            ];
            
            // Clean empty filters
            foreach ($filters as $key => $value) {
                if (empty($value)) {
                    unset($filters[$key]);
                }
            }
            
            $page = max(1, (int)$this->query('page', 1));
            $limit = 20;
            $offset = ($page - 1) * $limit;
            
            $news = $this->newsModel->getAllWithFilters($filters, $limit, $offset);
            $total = $this->newsModel->countAllWithFilters($filters);
            $totalPages = ceil($total / $limit);
            
            // Get stats
            $stats = $this->getAdminStats();
            
            // Get categories
            $categories = $this->getAllCategories();
            
            // Prepare flash messages
            $flashSuccess = $this->getFlash('success');
            $flashError = $this->getFlash('error');
            
            $this->data = array_merge($this->data, [
                'news' => $news,
                'stats' => $stats,
                'categories' => $categories,
                'filters' => $filters,
                'pagination' => [
                    'current' => $page,
                    'total' => $totalPages,
                    'totalCount' => $total,
                    'limit' => $limit
                ],
                'csrf_token' => $this->csrfToken(),
                'flash_success' => $flashSuccess,
                'flash_error' => $flashError,
                'pageTitle' => 'News Management',
                'isAdmin' => true,
                'isNewsManager' => false,
                'canCreate' => $this->hasPermission('news_create'),
                'canEdit' => $this->hasPermission('news_edit'),
                'canDelete' => $this->hasPermission('news_delete'),
                'canPublish' => $this->hasPermission('news_publish')
            ]);
            
            $this->layout = 'admin';
            $this->render('admin/news/index');
            
        } catch (Exception $e) {
            error_log("Admin index error: " . $e->getMessage());
            $this->setFlash('error', 'Failed to load news: ' . $e->getMessage());
            $this->redirect('/admin/dashboard');
        }
    }
    
    // ============================================
    // NEWS MANAGER ROUTES (Limited access)
    // ============================================
    
    /**
     * News manager dashboard - /admin/news-manager
     */
    public function managerIndex() {
        $this->requireAuth();
        
        if (!$this->hasPermission('news_view')) {
            $this->setFlash('error', 'Access denied');
            $this->redirect('/dashboard');
            return;
        }
        
        error_log("=== NEWS MANAGER INDEX ===");
        
        try {
            // Get filters
            $filters = [
                'status' => $this->query('status', ''),
                'type' => $this->query('type', ''),
                'category' => $this->query('category', ''),
                'search' => $this->query('search', ''),
                'date_from' => $this->query('date_from', ''),
                'date_to' => $this->query('date_to', '')
            ];
            
            // Clean empty filters
            foreach ($filters as $key => $value) {
                if (empty($value)) {
                    unset($filters[$key]);
                }
            }
            
            $page = max(1, (int)$this->query('page', 1));
            $limit = 15;
            $offset = ($page - 1) * $limit;
            
            // Get content with filters
            $content = $this->newsModel->getAllWithFilters($filters, $limit, $offset);
            $total = $this->newsModel->countAllWithFilters($filters);
            $totalPages = ceil($total / $limit);
            
            // Get comprehensive stats
            $stats = $this->getManagerStats();
            
            // Ensure stats has all required keys with defaults
            $defaultStats = [
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
            
            $stats = array_merge($defaultStats, $stats);
            
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
            $flashSuccess = $this->getFlash('success');
            $flashError = $this->getFlash('error');
            
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
                    'totalCount' => $total,
                    'limit' => $limit
                ],
                'csrf_token' => $this->csrfToken(),
                'flash_success' => $flashSuccess,
                'flash_error' => $flashError,
                'pageTitle' => 'News Manager Dashboard',
                'isAdmin' => false,
                'isNewsManager' => true,
                'canCreate' => $this->hasPermission('news_create'),
                'canEdit' => $this->hasPermission('news_edit'),
                'canDelete' => $this->hasPermission('news_delete'),
                'canPublish' => $this->hasPermission('news_publish'),
                'canManageCategories' => $this->hasPermission('news_manage_categories')
            ]);
            
            $this->layout = 'default';
            $this->render('news-manager/index');
            
        } catch (Exception $e) {
            error_log("Manager index error: " . $e->getMessage());
            $this->setFlash('error', 'Failed to load dashboard: ' . $e->getMessage());
            $this->redirect('/dashboard');
        }
    }
    
    // ============================================
    // NEWS MANAGER CATEGORIES METHODS
    // ============================================
    
    /**
     * Categories management - /admin/news-manager/categories
     */
    public function categories() {
        $this->requireAuth();
        
        if (!$this->hasPermission('news_manage_categories')) {
            $this->setFlash('error', 'Access denied');
            $this->redirect('/admin/news-manager');
            return;
        }
        
        error_log("=== NEWS MANAGER CATEGORIES METHOD CALLED ===");
        
        try {
            // Get categories with counts
            $categories = $this->getCategoriesWithCounts();
            
            // Get all distinct categories for the add form
            $allCategories = $this->getAllCategories();
            
            $this->data = array_merge($this->data, [
                'categories' => $categories,
                'allCategories' => $allCategories,
                'csrf_token' => $this->csrfToken(),
                'pageTitle' => 'Manage Categories',
                'canManageCategories' => $this->hasPermission('news_manage_categories')
            ]);
            
            $this->layout = 'default';
            $this->render('news-manager/categories');
            
        } catch (Exception $e) {
            error_log("Categories error: " . $e->getMessage());
            $this->setFlash('error', 'Failed to load categories: ' . $e->getMessage());
            $this->redirect('/admin/news-manager');
        }
    }
    
    /**
     * Add category - /admin/news-manager/categories/add
     */
    public function addCategory() {
        $this->requireAuth();
        
        if (!$this->hasPermission('news_manage_categories')) {
            $this->setFlash('error', 'Access denied');
            $this->redirect('/admin/news-manager/categories');
            return;
        }
        
        error_log("=== ADD CATEGORY METHOD CALLED ===");
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/admin/news-manager/categories');
            return;
        }
        
        try {
            // Validate CSRF token
            if (!$this->validateCsrfToken()) {
                $this->setFlash('error', 'Invalid security token');
                $this->redirect('/admin/news-manager/categories');
                return;
            }
            
            $categoryName = trim($this->input('category_name', ''));
            
            if (empty($categoryName)) {
                $this->setFlash('error', 'Category name is required');
                $this->redirect('/admin/news-manager/categories');
                return;
            }
            
            // Check if category already exists
            $stmt = $this->db->prepare("SELECT COUNT(*) as count FROM news WHERE category = ?");
            $stmt->execute([$categoryName]);
            $exists = $stmt->fetch()['count'] > 0;
            
            if ($exists) {
                $this->setFlash('error', 'Category "' . htmlspecialchars($categoryName) . '" already exists');
                $this->redirect('/admin/news-manager/categories');
                return;
            }
            
            // Create a dummy article with this category to "create" the category
            // This is a workaround since categories are derived from existing articles
            $dummyData = [
                'title' => 'Category: ' . $categoryName,
                'slug' => 'category-' . $this->generateSlug($categoryName),
                'excerpt' => 'System generated category placeholder',
                'content' => '<p>This is a system-generated placeholder for the category "' . htmlspecialchars($categoryName) . '". You can delete this article after creating real content in this category.</p>',
                'category' => $categoryName,
                'type' => 'news',
                'author_id' => $this->userId,
                'is_published' => 0, // Draft
                'is_featured' => 0,
                'is_breaking' => 0,
                'tags' => json_encode([]),
                'meta_title' => '',
                'meta_description' => '',
                'meta_keywords' => ''
            ];
            
            $id = $this->newsModel->create($dummyData);
            
            if ($id) {
                $this->setFlash('success', 'Category "' . htmlspecialchars($categoryName) . '" added successfully');
                error_log("Category added with dummy article ID: " . $id);
            } else {
                $this->setFlash('error', 'Failed to add category');
            }
            
        } catch (Exception $e) {
            error_log("Add category error: " . $e->getMessage());
            $this->setFlash('error', 'Error: ' . $e->getMessage());
        }
        
        $this->redirect('/admin/news-manager/categories');
    }
    
    /**
     * Edit category - /admin/news-manager/categories/edit
     */
    public function editCategory() {
        $this->requireAuth();
        
        if (!$this->hasPermission('news_manage_categories')) {
            $this->setFlash('error', 'Access denied');
            $this->redirect('/admin/news-manager/categories');
            return;
        }
        
        error_log("=== EDIT CATEGORY METHOD CALLED ===");
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/admin/news-manager/categories');
            return;
        }
        
        try {
            // Validate CSRF token
            if (!$this->validateCsrfToken()) {
                $this->setFlash('error', 'Invalid security token');
                $this->redirect('/admin/news-manager/categories');
                return;
            }
            
            $oldName = trim($this->input('old_name', ''));
            $newName = trim($this->input('new_name', ''));
            
            if (empty($oldName) || empty($newName)) {
                $this->setFlash('error', 'Both old and new category names are required');
                $this->redirect('/admin/news-manager/categories');
                return;
            }
            
            if ($oldName === $newName) {
                $this->setFlash('info', 'No changes made');
                $this->redirect('/admin/news-manager/categories');
                return;
            }
            
            // Check if new category name already exists
            $stmt = $this->db->prepare("SELECT COUNT(*) as count FROM news WHERE category = ?");
            $stmt->execute([$newName]);
            $exists = $stmt->fetch()['count'] > 0;
            
            if ($exists) {
                $this->setFlash('error', 'Category "' . htmlspecialchars($newName) . '" already exists');
                $this->redirect('/admin/news-manager/categories');
                return;
            }
            
            // Update all articles with the old category to the new category
            $stmt = $this->db->prepare("UPDATE news SET category = ? WHERE category = ?");
            $success = $stmt->execute([$newName, $oldName]);
            
            if ($success) {
                $affectedRows = $stmt->rowCount();
                $this->setFlash('success', 'Category renamed from "' . htmlspecialchars($oldName) . '" to "' . htmlspecialchars($newName) . '" (' . $affectedRows . ' articles updated)');
            } else {
                $this->setFlash('error', 'Failed to rename category');
            }
            
        } catch (Exception $e) {
            error_log("Edit category error: " . $e->getMessage());
            $this->setFlash('error', 'Error: ' . $e->getMessage());
        }
        
        $this->redirect('/admin/news-manager/categories');
    }
    
    /**
     * Delete category - /admin/news-manager/categories/delete
     */
    public function deleteCategory() {
        $this->requireAuth();
        
        if (!$this->hasPermission('news_manage_categories')) {
            $this->setFlash('error', 'Access denied');
            $this->redirect('/admin/news-manager/categories');
            return;
        }
        
        error_log("=== DELETE CATEGORY METHOD CALLED ===");
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/admin/news-manager/categories');
            return;
        }
        
        try {
            // Validate CSRF token
            if (!$this->validateCsrfToken()) {
                $this->setFlash('error', 'Invalid security token');
                $this->redirect('/admin/news-manager/categories');
                return;
            }
            
            $categoryName = trim($this->input('category_name', ''));
            
            if (empty($categoryName)) {
                $this->setFlash('error', 'Category name is required');
                $this->redirect('/admin/news-manager/categories');
                return;
            }
            
            // Check if category has any articles
            $stmt = $this->db->prepare("SELECT COUNT(*) as count FROM news WHERE category = ?");
            $stmt->execute([$categoryName]);
            $count = $stmt->fetch()['count'];
            
            if ($count > 0) {
                $this->setFlash('error', 'Cannot delete category "' . htmlspecialchars($categoryName) . '" because it has ' . $count . ' article(s). Reassign or delete the articles first.');
                $this->redirect('/admin/news-manager/categories');
                return;
            }
            
            // If no articles, just redirect with success message
            // Since categories are derived from articles, there's nothing to delete
            $this->setFlash('success', 'Category "' . htmlspecialchars($categoryName) . '" removed successfully');
            
        } catch (Exception $e) {
            error_log("Delete category error: " . $e->getMessage());
            $this->setFlash('error', 'Error: ' . $e->getMessage());
        }
        
        $this->redirect('/admin/news-manager/categories');
    }
    
    // ============================================
    // SHARED CRUD METHODS (Used by both admin and news manager)
    // ============================================
    
    /**
     * Create form - /admin/news/create or /admin/news-manager/create
     */
    public function create() {
        $this->requireAuth();
        
        if (!$this->hasPermission('news_create')) {
            $this->setFlash('error', 'Access denied');
            $this->redirect($this->getDashboardUrl());
            return;
        }
        
        error_log("=== CREATE FORM ===");
        
        try {
            $type = $this->query('type', 'news');
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
                'csrf_token' => $this->csrfToken(),
                'pageTitle' => $type === 'event' ? 'Create Event' : 'Create News'
            ]);
            
            // Determine which view to use
            $referer = $_SERVER['HTTP_REFERER'] ?? '';
            if (strpos($referer, '/admin/news-manager') !== false || 
                strpos($_SERVER['REQUEST_URI'], '/admin/news-manager') !== false) {
                $this->layout = 'default';
                $this->render('news-manager/create');
            } else {
                $this->layout = 'admin';
                $this->render('admin/news/create');
            }
            
        } catch (Exception $e) {
            error_log("Create error: " . $e->getMessage());
            $this->setFlash('error', 'Failed to load form: ' . $e->getMessage());
            $this->redirect($this->getDashboardUrl());
        }
    }
    
    /**
     * Store new content - /admin/news/store or /admin/news-manager/store
     */
    public function store() {
        $this->requireAuth();
        
        if (!$this->hasPermission('news_create')) {
            $this->setFlash('error', 'Access denied');
            $this->redirect($this->getDashboardUrl());
            return;
        }
        
        error_log("=== STORE CONTENT ===");
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect($this->getDashboardUrl());
            return;
        }
        
        try {
            // Validate CSRF token using parent method
            if (!$this->validateCsrfToken()) {
                $this->setFlash('error', 'Invalid security token');
                $this->redirect('/admin/news/create?type=' . $this->input('type', 'news'));
                return;
            }
            
            $type = $this->input('type', 'news');
            $isEvent = ($type === 'event');
            $isDraft = $this->input('save_draft') ? true : false;
            
            // Prepare data
            $data = [
                'title' => trim($this->input('title', '')),
                'slug' => $this->generateSlug($this->input('title', '')),
                'excerpt' => trim($this->input('excerpt', '')),
                'content' => trim($this->input('content', '')),
                'category' => $this->input('category', ''),
                'tags' => $this->processTags($this->input('tags', '')),
                'is_published' => $isDraft ? 0 : 1,
                'is_featured' => $this->input('is_featured') ? 1 : 0,
                'is_breaking' => $this->input('is_breaking') ? 1 : 0,
                'meta_title' => $this->input('meta_title', ''),
                'meta_description' => $this->input('meta_description', ''),
                'meta_keywords' => $this->input('meta_keywords', ''),
                'type' => $type,
                'author_id' => $this->userId
            ];
            
            // Handle image upload
            $data['featured_image'] = $this->handleImageUpload();
            
            // Add event fields
            if ($isEvent) {
                $data['event_date'] = $this->input('event_date', null);
                $data['event_end_date'] = $this->input('event_end_date', null);
                $data['event_time'] = $this->input('event_time', '');
                $data['event_location'] = $this->input('event_location', '');
            }
            
            // Validate
            $errors = [];
            if (empty($data['title'])) $errors[] = 'Title is required';
            if (empty($data['content'])) $errors[] = 'Content is required';
            if ($isEvent && empty($data['event_date'])) $errors[] = 'Event date is required';
            
            if (!empty($errors)) {
                $this->setFlash('error', implode('<br>', $errors));
                $_SESSION['form_data'] = $_POST;
                $this->redirect('/admin/news/create?type=' . $type);
                return;
            }
            
            $id = $this->newsModel->create($data);
            
            if ($id) {
                $contentType = $isEvent ? 'Event' : 'News article';
                $message = $isDraft ? 
                    ucfirst($contentType) . ' saved as draft successfully!' : 
                    ucfirst($contentType) . ' created successfully!';
                
                $this->setFlash('success', $message);
                
                // Determine redirect
                $referer = $_SERVER['HTTP_REFERER'] ?? '';
                if (strpos($referer, '/admin/news-manager') !== false) {
                    if ($this->input('save_and_continue')) {
                        $this->redirect('/admin/news-manager/' . $id . '/edit');
                    } else {
                        $this->redirect('/admin/news-manager');
                    }
                } else {
                    if ($this->input('save_and_continue')) {
                        $this->redirect('/admin/news/' . $id . '/edit');
                    } else {
                        $this->redirect('/admin/news');
                    }
                }
            } else {
                $this->setFlash('error', 'Failed to create content');
                $_SESSION['form_data'] = $_POST;
                $this->redirect('/admin/news/create?type=' . $type);
            }
            
        } catch (Exception $e) {
            error_log("Store error: " . $e->getMessage());
            $this->setFlash('error', 'Error: ' . $e->getMessage());
            $_SESSION['form_data'] = $_POST;
            $this->redirect('/admin/news/create?type=' . $this->input('type', 'news'));
        }
    }
    
    /**
     * Edit form - /admin/news/{id}/edit or /admin/news-manager/{id}/edit
     */
    public function edit($id) {
        $this->requireAuth();
        
        if (!$this->hasPermission('news_edit')) {
            $this->setFlash('error', 'Access denied');
            $this->redirect($this->getDashboardUrl());
            return;
        }
        
        error_log("=== EDIT FORM ID: $id ===");
        
        try {
            $news = $this->newsModel->getById($id);
            
            if (!$news) {
                $this->setFlash('error', 'Content not found');
                $this->redirect($this->getDashboardUrl());
                return;
            }
            
            $categories = $this->getAllCategories();
            $type = $news['type'] ?? 'news';
            
            $this->data = array_merge($this->data, [
                'news' => $news,
                'categories' => $categories,
                'type' => $type,
                'csrf_token' => $this->csrfToken(),
                'pageTitle' => 'Edit: ' . $news['title']
            ]);
            
            // Determine which view to use
            $referer = $_SERVER['HTTP_REFERER'] ?? '';
            if (strpos($referer, '/admin/news-manager') !== false || 
                strpos($_SERVER['REQUEST_URI'], '/admin/news-manager') !== false) {
                $this->layout = 'default';
                $this->render('news-manager/edit');
            } else {
                $this->layout = 'admin';
                $this->render('admin/news/edit');
            }
            
        } catch (Exception $e) {
            error_log("Edit error: " . $e->getMessage());
            $this->setFlash('error', 'Failed to load edit form: ' . $e->getMessage());
            $this->redirect($this->getDashboardUrl());
        }
    }
    
    /**
     * Update content - /admin/news/update/{id} or /admin/news-manager/update/{id}
     */
    public function update($id) {
        $this->requireAuth();
        
        if (!$this->hasPermission('news_edit')) {
            $this->setFlash('error', 'Access denied');
            $this->redirect($this->getDashboardUrl());
            return;
        }
        
        error_log("=== UPDATE ID: $id ===");
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/admin/news/' . $id . '/edit');
            return;
        }
        
        try {
            // Validate CSRF token using parent method
            if (!$this->validateCsrfToken()) {
                $this->setFlash('error', 'Invalid security token');
                $this->redirect('/admin/news/' . $id . '/edit');
                return;
            }
            
            $existing = $this->newsModel->getById($id);
            if (!$existing) {
                $this->setFlash('error', 'Content not found');
                $this->redirect('/admin/news');
                return;
            }
            
            $type = $this->input('type', $existing['type']);
            $isEvent = ($type === 'event');
            $isDraft = $this->input('save_draft') ? true : false;
            
            $data = [
                'title' => trim($this->input('title', '')),
                'slug' => $this->generateSlug($this->input('slug', $this->input('title', '')), $id),
                'excerpt' => trim($this->input('excerpt', '')),
                'content' => trim($this->input('content', '')),
                'category' => $this->input('category', ''),
                'tags' => $this->processTags($this->input('tags', '')),
                'is_published' => $isDraft ? 0 : ($this->input('is_published', 1)),
                'is_featured' => $this->input('is_featured') ? 1 : 0,
                'is_breaking' => $this->input('is_breaking') ? 1 : 0,
                'meta_title' => $this->input('meta_title', ''),
                'meta_description' => $this->input('meta_description', ''),
                'meta_keywords' => $this->input('meta_keywords', ''),
                'type' => $type,
                'updated_at' => date('Y-m-d H:i:s')
            ];
            
            // Handle image upload
            $imagePath = $this->handleImageUpload();
            if ($imagePath !== null) {
                $data['featured_image'] = $imagePath;
            }
            
            if ($isEvent) {
                $data['event_date'] = $this->input('event_date', null);
                $data['event_end_date'] = $this->input('event_end_date', null);
                $data['event_time'] = $this->input('event_time', '');
                $data['event_location'] = $this->input('event_location', '');
            }
            
            // Validate
            $errors = [];
            if (empty($data['title'])) $errors[] = 'Title is required';
            if (empty($data['content'])) $errors[] = 'Content is required';
            if ($isEvent && empty($data['event_date'])) $errors[] = 'Event date is required';
            
            if (!empty($errors)) {
                $this->setFlash('error', implode('<br>', $errors));
                $_SESSION['form_data'] = $_POST;
                $this->redirect('/admin/news/' . $id . '/edit');
                return;
            }
            
            $success = $this->newsModel->update($id, $data);
            
            if ($success) {
                $contentType = $isEvent ? 'Event' : 'News article';
                $message = $isDraft ? 
                    ucfirst($contentType) . ' saved as draft successfully!' : 
                    ucfirst($contentType) . ' updated successfully!';
                
                $this->setFlash('success', $message);
                
                // Determine redirect
                if ($this->input('save_and_continue')) {
                    $this->redirect('/admin/news/' . $id . '/edit');
                } else {
                    $referer = $_SERVER['HTTP_REFERER'] ?? '';
                    if (strpos($referer, '/admin/news-manager') !== false) {
                        $this->redirect('/admin/news-manager');
                    } else {
                        $this->redirect('/admin/news');
                    }
                }
            } else {
                $this->setFlash('error', 'Failed to update content');
                $_SESSION['form_data'] = $_POST;
                $this->redirect('/admin/news/' . $id . '/edit');
            }
            
        } catch (Exception $e) {
            error_log("Update error: " . $e->getMessage());
            $this->setFlash('error', 'Error: ' . $e->getMessage());
            $_SESSION['form_data'] = $_POST;
            $this->redirect('/admin/news/' . $id . '/edit');
        }
    }
    
    /**
     * Delete content - /admin/news/delete/{id} or /admin/news-manager/delete/{id}
     */
    public function destroy($id) {
        $this->requireAuth();
        
        if (!$this->hasPermission('news_delete')) {
            $this->setFlash('error', 'Access denied');
            $this->redirect($this->getDashboardUrl());
            return;
        }
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/admin/news');
            return;
        }
        
        try {
            // Validate CSRF token using parent method
            if (!$this->validateCsrfToken()) {
                $this->setFlash('error', 'Invalid security token');
                $this->redirect('/admin/news');
                return;
            }
            
            $existing = $this->newsModel->getById($id);
            if (!$existing) {
                $this->setFlash('error', 'Content not found');
                $this->redirect('/admin/news');
                return;
            }
            
            $success = $this->newsModel->delete($id);
            
            if ($success) {
                $contentType = isset($existing['type']) && $existing['type'] === 'event' ? 'event' : 'article';
                $this->setFlash('success', ucfirst($contentType) . ' deleted successfully!');
            } else {
                $this->setFlash('error', 'Failed to delete content');
            }
            
        } catch (Exception $e) {
            error_log("Delete error: " . $e->getMessage());
            $this->setFlash('error', 'Error: ' . $e->getMessage());
        }
        
        // Determine redirect
        $referer = $_SERVER['HTTP_REFERER'] ?? '';
        if (strpos($referer, '/admin/news-manager') !== false) {
            $this->redirect('/admin/news-manager');
        } else {
            $this->redirect('/admin/news');
        }
    }
    
    // ============================================
    // AJAX METHODS
    // ============================================
    
    /**
     * Toggle publish status (AJAX)
     */
    public function togglePublish($id) {
        $this->requireAuth();
        
        if (!$this->hasPermission('news_publish')) {
            $this->json(['success' => false, 'message' => 'Permission denied'], 403);
            return;
        }
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->json(['success' => false, 'message' => 'Method not allowed'], 405);
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
     * Toggle featured status (AJAX)
     */
    public function toggleFeatured($id) {
        $this->requireAuth();
        
        if (!$this->hasPermission('news_edit')) {
            $this->json(['success' => false, 'message' => 'Permission denied'], 403);
            return;
        }
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->json(['success' => false, 'message' => 'Method not allowed'], 405);
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
     * Bulk actions
     */
    public function bulkAction() {
        $this->requireAuth();
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/admin/news');
            return;
        }
        
        try {
            // Validate CSRF token
            if (!$this->validateCsrfToken()) {
                $this->setFlash('error', 'Invalid security token');
                $this->redirect('/admin/news');
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
        
        // Determine redirect
        $referer = $_SERVER['HTTP_REFERER'] ?? '';
        if (strpos($referer, '/admin/news-manager') !== false) {
            $this->redirect('/admin/news-manager');
        } else {
            $this->redirect('/admin/news');
        }
    }
    
    /**
     * Export to CSV
     */
    public function export() {
        $this->requireAuth();
        
        if (!$this->hasPermission('news_view')) {
            $this->setFlash('error', 'Access denied');
            $this->redirect('/admin/news');
            return;
        }
        
        try {
            // Get all content based on filters
            $filters = [
                'status' => $_GET['status'] ?? '',
                'type' => $_GET['type'] ?? '',
                'category' => $_GET['category'] ?? ''
            ];
            
            // Clean empty filters
            foreach ($filters as $key => $value) {
                if (empty($value)) {
                    unset($filters[$key]);
                }
            }
            
            $content = $this->newsModel->getAllWithFilters($filters, 1000, 0);
            
            // Set headers for CSV download
            header('Content-Type: text/csv; charset=utf-8');
            header('Content-Disposition: attachment; filename=news_export_' . date('Y-m-d') . '.csv');
            
            // Create output stream
            $output = fopen('php://output', 'w');
            
            // Add UTF-8 BOM for Excel
            fprintf($output, chr(0xEF).chr(0xBB).chr(0xBF));
            
            // Add headers
            fputcsv($output, [
                'ID', 'Title', 'Type', 'Category', 'Status', 'Featured',
                'Views', 'Author', 'Created At', 'Published At', 'URL'
            ]);
            
            // Add data
            foreach ($content as $item) {
                fputcsv($output, [
                    $item['id'],
                    $item['title'],
                    $item['type'] ?? 'news',
                    $item['category'] ?? '',
                    $item['is_published'] ? 'Published' : 'Draft',
                    $item['is_featured'] ? 'Yes' : 'No',
                    $item['views_count'] ?? 0,
                    $item['author_name'] ?? 'Unknown',
                    $item['created_at'] ?? '',
                    $item['published_at'] ?? '',
                    $this->getBaseUrl() . '/news/' . ($item['slug'] ?? '')
                ]);
            }
            
            fclose($output);
            exit;
            
        } catch (Exception $e) {
            error_log("Export error: " . $e->getMessage());
            $this->setFlash('error', 'Failed to export data');
            $this->redirect('/admin/news');
        }
    }
    
    // ============================================
    // HELPER METHODS
    // ============================================
    
    /**
     * Get dashboard URL based on user role
     */
    private function getDashboardUrl() {
        if (in_array($this->userRole, ['admin', 'super_admin', 'editor'])) {
            return '/admin/dashboard';
        }
        return '/dashboard';
    }
    
    /**
     * Get admin stats
     */
    private function getAdminStats() {
        $stats = [];
        
        $queries = [
            'total' => "SELECT COUNT(*) as total FROM news",
            'published' => "SELECT COUNT(*) as total FROM news WHERE is_published = 1",
            'draft' => "SELECT COUNT(*) as total FROM news WHERE is_published = 0",
            'featured' => "SELECT COUNT(*) as total FROM news WHERE is_featured = 1",
            'news' => "SELECT COUNT(*) as total FROM news WHERE (type = 'news' OR type IS NULL) AND is_published = 1",
            'events' => "SELECT COUNT(*) as total FROM news WHERE type = 'event' AND is_published = 1",
            'breaking' => "SELECT COUNT(*) as total FROM news WHERE is_breaking = 1",
            'this_month' => "SELECT COUNT(*) as total FROM news WHERE MONTH(created_at) = MONTH(CURDATE()) AND YEAR(created_at) = YEAR(CURDATE())",
            'this_week' => "SELECT COUNT(*) as total FROM news WHERE YEARWEEK(created_at) = YEARWEEK(CURDATE())"
        ];
        
        foreach ($queries as $key => $sql) {
            try {
                $stmt = $this->db->query($sql);
                $stats[$key] = (int)($stmt->fetch()['total'] ?? 0);
            } catch (Exception $e) {
                error_log("Stats query error for $key: " . $e->getMessage());
                $stats[$key] = 0;
            }
        }
        
        return $stats;
    }
    
    /**
     * Get comprehensive stats for news manager
     */
    private function getManagerStats() {
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
                $stmt = $this->db->query($sql);
                $stats[$key] = (int)($stmt->fetch()['total'] ?? 0);
            } catch (Exception $e) {
                error_log("Stats query error for $key: " . $e->getMessage());
                $stats[$key] = 0;
            }
        }
        
        return $stats;
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
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
            
        } catch (Exception $e) {
            error_log("Error getting categories with counts: " . $e->getMessage());
            return [];
        }
    }
    
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
     * Get recent activity
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
        if ($this->input('remove_image') == '1') {
            return '';
        }
        
        // Check if file was uploaded
        if (isset($_FILES['featured_image_upload']) && $_FILES['featured_image_upload']['error'] === UPLOAD_ERR_OK) {
            $file = $_FILES['featured_image_upload'];
            $type = $this->input('type', 'news');
            
            // Validate file type
            $allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp'];
            if (!in_array($file['type'], $allowedTypes)) {
                error_log("Invalid file type: " . $file['type']);
                return $this->input('featured_image', null);
            }
            
            // Validate file size (max 5MB)
            if ($file['size'] > 5 * 1024 * 1024) {
                error_log("File too large: " . $file['size']);
                return $this->input('featured_image', null);
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
                return $this->input('featured_image', null);
            }
        }
        
        // Keep existing image
        return $this->input('featured_image', null);
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
    
    // ============================================
    // DEBUG METHODS
    // ============================================
    
    /**
     * Debug method
     */
    public function debug() {
        error_log("=== DEBUG METHOD CALLED ===");
        echo "<h1>NewsController Debug</h1>";
        echo "<p>User Role: " . $this->userRole . "</p>";
        echo "<p>User ID: " . $this->userId . "</p>";
        echo "<p>Permissions: " . implode(', ', $this->permissions) . "</p>";
        exit;
    }
    
    /**
     * Test method
     */
    public function test() {
        echo "<h1>NewsController Test</h1>";
        echo "<p>If you see this, the controller is working!</p>";
        exit;
    }
    
    /**
     * Simple test method
     */
    public function simpleTest() {
        echo "<h1>Simple Test</h1>";
        echo "<p>NewsController simpleTest() method reached.</p>";
        exit;
    }
    
    /**
     * Direct test method
     */
    public function directTest() {
        echo "<h1>Direct Test</h1>";
        echo "<p>NewsController directTest() method reached.</p>";
        exit;
    }
    
    /**
     * Test edit direct
     */
    public function testEditDirect() {
        echo "<h1>Test Edit Direct</h1>";
        echo "<p>This is a test endpoint.</p>";
        exit;
    }
    
    /**
     * Test data flow
     */
    public function testDataFlow() {
        echo "<h1>Test Data Flow</h1>";
        echo "<p>This is a test endpoint.</p>";
        exit;
    }
    
    /**
     * Test both inserts
     */
    public function testBothInserts() {
        echo "<h1>Test Both Inserts</h1>";
        echo "<p>This is a test endpoint.</p>";
        exit;
    }
    
    /**
     * Test direct create
     */
    public function testDirectCreate() {
        echo "<h1>Test Direct Create</h1>";
        echo "<p>This is a test endpoint.</p>";
        exit;
    }
    
    /**
     * Test endpoint
     */
    public function testEndpoint() {
        echo "<h1>Test Endpoint</h1>";
        echo "<p>POST data received:</p>";
        echo "<pre>" . print_r($_POST, true) . "</pre>";
        exit;
    }
    
    /**
     * Test simple query
     */
    public function testSimpleQuery() {
        echo "<h1>Test Simple Query</h1>";
        
        try {
            $stmt = $this->db->query("SELECT COUNT(*) as count FROM news");
            $result = $stmt->fetch();
            echo "<p>Total news records: " . $result['count'] . "</p>";
        } catch (Exception $e) {
            echo "<p style='color: red;'>Error: " . $e->getMessage() . "</p>";
        }
        
        exit;
    }
    
    /**
     * Test fixes
     */
    public function testFixes() {
        echo "<h1>Test Fixes</h1>";
        echo "<p>This endpoint tests that all fixes are applied.</p>";
        
        echo "<h2>Permissions:</h2>";
        echo "<pre>" . print_r($this->permissions, true) . "</pre>";
        
        exit;
    }
    
    /**
     * Test image paths
     */
    public function testImagePaths() {
        echo "<h1>Test Image Paths</h1>";
        
        $uploadDir = $this->getUploadPath('news');
        echo "<p>Upload directory: " . $uploadDir . "</p>";
        echo "<p>Exists: " . (is_dir($uploadDir) ? 'Yes' : 'No') . "</p>";
        echo "<p>Writable: " . (is_writable($uploadDir) ? 'Yes' : 'No') . "</p>";
        
        if (is_dir($uploadDir)) {
            $files = scandir($uploadDir);
            echo "<p>Files: " . (count($files) - 2) . "</p>";
        }
        
        exit;
    }
}