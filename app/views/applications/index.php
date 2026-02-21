<?php
/**
 * Home / Landing Page View - International Standard Design
 * Modern, responsive, and accessible application portal landing page
 */

$start_date   = isset($settings['key_value']['application_start_date'])
    ? date('j M Y', strtotime($settings['key_value']['application_start_date'])) : '15 Sep 2025';
$end_date     = isset($settings['key_value']['application_end_date'])
    ? date('j M Y', strtotime($settings['key_value']['application_end_date']))   : '28 Sep 2025';
$cbt_start    = isset($settings['key_value']['cbt_start_date'])
    ? date('j M Y', strtotime($settings['key_value']['cbt_start_date']))         : '6 Oct 2025';
$cbt_end      = isset($settings['key_value']['cbt_end_date'])
    ? date('j M Y', strtotime($settings['key_value']['cbt_end_date']))           : '8 Oct 2025';

$currency     = htmlspecialchars($settings['key_value']['application_currency'] ?? '₦');
$fee          = number_format($settings['key_value']['application_fee']          ?? 2200);
$duration     = htmlspecialchars($settings['key_value']['program_duration']      ?? '4 Years (2 Yrs ND + 2 Yrs HND)');
$min_score    = $settings['key_value']['min_utme_score']       ?? 170;
$max_sittings = $settings['key_value']['max_olevel_sittings']  ?? 2;
$min_age      = $settings['key_value']['min_age']              ?? 16;

$ph1          = htmlspecialchars($settings['key_value']['support_phone_1']   ?? '07039837749');
$ph2          = htmlspecialchars($settings['key_value']['support_phone_2']   ?? '08036625119');
$wa           = htmlspecialchars($settings['key_value']['support_whatsapp']  ?? '08082775076');
$email        = htmlspecialchars($settings['key_value']['support_email']     ?? 'support.consap@fcthhss.abj.gov.ng');
$hours        = htmlspecialchars($settings['key_value']['support_hours']     ?? 'Mon–Fri, 9AM–5PM');

$isOpen       = isset($portal_open) && $portal_open;
?>

<style>
/* Modern CSS Reset & Variables */
:root {
    /* Primary Colors - Professional Purple/Blue */
    --primary-50: #f5f3ff;
    --primary-100: #ede9fe;
    --primary-200: #ddd6fe;
    --primary-300: #c4b5fd;
    --primary-400: #a78bfa;
    --primary-500: #8b5cf6;
    --primary-600: #7c3aed;
    --primary-700: #6d28d9;
    --primary-800: #5b21b6;
    --primary-900: #4c1d95;
    
    /* Success Colors */
    --success-50: #ecfdf5;
    --success-100: #d1fae5;
    --success-200: #a7f3d0;
    --success-300: #6ee7b7;
    --success-400: #34d399;
    --success-500: #10b981;
    --success-600: #059669;
    --success-700: #047857;
    --success-800: #065f46;
    --success-900: #064e3b;
    
    /* Warning Colors */
    --warning-50: #fffbeb;
    --warning-100: #fef3c7;
    --warning-200: #fde68a;
    --warning-300: #fcd34d;
    --warning-400: #fbbf24;
    --warning-500: #f59e0b;
    --warning-600: #d97706;
    --warning-700: #b45309;
    --warning-800: #92400e;
    --warning-900: #78350f;
    
    /* Error Colors */
    --error-50: #fef2f2;
    --error-100: #fee2e2;
    --error-200: #fecaca;
    --error-300: #fca5a5;
    --error-400: #f87171;
    --error-500: #ef4444;
    --error-600: #dc2626;
    --error-700: #b91c1c;
    --error-800: #991b1b;
    --error-900: #7f1d1d;
    
    /* Neutral Colors */
    --neutral-50: #f9fafb;
    --neutral-100: #f3f4f6;
    --neutral-200: #e5e7eb;
    --neutral-300: #d1d5db;
    --neutral-400: #9ca3af;
    --neutral-500: #6b7280;
    --neutral-600: #4b5563;
    --neutral-700: #374151;
    --neutral-800: #1f2937;
    --neutral-900: #111827;
    
    /* Spacing */
    --space-1: 0.25rem;
    --space-2: 0.5rem;
    --space-3: 0.75rem;
    --space-4: 1rem;
    --space-5: 1.25rem;
    --space-6: 1.5rem;
    --space-8: 2rem;
    --space-10: 2.5rem;
    --space-12: 3rem;
    --space-16: 4rem;
    
    /* Typography */
    --font-sans: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
    --font-serif: Georgia, 'Times New Roman', serif;
    
    /* Shadows */
    --shadow-sm: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
    --shadow-md: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
    --shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
    --shadow-xl: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
    --shadow-2xl: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
    
    /* Border Radius */
    --radius-sm: 0.375rem;
    --radius-md: 0.5rem;
    --radius-lg: 0.75rem;
    --radius-xl: 1rem;
    --radius-2xl: 1.5rem;
    --radius-full: 9999px;
}

