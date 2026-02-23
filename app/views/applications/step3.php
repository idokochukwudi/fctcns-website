<?php
/**
 * Payment View - Step 3
 * Redesigned: Premium institutional design
 * UPDATED: Purple color scheme matching JAMB verification page
 * FIXED: Removed inline event handlers, fixed CSP violations, proper SRI hashes
 * 
 * @package FCTCNS
 * @version 2.7 (Security Enhanced + CSP Compliant)
 */

// =========================================================
// 1. Add required helpers at the top of each view file
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
            
            <!-- ========================================================= -->
            <!-- 2. Add security meta tags in the head -->
            <!-- ========================================================= -->
            <?php echo $this->getSecurityMetaTags(); ?>
            
            <!-- ========================================================= -->
            <!-- 3. Add CSRF meta tag for JavaScript -->
            <!-- ========================================================= -->
            <meta name="csrf-token" content="<?php echo $this->e($csrf_token); ?>">
            
            <title>Payment — FCT College of Nursing Sciences</title>

            <!-- Fonts -->
            <link rel="preconnect" href="https://fonts.googleapis.com">
            <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
            
            <!-- ========================================================= -->
            <!-- 4. Add CSP nonce to all style tags -->
            <!-- 5. Add SRI hashes to external scripts/styles -->
            <!-- ========================================================= -->
            <link href="https://fonts.googleapis.com/css2?family=DM+Serif+Display:ital@0;1&family=DM+Sans:wght@300;400;500;600&family=DM+Mono:wght@400;500&display=swap" 
                  rel="stylesheet">
            
            <link rel="stylesheet" 
                  href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"
                  integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw=="
                  crossorigin="anonymous" 
                  referrerpolicy="no-referrer">

            <style nonce="<?php echo $csp_nonce; ?>">
            /* ─── Reset ─── */
            *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
            html { scroll-behavior: smooth; }

            /* ─── Tokens - Purple Theme Matching JAMB Page ─── */
            :root {
                --sv1-primary:       #6B4E9B;
                --sv1-primary-dark:  #4A3B6B;
                --sv1-primary-light: #8A6FB0;
                --sv1-primary-soft:  #F3EAF8;
                --sv1-gold:          #C9A44A;
                --sv1-gold-light:    #E2B05F;
                --sv1-gold-pale:     #FDF6E9;

                --amber:       #D4860B;
                --amber-light: #F2A830;
                --amber-pale:  #FEF5E4;

                --white:  #FFFFFF;
                --grey-1: #F4F6FB;
                --grey-2: #E8ECF5;
                --grey-3: #C5CEDF;
                --grey-4: #8695AE;
                --grey-5: #4A5568;
                --ink:    #1A2438;

                --green:       #10b981;
                --green-pale:  #d1fae5;
                --red:         #ef4444;
                --red-pale:    #fee2e2;
                --orange:      #f59e0b;
                --orange-pale: #fef3c7;
                --blue:        #3b82f6;
                --blue-pale:   #dbeafe;

                --r-sm: 8px;
                --r-md: 14px;
                --r-lg: 20px;
                --r-xl: 28px;

                --sh-sm:  0 1px 4px rgba(107,78,155,.06), 0 2px 12px rgba(107,78,155,.04);
                --sh-md:  0 4px 16px rgba(107,78,155,.08), 0 1px 4px rgba(107,78,155,.04);
                --sh-lg:  0 12px 40px rgba(107,78,155,.10), 0 4px 12px rgba(107,78,155,.06);

                --font-display: 'DM Serif Display', Georgia, serif;
                --font-body:    'DM Sans', system-ui, sans-serif;
                --font-mono:    'DM Mono', monospace;

                --max-w: 900px;
                --gap:   clamp(1rem, 3vw, 2rem);
            }

            /* ─── Base ─── */
            body {
                font-family: var(--font-body);
                background: var(--sv1-primary-soft);
                color: var(--ink);
                min-height: 100vh;
                -webkit-font-smoothing: antialiased;
            }

            /* ─── Page Shell ─── */
            .page-shell {
                min-height: 100vh;
            }

            /* ─── Main ─── */
            .main {
                padding: 2.5rem var(--gap) 4rem;
            }
            .main-inner {
                max-width: var(--max-w);
                margin: 0 auto;
                display: flex;
                flex-direction: column;
                gap: 1.75rem;
            }

            /* ─── Card ─── */
            .card {
                background: var(--white);
                border-radius: var(--r-xl);
                box-shadow: var(--sh-lg);
                overflow: hidden;
            }

            /* Card Header - Purple Gradient */
            .card-head {
                background: linear-gradient(135deg, var(--sv1-primary), var(--sv1-primary-dark));
                padding: 2rem 2.5rem;
                position: relative;
                overflow: hidden;
            }
            .card-head::after {
                content: '';
                position: absolute;
                right: -40px; top: -40px;
                width: 220px; height: 220px;
                border-radius: 50%;
                background: rgba(255,255,255,0.03);
                pointer-events: none;
            }
            .card-head::before {
                content: '';
                position: absolute;
                right: 60px; bottom: -60px;
                width: 160px; height: 160px;
                border-radius: 50%;
                background: rgba(201,164,74,0.08);
                pointer-events: none;
            }
            .card-head-content {
                position: relative;
                z-index: 1;
                display: flex;
                align-items: center;
                gap: 1.25rem;
            }
            .card-head-icon {
                width: 52px; height: 52px;
                background: rgba(201,164,74,0.15);
                border: 1px solid rgba(201,164,74,0.25);
                border-radius: var(--r-md);
                display: flex; align-items: center; justify-content: center;
                color: var(--sv1-gold-light);
                font-size: 1.3rem;
                flex-shrink: 0;
            }
            .card-head-text {}
            .card-head-title {
                font-family: var(--font-display);
                font-size: 1.6rem;
                color: white;
                font-weight: 400;
                line-height: 1.1;
            }
            .card-head-sub {
                color: rgba(255,255,255,0.7);
                font-size: 0.82rem;
                margin-top: 4px;
                font-weight: 400;
            }
            .app-badge {
                margin-left: auto;
                background: rgba(255,255,255,0.06);
                border: 1px solid rgba(255,255,255,0.1);
                border-radius: var(--r-sm);
                padding: 6px 12px;
                text-align: right;
                flex-shrink: 0;
            }
            .app-badge-label {
                font-size: 0.65rem;
                color: rgba(255,255,255,0.45);
                text-transform: uppercase;
                letter-spacing: .8px;
            }
            .app-badge-value {
                font-family: var(--font-mono);
                font-size: 0.82rem;
                color: rgba(255,255,255,0.8);
                font-weight: 500;
            }

            /* Card Body */
            .card-body {
                padding: 2.5rem;
            }

            /* ─── Fee Panel ─── */
            .fee-panel {
                background: var(--sv1-primary-soft);
                border: 1px solid var(--sv1-border);
                border-radius: var(--r-lg);
                padding: 2rem;
                display: flex;
                align-items: center;
                gap: 2rem;
                margin-bottom: 2rem;
            }
            .fee-icon {
                width: 56px; height: 56px;
                background: var(--sv1-gold-pale);
                border: 1px solid rgba(201,164,74,0.2);
                border-radius: var(--r-md);
                display: flex; align-items: center; justify-content: center;
                color: var(--sv1-gold);
                font-size: 1.4rem;
                flex-shrink: 0;
            }
            .fee-info { flex: 1; }
            .fee-label {
                font-size: 0.75rem;
                font-weight: 600;
                text-transform: uppercase;
                letter-spacing: .8px;
                color: var(--grey-4);
                margin-bottom: 4px;
            }
            .fee-amount {
                font-family: var(--font-display);
                font-size: 2.6rem;
                color: var(--sv1-primary-dark);
                line-height: 1;
                font-weight: 400;
            }
            .fee-note {
                font-size: 0.78rem;
                color: var(--grey-4);
                margin-top: 6px;
            }
            .fee-badge {
                background: var(--sv1-gold-pale);
                border: 1px solid rgba(201,164,74,0.2);
                color: var(--sv1-gold);
                padding: 6px 14px;
                border-radius: 100px;
                font-size: 0.78rem;
                font-weight: 600;
                white-space: nowrap;
            }

            /* ─── Instructions ─── */
            .instructions-block {
                margin-bottom: 2rem;
            }
            .instructions-title {
                font-size: 0.78rem;
                font-weight: 600;
                text-transform: uppercase;
                letter-spacing: .8px;
                color: var(--grey-4);
                margin-bottom: 1rem;
            }
            .steps-list {
                list-style: none;
                display: flex;
                flex-direction: column;
                gap: .6rem;
            }
            .steps-list li {
                display: flex;
                align-items: flex-start;
                gap: .75rem;
                font-size: 0.9rem;
                color: var(--grey-5);
                line-height: 1.5;
            }
            .step-num {
                width: 22px; height: 22px;
                border-radius: 50%;
                background: var(--sv1-primary-soft);
                border: 1px solid var(--sv1-border);
                display: flex; align-items: center; justify-content: center;
                font-size: 0.7rem;
                font-weight: 700;
                color: var(--sv1-primary);
                flex-shrink: 0;
                margin-top: 1px;
            }

            /* ─── Alert ─── */
            #alertContainer { display: flex; flex-direction: column; gap: .75rem; }
            .alert {
                border-radius: var(--r-md);
                padding: 1rem 1.25rem;
                font-size: 0.88rem;
                display: flex;
                align-items: flex-start;
                gap: .75rem;
                border-left-width: 4px;
                border-left-style: solid;
                animation: fadeSlideIn .3s ease;
            }
            .alert i { font-size: 1rem; margin-top: 1px; flex-shrink: 0; }
            .alert-success { 
                background: var(--green-pale); 
                border-left-color: var(--green); 
                color: #065f46; 
            }
            .alert-danger   { 
                background: var(--red-pale);   
                border-left-color: var(--red); 
                color: #991b1b; 
            }
            .alert-warning  { 
                background: var(--orange-pale); 
                border-left-color: var(--orange); 
                color: #92400e; 
            }
            .alert-info     { 
                background: var(--blue-pale);   
                border-left-color: var(--blue); 
                color: #1e3a8a; 
            }

            /* ─── Pending Payment - handled by JS now, but keep styles for potential use */
            .pending-box {
                border: 1px solid rgba(201,164,74,0.3);
                background: var(--sv1-gold-pale);
                border-radius: var(--r-lg);
                padding: 1.5rem;
                margin-bottom: 2rem;
            }
            .pending-box-header {
                display: flex;
                align-items: center;
                gap: .75rem;
                margin-bottom: 1rem;
            }
            .pending-icon {
                width: 36px; height: 36px;
                background: rgba(201,164,74,0.15);
                border-radius: 8px;
                display: flex; align-items: center; justify-content: center;
                color: var(--sv1-gold);
                font-size: 1rem;
            }
            .pending-title { 
                font-weight: 600; 
                color: var(--sv1-primary-dark); 
                font-size: 0.95rem; 
            }
            .pending-sub   { 
                font-size: 0.8rem; 
                color: var(--sv1-text-muted); 
            }

            /* ─── RRR Box ─── */
            .rrr-box {
                background: var(--sv1-primary-soft);
                border: 1px dashed var(--sv1-primary-light);
                border-radius: var(--r-md);
                padding: 1rem 1.25rem;
                display: flex;
                align-items: center;
                justify-content: space-between;
                gap: 1rem;
                margin-bottom: 1.25rem;
            }
            .rrr-value {
                font-family: var(--font-mono);
                font-size: 1.35rem;
                font-weight: 500;
                color: var(--sv1-primary-dark);
                letter-spacing: 3px;
                word-break: break-all;
            }
            .rrr-copy-btn {
                background: var(--sv1-primary);
                color: white;
                border: none;
                border-radius: var(--r-sm);
                padding: 7px 14px;
                font-size: 0.78rem;
                font-family: var(--font-body);
                font-weight: 500;
                cursor: pointer;
                display: flex; align-items: center; gap: 6px;
                transition: background .2s;
                flex-shrink: 0;
            }
            .rrr-copy-btn:hover { background: var(--sv1-primary-dark); }

            /* ─── Buttons - Purple Theme ─── */
            .btn-primary {
                width: 100%;
                padding: 1rem 1.5rem;
                background: linear-gradient(135deg, var(--sv1-primary), var(--sv1-primary-dark));
                color: white;
                border: none;
                border-radius: var(--r-md);
                font-family: var(--font-body);
                font-size: 0.95rem;
                font-weight: 600;
                cursor: pointer;
                display: flex; align-items: center; justify-content: center; gap: .6rem;
                transition: all .25s;
                text-decoration: none;
                letter-spacing: .2px;
                box-shadow: 0 4px 12px rgba(107,78,155,0.3);
            }
            .btn-primary:hover:not(:disabled) {
                background: var(--sv1-primary-dark);
                transform: translateY(-1px);
                box-shadow: 0 8px 20px rgba(107,78,155,0.4);
            }
            .btn-primary:active { transform: translateY(0); }
            .btn-primary:disabled { opacity: .55; cursor: not-allowed; }

            .btn-success {
                width: 100%;
                padding: 1rem 1.5rem;
                background: var(--green);
                color: white;
                border: none;
                border-radius: var(--r-md);
                font-family: var(--font-body);
                font-size: 0.95rem;
                font-weight: 600;
                cursor: pointer;
                display: flex; align-items: center; justify-content: center; gap: .6rem;
                transition: all .25s;
            }
            .btn-success:hover:not(:disabled) {
                background: #0d9488;
                transform: translateY(-1px);
                box-shadow: 0 8px 20px rgba(16,185,129,0.3);
            }
            .btn-success:disabled { opacity: .55; cursor: not-allowed; }

            .btn-amber {
                padding: .7rem 1.25rem;
                background: var(--sv1-gold);
                color: white;
                border: none;
                border-radius: var(--r-sm);
                font-family: var(--font-body);
                font-size: 0.88rem;
                font-weight: 600;
                cursor: pointer;
                display: inline-flex; align-items: center; gap: .5rem;
                transition: all .25s;
                text-decoration: none;
            }
            .btn-amber:hover {
                background: var(--sv1-gold-light);
                transform: translateY(-1px);
            }

            .btn-ghost {
                padding: .7rem 1.25rem;
                background: transparent;
                color: var(--sv1-primary);
                border: 2px solid var(--sv1-border);
                border-radius: var(--r-sm);
                font-family: var(--font-body);
                font-size: 0.88rem;
                font-weight: 500;
                cursor: pointer;
                display: inline-flex; align-items: center; gap: .5rem;
                transition: all .25s;
                text-decoration: none;
            }
            .btn-ghost:hover {
                border-color: var(--sv1-primary);
                background: var(--sv1-primary-soft);
                color: var(--sv1-primary-dark);
            }

            .action-stack {
                display: flex;
                flex-direction: column;
                gap: .75rem;
                margin-bottom: 2rem;
            }
            .action-row {
                display: flex;
                justify-content: space-between;
                align-items: center;
                gap: 1rem;
            }

            /* ─── Status Area ─── */
            .status-area {
                background: var(--sv1-primary-soft);
                border: 1px solid var(--sv1-border);
                border-radius: var(--r-md);
                padding: 1.5rem;
                text-align: center;
                margin-bottom: 1.5rem;
            }
            .spinner {
                width: 36px; height: 36px;
                border: 3px solid var(--sv1-border);
                border-top-color: var(--sv1-primary);
                border-radius: 50%;
                animation: spin .8s linear infinite;
                margin: 0 auto 1rem;
            }
            @keyframes spin { to { transform: rotate(360deg); } }
            @keyframes fadeSlideIn {
                from { opacity: 0; transform: translateY(-6px); }
                to   { opacity: 1; transform: translateY(0); }
            }
            .status-message {
                font-size: 0.9rem;
                color: var(--sv1-text-dark);
                font-weight: 500;
            }

            /* ─── Payment Button Area ─── */
            #paymentButtonArea {
                margin-bottom: 1.5rem;
                transition: all 0.3s ease;
            }
            
            #paymentButtonArea .alert-warning {
                background: var(--orange-pale);
                border-left: 4px solid var(--orange);
                border-radius: var(--r-md);
                padding: 1.5rem;
            }
            
            #paymentButtonArea .btn-amber {
                display: block;
                width: 100%;
                padding: 1rem 1.5rem;
                background: var(--sv1-gold);
                color: white;
                border: none;
                border-radius: var(--r-md);
                font-weight: 600;
                text-align: center;
                text-decoration: none;
                transition: all .25s;
            }
            
            #paymentButtonArea .btn-amber:hover {
                background: var(--sv1-gold-light);
                transform: translateY(-1px);
                box-shadow: 0 4px 12px rgba(201,164,74,0.3);
            }

            /* ─── Divider ─── */
            .divider {
                height: 1px;
                background: var(--sv1-border);
                margin: 2rem 0;
            }

            /* ─── Support ─── */
            .support-block {}
            .support-title {
                font-size: 0.75rem;
                font-weight: 600;
                text-transform: uppercase;
                letter-spacing: .8px;
                color: var(--grey-4);
                margin-bottom: 1rem;
                text-align: center;
            }
            .support-grid {
                display: grid;
                grid-template-columns: repeat(3, 1fr);
                gap: .75rem;
            }
            .support-item {
                background: var(--sv1-primary-soft);
                border: 1px solid var(--sv1-border);
                border-radius: var(--r-md);
                padding: 1rem;
                display: flex;
                align-items: center;
                gap: .75rem;
                transition: border-color .2s, box-shadow .2s;
            }
            .support-item:hover {
                border-color: var(--sv1-primary-light);
                box-shadow: var(--sh-sm);
            }
            .support-dot {
                width: 36px; height: 36px;
                border-radius: 8px;
                display: flex; align-items: center; justify-content: center;
                font-size: 0.95rem;
                color: white;
                flex-shrink: 0;
            }
            .support-dot.phone    { background: var(--sv1-primary); }
            .support-dot.whatsapp { background: #25D366; }
            .support-dot.email    { background: var(--red); }
            .support-text {}
            .support-label {
                font-size: 0.68rem;
                font-weight: 600;
                text-transform: uppercase;
                letter-spacing: .5px;
                color: var(--grey-4);
                margin-bottom: 2px;
            }
            .support-value {
                font-size: 0.85rem;
                font-weight: 500;
                color: var(--sv1-text-dark);
                line-height: 1.3;
                word-break: break-word;
            }

            /* ─── Card Footer ─── */
            .card-foot {
                padding: 1.25rem 2.5rem;
                background: var(--sv1-primary-soft);
                border-top: 1px solid var(--sv1-border);
                display: flex;
                align-items: center;
                justify-content: center;
                gap: .6rem;
                font-size: 0.78rem;
                color: var(--sv1-text-muted);
            }
            .card-foot i { 
                font-size: 0.85rem; 
                color: var(--sv1-gold); 
            }

            /* ─── Responsive ─── */
            @media (max-width: 768px) {
                .card-head { padding: 1.5rem; }
                .app-badge { display: none; }
                .card-body { padding: 1.5rem; }
                .card-foot { padding: 1rem 1.5rem; }
                .fee-panel { flex-direction: column; align-items: flex-start; gap: 1rem; }
                .fee-amount { font-size: 2rem; }
                .support-grid { grid-template-columns: 1fr; }
            }
            @media (max-width: 540px) {
                .card-head-content { flex-wrap: wrap; }
                .main { padding: 1.5rem var(--gap) 3rem; }
                .action-row { flex-direction: column; }
                .action-row .btn-ghost { width: 100%; justify-content: center; }
            }
            @media (min-width: 769px) and (max-width: 1024px) {
                .support-grid { grid-template-columns: repeat(3, 1fr); }
            }
            </style>
        </head>
        <body>
        <div class="page-shell">

            <!-- ── Main ── -->
            <main class="main">
                <div class="main-inner">

                    <!-- Alert Container -->
                    <div id="alertContainer"></div>

                    <!-- Main Card -->
                    <div class="card">

                        <!-- Header -->
                        <div class="card-head">
                            <div class="card-head-content">
                                <div class="card-head-icon">
                                    <i class="fas fa-credit-card"></i>
                                </div>
                                <div class="card-head-text">
                                    <div class="card-head-title">Application Payment</div>
                                    <div class="card-head-sub">Step 4 of 5 &mdash; Complete your payment to proceed</div>
                                </div>
                                <div class="app-badge">
                                    <div class="app-badge-label">App. Number</div>
                                    <div class="app-badge-value"><?php echo $this->e($application['application_number'] ?? 'Pending'); ?></div>
                                </div>
                            </div>
                        </div>

                        <!-- Body -->
                        <div class="card-body">

                            <!-- ========================================================= -->
                            <!-- 6. Add CSRF token to all forms -->
                            <!-- ========================================================= -->
                            <input type="hidden" name="csrf_token" id="csrf_token" value="<?php echo $this->e($csrf_token); ?>">

                            <!-- Fee Panel -->
                            <div class="fee-panel">
                                <div class="fee-icon">
                                    <i class="fas fa-naira-sign"></i>
                                </div>
                                <div class="fee-info">
                                    <div class="fee-label">Application Fee</div>
                                    <div class="fee-amount"><?php echo $this->e($currency); ?><?php echo $this->e(number_format($fee)); ?></div>
                                    <div class="fee-note"><i class="fas fa-info-circle" style="font-size:.75rem;margin-right:4px"></i>This fee is non-refundable once payment is confirmed.</div>
                                </div>
                                <div class="fee-badge">
                                    <i class="fas fa-shield-check" style="font-size:.75rem;margin-right:4px"></i>Secure Payment
                                </div>
                            </div>

                            <!-- Instructions -->
                            <div class="instructions-block">
                                <div class="instructions-title">How to complete payment</div>
                                <ol class="steps-list">
                                    <li>
                                        <span class="step-num">1</span>
                                        Click <strong>Generate RRR</strong> to create your unique Remita Retrieval Reference number.
                                    </li>
                                    <li>
                                        <span class="step-num">2</span>
                                        Click the <strong>Complete Payment</strong> button that appears immediately.
                                    </li>
                                    <li>
                                        <span class="step-num">3</span>
                                        Use demo card: <strong>5178 6810 0000 0002</strong> (Exp: 05/30, CVV: 000, OTP: 123456)
                                    </li>
                                    <li>
                                        <span class="step-num">4</span>
                                        Return here and click <strong>I've Paid — Verify Payment</strong>.
                                    </li>
                                    <li>
                                        <span class="step-num">5</span>
                                        Your exam slip will be available immediately after successful verification.
                                    </li>
                                </ol>
                            </div>

                            <!-- Pending Payment Block - handled by JS below (HTML removed) -->

                            <!-- Generated RRR Display -->
                            <div id="rrrDisplayArea" style="display:none;margin-bottom:1.5rem">
                                <div class="instructions-title" style="margin-bottom:.75rem">Your Payment Reference (RRR)</div>
                                <div class="rrr-box">
                                    <div class="rrr-value" id="generatedRRR"></div>
                                    <button class="rrr-copy-btn" id="copyRRRBtn">
                                        <i class="fas fa-copy"></i> Copy
                                    </button>
                                </div>
                                <p style="font-size:.8rem;color:var(--sv1-text-muted);display:flex;align-items:center;gap:5px">
                                    <i class="fas fa-info-circle"></i>
                                    Save this RRR in case you need to verify your payment later.
                                </p>
                            </div>

                            <!-- Payment Button Area - Appears Immediately After RRR Generation -->
                            <div id="paymentButtonArea" style="display:none; margin-bottom:1.5rem">
                                <div class="alert alert-warning">
                                    <h5><i class="fas fa-external-link-alt"></i> Proceed to Payment</h5>
                                    <p class="mb-3">Click the button below to complete your payment on Remita demo platform:</p>
                                    <a href="#" id="remitaPaymentLink" target="_blank" class="btn-amber w-100" style="text-align:center; display:block;">
                                        <i class="fas fa-credit-card me-2"></i> Complete Payment (Demo)
                                    </a>
                                    <p class="mt-3 small text-muted">After payment, return here and click "I've Paid — Verify Payment"</p>
                                </div>
                            </div>

                            <!-- Processing Status -->
                            <div id="paymentStatus" style="display:none;margin-bottom:1.5rem">
                                <div class="status-area">
                                    <div id="paymentSpinner" class="spinner"></div>
                                    <div id="paymentMessage" class="status-message">Processing…</div>
                                    <div id="paymentRRR" class="rrr-value" style="display:none;margin-top:1rem;text-align:center"></div>
                                    <div id="remitaLink" style="display:none;margin-top:1rem"></div>
                                </div>
                            </div>

                            <!-- Action Buttons -->
                            <div class="action-stack">
                                <button class="btn-primary" id="generateRRRBtn">
                                    <i class="fas fa-bolt"></i> Generate RRR
                                </button>
                                <button class="btn-success" id="verifyPaymentBtn" style="display:none">
                                    <i class="fas fa-check-circle"></i> I've Paid — Verify Payment
                                </button>
                                <div class="action-row">
                                    <a href="/apply/form" class="btn-ghost">
                                        <i class="fas fa-arrow-left"></i> Back to Form
                                    </a>
                                    <button class="btn-ghost" id="checkStatusBtn" style="display:none">
                                        <i class="fas fa-sync"></i> Check Status
                                    </button>
                                </div>
                            </div>

                            <div class="divider"></div>

                            <!-- Support -->
                            <div class="support-block">
                                <div class="support-title">Need help with payment?</div>
                                <div class="support-grid">
                                    <div class="support-item">
                                        <div class="support-dot phone"><i class="fas fa-phone"></i></div>
                                        <div class="support-text">
                                            <div class="support-label">Phone</div>
                                            <div class="support-value">07039837749</div>
                                        </div>
                                    </div>
                                    <div class="support-item">
                                        <div class="support-dot whatsapp"><i class="fab fa-whatsapp"></i></div>
                                        <div class="support-text">
                                            <div class="support-label">WhatsApp</div>
                                            <div class="support-value">08082775076</div>
                                        </div>
                                    </div>
                                    <div class="support-item">
                                        <div class="support-dot email"><i class="fas fa-envelope"></i></div>
                                        <div class="support-text">
                                            <div class="support-label">Email</div>
                                            <div class="support-value">info@fctcns.edu.ng</div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div><!-- /card-body -->

                        <!-- Footer -->
                        <div class="card-foot">
                            <i class="fas fa-lock"></i>
                            <span>Payments are secured and processed by <strong>Remita Payment Gateway</strong></span>
                        </div>

                    </div><!-- /card -->

                </div><!-- /main-inner -->
            </main>

        </div><!-- /page-shell -->

        <!-- ========================================================= -->
        <!-- 7. Add CSP nonce to all script tags -->
        <!-- 8. Add SRI hashes to external scripts -->
        <!-- ========================================================= -->
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"
                integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4="
                crossorigin="anonymous"
                nonce="<?php echo $csp_nonce; ?>"></script>
        
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"
                integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz"
                crossorigin="anonymous"
                nonce="<?php echo $csp_nonce; ?>"></script>
        
        <script nonce="<?php echo $csp_nonce; ?>">
        (function() {
            'use strict';

            // ── Config from PHP ──────────────────────────────────────────
            var pendingRRR    = <?php echo isset($pending_payment['rrr']) ? json_encode($pending_payment['rrr']) : 'null'; ?>;
            var pendingPayUrl = <?php echo isset($pending_payment['payment_url']) ? json_encode($pending_payment['payment_url']) : 'null'; ?>;

            // ── State ────────────────────────────────────────────────────
            var currentRRR = '';

            // ── DOM refs ─────────────────────────────────────────────────
            var generateBtn     = document.getElementById('generateRRRBtn');
            var verifyBtn       = document.getElementById('verifyPaymentBtn');
            var checkStatusBtn  = document.getElementById('checkStatusBtn');
            var copyRRRBtn      = document.getElementById('copyRRRBtn');
            var rrrDisplayArea  = document.getElementById('rrrDisplayArea');
            var paymentBtnArea  = document.getElementById('paymentButtonArea');
            var remitaPayLink   = document.getElementById('remitaPaymentLink');
            var generatedRRREl  = document.getElementById('generatedRRR');
            var paymentStatus   = document.getElementById('paymentStatus');
            var paymentSpinner  = document.getElementById('paymentSpinner');
            var paymentMessage  = document.getElementById('paymentMessage');
            var alertContainer  = document.getElementById('alertContainer');

            // ── CSRF ─────────────────────────────────────────────────────
            function getCsrfToken() {
                var meta = document.querySelector('meta[name="csrf-token"]');
                return meta ? meta.getAttribute('content') : '';
            }

            // ── Alert ────────────────────────────────────────────────────
            function showAlert(message, type) {
                type = type || 'info';
                var icons = { success: 'check-circle', danger: 'exclamation-circle', warning: 'exclamation-triangle', info: 'info-circle' };
                var div = document.createElement('div');
                div.className = 'alert alert-' + type;
                div.innerHTML = '<i class="fas fa-' + (icons[type] || 'info-circle') + '"></i>' + message;
                alertContainer.appendChild(div);
                setTimeout(function() { if (div.parentNode) div.remove(); }, 6000);
            }

            // ── Copy to clipboard ────────────────────────────────────────
            function copyToClipboard(text) {
                if (navigator.clipboard && navigator.clipboard.writeText) {
                    navigator.clipboard.writeText(text).then(function() {
                        showAlert('RRR copied to clipboard!', 'success');
                    }).catch(function() { fallbackCopy(text); });
                } else {
                    fallbackCopy(text);
                }
            }

            function fallbackCopy(text) {
                var ta = document.createElement('textarea');
                ta.value = text;
                ta.style.cssText = 'position:fixed;opacity:0;top:0;left:0';
                document.body.appendChild(ta);
                ta.select();
                try {
                    document.execCommand('copy');
                    showAlert('RRR copied to clipboard!', 'success');
                } catch(e) {
                    showAlert('Could not copy RRR', 'danger');
                }
                document.body.removeChild(ta);
            }

            // ── Show payment UI after RRR is ready ───────────────────────
            function showPaymentUI(rrr, paymentUrl) {
                currentRRR = rrr;

                // Populate RRR display
                if (generatedRRREl) generatedRRREl.textContent = rrr;
                if (rrrDisplayArea) rrrDisplayArea.style.display = 'block';

                // Set payment link
                var url = paymentUrl || ('https://demo.remita.net/remita/onepage/payment/init.reg?rrr=' + encodeURIComponent(rrr) + '&channel=CARD,USSD,ENAIRA,TRANSFER');
                if (remitaPayLink) remitaPayLink.href = url;

                // Show payment button area
                if (paymentBtnArea) paymentBtnArea.style.display = 'block';

                // Show verify and check buttons
                if (verifyBtn) verifyBtn.style.display = 'flex';
                if (checkStatusBtn) checkStatusBtn.style.display = 'inline-flex';

                // Hide generate button since we already have an RRR
                if (generateBtn) generateBtn.style.display = 'none';

                // Scroll to payment button
                if (paymentBtnArea) {
                    paymentBtnArea.scrollIntoView({ behavior: 'smooth', block: 'center' });
                }

                // Auto-open payment in new tab
                window.open(url, '_blank');
            }

            // ── Verify payment ───────────────────────────────────────────
            function doVerifyPayment(rrr) {
                if (!rrr) {
                    showAlert('No RRR found to verify', 'warning');
                    return;
                }

                if (paymentStatus) paymentStatus.style.display = 'block';
                if (paymentSpinner) paymentSpinner.style.display = 'block';
                if (paymentMessage) paymentMessage.textContent = 'Verifying payment, please wait...';

                if (verifyBtn) verifyBtn.disabled = true;

                fetch('/apply/verify-payment', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: JSON.stringify({
                        rrr: rrr,
                        csrf_token: getCsrfToken()
                    })
                })
                .then(function(response) {
                    var ct = response.headers.get('content-type') || '';
                    if (!ct.includes('application/json')) {
                        return response.text().then(function(t) {
                            throw new Error('Server returned non-JSON response');
                        });
                    }
                    return response.json();
                })
                .then(function(data) {
                    if (paymentSpinner) paymentSpinner.style.display = 'none';

                    if (data.success) {
                        if (paymentMessage) paymentMessage.textContent = 'Payment verified! Redirecting to exam slip...';
                        showAlert('Payment confirmed! Redirecting...', 'success');
                        setTimeout(function() {
                            window.location.href = data.redirect || '/apply/step/4';
                        }, 1500);
                    } else {
                        if (paymentStatus) paymentStatus.style.display = 'none';
                        if (verifyBtn) verifyBtn.disabled = false;
                        showAlert(data.message || 'Payment not confirmed yet. Please try again.', 'danger');
                    }
                })
                .catch(function(err) {
                    if (paymentSpinner) paymentSpinner.style.display = 'none';
                    if (paymentStatus) paymentStatus.style.display = 'none';
                    if (verifyBtn) verifyBtn.disabled = false;
                    showAlert('Network error. Please try again.', 'danger');
                    console.error('Verify error:', err);
                });
            }

            // ── Event listeners (no inline handlers) ─────────────────────

            // Copy RRR button
            if (copyRRRBtn) {
                copyRRRBtn.addEventListener('click', function() {
                    copyToClipboard(currentRRR || (generatedRRREl ? generatedRRREl.textContent : ''));
                });
            }

            // Generate RRR button
            if (generateBtn) {
                generateBtn.addEventListener('click', function() {
                    var btn = this;
                    btn.disabled = true;
                    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Generating...';

                    if (paymentStatus) paymentStatus.style.display = 'block';
                    if (paymentSpinner) paymentSpinner.style.display = 'block';
                    if (paymentMessage) paymentMessage.textContent = 'Generating RRR, please wait...';

                    fetch('/apply/initiate-payment', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                        body: JSON.stringify({ csrf_token: getCsrfToken() })
                    })
                    .then(function(response) {
                        var ct = response.headers.get('content-type') || '';
                        if (!ct.includes('application/json')) {
                            return response.text().then(function(t) {
                                throw new Error('Server error. Please try again.');
                            });
                        }
                        return response.json();
                    })
                    .then(function(data) {
                        if (paymentSpinner) paymentSpinner.style.display = 'none';
                        if (paymentStatus) paymentStatus.style.display = 'none';

                        if (data.success) {
                            showAlert('RRR generated successfully! Opening payment page...', 'success');
                            showPaymentUI(data.rrr, data.payment_url);
                        } else {
                            btn.disabled = false;
                            btn.innerHTML = '<i class="fas fa-bolt"></i> Generate RRR';
                            showAlert(data.message || 'Failed to generate RRR. Please try again.', 'danger');
                        }
                    })
                    .catch(function(err) {
                        btn.disabled = false;
                        btn.innerHTML = '<i class="fas fa-bolt"></i> Generate RRR';
                        if (paymentStatus) paymentStatus.style.display = 'none';
                        showAlert('Network error. Please try again.', 'danger');
                        console.error('Generate error:', err);
                    });
                });
            }

            // Verify payment button
            if (verifyBtn) {
                verifyBtn.addEventListener('click', function() {
                    doVerifyPayment(currentRRR || (generatedRRREl ? generatedRRREl.textContent.trim() : ''));
                });
            }

            // Check status button
            if (checkStatusBtn) {
                checkStatusBtn.addEventListener('click', function() {
                    doVerifyPayment(currentRRR || (generatedRRREl ? generatedRRREl.textContent.trim() : ''));
                });
            }

            // ── On page load: restore pending RRR if exists ───────────────
            document.addEventListener('DOMContentLoaded', function() {
                if (pendingRRR) {
                    showPaymentUI(pendingRRR, pendingPayUrl);
                    // Don't auto-open tab on page load for pending - user must click
                    // We override: just show UI without auto-opening tab on load
                }
            });

            // Handle pending RRR on load without auto-opening tab
            if (pendingRRR) {
                currentRRR = pendingRRR;
                if (generatedRRREl) generatedRRREl.textContent = pendingRRR;
                if (rrrDisplayArea) rrrDisplayArea.style.display = 'block';
                var url = pendingPayUrl || ('https://demo.remita.net/remita/onepage/payment/init.reg?rrr=' + encodeURIComponent(pendingRRR) + '&channel=CARD,USSD,ENAIRA,TRANSFER');
                if (remitaPayLink) remitaPayLink.href = url;
                if (paymentBtnArea) paymentBtnArea.style.display = 'block';
                if (verifyBtn) verifyBtn.style.display = 'flex';
                if (checkStatusBtn) checkStatusBtn.style.display = 'inline-flex';
                if (generateBtn) generateBtn.style.display = 'none';
            }

        }());
        </script>
        </body>
        </html>
        <?php
    }
}

// =========================================================
// 9. Add the view instantiation at the bottom
// =========================================================
$view = new PaymentView();
$view->render(get_defined_vars());
?>