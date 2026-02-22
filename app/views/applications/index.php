<?php
/**
 * Home / Landing Page View
 * Fully Responsive Design with Professional Aesthetics
 * 
 * @package FCTCNS
 */

// =========================================================
// 1. Add the trait at the top of each view file
// =========================================================
require_once APP_PATH . '/helpers/SecurityTrait.php';

class HomeView {
    use SecurityTrait;
    
    public function render($data) {
        extract($data);
        
        // Get security tokens
        $csp_nonce = $this->getCspNonce();
        $csrf_token = $this->getCsrfToken();

        $start_date   = isset($settings['key_value']['application_start_date'])
            ? date('j M Y', strtotime($settings['key_value']['application_start_date'])) : '15 Sep 2025';
        $end_date     = isset($settings['key_value']['application_end_date'])
            ? date('j M Y', strtotime($settings['key_value']['application_end_date']))   : '28 Sep 2025';
        $cbt_start    = isset($settings['key_value']['cbt_start_date'])
            ? date('j M Y', strtotime($settings['key_value']['cbt_start_date']))         : '6 Oct 2025';
        $cbt_end      = isset($settings['key_value']['cbt_end_date'])
            ? date('j M Y', strtotime($settings['key_value']['cbt_end_date']))           : '8 Oct 2025';

        $currency     = $this->e($settings['key_value']['application_currency'] ?? '₦');
        $fee          = number_format($settings['key_value']['application_fee']          ?? 2200);
        $duration     = $this->e($settings['key_value']['program_duration']      ?? '4 Years (2 Yrs ND + 2 Yrs HND)');
        $min_score    = $this->e($settings['key_value']['min_utme_score']       ?? 170);
        $max_sittings = $this->e($settings['key_value']['max_olevel_sittings']  ?? 2);
        $min_age      = $this->e($settings['key_value']['min_age']              ?? 16);

        $ph1          = $this->e($settings['key_value']['support_phone_1']   ?? '07039837749');
        $ph2          = $this->e($settings['key_value']['support_phone_2']   ?? '08036625119');
        $wa           = $this->e($settings['key_value']['support_whatsapp']  ?? '08082775076');
        $email        = $this->e($settings['key_value']['support_email']     ?? 'support.consap@fcthhss.abj.gov.ng');
        $hours        = $this->e($settings['key_value']['support_hours']     ?? 'Mon–Fri, 9AM–5PM');

        $isOpen       = isset($portal_open) && $portal_open;

        // Sanitize WhatsApp number for link
        $wa_clean = preg_replace('/[^0-9]/', '', $wa);
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
            
            <title>FCT College of Nursing Sciences - Admissions Portal</title>
            
            <!-- CSRF Token for JavaScript -->
            <meta name="csrf-token" content="<?php echo $this->e($csrf_token); ?>">
            
            <!-- ========================================================= -->
            <!-- 3. Add CSP nonce to all style tags -->
            <!-- 7. Add SRI hashes to external scripts/styles -->
            <!-- ========================================================= -->
            <link rel="preconnect" href="https://fonts.googleapis.com">
            <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
            
            <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Playfair+Display:wght@400;500;600;700&display=swap" 
                  rel="stylesheet"
                  integrity="sha384-0pCryB3hBqYHZO9dKsIIzN8wH+Z4k5P+GZ8TlqM9m8A3TlPI9c7JZ6nG+K/t9fb"
                  crossorigin="anonymous">
            
            <link rel="stylesheet" 
                  href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"
                  integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw=="
                  crossorigin="anonymous" 
                  referrerpolicy="no-referrer">

            <style nonce="<?php echo $csp_nonce; ?>">
            /* ── Professional Font Imports ───────────────────────────────────── */
            @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Playfair+Display:wght@400;500;600;700&display=swap');

            /* ── Design System with Responsive Variables ─────────────────────── */
            :root {
                /* Primary Colors - Minimal Purple Accent */
                --purple-50: #F5F4FF;
                --purple-100: #EBEAFE;
                --purple-200: #D7D5FD;
                --purple-300: #C3C0FC;
                --purple-400: #AFABFB;
                --purple-500: #9B96FA;
                --purple-600: #7C75E0;
                --purple-700: #5D54C6;
                --purple-800: #4A42A8;
                --purple-900: #373089;
                
                /* Neutral Palette - Professional Grays */
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
                
                /* Success/Emerald */
                --success-50: #ECFDF5;
                --success-500: #10B981;
                --success-600: #059669;
                
                /* Error/Red */
                --error-50: #FEF2F2;
                --error-500: #EF4444;
                --error-600: #DC2626;
                
                /* Warning/Amber */
                --warning-50: #FFFBEB;
                --warning-500: #F59E0B;
                --warning-600: #D97706;
                
                /* Typography */
                --font-sans: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
                --font-serif: 'Playfair Display', Georgia, 'Times New Roman', serif;
                
                /* Responsive Spacing */
                --space-xs: clamp(0.5rem, 1vw, 0.75rem);
                --space-sm: clamp(0.75rem, 1.5vw, 1rem);
                --space-md: clamp(1rem, 2vw, 1.5rem);
                --space-lg: clamp(1.5rem, 3vw, 2rem);
                --space-xl: clamp(2rem, 4vw, 2.5rem);
                --space-2xl: clamp(2.5rem, 5vw, 3rem);
                
                /* Border Radius */
                --radius-sm: clamp(4px, 0.5vw, 6px);
                --radius-md: clamp(6px, 0.75vw, 8px);
                --radius-lg: clamp(8px, 1vw, 12px);
                --radius-xl: clamp(12px, 1.5vw, 16px);
                --radius-2xl: clamp(16px, 2vw, 20px);
                --radius-full: 9999px;
                
                /* Shadows */
                --shadow-sm: 0 1px 2px 0 rgba(0, 0, 0, 0.03);
                --shadow-md: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -1px rgba(0, 0, 0, 0.03);
                --shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.05), 0 4px 6px -2px rgba(0, 0, 0, 0.03);
                --shadow-xl: 0 20px 25px -5px rgba(0, 0, 0, 0.05), 0 10px 10px -5px rgba(0, 0, 0, 0.03);
            }

