<?php
/**
 * Application Form View - Step 2
 * FIXED: Displays existing uploaded files, view buttons, and birth certificate persistence
 * 
 * @package FCTCNS
 * @version 2.2 - Added view buttons and birth certificate persistence
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

// Existing files from controller
$existing_passport = $existing_passport ?? null;
$existing_olevel = $existing_olevel ?? [];
$existing_jamb_result = $existing_jamb_result ?? null;
$existing_birth_certificate = $existing_birth_certificate ?? null;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0, viewport-fit=cover">
    <meta name="description" content="Application Form - FCT College of Nursing Sciences">
    <title><?php echo e($pageTitle ?? 'Application Form'); ?> - FCT College of Nursing Sciences</title>
    
    <!-- Premium Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@400;500;600;700&family=Outfit:wght@300;400;500;600;700&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">
    
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
    /* ==========================================================================
       RESET & BASE STYLES - ABSOLUTELY NO GAPS
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
        --container-max: 1400px;
        
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

    .main-content {
        min-height: calc(100vh - 200px);
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
        max-width: 600px;
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
       UPLOAD AREA
       ========================================================================== */
    .upload-area {
        background: var(--surface);
        border: 2px dashed var(--border);
        border-radius: var(--radius-lg);
        padding: var(--space-xl) var(--space-lg);
        text-align: center;
        transition: all 0.3s ease;
        cursor: pointer;
        position: relative;
        overflow: hidden;
    }

    .upload-area:hover {
        border-color: var(--purple);
        background: var(--purple-pale);
    }

    .upload-area.border-success {
        border-color: var(--success);
        background: var(--success-light);
    }

    .upload-area i {
        font-size: 2.5rem;
        color: var(--purple);
        margin-bottom: var(--space-sm);
    }

    .upload-area h6 {
        font-size: 1rem;
        margin-bottom: 0.25rem;
    }

    .upload-area p {
        font-size: 0.85rem;
        color: var(--slate);
        margin-bottom: var(--space-md);
    }

    /* File Preview */
    .file-preview {
        margin-top: var(--space-md);
        text-align: left;
    }

    .file-preview-item {
        background: var(--white);
        border: 1px solid var(--border);
        border-radius: var(--radius-md);
        padding: var(--space-sm);
        margin-bottom: var(--space-sm);
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: var(--space-sm);
        flex-wrap: wrap;
    }

    .file-preview-item-info {
        display: flex;
        align-items: center;
        gap: var(--space-sm);
        flex: 1;
        min-width: 0;
    }

    .file-preview-item-icon {
        width: 32px;
        height: 32px;
        background: var(--purple-pale);
        border-radius: var(--radius-sm);
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--purple);
        flex-shrink: 0;
    }

    .file-preview-item-details {
        min-width: 0;
        flex: 1;
    }

    .file-preview-item-name {
        font-size: 0.9rem;
        font-weight: 500;
        color: var(--ink);
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .file-preview-item-size {
        font-size: 0.75rem;
        color: var(--mist);
    }

    .file-preview-item-remove {
        background: transparent;
        border: none;
        color: var(--danger);
        cursor: pointer;
        padding: var(--space-xs);
        border-radius: var(--radius-sm);
        transition: all 0.3s ease;
        flex-shrink: 0;
    }

    .file-preview-item-remove:hover {
        background: var(--danger-light);
    }

    /* Existing file display */
    .existing-file {
        background: var(--purple-pale);
        border: 1px solid var(--purple-light);
        border-radius: var(--radius-md);
        padding: var(--space-sm);
        margin-top: var(--space-sm);
    }

    .existing-file-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: var(--space-xs);
    }

    .existing-file-title {
        font-size: 0.85rem;
        font-weight: 600;
        color: var(--purple-deep);
    }

    .existing-file-actions {
        display: flex;
        gap: var(--space-xs);
    }

    .existing-file-btn {
        background: transparent;
        border: none;
        color: var(--purple);
        cursor: pointer;
        padding: 0.25rem;
        border-radius: var(--radius-sm);
        transition: all 0.3s ease;
    }

    .existing-file-btn:hover {
        background: rgba(108,48,130,0.1);
    }

    .existing-file-btn.danger:hover {
        color: var(--danger);
    }

    .existing-file-preview {
        display: flex;
        align-items: center;
        gap: var(--space-sm);
        margin-bottom: var(--space-xs);
    }

    .existing-file-thumb {
        width: 60px;
        height: 60px;
        border-radius: var(--radius-sm);
        object-fit: cover;
        border: 2px solid var(--white);
        box-shadow: var(--shadow-sm);
    }

    .existing-file-info {
        flex: 1;
        font-size: 0.85rem;
        color: var(--slate);
        word-break: break-all;
    }

    .existing-file-list {
        list-style: none;
    }

    .existing-file-list li {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: var(--space-xs) 0;
        border-bottom: 1px solid var(--border);
    }

    .existing-file-list li:last-child {
        border-bottom: none;
    }

    /* ==========================================================================
       SECTION TITLES
       ========================================================================== */
    .section-title {
        position: relative;
        margin-bottom: var(--space-lg);
        padding-bottom: var(--space-sm);
    }

    .section-title::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        width: 60px;
        height: 3px;
        background: var(--gold-gradient);
        border-radius: 3px;
    }

    .section-title h5 {
        font-size: 1.25rem;
        color: var(--purple-deep);
        margin-bottom: 0.25rem;
    }

    .section-title p {
        font-size: 0.9rem;
        color: var(--slate);
    }

    /* ==========================================================================
       GRID SYSTEM
       ========================================================================== */
    .row {
        display: flex;
        flex-wrap: wrap;
        margin: calc(-1 * var(--space-sm));
    }

    .col {
        flex: 1 0 0%;
        padding: var(--space-sm);
    }

    .col-12 { width: 100%; padding: var(--space-sm); }
    .col-6 { width: 50%; padding: var(--space-sm); }
    .col-4 { width: 33.333%; padding: var(--space-sm); }
    .col-3 { width: 25%; padding: var(--space-sm); }

    @media (min-width: 768px) {
        .col-md-12 { width: 100%; }
        .col-md-8 { width: 66.667%; }
        .col-md-6 { width: 50%; }
        .col-md-4 { width: 33.333%; }
        .col-md-3 { width: 25%; }
    }

    @media (min-width: 992px) {
        .col-lg-10 { width: 83.333%; }
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
        
        .upload-area {
            padding: var(--space-lg) var(--space-md);
        }
        
        .existing-file-preview {
            flex-direction: column;
            align-items: flex-start;
        }
        
        .existing-file-thumb {
            width: 100%;
            height: auto;
            max-height: 150px;
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
                <div class="col-12 col-lg-10 col-xl-8">
                    
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
                    <?php if (isset($flash_success)): ?>
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-check-circle fa-lg me-2"></i>
                                <span><?php echo e($flash_success); ?></span>
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"><i class="fas fa-times"></i></button>
                            </div>
                        </div>
                    <?php endif; ?>
                    
                    <?php if (isset($flash_error)): ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-exclamation-circle fa-lg me-2"></i>
                                <span><?php echo e($flash_error); ?></span>
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"><i class="fas fa-times"></i></button>
                            </div>
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
                                    <p class="mb-0 opacity-75">Complete your details below to proceed</p>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Card Body -->
                        <div class="card-body">
                            <!-- Main Form -->
                            <form id="applicationForm" enctype="multipart/form-data" class="needs-validation" novalidate>
                                <input type="hidden" name="csrf_token" value="<?php echo e($csrf_token); ?>">
                                <input type="hidden" id="jamb_number" name="jamb_number">
                                <input type="hidden" id="utme_score" name="utme_score">
                                
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
                                        <input type="text" class="form-control" id="first_name" readonly>
                                    </div>
                                    <div class="col-12 col-md-4">
                                        <label class="form-label">Last Name <span class="required">*</span></label>
                                        <input type="text" class="form-control" id="last_name" readonly>
                                    </div>
                                    <div class="col-12 col-md-4">
                                        <label class="form-label">Other Names</label>
                                        <input type="text" class="form-control" id="other_names" readonly>
                                    </div>
                                </div>

                                <div class="row mt-3">
                                    <div class="col-12 col-md-3">
                                        <label class="form-label">Gender <span class="required">*</span></label>
                                        <input type="text" class="form-control" id="gender" readonly>
                                    </div>
                                    <div class="col-12 col-md-3">
                                        <label class="form-label">State of Origin <span class="required">*</span></label>
                                        <input type="text" class="form-control" id="state_of_origin" readonly>
                                    </div>
                                    <div class="col-12 col-md-3">
                                        <label class="form-label">LGA <span class="required">*</span></label>
                                        <input type="text" class="form-control" id="lga" readonly>
                                    </div>
                                    <div class="col-12 col-md-3">
                                        <label class="form-label">UTME Score <span class="required">*</span></label>
                                        <input type="text" class="form-control fw-bold text-success" id="utme_score_display" readonly>
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
                                               value="<?php echo e($application['phone'] ?? ''); ?>"
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

                                <!-- Document Upload Section -->
                                <div class="section-title mt-5">
                                    <h5 class="fw-bold text-primary mb-0">
                                        <i class="fas fa-cloud-upload-alt me-2"></i>Document Upload
                                    </h5>
                                    <p class="text-muted small">Upload required documents (PDF, JPG, PNG accepted)</p>
                                </div>
                                
                                <div class="row">
                                    <!-- Passport Upload -->
                                    <div class="col-12 col-md-6">
                                        <div class="upload-area" id="passportArea">
                                            <i class="fas fa-camera"></i>
                                            <h6>Passport Photograph <span class="text-danger">*</span></h6>
                                            <p>Upload a recent passport photo</p>
                                            <input type="file" class="form-control" id="passport" name="passport" 
                                                   accept="image/jpeg,image/png" style="display: none;">
                                            <button type="button" class="btn btn--outline btn--sm" onclick="document.getElementById('passport').click()">
                                                <i class="fas fa-upload me-2"></i><?php echo isset($existing_passport) ? 'Replace File' : 'Choose File'; ?>
                                            </button>
                                            <div class="form-text mt-2">Max size: 1MB. Format: JPG, PNG</div>
                                            
                                            <!-- Passport Preview -->
                                            <div id="passportPreview" class="file-preview">
                                                <?php if (isset($existing_passport)): ?>
                                                <div class="existing-file">
                                                    <div class="existing-file-header">
                                                        <span class="existing-file-title">
                                                            <i class="fas fa-check-circle text-success me-1"></i>Current Passport
                                                        </span>
                                                        <div class="existing-file-actions">
                                                            <a href="<?php echo e($existing_passport['file_path']); ?>" target="_blank" class="existing-file-btn" title="View">
                                                                <i class="fas fa-eye"></i>
                                                            </a>
                                                            <button type="button" class="existing-file-btn danger" onclick="removeExistingFile('passport')" title="Remove">
                                                                <i class="fas fa-times"></i>
                                                            </button>
                                                        </div>
                                                    </div>
                                                    <div class="existing-file-preview">
                                                        <img src="<?php echo e($existing_passport['file_path']); ?>" class="existing-file-thumb" alt="Passport">
                                                        <div class="existing-file-info">
                                                            <?php echo e(basename($existing_passport['file_path'])); ?>

                                                        </div>
                                                    </div>
                                                </div>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- O'Level Upload -->
                                    <div class="col-12 col-md-6">
                                        <div class="upload-area" id="olevelArea">
                                            <i class="fas fa-file-pdf"></i>
                                            <h6>O'Level Results <span class="text-danger">*</span></h6>
                                            <p>Upload WAEC/NECO results</p>
                                            <input type="file" class="form-control" id="olevel" name="olevel[]" 
                                                   multiple accept=".pdf,.jpg,.jpeg,.png" style="display: none;">
                                            <button type="button" class="btn btn--outline btn--sm" onclick="document.getElementById('olevel').click()">
                                                <i class="fas fa-upload me-2"></i><?php echo isset($existing_olevel) && count($existing_olevel) > 0 ? 'Add More Files' : 'Choose Files'; ?>
                                            </button>
                                            <div class="form-text mt-2">Max 5 files, 2MB each. PDF or Images</div>
                                            
                                            <!-- O'Level Preview -->
                                            <div id="olevelPreview" class="file-preview">
                                                <?php if (isset($existing_olevel) && !empty($existing_olevel)): ?>
                                                <div class="existing-file">
                                                    <div class="existing-file-header">
                                                        <span class="existing-file-title">
                                                            <i class="fas fa-check-circle text-success me-1"></i>Uploaded Files (<?php echo count($existing_olevel); ?>)
                                                        </span>
                                                    </div>
                                                    <ul class="existing-file-list">
                                                        <?php foreach ($existing_olevel as $index => $file): ?>
                                                        <li>
                                                            <div class="d-flex align-items-center gap-2">
                                                                <i class="fas fa-file-<?php echo pathinfo($file['file_path'], PATHINFO_EXTENSION) == 'pdf' ? 'pdf' : 'image'; ?> text-primary"></i>
                                                                <small class="text-muted" style="max-width: 200px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                                                                    <?php echo e(basename($file['file_path'])); ?>

                                                                </small>
                                                            </div>
                                                            <div class="d-flex gap-1">
                                                                <a href="<?php echo e($file['file_path']); ?>" target="_blank" class="existing-file-btn" title="View">
                                                                    <i class="fas fa-eye"></i>
                                                                </a>
                                                                <button type="button" class="existing-file-btn danger" onclick="removeExistingOlevel(<?php echo $index; ?>)" title="Remove">
                                                                    <i class="fas fa-times"></i>
                                                                </button>
                                                            </div>
                                                        </li>
                                                        <?php endforeach; ?>
                                                    </ul>
                                                </div>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Optional Documents -->
                                <div class="row mt-4">
                                    <div class="col-12 col-md-6">
                                        <label class="form-label">JAMB Result Slip (Optional)</label>
                                        <div class="d-flex align-items-center gap-2 flex-wrap">
                                            <input type="file" class="form-control" id="jamb_result" name="jamb_result" 
                                                   accept=".pdf,.jpg,.jpeg,.png" style="display: none;">
                                            <button type="button" class="btn btn--outline" onclick="document.getElementById('jamb_result').click()">
                                                <i class="fas fa-upload me-2"></i><?php echo isset($existing_jamb_result) ? 'Replace' : 'Upload'; ?>
                                            </button>
                                            <?php if (isset($existing_jamb_result)): ?>
                                            <span class="text-success d-flex align-items-center gap-2">
                                                <i class="fas fa-check-circle"></i>
                                                <small class="text-muted" style="max-width: 150px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                                                    <?php echo e(basename($existing_jamb_result['file_path'])); ?>

                                                </small>
                                                <a href="<?php echo e($existing_jamb_result['file_path']); ?>" target="_blank" class="existing-file-btn" title="View">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <button type="button" class="btn btn--sm btn--outline text-danger" onclick="removeExistingJambResult()" title="Remove">
                                                    <i class="fas fa-times"></i>
                                                </button>
                                            </span>
                                            <?php endif; ?>
                                        </div>
                                        <div class="form-text">Upload your JAMB result slip. Max 2MB.</div>
                                    </div>
                                    
                                    <div class="col-12 col-md-6">
                                        <label class="form-label">Birth Certificate (Optional)</label>
                                        <div class="d-flex align-items-center gap-2 flex-wrap">
                                            <input type="file" class="form-control" id="birth_certificate" name="birth_certificate" 
                                                   accept=".pdf,.jpg,.jpeg,.png" style="display: none;">
                                            <button type="button" class="btn btn--outline" onclick="document.getElementById('birth_certificate').click()">
                                                <i class="fas fa-upload me-2"></i><?php echo isset($existing_birth_certificate) ? 'Replace' : 'Upload'; ?>
                                            </button>
                                            <?php if (isset($existing_birth_certificate)): ?>
                                            <span class="text-success d-flex align-items-center gap-2">
                                                <i class="fas fa-check-circle"></i>
                                                <small class="text-muted" style="max-width: 150px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                                                    <?php echo e(basename($existing_birth_certificate['file_path'])); ?>

                                                </small>
                                                <a href="<?php echo e($existing_birth_certificate['file_path']); ?>" target="_blank" class="existing-file-btn" title="View">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <button type="button" class="btn btn--sm btn--outline text-danger" onclick="removeExistingBirthCertificate()" title="Remove">
                                                    <i class="fas fa-times"></i>
                                                </button>
                                            </span>
                                            <?php endif; ?>
                                        </div>
                                        <div class="form-text">Upload your birth certificate. Max 2MB.</div>
                                    </div>
                                </div>

                                <!-- Navigation Buttons -->
                                <div class="d-flex justify-content-between mt-5">
                                    <a href="/apply/step/1" class="btn btn--outline btn--lg" onclick="return confirmBack()">
                                        <i class="fas fa-arrow-left me-2"></i>Back to JAMB Info
                                    </a>
                                    <button type="submit" class="btn btn--success btn--lg" id="submitBtn">
                                        <span id="submitText">
                                            <i class="fas fa-save me-2"></i>Save & Continue
                                        </span>
                                        <span id="submitSpinner" style="display: none;">
                                            <span class="spinner-border me-2" role="status" aria-hidden="true"></span>
                                            Saving...
                                        </span>
                                    </button>
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
    // Load saved form data from database (passed from controller)
    document.addEventListener('DOMContentLoaded', function() {
        <?php if (isset($application)): ?>
        // Pre-fill form with existing data (already done in HTML)
        console.log('Application data loaded from server');
        <?php endif; ?>
    });

    // Load JAMB data on page load - FIXED to check both sessionStorage AND server data
    document.addEventListener('DOMContentLoaded', function() {
        // First check if we have JAMB data from the server (passed from PHP)
        <?php if (isset($jamb_data) && $jamb_data): ?>
        // Use server-provided JAMB data
        const serverJambData = <?php echo json_encode($jamb_data); ?>;
        console.log('Using server JAMB data:', serverJambData);
        
        // Fill JAMB data from server
        document.getElementById('first_name').value = serverJambData.first_name || '';
        document.getElementById('last_name').value = serverJambData.last_name || '';
        document.getElementById('other_names').value = serverJambData.other_names || '';
        
        // Convert gender code to full text if needed
        let genderText = '';
        if (serverJambData.gender === 'M') genderText = 'Male';
        else if (serverJambData.gender === 'F') genderText = 'Female';
        else genderText = serverJambData.gender || '';
        document.getElementById('gender').value = genderText;
        
        document.getElementById('state_of_origin').value = serverJambData.state_of_origin || '';
        document.getElementById('lga').value = serverJambData.lga || '';
        document.getElementById('utme_score_display').value = serverJambData.score || '';
        
        // Hidden fields
        document.getElementById('jamb_number').value = serverJambData.jamb_number || '';
        document.getElementById('utme_score').value = serverJambData.score || '';
        
        // Update summary
        document.getElementById('jambSummary').innerHTML = `
            <div class="jamb-summary-content">
                <div class="jamb-icon">
                    <i class="fas fa-check-circle"></i>
                </div>
                <div class="jamb-details">
                    <h5>JAMB Verified Successfully</h5>
                    <p><strong>${serverJambData.first_name} ${serverJambData.last_name}</strong> | JAMB: ${serverJambData.jamb_number} | Score: <span class="jamb-badge">${serverJambData.score}</span></p>
                </div>
            </div>
        `;
        
        <?php else: ?>
        // Fallback to sessionStorage if no server data
        const jambData = sessionStorage.getItem('jamb_data');
        const jambVerified = sessionStorage.getItem('jamb_verified');
        
        if (!jambData || !jambVerified) {
            // Check if we have application data from server instead
            <?php if (isset($application) && !empty($application['jamb_number'])): ?>
            // We have application data but no JAMB data in sessionStorage - this is OK
            console.log('No sessionStorage JAMB data but application exists - proceeding normally');
            
            // Construct JAMB data from application
            const appData = {
                jamb_number: '<?php echo $application['jamb_number'] ?? ''; ?>',
                first_name: '<?php echo $application['first_name'] ?? ''; ?>',
                last_name: '<?php echo $application['last_name'] ?? ''; ?>',
                other_names: '<?php echo $application['other_names'] ?? ''; ?>',
                gender: '<?php echo $application['gender'] ?? ''; ?>',
                state_of_origin: '<?php echo $application['state_of_origin'] ?? ''; ?>',
                lga: '<?php echo $application['lga'] ?? ''; ?>',
                score: '<?php echo $application['utme_score'] ?? ''; ?>'
            };
            
            // Fill JAMB data from application
            document.getElementById('first_name').value = appData.first_name || '';
            document.getElementById('last_name').value = appData.last_name || '';
            document.getElementById('other_names').value = appData.other_names || '';
            
            // Convert gender code to full text
            let genderText = '';
            if (appData.gender === 'M') genderText = 'Male';
            else if (appData.gender === 'F') genderText = 'Female';
            else genderText = appData.gender || '';
            document.getElementById('gender').value = genderText;
            
            document.getElementById('state_of_origin').value = appData.state_of_origin || '';
            document.getElementById('lga').value = appData.lga || '';
            document.getElementById('utme_score_display').value = appData.score || '';
            
            // Hidden fields
            document.getElementById('jamb_number').value = appData.jamb_number || '';
            document.getElementById('utme_score').value = appData.score || '';
            
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
            
            <?php else: ?>
            // No JAMB data anywhere - redirect to verification
            console.log('No JAMB data found anywhere, redirecting to verification');
            showAlert('Please verify your JAMB number first', 'warning');
            setTimeout(() => {
                window.location.href = '/apply/step/1';
            }, 2000);
            return;
            <?php endif; ?>
        } else {
            // Use sessionStorage data
            try {
                const data = JSON.parse(jambData);
                console.log('Loading JAMB data from sessionStorage:', data);
                
                // Fill JAMB data
                document.getElementById('first_name').value = data.first_name || '';
                document.getElementById('last_name').value = data.last_name || '';
                document.getElementById('other_names').value = data.other_names || '';
                
                // Convert gender code to full text
                let genderText = '';
                if (data.gender === 'M') genderText = 'Male';
                else if (data.gender === 'F') genderText = 'Female';
                document.getElementById('gender').value = genderText;
                
                document.getElementById('state_of_origin').value = data.state_of_origin || '';
                document.getElementById('lga').value = data.lga || '';
                document.getElementById('utme_score_display').value = data.score || '';
                
                // Hidden fields
                document.getElementById('jamb_number').value = data.jamb_number || '';
                document.getElementById('utme_score').value = data.score || '';
                
                // Update summary
                document.getElementById('jambSummary').innerHTML = `
                    <div class="jamb-summary-content">
                        <div class="jamb-icon">
                            <i class="fas fa-check-circle"></i>
                        </div>
                        <div class="jamb-details">
                            <h5>JAMB Verified Successfully</h5>
                            <p><strong>${data.first_name} ${data.last_name}</strong> | JAMB: ${data.jamb_number} | Score: <span class="jamb-badge">${data.score}</span></p>
                        </div>
                    </div>
                `;
            } catch (e) {
                console.error('Error parsing JAMB data:', e);
                <?php if (isset($application) && !empty($application['jamb_number'])): ?>
                console.log('Falling back to application data');
                // Fallback handled above
                <?php else: ?>
                showAlert('Error loading JAMB data. Please verify again.', 'danger');
                setTimeout(() => {
                    window.location.href = '/apply/step/1';
                }, 2000);
                <?php endif; ?>
            }
        }
        <?php endif; ?>
    });

    // Functions to remove existing files
    function removeExistingFile(type) {
        if (confirm('Are you sure you want to remove this file?')) {
            const formData = new FormData();
            formData.append('type', type);
            formData.append('csrf_token', '<?php echo $csrf_token; ?>');
            
            showAlert('Removing file...', 'info');
            
            fetch('/apply/remove-document', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showAlert('File removed successfully', 'success');
                    setTimeout(() => location.reload(), 1000);
                } else {
                    showAlert('Failed to remove file: ' + (data.message || 'Unknown error'), 'danger');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showAlert('Network error. Please try again.', 'danger');
            });
        }
    }

    function removeExistingOlevel(index) {
        if (confirm('Are you sure you want to remove this O\'Level result?')) {
            const formData = new FormData();
            formData.append('type', 'olevel');
            formData.append('index', index);
            formData.append('csrf_token', '<?php echo $csrf_token; ?>');
            
            showAlert('Removing file...', 'info');
            
            fetch('/apply/remove-document', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showAlert('File removed successfully', 'success');
                    setTimeout(() => location.reload(), 1000);
                } else {
                    showAlert('Failed to remove file: ' + (data.message || 'Unknown error'), 'danger');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showAlert('Network error. Please try again.', 'danger');
            });
        }
    }

    function removeExistingJambResult() {
        if (confirm('Are you sure you want to remove your JAMB result slip?')) {
            const formData = new FormData();
            formData.append('type', 'jamb_result');
            formData.append('csrf_token', '<?php echo $csrf_token; ?>');
            
            showAlert('Removing file...', 'info');
            
            fetch('/apply/remove-document', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showAlert('File removed successfully', 'success');
                    setTimeout(() => location.reload(), 1000);
                } else {
                    showAlert('Failed to remove file: ' + (data.message || 'Unknown error'), 'danger');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showAlert('Network error. Please try again.', 'danger');
            });
        }
    }

    function removeExistingBirthCertificate() {
        if (confirm('Are you sure you want to remove your birth certificate?')) {
            const formData = new FormData();
            formData.append('type', 'birth_certificate');
            formData.append('csrf_token', '<?php echo $csrf_token; ?>');
            
            showAlert('Removing file...', 'info');
            
            fetch('/apply/remove-document', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showAlert('File removed successfully', 'success');
                    setTimeout(() => location.reload(), 1000);
                } else {
                    showAlert('Failed to remove file: ' + (data.message || 'Unknown error'), 'danger');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showAlert('Network error. Please try again.', 'danger');
            });
        }
    }

    // Form submission with enhanced error handling
    document.getElementById('applicationForm').addEventListener('submit', async function(e) {
        e.preventDefault();
        
        // Validate required fields
        const dob = document.getElementById('date_of_birth').value;
        const phone = document.getElementById('phone').value;
        const address = document.getElementById('address').value;
        const program = document.getElementById('program_choice').value;
        
        // Only validate file uploads if no existing files
        <?php if (!isset($existing_passport)): ?>
        const passport = document.getElementById('passport').files[0];
        if (!passport) {
            showAlert('Please upload your passport photograph', 'danger');
            return;
        }
        <?php endif; ?>
        
        <?php if (!isset($existing_olevel) || empty($existing_olevel)): ?>
        const olevel = document.getElementById('olevel').files;
        if (olevel.length === 0) {
            showAlert('Please upload your O\'Level results', 'danger');
            return;
        }
        <?php endif; ?>
        
        if (!dob) {
            showAlert('Please enter your date of birth', 'danger');
            return;
        }
        
        if (!phone || !/^[0-9]{11}$/.test(phone)) {
            showAlert('Please enter a valid 11-digit phone number', 'danger');
            return;
        }
        
        if (!address || address.trim() === '') {
            showAlert('Please enter your address', 'danger');
            return;
        }
        
        if (!program) {
            showAlert('Please select your program', 'danger');
            return;
        }
        
        // Validate JAMB data exists
        const jambNumber = document.getElementById('jamb_number').value;
        if (!jambNumber) {
            showAlert('JAMB verification data not found. Please restart your application.', 'danger');
            return;
        }
        
        // Show loading state
        document.getElementById('submitText').style.display = 'none';
        document.getElementById('submitSpinner').style.display = 'inline-block';
        document.getElementById('submitBtn').disabled = true;
        
        try {
            // Create FormData with all form data
            const formData = new FormData(this);
            
            // Add JAMB data
            formData.append('jamb_number', document.getElementById('jamb_number').value);
            formData.append('first_name', document.getElementById('first_name').value);
            formData.append('last_name', document.getElementById('last_name').value);
            formData.append('other_names', document.getElementById('other_names').value);
            
            // Convert gender text back to code
            const genderField = document.getElementById('gender').value;
            const genderCode = genderField === 'Male' ? 'M' : (genderField === 'Female' ? 'F' : '');
            formData.append('gender', genderCode);
            
            formData.append('state_of_origin', document.getElementById('state_of_origin').value);
            formData.append('lga', document.getElementById('lga').value);
            formData.append('utme_score', document.getElementById('utme_score').value || '');
            
            // Make the API call
            const response = await fetch('/apply/save-application', {
                method: 'POST',
                body: formData
            });
            
            // Check if response is OK
            if (!response.ok) {
                throw new Error(`Server error: ${response.status} ${response.statusText}`);
            }
            
            // Check content type
            const contentType = response.headers.get('content-type');
            if (!contentType || !contentType.includes('application/json')) {
                const text = await response.text();
                console.error('Non-JSON response received:', text.substring(0, 200));
                throw new Error('Server returned an invalid response format');
            }
            
            // Parse JSON response
            const result = await response.json();
            
            if (result.success) {
                showAlert('Application saved successfully! Redirecting to payment...', 'success');
                
                // Redirect to payment page
                setTimeout(() => {
                    window.location.href = '/apply/step/3';
                }, 2000);
            } else {
                // Show error message from server
                const errorMessage = result.message || 'Failed to save application. Please try again.';
                showAlert(errorMessage, 'danger');
                
                // If there are upload errors, show them
                if (result.upload_errors && result.upload_errors.length > 0) {
                    console.warn('Upload errors:', result.upload_errors);
                }
                
                resetSubmitButton();
            }
        } catch (error) {
            console.error('Form submission error:', error);
            
            // Show user-friendly error message
            let userMessage = 'Network error. Please check your connection and try again.';
            
            if (error.message.includes('Failed to fetch')) {
                userMessage = 'Unable to connect to server. Please check your internet connection.';
            } else if (error.message.includes('JSON')) {
                userMessage = 'Server error. Please try again later.';
            } else if (error.message.includes('500')) {
                userMessage = 'Server error. Our team has been notified.';
            } else if (error.message.includes('404')) {
                userMessage = 'Service temporarily unavailable. Please try again.';
            } else if (error.message) {
                userMessage = error.message;
            }
            
            showAlert(userMessage, 'danger');
            resetSubmitButton();
        }
    });

    // File upload preview functions
    document.getElementById('passport').addEventListener('change', function(e) {
        if (this.files && this.files[0]) {
            const file = this.files[0];
            
            // Validate file size
            if (file.size > 1 * 1024 * 1024) {
                showAlert('Passport image must be less than 1MB', 'warning');
                this.value = '';
                return;
            }
            
            // Validate file type
            if (!file.type.match('image.*')) {
                showAlert('Please upload an image file (JPG, PNG)', 'warning');
                this.value = '';
                return;
            }
            
            const reader = new FileReader();
            reader.onload = function(e) {
                const preview = document.getElementById('passportPreview');
                preview.innerHTML = `
                    <div class="existing-file">
                        <div class="existing-file-header">
                            <span class="existing-file-title">
                                <i class="fas fa-check-circle text-success me-1"></i>New File
                            </span>
                            <button type="button" class="existing-file-btn danger" onclick="removePassport()" title="Remove">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                        <div class="existing-file-preview">
                            <img src="${e.target.result}" class="existing-file-thumb" alt="Passport">
                            <div class="existing-file-info">
                                ${file.name} (${(file.size / 1024).toFixed(1)}KB)
                            </div>
                        </div>
                    </div>
                `;
                
                // Update upload area styling
                document.getElementById('passportArea').classList.add('border-success');
            }
            reader.readAsDataURL(file);
        }
    });

    document.getElementById('olevel').addEventListener('change', function(e) {
        const files = this.files;
        const preview = document.getElementById('olevelPreview');
        
        if (files.length > 0) {
            let html = '<div class="existing-file"><div class="existing-file-header"><span class="existing-file-title"><i class="fas fa-check-circle text-success me-1"></i>New Files</span></div><ul class="existing-file-list">';
            let totalSize = 0;
            
            for (let i = 0; i < files.length; i++) {
                const file = files[i];
                totalSize += file.size;
                
                // Validate each file
                if (file.size > 2 * 1024 * 1024) {
                    showAlert(`File "${file.name}" exceeds 2MB limit`, 'warning');
                    this.value = '';
                    preview.innerHTML = '';
                    return;
                }
                
                html += `
                    <li>
                        <div class="d-flex align-items-center gap-2">
                            <i class="fas fa-file-${file.type.includes('pdf') ? 'pdf' : 'image'} text-primary"></i>
                            <small class="text-muted" style="max-width: 200px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                                ${file.name} (${(file.size / 1024).toFixed(1)}KB)
                            </small>
                        </div>
                    </li>
                `;
            }
            
            // Check total size (optional - adjust as needed)
            if (totalSize > 10 * 1024 * 1024) {
                showAlert('Total file size exceeds 10MB. Please compress files.', 'warning');
                this.value = '';
                preview.innerHTML = '';
                return;
            }
            
            html += '</ul></div>';
            preview.innerHTML = html;
            
            // Update upload area styling
            document.getElementById('olevelArea').classList.add('border-success');
        }
    });

    document.getElementById('jamb_result').addEventListener('change', function(e) {
        if (this.files && this.files[0]) {
            const file = this.files[0];
            if (file.size > 2 * 1024 * 1024) {
                showAlert('File must be less than 2MB', 'warning');
                this.value = '';
            }
        }
    });

    document.getElementById('birth_certificate').addEventListener('change', function(e) {
        if (this.files && this.files[0]) {
            const file = this.files[0];
            if (file.size > 2 * 1024 * 1024) {
                showAlert('File must be less than 2MB', 'warning');
                this.value = '';
            }
        }
    });

    function removePassport() {
        document.getElementById('passport').value = '';
        document.getElementById('passportPreview').innerHTML = '';
        document.getElementById('passportArea').classList.remove('border-success');
    }

    function confirmBack() {
        return confirm('Are you sure you want to go back to JAMB verification? Your form data will be lost if you haven\'t saved.');
    }

    function showAlert(message, type) {
        const alertContainer = document.getElementById('alertContainer');
        const icon = type === 'success' ? 'fa-check-circle' : 
                     type === 'warning' ? 'fa-exclamation-triangle' : 'fa-exclamation-circle';
        
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
        
        // Auto dismiss after 5 seconds (except for errors)
        if (type !== 'danger') {
            setTimeout(() => {
                const alert = alertContainer.querySelector('.alert');
                if (alert) {
                    alert.remove();
                }
            }, 5000);
        }
    }

    function resetSubmitButton() {
        document.getElementById('submitText').style.display = 'inline-block';
        document.getElementById('submitSpinner').style.display = 'none';
        document.getElementById('submitBtn').disabled = false;
    }

    // Bootstrap form validation
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
    </script>
</body>
</html>