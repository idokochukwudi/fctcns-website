<?php
/**
 * About Page View Template
 * Redesigned to match design system (v6.0)
 *
 * Fonts   : Cormorant Garamond (display) + Outfit (body) + JetBrains Mono (mono)
 * Palette : identical CSS custom-properties as all other pages
 * Layout  : fluid --gutter, full-bleed hero at very top, max-width 1400px
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
$page_title       = $page_title ?? 'About | FCT College of Nursing Sciences';
$page_description = $page_description ?? 'Learn about our history, mission, vision, values, leadership, and commitment to excellence in nursing education.';
$heroImagePath    = rtrim($baseUrl, '/') . '/assets/images/about/campus-building.jpg';
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
html, body { margin: 0 !important; padding: 0 !important; width: 100%; overflow-x: hidden; }
body { min-height: 100vh; background: #fff; -webkit-font-smoothing: antialiased; }

/* ==========================================================================
   DESIGN TOKENS — identical across all pages
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
.ab-root {
    font-family: var(--font-body);
    color: var(--ink);
    background: var(--white);
    overflow-x: hidden;
    width: 100%;
}

.ab-container {
    width: 100%;
    max-width: var(--container-max);
    margin-left: auto;
    margin-right: auto;
    padding-left:  var(--gutter);
    padding-right: var(--gutter);
}

/* ==========================================================================
   HERO — full-bleed, starts at very top
   ========================================================================== */
