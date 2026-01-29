<?php
/**
 * News Controller
 * Handles news management operations
 * Extends the base Controller class for common functionality
 */
class NewsController extends Controller {
    
    private $db;
    
    /**
     * Constructor
     */
    public function __construct() {
        parent::__construct();
        
        // Set admin layout
        $this->layout = 'admin';
        
        // Require authentication
        require_once __DIR__ . '/../middleware/AuthMiddleware.php';
        AuthMiddleware::authenticate();
        
        // Setup database
        require_once __DIR__ . '/../config/database.php';
        $database = Database::getInstance();
        $this->db = $database->getConnection();
        
        // Initialize common data
        $this->data = array_merge($this->data, [
            'user' => $_SESSION ?? [],
            'baseUrl' => defined('BASE_URL') ? BASE_URL : '',
            'currentPage' => 'news'
        ]);
    }
    
    /**
     * Display all news articles
     */
    public function index() {
        try {
            // Get all news articles
            $stmt = $this->db->query("
                SELECT n.*, u.username as author_name, u.full_name as author_full_name
                FROM news n 
                LEFT JOIN users u ON n.author_id = u.id 
                ORDER BY n.created_at DESC
            ");
            $news = $stmt->fetchAll();
            
            // Get statistics
            $statsStmt = $this->db->query("
                SELECT 
                    COUNT(*) as total,
                    SUM(CASE WHEN is_published = 1 THEN 1 ELSE 0 END) as published,
                    SUM(CASE WHEN is_featured = 1 THEN 1 ELSE 0 END) as featured,
                    SUM(CASE WHEN is_breaking = 1 THEN 1 ELSE 0 END) as breaking
                FROM news
            ");
            $stats = $statsStmt->fetch();
            
            // Set data for view
            $this->data = array_merge($this->data, [
                'news' => $news,
                'stats' => $stats,
                'pageTitle' => 'News Management - FCT College of Nursing Sciences',
                'pageDescription' => 'Manage news articles and announcements'
            ]);
            
            // Render view
            $this->render('admin/news/index');
            
        } catch (Exception $e) {
            error_log("NewsController index error: " . $e->getMessage());
            $this->showError("Failed to load news articles.");
        }
    }
    
    /**
     * Display create news form
     */
    public function create() {
        // Get categories
        $categories = $this->getCategories();
        
        // Set data for view
        $this->data = array_merge($this->data, [
            'categories' => $categories,
            'pageTitle' => 'Create News Article - FCT College of Nursing Sciences',
            'pageDescription' => 'Create a new news article'
        ]);
        
        $this->render('admin/news/create');
    }
    
    /**
     * Save new news article
     */
    public function store() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/admin/news/create');
            return;
        }

        try {
            // Validate CSRF token
            $this->validateCsrf();
            
            $title = $this->input('title', '');
            $slug = $this->input('slug', $this->generateSlug($title));
            $excerpt = $this->input('excerpt', '');
            $content = $this->input('content', '');
            $category = $this->input('category', '');
            $tags = $this->input('tags', '');
            $featured_image = $this->input('featured_image', '');
            $is_published = $this->input('is_published', 0) ? 1 : 0;
            $is_featured = $this->input('is_featured', 0) ? 1 : 0;
            $is_breaking = $this->input('is_breaking', 0) ? 1 : 0;
            $author_id = $_SESSION['user_id'] ?? 1;
            
            // Validate
            if (empty($title) || empty($content)) {
                throw new Exception("Title and content are required.");
            }
            
            // Check if slug already exists
            $slugCheckStmt = $this->db->prepare("SELECT id FROM news WHERE slug = ?");
            $slugCheckStmt->execute([$slug]);
            if ($slugCheckStmt->fetch()) {
                // Append timestamp to make slug unique
                $slug = $slug . '-' . time();
            }
            
            // Prepare SQL
            $stmt = $this->db->prepare("
                INSERT INTO news (
                    title, slug, excerpt, content, author_id, category, tags,
                    featured_image, is_published, is_featured, is_breaking,
                    published_at, created_at, updated_at
                ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), NOW(), NOW())
            ");
            
            // Execute
            $stmt->execute([
                $title, $slug, $excerpt, $content, $author_id, $category, $tags,
                $featured_image, $is_published, $is_featured, $is_breaking
            ]);
            
            // Get the new news ID
            $newNewsId = $this->db->lastInsertId();
            
            // Log activity
            $this->logActivity('news_created', "News article '{$title}' created");
            
            // Set success message
            $this->flash('success', 'News article created successfully!');
            
            // Redirect to news list
            $this->redirect('/admin/news');
            
        } catch (Exception $e) {
            error_log("NewsController store error: " . $e->getMessage());
            
            // Get categories for form
            $categories = $this->getCategories();
            
            // Set data with error for create form
            $this->data = array_merge($this->data, [
                'categories' => $categories,
                'error' => $e->getMessage(),
                'formData' => [
                    'title' => $this->input('title', ''),
                    'slug' => $this->input('slug', ''),
                    'excerpt' => $this->input('excerpt', ''),
                    'content' => $this->input('content', ''),
                    'category' => $this->input('category', ''),
                    'tags' => $this->input('tags', ''),
                    'featured_image' => $this->input('featured_image', ''),
                    'is_published' => $this->input('is_published', 0),
                    'is_featured' => $this->input('is_featured', 0),
                    'is_breaking' => $this->input('is_breaking', 0)
                ],
                'pageTitle' => 'Create News Article - FCT College of Nursing Sciences',
                'pageDescription' => 'Create a new news article'
            ]);
            
            $this->render('admin/news/create');
        }
    }
    
