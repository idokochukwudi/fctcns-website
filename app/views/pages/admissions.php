<?php
/**
 * Admissions Page View Template
 * Redesigned to match research-publications.php design system (v5.5)
 * Enhanced with clearer step-by-step application process
 *
 * Fonts   : Cormorant Garamond (display) + Outfit (body) + JetBrains Mono (mono)
 * Palette : identical CSS custom-properties as research pages
 * Layout  : fluid --gutter, full-bleed hero, max-width 1400px
 *
 * @package FCTCNS
 * @version 6.1
 */

extract($data ?? []);

if (!function_exists('e')) {
    function e($text) {
        return htmlspecialchars($text ?? '', ENT_QUOTES, 'UTF-8');
    }
}

$baseUrl = $baseUrl ?? '/';
$page_title = $page_title ?? 'Admissions 2025/2026 | FCT College of Nursing Sciences';
$page_description = $page_description ?? 'Apply for ND/HND Nursing Programme 2025/2026 academic session.';
$applicationPortal = 'https://consap.fcthhss.abj.gov.ng';
$heroImagePath = rtrim($baseUrl, '/') . '/assets/images/admissions/admissions-hero.jpg';
?>
<!-- Google Fonts — same as research pages -->
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@400;500;600;700&family=Outfit:wght@300;400;500;600;700&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<style>
    /* EMERGENCY FULL WIDTH OVERRIDE */
body .main-content {
    padding: 0 !important;
    max-width: 100vw !important;
}

.hero-section {
    width: 100vw !important;
    position: relative !important;
    left: 50% !important;
    right: 50% !important;
    margin-left: -50vw !important;
    margin-right: -50vw !important;
}
    </style>
<style>
/* ==========================================================================
   RESET
   ========================================================================== */
*, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

html, body {
    margin: 0 !important;
    padding: 0 !important;
    width: 100%;
    overflow-x: hidden;
}

body {
    min-height: 100vh;
    background: #fff;
    -webkit-font-smoothing: antialiased;
    -moz-osx-font-smoothing: grayscale;
}

/* ==========================================================================
   DESIGN TOKENS — identical to research-publications.php
   ========================================================================== */
:root {
    --ink:          #1A1F2E;
    --ink-mid:      #2A3042;
    --ink-soft:     #3A4055;
    --slate:        #5B677B;
    --mist:         #8E9AAC;
    --border:       #E9EDF2;
    --surface:      #F7F9FC;
    --white:        #FFFFFF;

    --purple:       #8B7BB8;
    --purple-dark:  #6D5C9E;
    --purple-light: #B2A4D4;
    --purple-pale:  #F3F0FA;

    --gold:         #C9A44A;
    --gold-light:   #D8B86C;
    --gold-pale:    #FDF8ED;

    --red:          #C0392B;
    --red-pale:     #FDF3F2;
    --green:        #5D9B8C;
    --green-pale:   #EEF7F5;

    --blue:         #2A6F97;
    --blue-light:   #4A8BB7;
    --blue-pale:    #E8F0FE;

    --font-display: 'Cormorant Garamond', Georgia, serif;
    --font-body:    'Outfit', system-ui, sans-serif;
    --font-mono:    'JetBrains Mono', monospace;

    --radius-sm:    6px;
    --radius-md:    12px;
    --radius-lg:    20px;
    --radius-xl:    28px;
    --radius-full:  9999px;

    --shadow-xs:    0 1px 3px rgba(0,0,0,0.04);
    --shadow-sm:    0 2px 8px rgba(0,0,0,0.05);
    --shadow-md:    0 6px 24px rgba(0,0,0,0.06);
    --shadow-lg:    0 16px 48px rgba(0,0,0,0.08);
    --shadow-xl:    0 32px 80px rgba(0,0,0,0.10);

    /* fluid gutter — same as research pages */
    --gutter:        clamp(1.25rem, 5vw, 6rem);
    --container-max: 1400px;
}

/* ==========================================================================
   ROOT SCOPE
   ========================================================================== */
.adm-root {
    font-family: var(--font-body);
    color: var(--ink);
    background: var(--white);
    overflow-x: hidden;
    width: 100%;
}

/* ==========================================================================
   CONTAINER
   ========================================================================== */
.adm-container {
    width: 100%;
    max-width: var(--container-max);
    margin-left: auto;
    margin-right: auto;
    padding-left:  var(--gutter);
    padding-right: var(--gutter);
}

/* ==========================================================================
   BREADCRUMB — exact match to research pages
   ========================================================================== */
.adm-breadcrumb {
    background: var(--white);
    border-bottom: 1px solid var(--border);
    padding: 0.75rem 0;
}

.adm-breadcrumb-list {
    display: flex;
    align-items: center;
    gap: 0.4rem;
    list-style: none;
    font-size: 0.8rem;
    flex-wrap: wrap;
}

.adm-breadcrumb-list a {
    color: var(--purple-dark);
    text-decoration: none;
    font-weight: 500;
    transition: color 0.18s;
}
.adm-breadcrumb-list a:hover { color: var(--purple); text-decoration: underline; }
.adm-breadcrumb-sep     { color: var(--mist); }
.adm-breadcrumb-current { color: var(--slate); }

/* ==========================================================================
   HERO — full-bleed, matches research list hero exactly
   ========================================================================== */
