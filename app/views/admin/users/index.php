<?php
// This view expects data from UserManagementController
// $users, $stats, $departments, $roles, $pagination, $filters should be passed from controller
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Management - FCT CNS</title>
    <style>
        /* Same CSS as before, but removed the path calculations at the top */
        :root {
            --admin-sidebar-width: 260px;
            --admin-header-height: 70px;
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
            --admin-gray-300: #cbd5e0;
            --admin-gray-600: #718096;
            --admin-gray-700: #4a5568;
            --admin-gray-800: #2d3748;
            --admin-gray-900: #1a202c;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background-color: var(--admin-gray-100);
            color: var(--admin-gray-800);
        }
        
        .admin-header {
            height: var(--admin-header-height);
            background: white;
            border-bottom: 1px solid var(--admin-gray-200);
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 2rem;
            position: sticky;
            top: 0;
            z-index: 50;
        }
        
        .header-title h1 {
            font-size: 1.5rem;
            font-weight: 600;
            color: var(--admin-gray-800);
        }
        
        .header-actions a {
            padding: 8px 16px;
            background: var(--admin-primary);
            color: white;
            text-decoration: none;
            border-radius: 6px;
            font-weight: 500;
        }
        
        .admin-content {
            padding: 2rem;
            min-height: calc(100vh - var(--admin-header-height));
        }
        
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }
        
        .stat-card {
            background: white;
            border-radius: 12px;
            padding: 1.5rem;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            border-left: 4px solid var(--admin-primary);
        }
        
        .stat-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 1rem;
        }
        
        .stat-icon {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            background: rgba(66, 153, 225, 0.1);
            color: var(--admin-primary-light);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
        }
        
        .stat-value {
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 0.25rem;
            color: var(--admin-gray-800);
        }
        
        .stat-label {
            font-size: 0.875rem;
            color: var(--admin-gray-600);
        }
        
        .filter-card {
            background: white;
            border-radius: 12px;
            padding: 1.5rem;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            margin-bottom: 1.5rem;
        }
        
        .filter-form {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
            align-items: end;
        }
        
        .form-group {
            margin-bottom: 0;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 500;
            color: var(--admin-gray-700);
            font-size: 0.875rem;
        }
        
        .form-control {
            width: 100%;
            padding: 0.5rem 0.75rem;
            border: 1px solid var(--admin-gray-300);
            border-radius: 6px;
            font-size: 0.875rem;
        }
        
        .users-table {
            background: white;
            border-radius: 12px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }
        
        .table-header {
            padding: 1.25rem 1.5rem;
            border-bottom: 1px solid var(--admin-gray-200);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .table-body {
            padding: 1.5rem;
        }
        
        .table {
            width: 100%;
            border-collapse: collapse;
        }
        
        .table th {
            text-align: left;
            padding: 0.75rem;
            font-weight: 600;
            color: var(--admin-gray-600);
            border-bottom: 2px solid var(--admin-gray-200);
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }
        
        .table td {
            padding: 1rem 0.75rem;
            border-bottom: 1px solid var(--admin-gray-100);
            vertical-align: middle;
        }
        
        .table tr:hover {
            background: var(--admin-gray-50);
        }
        
        .user-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            font-size: 1rem;
        }
        
        .user-info {
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }
        
        .user-details h4 {
            font-weight: 600;
            margin-bottom: 0.25rem;
        }
        
        .user-details .text-muted {
            font-size: 0.875rem;
            color: var(--admin-gray-600);
        }
        
        .badge {
            padding: 0.25rem 0.5rem;
            border-radius: 4px;
            font-size: 0.75rem;
            font-weight: 600;
            display: inline-block;
        }
        
        .badge-admin { background: var(--admin-danger); color: white; }
        .badge-editor { background: var(--admin-warning); color: var(--admin-gray-900); }
        .badge-viewer { background: var(--admin-info); color: white; }
        .badge-active { background: var(--admin-success); color: white; }
        .badge-inactive { background: var(--admin-gray-600); color: white; }
        
        .action-buttons {
            display: flex;
            gap: 0.5rem;
        }
        
        .btn {
            padding: 0.375rem 0.75rem;
            border-radius: 6px;
            text-decoration: none;
            font-size: 0.875rem;
            font-weight: 500;
            cursor: pointer;
            border: 1px solid transparent;
        }
        
        .btn-view {
            background: var(--admin-primary-light);
            color: white;
        }
        
        .btn-edit {
            background: var(--admin-warning);
            color: white;
        }
        
        .btn-deactivate {
            background: var(--admin-gray-300);
            color: var(--admin-gray-700);
        }
        
        .btn-activate {
            background: var(--admin-success);
            color: white;
        }
        
        .btn-reset {
            background: var(--admin-info);
            color: white;
        }
        
        .btn-delete {
            background: var(--admin-danger);
            color: white;
        }
        
        .btn:hover {
            opacity: 0.9;
        }
        
        .btn-primary {
            background: var(--admin-primary);
            color: white;
        }
        
        .btn-secondary {
            background: var(--admin-gray-300);
            color: var(--admin-gray-700);
        }
        
        .empty-state {
            text-align: center;
            padding: 3rem;
            color: var(--admin-gray-600);
        }
        
        .empty-state p {
            font-size: 1.125rem;
            margin-bottom: 0.5rem;
        }
    </style>
