<?php
/**
 * Reset Password View
 * 
 * @package FCTCNS
 */

// =========================================================
// FIX: Add require for SecurityHelper and SecurityTrait
// =========================================================
require_once APP_PATH . '/helpers/SecurityHelper.php';
require_once APP_PATH . '/helpers/SecurityTrait.php';

class ResetPasswordView {
    use SecurityTrait;
    
    public function render($data) {
        extract($data);
        
        // Get security tokens
        $csp_nonce = $this->getCspNonce();
        $csrf_token = $this->getCsrfToken();

        $token = $token ?? '';
        $email = $email ?? '';
        ?>
        <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            
            <!-- ========================================================= -->
            <!-- 2. Add security meta tags in the head -->
            <!-- ========================================================= -->
            <?php echo $this->getSecurityMetaTags(); ?>
            
            <title>Reset Password - FCT College of Nursing Sciences</title>
            
            <!-- CSRF Token for JavaScript -->
            <meta name="csrf-token" content="<?php echo $this->e($csrf_token); ?>">
            
            <!-- ========================================================= -->
            <!-- 3. Add CSP nonce to all style tags -->
            <!-- 7. Add SRI hashes to external scripts/styles -->
            <!-- ========================================================= -->
            
            <!-- Bootstrap 5 with SRI -->
            <?php 
            $bootstrapCssUrl = 'https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css';
            $bootstrapCssSri = SecurityHelper::getSriHash($bootstrapCssUrl);
            ?>
            <link href="<?php echo $bootstrapCssUrl; ?>" 
                  rel="stylesheet"
                  <?php if ($bootstrapCssSri): ?>integrity="<?php echo $bootstrapCssSri; ?>"<?php endif; ?>
                  crossorigin="anonymous">
            
            <!-- Font Awesome with CORRECT SRI hash -->
            <?php 
            $faUrl = 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css';
            $faSri = SecurityHelper::getSriHash($faUrl);
            ?>
            <link rel="stylesheet" 
                  href="<?php echo $faUrl; ?>"
                  <?php if ($faSri): ?>integrity="<?php echo $faSri; ?>"<?php endif; ?>
                  crossorigin="anonymous" 
                  referrerpolicy="no-referrer">

            <style nonce="<?php echo $csp_nonce; ?>">
                body {
                    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                    min-height: 100vh;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
                    margin: 0;
                    padding: 20px;
                }
                
                .reset-container {
                    max-width: 450px;
                    width: 100%;
                    margin: 0 auto;
                }
                
                .reset-card {
                    background: white;
                    border-radius: 15px;
                    box-shadow: 0 20px 40px rgba(0,0,0,0.2);
                    overflow: hidden;
                    animation: slideUp 0.5s ease;
                }
                
                @keyframes slideUp {
                    from {
                        opacity: 0;
                        transform: translateY(20px);
                    }
                    to {
                        opacity: 1;
                        transform: translateY(0);
                    }
                }
                
                .card-header {
                    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                    color: white;
                    padding: 30px 20px;
                    text-align: center;
                }
                
                .card-header h2 {
                    margin: 0;
                    font-size: 24px;
                    font-weight: 600;
                }
                
                .card-header p {
                    margin: 10px 0 0;
                    opacity: 0.9;
                    font-size: 14px;
                }
                
                .card-body {
                    padding: 40px 30px;
                }
                
                .form-group {
                    margin-bottom: 20px;
                }
                
                .form-label {
                    font-weight: 500;
                    color: #333;
                    margin-bottom: 8px;
                    display: block;
                }
                
                .form-control {
                    border: 2px solid #e0e0e0;
                    border-radius: 8px;
                    padding: 12px 15px;
                    font-size: 14px;
                    width: 100%;
                    transition: all 0.3s ease;
                }
                
                .form-control:focus {
                    border-color: #667eea;
                    box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
                    outline: none;
                }
                
                .form-control.is-invalid {
                    border-color: #dc3545;
                    background-color: #fff8f8;
                }
                
                .input-group {
                    position: relative;
                    display: flex;
                    align-items: center;
                }
                
                .input-group .form-control {
                    border-top-right-radius: 0;
                    border-bottom-right-radius: 0;
                }
                
                .input-group .btn {
                    border-top-left-radius: 0;
                    border-bottom-left-radius: 0;
                    height: 100%;
                    padding: 12px 15px;
                    border: 2px solid #e0e0e0;
                    border-left: none;
                    background: white;
                    color: #667eea;
                    cursor: pointer;
                    transition: all 0.2s ease;
                }
                
                .input-group .btn:hover {
                    background: #f8f9fa;
                    color: #764ba2;
                }
                
                .btn-reset {
                    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                    color: white;
                    border: none;
                    border-radius: 8px;
                    padding: 12px;
                    font-size: 16px;
                    font-weight: 600;
                    width: 100%;
                    cursor: pointer;
                    transition: all 0.3s ease;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    gap: 8px;
                }
                
                .btn-reset:hover:not(:disabled) {
                    transform: translateY(-2px);
                    box-shadow: 0 10px 20px rgba(102, 126, 234, 0.3);
                }
                
                .btn-reset:disabled {
                    opacity: 0.7;
                    cursor: not-allowed;
                }
                
                .alert {
                    border-radius: 8px;
                    padding: 15px 20px;
                    margin-bottom: 20px;
                    display: flex;
                    align-items: center;
                    justify-content: space-between;
                }
                
                .alert-success {
                    background-color: #d4edda;
                    border: 1px solid #c3e6cb;
                    color: #155724;
                }
                
                .alert-danger {
                    background-color: #f8d7da;
                    border: 1px solid #f5c6cb;
                    color: #721c24;
                }
                
                .alert .btn-close {
                    background: none;
                    border: none;
                    font-size: 20px;
                    cursor: pointer;
                    color: currentColor;
                    opacity: 0.5;
                }
                
                .alert .btn-close:hover {
                    opacity: 1;
                }
                
                .footer {
                    text-align: center;
                    margin-top: 20px;
                    color: rgba(255, 255, 255, 1);
                    font-size: 13px;
                    line-height: 1.6;
                    text-shadow: 0 1px 2px rgba(0,0,0,0.3);
                    background-color: rgba(0,0,0,0.2);
                    padding: 15px;
                    border-radius: 8px;
                    backdrop-filter: blur(5px);
                }
                
                .footer a {
                    color: white;
                    text-decoration: none;
                    font-weight: 500;
                    border-bottom: 1px dotted rgba(255,255,255,0.5);
                }
                
                .footer a:hover {
                    border-bottom-color: white;
                }
                
                .password-requirements {
                    font-size: 12px;
                    color: #666;
                    margin-top: 5px;
                }
                
                .password-requirements i {
                    color: #667eea;
                    margin-right: 5px;
                }
                
                .invalid-feedback {
                    display: none;
                    color: #dc3545;
                    font-size: 12px;
                    margin-top: 5px;
                }
                
                .form-control.is-invalid + .invalid-feedback,
                .is-invalid ~ .invalid-feedback {
                    display: block;
                }
                
                /* Toast notification */
                .toast-notification {
                    position: fixed;
                    top: 20px;
                    right: 20px;
                    padding: 12px 20px;
                    border-radius: 8px;
                    color: white;
                    font-size: 14px;
                    font-weight: 500;
                    display: flex;
                    align-items: center;
                    gap: 10px;
                    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
                    z-index: 9999;
                    animation: slideInRight 0.3s ease;
                }
                
                @keyframes slideInRight {
                    from {
                        opacity: 0;
                        transform: translateX(100%);
                    }
                    to {
                        opacity: 1;
                        transform: translateX(0);
                    }
                }
                
                .toast-success { background: #28a745; }
                .toast-error { background: #dc3545; }
                .toast-info { background: #17a2b8; }
                
                /* Password strength meter */
                .password-strength {
                    height: 4px;
                    margin-top: 8px;
                    border-radius: 2px;
                    background: #e0e0e0;
                    overflow: hidden;
                }
                
                .strength-bar {
                    height: 100%;
                    width: 0;
                    transition: width 0.3s ease;
                }
                
                .strength-bar.weak { width: 33%; background: #dc3545; }
                .strength-bar.medium { width: 66%; background: #ffc107; }
                .strength-bar.strong { width: 100%; background: #28a745; }
                
                /* Responsive */
                @media (max-width: 480px) {
                    .card-body { padding: 30px 20px; }
                    .card-header { padding: 25px 15px; }
                }
            </style>
        </head>
        <body>
            <div class="reset-container">
                <div class="reset-card">
                    <div class="card-header">
                        <i class="fas fa-lock fa-2x mb-2"></i>
                        <h2>Reset Password</h2>
                        <p>Create a new password for <?php echo $this->e($email); ?></p>
                    </div>
                    
                    <div class="card-body">
                        <!-- Flash Messages -->
                        <?php if (isset($_SESSION['flash_error']) && !empty($_SESSION['flash_error'])): ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <div>
                                <i class="fas fa-exclamation-circle me-2"></i>
                                <?php echo $this->e($_SESSION['flash_error']); ?>
                            </div>
                            <button type="button" class="btn-close" onclick="this.parentElement.remove()" aria-label="Close">×</button>
                        </div>
                        <?php unset($_SESSION['flash_error']); ?>
                        <?php endif; ?>
                        
                        <?php if (isset($_SESSION['flash_success']) && !empty($_SESSION['flash_success'])): ?>
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <div>
                                <i class="fas fa-check-circle me-2"></i>
                                <?php echo $this->e($_SESSION['flash_success']); ?>
                            </div>
                            <button type="button" class="btn-close" onclick="this.parentElement.remove()" aria-label="Close">×</button>
                        </div>
                        <?php unset($_SESSION['flash_success']); ?>
                        <?php endif; ?>
                        
                        <!-- Reset Password Form -->
                        <form method="POST" action="/applicant/reset-password" id="resetForm" novalidate>
                            <!-- ========================================================= -->
                            <!-- 5. Add CSRF token to all forms -->
                            <!-- ========================================================= -->
                            <input type="hidden" name="csrf_token" value="<?php echo $this->e($csrf_token); ?>">
                            <input type="hidden" name="token" value="<?php echo $this->e($token); ?>">
                            
                            <div class="form-group">
                                <label for="password" class="form-label">
                                    <i class="fas fa-lock me-1"></i> New Password <span class="text-danger">*</span>
                                </label>
                                <div class="input-group">
                                    <input type="password" 
                                           class="form-control" 
                                           id="password" 
                                           name="password" 
                                           placeholder="Enter new password"
                                           minlength="8"
                                           required
                                           autocomplete="new-password">
                                    <button type="button" class="btn" onclick="togglePassword('password', 'toggleIcon1')" aria-label="Toggle password visibility">
                                        <i class="fas fa-eye" id="toggleIcon1"></i>
                                    </button>
                                </div>
                                <div class="invalid-feedback" id="passwordError">Password must be at least 8 characters long</div>
                                
                                <!-- Password strength meter -->
                                <div class="password-strength">
                                    <div class="strength-bar" id="strengthBar"></div>
                                </div>
                                
                                <div class="password-requirements">
                                    <i class="fas fa-info-circle"></i> 
                                    Password must be at least 8 characters long and contain letters and numbers
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label for="confirm_password" class="form-label">
                                    <i class="fas fa-check-circle me-1"></i> Confirm Password <span class="text-danger">*</span>
                                </label>
                                <div class="input-group">
                                    <input type="password" 
                                           class="form-control" 
                                           id="confirm_password" 
                                           name="confirm_password" 
                                           placeholder="Confirm new password"
                                           minlength="8"
                                           required
                                           autocomplete="new-password">
                                    <button type="button" class="btn" onclick="togglePassword('confirm_password', 'toggleIcon2')" aria-label="Toggle password visibility">
                                        <i class="fas fa-eye" id="toggleIcon2"></i>
                                    </button>
                                </div>
                                <div class="invalid-feedback" id="confirmError">Passwords do not match</div>
                            </div>
                            
                            <button type="submit" class="btn-reset" id="resetBtn">
                                <span id="resetText"><i class="fas fa-save me-2"></i>Reset Password</span>
                                <span id="resetSpinner" style="display: none;">
                                    <span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>
                                    Resetting...
                                </span>
                            </button>
                        </form>
                    </div>
                </div>
                
                <div class="footer">
                    <p>© <?php echo date('Y'); ?> FCT College of Nursing Sciences. All rights reserved.</p>
                    <p>
                        <i class="fas fa-phone-alt"></i> Support: 07039837749 | 
                        <i class="fas fa-envelope"></i> Email: <a href="mailto:info@fctcns.edu.ng">info@fctcns.edu.ng</a>
                    </p>
                </div>
            </div>
            
            <!-- ========================================================= -->
            <!-- 4. Add CSP nonce to all script tags -->
            <!-- ========================================================= -->
            
            <!-- Bootstrap JS with SRI -->
            <?php 
            $bootstrapJsUrl = 'https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js';
            $bootstrapJsSri = SecurityHelper::getSriHash($bootstrapJsUrl);
            ?>
            <script src="<?php echo $bootstrapJsUrl; ?>" 
                    <?php if ($bootstrapJsSri): ?>integrity="<?php echo $bootstrapJsSri; ?>"<?php endif; ?>
                    crossorigin="anonymous"
                    nonce="<?php echo $csp_nonce; ?>"></script>
            
            <script nonce="<?php echo $csp_nonce; ?>">
                // ======================================================
                // Reset Password JavaScript with Security Enhancements
                // ======================================================
                
                // Get CSRF token from meta tag
                const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

                // Toggle password visibility
                function togglePassword(fieldId, iconId) {
                    const field = document.getElementById(fieldId);
                    const icon = document.getElementById(iconId);
                    
                    if (!field || !icon) return;
                    
                    if (field.type === 'password') {
                        field.type = 'text';
                        icon.classList.remove('fa-eye');
                        icon.classList.add('fa-eye-slash');
                    } else {
                        field.type = 'password';
                        icon.classList.remove('fa-eye-slash');
                        icon.classList.add('fa-eye');
                    }
                }

                // Sanitize input to prevent XSS
                function sanitizeInput(input) {
                    if (!input) return input;
                    return input.replace(/[<>]/g, '').trim();
                }

                // Show toast notification
                function showToast(msg, type = 'success') {
                    // Remove existing toasts
                    document.querySelectorAll('.toast-notification').forEach(t => t.remove());
                    
                    // Create toast element
                    const toast = document.createElement('div');
                    toast.className = `toast-notification toast-${type}`;
                    toast.setAttribute('role', 'alert');
                    
                    const icon = type === 'success' ? 'fa-check-circle' : 
                                type === 'error' ? 'fa-exclamation-circle' : 'fa-info-circle';
                    
                    // Sanitize message to prevent XSS
                    const safeMsg = String(msg).replace(/[<>]/g, '');
                    
                    toast.innerHTML = `<i class="fas ${icon}"></i> ${safeMsg}`;
                    
                    document.body.appendChild(toast);
                    
                    // Auto remove after 3 seconds
                    setTimeout(() => {
                        toast.style.transition = 'opacity 0.3s, transform 0.3s';
                        toast.style.opacity = '0';
                        toast.style.transform = 'translateX(100%)';
                        setTimeout(() => toast.remove(), 300);
                    }, 3000);
                }

                // Check password strength
                function checkPasswordStrength(password) {
                    const strengthBar = document.getElementById('strengthBar');
                    if (!strengthBar) return;
                    
                    // Remove existing classes
                    strengthBar.classList.remove('weak', 'medium', 'strong');
                    
                    if (!password) {
                        strengthBar.style.width = '0';
                        return;
                    }
                    
                    let strength = 0;
                    
                    // Length check
                    if (password.length >= 8) strength++;
                    if (password.length >= 10) strength++;
                    
                    // Character variety
                    if (/[a-z]/.test(password) && /[A-Z]/.test(password)) strength++;
                    if (/[0-9]/.test(password)) strength++;
                    if (/[^a-zA-Z0-9]/.test(password)) strength++;
                    
                    // Update strength bar
                    if (strength <= 2) {
                        strengthBar.classList.add('weak');
                    } else if (strength <= 4) {
                        strengthBar.classList.add('medium');
                    } else {
                        strengthBar.classList.add('strong');
                    }
                }

                // Rate limiting for password reset attempts
                const maxAttempts = 3;
                const lockoutTime = 15 * 60 * 1000; // 15 minutes
                
                function checkRateLimit() {
                    const attempts = parseInt(sessionStorage.getItem('resetPasswordAttempts') || '0');
                    const lockUntil = parseInt(sessionStorage.getItem('resetPasswordLockUntil') || '0');
                    
                    if (Date.now() < lockUntil) {
                        showToast('Too many attempts. Please try again later.', 'error');
                        return false;
                    }
                    
                    if (attempts >= maxAttempts) {
                        sessionStorage.setItem('resetPasswordLockUntil', Date.now() + lockoutTime);
                        showToast('Too many failed attempts. Locked for 15 minutes.', 'error');
                        return false;
                    }
                    
                    return true;
                }

                // Form submission with validation
                document.getElementById('resetForm').addEventListener('submit', function(e) {
                    // Check rate limit
                    if (!checkRateLimit()) {
                        e.preventDefault();
                        return;
                    }

                    const password = document.getElementById('password');
                    const confirm = document.getElementById('confirm_password');
                    const passwordError = document.getElementById('passwordError');
                    const confirmError = document.getElementById('confirmError');
                    
                    // Sanitize inputs
                    if (password) password.value = sanitizeInput(password.value);
                    if (confirm) confirm.value = sanitizeInput(confirm.value);
                    
                    const passwordVal = password ? password.value : '';
                    const confirmVal = confirm ? confirm.value : '';
                    
                    // Reset validation states
                    password.classList.remove('is-invalid');
                    confirm.classList.remove('is-invalid');
                    
                    let isValid = true;

                    // Validate password length
                    if (passwordVal.length < 8) {
                        password.classList.add('is-invalid');
                        isValid = false;
                    }
                    
                    // Validate password match
                    if (passwordVal !== confirmVal) {
                        confirm.classList.add('is-invalid');
                        isValid = false;
                    }
                    
                    if (!isValid) {
                        e.preventDefault();
                        
                        // Scroll to first error
                        const firstError = document.querySelector('.is-invalid');
                        if (firstError) {
                            firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
                        }
                        
                        showToast('Please fix the errors in the form', 'error');
                        return;
                    }
                    
                    // Verify CSRF token exists
                    if (!csrfToken) {
                        console.warn('CSRF token not found');
                        showToast('Security token missing. Please refresh.', 'error');
                        e.preventDefault();
                        return;
                    }

                    // Add timestamp to prevent caching
                    const timestamp = document.createElement('input');
                    timestamp.type = 'hidden';
                    timestamp.name = '_t';
                    timestamp.value = Date.now();
                    this.appendChild(timestamp);

                    // Increment attempt counter
                    const attempts = parseInt(sessionStorage.getItem('resetPasswordAttempts') || '0');
                    sessionStorage.setItem('resetPasswordAttempts', attempts + 1);

                    // Show loading state
                    document.getElementById('resetText').style.display = 'none';
                    document.getElementById('resetSpinner').style.display = 'inline-flex';
                    document.getElementById('resetBtn').disabled = true;
                    
                    // Log reset attempt (for security auditing)
                    console.log('Password reset attempt for:', '<?php echo $this->e($email); ?>');
                });

                // Password strength checker on input
                document.getElementById('password').addEventListener('input', function(e) {
                    checkPasswordStrength(this.value);
                });

                // Real-time password match checker
                document.getElementById('confirm_password').addEventListener('input', function(e) {
                    const password = document.getElementById('password').value;
                    const confirm = this.value;
                    
                    if (confirm && password !== confirm) {
                        this.classList.add('is-invalid');
                    } else {
                        this.classList.remove('is-invalid');
                    }
                });

                // Auto-dismiss alerts after 5 seconds
                setTimeout(function() {
                    document.querySelectorAll('.alert').forEach(function(el) {
                        el.style.transition = 'opacity .4s';
                        el.style.opacity = '0';
                        setTimeout(function() { 
                            if (el.parentNode) el.remove(); 
                        }, 400);
                    });
                }, 5000);

                // Clear rate limiting on successful submission detection
                if (document.querySelector('.alert-success')) {
                    // If we see a success message, clear the rate limit counter
                    sessionStorage.removeItem('resetPasswordAttempts');
                    sessionStorage.removeItem('resetPasswordLockUntil');
                }

                // Handle back button cache
                window.addEventListener('pageshow', function(event) {
                    if (event.persisted) {
                        // Reset button state if coming from cache
                        document.getElementById('resetText').style.display = 'inline-flex';
                        document.getElementById('resetSpinner').style.display = 'none';
                        document.getElementById('resetBtn').disabled = false;
                        
                        // Clear validation states
                        document.getElementById('password').classList.remove('is-invalid');
                        document.getElementById('confirm_password').classList.remove('is-invalid');
                    }
                });

                // Focus on password field on page load
                document.addEventListener('DOMContentLoaded', function() {
                    const passwordField = document.getElementById('password');
                    if (passwordField) {
                        passwordField.focus();
                    }
                    
                    // Check if token is valid (optional)
                    const token = '<?php echo $this->e($token); ?>';
                    if (!token) {
                        showToast('Invalid reset token. Please request a new one.', 'error');
                    }
                });

                // Prevent right-click on password fields
                ['password', 'confirm_password'].forEach(id => {
                    const field = document.getElementById(id);
                    if (field) {
                        field.addEventListener('contextmenu', e => e.preventDefault());
                    }
                });

                // Add keyboard shortcut for form submission (Ctrl/Cmd + Enter)
                document.addEventListener('keydown', function(e) {
                    if ((e.ctrlKey || e.metaKey) && e.key === 'Enter') {
                        e.preventDefault();
                        document.getElementById('resetForm').requestSubmit();
                    }
                });

                // Track page view
                console.log('Reset password page loaded for:', '<?php echo $this->e($email); ?>');
            </script>
        </body>
        </html>
        <?php
    }
}

// =========================================================
// 8. Add the view instantiation at the bottom
// =========================================================
$view = new ResetPasswordView();
$view->render(get_defined_vars());
?>