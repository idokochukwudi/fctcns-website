<?php
/**
 * Research Model - Updated to use existing Database class
 * Handles all database operations for research publications
 */

// Load database configuration
require_once __DIR__ . '/../config/database.php';

class ResearchModel
{
    private $tablePublications = 'research_publications';
    private $tableCategories = 'research_categories';
    
    /**
     * Get database instance from your existing Database class
     */
    private function getDb()
    {
        return Database::getInstance();
    }
    
    /**
     * Test database connection
     */
    public function testConnection()
    {
        try {
            $db = $this->getDb();
            $result = $db->fetchOne("SELECT 1 as test");
            return $result && $result['test'] == 1;
        } catch (Exception $e) {
            error_log("ResearchModel database test failed: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Get all publications with optional filters
     */
    public function getAll($filters = [])
    {
        $query = "SELECT p.*, c.name as category_name 
                  FROM {$this->tablePublications} p
                  LEFT JOIN {$this->tableCategories} c ON p.research_area = c.slug
                  WHERE 1=1";
        
        $params = [];
        
        // Apply filters
        if (isset($filters['is_published']) && $filters['is_published'] !== '') {
            $query .= " AND p.is_published = ?";
            $params[] = $filters['is_published'];
        }
        
        if (!empty($filters['research_area'])) {
            $query .= " AND p.research_area = ?";
            $params[] = $filters['research_area'];
        }
        
        if (!empty($filters['publication_type'])) {
            $query .= " AND p.publication_type = ?";
            $params[] = $filters['publication_type'];
        }
        
        if (!empty($filters['year'])) {
            $query .= " AND YEAR(p.publication_date) = ?";
            $params[] = $filters['year'];
        }
        
        if (!empty($filters['search'])) {
            $query .= " AND (p.title LIKE ? OR p.authors LIKE ? OR p.keywords LIKE ?)";
            $searchTerm = "%{$filters['search']}%";
            $params[] = $searchTerm;
            $params[] = $searchTerm;
            $params[] = $searchTerm;
        }
        
        // Ordering
        $orderBy = $filters['order_by'] ?? 'publication_date';
        $orderDir = $filters['order_dir'] ?? 'DESC';
        $query .= " ORDER BY p.{$orderBy} {$orderDir}";
        
        // FIX: Don't use parameter binding for LIMIT/OFFSET - add them directly to query
        if (!empty($filters['limit'])) {
            $limit = (int)$filters['limit'];
            $query .= " LIMIT $limit";
            
            if (!empty($filters['offset'])) {
                $offset = (int)$filters['offset'];
                $query .= " OFFSET $offset";
            }
        }
        
        $db = $this->getDb();
        return $db->fetchAll($query, $params);
    }
    
    /**
     * Get single publication by ID
     */
    public function getById($id)
    {
        $query = "SELECT p.*, c.name as category_name 
                  FROM {$this->tablePublications} p
                  LEFT JOIN {$this->tableCategories} c ON p.research_area = c.slug
                  WHERE p.id = ?";
        
        $db = $this->getDb();
        return $db->fetchOne($query, [$id]);
    }
    
    /**
     * Get all active categories
     */
    public function getCategories($activeOnly = true)
    {
        $query = "SELECT * FROM {$this->tableCategories}";
        
        if ($activeOnly) {
            $query .= " WHERE is_active = 1";
        }
        
        $query .= " ORDER BY sort_order, name";
        
        $db = $this->getDb();
        return $db->fetchAll($query);
    }
    
    /**
     * Create new publication
     */
    public function create($data)
    {
        error_log("💽 [MODEL CREATE START] " . date('Y-m-d H:i:s'));
        error_log("  Data received keys: " . implode(', ', array_keys($data)));
        
        // CRITICAL: Check for created_by
        if (!isset($data['created_by']) || empty($data['created_by'])) {
            error_log("⚠️ WARNING: created_by missing in data. Session user_id: " . ($_SESSION['user_id'] ?? 'NOT SET'));
            error_log("  Setting created_by to 1 (admin)");
            $data['created_by'] = 1;
        }
        
        // CRITICAL: Check checkbox fields
        if (!isset($data['is_published'])) {
            error_log("⚠️ WARNING: is_published not set in data, defaulting to 0");
            $data['is_published'] = 0;
        }
        
        if (!isset($data['is_featured'])) {
            error_log("⚠️ WARNING: is_featured not set in data, defaulting to 0");
            $data['is_featured'] = 0;
        }
        
        // Check published_at based on is_published
        if ($data['is_published'] == 1 && empty($data['published_at'])) {
            error_log("⚠️ WARNING: is_published=1 but published_at not set, adding timestamp");
            $data['published_at'] = date('Y-m-d H:i:s');
        }
        
        // Log critical values
        error_log("🔍 Critical values:");
        error_log("  - created_by: " . ($data['created_by'] ?? 'MISSING'));
        error_log("  - is_published: " . ($data['is_published'] ?? 'MISSING'));
        error_log("  - is_featured: " . ($data['is_featured'] ?? 'MISSING'));
        error_log("  - published_at: " . ($data['published_at'] ?? 'NULL'));
        
        // Set default values
        $defaults = [
            'views_count' => 0,
            'downloads_count' => 0,
            'citations' => 0,
            'is_published' => 0,  // Default to unpublished
            'is_featured' => 0,   // Default to not featured
            'published_at' => null
        ];
        
        // Merge with provided data (provided data overrides defaults)
        $finalData = array_merge($defaults, $data);
        
        error_log("📦 Final data to insert: " . print_r($finalData, true));
        
        try {
            $db = $this->getDb();
            
            // DEBUG: Check if database connection is working
            error_log("🔄 Attempting database insert...");
            
            // Insert the data
            $result = $db->insert('research_publications', $finalData);
            
            if ($result) {
                error_log("✅ [MODEL CREATE SUCCESS] ID: $result");
                
                // Verify the insertion
                $verify = $this->getById($result);
                if ($verify) {
                    error_log("✅ Record verified in database!");
                    error_log("  Verified title: " . ($verify['title'] ?? 'NOT FOUND'));
                } else {
                    error_log("⚠️ WARNING: Record inserted but cannot verify!");
                }
                
                return $result;
            } else {
                error_log("❌ [MODEL CREATE FAILED] Insert returned: " . ($result === false ? 'false' : 'empty'));
                
                // Try to get last error if available
                if (method_exists($db, 'getLastError')) {
                    $lastError = $db->getLastError();
                    error_log("  Last database error: " . print_r($lastError, true));
                }
                
                // Try a simple test query to see if DB is working
                try {
                    $test = $db->fetchOne("SELECT 1 as test");
                    error_log("  Database connection test: " . ($test ? 'PASSED' : 'FAILED'));
                } catch (Exception $e) {
                    error_log("  Database test query failed: " . $e->getMessage());
                }
                
                return false;
            }
            
        } catch (Exception $e) {
            error_log("💥 [MODEL CREATE EXCEPTION] " . $e->getMessage());
            error_log("  Stack trace: " . $e->getTraceAsString());
            return false;
        }
        
        error_log("💽 [MODEL CREATE END]");
    }
    
    /**
     * Update publication
     */
    public function update($id, $data)
    {
        // Don't update created_by
        unset($data['created_by']);
        
        // If toggling publish status, update published_at
        if (isset($data['is_published'])) {
            $current = $this->getById($id);
            if ($data['is_published'] == 1 && $current['is_published'] == 0) {
                $data['published_at'] = date('Y-m-d H:i:s');
            } elseif ($data['is_published'] == 0) {
                $data['published_at'] = null;
            }
        }
        
        $db = $this->getDb();
        return $db->update('research_publications', $data, 'id = :id', ['id' => $id]);
    }
    
    /**
     * Delete publication
     */
    public function delete($id)
    {
        // First, get file paths to delete files later
        $publication = $this->getById($id);
        
        $db = $this->getDb();
        $result = $db->delete('research_publications', 'id = :id', ['id' => $id]);
        
        if ($result) {
            // Return file paths for cleanup
            return [
                'file_path' => $publication['file_path'] ?? null,
                'thumbnail_path' => $publication['thumbnail_path'] ?? null
            ];
        }
        
        return false;
    }
    
    /**
     * Toggle publish status
     */
    public function toggleStatus($id, $status = null)
    {
        if ($status === null) {
            // Toggle current status
            $current = $this->getById($id);
            $newStatus = $current['is_published'] ? 0 : 1;
        } else {
            $newStatus = $status ? 1 : 0;
        }
        
        return $this->update($id, [
            'is_published' => $newStatus,
            'published_at' => $newStatus ? date('Y-m-d H:i:s') : null
        ]);
    }
    
    /**
     * Increment view count
     */
    public function incrementViews($id)
    {
        $query = "UPDATE {$this->tablePublications} 
                  SET views_count = views_count + 1 
                  WHERE id = ?";
        
        $db = $this->getDb();
        $stmt = $db->query($query, [$id]);
        return $stmt->rowCount();
    }
    
    /**
     * Increment download count
     */
    public function incrementDownloads($id)
    {
        $query = "UPDATE {$this->tablePublications} 
                  SET downloads_count = downloads_count + 1 
                  WHERE id = ?";
        
        $db = $this->getDb();
        $stmt = $db->query($query, [$id]);
        return $stmt->rowCount();
    }
    
    /**
     * Get statistics
     */
    public function getStats()
    {
        $query = "SELECT 
                    COUNT(*) as total_publications,
                    SUM(is_published) as published_count,
                    SUM(is_featured) as featured_count,
                    SUM(views_count) as total_views,
                    SUM(downloads_count) as total_downloads,
                    SUM(citations) as total_citations,
                    COUNT(DISTINCT research_area) as categories_used
                  FROM {$this->tablePublications}";
        
        $db = $this->getDb();
        $stats = $db->fetchOne($query);
        
        // Get publications by type
        $typeQuery = "SELECT publication_type, COUNT(*) as count 
                      FROM {$this->tablePublications} 
                      GROUP BY publication_type";
        $stats['by_type'] = $db->fetchAll($typeQuery);
        
        // Get recent publications
        $recentQuery = "SELECT title, publication_date, views_count 
                        FROM {$this->tablePublications} 
                        WHERE is_published = 1 
                        ORDER BY publication_date DESC 
                        LIMIT 5";
        $stats['recent'] = $db->fetchAll($recentQuery);
        
        return $stats;
    }
    
    /**
     * Get publications for public display
     */
    public function getPublished($limit = 20, $offset = 0)
    {
        $query = "SELECT p.*, c.name as category_name 
                  FROM {$this->tablePublications} p
                  LEFT JOIN {$this->tableCategories} c ON p.research_area = c.slug
                  WHERE p.is_published = 1 
                  AND (c.is_active = 1 OR c.id IS NULL)
                  ORDER BY p.publication_date DESC, p.created_at DESC
                  LIMIT $limit OFFSET $offset";
        
        $db = $this->getDb();
        return $db->fetchAll($query);
    }
    
    /**
     * Get publications by category for public display
     */
    public function getByCategory($categorySlug, $limit = 20)
    {
        $query = "SELECT p.*, c.name as category_name 
                  FROM {$this->tablePublications} p
                  JOIN {$this->tableCategories} c ON p.research_area = c.slug
                  WHERE p.is_published = 1 
                  AND c.is_active = 1
                  AND c.slug = ?
                  ORDER BY p.publication_date DESC
                  LIMIT $limit";
        
        $db = $this->getDb();
        return $db->fetchAll($query, [$categorySlug]);
    }
    
    /**
     * Get featured publications
     */
    public function getFeatured($limit = 5)
    {
        $query = "SELECT p.*, c.name as category_name 
                  FROM {$this->tablePublications} p
                  LEFT JOIN {$this->tableCategories} c ON p.research_area = c.slug
                  WHERE p.is_published = 1 
                  AND p.is_featured = 1
                  AND (c.is_active = 1 OR c.id IS NULL)
                  ORDER BY p.publication_date DESC
                  LIMIT $limit";
        
        $db = $this->getDb();
        return $db->fetchAll($query);
    }
    
    /**
     * Bulk update publications
     */
    public function bulkUpdate($ids, $data)
    {
        if (empty($ids)) {
            return 0;
        }
        
        $db = $this->getDb();
        
        // Build WHERE clause for multiple IDs
        $placeholders = implode(',', array_fill(0, count($ids), '?'));
        $whereClause = "id IN ({$placeholders})";
        
        // Prepare params including IDs
        $params = $ids;
        
        return $db->update('research_publications', $data, $whereClause, $params);
    }
    
    /**
     * Bulk delete publications
     */
    public function bulkDelete($ids)
    {
        if (empty($ids)) {
            return 0;
        }
        
        $db = $this->getDb();
        
        // Get file paths for cleanup
        $placeholders = implode(',', array_fill(0, count($ids), '?'));
        $query = "SELECT file_path, thumbnail_path 
                  FROM {$this->tablePublications} 
                  WHERE id IN ({$placeholders})";
        
        $publications = $db->fetchAll($query, $ids);
        
        // Build WHERE clause for delete
        $whereClause = "id IN ({$placeholders})";
        
        // Delete publications
        $result = $db->delete('research_publications', $whereClause, $ids);
        
        if ($result) {
            // Return file paths for cleanup
            $filePaths = [];
            foreach ($publications as $pub) {
                if (!empty($pub['file_path'])) {
                    $filePaths[] = $pub['file_path'];
                }
                if (!empty($pub['thumbnail_path'])) {
                    $filePaths[] = $pub['thumbnail_path'];
                }
            }
            return $filePaths;
        }
        
        return false;
    }
}