<?php
/**
 * Contact Page View Template - Professional Redesign
 * 
 * MVC view - displays contact form and information
 * Based on international university website standards
 * 
 * @package FCTCNS
 * @version 2.0
 */

extract($data ?? []);

if (!function_exists('e')) {
    function e($text) {
        return htmlspecialchars($text ?? '', ENT_QUOTES, 'UTF-8');
    }
}

$settings = $contact_settings ?? [];
?>

<style>
/* ========== GLOBAL VARIABLES ========== */
:root {
    /* Professional Color Palette - Academic Excellence */
    --color-primary: #1a365d;               /* Deep navy - authority, trust */
    --color-primary-dark: #0d243f;
    --color-primary-light: #2c5282;
    --color-secondary: #2d3748;             /* Charcoal - professional text */
    --color-accent: #285e61;                /* Teal - institutional highlight */
    --color-success: #276749;
    --color-warning: #b7791f;
    --color-danger: #c53030;
    --color-gray-50: #f7fafc;
    --color-gray-100: #edf2f7;
    --color-gray-200: #e2e8f0;
    --color-gray-300: #cbd5e0;
    --color-gray-400: #a0aec0;
    --color-gray-600: #718096;
    --color-gray-700: #4a5568;
    --color-gray-800: #2d3748;
    --color-gray-900: #1a202c;
    
    /* Typography - Academic Standards */
    --font-heading: 'Georgia', 'Times New Roman', serif;
    --font-body: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
    --font-mono: 'SFMono-Regular', Consolas, 'Liberation Mono', Menlo, monospace;
    
    /* Spacing System */
    --space-xs: 0.25rem;
    --space-sm: 0.5rem;
    --space-md: 1rem;
    --space-lg: 1.5rem;
    --space-xl: 2rem;
    --space-2xl: 3rem;
    --space-3xl: 4rem;
    --space-4xl: 5rem;
    
    /* Border Radius */
    --radius-sm: 4px;
    --radius-md: 6px;
    --radius-lg: 8px;
    --radius-xl: 12px;
    --radius-2xl: 16px;
    
    /* Shadows */
    --shadow-sm: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
    --shadow-md: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -1px rgba(0, 0, 0, 0.03);
    --shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.07), 0 4px 6px -2px rgba(0, 0, 0, 0.03);
    --shadow-xl: 0 20px 25px -5px rgba(0, 0, 0, 0.08), 0 10px 10px -5px rgba(0, 0, 0, 0.02);
    --shadow-inner: inset 0 2px 4px 0 rgba(0, 0, 0, 0.05);
    
    /* Transitions */
    --transition-fast: 150ms cubic-bezier(0.4, 0, 0.2, 1);
    --transition-base: 250ms cubic-bezier(0.4, 0, 0.2, 1);
    --transition-slow: 350ms cubic-bezier(0.4, 0, 0.2, 1);
    
    /* Container Widths */
    --container-sm: 640px;
    --container-md: 768px;
    --container-lg: 1024px;
    --container-xl: 1280px;
    --container-2xl: 1536px;
    
    /* Border Colors */
    --border-color: #e2e8f0;
    --border-color-dark: #cbd5e0;
    
    /* Backgrounds */
    --bg-primary: #ffffff;
    --bg-secondary: #f7fafc;
    --bg-accent: #285e61;
}

/* ========== GLOBAL RESET & BASE STYLES ========== */
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body {
    font-family: var(--font-body);
    line-height: 1.6;
    color: var(--color-secondary);
    background-color: #ffffff;
}

/* CRITICAL: Remove space between header and body */
body > main.main-content,
.homepage-content,
.hero-section,
.hero-carousel,
.contact-hero,
.contact-container {
    margin-top: 0 !important;
    padding-top: 0 !important;
}

/* Override any existing margins */
*[style*="margin-top"], 
*[style*="padding-top"] {
    margin-top: 0 !important;
    padding-top: 0 !important;
}

/* Ensure no space at the very top */
html, body {
    margin: 0;
    padding: 0;
}

.contact-container {
    max-width: var(--container-xl);
    margin: 0 auto;
    padding: 0 var(--space-lg);
}

/* ========== HERO SECTION ========== */
.contact-hero {
    background: linear-gradient(135deg, rgba(26, 54, 93, 0.97) 0%, rgba(13, 36, 63, 0.95) 100%);
    color: white;
    padding: var(--space-3xl) 0 var(--space-2xl);
    position: relative;
    overflow: hidden;
    margin: 0 !important;
    border-bottom: 1px solid rgba(255, 255, 255, 0.1);
}

.contact-hero::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background-image: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.03'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
    pointer-events: none;
}

.hero-content {
    position: relative;
    z-index: 1;
    max-width: 800px;
    margin: 0 auto;
    text-align: center;
}

.hero-content h1 {
    font-family: var(--font-heading);
    font-size: 3rem;
    font-weight: 700;
    line-height: 1.1;
    margin-bottom: var(--space-md);
    color: white;
    letter-spacing: -0.02em;
    padding-top: 0 !important;
    margin-top: 0 !important;
}

.hero-content .lead {
    font-size: 1.25rem;
    line-height: 1.7;
    margin-bottom: var(--space-lg);
    opacity: 0.95;
    font-weight: 300;
    max-width: 700px;
    margin-left: auto;
    margin-right: auto;
}

.hero-meta {
    display: inline-flex;
    align-items: center;
    gap: var(--space-md);
    background: rgba(255, 255, 255, 0.1);
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.15);
    border-radius: var(--radius-xl);
    padding: var(--space-sm) var(--space-lg);
    font-size: 0.9rem;
}

.hero-meta span {
    display: inline-flex;
    align-items: center;
    gap: var(--space-sm);
}

.hero-meta .divider {
    width: 1px;
    height: 1rem;
    background: rgba(255, 255, 255, 0.3);
}

/* ========== CONTACT INFORMATION SECTION ========== */
.contact-info-section {
    margin-bottom: var(--space-4xl);
    padding-top: var(--space-2xl);
}

.section-header {
    text-align: center;
    margin-bottom: var(--space-2xl);
    padding: 0 var(--space-md);
}

.section-header h2 {
    font-family: var(--font-heading);
    font-size: 2.25rem;
    font-weight: 700;
    color: var(--color-primary);
    margin-bottom: var(--space-md);
    line-height: 1.2;
}

