<?php
/**
 * Forgot Password View
 * Redesigned to match JAMB verification page design system.
 *
 * @package FCTCNS
 */

// =========================================================
// FIX: Add require for SecurityHelper and SecurityTrait
// =========================================================
require_once APP_PATH . '/helpers/SecurityHelper.php';
require_once APP_PATH . '/helpers/SecurityTrait.php';

class ForgotPasswordView {
    use SecurityTrait;
    
    public function render($data) {
        extract($data);
        
        // Get security tokens
        $csp_nonce = $this->getCspNonce();
        $csrf_token = $this->getCsrfToken();

        $baseUrl    = $baseUrl    ?? '/';
        ?>
        <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0">
            
            <!-- ========================================================= -->
            <!-- 2. Add security meta tags in the head -->
            <!-- ========================================================= -->
            <?php echo $this->getSecurityMetaTags(); ?>
            
            <title>Forgot Password &mdash; FCT College of Nursing Sciences</title>

            <!-- CSRF Token for JavaScript -->
            <meta name="csrf-token" content="<?php echo $this->e($csrf_token); ?>">

            <!-- ========================================================= -->
            <!-- 3. Add CSP nonce to all style tags -->
            <!-- 7. Add SRI hashes to external scripts/styles -->
            <!-- ========================================================= -->
            <link rel="preconnect" href="https://fonts.googleapis.com">
            <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
            
            <!-- Google Fonts - Keep original fonts -->
            <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600;700&family=DM+Sans:wght@300;400;500;600&display=swap" 
                  rel="stylesheet"
                  crossorigin="anonymous">
            
            <!-- Font Awesome with CORRECT SRI hash -->
            <?php 
            $faUrl = 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css';
            $faSri = SecurityHelper::getSriHash($faUrl);
            ?>
            <link rel="stylesheet" 
                  href="<?php echo $faUrl; ?>"
                  <?php if ($faSri): ?>integrity="<?php echo $faSri; ?>"<?php endif; ?>
                  crossorigin="anonymous" 
                  referrerpolicy="no-referrer">

            <style nonce="<?php echo $csp_nonce; ?>">
                /* ── Reset ─────────────────────────────────────────────── */
                *, *::before, *::after { margin: 0; padding: 0; box-sizing: border-box; }

                /* ── Tokens (Matching JAMB verification page) ───────────────── */
                :root {
                    --sv1-primary:       #6B4E9B;
                    --sv1-primary-dark:  #4A3B6B;
                    --sv1-primary-light: #8A6FB0;
                    --sv1-primary-soft:  #F3EAF8;
                    --sv1-gold:          #C9A44A;
                    --sv1-gold-light:    #E2B05F;
                    --sv1-gold-pale:     #FDF6E9;
                    --sv1-success:       #10b981;
                    --sv1-success-light: #d1fae5;
                    --sv1-danger:        #ef4444;
                    --sv1-danger-light:  #fee2e2;
                    --sv1-warning:       #f59e0b;
                    --sv1-warning-light: #fef3c7;
                    --sv1-info:          #3b82f6;
                    --sv1-info-light:    #dbeafe;
                    --sv1-border:        #E9EDF2;
                    --sv1-text-dark:     #1A1F2E;
                    --sv1-text-muted:    #6B7280;
                    
                    /* Keep original fonts */
                    --font-sans: 'DM Sans', -apple-system, sans-serif;
                    --font-serif: 'Playfair Display', Georgia, serif;
                    
                    /* Borders */
                    --sv1-radius-md:     12px;
                    --sv1-radius-lg:     20px;
                    --sv1-radius-xl:     30px;
                    
                    /* Shadows */
                    --sv1-shadow-primary: 0 10px 30px rgba(107,78,155,0.3);
                    --shadow-sm: 0 1px 3px 0 rgb(0 0 0 / 0.1), 0 1px 2px -1px rgb(0 0 0 / 0.1);
                    --shadow-md: 0 4px 6px -1px rgb(0 0 0 / 0.1), 0 2px 4px -2px rgb(0 0 0 / 0.1);
                    --shadow-lg: 0 10px 15px -3px rgb(0 0 0 / 0.1), 0 4px 6px -4px rgb(0 0 0 / 0.1);
                    --shadow-xl: 0 20px 25px -5px rgb(0 0 0 / 0.1), 0 8px 10px -6px rgb(0 0 0 / 0.1);
                }

                /* ── Body / Page ─────────────────────────────────────────── */
                html, body { height: 100%; }

                body {
                    font-family: var(--font-sans);
                    background: linear-gradient(135deg, var(--sv1-primary-soft) 0%, #ffffff 100%);
                    min-height: 100vh;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    padding: 24px 16px;
                    position: relative;
                    overflow-x: hidden;
                }

                /* Decorative background rings - updated with purple tones */
                body::before,
                body::after {
                    content: '';
                    position: fixed;
                    border-radius: 50%;
                    pointer-events: none;
                }
                body::before {
                    width: 600px; height: 600px;
                    border: 1px solid rgba(107,78,155,0.08);
                    top: -200px; right: -150px;
                }
                body::after {
                    width: 400px; height: 400px;
                    border: 1px solid rgba(107,78,155,0.06);
                    bottom: -150px; left: -100px;
                }

                /* ── Wrapper ─────────────────────────────────────────────── */
                .forgot-wrap {
                    width: 100%;
                    max-width: 440px;
                    margin: 0 auto;
                    position: relative;
                    z-index: 1;
                    animation: fadeIn 0.5s ease-out;
                }

                @keyframes fadeIn {
                    from { opacity: 0; transform: translateY(10px); }
                    to   { opacity: 1; transform: translateY(0); }
                }

                /* ── Card ────────────────────────────────────────────────── */
                .forgot-card {
                    background: #ffffff;
                    border-radius: var(--sv1-radius-xl);
                    overflow: visible;
                    box-shadow: var(--shadow-xl);
                    border: 1px solid var(--sv1-border);
                }

                /* ── Card Header (Purple gradient like JAMB page) ───────── */
                .card-head {
                    background: linear-gradient(135deg, var(--sv1-primary) 0%, var(--sv1-primary-dark) 100%);
                    padding: 28px 36px 28px;
                    text-align: center;
                    position: relative;
                    overflow: visible;
                }

                /* Gold top line accent */
                .card-head::before {
                    content: '';
                    position: absolute;
                    top: 0; left: 0; right: 0;
                    height: 3px;
                    background: linear-gradient(90deg, var(--sv1-gold), var(--sv1-gold-light), var(--sv1-gold));
                }

                /* Background texture */
                .card-head::after {
                    content: '';
                    position: absolute;
                    inset: 0;
                    background-image: repeating-linear-gradient(
                        45deg, transparent, transparent 40px,
                        rgba(255,255,255,0.02) 40px, rgba(255,255,255,0.02) 41px
                    );
                    pointer-events: none;
                }

                /* Logo container */
                .logo-container {
                    position: relative;
                    z-index: 10;
                    margin-top: 110px; /* Pushed down as requested */
                    margin-bottom: 16px;
                    display: flex;
                    justify-content: center;
                }

                .college-logo {
                    width: 90px;
                    height: 90px;
                    border-radius: 50%;
                    background: white;
                    padding: 8px;
                    box-shadow: 0 8px 20px rgba(0,0,0,0.2);
                    border: 3px solid var(--sv1-gold);
                    object-fit: contain;
                }

                .card-head h1 {
                    position: relative;
                    z-index: 1;
                    font-family: var(--font-serif);
                    font-size: 1.35rem;
                    font-weight: 700;
                    color: #FFFFFF;
                    line-height: 1.4;
                    margin-top: 15px;
                    margin-bottom: 8px;
                    letter-spacing: -0.01em;
                    text-shadow: 0 2px 4px rgba(0,0,0,0.2);
                }

                .card-head p {
                    position: relative;
                    z-index: 1;
                    font-size: 12px;
                    font-weight: 500;
                    color: rgba(255,255,255,0.9);
                    letter-spacing: 0.5px;
                    margin: 0;
                    line-height: 1.5;
                }

                .card-head-rule {
                    position: relative;
                    z-index: 1;
                    width: 50px;
                    height: 3px;
                    background: var(--sv1-gold);
                    border-radius: 3px;
                    margin: 15px auto 0;
                }

                /* ── Card Body ───────────────────────────────────────────── */
                .card-body {
                    padding: 30px 36px 28px;
                }

                /* ── Info banner ─────────────────────────────────────────── */
                .info-banner {
                    display: flex;
                    align-items: flex-start;
                    gap: 12px;
                    background: var(--sv1-primary-soft);
                    border: 1px solid var(--sv1-primary-light);
                    border-left: 4px solid var(--sv1-gold);
                    border-radius: var(--sv1-radius-md);
                    padding: 14px 16px;
                    margin-bottom: 24px;
                    font-size: 14px;
                    color: var(--sv1-text-dark);
                    line-height: 1.5;
                }

                .info-banner i {
                    color: var(--sv1-gold);
                    font-size: 1rem;
                    flex-shrink: 0;
                    margin-top: 1px;
                }

                /* ── Alerts ──────────────────────────────────────────────── */
                .alert {
                    display: flex;
                    align-items: flex-start;
                    gap: 12px;
                    padding: 14px 16px;
                    border-radius: var(--sv1-radius-md);
                    margin-bottom: 20px;
                    font-size: 14px;
                    border-left-width: 4px;
                    border-left-style: solid;
                    animation: slideIn 0.3s ease;
                }

                @keyframes slideIn {
                    from { opacity: 0; transform: translateY(-6px); }
                    to   { opacity: 1; transform: translateY(0); }
                }

                .alert-danger {
                    background: var(--sv1-danger-light);
                    border-color: var(--sv1-danger);
                    color: #991b1b;
                }

                .alert-success {
                    background: var(--sv1-success-light);
                    border-color: var(--sv1-success);
                    color: #065f46;
                }

                .alert-info {
                    background: var(--sv1-info-light);
                    border-color: var(--sv1-info);
                    color: #1e40af;
                }

                .alert i { font-size: 1rem; flex-shrink: 0; margin-top: 1px; }
                .alert-danger i { color: var(--sv1-danger); }
                .alert-success i { color: var(--sv1-success); }
                .alert-info i { color: var(--sv1-info); }

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

                /* ── Form ────────────────────────────────────────────────── */
                .form-group { margin-bottom: 22px; }

                .form-label {
                    display: flex;
                    align-items: center;
                    gap: 6px;
                    font-size: 13px;
                    font-weight: 600;
                    color: var(--sv1-primary-dark);
                    margin-bottom: 8px;
                    letter-spacing: 0.2px;
                }

                .form-label i {
                    color: var(--sv1-primary);
                    font-size: 0.85rem;
                }

                .form-label .req { color: var(--sv1-danger); margin-left: 2px; }

                .input-wrap {
                    position: relative;
                    display: flex;
                    align-items: center;
                }

                .input-wrap .input-icon {
                    position: absolute;
                    left: 14px;
                    color: var(--sv1-text-muted);
                    font-size: 0.9rem;
                    pointer-events: none;
                }

                .form-control {
                    width: 100%;
                    padding: 14px 14px 14px 40px;
                    border: 2px solid var(--sv1-border);
                    border-radius: var(--sv1-radius-md);
                    font-size: 15px;
                    font-family: var(--font-sans);
                    color: var(--sv1-text-dark);
                    background: #ffffff;
                    transition: all 0.2s ease;
                }

                .form-control::placeholder {
                    color: var(--sv1-text-muted);
                    font-size: 14px;
                    opacity: 0.6;
                }

                .form-control:focus {
                    border-color: var(--sv1-primary);
                    box-shadow: 0 0 0 4px var(--sv1-primary-soft);
                    outline: none;
                }

                .form-control.is-invalid {
                    border-color: var(--sv1-danger);
                    background: #fff8f8;
                }

                .invalid-msg {
                    font-size: 12px;
                    color: var(--sv1-danger);
                    margin-top: 6px;
                    display: none;
                    padding-left: 4px;
                }

                .form-control.is-invalid ~ .invalid-msg { display: block; }

                /* ── Submit button (Matching JAMB page) ──────────────────── */
                .btn-reset {
                    width: 100%;
                    padding: 15px;
                    background: linear-gradient(135deg, var(--sv1-primary), var(--sv1-primary-dark));
                    color: #fff;
                    border: none;
                    border-radius: var(--sv1-radius-md);
                    font-family: var(--font-sans);
                    font-size: 16px;
                    font-weight: 600;
                    cursor: pointer;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    gap: 8px;
                    transition: all 0.2s ease;
                    box-shadow: var(--sv1-shadow-primary);
                    margin-top: 10px;
                    letter-spacing: 0.3px;
                }

                .btn-reset:hover:not(:disabled) {
                    transform: translateY(-2px);
                    box-shadow: 0 15px 35px rgba(107,78,155,0.4);
                }

                .btn-reset:active:not(:disabled) {
                    transform: translateY(0);
                }

                .btn-reset:disabled {
                    opacity: 0.65;
                    cursor: not-allowed;
                }

                /* ── Spinner ─────────────────────────────────────────────── */
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

                /* ── Divider ─────────────────────────────────────────────── */
                .divider {
                    display: flex;
                    align-items: center;
                    gap: 12px;
                    margin: 28px 0 22px;
                    color: var(--sv1-text-muted);
                    font-size: 13px;
                }

                .divider::before,
                .divider::after {
                    content: '';
                    flex: 1;
                    height: 1px;
                    background: linear-gradient(90deg, transparent, var(--sv1-border), transparent);
                }

                /* ── Secondary actions ───────────────────────────────────── */
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
                    color: var(--sv1-primary);
                    border: 2px solid var(--sv1-primary);
                    border-radius: var(--sv1-radius-md);
                    font-family: var(--font-sans);
                    font-size: 15px;
                    font-weight: 600;
                    text-decoration: none;
                    cursor: pointer;
                    transition: all 0.2s ease;
                }

                .btn-outline:hover {
                    background: var(--sv1-primary);
                    color: #ffffff;
                    transform: translateY(-2px);
                }

                .btn-outline:active {
                    transform: translateY(0);
                }

                /* ── Return link ─────────────────────────────────────────── */
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
                    color: var(--sv1-primary);
                    text-decoration: none;
                    transition: color 0.2s;
                }

