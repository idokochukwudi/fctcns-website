<?php
/**
 * SINGLE NEWS ARTICLE VIEW - "CONTROLLER-DRIVEN TOPICS HUB"
 * ----------------------------------------------------------------------------
 * Design Philosophy: "Zero Database in Views - 100% Controller Data"
 * 
 * CRITICAL FIXES:
 * ✅ NO overflow on any screen size - text now wraps properly
 * ✅ Topic counts stay on same line or wrap cleanly
 * ✅ Cards stack vertically on mobile
 * ✅ Description text truncates with ellipsis
 * ✅ Zero database queries in view
 * ----------------------------------------------------------------------------
 */

// ===== DATA VALIDATION - ALL FROM CONTROLLER =====
$baseUrl = $baseUrl ?? BASE_URL ?? '';
$news = $news ?? [];
$relatedNews = $relatedNews ?? [];
$popularNews = $popularNews ?? [];
$currentPage = $currentPage ?? 'news';

// ✅ TOPICS DATA MUST COME FROM CONTROLLER
$allNewsTopics = $allNewsTopics ?? [];

// Fallback: If controller didn't pass topics, build from current article ONLY
if (empty($allNewsTopics) && !empty($news['tags'])) {
    $tags = [];
    if (is_string($news['tags'])) {
        $decoded = json_decode($news['tags'], true);
        $tags = json_last_error() === JSON_ERROR_NONE ? $decoded : array_map('trim', explode(',', $news['tags']));
    } elseif (is_array($news['tags'])) {
        $tags = $news['tags'];
    }
    
    foreach ($tags as $tag) {
        $tagLower = strtolower(trim($tag));
        $allNewsTopics[$tagLower] = ($allNewsTopics[$tagLower] ?? 0) + 1;
    }
}

// Page metadata
$pageTitle = $pageTitle ?? ($news['title'] ?? 'News Article') . ' - FCT College of Nursing Sciences';
$pageDescription = $pageDescription ?? ($news['excerpt'] ?? 'Official news from FCT College of Nursing Sciences');

// Format dates
$newsDate = !empty($news['created_at']) ? date('F j, Y', strtotime($news['created_at'])) : '';
$newsDateTimeIso = !empty($news['created_at']) ? date('c', strtotime($news['created_at'])) : '';

// Author (already joined in controller via NewsModel::getBySlug)
$authorName = $news['author_name'] ?? $news['full_name'] ?? 'FCT Nursing College';
$authorRole = $news['author_role'] ?? 'Communications';
$authorInitial = strtoupper(substr($authorName, 0, 1));

// Reading time
$wordCount = !empty($news['content']) ? str_word_count(strip_tags($news['content'])) : 0;
$readingTime = max(1, ceil($wordCount / 200));

// Breadcrumb
$breadcrumb = [
    ['label' => 'Home', 'url' => $baseUrl],
    ['label' => 'News', 'url' => $baseUrl . '/news'],
    ['label' => htmlspecialchars(mb_strimwidth($news['title'] ?? 'Article', 0, 50, '…')), 'url' => '']
];

// =========================================================================
// TOPIC LIBRARY - PURE PRESENTATION LAYER
// =========================================================================
$topicLibrary = [
    'nursing' => [
        'display_name' => 'Nursing',
        'icon' => 'fa-solid fa-stethoscope',
        'color' => '#5D4A8A',
        'bg' => 'rgba(93, 74, 138, 0.08)',
        'description' => 'Nursing education & practice',
        'slug' => 'nursing'
    ],
    'research' => [
        'display_name' => 'Research',
        'icon' => 'fa-solid fa-flask',
        'color' => '#D4A574',
        'bg' => 'rgba(212, 165, 116, 0.12)',
        'description' => 'Latest findings & studies',
        'slug' => 'research'
    ],
    'education' => [
        'display_name' => 'Education',
        'icon' => 'fa-solid fa-graduation-cap',
        'color' => '#2E6B7A',
        'bg' => 'rgba(46, 107, 122, 0.08)',
        'description' => 'Academic programs & training',
        'slug' => 'education'
    ],
    'healthcare' => [
        'display_name' => 'Healthcare',
        'icon' => 'fa-solid fa-heart-pulse',
        'color' => '#BC3B2C',
        'bg' => 'rgba(188, 59, 44, 0.08)',
        'description' => 'Clinical practice & healthcare',
        'slug' => 'healthcare'
    ],
    'announcement' => [
        'display_name' => 'Announcement',
        'icon' => 'fa-solid fa-bullhorn',
        'color' => '#A57C5A',
        'bg' => 'rgba(165, 124, 90, 0.08)',
        'description' => 'Institutional updates',
        'slug' => 'announcement'
    ],
    'student' => [
        'display_name' => 'Student',
        'icon' => 'fa-solid fa-user-graduate',
        'color' => '#1F7D4D',
        'bg' => 'rgba(31, 125, 77, 0.08)',
        'description' => 'Student life & achievements',
        'slug' => 'student'
    ],
    'faculty' => [
        'display_name' => 'Faculty',
        'icon' => 'fa-solid fa-chalkboard-user',
        'color' => '#5D4A8A',
        'bg' => 'rgba(93, 74, 138, 0.08)',
        'description' => 'Faculty excellence',
        'slug' => 'faculty'
    ],
    'event' => [
        'display_name' => 'Event',
        'icon' => 'fa-solid fa-calendar-check',
        'color' => '#C49A6C',
        'bg' => 'rgba(196, 154, 108, 0.12)',
        'description' => 'Upcoming events',
        'slug' => 'event'
    ],
    'policy' => [
        'display_name' => 'Policy',
        'icon' => 'fa-solid fa-file-lines',
        'color' => '#3F4A5A',
        'bg' => 'rgba(63, 74, 90, 0.08)',
        'description' => 'Policies & guidelines',
        'slug' => 'policy'
    ],
    'award' => [
        'display_name' => 'Award',
        'icon' => 'fa-solid fa-trophy',
        'color' => '#B9892E',
        'bg' => 'rgba(185, 137, 46, 0.08)',
        'description' => 'Recognition & awards',
        'slug' => 'award'
    ],
    'community' => [
        'display_name' => 'Community',
        'icon' => 'fa-solid fa-people-arrows',
        'color' => '#64748B',
        'bg' => 'rgba(100, 116, 139, 0.08)',
        'description' => 'Community engagement',
        'slug' => 'community'
    ]
];