/* Base Styles */
.landing-modern {
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 var(--space-4);
    font-family: var(--font-sans);
    color: var(--neutral-900);
    animation: fadeIn 0.5s ease-out;
}

@keyframes fadeIn {
    from { opacity: 0; transform: translateY(10px); }
    to { opacity: 1; transform: translateY(0); }
}

/* Hero Section */
.hero-modern {
    background: linear-gradient(145deg, var(--primary-900), var(--primary-700));
    border-radius: var(--radius-2xl);
    padding: clamp(2rem, 5vw, 3.5rem) clamp(1.5rem, 4vw, 2.5rem);
    margin-bottom: var(--space-6);
    position: relative;
    overflow: hidden;
    isolation: isolate;
}

.hero-modern::before {
    content: '';
    position: absolute;
    inset: 0;
    background: radial-gradient(circle at 30% 30%, rgba(255,255,255,0.15) 0%, transparent 70%);
    z-index: -1;
}

.hero-modern::after {
    content: '';
    position: absolute;
    inset: 0;
    background-image: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M30 5L55 20L30 35L5 20L30 5Z' fill='rgba(255,255,255,0.03)'/%3E%3C/svg%3E");
    opacity: 0.5;
    z-index: -1;
}

.status-badge-modern {
    display: inline-flex;
    align-items: center;
    gap: var(--space-2);
    padding: var(--space-2) var(--space-4);
    border-radius: var(--radius-full);
    font-size: 0.85rem;
    font-weight: 600;
    margin-bottom: var(--space-4);
    backdrop-filter: blur(4px);
}

.status-badge-modern.open {
    background: rgba(16, 185, 129, 0.15);
    border: 1px solid rgba(16, 185, 129, 0.3);
    color: var(--success-300);
}

.status-badge-modern.closed {
    background: rgba(239, 68, 68, 0.15);
    border: 1px solid rgba(239, 68, 68, 0.3);
    color: var(--error-300);
}

.status-dot {
    width: 8px;
    height: 8px;
    border-radius: var(--radius-full);
}

.status-dot.open {
    background: var(--success-500);
    box-shadow: 0 0 12px var(--success-500);
    animation: pulse 2s infinite;
}

.status-dot.closed {
    background: var(--error-500);
}

@keyframes pulse {
    0%, 100% { opacity: 1; transform: scale(1); }
    50% { opacity: 0.6; transform: scale(1.2); }
}

.hero-modern h1 {
    font-family: var(--font-serif);
    font-size: clamp(1.8rem, 5vw, 3rem);
    font-weight: 700;
    color: white;
    line-height: 1.2;
    margin-bottom: var(--space-2);
    max-width: 800px;
}

.hero-modern .hero-subtitle {
    font-size: clamp(1rem, 2vw, 1.2rem);
    color: rgba(255, 255, 255, 0.8);
    max-width: 600px;
    margin: 0 auto;
}

