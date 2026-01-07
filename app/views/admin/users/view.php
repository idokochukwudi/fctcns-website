<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Details - <?php echo htmlspecialchars($user['full_name'] ?? 'Unknown'); ?> - FCT CNS</title>
    <style>
        /* Same base styles */
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
            max-width: 1200px;
            margin: 0 auto;
        }
        
        .back-link {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            color: var(--admin-gray-600);
            text-decoration: none;
            margin-bottom: 1.5rem;
            font-weight: 500;
        }
        
        .back-link:hover {
            color: var(--admin-primary);
        }
        
        .card {
            background: white;
            border-radius: 12px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            margin-bottom: 1.5rem;
        }
        
        .card-header {
            padding: 1.5rem;
            border-bottom: 1px solid var(--admin-gray-200);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .card-header h2 {
            font-size: 1.25rem;
            font-weight: 600;
            color: var(--admin-gray-800);
        }
        
        .card-body {
            padding: 1.5rem;
        }
        
        .row {
            display: grid;
            grid-template-columns: 1fr 2fr;
            gap: 2rem;
        }
        
        @media (max-width: 768px) {
            .row {
                grid-template-columns: 1fr;
            }
        }
        
        .user-profile {
            text-align: center;
        }
        
        .avatar-large {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 3rem;
            font-weight: bold;
            margin: 0 auto 1.5rem;
        }
        
        .avatar-large img {
            width: 100%;
            height: 100%;
            border-radius: 50%;
            object-fit: cover;
        }
        
        .user-profile h3 {
            font-size: 1.5rem;
            font-weight: 600;
            margin-bottom: 0.5rem;
            color: var(--admin-gray-800);
        }
        
        .user-profile .username {
            color: var(--admin-gray-600);
            margin-bottom: 1rem;
        }
        
        .badge {
            padding: 0.5rem 1rem;
            border-radius: 6px;
            font-size: 0.875rem;
            font-weight: 600;
            display: inline-block;
            margin-bottom: 1.5rem;
        }
        
        .badge-admin { background: var(--admin-danger); color: white; }
        .badge-editor { background: var(--admin-warning); color: var(--admin-gray-900); }
        .badge-viewer { background: var(--admin-info); color: white; }
        .badge-active { background: var(--admin-success); color: white; }
        .badge-inactive { background: var(--admin-gray-600); color: white; }
        
        .list-group {
            list-style: none;
            background: var(--admin-gray-50);
            border-radius: 8px;
            overflow: hidden;
        }
        
        .list-group-item {
            padding: 1rem;
            border-bottom: 1px solid var(--admin-gray-200);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .list-group-item:last-child {
            border-bottom: none;
        }
        
        .list-group-item span:first-child {
            font-weight: 500;
            color: var(--admin-gray-700);
        }
        
        .list-group-item span:last-child {
            color: var(--admin-gray-800);
            text-align: right;
        }
        
        .quick-actions {
            margin-top: 1.5rem;
        }
        
        .quick-actions .btn {
            width: 100%;
            margin-bottom: 0.75rem;
        }
        
        .btn {
            padding: 0.75rem 1.5rem;
            border-radius: 8px;
            text-decoration: none;
            font-size: 0.875rem;
            font-weight: 500;
            cursor: pointer;
            border: none;
            transition: all 0.2s;
            display: inline-block;
            text-align: center;
        }
        
        .btn-primary {
            background: var(--admin-primary);
            color: white;
        }
        
        .btn-warning {
            background: var(--admin-warning);
            color: var(--admin-gray-900);
        }
        
        .btn-success {
            background: var(--admin-success);
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
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        
        .permission-list {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 1rem;
        }
        
        @media (max-width: 768px) {
            .permission-list {
                grid-template-columns: 1fr;
            }
        }
        
        .permission-item {
            background: var(--admin-gray-50);
            padding: 0.75rem;
            border-radius: 6px;
            border-left: 3px solid var(--admin-success);
        }
        
        .permission-item h6 {
            font-size: 0.875rem;
            font-weight: 600;
            color: var(--admin-gray-700);
            margin-bottom: 0.25rem;
        }
        
        .timeline {
            position: relative;
            padding-left: 2rem;
        }
        
        .timeline-item {
            position: relative;
            margin-bottom: 1.5rem;
            padding-bottom: 1.5rem;
            border-bottom: 1px solid var(--admin-gray-200);
        }
        
        .timeline-item:last-child {
            border-bottom: none;
            margin-bottom: 0;
            padding-bottom: 0;
        }
        
        .timeline-marker {
            position: absolute;
            left: -2rem;
            top: 0.5rem;
            width: 12px;
            height: 12px;
            border-radius: 50%;
            background: var(--admin-primary);
            border: 2px solid white;
            box-shadow: 0 0 0 3px rgba(66, 153, 225, 0.2);
        }
        
        .timeline-content h4 {
            font-size: 0.875rem;
            font-weight: 600;
            color: var(--admin-gray-800);
            margin-bottom: 0.25rem;
        }
        
        .timeline-content p {
            font-size: 0.875rem;
            color: var(--admin-gray-600);
            margin-bottom: 0.25rem;
        }
        
        .timeline-content small {
            font-size: 0.75rem;
            color: var(--admin-gray-500);
        }
    </style>
</head>
<body>
    <!-- Simple header -->
    <header class="admin-header">
        <div class="header-title">
            <h1>User Details</h1>
        </div>
        <div class="header-actions">
            <a href="<?php echo $baseUrl ?? BASE_URL; ?>/admin/dashboard" class="btn btn-primary">
                ← Back to Dashboard
            </a>
        </div>
    </header>
    
    <div class="admin-content">
        <a href="<?php echo $baseUrl ?? BASE_URL; ?>/admin/users" class="back-link">
            ← Back to Users
        </a>

        <div class="row">
            <!-- Left Column: User Info -->
            <div>
                <div class="card">
                    <div class="card-body">
                        <div class="user-profile">
                            <?php if ($user['profile_picture']): ?>
                            <div class="avatar-large">
                                <img src="<?php echo BASE_URL . '/uploads/profiles/' . htmlspecialchars($user['profile_picture']); ?>" 
                                     alt="<?php echo htmlspecialchars($user['full_name']); ?>">
                            </div>
                            <?php else: ?>
                            <div class="avatar-large">
                                <?php echo strtoupper(substr($user['full_name'] ?: $user['username'], 0, 1)); ?>
                            </div>
                            <?php endif; ?>
                            
                            <h3><?php echo htmlspecialchars($user['full_name'] ?: $user['username']); ?></h3>
                            <div class="username">@<?php echo htmlspecialchars($user['username']); ?></div>
                            
                            <div class="badge badge-<?php echo $user['role']; ?>">
                                <?php echo ucfirst($user['role']); ?>
                            </div>
                            
                            <div class="list-group">
                                <div class="list-group-item">
                                    <span>Status:</span>
                                    <span>
                                        <?php if ($user['is_active']): ?>
                                        <span class="badge badge-active">Active</span>
                                        <?php else: ?>
                                        <span class="badge badge-inactive">Inactive</span>
                                        <?php endif; ?>
                                    </span>
                                </div>
                                <div class="list-group-item">
                                    <span>Department:</span>
                                    <span><?php echo $user['department'] ? htmlspecialchars($user['department']) : '—'; ?></span>
                                </div>
                                <div class="list-group-item">
                                    <span>Email:</span>
                                    <span><?php echo htmlspecialchars($user['email']); ?></span>
                                </div>
                                <div class="list-group-item">
                                    <span>Phone:</span>
                                    <span><?php echo $user['phone'] ? htmlspecialchars($user['phone']) : '—'; ?></span>
                                </div>
                                <div class="list-group-item">
                                    <span>Last Login:</span>
                                    <span>
                                        <?php if ($user['last_login']): ?>
                                        <?php echo date('M d, Y H:i', strtotime($user['last_login'])); ?>
                                        <?php else: ?>
                                        Never
                                        <?php endif; ?>
                                    </span>
                                </div>
                                <div class="list-group-item">
                                    <span>Login Count:</span>
                                    <span><?php echo $user['login_count'] ?? 0; ?></span>
                                </div>
                                <div class="list-group-item">
                                    <span>Created:</span>
                                    <span><?php echo date('M d, Y', strtotime($user['created_at'])); ?></span>
                                </div>
                            </div>
                            
                            <div class="quick-actions">
                                <?php if ($user['id'] != ($_SESSION['user_id'] ?? 0)): ?>
                                <?php if ($user['is_active']): ?>
                                <form method="POST" action="<?php echo $baseUrl ?? BASE_URL; ?>/admin/users/toggle-status/<?php echo $user['id']; ?>" 
                                      onsubmit="return confirm('Deactivate this user?');">
                                    <input type="hidden" name="_csrf_token" value="<?php echo $csrf_token ?? ''; ?>">
                                    <input type="hidden" name="value" value="0">
                                    <button type="submit" class="btn btn-warning">
                                        Deactivate User
                                    </button>
                                </form>
                                <?php else: ?>
                                <form method="POST" action="<?php echo $baseUrl ?? BASE_URL; ?>/admin/users/toggle-status/<?php echo $user['id']; ?>" 
                                      onsubmit="return confirm('Activate this user?');">
                                    <input type="hidden" name="_csrf_token" value="<?php echo $csrf_token ?? ''; ?>">
                                    <input type="hidden" name="value" value="1">
                                    <button type="submit" class="btn btn-success">
                                        Activate User
                                    </button>
                                </form>
                                <?php endif; ?>
                                
                                <form method="POST" action="<?php echo $baseUrl ?? BASE_URL; ?>/admin/users/reset-password/<?php echo $user['id']; ?>" 
                                      onsubmit="return confirm('Reset password for this user?');">
                                    <input type="hidden" name="_csrf_token" value="<?php echo $csrf_token ?? ''; ?>">
                                    <button type="submit" class="btn btn-info">
                                        Reset Password
                                    </button>
                                </form>
                                
                                <a href="mailto:<?php echo htmlspecialchars($user['email']); ?>" class="btn btn-primary">
                                    Send Email
                                </a>
                                
                                <a href="<?php echo $baseUrl ?? BASE_URL; ?>/admin/users/edit/<?php echo $user['id']; ?>" class="btn btn-warning">
                                    Edit User
                                </a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Right Column: Details -->
            <div>
                <!-- Permissions -->
                <div class="card">
                    <div class="card-header">
                        <h2>Permissions</h2>
                        <span class="badge"><?php echo count($permissions); ?> permissions</span>
                    </div>
                    <div class="card-body">
                        <?php if (empty($permissions)): ?>
                        <p style="color: var(--admin-gray-600); text-align: center;">No permissions assigned</p>
                        <?php else: ?>
                        <div class="permission-list">
                            <?php 
                            // Group permissions
                            $grouped = [
                                'Nominal Roll' => [],
                                'User Management' => [],
                                'Applications' => [],
                                'System' => []
                            ];
                            
                            foreach ($permissions as $perm) {
                                $key = $perm['permission'];
                                if (strpos($key, 'nominal_roll') === 0) {
                                    $grouped['Nominal Roll'][] = $availablePermissions[$key] ?? $key;
                                } elseif (strpos($key, 'user_') === 0) {
                                    $grouped['User Management'][] = $availablePermissions[$key] ?? $key;
                                } elseif (strpos($key, 'application_') === 0) {
                                    $grouped['Applications'][] = $availablePermissions[$key] ?? $key;
                                } else {
                                    $grouped['System'][] = $availablePermissions[$key] ?? $key;
                                }
                            }
                            ?>
                            
                            <?php foreach ($grouped as $group => $perms): ?>
                            <?php if (!empty($perms)): ?>
                            <div class="permission-item">
                                <h6><?php echo $group; ?></h6>
                                <?php foreach ($perms as $perm): ?>
                                <div style="font-size: 0.875rem; color: var(--admin-gray-700); margin-bottom: 0.25rem;">
                                    ✓ <?php echo htmlspecialchars($perm); ?>
                                </div>
                                <?php endforeach; ?>
                            </div>
                            <?php endif; ?>
                            <?php endforeach; ?>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
                
                <!-- Recent Activity -->
                <?php if (!empty($activities)): ?>
                <div class="card">
                    <div class="card-header">
                        <h2>Recent Activity</h2>
                    </div>
                    <div class="card-body">
                        <div class="timeline">
                            <?php foreach ($activities as $activity): ?>
                            <div class="timeline-item">
                                <div class="timeline-marker"></div>
                                <div class="timeline-content">
                                    <h4><?php echo htmlspecialchars($activity['action']); ?></h4>
                                    <p><?php echo htmlspecialchars($activity['description']); ?></p>
                                    <small>
                                        <?php echo date('M d, H:i', strtotime($activity['created_at'])); ?>
                                        <?php if ($activity['ip_address']): ?>
                                        • IP: <?php echo htmlspecialchars($activity['ip_address']); ?>
                                        <?php endif; ?>
                                    </small>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</body>
</html>