// =========================================================================
// BUILD DISPLAY TOPICS - 100% FROM CONTROLLER DATA
// =========================================================================
$displayTopics = [];

foreach ($allNewsTopics as $tagName => $count) {
    $tagLower = strtolower(trim($tagName));
    
    if ($count > 0) {
        if (isset($topicLibrary[$tagLower])) {
            $displayTopics[$tagLower] = [
                'name' => $topicLibrary[$tagLower]['display_name'],
                'display_name' => $topicLibrary[$tagLower]['display_name'],
                'slug' => $topicLibrary[$tagLower]['slug'],
                'icon' => $topicLibrary[$tagLower]['icon'],
                'color' => $topicLibrary[$tagLower]['color'],
                'bg' => $topicLibrary[$tagLower]['bg'],
                'description' => $topicLibrary[$tagLower]['description'],
                'count' => (int)$count
            ];
        } else {
            $displayTopics['tag_' . $tagLower] = [
                'name' => ucwords($tagName),
                'display_name' => ucwords($tagName),
                'slug' => urlencode($tagName),
                'icon' => 'fa-solid fa-tag',
                'color' => '#64748B',
                'bg' => 'rgba(100, 116, 139, 0.08)',
                'description' => 'Related content',
                'count' => (int)$count
            ];
        }
    }
}

// Sort by count (highest first)
uasort($displayTopics, function($a, $b) {
    return $b['count'] - $a['count'];
});

