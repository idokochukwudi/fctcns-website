<?php
$baseUrl = $data['baseUrl'] ?? '';
$news = $data['news'] ?? [];
$csrfToken = bin2hex(random_bytes(32));
$_SESSION['csrf_token'] = $csrfToken;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($news['title']); ?> - Admin Dashboard</title>
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
        
        /* Article Content */
        .article-container {
            background: white;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
            margin-bottom: 30px;
        }
        
        .article-header {
            padding: 30px;
            border-bottom: 1px solid var(--border-color);
        }
        
        .article-title {
            font-size: 32px;
            font-weight: 700;
            margin-bottom: 16px;
            color: var(--text-dark);
        }
        
        .article-meta {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            color: var(--text-light);
            font-size: 14px;
        }
        
        .article-body {
            padding: 30px;
        }
        
        .article-content {
            line-height: 1.8;
            font-size: 16px;
        }
        
        .article-content img {
            max-width: 100%;
            height: auto;
            border-radius: 8px;
            margin: 20px 0;
        }
        
        .article-content h2, 
        .article-content h3, 
        .article-content h4 {
            margin: 24px 0 16px 0;
            color: var(--text-dark);
        }
        
        .article-content p {
            margin-bottom: 16px;
        }
        
        .article-content ul, 
        .article-content ol {
            margin-left: 20px;
            margin-bottom: 16px;
        }
        
        .article-content blockquote {
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
        .event-details {
            background: #f0f9ff;
            border-radius: 8px;
            padding: 20px;
            margin: 20px 0;
        }
        
        .event-details h3 {
            color: var(--info-color);
            margin-bottom: 16px;
        }
        
        .event-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 16px;
        }
        
        .event-item {
            display: flex;
            flex-direction: column;
            gap: 4px;
        }
        
        .event-label {
            font-size: 12px;
            color: var(--text-light);
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }
        
        .event-value {
            font-weight: 500;
            color: var(--text-dark);
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
            
            .article-title {
                font-size: 24px;
            }
            
            .info-grid {
                grid-template-columns: 1fr;
            }
        }
        
        @media (max-width: 480px) {
            .article-header,
            .article-body {
                padding: 20px;
            }
            
            .article-meta {
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
    <!-- Debug Section -->
    <?php if (isset($_GET['debug']) && $_GET['debug'] == '1'): ?>
    <div style="background: #FEF3C7; border: 2px solid #F59E0B; padding: 20px; margin: 20px; border-radius: 8px; font-family: monospace; font-size: 14px;">
        <h3 style="margin-top: 0; color: #D97706;">🔍 Image Debug Info</h3>
        
        <p><strong>Database Path:</strong><br>
        <code style="background: white; padding: 5px; display: block; margin-top: 5px;"><?php echo htmlspecialchars($news['featured_image'] ?? 'EMPTY'); ?></code></p>
        
        <p><strong>BASE_URL:</strong><br>
        <code style="background: white; padding: 5px; display: block; margin-top: 5px;"><?php echo htmlspecialchars($baseUrl); ?></code></p>
        
        <p><strong>Full Image URL:</strong><br>
        <code style="background: white; padding: 5px; display: block; margin-top: 5px;"><?php echo htmlspecialchars($baseUrl . ($news['featured_image'] ?? '')); ?></code></p>
        
        <p><strong>File Exists on Server:</strong><br>
        <code style="background: white; padding: 5px; display: block; margin-top: 5px;">
        <?php 
        $filePath = APP_PATH . '/public' . ($news['featured_image'] ?? '');
        echo file_exists($filePath) ? '✅ YES - ' . $filePath : '❌ NO - ' . $filePath;
        ?>
        </code></p>
        
        <p><strong>Test Image Direct Access:</strong><br>
        <a href="<?php echo $baseUrl . ($news['featured_image'] ?? ''); ?>" target="_blank" style="color: #2563EB;">
            Click to open image in new tab
        </a></p>
    </div>
    <?php endif; ?>

    <div class="admin-container">
        <!-- Page Header -->
        <div class="page-header">
            <h1 class="page-title">Article Details</h1>
            <div class="action-buttons">
                <a href="<?php echo $baseUrl; ?>/admin/news" class="btn btn-outline">
                    <i class="fas fa-arrow-left"></i> Back to List
                </a>
                <a href="<?php echo $baseUrl; ?>/admin/news/<?php echo $news['id']; ?>/edit" class="btn btn-primary">
                    <i class="fas fa-edit"></i> Edit
                </a>
                <a href="<?php echo $baseUrl; ?>/news/<?php echo $news['slug']; ?>" 
                   target="_blank" class="btn btn-secondary">
                    <i class="fas fa-eye"></i> View Public
                </a>
                <form method="POST" action="<?php echo $baseUrl; ?>/admin/news/<?php echo $news['id']; ?>/delete" 
                      style="display: inline;" onsubmit="return confirm('Are you sure you want to delete this article?');">
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
                <h3>Basic Information</h3>
                <div class="info-content">
                    <div><strong>ID:</strong> #<?php echo $news['id']; ?></div>
                    <div><strong>Type:</strong> 
                        <span class="badge <?php echo $news['type'] === 'event' ? 'badge-info' : 'badge-success'; ?>">
                            <?php echo ucfirst($news['type']); ?>
                        </span>
                    </div>
                    <div><strong>Category:</strong> <?php echo htmlspecialchars($news['category'] ?? 'Uncategorized'); ?></div>
                    <div><strong>Author:</strong> <?php echo htmlspecialchars($news['author_name'] ?? 'Unknown'); ?></div>
                </div>
            </div>
            
            <div class="info-card">
                <h3>Status</h3>
                <div class="info-content">
                    <div class="badges">
                        <?php if ($news['is_published']): ?>
                        <span class="badge badge-success">Published</span>
                        <?php else: ?>
                        <span class="badge badge-warning">Draft</span>
                        <?php endif; ?>
                        
                        <?php if ($news['is_featured']): ?>
                        <span class="badge badge-info">Featured</span>
                        <?php endif; ?>
                        
                        <?php if ($news['is_breaking']): ?>
                        <span class="badge badge-danger">Breaking</span>
                        <?php endif; ?>
                    </div>
                    
                    <?php if ($news['published_at']): ?>
                    <div><strong>Published:</strong> <?php echo date('M d, Y H:i', strtotime($news['published_at'])); ?></div>
                    <?php endif; ?>
                    
                    <div><strong>Created:</strong> <?php echo date('M d, Y H:i', strtotime($news['created_at'])); ?></div>
                    <div><strong>Updated:</strong> <?php echo date('M d, Y H:i', strtotime($news['updated_at'])); ?></div>
                </div>
            </div>
            
            <div class="info-card">
                <h3>Statistics</h3>
                <div class="info-content">
                    <div><strong>Views:</strong> <?php echo number_format($news['views_count']); ?></div>
                    <div><strong>Likes:</strong> <?php echo number_format($news['likes_count']); ?></div>
                    <div><strong>Shares:</strong> <?php echo number_format($news['shares_count']); ?></div>
                    <div><strong>Comments:</strong> <?php echo number_format($news['comments_count']); ?></div>
                </div>
            </div>
        </div>
        
        <!-- Article Content -->
        <div class="article-container">
            <div class="article-header">
                <h1 class="article-title"><?php echo htmlspecialchars($news['title']); ?></h1>
                
                <div class="article-meta">
                    <span><i class="fas fa-user"></i> <?php echo htmlspecialchars($news['author_name'] ?? 'Unknown'); ?></span>
                    <span><i class="fas fa-calendar"></i> <?php echo date('F j, Y', strtotime($news['created_at'])); ?></span>
                    <span><i class="fas fa-folder"></i> <?php echo htmlspecialchars($news['category'] ?? 'Uncategorized'); ?></span>
                    <span><i class="fas fa-eye"></i> <?php echo number_format($news['views_count']); ?> views</span>
                </div>
                
                <?php if ($news['type'] === 'event'): ?>
                <div class="event-details">
                    <h3><i class="fas fa-calendar-alt"></i> Event Details</h3>
                    <div class="event-grid">
                        <?php if (!empty($news['event_date'])): ?>
                        <div class="event-item">
                            <span class="event-label">Date</span>
                            <span class="event-value"><?php echo date('F j, Y', strtotime($news['event_date'])); ?></span>
                        </div>
                        <?php endif; ?>
                        
                        <?php if (!empty($news['event_time'])): ?>
                        <div class="event-item">
                            <span class="event-label">Time</span>
                            <span class="event-value"><?php echo date('h:i A', strtotime($news['event_time'])); ?></span>
                        </div>
                        <?php endif; ?>
                        
                        <?php if (!empty($news['event_location'])): ?>
                        <div class="event-item">
                            <span class="event-label">Location</span>
                            <span class="event-value"><?php echo htmlspecialchars($news['event_location']); ?></span>
                        </div>
                        <?php endif; ?>
                        
                        <?php if (!empty($news['event_end_date'])): ?>
                        <div class="event-item">
                            <span class="event-label">End Date</span>
                            <span class="event-value"><?php echo date('F j, Y', strtotime($news['event_end_date'])); ?></span>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
                <?php endif; ?>
            </div>
            
            <div class="article-body">
                <?php if (!empty($news['featured_image'])): ?>
                <div style="margin-bottom: 30px; text-align: center;">
                    <img src="<?php echo $baseUrl . htmlspecialchars($news['featured_image']); ?>" 
                         alt="<?php echo htmlspecialchars($news['title']); ?>" 
                         style="max-height: 400px; width: auto;"
                         onerror="console.log('Image failed to load:', this.src); this.src='<?php echo $baseUrl; ?>/assets/images/placeholder.jpg';">
                </div>
                <?php endif; ?>
                
                <?php if (!empty($news['excerpt'])): ?>
                <div style="background: var(--light-bg); padding: 20px; border-radius: 8px; margin-bottom: 30px; font-style: italic; color: var(--text-light);">
                    <?php echo htmlspecialchars($news['excerpt']); ?>
                </div>
                <?php endif; ?>
                
                <div class="article-content">
                    <?php echo $news['content']; ?>
                </div>
                
                <?php if (!empty($news['tags'])): ?>
                <div style="margin-top: 30px; padding-top: 20px; border-top: 1px solid var(--border-color);">
                    <strong>Tags:</strong>
                    <div style="margin-top: 10px; display: flex; flex-wrap: wrap; gap: 8px;">
                        <?php 
                        $tags = explode(',', $news['tags']);
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
        
        <!-- SEO Information -->
        <div class="article-container">
            <div class="article-header">
                <h3><i class="fas fa-search"></i> SEO Information</h3>
            </div>
            
            <div class="article-body">
                <div style="display: grid; gap: 16px;">
                    <div>
                        <strong>Meta Title:</strong>
                        <div style="margin-top: 8px; padding: 12px; background: var(--light-bg); border-radius: 6px; font-family: monospace;">
                            <?php echo htmlspecialchars($news['meta_title'] ?: $news['title']); ?>
                        </div>
                    </div>
                    
                    <div>
                        <strong>Meta Description:</strong>
                        <div style="margin-top: 8px; padding: 12px; background: var(--light-bg); border-radius: 6px; font-family: monospace;">
                            <?php echo htmlspecialchars($news['meta_description'] ?: ($news['excerpt'] ?: substr(strip_tags($news['content']), 0, 150) . '...')); ?>
                        </div>
                    </div>
                    
                    <?php if (!empty($news['meta_keywords'])): ?>
                    <div>
                        <strong>Meta Keywords:</strong>
                        <div style="margin-top: 8px; padding: 12px; background: var(--light-bg); border-radius: 6px; font-family: monospace;">
                            <?php echo htmlspecialchars($news['meta_keywords']); ?>
                        </div>
                    </div>
                    <?php endif; ?>
                    
                    <div>
                        <strong>URL:</strong>
                        <div style="margin-top: 8px; padding: 12px; background: var(--light-bg); border-radius: 6px; font-family: monospace; word-break: break-all;">
                            <?php echo $baseUrl; ?>/news/<?php echo htmlspecialchars($news['slug']); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Quick Actions -->
        <div style="display: flex; justify-content: center; gap: 12px; margin-top: 30px;">
            <form method="POST" action="<?php echo $baseUrl; ?>/admin/news/<?php echo $news['id']; ?>/toggle-publish" style="display: inline;">
                <input type="hidden" name="csrf_token" value="<?php echo $csrfToken; ?>">
                <button type="submit" class="btn <?php echo $news['is_published'] ? 'btn-warning' : 'btn-success'; ?>" 
                        onclick="return confirm('Are you sure you want to <?php echo $news['is_published'] ? 'unpublish' : 'publish'; ?> this article?');">
                    <i class="fas fa-<?php echo $news['is_published'] ? 'eye-slash' : 'eye'; ?>"></i>
                    <?php echo $news['is_published'] ? 'Unpublish' : 'Publish'; ?>
                </button>
            </form>
            
            <form method="POST" action="<?php echo $baseUrl; ?>/admin/news/<?php echo $news['id']; ?>/toggle-feature" style="display: inline;">
                <input type="hidden" name="csrf_token" value="<?php echo $csrfToken; ?>">
                <button type="submit" class="btn <?php echo $news['is_featured'] ? 'btn-outline' : 'btn-info'; ?>"
                        onclick="return confirm('Are you sure you want to <?php echo $news['is_featured'] ? 'remove from' : 'add to'; ?> featured?');">
                    <i class="fas fa-star"></i>
                    <?php echo $news['is_featured'] ? 'Unfeature' : 'Feature'; ?>
                </button>
            </form>
            
            <button class="btn btn-secondary" onclick="window.print()">
                <i class="fas fa-print"></i> Print
            </button>
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
                
                .article-container {
                    box-shadow: none;
                    border: 1px solid #ddd;
                }
                
                .article-content {
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
            const url = '<?php echo $baseUrl; ?>/news/<?php echo $news['slug']; ?>';
            navigator.clipboard.writeText(url).then(() => {
                alert('URL copied to clipboard!');
            });
        }
        
        // Share on social media
        function shareOnFacebook() {
            const url = encodeURIComponent('<?php echo $baseUrl; ?>/news/<?php echo $news['slug']; ?>');
            window.open(`https://www.facebook.com/sharer/sharer.php?u=${url}`, '_blank');
        }
        
        function shareOnTwitter() {
            const url = encodeURIComponent('<?php echo $baseUrl; ?>/news/<?php echo $news['slug']; ?>');
            const text = encodeURIComponent('<?php echo htmlspecialchars($news['title']); ?>');
            window.open(`https://twitter.com/intent/tweet?url=${url}&text=${text}`, '_blank');
        }
        
        // Keyboard shortcuts
        document.addEventListener('keydown', function(e) {
            // E for edit
            if (e.key === 'e' && !e.ctrlKey && !e.metaKey) {
                e.preventDefault();
                window.location.href = '<?php echo $baseUrl; ?>/admin/news/<?php echo $news['id']; ?>/edit';
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