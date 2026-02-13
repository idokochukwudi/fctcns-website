<?php
/**
 * News Index Page - Enhanced
 * File: /app/views/pages/news/index.php
 * Rendered inside main layout (no <html>/<head>/<body> wrapper needed)
 */

$baseUrl       = $baseUrl       ?? (defined('BASE_URL') ? BASE_URL : '');
$news          = $news          ?? [];
$featuredNews  = $featuredNews  ?? [];
$categories    = $categories    ?? [];
$archiveMonths = $archiveMonths ?? [];
$popularNews   = $popularNews   ?? [];
$pagination    = $pagination    ?? ['current' => 1, 'total' => 0, 'limit' => 10, 'totalCount' => 0];
$hasNews       = !empty($news) || !empty($featuredNews);

if (!function_exists('getImageUrl')) {
    function getImageUrl($path) {
        global $baseUrl;
        if (empty($path)) return '';
        $path = trim($path);
        if (strpos($path, 'http') === 0 || strpos($path, '//') === 0) return htmlspecialchars($path);
        if (strpos($path, '/uploads/') === 0) return $baseUrl . htmlspecialchars($path);
        if (preg_match('/\.(jpg|jpeg|png|gif|webp)$/i', $path)) return $baseUrl . '/uploads/news/' . htmlspecialchars($path);
        return $baseUrl . '/' . htmlspecialchars($path);
    }
}
?>

<!-- Google Fonts -->
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
/* ═══════════════════════════════════════════════════
   DESIGN TOKENS
═══════════════════════════════════════════════════ */
:root {
    --ink:          #0E1117;
    --ink-mid:      #1C2333;
    --ink-soft:     #2D3748;
    --slate:        #64748B;
    --mist:         #94A3B8;
    --border:       #E2E8F0;
    --surface:      #F8FAFC;
    --white:        #FFFFFF;

    /* Primary: soft purple — blends with navy + gold palette */
    --purple:       #7C6FAB;
    --purple-dark:  #5A4F8A;
    --purple-pale:  #F0EEF9;
    --purple-mid:   #9B8FCC;

    /* Gold accent — unchanged, pairs perfectly with purple */
    --gold:         #B8860B;
    --gold-light:   #D4A520;
    --gold-pale:    #FFFBEB;

    --purple-glow:  rgba(124,111,171,0.14);

    --font-display: 'Cormorant Garamond', Georgia, serif;
    --font-body:    'Outfit', system-ui, sans-serif;
    --font-mono:    'JetBrains Mono', monospace;

    --radius-sm:    6px;
    --radius-md:    12px;
    --radius-lg:    20px;
    --radius-xl:    28px;

    --shadow-xs:    0 1px 3px rgba(0,0,0,0.06);
    --shadow-sm:    0 2px 8px rgba(0,0,0,0.07);
    --shadow-md:    0 6px 24px rgba(0,0,0,0.08);
    --shadow-lg:    0 16px 48px rgba(0,0,0,0.1);
    --shadow-xl:    0 32px 80px rgba(0,0,0,0.12);
}

/* ═══════════════════════════════════════════════════
   HERO-SECTION ALIAS
   The full-width override snippet uses .hero-section.
   We alias it here so the snippet works without any
   changes — just paste it and the hero fills the page.
═══════════════════════════════════════════════════ */
.np-hero,
.hero-section {
    /* Both selectors share full-width treatment when
       the override snippet is active */
}

/* When the override snippet is pasted it sets:
   width:100vw; left:50%; margin-left:-50vw etc.
   The container inside stays centred automatically
   because .np-container uses margin: 0 auto.        */

/* ═══════════════════════════════════════════════════
   SCOPED RESET
═══════════════════════════════════════════════════ */
.np-root *, .np-root *::before, .np-root *::after {
    box-sizing: border-box;
}

.np-root {
    font-family: var(--font-body);
    color: var(--ink);
    background: var(--white);
    overflow-x: hidden;
    -webkit-font-smoothing: antialiased;
    width: 100%;
}

/* ═══════════════════════════════════════════════════
   CONTAINER
═══════════════════════════════════════════════════ */
.np-container {
    width: 100%;
    max-width: 1320px;
    margin: 0 auto;
    padding-left:  clamp(1rem, 4vw, 2.5rem);
    padding-right: clamp(1rem, 4vw, 2.5rem);
}

.np-container--narrow {
    max-width: 960px;
}