// Limit to top 6 topics
$displayTopics = array_slice($displayTopics, 0, 6, true);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0, viewport-fit=cover">
    <title><?php echo htmlspecialchars($pageTitle); ?></title>
    <meta name="description" content="<?php echo htmlspecialchars($pageDescription); ?>">
    
    <!-- Open Graph -->
    <meta property="og:title" content="<?php echo htmlspecialchars($news['title'] ?? $pageTitle); ?>">
    <meta property="og:description" content="<?php echo htmlspecialchars($pageDescription); ?>">
    <meta property="og:image" content="<?php echo !empty($news['featured_image']) ? $baseUrl . $news['featured_image'] : $baseUrl . '/assets/images/news/default-og.jpg'; ?>">
    <meta property="og:url" content="<?php echo $baseUrl . '/news/' . ($news['slug'] ?? ''); ?>">
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;600;700;800&family=Crimson+Pro:ital,wght@0,300;0,400;0,500;0,600;0,700;1,400&family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Font Awesome 6 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        /* ---------- DESIGN SYSTEM - PURE CSS, NO DATA LOGIC ---------- */
        * { margin: 0; padding: 0; box-sizing: border-box; }
        
        html, body {
            width: 100%;
            overflow-x: hidden;
            background: #FDFCFA;
        }

        body {
            font-family: 'Crimson Pro', Georgia, serif;
            font-size: 16px;
            line-height: 1.7;
            color: #1E293B;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }

        :root {
            --primary: #5D4A8A;
            --primary-dark: #4A3A6F;
            --primary-light: #7B68A8;
            --primary-soft: rgba(93, 74, 138, 0.08);
            
            --accent: #D4A574;
            --accent-dark: #BF8F5E;
            --accent-light: #E6C9A5;
            --accent-soft: rgba(212, 165, 116, 0.12);
            
            --white: #FFFFFF;
            --cream: #FDFCFA;
            --beige: #F7F5F2;
            --gray-100: #F1F5F9;
            --gray-200: #E2E8F0;
            --gray-300: #CBD5E1;
            --gray-400: #94A3B8;
            --gray-500: #64748B;
            --gray-600: #475569;
            --gray-700: #334155;
            --gray-800: #1E293B;
            --gray-900: #0F172A;
            
            --container-max-width: 1280px;
            --container-padding-mobile: 0.5rem;
            --container-padding-tablet: 1rem;
            --container-padding-desktop: 2rem;
            
            --space-xs: 0.5rem;
            --space-sm: 0.75rem;
            --space-md: 1rem;
            --space-lg: 1.5rem;
            --space-xl: 2rem;
            --space-2xl: 2.5rem;
            --space-3xl: 3rem;
            --space-4xl: 4rem;
            
            --radius-md: 0.5rem;
            --radius-lg: 0.75rem;
            --radius-xl: 1rem;
            --radius-full: 9999px;
            
            --transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
            --transition-slow: all 0.35s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .container {
            width: 100%;
            max-width: 100%;
            margin: 0 auto;
            padding-left: var(--container-padding-mobile);
            padding-right: var(--container-padding-mobile);
        }

        @media (min-width: 640px) {
            .container {
                max-width: 100%;
                padding-left: var(--container-padding-tablet);
                padding-right: var(--container-padding-tablet);
            }
        }

        @media (min-width: 1024px) {
            .container {
                max-width: var(--container-max-width);
                padding-left: var(--container-padding-desktop);
                padding-right: var(--container-padding-desktop);
            }
        }

        @media (max-width: 480px) {
            .container { padding-left: 0.5rem; padding-right: 0.5rem; }
        }
        @media (max-width: 360px) {
            .container { padding-left: 0.375rem; padding-right: 0.375rem; }
        }

        h1, h2, h3, h4, h5, h6 {
            font-family: 'Playfair Display', Georgia, serif;
            font-weight: 700;
            line-height: 1.2;
            color: var(--gray-900);
            letter-spacing: -0.01em;
        }

        h1 {
            font-size: 2.2rem;
            font-weight: 800;
            letter-spacing: -0.02em;
        }
        @media (min-width: 480px) { h1 { font-size: 2.5rem; } }
        @media (min-width: 768px) { h1 { font-size: 3rem; } }
        @media (min-width: 1024px) { h1 { font-size: 3.5rem; } }

        .skip-link {
            position: absolute;
            top: -40px;
            left: 1rem;
            background: var(--primary);
            color: white;
            padding: 0.75rem 1.5rem;
            border-radius: 0 0 var(--radius-md) var(--radius-md);
            z-index: 1000;
            text-decoration: none;
            font-family: 'Inter', sans-serif;
            font-weight: 600;
        }
        .skip-link:focus { top: 0; }

        .breadcrumb {
            background: var(--white);
            border-bottom: 1px solid var(--gray-200);
            padding: 0.85rem 0;
            position: sticky;
            top: 0;
            z-index: 100;
            backdrop-filter: blur(12px);
            background: rgba(255, 255, 255, 0.95);
            width: 100%;
        }

        .breadcrumb-list {
            display: flex;
            align-items: center;
            flex-wrap: wrap;
            list-style: none;
            gap: 0.5rem;
            font-size: 0.8rem;
            font-family: 'Inter', sans-serif;
            color: var(--gray-600);
        }

        .breadcrumb-item {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        .breadcrumb-item:not(:last-child)::after {
            content: "/";
            color: var(--gray-400);
            margin-left: 0.5rem;
        }
        .breadcrumb-link {
            color: var(--gray-600);
            white-space: nowrap;
        }
        .breadcrumb-link:hover { color: var(--primary); }
        .breadcrumb-current {
            color: var(--gray-900);
            font-weight: 600;
            white-space: nowrap;
            max-width: 200px;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .article-page {
            padding: var(--space-xl) 0 var(--space-4xl);
            width: 100%;
        }
        @media (min-width: 768px) { .article-page { padding: var(--space-3xl) 0 var(--space-4xl); } }

        .article-grid {
            display: grid;
            grid-template-columns: 1fr;
            gap: var(--space-2xl);
            align-items: start;
        }
        @media (min-width: 992px) {
            .article-grid {
                grid-template-columns: 1fr 340px;
                gap: var(--space-3xl);
            }
        }

        .article-header {
            width: 100%;
            margin-bottom: var(--space-xl);
        }

        .article-category {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            font-family: 'Inter', sans-serif;
            font-size: 0.7rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.12em;
            color: var(--primary-dark);
            background: var(--primary-soft);
            padding: 0.4rem 1.2rem;
            border-radius: var(--radius-full);
            margin-bottom: var(--space-lg);
            border: 1px solid var(--primary-light);
        }

        .article-title {
            margin-bottom: var(--space-lg);
            color: var(--gray-900);
            line-height: 1.1;
        }

        .article-excerpt {
            font-size: 1.2rem;
            line-height: 1.6;
            color: var(--gray-700);
            font-weight: 350;
            margin-bottom: var(--space-xl);
            border-left: 5px solid var(--accent);
            padding-left: var(--space-lg);
            font-style: normal;
        }
        @media (min-width: 768px) { .article-excerpt { font-size: 1.3rem; padding-left: var(--space-xl); } }

        .article-meta {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            gap: var(--space-md);
            font-family: 'Inter', sans-serif;
            font-size: 0.8rem;
            color: var(--gray-600);
            padding: var(--space-md) 0;
            border-top: 2px solid var(--gray-200);
            border-bottom: 2px solid var(--gray-200);
            margin-bottom: var(--space-xl);
        }
        @media (min-width: 640px) { .article-meta { gap: var(--space-lg); font-size: 0.85rem; } }

        .meta-item {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        .meta-item i {
            color: var(--accent-dark);
            width: 1rem;
        }

        .article-hero {
            margin: var(--space-lg) 0 var(--space-xl);
            border-radius: var(--radius-md);
            overflow: hidden;
            box-shadow: 0 8px 20px -8px rgba(93, 74, 138, 0.15);
            background: var(--gray-100);
            border: 1px solid var(--gray-200);
        }
        @media (min-width: 768px) { .article-hero { border-radius: var(--radius-lg); } }

        .hero-image-wrapper {
            position: relative;
            width: 100%;
            padding-bottom: 56.25%;
            background: linear-gradient(145deg, var(--gray-200), var(--gray-100));
        }

        .hero-image {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .article-main {
            background: var(--white);
            border-radius: var(--radius-lg);
            box-shadow: 0 4px 12px rgba(0,0,0,0.02);
            border: 1px solid var(--gray-200);
            overflow: hidden;
            width: 100%;
        }

        .article-body {
            padding: var(--space-xl) var(--space-lg);
        }
        @media (min-width: 640px) { .article-body { padding: var(--space-2xl) var(--space-2xl); } }

        .article-content {
            font-size: 1.1rem;
            line-height: 1.8;
            color: var(--gray-800);
        }
        @media (min-width: 768px) { .article-content { font-size: 1.2rem; } }

        .article-content > p:first-of-type:first-letter {
            font-family: 'Playfair Display', Georgia, serif;
            font-size: 4rem;
            float: left;
            line-height: 0.8;
            margin-right: var(--space-md);
            padding-top: 0.2rem;
            color: var(--primary);
            font-weight: 700;
        }
        @media (min-width: 768px) { .article-content > p:first-of-type:first-letter { font-size: 4.8rem; } }

        /* ---------- ✦✦✦ FIXED TOPICS HUB - NO OVERFLOW ✦✦✦ ---------- */
        .topics-hub {
            margin-bottom: var(--space-2xl);
            background: linear-gradient(145deg, var(--white), var(--cream));
            border-radius: var(--radius-xl);
            padding: var(--space-2xl) var(--space-xl);
            border: 1px solid var(--gray-200);
            box-shadow: 0 8px 20px -12px rgba(93, 74, 138, 0.12);
            position: relative;
            overflow: hidden;
            width: 100%;
        }

        .topics-hub::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 4px;
            background: linear-gradient(90deg, var(--primary), var(--accent), var(--primary-light));
            opacity: 0.6;
        }

        .topics-header {
            display: flex;
            align-items: baseline;
            justify-content: space-between;
            margin-bottom: var(--space-xl);
            flex-wrap: wrap;
            gap: var(--space-md);
        }

        .topics-title-area {
            display: flex;
            align-items: baseline;
            gap: var(--space-sm);
            flex-wrap: wrap;
        }

        .topics-icon {
            font-size: 1.4rem;
            color: var(--primary);
            background: var(--primary-soft);
            width: 48px;
            height: 48px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: var(--radius-full);
        }

        .topics-title {
            font-family: 'Playfair Display', Georgia, serif;
            font-size: 1.6rem;
            font-weight: 700;
            color: var(--gray-900);
            letter-spacing: -0.02em;
            margin: 0;
            line-height: 1.2;
        }

        .topics-subtitle {
            font-family: 'Inter', sans-serif;
            font-size: 0.85rem;
            color: var(--gray-600);
            font-weight: 400;
            margin-left: var(--space-sm);
        }

        .topics-view-all {
            font-family: 'Inter', sans-serif;
            font-size: 0.85rem;
            font-weight: 600;
            color: var(--primary);
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.5rem 1.2rem;
            background: var(--white);
            border-radius: var(--radius-full);
            border: 1px solid var(--gray-300);
            transition: var(--transition);
            text-decoration: none;
            white-space: nowrap;
        }

        .topics-view-all:hover {
            background: var(--primary);
            color: white;
            border-color: var(--primary);
            transform: translateX(4px);
        }

        .topics-view-all i {
            font-size: 0.75rem;
        }

        /* ✅ FIXED: Grid that stacks properly on mobile */
        .topics-grid {
            display: grid;
            grid-template-columns: 1fr; /* Always stack vertically */
            gap: var(--space-md);
        }

        /* ✅ FIXED: Two columns only on larger screens */
        @media (min-width: 768px) {
            .topics-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        /* ✅ FIXED: Topic card - no overflow, flex wrap */
        .topic-card {
            display: flex;
            align-items: flex-start;
            gap: var(--space-md);
            padding: var(--space-lg);
            background: var(--white);
            border: 1px solid var(--gray-200);
            border-radius: var(--radius-lg);
            transition: var(--transition-slow);
            text-decoration: none;
            position: relative;
            overflow: hidden;
            width: 100%;
            min-width: 0; /* Critical for flexbox text wrapping */
        }

        .topic-card::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(145deg, transparent, rgba(93, 74, 138, 0.02));
            opacity: 0;
            transition: var(--transition);
            pointer-events: none;
        }

        .topic-card:hover {
            transform: translateY(-4px) scale(1.02);
            border-color: transparent;
            box-shadow: 0 12px 24px -12px rgba(93, 74, 138, 0.2);
        }

        .topic-card:hover::after {
            opacity: 1;
        }

        /* ✅ FIXED: Icon stays fixed size, doesn't shrink */
        .topic-icon-wrapper {
            width: 56px;
            height: 56px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: var(--radius-full);
            background: var(--topic-bg, var(--primary-soft));
            color: var(--topic-color, var(--primary));
            font-size: 1.4rem;
            transition: var(--transition-slow);
            flex-shrink: 0; /* Prevents icon from squishing */
        }

        .topic-card:hover .topic-icon-wrapper {
            transform: scale(1.1);
            box-shadow: 0 8px 16px -8px var(--topic-color, var(--primary));
        }

        /* ✅ FIXED: Content area - flex and allow wrapping */
        .topic-content {
            flex: 1;
            min-width: 0; /* CRITICAL: Allows text to wrap */
            width: 100%;
        }

        /* ✅ FIXED: Topic name row - flex wrap for small screens */
        .topic-name {
            display: flex;
            align-items: baseline;
            flex-wrap: wrap; /* Allows count to move below on tiny screens */
            gap: var(--space-xs);
            margin-bottom: var(--space-xs);
            width: 100%;
        }

        .topic-title {
            font-family: 'Inter', sans-serif;
            font-size: 1.1rem;
            font-weight: 700;
            color: var(--gray-900);
            margin: 0;
            line-height: 1.3;
            letter-spacing: -0.01em;
            word-break: break-word; /* Prevents long words from overflowing */
        }

        /* ✅ FIXED: Count badge - no overflow */
        .topic-count {
            font-family: 'Inter', sans-serif;
            font-size: 0.75rem;
            font-weight: 600;
            color: var(--gray-600);
            background: var(--gray-100);
            padding: 0.25rem 0.75rem;
            border-radius: var(--radius-full);
            transition: var(--transition);
            white-space: nowrap; /* Keeps "1 article" together */
            flex-shrink: 0; /* Prevents badge from wrapping weirdly */
        }

        /* ✅ FIXED: When screen is tiny, allow badge to move to new line */
        @media (max-width: 360px) {
            .topic-name {
                flex-direction: column;
                align-items: flex-start;
                gap: 0.25rem;
            }
            
            .topic-count {
                white-space: nowrap;
                align-self: flex-start;
            }
        }

        .topic-card:hover .topic-count {
            background: var(--topic-color, var(--primary));
            color: white;
        }

        /* ✅ FIXED: Description - truncates with ellipsis */
        .topic-description {
            font-family: 'Inter', sans-serif;
            font-size: 0.8rem;
            color: var(--gray-600);
            line-height: 1.5;
            margin: 0;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
            text-overflow: ellipsis;
            word-break: break-word;
            width: 100%;
        }

        .topic-card {
            --topic-color: var(--primary);
            --topic-bg: var(--primary-soft);
        }

        /* ✅ FIXED: Mobile optimization */
        @media (max-width: 480px) {
            .topics-hub { 
                padding: var(--space-lg) var(--space-md); 
            }
            
            .topic-card { 
                padding: var(--space-md); 
            }
            
            .topic-icon-wrapper { 
                width: 48px; 
                height: 48px; 
                font-size: 1.2rem; 
            }
            
            .topic-title { 
                font-size: 1rem; 
            }
            
            .topic-description {
                -webkit-line-clamp: 2;
                font-size: 0.75rem;
            }
        }

        @media (max-width: 360px) {
            .topic-card {
                padding: var(--space-sm);
                gap: var(--space-sm);
            }
            
            .topic-icon-wrapper {
                width: 40px;
                height: 40px;
                font-size: 1rem;
            }
            
            .topic-title {
                font-size: 0.95rem;
            }
        }

        .tags-section { display: none; }

        .article-footer {
            padding: 0 var(--space-lg) var(--space-xl);
        }
        @media (min-width: 640px) { .article-footer { padding: 0 var(--space-2xl) var(--space-2xl); } }

        .share-section {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: var(--space-md) var(--space-lg);
            background: var(--beige);
            border-radius: var(--radius-xl);
            border: 1px solid var(--gray-200);
            flex-wrap: wrap;
            gap: var(--space-md);
            margin-bottom: var(--space-xl);
        }

        .share-label {
            font-family: 'Inter', sans-serif;
            font-weight: 600;
            color: var(--gray-800);
            display: flex;
            align-items: center;
            gap: var(--space-2);
            font-size: 0.9rem;
        }

        .share-buttons {
            display: flex;
            gap: var(--space-sm);
            flex-wrap: wrap;
        }

        .share-btn {
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            color: white;
            transition: var(--transition);
        }
        .share-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 16px rgba(0,0,0,0.1);
        }
        .share-btn.facebook { background: #1877F2; }
        .share-btn.twitter { background: #1DA1F2; }
        .share-btn.linkedin { background: #0A66C2; }
        .share-btn.whatsapp { background: #25D366; }
        .share-btn.email { background: var(--gray-700); }

        .back-button {
            display: inline-flex;
            align-items: center;
            gap: 0.75rem;
            padding: var(--space-md) var(--space-xl);
            border: 2px solid var(--primary);
            border-radius: var(--radius-full);
            color: var(--primary);
            font-weight: 700;
            font-size: 0.9rem;
            transition: var(--transition);
            width: 100%;
            justify-content: center;
        }
        @media (min-width: 480px) { .back-button { width: auto; } }
        .back-button:hover {
            background: var(--primary);
            color: white;
            text-decoration: none;
        }

        .article-sidebar {
            display: flex;
            flex-direction: column;
            gap: var(--space-xl);
        }
        @media (min-width: 992px) { .article-sidebar { position: sticky; top: 100px; } }

        .sidebar-card {
            background: var(--white);
            border-radius: var(--radius-lg);
            border: 1px solid var(--gray-200);
            padding: var(--space-xl);
            box-shadow: 0 2px 8px rgba(0,0,0,0.02);
            width: 100%;
        }

        .sidebar-title {
            font-family: 'Playfair Display', Georgia, serif;
            font-size: 1.3rem;
            font-weight: 700;
            color: var(--gray-900);
            margin-bottom: var(--space-lg);
            padding-bottom: var(--space-md);
            border-bottom: 2px solid var(--accent-light);
            display: flex;
            align-items: center;
            gap: var(--space-2);
        }

        .author-card { text-align: center; }
        .author-avatar {
            width: 80px;
            height: 80px;
            margin: 0 auto var(--space-md);
            border-radius: 50%;
            background: linear-gradient(145deg, var(--primary), var(--primary-light));
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Playfair Display', Georgia, serif;
            font-size: 2rem;
            font-weight: 700;
            color: white;
            box-shadow: 0 8px 16px rgba(93, 74, 138, 0.2);
            border: 3px solid var(--white);
        }
        .author-name {
            font-size: 1.25rem;
            font-weight: 700;
            margin-bottom: 0.2rem;
            color: var(--gray-900);
        }
        .author-role {
            font-size: 0.75rem;
            color: var(--accent-dark);
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            margin-bottom: var(--space-md);
        }
        .author-bio {
            font-size: 0.9rem;
            color: var(--gray-600);
            line-height: 1.6;
            font-family: 'Inter', sans-serif;
        }

        .popular-list { list-style: none; }
        .popular-item {
            padding: var(--space-md) 0;
            border-bottom: 1px solid var(--gray-200);
        }
        .popular-item:last-child { border-bottom: none; padding-bottom: 0; }
        .popular-item:first-child { padding-top: 0; }
        .popular-link {
            display: block;
            text-decoration: none;
        }
        .popular-title {
            font-size: 1rem;
            font-weight: 700;
            color: var(--gray-800);
            margin-bottom: var(--space-xs);
            line-height: 1.4;
            font-family: 'Playfair Display', Georgia, serif;
            transition: var(--transition);
        }
        .popular-link:hover .popular-title { color: var(--primary); }
        .popular-meta {
            font-size: 0.7rem;
            color: var(--gray-500);
            display: flex;
            gap: var(--space-md);
            font-family: 'Inter', sans-serif;
        }

        .newsletter-description {
            font-size: 0.9rem;
            color: var(--gray-600);
            margin-bottom: var(--space-lg);
            line-height: 1.5;
        }
        #newsletter-message {
            display: none;
            padding: 0.9rem 1rem;
            border-radius: var(--radius-md);
            font-size: 0.85rem;
            margin-bottom: var(--space-md);
            border: 1px solid transparent;
            font-family: 'Inter', sans-serif;
        }
        .newsletter-form {
            display: flex;
            flex-direction: column;
            gap: var(--space-md);
        }
        .newsletter-input {
            width: 100%;
            padding: 1rem 1.4rem;
            border: 2px solid var(--gray-300);
            border-radius: var(--radius-full);
            font-family: 'Inter', sans-serif;
            font-size: 0.95rem;
            background: white;
            color: var(--gray-900);
            caret-color: var(--primary) !important;
            transition: all 0.2s;
            outline: none;
        }
        .newsletter-input:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 4px rgba(93, 74, 138, 0.12);
            caret-color: var(--primary) !important;
        }
        .newsletter-button {
            width: 100%;
            padding: 1rem 1.4rem;
            background: linear-gradient(145deg, var(--primary), var(--primary-dark));
            color: white;
            border: none;
            border-radius: var(--radius-full);
            font-family: 'Inter', sans-serif;
            font-weight: 700;
            font-size: 0.95rem;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: var(--space-2);
            transition: var(--transition);
        }
        .newsletter-button:hover:not(:disabled) {
            transform: translateY(-2px);
            box-shadow: 0 12px 24px -8px rgba(93, 74, 138, 0.25);
        }
        .newsletter-disclaimer {
            font-size: 0.7rem;
            color: var(--gray-500);
            line-height: 1.4;
        }

        .related-section {
            margin-top: var(--space-3xl);
            padding-top: var(--space-2xl);
            border-top: 2px solid var(--gray-200);
        }
        .related-header { margin-bottom: var(--space-xl); }
        .related-title {
            font-size: 1.8rem;
            font-weight: 700;
            color: var(--gray-900);
        }
        .related-grid {
            display: grid;
            grid-template-columns: 1fr;
            gap: var(--space-lg);
        }
        @media (min-width: 640px) { .related-grid { grid-template-columns: repeat(2, 1fr); } }
        @media (min-width: 1024px) { .related-grid { grid-template-columns: repeat(3, 1fr); } }

        .related-card {
            background: var(--white);
            border-radius: var(--radius-lg);
            border: 1px solid var(--gray-200);
            overflow: hidden;
            transition: var(--transition);
        }
        .related-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 20px 30px -10px rgba(93, 74, 138, 0.15);
            border-color: var(--accent);
        }
        .related-image-wrapper {
            width: 100%;
            padding-top: 60%;
            position: relative;
            background: var(--gray-200);
        }
        .related-image {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        .related-content { padding: var(--space-lg); }
        .related-category {
            font-family: 'Inter', sans-serif;
            font-size: 0.6rem;
            font-weight: 700;
            text-transform: uppercase;
            color: var(--accent-dark);
            letter-spacing: 0.08em;
        }
        .related-card-title {
            font-size: 1.1rem;
            font-weight: 700;
            margin: var(--space-sm) 0 var(--space-sm);
            line-height: 1.4;
        }
        .related-card-title a {
            color: var(--gray-900);
            text-decoration: none;
        }
        .related-card-title a:hover {
            color: var(--primary);
            text-decoration: underline;
            text-decoration-color: var(--accent);
        }
        .related-card-meta {
            font-size: 0.7rem;
            color: var(--gray-600);
            display: flex;
            gap: var(--space-md);
            font-family: 'Inter', sans-serif;
        }

        .cta-banner {
            margin-top: var(--space-3xl);
            padding: var(--space-2xl) var(--space-lg);
            background: linear-gradient(145deg, var(--primary), var(--primary-dark));
            border-radius: var(--radius-lg);
            text-align: center;
            color: white;
            box-shadow: 0 12px 28px -8px rgba(93, 74, 138, 0.3);
        }
        .cta-title {
            color: white;
            font-size: 1.8rem;
            font-weight: 700;
            margin-bottom: var(--space-md);
        }
        @media (min-width: 768px) { .cta-title { font-size: 2.2rem; } }
        .cta-description {
            font-size: 1.1rem;
            margin-bottom: var(--space-xl);
            opacity: 0.95;
            max-width: 600px;
            margin-left: auto;
            margin-right: auto;
            font-family: 'Crimson Pro', Georgia, serif;
            color: white !important;
            font-weight: 400;
        }
        .cta-buttons {
            display: flex;
            justify-content: center;
            gap: var(--space-md);
            flex-wrap: wrap;
        }
        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.75rem;
            padding: 0.8rem 1.8rem;
            font-family: 'Inter', sans-serif;
            font-weight: 700;
            font-size: 0.9rem;
            border-radius: var(--radius-full);
            transition: var(--transition);
            border: none;
            cursor: pointer;
        }
        .btn-primary {
            background: white;
            color: var(--primary);
            border: 2px solid transparent;
        }
        .btn-primary:hover {
            background: var(--accent-light);
            color: var(--primary-dark);
            transform: translateY(-2px);
        }
        .btn-outline {
            background: transparent;
            color: white;
            border: 2px solid white;
        }
        .btn-outline:hover {
            background: white;
            color: var(--primary);
            transform: translateY(-2px);
        }

        .back-to-top {
            position: fixed;
            bottom: 1.5rem;
            right: 1.5rem;
            width: 48px;
            height: 48px;
            background: var(--accent);
            color: white;
            border: none;
            border-radius: 50%;
            display: none;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            box-shadow: 0 8px 20px rgba(212, 165, 116, 0.3);
            transition: var(--transition);
            z-index: 99;
            font-size: 1.2rem;
        }
        .back-to-top:hover {
            background: var(--accent-dark);
            transform: translateY(-4px);
        }

        @media (prefers-reduced-motion: reduce) {
            * { animation: none !important; transition: none !important; }
        }

        :focus-visible {
            outline: 3px solid var(--accent);
            outline-offset: 3px;
        }

        .container, .breadcrumb, .article-page, .article-main, .sidebar-card, .cta-banner, .topics-hub {
            max-width: 100%;
            overflow-x: hidden;
        }
        img, video, iframe, embed { max-width: 100%; height: auto; }
    </style>
</head>
<body>
    <a href="#main-content" class="skip-link">Skip to main content</a>

    <!-- BREADCRUMB -->
    <nav class="breadcrumb" aria-label="Breadcrumb">
        <div class="container">
            <ol class="breadcrumb-list">
                <?php foreach ($breadcrumb as $item): ?>
                <li class="breadcrumb-item">
                    <?php if (!empty($item['url'])): ?>
                    <a href="<?php echo $item['url']; ?>" class="breadcrumb-link"><?php echo $item['label']; ?></a>
                    <?php else: ?>
                    <span class="breadcrumb-current"><?php echo $item['label']; ?></span>
                    <?php endif; ?>
                </li>
                <?php endforeach; ?>
            </ol>
        </div>
    </nav>

    <main id="main-content" class="article-page">
        <div class="container">

            <!-- HEADER -->
            <header class="article-header">
                <?php if (!empty($news['category'])): ?>
                <div class="article-category">
                    <i class="fas fa-folder-open"></i>
                    <?php echo htmlspecialchars($news['category']); ?>
                </div>
                <?php endif; ?>
                
                <h1 class="article-title"><?php echo htmlspecialchars($news['title'] ?? 'News Article'); ?></h1>
                
                <?php if (!empty($news['excerpt'])): ?>
                <p class="article-excerpt"><?php echo htmlspecialchars($news['excerpt']); ?></p>
                <?php endif; ?>
                
                <div class="article-meta">
                    <span class="meta-item"><i class="far fa-calendar-alt"></i> <time datetime="<?php echo $newsDateTimeIso; ?>"><?php echo $newsDate; ?></time></span>
                    <span class="meta-item"><i class="far fa-clock"></i> <?php echo $readingTime; ?> min read</span>
                    <?php if (!empty($news['views_count'])): ?>
                    <span class="meta-item"><i class="far fa-eye"></i> <?php echo number_format($news['views_count']); ?> views</span>
                    <?php endif; ?>
                    <span class="meta-item"><i class="far fa-user"></i> <?php echo htmlspecialchars($authorName); ?></span>
                </div>
            </header>

            <!-- FEATURED IMAGE -->
            <?php if (!empty($news['featured_image'])): ?>
            <figure class="article-hero">
                <div class="hero-image-wrapper">
                    <img src="<?php echo $baseUrl . $news['featured_image']; ?>" 
                         alt="<?php echo htmlspecialchars($news['title'] ?? ''); ?>" 
                         class="hero-image"
                         loading="eager"
                         onerror="this.parentElement.innerHTML='<div style=\'width:100%;height:100%;display:flex;align-items:center;justify-content:center;background:var(--gray-200);color:var(--gray-500);font-size:3rem;\'><i class=\'fas fa-newspaper\'></i></div>';">
                </div>
            </figure>
            <?php endif; ?>

            <!-- GRID: MAIN + SIDEBAR -->
            <div class="article-grid">

                <!-- MAIN ARTICLE -->
                <article class="article-main">
                    <div class="article-body">
                        <div class="article-content">
                            <?php if (!empty($news['content'])): ?>
                                <?php echo $news['content']; ?>
                            <?php else: ?>
                                <p>We are preparing this article. Please check back soon for the latest updates from FCT College of Nursing Sciences.</p>
                            <?php endif; ?>
                        </div>
                    </div>

                    <footer class="article-footer">
                        
                        <!-- ================================================================= -->
                        <!-- ✦✦✦ FIXED TOPICS HUB - NO OVERFLOW, WRAPS PROPERLY ✦✦✦ -->
                        <!-- ================================================================= -->
                        
                        <?php if (!empty($displayTopics)): ?>
                        <div class="topics-hub">
                            <div class="topics-header">
                                <div class="topics-title-area">
                                    <span class="topics-icon">
                                        <i class="fas fa-tags"></i>
                                    </span>
                                    <h2 class="topics-title">Topics</h2>
                                    <span class="topics-subtitle">explore related content</span>
                                </div>
                                <a href="<?php echo $baseUrl; ?>/news/topics" class="topics-view-all">
                                    View all <i class="fas fa-arrow-right"></i>
                                </a>
                            </div>
                            
                            <div class="topics-grid">
                                <?php foreach ($displayTopics as $topic): ?>
                                <a href="<?php echo $baseUrl; ?>/news?topic=<?php echo urlencode($topic['slug']); ?>" 
                                   class="topic-card"
                                   style="--topic-color: <?php echo $topic['color']; ?>; --topic-bg: <?php echo $topic['bg']; ?>;">
                                    
                                    <span class="topic-icon-wrapper">
                                        <i class="<?php echo $topic['icon']; ?>"></i>
                                    </span>
                                    
                                    <span class="topic-content">
                                        <span class="topic-name">
                                            <span class="topic-title"><?php echo htmlspecialchars($topic['display_name']); ?></span>
                                            <span class="topic-count">
                                                <?php echo number_format($topic['count']); ?> 
                                                article<?php echo $topic['count'] != 1 ? 's' : ''; ?>
                                            </span>
                                        </span>
                                        <span class="topic-description">
                                            <?php echo $topic['description']; ?>
                                        </span>
                                    </span>
                                </a>
                                <?php endforeach; ?>
                            </div>
                        </div>
                        <?php endif; ?>

                        <!-- SHARE -->
                        <div class="share-section">
                            <span class="share-label"><i class="fas fa-share-alt"></i> Share</span>
                            <div class="share-buttons">
                                <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo urlencode($baseUrl . '/news/' . ($news['slug'] ?? '')); ?>" class="share-btn facebook" target="_blank" rel="noopener" aria-label="Facebook"><i class="fab fa-facebook-f"></i></a>
                                <a href="https://twitter.com/intent/tweet?url=<?php echo urlencode($baseUrl . '/news/' . ($news['slug'] ?? '')); ?>&text=<?php echo urlencode($news['title'] ?? ''); ?>" class="share-btn twitter" target="_blank" rel="noopener" aria-label="Twitter"><i class="fab fa-twitter"></i></a>
                                <a href="https://www.linkedin.com/shareArticle?mini=true&url=<?php echo urlencode($baseUrl . '/news/' . ($news['slug'] ?? '')); ?>" class="share-btn linkedin" target="_blank" rel="noopener" aria-label="LinkedIn"><i class="fab fa-linkedin-in"></i></a>
                                <a href="https://wa.me/?text=<?php echo urlencode($news['title'] . ' - ' . $baseUrl . '/news/' . ($news['slug'] ?? '')); ?>" class="share-btn whatsapp" target="_blank" rel="noopener" aria-label="WhatsApp"><i class="fab fa-whatsapp"></i></a>
                                <a href="mailto:?subject=<?php echo urlencode($news['title'] ?? ''); ?>&body=<?php echo urlencode($baseUrl . '/news/' . ($news['slug'] ?? '')); ?>" class="share-btn email" aria-label="Email"><i class="far fa-envelope"></i></a>
                            </div>
                        </div>

                        <!-- BACK -->
                        <a href="<?php echo $baseUrl; ?>/news" class="back-button"><i class="fas fa-arrow-left"></i> All News</a>
                    </footer>
                </article>

                <!-- SIDEBAR -->
                <aside class="article-sidebar">
                    
                    <!-- AUTHOR -->
                    <div class="sidebar-card">
                        <h2 class="sidebar-title"><i class="fas fa-user-circle"></i> Author</h2>
                        <div class="author-card">
                            <div class="author-avatar"><?php echo $authorInitial; ?></div>
                            <div class="author-name"><?php echo htmlspecialchars($authorName); ?></div>
                            <div class="author-role"><?php echo htmlspecialchars($authorRole); ?></div>
                            <p class="author-bio">
                                <?php echo ($authorRole === 'Communications') 
                                    ? 'Official news and announcements from FCT College of Nursing Sciences.' 
                                    : 'Contributor to FCT College news.'; ?>
                            </p>
                        </div>
                    </div>

                    <!-- POPULAR -->
                    <div class="sidebar-card">
                        <h2 class="sidebar-title"><i class="fas fa-fire" style="color: var(--accent);"></i> Popular</h2>
                        <?php if (!empty($popularNews)): ?>
                        <ul class="popular-list">
                            <?php $i = 0; foreach ($popularNews as $popular): if (++$i > 5) break; ?>
                            <li class="popular-item">
                                <a href="<?php echo $baseUrl; ?>/news/<?php echo htmlspecialchars($popular['slug'] ?? ''); ?>" class="popular-link">
                                    <h3 class="popular-title"><?php echo htmlspecialchars($popular['title'] ?? ''); ?></h3>
                                    <div class="popular-meta">
                                        <span><i class="far fa-calendar-alt"></i> <?php echo date('M j, Y', strtotime($popular['created_at'] ?? '')); ?></span>
                                        <span><i class="far fa-clock"></i> <?php echo max(1, ceil(str_word_count(strip_tags($popular['content'] ?? ''))/200)); ?> min</span>
                                    </div>
                                </a>
                            </li>
                            <?php endforeach; ?>
                        </ul>
                        <?php else: ?>
                        <p style="color: var(--gray-500); font-family: 'Inter', sans-serif;">Popular articles will appear here.</p>
                        <?php endif; ?>
                    </div>

                    <!-- NEWSLETTER -->
                    <div class="sidebar-card">
                        <h2 class="sidebar-title"><i class="fas fa-envelope"></i> Newsletter</h2>
                        <p class="newsletter-description">Subscribe to receive the latest news and updates directly in your inbox.</p>
                        <div id="newsletter-message"></div>
                        <form class="newsletter-form" id="newsletter-form" action="<?php echo BASE_URL; ?>/newsletter/subscribe" method="POST">
                            <div class="newsletter-input-wrapper">
                                <input type="email" 
                                       name="email" 
                                       id="newsletter-email" 
                                       class="newsletter-input" 
                                       placeholder="Your email address" 
                                       required 
                                       aria-label="Email for newsletter">
                            </div>
                            <button type="submit" class="newsletter-button" id="newsletter-submit">
                                <i class="fas fa-paper-plane"></i> Subscribe
                            </button>
                            <p class="newsletter-disclaimer">We respect your privacy. Unsubscribe anytime.</p>
                        </form>
                    </div>
                </aside>
            </div>

            <!-- RELATED ARTICLES -->
            <?php if (!empty($relatedNews)): ?>
            <section class="related-section">
                <div class="related-header">
                    <h2 class="related-title">Related Articles</h2>
                </div>
                <div class="related-grid">
                    <?php foreach ($relatedNews as $related): ?>
                    <article class="related-card">
                        <div class="related-image-wrapper">
                            <?php if (!empty($related['featured_image'])): ?>
                            <img src="<?php echo $baseUrl . $related['featured_image']; ?>" alt="" class="related-image" loading="lazy">
                            <?php else: ?>
                            <div style="width:100%;height:100%;background:var(--gray-200);display:flex;align-items:center;justify-content:center;color:var(--gray-500);"><i class="fas fa-newspaper fa-3x"></i></div>
                            <?php endif; ?>
                        </div>
                        <div class="related-content">
                            <?php if (!empty($related['category'])): ?>
                            <span class="related-category"><?php echo htmlspecialchars($related['category']); ?></span>
                            <?php endif; ?>
                            <h3 class="related-card-title">
                                <a href="<?php echo $baseUrl; ?>/news/<?php echo htmlspecialchars($related['slug'] ?? ''); ?>">
                                    <?php echo htmlspecialchars($related['title'] ?? ''); ?>
                                </a>
                            </h3>
                            <div class="related-card-meta">
                                <span><i class="far fa-calendar-alt"></i> <?php echo date('M j, Y', strtotime($related['created_at'] ?? '')); ?></span>
                                <span><i class="far fa-clock"></i> <?php echo max(1, ceil(str_word_count(strip_tags($related['content'] ?? ''))/200)); ?> min</span>
                            </div>
                        </div>
                    </article>
                    <?php endforeach; ?>
                </div>
            </section>
            <?php endif; ?>

            <!-- CTA -->
            <section class="cta-banner">
                <h2 class="cta-title">Stay Connected</h2>
                <p class="cta-description">Get the latest news from FCT College of Nursing Sciences delivered to your inbox.</p>
                <div class="cta-buttons">
                    <a href="<?php echo $baseUrl; ?>/news" class="btn btn-primary"><i class="fas fa-newspaper"></i> All News</a>
                    <a href="<?php echo $baseUrl; ?>/contact" class="btn btn-outline"><i class="fas fa-envelope"></i> Contact</a>
                </div>
            </section>
        </div>
    </main>

    <!-- BACK TO TOP -->
    <button class="back-to-top" id="backToTop" aria-label="Back to top"><i class="fas fa-chevron-up"></i></button>

    <script>
        (function() {
            "use strict";

            // NEWSLETTER
            const newsletterForm = document.getElementById('newsletter-form');
            if (newsletterForm) {
                const emailInput = document.getElementById('newsletter-email');
                const submitBtn = document.getElementById('newsletter-submit');
                const msgDiv = document.getElementById('newsletter-message');

                newsletterForm.addEventListener('submit', async function(e) {
                    e.preventDefault();
                    
                    const email = emailInput.value.trim();
                    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                    
                    if (!email || !emailRegex.test(email)) {
                        msgDiv.style.display = 'block';
                        msgDiv.textContent = 'Please enter a valid email address.';
                        msgDiv.style.background = '#fee9e7';
                        msgDiv.style.color = '#BF8F5E';
                        msgDiv.style.border = '1px solid #f5c2b7';
                        return;
                    }

                    emailInput.disabled = true;
                    submitBtn.disabled = true;
                    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Subscribing...';

                    try {
                        const formData = new FormData();
                        formData.append('email', email);
                        formData.append('source', 'sidebar_professional');

                        const response = await fetch('<?php echo BASE_URL; ?>/newsletter/subscribe', {
                            method: 'POST',
                            body: formData,
                            headers: { 'Accept': 'application/json' }
                        });

                        const data = await response.json();

                        msgDiv.style.display = 'block';
                        if (data.success) {
                            msgDiv.textContent = data.message || 'Subscription confirmed!';
                            msgDiv.style.background = '#e2f0e2';
                            msgDiv.style.color = '#4A3A6F';
                            msgDiv.style.border = '1px solid #b8d9b8';
                            emailInput.value = '';
                            setTimeout(() => { msgDiv.style.display = 'none'; }, 5000);
                        } else {
                            msgDiv.textContent = data.message || 'Subscription failed.';
                            msgDiv.style.background = '#fee9e7';
                            msgDiv.style.color = '#BF8F5E';
                            msgDiv.style.border = '1px solid #f5c2b7';
                        }
                    } catch (error) {
                        msgDiv.style.display = 'block';
                        msgDiv.textContent = 'Connection error. Please try again.';
                        msgDiv.style.background = '#fee9e7';
                        msgDiv.style.color = '#BF8F5E';
                        msgDiv.style.border = '1px solid #f5c2b7';
                    } finally {
                        emailInput.disabled = false;
                        submitBtn.disabled = false;
                        submitBtn.innerHTML = '<i class="fas fa-paper-plane"></i> Subscribe';
                    }
                });
            }

            // BACK TO TOP
            const backBtn = document.getElementById('backToTop');
            if (backBtn) {
                window.addEventListener('scroll', function() {
                    backBtn.style.display = window.scrollY > 500 ? 'flex' : 'none';
                });
                backBtn.addEventListener('click', function() {
                    window.scrollTo({ top: 0, behavior: 'smooth' });
                });
            }

            // WIDTH ENFORCEMENT - PURE UI, NO DATA
            function enforceWidth() {
                if (window.innerWidth < 640) {
                    document.documentElement.style.setProperty('--container-padding-mobile', '0.5rem');
                }
                if (window.innerWidth < 380) {
                    document.documentElement.style.setProperty('--container-padding-mobile', '0.375rem');
                }
                document.body.style.overflowX = 'hidden';
                document.documentElement.style.overflowX = 'hidden';
            }
            
            enforceWidth();
            window.addEventListener('resize', enforceWidth);
            window.addEventListener('orientationchange', enforceWidth);
        })();
    </script>
</body>
</html>