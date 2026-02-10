<?php
$baseUrl = $data['baseUrl'] ?? '';
$user = $data['user'] ?? [];
$events = $data['events'] ?? [];
$stats = $data['stats'] ?? [];
$categories = $data['categories'] ?? [];
$filters = $data['filters'] ?? [];
$pagination = $data['pagination'] ?? [];
$csrfToken = bin2hex(random_bytes(32));
$_SESSION['csrf_token'] = $csrfToken;

// Ensure stats has all required keys with defaults
$defaultStats = [
    'total' => 0,
    'published' => 0,
    'upcoming' => 0,
    'featured' => 0,
    'past' => 0,
    'registrations' => 0,
    'draft' => 0
];
$stats = array_merge($defaultStats, $stats);

// Get flash messages - use the correct session key from Controller
$flashSuccess = $_SESSION['flash_success'] ?? '';
$flashError = $_SESSION['flash_error'] ?? '';
unset($_SESSION['flash_success'], $_SESSION['flash_error']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Events Management - Admin Dashboard</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary-color: #8b5cf6;
            --primary-dark: #7c3aed;
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
        
        /* Same structure as news/index.php but with purple theme */
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
        
        .btn-sm {
            padding: 6px 12px;
            font-size: 13px;
        }
        
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
            box-shadow: 0 0 0 3px rgba(139, 92, 246, 0.1);
        }
        
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
            background-color: rgba(139, 92, 246, 0.02);
        }
        
        .table tbody tr:last-child td {
            border-bottom: none;
        }
        
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
        
        .badge-purple {
            background-color: #ede9fe;
            color: var(--primary-color);
        }
        
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
        
        .date-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 4px 10px;
            background: #f3f4f6;
            border-radius: 4px;
            font-size: 12px;
        }
        
        .date-badge.upcoming {
            background: #d1fae5;
            color: var(--success-color);
        }
        
        .date-badge.past {
            background: #fef3c7;
            color: var(--warning-color);
        }
        
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
            <h1 class="page-title">Events Management</h1>
            <div class="page-actions">
                <a href="<?php echo $baseUrl; ?>/admin/events/create" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Create Event
                </a>
                <a href="<?php echo $baseUrl; ?>/admin/events/export" class="btn btn-success">
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
                <div class="stat-value"><?php echo number_format($stats['total']); ?></div>
                <div class="stat-label">Total Events</div>
            </div>
            <div class="stat-card">
                <div class="stat-value"><?php echo number_format($stats['published']); ?></div>
                <div class="stat-label">Published</div>
            </div>
            <div class="stat-card">
                <div class="stat-value"><?php echo number_format($stats['upcoming']); ?></div>
                <div class="stat-label">Upcoming</div>
            </div>
            <div class="stat-card">
                <div class="stat-value"><?php echo number_format($stats['featured']); ?></div>
                <div class="stat-label">Featured</div>
            </div>
            <div class="stat-card">
                <div class="stat-value"><?php echo number_format($stats['past']); ?></div>
                <div class="stat-label">Past Events</div>
            </div>
            <div class="stat-card">
                <div class="stat-value"><?php echo number_format($stats['registrations']); ?></div>
                <div class="stat-label">Registrations</div>
            </div>
        </div>
        
        <!-- Filters -->
        <div class="filters-card">
            <h3 class="filters-title">Filters</h3>
            <form method="GET" action="<?php echo $baseUrl; ?>/admin/events" class="filter-form">
                <div class="form-group">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-control">
                        <option value="">All Status</option>
                        <option value="published" <?php echo ($filters['status'] ?? '') === 'published' ? 'selected' : ''; ?>>Published</option>
                        <option value="draft" <?php echo ($filters['status'] ?? '') === 'draft' ? 'selected' : ''; ?>>Draft</option>
                        <option value="upcoming" <?php echo ($filters['status'] ?? '') === 'upcoming' ? 'selected' : ''; ?>>Upcoming</option>
                        <option value="past" <?php echo ($filters['status'] ?? '') === 'past' ? 'selected' : ''; ?>>Past</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label class="form-label">Category</label>
                    <select name="category" class="form-control">
                        <option value="">All Categories</option>
                        <?php foreach ($categories as $category): ?>
                        <option value="<?php echo htmlspecialchars($category); ?>" <?php echo ($filters['category'] ?? '') === $category ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($category); ?>
                        </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <div class="form-group">
                    <label class="form-label">Search</label>
                    <input type="text" name="search" class="form-control" 
                           placeholder="Search by title or description..." 
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
                    <a href="<?php echo $baseUrl; ?>/admin/events" class="btn btn-outline" style="flex: 1;">
                        <i class="fas fa-times"></i> Clear Filters
                    </a>
                </div>
            </form>
        </div>
        
        <!-- Bulk Actions Form -->
        <form id="bulkForm" method="POST" action="<?php echo $baseUrl; ?>/admin/events/bulk-action">
            <input type="hidden" name="csrf_token" value="<?php echo $csrfToken; ?>">
            
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
            
            <!-- Events Table -->
            <div class="table-container">
                <?php if (empty($events)): ?>
                <div class="empty-state">
                    <div class="empty-state-icon">
                        <i class="fas fa-calendar-alt"></i>
                    </div>
                    <h3 class="empty-state-title">No Events Found</h3>
                    <p class="empty-state-description">Create your first event to get started.</p>
                    <a href="<?php echo $baseUrl; ?>/admin/events/create" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Create Event
                    </a>
                </div>
                <?php else: ?>
                <table class="table">
                    <thead>
                        <tr>
                            <th style="width: 50px;">
                                <input type="checkbox" id="selectAllHeader">
                            </th>
                            <th style="width: 300px;">Event Title</th>
                            <th style="width: 120px;">Event Date</th>
                            <th style="width: 120px;">Category</th>
                            <th style="width: 120px;">Location</th>
                            <th style="width: 100px;">Status</th>
                            <th style="width: 100px;">Featured</th>
                            <th style="width: 120px;">Registrations</th>
                            <th style="width: 150px;">Created</th>
                            <th style="width: 120px;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($events as $event): 
                            $eventDate = $event['event_date'] ?? '';
                            $isUpcoming = !empty($eventDate) && strtotime($eventDate) > time();
                            $dateClass = $isUpcoming ? 'upcoming' : 'past';
                        ?>
                        <tr>
                            <td>
                                <input type="checkbox" name="ids[]" value="<?php echo $event['id'] ?? ''; ?>" class="item-checkbox">
                            </td>
                            <td>
                                <div style="font-weight: 500; margin-bottom: 4px;">
                                    <a href="<?php echo $baseUrl; ?>/admin/events/<?php echo $event['id'] ?? ''; ?>" 
                                       style="color: var(--primary-color); text-decoration: none;">
                                        <?php echo htmlspecialchars($event['title'] ?? ''); ?>
                                    </a>
                                </div>
                                <div style="font-size: 12px; color: var(--text-light);">
                                    <?php echo htmlspecialchars($event['slug'] ?? ''); ?>
                                </div>
                            </td>
                            <td>
                                <?php if (!empty($eventDate)): ?>
                                <div class="date-badge <?php echo $dateClass; ?>">
                                    <i class="fas fa-calendar"></i>
                                    <?php echo date('M d, Y', strtotime($eventDate)); ?>
                                </div>
                                <?php if (!empty($event['event_time'])): ?>
                                <div style="font-size: 12px; color: var(--text-light); margin-top: 4px;">
                                    <?php echo date('h:i A', strtotime($event['event_time'])); ?>
                                </div>
                                <?php endif; ?>
                                <?php else: ?>
                                <span>—</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php echo !empty($event['category']) ? htmlspecialchars($event['category']) : '—'; ?>
                            </td>
                            <td>
                                <?php echo !empty($event['location']) ? htmlspecialchars($event['location']) : '—'; ?>
                            </td>
                            <td>
                                <?php if ($event['is_published'] ?? false): ?>
                                <span class="badge badge-success">Published</span>
                                <?php else: ?>
                                <span class="badge badge-warning">Draft</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if ($event['is_featured'] ?? false): ?>
                                <span class="badge badge-purple">Featured</span>
                                <?php else: ?>
                                <span>—</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <div style="display: flex; align-items: center; gap: 6px;">
                                    <span><?php echo $event['current_participants'] ?? 0; ?> / <?php echo !empty($event['max_participants']) ? $event['max_participants'] : '∞'; ?></span>
                                    <?php if (($event['current_participants'] ?? 0) > 0): ?>
                                    <a href="<?php echo $baseUrl; ?>/admin/events/registrations/<?php echo $event['id'] ?? ''; ?>" 
                                       class="icon-btn btn-sm" title="View Registrations">
                                        <i class="fas fa-users"></i>
                                    </a>
                                    <?php endif; ?>
                                </div>
                            </td>
                            <td>
                                <?php echo !empty($event['created_at']) ? date('M d, Y', strtotime($event['created_at'])) : '—'; ?>
                            </td>
                            <td>
                                <div class="action-buttons">
                                    <a href="<?php echo $baseUrl; ?>/admin/events/<?php echo $event['id'] ?? ''; ?>/edit" 
                                       class="icon-btn" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <a href="<?php echo $baseUrl; ?>/events/<?php echo $event['slug'] ?? ''; ?>" 
                                       target="_blank" class="icon-btn" title="View">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <form method="POST" action="<?php echo $baseUrl; ?>/admin/events/<?php echo $event['id'] ?? ''; ?>/delete" 
                                          style="display: inline;" onsubmit="return confirm('Are you sure you want to delete this event?');">
                                        <input type="hidden" name="csrf_token" value="<?php echo $csrfToken; ?>">
                                        <button type="submit" class="icon-btn" title="Delete">
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
            $current = $pagination['current'] ?? 1;
            $totalPages = $pagination['total'] ?? 1;
            $start = max(1, $current - 2);
            $end = min($totalPages, $start + 4);
            $start = max(1, $end - 4);
            
            for ($i = $start; $i <= $end; $i++): 
            ?>
            <a href="?page=<?php echo $i; ?><?php echo !empty($filters) ? '&' . http_build_query($filters) : ''; ?>" 
               class="pagination-btn <?php echo $i == $current ? 'active' : ''; ?>">
                <?php echo $i; ?>
            </a>
            <?php endfor; ?>
            
            <?php if ($current < $totalPages): ?>
            <a href="?page=<?php echo $current + 1; ?><?php echo !empty($filters) ? '&' . http_build_query($filters) : ''; ?>" 
               class="pagination-btn">
                <i class="fas fa-chevron-right"></i>
            </a>
            <?php endif; ?>
        </div>
        <?php endif; ?>
    </div>
    
    <script>
        // Bulk selection (same as news)
        const selectAllHeader = document.getElementById('selectAllHeader');
        const selectAll = document.getElementById('selectAll');
        const itemCheckboxes = document.querySelectorAll('.item-checkbox');
        
        function updateSelectAll() {
            if (!itemCheckboxes.length) return;
            
            const allChecked = Array.from(itemCheckboxes).every(cb => cb.checked);
            const someChecked = Array.from(itemCheckboxes).some(cb => cb.checked);
            
            if (selectAllHeader) selectAllHeader.checked = allChecked;
            if (selectAll) selectAll.checked = allChecked;
            
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
        
        itemCheckboxes.forEach(cb => {
            cb.addEventListener('change', updateSelectAll);
        });
        
        // Confirm bulk action
        function confirmBulkAction() {
            const selectedCount = Array.from(itemCheckboxes).filter(cb => cb.checked).length;
            const actionSelect = document.querySelector('select[name="action"]');
            const action = actionSelect ? actionSelect.value : '';
            
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
                    window.location.href = '<?php echo $baseUrl; ?>/admin/events/create';
                }
            });
        });
    </script>
</body>
</html>