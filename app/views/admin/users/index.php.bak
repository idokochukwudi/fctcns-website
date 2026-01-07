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
    <title>User Management - FCT CNS Admin</title>
    <style>
        :root {
            --primary: #2c5282;
            --primary-dark: #1a365d;
            --success: #38a169;
            --warning: #d69e2e;
            --danger: #e53e3e;
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
        
        .btn-danger {
            background: var(--danger);
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
        
        .table-container {
            background: white;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            margin-bottom: 30px;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            min-width: 800px;
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
        
        .badge-admin { 
            background: rgba(56, 161, 105, 0.1); 
            color: var(--success); 
            border: 1px solid rgba(56, 161, 105, 0.2);
        }
        
        .badge-editor { 
            background: rgba(66, 153, 225, 0.1); 
            color: var(--primary); 
            border: 1px solid rgba(66, 153, 225, 0.2);
        }
        
        .badge-viewer { 
            background: rgba(160, 174, 192, 0.1); 
            color: var(--gray-600); 
            border: 1px solid rgba(160, 174, 192, 0.2);
        }
        
        .status-active { 
            background: rgba(56, 161, 105, 0.1); 
            color: var(--success); 
            border: 1px solid rgba(56, 161, 105, 0.2);
        }
        
        .status-inactive { 
            background: rgba(237, 137, 54, 0.1); 
            color: var(--warning); 
            border: 1px solid rgba(237, 137, 54, 0.2);
        }
        
        .actions {
            display: flex;
            gap: 8px;
        }
        
        .action-btn {
            padding: 6px 12px;
            border-radius: 4px;
            font-size: 0.75rem;
            text-decoration: none;
            border: 1px solid transparent;
            transition: all 0.2s;
        }
        
        .action-edit {
            background: var(--primary);
            color: white;
        }
        
        .action-permissions {
            background: var(--success);
            color: white;
        }
        
        .action-delete {
            background: var(--danger);
            color: white;
        }
        
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
        
        .mobile-only { display: none; }
        
        @media (max-width: 768px) {
            .header { flex-direction: column; align-items: stretch; }
            .btn-group { justify-content: center; }
            table { min-width: unset; }
            .desktop-only { display: none; }
            .mobile-only { display: block; }
            
            th, td {
                padding: 10px;
            }
        }
        
        @media (max-width: 640px) {
            .stats-grid {
                grid-template-columns: 1fr;
            }
        }
        
        .last-login {
            font-size: 0.75rem;
            color: var(--gray-600);
            display: block;
            margin-top: 4px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>👥 User Management</h1>
            <div class="btn-group">
                <a href="<?php echo BASE_URL; ?>/admin/dashboard" class="btn btn-secondary">
                    ← Back to Dashboard
                </a>
                <?php if ($userRole === 'admin'): ?>
                <a href="<?php echo BASE_URL; ?>/admin/users/create" class="btn btn-primary">
                    ＋ Add New User
                </a>
                <?php endif; ?>
            </div>
        </div>
        
        <!-- User Statistics -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-value"><?php echo count($users); ?></div>
                <div class="stat-label">Total Users</div>
            </div>
            
            <?php
            $adminCount = 0;
            $editorCount = 0;
            $viewerCount = 0;
            $activeCount = 0;
            
            foreach ($users as $user) {
                if ($user['role'] === 'admin') $adminCount++;
                if ($user['role'] === 'editor') $editorCount++;
                if ($user['role'] === 'viewer') $viewerCount++;
                if ($user['is_active']) $activeCount++;
            }
            ?>
            
            <div class="stat-card">
                <div class="stat-value"><?php echo $adminCount; ?></div>
                <div class="stat-label">Administrators</div>
            </div>
            
            <div class="stat-card">
                <div class="stat-value"><?php echo $editorCount; ?></div>
                <div class="stat-label">Editors</div>
            </div>
            
            <div class="stat-card">
                <div class="stat-value"><?php echo $viewerCount; ?></div>
                <div class="stat-label">Viewers</div>
            </div>
        </div>
        
        <!-- Users Table -->
        <div class="table-container">
            <?php if (!empty($users)): ?>
                <table>
                    <thead>
                        <tr>
                            <th>User</th>
                            <th class="desktop-only">Contact</th>
                            <th>Role</th>
                            <th>Status</th>
                            <th class="desktop-only">Last Login</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($users as $user): ?>
                        <tr>
                            <td>
                                <div style="font-weight: 600; color: var(--gray-800);">
                                    <?php echo htmlspecialchars($user['full_name']); ?>
                                </div>
                                <div style="font-size: 0.875rem; color: var(--gray-600); margin-top: 4px;">
                                    @<?php echo htmlspecialchars($user['username']); ?>
                                </div>
                                <div class="mobile-only" style="font-size: 0.75rem; color: var(--gray-600); margin-top: 4px;">
                                    <?php echo htmlspecialchars($user['email']); ?>
                                </div>
                            </td>
                            <td class="desktop-only">
                                <div><?php echo htmlspecialchars($user['email']); ?></div>
                                <div style="font-size: 0.75rem; color: var(--gray-600); margin-top: 4px;">
                                    ID: <?php echo $user['id']; ?>
                                </div>
                            </td>
                            <td>
                                <span class="badge badge-<?php echo $user['role']; ?>">
                                    <?php echo ucfirst($user['role']); ?>
                                </span>
                                <?php if ($user['permission_count'] > 0): ?>
                                <div style="font-size: 0.75rem; color: var(--gray-600); margin-top: 4px;">
                                    <?php echo $user['permission_count']; ?> permission<?php echo $user['permission_count'] !== 1 ? 's' : ''; ?>
                                </div>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if ($user['is_active']): ?>
                                <span class="badge status-active">Active</span>
                                <?php else: ?>
                                <span class="badge status-inactive">Inactive</span>
                                <?php endif; ?>
                            </td>
                            <td class="desktop-only">
                                <?php if ($user['last_login']): ?>
                                <?php echo date('M d, Y', strtotime($user['last_login'])); ?>
                                <span class="last-login">
                                    <?php echo date('H:i', strtotime($user['last_login'])); ?>
                                </span>
                                <?php else: ?>
                                <span style="color: var(--gray-500); font-style: italic;">Never</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <div class="actions">
                                    <?php if ($userRole === 'admin' || $currentUserId == $user['id']): ?>
                                    <a href="#" class="action-btn action-edit">Edit</a>
                                    <?php endif; ?>
                                    
                                    <?php if ($userRole === 'admin'): ?>
                                    <a href="#" class="action-btn action-permissions">Permissions</a>
                                    
                                    <?php if ($currentUserId != $user['id']): ?>
                                    <a href="#" class="action-btn action-delete">Delete</a>
                                    <?php endif; ?>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <div class="empty-state">
                    <h3>No Users Found</h3>
                    <p>There are no users in the database yet. Add your first user to get started.</p>
                    <?php if ($userRole === 'admin'): ?>
                    <a href="<?php echo BASE_URL; ?>/admin/users/create" class="btn btn-primary">
                        ＋ Create First User
                    </a>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </div>
        
        <!-- User Management Tips -->
        <div style="background: #f0f9ff; border: 1px solid #bee3f8; border-radius: 8px; padding: 20px; margin-top: 30px;">
            <h3 style="margin-top: 0; color: var(--primary);">💡 User Management Tips</h3>
            <ul style="margin: 10px 0; padding-left: 20px; color: var(--gray-700);">
                <li><strong>Administrators</strong> have full access to all system features</li>
                <li><strong>Editors</strong> can manage applications, research, and news content</li>
                <li><strong>Viewers</strong> have read-only access to the admin panel</li>
                <li>Use permissions to grant specific access beyond role-based permissions</li>
                <li>Always keep at least one active administrator account</li>
            </ul>
        </div>
    </div>
    
    <script>
        // Confirm before deleting user
        document.addEventListener('click', function(e) {
            if (e.target.classList.contains('action-delete')) {
                e.preventDefault();
                if (confirm('Are you sure you want to delete this user? This action cannot be undone.')) {
                    // In a real implementation, this would make an API call
                    alert('Delete functionality would be implemented here');
                }
            }
            
            if (e.target.classList.contains('action-edit')) {
                e.preventDefault();
                // In a real implementation, this would redirect to edit page
                alert('Edit functionality would be implemented here');
            }
            
            if (e.target.classList.contains('action-permissions')) {
                e.preventDefault();
                // In a real implementation, this would redirect to permissions page
                alert('Permissions management would be implemented here');
            }
        });
        
        // Auto-refresh page every 5 minutes to show updated login times
        setTimeout(function() {
            location.reload();
        }, 5 * 60 * 1000);
        
        // Print functionality
        document.addEventListener('keydown', function(e) {
            if (e.ctrlKey && e.key === 'p') {
                e.preventDefault();
                window.print();
            }
        });
        
        // Search functionality (basic implementation)
        function searchUsers() {
            const searchTerm = document.getElementById('searchInput').value.toLowerCase();
            const rows = document.querySelectorAll('tbody tr');
            
            rows.forEach(row => {
                const text = row.textContent.toLowerCase();
                row.style.display = text.includes(searchTerm) ? '' : 'none';
            });
        }
        
        // Add search input dynamically
        const header = document.querySelector('.header');
        const searchDiv = document.createElement('div');
        searchDiv.innerHTML = `
            <div style="position: relative; width: 300px; max-width: 100%;">
                <input type="text" id="searchInput" placeholder="Search users..." 
                    style="width: 100%; padding: 10px 40px 10px 15px; border: 1px solid var(--gray-200); border-radius: 6px; font-size: 14px;"
                    onkeyup="searchUsers()">
                <div style="position: absolute; right: 12px; top: 50%; transform: translateY(-50%); color: var(--gray-500);">
                    🔍
                </div>
            </div>
        `;
        header.appendChild(searchDiv);
    </script>
</body>
</html>