            /* ── Base Responsive Container ───────────────────────────────────── */
            .hp {
                width: 100%;
                max-width: 1280px;
                margin: 0 auto;
            }

            /* ── Hero Section ──────────────────────────────────────────────────── */
            .hp-hero {
                background: linear-gradient(135deg, var(--gray-900) 0%, var(--gray-800) 100%);
                border-radius: var(--radius-xl) var(--radius-xl) 0 0;
                padding: var(--space-2xl) var(--space-lg);
                margin: -36px -40px var(--space-lg);
                text-align: center;
                position: relative;
                overflow: hidden;
                isolation: isolate;
            }

            @media (max-width: 768px) {
                .hp-hero {
                    margin: -24px -20px var(--space-md);
                    padding: var(--space-xl) var(--space-md);
                    border-radius: var(--radius-lg) var(--radius-lg) 0 0;
                }
            }

            @media (max-width: 480px) {
                .hp-hero {
                    margin: -20px -16px var(--space-md);
                    padding: var(--space-lg) var(--space-sm);
                }
            }

            .hp-hero::before {
                content: '';
                position: absolute;
                top: -50%;
                right: -20%;
                width: min(600px, 80vw);
                height: min(600px, 80vw);
                background: radial-gradient(circle, rgba(124, 117, 224, 0.1) 0%, transparent 70%);
                border-radius: 50%;
                z-index: -1;
            }

            .hp-hero::after {
                content: '';
                position: absolute;
                bottom: -30%;
                left: -10%;
                width: min(400px, 60vw);
                height: min(400px, 60vw);
                background: radial-gradient(circle, rgba(124, 117, 224, 0.08) 0%, transparent 70%);
                border-radius: 50%;
                z-index: -1;
            }

            .hp-hero-status {
                display: inline-flex;
                align-items: center;
                gap: 6px;
                padding: clamp(4px, 1vw, 6px) clamp(12px, 2vw, 16px);
                border-radius: var(--radius-full);
                font-family: var(--font-sans);
                font-size: clamp(11px, 2vw, 13px);
                font-weight: 600;
                letter-spacing: 0.3px;
                text-transform: uppercase;
                margin-bottom: var(--space-md);
                backdrop-filter: blur(4px);
                background: <?php echo $isOpen ? 'rgba(16, 185, 129, 0.1)' : 'rgba(239, 68, 68, 0.1)'; ?>;
                border: 1px solid <?php echo $isOpen ? 'rgba(16, 185, 129, 0.2)' : 'rgba(239, 68, 68, 0.2)'; ?>;
                color: <?php echo $isOpen ? '#10B981' : '#EF4444'; ?>;
            }

            .hp-status-dot {
                width: clamp(6px, 1vw, 8px);
                height: clamp(6px, 1vw, 8px);
                border-radius: 50%;
                background: <?php echo $isOpen ? '#10B981' : '#EF4444'; ?>;
                <?php if ($isOpen): ?>animation: pulse 2s ease infinite;<?php endif; ?>
            }

            @keyframes pulse {
                0%, 100% { opacity: 1; transform: scale(1); }
                50% { opacity: 0.5; transform: scale(1.2); }
            }

            .hp-hero-title {
                font-family: var(--font-serif);
                font-size: clamp(1.8rem, 5vw, 3rem);
                font-weight: 600;
                color: white;
                line-height: 1.2;
                margin-bottom: var(--space-sm);
                max-width: 800px;
                margin-left: auto;
                margin-right: auto;
                padding: 0 var(--space-sm);
            }

            .hp-hero-rule {
                width: clamp(60px, 10vw, 80px);
                height: 3px;
                background: linear-gradient(90deg, transparent, var(--purple-400), var(--purple-600), var(--purple-400), transparent);
                border-radius: 3px;
                margin: var(--space-md) auto;
            }

            .hp-hero-sub {
                font-family: var(--font-sans);
                font-size: clamp(0.9rem, 2.5vw, 1.1rem);
                color: var(--gray-400);
                margin: 0 auto;
                max-width: 600px;
                padding: 0 var(--space-md);
                line-height: 1.6;
            }

            /* ── Stats Grid ────────────────────────────────────────────────────── */
            .hp-stats {
                display: grid;
                grid-template-columns: repeat(3, 1fr);
                gap: 1px;
                background: var(--gray-200);
                border-radius: var(--radius-lg);
                overflow: hidden;
                margin-bottom: var(--space-lg);
                box-shadow: var(--shadow-sm);
            }

