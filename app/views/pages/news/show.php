<?php
/**
 * SINGLE NEWS ARTICLE VIEW - "MAXIMUM WIDTH" REDESIGN
 * ----------------------------------------------------------------------------
 * Design Philosophy: "Edge-to-Edge on Mobile, Controlled on Desktop"
 * 
 * CRITICAL DIFFERENCE: 
 * - On mobile: 98% - 100% container width (only 8px gutter minimum)
 * - On desktop: Max 1280px for readability
 * 
 * CTA text color: NOW WHITE (100% readable on purple gradient)
 * Original color palette PRESERVED (#5D4A8A, #D4A574, creams)
 * Cursor visibility: TRIPLE-GUARANTEED with !important
 * No horizontal slip: ABSOLUTELY ELIMINATED
 * ----------------------------------------------------------------------------
 */

// ===== SECURE DATA PREPARATION =====
$baseUrl = $baseUrl ?? BASE_URL ?? '';
$news = $news ?? [];
$relatedNews = $relatedNews ?? [];
$popularNews = $popularNews ?? [];
$currentPage = $currentPage ?? 'news';
$pageTitle = $pageTitle ?? ($news['title'] ?? 'News Article') . ' - FCT College of Nursing Sciences';
$pageDescription = $pageDescription ?? ($news['excerpt'] ?? 'Official news from FCT College of Nursing Sciences');

// Format dates
$newsDate = !empty($news['created_at']) ? date('F j, Y', strtotime($news['created_at'])) : '';
$newsDateTimeIso = !empty($news['created_at']) ? date('c', strtotime($news['created_at'])) : '';

