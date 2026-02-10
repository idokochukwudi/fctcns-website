<?php
/**
 * News Search Results Page - COMPLETE FIXED VERSION
 * Professional design matching the news index
 * Proper search functionality with filters
 */

// Ensure required variables exist
$baseUrl = $baseUrl ?? (defined('BASE_URL') ? BASE_URL : '');
$news = $news ?? [];
$searchQuery = $searchQuery ?? ($_GET['q'] ?? '');
$categories = $categories ?? [];
$archiveMonths = $archiveMonths ?? [];
$popularNews = $popularNews ?? [];
$currentPage = $currentPage ?? 'news';
$pagination = $pagination ?? ['current' => 1, 'total' => 0, 'limit' => 10, 'totalCount' => 0];
$pageTitle = $pageTitle ?? 'Search Results - FCT College of Nursing Sciences';
$pageDescription = $pageDescription ?? 'Search results for news articles';

// Hero image
$heroImagePath = $baseUrl . '/assets/images/news/news-hero.jpg';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($pageTitle); ?></title>
    <meta name="description" content="<?php echo htmlspecialchars($pageDescription); ?>">
    
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700&family=Open+Sans:wght@300;400;500&display=swap" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- CSS Styles -->
    <style>
/* ==========================================================================
   RESET & GLOBAL
   ========================================================================== */
* { 
    box-sizing: border-box; 
    margin: 0;
    padding: 0;
}

html, body { 
    margin: 0 !important; 
    padding: 0 !important; 
    overflow-x: hidden;
}

body { 
    font-family: 'Open Sans', sans-serif; 
    font-size: 16px; 
    line-height: 1.6; 
    color: #2D3748; 
    background: #FFFFFF; 
    -webkit-font-smoothing: antialiased;
    -moz-osx-font-smoothing: grayscale;
}

main.search-page { 
    margin: 0 !important; 
    padding: 0 !important; 
}

/* ==========================================================================
   COLOR VARIABLES
   ========================================================================== */
:root {
    --color-primary: #5D4A8A;
    --color-primary-dark: #4A3A6F;
    --color-primary-light: #6F5B9E;
    --color-primary-very-light: #F8F6FC;
    --color-primary-transparent: rgba(93, 74, 138, 0.08);
    
    --color-accent: #D4A574;
    --color-accent-dark: #BF8F5E;
    --color-accent-light: #E6C9A5;
    
    --color-white: #FFFFFF;
    --color-off-white: #FAFAFA;
    --color-gray-50: #F5F7FA;
    --color-gray-100: #E8ECF1;
    --color-gray-200: #D1D9E3;
    --color-gray-300: #B8C2CC;
    --color-gray-600: #718096;
    --color-gray-800: #2D3748;
    --color-gray-900: #1A202C;
    
    --font-heading: 'Montserrat', sans-serif;
    --font-body: 'Open Sans', sans-serif;
    
    --spacing-xs: 0.5rem;
    --spacing-sm: 1rem;
    --spacing-md: 1.5rem;
    --spacing-lg: 2rem;
    --spacing-xl: 2.5rem;
    --spacing-xxl: 3.5rem;
    
    --shadow-subtle: 0 2px 6px rgba(0, 0, 0, 0.05);
    --shadow-soft: 0 4px 12px rgba(0, 0, 0, 0.08);
    --shadow-elevated: 0 8px 24px rgba(0, 0, 0, 0.12);
    
    --radius-sm: 6px;
    --radius-md: 10px;
    --radius-lg: 14px;
    --radius-full: 999px;
    
    --transition-smooth: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

/* ==========================================================================
   SEARCH HERO SECTION
   ========================================================================== */
.search-hero {
    position: relative;
    width: 100vw;
    min-height: 45vh;
    margin: 0 !important;
    padding: 0 !important;
    background: linear-gradient(135deg, var(--color-primary), var(--color-primary-dark));
    display: flex;
    align-items: center;
    justify-content: center;
    overflow: hidden;
}

.search-hero::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: linear-gradient(135deg, 
        rgba(93, 74, 138, 0.92) 0%, 
        rgba(74, 58, 111, 0.88) 100%
    );
    z-index: 1;
}

