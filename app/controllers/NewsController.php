<?php
/**
 * News Controller
 * Handles news management operations
 * Extends the base Controller class for common functionality
 */
class NewsController extends Controller {
    
    private $db;
    private $newsModel;
    
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
        
        // Initialize NewsModel
        require_once APP_PATH . '/models/NewsModel.php';
        $this->newsModel = new NewsModel();
        
        // Initialize common data
        $this->data = array_merge($this->data, [
            'user' => $_SESSION ?? [],
            'baseUrl' => defined('BASE_URL') ? BASE_URL : '',
            'currentPage' => 'news'
        ]);
    }
    
    // ============================================
    // ADMIN METHODS (require auth)
    // ============================================
    
    /**
     * Display all news articles with filters and stats
     */
    public function index() {
        try {
            // Get filter parameters
            $filters = [
                'status' => $this->input('status', ''),
                'category' => $this->input('category', ''),
                'search' => $this->input('search', ''),
                'date_from' => $this->input('date_from', ''),
                'date_to' => $this->input('date_to', ''),
                'author_id' => $this->input('author_id', '')
            ];
            
            // Get page parameters
            $page = max(1, (int)$this->input('page', 1));
            $limit = 20;
            $offset = ($page - 1) * $limit;
            
            // Get news articles using NewsModel
            $news = $this->newsModel->getAll($filters, $limit, $offset);
            
            // Get total count for pagination
            $totalCount = $this->getNewsCount($filters);
            $totalPages = ceil($totalCount / $limit);
            
            // Get statistics using NewsModel
            $stats = $this->newsModel->getStats();
            
            // Get categories for filter dropdown
            $categories = $this->newsModel->getCategories();
            
            // Get authors for filter dropdown
            $authors = $this->getAuthors();
            
            // Set data for view
            $this->data = array_merge($this->data, [
                'news' => $news,
                'stats' => $stats,
                'categories' => $categories,
                'authors' => $authors,
                'filters' => $filters,
                'pagination' => [
                    'current' => $page,
                    'total' => $totalPages,
                    'limit' => $limit,
                    'totalCount' => $totalCount
                ],
                'pageTitle' => 'News Management - FCT College of Nursing Sciences',
                'pageDescription' => 'Manage news articles and announcements'
            ]);
            
            // Render view using news_admin layout
            $this->renderWithNewsLayout('admin/news/index');
            
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
        $categories = $this->newsModel->getCategories();
        
        // Set data for view
        $this->data = array_merge($this->data, [
            'categories' => $categories,
            'pageTitle' => 'Create News Article - FCT College of Nursing Sciences',
            'pageDescription' => 'Create a new news article'
        ]);
        
        // Render view using news_admin layout
        $this->renderWithNewsLayout('admin/news/create');
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
            
            // Prepare data for NewsModel
            $data = [
                'title' => $this->input('title', ''),
                'slug' => $this->input('slug', ''),
                'excerpt' => $this->input('excerpt', ''),
                'content' => $this->input('content', ''),
                'category' => $this->input('category', ''),
                'tags' => $this->input('tags', ''),
                'featured_image' => $this->input('featured_image', ''),
                'is_published' => $this->input('is_published', 0) ? 1 : 0,
                'is_featured' => $this->input('is_featured', 0) ? 1 : 0,
                'is_breaking' => $this->input('is_breaking', 0) ? 1 : 0,
                'meta_title' => $this->input('meta_title', ''),
                'meta_description' => $this->input('meta_description', ''),
                'meta_keywords' => $this->input('meta_keywords', '')
            ];
            
            // Validate
            if (empty($data['title']) || empty($data['content'])) {
                throw new Exception("Title and content are required.");
            }
            
            // Use NewsModel to create
            $newNewsId = $this->newsModel->create($data);
            
            // Log activity
            $this->logActivity('news_created', "News article '{$data['title']}' created");
            
            // Set success message
            $this->flash('success', 'News article created successfully!');
            
            // Redirect to news list
            $this->redirect('/admin/news');
            
        } catch (Exception $e) {
            error_log("NewsController store error: " . $e->getMessage());
            
            // Get categories for form
            $categories = $this->newsModel->getCategories();
            
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
                    'is_breaking' => $this->input('is_breaking', 0),
                    'meta_title' => $this->input('meta_title', ''),
                    'meta_description' => $this->input('meta_description', ''),
                    'meta_keywords' => $this->input('meta_keywords', '')
                ],
                'pageTitle' => 'Create News Article - FCT College of Nursing Sciences',
                'pageDescription' => 'Create a new news article'
            ]);
            
            $this->renderWithNewsLayout('admin/news/create');
        }
    }
    
    /**
     * Display single news article
     */
    public function show($id) {
        try {
            // Use NewsModel to get news
            $news = $this->newsModel->getById($id);
            
            if (!$news) {
                $this->flash('error', 'News article not found.');
                $this->redirect('/admin/news');
                return;
            }
            
            // Get related news using NewsModel
            $relatedNews = $this->newsModel->getRelated($id, 3);
            
            // Set data for view
            $this->data = array_merge($this->data, [
                'news' => $news,
                'relatedNews' => $relatedNews,
                'pageTitle' => $news['title'] . ' - FCT College of Nursing Sciences',
                'pageDescription' => $news['excerpt'] ?: substr(strip_tags($news['content']), 0, 150) . '...'
            ]);
            
            $this->renderWithNewsLayout('admin/news/show');
            
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
            // Use NewsModel to get news
            $news = $this->newsModel->getById($id);
            
            if (!$news) {
                $this->flash('error', 'News article not found.');
                $this->redirect('/admin/news');
                return;
            }
            
            // Get categories
            $categories = $this->newsModel->getCategories();
            
            // Set data for view
            $this->data = array_merge($this->data, [
                'news' => $news,
                'categories' => $categories,
                'pageTitle' => 'Edit News Article - ' . $news['title'],
                'pageDescription' => 'Edit news article'
            ]);
            
            $this->renderWithNewsLayout('admin/news/edit');
            
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
            
            // Prepare data for NewsModel
            $data = [
                'title' => $this->input('title', ''),
                'slug' => $this->input('slug', ''),
                'excerpt' => $this->input('excerpt', ''),
                'content' => $this->input('content', ''),
                'category' => $this->input('category', ''),
                'tags' => $this->input('tags', ''),
                'featured_image' => $this->input('featured_image', ''),
                'is_published' => $this->input('is_published', 0) ? 1 : 0,
                'is_featured' => $this->input('is_featured', 0) ? 1 : 0,
                'is_breaking' => $this->input('is_breaking', 0) ? 1 : 0,
                'meta_title' => $this->input('meta_title', ''),
                'meta_description' => $this->input('meta_description', ''),
                'meta_keywords' => $this->input('meta_keywords', '')
            ];
            
            // Validate
            if (empty($data['title']) || empty($data['content'])) {
                throw new Exception("Title and content are required.");
            }
            
            // Use NewsModel to update
            $result = $this->newsModel->update($id, $data);
            
            if (!$result) {
                throw new Exception("Failed to update news article.");
            }
            
            // Log activity
            $this->logActivity('news_updated', "News article #{$id} '{$data['title']}' updated");
            
            // Set success message
            $this->flash('success', 'News article updated successfully!');
            
            // Redirect to news list
            $this->redirect('/admin/news');
            
        } catch (Exception $e) {
            error_log("NewsController update error: " . $e->getMessage());
            
            // Get news data for the form
            try {
                $news = $this->newsModel->getById($id);
                
                // Get categories
                $categories = $this->newsModel->getCategories();
                
                $this->data = array_merge($this->data, [
                    'news' => $news,
                    'categories' => $categories,
                    'error' => $e->getMessage(),
                    'pageTitle' => 'Edit News Article - ' . ($news['title'] ?? 'Unknown'),
                    'pageDescription' => 'Edit news article'
                ]);
                
                $this->renderWithNewsLayout('admin/news/edit');
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
            $news = $this->newsModel->getById($id);
            
            if (!$news) {
                throw new Exception("News article not found.");
            }
            
            // Delete news article using NewsModel
            $result = $this->newsModel->delete($id);
            
            if (!$result) {
                throw new Exception("Failed to delete news article.");
            }
            
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
     * Toggle news article status (publish/featured/breaking)
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
            
            if (!in_array($field, ['is_published', 'is_featured', 'is_breaking'])) {
                throw new Exception("Invalid field specified.");
            }
            
            // Use NewsModel to toggle status
            $result = $this->newsModel->toggleStatus($id, $field);
            
            if (!$result) {
                throw new Exception("Failed to update status.");
            }
            
            $fieldName = str_replace('is_', '', $field);
            $news = $this->newsModel->getById($id);
            $status = $news[$field] ? 'enabled' : 'disabled';
            
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
            
            $count = 0;
            
            // Process each news article
            foreach ($newsIds as $newsId) {
                switch ($action) {
                    case 'publish':
                        $this->newsModel->update($newsId, ['is_published' => 1]);
                        break;
                    case 'unpublish':
                        $this->newsModel->update($newsId, ['is_published' => 0]);
                        break;
                    case 'feature':
                        $this->newsModel->update($newsId, ['is_featured' => 1]);
                        break;
                    case 'unfeature':
                        $this->newsModel->update($newsId, ['is_featured' => 0]);
                        break;
                    case 'delete':
                        $this->newsModel->delete($newsId);
                        break;
                    default:
                        throw new Exception("Invalid action specified.");
                }
                $count++;
            }
            
            // Log activity
            $this->logActivity('news_bulk_action', "Bulk action '{$action}' performed on {$count} news articles");
            
            $this->flash('success', "Bulk action completed successfully on {$count} article(s)!");
            
        } catch (Exception $e) {
            error_log("NewsController bulkAction error: " . $e->getMessage());
            $this->flash('error', 'Failed to perform bulk action: ' . $e->getMessage());
        }

        $this->redirect('/admin/news');
    }
    
    /**
     * Search news articles (admin)
     */
    public function search() {
        try {
            // Get search parameters
            $query = $this->input('q', '');
            $filters = [
                'category' => $this->input('category', ''),
                'status' => $this->input('status', ''),
                'author_id' => $this->input('author_id', ''),
                'date_from' => $this->input('date_from', ''),
                'date_to' => $this->input('date_to', '')
            ];
            
            // Perform search using NewsModel
            $results = $this->newsModel->search($query, $filters);
            
            // Get categories for filter dropdown
            $categories = $this->newsModel->getCategories();
            
            // Get authors for filter dropdown
            $authors = $this->getAuthors();
            
            // Set data for view
            $this->data = array_merge($this->data, [
                'results' => $results,
                'query' => $query,
                'filters' => $filters,
                'categories' => $categories,
                'authors' => $authors,
                'pageTitle' => 'Search News - FCT College of Nursing Sciences',
                'pageDescription' => 'Search news articles'
            ]);
            
            $this->renderWithNewsLayout('admin/news/search');
            
        } catch (Exception $e) {
            error_log("NewsController search error: " . $e->getMessage());
            $this->showError("Failed to search news articles.");
        }
    }
    
    /**
     * Export news to CSV
     */
    public function export() {
        try {
            // Get filter parameters
            $filters = [
                'status' => $this->input('status', ''),
                'category' => $this->input('category', ''),
                'date_from' => $this->input('date_from', ''),
                'date_to' => $this->input('date_to', '')
            ];
            
            // Get CSV content using NewsModel
            $csvContent = $this->newsModel->exportToCSV($filters);
            
            if (!$csvContent) {
                throw new Exception("No data to export.");
            }
            
            // Set headers for CSV download
            header('Content-Type: text/csv; charset=utf-8');
            header('Content-Disposition: attachment; filename=news_export_' . date('Y-m-d_H-i-s') . '.csv');
            header('Pragma: no-cache');
            header('Expires: 0');
            
            // Output CSV content
            echo $csvContent;
            exit;
            
        } catch (Exception $e) {
            error_log("NewsController export error: " . $e->getMessage());
            $this->flash('error', 'Failed to export news articles: ' . $e->getMessage());
            $this->redirect('/admin/news');
        }
    }
    
    // ============================================
    // PUBLIC METHODS (no auth required)
    // ============================================
    
    /**
     * Public news listing
     */
    public function publicIndex() {
        // Don't require authentication for public methods
        $this->layout = 'public';
        
        try {
            // Get page parameters
            $page = max(1, (int)$this->input('page', 1));
            $limit = 10;
            $offset = ($page - 1) * $limit;
            
            // Get published news using NewsModel
            $news = $this->newsModel->getPublished($limit, $offset);
            
            // Get total count for pagination
            $totalCount = $this->getPublishedCount();
            $totalPages = ceil($totalCount / $limit);
            
            // Get featured news for sidebar
            $featuredNews = $this->newsModel->getFeatured(3);
            
            // Get categories with counts for sidebar
            $categoryCounts = $this->newsModel->getCategoryCounts();
            
            // Get archive months for sidebar
            $archiveMonths = $this->newsModel->getArchiveMonths();
            
            // Get popular news for sidebar
            $popularNews = $this->newsModel->getPopularNews(5);
            
            // Set data for view
            $this->data = array_merge($this->data, [
                'news' => $news,
                'featuredNews' => $featuredNews,
                'categoryCounts' => $categoryCounts,
                'archiveMonths' => $archiveMonths,
                'popularNews' => $popularNews,
                'pagination' => [
                    'current' => $page,
                    'total' => $totalPages,
                    'limit' => $limit,
                    'totalCount' => $totalCount
                ],
                'pageTitle' => 'News & Updates - FCT College of Nursing Sciences',
                'pageDescription' => 'Latest news, announcements, and updates from FCT College of Nursing Sciences'
            ]);
            
            // Render public news view
            $this->render('pages/news-article/index');
            
        } catch (Exception $e) {
            error_log("NewsController publicIndex error: " . $e->getMessage());
            $this->showPublicError("Failed to load news articles.");
        }
    }
    
    /**
     * Single news article for public
     */
    public function publicShow($slug) {
        // Don't require authentication for public methods
        $this->layout = 'public';
        
        try {
            // Get news by slug using NewsModel
            $news = $this->newsModel->getBySlug($slug);
            
            if (!$news) {
                $this->showPublicError("News article not found.", 404);
                return;
            }
            
            // Increment view count
            $this->newsModel->incrementViews($news['id']);
            
            // Get related news
            $relatedNews = $this->newsModel->getRelated($news['id'], 3);
            
            // Get featured news for sidebar
            $featuredNews = $this->newsModel->getFeatured(3);
            
            // Get categories with counts for sidebar
            $categoryCounts = $this->newsModel->getCategoryCounts();
            
            // Get popular news for sidebar
            $popularNews = $this->newsModel->getPopularNews(5);
            
            // Set SEO meta tags
            $metaTitle = !empty($news['meta_title']) ? $news['meta_title'] : $news['title'];
            $metaDescription = !empty($news['meta_description']) ? $news['meta_description'] : 
                ($news['excerpt'] ?: substr(strip_tags($news['content']), 0, 160));
            
            // Set data for view
            $this->data = array_merge($this->data, [
                'news' => $news,
                'relatedNews' => $relatedNews,
                'featuredNews' => $featuredNews,
                'categoryCounts' => $categoryCounts,
                'popularNews' => $popularNews,
                'pageTitle' => $metaTitle . ' - FCT College of Nursing Sciences',
                'pageDescription' => $metaDescription,
                'metaKeywords' => $news['meta_keywords'] ?? '',
                'metaImage' => !empty($news['featured_image']) ? BASE_URL . $news['featured_image'] : '',
                'metaUrl' => BASE_URL . '/news/' . $news['slug']
            ]);
            
            // Check if single.php exists, otherwise use a default template
            $singleViewPath = APP_PATH . '/views/pages/news-article/single.php';
            if (file_exists($singleViewPath)) {
                $this->render('pages/news-article/single');
            } else {
                // Fallback to show view
                $this->render('pages/news-article/show');
            }
            
        } catch (Exception $e) {
            error_log("NewsController publicShow error: " . $e->getMessage());
            $this->showPublicError("Failed to load news article.");
        }
    }
    
    /**
     * News by category for public
     */
    public function publicCategory($category) {
        // Don't require authentication for public methods
        $this->layout = 'public';
        
        try {
            // Get page parameters
            $page = max(1, (int)$this->input('page', 1));
            $limit = 10;
            $offset = ($page - 1) * $limit;
            
            // Get news by category using NewsModel
            $news = $this->newsModel->getByCategory($category, $limit, $offset);
            
            if (empty($news)) {
                $this->showPublicError("No news articles found in this category.", 404);
                return;
            }
            
            // Get total count for pagination
            $totalCount = $this->getCategoryPublishedCount($category);
            $totalPages = ceil($totalCount / $limit);
            
            // Get featured news for sidebar
            $featuredNews = $this->newsModel->getFeatured(3);
            
            // Get categories with counts for sidebar
            $categoryCounts = $this->newsModel->getCategoryCounts();
            
            // Get popular news for sidebar
            $popularNews = $this->newsModel->getPopularNews(5);
            
            // Set data for view
            $this->data = array_merge($this->data, [
                'news' => $news,
                'category' => $category,
                'featuredNews' => $featuredNews,
                'categoryCounts' => $categoryCounts,
                'popularNews' => $popularNews,
                'pagination' => [
                    'current' => $page,
                    'total' => $totalPages,
                    'limit' => $limit,
                    'totalCount' => $totalCount
                ],
                'pageTitle' => ucfirst($category) . ' News - FCT College of Nursing Sciences',
                'pageDescription' => 'Latest ' . $category . ' news and updates from FCT College of Nursing Sciences'
            ]);
            
            // Check if category.php exists, otherwise use index with category filter
            $categoryViewPath = APP_PATH . '/views/pages/news-article/category.php';
            if (file_exists($categoryViewPath)) {
                $this->render('pages/news-article/category');
            } else {
                $this->render('pages/news-article/index');
            }
            
        } catch (Exception $e) {
            error_log("NewsController publicCategory error: " . $e->getMessage());
            $this->showPublicError("Failed to load category news.");
        }
    }
    
    /**
     * Public news search
     */
    public function publicSearch() {
        // Don't require authentication for public methods
        $this->layout = 'public';
        
        try {
            // Get search parameters
            $query = $this->input('q', '');
            $category = $this->input('category', '');
            
            if (empty($query)) {
                $this->redirect('/news');
                return;
            }
            
            // Prepare filters
            $filters = [
                'category' => $category,
                'status' => 'published' // Only published for public search
            ];
            
            // Perform search using NewsModel
            $results = $this->newsModel->search($query, $filters);
            
            // Get categories for filter dropdown
            $categories = $this->newsModel->getCategories();
            
            // Get featured news for sidebar
            $featuredNews = $this->newsModel->getFeatured(3);
            
            // Get popular news for sidebar
            $popularNews = $this->newsModel->getPopularNews(5);
            
            // Set data for view
            $this->data = array_merge($this->data, [
                'results' => $results,
                'query' => $query,
                'category' => $category,
                'categories' => $categories,
                'featuredNews' => $featuredNews,
                'popularNews' => $popularNews,
                'pageTitle' => 'Search Results for "' . htmlspecialchars($query) . '" - FCT College of Nursing Sciences',
                'pageDescription' => 'Search results for news articles matching "' . htmlspecialchars($query) . '"'
            ]);
            
            // Check if search.php exists, otherwise use index with results
            $searchViewPath = APP_PATH . '/views/pages/news-article/search.php';
            if (file_exists($searchViewPath)) {
                $this->render('pages/news-article/search');
            } else {
                $this->render('pages/news-article/index');
            }
            
        } catch (Exception $e) {
            error_log("NewsController publicSearch error: " . $e->getMessage());
            $this->showPublicError("Failed to search news articles.");
        }
    }
    
    /**
     * RSS Feed generation
     */
    public function rssFeed() {
        try {
            // Get latest published news
            $news = $this->newsModel->getPublished(20, 0);
            
            // Set headers for RSS
            header('Content-Type: application/rss+xml; charset=utf-8');
            
            // Build RSS XML
            $rss = '<?xml version="1.0" encoding="UTF-8"?>';
            $rss .= '<rss version="2.0" xmlns:atom="http://www.w3.org/2005/Atom">';
            $rss .= '<channel>';
            $rss .= '<title>FCT College of Nursing Sciences - News & Updates</title>';
            $rss .= '<link>' . BASE_URL . '</link>';
            $rss .= '<description>Latest news, announcements, and updates from FCT College of Nursing Sciences</description>';
            $rss .= '<language>en-us</language>';
            $rss .= '<atom:link href="' . BASE_URL . '/news/rss" rel="self" type="application/rss+xml" />';
            $rss .= '<lastBuildDate>' . date(DATE_RSS) . '</lastBuildDate>';
            $rss .= '<pubDate>' . date(DATE_RSS) . '</pubDate>';
            
            // Add items
            foreach ($news as $item) {
                $pubDate = !empty($item['published_at']) ? $item['published_at'] : $item['created_at'];
                $description = !empty($item['excerpt']) ? $item['excerpt'] : substr(strip_tags($item['content']), 0, 300);
                
                $rss .= '<item>';
                $rss .= '<title>' . htmlspecialchars($item['title']) . '</title>';
                $rss .= '<link>' . BASE_URL . '/news/' . $item['slug'] . '</link>';
                $rss .= '<description>' . htmlspecialchars($description) . '</description>';
                $rss .= '<pubDate>' . date(DATE_RSS, strtotime($pubDate)) . '</pubDate>';
                $rss .= '<guid isPermaLink="true">' . BASE_URL . '/news/' . $item['slug'] . '</guid>';
                
                if (!empty($item['category'])) {
                    $rss .= '<category>' . htmlspecialchars($item['category']) . '</category>';
                }
                
                if (!empty($item['author_name'])) {
                    $rss .= '<author>' . htmlspecialchars($item['author_name']) . '</author>';
                }
                
                $rss .= '</item>';
            }
            
            $rss .= '</channel>';
            $rss .= '</rss>';
            
            echo $rss;
            exit;
            
        } catch (Exception $e) {
            error_log("NewsController rssFeed error: " . $e->getMessage());
            header('Content-Type: text/plain');
            echo "Error generating RSS feed: " . $e->getMessage();
        }
    }
    
    // ============================================
    // HELPER METHODS
    // ============================================
    
    /**
     * Render view using news_admin layout
     */
    private function renderWithNewsLayout($view = null, $data = []) {
        // Add CSRF token to all forms
        $data['csrf_token'] = $this->csrfToken();
        
        // Add flash messages
        $data['flash_success'] = $this->getFlash('success');
        $data['flash_error'] = $this->getFlash('error');
        
        // Merge with controller data
        $this->data = array_merge($this->data, $data);
        
        // Include the news_admin layout
        include APP_PATH . '/views/layouts/news_admin.php';
    }
    
    /**
     * Get total news count with filters
     */
    private function getNewsCount($filters = []) {
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
        
        $sql = "SELECT COUNT(*) as count FROM news $whereSQL";
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        $result = $stmt->fetch();
        
        return $result['count'] ?? 0;
    }
    
    /**
     * Get total published news count
     */
    private function getPublishedCount() {
        $sql = "SELECT COUNT(*) as count FROM news WHERE is_published = 1";
        $stmt = $this->db->query($sql);
        $result = $stmt->fetch();
        return $result['count'] ?? 0;
    }
    
    /**
     * Get published count by category
     */
    private function getCategoryPublishedCount($category) {
        $sql = "SELECT COUNT(*) as count FROM news WHERE is_published = 1 AND category = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$category]);
        $result = $stmt->fetch();
        return $result['count'] ?? 0;
    }
    
    /**
     * Get authors list for filter dropdown
     */
    private function getAuthors() {
        try {
            $sql = "SELECT DISTINCT u.id, u.username, u.full_name 
                    FROM news n 
                    INNER JOIN users u ON n.author_id = u.id 
                    ORDER BY u.full_name, u.username";
            $stmt = $this->db->query($sql);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            error_log("NewsController getAuthors error: " . $e->getMessage());
            return [];
        }
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
     * Show error message (admin)
     */
    private function showError($message) {
        $this->data = array_merge($this->data, [
            'error' => $message,
            'pageTitle' => 'Error - FCT College of Nursing Sciences',
            'pageDescription' => 'An error occurred'
        ]);
        
        $this->renderWithNewsLayout('admin/error');
    }
    
    /**
     * Show error message (public)
     */
    private function showPublicError($message, $code = 500) {
        http_response_code($code);
        
        $this->data = array_merge($this->data, [
            'error' => $message,
            'pageTitle' => 'Error - FCT College of Nursing Sciences',
            'pageDescription' => 'An error occurred'
        ]);
        
        // Try to render public error view
        $errorViewPath = APP_PATH . '/views/pages/error.php';
        if (file_exists($errorViewPath)) {
            $this->render('pages/error');
        } else {
            // Fallback error display
            echo '<!DOCTYPE html>
            <html>
            <head>
                <title>Error - FCT College of Nursing Sciences</title>
                <style>
                    body { font-family: Arial, sans-serif; padding: 40px; text-align: center; }
                    h1 { color: #e53e3e; }
                </style>
            </head>
            <body>
                <h1>Error</h1>
                <p>' . htmlspecialchars($message) . '</p>
                <p><a href="' . BASE_URL . '">Return to Homepage</a></p>
            </body>
            </html>';
        }
        exit;
    }
    
    /**
     * Helper method to get input value
     */
    private function input($key, $default = '') {
        if (isset($_POST[$key])) {
            return $_POST[$key];
        } elseif (isset($_GET[$key])) {
            return $_GET[$key];
        }
        return $default;
    }
    
    /**
     * Helper to generate CSRF token
     */
    private function csrfToken() {
        if (!isset($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['csrf_token'];
    }
    
    /**
     * Validate CSRF token
     */
    private function validateCsrf() {
        $token = $this->input('csrf_token', '');
        if (empty($token) || $token !== ($_SESSION['csrf_token'] ?? '')) {
            throw new Exception("Invalid CSRF token.");
        }
    }
    
    /**
     * Set flash message
     */
    private function flash($type, $message) {
        if (!isset($_SESSION['flash_messages'])) {
            $_SESSION['flash_messages'] = [];
        }
        $_SESSION['flash_messages'][] = ['type' => $type, 'text' => $message];
    }
    
    /**
     * Get flash message
     */
    private function getFlash($type) {
        if (!isset($_SESSION['flash_messages'])) {
            return '';
        }
        
        $messages = [];
        foreach ($_SESSION['flash_messages'] as $index => $flash) {
            if ($flash['type'] === $type) {
                $messages[] = $flash['text'];
                unset($_SESSION['flash_messages'][$index]);
            }
        }
        
        if (!empty($messages)) {
            $_SESSION['flash_messages'] = array_values($_SESSION['flash_messages']);
            return implode('<br>', $messages);
        }
        
        return '';
    }
}