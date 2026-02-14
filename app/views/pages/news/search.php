<?php
/**
 * News Search Results Page - REDESIGNED & FIXED
 * File: /app/views/pages/news/search.php
 * 
 * FIXES APPLIED:
 * 1. Added proper variable handling for search query
 * 2. Improved empty state messaging
 * 3. Added debug information (can be removed after testing)
 * 4. Ensured consistent variable naming
 */

// Ensure required variables exist
$baseUrl       = $baseUrl       ?? (defined('BASE_URL') ? BASE_URL : '');
$news          = $news          ?? [];
$searchQuery   = $searchQuery   ?? ($_GET['q'] ?? '');
$categories    = $categories    ?? [];
$archiveMonths = $archiveMonths ?? [];
$popularNews   = $popularNews   ?? [];
$pagination    = $pagination    ?? ['current' => 1, 'total' => 0, 'limit' => 10, 'totalCount' => 0];
$pageTitle     = $pageTitle     ?? 'Search Results - FCT College of Nursing Sciences';
$pageDescription = $pageDescription ?? 'Search results for news articles';

// Helper for image URLs
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

// Calculate hasNews
$hasNews = !empty($news);

// Debug mode - set to false in production
$debug_mode = false; // Set to true to see debug info
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($pageTitle); ?></title>
    <meta name="description" content="<?php echo htmlspecialchars($pageDescription); ?>">

    <!-- Google Fonts (same as index/category) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@400;500;600;700&family=Outfit:wght@300;400;500;600;700&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- EMERGENCY FULL WIDTH OVERRIDE (exactly as index) -->
    <style>
        body .main-content, body {
            padding: 0 !important;
            margin: 0 !important;
            max-width: 100vw !important;
            overflow-x: hidden;
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

    <!-- ========== FULL DESIGN TOKENS & STYLES (identical to index/category) ========== -->
    <style>
        /* ═══════════════════════════════════════════════════
           DESIGN TOKENS
        ════════════════════════════════════════════════════ */
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
           SCOPED RESET
        ════════════════════════════════════════════════════ */
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
        ════════════════════════════════════════════════════ */
        .np-container {
            width: 100%;
            max-width: 1320px;
            margin: 0 auto;
            padding-left:  clamp(1rem, 4vw, 2.5rem);
            padding-right: clamp(1rem, 4vw, 2.5rem);
        }

        /* ═══════════════════════════════════════════════════
           HERO SECTION (full width)
        ════════════════════════════════════════════════════ */
        .np-hero {
            position: relative;
            background: linear-gradient(145deg, #16152A 0%, #1A1B30 35%, var(--ink-mid) 100%);
            overflow: hidden;
            padding: clamp(3rem, 7vw, 5rem) 0 clamp(2.5rem, 5vw, 4rem);
        }

        .np-hero::before {
            content: '';
            position: absolute;
            inset: 0;
            background-image: repeating-linear-gradient(-55deg, transparent, transparent 40px, rgba(255,255,255,0.018) 40px, rgba(255,255,255,0.018) 41px);
            z-index: 1;
            pointer-events: none;
        }

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

        .np-hero-inner {
            position: relative;
            z-index: 2;
            display: flex;
            flex-direction: column;
            align-items: flex-start;
            max-width: 780px;
        }

        .np-hero-left  { width: 100%; }
        .np-hero-right { width: 100%; margin-top: 2rem; }

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

        .np-hero-subtitle {
            font-size: clamp(0.95rem, 1.8vw, 1.1rem);
            color: rgba(255,255,255,0.68);
            font-weight: 300;
            max-width: 560px;
            line-height: 1.7;
            margin-bottom: 2rem;
        }

        /* Search Form (hero version) */
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

        /* Stats Row */
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

        /* Breadcrumb inside hero (small, subtle) */
        .np-hero-breadcrumb {
            margin-bottom: 1.5rem;
            font-size: 0.82rem;
            display: flex;
            flex-wrap: wrap;
            gap: 0.4rem;
            color: rgba(255,255,255,0.55);
        }
        .np-hero-breadcrumb a {
            color: var(--gold-light);
            text-decoration: none;
            font-weight: 500;
        }
        .np-hero-breadcrumb a:hover { text-decoration: underline; }
        .np-hero-breadcrumb-sep { color: rgba(255,255,255,0.3); margin: 0 0.25rem; }

        /* ========== BUTTONS ========== */
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

        /* ========== RESULTS INFO ========== */
        .np-results-bar {
            background: var(--purple-pale);
            border-left: 4px solid var(--purple);
            padding: 1rem 1.5rem;
            border-radius: var(--radius-md);
            margin-bottom: 2rem;
            display: flex;
            align-items: center;
            gap: 1rem;
            flex-wrap: wrap;
        }
        .np-results-icon {
            color: var(--purple);
            font-size: 1.2rem;
        }
        .np-results-text {
            flex: 1;
        }
        .np-results-count {
            font-weight: 600;
            color: var(--purple-dark);
            font-size: 1.1rem;
        }
        .np-results-query {
            background: var(--white);
            padding: 0.25rem 1rem;
            border-radius: 40px;
            font-family: var(--font-mono);
            font-size: 0.85rem;
            color: var(--ink);
            border: 1px solid var(--border);
        }

        /* ========== NEWS CARDS (horizontal, same as index/category) ========== */
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

        .np-card-img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.5s ease;
            display: block;
        }

        .np-card:hover .np-card-img { transform: scale(1.06); }

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

        /* ========== SIDEBAR WIDGETS ========== */
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

        /* Search tips list */
        .np-tips-list {
            list-style: none;
        }
        .np-tips-list li {
            padding: 0.5rem 0;
            border-bottom: 1px dashed var(--border);
            display: flex;
            align-items: center;
            gap: 0.75rem;
            color: var(--slate);
        }
        .np-tips-list li::before {
            content: '✓';
            color: var(--purple);
            font-weight: 600;
        }

        /* ========== PAGINATION ========== */
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

        /* ========== EMPTY STATE ========== */
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
        .np-empty-title { font-family: var(--font-display); font-size: 1.5rem; font-weight: 700; color: var(--ink-soft); margin-bottom: 0.5rem; }
        .np-empty-desc { color: var(--slate); margin-bottom: 1.5rem; }

        /* Responsive */
        @media (max-width: 640px) {
            .np-search-form { flex-direction: column; border-radius: var(--radius-md); }
            .np-search-wrap,
            .np-search-input { border-radius: var(--radius-sm) var(--radius-sm) 0 0; border-right: none; }
            .np-search-btn { border-radius: 0 0 var(--radius-sm) var(--radius-sm); }
        }

        /* Debug styles - only visible when debug mode is on */
        .np-debug {
            background: #f8f9fa;
            border: 2px solid #dc3545;
            border-radius: 8px;
            padding: 1.5rem;
            margin-bottom: 2rem;
            font-family: monospace;
            font-size: 0.9rem;
        }
        .np-debug h3 {
            color: #dc3545;
            margin-top: 0;
            margin-bottom: 1rem;
        }
        .np-debug pre {
            background: #fff;
            padding: 1rem;
            border-radius: 4px;
            overflow-x: auto;
        }
    </style>
</head>
<body class="np-root">
    <!-- The .np-root container ensures all styles are scoped -->

    <!-- ========== DEBUG INFO (only shown when debug_mode = true) ========== -->
    <?php if ($debug_mode): ?>
    <div class="np-container" style="margin-top: 2rem;">
        <div class="np-debug">
            <h3>🔍 DEBUG INFORMATION</h3>
            <p><strong>Search Query:</strong> "<?php echo htmlspecialchars($searchQuery); ?>"</p>
            <p><strong>Results Found:</strong> <?php echo count($news); ?></p>
            <p><strong>Total Results (pagination):</strong> <?php echo $pagination['totalCount'] ?? 0; ?></p>
            <p><strong>Has News:</strong> <?php echo $hasNews ? 'Yes' : 'No'; ?></p>
            
            <?php if (!empty($news)): ?>
                <h4>First Result:</h4>
                <pre><?php print_r($news[0]); ?></pre>
            <?php else: ?>
                <p style="color: #dc3545;">No results found. Check:</p>
                <ul>
                    <li>Is the article published? (is_published = 1)</li>
                    <li>Does the search term match the title, content, or category?</li>
                    <li>Check PHP error logs for database errors</li>
                </ul>
            <?php endif; ?>
        </div>
    </div>
    <?php endif; ?>

    <!-- ========== HERO SECTION (FULL WIDTH, NO GAP) ========== -->
    <section class="np-hero hero-section" aria-label="Search hero">
        <div class="np-hero-bg"></div>
        <div class="np-container np-hero-inner">

            <!-- Breadcrumb (like index/category) -->
            <div class="np-hero-breadcrumb">
                <a href="<?php echo $baseUrl; ?>">Home</a>
                <span class="np-hero-breadcrumb-sep">/</span>
                <a href="<?php echo $baseUrl; ?>/news">News</a>
                <span class="np-hero-breadcrumb-sep">/</span>
                <span style="color:rgba(255,255,255,0.7);">Search</span>
            </div>

            <div class="np-hero-left">
                <div class="np-hero-eyebrow">
                    <span class="np-hero-eyebrow-rule"></span>
                    <span class="np-hero-eyebrow-text">FCT College of Nursing Sciences</span>
                </div>

                <h1 class="np-hero-title">
                    Search <em>Results</em>
                </h1>

                <?php if (!empty($searchQuery)): ?>
                <p class="np-hero-subtitle">
                    Showing results for <strong style="color:var(--gold-light);">"<?php echo htmlspecialchars($searchQuery); ?>"</strong>
                </p>
                <?php else: ?>
                <p class="np-hero-subtitle">Enter keywords to find news and announcements.</p>
                <?php endif; ?>

                <!-- Search Form (hero version) -->
                <form class="np-search-form" action="<?php echo $baseUrl; ?>/news/search" method="GET" role="search">
                    <div class="np-search-wrap">
                        <i class="fas fa-search np-search-icon"></i>
                        <input type="search" name="q" class="np-search-input" placeholder="Search articles, announcements…" aria-label="Search news" value="<?php echo htmlspecialchars($searchQuery); ?>" required>
                    </div>
                    <button type="submit" class="np-search-btn"><i class="fas fa-search"></i> Search</button>
                </form>
            </div>

            <div class="np-hero-right">
                <div class="np-hero-stats">
                    <div class="np-stat">
                        <span class="np-stat-value"><?php echo number_format($pagination['totalCount'] ?? count($news)); ?></span>
                        <span class="np-stat-label">Results</span>
                    </div>
                    <div class="np-stat">
                        <span class="np-stat-value"><?php echo count($categories) ?: '5'; ?></span>
                        <span class="np-stat-label">Categories</span>
                    </div>
                    <div class="np-stat">
                        <span class="np-stat-value"><?php echo date('Y'); ?></span>
                        <span class="np-stat-label">Latest</span>
                    </div>
                </div>
            </div>

        </div>
    </section>

    <!-- ========== MAIN CONTENT (container) ========== -->
    <div class="np-container">

        <?php if (!$hasNews && !empty($searchQuery)): ?>
            <!-- Empty state - with search tips -->
            <div style="padding: 4rem 0;">
                <div class="np-empty">
                    <div class="np-empty-icon"><i class="fas fa-search"></i></div>
                    <h2 class="np-empty-title">No results found</h2>
                    <p class="np-empty-desc">We couldn't find any articles matching "<?php echo htmlspecialchars($searchQuery); ?>".</p>
                    
                    <!-- Search Tips -->
                    <div style="max-width: 400px; margin: 2rem auto; text-align: left; background: white; padding: 1.5rem; border-radius: 8px;">
                        <h4 style="color: var(--purple); margin-bottom: 1rem;">Try these tips:</h4>
                        <ul style="list-style: none; padding: 0;">
                            <li style="margin-bottom: 0.75rem; display: flex; align-items: center; gap: 0.5rem;">
                                <i class="fas fa-check-circle" style="color: var(--purple);"></i>
                                Use more general keywords
                            </li>
                            <li style="margin-bottom: 0.75rem; display: flex; align-items: center; gap: 0.5rem;">
                                <i class="fas fa-check-circle" style="color: var(--purple);"></i>
                                Check your spelling
                            </li>
                            <li style="margin-bottom: 0.75rem; display: flex; align-items: center; gap: 0.5rem;">
                                <i class="fas fa-check-circle" style="color: var(--purple);"></i>
                                Try different words
                            </li>
                            <li style="margin-bottom: 0.75rem; display: flex; align-items: center; gap: 0.5rem;">
                                <i class="fas fa-check-circle" style="color: var(--purple);"></i>
                                Browse by category below
                            </li>
                        </ul>
                    </div>
                    
                    <a href="<?php echo $baseUrl; ?>/news" class="np-btn np-btn--purple"><i class="fas fa-arrow-left"></i> Browse all news</a>
                </div>
            </div>
        <?php elseif (!$hasNews): ?>
            <!-- No query entered -->
            <div style="padding: 4rem 0;">
                <div class="np-empty">
                    <div class="np-empty-icon"><i class="fas fa-search"></i></div>
                    <h2 class="np-empty-title">Enter a search term</h2>
                    <p class="np-empty-desc">Use the search box above to find news articles.</p>
                </div>
            </div>
        <?php else: ?>

            <!-- Results info bar -->
            <div class="np-results-bar">
                <i class="fas fa-info-circle np-results-icon"></i>
                <div class="np-results-text">
                    <span class="np-results-count">
                        <?php 
                        $totalResults = $pagination['totalCount'] ?? count($news);
                        echo $totalResults . ' ' . ($totalResults == 1 ? 'result' : 'results'); 
                        ?> found
                    </span>
                    <?php if (!empty($searchQuery)): ?>
                        <span style="margin-left: 1rem;" class="np-results-query">"<?php echo htmlspecialchars($searchQuery); ?>"</span>
                    <?php endif; ?>
                </div>
                <a href="<?php echo $baseUrl; ?>/news" class="np-btn np-btn--surface">Clear search</a>
            </div>

            <!-- Two-column layout -->
            <div class="np-layout">

                <!-- Main column: search results -->
                <main id="main-content">
                    <div class="np-grid">
                        <?php foreach ($news as $item): ?>
                        <article class="np-card">
                            <div class="np-card-img-wrap">
                                <?php
                                $cardImg = !empty($item['featured_image']) ? getImageUrl($item['featured_image']) : $baseUrl . '/assets/images/news/featured-nursing.jpg';
                                ?>
                                <img src="<?php echo $cardImg; ?>" alt="<?php echo htmlspecialchars($item['title']); ?>" class="np-card-img" loading="lazy" onerror="this.src='<?php echo $baseUrl; ?>/assets/images/news/featured-nursing.jpg';">
                                <?php if (!empty($item['category'])): ?><span class="np-card-cat"><?php echo htmlspecialchars($item['category']); ?></span><?php endif; ?>
                            </div>
                            <div class="np-card-body">
                                <h3 class="np-card-title">
                                    <a href="<?php echo $baseUrl; ?>/news/<?php echo $item['slug']; ?>">
                                        <?php echo htmlspecialchars($item['title']); ?>
                                    </a>
                                </h3>
                                <?php if (!empty($item['excerpt'])): ?>
                                    <p class="np-card-excerpt">
                                        <?php 
                                        $excerpt = strip_tags($item['excerpt']);
                                        echo htmlspecialchars(substr($excerpt, 0, 200) . (strlen($excerpt) > 200 ? '...' : ''));
                                        ?>
                                    </p>
                                <?php endif; ?>
                                <div class="np-card-footer">
                                    <div class="np-card-meta">
                                        <span class="np-card-meta-item">
                                            <i class="far fa-calendar"></i> 
                                            <?php echo date('M d, Y', strtotime($item['created_at'] ?? 'now')); ?>
                                        </span>
                                        <?php if (!empty($item['views_count'])): ?>
                                        <span class="np-card-meta-item">
                                            <i class="far fa-eye"></i> 
                                            <?php echo number_format($item['views_count']); ?>
                                        </span>
                                        <?php endif; ?>
                                    </div>
                                    <a href="<?php echo $baseUrl; ?>/news/<?php echo $item['slug']; ?>" class="np-read-more">
                                        Read more <i class="fas fa-arrow-right"></i>
                                    </a>
                                </div>
                            </div>
                        </article>
                        <?php endforeach; ?>
                    </div>

                    <!-- Pagination -->
                    <?php if (($pagination['total'] ?? 0) > 1): ?>
                    <nav class="np-pagination">
                        <ul class="np-pagination-list">
                            <?php if (($pagination['current'] ?? 1) > 1): ?>
                            <li>
                                <a href="?q=<?php echo urlencode($searchQuery); ?>&page=<?php echo ($pagination['current'] ?? 1) - 1; ?>" class="np-pagination-link">
                                    <i class="fas fa-chevron-left"></i>
                                </a>
                            </li>
                            <?php endif; ?>

                            <?php 
                            $start = max(1, ($pagination['current'] ?? 1) - 2);
                            $end = min(($pagination['total'] ?? 1), ($pagination['current'] ?? 1) + 2);
                            for ($i = $start; $i <= $end; $i++): 
                            ?>
                            <li>
                                <a href="?q=<?php echo urlencode($searchQuery); ?>&page=<?php echo $i; ?>" 
                                   class="np-pagination-link <?php echo ($i == ($pagination['current'] ?? 1)) ? 'active' : ''; ?>">
                                    <?php echo $i; ?>
                                </a>
                            </li>
                            <?php endfor; ?>

                            <?php if (($pagination['current'] ?? 1) < ($pagination['total'] ?? 1)): ?>
                            <li>
                                <a href="?q=<?php echo urlencode($searchQuery); ?>&page=<?php echo ($pagination['current'] ?? 1) + 1; ?>" class="np-pagination-link">
                                    <i class="fas fa-chevron-right"></i>
                                </a>
                            </li>
                            <?php endif; ?>
                        </ul>
                    </nav>
                    <?php endif; ?>
                </main>

                <!-- Sidebar -->
                <aside class="np-sidebar">
                    <!-- Search Tips -->
                    <div class="np-widget">
                        <h3 class="np-widget-title"><span class="np-widget-icon"><i class="fas fa-lightbulb"></i></span> Search Tips</h3>
                        <ul class="np-tips-list">
                            <li>Use specific keywords</li>
                            <li>Try different terms</li>
                            <li>Check your spelling</li>
                            <li>Browse by category</li>
                        </ul>
                    </div>

                    <!-- Categories -->
                    <?php if (!empty($categories)): ?>
                    <div class="np-widget">
                        <h3 class="np-widget-title"><span class="np-widget-icon"><i class="fas fa-folder"></i></span> Categories</h3>
                        <ul class="np-cat-list">
                            <?php foreach ($categories as $cat => $cnt): ?>
                            <li>
                                <a href="<?php echo $baseUrl; ?>/news/category/<?php echo urlencode(strtolower(str_replace(' ', '-', $cat))); ?>" class="np-cat-link">
                                    <span><?php echo htmlspecialchars($cat); ?></span>
                                    <span class="np-cat-count"><?php echo $cnt; ?></span>
                                </a>
                            </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                    <?php endif; ?>
                </aside>
            </div><!-- /np-layout -->
        <?php endif; /* hasNews */ ?>

    </div><!-- /np-container -->

    <!-- Highlight search terms in results (JS enhancement) -->
    <script>
        (function() {
            const urlParams = new URLSearchParams(window.location.search);
            const query = urlParams.get('q');
            if (query && query.length > 2) {
                const cards = document.querySelectorAll('.np-card-title a, .np-card-excerpt');
                const regex = new RegExp(`(${query.replace(/[.*+?^${}()|[\]\\]/g, '\\$&')})`, 'gi');
                cards.forEach(el => {
                    if (el.children.length === 0) { // only text nodes
                        const original = el.innerText;
                        el.innerHTML = original.replace(regex, '<mark style="background: var(--gold-pale); color: var(--ink); padding: 0 0.2rem; border-radius: 3px;">$1</mark>');
                    }
                });
            }
        })();
    </script>
</body>
</html>