.search-hero-bg {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-image: url('<?php echo $heroImagePath; ?>');
    background-size: cover;
    background-position: center;
    background-repeat: no-repeat;
    opacity: 0.15;
    z-index: 0;
}

.hero-container {
    position: relative;
    z-index: 2;
    width: 100%;
    max-width: 1200px;
    margin: 0 auto;
    padding: var(--spacing-xl) var(--spacing-md);
}

.search-hero-content {
    position: relative;
    color: var(--color-white);
    text-align: center;
    margin: 0 auto;
    max-width: 800px;
}

.search-hero-badge {
    display: inline-block;
    background: var(--color-accent);
    color: var(--color-gray-900);
    padding: 0.6rem 1.75rem;
    border-radius: var(--radius-full);
    font-size: 0.9rem;
    font-weight: 600;
    margin-bottom: var(--spacing-md);
    letter-spacing: 0.5px;
    text-transform: uppercase;
    font-family: var(--font-heading);
    box-shadow: var(--shadow-elevated);
}

.search-hero-title {
    font-family: var(--font-heading);
    font-size: clamp(2rem, 5vw, 3rem);
    font-weight: 700;
    color: var(--color-white);
    text-shadow: 0 2px 15px rgba(0, 0, 0, 0.4);
    line-height: 1.2;
    margin-bottom: var(--spacing-sm);
}

.search-hero-subtitle {
    font-size: clamp(1.1rem, 3vw, 1.4rem);
    color: rgba(255, 255, 255, 0.95);
    line-height: 1.5;
    margin-bottom: var(--spacing-lg);
    font-weight: 400;
}

.search-query-display {
    background: rgba(255, 255, 255, 0.15);
    backdrop-filter: blur(10px);
    padding: var(--spacing-sm) var(--spacing-lg);
    border-radius: var(--radius-full);
    display: inline-block;
    margin-bottom: var(--spacing-lg);
    border: 1px solid rgba(255, 255, 255, 0.2);
}

.search-query-display strong {
    color: var(--color-accent);
    font-weight: 600;
}

/* ==========================================================================
   SEARCH FORM
   ========================================================================== */
.news-search {
    max-width: 600px;
    margin: 0 auto;
}

.news-search-form {
    position: relative;
    width: 100%;
}

.news-search-input {
    width: 100%;
    padding: 1.25rem 4.5rem 1.25rem 1.75rem;
    border: 3px solid rgba(255, 255, 255, 0.3);
    border-radius: var(--radius-full);
    font-family: var(--font-body);
    font-size: 1.05rem;
    background-color: rgba(255, 255, 255, 0.95);
    color: var(--color-gray-900);
    box-shadow: var(--shadow-elevated);
    transition: var(--transition-smooth);
    outline: none;
    cursor: text;
    -webkit-appearance: none;
    appearance: none;
}

.news-search-input::placeholder {
    color: var(--color-gray-600);
    opacity: 0.8;
}

.news-search-input:hover {
    border-color: rgba(255, 255, 255, 0.5);
    background-color: var(--color-white);
}

.news-search-input:focus {
    border-color: var(--color-accent);
    background-color: var(--color-white);
    box-shadow: 0 0 0 4px rgba(212, 165, 116, 0.25), var(--shadow-elevated);
    transform: translateY(-2px);
}

.news-search-button {
    position: absolute;
    right: 0.5rem;
    top: 50%;
    transform: translateY(-50%);
    background-color: var(--color-primary);
    color: var(--color-white);
    border: none;
    width: 50px;
    height: 50px;
    border-radius: 50%;
    cursor: pointer;
    transition: var(--transition-smooth);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.1rem;
    box-shadow: var(--shadow-soft);
}

.news-search-button:hover {
    background-color: var(--color-primary-dark);
    transform: translateY(-50%) scale(1.05);
}

/* ==========================================================================
   CONTAINER & LAYOUT
   ========================================================================== */
.container {
    width: 100%;
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 var(--spacing-md);
}

.search-layout {
    display: grid;
    grid-template-columns: 1fr 350px;
    gap: var(--spacing-xl);
    margin: var(--spacing-xl) auto;
}

/* ==========================================================================
   RESULTS INFO
   ========================================================================== */
