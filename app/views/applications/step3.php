<?php
/**
 * Payment View - Step 3
 * Professional design matching application form
 * FIXED: RRR generation, expanded width, and support section layout
 * FIXED: Button ID to match JavaScript (generateRRRBtn)
 * FIXED: Responsive layout for laptop screens - no content hidden
 * 
 * @package FCTCNS
 * @version 1.5 - Fixed button ID and laptop responsiveness
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
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0">
    <meta name="description" content="Payment - FCT College of Nursing Sciences">
    <title>Payment - FCT College of Nursing Sciences</title>
    
    <!-- Premium Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@400;500;600;700&family=Outfit:wght@300;400;500;600;700&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">
    
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- CSRF Token -->
    <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">
    <meta name="csrf-token" content="<?php echo $csrf_token; ?>">
    
    <style>
    /* ==========================================================================
       RESET & BASE STYLES
       ========================================================================== */
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    html, body {
        margin: 0;
        padding: 0;
        width: 100%;
        overflow-x: hidden;
        background: #F7F9FC;
    }

    body {
        font-family: 'Outfit', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
        font-size: 16px;
        line-height: 1.5;
        color: #1A1F2E;
        -webkit-font-smoothing: antialiased;
        -moz-osx-font-smoothing: grayscale;
        min-height: 100vh;
    }

    /* ==========================================================================
       DESIGN TOKENS - OPTIMIZED FOR ALL SCREENS
       ========================================================================== */
    :root {
        /* Colors - Purple & Gold */
        --purple-deep: #4B1F5A;
        --purple: #6C3082;
        --purple-medium: #8A4FA0;
        --purple-light: #A875BD;
        --purple-pale: #F3EAF8;
        
        --gold-deep: #B48C3A;
        --gold: #C9A44A;
        --gold-light: #D8B86C;
        --gold-pale: #FDF6E7;
        
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
        
        /* Spacing - OPTIMIZED for laptop screens */
        --gutter: clamp(1rem, 3vw, 3rem);
        --container-max: 1400px; /* OPTIMIZED for laptops */
        
        --space-xs: 0.5rem;
        --space-sm: 1rem;
        --space-md: 1.5rem;
        --space-lg: 2rem;
        --space-xl: 2.5rem;
        --space-xxl: 4rem;
    }

    /* ==========================================================================
       CONTAINER & LAYOUT - OPTIMIZED FOR LAPTOPS
       ========================================================================== */
    .container {
        width: 100%;
        max-width: var(--container-max);
        margin: 0 auto;
        padding: var(--space-lg) var(--gutter);
    }

    /* Laptop optimization - 1366x768 and above */
    @media (min-width: 1200px) and (max-width: 1600px) {
        :root {
            --container-max: 1200px;
            --gutter: 2rem;
        }
        
        .container {
            padding: 1.5rem 2rem;
        }
    }

    /* Larger screens */
    @media (min-width: 1600px) {
        :root {
            --container-max: 1400px;
            --gutter: 3rem;
        }
    }

    .main-content {
        min-height: calc(100vh - 200px);
    }

    /* Content column - OPTIMIZED for laptops */
    .content-col {
        width: 100%;
    }
    
    @media (min-width: 1200px) {
        .content-col {
            width: 100%;
            margin: 0;
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
        flex-wrap: wrap;
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
        flex-shrink: 0;
    }

    .welcome-text {
        font-size: 0.95rem;
        color: var(--slate);
        line-height: 1.4;
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
        white-space: nowrap;
        flex-shrink: 0;
    }

    .logout-btn:hover {
        background: var(--danger);
        color: white;
        border-color: var(--danger);
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(239,68,68,0.2);
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
       STEPPER PROGRESS
       ========================================================================== */
    .stepper-wrapper {
        display: flex;
        justify-content: space-between;
        width: 100%;
        max-width: 800px;
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
        font-size: 0.8rem;
        color: var(--slate);
        font-weight: 500;
        text-align: center;
    }

    .stepper-item.active .step-name {
        color: var(--purple);
        font-weight: 600;
    }

    /* ==========================================================================
       CARDS - OPTIMIZED FOR LAPTOPS
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

    /* Laptop-optimized padding */
    @media (min-width: 1200px) and (max-width: 1600px) {
        .card-body {
            padding: 2rem;
        }
    }

    @media (min-width: 1600px) {
        .card-body {
            padding: 2.5rem 3rem;
        }
    }

    .card-footer {
        background: var(--surface);
        padding: var(--space-md) var(--space-xl);
        border-top: 1px solid var(--border);
    }

    /* ==========================================================================
       BUTTONS
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

    .btn--warning {
        background: var(--warning);
        color: white;
    }

    .btn--outline {
        background: transparent;
        color: var(--purple);
        border: 2px solid var(--purple-light);
    }

    .btn--lg {
        padding: 1rem 2rem;
        font-size: 1rem;
    }

    .btn--sm {
        padding: 0.5rem 1rem;
        font-size: 0.85rem;
    }

    .btn:disabled {
        opacity: 0.6;
        cursor: not-allowed;
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
        font-size: 3rem;
        font-weight: 700;
        color: var(--purple-deep);
        line-height: 1.2;
        font-family: var(--font-display);
    }

    /* ==========================================================================
       SUPPORT SECTION - FIXED FOR LAPTOP VISIBILITY
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
        min-width: 0;
        width: 100%;
    }

    .support-icon {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        flex-shrink: 0;
        font-size: 1.1rem;
    }

    .support-icon.phone { background: var(--purple); }
    .support-icon.whatsapp { background: #25D366; }
    .support-icon.email { background: var(--danger); }

    .support-content {
        flex: 1;
        min-width: 0;
    }

    .support-label {
        font-size: 0.75rem;
        color: var(--mist);
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 0.2rem;
    }

    .support-value {
        font-size: 1rem;
        font-weight: 500;
        color: var(--ink);
        white-space: normal; /* Allow wrapping on laptop */
        word-break: break-word;
        line-height: 1.4;
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

    @keyframes spinner {
        to { transform: rotate(360deg); }
    }

    /* ==========================================================================
       RESPONSIVE - LAPTOP OPTIMIZATION
       ========================================================================== */
    @media (max-width: 1200px) {
        .fee-amount {
            font-size: 2.5rem;
        }
        
        .support-grid {
            gap: var(--space-sm);
        }
    }

    @media (max-width: 992px) {
        .support-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    @media (max-width: 768px) {
        .support-grid {
            grid-template-columns: 1fr;
        }
        
        .stepper-item .step-name {
            font-size: 0.7rem;
        }
        
        .card-body {
            padding: var(--space-lg);
        }
    }

    @media (max-width: 480px) {
        .fee-amount {
            font-size: 2rem;
        }
        
        .rrr-display {
            font-size: 1.1rem;
        }
        
        .top-bar {
            flex-direction: column;
            align-items: flex-start;
        }
        
        .logout-btn {
            align-self: flex-end;
        }
    }
    </style>
</head>
<body>
    <main id="main-content" class="main-content" role="main">
        <div class="container">
            <div class="row justify-content-center">
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
                                        <button class="btn btn--outline btn--sm" id="copyRRRBtn" onclick="copyRRR()">
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
                                <!-- FIXED: Button ID matches JavaScript -->
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

                            <!-- Support Information - FIXED for laptop visibility -->
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

    <!-- jQuery (required for your JavaScript) -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    
    <!-- Bootstrap JS (optional, for alerts) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Your Payment JavaScript -->
    <script src="/assets/js/Payment.js"></script>
</body>
</html>