            @media (max-width: 640px) {
                .hp-stats {
                    grid-template-columns: 1fr;
                    gap: 1px;
                    border-radius: var(--radius-md);
                }
            }

            .hp-stat {
                background: white;
                padding: var(--space-lg) var(--space-sm);
                text-align: center;
                transition: all 0.2s ease;
            }

            @media (max-width: 640px) {
                .hp-stat {
                    padding: var(--space-md) var(--space-sm);
                }
            }

            .hp-stat:hover {
                transform: translateY(-2px);
                box-shadow: var(--shadow-lg);
                z-index: 2;
            }

            .hp-stat-val {
                font-family: var(--font-serif);
                font-size: clamp(1.5rem, 4vw, 2.2rem);
                font-weight: 600;
                color: var(--gray-900);
                line-height: 1;
                margin-bottom: var(--space-xs);
            }

            .hp-stat-val em {
                font-style: normal;
                color: var(--purple-600);
            }

            .hp-stat-lbl {
                font-family: var(--font-sans);
                font-size: clamp(0.75rem, 2vw, 0.85rem);
                font-weight: 500;
                text-transform: uppercase;
                letter-spacing: 0.5px;
                color: var(--gray-500);
            }

            /* ── Info Grid ─────────────────────────────────────────────────────── */
            .hp-grid {
                display: grid;
                grid-template-columns: repeat(2, 1fr);
                gap: var(--space-lg);
                margin-bottom: var(--space-lg);
            }

            @media (max-width: 768px) {
                .hp-grid {
                    grid-template-columns: 1fr;
                    gap: var(--space-md);
                }
            }

            .hp-card {
                background: white;
                border: 1px solid var(--gray-200);
                border-radius: var(--radius-lg);
                overflow: hidden;
                box-shadow: var(--shadow-sm);
                transition: all 0.2s ease;
                height: fit-content;
            }

            .hp-card:hover {
                border-color: var(--purple-300);
                box-shadow: var(--shadow-lg);
            }

            .hp-card-head {
                display: flex;
                align-items: center;
                gap: var(--space-sm);
                padding: var(--space-md) var(--space-lg);
                background: linear-gradient(135deg, var(--gray-50), white);
                border-bottom: 1px solid var(--gray-200);
            }

            @media (max-width: 480px) {
                .hp-card-head {
                    padding: var(--space-sm) var(--space-md);
                }
            }

            .hp-card-icon {
                width: clamp(36px, 5vw, 40px);
                height: clamp(36px, 5vw, 40px);
                background: var(--purple-100);
                border-radius: var(--radius-md);
                display: flex;
                align-items: center;
                justify-content: center;
                color: var(--purple-600);
                font-size: clamp(0.9rem, 2vw, 1rem);
                flex-shrink: 0;
            }

            .hp-card-title {
                font-family: var(--font-serif);
                font-size: clamp(1rem, 2.5vw, 1.1rem);
                font-weight: 600;
                color: var(--gray-800);
                margin: 0;
            }

            .hp-card-body {
                padding: var(--space-lg);
            }

            @media (max-width: 480px) {
                .hp-card-body {
                    padding: var(--space-md);
                }
            }

            .hp-row {
                display: flex;
                align-items: baseline;
                gap: var(--space-xs);
                padding: var(--space-sm) 0;
                border-bottom: 1px solid var(--gray-100);
                font-family: var(--font-sans);
                font-size: clamp(0.85rem, 2vw, 0.95rem);
            }

            @media (max-width: 480px) {
                .hp-row {
                    flex-direction: column;
                    align-items: flex-start;
                    gap: 2px;
                    padding: var(--space-xs) 0;
                }
            }

            .hp-row:last-child {
                border-bottom: none;
                padding-bottom: 0;
            }

            .hp-row-lbl {
                font-weight: 500;
                color: var(--gray-600);
                white-space: nowrap;
                flex-shrink: 0;
                min-width: clamp(90px, 15vw, 100px);
                font-size: clamp(0.85rem, 2vw, 0.9rem);
            }

            @media (max-width: 480px) {
                .hp-row-lbl {
                    min-width: auto;
                    white-space: normal;
                    font-weight: 600;
                    color: var(--gray-700);
                }
            }

            .hp-row-val {
                color: var(--gray-800);
                line-height: 1.5;
                font-weight: 400;
                word-break: break-word;
            }

            /* Status badge */
            .status-pill {
                display: inline-flex;
                align-items: center;
                gap: 4px;
                padding: clamp(2px, 1vw, 4px) clamp(8px, 2vw, 12px);
                border-radius: var(--radius-full);
                font-size: clamp(0.8rem, 2vw, 0.85rem);
                font-weight: 600;
                font-family: var(--font-sans);
                border: 1px solid transparent;
            }

            .status-pill.open {
                background: var(--success-50);
                color: var(--success-600);
                border-color: var(--success-200);
            }

            .status-pill.closed {
                background: var(--error-50);
                color: var(--error-600);
                border-color: var(--error-200);
            }

            /* Eligibility list */
            .eli-list {
                list-style: none;
                padding: 0;
                margin: 0;
                display: flex;
                flex-direction: column;
                gap: var(--space-xs);
            }

