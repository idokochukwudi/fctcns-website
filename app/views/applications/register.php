<?php
/**
 * Registration View - Step 1
 * REDESIGNED: International standards, fully responsive, accessible with purple accents
 * @package FCTCNS
 */

extract($data ?? []);

if (!function_exists('e')) {
    function e($text) { return htmlspecialchars($text ?? '', ENT_QUOTES, 'UTF-8'); }
}

$csrf_token    = $csrf_token    ?? '';
$terms         = $terms         ?? [];
$portal_closed = $portal_closed ?? false;
$portal_message= $portal_message?? '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <meta name="description" content="Create your account for FCT College of Nursing Sciences admissions">
    <meta name="theme-color" content="#6d28d9">
    <title>Create Account — FCT College of Nursing Sciences</title>

    <!-- Preconnect for performance -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    
    <!-- Modern font stack: Inter (international standard) -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&display=swap" rel="stylesheet">
    
    <!-- Font Awesome 6 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">

    <style>
        /* ============================================
           DESIGN SYSTEM — International Standards
           ============================================ */
        :root {
            /* Purple palette - modern, professional, accessible */
            --purple-50:   #faf5ff;
            --purple-100:  #f3e8ff;
            --purple-200:  #e9d5ff;
            --purple-300:  #d8b4fe;
            --purple-400:  #c084fc;
            --purple-500:  #a855f7;
            --purple-600:  #9333ea;
            --purple-700:  #7e22ce;
            --purple-800:  #6b21a5;
            --purple-900:  #581c87;
            --purple-950:  #3b0764;
            
            /* Neutral palette */
            --neutral-50:  #fafafa;
            --neutral-100: #f5f5f5;
            --neutral-200: #e5e5e5;
            --neutral-300: #d4d4d4;
            --neutral-400: #a3a3a3;
            --neutral-500: #737373;
            --neutral-600: #525252;
            --neutral-700: #404040;
            --neutral-800: #262626;
            --neutral-900: #171717;
            
            /* Semantic colors */
            --success-50:  #f0fdf4;
            --success-500: #22c55e;
            --success-700: #15803d;
            
            --error-50:    #fef2f2;
            --error-500:   #ef4444;
            --error-700:   #b91c1c;
            
            --warning-50:  #fffbeb;
            --warning-500: #f59e0b;
            --warning-700: #b45309;
            
            --info-50:     #eff6ff;
            --info-500:    #3b82f6;
            --info-700:    #1d4ed8;
            
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
            --radius-sm: 0.375rem;   /* 6px */
            --radius-md: 0.5rem;      /* 8px */
            --radius-lg: 0.75rem;     /* 12px */
            --radius-xl: 1rem;        /* 16px */
            --radius-2xl: 1.5rem;     /* 24px */
            --radius-full: 9999px;
            
            /* Shadows */
            --shadow-xs: 0 1px 2px 0 rgb(0 0 0 / 0.05);
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
            background: linear-gradient(135deg, var(--purple-50) 0%, var(--neutral-50) 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: var(--space-4);
            color: var(--neutral-900);
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
            background: var(--neutral-200);
            z-index: 0;
        }

        .step-item.active:not(:last-child)::after,
        .step-item.completed:not(:last-child)::after {
            background: var(--purple-600);
        }

        .step-number {
            width: 2.5rem;
            height: 2.5rem;
            background: var(--neutral-100);
            border: 2px solid var(--neutral-200);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            color: var(--neutral-600);
            margin-bottom: var(--space-2);
            position: relative;
            z-index: 1;
            transition: all 0.2s ease;
        }

        .step-item.active .step-number {
            background: var(--purple-600);
            border-color: var(--purple-600);
            color: white;
            box-shadow: 0 0 0 4px var(--purple-100);
        }

        .step-item.completed .step-number {
            background: var(--success-500);
            border-color: var(--success-500);
            color: white;
        }

        .step-label {
            font-size: 0.75rem;
            font-weight: 500;
            color: var(--neutral-600);
            text-transform: uppercase;
            letter-spacing: 0.05em;
            display: none;
        }

        .step-item.active .step-label {
            color: var(--purple-700);
            font-weight: 600;
        }

        .step-item.completed .step-label {
            color: var(--success-700);
        }

        @media (min-width: 640px) {
            .step-label {
                display: block;
            }
        }

        /* ============================================
           MAIN CARD
           ============================================ */
        .registration-card {
            background: white;
            border-radius: var(--radius-2xl);
            box-shadow: var(--shadow-xl);
            overflow: hidden;
            border: 1px solid var(--neutral-100);
        }

        /* Card Header */
        .card-header {
            background: linear-gradient(135deg, var(--purple-900), var(--purple-700));
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
        }

        .card-header h1 {
            font-size: 1.5rem;
            font-weight: 600;
            margin-bottom: var(--space-2);
            letter-spacing: -0.02em;
        }

        .card-header p {
            font-size: 0.875rem;
            opacity: 0.8;
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
            background: var(--neutral-50);
            border-top: 1px solid var(--neutral-200);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: var(--space-2);
            color: var(--neutral-600);
            font-size: 0.875rem;
        }

        .card-footer i {
            color: var(--purple-400);
        }

        /* ============================================
           ALERTS
           ============================================ */
        .alert {
            padding: var(--space-4);
            border-radius: var(--radius-lg);
            margin-bottom: var(--space-6);
            display: flex;
            align-items: flex-start;
            gap: var(--space-3);
            animation: slideIn 0.3s ease;
            border: 1px solid transparent;
        }

        @keyframes slideIn {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .alert-danger {
            background: var(--error-50);
            border-color: var(--error-500);
            color: var(--error-700);
        }

        .alert-success {
            background: var(--success-50);
            border-color: var(--success-500);
            color: var(--success-700);
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
            font-weight: 500;
            color: var(--neutral-700);
            margin-bottom: var(--space-2);
        }

        .form-label i {
            color: var(--purple-500);
            font-size: 1rem;
        }

        .required {
            color: var(--error-500);
            margin-left: var(--space-1);
        }

        .form-control {
            width: 100%;
            padding: var(--space-3) var(--space-4);
            font-size: 1rem;
            border: 2px solid var(--neutral-200);
            border-radius: var(--radius-lg);
            transition: all 0.2s ease;
            background: white;
            color: var(--neutral-900);
        }

        .form-control:focus {
            outline: none;
            border-color: var(--purple-500);
            box-shadow: 0 0 0 4px var(--purple-100);
        }

        .form-control.invalid {
            border-color: var(--error-500);
        }

        .form-control.valid {
            border-color: var(--success-500);
        }

        .form-hint {
            font-size: 0.75rem;
            color: var(--neutral-500);
            margin-top: var(--space-1);
            display: flex;
            align-items: center;
            gap: var(--space-1);
        }

        .form-hint i {
            font-size: 0.875rem;
            color: var(--neutral-400);
        }

        /* ============================================
           PASSWORD STRENGTH
           ============================================ */
        .password-strength {
            margin-top: var(--space-3);
        }

        .strength-bar {
            height: 4px;
            background: var(--neutral-200);
            border-radius: var(--radius-sm);
            overflow: hidden;
            margin-bottom: var(--space-2);
        }

        .strength-bar-fill {
            height: 100%;
            width: 0;
            border-radius: var(--radius-sm);
            transition: all 0.3s ease;
        }

        .strength-bar-fill.weak {
            width: 33%;
            background: var(--error-500);
        }

        .strength-bar-fill.medium {
            width: 66%;
            background: var(--warning-500);
        }

        .strength-bar-fill.strong {
            width: 100%;
            background: var(--success-500);
        }

        .strength-requirements {
            display: flex;
            flex-wrap: wrap;
            gap: var(--space-3);
            margin-top: var(--space-2);
        }

        .requirement {
            font-size: 0.75rem;
            color: var(--neutral-600);
            display: flex;
            align-items: center;
            gap: var(--space-1);
        }

        .requirement i {
            font-size: 0.625rem;
            color: var(--neutral-400);
        }

        .requirement.met {
            color: var(--success-700);
        }

        .requirement.met i {
            color: var(--success-500);
        }

        .password-match-hint {
            display: none;
            align-items: center;
            gap: var(--space-2);
            font-size: 0.75rem;
            color: var(--error-700);
            margin-top: var(--space-1);
            padding: var(--space-2) var(--space-3);
            background: var(--error-50);
            border-radius: var(--radius-md);
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
            border-top: 1px solid var(--neutral-200);
            border-bottom: 1px solid var(--neutral-200);
            background: var(--neutral-50);
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
            accent-color: var(--purple-600);
            cursor: pointer;
            flex-shrink: 0;
        }

        .checkbox-wrapper label {
            font-size: 0.95rem;
            color: var(--neutral-700);
            line-height: 1.5;
            cursor: pointer;
            flex: 1;
        }

        .terms-link {
            color: var(--purple-700);
            text-decoration: none;
            font-weight: 600;
            border-bottom: 1px dotted var(--purple-400);
            transition: all 0.2s ease;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: var(--space-1);
        }

        .terms-link:hover {
            color: var(--purple-900);
            border-bottom-color: var(--purple-700);
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
            background: var(--neutral-50);
            border: 1px solid var(--neutral-200);
            border-radius: var(--radius-xl);
            scroll-margin-top: var(--space-8);
        }

        .terms-content-header {
            display: flex;
            align-items: center;
            gap: var(--space-3);
            margin-bottom: var(--space-6);
            padding-bottom: var(--space-4);
            border-bottom: 2px solid var(--purple-200);
        }

        .terms-content-header i {
            font-size: 1.5rem;
            color: var(--purple-600);
            background: var(--purple-100);
            padding: var(--space-2);
            border-radius: var(--radius-lg);
        }

        .terms-content-header h2 {
            font-size: 1.25rem;
            font-weight: 600;
            color: var(--purple-900);
            margin: 0;
        }

        .terms-content-header p {
            color: var(--neutral-600);
            font-size: 0.875rem;
            margin: var(--space-1) 0 0 0;
        }

        .terms-text {
            color: var(--neutral-700);
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
            color: var(--purple-800);
        }

        /* Terms scrollbar styling */
        .terms-text::-webkit-scrollbar {
            width: 6px;
        }

        .terms-text::-webkit-scrollbar-track {
            background: var(--neutral-100);
            border-radius: var(--radius-sm);
        }

        .terms-text::-webkit-scrollbar-thumb {
            background: var(--purple-300);
            border-radius: var(--radius-sm);
        }

        .terms-text::-webkit-scrollbar-thumb:hover {
            background: var(--purple-400);
        }

        .terms-footer-info {
            margin-top: var(--space-6);
            padding-top: var(--space-4);
            border-top: 1px solid var(--neutral-200);
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: var(--space-2);
            font-size: 0.8rem;
            color: var(--neutral-500);
        }

        .terms-version {
            background: var(--purple-100);
            padding: var(--space-1) var(--space-3);
            border-radius: var(--radius-full);
            color: var(--purple-700);
            font-weight: 500;
        }

        .back-to-top {
            display: inline-flex;
            align-items: center;
            gap: var(--space-1);
            color: var(--purple-600);
            text-decoration: none;
            font-weight: 500;
            transition: all 0.2s ease;
        }

        .back-to-top:hover {
            color: var(--purple-800);
            transform: translateY(-2px);
        }

        /* ============================================
           BUTTONS - MATCHING DESIGN
           ============================================ */
        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: var(--space-2);
            padding: var(--space-3) var(--space-6);
            font-size: 0.95rem;
            font-weight: 600;
            border-radius: var(--radius-lg);
            border: 2px solid transparent;
            cursor: pointer;
            transition: all 0.2s ease;
            text-decoration: none;
            letter-spacing: 0.3px;
        }

        .btn-primary {
            background: var(--purple-600);
            color: white;
            width: 100%;
            box-shadow: var(--shadow-md);
        }

        .btn-primary:hover:not(:disabled) {
            background: var(--purple-700);
            transform: translateY(-2px);
            box-shadow: var(--shadow-lg);
        }

        .btn-primary:active:not(:disabled) {
            transform: translateY(0);
        }

        .btn-primary:disabled {
            opacity: 0.6;
            cursor: not-allowed;
        }

        .btn-outline {
            background: white;
            border: 2px solid var(--neutral-200);
            color: var(--neutral-700);
            min-width: 100px;
        }

        .btn-outline:hover {
            border-color: var(--purple-500);
            color: var(--purple-700);
            background: var(--purple-50);
        }

        .btn-success {
            background: var(--success-600);
            color: white;
            border: 2px solid var(--success-600);
            min-width: 100px;
        }

        .btn-success:hover {
            background: var(--success-700);
            border-color: var(--success-700);
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

        /* Spinner */
        .spinner {
            display: inline-block;
            width: 1.25rem;
            height: 1.25rem;
            border: 2px solid rgba(255,255,255,0.3);
            border-radius: 50%;
            border-top-color: white;
            animation: spin 0.6s linear infinite;
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
            color: var(--neutral-400);
            font-size: 0.875rem;
        }

        .divider::before,
        .divider::after {
            content: '';
            flex: 1;
            height: 1px;
            background: linear-gradient(90deg, transparent, var(--neutral-200), transparent);
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
            color: var(--neutral-600);
            margin-bottom: var(--space-3);
        }

        /* ============================================
           PORTAL CLOSED STATE
           ============================================ */
        .portal-closed {
            background: white;
            border-radius: var(--radius-2xl);
            padding: var(--space-10) var(--space-6);
            text-align: center;
            box-shadow: var(--shadow-xl);
            border: 1px solid var(--neutral-200);
        }

        .closed-icon {
            width: 5rem;
            height: 5rem;
            background: var(--warning-50);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto var(--space-6);
            color: var(--warning-600);
            font-size: 2rem;
        }

        .portal-closed h2 {
            font-size: 1.5rem;
            color: var(--neutral-900);
            margin-bottom: var(--space-4);
        }

        .portal-closed p {
            color: var(--neutral-600);
            max-width: 400px;
            margin: 0 auto;
            line-height: 1.6;
        }

        /* ============================================
           UTILITIES
           ============================================ */
        .text-purple {
            color: var(--purple-600);
        }

        .text-success {
            color: var(--success-500);
        }

        .text-error {
            color: var(--error-500);
        }

        .text-muted {
            color: var(--neutral-500);
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
            <p><?php echo e($portal_message ?: 'The application portal is currently closed. Please check back later for the next admission cycle.'); ?></p>
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
                        <div class="alert-content"><?php echo e($_SESSION['flash_error']); ?></div>
                        <button class="alert-close" onclick="this.closest('.alert').remove()" aria-label="Dismiss">&times;</button>
                    </div>
                    <?php unset($_SESSION['flash_error']); ?>
                <?php endif; ?>

                <?php if (!empty($_SESSION['flash_success'])): ?>
                    <div class="alert alert-success">
                        <i class="fas fa-check-circle alert-icon"></i>
                        <div class="alert-content"><?php echo e($_SESSION['flash_success']); ?></div>
                        <button class="alert-close" onclick="this.closest('.alert').remove()" aria-label="Dismiss">&times;</button>
                    </div>
                    <?php unset($_SESSION['flash_success']); ?>
                <?php endif; ?>

                <!-- Registration Form -->
                <form method="POST" action="/apply/register" id="registrationForm" novalidate>
                    <input type="hidden" name="csrf_token" value="<?php echo e($csrf_token); ?>">

                    <!-- Email Field -->
                    <div class="form-group">
                        <label class="form-label" for="email">
                            <i class="fas fa-envelope"></i>
                            Email Address <span class="required">*</span>
                        </label>
                        <input type="email" class="form-control" id="email" name="email"
                               value="<?php echo e($_POST['email'] ?? ''); ?>"
                               placeholder="you@example.com"
                               required
                               autocomplete="email"
                               aria-describedby="emailHint">
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
                               value="<?php echo e($_POST['phone'] ?? ''); ?>"
                               placeholder="08012345678"
                               pattern="[0-9]{11}"
                               maxlength="11"
                               required
                               autocomplete="tel"
                               aria-describedby="phoneHint">
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
                            <input type="checkbox" id="terms" name="terms" required>
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
                    <h6 style="color: var(--purple-800); margin-bottom: var(--space-4);">
                        <?php echo e($terms['title'] ?? 'Terms and Conditions of Application'); ?>
                    </h6>
                    
                    <?php echo nl2br(e($terms['content'] ?? '')); ?>
                </div>
                
                <div class="terms-footer-info">
                    <span class="terms-version">
                        <i class="fas fa-code-branch me-1"></i>
                        Version: <?php echo e($terms['version'] ?? '1.0'); ?>
                    </span>
                    <span>
                        <i class="fas fa-calendar-alt me-1"></i>
                        Effective: <?php echo isset($terms['effective_date']) ? date('F j, Y', strtotime($terms['effective_date'])) : 'September 15, 2025'; ?>
                    </span>
                    <a href="#" class="back-to-top" onclick="window.scrollTo({top: 0, behavior: 'smooth'}); return false;">
                        <i class="fas fa-arrow-up"></i> Back to Top
                    </a>
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
                    <a href="#" class="back-to-top" onclick="window.scrollTo({top: 0, behavior: 'smooth'}); return false;">
                        <i class="fas fa-arrow-up"></i> Back to Top
                    </a>
                </div>
            <?php endif; ?>
        </div>

    <?php endif; ?>

</div>

<!-- Form Validation Script -->
<script>
(function() {
    'use strict';

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

    // Phone number formatting
    phone.addEventListener('input', function(e) {
        this.value = this.value.replace(/\D/g, '').slice(0, 11);
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
                <div class="alert-content">${message}</div>
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

    // Form submission
    form.addEventListener('submit', function(e) {
        e.preventDefault();

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

        // Submit form
        this.submit();
    });

    // Add shake animation
    const style = document.createElement('style');
    style.textContent = `
        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            25% { transform: translateX(-5px); }
            75% { transform: translateX(5px); }
        }
    `;
    document.head.appendChild(style);

    // Initialize
    if (password.value) checkPasswordStrength();

})();
</script>

</body>
</html>