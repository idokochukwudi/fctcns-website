<?php
/**
 * Events Controller
 * Handles events management (using news table with type = 'event')
 */
class EventsController extends Controller {
    
    private $db;
    private $eventsModel;
    
    public function __construct() {
        parent::__construct();
        
        // Setup database
        require_once __DIR__ . '/../config/database.php';
        $database = Database::getInstance();
        $this->db = $database->getConnection();
        
        // Initialize EventsModel
        require_once APP_PATH . '/models/EventsModel.php';
        $this->eventsModel = new EventsModel();
        
        // Common data
        $this->data = array_merge($this->data, [
            'user' => $_SESSION ?? [],
            'baseUrl' => BASE_URL
        ]);
    }
    
    /**
     * Admin: List all events
     */
    public function index() {
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
        
        try {
            // Get filter parameters
            $filters = [
                'status' => $_GET['status'] ?? '',
                'category' => $_GET['category'] ?? '',
                'search' => $_GET['search'] ?? '',
                'date_from' => $_GET['date_from'] ?? '',
                'date_to' => $_GET['date_to'] ?? ''
            ];
            
            // Get pagination
            $page = max(1, (int)($_GET['page'] ?? 1));
            $limit = 20;
            $offset = ($page - 1) * $limit;
            
            // Get events from news table
            $events = $this->getAllEventsFromNewsTable($filters, $limit, $offset);
            $total = $this->countAllEventsFromNewsTable($filters);
            $totalPages = ceil($total / $limit);
            
            // Get stats
            $stats = $this->getEventStatsFromNewsTable();
            
            // Ensure stats array has all required keys with defaults
            $defaultStats = [
                'total' => 0,
                'published' => 0,
                'upcoming' => 0,
                'featured' => 0,
                'past' => 0,
                'registrations' => 0,
                'draft' => 0
            ];
            
            $stats = array_merge($defaultStats, $stats ?? []);
            
            // Get categories from news table
            $categories = $this->getEventCategoriesFromNewsTable();
            
            // Set current page for navigation
            $this->data['currentPage'] = 'events';
            
            // Prepare data for view
            $this->data = array_merge($this->data, [
                'events' => $events ?? [],
                'stats' => $stats,
                'categories' => $categories,
                'filters' => $filters,
                'pagination' => [
                    'current' => $page,
                    'total' => $totalPages,
                    'limit' => $limit,
                    'totalCount' => $total
                ],
                'csrf_token' => $this->generateCsrfToken()
            ]);
            
            // Debug log
            error_log("EventsController index - stats: " . json_encode($stats));
            error_log("EventsController index - events count: " . count($events));
            
            $this->render('admin/events/index');
            
        } catch (Exception $e) {
            error_log("EventsController index error: " . $e->getMessage());
            
            // Set fallback data
            $this->data = array_merge($this->data, [
                'events' => [],
                'stats' => [
                    'total' => 0,
                    'published' => 0,
                    'upcoming' => 0,
                    'featured' => 0,
                    'past' => 0,
                    'registrations' => 0,
                    'draft' => 0
                ],
                'categories' => [],
                'filters' => [],
                'pagination' => [
                    'current' => 1,
                    'total' => 0,
                    'limit' => 20,
                    'totalCount' => 0
                ],
                'flash_error' => 'Failed to load events: ' . $e->getMessage(),
                'currentPage' => 'events',
                'csrf_token' => $this->generateCsrfToken()
            ]);
            
            $this->render('admin/events/index');
        }
    }