.section-header .section-description {
    font-size: 1.125rem;
    color: var(--color-gray-700);
    max-width: 700px;
    margin: 0 auto;
    line-height: 1.6;
}

.contact-grid-four {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
    gap: var(--space-xl);
    margin-bottom: var(--space-3xl);
}

.contact-card {
    background: var(--bg-primary);
    border: 1px solid var(--border-color);
    border-radius: var(--radius-xl);
    padding: var(--space-2xl);
    box-shadow: var(--shadow-md);
    transition: all var(--transition-base);
    height: 100%;
    display: flex;
    flex-direction: column;
    position: relative;
    overflow: hidden;
}

.contact-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 4px;
    background: linear-gradient(90deg, var(--color-primary), var(--color-accent));
}

.contact-card:hover {
    transform: translateY(-8px);
    box-shadow: var(--shadow-xl);
    border-color: var(--color-primary-light);
}

.card-header {
    display: flex;
    align-items: center;
    gap: var(--space-md);
    margin-bottom: var(--space-lg);
}

.card-icon {
    width: 56px;
    height: 56px;
    border-radius: var(--radius-lg);
    display: flex;
    align-items: center;
    justify-content: center;
    background: linear-gradient(135deg, var(--color-primary) 0%, var(--color-primary-dark) 100%);
    color: white;
    flex-shrink: 0;
    padding: var(--space-sm);
}

.card-icon svg {
    width: 24px;
    height: 24px;
    stroke: currentColor;
    stroke-width: 2;
    fill: none;
    stroke-linecap: round;
    stroke-linejoin: round;
}

.card-title {
    font-family: var(--font-heading);
    font-size: 1.375rem;
    font-weight: 600;
    color: var(--color-secondary);
    margin: 0;
    line-height: 1.3;
}

.card-content {
    flex-grow: 1;
    margin-bottom: var(--space-lg);
}

.card-content p {
    color: var(--color-gray-700);
    line-height: 1.7;
    margin-bottom: var(--space-sm);
    font-size: 1rem;
}

.card-content strong {
    color: var(--color-secondary);
    font-weight: 600;
}

.card-cta {
    margin-top: auto;
    padding-top: var(--space-lg);
    border-top: 1px solid var(--border-color);
}

.cta-link {
    display: inline-flex;
    align-items: center;
    gap: var(--space-sm);
    color: var(--color-primary);
    text-decoration: none;
    font-weight: 500;
    font-size: 0.95rem;
    padding: var(--space-sm) var(--space-md);
    border: 1px solid var(--border-color);
    border-radius: var(--radius-md);
    transition: all var(--transition-fast);
    background: var(--bg-secondary);
}

.cta-link:hover {
    background: var(--color-primary);
    color: white;
    border-color: var(--color-primary);
    text-decoration: none;
}

.cta-link::after {
    content: '→';
    font-size: 1.1em;
    transition: transform var(--transition-fast);
}

.cta-link:hover::after {
    transform: translateX(3px);
}

/* ========== MAIN CONTENT GRID ========== */
.contact-main-grid {
    display: grid;
    grid-template-columns: 1fr;
    gap: var(--space-3xl);
    margin-bottom: var(--space-4xl);
}

@media (min-width: 992px) {
    .contact-main-grid {
        grid-template-columns: 1.25fr 1fr;
        gap: var(--space-4xl);
    }
}

/* ========== CONTACT FORM ========== */
.contact-form-container {
    background: var(--bg-primary);
    border-radius: var(--radius-2xl);
    padding: var(--space-3xl);
    border: 1px solid var(--border-color);
    box-shadow: var(--shadow-lg);
}

.form-header {
    margin-bottom: var(--space-2xl);
    display: flex;
    align-items: center;
    gap: var(--space-md);
}

.form-header-icon {
    width: 48px;
    height: 48px;
    border-radius: var(--radius-lg);
    display: flex;
    align-items: center;
    justify-content: center;
    background: linear-gradient(135deg, var(--color-primary) 0%, var(--color-primary-dark) 100%);
    color: white;
    flex-shrink: 0;
    padding: var(--space-sm);
}

.form-header-icon svg {
    width: 24px;
    height: 24px;
    stroke: currentColor;
    stroke-width: 2;
    fill: none;
    stroke-linecap: round;
    stroke-linejoin: round;
}

.form-header-content h2 {
    font-family: var(--font-heading);
    font-size: 2rem;
    font-weight: 700;
    color: var(--color-secondary);
    margin-bottom: var(--space-xs);
    line-height: 1.2;
}

.form-header .form-description {
    color: var(--color-gray-600);
    line-height: 1.6;
    font-size: 1.0625rem;
}

/* Form Styling */
.contact-form {
    display: grid;
    gap: var(--space-xl);
}

.form-row {
    display: grid;
    grid-template-columns: 1fr;
    gap: var(--space-lg);
}

@media (min-width: 768px) {
    .form-row {
        grid-template-columns: 1fr 1fr;
    }
}

.form-field {
    display: flex;
    flex-direction: column;
}

.form-field label {
    display: block;
    margin-bottom: var(--space-sm);
    font-weight: 500;
    color: var(--color-secondary);
    font-size: 0.9375rem;
    letter-spacing: 0.01em;
}

.form-field label.required::after {
    content: ' *';
    color: var(--color-danger);
    font-weight: 700;
}

.form-input,
.form-select,
.form-textarea {
    width: 100%;
    padding: 0.875rem 1rem;
    font-size: 1rem;
    font-family: var(--font-body);
    border: 1px solid var(--border-color);
    border-radius: var(--radius-md);
    background-color: white;
    color: var(--color-secondary);
    transition: all var(--transition-fast);
    line-height: 1.5;
}

.form-input:focus,
.form-select:focus,
.form-textarea:focus {
    outline: none;
    border-color: var(--color-primary);
    box-shadow: 0 0 0 3px rgba(26, 54, 93, 0.1);
}

.form-input::placeholder,
.form-textarea::placeholder {
    color: var(--color-gray-400);
    font-weight: 300;
}

.form-textarea {
    min-height: 160px;
    resize: vertical;
    line-height: 1.6;
}