                .page-foot a:hover {
                    color: var(--sv1-primary-dark);
                }

                .page-foot a i {
                    font-size: 0.7rem;
                }

                /* ── Toast notification ───────────────────────────────────── */
                .toast-notification {
                    position: fixed;
                    top: 20px;
                    right: 20px;
                    background: var(--sv1-primary);
                    color: white;
                    padding: 14px 20px;
                    border-radius: var(--sv1-radius-md);
                    font-size: 14px;
                    font-weight: 500;
                    display: flex;
                    align-items: center;
                    gap: 10px;
                    box-shadow: var(--shadow-lg);
                    z-index: 9999;
                    animation: toastSlideIn 0.3s ease;
                    border-left: 4px solid var(--sv1-gold);
                }

                @keyframes toastSlideIn {
                    from {
                        opacity: 0;
                        transform: translateX(100%);
                    }
                    to {
                        opacity: 1;
                        transform: translateX(0);
                    }
                }

                /* ── Responsive ──────────────────────────────────────────── */
                @media (max-width: 480px) {
                    body {
                        padding: 12px;
                    }
                    
                    .card-head {
                        padding: 20px 22px 22px;
                    }
                    
                    .logo-container {
                        margin-top: 65px; /* Slightly smaller on mobile */
                    }
                    
                    .college-logo {
                        width: 80px;
                        height: 80px;
                    }
                    
                    .card-head h1 { 
                        font-size: 1.2rem;
                        margin-top: 15px;
                    }
                    
                    .card-head p { 
                        font-size: 10px; 
                    }
                    
                    .card-body { 
                        padding: 22px 22px 22px; 
                    }
                    
                    .btn-reset {
                        padding: 13px;
                        font-size: 15px;
                    }
                    
                    .btn-outline {
                        padding: 12px;
                        font-size: 14px;
                    }
                    
                    .form-control {
                        padding: 13px 13px 13px 38px;
                    }
                }
            </style>
        </head>
        <body>