/* Stats Grid */
.stats-grid-modern {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: var(--space-4);
    margin-bottom: var(--space-8);
}

.stat-card-modern {
    background: white;
    border: 1px solid var(--neutral-200);
    border-radius: var(--radius-xl);
    padding: var(--space-6);
    text-align: center;
    box-shadow: var(--shadow-sm);
    transition: all 0.3s ease;
    position: relative;
    overflow: hidden;
}

.stat-card-modern:hover {
    transform: translateY(-4px);
    box-shadow: var(--shadow-lg);
    border-color: var(--primary-300);
}

.stat-card-modern::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 4px;
    background: linear-gradient(90deg, var(--primary-400), var(--primary-600));
    transform: scaleX(0);
    transition: transform 0.3s ease;
}

.stat-card-modern:hover::before {
    transform: scaleX(1);
}

.stat-value {
    font-family: var(--font-serif);
    font-size: 2.5rem;
    font-weight: 700;
    color: var(--primary-700);
    line-height: 1;
    margin-bottom: var(--space-2);
}

.stat-label {
    font-size: 0.9rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    color: var(--neutral-500);
}

/* Content Grid */
.content-grid-modern {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: var(--space-6);
    margin-bottom: var(--space-8);
}

.card-modern {
    background: white;
    border: 1px solid var(--neutral-200);
    border-radius: var(--radius-xl);
    overflow: hidden;
    box-shadow: var(--shadow-sm);
    transition: all 0.3s ease;
}

.card-modern:hover {
    box-shadow: var(--shadow-lg);
    border-color: var(--primary-300);
}

.card-header-modern {
    padding: var(--space-5) var(--space-6);
    background: linear-gradient(145deg, var(--primary-50), white);
    border-bottom: 1px solid var(--neutral-200);
    display: flex;
    align-items: center;
    gap: var(--space-3);
}

.card-icon-modern {
    width: 40px;
    height: 40px;
    background: var(--primary-100);
    border-radius: var(--radius-lg);
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--primary-700);
    font-size: 1.2rem;
}

.card-header-modern h3 {
    font-family: var(--font-serif);
    font-size: 1.2rem;
    font-weight: 600;
    color: var(--neutral-900);
    margin: 0;
}

.card-body-modern {
    padding: var(--space-6);
}

.info-row-modern {
    display: flex;
    align-items: baseline;
    gap: var(--space-4);
    padding: var(--space-3) 0;
    border-bottom: 1px solid var(--neutral-100);
}

.info-row-modern:last-child {
    border-bottom: none;
}

.info-label {
    min-width: 120px;
    font-size: 0.9rem;
    font-weight: 600;
    color: var(--neutral-600);
}

.info-value {
    flex: 1;
    font-size: 0.95rem;
    color: var(--neutral-900);
    line-height: 1.5;
}

/* Requirements List */
.requirements-list-modern {
    list-style: none;
    padding: 0;
    margin: 0;
    display: flex;
    flex-direction: column;
    gap: var(--space-3);
}

.requirements-list-modern li {
    display: flex;
    align-items: flex-start;
    gap: var(--space-3);
    font-size: 0.95rem;
    color: var(--neutral-700);
    line-height: 1.5;
}

.requirement-check {
    width: 20px;
    height: 20px;
    background: var(--success-100);
    border-radius: var(--radius-full);
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--success-600);
    font-size: 0.7rem;
    flex-shrink: 0;
    margin-top: 2px;
}

/* Process Section */
.process-section-modern {
    background: white;
    border: 1px solid var(--neutral-200);
    border-radius: var(--radius-xl);
    overflow: hidden;
    margin-bottom: var(--space-8);
    box-shadow: var(--shadow-sm);
}

.process-header-modern {
    padding: var(--space-5) var(--space-6);
    background: linear-gradient(145deg, var(--primary-50), white);
    border-bottom: 1px solid var(--neutral-200);
    display: flex;
    align-items: center;
    gap: var(--space-3);
}