.adm-hero {
    position: relative;
    background: linear-gradient(145deg, #2A2A42 0%, #383856 100%);
    overflow: hidden;
    padding-top:    clamp(4rem, 8vw, 7rem);
    padding-bottom: clamp(4rem, 8vw, 7rem);
    min-height: 560px;
    display: flex;
    align-items: center;
    width: 100%;
}

.adm-hero-bg {
    position: absolute;
    inset: 0;
    background-image: url('<?php echo $heroImagePath; ?>');
    background-size: cover;
    background-position: center;
    opacity: 0.18;
    z-index: 0;
}

.adm-hero::after {
    content: '';
    position: absolute;
    inset: 0;
    background: radial-gradient(circle at 70% 30%, rgba(139,123,184,0.18) 0%, transparent 60%);
    z-index: 1;
    pointer-events: none;
}

.adm-hero-inner {
    width: 100%;
    max-width: var(--container-max);
    margin-left:  auto;
    margin-right: auto;
    padding-left:  var(--gutter);
    padding-right: var(--gutter);
    position: relative;
    z-index: 2;
    display: flex;
    flex-direction: column;
    align-items: flex-start;
    gap: 2.5rem;
}

@media (min-width: 992px) {
    .adm-hero-inner {
        flex-direction: row;
        align-items: center;
        justify-content: space-between;
    }
}

.adm-hero-left  { width: 100%; max-width: 660px; }
.adm-hero-right { width: 100%; }

@media (min-width: 992px) {
    .adm-hero-left  { width: 58%; max-width: none; }
    .adm-hero-right { width: 38%; }
}

/* Badge — matches rp-hero-badge */
.adm-hero-badge {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    background: rgba(139,123,184,0.2);
    backdrop-filter: blur(8px);
    -webkit-backdrop-filter: blur(8px);
    border: 1px solid rgba(201,164,74,0.35);
    padding: 0.45rem 1.1rem;
    border-radius: 50px;
    margin-bottom: 1.1rem;
}

.adm-hero-badge-icon {
    width: 22px; height: 22px;
    background: var(--purple);
    border-radius: 50%;
    display: flex; align-items: center; justify-content: center;
    color: white;
    font-size: 0.65rem;
}

.adm-hero-badge-text {
    font-family: var(--font-mono);
    font-size: 0.68rem;
    font-weight: 500;
    letter-spacing: 0.1em;
    text-transform: uppercase;
    color: var(--gold);
}

/* Title */
.adm-hero-title {
    font-family: var(--font-display);
    font-size: clamp(2.2rem, 4.5vw, 4rem);
    font-weight: 700;
    line-height: 1.1;
    color: white;
    margin-bottom: 1rem;
    letter-spacing: -0.01em;
    text-shadow: 0 2px 8px rgba(0,0,0,0.2);
}

.adm-hero-title .accent { color: var(--gold-light); font-style: italic; }

.adm-hero-subtitle {
    font-size: clamp(0.95rem, 1.5vw, 1.15rem);
    color: rgba(255,255,255,0.82);
    font-weight: 300;
    max-width: 540px;
    line-height: 1.65;
    margin-bottom: 1.75rem;
}

/* Status chip in hero */
.adm-hero-status {
    display: inline-flex;
    align-items: center;
    gap: 7px;
    padding: 0.5rem 1.1rem;
    background: rgba(192,57,43,0.25);
    border: 1px solid rgba(192,57,43,0.4);
    border-radius: var(--radius-full);
    font-family: var(--font-mono);
    font-size: 0.7rem;
    font-weight: 500;
    letter-spacing: 0.08em;
    text-transform: uppercase;
    color: #ffb3ae;
}

.adm-hero-status i { color: #ff7b72; }

/* Stats panel — matches rp-hero-stats */
.adm-hero-stats {
    display: flex;
    flex-wrap: wrap;
    gap: clamp(1rem, 3vw, 2rem);
    background: rgba(0,0,0,0.35);
    backdrop-filter: blur(10px);
    -webkit-backdrop-filter: blur(10px);
    border: 1px solid rgba(255,255,255,0.18);
    border-radius: var(--radius-lg);
    padding: clamp(1.25rem, 3vw, 1.75rem) clamp(1.5rem, 3vw, 2rem);
}

.adm-stat {
    display: flex;
    flex-direction: column;
    gap: 4px;
}

.adm-stat-value {
    font-family: var(--font-display);
    font-size: clamp(1.8rem, 3vw, 2.4rem);
    font-weight: 700;
    color: #FFE082;
    line-height: 1;
    text-shadow: 0 1px 4px rgba(0,0,0,0.3);
}

.adm-stat-label {
    font-size: 0.72rem;
    font-weight: 600;
    letter-spacing: 0.08em;
    text-transform: uppercase;
    color: rgba(255,255,255,0.92);
}

/* ==========================================================================
   SECTION SPACING SYSTEM
   ========================================================================== */
.adm-section {
    padding-top:    clamp(2.5rem, 5vw, 4rem);
    padding-bottom: clamp(2.5rem, 5vw, 4rem);
}

.adm-section--alt { background: var(--surface); }
.adm-section--bordered {
    border-top: 1px solid var(--border);
    border-bottom: 1px solid var(--border);
}

/* ==========================================================================
   SECTION HEADER — matches rp-section-header
   ========================================================================== */
.adm-section-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 1rem;
    margin-bottom: 2rem;
    padding-bottom: 1rem;
    border-bottom: 2px solid var(--border);
    border-image: linear-gradient(90deg, var(--purple) 110px, var(--border) 110px) 1;
    flex-wrap: wrap;
}

.adm-section-title {
    display: flex;
    align-items: center;
    gap: 10px;
    font-family: var(--font-display);
    font-size: clamp(1.35rem, 2.5vw, 1.9rem);
    font-weight: 700;
    color: var(--ink);
    letter-spacing: -0.01em;
}

.adm-section-pip {
    width: 8px; height: 8px;
    border-radius: 50%;
    background: var(--purple);
    flex-shrink: 0;
}

.adm-process-badge {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 0.35rem 1.2rem;
    background: var(--purple-pale);
    border: 1px solid var(--purple-light);
    border-radius: var(--radius-full);
    font-family: var(--font-mono);
    font-size: 0.75rem;
    font-weight: 500;
    color: var(--purple-dark);
}

/* Centred section header variant */
.adm-section-header--center {
    flex-direction: column;
    text-align: center;
    border-image: none;
    border-bottom: none;
    padding-bottom: 0;
    margin-bottom: 0;
}

.adm-section-header--center::after {
    content: '';
    display: block;
    width: 80px;
    height: 3px;
    background: linear-gradient(90deg, var(--purple), var(--purple-light));
    border-radius: 2px;
    margin-top: 0.75rem;
}

.adm-section-subtitle {
    font-size: clamp(0.95rem, 1.5vw, 1.1rem);
    color: var(--slate);
    line-height: 1.65;
    max-width: 640px;
    margin-top: 0.85rem;
    font-weight: 400;
}

/* ==========================================================================
   BUTTONS — mirrors rp-btn
   ========================================================================== */
.adm-btn {
    display: inline-flex;
    align-items: center;
    gap: 7px;
    padding: 0.65rem 1.5rem;
    border-radius: var(--radius-sm);
    font-family: var(--font-body);
    font-size: 0.9rem;
    font-weight: 500;
    text-decoration: none;
    border: none;
    cursor: pointer;
    transition: all 0.22s ease;
    letter-spacing: 0.01em;
    white-space: nowrap;
}

.adm-btn--purple { background: var(--purple); color: white; }
.adm-btn--purple:hover {
    background: var(--purple-dark); color: white;
    transform: translateY(-1px);
    box-shadow: 0 4px 14px rgba(139,123,184,0.32);
}

.adm-btn--gold { background: var(--gold); color: white; }
.adm-btn--gold:hover {
    background: var(--gold-light); color: white;
    transform: translateY(-1px);
    box-shadow: 0 4px 14px rgba(201,164,74,0.3);
}

.adm-btn--outline { background: transparent; color: var(--purple); border: 1.5px solid var(--purple); }
.adm-btn--outline:hover { background: var(--purple); color: white; transform: translateY(-1px); }

.adm-btn--surface { background: var(--surface); color: var(--ink-soft); border: 1px solid var(--border); }
.adm-btn--surface:hover { background: var(--border); color: var(--ink); }

.adm-btn--disabled {
    background: var(--border);
    color: var(--mist);
    cursor: not-allowed;
    opacity: 0.8;
    pointer-events: none;
}

.adm-btn--lg { padding: 0.85rem 2rem; font-size: 1rem; }

/* ==========================================================================
   ALERT BANNERS
   ========================================================================== */
.adm-alert {
    border-radius: var(--radius-lg);
    padding: clamp(1.25rem, 3vw, 1.75rem);
    border: 1px solid;
    display: flex;
    gap: 1rem;
    align-items: flex-start;
}

.adm-alert--error {
    background: var(--red-pale);
    border-color: rgba(192,57,43,0.2);
    border-left: 4px solid var(--red);
}

.adm-alert--warning {
    background: var(--gold-pale);
    border-color: rgba(201,164,74,0.2);
    border-left: 4px solid var(--gold);
}

.adm-alert-icon {
    width: 38px; height: 38px;
    border-radius: 50%;
    display: flex; align-items: center; justify-content: center;
    font-size: 1.1rem;
    flex-shrink: 0;
}

.adm-alert--error  .adm-alert-icon { background: rgba(192,57,43,0.12); color: var(--red); }
.adm-alert--warning .adm-alert-icon { background: rgba(201,164,74,0.15); color: var(--gold); }

.adm-alert-body {}

.adm-alert-title {
    font-family: var(--font-display);
    font-size: 1.25rem;
    font-weight: 700;
    margin-bottom: 0.5rem;
}

.adm-alert--error   .adm-alert-title { color: var(--red); }
.adm-alert--warning .adm-alert-title { color: #7a5d1a; }

.adm-alert-text {
    font-size: 0.95rem;
    line-height: 1.7;
    color: var(--ink-soft);
}

.adm-alert-text p + p { margin-top: 0.5rem; }
.adm-alert-text strong { color: var(--ink); }

/* ==========================================================================
   INFO CARDS GRID — mirrors rp-stat-card / rp-area-card
   ========================================================================== */
.adm-cards-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
    gap: 1.4rem;
    margin-top: 2rem;
}

.adm-card {
    background: var(--white);
    border: 1px solid var(--border);
    border-radius: var(--radius-lg);
    padding: clamp(1.25rem, 3vw, 1.75rem);
    transition: transform 0.28s ease, box-shadow 0.28s ease, border-color 0.28s ease;
    position: relative;
    overflow: hidden;
}

.adm-card::before {
    content: '';
    position: absolute;
    top: 0; left: 0; right: 0;
    height: 3px;
    background: linear-gradient(90deg, var(--purple), var(--purple-light));
    transform: scaleX(0);
    transform-origin: left;
    transition: transform 0.28s ease;
    border-radius: 3px 3px 0 0;
}

.adm-card:hover {
    transform: translateY(-4px);
    box-shadow: var(--shadow-lg);
    border-color: rgba(139,123,184,0.2);
}

.adm-card:hover::before { transform: scaleX(1); }

.adm-card-icon {
    width: 52px; height: 52px;
    background: var(--purple-pale);
    color: var(--purple);
    border-radius: 50%;
    display: flex; align-items: center; justify-content: center;
    font-size: 1.3rem;
    margin-bottom: 1.1rem;
    transition: all 0.28s ease;
}

.adm-card:hover .adm-card-icon { background: var(--purple); color: white; }

.adm-card-title {
    font-family: var(--font-display);
    font-size: 1.3rem;
    font-weight: 700;
    color: var(--ink);
    margin-bottom: 0.75rem;
    letter-spacing: -0.01em;
}

.adm-card-body {
    font-size: 0.9rem;
    color: var(--slate);
    line-height: 1.7;
}

.adm-card-body p { margin-bottom: 0.4rem; }
.adm-card-body p:last-child { margin-bottom: 0; }
.adm-card-body strong { color: var(--ink-soft); font-weight: 600; }

/* ==========================================================================
   REQUIREMENTS LIST
   ========================================================================== */
.adm-req-panel {
    background: var(--white);
    border: 1px solid var(--border);
    border-radius: var(--radius-lg);
    padding: clamp(1.5rem, 3vw, 2.25rem);
    box-shadow: var(--shadow-sm);
    margin-top: 2rem;
    border-left: 4px solid var(--purple);
}

.adm-req-list { list-style: none; }

.adm-req-item {
    display: flex;
    align-items: flex-start;
    gap: 0.85rem;
    padding: 0.85rem 0;
    border-bottom: 1px solid var(--border);
    font-size: 0.95rem;
    color: var(--ink-soft);
    line-height: 1.6;
}

.adm-req-item:last-child { border-bottom: none; }

.adm-req-check {
    width: 22px; height: 22px;
    background: var(--purple-pale);
    color: var(--purple);
    border-radius: 50%;
    display: flex; align-items: center; justify-content: center;
    font-size: 0.65rem;
    flex-shrink: 0;
    margin-top: 2px;
}

.adm-req-item strong { color: var(--ink); }

/* ==========================================================================
   REDESIGNED PROCESS OVERVIEW CARDS
   ========================================================================== */
.adm-process-overview {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 1rem;
    margin-bottom: 2rem;
}

@media (max-width: 640px) {
    .adm-process-overview {
        grid-template-columns: 1fr;
        gap: 0.75rem;
    }
}

.adm-overview-card {
    background: var(--white);
    border: 1px solid var(--border);
    border-radius: var(--radius-lg);
    padding: 1.25rem 1rem;
    display: flex;
    align-items: center;
    gap: 1rem;
    transition: all 0.2s ease;
}

.adm-overview-card:hover {
    transform: translateY(-2px);
    box-shadow: var(--shadow-md);
    border-color: var(--purple-light);
}

.adm-overview-icon {
    width: 48px;
    height: 48px;
    border-radius: var(--radius-md);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.25rem;
    flex-shrink: 0;
}

.adm-overview-content {
    flex: 1;
}

.adm-overview-label {
    display: block;
    font-size: 0.75rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    color: var(--slate);
    margin-bottom: 0.15rem;
}

.adm-overview-value {
    display: block;
    font-family: var(--font-display);
    font-size: 1.2rem;
    font-weight: 700;
    color: var(--ink);
    line-height: 1.2;
}

/* ==========================================================================
   REDESIGNED TIMELINE STEP INDICATOR
   ========================================================================== */
.adm-steps-showcase {
    margin: 2rem 0 2.5rem;
}

.adm-steps-track {
    display: flex;
    align-items: stretch;
    background: var(--white);
    border: 1px solid var(--border);
    border-radius: var(--radius-xl);
    overflow: hidden;
    box-shadow: var(--shadow-sm);
}

@media (max-width: 640px) {
    .adm-steps-track {
        flex-direction: column;
        border-radius: var(--radius-lg);
    }
}

.adm-track-step {
    flex: 1;
    padding: 1.5rem 0.5rem;
    text-align: center;
    position: relative;
    background: var(--white);
    transition: all 0.2s ease;
    border-right: 1px solid var(--border);
}

.adm-track-step:last-child {
    border-right: none;
}

@media (max-width: 640px) {
    .adm-track-step {
        border-right: none;
        border-bottom: 1px solid var(--border);
        padding: 1rem 0.5rem;
    }
    .adm-track-step:last-child {
        border-bottom: none;
    }
}

.adm-track-step.active {
    background: var(--purple-pale);
    position: relative;
}

.adm-track-step.active::after {
    content: '';
    position: absolute;
    bottom: -1px;
    left: 0;
    right: 0;
    height: 3px;
    background: var(--purple);
}

@media (max-width: 640px) {
    .adm-track-step.active::after {
        bottom: auto;
        top: 0;
        height: 100%;
        width: 3px;
        left: 0;
        right: auto;
    }
}

.adm-step-marker {
    width: 36px;
    height: 36px;
    background: var(--purple-pale);
    border: 2px solid var(--purple-light);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 0.75rem;
    font-weight: 700;
    color: var(--purple-dark);
    transition: all 0.2s ease;
}

.adm-track-step.active .adm-step-marker {
    background: var(--purple);
    border-color: var(--purple);
    color: white;
    transform: scale(1.1);
}

.adm-step-label {
    font-family: var(--font-display);
    font-size: 0.95rem;
    font-weight: 600;
    color: var(--ink-soft);
    margin-bottom: 0.25rem;
}

.adm-step-desc {
    font-size: 0.7rem;
    color: var(--mist);
    text-transform: uppercase;
    letter-spacing: 0.05em;
}

/* ==========================================================================
   REDESIGNED STEP DETAIL CARDS — CLEANER LAYOUT
   ========================================================================== */
.adm-steps-detailed {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 1.25rem;
    margin: 2rem 0;
}

@media (max-width: 768px) {
    .adm-steps-detailed {
        grid-template-columns: 1fr;
        gap: 1rem;
    }
}

.adm-step-detail-card {
    background: var(--white);
    border: 1px solid var(--border);
    border-radius: var(--radius-lg);
    overflow: hidden;
    transition: all 0.25s ease;
    display: flex;
    flex-direction: column;
}

.adm-step-detail-card:hover {
    transform: translateY(-3px);
    box-shadow: var(--shadow-lg);
    border-color: var(--purple-light);
}

.adm-detail-header {
    padding: 1.25rem 1.25rem 0.75rem;
    display: flex;
    align-items: center;
    gap: 0.75rem;
    border-bottom: 1px solid var(--border);
}

.adm-detail-num {
    width: 42px;
    height: 42px;
    background: var(--purple-pale);
    border-radius: var(--radius-md);
    display: flex;
    align-items: center;
    justify-content: center;
    font-family: var(--font-display);
    font-size: 1.5rem;
    font-weight: 700;
    color: var(--purple-dark);
    flex-shrink: 0;
}

.adm-detail-title {
    font-family: var(--font-display);
    font-size: 1.15rem;
    font-weight: 700;
    color: var(--ink);
    line-height: 1.2;
}

.adm-detail-content {
    padding: 1.25rem;
    flex: 1;
}

.adm-detail-list {
    list-style: none;
}

.adm-detail-list li {
    display: flex;
    align-items: flex-start;
    gap: 0.75rem;
    padding: 0.5rem 0;
    font-size: 0.9rem;
    color: var(--ink-soft);
    line-height: 1.5;
    border-bottom: 1px dashed var(--border);
}

.adm-detail-list li:last-child {
    border-bottom: none;
}

.adm-detail-list li i {
    width: 18px;
    height: 18px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--purple);
    font-size: 0.8rem;
    flex-shrink: 0;
    margin-top: 2px;
}