            <div class="forgot-wrap">

                <div class="forgot-card">

                    <!-- Header with Logo -->
                    <div class="card-head">
                        
                        <!-- Logo Container - pushed down with margin-top: 75px -->
                        <div class="logo-container">
                            <img src="/assets/images/logo/logo.png" alt="FCT College of Nursing Sciences Logo" class="college-logo">
                        </div>
                        
                        <h1>Forgot Password</h1>
                        <p>Reset your password to regain access to your account</p>
                        <div class="card-head-rule"></div>
                    </div>

                    <!-- Body -->
                    <div class="card-body">

                        <!-- Info banner -->
                        <div class="info-banner">
                            <i class="fas fa-info-circle"></i>
                            <span>Enter your registered email address and we'll send you a link to reset your password.</span>
                        </div>

                        <!-- Flash messages -->
                        <?php if (!empty($_SESSION['flash_success'])): ?>
                        <div class="alert alert-success" role="alert">
                            <i class="fas fa-check-circle"></i>
                            <span><?php echo $this->e($_SESSION['flash_success']); ?></span>
                            <button class="alert-close" onclick="this.closest('.alert').remove()" aria-label="Close">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                        <?php unset($_SESSION['flash_success']); ?>
                        <?php endif; ?>

                        <?php if (!empty($_SESSION['flash_error'])): ?>
                        <div class="alert alert-danger" role="alert">
                            <i class="fas fa-exclamation-circle"></i>
                            <span><?php echo $this->e($_SESSION['flash_error']); ?></span>
                            <button class="alert-close" onclick="this.closest('.alert').remove()" aria-label="Close">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                        <?php unset($_SESSION['flash_error']); ?>
                        <?php endif; ?>