.form-select {
    appearance: none;
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' viewBox='0 0 24 24' fill='none' stroke='%23718096' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpath d='M6 9l6 6 6-6'/%3E%3C/svg%3E");
    background-repeat: no-repeat;
    background-position: right 1rem center;
    background-size: 1em;
    cursor: pointer;
    padding-right: 2.5rem;
}

.field-hint {
    font-size: 0.8125rem;
    color: var(--color-gray-600);
    margin-top: var(--space-xs);
    font-style: italic;
    line-height: 1.4;
}

.form-submit {
    margin-top: var(--space-lg);
}

.submit-button {
    width: 100%;
    padding: 1.125rem 2rem;
    background: linear-gradient(135deg, var(--color-primary) 0%, var(--color-primary-dark) 100%);
    color: white;
    border: none;
    border-radius: var(--radius-lg);
    font-size: 1.125rem;
    font-weight: 600;
    cursor: pointer;
    transition: all var(--transition-base);
    letter-spacing: 0.02em;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: var(--space-sm);
    position: relative;
    overflow: hidden;
}

.submit-button::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
    transition: left 0.7s;
}

.submit-button:hover {
    background: linear-gradient(135deg, var(--color-primary-dark) 0%, #0a1a2f 100%);
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(26, 54, 93, 0.25);
}

.submit-button:hover::before {
    left: 100%;
}

.submit-button:active {
    transform: translateY(0);
}

.submit-button .icon {
    font-size: 1.2em;
}

/* ========== SIDEBAR CONTENT ========== */
.sidebar-content {
    display: flex;
    flex-direction: column;
    gap: var(--space-2xl);
}

.sidebar-card {
    background: var(--bg-primary);
    border: 1px solid var(--border-color);
    border-radius: var(--radius-xl);
    padding: var(--space-2xl);
    box-shadow: var(--shadow-md);
}

.sidebar-card h3 {
    font-family: var(--font-heading);
    font-size: 1.5rem;
    font-weight: 600;
    color: var(--color-secondary);
    margin-bottom: var(--space-lg);
    padding-bottom: var(--space-md);
    border-bottom: 2px solid var(--border-color);
}

/* FAQ Section */
.faq-list {
    list-style: none;
    margin: 0 0 var(--space-lg) 0;
    padding: 0;
}

.faq-list li {
    padding: var(--space-md) 0;
    border-bottom: 1px solid var(--border-color);
    color: var(--color-gray-700);
    line-height: 1.5;
    font-size: 0.9375rem;
    position: relative;
    padding-left: var(--space-lg);
}

.faq-list li::before {
    content: '';
    position: absolute;
    left: 0;
    top: 1.375rem;
    width: 6px;
    height: 6px;
    border-radius: 50%;
    background: var(--color-accent);
}

.faq-list li:last-child {
    border-bottom: none;
}

.faq-link {
    display: inline-flex;
    align-items: center;
    gap: var(--space-sm);
    color: var(--color-primary);
    text-decoration: no-none;
    font-weight: 500;
    font-size: 0.9375rem;
    padding: var(--space-sm) var(--space-md);
    background: var(--bg-secondary);
    border-radius: var(--radius-md);
    border: 1px solid var(--border-color);
    transition: all var(--transition-fast);
}

.faq-link:hover {
    background: var(--color-primary);
    color: white;
    text-decoration: none;
    border-color: var(--color-primary);
}

/* Quick Links */
.quick-links {
    display: grid;
    gap: var(--space-sm);
}

.quick-link {
    display: flex;
    align-items: center;
    gap: var(--space-md);
    padding: var(--space-md) var(--space-lg);
    background: var(--bg-secondary);
    border: 1px solid var(--border-color);
    border-radius: var(--radius-lg);
    color: var(--color-secondary);
    text-decoration: none;
    font-weight: 500;
    font-size: 0.9375rem;
    transition: all var(--transition-fast);
    position: relative;
    overflow: hidden;
}

.quick-link::before {
    content: '';
    position: absolute;
    left: 0;
    top: 0;
    bottom: 0;
    width: 4px;
    background: var(--color-primary);
    transform: scaleY(0);
    transition: transform var(--transition-base);
}

.quick-link:hover {
    background: white;
    border-color: var(--color-primary-light);
    color: var(--color-primary);
    transform: translateX(4px);
    box-shadow: var(--shadow-md);
}

.quick-link:hover::before {
    transform: scaleY(1);
}

.quick-link .link-icon {
    width: 20px;
    height: 20px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--color-primary);
    font-size: 1rem;
}

.quick-link .link-text {
    flex-grow: 1;
}

.quick-link .arrow {
    color: var(--color-gray-400);
    font-size: 0.875rem;
    transition: transform var(--transition-fast);
}

.quick-link:hover .arrow {
    transform: translateX(3px);
    color: var(--color-primary);
}

/* Response Time Info */
.response-info {
    background: linear-gradient(135deg, rgba(40, 94, 97, 0.05) 0%, rgba(26, 54, 93, 0.05) 100%);
    border: 1px solid rgba(40, 94, 97, 0.2);
}

.response-info h3 {
    border-bottom-color: rgba(40, 94, 97, 0.3);
    color: var(--color-accent);
}

.timeline-list {
    list-style: none;
    margin: 0;
    padding: 0;
}

.timeline-list li {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    padding: var(--space-md) 0;
    border-bottom: 1px solid rgba(40, 94, 97, 0.1);
}

.timeline-list li:last-child {
    border-bottom: none;
}

.timeline-list .category {
    font-weight: 600;
    color: var(--color-accent);
    font-size: 0.9375rem;
}

.timeline-list .timeframe {
    color: var(--color-gray-700);
    font-size: 0.9375rem;
    text-align: right;
}

/* ========== MAP SECTION ========== */
.map-section {
    margin-bottom: var(--space-4xl);
}

.map-container {
    border-radius: var(--radius-xl);
    overflow: hidden;
    box-shadow: var(--shadow-lg);
    border: 1px solid var(--border-color);
    height: 450px;
    position: relative;
}

.map-overlay {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: linear-gradient(135deg, rgba(26, 54, 93, 0.9) 0%, rgba(13, 36, 63, 0.85) 100%);
    display: flex;
    align-items: center;
    justify-content: center;
    flex-direction: column;
    text-align: center;
    padding: var(--space-3xl);
    z-index: 2;
}

