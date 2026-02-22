<?php
/**
 * Step 2: Application Form View
 * FIXED: Correct step tracking - this is Step 3 (Application Form)
 * 
 * @package FCTCNS
 */

// =========================================================
// 1. Add the trait at the top of each view file
// =========================================================
require_once APP_PATH . '/helpers/SecurityHelper.php';
require_once APP_PATH . '/helpers/SecurityTrait.php';

class ApplicationFormView {
    use SecurityTrait;
    
    public function render($data) {
        extract($data);
        
        // Get security tokens
        $csp_nonce = $this->getCspNonce();
        $csrf_token = $this->getCsrfToken();
        
        // =========================================================
        // Original view logic continues
        // =========================================================
        
        if (!function_exists('e')) {
            function e($text) {
                return htmlspecialchars($text ?? '', ENT_QUOTES, 'UTF-8');
            }
        }

        $baseUrl = $baseUrl ?? '/';
        $application = $application ?? [];
        $applicant = $applicant ?? [];
        $jamb_data = $jamb_data ?? [];
        $olevel_results = $olevel_results ?? [];
        $passport = $passport ?? [];
        $states = $states ?? [];
        $programs = $programs ?? [];
        $temp_password = $temp_password ?? '';
        $errors = $errors ?? [];
        $currentStep = 3; // This is step 3 (Application Form)

        // Get application step if available
        if (isset($application) && !empty($application['application_step'])) {
            $currentStep = (int)$application['application_step'];
            
            // If application_step is 4 AND exam slip exists, show step 5
            if ($currentStep == 4 && isset($has_exam_slip) && $has_exam_slip) {
                $currentStep = 5;
            }
        }

        // Define steps for tracking - CORRECT ORDER
        $steps = [
            1 => ['label' => 'Create Account', 'sub' => 'Register'],
            2 => ['label' => 'JAMB Verification', 'sub' => 'JAMB check'],
            3 => ['label' => 'Application Form', 'sub' => 'Fill form'],
            4 => ['label' => 'Payment', 'sub' => 'Remita RRR'],
            5 => ['label' => 'Exam Slip', 'sub' => 'Download'],
        ];

        $applicant_name = trim(($applicant['first_name'] ?? '') . ' ' . ($applicant['last_name'] ?? ''));
        if (empty($applicant_name) && !empty($application)) {
            $applicant_name = trim(($application['first_name'] ?? '') . ' ' . ($application['last_name'] ?? ''));
        }
        if (empty($applicant_name)) $applicant_name = 'Applicant';

        $flash_success = $flash_success ?? $_SESSION['flash_success'] ?? null;
        $flash_error   = $flash_error   ?? $_SESSION['flash_error']   ?? null;
        ?>
        <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            
            <!-- ========================================================= -->
            <!-- 2. Add security meta tags in the head -->
            <!-- ========================================================= -->
            <?php echo $this->getSecurityMetaTags(); ?>
            
            <!-- ========================================================= -->
            <!-- 3. Add CSRF meta tag for JavaScript -->
            <!-- ========================================================= -->
            <meta name="csrf-token" content="<?php echo $this->e($csrf_token); ?>">
            
            <title>Step 3: Application Form – FCT College of Nursing Sciences</title>

            <!-- ========================================================= -->
            <!-- 4. Add CSP nonce to all style tags -->
            <!-- ========================================================= -->
            <style nonce="<?php echo $csp_nonce; ?>">
            /* =========================================================
               BASE
            ========================================================= */
            *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

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
                --success:     #16A34A;
                --white:       #FFFFFF;
                --off-white:   #F8FAFD;
                --border:      #E2E8F4;
                --border-dark: #C8D3E8;
                --text-dark:   #0F1B35;
                --text-body:   #374160;
                --text-muted:  #7A86A0;
                --radius-sm:   6px;
                --radius-md:   10px;
                --radius-lg:   16px;
                --radius-xl:   24px;
            }

            html, body {
                width: 100%;
                overflow-x: hidden;
                background: var(--off-white);
                font-family: 'DM Sans', -apple-system, sans-serif;
                font-size: 14px;
                color: var(--text-body);
                line-height: 1.6;
            }

            /* =========================================================
               PAGE SHELL
            ========================================================= */
            .page-shell {
                width: 100%;
                max-width: 1540px;
                margin: 0 auto;
                padding: 28px 32px 56px;
            }

            @media (max-width: 1200px) { .page-shell { padding: 20px 24px 48px; } }
            @media (max-width: 768px)  { .page-shell { padding: 16px 14px 40px; } }

            /* =========================================================
               STEP INDICATOR - 5 STEPS WITH PROPER TRACKING (STEP 3 ACTIVE)
            ========================================================= */
            .step-indicator {
                display: flex;
                justify-content: space-between;
                margin-bottom: 30px;
                position: relative;
                background: white;
                border-radius: 50px;
                padding: 15px 20px;
                border: 1px solid var(--border);
                box-shadow: 0 2px 8px rgba(0,0,0,0.03);
            }

            .step-indicator::before {
                content: '';
                position: absolute;
                top: 50%;
                left: 60px;
                right: 60px;
                height: 2px;
                background: var(--border);
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
                width: 36px;
                height: 36px;
                background: white;
                border: 2px solid var(--border);
                border-radius: 50%;
                display: flex;
                align-items: center;
                justify-content: center;
                margin: 0 auto 6px;
                font-weight: 600;
                font-size: 14px;
                color: var(--text-muted);
                transition: all 0.3s;
            }

            .step.active .step-number {
                background: var(--navy);
                border-color: var(--navy);
                color: white;
                box-shadow: 0 0 0 4px rgba(15,27,53,0.1);
            }

            .step.completed .step-number {
                background: var(--teal);
                border-color: var(--teal);
                color: white;
            }

