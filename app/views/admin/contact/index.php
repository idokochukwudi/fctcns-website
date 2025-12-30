<?php
// Get the absolute path to the root
$rootPath = dirname(__DIR__, 4); // Go up 4 levels from app/views/admin/contact/
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
    <title>Contact Management - FCT CNS Admin</title>
    <style>
        /* Admin Contact Styles */
        :root {
            --admin-primary: #2c5282;
            --admin-primary-dark: #1a365d;
            --admin-primary-light: #4299e1;
            --admin-success: #38a169;
            --admin-warning: #d69e2e;
            --admin-danger: #e53e3e;
            --admin-info: #3182ce;
            --admin-gray-50: #f7fafc;
            --admin-gray-100: #edf2f7;
            --admin-gray-200: #e2e8f0;
            --admin-gray-600: #718096;
            --admin-gray-700: #4a5568;
            --admin-gray-800: #2d3748;
        }
        
        .contact-management {
            padding: 20px;
        }
        
        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            flex-wrap: wrap;
            gap: 15px;
        }
        
        .page-header h1 {
            color: var(--admin-gray-800);
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
            background: var(--admin-primary);
            color: white;
        }
        
        .btn-secondary {
            background: var(--admin-gray-600);
            color: white;
        }
        
        .btn-success {
            background: var(--admin-success);
            color: white;
        }
        
        .btn-warning {
            background: var(--admin-warning);
            color: white;
        }
        
        .btn-danger {
            background: var(--admin-danger);
            color: white;
        }
        
        .btn-info {
            background: var(--admin-info);
            color: white;
        }
        
        .btn:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }
        
        .btn-group {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }
        
        /* Stats Cards */
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
            border-left: 4px solid var(--admin-primary);
        }
        
        .stat-card.pending { border-left-color: var(--admin-warning); }
        .stat-card.responded { border-left-color: var(--admin-success); }
        .stat-card.archived { border-left-color: var(--admin-gray-600); }
        .stat-card.total { border-left-color: var(--admin-info); }
        
        .stat-value {
            font-size: 2rem;
            font-weight: 700;
            color: var(--admin-gray-800);
            line-height: 1;
            margin-bottom: 5px;
        }
        
        .stat-label {
            font-size: 0.875rem;
            color: var(--admin-gray-600);
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }
        
        /* Filters */
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
            color: var(--admin-gray-700);
            font-size: 0.875rem;
        }
        
        .filter-group select, .filter-group input {
            padding: 10px;
            border: 1px solid var(--admin-gray-200);
            border-radius: 6px;
            font-size: 14px;
            width: 100%;
            box-sizing: border-box;
        }
        
        /* Table */
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
            min-width: 1200px;
        }
        
        th, td {
            padding: 15px;
            text-align: left;
            border-bottom: 1px solid var(--admin-gray-200);
        }
        
        th {
            background: var(--admin-gray-50);
            font-weight: 600;
            color: var(--admin-gray-700);
            text-transform: uppercase;
            font-size: 0.75rem;
            letter-spacing: 0.05em;
        }
        
        tr:hover {
            background: var(--admin-gray-50);
        }
        
        /* Badges */
        .badge {
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 600;
            display: inline-block;
        }
        
        .badge-pending { 
            background: rgba(214, 158, 46, 0.1); 
            color: var(--admin-warning); 
            border: 1px solid rgba(214, 158, 46, 0.2);
        }
        
        .badge-responded { 
            background: rgba(56, 161, 105, 0.1); 
            color: var(--admin-success); 
            border: 1px solid rgba(56, 161, 105, 0.2);
        }
        
        .badge-archived { 
            background: rgba(113, 128, 150, 0.1); 
            color: var(--admin-gray-600); 
            border: 1px solid rgba(113, 128, 150, 0.2);
        }
        
        /* Submission Preview */
        .submission-preview {
            max-width: 200px;
        }
        
        .submission-name {
            font-weight: 600;
            color: var(--admin-gray-800);
            margin-bottom: 5px;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        
        .submission-email {
            font-size: 0.875rem;
            color: var(--admin-gray-600);
            margin-bottom: 5px;
        }
        
        .submission-subject {
            font-size: 0.875rem;
            color: var(--admin-gray-700);
            font-weight: 500;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
        
        /* Actions */
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
        
        .action-view { background: var(--admin-info); color: white; }
        .action-respond { background: var(--admin-success); color: white; }
        .action-archive { background: var(--admin-gray-600); color: white; }
        .action-delete { background: var(--admin-danger); color: white; }
        
        .action-btn:hover {
            transform: translateY(-1px);
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }
        
        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 60px 20px;
            color: var(--admin-gray-600);
        }
        
        .empty-state h3 {
            margin-bottom: 10px;
            color: var(--admin-gray-700);
        }
        
        /* Responsive */
        @media (max-width: 768px) {
            .page-header { flex-direction: column; align-items: stretch; }
            .btn-group { justify-content: center; }
            
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
                grid-template-columns: repeat(2, 1fr);
            }
            
            .filter-row {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <div class="contact-management">
        <div class="page-header">
            <h1>📧 Contact Management</h1>
            <div class="btn-group">
                <a href="<?php echo BASE_URL; ?>/admin/dashboard" class="btn btn-secondary">
                    ← Dashboard
                </a>
                <button class="btn btn-info" onclick="toggleFilters()">
                    🔍 Filters
                </button>
                <a href="<?php echo BASE_URL; ?>/admin/contact/settings" class="btn btn-primary">
                    ⚙️ Settings
                </a>
                <a href="<?php echo BASE_URL; ?>/admin/contact/export" class="btn btn-success">
                    📥 Export CSV
                </a>
            </div>
        </div>
        
        <!-- Statistics -->
        <div class="stats-grid">
            <?php
            $total = count($submissions ?? []);
            $pending = 0;
            $responded = 0;
            $archived = 0;
            
            foreach ($submissions ?? [] as $submission) {
                switch ($submission['status']) {
                    case 'pending': $pending++; break;
                    case 'responded': $responded++; break;
                    case 'archived': $archived++; break;
                }
            }
            ?>
            
            <div class="stat-card total">
                <div class="stat-value"><?php echo $total; ?></div>
                <div class="stat-label">Total Submissions</div>
            </div>
            
            <div class="stat-card pending">
                <div class="stat-value"><?php echo $pending; ?></div>
                <div class="stat-label">Pending</div>
            </div>
            
            <div class="stat-card responded">
                <div class="stat-value"><?php echo $responded; ?></div>
                <div class="stat-label">Responded</div>
            </div>
            
            <div class="stat-card archived">
                <div class="stat-value"><?php echo $archived; ?></div>
                <div class="stat-label">Archived</div>
            </div>
        </div>
        
        <!-- Filters -->
        <div class="filters" id="filtersSection" style="display: none;">
            <form method="GET" action="">
                <div class="filter-row">
                    <div class="filter-group">
                        <label for="status">Status</label>
                        <select id="status" name="status">
                            <option value="all" <?php echo ($current_status ?? 'all') === 'all' ? 'selected' : ''; ?>>All Statuses</option>
                            <option value="pending" <?php echo ($current_status ?? '') === 'pending' ? 'selected' : ''; ?>>Pending</option>
                            <option value="responded" <?php echo ($current_status ?? '') === 'responded' ? 'selected' : ''; ?>>Responded</option>
                            <option value="archived" <?php echo ($current_status ?? '') === 'archived' ? 'selected' : ''; ?>>Archived</option>
                        </select>
                    </div>
                    
                    <div class="filter-group">
                        <label for="department">Department</label>
                        <select id="department" name="department">
                            <option value="">All Departments</option>
                            <option value="general">General Inquiry</option>
                            <option value="admissions">Admissions</option>
                            <option value="academic">Academic Affairs</option>
                            <option value="student">Student Affairs</option>
                            <option value="finance">Finance & Billing</option>
                        </select>
                    </div>
                    
                    <div class="filter-group">
                        <label for="date_from">Date From</label>
                        <input type="date" id="date_from" name="date_from">
                    </div>
                    
                    <div class="filter-group">
                        <label for="date_to">Date To</label>
                        <input type="date" id="date_to" name="date_to">
                    </div>
                </div>
                
                <div class="filter-row">
                    <div class="filter-group">
                        <label for="search">Search</label>
                        <input type="text" id="search" name="search" 
                               placeholder="Search by name, email, or subject..."
                               value="<?php echo htmlspecialchars($search_term ?? ''); ?>">
                    </div>
                </div>
                
                <div style="display: flex; justify-content: flex-end; gap: 10px; margin-top: 15px;">
                    <a href="<?php echo BASE_URL; ?>/admin/contact" class="btn btn-secondary">
                        Clear Filters
                    </a>
                    <button type="submit" class="btn btn-primary">
                        Apply Filters
                    </button>
                </div>
            </form>
        </div>
        
        <!-- Submissions Table -->
        <div class="table-container">
            <?php if (!empty($submissions)): ?>
                <table>
                    <thead>
                        <tr>
                            <th>Submission</th>
                            <th>Contact Info</th>
                            <th>Department</th>
                            <th>Status</th>
                            <th>Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($submissions as $submission): ?>
                        <tr data-id="<?php echo $submission['id']; ?>">
                            <td>
                                <div class="submission-preview">
                                    <div class="submission-name">
                                        <?php if ($submission['status'] === 'pending'): ?>
                                        <span style="color: var(--admin-warning); font-size: 12px;">●</span>
                                        <?php endif; ?>
                                        <?php echo htmlspecialchars($submission['name']); ?>
                                    </div>
                                    <div class="submission-email">
                                        <?php echo htmlspecialchars($submission['email']); ?>
                                    </div>
                                    <div class="submission-subject">
                                        <?php echo htmlspecialchars($submission['subject']); ?>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div style="font-size: 0.875rem;">
                                    <?php if (!empty($submission['phone'])): ?>
                                    <div>📞 <?php echo htmlspecialchars($submission['phone']); ?></div>
                                    <?php endif; ?>
                                    <div>📧 <?php echo htmlspecialchars($submission['email']); ?></div>
                                    <div style="margin-top: 5px; font-size: 0.75rem; color: var(--admin-gray-600);">
                                        <?php echo date('M d, Y H:i', strtotime($submission['created_at'])); ?>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span style="font-size: 0.875rem; color: var(--admin-gray-700);">
                                    <?php echo ucfirst(htmlspecialchars($submission['department'])); ?>
                                </span>
                            </td>
                            <td>
                                <?php if ($submission['status'] === 'pending'): ?>
                                <span class="badge badge-pending">Pending</span>
                                <?php elseif ($submission['status'] === 'responded'): ?>
                                <span class="badge badge-responded">Responded</span>
                                <?php else: ?>
                                <span class="badge badge-archived">Archived</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <div style="font-size: 0.875rem;">
                                    <div><?php echo date('M d, Y', strtotime($submission['created_at'])); ?></div>
                                    <div style="color: var(--admin-gray-500); font-size: 0.75rem;">
                                        <?php echo date('H:i', strtotime($submission['created_at'])); ?>
                                    </div>
                                    <?php if ($submission['responded_at']): ?>
                                    <div style="color: var(--admin-success); font-size: 0.75rem; margin-top: 4px;">
                                        Responded: <?php echo date('M d', strtotime($submission['responded_at'])); ?>
                                    </div>
                                    <?php endif; ?>
                                </div>
                            </td>
                            <td>
                                <div class="actions">
                                    <a href="<?php echo BASE_URL; ?>/admin/contact/view/<?php echo $submission['id']; ?>" 
                                       class="action-btn action-view">
                                        View
                                    </a>
                                    
                                    <?php if ($submission['status'] === 'pending'): ?>
                                    <a href="<?php echo BASE_URL; ?>/admin/contact/view/<?php echo $submission['id']; ?>#respond" 
                                       class="action-btn action-respond">
                                        Respond
                                    </a>
                                    <?php endif; ?>
                                    
                                    <?php if (in_array($userRole, ['admin', 'editor'])): ?>
                                    <form method="POST" 
                                          action="<?php echo BASE_URL; ?>/admin/contact/delete/<?php echo $submission['id']; ?>" 
                                          style="display: inline;"
                                          onsubmit="return confirm('Delete this submission?')">
                                        <input type="hidden" name="csrf_token" value="<?php echo $csrf_token ?? ''; ?>">
                                        <button type="submit" class="action-btn action-delete">
                                            Delete
                                        </button>
                                    </form>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <div class="empty-state">
                    <h3>No Contact Submissions Found</h3>
                    <p>No contact submissions match your current filters.</p>
                    <a href="<?php echo BASE_URL; ?>/admin/contact" class="btn btn-primary">
                        Clear Filters
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </div>
    
    <script>
        // Toggle filters
        function toggleFilters() {
            const filters = document.getElementById('filtersSection');
            filters.style.display = filters.style.display === 'none' ? 'block' : 'none';
        }
        
        // Auto-fill date filters
        document.addEventListener('DOMContentLoaded', function() {
            const dateTo = document.getElementById('date_to');
            if (dateTo) {
                dateTo.value = new Date().toISOString().split('T')[0];
            }
            
            // Set date_from to 30 days ago
            const dateFrom = document.getElementById('date_from');
            if (dateFrom) {
                const thirtyDaysAgo = new Date();
                thirtyDaysAgo.setDate(thirtyDaysAgo.getDate() - 30);
                dateFrom.value = thirtyDaysAgo.toISOString().split('T')[0];
            }
        });
        
        // Quick status update
        document.addEventListener('click', function(e) {
            if (e.target.classList.contains('quick-respond')) {
                e.preventDefault();
                const submissionId = e.target.dataset.id;
                
                if (confirm('Mark this submission as responded?')) {
                    fetch(`/admin/contact/quick-update/${submissionId}`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                        },
                        body: JSON.stringify({ status: 'responded' })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            location.reload();
                        } else {
                            alert('Error updating status');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('Error updating status');
                    });
                }
            }
        });
        
        // Keyboard shortcuts
        document.addEventListener('keydown', function(e) {
            // Ctrl/Cmd + F for filters
            if ((e.ctrlKey || e.metaKey) && e.key === 'f') {
                e.preventDefault();
                toggleFilters();
                document.getElementById('search').focus();
            }
            
            // Esc to close filters
            if (e.key === 'Escape') {
                document.getElementById('filtersSection').style.display = 'none';
            }
        });
        
        // Auto-refresh every 2 minutes
        setInterval(function() {
            const activeElement = document.activeElement;
            const isFormFocused = activeElement.tagName === 'INPUT' || 
                                 activeElement.tagName === 'TEXTAREA' || 
                                 activeElement.tagName === 'SELECT';
            
            if (!isFormFocused) {
                console.log('Auto-refreshing contact submissions...');
                // In a real app, this would fetch updated data via AJAX
            }
        }, 2 * 60 * 1000);
    </script>
</body>
</html>