.map-content {
    max-width: 500px;
    color: white;
}

.map-content .icon {
    font-size: 3.5rem;
    margin-bottom: var(--space-lg);
    opacity: 0.9;
}

.map-content h3 {
    font-family: var(--font-heading);
    font-size: 1.75rem;
    font-weight: 700;
    margin-bottom: var(--space-md);
    line-height: 1.2;
}

.map-content p {
    font-size: 1.125rem;
    line-height: 1.6;
    margin-bottom: var(--space-xl);
    opacity: 0.9;
}

.map-actions {
    display: flex;
    gap: var(--space-md);
    flex-wrap: wrap;
    justify-content: center;
}

.map-button {
    padding: 0.875rem 1.75rem;
    background: rgba(255, 255, 255, 0.1);
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.2);
    border-radius: var(--radius-lg);
    color: white;
    text-decoration: none;
    font-weight: 500;
    font-size: 0.9375rem;
    transition: all var(--transition-fast);
    display: inline-flex;
    align-items: center;
    gap: var(--space-sm);
}

.map-button.primary {
    background: white;
    color: var(--color-primary);
    border-color: white;
}

.map-button:hover {
    background: rgba(255, 255, 255, 0.2);
    border-color: rgba(255, 255, 255, 0.3);
    transform: translateY(-2px);
}

.map-button.primary:hover {
    background: var(--color-gray-100);
    color: var(--color-primary-dark);
}

/* ========== ALERT MESSAGES ========== */
.alert-container {
    max-width: 800px;
    margin: 0 auto var(--space-2xl);
}

.alert {
    padding: var(--space-lg) var(--space-xl);
    border-radius: var(--radius-lg);
    margin-bottom: var(--space-md);
    border: 1px solid transparent;
    font-size: 0.9375rem;
    line-height: 1.5;
    display: flex;
    align-items: flex-start;
    gap: var(--space-md);
}

.alert-success {
    background-color: rgba(39, 103, 73, 0.08);
    border-color: rgba(39, 103, 73, 0.2);
    color: var(--color-success);
}

.alert-danger {
    background-color: rgba(197, 48, 48, 0.08);
    border-color: rgba(197, 48, 48, 0.2);
    color: var(--color-danger);
}

.alert-icon {
    font-size: 1.25rem;
    flex-shrink: 0;
}

.alert-content {
    flex-grow: 1;
}

/* ========== VALIDATION STYLES ========== */
.form-input.is-invalid,
.form-select.is-invalid,
.form-textarea.is-invalid {
    border-color: var(--color-danger);
    background-color: rgba(197, 48, 48, 0.02);
}

.form-input.is-invalid:focus,
.form-select.is-invalid:focus,
.form-textarea.is-invalid:focus {
    box-shadow: 0 0 0 3px rgba(197, 48, 48, 0.1);
}

.invalid-feedback {
    display: none;
    color: var(--color-danger);
    font-size: 0.8125rem;
    margin-top: var(--space-xs);
    line-height: 1.4;
}

.form-input.is-invalid ~ .invalid-feedback,
.form-select.is-invalid ~ .invalid-feedback,
.form-textarea.is-invalid ~ .invalid-feedback {
    display: block;
}

/* ========== RESPONSIVE DESIGN ========== */
@media (max-width: 768px) {
    .contact-container {
        padding: 0 var(--space-md);
    }
    
    .contact-hero {
        padding: var(--space-2xl) 0 var(--space-xl);
        margin-bottom: var(--space-2xl);
    }
    
    .hero-content h1 {
        font-size: 2.25rem;
    }
    
    .hero-content .lead {
        font-size: 1.125rem;
    }
    
    .hero-meta {
        flex-direction: column;
        gap: var(--space-sm);
        align-items: flex-start;
    }
    
    .hero-meta .divider {
        display: none;
    }
    
    .section-header h2 {
        font-size: 1.75rem;
    }
    
    .contact-grid-four {
        grid-template-columns: 1fr;
        gap: var(--space-lg);
    }
    
    .contact-card {
        padding: var(--space-xl);
    }
    
    .contact-form-container {
        padding: var(--space-xl);
    }
    
    .form-header h2 {
        font-size: 1.75rem;
    }
    
    .sidebar-card {
        padding: var(--space-xl);
    }
    
    .map-container {
        height: 350px;
    }
    
    .map-content {
        padding: var(--space-xl);
    }
    
    .map-content h3 {
        font-size: 1.5rem;
    }
    
    .map-actions {
        flex-direction: column;
        width: 100%;
    }
    
    .map-button {
        width: 100%;
        justify-content: center;
    }
}

@media (max-width: 480px) {
    .contact-hero {
        padding: var(--space-xl) 0 var(--space-lg);
    }
    
    .hero-content h1 {
        font-size: 1.875rem;
    }
    
    .hero-content .lead {
        font-size: 1rem;
    }
    
    .card-header {
        flex-direction: column;
        align-items: flex-start;
        gap: var(--space-sm);
    }
    
    .contact-form-container {
        padding: var(--space-lg);
    }
    
    .submit-button {
        padding: 1rem 1.5rem;
        font-size: 1rem;
    }
}

/* ========== PRINT STYLES ========== */
@media print {
    .contact-hero {
        background: white !important;
        color: black !important;
        padding: 1rem 0 !important;
    }
    
    .hero-content h1 {
        color: black !important;
        font-size: 2rem !important;
    }
    
    .hero-meta,
    .cta-link,
    .map-container,
    .submit-button,
    .sidebar-card:last-child {
        display: none !important;
    }
    
    .contact-card {
        break-inside: avoid;
        box-shadow: none !important;
        border: 1px solid #ddd !important;
    }
    
    .contact-form-container {
        box-shadow: none !important;
        border: 1px solid #ddd !important;
    }
    
    .contact-main-grid {
        display: block !important;
    }
    
    .sidebar-content {
        display: block !important;
    }
}