                        <!-- Form -->
                        <form method="POST" action="/applicant/forgot-password" id="forgotForm" novalidate>
                            <!-- ========================================================= -->
                            <!-- 5. Add CSRF token to all forms -->
                            <!-- ========================================================= -->
                            <input type="hidden" name="csrf_token" value="<?php echo $this->e($csrf_token); ?>">

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
                                           value="<?php echo $this->e($_SESSION['email_value'] ?? ''); ?>"
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
                            <a href="/apply/register" class="btn-outline">
                                <i class="fas fa-user-plus"></i> Start New Application
                            </a>
                        </div>

                    </div><!-- /card-body -->
                </div><!-- /forgot-card -->

                <!-- Return to Home -->
                <div class="page-foot">
                    <a href="/">
                        <i class="fas fa-arrow-left"></i> Return to Home
                    </a>
                </div>

            </div><!-- /forgot-wrap -->

            <!-- ========================================================= -->
            <!-- 4. Add CSP nonce to all script tags -->
            <!-- ========================================================= -->
            <script nonce="<?php echo $csp_nonce; ?>">
                // ======================================================
                // Forgot Password JavaScript (Unchanged)
                // ======================================================
                
                // Get CSRF token from meta tag
                const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';

                // Sanitize input to prevent XSS
                function sanitizeInput(input) {
                    if (!input) return input;
                    return input.replace(/[<>]/g, '').trim();
                }

