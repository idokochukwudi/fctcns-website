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
            max-width: 900px;
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
        
        .btn-success {
            background: var(--admin-success);
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
        .icon-reply { background: var(--admin-info); }
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
        
        /* Info Banner */
        .info-banner {
            background: linear-gradient(135deg, #ebf8ff 0%, #bee3f8 100%);
            border-left: 4px solid var(--admin-primary);
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 30px;
            display: flex;
            align-items: flex-start;
            gap: 15px;
        }
        
        .info-banner-icon {
            font-size: 1.5rem;
        }
        
        .info-banner-content {
            flex: 1;
        }
        
        .info-banner-title {
            font-weight: 700;
            color: var(--admin-primary-dark);
            margin-bottom: 5px;
        }
        
        .info-banner-text {
            color: var(--admin-gray-700);
            font-size: 0.9rem;
            margin: 0;
        }
        
        /* Department Tags */
        .department-tag {
            display: inline-block;
            background: var(--admin-gray-100);
            padding: 4px 12px;
            border-radius: 16px;
            font-size: 0.75rem;
            color: var(--admin-gray-700);
            margin-left: 10px;
            font-weight: normal;
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
        
        .preview-row {
            display: flex;
            margin-bottom: 10px;
            padding-bottom: 10px;
            border-bottom: 1px dashed var(--admin-gray-200);
        }
        
        .preview-row:last-child {
            border-bottom: none;
            margin-bottom: 0;
            padding-bottom: 0;
        }
        
        .preview-label {
            font-weight: 600;
            color: var(--admin-gray-700);
            width: 120px;
        }
        
        .preview-value {
            color: var(--admin-gray-800);
            flex: 1;
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
        
        .form-errors {
            background: #fff5f5;
            border-left: 4px solid var(--admin-danger);
            border-radius: 4px;
            padding: 15px 20px;
            margin-bottom: 20px;
        }
        
        /* Success Message */
        .success-message {
            background: #f0fff4;
            border-left: 4px solid var(--admin-success);
            border-radius: 4px;
            padding: 15px 20px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        /* Responsive */
        @media (max-width: 768px) {
            .page-header { flex-direction: column; align-items: stretch; }
            .settings-form { padding: 20px; }
            .form-row { flex-direction: column; }
            .preview-row { flex-direction: column; }
            .preview-label { width: 100%; margin-bottom: 5px; }
        }
    </style>
</head>
<body>
    <div class="contact-settings">
        <div class="page-header">
            <h1>⚙️ Contact & Reply Settings</h1>
            <div>
                <a href="<?php echo BASE_URL; ?>/admin/contact" class="btn btn-secondary">
                    ← Back to Contact
                </a>
            </div>
        </div>
        
        <!-- Info Banner - Explains Reply-To Feature -->
        <div class="info-banner">
            <div class="info-banner-icon">📧</div>
            <div class="info-banner-content">
                <div class="info-banner-title">Reply-to Email Configuration</div>
                <p class="info-banner-text">
                    When you click "Reply via Email" on a contact submission, the system automatically uses 
                    the department-specific email address below. This ensures replies come from the correct department.
                    <br><strong>Current default reply-to: <?php echo htmlspecialchars($settings['reply_to_email'] ?? 'noreply@fctcns.edu.ng'); ?></strong>
                </p>
            </div>
        </div>
        
        <!-- Settings Form -->
        <form method="POST" action="<?php echo BASE_URL; ?>/admin/contact/save-settings" 
              class="settings-form" id="settingsForm">
            <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">
            
            <!-- ============================================ -->
            <!-- NEW SECTION: REPLY-TO EMAIL CONFIGURATION    -->
            <!-- ============================================ -->
            <div class="settings-section">
                <div class="section-header">
                    <div class="section-icon icon-reply">📧</div>
                    <h2>Reply-To Email Configuration</h2>
                </div>
                
                <div class="form-grid" style="margin-bottom: 15px;">
                    <div class="form-group">
                        <label for="reply_to_email" class="required">
                            🔹 Default Reply-To Email
                            <span class="department-tag">General</span>
                        </label>
                        <input type="email" id="reply_to_email" name="reply_to_email" required
                               value="<?php echo htmlspecialchars($settings['reply_to_email'] ?? 'noreply@fctcns.edu.ng'); ?>"
                               placeholder="noreply@fctcns.edu.ng">
                        <div class="form-help">
                            Default "from" address for all replies. Used when no department-specific email is set.
                            <br><strong>This appears as the Reply-To header in emails.</strong>
                        </div>
                    </div>
                </div>
                
                <div class="form-grid">
                    <div class="form-group">
                        <label for="admissions_email">
                            🎓 Admissions Department
                            <span class="department-tag">Admissions</span>
                        </label>
                        <input type="email" id="admissions_email" name="admissions_email"
                               value="<?php echo htmlspecialchars($settings['admissions_email'] ?? 'admissions@fctcns.edu.ng'); ?>"
                               placeholder="admissions@fctcns.edu.ng">
                        <div class="form-help">Used when user selects "Admissions" department</div>
                    </div>
                    
                    <div class="form-group">
                        <label for="support_email">
                            🛠️ Support Department
                            <span class="department-tag">Support/Technical</span>
                        </label>
                        <input type="email" id="support_email" name="support_email"
                               value="<?php echo htmlspecialchars($settings['support_email'] ?? 'support@fctcns.edu.ng'); ?>"
                               placeholder="support@fctcns.edu.ng">
                        <div class="form-help">Used for technical support and IT inquiries</div>
                    </div>
                    
                    <div class="form-group">
                        <label for="billing_email">
                            💰 Billing Department
                            <span class="department-tag">Billing/Finance</span>
                        </label>
                        <input type="email" id="billing_email" name="billing_email"
                               value="<?php echo htmlspecialchars($settings['billing_email'] ?? 'billing@fctcns.edu.ng'); ?>"
                               placeholder="billing@fctcns.edu.ng">
                        <div class="form-help">Used for billing, fees, and payment inquiries</div>
                    </div>
                    
                    <div class="form-group">
                        <label for="academic_email">
                            📚 Academic Department
                            <span class="department-tag">Academic/Registrar</span>
                        </label>
                        <input type="email" id="academic_email" name="academic_email"
                               value="<?php echo htmlspecialchars($settings['academic_email'] ?? 'academic@fctcns.edu.ng'); ?>"
                               placeholder="academic@fctcns.edu.ng">
                        <div class="form-help">Used for academic and registrar inquiries</div>
                    </div>
                </div>
                
                <!-- How it works explanation -->
                <div style="background: var(--admin-gray-50); padding: 15px; border-radius: 6px; margin-top: 20px;">
                    <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 10px;">
                        <span style="background: var(--admin-primary); color: white; width: 24px; height: 24px; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 12px;">i</span>
                        <strong style="color: var(--admin-gray-800);">How Reply-To Routing Works:</strong>
                    </div>
                    <ul style="margin: 0; padding-left: 35px; color: var(--admin-gray-700); font-size: 0.875rem;">
                        <li style="margin-bottom: 5px;"><strong>When admin clicks "Reply via Email":</strong> The system detects the submission's department</li>
                        <li style="margin-bottom: 5px;"><strong>Automatically selects:</strong> The appropriate email from above based on department</li>
                        <li style="margin-bottom: 5px;"><strong>Falls back to:</strong> Default Reply-To Email if department-specific email is not set</li>
                        <li><strong>CC field:</strong> Includes the reply-to email for tracking purposes</li>
                    </ul>
                </div>
            </div>
            
            <!-- ============================================ -->
            <!-- EXISTING: Phone Numbers Section              -->
            <!-- ============================================ -->
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
            
            <!-- ============================================ -->
            <!-- EXISTING: Email Addresses Section            -->
            <!-- ============================================ -->
            <div class="settings-section">
                <div class="section-header">
                    <div class="section-icon icon-email">✉️</div>
                    <h2>Public Email Addresses</h2>
                </div>
                <div class="form-grid">
                    <div class="form-group">
                        <label for="email" class="required">General Email</label>
                        <input type="email" id="email" name="email" required
                               value="<?php echo htmlspecialchars($settings['email'] ?? ''); ?>"
                               placeholder="info@fctcns.edu.ng">
                        <div class="form-help">For general inquiries (public-facing)</div>
                    </div>
                </div>
            </div>
            
            <!-- ============================================ -->
            <!-- EXISTING: Location Section                   -->
            <!-- ============================================ -->
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
            
            <!-- ============================================ -->
            <!-- EXISTING: Working Hours Section              -->
            <!-- ============================================ -->
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
            
            <!-- ============================================ -->
            <!-- EXISTING: Map Coordinates Section            -->
            <!-- ============================================ -->
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
            
            <!-- ============================================ -->
            <!-- UPDATED: Preview Section with Reply Info     -->
            <!-- ============================================ -->
            <div class="preview-card">
                <h3>📱 Contact Information Preview</h3>
                <div class="preview-content">
                    <div class="preview-row">
                        <span class="preview-label">Main Phone:</span>
                        <span class="preview-value" id="preview-phone"><?php echo htmlspecialchars($settings['phone'] ?? 'Not set'); ?></span>
                    </div>
                    <div class="preview-row">
                        <span class="preview-label">Emergency:</span>
                        <span class="preview-value" id="preview-emergency"><?php echo htmlspecialchars($settings['emergency_contact'] ?? 'Not set'); ?></span>
                    </div>
                    <div class="preview-row">
                        <span class="preview-label">General Email:</span>
                        <span class="preview-value" id="preview-email"><?php echo htmlspecialchars($settings['email'] ?? 'Not set'); ?></span>
                    </div>
                    <div class="preview-row" style="border-bottom: 2px solid var(--admin-gray-200); padding-bottom: 15px; margin-bottom: 15px;">
                        <span class="preview-label">Address:</span>
                        <span class="preview-value" id="preview-address"><?php echo nl2br(htmlspecialchars($settings['address'] ?? 'Not set')); ?></span>
                    </div>
                    <div class="preview-row">
                        <span class="preview-label">Hours:</span>
                        <span class="preview-value" id="preview-hours"><?php echo nl2br(htmlspecialchars($settings['working_hours'] ?? 'Not set')); ?></span>
                    </div>
                </div>
                
                <h3 style="margin-top: 25px;">📧 Reply-To Email Preview</h3>
                <div class="preview-content">
                    <div class="preview-row">
                        <span class="preview-label">Default Reply-To:</span>
                        <span class="preview-value" id="preview-reply_to"><?php echo htmlspecialchars($settings['reply_to_email'] ?? 'noreply@fctcns.edu.ng'); ?></span>
                    </div>
                    <div class="preview-row">
                        <span class="preview-label">Admissions:</span>
                        <span class="preview-value" id="preview-admissions"><?php echo htmlspecialchars($settings['admissions_email'] ?? 'admissions@fctcns.edu.ng'); ?></span>
                    </div>
                    <div class="preview-row">
                        <span class="preview-label">Support:</span>
                        <span class="preview-value" id="preview-support"><?php echo htmlspecialchars($settings['support_email'] ?? 'support@fctcns.edu.ng'); ?></span>
                    </div>
                    <div class="preview-row">
                        <span class="preview-label">Billing:</span>
                        <span class="preview-value" id="preview-billing"><?php echo htmlspecialchars($settings['billing_email'] ?? 'billing@fctcns.edu.ng'); ?></span>
                    </div>
                    <div class="preview-row">
                        <span class="preview-label">Academic:</span>
                        <span class="preview-value" id="preview-academic"><?php echo htmlspecialchars($settings['academic_email'] ?? 'academic@fctcns.edu.ng'); ?></span>
                    </div>
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
        // ============================================
        // LIVE PREVIEW UPDATES - UPDATED WITH REPLY-TO FIELDS
        // ============================================
        const previewFields = {
            'phone': 'preview-phone',
            'emergency': 'preview-emergency',
            'email': 'preview-email',
            'address': 'preview-address',
            'hours': 'preview-hours',
            'reply_to_email': 'preview-reply_to',
            'admissions_email': 'preview-admissions',
            'support_email': 'preview-support',
            'billing_email': 'preview-billing',
            'academic_email': 'preview-academic'
        };
        
        Object.keys(previewFields).forEach(fieldId => {
            const input = document.getElementById(fieldId);
            const preview = document.getElementById(previewFields[fieldId]);
            
            if (input && preview) {
                input.addEventListener('input', function() {
                    let value = this.value.trim();
                    if (value === '') {
                        value = 'Not set';
                    }
                    preview.textContent = value;
                    
                    // Special formatting for address and hours
                    if (fieldId === 'address' || fieldId === 'hours') {
                        preview.innerHTML = (this.value || 'Not set').replace(/\n/g, '<br>');
                    }
                });
            }
        });
        
        // ============================================
        // FORM VALIDATION - UPDATED WITH REPLY-TO FIELDS
        // ============================================
        document.getElementById('settingsForm').addEventListener('submit', function(e) {
            // Clear previous errors
            document.querySelectorAll('.form-group.error').forEach(el => {
                el.classList.remove('error');
            });
            
            let errors = [];
            
            // Validate required fields
            const required = ['phone', 'email', 'address', 'hours', 'reply_to_email'];
            required.forEach(field => {
                const element = document.getElementById(field);
                if (element && !element.value.trim()) {
                    errors.push(`${getFieldLabel(field)} is required`);
                    element.classList.add('error');
                }
            });
            
            // Validate all email fields
            const emailFields = ['email', 'reply_to_email', 'admissions_email', 'support_email', 'billing_email', 'academic_email'];
            emailFields.forEach(field => {
                const element = document.getElementById(field);
                if (element && element.value.trim() && !isValidEmail(element.value.trim())) {
                    errors.push(`${getFieldLabel(field)} must be a valid email address`);
                    element.classList.add('error');
                }
            });
            
            // Validate phone format
            const phone = document.getElementById('phone');
            if (phone && phone.value.trim() && !isValidPhone(phone.value.trim())) {
                errors.push('Phone number should contain only numbers and + sign');
                phone.classList.add('error');
            }
            
            // Validate coordinates if provided
            const lat = document.getElementById('map_latitude');
            const lng = document.getElementById('map_longitude');
            
            if (lat && lat.value.trim() && !isValidCoordinate(lat.value.trim())) {
                errors.push('Latitude must be a valid decimal number');
                lat.classList.add('error');
            }
            
            if (lng && lng.value.trim() && !isValidCoordinate(lng.value.trim())) {
                errors.push('Longitude must be a valid decimal number');
                lng.classList.add('error');
            }
            
            if (errors.length > 0) {
                e.preventDefault();
                showErrors(errors);
            }
        });
        
        // Helper: Get field label
        function getFieldLabel(fieldId) {
            const labels = {
                'phone': 'Main Phone Number',
                'email': 'General Email',
                'address': 'Full Address',
                'hours': 'Working Hours',
                'reply_to_email': 'Default Reply-To Email',
                'admissions_email': 'Admissions Email',
                'support_email': 'Support Email',
                'billing_email': 'Billing Email',
                'academic_email': 'Academic Email',
                'emergency': 'Emergency Contact'
            };
            return labels[fieldId] || fieldId;
        }
        
        // Helper: Email validation
        function isValidEmail(email) {
            const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            return re.test(email);
        }
        
        // Helper: Phone validation
        function isValidPhone(phone) {
            const re = /^[\+\d\s\-\(\)]+$/;
            return re.test(phone);
        }
        
        // Helper: Coordinate validation
        function isValidCoordinate(coord) {
            const re = /^-?\d+(\.\d+)?$/;
            return re.test(coord);
        }
        
        // Helper: Show errors
        function showErrors(errors) {
            // Remove existing error messages
            const existingError = document.querySelector('.form-errors');
            if (existingError) existingError.remove();
            
            // Create error container
            const errorDiv = document.createElement('div');
            errorDiv.className = 'form-errors';
            errorDiv.innerHTML = `
                <strong style="color: var(--admin-danger); display: block; margin-bottom: 10px;">
                    ⚠️ Please fix the following errors:
                </strong>
                <ul style="margin: 0; padding-left: 20px; color: var(--admin-gray-700);">
                    ${errors.map(error => `<li style="margin-bottom: 5px;">${error}</li>`).join('')}
                </ul>
            `;
            
            // Insert at top of form
            const form = document.getElementById('settingsForm');
            form.insertBefore(errorDiv, form.firstChild);
            
            // Scroll to errors
            errorDiv.scrollIntoView({ behavior: 'smooth', block: 'center' });
        }
        
        // ============================================
        // RESET FORM FUNCTION
        // ============================================
        function resetForm() {
            if (confirm('Are you sure you want to reset all changes? Unsaved data will be lost.')) {
                // Reset form to initial values
                document.getElementById('settingsForm').reset();
                
                // Reset previews
                Object.keys(previewFields).forEach(fieldId => {
                    const input = document.getElementById(fieldId);
                    const preview = document.getElementById(previewFields[fieldId]);
                    if (input && preview) {
                        const defaultValue = input.defaultValue || 'Not set';
                        preview.textContent = defaultValue;
                        if (fieldId === 'address' || fieldId === 'hours') {
                            preview.innerHTML = (input.defaultValue || 'Not set').replace(/\n/g, '<br>');
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
                
                // Show success message
                showNotification('Form has been reset', 'info');
            }
        }
        
        // ============================================
        // PHONE NUMBER FORMATTING
        // ============================================
        document.getElementById('phone')?.addEventListener('input', function(e) {
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
        
        // ============================================
        // KEYBOARD SHORTCUTS
        // ============================================
        document.addEventListener('keydown', function(e) {
            // Ctrl/Cmd + S to save
            if ((e.ctrlKey || e.metaKey) && e.key === 's') {
                e.preventDefault();
                document.getElementById('settingsForm').submit();
            }
            
            // Esc to go back
            if (e.key === 'Escape') {
                if (confirm('Leave without saving?')) {
                    window.location.href = '<?php echo BASE_URL; ?>/admin/contact';
                }
            }
        });
        
        // ============================================
        // NOTIFICATION SYSTEM
        // ============================================
        function showNotification(message, type = 'success') {
            const notification = document.createElement('div');
            notification.style.cssText = `
                position: fixed;
                top: 20px;
                right: 20px;
                padding: 15px 25px;
                background: ${type === 'success' ? '#48bb78' : '#4299e1'};
                color: white;
                border-radius: 6px;
                box-shadow: 0 4px 12px rgba(0,0,0,0.15);
                z-index: 9999;
                animation: slideIn 0.3s ease;
            `;
            notification.textContent = message;
            document.body.appendChild(notification);
            
            setTimeout(() => {
                notification.style.animation = 'slideOut 0.3s ease';
                setTimeout(() => notification.remove(), 300);
            }, 3000);
        }
        
        // Add animations
        const style = document.createElement('style');
        style.textContent = `
            @keyframes slideIn {
                from { transform: translateX(100%); opacity: 0; }
                to { transform: translateX(0); opacity: 1; }
            }
            @keyframes slideOut {
                from { transform: translateX(0); opacity: 1; }
                to { transform: translateX(100%); opacity: 0; }
            }
        `;
        document.head.appendChild(style);
        
        // ============================================
        // AUTO-SAVE DRAFT
        // ============================================
        let saveTimeout;
        const formInputs = document.querySelectorAll('#settingsForm input, #settingsForm textarea');
        
        formInputs.forEach(input => {
            input.addEventListener('input', function() {
                clearTimeout(saveTimeout);
                saveTimeout = setTimeout(saveDraft, 30000);
            });
        });
        
        function saveDraft() {
            const formData = {};
            formInputs.forEach(input => {
                formData[input.id] = input.value;
            });
            localStorage.setItem('contact_settings_draft', JSON.stringify(formData));
            showNotification('Draft saved', 'info');
        }
        
        // Load draft on page load
        window.addEventListener('load', function() {
            const draft = localStorage.getItem('contact_settings_draft');
            if (draft) {
                if (confirm('You have unsaved changes from a previous session. Load them?')) {
                    const data = JSON.parse(draft);
                    Object.keys(data).forEach(key => {
                        const input = document.getElementById(key);
                        if (input) {
                            input.value = data[key];
                            input.dispatchEvent(new Event('input'));
                        }
                    });
                    showNotification('Draft loaded', 'info');
                }
            }
        });
    </script>
</body>
</html>