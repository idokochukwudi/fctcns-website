<?php
/**
 * Research Publication Detail Page
 * Redesigned to match the list page design system (v5.5)
 * 
 * Fonts    : Cormorant Garamond (display) + Outfit (body) + JetBrains Mono (mono)
 * Palette  : identical CSS custom-properties as research-publications.php
 * Layout   : fluid --gutter, max-width 1400px, sidebar + main two-column on ≥992px
 * 
 * @package FCTCNS
 * @version 1.0
 */

if (!isset($publication) || !isset($categories)) {
    http_response_code(404);
    echo '<h1>Publication Not Found</h1>';
    echo '<p>The requested research publication could not be found.</p>';
    echo '<p><a href="/research">Return to Research</a></p>';
    return;
}

if (!function_exists('e')) {
    function e($text) {
        return htmlspecialchars($text ?? '', ENT_QUOTES, 'UTF-8');
    }
}

$baseUrl = $baseUrl ?? '/';
?>
<!-- Google Fonts — same as list page -->
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
    background: #FFFFFF;
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

    --color-journal:    #6D8EB0;
    --color-conference: #8B7BB8;
    --color-book:       #B08968;
    --color-thesis:     #5D9B8C;
    --color-report:     #6D5C9E;

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

    /* fluid gutter — same as list page */
    --gutter:        clamp(1.25rem, 5vw, 6rem);
    --container-max: 1400px;
}

/* ==========================================================================
   ROOT SCOPE
   ========================================================================== */
.rd-root {
    font-family: var(--font-body);
    color: var(--ink);
    background: var(--white);
    overflow-x: hidden;
    width: 100%;
}

/* ==========================================================================
   CONTAINER — mirrors list page
   ========================================================================== */
.rd-container {
    width: 100%;
    max-width: var(--container-max);
    margin-left: auto;
    margin-right: auto;
    padding-left:  var(--gutter);
    padding-right: var(--gutter);
}

/* ==========================================================================
   BREADCRUMB — exact copy from list page
   ========================================================================== */
.rd-breadcrumb {
    background: var(--white);
    border-bottom: 1px solid var(--border);
    padding: 0.75rem 0;
}

.rd-breadcrumb-list {
    display: flex;
    align-items: center;
    gap: 0.4rem;
    list-style: none;
    font-size: 0.8rem;
    flex-wrap: wrap;
}

.rd-breadcrumb-list a {
    color: var(--purple-dark);
    text-decoration: none;
    font-weight: 500;
    transition: color 0.18s;
}

.rd-breadcrumb-list a:hover { color: var(--purple); text-decoration: underline; }
.rd-breadcrumb-sep     { color: var(--mist); }
.rd-breadcrumb-current { color: var(--slate); }

/* ==========================================================================
   HERO — matches list page hero style: dark gradient + image bg
   ========================================================================== */