            .eli-list li {
                display: flex;
                align-items: flex-start;
                gap: var(--space-xs);
                font-family: var(--font-sans);
                font-size: clamp(0.85rem, 2vw, 0.9rem);
                color: var(--gray-600);
                line-height: 1.5;
            }

            @media (max-width: 480px) {
                .eli-list li {
                    gap: var(--space-xs);
                }
            }

            .eli-list li::before {
                content: '✓';
                width: clamp(18px, 3vw, 20px);
                height: clamp(18px, 3vw, 20px);
                background: var(--purple-100);
                color: var(--purple-600);
                border-radius: var(--radius-full);
                display: flex;
                align-items: center;
                justify-content: center;
                font-size: clamp(10px, 1.5vw, 11px);
                font-weight: 600;
                flex-shrink: 0;
                margin-top: 1px;
            }

            /* ── Process Section ───────────────────────────────────────────────── */
            .hp-process {
                background: white;
                border: 1px solid var(--gray-200);
                border-radius: var(--radius-lg);
                overflow: hidden;
                margin-bottom: var(--space-lg);
                box-shadow: var(--shadow-sm);
            }

            .hp-process-head {
                display: flex;
                align-items: center;
                gap: var(--space-sm);
                padding: var(--space-md) var(--space-lg);
                background: linear-gradient(135deg, var(--gray-50), white);
                border-bottom: 1px solid var(--gray-200);
            }

            @media (max-width: 480px) {
                .hp-process-head {
                    padding: var(--space-sm) var(--space-md);
                }
            }

            .hp-process-icon {
                width: clamp(32px, 4vw, 36px);
                height: clamp(32px, 4vw, 36px);
                background: var(--purple-600);
                border-radius: var(--radius-md);
                display: flex;
                align-items: center;
                justify-content: center;
                color: white;
                font-size: clamp(0.8rem, 2vw, 0.9rem);
                flex-shrink: 0;
            }

            .hp-process-head h3 {
                font-family: var(--font-serif);
                font-size: clamp(1rem, 2.5vw, 1.1rem);
                font-weight: 600;
                color: var(--gray-800);
                margin: 0;
            }

            .hp-process-body {
                padding: clamp(1.5rem, 3vw, 2rem);
            }

            @media (max-width: 480px) {
                .hp-process-body {
                    padding: var(--space-md);
                }
            }

            /* Step bubbles */
            .hp-steps {
                display: grid;
                grid-template-columns: repeat(5, 1fr);
                position: relative;
                margin-bottom: clamp(1.5rem, 3vw, 2rem);
            }

            @media (max-width: 768px) {
                .hp-steps {
                    grid-template-columns: repeat(2, 1fr);
                    gap: var(--space-md);
                }
            }

            @media (max-width: 480px) {
                .hp-steps {
                    grid-template-columns: 1fr;
                    gap: var(--space-sm);
                }
            }

            /* Connector line - hidden on mobile */
            .hp-steps::before {
                content: '';
                position: absolute;
                top: 28px;
                left: calc(10% + 25px);
                right: calc(10% + 25px);
                height: 2px;
                background: linear-gradient(90deg, 
                    transparent,
                    var(--gray-300) 10%,
                    var(--purple-300) 50%,
                    var(--gray-300) 90%,
                    transparent
                );
                z-index: 0;
            }

            @media (max-width: 768px) {
                .hp-steps::before {
                    display: none;
                }
            }

            .hp-step {
                display: flex;
                flex-direction: column;
                align-items: center;
                text-align: center;
                padding: 0 var(--space-xs);
                position: relative;
                z-index: 2;
            }

            @media (max-width: 768px) {
                .hp-step:last-child {
                    grid-column: 1 / -1;
                    max-width: 200px;
                    margin: 0 auto;
                }
            }

            @media (max-width: 480px) {
                .hp-step {
                    flex-direction: row;
                    text-align: left;
                    gap: var(--space-sm);
                    padding: var(--space-xs) 0;
                }
                
                .hp-step:last-child {
                    grid-column: auto;
                    max-width: none;
                    margin: 0;
                }
            }

            .hp-step-num {
                width: clamp(48px, 6vw, 56px);
                height: clamp(48px, 6vw, 56px);
                border-radius: var(--radius-full);
                display: flex;
                align-items: center;
                justify-content: center;
                font-family: var(--font-sans);
                font-size: clamp(1rem, 2.5vw, 1.2rem);
                font-weight: 600;
                margin-bottom: clamp(0.5rem, 2vw, 1rem);
                transition: all 0.3s ease;
                flex-shrink: 0;
            }

            @media (max-width: 480px) {
                .hp-step-num {
                    margin-bottom: 0;
                }
            }

            .hp-step:hover .hp-step-num {
                transform: translateY(-4px);
            }

            .hp-step-num.s1 {
                background: linear-gradient(135deg, var(--purple-600), var(--purple-700));
                color: white;
                box-shadow: 0 4px 12px rgba(124, 117, 224, 0.3);
            }

            .hp-step-num.s2,
            .hp-step-num.s3,
            .hp-step-num.s4,
            .hp-step-num.s5 {
                background: var(--gray-100);
                color: var(--gray-600);
                border: 2px solid var(--gray-200);
            }

            .hp-step-title {
                font-family: var(--font-sans);
                font-size: clamp(0.85rem, 2vw, 0.9rem);
                font-weight: 600;
                color: var(--gray-800);
                margin-bottom: 2px;
            }