.process-icon-modern {
    width: 40px;
    height: 40px;
    background: var(--primary-100);
    border-radius: var(--radius-lg);
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--primary-700);
    font-size: 1.2rem;
}

.process-header-modern h3 {
    font-family: var(--font-serif);
    font-size: 1.2rem;
    font-weight: 600;
    color: var(--neutral-900);
    margin: 0;
}

.process-body-modern {
    padding: var(--space-8);
}

.steps-grid-modern {
    display: grid;
    grid-template-columns: repeat(5, 1fr);
    gap: var(--space-4);
    margin-bottom: var(--space-8);
    position: relative;
}

.steps-grid-modern::before {
    content: '';
    position: absolute;
    top: 30px;
    left: calc(10% + 20px);
    right: calc(10% + 20px);
    height: 2px;
    background: linear-gradient(90deg, var(--primary-200), var(--primary-400), var(--primary-200));
    z-index: 0;
}

.step-item-modern {
    text-align: center;
    position: relative;
    z-index: 1;
}

.step-number-modern {
    width: 60px;
    height: 60px;
    margin: 0 auto var(--space-3);
    border-radius: var(--radius-full);
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 700;
    font-size: 1.2rem;
    transition: all 0.3s ease;
    position: relative;
}

.step-number-modern.active {
    background: linear-gradient(145deg, var(--primary-600), var(--primary-700));
    color: white;
    box-shadow: 0 4px 15px var(--primary-400);
}

.step-number-modern:not(.active) {
    background: var(--neutral-100);
    color: var(--neutral-500);
    border: 2px solid var(--neutral-200);
}

.step-item-modern:hover .step-number-modern {
    transform: translateY(-4px);
}

.step-item-modern h4 {
    font-size: 1rem;
    font-weight: 600;
    color: var(--neutral-900);
    margin: 0 0 var(--space-1);
}

.step-item-modern p {
    font-size: 0.85rem;
    color: var(--neutral-500);
    margin: 0;
    line-height: 1.4;
}

/* CTA Section */
.cta-section-modern {
    text-align: center;
    padding-top: var(--space-6);
    border-top: 1px solid var(--neutral-200);
}

.btn-modern {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: var(--space-3);
    padding: var(--space-4) var(--space-8);
    border-radius: var(--radius-xl);
    font-size: 1rem;
    font-weight: 600;
    text-decoration: none;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    cursor: pointer;
    border: none;
    min-width: 240px;
}

.btn-modern-primary {
    background: linear-gradient(145deg, var(--primary-600), var(--primary-700));
    color: white;
    box-shadow: 0 4px 15px var(--primary-400);
    margin-bottom: var(--space-4);
}

.btn-modern-primary:hover {
    transform: translateY(-4px);
    box-shadow: 0 8px 25px var(--primary-500);
    color: white;
    text-decoration: none;
}

.btn-modern-primary.disabled {
    background: var(--neutral-300);
    box-shadow: none;
    cursor: not-allowed;
    opacity: 0.7;
}

.btn-modern-primary.disabled:hover {
    transform: none;
    box-shadow: none;
}

.cta-links-modern {
    display: flex;
    gap: var(--space-8);
    justify-content: center;
    flex-wrap: wrap;
}

.cta-link-modern {
    font-size: 0.9rem;
    color: var(--neutral-500);
}

.cta-link-modern a {
    color: var(--primary-600);
    text-decoration: none;
    font-weight: 600;
    margin-left: var(--space-1);
    transition: color 0.2s ease;
}

.cta-link-modern a:hover {
    color: var(--primary-700);
    text-decoration: underline;
}

/* Notice Banner */
.notice-banner-modern {
    background: var(--warning-50);
    border: 1px solid var(--warning-200);
    border-left: 4px solid var(--warning-500);
    border-radius: var(--radius-lg);
    padding: var(--space-5) var(--space-6);
    margin-bottom: var(--space-8);
    display: flex;
    align-items: flex-start;
    gap: var(--space-4);
}