                // Show toast notification
                function showToast(msg, type = 'success') {
                    document.querySelectorAll('.toast-notification').forEach(t => t.remove());
                    
                    const toast = document.createElement('div');
                    toast.className = 'toast-notification';
                    toast.setAttribute('role', 'alert');
                    
                    const icon = type === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle';
                    toast.innerHTML = `<i class="fas ${icon}"></i> ${sanitizeInput(msg)}`;
                    
                    document.body.appendChild(toast);
                    
                    setTimeout(() => {
                        toast.style.transition = 'opacity 0.3s, transform 0.3s';
                        toast.style.opacity = '0';
                        toast.style.transform = 'translateX(100%)';
                        setTimeout(() => toast.remove(), 300);
                    }, 3000);
                }

                // Rate limiting for password reset requests
                function checkRateLimit() {
                    const attempts = parseInt(sessionStorage.getItem('resetAttempts') || '0');
                    const lockUntil = parseInt(sessionStorage.getItem('resetLockUntil') || '0');
                    
                    if (Date.now() < lockUntil) {
                        showToast('Too many reset attempts. Please try again later.', 'error');
                        return false;
                    }
                    
                    if (attempts >= 3) {
                        sessionStorage.setItem('resetLockUntil', Date.now() + (30 * 60 * 1000));
                        showToast('Too many reset attempts. Locked for 30 minutes.', 'error');
                        return false;
                    }
                    
                    return true;
                }

