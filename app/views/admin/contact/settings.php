<?php
// Get the absolute path to the root
$rootPath = dirname(__DIR__, 4);
require_once $rootPath . '/app/config/constants.php';
require_once APP_PATH . '/config/session.php';
require_once APP_PATH . '/middleware/AuthMiddleware.php';
AuthMiddleware::authenticate();

// Only admin can access settings
if ($_SESSION['user_role'] !== 'admin') {
    header('Location: ' . BASE_URL . '/admin/contact');
    exit;
}

$csrf_token = bin2hex(random_bytes(32));
$_SESSION['csrf_token'] = $csrf_token;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Settings - FCT CNS Admin</title>
    <style>
        :root {
            --admin-primary: #2c5282;
            --admin-primary-dark: #1a365d;
            --admin-success: #38a169;
            --admin-warning: #d69e2e;
            --admin-danger: #e53e3e;
            --admin-info: #3182ce;
            --admin-gray-50: #f7fafc;
            --admin-gray-100: #edf2f7;
            --admin-gray-200: #e2e8f0;
            --admin-gray-600: #718096;
            --admin-gray-700: #4a5568;
            --admin-gray-800: #2d3748;
        }
        
        .contact-settings {
            padding: 20px;
            max-width: 800px;
            margin: 0 auto;
        }
        
        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            flex-wrap: wrap;
            gap: 15px;
        }
        
        .page-header h1 {
            color: var(--admin-gray-800);
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
            background: var(--admin-primary);
            color: white;
        }
        
        .btn-secondary {
            background: var(--admin-gray-600);
            color: white;
        }
        
        .btn:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }
        
        /* Settings Form */
        .settings-form {
            background: white;
            border-radius: 8px;
            padding: 30px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }
        
        .settings-section {
            margin-bottom: 30px;
            padding-bottom: 30px;
            border-bottom: 1px solid var(--admin-gray-200);
        }
        
        .settings-section:last-child {
            border-bottom: none;
            margin-bottom: 0;
            padding-bottom: 0;
        }
        
        .section-header {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 20px;
        }
        
        .section-header h2 {
            margin: 0;
            color: var(--admin-gray-800);
            font-size: 1.25rem;
        }
        
        .section-icon {
            width: 40px;
            height: 40px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.25rem;
        }
        
        .icon-phone { background: var(--admin-success); }
        .icon-email { background: var(--admin-primary); }
        .icon-location { background: var(--admin-warning); }
        .icon-hours { background: var(--admin-info); }
        .icon-map { background: var(--admin-danger); }
        
        .form-grid {
            display: grid;
            grid-template-columns: 1fr;
            gap: 20px;
        }
        
        @media (min-width: 768px) {
            .form-grid {
                grid-template-columns: repeat(2, 1fr);
            }
            
            .form-grid.full-width {
                grid-template-columns: 1fr;
            }
        }
        
        .form-group {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }
        
        .form-group label {
            font-weight: 600;
            color: var(--admin-gray-700);
            font-size: 0.875rem;
            display: flex;
            align-items: center;
            gap: 5px;
        }
        
        .form-group label.required::after {
            content: '*';
            color: var(--admin-danger);
        }
        
        .form-group input,
        .form-group textarea {
            padding: 12px;
            border: 1px solid var(--admin-gray-200);
            border-radius: 6px;
            font-size: 14px;
            width: 100%;
            box-sizing: border-box;
            font-family: inherit;
        }
        
        .form-group textarea {
            min-height: 100px;
            resize: vertical;
        }
        
        .form-group input:focus,
        .form-group textarea:focus {
            outline: none;
            border-color: var(--admin-primary);
            box-shadow: 0 0 0 3px rgba(44, 82, 130, 0.1);
        }
        
        .form-help {
            font-size: 0.75rem;
            color: var(--admin-gray-600);
            margin-top: 5px;
        }
        
        .form-row {
            display: flex;
            gap: 15px;
            align-items: flex-end;
        }
        
        .form-row .form-group {
            flex: 1;
        }
        
        /* Preview Card */
        .preview-card {
            background: var(--admin-gray-50);
            border: 1px solid var(--admin-gray-200);
            border-radius: 8px;
            padding: 20px;
            margin-top: 30px;
        }
        
        .preview-card h3 {
            margin-top: 0;
            margin-bottom: 15px;
            color: var(--admin-gray-800);
        }
        
        .preview-content {
            background: white;
            border-radius: 6px;
            padding: 15px;
            border: 1px solid var(--admin-gray-200);
            font-size: 0.9rem;
        }
        
        /* Form Actions */
        .form-actions {
            display: flex;
            justify-content: flex-end;
            gap: 15px;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid var(--admin-gray-200);
        }
        
        /* Validation Styles */
        .form-group.error input,
        .form-group.error textarea {
            border-color: var(--admin-danger);
        }
        
        .error-message {
            font-size: 0.75rem;
            color: var(--admin-danger);
            margin-top: 5px;
        }
        
        /* Responsive */
        @media (max-width: 768px) {
            .page-header { flex-direction: column; align-items: stretch; }
            .settings-form { padding: 20px; }
            .form-row { flex-direction: column; }
        }
    </style>
