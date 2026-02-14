<?php
// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Ensure variables exist
$baseUrl = $baseUrl ?? '';
$news = $news ?? [];
$category = $category ?? '';
$category_slug = $category_slug ?? str_replace(' ', '-', strtolower($category));
$categories = $categories ?? [];
$popularNews = $popularNews ?? [];
$archiveMonths = $archiveMonths ?? [];
$pagination = $pagination ?? [];

// Pagination defaults
$currentPage = $pagination['current'] ?? 1;
$totalPages = $pagination['total'] ?? 1;
$totalCount = $pagination['totalCount'] ?? 0;

// Get category color based on name
function getCategoryColor($category) {
    $colors = [
        'Academic News' => '#4361ee',
        'Research' => '#f72585',
        'Events' => '#fb8500',
        'Student Life' => '#06d6a0',
        'Alumni' => '#8338ec',
        'Faculty' => '#3a0ca3',
        'Clinical' => '#4cc9f0',
        'Healthcare' => '#f72585',
        'Announcements' => '#fb8500',
        'default' => '#4361ee'
    ];
    
    foreach ($colors as $key => $color) {
        if (stripos($category, $key) !== false) {
            return $color;
        }
    }
    
    return $colors['default'];
}

$categoryColor = getCategoryColor($category);

// Helper function to truncate text
function truncateText($text, $length = 150) {
    if (strlen($text) <= $length) return $text;
    return substr($text, 0, $length) . '...';
}

