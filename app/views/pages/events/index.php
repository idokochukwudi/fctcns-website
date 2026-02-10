<?php
$baseUrl = $data['baseUrl'] ?? '';
$events = $data['events'] ?? [];
$featuredEvents = $data['featuredEvents'] ?? [];
$eventCategories = $data['eventCategories'] ?? [];
$archiveMonths = $data['archiveMonths'] ?? [];
$upcomingEvents = $data['upcomingEvents'] ?? [];
$pastEvents = $data['pastEvents'] ?? [];
$pagination = $data['pagination'] ?? [];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $data['pageTitle'] ?? 'Events - FCT College of Nursing Sciences'; ?></title>
    <meta name="description" content="<?php echo htmlspecialchars($data['pageDescription'] ?? ''); ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary-color: #4f46e5;
            --primary-dark: #4338ca;
            --secondary-color: #6b7280;
            --light-bg: #f9fafb;
            --border-color: #e5e8ed;
            --text-dark: #1a202c;
            --text-light: #718096;
            --success-color: #10b981;
            --warning-color: #f59e0b;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            color: var(--text-dark);
            line-height: 1.6;
        }
        
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }
        
        /* Header */
        .page-header {
            background: linear-gradient(135deg, #7c3aed, #5b21b6);
            color: white;
            padding: 60px 0;
            margin-bottom: 40px;
            text-align: center;
        }
        
        .page-title {
            font-size: 36px;
            font-weight: 700;
            margin-bottom: 16px;
        }
        
        .page-description {
            font-size: 18px;
            opacity: 0.9;
            max-width: 600px;
            margin: 0 auto;
        }
        
        /* Main Layout */
        .events-layout {
            display: grid;
            grid-template-columns: 1fr 300px;
            gap: 40px;
            margin-bottom: 60px;
        }
        
        @media (max-width: 992px) {
            .events-layout {
                grid-template-columns: 1fr;
            }
        }
        
        /* Events Grid */
        .events-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 30px;
            margin-bottom: 40px;
        }
        
        .event-card {
            background: white;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            position: relative;
        }
        
        .event-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 20px rgba(0,0,0,0.15);
        }
        
        .event-badge {
            position: absolute;
            top: 16px;
            right: 16px;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            z-index: 2;
        }
        
        .badge-upcoming {
            background: var(--success-color);
            color: white;
        }
        
        .badge-ongoing {
            background: var(--warning-color);
            color: white;
        }
        
        .badge-past {
            background: var(--secondary-color);
            color: white;
        }
        
        .event-image {
            width: 100%;
            height: 200px;
            object-fit: cover;
        }
        
        .event-content {
            padding: 24px;
        }
        
        .event-category {
            display: inline-block;
            padding: 4px 12px;
            background: var(--light-bg);
            color: #7c3aed;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            margin-bottom: 12px;
        }
        
        .event-title {
            font-size: 20px;
            font-weight: 700;
            margin-bottom: 12px;
            line-height: 1.4;
        }
        
        .event-title a {
            color: var(--text-dark);
            text-decoration: none;
        }
        
        .event-title a:hover {
            color: #7c3aed;
        }
        
        .event-excerpt {
            color: var(--text-light);
            margin-bottom: 16px;
            line-height: 1.6;
        }
        
        .event-meta {
            display: flex;
            flex-direction: column;
            gap: 8px;
            margin-bottom: 16px;
        }
        
        .event-date, .event-time, .event-location {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 14px;
            color: var(--text-light);
        }
        
        .event-actions {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 16px;
        }
        
        .event-btn {
            padding: 8px 20px;
            background: #7c3aed;
            color: white;
            border: none;
            border-radius: 6px;
            font-size: 14px;
            font-weight: 600;
            text-decoration: none;
            cursor: pointer;
            transition: background 0.3s ease;
        }
        
        .event-btn:hover {
            background: #5b21b6;
        }
        
        /* Featured Events */
        .featured-section {
            margin-bottom: 40px;
        }
        
        .section-title {
            font-size: 24px;
            font-weight: 700;
            margin-bottom: 24px;
            color: var(--text-dark);
            border-bottom: 3px solid #7c3aed;
            padding-bottom: 8px;
        }
        
        /* Event Details */
        .event-details {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 8px;
        }
        
        /* Sidebar */
        .sidebar {
            position: sticky;
            top: 20px;
        }
        
        .sidebar-widget {
            background: white;
            border-radius: 12px;
            padding: 24px;
            margin-bottom: 30px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }
        
        .widget-title {
            font-size: 18px;
            font-weight: 700;
            margin-bottom: 20px;
            color: var(--text-dark);
            display: flex;
            align-items: center;
            gap: 8px;
        }
        
        .category-list {
            list-style: none;
        }
        
        .category-item {
            margin-bottom: 12px;
        }
        
        .category-link {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 12px 16px;
            background: var(--light-bg);
            border-radius: 8px;
            color: var(--text-dark);
            text-decoration: none;
            transition: all 0.3s ease;
        }
        
        .category-link:hover {
            background: #7c3aed;
            color: white;
            transform: translateX(4px);
        }
        
        .category-count {
            background: white;
            color: #7c3aed;
            padding: 2px 8px;
            border-radius: 12px;
            font-size: 12px;
            font-weight: 600;
        }
        
        .upcoming-list, .past-list {
            list-style: none;
        }
        
        .upcoming-item, .past-item {
            display: flex;
            gap: 16px;
            margin-bottom: 20px;
            padding-bottom: 20px;
            border-bottom: 1px solid var(--border-color);
        }
        
        .upcoming-item:last-child, .past-item:last-child {
            margin-bottom: 0;
            padding-bottom: 0;
            border-bottom: none;
        }
        
        .upcoming-image, .past-image {
            width: 80px;
            height: 80px;
            border-radius: 8px;
            object-fit: cover;
            flex-shrink: 0;
        }
        
        .upcoming-content, .past-content {
            flex: 1;
        }
        
        .upcoming-title, .past-title {
            font-size: 16px;
            font-weight: 600;
            margin-bottom: 8px;
            line-height: 1.4;
        }
        
        .upcoming-title a, .past-title a {
            color: var(--text-dark);
            text-decoration: none;
        }
        
        .upcoming-title a:hover, .past-title a:hover {
            color: #7c3aed;
        }
        
        .upcoming-date, .past-date {
            font-size: 14px;
            color: var(--text-light);
        }
        
        /* Calendar Widget */
        .calendar-widget {
            text-align: center;
        }
        
        .calendar-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 16px;
        }
        
        .calendar-month {
            font-size: 18px;
            font-weight: 600;
            color: var(--text-dark);
        }
        
        .calendar-days {
            display: grid;
            grid-template-columns: repeat(7, 1fr);
            gap: 4px;
            margin-bottom: 8px;
        }
        
        .calendar-day {
            padding: 8px;
            font-size: 12px;
            font-weight: 600;
            color: var(--text-light);
        }
        
        .calendar-dates {
            display: grid;
            grid-template-columns: repeat(7, 1fr);
            gap: 4px;
        }
        
        .calendar-date {
            padding: 8px;
            border-radius: 6px;
            cursor: pointer;
            transition: background 0.3s ease;
        }
        
        .calendar-date:hover {
            background: var(--light-bg);
        }
        
        .calendar-date.has-event {
            background: #7c3aed;
            color: white;
        }
        
        .calendar-date.today {
            background: var(--primary-color);
            color: white;
        }
        
        /* Pagination */
        .pagination {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 8px;
            margin-top: 40px;
        }
        
        .pagination-btn {
            padding: 10px 18px;
            border: 1px solid var(--border-color);
            background: white;
            color: var(--text-dark);
            border-radius: 6px;
            text-decoration: none;
            transition: all 0.3s ease;
        }
        
        .pagination-btn:hover {
            background: #7c3aed;
            color: white;
            border-color: #7c3aed;
        }
        
        .pagination-btn.active {
            background: #7c3aed;
            color: white;
            border-color: #7c3aed;
        }
        
        /* Search */
        .search-box {
            position: relative;
        }
        
        .search-input {
            width: 100%;
            padding: 12px 16px 12px 44px;
            border: 1px solid var(--border-color);
            border-radius: 8px;
            font-size: 16px;
            transition: all 0.3s ease;
        }
        
        .search-input:focus {
            outline: none;
            border-color: #7c3aed;
            box-shadow: 0 0 0 3px rgba(124, 58, 237, 0.1);
        }
        
        .search-icon {
            position: absolute;
            left: 16px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--text-light);
        }
        
        /* Event Status Tabs */
        .event-tabs {
            display: flex;
            gap: 16px;
            margin-bottom: 30px;
            border-bottom: 2px solid var(--border-color);
        }
        
        .event-tab {
            padding: 12px 24px;
            background: none;
            border: none;
            font-size: 16px;
            font-weight: 600;
            color: var(--text-light);
            cursor: pointer;
            position: relative;
            transition: color 0.3s ease;
        }
        
        .event-tab:hover {
            color: #7c3aed;
        }
        
        .event-tab.active {
            color: #7c3aed;
        }
        
        .event-tab.active::after {
            content: '';
            position: absolute;
            bottom: -2px;
            left: 0;
            width: 100%;
            height: 2px;
            background: #7c3aed;
        }
        
        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 60px 20px;
            grid-column: 1 / -1;
        }
        
        .empty-state-icon {
            font-size: 48px;
            color: var(--text-light);
            margin-bottom: 20px;
        }
        
        .empty-state-title {
            font-size: 24px;
            font-weight: 600;
            margin-bottom: 12px;
            color: var(--text-dark);
        }
        
        .empty-state-description {
            color: var(--text-light);
            margin-bottom: 24px;
        }
        
        /* Responsive */
        @media (max-width: 768px) {
            .page-header {
                padding: 40px 0;
            }
            
            .page-title {
                font-size: 28px;
            }
            
            .events-grid {
                grid-template-columns: 1fr;
            }
            
            .events-layout {
                gap: 30px;
            }
            
            .event-tabs {
                overflow-x: auto;
                padding-bottom: 8px;
            }
        }
    </style>