.notice-icon-modern {
    width: 32px;
    height: 32px;
    background: var(--warning-100);
    border-radius: var(--radius-full);
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--warning-600);
    font-size: 1rem;
    flex-shrink: 0;
}

.notice-banner-modern p {
    font-size: 0.95rem;
    color: var(--warning-900);
    margin: 0;
    line-height: 1.6;
}

.notice-banner-modern strong {
    font-weight: 700;
    color: var(--warning-800);
}

/* Support Section */
.support-section-modern {
    background: white;
    border: 1px solid var(--neutral-200);
    border-radius: var(--radius-xl);
    overflow: hidden;
    box-shadow: var(--shadow-sm);
}

.support-header-modern {
    padding: var(--space-5) var(--space-6);
    background: linear-gradient(145deg, var(--primary-50), white);
    border-bottom: 1px solid var(--neutral-200);
    display: flex;
    align-items: center;
    gap: var(--space-3);
}

.support-icon-modern {
    width: 40px;
    height: 40px;
    background: var(--primary-100);
    border-radius: var(--radius-lg);
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--primary-700);
    font-size: 1.2rem;
}

.support-header-modern h3 {
    font-family: var(--font-serif);
    font-size: 1.2rem;
    font-weight: 600;
    color: var(--neutral-900);
    margin: 0;
}

.support-grid-modern {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 1px;
    background: var(--neutral-200);
}

.support-item-modern {
    background: white;
    padding: var(--space-6) var(--space-4);
    text-align: center;
    transition: all 0.3s ease;
}

.support-item-modern:hover {
    background: var(--primary-50);
    transform: translateY(-2px);
}

.support-item-icon {
    width: 56px;
    height: 56px;
    border-radius: var(--radius-xl);
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto var(--space-4);
    font-size: 1.4rem;
    color: white;
    transition: all 0.3s ease;
}

.support-item-modern:hover .support-item-icon {
    transform: scale(1.1) rotate(5deg);
}