// Helper function to format date
function formatDate($date) {
    return date('M d, Y', strtotime($date));
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($category); ?> - FCT College of Nursing Sciences</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&display=swap" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- AOS Animation Library -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        :root {
            --primary: <?php echo $categoryColor; ?>;
            --primary-light: <?php echo $categoryColor; ?>20;
            --primary-dark: <?php echo $categoryColor; ?>dd;
            --secondary: #64748b;
            --success: #10b981;
            --warning: #f59e0b;
            --danger: #ef4444;
            --light: #f8fafc;
            --dark: #0f172a;
            --gray: #64748b;
            --gray-light: #e2e8f0;
            --border: #e2e8f0;
            --white: #ffffff;
            --shadow-sm: 0 1px 3px rgba(0,0,0,0.12), 0 1px 2px rgba(0,0,0,0.24);
            --shadow: 0 4px 6px -1px rgba(0,0,0,0.1), 0 2px 4px -1px rgba(0,0,0,0.06);
            --shadow-lg: 0 10px 15px -3px rgba(0,0,0,0.1), 0 4px 6px -2px rgba(0,0,0,0.05);
            --shadow-xl: 0 20px 25px -5px rgba(0,0,0,0.1), 0 10px 10px -5px rgba(0,0,0,0.04);
        }

        body {
            font-family: "Inter", sans-serif;
            background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
            color: var(--dark);
            line-height: 1.6;
            overflow-x: hidden;
        }

        /* Hero Section */
        .category-hero {
            position: relative;
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            color: white;
            padding: 100px 20px;
            text-align: center;
            margin-bottom: 60px;
            overflow: hidden;
        }

        .category-hero::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320"><path fill="%23ffffff" fill-opacity="0.1" d="M0,96L48,112C96,128,192,160,288,160C384,160,480,128,576,122.7C672,117,768,139,864,154.7C960,171,1056,181,1152,165.3C1248,149,1344,107,1392,85.3L1440,64L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z"></path></svg>') no-repeat bottom;
            background-size: cover;
            opacity: 0.1;
        }

        .hero-content {
            position: relative;
            z-index: 2;
            max-width: 800px;
            margin: 0 auto;
        }

        .hero-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: rgba(255,255,255,0.2);
            backdrop-filter: blur(10px);
            padding: 8px 16px;
            border-radius: 50px;
            font-size: 14px;
            font-weight: 500;
            margin-bottom: 24px;
            border: 1px solid rgba(255,255,255,0.3);
        }

        .hero-badge i {
            font-size: 16px;
        }

        .category-hero h1 {
            font-size: 56px;
            font-weight: 800;
            margin-bottom: 20px;
            line-height: 1.2;
            text-shadow: 0 2px 10px rgba(0,0,0,0.2);
            letter-spacing: -0.02em;
        }

        .category-hero p {
            font-size: 18px;
            opacity: 0.95;
            max-width: 600px;
            margin: 0 auto 30px;
            line-height: 1.7;
        }

        .hero-stats {
            display: flex;
            justify-content: center;
            gap: 40px;
            margin-top: 30px;
        }

        .stat-item {
            text-align: center;
        }

        .stat-value {
            font-size: 32px;
            font-weight: 700;
            margin-bottom: 5px;
        }

        .stat-label {
            font-size: 14px;
            opacity: 0.8;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        /* Container */
        .container {
            max-width: 1280px;
            margin: 0 auto;
            padding: 0 20px 80px;
        }

        /* Layout */
        .content-wrapper {
            display: grid;
            grid-template-columns: 2.5fr 1fr;
            gap: 40px;
        }

        @media (max-width: 992px) {
            .content-wrapper {
                grid-template-columns: 1fr;
            }
            
            .category-hero h1 {
                font-size: 42px;
            }
        }

        /* Breadcrumb */
        .breadcrumb {
            max-width: 1280px;
            margin: 20px auto;
            padding: 0 20px;
            font-size: 14px;
        }

        .breadcrumb-list {
            display: flex;
            align-items: center;
            gap: 8px;
            list-style: none;
            background: white;
            padding: 12px 20px;
            border-radius: 50px;
            box-shadow: var(--shadow-sm);
        }

        .breadcrumb-item a {
            color: var(--gray);
            text-decoration: none;
            transition: color 0.3s ease;
            display: flex;
            align-items: center;
            gap: 4px;
        }

        .breadcrumb-item a:hover {
            color: var(--primary);
        }

        .breadcrumb-item.active {
            color: var(--primary);
            font-weight: 500;
        }

        .breadcrumb-separator {
            color: var(--gray-light);
            font-size: 12px;
        }

        /* News Grid */
        .news-grid {
            display: flex;
            flex-direction: column;
            gap: 30px;
        }

        .news-card {
            background: white;
            border-radius: 24px;
            overflow: hidden;
            box-shadow: var(--shadow);
            transition: all 0.3s ease;
            display: flex;
            flex-direction: row;
            position: relative;
            border: 1px solid rgba(0,0,0,0.05);
        }

        .news-card:hover {
            transform: translateY(-5px);
            box-shadow: var(--shadow-xl);
        }

        @media (max-width: 768px) {
            .news-card {
                flex-direction: column;
            }
        }

        .news-image {
            width: 300px;
            flex-shrink: 0;
            position: relative;
            overflow: hidden;
        }

        @media (max-width: 768px) {
            .news-image {
                width: 100%;
                height: 220px;
            }
        }

        .news-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.5s ease;
        }

        .news-card:hover .news-image img {
            transform: scale(1.1);
        }

        .image-overlay {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(to top, rgba(0,0,0,0.3), transparent);
            pointer-events: none;
        }

        .category-tag {
            position: absolute;
            top: 20px;
            left: 20px;
            background: var(--primary);
            color: white;
            padding: 6px 12px;
            border-radius: 50px;
            font-size: 12px;
            font-weight: 600;
            z-index: 2;
            box-shadow: var(--shadow);
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .category-tag i {
            font-size: 12px;
        }

        .news-content {
            padding: 30px;
            flex: 1;
            display: flex;
            flex-direction: column;
        }

        .news-meta {
            display: flex;
            gap: 20px;
            margin-bottom: 15px;
            font-size: 13px;
            color: var(--gray);
            flex-wrap: wrap;
        }

        .meta-item {
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .meta-item i {
            color: var(--primary);
            font-size: 14px;
        }

        .news-title {
            font-size: 24px;
            margin-bottom: 15px;
            font-weight: 700;
            line-height: 1.4;
        }

        .news-title a {
            color: var(--dark);
            text-decoration: none;
            transition: color 0.3s ease;
        }

        .news-title a:hover {
            color: var(--primary);
        }

        .news-excerpt {
            color: var(--gray);
            margin-bottom: 20px;
            line-height: 1.7;
            flex: 1;
        }

        .read-more {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            color: var(--primary);
            text-decoration: none;
            font-weight: 600;
            font-size: 14px;
            transition: all 0.3s ease;
            margin-top: auto;
            padding: 8px 0;
        }

        .read-more:hover {
            gap: 15px;
            color: var(--primary-dark);
        }

        .read-more i {
            transition: transform 0.3s ease;
        }

        .read-more:hover i {
            transform: translateX(5px);
        }

        /* Featured Card */
        .news-card.featured {
            border-left: 4px solid var(--primary);
        }

        .featured-badge {
            position: absolute;
            top: 20px;
            right: 20px;
            background: var(--warning);
            color: white;
            padding: 6px 12px;
            border-radius: 50px;
            font-size: 12px;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 5px;
            z-index: 2;
        }

        /* No News State */
        .no-news {
            text-align: center;
            padding: 80px 40px;
            background: white;
            border-radius: 24px;
            box-shadow: var(--shadow);
            border: 1px solid rgba(0,0,0,0.05);
        }

        .no-news-icon {
            width: 120px;
            height: 120px;
            background: var(--primary-light);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 25px;
        }

        .no-news-icon i {
            font-size: 48px;
            color: var(--primary);
        }

        .no-news h2 {
            font-size: 28px;
            color: var(--dark);
            margin-bottom: 15px;
            font-weight: 700;
        }

        .no-news p {
            color: var(--gray);
            font-size: 16px;
            margin-bottom: 30px;
            max-width: 400px;
            margin-left: auto;
            margin-right: auto;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            padding: 14px 32px;
            background: var(--primary);
            color: white;
            text-decoration: none;
            border-radius: 50px;
            font-weight: 600;
            font-size: 15px;
            transition: all 0.3s ease;
            border: none;
            cursor: pointer;
            box-shadow: var(--shadow);
        }

        .btn:hover {
            background: var(--primary-dark);
            transform: translateY(-2px);
            box-shadow: var(--shadow-lg);
        }

        .btn-outline {
            background: transparent;
            color: var(--primary);
            border: 2px solid var(--primary);
            box-shadow: none;
        }

        .btn-outline:hover {
            background: var(--primary);
            color: white;
        }

        /* Sidebar */
        .sidebar {
            position: sticky;
            top: 20px;
        }

        .sidebar-widget {
            background: white;
            border-radius: 24px;
            padding: 30px;
            margin-bottom: 30px;
            box-shadow: var(--shadow);
            border: 1px solid rgba(0,0,0,0.05);
            transition: all 0.3s ease;
        }

        .sidebar-widget:hover {
            box-shadow: var(--shadow-lg);
        }

        .widget-title {
            font-size: 20px;
            margin-bottom: 25px;
            padding-bottom: 15px;
            border-bottom: 2px solid var(--gray-light);
            display: flex;
            align-items: center;
            gap: 10px;
            font-weight: 700;
        }

        .widget-title i {
            color: var(--primary);
            font-size: 20px;
        }

        .widget-title span {
            background: var(--primary-light);
            color: var(--primary);
            padding: 2px 8px;
            border-radius: 50px;
            font-size: 12px;
            margin-left: auto;
        }

        /* Categories List */
        .category-list {
            list-style: none;
        }

        .category-item {
            margin-bottom: 10px;
        }

        .category-link {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 12px 15px;
            background: var(--light);
            border-radius: 12px;
            color: var(--dark);
            text-decoration: none;
            transition: all 0.3s ease;
            border: 1px solid transparent;
        }

        .category-link:hover {
            background: white;
            border-color: var(--primary);
            transform: translateX(5px);
        }

        .category-link.active {
            background: var(--primary);
            color: white;
        }

        .category-name {
            display: flex;
            align-items: center;
            gap: 10px;
            font-weight: 500;
        }

        .category-name i {
            color: var(--primary);
            font-size: 14px;
        }

        .category-link.active .category-name i {
            color: white;
        }

        .category-count {
            background: rgba(0,0,0,0.1);
            padding: 4px 10px;
            border-radius: 50px;
            font-size: 12px;
            font-weight: 600;
        }

        .category-link.active .category-count {
            background: rgba(255,255,255,0.2);
            color: white;
        }

        /* Popular Posts */
        .popular-list {
            list-style: none;
        }

        .popular-item {
            display: flex;
            gap: 15px;
            padding: 15px 0;
            border-bottom: 1px solid var(--gray-light);
        }

        .popular-item:last-child {
            border-bottom: none;
        }

        .popular-image {
            width: 70px;
            height: 70px;
            border-radius: 12px;
            overflow: hidden;
            flex-shrink: 0;
            position: relative;
        }

        .popular-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.3s ease;
        }

        .popular-item:hover .popular-image img {
            transform: scale(1.1);
        }

        .popular-number {
            position: absolute;
            top: -5px;
            left: -5px;
            width: 24px;
            height: 24px;
            background: var(--primary);
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 12px;
            font-weight: 700;
            border: 2px solid white;
        }

        .popular-content {
            flex: 1;
        }

        .popular-title {
            font-size: 15px;
            margin-bottom: 8px;
            font-weight: 600;
            line-height: 1.4;
        }

        .popular-title a {
            color: var(--dark);
            text-decoration: none;
            transition: color 0.3s ease;
        }

        .popular-title a:hover {
            color: var(--primary);
        }

        .popular-meta {
            font-size: 12px;
            color: var(--gray);
            display: flex;
            gap: 12px;
        }

        .popular-meta i {
            margin-right: 3px;
            color: var(--primary);
        }

        /* Archive List */
        .archive-list {
            list-style: none;
        }

        .archive-item {
            margin-bottom: 8px;
        }

        .archive-link {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px 12px;
            background: var(--light);
            border-radius: 10px;
            color: var(--dark);
            text-decoration: none;
            transition: all 0.3s ease;
            font-size: 14px;
        }

        .archive-link:hover {
            background: var(--primary);
            color: white;
            transform: translateX(5px);
        }

        .archive-link i {
            margin-right: 8px;
            color: var(--primary);
            font-size: 12px;
        }

        .archive-link:hover i {
            color: white;
        }

        .archive-count {
            background: rgba(0,0,0,0.1);
            padding: 2px 8px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 600;
        }

        .archive-link:hover .archive-count {
            background: rgba(255,255,255,0.2);
            color: white;
        }

        /* Tags Cloud */
        .tags-cloud {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
        }

        .tag {
            padding: 6px 14px;
            background: var(--light);
            border-radius: 50px;
            font-size: 13px;
            color: var(--dark);
            text-decoration: none;
            transition: all 0.3s ease;
            border: 1px solid transparent;
        }

        .tag:hover {
            background: var(--primary);
            color: white;
            transform: translateY(-2px);
        }

        /* Newsletter Widget */
        .newsletter-widget {
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            color: white;
        }

        .newsletter-widget .widget-title {
            color: white;
            border-bottom-color: rgba(255,255,255,0.2);
        }

        .newsletter-widget .widget-title i {
            color: white;
        }

        .newsletter-text {
            margin-bottom: 20px;
            font-size: 14px;
            opacity: 0.9;
            line-height: 1.7;
        }

        .newsletter-form {
            display: flex;
            gap: 10px;
        }

        .newsletter-input {
            flex: 1;
            padding: 12px 16px;
            border: none;
            border-radius: 50px;
            font-size: 14px;
            outline: none;
        }

        .newsletter-btn {
            width: 45px;
            height: 45px;
            border: none;
            background: white;
            color: var(--primary);
            border-radius: 50%;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .newsletter-btn:hover {
            transform: scale(1.1);
            box-shadow: 0 0 20px rgba(255,255,255,0.3);
        }

        /* Pagination */
        .pagination {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 8px;
            margin-top: 50px;
        }

        .page-link {
            min-width: 45px;
            height: 45px;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 1px solid var(--gray-light);
            background: white;
            color: var(--dark);
            text-decoration: none;
            border-radius: 12px;
            transition: all 0.3s ease;
            font-weight: 500;
        }

        .page-link:hover:not(.active) {
            background: var(--primary);
            color: white;
            border-color: var(--primary);
            transform: translateY(-2px);
        }

        .page-link.active {
            background: var(--primary);
            color: white;
            border-color: var(--primary);
            cursor: default;
            box-shadow: var(--shadow);
        }

        .page-link.disabled {
            opacity: 0.5;
            cursor: not-allowed;
            pointer-events: none;
        }

        .page-dots {
            color: var(--gray);
            font-weight: 600;
        }

        /* Loading Animation */
        .loading-skeleton {
            animation: skeleton-loading 1s linear infinite alternate;
        }

        @keyframes skeleton-loading {
            0% {
                background-color: #e2e8f0;
            }
            100% {
                background-color: #f1f5f9;
            }
        }

        /* Scroll to Top Button */
        .scroll-top {
            position: fixed;
            bottom: 30px;
            right: 30px;
            width: 50px;
            height: 50px;
            background: var(--primary);
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            box-shadow: var(--shadow-lg);
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s ease;
            z-index: 100;
            border: none;
        }

        .scroll-top.visible {
            opacity: 1;
            visibility: visible;
        }

        .scroll-top:hover {
            transform: translateY(-5px);
            background: var(--primary-dark);
        }

        /* Responsive */
        @media (max-width: 768px) {
            .hero-stats {
                flex-direction: column;
                gap: 20px;
            }
            
            .category-hero h1 {
                font-size: 36px;
            }
            
            .newsletter-form {
                flex-direction: column;
            }
            
            .newsletter-btn {
                width: 100%;
                border-radius: 50px;
            }
        }
    </style>
</head>
<body>
    <!-- Include Header -->
    <?php include APP_PATH . '/views/partials/header.php'; ?>

    <!-- Breadcrumb -->
    <div class="breadcrumb" data-aos="fade-down">
        <ul class="breadcrumb-list">
            <li class="breadcrumb-item">
                <a href="<?php echo $baseUrl; ?>">
                    <i class="fas fa-home"></i> Home
                </a>
            </li>
            <li class="breadcrumb-separator">
                <i class="fas fa-chevron-right"></i>
            </li>
            <li class="breadcrumb-item">
                <a href="<?php echo $baseUrl; ?>/news">
                    <i class="fas fa-newspaper"></i> News
                </a>
            </li>
            <li class="breadcrumb-separator">
                <i class="fas fa-chevron-right"></i>
            </li>
            <li class="breadcrumb-item active">
                <i class="fas fa-folder"></i> <?php echo htmlspecialchars($category); ?>
            </li>
        </ul>
    </div>

    <!-- Hero Section -->
    <section class="category-hero" data-aos="fade-up">
        <div class="hero-content">
            <div class="hero-badge">
                <i class="fas fa-folder-open"></i>
                Category
            </div>
            <h1><?php echo htmlspecialchars($category); ?></h1>
            <p>Explore the latest news, updates, and insights in <?php echo strtolower($category); ?>. Stay informed with our comprehensive coverage.</p>
            <div class="hero-stats">
                <div class="stat-item" data-aos="fade-up" data-aos-delay="100">
                    <div class="stat-value"><?php echo $totalCount; ?></div>
                    <div class="stat-label">Articles</div>
                </div>
                <div class="stat-item" data-aos="fade-up" data-aos-delay="200">
                    <div class="stat-value">
                        <?php 
                        $authors = array_unique(array_column($news, 'author_name'));
                        echo count($authors); 
                        ?>
                    </div>
                    <div class="stat-label">Contributors</div>
                </div>
                <div class="stat-item" data-aos="fade-up" data-aos-delay="300">
                    <div class="stat-value">
                        <?php 
                        $totalViews = array_sum(array_column($news, 'views_count'));
                        echo $totalViews > 1000 ? round($totalViews/1000,1).'k' : $totalViews;
                        ?>
                    </div>
                    <div class="stat-label">Total Views</div>
                </div>
            </div>
        </div>
    </section>

    <!-- Main Content -->
    <div class="container">
        <div class="content-wrapper">
            <!-- News Grid -->
            <div class="news-grid">
                <?php if (empty($news)): ?>
                <div class="no-news" data-aos="zoom-in">
                    <div class="no-news-icon">
                        <i class="fas fa-newspaper"></i>
                    </div>
                    <h2>No articles found</h2>
                    <p>There are currently no articles in the <?php echo strtolower($category); ?> category. Check back soon for updates!</p>
                    <a href="<?php echo $baseUrl; ?>/news" class="btn">
                        <i class="fas fa-arrow-left"></i>
                        Browse All News
                    </a>
                </div>
                <?php else: ?>
                    <?php foreach ($news as $index => $item): ?>
                    <article class="news-card <?php echo !empty($item['is_featured']) ? 'featured' : ''; ?>" 
                             data-aos="fade-up" 
                             data-aos-delay="<?php echo ($index % 5) * 50; ?>">
                        
                        <?php if (!empty($item['is_featured'])): ?>
                        <div class="featured-badge">
                            <i class="fas fa-star"></i>
                            Featured
                        </div>
                        <?php endif; ?>
                        
                        <?php if (!empty($item['featured_image'])): ?>
                        <div class="news-image">
                            <img src="<?php echo $baseUrl . htmlspecialchars($item['featured_image']); ?>" 
                                 alt="<?php echo htmlspecialchars($item['title']); ?>"
                                 loading="lazy">
                            <div class="image-overlay"></div>
                            <div class="category-tag">
                                <i class="fas fa-folder"></i>
                                <?php echo htmlspecialchars($item['category'] ?? $category); ?>
                            </div>
                        </div>
                        <?php endif; ?>
                        
                        <div class="news-content">
                            <div class="news-meta">
                                <span class="meta-item">
                                    <i class="far fa-calendar-alt"></i>
                                    <?php echo formatDate($item['created_at']); ?>
                                </span>
                                <?php if (!empty($item['author_name'])): ?>
                                <span class="meta-item">
                                    <i class="far fa-user-circle"></i>
                                    <?php echo htmlspecialchars($item['author_name']); ?>
                                </span>
                                <?php endif; ?>
                                <span class="meta-item">
                                    <i class="far fa-clock"></i>
                                    <?php echo ceil(str_word_count($item['content'] ?? '') / 200); ?> min read
                                </span>
                                <?php if (!empty($item['views_count'])): ?>
                                <span class="meta-item">
                                    <i class="far fa-eye"></i>
                                    <?php echo number_format($item['views_count']); ?> views
                                </span>
                                <?php endif; ?>
                            </div>
                            
                            <h2 class="news-title">
                                <a href="<?php echo $baseUrl; ?>/news/<?php echo htmlspecialchars($item['slug']); ?>">
                                    <?php echo htmlspecialchars($item['title']); ?>
                                </a>
                            </h2>
                            
                            <div class="news-excerpt">
                                <?php echo htmlspecialchars(truncateText(strip_tags($item['excerpt'] ?: $item['content']), 200)); ?>
                            </div>
                            
                            <a href="<?php echo $baseUrl; ?>/news/<?php echo htmlspecialchars($item['slug']); ?>" class="read-more">
                                Continue Reading
                                <i class="fas fa-arrow-right"></i>
                            </a>
                        </div>
                    </article>
                    <?php endforeach; ?>

                    <!-- Pagination -->
                    <?php if ($totalPages > 1): ?>
                    <div class="pagination" data-aos="fade-up">
                        <?php if ($currentPage > 1): ?>
                        <a href="?page=<?php echo $currentPage - 1; ?>" class="page-link">
                            <i class="fas fa-chevron-left"></i>
                        </a>
                        <?php endif; ?>

                        <?php
                        $start = max(1, $currentPage - 2);
                        $end = min($totalPages, $start + 4);
                        $start = max(1, $end - 4);
                        
                        if ($start > 1): ?>
                            <a href="?page=1" class="page-link">1</a>
                            <?php if ($start > 2): ?>
                            <span class="page-dots">...</span>
                            <?php endif; ?>
                        <?php endif;
                        
                        for ($i = $start; $i <= $end; $i++):
                        ?>
                        <a href="?page=<?php echo $i; ?>" 
                           class="page-link <?php echo $i == $currentPage ? 'active' : ''; ?>">
                            <?php echo $i; ?>
                        </a>
                        <?php endfor; ?>

                        <?php if ($end < $totalPages): ?>
                            <?php if ($end < $totalPages - 1): ?>
                            <span class="page-dots">...</span>
                            <?php endif; ?>
                            <a href="?page=<?php echo $totalPages; ?>" class="page-link"><?php echo $totalPages; ?></a>
                        <?php endif; ?>

                        <?php if ($currentPage < $totalPages): ?>
                        <a href="?page=<?php echo $currentPage + 1; ?>" class="page-link">
                            <i class="fas fa-chevron-right"></i>
                        </a>
                        <?php endif; ?>
                    </div>
                    <?php endif; ?>
                <?php endif; ?>
            </div>

            <!-- Sidebar -->
            <div class="sidebar">
                <!-- Categories Widget -->
                <div class="sidebar-widget" data-aos="fade-left">
                    <h3 class="widget-title">
                        <i class="fas fa-folder-tree"></i>
                        Categories
                        <span><?php echo count($categories); ?></span>
                    </h3>
                    <ul class="category-list">
                        <?php foreach ($categories as $cat): ?>
                        <li class="category-item">
                            <a href="<?php echo $baseUrl; ?>/news/category/<?php echo str_replace(' ', '-', strtolower($cat['category'])); ?>" 
                               class="category-link <?php echo ($cat['category'] == $category) ? 'active' : ''; ?>">
                                <span class="category-name">
                                    <i class="fas fa-chevron-right"></i>
                                    <?php echo htmlspecialchars($cat['category']); ?>
                                </span>
                                <span class="category-count"><?php echo $cat['count']; ?></span>
                            </a>
                        </li>
                        <?php endforeach; ?>
                    </ul>
                </div>

                <!-- Popular News Widget -->
                <?php if (!empty($popularNews)): ?>
                <div class="sidebar-widget" data-aos="fade-left" data-aos-delay="100">
                    <h3 class="widget-title">
                        <i class="fas fa-fire-flame-curved"></i>
                        Popular This Week
                        <span>Top 5</span>
                    </h3>
                    <ul class="popular-list">
                        <?php foreach ($popularNews as $index => $popular): ?>
                        <li class="popular-item">
                            <?php if (!empty($popular['featured_image'])): ?>
                            <div class="popular-image">
                                <img src="<?php echo $baseUrl . htmlspecialchars($popular['featured_image']); ?>" 
                                     alt="<?php echo htmlspecialchars($popular['title']); ?>"
                                     loading="lazy">
                                <span class="popular-number"><?php echo $index + 1; ?></span>
                            </div>
                            <?php endif; ?>
                            <div class="popular-content">
                                <h4 class="popular-title">
                                    <a href="<?php echo $baseUrl; ?>/news/<?php echo htmlspecialchars($popular['slug']); ?>">
                                        <?php echo htmlspecialchars(truncateText($popular['title'], 50)); ?>
                                    </a>
                                </h4>
                                <div class="popular-meta">
                                    <span>
                                        <i class="far fa-eye"></i>
                                        <?php echo number_format($popular['views_count'] ?? 0); ?>
                                    </span>
                                    <span>
                                        <i class="far fa-calendar"></i>
                                        <?php echo formatDate($popular['created_at']); ?>
                                    </span>
                                </div>
                            </div>
                        </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
                <?php endif; ?>

                <!-- Archive Widget -->
                <?php if (!empty($archiveMonths)): ?>
                <div class="sidebar-widget" data-aos="fade-left" data-aos-delay="200">
                    <h3 class="widget-title">
                        <i class="fas fa-calendar-archive"></i>
                        Archive
                        <span><?php echo count($archiveMonths); ?></span>
                    </h3>
                    <ul class="archive-list">
                        <?php foreach ($archiveMonths as $archive): ?>
                        <li class="archive-item">
                            <a href="<?php echo $baseUrl; ?>/news/archive/<?php echo $archive['year']; ?>/<?php echo $archive['month']; ?>" 
                               class="archive-link">
                                <span>
                                    <i class="far fa-calendar-alt"></i>
                                    <?php echo $archive['month_name']; ?> <?php echo $archive['year']; ?>
                                </span>
                                <span class="archive-count"><?php echo $archive['count']; ?></span>
                            </a>
                        </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
                <?php endif; ?>

                <!-- Tags Widget (Example) -->
                <div class="sidebar-widget" data-aos="fade-left" data-aos-delay="300">
                    <h3 class="widget-title">
                        <i class="fas fa-tags"></i>
                        Popular Tags
                    </h3>
                    <div class="tags-cloud">
                        <a href="#" class="tag">Nursing</a>
                        <a href="#" class="tag">Healthcare</a>
                        <a href="#" class="tag">Education</a>
                        <a href="#" class="tag">Research</a>
                        <a href="#" class="tag">Clinical</a>
                        <a href="#" class="tag">Students</a>
                        <a href="#" class="tag">Faculty</a>
                        <a href="#" class="tag">Events</a>
                        <a href="#" class="tag">Workshops</a>
                        <a href="#" class="tag">Scholarships</a>
                    </div>
                </div>

                <!-- Newsletter Widget -->
                <div class="sidebar-widget newsletter-widget" data-aos="fade-left" data-aos-delay="400">
                    <h3 class="widget-title">
                        <i class="fas fa-envelope-open-text"></i>
                        Newsletter
                    </h3>
                    <p class="newsletter-text">
                        Subscribe to get the latest updates from <?php echo strtolower($category); ?> category directly in your inbox.
                    </p>
                    <form class="newsletter-form" action="<?php echo $baseUrl; ?>/newsletter/subscribe" method="POST">
                        <input type="email" name="email" class="newsletter-input" 
                               placeholder="Your email address" required>
                        <input type="hidden" name="category" value="<?php echo htmlspecialchars($category); ?>">
                        <button type="submit" class="newsletter-btn">
                            <i class="fas fa-paper-plane"></i>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Scroll to Top Button -->
    <button class="scroll-top" id="scrollTop" onclick="window.scrollTo({top: 0, behavior: 'smooth'})">
        <i class="fas fa-arrow-up"></i>
    </button>

    <!-- Include Footer -->
    <?php include APP_PATH . '/views/partials/footer.php'; ?>

    <!-- Scripts -->
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        // Initialize AOS
        AOS.init({
            duration: 800,
            once: true,
            offset: 100
        });

        // Scroll to top button
        window.addEventListener('scroll', function() {
            const scrollTop = document.getElementById('scrollTop');
            if (window.scrollY > 500) {
                scrollTop.classList.add('visible');
            } else {
                scrollTop.classList.remove('visible');
            }
        });

        // Lazy loading images
        document.addEventListener('DOMContentLoaded', function() {
            const images = document.querySelectorAll('img[loading="lazy"]');
            if ('loading' in HTMLImageElement.prototype) {
                // Browser supports native lazy loading
                images.forEach(img => {
                    img.loading = 'lazy';
                });
            } else {
                // Fallback for browsers that don't support lazy loading
                const script = document.createElement('script');
                script.src = 'https://cdnjs.cloudflare.com/ajax/libs/lazysizes/5.3.2/lazysizes.min.js';
                document.body.appendChild(script);
            }
        });

        // Newsletter form submission
        document.querySelector('.newsletter-form')?.addEventListener('submit', function(e) {
            e.preventDefault();
            const email = this.querySelector('input[type="email"]').value;
            
            // Simple email validation
            if (!email || !email.includes('@') || !email.includes('.')) {
                alert('Please enter a valid email address');
                return;
            }
            
            // Show success message
            alert('Thank you for subscribing to our newsletter!');
            this.reset();
        });

        // Add active class to current category in sidebar
        const currentCategory = '<?php echo addslashes($category); ?>';
        document.querySelectorAll('.category-link').forEach(link => {
            if (link.querySelector('.category-name').innerText.trim() === currentCategory) {
                link.classList.add('active');
            }
        });

        // Smooth scroll for anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });

        // Add reading progress indicator
        const progressBar = document.createElement('div');
        progressBar.style.cssText = `
            position: fixed;
            top: 0;
            left: 0;
            width: 0%;
            height: 3px;
            background: <?php echo $categoryColor; ?>;
            z-index: 9999;
            transition: width 0.3s ease;
        `;
        document.body.appendChild(progressBar);

        window.addEventListener('scroll', () => {
            const winScroll = document.body.scrollTop || document.documentElement.scrollTop;
            const height = document.documentElement.scrollHeight - document.documentElement.clientHeight;
            const scrolled = (winScroll / height) * 100;
            progressBar.style.width = scrolled + '%';
        });
    </script>
</body>
</html>