    /**
     * Display single news article
     */
    public function show($id) {
        try {
            $stmt = $this->db->prepare("
                SELECT n.*, u.username as author_name, u.full_name as author_full_name
                FROM news n 
                LEFT JOIN users u ON n.author_id = u.id 
                WHERE n.id = ?
            ");
            $stmt->execute([$id]);
            $news = $stmt->fetch();
            
            if (!$news) {
                $this->flash('error', 'News article not found.');
                $this->redirect('/admin/news');
                return;
            }
            
            // Get related news
            $relatedStmt = $this->db->prepare("
                SELECT id, title, slug, created_at, featured_image
                FROM news 
                WHERE category = ? AND id != ? AND is_published = 1
                ORDER BY created_at DESC 
                LIMIT 3
            ");
            $relatedStmt->execute([$news['category'], $id]);
            $relatedNews = $relatedStmt->fetchAll();
            
            // Set data for view
            $this->data = array_merge($this->data, [
                'news' => $news,
                'relatedNews' => $relatedNews,
                'pageTitle' => $news['title'] . ' - FCT College of Nursing Sciences',
                'pageDescription' => $news['excerpt'] ?: substr(strip_tags($news['content']), 0, 150) . '...'
            ]);
            
            $this->render('admin/news/show');
            
        } catch (Exception $e) {
            error_log("NewsController show error: " . $e->getMessage());
            $this->showError($e->getMessage());
        }
    }
    
    /**
     * Display edit news form
     */
    public function edit($id) {
        try {
            $stmt = $this->db->prepare("SELECT * FROM news WHERE id = ?");
            $stmt->execute([$id]);
            $news = $stmt->fetch();
            
            if (!$news) {
                $this->flash('error', 'News article not found.');
                $this->redirect('/admin/news');
                return;
            }
            
            // Get categories
            $categories = $this->getCategories();
            
            // Set data for view
            $this->data = array_merge($this->data, [
                'news' => $news,
                'categories' => $categories,
                'pageTitle' => 'Edit News Article - ' . $news['title'],
                'pageDescription' => 'Edit news article'
            ]);
            
            $this->render('admin/news/edit');
            
        } catch (Exception $e) {
            error_log("NewsController edit error: " . $e->getMessage());
            $this->showError($e->getMessage());
        }
    }
    
    /**
     * Update news article
     */
    public function update($id) {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->flash('error', 'Invalid request method.');
            $this->redirect('/admin/news/' . $id . '/edit');
            return;
        }

        try {
            // Validate CSRF token
            $this->validateCsrf();
            
            $title = $this->input('title', '');
            $slug = $this->input('slug', $this->generateSlug($title));
            $excerpt = $this->input('excerpt', '');
            $content = $this->input('content', '');
            $category = $this->input('category', '');
            $tags = $this->input('tags', '');
            $featured_image = $this->input('featured_image', '');
            $is_published = $this->input('is_published', 0) ? 1 : 0;
            $is_featured = $this->input('is_featured', 0) ? 1 : 0;
            $is_breaking = $this->input('is_breaking', 0) ? 1 : 0;
            
            // Validate
            if (empty($title) || empty($content)) {
                throw new Exception("Title and content are required.");
            }
            
            // Check if slug already exists (excluding current article)
            $slugCheckStmt = $this->db->prepare("SELECT id FROM news WHERE slug = ? AND id != ?");
            $slugCheckStmt->execute([$slug, $id]);
            if ($slugCheckStmt->fetch()) {
                // Append timestamp to make slug unique
                $slug = $slug . '-' . time();
            }
            
            // Prepare SQL
            $stmt = $this->db->prepare("
                UPDATE news 
                SET title = ?, slug = ?, excerpt = ?, content = ?, 
                    category = ?, tags = ?, featured_image = ?,
                    is_published = ?, is_featured = ?, is_breaking = ?,
                    updated_at = NOW()
                WHERE id = ?
            ");
            
            // Execute
            $stmt->execute([
                $title, $slug, $excerpt, $content, $category, $tags,
                $featured_image, $is_published, $is_featured, $is_breaking, $id
            ]);
            
            // Log activity
            $this->logActivity('news_updated', "News article #{$id} '{$title}' updated");
            
            // Set success message
            $this->flash('success', 'News article updated successfully!');
            
            // Redirect to news list
            $this->redirect('/admin/news');
            
        } catch (Exception $e) {
            error_log("NewsController update error: " . $e->getMessage());
            
            // Get news data for the form
            try {
                $stmt = $this->db->prepare("SELECT * FROM news WHERE id = ?");
                $stmt->execute([$id]);
                $news = $stmt->fetch();
                
                // Get categories
                $categories = $this->getCategories();
                
                $this->data = array_merge($this->data, [
                    'news' => $news,
                    'categories' => $categories,
                    'error' => $e->getMessage(),
                    'pageTitle' => 'Edit News Article - ' . ($news['title'] ?? 'Unknown'),
                    'pageDescription' => 'Edit news article'
                ]);
                
                $this->render('admin/news/edit');
            } catch (Exception $ex) {
                $this->showError($e->getMessage());
            }
        }
    }
    
    /**
     * Delete news article
     */
    public function destroy($id) {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->flash('error', 'Invalid request method.');
            $this->redirect('/admin/news');
            return;
        }

        try {
            // Validate CSRF token
            $this->validateCsrf();
            
            // Get news title before deletion for logging
            $stmt = $this->db->prepare("SELECT title FROM news WHERE id = ?");
            $stmt->execute([$id]);
            $news = $stmt->fetch();
            
            if (!$news) {
                throw new Exception("News article not found.");
            }
            
            // Delete news article
            $deleteStmt = $this->db->prepare("DELETE FROM news WHERE id = ?");
            $deleteStmt->execute([$id]);
            
            // Log activity
            $this->logActivity('news_deleted', "News article '{$news['title']}' deleted");
            
            // Set success message
            $this->flash('success', 'News article deleted successfully!');
            
        } catch (Exception $e) {
            error_log("NewsController destroy error: " . $e->getMessage());
            $this->flash('error', 'Failed to delete news article: ' . $e->getMessage());
        }

        $this->redirect('/admin/news');
    }
    
    /**
     * Toggle news article status
     */
    public function toggleStatus($id) {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/admin/news');
            return;
        }

        try {
            // Validate CSRF token
            $this->validateCsrf();
            
            $field = $this->input('field', '');
            $value = $this->input('value', 0);
            
            if (!in_array($field, ['is_published', 'is_featured', 'is_breaking'])) {
                throw new Exception("Invalid field specified.");
            }
            
            $stmt = $this->db->prepare("UPDATE news SET {$field} = ?, updated_at = NOW() WHERE id = ?");
            $stmt->execute([$value, $id]);
            
            $fieldName = str_replace('is_', '', $field);
            $status = $value ? 'enabled' : 'disabled';
            
            $this->flash('success', ucfirst($fieldName) . " status {$status} successfully!");
            
        } catch (Exception $e) {
            error_log("NewsController toggleStatus error: " . $e->getMessage());
            $this->flash('error', 'Failed to update status: ' . $e->getMessage());
        }

        $this->redirect('/admin/news');
    }
    
