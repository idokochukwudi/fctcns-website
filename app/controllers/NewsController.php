<?php
/**
 * Public News Controller - COMPLETE FIXED VERSION WITH AUTHOR DATA
 * Handles /news routes for public visitors
 */
class NewsController extends Controller {
    
    private $db;
    private $newsModel;
    
    public function __construct() {
        parent::__construct();
        
        error_log("=== PUBLIC NEWS CONTROLLER CONSTRUCTOR ===");
        
        // Set public layout
        $this->layout = 'main';
        
        // Get database connection
        require_once APP_PATH . '/config/database.php';
        $database = Database::getInstance();
        $this->db = $database->getConnection();
        
        // Initialize News Model
        require_once APP_PATH . '/models/NewsModel.php';
        $this->newsModel = new NewsModel($this->db);
        
        // Set common data
        $this->data = array_merge($this->data, [
            'baseUrl' => BASE_URL,
            'currentPage' => 'news'
        ]);
    }
    
    /**
     * Main news listing page - /news
     */
    public function index() {
        error_log("=== PUBLIC NEWS INDEX METHOD CALLED ===");
        
        try {
            // Get page number
            $page = max(1, (int)($_GET['page'] ?? 1));
            $limit = 10;
            $offset = ($page - 1) * $limit;
            
            // Use NewsModel methods instead of direct queries
            $news = $this->newsModel->getPublishedNews($limit, $offset);
            $total = $this->newsModel->countPublishedNews();
            
            error_log("NewsModel returned: " . count($news) . " articles");
            error_log("Total count: " . $total);
            
            // Get other data using model methods
            $featuredNews = $this->newsModel->getFeaturedNews(3);
            $categories = $this->newsModel->getCategoriesWithCounts();
            $archiveMonths = $this->newsModel->getArchiveMonths();
            $popularNews = $this->newsModel->getPopularNews(5);
            
            error_log("Featured news: " . count($featuredNews));
            error_log("Categories: " . count($categories));
            error_log("Archive months: " . count($archiveMonths));
            error_log("Popular news: " . count($popularNews));
            
            $totalPages = ceil($total / $limit);
            
            // Prepare view data
            $viewData = [
                'baseUrl' => BASE_URL,
                'currentPage' => 'news',
                'news' => $news,
                'featuredNews' => $featuredNews,
                'categories' => $categories,
                'archiveMonths' => $archiveMonths,
                'popularNews' => $popularNews,
                'pagination' => [
                    'current' => $page,
                    'total' => $totalPages,
                    'limit' => $limit,
                    'totalCount' => $total
                ],
                'pageTitle' => 'News & Updates - FCT College of Nursing Sciences',
                'pageDescription' => 'Latest news, announcements, and updates from FCT College of Nursing Sciences',
                'hasRealData' => count($news) > 0
            ];
            
            error_log("Passing data to view with " . count($news) . " articles");
            
            // Use the Controller's render method
            $this->data = array_merge($this->data, $viewData);
            $this->render('pages/news/index');
            
        } catch (Exception $e) {
            error_log("Public NewsController index error: " . $e->getMessage());
            error_log("Error trace: " . $e->getTraceAsString());
            
            // Show error state
            $viewData = [
                'baseUrl' => BASE_URL,
                'currentPage' => 'news',
                'news' => [],
                'featuredNews' => [],
                'categories' => [],
                'archiveMonths' => [],
                'popularNews' => [],
                'pagination' => [
                    'current' => 1,
                    'total' => 0,
                    'limit' => 10,
                    'totalCount' => 0
                ],
                'pageTitle' => 'News - FCT College of Nursing Sciences',
                'pageDescription' => 'Latest news and updates',
                'hasRealData' => false,
                'error' => 'Failed to load news: ' . $e->getMessage()
            ];
            
            $this->data = array_merge($this->data, $viewData);
            $this->render('pages/news/index');
        }
    }
    
