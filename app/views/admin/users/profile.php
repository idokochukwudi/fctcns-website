<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Profile - FCT CNS</title>
    <style>
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
            min-height: 100vh;
        }
        
        .app-header {
            height: 70px;
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
        
        .header-actions {
            display: flex;
            align-items: center;
            gap: 1rem;
        }
        
        .user-menu {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            cursor: pointer;
            padding: 0.5rem;
            border-radius: 6px;
        }
        
        .user-menu:hover {
            background: var(--admin-gray-100);
        }
        
        .user-avatar {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
        }
        
        .user-avatar img {
            width: 100%;
            height: 100%;
            border-radius: 50%;
            object-fit: cover;
        }
        
        .app-content {
            max-width: 1000px;
            margin: 2rem auto;
            padding: 0 2rem;
        }
        
        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
        }
        
        .page-title h2 {
            font-size: 1.75rem;
            font-weight: 600;
            color: var(--admin-gray-800);
        }
        
        .page-subtitle {
            color: var(--admin-gray-600);
            margin-top: 0.25rem;
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
        }
        
        .card-header h3 {
            font-size: 1.125rem;
            font-weight: 600;
            color: var(--admin-gray-800);
        }
        
        .card-body {
            padding: 1.5rem;
        }
        
        .row {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 1.5rem;
        }
        
        @media (max-width: 768px) {
            .row {
                grid-template-columns: 1fr;
            }
            
            .app-content {
                padding: 1rem;
            }
            
            .app-header {
                padding: 0 1rem;
            }
        }
        
        .form-group {
            margin-bottom: 1.5rem;
        }
        
        .form-label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 500;
            color: var(--admin-gray-700);
            font-size: 0.875rem;
        }
        
        .form-control {
            width: 100%;
            padding: 0.75rem;
            border: 1px solid var(--admin-gray-300);
            border-radius: 8px;
            font-size: 0.875rem;
            transition: border-color 0.2s;
        }
        
        .form-control:focus {
            outline: none;
            border-color: var(--admin-primary);
            box-shadow: 0 0 0 3px rgba(66, 153, 225, 0.1);
        }
        
        .form-text {
            font-size: 0.75rem;
            color: var(--admin-gray-600);
            margin-top: 0.25rem;
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
        }
        
        .btn-primary {
            background: var(--admin-primary);
            color: white;
        }
        
        .btn-secondary {
            background: var(--admin-gray-300);
            color: var(--admin-gray-700);
        }
        
        .btn:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        
        .text-end {
            text-align: right;
        }
        
        .alert {
            padding: 1rem;
            border-radius: 8px;
            margin-bottom: 1.5rem;
            border: 1px solid transparent;
        }
        
        .alert-danger {
            background: #fed7d7;
            color: #9b2c2c;
            border-color: #fc8181;
        }
        
        .alert-success {
            background: #c6f6d5;
            color: #276749;
            border-color: #9ae6b4;
        }
        
        .badge {
            padding: 0.5rem 1rem;
            border-radius: 6px;
            font-size: 0.875rem;
            font-weight: 600;
            display: inline-block;
        }
        
        .badge-primary { background: var(--admin-primary); color: white; }
        .badge-success { background: var(--admin-success); color: white; }
        .badge-warning { background: var(--admin-warning); color: var(--admin-gray-900); }
        
        .avatar-section {
            display: flex;
            align-items: center;
            gap: 1.5rem;
            margin-bottom: 1.5rem;
        }
        
        .avatar-preview {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2.5rem;
            font-weight: bold;
            overflow: hidden;
            flex-shrink: 0;
        }
        
        .avatar-preview img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        
        .avatar-actions {
            flex: 1;
        }
        
        .info-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 1rem;
            margin-top: 1rem;
        }
        
        .info-item {
            padding: 0.75rem;
            background: var(--admin-gray-50);
            border-radius: 8px;
        }
        
        .info-label {
            font-size: 0.75rem;
            color: var(--admin-gray-600);
            margin-bottom: 0.25rem;
        }
        
        .info-value {
            font-weight: 500;
            color: var(--admin-gray-800);
        }
        
        .logout-btn {
            background: var(--admin-danger);
            color: white;
            padding: 0.5rem 1rem;
            border-radius: 6px;
            text-decoration: none;
            font-size: 0.875rem;
        }
    </style>