.results-info {
    background: var(--color-primary-very-light);
    border-left: 4px solid var(--color-primary);
    padding: var(--spacing-md) var(--spacing-lg);
    border-radius: var(--radius-md);
    margin-bottom: var(--spacing-lg);
    display: flex;
    align-items: center;
    gap: var(--spacing-sm);
}

.results-info i {
    color: var(--color-primary);
    font-size: 1.25rem;
}

.results-info-text {
    flex: 1;
}

.results-count {
    font-weight: 600;
    color: var(--color-primary);
    font-size: 1.1rem;
}

/* ==========================================================================
   NEWS CARDS (Same as index)
   ========================================================================== */
.news-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(min(100%, 350px), 1fr));
    gap: var(--spacing-lg);
    margin-bottom: var(--spacing-xl);
}

.news-card {
    background: var(--color-white);
    border-radius: var(--radius-lg);
    box-shadow: var(--shadow-subtle);
    transition: var(--transition-smooth);
    border: 1px solid var(--color-gray-100);
    overflow: hidden;
    height: 100%;
    display: flex;
    flex-direction: column;
}

.news-card:hover {
    transform: translateY(-8px);
    box-shadow: var(--shadow-elevated);
    border-color: var(--color-primary-light);
}

.news-image-container {
    position: relative;
    height: 200px;
    overflow: hidden;
}

.news-image {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.5s ease;
}

.news-card:hover .news-image {
    transform: scale(1.08);
}

.news-content {
    padding: var(--spacing-lg);
    flex: 1;
    display: flex;
    flex-direction: column;
}

.news-category {
    display: inline-block;
    background-color: var(--color-gray-100);
    color: var(--color-primary);
    padding: 0.4rem 0.875rem;
    border-radius: var(--radius-full);
    font-size: 0.75rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    margin-bottom: var(--spacing-md);
    align-self: flex-start;
}

.news-title {
    font-family: var(--font-heading);
    font-size: 1.25rem;
    font-weight: 600;
    line-height: 1.4;
    margin-bottom: var(--spacing-sm);
    color: var(--color-gray-900);
}

.news-title a {
    color: inherit;
    text-decoration: none;
    transition: color 0.3s ease;
}

.news-title a:hover {
    color: var(--color-primary);
}

.news-excerpt {
    color: var(--color-gray-800);
    line-height: 1.6;
    margin-bottom: var(--spacing-md);
    flex: 1;
}

.news-meta {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding-top: var(--spacing-sm);
    border-top: 1px solid var(--color-gray-200);
    color: var(--color-gray-600);
    font-size: 0.875rem;
}

.meta-item {
    display: flex;
    align-items: center;
    gap: 0.375rem;
}

.meta-item i {
    color: var(--color-primary);
}

/* ==========================================================================
   SIDEBAR
   ========================================================================== */
.search-sidebar {
    position: sticky;
    top: var(--spacing-md);
    height: fit-content;
}

.sidebar-widget {
    background-color: var(--color-white);
    border-radius: var(--radius-lg);
    padding: var(--spacing-lg);
    margin-bottom: var(--spacing-lg);
    box-shadow: var(--shadow-subtle);
    border: 1px solid var(--color-gray-100);
}

.widget-title {
    font-size: 1.125rem;
    font-weight: 600;
    margin-bottom: var(--spacing-md);
    color: var(--color-primary);
    display: flex;
    align-items: center;
    gap: var(--spacing-sm);
}

.widget-title i {
    color: var(--color-accent);
}

.search-tips {
    list-style: none;
}

.search-tips li {
    padding: var(--spacing-sm) 0;
    border-bottom: 1px solid var(--color-gray-200);
    color: var(--color-gray-700);
    display: flex;
    align-items: center;
    gap: var(--spacing-sm);
}

.search-tips li:last-child {
    border-bottom: none;
}

.search-tips li::before {
    content: '✓';
    color: var(--color-primary);
    font-weight: 600;
}

.category-list {
    list-style: none;
}

.category-item {
    margin-bottom: var(--spacing-xs);
}

.category-link {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 0.75rem var(--spacing-sm);
    background-color: var(--color-gray-50);
    border-radius: var(--radius-md);
    color: var(--color-gray-700);
    text-decoration: none;
    transition: var(--transition-smooth);
}

