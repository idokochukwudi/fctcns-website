<?php
/**
 * News Model - COMPLETE FIXED VERSION WITH WORD-BY-WORD SEARCH
 * ✅ FIX 1: Search now splits query into individual words for better matching
 * ✅ FIX 2: All author references use full_name instead of username
 * ✅ FIX 3: Events now insert into news table with type='event'
 * ✅ FIX 4: Image handling uses getProjectRootPath() for correct directory
 * ✅ FIX 5: countPublishedNews() method added
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
    
    /**
     * Get database connection (public getter)
     */
    public function getDb() {
        return $this->db;
    }
    
    // === IMAGE HANDLING METHODS ===
    
    /**
     * Save uploaded image from base64 data - FIXED VERSION
     * ✅ APPLIED FIX: Use getProjectRootPath() for correct directory
     */
    public function saveImageFromBase64($base64Data, $filename) {
        if (empty($base64Data) || empty($filename)) {
            error_log("No base64 data or filename provided for image");
            return '';
        }
        
        try {
            error_log("Starting image save from base64. Filename: $filename");
            
            // Remove data:image/xxx;base64, prefix if present
            $base64Data = preg_replace('/^data:image\/\w+;base64,/', '', $base64Data);
            
            // Decode base64
            $imageData = base64_decode($base64Data);
            
            if (!$imageData) {
                error_log("Failed to decode base64 image");
                return '';
            }
            
            // ✅ APPLIED FIX: Use getProjectRootPath() for correct directory
            $uploadDir = getProjectRootPath() . '/public/uploads/news/';
            error_log("Upload directory: $uploadDir");
            
            if (!file_exists($uploadDir)) {
                mkdir($uploadDir, 0777, true);
                error_log("Created upload directory: $uploadDir");
            }
            
            // Generate unique filename
            $extension = pathinfo($filename, PATHINFO_EXTENSION);
            if (empty($extension)) {
                $extension = 'jpg'; // Default extension
            }
            $uniqueName = uniqid() . '_' . time() . '.' . $extension;
            $filePath = $uploadDir . $uniqueName;
            
            error_log("Saving image to: $filePath");
            
            // Save file
            if (file_put_contents($filePath, $imageData)) {
                $imageUrl = '/uploads/news/' . $uniqueName;
                error_log("✅ Image saved successfully to correct location: $imageUrl");
                return $imageUrl;
            } else {
                error_log("Failed to save image to: $filePath");
                return '';
            }
            
        } catch (Exception $e) {
            error_log("Image upload error: " . $e->getMessage());
            return '';
        }
    }
    
    /**
     * Process and save uploaded file - FIXED VERSION
     * ✅ APPLIED FIX: Use getProjectRootPath() for correct directory
     */
    public function saveUploadedFile($file, $type = 'news') {
        try {
            if (empty($file['name'])) {
                error_log("No file uploaded");
                return '';
            }
            
            error_log("Processing uploaded file: " . $file['name']);
            
            // Check for errors
            if ($file['error'] !== UPLOAD_ERR_OK) {
                error_log("File upload error: " . $file['error']);
                return '';
            }
            
            // Check file size (max 5MB)
            $maxSize = 5 * 1024 * 1024; // 5MB in bytes
            if ($file['size'] > $maxSize) {
                error_log("File too large: " . $file['size'] . " bytes");
                return '';
            }
            
            // Check file type
            $allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp'];
            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            $mimeType = finfo_file($finfo, $file['tmp_name']);
            finfo_close($finfo);
            
            if (!in_array($mimeType, $allowedTypes)) {
                error_log("Invalid file type: $mimeType");
                return '';
            }
            
            // ✅ APPLIED FIX: Use getProjectRootPath() for correct directory
            $uploadDir = getProjectRootPath() . "/public/uploads/{$type}/";
            error_log("Upload directory: $uploadDir");
            
            if (!file_exists($uploadDir)) {
                mkdir($uploadDir, 0777, true);
                error_log("Created upload directory: $uploadDir");
            }
            
            // Generate unique filename
            $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
            if (empty($extension)) {
                // Determine extension from mime type
                $extensionMap = [
                    'image/jpeg' => 'jpg',
                    'image/jpg' => 'jpg',
                    'image/png' => 'png',
                    'image/gif' => 'gif',
                    'image/webp' => 'webp'
                ];
                $extension = $extensionMap[$mimeType] ?? 'jpg';
            }
            
            $uniqueName = uniqid() . '_' . time() . '.' . $extension;
            $filePath = $uploadDir . $uniqueName;
            
            // Move uploaded file
            if (move_uploaded_file($file['tmp_name'], $filePath)) {
                $imageUrl = "/uploads/{$type}/" . $uniqueName;
                error_log("✅ File uploaded successfully to correct location: $imageUrl");
                return $imageUrl;
            } else {
                error_log("Failed to move uploaded file to: $filePath");
                return '';
            }
            
        } catch (Exception $e) {
            error_log("saveUploadedFile error: " . $e->getMessage());
            return '';
        }
    }
    
    // === CRUD METHODS ===
    
    /**
     * Create content in news table (handles both news and events) - UPDATED FIXED VERSION
     * This is now the ONLY method that inserts into the database
     */
    public function createNews($data) {
        try {
            error_log("=== NewsModel::createNews() called ===");
            error_log("Type: " . ($data['type'] ?? 'news'));
            error_log("Data received: " . json_encode($data, JSON_PRETTY_PRINT));
            
            // Handle image upload if present as base64
            if (!empty($data['featured_image_data']) && !empty($data['featured_image_filename'])) {
                $imagePath = $this->saveImageFromBase64(
                    $data['featured_image_data'],
                    $data['featured_image_filename']
                );
                $data['featured_image'] = $imagePath;
                error_log("Base64 Image saved: " . ($imagePath ?: 'Failed'));
            }
            
            // Handle image upload from file input
            if (!empty($_FILES['featured_image_upload']['name']) && empty($data['featured_image'])) {
                $imagePath = $this->saveUploadedFile($_FILES['featured_image_upload']);
                $data['featured_image'] = $imagePath;
                error_log("Uploaded Image saved: " . ($imagePath ?: 'Failed'));
            }
            
            // Build SQL with ALL fields including type
            $sql = "INSERT INTO news (
                title, slug, excerpt, content, author_id, category, type,
                tags, featured_image, is_published, is_featured, is_breaking,
                meta_title, meta_description, meta_keywords,
                event_date, event_end_date, event_time, event_location,
                created_at, updated_at
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), NOW())";
            
            error_log("SQL for NEWS table: " . $sql);
            
            $stmt = $this->db->prepare($sql);
            
            // Prepare values array with correct order and count
            $values = [
                $data['title'] ?? '',
                $data['slug'] ?? '',
                $data['excerpt'] ?? '',
                $data['content'] ?? '',
                $data['author_id'] ?? 1,
                $data['category'] ?? '',
                $data['type'] ?? 'news',  // CRITICAL: Type field is included!
                $data['tags'] ?? '',
                $data['featured_image'] ?? '',
                $data['is_published'] ?? 1,
                $data['is_featured'] ?? 0,
                $data['is_breaking'] ?? 0,
                $data['meta_title'] ?? '',
                $data['meta_description'] ?? '',
                $data['meta_keywords'] ?? '',
                $data['event_date'] ?? null,
                $data['event_end_date'] ?? null,
                $data['event_time'] ?? null,
                $data['event_location'] ?? ''
            ];
            
            error_log("Values to insert into NEWS table: " . json_encode($values));
            
            $success = $stmt->execute($values);
            
            if ($success) {
                $id = $this->db->lastInsertId();
                error_log("✓ SUCCESS: Created content in news table with ID: $id");
                error_log("  Type: " . ($data['type'] ?? 'news'));
                error_log("  Title: " . ($data['title'] ?? ''));
                return $id;
            } else {
                $errorInfo = $stmt->errorInfo();
                error_log("✗ FAILED to create content in NEWS table:");
                error_log("  - SQL State: " . $errorInfo[0]);
                error_log("  - Error Code: " . $errorInfo[1]);
                error_log("  - Error Message: " . $errorInfo[2]);
                error_log("  - SQL: " . $sql);
                error_log("  - Values: " . json_encode($values));
                return false;
            }
            
        } catch (Exception $e) {
            error_log("NewsModel::createNews error: " . $e->getMessage());
            error_log("Error trace: " . $e->getTraceAsString());
            return false;
        }
    }
    
    /**
     * Create event in NEWS table (with type='event') - UPDATED FIXED VERSION
     * Now inserts into the news table instead of events table
     */
    public function createEvent($data) {
        try {
            error_log("=== NewsModel::createEvent() called ===");
            error_log("Data: " . print_r($data, true));
            
            // Handle image upload if present as base64
            if (!empty($data['featured_image_data']) && !empty($data['featured_image_filename'])) {
                $imagePath = $this->saveImageFromBase64(
                    $data['featured_image_data'],
                    $data['featured_image_filename']
                );
                $data['featured_image'] = $imagePath;
                error_log("Base64 Image saved: " . ($imagePath ?: 'Failed'));
            }
            
            // Handle image upload from file input
            if (!empty($_FILES['featured_image_upload']['name']) && empty($data['featured_image'])) {
                $imagePath = $this->saveUploadedFile($_FILES['featured_image_upload']);
                $data['featured_image'] = $imagePath;
                error_log("Uploaded Image saved: " . ($imagePath ?: 'Failed'));
            }
            
            // Build SQL for NEWS table with type='event'
            $sql = "INSERT INTO news (
                title, slug, excerpt, content, author_id, category, type,
                tags, featured_image, is_published, is_featured, is_breaking,
                meta_title, meta_description, meta_keywords,
                event_date, event_end_date, event_time, event_location,
                created_at, updated_at
            ) VALUES (?, ?, ?, ?, ?, ?, 'event', ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), NOW())";
            
            error_log("Event SQL for NEWS table: " . $sql);
            
            $stmt = $this->db->prepare($sql);
            
            $values = [
                $data['title'],
                $data['slug'],
                $data['excerpt'] ?? '',
                $data['content'],
                $data['author_id'] ?? 1,
                $data['category'] ?? '',
                $data['tags'] ?? '',
                $data['featured_image'] ?? '',
                $data['is_published'] ?? 1,
                $data['is_featured'] ?? 0,
                $data['is_breaking'] ?? 0,
                $data['meta_title'] ?? '',
                $data['meta_description'] ?? '',
                $data['meta_keywords'] ?? '',
                $data['event_date'],
                $data['event_end_date'] ?? null,
                $data['event_time'] ?? null,
                $data['event_location'] ?? ''
            ];
            
            error_log("Event values for NEWS table: " . json_encode($values));
            
            $success = $stmt->execute($values);
            
            if ($success) {
                $id = $this->db->lastInsertId();
                error_log("Successfully created event in NEWS table with ID: $id");
                return $id;
            } else {
                $errorInfo = $stmt->errorInfo();
                error_log("Failed to create event. SQL Error: " . $errorInfo[2]);
                return false;
            }
            
        } catch (Exception $e) {
            error_log("NewsModel::createEvent error: " . $e->getMessage());
            error_log("Error trace: " . $e->getTraceAsString());
            return false;
        }
    }
    
    public function create($data) {
        error_log("==========================================");
        error_log("=== NewsModel::create() START ===");
        error_log("Data type: " . ($data['type'] ?? 'unknown'));
        error_log("Data received: " . json_encode($data, JSON_PRETTY_PRINT));
        
        try {
            $type = $data['type'] ?? 'news';
            
            if ($type === 'event') {
                error_log("Calling createEvent()");
                return $this->createEvent($data);
            } else {
                error_log("Calling createNews()");
                return $this->createNews($data);
            }
            
        } catch (Exception $e) {
            error_log("✗✗✗ NewsModel::create() ERROR: " . $e->getMessage());
            error_log("Error trace: " . $e->getTraceAsString());
            return false;
        }
    }
    
    // === FILTER METHODS FOR ADMIN ===
    
    /**
     * Get all content from news table with filters (for admin)
     * ✅ FIXED: Changed username to full_name for author display
     */
    public function getAllWithFilters($filters = [], $limit = 20, $offset = 0) {
        error_log("=== NewsModel getAllWithFilters CALLED ===");
        error_log("Filters: " . json_encode($filters));
        error_log("Limit: $limit, Offset: $offset");
        
        try {
            // Start with base WHERE clause
            $where = ['1=1'];
            $params = [];
            
            // Build WHERE clause based on filters
            if (!empty($filters['status'])) {
                if ($filters['status'] === 'published') {
                    $where[] = 'n.is_published = 1';
                } elseif ($filters['status'] === 'draft') {
                    $where[] = 'n.is_published = 0';
                }
            }
            
            if (!empty($filters['type'])) {
                $where[] = 'n.type = ?';
                $params[] = $filters['type'];
            }
            
            if (!empty($filters['category'])) {
                $where[] = 'n.category = ?';
                $params[] = $filters['category'];
            }
            
            if (!empty($filters['search'])) {
                $where[] = '(n.title LIKE ? OR n.excerpt LIKE ? OR n.content LIKE ?)';
                $search = '%' . $filters['search'] . '%';
                $params[] = $search;
                $params[] = $search;
                $params[] = $search;
            }
            
            if (!empty($filters['date_from'])) {
                $where[] = 'DATE(n.created_at) >= ?';
                $params[] = $filters['date_from'];
            }
            
            if (!empty($filters['date_to'])) {
                $where[] = 'DATE(n.created_at) <= ?';
                $params[] = $filters['date_to'];
            }
            
            $whereClause = implode(' AND ', $where);
            error_log("WHERE clause: " . $whereClause);
            
            // ✅ FIXED: Changed from username to full_name
            $sql = "SELECT 
                        n.id,
                        n.title,
                        n.slug,
                        n.excerpt,
                        n.category,
                        n.type,
                        n.featured_image,
                        n.is_published,
                        n.is_featured,
                        n.is_breaking,
                        COALESCE(n.views_count, 0) as views_count,
                        n.created_at,
                        n.event_date,
                        n.event_time,
                        n.event_location,
                        COALESCE(u.full_name, 'System') as author_name
                    FROM news n 
                    LEFT JOIN users u ON n.author_id = u.id 
                    WHERE {$whereClause} 
                    ORDER BY n.created_at DESC 
                    LIMIT ? OFFSET ?";
            
            $params[] = $limit;
            $params[] = $offset;
            
            error_log("SQL: " . $sql);
            error_log("Params count: " . count($params));
            
            $stmt = $this->db->prepare($sql);
            
            // Bind parameters with correct types
            foreach ($params as $index => $param) {
                $paramType = is_int($param) ? PDO::PARAM_INT : PDO::PARAM_STR;
                $stmt->bindValue($index + 1, $param, $paramType);
            }
            
            $stmt->execute();
            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            error_log("Model returned " . count($results) . " results");
            
            return $results;
            
        } catch (Exception $e) {
            error_log("NewsModel getAllWithFilters error: " . $e->getMessage());
            error_log("Error trace: " . $e->getTraceAsString());
            return [];
        }
    }
    
    /**
     * Count all content with filters (for admin)
     */
    public function countAllWithFilters($filters = []) {
        error_log("=== NewsModel countAllWithFilters CALLED ===");
        error_log("Filters: " . json_encode($filters));
        
        try {
            $where = ['1=1'];
            $params = [];
            
            if (!empty($filters['status'])) {
                if ($filters['status'] === 'published') {
                    $where[] = 'is_published = 1';
                } elseif ($filters['status'] === 'draft') {
                    $where[] = 'is_published = 0';
                }
            }
            
            if (!empty($filters['type'])) {
                $where[] = 'type = ?';
                $params[] = $filters['type'];
            }
            
            if (!empty($filters['category'])) {
                $where[] = 'category = ?';
                $params[] = $filters['category'];
            }
            
            if (!empty($filters['search'])) {
                $where[] = '(title LIKE ? OR excerpt LIKE ? OR content LIKE ?)';
                $search = '%' . $filters['search'] . '%';
                $params[] = $search;
                $params[] = $search;
                $params[] = $search;
            }
            
            if (!empty($filters['date_from'])) {
                $where[] = 'DATE(created_at) >= ?';
                $params[] = $filters['date_from'];
            }
            
            if (!empty($filters['date_to'])) {
                $where[] = 'DATE(created_at) <= ?';
                $params[] = $filters['date_to'];
            }
            
            $whereClause = implode(' AND ', $where);
            
            $sql = "SELECT COUNT(*) as total FROM news WHERE {$whereClause}";
            error_log("Count SQL: " . $sql);
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute($params);
            $result = $stmt->fetch();
            
            $total = $result['total'] ?? 0;
            error_log("Count result: " . $total);
            
            return $total;
            
        } catch (Exception $e) {
            error_log("NewsModel countAllWithFilters error: " . $e->getMessage());
            return 0;
        }
    }
    
    // === PUBLIC DISPLAY METHODS ===
    
    /**
     * Get published news only (for public display) - FIXED VERSION
     * ✅ FIXED: Added JOIN to users table to get author full_name
     */
    public function getPublishedNews($limit = 10, $offset = 0, $category = '') {
        try {
            error_log("=== NewsModel getPublishedNews CALLED ===");
            error_log("Limit: $limit, Offset: $offset, Category: '$category'");
            
            if ($category) {
                $sql = "SELECT 
                        n.id,
                        n.title,
                        n.slug,
                        n.excerpt,
                        n.content,
                        n.category,
                        n.type,
                        n.featured_image,
                        n.is_published,
                        n.is_featured,
                        n.is_breaking,
                        COALESCE(n.views_count, 0) as views_count,
                        n.author_id,
                        n.created_at,
                        u.full_name as author_name,
                        u.role as author_role,
                        'news' as content_type
                    FROM news n
                    LEFT JOIN users u ON n.author_id = u.id
                    WHERE n.is_published = 1 AND n.category = ? 
                    ORDER BY n.created_at DESC 
                    LIMIT ? OFFSET ?";
                
                error_log("SQL with category: " . $sql);
                
                $stmt = $this->db->prepare($sql);
                $stmt->bindParam(1, $category, PDO::PARAM_STR);
                $stmt->bindParam(2, $limit, PDO::PARAM_INT);
                $stmt->bindParam(3, $offset, PDO::PARAM_INT);
                $stmt->execute();
            } else {
                $sql = "SELECT 
                        n.id,
                        n.title,
                        n.slug,
                        n.excerpt,
                        n.content,
                        n.category,
                        n.type,
                        n.featured_image,
                        n.is_published,
                        n.is_featured,
                        n.is_breaking,
                        COALESCE(n.views_count, 0) as views_count,
                        n.author_id,
                        n.created_at,
                        u.full_name as author_name,
                        u.role as author_role,
                        'news' as content_type
                    FROM news n
                    LEFT JOIN users u ON n.author_id = u.id
                    WHERE n.is_published = 1 
                    ORDER BY n.created_at DESC 
                    LIMIT ? OFFSET ?";
                
                error_log("SQL without category: " . $sql);
                
                $stmt = $this->db->prepare($sql);
                $stmt->bindParam(1, $limit, PDO::PARAM_INT);
                $stmt->bindParam(2, $offset, PDO::PARAM_INT);
                $stmt->execute();
            }
            
            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
            error_log("getPublishedNews returned: " . count($results) . " articles");
            
            if (count($results) > 0) {
                error_log("First article: " . json_encode($results[0]));
            }
            
            return $results;
            
        } catch (Exception $e) {
            error_log("NewsModel getPublishedNews error: " . $e->getMessage());
            error_log("Error trace: " . $e->getTraceAsString());
            return [];
        }
    }
    
    /**
     * Count published news with filters - FIXED (ADDED THIS METHOD)
     */
    public function countPublishedNews($category = '') {
        try {
            error_log("=== countPublishedNews CALLED ===");
            
            if ($category) {
                $sql = "SELECT COUNT(*) as total FROM news WHERE is_published = 1 AND category = ?";
                error_log("Count SQL with category: " . $sql);
                
                $stmt = $this->db->prepare($sql);
                $stmt->execute([$category]);
            } else {
                $sql = "SELECT COUNT(*) as total FROM news WHERE is_published = 1";
                error_log("Count SQL: " . $sql);
                
                $stmt = $this->db->prepare($sql);
                $stmt->execute();
            }
            
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            $total = $result['total'] ?? 0;
            error_log("countPublishedNews result: " . $total);
            
            return $total;
            
        } catch (Exception $e) {
            error_log("NewsModel countPublishedNews error: " . $e->getMessage());
            return 0;
        }
    }
    
    /**
     * Get featured news - FIXED
     * ✅ FIXED: Added JOIN to get author full_name
     */
    public function getFeaturedNews($limit = 3) {
        try {
            error_log("=== getFeaturedNews CALLED ===");
            
            $sql = "SELECT 
                    n.id,
                    n.title,
                    n.slug,
                    n.excerpt,
                    n.content,
                    n.category,
                    n.featured_image,
                    n.is_featured,
                    COALESCE(n.views_count, 0) as views_count,
                    n.created_at,
                    u.full_name as author_name,
                    u.role as author_role
                FROM news n
                LEFT JOIN users u ON n.author_id = u.id
                WHERE n.is_published = 1 AND n.is_featured = 1 
                ORDER BY n.created_at DESC 
                LIMIT ?";
            
            error_log("Featured SQL: " . $sql);
            
            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(1, (int)$limit, PDO::PARAM_INT);
            $stmt->execute();
            
            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
            error_log("getFeaturedNews returned: " . count($results) . " articles");
            
            return $results;
            
        } catch (Exception $e) {
            error_log("NewsModel getFeaturedNews error: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Get popular news - FIXED
     * ✅ FIXED: Added JOIN to get author full_name (changed from username)
     */
    public function getPopularNews($limit = 5) {
        try {
            error_log("=== getPopularNews CALLED ===");
            
            $sql = "SELECT 
                    n.id,
                    n.title,
                    n.slug,
                    n.excerpt,
                    n.category,
                    n.featured_image,
                    n.content,
                    COALESCE(n.views_count, 0) as views_count,
                    n.created_at,
                    u.full_name as author_name
                FROM news n 
                LEFT JOIN users u ON n.author_id = u.id
                WHERE n.is_published = 1 
                ORDER BY n.views_count DESC, n.created_at DESC 
                LIMIT ?";
            
            error_log("Popular SQL: " . $sql);
            
            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(1, (int)$limit, PDO::PARAM_INT);
            $stmt->execute();
            
            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
            error_log("getPopularNews returned: " . count($results) . " articles");
            
            return $results;
            
        } catch (Exception $e) {
            error_log("NewsModel getPopularNews error: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Get news categories with counts - FIXED
     */
    public function getCategoriesWithCounts() {
        try {
            error_log("=== getCategoriesWithCounts CALLED ===");
            
            $sql = "SELECT category, COUNT(*) as count 
                    FROM news 
                    WHERE is_published = 1 AND category IS NOT NULL AND category != ''
                    GROUP BY category 
                    ORDER BY category";
            
            error_log("Categories SQL: " . $sql);
            
            $stmt = $this->db->query($sql);
            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            $categories = [];
            foreach ($results as $result) {
                $categories[$result['category']] = $result['count'];
            }
            
            error_log("Found categories: " . json_encode($categories));
            
            return $categories;
            
        } catch (Exception $e) {
            error_log("NewsModel getCategoriesWithCounts error: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Get archive months - FIXED
     */
    public function getArchiveMonths() {
        try {
            error_log("=== getArchiveMonths CALLED ===");
            
            $sql = "SELECT 
                        DATE_FORMAT(created_at, '%Y-%m') as month,
                        DATE_FORMAT(created_at, '%M %Y') as month_name,
                        COUNT(*) as count
                    FROM news 
                    WHERE is_published = 1
                    GROUP BY DATE_FORMAT(created_at, '%Y-%m')
                    ORDER BY month DESC
                    LIMIT 12";
            
            error_log("Archive SQL: " . $sql);
            
            $stmt = $this->db->query($sql);
            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            error_log("Archive months found: " . count($results));
            
            return $results;
            
        } catch (Exception $e) {
            error_log("NewsModel getArchiveMonths error: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Get news by slug
     * ✅ FIXED: Get full_name instead of username, added role and fallback
     */
    public function getBySlug($slug) {
        try {
            // ✅ FIXED: Get full_name instead of username
            $sql = "SELECT n.*, 
                       u.full_name as author_name,
                       u.role as author_role
                FROM news n 
                LEFT JOIN users u ON n.author_id = u.id 
                WHERE n.slug = ? AND n.is_published = 1";
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$slug]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            
            // Fallback if no author found
            if ($result && empty($result['author_name'])) {
                $result['author_name'] = 'FCT Nursing College';
                $result['author_role'] = 'Administration';
            }
            
            return $result;
            
        } catch (Exception $e) {
            error_log("NewsModel getBySlug error: " . $e->getMessage());
            return null;
        }
    }
    
    /**
     * Get news by category (for public category pages)
     * ✅ FIXED: Added JOIN to get author full_name
     */
    public function getNewsByCategory($category, $limit = 10, $offset = 0) {
        try {
            error_log("=== NewsModel::getNewsByCategory() called ===");
            error_log("Category: $category, Limit: $limit, Offset: $offset");
            
            $sql = "SELECT 
                    n.id,
                    n.title,
                    n.slug,
                    n.excerpt,
                    n.content,
                    n.category,
                    n.type,
                    n.featured_image,
                    n.is_published,
                    n.is_featured,
                    n.is_breaking,
                    COALESCE(n.views_count, 0) as views_count,
                    n.author_id,
                    n.created_at,
                    u.full_name as author_name,
                    u.role as author_role
                FROM news n
                LEFT JOIN users u ON n.author_id = u.id
                WHERE n.is_published = 1 AND n.category = ? 
                ORDER BY n.created_at DESC 
                LIMIT ? OFFSET ?";
            
            error_log("SQL: " . $sql);
            
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(1, $category, PDO::PARAM_STR);
            $stmt->bindParam(2, $limit, PDO::PARAM_INT);
            $stmt->bindParam(3, $offset, PDO::PARAM_INT);
            $stmt->execute();
            
            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
            error_log("getNewsByCategory returned: " . count($results) . " articles");
            
            return $results;
            
        } catch (Exception $e) {
            error_log("NewsModel getNewsByCategory error: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Count news by category (for pagination)
     */
    public function countNewsByCategory($category) {
        try {
            error_log("=== NewsModel::countNewsByCategory() called ===");
            error_log("Category: $category");
            
            $sql = "SELECT COUNT(*) as total FROM news WHERE is_published = 1 AND category = ?";
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$category]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            
            $total = $result['total'] ?? 0;
            error_log("countNewsByCategory result: " . $total);
            
            return $total;
            
        } catch (Exception $e) {
            error_log("NewsModel countNewsByCategory error: " . $e->getMessage());
            return 0;
        }
    }
    
    /**
     * Get news by month (for archive pages)
     * ✅ FIXED: Added JOIN to get author full_name
     */
    public function getNewsByMonth($year, $month, $limit = 10, $offset = 0) {
        try {
            error_log("=== NewsModel::getNewsByMonth() called ===");
            error_log("Year: $year, Month: $month");
            
            $sql = "SELECT 
                    n.id,
                    n.title,
                    n.slug,
                    n.excerpt,
                    n.content,
                    n.category,
                    n.type,
                    n.featured_image,
                    n.is_published,
                    n.is_featured,
                    n.is_breaking,
                    COALESCE(n.views_count, 0) as views_count,
                    n.author_id,
                    n.created_at,
                    u.full_name as author_name,
                    u.role as author_role
                FROM news n
                LEFT JOIN users u ON n.author_id = u.id
                WHERE n.is_published = 1 
                AND YEAR(n.created_at) = ? 
                AND MONTH(n.created_at) = ?
                ORDER BY n.created_at DESC 
                LIMIT ? OFFSET ?";
            
            error_log("SQL: " . $sql);
            
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(1, $year, PDO::PARAM_INT);
            $stmt->bindParam(2, $month, PDO::PARAM_INT);
            $stmt->bindParam(3, $limit, PDO::PARAM_INT);
            $stmt->bindParam(4, $offset, PDO::PARAM_INT);
            $stmt->execute();
            
            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
            error_log("getNewsByMonth returned: " . count($results) . " articles");
            
            return $results;
            
        } catch (Exception $e) {
            error_log("NewsModel getNewsByMonth error: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Count news by month (for archive pagination)
     */
    public function countNewsByMonth($year, $month) {
        try {
            error_log("=== NewsModel::countNewsByMonth() called ===");
            error_log("Year: $year, Month: $month");
            
            $sql = "SELECT COUNT(*) as total FROM news 
                    WHERE is_published = 1 
                    AND YEAR(created_at) = ? 
                    AND MONTH(created_at) = ?";
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$year, $month]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            
            $total = $result['total'] ?? 0;
            error_log("countNewsByMonth result: " . $total);
            
            return $total;
            
        } catch (Exception $e) {
            error_log("NewsModel countNewsByMonth error: " . $e->getMessage());
            return 0;
        }
    }
    
    /**
     * Get related news by category (excluding current article)
     * ✅ FIXED: Added JOIN to get author full_name
     */
    public function getRelatedNews($id, $category, $limit = 3) {
        try {
            error_log("=== NewsModel::getRelatedNews() called ===");
            error_log("ID: $id, Category: $category, Limit: $limit");
            
            if (empty($category)) {
                return [];
            }
            
            $sql = "SELECT 
                    n.id,
                    n.title,
                    n.slug,
                    n.excerpt,
                    n.category,
                    n.featured_image,
                    COALESCE(n.views_count, 0) as views_count,
                    n.created_at,
                    u.full_name as author_name
                FROM news n
                LEFT JOIN users u ON n.author_id = u.id
                WHERE n.is_published = 1 
                AND n.category = ? 
                AND n.id != ?
                ORDER BY n.created_at DESC 
                LIMIT ?";
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$category, $id, $limit]);
            
            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
            error_log("getRelatedNews returned: " . count($results) . " articles");
            
            return $results;
            
        } catch (Exception $e) {
            error_log("NewsModel getRelatedNews error: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Get upcoming events
     * ✅ FIXED: Ensuring this method exists with proper JOIN
     */
    public function getUpcomingEvents($limit = 5) {
        try {
            error_log("=== NewsModel::getUpcomingEvents() called ===");
            
            $sql = "SELECT 
                    n.id,
                    n.title,
                    n.slug,
                    n.excerpt,
                    n.content,
                    n.category,
                    n.type,
                    n.featured_image,
                    n.is_published,
                    n.is_featured,
                    n.is_breaking,
                    COALESCE(n.views_count, 0) as views_count,
                    n.event_date,
                    n.event_end_date,
                    n.event_time,
                    n.event_location,
                    n.author_id,
                    n.created_at,
                    u.full_name as author_name
                FROM news n
                LEFT JOIN users u ON n.author_id = u.id
                WHERE n.type = 'event' 
                AND n.is_published = 1 
                AND (n.event_date >= CURDATE() OR n.event_date IS NULL)
                ORDER BY n.event_date ASC 
                LIMIT ?";
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$limit]);
            
            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
            error_log("getUpcomingEvents returned: " . count($results) . " events");
            
            return $results;
            
        } catch (Exception $e) {
            error_log("NewsModel getUpcomingEvents error: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Get news by archive month (legacy method)
     * ✅ FIXED: Added JOIN to get author full_name
     */
    public function getByArchiveMonth($year, $month, $limit = 10, $offset = 0) {
        try {
            $sql = "SELECT n.*, u.full_name as author_name 
                    FROM news n 
                    LEFT JOIN users u ON n.author_id = u.id 
                    WHERE n.is_published = 1 
                    AND YEAR(n.created_at) = ? 
                    AND MONTH(n.created_at) = ?
                    ORDER BY n.created_at DESC 
                    LIMIT ? OFFSET ?";
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$year, $month, $limit, $offset]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
            
        } catch (Exception $e) {
            error_log("NewsModel getByArchiveMonth error: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Count news by archive month (legacy method)
     */
    public function countByArchiveMonth($year, $month) {
        try {
            $sql = "SELECT COUNT(*) as total 
                    FROM news 
                    WHERE is_published = 1 
                    AND YEAR(created_at) = ? 
                    AND MONTH(created_at) = ?";
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$year, $month]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result['total'] ?? 0;
            
        } catch (Exception $e) {
            error_log("NewsModel countByArchiveMonth error: " . $e->getMessage());
            return 0;
        }
    }
    
    // === SEARCH METHODS - FIXED WORD-BY-WORD VERSION ===
    
    /**
     * Search news articles - FIXED WORD-BY-WORD VERSION
     * ✅ Splits query into individual words for better matching
     */
    public function searchNews($query, $limit = 10, $offset = 0) {
        error_log("=== searchNews() START ===");
        error_log("Search query: '" . $query . "'");
        
        try {
            // Split the query into individual words for better matching
            $words = explode(' ', $query);
            $conditions = [];
            $params = [];
            
            // Build conditions for each word (minimum 3 characters)
            foreach ($words as $word) {
                $word = trim($word);
                if (strlen($word) >= 3) {
                    $conditions[] = "n.title LIKE ?";
                    $params[] = '%' . $word . '%';
                }
            }
            
            // If no valid words, use the full query
            if (empty($conditions)) {
                $conditions[] = "n.title LIKE ?";
                $params[] = '%' . $query . '%';
            }
            
            $whereClause = implode(' AND ', $conditions);
            
            $sql = "SELECT 
                        n.*,
                        u.full_name as author_name,
                        u.role as author_role
                    FROM news n 
                    LEFT JOIN users u ON n.author_id = u.id 
                    WHERE n.is_published = 1 
                    AND ($whereClause)
                    ORDER BY n.created_at DESC 
                    LIMIT ? OFFSET ?";
            
            // Add limit and offset to params
            $params[] = $limit;
            $params[] = $offset;
            
            error_log("SQL: " . $sql);
            error_log("Params: " . json_encode($params));
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute($params);
            
            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            error_log("Found " . count($results) . " results");
            
            return $results;
            
        } catch (Exception $e) {
            error_log("searchNews error: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Count search results - FIXED WORD-BY-WORD VERSION
     */
    public function countSearchResults($query) {
        error_log("=== countSearchResults() START ===");
        error_log("Search query: '" . $query . "'");
        
        try {
            // Split the query into individual words
            $words = explode(' ', $query);
            $conditions = [];
            $params = [];
            
            // Build conditions for each word
            foreach ($words as $word) {
                $word = trim($word);
                if (strlen($word) >= 3) {
                    $conditions[] = "title LIKE ?";
                    $params[] = '%' . $word . '%';
                }
            }
            
            // If no valid words, use the full query
            if (empty($conditions)) {
                $conditions[] = "title LIKE ?";
                $params[] = '%' . $query . '%';
            }
            
            $whereClause = implode(' AND ', $conditions);
            
            $sql = "SELECT COUNT(*) as total 
                    FROM news 
                    WHERE is_published = 1 
                    AND ($whereClause)";
            
            error_log("Count SQL: " . $sql);
            error_log("Count params: " . json_encode($params));
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute($params);
            
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            $total = $result['total'] ?? 0;
            
            error_log("Total results: " . $total);
            
            return $total;
            
        } catch (Exception $e) {
            error_log("countSearchResults error: " . $e->getMessage());
            return 0;
        }
    }
    
    // === UTILITY METHODS ===
    
    /**
     * Increment views
     */
    public function incrementViews($id) {
        try {
            $sql = "UPDATE news SET views_count = COALESCE(views_count, 0) + 1 WHERE id = ?";
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([$id]);
        } catch (Exception $e) {
            error_log("NewsModel incrementViews error: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Get all categories
     */
    public function getAllCategories() {
        try {
            $sql = "SELECT DISTINCT category FROM news WHERE category IS NOT NULL AND category != '' ORDER BY category";
            $stmt = $this->db->query($sql);
            return $stmt->fetchAll(PDO::FETCH_COLUMN);
            
        } catch (Exception $e) {
            error_log("NewsModel getAllCategories error: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Count ALL content (news + events) with filters - for admin panel
     */
    public function countAll($filters = []) {
        try {
            $where = ['1=1'];
            $params = [];
            
            if (!empty($filters['status'])) {
                if ($filters['status'] === 'published') {
                    $where[] = 'is_published = 1';
                } elseif ($filters['status'] === 'draft') {
                    $where[] = 'is_published = 0';
                }
            }
            
            if (!empty($filters['category'])) {
                $where[] = 'category = ?';
                $params[] = $filters['category'];
            }
            
            if (!empty($filters['type'])) {
                if ($filters['type'] === 'news') {
                    $where[] = "type = 'news' OR type IS NULL";
                } elseif ($filters['type'] === 'event') {
                    $where[] = "type = 'event'";
                }
            }
            
            $whereClause = implode(' AND ', $where);
            $sql = "SELECT COUNT(*) as count FROM news WHERE {$whereClause}";
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute($params);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            
            return $result['count'] ?? 0;
            
        } catch (Exception $e) {
            error_log("NewsModel countAll error: " . $e->getMessage());
            return 0;
        }
    }
    
    /**
     * Get all news only (not events)
     * ✅ FIXED: Changed username to full_name
     */
    public function getAllNewsOnly($filters = [], $limit = 20, $offset = 0) {
        $where = ['1=1'];
        $params = [];
        
        if (!empty($filters['status'])) {
            if ($filters['status'] === 'published') {
                $where[] = 'is_published = 1';
            } elseif ($filters['status'] === 'draft') {
                $where[] = 'is_published = 0';
            }
        }
        
        if (!empty($filters['category'])) {
            $where[] = 'category = ?';
            $params[] = $filters['category'];
        }
        
        if (!empty($filters['search'])) {
            $where[] = '(title LIKE ? OR excerpt LIKE ? OR content LIKE ?)';
            $search = '%' . $filters['search'] . '%';
            $params[] = $search;
            $params[] = $search;
            $params[] = $search;
        }
        
        if (!empty($filters['date_from'])) {
            $where[] = 'DATE(created_at) >= ?';
            $params[] = $filters['date_from'];
        }
        
        if (!empty($filters['date_to'])) {
            $where[] = 'DATE(created_at) <= ?';
            $params[] = $filters['date_to'];
        }
        
        $whereClause = implode(' AND ', $where);
        
        // ✅ FIXED: Changed from username to full_name
        $sql = "SELECT n.*, u.full_name as author_name 
                FROM news n 
                LEFT JOIN users u ON n.author_id = u.id 
                WHERE {$whereClause} 
                ORDER BY created_at DESC 
                LIMIT ? OFFSET ?";
        
        $params[] = $limit;
        $params[] = $offset;
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    /**
     * Get ALL content (news + events) with filters - SIMPLIFIED FIXED VERSION
     * ✅ FIXED: Changed username to full_name
     */
    public function getAll($filters = [], $limit = 20, $offset = 0) {
        try {
            error_log("=== NEWS MODEL getAll CALLED ===");
            error_log("Filters: " . json_encode($filters));
            
            $where = ['1=1'];
            $params = [];
            
            if (!empty($filters['status'])) {
                if ($filters['status'] === 'published') {
                    $where[] = 'is_published = 1';
                } elseif ($filters['status'] === 'draft') {
                    $where[] = 'is_published = 0';
                }
            }
            
            if (!empty($filters['category'])) {
                $where[] = 'category = ?';
                $params[] = $filters['category'];
            }
            
            if (!empty($filters['type'])) {
                $where[] = 'type = ?';
                $params[] = $filters['type'];
            }
            
            if (!empty($filters['search'])) {
                $where[] = '(title LIKE ? OR excerpt LIKE ? OR content LIKE ?)';
                $search = '%' . $filters['search'] . '%';
                $params[] = $search;
                $params[] = $search;
                $params[] = $search;
            }
            
            if (!empty($filters['date_from'])) {
                $where[] = 'DATE(created_at) >= ?';
                $params[] = $filters['date_from'];
            }
            
            if (!empty($filters['date_to'])) {
                $where[] = 'DATE(created_at) <= ?';
                $params[] = $filters['date_to'];
            }
            
            $whereClause = implode(' AND ', $where);
            
            // ✅ FIXED: Changed from username to full_name
            $sql = "SELECT n.*, u.full_name as author_name 
                    FROM news n 
                    LEFT JOIN users u ON n.author_id = u.id 
                    WHERE {$whereClause} 
                    ORDER BY created_at DESC 
                    LIMIT ? OFFSET ?";
            
            $params[] = $limit;
            $params[] = $offset;
            
            error_log("News SQL: " . $sql);
            error_log("Params: " . json_encode($params));
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute($params);
            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            error_log("Found " . count($results) . " items in news table");
            
            return $results;
            
        } catch (Exception $e) {
            error_log("NewsModel getAll error: " . $e->getMessage());
            error_log("Error trace: " . $e->getTraceAsString());
            return [];
        }
    }
    
    /**
     * Get statistics for BOTH news and events
     */
    public function getStats() {
        $stats = [
            'total' => 0,
            'published' => 0,
            'draft' => 0,
            'featured' => 0,
            'news' => 0,
            'events' => 0,
            'breaking' => 0,
            'this_month' => 0,
            'this_week' => 0
        ];
        
        try {
            // NEWS Statistics (now includes events too)
            $sql = "SELECT 
                COUNT(*) as total,
                SUM(is_published = 1) as published,
                SUM(is_published = 0) as draft,
                SUM(is_featured = 1) as featured,
                SUM(is_breaking = 1) as breaking,
                SUM(type = 'news' OR type IS NULL) as news_count,
                SUM(type = 'event') as events_count,
                SUM(MONTH(created_at) = MONTH(CURDATE()) AND YEAR(created_at) = YEAR(CURDATE())) as this_month,
                SUM(YEARWEEK(created_at, 1) = YEARWEEK(CURDATE(), 1)) as this_week
            FROM news";
            
            $stmt = $this->db->query($sql);
            $newsStats = $stmt->fetch(PDO::FETCH_ASSOC);
            
            // Now ALL statistics come from news table only
            $stats['total'] = (int)($newsStats['total'] ?? 0);
            $stats['published'] = (int)($newsStats['published'] ?? 0);
            $stats['draft'] = (int)($newsStats['draft'] ?? 0);
            $stats['featured'] = (int)($newsStats['featured'] ?? 0);
            $stats['breaking'] = (int)($newsStats['breaking'] ?? 0);
            $stats['news'] = (int)($newsStats['news_count'] ?? 0);
            $stats['events'] = (int)($newsStats['events_count'] ?? 0);
            $stats['this_month'] = (int)($newsStats['this_month'] ?? 0);
            $stats['this_week'] = (int)($newsStats['this_week'] ?? 0);
            
        } catch (Exception $e) {
            error_log("NewsModel getStats error: " . $e->getMessage());
        }
        
        return $stats;
    }
    
    /**
     * Get news by ID (from news table only now)
     * ✅ FIXED: Changed username to full_name
     */
    public function getById($id) {
        try {
            // Get from news table only (events are now in news table too)
            $sql = "SELECT n.*, u.full_name as author_name, u.role as author_role
                    FROM news n 
                    LEFT JOIN users u ON n.author_id = u.id 
                    WHERE n.id = ?";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$id]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($result) {
                // Set content_type based on type field
                $result['content_type'] = $result['type'] ?? 'news';
                
                // Fallback if no author found
                if (empty($result['author_name'])) {
                    $result['author_name'] = 'FCT Nursing College';
                    $result['author_role'] = 'Administration';
                }
                
                return $result;
            }
            
            return null;
            
        } catch (Exception $e) {
            error_log("NewsModel getById error: " . $e->getMessage());
            return null;
        }
    }
    
    /**
     * Update news or event (both in news table now)
     */
    public function update($id, $data) {
        try {
            // Check if it exists in news table
            $checkSql = "SELECT COUNT(*) as count FROM news WHERE id = ?";
            $checkStmt = $this->db->prepare($checkSql);
            $checkStmt->execute([$id]);
            $result = $checkStmt->fetch();
            
            if ($result['count'] > 0) {
                // Update news table
                return $this->updateNews($id, $data);
            } else {
                error_log("Item with ID $id not found in news table");
                return false;
            }
            
        } catch (Exception $e) {
            error_log("NewsModel update error: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Update news (including events)
     */
    private function updateNews($id, $data) {
        $fields = [];
        $values = [];
        
        foreach ($data as $key => $value) {
            $fields[] = "{$key} = ?";
            $values[] = $value;
        }
        
        $fields[] = "updated_at = NOW()";
        $values[] = $id;
        
        $sql = "UPDATE news SET " . implode(', ', $fields) . " WHERE id = ?";
        error_log("Update SQL: " . $sql);
        error_log("Update values: " . json_encode($values));
        
        $stmt = $this->db->prepare($sql);
        return $stmt->execute($values);
    }
    
    /**
     * Delete news or event (both from news table)
     */
    public function delete($id) {
        try {
            // Delete from news table (includes events)
            $sql = "DELETE FROM news WHERE id = ?";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$id]);
            
            return $stmt->rowCount() > 0;
            
        } catch (Exception $e) {
            error_log("NewsModel delete error: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Check if slug exists in news table (includes events)
     */
    public function slugExists($slug, $excludeId = null) {
        try {
            // Check news table (includes events)
            $sql = "SELECT COUNT(*) as count FROM news WHERE slug = ?";
            $params = [$slug];
            
            if ($excludeId) {
                $sql .= " AND id != ?";
                $params[] = $excludeId;
            }
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute($params);
            $result = $stmt->fetch();
            
            return $result['count'] > 0;
            
        } catch (Exception $e) {
            error_log("NewsModel slugExists error: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Get events specifically (for backwards compatibility)
     * ✅ FIXED: Changed username to full_name
     */
    public function getEvents($limit = 10, $offset = 0) {
        try {
            $sql = "SELECT n.*, u.full_name as author_name 
                    FROM news n 
                    LEFT JOIN users u ON n.author_id = u.id 
                    WHERE n.type = 'event' AND n.is_published = 1 
                    ORDER BY n.event_date ASC 
                    LIMIT ? OFFSET ?";
            
            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(1, (int)$limit, PDO::PARAM_INT);
            $stmt->bindValue(2, (int)$offset, PDO::PARAM_INT);
            $stmt->execute();
            
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
            
        } catch (Exception $e) {
            error_log("NewsModel getEvents error: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Count events (for backwards compatibility)
     */
    public function countEvents() {
        try {
            $sql = "SELECT COUNT(*) as total FROM news WHERE type = 'event' AND is_published = 1";
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result['total'] ?? 0;
            
        } catch (Exception $e) {
            error_log("NewsModel countEvents error: " . $e->getMessage());
            return 0;
        }
    }
    
    /**
     * DEBUG METHOD: Test all queries directly
     */
    public function debugQueries() {
        error_log("=== DEBUGGING ALL QUERIES ===");
        
        $results = [];
        
        try {
            // Test 1: Direct published news query
            $sql1 = "SELECT COUNT(*) as count FROM news WHERE is_published = 1";
            $stmt1 = $this->db->query($sql1);
            $result1 = $stmt1->fetch(PDO::FETCH_ASSOC);
            $results['direct_count'] = $result1['count'] ?? 0;
            error_log("Direct count: " . $results['direct_count']);
            
            // Test 2: Check type distribution
            $sql2 = "SELECT type, COUNT(*) as count FROM news GROUP BY type";
            $stmt2 = $this->db->query($sql2);
            $typeCounts = $stmt2->fetchAll(PDO::FETCH_ASSOC);
            $results['type_counts'] = $typeCounts;
            error_log("Type distribution: " . json_encode($typeCounts));
            
            // Test 3: Check actual data with author names
            $sql3 = "SELECT n.id, n.title, n.type, n.is_published, n.category, n.event_date, 
                            u.full_name as author_name 
                    FROM news n 
                    LEFT JOIN users u ON n.author_id = u.id 
                    ORDER BY n.created_at DESC 
                    LIMIT 10";
            $stmt3 = $this->db->query($sql3);
            $allNews = $stmt3->fetchAll(PDO::FETCH_ASSOC);
            $results['recent_items'] = $allNews;
            error_log("Recent items with author names: " . json_encode($allNews));
            
            return $results;
            
        } catch (Exception $e) {
            error_log("Debug error: " . $e->getMessage());
            return ['error' => $e->getMessage()];
        }
    }
}