.support-item-icon.phone { background: linear-gradient(145deg, var(--primary-600), var(--primary-700)); }
.support-item-icon.whatsapp { background: linear-gradient(145deg, #25D366, #128C7E); }
.support-item-icon.email { background: linear-gradient(145deg, var(--error-500), var(--error-600)); }
.support-item-icon.hours { background: linear-gradient(145deg, var(--info-500), var(--info-600)); }

.support-item-label {
    font-size: 0.75rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    color: var(--neutral-500);
    margin-bottom: var(--space-2);
}

.support-item-value {
    font-size: 0.95rem;
    font-weight: 500;
    color: var(--neutral-800);
    line-height: 1.5;
    word-break: break-word;
}

.support-item-value a {
    color: inherit;
    text-decoration: none;
    transition: color 0.2s ease;
}

.support-item-value a:hover {
    color: var(--primary-600);
}

/* Responsive Breakpoints */
@media (max-width: 1024px) {
    .content-grid-modern {
        gap: var(--space-4);
    }
    
    .steps-grid-modern {
        gap: var(--space-2);
    }
    
    .step-number-modern {
        width: 50px;
        height: 50px;
        font-size: 1rem;
    }
}

@media (max-width: 768px) {
    .content-grid-modern {
        grid-template-columns: 1fr;
    }
    
    .steps-grid-modern {
        grid-template-columns: repeat(2, 1fr);
        gap: var(--space-6);
    }
    
    .steps-grid-modern::before {
        display: none;
    }
    
    .steps-grid-modern > :last-child {
        grid-column: span 2;
        max-width: 200px;
        margin: 0 auto;
    }
    
    .support-grid-modern {
        grid-template-columns: repeat(2, 1fr);
    }
    
    .cta-links-modern {
        gap: var(--space-4);
    }
}

@media (max-width: 640px) {
    .landing-modern {
        padding: 0 var(--space-3);
    }
    
    .hero-modern {
        padding: var(--space-6) var(--space-4);
    }
    
    .hero-modern h1 {
        font-size: 1.8rem;
    }
    
    .stat-value {
        font-size: 2rem;
    }
    
    .card-body-modern {
        padding: var(--space-4);
    }
    
    .info-row-modern {
        flex-direction: column;
        gap: var(--space-1);
        align-items: flex-start;
    }
    
    .info-label {
        min-width: auto;
    }
    
    .steps-grid-modern {
        grid-template-columns: 1fr;
    }
    
    .steps-grid-modern > :last-child {
        grid-column: auto;
        max-width: none;
    }
    
    .step-item-modern {
        display: flex;
        align-items: center;
        text-align: left;
        gap: var(--space-4);
    }
    
    .step-number-modern {
        margin: 0;
    }
    
    .support-grid-modern {
        grid-template-columns: 1fr;
    }
    
    .btn-modern {
        min-width: 100%;
    }
    
    .notice-banner-modern {
        flex-direction: column;
        text-align: center;
    }
    
    .notice-icon-modern {
        margin: 0 auto;
    }
}

/* Loading States */
.loading-spinner {
    width: 20px;
    height: 20px;
    border: 3px solid rgba(255, 255, 255, 0.3);
    border-radius: 50%;
    border-top-color: white;
    animation: spin 1s linear infinite;
}

@keyframes spin {
    to { transform: rotate(360deg); }
}

/* Focus States for Accessibility */
a:focus-visible,
button:focus-visible {
    outline: 3px solid var(--primary-400);
    outline-offset: 2px;
}

/* Print Styles */
@media print {
    .btn-modern,
    .support-grid-modern {
        display: none;
    }
    
    .hero-modern {
        background: none;
        color: black;
        border: 1px solid #000;
    }
    
    .hero-modern h1 {
        color: black;
    }
}
</style>

<div class="landing-modern">
    <!-- Hero Section -->
    <section class="hero-modern">
        <div class="status-badge-modern <?php echo $isOpen ? 'open' : 'closed'; ?>">
            <span class="status-dot <?php echo $isOpen ? 'open' : 'closed'; ?>"></span>
            Applications <?php echo $isOpen ? 'Open' : 'Closed'; ?>
        </div>
        <h1>2025/2026 Admissions Application Portal</h1>
        <p class="hero-subtitle">ND/HND Nursing Programme — FCT College of Nursing Sciences</p>
    </section>

    <!-- Stats Grid -->
    <section class="stats-grid-modern">
        <div class="stat-card-modern">
            <div class="stat-value"><?php echo $currency; ?><?php echo $fee; ?></div>
            <div class="stat-label">Application Fee</div>
        </div>
        <div class="stat-card-modern">
            <div class="stat-value"><?php echo $min_score; ?>+</div>
            <div class="stat-label">Min UTME Score</div>
        </div>
        <div class="stat-card-modern">
            <div class="stat-value">4</div>
            <div class="stat-label">Programme Duration</div>
        </div>
        <div class="stat-card-modern">
            <div class="stat-value"><?php echo $max_sittings; ?></div>
            <div class="stat-label">Max O'Level Sittings</div>
        </div>
    </section>

    <!-- Content Grid -->
    <section class="content-grid-modern">
        <!-- Application Period Card -->
        <div class="card-modern">
            <div class="card-header-modern">
                <div class="card-icon-modern"><i class="fas fa-calendar-alt"></i></div>
                <h3>Application Period</h3>
            </div>
            <div class="card-body-modern">
                <div class="info-row-modern">
                    <span class="info-label">Form Sales:</span>
                    <span class="info-value"><?php echo $start_date; ?> — <?php echo $end_date; ?></span>
                </div>
                <div class="info-row-modern">
                    <span class="info-label">CBT Screening:</span>
                    <span class="info-value"><?php echo $cbt_start; ?> — <?php echo $cbt_end; ?></span>
                </div>
                <div class="info-row-modern">
                    <span class="info-label">Venue:</span>
                    <span class="info-value">FCT College of Nursing Sciences, Gwagwalada</span>
                </div>
                <div class="info-row-modern">
                    <span class="info-label">Reporting Time:</span>
                    <span class="info-value">8:00 AM daily</span>
                </div>
                <div class="info-row-modern">
                    <span class="info-label">Portal Status:</span>
                    <span class="info-value">
                        <span class="status-badge-modern <?php echo $isOpen ? 'open' : 'closed'; ?>" style="display: inline-flex; padding: 0.25rem 0.75rem; margin: 0;">
                            <span class="status-dot <?php echo $isOpen ? 'open' : 'closed'; ?>" style="margin-right: 0.5rem;"></span>
                            <?php echo $isOpen ? 'Open' : 'Closed'; ?>
                        </span>
                    </span>
                </div>
            </div>
        </div>

        <!-- Programme & Eligibility Card -->
        <div class="card-modern">
            <div class="card-header-modern">
                <div class="card-icon-modern"><i class="fas fa-graduation-cap"></i></div>
                <h3>Programme & Eligibility</h3>
            </div>
            <div class="card-body-modern">
                <div class="info-row-modern">
                    <span class="info-label">Programme:</span>
                    <span class="info-value">ND/HND Nursing (Non-terminal)</span>
                </div>
                <div class="info-row-modern">
                    <span class="info-label">Duration:</span>
                    <span class="info-value"><?php echo $duration; ?></span>
                </div>
                <div class="info-row-modern">
                    <span class="info-label">Accreditation:</span>
                    <span class="info-value">NBTE & NMCN Approved</span>
                </div>
                <div class="info-row-modern" style="align-items: flex-start;">
                    <span class="info-label">Requirements:</span>
                    <ul class="requirements-list-modern">
                        <li>
                            <span class="requirement-check"><i class="fas fa-check"></i></span>
                            Minimum UTME score of <?php echo $min_score; ?>
                        </li>
                        <li>
                            <span class="requirement-check"><i class="fas fa-check"></i></span>
                            First Choice: FCT College of Nursing Sciences
                        </li>
                        <li>
                            <span class="requirement-check"><i class="fas fa-check"></i></span>
                            5 O'Level Credits in ≤ <?php echo $max_sittings; ?> sittings
                        </li>
                        <li>
                            <span class="requirement-check"><i class="fas fa-check"></i></span>
                            Minimum age of <?php echo $min_age; ?> years
                        </li>
                        <li>
                            <span class="requirement-check"><i class="fas fa-check"></i></span>
                            Valid JAMB registration number
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    <!-- Process Section -->
    <section class="process-section-modern">
        <div class="process-header-modern">
            <div class="process-icon-modern"><i class="fas fa-route"></i></div>
            <h3>Application Process</h3>
        </div>
        <div class="process-body-modern">
            <div class="steps-grid-modern">
                <div class="step-item-modern">
                    <div class="step-number-modern active">1</div>
                    <h4>Create Account</h4>
                    <p>Register & verify email</p>
                </div>
                <div class="step-item-modern">
                    <div class="step-number-modern">2</div>
                    <h4>JAMB Verification</h4>
                    <p>Verify your JAMB number</p>
                </div>
                <div class="step-item-modern">
                    <div class="step-number-modern">3</div>
                    <h4>Application Form</h4>
                    <p>Fill personal details</p>
                </div>
                <div class="step-item-modern">
                    <div class="step-number-modern">4</div>
                    <h4>Payment</h4>
                    <p><?php echo $currency . $fee; ?> via Remita</p>
                </div>
                <div class="step-item-modern">
                    <div class="step-number-modern">5</div>
                    <h4>Exam Slip</h4>
                    <p>Download CBT slip</p>
                </div>
            </div>

            <div class="cta-section-modern">
                <?php if ($isOpen): ?>
                    <a href="/apply/register" class="btn-modern btn-modern-primary" id="startApplicationBtn">
                        <span>Start Your Application</span>
                        <i class="fas fa-arrow-right"></i>
                    </a>
                <?php else: ?>
                    <button class="btn-modern btn-modern-primary disabled" disabled>
                        <i class="fas fa-ban"></i>
                        Applications Currently Closed
                    </button>
                <?php endif; ?>

                <div class="cta-links-modern">
                    <span class="cta-link-modern">
                        Already registered? <a href="/applicant/login">Login here</a>
                    </span>
                    <span class="cta-link-modern">
                        Forgot password? <a href="/applicant/forgot-password">Reset here</a>
                    </span>
                </div>
            </div>
        </div>
    </section>

    <!-- Notice Banner -->
    <section class="notice-banner-modern">
        <div class="notice-icon-modern">
            <i class="fas fa-exclamation-triangle"></i>
        </div>
        <p>
            <strong>Important Notice:</strong> No extension of the application deadline will be granted.
            The College has <strong>NO AGENTS</strong>. Beware of fraudulent websites and individuals —
            deal only through the official channels listed below.
        </p>
    </section>

    <!-- Support Section -->
    <section class="support-section-modern">
        <div class="support-header-modern">
            <div class="support-icon-modern"><i class="fas fa-headset"></i></div>
            <h3>Support & Enquiries</h3>
        </div>
        <div class="support-grid-modern">
            <div class="support-item-modern">
                <div class="support-item-icon phone"><i class="fas fa-phone-alt"></i></div>
                <div class="support-item-label">Phone</div>
                <div class="support-item-value">
                    <a href="tel:<?php echo $ph1; ?>"><?php echo $ph1; ?></a><br>
                    <a href="tel:<?php echo $ph2; ?>"><?php echo $ph2; ?></a>
                </div>
            </div>
            <div class="support-item-modern">
                <div class="support-item-icon whatsapp"><i class="fab fa-whatsapp"></i></div>
                <div class="support-item-label">WhatsApp</div>
                <div class="support-item-value">
                    <a href="https://wa.me/<?php echo preg_replace('/[^0-9]/', '', $wa); ?>"><?php echo $wa; ?></a>
                </div>
            </div>
            <div class="support-item-modern">
                <div class="support-item-icon email"><i class="fas fa-envelope"></i></div>
                <div class="support-item-label">Email</div>
                <div class="support-item-value">
                    <a href="mailto:<?php echo $email; ?>"><?php echo $email; ?></a>
                </div>
            </div>
            <div class="support-item-modern">
                <div class="support-item-icon hours"><i class="fas fa-clock"></i></div>
                <div class="support-item-label">Office Hours</div>
                <div class="support-item-value"><?php echo $hours; ?></div>
            </div>
        </div>
    </section>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const startBtn = document.getElementById('startApplicationBtn');
    
    if (startBtn) {
        startBtn.addEventListener('click', function(e) {
            const originalText = this.innerHTML;
            this.innerHTML = '<span class="loading-spinner" style="margin-right: 0.5rem;"></span> Redirecting...';
            this.style.pointerEvents = 'none';
            
            // Safety timeout to restore button if stuck
            setTimeout(() => {
                this.innerHTML = originalText;
                this.style.pointerEvents = 'auto';
            }, 5000);
        });
    }
    
    // Add smooth scroll to sections
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.scrollIntoView({ behavior: 'smooth', block: 'start' });
            }
        });
    });
    
    // Intersection Observer for fade-in animations
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.style.opacity = '1';
                entry.target.style.transform = 'translateY(0)';
            }
        });
    }, { threshold: 0.1 });
    
    document.querySelectorAll('.card-modern, .stat-card-modern, .process-section-modern').forEach(el => {
        el.style.opacity = '0';
        el.style.transform = 'translateY(20px)';
        el.style.transition = 'opacity 0.5s ease, transform 0.5s ease';
        observer.observe(el);
    });
});
</script>