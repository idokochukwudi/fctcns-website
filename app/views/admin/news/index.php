<?php
// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Ensure variables exist with null coalescing operators
$baseUrl = $baseUrl ?? '';
$user = $user ?? [];
$news = $news ?? [];
$categories = $categories ?? [];
$filters = $filters ?? [];
$pagination = $pagination ?? [];

// Stats protection
if (!isset($stats) || !is_array($stats)) {
    $stats = [];
}

// Define defaults and merge
$defaultStats = [
    'total' => 0,
    'published' => 0,
    'draft' => 0,
    'featured' => 0,
    'news' => 0,
    'breaking' => 0,
    'this_month' => 0,
    'this_week' => 0
];

// Merge with defaults
$stats = array_merge($defaultStats, $stats);

// Verify each key exists
foreach ($defaultStats as $key => $defaultValue) {
    if (!array_key_exists($key, $stats)) {
        $stats[$key] = $defaultValue;
    }
}

// CSRF token
if (!isset($csrf_token) || empty($csrf_token)) {
    $csrfToken = bin2hex(random_bytes(32));
    $_SESSION['csrf_token'] = $csrfToken;
} else {
    $csrfToken = $csrf_token;
}

// Get flash messages
$flashSuccess = $flash_success ?? '';
$flashError = $flash_error ?? '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>News Management - Admin Dashboard</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary-color: #4f46e5;
            --primary-dark: #4338ca;
            --secondary-color: #6b7280;
            --success-color: #10b981;
            --warning-color: #f59e0b;
            --danger-color: #ef4444;
            --info-color: #3b82f6;
            --light-bg: #f9fafb;
            --border-color: #e5e7eb;
            --text-dark: #111827;
            --text-light: #6b7280;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            background-color: var(--light-bg);
            color: var(--text-dark);
            line-height: 1.5;
        }
        
        .admin-container {
            padding: 20px;
            max-width: 1400px;
            margin: 0 auto;
        }
        
        /* Header */
        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            flex-wrap: wrap;
            gap: 20px;
        }
        
        .page-title {
            font-size: 28px;
            font-weight: 700;
            color: var(--text-dark);
        }
        
        .page-actions {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }
        
        .btn {
            padding: 10px 20px;
            border-radius: 6px;
            text-decoration: none;
            font-weight: 500;
            font-size: 14px;
            border: none;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: all 0.3s ease;
        }
        
        .btn-primary {
            background-color: var(--primary-color);
            color: white;
        }
        
        .btn-primary:hover {
            background-color: var(--primary-dark);
            transform: translateY(-2px);
        }
        
        .btn-success {
            background-color: var(--success-color);
            color: white;
        }
        
        .btn-success:hover {
            background-color: #0da271;
            transform: translateY(-2px);
        }
        
        .btn-outline {
            background-color: white;
            color: var(--primary-color);
            border: 1px solid var(--primary-color);
        }
        
        .btn-outline:hover {
            background-color: var(--primary-color);
            color: white;
        }
        
        .btn-danger {
            background-color: var(--danger-color);
            color: white;
        }
        
        .btn-danger:hover {
            background-color: #dc2626;
        }
        
        .btn-sm {
            padding: 6px 12px;
            font-size: 13px;
        }
        
        /* Stats Cards */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }
        
        .stat-card {
            background: white;
            padding: 24px;
            border-radius: 12px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
            border-left: 4px solid var(--primary-color);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        
        .stat-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }
        
        .stat-value {
            font-size: 32px;
            font-weight: 700;
            margin-bottom: 8px;
            color: var(--text-dark);
        }
        
        .stat-label {
            font-size: 14px;
            color: var(--text-light);
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }
        
        /* Filters */
        .filters-card {
            background: white;
            padding: 24px;
            border-radius: 12px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
            margin-bottom: 30px;
        }
        
        .filters-title {
            font-size: 16px;
            font-weight: 600;
            margin-bottom: 16px;
            color: var(--text-dark);
        }
        
        .filter-form {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 16px;
        }
        
        .form-group {
            display: flex;
            flex-direction: column;
            gap: 6px;
        }
        
        .form-label {
            font-size: 14px;
            font-weight: 500;
            color: var(--text-dark);
        }
        
        .form-control {
            padding: 10px 12px;
            border: 1px solid var(--border-color);
            border-radius: 6px;
            font-size: 14px;
            transition: border-color 0.3s ease;
        }
        
        .form-control:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1);
        }
        
        /* Table */
        .table-container {
            background: white;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
            margin-bottom: 30px;
            overflow-x: auto;
        }
        
        .table {
            width: 100%;
            border-collapse: collapse;
            min-width: 1000px;
        }
        
        .table thead {
            background-color: var(--light-bg);
        }
        
        .table th {
            padding: 16px;
            text-align: left;
            font-weight: 600;
            font-size: 14px;
            color: var(--text-dark);
            border-bottom: 2px solid var(--border-color);
        }
        
        .table td {
            padding: 16px;
            border-bottom: 1px solid var(--border-color);
            vertical-align: middle;
        }
        
        .table tbody tr:hover {
            background-color: rgba(79, 70, 229, 0.02);
        }
        
        .table tbody tr:last-child td {
            border-bottom: none;
        }
        
        /* Badges */
        .badge {
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }
        
        .badge-success {
            background-color: #d1fae5;
            color: var(--success-color);
        }
        
        .badge-warning {
            background-color: #fef3c7;
            color: var(--warning-color);
        }
        
        .badge-info {
            background-color: #dbeafe;
            color: var(--info-color);
        }
        
        .badge-danger {
            background-color: #fee2e2;
            color: var(--danger-color);
        }
        
        /* Actions */
        .action-buttons {
            display: flex;
            gap: 8px;
        }
        
        .icon-btn {
            width: 36px;
            height: 36px;
            border-radius: 6px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: none;
            border: 1px solid var(--border-color);
            color: var(--text-light);
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .icon-btn:hover {
            background-color: var(--light-bg);
            color: var(--primary-color);
            border-color: var(--primary-color);
        }
        
        /* Pagination */
        .pagination {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 8px;
            margin-top: 30px;
        }
        
        .pagination-btn {
            padding: 8px 16px;
            border: 1px solid var(--border-color);
            background: white;
            color: var(--text-dark);
            border-radius: 6px;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .pagination-btn:hover:not(:disabled) {
            background-color: var(--primary-color);
            color: white;
            border-color: var(--primary-color);
        }
        
        .pagination-btn.active {
            background-color: var(--primary-color);
            color: white;
            border-color: var(--primary-color);
        }
        
        .pagination-btn:disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }
        
        /* Bulk Actions */
        .bulk-actions {
            background: white;
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 16px;
            flex-wrap: wrap;
        }
        
        .bulk-select {
            display: flex;
            align-items: center;
            gap: 8px;
        }
        
        .bulk-select input[type="checkbox"] {
            width: 18px;
            height: 18px;
        }
        
        /* Flash Messages */
        .flash-message {
            padding: 16px 20px;
            border-radius: 8px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            animation: slideIn 0.3s ease;
        }
        
        .flash-success {
            background-color: #d1fae5;
            color: var(--success-color);
            border: 1px solid #a7f3d0;
        }
        
        .flash-error {
            background-color: #fee2e2;
            color: var(--danger-color);
            border: 1px solid #fecaca;
        }
        
        .flash-close {
            background: none;
            border: none;
            color: inherit;
            cursor: pointer;
            font-size: 18px;
        }
        
        @keyframes slideIn {
            from {
                transform: translateY(-20px);
                opacity: 0;
            }
            to {
                transform: translateY(0);
                opacity: 1;
            }
        }
        
        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 60px 20px;
        }
        
        .empty-state-icon {
            font-size: 48px;
            color: var(--text-light);
            margin-bottom: 16px;
        }
        
        .empty-state-title {
            font-size: 20px;
            font-weight: 600;
            margin-bottom: 8px;
            color: var(--text-dark);
        }
        
        .empty-state-description {
            color: var(--text-light);
            margin-bottom: 24px;
        }
        
        /* Fix for delete button styling */
        .action-buttons form {
            display: inline;
            margin: 0;
            padding: 0;
        }

        .action-buttons .icon-btn[type="submit"] {
            width: 36px;
            height: 36px;
            border-radius: 6px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: none;
            border: 1px solid var(--border-color);
            color: var(--text-light);
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .action-buttons .icon-btn[type="submit"]:hover {
            background-color: var(--light-bg);
            color: var(--danger-color);
            border-color: var(--danger-color);
        }
        
        /* Responsive */
        @media (max-width: 768px) {
            .page-header {
                flex-direction: column;
                align-items: flex-start;
            }
            
            .page-actions {
                width: 100%;
            }
            
            .btn {
                flex: 1;
                justify-content: center;
            }
            
            .stats-grid {
                grid-template-columns: repeat(2, 1fr);
            }
            
            .filter-form {
                grid-template-columns: 1fr;
            }
        }
        
        @media (max-width: 480px) {
            .stats-grid {
                grid-template-columns: 1fr;
            }
            
            .action-buttons {
                flex-wrap: wrap;
            }
        }
    </style>
</head>
<body>
    <div class="admin-container">
        <!-- Flash Messages -->
        <?php if ($flashSuccess): ?>
        <div class="flash-message flash-success">
            <span><i class="fas fa-check-circle"></i> <?php echo htmlspecialchars($flashSuccess); ?></span>
            <button class="flash-close" onclick="this.parentElement.remove()">&times;</button>
        </div>
        <?php endif; ?>
        
        <?php if ($flashError): ?>
        <div class="flash-message flash-error">
            <span><i class="fas fa-exclamation-circle"></i> <?php echo htmlspecialchars($flashError); ?></span>
            <button class="flash-close" onclick="this.parentElement.remove()">&times;</button>
        </div>
        <?php endif; ?>
        
        <!-- Page Header -->
        <div class="page-header">
            <h1 class="page-title">News Management</h1>
            <div class="page-actions">
                <a href="<?php echo $baseUrl; ?>/admin/news/create" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Create News
                </a>
                <a href="<?php echo $baseUrl; ?>/admin/news/export" class="btn btn-success">
                    <i class="fas fa-file-export"></i> Export CSV
                </a>
                <a href="<?php echo $baseUrl; ?>/admin/dashboard" class="btn btn-outline">
                    <i class="fas fa-arrow-left"></i> Back to Dashboard
                </a>
            </div>
        </div>
        
        <!-- Stats -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-value"><?php echo number_format($stats['total'] ?? 0); ?></div>
                <div class="stat-label">Total Articles</div>
            </div>
            <div class="stat-card">
                <div class="stat-value"><?php echo number_format($stats['published'] ?? 0); ?></div>
                <div class="stat-label">Published</div>
            </div>
            <div class="stat-card">
                <div class="stat-value"><?php echo number_format($stats['draft'] ?? 0); ?></div>
                <div class="stat-label">Drafts</div>
            </div>
            <div class="stat-card">
                <div class="stat-value"><?php echo number_format($stats['featured'] ?? 0); ?></div>
                <div class="stat-label">Featured</div>
            </div>
            <div class="stat-card">
                <div class="stat-value"><?php echo number_format($stats['news'] ?? 0); ?></div>
                <div class="stat-label">News Articles</div>
            </div>
            <div class="stat-card">
                <div class="stat-value"><?php echo number_format($stats['breaking'] ?? 0); ?></div>
                <div class="stat-label">Breaking News</div>
            </div>
        </div>
        
        <!-- Filters -->
        <div class="filters-card">
            <h3 class="filters-title">Filters</h3>
            <form method="GET" action="<?php echo $baseUrl; ?>/admin/news" class="filter-form">
                <div class="form-group">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-control">
                        <option value="">All Status</option>
                        <option value="published" <?php echo ($filters['status'] ?? '') === 'published' ? 'selected' : ''; ?>>Published</option>
                        <option value="draft" <?php echo ($filters['status'] ?? '') === 'draft' ? 'selected' : ''; ?>>Draft</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label class="form-label">Type</label>
                    <select name="type" class="form-control">
                        <option value="">All Types</option>
                        <option value="news" <?php echo ($filters['type'] ?? '') === 'news' ? 'selected' : ''; ?>>News</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label class="form-label">Category</label>
                    <select name="category" class="form-control">
                        <option value="">All Categories</option>
                        <?php if (!empty($categories)): ?>
                            <?php foreach ($categories as $category): ?>
                                <option value="<?php echo htmlspecialchars($category); ?>" <?php echo ($filters['category'] ?? '') === $category ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($category); ?>
                                </option>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <option value="Announcements">Announcements</option>
                            <option value="Academic News">Academic News</option>
                            <option value="Research">Research</option>
                        <?php endif; ?>
                    </select>
                </div>
                
                <div class="form-group">
                    <label class="form-label">Search</label>
                    <input type="text" name="search" class="form-control" 
                           placeholder="Search by title or content..." 
                           value="<?php echo htmlspecialchars($filters['search'] ?? ''); ?>">
                </div>
                
                <div class="form-group">
                    <label class="form-label">Date From</label>
                    <input type="date" name="date_from" class="form-control" 
                           value="<?php echo htmlspecialchars($filters['date_from'] ?? ''); ?>">
                </div>
                
                <div class="form-group">
                    <label class="form-label">Date To</label>
                    <input type="date" name="date_to" class="form-control" 
                           value="<?php echo htmlspecialchars($filters['date_to'] ?? ''); ?>">
                </div>
                
                <div class="form-group" style="grid-column: 1 / -1; display: flex; gap: 10px; margin-top: 10px;">
                    <button type="submit" class="btn btn-primary" style="flex: 1;">
                        <i class="fas fa-filter"></i> Apply Filters
                    </button>
                    <a href="<?php echo $baseUrl; ?>/admin/news" class="btn btn-outline" style="flex: 1;">
                        <i class="fas fa-times"></i> Clear Filters
                    </a>
                </div>
            </form>
        </div>
        
        <!-- Bulk Actions Form -->
        <form id="bulkForm" method="POST" action="<?php echo $baseUrl; ?>/admin/news/bulk-action">
            <input type="hidden" name="csrf_token" value="<?php echo $csrfToken; ?>">
            
            <!-- News Table -->
            <div class="table-container">
                <?php if (empty($news)): ?>
                <div class="empty-state">
                    <div class="empty-state-icon">
                        <i class="fas fa-newspaper"></i>
                    </div>
                    <h3 class="empty-state-title">No News Articles Found</h3>
                    <p class="empty-state-description">
                        <?php if (!empty($filters)): ?>
                        Try adjusting your filters or 
                        <a href="<?php echo $baseUrl; ?>/admin/news" style="color: var(--primary-color);">clear all filters</a>.
                        <?php else: ?>
                        Create your first news article to get started.
                        <?php endif; ?>
                    </p>
                    <a href="<?php echo $baseUrl; ?>/admin/news/create" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Create News
                    </a>
                </div>
                <?php else: ?>
                
                <div class="bulk-actions">
                    <div class="bulk-select">
                        <input type="checkbox" id="selectAll">
                        <label for="selectAll">Select All</label>
                    </div>
                    
                    <select name="action" class="form-control" style="max-width: 200px;" required>
                        <option value="">Bulk Actions</option>
                        <option value="publish">Publish</option>
                        <option value="unpublish">Unpublish</option>
                        <option value="feature">Feature</option>
                        <option value="unfeature">Unfeature</option>
                        <option value="delete">Delete</option>
                    </select>
                    
                    <button type="submit" class="btn btn-primary" onclick="return confirmBulkAction()">
                        <i class="fas fa-play"></i> Apply
                    </button>
                </div>
                
                <table class="table">
                    <thead>
                        <tr>
                            <th style="width: 50px;">
                                <input type="checkbox" id="selectAllHeader">
                            </th>
                            <th style="width: 300px;">Title</th>
                            <th style="width: 100px;">Type</th>
                            <th style="width: 120px;">Category</th>
                            <th style="width: 100px;">Status</th>
                            <th style="width: 100px;">Featured</th>
                            <th style="width: 150px;">Author</th>
                            <th style="width: 120px;">Views</th>
                            <th style="width: 150px;">Created</th>
                            <th style="width: 120px;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($news as $item): ?>
                        <tr>
                            <td>
                                <input type="checkbox" name="ids[]" value="<?php echo $item['id']; ?>" class="item-checkbox">
                            </td>
                            <td>
                                <div style="font-weight: 500; margin-bottom: 4px;">
                                    <a href="<?php echo $baseUrl; ?>/admin/news/<?php echo $item['id']; ?>" 
                                       style="color: var(--primary-color); text-decoration: none;">
                                        <?php echo htmlspecialchars($item['title'] ?? 'Untitled'); ?>
                                    </a>
                                </div>
                                <div style="font-size: 12px; color: var(--text-light);">
                                    <?php echo htmlspecialchars($item['slug'] ?? 'no-slug'); ?>
                                </div>
                            </td>
                            <td>
                                <span class="badge badge-success">
                                    <?php echo ucfirst($item['type'] ?? 'news'); ?>
                                </span>
                            </td>
                            <td>
                                <?php echo !empty($item['category']) ? htmlspecialchars($item['category']) : '—'; ?>
                            </td>
                            <td>
                                <?php if (($item['is_published'] ?? 0)): ?>
                                <span class="badge badge-success">Published</span>
                                <?php else: ?>
                                <span class="badge badge-warning">Draft</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if (($item['is_featured'] ?? 0)): ?>
                                <span class="badge badge-info">Featured</span>
                                <?php else: ?>
                                <span>—</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php echo htmlspecialchars($item['author_name'] ?? 'Unknown'); ?>
                            </td>
                            <td>
                                <?php echo number_format($item['views_count'] ?? 0); ?>
                            </td>
                            <td>
                                <?php echo !empty($item['created_at']) ? date('M d, Y', strtotime($item['created_at'])) : '—'; ?>
                            </td>
                            <td>
                                <div class="action-buttons">
                                    <a href="<?php echo $baseUrl; ?>/admin/news/<?php echo $item['id']; ?>/edit" 
                                       class="icon-btn" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    
                                    <?php if (($item['is_published'] ?? 0) && !empty($item['slug'])): ?>
                                    <a href="<?php echo $baseUrl; ?>/news/<?php echo $item['slug']; ?>" 
                                       target="_blank" class="icon-btn" title="View">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <?php else: ?>
                                    <span class="icon-btn" title="Not published" style="opacity: 0.5; cursor: not-allowed;">
                                        <i class="fas fa-eye-slash"></i>
                                    </span>
                                    <?php endif; ?>
                                    
                                    <form method="POST" action="<?php echo $baseUrl; ?>/admin/news/delete/<?php echo $item['id']; ?>" 
                                          style="display: inline;" 
                                          onsubmit="return confirm('Are you sure you want to delete this article?');">
                                        <input type="hidden" name="csrf_token" value="<?php echo $csrfToken; ?>">
                                        <button type="submit" class="icon-btn" title="Delete" style="background: none; border: none; cursor: pointer;">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                <?php endif; ?>
            </div>
        </form>
        
        <!-- Pagination -->
        <?php if (($pagination['total'] ?? 0) > 1): ?>
        <div class="pagination">
            <?php if (($pagination['current'] ?? 1) > 1): ?>
            <a href="?page=<?php echo ($pagination['current'] ?? 1) - 1; ?><?php echo !empty($filters) ? '&' . http_build_query($filters) : ''; ?>" 
               class="pagination-btn">
                <i class="fas fa-chevron-left"></i>
            </a>
            <?php endif; ?>
            
            <?php 
            $start = max(1, ($pagination['current'] ?? 1) - 2);
            $end = min(($pagination['total'] ?? 1), $start + 4);
            $start = max(1, $end - 4);
            
            for ($i = $start; $i <= $end; $i++): 
            ?>
            <a href="?page=<?php echo $i; ?><?php echo !empty($filters) ? '&' . http_build_query($filters) : ''; ?>" 
               class="pagination-btn <?php echo $i == ($pagination['current'] ?? 1) ? 'active' : ''; ?>">
                <?php echo $i; ?>
            </a>
            <?php endfor; ?>
            
            <?php if (($pagination['current'] ?? 1) < ($pagination['total'] ?? 1)): ?>
            <a href="?page=<?php echo ($pagination['current'] ?? 1) + 1; ?><?php echo !empty($filters) ? '&' . http_build_query($filters) : ''; ?>" 
               class="pagination-btn">
                <i class="fas fa-chevron-right"></i>
            </a>
            <?php endif; ?>
        </div>
        <?php endif; ?>
    </div>
    
    <script>
        // Bulk selection
        const selectAllHeader = document.getElementById('selectAllHeader');
        const selectAll = document.getElementById('selectAll');
        const itemCheckboxes = document.querySelectorAll('.item-checkbox');
        
        function updateSelectAll() {
            const allChecked = Array.from(itemCheckboxes).every(cb => cb.checked);
            const someChecked = Array.from(itemCheckboxes).some(cb => cb.checked);
            
            if (selectAllHeader) selectAllHeader.checked = allChecked;
            if (selectAll) selectAll.checked = allChecked;
            
            // Update select all text
            if (selectAll) {
                const label = selectAll.nextElementSibling;
                if (someChecked && !allChecked) {
                    label.textContent = `Selected (${Array.from(itemCheckboxes).filter(cb => cb.checked).length})`;
                } else {
                    label.textContent = 'Select All';
                }
            }
        }
        
        if (selectAllHeader) {
            selectAllHeader.addEventListener('change', function() {
                itemCheckboxes.forEach(cb => cb.checked = this.checked);
                updateSelectAll();
            });
        }
        
        if (selectAll) {
            selectAll.addEventListener('change', function() {
                itemCheckboxes.forEach(cb => cb.checked = this.checked);
                updateSelectAll();
            });
        }
        
        if (itemCheckboxes.length > 0) {
            itemCheckboxes.forEach(cb => {
                cb.addEventListener('change', updateSelectAll);
            });
        }
        
        // Confirm bulk action
        function confirmBulkAction() {
            const selectedCount = Array.from(itemCheckboxes).filter(cb => cb.checked).length;
            const action = document.querySelector('select[name="action"]')?.value;
            
            if (selectedCount === 0) {
                alert('Please select at least one item.');
                return false;
            }
            
            if (!action) {
                alert('Please select an action.');
                return false;
            }
            
            const actionText = action === 'delete' ? 'delete' : action;
            return confirm(`Are you sure you want to ${actionText} ${selectedCount} item(s)?`);
        }
        
        // Auto-update filters when typing in search
        let searchTimeout;
        const searchInput = document.querySelector('input[name="search"]');
        if (searchInput) {
            searchInput.addEventListener('input', function() {
                clearTimeout(searchTimeout);
                searchTimeout = setTimeout(() => {
                    this.form.submit();
                }, 500);
            });
        }
        
        // Quick status toggle
        function toggleStatus(id, currentStatus) {
            if (confirm(`Are you sure you want to ${currentStatus ? 'unpublish' : 'publish'} this article?`)) {
                fetch(`<?php echo $baseUrl; ?>/admin/news/${id}/toggle-publish`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        csrf_token: '<?php echo $csrfToken; ?>'
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        location.reload();
                    } else {
                        alert(data.message || 'Failed to update status');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Failed to update status');
                });
            }
        }
        
        // Quick feature toggle
        function toggleFeature(id, currentFeatured) {
            if (confirm(`Are you sure you want to ${currentFeatured ? 'remove from' : 'add to'} featured?`)) {
                fetch(`<?php echo $baseUrl; ?>/admin/news/${id}/toggle-feature`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        csrf_token: '<?php echo $csrfToken; ?>'
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        location.reload();
                    } else {
                        alert(data.message || 'Failed to update feature status');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Failed to update feature status');
                });
            }
        }
        
        // Initialize
        document.addEventListener('DOMContentLoaded', function() {
            updateSelectAll();
            
            // Add keyboard shortcuts
            document.addEventListener('keydown', function(e) {
                // Ctrl/Cmd + F for search
                if ((e.ctrlKey || e.metaKey) && e.key === 'f') {
                    e.preventDefault();
                    if (searchInput) {
                        searchInput.focus();
                        searchInput.select();
                    }
                }
                
                // Ctrl/Cmd + N for new
                if ((e.ctrlKey || e.metaKey) && e.key === 'n') {
                    e.preventDefault();
                    window.location.href = '<?php echo $baseUrl; ?>/admin/news/create';
                }
                
                // Escape to clear filters
                if (e.key === 'Escape') {
                    const clearBtn = document.querySelector('a[href*="/admin/news"]');
                    if (clearBtn && window.location.search.includes('?')) {
                        window.location.href = '<?php echo $baseUrl; ?>/admin/news';
                    }
                }
            });
            
            // Auto-hide flash messages after 5 seconds
            setTimeout(() => {
                document.querySelectorAll('.flash-message').forEach(msg => {
                    msg.style.opacity = '0';
                    msg.style.transition = 'opacity 0.5s ease';
                    setTimeout(() => msg.remove(), 500);
                });
            }, 5000);
        });
    </script>
</body>
</html>