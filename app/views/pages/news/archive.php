<?php
// Ensure required variables exist
$baseUrl = $baseUrl ?? '';
$news = $news ?? [];
$archiveTitle = $archiveTitle ?? '';
$categories = $categories ?? [];
$archiveMonths = $archiveMonths ?? [];
$currentPage = $currentPage ?? 'news';
$pagination = $pagination ?? [];
$pageTitle = $pageTitle ?? 'Archive News';
$pageDescription = $pageDescription ?? '';

// Breadcrumb
$breadcrumb = [
    ['label' => 'Home', 'url' => $baseUrl],
    ['label' => 'News', 'url' => $baseUrl . '/news'],
    ['label' => 'Archive: ' . $archiveTitle, 'url' => '']
];
?>

<!-- Include header -->
<?php include_once APP_PATH . '/views/layouts/header.php'; ?>

<main class="main-content">
    <!-- Breadcrumb -->
    <div class="breadcrumb">
        <div class="container">
            <ul class="breadcrumb-list">
                <?php foreach ($breadcrumb as $index => $item): ?>
                    <li class="breadcrumb-item">
                        <?php if (!empty($item['url'])): ?>
                            <a href="<?php echo $item['url']; ?>" class="breadcrumb-link">
                                <?php echo $item['label']; ?>
                            </a>
                        <?php else: ?>
                            <span class="breadcrumb-current"><?php echo $item['label']; ?></span>
                        <?php endif; ?>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
    </div>

    <!-- Archive News Section -->
    <section class="section news-section">
        <div class="container">
            <div class="news-header">
                <h1 class="section-title">News from <?php echo htmlspecialchars($archiveTitle); ?></h1>
                <p class="section-description">
                    Browse news articles published in <?php echo htmlspecialchars($archiveTitle); ?>.
                </p>
            </div>

            <div class="news-container">
                <!-- Main News Grid -->
                <div class="news-main">
                    <?php if (!empty($news)): ?>
                        <div class="news-grid">
                            <?php foreach ($news as $item): ?>
                                <article class="news-card">
                                    <?php if (!empty($item['featured_image'])): ?>
                                        <div class="news-image">
                                            <img src="<?php echo $item['featured_image']; ?>" 
                                                 alt="<?php echo htmlspecialchars($item['title'] ?? ''); ?>">
                                        </div>
                                    <?php endif; ?>
                                    
                                    <?php if (!empty($item['category'])): ?>
                                        <span class="news-category"><?php echo htmlspecialchars($item['category']); ?></span>
                                    <?php endif; ?>
                                    
                                    <h3 class="news-title">
                                        <a href="<?php echo $baseUrl; ?>/news/<?php echo $item['slug']; ?>">
                                            <?php echo htmlspecialchars($item['title'] ?? ''); ?>
                                        </a>
                                    </h3>
                                    
                                    <?php if (!empty($item['excerpt'])): ?>
                                        <p class="news-excerpt"><?php echo htmlspecialchars($item['excerpt']); ?></p>
                                    <?php endif; ?>
                                    
                                    <div class="news-meta">
                                        <span class="news-date">
                                            <i class="far fa-calendar"></i> 
                                            <?php echo !empty($item['created_at']) ? date('M j, Y', strtotime($item['created_at'])) : ''; ?>
                                        </span>
                                        <?php if (!empty($item['views_count'])): ?>
                                            <span class="news-views">
                                                <i class="far fa-eye"></i> <?php echo number_format($item['views_count']); ?>
                                            </span>
                                        <?php endif; ?>
                                    </div>
                                </article>
                            <?php endforeach; ?>
                        </div>

                        <!-- Pagination -->
                        <?php if (($pagination['total'] ?? 0) > 1): ?>
                            <div class="pagination">
                                <?php if (($pagination['current'] ?? 1) > 1): ?>
                                    <a href="?page=<?php echo ($pagination['current'] ?? 1) - 1; ?>" class="pagination-btn">
                                        <i class="fas fa-chevron-left"></i> Previous
                                    </a>
                                <?php endif; ?>
                                
                                <span class="pagination-info">
                                    Page <?php echo $pagination['current'] ?? 1; ?> of <?php echo $pagination['total'] ?? 1; ?>
                                </span>
                                
                                <?php if (($pagination['current'] ?? 1) < ($pagination['total'] ?? 1)): ?>
                                    <a href="?page=<?php echo ($pagination['current'] ?? 1) + 1; ?>" class="pagination-btn">
                                        Next <i class="fas fa-chevron-right"></i>
                                    </a>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>
                    <?php else: ?>
                        <div class="empty-state">
                            <i class="fas fa-newspaper empty-icon"></i>
                            <h3>No news found for this period</h3>
                            <p>No articles were published in <?php echo htmlspecialchars($archiveTitle); ?>.</p>
                            <a href="<?php echo $baseUrl; ?>/news" class="btn btn-primary">
                                <i class="fas fa-arrow-left"></i> Back to All News
                            </a>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Sidebar -->
                <aside class="news-sidebar">
                    <!-- Categories -->
                    <?php if (!empty($categories)): ?>
                        <div class="sidebar-widget">
                            <h3 class="widget-title">Categories</h3>
                            <ul class="categories-list">
                                <?php foreach ($categories as $cat => $count): ?>
                                    <li class="category-item">
                                        <a href="<?php echo $baseUrl; ?>/news/category/<?php echo urlencode(strtolower($cat)); ?>" 
                                           class="category-link">
                                            <?php echo htmlspecialchars($cat); ?>
                                            <span class="category-count">(<?php echo $count; ?>)</span>
                                        </a>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    <?php endif; ?>

                    <!-- Archive -->
                    <?php if (!empty($archiveMonths)): ?>
                        <div class="sidebar-widget">
                            <h3 class="widget-title">Archive</h3>
                            <ul class="archive-list">
                                <?php foreach ($archiveMonths as $archive): ?>
                                    <li class="archive-item">
                                        <a href="<?php echo $baseUrl; ?>/news/archive/<?php echo str_replace('-', '/', $archive['month']); ?>" 
                                           class="archive-link">
                                            <?php echo htmlspecialchars($archive['month_name'] ?? $archive['month']); ?>
                                            <span class="archive-count">(<?php echo $archive['count']; ?>)</span>
                                        </a>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    <?php endif; ?>
                </aside>
            </div>
        </div>
    </section>
</main>

<!-- Include footer -->
<?php include_once APP_PATH . '/views/layouts/footer.php'; ?>