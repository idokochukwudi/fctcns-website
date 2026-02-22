<?php
/**
 * Registration View - Step 1
 * REDESIGNED: International standards, fully responsive, accessible with purple accents
 * Color scheme matching JAMB verification page
 * @package FCTCNS
 */

// =========================================================
// FIX: Add require for SecurityHelper
// =========================================================
// Try multiple paths to ensure SecurityHelper is found
$possiblePaths = [
    defined('APP_PATH') ? APP_PATH . '/helpers/SecurityHelper.php' : null,
    dirname(__DIR__, 2) . '/helpers/SecurityHelper.php',
    __DIR__ . '/../../helpers/SecurityHelper.php',
    $_SERVER['DOCUMENT_ROOT'] . '/fctcns-website/app/helpers/SecurityHelper.php'
];

$loaded = false;
foreach ($possiblePaths as $path) {
    if ($path && file_exists($path)) {
        require_once $path;
        $loaded = true;
        break;
    }
}

if (!$loaded) {
    die('Error: SecurityHelper.php not found. Please check the file path.');
}

// =========================================================
// 1. Add the trait at the top of each view file
// =========================================================
$traitPaths = [
    defined('APP_PATH') ? APP_PATH . '/helpers/SecurityTrait.php' : null,
    dirname(__DIR__, 2) . '/helpers/SecurityTrait.php',
    __DIR__ . '/../../helpers/SecurityTrait.php',
    $_SERVER['DOCUMENT_ROOT'] . '/fctcns-website/app/helpers/SecurityTrait.php'
];

$traitLoaded = false;
foreach ($traitPaths as $path) {
    if ($path && file_exists($path)) {
        require_once $path;
        $traitLoaded = true;
        break;
    }
}

if (!$traitLoaded) {
    die('Error: SecurityTrait.php not found. Please check the file path.');
}

class RegistrationView {
    use SecurityTrait;
    