</head>
<body>
    <!-- Header -->
    <header class="app-header">
        <div class="header-title">
            <h1>FCT CNS</h1>
        </div>
        <div class="header-actions">
            <a href="<?php echo $baseUrl ?? BASE_URL; ?>/dashboard" class="btn btn-secondary">
                Dashboard
            </a>
            <div class="user-menu" id="userMenu">
                <div class="user-avatar" id="userAvatar">
                    <?php if ($user['profile_picture']): ?>
                    <img src="<?php echo BASE_URL . '/uploads/profiles/' . htmlspecialchars($user['profile_picture']); ?>" 
                         alt="Profile">
                    <?php else: ?>
                    <?php echo strtoupper(substr($user['full_name'] ?: $user['username'], 0, 1)); ?>
                    <?php endif; ?>
                </div>
                <span><?php echo htmlspecialchars($user['full_name'] ?? $user['username']); ?></span>
            </div>
        </div>
    </header>
    
    <div class="app-content">
        <div class="page-header">
            <div class="page-title">
                <h2>My Profile</h2>
                <div class="page-subtitle">
                    Manage your account information and settings
                </div>
            </div>
            <div>
                <span class="badge badge-primary"><?php echo ucfirst($user['role']); ?></span>
                <span class="badge badge-success">Active</span>
            </div>
        </div>

        <?php if (!empty($error)): ?>
        <div class="alert alert-danger"><?php echo $error; ?></div>
        <?php endif; ?>

        <?php if (!empty($success)): ?>
        <div class="alert alert-success"><?php echo $success; ?></div>
        <?php endif; ?>

        <!-- Profile Info -->
        <div class="card">
            <div class="card-header">
                <h3>Personal Information</h3>
            </div>
            <div class="card-body">
                <form method="POST" action="<?php echo $baseUrl ?? BASE_URL; ?>/profile/update" 
                      enctype="multipart/form-data">
                    <input type="hidden" name="_csrf_token" value="<?php echo $csrf_token ?? ''; ?>">
                    
                    <div class="avatar-section">
                        <div class="avatar-preview" id="avatarPreview">
                            <?php if ($user['profile_picture']): ?>
                            <img src="<?php echo BASE_URL . '/uploads/profiles/' . htmlspecialchars($user['profile_picture']); ?>" 
                                 alt="Current Profile Picture" id="currentAvatar">
                            <?php else: ?>
                            <div><?php echo strtoupper(substr($user['full_name'] ?: $user['username'], 0, 1)); ?></div>
                            <?php endif; ?>
                        </div>
                        <div class="avatar-actions">
                            <label for="profile_picture" class="form-label">Profile Picture</label>
                            <input type="file" class="form-control" id="profile_picture" name="profile_picture" 
                                   accept="image/*" onchange="previewImage(event)">
                            <div class="form-text">Maximum 2MB. Allowed: JPG, PNG, GIF</div>
                            <?php if ($user['profile_picture']): ?>
                            <div style="margin-top: 0.5rem;">
                                <input type="checkbox" id="remove_picture" name="remove_picture" value="1">
                                <label for="remove_picture" class="form-check-label">Remove current profile picture</label>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="form-group">
                            <label for="full_name" class="form-label">Full Name *</label>
                            <input type="text" class="form-control" id="full_name" name="full_name" 
                                   value="<?php echo htmlspecialchars($user['full_name'] ?? ''); ?>" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="email" class="form-label">Email *</label>
                            <input type="email" class="form-control" id="email" name="email" 
                                   value="<?php echo htmlspecialchars($user['email'] ?? ''); ?>" required>
                        </div>
                    </div>

                    <div class="row">
                        <div class="form-group">
                            <label for="phone" class="form-label">Phone Number</label>
                            <input type="tel" class="form-control" id="phone" name="phone" 
                                   value="<?php echo htmlspecialchars($user['phone'] ?? ''); ?>">
                        </div>
                        
                        <div class="form-group">
                            <label for="department" class="form-label">Department</label>
                            <input type="text" class="form-control" id="department" name="department" 
                                   value="<?php echo htmlspecialchars($user['department'] ?? ''); ?>" 
                                   list="department-list">
                            <datalist id="department-list">
                                <?php foreach ($departments as $dept): ?>
                                <option value="<?php echo htmlspecialchars($dept); ?>">
                                <?php endforeach; ?>
                            </datalist>
                        </div>
                    </div>

                    <div class="text-end">
                        <button type="submit" class="btn btn-primary">
                            Save Changes
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Account Security -->
        <div class="card">
            <div class="card-header">
                <h3>Security</h3>
            </div>
            <div class="card-body">
                <form method="POST" action="<?php echo $baseUrl ?? BASE_URL; ?>/profile/change-password">
                    <input type="hidden" name="_csrf_token" value="<?php echo $csrf_token ?? ''; ?>">
                    
                    <div class="row">
                        <div class="form-group">
                            <label for="current_password" class="form-label">Current Password *</label>
                            <input type="password" class="form-control" id="current_password" name="current_password" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="new_password" class="form-label">New Password *</label>
                            <input type="password" class="form-control" id="new_password" name="new_password" required>
                            <div class="form-text">Minimum 6 characters</div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="form-group">
                            <label for="confirm_new_password" class="form-label">Confirm New Password *</label>
                            <input type="password" class="form-control" id="confirm_new_password" name="confirm_new_password" required>
                        </div>
                        
                        <div class="form-group">
                            <div style="padding-top: 2rem;">
                                <button type="submit" class="btn btn-primary">
                                    Change Password
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Account Info -->
        <div class="card">
            <div class="card-header">
                <h3>Account Information</h3>
            </div>
            <div class="card-body">
                <div class="info-grid">
                    <div class="info-item">
                        <div class="info-label">Username</div>
                        <div class="info-value"><?php echo htmlspecialchars($user['username']); ?></div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Role</div>
                        <div class="info-value"><?php echo ucfirst($user['role']); ?></div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Last Login</div>
                        <div class="info-value">
                            <?php if ($user['last_login']): ?>
                            <?php echo date('M d, Y H:i', strtotime($user['last_login'])); ?>
                            <?php else: ?>
                            Never
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Account Created</div>
                        <div class="info-value"><?php echo date('M d, Y', strtotime($user['created_at'])); ?></div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Total Logins</div>
                        <div class="info-value"><?php echo $user['login_count'] ?? 0; ?></div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Status</div>
                        <div class="info-value">
                            <?php if ($user['is_active']): ?>
                            <span class="badge badge-success">Active</span>
                            <?php else: ?>
                            <span class="badge badge-warning">Inactive</span>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                
                <div style="margin-top: 1.5rem; text-align: center;">
                    <a href="<?php echo $baseUrl ?? BASE_URL; ?>/logout" class="logout-btn">
                        Logout
                    </a>
                </div>
            </div>
        </div>
    </div>

    <script>
        function previewImage(event) {
            const input = event.target;
            const preview = document.getElementById('avatarPreview');
            const headerAvatar = document.getElementById('userAvatar');
            
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                const currentAvatar = document.getElementById('currentAvatar');
                
                reader.onload = function(e) {
                    // Update form preview
                    if (currentAvatar) {
                        currentAvatar.src = e.target.result;
                    } else {
                        preview.innerHTML = `<img src="${e.target.result}" alt="Preview" id="currentAvatar">`;
                    }
                    
                    // Update header avatar
                    if (headerAvatar.querySelector('img')) {
                        headerAvatar.querySelector('img').src = e.target.result;
                    } else {
                        headerAvatar.innerHTML = `<img src="${e.target.result}" alt="Profile">`;
                    }
                };
                
                reader.readAsDataURL(input.files[0]);
            }
        }
        
        // User menu functionality
        document.getElementById('userMenu').addEventListener('click', function() {
            window.location.href = '<?php echo $baseUrl ?? BASE_URL; ?>/profile';
        });
    </script>
</body>
</html>