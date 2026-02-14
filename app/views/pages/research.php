<?php
/**
 * Research Publications Page - Professional Redesign (Enhanced Pattern)
 * Complete redesign using the elegant news page pattern
 * Maintains all functionality with improved aesthetics
 * 
 * FIXED: Removed header spacing
 * FIXED: Cards without images now show proper text-only layout
 * FIXED: Hero section uses your original image path
 * FIXED: Light, clean purple applied to hero elements
 * FIXED: Proper side margins - content no longer too close to edges
 * FIXED v5.5: Content inside hero fully padded and centered, never touches edges
 * FIXED v5.5: Fluid clamp()-based gutters that adapt from mobile → 4K
 * 
 * @package FCTCNS
 * @version 5.5
 */

extract($data ?? []);

if (!function_exists('e')) {
    function e($text) {
        return htmlspecialchars($text ?? '', ENT_QUOTES, 'UTF-8');
    }
}

if (!function_exists('getImageUrl')) {
    function getImageUrl($path) {
        global $baseUrl;
        if (empty($path)) return '';
        $path = trim($path);
        if (strpos($path, 'http') === 0 || strpos($path, '//') === 0) return htmlspecialchars($path);
        if (strpos($path, '/uploads/') === 0) return $baseUrl . htmlspecialchars($path);
        if (preg_match('/\.(jpg|jpeg|png|gif|webp)$/i', $path)) return $baseUrl . '/uploads/research/' . htmlspecialchars($path);
        return $baseUrl . '/' . htmlspecialchars($path);
    }
}

$baseUrl = $baseUrl ?? '/';
$featured = $featured ?? [];
$publications = $publications ?? [];
$categories = $categories ?? [];
$searchTerm = $searchTerm ?? '';
$currentCategory = $currentCategory ?? '';
$totalPublications = count($publications);

// Flash messages
$flash_success = $flash_success ?? null;
$flash_error = $flash_error ?? null;
$flash_errors = $flash_errors ?? null;

// Check for scroll parameter
$scrollToPublications = isset($_GET['scroll']) && $_GET['scroll'] === 'publications';

// Use original image paths
$heroImagePath = rtrim($baseUrl, '/') . '/assets/images/research/research-hero.jpg';
$featuredImagePath = rtrim($baseUrl, '/') . '/assets/images/research/featured-research.jpg';
?>

<!-- Google Fonts -->
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@400;500;600;700&family=Outfit:wght@300;400;500;600;700&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<style>
/* ==========================================================================
   CORE RESET
   ========================================================================== */
*, *::before, *::after {
    box-sizing: border-box;
    margin: 0;
    padding: 0;
}

html, body {
    margin: 0 !important;
    padding: 0 !important;
    width: 100%;
    overflow-x: hidden;
}

body {
    min-height: 100vh;
    background-color: #FFFFFF;
}

#main-content,
.rp-content,
main {
    margin: 0 !important;
    padding: 0 !important;
    width: 100%;
}

/* ==========================================================================
   DESIGN TOKENS
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
    --purple-soft:  #A594C9;

    --gold:         #C9A44A;
    --gold-light:   #D8B86C;
    --gold-pale:    #FDF8ED;

    --color-journal:    #6D8EB0;
    --color-conference: #8B7BB8;
    --color-book:       #B08968;
    --color-thesis:     #5D9B8C;

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

    /*
     * KEY SPACING TOKENS — fluid gutters
     * clamp(MIN, PREFERRED_VW, MAX)
     *  - On 320px mobile  → 20px side-gutter
     *  - On 768px tablet  → ~32px
     *  - On 1280px desktop→ ~64px (5vw)
     *  - On 1920px wide   → 96px cap
     *
     * The --container-max caps the readable column width.
     */
    --gutter:         clamp(1.25rem, 5vw, 6rem);
    --container-max:  1400px;
}

/* ==========================================================================
   ROOT SCOPE
   ========================================================================== */
.rp-root {
    font-family: var(--font-body);
    color: var(--ink);
    background: var(--white);
    overflow-x: hidden;
    -webkit-font-smoothing: antialiased;
    width: 100%;
}

/* ==========================================================================
   CONTAINER — always has safe side-gutters, never stretches past max-width
   ========================================================================== */
.rp-container {
    width: 100%;
    max-width: var(--container-max);
    margin-left: auto;
    margin-right: auto;
    padding-left:  var(--gutter);
    padding-right: var(--gutter);
}

/* ==========================================================================
   FLASH MESSAGES
   ========================================================================== */
.rp-flash-messages {
    position: fixed;
    top: 80px;
    right: 20px;
    z-index: 9999;
    max-width: 400px;
    width: calc(100% - 40px);
}

.rp-flash-message {
    padding: 15px 20px;
    margin-bottom: 10px;
    border-radius: var(--radius-md);
    box-shadow: var(--shadow-lg);
    display: flex;
    align-items: center;
    gap: 12px;
    animation: slideInRight 0.3s ease-out;
    backdrop-filter: blur(10px);
    -webkit-backdrop-filter: blur(10px);
    border: 1px solid rgba(255,255,255,0.1);
}

@keyframes slideInRight {
    from { transform: translateX(110%); opacity: 0; }
    to   { transform: translateX(0);   opacity: 1; }
}

.rp-flash-message.success {
    background: linear-gradient(135deg, rgba(76,175,80,0.95), rgba(56,142,60,0.95));
    color: white;
}

.rp-flash-message.error {
    background: linear-gradient(135deg, rgba(244,67,54,0.95), rgba(198,40,40,0.95));
    color: white;
}

.rp-flash-message .icon { font-size: 1.2rem; flex-shrink: 0; }
.rp-flash-message .content { flex: 1; font-size: 0.95rem; line-height: 1.4; }

.rp-flash-message .close-btn {
    background: none;
    border: none;
    color: inherit;
    font-size: 1rem;
    cursor: pointer;
    padding: 4px;
    border-radius: 50%;
    width: 28px; height: 28px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
    opacity: 0.8;
    transition: opacity 0.2s;
}

.rp-flash-message .close-btn:hover {
    opacity: 1;
    background: rgba(255,255,255,0.15);
}

@media (max-width: 480px) {
    .rp-flash-messages {
        top: 70px;
        right: 12px;
        left: 12px;
        max-width: none;
        width: auto;
    }
}

/* ==========================================================================
   HERO — full-bleed background, padded inner content
   ========================================================================== */

/*
 * The hero itself spans 100vw (full bleed).
 * All visible content lives inside .rp-hero-inner which uses the same
 * fluid gutter system as .rp-container so nothing ever touches the edge.
 */