.category-link:hover {
    background-color: var(--color-primary);
    color: var(--color-white);
    transform: translateX(5px);
}

.category-count {
    background-color: var(--color-white);
    color: var(--color-primary);
    padding: 0.125rem 0.5rem;
    border-radius: var(--radius-full);
    font-size: 0.75rem;
    font-weight: 600;
}

.category-link:hover .category-count {
    background-color: var(--color-accent);
    color: var(--color-gray-900);
}

/* ==========================================================================
   EMPTY STATE
   ========================================================================== */
.empty-state {
    text-align: center;
    padding: var(--spacing-xxl) var(--spacing-md);
    background-color: var(--color-gray-50);
    border-radius: var(--radius-lg);
    margin: var(--spacing-xl) 0;
}

.empty-icon {
    font-size: 4rem;
    color: var(--color-gray-300);
    margin-bottom: var(--spacing-lg);
}

.empty-state h3 {
    font-size: 1.75rem;
    font-weight: 600;
    margin-bottom: var(--spacing-md);
    color: var(--color-gray-700);
}

.empty-state p {
    color: var(--color-gray-600);
    font-size: 1.125rem;
    margin-bottom: var(--spacing-lg);
    max-width: 600px;
    margin-left: auto;
    margin-right: auto;
}

/* ==========================================================================
   BUTTONS
   ========================================================================== */
.btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 0.75rem;
    padding: 1rem 2rem;
    border-radius: var(--radius-md);
    text-decoration: none;
    font-weight: 600;
    transition: var(--transition-smooth);
    border: 2px solid;
    font-family: var(--font-heading);
    cursor: pointer;
}

.btn-primary {
    background: var(--color-accent);
    color: var(--color-gray-900);
    border-color: var(--color-accent);
}

.btn-primary:hover {
    background: var(--color-accent-dark);
    transform: translateY(-3px);
    box-shadow: var(--shadow-soft);
}

.btn-outline {
    background: transparent;
    color: var(--color-primary);
    border-color: var(--color-primary);
}

.btn-outline:hover {
    background: var(--color-primary);
    color: var(--color-white);
    transform: translateY(-3px);
}

/* ==========================================================================
   PAGINATION
   ========================================================================== */
.pagination {
    display: flex;
    justify-content: center;
    align-items: center;
    gap: var(--spacing-xs);
    margin-top: var(--spacing-xl);
    flex-wrap: wrap;
}

.pagination-list {
    display: flex;
    gap: var(--spacing-xs);
    list-style: none;
}

.pagination-link {
    display: flex;
    align-items: center;
    justify-content: center;
    min-width: 40px;
    height: 40px;
    padding: 0 var(--spacing-sm);
    border-radius: var(--radius-md);
    background-color: var(--color-gray-100);
    color: var(--color-gray-700);
    font-weight: 500;
    transition: var(--transition-smooth);
    text-decoration: none;
    border: 1px solid var(--color-gray-200);
}

.pagination-link:hover:not(.active) {
    background-color: var(--color-primary);
    color: var(--color-white);
}

.pagination-link.active {
    background-color: var(--color-primary);
    color: var(--color-white);
}

/* ==========================================================================
   RESPONSIVE DESIGN
   ========================================================================== */
@media (max-width: 1024px) {
    .search-layout {
        grid-template-columns: 1fr;
    }
    
    .search-sidebar {
        position: static;
    }
}

@media (max-width: 768px) {
    .search-hero {
        min-height: 40vh;
    }
    
    .news-grid {
        grid-template-columns: 1fr;
    }
}

@media (max-width: 480px) {
    .search-hero-title {
        font-size: 1.75rem;
    }
    
    .news-search-input {
        padding: 1.1rem 4rem 1.1rem 1.5rem;
        font-size: 1rem;
    }
    
    .news-search-button {
        width: 45px;
        height: 45px;
    }
}

/* Accessibility */
:focus-visible {
    outline: 3px solid var(--color-accent);
    outline-offset: 3px;
}

@media (prefers-reduced-motion: reduce) {
    * {
        animation-duration: 0.01ms !important;
        transition-duration: 0.01ms !important;
    }
}
    </style>
</head>
<body>

