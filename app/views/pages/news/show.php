<?php
/**
 * Single News Article — Show Page
 * File: /app/views/pages/news/show.php
 * Rendered inside main layout (header + footer already present).
 */

$baseUrl        = $baseUrl        ?? (defined('BASE_URL') ? BASE_URL : '');
$news           = $news           ?? [];
$relatedNews    = $relatedNews    ?? [];
$popularNews    = $popularNews    ?? [];
$allNewsTopics  = $allNewsTopics  ?? [];

// Dates
$newsDate        = !empty($news['created_at']) ? date('F j, Y',  strtotime($news['created_at'])) : '';
$newsDateIso     = !empty($news['created_at']) ? date('c',       strtotime($news['created_at'])) : '';

// Author
$authorName      = $news['author_name'] ?? $news['full_name'] ?? 'FCT Nursing College';
$authorRole      = $news['author_role'] ?? 'Communications';
$authorInitial   = strtoupper(substr($authorName, 0, 1));

// Reading time
$wordCount       = !empty($news['content']) ? str_word_count(strip_tags($news['content'])) : 0;
$readingTime     = max(1, ceil($wordCount / 200));

// Breadcrumb
$breadcrumb = [
    ['label' => 'Home', 'url' => $baseUrl],
    ['label' => 'News', 'url' => $baseUrl . '/news'],
    ['label' => htmlspecialchars(mb_strimwidth($news['title'] ?? 'Article', 0, 52, '…')), 'url' => ''],
];

// Topics (same logic as original, kept intact)
$topicLibrary = [
    'nursing'      => ['display_name'=>'Nursing',      'icon'=>'fa-solid fa-stethoscope',    'color'=>'#7C6FAB','bg'=>'rgba(124,111,171,0.1)', 'description'=>'Nursing education & practice',    'slug'=>'nursing'],
    'research'     => ['display_name'=>'Research',     'icon'=>'fa-solid fa-flask',           'color'=>'#B8860B','bg'=>'rgba(184,134,11,0.1)',  'description'=>'Latest findings & studies',       'slug'=>'research'],
    'education'    => ['display_name'=>'Education',    'icon'=>'fa-solid fa-graduation-cap',  'color'=>'#5A4F8A','bg'=>'rgba(90,79,138,0.08)',  'description'=>'Academic programs & training',    'slug'=>'education'],
    'healthcare'   => ['display_name'=>'Healthcare',   'icon'=>'fa-solid fa-heart-pulse',     'color'=>'#7C6FAB','bg'=>'rgba(124,111,171,0.1)', 'description'=>'Clinical practice & healthcare',  'slug'=>'healthcare'],
    'announcement' => ['display_name'=>'Announcement', 'icon'=>'fa-solid fa-bullhorn',        'color'=>'#9B8FCC','bg'=>'rgba(155,143,204,0.1)', 'description'=>'Institutional updates',           'slug'=>'announcement'],
    'student'      => ['display_name'=>'Student',      'icon'=>'fa-solid fa-user-graduate',   'color'=>'#B8860B','bg'=>'rgba(184,134,11,0.08)', 'description'=>'Student life & achievements',     'slug'=>'student'],
    'faculty'      => ['display_name'=>'Faculty',      'icon'=>'fa-solid fa-chalkboard-user', 'color'=>'#7C6FAB','bg'=>'rgba(124,111,171,0.1)', 'description'=>'Faculty excellence',              'slug'=>'faculty'],
    'event'        => ['display_name'=>'Event',        'icon'=>'fa-solid fa-calendar-check',  'color'=>'#D4A520','bg'=>'rgba(212,165,32,0.1)',  'description'=>'Upcoming events',                 'slug'=>'event'],
    'policy'       => ['display_name'=>'Policy',       'icon'=>'fa-solid fa-file-lines',      'color'=>'#64748B','bg'=>'rgba(100,116,139,0.08)','description'=>'Policies & guidelines',           'slug'=>'policy'],
    'award'        => ['display_name'=>'Award',        'icon'=>'fa-solid fa-trophy',          'color'=>'#B8860B','bg'=>'rgba(184,134,11,0.1)',  'description'=>'Recognition & awards',            'slug'=>'award'],
    'community'    => ['display_name'=>'Community',    'icon'=>'fa-solid fa-people-arrows',   'color'=>'#9B8FCC','bg'=>'rgba(155,143,204,0.1)', 'description'=>'Community engagement',            'slug'=>'community'],
];

if (empty($allNewsTopics) && !empty($news['tags'])) {
    $tags = is_string($news['tags'])
        ? (json_decode($news['tags'], true) ?: array_map('trim', explode(',', $news['tags'])))
        : (array)$news['tags'];
    foreach ($tags as $tag) {
        $k = strtolower(trim($tag));
        $allNewsTopics[$k] = ($allNewsTopics[$k] ?? 0) + 1;
    }
}

$displayTopics = [];
foreach ($allNewsTopics as $tagName => $count) {
    if ($count < 1) continue;
    $k = strtolower(trim($tagName));
    $lib = $topicLibrary[$k] ?? null;
    $displayTopics[$k] = [
        'display_name' => $lib ? $lib['display_name'] : ucwords($tagName),
        'slug'         => $lib ? $lib['slug']         : urlencode($tagName),
        'icon'         => $lib ? $lib['icon']         : 'fa-solid fa-tag',
        'color'        => $lib ? $lib['color']        : '#7C6FAB',
        'bg'           => $lib ? $lib['bg']           : 'rgba(124,111,171,0.1)',
        'description'  => $lib ? $lib['description']  : 'Related content',
        'count'        => (int)$count,
    ];
}
uasort($displayTopics, fn($a,$b) => $b['count'] - $a['count']);
$displayTopics = array_slice($displayTopics, 0, 6, true);

// Featured image src
$heroImgSrc = '';
if (!empty($news['featured_image'])) {
    $p = $news['featured_image'];
    if (strpos($p, 'http') === 0 || strpos($p, '//') === 0) {
        $heroImgSrc = htmlspecialchars($p);
    } elseif (strpos($p, '/uploads/') === 0) {
        $heroImgSrc = $baseUrl . htmlspecialchars($p);
    } else {
        $heroImgSrc = $baseUrl . '/uploads/news/' . htmlspecialchars($p);
    }
}
?>

<!-- Fonts (same as index) -->
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
/* ═══════════════════════════════════════════════════════════
   DESIGN TOKENS  — identical to news index
═══════════════════════════════════════════════════════════ */
:root{
    --ink:         #0E1117;
    --ink-mid:     #1C2333;
    --ink-soft:    #2D3748;
    --slate:       #64748B;
    --mist:        #94A3B8;
    --border:      #E2E8F0;
    --surface:     #F8FAFC;
    --white:       #FFFFFF;

    --purple:      #7C6FAB;
    --purple-dark: #5A4F8A;
    --purple-pale: #F0EEF9;
    --purple-mid:  #9B8FCC;

    --gold:        #B8860B;
    --gold-light:  #D4A520;
    --gold-pale:   #FFFBEB;

    --font-display:'Cormorant Garamond', Georgia, serif;
    --font-body:   'Outfit', system-ui, sans-serif;
    --font-mono:   'JetBrains Mono', monospace;

    --radius-sm:   6px;
    --radius-md:   12px;
    --radius-lg:   20px;
    --radius-xl:   28px;

    --shadow-sm:   0 2px 8px  rgba(0,0,0,0.07);
    --shadow-md:   0 6px 24px rgba(0,0,0,0.08);
    --shadow-lg:   0 16px 48px rgba(0,0,0,0.10);
    --shadow-xl:   0 32px 80px rgba(0,0,0,0.12);
}

