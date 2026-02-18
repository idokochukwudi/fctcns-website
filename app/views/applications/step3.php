<?php
/**
 * Payment View - Step 3
 * Professional design matching application form
 * FIXED: RRR generation, expanded width, and support section layout
 * 
 * @package FCTCNS
 * @version 1.2 - Fixed button ID and support section overflow
 */

extract($data ?? []);

if (!function_exists('e')) {
    function e($text) {
        return htmlspecialchars($text ?? '', ENT_QUOTES, 'UTF-8');
    }
}

$baseUrl = $baseUrl ?? '/';
$csrf_token = $csrf_token ?? '';
$application = $application ?? [];
$fee = $fee ?? 2200;
$currency = $currency ?? '₦';
$pending_payment = $pending_payment ?? null;
$applicant_name = trim(($application['first_name'] ?? '') . ' ' . ($application['last_name'] ?? ''));
if (empty($applicant_name)) {
    $applicant_name = 'Applicant';
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0, viewport-fit=cover">
    <meta name="description" content="Payment - FCT College of Nursing Sciences">
    <title>Payment - FCT College of Nursing Sciences</title>
    
    <!-- Premium Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@400;500;600;700&family=Outfit:wght@300;400;500;600;700&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">
    
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- CSRF Token Meta Tag -->
    <meta name="csrf-token" content="<?php echo $csrf_token; ?>">
    
    <style>
    /* ==========================================================================
       RESET & BASE STYLES (Matching application form)
       ========================================================================== */
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    html, body {
        margin: 0 !important;
        padding: 0 !important;
        width: 100%;
        overflow-x: hidden;
        background: #F7F9FC;
    }

    body {
        font-family: 'Outfit', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
        font-size: 16px;
        line-height: 1.6;
        color: #1A1F2E;
        -webkit-font-smoothing: antialiased;
        -moz-osx-font-smoothing: grayscale;
        min-height: 100vh;
    }

    /* ==========================================================================
       DESIGN TOKENS (Matching application form with expanded container)
       ========================================================================== */
    :root {
        /* Colors - Purple & Gold */
        --purple-deep: #4B1F5A;
        --purple: #6C3082;
        --purple-medium: #8A4FA0;
        --purple-light: #A875BD;
        --purple-pale: #F3EAF8;
        --purple-soft: #F9F3FC;
        
        --gold-deep: #B48C3A;
        --gold: #C9A44A;
        --gold-light: #D8B86C;
        --gold-pale: #FDF6E7;
        --gold-soft: #FFFAF0;
        
        /* Status Colors */
        --success: #10b981;
        --success-light: #d1fae5;
        --warning: #f59e0b;
        --warning-light: #fef3c7;
        --danger: #ef4444;
        --danger-light: #fee2e2;
        --info: #3b82f6;
        --info-light: #dbeafe;
        
        /* Neutrals */
        --ink: #1A1F2E;
        --ink-mid: #2A3042;
        --ink-soft: #3A4055;
        --slate: #5B677B;
        --mist: #8E9AAC;
        --border: #E9EDF2;
        --surface: #F7F9FC;
        --white: #FFFFFF;
        
        /* Gradients */
        --purple-gradient: linear-gradient(135deg, #4B1F5A 0%, #6C3082 50%, #8A4FA0 100%);
        --gold-gradient: linear-gradient(135deg, #B48C3A 0%, #C9A44A 50%, #D8B86C 100%);
        
        /* Shadows */
        --shadow-sm: 0 2px 8px rgba(0,0,0,0.05);
        --shadow-md: 0 6px 24px rgba(0,0,0,0.06);
        --shadow-lg: 0 16px 48px rgba(0,0,0,0.08);
        --shadow-xl: 0 32px 80px rgba(0,0,0,0.12);
        --shadow-purple: 0 10px 30px rgba(108,48,130,0.25);
        --shadow-gold: 0 10px 30px rgba(201,164,74,0.2);
        
        /* Border Radius */
        --radius-sm: 6px;
        --radius-md: 12px;
        --radius-lg: 20px;
        --radius-xl: 28px;
        --radius-full: 9999px;
        
        /* Typography */
        --font-display: 'Cormorant Garamond', Georgia, serif;
        --font-body: 'Outfit', system-ui, sans-serif;
        --font-mono: 'JetBrains Mono', monospace;
        
        /* Spacing - Expanded for desktop */
        --gutter: clamp(1rem, 4vw, 4rem);
        --container-max: 1800px; /* Increased for ultra-wide layout */
        
        --space-xs: 0.5rem;
        --space-sm: 1rem;
        --space-md: 1.5rem;
        --space-lg: 2rem;
        --space-xl: 3rem;
        --space-xxl: 5rem;
    }

    /* ==========================================================================
       CONTAINER & LAYOUT - OPTIMIZED FOR WIDE SCREENS
       ========================================================================== */
    .container {
        width: 100%;
        max-width: var(--container-max);
        margin: 0 auto;
        padding: var(--space-lg) var(--gutter);
    }

    /* Reduced side padding on very wide screens */
    @media (min-width: 1400px) {
        .container {
            padding-left: 2rem;
            padding-right: 2rem;
        }
    }

    @media (min-width: 1800px) {
        .container {
            padding-left: 1.5rem;
            padding-right: 1.5rem;
        }
    }

    .main-content {
        min-height: calc(100vh - 200px);
    }

    /* Expanded content area */
    .content-col {
        width: 100%;
    }
    
    @media (min-width: 1200px) {
        .content-col {
            width: 90%;
            margin: 0 auto;
        }
    }
    
    @media (min-width: 1600px) {
        .content-col {
            width: 85%;
            margin: 0 auto;
        }
    }

    /* ==========================================================================
       TOP BAR WITH WELCOME AND LOGOUT
       ========================================================================== */
    .top-bar {
        display: flex;
        justify-content: space-between;
        align-items: center;
        background: var(--white);
        border-radius: var(--radius-lg);
        padding: var(--space-md) var(--space-lg);
        margin-bottom: var(--space-lg);
        box-shadow: var(--shadow-sm);
        border: 1px solid var(--border);
        flex-wrap: wrap;
        gap: var(--space-md);
    }

    .welcome-message {
        display: flex;
        align-items: center;
        gap: var(--space-sm);
    }

    .welcome-icon {
        width: 40px;
        height: 40px;
        background: var(--purple-pale);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--purple);
        font-size: 1.2rem;
    }

    .welcome-text {
        font-size: 0.95rem;
        color: var(--slate);
    }

    .welcome-text strong {
        color: var(--purple-deep);
        font-weight: 600;
    }

    .logout-btn {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.5rem 1.25rem;
        background: transparent;
        color: var(--danger);
        border: 1.5px solid var(--danger-light);
        border-radius: var(--radius-full);
        font-size: 0.9rem;
        font-weight: 500;
        text-decoration: none;
        transition: all 0.3s ease;
    }

    .logout-btn:hover {
        background: var(--danger);
        color: white;
        border-color: var(--danger);
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(239,68,68,0.2);
    }

    @media (max-width: 480px) {
        .top-bar {
            flex-direction: column;
            align-items: flex-start;
        }
        
        .logout-btn {
            align-self: flex-end;
        }
    }

    /* ==========================================================================
       TYPOGRAPHY
       ========================================================================== */
    h1, h2, h3, h4, h5, h6 {
        font-family: var(--font-display);
        font-weight: 700;
        line-height: 1.2;
        color: var(--ink);
    }

    /* ==========================================================================
       STEPPER PROGRESS (Matching application form)
       ========================================================================== */
    .stepper-wrapper {
        display: flex;
        justify-content: space-between;
        width: 100%;
        max-width: 900px;
        margin: 0 auto;
    }

    .stepper-item {
        position: relative;
        display: flex;
        flex-direction: column;
        align-items: center;
        flex: 1;
    }

    .stepper-item::before {
        position: absolute;
        content: "";
        border-bottom: 2px solid var(--border);
        width: 100%;
        top: 20px;
        left: -50%;
        z-index: 2;
    }

    .stepper-item:first-child::before {
        content: none;
    }

    .stepper-item .step-counter {
        position: relative;
        z-index: 5;
        display: flex;
        justify-content: center;
        align-items: center;
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background: var(--white);
        border: 2px solid var(--border);
        color: var(--slate);
        font-weight: 600;
        margin-bottom: 8px;
        transition: all 0.3s ease;
    }

    .stepper-item.active .step-counter {
        background: var(--purple-gradient);
        border-color: transparent;
        color: white;
        box-shadow: var(--shadow-purple);
    }

    .stepper-item.completed .step-counter {
        background: var(--success);
        border-color: transparent;
        color: white;
    }

    .stepper-item .step-name {
        font-size: 0.75rem;
        color: var(--slate);
        font-weight: 500;
        text-align: center;
    }

    .stepper-item.active .step-name {
        color: var(--purple);
        font-weight: 600;
    }

    .stepper-item.completed .step-name {
        color: var(--success);
    }

    @media (max-width: 768px) {
        .stepper-item .step-name {
            font-size: 0.65rem;
        }
        
        .stepper-item .step-counter {
            width: 32px;
            height: 32px;
            font-size: 0.875rem;
        }
    }

    /* ==========================================================================
       CARDS
       ========================================================================== */
    .card {
        background: var(--white);
        border-radius: var(--radius-lg);
        box-shadow: var(--shadow-lg);
        overflow: hidden;
        transition: all 0.3s ease;
        width: 100%;
        border: none;
    }

    .card:hover {
        box-shadow: var(--shadow-xl);
    }

    .card-header {
        background: var(--purple-gradient);
        color: white;
        padding: var(--space-lg) var(--space-xl);
        position: relative;
        overflow: hidden;
        border-bottom: none;
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

    .card-body {
        padding: var(--space-xl);
    }

    @media (min-width: 1200px) {
        .card-body {
            padding: 3rem 4rem;
        }
    }

    @media (min-width: 1600px) {
        .card-body {
            padding: 3rem 5rem;
        }
    }

    .card-footer {
        background: var(--surface);
        padding: var(--space-md) var(--space-xl);
        border-top: 1px solid var(--border);
    }

    /* ==========================================================================
       BUTTONS (Matching application form)
       ========================================================================== */
    .btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        padding: 0.75rem 1.5rem;
        font-family: var(--font-body);
        font-size: 0.95rem;
        font-weight: 500;
        line-height: 1;
        text-decoration: none;
        border: none;
        border-radius: var(--radius-sm);
        cursor: pointer;
        transition: all 0.3s ease;
        white-space: nowrap;
    }

    .btn--primary {
        background: var(--purple-gradient);
        color: white;
        background-size: 200% auto;
        box-shadow: var(--shadow-purple);
    }

    .btn--primary:hover {
        background-position: right center;
        transform: translateY(-2px);
        box-shadow: 0 15px 35px rgba(108,48,130,0.35);
    }

    .btn--success {
        background: var(--success);
        color: white;
    }

    .btn--success:hover {
        background: #0ca678;
        transform: translateY(-2px);
        box-shadow: 0 10px 25px rgba(16,185,129,0.3);
    }

    .btn--warning {
        background: var(--warning);
        color: white;
    }

    .btn--warning:hover {
        background: #e08e0b;
        transform: translateY(-2px);
        box-shadow: 0 10px 25px rgba(245,158,11,0.3);
    }

    .btn--outline {
        background: transparent;
        color: var(--purple);
        border: 2px solid var(--purple-light);
    }

    .btn--outline:hover {
        background: var(--purple);
        border-color: var(--purple);
        color: white;
        transform: translateY(-2px);
    }

    .btn--lg {
        padding: 0.875rem 2rem;
        font-size: 1rem;
    }

    .btn--sm {
        padding: 0.5rem 1rem;
        font-size: 0.85rem;
    }

    .btn:disabled {
        opacity: 0.6;
        cursor: not-allowed;
        transform: none !important;
    }

    /* ==========================================================================
       ALERTS
       ========================================================================== */
    .alert {
        padding: var(--space-md);
        border-radius: var(--radius-md);
        margin-bottom: var(--space-lg);
        border-left: 4px solid transparent;
        border: none;
    }

    .alert-success {
        background: var(--success-light);
        border-left-color: var(--success);
        color: #065f46;
    }

    .alert-danger {
        background: var(--danger-light);
        border-left-color: var(--danger);
        color: #991b1b;
    }

    .alert-warning {
        background: var(--warning-light);
        border-left-color: var(--warning);
        color: #92400e;
    }

    .alert-info {
        background: var(--info-light);
        border-left-color: var(--info);
        color: #1e40af;
    }

    .alert-dismissible {
        position: relative;
        padding-right: 3rem;
    }

    .alert-dismissible .btn-close {
        position: absolute;
        top: 50%;
        right: var(--space-md);
        transform: translateY(-50%);
        background: transparent;
        border: none;
        font-size: 1.2rem;
        cursor: pointer;
        color: currentColor;
        opacity: 0.6;
    }

    .alert-dismissible .btn-close:hover {
        opacity: 1;
    }

    /* ==========================================================================
       RRR DISPLAY
       ========================================================================== */
    .rrr-display {
        font-size: 1.5rem;
        font-weight: 700;
        font-family: var(--font-mono);
        color: var(--purple-deep);
        background: var(--purple-pale);
        padding: var(--space-md);
        border-radius: var(--radius-md);
        text-align: center;
        letter-spacing: 2px;
        border: 1px dashed var(--purple-light);
        word-break: break-all;
    }

    /* ==========================================================================
       PAYMENT INSTRUCTIONS
       ========================================================================== */
    .payment-instructions {
        background: var(--info-light);
        border-radius: var(--radius-lg);
        padding: var(--space-lg);
        border-left: 4px solid var(--info);
    }

    .payment-instructions ol {
        margin-top: var(--space-sm);
        padding-left: var(--space-lg);
    }

    .payment-instructions li {
        margin-bottom: var(--space-xs);
        color: var(--ink-mid);
    }

    /* ==========================================================================
       FEE DISPLAY
       ========================================================================== */
    .fee-display {
        text-align: center;
        margin-bottom: var(--space-lg);
        padding: var(--space-lg);
        background: var(--surface);
        border-radius: var(--radius-lg);
    }

    .fee-label {
        font-size: 0.9rem;
        color: var(--slate);
        text-transform: uppercase;
        letter-spacing: 1px;
    }

    .fee-amount {
        font-size: 3.5rem;
        font-weight: 700;
        color: var(--purple-deep);
        line-height: 1.2;
        font-family: var(--font-display);
    }

    .fee-note {
        font-size: 0.85rem;
        color: var(--mist);
    }

    /* ==========================================================================
       LOADING SPINNER
       ========================================================================== */
    .spinner-border {
        display: inline-block;
        width: 1.5rem;
        height: 1.5rem;
        border: 3px solid currentColor;
        border-right-color: transparent;
        border-radius: 50%;
        animation: spinner 0.75s linear infinite;
    }

    .spinner-border-sm {
        width: 1rem;
        height: 1rem;
        border-width: 2px;
    }

    @keyframes spinner {
        to { transform: rotate(360deg); }
    }

    /* ==========================================================================
       SUPPORT SECTION - FIXED FOR EMAIL OVERFLOW
       ========================================================================== */
    .support-section {
        background: var(--surface);
        border-radius: var(--radius-lg);
        padding: var(--space-lg);
        margin-top: var(--space-lg);
    }

    .support-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: var(--space-md);
    }

    .support-item {
        display: flex;
        align-items: center;
        gap: var(--space-sm);
        padding: var(--space-sm) var(--space-md);
        background: var(--white);
        border-radius: var(--radius-md);
        box-shadow: var(--shadow-sm);
        min-width: 0; /* Allows flex items to shrink below content size */
        width: 100%;
    }

    .support-icon {
        width: 36px;
        height: 36px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        flex-shrink: 0; /* Prevents icon from shrinking */
    }

    .support-icon.phone { background: var(--purple); }
    .support-icon.whatsapp { background: #25D366; }
    .support-icon.email { background: var(--danger); }

    .support-content {
        flex: 1;
        min-width: 0; /* Enables text truncation */
    }

    .support-label {
        font-size: 0.7rem;
        color: var(--mist);
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 0.1rem;
    }

    .support-value {
        font-size: 0.9rem;
        font-weight: 500;
        color: var(--ink);
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        width: 100%;
    }

    /* Mobile styles */
    @media (max-width: 768px) {
        .support-grid {
            grid-template-columns: 1fr;
            gap: var(--space-sm);
        }
        
        .support-item {
            padding: var(--space-sm) var(--space-md);
        }
        
        .support-value {
            white-space: normal; /* Allow wrapping on mobile */
            word-break: break-word;
        }
    }

    /* ==========================================================================
       UTILITIES
       ========================================================================== */
    .d-flex { display: flex; }
    .align-items-center { align-items: center; }
    .justify-content-between { justify-content: space-between; }
    .justify-content-center { justify-content: center; }
    .flex-wrap { flex-wrap: wrap; }
    .flex-column { flex-direction: column; }
    .gap-1 { gap: 0.25rem; }
    .gap-2 { gap: 0.5rem; }
    .gap-3 { gap: 1rem; }
    .gap-4 { gap: 1.5rem; }

    .mt-1 { margin-top: 0.25rem; }
    .mt-2 { margin-top: 0.5rem; }
    .mt-3 { margin-top: 1rem; }
    .mt-4 { margin-top: 1.5rem; }
    .mt-5 { margin-top: 2rem; }

    .mb-1 { margin-bottom: 0.25rem; }
    .mb-2 { margin-bottom: 0.5rem; }
    .mb-3 { margin-bottom: 1rem; }
    .mb-4 { margin-bottom: 1.5rem; }
    .mb-5 { margin-bottom: 2rem; }

    .text-primary { color: var(--purple); }
    .text-success { color: var(--success); }
    .text-danger { color: var(--danger); }
    .text-warning { color: var(--warning); }
    .text-muted { color: var(--mist); }

    .fw-bold { font-weight: 700; }
    .fw-semibold { font-weight: 600; }

    .text-center { text-align: center; }

    .w-100 { width: 100%; }

    /* ==========================================================================
       ANIMATIONS
       ========================================================================== */
    .fade-in {
        animation: fadeIn 0.5s ease forwards;
    }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }

    /* ==========================================================================
       RESPONSIVE
       ========================================================================== */
    @media (max-width: 768px) {
        .fee-amount {
            font-size: 2.5rem;
        }
        
        .card-body {
            padding: var(--space-lg);
        }
        
        .d-flex.justify-content-between {
            flex-direction: column;
            gap: var(--space-md);
        }
        
        .d-flex.justify-content-between a,
        .d-flex.justify-content-between button {
            width: 100%;
        }
        
        .btn {
            white-space: normal;
        }
    }

    @media (max-width: 480px) {
        .card-body {
            padding: var(--space-md);
        }
        
        .fee-amount {
            font-size: 2rem;
        }
        
        .rrr-display {
            font-size: 1.1rem;
            padding: var(--space-sm);
        }
        
        .stepper-item .step-name {
            font-size: 0.6rem;
        }
    }
    </style>
</head>
<body>
    <main id="main-content" class="main-content" role="main">
        <div class="container">
            <div class="row justify-content-center">
                <!-- Expanded content column for desktop -->
                <div class="col-12 content-col">
                    
                    <!-- TOP BAR WITH WELCOME AND LOGOUT BUTTON -->
                    <div class="top-bar fade-in">
                        <div class="welcome-message">
                            <div class="welcome-icon">
                                <i class="fas fa-user-graduate"></i>
                            </div>
                            <div class="welcome-text">
                                Welcome, <strong><?php echo e($applicant_name); ?></strong> | 
                                <span class="text-muted">Application #: <?php echo e($application['application_number'] ?? 'Not assigned'); ?></span>
                            </div>
                        </div>
                        <a href="/applicant/logout" class="logout-btn" onclick="return confirm('Are you sure you want to logout? Your progress will be saved.');">
                            <i class="fas fa-sign-out-alt"></i> Logout
                        </a>
                    </div>
                    
                    <!-- Progress Indicator -->
                    <div class="text-center mb-5 fade-in">
                        <h1 class="display-5 fw-bold text-primary">FCT College of Nursing Sciences</h1>
                        <p class="text-muted">2025/2026 Admissions Application Portal</p>
                        
                        <!-- Step Progress -->
                        <div class="stepper-wrapper mt-4">
                            <div class="stepper-item completed">
                                <div class="step-counter"><i class="fas fa-check"></i></div>
                                <div class="step-name">JAMB Verified</div>
                            </div>
                            <div class="stepper-item completed">
                                <div class="step-counter"><i class="fas fa-check"></i></div>
                                <div class="step-name">Application</div>
                            </div>
                            <div class="stepper-item active">
                                <div class="step-counter">3</div>
                                <div class="step-name">Payment</div>
                            </div>
                            <div class="stepper-item">
                                <div class="step-counter">4</div>
                                <div class="step-name">Exam Slip</div>
                            </div>
                        </div>
                    </div>

                    <!-- Alert Container -->
                    <div id="alertContainer" class="mb-4"></div>

                    <!-- Payment Card -->
                    <div class="card fade-in">
                        <!-- Card Header -->
                        <div class="card-header">
                            <div class="d-flex align-items-center">
                                <div class="rounded-circle bg-white bg-opacity-20 p-3 me-3">
                                    <i class="fas fa-credit-card fa-2x text-white"></i>
                                </div>
                                <div>
                                    <h2 class="h3 mb-1 fw-bold">Payment</h2>
                                    <p class="mb-0 opacity-75">Step 3 of 4 - Complete your payment</p>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Card Body -->
                        <div class="card-body">
                            <!-- Fee Display -->
                            <div class="fee-display">
                                <div class="fee-label">Application Fee</div>
                                <div class="fee-amount"><?php echo $currency; ?><?php echo number_format($fee); ?></div>
                                <div class="fee-note">This fee is non-refundable</div>
                            </div>

                            <!-- Payment Instructions -->
                            <div class="payment-instructions mb-4">
                                <h5 class="fw-semibold mb-3">
                                    <i class="fas fa-info-circle text-info me-2"></i>Payment Instructions:
                                </h5>
                                <ol class="mb-0">
                                    <li>Click "Generate RRR" to create a payment RRR (Remita Retrieval Reference)</li>
                                    <li>You'll be redirected to Remita secure payment page</li>
                                    <li>Complete payment using your card, internet banking, or USSD</li>
                                    <li>After payment, return here and click "Verify Payment"</li>
                                    <li>Your exam slip will be available immediately after verification</li>
                                </ol>
                            </div>

                            <!-- Pending Payment (if exists) -->
                            <?php if (isset($pending_payment) && $pending_payment): ?>
                            <div class="alert alert-warning mb-4">
                                <div class="d-flex align-items-start">
                                    <i class="fas fa-clock fa-lg me-3 mt-1"></i>
                                    <div class="w-100">
                                        <h5 class="alert-heading fw-semibold">Pending Payment</h5>
                                        <p class="mb-2">You have a pending payment with RRR:</p>
                                        <div class="rrr-display mb-3"><?php echo e($pending_payment['rrr']); ?></div>
                                        <div class="d-flex gap-2 flex-wrap">
                                            <a href="https://remitademo.net/remita/ecomm/frame/handleCCD.action?rrr=<?php echo $pending_payment['rrr']; ?>" 
                                               target="_blank" class="btn btn--warning">
                                                <i class="fas fa-external-link-alt me-2"></i>Complete Payment
                                            </a>
                                            <button class="btn btn--success" onclick="verifyPayment('<?php echo $pending_payment['rrr']; ?>')">
                                                <i class="fas fa-check-circle me-2"></i>I've Paid, Verify
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php endif; ?>

                            <!-- RRR Display Area -->
                            <div id="rrrDisplayArea" class="mb-4" style="display: none;">
                                <div class="card bg-light border-0">
                                    <div class="card-body">
                                        <h5 class="card-title fw-semibold mb-3">
                                            <i class="fas fa-receipt me-2 text-primary"></i>
                                            Your Payment RRR
                                        </h5>
                                        <div class="rrr-display mb-3" id="generatedRRR"></div>
                                        <button class="btn btn--outline btn--sm" onclick="copyRRR()">
                                            <i class="fas fa-copy me-2"></i>Copy RRR
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <!-- Payment Status Area -->
                            <div id="paymentStatus" class="mb-4" style="display: none;">
                                <div class="card bg-light border-0">
                                    <div class="card-body text-center">
                                        <div id="paymentSpinner" class="spinner-border text-primary mb-3" role="status">
                                            <span class="visually-hidden">Loading...</span>
                                        </div>
                                        <h5 id="paymentMessage" class="fw-semibold mb-3">Processing payment...</h5>
                                        <div id="paymentRRR" class="rrr-display mt-3" style="display: none;"></div>
                                        <div id="remitaLink" class="mt-3" style="display: none;"></div>
                                    </div>
                                </div>
                            </div>

                            <!-- Action Buttons -->
                            <div class="d-flex flex-column gap-3">
                                <button class="btn btn--primary btn--lg w-100" id="generateRRRBtn">
                                    <i class="fas fa-play me-2"></i>Generate RRR
                                </button>
                                
                                <button class="btn btn--success btn--lg w-100" id="verifyPaymentBtn" style="display: none;">
                                    <i class="fas fa-check-circle me-2"></i>I've Paid, Verify
                                </button>
                                
                                <div class="d-flex justify-content-between mt-3">
                                    <a href="/apply/form" class="btn btn--outline">
                                        <i class="fas fa-arrow-left me-2"></i>Back to Form
                                    </a>
                                    <button class="btn btn--outline" id="checkStatusBtn" style="display: none;">
                                        <i class="fas fa-sync me-2"></i>Check Status
                                    </button>
                                </div>
                            </div>

                            <!-- Support Information - FIXED for email overflow -->
                            <div class="support-section">
                                <h5 class="fw-semibold text-center mb-4">Payment Support</h5>
                                <div class="support-grid">
                                    <div class="support-item">
                                        <div class="support-icon phone">
                                            <i class="fas fa-phone"></i>
                                        </div>
                                        <div class="support-content">
                                            <div class="support-label">Phone</div>
                                            <div class="support-value">07039837749</div>
                                        </div>
                                    </div>
                                    <div class="support-item">
                                        <div class="support-icon whatsapp">
                                            <i class="fab fa-whatsapp"></i>
                                        </div>
                                        <div class="support-content">
                                            <div class="support-label">WhatsApp</div>
                                            <div class="support-value">08082775076</div>
                                        </div>
                                    </div>
                                    <div class="support-item">
                                        <div class="support-icon email">
                                            <i class="fas fa-envelope"></i>
                                        </div>
                                        <div class="support-content">
                                            <div class="support-label">Email</div>
                                            <div class="support-value">info@fctcns.edu.ng</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Card Footer -->
                        <div class="card-footer">
                            <div class="d-flex align-items-center justify-content-center">
                                <i class="fas fa-lock text-muted me-2"></i>
                                <small class="text-muted">Secured by Remita Payment Gateway</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- JavaScript -->
    <script>
    // Get CSRF token from meta tag
    function getCsrfToken() {
        return document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || 
               '<?php echo $csrf_token; ?>';
    }

    // Debug function to log with timestamp
    function debugLog(message, data = null) {
        const timestamp = new Date().toISOString();
        console.log(`[${timestamp}] ${message}`);
        if (data) {
            console.log(data);
        }
    }

    // Document ready
    document.addEventListener('DOMContentLoaded', function() {
        debugLog('Payment page loaded');
        
        // Check if generate button exists
        const generateBtn = document.getElementById('generateRRRBtn');
        if (!generateBtn) {
            debugLog('ERROR: Generate RRR button not found!');
        } else {
            debugLog('Generate RRR button found');
            generateBtn.addEventListener('click', function(e) {
                e.preventDefault();
                debugLog('Generate RRR button clicked');
                initiatePayment();
            });
        }
        
        // Initialize verify button
        const verifyBtn = document.getElementById('verifyPaymentBtn');
        if (verifyBtn) {
            verifyBtn.addEventListener('click', function(e) {
                e.preventDefault();
                var rrr = document.getElementById('generatedRRR')?.textContent || 
                         document.querySelector('.rrr-display')?.textContent ||
                         '<?php echo $pending_payment['rrr'] ?? ''; ?>';
                if (rrr) {
                    debugLog('Verifying payment for RRR:', rrr);
                    verifyPayment(rrr);
                } else {
                    showAlert('No RRR found. Please generate RRR first.', 'warning');
                }
            });
        }
        
        // Initialize check status button
        const checkBtn = document.getElementById('checkStatusBtn');
        if (checkBtn) {
            checkBtn.addEventListener('click', function(e) {
                e.preventDefault();
                var rrr = document.getElementById('generatedRRR')?.textContent || 
                         document.querySelector('.rrr-display')?.textContent ||
                         '<?php echo $pending_payment['rrr'] ?? ''; ?>';
                if (rrr) {
                    debugLog('Checking payment status for RRR:', rrr);
                    checkPaymentStatus(rrr);
                } else {
                    showAlert('No RRR found.', 'warning');
                }
            });
        }
        
        // Check if we have a pending RRR in sessionStorage
        var pendingRRR = sessionStorage.getItem('pending_rrr');
        if (pendingRRR) {
            debugLog('Found pending RRR in sessionStorage:', pendingRRR);
            showRRR(pendingRRR);
            document.getElementById('verifyPaymentBtn').style.display = 'block';
            document.getElementById('checkStatusBtn').style.display = 'inline-block';
        }
    });

    function initiatePayment() {
        debugLog('initiatePayment() called');
        
        const generateBtn = document.getElementById('generateRRRBtn');
        if (!generateBtn) {
            debugLog('ERROR: generateRRRBtn not found in initiatePayment');
            showAlert('Technical error: Button not found. Please refresh.', 'danger');
            return;
        }
        
        // Show payment status area
        document.getElementById('paymentStatus').style.display = 'block';
        generateBtn.disabled = true;
        document.getElementById('paymentMessage').innerText = 'Generating RRR...';
        document.getElementById('paymentSpinner').style.display = 'inline-block';
        
        // Get CSRF token
        var csrfToken = getCsrfToken();
        debugLog('CSRF Token:', csrfToken ? 'Found' : 'Missing');
        
        if (!csrfToken) {
            showAlert('Security token missing. Please refresh the page.', 'danger');
            resetPayment();
            return;
        }
        
        // Create form data
        const formData = new FormData();
        formData.append('csrf_token', csrfToken);
        
        const endpoint = '/payment/initiate';
        
        debugLog('Sending request to:', endpoint);
        
        fetch(endpoint, {
            method: 'POST',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            },
            body: formData,
            credentials: 'same-origin'
        })
        .then(response => {
            debugLog('Response status:', response.status);
            
            if (!response.ok) {
                return response.text().then(text => {
                    debugLog('Error response text:', text.substring(0, 500));
                    throw new Error(`Server error: ${response.status}`);
                });
            }
            
            return response.json();
        })
        .then(data => {
            debugLog('Payment initiation response:', data);
            
            document.getElementById('paymentSpinner').style.display = 'none';
            
            if (data.success) {
                document.getElementById('paymentMessage').innerText = 'RRR Generated Successfully!';
                
                // Store RRR - handle different response formats
                var rrr = data.rrr || data.data?.rrr || data.reference;
                debugLog('RRR received:', rrr);
                
                if (!rrr) {
                    showAlert('No RRR in response', 'danger');
                    resetPayment();
                    return;
                }
                
                sessionStorage.setItem('pending_rrr', rrr);
                
                // Show RRR
                showRRR(rrr);
                
                // Show Remita link
                var remitaUrl = 'https://remitademo.net/remita/ecomm/frame/handleCCD.action?rrr=' + rrr;
                
                // Create Remita link HTML
                var remitaHtml = '<div class="mt-3">' +
                    '<p class="mb-2 fw-semibold">Proceed to payment:</p>' +
                    '<a href="' + remitaUrl + '" target="_blank" class="btn btn--warning w-100">' +
                    '<i class="fas fa-external-link-alt me-2"></i>Pay Now on Remita</a>' +
                    '</div>';
                document.getElementById('remitaLink').innerHTML = remitaHtml;
                document.getElementById('remitaLink').style.display = 'block';
                
                // Show verify and check buttons
                document.getElementById('verifyPaymentBtn').style.display = 'block';
                document.getElementById('checkStatusBtn').style.display = 'inline-block';
                
                showAlert('RRR generated successfully: ' + rrr, 'success');
                
                // Open Remita in new window with confirmation
                if (confirm('RRR generated: ' + rrr + '\n\nClick OK to proceed to Remita payment page.')) {
                    window.open(remitaUrl, '_blank');
                }
            } else {
                showAlert(data.message || data.error || 'Failed to generate RRR', 'danger');
                resetPayment();
            }
        })
        .catch(error => {
            console.error('Fetch error:', error);
            document.getElementById('paymentSpinner').style.display = 'none';
            showAlert('Error: ' + error.message, 'danger');
            resetPayment();
        });
    }

    function showRRR(rrr) {
        document.getElementById('generatedRRR').textContent = rrr;
        document.getElementById('rrrDisplayArea').style.display = 'block';
        
        // Also update paymentRRR element if it exists
        if (document.getElementById('paymentRRR')) {
            document.getElementById('paymentRRR').innerHTML = '<strong>RRR:</strong> ' + rrr;
            document.getElementById('paymentRRR').style.display = 'block';
        }
    }

    function verifyPayment(rrr) {
        debugLog('Verifying payment for RRR:', rrr);
        
        document.getElementById('paymentStatus').style.display = 'block';
        document.getElementById('paymentMessage').innerText = 'Verifying payment...';
        document.getElementById('verifyPaymentBtn').disabled = true;
        document.getElementById('paymentSpinner').style.display = 'inline-block';
        
        var csrfToken = getCsrfToken();
        
        const formData = new FormData();
        formData.append('rrr', rrr);
        formData.append('csrf_token', csrfToken);
        
        fetch('/payment/verify', {
            method: 'POST',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            },
            body: formData,
            credentials: 'same-origin'
        })
        .then(response => response.json())
        .then(data => {
            debugLog('Verification response:', data);
            document.getElementById('paymentSpinner').style.display = 'none';
            
            if (data.success) {
                document.getElementById('paymentMessage').innerText = 'Payment Verified!';
                showAlert('Payment verified successfully! Redirecting...', 'success');
                
                // Clear session storage
                sessionStorage.removeItem('pending_rrr');
                
                setTimeout(function() {
                    window.location.href = data.redirect || '/apply/step/4';
                }, 2000);
            } else {
                document.getElementById('paymentMessage').innerText = 'Verification Failed';
                showAlert(data.message || 'Payment verification failed', 'danger');
                document.getElementById('verifyPaymentBtn').disabled = false;
            }
        })
        .catch(error => {
            console.error('Error:', error);
            document.getElementById('paymentSpinner').style.display = 'none';
            showAlert('Error verifying payment', 'danger');
            document.getElementById('verifyPaymentBtn').disabled = false;
        });
    }

    function checkPaymentStatus(rrr) {
        debugLog('Checking payment status for RRR:', rrr);
        
        document.getElementById('paymentMessage').innerText = 'Checking payment status...';
        document.getElementById('checkStatusBtn').disabled = true;
        document.getElementById('paymentSpinner').style.display = 'inline-block';
        
        fetch('/payment/status?rrr=' + encodeURIComponent(rrr), {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            },
            credentials: 'same-origin'
        })
        .then(response => response.json())
        .then(data => {
            debugLog('Status response:', data);
            document.getElementById('paymentSpinner').style.display = 'none';
            
            if (data.success && (data.status === 'success' || data.paid)) {
                document.getElementById('paymentMessage').innerText = 'Payment completed!';
                showAlert('Payment verified successfully! Redirecting...', 'success');
                
                // Clear session storage
                sessionStorage.removeItem('pending_rrr');
                
                setTimeout(() => {
                    window.location.href = '/apply/step/4';
                }, 2000);
            } else if (data.status === 'pending' || data.pending) {
                document.getElementById('paymentMessage').innerText = 'Payment still processing. Checking again in 10 seconds...';
                document.getElementById('checkStatusBtn').disabled = false;
                
                // Check again after 10 seconds
                setTimeout(() => checkPaymentStatus(rrr), 10000);
            } else {
                document.getElementById('paymentMessage').innerText = 'Payment not yet completed';
                showAlert('Payment not completed. Please complete payment first.', 'warning');
                document.getElementById('checkStatusBtn').disabled = false;
            }
        })
        .catch(error => {
            console.error('Error:', error);
            document.getElementById('paymentSpinner').style.display = 'none';
            showAlert('Error checking payment status', 'danger');
            document.getElementById('checkStatusBtn').disabled = false;
        });
    }

    function showAlert(message, type) {
        const alertContainer = document.getElementById('alertContainer');
        const icon = type === 'success' ? 'fa-check-circle' : 
                     type === 'danger' ? 'fa-exclamation-circle' : 
                     type === 'warning' ? 'fa-exclamation-triangle' : 'fa-info-circle';
        
        alertContainer.innerHTML = `
            <div class="alert alert-${type} alert-dismissible fade show" role="alert">
                <div class="d-flex align-items-center">
                    <i class="fas ${icon} fa-lg me-2"></i>
                    <span>${message}</span>
                    <button type="button" class="btn-close" onclick="this.closest('.alert').remove()" aria-label="Close">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>
        `;
        
        // Auto dismiss after 5 seconds (8 for errors)
        const timeout = type === 'danger' ? 8000 : 5000;
        setTimeout(() => {
            const alert = alertContainer.querySelector('.alert');
            if (alert) {
                alert.classList.remove('show');
                setTimeout(() => {
                    if (alertContainer.querySelector('.alert') === alert) {
                        alertContainer.innerHTML = '';
                    }
                }, 300);
            }
        }, timeout);
    }

    function copyRRR() {
        var rrr = document.getElementById('generatedRRR')?.textContent || 
                 document.querySelector('.rrr-display')?.textContent ||
                 sessionStorage.getItem('pending_rrr');
        
        if (!rrr) {
            showAlert('No RRR to copy', 'warning');
            return;
        }
        
        // Use modern clipboard API if available
        if (navigator.clipboard && navigator.clipboard.writeText) {
            navigator.clipboard.writeText(rrr).then(() => {
                showAlert('RRR copied to clipboard!', 'success');
            }).catch(() => {
                fallbackCopy(rrr);
            });
        } else {
            fallbackCopy(rrr);
        }
    }

    function fallbackCopy(text) {
        var textarea = document.createElement('textarea');
        textarea.value = text;
        document.body.appendChild(textarea);
        textarea.select();
        document.execCommand('copy');
        document.body.removeChild(textarea);
        showAlert('RRR copied to clipboard!', 'success');
    }

    function resetPayment() {
        document.getElementById('paymentStatus').style.display = 'none';
        document.getElementById('generateRRRBtn').disabled = false;
        document.getElementById('verifyPaymentBtn').style.display = 'none';
        document.getElementById('checkStatusBtn').style.display = 'none';
        document.getElementById('paymentSpinner').style.display = 'none';
    }
    </script>
</body>
</html>