</head>
<body>
    <!-- Header -->
    <header class="page-header">
        <div class="container">
            <h1 class="page-title">Events & Activities</h1>
            <p class="page-description">
                Discover upcoming events, workshops, seminars, and activities at FCT College of Nursing Sciences
            </p>
        </div>
    </header>
    
    <!-- Main Content -->
    <div class="container">
        <div class="events-layout">
            <!-- Main Content -->
            <main>
                <!-- Event Tabs -->
                <div class="event-tabs">
                    <button class="event-tab active" data-tab="upcoming">Upcoming Events</button>
                    <button class="event-tab" data-tab="past">Past Events</button>
                    <button class="event-tab" data-tab="all">All Events</button>
                </div>
                
                <!-- Featured Events -->
                <?php if (!empty($featuredEvents)): ?>
                <section class="featured-section">
                    <h2 class="section-title">Featured Events</h2>
                    <div class="events-grid">
                        <?php foreach ($featuredEvents as $featured): ?>
                        <article class="event-card">
                            <?php if ($featured['status'] == 'upcoming'): ?>
                            <span class="event-badge badge-upcoming">Upcoming</span>
                            <?php elseif ($featured['status'] == 'ongoing'): ?>
                            <span class="event-badge badge-ongoing">Ongoing</span>
                            <?php else: ?>
                            <span class="event-badge badge-past">Past</span>
                            <?php endif; ?>
                            
                            <?php if (!empty($featured['featured_image'])): ?>
                            <img src="<?php echo htmlspecialchars($featured['featured_image']); ?>" 
                                 alt="<?php echo htmlspecialchars($featured['title']); ?>" 
                                 class="event-image">
                            <?php endif; ?>
                            <div class="event-content">
                                <?php if (!empty($featured['category'])): ?>
                                <span class="event-category"><?php echo htmlspecialchars($featured['category']); ?></span>
                                <?php endif; ?>
                                <h3 class="event-title">
                                    <a href="<?php echo $baseUrl; ?>/events/<?php echo $featured['slug']; ?>">
                                        <?php echo htmlspecialchars($featured['title']); ?>
                                    </a>
                                </h3>
                                <div class="event-meta">
                                    <div class="event-details">
                                        <i class="far fa-calendar-alt"></i>
                                        <span><?php echo date('M d, Y', strtotime($featured['event_date'])); ?></span>
                                    </div>
                                    <div class="event-details">
                                        <i class="far fa-clock"></i>
                                        <span><?php echo htmlspecialchars($featured['event_time'] ?? 'TBA'); ?></span>
                                    </div>
                                    <?php if (!empty($featured['location'])): ?>
                                    <div class="event-details">
                                        <i class="fas fa-map-marker-alt"></i>
                                        <span><?php echo htmlspecialchars($featured['location']); ?></span>
                                    </div>
                                    <?php endif; ?>
                                </div>
                                <p class="event-excerpt">
                                    <?php echo htmlspecialchars(substr($featured['excerpt'] ?: strip_tags($featured['description']), 0, 120) . '...'); ?>
                                </p>
                                <div class="event-actions">
                                    <?php if ($featured['status'] == 'upcoming' && !empty($featured['registration_link'])): ?>
                                    <a href="<?php echo htmlspecialchars($featured['registration_link']); ?>" 
                                       class="event-btn" target="_blank">
                                        Register Now
                                    </a>
                                    <?php elseif ($featured['status'] == 'past' && !empty($featured['recording_link'])): ?>
                                    <a href="<?php echo htmlspecialchars($featured['recording_link']); ?>" 
                                       class="event-btn" target="_blank">
                                        Watch Recording
                                    </a>
                                    <?php else: ?>
                                    <a href="<?php echo $baseUrl; ?>/events/<?php echo $featured['slug']; ?>" 
                                       class="event-btn">
                                        View Details
                                    </a>
                                    <?php endif; ?>
                                    <span class="event-date">
                                        <i class="fas fa-users"></i>
                                        <?php echo number_format($featured['attendees_count'] ?? 0); ?> attending
                                    </span>
                                </div>
                            </div>
                        </article>
                        <?php endforeach; ?>
                    </div>
                </section>
                <?php endif; ?>
                
                <!-- Events Grid -->
                <section id="upcoming-events" class="event-tab-content active">
                    <h2 class="section-title">Upcoming Events</h2>
                    
                    <?php if (empty($upcomingEvents)): ?>
                    <div class="empty-state">
                        <div class="empty-state-icon">📅</div>
                        <h3 class="empty-state-title">No Upcoming Events</h3>
                        <p class="empty-state-description">
                            Check back later for upcoming events and activities.
                        </p>
                    </div>
                    <?php else: ?>
                    <div class="events-grid">
                        <?php foreach ($upcomingEvents as $event): ?>
                        <article class="event-card">
                            <span class="event-badge badge-upcoming">Upcoming</span>
                            
                            <?php if (!empty($event['featured_image'])): ?>
                            <img src="<?php echo htmlspecialchars($event['featured_image']); ?>" 
                                 alt="<?php echo htmlspecialchars($event['title']); ?>" 
                                 class="event-image">
                            <?php endif; ?>
                            <div class="event-content">
                                <?php if (!empty($event['category'])): ?>
                                <span class="event-category"><?php echo htmlspecialchars($event['category']); ?></span>
                                <?php endif; ?>
                                <h3 class="event-title">
                                    <a href="<?php echo $baseUrl; ?>/events/<?php echo $event['slug']; ?>">
                                        <?php echo htmlspecialchars($event['title']); ?>
                                    </a>
                                </h3>
                                <div class="event-meta">
                                    <div class="event-details">
                                        <i class="far fa-calendar-alt"></i>
                                        <span><?php echo date('M d, Y', strtotime($event['event_date'])); ?></span>
                                    </div>
                                    <div class="event-details">
                                        <i class="far fa-clock"></i>
                                        <span><?php echo htmlspecialchars($event['event_time'] ?? 'TBA'); ?></span>
                                    </div>
                                    <?php if (!empty($event['location'])): ?>
                                    <div class="event-details">
                                        <i class="fas fa-map-marker-alt"></i>
                                        <span><?php echo htmlspecialchars($event['location']); ?></span>
                                    </div>
                                    <?php endif; ?>
                                </div>
                                <p class="event-excerpt">
                                    <?php echo htmlspecialchars(substr($event['excerpt'] ?: strip_tags($event['description']), 0, 100) . '...'); ?>
                                </p>
                                <div class="event-actions">
                                    <?php if (!empty($event['registration_link'])): ?>
                                    <a href="<?php echo htmlspecialchars($event['registration_link']); ?>" 
                                       class="event-btn" target="_blank">
                                        Register Now
                                    </a>
                                    <?php else: ?>
                                    <a href="<?php echo $baseUrl; ?>/events/<?php echo $event['slug']; ?>" 
                                       class="event-btn">
                                        View Details
                                    </a>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </article>
                        <?php endforeach; ?>
                    </div>
                    <?php endif; ?>
                </section>
                
                <section id="past-events" class="event-tab-content" style="display: none;">
                    <h2 class="section-title">Past Events</h2>
                    
                    <?php if (empty($pastEvents)): ?>
                    <div class="empty-state">
                        <div class="empty-state-icon">📜</div>
                        <h3 class="empty-state-title">No Past Events</h3>
                        <p class="empty-state-description">
                            Browse upcoming events for future activities.
                        </p>
                    </div>
                    <?php else: ?>
                    <div class="events-grid">
                        <?php foreach ($pastEvents as $event): ?>
                        <article class="event-card">
                            <span class="event-badge badge-past">Past</span>
                            
                            <?php if (!empty($event['featured_image'])): ?>
                            <img src="<?php echo htmlspecialchars($event['featured_image']); ?>" 
                                 alt="<?php echo htmlspecialchars($event['title']); ?>" 
                                 class="event-image">
                            <?php endif; ?>
                            <div class="event-content">
                                <?php if (!empty($event['category'])): ?>
                                <span class="event-category"><?php echo htmlspecialchars($event['category']); ?></span>
                                <?php endif; ?>
                                <h3 class="event-title">
                                    <a href="<?php echo $baseUrl; ?>/events/<?php echo $event['slug']; ?>">
                                        <?php echo htmlspecialchars($event['title']); ?>
                                    </a>
                                </h3>
                                <div class="event-meta">
                                    <div class="event-details">
                                        <i class="far fa-calendar-alt"></i>
                                        <span><?php echo date('M d, Y', strtotime($event['event_date'])); ?></span>
                                    </div>
                                    <?php if (!empty($event['location'])): ?>
                                    <div class="event-details">
                                        <i class="fas fa-map-marker-alt"></i>
                                        <span><?php echo htmlspecialchars($event['location']); ?></span>
                                    </div>
                                    <?php endif; ?>
                                </div>
                                <p class="event-excerpt">
                                    <?php echo htmlspecialchars(substr($event['excerpt'] ?: strip_tags($event['description']), 0, 100) . '...'); ?>
                                </p>
                                <div class="event-actions">
                                    <?php if (!empty($event['recording_link'])): ?>
                                    <a href="<?php echo htmlspecialchars($event['recording_link']); ?>" 
                                       class="event-btn" target="_blank">
                                        Watch Recording
                                    </a>
                                    <?php else: ?>
                                    <a href="<?php echo $baseUrl; ?>/events/<?php echo $event['slug']; ?>" 
                                       class="event-btn">
                                        View Details
                                    </a>
                                    <?php endif; ?>
                                    <span class="event-date">
                                        <i class="fas fa-users"></i>
                                        <?php echo number_format($event['attendees_count'] ?? 0); ?> attended
                                    </span>
                                </div>
                            </div>
                        </article>
                        <?php endforeach; ?>
                    </div>
                    <?php endif; ?>
                </section>
                
                <section id="all-events" class="event-tab-content" style="display: none;">
                    <h2 class="section-title">All Events</h2>
                    
                    <?php if (empty($events)): ?>
                    <div class="empty-state">
                        <div class="empty-state-icon">🎯</div>
                        <h3 class="empty-state-title">No Events Found</h3>
                        <p class="empty-state-description">
                            There are no events scheduled at this time.
                        </p>
                    </div>
                    <?php else: ?>
                    <div class="events-grid">
                        <?php foreach ($events as $event): ?>
                        <article class="event-card">
                            <?php if ($event['status'] == 'upcoming'): ?>
                            <span class="event-badge badge-upcoming">Upcoming</span>
                            <?php elseif ($event['status'] == 'ongoing'): ?>
                            <span class="event-badge badge-ongoing">Ongoing</span>
                            <?php else: ?>
                            <span class="event-badge badge-past">Past</span>
                            <?php endif; ?>
                            
                            <?php if (!empty($event['featured_image'])): ?>
                            <img src="<?php echo htmlspecialchars($event['featured_image']); ?>" 
                                 alt="<?php echo htmlspecialchars($event['title']); ?>" 
                                 class="event-image">
                            <?php endif; ?>
                            <div class="event-content">
                                <?php if (!empty($event['category'])): ?>
                                <span class="event-category"><?php echo htmlspecialchars($event['category']); ?></span>
                                <?php endif; ?>
                                <h3 class="event-title">
                                    <a href="<?php echo $baseUrl; ?>/events/<?php echo $event['slug']; ?>">
                                        <?php echo htmlspecialchars($event['title']); ?>
                                    </a>
                                </h3>
                                <div class="event-meta">
                                    <div class="event-details">
                                        <i class="far fa-calendar-alt"></i>
                                        <span><?php echo date('M d, Y', strtotime($event['event_date'])); ?></span>
                                    </div>
                                    <?php if ($event['status'] == 'upcoming'): ?>
                                    <div class="event-details">
                                        <i class="far fa-clock"></i>
                                        <span><?php echo htmlspecialchars($event['event_time'] ?? 'TBA'); ?></span>
                                    </div>
                                    <?php endif; ?>
                                    <?php if (!empty($event['location'])): ?>
                                    <div class="event-details">
                                        <i class="fas fa-map-marker-alt"></i>
                                        <span><?php echo htmlspecialchars($event['location']); ?></span>
                                    </div>
                                    <?php endif; ?>
                                </div>
                                <p class="event-excerpt">
                                    <?php echo htmlspecialchars(substr($event['excerpt'] ?: strip_tags($event['description']), 0, 100) . '...'); ?>
                                </p>
                                <div class="event-actions">
                                    <?php if ($event['status'] == 'upcoming' && !empty($event['registration_link'])): ?>
                                    <a href="<?php echo htmlspecialchars($event['registration_link']); ?>" 
                                       class="event-btn" target="_blank">
                                        Register Now
                                    </a>
                                    <?php elseif ($event['status'] == 'past' && !empty($event['recording_link'])): ?>
                                    <a href="<?php echo htmlspecialchars($event['recording_link']); ?>" 
                                       class="event-btn" target="_blank">
                                        Watch Recording
                                    </a>
                                    <?php else: ?>
                                    <a href="<?php echo $baseUrl; ?>/events/<?php echo $event['slug']; ?>" 
                                       class="event-btn">
                                        View Details
                                    </a>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </article>
                        <?php endforeach; ?>
                    </div>
                    <?php endif; ?>
                    
                    <!-- Pagination -->
                    <?php if ($pagination['total'] > 1): ?>
                    <div class="pagination">
                        <?php if ($pagination['current'] > 1): ?>
                        <a href="?page=<?php echo $pagination['current'] - 1; ?>" class="pagination-btn">
                            <i class="fas fa-chevron-left"></i>
                        </a>
                        <?php endif; ?>
                        
                        <?php for ($i = 1; $i <= $pagination['total']; $i++): ?>
                        <a href="?page=<?php echo $i; ?>" 
                           class="pagination-btn <?php echo $i == $pagination['current'] ? 'active' : ''; ?>">
                            <?php echo $i; ?>
                        </a>
                        <?php endfor; ?>
                        
                        <?php if ($pagination['current'] < $pagination['total']): ?>
                        <a href="?page=<?php echo $pagination['current'] + 1; ?>" class="pagination-btn">
                            <i class="fas fa-chevron-right"></i>
                        </a>
                        <?php endif; ?>
                    </div>
                    <?php endif; ?>
                </section>
            </main>
            
            <!-- Sidebar -->
            <aside class="sidebar">
                <!-- Search -->
                <div class="sidebar-widget">
                    <div class="search-box">
                        <i class="fas fa-search search-icon"></i>
                        <form action="<?php echo $baseUrl; ?>/events/search" method="GET">
                            <input type="text" name="q" class="search-input" 
                                   placeholder="Search events..." value="<?php echo htmlspecialchars($_GET['q'] ?? ''); ?>">
                        </form>
                    </div>
                </div>
                
                <!-- Categories -->
                <div class="sidebar-widget">
                    <h3 class="widget-title">
                        <i class="fas fa-tags"></i> Event Categories
                    </h3>
                    <ul class="category-list">
                        <?php foreach ($eventCategories as $category => $count): ?>
                        <li class="category-item">
                            <a href="<?php echo $baseUrl; ?>/events/category/<?php echo urlencode($category); ?>" 
                               class="category-link">
                                <span><?php echo htmlspecialchars($category); ?></span>
                                <span class="category-count"><?php echo $count; ?></span>
                            </a>
                        </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
                
                <!-- Upcoming Events -->
                <div class="sidebar-widget">
                    <h3 class="widget-title">
                        <i class="fas fa-calendar-plus"></i> Upcoming Events
                    </h3>
                    <ul class="upcoming-list">
                        <?php foreach ($upcomingEvents as $upcoming): ?>
                        <li class="upcoming-item">
                            <?php if (!empty($upcoming['featured_image'])): ?>
                            <img src="<?php echo htmlspecialchars($upcoming['featured_image']); ?>" 
                                 alt="<?php echo htmlspecialchars($upcoming['title']); ?>" 
                                 class="upcoming-image">
                            <?php endif; ?>
                            <div class="upcoming-content">
                                <h4 class="upcoming-title">
                                    <a href="<?php echo $baseUrl; ?>/events/<?php echo $upcoming['slug']; ?>">
                                        <?php echo htmlspecialchars($upcoming['title']); ?>
                                    </a>
                                </h4>
                                <div class="upcoming-date">
                                    <?php echo date('M d, Y', strtotime($upcoming['event_date'])); ?>
                                </div>
                            </div>
                        </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
                
                <!-- Past Events -->
                <div class="sidebar-widget">
                    <h3 class="widget-title">
                        <i class="fas fa-history"></i> Recent Past Events
                    </h3>
                    <ul class="past-list">
                        <?php foreach ($pastEvents as $past): ?>
                        <li class="past-item">
                            <?php if (!empty($past['featured_image'])): ?>
                            <img src="<?php echo htmlspecialchars($past['featured_image']); ?>" 
                                 alt="<?php echo htmlspecialchars($past['title']); ?>" 
                                 class="past-image">
                            <?php endif; ?>
                            <div class="past-content">
                                <h4 class="past-title">
                                    <a href="<?php echo $baseUrl; ?>/events/<?php echo $past['slug']; ?>">
                                        <?php echo htmlspecialchars($past['title']); ?>
                                    </a>
                                </h4>
                                <div class="past-date">
                                    <?php echo date('M d, Y', strtotime($past['event_date'])); ?>
                                </div>
                            </div>
                        </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
                
                <!-- Calendar Widget -->
                <div class="sidebar-widget calendar-widget">
                    <h3 class="widget-title">
                        <i class="fas fa-calendar-alt"></i> Event Calendar
                    </h3>
                    <div id="event-calendar">
                        <!-- Calendar will be populated by JavaScript -->
                        <div class="calendar-header">
                            <button id="prev-month"><i class="fas fa-chevron-left"></i></button>
                            <div id="current-month" class="calendar-month">February 2026</div>
                            <button id="next-month"><i class="fas fa-chevron-right"></i></button>
                        </div>
                        <div class="calendar-days">
                            <div class="calendar-day">Sun</div>
                            <div class="calendar-day">Mon</div>
                            <div class="calendar-day">Tue</div>
                            <div class="calendar-day">Wed</div>
                            <div class="calendar-day">Thu</div>
                            <div class="calendar-day">Fri</div>
                            <div class="calendar-day">Sat</div>
                        </div>
                        <div id="calendar-dates" class="calendar-dates">
                            <!-- Dates will be populated by JavaScript -->
                        </div>
                    </div>
                </div>
                
                <!-- Subscribe to Events -->
                <div class="sidebar-widget">
                    <h3 class="widget-title">
                        <i class="fas fa-bell"></i> Event Notifications
                    </h3>
                    <p style="margin-bottom: 16px; color: var(--text-light);">
                        Get notified about upcoming events and activities.
                    </p>
                    <form action="<?php echo $baseUrl; ?>/events/subscribe" method="POST" style="display: grid; gap: 12px;">
                        <input type="email" name="email" placeholder="Your email address" 
                               class="form-control" style="padding: 12px; border-radius: 6px; border: 1px solid var(--border-color);" required>
                        <button type="submit" style="padding: 12px; background: #7c3aed; color: white; border: none; border-radius: 6px; font-weight: 600; cursor: pointer;">
                            Subscribe
                        </button>
                    </form>
                </div>
            </aside>
        </div>
    </div>
    
    <script>
        // Tab Switching
        document.querySelectorAll('.event-tab').forEach(tab => {
            tab.addEventListener('click', () => {
                // Update active tab
                document.querySelectorAll('.event-tab').forEach(t => t.classList.remove('active'));
                tab.classList.add('active');
                
                // Show active content
                const tabId = tab.dataset.tab;
                document.querySelectorAll('.event-tab-content').forEach(content => {
                    content.style.display = 'none';
                });
                
                if (tabId === 'upcoming') {
                    document.getElementById('upcoming-events').style.display = 'block';
                } else if (tabId === 'past') {
                    document.getElementById('past-events').style.display = 'block';
                } else if (tabId === 'all') {
                    document.getElementById('all-events').style.display = 'block';
                }
            });
        });
        
        // Calendar functionality
        let currentDate = new Date();
        
        function renderCalendar(date) {
            const monthNames = ["January", "February", "March", "April", "May", "June",
                "July", "August", "September", "October", "November", "December"];
            
            const year = date.getFullYear();
            const month = date.getMonth();
            
            // Update month header
            document.getElementById('current-month').textContent = 
                `${monthNames[month]} ${year}`;
            
            // Get first day of month
            const firstDay = new Date(year, month, 1);
            const lastDay = new Date(year, month + 1, 0);
            const daysInMonth = lastDay.getDate();
            const startingDay = firstDay.getDay();
            
            // Clear previous dates
            const calendarDates = document.getElementById('calendar-dates');
            calendarDates.innerHTML = '';
            
            // Add empty cells for days before first day of month
            for (let i = 0; i < startingDay; i++) {
                const emptyCell = document.createElement('div');
                emptyCell.className = 'calendar-date';
                calendarDates.appendChild(emptyCell);
            }
            
            // Add date cells
            for (let day = 1; day <= daysInMonth; day++) {
                const dateCell = document.createElement('div');
                dateCell.className = 'calendar-date';
                dateCell.textContent = day;
                
                const cellDate = new Date(year, month, day);
                const today = new Date();
                
                // Check if today
                if (cellDate.toDateString() === today.toDateString()) {
                    dateCell.classList.add('today');
                }
                
                // Check if has events (in real implementation, this would check against actual event dates)
                // This is a placeholder for demonstration
                if (Math.random() > 0.7) {
                    dateCell.classList.add('has-event');
                    dateCell.title = 'Has events';
                    dateCell.addEventListener('click', () => {
                        window.location.href = `<?php echo $baseUrl; ?>/events/date/${year}-${String(month + 1).padStart(2, '0')}-${String(day).padStart(2, '0')}`;
                    });
                }
                
                calendarDates.appendChild(dateCell);
            }
        }
        
        // Initialize calendar
        document.addEventListener('DOMContentLoaded', () => {
            renderCalendar(currentDate);
            
            // Previous month button
            document.getElementById('prev-month').addEventListener('click', () => {
                currentDate.setMonth(currentDate.getMonth() - 1);
                renderCalendar(currentDate);
            });
            
            // Next month button
            document.getElementById('next-month').addEventListener('click', () => {
                currentDate.setMonth(currentDate.getMonth() + 1);
                renderCalendar(currentDate);
            });
        });
        
        // Event registration
        document.querySelectorAll('.event-btn').forEach(btn => {
            if (btn.textContent.includes('Register')) {
                btn.addEventListener('click', function(e) {
                    if (!this.getAttribute('href') || this.getAttribute('href') === '#') {
                        e.preventDefault();
                        // Show registration modal or form
                        alert('Registration will open soon. Please check back later.');
                    }
                });
            }
        });
        
        // Event filtering
        const searchForm = document.querySelector('form[action*="events/search"]');
        if (searchForm) {
            const searchInput = searchForm.querySelector('input[name="q"]');
            searchInput.addEventListener('keypress', function(e) {
                if (e.key === 'Enter') {
                    if (this.value.trim().length < 2) {
                        e.preventDefault();
                        alert('Please enter at least 2 characters to search');
                    }
                }
            });
        }
        
        // Smooth scrolling for anchor links
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
        
        // Back to top button
        const backToTop = document.createElement('button');
        backToTop.innerHTML = '<i class="fas fa-arrow-up"></i>';
        backToTop.style.cssText = `
            position: fixed;
            bottom: 20px;
            right: 20px;
            width: 50px;
            height: 50px;
            background: #7c3aed;
            color: white;
            border: none;
            border-radius: 50%;
            cursor: pointer;
            display: none;
            align-items: center;
            justify-content: center;
            font-size: 18px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
            transition: all 0.3s ease;
            z-index: 1000;
        `;
        
        backToTop.addEventListener('mouseenter', () => {
            backToTop.style.transform = 'scale(1.1)';
        });
        
        backToTop.addEventListener('mouseleave', () => {
            backToTop.style.transform = 'scale(1)';
        });
        
        backToTop.addEventListener('click', () => {
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        });
        
        document.body.appendChild(backToTop);
        
        window.addEventListener('scroll', () => {
            if (window.scrollY > 300) {
                backToTop.style.display = 'flex';
            } else {
                backToTop.style.display = 'none';
            }
        });
        
        // Countdown for upcoming events (optional enhancement)
        function updateEventCountdowns() {
            document.querySelectorAll('.badge-upcoming').forEach(badge => {
                const eventCard = badge.closest('.event-card');
                const dateElement = eventCard.querySelector('.event-details span');
                if (dateElement) {
                    const eventDate = new Date(dateElement.textContent);
                    const today = new Date();
                    const diffTime = eventDate - today;
                    const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
                    
                    if (diffDays === 0) {
                        badge.textContent = 'Today';
                        badge.classList.remove('badge-upcoming');
                        badge.classList.add('badge-ongoing');
                    } else if (diffDays < 0) {
                        badge.textContent = 'Past';
                        badge.classList.remove('badge-upcoming');
                        badge.classList.add('badge-past');
                    } else if (diffDays <= 7) {
                        badge.textContent = `In ${diffDays} day${diffDays > 1 ? 's' : ''}`;
                    }
                }
            });
        }
        
        // Initialize countdowns
        document.addEventListener('DOMContentLoaded', updateEventCountdowns);
    </script>
</body>
</html>