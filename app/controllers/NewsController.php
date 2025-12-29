<?php
/**
 * News Controller
 * Handles news management operations
 */
class NewsController {
    
    private $db;
    
    public function __construct() {
        // Require authentication first
        require_once __DIR__ . '/../middleware/AuthMiddleware.php';
        AuthMiddleware::authenticate();
        
        // Then setup database
        require_once __DIR__ . '/../config/database.php';
        $database = Database::getInstance();
        $this->db = $database->getConnection();
    }
    
    /**
     * Display all news articles
     */
    public function index() {
        try {
            // Get all news articles - updated to match your table structure
            $stmt = $this->db->query("
                SELECT n.*, u.username as author_name 
                FROM news n 
                LEFT JOIN users u ON n.author_id = u.id 
                ORDER BY n.created_at DESC
            ");
            $news = $stmt->fetchAll();
            
            // Load view with data
            $this->loadView('admin/news', ['news' => $news]);
            
        } catch (Exception $e) {
            error_log("NewsController index error: " . $e->getMessage());
            $this->showError("Failed to load news articles.");
        }
    }
    
    /**
     * Display create news form
     */
    public function create() {
        $this->loadView('admin/news_create', []);
    }
    
    /**
     * Save new news article - UPDATED FOR YOUR SCHEMA
     */
    public function store() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                $title = $_POST['title'] ?? '';
                $slug = $_POST['slug'] ?? $this->generateSlug($title);
                $excerpt = $_POST['excerpt'] ?? '';
                $content = $_POST['content'] ?? '';
                $category = $_POST['category'] ?? '';
                $tags = $_POST['tags'] ?? '';
                $featured_image = $_POST['featured_image'] ?? '';
                $is_published = isset($_POST['is_published']) ? 1 : 0;
                $is_featured = isset($_POST['is_featured']) ? 1 : 0;
                $is_breaking = isset($_POST['is_breaking']) ? 1 : 0;
                $author_id = $_SESSION['user_id'] ?? 1;
                
                // Validate
                if (empty($title) || empty($content)) {
                    throw new Exception("Title and content are required.");
                }
                
                // Prepare SQL - matches your table structure exactly
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
                
                // Redirect to news list
                header("Location: " . BASE_URL . "/admin/news");
                exit;
                
            } catch (Exception $e) {
                error_log("NewsController store error: " . $e->getMessage());
                $this->loadView('admin/news_create', ['error' => $e->getMessage()]);
            }
        } else {
            header("Location: " . BASE_URL . "/admin/news/create");
            exit;
        }
    }
    
    /**
     * Display single news article
     */
    public function show($id) {
        try {
            $stmt = $this->db->prepare("
                SELECT n.*, u.username as author_name 
                FROM news n 
                LEFT JOIN users u ON n.author_id = u.id 
                WHERE n.id = ?
            ");
            $stmt->execute([$id]);
            $news = $stmt->fetch();
            
            if (!$news) {
                throw new Exception("News article not found.");
            }
            
            $this->loadView('admin/news_show', ['news' => $news]);
            
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
                throw new Exception("News article not found.");
            }
            
            $this->loadView('admin/news_edit', ['news' => $news]);
            
        } catch (Exception $e) {
            error_log("NewsController edit error: " . $e->getMessage());
            $this->showError($e->getMessage());
        }
    }
    
    /**
     * Update news article
     */
    public function update($id) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                $title = $_POST['title'] ?? '';
                $slug = $_POST['slug'] ?? $this->generateSlug($title);
                $excerpt = $_POST['excerpt'] ?? '';
                $content = $_POST['content'] ?? '';
                $category = $_POST['category'] ?? '';
                $tags = $_POST['tags'] ?? '';
                $featured_image = $_POST['featured_image'] ?? '';
                $is_published = isset($_POST['is_published']) ? 1 : 0;
                $is_featured = isset($_POST['is_featured']) ? 1 : 0;
                $is_breaking = isset($_POST['is_breaking']) ? 1 : 0;
                
                // Validate
                if (empty($title) || empty($content)) {
                    throw new Exception("Title and content are required.");
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
                
                // Redirect to news list
                header("Location: " . BASE_URL . "/admin/news");
                exit;
                
            } catch (Exception $e) {
                error_log("NewsController update error: " . $e->getMessage());
                
                // Get news data for the form
                try {
                    $stmt = $this->db->prepare("SELECT * FROM news WHERE id = ?");
                    $stmt->execute([$id]);
                    $news = $stmt->fetch();
                    
                    $this->loadView('admin/news_edit', [
                        'news' => $news,
                        'error' => $e->getMessage()
                    ]);
                } catch (Exception $ex) {
                    $this->showError($e->getMessage());
                }
            }
        } else {
            header("Location: " . BASE_URL . "/admin/news");
            exit;
        }
    }
    
    /**
     * Delete news article
     */
    public function destroy($id) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                $stmt = $this->db->prepare("DELETE FROM news WHERE id = ?");
                $stmt->execute([$id]);
                
                // Redirect to news list
                header("Location: " . BASE_URL . "/admin/news");
                exit;
                
            } catch (Exception $e) {
                error_log("NewsController destroy error: " . $e->getMessage());
                $this->showError($e->getMessage());
            }
        } else {
            header("Location: " . BASE_URL . "/admin/news");
            exit;
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
     * Helper method to load views
     */
    private function loadView($view, $data = []) {
        // Define APP_PATH if not defined
        if (!defined('APP_PATH')) {
            define('APP_PATH', dirname(__DIR__));
        }
        
        // Define BASE_URL if not defined
        if (!defined('BASE_URL')) {
            // Try to get BASE_URL from constants file
            $constantsPath = APP_PATH . '/config/constants.php';
            if (file_exists($constantsPath)) {
                require_once $constantsPath;
            } else {
                // Fallback definition
                define('BASE_URL', 'http://localhost/fctcns-website');
            }
        }
        
        // Extract data for the view
        extract($data);
        
        // Include the view file
        $viewPath = APP_PATH . '/views/' . $view . '.php';
        
        if (file_exists($viewPath)) {
            require_once $viewPath;
        } else {
            // Fallback error
            echo "<h1>View not found</h1>";
            echo "<p>View file not found: " . htmlspecialchars($viewPath) . "</p>";
            echo "<p>Looking for: " . htmlspecialchars($view) . ".php</p>";
            echo "<p><a href='" . BASE_URL . "/admin/dashboard'>Return to Dashboard</a></p>";
        }
    }
    
    /**
     * Show error message
     */
    private function showError($message) {
        // Ensure BASE_URL is defined
        if (!defined('BASE_URL')) {
            define('BASE_URL', 'http://localhost/fctcns-website');
        }
        
        echo '<div style="padding: 20px; background: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; border-radius: 5px; margin: 20px;">';
        echo '<h3>Error</h3>';
        echo '<p>' . htmlspecialchars($message) . '</p>';
        echo '<p><a href="' . BASE_URL . '/admin/dashboard">Back to Dashboard</a></p>';
        echo '</div>';
    }
}