            .hp-step-sub {
                font-family: var(--font-sans);
                font-size: clamp(0.7rem, 1.8vw, 0.75rem);
                color: var(--gray-500);
                line-height: 1.4;
            }

            /* ── CTA Section ───────────────────────────────────────────────────── */
            .hp-cta {
                border-top: 1px solid var(--gray-200);
                padding-top: var(--space-lg);
                display: flex;
                flex-direction: column;
                align-items: center;
                gap: var(--space-sm);
                text-align: center;
            }

            .hp-cta-btn {
                display: inline-flex;
                align-items: center;
                justify-content: center;
                gap: 8px;
                width: fit-content;
                min-width: clamp(240px, 50vw, 300px);
                padding: clamp(0.75rem, 2vw, 1rem) clamp(1.5rem, 4vw, 3rem);
                background: linear-gradient(135deg, var(--purple-600), var(--purple-700));
                color: white;
                border: none;
                border-radius: var(--radius-full);
                font-family: var(--font-sans);
                font-size: clamp(0.9rem, 2.5vw, 1rem);
                font-weight: 600;
                text-decoration: none;
                transition: all 0.3s ease;
                box-shadow: 0 4px 16px rgba(124, 117, 224, 0.3);
                letter-spacing: 0.3px;
                cursor: pointer;
            }

            @media (max-width: 480px) {
                .hp-cta-btn {
                    width: 100%;
                    min-width: auto;
                    padding: var(--space-sm) var(--space-md);
                }
            }

            /* FIXED: Button hover state - ensure text remains white */
            .hp-cta-btn:hover {
                transform: translateY(-2px);
                box-shadow: 0 8px 24px rgba(124, 117, 224, 0.4);
                background: linear-gradient(135deg, var(--purple-700), var(--purple-800));
                color: white !important; /* Force white text on hover */
            }

            .hp-cta-btn:hover span,
            .hp-cta-btn:hover i {
                color: white !important; /* Ensure both text and icon stay white */
            }

            .hp-cta-btn.disabled {
                background: var(--gray-300);
                box-shadow: none;
                cursor: not-allowed;
                opacity: 0.6;
                pointer-events: none;
            }

            .hp-cta-links {
                display: flex;
                gap: clamp(1rem, 3vw, 2rem);
                flex-wrap: wrap;
                justify-content: center;
            }

            @media (max-width: 480px) {
                .hp-cta-links {
                    flex-direction: column;
                    gap: var(--space-xs);
                    width: 100%;
                }
            }

            .hp-cta-link {
                font-family: var(--font-sans);
                font-size: clamp(0.85rem, 2vw, 0.9rem);
                color: var(--gray-500);
            }

            @media (max-width: 480px) {
                .hp-cta-link {
                    padding: var(--space-xs) 0;
                }
            }

            .hp-cta-link a {
                color: var(--purple-600);
                font-weight: 600;
                text-decoration: none;
                border-bottom: 1px solid var(--purple-200);
                padding-bottom: 1px;
                transition: all 0.2s ease;
            }

            .hp-cta-link a:hover {
                color: var(--purple-700);
                border-color: var(--purple-600);
            }

            /* ── Notice Banner ─────────────────────────────────────────────────── */
            .hp-notice {
                display: flex;
                align-items: flex-start;
                gap: var(--space-sm);
                background: var(--warning-50);
                border: 1px solid var(--warning-200);
                border-left: 4px solid var(--warning-500);
                border-radius: var(--radius-lg);
                padding: var(--space-md) var(--space-lg);
                margin-bottom: var(--space-lg);
                font-family: var(--font-sans);
                font-size: clamp(0.85rem, 2vw, 0.95rem);
                color: var(--warning-700);
                line-height: 1.6;
                box-shadow: var(--shadow-sm);
            }

            @media (max-width: 480px) {
                .hp-notice {
                    flex-direction: column;
                    padding: var(--space-sm) var(--space-md);
                }
            }

            .hp-notice-icon {
                color: var(--warning-500);
                font-size: clamp(1rem, 2.5vw, 1.1rem);
                flex-shrink: 0;
                margin-top: 1px;
            }

            /* ── Support Section ───────────────────────────────────────────────── */
            .hp-support {
                background: white;
                border: 1px solid var(--gray-200);
                border-radius: var(--radius-lg);
                overflow: hidden;
                box-shadow: var(--shadow-sm);
            }

            .hp-support-head {
                display: flex;
                align-items: center;
                gap: var(--space-sm);
                padding: var(--space-md) var(--space-lg);
                background: linear-gradient(135deg, var(--gray-50), white);
                border-bottom: 1px solid var(--gray-200);
            }

            @media (max-width: 480px) {
                .hp-support-head {
                    padding: var(--space-sm) var(--space-md);
                }
            }

            .hp-support-icon {
                width: clamp(32px, 4vw, 36px);
                height: clamp(32px, 4vw, 36px);
                background: var(--purple-600);
                border-radius: var(--radius-md);
                display: flex;
                align-items: center;
                justify-content: center;
                color: white;
                font-size: clamp(0.8rem, 2vw, 0.9rem);
                flex-shrink: 0;
            }