/* ========== ACCESSIBILITY ========== */
.visually-hidden {
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

.focus-visible:focus {
    outline: 2px solid var(--color-primary);
    outline-offset: 2px;
}

.skip-to-content {
    position: absolute;
    top: -40px;
    left: 0;
    background: var(--color-primary);
    color: white;
    padding: 8px 16px;
    z-index: 1001;
    text-decoration: none;
    border-radius: 0 0 4px 0;
    transition: top 0.2s ease;
}

.skip-to-content:focus {
    top: 0;
}
</style>

<!-- SVG Definitions for Icons -->
<svg style="display: none;">
    <symbol id="icon-location" viewBox="0 0 24 24">
        <path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5a2.5 2.5 0 0 1 0-5 2.5 2.5 0 0 1 0 5z"/>
    </symbol>
    <symbol id="icon-phone" viewBox="0 0 24 24">
        <path d="M20 15.5c-1.2 0-2.4-.2-3.6-.6-.3-.1-.7 0-1 .2l-2.2 2.2c-2.8-1.5-5.2-3.8-6.6-6.6l2.2-2.2c.3-.3.4-.7.2-1-.3-1.1-.5-2.3-.5-3.5 0-.6-.4-1-1-1H4c-.6 0-1 .4-1 1 0 9.4 7.6 17 17 17 .6 0 1-.4 1-1v-3.5c0-.6-.4-1-1-1zM5 6h1.5c.1 1.2.3 2.4.6 3.5L5.3 11.8c-.8-1.8-1.2-3.8-1.3-5.8zM19 20c-2-.1-4-.5-5.8-1.3l2.3-2.3c1.1.3 2.3.5 3.5.6V20z"/>
    </symbol>
    <symbol id="icon-email" viewBox="0 0 24 24">
        <path d="M20 4H4c-1.1 0-1.99.9-1.99 2L2 18c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 4l-8 5-8-5V6l8 5 8-5v2z"/>
    </symbol>
    <symbol id="icon-clock" viewBox="0 0 24 24">
        <path d="M11.99 2C6.47 2 2 6.48 2 12s4.47 10 9.99 10C17.52 22 22 17.52 22 12S17.52 2 11.99 2zM12 20c-4.42 0-8-3.58-8-8s3.58-8 8-8 8 3.58 8 8-3.58 8-8 8zm.5-13H11v6l5.25 3.15.75-1.23-4.5-2.67z"/>
    </symbol>
    <symbol id="icon-form" viewBox="0 0 24 24">
        <path d="M14 2H6c-1.1 0-1.99.9-1.99 2L4 20c0 1.1.89 2 1.99 2H18c1.1 0 2-.9 2-2V8l-6-6zm2 16H8v-2h8v2zm0-4H8v-2h8v2zm-3-5V3.5L18.5 9H13z"/>
    </symbol>
</svg>

<!-- Contact Page Content -->
<div class="contact-hero">
    <div class="hero-content">
        <h1>Contact FCT College of Nursing Sciences</h1>
        <p class="lead">As a premier institution for nursing education and healthcare research, we welcome inquiries from prospective students, academic partners, researchers, and healthcare organizations worldwide.</p>
        <div class="hero-meta">
            <span>Response Time: 24-48 Hours</span>
            <span class="divider"></span>
            <span>Office Hours: Monday-Friday, 8:00 AM - 5:00 PM</span>
        </div>
    </div>
</div>

<main class="contact-container" id="main-content">
    <!-- Skip to Content Link (Accessibility) -->
    <a href="#main-content" class="skip-to-content">Skip to main content</a>

    <!-- Flash Messages -->
    <?php if (!empty($flash_success)): ?>
    <div class="alert-container">
        <div class="alert alert-success">
            <div class="alert-icon">✓</div>
            <div class="alert-content"><?php echo e($flash_success); ?></div>
        </div>
    </div>
    <?php endif; ?>

    <?php if (!empty($flash_error)): ?>
    <div class="alert-container">
        <div class="alert alert-danger">
            <div class="alert-icon">!</div>
            <div class="alert-content"><?php echo e($flash_error); ?></div>
        </div>
    </div>
    <?php endif; ?>

    <!-- Contact Information Section -->
    <section class="contact-info-section" aria-labelledby="contact-info-heading">
        <div class="section-header">
            <h2 id="contact-info-heading">Contact Information</h2>
            <p class="section-description">Our administrative offices and support departments are available during standard business hours. For urgent matters, please use the telephone numbers provided.</p>
        </div>

        <div class="contact-grid-four">
            <!-- Address Card -->
            <article class="contact-card">
                <div class="card-header">
                    <div class="card-icon">
                        <svg><use xlink:href="#icon-location"></use></svg>
                    </div>
                    <h3 class="card-title">Main Campus Address</h3>
                </div>
                <div class="card-content">
                    <p><?php echo e($settings['address']); ?></p>
                    <p><strong>Campus:</strong> FCT College of Nursing Sciences</p>
                    <p><strong>City:</strong> Federal Capital Territory, Nigeria</p>
                </div>
                <div class="card-cta">
                    <a href="#map" class="cta-link">View Campus Directions</a>
                </div>
            </article>

            <!-- Telephone Card -->
            <article class="contact-card">
                <div class="card-header">
                    <div class="card-icon">
                        <svg><use xlink:href="#icon-phone"></use></svg>
                    </div>
                    <h3 class="card-title">Telephone & Fax</h3>
                </div>
                <div class="card-content">
                    <p><strong>Main Switchboard:</strong> <?php echo e($settings['phone']); ?></p>
                    <p><strong>Admissions Office:</strong> <?php echo e($settings['admissions_phone'] ?? 'Ext. 123'); ?></p>
                    <p><strong>Emergency Contact:</strong> <?php echo e($settings['emergency_contact']); ?></p>
                    <p><strong>Fax:</strong> <?php echo e($settings['fax'] ?? '+234 XXX XXX XXXX'); ?></p>
                </div>
                <div class="card-cta">
                    <a href="tel:<?php echo preg_replace('/[^0-9+]/', '', $settings['phone']); ?>" class="cta-link">Call Main Office</a>
                </div>
            </article>

            <!-- Email Card -->
            <article class="contact-card">
                <div class="card-header">
                    <div class="card-icon">
                        <svg><use xlink:href="#icon-email"></use></svg>
                    </div>
                    <h3 class="card-title">Electronic Correspondence</h3>
                </div>
                <div class="card-content">
                    <p><strong>General Inquiries:</strong> <?php echo e($settings['email']); ?></p>
                    <p><strong>Admissions Office:</strong> <?php echo e($settings['admissions_email']); ?></p>
                    <p><strong>Registrar's Office:</strong> <?php echo e($settings['registrar_email'] ?? 'registrar@fctcns.edu.ng'); ?></p>
                    <p><strong>Academic Affairs:</strong> <?php echo e($settings['academic_email'] ?? 'academics@fctcns.edu.ng'); ?></p>
                </div>
                <div class="card-cta">
                    <a href="mailto:<?php echo e($settings['email']); ?>?subject=General Inquiry" class="cta-link">Send Email</a>
                </div>
            </article>

            <!-- Hours Card -->
            <article class="contact-card">
                <div class="card-header">
                    <div class="card-icon">
                        <svg><use xlink:href="#icon-clock"></use></svg>
                    </div>
                    <h3 class="card-title">Office Hours</h3>
                </div>
                <div class="card-content">
                    <p><strong>Administrative Offices:</strong><br><?php echo e($settings['working_hours']); ?></p>
                    <p><strong>Weekend Hours:</strong><br>Saturday: 9:00 AM - 1:00 PM</p>
                    <p><strong>Holiday Schedule:</strong><br>Closed on Sundays & National Holidays</p>
                    <p><strong>Library Hours:</strong><br>Monday-Friday: 8:00 AM - 8:00 PM</p>
                </div>
                <div class="card-cta">
                    <a href="<?php echo $baseUrl; ?>/about/calendar" class="cta-link">Academic Calendar</a>
                </div>
            </article>
        </div>
    </section>

    <!-- Main Content Grid: Form + Sidebar -->
    <div class="contact-main-grid">
        <!-- Contact Form Column -->
        <section class="contact-form-container" aria-labelledby="contact-form-heading">
            <div class="form-header">
                <div class="form-header-icon">
                    <svg><use xlink:href="#icon-form"></use></svg>
                </div>
                <div class="form-header-content">
                    <h2 id="contact-form-heading">Contact Form</h2>
                    <p class="form-description">Please complete the form below with detailed information about your inquiry. For admission-related questions, please include your application reference number if available.</p>
                </div>
            </div>

            <form action="<?php echo $baseUrl; ?>/contact/submit" method="POST" id="contactForm" class="contact-form" novalidate>
                <input type="hidden" name="csrf_token" value="<?php echo e($csrf_token); ?>">

                <div class="form-row">
                    <div class="form-field">
                        <label for="name" class="required">Full Legal Name</label>
                        <input type="text" id="name" name="name" class="form-input" required 
                               placeholder="Enter your complete name" 
                               value="<?php echo e($_POST['name'] ?? ''); ?>">
                        <div class="invalid-feedback">Please provide your full legal name.</div>
                    </div>

                    <div class="form-field">
                        <label for="email" class="required">Email Address</label>
                        <input type="email" id="email" name="email" class="form-input" required 
                               placeholder="name@example.com" 
                               value="<?php echo e($_POST['email'] ?? ''); ?>">
                        <div class="invalid-feedback">Please provide a valid email address.</div>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-field">
                        <label for="phone">Telephone Number</label>
                        <input type="tel" id="phone" name="phone" class="form-input" 
                               placeholder="+234 801 234 5678 or 08012345678"
                               value="<?php echo e($_POST['phone'] ?? ''); ?>">
                        <div class="field-hint">International format or local 10-11 digit number</div>
                        <div class="invalid-feedback">Please enter a valid phone number.</div>
                    </div>

                    <div class="form-field">
                        <label for="department">Department / Inquiry Type</label>
                        <select id="department" name="department" class="form-select">
                            <option value="general" <?php echo (($_POST['department'] ?? 'general') === 'general') ? 'selected' : ''; ?>>General Inquiry</option>
                            <option value="admissions" <?php echo (($_POST['department'] ?? '') === 'admissions') ? 'selected' : ''; ?>>Admissions & Applications</option>
                            <option value="academic" <?php echo (($_POST['department'] ?? '') === 'academic') ? 'selected' : ''; ?>>Academic Programs</option>
                            <option value="clinical" <?php echo (($_POST['department'] ?? '') === 'clinical') ? 'selected' : ''; ?>>Clinical Placements</option>
                            <option value="research" <?php echo (($_POST['department'] ?? '') === 'research') ? 'selected' : ''; ?>>Research Collaboration</option>
                            <option value="student" <?php echo (($_POST['department'] ?? '') === 'student') ? 'selected' : ''; ?>>Student Services</option>
                            <option value="international" <?php echo (($_POST['department'] ?? '') === 'international') ? 'selected' : ''; ?>>International Students</option>
                            <option value="faculty" <?php echo (($_POST['department'] ?? '') === 'faculty') ? 'selected' : ''; ?>>Faculty Affairs</option>
                            <option value="finance" <?php echo (($_POST['department'] ?? '') === 'finance') ? 'selected' : ''; ?>>Finance & Billing</option>
                            <option value="alumni" <?php echo (($_POST['department'] ?? '') === 'alumni') ? 'selected' : ''; ?>>Alumni Relations</option>
                        </select>
                    </div>
                </div>

                <div class="form-field">
                    <label for="subject" class="required">Subject Line</label>
                    <input type="text" id="subject" name="subject" class="form-input" required 
                           placeholder="Brief summary of your inquiry" 
                           value="<?php echo e($_POST['subject'] ?? ''); ?>">
                    <div class="invalid-feedback">Please provide a subject for your message.</div>
                </div>

                <div class="form-field">
                    <label for="message" class="required">Detailed Message</label>
                    <textarea id="message" name="message" class="form-textarea" required 
                              placeholder="Please provide specific details regarding your inquiry. Include any relevant reference numbers, dates, or previous correspondence."
                              rows="6"><?php echo e($_POST['message'] ?? ''); ?></textarea>
                    <div class="field-hint">Minimum 50 characters for detailed inquiries</div>
                    <div class="invalid-feedback">Please provide a detailed message (minimum 50 characters).</div>
                </div>

                <div class="form-field">
                    <label for="attachment">File Attachment (Optional)</label>
                    <input type="file" id="attachment" name="attachment" class="form-input" 
                           accept=".pdf,.doc,.docx,.jpg,.jpeg,.png">
                    <div class="field-hint">Maximum file size: 5MB. Accepted formats: PDF, DOC, JPG, PNG</div>
                </div>

                <div class="form-submit">
                    <button type="submit" class="submit-button">
                        <span class="icon">→</span>
                        <span>Submit Message</span>
                    </button>
                </div>
            </form>
        </section>

        <!-- Sidebar Column -->
        <aside class="sidebar-content">
            <!-- FAQ Card -->
            <section class="sidebar-card" aria-labelledby="faq-heading">
                <h3 id="faq-heading">Frequently Asked Questions</h3>
                <ul class="faq-list">
                    <li>What are the admission requirements for international students?</li>
                    <li>How do I apply for scholarships or financial aid?</li>
                    <li>What is the application deadline for the next academic session?</li>
                    <li>How can I schedule a campus tour or virtual visit?</li>
                    <li>Where can I find the academic calendar and important dates?</li>
                    <li>What are the clinical placement requirements for nursing students?</li>
                    <li>How do international students apply for student visas?</li>
                </ul>
                <a href="<?php echo $baseUrl; ?>/faq" class="faq-link">View Complete FAQ Section →</a>
            </section>

            <!-- Quick Links Card -->
            <section class="sidebar-card" aria-labelledby="quick-links-heading">
                <h3 id="quick-links-heading">Quick Resources</h3>
                <div class="quick-links">
                    <a href="<?php echo $baseUrl; ?>/admissions/requirements" class="quick-link">
                        <span class="link-icon">📋</span>
                        <span class="link-text">Admissions Requirements</span>
                        <span class="arrow">→</span>
                    </a>
                    <a href="<?php echo $baseUrl; ?>/programs/undergraduate" class="quick-link">
                        <span class="link-icon">🎓</span>
                        <span class="link-text">Undergraduate Programs</span>
                        <span class="arrow">→</span>
                    </a>
                    <a href="<?php echo $baseUrl; ?>/programs/postgraduate" class="quick-link">
                        <span class="link-icon">📚</span>
                        <span class="link-text">Postgraduate Programs</span>
                        <span class="arrow">→</span>
                    </a>
                    <a href="<?php echo $baseUrl; ?>/about/faculty" class="quick-link">
                        <span class="link-icon">👨‍🏫</span>
                        <span class="link-text">Faculty Directory</span>
                        <span class="arrow">→</span>
                    </a>
                    <a href="<?php echo $baseUrl; ?>/research/opportunities" class="quick-link">
                        <span class="link-icon">🔬</span>
                        <span class="link-text">Research Opportunities</span>
                        <span class="arrow">→</span>
                    </a>
                    <a href="<?php echo $baseUrl; ?>/news/events" class="quick-link">
                        <span class="link-icon">📅</span>
                        <span class="link-text">Upcoming Events</span>
                        <span class="arrow">→</span>
                    </a>
                </div>
            </section>

            <!-- Response Time Card -->
            <section class="sidebar-card response-info" aria-labelledby="response-heading">
                <h3 id="response-heading">Response Timeline</h3>
                <ul class="timeline-list">
                    <li>
                        <span class="category">General Inquiries</span>
                        <span class="timeframe">1-2 business days</span>
                    </li>
                    <li>
                        <span class="category">Admissions Questions</span>
                        <span class="timeframe">3-5 business days</span>
                    </li>
                    <li>
                        <span class="category">Academic Advising</span>
                        <span class="timeframe">5-7 business days</span>
                    </li>
                    <li>
                        <span class="category">Research Collaboration</span>
                        <span class="timeframe">7-10 business days</span>
                    </li>
                    <li>
                        <span class="category">International Students</span>
                        <span class="timeframe">5-7 business days</span>
                    </li>
                </ul>
                <p style="margin-top: var(--space-lg); font-size: 0.875rem; color: var(--color-gray-700);">
                    <strong>Note:</strong> Response times may vary during peak admission periods (January-March, August-October).
                </p>
            </section>
        </aside>
    </div>

    <!-- Campus Location Section -->
    <section id="map" class="map-section" aria-labelledby="map-heading">
        <div class="section-header">
            <h2 id="map-heading">Campus Location</h2>
            <p class="section-description">Our main campus is located in the Federal Capital Territory. The map below shows our location and nearby landmarks.</p>
        </div>

        <div class="map-container">
            <!-- Google Maps Embed would go here -->
            <!-- For now, using an overlay with information -->
            <div class="map-overlay">
                <div class="map-content">
                    <div class="icon">📍</div>
                    <h3><?php echo e($settings['address']); ?></h3>
                    <p>FCT College of Nursing Sciences main campus and administrative offices. Easily accessible by public transportation with parking facilities available.</p>
                    <div class="map-actions">
                        <a href="https://maps.google.com/?q=<?php echo urlencode($settings['address']); ?>" 
                           target="_blank" 
                           class="map-button primary">
                            Open in Google Maps
                        </a>
                        <a href="<?php echo $baseUrl; ?>/about/campus" class="map-button">
                            Virtual Campus Tour
                        </a>
                        <a href="<?php echo $baseUrl; ?>/about/directions" class="map-button">
                            Parking & Directions
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>

<script>
// Enhanced Form Validation with Real-time Feedback
document.addEventListener('DOMContentLoaded', function() {
    'use strict';
    
    const form = document.getElementById('contactForm');
    if (!form) return;
    
    const inputs = form.querySelectorAll('.form-input, .form-select, .form-textarea');
    
    // Validation Functions
    function validateEmail(email) {
        const re = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
        return re.test(String(email).toLowerCase());
    }
    
    function validatePhone(phone) {
        const cleanPhone = phone.replace(/\s+/g, '');
        // Accepts: +234XXXXXXXXXX, 0XXXXXXXXXX, 234XXXXXXXXXX
        const re = /^(\+?234|0)?[789]\d{9}$/;
        return re.test(cleanPhone);
    }
    
    function validateName(name) {
        return name.trim().length >= 2;
    }
    
    function validateSubject(subject) {
        return subject.trim().length >= 5;
    }
    
    function validateMessage(message) {
        return message.trim().length >= 50;
    }
    
    // Real-time Validation
    function validateField(field) {
        const value = field.value.trim();
        let isValid = true;
        let errorMessage = '';
        
        field.classList.remove('is-invalid');
        
        if (field.hasAttribute('required') && !value) {
            isValid = false;
            errorMessage = 'This field is required';
        } else if (field.type === 'email' && value && !validateEmail(value)) {
            isValid = false;
            errorMessage = 'Please enter a valid email address';
        } else if (field.id === 'phone' && value && !validatePhone(value)) {
            isValid = false;
            errorMessage = 'Please enter a valid Nigerian phone number';
        } else if (field.id === 'name' && value && !validateName(value)) {
            isValid = false;
            errorMessage = 'Name must be at least 2 characters';
        } else if (field.id === 'subject' && value && !validateSubject(value)) {
            isValid = false;
            errorMessage = 'Subject must be at least 5 characters';
        } else if (field.id === 'message' && value && !validateMessage(value)) {
            isValid = false;
            errorMessage = 'Message must be at least 50 characters';
        }
        
        if (!isValid) {
            field.classList.add('is-invalid');
            let feedback = field.nextElementSibling;
            while (feedback && !feedback.classList.contains('invalid-feedback')) {
                feedback = feedback.nextElementSibling;
            }
            if (feedback && feedback.classList.contains('invalid-feedback')) {
                feedback.textContent = errorMessage;
            }
        }
        
        return isValid;
    }
    
    // Event Listeners for Real-time Validation
    inputs.forEach(input => {
        // Validate on blur
        input.addEventListener('blur', function() {
            validateField(this);
        });
        
        // Clear validation on input
        input.addEventListener('input', function() {
            this.classList.remove('is-invalid');
        });
    });
    
    // Form Submission Validation
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        
        let isValid = true;
        const invalidFields = [];
        
        // Validate all fields
        inputs.forEach(input => {
            if (!validateField(input)) {
                isValid = false;
                invalidFields.push(input);
            }
        });
        
        if (!isValid) {
            // Scroll to first invalid field
            if (invalidFields.length > 0) {
                invalidFields[0].scrollIntoView({
                    behavior: 'smooth',
                    block: 'center'
                });
                invalidFields[0].focus();
                
                // Show error summary
                showErrorSummary(invalidFields.length);
            }
            return false;
        }
        
        // If valid, submit the form
        this.submit();
    });
    
    // Error Summary Function
    function showErrorSummary(errorCount) {
        // Remove existing error summary if present
        const existingSummary = document.querySelector('.error-summary');
        if (existingSummary) {
            existingSummary.remove();
        }
        
        // Create error summary
        const summary = document.createElement('div');
        summary.className = 'alert alert-danger error-summary';
        summary.innerHTML = `
            <div class="alert-icon">!</div>
            <div class="alert-content">
                <strong>Please correct ${errorCount} error${errorCount > 1 ? 's' : ''} below:</strong>
                Your form contains invalid information. Please review the highlighted fields.
            </div>
        `;
        
        // Insert after alert container
        const alertContainer = document.querySelector('.alert-container');
        if (alertContainer) {
            alertContainer.appendChild(summary);
        } else {
            const formContainer = document.querySelector('.contact-form-container');
            formContainer.insertBefore(summary, formContainer.firstChild);
        }
        
        // Auto-remove after 5 seconds
        setTimeout(() => {
            summary.style.opacity = '0';
            summary.style.transition = 'opacity 0.3s ease';
            setTimeout(() => summary.remove(), 300);
        }, 5000);
    }
    
    // Phone Number Formatting
    const phoneInput = document.getElementById('phone');
    if (phoneInput) {
        phoneInput.addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');
            
            if (value.startsWith('0') && value.length > 0) {
                // Format as 0XXX XXX XXXX
                if (value.length <= 4) {
                    value = value;
                } else if (value.length <= 7) {
                    value = value.substring(0, 4) + ' ' + value.substring(4);
                } else if (value.length <= 10) {
                    value = value.substring(0, 4) + ' ' + value.substring(4, 7) + ' ' + value.substring(7);
                } else {
                    value = value.substring(0, 4) + ' ' + value.substring(4, 7) + ' ' + value.substring(7, 11);
                }
            } else if (value.startsWith('234') && value.length > 3) {
                // Format as +234 XXX XXX XXXX
                if (value.length <= 3) {
                    value = '+' + value;
                } else if (value.length <= 6) {
                    value = '+234 ' + value.substring(3);
                } else if (value.length <= 9) {
                    value = '+234 ' + value.substring(3, 6) + ' ' + value.substring(6);
                } else {
                    value = '+234 ' + value.substring(3, 6) + ' ' + value.substring(6, 9) + ' ' + value.substring(9, 13);
                }
            } else if (value.length > 0) {
                // Format as international
                value = '+' + value;
                if (value.length > 4) value = value.substring(0, 4) + ' ' + value.substring(4);
                if (value.length > 8) value = value.substring(0, 8) + ' ' + value.substring(8);
                if (value.length > 12) value = value.substring(0, 12) + ' ' + value.substring(12);
            }
            
            e.target.value = value;
        });
    }
    
    // Character Counter for Message
    const messageInput = document.getElementById('message');
    if (messageInput) {
        const counter = document.createElement('div');
        counter.className = 'field-hint character-counter';
        counter.style.marginTop = '4px';
        counter.style.fontSize = '0.75rem';
        counter.textContent = 'Characters: 0/50 minimum';
        messageInput.parentNode.insertBefore(counter, messageInput.nextSibling.nextSibling);
        
        messageInput.addEventListener('input', function() {
            const count = this.value.trim().length;
            counter.textContent = `Characters: ${count}/50 minimum`;
            counter.style.color = count >= 50 ? 'var(--color-success)' : 'var(--color-danger)';
        });
    }
    
    // Form Reset Handler
    form.addEventListener('reset', function() {
        inputs.forEach(input => {
            input.classList.remove('is-invalid');
        });
        
        const errorSummary = document.querySelector('.error-summary');
        if (errorSummary) {
            errorSummary.remove();
        }
    });
    
    // Print Form Functionality
    const printButton = document.createElement('button');
    printButton.type = 'button';
    printButton.className = 'map-button';
    printButton.innerHTML = 'Print This Form';
    printButton.style.marginTop = '1rem';
    printButton.addEventListener('click', function() {
        window.print();
    });
    
    const formSubmit = document.querySelector('.form-submit');
    if (formSubmit) {
        formSubmit.appendChild(printButton);
    }
});
</script>