/* ═══════════════════════════════════════════════════
   HERO SECTION
   The .hero-section override you paste in expands
   width to 100vw. All content is centred inside
   .np-container so it stays readable at any width.
═══════════════════════════════════════════════════ */
.np-hero {
    position: relative;
    background: linear-gradient(145deg, #16152A 0%, #1A1B30 35%, var(--ink-mid) 100%);
    overflow: hidden;
    padding: clamp(3.5rem, 8vw, 6rem) 0 clamp(3rem, 6vw, 5rem);
    /* When parent applies .hero-section full-width hack,
       the hero expands correctly. No breakout needed here —
       the content centres itself via np-container. */
}

/* Diagonal stripe texture */
.np-hero::before {
    content: '';
    position: absolute;
    inset: 0;
    background-image:
        repeating-linear-gradient(
            -55deg,
            transparent,
            transparent 40px,
            rgba(255,255,255,0.018) 40px,
            rgba(255,255,255,0.018) 41px
        );
    z-index: 1;
    pointer-events: none;
}

/* Crimson radial glow — top-left accent */
.np-hero::after {
    content: '';
    position: absolute;
    top: -120px; left: -80px;
    width: 600px; height: 600px;
    border-radius: 50%;
    background: radial-gradient(circle, rgba(124,111,171,0.26) 0%, transparent 65%);
    z-index: 1;
    pointer-events: none;
}

.np-hero-bg {
    position: absolute;
    inset: 0;
    background-image: url('<?php echo $baseUrl; ?>/assets/images/news/news-hero.jpg');
    background-size: cover;
    background-position: center;
    opacity: 0.08;
    z-index: 0;
}

/* ── Inner wrapper: single centred column ── */
.np-hero-inner {
    position: relative;
    z-index: 2;
    /* Remove two-column; single centred column works at every width */
    display: flex;
    flex-direction: column;
    align-items: flex-start;
    max-width: 780px;         /* content never stretches too wide */
}

/* Left / Right wrappers kept in HTML but collapsed to single column */
.np-hero-left  { width: 100%; }
.np-hero-right { width: 100%; margin-top: 2rem; }

/* ── Gold eyebrow rule ── */
.np-hero-eyebrow {
    display: flex;
    align-items: center;
    gap: 12px;
    margin-bottom: 1.25rem;
}

.np-hero-eyebrow-rule {
    width: 40px;
    height: 2px;
    background: var(--gold-light);
    flex-shrink: 0;
}

.np-hero-eyebrow-text {
    font-family: var(--font-mono);
    font-size: 0.7rem;
    font-weight: 500;
    letter-spacing: 0.2em;
    text-transform: uppercase;
    color: var(--gold-light);
    white-space: nowrap;
}

/* ── Heading ── */
.np-hero-title {
    font-family: var(--font-display);
    font-size: clamp(2.4rem, 5vw, 4rem);
    font-weight: 700;
    line-height: 1.05;
    color: var(--white);
    margin-bottom: 1rem;
    letter-spacing: -0.01em;
}

.np-hero-title em {
    font-style: italic;
    color: var(--gold-light);
}

/* ── Subtitle ── */
.np-hero-subtitle {
    font-size: clamp(0.95rem, 1.8vw, 1.1rem);
    color: rgba(255,255,255,0.68);
    font-weight: 300;
    max-width: 560px;
    line-height: 1.7;
    margin-bottom: 2rem;
}

/* ── Search Bar ── */
.np-search-form {
    display: flex;
    gap: 0;
    max-width: 580px;
    border-radius: var(--radius-md);
    overflow: hidden;
    box-shadow: 0 0 0 2px rgba(184,134,11,0.3), var(--shadow-lg);
}

.np-search-wrap {
    position: relative;
    flex: 1;
}

.np-search-icon {
    position: absolute;
    left: 1.25rem;
    top: 50%;
    transform: translateY(-50%);
    color: var(--slate);
    pointer-events: none;
    z-index: 2;
    font-size: 0.85rem;
}

.np-search-input {
    width: 100%;
    height: 54px;
    padding: 0 1rem 0 3rem;
    border: none;
    border-right: 1px solid var(--border);
    background: var(--white);
    color: var(--ink);
    font-family: var(--font-body);
    font-size: 0.95rem;
    font-weight: 400;
    caret-color: var(--purple);
    outline: none;
    transition: background 0.2s;
}

.np-search-input::placeholder { color: var(--mist); }
.np-search-input:focus { background: #FFFCF8; }

.np-search-btn {
    height: 54px;
    padding: 0 1.75rem;
    background: var(--purple);
    color: var(--white);
    border: none;
    font-family: var(--font-body);
    font-size: 0.9rem;
    font-weight: 600;
    cursor: pointer;
    display: flex;
    align-items: center;
    gap: 8px;
    white-space: nowrap;
    transition: background 0.2s;
    letter-spacing: 0.02em;
}

.np-search-btn:hover { background: var(--purple-dark); }

/* ── Hero Stats Row ── */
.np-hero-stats {
    display: flex;
    flex-direction: row;
    gap: clamp(1.5rem, 4vw, 3rem);
    flex-wrap: wrap;
    margin-top: 0;
}

.np-stat {
    display: flex;
    flex-direction: column;
    gap: 3px;
    padding-left: 1rem;
    border-left: 2px solid rgba(212,165,32,0.35);
}

.np-stat-value {
    font-family: var(--font-display);
    font-size: 1.9rem;
    font-weight: 700;
    color: var(--white);
    line-height: 1;
}

.np-stat-label {
    font-size: 0.66rem;
    font-weight: 500;
    letter-spacing: 0.14em;
    text-transform: uppercase;
    color: rgba(255,255,255,0.4);
}

/* ── Breadcrumb ── */
.np-breadcrumb {
    background: var(--white);
    border-bottom: 1px solid var(--border);
    padding: 0.8rem 0;
}

.np-breadcrumb-list {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    list-style: none;
    font-size: 0.82rem;
    flex-wrap: wrap;
}

.np-breadcrumb-list a {
    color: var(--purple-dark);
    text-decoration: none;
    font-weight: 500;
}
.np-breadcrumb-list a:hover {
    color: var(--purple);
    text-decoration: underline;
}
.np-breadcrumb-sep { color: var(--mist); }
.np-breadcrumb-current { color: var(--slate); }

/* ═══════════════════════════════════════════════════
   FEATURED ARTICLE
═══════════════════════════════════════════════════ */
.np-featured-wrap {
    padding: clamp(2rem, 5vw, 3.5rem) 0 0;
}

/* ── Featured card: horizontal split ──
   Image occupies the left half on desktop.
   Text panel sits on the right with dark bg.
   On mobile: image on top, text below — no overlap.
─────────────────────────────────────────────────── */
.np-featured {
    border-radius: var(--radius-xl);
    overflow: hidden;
    background: var(--ink-mid);
    box-shadow: var(--shadow-xl);
    display: grid;
    grid-template-columns: 1fr;
    grid-template-rows: 240px auto;
}

@media (min-width: 680px) {
    .np-featured {
        grid-template-columns: 1fr 1fr;
        grid-template-rows: none;
        min-height: 380px;
    }
}

@media (min-width: 1024px) {
    .np-featured {
        grid-template-columns: 55% 45%;
        min-height: 420px;
    }
}

/* Image cell */
.np-featured-img-cell {
    position: relative;
    overflow: hidden;
    background: var(--ink);
}

.np-featured-img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    display: block;
    transition: transform 6s ease;
}

.np-featured:hover .np-featured-img {
    transform: scale(1.04);
}

/* Subtle right fade so image blends into text panel */
.np-featured-img-cell::after {
    content: '';
    position: absolute;
    inset: 0;
    background: linear-gradient(
        to right,
        transparent 50%,
        rgba(14,17,23,0.6) 100%
    );
    pointer-events: none;
}

@media (max-width: 679px) {
    /* On mobile: bottom fade so text panel is readable */
    .np-featured-img-cell::after {
        background: linear-gradient(
            to bottom,
            transparent 40%,
            rgba(14,17,23,0.85) 100%
        );
    }
}

/* Text cell */
.np-featured-content {
    background: linear-gradient(160deg, #1C2333 40%, #201E38 100%);
    padding: clamp(1.5rem, 4vw, 2.5rem);
    display: flex;
    flex-direction: column;
    justify-content: center;
    gap: 0;
    position: relative;
}

/* Left crimson border accent on text panel */
.np-featured-content::before {
    content: '';
    position: absolute;
    left: 0; top: 15%; bottom: 15%;
    width: 3px;
    background: linear-gradient(to bottom, var(--purple-mid), var(--purple));
    border-radius: 3px;
}

@media (max-width: 679px) {
    .np-featured-content::before { display: none; }
}

/* Remove the old absolute overlay — no longer needed */
.np-featured-overlay { display: none; }

.np-featured-tag {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    background: var(--purple);
    color: var(--white);
    font-family: var(--font-mono);
    font-size: 0.65rem;
    font-weight: 500;
    letter-spacing: 0.15em;
    text-transform: uppercase;
    padding: 4px 12px;
    border-radius: 4px;
    margin-bottom: 0.875rem;
    width: fit-content;
}

.np-featured-title {
    font-family: var(--font-display);
    /* Shorter on mobile since card is compact */
    font-size: clamp(1.2rem, 2.8vw, 1.9rem);
    font-weight: 700;
    line-height: 1.2;
    color: var(--white);
    margin-bottom: 0.75rem;
    letter-spacing: -0.01em;
}

/* Excerpt hidden on mobile — full article is one card down */
.np-featured-excerpt {
    font-size: 0.875rem;
    color: rgba(255,255,255,0.65);
    line-height: 1.6;
    margin-bottom: 1.125rem;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

@media (max-width: 679px) {
    .np-featured-excerpt { display: none; }
}

.np-featured-meta {
    display: flex;
    align-items: center;
    gap: 0.875rem;
    flex-wrap: wrap;
    margin-bottom: 1.25rem;
}

.np-featured-meta-item {
    display: flex;
    align-items: center;
    gap: 4px;
    font-family: var(--font-mono);
    font-size: 0.68rem;
    color: rgba(255,255,255,0.45);
    letter-spacing: 0.04em;
}

.np-featured-meta-item i { color: var(--gold-light); font-size: 0.62rem; }

.np-featured-actions {
    display: flex;
    gap: 0.75rem;
    flex-wrap: wrap;
    align-items: center;
}

/* ═══════════════════════════════════════════════════
   BUTTONS
═══════════════════════════════════════════════════ */
.np-btn {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 0.6rem 1.5rem;
    border-radius: var(--radius-sm);
    font-family: var(--font-body);
    font-size: 0.875rem;
    font-weight: 600;
    text-decoration: none;
    border: none;
    cursor: pointer;
    transition: all 0.22s ease;
    letter-spacing: 0.01em;
    white-space: nowrap;
}

.np-btn--purple {
    background: var(--purple);
    color: var(--white);
}
.np-btn--purple:hover {
    background: var(--purple-dark);
    color: var(--white);
    transform: translateY(-1px);
    box-shadow: 0 4px 16px rgba(124,111,171,0.32);
}

.np-btn--ghost {
    background: transparent;
    color: var(--white);
    border: 1.5px solid rgba(255,255,255,0.4);
}
.np-btn--ghost:hover {
    border-color: var(--white);
    background: rgba(255,255,255,0.08);
    color: var(--white);
}

.np-btn--outline {
    background: transparent;
    color: var(--purple);
    border: 1.5px solid var(--purple);
}
.np-btn--outline:hover {
    background: var(--purple);
    color: var(--white);
    transform: translateY(-1px);
}

.np-btn--surface {
    background: var(--surface);
    color: var(--ink-soft);
    border: 1px solid var(--border);
}
.np-btn--surface:hover {
    background: var(--border);
    color: var(--ink);
}

.np-btn--gold {
    background: var(--gold);
    color: var(--white);
}
.np-btn--gold:hover {
    background: var(--gold-light);
    color: var(--white);
    transform: translateY(-1px);
    box-shadow: 0 4px 16px rgba(184,134,11,0.3);
}

/* ═══════════════════════════════════════════════════
   SECTION HEADER
═══════════════════════════════════════════════════ */
.np-section-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 1rem;
    margin-bottom: 1.75rem;
    padding-bottom: 1rem;
    /* Two-tone border: thin purple stripe over grey */
    border-bottom: 2px solid var(--border);
    border-image: linear-gradient(90deg, var(--purple) 120px, var(--border) 120px) 1;
    flex-wrap: wrap;
}

.np-section-title {
    display: flex;
    align-items: center;
    gap: 10px;
    font-family: var(--font-display);
    font-size: clamp(1.4rem, 3vw, 1.9rem);
    font-weight: 700;
    color: var(--ink);
    letter-spacing: -0.01em;
}

.np-section-pip {
    width: 8px;
    height: 8px;
    border-radius: 50%;
    background: var(--purple);
    flex-shrink: 0;
}

/* ═══════════════════════════════════════════════════
   FILTER BAR
═══════════════════════════════════════════════════ */
.np-filter-bar {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 1rem;
    flex-wrap: wrap;
    padding: 0.875rem 1.25rem;
    background: var(--surface);
    border: 1px solid var(--border);
    border-left: 3px solid var(--purple);
    border-radius: var(--radius-md);
    margin-bottom: 2rem;
}

.np-filter-group {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    flex-wrap: wrap;
}

.np-filter-label {
    font-size: 0.8rem;
    font-weight: 600;
    color: var(--slate);
    letter-spacing: 0.05em;
    text-transform: uppercase;
}

.np-filter-select {
    height: 38px;
    padding: 0 2rem 0 0.875rem;
    border: 1.5px solid var(--border);
    border-radius: var(--radius-sm);
    background: var(--white);
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 24 24' fill='none' stroke='%2364748B' stroke-width='2.5'%3E%3Cpolyline points='6 9 12 15 18 9'/%3E%3C/svg%3E");
    background-repeat: no-repeat;
    background-position: right 0.625rem center;
    background-size: 12px;
    color: var(--ink-soft);
    font-family: var(--font-body);
    font-size: 0.875rem;
    font-weight: 400;
    cursor: pointer;
    appearance: none;
    outline: none;
    transition: border-color 0.2s, box-shadow 0.2s;
    caret-color: var(--purple);
}

.np-filter-select:hover  { border-color: var(--slate); }
.np-filter-select:focus  {
    border-color: var(--purple);
    box-shadow: 0 0 0 3px rgba(124,111,171,0.12);
}

.np-results-count {
    font-family: var(--font-mono);
    font-size: 0.78rem;
    color: var(--slate);
    white-space: nowrap;
}

.np-results-count strong { color: var(--ink); }

/* ═══════════════════════════════════════════════════
   NEWS CARDS (WIDE HORIZONTAL)
═══════════════════════════════════════════════════ */
.np-grid {
    display: flex;
    flex-direction: column;
    gap: 1.25rem;
    margin-bottom: 2.5rem;
}

.np-card {
    display: flex;
    flex-direction: column;
    background: var(--white);
    border: 1px solid var(--border);
    border-radius: var(--radius-lg);
    overflow: hidden;
    transition: transform 0.28s ease, box-shadow 0.28s ease, border-color 0.28s ease;
    position: relative;
}

@media (min-width: 680px) {
    .np-card { flex-direction: row; min-height: 220px; }
}

.np-card:hover {
    transform: translateY(-3px);
    box-shadow: var(--shadow-lg);
    border-color: rgba(124,111,171,0.22);
}

/* Left crimson accent on hover */
.np-card::before {
    content: '';
    position: absolute;
    left: 0; top: 0; bottom: 0;
    width: 3px;
    background: linear-gradient(to bottom, var(--purple), var(--purple-mid));
    transform: scaleY(0);
    transform-origin: center;
    transition: transform 0.28s ease;
    border-radius: 3px 0 0 3px;
    z-index: 1;
}

.np-card:hover::before { transform: scaleY(1); }

/* Image side */
.np-card-img-wrap {
    position: relative;
    width: 100%;
    height: 200px;
    flex-shrink: 0;
    overflow: hidden;
    background: var(--surface);
}

@media (min-width: 680px) {
    .np-card-img-wrap {
        width: 260px;
        height: auto;
    }
}

@media (min-width: 1024px) {
    .np-card-img-wrap { width: 300px; }
}

.np-card-img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.5s ease;
    display: block;
}

.np-card:hover .np-card-img { transform: scale(1.06); }

.np-card-img-placeholder {
    width: 100%; height: 100%;
    display: flex; align-items: center; justify-content: center;
    background: linear-gradient(135deg, var(--surface), var(--border));
    color: var(--mist);
    font-size: 2.5rem;
}

.np-card-cat {
    position: absolute;
    top: 0.875rem;
    left: 0.875rem;
    background: var(--purple);
    color: var(--white);
    font-family: var(--font-mono);
    font-size: 0.62rem;
    font-weight: 500;
    letter-spacing: 0.12em;
    text-transform: uppercase;
    padding: 4px 10px;
    border-radius: 4px;
    z-index: 2;
}

/* Body side */
.np-card-body {
    padding: 1.5rem 1.75rem;
    flex: 1;
    display: flex;
    flex-direction: column;
    gap: 0;
}

.np-card-title {
    font-family: var(--font-display);
    font-size: clamp(1.1rem, 2vw, 1.4rem);
    font-weight: 700;
    line-height: 1.25;
    color: var(--ink);
    margin-bottom: 0.625rem;
    letter-spacing: -0.01em;
}

.np-card-title a {
    color: inherit;
    text-decoration: none;
    transition: color 0.2s;
}

.np-card-title a:hover { color: var(--purple); }

.np-card-excerpt {
    font-size: 0.875rem;
    color: var(--slate);
    line-height: 1.65;
    flex: 1;
    margin-bottom: 1.25rem;
    display: -webkit-box;
    -webkit-line-clamp: 3;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.np-card-footer {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 1rem;
    padding-top: 1rem;
    border-top: 1px solid var(--border);
    flex-wrap: wrap;
}

.np-card-meta {
    display: flex;
    align-items: center;
    gap: 1rem;
    flex-wrap: wrap;
}

.np-card-meta-item {
    display: flex;
    align-items: center;
    gap: 5px;
    font-family: var(--font-mono);
    font-size: 0.72rem;
    color: var(--mist);
}

.np-card-meta-item i { color: var(--slate); font-size: 0.68rem; }

.np-read-more {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    color: var(--purple);
    font-size: 0.82rem;
    font-weight: 600;
    text-decoration: none;
    letter-spacing: 0.02em;
    transition: gap 0.2s, color 0.2s;
}

.np-read-more:hover {
    gap: 10px;
    color: var(--purple-dark);
}

.np-read-more i { font-size: 0.7rem; transition: transform 0.2s; }
.np-read-more:hover i { transform: translateX(2px); }

/* ═══════════════════════════════════════════════════
   PAGE LAYOUT (MAIN + SIDEBAR)
═══════════════════════════════════════════════════ */
.np-layout {
    display: grid;
    grid-template-columns: 1fr;
    gap: 2.5rem;
    padding: clamp(2rem, 5vw, 3.5rem) 0 clamp(3rem, 6vw, 5rem);
}

@media (min-width: 1080px) {
    .np-layout {
        grid-template-columns: 1fr 308px;
    }
}

/* ═══════════════════════════════════════════════════
   SIDEBAR
═══════════════════════════════════════════════════ */
.np-sidebar {
    display: flex;
    flex-direction: column;
    gap: 1.5rem;
}

@media (min-width: 1080px) {
    .np-sidebar {
        position: sticky;
        top: 1.5rem;
        align-self: start;
    }
}

.np-widget {
    background: var(--white);
    border: 1px solid var(--border);
    border-radius: var(--radius-lg);
    padding: 1.5rem;
}

.np-widget-title {
    display: flex;
    align-items: center;
    gap: 10px;
    font-family: var(--font-display);
    font-size: 1.2rem;
    font-weight: 700;
    color: var(--ink);
    margin-bottom: 1.25rem;
    padding-bottom: 0.875rem;
    border-bottom: 1px solid var(--border);
}

.np-widget-icon {
    width: 32px; height: 32px;
    background: var(--purple-pale);
    color: var(--purple);
    border-radius: var(--radius-sm);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 0.8rem;
}

/* Categories */
.np-cat-list { list-style: none; display: flex; flex-direction: column; gap: 4px; }

.np-cat-link {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 0.55rem 0.875rem;
    border-radius: var(--radius-sm);
    color: var(--ink-soft);
    text-decoration: none;
    font-size: 0.875rem;
    font-weight: 500;
    transition: all 0.2s;
    background: transparent;
}

.np-cat-link:hover {
    background: var(--purple-pale);
    color: var(--purple-dark);
    padding-left: 1.125rem;
}

.np-cat-count {
    font-family: var(--font-mono);
    font-size: 0.68rem;
    color: var(--mist);
    background: var(--surface);
    padding: 2px 7px;
    border-radius: 20px;
    border: 1px solid var(--border);
    min-width: 28px;
    text-align: center;
}

.np-cat-link:hover .np-cat-count {
    background: var(--white);
    color: var(--purple);
    border-color: rgba(124,111,171,0.22);
}

/* Popular */
.np-popular-list { list-style: none; display: flex; flex-direction: column; }

.np-popular-item {
    display: flex;
    gap: 0.875rem;
    padding: 0.875rem 0;
    border-bottom: 1px solid var(--border);
}
.np-popular-item:last-child { border-bottom: none; padding-bottom: 0; }

.np-popular-thumb {
    width: 72px;
    height: 56px;
    flex-shrink: 0;
    border-radius: var(--radius-sm);
    overflow: hidden;
    background: var(--surface);
}

.np-popular-thumb img {
    width: 100%; height: 100%;
    object-fit: cover;
    display: block;
}

.np-popular-body { flex: 1; min-width: 0; }

.np-popular-title {
    font-size: 0.85rem;
    font-weight: 600;
    line-height: 1.4;
    color: var(--ink-soft);
    margin-bottom: 0.3rem;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.np-popular-title a {
    color: inherit;
    text-decoration: none;
    transition: color 0.2s;
}

.np-popular-title a:hover { color: var(--purple); }

.np-popular-date {
    font-family: var(--font-mono);
    font-size: 0.68rem;
    color: var(--mist);
    display: flex;
    align-items: center;
    gap: 4px;
}

/* Newsletter widget */
.np-widget--newsletter {
    background: linear-gradient(155deg, #1C2333 40%, #221F3A 100%);
    border-color: rgba(124,111,171,0.18);
    position: relative;
    overflow: hidden;
}

.np-widget--newsletter::before {
    content: '';
    position: absolute;
    bottom: -60px; right: -60px;
    width: 200px; height: 200px;
    border-radius: 50%;
    background: radial-gradient(circle, rgba(124,111,171,0.18) 0%, transparent 70%);
    pointer-events: none;
}

.np-widget--newsletter .np-widget-title { color: var(--white); border-color: rgba(255,255,255,0.1); }
.np-widget--newsletter .np-widget-icon { background: rgba(124,111,171,0.22); color: var(--gold-light); }

.np-nl-desc {
    font-size: 0.875rem;
    color: rgba(255,255,255,0.65);
    line-height: 1.65;
    margin-bottom: 1.25rem;
}

.np-nl-input-wrap { position: relative; margin-bottom: 0.75rem; }

.np-nl-icon {
    position: absolute;
    left: 1rem;
    top: 50%;
    transform: translateY(-50%);
    color: var(--mist);
    font-size: 0.8rem;
    pointer-events: none;
    z-index: 2;
}

.np-nl-input {
    width: 100%;
    height: 46px;
    padding: 0 1rem 0 2.75rem;
    border: 1.5px solid rgba(255,255,255,0.12);
    border-radius: var(--radius-sm);
    background: rgba(255,255,255,0.06);
    color: var(--white);
    font-family: var(--font-body);
    font-size: 0.875rem;
    font-weight: 400;
    caret-color: var(--gold-light);
    outline: none;
    transition: border-color 0.2s, background 0.2s;
}

.np-nl-input::placeholder { color: rgba(255,255,255,0.3); }

.np-nl-input:focus {
    border-color: var(--gold-light);
    background: rgba(255,255,255,0.1);
    box-shadow: 0 0 0 3px rgba(124,111,171,0.16);
}

.np-nl-btn {
    width: 100%;
    height: 44px;
    background: var(--purple);
    color: var(--white);
    border: none;
    border-radius: var(--radius-sm);
    font-family: var(--font-body);
    font-size: 0.875rem;
    font-weight: 600;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    transition: background 0.2s, transform 0.2s;
    letter-spacing: 0.02em;
    position: relative;
    z-index: 1;
}

.np-nl-btn:hover:not(:disabled) {
    background: var(--purple-dark);
    transform: translateY(-1px);
}

.np-nl-btn:disabled { opacity: 0.65; cursor: not-allowed; }

.np-nl-disclaimer {
    font-size: 0.68rem;
    color: rgba(255,255,255,0.35);
    margin-top: 0.75rem;
    line-height: 1.5;
    position: relative;
    z-index: 1;
}

#np-nl-message {
    display: none;
    margin-bottom: 0.875rem;
    padding: 0.75rem 1rem;
    border-radius: var(--radius-sm);
    font-size: 0.82rem;
    font-weight: 500;
}

/* ═══════════════════════════════════════════════════
   PAGINATION
═══════════════════════════════════════════════════ */
.np-pagination {
    display: flex;
    justify-content: center;
    margin-top: 2rem;
}

.np-pagination-list {
    display: flex;
    gap: 4px;
    list-style: none;
    flex-wrap: wrap;
    justify-content: center;
}

.np-pagination-link {
    min-width: 40px;
    height: 40px;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 0 0.75rem;
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

.np-pagination-link:hover:not(.active) {
    background: var(--purple);
    color: var(--white);
    border-color: var(--purple);
}

.np-pagination-link.active {
    background: var(--purple);
    color: var(--white);
    border-color: var(--purple);
}

/* ═══════════════════════════════════════════════════
   EMPTY STATE
═══════════════════════════════════════════════════ */
.np-empty {
    text-align: center;
    padding: 4rem 2rem;
    background: var(--surface);
    border-radius: var(--radius-xl);
    border: 1px dashed var(--border);
}

.np-empty-icon {
    width: 72px; height: 72px;
    margin: 0 auto 1.25rem;
    background: var(--white);
    border: 1px solid var(--border);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.75rem;
    color: var(--mist);
}

.np-empty-title {
    font-family: var(--font-display);
    font-size: 1.5rem;
    font-weight: 700;
    color: var(--ink-soft);
    margin-bottom: 0.5rem;
}

.np-empty-desc {
    font-size: 0.9rem;
    color: var(--slate);
    margin-bottom: 1.5rem;
    max-width: 360px;
    margin-left: auto;
    margin-right: auto;
    line-height: 1.65;
}

/* ═══════════════════════════════════════════════════
   TOAST NOTIFICATION
═══════════════════════════════════════════════════ */
.np-toast {
    position: fixed;
    bottom: 1.5rem;
    right: 1.5rem;
    background: var(--white);
    padding: 1rem 1.25rem;
    border-radius: var(--radius-md);
    box-shadow: var(--shadow-xl);
    border-left: 4px solid var(--purple);
    z-index: 9999;
    display: flex;
    align-items: center;
    gap: 0.75rem;
    max-width: 340px;
    font-size: 0.875rem;
    font-weight: 500;
    color: var(--ink-soft);
    animation: np-slideUp 0.3s ease;
    pointer-events: none;
}

.np-toast.success { border-left-color: #059669; }
.np-toast.error   { border-left-color: #DC2626; }

@keyframes np-slideUp {
    from { transform: translateY(20px); opacity: 0; }
    to   { transform: translateY(0);    opacity: 1; }
}

/* ═══════════════════════════════════════════════════
   ANIMATIONS
═══════════════════════════════════════════════════ */
@keyframes np-fadeIn {
    from { opacity: 0; transform: translateY(14px); }
    to   { opacity: 1; transform: translateY(0); }
}

.np-card { animation: np-fadeIn 0.4s ease both; }
.np-card:nth-child(1) { animation-delay: 0.05s; }
.np-card:nth-child(2) { animation-delay: 0.1s;  }
.np-card:nth-child(3) { animation-delay: 0.15s; }
.np-card:nth-child(4) { animation-delay: 0.2s;  }
.np-card:nth-child(5) { animation-delay: 0.25s; }
.np-card:nth-child(n+6) { animation-delay: 0.3s; }

/* ═══════════════════════════════════════════════════
   RESPONSIVE
═══════════════════════════════════════════════════ */
@media (max-width: 640px) {
    .np-search-form {
        flex-direction: column;
        border-radius: var(--radius-md);
        box-shadow: none;
    }
    .np-search-wrap,
    .np-search-input { border-radius: var(--radius-sm) var(--radius-sm) 0 0; border-right: none; border-bottom: 1px solid var(--border); }
    .np-search-btn   { border-radius: 0 0 var(--radius-sm) var(--radius-sm); height: 50px; }
    .np-hero-stats { gap: 1.5rem; }
    .np-featured-content { padding: 1.5rem; }
    .np-featured-title { font-size: 1.3rem; }
}

@media (max-width: 480px) {
    .np-filter-bar { padding: 0.75rem 1rem; }
    .np-card-body  { padding: 1.25rem; }
}

@media (prefers-reduced-motion: reduce) {
    .np-card, .np-featured-img { animation: none !important; transition: none !important; }
}
</style>

<!-- ═══════════════════════════════════════════════
     PAGE ROOT
═══════════════════════════════════════════════════ -->
<div class="np-root">

<!-- ── HERO ── -->
<section class="np-hero hero-section" aria-label="News hero">
    <div class="np-hero-bg"></div>
    <div class="np-container np-hero-inner">

        <!-- LEFT: eyebrow, title, subtitle, search -->
        <div class="np-hero-left">
            <div class="np-hero-eyebrow">
                <span class="np-hero-eyebrow-rule"></span>
                <span class="np-hero-eyebrow-text">FCT College of Nursing Sciences</span>
            </div>

            <h1 class="np-hero-title">
                News &amp; <em>Announcements</em>
            </h1>

            <p class="np-hero-subtitle">
                Stay informed with the latest developments, achievements, and important updates from across the College.
            </p>

            <form class="np-search-form" action="<?php echo $baseUrl; ?>/news/search" method="GET" role="search">
                <div class="np-search-wrap">
                    <i class="fas fa-search np-search-icon" aria-hidden="true"></i>
                    <input type="search"
                           name="q"
                           class="np-search-input"
                           placeholder="Search articles, announcements…"
                           aria-label="Search news"
                           value="<?php echo htmlspecialchars($_GET['q'] ?? ''); ?>">
                </div>
                <button type="submit" class="np-search-btn">
                    <i class="fas fa-search" aria-hidden="true"></i>
                    Search
                </button>
            </form>
        </div>

        <!-- RIGHT: stats panel -->
        <div class="np-hero-right">
            <div class="np-hero-stats">
                <div class="np-stat">
                    <span class="np-stat-value"><?php echo number_format($pagination['totalCount'] ?? count($news)); ?></span>
                    <span class="np-stat-label">Articles</span>
                </div>
                <div class="np-stat">
                    <span class="np-stat-value"><?php echo count($categories) ?: '8'; ?></span>
                    <span class="np-stat-label">Categories</span>
                </div>
                <div class="np-stat">
                    <span class="np-stat-value">2026</span>
                    <span class="np-stat-label">Latest Year</span>
                </div>
            </div>
        </div>

    </div>
</section>

<!-- ── BREADCRUMB ── -->
<nav class="np-breadcrumb" aria-label="Breadcrumb">
    <div class="np-container">
        <ul class="np-breadcrumb-list">
            <li><a href="<?php echo $baseUrl; ?>"><i class="fas fa-home" aria-hidden="true"></i> Home</a></li>
            <li><span class="np-breadcrumb-sep">/</span></li>
            <li><span class="np-breadcrumb-current" aria-current="page">News</span></li>
        </ul>
    </div>
</nav>

<!-- ── MAIN CONTENT ── -->
<div class="np-container">

    <?php if (!$hasNews): ?>
    <!-- Empty State -->
    <div style="padding: 4rem 0;">
        <div class="np-empty">
            <div class="np-empty-icon"><i class="fas fa-newspaper" aria-hidden="true"></i></div>
            <h2 class="np-empty-title">No Articles Yet</h2>
            <p class="np-empty-desc">We're preparing our latest news and updates. Please check back soon.</p>
            <a href="<?php echo $baseUrl; ?>" class="np-btn np-btn--purple">
                <i class="fas fa-home" aria-hidden="true"></i> Return Home
            </a>
        </div>
    </div>
    <?php else: ?>

    <!-- ── FEATURED ARTICLE ── -->
    <div class="np-featured-wrap">
        <article class="np-featured">
            <?php
            /* Resolve featured hero image:
             * 1. First item in $featuredNews that has an image
             * 2. First item in $news that has an image
             * 3. Fall back to local asset path (gradient bg shows if file missing)
             */
            $_fImg = '';
            if (!empty($featuredNews) && !empty($featuredNews[0]['featured_image'])) {
                $_fImg = getImageUrl($featuredNews[0]['featured_image']);
            }
            if (empty($_fImg)) {
                foreach ($news as $_ni) {
                    if (!empty($_ni['featured_image'])) {
                        $_fImg = getImageUrl($_ni['featured_image']);
                        break;
                    }
                }
            }
            $_fLocal = $baseUrl . '/assets/images/news/featured-nursing.jpg';
            $_fImg   = $_fImg ?: $_fLocal;
            ?>
            <!-- Image cell (left half) -->
            <div class="np-featured-img-cell">
                <img src="<?php echo htmlspecialchars($_fImg); ?>"
                     alt="Nursing students in practical training"
                     class="np-featured-img"
                     onerror="this.onerror=null; this.style.opacity='0.15';">
            </div>

            <!-- Hidden overlay kept for compat but display:none via CSS -->
            <div class="np-featured-overlay" aria-hidden="true"></div>

            <!-- Text cell (right half) -->
            <div class="np-featured-content">
                <span class="np-featured-tag">
                    <i class="fas fa-star" aria-hidden="true"></i>
                    Featured — Academic
                </span>

                <h2 class="np-featured-title">
                    Strengthening Nursing Education Through Practical Training and Innovation
                </h2>

                <p class="np-featured-excerpt">
                    The College continues to enhance nursing education by integrating hands-on clinical training, modern learning tools, and evidence-based practices to prepare students for real-world healthcare challenges.
                </p>

                <div class="np-featured-meta">
                    <span class="np-featured-meta-item">
                        <i class="far fa-calendar-alt" aria-hidden="true"></i>
                        February 10, 2026
                    </span>
                    <span class="np-featured-meta-item">
                        <i class="far fa-eye" aria-hidden="true"></i>
                        19 views
                    </span>
                    <span class="np-featured-meta-item">
                        <i class="far fa-clock" aria-hidden="true"></i>
                        5 min read
                    </span>
                </div>

                <div class="np-featured-actions">
                    <a href="<?php echo $baseUrl; ?>/news/strengthening-nursing-education"
                       class="np-btn np-btn--purple">
                        Read Full Article
                        <i class="fas fa-arrow-right" aria-hidden="true"></i>
                    </a>
                    <a href="<?php echo $baseUrl; ?>/news/category/academic"
                       class="np-btn np-btn--ghost">
                        <i class="fas fa-folder" aria-hidden="true"></i>
                        Academic News
                    </a>
                </div>
            </div>
        </article>
    </div>

    <!-- ── TWO-COLUMN LAYOUT ── -->
    <div class="np-layout">

        <!-- Main Column -->
        <main id="main-content">

            <div class="np-section-header">
                <h2 class="np-section-title">
                    <span class="np-section-pip"></span>
                    Latest News
                </h2>
                <a href="<?php echo $baseUrl; ?>/news/archive" class="np-btn np-btn--surface">
                    View Archive
                    <i class="fas fa-arrow-right" aria-hidden="true"></i>
                </a>
            </div>

            <!-- Filter Bar -->
            <div class="np-filter-bar">
                <div class="np-filter-group">
                    <span class="np-filter-label">Filter</span>

                    <select class="np-filter-select" id="np-cat-filter" aria-label="Filter by category">
                        <option value="">All Categories</option>
                        <?php foreach ($categories as $cat => $cnt): ?>
                        <option value="<?php echo urlencode(strtolower(str_replace(' ', '-', $cat))); ?>">
                            <?php echo htmlspecialchars($cat); ?> (<?php echo $cnt; ?>)
                        </option>
                        <?php endforeach; ?>
                    </select>

                    <select class="np-filter-select" id="np-sort-filter" aria-label="Sort by">
                        <option value="latest">Latest First</option>
                        <option value="oldest">Oldest First</option>
                        <option value="popular">Most Popular</option>
                    </select>
                </div>

                <span class="np-results-count">
                    <strong><?php echo count($news); ?></strong> of
                    <strong><?php echo $pagination['totalCount']; ?></strong> articles
                </span>
            </div>

            <!-- Cards -->
            <?php if (empty($news)): ?>
            <div class="np-empty">
                <div class="np-empty-icon"><i class="fas fa-search" aria-hidden="true"></i></div>
                <h3 class="np-empty-title">No Articles Found</h3>
                <p class="np-empty-desc">Try adjusting your filters or search criteria.</p>
                <a href="<?php echo $baseUrl; ?>/news" class="np-btn np-btn--outline">
                    <i class="fas fa-rotate-right" aria-hidden="true"></i> Reset Filters
                </a>
            </div>
            <?php else: ?>

            <div class="np-grid" id="np-news-grid">
                <?php foreach ($news as $item): ?>
                <article class="np-card">

                    <!-- Thumbnail -->
                    <?php
                    /* Resolve the image src:
                     * 1. Use the article's own featured_image if set
                     * 2. Fall back to the featured hero image so the card
                     *    for "Strengthening Nursing Education…" always shows
                     *    a real photo instead of a grey placeholder.
                     */
                    $cardFallback = $baseUrl . '/assets/images/news/featured-nursing.jpg';
                    $cardImgSrc   = !empty($item['featured_image'])
                        ? getImageUrl($item['featured_image'])
                        : $cardFallback;
                    ?>
                    <div class="np-card-img-wrap">
                        <img src="<?php echo $cardImgSrc; ?>"
                             alt="<?php echo htmlspecialchars($item['title']); ?>"
                             class="np-card-img"
                             loading="lazy"
                             onerror="this.src='<?php echo $cardFallback; ?>'">
                        

                        <?php if (!empty($item['category'])): ?>
                        <span class="np-card-cat"><?php echo htmlspecialchars($item['category']); ?></span>
                        <?php endif; ?>
                    </div>

                    <!-- Body -->
                    <div class="np-card-body">
                        <h3 class="np-card-title">
                            <a href="<?php echo $baseUrl; ?>/news/<?php echo $item['slug']; ?>">
                                <?php echo htmlspecialchars($item['title']); ?>
                            </a>
                        </h3>

                        <?php if (!empty($item['excerpt'])): ?>
                        <p class="np-card-excerpt">
                            <?php echo htmlspecialchars(substr(strip_tags($item['excerpt']), 0, 200)); ?>
                        </p>
                        <?php endif; ?>

                        <div class="np-card-footer">
                            <div class="np-card-meta">
                                <span class="np-card-meta-item">
                                    <i class="far fa-calendar" aria-hidden="true"></i>
                                    <?php echo date('M d, Y', strtotime($item['created_at'])); ?>
                                </span>
                                <span class="np-card-meta-item">
                                    <i class="far fa-eye" aria-hidden="true"></i>
                                    <?php echo number_format($item['views_count'] ?? 0); ?>
                                </span>
                            </div>
                            <a href="<?php echo $baseUrl; ?>/news/<?php echo $item['slug']; ?>"
                               class="np-read-more" aria-label="Read more: <?php echo htmlspecialchars($item['title']); ?>">
                                Read more <i class="fas fa-arrow-right" aria-hidden="true"></i>
                            </a>
                        </div>
                    </div>

                </article>
                <?php endforeach; ?>
            </div>

            <!-- Pagination -->
            <?php if ($pagination['total'] > 1): ?>
            <nav class="np-pagination" aria-label="Page navigation">
                <ul class="np-pagination-list">
                    <?php if ($pagination['current'] > 1): ?>
                    <li>
                        <a href="?page=<?php echo $pagination['current'] - 1; ?>"
                           class="np-pagination-link" aria-label="Previous page">
                            <i class="fas fa-chevron-left" aria-hidden="true"></i>
                        </a>
                    </li>
                    <?php endif; ?>

                    <?php for ($i = 1; $i <= $pagination['total']; $i++): ?>
                    <li>
                        <a href="?page=<?php echo $i; ?>"
                           class="np-pagination-link <?php echo $i == $pagination['current'] ? 'active' : ''; ?>"
                           aria-label="Page <?php echo $i; ?>"
                           <?php echo $i == $pagination['current'] ? 'aria-current="page"' : ''; ?>>
                            <?php echo $i; ?>
                        </a>
                    </li>
                    <?php endfor; ?>

                    <?php if ($pagination['current'] < $pagination['total']): ?>
                    <li>
                        <a href="?page=<?php echo $pagination['current'] + 1; ?>"
                           class="np-pagination-link" aria-label="Next page">
                            <i class="fas fa-chevron-right" aria-hidden="true"></i>
                        </a>
                    </li>
                    <?php endif; ?>
                </ul>
            </nav>
            <?php endif; ?>

            <?php endif; /* empty $news */ ?>
        </main>

        <!-- ── SIDEBAR ── -->
        <aside class="np-sidebar" aria-label="News sidebar">

            <!-- Categories -->
            <?php if (!empty($categories)): ?>
            <div class="np-widget">
                <h3 class="np-widget-title">
                    <span class="np-widget-icon"><i class="fas fa-folder" aria-hidden="true"></i></span>
                    Categories
                </h3>
                <ul class="np-cat-list">
                    <?php foreach ($categories as $cat => $cnt): ?>
                    <li>
                        <a href="<?php echo $baseUrl; ?>/news/category/<?php echo urlencode(strtolower(str_replace(' ', '-', $cat))); ?>"
                           class="np-cat-link">
                            <span><?php echo htmlspecialchars($cat); ?></span>
                            <span class="np-cat-count"><?php echo $cnt; ?></span>
                        </a>
                    </li>
                    <?php endforeach; ?>
                </ul>
            </div>
            <?php endif; ?>

            <!-- Popular Articles -->
            <?php if (!empty($popularNews)): ?>
            <div class="np-widget">
                <h3 class="np-widget-title">
                    <span class="np-widget-icon"><i class="fas fa-fire" aria-hidden="true"></i></span>
                    Popular
                </h3>
                <ul class="np-popular-list">
                    <?php foreach ($popularNews as $pop): ?>
                    <li class="np-popular-item">
                        <div class="np-popular-thumb">
                            <?php
                            $popImgSrc = !empty($pop['featured_image'])
                                ? getImageUrl($pop['featured_image'])
                                : $baseUrl . '/assets/images/news/featured-nursing.jpg';
                            ?>
                            <img src="<?php echo $popImgSrc; ?>"
                                 alt=""
                                 loading="lazy"
                                 onerror="this.src='<?php echo $baseUrl; ?>/assets/images/news/featured-nursing.jpg'">
                        </div>
                        <div class="np-popular-body">
                            <h4 class="np-popular-title">
                                <a href="<?php echo $baseUrl; ?>/news/<?php echo $pop['slug']; ?>">
                                    <?php echo htmlspecialchars($pop['title']); ?>
                                </a>
                            </h4>
                            <span class="np-popular-date">
                                <i class="far fa-calendar" aria-hidden="true"></i>
                                <?php echo date('M d, Y', strtotime($pop['created_at'])); ?>
                            </span>
                        </div>
                    </li>
                    <?php endforeach; ?>
                </ul>
            </div>
            <?php endif; ?>

            <!-- Newsletter -->
            <div class="np-widget np-widget--newsletter">
                <h3 class="np-widget-title">
                    <span class="np-widget-icon"><i class="fas fa-envelope" aria-hidden="true"></i></span>
                    Stay Updated
                </h3>
                <p class="np-nl-desc">
                    Subscribe to receive the latest news and announcements directly in your inbox.
                </p>

                <div id="np-nl-message" role="alert"></div>

                <form id="np-nl-form" action="<?php echo $baseUrl; ?>/newsletter/subscribe" method="POST" novalidate>
                    <div class="np-nl-input-wrap">
                        <i class="fas fa-envelope np-nl-icon" aria-hidden="true"></i>
                        <input type="email"
                               id="np-nl-email"
                               name="email"
                               class="np-nl-input"
                               placeholder="your@email.com"
                               required
                               aria-label="Email address for newsletter">
                    </div>
                    <button type="submit" class="np-nl-btn" id="np-nl-submit">
                        <i class="fas fa-paper-plane" aria-hidden="true"></i>
                        Subscribe
                    </button>
                    <p class="np-nl-disclaimer">
                        We respect your privacy. No spam, unsubscribe any time.
                    </p>
                </form>
            </div>

        </aside>

    </div><!-- /np-layout -->

    <?php endif; /* hasNews */ ?>

</div><!-- /np-container -->

</div><!-- /np-root -->

<!-- Toast -->
<div id="np-toast" class="np-toast" role="status" aria-live="polite" style="display:none;"></div>

<script>
(function () {
    'use strict';

    /* ── Newsletter AJAX ── */
    var form   = document.getElementById('np-nl-form');
    var email  = document.getElementById('np-nl-email');
    var btn    = document.getElementById('np-nl-submit');
    var msgDiv = document.getElementById('np-nl-message');

    if (form) {
        form.addEventListener('submit', function (e) {
            e.preventDefault();

            var val = email.value.trim();
            if (!val || !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(val)) {
                showMsg('Please enter a valid email address.', 'error');
                return;
            }

            email.disabled = true;
            btn.disabled   = true;
            btn.innerHTML  = '<i class="fas fa-spinner fa-spin" aria-hidden="true"></i> Subscribing…';

            var fd = new FormData();
            fd.append('email', val);
            fd.append('source', 'news_sidebar');

            fetch('<?php echo $baseUrl; ?>/newsletter/subscribe', { method: 'POST', body: fd })
                .then(function (r) { return r.json(); })
                .then(function (data) {
                    if (data.success) {
                        showMsg(data.message || 'Subscribed! Thank you.', 'success');
                        email.value = '';
                    } else {
                        showMsg(data.message || 'Something went wrong.', 'error');
                    }
                })
                .catch(function () {
                    showMsg('Connection error. Please try again.', 'error');
                })
                .finally(function () {
                    email.disabled = false;
                    btn.disabled   = false;
                    btn.innerHTML  = '<i class="fas fa-paper-plane" aria-hidden="true"></i> Subscribe';
                });
        });
    }

    function showMsg(msg, type) {
        msgDiv.style.display      = 'block';
        msgDiv.textContent        = msg;
        msgDiv.style.background   = type === 'success' ? 'rgba(5,150,105,0.15)'  : 'rgba(220,38,38,0.15)';
        msgDiv.style.color        = type === 'success' ? '#D1FAE5'                : '#FEE2E2';
        msgDiv.style.border       = type === 'success' ? '1px solid rgba(5,150,105,0.3)' : '1px solid rgba(220,38,38,0.3)';
        msgDiv.style.borderRadius = '6px';
        msgDiv.style.padding      = '0.625rem 0.875rem';
        if (type === 'success') {
            setTimeout(function () { msgDiv.style.display = 'none'; }, 5000);
        }
    }

    /* ── Category Filter ── */
    var catFilter  = document.getElementById('np-cat-filter');
    var sortFilter = document.getElementById('np-sort-filter');

    if (catFilter) {
        catFilter.addEventListener('change', function () {
            if (this.value) {
                window.location.href = '<?php echo $baseUrl; ?>/news/category/' + this.value;
            } else {
                window.location.href = '<?php echo $baseUrl; ?>/news';
            }
        });
    }

    if (sortFilter) {
        sortFilter.addEventListener('change', function () {
            var p = new URLSearchParams(window.location.search);
            p.set('sort', this.value);
            p.set('page', '1');
            window.location.search = p.toString();
        });
    }

    /* ── Toast helper (reusable) ── */
    window.npToast = function (msg, type) {
        var t = document.getElementById('np-toast');
        t.textContent  = msg;
        t.className    = 'np-toast ' + (type || '');
        t.style.display = 'flex';
        setTimeout(function () { t.style.display = 'none'; }, 3500);
    };
})();
</script>