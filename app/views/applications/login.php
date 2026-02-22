<?php
/**
 * Applicant Login View
 * Redesigned to match portal navy/gold design system.
 *
 * @package FCTCNS
 */

// =========================================================
// 1. Add the trait at the top of each view file
// =========================================================
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
            
            <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600;700&family=DM+Sans:wght@300;400;500;600&display=swap" 
                  rel="stylesheet"
                  integrity="sha384-0pCryB3hBqYHZO9dKsIIzN8wH+Z4k5P+GZ8TlqM9m8A3TlPI9c7JZ6nG+K/t9fb"
                  crossorigin="anonymous">
            
            <link rel="stylesheet" 
                  href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"
                  integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw=="
                  crossorigin="anonymous" 
                  referrerpolicy="no-referrer">

            <style nonce="<?php echo $csp_nonce; ?>">
                /* ── Reset ─────────────────────────────────────────────── */
                *, *::before, *::after { margin: 0; padding: 0; box-sizing: border-box; }

                /* ── Tokens ─────────────────────────────────────────────── */
                :root {
                    --navy:        #0F1B35;
                    --navy-mid:    #1A2D55;
                    --navy-light:  #243E73;
                    --gold:        #C8963A;
                    --gold-light:  #E2B05F;
                    --gold-pale:   #FDF6E9;
                    --teal:        #1D8A7A;
                    --teal-light:  #E8F7F5;
                    --red:         #C0392B;
                    --red-light:   #FDEEEC;
                    --white:       #FFFFFF;
                    --off-white:   #F8FAFD;
                    --border:      #E2E8F4;
                    --border-dark: #C8D3E8;
                    --text-dark:   #0F1B35;
                    --text-body:   #374160;
                    --text-muted:  #7A86A0;
                }

                /* ── Body / Page ─────────────────────────────────────────── */
                html, body { height: 100%; }

                body {
                    font-family: 'DM Sans', -apple-system, sans-serif;
                    background: var(--navy);
                    min-height: 100vh;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    padding: 24px 16px;
                    position: relative;
                    overflow-x: hidden;
                }

                /* Decorative background rings */
                body::before,
                body::after {
                    content: '';
                    position: fixed;
                    border-radius: 50%;
                    pointer-events: none;
                }
                body::before {
                    width: 600px; height: 600px;
                    border: 1px solid rgba(200,150,58,0.08);
                    top: -200px; right: -150px;
                }
                body::after {
                    width: 400px; height: 400px;
                    border: 1px solid rgba(200,150,58,0.06);
                    bottom: -150px; left: -100px;
                }

                .login-wrap {
                    width: 100%;
                    max-width: 440px;
                    margin: 0 auto;
                    position: relative;
                    z-index: 1;
                    animation: rise 0.45s cubic-bezier(0.22,0.61,0.36,1) both;
                }

                @keyframes rise {
                    from { opacity: 0; transform: translateY(20px); }
                    to   { opacity: 1; transform: translateY(0); }
                }

                /* ── Card ────────────────────────────────────────────────── */
                .login-card {
                    background: var(--white);
                    border-radius: 20px;
                    overflow: hidden;
                    box-shadow: 0 32px 80px rgba(0,0,0,0.4), 0 0 0 1px rgba(255,255,255,0.05);
                }

                /* ── Card Header ─────────────────────────────────────────── */
                .card-head {
                    background: var(--navy-mid);
                    padding: 32px 36px 28px;
                    text-align: center;
                    position: relative;
                    overflow: hidden;
                }

                /* Gold top line */
                .card-head::before {
                    content: '';
                    position: absolute;
                    top: 0; left: 0; right: 0;
                    height: 3px;
                    background: linear-gradient(90deg, var(--gold), var(--gold-light), var(--gold));
                }

                /* Background texture */
                .card-head::after {
                    content: '';
                    position: absolute;
                    inset: 0;
                    background-image: repeating-linear-gradient(
                        45deg, transparent, transparent 40px,
                        rgba(255,255,255,0.012) 40px, rgba(255,255,255,0.012) 41px
                    );
                    pointer-events: none;
                }

                .card-head-emblem {
                    position: relative;
                    z-index: 1;
                    width: 56px; height: 56px;
                    background: rgba(200,150,58,0.12);
                    border: 1.5px solid rgba(200,150,58,0.35);
                    border-radius: 50%;
                    display: flex; align-items: center; justify-content: center;
                    margin: 0 auto 14px;
                    color: var(--gold-light);
                    font-size: 1.3rem;
                }

                .card-head h1 {
                    position: relative;
                    z-index: 1;
                    font-family: 'Playfair Display', Georgia, serif;
                    font-size: 1.15rem;
                    font-weight: 700;
                    color: #fff;
                    line-height: 1.3;
                    margin-bottom: 6px;
                }

                .card-head p {
                    position: relative;
                    z-index: 1;
                    font-size: 11.5px;
                    color: rgba(255,255,255,0.45);
                    text-transform: uppercase;
                    letter-spacing: .5px;
                    margin: 0;
                }

                .card-head-rule {
                    position: relative;
                    z-index: 1;
                    width: 36px;
                    height: 2px;
                    background: var(--gold);
                    border-radius: 2px;
                    margin: 12px auto 0;
                }

                /* ── Card Body ───────────────────────────────────────────── */
                .card-body {
                    padding: 32px 36px 28px;
                }

                /* ── Alerts ──────────────────────────────────────────────── */
                .alert {
                    display: flex;
                    align-items: flex-start;
                    gap: 10px;
                    padding: 12px 14px;
                    border-radius: 10px;
                    margin-bottom: 20px;
                    font-size: 13.5px;
                    border: 1px solid transparent;
                    animation: popIn .3s ease;
                }

                @keyframes popIn {
                    from { opacity: 0; transform: translateY(-6px); }
                    to   { opacity: 1; transform: translateY(0); }
                }

                .alert-danger  { background: var(--red-light);  border-color: rgba(192,57,43,.2);  color: #7f1d1d; }
                .alert-success { background: var(--teal-light); border-color: rgba(29,138,122,.2); color: #134e42; }
                .alert-info    { background: #EFF4FF;           border-color: rgba(37,99,235,.15); color: #1e3a8a; }

                .alert i { font-size: .95rem; flex-shrink: 0; margin-top: 1px; }
                .alert-danger  i { color: var(--red); }
                .alert-success i { color: var(--teal); }
                .alert-info    i { color: #2563EB; }

                .alert-close {
                    margin-left: auto;
                    background: none;
                    border: none;
                    cursor: pointer;
                    color: inherit;
                    opacity: .45;
                    font-size: 1rem;
                    line-height: 1;
                    padding: 0;
                    flex-shrink: 0;
                }
                .alert-close:hover { opacity: .9; }

                /* ── Form groups ─────────────────────────────────────────── */
                .form-group { margin-bottom: 18px; }

                .form-label {
                    display: block;
                    font-size: 12.5px;
                    font-weight: 600;
                    color: var(--text-dark);
                    margin-bottom: 6px;
                    letter-spacing: .2px;
                }

                .form-label .req { color: var(--red); margin-left: 2px; }

                .input-wrap {
                    position: relative;
                    display: flex;
                    align-items: stretch;
                }

                .input-wrap .input-icon {
                    position: absolute;
                    left: 13px;
                    top: 50%;
                    transform: translateY(-50%);
                    color: var(--text-muted);
                    font-size: .85rem;
                    pointer-events: none;
                }

                .form-control {
                    width: 100%;
                    padding: 11px 14px 11px 36px;
                    border: 1.5px solid var(--border-dark);
                    border-radius: 10px;
                    font-size: 13.5px;
                    font-family: 'DM Sans', sans-serif;
                    color: var(--text-dark);
                    background: var(--white);
                    transition: border-color .2s, box-shadow .2s;
                }

                .form-control::placeholder { color: var(--text-muted); font-size: 13px; }

                .form-control:focus {
                    border-color: var(--navy-mid);
                    box-shadow: 0 0 0 3px rgba(26,45,85,.1);
                    outline: none;
                }

                .form-control.is-invalid {
                    border-color: var(--red);
                    background: #fff8f8;
                }

                /* Password input — room for toggle button */
                .form-control.has-toggle { padding-right: 44px; }

                .toggle-btn {
                    position: absolute;
                    right: 0;
                    top: 0; bottom: 0;
                    width: 42px;
                    background: none;
                    border: none;
                    color: var(--text-muted);
                    cursor: pointer;
                    display: flex; align-items: center; justify-content: center;
                    font-size: .85rem;
                    transition: color .2s;
                }

                .toggle-btn:hover { color: var(--navy); }

                .invalid-msg {
                    font-size: 11.5px;
                    color: var(--red);
                    margin-top: 4px;
                    display: none;
                }

                .form-control.is-invalid ~ .invalid-msg,
                .is-invalid ~ .invalid-msg { display: block; }

                .form-hint {
                    font-size: 11px;
                    color: var(--text-muted);
                    margin-top: 5px;
                    display: flex;
                    align-items: center;
                    gap: 4px;
                }

                /* ── Login button ────────────────────────────────────────── */
                .btn-login {
                    width: 100%;
                    padding: 12px;
                    background: var(--navy);
                    color: #fff;
                    border: none;
                    border-radius: 10px;
                    font-family: 'DM Sans', sans-serif;
                    font-size: .95rem;
                    font-weight: 600;
                    cursor: pointer;
                    display: flex; align-items: center; justify-content: center; gap: 8px;
                    transition: all .25s;
                    box-shadow: 0 4px 14px rgba(15,27,53,.25);
                    margin-top: 6px;
                    letter-spacing: .2px;
                }

                .btn-login:hover:not(:disabled) {
                    background: var(--navy-light);
                    transform: translateY(-1px);
                    box-shadow: 0 8px 22px rgba(15,27,53,.3);
                }

                .btn-login:disabled {
                    opacity: .6;
                    cursor: not-allowed;
                    transform: none;
                }

                /* ── Spinner ─────────────────────────────────────────────── */
                .spinner {
                    display: inline-block;
                    width: 14px; height: 14px;
                    border: 2px solid rgba(255,255,255,.35);
                    border-top-color: #fff;
                    border-radius: 50%;
                    animation: spin .7s linear infinite;
                }

                @keyframes spin { to { transform: rotate(360deg); } }

                /* ── Divider ─────────────────────────────────────────────── */
                .divider {
                    display: flex;
                    align-items: center;
                    gap: 12px;
                    margin: 22px 0 18px;
                    color: var(--text-muted);
                    font-size: 12px;
                }

                .divider::before,
                .divider::after {
                    content: '';
                    flex: 1;
                    height: 1px;
                    background: var(--border);
                }

                /* ── Register link ───────────────────────────────────────── */
                .register-block {
                    text-align: center;
                }

                .register-block p {
                    font-size: 12.5px;
                    color: var(--text-muted);
                    margin-bottom: 10px;
                }

                .btn-register {
                    display: inline-flex;
                    align-items: center;
                    gap: 7px;
                    width: 100%;
                    padding: 11px;
                    background: transparent;
                    color: var(--navy);
                    border: 1.5px solid var(--border-dark);
                    border-radius: 10px;
                    font-family: 'DM Sans', sans-serif;
                    font-size: .88rem;
                    font-weight: 600;
                    text-decoration: none;
                    cursor: pointer;
                    justify-content: center;
                    transition: all .2s;
                }

                .btn-register:hover {
                    background: var(--off-white);
                    border-color: var(--navy);
                    color: var(--navy);
                }

                /* ── Forgot password ─────────────────────────────────────── */
                .forgot-wrap {
                    text-align: center;
                    margin-top: 16px;
                }

                .forgot-wrap a {
                    font-size: 12.5px;
                    color: var(--text-muted);
                    text-decoration: none;
                    transition: color .2s;
                }

                .forgot-wrap a:hover { color: var(--navy); }

                /* ── Return to Home ──────────────────────────────────────── */
                .page-foot {
                    margin-top: 16px;
                    text-align: center;
                }

                .page-foot a {
                    display: inline-flex;
                    align-items: center;
                    gap: 6px;
                    font-size: 12.5px;
                    font-weight: 600;
                    color: var(--gold);
                    text-decoration: none;
                    transition: color .2s;
                }

                .page-foot a:hover { color: var(--gold-light); }

                /* ── Responsive ──────────────────────────────────────────── */
                @media (max-width: 480px) {
                    .card-body { padding: 24px 22px 22px; }
                    .card-head { padding: 26px 22px 22px; }
                    .card-head h1 { font-size: 1rem; }
                }
            </style>
        </head>
        <body>

            <div class="login-wrap">

                <!-- Card -->
                <div class="login-card">

                    <!-- Header -->
                    <div class="card-head">
                        <div class="card-head-emblem">
                            <i class="fas fa-star-of-life"></i>
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
                                    Login Identifier <span class="req">*</span>
                                </label>
                                <div class="input-wrap">
                                    <i class="fas fa-user input-icon"></i>
                                    <input type="text"
                                           class="form-control <?php echo !empty($_SESSION['login_error']) ? 'is-invalid' : ''; ?>"
                                           id="login"
                                           name="login"
                                           value="<?php echo $this->e($_SESSION['login_value'] ?? ''); ?>"
                                           placeholder="Email, phone number, or JAMB number"
                                           autocomplete="username"
                                           required>
                                </div>
                                <?php if (!empty($_SESSION['login_error'])): ?>
                                <div class="invalid-msg" style="display:block">
                                    <?php echo $this->e($_SESSION['login_error']); ?>
                                </div>
                                <?php endif; ?>
                                <div class="form-hint">
                                    <i class="fas fa-info-circle" style="color:var(--gold);font-size:.75rem"></i>
                                    You can login with your email, phone number, or JAMB registration number
                                </div>
                            </div>

                            <!-- Password -->
                            <div class="form-group">
                                <label for="password" class="form-label">
                                    Password <span class="req">*</span>
                                </label>
                                <div class="input-wrap">
                                    <i class="fas fa-lock input-icon"></i>
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
                                <i class="fas fa-key" style="font-size:.75rem;margin-right:4px"></i>
                                Forgot your password?
                            </a>
                        </div>

                    </div><!-- /card-body -->
                </div><!-- /login-card -->

                <!-- Return to Home -->
                <div class="page-foot">
                    <a href="/">
                        <i class="fas fa-arrow-left" style="font-size:.7rem"></i> Return to Home
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
                function getCsrfToken() {
                    const meta = document.querySelector('meta[name="csrf-token"]');
                    return meta ? meta.getAttribute('content') : '';
                }

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
                    return input.replace(/[<>]/g, '');
                }

                // Login form submission with enhanced validation
                document.getElementById('loginForm').addEventListener('submit', function (e) {
                    const loginInput = document.getElementById('login');
                    const passInput  = document.getElementById('password');
                    
                    // Sanitize login input
                    if (loginInput) {
                        loginInput.value = sanitizeInput(loginInput.value.trim());
                    }
                    
                    const loginVal = loginInput ? loginInput.value : '';
                    const passVal  = passInput ? passInput.value : '';
                    
                    let valid = true;

                    // Reset validation states
                    if (loginInput) loginInput.classList.remove('is-invalid');
                    if (passInput) passInput.classList.remove('is-invalid');

                    // Validate login identifier
                    if (!loginVal) {
                        if (loginInput) loginInput.classList.add('is-invalid');
                        valid = false;
                        
                        // Create or update error message
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
                        
                        // Create or update error message
                        let errorMsg = passInput?.parentNode?.parentNode?.querySelector('.invalid-msg');
                        if (errorMsg) {
                            errorMsg.style.display = 'block';
                            errorMsg.textContent = 'Password is required';
                        }
                    }

                    // Prevent rate limiting by adding timestamp
                    if (valid) {
                        // Add timestamp to prevent caching
                        const timestamp = document.createElement('input');
                        timestamp.type = 'hidden';
                        timestamp.name = '_t';
                        timestamp.value = Date.now();
                        this.appendChild(timestamp);

                        // Loading state
                        document.getElementById('loginText').style.display   = 'none';
                        document.getElementById('loginSpinner').style.display = 'inline-flex';
                        document.getElementById('loginBtn').disabled          = true;
                        
                        // Add security headers check
                        const csrfToken = getCsrfToken();
                        if (!csrfToken) {
                            console.warn('CSRF token not found');
                        }
                    } else {
                        e.preventDefault();
                        
                        // Show error toast
                        showToast('Please fill in all required fields', 'error');
                    }
                });

                // Auto-dismiss alerts after 5.5 seconds with animation
                function dismissAlerts() {
                    document.querySelectorAll('.alert').forEach(function (el) {
                        el.style.transition = 'opacity .4s, transform .4s';
                        el.style.opacity    = '0';
                        el.style.transform  = 'translateY(-10px)';
                        
                        setTimeout(function () { 
                            if (el.parentNode) el.remove(); 
                        }, 400);
                    });
                }

                // Toast notification system
                function showToast(msg, type = 'success') {
                    // Remove existing toasts
                    document.querySelectorAll('.toast-notification').forEach(t => t.remove());
                    
                    // Create toast element
                    const toast = document.createElement('div');
                    toast.className = 'toast-notification';
                    toast.setAttribute('role', 'alert');
                    
                    // Style toast
                    toast.style.cssText = `
                        position: fixed;
                        top: 20px;
                        right: 20px;
                        background: ${type === 'success' ? 'var(--teal)' : 'var(--red)'};
                        color: white;
                        padding: 12px 20px;
                        border-radius: 8px;
                        font-size: 14px;
                        font-weight: 500;
                        display: flex;
                        align-items: center;
                        gap: 10px;
                        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
                        z-index: 9999;
                        animation: slideIn 0.3s ease;
                    `;
                    
                    const icon = type === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle';
                    toast.innerHTML = `<i class="fas ${icon}"></i> ${msg}`;
                    
                    document.body.appendChild(toast);
                    
                    // Auto remove after 3 seconds
                    setTimeout(() => {
                        toast.style.transition = 'opacity 0.3s, transform 0.3s';
                        toast.style.opacity = '0';
                        toast.style.transform = 'translateX(100%)';
                        setTimeout(() => toast.remove(), 300);
                    }, 3000);
                }

                // Add slide-in animation
                const style = document.createElement('style');
                style.textContent = `
                    @keyframes slideIn {
                        from {
                            opacity: 0;
                            transform: translateX(100%);
                        }
                        to {
                            opacity: 1;
                            transform: translateX(0);
                        }
                    }
                `;
                document.head.appendChild(style);

                // Prevent multiple rapid form submissions
                let isSubmitting = false;
                document.getElementById('loginForm').addEventListener('submit', function(e) {
                    if (isSubmitting) {
                        e.preventDefault();
                        showToast('Please wait...', 'info');
                        return false;
                    }
                    isSubmitting = true;
                    
                    // Reset after 5 seconds (in case of network issues)
                    setTimeout(() => {
                        isSubmitting = false;
                    }, 5000);
                });

                // Add rate limiting detection
                let loginAttempts = 0;
                const maxAttempts = 5;
                const lockoutTime = 15 * 60 * 1000; // 15 minutes
                
                function checkRateLimit() {
                    const attempts = parseInt(sessionStorage.getItem('loginAttempts') || '0');
                    const lockUntil = parseInt(sessionStorage.getItem('lockUntil') || '0');
                    
                    if (Date.now() < lockUntil) {
                        showToast('Too many attempts. Please try again later.', 'error');
                        return false;
                    }
                    
                    if (attempts >= maxAttempts) {
                        sessionStorage.setItem('lockUntil', Date.now() + lockoutTime);
                        showToast('Too many failed attempts. Locked for 15 minutes.', 'error');
                        return false;
                    }
                    
                    return true;
                }

                // Increment attempt counter
                document.getElementById('loginForm').addEventListener('submit', function(e) {
                    if (!checkRateLimit()) {
                        e.preventDefault();
                        return;
                    }
                });

                // Clean up session markers
                <?php
                    unset($_SESSION['login_error']);
                    unset($_SESSION['password_error']);
                    unset($_SESSION['login_value']);
                ?>

                // Execute on page load
                document.addEventListener('DOMContentLoaded', function() {
                    // Auto-dismiss alerts
                    setTimeout(dismissAlerts, 5500);
                    
                    // Focus on login field if empty
                    const loginField = document.getElementById('login');
                    if (loginField && !loginField.value) {
                        loginField.focus();
                    }
                    
                    // Add password strength indicator (optional)
                    const passwordField = document.getElementById('password');
                    if (passwordField) {
                        passwordField.addEventListener('input', function() {
                            // You can add password strength checking here if needed
                        });
                    }
                    
                    // Prevent right-click on sensitive fields
                    ['login', 'password'].forEach(id => {
                        const field = document.getElementById(id);
                        if (field) {
                            field.addEventListener('contextmenu', e => e.preventDefault());
                        }
                    });
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