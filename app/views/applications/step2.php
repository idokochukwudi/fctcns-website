<?php
/**
 * Step 2: Application Form View
 * FIXED: Correct step tracking - this is Step 3 (Application Form)
 * UPDATED: Purple color scheme matching JAMB verification page
 * FIXED 3a: Added O'Level credit status banner
 * FIXED 3b: Added subject-level grade feedback panel
 * FIXED 3c: Replaced JavaScript with credit check functionality
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
        
        // FIX 3a: Get credit summary from controller data
        $credit_summary = $credit_summary ?? null;
        $olevel_session_error = $olevel_session_error ?? null;

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
               BASE - Purple Theme Matching JAMB Page
            ========================================================= */
            *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

            :root {
                --sv1-primary:       #6B4E9B;
                --sv1-primary-dark:  #4A3B6B;
                --sv1-primary-light: #8A6FB0;
                --sv1-primary-soft:  #F3EAF8;
                --sv1-gold:          #C9A44A;
                --sv1-gold-light:    #E2B05F;
                --sv1-gold-pale:     #FDF6E9;
                --sv1-success:       #10b981;
                --sv1-success-light: #d1fae5;
                --sv1-danger:        #ef4444;
                --sv1-danger-light:  #fee2e2;
                --sv1-warning:       #f59e0b;
                --sv1-warning-light: #fef3c7;
                --sv1-info:          #3b82f6;
                --sv1-info-light:    #dbeafe;
                --sv1-border:        #E9EDF2;
                --sv1-text-dark:     #1A1F2E;
                --sv1-text-muted:    #6B7280;
                
                --radius-sm:   6px;
                --radius-md:   10px;
                --radius-lg:   16px;
                --radius-xl:   24px;
            }

            html, body {
                width: 100%;
                overflow-x: hidden;
                background: var(--sv1-primary-soft);
                font-family: 'DM Sans', -apple-system, sans-serif;
                font-size: 14px;
                color: var(--sv1-text-dark);
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
               STEP INDICATOR - 5 STEPS WITH PURPLE THEME
            ========================================================= */
            .step-indicator {
                display: flex;
                justify-content: space-between;
                margin-bottom: 30px;
                position: relative;
                background: white;
                border-radius: 50px;
                padding: 15px 20px;
                border: 1px solid var(--sv1-border);
                box-shadow: 0 2px 8px rgba(0,0,0,0.03);
            }

            .step-indicator::before {
                content: '';
                position: absolute;
                top: 50%;
                left: 60px;
                right: 60px;
                height: 2px;
                background: var(--sv1-border);
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
                border: 2px solid var(--sv1-border);
                border-radius: 50%;
                display: flex;
                align-items: center;
                justify-content: center;
                margin: 0 auto 6px;
                font-weight: 600;
                font-size: 14px;
                color: var(--sv1-text-muted);
                transition: all 0.3s;
            }

            .step.active .step-number {
                background: var(--sv1-primary);
                border-color: var(--sv1-primary);
                color: white;
                box-shadow: 0 0 0 4px var(--sv1-primary-soft);
            }

            .step.completed .step-number {
                background: var(--sv1-success);
                border-color: var(--sv1-success);
                color: white;
            }

            .step-label {
                font-size: 11px;
                font-weight: 600;
                color: var(--sv1-text-muted);
                text-transform: uppercase;
                letter-spacing: 0.3px;
                white-space: nowrap;
            }

            .step-sub {
                font-size: 9px;
                color: var(--sv1-text-muted);
                margin-top: 2px;
                opacity: 0.8;
            }

            .step.active .step-label {
                color: var(--sv1-primary);
                font-weight: 700;
            }

            .step.active .step-sub {
                color: var(--sv1-primary);
                opacity: 0.9;
            }

            .step.completed .step-label {
                color: var(--sv1-success);
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
                    background: var(--sv1-primary-soft);
                    border-radius: 30px;
                    border: 1px solid var(--sv1-border);
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
                background: var(--sv1-danger-light);
                border: 1px solid var(--sv1-danger);
                border-radius: 50px;
                padding: 7px 16px;
                font-size: 12px; font-weight: 600;
                color: var(--sv1-danger);
                text-decoration: none;
                transition: all 0.2s;
                white-space: nowrap;
            }

            .logout-btn:hover { background: var(--sv1-danger); color: #fff; border-color: var(--sv1-danger); }

            /* =========================================================
               FLASH ALERTS
            ========================================================= */
            .flash-alert {
                display: flex; align-items: flex-start; gap: 12px;
                padding: 13px 18px; border-radius: var(--radius-md);
                margin-bottom: 16px; font-size: 14px;
                border-left-width: 4px;
                border-left-style: solid;
            }
            .flash-alert.success { 
                background: var(--sv1-success-light);  
                border-left-color: var(--sv1-success); 
                color: #065f46; 
            }
            .flash-alert.error   { 
                background: var(--sv1-danger-light);   
                border-left-color: var(--sv1-danger);  
                color: #991b1b; 
            }
            .flash-alert.warning { 
                background: var(--sv1-warning-light);   
                border-left-color: var(--sv1-warning); 
                color: #92400e; 
            }
            .flash-alert i { margin-top: 1px; flex-shrink: 0; }
            .flash-alert.success i { color: var(--sv1-success); }
            .flash-alert.error i { color: var(--sv1-danger); }
            .flash-alert.warning i { color: var(--sv1-warning); }

            /* Temp password box */
            .temp-pw-box {
                background: var(--sv1-gold-pale);
                border: 1.5px solid var(--sv1-gold);
                border-radius: var(--radius-md);
                padding: 18px 22px;
                margin-bottom: 18px;
            }
            .temp-pw-box h6 { 
                font-weight: 700; 
                color: var(--sv1-primary-dark); 
                margin-bottom: 8px; 
            }
            .temp-pw-code {
                background: var(--white);
                border: 1px solid var(--sv1-border);
                border-radius: var(--radius-sm);
                padding: 12px 20px;
                font-family: 'DM Mono', monospace;
                font-size: 22px;
                font-weight: 700;
                letter-spacing: 4px;
                text-align: center;
                color: var(--sv1-primary);
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
                background: linear-gradient(135deg, var(--sv1-primary), var(--sv1-primary-dark));
                border-radius: var(--radius-md);
                padding: 18px 24px;
                margin-bottom: 24px;
                flex-wrap: wrap;
            }

            .jamb-banner-left { display: flex; align-items: center; gap: 14px; }

            .jamb-check {
                width: 44px; height: 44px; border-radius: 50%;
                background: var(--sv1-success);
                display: flex; align-items: center; justify-content: center;
                color: #fff; font-size: 18px;
                flex-shrink: 0;
            }

            .jamb-info-title { 
                font-size: 14px; font-weight: 700; 
                color: #fff; margin-bottom: 3px; 
            }
            .jamb-info-sub   { 
                font-size: 13px; color: rgba(255,255,255,0.8); 
            }
            .jamb-info-sub strong { color: #fff; }

            .jamb-score-pill {
                background: rgba(201,164,74,0.15);
                border: 1px solid rgba(201,164,74,0.35);
                border-radius: 50px;
                padding: 5px 14px;
                font-size: 12px; font-weight: 700;
                color: var(--sv1-gold-light);
                white-space: nowrap;
            }

            /* =========================================================
               FORM CARD
            ========================================================= */
            .form-card {
                background: white;
                border: 1px solid var(--sv1-border);
                border-radius: var(--radius-xl);
                overflow: hidden;
                box-shadow: 0 4px 24px rgba(107,78,155,0.1);
            }

            /* =========================================================
               SECTION BLOCKS
            ========================================================= */
            .f-section {
                padding: 32px 36px;
                border-bottom: 1px solid var(--sv1-border);
            }

            .f-section:last-child { border-bottom: none; }

            @media (max-width: 768px) { .f-section { padding: 22px 18px; } }

            .f-section-head {
                display: flex; align-items: center; gap: 12px;
                margin-bottom: 24px;
                padding-bottom: 14px;
                border-bottom: 1px solid var(--sv1-border);
            }

            .f-section-icon {
                width: 36px; height: 36px;
                background: var(--sv1-primary);
                border-radius: var(--radius-sm);
                display: flex; align-items: center; justify-content: center;
                font-size: 14px; color: var(--sv1-gold);
                flex-shrink: 0;
            }

            .f-section-title { 
                font-family: 'Playfair Display', serif; 
                font-size: 17px; 
                font-weight: 700; 
                color: var(--sv1-text-dark); 
                margin: 0; 
            }
            .f-section-sub   { 
                font-size: 12px; 
                color: var(--sv1-text-muted); 
                margin-top: 2px; 
            }

            /* =========================================================
               FORM FIELDS
            ========================================================= */
            .field-label {
                display: block;
                font-size: 12px; font-weight: 600;
                color: var(--sv1-primary-dark);
                margin-bottom: 6px;
                letter-spacing: 0.1px;
            }
            .field-label .req { color: var(--sv1-danger); margin-left: 2px; }

            .field-hint { 
                font-size: 11px; 
                color: var(--sv1-text-muted); 
                margin-top: 4px; 
            }

            .form-control,
            .form-select {
                width: 100%;
                border: 2px solid var(--sv1-border);
                border-radius: var(--radius-md);
                padding: 10px 13px;
                font-size: 14px;
                font-family: 'DM Sans', sans-serif;
                color: var(--sv1-text-dark);
                background: white;
                transition: border-color 0.2s, box-shadow 0.2s;
            }

            .form-control:focus,
            .form-select:focus {
                border-color: var(--sv1-primary);
                box-shadow: 0 0 0 4px var(--sv1-primary-soft);
                outline: none;
            }

            .form-control[readonly] {
                background: var(--sv1-primary-soft);
                color: var(--sv1-text-muted);
                cursor: not-allowed;
                border-color: var(--sv1-border);
            }

            textarea.form-control { resize: vertical; min-height: 80px; }

            .invalid-feedback {
                font-size: 11px;
                color: var(--sv1-danger);
                margin-top: 4px;
            }

            .was-validated .form-control:invalid,
            .was-validated .form-select:invalid {
                border-color: var(--sv1-danger);
            }

            .was-validated .form-control:valid,
            .was-validated .form-select:valid {
                border-color: var(--sv1-success);
            }

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
                background: var(--sv1-primary-soft);
                border: 1px solid var(--sv1-border);
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
                color: var(--sv1-primary-dark);
                display: flex; align-items: center; gap: 8px;
            }

            .olevel-item-label .idx-badge {
                background: var(--sv1-primary);
                color: var(--sv1-gold);
                font-size: 11px; font-weight: 700;
                padding: 2px 8px;
                border-radius: 50px;
            }

            .btn-remove {
                background: transparent;
                border: 1px solid var(--sv1-danger);
                border-radius: var(--radius-sm);
                color: var(--sv1-danger);
                font-size: 12px; font-weight: 600;
                padding: 5px 12px;
                cursor: pointer;
                display: inline-flex; align-items: center; gap: 5px;
                transition: all 0.2s;
            }
            .btn-remove:hover { 
                background: var(--sv1-danger); 
                color: #fff; 
                border-color: var(--sv1-danger); 
            }

            .grades-divider {
                font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.8px;
                color: var(--sv1-text-muted);
                margin: 14px 0 12px;
                padding-bottom: 8px;
                border-bottom: 1px dashed var(--sv1-border);
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
                border: 2px dashed var(--sv1-border);
                border-radius: var(--radius-md);
                display: flex;
                align-items: center;
                justify-content: center;
                overflow: hidden;
                background: var(--sv1-primary-soft);
                transition: border-color 0.2s;
                position: relative;
            }

            .passport-preview-box.has-image {
                border-style: solid;
                border-color: var(--sv1-success);
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
                color: var(--sv1-primary-light);
            }

            .passport-preview-box.has-image .placeholder-icon {
                display: none;
            }

            .passport-upload-area h6 { 
                font-size: 14px; 
                font-weight: 600; 
                color: var(--sv1-primary-dark); 
                margin-bottom: 6px; 
            }
            .passport-upload-area p  { 
                font-size: 12px; 
                color: var(--sv1-text-muted); 
                margin-bottom: 14px; 
            }

            /* =========================================================
               BUTTONS - Purple Theme
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
                background: linear-gradient(135deg, var(--sv1-primary), var(--sv1-primary-dark));
                color: #fff;
                box-shadow: 0 4px 12px rgba(107,78,155,0.3);
            }
            .btn-navy:hover { 
                background: var(--sv1-primary-dark); 
                color: #fff; 
                transform: translateY(-1px); 
                box-shadow: 0 8px 20px rgba(107,78,155,0.4);
            }

            .btn-gold {
                background: var(--sv1-gold); 
                color: var(--sv1-primary-dark);
                box-shadow: 0 4px 12px rgba(201,164,74,0.3);
            }
            .btn-gold:hover { 
                background: var(--sv1-gold-light); 
                transform: translateY(-1px); 
            }

            .btn-teal {
                background: linear-gradient(135deg, var(--sv1-primary-light), var(--sv1-primary));
                color: #fff;
                box-shadow: 0 4px 12px rgba(107,78,155,0.3);
            }
            .btn-teal:hover { 
                background: var(--sv1-primary); 
                color: #fff; 
                transform: translateY(-1px); 
            }

            .btn-ghost {
                background: transparent; 
                color: var(--sv1-text-muted);
                border: 2px solid var(--sv1-border);
            }
            .btn-ghost:hover { 
                background: var(--sv1-primary-soft); 
                border-color: var(--sv1-primary); 
                color: var(--sv1-primary); 
            }

            .btn-outline-teal {
                background: transparent; 
                color: var(--sv1-primary);
                border: 2px solid var(--sv1-primary);
            }
            .btn-outline-teal:hover { 
                background: var(--sv1-primary); 
                color: #fff; 
                transform: translateY(-1px);
            }

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
                background: var(--sv1-primary-soft);
                border-top: 1px solid var(--sv1-border);
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
                color: var(--sv1-text-muted);
            }
            .page-footer a { 
                color: var(--sv1-primary); 
                text-decoration: none; 
                font-weight: 500; 
            }
            .page-footer a:hover { 
                color: var(--sv1-gold); 
            }
            .page-footer i { 
                color: var(--sv1-gold); 
                font-size: 11px; 
                margin-right: 4px; 
            }

            /* =========================================================
               ERROR LIST
            ========================================================= */
            .error-list {
                background: var(--sv1-danger-light);
                border: 1px solid var(--sv1-danger);
                border-left: 4px solid var(--sv1-danger);
                border-radius: var(--radius-md);
                padding: 16px 20px;
                margin-bottom: 20px;
            }
            .error-list h6 { 
                color: var(--sv1-danger); 
                font-weight: 700; 
                margin-bottom: 8px; 
                font-size: 13px; 
            }
            .error-list ul { 
                margin: 0; 
                padding-left: 18px; 
                font-size: 13px; 
                color: #991b1b; 
            }
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
                border: 3px solid var(--sv1-border);
                border-top-color: var(--sv1-primary);
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
            <!-- 5. Add SRI hashes to external scripts/styles - FIXED -->
            <!-- ========================================================= -->
            
            <!-- Bootstrap CSS with SRI -->
            <?php 
            $bootstrapCssUrl = 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css';
            $bootstrapCssSri = SecurityHelper::getSriHash($bootstrapCssUrl);
            ?>
            <link href="<?php echo $bootstrapCssUrl; ?>" 
                  rel="stylesheet"
                  <?php if ($bootstrapCssSri): ?>integrity="<?php echo $bootstrapCssSri; ?>"<?php endif; ?>
                  crossorigin="anonymous">
            
            <!-- Font Awesome with conditional SRI -->
            <?php 
            $faUrl = 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css';
            $faSri = SecurityHelper::getSriHash($faUrl);
            ?>
            <link rel="stylesheet" 
                  href="<?php echo $faUrl; ?>"
                  <?php if ($faSri): ?>integrity="<?php echo $faSri; ?>"<?php endif; ?>
                  crossorigin="anonymous" 
                  referrerpolicy="no-referrer">

            <!-- Google Fonts - NO SRI HASH (they change dynamically) -->
            <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700&family=DM+Sans:wght@300;400;500;600&display=swap" 
                  rel="stylesheet"
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
                <h6><i class="fas fa-key" style="color:var(--sv1-gold);margin-right:6px;"></i> Your Login Password</h6>
                <p style="font-size:13px;color:var(--sv1-text-muted);margin-bottom:4px;">
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

            <!-- ===== FIX 3a: O'LEVEL CREDIT STATUS BANNER ===== -->
            <?php if (!empty($olevel_session_error)): ?>
            <div class="flash-alert error" id="olevelSessionError">
                <i class="fas fa-ban"></i>
                <div>
                    <strong>Cannot Proceed to Payment</strong><br>
                    <?php echo $this->e($olevel_session_error); ?>
                </div>
            </div>
            <?php endif; ?>

            <?php if ($credit_summary && !$credit_summary['meets_requirement'] && $credit_summary['total_sittings'] > 0): ?>
            <div style="
                background: #fff3e0;
                border: 2px solid #f57c00;
                border-left: 6px solid #f57c00;
                border-radius: 10px;
                padding: 1.2rem 1.5rem;
                margin-bottom: 1.2rem;
                display: flex;
                align-items: flex-start;
                gap: 1rem;
            " id="olevelWarningBanner">
                <i class="fas fa-triangle-exclamation" style="color:#f57c00;font-size:1.5rem;flex-shrink:0;margin-top:0.1rem;"></i>
                <div style="flex:1;">
                    <div style="font-weight:700;font-size:0.95rem;color:#b45309;margin-bottom:0.4rem;">
                        O'Level Requirement Not Met — <?php echo (int)$credit_summary['credits_achieved']; ?>/5 Credits
                    </div>
                    <div style="font-size:0.85rem;color:#92400e;line-height:1.6;">
                        <?php echo $this->e($credit_summary['message']); ?>
                        <br>
                        You need <strong>credit passes (A1–C6) in all five subjects</strong>: English Language, Mathematics, Biology, Chemistry, and Physics.
                        <?php if ($credit_summary['total_sittings'] < 2): ?>
                        <br>You may add a second sitting below if you sat the exam twice.
                        <?php endif; ?>
                    </div>
                    <div style="margin-top:0.6rem;padding:0.5rem 0.75rem;background:#fff8f0;border-radius:6px;font-size:0.8rem;color:#b45309;">
                        <i class="fas fa-lock" style="margin-right:0.4rem;"></i>
                        <strong>The "Save &amp; Continue" button is disabled</strong> until you meet the minimum credit requirement.
                    </div>
                </div>
            </div>
            <?php elseif ($credit_summary && $credit_summary['meets_requirement'] && $credit_summary['total_sittings'] > 0): ?>
            <div style="
                background: #ecfdf5;
                border: 2px solid #10b981;
                border-left: 6px solid #10b981;
                border-radius: 10px;
                padding: 1rem 1.4rem;
                margin-bottom: 1.2rem;
                display: flex;
                align-items: center;
                gap: 1rem;
            ">
                <i class="fas fa-circle-check" style="color:#10b981;font-size:1.4rem;flex-shrink:0;"></i>
                <div style="font-size:0.88rem;color:#065f46;">
                    <strong>O'Level Requirement Met</strong> — <?php echo (int)$credit_summary['credits_achieved']; ?>/5 credits achieved.
                    You may proceed to payment.
                </div>
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

                        <!-- FIX 3b: Live credit check panel -->
                        <div id="creditCheckPanel" style="margin-top:16px;display:none;">
                            <div id="creditCheckInner" style="
                                background: var(--sv1-primary-soft);
                                border: 1px solid var(--sv1-border);
                                border-radius: 8px;
                                padding: 14px 18px;
                                font-size: 0.82rem;
                            "></div>
                        </div>

                        <div class="mt-4">
                            <button type="button" class="btn btn-outline-teal btn-sm" id="add-olevel">
                                <i class="fas fa-plus"></i> Add Another Sitting
                            </button>
                            <span style="font-size:12px;color:var(--sv1-text-muted);margin-left:10px;">Maximum 2 sittings</span>
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
        <!-- 7. Add CSP nonce to all script tags and SRI hashes to external scripts - FIXED -->
        <!-- ========================================================= -->
        
        <!-- Bootstrap JS with conditional SRI -->
        <?php 
        $bootstrapJsUrl = 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js';
        $bootstrapJsSri = SecurityHelper::getSriHash($bootstrapJsUrl);
        ?>
        <script src="<?php echo $bootstrapJsUrl; ?>" 
                <?php if ($bootstrapJsSri): ?>integrity="<?php echo $bootstrapJsSri; ?>"<?php endif; ?>
                crossorigin="anonymous"
                nonce="<?php echo $csp_nonce; ?>"></script>
        
        <!-- FIX 3c: Replaced entire JavaScript block with credit check functionality -->
        <script nonce="<?php echo $csp_nonce; ?>">
        (function () {
            'use strict';

            // ── CSRF token ────────────────────────────────────────────────
            var csrfToken = (document.querySelector('meta[name="csrf-token"]') || {}).getAttribute('content') || '';

            // ── Credit grades ─────────────────────────────────────────────
            var CREDIT_GRADES  = ['A1','B2','B3','C4','C5','C6'];
            var GRADE_ORDER    = ['A1','B2','B3','C4','C5','C6','D7','E8','F9'];
            var REQUIRED_KEYS  = ['english','mathematics','biology','chemistry','physics'];
            var REQUIRED_LABELS = {
                english: 'English Language',
                mathematics: 'Mathematics',
                biology: 'Biology',
                chemistry: 'Chemistry',
                physics: 'Physics'
            };

            // ── PHP initial credit summary ────────────────────────────────
            var initialMeetsRequirement = <?php echo ($credit_summary && $credit_summary['meets_requirement']) ? 'true' : 'false'; ?>;

            // ── Compute best grades from all olevel-item blocks ───────────
            function computeCreditCheck() {
                var items = document.querySelectorAll('.olevel-item');
                var bestGrades = {};

                items.forEach(function (item) {
                    REQUIRED_KEYS.forEach(function (key) {
                        var sel = item.querySelector('select[name*="[' + key + '_grade]"]');
                        if (!sel || !sel.value) return;
                        var grade = sel.value;
                        if (!bestGrades[key]) {
                            bestGrades[key] = grade;
                        } else {
                            var curRank = GRADE_ORDER.indexOf(bestGrades[key]);
                            var newRank = GRADE_ORDER.indexOf(grade);
                            if (newRank !== -1 && (curRank === -1 || newRank < curRank)) {
                                bestGrades[key] = grade;
                            }
                        }
                    });
                });

                var creditsAchieved = 0;
                var missingSubjects = [];
                var failedSubjects  = [];

                REQUIRED_KEYS.forEach(function (key) {
                    var label = REQUIRED_LABELS[key];
                    if (!bestGrades[key]) {
                        missingSubjects.push(label);
                    } else if (CREDIT_GRADES.indexOf(bestGrades[key]) !== -1) {
                        creditsAchieved++;
                    } else {
                        failedSubjects.push(label + ' (' + bestGrades[key] + ')');
                    }
                });

                return {
                    meetsRequirement: creditsAchieved >= 5,
                    creditsAchieved:  creditsAchieved,
                    missingSubjects:  missingSubjects,
                    failedSubjects:   failedSubjects,
                    bestGrades:       bestGrades
                };
            }

            // ── Render live credit check panel ────────────────────────────
            function renderCreditPanel(result) {
                var panel = document.getElementById('creditCheckPanel');
                var inner = document.getElementById('creditCheckInner');
                if (!panel || !inner) return;

                // Only show panel once at least one grade has been selected
                var hasAnyGrade = Object.keys(result.bestGrades).length > 0;
                panel.style.display = hasAnyGrade ? 'block' : 'none';
                if (!hasAnyGrade) return;

                var rows = REQUIRED_KEYS.map(function (key) {
                    var label = REQUIRED_LABELS[key];
                    var grade = result.bestGrades[key];
                    if (!grade) {
                        return '<span style="color:#ef4444;margin-right:12px;"><i class="fas fa-times-circle"></i> ' + label + ': —</span>';
                    }
                    var isCredit = CREDIT_GRADES.indexOf(grade) !== -1;
                    var color = isCredit ? '#10b981' : '#ef4444';
                    var icon  = isCredit ? 'fa-check-circle' : 'fa-times-circle';
                    return '<span style="color:' + color + ';margin-right:12px;"><i class="fas ' + icon + '"></i> ' + label + ': <strong>' + grade + '</strong></span>';
                }).join('');

                var statusColor = result.meetsRequirement ? '#065f46' : '#92400e';
                var statusBg    = result.meetsRequirement ? '#ecfdf5' : '#fff3e0';
                var statusBorder = result.meetsRequirement ? '#10b981' : '#f57c00';
                var statusIcon  = result.meetsRequirement ? 'fa-circle-check' : 'fa-triangle-exclamation';
                var statusMsg   = result.meetsRequirement
                    ? 'All 5 credits met! You may proceed to payment.'
                    : result.creditsAchieved + '/5 credits. ';

                if (!result.meetsRequirement) {
                    if (result.missingSubjects.length > 0) statusMsg += 'No grade: ' + result.missingSubjects.join(', ') + '. ';
                    if (result.failedSubjects.length  > 0) statusMsg += 'Below credit: ' + result.failedSubjects.join(', ') + '.';
                }

                inner.style.background = statusBg;
                inner.style.border = '1px solid ' + statusBorder;
                inner.innerHTML =
                    '<div style="display:flex;flex-wrap:wrap;gap:4px 0;margin-bottom:8px;">' + rows + '</div>' +
                    '<div style="color:' + statusColor + ';font-weight:600;">' +
                        '<i class="fas ' + statusIcon + '" style="margin-right:6px;"></i>' + statusMsg +
                    '</div>';
            }

            // ── Update warning banner and next button state ───────────────
            function updateUIState(result) {
                var banner  = document.getElementById('olevelWarningBanner');
                var nextBtn = document.getElementById('nextBtn');

                if (nextBtn) {
                    if (result.meetsRequirement) {
                        nextBtn.disabled = false;
                        nextBtn.style.opacity = '';
                        nextBtn.style.cursor  = '';
                        nextBtn.title = '';
                    } else {
                        nextBtn.disabled = true;
                        nextBtn.style.opacity = '0.45';
                        nextBtn.style.cursor  = 'not-allowed';
                        nextBtn.title = 'You must meet the O\'Level credit requirement before proceeding.';
                    }
                }

                if (banner) {
                    banner.style.display = result.meetsRequirement ? 'none' : 'flex';
                }
            }

            // ── Run credit check whenever any grade dropdown changes ───────
            function onGradeChange() {
                var result = computeCreditCheck();
                renderCreditPanel(result);
                updateUIState(result);
            }

            function attachGradeListeners() {
                document.querySelectorAll('.olevel-item select[name*="_grade"]').forEach(function (sel) {
                    sel.removeEventListener('change', onGradeChange);
                    sel.addEventListener('change', onGradeChange);
                });
            }

            // ── Initial state on page load ────────────────────────────────
            attachGradeListeners();
            var initialResult = computeCreditCheck();
            renderCreditPanel(initialResult);

            // If no grades selected yet but PHP says not met, disable next
            if (!initialMeetsRequirement || !initialResult.meetsRequirement) {
                updateUIState({ meetsRequirement: false });
            }

            // ── O'Level — Add another sitting ─────────────────────────────
            var olevelIndex = <?php echo max(count($olevel_results ?: [[]]), 1); ?>;

            document.getElementById('add-olevel').addEventListener('click', function () {
                if (olevelIndex >= 2) {
                    alert('Maximum of 2 sittings allowed.');
                    return;
                }

                var grades    = ['English','Mathematics','Biology','Chemistry','Physics'];
                var gradeKeys = ['english','mathematics','biology','chemistry','physics'];
                var gradeOptions = ['A1','B2','B3','C4','C5','C6','D7','E8','F9']
                    .map(function(g) { return '<option value="' + g + '">' + g + '</option>'; }).join('');

                var gradeFields = gradeKeys.map(function (key, i) {
                    return '<div>' +
                        '<label class="field-label">' + grades[i] + '</label>' +
                        '<select class="form-select" name="olevel[' + olevelIndex + '][' + key + '_grade]" required>' +
                            '<option value="">Grade</option>' + gradeOptions +
                        '</select>' +
                    '</div>';
                }).join('');

                var html = '<div class="olevel-item">' +
                    '<div class="olevel-item-head">' +
                        '<div class="olevel-item-label">' +
                            '<span class="idx-badge">' + (olevelIndex + 1) + '</span>' +
                            ' O\'Level Result — Sitting ' + (olevelIndex + 1) +
                        '</div>' +
                        '<button type="button" class="btn-remove" id="removeOlevel' + olevelIndex + '">' +
                            '<i class="fas fa-trash-alt"></i> Remove' +
                        '</button>' +
                    '</div>' +
                    '<div class="f-row cols-4">' +
                        '<div><label class="field-label">Exam Type</label>' +
                            '<select class="form-select" name="olevel[' + olevelIndex + '][exam_type]" required>' +
                                '<option value="WAEC">WAEC</option>' +
                                '<option value="NECO">NECO</option>' +
                                '<option value="NABTEB">NABTEB</option>' +
                            '</select></div>' +
                        '<div><label class="field-label">Exam Year</label>' +
                            '<input type="text" class="form-control" name="olevel[' + olevelIndex + '][exam_year]" placeholder="e.g. 2023" required></div>' +
                        '<div><label class="field-label">Exam / Centre Number</label>' +
                            '<input type="text" class="form-control" name="olevel[' + olevelIndex + '][exam_number]" placeholder="Optional"></div>' +
                        '<div><label class="field-label">Sitting</label>' +
                            '<select class="form-select" name="olevel[' + olevelIndex + '][sitting]">' +
                                '<option value="1st">1st Sitting</option>' +
                                '<option value="2nd" selected>2nd Sitting</option>' +
                            '</select></div>' +
                    '</div>' +
                    '<div class="grades-divider">Subject Grades</div>' +
                    '<div class="f-row cols-5">' + gradeFields + '</div>' +
                '</div>';

                var container = document.getElementById('olevel-results-container');
                container.insertAdjacentHTML('beforeend', html);

                // Wire up remove button
                var removeBtn = document.getElementById('removeOlevel' + olevelIndex);
                if (removeBtn) {
                    removeBtn.addEventListener('click', function () {
                        this.closest('.olevel-item').remove();
                        onGradeChange();
                        attachGradeListeners();
                    });
                }

                olevelIndex++;
                attachGradeListeners();
                onGradeChange();
            });

            // ── Wire up existing remove buttons ───────────────────────────
            document.querySelectorAll('.btn-remove').forEach(function (btn) {
                btn.addEventListener('click', function () {
                    this.closest('.olevel-item').remove();
                    onGradeChange();
                    attachGradeListeners();
                });
            });

            // ── Passport upload preview ───────────────────────────────────
            window.previewPassport = function (input) {
                if (!input.files || !input.files[0]) return;
                var file = input.files[0];
                if (file.size > 500 * 1024) {
                    alert('File is too large. Maximum size is 500 KB.');
                    input.value = '';
                    return;
                }
                var reader = new FileReader();
                reader.onload = function (e) {
                    var img = document.getElementById('passport-preview');
                    var box = document.getElementById('passportBox');
                    var ph  = document.getElementById('passportPlaceholder');
                    img.src = e.target.result;
                    img.style.display = 'block';
                    if (ph) ph.style.display = 'none';
                    box.classList.add('has-image');
                    document.getElementById('passport-confirmed').value = '1';
                };
                reader.readAsDataURL(file);
            };

            // ── AJAX Form Submission ──────────────────────────────────────
            var form          = document.getElementById('mainForm');
            var loadingOverlay = document.getElementById('loadingOverlay');
            var saveBtn       = document.getElementById('saveBtn');
            var nextBtn       = document.getElementById('nextBtn');

            // Set initial disabled state
            var initCheck = computeCreditCheck();
            if (!initialMeetsRequirement && !initCheck.meetsRequirement) {
                if (nextBtn) {
                    nextBtn.disabled = true;
                    nextBtn.style.opacity = '0.45';
                    nextBtn.style.cursor  = 'not-allowed';
                    nextBtn.title = 'You must meet the O\'Level credit requirement before proceeding.';
                }
            }

            saveBtn.addEventListener('click', function () {
                document.getElementById('form_action').value = 'save';
            });

            nextBtn.addEventListener('click', function (e) {
                // Double-check client side before allowing submission
                var check = computeCreditCheck();
                if (!check.meetsRequirement) {
                    e.preventDefault();
                    e.stopPropagation();

                    var missing = [];
                    if (check.missingSubjects.length > 0) missing.push('No grade entered for: ' + check.missingSubjects.join(', '));
                    if (check.failedSubjects.length  > 0) missing.push('Below credit in: ' + check.failedSubjects.join(', '));

                    alert(
                        '⚠ O\'Level Requirement Not Met\n\n' +
                        'You have ' + check.creditsAchieved + '/5 required credit passes.\n\n' +
                        missing.join('\n') + '\n\n' +
                        'You need credit passes (A1–C6) in:\n' +
                        '• English Language\n• Mathematics\n• Biology\n• Chemistry\n• Physics\n\n' +
                        'Please correct your O\'Level grades or add a second sitting before proceeding.'
                    );
                    return;
                }

                document.getElementById('form_action').value = 'next';
            });

            form.addEventListener('submit', function (e) {
                e.preventDefault();

                if (!form.checkValidity()) {
                    form.classList.add('was-validated');
                    return;
                }

                form.classList.add('was-validated');

                var action = document.getElementById('form_action').value;

                // Final O'Level gate on 'next'
                if (action === 'next') {
                    var check = computeCreditCheck();
                    if (!check.meetsRequirement) {
                        alert(
                            '⚠ O\'Level Requirement Not Met\n\n' +
                            'You have ' + check.creditsAchieved + '/5 required credit passes.\n\n' +
                            'Please correct your grades before proceeding to payment.'
                        );
                        return;
                    }
                }

                loadingOverlay.classList.add('show');

                var formData = new FormData(form);

                fetch('/apply/save-application', {
                    method: 'POST',
                    body: formData,
                    headers: { 'X-CSRF-TOKEN': csrfToken }
                })
                .then(function (response) {
                    var ct = response.headers.get('content-type');
                    if (!ct || ct.indexOf('application/json') === -1) {
                        throw new Error('Server returned non-JSON response');
                    }
                    return response.json();
                })
                .then(function (data) {
                    loadingOverlay.classList.remove('show');

                    if (data.success) {
                        // Check if server blocked payment due to O'Level
                        if (data.olevel_blocked) {
                            var blockMsg = '⚠ Cannot proceed to payment.\n\n' +
                                'O\'Level requirement not met: ' + data.olevel_message + '\n\n' +
                                'Credits achieved: ' + data.credits_achieved + '/5\n\n';
                            if (data.missing_subjects && data.missing_subjects.length > 0) {
                                blockMsg += 'Missing grades: ' + data.missing_subjects.join(', ') + '\n';
                            }
                            if (data.failed_subjects && data.failed_subjects.length > 0) {
                                blockMsg += 'Below credit: ' + data.failed_subjects.join(', ') + '\n';
                            }
                            blockMsg += '\nPlease fix your O\'Level results and try again.';
                            alert(blockMsg);
                            return;
                        }

                        if (data.redirect) {
                            window.location.href = data.redirect;
                        } else {
                            alert(data.message || 'Saved successfully.');
                        }
                    } else {
                        alert('Error: ' + (data.message || 'An error occurred.'));
                    }
                })
                .catch(function (error) {
                    loadingOverlay.classList.remove('show');
                    console.error('Form submission error:', error);
                    alert('A server error occurred. Please try again.');
                });
            });

        }());
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