.adm-detail-list strong {
    color: var(--ink);
    font-weight: 600;
}

.adm-portal-badge {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    background: var(--purple-pale);
    color: var(--purple-dark);
    border: 1px solid var(--purple-light);
    border-radius: var(--radius-full);
    font-family: var(--font-mono);
    font-size: 0.7rem;
    padding: 0.2rem 0.8rem;
    margin-left: 0.5rem;
    text-decoration: none;
    transition: all 0.2s;
    white-space: nowrap;
}

.adm-portal-badge:hover {
    background: var(--purple);
    color: white;
    border-color: var(--purple);
}

/* ==========================================================================
   QUICK ACTIONS BAR
   ========================================================================== */
.adm-quick-actions {
    display: flex;
    flex-wrap: wrap;
    align-items: center;
    gap: 1rem;
    background: var(--surface);
    border: 1px solid var(--border);
    border-radius: var(--radius-lg);
    padding: 1rem 1.5rem;
    margin: 2rem 0;
}

@media (max-width: 640px) {
    .adm-quick-actions {
        flex-direction: column;
        align-items: stretch;
        text-align: center;
    }
}

.adm-quick-actions span {
    font-size: 0.9rem;
    color: var(--ink-soft);
}

.adm-quick-actions .adm-btn {
    padding: 0.5rem 1.25rem;
    font-size: 0.85rem;
}