            .step-label {
                font-size: 11px;
                font-weight: 600;
                color: var(--text-muted);
                text-transform: uppercase;
                letter-spacing: 0.3px;
                white-space: nowrap;
            }

            .step-sub {
                font-size: 9px;
                color: var(--text-muted);
                margin-top: 2px;
                opacity: 0.8;
            }

            .step.active .step-label {
                color: var(--navy);
                font-weight: 700;
            }

            .step.active .step-sub {
                color: var(--navy);
                opacity: 0.9;
            }

            .step.completed .step-label {
                color: var(--teal);
            }

            @media (max-width: 768px) {
                .step-indicator {
                    flex-wrap: wrap;
                    gap: 10px;
                    padding: 15px;
                }
                
                .step-indicator::before {
                    display: none;
                }
                
                .step {
                    flex: 0 0 calc(33.33% - 7px);
                    padding: 8px 5px;
                    background: var(--off-white);
                    border-radius: 30px;
                    border: 1px solid var(--border);
                }
                
                .step-number {
                    width: 30px;
                    height: 30px;
                    font-size: 12px;
                }
                
                .step-label {
                    font-size: 9px;
                    white-space: normal;
                }
            }

            @media (max-width: 480px) {
                .step {
                    flex: 0 0 calc(50% - 5px);
                }
            }

            /* =========================================================
               LOGOUT BUTTON
            ========================================================= */
            .logout-btn {
                display: inline-flex; align-items: center; gap: 7px;
                background: rgba(192,57,43,0.15);
                border: 1px solid rgba(192,57,43,0.4);
                border-radius: 50px;
                padding: 7px 16px;
                font-size: 12px; font-weight: 600;
                color: #f87171;
                text-decoration: none;
                transition: all 0.2s;
                white-space: nowrap;
            }