    private function getAllEventsFromNewsTable($filters = [], $limit = 20, $offset = 0) {
        $where = "WHERE type = 'event'";
        $params = [];
        
        if (!empty($filters['status'])) {
            if ($filters['status'] === 'published') {
                $where .= " AND is_published = 1";
            } elseif ($filters['status'] === 'draft') {
                $where .= " AND is_published = 0";
            } elseif ($filters['status'] === 'featured') {
                $where .= " AND is_featured = 1";
            }
        }
        
        if (!empty($filters['category'])) {
            $where .= " AND category = ?";
            $params[] = $filters['category'];
        }
        
        if (!empty($filters['search'])) {
            $where .= " AND (title LIKE ? OR content LIKE ?)";
            $searchTerm = "%{$filters['search']}%";
            $params[] = $searchTerm;
            $params[] = $searchTerm;
        }
        
        if (!empty($filters['date_from'])) {
            $where .= " AND event_date >= ?";
            $params[] = $filters['date_from'];
        }
        
        if (!empty($filters['date_to'])) {
            $where .= " AND event_date <= ?";
            $params[] = $filters['date_to'];
        }
        
        $sql = "SELECT n.*, u.username as author_name 
                FROM news n 
                LEFT JOIN users u ON n.author_id = u.id 
                $where 
                ORDER BY n.event_date ASC, n.created_at DESC 
                LIMIT ? OFFSET ?";
        
        $params[] = $limit;
        $params[] = $offset;
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    private function countAllEventsFromNewsTable($filters = []) {
        $where = "WHERE type = 'event'";
        $params = [];
        
        if (!empty($filters['status'])) {
            if ($filters['status'] === 'published') {
                $where .= " AND is_published = 1";
            } elseif ($filters['status'] === 'draft') {
                $where .= " AND is_published = 0";
            } elseif ($filters['status'] === 'featured') {
                $where .= " AND is_featured = 1";
            }
        }
        
        if (!empty($filters['category'])) {
            $where .= " AND category = ?";
            $params[] = $filters['category'];
        }
        
        if (!empty($filters['search'])) {
            $where .= " AND (title LIKE ? OR content LIKE ?)";
            $searchTerm = "%{$filters['search']}%";
            $params[] = $searchTerm;
            $params[] = $searchTerm;
        }
        
        if (!empty($filters['date_from'])) {
            $where .= " AND event_date >= ?";
            $params[] = $filters['date_from'];
        }
        
        if (!empty($filters['date_to'])) {
            $where .= " AND event_date <= ?";
            $params[] = $filters['date_to'];
        }
        
        $sql = "SELECT COUNT(*) as total FROM news $where";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        $result = $stmt->fetch();
        return $result['total'] ?? 0;
    }

    private function getEventStatsFromNewsTable() {
        $stats = [];
        
        // Total events
        $sql = "SELECT COUNT(*) as total FROM news WHERE type = 'event'";
        $stmt = $this->db->query($sql);
        $result = $stmt->fetch();
        $stats['total'] = $result['total'] ?? 0;
        
        // Published events
        $sql = "SELECT COUNT(*) as published FROM news WHERE type = 'event' AND is_published = 1";
        $stmt = $this->db->query($sql);
        $result = $stmt->fetch();
        $stats['published'] = $result['published'] ?? 0;
        
        // Draft events
        $sql = "SELECT COUNT(*) as draft FROM news WHERE type = 'event' AND is_published = 0";
        $stmt = $this->db->query($sql);
        $result = $stmt->fetch();
        $stats['draft'] = $result['draft'] ?? 0;
        
        // Featured events
        $sql = "SELECT COUNT(*) as featured FROM news WHERE type = 'event' AND is_featured = 1 AND is_published = 1";
        $stmt = $this->db->query($sql);
        $result = $stmt->fetch();
        $stats['featured'] = $result['featured'] ?? 0;
        
        // Upcoming events
        $sql = "SELECT COUNT(*) as upcoming FROM news WHERE type = 'event' AND is_published = 1 AND (event_date >= CURDATE() OR event_date IS NULL)";
        $stmt = $this->db->query($sql);
        $result = $stmt->fetch();
        $stats['upcoming'] = $result['upcoming'] ?? 0;
        
        // Past events
        $sql = "SELECT COUNT(*) as past FROM news WHERE type = 'event' AND is_published = 1 AND event_date < CURDATE()";
        $stmt = $this->db->query($sql);
        $result = $stmt->fetch();
        $stats['past'] = $result['past'] ?? 0;
        
        return $stats;
    }

    private function getEventCategoriesFromNewsTable() {
        $sql = "SELECT DISTINCT category 
                FROM news 
                WHERE type = 'event' AND category IS NOT NULL AND category != '' 
                ORDER BY category";
        
        $stmt = $this->db->query($sql);
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        $categories = [];
        foreach ($results as $row) {
            $categories[] = $row['category'];
        }
        
        return $categories;
    }
    
    /**
     * Admin: Show create event form
     */
    public function create() {
        $this->layout = 'admin';
        
        require_once __DIR__ . '/../middleware/AuthMiddleware.php';
        AuthMiddleware::authenticate();
        
        $userRole = $_SESSION['user_role'] ?? '';
        if (!in_array($userRole, ['admin', 'editor'])) {
            $this->redirect('/admin/dashboard');
        }
        
        $categories = $this->getEventCategoriesFromNewsTable();
        
        $this->data = array_merge($this->data, [
            'categories' => $categories,
            'event' => [
                'id' => 0,
                'title' => '',
                'slug' => '',
                'description' => '',
                'content' => '',
                'category' => '',
                'tags' => '',
                'featured_image' => '',
                'event_date' => date('Y-m-d'),
                'event_end_date' => '',
                'event_time' => '',
                'location' => '',
                'venue' => '',
                'is_published' => 1,
                'is_featured' => 0,
                'registration_link' => '',
                'registration_deadline' => '',
                'max_participants' => '',
                'organizer' => '',
                'contact_email' => '',
                'contact_phone' => '',
                'meta_title' => '',
                'meta_description' => '',
                'meta_keywords' => ''
            ],
            'currentPage' => 'events',
            'csrf_token' => $this->generateCsrfToken()
        ]);
        
        $this->render('admin/events/create');
    }
    
    /**
     * Admin: Store new event - FIXED CSRF VERSION
     */
    public function store() {
        $this->layout = 'admin';
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/admin/events/create');
            return;
        }
        
        require_once __DIR__ . '/../middleware/AuthMiddleware.php';
        AuthMiddleware::authenticate();
        
        try {
            // Validate CSRF token - USING MULTI-TOKEN SYSTEM
            $csrfToken = $_POST['csrf_token'] ?? '';
            
            error_log("CSRF Token from form (store): " . ($csrfToken ? substr($csrfToken, 0, 20) . "..." : "EMPTY"));
            
            require_once APP_PATH . '/config/session.php';
            
            if (!Session::validateCSRFTokenMulti($csrfToken)) {
                error_log("CSRF VALIDATION FAILED in store!");
                $this->setFlash('error', 'Invalid security token. Please try again.');
                $this->redirect('/admin/events/create');
                return;
            }
            
            error_log("CSRF validation passed!");
            
            // Remove the used token to prevent replay attacks
            Session::removeCSRFToken($csrfToken);
            
            $data = [
                'title' => trim($_POST['title'] ?? ''),
                'slug' => $this->generateSlug($_POST['slug'] ?? $_POST['title'] ?? ''),
                'description' => trim($_POST['description'] ?? ''),
                'content' => trim($_POST['content'] ?? ''),
                'category' => $_POST['category'] ?? '',
                'tags' => $_POST['tags'] ?? '',
                'featured_image' => $_POST['featured_image'] ?? '',
                'event_date' => $_POST['event_date'] ?: date('Y-m-d'),
                'event_end_date' => !empty($_POST['event_end_date']) ? $_POST['event_end_date'] : null,
                'event_time' => !empty($_POST['event_time']) ? $_POST['event_time'] : null,
                'location' => $_POST['location'] ?? '',
                'venue' => $_POST['venue'] ?? '',
                'is_published' => isset($_POST['is_published']) ? 1 : 0,
                'is_featured' => isset($_POST['is_featured']) ? 1 : 0,
                'registration_link' => $_POST['registration_link'] ?? '',
                'registration_deadline' => !empty($_POST['registration_deadline']) ? $_POST['registration_deadline'] : null,
                'max_participants' => !empty($_POST['max_participants']) ? (int)$_POST['max_participants'] : null,
                'organizer' => $_POST['organizer'] ?? '',
                'contact_email' => $_POST['contact_email'] ?? '',
                'contact_phone' => $_POST['contact_phone'] ?? '',
                'author_id' => $_SESSION['user_id'] ?? 1,
                'meta_title' => $_POST['meta_title'] ?? '',
                'meta_description' => $_POST['meta_description'] ?? '',
                'meta_keywords' => $_POST['meta_keywords'] ?? '',
                'type' => 'event' // Set type to 'event' for news table
            ];
            
            // Validate required fields
            if (empty($data['title']) || empty($data['content']) || empty($data['event_date'])) {
                throw new Exception('Title, content and event date are required');
            }
            
            $id = $this->createEventInNewsTable($data);
            
            if ($id) {
                // Log activity
                $this->logActivity('event_created', "Created event: {$data['title']}");
                
                $this->setFlash('success', 'Event created successfully!');
                $this->redirect('/admin/events');
            } else {
                throw new Exception('Failed to create event');
            }
            
        } catch (Exception $e) {
            error_log("EventsController store error: " . $e->getMessage());
            
            $categories = $this->getEventCategoriesFromNewsTable();
            $this->data = array_merge($this->data, [
                'categories' => $categories,
                'error' => $e->getMessage(),
                'event' => array_merge(['id' => 0], $_POST),
                'csrf_token' => Session::generateCSRFTokenMulti() // Generate new multi-token
            ]);
            
            $this->render('admin/events/create');
        }
    }

