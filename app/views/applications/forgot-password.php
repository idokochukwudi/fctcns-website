<?php
/**
 * Forgot Password View
 * Simplified to ensure flash messages display properly
 */

// Start output buffering to prevent header issues
ob_start();

// Get security tokens from controller data
$csp_nonce = $csp_nonce ?? '';
$csrf_token = $csrf_token ?? '';
$security_meta_tags = $security_meta_tags ?? '';

$baseUrl = $baseUrl ?? '/';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <!-- Security meta tags -->
    <?php echo $security_meta_tags; ?>
    
    <title>Forgot Password &mdash; FCT College of Nursing Sciences</title>
    
    <!-- CSRF Token -->
    <meta name="csrf-token" content="<?php echo htmlspecialchars($csrf_token); ?>">
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600;700&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" crossorigin="anonymous" referrerpolicy="no-referrer">
    
    <style nonce="<?php echo htmlspecialchars($csp_nonce); ?>">
        /* Reset */
        *, *::before, *::after { margin: 0; padding: 0; box-sizing: border-box; }
        
        /* Variables */
        :root {
            --primary: #6B4E9B;
            --primary-dark: #4A3B6B;
            --primary-light: #8A6FB0;
            --primary-soft: #F3EAF8;
            --gold: #C9A44A;
            --gold-light: #E2B05F;
            --gold-pale: #FDF6E9;
            --success: #10b981;
            --success-light: #d1fae5;
            --danger: #ef4444;
            --danger-light: #fee2e2;
            --warning: #f59e0b;
            --warning-light: #fef3c7;
            --info: #3b82f6;
            --info-light: #dbeafe;
            --border: #E9EDF2;
            --text-dark: #1A1F2E;
            --text-muted: #6B7280;
            --font-sans: 'DM Sans', -apple-system, sans-serif;
            --font-serif: 'Playfair Display', Georgia, serif;
            --radius-md: 12px;
            --radius-lg: 20px;
            --radius-xl: 30px;
            --shadow-xl: 0 20px 25px -5px rgb(0 0 0 / 0.1), 0 8px 10px -6px rgb(0 0 0 / 0.1);
        }
        
        /* Body */
        body {
            font-family: var(--font-sans);
            background: linear-gradient(135deg, var(--primary-soft) 0%, #ffffff 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 24px 16px;
        }
        
        /* Wrapper */
        .forgot-wrap {
            width: 100%;
            max-width: 440px;
            margin: 0 auto;
            animation: fadeIn 0.5s ease-out;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        /* Card */
        .forgot-card {
            background: #ffffff;
            border-radius: var(--radius-xl);
            overflow: hidden;
            box-shadow: var(--shadow-xl);
            border: 1px solid var(--border);
        }
        
        /* Header */
        .card-head {
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            padding: 28px 36px 28px;
            text-align: center;
            position: relative;
        }
        
        .card-head::before {
            content: '';
            position: absolute;
            top: 0; left: 0; right: 0;
            height: 3px;
            background: linear-gradient(90deg, var(--gold), var(--gold-light), var(--gold));
        }
        
        .logo-container {
            margin-top: 15px;
            margin-bottom: 16px;
        }
        
        .college-logo {
            width: 90px;
            height: 90px;
            border-radius: 50%;
            background: white;
            padding: 8px;
            box-shadow: 0 8px 20px rgba(0,0,0,0.2);
            border: 3px solid var(--gold);
            object-fit: contain;
        }
        
        .card-head h1 {
            font-family: var(--font-serif);
            font-size: 1.35rem;
            font-weight: 700;
            color: #FFFFFF;
            margin-bottom: 8px;
        }
        
        .card-head p {
            font-size: 12px;
            font-weight: 500;
            color: rgba(255,255,255,0.9);
        }
        
        .card-head-rule {
            width: 50px;
            height: 3px;
            background: var(--gold);
            border-radius: 3px;
            margin: 15px auto 0;
        }
        
        /* Body */
        .card-body {
            padding: 30px 36px 28px;
        }
        
        /* Info banner */
        .info-banner {
            display: flex;
            align-items: flex-start;
            gap: 12px;
            background: var(--primary-soft);
            border: 1px solid var(--primary-light);
            border-left: 4px solid var(--gold);
            border-radius: var(--radius-md);
            padding: 14px 16px;
            margin-bottom: 24px;
            font-size: 14px;
            color: var(--text-dark);
            line-height: 1.5;
        }
        
        .info-banner i {
            color: var(--gold);
            font-size: 1rem;
            flex-shrink: 0;
        }
        
        /* Alerts - CRITICAL: Ensure these are visible */
        .alert {
            display: flex;
            align-items: flex-start;
            gap: 12px;
            padding: 14px 16px;
            border-radius: var(--radius-md);
            margin-bottom: 20px;
            font-size: 14px;
            border-left-width: 4px;
            border-left-style: solid;
            animation: slideIn 0.3s ease;
            position: relative;
        }
        
        @keyframes slideIn {
            from { opacity: 0; transform: translateY(-6px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .alert-success {
            background: var(--success-light);
            border-color: var(--success);
            color: #065f46;
        }
        
        .alert-danger {
            background: var(--danger-light);
            border-color: var(--danger);
            color: #991b1b;
        }
        
        .alert-info {
            background: var(--info-light);
            border-color: var(--info);
            color: #1e40af;
        }
        
        .alert i { 
            font-size: 1rem; 
            flex-shrink: 0; 
            margin-top: 1px; 
        }
        
        .alert-success i { color: var(--success); }
        .alert-danger i { color: var(--danger); }
        .alert-info i { color: var(--info); }
        
        .alert-close {
            margin-left: auto;
            background: none;
            border: none;
            cursor: pointer;
            color: currentColor;
            opacity: 0.45;
            font-size: 1rem;
            line-height: 1;
            padding: 0 4px;
            flex-shrink: 0;
        }
        .alert-close:hover { opacity: 1; }
        
        /* Form */
        .form-group { margin-bottom: 22px; }
        
        .form-label {
            display: flex;
            align-items: center;
            gap: 6px;
            font-size: 13px;
            font-weight: 600;
            color: var(--primary-dark);
            margin-bottom: 8px;
        }
        
        .form-label i {
            color: var(--primary);
            font-size: 0.85rem;
        }
        
        .form-label .req { color: var(--danger); margin-left: 2px; }
        
        .input-wrap {
            position: relative;
            display: flex;
            align-items: center;
        }
        
        .input-wrap .input-icon {
            position: absolute;
            left: 14px;
            color: var(--text-muted);
            font-size: 0.9rem;
            pointer-events: none;
        }
        
        .form-control {
            width: 100%;
            padding: 14px 14px 14px 40px;
            border: 2px solid var(--border);
            border-radius: var(--radius-md);
            font-size: 15px;
            font-family: var(--font-sans);
            color: var(--text-dark);
            background: #ffffff;
            transition: all 0.2s ease;
        }
        
        .form-control:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 4px var(--primary-soft);
            outline: none;
        }
        
        .form-control.is-invalid {
            border-color: var(--danger);
            background: #fff8f8;
        }
        
        .invalid-msg {
            font-size: 12px;
            color: var(--danger);
            margin-top: 6px;
            display: none;
            padding-left: 4px;
        }
        
        .form-control.is-invalid ~ .invalid-msg { display: block; }
        
        /* Button */
        .btn-reset {
            width: 100%;
            padding: 15px;
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            color: #fff;
            border: none;
            border-radius: var(--radius-md);
            font-family: var(--font-sans);
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            transition: all 0.2s ease;
            box-shadow: 0 10px 30px rgba(107,78,155,0.3);
            margin-top: 10px;
        }
        
        .btn-reset:hover:not(:disabled) {
            transform: translateY(-2px);
            box-shadow: 0 15px 35px rgba(107,78,155,0.4);
        }
        
        .btn-reset:disabled {
            opacity: 0.65;
            cursor: not-allowed;
        }
        
        /* Spinner */
        .spinner {
            display: inline-block;
            width: 16px; height: 16px;
            border: 2px solid rgba(255,255,255,0.4);
            border-top-color: #fff;
            border-radius: 50%;
            animation: spin 0.7s linear infinite;
            vertical-align: middle;
        }
        
        @keyframes spin { to { transform: rotate(360deg); } }
        
        /* Divider */
        .divider {
            display: flex;
            align-items: center;
            gap: 12px;
            margin: 28px 0 22px;
            color: var(--text-muted);
            font-size: 13px;
        }
        
        .divider::before,
        .divider::after {
            content: '';
            flex: 1;
            height: 1px;
            background: linear-gradient(90deg, transparent, var(--border), transparent);
        }
        
        /* Actions */
        .actions-block {
            display: flex;
            flex-direction: column;
            gap: 12px;
        }
        
        .btn-outline {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            width: 100%;
            padding: 14px;
            background: transparent;
            color: var(--primary);
            border: 2px solid var(--primary);
            border-radius: var(--radius-md);
            font-family: var(--font-sans);
            font-size: 15px;
            font-weight: 600;
            text-decoration: none;
            cursor: pointer;
            transition: all 0.2s ease;
        }
        
        .btn-outline:hover {
            background: var(--primary);
            color: #ffffff;
            transform: translateY(-2px);
        }
        
        /* Footer */
        .page-foot {
            margin-top: 20px;
            text-align: center;
        }
        
        .page-foot a {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            font-size: 13px;
            font-weight: 600;
            color: var(--primary);
            text-decoration: none;
            transition: color 0.2s;
        }
        
        .page-foot a:hover {
            color: var(--primary-dark);
        }
        
        /* Responsive */
        @media (max-width: 480px) {
            body { padding: 12px; }
            .card-head { padding: 20px 22px; }
            .logo-container { margin-top: 10px; }
            .college-logo { width: 80px; height: 80px; }
            .card-body { padding: 22px; }
        }
    </style>
</head>
<body>
    <div class="forgot-wrap">
        <div class="forgot-card">
            <!-- Header -->
            <div class="card-head">
                <div class="logo-container">
                    <img src="/assets/images/logo/logo.png" alt="FCT College of Nursing Sciences Logo" class="college-logo" id="collegeLogo">
                </div>
                <h1>Forgot Password</h1>
                <p>Reset your password to regain access</p>
                <div class="card-head-rule"></div>
            </div>
            
            <!-- Body -->
            <div class="card-body">
                <!-- Info banner -->
                <div class="info-banner">
                    <i class="fas fa-info-circle"></i>
                    <span>Enter your registered email address and we'll send you a link to reset your password.</span>
                </div>
                
                <!-- ========================================================= -->
                <!-- FLASH MESSAGES - CRITICAL SECTION -->
                <!-- ========================================================= -->
                
                <?php if (isset($_SESSION['flash_success']) && !empty($_SESSION['flash_success'])): ?>
                <div class="alert alert-success" role="alert" data-alert="success">
                    <i class="fas fa-check-circle"></i>
                    <span><?php echo htmlspecialchars($_SESSION['flash_success']); ?></span>
                    <button class="alert-close" aria-label="Close">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <?php 
                // Keep success message for redirect, but mark as shown
                $_SESSION['flash_success_shown'] = true;
                endif; 
                ?>
                
                <?php if (isset($_SESSION['flash_error']) && !empty($_SESSION['flash_error'])): ?>
                <div class="alert alert-danger" role="alert" data-alert="error">
                    <i class="fas fa-exclamation-circle"></i>
                    <span><?php echo htmlspecialchars($_SESSION['flash_error']); ?></span>
                    <button class="alert-close" aria-label="Close">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <?php 
                unset($_SESSION['flash_error']); 
                endif; 
                ?>
                
                <?php if (isset($_SESSION['flash_info']) && !empty($_SESSION['flash_info'])): ?>
                <div class="alert alert-info" role="alert" data-alert="info">
                    <i class="fas fa-info-circle"></i>
                    <span><?php echo htmlspecialchars($_SESSION['flash_info']); ?></span>
                    <button class="alert-close" aria-label="Close">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <?php 
                unset($_SESSION['flash_info']); 
                endif; 
                ?>
                
                <!-- Form -->
                <form method="POST" action="/applicant/forgot-password/process" id="forgotForm" novalidate>
                    <!-- CSRF Token -->
                    <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($csrf_token); ?>">
                    
                    <div class="form-group">
                        <label for="email" class="form-label">
                            <i class="fas fa-envelope"></i>
                            Email Address <span class="req">*</span>
                        </label>
                        <div class="input-wrap">
                            <i class="fas fa-envelope input-icon"></i>
                            <input type="email"
                                   class="form-control"
                                   id="email"
                                   name="email"
                                   value="<?php echo htmlspecialchars($_SESSION['email_value'] ?? ''); ?>"
                                   placeholder="Enter your registered email address"
                                   autocomplete="email"
                                   required>
                        </div>
                        <div class="invalid-msg" id="emailError">Please enter a valid email address.</div>
                    </div>
                    
                    <button type="submit" class="btn-reset" id="resetBtn">
                        <span id="resetText">
                            <i class="fas fa-paper-plane"></i> Send Reset Link
                        </span>
                        <span id="resetSpinner" style="display:none">
                            <span class="spinner"></span> Sending&hellip;
                        </span>
                    </button>
                </form>
                
                <!-- Secondary actions -->
                <div class="divider">OR</div>
                
                <div class="actions-block">
                    <a href="/applicant/login" class="btn-outline">
                        <i class="fas fa-arrow-left"></i> Back to Login
                    </a>
                </div>
            </div>
        </div>
        
        <!-- Footer -->
        <div class="page-foot">
            <a href="/">
                <i class="fas fa-arrow-left"></i> Return to Home
            </a>
        </div>
    </div>
    
    <script nonce="<?php echo htmlspecialchars($csp_nonce); ?>">
        (function() {
            'use strict';
            
            // Get elements
            const forgotForm = document.getElementById('forgotForm');
            const emailInput = document.getElementById('email');
            const emailError = document.getElementById('emailError');
            const resetBtn = document.getElementById('resetBtn');
            const resetText = document.getElementById('resetText');
            const resetSpinner = document.getElementById('resetSpinner');
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
            
            // Sanitize input to prevent XSS
            function sanitizeInput(input) {
                if (!input) return input;
                return input.replace(/[<>]/g, '').trim();
            }
            
            // Handle alert close buttons
            function setupAlertCloseButtons() {
                const closeButtons = document.querySelectorAll('.alert-close');
                closeButtons.forEach(function(button) {
                    button.addEventListener('click', function() {
                        const alert = this.closest('.alert');
                        if (alert) {
                            alert.style.transition = 'opacity .4s, transform .4s';
                            alert.style.opacity = '0';
                            alert.style.transform = 'translateY(-10px)';
                            setTimeout(function() {
                                if (alert.parentNode) alert.remove();
                            }, 400);
                        }
                    });
                });
            }
            
            // Auto-dismiss alerts after 5 seconds
            function setupAutoDismiss() {
                const alerts = document.querySelectorAll('.alert');
                if (alerts.length > 0) {
                    setTimeout(function() {
                        alerts.forEach(function(alert) {
                            alert.style.transition = 'opacity .4s, transform .4s';
                            alert.style.opacity = '0';
                            alert.style.transform = 'translateY(-10px)';
                            setTimeout(function() {
                                if (alert.parentNode) alert.remove();
                            }, 400);
                        });
                    }, 5000);
                }
            }
            
            // Form validation
            function validateForm(e) {
                if (!emailInput || !emailError || !resetBtn || !resetText || !resetSpinner) {
                    return true; // Allow submission if elements missing
                }
                
                const email = emailInput.value.trim();
                const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                
                emailInput.classList.remove('is-invalid');
                emailError.style.display = 'none';
                
                if (!email || !emailRegex.test(email)) {
                    emailInput.classList.add('is-invalid');
                    emailError.style.display = 'block';
                    e.preventDefault();
                    emailInput.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    return false;
                }
                
                // Show spinner
                resetText.style.display = 'none';
                resetSpinner.style.display = 'inline-flex';
                resetBtn.disabled = true;
                
                return true;
            }
            
            // Handle logo error
            const logo = document.getElementById('collegeLogo');
            if (logo) {
                logo.addEventListener('error', function() {
                    // Create SVG data URI as fallback
                    const svgData = '<svg xmlns="http://www.w3.org/2000/svg" width="90" height="90" viewBox="0 0 100 100"><rect width="100" height="100" fill="#6B4E9B"/><text x="20" y="65" fill="#C9A44A" font-size="40" font-weight="bold">FCT</text></svg>';
                    this.src = 'data:image/svg+xml;utf8,' + encodeURIComponent(svgData);
                });
            }
            
            // Set up event listeners
            if (forgotForm) {
                forgotForm.addEventListener('submit', validateForm);
            }
            
            // Initialize
            setupAlertCloseButtons();
            setupAutoDismiss();
            
            // Focus on email field if empty
            if (emailInput && !emailInput.value) {
                emailInput.focus();
            }
            
            // Clear flash messages from session after they've been displayed
            <?php 
            // Clear success message after it's been shown
            if (isset($_SESSION['flash_success']) && isset($_SESSION['flash_success_shown'])) {
                unset($_SESSION['flash_success']);
                unset($_SESSION['flash_success_shown']);
            }
            
            // Clear email value from session
            unset($_SESSION['email_value']);
            ?>
        })();
    </script>
</body>
</html>
<?php
// End output buffering and flush
ob_end_flush();
?>