/* ==========================================================================
   PORTAL CTA CARD — matches rp-featured dark card style
   ========================================================================== */
.adm-portal-card {
    background: linear-gradient(160deg, #2A3042 0%, #3A4055 100%);
    border-radius: var(--radius-xl);
    padding: clamp(1.75rem, 4vw, 2.75rem);
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 2rem;
    flex-wrap: wrap;
    box-shadow: var(--shadow-xl);
    position: relative;
    overflow: hidden;
    margin-top: 2.5rem;
}

.adm-portal-card::before {
    content: '';
    position: absolute;
    left: 0; top: 15%; bottom: 15%;
    width: 3px;
    background: linear-gradient(to bottom, var(--purple-light), var(--purple));
    border-radius: 3px;
}

.adm-portal-card-content { flex: 1; min-width: 220px; }

.adm-portal-card-tag {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    background: var(--purple);
    color: white;
    font-family: var(--font-mono);
    font-size: 0.62rem;
    font-weight: 500;
    letter-spacing: 0.15em;
    text-transform: uppercase;
    padding: 4px 12px;
    border-radius: 4px;
    margin-bottom: 0.85rem;
}

.adm-portal-card-title {
    font-family: var(--font-display);
    font-size: clamp(1.4rem, 2.5vw, 2rem);
    font-weight: 700;
    color: white;
    margin-bottom: 0.5rem;
    letter-spacing: -0.01em;
}

.adm-portal-card-desc {
    font-size: 0.95rem;
    color: rgba(255,255,255,0.7);
    line-height: 1.6;
}

.adm-portal-card-actions {
    display: flex;
    gap: 0.85rem;
    flex-wrap: wrap;
}

.adm-btn--ghost {
    background: transparent;
    color: white;
    border: 1.5px solid rgba(255,255,255,0.35);
}
.adm-btn--ghost:hover { border-color: white; background: rgba(255,255,255,0.1); color: white; }

/* ==========================================================================
   CONTACT CARDS — same as adm-card but with email/phone focus
   ========================================================================== */
.adm-contact-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
    gap: 1.4rem;
    margin-top: 2rem;
}

