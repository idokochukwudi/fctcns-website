<?php
// VIEW FILE ONLY - displays data passed from Controller
// Variables available from Controller:
// - $news: array of news articles
// - $stats: array of statistics
// - $categories: array of categories  
// - $authors: array of authors
// - $filters: array of filter parameters
// - $pagination: array of pagination info
// - $pageTitle: string
// - $pageDescription: string
// - $baseUrl: string
// - $currentPage: string
// - $user: array with user info (from $_SESSION)
// - $flash_success: success message
// - $flash_error: error message
?>

<!-- This is a PARTIAL view - only content, no HTML structure -->
<!-- It will be inserted into the news_admin.php layout -->

<div class="news-content-container">
    <!-- Header -->
    <div class="header">
        <h1>��� News Management</h1>
        <div class="btn-group">
            <a href="<?php echo $baseUrl; ?>/admin/dashboard" class="btn btn-secondary">
                ← Dashboard
            </a>
            <?php if (in_array($user['user_role'] ?? '', ['admin', 'editor'])): ?>
            <a href="<?php echo $baseUrl; ?>/admin/news/create" class="btn btn-primary">
                ＋ New Article
            </a>
            <?php endif; ?>
        </div>
    </div>

    <!-- Flash Messages -->
    <?php if (!empty($flash_success)): ?>
        <div class="alert alert-success">
            <?php echo htmlspecialchars($flash_success); ?>
        </div>
    <?php endif; ?>
    
    <?php if (!empty($flash_error)): ?>
        <div class="alert alert-danger">
            <?php echo htmlspecialchars($flash_error); ?>
        </div>
    <?php endif; ?>

    <!-- Statistics -->
    <div class="stats-grid">
        <?php if (!empty($stats)): ?>
        <div class="stat-card total">
            <div class="stat-value"><?php echo $stats['total_news'] ?? 0; ?></div>
            <div class="stat-label">Total News</div>
        </div>

        <div class="stat-card published">
            <div class="stat-value"><?php echo $stats['published_news'] ?? 0; ?></div>
            <div class="stat-label">Published</div>
        </div>

        <div class="stat-card draft">
            <div class="stat-value"><?php echo $stats['draft_news'] ?? 0; ?></div>
            <div class="stat-label">Drafts</div>
        </div>

        <div class="stat-card featured">
            <div class="stat-value"><?php echo $stats['featured_news'] ?? 0; ?></div>
            <div class="stat-label">Featured</div>
        </div>
        <?php else: ?>
        <p>No statistics available</p>
        <?php endif; ?>
    </div>

    <!-- News Table -->
    <?php if (!empty($news) && is_array($news)): ?>
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>Category</th>
                        <th>Status</th>
                        <th>Views</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($news as $article): ?>
                    <tr>
                        <td>
                            <div class="news-title">
                                <?php echo htmlspecialchars($article['title'] ?? ''); ?>
                                <?php if (!empty($article['is_featured'])): ?>
                                <span style="color: #e53e3e; font-size: 0.875rem;">[Featured]</span>        
                                <?php endif; ?>
                            </div>
                            <div class="news-excerpt">
                                <?php echo htmlspecialchars(substr($article['excerpt'] ?? '', 0, 100)); ?>...
                            </div>
                        </td>
                        <td><?php echo htmlspecialchars($article['category'] ?? 'Uncategorized'); ?></td>   
                        <td>
                            <?php if (!empty($article['is_published'])): ?>
                                <span style="background: #38a169; color: white; padding: 4px 12px; border-radius: 20px; font-size: 0.75rem;">
                                    Published
                                </span>
                            <?php else: ?>
                                <span style="background: #d69e2e; color: white; padding: 4px 12px; border-radius: 20px; font-size: 0.75rem;">
                                    Draft
                                </span>
                            <?php endif; ?>
                        </td>
                        <td><?php echo number_format($article['views_count'] ?? 0); ?></td>
                        <td>
                            <div class="actions">
                                <a href="<?php echo $baseUrl; ?>/admin/news/<?php echo $article['id'] ?? ''; ?>" class="btn btn-sm btn-info">
                                    View
                                </a>
                                <?php if (in_array($user['user_role'] ?? '', ['admin', 'editor'])): ?>
                                <a href="<?php echo $baseUrl; ?>/admin/news/<?php echo $article['id'] ?? ''; ?>/edit" class="btn btn-sm btn-primary">
                                    Edit
                                </a>
                                <?php endif; ?>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <!-- Pagination -->
            <?php if (!empty($pagination) && $pagination['total'] > 1): ?>
            <div class="pagination">
                <?php if ($pagination['current'] > 1): ?>
                <a href="?page=<?php echo $pagination['current'] - 1; ?>" class="pagination-btn">Previous</a>
                <?php endif; ?>

                <?php for ($i = 1; $i <= $pagination['total']; $i++): ?>
                <a href="?page=<?php echo $i; ?>" class="pagination-btn <?php echo $i == $pagination['current'] ? 'active' : ''; ?>">
                    <?php echo $i; ?>
                </a>
                <?php endfor; ?>

                <?php if ($pagination['current'] < $pagination['total']): ?>
                <a href="?page=<?php echo $pagination['current'] + 1; ?>" class="pagination-btn">Next</a>   
                <?php endif; ?>
            </div>
            <?php endif; ?>
        </div>
    <?php else: ?>
        <div class="empty-state">
            <h3>No News Articles Found</h3>
            <p>There are no news articles in the database yet.</p>
            <?php if (in_array($user['user_role'] ?? '', ['admin', 'editor'])): ?>
            <a href="<?php echo $baseUrl; ?>/admin/news/create" class="btn btn-primary">
                ＋ Create First Article
            </a>
            <?php endif; ?>
        </div>
    <?php endif; ?>