.rd-hero {
    position: relative;
    background: linear-gradient(145deg, #2A2A42 0%, #383856 100%);
    overflow: hidden;
    padding-top:    clamp(3rem, 7vw, 5.5rem);
    padding-bottom: clamp(3rem, 7vw, 5.5rem);
    width: 100%;
}

/* subtle radial accent */
.rd-hero::after {
    content: '';
    position: absolute;
    inset: 0;
    background: radial-gradient(circle at 70% 30%, rgba(139,123,184,0.18) 0%, transparent 60%);
    z-index: 1;
    pointer-events: none;
}

.rd-hero-inner {
    width: 100%;
    max-width: var(--container-max);
    margin-left:  auto;
    margin-right: auto;
    padding-left:  var(--gutter);
    padding-right: var(--gutter);
    position: relative;
    z-index: 2;
    /* prevent any child from escaping left edge */
    overflow-x: hidden;
}

/* Publication type pill — matches rp-featured-tag */
.rd-hero-pill {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    background: var(--purple);
    color: white;
    font-family: var(--font-mono);
    font-size: 0.65rem;
    font-weight: 500;
    letter-spacing: 0.15em;
    text-transform: uppercase;
    padding: 4px 14px;
    border-radius: 4px;
    margin-bottom: 1.25rem;
    width: fit-content;
}

.rd-hero-pill.journal    { background: var(--color-journal); }
.rd-hero-pill.conference { background: var(--color-conference); }
.rd-hero-pill.book       { background: var(--color-book); }
.rd-hero-pill.thesis     { background: var(--color-thesis); }
.rd-hero-pill.report     { background: var(--color-report); }

/* Gold badge — "Research Publication" label */
.rd-hero-badge {
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

.rd-hero-badge-icon {
    width: 22px; height: 22px;
    background: var(--purple);
    border-radius: 50%;
    display: flex; align-items: center; justify-content: center;
    color: white;
    font-size: 0.65rem;
}

.rd-hero-badge-text {
    font-family: var(--font-mono);
    font-size: 0.68rem;
    font-weight: 500;
    letter-spacing: 0.1em;
    text-transform: uppercase;
    color: var(--gold);
}

/* Title — mirrors rp-featured-title */
.rd-hero-title {
    font-family: var(--font-display);
    font-size: clamp(1.7rem, 3.5vw, 3rem);
    font-weight: 700;
    line-height: 1.18;
    color: white;
    margin-bottom: 1.25rem;
    letter-spacing: -0.01em;
    text-shadow: 0 2px 10px rgba(0,0,0,0.25);
    max-width: 900px;
}

/* Decorative underline accent */
.rd-hero-title::after {
    content: '';
    display: block;
    width: clamp(60px, 8vw, 100px);
    height: 3px;
    background: linear-gradient(90deg, var(--gold), var(--gold-light));
    margin-top: 1rem;
    border-radius: 2px;
}

/* Authors row */
.rd-hero-authors {
    font-size: clamp(0.95rem, 1.8vw, 1.15rem);
    color: rgba(255,255,255,0.88);
    font-style: italic;
    margin-bottom: 1.25rem;
    max-width: 800px;
    line-height: 1.5;
}

.rd-hero-authors i { color: var(--gold-light); margin-right: 0.4rem; }

/* Meta chips row */
.rd-hero-meta {
    display: flex;
    flex-wrap: wrap;
    gap: 0.65rem;
    align-items: center;
}

.rd-hero-chip {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 0.4rem 1rem;
    background: rgba(0,0,0,0.28);
    border: 1px solid rgba(255,255,255,0.15);
    border-radius: var(--radius-full);
    font-family: var(--font-mono);
    font-size: 0.7rem;
    color: rgba(255,255,255,0.88);
    backdrop-filter: blur(6px);
    -webkit-backdrop-filter: blur(6px);
    white-space: nowrap;
}

.rd-hero-chip i { color: var(--gold-light); font-size: 0.62rem; }

/* ==========================================================================
   MAIN LAYOUT — sidebar (left) + content (right) on ≥992px
   ========================================================================== */
.rd-body {
    padding-top:    clamp(2rem, 4vw, 3.5rem);
    padding-bottom: clamp(3rem, 6vw, 5rem);
    background: var(--white);
}

.rd-grid {
    display: grid;
    grid-template-columns: 1fr;
    gap: clamp(1.5rem, 3vw, 2.5rem);
    align-items: start;
}

@media (min-width: 992px) {
    .rd-grid {
        grid-template-columns: 300px 1fr;
    }
}

@media (min-width: 1200px) {
    .rd-grid {
        grid-template-columns: 330px 1fr;
    }
}

/* On mobile, sidebar drops below main content */
.rd-sidebar { order: 2; }
.rd-main    { order: 1; }

@media (min-width: 992px) {
    .rd-sidebar { order: 1; }
    .rd-main    { order: 2; }
}

/* ==========================================================================
   SHARED PANEL STYLE
   ========================================================================== */
.rd-panel {
    background: var(--white);
    border: 1px solid var(--border);
    border-radius: var(--radius-lg);
    padding: clamp(1.25rem, 3vw, 1.75rem);
    box-shadow: var(--shadow-sm);
}

.rd-panel + .rd-panel {
    margin-top: clamp(1.25rem, 3vw, 1.75rem);
}

/* Panel header — mirrors rp-section-header */
.rd-panel-header {
    display: flex;
    align-items: center;
    gap: 10px;
    margin-bottom: 1.4rem;
    padding-bottom: 0.9rem;
    border-bottom: 2px solid var(--border);
    border-image: linear-gradient(90deg, var(--purple) 80px, var(--border) 80px) 1;
}

.rd-panel-icon {
    width: 36px; height: 36px;
    background: var(--purple-pale);
    color: var(--purple);
    border-radius: 50%;
    display: flex; align-items: center; justify-content: center;
    font-size: 0.95rem;
    flex-shrink: 0;
}

.rd-panel-title {
    font-family: var(--font-display);
    font-size: 1.3rem;
    font-weight: 700;
    color: var(--ink);
    letter-spacing: -0.01em;
}

/* ==========================================================================
   SIDEBAR — METADATA
   ========================================================================== */
.rd-meta-list {
    list-style: none;
}

.rd-meta-item {
    padding: 0.85rem 0;
    border-bottom: 1px solid var(--border);
    display: flex;
    flex-direction: column;
    gap: 0.2rem;
}

.rd-meta-item:last-child { border-bottom: none; }

.rd-meta-label {
    font-family: var(--font-mono);
    font-size: 0.65rem;
    font-weight: 500;
    letter-spacing: 0.1em;
    text-transform: uppercase;
    color: var(--mist);
    display: flex;
    align-items: center;
    gap: 0.4rem;
}

.rd-meta-label i { color: var(--purple-light); font-size: 0.6rem; }

.rd-meta-value {
    font-size: 0.92rem;
    color: var(--ink-soft);
    line-height: 1.5;
    font-weight: 400;
}

.rd-meta-value a {
    color: var(--purple-dark);
    text-decoration: none;
    transition: color 0.18s;
    word-break: break-word;
}

.rd-meta-value a:hover { color: var(--purple); text-decoration: underline; }

/* Type badge inside meta */
.rd-type-badge {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    background: var(--purple);
    color: white;
    font-family: var(--font-mono);
    font-size: 0.62rem;
    font-weight: 500;
    letter-spacing: 0.12em;
    text-transform: uppercase;
    padding: 3px 10px;
    border-radius: 4px;
    width: fit-content;
}

.rd-type-badge.journal    { background: var(--color-journal); }
.rd-type-badge.conference { background: var(--color-conference); }
.rd-type-badge.book       { background: var(--color-book); }
.rd-type-badge.thesis     { background: var(--color-thesis); }
.rd-type-badge.report     { background: var(--color-report); }

/* Metrics mini-grid inside sidebar */
.rd-metrics-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 0.75rem;
    margin-top: 0;
}

.rd-metric-tile {
    background: var(--surface);
    border: 1px solid var(--border);
    border-radius: var(--radius-md);
    padding: 0.9rem 0.75rem;
    text-align: center;
    transition: border-color 0.2s, box-shadow 0.2s;
}

.rd-metric-tile:hover {
    border-color: var(--purple-light);
    box-shadow: var(--shadow-xs);
}

.rd-metric-icon {
    font-size: 1.1rem;
    color: var(--purple);
    margin-bottom: 0.3rem;
}

.rd-metric-value {
    font-family: var(--font-display);
    font-size: 1.6rem;
    font-weight: 700;
    color: var(--purple);
    line-height: 1.1;
}

.rd-metric-label {
    font-size: 0.7rem;
    color: var(--slate);
    font-weight: 500;
    margin-top: 0.15rem;
}

/* ==========================================================================
   MAIN CONTENT — ABSTRACT & EXTRAS
   ========================================================================== */

/* Abstract text */
.rd-abstract-body {
    font-size: clamp(0.95rem, 1.4vw, 1.05rem);
    color: var(--slate);
    line-height: 1.8;
    white-space: pre-line;
}

.rd-abstract-body p + p { margin-top: 1em; }

/* Keywords */
.rd-keywords {
    display: flex;
    flex-wrap: wrap;
    gap: 0.5rem;
    margin-top: 0.25rem;
}

.rd-keyword {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    padding: 0.3rem 0.85rem;
    background: var(--purple-pale);
    color: var(--purple-dark);
    border-radius: var(--radius-full);
    font-size: 0.8rem;
    font-weight: 500;
    border: 1px solid var(--purple-light);
    text-decoration: none;
    transition: all 0.22s ease;
}

.rd-keyword i { font-size: 0.68rem; color: var(--purple-light); }

.rd-keyword:hover {
    background: var(--purple);
    color: white;
    border-color: var(--purple);
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(139,123,184,0.25);
}

.rd-keyword:hover i { color: rgba(255,255,255,0.7); }

/* ==========================================================================
   ACTION BUTTONS — mirrors list page rp-btn
   ========================================================================== */
.rd-actions {
    display: flex;
    flex-wrap: wrap;
    gap: 0.75rem;
    margin-top: 0.25rem;
}

.rd-btn {
    display: inline-flex;
    align-items: center;
    gap: 7px;
    padding: 0.7rem 1.5rem;
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

.rd-btn--purple {
    background: var(--purple);
    color: white;
}
.rd-btn--purple:hover {
    background: var(--purple-dark); color: white;
    transform: translateY(-1px);
    box-shadow: 0 4px 14px rgba(139,123,184,0.32);
}

.rd-btn--outline {
    background: transparent;
    color: var(--purple);
    border: 1.5px solid var(--purple);
}
.rd-btn--outline:hover {
    background: var(--purple); color: white;
    transform: translateY(-1px);
}

.rd-btn--gold {
    background: var(--gold); color: white;
}
.rd-btn--gold:hover {
    background: var(--gold-light); color: white;
    transform: translateY(-1px);
    box-shadow: 0 4px 14px rgba(201,164,74,0.3);
}

.rd-btn--surface {
    background: var(--surface);
    color: var(--ink-soft);
    border: 1px solid var(--border);
}
.rd-btn--surface:hover { background: var(--border); color: var(--ink); }

@media (max-width: 480px) {
    .rd-actions { flex-direction: column; }
    .rd-actions .rd-btn { justify-content: center; }
}

/* ==========================================================================
   RELATED PUBLICATIONS GRID — mirrors rp-card
   ========================================================================== */
.rd-related-grid {
    display: grid;
    grid-template-columns: 1fr;
    gap: 1rem;
    margin-top: 0.25rem;
}

@media (min-width: 640px)  { .rd-related-grid { grid-template-columns: repeat(2, 1fr); } }
@media (min-width: 1100px) { .rd-related-grid { grid-template-columns: repeat(3, 1fr); } }

.rd-related-card {
    display: block;
    background: var(--white);
    border: 1px solid var(--border);
    border-radius: var(--radius-lg);
    padding: 1.25rem;
    text-decoration: none;
    color: inherit;
    transition: transform 0.28s ease, box-shadow 0.28s ease, border-color 0.28s ease;
    position: relative;
    overflow: hidden;
}

.rd-related-card::before {
    content: '';
    position: absolute;
    left: 0; top: 0; bottom: 0;
    width: 3px;
    background: linear-gradient(to bottom, var(--purple), var(--purple-light));
    transform: scaleY(0);
    transform-origin: center;
    transition: transform 0.28s ease;
    border-radius: 3px 0 0 3px;
}

.rd-related-card:hover {
    transform: translateY(-3px);
    box-shadow: var(--shadow-lg);
    border-color: rgba(139,123,184,0.25);
}

.rd-related-card:hover::before { transform: scaleY(1); }

.rd-related-type {
    display: inline-block;
    background: var(--purple);
    color: white;
    font-family: var(--font-mono);
    font-size: 0.6rem;
    font-weight: 500;
    letter-spacing: 0.12em;
    text-transform: uppercase;
    padding: 3px 9px;
    border-radius: 4px;
    margin-bottom: 0.65rem;
}

.rd-related-type.journal    { background: var(--color-journal); }
.rd-related-type.conference { background: var(--color-conference); }
.rd-related-type.book       { background: var(--color-book); }
.rd-related-type.thesis     { background: var(--color-thesis); }

.rd-related-title {
    font-family: var(--font-display);
    font-size: 1.1rem;
    font-weight: 700;
    line-height: 1.3;
    color: var(--ink);
    margin-bottom: 0.5rem;
    letter-spacing: -0.01em;
    transition: color 0.2s;
}

.rd-related-card:hover .rd-related-title { color: var(--purple); }

.rd-related-authors {
    font-size: 0.83rem;
    color: var(--slate);
    font-style: italic;
    line-height: 1.4;
    margin-bottom: 0.75rem;
}

.rd-related-footer {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding-top: 0.75rem;
    border-top: 1px solid var(--border);
    font-family: var(--font-mono);
    font-size: 0.68rem;
    color: var(--mist);
}

/* ==========================================================================
   FULL-WIDTH SECTION WRAPPER (for related / below the grid)
   ========================================================================== */
.rd-full-section {
    margin-top: clamp(2rem, 4vw, 3rem);
}

.rd-full-section .rd-panel { border-radius: var(--radius-xl); }

/* ==========================================================================
   DIVIDER
   ========================================================================== */
.rd-divider {
    border: none;
    border-top: 1px solid var(--border);
    margin: clamp(1.5rem, 3vw, 2.5rem) 0;
}

/* ==========================================================================
   SECTION HEADER — identical to rp-section-header
   ========================================================================== */
.rd-section-header {
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

.rd-section-title {
    display: flex;
    align-items: center;
    gap: 10px;
    font-family: var(--font-display);
    font-size: clamp(1.35rem, 2.5vw, 1.9rem);
    font-weight: 700;
    color: var(--ink);
    letter-spacing: -0.01em;
}

.rd-section-pip {
    width: 8px; height: 8px;
    border-radius: 50%;
    background: var(--purple);
    flex-shrink: 0;
}

/* ==========================================================================
   ANIMATIONS
   ========================================================================== */
@keyframes rd-fadeIn {
    from { opacity: 0; transform: translateY(12px); }
    to   { opacity: 1; transform: translateY(0); }
}

.rd-panel     { animation: rd-fadeIn 0.4s ease both; }
.rd-panel:nth-child(1) { animation-delay: 0.05s; }
.rd-panel:nth-child(2) { animation-delay: 0.12s; }
.rd-related-card       { animation: rd-fadeIn 0.4s ease both; }

@media (prefers-reduced-motion: reduce) {
    .rd-panel, .rd-related-card { animation: none !important; transition: none !important; }
}

/* ==========================================================================
   PRINT
   ========================================================================== */
@media print {
    .rd-hero     { background: #2A2A42 !important; }
    .rd-actions  { display: none !important; }
    .rd-panel    { box-shadow: none; border: 1px solid #ccc; break-inside: avoid; }
}
</style>

<!-- =====================================================================
     PAGE ROOT
     ===================================================================== -->
<div class="rd-root">

    <!-- ── BREADCRUMB ────────────────────────────────────────────────────── -->
    <nav class="rd-breadcrumb" aria-label="Breadcrumb">
        <div class="rd-container">
            <ul class="rd-breadcrumb-list">
                <li><a href="<?php echo $baseUrl; ?>"><i class="fas fa-home" aria-hidden="true"></i> Home</a></li>
                <li><span class="rd-breadcrumb-sep">/</span></li>
                <li><a href="<?php echo $baseUrl; ?>research"><i class="fas fa-book-open" aria-hidden="true"></i> Research</a></li>
                <li><span class="rd-breadcrumb-sep">/</span></li>
                <li><span class="rd-breadcrumb-current" aria-current="page">
                    <?php echo e(substr($publication['title'] ?? 'Publication Detail', 0, 55) . (strlen($publication['title'] ?? '') > 55 ? '…' : '')); ?>
                </span></li>
            </ul>
        </div>
    </nav>

    <!-- ── HERO ─────────────────────────────────────────────────────────── -->
    <header class="rd-hero" role="banner">
        <div class="rd-hero-inner">

            <!-- Top label row -->
            <div style="display:flex; flex-wrap:wrap; gap:.6rem; align-items:center; margin-bottom: 1rem;">
                <div class="rd-hero-badge">
                    <span class="rd-hero-badge-icon"><i class="fas fa-flask"></i></span>
                    <span class="rd-hero-badge-text">Research Publication</span>
                </div>
                <span class="rd-hero-pill <?php echo e($publication['publication_type'] ?? ''); ?>">
                    <i class="fas fa-tag"></i>
                    <?php echo e($pubTypeLabel ?? ucfirst($publication['publication_type'] ?? 'Publication')); ?>
                </span>
            </div>

            <h1 class="rd-hero-title">
                <?php echo e($publication['title'] ?? 'Untitled Publication'); ?>
            </h1>

            <p class="rd-hero-authors">
                <i class="fas fa-users"></i>
                <?php echo e($publication['authors'] ?? 'N/A'); ?>
            </p>

            <div class="rd-hero-meta">
                <?php if (!empty($pubDate)): ?>
                <span class="rd-hero-chip">
                    <i class="far fa-calendar-alt"></i>
                    <?php echo e($pubDate); ?>
                </span>
                <?php endif; ?>

                <?php if (!empty($categoryName)): ?>
                <span class="rd-hero-chip">
                    <i class="fas fa-folder"></i>
                    <?php echo e($categoryName); ?>
                </span>
                <?php endif; ?>

                <?php if (!empty($publication['journal_name'])): ?>
                <span class="rd-hero-chip">
                    <i class="fas fa-book"></i>
                    <?php echo e($publication['journal_name']); ?>
                </span>
                <?php endif; ?>

                <?php if (!empty($publication['doi'])): ?>
                <span class="rd-hero-chip">
                    <i class="fas fa-fingerprint"></i>
                    DOI: <?php echo e($publication['doi']); ?>
                </span>
                <?php endif; ?>
            </div>

        </div>
    </header>

    <!-- ── BODY ─────────────────────────────────────────────────────────── -->
    <div class="rd-body">
        <div class="rd-container">

            <!-- Two-column grid: sidebar + main -->
            <div class="rd-grid">

                <!-- ═══════════════════════════════════════
                     SIDEBAR
                     ═══════════════════════════════════════ -->
                <aside class="rd-sidebar" aria-label="Publication details">

                    <!-- Publication Details card -->
                    <div class="rd-panel">
                        <div class="rd-panel-header">
                            <div class="rd-panel-icon"><i class="fas fa-info-circle"></i></div>
                            <h2 class="rd-panel-title">Publication Details</h2>
                        </div>

                        <ul class="rd-meta-list">

                            <li class="rd-meta-item">
                                <span class="rd-meta-label"><i class="fas fa-tag"></i> Category</span>
                                <span class="rd-meta-value"><?php echo e($categoryName ?? 'Unknown'); ?></span>
                            </li>

                            <li class="rd-meta-item">
                                <span class="rd-meta-label"><i class="fas fa-file-alt"></i> Type</span>
                                <span class="rd-meta-value">
                                    <span class="rd-type-badge <?php echo e($publication['publication_type'] ?? ''); ?>">
                                        <?php echo e($pubTypeLabel ?? ucfirst($publication['publication_type'] ?? 'Unknown')); ?>
                                    </span>
                                </span>
                            </li>

                            <?php if (!empty($publication['journal_name'])): ?>
                            <li class="rd-meta-item">
                                <span class="rd-meta-label"><i class="fas fa-book"></i> Journal</span>
                                <span class="rd-meta-value">
                                    <?php echo e($publication['journal_name']); ?>
                                    <?php if (!empty($publication['volume'])): ?>, Vol.&nbsp;<?php echo e($publication['volume']); ?><?php endif; ?>
                                    <?php if (!empty($publication['issue'])): ?>, Iss.&nbsp;<?php echo e($publication['issue']); ?><?php endif; ?>
                                    <?php if (!empty($publication['pages'])): ?>, pp.&nbsp;<?php echo e($publication['pages']); ?><?php endif; ?>
                                </span>
                            </li>
                            <?php endif; ?>

                            <?php if (!empty($publication['publisher'])): ?>
                            <li class="rd-meta-item">
                                <span class="rd-meta-label"><i class="fas fa-building"></i> Publisher</span>
                                <span class="rd-meta-value"><?php echo e($publication['publisher']); ?></span>
                            </li>
                            <?php endif; ?>

                            <?php if (!empty($publication['doi'])): ?>
                            <li class="rd-meta-item">
                                <span class="rd-meta-label"><i class="fas fa-fingerprint"></i> DOI</span>
                                <span class="rd-meta-value">
                                    <a href="https://doi.org/<?php echo e($publication['doi']); ?>" target="_blank" rel="noopener noreferrer">
                                        <?php echo e($publication['doi']); ?>
                                        <i class="fas fa-external-link-alt" style="font-size:.65rem; margin-left:4px; opacity:.6;"></i>
                                    </a>
                                </span>
                            </li>
                            <?php endif; ?>

                            <?php if (!empty($publication['url'])): ?>
                            <li class="rd-meta-item">
                                <span class="rd-meta-label"><i class="fas fa-link"></i> URL</span>
                                <span class="rd-meta-value">
                                    <a href="<?php echo e($publication['url']); ?>" target="_blank" rel="noopener noreferrer">
                                        <?php echo e(preg_replace('#^https?://#', '', rtrim($publication['url'], '/'))); ?>
                                        <i class="fas fa-external-link-alt" style="font-size:.65rem; margin-left:4px; opacity:.6;"></i>
                                    </a>
                                </span>
                            </li>
                            <?php endif; ?>

                            <?php if (!empty($publication['citations']) && $publication['citations'] > 0): ?>
                            <li class="rd-meta-item">
                                <span class="rd-meta-label"><i class="fas fa-quote-right"></i> Citations</span>
                                <span class="rd-meta-value"><?php echo e($publication['citations']); ?></span>
                            </li>
                            <?php endif; ?>

                            <?php if (!empty($publication['impact_factor'])): ?>
                            <li class="rd-meta-item">
                                <span class="rd-meta-label"><i class="fas fa-chart-line"></i> Impact Factor</span>
                                <span class="rd-meta-value"><?php echo e($publication['impact_factor']); ?></span>
                            </li>
                            <?php endif; ?>

                        </ul>
                    </div><!-- /.rd-panel -->

                    <!-- Metrics card -->
                    <div class="rd-panel">
                        <div class="rd-panel-header">
                            <div class="rd-panel-icon"><i class="fas fa-chart-bar"></i></div>
                            <h2 class="rd-panel-title">Metrics</h2>
                        </div>

                        <div class="rd-metrics-grid">
                            <div class="rd-metric-tile">
                                <div class="rd-metric-icon"><i class="fas fa-eye"></i></div>
                                <div class="rd-metric-value"><?php echo e($publication['views_count'] ?? 0); ?></div>
                                <div class="rd-metric-label">Views</div>
                            </div>
                            <div class="rd-metric-tile">
                                <div class="rd-metric-icon"><i class="fas fa-download"></i></div>
                                <div class="rd-metric-value"><?php echo e($publication['downloads_count'] ?? 0); ?></div>
                                <div class="rd-metric-label">Downloads</div>
                            </div>
                            <div class="rd-metric-tile">
                                <div class="rd-metric-icon"><i class="fas fa-quote-right"></i></div>
                                <div class="rd-metric-value"><?php echo e($publication['citations'] ?? 0); ?></div>
                                <div class="rd-metric-label">Citations</div>
                            </div>
                            <div class="rd-metric-tile">
                                <div class="rd-metric-icon"><i class="fas fa-star"></i></div>
                                <div class="rd-metric-value" style="font-size:1.1rem; padding-top:.3rem;">
                                    <?php echo $publication['is_featured'] ? '✦ Yes' : '—'; ?>
                                </div>
                                <div class="rd-metric-label">Featured</div>
                            </div>
                        </div>
                    </div><!-- /.rd-panel -->

                    <!-- Quick actions card -->
                    <div class="rd-panel">
                        <div class="rd-panel-header">
                            <div class="rd-panel-icon"><i class="fas fa-bolt"></i></div>
                            <h2 class="rd-panel-title">Quick Actions</h2>
                        </div>

                        <div style="display:flex; flex-direction:column; gap:.6rem;">
                            <a href="/research" class="rd-btn rd-btn--surface" style="justify-content:center;">
                                <i class="fas fa-arrow-left"></i> Back to Research
                            </a>

                            <?php if (!empty($publication['file_path'])): ?>
                            <a href="/research/<?php echo e($publication['id'] ?? ''); ?>/download"
                               class="rd-btn rd-btn--gold" style="justify-content:center;">
                                <i class="fas fa-download"></i> Download PDF
                            </a>
                            <?php endif; ?>

                            <?php if (!empty($publication['url'])): ?>
                            <a href="<?php echo e($publication['url']); ?>"
                               target="_blank" rel="noopener noreferrer"
                               class="rd-btn rd-btn--outline" style="justify-content:center;">
                                <i class="fas fa-external-link-alt"></i> View Online
                            </a>
                            <?php endif; ?>
                        </div>
                    </div><!-- /.rd-panel -->

                </aside><!-- /.rd-sidebar -->

                <!-- ═══════════════════════════════════════
                     MAIN CONTENT
                     ═══════════════════════════════════════ -->
                <main class="rd-main" role="main">

                    <!-- Abstract -->
                    <div class="rd-panel">
                        <div class="rd-panel-header">
                            <div class="rd-panel-icon"><i class="fas fa-align-left"></i></div>
                            <h2 class="rd-panel-title">Abstract</h2>
                        </div>

                        <div class="rd-abstract-body">
                            <?php echo nl2br(e($publication['abstract'] ?? 'No abstract available.')); ?>
                        </div>
                    </div>

                    <!-- Keywords -->
                    <?php if (!empty($keywordsArray)): ?>
                    <div class="rd-panel">
                        <div class="rd-panel-header">
                            <div class="rd-panel-icon"><i class="fas fa-tags"></i></div>
                            <h2 class="rd-panel-title">Keywords</h2>
                        </div>

                        <div class="rd-keywords">
                            <?php foreach ($keywordsArray as $keyword): ?>
                            <a href="/research?search=<?php echo urlencode(trim($keyword)); ?>" class="rd-keyword">
                                <i class="fas fa-hashtag"></i>
                                <?php echo e(trim($keyword)); ?>
                            </a>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    <?php endif; ?>

                    <!-- How to Cite -->
                    <?php
                        $year = !empty($publication['publication_date'])
                            ? date('Y', strtotime($publication['publication_date']))
                            : 'n.d.';
                        $authorList = $publication['authors'] ?? 'Unknown';
                        $titleStr   = $publication['title']   ?? '';
                        $journalStr = $publication['journal_name'] ?? '';
                        $doiStr     = $publication['doi'] ?? '';
                        $volStr     = $publication['volume'] ?? '';
                        $issStr     = $publication['issue']  ?? '';
                        $ppStr      = $publication['pages']  ?? '';

                        $citation  = e($authorList) . ' (' . $year . '). ';
                        $citation .= '<em>' . e($titleStr) . '</em>. ';
                        if ($journalStr) {
                            $citation .= e($journalStr);
                            if ($volStr)  $citation .= ', <strong>' . e($volStr) . '</strong>';
                            if ($issStr)  $citation .= '(' . e($issStr) . ')';
                            if ($ppStr)   $citation .= ', ' . e($ppStr);
                            $citation .= '. ';
                        }
                        if ($doiStr) $citation .= 'https://doi.org/' . e($doiStr);
                    ?>
                    <div class="rd-panel">
                        <div class="rd-panel-header">
                            <div class="rd-panel-icon"><i class="fas fa-quote-left"></i></div>
                            <h2 class="rd-panel-title">How to Cite</h2>
                        </div>

                        <blockquote style="
                            background: var(--surface);
                            border-left: 3px solid var(--purple);
                            border-radius: 0 var(--radius-md) var(--radius-md) 0;
                            padding: 1rem 1.25rem;
                            font-size: 0.88rem;
                            color: var(--slate);
                            line-height: 1.7;
                            font-style: italic;
                            margin: 0;
                        ">
                            <?php echo $citation; ?>
                        </blockquote>

                        <button onclick="
                            var text = document.querySelector('.rd-cite-text')?.innerText;
                            if(text) { navigator.clipboard.writeText(text).then(function(){
                                var btn = document.getElementById('rdCopyBtn');
                                btn.innerHTML = '<i class=\'fas fa-check\'></i> Copied!';
                                setTimeout(function(){ btn.innerHTML = '<i class=\'fas fa-copy\'></i> Copy Citation'; }, 2000);
                            }); }
                        " id="rdCopyBtn"
                           class="rd-btn rd-btn--outline"
                           style="margin-top: 0.85rem; font-size:.82rem; padding: .45rem 1rem;">
                            <i class="fas fa-copy"></i> Copy Citation
                        </button>

                        <!-- Hidden plain-text version for clipboard -->
                        <span class="rd-cite-text" style="display:none;">
                            <?php
                                echo ($authorList) . ' (' . $year . '). ';
                                echo $titleStr . '. ';
                                if ($journalStr) {
                                    echo $journalStr;
                                    if ($volStr) echo ', ' . $volStr;
                                    if ($issStr) echo '(' . $issStr . ')';
                                    if ($ppStr)  echo ', ' . $ppStr;
                                    echo '. ';
                                }
                                if ($doiStr) echo 'https://doi.org/' . $doiStr;
                            ?>
                        </span>
                    </div>

                </main><!-- /.rd-main -->

            </div><!-- /.rd-grid -->

            <!-- ── RELATED PUBLICATIONS (full-width below the grid) ─────── -->
            <?php if (!empty($related) && count($related) > 0): ?>
            <div class="rd-full-section">
                <div class="rd-section-header">
                    <h2 class="rd-section-title">
                        <span class="rd-section-pip"></span>
                        Related Publications
                    </h2>
                    <a href="/research" class="rd-btn rd-btn--surface" style="font-size:.82rem; padding:.4rem .95rem;">
                        Browse All <i class="fas fa-arrow-right"></i>
                    </a>
                </div>

                <div class="rd-related-grid">
                    <?php foreach (array_slice($related, 0, 6) as $relatedPub): ?>
                    <a href="/research/<?php echo e($relatedPub['id']); ?>" class="rd-related-card">
                        <span class="rd-related-type <?php echo e($relatedPub['publication_type'] ?? ''); ?>">
                            <?php echo ucfirst($relatedPub['publication_type'] ?? 'Publication'); ?>
                        </span>
                        <h3 class="rd-related-title">
                            <?php echo e(substr($relatedPub['title'], 0, 85) . (strlen($relatedPub['title']) > 85 ? '…' : '')); ?>
                        </h3>
                        <p class="rd-related-authors">
                            <?php echo e(substr($relatedPub['authors'], 0, 65) . (strlen($relatedPub['authors']) > 65 ? '…' : '')); ?>
                        </p>
                        <div class="rd-related-footer">
                            <span><i class="far fa-calendar-alt" style="margin-right:4px;"></i>
                                <?php echo date('M Y', strtotime($relatedPub['publication_date'])); ?>
                            </span>
                            <span style="color: var(--purple); font-size:.62rem; letter-spacing:.05em;">
                                View <i class="fas fa-arrow-right"></i>
                            </span>
                        </div>
                    </a>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php endif; ?>

        </div><!-- /.rd-container -->
    </div><!-- /.rd-body -->

</div><!-- /.rd-root -->

<script>
(function () {
    'use strict';

    /* Copy citation button */
    var copyBtn = document.getElementById('rdCopyBtn');
    var citeEl  = document.querySelector('.rd-cite-text');

    if (copyBtn && citeEl) {
        copyBtn.addEventListener('click', function () {
            var text = citeEl.innerText.trim();
            if (navigator.clipboard && text) {
                navigator.clipboard.writeText(text).then(function () {
                    copyBtn.innerHTML = '<i class="fas fa-check"></i> Copied!';
                    copyBtn.style.background = 'var(--purple)';
                    copyBtn.style.color = 'white';
                    setTimeout(function () {
                        copyBtn.innerHTML = '<i class="fas fa-copy"></i> Copy Citation';
                        copyBtn.style.background = '';
                        copyBtn.style.color = '';
                    }, 2200);
                });
            }
        });
    }
})();
</script>