<?php
/**
 * Payment View - Step 3
 * Redesigned: Premium institutional design
 * FIXED: Removed HTML escaping from Remita payment URL
 * FIXED: Updated copy button to pass RRR directly
 * FIXED: Added payment button area for after RRR generation
 * FIXED: Moved CSRF inputs from head to body for valid HTML
 * FIXED: Updated both Remita URLs to correct format from support
 * FIXED: Using demo.remita.net for demo environment (not login.remita.net)
 * 
 * @package FCTCNS
 * @version 2.5 (Security Enhanced)
 */

// =========================================================
// 1. Add the trait at the top of each view file
// =========================================================
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
            
            <title>Payment — FCT College of Nursing Sciences</title>

            <!-- Fonts -->
            <link rel="preconnect" href="https://fonts.googleapis.com">
            <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
            
            <!-- ========================================================= -->
            <!-- 3. Add CSP nonce to all style tags -->
            <!-- 7. Add SRI hashes to external scripts/styles -->
            <!-- ========================================================= -->
            <link href="https://fonts.googleapis.com/css2?family=DM+Serif+Display:ital@0;1&family=DM+Sans:wght@300;400;500;600&family=DM+Mono:wght@400;500&display=swap" 
                  rel="stylesheet"
                  integrity="sha384-0pCryB3hBqYHZO9dKsIIzN8wH+Z4k5P+GZ8TlqM9m8A3TlPI9c7JZ6nG+K/t9fb"
                  crossorigin="anonymous">
            
            <link rel="stylesheet" 
                  href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"
                  integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw=="
                  crossorigin="anonymous" 
                  referrerpolicy="no-referrer">

            <style nonce="<?php echo $csp_nonce; ?>">
            /* ─── Reset ─── */
            *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
            html { scroll-behavior: smooth; }

            /* ─── Tokens ─── */
            :root {
                --navy:        #0B1D3A;
                --navy-mid:    #152D56;
                --navy-soft:   #1E3D6E;
                --navy-ghost:  #EEF3FA;

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

                --green:       #0E9462;
                --green-pale:  #E6F7F1;
                --red:         #D94F3A;
                --red-pale:    #FDECEA;
                --orange:      #E07B2A;
                --orange-pale: #FEF0E2;
                --blue:        #2563EB;
                --blue-pale:   #EFF4FF;

                --r-sm: 8px;
                --r-md: 14px;
                --r-lg: 20px;
                --r-xl: 28px;

                --sh-sm:  0 1px 4px rgba(11,29,58,.06), 0 2px 12px rgba(11,29,58,.04);
                --sh-md:  0 4px 16px rgba(11,29,58,.08), 0 1px 4px rgba(11,29,58,.04);
                --sh-lg:  0 12px 40px rgba(11,29,58,.10), 0 4px 12px rgba(11,29,58,.06);

                --font-display: 'DM Serif Display', Georgia, serif;
                --font-body:    'DM Sans', system-ui, sans-serif;
                --font-mono:    'DM Mono', monospace;

                --max-w: 900px;
                --gap:   clamp(1rem, 3vw, 2rem);
            }

            /* ─── Base ─── */
            body {
                font-family: var(--font-body);
                background: var(--grey-1);
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

            /* Card Header */
            .card-head {
                background: var(--navy);
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
                background: rgba(212,134,11,0.08);
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
                background: rgba(212,134,11,0.15);
                border: 1px solid rgba(212,134,11,0.25);
                border-radius: var(--r-md);
                display: flex; align-items: center; justify-content: center;
                color: var(--amber-light);
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
                color: rgba(255,255,255,0.45);
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
                color: rgba(255,255,255,0.35);
                text-transform: uppercase;
                letter-spacing: .8px;
            }
            .app-badge-value {
                font-family: var(--font-mono);
                font-size: 0.82rem;
                color: rgba(255,255,255,0.7);
                font-weight: 500;
            }

            /* Card Body */
            .card-body {
                padding: 2.5rem;
            }

            /* ─── Fee Panel ─── */
            .fee-panel {
                background: var(--navy-ghost);
                border: 1px solid var(--grey-2);
                border-radius: var(--r-lg);
                padding: 2rem;
                display: flex;
                align-items: center;
                gap: 2rem;
                margin-bottom: 2rem;
            }
            .fee-icon {
                width: 56px; height: 56px;
                background: var(--amber-pale);
                border: 1px solid rgba(212,134,11,0.2);
                border-radius: var(--r-md);
                display: flex; align-items: center; justify-content: center;
                color: var(--amber);
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
                color: var(--navy);
                line-height: 1;
                font-weight: 400;
            }
            .fee-note {
                font-size: 0.78rem;
                color: var(--grey-4);
                margin-top: 6px;
            }
            .fee-badge {
                background: var(--amber-pale);
                border: 1px solid rgba(212,134,11,0.2);
                color: var(--amber);
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
                background: var(--navy-ghost);
                border: 1px solid var(--grey-2);
                display: flex; align-items: center; justify-content: center;
                font-size: 0.7rem;
                font-weight: 700;
                color: var(--navy-soft);
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
                border: 1px solid transparent;
                animation: fadeSlideIn .3s ease;
            }
            .alert i { font-size: 1rem; margin-top: 1px; flex-shrink: 0; }
            .alert-success { background: var(--green-pale); border-color: rgba(14,148,98,.15); color: #065f46; }
            .alert-danger   { background: var(--red-pale);   border-color: rgba(217,79,58,.15); color: #7f1d1d; }
            .alert-warning  { background: var(--orange-pale); border-color: rgba(224,123,42,.15); color: #7c2d12; }
            .alert-info     { background: var(--blue-pale);   border-color: rgba(37,99,235,.15); color: #1e3a8a; }

            /* ─── Pending Payment ─── */
            .pending-box {
                border: 1px solid rgba(224,123,42,.3);
                background: var(--orange-pale);
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
                background: rgba(224,123,42,.15);
                border-radius: 8px;
                display: flex; align-items: center; justify-content: center;
                color: var(--orange);
                font-size: 1rem;
            }
            .pending-title { font-weight: 600; color: #7c2d12; font-size: 0.95rem; }
            .pending-sub   { font-size: 0.8rem; color: rgba(124,45,18,.7); }

            /* ─── RRR Box ─── */
            .rrr-box {
                background: var(--navy-ghost);
                border: 1px dashed var(--grey-3);
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
                color: var(--navy);
                letter-spacing: 3px;
                word-break: break-all;
            }
            .rrr-copy-btn {
                background: var(--navy);
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
            .rrr-copy-btn:hover { background: var(--navy-mid); }

            /* ─── Buttons ─── */
            .btn-primary {
                width: 100%;
                padding: 1rem 1.5rem;
                background: var(--navy);
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
            }
            .btn-primary:hover:not(:disabled) {
                background: var(--navy-mid);
                transform: translateY(-1px);
                box-shadow: 0 6px 20px rgba(11,29,58,.2);
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
                background: #0a7a52;
                transform: translateY(-1px);
                box-shadow: 0 6px 20px rgba(14,148,98,.25);
            }
            .btn-success:disabled { opacity: .55; cursor: not-allowed; }

            .btn-amber {
                padding: .7rem 1.25rem;
                background: var(--amber);
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
                background: #b87209;
                transform: translateY(-1px);
            }

            .btn-ghost {
                padding: .7rem 1.25rem;
                background: transparent;
                color: var(--navy);
                border: 1.5px solid var(--grey-2);
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
                border-color: var(--navy);
                background: var(--navy-ghost);
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
                background: var(--grey-1);
                border: 1px solid var(--grey-2);
                border-radius: var(--r-md);
                padding: 1.5rem;
                text-align: center;
                margin-bottom: 1.5rem;
            }
            .spinner {
                width: 36px; height: 36px;
                border: 3px solid var(--grey-2);
                border-top-color: var(--navy);
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
                color: var(--grey-5);
                font-weight: 500;
            }

            /* ─── Payment Button Area ─── */
            #paymentButtonArea {
                margin-bottom: 1.5rem;
            }
            
            #paymentButtonArea .alert-warning {
                background: var(--orange-pale);
                border: 1px solid var(--orange);
                border-radius: var(--r-md);
                padding: 1.5rem;
            }
            
            #paymentButtonArea .btn-amber {
                display: block;
                width: 100%;
                padding: 1rem 1.5rem;
                background: var(--amber);
                color: white;
                border: none;
                border-radius: var(--r-md);
                font-weight: 600;
                text-align: center;
                text-decoration: none;
                transition: all .25s;
            }
            
            #paymentButtonArea .btn-amber:hover {
                background: #b87209;
                transform: translateY(-1px);
                box-shadow: 0 4px 12px rgba(212,134,11,0.3);
            }

            /* ─── Divider ─── */
            .divider {
                height: 1px;
                background: var(--grey-2);
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
                background: var(--grey-1);
                border: 1px solid var(--grey-2);
                border-radius: var(--r-md);
                padding: 1rem;
                display: flex;
                align-items: center;
                gap: .75rem;
                transition: border-color .2s, box-shadow .2s;
            }
            .support-item:hover {
                border-color: var(--grey-3);
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
            .support-dot.phone    { background: var(--navy-soft); }
            .support-dot.whatsapp { background: #1BA950; }
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
                color: var(--ink);
                line-height: 1.3;
                word-break: break-word;
            }

            /* ─── Card Footer ─── */
            .card-foot {
                padding: 1.25rem 2.5rem;
                background: var(--grey-1);
                border-top: 1px solid var(--grey-2);
                display: flex;
                align-items: center;
                justify-content: center;
                gap: .6rem;
                font-size: 0.78rem;
                color: var(--grey-4);
            }
            .card-foot i { font-size: 0.85rem; color: var(--green); }

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
                            <!-- 5. Add CSRF token to all forms -->
                            <!-- ========================================================= -->
                            <input type="hidden" name="csrf_token" id="csrf_token" value="<?php echo $this->e($csrf_token); ?>">
                            <meta name="csrf-token" content="<?php echo $this->e($csrf_token); ?>">

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
                                        Click the <strong>Complete Payment</strong> button that appears.
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

                            <!-- Pending Payment Block - FIXED: Using demo URL -->
                            <?php if (isset($pending_payment) && $pending_payment): ?>
                            <div class="pending-box">
                                <div class="pending-box-header">
                                    <div class="pending-icon"><i class="fas fa-clock"></i></div>
                                    <div>
                                        <div class="pending-title">Pending Payment Found</div>
                                        <div class="pending-sub">You have an existing RRR that hasn't been confirmed yet</div>
                                    </div>
                                </div>
                                <div class="rrr-box" style="margin-bottom:1.25rem">
                                    <div class="rrr-value"><?php echo $this->e($pending_payment['rrr']); ?></div>
                                    <button class="rrr-copy-btn" onclick="copyRRR('<?php echo $this->e($pending_payment['rrr']); ?>')">
                                        <i class="fas fa-copy"></i> Copy
                                    </button>
                                </div>
                                <div style="display:flex;gap:.75rem;flex-wrap:wrap">
                                    <!-- FIXED: Using demo.remita.net for demo environment -->
                                    <a href="https://demo.remita.net/remita/onepage/payment/init.reg?rrr=<?php echo $this->e($pending_payment['rrr']); ?>&channel=CARD,USSD,ENAIRA,TRANSFER"
                                       target="_blank" class="btn-amber">
                                        <i class="fas fa-external-link-alt"></i> Complete Payment (Demo)
                                    </a>
                                    <button class="btn-ghost" onclick="verifyPayment('<?php echo $this->e($pending_payment['rrr']); ?>')">
                                        <i class="fas fa-check-circle"></i> I've Paid — Verify Now
                                    </button>
                                </div>
                            </div>
                            <?php endif; ?>

                            <!-- Generated RRR Display -->
                            <div id="rrrDisplayArea" style="display:none;margin-bottom:1.5rem">
                                <div class="instructions-title" style="margin-bottom:.75rem">Your Payment Reference (RRR)</div>
                                <div class="rrr-box">
                                    <div class="rrr-value" id="generatedRRR"></div>
                                    <button class="rrr-copy-btn" id="copyRRRBtn" onclick="copyRRR()">
                                        <i class="fas fa-copy"></i> Copy
                                    </button>
                                </div>
                                <p style="font-size:.8rem;color:var(--grey-4);display:flex;align-items:center;gap:5px">
                                    <i class="fas fa-info-circle"></i>
                                    Save this RRR in case you need to verify your payment later.
                                </p>
                            </div>

                            <!-- Payment Button Area -->
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
        <!-- 4. Add CSP nonce to all script tags -->
        <!-- 7. Add SRI hashes to external scripts -->
        <!-- ========================================================= -->
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"
                integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4="
                crossorigin="anonymous"
                nonce="<?php echo $csp_nonce; ?>"></script>
        
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"
                integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz"
                crossorigin="anonymous"
                nonce="<?php echo $csp_nonce; ?>"></script>
        
        <script src="/assets/js/payment.js"
                nonce="<?php echo $csp_nonce; ?>"></script>
        
        <script nonce="<?php echo $csp_nonce; ?>">
        // ======================================================
        // Payment Page JavaScript with Security Enhancements
        // ======================================================
        
        // Get CSRF token from meta tag
        function getCsrfToken() {
            return document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        }

        // Copy RRR to clipboard with fallback
        function copyRRR(rrr = null) {
            const rrrValue = rrr || document.getElementById('generatedRRR')?.textContent;
            
            if (!rrrValue) {
                showAlert('No RRR found to copy', 'warning');
                return;
            }

            // Modern clipboard API
            if (navigator.clipboard && navigator.clipboard.writeText) {
                navigator.clipboard.writeText(rrrValue).then(() => {
                    showAlert('RRR copied to clipboard!', 'success');
                }).catch(() => {
                    fallbackCopy(rrrValue);
                });
            } else {
                fallbackCopy(rrrValue);
            }
        }

        function fallbackCopy(text) {
            const textarea = document.createElement('textarea');
            textarea.value = text;
            textarea.style.position = 'fixed';
            textarea.style.opacity = '0';
            document.body.appendChild(textarea);
            textarea.select();
            
            try {
                document.execCommand('copy');
                showAlert('RRR copied to clipboard!', 'success');
            } catch (err) {
                showAlert('Failed to copy RRR', 'danger');
            }
            
            document.body.removeChild(textarea);
        }

        // Show alert messages
        function showAlert(message, type = 'info') {
            const alertContainer = document.getElementById('alertContainer');
            const alertDiv = document.createElement('div');
            alertDiv.className = `alert alert-${type}`;
            alertDiv.innerHTML = `<i class="fas fa-${type === 'success' ? 'check-circle' : type === 'danger' ? 'exclamation-circle' : 'info-circle'}"></i>${message}`;
            alertContainer.appendChild(alertDiv);
            
            setTimeout(() => {
                alertDiv.remove();
            }, 5000);
        }

        // Generate RRR with CSRF protection
        document.getElementById('generateRRRBtn')?.addEventListener('click', function() {
            const btn = this;
            btn.disabled = true;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Generating...';
            
            const statusDiv = document.getElementById('paymentStatus');
            const messageDiv = document.getElementById('paymentMessage');
            const spinner = document.getElementById('paymentSpinner');
            
            statusDiv.style.display = 'block';
            spinner.style.display = 'block';
            messageDiv.textContent = 'Generating RRR, please wait...';
            
            fetch('/payment/generate-rrr', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': getCsrfToken()
                },
                body: JSON.stringify({})
            })
            .then(response => response.json())
            .then(data => {
                btn.disabled = false;
                btn.innerHTML = '<i class="fas fa-bolt"></i> Generate RRR';
                
                if (data.success) {
                    spinner.style.display = 'none';
                    messageDiv.textContent = 'RRR generated successfully!';
                    
                    // Display RRR
                    document.getElementById('generatedRRR').textContent = data.rrr;
                    document.getElementById('rrrDisplayArea').style.display = 'block';
                    
                    // Show payment button area
                    const paymentArea = document.getElementById('paymentButtonArea');
                    paymentArea.style.display = 'block';
                    
                    // Update Remita link
                    const remitaLink = document.getElementById('remitaPaymentLink');
                    remitaLink.href = 'https://demo.remita.net/remita/onepage/payment/init.reg?rrr=' + data.rrr + '&channel=CARD,USSD,ENAIRA,TRANSFER';
                    
                    // Show verify button
                    document.getElementById('verifyPaymentBtn').style.display = 'block';
                    document.getElementById('checkStatusBtn').style.display = 'block';
                    
                    showAlert('RRR generated: ' + data.rrr, 'success');
                } else {
                    statusDiv.style.display = 'none';
                    showAlert('Error: ' + data.message, 'danger');
                }
            })
            .catch(error => {
                btn.disabled = false;
                btn.innerHTML = '<i class="fas fa-bolt"></i> Generate RRR';
                statusDiv.style.display = 'none';
                showAlert('Network error. Please try again.', 'danger');
                console.error('Error:', error);
            });
        });

        // Verify payment with CSRF protection
        function verifyPayment(rrr) {
            if (!rrr) {
                showAlert('No RRR provided for verification', 'warning');
                return;
            }
            
            const statusDiv = document.getElementById('paymentStatus');
            const messageDiv = document.getElementById('paymentMessage');
            const spinner = document.getElementById('paymentSpinner');
            
            statusDiv.style.display = 'block';
            spinner.style.display = 'block';
            messageDiv.textContent = 'Verifying payment...';
            
            fetch('/payment/verify', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': getCsrfToken()
                },
                body: JSON.stringify({ rrr: rrr })
            })
            .then(response => response.json())
            .then(data => {
                spinner.style.display = 'none';
                
                if (data.success) {
                    messageDiv.textContent = 'Payment verified successfully! Redirecting...';
                    showAlert('Payment confirmed!', 'success');
                    
                    setTimeout(() => {
                        window.location.href = data.redirect || '/apply/exam-slip';
                    }, 1500);
                } else {
                    messageDiv.textContent = data.message;
                    showAlert('Verification failed: ' + data.message, 'danger');
                }
            })
            .catch(error => {
                spinner.style.display = 'none';
                messageDiv.textContent = 'Error verifying payment';
                showAlert('Network error. Please try again.', 'danger');
                console.error('Error:', error);
            });
        }

        // Check payment status with CSRF protection
        document.getElementById('checkStatusBtn')?.addEventListener('click', function() {
            const rrr = document.getElementById('generatedRRR')?.textContent;
            if (!rrr) {
                showAlert('No RRR found to check', 'warning');
                return;
            }
            
            const statusDiv = document.getElementById('paymentStatus');
            const messageDiv = document.getElementById('paymentMessage');
            const spinner = document.getElementById('paymentSpinner');
            
            statusDiv.style.display = 'block';
            spinner.style.display = 'block';
            messageDiv.textContent = 'Checking payment status...';
            
            fetch('/payment/status/' + rrr, {
                method: 'GET',
                headers: {
                    'X-CSRF-TOKEN': getCsrfToken()
                }
            })
            .then(response => response.json())
            .then(data => {
                spinner.style.display = 'none';
                
                if (data.status === 'paid') {
                    messageDiv.textContent = 'Payment is confirmed!';
                    showAlert('Payment confirmed!', 'success');
                    
                    setTimeout(() => {
                        window.location.href = '/apply/exam-slip';
                    }, 1500);
                } else {
                    messageDiv.textContent = 'Payment status: ' + (data.status || 'pending');
                    showAlert('Payment not yet confirmed', 'warning');
                }
            })
            .catch(error => {
                spinner.style.display = 'none';
                messageDiv.textContent = 'Error checking status';
                showAlert('Network error. Please try again.', 'danger');
                console.error('Error:', error);
            });
        });

        // Verify Payment button click handler
        document.getElementById('verifyPaymentBtn')?.addEventListener('click', function() {
            const rrr = document.getElementById('generatedRRR')?.textContent;
            if (rrr) {
                verifyPayment(rrr);
            } else {
                showAlert('No RRR found to verify', 'warning');
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
$view = new PaymentView();
$view->render(get_defined_vars());
?>