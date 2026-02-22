<?php
/**
 * Applicant Login View
 * Redesigned to match JAMB verification page design system.
 *
 * @package FCTCNS
 */

// =========================================================
// FIX: Add require for SecurityHelper and SecurityTrait
// =========================================================
require_once APP_PATH . '/helpers/SecurityHelper.php';
require_once APP_PATH . '/helpers/SecurityTrait.php';

class LoginView {
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
            
            <title>Login &mdash; FCT College of Nursing Sciences</title>

            <!-- CSRF Token for JavaScript -->
            <meta name="csrf-token" content="<?php echo $this->e($csrf_token); ?>">

            <!-- ========================================================= -->
            <!-- 3. Add CSP nonce to all style tags -->
            <!-- 7. Add SRI hashes to external scripts/styles -->
            <!-- ========================================================= -->
            <link rel="preconnect" href="https://fonts.googleapis.com">
            <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
            
            <!-- Google Fonts - Better font combination: Inter (modern sans-serif) and Playfair Display (elegant serif) -->
            <link href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&family=Playfair+Display:wght@400;500;600;700;800&display=swap" 
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
                    
                    /* Typography */
                    --font-sans: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
                    --font-serif: 'Playfair Display', Georgia, 'Times New Roman', serif;
                    
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

                .login-wrap {
                    width: 100%;
                    max-width: 440px;
                    margin: 40px auto 0; /* Added top margin to bring card down */
                    position: relative;
                    z-index: 1;
                    animation: fadeIn 0.5s ease-out;
                }

                @keyframes fadeIn {
                    from { opacity: 0; transform: translateY(10px); }
                    to   { opacity: 1; transform: translateY(0); }
                }

                /* ── Card (Matching JAMB page) ───────────────────────────── */
                .login-card {
                    background: #ffffff;
                    border-radius: var(--sv1-radius-xl);
                    overflow: hidden;
                    box-shadow: var(--shadow-xl);
                    border: 1px solid var(--sv1-border);
                }