/* Reuse adm-card for contact items */

/* ==========================================================================
   NOTE / INFO BOX
   ========================================================================== */
.adm-note {
    background: var(--surface);
    border: 1px dashed var(--border);
    border-radius: var(--radius-md);
    padding: 1rem 1.25rem;
    font-size: 0.88rem;
    color: var(--slate);
    line-height: 1.65;
    text-align: center;
    margin-top: 1.75rem;
}

.adm-note i { color: var(--gold); margin-right: 5px; }
.adm-note strong { color: var(--ink-soft); }

/* ==========================================================================
   CTA STRIP
   ========================================================================== */
.adm-cta-strip {
    background: var(--surface);
    border-top: 1px solid var(--border);
    padding: clamp(2.5rem, 5vw, 4rem) 0;
    text-align: center;
}

.adm-cta-strip .adm-section-title { justify-content: center; }

.adm-cta-actions {
    display: flex;
    flex-wrap: wrap;
    gap: 0.85rem;
    justify-content: center;
    margin-top: 1.75rem;
}

.adm-cta-note {
    margin-top: 1.5rem;
    font-size: 0.88rem;
    color: var(--slate);
    line-height: 1.65;
}

.adm-cta-note i { color: var(--purple-light); margin-right: 5px; }

/* ==========================================================================
   ANIMATIONS
   ========================================================================== */
@keyframes adm-fadeIn {
    from { opacity: 0; transform: translateY(14px); }
    to   { opacity: 1; transform: translateY(0); }
}

.adm-card,
.adm-step-detail-card,
.adm-overview-card {
    animation: adm-fadeIn 0.4s ease both;
}

.adm-card:nth-child(1),
.adm-step-detail-card:nth-child(1) { animation-delay: 0.05s; }
.adm-card:nth-child(2),
.adm-step-detail-card:nth-child(2) { animation-delay: 0.10s; }
.adm-card:nth-child(3),
.adm-step-detail-card:nth-child(3) { animation-delay: 0.15s; }
.adm-card:nth-child(4),
.adm-step-detail-card:nth-child(4) { animation-delay: 0.20s; }

@media (prefers-reduced-motion: reduce) {
    .adm-card, .adm-step-detail-card, .adm-overview-card { animation: none !important; transition: none !important; }
}

/* ==========================================================================
   RESPONSIVE EXTRAS
   ========================================================================== */
@media (max-width: 480px) {
    .adm-cta-actions { flex-direction: column; align-items: center; }
    .adm-portal-card { flex-direction: column; }
    .adm-portal-card-actions { flex-direction: column; width: 100%; }
    .adm-portal-card-actions .adm-btn { justify-content: center; width: 100%; }
}

