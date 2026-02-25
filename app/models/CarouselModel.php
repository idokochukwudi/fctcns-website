<?php
/**
 * Carousel Model - Handles carousel slides CRUD operations
 */
class CarouselModel {
    private $db;
    
    public function __construct() {
        try {
            error_log("=== CAROUSEL MODEL CONSTRUCTOR ===");
            require_once __DIR__ . '/../config/database.php';
            $database = Database::getInstance();
            $this->db = $database->getConnection();
            error_log("Database connection established: " . ($this->db ? 'YES' : 'NO'));
        } catch (Exception $e) {
            error_log("CarouselModel database error: " . $e->getMessage());
            throw new Exception("Database connection failed");
        }
    }
    
    /**
     * Test database connection
     */
    public function testConnection() {
        error_log("=== TESTING CAROUSEL MODEL CONNECTION ===");
        error_log("Database object exists: " . (isset($this->db) ? 'YES' : 'NO'));
        
        if (!$this->db) {
            error_log("ERROR: Database connection is null!");
            return false;
        }
        
        try {
            $result = $this->db->query("SELECT 1")->fetch();
            error_log("Database query test: " . ($result ? 'SUCCESS' : 'FAILED'));
            
            // Check if table exists
            $tables = $this->db->query("SHOW TABLES LIKE 'carousel_slides'")->fetch();
            error_log("Carousel_slides table exists: " . ($tables ? 'YES' : 'NO'));
            
            // Count records
            $count = $this->db->query("SELECT COUNT(*) as count FROM carousel_slides")->fetch();
            error_log("Total slides in table: " . ($count ? $count['count'] : 'unknown'));
            
            return true;
        } catch (Exception $e) {
            error_log("Database test error: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Get all carousel slides (for admin)
     */
    public function getAllSlides() {
        try {
            $stmt = $this->db->query("
                SELECT * FROM carousel_slides 
                ORDER BY display_order ASC, created_at DESC
            ");
            return $stmt->fetchAll();
        } catch (Exception $e) {
            error_log("CarouselModel getAllSlides error: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Get active carousel slides for homepage
     */
    public function getActiveSlides($limit = 5) {
        try {
            $stmt = $this->db->prepare("
                SELECT * FROM carousel_slides 
                WHERE is_active = 1 
                ORDER BY display_order ASC, created_at DESC
                LIMIT :limit
            ");
            $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll();
        } catch (Exception $e) {
            error_log("CarouselModel getActiveSlides error: " . $e->getMessage());
            return $this->getFallbackSlides();
        }
    }
    
    /**
     * Get single slide by ID
     */
    public function getSlideById($id) {
        try {
            error_log("CarouselModel: getSlideById called with ID: " . $id);
            error_log("CarouselModel: Database connection exists: " . ($this->db ? 'YES' : 'NO'));
            
            if (!$this->db) {
                error_log("CarouselModel: Database connection is null!");
                return null;
            }
            
            $stmt = $this->db->prepare("SELECT * FROM carousel_slides WHERE id = :id");
            error_log("CarouselModel: Statement prepared: " . ($stmt ? 'YES' : 'NO'));
            
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            
            $result = $stmt->fetch();
            error_log("CarouselModel: Query executed, result: " . ($result ? 'FOUND' : 'NOT FOUND'));
            
            if ($result) {
                error_log("CarouselModel: Slide title: " . ($result['title'] ?? 'N/A'));
            }
            
            return $result;
            
        } catch (Exception $e) {
            error_log("CarouselModel getSlideById ERROR: " . $e->getMessage());
            error_log("CarouselModel getSlideById TRACE: " . $e->getTraceAsString());
            return null;
        }
    }
    
    /**
     * Create new carousel slide
     */
    public function createSlide($data) {
        try {
            $sql = "
                INSERT INTO carousel_slides 
                (title, subtitle, image_path, button_text, button_link, display_order, is_active, created_at, updated_at)
                VALUES (:title, :subtitle, :image_path, :button_text, :button_link, :display_order, :is_active, NOW(), NOW())
            ";
            
            $stmt = $this->db->prepare($sql);
            
            return $stmt->execute([
                ':title' => $data['title'] ?? '',
                ':subtitle' => $data['subtitle'] ?? '',
                ':image_path' => $data['image_path'] ?? '',
                ':button_text' => $data['button_text'] ?? '',
                ':button_link' => $data['button_link'] ?? '',
                ':display_order' => $data['display_order'] ?? 0,
                ':is_active' => $data['is_active'] ?? 1
            ]);
            
        } catch (Exception $e) {
            error_log("CarouselModel createSlide error: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Update existing carousel slide
     */
    public function updateSlide($id, $data) {
        try {
            $sql = "
                UPDATE carousel_slides SET
                title = :title,
                subtitle = :subtitle,
                image_path = :image_path,
                button_text = :button_text,
                button_link = :button_link,
                display_order = :display_order,
                is_active = :is_active,
                updated_at = NOW()
                WHERE id = :id
            ";
            
            $stmt = $this->db->prepare($sql);
            
            $params = [
                ':id' => $id,
                ':title' => $data['title'] ?? '',
                ':subtitle' => $data['subtitle'] ?? '',
                ':image_path' => $data['image_path'] ?? '',
                ':button_text' => $data['button_text'] ?? '',
                ':button_link' => $data['button_link'] ?? '',
                ':display_order' => $data['display_order'] ?? 0,
                ':is_active' => $data['is_active'] ?? 1
            ];
            
            return $stmt->execute($params);
            
        } catch (Exception $e) {
            error_log("CarouselModel updateSlide error: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Delete carousel slide
     */
    public function deleteSlide($id) {
        try {
            $stmt = $this->db->prepare("
                DELETE FROM carousel_slides WHERE id = :id
            ");
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (Exception $e) {
            error_log("CarouselModel deleteSlide error: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Toggle slide active status
     */
    public function toggleActive($id) {
        try {
            $stmt = $this->db->prepare("
                UPDATE carousel_slides 
                SET is_active = NOT is_active, updated_at = NOW()
                WHERE id = :id
            ");
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (Exception $e) {
            error_log("CarouselModel toggleActive error: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Update display order of slides
     */
    public function updateDisplayOrder($orders) {
        try {
            $this->db->beginTransaction();
            
            foreach ($orders as $order) {
                $stmt = $this->db->prepare("
                    UPDATE carousel_slides 
                    SET display_order = :display_order, updated_at = NOW()
                    WHERE id = :id
                ");
                $stmt->execute([
                    ':id' => $order['id'],
                    ':display_order' => $order['order']
                ]);
            }
            
            return $this->db->commit();
            
        } catch (Exception $e) {
            $this->db->rollBack();
            error_log("CarouselModel updateDisplayOrder error: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Fallback slides if database fails
     */
    private function getFallbackSlides() {
        return [
            [
                'title' => 'Welcome to FCT College of Nursing Sciences',
                'subtitle' => 'NMCN & NBTE Accredited Nursing Education Since 1989',
                'image_path' => '/assets/images/carousel/slide1.jpg',
                'button_text' => 'Explore Programs',
                'button_link' => '/programs'
            ],
            [
                'title' => 'Excellence in Nursing Education',
                'subtitle' => 'Fully accredited programs with modern clinical facilities',
                'image_path' => '/assets/images/carousel/slide2.jpg',
                'button_text' => 'Learn More',
                'button_link' => '/about'
            ]
        ];
    }
    
    /**
     * Get next available display order
     */
    public function getNextDisplayOrder() {
        try {
            $stmt = $this->db->query("
                SELECT COALESCE(MAX(display_order), 0) + 1 as next_order 
                FROM carousel_slides
            ");
            $result = $stmt->fetch();
            return $result['next_order'] ?? 1;
        } catch (Exception $e) {
            error_log("CarouselModel getNextDisplayOrder error: " . $e->getMessage());
            return 1;
        }
    }
}