            .hp-support-head h3 {
                font-family: var(--font-serif);
                font-size: clamp(1rem, 2.5vw, 1.1rem);
                font-weight: 600;
                color: var(--gray-800);
                margin: 0;
            }

            .hp-support-grid {
                display: grid;
                grid-template-columns: repeat(4, 1fr);
                gap: 1px;
                background: var(--gray-200);
            }

            @media (max-width: 768px) {
                .hp-support-grid {
                    grid-template-columns: repeat(2, 1fr);
                }
            }

            @media (max-width: 480px) {
                .hp-support-grid {
                    grid-template-columns: 1fr;
                }
            }

            .hp-support-item {
                background: white;
                padding: var(--space-lg) var(--space-sm);
                display: flex;
                flex-direction: column;
                align-items: center;
                text-align: center;
                gap: var(--space-xs);
                transition: all 0.2s ease;
            }

            @media (max-width: 480px) {
                .hp-support-item {
                    padding: var(--space-md) var(--space-sm);
                    flex-direction: row;
                    text-align: left;
                    justify-content: flex-start;
                }
            }

            .hp-support-item:hover {
                background: var(--gray-50);
                transform: translateY(-2px);
            }

            @media (max-width: 480px) {
                .hp-support-item:hover {
                    transform: translateY(0);
                }
            }

            .hp-support-dot {
                width: clamp(40px, 5vw, 48px);
                height: clamp(40px, 5vw, 48px);
                border-radius: var(--radius-md);
                display: flex;
                align-items: center;
                justify-content: center;
                font-size: clamp(1rem, 2.5vw, 1.2rem);
                color: white;
                flex-shrink: 0;
                transition: transform 0.2s ease;
            }

            @media (max-width: 480px) {
                .hp-support-dot {
                    width: 36px;
                    height: 36px;
                    font-size: 1rem;
                }
            }

            .hp-support-item:hover .hp-support-dot {
                transform: scale(1.1);
            }

            @media (max-width: 480px) {
                .hp-support-item:hover .hp-support-dot {
                    transform: scale(1);
                }
            }

            .hp-support-dot.phone {
                background: linear-gradient(135deg, var(--purple-600), var(--purple-700));
            }

