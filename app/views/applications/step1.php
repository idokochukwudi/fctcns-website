<?php
/**
 * JAMB Verification View - Step 1
 * SECURITY FIXED: Using SecurityTrait for XSS protection, CSRF tokens, CSP nonce
 * 
 * @package FCTCNS
 */

// =========================================================
// FIX: Add require for SecurityHelper and SecurityTrait
// =========================================================
require_once APP_PATH . '/helpers/SecurityHelper.php';
require_once APP_PATH . '/helpers/SecurityTrait.php';

// Use the trait in a view helper class
class JambVerificationView {
    use SecurityTrait;
    
    public function render($data) {
        extract($data);
        
        // Security: Get CSP nonce and CSRF token
        $csp_nonce = $this->getCspNonce();
        $csrf_token = $this->getCsrfToken();
        
        $terms = $terms ?? [];
        $settings = $settings ?? [];
        $portal_closed = $portal_closed ?? false;
        $portal_message = $portal_message ?? '';
        
        // Security: Secure JSON for JavaScript
        $secureTermsData = $this->secureJsonEncode($terms);
        
        // Get current step from application if available
        $currentStep = 1;
        if (isset($application) && !empty($application['application_step'])) {
            $currentStep = (int)$application['application_step'];
            
            // If application_step is 4 AND exam slip exists, show step 5
            if ($currentStep == 4 && isset($has_exam_slip) && $has_exam_slip) {
                $currentStep = 5;
            }
        }
        
        // Define steps
        $steps = [
            1 => ['label' => 'Create Account', 'sub' => 'Register'],
            2 => ['label' => 'JAMB Verification', 'sub' => 'JAMB check'],
            3 => ['label' => 'Application Form', 'sub' => 'Fill form'],
            4 => ['label' => 'Payment', 'sub' => 'Remita RRR'],
            5 => ['label' => 'Exam Slip', 'sub' => 'Download'],
        ];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0, viewport-fit=cover">
    <meta name="description" content="JAMB Verification - FCT College of Nursing Sciences">
    
    <!-- ===== SECURITY HEADERS ===== -->
    <?php echo $this->getSecurityMetaTags(); ?>
    
    <!-- CSRF Token for JavaScript -->
    <meta name="csrf-token" content="<?php echo $this->e($csrf_token); ?>">
    
    <title>JAMB Verification - FCT College of Nursing Sciences</title>
    
    <!-- Premium Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Playfair+Display:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Font Awesome with SRI -->
    <link rel="stylesheet" 
          href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"
          integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw=="
          crossorigin="anonymous" 
          referrerpolicy="no-referrer">
    
    <!-- Bootstrap 5 with SRI -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" 
          rel="stylesheet"
          integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" 
          crossorigin="anonymous">
    
    <style nonce="<?php echo $csp_nonce; ?>">
        /* ==========================================================================
           RESET & BASE STYLES
           ========================================================================== */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
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
           HEADER
           ========================================================================== */
        .header {
            text-align: center;
            margin-bottom: 30px;
        }

        .header h1 {
            font-family: 'Playfair Display', serif;
            font-size: clamp(28px, 5vw, 42px);
            font-weight: 700;
            color: #FFFFFF !important;
            margin-bottom: 10px;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.5), 0 0 10px rgba(0,0,0,0.3);
            letter-spacing: -0.5px;
        }

        .header p {
            font-size: clamp(14px, 2vw, 16px);
            color: #FFFFFF !important;
            font-weight: 500;
            text-shadow: 1px 1px 3px rgba(0,0,0,0.5);
            opacity: 1;
            background: rgba(0,0,0,0.2);
            display: inline-block;
            padding: 8px 20px;
            border-radius: 50px;
            backdrop-filter: blur(5px);
        }