</div>

<style>
    /* Minimal styles for this view only */
    .news-content-container { max-width: 1200px; margin: 0 auto; padding: 20px; }
    .header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px; }    
    .btn { padding: 10px 20px; border-radius: 6px; text-decoration: none; display: inline-block; }
    .btn-primary { background: #2c5282; color: white; }
    .btn-secondary { background: #718096; color: white; }
    .btn-info { background: #4299e1; color: white; }
    .btn-sm { padding: 6px 12px; font-size: 0.875rem; }
    .stats-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 20px; margin-bottom: 30px; }   
    .stat-card { background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); border-left: 4px solid; }
    .stat-card.total { border-left-color: #2c5282; }
    .stat-card.published { border-left-color: #38a169; }
    .stat-card.draft { border-left-color: #d69e2e; }
    .stat-card.featured { border-left-color: #e53e3e; }
    .stat-value { font-size: 2rem; font-weight: bold; margin-bottom: 5px; }
    .stat-label { font-size: 0.875rem; color: #718096; }
    .table-container { background: white; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
    table { width: 100%; border-collapse: collapse; }
    th { background: #f7fafc; padding: 15px; text-align: left; font-weight: 600; }
    td { padding: 15px; border-bottom: 1px solid #e2e8f0; }
    .news-title { font-weight: 600; margin-bottom: 5px; }
    .news-excerpt { font-size: 0.875rem; color: #718096; }
    .actions { display: flex; gap: 8px; }
    .alert { padding: 15px; border-radius: 6px; margin-bottom: 20px; }
    .alert-success { background: #c6f6d5; color: #22543d; border: 1px solid #9ae6b4; }
    .alert-danger { background: #fed7d7; color: #742a2a; border: 1px solid #fc8181; }
    .pagination { display: flex; gap: 8px; justify-content: center; margin-top: 20px; }
    .pagination-btn { padding: 8px 16px; background: white; border: 1px solid #e2e8f0; border-radius: 4px; text-decoration: none; }
    .pagination-btn.active { background: #2c5282; color: white; border-color: #2c5282; }
    .empty-state { text-align: center; padding: 40px; color: #718096; }
</style>