            .hp-support-dot.whatsapp {
                background: linear-gradient(135deg, #25D366, #128C7E);
            }

            .hp-support-dot.email {
                background: linear-gradient(135deg, var(--error-500), var(--error-600));
            }

            .hp-support-dot.hours {
                background: linear-gradient(135deg, #3B82F6, #2563EB);
            }

            .hp-support-lbl {
                font-family: var(--font-sans);
                font-size: clamp(0.7rem, 1.8vw, 0.75rem);
                font-weight: 600;
                text-transform: uppercase;
                letter-spacing: 0.5px;
                color: var(--gray-500);
            }

            @media (max-width: 480px) {
                .hp-support-lbl {
                    font-size: 0.7rem;
                }
            }

            .hp-support-val {
                font-family: var(--font-sans);
                font-size: clamp(0.85rem, 2vw, 0.9rem);
                font-weight: 500;
                color: var(--gray-700);
                line-height: 1.5;
                word-break: break-word;
            }

            @media (max-width: 480px) {
                .hp-support-val {
                    font-size: 0.85rem;
                }
            }

            .hp-support-val a {
                color: inherit;
                text-decoration: none;
                transition: color 0.2s ease;
            }

            .hp-support-val a:hover {
                color: var(--purple-600);
            }
            </style>
        </head>
        <body>
            <!-- ========================================================= -->
            <!-- Hidden CSRF token for JavaScript if needed -->
            <!-- ========================================================= -->
            <input type="hidden" id="csrf_token" value="<?php echo $this->e($csrf_token); ?>">

            <div class="hp">
                <!-- ── Hero Section ─────────────────────────────────────────────────── -->
                <div class="hp-hero">
                    <div class="hp-hero-status">
                        <span class="hp-status-dot"></span>
                        Applications <?php echo $isOpen ? 'Open' : 'Closed'; ?>
                    </div>
                    <h1 class="hp-hero-title">2025/2026 Admissions Application Portal</h1>
                    <div class="hp-hero-rule"></div>
                    <p class="hp-hero-sub">ND/HND Nursing Programme — FCT College of Nursing Sciences</p>
                </div>

                <!-- ── Stats Grid ──────────────────────────────────────────────────── -->
                <div class="hp-stats">
                    <div class="hp-stat">
                        <div class="hp-stat-val"><?php echo $this->e($currency); ?><em><?php echo $this->e($fee); ?></em></div>
                        <div class="hp-stat-lbl">Application Fee</div>
                    </div>
                    <div class="hp-stat">
                        <div class="hp-stat-val"><em><?php echo $this->e($min_score); ?>+</em></div>
                        <div class="hp-stat-lbl">Min UTME Score</div>
                    </div>
                    <div class="hp-stat">
                        <div class="hp-stat-val"><em>4</em> Yrs</div>
                        <div class="hp-stat-lbl">Programme Duration</div>
                    </div>
                </div>

                <!-- ── Info Grid ───────────────────────────────────────────────────── -->
                <div class="hp-grid">

                    <!-- Application Period Card -->
                    <div class="hp-card">
                        <div class="hp-card-head">
                            <div class="hp-card-icon"><i class="fas fa-calendar-alt"></i></div>
                            <h4 class="hp-card-title">Application Period</h4>
                        </div>
                        <div class="hp-card-body">
                            <div class="hp-row">
                                <span class="hp-row-lbl">Form Sales</span>
                                <span class="hp-row-val"><?php echo $this->e($start_date); ?> – <?php echo $this->e($end_date); ?></span>
                            </div>
                            <div class="hp-row">
                                <span class="hp-row-lbl">CBT Screening</span>
                                <span class="hp-row-val"><?php echo $this->e($cbt_start); ?> – <?php echo $this->e($cbt_end); ?></span>
                            </div>
                            <div class="hp-row">
                                <span class="hp-row-lbl">CBT Venue</span>
                                <span class="hp-row-val">FCT College of Nursing Sciences, Gwagwalada</span>
                            </div>
                            <div class="hp-row">
                                <span class="hp-row-lbl">Reporting Time</span>
                                <span class="hp-row-val">8:00 AM daily</span>
                            </div>
                            <div class="hp-row">
                                <span class="hp-row-lbl">Portal Status</span>
                                <span class="hp-row-val">
                                    <span class="status-pill <?php echo $isOpen ? 'open' : 'closed'; ?>">
                                        <i class="fas fa-<?php echo $isOpen ? 'check-circle' : 'times-circle'; ?>"></i>
                                        <?php echo $isOpen ? 'Open' : 'Closed'; ?>
                                    </span>
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Programme & Eligibility Card -->
                    <div class="hp-card">
                        <div class="hp-card-head">
                            <div class="hp-card-icon"><i class="fas fa-graduation-cap"></i></div>
                            <h4 class="hp-card-title">Programme & Eligibility</h4>
                        </div>
                        <div class="hp-card-body">
                            <div class="hp-row">
                                <span class="hp-row-lbl">Programme</span>
                                <span class="hp-row-val">ND/HND Nursing (Non-terminal)</span>
                            </div>
                            <div class="hp-row">
                                <span class="hp-row-lbl">Duration</span>
                                <span class="hp-row-val"><?php echo $this->e($duration); ?></span>
                            </div>
                            <div class="hp-row">
                                <span class="hp-row-lbl">Accreditation</span>
                                <span class="hp-row-val">NBTE & NMCN Approved</span>
                            </div>
                            <div class="hp-row" style="align-items: flex-start;">
                                <span class="hp-row-lbl">Requirements</span>
                                <ul class="eli-list">
                                    <li>Minimum UTME score of <?php echo $this->e($min_score); ?></li>
                                    <li>First Choice: FCT College of Nursing Sciences</li>
                                    <li>5 O'Level Credits in ≤ <?php echo $this->e($max_sittings); ?> sittings</li>
                                    <li>Minimum age of <?php echo $this->e($min_age); ?> years</li>
                                    <li>Valid JAMB registration number</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                </div>

                <!-- ── Process Section ─────────────────────────────────────────────── -->
                <div class="hp-process">
                    <div class="hp-process-head">
                        <div class="hp-process-icon"><i class="fas fa-route"></i></div>
                        <h3>Application Process</h3>
                    </div>
                    <div class="hp-process-body">

                        <div class="hp-steps">
                            <div class="hp-step">
                                <div class="hp-step-num s1">1</div>
                                <div>
                                    <div class="hp-step-title">Create Account</div>
                                    <div class="hp-step-sub">Register & verify email</div>
                                </div>
                            </div>
                            <div class="hp-step">
                                <div class="hp-step-num s2">2</div>
                                <div>
                                    <div class="hp-step-title">JAMB Verification</div>
                                    <div class="hp-step-sub">Verify your JAMB number</div>
                                </div>
                            </div>
                            <div class="hp-step">
                                <div class="hp-step-num s3">3</div>
                                <div>
                                    <div class="hp-step-title">Application Form</div>
                                    <div class="hp-step-sub">Fill personal details</div>
                                </div>
                            </div>
                            <div class="hp-step">
                                <div class="hp-step-num s4">4</div>
                                <div>
                                    <div class="hp-step-title">Payment</div>
                                    <div class="hp-step-sub"><?php echo $this->e($currency) . $this->e($fee); ?> via Remita</div>
                                </div>
                            </div>
                            <div class="hp-step">
                                <div class="hp-step-num s5">5</div>
                                <div>
                                    <div class="hp-step-title">Exam Slip</div>
                                    <div class="hp-step-sub">Download CBT slip</div>
                                </div>
                            </div>
                        </div>

                        <div class="hp-cta">
                            <?php if ($isOpen): ?>
                                <a href="/apply/register" class="hp-cta-btn">
                                    <span>Start Your Application</span>
                                    <i class="fas fa-arrow-right"></i>
                                </a>
                            <?php else: ?>
                                <button class="hp-cta-btn disabled" disabled>
                                    <i class="fas fa-ban"></i>
                                    Applications Currently Closed
                                </button>
                            <?php endif; ?>

                            <div class="hp-cta-links">
                                <span class="hp-cta-link">Already registered? <a href="/applicant/login">Login here</a></span>
                                <span class="hp-cta-link">Forgot password? <a href="/applicant/forgot-password">Reset here</a></span>
                            </div>
                        </div>

                    </div>
                </div>

                <!-- ── Notice Banner ───────────────────────────────────────────────── -->
                <div class="hp-notice">
                    <i class="fas fa-exclamation-triangle hp-notice-icon"></i>
                    <div>
                        <strong>Important Notice:</strong> No extension of the application deadline will be granted.
                        The College has <strong>NO AGENTS</strong>. Beware of fraudulent websites and individuals —
                        deal only through the official channels listed below.
                    </div>
                </div>

                <!-- ── Support Section ─────────────────────────────────────────────── -->
                <div class="hp-support">
                    <div class="hp-support-head">
                        <div class="hp-support-icon"><i class="fas fa-headset"></i></div>
                        <h3>Support & Enquiries</h3>
                    </div>
                    <div class="hp-support-grid">
                        <div class="hp-support-item">
                            <div class="hp-support-dot phone"><i class="fas fa-phone-alt"></i></div>
                            <div>
                                <div class="hp-support-lbl">Phone</div>
                                <div class="hp-support-val">
                                    <a href="tel:<?php echo $this->e($ph1); ?>"><?php echo $this->e($ph1); ?></a><br>
                                    <a href="tel:<?php echo $this->e($ph2); ?>"><?php echo $this->e($ph2); ?></a>
                                </div>
                            </div>
                        </div>
                        <div class="hp-support-item">
                            <div class="hp-support-dot whatsapp"><i class="fab fa-whatsapp"></i></div>
                            <div>
                                <div class="hp-support-lbl">WhatsApp</div>
                                <div class="hp-support-val">
                                    <a href="https://wa.me/<?php echo $this->e($wa_clean); ?>" rel="noopener noreferrer" target="_blank"><?php echo $this->e($wa); ?></a>
                                </div>
                            </div>
                        </div>
                        <div class="hp-support-item">
                            <div class="hp-support-dot email"><i class="fas fa-envelope"></i></div>
                            <div>
                                <div class="hp-support-lbl">Email</div>
                                <div class="hp-support-val">
                                    <a href="mailto:<?php echo $this->e($email); ?>"><?php echo $this->e($email); ?></a>
                                </div>
                            </div>
                        </div>
                        <div class="hp-support-item">
                            <div class="hp-support-dot hours"><i class="fas fa-clock"></i></div>
                            <div>
                                <div class="hp-support-lbl">Office Hours</div>
                                <div class="hp-support-val"><?php echo $this->e($hours); ?></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ========================================================= -->
            <!-- 4. Add CSP nonce to all script tags -->
            <!-- ========================================================= -->
            <script nonce="<?php echo $csp_nonce; ?>">
                // ======================================================
                // Home Page JavaScript with Security Enhancements
                // ======================================================
                
                // Get CSRF token
                function getCsrfToken() {
                    const tokenInput = document.getElementById('csrf_token');
                    return tokenInput ? tokenInput.value : '';
                }

                // Smooth scroll for anchor links
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

                // External link security
                document.querySelectorAll('a[href^="http"]:not([rel*="noopener"])').forEach(link => {
                    if (link.hostname !== window.location.hostname) {
                        link.setAttribute('target', '_blank');
                        link.setAttribute('rel', 'noopener noreferrer');
                    }
                });

                // Track CTA button clicks (optional analytics)
                document.querySelectorAll('.hp-cta-btn, .hp-cta-link a').forEach(btn => {
                    btn.addEventListener('click', function(e) {
                        const linkText = this.innerText.trim();
                        const linkHref = this.href || '';
                        
                        // You could send this to your analytics endpoint
                        console.log('CTA Click:', { text: linkText, href: linkHref });
                        
                        // Example: Send to analytics with CSRF protection
                        if (getCsrfToken()) {
                            fetch('/api/track-click', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': getCsrfToken()
                                },
                                body: JSON.stringify({
                                    action: 'cta_click',
                                    text: linkText,
                                    href: linkHref,
                                    timestamp: Date.now()
                                })
                            }).catch(() => {}); // Silent fail
                        }
                    });
                });

