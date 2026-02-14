<?php
/**
 * Programs Page View Template
 * Redesigned to match design system (v6.0)
 *
 * Fonts   : Cormorant Garamond (display) + Outfit (body) + JetBrains Mono (mono)
 * Palette : identical CSS custom-properties as research & admissions pages
 * Layout  : fluid --gutter, full-bleed hero (starts at very top), max-width 1400px
 *
 * @package FCTCNS
 * @version 6.0
 */

extract($data ?? []);

if (!function_exists('e')) {
    function e($text) {
        return htmlspecialchars($text ?? '', ENT_QUOTES, 'UTF-8');
    }
}

$baseUrl          = $baseUrl ?? '/';
$page_title       = $page_title ?? 'Nursing Programs | FCT College of Nursing Sciences';
$page_description = $page_description ?? 'Explore our accredited nursing education programs.';
$heroImagePath    = rtrim($baseUrl, '/') . '/assets/images/programs/programs-hero.jpg';

$programImages = [
    'nd-nursing'    => rtrim($baseUrl,'/') . '/assets/images/programs/nd-nursing.jpg',
    'basic-nursing' => rtrim($baseUrl,'/') . '/assets/images/programs/basic-nursing.jpg',
    'basic-midwifery'=> rtrim($baseUrl,'/') . '/assets/images/programs/basic-midwifery.jpg',
    'post-basic'    => rtrim($baseUrl,'/') . '/assets/images/programs/post-basic.jpg',
];
?>
<!-- Google Fonts — identical to all other pages -->
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
   DESIGN TOKENS — identical to research & admissions pages
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

    --green:        #5D9B8C;
    --green-pale:   #EEF7F5;
    --amber:        #C9870A;
    --amber-pale:   #FEF6E4;

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

    --gutter:        clamp(1.25rem, 5vw, 6rem);
    --container-max: 1400px;
}

/* ==========================================================================
   ROOT SCOPE
   ========================================================================== */
.pg-root {
    font-family: var(--font-body);
    color: var(--ink);
    background: var(--white);
    overflow-x: hidden;
    width: 100%;
}

/* ==========================================================================
   CONTAINER
   ========================================================================== */
.pg-container {
    width: 100%;
    max-width: var(--container-max);
    margin-left: auto;
    margin-right: auto;
    padding-left:  var(--gutter);
    padding-right: var(--gutter);
}

/* ==========================================================================
   HERO — full-bleed, no breadcrumb above, starts at very top
   ========================================================================== */