.ab-hero {
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

.ab-hero-bg {
    position: absolute;
    inset: 0;
    background-image: url('<?php echo $heroImagePath; ?>');
    background-size: cover;
    background-position: center;
    opacity: 0.18;
    z-index: 0;
}

.ab-hero::after {
    content: '';
    position: absolute;
    inset: 0;
    background: radial-gradient(circle at 70% 30%, rgba(139,123,184,0.18) 0%, transparent 60%);
    z-index: 1;
    pointer-events: none;
}

.ab-hero-inner {
    width: 100%;
    max-width: var(--container-max);
    margin-left: auto;
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
    .ab-hero-inner { flex-direction: row; align-items: center; justify-content: space-between; }
}

.ab-hero-left  { width: 100%; max-width: 660px; }
.ab-hero-right { width: 100%; }
@media (min-width: 992px) {
    .ab-hero-left  { width: 58%; max-width: none; }
    .ab-hero-right { width: 38%; }
}

.ab-hero-badge {
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

.ab-hero-badge-icon {
    width: 22px; height: 22px;
    background: var(--purple);
    border-radius: 50%;
    display: flex; align-items: center; justify-content: center;
    color: white; font-size: 0.65rem;
}

.ab-hero-badge-text {
    font-family: var(--font-mono);
    font-size: 0.68rem;
    font-weight: 500;
    letter-spacing: 0.1em;
    text-transform: uppercase;
    color: var(--gold);
}

.ab-hero-title {
    font-family: var(--font-display);
    font-size: clamp(2.2rem, 4.5vw, 4rem);
    font-weight: 700;
    line-height: 1.1;
    color: white;
    margin-bottom: 1rem;
    letter-spacing: -0.01em;
    text-shadow: 0 2px 8px rgba(0,0,0,0.2);
}

.ab-hero-title .accent { color: var(--gold-light); font-style: italic; }

.ab-hero-subtitle {
    font-size: clamp(0.95rem, 1.5vw, 1.15rem);
    color: rgba(255,255,255,0.82);
    font-weight: 300;
    max-width: 540px;
    line-height: 1.65;
}

.ab-hero-stats {
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

.ab-stat { display: flex; flex-direction: column; gap: 4px; }

.ab-stat-value {
    font-family: var(--font-display);
    font-size: clamp(1.8rem, 3vw, 2.4rem);
    font-weight: 700;
    color: #FFE082;
    line-height: 1;
    text-shadow: 0 1px 4px rgba(0,0,0,0.3);
}

.ab-stat-label {
    font-size: 0.72rem;
    font-weight: 600;
    letter-spacing: 0.08em;
    text-transform: uppercase;
    color: rgba(255,255,255,0.92);
}

/* ==========================================================================
   BREADCRUMB — below hero
   ========================================================================== */
.ab-breadcrumb {
    background: var(--white);
    border-bottom: 1px solid var(--border);
    padding: 0.75rem 0;
}

.ab-breadcrumb-list {
    display: flex; align-items: center; gap: 0.4rem;
    list-style: none; font-size: 0.8rem; flex-wrap: wrap;
}

.ab-breadcrumb-list a { color: var(--purple-dark); text-decoration: none; font-weight: 500; transition: color 0.18s; }
.ab-breadcrumb-list a:hover { color: var(--purple); text-decoration: underline; }
.ab-breadcrumb-sep { color: var(--mist); }
.ab-breadcrumb-current { color: var(--slate); }

/* ==========================================================================
   SECTION SPACING
   ========================================================================== */
.ab-section {
    padding-top:    clamp(2.5rem, 5vw, 4rem);
    padding-bottom: clamp(2.5rem, 5vw, 4rem);
}

.ab-section--alt      { background: var(--surface); }
.ab-section--bordered { border-top: 1px solid var(--border); border-bottom: 1px solid var(--border); }

/* ==========================================================================
   SECTION HEADER
   ========================================================================== */
.ab-section-header {
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

.ab-section-title {
    display: flex;
    align-items: center;
    gap: 10px;
    font-family: var(--font-display);
    font-size: clamp(1.35rem, 2.5vw, 1.9rem);
    font-weight: 700;
    color: var(--ink);
    letter-spacing: -0.01em;
}

.ab-section-pip {
    width: 8px; height: 8px;
    border-radius: 50%;
    background: var(--purple);
    flex-shrink: 0;
}

.ab-section-subtitle {
    font-size: clamp(0.95rem, 1.5vw, 1.05rem);
    color: var(--slate);
    line-height: 1.65;
    margin-top: 0.5rem;
    font-weight: 400;
    max-width: 680px;
}

/* ==========================================================================
   BUTTONS
   ========================================================================== */
.ab-btn {
    display: inline-flex; align-items: center; gap: 7px;
    padding: 0.65rem 1.5rem;
    border-radius: var(--radius-sm);
    font-family: var(--font-body);
    font-size: 0.9rem; font-weight: 500;
    text-decoration: none; border: none; cursor: pointer;
    transition: all 0.22s ease; letter-spacing: 0.01em; white-space: nowrap;
}

.ab-btn--purple { background: var(--purple); color: white; }
.ab-btn--purple:hover { background: var(--purple-dark); color: white; transform: translateY(-1px); box-shadow: 0 4px 14px rgba(139,123,184,0.32); }

.ab-btn--gold { background: var(--gold); color: white; }
.ab-btn--gold:hover { background: var(--gold-light); color: white; transform: translateY(-1px); }

.ab-btn--outline { background: transparent; color: var(--purple); border: 1.5px solid var(--purple); }
.ab-btn--outline:hover { background: var(--purple); color: white; transform: translateY(-1px); }

.ab-btn--ghost { background: transparent; color: white; border: 1.5px solid rgba(255,255,255,0.35); }
.ab-btn--ghost:hover { border-color: white; background: rgba(255,255,255,0.1); color: white; }

.ab-btn--lg { padding: 0.85rem 2rem; font-size: 1rem; }

/* ==========================================================================
   MISSION / VISION / VALUES
   ========================================================================== */
.ab-mvv-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
    gap: 1.4rem;
    margin-top: 0.5rem;
}

.ab-mvv-card {
    background: var(--white);
    border: 1px solid var(--border);
    border-radius: var(--radius-lg);
    padding: clamp(1.5rem, 3vw, 2rem);
    position: relative;
    overflow: hidden;
    transition: transform 0.28s ease, box-shadow 0.28s ease, border-color 0.28s ease;
    display: flex;
    flex-direction: column;
}

.ab-mvv-card::before {
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

.ab-mvv-card:hover { transform: translateY(-4px); box-shadow: var(--shadow-lg); border-color: rgba(139,123,184,0.25); }
.ab-mvv-card:hover::before { transform: scaleX(1); }

.ab-mvv-label {
    font-family: var(--font-mono);
    font-size: 0.62rem;
    font-weight: 500;
    letter-spacing: 0.15em;
    text-transform: uppercase;
    color: var(--purple);
    margin-bottom: 0.75rem;
}

.ab-mvv-title {
    font-family: var(--font-display);
    font-size: clamp(1.4rem, 2vw, 1.75rem);
    font-weight: 700;
    color: var(--ink);
    margin-bottom: 1rem;
    letter-spacing: -0.01em;
}

.ab-mvv-text {
    font-size: 0.92rem;
    color: var(--slate);
    line-height: 1.75;
    flex: 1;
}

/* Values list */
.ab-values-list { list-style: none; display: flex; flex-direction: column; gap: 0.4rem; margin-top: 0.5rem; }

.ab-value-item {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    padding: 0.6rem 0.85rem;
    background: var(--surface);
    border-radius: var(--radius-md);
    border: 1px solid var(--border);
    font-size: 0.88rem;
    color: var(--ink-soft);
    font-weight: 500;
    transition: all 0.2s ease;
}

.ab-value-item:hover { background: var(--purple-pale); border-color: var(--purple-light); transform: translateX(4px); }

.ab-value-dot {
    width: 7px; height: 7px;
    background: var(--gold);
    border-radius: 50%;
    flex-shrink: 0;
}

/* ==========================================================================
   STATISTICS
   ========================================================================== */
.ab-stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
    gap: 1.25rem;
    margin-top: 0.5rem;
}

.ab-stat-card {
    background: var(--white);
    border: 1px solid var(--border);
    border-radius: var(--radius-lg);
    padding: 1.5rem 1.25rem;
    text-align: center;
    transition: transform 0.28s ease, box-shadow 0.28s ease;
    position: relative;
    overflow: hidden;
}

.ab-stat-card::after {
    content: '';
    position: absolute;
    bottom: 0; left: 0; right: 0;
    height: 3px;
    background: linear-gradient(90deg, var(--purple), var(--gold));
    transform: scaleX(0);
    transition: transform 0.28s ease;
}

.ab-stat-card:hover { transform: translateY(-4px); box-shadow: var(--shadow-md); }
.ab-stat-card:hover::after { transform: scaleX(1); }

.ab-stat-card-value {
    font-family: var(--font-display);
    font-size: clamp(2rem, 3vw, 2.8rem);
    font-weight: 700;
    color: var(--purple);
    line-height: 1;
    margin-bottom: 0.4rem;
}

.ab-stat-card-label {
    font-size: 0.82rem;
    color: var(--slate);
    font-weight: 500;
    line-height: 1.4;
}

/* ==========================================================================
   LEADERSHIP CARDS
   ========================================================================== */
.ab-leadership-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
    gap: 1.4rem;
    margin-top: 0.5rem;
}

.ab-leader-card {
    background: var(--white);
    border: 1px solid var(--border);
    border-radius: var(--radius-lg);
    overflow: hidden;
    transition: transform 0.28s ease, box-shadow 0.28s ease, border-color 0.28s ease;
    display: flex;
    flex-direction: column;
}

.ab-leader-card:hover { transform: translateY(-4px); box-shadow: var(--shadow-lg); border-color: rgba(139,123,184,0.25); }

.ab-leader-img-wrap {
    position: relative;
    width: 100%;
    padding-top: 110%;
    overflow: hidden;
    background: var(--purple-pale);
}

.ab-leader-img {
    position: absolute;
    inset: 0;
    width: 100%; height: 100%;
    object-fit: cover;
    object-position: center 20%;
    transition: transform 0.5s ease;
}

.ab-leader-card:hover .ab-leader-img { transform: scale(1.05); }

.ab-leader-body {
    padding: 1.1rem 1.25rem 1.25rem;
    flex: 1;
    display: flex;
    flex-direction: column;
}

.ab-leader-name {
    font-family: var(--font-display);
    font-size: 1.15rem;
    font-weight: 700;
    color: var(--ink);
    margin-bottom: 0.2rem;
    letter-spacing: -0.01em;
    line-height: 1.25;
}

.ab-leader-role {
    font-family: var(--font-mono);
    font-size: 0.62rem;
    font-weight: 500;
    letter-spacing: 0.12em;
    text-transform: uppercase;
    color: var(--gold);
    margin-bottom: 0.15rem;
}

.ab-leader-dept {
    font-size: 0.8rem;
    color: var(--slate);
    line-height: 1.4;
    margin-bottom: 0.85rem;
    padding-bottom: 0.85rem;
    border-bottom: 1px solid var(--border);
}

.ab-leader-bio {
    font-size: 0.82rem;
    color: var(--slate);
    line-height: 1.65;
    flex: 1;
    margin-bottom: 0.9rem;
}

.ab-leader-social {
    display: flex;
    gap: 0.5rem;
    margin-top: auto;
}

.ab-leader-social a {
    width: 30px; height: 30px;
    border-radius: 50%;
    background: var(--surface);
    color: var(--slate);
    border: 1px solid var(--border);
    display: flex; align-items: center; justify-content: center;
    font-size: 0.7rem;
    text-decoration: none;
    transition: all 0.2s ease;
}

.ab-leader-social a:hover { background: var(--purple); color: white; border-color: var(--purple); transform: translateY(-2px); }

/* ==========================================================================
   LEADERSHIP QUOTE CARD — dark card style matching all pages
   ========================================================================== */
.ab-quote-card {
    background: linear-gradient(160deg, #2A3042 0%, #3A4055 100%);
    border-radius: var(--radius-xl);
    padding: clamp(2rem, 4vw, 3rem) clamp(1.75rem, 4vw, 3rem);
    margin-top: 2rem;
    position: relative;
    overflow: hidden;
    text-align: center;
    box-shadow: var(--shadow-xl);
}

.ab-quote-card::before {
    content: '\201C';
    position: absolute;
    top: -0.5rem; left: 50%;
    transform: translateX(-50%);
    font-family: var(--font-display);
    font-size: clamp(8rem, 15vw, 14rem);
    color: rgba(255,255,255,0.06);
    line-height: 1;
    pointer-events: none;
    user-select: none;
}

.ab-quote-card::after {
    content: '';
    position: absolute;
    left: 0; top: 20%; bottom: 20%;
    width: 3px;
    background: linear-gradient(to bottom, var(--purple-light), var(--purple));
    border-radius: 3px;
}

.ab-quote-text {
    font-family: var(--font-display);
    font-size: clamp(1.1rem, 2vw, 1.45rem);
    font-weight: 400;
    font-style: italic;
    color: rgba(255,255,255,0.9);
    line-height: 1.65;
    max-width: 760px;
    margin: 0 auto 1.5rem;
    position: relative;
    z-index: 1;
}

.ab-quote-author {
    font-family: var(--font-display);
    font-size: 1.2rem;
    font-weight: 700;
    color: var(--gold-light);
    margin-bottom: 0.25rem;
    position: relative;
    z-index: 1;
}

.ab-quote-role {
    font-family: var(--font-mono);
    font-size: 0.68rem;
    font-weight: 500;
    letter-spacing: 0.12em;
    text-transform: uppercase;
    color: rgba(255,255,255,0.55);
    position: relative;
    z-index: 1;
}

/* ==========================================================================
   ACCREDITATION CARDS
   ========================================================================== */
.ab-accred-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 1.4rem;
    margin-top: 0.5rem;
}

.ab-accred-card {
    background: var(--white);
    border: 1px solid var(--border);
    border-radius: var(--radius-lg);
    padding: clamp(1.5rem, 3vw, 2rem);
    position: relative;
    overflow: hidden;
    transition: transform 0.28s ease, box-shadow 0.28s ease;
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.ab-accred-card::before {
    content: '';
    position: absolute;
    top: 0; left: 0; right: 0;
    height: 3px;
    background: linear-gradient(90deg, var(--purple), var(--gold));
    border-radius: 3px 3px 0 0;
}

.ab-accred-card:hover { transform: translateY(-4px); box-shadow: var(--shadow-lg); border-color: rgba(139,123,184,0.25); }

.ab-accred-badge {
    position: absolute;
    top: 1.1rem; right: 1.1rem;
    font-family: var(--font-mono);
    font-size: 0.6rem;
    font-weight: 500;
    letter-spacing: 0.1em;
    text-transform: uppercase;
    padding: 3px 10px;
    background: var(--green-pale);
    color: var(--green);
    border: 1px solid rgba(93,155,140,0.3);
    border-radius: 4px;
}

.ab-accred-icon-wrap {
    width: 60px; height: 60px;
    background: var(--purple-pale);
    color: var(--purple);
    border-radius: var(--radius-md);
    display: flex; align-items: center; justify-content: center;
    font-size: 1.5rem;
    transition: all 0.28s ease;
    border: 1px solid var(--border);
}

.ab-accred-card:hover .ab-accred-icon-wrap { background: var(--purple); color: white; }

.ab-accred-abbr {
    font-family: var(--font-display);
    font-size: clamp(2rem, 3vw, 2.6rem);
    font-weight: 700;
    color: var(--ink);
    letter-spacing: -0.02em;
    line-height: 1;
}

.ab-accred-name {
    font-family: var(--font-mono);
    font-size: 0.68rem;
    font-weight: 500;
    letter-spacing: 0.08em;
    text-transform: uppercase;
    color: var(--slate);
    margin-top: 0.2rem;
}

.ab-accred-desc {
    font-size: 0.88rem;
    color: var(--slate);
    line-height: 1.7;
}

.ab-accred-features { list-style: none; display: flex; flex-direction: column; gap: 0.35rem; }

.ab-accred-features li {
    display: flex;
    align-items: center;
    gap: 0.6rem;
    font-size: 0.85rem;
    color: var(--ink-soft);
    padding: 0.35rem 0;
    border-bottom: 1px solid var(--border);
}

.ab-accred-features li:last-child { border-bottom: none; }

.ab-accred-features li i { color: var(--gold); font-size: 0.75rem; flex-shrink: 0; }

/* ==========================================================================
   GALLERY CAROUSEL
   ========================================================================== */
.ab-gallery-wrap {
    position: relative;
    margin-top: 0.5rem;
    border-radius: var(--radius-xl);
    overflow: hidden;
    box-shadow: var(--shadow-xl);
    height: clamp(300px, 50vw, 600px);
}

.ab-gallery-slide {
    position: absolute;
    inset: 0;
    background-size: cover;
    background-position: center;
    opacity: 0;
    transition: opacity 0.8s ease;
}

.ab-gallery-slide.is-active { opacity: 1; }

/* Caption overlay */
.ab-gallery-caption {
    position: absolute;
    bottom: 0; left: 0; right: 0;
    padding: clamp(1.25rem, 3vw, 2rem);
    background: linear-gradient(to top, rgba(26,31,46,0.92) 0%, rgba(26,31,46,0.6) 60%, transparent 100%);
    z-index: 5;
}

@media (min-width: 768px) {
    .ab-gallery-caption {
        top: 0; bottom: 0; left: auto;
        right: 0;
        width: 40%;
        max-width: 420px;
        background: rgba(26,31,46,0.88);
        backdrop-filter: blur(8px);
        -webkit-backdrop-filter: blur(8px);
        display: flex;
        flex-direction: column;
        justify-content: center;
        border-left: 3px solid var(--purple);
    }
}

.ab-gallery-caption-tag {
    font-family: var(--font-mono);
    font-size: 0.6rem;
    font-weight: 500;
    letter-spacing: 0.15em;
    text-transform: uppercase;
    color: var(--gold);
    margin-bottom: 0.65rem;
    display: flex;
    align-items: center;
    gap: 5px;
}

.ab-gallery-caption-tag::before {
    content: '';
    width: 20px; height: 1.5px;
    background: var(--gold);
    display: block;
}

.ab-gallery-caption h3 {
    font-family: var(--font-display);
    font-size: clamp(1.2rem, 2.5vw, 1.65rem);
    font-weight: 700;
    color: white;
    margin-bottom: 0.6rem;
    line-height: 1.25;
}

.ab-gallery-caption p {
    font-size: clamp(0.82rem, 1.2vw, 0.92rem);
    color: rgba(255,255,255,0.75);
    line-height: 1.6;
}

/* Dots */
.ab-gallery-dots {
    position: absolute;
    bottom: 1rem; left: 1rem;
    display: flex;
    gap: 0.4rem;
    z-index: 10;
}

@media (min-width: 768px) {
    .ab-gallery-dots { left: 1.25rem; bottom: 1.25rem; }
}

.ab-gallery-dot {
    width: 8px; height: 8px;
    border-radius: 50%;
    background: rgba(255,255,255,0.35);
    border: 1.5px solid rgba(255,255,255,0.6);
    cursor: pointer;
    transition: all 0.22s ease;
}

.ab-gallery-dot.is-active { background: var(--gold); border-color: var(--gold); transform: scale(1.3); }
.ab-gallery-dot:hover     { background: rgba(255,255,255,0.7); }

/* ==========================================================================
   CTA DARK CARD
   ========================================================================== */
.ab-cta-card {
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

.ab-cta-card::before {
    content: '';
    position: absolute;
    left: 0; top: 15%; bottom: 15%;
    width: 3px;
    background: linear-gradient(to bottom, var(--purple-light), var(--purple));
    border-radius: 3px;
}

.ab-cta-content { flex: 1; min-width: 220px; }

.ab-cta-tag {
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

.ab-cta-title {
    font-family: var(--font-display);
    font-size: clamp(1.4rem, 2.5vw, 2rem);
    font-weight: 700;
    color: white;
    margin-bottom: 0.5rem;
    letter-spacing: -0.01em;
}

.ab-cta-desc {
    font-size: 0.95rem;
    color: rgba(255,255,255,0.7);
    line-height: 1.6;
}

.ab-cta-actions { display: flex; gap: 0.85rem; flex-wrap: wrap; }

@media (max-width: 480px) {
    .ab-cta-card { flex-direction: column; }
    .ab-cta-actions { flex-direction: column; width: 100%; }
    .ab-cta-actions .ab-btn { justify-content: center; }
}

/* ==========================================================================
   ANIMATIONS
   ========================================================================== */
@keyframes ab-fadeIn {
    from { opacity: 0; transform: translateY(14px); }
    to   { opacity: 1; transform: translateY(0); }
}

.ab-mvv-card, .ab-leader-card, .ab-accred-card, .ab-stat-card {
    animation: ab-fadeIn 0.4s ease both;
}

.ab-mvv-card:nth-child(1), .ab-leader-card:nth-child(1), .ab-stat-card:nth-child(1) { animation-delay: 0.05s; }
.ab-mvv-card:nth-child(2), .ab-leader-card:nth-child(2), .ab-stat-card:nth-child(2) { animation-delay: 0.10s; }
.ab-mvv-card:nth-child(3), .ab-leader-card:nth-child(3), .ab-stat-card:nth-child(3) { animation-delay: 0.15s; }
.ab-leader-card:nth-child(4), .ab-stat-card:nth-child(4) { animation-delay: 0.20s; }
.ab-leader-card:nth-child(5) { animation-delay: 0.25s; }

@media (prefers-reduced-motion: reduce) {
    .ab-mvv-card, .ab-leader-card, .ab-accred-card, .ab-stat-card,
    .ab-gallery-slide { animation: none !important; transition: none !important; }
}

:focus-visible { outline: 2px solid var(--gold); outline-offset: 2px; border-radius: var(--radius-sm); }

.sr-only {
    position: absolute; width: 1px; height: 1px;
    padding: 0; margin: -1px; overflow: hidden;
    clip: rect(0,0,0,0); white-space: nowrap; border: 0;
}

/* Print */
@media print {
    .ab-hero, .ab-btn, .ab-gallery-wrap, .ab-cta-card { display: none !important; }
    .ab-mvv-card, .ab-leader-card, .ab-accred-card { box-shadow: none; border: 1px solid #ccc; break-inside: avoid; }
    .ab-quote-card { background: #f5f5f5; color: #000; }
}
</style>

<!-- =====================================================================
     PAGE ROOT
     ===================================================================== -->
<div class="ab-root">

    <!-- ── HERO — starts at very top ─────────────────────────────────────── -->
    <section class="ab-hero" aria-label="About FCT College of Nursing Sciences">
        <div class="ab-hero-bg"></div>

        <div class="ab-hero-inner">
            <div class="ab-hero-left">
                <div class="ab-hero-badge">
                    <span class="ab-hero-badge-icon"><i class="fas fa-building-columns"></i></span>
                    <span class="ab-hero-badge-text">Excellence Since 1989</span>
                </div>

                <h1 class="ab-hero-title">
                    About FCT College of <span class="accent">Nursing Sciences</span>
                </h1>

                <p class="ab-hero-subtitle">
                    A premier institution dedicated to excellence in nursing education, research, and healthcare training in Nigeria's Federal Capital Territory.
                </p>
            </div>

            <div class="ab-hero-right">
                <div class="ab-hero-stats">
                    <div class="ab-stat">
                        <span class="ab-stat-value">35+</span>
                        <span class="ab-stat-label">Years</span>
                    </div>
                    <div class="ab-stat">
                        <span class="ab-stat-value">5,000+</span>
                        <span class="ab-stat-label">Graduates</span>
                    </div>
                    <div class="ab-stat">
                        <span class="ab-stat-value">100%</span>
                        <span class="ab-stat-label">Accredited</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ── BREADCRUMB ────────────────────────────────────────────────────── -->
    <nav class="ab-breadcrumb" aria-label="Breadcrumb">
        <div class="ab-container">
            <ul class="ab-breadcrumb-list">
                <li><a href="<?php echo $baseUrl; ?>"><i class="fas fa-home"></i> Home</a></li>
                <li><span class="ab-breadcrumb-sep">/</span></li>
                <li><span class="ab-breadcrumb-current" aria-current="page">About</span></li>
            </ul>
        </div>
    </nav>

    <!-- ── MISSION / VISION / VALUES ─────────────────────────────────────── -->
    <div class="ab-section ab-section--alt ab-section--bordered">
        <div class="ab-container">
            <div class="ab-section-header">
                <div>
                    <h2 class="ab-section-title"><span class="ab-section-pip"></span> Mission, Vision &amp; Values</h2>
                    <p class="ab-section-subtitle">The guiding principles that define our commitment to nursing excellence.</p>
                </div>
            </div>

            <div class="ab-mvv-grid">

                <div class="ab-mvv-card">
                    <div class="ab-mvv-label">Our Mission</div>
                    <h3 class="ab-mvv-title">Mission</h3>
                    <p class="ab-mvv-text">
                        To prepare competent and polyvalent nurses that will use problem-solving skills in providing safe, acceptable, effective health services to meet the health needs of individuals, families and communities at all levels of care.
                    </p>
                </div>

                <div class="ab-mvv-card">
                    <div class="ab-mvv-label">Our Vision</div>
                    <h3 class="ab-mvv-title">Vision</h3>
                    <p class="ab-mvv-text">
                        To be one of the best colleges of Nursing Sciences in Nigeria, especially in imparting knowledge into prospective nurses and providing the health services required by the people of the FCT in particular, and Nigeria at large.
                    </p>
                </div>

                <div class="ab-mvv-card">
                    <div class="ab-mvv-label">Core Values</div>
                    <h3 class="ab-mvv-title">Values</h3>
                    <ul class="ab-values-list">
                        <li class="ab-value-item"><span class="ab-value-dot"></span> Excellence in Education</li>
                        <li class="ab-value-item"><span class="ab-value-dot"></span> Integrity and Ethics</li>
                        <li class="ab-value-item"><span class="ab-value-dot"></span> Compassionate Care</li>
                        <li class="ab-value-item"><span class="ab-value-dot"></span> Innovation and Research</li>
                        <li class="ab-value-item"><span class="ab-value-dot"></span> Professional Development</li>
                        <li class="ab-value-item"><span class="ab-value-dot"></span> Community Service</li>
                    </ul>
                </div>

            </div>
        </div>
    </div>

    <!-- ── STATISTICS ─────────────────────────────────────────────────────── -->
    <div class="ab-section">
        <div class="ab-container">
            <div class="ab-section-header">
                <h2 class="ab-section-title"><span class="ab-section-pip"></span> Our Impact in Numbers</h2>
            </div>

            <div class="ab-stats-grid">
                <div class="ab-stat-card">
                    <div class="ab-stat-card-value">35+</div>
                    <div class="ab-stat-card-label">Years of Excellence</div>
                </div>
                <div class="ab-stat-card">
                    <div class="ab-stat-card-value">5,000+</div>
                    <div class="ab-stat-card-label">Nursing Graduates</div>
                </div>
                <div class="ab-stat-card">
                    <div class="ab-stat-card-value">100%</div>
                    <div class="ab-stat-card-label">NMCN Accredited</div>
                </div>
                <div class="ab-stat-card">
                    <div class="ab-stat-card-value">50+</div>
                    <div class="ab-stat-card-label">Faculty Members</div>
                </div>
            </div>
        </div>
    </div>

    <!-- ── LEADERSHIP ─────────────────────────────────────────────────────── -->
    <div class="ab-section ab-section--alt ab-section--bordered">
        <div class="ab-container">
            <div class="ab-section-header">
                <div>
                    <h2 class="ab-section-title"><span class="ab-section-pip"></span> Institutional Leadership</h2>
                    <p class="ab-section-subtitle">Distinguished leaders shaping the future of nursing education in the Federal Capital Territory.</p>
                </div>
            </div>

            <div class="ab-leadership-grid">

                <div class="ab-leader-card">
                    <div class="ab-leader-img-wrap">
                        <img src="<?php echo rtrim($baseUrl,'/'); ?>/assets/images/leadership/fct-minister.jpg"
                             alt="Ezenwo Nyesom Wike CON"
                             class="ab-leader-img" loading="lazy"
                             onerror="this.closest('.ab-leader-img-wrap').style.background='var(--purple-pale)';">
                    </div>
                    <div class="ab-leader-body">
                        <h3 class="ab-leader-name">Ezenwo Nyesom Wike CON</h3>
                        <div class="ab-leader-role">FCT Minister</div>
                        <div class="ab-leader-dept">Federal Capital Territory Administration</div>
                        <p class="ab-leader-bio">Distinguished leader and legal practitioner driving transformative healthcare infrastructure development in the nation's capital.</p>
                        <div class="ab-leader-social">
                            <a href="#" aria-label="LinkedIn"><i class="fab fa-linkedin-in"></i></a>
                            <a href="#" aria-label="Email"><i class="fas fa-envelope"></i></a>
                        </div>
                    </div>
                </div>

                <div class="ab-leader-card">
                    <div class="ab-leader-img-wrap">
                        <img src="<?php echo rtrim($baseUrl,'/'); ?>/assets/images/leadership/mandate-secretary.jpg"
                             alt="Dr. Adedolapo Fasawe"
                             class="ab-leader-img" loading="lazy"
                             onerror="this.closest('.ab-leader-img-wrap').style.background='var(--purple-pale)';">
                    </div>
                    <div class="ab-leader-body">
                        <h3 class="ab-leader-name">Dr. Adedolapo Fasawe</h3>
                        <div class="ab-leader-role">Mandate Secretary</div>
                        <div class="ab-leader-dept">Health Services &amp; Environment Secretariat</div>
                        <p class="ab-leader-bio">Visionary public health administrator with extensive experience in healthcare policy and institutional development.</p>
                        <div class="ab-leader-social">
                            <a href="#" aria-label="LinkedIn"><i class="fab fa-linkedin-in"></i></a>
                            <a href="#" aria-label="Email"><i class="fas fa-envelope"></i></a>
                        </div>
                    </div>
                </div>

                <div class="ab-leader-card">
                    <div class="ab-leader-img-wrap">
                        <img src="<?php echo rtrim($baseUrl,'/'); ?>/assets/images/leadership/permanent-secretary.jpg"
                             alt="Dr. Babagana Adam"
                             class="ab-leader-img" loading="lazy"
                             onerror="this.closest('.ab-leader-img-wrap').style.background='var(--purple-pale)';">
                    </div>
                    <div class="ab-leader-body">
                        <h3 class="ab-leader-name">Dr. Babagana Adam</h3>
                        <div class="ab-leader-role">Permanent Secretary</div>
                        <div class="ab-leader-dept">Health Services &amp; Environment Secretariat</div>
                        <p class="ab-leader-bio">Seasoned administrator dedicated to strengthening public health systems and nursing education standards.</p>
                        <div class="ab-leader-social">
                            <a href="#" aria-label="LinkedIn"><i class="fab fa-linkedin-in"></i></a>
                            <a href="#" aria-label="Email"><i class="fas fa-envelope"></i></a>
                        </div>
                    </div>
                </div>

                <div class="ab-leader-card">
                    <div class="ab-leader-img-wrap">
                        <img src="<?php echo rtrim($baseUrl,'/'); ?>/assets/images/leadership/director-nursing.jpg"
                             alt="Mrs Ijoema Jimi Bada"
                             class="ab-leader-img" loading="lazy"
                             onerror="this.closest('.ab-leader-img-wrap').style.background='var(--purple-pale)';">
                    </div>
                    <div class="ab-leader-body">
                        <h3 class="ab-leader-name">Mrs Ijoema Jimi Bada</h3>
                        <div class="ab-leader-role">Director, Nursing Services</div>
                        <div class="ab-leader-dept">Nursing Services</div>
                        <p class="ab-leader-bio">Accomplished nursing professional advancing nursing practice standards and clinical education excellence.</p>
                        <div class="ab-leader-social">
                            <a href="#" aria-label="LinkedIn"><i class="fab fa-linkedin-in"></i></a>
                            <a href="#" aria-label="Email"><i class="fas fa-envelope"></i></a>
                        </div>
                    </div>
                </div>

                <div class="ab-leader-card">
                    <div class="ab-leader-img-wrap">
                        <img src="<?php echo rtrim($baseUrl,'/'); ?>/assets/images/leadership/college-provost.jpg"
                             alt="Comr. Deborah Yusuf"
                             class="ab-leader-img" loading="lazy"
                             onerror="this.closest('.ab-leader-img-wrap').style.background='var(--purple-pale)';">
                    </div>
                    <div class="ab-leader-body">
                        <h3 class="ab-leader-name">Comr. Deborah Yusuf</h3>
                        <div class="ab-leader-role">Provost</div>
                        <div class="ab-leader-dept">FCT College of Nursing Sciences</div>
                        <p class="ab-leader-bio">Dynamic academic leader committed to innovative curriculum development and student-centered learning approaches.</p>
                        <div class="ab-leader-social">
                            <a href="#" aria-label="LinkedIn"><i class="fab fa-linkedin-in"></i></a>
                            <a href="#" aria-label="Email"><i class="fas fa-envelope"></i></a>
                        </div>
                    </div>
                </div>

            </div>

            <!-- Quote card -->
            <div class="ab-quote-card">
                <p class="ab-quote-text">"Our collective vision is to nurture a new generation of nursing professionals who will transform healthcare delivery across Nigeria. Through strategic leadership and unwavering commitment to excellence, we are building an institution that stands as a beacon of hope and quality education."</p>
                <div class="ab-quote-author">Comr. Deborah Yusuf</div>
                <div class="ab-quote-role">Provost, FCT College of Nursing Sciences</div>
            </div>

        </div>
    </div>

    <!-- ── ACCREDITATION ──────────────────────────────────────────────────── -->
    <div class="ab-section">
        <div class="ab-container">
            <div class="ab-section-header">
                <div>
                    <h2 class="ab-section-title"><span class="ab-section-pip"></span> Institutional Accreditation</h2>
                    <p class="ab-section-subtitle">Nationally recognised and fully accredited by Nigeria's premier regulatory bodies.</p>
                </div>
            </div>

            <div class="ab-accred-grid">

                <div class="ab-accred-card">
                    <span class="ab-accred-badge">Full Accreditation</span>
                    <div class="ab-accred-icon-wrap"><i class="fas fa-stethoscope"></i></div>
                    <div>
                        <div class="ab-accred-abbr">NMCN</div>
                        <div class="ab-accred-name">Nursing &amp; Midwifery Council of Nigeria</div>
                    </div>
                    <p class="ab-accred-desc">Full accreditation for all nursing programmes, ensuring graduates meet the highest professional standards.</p>
                    <ul class="ab-accred-features">
                        <li><i class="fas fa-check-circle"></i> Basic Nursing Programme</li>
                        <li><i class="fas fa-check-circle"></i> Post-Basic Nursing Programmes</li>
                        <li><i class="fas fa-check-circle"></i> Midwifery Education</li>
                        <li><i class="fas fa-check-circle"></i> Continuing Professional Development</li>
                    </ul>
                </div>

                <div class="ab-accred-card">
                    <span class="ab-accred-badge">Full Accreditation</span>
                    <div class="ab-accred-icon-wrap"><i class="fas fa-university"></i></div>
                    <div>
                        <div class="ab-accred-abbr">NBTE</div>
                        <div class="ab-accred-name">National Board for Technical Education</div>
                    </div>
                    <p class="ab-accred-desc">Comprehensive accreditation for technical education programmes, demonstrating our commitment to educational excellence.</p>
                    <ul class="ab-accred-features">
                        <li><i class="fas fa-check-circle"></i> Community Health Programmes</li>
                        <li><i class="fas fa-check-circle"></i> Health Technology Programmes</li>
                        <li><i class="fas fa-check-circle"></i> Technical Nursing Education</li>
                        <li><i class="fas fa-check-circle"></i> Vocational Training Standards</li>
                    </ul>
                </div>

            </div>
        </div>
    </div>

    <!-- ── GALLERY ────────────────────────────────────────────────────────── -->
    <div class="ab-section ab-section--alt ab-section--bordered">
        <div class="ab-container">
            <div class="ab-section-header">
                <div>
                    <h2 class="ab-section-title"><span class="ab-section-pip"></span> Our Learning Environment</h2>
                    <p class="ab-section-subtitle">Modern facilities supporting excellence in nursing education.</p>
                </div>
            </div>

            <div class="ab-gallery-wrap" role="region" aria-label="Campus gallery">

                <div class="ab-gallery-slide is-active"
                     style="background-image:url('<?php echo rtrim($baseUrl,'/'); ?>/assets/images/about/simulation-lab.jpg');"
                     role="img" aria-label="Simulation Laboratory">
                    <div class="ab-gallery-caption">
                        <div class="ab-gallery-caption-tag">Facility</div>
                        <h3>Simulation Laboratory</h3>
                        <p>State-of-the-art simulation lab where students practise clinical skills in a controlled, realistic environment.</p>
                    </div>
                </div>

                <div class="ab-gallery-slide"
                     style="background-image:url('<?php echo rtrim($baseUrl,'/'); ?>/assets/images/about/library.jpg');"
                     role="img" aria-label="Medical Library">
                    <div class="ab-gallery-caption">
                        <div class="ab-gallery-caption-tag">Facility</div>
                        <h3>Medical Library</h3>
                        <p>Comprehensive collection of nursing journals, textbooks, and digital resources for research and study.</p>
                    </div>
                </div>

                <div class="ab-gallery-slide"
                     style="background-image:url('<?php echo rtrim($baseUrl,'/'); ?>/assets/images/about/classroom.jpg');"
                     role="img" aria-label="Interactive Classroom">
                    <div class="ab-gallery-caption">
                        <div class="ab-gallery-caption-tag">Facility</div>
                        <h3>Interactive Classrooms</h3>
                        <p>Technology-enhanced learning spaces designed for collaborative nursing education and discussion.</p>
                    </div>
                </div>

                <div class="ab-gallery-slide"
                     style="background-image:url('<?php echo rtrim($baseUrl,'/'); ?>/assets/images/about/campus-building.jpg');"
                     role="img" aria-label="Main Campus">
                    <div class="ab-gallery-caption">
                        <div class="ab-gallery-caption-tag">Campus</div>
                        <h3>Main Campus</h3>
                        <p>The heart of our institution where future nursing professionals begin their transformative journey.</p>
                    </div>
                </div>

                <div class="ab-gallery-dots" role="tablist" aria-label="Gallery slides">
                    <div class="ab-gallery-dot is-active" data-slide="0" role="tab" aria-selected="true" tabindex="0" aria-label="Slide 1"></div>
                    <div class="ab-gallery-dot" data-slide="1" role="tab" aria-selected="false" tabindex="0" aria-label="Slide 2"></div>
                    <div class="ab-gallery-dot" data-slide="2" role="tab" aria-selected="false" tabindex="0" aria-label="Slide 3"></div>
                    <div class="ab-gallery-dot" data-slide="3" role="tab" aria-selected="false" tabindex="0" aria-label="Slide 4"></div>
                </div>

            </div>
        </div>
    </div>

    <!-- ── CTA ───────────────────────────────────────────────────────────── -->
    <div class="ab-section">
        <div class="ab-container">
            <div class="ab-cta-card">
                <div class="ab-cta-content">
                    <span class="ab-cta-tag"><i class="fas fa-graduation-cap"></i> Join Our Community</span>
                    <h2 class="ab-cta-title">Begin Your Nursing Journey</h2>
                    <p class="ab-cta-desc">Join thousands of graduates making a difference in healthcare across Nigeria.</p>
                </div>
                <div class="ab-cta-actions">
                    <a href="<?php echo $baseUrl; ?>/admissions" class="ab-btn ab-btn--gold ab-btn--lg">
                        <i class="fas fa-file-alt"></i> Apply Now
                    </a>
                    <a href="<?php echo $baseUrl; ?>/programs" class="ab-btn ab-btn--ghost ab-btn--lg">
                        <i class="fas fa-book-open"></i> Explore Programs
                    </a>
                </div>
            </div>
        </div>
    </div>

</div><!-- /.ab-root -->

<script>
(function () {
    'use strict';

    /* ── Gallery Carousel ─────────────────────────────────────────── */
    var slides  = document.querySelectorAll('.ab-gallery-slide');
    var dots    = document.querySelectorAll('.ab-gallery-dot');
    var current = 0;
    var timer;

    function showSlide(idx) {
        slides.forEach(function (s, i) {
            s.classList.toggle('is-active', i === idx);
        });
        dots.forEach(function (d, i) {
            d.classList.toggle('is-active', i === idx);
            d.setAttribute('aria-selected', i === idx ? 'true' : 'false');
        });
        current = idx;
    }

    function next() { showSlide((current + 1) % slides.length); }
    function prev() { showSlide((current - 1 + slides.length) % slides.length); }

    function startAuto() { timer = setInterval(next, 5000); }
    function stopAuto()  { clearInterval(timer); }

    dots.forEach(function (d) {
        d.addEventListener('click', function () {
            stopAuto(); showSlide(parseInt(d.dataset.slide, 10)); startAuto();
        });
        d.addEventListener('keydown', function (e) {
            if (e.key === 'Enter' || e.key === ' ') {
                e.preventDefault(); stopAuto(); showSlide(parseInt(d.dataset.slide, 10)); startAuto();
            }
        });
    });

    var wrap = document.querySelector('.ab-gallery-wrap');
    if (wrap) {
        wrap.addEventListener('mouseenter', stopAuto);
        wrap.addEventListener('mouseleave', startAuto);

        var tx = 0;
        wrap.addEventListener('touchstart', function (e) { tx = e.changedTouches[0].screenX; stopAuto(); });
        wrap.addEventListener('touchend',   function (e) {
            var diff = tx - e.changedTouches[0].screenX;
            if (Math.abs(diff) > 50) { diff > 0 ? next() : prev(); }
            startAuto();
        });

        document.addEventListener('keydown', function (e) {
            if (!document.activeElement.closest('.ab-gallery-wrap')) return;
            if (e.key === 'ArrowLeft')  { stopAuto(); prev(); startAuto(); }
            if (e.key === 'ArrowRight') { stopAuto(); next(); startAuto(); }
        });
    }

    startAuto();

    /* ── Hero background fallback ─────────────────────────────────── */
    var heroBg = document.querySelector('.ab-hero-bg');
    if (heroBg) {
        var img = new Image();
        img.onerror = function () { heroBg.style.backgroundImage = 'none'; };
        img.src = '<?php echo $heroImagePath; ?>';
    }

    /* ── Scroll-triggered fade-in ─────────────────────────────────── */
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

        document.querySelectorAll('.ab-mvv-card, .ab-leader-card, .ab-accred-card, .ab-stat-card')
            .forEach(function (el, i) {
                el.style.opacity = '0';
                el.style.transform = 'translateY(18px)';
                el.style.transition = 'opacity .5s ease ' + (i * 0.07) + 's, transform .5s ease ' + (i * 0.07) + 's';
                io.observe(el);
            });
    }

    /* ── Smooth scroll ─────────────────────────────────────────────── */
    document.querySelectorAll('a[href^="#"]').forEach(function (a) {
        a.addEventListener('click', function (e) {
            var id = a.getAttribute('href');
            if (id === '#') return;
            var t = document.querySelector(id);
            if (t) { e.preventDefault(); window.scrollTo({ top: t.offsetTop - 90, behavior: 'smooth' }); }
        });
    });
})();
</script>