.rp-hero {
    position: relative;
    background: linear-gradient(145deg, #2A2A42 0%, #383856 100%);
    overflow: hidden;
    padding-top:    clamp(3rem, 7vw, 6rem);
    padding-bottom: clamp(3rem, 7vw, 6rem);
    min-height: 520px;
    display: flex;
    align-items: center;

    /* full-bleed trick that works inside any wrapper */
    width: 100vw;
    position: relative;
    left: 50%;
    right: 50%;
    margin-left: -50vw;
    margin-right: -50vw;
}

.rp-hero-bg {
    position: absolute;
    inset: 0;
    background-image: url('<?php echo $heroImagePath; ?>');
    background-size: cover;
    background-position: center;
    opacity: 0.2;
    z-index: 0;
}

.rp-hero::after {
    content: '';
    position: absolute;
    inset: 0;
    background: radial-gradient(circle at 70% 30%, rgba(139,123,184,0.18) 0%, transparent 60%);
    z-index: 1;
    pointer-events: none;
}

/* The inner container uses the same fluid gutter as everywhere else */
.rp-hero-inner {
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
    .rp-hero-inner {
        flex-direction: row;
        align-items: center;
        justify-content: space-between;
    }
}

.rp-hero-left {
    width: 100%;
    max-width: 640px;
    flex-shrink: 0;
}

@media (min-width: 992px) {
    .rp-hero-left  { width: 55%; max-width: none; }
    .rp-hero-right { width: 40%; }
}

/* Badge */
.rp-hero-badge {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    background: rgba(139,123,184,0.2);
    backdrop-filter: blur(8px);
    -webkit-backdrop-filter: blur(8px);
    border: 1px solid rgba(201,164,74,0.35);
    padding: 0.45rem 1.1rem;
    border-radius: 50px;
    margin-bottom: 1.25rem;
}

.rp-hero-badge-icon {
    width: 22px; height: 22px;
    background: var(--purple);
    border-radius: 50%;
    display: flex; align-items: center; justify-content: center;
    color: white;
    font-size: 0.65rem;
}

.rp-hero-badge-text {
    font-family: var(--font-mono);
    font-size: 0.68rem;
    font-weight: 500;
    letter-spacing: 0.1em;
    text-transform: uppercase;
    color: var(--gold);
}

/* Title */
.rp-hero-title {
    font-family: var(--font-display);
    font-size: clamp(2.2rem, 4.5vw, 4rem);
    font-weight: 700;
    line-height: 1.1;
    color: white;
    margin-bottom: 1rem;
    letter-spacing: -0.01em;
    text-shadow: 0 2px 8px rgba(0,0,0,0.2);
}

.rp-hero-title .research-mark {
    color: var(--gold-light);
    font-style: italic;
}

/* Subtitle */
.rp-hero-subtitle {
    font-size: clamp(0.95rem, 1.5vw, 1.15rem);
    color: rgba(255,255,255,0.82);
    font-weight: 300;
    max-width: 540px;
    line-height: 1.65;
    margin-bottom: 2rem;
}

/* Search */
.rp-search-form {
    display: flex;
    width: 100%;
    max-width: 560px;
    border-radius: var(--radius-md);
    overflow: hidden;
    box-shadow: 0 10px 28px rgba(0,0,0,0.18);
    border: 1px solid rgba(255,255,255,0.1);
}

.rp-search-wrap {
    position: relative;
    flex: 1;
    min-width: 0; /* prevents overflow in flex */
}

.rp-search-icon {
    position: absolute;
    left: 1.1rem;
    top: 50%;
    transform: translateY(-50%);
    color: var(--purple);
    pointer-events: none;
    z-index: 2;
    font-size: 0.85rem;
}

.rp-search-input {
    width: 100%;
    height: 52px;
    padding: 0 0.75rem 0 2.8rem;
    border: none;
    background: rgba(255,255,255,0.98);
    color: var(--ink);
    font-family: var(--font-body);
    font-size: 0.9rem;
    caret-color: var(--purple);
    outline: none;
    min-width: 0;
}

.rp-search-input::placeholder { color: var(--mist); }
.rp-search-input:focus { background: white; }

.rp-search-btn {
    height: 52px;
    padding: 0 1.5rem;
    background: var(--purple);
    color: white;
    border: none;
    font-family: var(--font-body);
    font-size: 0.88rem;
    font-weight: 500;
    cursor: pointer;
    display: flex;
    align-items: center;
    gap: 7px;
    white-space: nowrap;
    transition: background 0.2s;
    flex-shrink: 0;
}

.rp-search-btn:hover { background: var(--purple-dark); }

@media (max-width: 420px) {
    .rp-search-form { flex-direction: column; border-radius: var(--radius-md); }
    .rp-search-input { border-radius: var(--radius-sm) var(--radius-sm) 0 0; }
    .rp-search-btn {
        height: 48px;
        border-radius: 0 0 var(--radius-sm) var(--radius-sm);
        justify-content: center;
    }
}

/* Stats panel */
.rp-hero-stats {
    display: flex;
    flex-wrap: wrap;
    gap: clamp(1rem, 3vw, 2rem);
    background: rgba(0, 0, 0, 0.35);
    backdrop-filter: blur(10px);
    -webkit-backdrop-filter: blur(10px);
    border: 1px solid rgba(255,255,255,0.18);
    border-radius: var(--radius-lg);
    padding: clamp(1.25rem, 3vw, 1.75rem) clamp(1.5rem, 3vw, 2rem);
}

.rp-stat {
    display: flex;
    flex-direction: column;
    gap: 4px;
}

.rp-stat-value {
    font-family: var(--font-display);
    font-size: clamp(1.8rem, 3vw, 2.4rem);
    font-weight: 700;
    color: #FFE082;
    line-height: 1;
    text-shadow: 0 1px 4px rgba(0,0,0,0.3);
}

.rp-stat-label {
    font-size: 0.72rem;
    font-weight: 600;
    letter-spacing: 0.08em;
    text-transform: uppercase;
    color: rgba(255,255,255,0.92);
    text-shadow: 0 1px 3px rgba(0,0,0,0.25);
}

/* ==========================================================================
   BREADCRUMB
   ========================================================================== */
.rp-breadcrumb {
    background: var(--white);
    border-bottom: 1px solid var(--border);
    padding: 0.75rem 0;
}

.rp-breadcrumb .rp-container { /* reuses container for safe gutters */ }

.rp-breadcrumb-list {
    display: flex;
    align-items: center;
    gap: 0.4rem;
    list-style: none;
    font-size: 0.8rem;
    flex-wrap: wrap;
}

.rp-breadcrumb-list a {
    color: var(--purple-dark);
    text-decoration: none;
    font-weight: 500;
    transition: color 0.18s;
}
.rp-breadcrumb-list a:hover { color: var(--purple); text-decoration: underline; }
.rp-breadcrumb-sep     { color: var(--mist); }
.rp-breadcrumb-current { color: var(--slate); }

/* ==========================================================================
   SECTION HEADER
   ========================================================================== */
.rp-section-header {
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

.rp-section-title {
    display: flex;
    align-items: center;
    gap: 10px;
    font-family: var(--font-display);
    font-size: clamp(1.35rem, 2.5vw, 1.9rem);
    font-weight: 700;
    color: var(--ink);
    letter-spacing: -0.01em;
}

.rp-section-pip {
    width: 8px; height: 8px;
    border-radius: 50%;
    background: var(--purple);
    flex-shrink: 0;
}

/* ==========================================================================
   BUTTONS
   ========================================================================== */
.rp-btn {
    display: inline-flex;
    align-items: center;
    gap: 7px;
    padding: 0.6rem 1.4rem;
    border-radius: var(--radius-sm);
    font-family: var(--font-body);
    font-size: 0.875rem;
    font-weight: 500;
    text-decoration: none;
    border: none;
    cursor: pointer;
    transition: all 0.22s ease;
    letter-spacing: 0.01em;
    white-space: nowrap;
}

.rp-btn--purple { background: var(--purple); color: white; }
.rp-btn--purple:hover {
    background: var(--purple-dark); color: white;
    transform: translateY(-1px);
    box-shadow: 0 4px 14px rgba(139,123,184,0.32);
}

.rp-btn--ghost {
    background: transparent; color: white;
    border: 1.5px solid rgba(255,255,255,0.32);
}
.rp-btn--ghost:hover { border-color: white; background: rgba(255,255,255,0.1); color: white; }

.rp-btn--outline {
    background: transparent; color: var(--purple);
    border: 1.5px solid var(--purple);
}
.rp-btn--outline:hover { background: var(--purple); color: white; transform: translateY(-1px); }

.rp-btn--surface {
    background: var(--surface); color: var(--ink-soft);
    border: 1px solid var(--border);
}
.rp-btn--surface:hover { background: var(--border); color: var(--ink); }

.rp-btn--gold { background: var(--gold); color: white; }
.rp-btn--gold:hover {
    background: var(--gold-light); color: white;
    transform: translateY(-1px);
    box-shadow: 0 4px 14px rgba(201,164,74,0.3);
}

.rp-btn-sm { padding: 0.38rem 0.9rem; font-size: 0.78rem; }

/* ==========================================================================
   SEARCH RESULTS BANNER
   ========================================================================== */
.rp-search-results {
    background: var(--purple-pale);
    padding: 1.25rem 1.5rem;
    border-radius: var(--radius-md);
    margin: 2rem 0 1.25rem;
    border: 1px solid var(--border);
}

.rp-search-results-header {
    display: flex;
    flex-wrap: wrap;
    gap: 1rem;
    align-items: center;
    justify-content: space-between;
}

.rp-search-tags {
    display: flex;
    flex-wrap: wrap;
    gap: 0.75rem;
    align-items: center;
}

.rp-search-tag {
    background: var(--white);
    padding: 0.45rem 0.9rem;
    border-radius: var(--radius-full);
    border: 1px solid var(--border);
    display: flex;
    align-items: center;
    gap: 0.45rem;
    font-size: 0.83rem;
}

.rp-search-tag.keyword { background: var(--white); color: var(--ink-soft); }
.rp-search-tag.category { background: var(--purple); color: white; border-color: var(--purple); }
.rp-search-tag .remove { color: currentColor; opacity: 0.6; text-decoration: none; margin-left: 0.2rem; }
.rp-search-tag .remove:hover { opacity: 1; }
.rp-search-count { font-weight: 500; color: var(--slate); font-size: 0.88rem; }
.rp-search-clear-all {
    color: var(--purple); text-decoration: none; font-weight: 500;
    display: inline-flex; align-items: center; gap: 0.4rem; font-size: 0.88rem;
}

/* ==========================================================================
   FILTER BAR
   ========================================================================== */
.rp-filter-bar {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 1rem;
    flex-wrap: wrap;
    padding: 0.85rem 1.2rem;
    background: var(--surface);
    border: 1px solid var(--border);
    border-left: 3px solid var(--purple);
    border-radius: var(--radius-md);
    margin-bottom: 2rem;
}

.rp-filter-group {
    display: flex;
    align-items: center;
    gap: 0.7rem;
    flex-wrap: wrap;
}

.rp-filter-label {
    font-size: 0.75rem;
    font-weight: 600;
    color: var(--slate);
    letter-spacing: 0.06em;
    text-transform: uppercase;
    white-space: nowrap;
}

.rp-filter-select {
    height: 38px;
    padding: 0 2rem 0 0.8rem;
    border: 1.5px solid var(--border);
    border-radius: var(--radius-sm);
    background: var(--white);
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 24 24' fill='none' stroke='%2364748B' stroke-width='2.5'%3E%3Cpolyline points='6 9 12 15 18 9'/%3E%3C/svg%3E");
    background-repeat: no-repeat;
    background-position: right 0.6rem center;
    background-size: 11px;
    color: var(--ink-soft);
    font-family: var(--font-body);
    font-size: 0.85rem;
    cursor: pointer;
    appearance: none;
    outline: none;
    transition: border-color 0.2s, box-shadow 0.2s;
    min-width: 120px;
}

.rp-filter-select:hover { border-color: var(--slate); }
.rp-filter-select:focus {
    border-color: var(--purple);
    box-shadow: 0 0 0 3px rgba(139,123,184,0.12);
}

.rp-results-count {
    font-family: var(--font-mono);
    font-size: 0.75rem;
    color: var(--slate);
    white-space: nowrap;
}
.rp-results-count strong { color: var(--ink); }

@media (max-width: 560px) {
    .rp-filter-bar { flex-direction: column; align-items: flex-start; }
    .rp-filter-group { width: 100%; }
    .rp-filter-select { flex: 1; }
}

/* ==========================================================================
   FEATURED PUBLICATION
   ========================================================================== */
.rp-featured-wrap {
    padding: clamp(2rem, 4vw, 3.5rem) 0 1rem;
}

.rp-featured {
    border-radius: var(--radius-xl);
    overflow: hidden;
    background: var(--ink-mid);
    box-shadow: var(--shadow-xl);
    display: grid;
    grid-template-columns: 1fr;
    width: 100%;
}

.rp-featured.has-image {
    grid-template-columns: 1fr;
}

@media (min-width: 700px) {
    .rp-featured.has-image { grid-template-columns: 1fr 1fr; }
}

@media (min-width: 1024px) {
    .rp-featured.has-image { grid-template-columns: 44% 56%; }
}

.rp-featured-img-cell {
    position: relative;
    overflow: hidden;
    background: var(--ink);
    display: none;
    min-height: 280px;
}

.rp-featured.has-image .rp-featured-img-cell { display: block; }

.rp-featured-img {
    width: 100%; height: 100%;
    object-fit: cover;
    display: block;
    transition: transform 6s ease;
}

.rp-featured:hover .rp-featured-img { transform: scale(1.04); }

.rp-featured-img-cell::after {
    content: '';
    position: absolute;
    inset: 0;
    background: linear-gradient(to right, transparent 50%, rgba(26,31,46,0.4) 100%);
    pointer-events: none;
}

@media (max-width: 699px) {
    .rp-featured-img-cell::after {
        background: linear-gradient(to bottom, transparent 40%, rgba(26,31,46,0.7) 100%);
    }
}

.rp-featured-content {
    background: linear-gradient(160deg, #2A3042 0%, #3A4055 100%);
    padding: clamp(1.75rem, 4vw, 3rem);
    display: flex;
    flex-direction: column;
    justify-content: center;
    position: relative;
    width: 100%;
}

.rp-featured-content::before {
    content: '';
    position: absolute;
    left: 0; top: 15%; bottom: 15%;
    width: 3px;
    background: linear-gradient(to bottom, var(--purple-light), var(--purple));
    border-radius: 3px;
}

@media (max-width: 699px) { .rp-featured-content::before { display: none; } }

.rp-featured-tag {
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
    padding: 4px 11px;
    border-radius: 4px;
    margin-bottom: 1rem;
    width: fit-content;
}

.rp-featured-title {
    font-family: var(--font-display);
    font-size: clamp(1.45rem, 2.8vw, 2.1rem);
    font-weight: 700;
    line-height: 1.2;
    color: white;
    margin-bottom: 0.9rem;
    letter-spacing: -0.01em;
}

.rp-featured-authors {
    font-size: 0.92rem;
    color: rgba(255,255,255,0.7);
    margin-bottom: 0.9rem;
    font-style: italic;
}

.rp-featured-authors i { color: var(--gold-light); margin-right: 0.3rem; }

.rp-featured-excerpt {
    font-size: 0.92rem;
    color: rgba(255,255,255,0.62);
    line-height: 1.7;
    margin-bottom: 1.4rem;
    display: -webkit-box;
    -webkit-line-clamp: 3;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

@media (max-width: 699px) { .rp-featured-excerpt { display: block; -webkit-line-clamp: unset; } }

.rp-featured-meta {
    display: flex;
    align-items: center;
    gap: 1.1rem;
    flex-wrap: wrap;
    margin-bottom: 1.5rem;
}

.rp-featured-meta-item {
    display: flex;
    align-items: center;
    gap: 5px;
    font-family: var(--font-mono);
    font-size: 0.68rem;
    color: rgba(255,255,255,0.5);
    letter-spacing: 0.02em;
}

.rp-featured-meta-item i { color: var(--gold-light); font-size: 0.62rem; }

.rp-featured-actions {
    display: flex;
    gap: 0.85rem;
    flex-wrap: wrap;
    align-items: center;
}

/* ==========================================================================
   STATISTICS CARDS
   ========================================================================== */
.rp-stats-section {
    background: var(--surface);
    padding: clamp(2rem, 4vw, 3.5rem) 0;
    border-top: 1px solid var(--border);
    border-bottom: 1px solid var(--border);
}

.rp-stats-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 1.25rem;
}

@media (min-width: 600px) { .rp-stats-grid { grid-template-columns: repeat(4, 1fr); } }

.rp-stat-card {
    background: var(--white);
    padding: 1.6rem 1.25rem;
    border-radius: var(--radius-lg);
    border: 1px solid var(--border);
    text-align: center;
    transition: transform 0.3s ease, box-shadow 0.3s ease, border-color 0.3s;
}

.rp-stat-card:hover {
    transform: translateY(-4px);
    box-shadow: var(--shadow-md);
    border-color: var(--purple-light);
}

.rp-stat-icon {
    width: 50px; height: 50px;
    margin: 0 auto 0.9rem;
    background: var(--purple-pale);
    color: var(--purple);
    border-radius: 50%;
    display: flex; align-items: center; justify-content: center;
    font-size: 1.25rem;
}

.rp-stat-number {
    font-family: var(--font-display);
    font-size: clamp(1.8rem, 3vw, 2.2rem);
    font-weight: 700;
    color: var(--purple);
    line-height: 1.2;
    margin-bottom: 0.2rem;
}

.rp-stat-label {
    font-size: 0.82rem;
    color: var(--slate);
    font-weight: 500;
}

/* ==========================================================================
   RESEARCH AREAS
   ========================================================================== */
.rp-areas-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 1.25rem;
    margin: 1.5rem 0;
}

@media (min-width: 900px)  { .rp-areas-grid { grid-template-columns: repeat(4, 1fr); } }
@media (max-width: 479px)  { .rp-areas-grid { grid-template-columns: 1fr; } }

.rp-area-card {
    background: var(--white);
    border: 1px solid var(--border);
    border-radius: var(--radius-lg);
    padding: 1.65rem 1.35rem;
    text-decoration: none;
    color: inherit;
    transition: all 0.3s ease;
    display: flex;
    flex-direction: column;
    align-items: center;
    text-align: center;
}

.rp-area-card:hover {
    transform: translateY(-4px);
    box-shadow: var(--shadow-lg);
    border-color: var(--purple);
}

.rp-area-icon {
    width: 66px; height: 66px;
    background: var(--purple-pale);
    color: var(--purple);
    border-radius: 50%;
    display: flex; align-items: center; justify-content: center;
    font-size: 1.5rem;
    margin-bottom: 1.1rem;
    transition: all 0.3s ease;
}

.rp-area-card:hover .rp-area-icon { background: var(--purple); color: white; }

.rp-area-title {
    font-family: var(--font-display);
    font-size: 1.25rem;
    font-weight: 600;
    margin-bottom: 0.65rem;
    color: var(--ink);
}

.rp-area-desc {
    font-size: 0.83rem;
    color: var(--slate);
    line-height: 1.6;
    margin-bottom: 1.1rem;
    flex-grow: 1;
}

.rp-area-count {
    font-family: var(--font-mono);
    font-size: 0.78rem;
    color: var(--purple);
    font-weight: 500;
    display: flex; align-items: center; gap: 5px;
}

/* ==========================================================================
   PUBLICATIONS GRID
   ========================================================================== */
.rp-grid {
    display: flex;
    flex-direction: column;
    gap: 1.4rem;
    margin-bottom: 2.5rem;
}

.rp-card {
    display: flex;
    flex-direction: column;
    background: var(--white);
    border: 1px solid var(--border);
    border-radius: var(--radius-lg);
    overflow: hidden;
    transition: transform 0.28s ease, box-shadow 0.28s ease, border-color 0.28s ease;
    position: relative;
    width: 100%;
}

@media (min-width: 700px) { .rp-card { flex-direction: row; } }

.rp-card:hover {
    transform: translateY(-3px);
    box-shadow: var(--shadow-lg);
    border-color: rgba(139,123,184,0.25);
}

.rp-card::before {
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

.rp-card:hover::before { transform: scaleY(1); }

/* Image col */
.rp-card-img-wrap {
    position: relative;
    width: 100%;
    height: 190px;
    flex-shrink: 0;
    overflow: hidden;
    background: var(--surface);
    display: none;
}

.rp-card.has-image .rp-card-img-wrap { display: block; }

@media (min-width: 700px) {
    .rp-card-img-wrap { width: 210px; height: auto; }
}

@media (min-width: 1024px) {
    .rp-card-img-wrap { width: 240px; }
}

.rp-card-img {
    width: 100%; height: 100%;
    object-fit: cover;
    transition: transform 0.5s ease;
    display: block;
}

.rp-card:hover .rp-card-img { transform: scale(1.06); }

.rp-card-type {
    background: var(--purple);
    color: white;
    font-family: var(--font-mono);
    font-size: 0.62rem;
    font-weight: 500;
    letter-spacing: 0.12em;
    text-transform: uppercase;
    padding: 3px 10px;
    border-radius: 4px;
}

/* When image exists, badge is absolutely positioned over the image */
.rp-card-img-wrap .rp-card-type {
    position: absolute;
    top: 0.85rem;
    left: 0.85rem;
    z-index: 2;
}

.rp-card-type.journal    { background: var(--color-journal); }
.rp-card-type.conference { background: var(--color-conference); }
.rp-card-type.book       { background: var(--color-book); }
.rp-card-type.thesis     { background: var(--color-thesis); }

/* Card body */
.rp-card-body {
    padding: clamp(1.2rem, 2.5vw, 1.75rem);
    flex: 1;
    display: flex;
    flex-direction: column;
    gap: 0;
    min-width: 0; /* prevents overflow */
    width: 100%;
}

.rp-card-header {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    margin-bottom: 0.7rem;
    flex-wrap: wrap;
}

.rp-card-date {
    font-family: var(--font-mono);
    font-size: 0.68rem;
    color: var(--mist);
    display: flex; align-items: center; gap: 0.25rem;
}

.rp-card-featured {
    background: var(--gold);
    color: white;
    font-size: 0.58rem;
    font-weight: 600;
    padding: 2px 9px;
    border-radius: var(--radius-full);
    display: inline-flex; align-items: center; gap: 4px;
}

.rp-card-title {
    font-family: var(--font-display);
    font-size: clamp(1.15rem, 2vw, 1.45rem);
    font-weight: 700;
    line-height: 1.3;
    color: var(--ink);
    margin-bottom: 0.65rem;
    letter-spacing: -0.01em;
}

.rp-card-title a {
    color: inherit;
    text-decoration: none;
    transition: color 0.2s;
}

.rp-card-title a:hover { color: var(--purple); }

.rp-card-authors {
    font-size: 0.87rem;
    color: var(--slate);
    margin-bottom: 0.9rem;
    font-style: italic;
    line-height: 1.5;
}

.rp-card-authors i { color: var(--purple-light); margin-right: 0.3rem; }

.rp-card-abstract {
    font-size: 0.87rem;
    color: var(--slate);
    line-height: 1.7;
    flex: 1;
    margin-bottom: 1.4rem;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.rp-card-footer {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 0.75rem;
    padding-top: 1.1rem;
    border-top: 1px solid var(--border);
    flex-wrap: wrap;
}

.rp-card-tags {
    display: flex;
    gap: 0.45rem;
    flex-wrap: wrap;
}

.rp-card-tag {
    padding: 0.22rem 0.7rem;
    background: var(--surface);
    color: var(--slate);
    border-radius: var(--radius-full);
    font-size: 0.68rem;
    font-weight: 500;
    border: 1px solid var(--border);
}

.rp-card-tag.doi {
    font-family: var(--font-mono);
    background: var(--white);
    color: var(--purple);
}

.rp-card-meta {
    display: flex;
    align-items: center;
    gap: 0.9rem;
}

.rp-card-meta-item {
    display: flex; align-items: center; gap: 4px;
    font-family: var(--font-mono);
    font-size: 0.68rem;
    color: var(--mist);
}

.rp-card-meta-item i { color: var(--slate); font-size: 0.62rem; }

.rp-card-actions {
    display: flex;
    gap: 0.45rem;
}

@media (max-width: 560px) {
    .rp-card-footer { flex-direction: column; align-items: flex-start; }
    .rp-card-actions { width: 100%; justify-content: flex-end; }
}

/* ==========================================================================
   PAGINATION
   ========================================================================== */
.rp-pagination {
    display: flex;
    justify-content: center;
    margin-top: 3rem;
}

.rp-pagination-list {
    display: flex;
    gap: 5px;
    list-style: none;
    flex-wrap: wrap;
    justify-content: center;
}

.rp-pagination-link {
    min-width: 40px;
    height: 40px;
    display: flex; align-items: center; justify-content: center;
    padding: 0 0.7rem;
    background: var(--white);
    border: 1.5px solid var(--border);
    border-radius: var(--radius-sm);
    color: var(--ink-soft);
    font-family: var(--font-mono);
    font-size: 0.82rem;
    font-weight: 500;
    text-decoration: none;
    transition: all 0.2s;
}

.rp-pagination-link:hover:not(.active) {
    background: var(--purple); color: white; border-color: var(--purple);
}

.rp-pagination-link.active {
    background: var(--purple); color: white; border-color: var(--purple);
}

/* ==========================================================================
   EMPTY STATE
   ========================================================================== */
.rp-empty {
    text-align: center;
    padding: clamp(2.5rem, 6vw, 4.5rem) clamp(1.5rem, 4vw, 3rem);
    background: var(--surface);
    border-radius: var(--radius-xl);
    border: 1px dashed var(--border);
}

.rp-empty-icon {
    width: 76px; height: 76px;
    margin: 0 auto 1.4rem;
    background: var(--white);
    border: 1px solid var(--border);
    border-radius: 50%;
    display: flex; align-items: center; justify-content: center;
    font-size: 1.9rem;
    color: var(--mist);
}

.rp-empty-title {
    font-family: var(--font-display);
    font-size: 1.6rem;
    font-weight: 700;
    color: var(--ink-soft);
    margin-bottom: 0.7rem;
}

.rp-empty-desc {
    font-size: 0.92rem;
    color: var(--slate);
    margin-bottom: 1.75rem;
    max-width: 400px;
    margin-left: auto;
    margin-right: auto;
    line-height: 1.7;
}

/* ==========================================================================
   SECTION VERTICAL SPACING HELPERS
   ========================================================================== */
.section-gap-top    { padding-top:    clamp(2rem, 4vw, 3.5rem); }
.section-gap-bottom { padding-bottom: clamp(2rem, 4vw, 3.5rem); }
.section-gap        { padding-top:    clamp(2rem, 4vw, 3.5rem);
                      padding-bottom: clamp(2rem, 4vw, 3.5rem); }

/* ==========================================================================
   ANIMATIONS
   ========================================================================== */
@keyframes rp-fadeIn {
    from { opacity: 0; transform: translateY(14px); }
    to   { opacity: 1; transform: translateY(0); }
}

.rp-card { animation: rp-fadeIn 0.4s ease both; }
.rp-card:nth-child(1) { animation-delay: 0.05s; }
.rp-card:nth-child(2) { animation-delay: 0.10s; }
.rp-card:nth-child(3) { animation-delay: 0.15s; }
.rp-card:nth-child(4) { animation-delay: 0.20s; }
.rp-card:nth-child(5) { animation-delay: 0.25s; }
.rp-card:nth-child(n+6) { animation-delay: 0.30s; }

@media (prefers-reduced-motion: reduce) {
    .rp-card, .rp-featured-img, .rp-stat-card, .rp-area-card {
        animation: none !important;
        transition: none !important;
    }
}

/* ==========================================================================
   PRINT
   ========================================================================== */
@media print {
    .rp-hero, .rp-search-form, .rp-btn, .rp-filter-bar { display: none !important; }
    .rp-card { border: 1px solid #000; break-inside: avoid; page-break-inside: avoid; }
}
</style>

<!-- Flash Messages -->
<div class="rp-flash-messages">
    <?php if (!empty($flash_success)): ?>
    <div class="rp-flash-message success">
        <div class="icon"><i class="fas fa-check-circle"></i></div>
        <div class="content"><?php echo e($flash_success); ?></div>
        <button class="close-btn" onclick="this.parentElement.remove()"><i class="fas fa-times"></i></button>
    </div>
    <?php endif; ?>

    <?php if (!empty($flash_error)): ?>
    <div class="rp-flash-message error">
        <div class="icon"><i class="fas fa-exclamation-circle"></i></div>
        <div class="content"><?php echo e($flash_error); ?></div>
        <button class="close-btn" onclick="this.parentElement.remove()"><i class="fas fa-times"></i></button>
    </div>
    <?php endif; ?>

    <?php if (!empty($flash_errors) && is_array($flash_errors)): ?>
        <?php foreach ($flash_errors as $error): ?>
        <div class="rp-flash-message error">
            <div class="icon"><i class="fas fa-exclamation-triangle"></i></div>
            <div class="content"><?php echo e($error); ?></div>
            <button class="close-btn" onclick="this.parentElement.remove()"><i class="fas fa-times"></i></button>
        </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

<!-- =====================================================================
     MAIN CONTENT
     ===================================================================== -->
<main id="main-content" class="rp-root" role="main">

    <!-- ── HERO ─────────────────────────────────────────────────────────── -->
    <section class="rp-hero" aria-label="Research publications hero">
        <div class="rp-hero-bg"></div>

        <div class="rp-hero-inner">

            <!-- Left: headline + search -->
            <div class="rp-hero-left">
                <div class="rp-hero-badge">
                    <span class="rp-hero-badge-icon"><i class="fas fa-flask"></i></span>
                    <span class="rp-hero-badge-text">Research &amp; Innovation</span>
                </div>

                <h1 class="rp-hero-title">
                    Advancing Nursing <span class="research-mark">Science</span>
                </h1>

                <p class="rp-hero-subtitle">
                    Explore peer-reviewed publications, clinical studies, and academic research from FCT College of Nursing Sciences faculty and students.
                </p>

                <form class="rp-search-form" method="GET" action="/research" role="search">
                    <div class="rp-search-wrap">
                        <i class="fas fa-search rp-search-icon" aria-hidden="true"></i>
                        <input type="search"
                               name="search"
                               class="rp-search-input"
                               placeholder="Search by title, author, keywords, DOI…"
                               aria-label="Search publications"
                               value="<?php echo e($searchTerm); ?>"
                               id="searchInput">
                    </div>
                    <button type="submit" class="rp-search-btn" id="searchSubmit">
                        <i class="fas fa-search" aria-hidden="true"></i>
                        Search
                    </button>
                </form>
            </div>

            <!-- Right: stats panel -->
            <div class="rp-hero-right">
                <div class="rp-hero-stats">
                    <div class="rp-stat">
                        <span class="rp-stat-value"><?php echo count($publications) ?: '45'; ?></span>
                        <span class="rp-stat-label">Publications</span>
                    </div>
                    <div class="rp-stat">
                        <span class="rp-stat-value"><?php echo count($categories) ?: '12'; ?></span>
                        <span class="rp-stat-label">Research Areas</span>
                    </div>
                    <div class="rp-stat">
                        <span class="rp-stat-value">1.2K</span>
                        <span class="rp-stat-label">Citations</span>
                    </div>
                </div>
            </div>

        </div><!-- /.rp-hero-inner -->
    </section>

    <!-- ── BREADCRUMB ────────────────────────────────────────────────────── -->
    <nav class="rp-breadcrumb" aria-label="Breadcrumb">
        <div class="rp-container">
            <ul class="rp-breadcrumb-list">
                <li><a href="<?php echo $baseUrl; ?>"><i class="fas fa-home" aria-hidden="true"></i> Home</a></li>
                <li><span class="rp-breadcrumb-sep">/</span></li>
                <li><span class="rp-breadcrumb-current" aria-current="page">Research Publications</span></li>
            </ul>
        </div>
    </nav>

    <!-- ── SEARCH RESULTS BANNER ────────────────────────────────────────── -->
    <?php if ($searchTerm || $currentCategory): ?>
    <div class="rp-container">
        <div class="rp-search-results">
            <div class="rp-search-results-header">
                <div class="rp-search-tags">
                    <?php if ($searchTerm): ?>
                    <div class="rp-search-tag keyword">
                        <i class="fas fa-search"></i>
                        "<?php echo e($searchTerm); ?>"
                        <a href="?<?php echo $currentCategory ? 'category=' . urlencode($currentCategory) : ''; ?>&scroll=publications" class="remove">
                            <i class="fas fa-times"></i>
                        </a>
                    </div>
                    <?php endif; ?>

                    <?php if ($currentCategory):
                        $categoryName = '';
                        foreach ($categories as $cat) {
                            if ($cat['slug'] == $currentCategory) { $categoryName = $cat['name']; break; }
                        }
                    ?>
                    <div class="rp-search-tag category">
                        <i class="fas fa-folder"></i>
                        <?php echo e($categoryName ?: $currentCategory); ?>
                        <a href="?<?php echo $searchTerm ? 'search=' . urlencode($searchTerm) : ''; ?>&scroll=publications" class="remove">
                            <i class="fas fa-times"></i>
                        </a>
                    </div>
                    <?php endif; ?>

                    <span class="rp-search-count">
                        Found <strong><?php echo count($publications); ?></strong>
                        publication<?php echo count($publications) !== 1 ? 's' : ''; ?>
                    </span>
                </div>

                <a href="/research" class="rp-search-clear-all">
                    <i class="fas fa-undo-alt"></i> Clear all filters
                </a>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <!-- ── EMPTY STATE (no data at all) ─────────────────────────────────── -->
    <?php if (empty($publications) && empty($featured)): ?>
    <div class="rp-container section-gap">
        <div class="rp-empty">
            <div class="rp-empty-icon"><i class="fas fa-flask"></i></div>
            <h2 class="rp-empty-title">No Publications Yet</h2>
            <p class="rp-empty-desc">Our research archive is being updated. Please check back soon for new publications.</p>
            <a href="<?php echo $baseUrl; ?>" class="rp-btn rp-btn--purple">
                <i class="fas fa-home" aria-hidden="true"></i> Return Home
            </a>
        </div>
    </div>

    <?php else: ?>

    <!-- ── STATISTICS SECTION ───────────────────────────────────────────── -->
    <section class="rp-stats-section" aria-label="Research statistics">
        <div class="rp-container">
            <div class="rp-section-header">
                <h2 class="rp-section-title">
                    <span class="rp-section-pip"></span>
                    Research Impact
                </h2>
            </div>
            <div class="rp-stats-grid">
                <div class="rp-stat-card">
                    <div class="rp-stat-icon"><i class="fas fa-file-alt"></i></div>
                    <div class="rp-stat-number"><?php echo count($publications) ?: '45'; ?></div>
                    <div class="rp-stat-label">Publications</div>
                </div>
                <div class="rp-stat-card">
                    <div class="rp-stat-icon"><i class="fas fa-users"></i></div>
                    <div class="rp-stat-number">50+</div>
                    <div class="rp-stat-label">Researchers</div>
                </div>
                <div class="rp-stat-card">
                    <div class="rp-stat-icon"><i class="fas fa-graduation-cap"></i></div>
                    <div class="rp-stat-number"><?php echo count($categories) ?: '12'; ?></div>
                    <div class="rp-stat-label">Research Areas</div>
                </div>
                <div class="rp-stat-card">
                    <div class="rp-stat-icon"><i class="fas fa-quote-right"></i></div>
                    <div class="rp-stat-number">1.2K+</div>
                    <div class="rp-stat-label">Citations</div>
                </div>
            </div>
        </div>
    </section>

    <!-- ── FEATURED PUBLICATION ─────────────────────────────────────────── -->
    <?php if (!empty($featured)):
        $featuredItem = $featured[0];
        $hasFeaturedImage = !empty($featuredItem['featured_image']);
        $fImg = $hasFeaturedImage ? getImageUrl($featuredItem['featured_image']) : $featuredImagePath;
        $featuredClass = $hasFeaturedImage ? 'has-image' : '';
    ?>
    <div class="rp-container">
        <div class="rp-featured-wrap">
            <article class="rp-featured <?php echo $featuredClass; ?>">
                <?php if ($hasFeaturedImage): ?>
                <div class="rp-featured-img-cell">
                    <img src="<?php echo $fImg; ?>"
                         alt="<?php echo e($featuredItem['title']); ?>"
                         class="rp-featured-img"
                         onerror="this.style.display='none'; this.parentElement.style.display='none';">
                </div>
                <?php endif; ?>

                <div class="rp-featured-content">
                    <span class="rp-featured-tag">
                        <i class="fas fa-star" aria-hidden="true"></i>
                        Featured Research
                    </span>
                    <h2 class="rp-featured-title"><?php echo e($featuredItem['title']); ?></h2>
                    <p class="rp-featured-authors">
                        <i class="fas fa-users"></i> <?php echo e($featuredItem['authors']); ?>
                    </p>
                    <p class="rp-featured-excerpt">
                        <?php echo e(substr(strip_tags($featuredItem['abstract']), 0, 200)); ?>...
                    </p>
                    <div class="rp-featured-meta">
                        <span class="rp-featured-meta-item">
                            <i class="far fa-calendar-alt"></i>
                            <?php echo date('M d, Y', strtotime($featuredItem['publication_date'])); ?>
                        </span>
                        <span class="rp-featured-meta-item">
                            <i class="fas fa-tag"></i>
                            <?php echo ucfirst($featuredItem['publication_type']); ?>
                        </span>
                        <span class="rp-featured-meta-item">
                            <i class="far fa-eye"></i>
                            <?php echo e($featuredItem['views_count']); ?> views
                        </span>
                    </div>
                    <div class="rp-featured-actions">
                        <a href="/research/<?php echo e($featuredItem['id']); ?>" class="rp-btn rp-btn--purple">
                            Read Full Paper <i class="fas fa-arrow-right"></i>
                        </a>
                        <?php if (!empty($featuredItem['file_path'])): ?>
                        <a href="/research/<?php echo e($featuredItem['id']); ?>/download" class="rp-btn rp-btn--ghost">
                            <i class="fas fa-download"></i> Download PDF
                        </a>
                        <?php endif; ?>
                    </div>
                </div>
            </article>
        </div>
    </div>
    <?php endif; ?>

    <!-- ── RESEARCH AREAS ───────────────────────────────────────────────── -->
    <?php if (!empty($categories)): ?>
    <div class="rp-container section-gap">
        <div class="rp-section-header">
            <h2 class="rp-section-title">
                <span class="rp-section-pip"></span>
                Research Areas
            </h2>
            <a href="#publications" class="rp-btn rp-btn--surface">
                Browse All <i class="fas fa-arrow-right"></i>
            </a>
        </div>

        <div class="rp-areas-grid">
            <?php foreach (array_slice($categories, 0, 8) as $category): ?>
            <a href="/research?category=<?php echo e($category['slug']); ?>&scroll=publications" class="rp-area-card">
                <div class="rp-area-icon"><i class="fas fa-flask"></i></div>
                <h3 class="rp-area-title"><?php echo e($category['name']); ?></h3>
                <p class="rp-area-desc">
                    <?php echo e(substr($category['description'] ?? 'Research publications in this area', 0, 80)); ?>...
                </p>
                <span class="rp-area-count">
                    <?php echo $category['count'] ?? '0'; ?> publications <i class="fas fa-arrow-right"></i>
                </span>
            </a>
            <?php endforeach; ?>
        </div>
    </div>
    <?php endif; ?>

    <!-- ── ALL PUBLICATIONS ─────────────────────────────────────────────── -->
    <div id="publications" class="rp-container section-gap-bottom" style="padding-top: 2rem;">
        <div class="rp-section-header">
            <h2 class="rp-section-title">
                <span class="rp-section-pip"></span>
                All Publications
            </h2>
            <span class="rp-results-count">
                <strong><?php echo count($publications); ?></strong> of
                <strong><?php echo $totalPublications ?: count($publications); ?></strong> articles
            </span>
        </div>

        <!-- Filter Bar -->
        <div class="rp-filter-bar">
            <div class="rp-filter-group">
                <span class="rp-filter-label">Filter</span>

                <select class="rp-filter-select" id="categorySelect" aria-label="Filter by category">
                    <option value="">All Categories</option>
                    <?php foreach ($categories as $category): ?>
                    <option value="<?php echo e($category['slug']); ?>"
                        <?php echo ($currentCategory == $category['slug']) ? 'selected' : ''; ?>>
                        <?php echo e($category['name']); ?>
                    </option>
                    <?php endforeach; ?>
                </select>

                <select class="rp-filter-select" id="typeFilter" aria-label="Filter by publication type">
                    <option value="">All Types</option>
                    <option value="journal">Journal Articles</option>
                    <option value="conference">Conference Papers</option>
                    <option value="book">Books &amp; Chapters</option>
                    <option value="thesis">Theses</option>
                </select>

                <select class="rp-filter-select" id="sortFilter" aria-label="Sort by">
                    <option value="latest">Latest First</option>
                    <option value="oldest">Oldest First</option>
                    <option value="popular">Most Viewed</option>
                </select>
            </div>

            <?php if ($searchTerm || $currentCategory): ?>
            <a href="/research" class="rp-btn rp-btn--outline rp-btn-sm">
                <i class="fas fa-times"></i> Clear Filters
            </a>
            <?php endif; ?>
        </div>

        <?php if (empty($publications)): ?>
        <!-- No results -->
        <div class="rp-empty">
            <div class="rp-empty-icon"><i class="fas fa-search" aria-hidden="true"></i></div>
            <h3 class="rp-empty-title">No Publications Found</h3>
            <p class="rp-empty-desc">
                <?php if ($searchTerm || $currentCategory): ?>
                    No publications match your search criteria. Try different keywords or browse all publications.
                <?php else: ?>
                    There are currently no publications available. Please check back soon.
                <?php endif; ?>
            </p>
            <?php if ($searchTerm || $currentCategory): ?>
            <a href="/research" class="rp-btn rp-btn--purple">
                <i class="fas fa-undo-alt"></i> View All Publications
            </a>
            <?php endif; ?>
        </div>

        <?php else: ?>

        <div class="rp-grid" id="publications-grid">
            <?php foreach ($publications as $pub):
                $hasImage  = !empty($pub['featured_image']);
                $pubImg    = $hasImage ? getImageUrl($pub['featured_image']) : $featuredImagePath;
                $cardClass = $hasImage ? 'has-image' : '';
            ?>
            <article class="rp-card <?php echo $cardClass; ?>">

                <!-- Image column (only when image exists) -->
                <?php if ($hasImage): ?>
                <div class="rp-card-img-wrap">
                    <img src="<?php echo $pubImg; ?>"
                         alt="<?php echo e($pub['title']); ?>"
                         class="rp-card-img"
                         loading="lazy"
                         onerror="this.style.display='none'; this.parentElement.style.display='none';">
                    <span class="rp-card-type <?php echo e($pub['publication_type']); ?>">
                        <?php echo ucfirst($pub['publication_type']); ?>
                    </span>
                </div>
                <?php endif; ?>

                <!-- Content column -->
                <div class="rp-card-body">
                    <div class="rp-card-header">
                        <span class="rp-card-date">
                            <i class="far fa-calendar-alt"></i>
                            <?php echo date('M d, Y', strtotime($pub['publication_date'])); ?>
                        </span>

                        <?php if (!$hasImage): ?>
                        <!-- Type badge inline when no image -->
                        <span class="rp-card-type <?php echo e($pub['publication_type']); ?>" style="margin-left: auto;">
                            <?php echo ucfirst($pub['publication_type']); ?>
                        </span>
                        <?php endif; ?>

                        <?php if ($pub['is_featured']): ?>
                        <span class="rp-card-featured">
                            <i class="fas fa-star"></i> Featured
                        </span>
                        <?php endif; ?>
                    </div>

                    <h3 class="rp-card-title">
                        <a href="/research/<?php echo e($pub['id']); ?>">
                            <?php echo e($pub['title']); ?>
                        </a>
                    </h3>

                    <p class="rp-card-authors">
                        <i class="fas fa-users"></i> <?php echo e($pub['authors']); ?>
                    </p>

                    <p class="rp-card-abstract">
                        <?php echo e(substr(strip_tags($pub['abstract']), 0, 200)); ?>...
                    </p>

                    <footer class="rp-card-footer">
                        <div class="rp-card-tags">
                            <span class="rp-card-tag">
                                <?php echo e($pub['category_name'] ?? $pub['research_area']); ?>
                            </span>
                            <?php if (!empty($pub['doi'])): ?>
                            <span class="rp-card-tag doi">DOI: <?php echo e($pub['doi']); ?></span>
                            <?php endif; ?>
                        </div>

                        <div class="rp-card-meta">
                            <span class="rp-card-meta-item">
                                <i class="far fa-eye"></i> <?php echo e($pub['views_count']); ?>
                            </span>
                            <span class="rp-card-meta-item">
                                <i class="far fa-download"></i> <?php echo e($pub['downloads_count']); ?>
                            </span>
                        </div>

                        <div class="rp-card-actions">
                            <?php if (!empty($pub['file_path'])): ?>
                            <a href="/research/<?php echo e($pub['id']); ?>/download"
                               class="rp-btn rp-btn--outline rp-btn-sm"
                               title="Download PDF">
                                <i class="fas fa-download"></i>
                            </a>
                            <?php endif; ?>
                            <a href="/research/<?php echo e($pub['id']); ?>"
                               class="rp-btn rp-btn--purple rp-btn-sm">
                                View Details
                            </a>
                        </div>
                    </footer>
                </div>

            </article>
            <?php endforeach; ?>
        </div>

        <!-- Pagination -->
        <?php if ($totalPublications > 10): ?>
        <nav class="rp-pagination" aria-label="Page navigation">
            <ul class="rp-pagination-list">
                <li>
                    <a href="?page=1" class="rp-pagination-link" aria-label="First page">
                        <i class="fas fa-chevron-left"></i><i class="fas fa-chevron-left"></i>
                    </a>
                </li>
                <li>
                    <a href="?page=1" class="rp-pagination-link" aria-label="Previous page">
                        <i class="fas fa-chevron-left"></i>
                    </a>
                </li>
                <li><a href="?page=1" class="rp-pagination-link active" aria-current="page">1</a></li>
                <li><a href="?page=2" class="rp-pagination-link">2</a></li>
                <li><a href="?page=3" class="rp-pagination-link">3</a></li>
                <li><span class="rp-pagination-link" style="border:none;background:transparent;">…</span></li>
                <li><a href="?page=10" class="rp-pagination-link">10</a></li>
                <li>
                    <a href="?page=2" class="rp-pagination-link" aria-label="Next page">
                        <i class="fas fa-chevron-right"></i>
                    </a>
                </li>
                <li>
                    <a href="?page=10" class="rp-pagination-link" aria-label="Last page">
                        <i class="fas fa-chevron-right"></i><i class="fas fa-chevron-right"></i>
                    </a>
                </li>
            </ul>
        </nav>
        <?php endif; ?>

        <?php endif; // end has publications ?>
    </div>

    <?php endif; // end has any data ?>

</main>

<!-- =====================================================================
     JAVASCRIPT
     ===================================================================== -->
<script>
(function () {
    'use strict';

    /* Flash auto-dismiss (5s) */
    setTimeout(function () {
        document.querySelectorAll('.rp-flash-message').forEach(function (msg) {
            msg.style.opacity    = '0';
            msg.style.transform  = 'translateX(110%)';
            msg.style.transition = 'opacity .3s, transform .3s';
            setTimeout(function () { if (msg.parentNode) msg.parentNode.removeChild(msg); }, 320);
        });
    }, 5000);

    document.querySelectorAll('.rp-flash-message .close-btn').forEach(function (btn) {
        btn.addEventListener('click', function () {
            var msg = this.closest('.rp-flash-message');
            msg.style.opacity   = '0';
            msg.style.transform = 'translateX(110%)';
            msg.style.transition= 'opacity .25s, transform .25s';
            setTimeout(function () { if (msg.parentNode) msg.parentNode.removeChild(msg); }, 260);
        });
    });

    /* Search form */
    var searchForm   = document.querySelector('.rp-search-form');
    var searchInput  = document.getElementById('searchInput');
    var categorySelect = document.getElementById('categorySelect');

    if (searchForm) {
        searchForm.addEventListener('submit', function (e) {
            if (!searchInput.value.trim() && !categorySelect.value) {
                e.preventDefault(); return false;
            }
        });
    }

    /* Category filter */
    if (categorySelect) {
        categorySelect.addEventListener('change', function () {
            var url = new URL(window.location.href);
            if (this.value) { url.searchParams.set('category', this.value); }
            else            { url.searchParams.delete('category'); }
            url.searchParams.set('scroll', 'publications');
            window.location.href = url.pathname + url.search;
        });
    }

    /* Sort filter */
    var sortFilter = document.getElementById('sortFilter');
    if (sortFilter) {
        var urlParams = new URLSearchParams(window.location.search);
        var sortVal   = urlParams.get('sort');
        if (sortVal) sortFilter.value = sortVal;

        sortFilter.addEventListener('change', function () {
            var url = new URL(window.location.href);
            url.searchParams.set('sort',   this.value);
            url.searchParams.set('scroll', 'publications');
            window.location.href = url.pathname + url.search;
        });
    }

    /* Keyboard shortcuts */
    document.addEventListener('keydown', function (e) {
        if ((e.ctrlKey || e.metaKey) && e.key === 'k') {
            e.preventDefault();
            if (searchInput) searchInput.focus();
        }
        if (e.key === 'Escape' && document.activeElement === searchInput) {
            searchInput.value = '';
        }
    });

    /* Highlight search terms */
    var searchTerm = '<?php echo addslashes($searchTerm); ?>';
    if (searchTerm && searchTerm.trim()) {
        var terms = searchTerm.toLowerCase().split(' ').filter(function (t) { return t.length > 2; });
        if (terms.length > 0) {
            var targets = document.querySelectorAll(
                '.rp-card-title, .rp-card-abstract, .rp-card-authors, ' +
                '.rp-featured-title, .rp-featured-authors, .rp-featured-excerpt'
            );
            targets.forEach(function (el) {
                var html = el.innerHTML;
                terms.forEach(function (term) {
                    var regex = new RegExp('(' + term.replace(/[.*+?^${}()|[\]\\]/g,'\\$&') + ')', 'gi');
                    html = html.replace(regex,
                        '<mark style="background:var(--gold-pale);padding:0 2px;border-radius:2px;">$1</mark>'
                    );
                });
                el.innerHTML = html;
            });
        }
    }

    /* Scroll to publications anchor */
    <?php if ($scrollToPublications): ?>
    setTimeout(function () {
        var sec = document.getElementById('publications');
        if (sec) sec.scrollIntoView({ behavior: 'smooth', block: 'start' });
    }, 300);
    <?php endif; ?>

})();
</script>