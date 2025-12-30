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
    <title>Applications Management - FCT CNS Admin</title>
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
            max-width: 1400px;
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
        
        .stat-card.pending { border-left-color: var(--warning); }
        .stat-card.reviewed { border-left-color: var(--info); }
        .stat-card.accepted { border-left-color: var(--success); }
        .stat-card.rejected { border-left-color: var(--danger); }
        
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
            min-width: 1200px;
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
        
        .badge-pending { 
            background: rgba(214, 158, 46, 0.1); 
            color: var(--warning); 
            border: 1px solid rgba(214, 158, 46, 0.2);
        }
        
        .badge-reviewed { 
            background: rgba(66, 153, 225, 0.1); 
            color: var(--info); 
            border: 1px solid rgba(66, 153, 225, 0.2);
        }
        
        .badge-accepted { 
            background: rgba(56, 161, 105, 0.1); 
            color: var(--success); 
            border: 1px solid rgba(56, 161, 105, 0.2);
        }
        
        .badge-rejected { 
            background: rgba(229, 62, 62, 0.1); 
            color: var(--danger); 
            border: 1px solid rgba(229, 62, 62, 0.2);
        }
        
        .applicant-info {
            font-weight: 600;
            color: var(--gray-800);
            margin-bottom: 5px;
            line-height: 1.4;
        }
        
        .applicant-meta {
            display: flex;
            align-items: center;
            gap: 15px;
            font-size: 0.75rem;
            color: var(--gray-500);
            margin-top: 8px;
            flex-wrap: wrap;
        }
        
        .applicant-meta span {
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
        .action-accept { background: var(--success); color: white; }
        .action-reject { background: var(--danger); color: white; }
        .action-review { background: var(--warning); color: white; }
        .action-delete { background: var(--danger); color: white; }
        
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
        
        .status-pending { background: var(--warning); }
        .status-reviewed { background: var(--info); }
        .status-accepted { background: var(--success); }
        .status-rejected { background: var(--danger); }
        
        .program-badge {
            background: rgba(66, 153, 225, 0.1);
            color: var(--info);
            padding: 2px 8px;
            border-radius: 4px;
            font-size: 0.75rem;
            font-weight: 500;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>📋 Applications Management</h1>
            <div class="btn-group">
                <a href="<?php echo BASE_URL; ?>/admin/dashboard" class="btn btn-secondary">
                    ← Dashboard
                </a>
                <?php if (in_array($userRole, ['admin', 'editor'])): ?>
                <a href="<?php echo BASE_URL; ?>/admin/applications/create" class="btn btn-primary">
                    ＋ New Application
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
            // These would come from the controller in a real implementation
            $pendingCount = 0;
            $reviewedCount = 0;
            $acceptedCount = 0;
            $rejectedCount = 0;
            ?>
            
            <div class="stat-card pending">
                <div class="stat-value"><?php echo $pendingCount; ?></div>
                <div class="stat-label">Pending Review</div>
            </div>
            
            <div class="stat-card reviewed">
                <div class="stat-value"><?php echo $reviewedCount; ?></div>
                <div class="stat-label">Under Review</div>
            </div>
            
            <div class="stat-card accepted">
                <div class="stat-value"><?php echo $acceptedCount; ?></div>
                <div class="stat-label">Accepted</div>
            </div>
            
            <div class="stat-card rejected">
                <div class="stat-value"><?php echo $rejectedCount; ?></div>
                <div class="stat-label">Rejected</div>
            </div>
        </div>
        
        <!-- Filters -->
        <div class="filters" id="filtersSection" style="display: none;">
            <div class="filter-row">
                <div class="filter-group">
                    <label for="filterStatus">Status</label>
                    <select id="filterStatus">
                        <option value="">All Statuses</option>
                        <option value="pending">Pending</option>
                        <option value="reviewed">Reviewed</option>
                        <option value="accepted">Accepted</option>
                        <option value="rejected">Rejected</option>
                    </select>
                </div>
                
                <div class="filter-group">
                    <label for="filterProgram">Program</label>
                    <select id="filterProgram">
                        <option value="">All Programs</option>
                        <option value="Nursing">Nursing</option>
                        <option value="Midwifery">Midwifery</option>
                        <option value="Public Health">Public Health</option>
                        <option value="Post Basic">Post Basic</option>
                    </select>
                </div>
                
                <div class="filter-group">
                    <label for="filterYear">Entry Year</label>
                    <select id="filterYear">
                        <option value="">All Years</option>
                        <option value="2025">2025</option>
                        <option value="2024">2024</option>
                        <option value="2023">2023</option>
                    </select>
                </div>
            </div>
            
            <div class="filter-row">
                <div class="filter-group">
                    <label for="filterSearch">Search</label>
                    <input type="text" id="filterSearch" placeholder="Search by name, email, or phone...">
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
        
        <!-- Applications Table -->
        <div class="table-container">
            <?php if (isset($applications) && !empty($applications)): ?>
                <table>
                    <thead>
                        <tr>
                            <th>Applicant</th>
                            <th class="desktop-only">Program & Year</th>
                            <th>Status</th>
                            <th class="desktop-only">Qualification</th>
                            <th class="desktop-only">Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($applications as $application): ?>
                        <tr data-status="<?php echo htmlspecialchars($application['status']); ?>"
                            data-program="<?php echo htmlspecialchars($application['program']); ?>"
                            data-year="<?php echo htmlspecialchars($application['entry_year']); ?>"
                            data-date="<?php echo date('Y-m-d', strtotime($application['created_at'])); ?>">
                            <td>
                                <div class="applicant-info">
                                    <?php echo htmlspecialchars($application['first_name'] . ' ' . $application['last_name']); ?>
                                </div>
                                <div style="font-size: 0.875rem; color: var(--gray-600);">
                                    📧 <?php echo htmlspecialchars($application['email']); ?>
                                </div>
                                <div style="font-size: 0.875rem; color: var(--gray-600);">
                                    📱 <?php echo htmlspecialchars($application['phone']); ?>
                                </div>
                                <div class="applicant-meta mobile-only">
                                    <span>
                                        🎓 <?php echo htmlspecialchars($application['program']); ?>
                                    </span>
                                    <span>
                                        📅 <?php echo date('M d', strtotime($application['created_at'])); ?>
                                    </span>
                                </div>
                            </td>
                            <td class="desktop-only">
                                <div>
                                    <span class="program-badge">
                                        <?php echo htmlspecialchars($application['program']); ?>
                                    </span>
                                </div>
                                <div style="margin-top: 8px; font-size: 0.875rem;">
                                    Entry Year: <strong><?php echo htmlspecialchars($application['entry_year']); ?></strong>
                                </div>
                            </td>
                            <td>
                                <?php if ($application['status'] == 'pending'): ?>
                                <span class="badge badge-pending">
                                    <span class="status-indicator status-pending"></span>
                                    Pending
                                </span>
                                <?php elseif ($application['status'] == 'reviewed'): ?>
                                <span class="badge badge-reviewed">
                                    <span class="status-indicator status-reviewed"></span>
                                    Under Review
                                </span>
                                <?php elseif ($application['status'] == 'accepted'): ?>
                                <span class="badge badge-accepted">
                                    <span class="status-indicator status-accepted"></span>
                                    Accepted
                                </span>
                                <?php elseif ($application['status'] == 'rejected'): ?>
                                <span class="badge badge-rejected">
                                    <span class="status-indicator status-rejected"></span>
                                    Rejected
                                </span>
                                <?php endif; ?>
                                <div class="mobile-only" style="font-size: 0.75rem; color: var(--gray-600); margin-top: 4px;">
                                    <?php echo htmlspecialchars($application['highest_qualification']); ?>
                                </div>
                            </td>
                            <td class="desktop-only">
                                <div style="font-size: 0.875rem;">
                                    <div>🎓 <?php echo htmlspecialchars($application['highest_qualification']); ?></div>
                                    <?php if ($application['qualification_file']): ?>
                                    <div style="margin-top: 4px;">
                                        <a href="#" style="color: var(--info); font-size: 0.75rem;">
                                            📄 View Document
                                        </a>
                                    </div>
                                    <?php endif; ?>
                                </div>
                            </td>
                            <td class="desktop-only">
                                <div style="font-size: 0.875rem;">
                                    <div><?php echo date('M d, Y', strtotime($application['created_at'])); ?></div>
                                    <div style="color: var(--gray-500); font-size: 0.75rem;">
                                        <?php echo date('H:i', strtotime($application['created_at'])); ?>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="actions">
                                    <a href="<?php echo BASE_URL; ?>/admin/applications/view/<?php echo $application['id']; ?>" 
                                       class="action-btn action-view">
                                        View
                                    </a>
                                    
                                    <?php if (in_array($userRole, ['admin', 'editor'])): ?>
                                    <a href="<?php echo BASE_URL; ?>/admin/applications/edit/<?php echo $application['id']; ?>" 
                                       class="action-btn action-edit">
                                        Edit
                                    </a>
                                    
                                    <?php if ($application['status'] == 'pending'): ?>
                                    <a href="#" class="action-btn action-review" onclick="updateStatus(<?php echo $application['id']; ?>, 'reviewed')">
                                        Review
                                    </a>
                                    <?php endif; ?>
                                    
                                    <a href="#" class="action-btn action-accept" onclick="updateStatus(<?php echo $application['id']; ?>, 'accepted')">
                                        Accept
                                    </a>
                                    <a href="#" class="action-btn action-reject" onclick="updateStatus(<?php echo $application['id']; ?>, 'rejected')">
                                        Reject
                                    </a>
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
                    <h3>No Applications Found</h3>
                    <p>There are no applications in the database yet. New applications will appear here when submitted.</p>
                    <?php if (in_array($userRole, ['admin', 'editor'])): ?>
                    <a href="<?php echo BASE_URL; ?>/admin/applications/create" class="btn btn-primary">
                        ＋ Create Test Application
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
                <a href="<?php echo BASE_URL; ?>/admin/applications/create" class="btn btn-primary">
                    ＋ Create New Application
                </a>
                <button class="btn btn-success" onclick="exportApplications()">
                    📥 Export to CSV
                </button>
                <button class="btn btn-warning" onclick="bulkReview()">
                    🔍 Bulk Review Selected
                </button>
                <button class="btn btn-info" onclick="generateReport()">
                    📊 Generate Report
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
            const program = document.getElementById('filterProgram').value;
            const year = document.getElementById('filterYear').value;
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
                
                // Program filter
                if (program && row.dataset.program !== program) {
                    show = false;
                }
                
                // Year filter
                if (year && row.dataset.year !== year) {
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
            document.getElementById('filterProgram').value = '';
            document.getElementById('filterYear').value = '';
            document.getElementById('filterSearch').value = '';
            document.getElementById('filterDateFrom').value = '';
            document.getElementById('filterDateTo').value = '';
            
            const rows = document.querySelectorAll('tbody tr');
            rows.forEach(row => {
                row.style.display = '';
            });
        }
        
        // Update application status
        function updateStatus(applicationId, newStatus) {
            const statusText = {
                'pending': 'Pending',
                'reviewed': 'Under Review',
                'accepted': 'Accepted',
                'rejected': 'Rejected'
            }[newStatus];
            
            if (confirm(`Change application status to "${statusText}"?`)) {
                // In a real implementation, this would make an API call
                fetch(`${BASE_URL}/admin/api/applications/${applicationId}/status`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({ status: newStatus })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('Status updated successfully!');
                        location.reload();
                    } else {
                        alert('Error updating status: ' + data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Error updating status. Please try again.');
                });
            }
        }
        
        // Bulk actions
        function bulkReview() {
            alert('Bulk review functionality would be implemented here');
        }
        
        function exportApplications() {
            alert('Export to CSV functionality would be implemented here');
        }
        
        function generateReport() {
            alert('Report generation functionality would be implemented here');
        }
        
        // Auto-refresh applications every 5 minutes
        setInterval(function() {
            // In a real implementation, this would fetch new applications via AJAX
            console.log('Auto-refreshing applications...');
        }, 5 * 60 * 1000);
        
        // Initialize filters with today's date
        document.getElementById('filterDateTo').value = new Date().toISOString().split('T')[0];
        
        // Add keyboard shortcuts
        document.addEventListener('keydown', function(e) {
            // Ctrl/Cmd + N for new application
            if ((e.ctrlKey || e.metaKey) && e.key === 'n') {
                e.preventDefault();
                window.location.href = '<?php echo BASE_URL; ?>/admin/applications/create';
            }
            
            // Ctrl/Cmd + F for filters
            if ((e.ctrlKey || e.metaKey) && e.key === 'f') {
                e.preventDefault();
                toggleFilters();
                document.getElementById('filterSearch').focus();
            }
            
            // Ctrl/Cmd + R for refresh
            if ((e.ctrlKey || e.metaKey) && e.key === 'r') {
                e.preventDefault();
                location.reload();
            }
        });
    </script>
</body>
</html>