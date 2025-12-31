<?php
// Get the absolute path to the root
$rootPath = dirname(__DIR__, 4);
require_once $rootPath . '/app/config/constants.php';
require_once APP_PATH . '/config/session.php';
require_once APP_PATH . '/middleware/AuthMiddleware.php';
AuthMiddleware::authenticate();

$userRole = $_SESSION['user_role'] ?? 'viewer';
if (!in_array($userRole, ['admin', 'editor'])) {
    header("Location: " . BASE_URL . "/admin/dashboard");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create New Application - FCT CNS Admin</title>
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
        
        .form-container {
            background: white;
            border-radius: 8px;
            padding: 30px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }
        
        .form-group {
            margin-bottom: 24px;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
            color: var(--gray-700);
        }
        
        .form-group .required::after {
            content: " *";
            color: var(--danger);
        }
        
        .form-group input[type="text"],
        .form-group input[type="email"],
        .form-group input[type="tel"],
        .form-group input[type="file"],
        .form-group select,
        .form-group textarea {
            width: 100%;
            padding: 12px;
            border: 1px solid var(--gray-200);
            border-radius: 6px;
            font-size: 14px;
            transition: border-color 0.2s;
            box-sizing: border-box;
        }
        
        .form-group input:focus,
        .form-group select:focus,
        .form-group textarea:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(44, 82, 130, 0.1);
        }
        
        .form-group textarea {
            min-height: 120px;
            resize: vertical;
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
        
        .file-upload {
            border: 2px dashed var(--gray-200);
            border-radius: 6px;
            padding: 40px 20px;
            text-align: center;
            cursor: pointer;
            transition: all 0.2s;
        }
        
        .file-upload:hover {
            border-color: var(--primary);
            background: var(--gray-50);
        }
        
        .file-upload input[type="file"] {
            display: none;
        }
        
        .file-upload-label {
            color: var(--primary);
            font-weight: 500;
            cursor: pointer;
            display: block;
        }
        
        .file-upload-label:hover {
            text-decoration: underline;
        }
        
        .file-info {
            font-size: 0.875rem;
            color: var(--gray-600);
            margin-top: 8px;
        }
        
        .form-actions {
            display: flex;
            justify-content: flex-end;
            gap: 15px;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid var(--gray-200);
        }
        
        .error-message {
            background: rgba(229, 62, 62, 0.1);
            color: var(--danger);
            padding: 15px;
            border-radius: 6px;
            margin-bottom: 20px;
            border-left: 4px solid var(--danger);
        }
        
        .success-message {
            background: rgba(56, 161, 105, 0.1);
            color: var(--success);
            padding: 15px;
            border-radius: 6px;
            margin-bottom: 20px;
            border-left: 4px solid var(--success);
        }
        
        .form-help {
            font-size: 0.875rem;
            color: var(--gray-600);
            margin-top: 6px;
        }
        
        .status-badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 600;
            margin-left: 10px;
        }
        
        .status-pending { 
            background: rgba(214, 158, 46, 0.1); 
            color: var(--warning); 
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>📝 Create New Application</h1>
            <div class="btn-group">
                <a href="<?php echo BASE_URL; ?>/admin/applications" class="btn btn-secondary">
                    ← Back to Applications
                </a>
                <a href="<?php echo BASE_URL; ?>/admin/dashboard" class="btn btn-secondary">
                    🏠 Dashboard
                </a>
            </div>
        </div>
        
        <?php if (isset($error) && $error): ?>
        <div class="error-message">
            <strong>Error:</strong> <?php echo htmlspecialchars($error); ?>
        </div>
        <?php endif; ?>
        
        <?php if (isset($success) && $success): ?>
        <div class="success-message">
            <strong>Success:</strong> <?php echo htmlspecialchars($success); ?>
        </div>
        <?php endif; ?>
        
        <div class="form-container">
            <form action="<?php echo BASE_URL; ?>/admin/applications/store" method="POST" enctype="multipart/form-data" id="applicationForm">
                <!-- Personal Information -->
                <h3 style="color: var(--primary); margin-top: 0; margin-bottom: 20px;">Personal Information</h3>
                
                <div class="form-row">
                    <div class="form-group">
                        <label class="required">First Name</label>
                        <input type="text" name="first_name" required 
                               placeholder="Enter first name" 
                               value="<?php echo htmlspecialchars($_POST['first_name'] ?? ''); ?>">
                    </div>
                    
                    <div class="form-group">
                        <label class="required">Last Name</label>
                        <input type="text" name="last_name" required 
                               placeholder="Enter last name"
                               value="<?php echo htmlspecialchars($_POST['last_name'] ?? ''); ?>">
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label class="required">Email Address</label>
                        <input type="email" name="email" required 
                               placeholder="Enter email address"
                               value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>">
                    </div>
                    
                    <div class="form-group">
                        <label class="required">Phone Number</label>
                        <input type="tel" name="phone" required 
                               placeholder="Enter phone number"
                               value="<?php echo htmlspecialchars($_POST['phone'] ?? ''); ?>">
                    </div>
                </div>
                
                <!-- Program Information -->
                <h3 style="color: var(--primary); margin-top: 40px; margin-bottom: 20px;">Program Information</h3>
                
                <div class="form-row">
                    <div class="form-group">
                        <label class="required">Program</label>
                        <select name="program" required>
                            <option value="">Select a program</option>
                            <option value="Basic Nursing" <?php echo isset($_POST['program']) && $_POST['program'] == 'Basic Nursing' ? 'selected' : ''; ?>>Basic Nursing</option>
                            <option value="Post Basic Nursing" <?php echo isset($_POST['program']) && $_POST['program'] == 'Post Basic Nursing' ? 'selected' : ''; ?>>Post Basic Nursing</option>
                            <option value="Midwifery" <?php echo isset($_POST['program']) && $_POST['program'] == 'Midwifery' ? 'selected' : ''; ?>>Midwifery</option>
                            <option value="Public Health Nursing" <?php echo isset($_POST['program']) && $_POST['program'] == 'Public Health Nursing' ? 'selected' : ''; ?>>Public Health Nursing</option>
                            <option value="Community Health" <?php echo isset($_POST['program']) && $_POST['program'] == 'Community Health' ? 'selected' : ''; ?>>Community Health</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label class="required">Entry Year</label>
                        <select name="entry_year" required>
                            <option value="">Select entry year</option>
                            <?php for ($year = date('Y'); $year <= date('Y') + 2; $year++): ?>
                            <option value="<?php echo $year; ?>" <?php echo isset($_POST['entry_year']) && $_POST['entry_year'] == $year ? 'selected' : ''; ?>>
                                <?php echo $year; ?>
                            </option>
                            <?php endfor; ?>
                        </select>
                    </div>
                </div>
                
                <!-- Academic Information -->
                <h3 style="color: var(--primary); margin-top: 40px; margin-bottom: 20px;">Academic Information</h3>
                
                <div class="form-group">
                    <label class="required">Highest Qualification</label>
                    <select name="highest_qualification" required>
                        <option value="">Select highest qualification</option>
                        <option value="WASSCE" <?php echo isset($_POST['highest_qualification']) && $_POST['highest_qualification'] == 'WASSCE' ? 'selected' : ''; ?>>WASSCE</option>
                        <option value="NECO" <?php echo isset($_POST['highest_qualification']) && $_POST['highest_qualification'] == 'NECO' ? 'selected' : ''; ?>>NECO</option>
                        <option value="GCE" <?php echo isset($_POST['highest_qualification']) && $_POST['highest_qualification'] == 'GCE' ? 'selected' : ''; ?>>GCE</option>
                        <option value="Diploma" <?php echo isset($_POST['highest_qualification']) && $_POST['highest_qualification'] == 'Diploma' ? 'selected' : ''; ?>>Diploma</option>
                        <option value="Bachelor's Degree" <?php echo isset($_POST['highest_qualification']) && $_POST['highest_qualification'] == 'Bachelor\'s Degree' ? 'selected' : ''; ?>>Bachelor's Degree</option>
                        <option value="Master's Degree" <?php echo isset($_POST['highest_qualification']) && $_POST['highest_qualification'] == 'Master\'s Degree' ? 'selected' : ''; ?>>Master's Degree</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label>Qualification Document</label>
                    <div class="file-upload" onclick="document.getElementById('qualification_file').click()">
                        <div class="file-upload-label">
                            📁 Click to upload qualification document
                        </div>
                        <div class="file-info">
                            Supported formats: PDF, DOC, DOCX, JPG, PNG (Max 5MB)
                        </div>
                        <input type="file" id="qualification_file" name="qualification_file" 
                               accept=".pdf,.doc,.docx,.jpg,.jpeg,.png" onchange="showFileName(this)">
                        <div id="fileName" style="margin-top: 10px; font-size: 0.875rem; color: var(--gray-700);"></div>
                    </div>
                </div>
                
                <!-- Personal Statement -->
                <div class="form-group">
                    <label class="required">Personal Statement</label>
                    <textarea name="personal_statement" required 
                              placeholder="Write your personal statement explaining why you want to join this program..."
                              rows="6"><?php echo htmlspecialchars($_POST['personal_statement'] ?? ''); ?></textarea>
                    <div class="form-help">Minimum 200 characters. Explain your motivation, goals, and why you're a good fit.</div>
                </div>
                
                <!-- Application Status (Admin only) -->
                <?php if ($userRole == 'admin'): ?>
                <h3 style="color: var(--primary); margin-top: 40px; margin-bottom: 20px;">Application Status</h3>
                
                <div class="form-row">
                    <div class="form-group">
                        <label>Status</label>
                        <select name="status">
                            <option value="pending" selected>Pending</option>
                            <option value="reviewed">Under Review</option>
                            <option value="accepted">Accepted</option>
                            <option value="rejected">Rejected</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label>Admin Notes</label>
                        <textarea name="notes" 
                                  placeholder="Internal notes about this application..."
                                  rows="3"><?php echo htmlspecialchars($_POST['notes'] ?? ''); ?></textarea>
                    </div>
                </div>
                <?php endif; ?>
                
                <!-- Form Actions -->
                <div class="form-actions">
                    <button type="button" class="btn btn-secondary" onclick="if(confirm('Discard changes?')) window.location.href='<?php echo BASE_URL; ?>/admin/applications'">
                        Cancel
                    </button>
                    <button type="submit" class="btn btn-primary">
                        💾 Save Application
                    </button>
                    <button type="button" class="btn btn-success" onclick="previewApplication()">
                        👁️ Preview
                    </button>
                </div>
            </form>
        </div>
        
        <!-- Quick Tips -->
        <div style="background: #f0f9ff; border: 1px solid #bee3f8; border-radius: 8px; padding: 20px; margin-top: 30px;">
            <h3 style="margin-top: 0; color: var(--primary);">💡 Quick Tips</h3>
            <ul style="margin: 0; padding-left: 20px; color: var(--gray-700);">
                <li>All fields marked with * are required</li>
                <li>Make sure email and phone are correct for communication</li>
                <li>Upload clear, readable qualification documents</li>
                <li>Personal statement should be detailed and genuine</li>
                <li>Click Preview to review before saving</li>
            </ul>
        </div>
    </div>
    
    <script>
        // Show selected file name
        function showFileName(input) {
            const fileNameDiv = document.getElementById('fileName');
            if (input.files && input.files[0]) {
                fileNameDiv.innerHTML = `<strong>Selected file:</strong> ${input.files[0].name}`;
            } else {
                fileNameDiv.innerHTML = '';
            }
        }
        
        // Preview application
        function previewApplication() {
            // Collect form data
            const formData = new FormData(document.getElementById('applicationForm'));
            const data = Object.fromEntries(formData.entries());
            
            // Show preview in alert (in real implementation, this would open a modal)
            alert('Application Preview:\n\n' +
                  `Name: ${data.first_name} ${data.last_name}\n` +
                  `Email: ${data.email}\n` +
                  `Phone: ${data.phone}\n` +
                  `Program: ${data.program}\n` +
                  `Entry Year: ${data.entry_year}\n` +
                  `Qualification: ${data.highest_qualification}\n` +
                  `\nPersonal Statement Preview:\n${data.personal_statement?.substring(0, 200)}...`);
        }
        
        // Form validation
        document.getElementById('applicationForm').addEventListener('submit', function(e) {
            const personalStatement = document.querySelector('textarea[name="personal_statement"]').value;
            if (personalStatement.length < 200) {
                e.preventDefault();
                alert('Personal statement must be at least 200 characters long.');
                return false;
            }
            
            // Validate phone number (basic validation)
            const phone = document.querySelector('input[name="phone"]').value;
            const phoneRegex = /^[\d\s\+\-\(\)]{10,20}$/;
            if (!phoneRegex.test(phone)) {
                e.preventDefault();
                alert('Please enter a valid phone number (10-20 digits, may include +, -, (, ), or spaces).');
                return false;
            }
            
            // Show saving indicator
            const submitBtn = document.querySelector('button[type="submit"]');
            submitBtn.innerHTML = '⏳ Saving...';
            submitBtn.disabled = true;
            
            return true;
        });
        
        // Auto-save draft every 30 seconds
        let autoSaveTimer;
        function autoSaveDraft() {
            const formData = new FormData(document.getElementById('applicationForm'));
            const data = Object.fromEntries(formData.entries());
            
            // In a real implementation, this would send to server
            console.log('Auto-saving draft...', data);
            
            // Show auto-save notification
            const notification = document.createElement('div');
            notification.style.cssText = `
                position: fixed;
                bottom: 20px;
                right: 20px;
                background: var(--success);
                color: white;
                padding: 10px 20px;
                border-radius: 6px;
                box-shadow: 0 2px 8px rgba(0,0,0,0.2);
                z-index: 1000;
                animation: fadeInOut 2s ease-in-out;
            `;
            notification.innerHTML = '💾 Draft auto-saved';
            document.body.appendChild(notification);
            
            setTimeout(() => notification.remove(), 2000);
        }
        
        // Start auto-save
        document.addEventListener('DOMContentLoaded', function() {
            // Start auto-save after 30 seconds of inactivity
            let timeout;
            document.getElementById('applicationForm').addEventListener('input', function() {
                clearTimeout(timeout);
                timeout = setTimeout(autoSaveDraft, 30000);
            });
        });
        
        // Character counter for personal statement
        const textarea = document.querySelector('textarea[name="personal_statement"]');
        if (textarea) {
            const counter = document.createElement('div');
            counter.style.cssText = 'font-size: 0.875rem; color: var(--gray-600); margin-top: 5px;';
            textarea.parentNode.insertBefore(counter, textarea.nextSibling);
            
            function updateCounter() {
                const length = textarea.value.length;
                counter.textContent = `${length} characters (minimum 200)`;
                counter.style.color = length < 200 ? 'var(--danger)' : 'var(--success)';
            }
            
            textarea.addEventListener('input', updateCounter);
            updateCounter();
        }
        
        // Add keyboard shortcuts
        document.addEventListener('keydown', function(e) {
            // Ctrl/Cmd + S to save
            if ((e.ctrlKey || e.metaKey) && e.key === 's') {
                e.preventDefault();
                document.querySelector('button[type="submit"]').click();
            }
            
            // Ctrl/Cmd + P to preview
            if ((e.ctrlKey || e.metaKey) && e.key === 'p') {
                e.preventDefault();
                previewApplication();
            }
            
            // Ctrl/Cmd + E to cancel
            if ((e.ctrlKey || e.metaKey) && e.key === 'e') {
                e.preventDefault();
                if (confirm('Discard changes?')) {
                    window.location.href = '<?php echo BASE_URL; ?>/admin/applications';
                }
            }
        });
        
        // Add fadeInOut animation
        const style = document.createElement('style');
        style.textContent = `
            @keyframes fadeInOut {
                0% { opacity: 0; transform: translateY(20px); }
                20% { opacity: 1; transform: translateY(0); }
                80% { opacity: 1; transform: translateY(0); }
                100% { opacity: 0; transform: translateY(-20px); }
            }
        `;
        document.head.appendChild(style);
    </script>
</body>
</html>