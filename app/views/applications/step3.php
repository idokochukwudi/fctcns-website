<?php
/**
 * Payment View - Step 3
 * Premium institutional design with proper Remita integration
 * FIXED: Complete redesign matching model/controller fixes
 * FIXED: Proper payment flow with RRR generation and verification
 * FIXED: Demo card details displayed correctly
 * FIXED: CSP compliance with proper nonce handling
 * FIXED: AJAX error handling for non-JSON responses
 * FIXED: Corrected API endpoints to match router routes
 * FIXED: Using /apply/initiate-payment and /apply/verify-payment endpoints
 * FIXED: Enhanced verification with auto-retry and better error messages
 * 
 * @package FCTCNS
 * @version 3.1 (Production Ready)
 */

// =========================================================
// 1. Security Helpers
// =========================================================
require_once APP_PATH . '/helpers/SecurityHelper.php';
require_once APP_PATH . '/helpers/SecurityTrait.php';

class PaymentView {
    use SecurityTrait;
    
    public function render($data) {
        extract($data);
        
        // Get security tokens
        $csp_nonce = $this->getCspNonce();
        $csrf_token = $this->getCsrfToken();

        if (!function_exists('e')) {
            function e($text) {
                return htmlspecialchars($text ?? '', ENT_QUOTES, 'UTF-8');
            }
        }

        $baseUrl = $baseUrl ?? '/';
        $application = $application ?? [];
        $fee = $fee ?? 2500;
        $currency = $currency ?? '₦';
        $pending_payment = $pending_payment ?? null;
        $environment = $environment ?? 'demo';
        $payment_status = $payment_status ?? null;
        
        $applicant_name = trim(($application['first_name'] ?? '') . ' ' . ($application['last_name'] ?? ''));
        if (empty($applicant_name)) {
            $applicant_name = 'Applicant';
        }
        
        $application_number = $application['application_number'] ?? 'APP-' . str_pad(($application['id'] ?? 0), 5, '0', STR_PAD_LEFT);
        
        // Check if payment already success
        $payment_success = ($payment_status === 'success');
        ?>
        <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <meta name="description" content="Payment - FCT College of Nursing Sciences">
            
            <!-- Security Meta Tags -->
            <?php echo $this->getSecurityMetaTags(); ?>
            
            <!-- CSRF Token -->
            <meta name="csrf-token" content="<?php echo e($csrf_token); ?>">
            
            <title>Payment — FCT College of Nursing Sciences</title>

            <!-- Fonts -->
            <link rel="preconnect" href="https://fonts.googleapis.com">
            <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
            <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
            
            <!-- Font Awesome -->
            <link rel="stylesheet" 
                  href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"
                  integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw=="
                  crossorigin="anonymous" 
                  referrerpolicy="no-referrer">

            <style nonce="<?php echo e($csp_nonce); ?>">
            /* ===== RESET & VARIABLES ===== */
            * {
                margin: 0;
                padding: 0;
                box-sizing: border-box;
            }

            :root {
                /* Purple Theme */
                --primary: #6B4E9B;
                --primary-dark: #4A3B6B;
                --primary-light: #8A6FB0;
                --primary-soft: #F3EAF8;
                
                /* Gold Accents */
                --gold: #C9A44A;
                --gold-light: #E2B05F;
                --gold-soft: #FDF6E9;
                
                /* Status Colors */
                --success: #10b981;
                --success-soft: #d1fae5;
                --danger: #ef4444;
                --danger-soft: #fee2e2;
                --warning: #f59e0b;
                --warning-soft: #fef3c7;
                --info: #3b82f6;
                --info-soft: #dbeafe;
                
                /* Neutrals */
                --white: #FFFFFF;
                --gray-50: #F9FAFB;
                --gray-100: #F3F4F6;
                --gray-200: #E5E7EB;
                --gray-300: #D1D5DB;
                --gray-400: #9CA3AF;
                --gray-500: #6B7280;
                --gray-600: #4B5563;
                --gray-700: #374151;
                --gray-800: #1F2937;
                --gray-900: #111827;
                
                /* Spacing */
                --space-xs: 0.5rem;
                --space-sm: 0.75rem;
                --space-md: 1rem;
                --space-lg: 1.5rem;
                --space-xl: 2rem;
                --space-2xl: 2.5rem;
                
                /* Border Radius */
                --radius-sm: 6px;
                --radius-md: 10px;
                --radius-lg: 16px;
                --radius-xl: 24px;
                
                /* Shadows */
                --shadow-sm: 0 1px 3px rgba(0,0,0,0.1);
                --shadow-md: 0 4px 6px -1px rgba(0,0,0,0.1);
                --shadow-lg: 0 10px 15px -3px rgba(0,0,0,0.1);
                --shadow-purple: 0 4px 14px rgba(107,78,155,0.25);
                
                /* Font */
                --font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
                
                /* Container */
                --container-max: 900px;
            }

            body {
                font-family: var(--font-family);
                background: linear-gradient(135deg, var(--primary-soft) 0%, #f5f0fa 100%);
                color: var(--gray-800);
                min-height: 100vh;
                line-height: 1.5;
                -webkit-font-smoothing: antialiased;
            }

            /* ===== LAYOUT ===== */
            .payment-wrapper {
                min-height: 100vh;
                display: flex;
                align-items: center;
                justify-content: center;
                padding: var(--space-lg);
            }

            .payment-container {
                max-width: var(--container-max);
                width: 100%;
                margin: 0 auto;
            }

            /* ===== CARD ===== */
            .payment-card {
                background: var(--white);
                border-radius: var(--radius-xl);
                box-shadow: var(--shadow-lg), var(--shadow-purple);
                overflow: hidden;
            }

            /* Card Header */
            .payment-header {
                background: linear-gradient(135deg, var(--primary), var(--primary-dark));
                padding: var(--space-xl) var(--space-2xl);
                position: relative;
                overflow: hidden;
            }

            .payment-header::before {
                content: '';
                position: absolute;
                top: -50%;
                right: -10%;
                width: 300px;
                height: 300px;
                border-radius: 50%;
                background: rgba(255,255,255,0.03);
                pointer-events: none;
            }

            .payment-header::after {
                content: '';
                position: absolute;
                bottom: -30%;
                left: -5%;
                width: 200px;
                height: 200px;
                border-radius: 50%;
                background: rgba(201,164,74,0.1);
                pointer-events: none;
            }

            .header-content {
                position: relative;
                z-index: 1;
                display: flex;
                align-items: center;
                gap: var(--space-lg);
            }

            .header-icon {
                width: 64px;
                height: 64px;
                background: rgba(255,255,255,0.1);
                border: 1px solid rgba(255,255,255,0.2);
                border-radius: var(--radius-md);
                display: flex;
                align-items: center;
                justify-content: center;
                color: var(--gold-light);
                font-size: 1.75rem;
                flex-shrink: 0;
            }

            .header-text {
                flex: 1;
            }

            .header-title {
                font-size: 1.75rem;
                font-weight: 600;
                color: white;
                line-height: 1.2;
                margin-bottom: var(--space-xs);
            }

            .header-subtitle {
                color: rgba(255,255,255,0.7);
                font-size: 0.9rem;
                font-weight: 400;
            }

            .app-badge {
                background: rgba(255,255,255,0.1);
                border: 1px solid rgba(255,255,255,0.15);
                border-radius: var(--radius-sm);
                padding: var(--space-sm) var(--space-md);
                text-align: right;
                flex-shrink: 0;
            }

            .badge-label {
                font-size: 0.7rem;
                color: rgba(255,255,255,0.5);
                text-transform: uppercase;
                letter-spacing: 0.5px;
            }

            .badge-value {
                font-size: 0.9rem;
                color: white;
                font-weight: 600;
                font-family: monospace;
            }

            /* Card Body */
            .payment-body {
                padding: var(--space-2xl);
            }

            /* ===== FEE PANEL ===== */
            .fee-panel {
                background: var(--primary-soft);
                border: 1px solid rgba(107,78,155,0.1);
                border-radius: var(--radius-lg);
                padding: var(--space-xl);
                display: flex;
                align-items: center;
                gap: var(--space-xl);
                margin-bottom: var(--space-xl);
            }

            .fee-icon {
                width: 64px;
                height: 64px;
                background: var(--gold-soft);
                border: 1px solid rgba(201,164,74,0.2);
                border-radius: var(--radius-md);
                display: flex;
                align-items: center;
                justify-content: center;
                color: var(--gold);
                font-size: 1.75rem;
                flex-shrink: 0;
            }

            .fee-info {
                flex: 1;
            }

            .fee-label {
                font-size: 0.8rem;
                font-weight: 600;
                text-transform: uppercase;
                letter-spacing: 0.5px;
                color: var(--gray-500);
                margin-bottom: var(--space-xs);
            }

            .fee-amount {
                font-size: 2.5rem;
                font-weight: 700;
                color: var(--primary-dark);
                line-height: 1;
                margin-bottom: var(--space-xs);
            }

            .fee-note {
                font-size: 0.8rem;
                color: var(--gray-500);
                display: flex;
                align-items: center;
                gap: var(--space-xs);
            }

            .fee-badge {
                background: var(--gold-soft);
                border: 1px solid rgba(201,164,74,0.2);
                color: var(--gold);
                padding: var(--space-sm) var(--space-lg);
                border-radius: 100px;
                font-size: 0.85rem;
                font-weight: 600;
                white-space: nowrap;
            }

            /* ===== INSTRUCTIONS ===== */
            .instructions {
                background: var(--gray-50);
                border-radius: var(--radius-lg);
                padding: var(--space-xl);
                margin-bottom: var(--space-xl);
                border: 1px solid var(--gray-200);
            }

            .instructions-title {
                font-size: 1rem;
                font-weight: 600;
                color: var(--gray-700);
                margin-bottom: var(--space-lg);
                display: flex;
                align-items: center;
                gap: var(--space-sm);
            }

            .instructions-title i {
                color: var(--primary);
            }

            .steps {
                display: flex;
                flex-direction: column;
                gap: var(--space-md);
            }

            .step {
                display: flex;
                align-items: flex-start;
                gap: var(--space-md);
            }

            .step-number {
                width: 28px;
                height: 28px;
                background: var(--primary-soft);
                border: 1px solid rgba(107,78,155,0.2);
                border-radius: 50%;
                display: flex;
                align-items: center;
                justify-content: center;
                font-size: 0.85rem;
                font-weight: 600;
                color: var(--primary);
                flex-shrink: 0;
            }

            .step-content {
                flex: 1;
                font-size: 0.95rem;
                color: var(--gray-600);
            }

            .step-content strong {
                color: var(--gray-800);
            }

            .demo-card {
                background: var(--gold-soft);
                border: 1px solid rgba(201,164,74,0.2);
                border-radius: var(--radius-md);
                padding: var(--space-md);
                margin-top: var(--space-sm);
                font-family: monospace;
                font-size: 0.9rem;
                color: var(--gray-700);
            }

            .demo-card i {
                color: var(--gold);
                margin-right: var(--space-sm);
            }

            /* ===== RRR DISPLAY ===== */
            .rrr-section {
                background: var(--primary-soft);
                border-radius: var(--radius-lg);
                padding: var(--space-xl);
                margin-bottom: var(--space-xl);
            }

            .rrr-label {
                font-size: 0.85rem;
                font-weight: 600;
                color: var(--gray-500);
                text-transform: uppercase;
                letter-spacing: 0.5px;
                margin-bottom: var(--space-sm);
            }

            .rrr-box {
                display: flex;
                align-items: center;
                gap: var(--space-md);
                background: white;
                border: 1px solid rgba(107,78,155,0.2);
                border-radius: var(--radius-md);
                padding: var(--space-md) var(--space-lg);
            }

            .rrr-value {
                flex: 1;
                font-family: monospace;
                font-size: 1.5rem;
                font-weight: 600;
                color: var(--primary-dark);
                letter-spacing: 2px;
                word-break: break-all;
            }

            .copy-btn {
                background: var(--primary);
                color: white;
                border: none;
                border-radius: var(--radius-sm);
                padding: var(--space-sm) var(--space-lg);
                font-size: 0.9rem;
                font-weight: 500;
                cursor: pointer;
                display: flex;
                align-items: center;
                gap: var(--space-sm);
                transition: all 0.2s;
                flex-shrink: 0;
            }

            .copy-btn:hover {
                background: var(--primary-dark);
                transform: translateY(-1px);
            }

            .copy-btn:active {
                transform: translateY(0);
            }

            /* ===== PAYMENT BUTTON AREA ===== */
            .payment-action {
                background: var(--warning-soft);
                border: 1px solid rgba(245,158,11,0.2);
                border-radius: var(--radius-lg);
                padding: var(--space-xl);
                margin-bottom: var(--space-xl);
            }

            .action-header {
                display: flex;
                align-items: center;
                gap: var(--space-sm);
                margin-bottom: var(--space-lg);
            }

            .action-header i {
                font-size: 1.25rem;
                color: var(--warning);
            }

            .action-header h4 {
                font-size: 1.1rem;
                font-weight: 600;
                color: var(--gray-800);
            }

            .pay-button {
                display: block;
                width: 100%;
                padding: var(--space-lg);
                background: linear-gradient(135deg, var(--gold), var(--gold-light));
                color: white;
                border: none;
                border-radius: var(--radius-md);
                font-size: 1.1rem;
                font-weight: 600;
                text-align: center;
                text-decoration: none;
                transition: all 0.3s;
                box-shadow: 0 4px 12px rgba(201,164,74,0.3);
            }

            .pay-button:hover {
                transform: translateY(-2px);
                box-shadow: 0 8px 20px rgba(201,164,74,0.4);
            }

            .pay-button:active {
                transform: translateY(0);
            }

            .pay-button i {
                margin-right: var(--space-sm);
            }

            .payment-note {
                font-size: 0.85rem;
                color: var(--gray-600);
                margin-top: var(--space-md);
                text-align: center;
            }

            /* ===== ACTION BUTTONS ===== */
            .action-grid {
                display: flex;
                flex-direction: column;
                gap: var(--space-md);
                margin-bottom: var(--space-xl);
            }

            .btn-primary {
                width: 100%;
                padding: var(--space-lg);
                background: linear-gradient(135deg, var(--primary), var(--primary-dark));
                color: white;
                border: none;
                border-radius: var(--radius-md);
                font-size: 1rem;
                font-weight: 600;
                cursor: pointer;
                display: flex;
                align-items: center;
                justify-content: center;
                gap: var(--space-sm);
                transition: all 0.3s;
                box-shadow: var(--shadow-purple);
            }

            .btn-primary:hover:not(:disabled) {
                transform: translateY(-2px);
                box-shadow: 0 8px 20px rgba(107,78,155,0.4);
            }

            .btn-primary:disabled {
                opacity: 0.6;
                cursor: not-allowed;
            }

            .btn-success {
                width: 100%;
                padding: var(--space-lg);
                background: var(--success);
                color: white;
                border: none;
                border-radius: var(--radius-md);
                font-size: 1rem;
                font-weight: 600;
                cursor: pointer;
                display: flex;
                align-items: center;
                justify-content: center;
                gap: var(--space-sm);
                transition: all 0.3s;
            }

            .btn-success:hover:not(:disabled) {
                background: #0d9488;
                transform: translateY(-2px);
                box-shadow: 0 8px 20px rgba(16,185,129,0.3);
            }

            .btn-success:disabled {
                opacity: 0.6;
                cursor: not-allowed;
            }

            .btn-outline {
                padding: var(--space-md) var(--space-lg);
                background: transparent;
                color: var(--primary);
                border: 2px solid var(--primary-soft);
                border-radius: var(--radius-md);
                font-size: 0.95rem;
                font-weight: 500;
                cursor: pointer;
                display: inline-flex;
                align-items: center;
                justify-content: center;
                gap: var(--space-sm);
                transition: all 0.2s;
                text-decoration: none;
            }

            .btn-outline:hover {
                border-color: var(--primary);
                background: var(--primary-soft);
            }

            .action-row {
                display: flex;
                justify-content: space-between;
                align-items: center;
                gap: var(--space-md);
            }

            /* ===== STATUS AREA ===== */
            .status-area {
                background: var(--gray-50);
                border: 1px solid var(--gray-200);
                border-radius: var(--radius-lg);
                padding: var(--space-xl);
                text-align: center;
                margin-bottom: var(--space-xl);
            }

            .spinner {
                width: 48px;
                height: 48px;
                border: 4px solid var(--gray-200);
                border-top-color: var(--primary);
                border-radius: 50%;
                animation: spin 1s linear infinite;
                margin: 0 auto var(--space-lg);
            }

            @keyframes spin {
                to { transform: rotate(360deg); }
            }

            .status-message {
                font-size: 1rem;
                color: var(--gray-600);
            }

            /* ===== ALERTS ===== */
            .alert-container {
                margin-bottom: var(--space-lg);
            }

            .alert {
                border-radius: var(--radius-md);
                padding: var(--space-md) var(--space-lg);
                font-size: 0.95rem;
                display: flex;
                align-items: flex-start;
                gap: var(--space-sm);
                animation: slideIn 0.3s ease;
                margin-bottom: var(--space-sm);
            }

            @keyframes slideIn {
                from {
                    opacity: 0;
                    transform: translateY(-10px);
                }
                to {
                    opacity: 1;
                    transform: translateY(0);
                }
            }

            .alert i {
                font-size: 1.1rem;
                margin-top: 2px;
            }

            .alert-success {
                background: var(--success-soft);
                border-left: 4px solid var(--success);
                color: #065f46;
            }

            .alert-danger {
                background: var(--danger-soft);
                border-left: 4px solid var(--danger);
                color: #991b1b;
            }

            .alert-warning {
                background: var(--warning-soft);
                border-left: 4px solid var(--warning);
                color: #92400e;
            }

            .alert-info {
                background: var(--info-soft);
                border-left: 4px solid var(--info);
                color: #1e3a8a;
            }

            /* ===== SUPPORT SECTION ===== */
            .support-section {
                margin-top: var(--space-xl);
            }

            .support-title {
                font-size: 0.85rem;
                font-weight: 600;
                text-transform: uppercase;
                letter-spacing: 0.5px;
                color: var(--gray-500);
                margin-bottom: var(--space-lg);
                text-align: center;
            }

            .support-grid {
                display: grid;
                grid-template-columns: repeat(3, 1fr);
                gap: var(--space-md);
            }

            .support-item {
                background: var(--gray-50);
                border: 1px solid var(--gray-200);
                border-radius: var(--radius-md);
                padding: var(--space-md);
                display: flex;
                align-items: center;
                gap: var(--space-sm);
                transition: all 0.2s;
            }

            .support-item:hover {
                border-color: var(--primary-light);
                box-shadow: var(--shadow-sm);
            }

            .support-icon {
                width: 40px;
                height: 40px;
                border-radius: var(--radius-sm);
                display: flex;
                align-items: center;
                justify-content: center;
                color: white;
                font-size: 1.1rem;
                flex-shrink: 0;
            }

            .support-icon.phone { background: var(--primary); }
            .support-icon.whatsapp { background: #25D366; }
            .support-icon.email { background: var(--danger); }

            .support-details {
                flex: 1;
            }

            .support-label {
                font-size: 0.7rem;
                font-weight: 600;
                text-transform: uppercase;
                color: var(--gray-500);
                margin-bottom: 2px;
            }

            .support-value {
                font-size: 0.9rem;
                font-weight: 500;
                color: var(--gray-800);
            }

            /* ===== FOOTER ===== */
            .payment-footer {
                padding: var(--space-lg) var(--space-2xl);
                background: var(--gray-50);
                border-top: 1px solid var(--gray-200);
                display: flex;
                align-items: center;
                justify-content: center;
                gap: var(--space-sm);
                font-size: 0.85rem;
                color: var(--gray-500);
            }

            .payment-footer i {
                color: var(--gold);
            }

            /* ===== RESPONSIVE ===== */
            @media (max-width: 768px) {
                .payment-header {
                    padding: var(--space-lg);
                }

                .header-content {
                    flex-direction: column;
                    align-items: flex-start;
                }

                .app-badge {
                    align-self: flex-start;
                }

                .payment-body {
                    padding: var(--space-lg);
                }

                .fee-panel {
                    flex-direction: column;
                    align-items: flex-start;
                }

                .fee-amount {
                    font-size: 2rem;
                }

                .fee-badge {
                    align-self: flex-start;
                }

                .rrr-box {
                    flex-direction: column;
                    align-items: stretch;
                }

                .copy-btn {
                    justify-content: center;
                }

                .support-grid {
                    grid-template-columns: 1fr;
                }

                .action-row {
                    flex-direction: column;
                }

                .btn-outline {
                    width: 100%;
                    justify-content: center;
                }
            }

            @media (max-width: 480px) {
                .payment-wrapper {
                    padding: var(--space-sm);
                }

                .header-title {
                    font-size: 1.5rem;
                }

                .step {
                    flex-direction: column;
                    gap: var(--space-xs);
                }

                .step-number {
                    margin-bottom: var(--space-xs);
                }
            }
            </style>
        </head>
        <body>
            <div class="payment-wrapper">
                <div class="payment-container">
                    
                    <!-- Alert Container -->
                    <div id="alertContainer" class="alert-container"></div>

                    <!-- Main Card -->
                    <div class="payment-card">
                        
                        <!-- Header -->
                        <div class="payment-header">
                            <div class="header-content">
                                <div class="header-icon">
                                    <i class="fas fa-credit-card"></i>
                                </div>
                                <div class="header-text">
                                    <h1 class="header-title">Application Payment</h1>
                                    <p class="header-subtitle">Complete your payment to proceed with application</p>
                                </div>
                                <div class="app-badge">
                                    <div class="badge-label">Application #</div>
                                    <div class="badge-value"><?php echo e($application_number); ?></div>
                                </div>
                            </div>
                        </div>

                        <!-- Body -->
                        <div class="payment-body">
                            
                            <!-- CSRF Token -->
                            <input type="hidden" id="csrf_token" value="<?php echo e($csrf_token); ?>">
                            
                            <!-- Fee Panel -->
                            <div class="fee-panel">
                                <div class="fee-icon">
                                    <i class="fas fa-naira-sign"></i>
                                </div>
                                <div class="fee-info">
                                    <div class="fee-label">Application Fee</div>
                                    <div class="fee-amount"><?php echo e($currency); ?><?php echo e(number_format($fee)); ?></div>
                                    <div class="fee-note">
                                        <i class="fas fa-info-circle"></i>
                                        This fee is non-refundable once payment is confirmed
                                    </div>
                                </div>
                                <div class="fee-badge">
                                    <i class="fas fa-shield-alt"></i>
                                    Secure Payment
                                </div>
                            </div>

                            <!-- Instructions with Demo Card Details -->
                            <div class="instructions">
                                <div class="instructions-title">
                                    <i class="fas fa-list-ol"></i>
                                    Payment Instructions
                                </div>
                                <div class="steps">
                                    <div class="step">
                                        <span class="step-number">1</span>
                                        <div class="step-content">
                                            Click <strong>Generate RRR</strong> to create your unique Remita Retrieval Reference number
                                        </div>
                                    </div>
                                    <div class="step">
                                        <span class="step-number">2</span>
                                        <div class="step-content">
                                            Click the <strong>Complete Payment on Remita</strong> button that appears
                                        </div>
                                    </div>
                                    <div class="step">
                                        <span class="step-number">3</span>
                                        <div class="step-content">
                                            On Remita demo page, use these test card details:
                                            <div class="demo-card">
                                                <i class="fas fa-credit-card"></i>
                                                <strong>Card:</strong> 5178 6810 0000 0002<br>
                                                <i class="fas fa-calendar"></i>
                                                <strong>Expiry:</strong> 05/30 &nbsp;&nbsp; 
                                                <i class="fas fa-lock"></i>
                                                <strong>CVV:</strong> 000<br>
                                                <i class="fas fa-key"></i>
                                                <strong>OTP:</strong> 123456
                                            </div>
                                        </div>
                                    </div>
                                    <div class="step">
                                        <span class="step-number">4</span>
                                        <div class="step-content">
                                            After payment, return here and click <strong>I've Paid — Verify Payment</strong>
                                        </div>
                                    </div>
                                    <div class="step">
                                        <span class="step-number">5</span>
                                        <div class="step-content">
                                            Your exam slip will be generated immediately after successful verification
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- RRR Display Area (hidden initially) -->
                            <div id="rrrSection" class="rrr-section" style="display: none;">
                                <div class="rrr-label">
                                    <i class="fas fa-receipt"></i>
                                    Remita Retrieval Reference (RRR)
                                </div>
                                <div class="rrr-box">
                                    <span id="rrrValue" class="rrr-value"></span>
                                    <button id="copyRRRBtn" class="copy-btn">
                                        <i class="fas fa-copy"></i>
                                        Copy
                                    </button>
                                </div>
                                <p style="font-size: 0.85rem; color: var(--gray-500); margin-top: var(--space-sm);">
                                    <i class="fas fa-info-circle"></i>
                                    Save this RRR for future reference
                                </p>
                            </div>

                            <!-- Payment Action Area (hidden initially) -->
                            <div id="paymentAction" class="payment-action" style="display: none;">
                                <div class="action-header">
                                    <i class="fas fa-external-link-alt"></i>
                                    <h4>Proceed to Payment</h4>
                                </div>
                                <a href="#" id="remitaPaymentLink" target="_blank" class="pay-button">
                                    <i class="fas fa-credit-card"></i>
                                    Complete Payment on Remita
                                </a>
                                <p class="payment-note">
                                    After completing payment, return here and click "Verify Payment" below
                                </p>
                            </div>

                            <!-- Status Area (hidden initially) -->
                            <div id="statusArea" class="status-area" style="display: none;">
                                <div id="statusSpinner" class="spinner"></div>
                                <div id="statusMessage" class="status-message">Processing...</div>
                            </div>

                            <!-- Action Buttons -->
                            <div class="action-grid">
                                <button id="generateRRRBtn" class="btn-primary">
                                    <i class="fas fa-bolt"></i>
                                    Generate RRR
                                </button>
                                
                                <button id="verifyPaymentBtn" class="btn-success" style="display: none;">
                                    <i class="fas fa-check-circle"></i>
                                    I've Paid — Verify Payment
                                </button>
                                
                                <div class="action-row">
                                    <a href="/apply/form" class="btn-outline">
                                        <i class="fas fa-arrow-left"></i>
                                        Back to Form
                                    </a>
                                    <button id="checkStatusBtn" class="btn-outline" style="display: none;">
                                        <i class="fas fa-sync-alt"></i>
                                        Check Status
                                    </button>
                                </div>
                            </div>

                            <!-- Support Section -->
                            <div class="support-section">
                                <div class="support-title">Need Help With Payment?</div>
                                <div class="support-grid">
                                    <div class="support-item">
                                        <div class="support-icon phone">
                                            <i class="fas fa-phone"></i>
                                        </div>
                                        <div class="support-details">
                                            <div class="support-label">Phone</div>
                                            <div class="support-value">07039837749</div>
                                        </div>
                                    </div>
                                    <div class="support-item">
                                        <div class="support-icon whatsapp">
                                            <i class="fab fa-whatsapp"></i>
                                        </div>
                                        <div class="support-details">
                                            <div class="support-label">WhatsApp</div>
                                            <div class="support-value">08082775076</div>
                                        </div>
                                    </div>
                                    <div class="support-item">
                                        <div class="support-icon email">
                                            <i class="fas fa-envelope"></i>
                                        </div>
                                        <div class="support-details">
                                            <div class="support-label">Email</div>
                                            <div class="support-value">support@fctcns.edu.ng</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Footer -->
                        <div class="payment-footer">
                            <i class="fas fa-lock"></i>
                            <span>Secured by <strong>Remita Payment Gateway</strong></span>
                            <?php if ($environment === 'demo'): ?>
                            <span class="badge" style="background: var(--gold); color: white; padding: 2px 8px; border-radius: 12px; font-size: 0.7rem; margin-left: var(--space-sm);">
                                DEMO MODE
                            </span>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>

            <!-- JavaScript with proper CSP nonce -->
            <script nonce="<?php echo e($csp_nonce); ?>">
            (function() {
                'use strict';

                // ===== Configuration =====
                const CONFIG = {
                    pendingRRR: <?php echo isset($pending_payment['rrr']) ? json_encode($pending_payment['rrr']) : 'null'; ?>,
                    environment: '<?php echo e($environment); ?>',
                    // CORRECT ENDPOINTS - matches router routes
                    initiateEndpoint: '/apply/initiate-payment',
                    verifyEndpoint: '/apply/verify-payment',
                    statusEndpoint: '/payment/check-status',
                    demoPaymentUrl: 'https://demo.remita.net/remita/onepage/payment/init.reg'
                };

                // ===== State =====
                let currentRRR = '';
                let verificationInProgress = false;

                // ===== DOM Elements =====
                const elements = {
                    generateBtn: document.getElementById('generateRRRBtn'),
                    verifyBtn: document.getElementById('verifyPaymentBtn'),
                    checkStatusBtn: document.getElementById('checkStatusBtn'),
                    copyBtn: document.getElementById('copyRRRBtn'),
                    rrrSection: document.getElementById('rrrSection'),
                    paymentAction: document.getElementById('paymentAction'),
                    rrrValue: document.getElementById('rrrValue'),
                    remitaLink: document.getElementById('remitaPaymentLink'),
                    statusArea: document.getElementById('statusArea'),
                    statusSpinner: document.getElementById('statusSpinner'),
                    statusMessage: document.getElementById('statusMessage'),
                    alertContainer: document.getElementById('alertContainer')
                };

                // ===== Utility Functions =====
                function getCsrfToken() {
                    return document.getElementById('csrf_token')?.value || '';
                }

                function showAlert(message, type = 'info') {
                    if (!elements.alertContainer) return;
                    
                    const icons = {
                        success: 'check-circle',
                        danger: 'exclamation-circle',
                        warning: 'exclamation-triangle',
                        info: 'info-circle'
                    };
                    
                    const alert = document.createElement('div');
                    alert.className = `alert alert-${type}`;
                    alert.innerHTML = `<i class="fas fa-${icons[type] || 'info-circle'}"></i>${message}`;
                    
                    elements.alertContainer.appendChild(alert);
                    
                    setTimeout(() => {
                        if (alert.parentNode) alert.remove();
                    }, 6000);
                }

                function copyToClipboard(text) {
                    if (navigator.clipboard && navigator.clipboard.writeText) {
                        navigator.clipboard.writeText(text).then(() => {
                            showAlert('RRR copied to clipboard!', 'success');
                        }).catch(() => fallbackCopy(text));
                    } else {
                        fallbackCopy(text);
                    }
                }

                function fallbackCopy(text) {
                    const textarea = document.createElement('textarea');
                    textarea.value = text;
                    textarea.style.cssText = 'position:fixed;opacity:0;top:0;left:0';
                    document.body.appendChild(textarea);
                    textarea.select();
                    
                    try {
                        document.execCommand('copy');
                        showAlert('RRR copied to clipboard!', 'success');
                    } catch (err) {
                        showAlert('Could not copy RRR', 'danger');
                    }
                    
                    document.body.removeChild(textarea);
                }

                function showStatus(show = true, message = 'Processing...') {
                    if (elements.statusArea) {
                        elements.statusArea.style.display = show ? 'block' : 'none';
                    }
                    if (elements.statusMessage) {
                        elements.statusMessage.textContent = message;
                    }
                }

                function showPaymentUI(rrr, paymentUrl = null) {
                    currentRRR = rrr;
                    
                    // Show RRR section
                    if (elements.rrrSection) {
                        elements.rrrSection.style.display = 'block';
                    }
                    if (elements.rrrValue) {
                        elements.rrrValue.textContent = rrr;
                    }
                    
                    // Build and set payment link
                    const url = paymentUrl || `${CONFIG.demoPaymentUrl}?rrr=${encodeURIComponent(rrr)}&channel=CARD,USSD,ENAIRA,TRANSFER`;
                    if (elements.remitaLink) {
                        elements.remitaLink.href = url;
                    }
                    
                    // Show payment action and verify button
                    if (elements.paymentAction) {
                        elements.paymentAction.style.display = 'block';
                    }
                    if (elements.verifyBtn) {
                        elements.verifyBtn.style.display = 'flex';
                    }
                    if (elements.checkStatusBtn) {
                        elements.checkStatusBtn.style.display = 'inline-flex';
                    }
                    
                    // Hide generate button
                    if (elements.generateBtn) {
                        elements.generateBtn.style.display = 'none';
                    }
                    
                    // Scroll to payment action
                    if (elements.paymentAction) {
                        elements.paymentAction.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    }
                }

                // ===== API Calls =====
                async function generateRRR() {
                    if (!elements.generateBtn || verificationInProgress) return;
                    
                    const btn = elements.generateBtn;
                    btn.disabled = true;
                    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Generating...';
                    
                    showStatus(true, 'Generating RRR, please wait...');
                    
                    try {
                        const response = await fetch(CONFIG.initiateEndpoint, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-Requested-With': 'XMLHttpRequest'
                            },
                            body: JSON.stringify({ csrf_token: getCsrfToken() })
                        });
                        
                        // Check content type
                        const contentType = response.headers.get('content-type') || '';
                        
                        if (!response.ok) {
                            const text = await response.text();
                            console.error('Server error response:', text.substring(0, 200));
                            throw new Error(`Server error (${response.status})`);
                        }
                        
                        if (!contentType.includes('application/json')) {
                            const text = await response.text();
                            console.error('Non-JSON response:', text.substring(0, 200));
                            throw new Error('Server returned invalid response format');
                        }
                        
                        const data = await response.json();
                        
                        showStatus(false);
                        
                        if (data.success) {
                            showAlert('RRR generated successfully!', 'success');
                            showPaymentUI(data.rrr, data.payment_url);
                            
                            // Auto-open payment in new tab after a brief delay
                            setTimeout(() => {
                                const url = data.payment_url || `${CONFIG.demoPaymentUrl}?rrr=${encodeURIComponent(data.rrr)}`;
                                window.open(url, '_blank');
                            }, 800);
                        } else {
                            btn.disabled = false;
                            btn.innerHTML = '<i class="fas fa-bolt"></i> Generate RRR';
                            showAlert(data.message || 'Failed to generate RRR', 'danger');
                        }
                    } catch (error) {
                        console.error('Generate error:', error);
                        btn.disabled = false;
                        btn.innerHTML = '<i class="fas fa-bolt"></i> Generate RRR';
                        showStatus(false);
                        showAlert(error.message, 'danger');
                    }
                }

                async function verifyPayment(rrr, retryCount = 0) {
                    if (!rrr) {
                        showAlert('No RRR found to verify', 'warning');
                        return;
                    }
                    
                    if (verificationInProgress) {
                        showAlert('Verification already in progress', 'info');
                        return;
                    }
                    
                    verificationInProgress = true;
                    
                    showStatus(true, 'Verifying payment with Remita...');
                    
                    if (elements.verifyBtn) {
                        elements.verifyBtn.disabled = true;
                        elements.verifyBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Verifying...';
                    }
                    
                    try {
                        const response = await fetch(CONFIG.verifyEndpoint, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-Requested-With': 'XMLHttpRequest'
                            },
                            body: JSON.stringify({
                                rrr: rrr,
                                csrf_token: getCsrfToken()
                            })
                        });
                        
                        const contentType = response.headers.get('content-type') || '';
                        
                        if (!response.ok) {
                            throw new Error(`Server error (${response.status})`);
                        }
                        
                        if (!contentType.includes('application/json')) {
                            const text = await response.text();
                            console.error('Non-JSON response:', text.substring(0, 200));
                            throw new Error('Server returned invalid response format');
                        }
                        
                        const data = await response.json();
                        
                        showStatus(false);
                        
                        if (elements.verifyBtn) {
                            elements.verifyBtn.disabled = false;
                            elements.verifyBtn.innerHTML = '<i class="fas fa-check-circle"></i> I\'ve Paid — Verify Payment';
                        }
                        
                        if (data.success) {
                            showAlert('✅ Payment verified successfully! Redirecting...', 'success');
                            setTimeout(() => {
                                window.location.href = data.redirect || '/apply/step/4';
                            }, 1500);
                        } else {
                            if (data.pending) {
                                showAlert('⏳ Payment is still pending. Please complete payment on Remita.', 'warning');
                                
                                // Offer to retry after 5 seconds
                                if (retryCount < 2) {
                                    setTimeout(() => {
                                        if (confirm('Payment still pending. Would you like to check again?')) {
                                            verifyPayment(rrr, retryCount + 1);
                                        }
                                    }, 5000);
                                }
                            } else {
                                showAlert(data.message || 'Payment not confirmed', 'danger');
                            }
                        }
                    } catch (error) {
                        console.error('Verify error:', error);
                        showStatus(false);
                        if (elements.verifyBtn) {
                            elements.verifyBtn.disabled = false;
                            elements.verifyBtn.innerHTML = '<i class="fas fa-check-circle"></i> I\'ve Paid — Verify Payment';
                        }
                        showAlert(error.message, 'danger');
                    } finally {
                        verificationInProgress = false;
                    }
                }

                // ===== Event Listeners =====
                if (elements.generateBtn) {
                    elements.generateBtn.addEventListener('click', generateRRR);
                }
                
                if (elements.verifyBtn) {
                    elements.verifyBtn.addEventListener('click', () => {
                        const rrr = currentRRR || (elements.rrrValue ? elements.rrrValue.textContent.trim() : '');
                        verifyPayment(rrr);
                    });
                }
                
                if (elements.checkStatusBtn) {
                    elements.checkStatusBtn.addEventListener('click', () => {
                        const rrr = currentRRR || (elements.rrrValue ? elements.rrrValue.textContent.trim() : '');
                        verifyPayment(rrr);
                    });
                }
                
                if (elements.copyBtn) {
                    elements.copyBtn.addEventListener('click', () => {
                        const rrr = currentRRR || (elements.rrrValue ? elements.rrrValue.textContent : '');
                        if (rrr) copyToClipboard(rrr);
                    });
                }

                // ===== Initialize on Page Load =====
                document.addEventListener('DOMContentLoaded', () => {
                    // Check for pending payment from session
                    if (CONFIG.pendingRRR) {
                        showPaymentUI(CONFIG.pendingRRR);
                        
                        // Show info alert
                        showAlert('You have a pending payment. Complete it to continue.', 'info');
                        
                        // Check URL for RRR parameter (from redirect)
                        const urlParams = new URLSearchParams(window.location.search);
                        const rrrParam = urlParams.get('rrr');
                        
                        if (rrrParam && rrrParam === CONFIG.pendingRRR) {
                            // Auto-verify after returning from Remita
                            setTimeout(() => {
                                verifyPayment(rrrParam);
                            }, 1000);
                        }
                    }
                });

            })();
            </script>
        </body>
        </html>
        <?php
    }
}

// ===== Render the view =====
$view = new PaymentView();
$view->render(get_defined_vars());
?>