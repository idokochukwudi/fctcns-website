<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create New User - FCT CNS</title>
    <style>
        /* Same base styles as index.php */
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
        
        .input-group {
            display: flex;
            gap: 0.5rem;
        }
        
        .input-group .form-control {
            flex: 1;
        }
        
        .input-group .btn {
            white-space: nowrap;
        }
    </style>
</head>
<body>
    <!-- Simple header -->
    <header class="admin-header">
        <div class="header-title">
            <h1>Create New User</h1>
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

        <?php if (!empty($error)): ?>
        <div class="alert alert-danger"><?php echo $error; ?></div>
        <?php endif; ?>

        <div class="card">
            <div class="card-header">
                <h2>Create New User Account</h2>
            </div>
            <div class="card-body">
                <form method="POST" action="<?php echo $baseUrl ?? BASE_URL; ?>/admin/users/store">
                    <input type="hidden" name="_csrf_token" value="<?php echo $csrf_token ?? ''; ?>">
                    
                    <div class="row">
                        <div class="form-group">
                            <label for="username" class="form-label">Username *</label>
                            <input type="text" class="form-control" id="username" name="username" 
                                   value="<?php echo htmlspecialchars($formData['username'] ?? ''); ?>" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="email" class="form-label">Email *</label>
                            <input type="email" class="form-control" id="email" name="email" 
                                   value="<?php echo htmlspecialchars($formData['email'] ?? ''); ?>" required>
                        </div>
                    </div>

                    <div class="row">
                        <div class="form-group">
                            <label for="full_name" class="form-label">Full Name *</label>
                            <input type="text" class="form-control" id="full_name" name="full_name" 
                                   value="<?php echo htmlspecialchars($formData['full_name'] ?? ''); ?>" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="phone" class="form-label">Phone Number</label>
                            <input type="tel" class="form-control" id="phone" name="phone" 
                                   value="<?php echo htmlspecialchars($formData['phone'] ?? ''); ?>">
                        </div>
                    </div>

                    <div class="row">
                        <div class="form-group">
                            <label for="password" class="form-label">Password *</label>
                            <div class="input-group">
                                <input type="password" class="form-control" id="password" name="password" required>
                                <button type="button" class="btn btn-secondary" onclick="generatePassword()">
                                    Generate
                                </button>
                            </div>
                            <div class="form-text">Minimum 6 characters</div>
                        </div>
                        
                        <div class="form-group">
                            <label for="confirm_password" class="form-label">Confirm Password *</label>
                            <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
                        </div>
                    </div>

                    <div class="row">
                        <div class="form-group">
                            <label for="role" class="form-label">Role *</label>
                            <select class="form-control" id="role" name="role" required>
                                <option value="">Select Role</option>
                                <?php foreach ($roles as $key => $label): ?>
                                <option value="<?php echo $key; ?>" 
                                    <?php echo ($formData['role'] ?? '') == $key ? 'selected' : ''; ?>>
                                    <?php echo $label; ?>
                                </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label for="department" class="form-label">Department</label>
                            <input type="text" class="form-control" id="department" name="department" 
                                   value="<?php echo htmlspecialchars($formData['department'] ?? ''); ?>" 
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
                                       <?php echo isset($formData['is_active']) && $formData['is_active'] ? 'checked' : 'checked'; ?>>
                                <label class="form-check-label" for="is_active">Active Account</label>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" id="must_change_password" name="must_change_password" value="1"
                                       <?php echo isset($formData['must_change_password']) && $formData['must_change_password'] ? 'checked' : ''; ?>>
                                <label class="form-check-label" for="must_change_password">User must change password on first login</label>
                            </div>
                        </div>
                    </div>

                    <!-- Permissions -->
                    <div class="permission-group">
                        <h6>Permissions</h6>
                        <p class="form-text">Select permissions for this user</p>
                        
                        <div class="row">
                            <?php 
                            // Group permissions - UPDATED TO INCLUDE RESEARCH MANAGEMENT GROUP
                            $groupedPermissions = [
                                // ADDED NEW GROUP AT THE TOP:
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
                                           <?php echo (isset($formData['permissions']) && in_array($key, $formData['permissions'])) ? 'checked' : ''; ?>>
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
                            Create User
                        </button>
                        <a href="<?php echo $baseUrl ?? BASE_URL; ?>/admin/users" class="btn btn-secondary">Cancel</a>
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
        
        // Role-based permission presets - UPDATED TO INCLUDE RESEARCH PERMISSIONS
        document.getElementById('role').addEventListener('change', function() {
            const role = this.value;
            const presets = {
                'admin': [
                    // Existing nominal roll permissions
                    'nominal_roll_view', 'nominal_roll_create', 'nominal_roll_edit', 'nominal_roll_delete',
                    'nominal_roll_bulk_upload', 'nominal_roll_export', 'nominal_roll_settings', 'nominal_roll_approve',
                    // ADDED RESEARCH PERMISSIONS
                    'research_view', 'research_create', 'research_edit', 'research_delete', 'research_publish',
                    // Other permissions
                    'user_view', 'user_create', 'user_edit', 'user_delete',
                    'application_view', 'application_edit', 'application_delete',
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
                // ADDED NEW ROLE: research_manager
                'research_manager': [
                    'research_view', 'research_create', 'research_edit', 'research_publish'
                    // Note: No research_delete for safety
                ]
            };
            
            // Clear all checkboxes
            document.querySelectorAll('input[name="permissions[]"]').forEach(cb => {
                cb.checked = false;
            });
            
            // Check preset permissions
            if (presets[role]) {
                presets[role].forEach(perm => {
                    const checkbox = document.getElementById('perm_' + perm);
                    if (checkbox) checkbox.checked = true;
                });
            }
        });
    </script>
</body>
</html>