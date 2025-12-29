<?php
// Require authentication
require_once __DIR__ . '/../../../app/middleware/AuthMiddleware.php';
AuthMiddleware::requireAnyRole(['admin', 'editor']);

// Include database
require_once __DIR__ . '/../../../app/config/database.php';
$db = Database::getInstance();
$conn = $db->getConnection();

// Get user info
require_once __DIR__ . '/../../../app/config/session.php';
$userRole = $_SESSION['user_role'];

// Get filter parameters
$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$status = isset($_GET['status']) ? trim($_GET['status']) : '';
$program = isset($_GET['program']) ? trim($_GET['program']) : '';
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$limit = 20;
$offset = ($page - 1) * $limit;

// Build query
$query = "SELECT * FROM applications WHERE 1=1";
$params = [];
$types = '';

if (!empty($search)) {
    $query .= " AND (first_name LIKE ? OR last_name LIKE ? OR email LIKE ? OR application_number LIKE ?)";
    $searchTerm = "%{$search}%";
    $params = array_merge($params, [$searchTerm, $searchTerm, $searchTerm, $searchTerm]);
}

if (!empty($status) && $status !== 'all') {
    $query .= " AND status = ?";
    $params[] = $status;
}

if (!empty($program) && $program !== 'all') {
    $query .= " AND program_applied = ?";
    $params[] = $program;
}

// Get total count
$countQuery = "SELECT COUNT(*) as total FROM applications WHERE 1=1";
$countParams = [];

if (!empty($search)) {
    $countQuery .= " AND (first_name LIKE ? OR last_name LIKE ? OR email LIKE ? OR application_number LIKE ?)";
    $countParams = array_merge($countParams, [$searchTerm, $searchTerm, $searchTerm, $searchTerm]);
}

if (!empty($status) && $status !== 'all') {
    $countQuery .= " AND status = ?";
    $countParams[] = $status;
}

if (!empty($program) && $program !== 'all') {
    $countQuery .= " AND program_applied = ?";
    $countParams[] = $program;
}

$stmt = $conn->prepare($countQuery);
$stmt->execute($countParams);
$total = $stmt->fetch()['total'];
$totalPages = ceil($total / $limit);

// Get applications
$query .= " ORDER BY created_at DESC LIMIT ? OFFSET ?";
$params[] = $limit;
$params[] = $offset;

$stmt = $conn->prepare($query);
$stmt->execute($params);
$applications = $stmt->fetchAll();