</head>
<body>
    <!-- Simple header -->
    <header class="admin-header">
        <div class="header-title">
            <h1>User Management</h1>
        </div>
        <div class="header-actions">
            <a href="<?php echo $baseUrl ?? BASE_URL; ?>/admin/dashboard" class="btn btn-primary">
                ← Back to Dashboard
            </a>
        </div>
    </header>
    
    <div class="admin-content">
        <!-- Stats Grid -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-header">
                    <div>
                        <div class="stat-value"><?php echo $stats['total'] ?? 0; ?></div>
                        <div class="stat-label">Total Users</div>
                    </div>
                    <div class="stat-icon">
                        👥
                    </div>
                </div>
            </div>
            
            <div class="stat-card">
                <div class="stat-header">
                    <div>
                        <div class="stat-value"><?php echo $stats['active'] ?? 0; ?></div>
                        <div class="stat-label">Active Users</div>
                    </div>
                    <div class="stat-icon">
                        ✓
                    </div>
                </div>
            </div>
            
            <div class="stat-card">
                <div class="stat-header">
                    <div>
                        <div class="stat-value"><?php echo $stats['admin_count'] ?? 0; ?></div>
                        <div class="stat-label">Administrators</div>
                    </div>
                    <div class="stat-icon">
                        ⚡
                    </div>
                </div>
            </div>
            
            <div class="stat-card">
                <div class="stat-header">
                    <div>
                        <div class="stat-value"><?php echo $stats['editor_count'] ?? 0; ?></div>
                        <div class="stat-label">Editors</div>
                    </div>
                    <div class="stat-icon">
                        ✏️
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Filters -->
        <div class="filter-card">
            <form method="GET" class="filter-form">
                <div class="form-group">
                    <label for="search">Search users</label>
                    <input type="text" id="search" name="search" class="form-control" 
                           placeholder="Name, username, or email" value="<?php echo htmlspecialchars($filters['search'] ?? ''); ?>">
                </div>
                
                <div class="form-group">
                    <label for="role">Role</label>
                    <select id="role" name="role" class="form-control">
                        <option value="">All Roles</option>
                        <?php foreach ($roles as $key => $label): ?>
                        <option value="<?php echo $key; ?>" <?php echo ($filters['role'] ?? '') === $key ? 'selected' : ''; ?>>
                            <?php echo $label; ?>
                        </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="status">Status</label>
                    <select id="status" name="status" class="form-control">
                        <option value="">All Status</option>
                        <option value="active" <?php echo ($filters['status'] ?? '') === 'active' ? 'selected' : ''; ?>>Active</option>
                        <option value="inactive" <?php echo ($filters['status'] ?? '') === 'inactive' ? 'selected' : ''; ?>>Inactive</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="department">Department</label>
                    <select id="department" name="department" class="form-control">
                        <option value="">All Departments</option>
                        <?php foreach ($departments as $dept): ?>
                        <option value="<?php echo htmlspecialchars($dept); ?>" 
                                <?php echo ($filters['department'] ?? '') === $dept ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($dept); ?>
                        </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <div class="form-group">
                    <button type="submit" class="btn btn-primary">Filter</button>
                    <a href="<?php echo $baseUrl ?? BASE_URL; ?>/admin/users" class="btn btn-secondary">Reset</a>
                </div>
            </form>
        </div>
        
        <!-- Users Table -->
        <div class="users-table">
            <div class="table-header">
                <h3 style="font-weight: 600; color: var(--admin-gray-800);">Users List</h3>
                <div>
                    <a href="<?php echo $baseUrl ?? BASE_URL; ?>/admin/users/create" class="btn" style="background: var(--admin-success); color: white;">
                        + Add User
                    </a>
                    <a href="#" onclick="exportUsers()" class="btn" style="background: var(--admin-info); color: white; margin-left: 0.5rem;">
                        📥 Export
                    </a>
                </div>
            </div>
            
            <div class="table-body">
                <?php if (empty($users)): ?>
                <div class="empty-state">
                    <p>No users found</p>
                    <p>Try adjusting your filters or create a new user.</p>
                </div>
                <?php else: ?>
                <table class="table">
                    <thead>
                        <tr>
                            <th>User</th>
                            <th>Role</th>
                            <th>Department</th>
                            <th>Status</th>
                            <th>Last Login</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($users as $user): ?>
                        <tr>
                            <td>
                                <div class="user-info">
                                    <div class="user-avatar">
                                        <?php echo strtoupper(substr($user['full_name'] ?: $user['username'], 0, 1)); ?>
                                    </div>
                                    <div class="user-details">
                                        <h4><?php echo htmlspecialchars($user['full_name'] ?: $user['username']); ?></h4>
                                        <div class="text-muted">
                                            @<?php echo htmlspecialchars($user['username']); ?>
                                        </div>
                                        <div class="text-muted" style="font-size: 0.75rem;">
                                            <?php echo htmlspecialchars($user['email']); ?>
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="badge badge-<?php echo $user['role']; ?>">
                                    <?php echo ucfirst($user['role']); ?>
                                </span>
                                <div style="font-size: 0.75rem; color: var(--admin-gray-600); margin-top: 0.25rem;">
                                    <?php echo $user['permission_count'] ?? 0; ?> permissions
                                </div>
                            </td>
                            <td>
                                <?php echo $user['department'] ? htmlspecialchars($user['department']) : '<span style="color: var(--admin-gray-400);">—</span>'; ?>
                            </td>
                            <td>
                                <?php if ($user['is_active']): ?>
                                <span class="badge badge-active">Active</span>
                                <?php else: ?>
                                <span class="badge badge-inactive">Inactive</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if ($user['last_login']): ?>
                                <div><?php echo date('M d, Y', strtotime($user['last_login'])); ?></div>
                                <div style="font-size: 0.75rem; color: var(--admin-gray-600);">
                                    <?php echo date('H:i', strtotime($user['last_login'])); ?>
                                </div>
                                <?php else: ?>
                                <span style="color: var(--admin-gray-400);">Never</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <div class="action-buttons">
                                    <a href="<?php echo $baseUrl ?? BASE_URL; ?>/admin/users/view/<?php echo $user['id']; ?>" 
                                       class="btn btn-view" title="View">
                                        👁️
                                    </a>
                                    <a href="<?php echo $baseUrl ?? BASE_URL; ?>/admin/users/edit/<?php echo $user['id']; ?>" 
                                       class="btn btn-edit" title="Edit">
                                        ✏️
                                    </a>
                                    
                                    <?php if ($user['id'] != ($_SESSION['user_id'] ?? 0)): ?>
                                    <?php if ($user['is_active']): ?>
                                    <form method="POST" action="<?php echo $baseUrl ?? BASE_URL; ?>/admin/users/toggle-status/<?php echo $user['id']; ?>" 
                                          style="display: inline;" onsubmit="return confirm('Deactivate this user?');">
                                        <input type="hidden" name="_csrf_token" value="<?php echo $csrf_token ?? ''; ?>">
                                        <input type="hidden" name="value" value="0">
                                        <button type="submit" class="btn btn-deactivate" title="Deactivate">
                                            ⏸️
                                        </button>
                                    </form>
                                    <?php else: ?>
                                    <form method="POST" action="<?php echo $baseUrl ?? BASE_URL; ?>/admin/users/toggle-status/<?php echo $user['id']; ?>" 
                                          style="display: inline;" onsubmit="return confirm('Activate this user?');">
                                        <input type="hidden" name="_csrf_token" value="<?php echo $csrf_token ?? ''; ?>">
                                        <input type="hidden" name="value" value="1">
                                        <button type="submit" class="btn btn-activate" title="Activate">
                                            ▶️
                                        </button>
                                    </form>
                                    <?php endif; ?>
                                    
                                    <form method="POST" action="<?php echo $baseUrl ?? BASE_URL; ?>/admin/users/reset-password/<?php echo $user['id']; ?>" 
                                          style="display: inline;" onsubmit="return confirm('Reset password for this user?');">
                                        <input type="hidden" name="_csrf_token" value="<?php echo $csrf_token ?? ''; ?>">
                                        <button type="submit" class="btn btn-reset" title="Reset Password">
                                            🔑
                                        </button>
                                    </form>
                                    
                                    <form method="POST" action="<?php echo $baseUrl ?? BASE_URL; ?>/admin/users/delete/<?php echo $user['id']; ?>" 
                                          style="display: inline;" onsubmit="return confirm('Delete this user permanently?');">
                                        <input type="hidden" name="_csrf_token" value="<?php echo $csrf_token ?? ''; ?>">
                                        <button type="submit" class="btn btn-delete" title="Delete">
                                            🗑️
                                        </button>
                                    </form>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                
                <?php if (isset($pagination) && $pagination['total'] > 1): ?>
                <div style="display: flex; justify-content: center; margin-top: 2rem;">
                    <div style="display: flex; gap: 0.5rem;">
                        <?php if ($pagination['current'] > 1): ?>
                        <a href="<?php echo $baseUrl ?? BASE_URL; ?>/admin/users?<?php echo http_build_query(array_merge($filters, ['page' => $pagination['current'] - 1])); ?>" 
                           class="btn btn-secondary">Previous</a>
                        <?php endif; ?>
                        
                        <?php for ($i = 1; $i <= $pagination['total']; $i++): ?>
                        <a href="<?php echo $baseUrl ?? BASE_URL; ?>/admin/users?<?php echo http_build_query(array_merge($filters, ['page' => $i])); ?>" 
                           class="btn <?php echo $pagination['current'] == $i ? 'btn-primary' : 'btn-secondary'; ?>">
                            <?php echo $i; ?>
                        </a>
                        <?php endfor; ?>
                        
                        <?php if ($pagination['current'] < $pagination['total']): ?>
                        <a href="<?php echo $baseUrl ?? BASE_URL; ?>/admin/users?<?php echo http_build_query(array_merge($filters, ['page' => $pagination['current'] + 1])); ?>" 
                           class="btn btn-secondary">Next</a>
                        <?php endif; ?>
                    </div>
                </div>
                <?php endif; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
    
    <script>
        function exportUsers() {
            const filters = {
                search: document.getElementById('search').value,
                role: document.getElementById('role').value,
                status: document.getElementById('status').value,
                department: document.getElementById('department').value
            };
            
            const params = new URLSearchParams();
            for (const key in filters) {
                if (filters[key]) {
                    params.append(key, filters[key]);
                }
            }
            
            window.location.href = '<?php echo $baseUrl ?? BASE_URL; ?>/admin/users/export?' + params.toString();
        }
        
        // Add CSRF token if missing
        document.addEventListener('DOMContentLoaded', function() {
            const csrfToken = '<?php echo $csrf_token ?? ""; ?>';
            if (csrfToken) {
                document.querySelectorAll('form').forEach(form => {
                    if (!form.querySelector('input[name="_csrf_token"]')) {
                        const csrfInput = document.createElement('input');
                        csrfInput.type = 'hidden';
                        csrfInput.name = '_csrf_token';
                        csrfInput.value = csrfToken;
                        form.appendChild(csrfInput);
                    }
                });
            }
        });
    </script>
</body>
</html>