                /* ── Card Header (Purple gradient like JAMB page) ───────── */
                .card-head {
                    background: linear-gradient(135deg, var(--sv1-primary) 0%, var(--sv1-primary-dark) 100%);
                    padding: 28px 36px 28px; /* Reduced top padding */
                    text-align: center;
                    position: relative;
                    overflow: visible; /* Changed to visible for logo */
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

                /* Logo container - positioned to sit on top */
                .logo-container {
                    position: relative;
                    z-index: 2;
                    margin-top: 75px; /* Push logo down instead of pulling up */
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

                .card-head-emblem {
                    position: relative;
                    z-index: 1;
                    width: 56px; height: 56px;
                    background: rgba(201,164,74,0.15);
                    border: 1.5px solid rgba(201,164,74,0.4);
                    border-radius: 50%;
                    display: flex; align-items: center; justify-content: center;
                    margin: 0 auto 14px;
                    color: var(--sv1-gold);
                    font-size: 1.5rem;
                }

                .card-head h1 {
                    position: relative;
                    z-index: 1;
                    font-family: var(--font-serif);
                    font-size: 1.35rem;
                    font-weight: 700;
                    color: #FFFFFF; /* Pure white for better contrast */
                    line-height: 1.4;
                    margin-bottom: 8px;
                    letter-spacing: -0.01em;
                    text-shadow: 0 2px 4px rgba(0,0,0,0.2); /* Added text shadow for depth */
                }

                .card-head p {
                    position: relative;
                    z-index: 1;
                    font-size: 12px;
                    font-weight: 400;
                    color: rgba(255,255,255,0.85); /* Lighter for better readability */
                    letter-spacing: 0.3px;
                    margin: 0;
                    text-transform: uppercase;
                    font-weight: 500;
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
                    padding: 24px 36px 28px; /* Reduced top padding */
                }

                /* ── Alerts (Matching JAMB page) ─────────────────────────── */
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

                /* ── Form groups ─────────────────────────────────────────── */
                .form-group { margin-bottom: 20px; }

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
                    align-items: stretch;
                }

                .input-wrap .input-icon {
                    position: absolute;
                    left: 14px;
                    top: 50%;
                    transform: translateY(-50%);
                    color: var(--sv1-text-muted);
                    font-size: 0.9rem;
                    pointer-events: none;
                    z-index: 1;
                }

                .form-control {
                    width: 100%;
                    padding: 12px 14px 12px 40px;
                    border: 2px solid var(--sv1-border);
                    border-radius: var(--sv1-radius-md);
                    font-size: 14px;
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
                    background-color: #fff8f8;
                }

                .form-control.is-invalid:focus {
                    box-shadow: 0 0 0 4px rgba(239,68,68,0.1);
                }

                /* Password input — room for toggle button */
                .form-control.has-toggle { padding-right: 44px; }

                .toggle-btn {
                    position: absolute;
                    right: 0;
                    top: 0; bottom: 0;
                    width: 44px;
                    background: none;
                    border: none;
                    color: var(--sv1-text-muted);
                    cursor: pointer;
                    display: flex; align-items: center; justify-content: center;
                    font-size: 0.95rem;
                    transition: color 0.2s;
                    z-index: 1;
                }

                .toggle-btn:hover { color: var(--sv1-primary); }

                .invalid-msg {
                    font-size: 12px;
                    color: var(--sv1-danger);
                    margin-top: 5px;
                    display: none;
                    padding-left: 4px;
                }

                .form-control.is-invalid ~ .invalid-msg,
                .is-invalid ~ .invalid-msg { display: block; }

                .form-hint {
                    font-size: 12px;
                    color: var(--sv1-text-muted);
                    margin-top: 6px;
                    display: flex;
                    align-items: center;
                    gap: 5px;
                }

                .form-hint i {
                    color: var(--sv1-primary-light);
                    font-size: 0.8rem;
                }

                /* ── Login button (Matching JAMB page) ───────────────────── */
                .btn-login {
                    width: 100%;
                    padding: 14px;
                    background: linear-gradient(135deg, var(--sv1-primary), var(--sv1-primary-dark));
                    color: #fff;
                    border: none;
                    border-radius: var(--sv1-radius-md);
                    font-family: var(--font-sans);
                    font-size: 16px;
                    font-weight: 600;
                    cursor: pointer;
                    display: flex; align-items: center; justify-content: center; gap: 8px;
                    transition: all 0.2s ease;
                    box-shadow: var(--sv1-shadow-primary);
                    margin-top: 8px;
                    letter-spacing: 0.3px;
                }

                .btn-login:hover:not(:disabled) {
                    transform: translateY(-2px);
                    box-shadow: 0 15px 35px rgba(107,78,155,0.4);
                }

                .btn-login:active:not(:disabled) {
                    transform: translateY(0);
                }

                .btn-login:disabled {
                    opacity: 0.65;
                    cursor: not-allowed;
                }

                /* ── Spinner (Matching JAMB page) ────────────────────────── */
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
                    margin: 24px 0 20px;
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

                /* ── Register link ───────────────────────────────────────── */
                .register-block {
                    text-align: center;
                }

                .register-block p {
                    font-size: 14px;
                    color: var(--sv1-text-muted);
                    margin-bottom: 12px;
                }

                .btn-register {
                    display: inline-flex;
                    align-items: center;
                    justify-content: center;
                    gap: 8px;
                    width: 100%;
                    padding: 12px;
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

                .btn-register:hover {
                    background: var(--sv1-primary);
                    color: #ffffff;
                    transform: translateY(-2px);
                }

                .btn-register:active {
                    transform: translateY(0);
                }

                /* ── Forgot password ─────────────────────────────────────── */
                .forgot-wrap {
                    text-align: center;
                    margin-top: 18px;
                }

                .forgot-wrap a {
                    font-size: 13px;
                    font-weight: 500;
                    color: var(--sv1-text-muted);
                    text-decoration: none;
                    transition: color 0.2s;
                    display: inline-flex;
                    align-items: center;
                    gap: 4px;
                }

                .forgot-wrap a:hover {
                    color: var(--sv1-primary);
                }

                .forgot-wrap a i {
                    font-size: 0.75rem;
                }

                /* ── Return to Home ──────────────────────────────────────── */
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
                    .login-wrap {
                        margin-top: 20px;
                    }
                    
                    .card-body { padding: 20px 22px 22px; }
                    .card-head { padding: 20px 22px 22px; }
                    
                    .logo-container {
                        margin-top: -40px;
                    }
                    
                    .college-logo {
                        width: 75px;
                        height: 75px;
                    }
                    
                    .card-head h1 { 
                        font-size: 1.2rem; 
                    }
                    
                    .card-head p { 
                        font-size: 11px; 
                    }
                    
                    .btn-login {
                        padding: 12px;
                        font-size: 15px;
                    }
                    
                    .btn-register {
                        padding: 11px;
                        font-size: 14px;
                    }
                }
            </style>
        </head>
        <body>

            <div class="login-wrap">

                <!-- Card -->
                <div class="login-card">

                    <!-- Header -->
                    <div class="card-head">
                        
                        <!-- Logo Container - Positioned to overlap -->
                        <div class="logo-container">
                            <img src="/assets/images/logo/logo.png" alt="FCT College of Nursing Sciences Logo" class="college-logo">
                        </div>
                        
                        <h1>FCT College of Nursing Sciences</h1>
                        <p>2025/2026 Admissions Application Portal</p>
                        <div class="card-head-rule"></div>
                    </div>

                    <!-- Body -->
                    <div class="card-body">

                        <!-- Flash messages -->
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

                        <?php if (!empty($_SESSION['flash_info'])): ?>
                        <div class="alert alert-info" role="alert">
                            <i class="fas fa-info-circle"></i>
                            <span><?php echo $this->e($_SESSION['flash_info']); ?></span>
                            <button class="alert-close" onclick="this.closest('.alert').remove()" aria-label="Close">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                        <?php unset($_SESSION['flash_info']); ?>
                        <?php endif; ?>

                        <!-- Login form -->
                        <form method="POST" action="/applicant/login" id="loginForm" novalidate>
                            
                            <!-- ========================================================= -->
                            <!-- 5. Add CSRF token to all forms -->
                            <!-- ========================================================= -->
                            <input type="hidden" name="csrf_token" value="<?php echo $this->e($csrf_token); ?>">

                            <!-- Login identifier -->
                            <div class="form-group">
                                <label for="login" class="form-label">
                                    <i class="fas fa-user"></i>
                                    Login Identifier <span class="req">*</span>
                                </label>
                                <div class="input-wrap">
                                    <i class="fas fa-id-card input-icon"></i>
                                    <input type="text"
                                           class="form-control <?php echo !empty($_SESSION['login_error']) ? 'is-invalid' : ''; ?>"
                                           id="login"
                                           name="login"
                                           value="<?php echo $this->e($_SESSION['login_value'] ?? ''); ?>"
                                           placeholder="Email, phone, or JAMB number"
                                           autocomplete="username"
                                           required>
                                </div>
                                <?php if (!empty($_SESSION['login_error'])): ?>
                                <div class="invalid-msg" style="display:block">
                                    <?php echo $this->e($_SESSION['login_error']); ?>
                                </div>
                                <?php endif; ?>
                                <div class="form-hint">
                                    <i class="fas fa-info-circle"></i>
                                    Use your email, phone number, or JAMB registration number
                                </div>
                            </div>

                            <!-- Password -->
                            <div class="form-group">
                                <label for="password" class="form-label">
                                    <i class="fas fa-lock"></i>
                                    Password <span class="req">*</span>
                                </label>
                                <div class="input-wrap">
                                    <i class="fas fa-key input-icon"></i>
                                    <input type="password"
                                           class="form-control has-toggle <?php echo !empty($_SESSION['password_error']) ? 'is-invalid' : ''; ?>"
                                           id="password"
                                           name="password"
                                           placeholder="Enter your password"
                                           autocomplete="current-password"
                                           required>
                                    <button type="button" class="toggle-btn" onclick="togglePassword()" aria-label="Toggle password visibility">
                                        <i class="fas fa-eye" id="toggleIcon"></i>
                                    </button>
                                </div>
                                <?php if (!empty($_SESSION['password_error'])): ?>
                                <div class="invalid-msg" style="display:block">
                                    <?php echo $this->e($_SESSION['password_error']); ?>
                                </div>
                                <?php endif; ?>
                            </div>

                            <!-- Submit -->
                            <button type="submit" class="btn-login" id="loginBtn">
                                <span id="loginText">
                                    <i class="fas fa-sign-in-alt"></i> Login to Portal
                                </span>
                                <span id="loginSpinner" style="display:none">
                                    <span class="spinner"></span> Logging in&hellip;
                                </span>
                            </button>

                        </form>

                        <!-- Register CTA -->
                        <div class="divider">OR</div>

                        <div class="register-block">
                            <p>Don't have an account yet?</p>
                            <a href="/apply/register" class="btn-register">
                                <i class="fas fa-user-plus"></i> Start New Application
                            </a>
                        </div>

                        <!-- Forgot password -->
                        <div class="forgot-wrap">
                            <a href="/applicant/forgot-password">
                                <i class="fas fa-key"></i>
                                Forgot your password?
                            </a>
                        </div>

                    </div><!-- /card-body -->
                </div><!-- /login-card -->

                <!-- Return to Home -->
                <div class="page-foot">
                    <a href="/">
                        <i class="fas fa-arrow-left"></i> Return to Home
                    </a>
                </div>

            </div><!-- /login-wrap -->

            <!-- ========================================================= -->
            <!-- 4. Add CSP nonce to all script tags -->
            <!-- ========================================================= -->
            <script nonce="<?php echo $csp_nonce; ?>">
                // ======================================================
                // Login Page JavaScript with Security Enhancements
                // ======================================================
                
                // Get CSRF token from meta tag
                const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';

                // Toggle password visibility
                function togglePassword() {
                    const pw   = document.getElementById('password');
                    const icon = document.getElementById('toggleIcon');
                    
                    if (!pw || !icon) return;
                    
                    if (pw.type === 'password') {
                        pw.type = 'text';
                        icon.classList.replace('fa-eye', 'fa-eye-slash');
                    } else {
                        pw.type = 'password';
                        icon.classList.replace('fa-eye-slash', 'fa-eye');
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
                    toast.className = 'toast-notification';
                    toast.setAttribute('role', 'alert');
                    
                    const icon = type === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle';
                    toast.innerHTML = `<i class="fas ${icon}"></i> ${sanitizeInput(msg)}`;
                    
                    document.body.appendChild(toast);
                    
                    // Auto remove after 3 seconds
                    setTimeout(() => {
                        toast.style.transition = 'opacity 0.3s, transform 0.3s';
                        toast.style.opacity = '0';
                        toast.style.transform = 'translateX(100%)';
                        setTimeout(() => toast.remove(), 300);
                    }, 3000);
                }

                // Rate limiting
                function checkRateLimit() {
                    const attempts = parseInt(sessionStorage.getItem('loginAttempts') || '0');
                    const lockUntil = parseInt(sessionStorage.getItem('lockUntil') || '0');
                    
                    if (Date.now() < lockUntil) {
                        showToast('Too many attempts. Please try again later.', 'error');
                        return false;
                    }
                    
                    if (attempts >= 5) {
                        sessionStorage.setItem('lockUntil', Date.now() + (15 * 60 * 1000)); // 15 minutes
                        showToast('Too many failed attempts. Locked for 15 minutes.', 'error');
                        return false;
                    }
                    
                    return true;
                }

                // Login form submission
                document.getElementById('loginForm')?.addEventListener('submit', function (e) {
                    if (!checkRateLimit()) {
                        e.preventDefault();
                        return;
                    }

                    const loginInput = document.getElementById('login');
                    const passInput  = document.getElementById('password');
                    
                    // Sanitize login input
                    if (loginInput) {
                        loginInput.value = sanitizeInput(loginInput.value);
                    }
                    
                    const loginVal = loginInput?.value || '';
                    const passVal  = passInput?.value || '';
                    
                    let valid = true;

                    // Reset validation states
                    if (loginInput) loginInput.classList.remove('is-invalid');
                    if (passInput) passInput.classList.remove('is-invalid');

                    // Validate login identifier
                    if (!loginVal) {
                        if (loginInput) loginInput.classList.add('is-invalid');
                        valid = false;
                        
                        let errorMsg = loginInput?.parentNode?.parentNode?.querySelector('.invalid-msg');
                        if (errorMsg) {
                            errorMsg.style.display = 'block';
                            errorMsg.textContent = 'Login identifier is required';
                        }
                    }

                    // Validate password
                    if (!passVal) {
                        if (passInput) passInput.classList.add('is-invalid');
                        valid = false;
                        
                        let errorMsg = passInput?.parentNode?.parentNode?.querySelector('.invalid-msg');
                        if (errorMsg) {
                            errorMsg.style.display = 'block';
                            errorMsg.textContent = 'Password is required';
                        }
                    }

                    if (valid) {
                        // Add timestamp to prevent caching
                        const timestamp = document.createElement('input');
                        timestamp.type = 'hidden';
                        timestamp.name = '_t';
                        timestamp.value = Date.now();
                        this.appendChild(timestamp);

                        // Increment attempt counter
                        const attempts = parseInt(sessionStorage.getItem('loginAttempts') || '0');
                        sessionStorage.setItem('loginAttempts', attempts + 1);

                        // Loading state
                        document.getElementById('loginText').style.display = 'none';
                        document.getElementById('loginSpinner').style.display = 'inline-flex';
                        document.getElementById('loginBtn').disabled = true;
                        
                        // Verify CSRF token exists
                        if (!csrfToken) {
                            console.warn('CSRF token not found');
                            showToast('Security token missing. Please refresh.', 'error');
                            e.preventDefault();
                            return;
                        }
                    } else {
                        e.preventDefault();
                        showToast('Please fill in all required fields', 'error');
                    }
                });

                // Auto-dismiss alerts
                function dismissAlerts() {
                    document.querySelectorAll('.alert').forEach(function (el) {
                        el.style.transition = 'opacity .4s, transform .4s';
                        el.style.opacity = '0';
                        el.style.transform = 'translateY(-10px)';
                        
                        setTimeout(function () { 
                            if (el.parentNode) el.remove(); 
                        }, 400);
                    });
                }

                // Prevent multiple rapid form submissions
                let isSubmitting = false;
                document.getElementById('loginForm')?.addEventListener('submit', function(e) {
                    if (isSubmitting) {
                        e.preventDefault();
                        showToast('Please wait...', 'info');
                        return false;
                    }
                    isSubmitting = true;
                    
                    setTimeout(() => {
                        isSubmitting = false;
                    }, 5000);
                });

                // Clean up session markers
                <?php
                    unset($_SESSION['login_error']);
                    unset($_SESSION['password_error']);
                    unset($_SESSION['login_value']);
                ?>

                // Execute on page load
                document.addEventListener('DOMContentLoaded', function() {
                    // Auto-dismiss alerts after 5.5 seconds
                    setTimeout(dismissAlerts, 5500);
                    
                    // Focus on login field if empty
                    const loginField = document.getElementById('login');
                    if (loginField && !loginField.value) {
                        loginField.focus();
                    }
                    
                    // Prevent right-click on sensitive fields
                    ['login', 'password'].forEach(id => {
                        const field = document.getElementById(id);
                        if (field) {
                            field.addEventListener('contextmenu', e => e.preventDefault());
                        }
                    });

                    // Clear any stale session data
                    const lockUntil = parseInt(sessionStorage.getItem('lockUntil') || '0');
                    if (lockUntil && Date.now() > lockUntil) {
                        sessionStorage.removeItem('lockUntil');
                        sessionStorage.removeItem('loginAttempts');
                    }
                });

                // Handle back button cache
                window.addEventListener('pageshow', function(event) {
                    if (event.persisted) {
                        window.location.reload();
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
$view = new LoginView();
$view->render(get_defined_vars());
?>