                // Form submission with validation
                document.getElementById('forgotForm')?.addEventListener('submit', function (e) {
                    if (!checkRateLimit()) {
                        e.preventDefault();
                        return;
                    }

                    const email     = document.getElementById('email');
                    const errorMsg  = document.getElementById('emailError');
                    const val       = email.value.trim();
                    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

                    email.value = sanitizeInput(val);
                    email.classList.remove('is-invalid');
                    errorMsg.style.display = 'none';

                    if (!val || !emailRegex.test(val)) {
                        email.classList.add('is-invalid');
                        errorMsg.style.display = 'block';
                        e.preventDefault();
                        email.scrollIntoView({ behavior: 'smooth', block: 'center' });
                        return;
                    }

                    if (!csrfToken) {
                        console.warn('CSRF token not found');
                        showToast('Security token missing. Please refresh.', 'error');
                        e.preventDefault();
                        return;
                    }

                    const timestamp = document.createElement('input');
                    timestamp.type = 'hidden';
                    timestamp.name = '_t';
                    timestamp.value = Date.now();
                    this.appendChild(timestamp);

                    const attempts = parseInt(sessionStorage.getItem('resetAttempts') || '0');
                    sessionStorage.setItem('resetAttempts', attempts + 1);

                    document.getElementById('resetText').style.display = 'none';
                    document.getElementById('resetSpinner').style.display = 'inline-flex';
                    document.getElementById('resetBtn').disabled = true;
                });

                // Auto-dismiss alerts
                setTimeout(function () {
                    document.querySelectorAll('.alert').forEach(function (el) {
                        el.style.transition = 'opacity .4s, transform .4s';
                        el.style.opacity = '0';
                        el.style.transform = 'translateY(-10px)';
                        setTimeout(function () { 
                            if (el.parentNode) el.remove(); 
                        }, 400);
                    });
                }, 5500);

                <?php unset($_SESSION['email_value']); ?>

                if (document.querySelector('.alert-success')) {
                    sessionStorage.removeItem('resetAttempts');
                    sessionStorage.removeItem('resetLockUntil');
                }

                window.addEventListener('pageshow', function(event) {
                    if (event.persisted) {
                        document.getElementById('resetText').style.display = 'inline-flex';
                        document.getElementById('resetSpinner').style.display = 'none';
                        document.getElementById('resetBtn').disabled = false;
                        
                        const email = document.getElementById('email');
                        if (email) {
                            email.classList.remove('is-invalid');
                        }
                    }
                });

                document.addEventListener('DOMContentLoaded', function() {
                    const emailField = document.getElementById('email');
                    if (emailField && !emailField.value) {
                        emailField.focus();
                    }
                });
            </script>
        </body>
        </html>
        <?php
    }
}

// =========================================================
// 8. Add the view instantiation at the bottom
// =========================================================
$view = new ForgotPasswordView();
$view->render(get_defined_vars());
?>