<main class="search-page">
    <!-- Search Hero -->
    <section class="search-hero" aria-label="Search Results">
        <div class="search-hero-bg"></div>
        <div class="hero-container">
            <div class="search-hero-content">
                <span class="search-hero-badge">Search Results</span>
                <h1 class="search-hero-title">Find What You're Looking For</h1>
                
                <?php if (!empty($searchQuery)): ?>
                <div class="search-query-display">
                    Searching for: <strong>"<?php echo htmlspecialchars($searchQuery); ?>"</strong>
                </div>
                <?php else: ?>
                <p class="search-hero-subtitle">Enter a search term to find news articles</p>
                <?php endif; ?>
                
                <!-- Search Form -->
                <div class="news-search">
                    <form class="news-search-form" action="<?php echo $baseUrl; ?>/news/search" method="GET">
                        <input type="search" 
                               name="q" 
                               class="news-search-input" 
                               placeholder="Search news articles..." 
                               aria-label="Search news articles"
                               autocomplete="off"
                               value="<?php echo htmlspecialchars($searchQuery); ?>"
                               required>
                        <button type="submit" class="news-search-button" aria-label="Search">
                            <i class="fas fa-search"></i>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <!-- Main Content -->
    <div class="container">
        <div class="search-layout">
            <!-- Main Results -->
            <div>
                <?php if (!empty($news)): ?>
                    <!-- Results Info -->
                    <div class="results-info">
                        <i class="fas fa-info-circle"></i>
                        <div class="results-info-text">
                            <div class="results-count">
                                Found <?php echo $pagination['totalCount'] ?? count($news); ?> result(s)
                            </div>
                            <?php if (!empty($searchQuery)): ?>
                            <div style="font-size: 0.875rem; color: var(--color-gray-600); margin-top: 0.25rem;">
                                for "<?php echo htmlspecialchars($searchQuery); ?>"
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                    
                    <!-- News Grid -->
                    <div class="news-grid">
                        <?php foreach ($news as $item): ?>
                        <article class="news-card">
                            <?php if (!empty($item['featured_image'])): ?>
                            <div class="news-image-container">
                                <img src="<?php echo $baseUrl . htmlspecialchars($item['featured_image']); ?>" 
                                     alt="<?php echo htmlspecialchars($item['title'] ?? ''); ?>" 
                                     class="news-image"
                                     loading="lazy">
                            </div>
                            <?php endif; ?>
                            
                            <div class="news-content">
                                <?php if (!empty($item['category'])): ?>
                                <span class="news-category"><?php echo htmlspecialchars($item['category']); ?></span>
                                <?php endif; ?>
                                
                                <h3 class="news-title">
                                    <a href="<?php echo $baseUrl; ?>/news/<?php echo $item['slug']; ?>">
                                        <?php echo htmlspecialchars($item['title'] ?? ''); ?>
                                    </a>
                                </h3>
                                
                                <?php if (!empty($item['excerpt'])): ?>
                                <p class="news-excerpt">
                                    <?php 
                                    $excerpt = strip_tags($item['excerpt']);
                                    echo htmlspecialchars(strlen($excerpt) > 150 ? substr($excerpt, 0, 150) . '...' : $excerpt);
                                    ?>
                                </p>
                                <?php endif; ?>
                                
                                <div class="news-meta">
                                    <div class="meta-item">
                                        <i class="far fa-calendar"></i>
                                        <span><?php echo !empty($item['created_at']) ? date('M j, Y', strtotime($item['created_at'])) : ''; ?></span>
                                    </div>
                                    <?php if (!empty($item['views_count'])): ?>
                                    <div class="meta-item">
                                        <i class="far fa-eye"></i>
                                        <span><?php echo number_format($item['views_count']); ?></span>
                                    </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </article>
                        <?php endforeach; ?>
                    </div>

                    <!-- Pagination -->
                    <?php if (($pagination['total'] ?? 0) > 1): ?>
                    <nav class="pagination" aria-label="Search results pagination">
                        <ul class="pagination-list">
                            <?php if (($pagination['current'] ?? 1) > 1): ?>
                            <li>
                                <a href="?q=<?php echo urlencode($searchQuery); ?>&page=<?php echo ($pagination['current'] ?? 1) - 1; ?>" 
                                   class="pagination-link">
                                    <i class="fas fa-chevron-left"></i>
                                </a>
                            </li>
                            <?php endif; ?>
                            
                            <?php
                            $start = max(1, ($pagination['current'] ?? 1) - 2);
                            $end = min(($pagination['total'] ?? 1), ($pagination['current'] ?? 1) + 2);
                            
                            for ($i = $start; $i <= $end; $i++):
                            ?>
                            <li>
                                <a href="?q=<?php echo urlencode($searchQuery); ?>&page=<?php echo $i; ?>" 
                                   class="pagination-link <?php echo $i == ($pagination['current'] ?? 1) ? 'active' : ''; ?>">
                                    <?php echo $i; ?>
                                </a>
                            </li>
                            <?php endfor; ?>
                            
                            <?php if (($pagination['current'] ?? 1) < ($pagination['total'] ?? 1)): ?>
                            <li>
                                <a href="?q=<?php echo urlencode($searchQuery); ?>&page=<?php echo ($pagination['current'] ?? 1) + 1; ?>" 
                                   class="pagination-link">
                                    <i class="fas fa-chevron-right"></i>
                                </a>
                            </li>
                            <?php endif; ?>
                        </ul>
                    </nav>
                    <?php endif; ?>
                <?php else: ?>
                    <!-- Empty State -->
                    <div class="empty-state">
                        <div class="empty-icon">
                            <i class="fas fa-search"></i>
                        </div>
                        <h3>No Results Found</h3>
                        <p>
                            <?php if (!empty($searchQuery)): ?>
                                No articles found for "<?php echo htmlspecialchars($searchQuery); ?>". 
                                Try different keywords or browse our categories.
                            <?php else: ?>
                                Please enter a search term to find news articles.
                            <?php endif; ?>
                        </p>
                        <a href="<?php echo $baseUrl; ?>/news" class="btn btn-primary">
                            <i class="fas fa-arrow-left"></i> Back to All News
                        </a>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Sidebar -->
            <aside class="search-sidebar" aria-label="Search sidebar">
                <!-- Search Tips -->
                <div class="sidebar-widget">
                    <h3 class="widget-title">
                        <i class="fas fa-lightbulb"></i> Search Tips
                    </h3>
                    <ul class="search-tips">
                        <li>Use specific keywords</li>
                        <li>Try different terms</li>
                        <li>Check your spelling</li>
                        <li>Browse by category</li>
                    </ul>
                </div>

                <!-- Categories -->
                <?php if (!empty($categories)): ?>
                <div class="sidebar-widget">
                    <h3 class="widget-title">
                        <i class="fas fa-folder"></i> Browse Categories
                    </h3>
                    <ul class="category-list">
                        <?php foreach ($categories as $cat => $count): ?>
                        <li class="category-item">
                            <a href="<?php echo $baseUrl; ?>/news/category/<?php echo urlencode(strtolower(str_replace(' ', '-', $cat))); ?>" 
                               class="category-link">
                                <span><?php echo htmlspecialchars($cat); ?></span>
                                <span class="category-count"><?php echo $count; ?></span>
                            </a>
                        </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
                <?php endif; ?>
            </aside>
        </div>
    </div>
</main>

<script>
document.addEventListener('DOMContentLoaded', function() {
    console.log('Search page loaded');
    
    // Ensure search input is interactive
    const searchInput = document.querySelector('.news-search-input');
    if (searchInput) {
        searchInput.style.pointerEvents = 'auto';
        searchInput.style.cursor = 'text';
        
        searchInput.addEventListener('click', function(e) {
            e.stopPropagation();
            this.focus();
        });
    }
    
    // Highlight search terms in results (optional enhancement)
    const searchQuery = '<?php echo addslashes($searchQuery); ?>';
    if (searchQuery) {
        const cards = document.querySelectorAll('.news-card');
        cards.forEach(card => {
            const title = card.querySelector('.news-title a');
            const excerpt = card.querySelector('.news-excerpt');
            
            if (title && searchQuery.length > 2) {
                const titleText = title.textContent;
                const regex = new RegExp(`(${searchQuery})`, 'gi');
                title.innerHTML = titleText.replace(regex, '<mark style="background: var(--color-accent-light); padding: 0 0.25rem;">$1</mark>');
            }
        });
    }
});
</script>

</body>
</html>