    /**
     * Show single news article - /news/{slug}
     */
    public function show($slug) {
        error_log("=== PUBLIC NEWS SHOW METHOD CALLED for slug: $slug ===");
        
        try {
            // Get news article by slug using model - NOW INCLUDES full_name and role
            $news = $this->newsModel->getBySlug($slug);
            
            if (!$news) {
                error_log("Article not found with slug: $slug");
                $this->show404('Article not found');
                return;
            }
            
            // Check if article is published
            if (empty($news['is_published']) || $news['is_published'] == 0) {
                error_log("Article found but not published: $slug");
                $this->show404('Article is not published');
                return;
            }
            
            // Check if it's a news article (not event)
            if (isset($news['type']) && $news['type'] === 'event') {
                error_log("Article is an event, redirecting to events section: $slug");
                $this->show404('This is an event, not a news article');
                return;
            }
            
            // Increment views
            $this->newsModel->incrementViews($news['id']);
            
            // Get related news
            $relatedNews = $this->newsModel->getRelatedNews($news['id'], $news['category'] ?? '', 3);
            
            // Get other data for sidebar
            $categories = $this->newsModel->getCategoriesWithCounts();
            $archiveMonths = $this->newsModel->getArchiveMonths();
            
            // Get popular news
            $popularNews = $this->newsModel->getPopularNews(5);
            
            // ✅ DEBUG: Log author information
            error_log("=== AUTHOR INFORMATION ===");
            error_log("Author ID: " . ($news['author_id'] ?? 'NULL'));
            error_log("Author Name: " . ($news['author_name'] ?? 'NOT SET'));
            error_log("Author Full Name: " . ($news['full_name'] ?? 'NOT SET'));
            error_log("Author Role: " . ($news['author_role'] ?? 'NOT SET'));
            
            // ✅ DEBUG: Verify full author data structure
            if (!empty($news['full_name'])) {
                error_log("✓ Full author data present: {$news['full_name']} ({$news['author_role']})");
            } elseif (!empty($news['author_name'])) {
                error_log("⚠ Only legacy author_name field present: {$news['author_name']}");
            } else {
                error_log("✗ No author information available");
            }
            
            // ✅ DEBUG: Log popular news data
            error_log("=== POPULAR NEWS DATA ===");
            error_log("Popular news count: " . count($popularNews));
            if (count($popularNews) > 0) {
                error_log("First popular article: " . json_encode($popularNews[0]));
            } else {
                error_log("WARNING: No popular news found - check database for published articles with views_count");
            }
            
            error_log("Found article: " . ($news['title'] ?? 'No title'));
            error_log("Related news: " . count($relatedNews));
            error_log("Categories: " . count($categories));
            error_log("Archive months: " . count($archiveMonths));
            
            $viewData = [
                'baseUrl' => BASE_URL,
                'currentPage' => 'news',
                'news' => $news,
                'relatedNews' => $relatedNews,
                'categories' => $categories,
                'archiveMonths' => $archiveMonths,
                'popularNews' => $popularNews,
                'pageTitle' => ($news['title'] ?? 'News Article') . ' - FCT College of Nursing Sciences',
                'pageDescription' => $news['excerpt'] ?? substr(strip_tags($news['content'] ?? ''), 0, 150) . '...'
            ];
            
            // Check if professional show view exists, otherwise use regular show
            $professionalViewPath = APP_PATH . '/views/pages/news/show_professional.php';
            $regularViewPath = APP_PATH . '/views/pages/news/show.php';
            
            if (file_exists($professionalViewPath)) {
                error_log("Using professional show view");
                $this->data = array_merge($this->data, $viewData);
                $this->render('pages/news/show_professional');
            } elseif (file_exists($regularViewPath)) {
                error_log("Using regular show view");
                $this->data = array_merge($this->data, $viewData);
                $this->render('pages/news/show');
            } else {
                error_log("No show view found, rendering inline");
                $this->renderInlineShow($viewData);
            }
            
        } catch (Exception $e) {
            error_log("Public NewsController show error: " . $e->getMessage());
            error_log("Error trace: " . $e->getTraceAsString());
            $this->show404('Error loading article: ' . $e->getMessage());
        }
    }
    