    private function createEventInNewsTable($data) {
        $sql = "INSERT INTO news (
            title, slug, description, content, category, tags, featured_image,
            event_date, event_end_date, event_time, location, venue, is_published,
            is_featured, registration_link, registration_deadline, max_participants,
            organizer, contact_email, contact_phone, author_id, meta_title,
            meta_description, meta_keywords, type, created_at, updated_at
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), NOW())";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            $data['title'],
            $data['slug'],
            $data['description'],
            $data['content'],
            $data['category'],
            $data['tags'],
            $data['featured_image'],
            $data['event_date'],
            $data['event_end_date'],
            $data['event_time'],
            $data['location'],
            $data['venue'],
            $data['is_published'],
            $data['is_featured'],
            $data['registration_link'],
            $data['registration_deadline'],
            $data['max_participants'],
            $data['organizer'],
            $data['contact_email'],
            $data['contact_phone'],
            $data['author_id'],
            $data['meta_title'],
            $data['meta_description'],
            $data['meta_keywords'],
            $data['type']
        ]);
        
        return $this->db->lastInsertId();
    }
    
    /**
     * Admin: Show edit event form
     */
    public function edit($id) {
        $this->layout = 'admin';
        
        require_once __DIR__ . '/../middleware/AuthMiddleware.php';
        AuthMiddleware::authenticate();
        
        $userRole = $_SESSION['user_role'] ?? '';
        if (!in_array($userRole, ['admin', 'editor'])) {
            $this->redirect('/admin/dashboard');
        }
        
        try {
            $event = $this->getEventByIdFromNewsTable($id);
            
            if (!$event) {
                throw new Exception('Event not found');
            }
            
            $categories = $this->getEventCategoriesFromNewsTable();
            
            $this->data = array_merge($this->data, [
                'event' => $event,
                'categories' => $categories,
                'currentPage' => 'events',
                'csrf_token' => $this->generateCsrfToken()
            ]);
            
            $this->render('admin/events/edit');
            
        } catch (Exception $e) {
            error_log("EventsController edit error: " . $e->getMessage());
            $this->setFlash('error', $e->getMessage());
            $this->redirect('/admin/events');
        }
    }

    private function getEventByIdFromNewsTable($id) {
        $sql = "SELECT n.*, u.username as author_name 
                FROM news n 
                LEFT JOIN users u ON n.author_id = u.id 
                WHERE n.id = ? AND n.type = 'event'";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    /**
     * Admin: Update event - FIXED CSRF VERSION
     */
    public function update($id) {
        $this->layout = 'admin';
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect("/admin/events/{$id}/edit");
            return;
        }
        
        require_once __DIR__ . '/../middleware/AuthMiddleware.php';
        AuthMiddleware::authenticate();
        
        try {
            // Validate CSRF token - USING MULTI-TOKEN SYSTEM
            $csrfToken = $_POST['csrf_token'] ?? '';
            
            error_log("CSRF Token from form (update): " . ($csrfToken ? substr($csrfToken, 0, 20) . "..." : "EMPTY"));
            
            require_once APP_PATH . '/config/session.php';
            
            if (!Session::validateCSRFTokenMulti($csrfToken)) {
                error_log("CSRF VALIDATION FAILED in update!");
                $this->setFlash('error', 'Invalid security token. Please try again.');
                $this->redirect("/admin/events/{$id}/edit");
                return;
            }
            
            error_log("CSRF validation passed!");
            
            // Remove the used token to prevent replay attacks
            Session::removeCSRFToken($csrfToken);
            
            $data = [
                'title' => trim($_POST['title'] ?? ''),
                'slug' => $this->generateSlug($_POST['slug'] ?? $_POST['title'] ?? '', $id),
                'description' => trim($_POST['description'] ?? ''),
                'content' => trim($_POST['content'] ?? ''),
                'category' => $_POST['category'] ?? '',
                'tags' => $_POST['tags'] ?? '',
                'featured_image' => $_POST['featured_image'] ?? '',
                'event_date' => $_POST['event_date'] ?: date('Y-m-d'),
                'event_end_date' => !empty($_POST['event_end_date']) ? $_POST['event_end_date'] : null,
                'event_time' => !empty($_POST['event_time']) ? $_POST['event_time'] : null,
                'location' => $_POST['location'] ?? '',
                'venue' => $_POST['venue'] ?? '',
                'is_published' => isset($_POST['is_published']) ? 1 : 0,
                'is_featured' => isset($_POST['is_featured']) ? 1 : 0,
                'registration_link' => $_POST['registration_link'] ?? '',
                'registration_deadline' => !empty($_POST['registration_deadline']) ? $_POST['registration_deadline'] : null,
                'max_participants' => !empty($_POST['max_participants']) ? (int)$_POST['max_participants'] : null,
                'organizer' => $_POST['organizer'] ?? '',
                'contact_email' => $_POST['contact_email'] ?? '',
                'contact_phone' => $_POST['contact_phone'] ?? '',
                'meta_title' => $_POST['meta_title'] ?? '',
                'meta_description' => $_POST['meta_description'] ?? '',
                'meta_keywords' => $_POST['meta_keywords'] ?? ''
            ];
            
            if (empty($data['title']) || empty($data['content']) || empty($data['event_date'])) {
                throw new Exception('Title, content and event date are required');
            }
            
            $success = $this->updateEventInNewsTable($id, $data);
            
            if ($success) {
                $this->logActivity('event_updated', "Updated event #{$id}: {$data['title']}");
                $this->setFlash('success', 'Event updated successfully!');
                $this->redirect('/admin/events');
            } else {
                throw new Exception('Failed to update event');
            }
            
        } catch (Exception $e) {
            error_log("EventsController update error: " . $e->getMessage());
            
            $categories = $this->getEventCategoriesFromNewsTable();
            $this->data = array_merge($this->data, [
                'categories' => $categories,
                'error' => $e->getMessage(),
                'event' => array_merge(['id' => $id], $_POST),
                'csrf_token' => Session::generateCSRFTokenMulti() // Generate new multi-token
            ]);
            
            $this->render('admin/events/edit');
        }
    }

    private function updateEventInNewsTable($id, $data) {
        $sql = "UPDATE news SET
            title = ?, slug = ?, description = ?, content = ?, category = ?, tags = ?,
            featured_image = ?, event_date = ?, event_end_date = ?, event_time = ?,
            location = ?, venue = ?, is_published = ?, is_featured = ?,
            registration_link = ?, registration_deadline = ?, max_participants = ?,
            organizer = ?, contact_email = ?, contact_phone = ?, meta_title = ?,
            meta_description = ?, meta_keywords = ?, updated_at = NOW()
            WHERE id = ? AND type = 'event'";
        
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            $data['title'],
            $data['slug'],
            $data['description'],
            $data['content'],
            $data['category'],
            $data['tags'],
            $data['featured_image'],
            $data['event_date'],
            $data['event_end_date'],
            $data['event_time'],
            $data['location'],
            $data['venue'],
            $data['is_published'],
            $data['is_featured'],
            $data['registration_link'],
            $data['registration_deadline'],
            $data['max_participants'],
            $data['organizer'],
            $data['contact_email'],
            $data['contact_phone'],
            $data['meta_title'],
            $data['meta_description'],
            $data['meta_keywords'],
            $id
        ]);
    }
    
    /**
     * Admin: Show event details
     */
    public function show($id) {
        $this->layout = 'admin';
        
        require_once __DIR__ . '/../middleware/AuthMiddleware.php';
        AuthMiddleware::authenticate();
        
        try {
            $event = $this->getEventByIdFromNewsTable($id);
            
            if (!$event) {
                throw new Exception('Event not found');
            }
            
            // Get registrations (if using separate registrations table)
            $registrations = $this->getEventRegistrations($id);
            
            $this->data = array_merge($this->data, [
                'event' => $event,
                'registrations' => $registrations ?? [],
                'currentPage' => 'events'
            ]);
            
            $this->render('admin/events/show');
            
        } catch (Exception $e) {
            error_log("EventsController show error: " . $e->getMessage());
            $this->setFlash('error', $e->getMessage());
            $this->redirect('/admin/events');
        }
    }

    private function getEventRegistrations($eventId) {
        try {
            // Check if event_registrations table exists
            $sql = "SELECT * FROM event_registrations WHERE event_id = ? ORDER BY created_at DESC";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$eventId]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            error_log("No registrations table found for event $eventId: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Admin: Delete event - FIXED CSRF VERSION
     */
    public function destroy($id) {
        $this->layout = 'admin';
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/admin/events');
            return;
        }
        
        require_once __DIR__ . '/../middleware/AuthMiddleware.php';
        AuthMiddleware::authenticate();
        
        try {
            // Validate CSRF token - USING MULTI-TOKEN SYSTEM
            $csrfToken = $_POST['csrf_token'] ?? '';
            
            error_log("CSRF Token from form (destroy): " . ($csrfToken ? substr($csrfToken, 0, 20) . "..." : "EMPTY"));
            
            require_once APP_PATH . '/config/session.php';
            
            if (!Session::validateCSRFTokenMulti($csrfToken)) {
                error_log("CSRF VALIDATION FAILED in destroy!");
                $this->setFlash('error', 'Invalid security token. Please try again.');
                $this->redirect('/admin/events');
                return;
            }
            
            // Remove the used token to prevent replay attacks
            Session::removeCSRFToken($csrfToken);
            
            $event = $this->getEventByIdFromNewsTable($id);
            
            if (!$event) {
                throw new Exception('Event not found');
            }
            
            $success = $this->deleteEventFromNewsTable($id);
            
            if ($success) {
                $this->logActivity('event_deleted', "Deleted event #{$id}: {$event['title']}");
                $this->setFlash('success', 'Event deleted successfully!');
            } else {
                throw new Exception('Failed to delete event');
            }
            
        } catch (Exception $e) {
            error_log("EventsController destroy error: " . $e->getMessage());
            $this->setFlash('error', $e->getMessage());
        }
        
        $this->redirect('/admin/events');
    }

    private function deleteEventFromNewsTable($id) {
        $sql = "DELETE FROM news WHERE id = ? AND type = 'event'";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$id]);
    }
    
    /**
     * Admin: View event registrations
     */
    public function registrations($id) {
        $this->layout = 'admin';
        
        require_once __DIR__ . '/../middleware/AuthMiddleware.php';
        AuthMiddleware::authenticate();
        
        try {
            $event = $this->getEventByIdFromNewsTable($id);
            
            if (!$event) {
                throw new Exception('Event not found');
            }
            
            $registrations = $this->getEventRegistrations($id);
            
            $this->data = array_merge($this->data, [
                'event' => $event,
                'registrations' => $registrations,
                'currentPage' => 'events',
                'csrf_token' => $this->generateCsrfToken()
            ]);
            
            $this->render('admin/events/registrations');
            
        } catch (Exception $e) {
            error_log("EventsController registrations error: " . $e->getMessage());
            $this->setFlash('error', $e->getMessage());
            $this->redirect('/admin/events');
        }
    }
    
    /**
     * Public: List all events
     */
    public function publicIndex() {
        $this->layout = 'public';
        
        try {
            // Get pagination
            $page = max(1, (int)($_GET['page'] ?? 1));
            $limit = 12;
            $offset = ($page - 1) * $limit;
            
            // Get upcoming events from news table (type = 'event')
            $events = $this->getUpcomingEventsFromNewsTable($limit, $offset);
            
            // Count upcoming events
            $total = $this->countUpcomingEventsFromNewsTable();
            
            $totalPages = ceil($total / $limit);
            
            // Get featured events
            $featuredEvents = $this->getFeaturedEvents(3);
            
            // Get event categories
            $eventCategories = $this->getEventCategoriesWithCount();
            
            $this->data = array_merge($this->data, [
                'events' => $events,
                'featuredEvents' => $featuredEvents,
                'eventCategories' => $eventCategories,
                'pagination' => [
                    'current' => $page,
                    'total' => $totalPages,
                    'limit' => $limit,
                    'totalCount' => $total
                ],
                'pageTitle' => 'Events - FCT College of Nursing Sciences',
                'pageDescription' => 'Upcoming events, seminars, and workshops at FCT College of Nursing Sciences',
                'csrf_token' => $this->generateCsrfToken()
            ]);
            
            $this->render('pages/events/index');
            
        } catch (Exception $e) {
            error_log("EventsController publicIndex error: " . $e->getMessage());
            $this->data = array_merge($this->data, [
                'pageTitle' => 'Events - Error',
                'pageDescription' => 'Error loading events'
            ]);
            $this->render('pages/events/index', ['events' => [], 'featuredEvents' => [], 'eventCategories' => []]);
        }
    }

    private function getUpcomingEventsFromNewsTable($limit = 12, $offset = 0) {
        $sql = "SELECT n.*, u.username as author_name 
                FROM news n 
                LEFT JOIN users u ON n.author_id = u.id 
                WHERE n.is_published = 1 AND n.type = 'event'
                AND (n.event_date >= CURDATE() OR n.event_date IS NULL)
                ORDER BY n.event_date ASC, n.created_at DESC 
                LIMIT ? OFFSET ?";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$limit, $offset]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    private function countUpcomingEventsFromNewsTable() {
        $sql = "SELECT COUNT(*) as total 
                FROM news 
                WHERE is_published = 1 AND type = 'event'
                AND (event_date >= CURDATE() OR event_date IS NULL)";
        
        $stmt = $this->db->query($sql);
        $result = $stmt->fetch();
        return $result['total'] ?? 0;
    }

    private function getFeaturedEvents($limit = 3) {
        $sql = "SELECT n.*, u.username as author_name 
                FROM news n 
                LEFT JOIN users u ON n.author_id = u.id 
                WHERE n.is_published = 1 AND n.is_featured = 1 AND n.type = 'event'
                ORDER BY n.event_date ASC, n.created_at DESC 
                LIMIT ?";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$limit]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    private function getEventCategoriesWithCount() {
        $sql = "SELECT category, COUNT(*) as count 
                FROM news 
                WHERE is_published = 1 AND type = 'event' AND category IS NOT NULL AND category != ''
                GROUP BY category 
                ORDER BY count DESC, category";
        
        $stmt = $this->db->query($sql);
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        $categories = [];
        foreach ($results as $row) {
            $categories[$row['category']] = $row['count'];
        }
        
        return $categories;
    }
    
    /**
     * Public: Show single event
     */
    public function publicShow($slug) {
        $this->layout = 'public';
        
        try {
            $event = $this->getEventBySlugFromNewsTable($slug);
            
            if (!$event) {
                throw new Exception('Event not found');
            }
            
            // Increment views
            $this->incrementEventViews($event['id']);
            
            // Get related events
            $relatedEvents = $this->getRelatedEvents($event['id'], $event['category'] ?? '', 3);
            
            // Get featured events
            $featuredEvents = $this->getFeaturedEvents(3);
            
            // Check if registration is open
            $registrationOpen = true;
            if (!empty($event['registration_deadline']) && strtotime($event['registration_deadline']) < time()) {
                $registrationOpen = false;
            }
            
            $this->data = array_merge($this->data, [
                'event' => $event,
                'relatedEvents' => $relatedEvents,
                'featuredEvents' => $featuredEvents,
                'registrationOpen' => $registrationOpen,
                'pageTitle' => $event['title'] . ' - FCT College of Nursing Sciences',
                'pageDescription' => $event['description'] ?: substr(strip_tags($event['content'] ?? ''), 0, 150) . '...',
                'csrf_token' => $this->generateCsrfToken()
            ]);
            
            $this->render('pages/events/show');
            
        } catch (Exception $e) {
            error_log("EventsController publicShow error: " . $e->getMessage());
            $this->data = array_merge($this->data, [
                'pageTitle' => 'Event Not Found',
                'pageDescription' => 'The requested event could not be found'
            ]);
            $this->render('pages/404');
        }
    }

    private function getEventBySlugFromNewsTable($slug) {
        $sql = "SELECT n.*, u.username as author_name 
                FROM news n 
                LEFT JOIN users u ON n.author_id = u.id 
                WHERE n.slug = ? AND n.type = 'event' AND n.is_published = 1";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$slug]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    private function incrementEventViews($id) {
        $sql = "UPDATE news SET views_count = COALESCE(views_count, 0) + 1, updated_at = NOW() WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id]);
    }

    private function getRelatedEvents($eventId, $category, $limit = 3) {
        $sql = "SELECT n.*, u.username as author_name 
                FROM news n 
                LEFT JOIN users u ON n.author_id = u.id 
                WHERE n.is_published = 1 AND n.type = 'event' AND n.id != ?";
        
        $params = [$eventId];
        
        if (!empty($category)) {
            $sql .= " AND n.category = ?";
            $params[] = $category;
        }
        
        $sql .= " ORDER BY n.event_date ASC LIMIT ?";
        $params[] = $limit;
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    /**
     * Public: Register for event - FIXED CSRF VERSION
     */
    public function publicRegister($id) {
        $this->layout = 'public';
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect("/events/{$id}");
            return;
        }
        
        try {
            // Validate CSRF token - USING MULTI-TOKEN SYSTEM
            $csrfToken = $_POST['csrf_token'] ?? '';
            
            error_log("CSRF Token from form (publicRegister): " . ($csrfToken ? substr($csrfToken, 0, 20) . "..." : "EMPTY"));
            
            require_once APP_PATH . '/config/session.php';
            
            if (!Session::validateCSRFTokenMulti($csrfToken)) {
                error_log("CSRF VALIDATION FAILED in publicRegister!");
                $this->setFlash('error', 'Invalid security token. Please try again.');
                $this->redirect("/events/{$id}");
                return;
            }
            
            // Remove the used token to prevent replay attacks
            Session::removeCSRFToken($csrfToken);
            
            $event = $this->getEventByIdFromNewsTable($id);
            
            if (!$event || !($event['is_published'] ?? false)) {
                throw new Exception('Event not found or not available');
            }
            
            // Check registration availability
            if (!empty($event['registration_deadline']) && strtotime($event['registration_deadline']) < time()) {
                throw new Exception('Registration deadline has passed');
            }
            
            $data = [
                'event_id' => $id,
                'name' => trim($_POST['name'] ?? ''),
                'email' => trim($_POST['email'] ?? ''),
                'phone' => trim($_POST['phone'] ?? ''),
                'institution' => trim($_POST['institution'] ?? ''),
                'position' => trim($_POST['position'] ?? ''),
                'additional_info' => trim($_POST['additional_info'] ?? '')
            ];
            
            if (empty($data['name']) || empty($data['email'])) {
                throw new Exception('Name and email are required');
            }
            
            $registrationId = $this->registerForEvent($data);
            
            if ($registrationId) {
                $this->setFlash('success', 'Registration successful! You will receive a confirmation email shortly.');
                $this->redirect("/events/{$event['slug']}");
            } else {
                throw new Exception('Failed to register for event');
            }
            
        } catch (Exception $e) {
            error_log("EventsController publicRegister error: " . $e->getMessage());
            $this->setFlash('error', $e->getMessage());
            $this->redirect("/events/{$id}");
        }
    }

    private function registerForEvent($data) {
        try {
            // Check if event_registrations table exists
            $sql = "INSERT INTO event_registrations 
                    (event_id, name, email, phone, institution, position, additional_info, created_at)
                    VALUES (?, ?, ?, ?, ?, ?, ?, NOW())";
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute([
                $data['event_id'],
                $data['name'],
                $data['email'],
                $data['phone'],
                $data['institution'],
                $data['position'],
                $data['additional_info']
            ]);
            
            return $this->db->lastInsertId();
        } catch (Exception $e) {
            error_log("Event registration failed: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Public: Events calendar
     */
    public function calendar() {
        $this->layout = 'public';
        
        try {
            // Get events for calendar from news table
            $events = $this->getCalendarEventsFromNewsTable();
            
            $this->data = array_merge($this->data, [
                'events' => $events,
                'pageTitle' => 'Events Calendar - FCT College of Nursing Sciences',
                'pageDescription' => 'Browse upcoming events on our calendar',
                'csrf_token' => $this->generateCsrfToken()
            ]);
            
            $this->render('pages/events/calendar');
            
        } catch (Exception $e) {
            error_log("EventsController calendar error: " . $e->getMessage());
            $this->data = array_merge($this->data, [
                'pageTitle' => 'Calendar - Error',
                'pageDescription' => 'Error loading calendar'
            ]);
            $this->render('pages/events/calendar', ['events' => []]);
        }
    }

    private function getCalendarEventsFromNewsTable() {
        $sql = "SELECT id, title, slug, event_date, event_end_date, event_time, location, venue
                FROM news 
                WHERE type = 'event' AND is_published = 1
                AND (event_date >= CURDATE() OR event_date IS NULL)
                ORDER BY event_date ASC";
        
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    /**
     * Generate slug
     */
    private function generateSlug($text, $excludeId = null) {
        if (empty($text)) return '';
        
        $slug = strtolower(trim($text));
        $slug = preg_replace('/[^a-z0-9-]/', '-', $slug);
        $slug = preg_replace('/-+/', '-', $slug);
        $slug = trim($slug, '-');
        
        // Check if slug exists
        $counter = 1;
        $originalSlug = $slug;
        
        while ($this->slugExistsInNewsTable($slug, $excludeId)) {
            $slug = $originalSlug . '-' . $counter;
            $counter++;
        }
        
        return $slug;
    }

    private function slugExistsInNewsTable($slug, $excludeId = null) {
        $sql = "SELECT COUNT(*) as count FROM news WHERE slug = ? AND type = 'event'";
        $params = [$slug];
        
        if ($excludeId) {
            $sql .= " AND id != ?";
            $params[] = $excludeId;
        }
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        $result = $stmt->fetch();
        return ($result['count'] ?? 0) > 0;
    }
    
    /**
     * Set flash message
     */
    private function setFlash($type, $message) {
        if (!isset($_SESSION['flash_messages'])) {
            $_SESSION['flash_messages'] = [];
        }
        $_SESSION['flash_messages'][$type] = $message;
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
     * Bulk actions for events - FIXED CSRF VERSION
     */
    public function bulkAction() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/admin/events');
            return;
        }
        
        $this->layout = 'admin';
        
        require_once __DIR__ . '/../middleware/AuthMiddleware.php';
        AuthMiddleware::authenticate();
        
        try {
            // Validate CSRF token - USING MULTI-TOKEN SYSTEM
            $csrfToken = $_POST['csrf_token'] ?? '';
            
            error_log("CSRF Token from form (bulkAction): " . ($csrfToken ? substr($csrfToken, 0, 20) . "..." : "EMPTY"));
            
            require_once APP_PATH . '/config/session.php';
            
            if (!Session::validateCSRFTokenMulti($csrfToken)) {
                error_log("CSRF VALIDATION FAILED in bulkAction!");
                $this->setFlash('error', 'Invalid security token. Please try again.');
                $this->redirect('/admin/events');
                return;
            }
            
            // Remove the used token to prevent replay attacks
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
                
                $event = $this->getEventByIdFromNewsTable($id);
                if (!$event) continue;
                
                switch ($action) {
                    case 'publish':
                        $success = $this->updateEventInNewsTable($id, ['is_published' => 1]);
                        break;
                    case 'unpublish':
                        $success = $this->updateEventInNewsTable($id, ['is_published' => 0]);
                        break;
                    case 'feature':
                        $success = $this->updateEventInNewsTable($id, ['is_featured' => 1]);
                        break;
                    case 'unfeature':
                        $success = $this->updateEventInNewsTable($id, ['is_featured' => 0]);
                        break;
                    case 'delete':
                        $success = $this->deleteEventFromNewsTable($id);
                        break;
                    default:
                        $success = false;
                }
                
                if ($success) $successCount++;
            }
            
            $this->setFlash('success', "Bulk action completed. $successCount item(s) updated.");
            
        } catch (Exception $e) {
            error_log("EventsController bulkAction error: " . $e->getMessage());
            $this->setFlash('error', 'Error: ' . $e->getMessage());
        }
        
        $this->redirect('/admin/events');
    }
    
    /**
     * Export events to CSV
     */
    public function export() {
        $this->layout = null; // No layout for CSV export
        
        require_once __DIR__ . '/../middleware/AuthMiddleware.php';
        AuthMiddleware::authenticate();
        
        $userRole = $_SESSION['user_role'] ?? '';
        if (!in_array($userRole, ['admin', 'editor'])) {
            $this->redirect('/admin/dashboard');
        }
        
        try {
            // Get all events from news table
            $events = $this->getAllEventsFromNewsTable([], 1000, 0);
            
            // Set headers for CSV download
            header('Content-Type: text/csv; charset=utf-8');
            header('Content-Disposition: attachment; filename=events_' . date('Y-m-d') . '.csv');
            
            $output = fopen('php://output', 'w');
            
            // Add CSV headers
            fputcsv($output, [
                'ID', 'Title', 'Slug', 'Event Date', 'Event End Date', 'Event Time', 'Location', 
                'Venue', 'Category', 'Status', 'Featured', 'Organizer', 'Contact Email', 
                'Contact Phone', 'Registration Link', 'Registration Deadline', 'Max Participants',
                'Views', 'Created At', 'Updated At'
            ]);
            
            // Add data rows
            foreach ($events as $event) {
                fputcsv($output, [
                    $event['id'],
                    $event['title'],
                    $event['slug'],
                    $event['event_date'],
                    $event['event_end_date'] ?? '',
                    $event['event_time'] ?? '',
                    $event['location'] ?? '',
                    $event['venue'] ?? '',
                    $event['category'] ?? '',
                    $event['is_published'] ? 'Published' : 'Draft',
                    $event['is_featured'] ? 'Yes' : 'No',
                    $event['organizer'] ?? '',
                    $event['contact_email'] ?? '',
                    $event['contact_phone'] ?? '',
                    $event['registration_link'] ?? '',
                    $event['registration_deadline'] ?? '',
                    $event['max_participants'] ?? '',
                    $event['views_count'] ?? 0,
                    $event['created_at'],
                    $event['updated_at'] ?? ''
                ]);
            }
            
            fclose($output);
            exit;
            
        } catch (Exception $e) {
            error_log("EventsController export error: " . $e->getMessage());
            $this->setFlash('error', 'Failed to export events: ' . $e->getMessage());
            $this->redirect('/admin/events');
        }
    }
    
    /**
     * Generate CSRF token - UPDATED for multi-token system
     */
    private function generateCsrfToken() {
        require_once APP_PATH . '/config/session.php';
        return Session::generateCSRFTokenMulti();
    }
    
    /**
     * Validate CSRF token - UPDATED for multi-token system
     */
    protected function validateCsrf() {
        $token = $_POST['csrf_token'] ?? '';
        require_once APP_PATH . '/config/session.php';
        return Session::validateCSRFTokenMulti($token);
    }
}