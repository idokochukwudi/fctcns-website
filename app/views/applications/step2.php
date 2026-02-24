<?php
/**
 * Step 2: Application Form View
 * FIXED: Correct step tracking - this is Step 3 (Application Form)
 * UPDATED: Purple color scheme matching JAMB verification page
 * FIXED 3a: Added O'Level credit status banner
 * FIXED 3b: Added subject-level grade feedback panel
 * FIXED 3c: Replaced JavaScript with credit check functionality
 * FIXED: Button styling - clean colors with smooth hover effects
 * FIXED: Passport preview - removed inline onerror handlers
 * FIXED: O'Level remove buttons - proper event handling without inline JS
 * FIXED: O'Level counter - now counts actual DOM elements instead of using variable
 * FIXED: O'Level add another - preserves existing grades when adding new sitting
 * FIXED: Professional loading pattern for Save & Continue button
 * FIXED: Added email verification error handling in JavaScript
 * FIXED: Removed all inline onclick handlers for CSP compliance
 * FIXED: O'Level indexing - ensures sequential indices (0,1,2...) when adding/removing
 * FIXED: Grade persistence - delegated event listener replaces clone-based approach
 *        so grades in sitting 1 are NOT wiped when "Add Another Sitting" is clicked
 * FIXED: O'Level data filtering - ensures only current user's records are displayed
 * FIXED: Back button - now correctly navigates to JAMB verification page
 * FIXED: Passport photo display - proper sizing and fit in preview box
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

        $baseUrl         = $baseUrl         ?? '/';
        $application     = $application     ?? [];
        $applicant       = $applicant       ?? [];
        $jamb_data       = $jamb_data       ?? [];
        $olevel_results  = $olevel_results  ?? [];
        $passport        = $passport        ?? [];
        $states          = $states          ?? [];
        $programs        = $programs        ?? [];
        $temp_password   = $temp_password   ?? '';
        $errors          = $errors          ?? [];
        $currentStep     = 3; // This is step 3 (Application Form)

        // FIX 3a: Get credit summary from controller data
        $credit_summary       = $credit_summary       ?? null;
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
            1 => ['label' => 'Create Account',    'sub' => 'Register'],
            2 => ['label' => 'JAMB Verification', 'sub' => 'JAMB check'],
            3 => ['label' => 'Application Form',  'sub' => 'Fill form'],
            4 => ['label' => 'Payment',            'sub' => 'Remita RRR'],
            5 => ['label' => 'Exam Slip',          'sub' => 'Download'],
        ];

        $applicant_name = trim(($applicant['first_name'] ?? '') . ' ' . ($applicant['last_name'] ?? ''));
        if (empty($applicant_name) && !empty($application)) {
            $applicant_name = trim(($application['first_name'] ?? '') . ' ' . ($application['last_name'] ?? ''));
        }
        if (empty($applicant_name)) $applicant_name = 'Applicant';

        $flash_success = $flash_success ?? $_SESSION['flash_success'] ?? null;
        $flash_error   = $flash_error   ?? $_SESSION['flash_error']   ?? null;

        // Determine the correct back URL based on user state
        $backUrl = '/apply/step/2'; // Default
        if (isset($application) && !empty($application['id'])) {
            // If we have an application, go back to JAMB verification with context
            $backUrl = '/apply/verify-jamb?back=true';
        }
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
                --sv1-success-dark:  #0d9488;
                --sv1-danger:        #ef4444;
                --sv1-danger-light:  #fee2e2;
                --sv1-danger-dark:   #dc2626;
                --sv1-warning:       #f59e0b;
                --sv1-warning-light: #fef3c7;
                --sv1-warning-dark:  #d97706;
                --sv1-info:          #3b82f6;
                --sv1-info-light:    #dbeafe;
                --sv1-info-dark:     #2563eb;
                --sv1-border:        #E9EDF2;
                --sv1-text-dark:     #1A1F2E;
                --sv1-text-muted:    #6B7280;
                --sv1-white:         #FFFFFF;
                --sv1-gray-50:       #F9FAFB;
                --sv1-gray-100:      #F3F4F6;
                --sv1-gray-200:      #E5E7EB;
                --sv1-gray-300:      #D1D5DB;
                --sv1-gray-400:      #9CA3AF;
                --sv1-gray-500:      #6B7280;
                --sv1-gray-600:      #4B5563;
                --sv1-gray-700:      #374151;
                --sv1-gray-800:      #1F2937;
                --sv1-gray-900:      #111827;

                --radius-sm:   6px;
                --radius-md:   10px;
                --radius-lg:   16px;
                --radius-xl:   24px;

                --shadow-sm: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
                --shadow-md: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
                --shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
                --shadow-xl: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);

                --transition-base:   all 0.2s ease-in-out;
                --transition-smooth: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            }

            html, body {
                width: 100%;
                overflow-x: hidden;
                background: var(--sv1-primary-soft);
                font-family: 'DM Sans', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
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
                background: var(--sv1-white);
                border-radius: 50px;
                padding: 15px 20px;
                border: 1px solid var(--sv1-border);
                box-shadow: var(--shadow-md);
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
                background: var(--sv1-white);
                border: 2px solid var(--sv1-border);
                border-radius: 50%;
                display: flex;
                align-items: center;
                justify-content: center;
                margin: 0 auto 6px;
                font-weight: 600;
                font-size: 14px;
                color: var(--sv1-text-muted);
                transition: var(--transition-base);
            }

            .step.active .step-number {
                background: var(--sv1-primary);
                border-color: var(--sv1-primary);
                color: var(--sv1-white);
                box-shadow: 0 0 0 4px var(--sv1-primary-soft);
                transform: scale(1.05);
            }

            .step.completed .step-number {
                background: var(--sv1-success);
                border-color: var(--sv1-success);
                color: var(--sv1-white);
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
                display: inline-flex;
                align-items: center;
                gap: 7px;
                background: var(--sv1-white);
                border: 1.5px solid rgba(255,255,255,0.3);
                border-radius: 50px;
                padding: 7px 16px;
                font-size: 12px;
                font-weight: 600;
                color: var(--sv1-white);
                text-decoration: none;
                transition: var(--transition-base);
                white-space: nowrap;
                backdrop-filter: blur(5px);
            }

            .logout-btn:hover {
                background: var(--sv1-white);
                color: var(--sv1-primary-dark);
                border-color: var(--sv1-white);
                transform: translateY(-1px);
                box-shadow: var(--shadow-lg);
            }

            /* =========================================================
               FLASH ALERTS
            ========================================================= */
            .flash-alert {
                display: flex;
                align-items: flex-start;
                gap: 12px;
                padding: 13px 18px;
                border-radius: var(--radius-md);
                margin-bottom: 16px;
                font-size: 14px;
                border-left-width: 4px;
                border-left-style: solid;
                animation: slideIn 0.3s ease;
            }

            @keyframes slideIn {
                from { opacity: 0; transform: translateY(-10px); }
                to   { opacity: 1; transform: translateY(0); }
            }

            .flash-alert.success {
                background: var(--sv1-success-light);
                border-left-color: var(--sv1-success);
                color: #065f46;
            }
            .flash-alert.error {
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
            .flash-alert.error   i { color: var(--sv1-danger); }
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
                background: var(--sv1-white);
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
                box-shadow: var(--shadow-lg);
            }

            .jamb-banner-left { display: flex; align-items: center; gap: 14px; }

            .jamb-check {
                width: 44px; height: 44px; border-radius: 50%;
                background: var(--sv1-success);
                display: flex; align-items: center; justify-content: center;
                color: #fff; font-size: 18px;
                flex-shrink: 0;
                box-shadow: 0 2px 10px rgba(16, 185, 129, 0.3);
            }

            .jamb-info-title { font-size: 14px; font-weight: 700; color: #fff; margin-bottom: 3px; }
            .jamb-info-sub   { font-size: 13px; color: rgba(255,255,255,0.8); }
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
                background: var(--sv1-white);
                border: 1px solid var(--sv1-border);
                border-radius: var(--radius-xl);
                overflow: hidden;
                box-shadow: var(--shadow-xl);
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
                box-shadow: var(--shadow-sm);
            }

            .f-section-title {
                font-family: 'Playfair Display', serif;
                font-size: 17px;
                font-weight: 700;
                color: var(--sv1-text-dark);
                margin: 0;
            }
            .f-section-sub {
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
                background: var(--sv1-white);
                transition: var(--transition-base);
            }

            .form-control:hover,
            .form-select:hover {
                border-color: var(--sv1-primary-light);
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

            .f-row.cols-2 { grid-template-columns: repeat(2, 1fr); }
            .f-row.cols-3 { grid-template-columns: repeat(3, 1fr); }
            .f-row.cols-4 { grid-template-columns: repeat(4, 1fr); }
            .f-row.cols-5 { grid-template-columns: repeat(5, 1fr); }
            .f-row.cols-6 { grid-template-columns: repeat(6, 1fr); }

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
                transition: var(--transition-base);
            }

            .olevel-item:hover {
                border-color: var(--sv1-primary-light);
                box-shadow: var(--shadow-md);
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
                border: 1.5px solid var(--sv1-danger);
                border-radius: var(--radius-sm);
                color: var(--sv1-danger);
                font-size: 12px; font-weight: 600;
                padding: 5px 12px;
                cursor: pointer;
                display: inline-flex; align-items: center; gap: 5px;
                transition: var(--transition-base);
            }
            .btn-remove:hover {
                background: var(--sv1-danger);
                color: var(--sv1-white);
                border-color: var(--sv1-danger);
                transform: translateY(-1px);
                box-shadow: var(--shadow-md);
            }
            .btn-remove:active {
                transform: translateY(0);
            }

            .grades-divider {
                font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.8px;
                color: var(--sv1-text-muted);
                margin: 14px 0 12px;
                padding-bottom: 8px;
                border-bottom: 1px dashed var(--sv1-border);
            }

            /* =========================================================
               PASSPORT SECTION - FIXED FOR PROPER IMAGE DISPLAY
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
                transition: var(--transition-base);
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
                object-position: center;
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

            /* FIX: Ensure uploaded image displays properly */
            .passport-preview-box img[src]:not([src=""]) {
                display: block !important;
            }

            .passport-upload-area h6 {
                font-size: 14px;
                font-weight: 600;
                color: var(--sv1-primary-dark);
                margin-bottom: 6px;
            }
            .passport-upload-area p {
                font-size: 12px;
                color: var(--sv1-text-muted);
                margin-bottom: 14px;
            }

            /* =========================================================
               BUTTONS
            ========================================================= */
            .btn {
                font-family: 'DM Sans', sans-serif;
                font-size: 13px; font-weight: 600;
                border-radius: var(--radius-md);
                border: none;
                cursor: pointer;
                display: inline-flex;
                align-items: center;
                justify-content: center;
                gap: 7px;
                transition: var(--transition-smooth);
                text-decoration: none;
                padding: 10px 22px;
                position: relative;
                overflow: hidden;
            }

            .btn::after {
                content: '';
                position: absolute;
                top: 50%; left: 50%;
                width: 0; height: 0;
                border-radius: 50%;
                background: rgba(255, 255, 255, 0.2);
                transform: translate(-50%, -50%);
                transition: width 0.3s, height 0.3s;
            }

            .btn:hover::after {
                width: 300px;
                height: 300px;
            }

            .btn:active { transform: scale(0.98); }

            /* Primary */
            .btn-primary {
                background: linear-gradient(135deg, var(--sv1-primary), var(--sv1-primary-dark));
                color: var(--sv1-white);
                box-shadow: 0 4px 12px rgba(107,78,155,0.3);
            }
            .btn-primary:hover {
                background: linear-gradient(135deg, var(--sv1-primary-dark), var(--sv1-primary));
                color: var(--sv1-white);
                transform: translateY(-2px);
                box-shadow: 0 8px 20px rgba(107,78,155,0.4);
            }
            .btn-primary:disabled {
                opacity: 0.6; cursor: not-allowed; transform: none; box-shadow: none;
            }

            /* Success */
            .btn-success {
                background: linear-gradient(135deg, var(--sv1-success), var(--sv1-success-dark));
                color: var(--sv1-white);
                box-shadow: 0 4px 12px rgba(16,185,129,0.3);
                position: relative;
                transition: all 0.3s ease;
            }
            .btn-success:hover:not(:disabled) {
                background: linear-gradient(135deg, var(--sv1-success-dark), var(--sv1-success));
                color: var(--sv1-white);
                transform: translateY(-2px);
                box-shadow: 0 8px 20px rgba(16,185,129,0.4);
            }
            .btn-success:disabled {
                opacity: 1; cursor: not-allowed; transform: none; box-shadow: none;
                background: linear-gradient(135deg, #94a3b8, #64748b);
                pointer-events: none;
            }

            .btn-success.loading {
                padding-left: 45px;
                position: relative;
                pointer-events: none;
            }

            .btn-success.loading::before {
                content: '';
                position: absolute;
                left: 16px; top: 50%;
                transform: translateY(-50%);
                width: 18px; height: 18px;
                border: 2px solid rgba(255,255,255,0.3);
                border-radius: 50%;
                border-top-color: #ffffff;
                animation: professional-spin 0.8s linear infinite;
            }

            @keyframes professional-spin {
                to { transform: translateY(-50%) rotate(360deg); }
            }

            .btn-success .btn-text { transition: all 0.3s ease; }
            .btn-success.loading .btn-text { opacity: 0.9; }
            .btn-success .btn-icon { transition: all 0.3s ease; }
            .btn-success.loading .btn-icon { opacity: 0; transform: translateX(10px); }

            /* Navy */
            .btn-navy {
                background: linear-gradient(135deg, var(--sv1-primary-dark), var(--sv1-primary));
                color: var(--sv1-white);
                box-shadow: 0 4px 12px rgba(74,59,107,0.3);
            }
            .btn-navy:hover {
                background: linear-gradient(135deg, var(--sv1-primary), var(--sv1-primary-dark));
                color: var(--sv1-white);
                transform: translateY(-2px);
                box-shadow: 0 8px 20px rgba(74,59,107,0.4);
            }
            .btn-navy:disabled { opacity: 0.6; cursor: not-allowed; transform: none; box-shadow: none; }

            /* Gold */
            .btn-gold {
                background: linear-gradient(135deg, var(--sv1-gold), var(--sv1-gold-light));
                color: var(--sv1-primary-dark);
                box-shadow: 0 4px 12px rgba(201,164,74,0.3);
            }
            .btn-gold:hover {
                background: linear-gradient(135deg, var(--sv1-gold-light), var(--sv1-gold));
                color: var(--sv1-primary-dark);
                transform: translateY(-2px);
                box-shadow: 0 8px 20px rgba(201,164,74,0.4);
            }
            .btn-gold:disabled { opacity: 0.6; cursor: not-allowed; transform: none; box-shadow: none; }

            /* Ghost */
            .btn-ghost {
                background: transparent;
                color: var(--sv1-text-muted);
                border: 2px solid var(--sv1-border);
            }
            .btn-ghost:hover {
                background: var(--sv1-primary-soft);
                border-color: var(--sv1-primary);
                color: var(--sv1-primary);
                transform: translateY(-1px);
                box-shadow: var(--shadow-md);
            }
            .btn-ghost:active { transform: translateY(0); }

            /* Outline Teal */
            .btn-outline-teal {
                background: transparent;
                color: var(--sv1-primary);
                border: 2px solid var(--sv1-primary);
            }
            .btn-outline-teal:hover {
                background: var(--sv1-primary);
                color: var(--sv1-white);
                transform: translateY(-2px);
                box-shadow: 0 4px 12px rgba(107,78,155,0.3);
            }
            .btn-outline-teal:active { transform: translateY(0); }

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
                transition: var(--transition-base);
            }
            .page-footer a:hover { color: var(--sv1-gold); }
            .page-footer i { color: var(--sv1-gold); font-size: 11px; margin-right: 4px; }

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
               UTILITY
            ========================================================= */
            .mb-0 { margin-bottom: 0 !important; }
            .mt-4 { margin-top: 16px; }
            .text-center { text-align: center; }
            </style>

            <!-- Bootstrap CSS -->
            <?php
            $bootstrapCssUrl = 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css';
            $bootstrapCssSri = SecurityHelper::getSriHash($bootstrapCssUrl);
            ?>
            <link href="<?php echo $bootstrapCssUrl; ?>"
                  rel="stylesheet"
                  <?php if ($bootstrapCssSri): ?>integrity="<?php echo $bootstrapCssSri; ?>"<?php endif; ?>
                  crossorigin="anonymous">

            <!-- Font Awesome -->
            <?php
            $faUrl = 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css';
            $faSri = SecurityHelper::getSriHash($faUrl);
            ?>
            <link rel="stylesheet"
                  href="<?php echo $faUrl; ?>"
                  <?php if ($faSri): ?>integrity="<?php echo $faSri; ?>"<?php endif; ?>
                  crossorigin="anonymous"
                  referrerpolicy="no-referrer">

            <!-- Google Fonts -->
            <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700&family=DM+Sans:wght@300;400;500;600&display=swap"
                  rel="stylesheet"
                  crossorigin="anonymous">
        </head>
        <body>
        <div class="page-shell">

            <!-- ===== STEP INDICATOR ===== -->
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
                            <strong><?php echo $this->e(
                                ($jamb_data['first_name'] ?? $application['first_name'] ?? '') . ' ' .
                                ($jamb_data['last_name']  ?? $application['last_name']  ?? '')
                            ); ?></strong>
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
                    <a href="/applicant/logout" class="logout-btn" id="logoutBtn">
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

                    <input type="hidden" name="csrf_token" value="<?php echo $this->e($csrf_token); ?>">
                    <input type="hidden" name="action"           id="form_action" value="save">
                    <input type="hidden" name="jamb_number"      value="<?php echo $this->e($jamb_data['jamb_number']     ?? $application['jamb_number']     ?? ''); ?>">
                    <input type="hidden" name="utme_score"       value="<?php echo $this->e($jamb_data['score']           ?? $application['utme_score']       ?? ''); ?>">
                    <input type="hidden" name="first_name"       value="<?php echo $this->e($jamb_data['first_name']      ?? $application['first_name']       ?? ''); ?>">
                    <input type="hidden" name="last_name"        value="<?php echo $this->e($jamb_data['last_name']       ?? $application['last_name']        ?? ''); ?>">
                    <input type="hidden" name="other_names"      value="<?php echo $this->e($jamb_data['other_names']     ?? $application['other_names']      ?? ''); ?>">
                    <input type="hidden" name="gender"           value="<?php echo $this->e($jamb_data['gender']          ?? $application['gender']           ?? ''); ?>">
                    <input type="hidden" name="state_of_origin"  value="<?php echo $this->e($jamb_data['state_of_origin'] ?? $application['state_of_origin']  ?? ''); ?>">
                    <input type="hidden" name="lga"              value="<?php echo $this->e($jamb_data['lga']             ?? $application['lga']              ?? ''); ?>">
                    <input type="hidden" name="program_choice_2" value="">
                    <input type="hidden" name="program_choice_3" value="">

                    <!-- ── SECTION 1: Personal Information ── -->
                    <div class="f-section">
                        <div class="f-section-head">
                            <div class="f-section-icon"><i class="fas fa-user"></i></div>
                            <div>
                                <div class="f-section-title">Personal Information</div>
                                <div class="f-section-sub">Fields from your JAMB record are read-only. Please verify they are correct.</div>
                            </div>
                        </div>

                        <!-- Row 1: Names -->
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

                        <!-- Row 2: Gender / State / LGA -->
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

                        <!-- Row 3: DOB / Nationality -->
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

                        <!-- Row 4: Email / Phone -->
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

                        <!-- Row 5: Address -->
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
                                    <option value="ND Nursing"         <?php echo ($application['program_choice_1'] ?? '') == 'ND Nursing'         ? 'selected' : ''; ?>>ND Nursing</option>
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
                            // FIX: Ensure we only show results for the CURRENT user
                            // Reset and validate the O'Level results array
                            if (empty($olevel_results) || !is_array($olevel_results)) {
                                $olevel_results = [];
                            }
                            
                            // Filter out any results that don't belong to the current application
                            // This prevents showing other users' records
                            $current_app_id = $application['id'] ?? 0;
                            $current_applicant_id = $applicant['id'] ?? 0;
                            
                            if ($current_app_id > 0 && !empty($olevel_results)) {
                                $filtered_results = [];
                                foreach ($olevel_results as $result) {
                                    // Only keep results that belong to this application or applicant
                                    if ((isset($result['application_id']) && $result['application_id'] == $current_app_id) || 
                                        (isset($result['applicant_id']) && $result['applicant_id'] == $current_applicant_id)) {
                                        $filtered_results[] = $result;
                                    }
                                }
                                $olevel_results = $filtered_results;
                            } elseif ($current_applicant_id > 0 && !empty($olevel_results)) {
                                // Fallback to applicant_id if application_id isn't set
                                $filtered_results = [];
                                foreach ($olevel_results as $result) {
                                    if (isset($result['applicant_id']) && $result['applicant_id'] == $current_applicant_id) {
                                        $filtered_results[] = $result;
                                    }
                                }
                                $olevel_results = $filtered_results;
                            }
                            
                            // Re-index the array after filtering
                            $olevel_results = array_values($olevel_results);
                            
                            // If no results, start with an empty array to show just one sitting
                            if (empty($olevel_results)) {
                                $olevelItems = [[]];
                            } else {
                                $olevelItems = $olevel_results;
                            }
                            
                            foreach ($olevelItems as $idx => $result):
                                // FIX: Ensure we're not accessing undefined array keys
                                $examType = isset($result['exam_type']) ? $result['exam_type'] : 'WAEC';
                                $examYear = isset($result['exam_year']) ? $result['exam_year'] : '';
                                $examNum  = isset($result['exam_number']) ? $result['exam_number'] : '';
                                $sitting  = isset($result['sitting']) ? $result['sitting'] : '1st';
                                $grades   = ['english','mathematics','biology','chemistry','physics'];
                                $allGrades = ['A1','B2','B3','C4','C5','C6','D7','E8','F9'];
                            ?>
                            <div class="olevel-item" data-index="<?php echo $idx; ?>">
                                <div class="olevel-item-head">
                                    <div class="olevel-item-label">
                                        <span class="idx-badge"><?php echo $this->e($idx + 1); ?></span>
                                        O'Level Result — Sitting <?php echo $this->e($idx + 1); ?>
                                    </div>
                                    <?php if ($idx > 0): ?>
                                    <button type="button" class="btn-remove" data-index="<?php echo $this->e($idx); ?>">
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

                                <!-- Grade dropdowns -->
                                <div class="grades-divider">Subject Grades</div>
                                <div class="f-row cols-5">
                                    <?php foreach ($grades as $subj): ?>
                                    <div>
                                        <label class="field-label"><?php echo $this->e(ucfirst($subj)); ?></label>
                                        <select class="form-select" name="olevel[<?php echo $this->e($idx); ?>][<?php echo $this->e($subj); ?>_grade]" required>
                                            <option value="">Grade</option>
                                            <?php foreach ($allGrades as $grade): ?>
                                            <option value="<?php echo $this->e($grade); ?>"
                                                <?php echo (isset($result[$subj.'_grade']) && $result[$subj.'_grade'] == $grade) ? 'selected' : ''; ?>>
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
                                <?php 
                                // FIX: Proper passport photo display
                                $passportUrl = '';
                                if (!empty($application['passport_photo'])) {
                                    // If it's a full URL, use it directly
                                    if (filter_var($application['passport_photo'], FILTER_VALIDATE_URL)) {
                                        $passportUrl = $application['passport_photo'];
                                    } 
                                    // If it's a relative path, prepend the base URL
                                    elseif (strpos($application['passport_photo'], '/') === 0) {
                                        $passportUrl = $application['passport_photo'];
                                    }
                                    // Otherwise, assume it's in the uploads directory
                                    else {
                                        $passportUrl = '/uploads/passports/' . ltrim($application['passport_photo'], '/');
                                    }
                                }
                                ?>
                                <?php if (!empty($passportUrl)): ?>
                                <img src="<?php echo $this->e($passportUrl); ?>" alt="Passport" id="passport-preview"
                                     style="display:block; width:100%; height:100%; object-fit:cover; object-position:center;">
                                <?php else: ?>
                                <img src="" alt="Passport Preview" id="passport-preview" style="display:none;">
                                <?php endif; ?>
                            </div>
                            <div class="passport-upload-area">
                                <h6>Select Passport Photo</h6>
                                <p>Ensure the photo clearly shows your face on a plain white background.</p>
                                <input type="hidden" name="passport_confirmed" id="passport-confirmed"
                                       value="<?php echo !empty($application['passport_photo']) ? '1' : '0'; ?>">
                                <input type="file" class="form-control" id="passport" name="passport"
                                       accept="image/jpeg,image/png"
                                       style="margin-bottom:8px;">
                                <div class="field-hint">Allowed formats: JPG, PNG &nbsp;|&nbsp; Maximum size: 500 KB</div>
                            </div>
                        </div>
                    </div>

                    <!-- ── ACTION BAR ── -->
                    <div class="action-bar">
                        <!-- FIX: Back button now uses dynamic URL -->
                        <a href="<?php echo $this->e($backUrl); ?>" class="btn btn-ghost" id="backBtn">
                            <i class="fas fa-arrow-left"></i> Back to JAMB Verification
                        </a>
                        <div class="action-bar-right">
                            <button type="submit" class="btn btn-primary" id="saveBtn">
                                <i class="fas fa-save"></i> Save Progress
                            </button>
                            <button type="submit" class="btn btn-success btn-lg" id="nextBtn">
                                <span class="btn-icon"><i class="fas fa-arrow-right"></i></span>
                                <span class="btn-text">Save &amp; Continue</span>
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

        <!-- Bootstrap JS -->
        <?php
        $bootstrapJsUrl = 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js';
        $bootstrapJsSri = SecurityHelper::getSriHash($bootstrapJsUrl);
        ?>
        <script src="<?php echo $bootstrapJsUrl; ?>"
                <?php if ($bootstrapJsSri): ?>integrity="<?php echo $bootstrapJsSri; ?>"<?php endif; ?>
                crossorigin="anonymous"
                nonce="<?php echo $csp_nonce; ?>"></script>

        <!-- ================================================================
             MAIN JAVASCRIPT
             KEY FIX: Grade persistence when adding a second sitting.

             OLD approach (broken):
               attachGradeListeners() cloned each <select> with cloneNode(true),
               then replaced the original in the DOM. Even though cloneNode copies
               the selected attribute, the browser resets the live value to the
               first option on replaceChild — wiping whatever the user picked.

             NEW approach (fixed):
               A SINGLE delegated 'change' listener on #olevel-results-container
               catches events from ALL grade selects, present and future, without
               ever touching (cloning/replacing) the existing elements.
               No cloning → no value loss.
        ================================================================ -->
        <script nonce="<?php echo $csp_nonce; ?>">
        (function () {
            'use strict';

            // ── CSRF token ────────────────────────────────────────────────
            var csrfToken = (document.querySelector('meta[name="csrf-token"]') || {}).getAttribute('content') || '';

            // ── Credit grade constants ────────────────────────────────────
            var CREDIT_GRADES   = ['A1','B2','B3','C4','C5','C6'];
            var GRADE_ORDER     = ['A1','B2','B3','C4','C5','C6','D7','E8','F9'];
            var REQUIRED_KEYS   = ['english','mathematics','biology','chemistry','physics'];
            var REQUIRED_LABELS = {
                english:     'English Language',
                mathematics: 'Mathematics',
                biology:     'Biology',
                chemistry:   'Chemistry',
                physics:     'Physics'
            };

            var MAX_SITTINGS = 2;

            var initialMeetsRequirement = <?php echo ($credit_summary && $credit_summary['meets_requirement']) ? 'true' : 'false'; ?>;

            // ─────────────────────────────────────────────────────────────
            // HELPER: show transient alert banner
            // ─────────────────────────────────────────────────────────────
            function showAlert(message, type) {
                var alertDiv = document.createElement('div');
                alertDiv.className = 'flash-alert ' + type;
                alertDiv.innerHTML =
                    '<i class="fas fa-' +
                    (type === 'success' ? 'check-circle' : (type === 'warning' ? 'exclamation-triangle' : 'exclamation-circle')) +
                    '"></i><span>' + message + '</span>';

                var pageShell = document.querySelector('.page-shell');
                if (pageShell) {
                    var stepIndicator = document.querySelector('.step-indicator');
                    if (stepIndicator) {
                        stepIndicator.insertAdjacentElement('afterend', alertDiv);
                    } else {
                        pageShell.insertBefore(alertDiv, pageShell.firstChild);
                    }
                    setTimeout(function () {
                        if (alertDiv.parentNode) alertDiv.parentNode.removeChild(alertDiv);
                    }, 5000);
                } else {
                    alert(message);
                }
            }

            // ─────────────────────────────────────────────────────────────
            // Navigation confirmation handlers
            // ─────────────────────────────────────────────────────────────
            document.getElementById('logoutBtn') && document.getElementById('logoutBtn').addEventListener('click', function (e) {
                if (!confirm('Are you sure you want to logout? Your progress will be saved.')) e.preventDefault();
            });

            // FIX: Back button confirmation
            document.getElementById('backBtn') && document.getElementById('backBtn').addEventListener('click', function (e) {
                if (!confirm('Go back to JAMB verification? Any unsaved changes will be lost.')) {
                    e.preventDefault();
                }
            });

            // ─────────────────────────────────────────────────────────────
            // Count / index helpers
            // ─────────────────────────────────────────────────────────────
            function countSittings() {
                return document.querySelectorAll('#olevel-results-container .olevel-item').length;
            }

            function getNextIndex() {
                return document.querySelectorAll('#olevel-results-container .olevel-item').length;
            }

            // ─────────────────────────────────────────────────────────────
            // Re-index all sitting blocks so names stay sequential (0,1,2…)
            // ─────────────────────────────────────────────────────────────
            function reindexSittings() {
                document.querySelectorAll('#olevel-results-container .olevel-item').forEach(function (item, newIndex) {
                    item.dataset.index = newIndex;

                    var badge = item.querySelector('.idx-badge');
                    if (badge) badge.textContent = newIndex + 1;

                    // Update sitting label text node
                    var labelDiv = item.querySelector('.olevel-item-label');
                    if (labelDiv) {
                        labelDiv.childNodes.forEach(function (node) {
                            if (node.nodeType === 3 && node.textContent.includes('Sitting')) {
                                node.textContent = " O'Level Result — Sitting " + (newIndex + 1) + " ";
                            }
                        });
                    }

                    // Rename all select/input fields
                    item.querySelectorAll('select[name^="olevel["], input[name^="olevel["]').forEach(function (el) {
                        el.setAttribute('name', el.getAttribute('name').replace(/olevel\[\d+\]/, 'olevel[' + newIndex + ']'));
                    });

                    // Update remove button index
                    var removeBtn = item.querySelector('.btn-remove');
                    if (removeBtn) removeBtn.dataset.index = newIndex;
                });
            }

            // ─────────────────────────────────────────────────────────────
            // Compute best grades across all sittings (for credit check)
            // ─────────────────────────────────────────────────────────────
            function computeCreditCheck() {
                var bestGrades = {};

                document.querySelectorAll('.olevel-item').forEach(function (item) {
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

            // ─────────────────────────────────────────────────────────────
            // Render the live credit check panel
            // ─────────────────────────────────────────────────────────────
            function renderCreditPanel(result) {
                var panel = document.getElementById('creditCheckPanel');
                var inner = document.getElementById('creditCheckInner');
                if (!panel || !inner) return;

                var hasAnyGrade = Object.keys(result.bestGrades).length > 0;
                panel.style.display = hasAnyGrade ? 'block' : 'none';
                if (!hasAnyGrade) return;

                var rows = REQUIRED_KEYS.map(function (key) {
                    var label    = REQUIRED_LABELS[key];
                    var grade    = result.bestGrades[key];
                    var isCredit = grade && CREDIT_GRADES.indexOf(grade) !== -1;
                    var color    = isCredit ? '#10b981' : '#ef4444';
                    var icon     = isCredit ? 'fa-check-circle' : 'fa-times-circle';
                    var gradeStr = grade ? ': <strong>' + grade + '</strong>' : ': —';
                    return '<span style="color:' + color + ';margin-right:12px;">'
                         + '<i class="fas ' + icon + '"></i> ' + label + gradeStr + '</span>';
                }).join('');

                var met          = result.meetsRequirement;
                var statusColor  = met ? '#065f46' : '#92400e';
                var statusBg     = met ? '#ecfdf5' : '#fff3e0';
                var statusBorder = met ? '#10b981' : '#f57c00';
                var statusIcon   = met ? 'fa-circle-check' : 'fa-triangle-exclamation';
                var statusMsg    = met
                    ? 'All 5 credits met! You may proceed to payment.'
                    : result.creditsAchieved + '/5 credits. ';

                if (!met) {
                    if (result.missingSubjects.length > 0) statusMsg += 'No grade: ' + result.missingSubjects.join(', ') + '. ';
                    if (result.failedSubjects.length  > 0) statusMsg += 'Below credit: ' + result.failedSubjects.join(', ') + '.';
                }

                inner.style.background = statusBg;
                inner.style.border     = '1px solid ' + statusBorder;
                inner.innerHTML =
                    '<div style="display:flex;flex-wrap:wrap;gap:4px 0;margin-bottom:8px;">' + rows + '</div>' +
                    '<div style="color:' + statusColor + ';font-weight:600;">' +
                        '<i class="fas ' + statusIcon + '" style="margin-right:6px;"></i>' + statusMsg +
                    '</div>';
            }

            // ─────────────────────────────────────────────────────────────
            // Update button state and warning banner
            // ─────────────────────────────────────────────────────────────
            function updateUIState(result) {
                var banner  = document.getElementById('olevelWarningBanner');
                var nextBtn = document.getElementById('nextBtn');

                if (nextBtn) {
                    if (result.meetsRequirement) {
                        nextBtn.disabled      = false;
                        nextBtn.style.opacity = '';
                        nextBtn.style.cursor  = '';
                        nextBtn.title         = '';
                    } else {
                        nextBtn.disabled      = true;
                        nextBtn.style.opacity = '0.45';
                        nextBtn.style.cursor  = 'not-allowed';
                        nextBtn.title         = "You must meet the O'Level credit requirement before proceeding.";
                    }
                }

                if (banner) {
                    banner.style.display = result.meetsRequirement ? 'none' : 'flex';
                }
            }

            // ─────────────────────────────────────────────────────────────
            // Run credit check and refresh UI
            // ─────────────────────────────────────────────────────────────
            function onGradeChange() {
                var result = computeCreditCheck();
                renderCreditPanel(result);
                updateUIState(result);
            }

            // ─────────────────────────────────────────────────────────────
            // DELEGATED grade-change listener — attached ONCE to the
            // container. Works for existing AND dynamically-added selects
            // WITHOUT cloning or replacing any element, so user-selected
            // values are never lost.
            // ─────────────────────────────────────────────────────────────
            var _gradeListenerAttached = false;

            function attachGradeListeners() {
                if (_gradeListenerAttached) return; // idempotent — only ever attach once

                var container = document.getElementById('olevel-results-container');
                if (!container) return;

                container.addEventListener('change', function (e) {
                    if (e.target && e.target.matches('select[name*="_grade"]')) {
                        onGradeChange();
                    }
                });

                _gradeListenerAttached = true;
            }

            // ─────────────────────────────────────────────────────────────
            // Wire a remove button (click → confirm → remove → reindex)
            // ─────────────────────────────────────────────────────────────
            function wireRemoveButton(btn) {
                btn.addEventListener('click', function (e) {
                    e.preventDefault();
                    if (confirm('Are you sure you want to remove this sitting?')) {
                        this.closest('.olevel-item').remove();
                        reindexSittings();
                        // Delegation still active — no need to re-attach anything
                        onGradeChange();
                    }
                });
            }

            // ─────────────────────────────────────────────────────────────
            // Add Another Sitting
            // ─────────────────────────────────────────────────────────────
            var addOlevelBtn = document.getElementById('add-olevel');
            if (addOlevelBtn) {
                addOlevelBtn.addEventListener('click', function () {
                    var current = countSittings();

                    if (current >= MAX_SITTINGS) {
                        alert('Maximum of ' + MAX_SITTINGS + ' sittings allowed.');
                        return;
                    }

                    var newIndex  = getNextIndex();
                    var sittingNo = current + 1;

                    var gradeLabels = ['English','Mathematics','Biology','Chemistry','Physics'];
                    var gradeKeys   = ['english','mathematics','biology','chemistry','physics'];
                    var gradeOpts   = ['A1','B2','B3','C4','C5','C6','D7','E8','F9']
                        .map(function (g) { return '<option value="' + g + '">' + g + '</option>'; }).join('');

                    var gradeFields = gradeKeys.map(function (key, i) {
                        return '<div>' +
                            '<label class="field-label">' + gradeLabels[i] + '</label>' +
                            '<select class="form-select" name="olevel[' + newIndex + '][' + key + '_grade]" required>' +
                                '<option value="">Grade</option>' + gradeOpts +
                            '</select>' +
                        '</div>';
                    }).join('');

                    // Unique ID so we can find the remove button immediately after insertion
                    var uid = 'removeOlevel_' + Date.now() + '_' + Math.random().toString(36).substr(2, 9);

                    var html =
                        '<div class="olevel-item" data-index="' + newIndex + '">' +
                            '<div class="olevel-item-head">' +
                                '<div class="olevel-item-label">' +
                                    '<span class="idx-badge">' + sittingNo + '</span>' +
                                    " O'Level Result — Sitting " + sittingNo +
                                '</div>' +
                                '<button type="button" class="btn-remove" data-index="' + newIndex + '" id="' + uid + '">' +
                                    '<i class="fas fa-trash-alt"></i> Remove' +
                                '</button>' +
                            '</div>' +
                            '<div class="f-row cols-4">' +
                                '<div><label class="field-label">Exam Type</label>' +
                                    '<select class="form-select" name="olevel[' + newIndex + '][exam_type]" required>' +
                                        '<option value="WAEC">WAEC</option>' +
                                        '<option value="NECO">NECO</option>' +
                                        '<option value="NABTEB">NABTEB</option>' +
                                    '</select></div>' +
                                '<div><label class="field-label">Exam Year</label>' +
                                    '<input type="text" class="form-control" name="olevel[' + newIndex + '][exam_year]" placeholder="e.g. 2023" required></div>' +
                                '<div><label class="field-label">Exam / Centre Number</label>' +
                                    '<input type="text" class="form-control" name="olevel[' + newIndex + '][exam_number]" placeholder="Optional"></div>' +
                                '<div><label class="field-label">Sitting</label>' +
                                    '<select class="form-select" name="olevel[' + newIndex + '][sitting]">' +
                                        '<option value="1st">1st Sitting</option>' +
                                        '<option value="2nd" selected>2nd Sitting</option>' +
                                    '</select></div>' +
                            '</div>' +
                            '<div class="grades-divider">Subject Grades</div>' +
                            '<div class="f-row cols-5">' + gradeFields + '</div>' +
                        '</div>';

                    document.getElementById('olevel-results-container').insertAdjacentHTML('beforeend', html);

                    // Wire the remove button on the new block
                    var removeBtn = document.getElementById(uid);
                    if (removeBtn) wireRemoveButton(removeBtn);

                    // Delegation already covers the new grade selects — no re-attach needed.
                    // Just update the credit panel to reflect the new (empty) sitting.
                    onGradeChange();

                    console.log('Added sitting ' + sittingNo + ' at index ' + newIndex);
                });
            }

            // ─────────────────────────────────────────────────────────────
            // Passport photo preview - FIXED for proper display
            // ─────────────────────────────────────────────────────────────
            var passportInput = document.getElementById('passport');
            if (passportInput) {
                passportInput.addEventListener('change', function () {
                    if (!this.files || !this.files[0]) return;

                    var file = this.files[0];

                    if (file.size > 500 * 1024) {
                        alert('File is too large. Maximum size is 500 KB.');
                        this.value = '';
                        return;
                    }

                    // Check file type
                    var validTypes = ['image/jpeg', 'image/jpg', 'image/png'];
                    if (validTypes.indexOf(file.type) === -1) {
                        alert('Invalid file type. Please upload JPG or PNG only.');
                        this.value = '';
                        return;
                    }

                    var reader = new FileReader();
                    reader.onload = function (ev) {
                        var img         = document.getElementById('passport-preview');
                        var box         = document.getElementById('passportBox');
                        var placeholder = document.getElementById('passportPlaceholder');

                        if (img) {
                            img.src = ev.target.result;
                            img.style.display = 'block';
                            img.style.objectFit = 'cover';
                            img.style.objectPosition = 'center';
                        }
                        if (placeholder) {
                            placeholder.style.display = 'none';
                        }
                        if (box) {
                            box.classList.add('has-image');
                        }

                        var confirmed = document.getElementById('passport-confirmed');
                        if (confirmed) confirmed.value = '1';
                    };
                    reader.readAsDataURL(file);
                });
            }

            // FIX: Ensure existing passport image displays properly on page load
            (function checkExistingPassport() {
                var img = document.getElementById('passport-preview');
                var box = document.getElementById('passportBox');
                var placeholder = document.getElementById('passportPlaceholder');
                
                if (img && img.src && img.src !== '') {
                    img.style.display = 'block';
                    img.style.objectFit = 'cover';
                    img.style.objectPosition = 'center';
                    if (placeholder) placeholder.style.display = 'none';
                    if (box) box.classList.add('has-image');
                }
            })();

            // ─────────────────────────────────────────────────────────────
            // Wire existing PHP-rendered remove buttons (sitting 2+)
            // ─────────────────────────────────────────────────────────────
            document.querySelectorAll('.olevel-item .btn-remove').forEach(function (btn) {
                wireRemoveButton(btn);
            });

            // ─────────────────────────────────────────────────────────────
            // AJAX form submission
            // ─────────────────────────────────────────────────────────────
            var form    = document.getElementById('mainForm');
            var saveBtn = document.getElementById('saveBtn');
            var nextBtn = document.getElementById('nextBtn');

            if (saveBtn) {
                saveBtn.addEventListener('click', function (e) {
                    e.preventDefault();
                    document.getElementById('form_action').value = 'save';

                    var originalHtml = this.innerHTML;
                    this.disabled    = true;
                    this.innerHTML   = '<i class="fas fa-spinner fa-spin"></i> Saving...';

                    submitForm(this, originalHtml);
                });
            }

            if (nextBtn) {
                nextBtn.addEventListener('click', function (e) {
                    e.preventDefault();

                    var check = computeCreditCheck();
                    if (!check.meetsRequirement) {
                        var lines = ["⚠ O'Level Requirement Not Met\n\nYou have " + check.creditsAchieved + '/5 required credit passes.\n'];
                        if (check.missingSubjects.length > 0) lines.push('No grade entered for: ' + check.missingSubjects.join(', '));
                        if (check.failedSubjects.length  > 0) lines.push('Below credit in: ' + check.failedSubjects.join(', '));
                        lines.push('\nCredit passes (A1–C6) required in:\n• English Language\n• Mathematics\n• Biology\n• Chemistry\n• Physics');
                        lines.push('\nPlease correct your grades or add a second sitting.');
                        alert(lines.join('\n'));
                        return;
                    }

                    document.getElementById('form_action').value = 'next';
                    this.classList.add('loading');
                    this.disabled = true;

                    submitForm(this);
                });
            }

            function submitForm(button, originalHtml) {
                if (!form.checkValidity()) {
                    form.classList.add('was-validated');
                    button.disabled = false;
                    button.classList.remove('loading');
                    if (originalHtml) button.innerHTML = originalHtml;
                    return;
                }
                form.classList.add('was-validated');

                fetch('/apply/save-application', {
                    method: 'POST',
                    body: new FormData(form),
                    headers: { 'X-CSRF-TOKEN': csrfToken }
                })
                .then(function (response) {
                    var ct = response.headers.get('content-type') || '';
                    if (ct.indexOf('application/json') === -1) throw new Error('non-JSON response');
                    return response.json();
                })
                .then(function (data) {
                    console.log('Response:', data);

                    button.disabled = false;
                    button.classList.remove('loading');
                    if (originalHtml) button.innerHTML = originalHtml;

                    if (data.success) {
                        if (data.olevel_blocked) {
                            var msg = '⚠ Cannot proceed to payment.\n\n'
                                    + "O'Level requirement not met: " + data.olevel_message + '\n'
                                    + 'Credits achieved: ' + data.credits_achieved + '/5\n';
                            if (data.missing_subjects && data.missing_subjects.length) msg += 'Missing grades: ' + data.missing_subjects.join(', ') + '\n';
                            if (data.failed_subjects  && data.failed_subjects.length)  msg += 'Below credit: '  + data.failed_subjects.join(', ')  + '\n';
                            alert(msg);
                            return;
                        }
                        if (data.redirect) {
                            window.location.href = data.redirect;
                        } else {
                            showAlert(data.message || 'Saved successfully.', 'success');
                        }
                    } else {
                        if (data.email_not_verified) {
                            showAlert(data.message || 'Please verify your email first.', 'warning');
                            setTimeout(function () {
                                window.location.href = data.redirect || '/apply/verify-email';
                            }, 2000);
                        } else {
                            showAlert(data.message || 'Error occurred. Please try again.', 'error');
                        }
                    }
                })
                .catch(function (error) {
                    console.error('Form error:', error);
                    button.disabled = false;
                    button.classList.remove('loading');
                    if (originalHtml) button.innerHTML = originalHtml;
                    showAlert('A server error occurred. Please try again.', 'error');
                });
            }

            // ─────────────────────────────────────────────────────────────
            // Bootstrap native validation
            // ─────────────────────────────────────────────────────────────
            document.querySelectorAll('.needs-validation').forEach(function (f) {
                f.addEventListener('submit', function (e) {
                    if (!f.checkValidity()) { e.preventDefault(); e.stopPropagation(); }
                    f.classList.add('was-validated');
                });
            });

            // ─────────────────────────────────────────────────────────────
            // INITIAL SETUP
            // ─────────────────────────────────────────────────────────────

            // Attach the single delegated grade listener (idempotent)
            attachGradeListeners();

            // Run initial credit check to set correct UI state on page load
            var initResult = computeCreditCheck();
            renderCreditPanel(initResult);

            if (!initialMeetsRequirement || !initResult.meetsRequirement) {
                updateUIState({ meetsRequirement: false });
            } else {
                updateUIState(initResult);
            }

            console.log('Init: sitting count =', countSittings());
            console.log('Init: credit check =', initResult);

        }());
        </script>
        </body>
        </html>
        <?php
    }
}

// =========================================================
// Instantiate and render
// =========================================================
$view = new ApplicationFormView();
$view->render(get_defined_vars());