.pg-hero {
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

.pg-hero-bg {
    position: absolute;
    inset: 0;
    background-image: url('<?php echo $heroImagePath; ?>');
    background-size: cover;
    background-position: center;
    opacity: 0.18;
    z-index: 0;
}

.pg-hero::after {
    content: '';
    position: absolute;
    inset: 0;
    background: radial-gradient(circle at 70% 30%, rgba(139,123,184,0.18) 0%, transparent 60%);
    z-index: 1;
    pointer-events: none;
}

.pg-hero-inner {
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
    .pg-hero-inner {
        flex-direction: row;
        align-items: center;
        justify-content: space-between;
    }
}

.pg-hero-left  { width: 100%; max-width: 660px; }
.pg-hero-right { width: 100%; }

@media (min-width: 992px) {
    .pg-hero-left  { width: 58%; max-width: none; }
    .pg-hero-right { width: 38%; }
}

/* Badge */
.pg-hero-badge {
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

.pg-hero-badge-icon {
    width: 22px; height: 22px;
    background: var(--purple);
    border-radius: 50%;
    display: flex; align-items: center; justify-content: center;
    color: white; font-size: 0.65rem;
}

.pg-hero-badge-text {
    font-family: var(--font-mono);
    font-size: 0.68rem;
    font-weight: 500;
    letter-spacing: 0.1em;
    text-transform: uppercase;
    color: var(--gold);
}

/* Title */
.pg-hero-title {
    font-family: var(--font-display);
    font-size: clamp(2.2rem, 4.5vw, 4rem);
    font-weight: 700;
    line-height: 1.1;
    color: white;
    margin-bottom: 1rem;
    letter-spacing: -0.01em;
    text-shadow: 0 2px 8px rgba(0,0,0,0.2);
}

.pg-hero-title .accent { color: var(--gold-light); font-style: italic; }

.pg-hero-subtitle {
    font-size: clamp(0.95rem, 1.5vw, 1.15rem);
    color: rgba(255,255,255,0.82);
    font-weight: 300;
    max-width: 540px;
    line-height: 1.65;
    margin-bottom: 1.75rem;
}

/* Hero CTA buttons */
.pg-hero-actions {
    display: flex;
    flex-wrap: wrap;
    gap: 0.75rem;
}

/* Stats panel — matches all other pages */
.pg-hero-stats {
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

.pg-stat { display: flex; flex-direction: column; gap: 4px; }

.pg-stat-value {
    font-family: var(--font-display);
    font-size: clamp(1.8rem, 3vw, 2.4rem);
    font-weight: 700;
    color: #FFE082;
    line-height: 1;
    text-shadow: 0 1px 4px rgba(0,0,0,0.3);
}

.pg-stat-label {
    font-size: 0.72rem;
    font-weight: 600;
    letter-spacing: 0.08em;
    text-transform: uppercase;
    color: rgba(255,255,255,0.92);
}

/* ==========================================================================
   BREADCRUMB — appears BELOW hero
   ========================================================================== */
.pg-breadcrumb {
    background: var(--white);
    border-bottom: 1px solid var(--border);
    padding: 0.75rem 0;
}

.pg-breadcrumb-list {
    display: flex;
    align-items: center;
    gap: 0.4rem;
    list-style: none;
    font-size: 0.8rem;
    flex-wrap: wrap;
}

.pg-breadcrumb-list a {
    color: var(--purple-dark);
    text-decoration: none;
    font-weight: 500;
    transition: color 0.18s;
}
.pg-breadcrumb-list a:hover { color: var(--purple); text-decoration: underline; }
.pg-breadcrumb-sep     { color: var(--mist); }
.pg-breadcrumb-current { color: var(--slate); }

/* ==========================================================================
   SECTION SPACING
   ========================================================================== */
.pg-section {
    padding-top:    clamp(2.5rem, 5vw, 4rem);
    padding-bottom: clamp(2.5rem, 5vw, 4rem);
}

.pg-section--alt { background: var(--surface); }
.pg-section--bordered {
    border-top: 1px solid var(--border);
    border-bottom: 1px solid var(--border);
}

/* ==========================================================================
   SECTION HEADER — matches all pages
   ========================================================================== */
.pg-section-header {
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

.pg-section-title {
    display: flex;
    align-items: center;
    gap: 10px;
    font-family: var(--font-display);
    font-size: clamp(1.35rem, 2.5vw, 1.9rem);
    font-weight: 700;
    color: var(--ink);
    letter-spacing: -0.01em;
}

.pg-section-pip {
    width: 8px; height: 8px;
    border-radius: 50%;
    background: var(--purple);
    flex-shrink: 0;
}

.pg-section-subtitle {
    font-size: clamp(0.95rem, 1.5vw, 1.05rem);
    color: var(--slate);
    line-height: 1.65;
    margin-top: 0.5rem;
    font-weight: 400;
}

/* ==========================================================================
   BUTTONS — mirrors rp-btn / adm-btn exactly
   ========================================================================== */
.pg-btn {
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

.pg-btn--purple { background: var(--purple); color: white; }
.pg-btn--purple:hover {
    background: var(--purple-dark); color: white;
    transform: translateY(-1px);
    box-shadow: 0 4px 14px rgba(139,123,184,0.32);
}

.pg-btn--gold { background: var(--gold); color: white; }
.pg-btn--gold:hover {
    background: var(--gold-light); color: white;
    transform: translateY(-1px);
    box-shadow: 0 4px 14px rgba(201,164,74,0.3);
}

.pg-btn--outline { background: transparent; color: var(--purple); border: 1.5px solid var(--purple); }
.pg-btn--outline:hover { background: var(--purple); color: white; transform: translateY(-1px); }

.pg-btn--ghost {
    background: transparent; color: white;
    border: 1.5px solid rgba(255,255,255,0.35);
}
.pg-btn--ghost:hover { border-color: white; background: rgba(255,255,255,0.1); color: white; }

.pg-btn--surface { background: var(--surface); color: var(--ink-soft); border: 1px solid var(--border); }
.pg-btn--surface:hover { background: var(--border); color: var(--ink); }

.pg-btn--lg { padding: 0.85rem 2rem; font-size: 1rem; }

/* ==========================================================================
   PROGRAM CARDS — list-style rows like rp-card
   ========================================================================== */
.pg-programs-grid {
    display: flex;
    flex-direction: column;
    gap: 1.5rem;
    margin-top: 0.5rem;
}

.pg-card {
    display: flex;
    flex-direction: column;
    background: var(--white);
    border: 1px solid var(--border);
    border-radius: var(--radius-lg);
    overflow: hidden;
    transition: transform 0.28s ease, box-shadow 0.28s ease, border-color 0.28s ease;
    position: relative;
}

@media (min-width: 768px) { .pg-card { flex-direction: row; } }

.pg-card::before {
    content: '';
    position: absolute;
    left: 0; top: 0; bottom: 0;
    width: 3px;
    background: linear-gradient(to bottom, var(--purple), var(--purple-light));
    transform: scaleY(0);
    transform-origin: center;
    transition: transform 0.28s ease;
    border-radius: 3px 0 0 3px;
    z-index: 1;
}

.pg-card:hover {
    transform: translateY(-3px);
    box-shadow: var(--shadow-lg);
    border-color: rgba(139,123,184,0.25);
}

.pg-card:hover::before { transform: scaleY(1); }

/* Image column */
.pg-card-img-wrap {
    position: relative;
    width: 100%;
    height: 220px;
    flex-shrink: 0;
    overflow: hidden;
    background: var(--surface);
}

@media (min-width: 768px)  { .pg-card-img-wrap { width: 260px; height: auto; } }
@media (min-width: 1024px) { .pg-card-img-wrap { width: 300px; } }

.pg-card-img {
    width: 100%; height: 100%;
    object-fit: cover;
    display: block;
    transition: transform 0.5s ease;
}

.pg-card:hover .pg-card-img { transform: scale(1.05); }

/* Status badge over image */
.pg-card-status {
    position: absolute;
    top: 0.85rem; left: 0.85rem;
    font-family: var(--font-mono);
    font-size: 0.6rem;
    font-weight: 500;
    letter-spacing: 0.12em;
    text-transform: uppercase;
    padding: 3px 10px;
    border-radius: 4px;
    z-index: 2;
}

.pg-card-status--active {
    background: var(--green);
    color: white;
}

.pg-card-status--transition {
    background: var(--amber);
    color: white;
}

/* Card body */
.pg-card-body {
    padding: clamp(1.25rem, 2.5vw, 1.75rem);
    flex: 1;
    display: flex;
    flex-direction: column;
    min-width: 0;
}

/* Top row: title + duration chip */
.pg-card-top {
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    gap: 0.75rem;
    margin-bottom: 0.75rem;
    flex-wrap: wrap;
}

.pg-card-title {
    font-family: var(--font-display);
    font-size: clamp(1.25rem, 2vw, 1.55rem);
    font-weight: 700;
    color: var(--ink);
    letter-spacing: -0.01em;
    line-height: 1.25;
}

.pg-card-duration {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    background: var(--purple-pale);
    color: var(--purple-dark);
    border: 1px solid var(--purple-light);
    border-radius: var(--radius-full);
    font-family: var(--font-mono);
    font-size: 0.65rem;
    font-weight: 500;
    padding: 3px 10px;
    white-space: nowrap;
    flex-shrink: 0;
}

.pg-card-duration i { font-size: 0.6rem; }

/* Duration breakdown pills */
.pg-card-breakdown {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    margin-bottom: 0.85rem;
    flex-wrap: wrap;
}

.pg-card-breakdown-pill {
    background: var(--surface);
    color: var(--slate);
    border: 1px solid var(--border);
    border-radius: var(--radius-full);
    font-family: var(--font-mono);
    font-size: 0.62rem;
    padding: 2px 9px;
}

.pg-card-breakdown-arrow {
    color: var(--mist);
    font-size: 0.7rem;
}

/* Description */
.pg-card-desc {
    font-size: 0.9rem;
    color: var(--slate);
    line-height: 1.7;
    margin-bottom: 1.1rem;
    flex: 1;
}

.pg-card-desc strong { color: var(--ink-soft); }

/* Highlights panel */
.pg-highlights {
    background: var(--purple-pale);
    border-left: 3px solid var(--purple);
    border-radius: 0 var(--radius-md) var(--radius-md) 0;
    padding: 0.9rem 1.1rem;
    margin-bottom: 1.25rem;
}

.pg-highlights-title {
    font-family: var(--font-mono);
    font-size: 0.65rem;
    font-weight: 500;
    letter-spacing: 0.1em;
    text-transform: uppercase;
    color: var(--purple-dark);
    margin-bottom: 0.6rem;
    display: flex;
    align-items: center;
    gap: 5px;
}

.pg-highlights-list {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 0.2rem 1rem;
    list-style: none;
}

@media (max-width: 560px) { .pg-highlights-list { grid-template-columns: 1fr; } }

.pg-highlights-list li {
    font-size: 0.82rem;
    color: var(--ink-soft);
    line-height: 1.5;
    padding: 0.2rem 0;
    padding-left: 1.1rem;
    position: relative;
}

.pg-highlights-list li::before {
    content: '';
    position: absolute;
    left: 0; top: 9px;
    width: 5px; height: 5px;
    background: var(--gold);
    border-radius: 50%;
}

/* Footer row */
.pg-card-footer {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 0.75rem;
    padding-top: 1.1rem;
    border-top: 1px solid var(--border);
    flex-wrap: wrap;
    margin-top: auto;
}

.pg-card-footer-meta {
    font-family: var(--font-mono);
    font-size: 0.68rem;
    color: var(--mist);
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.pg-card-footer-meta i { font-size: 0.62rem; color: var(--slate); }

.pg-card-actions { display: flex; gap: 0.5rem; }

@media (max-width: 480px) {
    .pg-card-footer { flex-direction: column; align-items: flex-start; }
    .pg-card-actions { width: 100%; justify-content: flex-end; }
}

/* ==========================================================================
   CTA DARK CARD — matches adm-portal-card / rp-featured style
   ========================================================================== */
.pg-cta-card {
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
}

.pg-cta-card::before {
    content: '';
    position: absolute;
    left: 0; top: 15%; bottom: 15%;
    width: 3px;
    background: linear-gradient(to bottom, var(--purple-light), var(--purple));
    border-radius: 3px;
}

.pg-cta-card-content { flex: 1; min-width: 220px; }

.pg-cta-card-tag {
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

.pg-cta-card-title {
    font-family: var(--font-display);
    font-size: clamp(1.4rem, 2.5vw, 2rem);
    font-weight: 700;
    color: white;
    margin-bottom: 0.5rem;
    letter-spacing: -0.01em;
}

.pg-cta-card-desc {
    font-size: 0.95rem;
    color: rgba(255,255,255,0.7);
    line-height: 1.6;
}

.pg-cta-card-actions {
    display: flex;
    gap: 0.85rem;
    flex-wrap: wrap;
}

@media (max-width: 480px) {
    .pg-cta-card { flex-direction: column; }
    .pg-cta-card-actions { flex-direction: column; width: 100%; }
    .pg-cta-card-actions .pg-btn { justify-content: center; }
}

/* ==========================================================================
   ANIMATIONS
   ========================================================================== */
@keyframes pg-fadeIn {
    from { opacity: 0; transform: translateY(14px); }
    to   { opacity: 1; transform: translateY(0); }
}

.pg-card { animation: pg-fadeIn 0.4s ease both; }
.pg-card:nth-child(1) { animation-delay: 0.05s; }
.pg-card:nth-child(2) { animation-delay: 0.10s; }
.pg-card:nth-child(3) { animation-delay: 0.15s; }
.pg-card:nth-child(4) { animation-delay: 0.20s; }

@media (prefers-reduced-motion: reduce) {
    .pg-card { animation: none !important; transition: none !important; }
}

/* Focus */
:focus-visible { outline: 2px solid var(--gold); outline-offset: 2px; border-radius: var(--radius-sm); }

/* Print */
@media print {
    .pg-hero, .pg-btn { display: none !important; }
    .pg-card { box-shadow: none; border: 1px solid #ccc; break-inside: avoid; }
    .pg-card-img-wrap { display: none; }
}
</style>

<!-- =====================================================================
     PAGE ROOT
     ===================================================================== -->
<div class="pg-root">

    <!-- ── HERO — starts at very top, no breadcrumb above ───────────────── -->
    <section class="pg-hero" aria-label="Programs hero">
        <div class="pg-hero-bg"></div>

        <div class="pg-hero-inner">

            <!-- Left: headline + CTAs -->
            <div class="pg-hero-left">
                <div class="pg-hero-badge">
                    <span class="pg-hero-badge-icon"><i class="fas fa-book-medical"></i></span>
                    <span class="pg-hero-badge-text">Accredited Programs</span>
                </div>

                <h1 class="pg-hero-title">
                    Nursing Education <span class="accent">Programs</span>
                </h1>

                <p class="pg-hero-subtitle">
                    Fully accredited programs combining theoretical excellence with hands-on clinical training — preparing compassionate, competent healthcare professionals.
                </p>

                <div class="pg-hero-actions">
                    <a href="#programs" class="pg-btn pg-btn--gold pg-btn--lg">
                        <i class="fas fa-book-medical"></i> Explore Programs
                    </a>
                    <!-- FIXED: Added slash after baseUrl to match old code -->
                    <a href="<?php echo $baseUrl; ?>/admissions" class="pg-btn pg-btn--ghost pg-btn--lg">
                        <i class="fas fa-graduation-cap"></i> Apply Now
                    </a>
                </div>
            </div>

            <!-- Right: stats panel -->
            <div class="pg-hero-right">
                <div class="pg-hero-stats">
                    <div class="pg-stat">
                        <span class="pg-stat-value">4</span>
                        <span class="pg-stat-label">Programs</span>
                    </div>
                    <div class="pg-stat">
                        <span class="pg-stat-value">100%</span>
                        <span class="pg-stat-label">Accredited</span>
                    </div>
                    <div class="pg-stat">
                        <span class="pg-stat-value">4+</span>
                        <span class="pg-stat-label">Years Duration</span>
                    </div>
                </div>
            </div>

        </div>
    </section>

    <!-- ── BREADCRUMB — below hero ───────────────────────────────────────── -->
    <nav class="pg-breadcrumb" aria-label="Breadcrumb">
        <div class="pg-container">
            <ul class="pg-breadcrumb-list">
                <!-- FIXED: Added slash after baseUrl -->
                <li><a href="<?php echo $baseUrl; ?>/"><i class="fas fa-home"></i> Home</a></li>
                <li><span class="pg-breadcrumb-sep">/</span></li>
                <li><span class="pg-breadcrumb-current" aria-current="page">Nursing Programs</span></li>
            </ul>
        </div>
    </nav>

    <!-- ── PROGRAMS GRID ─────────────────────────────────────────────────── -->
    <div id="programs" class="pg-section">
        <div class="pg-container">

            <div class="pg-section-header">
                <div>
                    <h2 class="pg-section-title">
                        <span class="pg-section-pip"></span>
                        Our Accredited Programs
                    </h2>
                    <p class="pg-section-subtitle">Nationally recognised nursing programs designed for real-world healthcare delivery.</p>
                </div>
                <span style="font-family:var(--font-mono); font-size:.75rem; color:var(--slate);">4 programs</span>
            </div>

            <div class="pg-programs-grid">

                <!-- ── CARD 1: ND/HND Nursing ──────────────────────────── -->
                <article class="pg-card">
                    <div class="pg-card-img-wrap">
                        <img src="<?php echo $programImages['nd-nursing']; ?>"
                             alt="ND/HND Nursing Programme"
                             class="pg-card-img"
                             loading="lazy"
                             onerror="this.closest('.pg-card-img-wrap').style.background='var(--purple-pale)';">
                        <span class="pg-card-status pg-card-status--active">Currently Available</span>
                    </div>

                    <div class="pg-card-body">
                        <div class="pg-card-top">
                            <h3 class="pg-card-title">ND/HND Nursing Programme</h3>
                            <span class="pg-card-duration">
                                <i class="far fa-clock"></i> 4 Years · Non-Terminal
                            </span>
                        </div>

                        <div class="pg-card-breakdown">
                            <span class="pg-card-breakdown-pill">ND — 2 Years</span>
                            <span class="pg-card-breakdown-arrow"><i class="fas fa-arrow-right"></i></span>
                            <span class="pg-card-breakdown-pill">HND — 2 Years</span>
                        </div>

                        <p class="pg-card-desc">
                            Comprehensive four-year non-terminal programme leading to National Diploma (ND) and Higher National Diploma (HND) qualifications. Combines theoretical knowledge with practical skills for advanced healthcare delivery.
                        </p>

                        <div class="pg-highlights">
                            <div class="pg-highlights-title"><i class="fas fa-list-check"></i> Key Features</div>
                            <ul class="pg-highlights-list">
                                <li>NBTE accredited programme</li>
                                <li>Non-terminal ND/HND structure</li>
                                <li>JAMB UTME pathway</li>
                                <li>Clinical rotations &amp; internships</li>
                                <li>Modern simulation labs</li>
                                <li>Research methodology training</li>
                            </ul>
                        </div>

                        <div class="pg-card-footer">
                            <div class="pg-card-footer-meta">
                                <i class="fas fa-shield-halved"></i> NBTE &amp; NMCN Approved
                            </div>
                            <div class="pg-card-actions">
                                <!-- FIXED: Added slash after baseUrl to match old code -->
                                <a href="<?php echo $baseUrl; ?>/admissions" class="pg-btn pg-btn--outline pg-btn-sm">Learn More</a>
                                <a href="<?php echo $baseUrl; ?>/admissions" class="pg-btn pg-btn--purple pg-btn-sm">Apply Now</a>
                            </div>
                        </div>
                    </div>
                </article>

                <!-- ── CARD 2: Basic Nursing ───────────────────────────── -->
                <article class="pg-card">
                    <div class="pg-card-img-wrap">
                        <img src="<?php echo $programImages['basic-nursing']; ?>"
                             alt="Basic Nursing Programme"
                             class="pg-card-img"
                             loading="lazy"
                             onerror="this.closest('.pg-card-img-wrap').style.background='var(--purple-pale)';">
                        <span class="pg-card-status pg-card-status--transition">Programme Transition</span>
                    </div>

                    <div class="pg-card-body">
                        <div class="pg-card-top">
                            <h3 class="pg-card-title">Basic Nursing</h3>
                            <span class="pg-card-duration">
                                <i class="far fa-clock"></i> 3 Years
                            </span>
                        </div>

                        <p class="pg-card-desc">
                            Comprehensive general nursing education preparing students for registration as Registered Nurses (RN). <strong>Note: This programme is transitioning to the ND/HND system.</strong>
                        </p>

                        <div class="pg-highlights">
                            <div class="pg-highlights-title"><i class="fas fa-list-check"></i> Key Features</div>
                            <ul class="pg-highlights-list">
                                <li>Full NMCN accreditation</li>
                                <li>Extensive clinical practice</li>
                                <li>Simulation training</li>
                                <li>Exam preparation support</li>
                                <li>Professional development</li>
                            </ul>
                        </div>

                        <div class="pg-card-footer">
                            <div class="pg-card-footer-meta">
                                <i class="fas fa-arrows-rotate"></i> Transitioning to ND/HND
                            </div>
                            <div class="pg-card-actions">
                                <!-- FIXED: Added slash after baseUrl to match old code -->
                                <a href="<?php echo $baseUrl; ?>/admissions" class="pg-btn pg-btn--outline pg-btn-sm">Learn More</a>
                                <a href="<?php echo $baseUrl; ?>/contact"     class="pg-btn pg-btn--surface pg-btn-sm">Contact Us</a>
                            </div>
                        </div>
                    </div>
                </article>

                <!-- ── CARD 3: Basic Midwifery ────────────────────────── -->
                <article class="pg-card">
                    <div class="pg-card-img-wrap">
                        <img src="<?php echo $programImages['basic-midwifery']; ?>"
                             alt="Basic Midwifery Programme"
                             class="pg-card-img"
                             loading="lazy"
                             onerror="this.closest('.pg-card-img-wrap').style.background='var(--purple-pale)';">
                        <span class="pg-card-status pg-card-status--transition">Programme Transition</span>
                    </div>

                    <div class="pg-card-body">
                        <div class="pg-card-top">
                            <h3 class="pg-card-title">Basic Midwifery</h3>
                            <span class="pg-card-duration">
                                <i class="far fa-clock"></i> 3 Years
                            </span>
                        </div>

                        <p class="pg-card-desc">
                            Specialised training in maternal and child healthcare, antenatal care, delivery, and postnatal services. <strong>Note: This programme is transitioning to the ND/HND system.</strong>
                        </p>

                        <div class="pg-highlights">
                            <div class="pg-highlights-title"><i class="fas fa-list-check"></i> Key Features</div>
                            <ul class="pg-highlights-list">
                                <li>NMCN approved</li>
                                <li>Maternity clinical placements</li>
                                <li>Family planning training</li>
                                <li>Neonatal care focus</li>
                                <li>Community outreach</li>
                            </ul>
                        </div>

                        <div class="pg-card-footer">
                            <div class="pg-card-footer-meta">
                                <i class="fas fa-arrows-rotate"></i> Transitioning to ND/HND
                            </div>
                            <div class="pg-card-actions">
                                <!-- FIXED: Added slash after baseUrl to match old code -->
                                <a href="<?php echo $baseUrl; ?>/admissions" class="pg-btn pg-btn--outline pg-btn-sm">Learn More</a>
                                <a href="<?php echo $baseUrl; ?>/contact"     class="pg-btn pg-btn--surface pg-btn-sm">Contact Us</a>
                            </div>
                        </div>
                    </div>
                </article>

                <!-- ── CARD 4: Post Basic ─────────────────────────────── -->
                <article class="pg-card">
                    <div class="pg-card-img-wrap">
                        <img src="<?php echo $programImages['post-basic']; ?>"
                             alt="Post Basic Nursing Specialisation"
                             class="pg-card-img"
                             loading="lazy"
                             onerror="this.closest('.pg-card-img-wrap').style.background='var(--purple-pale)';">
                        <span class="pg-card-status pg-card-status--transition">Programme Transition</span>
                    </div>

                    <div class="pg-card-body">
                        <div class="pg-card-top">
                            <h3 class="pg-card-title">Post Basic Nursing Specialisation</h3>
                            <span class="pg-card-duration">
                                <i class="far fa-clock"></i> 18 Months
                            </span>
                        </div>

                        <p class="pg-card-desc">
                            Advanced specialisation for registered nurses in intensive care, paediatrics, perioperative, or psychiatric nursing. <strong>Note: This programme is transitioning to the ND/HND system.</strong>
                        </p>

                        <div class="pg-highlights">
                            <div class="pg-highlights-title"><i class="fas fa-list-check"></i> Key Features</div>
                            <ul class="pg-highlights-list">
                                <li>Specialist clinical training</li>
                                <li>Leadership development</li>
                                <li>Research methodology</li>
                                <li>Career advancement pathway</li>
                                <li>Expert faculty mentorship</li>
                            </ul>
                        </div>

                        <div class="pg-card-footer">
                            <div class="pg-card-footer-meta">
                                <i class="fas fa-arrows-rotate"></i> Transitioning to ND/HND
                            </div>
                            <div class="pg-card-actions">
                                <!-- FIXED: Added slash after baseUrl to match old code -->
                                <a href="<?php echo $baseUrl; ?>/admissions" class="pg-btn pg-btn--outline pg-btn-sm">Learn More</a>
                                <a href="<?php echo $baseUrl; ?>/contact"     class="pg-btn pg-btn--surface pg-btn-sm">Contact Us</a>
                            </div>
                        </div>
                    </div>
                </article>

            </div><!-- /.pg-programs-grid -->
        </div>
    </div>

    <!-- ── CTA SECTION ───────────────────────────────────────────────────── -->
    <div class="pg-section pg-section--alt pg-section--bordered">
        <div class="pg-container">

            <div class="pg-cta-card">
                <div class="pg-cta-card-content">
                    <span class="pg-cta-card-tag"><i class="fas fa-graduation-cap"></i> Start Your Journey</span>
                    <h2 class="pg-cta-card-title">Begin Your Nursing Career Today</h2>
                    <p class="pg-cta-card-desc">
                        Join thousands of graduates making a difference in healthcare across Nigeria. Apply for the next admissions cycle.
                    </p>
                </div>
                <div class="pg-cta-card-actions">
                    <!-- FIXED: Added slash after baseUrl to match old code -->
                    <a href="<?php echo $baseUrl; ?>/admissions" class="pg-btn pg-btn--gold pg-btn--lg">
                        <i class="fas fa-file-alt"></i> Apply Now
                    </a>
                    <a href="<?php echo $baseUrl; ?>/contact" class="pg-btn pg-btn--ghost pg-btn--lg">
                        <i class="fas fa-phone-alt"></i> Contact Admissions
                    </a>
                </div>
            </div>

        </div>
    </div>

</div><!-- /.pg-root -->

<script>
(function () {
    'use strict';

    /* Hero background fallback */
    var heroBg = document.querySelector('.pg-hero-bg');
    if (heroBg) {
        var img = new Image();
        img.onerror = function () { heroBg.style.backgroundImage = 'none'; };
        img.src = '<?php echo $heroImagePath; ?>';
    }

    /* Scroll-triggered fade-in */
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

        document.querySelectorAll('.pg-card').forEach(function (el, i) {
            el.style.opacity = '0';
            el.style.transform = 'translateY(18px)';
            el.style.transition = 'opacity .5s ease ' + (i * 0.08) + 's, transform .5s ease ' + (i * 0.08) + 's';
            io.observe(el);
        });
    }

    /* Smooth scroll */
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
})();
</script>