        /* ==========================================================================
           STEP INDICATOR
           ========================================================================== */
        .step-indicator {
            display: flex;
            justify-content: space-between;
            margin-bottom: 40px;
            position: relative;
            background: rgba(0, 0, 0, 0.4);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border-radius: 50px;
            padding: 20px 25px;
            border: 1px solid rgba(255, 255, 255, 0.3);
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.3);
        }

        .step-indicator::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 70px;
            right: 70px;
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
            padding: 5px 0;
        }

        .step-number {
            width: 40px;
            height: 40px;
            background: rgba(255, 255, 255, 0.2);
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
            border-color: #FFD700;
            box-shadow: 0 0 20px rgba(107, 78, 155, 0.6);
            color: #FFFFFF !important;
            transform: scale(1.1);
        }

        .step.completed .step-number {
            background: var(--success);
            border-color: #FFFFFF;
            color: #FFFFFF !important;
        }

        .step-label {
            font-size: 12px;
            font-weight: 600;
            color: #FFFFFF !important;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            text-shadow: 2px 2px 3px rgba(0, 0, 0, 0.6);
            white-space: nowrap;
        }

        .step-sub {
            font-size: 10px;
            color: rgba(255, 255, 255, 0.8) !important;
            text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.5);
            margin-top: 2px;
        }

        .step.active .step-label {
            color: #FFD700 !important;
            font-weight: 700;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.6);
        }

        .step.active .step-sub {
            color: rgba(255, 215, 0, 0.9) !important;
        }

        @media (max-width: 768px) {
            .step-indicator {
                flex-wrap: wrap;
                gap: 12px;
                background: rgba(0, 0, 0, 0.6);
                backdrop-filter: blur(10px);
                padding: 15px;
                border-radius: 30px;
            }
            
            .step-indicator::before {
                display: none;
            }
            
            .step {
                flex: 0 0 calc(50% - 6px);
                padding: 8px 5px;
                background: rgba(255, 255, 255, 0.1);
                border-radius: 20px;
                backdrop-filter: blur(5px);
                border: 1px solid rgba(255, 255, 255, 0.15);
            }
            
            .step-number {
                width: 32px;
                height: 32px;
                font-size: 14px;
                margin-bottom: 4px;
            }
            
            .step-label {
                font-size: 10px;
                white-space: normal;
            }
            
            .step-sub {
                font-size: 8px;
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
            text-shadow: 1px 1px 2px rgba(0,0,0,0.2);
        }

        .card-header p {
            font-size: 14px;
            margin: 0;
            color: rgba(255,255,255,0.95) !important;
            font-weight: 400;
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
            text-shadow: 1px 1px 2px rgba(0,0,0,0.2);
        }

        .terms-body {
            padding: 20px;
            max-height: 300px;
            overflow-y: auto;
            background: white;
        }

        .terms-body h6 {
            color: var(--primary-dark);
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
            font-family: 'Inter', sans-serif;
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
            color: white;
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
           FOOTER
           ========================================================================== */
        .app-footer {
            text-align: center;
            margin-top: 30px;
            color: #FFFFFF !important;
            font-size: 13px;
            padding: 20px;
            background: rgba(0, 0, 0, 0.4);
            border-radius: var(--radius-lg);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .app-footer p {
            color: #FFFFFF !important;
            margin-bottom: 5px;
            text-shadow: 1px 1px 2px rgba(0,0,0,0.3);
        }

        .app-footer a {
            color: #FFFFFF !important;
            text-decoration: none;
            font-weight: 500;
            border-bottom: 1px dotted rgba(255, 255, 255, 0.6);
        }

        .app-footer a:hover {
            border-bottom-color: #FFFFFF;
        }

        .app-footer i {
            margin: 0 5px;
            color: #FFFFFF !important;
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
        <!-- Header -->
        <div class="header">
            <h1><?php echo $this->e('FCT College of Nursing Sciences'); ?></h1>
            <p><?php echo $this->e('2025/2026 Admissions Application Portal'); ?></p>
        </div>

        <!-- Step Indicator -->
        <div class="step-indicator">
            <?php foreach ($steps as $num => $step): 
                $stepClass = '';
                if ($num < $currentStep) $stepClass = 'completed';
                elseif ($num == $currentStep) $stepClass = 'active';
            ?>
            <div class="step <?php echo $this->e($stepClass); ?>">
                <div class="step-number">
                    <?php if ($num < $currentStep): ?>
                        <i class="fas fa-check"></i>
                    <?php else: ?>
                        <?php echo $this->e($num); ?>
                    <?php endif; ?>
                </div>
                <div class="step-label"><?php echo $this->e($step['label']); ?></div>
                <div class="step-sub"><?php echo $this->e($step['sub']); ?></div>
            </div>
            <?php endforeach; ?>
        </div>

        <!-- Alert Container -->
        <div id="alertContainer"></div>

        <?php if ($portal_closed): ?>
            <!-- Portal Closed Message -->
            <div class="portal-closed">
                <i class="fas fa-exclamation-triangle"></i>
                <h2><?php echo $this->e('Application Portal Closed'); ?></h2>
                <p><?php echo $this->e($portal_message); ?></p>
                <p class="text-muted mt-3"><?php echo $this->e('The next admissions cycle will be announced on this portal.'); ?></p>
            </div>
        <?php else: ?>
            <!-- Main Card -->
            <div class="main-card">
                <div class="card-header">
                    <i class="fas fa-id-card"></i>
                    <h2><?php echo $this->e('JAMB Verification'); ?></h2>
                    <p><?php echo $this->e('Enter your JAMB registration number to begin your application'); ?></p>
                </div>
                
                <div class="card-body">
                    <?php if (empty($terms)): ?>
                        <div class="alert alert-warning">
                            <i class="fas fa-exclamation-triangle"></i>
                            <?php echo $this->e('Terms and conditions are not available at the moment. Please try again later.'); ?>
                        </div>
                    <?php else: ?>
                    
                    <!-- Terms and Conditions Card -->
                    <div class="terms-card">
                        <div class="terms-header">
                            <h5><i class="fas fa-file-contract"></i> <?php echo $this->e('Terms and Conditions'); ?></h5>
                        </div>
                        <div class="terms-body">
                            <h6><?php echo $this->e($terms['title'] ?? 'Terms and Conditions'); ?></h6>
                            <div class="terms-content">
                                <?php echo nl2br($this->e($terms['content'] ?? '')); ?>
                            </div>
                        </div>
                        <div class="terms-footer">
                            <i class="fas fa-clock me-1"></i>
                            <?php echo $this->e('Version: ' . ($terms['version'] ?? '1.0')); ?> | 
                            <?php echo $this->e('Effective: ' . (isset($terms['effective_date']) ? date('jS F Y', strtotime($terms['effective_date'])) : '15th September 2025')); ?>
                        </div>
                    </div>

                    <!-- JAMB Verification Form -->
                    <form id="jambVerificationForm">
                        <!-- FIXED: CSRF token using the same token from SecurityTrait -->
                        <input type="hidden" name="csrf_token" value="<?php echo $this->e($csrf_token); ?>">
                        
                        <div class="mb-4">
                            <label for="jamb_number" class="form-label">
                                <i class="fas fa-id-card text-primary"></i>
                                <?php echo $this->e('JAMB Registration Number'); ?>
                            </label>
                            <input type="text" 
                                   class="form-control form-control-lg" 
                                   id="jamb_number" 
                                   name="jamb_number" 
                                   placeholder="<?php echo $this->e('e.g., 202650000089FG'); ?>"
                                   style="text-transform: uppercase;"
                                   autocomplete="off"
                                   maxlength="14"
                                   pattern="[0-9A-Za-z]{10,14}"
                                   required>
                            <div class="form-text">
                                <i class="fas fa-info-circle"></i> 
                                <?php echo $this->e('Enter the JAMB registration number you used for the 2025 UTME.'); ?>
                            </div>
                        </div>
                        
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" id="accept_terms" name="accept_terms" required>
                            <label class="form-check-label" for="accept_terms">
                                <?php echo $this->e('I have read and agree to the '); ?>
                                <a href="#" data-bs-toggle="modal" data-bs-target="#termsModal"><?php echo $this->e('Terms and Conditions'); ?></a>
                            </label>
                        </div>
                        
                        <!-- Requirements Alert -->
                        <div class="info-alert">
                            <i class="fas fa-info-circle"></i>
                            <strong><?php echo $this->e('By proceeding, you confirm that:'); ?></strong>
                            <ul class="mb-0">
                                <li><?php echo $this->e('You have a minimum UTME score of ' . ($settings['key_value']['min_utme_score'] ?? '170')); ?></li>
                                <li><?php echo $this->e('You selected FCT College of Nursing Sciences as your first choice'); ?></li>
                                <li><?php echo $this->e('You have the required O\'Level credits (5 credits including English, Maths, Biology, Chemistry, Physics)'); ?></li>
                                <li><?php echo $this->e('You are at least ' . ($settings['key_value']['min_age'] ?? '16') . ' years old'); ?></li>
                            </ul>
                        </div>
                        
                        <button type="submit" class="btn btn-primary btn-lg" id="verifyBtn">
                            <span id="btnText"><i class="fas fa-check-circle"></i> <?php echo $this->e('Verify JAMB Number'); ?></span>
                            <span id="btnSpinner" style="display: none;">
                                <span class="spinner-border" role="status"></span>
                                <?php echo $this->e('Verifying...'); ?>
                            </span>
                        </button>
                    </form>
                    
                    <?php endif; ?>
                    
                    <div class="divider">
                        <span><?php echo $this->e('OR'); ?></span>
                    </div>
                    
                    <!-- Login Link -->
                    <div class="text-center">
                        <p class="mb-2" style="color: var(--text-muted);"><?php echo $this->e('Already have an account?'); ?></p>
                        <a href="/applicant/login" class="btn btn-outline-primary">
                            <i class="fas fa-sign-in-alt"></i> <?php echo $this->e('Login to Continue Application'); ?>
                        </a>
                    </div>
                </div>
            </div>
        <?php endif; ?>

        <!-- Footer -->
        <div class="app-footer">
            <p>© <?php echo date('Y'); ?> <?php echo $this->e('FCT College of Nursing Sciences. All rights reserved.'); ?></p>
            <p>
                <i class="fas fa-phone-alt"></i> <?php echo $this->e('Support: 07039837749'); ?> | 
                <i class="fas fa-envelope"></i> <?php echo $this->e('Email:'); ?> <a href="mailto:info@fctcns.edu.ng">info@fctcns.edu.ng</a>
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
                        <i class="fas fa-file-contract me-2"></i><?php echo $this->e('Terms and Conditions'); ?>
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <h6><?php echo $this->e($terms['title'] ?? 'Terms and Conditions'); ?></h6>
                    <div class="terms-content">
                        <?php echo nl2br($this->e($terms['content'] ?? '')); ?>
                    </div>
                    <hr>
                    <p class="text-muted small mb-0">
                        <i class="fas fa-clock me-1"></i>
                        <?php echo $this->e('Version: ' . ($terms['version'] ?? '1.0')); ?> | 
                        <?php echo $this->e('Effective: ' . (isset($terms['effective_date']) ? date('jS F Y', strtotime($terms['effective_date'])) : '15th September 2025')); ?>
                    </p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" data-bs-dismiss="modal"><?php echo $this->e('Close'); ?></button>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <!-- Bootstrap JS with SRI -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" 
            integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" 
            crossorigin="anonymous"
            nonce="<?php echo $csp_nonce; ?>"></script>
    
    <!-- Custom JavaScript with CSP nonce -->
    <script nonce="<?php echo $csp_nonce; ?>">
    // FIXED: Get CSRF token from meta tag instead of PHP variable
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    
    // Security: Secure terms data
    const TERMS_DATA = <?php echo $secureTermsData; ?>;

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
        
        // Add CSRF token (already in form, but ensure it's there)
        if (!formData.has('csrf_token')) {
            formData.append('csrf_token', csrfToken);
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
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-Token': csrfToken
                }
            });
            
            const data = await response.json();
            
            if (data.success) {
                // Store JAMB data in sessionStorage (already escaped)
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
        
        // Security: Escape message to prevent XSS
        const safeMessage = String(message).replace(/[<>]/g, '');
        
        alertContainer.innerHTML = `
            <div class="alert alert-${type} alert-dismissible fade show" role="alert">
                <i class="fas ${icon}"></i>
                <span>${safeMessage}</span>
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
<?php
    }
}

// Instantiate and render the view
$view = new JambVerificationView();
$view->render(get_defined_vars());
?>