    /**
     * Render inline show view if no view file exists
     */
    private function renderInlineShow($viewData) {
        extract($viewData);
        
        // Simple inline HTML for the show page
        ?>
        <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title><?php echo htmlspecialchars($pageTitle); ?></title>
            <meta name="description" content="<?php echo htmlspecialchars($pageDescription); ?>">
            <style>
                body { font-family: Arial, sans-serif; line-height: 1.6; max-width: 800px; margin: 0 auto; padding: 20px; }
                .article-header { margin-bottom: 30px; }
                .article-title { font-size: 2rem; margin-bottom: 10px; }
                .article-meta { color: #666; margin-bottom: 20px; }
                .author-info { background: #f5f5f5; padding: 15px; border-left: 4px solid #5D4A8A; margin-bottom: 20px; }
                .author-name { font-weight: bold; color: #5D4A8A; }
                .author-role { color: #666; font-style: italic; }
                .article-content { font-size: 1.1rem; }
                .back-link { display: inline-block; margin-top: 30px; padding: 10px 20px; background: #5D4A8A; color: white; text-decoration: none; border-radius: 5px; }
                .sidebar { margin-top: 40px; padding-top: 20px; border-top: 1px solid #ddd; }
                .popular-news { margin-top: 20px; }
                .popular-news-item { margin-bottom: 10px; padding-bottom: 10px; border-bottom: 1px solid #eee; }
            </style>
        </head>
        <body>
            <article>
                <header class="article-header">
                    <h1 class="article-title"><?php echo htmlspecialchars($news['title'] ?? 'News Article'); ?></h1>
                    <div class="article-meta">
                        <?php if (!empty($news['created_at'])): ?>
                            <span>Published: <?php echo date('F j, Y', strtotime($news['created_at'])); ?></span>
                        <?php endif; ?>
                        <?php if (!empty($news['category'])): ?>
                            <span> | Category: <?php echo htmlspecialchars($news['category']); ?></span>
                        <?php endif; ?>
                        <?php if (!empty($news['views_count'])): ?>
                            <span> | Views: <?php echo number_format($news['views_count']); ?></span>
                        <?php endif; ?>
                    </div>
                </header>
                
                <?php if (!empty($news['full_name']) || !empty($news['author_name'])): ?>
                <div class="author-info">
                    <span class="author-name">
                        <?php echo htmlspecialchars($news['full_name'] ?? $news['author_name'] ?? 'Author'); ?>
                    </span>
                    <?php if (!empty($news['author_role'])): ?>
                        <span class="author-role">(<?php echo htmlspecialchars($news['author_role']); ?>)</span>
                    <?php endif; ?>
                </div>
                <?php endif; ?>
                
                <?php if (!empty($news['featured_image'])): ?>
                <div class="featured-image">
                    <img src="<?php echo $news['featured_image']; ?>" alt="<?php echo htmlspecialchars($news['title'] ?? ''); ?>" style="width: 100%; height: auto;">
                </div>
                <?php endif; ?>
                
                <div class="article-content">
                    <?php echo !empty($news['content']) ? $news['content'] : 'Content not available.'; ?>
                </div>
                
                <?php if (!empty($popularNews)): ?>
                <div class="sidebar">
                    <h3>Popular News</h3>
                    <div class="popular-news">
                        <?php foreach ($popularNews as $popular): ?>
                        <div class="popular-news-item">
                            <a href="<?php echo $baseUrl; ?>/news/<?php echo $popular['slug']; ?>">
                                <?php echo htmlspecialchars($popular['title']); ?>
                            </a>
                            <small style="color: #666; display: block;">
                                Views: <?php echo number_format($popular['views_count'] ?? 0); ?>
                            </small>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                <?php endif; ?>
                
                <a href="<?php echo $baseUrl; ?>/news" class="back-link">← Back to News</a>
            </article>
        </body>
        </html>
        <?php
        exit;
    }
    
    /**
     * News by category - /news/category/{category}
     */
    public function category($category) {
        error_log("=== PUBLIC NEWS CATEGORY METHOD for: $category ===");
        
        try {
            // Get page number
            $page = max(1, (int)($_GET['page'] ?? 1));
            $limit = 10;
            $offset = ($page - 1) * $limit;
            
            // Decode category if it's URL encoded
            $category = urldecode($category);
            
            // Use model method with category filter
            $news = $this->newsModel->getPublishedNews($limit, $offset, $category);
            $total = $this->newsModel->countPublishedNews($category);
            
            $totalPages = ceil($total / $limit);
            
            // Get other data for sidebar
            $categories = $this->newsModel->getCategoriesWithCounts();
            $archiveMonths = $this->newsModel->getArchiveMonths();
            $popularNews = $this->newsModel->getPopularNews(5);
            
            error_log("Category '$category': Found " . count($news) . " articles, total: $total");
            
            $viewData = [
                'baseUrl' => BASE_URL,
                'currentPage' => 'news',
                'news' => $news,
                'category' => $category,
                'categories' => $categories,
                'archiveMonths' => $archiveMonths,
                'popularNews' => $popularNews,
                'pagination' => [
                    'current' => $page,
                    'total' => $totalPages,
                    'limit' => $limit,
                    'totalCount' => $total
                ],
                'pageTitle' => ucfirst($category) . ' News - FCT College of Nursing Sciences',
                'pageDescription' => 'Latest ' . $category . ' news and updates'
            ];
            
            // Use the index view but with filtered data
            $this->data = array_merge($this->data, $viewData);
            $this->render('pages/news/index');
            
        } catch (Exception $e) {
            error_log("Public NewsController category error: " . $e->getMessage());
            $this->show404('Category not found');
        }
    }
    
    /**
     * News by archive month - /news/archive/{year}/{month}
     */
    public function archive($year, $month) {
        error_log("=== PUBLIC NEWS ARCHIVE METHOD for: $year-$month ===");
        
        try {
            // Get page number
            $page = max(1, (int)($_GET['page'] ?? 1));
            $limit = 10;
            $offset = ($page - 1) * $limit;
            
            // Get news by archive month
            $news = $this->newsModel->getByArchiveMonth($year, $month, $limit, $offset);
            $total = $this->newsModel->countByArchiveMonth($year, $month);
            
            $totalPages = ceil($total / $limit);
            
            // Get other data for sidebar
            $categories = $this->newsModel->getCategoriesWithCounts();
            $archiveMonths = $this->newsModel->getArchiveMonths();
            $popularNews = $this->newsModel->getPopularNews(5);
            
            $monthName = date('F Y', strtotime("$year-$month-01"));
            
            error_log("Archive '$year-$month': Found " . count($news) . " articles, total: $total");
            
            $viewData = [
                'baseUrl' => BASE_URL,
                'currentPage' => 'news',
                'news' => $news,
                'archiveTitle' => $monthName,
                'categories' => $categories,
                'archiveMonths' => $archiveMonths,
                'popularNews' => $popularNews,
                'pagination' => [
                    'current' => $page,
                    'total' => $totalPages,
                    'limit' => $limit,
                    'totalCount' => $total
                ],
                'pageTitle' => 'News from ' . $monthName . ' - FCT College of Nursing Sciences',
                'pageDescription' => 'News articles from ' . $monthName
            ];
            
            // Use the index view
            $this->data = array_merge($this->data, $viewData);
            $this->render('pages/news/index');
            
        } catch (Exception $e) {
            error_log("Public NewsController archive error: " . $e->getMessage());
            $this->show404('Archive not found');
        }
    }
    
    /**
     * Search news - /news/search?q={query}
     */
    public function search() {
        error_log("=== PUBLIC NEWS SEARCH METHOD ===");
        
        try {
            $query = trim($_GET['q'] ?? '');
            
            if (empty($query)) {
                header('Location: /news');
                exit;
            }
            
            // Get page number
            $page = max(1, (int)($_GET['page'] ?? 1));
            $limit = 10;
            $offset = ($page - 1) * $limit;
            
            // Search news
            $news = $this->newsModel->searchNews($query, $limit, $offset);
            $total = $this->newsModel->countSearchResults($query);
            
            $totalPages = ceil($total / $limit);
            
            // Get other data for sidebar
            $categories = $this->newsModel->getCategoriesWithCounts();
            $archiveMonths = $this->newsModel->getArchiveMonths();
            $popularNews = $this->newsModel->getPopularNews(5);
            
            error_log("Search for '$query': Found " . count($news) . " articles, total: $total");
            
            $viewData = [
                'baseUrl' => BASE_URL,
                'currentPage' => 'news',
                'news' => $news,
                'searchQuery' => $query,
                'categories' => $categories,
                'archiveMonths' => $archiveMonths,
                'popularNews' => $popularNews,
                'pagination' => [
                    'current' => $page,
                    'total' => $totalPages,
                    'limit' => $limit,
                    'totalCount' => $total
                ],
                'pageTitle' => 'Search Results for "' . htmlspecialchars($query) . '" - FCT College of Nursing Sciences',
                'pageDescription' => 'Search results for ' . htmlspecialchars($query)
            ];
            
            $this->data = array_merge($this->data, $viewData);
            $this->render('pages/news/index');
            
        } catch (Exception $e) {
            error_log("Public NewsController search error: " . $e->getMessage());
            $this->show404('Search error');
        }
    }
    
    /**
     * Show 404 not found page
     */
    private function show404($message = 'Page not found') {
        // Set 404 header
        http_response_code(404);
        
        error_log("Showing 404: $message");
        
        $viewData = [
            'baseUrl' => BASE_URL,
            'currentPage' => 'news',
            'pageTitle' => 'Page Not Found - FCT College of Nursing Sciences',
            'pageDescription' => 'The requested page could not be found',
            'error_message' => $message
        ];
        
        // Check if 404 view exists
        $viewPath = APP_PATH . '/views/errors/404.php';
        if (file_exists($viewPath)) {
            $this->data = array_merge($this->data, $viewData);
            $this->render('errors/404');
        } else {
            // Fallback 404 page
            ?>
            <!DOCTYPE html>
            <html lang="en">
            <head>
                <meta charset="UTF-8">
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                <title>404 - Page Not Found</title>
                <style>
                    body { font-family: Arial, sans-serif; text-align: center; padding: 50px; }
                    h1 { font-size: 3rem; color: #5D4A8A; }
                    p { font-size: 1.2rem; color: #666; }
                    a { color: #5D4A8A; text-decoration: none; }
                </style>
            </head>
            <body>
                <h1>404</h1>
                <p>Page Not Found</p>
                <p><?php echo htmlspecialchars($message); ?></p>
                <p><a href="<?php echo BASE_URL; ?>/news">← Back to News</a></p>
            </body>
            </html>
            <?php
        }
        exit;
    }
    
    /**
     * RSS feed - /news/rss
     */
    public function rss() {
        try {
            // Get latest news
            $sql = "SELECT n.*, 
                           CASE 
                               WHEN n.author_id IS NOT NULL AND u.id IS NOT NULL THEN u.username
                               ELSE 'System'
                           END as author_name
                    FROM news n 
                    LEFT JOIN users u ON n.author_id = u.id 
                    WHERE n.is_published = 1 AND (n.type = 'news' OR n.type IS NULL)
                    ORDER BY n.created_at DESC 
                    LIMIT 20";
            
            $stmt = $this->db->query($sql);
            $news = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            // Set headers for RSS
            header('Content-Type: application/rss+xml; charset=utf-8');
            
            // Generate RSS
            echo '<?xml version="1.0" encoding="UTF-8"?>';
            echo '<rss version="2.0">';
            echo '<channel>';
            echo '<title>FCT College of Nursing Sciences - News</title>';
            echo '<link>' . BASE_URL . '/news</link>';
            echo '<description>Latest news and updates from FCT College of Nursing Sciences</description>';
            echo '<language>en-us</language>';
            echo '<lastBuildDate>' . date(DATE_RSS) . '</lastBuildDate>';
            
            foreach ($news as $item) {
                echo '<item>';
                echo '<title>' . htmlspecialchars($item['title']) . '</title>';
                echo '<link>' . BASE_URL . '/news/' . $item['slug'] . '</link>';
                echo '<description>' . htmlspecialchars($item['excerpt'] ?? '') . '</description>';
                echo '<pubDate>' . date(DATE_RSS, strtotime($item['created_at'])) . '</pubDate>';
                echo '<guid>' . BASE_URL . '/news/' . $item['slug'] . '</guid>';
                echo '</item>';
            }
            
            echo '</channel>';
            echo '</rss>';
            
        } catch (Exception $e) {
            error_log("Public NewsController rss error: " . $e->getMessage());
            header('Content-Type: text/plain');
            echo 'Error generating RSS feed';
        }
        exit;
    }
    
    /**
     * Quick test to check if data is being fetched
     */
    public function testData() {
        error_log("=== TEST DATA METHOD ===");
        
        echo "<h1>News Data Test</h1>";
        
        try {
            echo "<h2>Testing NewsModel Methods</h2>";
            
            // Test getPublishedNews
            echo "<h3>getPublishedNews()</h3>";
            $news = $this->newsModel->getPublishedNews(5, 0);
            echo "<p>Found " . count($news) . " published news articles</p>";
            
            if (count($news) > 0) {
                echo "<ul>";
                foreach ($news as $item) {
                    echo "<li>";
                    echo "<strong>" . htmlspecialchars($item['title']) . "</strong> ";
                    echo "(ID: {$item['id']}, Slug: {$item['slug']}) ";
                    echo "<a href='" . BASE_URL . "/news/{$item['slug']}' target='_blank'>View</a>";
                    echo "</li>";
                }
                echo "</ul>";
            } else {
                echo "<p style='color: red;'>No published news found!</p>";
                
                // Check database directly
                echo "<h3>Direct Database Check</h3>";
                $sql = "SELECT id, title, slug, is_published, type FROM news ORDER BY created_at DESC LIMIT 10";
                $stmt = $this->db->query($sql);
                $allNews = $stmt->fetchAll(PDO::FETCH_ASSOC);
                
                echo "<p>All news in database: " . count($allNews) . "</p>";
                echo "<table border='1' cellpadding='5'>";
                echo "<tr><th>ID</th><th>Title</th><th>Slug</th><th>Published</th><th>Type</th></tr>";
                foreach ($allNews as $item) {
                    echo "<tr>";
                    echo "<td>" . $item['id'] . "</td>";
                    echo "<td>" . htmlspecialchars($item['title']) . "</td>";
                    echo "<td>" . $item['slug'] . "</td>";
                    echo "<td>" . ($item['is_published'] ? 'Yes' : 'No') . "</td>";
                    echo "<td>" . ($item['type'] ?: 'NULL') . "</td>";
                    echo "</tr>";
                }
                echo "</table>";
            }
            
            // Test getBySlug with author data
            echo "<h3>getBySlug() with Author Data Test</h3>";
            if (count($news) > 0) {
                $firstSlug = $news[0]['slug'];
                $singleNews = $this->newsModel->getBySlug($firstSlug);
                if ($singleNews) {
                    echo "<p>Found article by slug '$firstSlug': " . htmlspecialchars($singleNews['title']) . "</p>";
                    echo "<p><strong>Author Information:</strong></p>";
                    echo "<ul>";
                    echo "<li>Author ID: " . ($singleNews['author_id'] ?? 'NULL') . "</li>";
                    echo "<li>Author Name: " . ($singleNews['author_name'] ?? 'NOT SET') . "</li>";
                    echo "<li>Author Full Name: " . ($singleNews['full_name'] ?? 'NOT SET') . "</li>";
                    echo "<li>Author Role: " . ($singleNews['author_role'] ?? 'NOT SET') . "</li>";
                    echo "</ul>";
                    
                    if (!empty($singleNews['full_name'])) {
                        echo "<p style='color: green;'>✓ Full author data present: {$singleNews['full_name']} ({$singleNews['author_role']})</p>";
                    } else {
                        echo "<p style='color: orange;'>⚠ Author data incomplete</p>";
                    }
                } else {
                    echo "<p style='color: red;'>getBySlug() failed for slug: $firstSlug</p>";
                }
            }
            
            // Test other methods
            echo "<h3>getCategoriesWithCounts()</h3>";
            $categories = $this->newsModel->getCategoriesWithCounts();
            echo "<p>Categories: " . count($categories) . "</p>";
            echo "<pre>" . print_r($categories, true) . "</pre>";
            
            echo "<h3>getArchiveMonths()</h3>";
            $archiveMonths = $this->newsModel->getArchiveMonths();
            echo "<p>Archive months: " . count($archiveMonths) . "</p>";
            echo "<pre>" . print_r($archiveMonths, true) . "</pre>";
            
            echo "<h3>getFeaturedNews()</h3>";
            $featuredNews = $this->newsModel->getFeaturedNews(3);
            echo "<p>Featured news: " . count($featuredNews) . "</p>";
            
            echo "<h3>getPopularNews()</h3>";
            $popularNews = $this->newsModel->getPopularNews(5);
            echo "<p>Popular news: " . count($popularNews) . "</p>";
            
        } catch (Exception $e) {
            echo "<p style='color: red;'>Error: " . $e->getMessage() . "</p>";
            echo "<pre>" . $e->getTraceAsString() . "</pre>";
        }
        
        exit;
    }
}