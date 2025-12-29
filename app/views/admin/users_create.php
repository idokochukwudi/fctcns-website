<?php
// Get the absolute path to the root
$rootPath = dirname(__DIR__, 3);
require_once $rootPath . '/app/config/constants.php';
require_once APP_PATH . '/config/session.php';
require_once APP_PATH . '/middleware/AuthMiddleware.php';
AuthMiddleware::authenticate();

$userRole = $_SESSION['user_role'] ?? 'viewer';
$error = $error ?? '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create New User - FCT CNS Admin</title>
    <style>
        :root {
            --primary: #2c5282;
            --primary-dark: #1a365d;
            --success: #38a169;
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
            max-width: 800px;
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
            padding: 12px 30px;
            font-size: 1rem;
        }
        
        .btn:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }
        
        .btn-primary:hover { background: var(--primary-dark); }
        .btn-secondary:hover { background: var(--gray-700); }
        
        .form-container {
            background: white;
            border-radius: 8px;
            padding: 30px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            margin-bottom: 30px;
        }
        
        .form-group {
            margin-bottom: 25px;
        }
        
        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }
        
        @media (max-width: 768px) {
            .form-row {
                grid-template-columns: 1fr;
            }
        }
        
        label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
            color: var(--gray-700);
        }
        
        .required:after {
            content: " *";
            color: var(--danger);
        }
        
        input, select, textarea {
            width: 100%;
            padding: 12px;
            border: 1px solid var(--gray-200);
            border-radius: 6px;
            font-size: 16px;
            transition: all 0.2s;
            box-sizing: border-box;
        }
        
        input:focus, select:focus, textarea:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(44, 82, 130, 0.1);
        }
        
        .form-help {
            font-size: 0.875rem;
            color: var(--gray-600);
            margin-top: 6px;
        }
        
        .error-message {
            background: rgba(229, 62, 62, 0.1);
            color: var(--danger);
            padding: 15px;
            border-radius: 6px;
            margin-bottom: 25px;
            border: 1px solid rgba(229, 62, 62, 0.2);
        }
        
        .password-strength {
            height: 4px;
            background: var(--gray-200);
            border-radius: 2px;
            margin-top: 8px;
            overflow: hidden;
        }
        
        .password-strength-meter {
            height: 100%;
            width: 0%;
            background: var(--danger);
            transition: width 0.3s, background 0.3s;
        }
        
        .checkbox-group {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .checkbox-group input[type="checkbox"] {
            width: auto;
        }
        
        .permissions-section {
            background: var(--gray-50);
            border-radius: 6px;
            padding: 20px;
            margin-top: 30px;
        }
        
        .permissions-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 15px;
            margin-top: 15px;
        }
        
        .permission-item {
            display: flex;
            align-items: center;
            gap: 8px;
        }
        
        .role-description {
            background: #f0f9ff;
            border: 1px solid #bee3f8;
            border-radius: 6px;
            padding: 15px;
            margin-top: 10px;
        }
        
        .role-description h4 {
            margin-top: 0;
            color: var(--primary);
        }
        
        .form-actions {
            display: flex;
            gap: 15px;
            margin-top: 30px;
            padding-top: 30px;
            border-top: 1px solid var(--gray-200);
        }
        
        @media (max-width: 768px) {
            .form-actions {
                flex-direction: column;
            }
            
            .btn {
                width: 100%;
                justify-content: center;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>👤 Create New User</h1>
            <div>
                <a href="<?php echo BASE_URL; ?>/admin/users" class="btn btn-secondary">
                    ← Back to Users
                </a>
            </div>
        </div>
        
        <?php if ($error): ?>
        <div class="error-message">
            <strong>Error:</strong> <?php echo htmlspecialchars($error); ?>
        </div>
        <?php endif; ?>
        
        <form method="POST" action="<?php echo BASE_URL; ?>/admin/users/store" class="form-container">
            <div class="form-row">
                <div class="form-group">
                    <label for="username" class="required">Username</label>
                    <input type="text" id="username" name="username" required 
                        placeholder="e.g., johndoe" 
                        pattern="[a-zA-Z0-9_]+" 
                        title="Only letters, numbers, and underscores allowed">
                    <div class="form-help">Only letters, numbers, and underscores allowed</div>
                </div>
                
                <div class="form-group">
                    <label for="email" class="required">Email Address</label>
                    <input type="email" id="email" name="email" required 
                        placeholder="user@example.com">
                    <div class="form-help">User's email address for notifications</div>
                </div>
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label for="full_name" class="required">Full Name</label>
                    <input type="text" id="full_name" name="full_name" required 
                        placeholder="John Doe">
                </div>
                
                <div class="form-group">
                    <label for="role" class="required">Role</label>
                    <select id="role" name="role" required onchange="updateRoleDescription(this.value)">
                        <option value="editor">Editor</option>
                        <option value="admin">Administrator</option>
                        <option value="viewer">Viewer</option>
                    </select>
                </div>
            </div>
            
            <div class="role-description" id="roleDescription">
                <h4>👑 Editor Role</h4>
                <p>Editors can manage applications, research publications, and news content. They can create, edit, and publish content but cannot manage users or system settings.</p>
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label for="password" class="required">Password</label>
                    <input type="password" id="password" name="password" required 
                        minlength="6" 
                        onkeyup="checkPasswordStrength(this.value)">
                    <div class="password-strength">
                        <div class="password-strength-meter" id="passwordStrengthMeter"></div>
                    </div>
                    <div class="form-help">Minimum 6 characters. Include uppercase, lowercase, and numbers for better security.</div>
                </div>
                
                <div class="form-group">
                    <label for="confirm_password" class="required">Confirm Password</label>
                    <input type="password" id="confirm_password" name="confirm_password" required
                        onkeyup="checkPasswordMatch()">
                    <div class="form-help" id="passwordMatchMessage"></div>
                </div>
            </div>
            
            <div class="form-group">
                <div class="checkbox-group">
                    <input type="checkbox" id="is_active" name="is_active" value="1" checked>
                    <label for="is_active" style="margin-bottom: 0;">Account is active</label>
                </div>
                <div class="form-help">Inactive users cannot log into the system</div>
            </div>
            
            <!-- Default Permissions Section -->
            <div class="permissions-section">
                <h3 style="margin-top: 0;">🔐 Default Permissions</h3>
                <p>These permissions will be automatically assigned based on the selected role:</p>
                
                <div class="permissions-grid" id="defaultPermissions">
                    <!-- Permissions will be populated by JavaScript based on role -->
                </div>
            </div>
            
            <div class="form-actions">
                <button type="submit" class="btn btn-success">
                    ✓ Create User
                </button>
                <a href="<?php echo BASE_URL; ?>/admin/users" class="btn btn-secondary">
                    Cancel
                </a>
            </div>
        </form>
        
        <!-- Quick User Creation Tips -->
        <div style="background: #f0f9ff; border: 1px solid #bee3f8; border-radius: 8px; padding: 20px;">
            <h3 style="margin-top: 0; color: var(--primary);">💡 Quick Tips</h3>
            <ul style="margin: 10px 0; padding-left: 20px; color: var(--gray-700);">
                <li><strong>Administrators:</strong> Full system access. Create sparingly.</li>
                <li><strong>Editors:</strong> Content managers. Ideal for department heads.</li>
                <li><strong>Viewers:</strong> Read-only access. Perfect for auditors or observers.</li>
                <li>Consider sending login credentials via secure email after creation.</li>
                <li>Users can be granted additional permissions after creation.</li>
            </ul>
        </div>
    </div>
    
    <script>
        // Role descriptions
        const roleDescriptions = {
            'admin': {
                title: '👑 Administrator',
                description: 'Administrators have full access to all system features including user management, system settings, and all content. Use this role sparingly for trusted personnel.',
                permissions: [
                    'Manage all users',
                    'Manage system settings',
                    'Create/edit/delete all content',
                    'View all reports',
                    'Manage permissions',
                    'Access audit logs'
                ]
            },
            'editor': {
                title: '📝 Editor',
                description: 'Editors can manage applications, research publications, and news content. They can create, edit, and publish content but cannot manage users or system settings.',
                permissions: [
                    'Manage applications',
                    'Create/edit research publications',
                    'Publish news articles',
                    'Upload files',
                    'Moderate comments',
                    'View analytics'
                ]
            },
            'viewer': {
                title: '👁️ Viewer',
                description: 'Viewers have read-only access to the admin panel. They can view content and reports but cannot make any changes. Ideal for auditors or observers.',
                permissions: [
                    'View applications',
                    'Read research publications',
                    'Browse news',
                    'View reports',
                    'Export data',
                    'Read-only access'
                ]
            }
        };
        
        function updateRoleDescription(role) {
            const desc = roleDescriptions[role];
            const descDiv = document.getElementById('roleDescription');
            const permissionsDiv = document.getElementById('defaultPermissions');
            
            // Update description
            descDiv.innerHTML = `
                <h4>${desc.title}</h4>
                <p>${desc.description}</p>
            `;
            
            // Update permissions list
            permissionsDiv.innerHTML = desc.permissions.map(perm => `
                <div class="permission-item">
                    <input type="checkbox" checked disabled>
                    <span>${perm}</span>
                </div>
            `).join('');
        }
        
        // Initialize role description
        updateRoleDescription('editor');
        
        // Password strength checker
        function checkPasswordStrength(password) {
            const meter = document.getElementById('passwordStrengthMeter');
            let strength = 0;
            
            if (password.length >= 6) strength += 25;
            if (/[a-z]/.test(password)) strength += 25;
            if (/[A-Z]/.test(password)) strength += 25;
            if (/[0-9]/.test(password)) strength += 25;
            
            meter.style.width = strength + '%';
            
            if (strength < 25) {
                meter.style.background = 'var(--danger)';
            } else if (strength < 50) {
                meter.style.background = '#f6ad55';
            } else if (strength < 75) {
                meter.style.background = '#68d391';
            } else {
                meter.style.background = 'var(--success)';
            }
        }
        
        // Password match checker
        function checkPasswordMatch() {
            const password = document.getElementById('password').value;
            const confirmPassword = document.getElementById('confirm_password').value;
            const message = document.getElementById('passwordMatchMessage');
            
            if (!confirmPassword) {
                message.textContent = '';
                message.style.color = '';
                return;
            }
            
            if (password === confirmPassword) {
                message.textContent = '✓ Passwords match';
                message.style.color = 'var(--success)';
            } else {
                message.textContent = '✗ Passwords do not match';
                message.style.color = 'var(--danger)';
            }
        }
        
        // Form validation
        document.querySelector('form').addEventListener('submit', function(e) {
            const password = document.getElementById('password').value;
            const confirmPassword = document.getElementById('confirm_password').value;
            const username = document.getElementById('username').value;
            
            // Check password match
            if (password !== confirmPassword) {
                e.preventDefault();
                alert('Passwords do not match. Please check and try again.');
                document.getElementById('confirm_password').focus();
                return;
            }
            
            // Check password length
            if (password.length < 6) {
                e.preventDefault();
                alert('Password must be at least 6 characters long.');
                document.getElementById('password').focus();
                return;
            }
            
            // Check username format
            if (!/^[a-zA-Z0-9_]+$/.test(username)) {
                e.preventDefault();
                alert('Username can only contain letters, numbers, and underscores.');
                document.getElementById('username').focus();
                return;
            }
        });
        
        // Auto-generate username from email
        document.getElementById('email').addEventListener('blur', function() {
            const email = this.value;
            const usernameField = document.getElementById('username');
            
            // Only auto-fill if username is empty
            if (email && !usernameField.value) {
                const username = email.split('@')[0].replace(/[^a-zA-Z0-9_]/g, '_').toLowerCase();
                usernameField.value = username;
            }
        });
        
        // Auto-generate full name from email (simple version)
        document.getElementById('email').addEventListener('blur', function() {
            const email = this.value;
            const fullNameField = document.getElementById('full_name');
            
            // Only auto-fill if full name is empty
            if (email && !fullNameField.value) {
                const namePart = email.split('@')[0].replace(/[._]/g, ' ');
                const nameParts = namePart.split(' ');
                const capitalized = nameParts.map(part => 
                    part.charAt(0).toUpperCase() + part.slice(1)
                ).join(' ');
                fullNameField.value = capitalized;
            }
        });
    </script>
</body>
</html>