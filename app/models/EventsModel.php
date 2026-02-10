<?php
/**
 * Events Model
 * Handles events data operations
 */
class EventsModel {
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
     * Get all events with filters
     */
    public function getAll($filters = [], $limit = 20, $offset = 0) {
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
            $where[] = '(title LIKE ? OR description LIKE ? OR content LIKE ?)';
            $search = '%' . $filters['search'] . '%';
            $params[] = $search;
            $params[] = $search;
            $params[] = $search;
        }
        
        if (!empty($filters['date_from'])) {
            $where[] = 'event_date >= ?';
            $params[] = $filters['date_from'];
        }
        
        if (!empty($filters['date_to'])) {
            $where[] = 'event_date <= ?';
            $params[] = $filters['date_to'];
        }
        
        $whereClause = implode(' AND ', $where);
        
        $sql = "SELECT e.*, u.username as author_name 
                FROM events e 
                LEFT JOIN users u ON e.author_id = u.id 
                WHERE {$whereClause} 
                ORDER BY event_date DESC, created_at DESC 
                LIMIT ? OFFSET ?";
        
        $params[] = $limit;
        $params[] = $offset;
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    /**
     * Count all events with filters
     */
    public function countAll($filters = []) {
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
            $where[] = '(title LIKE ? OR description LIKE ? OR content LIKE ?)';
            $search = '%' . $filters['search'] . '%';
            $params[] = $search;
            $params[] = $search;
            $params[] = $search;
        }
        
        $whereClause = implode(' AND ', $where);
        
