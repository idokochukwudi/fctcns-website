<?php
/**
 * Registration View - Step 1
 * ENHANCED: Removed page header, kept only application header
 * 
 * @package FCTCNS
 */

extract($data ?? []);

if (!function_exists('e')) {
    function e($text) {
        return htmlspecialchars($text ?? '', ENT_QUOTES, 'UTF-8');
    }
}

$csrf_token = $csrf_token ?? '';
$terms = $terms ?? [];
$portal_closed = $portal_closed ?? false;
$portal_message = $portal_message ?? '';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0, viewport-fit=cover">
    <meta name="description" content="Create Account - FCT College of Nursing Sciences">
    <title>Create Account - FCT College of Nursing Sciences</title>
    
    <!-- Premium Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@400;500;600;700&family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <style>
        /* ==========================================================================
           RESET & BASE STYLES
           ========================================================================== */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Outfit', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            margin: 0;
        }

        /* ==========================================================================
           DESIGN TOKENS
           ========================================================================== */
        :root {
            --primary: #6B4E9B;
            --primary-dark: #4A3B6B;
            --primary-light: #8A6FB0;
            --primary-soft: #F3EAF8;
            --gold: #C9A44A;
            --gold-light: #D8B86C;
            --success: #10b981;
            --success-light: #d1fae5;
            --danger: #ef4444;
            --danger-light: #fee2e2;
            --warning: #f59e0b;
            --warning-light: #fef3c7;
            --info: #3b82f6;
            --info-light: #dbeafe;
            --surface: #F7F9FC;
            --border: #E9EDF2;
            --white: #FFFFFF;
            --text-dark: #1A1F2E;
            --text-light: #FFFFFF;
            --text-muted: #6B7280;
            --shadow-sm: 0 4px 6px rgba(0,0,0,0.05);
            --shadow-md: 0 10px 25px rgba(0,0,0,0.1);
            --shadow-lg: 0 20px 40px rgba(0,0,0,0.15);
            --shadow-primary: 0 10px 30px rgba(107,78,155,0.3);
            --radius-sm: 8px;
            --radius-md: 12px;
            --radius-lg: 20px;
            --radius-xl: 30px;
        }

        /* ==========================================================================
           CONTAINER & LAYOUT
           ========================================================================== */
        .registration-container {
            max-width: 900px;
            width: 100%;
            margin: 0 auto;
            animation: fadeIn 0.5s ease;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* ==========================================================================
           PAGE HEADER - REMOVED
           ========================================================================== */
        /* No page-header styles - header removed */

        /* ==========================================================================
           STEP INDICATOR - IMPROVED VISIBILITY FOR ALL STEPS
           ========================================================================== */
        .step-indicator {
            display: flex;
            justify-content: space-between;
            margin-bottom: 40px;
            position: relative;
            background: rgba(0, 0, 0, 0.3);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border-radius: 50px;
            padding: 15px 20px;
            border: 1px solid rgba(255, 255, 255, 0.2);
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
        }

        .step-indicator::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 60px;
            right: 60px;
            height: 2px;
            background: rgba(255, 255, 255, 0.2);
            transform: translateY(-50%);
            z-index: 1;
        }

        .step {
            position: relative;
            z-index: 2;
            text-align: center;
            flex: 1;
            padding: 8px 0;
        }

        .step-number {
            width: 40px;
            height: 40px;
            background: rgba(255, 255, 255, 0.3);
            border: 2px solid rgba(255, 255, 255, 0.5);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 8px;
            font-weight: 700;
            font-size: 18px;
            color: #FFFFFF !important;
            transition: all 0.3s;
            backdrop-filter: blur(5px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.3);
        }

        .step.active .step-number {
            background: var(--primary);
            border-color: #FFFFFF;
            box-shadow: 0 0 15px rgba(107, 78, 155, 0.5);
            color: #FFFFFF !important;
        }

        .step.completed .step-number {
            background: var(--success);
            border-color: #FFFFFF;
            color: #FFFFFF !important;
        }

        .step-label {
            font-size: 13px;
            font-weight: 600;
            color: #FFFFFF !important;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            text-shadow: 2px 2px 3px rgba(0, 0, 0, 0.5);
        }

        .step.active .step-label {
            color: #FFD700 !important;
            font-weight: 700;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.5);
        }

        @media (max-width: 768px) {
            .step-indicator {
                flex-wrap: wrap;
                gap: 10px;
                background: rgba(0, 0, 0, 0.4);
                backdrop-filter: blur(10px);
                padding: 15px;
            }
            
            .step-indicator::before {
                display: none;
            }
            
            .step {
                flex: 0 0 calc(50% - 5px);
                padding: 10px 5px;
                background: rgba(255, 255, 255, 0.1);
                border-radius: 30px;
                backdrop-filter: blur(5px);
                border: 1px solid rgba(255, 255, 255, 0.1);
            }
            
            .step-label {
                font-size: 11px;
                color: #FFFFFF !important;
            }
        }

        /* ==========================================================================
           MAIN CARD - WITH APPLICATION HEADER
           ========================================================================== */
        .main-card {
            background: var(--white);
            border-radius: var(--radius-xl);
            box-shadow: var(--shadow-lg);
            overflow: hidden;
            transition: all 0.3s;
            margin-top: 20px;
        }

        .main-card:hover {
            box-shadow: var(--shadow-lg), var(--shadow-primary);
        }

        .card-header {
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            color: white;
            padding: 30px;
            text-align: center;
            border-bottom: none;
            position: relative;
            overflow: hidden;
        }

        .card-header::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
            opacity: 0.5;
        }

        .card-header .step-badge {
            display: inline-block;
            background: rgba(255,255,255,0.2);
            backdrop-filter: blur(5px);
            color: #FFD700 !important;
            font-weight: 600;
            font-size: 14px;
            padding: 8px 20px;
            border-radius: 50px;
            margin-bottom: 15px;
            border: 1px solid rgba(255,255,255,0.3);
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .card-header i {
            font-size: 3rem;
            margin-bottom: 15px;
            color: var(--gold);
        }

        .card-header h2 {
            font-size: 28px;
            font-weight: 700;
            margin-bottom: 10px;
            color: #FFFFFF !important;
        }

        .card-header p {
            font-size: 14px;
            opacity: 0.9;
            margin: 0;
            color: rgba(255,255,255,0.95) !important;
        }

        .card-body {
            padding: 40px;
        }

        .card-footer {
            background: var(--surface);
            padding: 15px 30px;
            border-top: 1px solid var(--border);
        }

        @media (max-width: 768px) {
            .card-body {
                padding: 25px;
            }
        }

        /* ==========================================================================
           FORM ELEMENTS
           ========================================================================== */
        .form-label {
            font-weight: 600;
            color: var(--primary-dark);
            margin-bottom: 8px;
            font-size: 14px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .form-control {
            border: 2px solid var(--border);
            border-radius: var(--radius-md);
            padding: 14px 16px;
            font-size: 15px;
            transition: all 0.3s;
            font-family: 'Outfit', sans-serif;
            color: var(--text-dark);
        }

        .form-control:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(107,78,155,0.15);
            outline: none;
        }

        .form-control.is-invalid {
            border-color: var(--danger);
            background-image: none;
        }

        .form-control::placeholder {
            color: #9CA3AF;
            font-size: 14px;
        }

        .form-text {
            font-size: 12px;
            color: var(--text-muted);
            margin-top: 5px;
        }

        .form-check {
            margin: 20px 0;
        }

        .form-check-input {
            width: 18px;
            height: 18px;
            margin-top: 2px;
            border: 2px solid var(--border);
        }

        .form-check-input:checked {
            background-color: var(--primary);
            border-color: var(--primary);
        }

        .form-check-label {
            font-size: 14px;
            color: var(--text-dark);
            margin-left: 5px;
        }

        .form-check-label a {
            color: var(--primary);
            text-decoration: none;
            font-weight: 500;
        }

        .form-check-label a:hover {
            text-decoration: underline;
        }

        .invalid-feedback {
            color: var(--danger);
            font-size: 12px;
            margin-top: 5px;
        }

        /* ==========================================================================
           BUTTONS
           ========================================================================== */
        .btn {
            padding: 14px 30px;
            border-radius: var(--radius-md);
            font-weight: 600;
            font-size: 16px;
            transition: all 0.3s;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            border: none;
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            color: white;
            box-shadow: var(--shadow-primary);
        }

        .btn-primary:hover:not(:disabled) {
            transform: translateY(-2px);
            box-shadow: 0 15px 35px rgba(107,78,155,0.4);
        }

        .btn-primary:disabled {
            opacity: 0.7;
            cursor: not-allowed;
        }

        .btn-outline-primary {
            background: transparent;
            border: 2px solid var(--primary);
            color: var(--primary);
        }

        .btn-outline-primary:hover {
            background: var(--primary);
            color: white;
            transform: translateY(-2px);
        }

        .btn-lg {
            padding: 16px;
            font-size: 16px;
        }

        .spinner-border {
            width: 18px;
            height: 18px;
            border-width: 2px;
            margin-right: 5px;
        }

        /* ==========================================================================
           ALERTS
           ========================================================================== */
        .alert {
            border-radius: var(--radius-md);
            padding: 15px 20px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
            border: none;
            animation: slideIn 0.3s ease;
        }

        @keyframes slideIn {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .alert-danger {
            background: var(--danger-light);
            color: #991b1b;
            border-left: 4px solid var(--danger);
        }

        .alert-success {
            background: var(--success-light);
            color: #065f46;
            border-left: 4px solid var(--success);
        }

        .alert .btn-close {
            margin-left: auto;
            background: none;
            border: none;
            font-size: 20px;
            cursor: pointer;
            opacity: 0.5;
            color: currentColor;
        }

        .alert .btn-close:hover {
            opacity: 1;
        }

        /* ==========================================================================
           PASSWORD STRENGTH INDICATOR
           ========================================================================== */
        .password-strength {
            margin-top: 10px;
            height: 4px;
            background: var(--border);
            border-radius: 2px;
            overflow: hidden;
        }

        .password-strength-bar {
            height: 100%;
            width: 0%;
            transition: all 0.3s;
            border-radius: 2px;
        }

        .strength-weak {
            background: var(--danger);
            width: 25%;
        }

        .strength-fair {
            background: var(--warning);
            width: 50%;
        }

        .strength-good {
            background: var(--info);
            width: 75%;
        }

        .strength-strong {
            background: var(--success);
            width: 100%;
        }

        .password-requirements {
            font-size: 12px;
            color: var(--text-muted);
            margin-top: 5px;
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
        }

        .requirement {
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .requirement i {
            font-size: 10px;
        }

        .requirement.met {
            color: var(--success);
        }

        .requirement.met i {
            color: var(--success);
        }

        /* ==========================================================================
           DIVIDER
           ========================================================================== */
        .divider {
            text-align: center;
            margin: 30px 0 20px;
            position: relative;
        }

        .divider::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 0;
            right: 0;
            height: 1px;
            background: var(--border);
            z-index: 1;
        }

        .divider span {
            background: var(--white);
            padding: 0 15px;
            color: var(--text-muted);
            font-size: 14px;
            position: relative;
            z-index: 2;
        }

        /* ==========================================================================
           PORTAL CLOSED
           ========================================================================== */
        .portal-closed {
            background: var(--white);
            border-radius: var(--radius-xl);
            padding: 40px;
            text-align: center;
        }

        .portal-closed i {
            font-size: 4rem;
            color: var(--warning);
            margin-bottom: 20px;
        }

        .portal-closed h2 {
            color: var(--primary);
            margin-bottom: 15px;
        }

        .portal-closed p {
            color: var(--text-dark);
            margin-bottom: 10px;
        }

        /* ==========================================================================
           RESPONSIVE UTILITIES
           ========================================================================== */
        @media (max-width: 576px) {
            body {
                padding: 10px;
            }
            
            .card-header {
                padding: 20px;
            }
            
            .card-header i {
                font-size: 2.5rem;
            }
            
            .card-header h2 {
                font-size: 22px;
            }
            
            .btn {
                padding: 12px 20px;
                font-size: 14px;
            }
            
            .password-requirements {
                flex-direction: column;
                gap: 5px;
            }
        }
    </style>
</head>
<body>
    <div class="registration-container">
        <!-- PAGE HEADER REMOVED - Only step indicator remains above card -->
        
        <!-- Step Indicator - All steps now clearly visible -->
        <div class="step-indicator">
            <div class="step active">
                <div class="step-number">1</div>
                <div class="step-label">Create Account</div>
            </div>
            <div class="step">
                <div class="step-number">2</div>
                <div class="step-label">JAMB Verification</div>
            </div>
            <div class="step">
                <div class="step-number">3</div>
                <div class="step-label">Application Form</div>
            </div>
            <div class="step">
                <div class="step-number">4</div>
                <div class="step-label">Payment</div>
            </div>
            <div class="step">
                <div class="step-number">5</div>
                <div class="step-label">Exam Slip</div>
            </div>
        </div>

        <!-- Alert Container -->
        <div id="alertContainer"></div>

        <?php if ($portal_closed): ?>
            <!-- Portal Closed Message -->
            <div class="portal-closed">
                <i class="fas fa-exclamation-triangle"></i>
                <h2>Application Portal Closed</h2>
                <p><?php echo e($portal_message); ?></p>
                <p class="text-muted mt-3">The next admissions cycle will be announced on this portal.</p>
            </div>
        <?php else: ?>
            <!-- Main Card - WITH APPLICATION HEADER -->
            <div class="main-card">
                <div class="card-header">
                    <div class="step-badge">
                        <i class="fas fa-arrow-right me-1"></i> Step 1 of 5
                    </div>
                    <i class="fas fa-user-plus"></i>
                    <h2>Create Account</h2>
                    <p>Start your application journey</p>
                </div>
                
                <div class="card-body">
                    <!-- Flash Messages -->
                    <?php if (isset($_SESSION['flash_error']) && !empty($_SESSION['flash_error'])): ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="fas fa-exclamation-circle"></i>
                            <?php echo e($_SESSION['flash_error']); ?>
                            <button type="button" class="btn-close" onclick="this.parentElement.remove()" aria-label="Close">×</button>
                        </div>
                        <?php unset($_SESSION['flash_error']); ?>
                    <?php endif; ?>
                    
                    <?php if (isset($_SESSION['flash_success']) && !empty($_SESSION['flash_success'])): ?>
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="fas fa-check-circle"></i>
                            <?php echo e($_SESSION['flash_success']); ?>
                            <button type="button" class="btn-close" onclick="this.parentElement.remove()" aria-label="Close">×</button>
                        </div>
                        <?php unset($_SESSION['flash_success']); ?>
                    <?php endif; ?>
                    
                    <!-- Registration Form -->
                    <form method="POST" action="/apply/register" id="registrationForm" novalidate>
                        <input type="hidden" name="csrf_token" value="<?php echo e($csrf_token); ?>">
                        
                        <div class="mb-3">
                            <label for="email" class="form-label">
                                <i class="fas fa-envelope text-primary"></i>
                                Email Address <span class="text-danger">*</span>
                            </label>
                            <input type="email" 
                                   class="form-control" 
                                   id="email" 
                                   name="email" 
                                   value="<?php echo e($_POST['email'] ?? ''); ?>" 
                                   placeholder="your@email.com"
                                   required>
                            <div class="form-text">
                                <i class="fas fa-info-circle"></i> We'll send a verification link to this email
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="phone" class="form-label">
                                <i class="fas fa-phone text-primary"></i>
                                Phone Number <span class="text-danger">*</span>
                            </label>
                            <input type="tel" 
                                   class="form-control" 
                                   id="phone" 
                                   name="phone" 
                                   value="<?php echo e($_POST['phone'] ?? ''); ?>" 
                                   placeholder="08012345678"
                                   pattern="[0-9]{11}"
                                   maxlength="11"
                                   required>
                            <div class="form-text">
                                <i class="fas fa-info-circle"></i> Enter 11-digit Nigerian mobile number
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="password" class="form-label">
                                <i class="fas fa-lock text-primary"></i>
                                Password <span class="text-danger">*</span>
                            </label>
                            <input type="password" 
                                   class="form-control" 
                                   id="password" 
                                   name="password" 
                                   minlength="8"
                                   required>
                            <div class="password-strength">
                                <div class="password-strength-bar" id="passwordStrengthBar"></div>
                            </div>
                            <div class="password-requirements" id="passwordRequirements">
                                <span class="requirement" id="req-length">
                                    <i class="fas fa-circle"></i> At least 8 characters
                                </span>
                                <span class="requirement" id="req-number">
                                    <i class="fas fa-circle"></i> At least 1 number
                                </span>
                                <span class="requirement" id="req-letter">
                                    <i class="fas fa-circle"></i> At least 1 letter
                                </span>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="confirm_password" class="form-label">
                                <i class="fas fa-lock text-primary"></i>
                                Confirm Password <span class="text-danger">*</span>
                            </label>
                            <input type="password" 
                                   class="form-control" 
                                   id="confirm_password" 
                                   name="confirm_password" 
                                   required>
                            <div class="invalid-feedback" id="passwordMatchError"></div>
                        </div>
                        
                        <div class="mb-4 form-check">
                            <input type="checkbox" class="form-check-input" id="terms" name="terms" required>
                            <label class="form-check-label" for="terms">
                                I agree to the <a href="#" data-bs-toggle="modal" data-bs-target="#termsModal">Terms and Conditions</a> <span class="text-danger">*</span>
                            </label>
                        </div>
                        
                        <button type="submit" class="btn btn-primary btn-lg w-100 mb-3" id="submitBtn">
                            <span id="btnText"><i class="fas fa-user-plus me-2"></i>Create Account</span>
                            <span id="btnSpinner" style="display: none;">
                                <span class="spinner-border" role="status"></span>
                                Creating Account...
                            </span>
                        </button>
                        
                        <div class="divider">
                            <span>OR</span>
                        </div>
                        
                        <div class="text-center">
                            <p class="mb-0" style="color: var(--text-muted);">Already have an account?</p>
                            <a href="/applicant/login" class="btn btn-outline-primary mt-2">
                                <i class="fas fa-sign-in-alt me-2"></i>Login Here
                            </a>
                        </div>
                    </form>
                </div>
                
                <div class="card-footer">
                    <div class="d-flex align-items-center justify-content-center">
                        <i class="fas fa-shield-alt text-muted me-2"></i>
                        <small class="text-muted">Your information is secure and encrypted</small>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <!-- Terms Modal -->
    <?php if (!empty($terms)): ?>
    <div class="modal fade" id="termsModal" tabindex="-1" aria-labelledby="termsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="termsModalLabel">
                        <i class="fas fa-file-contract me-2"></i>Terms and Conditions
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <h6><?php echo e($terms['title'] ?? 'Terms and Conditions'); ?></h6>
                    <div class="terms-content">
                        <?php echo nl2br(e($terms['content'] ?? '')); ?>
                    </div>
                    <hr>
                    <p class="text-muted small mb-0">
                        <i class="fas fa-clock me-1"></i>
                        Version: <?php echo e($terms['version'] ?? '1.0'); ?> | 
                        Effective: <?php echo isset($terms['effective_date']) ? date('jS F Y', strtotime($terms['effective_date'])) : '15th September 2025'; ?>
                    </p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Custom JavaScript -->
    <script>
    // Password strength checker
    const password = document.getElementById('password');
    const confirm = document.getElementById('confirm_password');
    const strengthBar = document.getElementById('passwordStrengthBar');
    
    // Requirements elements
    const reqLength = document.getElementById('req-length');
    const reqNumber = document.getElementById('req-number');
    const reqLetter = document.getElementById('req-letter');
    
    function checkPasswordStrength() {
        const val = password.value;
        let strength = 0;
        
        // Check length
        if (val.length >= 8) {
            strength++;
            reqLength.classList.add('met');
            reqLength.innerHTML = '<i class="fas fa-check-circle"></i> At least 8 characters';
        } else {
            reqLength.classList.remove('met');
            reqLength.innerHTML = '<i class="fas fa-circle"></i> At least 8 characters';
        }
        
        // Check for numbers
        if (/\d/.test(val)) {
            strength++;
            reqNumber.classList.add('met');
            reqNumber.innerHTML = '<i class="fas fa-check-circle"></i> At least 1 number';
        } else {
            reqNumber.classList.remove('met');
            reqNumber.innerHTML = '<i class="fas fa-circle"></i> At least 1 number';
        }
        
        // Check for letters
        if (/[a-zA-Z]/.test(val)) {
            strength++;
            reqLetter.classList.add('met');
            reqLetter.innerHTML = '<i class="fas fa-check-circle"></i> At least 1 letter';
        } else {
            reqLetter.classList.remove('met');
            reqLetter.innerHTML = '<i class="fas fa-circle"></i> At least 1 letter';
        }
        
        // Update strength bar
        strengthBar.className = 'password-strength-bar';
        if (strength === 0) {
            strengthBar.style.width = '0%';
        } else if (strength === 1) {
            strengthBar.classList.add('strength-weak');
        } else if (strength === 2) {
            strengthBar.classList.add('strength-fair');
        } else if (strength === 3) {
            strengthBar.classList.add('strength-good');
        }
    }
    
    password.addEventListener('input', checkPasswordStrength);
    
    // Form submission with validation
    document.getElementById('registrationForm').addEventListener('submit', function(e) {
        const passwordVal = password.value;
        const confirmVal = confirm.value;
        const email = document.getElementById('email').value.trim();
        const phone = document.getElementById('phone').value.trim();
        const terms = document.getElementById('terms').checked;
        
        let isValid = true;
        let errorMessage = '';
        
        // Validate email
        if (!email) {
            errorMessage = 'Please enter your email address.';
            isValid = false;
        } else if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
            errorMessage = 'Please enter a valid email address.';
            isValid = false;
        }
        
        // Validate phone
        if (isValid && !phone) {
            errorMessage = 'Please enter your phone number.';
            isValid = false;
        } else if (isValid && !/^[0-9]{11}$/.test(phone)) {
            errorMessage = 'Phone number must be 11 digits.';
            isValid = false;
        }
        
        // Validate password
        if (isValid && passwordVal.length < 8) {
            errorMessage = 'Password must be at least 8 characters long.';
            isValid = false;
        }
        
        if (isValid && passwordVal !== confirmVal) {
            errorMessage = 'Passwords do not match.';
            isValid = false;
        }
        
        // Validate terms
        if (isValid && !terms) {
            errorMessage = 'You must accept the terms and conditions.';
            isValid = false;
        }
        
        if (!isValid) {
            e.preventDefault();
            showAlert(errorMessage, 'danger');
            return false;
        }
        
        // Show loading state
        document.getElementById('btnText').style.display = 'none';
        document.getElementById('btnSpinner').style.display = 'inline-block';
        document.getElementById('submitBtn').disabled = true;
        
        return true;
    });
    
    // Show alert function
    function showAlert(message, type) {
        const alertContainer = document.getElementById('alertContainer');
        const icon = type === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle';
        
        alertContainer.innerHTML = `
            <div class="alert alert-${type} alert-dismissible fade show" role="alert">
                <i class="fas ${icon}"></i>
                <span>${message}</span>
                <button type="button" class="btn-close" onclick="this.parentElement.remove()" aria-label="Close">×</button>
            </div>
        `;
        
        // Auto dismiss after 5 seconds
        setTimeout(() => {
            const alert = alertContainer.querySelector('.alert');
            if (alert) {
                alert.style.transition = 'opacity 0.5s ease';
                alert.style.opacity = '0';
                setTimeout(() => alert.remove(), 500);
            }
        }, 5000);
    }
    
    // Auto-format phone number
    document.getElementById('phone').addEventListener('input', function(e) {
        this.value = this.value.replace(/[^0-9]/g, '').slice(0, 11);
    });
    </script>
</body>
</html>