</head>
<body>
    <div class="contact-settings">
        <div class="page-header">
            <h1>⚙️ Contact Settings</h1>
            <div>
                <a href="<?php echo BASE_URL; ?>/admin/contact" class="btn btn-secondary">
                    ← Back to Contact
                </a>
            </div>
        </div>
        
        <!-- Settings Form -->
        <form method="POST" action="<?php echo BASE_URL; ?>/admin/contact/save-settings" 
              class="settings-form" id="settingsForm">
            <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">
            
            <!-- Contact Information Section -->
            <div class="settings-section">
                <div class="section-header">
                    <div class="section-icon icon-phone">📞</div>
                    <h2>Phone Numbers</h2>
                </div>
                <div class="form-grid">
                    <div class="form-group">
                        <label for="phone" class="required">Main Phone Number</label>
                        <input type="tel" id="phone" name="phone" required
                               value="<?php echo htmlspecialchars($settings['phone'] ?? ''); ?>"
                               placeholder="+234 800 123 4567">
                        <div class="form-help">Format: +234 XXX XXX XXXX</div>
                    </div>
                    
                    <div class="form-group">
                        <label for="emergency">Emergency Contact</label>
                        <input type="tel" id="emergency" name="emergency"
                               value="<?php echo htmlspecialchars($settings['emergency_contact'] ?? ''); ?>"
                               placeholder="+234 800 987 6543">
                        <div class="form-help">24/7 emergency contact number</div>
                    </div>
                </div>
            </div>
            
            <!-- Email Addresses Section -->
            <div class="settings-section">
                <div class="section-header">
                    <div class="section-icon icon-email">✉️</div>
                    <h2>Email Addresses</h2>
                </div>
                <div class="form-grid">
                    <div class="form-group">
                        <label for="email" class="required">General Email</label>
                        <input type="email" id="email" name="email" required
                               value="<?php echo htmlspecialchars($settings['email'] ?? ''); ?>"
                               placeholder="info@fctcns.edu.ng">
                        <div class="form-help">For general inquiries</div>
                    </div>
                    
                    <div class="form-group">
                        <label for="admissions_email">Admissions Email</label>
                        <input type="email" id="admissions_email" name="admissions_email"
                               value="<?php echo htmlspecialchars($settings['admissions_email'] ?? ''); ?>"
                               placeholder="admissions@fctcns.edu.ng">
                        <div class="form-help">For admission-related inquiries</div>
                    </div>
                </div>
            </div>
            
            <!-- Location Section -->
            <div class="settings-section">
                <div class="section-header">
                    <div class="section-icon icon-location">📍</div>
                    <h2>Location Information</h2>
                </div>
                <div class="form-grid full-width">
                    <div class="form-group">
                        <label for="address" class="required">Full Address</label>
                        <textarea id="address" name="address" required rows="3"
                                  placeholder="FCT College of Nursing Sciences, Federal Capital Territory, Abuja, Nigeria"><?php 
                            echo htmlspecialchars($settings['address'] ?? ''); 
                        ?></textarea>
                        <div class="form-help">Full physical address for visitors</div>
                    </div>
                </div>
            </div>
            
            <!-- Working Hours Section -->
            <div class="settings-section">
                <div class="section-header">
                    <div class="section-icon icon-hours">🕐</div>
                    <h2>Working Hours</h2>
                </div>
                <div class="form-grid full-width">
                    <div class="form-group">
                        <label for="hours" class="required">Working Hours</label>
                        <textarea id="hours" name="hours" required rows="3"
                                  placeholder="Monday - Friday: 8:00 AM - 5:00 PM&#10;Saturday: 9:00 AM - 1:00 PM&#10;Sunday: Closed"><?php 
                            echo htmlspecialchars($settings['working_hours'] ?? ''); 
                        ?></textarea>
                        <div class="form-help">One line per schedule entry</div>
                    </div>
                </div>
            </div>
            
            <!-- Map Coordinates Section -->
            <div class="settings-section">
                <div class="section-header">
                    <div class="section-icon icon-map">🗺️</div>
                    <h2>Map Coordinates</h2>
                </div>
                <div class="form-grid">
                    <div class="form-group">
                        <label for="map_latitude">Latitude</label>
                        <input type="text" id="map_latitude" name="map_latitude"
                               value="<?php echo htmlspecialchars($settings['map_latitude'] ?? ''); ?>"
                               placeholder="9.0765">
                        <div class="form-help">For Google Maps integration</div>
                    </div>
                    
                    <div class="form-group">
                        <label for="map_longitude">Longitude</label>
                        <input type="text" id="map_longitude" name="map_longitude"
                               value="<?php echo htmlspecialchars($settings['map_longitude'] ?? ''); ?>"
                               placeholder="7.3986">
                        <div class="form-help">For Google Maps integration</div>
                    </div>
                </div>
            </div>
            
            <!-- Preview Section -->
            <div class="preview-card">
                <h3>📱 Contact Information Preview</h3>
                <div class="preview-content">
                    <p><strong>Phone:</strong> <span id="preview-phone"><?php echo htmlspecialchars($settings['phone'] ?? ''); ?></span></p>
                    <p><strong>Email:</strong> <span id="preview-email"><?php echo htmlspecialchars($settings['email'] ?? ''); ?></span></p>
                    <p><strong>Address:</strong> <span id="preview-address"><?php echo nl2br(htmlspecialchars($settings['address'] ?? '')); ?></span></p>
                    <p><strong>Hours:</strong> <span id="preview-hours"><?php echo nl2br(htmlspecialchars($settings['working_hours'] ?? '')); ?></span></p>
                </div>
            </div>
            
            <!-- Form Actions -->
            <div class="form-actions">
                <button type="button" class="btn btn-secondary" onclick="resetForm()">
                    Reset Changes
                </button>
                <button type="submit" class="btn btn-primary">
                    💾 Save Settings
                </button>
            </div>
        </form>
    </div>
    
    <script>
        // Live preview updates
        const previewFields = {
            'phone': 'preview-phone',
            'email': 'preview-email',
            'address': 'preview-address',
            'hours': 'preview-hours',
            'emergency': 'preview-emergency',
            'admissions_email': 'preview-admissions-email'
        };
        
        Object.keys(previewFields).forEach(fieldId => {
            const input = document.getElementById(fieldId);
            const preview = document.getElementById(previewFields[fieldId]);
            
            if (input && preview) {
                input.addEventListener('input', function() {
                    preview.textContent = this.value;
                    
                    // Special formatting for address and hours
                    if (fieldId === 'address' || fieldId === 'hours') {
                        preview.innerHTML = this.value.replace(/\n/g, '<br>');
                    }
                });
            }
        });
        
        // Form validation
        document.getElementById('settingsForm').addEventListener('submit', function(e) {
            const email = document.getElementById('email').value.trim();
            const admissionsEmail = document.getElementById('admissions_email').value.trim();
            const phone = document.getElementById('phone').value.trim();
            
            let errors = [];
            
            // Validate required fields
            const required = ['phone', 'email', 'address', 'hours'];
            required.forEach(field => {
                const element = document.getElementById(field);
                if (!element.value.trim()) {
                    errors.push(`${element.previousElementSibling.textContent} is required`);
                    element.classList.add('error');
                } else {
                    element.classList.remove('error');
                }
            });
            
            // Validate email format
            if (email && !isValidEmail(email)) {
                errors.push('General email must be a valid email address');
                document.getElementById('email').classList.add('error');
            }
            
            if (admissionsEmail && !isValidEmail(admissionsEmail)) {
                errors.push('Admissions email must be a valid email address');
                document.getElementById('admissions_email').classList.add('error');
            }
            
            // Validate phone format (basic)
            if (phone && !isValidPhone(phone)) {
                errors.push('Phone number should contain only numbers and + sign');
                document.getElementById('phone').classList.add('error');
            }
            
            // Validate coordinates if provided
            const lat = document.getElementById('map_latitude').value.trim();
            const lng = document.getElementById('map_longitude').value.trim();
            
            if ((lat || lng) && (!isValidCoordinate(lat) || !isValidCoordinate(lng))) {
                errors.push('Map coordinates must be valid decimal numbers');
                if (!isValidCoordinate(lat)) document.getElementById('map_latitude').classList.add('error');
                if (!isValidCoordinate(lng)) document.getElementById('map_longitude').classList.add('error');
            }
            
            if (errors.length > 0) {
                e.preventDefault();
                
                // Show error messages
                let errorHtml = '<div style="color: var(--admin-danger); margin-bottom: 15px;">';
                errorHtml += '<strong>Please fix the following errors:</strong><ul style="margin: 10px 0; padding-left: 20px;">';
                errors.forEach(error => {
                    errorHtml += `<li>${error}</li>`;
                });
                errorHtml += '</ul></div>';
                
                // Remove any existing error messages
                const existingError = document.querySelector('.form-errors');
                if (existingError) existingError.remove();
                
                // Add new error messages
                const errorDiv = document.createElement('div');
                errorDiv.className = 'form-errors';
                errorDiv.innerHTML = errorHtml;
                
                const firstSection = document.querySelector('.settings-section');
                firstSection.parentNode.insertBefore(errorDiv, firstSection);
                
                // Scroll to first error
                document.querySelector('.form-group.error input, .form-group.error textarea')?.focus();
            }
        });
        
        // Helper functions
        function isValidEmail(email) {
            const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            return re.test(email);
        }
        
        function isValidPhone(phone) {
            const re = /^[\+\d\s\-\(\)]+$/;
            return re.test(phone);
        }
        
        function isValidCoordinate(coord) {
            if (!coord) return true;
            const re = /^-?\d+(\.\d+)?$/;
            return re.test(coord);
        }
        
        // Reset form to original values
        function resetForm() {
            if (confirm('Are you sure you want to reset all changes?')) {
                document.getElementById('settingsForm').reset();
                
                // Reset previews
                Object.keys(previewFields).forEach(fieldId => {
                    const input = document.getElementById(fieldId);
                    const preview = document.getElementById(previewFields[fieldId]);
                    if (input && preview) {
                        preview.textContent = input.defaultValue;
                        if (fieldId === 'address' || fieldId === 'hours') {
                            preview.innerHTML = input.defaultValue.replace(/\n/g, '<br>');
                        }
                    }
                });
                
                // Remove error classes
                document.querySelectorAll('.form-group.error').forEach(el => {
                    el.classList.remove('error');
                });
                
                // Remove error messages
                const existingError = document.querySelector('.form-errors');
                if (existingError) existingError.remove();
            }
        }
        
        // Auto-format phone number
        document.getElementById('phone').addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');
            if (value.length > 0) {
                if (value.startsWith('234')) {
                    value = '+' + value.substring(0, 3) + ' ' + value.substring(3, 6) + ' ' + value.substring(6, 9) + ' ' + value.substring(9, 13);
                } else {
                    if (value.length <= 3) {
                        value = value;
                    } else if (value.length <= 6) {
                        value = value.substring(0, 3) + ' ' + value.substring(3);
                    } else {
                        value = value.substring(0, 3) + ' ' + value.substring(3, 6) + ' ' + value.substring(6, 10);
                    }
                }
            }
            e.target.value = value;
        });
        
        // Keyboard shortcuts
        document.addEventListener('keydown', function(e) {
            // Ctrl/Cmd + S to save
            if ((e.ctrlKey || e.metaKey) && e.key === 's') {
                e.preventDefault();
                document.getElementById('settingsForm').submit();
            }
            
            // Esc to go back
            if (e.key === 'Escape') {
                window.location.href = '<?php echo BASE_URL; ?>/admin/contact';
            }
        });
        
        // Auto-save draft (every 30 seconds)
        let saveTimeout;
        const formInputs = document.querySelectorAll('#settingsForm input, #settingsForm textarea');
        
        formInputs.forEach(input => {
            input.addEventListener('input', function() {
                clearTimeout(saveTimeout);
                saveTimeout = setTimeout(saveDraft, 30000);
            });
        });
        
        function saveDraft() {
            // In a real implementation, this would save to localStorage or send via AJAX
            console.log('Auto-saving draft...');
        }
        
        // Load draft on page load
        window.addEventListener('load', function() {
            const draft = localStorage.getItem('contact_settings_draft');
            if (draft) {
                if (confirm('You have unsaved changes. Load them?')) {
                    const data = JSON.parse(draft);
                    Object.keys(data).forEach(key => {
                        const input = document.getElementById(key);
                        if (input) {
                            input.value = data[key];
                            input.dispatchEvent(new Event('input'));
                        }
                    });
                }
            }
        });
    </script>
</body>
</html>