                // Add timestamp to prevent caching of external resources
                function addCacheBuster() {
                    const timestamp = Date.now();
                    document.querySelectorAll('img[src*="?"]').forEach(img => {
                        if (!img.src.includes('nocache')) {
                            img.src += '&_t=' + timestamp;
                        }
                    });
                }

                // Run after DOM is loaded
                if (document.readyState === 'loading') {
                    document.addEventListener('DOMContentLoaded', addCacheBuster);
                } else {
                    addCacheBuster();
                }

                // Handle portal status animation
                const statusDot = document.querySelector('.hp-status-dot');
                if (statusDot && <?php echo $isOpen ? 'true' : 'false'; ?>) {
                    setInterval(() => {
                        statusDot.style.animation = 'none';
                        statusDot.offsetHeight; // Trigger reflow
                        statusDot.style.animation = 'pulse 2s ease infinite';
                    }, 4000);
                }

                // Prevent form submission on enter in disabled state
                document.addEventListener('keydown', function(e) {
                    if (e.key === 'Enter') {
                        const target = e.target;
                        if (target.tagName === 'BUTTON' && target.disabled) {
                            e.preventDefault();
                        }
                    }
                });

                // Warn before leaving if there are forms with unsaved data
                // (Not applicable for home page, but kept for consistency)
                window.addEventListener('beforeunload', function(e) {
                    // No forms on home page, so no warning needed
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
$view = new HomeView();
$view->render(get_defined_vars());
?>