/* Print */
@media print {
    .adm-hero, .adm-btn, .adm-portal-card { display: none !important; }
    .adm-card, .adm-step-detail-card { box-shadow: none; border: 1px solid #ccc; break-inside: avoid; }
}

/* Focus */
:focus-visible { outline: 2px solid var(--gold); outline-offset: 2px; border-radius: var(--radius-sm); }
</style>

<!-- =====================================================================
     PAGE ROOT
     ===================================================================== -->
<div class="adm-root">

    <!-- ── HERO ─────────────────────────────────────────────────────────── -->
    <section class="adm-hero" aria-label="Admissions hero">
        <div class="adm-hero-bg"></div>

        <div class="adm-hero-inner">

            <!-- Left -->
            <div class="adm-hero-left">
                <div class="adm-hero-badge">
                    <span class="adm-hero-badge-icon"><i class="fas fa-graduation-cap"></i></span>
                    <span class="adm-hero-badge-text">2025/2026 Admissions</span>
                </div>

                <h1 class="adm-hero-title">
                    ND/HND Nursing <span class="accent">Programme</span>
                </h1>

                <p class="adm-hero-subtitle">
                    FCT College of Nursing Sciences, Gwagwalada — NBTE &amp; NMCN Accredited. Applications for the 2025/2026 session are now closed.
                </p>

                <div class="adm-hero-status">
                    <i class="fas fa-lock"></i>
                    Application Portal Closed
                </div>
            </div>

            <!-- Right — stats panel -->
            <div class="adm-hero-right">
                <div class="adm-hero-stats">
                    <div class="adm-stat">
                        <span class="adm-stat-value">4</span>
                        <span class="adm-stat-label">Simple Steps</span>
                    </div>
                    <div class="adm-stat">
                        <span class="adm-stat-value">₦2,200</span>
                        <span class="adm-stat-label">Application Fee</span>
                    </div>
                    <div class="adm-stat">
                        <span class="adm-stat-value">4 Yrs</span>
                        <span class="adm-stat-label">Programme Duration</span>
                    </div>
                </div>
            </div>

        </div>
    </section>

    <!-- ── BREADCRUMB ────────────────────────────────────────────────────── -->
    <nav class="adm-breadcrumb" aria-label="Breadcrumb">
        <div class="adm-container">
            <ul class="adm-breadcrumb-list">
                <li><a href="<?php echo $baseUrl; ?>"><i class="fas fa-home"></i> Home</a></li>
                <li><span class="adm-breadcrumb-sep">/</span></li>
                <li><span class="adm-breadcrumb-current" aria-current="page">Admissions 2025/2026</span></li>
            </ul>
        </div>
    </nav>

    <!-- ── STATUS + NOTICE ──────────────────────────────────────────────── -->
    <div class="adm-section adm-section--bordered">
        <div class="adm-container">
            <div style="display:grid; grid-template-columns:1fr; gap:1.25rem;">

                <div class="adm-alert adm-alert--error">
                    <div class="adm-alert-icon"><i class="fas fa-lock"></i></div>
                    <div class="adm-alert-body">
                        <div class="adm-alert-title">Application Portal — Closed</div>
                        <div class="adm-alert-text">
                            <p><strong>The application portal for the 2025/2026 academic session is now closed.</strong> The sales of forms period ended on 28th September 2025. No further applications are being accepted for this session.</p>
                            <p>Please check back for updates on the 2026/2027 admissions cycle.</p>
                        </div>
                    </div>
                </div>

                <div class="adm-alert adm-alert--warning">
                    <div class="adm-alert-icon"><i class="fas fa-exclamation-triangle"></i></div>
                    <div class="adm-alert-body">
                        <div class="adm-alert-title">Important Notice</div>
                        <div class="adm-alert-text">
                            <p><strong>No extension</strong> of the application deadline. The College has <strong>NO AGENTS</strong>. Beware of fraudulent websites and deal only through official channels.</p>
                            <p>All applications must be submitted through the official portal only.</p>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <!-- ── ADMISSION DETAILS ────────────────────────────────────────────── -->
    <div class="adm-section">
        <div class="adm-container">
            <div class="adm-section-header">
                <h2 class="adm-section-title">
                    <span class="adm-section-pip"></span>
                    Admission Details
                </h2>
            </div>

            <div class="adm-cards-grid">

                <div class="adm-card">
                    <div class="adm-card-icon"><i class="far fa-calendar-alt"></i></div>
                    <h3 class="adm-card-title">Application Period</h3>
                    <div class="adm-card-body">
                        <p><strong>Sales of Forms:</strong> Mon 15th – Wed 28th September 2025</p>
                        <p><strong>Application Fee:</strong> ₦2,200 (Non-refundable)</p>
                        <p><strong>Status:</strong> <span style="color:var(--red); font-weight:600;">Closed</span></p>
                    </div>
                </div>

                <div class="adm-card">
                    <div class="adm-card-icon"><i class="fas fa-laptop-code"></i></div>
                    <h3 class="adm-card-title">CBT Screening</h3>
                    <div class="adm-card-body">
                        <p><strong>Dates:</strong> 6th, 7th &amp; 8th October 2025</p>
                        <p><strong>Venue:</strong> FCT College of Nursing Sciences, Gwagwalada (within UATH)</p>
                        <p><strong>Reporting Time:</strong> 8:00 AM daily</p>
                    </div>
                </div>

                <div class="adm-card">
                    <div class="adm-card-icon"><i class="fas fa-graduation-cap"></i></div>
                    <h3 class="adm-card-title">Programme Information</h3>
                    <div class="adm-card-body">
                        <p><strong>Programme:</strong> ND/HND Nursing (Non-terminal)</p>
                        <p><strong>Duration:</strong> 4 Years (2 Yrs ND + 2 Yrs HND)</p>
                        <p><strong>Accreditation:</strong> NBTE &amp; NMCN Approved</p>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <!-- ── ELIGIBILITY ──────────────────────────────────────────────────── -->
    <div class="adm-section adm-section--alt">
        <div class="adm-container">
            <div class="adm-section-header">
                <h2 class="adm-section-title">
                    <span class="adm-section-pip"></span>
                    Eligibility Requirements
                </h2>
            </div>

            <div class="adm-req-panel">
                <ul class="adm-req-list">
                    <li class="adm-req-item">
                        <span class="adm-req-check"><i class="fas fa-check"></i></span>
                        <span>Minimum UTME score of <strong>170</strong> in the 2025 JAMB examination</span>
                    </li>
                    <li class="adm-req-item">
                        <span class="adm-req-check"><i class="fas fa-check"></i></span>
                        <span>Selected <strong>FCT College of Nursing Sciences, Gwagwalada</strong> as First Choice institution</span>
                    </li>
                    <li class="adm-req-item">
                        <span class="adm-req-check"><i class="fas fa-check"></i></span>
                        <span>At least <strong>5 O'Level Credits</strong> — English Language, Mathematics, Biology, Chemistry, Physics — in not more than <strong>2 sittings</strong> (WAEC/NECO/NABTEB)</span>
                    </li>
                    <li class="adm-req-item">
                        <span class="adm-req-check"><i class="fas fa-check"></i></span>
                        <span>Must be <strong>16 years</strong> of age or above at the time of application</span>
                    </li>
                    <li class="adm-req-item">
                        <span class="adm-req-check"><i class="fas fa-check"></i></span>
                        <span>Valid JAMB registration number and complete personal details</span>
                    </li>
                    <li class="adm-req-item">
                        <span class="adm-req-check"><i class="fas fa-check"></i></span>
                        <span>Complete and accurate personal information on the application form</span>
                    </li>
                </ul>
            </div>
        </div>
    </div>

    <!-- ── REDESIGNED APPLICATION PROCESS ─────────────────────────────────── -->
    <div class="adm-section">
        <div class="adm-container">
            <!-- Section Header -->
            <div class="adm-section-header">
                <h2 class="adm-section-title">
                    <span class="adm-section-pip"></span>
                    Application Process
                </h2>
                <span class="adm-process-badge">
                    <i class="fas fa-arrow-right"></i> 4 Simple Steps
                </span>
            </div>

            <!-- Process Overview Cards (Clear at a glance) -->
            <div class="adm-process-overview">
                <div class="adm-overview-card">
                    <div class="adm-overview-icon" style="background: #e8f0fe; color: var(--blue);">
                        <i class="fas fa-clock"></i>
                    </div>
                    <div class="adm-overview-content">
                        <span class="adm-overview-label">Application Period</span>
                        <span class="adm-overview-value">Sept 15 – 28, 2025</span>
                    </div>
                </div>
                <div class="adm-overview-card">
                    <div class="adm-overview-icon" style="background: #e6f7ee; color: var(--green);">
                        <i class="fas fa-naira-sign"></i>
                    </div>
                    <div class="adm-overview-content">
                        <span class="adm-overview-label">Application Fee</span>
                        <span class="adm-overview-value">₦2,200</span>
                    </div>
                </div>
                <div class="adm-overview-card">
                    <div class="adm-overview-icon" style="background: #fff0e0; color: #b45f06;">
                        <i class="fas fa-calendar-check"></i>
                    </div>
                    <div class="adm-overview-content">
                        <span class="adm-overview-label">CBT Screening</span>
                        <span class="adm-overview-value">Oct 6 – 8, 2025</span>
                    </div>
                </div>
            </div>

            <!-- Visual Step Timeline -->
            <div class="adm-steps-showcase">
                <div class="adm-steps-track">
                    <div class="adm-track-step active">
                        <div class="adm-step-marker">1</div>
                        <div class="adm-step-label">Create Account</div>
                        <div class="adm-step-desc">Register on portal</div>
                    </div>
                    <div class="adm-track-step">
                        <div class="adm-step-marker">2</div>
                        <div class="adm-step-label">Fill Form</div>
                        <div class="adm-step-desc">Personal & academic info</div>
                    </div>
                    <div class="adm-track-step">
                        <div class="adm-step-marker">3</div>
                        <div class="adm-step-label">Make Payment</div>
                        <div class="adm-step-desc">₦2,200 application fee</div>
                    </div>
                    <div class="adm-track-step">
                        <div class="adm-step-marker">4</div>
                        <div class="adm-step-label">Print Slip</div>
                        <div class="adm-step-desc">CBT examination slip</div>
                    </div>
                </div>
            </div>

            <!-- Detailed Step Cards (Clear instructions) -->
            <div class="adm-steps-detailed">
                <!-- Step 1 -->
                <div class="adm-step-detail-card">
                    <div class="adm-detail-header">
                        <div class="adm-detail-num">01</div>
                        <div class="adm-detail-title">Account Creation</div>
                    </div>
                    <div class="adm-detail-content">
                        <ul class="adm-detail-list">
                            <li>
                                <i class="fas fa-chevron-right"></i>
                                <span><strong>Visit:</strong> 
                                    <a href="<?php echo $applicationPortal; ?>" target="_blank" rel="noopener" class="adm-portal-badge">
                                        <i class="fas fa-external-link-alt"></i> Application Portal
                                    </a>
                                </span>
                            </li>
                            <li>
                                <i class="fas fa-chevron-right"></i>
                                <span><strong>Click</strong> "Create New Account" or "Register"</span>
                            </li>
                            <li>
                                <i class="fas fa-chevron-right"></i>
                                <span><strong>Enter</strong> valid email address and phone number</span>
                            </li>
                            <li>
                                <i class="fas fa-chevron-right"></i>
                                <span><strong>Create</strong> secure password (min. 8 characters)</span>
                            </li>
                            <li>
                                <i class="fas fa-chevron-right"></i>
                                <span><strong>Verify</strong> email via confirmation link sent</span>
                            </li>
                        </ul>
                    </div>
                </div>

                <!-- Step 2 -->
                <div class="adm-step-detail-card">
                    <div class="adm-detail-header">
                        <div class="adm-detail-num">02</div>
                        <div class="adm-detail-title">Application Form</div>
                    </div>
                    <div class="adm-detail-content">
                        <ul class="adm-detail-list">
                            <li>
                                <i class="fas fa-chevron-right"></i>
                                <span><strong>Log in</strong> with your credentials</span>
                            </li>
                            <li>
                                <i class="fas fa-chevron-right"></i>
                                <span><strong>Enter JAMB Registration Number</strong> for validation</span>
                            </li>
                            <li>
                                <i class="fas fa-chevron-right"></i>
                                <span><strong>Fill personal details:</strong> name, date of birth, address</span>
                            </li>
                            <li>
                                <i class="fas fa-chevron-right"></i>
                                <span><strong>Upload:</strong> Passport photograph (max 200KB)</span>
                            </li>
                            <li>
                                <i class="fas fa-chevron-right"></i>
                                <span><strong>Upload O'Level results</strong> (WAEC/NECO/NABTEB)</span>
                            </li>
                        </ul>
                    </div>
                </div>

                <!-- Step 3 -->
                <div class="adm-step-detail-card">
                    <div class="adm-detail-header">
                        <div class="adm-detail-num">03</div>
                        <div class="adm-detail-title">Payment Processing</div>
                    </div>
                    <div class="adm-detail-content">
                        <ul class="adm-detail-list">
                            <li>
                                <i class="fas fa-chevron-right"></i>
                                <span><strong>Review</strong> all entered information</span>
                            </li>
                            <li>
                                <i class="fas fa-chevron-right"></i>
                                <span><strong>Click</strong> "Proceed to Payment"</span>
                            </li>
                            <li>
                                <i class="fas fa-chevron-right"></i>
                                <span><strong>Generate RRR code</strong> for payment reference</span>
                            </li>
                            <li>
                                <i class="fas fa-chevron-right"></i>
                                <span><strong>Pay ₦2,200</strong> via online banking or at any bank</span>
                            </li>
                            <li>
                                <i class="fas fa-chevron-right"></i>
                                <span><strong>Return to portal</strong> and click "Verify Payment"</span>
                            </li>
                        </ul>
                    </div>
                </div>

                <!-- Step 4 -->
                <div class="adm-step-detail-card">
                    <div class="adm-detail-header">
                        <div class="adm-detail-num">04</div>
                        <div class="adm-detail-title">Examination Slip</div>
                    </div>
                    <div class="adm-detail-content">
                        <ul class="adm-detail-list">
                            <li>
                                <i class="fas fa-chevron-right"></i>
                                <span><strong>Wait for payment confirmation</strong> (instant or within 24hrs)</span>
                            </li>
                            <li>
                                <i class="fas fa-chevron-right"></i>
                                <span><strong>Navigate to</strong> "Print Examination Slip"</span>
                            </li>
                            <li>
                                <i class="fas fa-chevron-right"></i>
                                <span><strong>Download and print</strong> your CBT slip</span>
                            </li>
                            <li>
                                <i class="fas fa-chevron-right"></i>
                                <span><strong>Check:</strong> Date, time, and venue</span>
                            </li>
                            <li>
                                <i class="fas fa-chevron-right"></i>
                                <span><strong>Bring slip + valid ID</strong> to exam centre</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Quick Actions Bar -->
            <div class="adm-quick-actions">
                <span><i class="fas fa-info-circle" style="color: var(--purple);"></i> <strong>Portal Status:</strong> Currently closed for 2025/2026</span>
                <a href="<?php echo $applicationPortal; ?>" target="_blank" rel="noopener" class="adm-btn adm-btn--outline">
                    <i class="fas fa-external-link-alt"></i> Visit Portal
                </a>
                <a href="<?php echo $baseUrl; ?>/faq" class="adm-btn adm-btn--surface">
                    <i class="fas fa-question-circle"></i> Application FAQs
                </a>
            </div>

            <!-- Portal CTA Card -->
            <div class="adm-portal-card">
                <div class="adm-portal-card-content">
                    <span class="adm-portal-card-tag"><i class="fas fa-globe"></i> Official Portal</span>
                    <h3 class="adm-portal-card-title">Application Portal</h3>
                    <p class="adm-portal-card-desc">
                        Access the official admissions portal for FCT College of Nursing Sciences. The portal for the 2025/2026 session is currently closed.
                    </p>
                </div>
                <div class="adm-portal-card-actions">
                    <a href="<?php echo $applicationPortal; ?>" target="_blank" rel="noopener"
                       class="adm-btn adm-btn--gold adm-btn--lg">
                        <i class="fas fa-external-link-alt"></i> Visit Portal
                    </a>
                    <a href="<?php echo $baseUrl; ?>/contact" 
                       class="adm-btn adm-btn--ghost adm-btn--lg">
                        <i class="fas fa-question-circle"></i> Help Guide
                    </a>
                </div>
            </div>

            <div class="adm-note">
                <i class="fas fa-info-circle"></i>
                This process was for the <strong>2025/2026 academic session</strong>. The portal is now closed.
                Please check back for the <strong>2026/2027 admissions cycle</strong>.
            </div>

        </div>
    </div>

    <!-- ── SUPPORT & CONTACT ────────────────────────────────────────────── -->
    <div class="adm-section adm-section--alt">
        <div class="adm-container">
            <div class="adm-section-header">
                <h2 class="adm-section-title">
                    <span class="adm-section-pip"></span>
                    Support &amp; Enquiries
                </h2>
            </div>

            <div class="adm-contact-grid">

                <div class="adm-card">
                    <div class="adm-card-icon"><i class="fas fa-phone-alt"></i></div>
                    <h3 class="adm-card-title">Phone Support</h3>
                    <div class="adm-card-body">
                        <p><strong>Call:</strong> 07039837749 / 08036625119</p>
                        <p><strong>WhatsApp Only:</strong> 08082775076</p>
                        <p><strong>Hours:</strong> Mon–Fri, 9:00 AM – 5:00 PM</p>
                    </div>
                </div>

                <div class="adm-card">
                    <div class="adm-card-icon"><i class="fas fa-envelope"></i></div>
                    <h3 class="adm-card-title">Email &amp; Online</h3>
                    <div class="adm-card-body">
                        <p><strong>Email:</strong> support.consap@fcthhss.abj.gov.ng</p>
                        <p><strong>Live Chat:</strong> Available on the portal</p>
                        <p style="display:flex; align-items:center; gap:.5rem; flex-wrap:wrap;">
                            <strong>Telegram:</strong>
                            <a href="https://t.me/+SWH5opeTcTXs34Ko" target="_blank" rel="noopener"
                               class="adm-btn adm-btn--purple"
                               style="font-size:.78rem; padding:.35rem .85rem;">
                                <i class="fab fa-telegram-plane"></i> Join Channel
                            </a>
                        </p>
                    </div>
                </div>

                <div class="adm-card">
                    <div class="adm-card-icon"><i class="fas fa-map-marker-alt"></i></div>
                    <h3 class="adm-card-title">Campus Visit</h3>
                    <div class="adm-card-body">
                        <p><strong>Address:</strong> FCT College of Nursing Sciences, Gwagwalada, Abuja</p>
                        <p><strong>Office Hours:</strong> Monday – Friday, 8:00 AM – 5:00 PM</p>
                        <p><strong>Note:</strong> Appointments recommended</p>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <!-- ── CTA STRIP ────────────────────────────────────────────────────── -->
    <div class="adm-cta-strip">
        <div class="adm-container">
            <div style="text-align:center;">
                <h2 class="adm-section-title" style="justify-content:center; margin-bottom:.6rem;">
                    <span class="adm-section-pip"></span>
                    2025/2026 Admissions Status
                </h2>
                <p class="adm-section-subtitle" style="margin:0 auto;">
                    The application period for the current academic session has ended
                </p>
            </div>

            <div class="adm-cta-actions">
                <span class="adm-btn adm-btn--disabled adm-btn--lg">
                    <i class="fas fa-lock"></i> Application Portal (Closed)
                </span>
                <a href="<?php echo $baseUrl; ?>/programs" class="adm-btn adm-btn--outline adm-btn--lg">
                    <i class="fas fa-book-open"></i> View Programmes
                </a>
                <a href="<?php echo $baseUrl; ?>/contact" class="adm-btn adm-btn--purple adm-btn--lg">
                    <i class="fas fa-phone-alt"></i> Contact Admissions
                </a>
            </div>

            <div class="adm-cta-note">
                <p><i class="fas fa-calendar-alt"></i> <strong>Next Admissions Cycle:</strong> 2026/2027 academic session</p>
                <p>Check back regularly for updates on the next admissions cycle.</p>
            </div>
        </div>
    </div>

</div><!-- /.adm-root -->

<script>
(function () {
    'use strict';

    /* Hero background fallback */
    var heroBg = document.querySelector('.adm-hero-bg');
    if (heroBg) {
        var img = new Image();
        img.onerror = function () {
            heroBg.style.backgroundImage = 'none';
        };
        img.src = '<?php echo $heroImagePath; ?>';
    }

    /* Scroll-triggered fade-in for cards */
    var animated = document.querySelectorAll('.adm-card, .adm-step-detail-card, .adm-overview-card');
    if ('IntersectionObserver' in window) {
        var io = new IntersectionObserver(function (entries) {
            entries.forEach(function (entry) {
                if (entry.isIntersecting) {
                    entry.target.style.opacity = '1';
                    entry.target.style.transform = 'translateY(0)';
                    io.unobserve(entry.target);
                }
            });
        }, { threshold: 0.1 });

        animated.forEach(function (el, i) {
            el.style.opacity = '0';
            el.style.transform = 'translateY(18px)';
            el.style.transition = 'opacity .5s ease ' + (i * 0.07) + 's, transform .5s ease ' + (i * 0.07) + 's';
            io.observe(el);
        });
    }

    /* Smooth scroll for internal anchors */
    document.querySelectorAll('a[href^="#"]').forEach(function (a) {
        a.addEventListener('click', function (e) {
            var id = a.getAttribute('href');
            if (id === '#') return;
            var target = document.querySelector(id);
            if (target) {
                e.preventDefault();
                window.scrollTo({ top: target.offsetTop - 90, behavior: 'smooth' });
            }
        });
    });

    /* Step tracker hover effect (optional) */
    var steps = document.querySelectorAll('.adm-track-step');
    steps.forEach(function(step, index) {
        step.addEventListener('mouseenter', function() {
            // Optional: preview step details
        });
    });
})();
</script>