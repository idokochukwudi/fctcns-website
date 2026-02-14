<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit User - <?php echo htmlspecialchars($user['full_name'] ?? 'User'); ?> - FCT CNS</title>
    <style>
        /* Same base styles as create.php */
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
        }
        
        .card-header {
            padding: 1.5rem;
            border-bottom: 1px solid var(--admin-gray-200);
            display: flex;
            align-items: center;
            gap: 1rem;
        }
        
        .card-header h2 {
            font-size: 1.25rem;
            font-weight: 600;
            color: var(--admin-gray-800);
        }
        
        .card-body {
            padding: 1.5rem;
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
        
        .row {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 1.5rem;
        }
        
        @media (max-width: 768px) {
            .row {
                grid-template-columns: 1fr;
            }
        }
        
        .form-check {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            margin-bottom: 0.75rem;
        }
        
        .form-check-input {
            width: 1rem;
            height: 1rem;
        }
        
        .form-check-label {
            font-size: 0.875rem;
            color: var(--admin-gray-700);
        }
        
        .permission-group {
            background: var(--admin-gray-50);
            border-radius: 8px;
            padding: 1.25rem;
            margin-bottom: 1.5rem;
            border: 1px solid var(--admin-gray-200);
        }
        
        .permission-group h6 {
            color: var(--admin-primary);
            border-bottom: 2px solid var(--admin-primary);
            padding-bottom: 0.75rem;
            margin-bottom: 1rem;
            font-weight: 600;
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
        
        .btn-warning {
            background: var(--admin-warning);
            color: var(--admin-gray-900);
        }
        
        .btn-danger {
            background: var(--admin-danger);
            color: white;
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
        
        .badge-active { background: var(--admin-success); color: white; }
        .badge-inactive { background: var(--admin-gray-600); color: white; }
        
        .avatar-section {
            display: flex;
            align-items: center;
            gap: 1.5rem;
            margin-bottom: 1.5rem;
        }
        
        .avatar-preview {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2rem;
            font-weight: bold;
            overflow: hidden;
        }
        
        .avatar-preview img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        
        .avatar-actions {
            flex: 1;
        }
        
        .input-group {
            display: flex;
            gap: 0.5rem;
        }
        
        .input-group .form-control {
            flex: 1;
        }
    </style>
</head>
<body>
    <!-- Simple header -->
    <header class="admin-header">
        <div class="header-title">
            <h1>Edit User</h1>
        </div>
        <div class="header-actions">
            <a href="<?php echo $baseUrl ?? BASE_URL; ?>/admin/dashboard" class="btn btn-primary">
                ← Back to Dashboard
            </a>
        </div>
    </header>
    
    <div class="admin-content">
        <a href="<?php echo $baseUrl ?? BASE_URL; ?>/admin/users/view/<?php echo $user['id']; ?>" class="back-link">
            ← Back to User Details
        </a>

        <?php if (!empty($error)): ?>
        <div class="alert alert-danger"><?php echo $error; ?></div>
        <?php endif; ?>

        <?php if (!empty($success)): ?>
        <div class="alert alert-success"><?php echo $success; ?></div>
        <?php endif; ?>

        <div class="card">
            <div class="card-header">
                <h2>Edit User: <?php echo htmlspecialchars($user['full_name'] ?? $user['username']); ?></h2>
                <?php if ($user['is_active']): ?>
                <span class="badge badge-active">Active</span>
                <?php else: ?>
                <span class="badge badge-inactive">Inactive</span>
                <?php endif; ?>
            </div>
            <div class="card-body">
                <form method="POST" action="<?php echo $baseUrl ?? BASE_URL; ?>/admin/users/update/<?php echo $user['id']; ?>" 
                      enctype="multipart/form-data">
                    <input type="hidden" name="_csrf_token" value="<?php echo $csrf_token ?? ''; ?>">
                    <input type="hidden" name="_method" value="PUT">
                    
                    <!-- Avatar Section -->
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
                            <label for="username" class="form-label">Username *</label>
                            <input type="text" class="form-control" id="username" name="username" 
                                   value="<?php echo htmlspecialchars($user['username'] ?? ''); ?>" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="email" class="form-label">Email *</label>
                            <input type="email" class="form-control" id="email" name="email" 
                                   value="<?php echo htmlspecialchars($user['email'] ?? ''); ?>" required>
                        </div>
                    </div>

                    <div class="row">
                        <div class="form-group">
                            <label for="full_name" class="form-label">Full Name *</label>
                            <input type="text" class="form-control" id="full_name" name="full_name" 
                                   value="<?php echo htmlspecialchars($user['full_name'] ?? ''); ?>" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="phone" class="form-label">Phone Number</label>
                            <input type="tel" class="form-control" id="phone" name="phone" 
                                   value="<?php echo htmlspecialchars($user['phone'] ?? ''); ?>">
                        </div>
                    </div>

                    <div class="row">
                        <div class="form-group">
                            <label for="password" class="form-label">Password (Leave blank to keep current)</label>
                            <div class="input-group">
                                <input type="password" class="form-control" id="password" name="password">
                                <button type="button" class="btn btn-secondary" onclick="generatePassword()">
                                    Generate
                                </button>
                            </div>
                            <div class="form-text">Minimum 6 characters</div>
                        </div>
                        
                        <div class="form-group">
                            <label for="confirm_password" class="form-label">Confirm Password</label>
                            <input type="password" class="form-control" id="confirm_password" name="confirm_password">
                        </div>
                    </div>

                    <div class="row">
                        <div class="form-group">
                            <label for="role" class="form-label">Role *</label>
                            <select class="form-control" id="role" name="role" required>
                                <option value="">Select Role</option>
                                <?php foreach ($roles as $key => $label): ?>
                                <option value="<?php echo $key; ?>" 
                                    <?php echo ($user['role'] ?? '') == $key ? 'selected' : ''; ?>>
                                    <?php echo $label; ?>
                                </option>
                                <?php endforeach; ?>
                            </select>
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

                    <div class="row">
                        <div class="form-group">
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" id="is_active" name="is_active" value="1" 
                                       <?php echo isset($user['is_active']) && $user['is_active'] ? 'checked' : ''; ?>>
                                <label class="form-check-label" for="is_active">Active Account</label>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" id="must_change_password" name="must_change_password" value="1"
                                       <?php echo isset($user['must_change_password']) && $user['must_change_password'] ? 'checked' : ''; ?>>
                                <label class="form-check-label" for="must_change_password">User must change password on next login</label>
                            </div>
                        </div>
                    </div>

                    <!-- Permissions -->
                    <div class="permission-group">
                        <h6>Permissions</h6>
                        <p class="form-text">Select permissions for this user</p>
                        
                        <div class="row">
                            <?php 
                            // Group permissions - UPDATED TO INCLUDE NEWS MANAGEMENT GROUP
                            $groupedPermissions = [
                                // ADDED NEWS MANAGEMENT GROUP AT THE TOP
                                'News & Events Management' => [ // ADD THIS NEW GROUP
                                    'news_view' => 'View News & Events',
                                    'news_create' => 'Create News & Events',
                                    'news_edit' => 'Edit News & Events',
                                    'news_delete' => 'Delete News & Events',
                                    'news_publish' => 'Publish/Unpublish',
                                    'news_manage_categories' => 'Manage Categories',
                                    'news_upload_images' => 'Upload Images'
                                ],
                                'Research Management' => [
                                    'research_view' => 'View Research Publications',
                                    'research_create' => 'Create Publications',
                                    'research_edit' => 'Edit Publications',
                                    'research_delete' => 'Delete Publications',
                                    'research_publish' => 'Publish/Unpublish'
                                ],
                                'Nominal Roll' => [
                                    'nominal_roll_view' => 'View Nominal Roll',
                                    'nominal_roll_create' => 'Create Nominal Roll',
                                    'nominal_roll_edit' => 'Edit Nominal Roll',
                                    'nominal_roll_delete' => 'Delete Nominal Roll',
                                    'nominal_roll_bulk_upload' => 'Bulk Upload',
                                    'nominal_roll_export' => 'Export Data',
                                    'nominal_roll_settings' => 'Manage Settings',
                                    'nominal_roll_approve' => 'Approve Drafts'
                                ],
                                'User Management' => [
                                    'user_view' => 'View Users',
                                    'user_create' => 'Create Users',
                                    'user_edit' => 'Edit Users',
                                    'user_delete' => 'Delete Users'
                                ],
                                'Applications' => [
                                    'application_view' => 'View Applications',
                                    'application_edit' => 'Edit Applications',
                                    'application_delete' => 'Delete Applications'
                                ],
                                'System' => [
                                    'system_settings' => 'Manage System Settings',
                                    'system_backup' => 'Backup System',
                                    'system_reports' => 'View Reports'
                                ]
                            ];
                            
                            // Get user's current permissions - FIXED: Check if $permissions is an array
                            $userPermissions = [];
                            if (is_array($permissions)) {
                                foreach ($permissions as $perm) {
                                    // Handle both array and object formats
                                    if (is_array($perm) && isset($perm['permission'])) {
                                        $userPermissions[] = $perm['permission'];
                                    } elseif (is_string($perm)) {
                                        $userPermissions[] = $perm;
                                    } elseif (is_object($perm) && isset($perm->permission)) {
                                        $userPermissions[] = $perm->permission;
                                    }
                                }
                            } elseif (is_string($permissions)) {
                                // If permissions is a comma-separated string
                                $userPermissions = array_map('trim', explode(',', $permissions));
                            }
                            ?>
                            
                            <?php foreach ($groupedPermissions as $group => $perms): ?>
                            <div class="form-group">
                                <h6 style="color: var(--admin-gray-700); font-size: 0.875rem; margin-bottom: 0.75rem;"><?php echo $group; ?></h6>
                                <?php foreach ($perms as $key => $label): ?>
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input" 
                                           id="perm_<?php echo $key; ?>" 
                                           name="permissions[]" 
                                           value="<?php echo $key; ?>"
                                           <?php echo in_array($key, $userPermissions) ? 'checked' : ''; ?>>
                                    <label class="form-check-label" for="perm_<?php echo $key; ?>">
                                        <?php echo $label; ?>
                                    </label>
                                </div>
                                <?php endforeach; ?>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <div class="text-end">
                        <button type="submit" class="btn btn-primary">
                            Update User
                        </button>
                        <a href="<?php echo $baseUrl ?? BASE_URL; ?>/admin/users/view/<?php echo $user['id']; ?>" class="btn btn-secondary">Cancel</a>
                        
                        <?php if ($user['id'] != ($_SESSION['user_id'] ?? 0)): ?>
                        <button type="button" class="btn btn-danger" onclick="confirmDelete()">
                            Delete User
                        </button>
                        <?php endif; ?>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function generatePassword() {
            const chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*';
            let password = '';
            
            // Ensure at least one of each type
            password += 'abcdefghijklmnopqrstuvwxyz'[Math.floor(Math.random() * 26)];
            password += 'ABCDEFGHIJKLMNOPQRSTUVWXYZ'[Math.floor(Math.random() * 26)];
            password += '0123456789'[Math.floor(Math.random() * 10)];
            password += '!@#$%^&*'[Math.floor(Math.random() * 8)];
            
            // Fill the rest
            for (let i = 4; i < 12; i++) {
                password += chars[Math.floor(Math.random() * chars.length)];
            }
            
            // Shuffle
            password = password.split('').sort(() => Math.random() - 0.5).join('');
            
            document.getElementById('password').value = password;
            document.getElementById('confirm_password').value = password;
            
            alert('Generated password: ' + password);
        }
        
        function previewImage(event) {
            const input = event.target;
            const preview = document.getElementById('avatarPreview');
            
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                const currentAvatar = document.getElementById('currentAvatar');
                
                reader.onload = function(e) {
                    if (currentAvatar) {
                        currentAvatar.src = e.target.result;
                    } else {
                        preview.innerHTML = `<img src="${e.target.result}" alt="Preview" id="currentAvatar">`;
                    }
                };
                
                reader.readAsDataURL(input.files[0]);
            }
        }
        
        function confirmDelete() {
            if (confirm('Are you sure you want to delete this user?\n\nThis action cannot be undone.')) {
                window.location.href = '<?php echo $baseUrl ?? BASE_URL; ?>/admin/users/delete/<?php echo $user['id']; ?>';
            }
        }
        
        // Role-based permission presets - UPDATED TO INCLUDE NEWS MANAGER
        document.getElementById('role').addEventListener('change', function() {
            const role = this.value;
            const presets = {
                'admin': [
                    // Nominal Roll
                    'nominal_roll_view', 'nominal_roll_create', 'nominal_roll_edit', 'nominal_roll_delete',
                    'nominal_roll_bulk_upload', 'nominal_roll_export', 'nominal_roll_settings', 'nominal_roll_approve',
                    // Research
                    'research_view', 'research_create', 'research_edit', 'research_delete', 'research_publish',
                    // News (for admin)
                    'news_view', 'news_create', 'news_edit', 'news_delete', 'news_publish', 
                    'news_manage_categories', 'news_upload_images',
                    // User Management
                    'user_view', 'user_create', 'user_edit', 'user_delete',
                    // Applications
                    'application_view', 'application_edit', 'application_delete',
                    // System
                    'system_settings', 'system_backup', 'system_reports'
                ],
                'editor': [
                    'nominal_roll_view', 'nominal_roll_create', 'nominal_roll_edit',
                    'nominal_roll_bulk_upload', 'nominal_roll_export',
                    'application_view', 'application_edit'
                ],
                'viewer': ['nominal_roll_view', 'application_view'],
                'moderator': [
                    'nominal_roll_view', 'nominal_roll_edit', 'nominal_roll_approve',
                    'application_view', 'application_edit'
                ],
                'supervisor': [
                    'nominal_roll_view', 'nominal_roll_create', 'nominal_roll_edit',
                    'application_view', 'system_reports'
                ],
                'nominal_roll_user': [
                    'nominal_roll_view', 'nominal_roll_create', 'nominal_roll_edit', 'nominal_roll_export'
                ],
                'research_manager': [
                    'research_view', 'research_create', 'research_edit', 'research_publish'
                    // No research_delete for safety
                ],
                // ADD THIS NEW PRESET - NEWS MANAGER
                'news_manager': [
                    'news_view', 'news_create', 'news_edit', 'news_delete', 
                    'news_publish', 'news_manage_categories', 'news_upload_images'
                    // No research permissions - intentionally excluded
                ]
            };
            
            // Check preset permissions
            if (presets[role]) {
                if (confirm('Apply role-based permission presets?\n\nThis will override current permission selections.')) {
                    // Clear all checkboxes
                    document.querySelectorAll('input[name="permissions[]"]').forEach(cb => {
                        cb.checked = false;
                    });
                    
                    // Check preset permissions
                    presets[role].forEach(perm => {
                        const checkbox = document.getElementById('perm_' + perm);
                        if (checkbox) checkbox.checked = true;
                    });
                }
            }
        });
    </script>
</body>
</html>