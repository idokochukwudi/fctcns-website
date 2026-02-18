<?php
/**
 * Step 2: Application Form View
 * ENHANCED: Professional design matching main form, fixed JAMB data loading, welcome message
 * 
 * @package FCTCNS
 */

extract($data ?? []);

if (!function_exists('e')) {
    function e($text) {
        return htmlspecialchars($text ?? '', ENT_QUOTES, 'UTF-8');
    }
}

$baseUrl = $baseUrl ?? '/';
$application = $application ?? [];
$applicant = $applicant ?? [];
$jamb_data = $jamb_data ?? null;
$states = $states ?? [];
$programs = $programs ?? [];
$csrf_token = $csrf_token ?? '';

// O'Level results from controller (structured format)
$olevel_results = $olevel_results ?? [];

// Passport from controller
$passport = $passport ?? [];

// Get applicant name for welcome message
$applicant_name = trim(($applicant['first_name'] ?? '') . ' ' . ($applicant['last_name'] ?? ''));
if (empty($applicant_name) && !empty($application)) {
    $applicant_name = trim(($application['first_name'] ?? '') . ' ' . ($application['last_name'] ?? ''));
}
if (empty($applicant_name)) {
    $applicant_name = 'Applicant';
}

// Flash messages
$flash_success = $flash_success ?? $_SESSION['flash_success'] ?? null;
$flash_error = $flash_error ?? $_SESSION['flash_error'] ?? null;
$temp_password = $temp_password ?? $_SESSION['temp_password'] ?? null;
$errors = $errors ?? [];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0, viewport-fit=cover">
    <meta name="description" content="Application Form - FCT College of Nursing Sciences">
    <title><?php echo e($pageTitle ?? 'Application Form - Step 2'); ?> - FCT College of Nursing Sciences</title>
    
    <!-- Premium Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@400;500;600;700&family=Outfit:wght@300;400;500;600;700&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">
    
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
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
       DESIGN TOKENS
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
        
        /* Spacing - Clean and Consistent */
        --gutter: clamp(1rem, 4vw, 4rem);
        --container-max: 1800px;
        
        --space-xs: 0.5rem;
        --space-sm: 1rem;
        --space-md: 1.5rem;
        --space-lg: 2rem;
        --space-xl: 3rem;
        --space-xxl: 5rem;
    }

    /* ==========================================================================
       CONTAINER & LAYOUT
       ========================================================================== */
    .container {
        width: 100%;
        max-width: var(--container-max);
        margin: 0 auto;
        padding: var(--space-lg) var(--gutter);
    }

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

    .logout-btn i {
        font-size: 0.9rem;
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

    .section-header {
        margin-bottom: var(--space-xl);
    }

    .section-header h2 {
        font-size: clamp(2rem, 4vw, 2.8rem);
        margin-bottom: var(--space-sm);
        position: relative;
        display: inline-block;
    }

    .section-header h2::after {
        content: '';
        position: absolute;
        bottom: -10px;
        left: 0;
        width: 80px;
        height: 3px;
        background: var(--gold-gradient);
        border-radius: 3px;
    }

    .section-header p {
        font-size: clamp(1rem, 1.5vw, 1.2rem);
        color: var(--slate);
        max-width: 700px;
        margin-top: var(--space-md);
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

    .btn--secondary {
        background: var(--gold-gradient);
        color: white;
        background-size: 200% auto;
        box-shadow: var(--shadow-gold);
    }

    .btn--secondary:hover {
        background-position: right center;
        transform: translateY(-2px);
        box-shadow: 0 15px 35px rgba(201,164,74,0.3);
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

    .btn--success {
        background: var(--success);
        color: white;
    }

    .btn--success:hover {
        background: #0ca678;
        transform: translateY(-2px);
        box-shadow: 0 10px 25px rgba(16,185,129,0.3);
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
       STEPPER PROGRESS
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
       FORMS
       ========================================================================== */
    .form-label {
        display: block;
        font-weight: 600;
        font-size: 0.9rem;
        color: var(--ink-mid);
        margin-bottom: 0.5rem;
    }

    .form-label .required {
        color: var(--danger);
        margin-left: 0.25rem;
    }

    .form-control, .form-select {
        width: 100%;
        padding: 0.75rem 1rem;
        font-family: var(--font-body);
        font-size: 0.95rem;
        color: var(--ink);
        background: var(--white);
        border: 1.5px solid var(--border);
        border-radius: var(--radius-sm);
        transition: all 0.3s ease;
    }

    .form-control:focus, .form-select:focus {
        outline: none;
        border-color: var(--purple);
        box-shadow: 0 0 0 3px rgba(108,48,130,0.1);
    }

    .form-control[readonly] {
        background: var(--surface);
        cursor: not-allowed;
        border-color: var(--border);
        color: var(--slate);
    }

    .form-control.is-invalid, .form-select.is-invalid {
        border-color: var(--danger);
    }

    .invalid-feedback {
        display: none;
        font-size: 0.8rem;
        color: var(--danger);
        margin-top: 0.25rem;
    }

    .was-validated .form-control:invalid ~ .invalid-feedback,
    .was-validated .form-select:invalid ~ .invalid-feedback {
        display: block;
    }

    .form-text {
        font-size: 0.8rem;
        color: var(--mist);
        margin-top: 0.25rem;
    }

    /* ==========================================================================
       ALERTS
       ========================================================================== */
    .alert {
        padding: var(--space-md);
        border-radius: var(--radius-md);
        margin-bottom: var(--space-lg);
        border-left: 4px solid transparent;
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
       JAMB SUMMARY CARD
       ========================================================================== */
    .jamb-summary {
        background: var(--purple-pale);
        border: 1px solid var(--purple-light);
        border-radius: var(--radius-md);
        padding: var(--space-lg);
        margin-bottom: var(--space-xl);
        position: relative;
        overflow: hidden;
    }

    .jamb-summary::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 4px;
        height: 100%;
        background: var(--gold-gradient);
    }

    .jamb-summary-content {
        display: flex;
        align-items: center;
        gap: var(--space-md);
        flex-wrap: wrap;
    }

    .jamb-icon {
        width: 60px;
        height: 60px;
        background: var(--purple-gradient);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 1.5rem;
        flex-shrink: 0;
    }

    .jamb-details {
        flex: 1;
    }

    .jamb-details h5 {
        font-size: 1.1rem;
        margin-bottom: 0.25rem;
        color: var(--purple-deep);
    }

    .jamb-details p {
        color: var(--slate);
        font-size: 0.9rem;
    }

    .jamb-badge {
        background: var(--success);
        color: white;
        padding: 0.25rem 1rem;
        border-radius: var(--radius-full);
        font-size: 0.8rem;
        font-weight: 600;
    }

    @media (max-width: 768px) {
        .jamb-summary-content {
            flex-direction: column;
            text-align: center;
        }
        
        .jamb-details {
            text-align: center;
        }
    }

    /* ==========================================================================
       FORM SECTIONS (for O'Level results)
       ========================================================================== */
    .form-section {
        background: var(--surface);
        border-radius: var(--radius-lg);
        padding: var(--space-lg);
        margin-bottom: var(--space-xl);
        border: 1px solid var(--border);
    }

    .form-section h3 {
        font-size: 1.25rem;
        color: var(--purple-deep);
        margin-bottom: var(--space-md);
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .form-section h3 i {
        color: var(--gold);
    }

    .olevel-result-item {
        background: var(--white);
        border: 1px solid var(--border);
        border-radius: var(--radius-md);
        padding: var(--space-md);
        margin-bottom: var(--space-md);
    }

    /* ==========================================================================
       DOCUMENT PREVIEW
       ========================================================================== */
    .document-preview {
        position: relative;
        display: inline-block;
        margin: 10px;
    }
    
    .document-preview img {
        max-width: 150px;
        max-height: 150px;
        border: 2px solid var(--border);
        border-radius: var(--radius-md);
        transition: all 0.3s;
    }

    .document-preview:hover img {
        border-color: var(--purple);
    }

    /* ==========================================================================
       GRID SYSTEM
       ========================================================================== */
    .row {
        display: flex;
        flex-wrap: wrap;
        margin: calc(-1 * var(--space-md));
    }

    .col {
        flex: 1 0 0%;
        padding: var(--space-md);
    }

    .col-12 { width: 100%; padding: var(--space-md); }
    .col-6 { width: 50%; padding: var(--space-md); }
    .col-4 { width: 33.333%; padding: var(--space-md); }
    .col-3 { width: 25%; padding: var(--space-md); }

    @media (min-width: 768px) {
        .col-md-12 { width: 100%; }
        .col-md-8 { width: 66.667%; }
        .col-md-6 { width: 50%; }
        .col-md-4 { width: 33.333%; }
        .col-md-3 { width: 25%; }
    }

    @media (min-width: 992px) {
        .col-lg-12 { width: 100%; }
        .col-lg-8 { width: 66.667%; }
        .col-lg-6 { width: 50%; }
        .col-lg-4 { width: 33.333%; }
    }

    /* ==========================================================================
       UTILITIES
       ========================================================================== */
    .d-flex { display: flex; }
    .align-items-center { align-items: center; }
    .justify-content-between { justify-content: space-between; }
    .justify-content-center { justify-content: center; }
    .flex-wrap { flex-wrap: wrap; }
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
    .fw-normal { font-weight: 400; }

    .small { font-size: 0.875rem; }
    .text-center { text-align: center; }

    .bg-light { background: var(--surface); }
    .bg-white { background: var(--white); }

    .border { border: 1px solid var(--border); }
    .border-0 { border: none; }
    .rounded { border-radius: var(--radius-sm); }
    .rounded-3 { border-radius: var(--radius-md); }
    .rounded-4 { border-radius: var(--radius-lg); }
    .rounded-circle { border-radius: 50%; }

    .shadow-sm { box-shadow: var(--shadow-sm); }
    .shadow { box-shadow: var(--shadow-md); }
    .shadow-lg { box-shadow: var(--shadow-lg); }

    .position-relative { position: relative; }
    .position-absolute { position: absolute; }
    .top-0 { top: 0; }
    .end-0 { right: 0; }

    .w-100 { width: 100%; }
    .h-100 { height: 100%; }

    /* ==========================================================================
       RESPONSIVE UTILITIES
       ========================================================================== */
    @media (max-width: 768px) {
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
            padding: var(--space-lg);
        }
    }

    /* ==========================================================================
       ANIMATIONS
       ========================================================================== */
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .fade-in {
        animation: fadeIn 0.5s ease forwards;
    }

    /* ==========================================================================
       LOADING SPINNER
       ========================================================================== */
    .spinner-border {
        display: inline-block;
        width: 1rem;
        height: 1rem;
        border: 2px solid currentColor;
        border-right-color: transparent;
        border-radius: 50%;
        animation: spinner 0.75s linear infinite;
    }

    @keyframes spinner {
        to { transform: rotate(360deg); }
    }

    /* ==========================================================================
       FOCUS STATE
       ========================================================================== */
    :focus-visible {
        outline: 3px solid var(--gold);
        outline-offset: 2px;
    }

    .sr-only {
        position: absolute;
        width: 1px;
        height: 1px;
        padding: 0;
        margin: -1px;
        overflow: hidden;
        clip: rect(0,0,0,0);
        border: 0;
    }
    </style>
</head>
<body>
    <main id="main-content" class="main-content" role="main">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-12">
                    
                    <!-- TOP BAR WITH WELCOME AND LOGOUT BUTTON -->
                    <div class="top-bar fade-in">
                        <div class="welcome-message">
                            <div class="welcome-icon">
                                <i class="fas fa-user-graduate"></i>
                            </div>
                            <div class="welcome-text">
                                Welcome, <strong><?php echo e($applicant_name); ?></strong>
                                <?php if (!empty($application['application_number'])): ?>
                                    | <span class="text-muted">Application #: <?php echo e($application['application_number']); ?></span>
                                <?php endif; ?>
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
                            <div class="stepper-item active">
                                <div class="step-counter">2</div>
                                <div class="step-name">Application</div>
                            </div>
                            <div class="stepper-item">
                                <div class="step-counter">3</div>
                                <div class="step-name">Payment</div>
                            </div>
                            <div class="stepper-item">
                                <div class="step-counter">4</div>
                                <div class="step-name">Exam Slip</div>
                            </div>
                        </div>
                    </div>

                    <!-- Alert Container for Dynamic Messages -->
                    <div id="alertContainer" class="mb-4"></div>

                    <!-- Flash Messages -->
                    <?php if (!empty($flash_success)): ?>
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-check-circle fa-lg me-2"></i>
                                <span><?php echo e($flash_success); ?></span>
                                <button type="button" class="btn-close" onclick="this.parentElement.remove()" aria-label="Close"><i class="fas fa-times"></i></button>
                            </div>
                        </div>
                        <?php unset($_SESSION['flash_success']); ?>
                    <?php endif; ?>
                    
                    <?php if (!empty($flash_error)): ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-exclamation-circle fa-lg me-2"></i>
                                <span><?php echo e($flash_error); ?></span>
                                <button type="button" class="btn-close" onclick="this.parentElement.remove()" aria-label="Close"><i class="fas fa-times"></i></button>
                            </div>
                        </div>
                        <?php unset($_SESSION['flash_error']); ?>
                    <?php endif; ?>

                    <?php if (!empty($temp_password)): ?>
                        <div class="alert alert-warning alert-dismissible fade show" role="alert">
                            <div>
                                <h5 class="alert-heading"><i class="fas fa-key me-2"></i>Your Login Password</h5>
                                <p class="mb-2">Please save this password. You'll need it to log in later:</p>
                                <div class="bg-light p-3 text-center rounded" style="background: white; border: 2px dashed var(--gold);">
                                    <strong style="font-size: 1.5rem; font-family: monospace; color: var(--purple);"><?php echo e($temp_password); ?></strong>
                                </div>
                                <p class="mt-2 mb-0 small text-muted">
                                    <i class="fas fa-info-circle"></i> This password will also be sent to your email after you provide it.
                                </p>
                            </div>
                            <button type="button" class="btn-close" onclick="this.parentElement.remove()" aria-label="Close">×</button>
                        </div>
                        <?php unset($_SESSION['temp_password']); ?>
                    <?php endif; ?>

                    <?php if (!empty($errors)): ?>
                        <div class="alert alert-danger">
                            <h5 class="alert-heading"><i class="fas fa-exclamation-triangle me-2"></i>Please fix the following errors:</h5>
                            <ul class="mb-0">
                                <?php foreach ($errors as $error): ?>
                                    <li><?php echo e($error); ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    <?php endif; ?>

                    <!-- Application Form Card -->
                    <div class="card fade-in">
                        <!-- Card Header -->
                        <div class="card-header">
                            <div class="d-flex align-items-center">
                                <div class="rounded-circle bg-white bg-opacity-20 p-3 me-3">
                                    <i class="fas fa-file-alt fa-2x text-white"></i>
                                </div>
                                <div>
                                    <h2 class="h3 mb-1 fw-bold">Application Form</h2>
                                    <p class="mb-0 opacity-75">Step 2 of 4 - Complete your application details</p>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Card Body -->
                        <div class="card-body">
                            <!-- Main Form -->
                            <form method="POST" action="/apply/save-application" enctype="multipart/form-data" class="needs-validation" novalidate>
                                <input type="hidden" name="csrf_token" value="<?php echo e($csrf_token); ?>">
                                <input type="hidden" id="jamb_number" name="jamb_number" value="<?php echo e($application['jamb_number'] ?? ''); ?>">
                                <input type="hidden" id="utme_score" name="utme_score" value="<?php echo e($application['utme_score'] ?? ''); ?>">
                                <input type="hidden" name="action" id="form_action" value="save">
                                
                                <!-- JAMB Data Summary Card -->
                                <div class="jamb-summary" id="jambSummary">
                                    <div class="jamb-summary-content">
                                        <div class="jamb-icon">
                                            <i class="fas fa-check-circle"></i>
                                        </div>
                                        <div class="jamb-details">
                                            <h5>Loading JAMB data...</h5>
                                            <p>Please wait while we load your verified information</p>
                                        </div>
                                    </div>
                                </div>

                                <!-- Personal Information Section -->
                                <div class="section-title">
                                    <h5 class="fw-bold text-primary mb-0">
                                        <i class="fas fa-user-circle me-2"></i>Personal Information
                                    </h5>
                                    <p class="text-muted small">Your JAMB information (read-only)</p>
                                </div>
                                
                                <div class="row">
                                    <div class="col-12 col-md-4">
                                        <label class="form-label">First Name <span class="required">*</span></label>
                                        <input type="text" class="form-control" id="first_name" name="first_name" 
                                               value="<?php echo e($application['first_name'] ?? ''); ?>" readonly>
                                    </div>
                                    <div class="col-12 col-md-4">
                                        <label class="form-label">Last Name <span class="required">*</span></label>
                                        <input type="text" class="form-control" id="last_name" name="last_name" 
                                               value="<?php echo e($application['last_name'] ?? ''); ?>" readonly>
                                    </div>
                                    <div class="col-12 col-md-4">
                                        <label class="form-label">Other Names</label>
                                        <input type="text" class="form-control" id="other_names" name="other_names" 
                                               value="<?php echo e($application['other_names'] ?? ''); ?>" readonly>
                                    </div>
                                </div>

                                <div class="row mt-3">
                                    <div class="col-12 col-md-3">
                                        <label class="form-label">Gender <span class="required">*</span></label>
                                        <input type="text" class="form-control" id="gender" name="gender" 
                                               value="<?php echo isset($application['gender']) ? ($application['gender'] == 'M' ? 'Male' : ($application['gender'] == 'F' ? 'Female' : '')) : ''; ?>" readonly>
                                    </div>
                                    <div class="col-12 col-md-3">
                                        <label class="form-label">State of Origin <span class="required">*</span></label>
                                        <input type="text" class="form-control" id="state_of_origin" name="state_of_origin" 
                                               value="<?php echo e($application['state_of_origin'] ?? ''); ?>" readonly>
                                    </div>
                                    <div class="col-12 col-md-3">
                                        <label class="form-label">LGA <span class="required">*</span></label>
                                        <input type="text" class="form-control" id="lga" name="lga" 
                                               value="<?php echo e($application['lga'] ?? ''); ?>" readonly>
                                    </div>
                                    <div class="col-12 col-md-3">
                                        <label class="form-label">UTME Score <span class="required">*</span></label>
                                        <input type="text" class="form-control fw-bold text-success" id="utme_score_display" 
                                               value="<?php echo e($application['utme_score'] ?? ''); ?>" readonly>
                                    </div>
                                </div>

                                <!-- Editable Fields Section -->
                                <div class="section-title mt-5">
                                    <h5 class="fw-bold text-primary mb-0">
                                        <i class="fas fa-pen me-2"></i>Additional Information
                                    </h5>
                                    <p class="text-muted small">Please provide your contact details</p>
                                </div>
                                
                                <div class="row">
                                    <div class="col-12 col-md-6">
                                        <label class="form-label">
                                            Date of Birth <span class="required">*</span>
                                        </label>
                                        <input type="date" class="form-control" id="date_of_birth" name="date_of_birth" 
                                               value="<?php echo e($application['date_of_birth'] ?? ''); ?>" required>
                                        <div class="invalid-feedback">Please provide your date of birth</div>
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <label class="form-label">
                                            Phone Number <span class="required">*</span>
                                        </label>
                                        <input type="tel" class="form-control" id="phone" name="phone" 
                                               value="<?php echo e($application['phone'] ?? ($applicant['phone'] ?? '')); ?>"
                                               placeholder="08012345678" pattern="[0-9]{11}" required
                                               maxlength="11" inputmode="numeric">
                                        <div class="invalid-feedback">Phone number must be 11 digits</div>
                                        <div class="form-text">Enter 11-digit Nigerian mobile number</div>
                                    </div>
                                </div>

                                <div class="mt-3">
                                    <label class="form-label">
                                        Contact Address <span class="required">*</span>
                                    </label>
                                    <textarea class="form-control" id="address" name="address" rows="3" 
                                              placeholder="Enter your residential address" required><?php echo e($application['address'] ?? ''); ?></textarea>
                                    <div class="invalid-feedback">Please provide your address</div>
                                </div>

                                <div class="row mt-3">
                                    <div class="col-12 col-md-6">
                                        <label class="form-label">
                                            Email Address <span class="required">*</span>
                                        </label>
                                        <input type="email" class="form-control" id="email" name="email" 
                                               value="<?php echo e($application['email'] ?? ($applicant['email'] ?? '')); ?>" required>
                                        <div class="invalid-feedback">Please provide a valid email address</div>
                                        <div class="form-text">Your login credentials will be sent to this email</div>
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <label class="form-label">
                                            Nationality
                                        </label>
                                        <input type="text" class="form-control" id="nationality" name="nationality" 
                                               value="<?php echo e($application['nationality'] ?? 'Nigerian'); ?>">
                                    </div>
                                </div>

                                <!-- Program Selection Section -->
                                <div class="section-title mt-5">
                                    <h5 class="fw-bold text-primary mb-0">
                                        <i class="fas fa-graduation-cap me-2"></i>Program Selection
                                    </h5>
                                    <p class="text-muted small">Choose your preferred program</p>
                                </div>
                                
                                <div class="row">
                                    <div class="col-12 col-md-8 col-lg-6">
                                        <label class="form-label">
                                            Program Choice <span class="required">*</span>
                                        </label>
                                        <select class="form-select" id="program_choice" name="program_choice" required>
                                            <option value="" <?php echo empty($application['program_choice_1']) ? 'selected' : ''; ?> disabled>Select a program</option>
                                            <option value="ND Nursing" <?php echo ($application['program_choice_1'] ?? '') === 'ND Nursing' ? 'selected' : ''; ?>>ND Nursing</option>
                                            <option value="Post Basic Nursing" <?php echo ($application['program_choice_1'] ?? '') === 'Post Basic Nursing' ? 'selected' : ''; ?>>Post Basic Nursing</option>
                                            <option value="Midwifery" <?php echo ($application['program_choice_1'] ?? '') === 'Midwifery' ? 'selected' : ''; ?>>Midwifery</option>
                                            <option value="Public Health Nursing" <?php echo ($application['program_choice_1'] ?? '') === 'Public Health Nursing' ? 'selected' : ''; ?>>Public Health Nursing</option>
                                        </select>
                                        <div class="invalid-feedback">Please select your program</div>
                                    </div>
                                </div>

                                <!-- Hidden fields for other choices -->
                                <input type="hidden" name="program_choice_2" value="">
                                <input type="hidden" name="program_choice_3" value="">

                                <!-- O'Level Results Section -->
                                <?php if (!empty($olevel_results)): ?>
                                <div class="section-title mt-5">
                                    <h5 class="fw-bold text-primary mb-0">
                                        <i class="fas fa-certificate me-2"></i>O'Level Results
                                    </h5>
                                    <p class="text-muted small">Your saved O'Level results</p>
                                </div>
                                
                                <div class="form-section">
                                    <div id="olevel-results-container">
                                        <?php foreach ($olevel_results as $index => $result): ?>
                                        <div class="olevel-result-item">
                                            <div class="row">
                                                <div class="col-md-3 mb-2">
                                                    <label class="form-label">Exam Type</label>
                                                    <input type="text" class="form-control" value="<?php echo e($result['exam_type'] ?? ''); ?>" readonly>
                                                    <input type="hidden" name="olevel[<?php echo $index; ?>][exam_type]" value="<?php echo e($result['exam_type'] ?? ''); ?>">
                                                </div>
                                                <div class="col-md-2 mb-2">
                                                    <label class="form-label">Year</label>
                                                    <input type="text" class="form-control" value="<?php echo e($result['exam_year'] ?? ''); ?>" readonly>
                                                    <input type="hidden" name="olevel[<?php echo $index; ?>][exam_year]" value="<?php echo e($result['exam_year'] ?? ''); ?>">
                                                </div>
                                                <div class="col-md-3 mb-2">
                                                    <label class="form-label">Exam Number</label>
                                                    <input type="text" class="form-control" value="<?php echo e($result['exam_number'] ?? ''); ?>" readonly>
                                                    <input type="hidden" name="olevel[<?php echo $index; ?>][exam_number]" value="<?php echo e($result['exam_number'] ?? ''); ?>">
                                                </div>
                                                <div class="col-md-2 mb-2">
                                                    <label class="form-label">Sitting</label>
                                                    <input type="text" class="form-control" value="<?php echo e($result['sitting'] ?? ''); ?>" readonly>
                                                    <input type="hidden" name="olevel[<?php echo $index; ?>][sitting]" value="<?php echo e($result['sitting'] ?? ''); ?>">
                                                </div>
                                            </div>
                                            
                                            <div class="row mt-2">
                                                <div class="col-md-2 mb-2">
                                                    <label class="form-label">English</label>
                                                    <input type="text" class="form-control" value="<?php echo e($result['english_grade'] ?? ''); ?>" readonly>
                                                    <input type="hidden" name="olevel[<?php echo $index; ?>][english_grade]" value="<?php echo e($result['english_grade'] ?? ''); ?>">
                                                </div>
                                                <div class="col-md-2 mb-2">
                                                    <label class="form-label">Mathematics</label>
                                                    <input type="text" class="form-control" value="<?php echo e($result['mathematics_grade'] ?? ''); ?>" readonly>
                                                    <input type="hidden" name="olevel[<?php echo $index; ?>][mathematics_grade]" value="<?php echo e($result['mathematics_grade'] ?? ''); ?>">
                                                </div>
                                                <div class="col-md-2 mb-2">
                                                    <label class="form-label">Biology</label>
                                                    <input type="text" class="form-control" value="<?php echo e($result['biology_grade'] ?? ''); ?>" readonly>
                                                    <input type="hidden" name="olevel[<?php echo $index; ?>][biology_grade]" value="<?php echo e($result['biology_grade'] ?? ''); ?>">
                                                </div>
                                                <div class="col-md-2 mb-2">
                                                    <label class="form-label">Chemistry</label>
                                                    <input type="text" class="form-control" value="<?php echo e($result['chemistry_grade'] ?? ''); ?>" readonly>
                                                    <input type="hidden" name="olevel[<?php echo $index; ?>][chemistry_grade]" value="<?php echo e($result['chemistry_grade'] ?? ''); ?>">
                                                </div>
                                                <div class="col-md-2 mb-2">
                                                    <label class="form-label">Physics</label>
                                                    <input type="text" class="form-control" value="<?php echo e($result['physics_grade'] ?? ''); ?>" readonly>
                                                    <input type="hidden" name="olevel[<?php echo $index; ?>][physics_grade]" value="<?php echo e($result['physics_grade'] ?? ''); ?>">
                                                </div>
                                            </div>
                                        </div>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                                <?php endif; ?>

                                <!-- Passport Upload Section -->
                                <div class="section-title mt-5">
                                    <h5 class="fw-bold text-primary mb-0">
                                        <i class="fas fa-camera me-2"></i>Passport Photograph
                                    </h5>
                                    <p class="text-muted small">Upload a recent passport photograph (max 1MB, JPG or PNG)</p>
                                </div>
                                
                                <div class="row align-items-center">
                                    <div class="col-md-4 text-center">
                                        <?php if (!empty($passport)): ?>
                                            <div class="document-preview">
                                                <img src="<?php echo e($passport['file_path']); ?>" alt="Passport" id="passport-preview">
                                            </div>
                                        <?php else: ?>
                                            <div class="document-preview">
                                                <img src="/assets/images/default-avatar.png" alt="Passport Preview" id="passport-preview" style="display: none;">
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                    
                                    <div class="col-md-8">
                                        <input type="hidden" name="passport_confirmed" id="passport-confirmed" value="0">
                                        <div class="mb-3">
                                            <label for="passport" class="form-label">Select Passport Photo</label>
                                            <input type="file" class="form-control" id="passport" name="passport" 
                                                   accept="image/jpeg,image/png" onchange="confirmPassportUpload(this)">
                                            <small class="text-muted">Allowed: JPG, PNG. Max size: 1MB</small>
                                        </div>
                                    </div>
                                </div>

                                <!-- Navigation Buttons -->
                                <div class="d-flex justify-content-between mt-5">
                                    <a href="/apply/step/1" class="btn btn--outline btn--lg" onclick="return confirm('Are you sure you want to go back to JAMB verification? Your form data will be lost if you haven\'t saved.')">
                                        <i class="fas fa-arrow-left me-2"></i>Back to JAMB Info
                                    </a>
                                    <div class="d-flex gap-2">
                                        <button type="submit" class="btn btn--primary" onclick="document.getElementById('form_action').value='save'">
                                            <i class="fas fa-save me-2"></i>Save Progress
                                        </button>
                                        <button type="submit" class="btn btn--success" onclick="document.getElementById('form_action').value='next'">
                                            Save & Continue <i class="fas fa-arrow-right ms-2"></i>
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                        
                        <!-- Card Footer -->
                        <div class="card-footer">
                            <div class="d-flex align-items-center justify-content-center">
                                <i class="fas fa-lock text-muted me-2"></i>
                                <small class="text-muted">Your information is encrypted and securely stored</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- JavaScript -->
    <script>
    // Load JAMB data on page load
    document.addEventListener('DOMContentLoaded', function() {
        // Get JAMB data from PHP
        <?php if (!empty($application['jamb_number'])): ?>
        // Use application data
        const appData = {
            first_name: '<?php echo $application['first_name'] ?? ''; ?>',
            last_name: '<?php echo $application['last_name'] ?? ''; ?>',
            other_names: '<?php echo $application['other_names'] ?? ''; ?>',
            gender: '<?php echo $application['gender'] ?? ''; ?>',
            state_of_origin: '<?php echo $application['state_of_origin'] ?? ''; ?>',
            lga: '<?php echo $application['lga'] ?? ''; ?>',
            score: '<?php echo $application['utme_score'] ?? ''; ?>',
            jamb_number: '<?php echo $application['jamb_number'] ?? ''; ?>'
        };
        
        // Convert gender code to full text if needed
        let genderText = '';
        if (appData.gender === 'M') genderText = 'Male';
        else if (appData.gender === 'F') genderText = 'Female';
        else genderText = appData.gender || '';
        document.getElementById('gender').value = genderText;
        
        // Update summary
        document.getElementById('jambSummary').innerHTML = `
            <div class="jamb-summary-content">
                <div class="jamb-icon">
                    <i class="fas fa-check-circle"></i>
                </div>
                <div class="jamb-details">
                    <h5>JAMB Verified Successfully</h5>
                    <p><strong>${appData.first_name} ${appData.last_name}</strong> | JAMB: ${appData.jamb_number} | Score: <span class="jamb-badge">${appData.score}</span></p>
                </div>
            </div>
        `;
        <?php endif; ?>
    });

    // Confirm passport upload
    function confirmPassportUpload(input) {
        if (input.files && input.files[0]) {
            const file = input.files[0];
            
            // Validate file size
            if (file.size > 1 * 1024 * 1024) {
                alert('Passport image must be less than 1MB');
                input.value = '';
                return;
            }
            
            // Validate file type
            if (!file.type.match('image.*')) {
                alert('Please upload an image file (JPG, PNG)');
                input.value = '';
                return;
            }
            
            var reader = new FileReader();
            
            reader.onload = function(e) {
                if (confirm('Is this your correct passport photograph? Click OK to upload.')) {
                    document.getElementById('passport-preview').src = e.target.result;
                    document.getElementById('passport-preview').style.display = 'block';
                    document.getElementById('passport-confirmed').value = '1';
                } else {
                    input.value = '';
                    document.getElementById('passport-preview').style.display = 'none';
                    document.getElementById('passport-confirmed').value = '0';
                }
            }
            
            reader.readAsDataURL(input.files[0]);
        }
    }

    // Form validation
    (function() {
        'use strict';
        
        var forms = document.querySelectorAll('.needs-validation');
        
        Array.prototype.slice.call(forms).forEach(function(form) {
            form.addEventListener('submit', function(event) {
                if (!form.checkValidity()) {
                    event.preventDefault();
                    event.stopPropagation();
                }
                
                form.classList.add('was-validated');
            }, false);
        });
    })();

    // Auto-dismiss alerts after 5 seconds
    setTimeout(function() {
        document.querySelectorAll('.alert').forEach(function(alert) {
            alert.style.transition = 'opacity 0.5s ease';
            alert.style.opacity = '0';
            setTimeout(function() {
                if (alert.parentNode) {
                    alert.remove();
                }
            }, 500);
        });
    }, 5000);
    </script>
</body>
</html>