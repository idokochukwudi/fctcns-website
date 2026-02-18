<?php
/**
 * JAMB Verification View - Step 1
 * ENHANCED: Fixed font colors, removed duplicate footer, updated placeholder
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
$settings = $settings ?? [];
$portal_closed = $portal_closed ?? false;
$portal_message = $portal_message ?? '';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0, viewport-fit=cover">
    <meta name="description" content="JAMB Verification - FCT College of Nursing Sciences">
    <title>JAMB Verification - FCT College of Nursing Sciences</title>
    
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
            color: #1A1F2E;
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
        .verification-container {
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
           HEADER - FIXED FONT COLORS
           ========================================================================== */
        .header {
            text-align: center;
            margin-bottom: 30px;
        }

        .header h1 {
            font-family: 'Cormorant Garamond', serif;
            font-size: clamp(28px, 5vw, 42px);
            font-weight: 700;
            color: #FFFFFF !important; /* Force white */
            margin-bottom: 10px;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
            letter-spacing: -0.5px;
        }

        .header p {
            font-size: clamp(14px, 2vw, 16px);
            color: rgba(255,255,255,0.95) !important; /* Force white with opacity */
            font-weight: 400;
            text-shadow: 1px 1px 2px rgba(0,0,0,0.2);
        }

        /* ==========================================================================
           STEP INDICATOR - FIXED FONT COLORS
           ========================================================================== */
        .step-indicator {
            display: flex;
            justify-content: space-between;
            margin-bottom: 40px;
            position: relative;
            background: rgba(255,255,255,0.15);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border-radius: 50px;
            padding: 15px 20px;
            border: 1px solid rgba(255,255,255,0.2);
        }

        .step-indicator::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 60px;
            right: 60px;
            height: 2px;
            background: rgba(255,255,255,0.2);
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
            background: rgba(255,255,255,0.2);
            border: 2px solid rgba(255,255,255,0.3);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 8px;
            font-weight: 700;
            color: #FFFFFF !important; /* Force white */
            transition: all 0.3s;
            backdrop-filter: blur(5px);
        }

        .step.active .step-number {
            background: var(--primary);
            border-color: #FFFFFF;
            box-shadow: var(--shadow-primary);
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
            color: #FFFFFF !important; /* Force white */
            text-transform: uppercase;
            letter-spacing: 0.5px;
            text-shadow: 1px 1px 2px rgba(0,0,0,0.2);
        }

        .step.active .step-label {
            color: #FFD700 !important; /* Gold color for active */
            font-weight: 700;
        }

        @media (max-width: 768px) {
            .step-indicator {
                flex-wrap: wrap;
                gap: 10px;
                background: rgba(255,255,255,0.2);
                backdrop-filter: blur(10px);
                border: 1px solid rgba(255,255,255,0.2);
                padding: 15px;
            }
            
            .step-indicator::before {
                display: none;
            }
            
            .step {
                flex: 0 0 calc(50% - 5px);
                padding: 10px 5px;
                background: rgba(255,255,255,0.1);
                border-radius: 30px;
                backdrop-filter: blur(5px);
            }
            
            .step-label {
                font-size: 11px;
            }
        }

        /* ==========================================================================
           MAIN CARD
           ========================================================================== */
        .main-card {
            background: var(--white);
            border-radius: var(--radius-xl);
            box-shadow: var(--shadow-lg);
            overflow: hidden;
            transition: all 0.3s;
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

        @media (max-width: 768px) {
            .card-body {
                padding: 25px;
            }
        }

        /* ==========================================================================
           TERMS CARD
           ========================================================================== */
        .terms-card {
            background: var(--primary-soft);
            border-radius: var(--radius-lg);
            margin-bottom: 30px;
            border: 1px solid var(--primary-light);
            overflow: hidden;
        }

        .terms-header {
            background: linear-gradient(135deg, var(--gold) 0%, #B48C3A 100%);
            color: white;
            padding: 15px 20px;
        }

        .terms-header h5 {
            margin: 0;
            font-size: 16px;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 10px;
            color: #FFFFFF !important;
        }

        .terms-body {
            padding: 20px;
            max-height: 300px;
            overflow-y: auto;
            background: white;
        }

        .terms-body h6 {
            color: var(--primary);
            font-weight: 600;
            margin-bottom: 15px;
            font-size: 16px;
        }

        .terms-content {
            font-size: 14px;
            color: var(--text-dark);
            line-height: 1.7;
        }

        .terms-content ol, .terms-content ul {
            padding-left: 20px;
            margin-bottom: 15px;
        }

        .terms-content li {
            margin-bottom: 8px;
            color: var(--text-dark);
        }

        .terms-footer {
            background: #f8f9fa;
            padding: 12px 20px;
            border-top: 1px solid var(--border);
            font-size: 12px;
            color: var(--text-muted);
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

        .form-control-lg {
            font-size: 18px;
            letter-spacing: 1px;
        }

        .form-control::placeholder {
            color: #9CA3AF;
            font-size: 14px;
            letter-spacing: normal;
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

        /* ==========================================================================
           INFO ALERT
           ========================================================================== */
        .info-alert {
            background: var(--info-light);
            border-left: 4px solid var(--info);
            border-radius: var(--radius-md);
            padding: 20px;
            margin: 25px 0;
        }

        .info-alert i {
            color: var(--info);
            font-size: 18px;
        }

        .info-alert strong {
            color: var(--text-dark);
        }

        .info-alert ul {
            margin: 10px 0 0 20px;
            color: var(--text-dark);
            font-size: 14px;
        }

        .info-alert li {
            margin-bottom: 5px;
            color: var(--text-dark);
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
            width: 100%;
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

        .alert-success {
            background: var(--success-light);
            color: #065f46;
            border-left: 4px solid var(--success);
        }

        .alert-danger {
            background: var(--danger-light);
            color: #991b1b;
            border-left: 4px solid var(--danger);
        }

        .alert-warning {
            background: var(--warning-light);
            color: #92400e;
            border-left: 4px solid var(--warning);
        }

        .alert-info {
            background: var(--info-light);
            color: #1e40af;
            border-left: 4px solid var(--info);
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
           DIVIDER
           ========================================================================== */
        .divider {
            text-align: center;
            margin: 30px 0;
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
           FOOTER - SINGLE FOOTER (REMOVED DUPLICATE)
           ========================================================================== */
        .app-footer {
            text-align: center;
            margin-top: 30px;
            color: rgba(255,255,255,0.95) !important;
            font-size: 13px;
            padding: 20px;
            background: rgba(0,0,0,0.25);
            border-radius: var(--radius-lg);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border: 1px solid rgba(255,255,255,0.15);
        }

        .app-footer p {
            color: rgba(255,255,255,0.95) !important;
            margin-bottom: 5px;
        }

        .app-footer a {
            color: #FFFFFF !important;
            text-decoration: none;
            font-weight: 500;
            border-bottom: 1px dotted rgba(255,255,255,0.5);
        }

        .app-footer a:hover {
            border-bottom-color: #FFFFFF;
        }

        .app-footer i {
            margin: 0 5px;
            opacity: 0.9;
            color: rgba(255,255,255,0.9);
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
            
            .form-control-lg {
                font-size: 16px;
                padding: 12px;
            }
            
            .app-footer {
                padding: 15px;
                font-size: 12px;
            }
        }
    </style>
</head>
<body>
    <div class="verification-container">
        <!-- Header - Fixed colors -->
        <div class="header">
            <h1>FCT College of Nursing Sciences</h1>
            <p>2025/2026 Admissions Application Portal</p>
        </div>

        <!-- Step Indicator - Fixed colors -->
        <div class="step-indicator">
            <div class="step active">
                <div class="step-number">1</div>
                <div class="step-label">JAMB Verification</div>
            </div>
            <div class="step">
                <div class="step-number">2</div>
                <div class="step-label">Application Form</div>
            </div>
            <div class="step">
                <div class="step-number">3</div>
                <div class="step-label">Payment</div>
            </div>
            <div class="step">
                <div class="step-number">4</div>
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
            <!-- Main Card -->
            <div class="main-card">
                <div class="card-header">
                    <i class="fas fa-id-card"></i>
                    <h2>JAMB Verification</h2>
                    <p>Enter your JAMB registration number to begin your application</p>
                </div>
                
                <div class="card-body">
                    <?php if (empty($terms)): ?>
                        <div class="alert alert-warning">
                            <i class="fas fa-exclamation-triangle"></i>
                            Terms and conditions are not available at the moment. Please try again later.
                        </div>
                    <?php else: ?>
                    
                    <!-- Terms and Conditions Card -->
                    <div class="terms-card">
                        <div class="terms-header">
                            <h5><i class="fas fa-file-contract"></i> Terms and Conditions</h5>
                        </div>
                        <div class="terms-body">
                            <h6><?php echo e($terms['title'] ?? 'Terms and Conditions'); ?></h6>
                            <div class="terms-content">
                                <?php echo nl2br(e($terms['content'] ?? '')); ?>
                            </div>
                        </div>
                        <div class="terms-footer">
                            <i class="fas fa-clock me-1"></i>
                            Version: <?php echo e($terms['version'] ?? '1.0'); ?> | 
                            Effective: <?php echo isset($terms['effective_date']) ? date('jS F Y', strtotime($terms['effective_date'])) : '15th September 2025'; ?>
                        </div>
                    </div>

                    <!-- JAMB Verification Form -->
                    <form id="jambVerificationForm">
                        <input type="hidden" name="csrf_token" value="<?php echo e($csrf_token); ?>">
                        
                        <div class="mb-4">
                            <label for="jamb_number" class="form-label">
                                <i class="fas fa-id-card text-primary"></i>
                                JAMB Registration Number
                            </label>
                            <input type="text" 
                                   class="form-control form-control-lg" 
                                   id="jamb_number" 
                                   name="jamb_number" 
                                   placeholder="e.g., 202650000089FG"
                                   style="text-transform: uppercase;"
                                   autocomplete="off"
                                   required>
                            <div class="form-text">
                                <i class="fas fa-info-circle"></i> Enter the JAMB registration number you used for the 2025 UTME.
                            </div>
                        </div>
                        
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" id="accept_terms" name="accept_terms" required>
                            <label class="form-check-label" for="accept_terms">
                                I have read and agree to the 
                                <a href="#" data-bs-toggle="modal" data-bs-target="#termsModal">Terms and Conditions</a>
                            </label>
                        </div>
                        
                        <!-- Requirements Alert -->
                        <div class="info-alert">
                            <i class="fas fa-info-circle"></i>
                            <strong>By proceeding, you confirm that:</strong>
                            <ul class="mb-0">
                                <li>You have a minimum UTME score of <?php echo e($settings['key_value']['min_utme_score'] ?? '170'); ?></li>
                                <li>You selected FCT College of Nursing Sciences as your first choice</li>
                                <li>You have the required O'Level credits (5 credits including English, Maths, Biology, Chemistry, Physics)</li>
                                <li>You are at least <?php echo e($settings['key_value']['min_age'] ?? '16'); ?> years old</li>
                            </ul>
                        </div>
                        
                        <button type="submit" class="btn btn-primary btn-lg" id="verifyBtn">
                            <span id="btnText"><i class="fas fa-check-circle"></i> Verify JAMB Number</span>
                            <span id="btnSpinner" style="display: none;">
                                <span class="spinner-border" role="status"></span>
                                Verifying...
                            </span>
                        </button>
                    </form>
                    
                    <?php endif; ?>
                    
                    <div class="divider">
                        <span>OR</span>
                    </div>
                    
                    <!-- Login Link - Fixed to point to login page -->
                    <div class="text-center">
                        <p class="mb-2" style="color: var(--text-muted);">Already have an account?</p>
                        <a href="/applicant/login" class="btn btn-outline-primary">
                            <i class="fas fa-sign-in-alt"></i> Login to Continue Application
                        </a>
                    </div>
                </div>
            </div>
        <?php endif; ?>

        <!-- SINGLE FOOTER - Using app-footer class to avoid conflict with layout -->
        <div class="app-footer">
            <p>© <?php echo date('Y'); ?> FCT College of Nursing Sciences. All rights reserved.</p>
            <p>
                <i class="fas fa-phone-alt"></i> Support: 07039837749 | 
                <i class="fas fa-envelope"></i> Email: <a href="mailto:info@fctcns.edu.ng">info@fctcns.edu.ng</a>
            </p>
        </div>
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
    // Auto-format JAMB number - Convert to uppercase and remove special characters
    document.getElementById('jamb_number').addEventListener('input', function(e) {
        this.value = this.value.toUpperCase().replace(/[^0-9A-Z]/g, '');
    });

    // JAMB Verification Form Submission
    document.getElementById('jambVerificationForm').addEventListener('submit', async function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        const jambNumber = document.getElementById('jamb_number').value.trim().toUpperCase();
        const acceptTerms = document.getElementById('accept_terms').checked;
        
        // Validation
        if (!jambNumber) {
            showAlert('Please enter your JAMB number', 'danger');
            return;
        }
        
        if (!/^[0-9A-Z]{10,14}$/.test(jambNumber)) {
            showAlert('Invalid JAMB number format. It should be 10-14 characters of letters and numbers.', 'danger');
            return;
        }
        
        if (!acceptTerms) {
            showAlert('You must accept the terms and conditions', 'danger');
            return;
        }
        
        // Show loading state
        document.getElementById('btnText').style.display = 'none';
        document.getElementById('btnSpinner').style.display = 'inline-block';
        document.getElementById('verifyBtn').disabled = true;
        
        try {
            const response = await fetch('/apply/verify-jamb', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });
            
            const data = await response.json();
            console.log('Response:', data);
            
            if (data.success) {
                // Store JAMB data in sessionStorage
                sessionStorage.setItem('jamb_data', JSON.stringify(data.data));
                sessionStorage.setItem('jamb_verified', 'true');
                
                showAlert('JAMB verified successfully! Redirecting to application form...', 'success');
                
                // Redirect to application form
                setTimeout(() => {
                    window.location.href = '/apply/form';
                }, 1500);
            } else {
                showAlert(data.message || 'Verification failed. Please check your JAMB number and try again.', 'danger');
                resetButton();
            }
        } catch (error) {
            console.error('Error:', error);
            showAlert('Network error. Please check your connection and try again.', 'danger');
            resetButton();
        }
    });

    // Show alert function
    function showAlert(message, type) {
        const alertContainer = document.getElementById('alertContainer');
        const icon = type === 'success' ? 'fa-check-circle' : 
                     type === 'danger' ? 'fa-exclamation-circle' : 'fa-info-circle';
        
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

    // Reset button function
    function resetButton() {
        document.getElementById('btnText').style.display = 'inline-block';
        document.getElementById('btnSpinner').style.display = 'none';
        document.getElementById('verifyBtn').disabled = false;
    }

    // Check if already verified on page load
    document.addEventListener('DOMContentLoaded', function() {
        // Clear any stale session data if needed
        // This prevents unwanted redirects
        const jambVerified = sessionStorage.getItem('jamb_verified');
        const jambData = sessionStorage.getItem('jamb_data');
        
        // Only redirect if we have valid data and we're not coming from a fresh page load
        if (jambVerified === 'true' && jambData && document.referrer.includes('/apply/verify-jamb')) {
            showAlert('You already have verified JAMB data. Redirecting to application form...', 'info');
            setTimeout(() => {
                window.location.href = '/apply/form';
            }, 2000);
        }
    });

    // Remove any leftover session data if user manually navigates back
    window.addEventListener('pageshow', function(event) {
        if (event.persisted) {
            // Page was loaded from cache (back/forward navigation)
            sessionStorage.removeItem('jamb_verified');
            sessionStorage.removeItem('jamb_data');
        }
    });
    </script>
</body>
</html>