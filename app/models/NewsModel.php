<?php
/**
 * News Model
 * Handles news data operations for FCT College of Nursing Sciences
 */
class NewsModel {
    private $db;
    
    public function __construct($database = null) {
        if ($database) {
            $this->db = $database;
        } else {
            require_once __DIR__ . '/../config/database.php';
            $database = Database::getInstance();
            $this->db = $database->getConnection();
        }
    }
    
    // ============================================
    // ADMIN METHODS
    // ============================================
    
    /**
     * Get all news with optional filters, limit, and offset
     */
    public function getAll($filters = [], $limit = 20, $offset = 0) {
        $whereClauses = [];
        $params = [];
        
        // Build WHERE clause based on filters
        if (!empty($filters)) {
            if (isset($filters['status'])) {
                if ($filters['status'] === 'published') {
                    $whereClauses[] = "is_published = 1";
                } elseif ($filters['status'] === 'draft') {
                    $whereClauses[] = "is_published = 0";
                } elseif ($filters['status'] === 'featured') {
                    $whereClauses[] = "is_featured = 1";
                }
            }
            
            if (isset($filters['category']) && $filters['category']) {
                $whereClauses[] = "category = ?";
                $params[] = $filters['category'];
            }
            
            if (isset($filters['search']) && $filters['search']) {
                $whereClauses[] = "(title LIKE ? OR content LIKE ? OR excerpt LIKE ?)";
                $searchTerm = '%' . $filters['search'] . '%';
                $params[] = $searchTerm;
                $params[] = $searchTerm;
                $params[] = $searchTerm;
            }
            
            if (isset($filters['author_id']) && $filters['author_id']) {
                $whereClauses[] = "author_id = ?";
                $params[] = $filters['author_id'];
            }
            
            if (isset($filters['date_from']) && $filters['date_from']) {
                $whereClauses[] = "DATE(created_at) >= ?";
                $params[] = $filters['date_from'];
            }
            
            if (isset($filters['date_to']) && $filters['date_to']) {
                $whereClauses[] = "DATE(created_at) <= ?";
                $params[] = $filters['date_to'];
            }
        }
        
        $whereSQL = !empty($whereClauses) ? 'WHERE ' . implode(' AND ', $whereClauses) : '';
        
        $sql = "SELECT n.*, u.username as author_name 
                FROM news n 
                LEFT JOIN users u ON n.author_id = u.id 
                $whereSQL 
                ORDER BY n.created_at DESC 
                LIMIT ? OFFSET ?";
        
        $params[] = $limit;
        $params[] = $offset;
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    /**
     * Get news by ID
     */
    public function getById($id) {
        $sql = "SELECT n.*, u.username as author_name, u.email as author_email 
                FROM news n 
                LEFT JOIN users u ON n.author_id = u.id 
                WHERE n.id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    /**
     * Get news statistics for dashboard
     */
    public function getStats() {
        $stats = [];
        
        // Total news
        $stmt = $this->db->query("SELECT COUNT(*) as total FROM news");
        $stats['total_news'] = $stmt->fetch()['total'];
        
        // Published news
        $stmt = $this->db->query("SELECT COUNT(*) as total FROM news WHERE is_published = 1");
        $stats['published_news'] = $stmt->fetch()['total'];
        
        // Draft news
        $stmt = $this->db->query("SELECT COUNT(*) as total FROM news WHERE is_published = 0");
        $stats['draft_news'] = $stmt->fetch()['total'];
        
        // Featured news
        $stmt = $this->db->query("SELECT COUNT(*) as total FROM news WHERE is_featured = 1");
        $stats['featured_news'] = $stmt->fetch()['total'];
        
        // Breaking news
        $stmt = $this->db->query("SELECT COUNT(*) as total FROM news WHERE is_breaking = 1");
        $stats['breaking_news'] = $stmt->fetch()['total'];
        
        // Today's news
        $stmt = $this->db->query("SELECT COUNT(*) as total FROM news WHERE DATE(created_at) = CURDATE()");
        $stats['today_news'] = $stmt->fetch()['total'];
        
        // This month's news
        $stmt = $this->db->query("SELECT COUNT(*) as total FROM news WHERE MONTH(created_at) = MONTH(CURDATE()) AND YEAR(created_at) = YEAR(CURDATE())");
        $stats['month_news'] = $stmt->fetch()['total'];
        
        return $stats;
    }
    
    /**
     * Create new news article
     */
    public function create($data) {
        // Set author_id from session if not provided
        if (!isset($data['author_id']) && isset($_SESSION['user_id'])) {
            $data['author_id'] = $_SESSION['user_id'];
        }
        
        // Generate slug if not provided
        if (empty($data['slug']) && !empty($data['title'])) {
            $data['slug'] = $this->generateSlug($data['title']);
        }
        
        // Set default values
        $defaults = [
            'is_published' => 0,
            'is_featured' => 0,
            'is_breaking' => 0,
            'views_count' => 0,
            'likes_count' => 0,
            'shares_count' => 0,
            'comments_count' => 0,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ];
        
        $data = array_merge($defaults, $data);
        
        // Handle published_at
        if (isset($data['is_published']) && $data['is_published'] == 1 && empty($data['published_at'])) {
            $data['published_at'] = date('Y-m-d H:i:s');
        }
        
        // Prepare SQL
        $fields = array_keys($data);
        $placeholders = array_fill(0, count($fields), '?');
        $values = array_values($data);
        
        $sql = "INSERT INTO news (" . implode(', ', $fields) . ") 
                VALUES (" . implode(', ', $placeholders) . ")";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute($values);
        
        return $this->db->lastInsertId();
    }
    
    /**
     * Update news article
     */
    public function update($id, $data) {
        // Don't allow updating ID
        if (isset($data['id'])) {
            unset($data['id']);
        }
        
        // Set updated_at
        $data['updated_at'] = date('Y-m-d H:i:s');
        
        // Handle published_at if status changed to published
        if (isset($data['is_published']) && $data['is_published'] == 1) {
            $current = $this->getById($id);
            if (!$current || !$current['published_at']) {
                $data['published_at'] = date('Y-m-d H:i:s');
            }
        }
        
        // Prepare SQL
        $fields = array_keys($data);
        $setClause = implode(' = ?, ', $fields) . ' = ?';
        $values = array_values($data);
        $values[] = $id; // For WHERE clause
        
        $sql = "UPDATE news SET $setClause WHERE id = ?";
        
        $stmt = $this->db->prepare($sql);
        return $stmt->execute($values);
    }
    
    /**
     * Delete news article
     */
    public function delete($id) {
        $sql = "DELETE FROM news WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$id]);
    }
    
    /**
     * Toggle status fields (publish/feature/breaking)
     */
    public function toggleStatus($id, $field) {
        $allowedFields = ['is_published', 'is_featured', 'is_breaking'];
        
        if (!in_array($field, $allowedFields)) {
            return false;
        }
        
        // Get current value
        $sql = "SELECT $field FROM news WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id]);
        $current = $stmt->fetch();
        
        if (!$current) {
            return false;
        }
        
        // Toggle value
        $newValue = $current[$field] ? 0 : 1;
        $updateData = [
            $field => $newValue,
            'updated_at' => date('Y-m-d H:i:s')
        ];
        
        // Set published_at if publishing
        if ($field === 'is_published' && $newValue == 1) {
            $updateData['published_at'] = date('Y-m-d H:i:s');
        }
        
        return $this->update($id, $updateData);
    }
    
    /**
     * Search news articles
     */
    public function search($query, $filters = []) {
        $whereClauses = ["(title LIKE ? OR content LIKE ? OR excerpt LIKE ?)"];
        $params = ["%$query%", "%$query%", "%$query%"];
        
        // Add filters
        if (isset($filters['category']) && $filters['category']) {
            $whereClauses[] = "category = ?";
            $params[] = $filters['category'];
        }
        
        if (isset($filters['status'])) {
            if ($filters['status'] === 'published') {
                $whereClauses[] = "is_published = 1";
            } elseif ($filters['status'] === 'draft') {
                $whereClauses[] = "is_published = 0";
            }
        }
        
        if (isset($filters['author_id']) && $filters['author_id']) {
            $whereClauses[] = "author_id = ?";
            $params[] = $filters['author_id'];
        }
        
        $whereSQL = implode(' AND ', $whereClauses);
        
        $sql = "SELECT n.*, u.username as author_name 
                FROM news n 
                LEFT JOIN users u ON n.author_id = u.id 
                WHERE $whereSQL 
                ORDER BY created_at DESC 
                LIMIT 100";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    /**
     * Get distinct categories from existing data
     */
    public function getCategories() {
        $sql = "SELECT DISTINCT category FROM news WHERE category IS NOT NULL AND category != '' ORDER BY category";
        $stmt = $this->db->query($sql);
        $categories = $stmt->fetchAll(PDO::FETCH_COLUMN);
        
        // Add default categories if none exist
        if (empty($categories)) {
            $categories = [
                'Announcements',
                'Events',
                'Academic News',
                'Research Updates',
                'Student Life',
                'Faculty News',
                'Alumni News',
                'Community Outreach'
            ];
        }
        
        return $categories;
    }
    
    // ============================================
    // PUBLIC METHODS
    // ============================================
    
    /**
     * Get published news for public website
     */
    public function getPublished($limit = 10, $offset = 0) {
        $sql = "SELECT n.*, u.username as author_name 
                FROM news n 
                LEFT JOIN users u ON n.author_id = u.id 
                WHERE n.is_published = 1 
                AND (n.published_at IS NULL OR n.published_at <= NOW())
                ORDER BY n.published_at DESC, n.created_at DESC 
                LIMIT ? OFFSET ?";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$limit, $offset]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    /**
     * Get news by slug for public viewing
     */
    public function getBySlug($slug) {
        $sql = "SELECT n.*, u.username as author_name, u.email as author_email 
                FROM news n 
                LEFT JOIN users u ON n.author_id = u.id 
                WHERE n.slug = ? AND n.is_published = 1 
                AND (n.published_at IS NULL OR n.published_at <= NOW())";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$slug]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    /**
     * Get featured news for homepage
     */
    public function getFeatured($limit = 3) {
        $sql = "SELECT n.*, u.username as author_name 
                FROM news n 
                LEFT JOIN users u ON n.author_id = u.id 
                WHERE n.is_published = 1 AND n.is_featured = 1 
                AND (n.published_at IS NULL OR n.published_at <= NOW())
                ORDER BY n.published_at DESC, n.created_at DESC 
                LIMIT ?";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$limit]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    /**
     * Get news by category for public
     */
    public function getByCategory($category, $limit = 10, $offset = 0) {
        $sql = "SELECT n.*, u.username as author_name 
                FROM news n 
                LEFT JOIN users u ON n.author_id = u.id 
                WHERE n.is_published = 1 AND n.category = ?
                AND (n.published_at IS NULL OR n.published_at <= NOW())
                ORDER BY n.published_at DESC, n.created_at DESC 
                LIMIT ? OFFSET ?";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$category, $limit, $offset]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    /**
     * Get related news articles
     */
    public function getRelated($newsId, $limit = 3) {
        // First get the current news article
        $current = $this->getById($newsId);
        if (!$current) {
            return [];
        }
        
        $whereClauses = ["n.is_published = 1", "n.id != ?"];
        $params = [$newsId];
        
        // Try to find by same category
        if (!empty($current['category'])) {
            $whereClauses[] = "n.category = ?";
            $params[] = $current['category'];
        }
        
        // Try to find by tags if available
        $tags = [];
        if (!empty($current['tags'])) {
            $tags = explode(',', $current['tags']);
            $tags = array_map('trim', $tags);
            $tags = array_filter($tags);
        }
        
        $whereSQL = implode(' AND ', $whereClauses);
        
        $sql = "SELECT n.*, u.username as author_name 
                FROM news n 
                LEFT JOIN users u ON n.author_id = u.id 
                WHERE $whereSQL 
                AND (n.published_at IS NULL OR n.published_at <= NOW())
                ORDER BY n.published_at DESC, n.created_at DESC 
                LIMIT ?";
        
        $params[] = $limit;
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    /**
     * Increment view count
     */
    public function incrementViews($id) {
        $sql = "UPDATE news SET views_count = views_count + 1, updated_at = NOW() WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$id]);
    }
    
    /**
     * Get latest news (existing method - enhanced)
     */
    public function getLatestNews($limit = 3) {
        return $this->getPublished($limit, 0);
    }
    
    /**
     * Find all news (existing method - enhanced)
     */
    public function findAll() {
        return $this->getAll([], 1000, 0);
    }
    
    /**
     * Get breaking news
     */
    public function getBreakingNews($limit = 5) {
        $sql = "SELECT n.*, u.username as author_name 
                FROM news n 
                LEFT JOIN users u ON n.author_id = u.id 
                WHERE n.is_published = 1 AND n.is_breaking = 1 
                AND (n.published_at IS NULL OR n.published_at <= NOW())
                ORDER BY n.published_at DESC 
                LIMIT ?";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$limit]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    /**
     * Get news count by category for sidebar
     */
    public function getCategoryCounts() {
        $sql = "SELECT category, COUNT(*) as count 
                FROM news 
                WHERE is_published = 1 AND category IS NOT NULL AND category != ''
                GROUP BY category 
                ORDER BY count DESC, category";
        
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    /**
     * Get archive months (for archive widget)
     */
    public function getArchiveMonths() {
        $sql = "SELECT 
                    DATE_FORMAT(published_at, '%Y-%m') as month,
                    DATE_FORMAT(published_at, '%M %Y') as month_name,
                    COUNT(*) as count
                FROM news 
                WHERE is_published = 1 AND published_at IS NOT NULL
                GROUP BY DATE_FORMAT(published_at, '%Y-%m')
                ORDER BY published_at DESC";
        
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    /**
     * Get popular news by views
     */
    public function getPopularNews($limit = 5) {
        $sql = "SELECT n.*, u.username as author_name 
                FROM news n 
                LEFT JOIN users u ON n.author_id = u.id 
                WHERE n.is_published = 1 
                AND (n.published_at IS NULL OR n.published_at <= NOW())
                ORDER BY n.views_count DESC, n.published_at DESC 
                LIMIT ?";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$limit]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    /**
     * Generate URL slug from title
     */
    private function generateSlug($title) {
        // Convert to lowercase
        $slug = strtolower($title);
        
        // Replace spaces with hyphens
        $slug = preg_replace('/\s+/', '-', $slug);
        
        // Remove special characters
        $slug = preg_replace('/[^a-z0-9\-]/', '', $slug);
        
        // Remove multiple hyphens
        $slug = preg_replace('/-+/', '-', $slug);
        
        // Trim hyphens from start and end
        $slug = trim($slug, '-');
        
        // Add timestamp to ensure uniqueness
        $slug .= '-' . time();
        
        return $slug;
    }
    
    /**
     * Export news to CSV
     */
    public function exportToCSV($filters = []) {
        $news = $this->getAll($filters, 10000, 0); // Get all with filters
        
        if (empty($news)) {
            return false;
        }
        
        // Create CSV content
        $output = fopen('php://temp', 'w');
        
        // Add BOM for UTF-8
        fwrite($output, "\xEF\xBB\xBF");
        
        // Add headers
        $headers = ['ID', 'Title', 'Slug', 'Category', 'Status', 'Author', 'Published Date', 'Created Date', 'Views', 'Featured', 'Breaking'];
        fputcsv($output, $headers);
        
        // Add data
        foreach ($news as $item) {
            $row = [
                $item['id'],
                $item['title'],
                $item['slug'],
                $item['category'] ?? 'N/A',
                $item['is_published'] ? 'Published' : 'Draft',
                $item['author_name'] ?? 'Unknown',
                $item['published_at'] ?? 'Not published',
                $item['created_at'],
                $item['views_count'],
                $item['is_featured'] ? 'Yes' : 'No',
                $item['is_breaking'] ? 'Yes' : 'No'
            ];
            fputcsv($output, $row);
        }
        
        rewind($output);
        $csv = stream_get_contents($output);
        fclose($output);
        
        return $csv;
    }
}