        $sql = "SELECT COUNT(*) as total FROM events WHERE {$whereClause}";
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        $result = $stmt->fetch();
        return $result['total'] ?? 0;
    }
    
    /**
     * Get event by ID
     */
    public function getById($id) {
        $sql = "SELECT e.*, u.username as author_name 
                FROM events e 
                LEFT JOIN users u ON e.author_id = u.id 
                WHERE e.id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    /**
     * Get event by slug
     */
    public function getBySlug($slug) {
        $sql = "SELECT e.*, u.username as author_name 
                FROM events e 
                LEFT JOIN users u ON e.author_id = u.id 
                WHERE e.slug = ? AND e.is_published = 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$slug]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    /**
     * Check if slug exists
     */
    public function slugExists($slug, $excludeId = null) {
        $sql = "SELECT COUNT(*) as count FROM events WHERE slug = ?";
        $params = [$slug];
        
        if ($excludeId) {
            $sql .= " AND id != ?";
            $params[] = $excludeId;
        }
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        $result = $stmt->fetch();
        return $result['count'] > 0;
    }
    
    /**
     * Create event
     */
    public function create($data) {
        $fields = [];
        $placeholders = [];
        $values = [];
        
        foreach ($data as $key => $value) {
            $fields[] = $key;
            $placeholders[] = '?';
            $values[] = $value;
        }
        
        // Set timestamps
        $fields[] = 'created_at';
        $fields[] = 'updated_at';
        $placeholders[] = 'NOW()';
        $placeholders[] = 'NOW()';
        
        $sql = "INSERT INTO events (" . implode(', ', $fields) . ") 
                VALUES (" . implode(', ', $placeholders) . ")";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute($values);
        return $this->db->lastInsertId();
    }
    
    /**
     * Update event
     */
    public function update($id, $data) {
        $fields = [];
        $values = [];
        
        foreach ($data as $key => $value) {
            $fields[] = "{$key} = ?";
            $values[] = $value;
        }
        
        $fields[] = "updated_at = NOW()";
        $values[] = $id;
        
        $sql = "UPDATE events SET " . implode(', ', $fields) . " WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute($values);
    }
    
    /**
     * Delete event
     */
    public function delete($id) {
        $sql = "DELETE FROM events WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$id]);
    }
    
    /**
     * Get statistics
     */
    public function getStats() {
        $stats = [];
        
        $queries = [
            'total' => "SELECT COUNT(*) as total FROM events",
            'published' => "SELECT COUNT(*) as total FROM events WHERE is_published = 1",
            'draft' => "SELECT COUNT(*) as total FROM events WHERE is_published = 0",
            'featured' => "SELECT COUNT(*) as total FROM events WHERE is_featured = 1",
            'upcoming' => "SELECT COUNT(*) as total FROM events WHERE is_published = 1 AND event_date >= CURDATE()",
            'past' => "SELECT COUNT(*) as total FROM events WHERE is_published = 1 AND event_date < CURDATE()",
            'today' => "SELECT COUNT(*) as total FROM events WHERE DATE(created_at) = CURDATE()",
            'month' => "SELECT COUNT(*) as total FROM events WHERE MONTH(created_at) = MONTH(CURDATE()) AND YEAR(created_at) = YEAR(CURDATE())"
        ];
        
        foreach ($queries as $key => $sql) {
            $stmt = $this->db->query($sql);
            $stats[$key] = $stmt->fetch()['total'];
        }
        
        // Total registrations
        $sql = "SELECT COUNT(*) as total FROM event_registrations";
        $stmt = $this->db->query($sql);
        $stats['registrations'] = $stmt->fetch()['total'];
        
        return $stats;
    }
    
    /**
     * Get categories
     */
    public function getCategories() {
        $sql = "SELECT DISTINCT category FROM events WHERE category IS NOT NULL AND category != '' ORDER BY category";
        $stmt = $this->db->query($sql);
        $categories = $stmt->fetchAll(PDO::FETCH_COLUMN);
        
        if (empty($categories)) {
            return [
                'Seminar',
                'Workshop', 
                'Conference',
                'Webinar',
                'Training',
                'Symposium',
                'Meeting',
                'Social Event'
            ];
        }
        
        return $categories;
    }
    
    /**
     * Get categories with count
     */
    public function getCategoriesWithCount() {
        $sql = "SELECT category, COUNT(*) as count 
                FROM events 
                WHERE is_published = 1 AND category IS NOT NULL AND category != ''
                GROUP BY category 
                ORDER BY count DESC, category";
        
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    /**
     * Increment views
     */
    public function incrementViews($id) {
        $sql = "UPDATE events SET views_count = views_count + 1 WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$id]);
    }
    
    /**
     * Get related events
     */
    public function getRelated($id, $limit = 3) {
        // Get current event category
        $current = $this->getById($id);
        if (!$current || empty($current['category'])) {
            return [];
        }
        
        $sql = "SELECT e.*, u.username as author_name 
                FROM events e 
                LEFT JOIN users u ON e.author_id = u.id 
                WHERE e.id != ? AND e.category = ? AND e.is_published = 1 
                ORDER BY e.event_date DESC, e.created_at DESC 
                LIMIT ?";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id, $current['category'], $limit]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    /**
     * Get upcoming events
     */
    public function getUpcoming($limit = 12, $offset = 0) {
        $sql = "SELECT e.*, u.username as author_name 
                FROM events e 
                LEFT JOIN users u ON e.author_id = u.id 
                WHERE e.is_published = 1 
                AND (e.event_date >= CURDATE() OR e.event_date IS NULL)
                ORDER BY e.event_date ASC, e.created_at DESC 
                LIMIT ? OFFSET ?";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$limit, $offset]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    /**
     * Count upcoming events
     */
    public function countUpcoming() {
        $sql = "SELECT COUNT(*) as total 
                FROM events 
                WHERE is_published = 1 
                AND (event_date >= CURDATE() OR event_date IS NULL)";
        
        $stmt = $this->db->query($sql);
        $result = $stmt->fetch();
        return $result['total'] ?? 0;
    }
    
    /**
     * Get featured events
     */
    public function getFeatured($limit = 3) {
        $sql = "SELECT e.*, u.username as author_name 
                FROM events e 
                LEFT JOIN users u ON e.author_id = u.id 
                WHERE e.is_published = 1 AND e.is_featured = 1 
                ORDER BY e.event_date ASC, e.created_at DESC 
                LIMIT ?";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$limit]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    /**
     * Get past events
     */
    public function getPast($limit = 10) {
        $sql = "SELECT e.*, u.username as author_name 
                FROM events e 
                LEFT JOIN users u ON e.author_id = u.id 
                WHERE e.is_published = 1 AND e.event_date < CURDATE()
                ORDER BY e.event_date DESC 
                LIMIT ?";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$limit]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    /**
     * Get calendar events
     */
    public function getCalendarEvents() {
        $sql = "SELECT id, title, event_date as start, event_end_date as end, 
                       location, is_featured
                FROM events 
                WHERE is_published = 1 
                AND event_date >= DATE_SUB(CURDATE(), INTERVAL 1 MONTH)
                ORDER BY event_date ASC";
        
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    /**
     * Get event registrations
     */
    public function getRegistrations($eventId) {
        $sql = "SELECT * FROM event_registrations 
                WHERE event_id = ? 
                ORDER BY created_at DESC";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$eventId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    /**
     * Check if email is registered for event
     */
    public function isRegistered($eventId, $email) {
        $sql = "SELECT COUNT(*) as count 
                FROM event_registrations 
                WHERE event_id = ? AND email = ?";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$eventId, $email]);
        $result = $stmt->fetch();
        return $result['count'] > 0;
    }
    
    /**
     * Register for event
     */
    public function register($data) {
        $fields = [];
        $placeholders = [];
        $values = [];
        
        foreach ($data as $key => $value) {
            $fields[] = $key;
            $placeholders[] = '?';
            $values[] = $value;
        }
        
        $sql = "INSERT INTO event_registrations (" . implode(', ', $fields) . ") 
                VALUES (" . implode(', ', $placeholders) . ")";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute($values);
        return $this->db->lastInsertId();
    }
    
    /**
     * Increment participant count
     */
    public function incrementParticipants($eventId) {
        $sql = "UPDATE events SET current_participants = current_participants + 1 WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$eventId]);
    }
    
    /**
     * Search events
     */
    public function search($query, $limit = 20) {
        $sql = "SELECT e.*, u.username as author_name 
                FROM events e 
                LEFT JOIN users u ON e.author_id = u.id 
                WHERE e.is_published = 1 
                AND (e.title LIKE ? OR e.description LIKE ? OR e.content LIKE ? OR e.tags LIKE ?)
                ORDER BY e.event_date DESC 
                LIMIT ?";
        
        $search = '%' . $query . '%';
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$search, $search, $search, $search, $limit]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    /**
     * Get events by category
     */
    public function getByCategory($category, $limit = 10, $offset = 0) {
        $sql = "SELECT e.*, u.username as author_name 
                FROM events e 
                LEFT JOIN users u ON e.author_id = u.id 
                WHERE e.is_published = 1 AND e.category = ? 
                ORDER BY e.event_date DESC, e.created_at DESC 
                LIMIT ? OFFSET ?";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$category, $limit, $offset]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    /**
     * Count by category
     */
    public function countByCategory($category) {
        $sql = "SELECT COUNT(*) as total FROM events WHERE is_published = 1 AND category = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$category]);
        $result = $stmt->fetch();
        return $result['total'] ?? 0;
    }
    
    /**
     * Export events to CSV
     */
    public function export($filters = []) {
        $events = $this->getAll($filters, 10000, 0);
        
        $output = fopen('php://temp', 'w');
        
        // Add BOM for UTF-8
        fwrite($output, "\xEF\xBB\xBF");
        
        // Headers
        fputcsv($output, [
            'ID', 'Title', 'Category', 'Event Date', 'Location', 
            'Status', 'Featured', 'Registrations', 'Max Participants',
            'Organizer', 'Created Date'
        ]);
        
        // Data
        foreach ($events as $event) {
            fputcsv($output, [
                $event['id'],
                $event['title'],
                $event['category'] ?? 'N/A',
                $event['event_date'],
                $event['location'] ?? 'N/A',
                $event['is_published'] ? 'Published' : 'Draft',
                $event['is_featured'] ? 'Yes' : 'No',
                $event['current_participants'],
                $event['max_participants'] ?? 'Unlimited',
                $event['organizer'] ?? 'N/A',
                $event['created_at']
            ]);
        }
        
        rewind($output);
        $csv = stream_get_contents($output);
        fclose($output);
        
        return $csv;
    }
}