/* ── Scoped reset ── */
.ns-root *, .ns-root *::before, .ns-root *::after { box-sizing: border-box; }
.ns-root {
    font-family: var(--font-body);
    color: var(--ink);
    background: var(--white);
    overflow-x: hidden;
    -webkit-font-smoothing: antialiased;
    width: 100%;
}

/* ── Container ── */
.ns-container {
    width: 100%;
    max-width: 1320px;
    margin: 0 auto;
    padding-left:  clamp(1rem, 4vw, 2.5rem);
    padding-right: clamp(1rem, 4vw, 2.5rem);
}

/* ═══════════════════════════════════════════════════════════
   BREADCRUMB
═══════════════════════════════════════════════════════════ */
.ns-breadcrumb {
    background: var(--white);
    border-bottom: 1px solid var(--border);
    padding: 0.8rem 0;
}
.ns-breadcrumb-list {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    list-style: none;
    font-family: var(--font-mono);
    font-size: 0.72rem;
    flex-wrap: wrap;
}
.ns-breadcrumb-list a         { color: var(--purple-dark); text-decoration: none; font-weight: 500; }
.ns-breadcrumb-list a:hover   { color: var(--purple); text-decoration: underline; }
.ns-bc-sep     { color: var(--mist); }
.ns-bc-current { color: var(--slate); }

/* ═══════════════════════════════════════════════════════════
   ARTICLE HERO  — mirrors the featured card on the index:
   image on left, dark-panel text on right (split grid).
   On mobile: image on top, text below — never overlapping.
═══════════════════════════════════════════════════════════ */
.ns-hero-wrap {
    background: linear-gradient(145deg, #16152A 0%, #1A1B30 35%, var(--ink-mid) 100%);
    padding: clamp(2rem, 5vw, 3.5rem) 0 0;
    position: relative;
    overflow: hidden;
}

/* Same diagonal stripe texture as index hero */
.ns-hero-wrap::before {
    content: '';
    position: absolute;
    inset: 0;
    background-image: repeating-linear-gradient(
        -55deg, transparent, transparent 40px,
        rgba(255,255,255,0.016) 40px, rgba(255,255,255,0.016) 41px
    );
    pointer-events: none;
    z-index: 0;
}

/* Purple radial glow — top-left */
.ns-hero-wrap::after {
    content: '';
    position: absolute;
    top: -80px; left: -60px;
    width: 500px; height: 500px;
    border-radius: 50%;
    background: radial-gradient(circle, rgba(124,111,171,0.22) 0%, transparent 65%);
    pointer-events: none;
    z-index: 0;
}

/* ── Split card ── */
.ns-hero-card {
    position: relative;
    z-index: 1;
    border-radius: var(--radius-xl) var(--radius-xl) 0 0;
    overflow: hidden;
    background: var(--ink-mid);
    box-shadow: var(--shadow-xl);
    display: grid;
    grid-template-columns: 1fr;          /* mobile: stacked */
    grid-template-rows: 260px auto;
}

@media (min-width: 680px) {
    .ns-hero-card {
        grid-template-columns: 52% 48%;
        grid-template-rows: unset;
        min-height: 400px;
    }
}

@media (min-width: 1024px) {
    .ns-hero-card {
        grid-template-columns: 58% 42%;
        min-height: 460px;
    }
}

/* Image cell */
.ns-hero-img-cell {
    position: relative;
    overflow: hidden;
    background: var(--ink);
    min-height: 260px;
}

@media (min-width: 680px) { .ns-hero-img-cell { min-height: unset; } }

.ns-hero-img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    display: block;
    transition: transform 6s ease;
}
.ns-hero-card:hover .ns-hero-img { transform: scale(1.04); }

/* Right-fade blending into text panel on desktop */
.ns-hero-img-cell::after {
    content: '';
    position: absolute;
    inset: 0;
    background: linear-gradient(to right, transparent 45%, rgba(28,35,51,0.75) 100%);
    pointer-events: none;
}

@media (max-width: 679px) {
    /* Bottom fade on mobile so text panel reads clearly */
    .ns-hero-img-cell::after {
        background: linear-gradient(to bottom, transparent 35%, rgba(14,17,23,0.9) 100%);
    }
}