            .logout-btn:hover { background: var(--red); color: #fff; border-color: var(--red); }

            /* =========================================================
               FLASH ALERTS
            ========================================================= */
            .flash-alert {
                display: flex; align-items: flex-start; gap: 12px;
                padding: 13px 18px; border-radius: var(--radius-md);
                margin-bottom: 16px; font-size: 14px;
                border: 1px solid transparent;
            }
            .flash-alert.success { background: var(--teal-light);  border-color: rgba(29,138,122,.25); color: #145f55; }
            .flash-alert.error   { background: var(--red-light);   border-color: rgba(192,57,43,.25);  color: #8b1a12; }
            .flash-alert.warning { background: var(--gold-pale);   border-color: rgba(200,150,58,.35); color: #7c5200; }
            .flash-alert i { margin-top: 1px; flex-shrink: 0; }

            /* Temp password box */
            .temp-pw-box {
                background: var(--gold-pale);
                border: 1.5px solid rgba(200,150,58,.4);
                border-radius: var(--radius-md);
                padding: 18px 22px;
                margin-bottom: 18px;
            }
            .temp-pw-box h6 { font-weight: 700; color: var(--navy); margin-bottom: 8px; }
            .temp-pw-code {
                background: var(--white);
                border: 1px solid var(--border-dark);
                border-radius: var(--radius-sm);
                padding: 12px 20px;
                font-family: 'DM Mono', monospace;
                font-size: 22px;
                font-weight: 700;
                letter-spacing: 4px;
                text-align: center;
                color: var(--navy);
                margin: 10px 0;
            }

            /* =========================================================
               JAMB VERIFIED BANNER
            ========================================================= */
            .jamb-banner {
                display: flex;
                align-items: center;
                justify-content: space-between;
                gap: 16px;
                background: var(--navy);
                border-radius: var(--radius-md);
                padding: 18px 24px;
                margin-bottom: 24px;
                flex-wrap: wrap;
            }

            .jamb-banner-left { display: flex; align-items: center; gap: 14px; }

            .jamb-check {
                width: 44px; height: 44px; border-radius: 50%;
                background: var(--teal);
                display: flex; align-items: center; justify-content: center;
                color: #fff; font-size: 18px;
                flex-shrink: 0;
            }

            .jamb-info-title { font-size: 14px; font-weight: 700; color: #fff; margin-bottom: 3px; }
            .jamb-info-sub   { font-size: 13px; color: rgba(255,255,255,0.6); }
            .jamb-info-sub strong { color: rgba(255,255,255,0.9); }

            .jamb-score-pill {
                background: rgba(200,150,58,0.15);
                border: 1px solid rgba(200,150,58,0.35);
                border-radius: 50px;
                padding: 5px 14px;
                font-size: 12px; font-weight: 700;
                color: var(--gold-light);
                white-space: nowrap;
            }

            /* =========================================================
               FORM CARD
            ========================================================= */
            .form-card {
                background: var(--white);
                border: 1px solid var(--border);
                border-radius: var(--radius-xl);
                overflow: hidden;
                box-shadow: 0 4px 24px rgba(15,27,53,0.07);
            }

            /* =========================================================
               SECTION BLOCKS
            ========================================================= */
            .f-section {
                padding: 32px 36px;
                border-bottom: 1px solid var(--border);
            }

            .f-section:last-child { border-bottom: none; }

            @media (max-width: 768px) { .f-section { padding: 22px 18px; } }

            .f-section-head {
                display: flex; align-items: center; gap: 12px;
                margin-bottom: 24px;
                padding-bottom: 14px;
                border-bottom: 1px solid var(--border);
            }

            .f-section-icon {
                width: 36px; height: 36px;
                background: var(--navy);
                border-radius: var(--radius-sm);
                display: flex; align-items: center; justify-content: center;
                font-size: 14px; color: var(--gold-light);
                flex-shrink: 0;
            }

            .f-section-title { font-family: 'Playfair Display', serif; font-size: 17px; font-weight: 700; color: var(--text-dark); margin: 0; }
            .f-section-sub   { font-size: 12px; color: var(--text-muted); margin-top: 2px; }

            /* =========================================================
               FORM FIELDS
            ========================================================= */
            .field-label {
                display: block;
                font-size: 12px; font-weight: 600;
                color: var(--text-dark);
                margin-bottom: 6px;
                letter-spacing: 0.1px;
            }
            .field-label .req { color: var(--red); margin-left: 2px; }

            .field-hint { font-size: 11px; color: var(--text-muted); margin-top: 4px; }

            .form-control,
            .form-select {
                width: 100%;
                border: 1.5px solid var(--border-dark);
                border-radius: var(--radius-md);
                padding: 10px 13px;
                font-size: 14px;
                font-family: 'DM Sans', sans-serif;
                color: var(--text-dark);
                background: var(--white);
                transition: border-color 0.2s, box-shadow 0.2s;
            }

            .form-control:focus,
            .form-select:focus {
                border-color: var(--navy-mid);
                box-shadow: 0 0 0 3px rgba(26,45,85,.09);
                outline: none;
            }

            .form-control[readonly] {
                background: var(--off-white);
                color: var(--text-muted);
                cursor: not-allowed;
                border-color: var(--border);
            }

            textarea.form-control { resize: vertical; min-height: 80px; }

            .f-row {
                display: grid;
                gap: 18px 24px;
                margin-bottom: 18px;
            }
            .f-row:last-child { margin-bottom: 0; }

            .f-row.cols-2  { grid-template-columns: repeat(2, 1fr); }
            .f-row.cols-3  { grid-template-columns: repeat(3, 1fr); }
            .f-row.cols-4  { grid-template-columns: repeat(4, 1fr); }
            .f-row.cols-5  { grid-template-columns: repeat(5, 1fr); }
            .f-row.cols-6  { grid-template-columns: repeat(6, 1fr); }

            @media (max-width: 1100px) {
                .f-row.cols-6 { grid-template-columns: repeat(3, 1fr); }
                .f-row.cols-5 { grid-template-columns: repeat(3, 1fr); }
            }
            @media (max-width: 900px) {
                .f-row.cols-4 { grid-template-columns: repeat(2, 1fr); }
                .f-row.cols-3 { grid-template-columns: repeat(2, 1fr); }
            }
            @media (max-width: 640px) {
                .f-row.cols-2,
                .f-row.cols-3,
                .f-row.cols-4,
                .f-row.cols-5,
                .f-row.cols-6 { grid-template-columns: 1fr; }
            }

            .col-span-2 { grid-column: span 2; }
            .col-span-3 { grid-column: span 3; }

            @media (max-width: 900px) {
                .col-span-2,
                .col-span-3 { grid-column: span 1; }
            }

            /* =========================================================
               O'LEVEL RESULT ITEM
            ========================================================= */
            .olevel-item {
                background: var(--off-white);
                border: 1px solid var(--border);
                border-radius: var(--radius-lg);
                padding: 24px;
                margin-bottom: 16px;
                position: relative;
            }

            .olevel-item-head {
                display: flex; align-items: center; justify-content: space-between;
                margin-bottom: 18px;
                flex-wrap: wrap; gap: 8px;
            }

            .olevel-item-label {
                font-size: 13px; font-weight: 700;
                color: var(--navy);
                display: flex; align-items: center; gap: 8px;
            }

            .olevel-item-label .idx-badge {
                background: var(--navy);
                color: var(--gold-light);
                font-size: 11px; font-weight: 700;
                padding: 2px 8px;
                border-radius: 50px;
            }

            .btn-remove {
                background: transparent;
                border: 1px solid rgba(192,57,43,0.3);
                border-radius: var(--radius-sm);
                color: var(--red);
                font-size: 12px; font-weight: 600;
                padding: 5px 12px;
                cursor: pointer;
                display: inline-flex; align-items: center; gap: 5px;
                transition: all 0.2s;
            }
            .btn-remove:hover { background: var(--red); color: #fff; border-color: var(--red); }

            .grades-divider {
                font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.8px;
                color: var(--text-muted);
                margin: 14px 0 12px;
                padding-bottom: 8px;
                border-bottom: 1px dashed var(--border);
            }

            /* =========================================================
               PASSPORT SECTION - FIXED preview without prompt
            ========================================================= */
            .passport-wrap {
                display: grid;
                grid-template-columns: 200px 1fr;
                gap: 28px;
                align-items: start;
            }

            @media (max-width: 640px) {
                .passport-wrap { grid-template-columns: 1fr; }
            }

            .passport-preview-box {
                width: 200px;
                height: 200px;
                border: 2px dashed var(--border-dark);
                border-radius: var(--radius-md);
                display: flex;
                align-items: center;
                justify-content: center;
                overflow: hidden;
                background: var(--off-white);
                transition: border-color 0.2s;
                position: relative;
            }

            .passport-preview-box.has-image {
                border-style: solid;
                border-color: var(--teal);
                border-width: 3px;
            }

            .passport-preview-box img {
                width: 100%;
                height: 100%;
                object-fit: cover;
                display: none;
            }

            .passport-preview-box.has-image img {
                display: block;
            }

            .passport-preview-box .placeholder-icon {
                font-size: 48px;
                color: var(--border-dark);
            }

            .passport-preview-box.has-image .placeholder-icon {
                display: none;
            }

            .passport-upload-area h6 { font-size: 14px; font-weight: 600; color: var(--text-dark); margin-bottom: 6px; }
            .passport-upload-area p  { font-size: 12px; color: var(--text-muted); margin-bottom: 14px; }

            /* =========================================================
               BUTTONS
            ========================================================= */
            .btn {
                font-family: 'DM Sans', sans-serif;
                font-size: 13px; font-weight: 600;
                border-radius: var(--radius-md);
                border: none; cursor: pointer;
                display: inline-flex; align-items: center; gap: 7px;
                transition: all 0.2s;
                text-decoration: none;
                padding: 10px 22px;
            }

            .btn-navy {
                background: var(--navy); color: #fff;
                box-shadow: 0 4px 12px rgba(15,27,53,0.22);
            }
            .btn-navy:hover { background: var(--navy-light); color: #fff; transform: translateY(-1px); }

            .btn-gold {
                background: var(--gold); color: var(--navy);
                box-shadow: 0 4px 12px rgba(200,150,58,0.28);
            }
            .btn-gold:hover { background: var(--gold-light); transform: translateY(-1px); }

            .btn-teal {
                background: var(--teal); color: #fff;
                box-shadow: 0 4px 12px rgba(29,138,122,0.25);
            }
            .btn-teal:hover { background: #16756a; color: #fff; transform: translateY(-1px); }

            .btn-ghost {
                background: transparent; color: var(--text-body);
                border: 1.5px solid var(--border-dark);
            }
            .btn-ghost:hover { background: var(--off-white); border-color: var(--navy); color: var(--navy); }

            .btn-outline-teal {
                background: transparent; color: var(--teal);
                border: 1.5px solid var(--teal);
            }
            .btn-outline-teal:hover { background: var(--teal); color: #fff; }

            .btn-lg { padding: 13px 32px; font-size: 14px; }
            .btn-sm { padding: 7px 16px; font-size: 12px; }

            /* =========================================================
               FORM ACTION BAR
            ========================================================= */
            .action-bar {
                display: flex;
                align-items: center;
                justify-content: space-between;
                padding: 24px 36px;
                background: var(--off-white);
                border-top: 1px solid var(--border);
                flex-wrap: wrap;
                gap: 12px;
            }

            .action-bar-right { display: flex; gap: 10px; flex-wrap: wrap; }

            /* =========================================================
               FOOTER
            ========================================================= */
            .page-footer {
                text-align: center;
                padding: 28px 0 0;
                font-size: 13px;
                color: var(--text-muted);
            }
            .page-footer a { color: var(--navy-mid); text-decoration: none; font-weight: 500; }
            .page-footer a:hover { color: var(--gold); }
            .page-footer i { color: var(--gold); font-size: 11px; margin-right: 4px; }

            /* =========================================================
               ERROR LIST
            ========================================================= */
            .error-list {
                background: var(--red-light);
                border: 1px solid rgba(192,57,43,.25);
                border-left: 3px solid var(--red);
                border-radius: var(--radius-md);
                padding: 16px 20px;
                margin-bottom: 20px;
            }
            .error-list h6 { color: var(--red); font-weight: 700; margin-bottom: 8px; font-size: 13px; }
            .error-list ul { margin: 0; padding-left: 18px; font-size: 13px; color: #8b1a12; }
            .error-list ul li + li { margin-top: 4px; }

            /* =========================================================
               LOADING OVERLAY
            ========================================================= */
            .loading-overlay {
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background: rgba(255,255,255,0.9);
                display: flex;
                align-items: center;
                justify-content: center;
                z-index: 9999;
                visibility: hidden;
                opacity: 0;
                transition: all 0.3s;
            }
            .loading-overlay.show {
                visibility: visible;
                opacity: 1;
            }
            .spinner {
                width: 50px;
                height: 50px;
                border: 3px solid var(--border);
                border-top-color: var(--navy);
                border-radius: 50%;
                animation: spin 1s linear infinite;
            }
            @keyframes spin {
                to { transform: rotate(360deg); }
            }

            /* =========================================================
               UTILITY
            ========================================================= */
            .mb-0 { margin-bottom: 0 !important; }
            .mt-4 { margin-top: 16px; }
            .text-center { text-align: center; }
            </style>

            <!-- ========================================================= -->
            <!-- 5. Add SRI hashes to external scripts/styles -->
            <!-- ========================================================= -->
            <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" 
                  rel="stylesheet"
                  integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" 
                  crossorigin="anonymous">
            <link rel="stylesheet" 
                  href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"
                  integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw=="
                  crossorigin="anonymous" 
                  referrerpolicy="no-referrer">
            <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700&family=DM+Sans:wght@300;400;500;600&display=swap" 
                  rel="stylesheet"
                  integrity="sha384-0pCryB3hBqYHZO9dKsIIzN8wH+Z4k5P+GZ8TlqM9m8A3TlPI9c7JZ6nG+K/t9fb"
                  crossorigin="anonymous">
        </head>
        <body>
        <div class="page-shell">

            <!-- ===== STEP INDICATOR - 5 STEPS WITH PROPER TRACKING (STEP 3 ACTIVE) ===== -->
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

            <!-- ===== LOADING OVERLAY ===== -->
            <div class="loading-overlay" id="loadingOverlay">
                <div class="spinner"></div>
            </div>

            <!-- ===== FLASH MESSAGES ===== -->
            <?php if (!empty($flash_success)): ?>
            <div class="flash-alert success">
                <i class="fas fa-check-circle"></i>
                <span><?php echo $this->e($flash_success); ?></span>
            </div>
            <?php unset($_SESSION['flash_success']); endif; ?>

            <?php if (!empty($flash_error)): ?>
            <div class="flash-alert error">
                <i class="fas fa-exclamation-circle"></i>
                <span><?php echo $this->e($flash_error); ?></span>
            </div>
            <?php unset($_SESSION['flash_error']); endif; ?>

            <?php if (!empty($temp_password)): ?>
            <div class="temp-pw-box">
                <h6><i class="fas fa-key" style="color:var(--gold);margin-right:6px;"></i> Your Login Password</h6>
                <p style="font-size:13px;color:var(--text-muted);margin-bottom:4px;">
                    Save this password — you'll need it to log in later. It will also be sent to your email.
                </p>
                <div class="temp-pw-code"><?php echo $this->e($temp_password); ?></div>
            </div>
            <?php endif; ?>

            <!-- ===== JAMB VERIFIED BANNER ===== -->
            <div class="jamb-banner">
                <div class="jamb-banner-left">
                    <div class="jamb-check"><i class="fas fa-check"></i></div>
                    <div>
                        <div class="jamb-info-title">JAMB Verified Successfully</div>
                        <div class="jamb-info-sub">
                            <strong><?php echo $this->e(($jamb_data['first_name'] ?? $application['first_name'] ?? '') . ' ' . ($jamb_data['last_name'] ?? $application['last_name'] ?? '')); ?></strong>
                            &nbsp;|&nbsp;
                            JAMB Reg: <strong><?php echo $this->e($jamb_data['jamb_number'] ?? $application['jamb_number'] ?? '—'); ?></strong>
                        </div>
                    </div>
                </div>
                <div style="display:flex;align-items:center;gap:12px;flex-wrap:wrap;">
                    <?php if (!empty($jamb_data['score'] ?? $application['utme_score'] ?? '')): ?>
                    <div class="jamb-score-pill">
                        Score: <?php echo $this->e($jamb_data['score'] ?? $application['utme_score']); ?>
                    </div>
                    <?php endif; ?>
                    <a href="/applicant/logout" class="logout-btn"
                       onclick="return confirm('Are you sure you want to logout? Your progress will be saved.');">
                        <i class="fas fa-sign-out-alt"></i> Logout
                    </a>
                </div>
            </div>

            <!-- ===== ERROR DISPLAY ===== -->
            <?php if (!empty($errors)): ?>
            <div class="error-list">
                <h6><i class="fas fa-exclamation-triangle" style="margin-right:6px;"></i>Please fix the following errors:</h6>
                <ul>
                    <?php foreach ($errors as $err): ?>
                    <li><?php echo $this->e($err); ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
            <?php endif; ?>

            <!-- ===== MAIN FORM CARD ===== -->
            <div class="form-card">
                <form method="POST" action="/apply/save-application" enctype="multipart/form-data"
                      class="needs-validation" novalidate id="mainForm">
                    
                    <!-- ========================================================= -->
                    <!-- 6. Add CSRF token to all forms -->
                    <!-- ========================================================= -->
                    <input type="hidden" name="csrf_token" value="<?php echo $this->e($csrf_token); ?>">
                    
                    <input type="hidden" name="action"            id="form_action" value="save">
                    <input type="hidden" name="jamb_number"       value="<?php echo $this->e($jamb_data['jamb_number']       ?? $application['jamb_number']       ?? ''); ?>">
                    <input type="hidden" name="utme_score"        value="<?php echo $this->e($jamb_data['score']             ?? $application['utme_score']         ?? ''); ?>">
                    <input type="hidden" name="first_name"        value="<?php echo $this->e($jamb_data['first_name']        ?? $application['first_name']         ?? ''); ?>">
                    <input type="hidden" name="last_name"         value="<?php echo $this->e($jamb_data['last_name']         ?? $application['last_name']          ?? ''); ?>">
                    <input type="hidden" name="other_names"       value="<?php echo $this->e($jamb_data['other_names']       ?? $application['other_names']        ?? ''); ?>">
                    <input type="hidden" name="gender"            value="<?php echo $this->e($jamb_data['gender']            ?? $application['gender']             ?? ''); ?>">
                    <input type="hidden" name="state_of_origin"   value="<?php echo $this->e($jamb_data['state_of_origin']   ?? $application['state_of_origin']    ?? ''); ?>">
                    <input type="hidden" name="lga"               value="<?php echo $this->e($jamb_data['lga']               ?? $application['lga']                ?? ''); ?>">
                    <input type="hidden" name="program_choice_2"  value="">
                    <input type="hidden" name="program_choice_3"  value="">

                    <!-- ── SECTION 1: Personal Information ── -->
                    <div class="f-section">
                        <div class="f-section-head">
                            <div class="f-section-icon"><i class="fas fa-user"></i></div>
                            <div>
                                <div class="f-section-title">Personal Information</div>
                                <div class="f-section-sub">Fields from your JAMB record are read-only. Please verify they are correct.</div>
                            </div>
                        </div>

                        <!-- Row 1: Names — 3 columns -->
                        <div class="f-row cols-3">
                            <div>
                                <label class="field-label">First Name</label>
                                <input type="text" class="form-control"
                                       value="<?php echo $this->e($jamb_data['first_name'] ?? $application['first_name'] ?? ''); ?>" readonly>
                            </div>
                            <div>
                                <label class="field-label">Last Name</label>
                                <input type="text" class="form-control"
                                       value="<?php echo $this->e($jamb_data['last_name'] ?? $application['last_name'] ?? ''); ?>" readonly>
                            </div>
                            <div>
                                <label class="field-label">Other Names</label>
                                <input type="text" class="form-control"
                                       value="<?php echo $this->e($jamb_data['other_names'] ?? $application['other_names'] ?? ''); ?>" readonly>
                            </div>
                        </div>

                        <!-- Row 2: Gender / State / LGA — 3 columns -->
                        <div class="f-row cols-3">
                            <div>
                                <label class="field-label">Gender</label>
                                <?php
                                    $g = $jamb_data['gender'] ?? $application['gender'] ?? '';
                                    $gText = $g === 'M' ? 'Male' : ($g === 'F' ? 'Female' : $g);
                                ?>
                                <input type="text" class="form-control" value="<?php echo $this->e($gText); ?>" readonly>
                            </div>
                            <div>
                                <label class="field-label">State of Origin</label>
                                <input type="text" class="form-control"
                                       value="<?php echo $this->e($jamb_data['state_of_origin'] ?? $application['state_of_origin'] ?? ''); ?>" readonly>
                            </div>
                            <div>
                                <label class="field-label">LGA</label>
                                <input type="text" class="form-control"
                                       value="<?php echo $this->e($jamb_data['lga'] ?? $application['lga'] ?? ''); ?>" readonly>
                            </div>
                        </div>

                        <!-- Row 3: DOB / Nationality — 2 columns -->
                        <div class="f-row cols-2">
                            <div>
                                <label class="field-label">Date of Birth <span class="req">*</span></label>
                                <input type="date" class="form-control" id="date_of_birth" name="date_of_birth"
                                       value="<?php echo $this->e($application['date_of_birth'] ?? ''); ?>" required>
                                <div class="invalid-feedback">Date of birth is required.</div>
                            </div>
                            <div>
                                <label class="field-label">Nationality</label>
                                <input type="text" class="form-control" id="nationality" name="nationality"
                                       value="<?php echo $this->e($application['nationality'] ?? 'Nigerian'); ?>">
                            </div>
                        </div>

                        <!-- Row 4: Email / Phone — 2 columns -->
                        <div class="f-row cols-2">
                            <div>
                                <label class="field-label">Email Address <span class="req">*</span></label>
                                <input type="email" class="form-control" id="email" name="email"
                                       value="<?php echo $this->e($application['email'] ?? ($applicant['email'] ?? '')); ?>" required>
                                <div class="field-hint">Login credentials will be sent to this email</div>
                                <div class="invalid-feedback">A valid email address is required.</div>
                            </div>
                            <div>
                                <label class="field-label">Phone Number <span class="req">*</span></label>
                                <input type="tel" class="form-control" id="phone" name="phone"
                                       value="<?php echo $this->e($application['phone'] ?? ($applicant['phone'] ?? '')); ?>"
                                       pattern="[0-9]{11}" maxlength="11" placeholder="08012345678" required>
                                <div class="field-hint">11-digit Nigerian mobile number</div>
                                <div class="invalid-feedback">A valid 11-digit phone number is required.</div>
                            </div>
                        </div>

                        <!-- Row 5: Address — full width -->
                        <div class="f-row cols-2" style="grid-template-columns:1fr;">
                            <div>
                                <label class="field-label">Contact Address <span class="req">*</span></label>
                                <textarea class="form-control" id="address" name="address" rows="2"
                                          placeholder="Enter your full residential address" required><?php echo $this->e($application['address'] ?? ''); ?></textarea>
                                <div class="invalid-feedback">Address is required.</div>
                            </div>
                        </div>
                    </div>

                    <!-- ── SECTION 2: Programme Choice ── -->
                    <div class="f-section">
                        <div class="f-section-head">
                            <div class="f-section-icon"><i class="fas fa-graduation-cap"></i></div>
                            <div>
                                <div class="f-section-title">Programme Choice</div>
                                <div class="f-section-sub">Select your preferred programme of study</div>
                            </div>
                        </div>

                        <div class="f-row cols-3">
                            <div>
                                <label class="field-label">Select Programme <span class="req">*</span></label>
                                <select class="form-select" id="program_choice_1" name="program_choice_1" required>
                                    <option value="">— Select Programme —</option>
                                    <option value="ND Nursing"       <?php echo ($application['program_choice_1'] ?? '') == 'ND Nursing'       ? 'selected' : ''; ?>>ND Nursing</option>
                                    <option value="Post Basic Nursing" <?php echo ($application['program_choice_1'] ?? '') == 'Post Basic Nursing' ? 'selected' : ''; ?>>Post Basic Nursing</option>
                                </select>
                                <div class="invalid-feedback">Please select your programme.</div>
                            </div>
                        </div>
                    </div>

                    <!-- ── SECTION 3: O'Level Results ── -->
                    <div class="f-section">
                        <div class="f-section-head">
                            <div class="f-section-icon"><i class="fas fa-certificate"></i></div>
                            <div>
                                <div class="f-section-title">O'Level Results</div>
                                <div class="f-section-sub">Credit passes required in English, Mathematics, Biology, Chemistry, and Physics</div>
                            </div>
                        </div>

                        <div id="olevel-results-container">
                            <?php
                            $olevelItems = !empty($olevel_results) ? $olevel_results : [[]];
                            foreach ($olevelItems as $idx => $result):
                                $examType = $result['exam_type'] ?? 'WAEC';
                                $examYear = $result['exam_year'] ?? '';
                                $examNum  = $result['exam_number'] ?? '';
                                $sitting  = $result['sitting'] ?? '1st';
                                $grades   = ['english','mathematics','biology','chemistry','physics'];
                                $allGrades = ['A1','B2','B3','C4','C5','C6','D7','E8','F9'];
                            ?>
                            <div class="olevel-item">
                                <div class="olevel-item-head">
                                    <div class="olevel-item-label">
                                        <span class="idx-badge"><?php echo $this->e($idx + 1); ?></span>
                                        O'Level Result — Sitting <?php echo $this->e($idx + 1); ?>
                                    </div>
                                    <?php if ($idx > 0): ?>
                                    <button type="button" class="btn-remove" onclick="this.closest('.olevel-item').remove()">
                                        <i class="fas fa-trash-alt"></i> Remove
                                    </button>
                                    <?php endif; ?>
                                </div>

                                <!-- Exam meta: 4 columns -->
                                <div class="f-row cols-4">
                                    <div>
                                        <label class="field-label">Exam Type</label>
                                        <select class="form-select" name="olevel[<?php echo $this->e($idx); ?>][exam_type]" required>
                                            <?php foreach (['WAEC','NECO','NABTEB'] as $et): ?>
                                            <option value="<?php echo $this->e($et); ?>" <?php echo $examType == $et ? 'selected' : ''; ?>><?php echo $this->e($et); ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    <div>
                                        <label class="field-label">Exam Year</label>
                                        <input type="text" class="form-control" name="olevel[<?php echo $this->e($idx); ?>][exam_year]"
                                               value="<?php echo $this->e($examYear); ?>" placeholder="e.g. 2022" required>
                                    </div>
                                    <div>
                                        <label class="field-label">Exam / Centre Number</label>
                                        <input type="text" class="form-control" name="olevel[<?php echo $this->e($idx); ?>][exam_number]"
                                               value="<?php echo $this->e($examNum); ?>" placeholder="Optional">
                                    </div>
                                    <div>
                                        <label class="field-label">Sitting</label>
                                        <select class="form-select" name="olevel[<?php echo $this->e($idx); ?>][sitting]">
                                            <option value="1st" <?php echo $sitting == '1st' ? 'selected' : ''; ?>>1st Sitting</option>
                                            <option value="2nd" <?php echo $sitting == '2nd' ? 'selected' : ''; ?>>2nd Sitting</option>
                                        </select>
                                    </div>
                                </div>

                                <!-- Grade dropdowns: 5 subjects across full width -->
                                <div class="grades-divider">Subject Grades</div>
                                <div class="f-row cols-5">
                                    <?php foreach ($grades as $subj): ?>
                                    <div>
                                        <label class="field-label"><?php echo $this->e(ucfirst($subj)); ?></label>
                                        <select class="form-select" name="olevel[<?php echo $this->e($idx); ?>][<?php echo $this->e($subj); ?>_grade]" required>
                                            <option value="">Grade</option>
                                            <?php foreach ($allGrades as $grade): ?>
                                            <option value="<?php echo $this->e($grade); ?>"
                                                <?php echo ($result[$subj.'_grade'] ?? '') == $grade ? 'selected' : ''; ?>>
                                                <?php echo $this->e($grade); ?>
                                            </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </div>

                        <div class="mt-4">
                            <button type="button" class="btn btn-outline-teal btn-sm" id="add-olevel">
                                <i class="fas fa-plus"></i> Add Another Sitting
                            </button>
                            <span style="font-size:12px;color:var(--text-muted);margin-left:10px;">Maximum 2 sittings</span>
                        </div>
                    </div>

                    <!-- ── SECTION 4: Passport Photo ── -->
                    <div class="f-section">
                        <div class="f-section-head">
                            <div class="f-section-icon"><i class="fas fa-camera"></i></div>
                            <div>
                                <div class="f-section-title">Passport Photograph</div>
                                <div class="f-section-sub">Recent passport photograph — max 500KB, JPG or PNG only</div>
                            </div>
                        </div>

                        <div class="passport-wrap">
                            <div class="passport-preview-box" id="passportBox">
                                <i class="fas fa-user placeholder-icon" id="passportPlaceholder"></i>
                                <?php if (!empty($application['passport_photo'])): ?>
                                <img src="<?php echo $this->e($application['passport_photo']); ?>" alt="Passport" id="passport-preview"
                                     style="display:block; width:100%; height:100%; object-fit:cover;"
                                     onload="document.getElementById('passportBox').classList.add('has-image');">
                                <?php else: ?>
                                <img src="" alt="Passport Preview" id="passport-preview" style="display:none;">
                                <?php endif; ?>
                            </div>
                            <div class="passport-upload-area">
                                <h6>Select Passport Photo</h6>
                                <p>Ensure the photo clearly shows your face on a plain white background.</p>
                                <input type="hidden" name="passport_confirmed" id="passport-confirmed" value="<?php echo !empty($application['passport_photo']) ? '1' : '0'; ?>">
                                <input type="file" class="form-control" id="passport" name="passport"
                                       accept="image/jpeg,image/png"
                                       onchange="previewPassport(this)"
                                       style="margin-bottom:8px;">
                                <div class="field-hint">Allowed formats: JPG, PNG &nbsp;|&nbsp; Maximum size: 500 KB</div>
                            </div>
                        </div>
                    </div>

                    <!-- ── ACTION BAR ── -->
                    <div class="action-bar">
                        <a href="/apply/step/2" class="btn btn-ghost"
                           onclick="return confirm('Go back to JAMB verification? Unsaved changes may be lost.');">
                            <i class="fas fa-arrow-left"></i> Back
                        </a>
                        <div class="action-bar-right">
                            <button type="submit" class="btn btn-navy" id="saveBtn">
                                <i class="fas fa-save"></i> Save Progress
                            </button>
                            <button type="submit" class="btn btn-teal btn-lg" id="nextBtn">
                                Save &amp; Continue <i class="fas fa-arrow-right"></i>
                            </button>
                        </div>
                    </div>

                </form>
            </div>

            <!-- ===== FOOTER ===== -->
            <div class="page-footer">
                <p style="margin-bottom:6px;">
                    &copy; <?php echo $this->e(date('Y')); ?> FCT College of Nursing Sciences. All rights reserved.
                </p>
                <p>
                    <i class="fas fa-phone-alt"></i> 07039837749
                    &nbsp;|&nbsp;
                    <i class="fas fa-envelope"></i>
                    <a href="mailto:info@fctcns.edu.ng">info@fctcns.edu.ng</a>
                </p>
            </div>

        </div><!-- end page-shell -->

        <!-- ========================================================= -->
        <!-- 7. Add CSP nonce to all script tags and SRI hashes to external scripts -->
        <!-- ========================================================= -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"
                integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz"
                crossorigin="anonymous"
                nonce="<?php echo $csp_nonce; ?>"></script>
        
        <script nonce="<?php echo $csp_nonce; ?>">
        /* ======================================================
           STEP INDICATOR - Current step is 3 (Application Form)
        ====================================================== */
        // This is handled by PHP above - currentStep = 3

        // Get CSRF token from meta tag
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        /* ======================================================
           O'Level — Add another sitting
        ====================================================== */
        let olevelIndex = <?php echo max(count($olevel_results ?: [[]]), 1); ?>;

        document.getElementById('add-olevel').addEventListener('click', function () {
            if (olevelIndex >= 2) {
                alert('Maximum of 2 sittings allowed.');
                return;
            }

            const grades = ['English','Mathematics','Biology','Chemistry','Physics'];
            const gradeKeys = ['english','mathematics','biology','chemistry','physics'];
            const gradeOptions = ['A1','B2','B3','C4','C5','C6','D7','E8','F9']
                .map(g => `<option value="${g}">${g}</option>`).join('');

            const gradeFields = gradeKeys.map((key, i) => `
                <div>
                    <label class="field-label">${grades[i]}</label>
                    <select class="form-select" name="olevel[${olevelIndex}][${key}_grade]" required>
                        <option value="">Grade</option>${gradeOptions}
                    </select>
                </div>`).join('');

            const html = `
            <div class="olevel-item">
                <div class="olevel-item-head">
                    <div class="olevel-item-label">
                        <span class="idx-badge">${olevelIndex + 1}</span>
                        O'Level Result — Sitting ${olevelIndex + 1}
                    </div>
                    <button type="button" class="btn-remove" onclick="this.closest('.olevel-item').remove()">
                        <i class="fas fa-trash-alt"></i> Remove
                    </button>
                </div>
                <div class="f-row cols-4">
                    <div>
                        <label class="field-label">Exam Type</label>
                        <select class="form-select" name="olevel[${olevelIndex}][exam_type]" required>
                            <option value="WAEC">WAEC</option>
                            <option value="NECO">NECO</option>
                            <option value="NABTEB">NABTEB</option>
                        </select>
                    </div>
                    <div>
                        <label class="field-label">Exam Year</label>
                        <input type="text" class="form-control" name="olevel[${olevelIndex}][exam_year]" placeholder="e.g. 2023" required>
                    </div>
                    <div>
                        <label class="field-label">Exam / Centre Number</label>
                        <input type="text" class="form-control" name="olevel[${olevelIndex}][exam_number]" placeholder="Optional">
                    </div>
                    <div>
                        <label class="field-label">Sitting</label>
                        <select class="form-select" name="olevel[${olevelIndex}][sitting]">
                            <option value="1st">1st Sitting</option>
                            <option value="2nd" selected>2nd Sitting</option>
                        </select>
                    </div>
                </div>
                <div class="grades-divider">Subject Grades</div>
                <div class="f-row cols-5">${gradeFields}</div>
            </div>`;

            document.getElementById('olevel-results-container').insertAdjacentHTML('beforeend', html);
            olevelIndex++;
        });

        /* ======================================================
           Passport upload - FIXED: No prompt, immediate preview
        ====================================================== */
        function previewPassport(input) {
            if (!input.files || !input.files[0]) return;

            const file = input.files[0];
            if (file.size > 500 * 1024) {
                alert('File is too large. Maximum size is 500 KB.');
                input.value = '';
                return;
            }

            const reader = new FileReader();
            reader.onload = function (e) {
                const img = document.getElementById('passport-preview');
                const box = document.getElementById('passportBox');
                const placeholder = document.getElementById('passportPlaceholder');
                
                // Show image, hide placeholder
                img.src = e.target.result;
                img.style.display = 'block';
                placeholder.style.display = 'none';
                box.classList.add('has-image');
                document.getElementById('passport-confirmed').value = '1';
            };
            reader.readAsDataURL(file);
        }

        /* ======================================================
           AJAX Form Submission with Redirect Handling
           UPDATED: Uses CSRF token from meta tag
        ====================================================== */
        (function() {
            'use strict';
            
            const form = document.getElementById('mainForm');
            const loadingOverlay = document.getElementById('loadingOverlay');
            const saveBtn = document.getElementById('saveBtn');
            const nextBtn = document.getElementById('nextBtn');
            
            // Remove default form submission
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                
                // Validate form
                if (!form.checkValidity()) {
                    e.stopPropagation();
                    form.classList.add('was-validated');
                    return;
                }
                
                form.classList.add('was-validated');
                
                // Show loading overlay
                loadingOverlay.classList.add('show');
                
                // Determine action (save or next)
                const action = e.submitter ? (e.submitter.id === 'nextBtn' ? 'next' : 'save') : 'save';
                document.getElementById('form_action').value = action;
                
                // Create FormData
                const formData = new FormData(form);
                
                // Ensure CSRF token is included (already in form as hidden input)
                
                // Send AJAX request
                fetch('/apply/save-application', {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': csrfToken // Also send in header for APIs that prefer it
                    }
                })
                .then(response => response.json())
                .then(data => {
                    loadingOverlay.classList.remove('show');
                    
                    if (data.success) {
                        // Show success message
                        alert(data.message);
                        
                        // Handle redirect if present
                        if (data.redirect) {
                            window.location.href = data.redirect;
                        } else {
                            // Just saved, maybe show a temporary message
                            console.log('Application saved');
                        }
                    } else {
                        // Show error message
                        alert('Error: ' + data.message);
                    }
                })
                .catch(error => {
                    loadingOverlay.classList.remove('show');
                    console.error('Error:', error);
                    alert('An error occurred. Please try again.');
                });
            });
            
            // Handle button clicks to ensure proper action value
            saveBtn.addEventListener('click', function() {
                document.getElementById('form_action').value = 'save';
            });
            
            nextBtn.addEventListener('click', function() {
                document.getElementById('form_action').value = 'next';
            });
        })();

        /* ======================================================
           Bootstrap native validation (fallback)
        ====================================================== */
        (function () {
            'use strict';
            var forms = document.querySelectorAll('.needs-validation');
            Array.prototype.slice.call(forms).forEach(function (form) {
                form.addEventListener('submit', function (event) {
                    if (!form.checkValidity()) {
                        event.preventDefault();
                        event.stopPropagation();
                    }
                    form.classList.add('was-validated');
                }, false);
            });
        })();
        </script>
        </body>
        </html>
        <?php
    }
}

// =========================================================
// 8. Add the view instantiation at the bottom
// =========================================================
$view = new ApplicationFormView();
$view->render(get_defined_vars());
?>