// Author
$authorName = $news['author_name'] ?? 'FCT Nursing College';
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
    
    <!-- Fonts: Original Playfair + Crimson Pro -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;600;700;800&family=Crimson+Pro:ital,wght@0,300;0,400;0,500;0,600;0,700;1,400&family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Font Awesome 6 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        /* ---------- DESIGN SYSTEM 6.0 : MAXIMUM WIDTH ON MOBILE, ORIGINAL COLORS, ZERO SLIP ---------- */
        
        * { 
            margin: 0; 
            padding: 0; 
            box-sizing: border-box; 
        }
        
        html, body {
            width: 100%;
            overflow-x: hidden; /* CRITICAL: kills horizontal scroll */
            background: #FDFCFA; /* Original cream */
        }

        body {
            font-family: 'Crimson Pro', Georgia, serif;
            font-size: 16px;
            line-height: 1.7;
            color: #1E293B;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }

        /* ---------- ORIGINAL COLOR VARIABLES (PRESERVED) ---------- */
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
            
            /* ---------- CONTAINER WIDTH: REVOLUTIONARY APPROACH ---------- */
            --container-max-width: 1280px;     /* Desktop: controlled */
            --container-padding-mobile: 0.5rem;  /* 8px - ABSOLUTE MINIMUM, makes content NEARLY EDGE-TO-EDGE */
            --container-padding-tablet: 1rem;    /* 16px - still very wide */
            --container-padding-desktop: 2rem;    /* 32px */
            
            /* Fluid spacing */
            --space-xs: 0.5rem;
            --space-sm: 0.75rem;
            --space-md: 1rem;
            --space-lg: 1.5rem;
            --space-xl: 2rem;
            --space-2xl: 2.5rem;
            --space-3xl: 3rem;
            --space-4xl: 4rem;
            
            /* Radius - original */
            --radius-md: 0.5rem;
            --radius-lg: 0.75rem;
            --radius-xl: 1rem;
            --radius-full: 9999px;
            
            --transition: all 0.25s ease;
        }

        /* ---------- CONTAINER: MAXIMUM WIDTH ON SMALL SCREENS, CONTROLLED ON LARGE ---------- */
        .container {
            width: 100%;
            max-width: 100%; /* On mobile, this is 100% - NO LEFT/RIGHT LIMIT */
            margin: 0 auto;
            padding-left: var(--container-padding-mobile);
            padding-right: var(--container-padding-mobile);
        }

        /* Tablet: slightly more padding but still wide */
        @media (min-width: 640px) {
            .container {
                max-width: 100%;
                padding-left: var(--container-padding-tablet);
                padding-right: var(--container-padding-tablet);
            }
        }

        /* Small desktop: start constraining */
        @media (min-width: 1024px) {
            .container {
                max-width: var(--container-max-width);
                padding-left: var(--container-padding-desktop);
                padding-right: var(--container-padding-desktop);
            }
        }

        /* Large desktop: fully constrained */
        @media (min-width: 1280px) {
            .container {
                max-width: var(--container-max-width);
                margin-left: auto;
                margin-right: auto;
            }
        }

        /* ---------- EXTREME MOBILE WIDTH: EDGE-TO-EDGE ---------- */
        @media (max-width: 480px) {
            .container {
                padding-left: 0.5rem;  /* Only 8px gutter - almost no margin */
                padding-right: 0.5rem;
            }
            
            .article-main, .sidebar-card, .cta-banner {
                border-radius: 0.375rem; /* Slightly smaller radius on extreme mobile */
            }
        }

        @media (max-width: 360px) {
            .container {
                padding-left: 0.375rem;  /* 6px - absolute minimum */
                padding-right: 0.375rem;
            }
        }

        /* ---------- TYPOGRAPHY - ORIGINAL, SCALED ---------- */
        h1, h2, h3, h4, h5, h6 {
            font-family: 'Playfair Display', Georgia, serif;
            font-weight: 700;
            line-height: 1.2;
            color: var(--gray-900);
            letter-spacing: -0.01em;
        }

        h1 {
            font-size: 2.2rem;  /* Larger on mobile by default */
            font-weight: 800;
            letter-spacing: -0.02em;
        }

        @media (min-width: 480px) { h1 { font-size: 2.5rem; } }
        @media (min-width: 768px) { h1 { font-size: 3rem; } }
        @media (min-width: 1024px) { h1 { font-size: 3.5rem; } }

        h2 { font-size: 1.8rem; }
        h3 { font-size: 1.5rem; }
        h4 { font-size: 1.25rem; }

        p, li, blockquote {
            font-family: 'Crimson Pro', Georgia, serif;
            color: var(--gray-800);
        }

        a {
            color: var(--primary);
            text-decoration: none;
            transition: var(--transition);
            font-weight: 500;
        }
        a:hover {
            color: var(--primary-dark);
            text-decoration: underline;
            text-underline-offset: 4px;
            text-decoration-color: var(--accent);
        }

        /* ---------- SKIP LINK ---------- */
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

        /* ---------- BREADCRUMB – WIDER ON MOBILE ---------- */
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

        @media (min-width: 640px) {
            .breadcrumb-list { font-size: 0.85rem; }
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

        /* ---------- ARTICLE PAGE – MAXIMUM WIDTH ON MOBILE ---------- */
        .article-page {
            padding: var(--space-xl) 0 var(--space-4xl);
            width: 100%;
        }

        @media (min-width: 768px) {
            .article-page { padding: var(--space-3xl) 0 var(--space-4xl); }
        }

        /* ---------- GRID: STACK ON MOBILE, SIDEBAR ON DESKTOP ---------- */
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

        /* ---------- ARTICLE HEADER – FULL WIDTH ON MOBILE ---------- */
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

        @media (min-width: 768px) {
            .article-excerpt { font-size: 1.3rem; padding-left: var(--space-xl); }
        }

        /* ARTICLE META */
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

        @media (min-width: 640px) {
            .article-meta { gap: var(--space-lg); font-size: 0.85rem; }
        }

        .meta-item {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        .meta-item i {
            color: var(--accent-dark);
            width: 1rem;
        }

        /* ---------- FEATURED IMAGE – FULL WIDTH ON MOBILE ---------- */
        .article-hero {
            margin: var(--space-lg) 0 var(--space-xl);
            border-radius: var(--radius-md);
            overflow: hidden;
            box-shadow: 0 8px 20px -8px rgba(93, 74, 138, 0.15);
            background: var(--gray-100);
            border: 1px solid var(--gray-200);
        }

        @media (min-width: 768px) {
            .article-hero { border-radius: var(--radius-lg); }
        }

        .hero-image-wrapper {
            position: relative;
            width: 100%;
            padding-bottom: 56.25%; /* 16:9 */
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

        /* ---------- ARTICLE MAIN – EDGE-TO-EDGE ON MOBILE ---------- */
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

        @media (min-width: 640px) {
            .article-body { padding: var(--space-2xl) var(--space-2xl); }
        }
        @media (min-width: 1024px) {
            .article-body { padding: var(--space-3xl); }
        }

        .article-content {
            font-size: 1.1rem;
            line-height: 1.8;
            color: var(--gray-800);
        }

        @media (min-width: 768px) {
            .article-content { font-size: 1.2rem; }
        }

        .article-content > * + * {
            margin-top: var(--space-lg);
        }

        .article-content p {
            margin-bottom: var(--space-lg);
            hyphens: auto;
        }

        /* Dropcap - original, scaled */
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

        @media (min-width: 768px) {
            .article-content > p:first-of-type:first-letter { font-size: 4.8rem; }
        }

        .article-content h2 {
            margin-top: var(--space-3xl);
            margin-bottom: var(--space-lg);
        }

        .article-content h3 {
            margin-top: var(--space-2xl);
            margin-bottom: var(--space-md);
        }

        .article-content blockquote {
            border-left: 5px solid var(--accent);
            padding: var(--space-lg) var(--space-xl);
            background: var(--beige);
            border-radius: 0 var(--radius-lg) var(--radius-lg) 0;
            font-style: italic;
            font-size: 1.1rem;
            margin: var(--space-2xl) 0;
        }

        .article-content ul,
        .article-content ol {
            margin-left: var(--space-xl);
            margin-bottom: var(--space-lg);
        }

        .article-content li {
            margin-bottom: var(--space-sm);
        }

        .article-content img {
            max-width: 100%;
            height: auto;
            border-radius: var(--radius-md);
            border: 1px solid var(--gray-200);
            margin: var(--space-2xl) 0;
        }

        /* ---------- ARTICLE FOOTER – WIDE ---------- */
        .article-footer {
            padding: 0 var(--space-lg) var(--space-xl);
        }

        @media (min-width: 640px) {
            .article-footer { padding: 0 var(--space-2xl) var(--space-2xl); }
        }

        /* TAGS */
        .tags-section {
            margin-bottom: var(--space-xl);
        }

        .tags-label {
            font-family: 'Inter', sans-serif;
            font-size: 0.7rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            color: var(--gray-700);
            display: block;
            margin-bottom: var(--space-md);
        }

        .tags-list {
            display: flex;
            flex-wrap: wrap;
            gap: var(--space-sm);
            list-style: none;
        }

        .tag-link {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            background: var(--beige);
            padding: 0.5rem 1.2rem;
            border-radius: var(--radius-full);
            font-size: 0.8rem;
            font-weight: 500;
            color: var(--gray-700);
            border: 1px solid var(--gray-300);
            transition: var(--transition);
        }
        .tag-link:hover {
            background: var(--primary);
            color: white;
            border-color: var(--primary);
            text-decoration: none;
        }

        /* SHARE */
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

        @media (min-width: 480px) {
            .back-button { width: auto; }
        }

        .back-button:hover {
            background: var(--primary);
            color: white;
            text-decoration: none;
        }

        /* ---------- SIDEBAR – WIDER ON MOBILE, STICKY ON DESKTOP ---------- */
        .article-sidebar {
            display: flex;
            flex-direction: column;
            gap: var(--space-xl);
        }

        @media (min-width: 992px) {
            .article-sidebar {
                position: sticky;
                top: 100px;
            }
        }

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

        /* AUTHOR */
        .author-card {
            text-align: center;
        }
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

        /* POPULAR */
        .popular-list {
            list-style: none;
        }
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
        .popular-link:hover .popular-title {
            color: var(--primary);
        }
        .popular-meta {
            font-size: 0.7rem;
            color: var(--gray-500);
            display: flex;
            gap: var(--space-md);
            font-family: 'Inter', sans-serif;
        }

        /* ---------- NEWSLETTER – ORIGINAL COLORS, CURSOR GUARANTEED ---------- */
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

        /* ----- CURSOR: TRIPLE GUARANTEE WITH !important ----- */
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
            line-height: 1.5;
        }
        .newsletter-input:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 4px rgba(93, 74, 138, 0.12);
            caret-color: var(--primary) !important;
            background: white;
        }
        .newsletter-input:hover {
            border-color: var(--primary-light);
        }
        .newsletter-input::placeholder {
            color: var(--gray-400);
            font-weight: 400;
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
            border: 1px solid var(--primary-light);
        }
        .newsletter-button:hover:not(:disabled) {
            transform: translateY(-2px);
            box-shadow: 0 12px 24px -8px rgba(93, 74, 138, 0.25);
            background: var(--primary-dark);
        }
        .newsletter-button:disabled {
            opacity: 0.7;
            cursor: not-allowed;
        }

        .newsletter-disclaimer {
            font-size: 0.7rem;
            color: var(--gray-500);
            line-height: 1.4;
            font-family: 'Inter', sans-serif;
        }

        /* ---------- RELATED ARTICLES – FULL WIDTH ON MOBILE ---------- */
        .related-section {
            margin-top: var(--space-3xl);
            padding-top: var(--space-2xl);
            border-top: 2px solid var(--gray-200);
        }

        .related-header {
            margin-bottom: var(--space-xl);
        }

        .related-title {
            font-size: 1.8rem;
            font-weight: 700;
            color: var(--gray-900);
        }

        @media (min-width: 768px) {
            .related-title { font-size: 2rem; }
        }

        .related-grid {
            display: grid;
            grid-template-columns: 1fr;
            gap: var(--space-lg);
        }

        @media (min-width: 640px) {
            .related-grid { grid-template-columns: repeat(2, 1fr); }
        }

        @media (min-width: 1024px) {
            .related-grid { grid-template-columns: repeat(3, 1fr); }
        }

        .related-card {
            background: var(--white);
            border-radius: var(--radius-lg);
            border: 1px solid var(--gray-200);
            overflow: hidden;
            transition: var(--transition);
            box-shadow: 0 2px 8px rgba(0,0,0,0.02);
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

        .related-content {
            padding: var(--space-lg);
        }
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

        /* ---------- CTA – FULL WIDTH, ORIGINAL GRADIENT, WHITE TEXT FOR READABILITY ---------- */
        .cta-banner {
            margin-top: var(--space-3xl);
            padding: var(--space-2xl) var(--space-lg);
            background: linear-gradient(145deg, var(--primary), var(--primary-dark));
            border-radius: var(--radius-lg);
            text-align: center;
            color: white; /* Base text color white */
            box-shadow: 0 12px 28px -8px rgba(93, 74, 138, 0.3);
        }

        @media (min-width: 768px) {
            .cta-banner { padding: var(--space-3xl) var(--space-2xl); }
        }

        .cta-title {
            color: white; /* Explicit white */
            font-size: 1.8rem;
            font-weight: 700;
            margin-bottom: var(--space-md);
        }

        @media (min-width: 768px) {
            .cta-title { font-size: 2.2rem; }
        }

        .cta-description {
            font-size: 1.1rem; /* Slightly larger */
            margin-bottom: var(--space-xl);
            opacity: 0.95;
            max-width: 600px;
            margin-left: auto;
            margin-right: auto;
            font-family: 'Crimson Pro', Georgia, serif;
            color: white; /* FORCED WHITE for maximum contrast */
            font-weight: 400;
            line-height: 1.6;
        }

        /* Additional override to ensure white text */
        .cta-banner p, 
        .cta-banner .cta-description {
            color: white !important;
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

        /* ---------- BACK TO TOP ---------- */
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

        /* ---------- ACCESSIBILITY & REDUCED MOTION ---------- */
        @media (prefers-reduced-motion: reduce) {
            * { animation: none !important; transition: none !important; }
        }

        :focus-visible {
            outline: 3px solid var(--accent);
            outline-offset: 3px;
        }

        /* ---------- EXTREME SMALL SCREEN SAFEGUARDS ---------- */
        @media (max-width: 360px) {
            .container { 
                padding-left: 0.375rem; 
                padding-right: 0.375rem; 
            }
            .article-title { font-size: 1.8rem; }
            .article-excerpt { 
                font-size: 1rem; 
                padding-left: var(--space-md); 
                border-left-width: 4px;
            }
            .meta-item { font-size: 0.7rem; }
            .sidebar-card { padding: var(--space-lg); }
            .breadcrumb-current { max-width: 140px; }
        }

        /* ZERO HORIZONTAL SCROLL - ULTIMATE GUARANTEE */
        .container,
        .breadcrumb,
        .article-page,
        .article-main,
        .sidebar-card,
        .cta-banner {
            max-width: 100%;
            overflow-x: hidden;
        }

        /* Force all images and embeds to respect container width */
        img, video, iframe, embed {
            max-width: 100%;
            height: auto;
        }
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
                        <!-- TAGS -->
                        <?php if (!empty($news['tags'])): 
                            $tags = [];
                            if (is_string($news['tags'])) {
                                $decoded = json_decode($news['tags'], true);
                                $tags = json_last_error() === JSON_ERROR_NONE ? $decoded : array_map('trim', explode(',', $news['tags']));
                            } elseif (is_array($news['tags'])) {
                                $tags = $news['tags'];
                            }
                            if (!empty($tags)): ?>
                        <div class="tags-section">
                            <span class="tags-label"><i class="fas fa-tags"></i> Topics</span>
                            <ul class="tags-list">
                                <?php foreach ($tags as $tag): ?>
                                <li><a href="<?php echo $baseUrl; ?>/news/search?q=<?php echo urlencode($tag); ?>" class="tag-link"><?php echo htmlspecialchars(trim($tag)); ?></a></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                        <?php endif; endif; ?>

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

                    <!-- NEWSLETTER – ORIGINAL COLORS, CURSOR GUARANTEED -->
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

            <!-- CTA – WITH FIXED WHITE TEXT FOR MAXIMUM CONTRAST -->
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

            // NEWSLETTER - ORIGINAL COLORS, CURSOR GUARANTEED
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
                        formData.append('source', 'sidebar_maxwidth');

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

            // DYNAMIC WIDTH ENFORCEMENT - ENSURES NO SLIP ON ANY DEVICE
            function enforceMaxWidth() {
                // Force container to be nearly edge-to-edge on small screens
                if (window.innerWidth < 640) {
                    document.documentElement.style.setProperty('--container-padding-mobile', '0.5rem');
                }
                if (window.innerWidth < 380) {
                    document.documentElement.style.setProperty('--container-padding-mobile', '0.375rem');
                }
                
                // Prevent any horizontal overflow
                document.body.style.overflowX = 'hidden';
                document.documentElement.style.overflowX = 'hidden';
                
                // Force all major containers to respect width
                const containers = document.querySelectorAll('.container, .article-main, .sidebar-card, .cta-banner');
                containers.forEach(el => {
                    if (el) el.style.maxWidth = '100%';
                });
            }
            
            enforceMaxWidth();
            window.addEventListener('resize', enforceMaxWidth);
            window.addEventListener('orientationchange', enforceMaxWidth);
            
            // DOM ready additional enforcement
            document.addEventListener('DOMContentLoaded', enforceMaxWidth);
        })();
    </script>
</body>
</html>