/* Image placeholder (no image) */
.ns-hero-img-placeholder {
    width: 100%; height: 100%;
    display: flex; align-items: center; justify-content: center;
    background: linear-gradient(135deg, #1A1B30, #2A2540);
    color: rgba(255,255,255,0.12);
    font-size: 5rem;
}

/* Text cell */
.ns-hero-text {
    background: linear-gradient(160deg, #1C2333 40%, #201E38 100%);
    padding: clamp(1.75rem, 4vw, 2.75rem);
    display: flex;
    flex-direction: column;
    justify-content: center;
    gap: 0;
    position: relative;
}

/* Left purple border accent — desktop only */
.ns-hero-text::before {
    content: '';
    position: absolute;
    left: 0; top: 12%; bottom: 12%;
    width: 3px;
    background: linear-gradient(to bottom, var(--purple-mid), var(--purple));
    border-radius: 3px;
}
@media (max-width: 679px) { .ns-hero-text::before { display: none; } }

/* Tag / category badge */
.ns-hero-tag {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    background: var(--purple);
    color: var(--white);
    font-family: var(--font-mono);
    font-size: 0.62rem;
    font-weight: 500;
    letter-spacing: 0.15em;
    text-transform: uppercase;
    padding: 4px 12px;
    border-radius: 4px;
    margin-bottom: 1rem;
    width: fit-content;
}

/* Article title */
.ns-hero-title {
    font-family: var(--font-display);
    font-size: clamp(1.4rem, 3vw, 2.2rem);
    font-weight: 700;
    line-height: 1.15;
    color: var(--white);
    margin-bottom: 0.875rem;
    letter-spacing: -0.01em;
}

/* Excerpt — hidden on mobile (full content below) */
.ns-hero-excerpt {
    font-size: 0.9rem;
    color: rgba(255,255,255,0.62);
    line-height: 1.65;
    margin-bottom: 1.25rem;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
@media (max-width: 679px) { .ns-hero-excerpt { display: none; } }

/* Meta row */
.ns-hero-meta {
    display: flex;
    align-items: center;
    gap: 1.125rem;
    flex-wrap: wrap;
}
.ns-hero-meta-item {
    display: flex;
    align-items: center;
    gap: 5px;
    font-family: var(--font-mono);
    font-size: 0.68rem;
    color: rgba(255,255,255,0.45);
    letter-spacing: 0.04em;
}
.ns-hero-meta-item i { color: var(--gold-light); font-size: 0.62rem; }

/* Caption strip below the hero card */
.ns-hero-caption {
    background: var(--ink-mid);
    padding: 0.75rem clamp(1rem, 4vw, 2.5rem);
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 0.75rem;
    border-top: 1px solid rgba(255,255,255,0.06);
}

.ns-hero-author {
    display: flex;
    align-items: center;
    gap: 10px;
}
.ns-hero-avatar {
    width: 34px; height: 34px;
    border-radius: 50%;
    background: linear-gradient(145deg, var(--purple), var(--purple-mid));
    color: var(--white);
    font-family: var(--font-display);
    font-size: 0.9rem;
    font-weight: 700;
    display: flex; align-items: center; justify-content: center;
    flex-shrink: 0;
}
.ns-hero-author-text { display: flex; flex-direction: column; gap: 1px; }
.ns-hero-author-name {
    font-family: var(--font-body);
    font-size: 0.82rem;
    font-weight: 600;
    color: rgba(255,255,255,0.85);
}
.ns-hero-author-role {
    font-family: var(--font-mono);
    font-size: 0.6rem;
    letter-spacing: 0.1em;
    text-transform: uppercase;
    color: var(--gold-light);
}

.ns-share-mini {
    display: flex;
    align-items: center;
    gap: 6px;
}
.ns-share-label {
    font-family: var(--font-mono);
    font-size: 0.62rem;
    letter-spacing: 0.1em;
    text-transform: uppercase;
    color: rgba(255,255,255,0.35);
    margin-right: 4px;
}
.ns-share-btn {
    width: 30px; height: 30px;
    border-radius: 50%;
    display: flex; align-items: center; justify-content: center;
    color: var(--white);
    font-size: 0.7rem;
    transition: transform 0.2s, opacity 0.2s;
    opacity: 0.75;
}
.ns-share-btn:hover { transform: translateY(-2px); opacity: 1; }
.ns-share-btn.fb   { background: #1877F2; }
.ns-share-btn.tw   { background: #1DA1F2; }
.ns-share-btn.li   { background: #0A66C2; }
.ns-share-btn.wa   { background: #25D366; }
.ns-share-btn.em   { background: var(--slate); }

/* ═══════════════════════════════════════════════════════════
   PAGE BODY  (article + sidebar)
═══════════════════════════════════════════════════════════ */
.ns-body {
    background: var(--surface);
    padding: clamp(2rem, 5vw, 3.5rem) 0 clamp(3rem, 6vw, 5rem);
}

.ns-layout {
    display: grid;
    grid-template-columns: 1fr;
    gap: 2.5rem;
}
@media (min-width: 1080px) {
    .ns-layout { grid-template-columns: 1fr 308px; }
}

/* ── Article content card ── */
.ns-article-card {
    background: var(--white);
    border: 1px solid var(--border);
    border-radius: var(--radius-lg);
    overflow: hidden;
    box-shadow: var(--shadow-sm);
}

.ns-article-body {
    padding: clamp(1.5rem, 5vw, 3rem);
}

/* Drop cap */
.ns-article-content {
    font-family: var(--font-body);
    font-size: clamp(1rem, 1.8vw, 1.1rem);
    line-height: 1.85;
    color: var(--ink-soft);
}

.ns-article-content > p:first-of-type::first-letter {
    font-family: var(--font-display);
    font-size: 4.2rem;
    font-weight: 700;
    float: left;
    line-height: 0.82;
    margin-right: 0.15em;
    padding-top: 0.12em;
    color: var(--purple);
}

.ns-article-content p            { margin-bottom: 1.4em; }
.ns-article-content h2           { font-family: var(--font-display); font-size: clamp(1.4rem,3vw,1.8rem); color: var(--ink); margin: 2em 0 0.6em; }
.ns-article-content h3           { font-family: var(--font-display); font-size: clamp(1.15rem,2.5vw,1.4rem); color: var(--ink); margin: 1.75em 0 0.5em; }
.ns-article-content blockquote   { border-left: 3px solid var(--gold-light); padding: 1em 1.5em; margin: 1.5em 0; background: var(--gold-pale); border-radius: 0 var(--radius-sm) var(--radius-sm) 0; font-style: italic; color: var(--ink-soft); }
.ns-article-content ul, .ns-article-content ol { padding-left: 1.5em; margin-bottom: 1.4em; }
.ns-article-content li           { margin-bottom: 0.4em; }
.ns-article-content img          { max-width: 100%; border-radius: var(--radius-md); margin: 1em 0; }
.ns-article-content a            { color: var(--purple); text-decoration: underline; text-decoration-color: rgba(124,111,171,0.4); }
.ns-article-content a:hover      { color: var(--purple-dark); }

/* ── Article footer ── */
.ns-article-footer {
    padding: 1.5rem clamp(1.5rem, 5vw, 3rem);
    border-top: 1px solid var(--border);
    display: flex;
    flex-direction: column;
    gap: 1.5rem;
}

/* Topics hub */
.ns-topics {
    background: var(--surface);
    border: 1px solid var(--border);
    border-radius: var(--radius-lg);
    padding: 1.5rem;
    position: relative;
    overflow: hidden;
}
.ns-topics::before {
    content: '';
    position: absolute;
    top: 0; left: 0; right: 0;
    height: 3px;
    background: linear-gradient(90deg, var(--purple) 80px, var(--border) 80px);
}
.ns-topics-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 1.125rem;
    flex-wrap: wrap;
    gap: 0.75rem;
}
.ns-topics-title {
    font-family: var(--font-display);
    font-size: 1.25rem;
    font-weight: 700;
    color: var(--ink);
    display: flex; align-items: center; gap: 8px;
}
.ns-topics-title-pip {
    width: 8px; height: 8px;
    border-radius: 50%;
    background: var(--purple);
    flex-shrink: 0;
}
.ns-topics-link {
    font-family: var(--font-mono);
    font-size: 0.68rem;
    font-weight: 500;
    letter-spacing: 0.08em;
    color: var(--purple-dark);
    text-decoration: none;
    display: flex; align-items: center; gap: 5px;
    padding: 5px 12px;
    border: 1px solid var(--border);
    border-radius: var(--radius-sm);
    background: var(--white);
    transition: all 0.2s;
}
.ns-topics-link:hover { background: var(--purple); color: var(--white); border-color: var(--purple); }

.ns-topics-grid {
    display: grid;
    grid-template-columns: 1fr;
    gap: 0.75rem;
}
@media (min-width: 560px) { .ns-topics-grid { grid-template-columns: repeat(2, 1fr); } }

.ns-topic-card {
    display: flex;
    align-items: center;
    gap: 0.875rem;
    padding: 0.875rem 1rem;
    background: var(--white);
    border: 1px solid var(--border);
    border-radius: var(--radius-md);
    text-decoration: none;
    transition: all 0.25s ease;
    min-width: 0;
}
.ns-topic-card:hover {
    border-color: rgba(124,111,171,0.3);
    box-shadow: var(--shadow-md);
    transform: translateY(-2px);
}
.ns-topic-icon {
    width: 40px; height: 40px;
    border-radius: 50%;
    display: flex; align-items: center; justify-content: center;
    font-size: 1rem;
    flex-shrink: 0;
    transition: transform 0.2s;
}
.ns-topic-card:hover .ns-topic-icon { transform: scale(1.1); }
.ns-topic-body { flex: 1; min-width: 0; }
.ns-topic-name {
    display: flex; align-items: baseline; gap: 6px;
    flex-wrap: wrap;
    margin-bottom: 2px;
}
.ns-topic-title {
    font-family: var(--font-body);
    font-size: 0.9rem;
    font-weight: 600;
    color: var(--ink);
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}
.ns-topic-count {
    font-family: var(--font-mono);
    font-size: 0.62rem;
    color: var(--slate);
    background: var(--surface);
    padding: 2px 7px;
    border-radius: 20px;
    border: 1px solid var(--border);
    white-space: nowrap;
    flex-shrink: 0;
}
.ns-topic-card:hover .ns-topic-count { background: var(--purple-pale); color: var(--purple-dark); border-color: rgba(124,111,171,0.25); }
.ns-topic-desc {
    font-family: var(--font-mono);
    font-size: 0.62rem;
    color: var(--mist);
    letter-spacing: 0.02em;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

/* Share row */
.ns-share-row {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 1rem;
    flex-wrap: wrap;
    padding: 1rem 1.25rem;
    background: var(--surface);
    border: 1px solid var(--border);
    border-left: 3px solid var(--purple);
    border-radius: var(--radius-md);
}
.ns-share-row-label {
    font-family: var(--font-mono);
    font-size: 0.7rem;
    font-weight: 500;
    letter-spacing: 0.12em;
    text-transform: uppercase;
    color: var(--slate);
    display: flex; align-items: center; gap: 6px;
}
.ns-share-row-btns { display: flex; gap: 8px; flex-wrap: wrap; }
.ns-share-row-btn {
    width: 36px; height: 36px;
    border-radius: 50%;
    display: flex; align-items: center; justify-content: center;
    color: var(--white);
    font-size: 0.8rem;
    transition: all 0.2s;
}
.ns-share-row-btn:hover { transform: translateY(-2px); box-shadow: var(--shadow-md); }
.ns-share-row-btn.fb  { background: #1877F2; }
.ns-share-row-btn.tw  { background: #1DA1F2; }
.ns-share-row-btn.li  { background: #0A66C2; }
.ns-share-row-btn.wa  { background: #25D366; }
.ns-share-row-btn.em  { background: var(--slate); }

/* Back button */
.ns-back {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    color: var(--purple-dark);
    font-family: var(--font-body);
    font-size: 0.875rem;
    font-weight: 600;
    text-decoration: none;
    padding: 0.6rem 1.25rem;
    border: 1.5px solid var(--purple);
    border-radius: var(--radius-sm);
    transition: all 0.22s;
    width: fit-content;
}
.ns-back:hover { background: var(--purple); color: var(--white); }

/* ═══════════════════════════════════════════════════════════
   SIDEBAR
═══════════════════════════════════════════════════════════ */
.ns-sidebar {
    display: flex;
    flex-direction: column;
    gap: 1.5rem;
}
@media (min-width: 1080px) {
    .ns-sidebar { position: sticky; top: 1.5rem; align-self: start; }
}

.ns-widget {
    background: var(--white);
    border: 1px solid var(--border);
    border-radius: var(--radius-lg);
    padding: 1.5rem;
    box-shadow: var(--shadow-sm);
}

.ns-widget-title {
    display: flex;
    align-items: center;
    gap: 9px;
    font-family: var(--font-display);
    font-size: 1.15rem;
    font-weight: 700;
    color: var(--ink);
    margin-bottom: 1.125rem;
    padding-bottom: 0.75rem;
    border-bottom: 1px solid var(--border);
}
.ns-widget-icon {
    width: 30px; height: 30px;
    background: var(--purple-pale);
    color: var(--purple);
    border-radius: var(--radius-sm);
    display: flex; align-items: center; justify-content: center;
    font-size: 0.75rem;
    flex-shrink: 0;
}

/* Author widget */
.ns-author {
    display: flex;
    flex-direction: column;
    align-items: center;
    text-align: center;
    gap: 0.5rem;
}
.ns-author-avatar {
    width: 68px; height: 68px;
    border-radius: 50%;
    background: linear-gradient(145deg, var(--purple), var(--purple-mid));
    color: var(--white);
    font-family: var(--font-display);
    font-size: 1.75rem;
    font-weight: 700;
    display: flex; align-items: center; justify-content: center;
    box-shadow: 0 6px 20px rgba(124,111,171,0.25);
    border: 3px solid var(--white);
    margin-bottom: 0.25rem;
}
.ns-author-name {
    font-family: var(--font-display);
    font-size: 1.1rem;
    font-weight: 700;
    color: var(--ink);
}
.ns-author-role {
    font-family: var(--font-mono);
    font-size: 0.62rem;
    letter-spacing: 0.12em;
    text-transform: uppercase;
    color: var(--gold);
}
.ns-author-bio {
    font-size: 0.82rem;
    color: var(--slate);
    line-height: 1.6;
    margin-top: 0.25rem;
}

/* Popular list */
.ns-popular-list { list-style: none; display: flex; flex-direction: column; }
.ns-popular-item {
    display: flex;
    gap: 0.75rem;
    padding: 0.75rem 0;
    border-bottom: 1px solid var(--border);
}
.ns-popular-item:first-child { padding-top: 0; }
.ns-popular-item:last-child  { border-bottom: none; padding-bottom: 0; }
.ns-popular-thumb {
    width: 64px; height: 52px;
    flex-shrink: 0;
    border-radius: var(--radius-sm);
    overflow: hidden;
    background: var(--surface);
}
.ns-popular-thumb img { width:100%; height:100%; object-fit:cover; display:block; }
.ns-popular-body { flex:1; min-width:0; }
.ns-popular-title {
    font-family: var(--font-display);
    font-size: 0.9rem;
    font-weight: 700;
    line-height: 1.35;
    color: var(--ink-soft);
    margin-bottom: 4px;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
.ns-popular-title a { color: inherit; text-decoration: none; transition: color 0.2s; }
.ns-popular-title a:hover { color: var(--purple); }
.ns-popular-date {
    font-family: var(--font-mono);
    font-size: 0.62rem;
    color: var(--mist);
    display: flex; align-items: center; gap: 4px;
}

/* Newsletter widget */
.ns-widget--nl {
    background: linear-gradient(155deg, #1C2333 40%, #221F3A 100%);
    border-color: rgba(124,111,171,0.2);
    position: relative;
    overflow: hidden;
}
.ns-widget--nl::before {
    content: '';
    position: absolute;
    bottom: -50px; right: -50px;
    width: 180px; height: 180px;
    border-radius: 50%;
    background: radial-gradient(circle, rgba(124,111,171,0.18) 0%, transparent 70%);
    pointer-events: none;
}
.ns-widget--nl .ns-widget-title { color: var(--white); border-color: rgba(255,255,255,0.08); }
.ns-widget--nl .ns-widget-icon  { background: rgba(124,111,171,0.22); color: var(--gold-light); }

.ns-nl-desc { font-size: 0.82rem; color: rgba(255,255,255,0.6); line-height: 1.65; margin-bottom: 1rem; position:relative; z-index:1; }

.ns-nl-form { display:flex; flex-direction:column; gap:0.75rem; position:relative; z-index:1; }

.ns-nl-input-wrap { position: relative; }
.ns-nl-icon {
    position: absolute; left: 0.875rem; top:50%; transform:translateY(-50%);
    color: var(--mist); font-size: 0.78rem; pointer-events:none; z-index:2;
}
.ns-nl-input {
    width: 100%;
    height: 44px;
    padding: 0 1rem 0 2.5rem;
    border: 1.5px solid rgba(255,255,255,0.1);
    border-radius: var(--radius-sm);
    background: rgba(255,255,255,0.06);
    color: var(--white);
    font-family: var(--font-body);
    font-size: 0.875rem;
    caret-color: var(--gold-light);
    outline: none;
    transition: border-color 0.2s, background 0.2s;
}
.ns-nl-input::placeholder { color: rgba(255,255,255,0.28); }
.ns-nl-input:focus {
    border-color: var(--gold-light);
    background: rgba(255,255,255,0.09);
    box-shadow: 0 0 0 3px rgba(212,165,32,0.12);
}
.ns-nl-btn {
    width: 100%; height: 42px;
    background: var(--purple);
    color: var(--white);
    border: none;
    border-radius: var(--radius-sm);
    font-family: var(--font-body);
    font-size: 0.875rem;
    font-weight: 600;
    cursor: pointer;
    display: flex; align-items: center; justify-content: center; gap: 7px;
    transition: background 0.2s, transform 0.2s;
    letter-spacing: 0.02em;
}
.ns-nl-btn:hover:not(:disabled) { background: var(--purple-dark); transform: translateY(-1px); }
.ns-nl-btn:disabled { opacity: 0.6; cursor: not-allowed; }
.ns-nl-disclaimer { font-size: 0.65rem; color: rgba(255,255,255,0.3); line-height: 1.5; }
#ns-nl-msg {
    display: none;
    padding: 0.625rem 0.875rem;
    border-radius: var(--radius-sm);
    font-size: 0.8rem;
    font-weight: 500;
    position: relative; z-index: 1;
}

/* ═══════════════════════════════════════════════════════════
   RELATED ARTICLES
═══════════════════════════════════════════════════════════ */
.ns-related {
    margin-top: clamp(2.5rem, 5vw, 4rem);
    padding-top: 2rem;
    border-top: 1px solid var(--border);
}
.ns-related-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 1.5rem;
    flex-wrap: wrap;
    gap: 0.75rem;
}
.ns-related-title {
    font-family: var(--font-display);
    font-size: clamp(1.5rem, 3vw, 2rem);
    font-weight: 700;
    color: var(--ink);
    display: flex; align-items: center; gap: 10px;
}
.ns-related-pip { width: 8px; height: 8px; border-radius: 50%; background: var(--purple); flex-shrink: 0; }

.ns-related-grid {
    display: grid;
    grid-template-columns: 1fr;
    gap: 1.25rem;
}
@media (min-width: 600px)  { .ns-related-grid { grid-template-columns: repeat(2, 1fr); } }
@media (min-width: 1024px) { .ns-related-grid { grid-template-columns: repeat(3, 1fr); } }

.ns-related-card {
    background: var(--white);
    border: 1px solid var(--border);
    border-radius: var(--radius-lg);
    overflow: hidden;
    transition: transform 0.28s ease, box-shadow 0.28s ease, border-color 0.28s ease;
    position: relative;
}
.ns-related-card::before {
    content: '';
    position: absolute;
    left: 0; top: 0; bottom: 0;
    width: 3px;
    background: var(--purple);
    transform: scaleY(0);
    transform-origin: center;
    transition: transform 0.28s ease;
    border-radius: 3px 0 0 3px;
    z-index: 1;
}
.ns-related-card:hover { transform: translateY(-3px); box-shadow: var(--shadow-lg); border-color: rgba(124,111,171,0.25); }
.ns-related-card:hover::before { transform: scaleY(1); }

.ns-related-img-wrap {
    position: relative;
    padding-top: 58%;
    overflow: hidden;
    background: var(--surface);
}
.ns-related-img {
    position: absolute; inset: 0;
    width: 100%; height: 100%;
    object-fit: cover;
    display: block;
    transition: transform 0.5s ease;
}
.ns-related-card:hover .ns-related-img { transform: scale(1.05); }
.ns-related-img-placeholder {
    position: absolute; inset: 0;
    display: flex; align-items: center; justify-content: center;
    background: linear-gradient(135deg, var(--surface), var(--border));
    color: var(--mist); font-size: 2.5rem;
}

.ns-related-cat {
    position: absolute; top: 0.75rem; left: 0.75rem;
    background: var(--purple);
    color: var(--white);
    font-family: var(--font-mono);
    font-size: 0.58rem;
    letter-spacing: 0.12em;
    text-transform: uppercase;
    padding: 3px 9px;
    border-radius: 4px;
    z-index: 2;
}

.ns-related-body { padding: 1.25rem 1.5rem; }
.ns-related-card-title {
    font-family: var(--font-display);
    font-size: clamp(1rem, 2vw, 1.2rem);
    font-weight: 700;
    line-height: 1.3;
    color: var(--ink);
    margin-bottom: 0.75rem;
}
.ns-related-card-title a { color: inherit; text-decoration: none; transition: color 0.2s; }
.ns-related-card-title a:hover { color: var(--purple); }
.ns-related-card-meta {
    display: flex; align-items: center; gap: 0.875rem; flex-wrap: wrap;
    font-family: var(--font-mono); font-size: 0.62rem; color: var(--mist);
}
.ns-related-card-meta i { color: var(--slate); font-size: 0.58rem; }

/* ═══════════════════════════════════════════════════════════
   CTA BANNER - FIXED FOR CENTERING
═══════════════════════════════════════════════════════════ */
.ns-cta {
    margin-top: clamp(2.5rem, 5vw, 4rem);
    padding: clamp(2.5rem, 6vw, 4rem) clamp(1.5rem, 5vw, 3rem);
    background: linear-gradient(145deg, #16152A 0%, #1A1B30 35%, var(--ink-mid) 100%);
    border-radius: var(--radius-xl);
    text-align: center;
    position: relative;
    overflow: hidden;
    box-shadow: var(--shadow-xl);
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
}
.ns-cta::before {
    content: '';
    position: absolute;
    inset: 0;
    background-image: repeating-linear-gradient(-55deg, transparent, transparent 40px, rgba(255,255,255,0.015) 40px, rgba(255,255,255,0.015) 41px);
    pointer-events: none;
}
.ns-cta-eyebrow {
    font-family: var(--font-mono);
    font-size: 0.75rem;
    font-weight: 500;
    letter-spacing: 0.25em;
    text-transform: uppercase;
    color: var(--gold-light);
    margin-bottom: 0.75rem;
    position: relative; 
    z-index: 1;
    text-align: center;
    width: 100%;
}
.ns-cta-title {
    font-family: var(--font-display);
    font-size: clamp(2rem, 5vw, 3rem);
    font-weight: 700;
    color: var(--white);
    line-height: 1.1;
    margin-bottom: 0.875rem;
    position: relative; 
    z-index: 1;
    text-align: center;
    width: 100%;
}
.ns-cta-desc {
    font-size: clamp(1rem, 2vw, 1.2rem);
    color: rgba(255,255,255,0.75);
    max-width: 600px;
    margin: 0 auto 2rem;
    line-height: 1.7;
    position: relative; 
    z-index: 1;
    text-align: center;
    width: 100%;
}
.ns-cta-btns {
    display: flex;
    justify-content: center;
    gap: 1rem;
    flex-wrap: wrap;
    position: relative; 
    z-index: 1;
    width: 100%;
}
.ns-cta-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    padding: 0.875rem 2rem;
    border-radius: var(--radius-sm);
    font-family: var(--font-body);
    font-size: 1rem;
    font-weight: 600;
    text-decoration: none;
    border: none;
    cursor: pointer;
    transition: all 0.22s ease;
    letter-spacing: 0.02em;
    min-width: 160px;
}
.ns-cta-btn--purple { 
    background: var(--purple); 
    color: var(--white); 
}
.ns-cta-btn--purple:hover { 
    background: var(--purple-dark); 
    color: var(--white); 
    transform: translateY(-2px); 
    box-shadow: 0 8px 20px rgba(124,111,171,0.4); 
}
.ns-cta-btn--ghost  { 
    background: transparent; 
    color: var(--white); 
    border: 2px solid rgba(255,255,255,0.35); 
}
.ns-cta-btn--ghost:hover  { 
    border-color: var(--white); 
    background: rgba(255,255,255,0.08); 
    color: var(--white);
    transform: translateY(-2px);
}

/* ── Back to top ── */
.ns-back-top {
    position: fixed;
    bottom: 1.5rem; right: 1.5rem;
    width: 44px; height: 44px;
    background: var(--purple);
    color: var(--white);
    border: none;
    border-radius: 50%;
    display: none;
    align-items: center; justify-content: center;
    cursor: pointer;
    box-shadow: 0 6px 20px rgba(124,111,171,0.3);
    transition: all 0.2s;
    z-index: 99;
    font-size: 1rem;
}
.ns-back-top:hover { background: var(--purple-dark); transform: translateY(-3px); }

/* ── Animations ── */
@keyframes ns-fadeUp {
    from { opacity:0; transform: translateY(14px); }
    to   { opacity:1; transform: translateY(0); }
}
.ns-article-card { animation: ns-fadeUp 0.45s ease both; }
.ns-widget       { animation: ns-fadeUp 0.45s ease both; }
.ns-widget:nth-child(2) { animation-delay: 0.08s; }
.ns-widget:nth-child(3) { animation-delay: 0.16s; }

@media (prefers-reduced-motion: reduce) {
    .ns-article-card, .ns-widget, .ns-hero-img { animation: none !important; transition: none !important; }
}

/* ── Overflow guard ── */
.ns-root img, .ns-root video, .ns-root iframe { max-width: 100%; height: auto; }
</style>

<!-- ═══════════════════════════════════════════════════════════
     PAGE ROOT
═══════════════════════════════════════════════════════════ -->
<div class="ns-root">

<!-- ── BREADCRUMB ── -->
<nav class="ns-breadcrumb" aria-label="Breadcrumb">
    <div class="ns-container">
        <ol class="ns-breadcrumb-list">
            <?php foreach ($breadcrumb as $item): ?>
            <li>
                <?php if (!empty($item['url'])): ?>
                    <a href="<?php echo $item['url']; ?>"><?php echo $item['label']; ?></a>
                    <span class="ns-bc-sep">/</span>
                <?php else: ?>
                    <span class="ns-bc-current"><?php echo $item['label']; ?></span>
                <?php endif; ?>
            </li>
            <?php endforeach; ?>
        </ol>
    </div>
</nav>

<!-- ════════════════════════════════════════════════
     ARTICLE HERO  (image-left / text-right card)
════════════════════════════════════════════════ -->
<div class="ns-hero-wrap">
    <div class="ns-container">
        <div class="ns-hero-card">

            <!-- Image cell -->
            <div class="ns-hero-img-cell">
                <?php if (!empty($heroImgSrc)): ?>
                <img src="<?php echo $heroImgSrc; ?>"
                     alt="<?php echo htmlspecialchars($news['title'] ?? ''); ?>"
                     class="ns-hero-img"
                     loading="eager"
                     onerror="this.onerror=null; this.style.opacity='0.2';">
                <?php else: ?>
                <div class="ns-hero-img-placeholder">
                    <i class="fas fa-newspaper" aria-hidden="true"></i>
                </div>
                <?php endif; ?>
            </div>

            <!-- Text cell -->
            <div class="ns-hero-text">
                <?php if (!empty($news['category'])): ?>
                <span class="ns-hero-tag">
                    <i class="fas fa-folder" aria-hidden="true"></i>
                    <?php echo htmlspecialchars($news['category']); ?>
                </span>
                <?php endif; ?>

                <h1 class="ns-hero-title">
                    <?php echo htmlspecialchars($news['title'] ?? 'News Article'); ?>
                </h1>

                <?php if (!empty($news['excerpt'])): ?>
                <p class="ns-hero-excerpt">
                    <?php echo htmlspecialchars($news['excerpt']); ?>
                </p>
                <?php endif; ?>

                <div class="ns-hero-meta">
                    <?php if ($newsDate): ?>
                    <span class="ns-hero-meta-item">
                        <i class="far fa-calendar-alt" aria-hidden="true"></i>
                        <time datetime="<?php echo $newsDateIso; ?>"><?php echo $newsDate; ?></time>
                    </span>
                    <?php endif; ?>
                    <span class="ns-hero-meta-item">
                        <i class="far fa-clock" aria-hidden="true"></i>
                        <?php echo $readingTime; ?> min read
                    </span>
                    <?php if (!empty($news['views_count'])): ?>
                    <span class="ns-hero-meta-item">
                        <i class="far fa-eye" aria-hidden="true"></i>
                        <?php echo number_format($news['views_count']); ?> views
                    </span>
                    <?php endif; ?>
                </div>
            </div>

        </div>
    </div>

    <!-- Caption / author + share strip -->
    <div class="ns-hero-caption">
        <div class="ns-hero-author">
            <div class="ns-hero-avatar"><?php echo $authorInitial; ?></div>
            <div class="ns-hero-author-text">
                <span class="ns-hero-author-name"><?php echo htmlspecialchars($authorName); ?></span>
                <span class="ns-hero-author-role"><?php echo htmlspecialchars($authorRole); ?></span>
            </div>
        </div>

        <div class="ns-share-mini">
            <span class="ns-share-label">Share</span>
            <?php
            $articleUrl  = urlencode($baseUrl . '/news/' . ($news['slug'] ?? ''));
            $articleTitle = urlencode($news['title'] ?? '');
            ?>
            <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo $articleUrl; ?>" class="ns-share-btn fb" target="_blank" rel="noopener" aria-label="Facebook"><i class="fab fa-facebook-f"></i></a>
            <a href="https://twitter.com/intent/tweet?url=<?php echo $articleUrl; ?>&text=<?php echo $articleTitle; ?>" class="ns-share-btn tw" target="_blank" rel="noopener" aria-label="Twitter"><i class="fab fa-twitter"></i></a>
            <a href="https://www.linkedin.com/shareArticle?mini=true&url=<?php echo $articleUrl; ?>" class="ns-share-btn li" target="_blank" rel="noopener" aria-label="LinkedIn"><i class="fab fa-linkedin-in"></i></a>
            <a href="https://wa.me/?text=<?php echo $articleTitle . ' ' . $articleUrl; ?>" class="ns-share-btn wa" target="_blank" rel="noopener" aria-label="WhatsApp"><i class="fab fa-whatsapp"></i></a>
            <a href="mailto:?subject=<?php echo $articleTitle; ?>&body=<?php echo $articleUrl; ?>" class="ns-share-btn em" aria-label="Email"><i class="far fa-envelope"></i></a>
        </div>
    </div>
</div>
<!-- /ns-hero-wrap -->

<!-- ════════════════════════════════════════════════
     BODY: ARTICLE CONTENT + SIDEBAR
════════════════════════════════════════════════ -->
<div class="ns-body">
    <div class="ns-container">
        <div class="ns-layout">

            <!-- MAIN ARTICLE -->
            <main id="main-content">

                <article class="ns-article-card">
                    <div class="ns-article-body">
                        <div class="ns-article-content">
                            <?php if (!empty($news['content'])): ?>
                                <?php echo $news['content']; ?>
                            <?php else: ?>
                                <p>We are preparing this article. Please check back soon for the latest updates from FCT College of Nursing Sciences.</p>
                            <?php endif; ?>
                        </div>
                    </div>

                    <footer class="ns-article-footer">

                        <!-- Topics hub -->
                        <?php if (!empty($displayTopics)): ?>
                        <div class="ns-topics">
                            <div class="ns-topics-header">
                                <h2 class="ns-topics-title">
                                    <span class="ns-topics-title-pip"></span>
                                    Topics
                                </h2>
                                <a href="<?php echo $baseUrl; ?>/news/topics" class="ns-topics-link">
                                    All topics <i class="fas fa-arrow-right" aria-hidden="true"></i>
                                </a>
                            </div>
                            <div class="ns-topics-grid">
                                <?php foreach ($displayTopics as $topic): ?>
                                <a href="<?php echo $baseUrl; ?>/news?topic=<?php echo urlencode($topic['slug']); ?>"
                                   class="ns-topic-card">
                                    <span class="ns-topic-icon"
                                          style="background:<?php echo $topic['bg']; ?>; color:<?php echo $topic['color']; ?>;">
                                        <i class="<?php echo $topic['icon']; ?>" aria-hidden="true"></i>
                                    </span>
                                    <span class="ns-topic-body">
                                        <span class="ns-topic-name">
                                            <span class="ns-topic-title"><?php echo htmlspecialchars($topic['display_name']); ?></span>
                                            <span class="ns-topic-count"><?php echo $topic['count']; ?> article<?php echo $topic['count'] != 1 ? 's' : ''; ?></span>
                                        </span>
                                        <span class="ns-topic-desc"><?php echo $topic['description']; ?></span>
                                    </span>
                                </a>
                                <?php endforeach; ?>
                            </div>
                        </div>
                        <?php endif; ?>

                        <!-- Share row -->
                        <div class="ns-share-row">
                            <span class="ns-share-row-label">
                                <i class="fas fa-share-nodes" aria-hidden="true"></i>
                                Share this article
                            </span>
                            <div class="ns-share-row-btns">
                                <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo $articleUrl; ?>" class="ns-share-row-btn fb" target="_blank" rel="noopener" aria-label="Facebook"><i class="fab fa-facebook-f"></i></a>
                                <a href="https://twitter.com/intent/tweet?url=<?php echo $articleUrl; ?>&text=<?php echo $articleTitle; ?>" class="ns-share-row-btn tw" target="_blank" rel="noopener" aria-label="Twitter"><i class="fab fa-twitter"></i></a>
                                <a href="https://www.linkedin.com/shareArticle?mini=true&url=<?php echo $articleUrl; ?>" class="ns-share-row-btn li" target="_blank" rel="noopener" aria-label="LinkedIn"><i class="fab fa-linkedin-in"></i></a>
                                <a href="https://wa.me/?text=<?php echo $articleTitle . ' ' . $articleUrl; ?>" class="ns-share-row-btn wa" target="_blank" rel="noopener" aria-label="WhatsApp"><i class="fab fa-whatsapp"></i></a>
                                <a href="mailto:?subject=<?php echo $articleTitle; ?>&body=<?php echo $articleUrl; ?>" class="ns-share-row-btn em" aria-label="Email"><i class="far fa-envelope"></i></a>
                            </div>
                        </div>

                        <!-- Back to news -->
                        <a href="<?php echo $baseUrl; ?>/news" class="ns-back">
                            <i class="fas fa-arrow-left" aria-hidden="true"></i> All News
                        </a>

                    </footer>
                </article>

                <!-- Related articles -->
                <?php if (!empty($relatedNews)): ?>
                <section class="ns-related">
                    <div class="ns-related-header">
                        <h2 class="ns-related-title">
                            <span class="ns-related-pip"></span>
                            Related Articles
                        </h2>
                    </div>
                    <div class="ns-related-grid">
                        <?php foreach ($relatedNews as $rel): ?>
                        <article class="ns-related-card">
                            <div class="ns-related-img-wrap">
                                <?php if (!empty($rel['featured_image'])): ?>
                                <img src="<?php echo $baseUrl . htmlspecialchars($rel['featured_image']); ?>"
                                     alt=""
                                     class="ns-related-img"
                                     loading="lazy"
                                     onerror="this.style.display='none'">
                                <?php else: ?>
                                <div class="ns-related-img-placeholder">
                                    <i class="fas fa-newspaper" aria-hidden="true"></i>
                                </div>
                                <?php endif; ?>
                                <?php if (!empty($rel['category'])): ?>
                                <span class="ns-related-cat"><?php echo htmlspecialchars($rel['category']); ?></span>
                                <?php endif; ?>
                            </div>
                            <div class="ns-related-body">
                                <h3 class="ns-related-card-title">
                                    <a href="<?php echo $baseUrl; ?>/news/<?php echo htmlspecialchars($rel['slug'] ?? ''); ?>">
                                        <?php echo htmlspecialchars($rel['title'] ?? ''); ?>
                                    </a>
                                </h3>
                                <div class="ns-related-card-meta">
                                    <span><i class="far fa-calendar-alt" aria-hidden="true"></i> <?php echo date('M j, Y', strtotime($rel['created_at'] ?? 'now')); ?></span>
                                    <span><i class="far fa-clock" aria-hidden="true"></i> <?php echo max(1, ceil(str_word_count(strip_tags($rel['content'] ?? ''))/200)); ?> min</span>
                                </div>
                            </div>
                        </article>
                        <?php endforeach; ?>
                    </div>
                </section>
                <?php endif; ?>

                <!-- CTA banner - NOW PROPERLY CENTERED -->
                <section class="ns-cta" aria-label="Stay connected">
                    <p class="ns-cta-eyebrow">FCT College of Nursing Sciences</p>
                    <h2 class="ns-cta-title">Stay Connected</h2>
                    <p class="ns-cta-desc">Get the latest news and updates delivered directly to your inbox.</p>
                    <div class="ns-cta-btns">
                        <a href="<?php echo $baseUrl; ?>/news" class="ns-cta-btn ns-cta-btn--purple">
                            <i class="fas fa-newspaper" aria-hidden="true"></i> All News
                        </a>
                        <a href="<?php echo $baseUrl; ?>/contact" class="ns-cta-btn ns-cta-btn--ghost">
                            <i class="fas fa-envelope" aria-hidden="true"></i> Contact Us
                        </a>
                    </div>
                </section>

            </main>

            <!-- SIDEBAR -->
            <aside class="ns-sidebar" aria-label="Article sidebar">

                <!-- Author -->
                <div class="ns-widget">
                    <h2 class="ns-widget-title">
                        <span class="ns-widget-icon"><i class="fas fa-user" aria-hidden="true"></i></span>
                        Author
                    </h2>
                    <div class="ns-author">
                        <div class="ns-author-avatar"><?php echo $authorInitial; ?></div>
                        <div class="ns-author-name"><?php echo htmlspecialchars($authorName); ?></div>
                        <div class="ns-author-role"><?php echo htmlspecialchars($authorRole); ?></div>
                        <p class="ns-author-bio">
                            <?php echo ($authorRole === 'Communications')
                                ? 'Official news and announcements from FCT College of Nursing Sciences.'
                                : 'Contributor to FCT College news and publications.'; ?>
                        </p>
                    </div>
                </div>

                <!-- Popular -->
                <?php if (!empty($popularNews)): ?>
                <div class="ns-widget">
                    <h2 class="ns-widget-title">
                        <span class="ns-widget-icon"><i class="fas fa-fire" aria-hidden="true"></i></span>
                        Popular
                    </h2>
                    <ul class="ns-popular-list">
                        <?php $i = 0; foreach ($popularNews as $pop): if (++$i > 5) break; ?>
                        <li class="ns-popular-item">
                            <?php
                            $popImg = '';
                            if (!empty($pop['featured_image'])) {
                                $pp = $pop['featured_image'];
                                $popImg = (strpos($pp,'http')===0||strpos($pp,'//')===0)
                                    ? htmlspecialchars($pp)
                                    : $baseUrl . (strpos($pp,'/uploads/')===0 ? htmlspecialchars($pp) : '/uploads/news/' . htmlspecialchars($pp));
                            }
                            ?>
                            <div class="ns-popular-thumb">
                                <?php if ($popImg): ?>
                                <img src="<?php echo $popImg; ?>" alt="" loading="lazy" onerror="this.style.display='none'">
                                <?php endif; ?>
                            </div>
                            <div class="ns-popular-body">
                                <h3 class="ns-popular-title">
                                    <a href="<?php echo $baseUrl; ?>/news/<?php echo htmlspecialchars($pop['slug'] ?? ''); ?>">
                                        <?php echo htmlspecialchars($pop['title'] ?? ''); ?>
                                    </a>
                                </h3>
                                <span class="ns-popular-date">
                                    <i class="far fa-calendar" aria-hidden="true"></i>
                                    <?php echo date('M j, Y', strtotime($pop['created_at'] ?? 'now')); ?>
                                </span>
                            </div>
                        </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
                <?php endif; ?>

                <!-- Newsletter -->
                <div class="ns-widget ns-widget--nl">
                    <h2 class="ns-widget-title">
                        <span class="ns-widget-icon"><i class="fas fa-envelope" aria-hidden="true"></i></span>
                        Newsletter
                    </h2>
                    <p class="ns-nl-desc">Subscribe for the latest news and updates from FCT College.</p>
                    <div id="ns-nl-msg" role="alert"></div>
                    <form class="ns-nl-form" id="ns-nl-form" novalidate>
                        <div class="ns-nl-input-wrap">
                            <i class="fas fa-envelope ns-nl-icon" aria-hidden="true"></i>
                            <input type="email"
                                   id="ns-nl-email"
                                   name="email"
                                   class="ns-nl-input"
                                   placeholder="your@email.com"
                                   required
                                   aria-label="Email address for newsletter">
                        </div>
                        <button type="submit" class="ns-nl-btn" id="ns-nl-submit">
                            <i class="fas fa-paper-plane" aria-hidden="true"></i> Subscribe
                        </button>
                        <p class="ns-nl-disclaimer">No spam. Unsubscribe any time.</p>
                    </form>
                </div>

            </aside>
        </div>
    </div>
</div>

</div><!-- /ns-root -->

<!-- Back to top -->
<button class="ns-back-top" id="ns-back-top" aria-label="Back to top">
    <i class="fas fa-chevron-up" aria-hidden="true"></i>
</button>

<script>
(function () {
    'use strict';

    /* ── Newsletter ── */
    var form  = document.getElementById('ns-nl-form');
    var email = document.getElementById('ns-nl-email');
    var btn   = document.getElementById('ns-nl-submit');
    var msg   = document.getElementById('ns-nl-msg');

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
            btn.innerHTML  = '<i class="fas fa-spinner fa-spin"></i> Subscribing…';

            var fd = new FormData();
            fd.append('email', val);
            fd.append('source', 'news_article_sidebar');

            fetch('<?php echo $baseUrl; ?>/newsletter/subscribe', { method: 'POST', body: fd })
                .then(function (r) { return r.json(); })
                .then(function (d) {
                    showMsg(d.message || (d.success ? 'Subscribed!' : 'Something went wrong.'), d.success ? 'success' : 'error');
                    if (d.success) email.value = '';
                })
                .catch(function () { showMsg('Connection error. Please try again.', 'error'); })
                .finally(function () {
                    email.disabled = false;
                    btn.disabled   = false;
                    btn.innerHTML  = '<i class="fas fa-paper-plane"></i> Subscribe';
                });
        });
    }

    function showMsg(text, type) {
        msg.style.display    = 'block';
        msg.textContent      = text;
        msg.style.background = type === 'success' ? 'rgba(5,150,105,0.15)'  : 'rgba(220,38,38,0.15)';
        msg.style.color      = type === 'success' ? '#D1FAE5' : '#FEE2E2';
        msg.style.border     = type === 'success' ? '1px solid rgba(5,150,105,0.3)' : '1px solid rgba(220,38,38,0.3)';
        if (type === 'success') setTimeout(function () { msg.style.display = 'none'; }, 5000);
    }

    /* ── Back to top ── */
    var topBtn = document.getElementById('ns-back-top');
    if (topBtn) {
        window.addEventListener('scroll', function () {
            topBtn.style.display = window.scrollY > 500 ? 'flex' : 'none';
        });
        topBtn.addEventListener('click', function () {
            window.scrollTo({ top: 0, behavior: 'smooth' });
        });
    }
})();
</script>