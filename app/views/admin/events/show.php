<?php
$baseUrl = $data['baseUrl'] ?? '';
$event = $data['event'] ?? [];
$csrfToken = bin2hex(random_bytes(32));
$_SESSION['csrf_token'] = $csrfToken;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($event['title']); ?> - Event Details - Admin Dashboard</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary-color: #4f46e5;
            --primary-dark: #4338ca;
            --secondary-color: #6b7280;
            --success-color: #10b981;
            --warning-color: #f59e0b;
            --danger-color: #ef4444;
            --info-color: #3b82f6;
            --light-bg: #f9fafb;
            --border-color: #e5e7eb;
            --text-dark: #111827;
            --text-light: #6b7280;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            background-color: var(--light-bg);
            color: var(--text-dark);
            line-height: 1.5;
        }
        
        .admin-container {
            padding: 20px;
            max-width: 1200px;
            margin: 0 auto;
        }
        
        /* Header */
        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            flex-wrap: wrap;
            gap: 20px;
        }
        
        .page-title {
            font-size: 28px;
            font-weight: 700;
            color: var(--text-dark);
            flex: 1;
        }
        
        /* Info Cards */
        .info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }
        
        .info-card {
            background: white;
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        }
        
        .info-card h3 {
            font-size: 14px;
            color: var(--text-light);
            text-transform: uppercase;
            letter-spacing: 0.05em;
            margin-bottom: 12px;
        }
        
        .info-content {
            font-size: 16px;
            color: var(--text-dark);
            font-weight: 500;
        }
        
        /* Badges */
        .badges {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            margin-top: 10px;
        }
        
        .badge {
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }
        
        .badge-success {
            background-color: #d1fae5;
            color: var(--success-color);
        }
        
        .badge-warning {
            background-color: #fef3c7;
            color: var(--warning-color);
        }
        
        .badge-info {
            background-color: #dbeafe;
            color: var(--info-color);
        }
        
        .badge-danger {
            background-color: #fee2e2;
            color: var(--danger-color);
        }
        
        .badge-primary {
            background-color: #e0e7ff;
            color: var(--primary-color);
        }
        
        /* Event Content */
        .event-container {
            background: white;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
            margin-bottom: 30px;
        }
        
        .event-header {
            padding: 30px;
            border-bottom: 1px solid var(--border-color);
        }
        
        .event-title {
            font-size: 32px;
            font-weight: 700;
            margin-bottom: 16px;
            color: var(--text-dark);
        }
        
        .event-meta {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            color: var(--text-light);
            font-size: 14px;
        }
        
        .event-body {
            padding: 30px;
        }
        
        .event-content {
            line-height: 1.8;
            font-size: 16px;
        }
        
        .event-content img {
            max-width: 100%;
            height: auto;
            border-radius: 8px;
            margin: 20px 0;
        }
        
        .event-content h2, 
        .event-content h3, 
        .event-content h4 {
            margin: 24px 0 16px 0;
            color: var(--text-dark);
        }
        
        .event-content p {
            margin-bottom: 16px;
        }
        
        .event-content ul, 
        .event-content ol {
            margin-left: 20px;
            margin-bottom: 16px;
        }
        
        .event-content blockquote {
            border-left: 4px solid var(--primary-color);
            padding-left: 20px;
            margin: 20px 0;
            font-style: italic;
            color: var(--text-light);
        }
        
        /* Actions */
        .action-buttons {
            display: flex;
            gap: 12px;
            flex-wrap: wrap;
        }
        
        .btn {
            padding: 12px 24px;
            border-radius: 6px;
            text-decoration: none;
            font-weight: 500;
            font-size: 14px;
            border: none;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: all 0.3s ease;
        }
        
        .btn-primary {
            background-color: var(--primary-color);
            color: white;
        }
        
        .btn-primary:hover {
            background-color: var(--primary-dark);
            transform: translateY(-2px);
        }
        
        .btn-secondary {
            background-color: var(--secondary-color);
            color: white;
        }
        
        .btn-outline {
            background-color: white;
            color: var(--primary-color);
            border: 1px solid var(--primary-color);
        }
        
        .btn-outline:hover {
            background-color: var(--light-bg);
        }
        
        .btn-danger {
            background-color: var(--danger-color);
            color: white;
        }
        
        .btn-danger:hover {
            background-color: #dc2626;
        }
        
        /* Event Details */
        .event-details-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin: 20px 0;
        }
        
        .event-detail-card {
            background: #f0f9ff;
            border-radius: 8px;
            padding: 20px;
            border-left: 4px solid var(--info-color);
        }
        
        .event-detail-card h4 {
            color: var(--info-color);
            margin-bottom: 12px;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        
        .detail-item {
            margin-bottom: 10px;
            display: flex;
            flex-direction: column;
            gap: 4px;
        }
        
        .detail-label {
            font-size: 12px;
            color: var(--text-light);
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }
        
        .detail-value {
            font-weight: 500;
            color: var(--text-dark);
            font-size: 14px;
        }
        
        /* Registration Info */
        .registration-info {
            background: #f0f9ff;
            border-radius: 8px;
            padding: 20px;
            margin: 20px 0;
        }
        
        .registration-info h3 {
            color: var(--info-color);
            margin-bottom: 16px;
        }
        
        .registration-stats {
            display: flex;
            gap: 20px;
            flex-wrap: wrap;
            margin-top: 15px;
        }
        
        .stat-box {
            background: white;
            padding: 15px;
            border-radius: 6px;
            text-align: center;
            flex: 1;
            min-width: 120px;
        }
        
        .stat-number {
            font-size: 24px;
            font-weight: 700;
            color: var(--primary-color);
        }
        
        .stat-label {
            font-size: 12px;
            color: var(--text-light);
            text-transform: uppercase;
            margin-top: 5px;
        }
        
        /* Stats */
        .stats {
            display: flex;
            gap: 20px;
            flex-wrap: wrap;
            margin-top: 20px;
        }
        
        .stat-item {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 10px 16px;
            background: var(--light-bg);
            border-radius: 8px;
        }
        
        .stat-icon {
            color: var(--primary-color);
        }
        
        /* Responsive */
        @media (max-width: 768px) {
            .page-header {
                flex-direction: column;
                align-items: flex-start;
            }
            
            .action-buttons {
                width: 100%;
            }
            
            .btn {
                flex: 1;
                justify-content: center;
            }
            
            .event-title {
                font-size: 24px;
            }
            
            .info-grid {
                grid-template-columns: 1fr;
            }
            
            .event-details-grid {
                grid-template-columns: 1fr;
            }
        }
        
        @media (max-width: 480px) {
            .event-header,
            .event-body {
                padding: 20px;
            }
            
            .event-meta {
                flex-direction: column;
                gap: 8px;
            }
            
            .stats {
                flex-direction: column;
            }
        }
    </style>
</head>
<body>
    <div class="admin-container">
        <!-- Page Header -->
        <div class="page-header">
            <h1 class="page-title">Event Details</h1>
            <div class="action-buttons">
                <a href="<?php echo $baseUrl; ?>/admin/events" class="btn btn-outline">
                    <i class="fas fa-arrow-left"></i> Back to Events
                </a>
                <a href="<?php echo $baseUrl; ?>/admin/events/<?php echo $event['id']; ?>/edit" class="btn btn-primary">
                    <i class="fas fa-edit"></i> Edit Event
                </a>
                <a href="<?php echo $baseUrl; ?>/events/<?php echo $event['slug']; ?>" 
                   target="_blank" class="btn btn-secondary">
                    <i class="fas fa-eye"></i> View Public
                </a>
                <form method="POST" action="<?php echo $baseUrl; ?>/admin/events/<?php echo $event['id']; ?>/delete" 
                      style="display: inline;" onsubmit="return confirm('Are you sure you want to delete this event?');">
                    <input type="hidden" name="csrf_token" value="<?php echo $csrfToken; ?>">
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-trash"></i> Delete
                    </button>
                </form>
            </div>
        </div>
        
        <!-- Info Cards -->
        <div class="info-grid">
            <div class="info-card">
                <h3>Event Information</h3>
                <div class="info-content">
                    <div><strong>ID:</strong> #<?php echo $event['id']; ?></div>
                    <div><strong>Event Type:</strong> 
                        <span class="badge <?php 
                            if ($event['event_type'] === 'conference') echo 'badge-info';
                            elseif ($event['event_type'] === 'workshop') echo 'badge-success';
                            elseif ($event['event_type'] === 'seminar') echo 'badge-primary';
                            else echo 'badge-secondary';
                        ?>">
                            <?php echo ucfirst($event['event_type'] ?? 'Event'); ?>
                        </span>
                    </div>
                    <div><strong>Category:</strong> <?php echo htmlspecialchars($event['category'] ?? 'General'); ?></div>
                    <div><strong>Organizer:</strong> <?php echo htmlspecialchars($event['organizer_name'] ?? $event['author_name'] ?? 'Unknown'); ?></div>
                </div>
            </div>
            
            <div class="info-card">
                <h3>Event Status</h3>
                <div class="info-content">
                    <div class="badges">
                        <?php 
                        $eventDate = strtotime($event['event_date'] ?? '');
                        $currentDate = time();
                        $isPastEvent = $eventDate && $eventDate < $currentDate;
                        $isUpcoming = $eventDate && $eventDate > $currentDate;
                        ?>
                        
                        <?php if ($event['is_published']): ?>
                        <span class="badge badge-success">Published</span>
                        <?php else: ?>
                        <span class="badge badge-warning">Draft</span>
                        <?php endif; ?>
                        
                        <?php if ($event['is_featured']): ?>
                        <span class="badge badge-info">Featured</span>
                        <?php endif; ?>
                        
                        <?php if ($isPastEvent): ?>
                        <span class="badge badge-secondary">Past Event</span>
                        <?php elseif ($isUpcoming): ?>
                        <span class="badge badge-success">Upcoming</span>
                        <?php endif; ?>
                        
                        <?php if (!empty($event['is_free'])): ?>
                        <span class="badge badge-success">Free</span>
                        <?php elseif (!empty($event['ticket_price'])): ?>
                        <span class="badge badge-primary">Paid</span>
                        <?php endif; ?>
                    </div>
                    
                    <?php if ($event['event_date']): ?>
                    <div><strong>Event Date:</strong> <?php echo date('M d, Y', strtotime($event['event_date'])); ?></div>
                    <?php endif; ?>
                    
                    <?php if ($event['event_end_date']): ?>
                    <div><strong>End Date:</strong> <?php echo date('M d, Y', strtotime($event['event_end_date'])); ?></div>
                    <?php endif; ?>
                    
                    <div><strong>Created:</strong> <?php echo date('M d, Y H:i', strtotime($event['created_at'])); ?></div>
                    <div><strong>Updated:</strong> <?php echo date('M d, Y H:i', strtotime($event['updated_at'])); ?></div>
                </div>
            </div>
            
            <div class="info-card">
                <h3>Event Statistics</h3>
                <div class="info-content">
                    <div><strong>Views:</strong> <?php echo number_format($event['views_count'] ?? 0); ?></div>
                    <div><strong>Registrations:</strong> <?php echo number_format($event['registrations_count'] ?? 0); ?></div>
                    <div><strong>Capacity:</strong> 
                        <?php 
                        $capacity = $event['max_attendees'] ?? 0;
                        $registrations = $event['registrations_count'] ?? 0;
                        if ($capacity > 0) {
                            echo number_format($registrations) . '/' . number_format($capacity);
                            $percentage = ($registrations / $capacity) * 100;
                            echo ' (' . round($percentage) . '%)';
                        } else {
                            echo 'Unlimited';
                        }
                        ?>
                    </div>
                    <div><strong>Shares:</strong> <?php echo number_format($event['shares_count'] ?? 0); ?></div>
                </div>
            </div>
        </div>
        
        <!-- Event Content -->
        <div class="event-container">
            <div class="event-header">
                <h1 class="event-title"><?php echo htmlspecialchars($event['title']); ?></h1>
                
                <div class="event-meta">
                    <span><i class="fas fa-user"></i> <?php echo htmlspecialchars($event['organizer_name'] ?? $event['author_name'] ?? 'Unknown'); ?></span>
                    <span><i class="fas fa-calendar"></i> <?php echo date('F j, Y', strtotime($event['created_at'])); ?></span>
                    <span><i class="fas fa-folder"></i> <?php echo htmlspecialchars($event['category'] ?? 'General'); ?></span>
                    <span><i class="fas fa-eye"></i> <?php echo number_format($event['views_count'] ?? 0); ?> views</span>
                </div>
                
                <!-- Event Details Cards -->
                <div class="event-details-grid">
                    <?php if (!empty($event['event_date'])): ?>
                    <div class="event-detail-card">
                        <h4><i class="fas fa-calendar-alt"></i> Date & Time</h4>
                        <div class="detail-item">
                            <span class="detail-label">Start Date</span>
                            <span class="detail-value">
                                <?php echo date('F j, Y', strtotime($event['event_date'])); ?>
                                <?php if (!empty($event['event_time'])): ?>
                                at <?php echo date('h:i A', strtotime($event['event_time'])); ?>
                                <?php endif; ?>
                            </span>
                        </div>
                        
                        <?php if (!empty($event['event_end_date'])): ?>
                        <div class="detail-item">
                            <span class="detail-label">End Date</span>
                            <span class="detail-value">
                                <?php echo date('F j, Y', strtotime($event['event_end_date'])); ?>
                                <?php if (!empty($event['event_end_time'])): ?>
                                at <?php echo date('h:i A', strtotime($event['event_end_time'])); ?>
                                <?php endif; ?>
                            </span>
                        </div>
                        <?php endif; ?>
                        
                        <?php 
                        $eventDateTime = strtotime($event['event_date'] . ' ' . ($event['event_time'] ?? ''));
                        if ($eventDateTime):
                            $timeLeft = $eventDateTime - time();
                            if ($timeLeft > 0):
                                $daysLeft = floor($timeLeft / (60 * 60 * 24));
                                if ($daysLeft > 0):
                        ?>
                        <div class="detail-item">
                            <span class="detail-label">Time Until Event</span>
                            <span class="detail-value"><?php echo $daysLeft; ?> days</span>
                        </div>
                        <?php endif; endif; endif; ?>
                    </div>
                    <?php endif; ?>
                    
                    <?php if (!empty($event['event_location']) || !empty($event['event_venue'])): ?>
                    <div class="event-detail-card">
                        <h4><i class="fas fa-map-marker-alt"></i> Location</h4>
                        <?php if (!empty($event['event_venue'])): ?>
                        <div class="detail-item">
                            <span class="detail-label">Venue</span>
                            <span class="detail-value"><?php echo htmlspecialchars($event['event_venue']); ?></span>
                        </div>
                        <?php endif; ?>
                        
                        <?php if (!empty($event['event_location'])): ?>
                        <div class="detail-item">
                            <span class="detail-label">Address</span>
                            <span class="detail-value"><?php echo htmlspecialchars($event['event_location']); ?></span>
                        </div>
                        <?php endif; ?>
                        
                        <?php if (!empty($event['city'])): ?>
                        <div class="detail-item">
                            <span class="detail-label">City</span>
                            <span class="detail-value"><?php echo htmlspecialchars($event['city']); ?></span>
                        </div>
                        <?php endif; ?>
                        
                        <?php if (!empty($event['country'])): ?>
                        <div class="detail-item">
                            <span class="detail-label">Country</span>
                            <span class="detail-value"><?php echo htmlspecialchars($event['country']); ?></span>
                        </div>
                        <?php endif; ?>
                    </div>
                    <?php endif; ?>
                    
                    <?php if (!empty($event['ticket_price']) || !empty($event['max_attendees'])): ?>
                    <div class="event-detail-card">
                        <h4><i class="fas fa-ticket-alt"></i> Registration</h4>
                        
                        <?php if (isset($event['is_free']) && $event['is_free']): ?>
                        <div class="detail-item">
                            <span class="detail-label">Ticket Type</span>
                            <span class="detail-value badge badge-success">Free Event</span>
                        </div>
                        <?php elseif (!empty($event['ticket_price'])): ?>
                        <div class="detail-item">
                            <span class="detail-label">Ticket Price</span>
                            <span class="detail-value">$<?php echo number_format($event['ticket_price'], 2); ?></span>
                        </div>
                        <?php endif; ?>
                        
                        <?php if (!empty($event['max_attendees'])): ?>
                        <div class="detail-item">
                            <span class="detail-label">Capacity</span>
                            <span class="detail-value">
                                <?php echo number_format($event['max_attendees']); ?> attendees
                                <?php 
                                $registrations = $event['registrations_count'] ?? 0;
                                $capacity = $event['max_attendees'];
                                $remaining = $capacity - $registrations;
                                if ($remaining > 0) {
                                    echo ' (' . number_format($remaining) . ' spots remaining)';
                                } else {
                                    echo ' (Sold Out)';
                                }
                                ?>
                            </span>
                        </div>
                        <?php else: ?>
                        <div class="detail-item">
                            <span class="detail-label">Capacity</span>
                            <span class="detail-value">Unlimited</span>
                        </div>
                        <?php endif; ?>
                        
                        <?php if (!empty($event['registration_deadline'])): ?>
                        <div class="detail-item">
                            <span class="detail-label">Registration Deadline</span>
                            <span class="detail-value">
                                <?php echo date('F j, Y', strtotime($event['registration_deadline'])); ?>
                                <?php if (!empty($event['registration_deadline_time'])): ?>
                                at <?php echo date('h:i A', strtotime($event['registration_deadline_time'])); ?>
                                <?php endif; ?>
                            </span>
                        </div>
                        <?php endif; ?>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
            
            <div class="event-body">
                <?php if (!empty($event['featured_image'])): ?>
                <div style="margin-bottom: 30px; text-align: center;">
                    <img src="<?php echo htmlspecialchars($event['featured_image']); ?>" 
                         alt="<?php echo htmlspecialchars($event['title']); ?>" 
                         style="max-height: 400px; width: auto; border-radius: 8px;">
                </div>
                <?php endif; ?>
                
                <?php if (!empty($event['excerpt'])): ?>
                <div style="background: var(--light-bg); padding: 20px; border-radius: 8px; margin-bottom: 30px; font-style: italic; color: var(--text-light);">
                    <?php echo htmlspecialchars($event['excerpt']); ?>
                </div>
                <?php endif; ?>
                
                <div class="event-content">
                    <?php echo $event['content']; ?>
                </div>
                
                <?php if (!empty($event['tags'])): ?>
                <div style="margin-top: 30px; padding-top: 20px; border-top: 1px solid var(--border-color);">
                    <strong>Tags:</strong>
                    <div style="margin-top: 10px; display: flex; flex-wrap: wrap; gap: 8px;">
                        <?php 
                        $tags = explode(',', $event['tags']);
                        foreach ($tags as $tag):
                            $tag = trim($tag);
                            if (!empty($tag)):
                        ?>
                        <span style="background: var(--light-bg); padding: 4px 12px; border-radius: 20px; font-size: 14px;">
                            <?php echo htmlspecialchars($tag); ?>
                        </span>
                        <?php 
                            endif;
                        endforeach; 
                        ?>
                    </div>
                </div>
                <?php endif; ?>
            </div>
        </div>
        
        <!-- Registration Information (if applicable) -->
        <?php if (!empty($event['max_attendees']) || !empty($event['ticket_price'])): ?>
        <div class="event-container">
            <div class="event-header">
                <h3><i class="fas fa-users"></i> Registration Information</h3>
            </div>
            
            <div class="event-body">
                <div class="registration-info">
                    <div class="registration-stats">
                        <div class="stat-box">
                            <div class="stat-number"><?php echo number_format($event['registrations_count'] ?? 0); ?></div>
                            <div class="stat-label">Registrations</div>
                        </div>
                        
                        <?php if (!empty($event['max_attendees'])): ?>
                        <div class="stat-box">
                            <div class="stat-number"><?php echo number_format($event['max_attendees']); ?></div>
                            <div class="stat-label">Total Capacity</div>
                        </div>
                        
                        <div class="stat-box">
                            <div class="stat-number">
                                <?php 
                                $remaining = ($event['max_attendees'] ?? 0) - ($event['registrations_count'] ?? 0);
                                echo number_format(max(0, $remaining));
                                ?>
                            </div>
                            <div class="stat-label">Spots Available</div>
                        </div>
                        <?php endif; ?>
                        
                        <?php if (!empty($event['ticket_price']) && !empty($event['registrations_count'])): ?>
                        <div class="stat-box">
                            <div class="stat-number">$<?php echo number_format($event['ticket_price'] * $event['registrations_count'], 2); ?></div>
                            <div class="stat-label">Total Revenue</div>
                        </div>
                        <?php endif; ?>
                    </div>
                    
                    <?php if (!empty($event['registration_instructions'])): ?>
                    <div style="margin-top: 20px;">
                        <strong>Registration Instructions:</strong>
                        <div style="margin-top: 10px; padding: 15px; background: white; border-radius: 6px;">
                            <?php echo nl2br(htmlspecialchars($event['registration_instructions'])); ?>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <?php endif; ?>
        
        <!-- SEO Information -->
        <div class="event-container">
            <div class="event-header">
                <h3><i class="fas fa-search"></i> SEO Information</h3>
            </div>
            
            <div class="event-body">
                <div style="display: grid; gap: 16px;">
                    <div>
                        <strong>Meta Title:</strong>
                        <div style="margin-top: 8px; padding: 12px; background: var(--light-bg); border-radius: 6px; font-family: monospace;">
                            <?php echo htmlspecialchars($event['meta_title'] ?: $event['title']); ?>
                        </div>
                    </div>
                    
                    <div>
                        <strong>Meta Description:</strong>
                        <div style="margin-top: 8px; padding: 12px; background: var(--light-bg); border-radius: 6px; font-family: monospace;">
                            <?php echo htmlspecialchars($event['meta_description'] ?: ($event['excerpt'] ?: substr(strip_tags($event['content']), 0, 150) . '...')); ?>
                        </div>
                    </div>
                    
                    <?php if (!empty($event['meta_keywords'])): ?>
                    <div>
                        <strong>Meta Keywords:</strong>
                        <div style="margin-top: 8px; padding: 12px; background: var(--light-bg); border-radius: 6px; font-family: monospace;">
                            <?php echo htmlspecialchars($event['meta_keywords']); ?>
                        </div>
                    </div>
                    <?php endif; ?>
                    
                    <div>
                        <strong>Event URL:</strong>
                        <div style="margin-top: 8px; padding: 12px; background: var(--light-bg); border-radius: 6px; font-family: monospace; word-break: break-all;">
                            <?php echo $baseUrl; ?>/events/<?php echo htmlspecialchars($event['slug']); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Quick Actions -->
        <div style="display: flex; justify-content: center; gap: 12px; margin-top: 30px; flex-wrap: wrap;">
            <form method="POST" action="<?php echo $baseUrl; ?>/admin/events/<?php echo $event['id']; ?>/toggle-publish" style="display: inline;">
                <input type="hidden" name="csrf_token" value="<?php echo $csrfToken; ?>">
                <button type="submit" class="btn <?php echo $event['is_published'] ? 'btn-warning' : 'btn-success'; ?>" 
                        onclick="return confirm('Are you sure you want to <?php echo $event['is_published'] ? 'unpublish' : 'publish'; ?> this event?');">
                    <i class="fas fa-<?php echo $event['is_published'] ? 'eye-slash' : 'eye'; ?>"></i>
                    <?php echo $event['is_published'] ? 'Unpublish' : 'Publish'; ?>
                </button>
            </form>
            
            <form method="POST" action="<?php echo $baseUrl; ?>/admin/events/<?php echo $event['id']; ?>/toggle-feature" style="display: inline;">
                <input type="hidden" name="csrf_token" value="<?php echo $csrfToken; ?>">
                <button type="submit" class="btn <?php echo $event['is_featured'] ? 'btn-outline' : 'btn-info'; ?>"
                        onclick="return confirm('Are you sure you want to <?php echo $event['is_featured'] ? 'remove from' : 'add to'; ?> featured?');">
                    <i class="fas fa-star"></i>
                    <?php echo $event['is_featured'] ? 'Unfeature' : 'Feature'; ?>
                </button>
            </form>
            
            <button class="btn btn-secondary" onclick="window.print()">
                <i class="fas fa-print"></i> Print
            </button>
            
            <a href="<?php echo $baseUrl; ?>/admin/events/<?php echo $event['id']; ?>/registrations" class="btn btn-primary">
                <i class="fas fa-users"></i> View Registrations
            </a>
        </div>
    </div>
    
    <script>
        // Add print styles
        const style = document.createElement('style');
        style.textContent = `
            @media print {
                .admin-container {
                    padding: 0;
                }
                
                .page-header,
                .action-buttons,
                .info-grid,
                .btn,
                form {
                    display: none !important;
                }
                
                .event-container {
                    box-shadow: none;
                    border: 1px solid #ddd;
                }
                
                .event-content {
                    font-size: 14px;
                }
                
                a {
                    color: black !important;
                    text-decoration: none !important;
                }
            }
        `;
        document.head.appendChild(style);
        
        // Copy URL to clipboard
        function copyUrl() {
            const url = '<?php echo $baseUrl; ?>/events/<?php echo $event['slug']; ?>';
            navigator.clipboard.writeText(url).then(() => {
                alert('Event URL copied to clipboard!');
            });
        }
        
        // Share event
        function shareOnFacebook() {
            const url = encodeURIComponent('<?php echo $baseUrl; ?>/events/<?php echo $event['slug']; ?>');
            window.open(`https://www.facebook.com/sharer/sharer.php?u=${url}`, '_blank');
        }
        
        function shareOnTwitter() {
            const url = encodeURIComponent('<?php echo $baseUrl; ?>/events/<?php echo $event['slug']; ?>');
            const text = encodeURIComponent('<?php echo htmlspecialchars($event['title']); ?>');
            window.open(`https://twitter.com/intent/tweet?url=${url}&text=${text}`, '_blank');
        }
        
        // Calendar download
        function downloadCalendar() {
            const eventDate = '<?php echo $event['event_date']; ?>';
            const eventTime = '<?php echo $event['event_time']; ?>';
            const title = '<?php echo htmlspecialchars($event['title']); ?>';
            const location = '<?php echo htmlspecialchars($event['event_location'] ?? ''); ?>';
            const description = '<?php echo htmlspecialchars(strip_tags($event['excerpt'] ?? $event['content'])); ?>';
            
            // Create ICS file
            const icsContent = [
                'BEGIN:VCALENDAR',
                'VERSION:2.0',
                'BEGIN:VEVENT',
                'DTSTART:' + formatICalDate(eventDate, eventTime),
                'DTEND:' + formatICalDate(eventDate, eventTime, 2), // 2 hours duration
                'SUMMARY:' + title,
                'LOCATION:' + location,
                'DESCRIPTION:' + description,
                'END:VEVENT',
                'END:VCALENDAR'
            ].join('\n');
            
            const blob = new Blob([icsContent], { type: 'text/calendar' });
            const url = URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.href = url;
            a.download = 'event.ics';
            document.body.appendChild(a);
            a.click();
            document.body.removeChild(a);
            URL.revokeObjectURL(url);
        }
        
        function formatICalDate(dateStr, timeStr, addHours = 0) {
            const date = new Date(dateStr + ' ' + (timeStr || '00:00:00'));
            if (addHours > 0) {
                date.setHours(date.getHours() + addHours);
            }
            return date.toISOString().replace(/[-:]/g, '').split('.')[0] + 'Z';
        }
        
        // Keyboard shortcuts
        document.addEventListener('keydown', function(e) {
            // E for edit
            if (e.key === 'e' && !e.ctrlKey && !e.metaKey) {
                e.preventDefault();
                window.location.href = '<?php echo $baseUrl; ?>/admin/events/<?php echo $event['id']; ?>/edit';
            }
            
            // R for registrations
            if (e.key === 'r' && !e.ctrlKey && !e.metaKey) {
                e.preventDefault();
                window.location.href = '<?php echo $baseUrl; ?>/admin/events/<?php echo $event['id']; ?>/registrations';
            }
            
            // Backspace or Delete to go back
            if (e.key === 'Backspace' || e.key === 'Delete') {
                e.preventDefault();
                window.history.back();
            }
        });
    </script>
</body>
</html>