// Get distinct programs for filter
$programs = $conn->query("SELECT DISTINCT program_applied FROM applications ORDER BY program_applied")->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Applications Management - FCT CNS Admin</title>
    <link rel="stylesheet" href="/fctcns-website/public/admin/assets/css/admin.css">
    <style>
        /* Add styles for applications page */
        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
        }
        
        .filters-card {
            background: white;
            border-radius: 12px;
            padding: 1.5rem;
            margin-bottom: 2rem;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }
        
        .filter-form {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
            align-items: end;
        }
        
        .filter-group label {
            display: block;
            margin-bottom: 0.5rem;
            color: var(--admin-gray-700);
            font-weight: 500;
            font-size: 0.875rem;
        }
        
        .filter-select, .filter-input {
            width: 100%;
            padding: 0.625rem;
            border: 1px solid var(--admin-gray-300);
            border-radius: 6px;
            background: white;
        }
        
        .filter-buttons {
            display: flex;
            gap: 0.5rem;
        }
        
        .btn {
            padding: 0.625rem 1.25rem;
            border-radius: 6px;
            font-weight: 500;
            cursor: pointer;
            border: none;
            transition: all 0.2s;
        }
        
        .btn-primary {
            background: var(--admin-primary);
            color: white;
        }
        
        .btn-secondary {
            background: var(--admin-gray-200);
            color: var(--admin-gray-700);
        }
        
        .btn-sm {
            padding: 0.375rem 0.75rem;
            font-size: 0.875rem;
        }
        
        .table-container {
            background: white;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }
        
        .data-table {
            width: 100%;
            border-collapse: collapse;
        }
        
        .data-table th {
            background: var(--admin-gray-50);
            padding: 1rem;
            text-align: left;
            font-weight: 600;
            color: var(--admin-gray-700);
            border-bottom: 1px solid var(--admin-gray-200);
        }
        
        .data-table td {
            padding: 1rem;
            border-bottom: 1px solid var(--admin-gray-100);
        }
        
        .data-table tr:hover {
            background: var(--admin-gray-50);
        }
        
        .pagination {
            display: flex;
            justify-content: center;
            gap: 0.5rem;
            margin-top: 2rem;
            padding: 1rem;
        }
        
        .page-link {
            padding: 0.5rem 0.75rem;
            border: 1px solid var(--admin-gray-300);
            border-radius: 6px;
            text-decoration: none;
            color: var(--admin-gray-700);
            transition: all 0.2s;
        }
        
        .page-link:hover, .page-link.active {
            background: var(--admin-primary);
            color: white;
            border-color: var(--admin-primary);
        }
        
        .action-buttons {
            display: flex;
            gap: 0.5rem;
        }
        
        .badge {
            padding: 0.25rem 0.5rem;
            border-radius: 4px;
            font-size: 0.75rem;
            font-weight: 600;
        }
        
        .badge-pending { background: #fef3c7; color: #92400e; }
        .badge-approved { background: #d1fae5; color: #065f46; }
        .badge-rejected { background: #fee2e2; color: #991b1b; }
    </style>
</head>
<body>
    <!-- Include Admin Header -->
    <?php include __DIR__ . '/../includes/header.php'; ?>
    
    <main class="admin-main">
        <div class="admin-content">
            <div class="page-header">
                <div>
                    <h1>Applications Management</h1>
                    <p style="color: var(--admin-gray-600); margin-top: 0.5rem;">
                        Total: <?php echo $total; ?> applications found
                    </p>
                </div>
                <div>
                    <a href="/admin/applications/create" class="btn btn-primary">
                        <svg style="width: 16px; height: 16px; margin-right: 8px;" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd"/>
                        </svg>
                        New Application
                    </a>
                </div>
            </div>
            
            <!-- Filters -->
            <div class="filters-card">
                <form method="GET" class="filter-form">
                    <div class="filter-group">
                        <label>Search</label>
                        <input type="text" 
                               name="search" 
                               class="filter-input" 
                               placeholder="Name, email, or application number"
                               value="<?php echo htmlspecialchars($search); ?>">
                    </div>
                    
                    <div class="filter-group">
                        <label>Status</label>
                        <select name="status" class="filter-select">
                            <option value="all">All Status</option>
                            <option value="pending" <?php echo $status === 'pending' ? 'selected' : ''; ?>>Pending</option>
                            <option value="under_review" <?php echo $status === 'under_review' ? 'selected' : ''; ?>>Under Review</option>
                            <option value="approved" <?php echo $status === 'approved' ? 'selected' : ''; ?>>Approved</option>
                            <option value="rejected" <?php echo $status === 'rejected' ? 'selected' : ''; ?>>Rejected</option>
                        </select>
                    </div>
                    
                    <div class="filter-group">
                        <label>Program</label>
                        <select name="program" class="filter-select">
                            <option value="all">All Programs</option>
                            <?php foreach ($programs as $programItem): ?>
                            <option value="<?php echo htmlspecialchars($programItem['program_applied']); ?>"
                                    <?php echo $program === $programItem['program_applied'] ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($programItem['program_applied']); ?>
                            </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <div class="filter-group filter-buttons">
                        <button type="submit" class="btn btn-primary">
                            <svg style="width: 16px; height: 16px; margin-right: 8px;" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd"/>
                            </svg>
                            Apply Filters
                        </button>
                        <a href="/admin/applications" class="btn btn-secondary">
                            Clear
                        </a>
                    </div>
                </form>
            </div>
            
            <!-- Applications Table -->
            <div class="table-container">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>App. No.</th>
                            <th>Applicant</th>
                            <th>Program</th>
                            <th>Applied On</th>
                            <th>Status</th>
                            <th>Payment</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($applications)): ?>
                        <tr>
                            <td colspan="7" style="text-align: center; padding: 3rem; color: var(--admin-gray-600);">
                                No applications found. <a href="/admin/applications/create">Create one?</a>
                            </td>
                        </tr>
                        <?php else: ?>
                            <?php foreach ($applications as $app): ?>
                            <tr>
                                <td>
                                    <strong><?php echo htmlspecialchars($app['application_number']); ?></strong>
                                </td>
                                <td>
                                    <div style="font-weight: 500;">
                                        <?php echo htmlspecialchars($app['first_name'] . ' ' . $app['last_name']); ?>
                                    </div>
                                    <div style="font-size: 0.875rem; color: var(--admin-gray-600);">
                                        <?php echo htmlspecialchars($app['email']); ?>
                                    </div>
                                </td>
                                <td><?php echo htmlspecialchars($app['program_applied']); ?></td>
                                <td><?php echo date('M d, Y', strtotime($app['application_date'])); ?></td>
                                <td>
                                    <span class="badge badge-<?php echo $app['status']; ?>">
                                        <?php echo ucfirst(str_replace('_', ' ', $app['status'])); ?>
                                    </span>
                                </td>
                                <td>
                                    <?php if ($app['payment_status'] === 'completed'): ?>
                                    <span style="color: var(--admin-success); font-weight: 500;">Paid</span>
                                    <?php else: ?>
                                    <span style="color: var(--admin-warning);">Pending</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <div class="action-buttons">
                                        <a href="/admin/applications/view?id=<?php echo $app['id']; ?>" 
                                           class="btn btn-sm btn-secondary"
                                           title="View">
                                            <svg style="width: 14px; height: 14px;" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M10 12a2 2 0 100-4 2 2 0 000 4z"/>
                                                <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd"/>
                                            </svg>
                                        </a>
                                        <a href="/admin/applications/edit?id=<?php echo $app['id']; ?>" 
                                           class="btn btn-sm btn-secondary"
                                           title="Edit">
                                            <svg style="width: 14px; height: 14px;" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"/>
                                            </svg>
                                        </a>
                                        <?php if ($userRole === 'admin'): ?>
                                        <button onclick="deleteApplication(<?php echo $app['id']; ?>)" 
                                                class="btn btn-sm btn-secondary"
                                                title="Delete"
                                                style="color: var(--admin-danger);">
                                            <svg style="width: 14px; height: 14px;" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                            </svg>
                                        </button>
                                        <?php endif; ?>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination -->
            <?php if ($totalPages > 1): ?>
            <div class="pagination">
                <?php if ($page > 1): ?>
                <a href="?page=<?php echo $page - 1; ?>&search=<?php echo urlencode($search); ?>&status=<?php echo $status; ?>&program=<?php echo $program; ?>"
                   class="page-link">
                    Previous
                </a>
                <?php endif; ?>
                
                <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                    <?php if ($i == 1 || $i == $totalPages || ($i >= $page - 2 && $i <= $page + 2)): ?>
                    <a href="?page=<?php echo $i; ?>&search=<?php echo urlencode($search); ?>&status=<?php echo $status; ?>&program=<?php echo $program; ?>"
                       class="page-link <?php echo $i == $page ? 'active' : ''; ?>">
                        <?php echo $i; ?>
                    </a>
                    <?php elseif ($i == $page - 3 || $i == $page + 3): ?>
                    <span class="page-link">...</span>
                    <?php endif; ?>
                <?php endfor; ?>
                
                <?php if ($page < $totalPages): ?>
                <a href="?page=<?php echo $page + 1; ?>&search=<?php echo urlencode($search); ?>&status=<?php echo $status; ?>&program=<?php echo $program; ?>"
                   class="page-link">
                    Next
                </a>
                <?php endif; ?>
            </div>
            <?php endif; ?>
        </div>
    </main>
    
    <script>
        function deleteApplication(id) {
            if (confirm('Are you sure you want to delete this application? This action cannot be undone.')) {
                fetch('/admin/api/applications/' + id, {
                    method: 'DELETE',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        location.reload();
                    } else {
                        alert('Error: ' + data.message);
                    }
                })
                .catch(error => {
                    alert('Network error. Please try again.');
                });
            }
        }
        
        // Export functionality
        document.addEventListener('DOMContentLoaded', function() {
            const exportBtn = document.createElement('button');
            exportBtn.className = 'btn btn-secondary';
            exportBtn.innerHTML = `
                <svg style="width: 16px; height: 16px; margin-right: 8px;" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm3.293-7.707a1 1 0 011.414 0L9 10.586V3a1 1 0 112 0v7.586l1.293-1.293a1 1 0 111.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z" clip-rule="evenodd"/>
                </svg>
                Export to CSV
            `;
            
            exportBtn.addEventListener('click', function() {
                const params = new URLSearchParams(window.location.search);
                window.open('/admin/api/applications/export?' + params.toString(), '_blank');
            });
            
            document.querySelector('.page-header > div:last-child').appendChild(exportBtn);
        });
    </script>
</body>
</html>