    /**
     * Bulk operations on news articles
     */
    public function bulkAction() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/admin/news');
            return;
        }

        try {
            // Validate CSRF token
            $this->validateCsrf();
            
            $action = $this->input('action', '');
            $newsIds = $this->input('news_ids', []);
            
            if (empty($newsIds) || !is_array($newsIds)) {
                throw new Exception("No news articles selected.");
            }
            
            $ids = implode(',', array_map('intval', $newsIds));
            
            switch ($action) {
                case 'publish':
                    $this->db->exec("UPDATE news SET is_published = 1, updated_at = NOW() WHERE id IN ({$ids})");
                    $message = "Selected news articles published successfully!";
                    break;
                    
                case 'unpublish':
                    $this->db->exec("UPDATE news SET is_published = 0, updated_at = NOW() WHERE id IN ({$ids})");
                    $message = "Selected news articles unpublished successfully!";
                    break;
                    
                case 'feature':
                    $this->db->exec("UPDATE news SET is_featured = 1, updated_at = NOW() WHERE id IN ({$ids})");
                    $message = "Selected news articles marked as featured!";
                    break;
                    
                case 'unfeature':
                    $this->db->exec("UPDATE news SET is_featured = 0, updated_at = NOW() WHERE id IN ({$ids})");
                    $message = "Selected news articles unfeatured successfully!";
                    break;
                    
                case 'delete':
                    $this->db->exec("DELETE FROM news WHERE id IN ({$ids})");
                    $message = "Selected news articles deleted successfully!";
                    break;
                    
                default:
                    throw new Exception("Invalid action specified.");
            }
            
            // Log activity
            $this->logActivity('news_bulk_action', "Bulk action '{$action}' performed on news articles");
            
            $this->flash('success', $message);
            
        } catch (Exception $e) {
            error_log("NewsController bulkAction error: " . $e->getMessage());
            $this->flash('error', 'Failed to perform bulk action: ' . $e->getMessage());
        }

        $this->redirect('/admin/news');
    }
    
    /**
     * Export news to CSV
     */
    public function export() {
        try {
            // Get all news articles
            $stmt = $this->db->query("
                SELECT n.*, u.username as author_name, u.full_name as author_full_name
                FROM news n 
                LEFT JOIN users u ON n.author_id = u.id 
                ORDER BY n.created_at DESC
            ");
            $news = $stmt->fetchAll();
            
            // Set headers for CSV download
            $this->header('Content-Type', 'text/csv; charset=utf-8');
            $this->header('Content-Disposition', 'attachment; filename=news_articles_' . date('Y-m-d') . '.csv');
            
            // Create output stream
            $output = fopen('php://output', 'w');
            
            // Add CSV headers
            fputcsv($output, [
                'ID', 'Title', 'Slug', 'Category', 'Author', 'Status',
                'Featured', 'Breaking', 'Published Date', 'Created Date', 'Views'
            ]);
            
            // Add data rows
            foreach ($news as $article) {
                fputcsv($output, [
                    $article['id'],
                    $article['title'],
                    $article['slug'],
                    $article['category'],
                    $article['author_full_name'] ?? $article['author_name'] ?? 'N/A',
                    $article['is_published'] ? 'Published' : 'Draft',
                    $article['is_featured'] ? 'Yes' : 'No',
                    $article['is_breaking'] ? 'Yes' : 'No',
                    date('Y-m-d', strtotime($article['published_at'])),
                    date('Y-m-d', strtotime($article['created_at'])),
                    $article['views'] ?? 0
                ]);
            }
            
            fclose($output);
            exit;
            
        } catch (Exception $e) {
            error_log("NewsController export error: " . $e->getMessage());
            $this->flash('error', 'Failed to export news articles.');
            $this->redirect('/admin/news');
        }
    }
    
    /**
     * Get categories from existing news
     */
    private function getCategories() {
        try {
            $stmt = $this->db->query("
                SELECT DISTINCT category 
                FROM news 
                WHERE category IS NOT NULL AND category != ''
                ORDER BY category
            ");
            $categories = $stmt->fetchAll(PDO::FETCH_COLUMN);
            
            // Default categories if none found
            if (empty($categories)) {
                $categories = [
                    'General',
                    'Announcements',
                    'Events',
                    'Academics',
                    'Research',
                    'Student Life',
                    'Admissions',
                    'Alumni'
                ];
            }
            
            return $categories;
        } catch (Exception $e) {
            error_log("NewsController getCategories error: " . $e->getMessage());
            return ['General', 'Announcements', 'Events', 'Academics'];
        }
    }
    
    /**
     * Helper to generate slug from title
     */
    private function generateSlug($title) {
        if (empty($title)) {
            return '';
        }
        
        $slug = strtolower(trim($title));
        $slug = preg_replace('/[^a-z0-9-]+/', '-', $slug);
        $slug = preg_replace('/-+/', '-', $slug);
        $slug = trim($slug, '-');
        return $slug;
    }
    
    /**
     * Log activity
     */
    private function logActivity($action, $description) {
        try {
            $user_id = $_SESSION['user_id'] ?? null;
            $ip_address = $_SERVER['REMOTE_ADDR'] ?? '';
            $user_agent = $_SERVER['HTTP_USER_AGENT'] ?? '';
            
            $stmt = $this->db->prepare("
                INSERT INTO activity_logs 
                (user_id, action, description, ip_address, user_agent, created_at)
                VALUES (?, ?, ?, ?, ?, NOW())
            ");
            $stmt->execute([$user_id, $action, $description, $ip_address, $user_agent]);
        } catch (Exception $e) {
            error_log("Failed to log activity: " . $e->getMessage());
        }
    }
    
    /**
     * Override render method for admin-specific views
     */
    protected function render($view = null, $data = []) {
        // Add CSRF token to all forms
        $data['csrf_token'] = $this->csrfToken();
        
        // Add flash messages
        $data['flash_success'] = $this->getFlash('success');
        $data['flash_error'] = $this->getFlash('error');
        
        // Merge with controller data
        $this->data = array_merge($this->data, $data);
        
        // Call parent render method
        parent::render($view);
    }
    
    /**
     * Show error message
     */
    private function showError($message) {
        $this->data = array_merge($this->data, [
            'error' => $message,
            'pageTitle' => 'Error - FCT College of Nursing Sciences',
            'pageDescription' => 'An error occurred'
        ]);
        
        // Try to render error view
        $errorViewPath = APP_PATH . '/views/admin/error.php';
        if (file_exists($errorViewPath)) {
            $this->render('admin/error');
        } else {
            // Fallback error display
            echo '<div style="padding: 20px; background: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; border-radius: 5px; margin: 20px;">';
            echo '<h3>Error</h3>';
            echo '<p>' . htmlspecialchars($message) . '</p>';
            echo '<p><a href="' . ($this->data['baseUrl'] ?? '') . '/admin/dashboard">Back to Dashboard</a></p>';
            echo '</div>';
        }
    }
}