<?php
// Get the absolute path to the root
$rootPath = dirname(__DIR__, 3);
require_once $rootPath . '/app/config/constants.php';
require_once APP_PATH . '/config/session.php';
require_once APP_PATH . '/middleware/AuthMiddleware.php';
AuthMiddleware::authenticate();

$userRole = $_SESSION['user_role'] ?? 'viewer';
$currentUserId = $_SESSION['user_id'] ?? 0;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>News Management - FCT CNS Admin</title>
    <style>
        :root {
            --primary: #2c5282;
            --primary-dark: #1a365d;
            --success: #38a169;
            --warning: #d69e2e;
            --danger: #e53e3e;
            --info: #4299e1;
            --gray-50: #f7fafc;
            --gray-100: #edf2f7;
            --gray-200: #e2e8f0;
            --gray-600: #718096;
            --gray-700: #4a5568;
            --gray-800: #2d3748;
        }
        
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background-color: var(--gray-100);
            margin: 0;
            padding: 0;
        }
        
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }
        
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            flex-wrap: wrap;
            gap: 15px;
        }
        
        .header h1 {
            color: var(--gray-800);
            margin: 0;
            font-size: 1.75rem;
        }
        
        .btn {
            padding: 10px 20px;
            border-radius: 6px;
            text-decoration: none;
            font-weight: 500;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            cursor: pointer;
            border: none;
            transition: all 0.2s;
        }
        
        .btn-primary {
            background: var(--primary);
            color: white;
        }
        
        .btn-secondary {
            background: var(--gray-600);
            color: white;
        }
        
        .btn-success {
            background: var(--success);
            color: white;
        }
        
        .btn-warning {
            background: var(--warning);
            color: white;
        }
        
        .btn-danger {
            background: var(--danger);
            color: white;
        }
        
        .btn-info {
            background: var(--info);
            color: white;
        }
        
        .btn:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }
        
        .btn-primary:hover { background: var(--primary-dark); }
        .btn-secondary:hover { background: var(--gray-700); }
        
        .btn-group {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }
        
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }
        
        .stat-card {
            background: white;
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            border-left: 4px solid var(--primary);
        }
        
        .stat-card.published { border-left-color: var(--success); }
        .stat-card.draft { border-left-color: var(--warning); }
        .stat-card.featured { border-left-color: var(--danger); }
        .stat-card.views { border-left-color: var(--info); }
        
        .stat-value {
            font-size: 2rem;
            font-weight: 700;
            color: var(--gray-800);
            line-height: 1;
            margin-bottom: 5px;
        }
        
        .stat-label {
            font-size: 0.875rem;
            color: var(--gray-600);
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }
        
        .filters {
            background: white;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }
        
        .filter-row {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
            margin-bottom: 15px;
        }
        
        .filter-group {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }
        
        .filter-group label {
            font-weight: 500;
            color: var(--gray-700);
            font-size: 0.875rem;
        }
        
        .filter-group select, .filter-group input {
            padding: 10px;
            border: 1px solid var(--gray-200);
            border-radius: 6px;
            font-size: 14px;
            width: 100%;
            box-sizing: border-box;
        }
        
        .table-container {
            background: white;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            margin-bottom: 30px;
            overflow-x: auto;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            min-width: 1000px;
        }
        
        th, td {
            padding: 15px;
            text-align: left;
            border-bottom: 1px solid var(--gray-200);
        }
        
        th {
            background: var(--gray-50);
            font-weight: 600;
            color: var(--gray-700);
            text-transform: uppercase;
            font-size: 0.75rem;
            letter-spacing: 0.05em;
        }
        
        tr:hover {
            background: var(--gray-50);
        }
        
        .badge {
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 600;
            display: inline-block;
        }
        
        .badge-published { 
            background: rgba(56, 161, 105, 0.1); 
            color: var(--success); 
            border: 1px solid rgba(56, 161, 105, 0.2);
        }
        
        .badge-draft { 
            background: rgba(214, 158, 46, 0.1); 
            color: var(--warning); 
            border: 1px solid rgba(214, 158, 46, 0.2);
        }
        
        .badge-archived { 
            background: rgba(113, 128, 150, 0.1); 
            color: var(--gray-600); 
            border: 1px solid rgba(113, 128, 150, 0.2);
        }
        
        .badge-featured {
            background: rgba(229, 62, 62, 0.1);
            color: var(--danger);
            border: 1px solid rgba(229, 62, 62, 0.2);
        }
        
        .badge-breaking {
            background: rgba(245, 101, 101, 0.1);
            color: #c53030;
            border: 1px solid rgba(245, 101, 101, 0.2);
        }
        
        .news-title {
            font-weight: 600;
            color: var(--gray-800);
            margin-bottom: 5px;
            line-height: 1.4;
        }
        
        .news-excerpt {
            font-size: 0.875rem;
            color: var(--gray-600);
            line-height: 1.5;
            margin: 5px 0;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
        
        .news-meta {
            display: flex;
            align-items: center;
            gap: 15px;
            font-size: 0.75rem;
            color: var(--gray-500);
            margin-top: 8px;
            flex-wrap: wrap;
        }
        
        .news-meta span {
            display: flex;
            align-items: center;
            gap: 4px;
        }
        
        .actions {
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
        }
        
        .action-btn {
            padding: 6px 12px;
            border-radius: 4px;
            font-size: 0.75rem;
            text-decoration: none;
            border: 1px solid transparent;
            transition: all 0.2s;
            white-space: nowrap;
        }
        
        .action-view { background: var(--info); color: white; }
        .action-edit { background: var(--primary); color: white; }
        .action-publish { background: var(--success); color: white; }
        .action-unpublish { background: var(--warning); color: white; }
        .action-delete { background: var(--danger); color: white; }
        .action-feature { background: #9f7aea; color: white; }
        
        .action-btn:hover {
            transform: translateY(-1px);
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }
        
        .empty-state {
            text-align: center;
            padding: 60px 20px;
            color: var(--gray-600);
        }
        
        .empty-state h3 {
            margin-bottom: 10px;
            color: var(--gray-700);
        }
        
        .empty-state p {
            margin-bottom: 20px;
            max-width: 400px;
            margin-left: auto;
            margin-right: auto;
        }
        
        .pagination {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 10px;
            margin-top: 30px;
        }
        
        .pagination-btn {
            padding: 8px 16px;
            border: 1px solid var(--gray-200);
            background: white;
            border-radius: 4px;
            text-decoration: none;
            color: var(--gray-700);
            transition: all 0.2s;
        }
        
        .pagination-btn.active {
            background: var(--primary);
            color: white;
            border-color: var(--primary);
        }
        
        .pagination-btn:hover:not(.active) {
            background: var(--gray-50);
        }
        
        .mobile-only { display: none; }
        
        @media (max-width: 768px) {
            .header { flex-direction: column; align-items: stretch; }
            .btn-group { justify-content: center; }
            .desktop-only { display: none; }
            .mobile-only { display: block; }
            
            th, td {
                padding: 12px 10px;
            }
            
            .table-container {
                border-radius: 0;
                margin: 0 -20px;
            }
        }
        
        @media (max-width: 640px) {
            .stats-grid {
                grid-template-columns: 1fr;
            }
            
            .filter-row {
                grid-template-columns: 1fr;
            }
        }
        
        .status-indicator {
            display: inline-block;
            width: 8px;
            height: 8px;
            border-radius: 50%;
            margin-right: 6px;
        }
        
        .status-published { background: var(--success); }
        .status-draft { background: var(--warning); }
        .status-archived { background: var(--gray-500); }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>📰 News Management</h1>
            <div class="btn-group">
                <a href="<?php echo BASE_URL; ?>/admin/dashboard" class="btn btn-secondary">
                    ← Dashboard
                </a>
                <?php if (in_array($userRole, ['admin', 'editor'])): ?>
                <a href="<?php echo BASE_URL; ?>/admin/news/create" class="btn btn-primary">
                    ＋ New Article
                </a>
                <?php endif; ?>
                <button class="btn btn-info" onclick="toggleFilters()">
                    🔍 Filters
                </button>
            </div>
        </div>
        
        <!-- Statistics -->
        <div class="stats-grid">
            <?php
            $publishedCount = 0;
            $draftCount = 0;
            $featuredCount = 0;
            $totalViews = 0;
            
            foreach ($news as $article) {
                if ($article['is_published']) $publishedCount++;
                else $draftCount++;
                if ($article['is_featured']) $featuredCount++;
                $totalViews += $article['views_count'];
            }
            ?>
            
            <div class="stat-card published">
                <div class="stat-value"><?php echo $publishedCount; ?></div>
                <div class="stat-label">Published Articles</div>
            </div>
            
            <div class="stat-card draft">
                <div class="stat-value"><?php echo $draftCount; ?></div>
                <div class="stat-label">Draft Articles</div>
            </div>
            
            <div class="stat-card featured">
                <div class="stat-value"><?php echo $featuredCount; ?></div>
                <div class="stat-label">Featured Articles</div>
            </div>
            
            <div class="stat-card views">
                <div class="stat-value"><?php echo number_format($totalViews); ?></div>
                <div class="stat-label">Total Views</div>
            </div>
        </div>
        
        <!-- Filters -->
        <div class="filters" id="filtersSection" style="display: none;">
            <div class="filter-row">
                <div class="filter-group">
                    <label for="filterStatus">Status</label>
                    <select id="filterStatus">
                        <option value="">All Statuses</option>
                        <option value="published">Published</option>
                        <option value="draft">Draft</option>
                        <option value="archived">Archived</option>
                    </select>
                </div>
                
                <div class="filter-group">
                    <label for="filterCategory">Category</label>
                    <select id="filterCategory">
                        <option value="">All Categories</option>
                        <option value="Announcements">Announcements</option>
                        <option value="Research">Research</option>
                        <option value="Events">Events</option>
                        <option value="General">General</option>
                    </select>
                </div>
                
                <div class="filter-group">
                    <label for="filterFeatured">Featured</label>
                    <select id="filterFeatured">
                        <option value="">All</option>
                        <option value="featured">Featured Only</option>
                        <option value="not-featured">Not Featured</option>
                    </select>
                </div>
            </div>
            
            <div class="filter-row">
                <div class="filter-group">
                    <label for="filterSearch">Search</label>
                    <input type="text" id="filterSearch" placeholder="Search by title or content...">
                </div>
                
                <div class="filter-group">
                    <label for="filterDateFrom">Date From</label>
                    <input type="date" id="filterDateFrom">
                </div>
                
                <div class="filter-group">
                    <label for="filterDateTo">Date To</label>
                    <input type="date" id="filterDateTo">
                </div>
            </div>
            
            <div style="display: flex; justify-content: flex-end; gap: 10px; margin-top: 15px;">
                <button class="btn btn-secondary" onclick="clearFilters()">
                    Clear Filters
                </button>
                <button class="btn btn-primary" onclick="applyFilters()">
                    Apply Filters
                </button>
            </div>
        </div>
        
        <!-- News Table -->
        <div class="table-container">
            <?php if (!empty($news)): ?>
                <table>
                    <thead>
                        <tr>
                            <th>Article</th>
                            <th class="desktop-only">Category</th>
                            <th>Status</th>
                            <th class="desktop-only">Stats</th>
                            <th class="desktop-only">Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($news as $article): ?>
                        <tr data-status="<?php echo $article['is_published'] ? 'published' : 'draft'; ?>"
                            data-category="<?php echo htmlspecialchars($article['category'] ?? ''); ?>"
                            data-featured="<?php echo $article['is_featured'] ? 'featured' : 'not-featured'; ?>"
                            data-date="<?php echo date('Y-m-d', strtotime($article['created_at'])); ?>">
                            <td>
                                <div class="news-title">
                                    <?php echo htmlspecialchars($article['title']); ?>
                                    <?php if ($article['is_featured']): ?>
                                    <span class="badge badge-featured" style="margin-left: 8px;">Featured</span>
                                    <?php endif; ?>
                                    <?php if ($article['is_breaking']): ?>
                                    <span class="badge badge-breaking" style="margin-left: 8px;">Breaking</span>
                                    <?php endif; ?>
                                </div>
                                <div class="news-excerpt">
                                    <?php echo htmlspecialchars(substr($article['excerpt'] ?? $article['content'], 0, 150)); ?>...
                                </div>
                                <div class="news-meta">
                                    <span>
                                        <svg width="12" height="12" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"/>
                                        </svg>
                                        <?php echo htmlspecialchars($article['author_name'] ?? 'Unknown'); ?>
                                    </span>
                                    <span class="mobile-only">
                                        <svg width="12" height="12" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"/>
                                        </svg>
                                        <?php echo date('M d', strtotime($article['created_at'])); ?>
                                    </span>
                                </div>
                            </td>
                            <td class="desktop-only">
                                <?php if ($article['category']): ?>
                                <span style="font-size: 0.875rem; color: var(--gray-700);">
                                    <?php echo htmlspecialchars($article['category']); ?>
                                </span>
                                <?php else: ?>
                                <span style="color: var(--gray-500); font-style: italic;">Uncategorized</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if ($article['is_published']): ?>
                                <span class="badge badge-published">
                                    <span class="status-indicator status-published"></span>
                                    Published
                                </span>
                                <?php else: ?>
                                <span class="badge badge-draft">
                                    <span class="status-indicator status-draft"></span>
                                    Draft
                                </span>
                                <?php endif; ?>
                                <div class="mobile-only" style="font-size: 0.75rem; color: var(--gray-600); margin-top: 4px;">
                                    Views: <?php echo number_format($article['views_count']); ?>
                                </div>
                            </td>
                            <td class="desktop-only">
                                <div style="font-size: 0.875rem;">
                                    <div>👁️ <?php echo number_format($article['views_count']); ?> views</div>
                                    <div>👍 <?php echo number_format($article['likes_count']); ?> likes</div>
                                    <div>💬 <?php echo number_format($article['comments_count']); ?> comments</div>
                                </div>
                            </td>
                            <td class="desktop-only">
                                <div style="font-size: 0.875rem;">
                                    <div><?php echo date('M d, Y', strtotime($article['created_at'])); ?></div>
                                    <div style="color: var(--gray-500); font-size: 0.75rem;">
                                        <?php echo date('H:i', strtotime($article['created_at'])); ?>
                                    </div>
                                    <?php if ($article['published_at']): ?>
                                    <div style="color: var(--success); font-size: 0.75rem; margin-top: 4px;">
                                        Published: <?php echo date('M d', strtotime($article['published_at'])); ?>
                                    </div>
                                    <?php endif; ?>
                                </div>
                            </td>
                            <td>
                                <div class="actions">
                                    <a href="#" class="action-btn action-view">View</a>
                                    
                                    <?php if (in_array($userRole, ['admin', 'editor']) && 
                                             ($article['author_id'] == $currentUserId || $userRole == 'admin')): ?>
                                    <a href="#" class="action-btn action-edit">Edit</a>
                                    
                                    <?php if (!$article['is_published']): ?>
                                    <a href="#" class="action-btn action-publish">Publish</a>
                                    <?php else: ?>
                                    <a href="#" class="action-btn action-unpublish">Unpublish</a>
                                    <?php endif; ?>
                                    
                                    <a href="#" class="action-btn action-delete">Delete</a>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                
                <!-- Pagination -->
                <div class="pagination">
                    <a href="#" class="pagination-btn">« Previous</a>
                    <a href="#" class="pagination-btn active">1</a>
                    <a href="#" class="pagination-btn">2</a>
                    <a href="#" class="pagination-btn">3</a>
                    <a href="#" class="pagination-btn">4</a>
                    <a href="#" class="pagination-btn">5</a>
                    <a href="#" class="pagination-btn">Next »</a>
                </div>
            <?php else: ?>
                <div class="empty-state">
                    <h3>No News Articles Found</h3>
                    <p>There are no news articles in the database yet. Create your first article to get started.</p>
                    <?php if (in_array($userRole, ['admin', 'editor'])): ?>
                    <a href="<?php echo BASE_URL; ?>/admin/news/create" class="btn btn-primary">
                        ＋ Create First Article
                    </a>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </div>
        
        <!-- Quick Actions -->
        <?php if (in_array($userRole, ['admin', 'editor'])): ?>
        <div style="background: #f0f9ff; border: 1px solid #bee3f8; border-radius: 8px; padding: 20px; margin-top: 30px;">
            <h3 style="margin-top: 0; color: var(--primary);">🚀 Quick Actions</h3>
            <div style="display: flex; gap: 15px; flex-wrap: wrap;">
                <a href="<?php echo BASE_URL; ?>/admin/news/create" class="btn btn-primary">
                    ＋ Create New Article
                </a>
                <button class="btn btn-success" onclick="bulkPublish()">
                    📤 Bulk Publish Selected
                </button>
                <button class="btn btn-warning" onclick="exportNews()">
                    📥 Export to CSV
                </button>
                <button class="btn btn-info" onclick="schedulePosts()">
                    ⏰ Schedule Posts
                </button>
            </div>
        </div>
        <?php endif; ?>
    </div>
    
    <script>
        // Toggle filters visibility
        function toggleFilters() {
            const filters = document.getElementById('filtersSection');
            filters.style.display = filters.style.display === 'none' ? 'block' : 'none';
        }
        
        // Apply filters
        function applyFilters() {
            const status = document.getElementById('filterStatus').value;
            const category = document.getElementById('filterCategory').value;
            const featured = document.getElementById('filterFeatured').value;
            const search = document.getElementById('filterSearch').value.toLowerCase();
            const dateFrom = document.getElementById('filterDateFrom').value;
            const dateTo = document.getElementById('filterDateTo').value;
            
            const rows = document.querySelectorAll('tbody tr');
            
            rows.forEach(row => {
                let show = true;
                
                // Status filter
                if (status && row.dataset.status !== status) {
                    show = false;
                }
                
                // Category filter
                if (category && row.dataset.category !== category) {
                    show = false;
                }
                
                // Featured filter
                if (featured && row.dataset.featured !== featured) {
                    show = false;
                }
                
                // Search filter
                if (search) {
                    const text = row.textContent.toLowerCase();
                    if (!text.includes(search)) {
                        show = false;
                    }
                }
                
                // Date range filter
                if (dateFrom && row.dataset.date < dateFrom) {
                    show = false;
                }
                if (dateTo && row.dataset.date > dateTo) {
                    show = false;
                }
                
                row.style.display = show ? '' : 'none';
            });
        }
        
        // Clear filters
        function clearFilters() {
            document.getElementById('filterStatus').value = '';
            document.getElementById('filterCategory').value = '';
            document.getElementById('filterFeatured').value = '';
            document.getElementById('filterSearch').value = '';
            document.getElementById('filterDateFrom').value = '';
            document.getElementById('filterDateTo').value = '';
            
            const rows = document.querySelectorAll('tbody tr');
            rows.forEach(row => {
                row.style.display = '';
            });
        }
        
        // Bulk actions
        function bulkPublish() {
            alert('Bulk publish functionality would be implemented here');
        }
        
        function exportNews() {
            alert('Export to CSV functionality would be implemented here');
        }
        
        function schedulePosts() {
            alert('Post scheduling functionality would be implemented here');
        }
        
        // Auto-refresh news stats every 2 minutes
        setInterval(function() {
            // In a real implementation, this would fetch updated stats via AJAX
            console.log('Auto-refreshing news statistics...');
        }, 2 * 60 * 1000);
        
        // Quick status toggle
        document.addEventListener('click', function(e) {
            if (e.target.classList.contains('action-publish')) {
                e.preventDefault();
                if (confirm('Publish this article?')) {
                    // In a real implementation, this would make an API call
                    e.target.textContent = 'Publishing...';
                    setTimeout(() => {
                        alert('Article published successfully!');
                        location.reload();
                    }, 1000);
                }
            }
            
            if (e.target.classList.contains('action-unpublish')) {
                e.preventDefault();
                if (confirm('Unpublish this article?')) {
                    // In a real implementation, this would make an API call
                    e.target.textContent = 'Unpublishing...';
                    setTimeout(() => {
                        alert('Article unpublished successfully!');
                        location.reload();
                    }, 1000);
                }
            }
            
            if (e.target.classList.contains('action-delete')) {
                e.preventDefault();
                if (confirm('Are you sure you want to delete this article? This action cannot be undone.')) {
                    // In a real implementation, this would make an API call
                    alert('Article deletion would be implemented here');
                }
            }
            
            if (e.target.classList.contains('action-view')) {
                e.preventDefault();
                // In a real implementation, this would open the article in a new tab
                alert('View article functionality would be implemented here');
            }
            
            if (e.target.classList.contains('action-edit')) {
                e.preventDefault();
                // In a real implementation, this would redirect to edit page
                alert('Edit article functionality would be implemented here');
            }
        });
        
        // Initialize filters with today's date
        document.getElementById('filterDateTo').value = new Date().toISOString().split('T')[0];
        
        // Add keyboard shortcuts
        document.addEventListener('keydown', function(e) {
            // Ctrl/Cmd + N for new article
            if ((e.ctrlKey || e.metaKey) && e.key === 'n') {
                e.preventDefault();
                window.location.href = '<?php echo BASE_URL; ?>/admin/news/create';
            }
            
            // Ctrl/Cmd + F for filters
            if ((e.ctrlKey || e.metaKey) && e.key === 'f') {
                e.preventDefault();
                toggleFilters();
                document.getElementById('filterSearch').focus();
            }
            
            // Ctrl/Cmd + P for print
            if ((e.ctrlKey || e.metaKey) && e.key === 'p') {
                e.preventDefault();
                window.print();
            }
        });
    </script>
</body>
</html>