    public function render($data) {
        extract($data);
        
        // Get security tokens
        $csp_nonce = $this->getCspNonce();
        $csrf_token = $this->getCsrfToken();

        $terms         = $terms         ?? [];
        $portal_closed = $portal_closed ?? false;
        $portal_message= $portal_message?? '';
        
        // Get Font Awesome SRI hash
        $faUrl = 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css';
        $faSri = SecurityHelper::getSriHash($faUrl);
        ?>
        <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
            <meta name="description" content="Create your account for FCT College of Nursing Sciences admissions">
            <meta name="theme-color" content="#6B4E9B">
            
            <!-- ========================================================= -->
            <!-- 2. Add security meta tags in the head -->
            <!-- ========================================================= -->
            <?php echo $this->getSecurityMetaTags(); ?>
            
            <title>Create Account — FCT College of Nursing Sciences</title>

            <!-- CSRF Token for JavaScript - Using the same token -->
            <meta name="csrf-token" content="<?php echo $this->e($csrf_token); ?>">

            <!-- Preconnect for performance -->
            <link rel="preconnect" href="https://fonts.googleapis.com">
            <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
            
            <!-- ========================================================= -->
            <!-- 3. Add CSP nonce to all style tags -->
            <!-- 7. Add SRI hashes to external scripts/styles -->
            <!-- ========================================================= -->
            
            <!-- Google Fonts - NO SRI HASH (they change dynamically) -->
            <link href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&display=swap" 
                  rel="stylesheet"
                  crossorigin="anonymous">
            
            <!-- Font Awesome with CORRECT SRI hash -->
            <link rel="stylesheet" 
                  href="<?php echo $faUrl; ?>"
                  <?php if ($faSri): ?>integrity="<?php echo $faSri; ?>"<?php endif; ?>
                  crossorigin="anonymous" 
                  referrerpolicy="no-referrer">

            <style nonce="<?php echo $csp_nonce; ?>">
                /* ============================================
                   DESIGN SYSTEM — JAMB Verification Color Scheme
                   ============================================ */
                :root {
                    /* Primary purple palette - matching JAMB page */
                    --sv1-primary:       #6B4E9B;
                    --sv1-primary-dark:  #4A3B6B;
                    --sv1-primary-light: #8A6FB0;
                    --sv1-primary-soft:  #F3EAF8;
                    --sv1-gold:          #C9A44A;
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
                    
                    /* Typography */
                    --font-sans: 'Inter', system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
                    
                    /* Spacing */
                    --space-unit: 0.25rem;
                    --space-1: calc(var(--space-unit) * 1);   /* 4px */
                    --space-2: calc(var(--space-unit) * 2);   /* 8px */
                    --space-3: calc(var(--space-unit) * 3);   /* 12px */
                    --space-4: calc(var(--space-unit) * 4);   /* 16px */
                    --space-5: calc(var(--space-unit) * 5);   /* 20px */
                    --space-6: calc(var(--space-unit) * 6);   /* 24px */
                    --space-8: calc(var(--space-unit) * 8);   /* 32px */
                    --space-10: calc(var(--space-unit) * 10); /* 40px */
                    --space-12: calc(var(--space-unit) * 12); /* 48px */
                    --space-16: calc(var(--space-unit) * 16); /* 64px */
                    
                    /* Borders */
                    --sv1-radius-md:     12px;
                    --sv1-radius-lg:     20px;
                    --sv1-radius-xl:     30px;
                    
                    /* Shadows */
                    --sv1-shadow-primary: 0 10px 30px rgba(107,78,155,0.3);
                    --shadow-sm: 0 1px 3px 0 rgb(0 0 0 / 0.1), 0 1px 2px -1px rgb(0 0 0 / 0.1);
                    --shadow-md: 0 4px 6px -1px rgb(0 0 0 / 0.1), 0 2px 4px -2px rgb(0 0 0 / 0.1);
                    --shadow-lg: 0 10px 15px -3px rgb(0 0 0 / 0.1), 0 4px 6px -4px rgb(0 0 0 / 0.1);
                    --shadow-xl: 0 20px 25px -5px rgb(0 0 0 / 0.1), 0 8px 10px -6px rgb(0 0 0 / 0.1);
                    
                    /* Layout */
                    --max-width-mobile: 480px;
                    --max-width-tablet: 640px;
                    --max-width-desktop: 1280px;
                }

                /* ============================================
                   RESET & BASE STYLES
                   ============================================ */
                *, *::before, *::after {
                    box-sizing: border-box;
                    margin: 0;
                    padding: 0;
                }

                html {
                    font-size: 16px;
                    -webkit-text-size-adjust: 100%;
                    scroll-behavior: smooth;
                    text-rendering: optimizeLegibility;
                    -webkit-font-smoothing: antialiased;
                    -moz-osx-font-smoothing: grayscale;
                }

                body {
                    font-family: var(--font-sans);
                    background: linear-gradient(135deg, var(--sv1-primary-soft) 0%, #ffffff 100%);
                    min-height: 100vh;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    padding: var(--space-4);
                    color: var(--sv1-text-dark);
                    line-height: 1.5;
                }

                /* ============================================
                   CONTAINER
                   ============================================ */
                .registration-container {
                    width: 100%;
                    max-width: var(--max-width-tablet);
                    margin: 0 auto;
                    animation: fadeIn 0.5s ease-out;
                }

                @keyframes fadeIn {
                    from { opacity: 0; transform: translateY(10px); }
                    to { opacity: 1; transform: translateY(0); }
                }

                /* ============================================
                   STEP INDICATOR - International Standard
                   ============================================ */
                .step-indicator {
                    display: flex;
                    justify-content: space-between;
                    align-items: center;
                    margin-bottom: var(--space-8);
                    padding: var(--space-2) 0;
                }

                .step-item {
                    flex: 1;
                    display: flex;
                    flex-direction: column;
                    align-items: center;
                    position: relative;
                    text-align: center;
                }

                .step-item:not(:last-child)::after {
                    content: '';
                    position: absolute;
                    top: 1.25rem;
                    right: -50%;
                    width: 100%;
                    height: 2px;
                    background: var(--sv1-border);
                    z-index: 0;
                }

                .step-item.active:not(:last-child)::after,
                .step-item.completed:not(:last-child)::after {
                    background: var(--sv1-primary);
                }

                .step-number {
                    width: 2.5rem;
                    height: 2.5rem;
                    background: white;
                    border: 2px solid var(--sv1-border);
                    border-radius: 50%;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    font-weight: 600;
                    color: var(--sv1-text-muted);
                    margin-bottom: var(--space-2);
                    position: relative;
                    z-index: 1;
                    transition: all 0.2s ease;
                }

                .step-item.active .step-number {
                    background: var(--sv1-primary);
                    border-color: var(--sv1-primary);
                    color: white;
                    box-shadow: 0 0 0 4px var(--sv1-primary-soft);
                }

                .step-item.completed .step-number {
                    background: var(--sv1-success);
                    border-color: var(--sv1-success);
                    color: white;
                }

                .step-label {
                    font-size: 0.75rem;
                    font-weight: 500;
                    color: var(--sv1-text-muted);
                    text-transform: uppercase;
                    letter-spacing: 0.05em;
                    display: none;
                }

                .step-item.active .step-label {
                    color: var(--sv1-primary-dark);
                    font-weight: 600;
                }

                .step-item.completed .step-label {
                    color: var(--sv1-success);
                }

                @media (min-width: 640px) {
                    .step-label {
                        display: block;
                    }
                }

                /* ============================================
                   MAIN CARD - Matching JAMB page
                   ============================================ */
                .registration-card {
                    background: #fff;
                    border-radius: var(--sv1-radius-xl);
                    box-shadow: var(--shadow-xl);
                    overflow: hidden;
                    border: 1px solid var(--sv1-border);
                }

                /* Card Header - Gradient like JAMB page */
                .card-header {
                    background: linear-gradient(135deg, var(--sv1-primary) 0%, var(--sv1-primary-dark) 100%);
                    padding: var(--space-8) var(--space-6);
                    text-align: center;
                    color: white;
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
                    transform: rotate(30deg);
                }

                .header-icon {
                    width: 4rem;
                    height: 4rem;
                    background: rgba(255,255,255,0.15);
                    border-radius: 50%;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    margin: 0 auto var(--space-4);
                    font-size: 1.5rem;
                    border: 2px solid rgba(255,255,255,0.3);
                    backdrop-filter: blur(4px);
                    color: var(--sv1-gold);
                }

                .card-header h1 {
                    font-size: 1.5rem;
                    font-weight: 700;
                    margin-bottom: var(--space-2);
                    letter-spacing: -0.02em;
                    color: white;
                }

                .card-header p {
                    font-size: 0.875rem;
                    opacity: 0.9;
                    max-width: 300px;
                    margin: 0 auto;
                }

                @media (min-width: 640px) {
                    .card-header {
                        padding: var(--space-10) var(--space-8);
                    }
                    .card-header h1 {
                        font-size: 2rem;
                    }
                    .card-header p {
                        font-size: 1rem;
                        max-width: 400px;
                    }
                }

                /* Card Body */
                .card-body {
                    padding: var(--space-6);
                }

                @media (min-width: 640px) {
                    .card-body {
                        padding: var(--space-8);
                    }
                }

                /* Card Footer */
                .card-footer {
                    padding: var(--space-4) var(--space-6);
                    background: var(--sv1-primary-soft);
                    border-top: 1px solid var(--sv1-border);
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    gap: var(--space-2);
                    color: var(--sv1-text-muted);
                    font-size: 0.875rem;
                }

                .card-footer i {
                    color: var(--sv1-primary-light);
                }

                /* ============================================
                   ALERTS
                   ============================================ */
                .alert {
                    padding: var(--space-4);
                    border-radius: var(--sv1-radius-md);
                    margin-bottom: var(--space-6);
                    display: flex;
                    align-items: flex-start;
                    gap: var(--space-3);
                    animation: slideIn 0.3s ease;
                    border-left-width: 4px;
                    border-left-style: solid;
                }

                @keyframes slideIn {
                    from { opacity: 0; transform: translateY(-10px); }
                    to { opacity: 1; transform: translateY(0); }
                }

                .alert-danger {
                    background: var(--sv1-danger-light);
                    border-color: var(--sv1-danger);
                    color: #991b1b;
                }

                .alert-success {
                    background: var(--sv1-success-light);
                    border-color: var(--sv1-success);
                    color: #065f46;
                }

                .alert-info {
                    background: var(--sv1-info-light);
                    border-color: var(--sv1-info);
                    color: #1e40af;
                }

                .alert-warning {
                    background: var(--sv1-warning-light);
                    border-color: var(--sv1-warning);
                    color: #92400e;
                }

                .alert-icon {
                    flex-shrink: 0;
                    font-size: 1.25rem;
                }

                .alert-content {
                    flex: 1;
                    font-size: 0.875rem;
                }

                .alert-close {
                    background: none;
                    border: none;
                    color: currentColor;
                    opacity: 0.5;
                    cursor: pointer;
                    padding: 0 var(--space-1);
                    font-size: 1.25rem;
                    line-height: 1;
                    transition: opacity 0.2s;
                }

                .alert-close:hover {
                    opacity: 1;
                }

                /* ============================================
                   FORM ELEMENTS
                   ============================================ */
                .form-group {
                    margin-bottom: var(--space-6);
                }

                .form-label {
                    display: flex;
                    align-items: center;
                    gap: var(--space-2);
                    font-size: 0.875rem;
                    font-weight: 600;
                    color: var(--sv1-primary-dark);
                    margin-bottom: var(--space-2);
                }

                .form-label i {
                    color: var(--sv1-primary);
                    font-size: 1rem;
                }

                .required {
                    color: var(--sv1-danger);
                    margin-left: var(--space-1);
                }

                .form-control {
                    width: 100%;
                    padding: var(--space-3) var(--space-4);
                    font-size: 1rem;
                    border: 2px solid var(--sv1-border);
                    border-radius: var(--sv1-radius-md);
                    transition: all 0.2s ease;
                    background: white;
                    color: var(--sv1-text-dark);
                }

                .form-control:focus {
                    outline: none;
                    border-color: var(--sv1-primary);
                    box-shadow: 0 0 0 4px var(--sv1-primary-soft);
                }

                .form-control.invalid {
                    border-color: var(--sv1-danger);
                }

                .form-control.valid {
                    border-color: var(--sv1-success);
                }

                .form-hint {
                    font-size: 0.75rem;
                    color: var(--sv1-text-muted);
                    margin-top: var(--space-1);
                    display: flex;
                    align-items: center;
                    gap: var(--space-1);
                }

                .form-hint i {
                    font-size: 0.875rem;
                    color: var(--sv1-primary-light);
                }

                /* ============================================
                   PASSWORD STRENGTH
                   ============================================ */
                .password-strength {
                    margin-top: var(--space-3);
                }

                .strength-bar {
                    height: 4px;
                    background: var(--sv1-border);
                    border-radius: var(--sv1-radius-md);
                    overflow: hidden;
                    margin-bottom: var(--space-2);
                }

                .strength-bar-fill {
                    height: 100%;
                    width: 0;
                    border-radius: var(--sv1-radius-md);
                    transition: all 0.3s ease;
                }

                .strength-bar-fill.weak {
                    width: 33%;
                    background: var(--sv1-danger);
                }

                .strength-bar-fill.medium {
                    width: 66%;
                    background: var(--sv1-warning);
                }

                .strength-bar-fill.strong {
                    width: 100%;
                    background: var(--sv1-success);
                }

                .strength-requirements {
                    display: flex;
                    flex-wrap: wrap;
                    gap: var(--space-3);
                    margin-top: var(--space-2);
                }

                .requirement {
                    font-size: 0.75rem;
                    color: var(--sv1-text-muted);
                    display: flex;
                    align-items: center;
                    gap: var(--space-1);
                }

                .requirement i {
                    font-size: 0.625rem;
                    color: var(--sv1-primary-light);
                }

                .requirement.met {
                    color: var(--sv1-success);
                }

                .requirement.met i {
                    color: var(--sv1-success);
                }

                .password-match-hint {
                    display: none;
                    align-items: center;
                    gap: var(--space-2);
                    font-size: 0.75rem;
                    color: var(--sv1-danger);
                    margin-top: var(--space-1);
                    padding: var(--space-2) var(--space-3);
                    background: var(--sv1-danger-light);
                    border-radius: var(--sv1-radius-md);
                    border-left: 4px solid var(--sv1-danger);
                }

                .password-match-hint i {
                    font-size: 0.875rem;
                }

                /* ============================================
                   TERMS CHECKBOX
                   ============================================ */
                .terms-container {
                    margin: var(--space-8) 0 var(--space-4) 0;
                    padding: var(--space-4) 0;
                    border-top: 1px solid var(--sv1-border);
                    border-bottom: 1px solid var(--sv1-border);
                    background: var(--sv1-primary-soft);
                    transition: all 0.3s ease;
                }

                .checkbox-wrapper {
                    display: flex;
                    align-items: flex-start;
                    gap: var(--space-3);
                    padding: var(--space-2) 0;
                }

                .checkbox-wrapper input[type="checkbox"] {
                    width: 1.25rem;
                    height: 1.25rem;
                    margin-top: 0.125rem;
                    accent-color: var(--sv1-primary);
                    cursor: pointer;
                    flex-shrink: 0;
                }

                .checkbox-wrapper label {
                    font-size: 0.95rem;
                    color: var(--sv1-text-dark);
                    line-height: 1.5;
                    cursor: pointer;
                    flex: 1;
                }

                .terms-link {
                    color: var(--sv1-primary);
                    text-decoration: none;
                    font-weight: 600;
                    border-bottom: 1px dotted var(--sv1-primary-light);
                    transition: all 0.2s ease;
                    cursor: pointer;
                    display: inline-flex;
                    align-items: center;
                    gap: var(--space-1);
                }

                .terms-link:hover {
                    color: var(--sv1-primary-dark);
                    border-bottom-color: var(--sv1-primary-dark);
                }

                .terms-link i {
                    font-size: 0.75rem;
                }

                /* ============================================
                   TERMS CONTENT SECTION (at the bottom)
                   ============================================ */
                .terms-content-section {
                    margin: var(--space-8) 0;
                    padding: var(--space-6);
                    background: white;
                    border: 1px solid var(--sv1-border);
                    border-radius: var(--sv1-radius-xl);
                    scroll-margin-top: var(--space-8);
                    box-shadow: var(--shadow-lg);
                }

                .terms-content-header {
                    display: flex;
                    align-items: center;
                    gap: var(--space-3);
                    margin-bottom: var(--space-6);
                    padding-bottom: var(--space-4);
                    border-bottom: 2px solid var(--sv1-primary-light);
                }

                .terms-content-header i {
                    font-size: 1.5rem;
                    color: var(--sv1-primary);
                    background: var(--sv1-primary-soft);
                    padding: var(--space-2);
                    border-radius: var(--sv1-radius-md);
                }

                .terms-content-header h2 {
                    font-size: 1.25rem;
                    font-weight: 600;
                    color: var(--sv1-primary-dark);
                    margin: 0;
                }

                .terms-content-header p {
                    color: var(--sv1-text-muted);
                    font-size: 0.875rem;
                    margin: var(--space-1) 0 0 0;
                }

                .terms-text {
                    color: var(--sv1-text-dark);
                    line-height: 1.7;
                    font-size: 0.95rem;
                    max-height: 400px;
                    overflow-y: auto;
                    padding-right: var(--space-2);
                }

                .terms-text p {
                    margin-bottom: var(--space-4);
                }

                .terms-text ul, 
                .terms-text ol {
                    margin-bottom: var(--space-4);
                    padding-left: var(--space-5);
                }

                .terms-text li {
                    margin-bottom: var(--space-2);
                }

                .terms-text strong {
                    color: var(--sv1-primary-dark);
                }

                /* Terms scrollbar styling */
                .terms-text::-webkit-scrollbar {
                    width: 6px;
                }

                .terms-text::-webkit-scrollbar-track {
                    background: var(--sv1-primary-soft);
                    border-radius: var(--sv1-radius-md);
                }

                .terms-text::-webkit-scrollbar-thumb {
                    background: var(--sv1-primary-light);
                    border-radius: var(--sv1-radius-md);
                }

                .terms-text::-webkit-scrollbar-thumb:hover {
                    background: var(--sv1-primary);
                }

                .terms-footer-info {
                    margin-top: var(--space-6);
                    padding-top: var(--space-4);
                    border-top: 1px solid var(--sv1-border);
                    display: flex;
                    align-items: center;
                    justify-content: space-between;
                    flex-wrap: wrap;
                    gap: var(--space-2);
                    font-size: 0.8rem;
                    color: var(--sv1-text-muted);
                }

                .terms-version {
                    background: var(--sv1-primary-soft);
                    padding: var(--space-1) var(--space-3);
                    border-radius: 9999px;
                    color: var(--sv1-primary);
                    font-weight: 500;
                }

                .back-to-top {
                    display: inline-flex;
                    align-items: center;
                    gap: var(--space-1);
                    color: var(--sv1-primary);
                    text-decoration: none;
                    font-weight: 500;
                    transition: all 0.2s ease;
                    cursor: pointer;
                    background: none;
                    border: none;
                    font-size: 0.8rem;
                }

                .back-to-top:hover {
                    color: var(--sv1-primary-dark);
                    transform: translateY(-2px);
                }

                /* ============================================
                   BUTTONS - Matching JAMB page
                   ============================================ */
                .btn {
                    display: inline-flex;
                    align-items: center;
                    justify-content: center;
                    gap: var(--space-2);
                    padding: var(--space-3) var(--space-6);
                    font-size: 0.95rem;
                    font-weight: 600;
                    border-radius: var(--sv1-radius-md);
                    border: 2px solid transparent;
                    cursor: pointer;
                    transition: all 0.2s ease;
                    text-decoration: none;
                    letter-spacing: 0.3px;
                    font-family: inherit;
                }

                .btn-primary {
                    background: linear-gradient(135deg, var(--sv1-primary), var(--sv1-primary-dark));
                    color: white;
                    width: 100%;
                    box-shadow: var(--sv1-shadow-primary);
                }

                .btn-primary:hover:not(:disabled) {
                    transform: translateY(-2px);
                    box-shadow: 0 15px 35px rgba(107,78,155,.4);
                }

                .btn-primary:active:not(:disabled) {
                    transform: translateY(0);
                }

                .btn-primary:disabled {
                    opacity: 0.65;
                    cursor: not-allowed;
                }

                .btn-outline {
                    background: white;
                    border: 2px solid var(--sv1-primary);
                    color: var(--sv1-primary);
                    min-width: 100px;
                }

                .btn-outline:hover {
                    background: var(--sv1-primary);
                    color: white;
                    transform: translateY(-2px);
                }

                .btn-success {
                    background: var(--sv1-success);
                    color: white;
                    min-width: 100px;
                }

                .btn-success:hover {
                    background: #0d9488;
                    transform: translateY(-2px);
                    box-shadow: var(--shadow-md);
                }

                .btn-block {
                    width: 100%;
                }

                .btn-lg {
                    padding: var(--space-4) var(--space-8);
                    font-size: 1rem;
                }

                /* Spinner - Matching JAMB page */
                .spinner {
                    display: inline-block;
                    width: 1.25rem;
                    height: 1.25rem;
                    border: 2px solid rgba(255,255,255,0.4);
                    border-radius: 50%;
                    border-top-color: white;
                    animation: spin 0.7s linear infinite;
                    vertical-align: middle;
                }

                @keyframes spin {
                    to { transform: rotate(360deg); }
                }

                /* ============================================
                   DIVIDER
                   ============================================ */
                .divider {
                    display: flex;
                    align-items: center;
                    gap: var(--space-4);
                    margin: var(--space-6) 0;
                    color: var(--sv1-text-muted);
                    font-size: 0.875rem;
                }

                .divider::before,
                .divider::after {
                    content: '';
                    flex: 1;
                    height: 1px;
                    background: linear-gradient(90deg, transparent, var(--sv1-border), transparent);
                }

                /* ============================================
                   LOGIN LINK
                   ============================================ */
                .login-section {
                    text-align: center;
                    margin-top: var(--space-6);
                }

                .login-section p {
                    font-size: 0.875rem;
                    color: var(--sv1-text-muted);
                    margin-bottom: var(--space-3);
                }

                /* ============================================
                   PORTAL CLOSED STATE
                   ============================================ */
                .portal-closed {
                    background: white;
                    border-radius: var(--sv1-radius-xl);
                    padding: var(--space-10) var(--space-6);
                    text-align: center;
                    box-shadow: var(--shadow-xl);
                    border: 1px solid var(--sv1-border);
                }

                .closed-icon {
                    width: 5rem;
                    height: 5rem;
                    background: var(--sv1-warning-light);
                    border-radius: 50%;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    margin: 0 auto var(--space-6);
                    color: var(--sv1-warning);
                    font-size: 2rem;
                }

                .portal-closed h2 {
                    font-size: 1.5rem;
                    color: var(--sv1-text-dark);
                    margin-bottom: var(--space-4);
                }

                .portal-closed p {
                    color: var(--sv1-text-muted);
                    max-width: 400px;
                    margin: 0 auto;
                    line-height: 1.6;
                }

                /* ============================================
                   UTILITIES
                   ============================================ */
                .text-purple {
                    color: var(--sv1-primary);
                }

                .text-success {
                    color: var(--sv1-success);
                }

                .text-error {
                    color: var(--sv1-danger);
                }

                .text-muted {
                    color: var(--sv1-text-muted);
                }

                .sr-only {
                    position: absolute;
                    width: 1px;
                    height: 1px;
                    padding: 0;
                    margin: -1px;
                    overflow: hidden;
                    clip: rect(0, 0, 0, 0);
                    white-space: nowrap;
                    border: 0;
                }

                .mt-6 {
                    margin-top: var(--space-6);
                }

                .mb-4 {
                    margin-bottom: var(--space-4);
                }

                /* Shake animation */
                @keyframes shake {
                    0%, 100% { transform: translateX(0); }
                    25% { transform: translateX(-5px); }
                    75% { transform: translateX(5px); }
                }

                /* ============================================
                   RESPONSIVE ADJUSTMENTS
                   ============================================ */
                @media (max-width: 480px) {
                    body {
                        padding: var(--space-2);
                    }

                    .card-header h1 {
                        font-size: 1.25rem;
                    }

                    .step-number {
                        width: 2rem;
                        height: 2rem;
                        font-size: 0.875rem;
                    }

                    .step-item:not(:last-child)::after {
                        top: 1rem;
                    }

                    .checkbox-wrapper label {
                        font-size: 0.85rem;
                    }

                    .terms-footer-info {
                        flex-direction: column;
                        align-items: flex-start;
                    }

                    .terms-content-section {
                        padding: var(--space-4);
                    }
                }

                @media (min-width: 768px) {
                    .card-body {
                        padding: var(--space-10);
                    }
                }

                @media (min-width: 1024px) {
                    .registration-container {
                        max-width: 800px;
                    }
                }
            </style>
        </head>
        <body>

        <div class="registration-container">

            <!-- Step Indicator -->
            <div class="step-indicator" role="navigation" aria-label="Application progress">
                <div class="step-item active" aria-current="step">
                    <div class="step-number">1</div>
                    <span class="step-label">Account</span>
                </div>
                <div class="step-item">
                    <div class="step-number">2</div>
                    <span class="step-label">JAMB</span>
                </div>
                <div class="step-item">
                    <div class="step-number">3</div>
                    <span class="step-label">Form</span>
                </div>
                <div class="step-item">
                    <div class="step-number">4</div>
                    <span class="step-label">Payment</span>
                </div>
                <div class="step-item">
                    <div class="step-number">5</div>
                    <span class="step-label">Slip</span>
                </div>
            </div>

            <!-- Alert Container -->
            <div id="alertContainer" role="alert" aria-live="polite"></div>

            <?php if ($portal_closed): ?>

                <!-- Portal Closed State -->
                <div class="portal-closed">
                    <div class="closed-icon">
                        <i class="fas fa-door-closed"></i>
                    </div>
                    <h2>Application Portal Closed</h2>
                    <p><?php echo $this->e($portal_message ?: 'The application portal is currently closed. Please check back later for the next admission cycle.'); ?></p>
                </div>

            <?php else: ?>

                <!-- Main Registration Card -->
                <div class="registration-card">

                    <!-- Header -->
                    <div class="card-header">
                        <div class="header-icon">
                            <i class="fas fa-user-plus"></i>
                        </div>
                        <h1>Create your account</h1>
                        <p>Begin your journey with FCT College of Nursing Sciences</p>
                    </div>

                    <!-- Body -->
                    <div class="card-body">

                        <!-- Flash Messages -->
                        <?php if (!empty($_SESSION['flash_error'])): ?>
                            <div class="alert alert-danger">
                                <i class="fas fa-exclamation-circle alert-icon"></i>
                                <div class="alert-content"><?php echo $this->e($_SESSION['flash_error']); ?></div>
                                <button class="alert-close" onclick="this.closest('.alert').remove()" aria-label="Dismiss">&times;</button>
                            </div>
                            <?php unset($_SESSION['flash_error']); ?>
                        <?php endif; ?>

                        <?php if (!empty($_SESSION['flash_success'])): ?>
                            <div class="alert alert-success">
                                <i class="fas fa-check-circle alert-icon"></i>
                                <div class="alert-content"><?php echo $this->e($_SESSION['flash_success']); ?></div>
                                <button class="alert-close" onclick="this.closest('.alert').remove()" aria-label="Dismiss">&times;</button>
                            </div>
                            <?php unset($_SESSION['flash_success']); ?>
                        <?php endif; ?>

                        <!-- Registration Form -->
                        <form method="POST" action="/apply/register" id="registrationForm" novalidate>
                            
                            <!-- ========================================================= -->
                            <!-- 5. Add CSRF token to all forms - FIXED: Using the same token -->
                            <!-- ========================================================= -->
                            <input type="hidden" name="csrf_token" value="<?php echo $this->e($csrf_token); ?>">

                            <!-- Email Field -->
                            <div class="form-group">
                                <label class="form-label" for="email">
                                    <i class="fas fa-envelope"></i>
                                    Email Address <span class="required">*</span>
                                </label>
                                <input type="email" class="form-control" id="email" name="email"
                                       value="<?php echo $this->e($_POST['email'] ?? ''); ?>"
                                       placeholder="you@example.com"
                                       required
                                       autocomplete="email"
                                       aria-describedby="emailHint"
                                       maxlength="255">
                                <div class="form-hint" id="emailHint">
                                    <i class="fas fa-info-circle"></i>
                                    We'll send a verification link to this address
                                </div>
                            </div>

                            <!-- Phone Field -->
                            <div class="form-group">
                                <label class="form-label" for="phone">
                                    <i class="fas fa-phone"></i>
                                    Phone Number <span class="required">*</span>
                                </label>
                                <input type="tel" class="form-control" id="phone" name="phone"
                                       value="<?php echo $this->e($_POST['phone'] ?? ''); ?>"
                                       placeholder="08012345678"
                                       pattern="[0-9]{11}"
                                       maxlength="11"
                                       required
                                       autocomplete="tel"
                                       aria-describedby="phoneHint"
                                       oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                                <div class="form-hint" id="phoneHint">
                                    <i class="fas fa-info-circle"></i>
                                    11-digit Nigerian mobile number
                                </div>
                            </div>

                            <!-- Password Field -->
                            <div class="form-group">
                                <label class="form-label" for="password">
                                    <i class="fas fa-lock"></i>
                                    Password <span class="required">*</span>
                                </label>
                                <input type="password" class="form-control" id="password" name="password"
                                       minlength="8"
                                       maxlength="100"
                                       required
                                       autocomplete="new-password"
                                       aria-describedby="passwordStrength">
                                
                                <div class="password-strength" id="passwordStrength">
                                    <div class="strength-bar">
                                        <div class="strength-bar-fill" id="strengthBar"></div>
                                    </div>
                                    
                                    <div class="strength-requirements">
                                        <span class="requirement" id="req-length">
                                            <i class="fas fa-circle"></i> 8+ characters
                                        </span>
                                        <span class="requirement" id="req-number">
                                            <i class="fas fa-circle"></i> 1 number
                                        </span>
                                        <span class="requirement" id="req-letter">
                                            <i class="fas fa-circle"></i> 1 letter
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <!-- Confirm Password Field -->
                            <div class="form-group">
                                <label class="form-label" for="confirm_password">
                                    <i class="fas fa-lock"></i>
                                    Confirm Password <span class="required">*</span>
                                </label>
                                <input type="password" class="form-control" id="confirm_password" name="confirm_password"
                                       maxlength="100"
                                       required
                                       autocomplete="off"
                                       aria-describedby="passwordMatchHint">
                                <div class="password-match-hint" id="passwordMatchHint">
                                    <i class="fas fa-exclamation-circle"></i>
                                    Passwords do not match
                                </div>
                            </div>

                            <!-- TERMS CHECKBOX - LINKS TO SECTION BELOW -->
                            <div class="terms-container">
                                <div class="checkbox-wrapper">
                                    <input type="checkbox" id="terms" name="terms" required value="1">
                                    <label for="terms">
                                        I agree to the 
                                        <a href="#terms-section" class="terms-link">
                                            Terms and Conditions <i class="fas fa-arrow-down"></i>
                                        </a>
                                        <span class="required">*</span>
                                    </label>
                                </div>
                            </div>

                            <!-- Submit Button -->
                            <button type="submit" class="btn btn-primary btn-lg" id="submitBtn">
                                <span id="btnText">
                                    <i class="fas fa-user-plus"></i>
                                    Create Account
                                </span>
                                <span id="btnSpinner" style="display: none;">
                                    <span class="spinner"></span>
                                    Creating account...
                                </span>
                            </button>

                            <!-- Divider -->
                            <div class="divider">
                                <span>or</span>
                            </div>

                            <!-- Login Link -->
                            <div class="login-section">
                                <p>Already have an account?</p>
                                <a href="/applicant/login" class="btn btn-outline">
                                    <i class="fas fa-sign-in-alt"></i>
                                    Sign In
                                </a>
                            </div>

                        </form>
                    </div>

                    <!-- Footer -->
                    <div class="card-footer">
                        <i class="fas fa-shield-alt"></i>
                        <span>Your information is encrypted and secure</span>
                    </div>

                </div>

                <!-- TERMS SECTION - AT THE BOTTOM (visible on page) -->
                <div id="terms-section" class="terms-content-section">
                    <div class="terms-content-header">
                        <i class="fas fa-file-contract"></i>
                        <div>
                            <h2>Terms and Conditions</h2>
                            <p>Please read carefully before proceeding</p>
                        </div>
                    </div>
                    
                    <?php if (!empty($terms)): ?>
                        <div class="terms-text">
                            <h6 style="color: var(--sv1-primary-dark); margin-bottom: var(--space-4);">
                                <?php echo $this->e($terms['title'] ?? 'Terms and Conditions of Application'); ?>
                            </h6>
                            
                            <?php echo nl2br($this->e($terms['content'] ?? '')); ?>
                        </div>
                        
                        <div class="terms-footer-info">
                            <span class="terms-version">
                                <i class="fas fa-code-branch me-1"></i>
                                Version: <?php echo $this->e($terms['version'] ?? '1.0'); ?>
                            </span>
                            <span>
                                <i class="fas fa-calendar-alt me-1"></i>
                                Effective: <?php echo isset($terms['effective_date']) ? $this->e(date('F j, Y', strtotime($terms['effective_date']))) : 'September 15, 2025'; ?>
                            </span>
                            <button class="back-to-top" onclick="window.scrollTo({top: 0, behavior: 'smooth'}); return false;">
                                <i class="fas fa-arrow-up"></i> Back to Top
                            </button>
                        </div>
                    <?php else: ?>
                        <div class="terms-text">
                            <p><strong>1. Accuracy of Information</strong><br>
                            You confirm that all information provided in your application is true, complete, and accurate. Providing false or misleading information may result in disqualification or revocation of admission.</p>
                            
                            <p><strong>2. Application Fee</strong><br>
                            The application fee is non-refundable. Payment must be made through the official Remita platform only. The college does not accept cash payments.</p>
                            
                            <p><strong>3. Data Privacy</strong><br>
                            Your personal data will be processed in accordance with the Nigeria Data Protection Regulation (NDPR). We will not share your information with third parties without your consent.</p>
                            
                            <p><strong>4. Admission Process</strong><br>
                            Meeting the minimum requirements does not guarantee admission. The selection process is competitive and based on merit, catchment area, and other criteria as determined by the college.</p>
                            
                            <p><strong>5. Communication</strong><br>
                            All official communication will be sent to the email address provided during registration. It is your responsibility to check this email regularly.</p>
                            
                            <p><strong>6. Code of Conduct</strong><br>
                            Applicants must maintain proper conduct throughout the admission process. Any form of examination malpractice or unethical behavior will lead to automatic disqualification.</p>
                            
                            <p><strong>7. Changes to Terms</strong><br>
                            The college reserves the right to modify these terms as necessary. Any changes will be communicated through the official portal.</p>
                        </div>
                        
                        <div class="terms-footer-info">
                            <span class="terms-version">
                                <i class="fas fa-code-branch me-1"></i>
                                Version: 1.0
                            </span>
                            <span>
                                <i class="fas fa-calendar-alt me-1"></i>
                                Effective: September 15, 2025
                            </span>
                            <button class="back-to-top" onclick="window.scrollTo({top: 0, behavior: 'smooth'}); return false;">
                                <i class="fas fa-arrow-up"></i> Back to Top
                            </button>
                        </div>
                    <?php endif; ?>
                </div>

            <?php endif; ?>

        </div>

        <!-- ========================================================= -->
        <!-- 4. Add CSP nonce to all script tags -->
        <!-- ========================================================= -->
        <script nonce="<?php echo $csp_nonce; ?>">
        // ======================================================
        // Registration Page JavaScript with Security Enhancements
        // ======================================================
        
        (function() {
            'use strict';

            // Get CSRF token from meta tag - FIXED: Using the same token
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            // DOM Elements
            const form = document.getElementById('registrationForm');
            const email = document.getElementById('email');
            const phone = document.getElementById('phone');
            const password = document.getElementById('password');
            const confirmPassword = document.getElementById('confirm_password');
            const terms = document.getElementById('terms');
            const submitBtn = document.getElementById('submitBtn');
            const btnText = document.getElementById('btnText');
            const btnSpinner = document.getElementById('btnSpinner');
            const alertContainer = document.getElementById('alertContainer');

            // Password strength elements
            const strengthBar = document.getElementById('strengthBar');
            const reqLength = document.getElementById('req-length');
            const reqNumber = document.getElementById('req-number');
            const reqLetter = document.getElementById('req-letter');
            const matchHint = document.getElementById('passwordMatchHint');

            // Sanitize input to prevent XSS
            function sanitizeInput(input) {
                if (!input) return input;
                return input.replace(/[<>]/g, '').trim();
            }

            // Phone number validation and formatting
            phone.addEventListener('input', function(e) {
                this.value = this.value.replace(/[^0-9]/g, '').slice(0, 11);
            });

            // Password strength checker
            function checkPasswordStrength() {
                const val = password.value;
                const hasLength = val.length >= 8;
                const hasNumber = /\d/.test(val);
                const hasLetter = /[a-zA-Z]/.test(val);

                // Update requirement indicators
                updateRequirement(reqLength, hasLength, '8+ characters');
                updateRequirement(reqNumber, hasNumber, '1 number');
                updateRequirement(reqLetter, hasLetter, '1 letter');

                // Update strength bar
                const score = [hasLength, hasNumber, hasLetter].filter(Boolean).length;
                strengthBar.className = 'strength-bar-fill';
                if (score === 1) strengthBar.classList.add('weak');
                else if (score === 2) strengthBar.classList.add('medium');
                else if (score === 3) strengthBar.classList.add('strong');
            }

            function updateRequirement(element, met, text) {
                element.classList.toggle('met', met);
                element.innerHTML = met 
                    ? `<i class="fas fa-check-circle"></i> ${text}`
                    : `<i class="fas fa-circle"></i> ${text}`;
            }

            // Password match checker
            function checkPasswordMatch() {
                const match = password.value === confirmPassword.value;
                if (confirmPassword.value) {
                    confirmPassword.classList.toggle('valid', match);
                    confirmPassword.classList.toggle('invalid', !match);
                    matchHint.style.display = match ? 'none' : 'flex';
                } else {
                    confirmPassword.classList.remove('valid', 'invalid');
                    matchHint.style.display = 'none';
                }
            }

            // Input validation styling
            function validateField(field) {
                if (!field.value) {
                    field.classList.remove('valid', 'invalid');
                    return;
                }

                let isValid = true;
                
                if (field.id === 'email') {
                    isValid = /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(field.value);
                } else if (field.id === 'phone') {
                    isValid = /^\d{11}$/.test(field.value);
                } else if (field.id === 'password') {
                    isValid = field.value.length >= 8 && /\d/.test(field.value) && /[a-zA-Z]/.test(field.value);
                }

                field.classList.toggle('valid', isValid);
                field.classList.toggle('invalid', !isValid);
            }

            // Event listeners
            password.addEventListener('input', () => {
                checkPasswordStrength();
                validateField(password);
                if (confirmPassword.value) checkPasswordMatch();
            });

            confirmPassword.addEventListener('input', checkPasswordMatch);
            email.addEventListener('input', () => validateField(email));
            phone.addEventListener('input', () => validateField(phone));

            // Show alert function
            function showAlert(message, type = 'danger') {
                const icon = type === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle';
                alertContainer.innerHTML = `
                    <div class="alert alert-${type}">
                        <i class="fas ${icon} alert-icon"></i>
                        <div class="alert-content">${message.replace(/</g, '&lt;').replace(/>/g, '&gt;')}</div>
                        <button class="alert-close" onclick="this.closest('.alert').remove()" aria-label="Dismiss">&times;</button>
                    </div>
                `;

                // Auto-dismiss after 5 seconds
                setTimeout(() => {
                    const alert = alertContainer.querySelector('.alert');
                    if (alert) {
                        alert.style.transition = 'opacity 0.3s';
                        alert.style.opacity = '0';
                        setTimeout(() => alert.remove(), 300);
                    }
                }, 5000);
            }

            // Smooth scroll for terms link
            document.querySelectorAll('a[href^="#"]').forEach(anchor => {
                anchor.addEventListener('click', function (e) {
                    e.preventDefault();
                    const target = document.querySelector(this.getAttribute('href'));
                    if (target) {
                        target.scrollIntoView({ 
                            behavior: 'smooth',
                            block: 'start'
                        });
                    }
                });
            });

            // Rate limiting
            let submitCount = 0;
            const maxSubmits = 3;
            const submitWindow = 60000; // 1 minute

            function checkRateLimit() {
                const now = Date.now();
                const attempts = JSON.parse(sessionStorage.getItem('regAttempts') || '[]');
                
                // Clean old attempts
                const recentAttempts = attempts.filter(t => now - t < submitWindow);
                
                if (recentAttempts.length >= maxSubmits) {
                    showAlert('Too many registration attempts. Please wait a minute.', 'warning');
                    return false;
                }
                
                recentAttempts.push(now);
                sessionStorage.setItem('regAttempts', JSON.stringify(recentAttempts));
                return true;
            }

            // Form submission
            if (form) {
                form.addEventListener('submit', function(e) {
                    e.preventDefault();

                    // Sanitize inputs
                    email.value = sanitizeInput(email.value);
                    phone.value = sanitizeInput(phone.value);

                    // Rate limiting check
                    if (!checkRateLimit()) {
                        return;
                    }

                    // Validation
                    if (!email.value) {
                        showAlert('Please enter your email address');
                        email.focus();
                        return;
                    }

                    if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email.value)) {
                        showAlert('Please enter a valid email address');
                        email.focus();
                        return;
                    }

                    if (email.value.length > 255) {
                        showAlert('Email address is too long');
                        email.focus();
                        return;
                    }

                    if (!phone.value) {
                        showAlert('Please enter your phone number');
                        phone.focus();
                        return;
                    }

                    if (!/^\d{11}$/.test(phone.value)) {
                        showAlert('Phone number must be exactly 11 digits');
                        phone.focus();
                        return;
                    }

                    if (!password.value) {
                        showAlert('Please enter a password');
                        password.focus();
                        return;
                    }

                    if (password.value.length < 8) {
                        showAlert('Password must be at least 8 characters');
                        password.focus();
                        return;
                    }

                    if (password.value.length > 100) {
                        showAlert('Password is too long (maximum 100 characters)');
                        password.focus();
                        return;
                    }

                    if (password.value !== confirmPassword.value) {
                        showAlert('Passwords do not match');
                        confirmPassword.focus();
                        return;
                    }

                    if (!terms.checked) {
                        showAlert('You must accept the Terms and Conditions');
                        // Highlight the terms container
                        document.querySelector('.terms-container').style.animation = 'shake 0.3s ease';
                        setTimeout(() => {
                            document.querySelector('.terms-container').style.animation = '';
                        }, 300);
                        
                        // Scroll to terms section
                        document.getElementById('terms-section').scrollIntoView({ 
                            behavior: 'smooth',
                            block: 'center'
                        });
                        return;
                    }

                    // Show loading state
                    btnText.style.display = 'none';
                    btnSpinner.style.display = 'inline-flex';
                    submitBtn.disabled = true;

                    // Add CSRF token validation timestamp
                    const timestamp = document.createElement('input');
                    timestamp.type = 'hidden';
                    timestamp.name = '_t';
                    timestamp.value = Date.now();
                    this.appendChild(timestamp);

                    // Submit form
                    this.submit();
                });
            }

            // Initialize
            if (password && password.value) checkPasswordStrength();

            // Prevent multiple submissions
            let submitting = false;
            if (form) {
                form.addEventListener('submit', function() {
                    if (submitting) {
                        e.preventDefault();
                        showAlert('Form is already submitting...', 'info');
                        return false;
                    }
                    submitting = true;
                });
            }

            // External link security
            document.querySelectorAll('a[href^="http"]:not([rel*="noopener"])').forEach(link => {
                if (link.hostname !== window.location.hostname) {
                    link.setAttribute('target', '_blank');
                    link.setAttribute('rel', 'noopener noreferrer');
                }
            });

            // Back to top with security
            document.querySelectorAll('.back-to-top').forEach(button => {
                button.addEventListener('click', function(e) {
                    e.preventDefault();
                    window.scrollTo({
                        top: 0,
                        behavior: 'smooth'
                    });
                });
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
$view = new RegistrationView();
$view->render(get_defined_vars());
?>