<?php
// Get the absolute path to the root
$rootPath = dirname(__DIR__, 4);
require_once $rootPath . '/app/config/constants.php';
require_once APP_PATH . '/config/session.php';
require_once APP_PATH . '/middleware/AuthMiddleware.php';
AuthMiddleware::authenticate();

$userRole = $_SESSION['user_role'] ?? 'viewer';
$currentUserId = $_SESSION['user_id'] ?? 0;

// Format date
$publicationDate = !empty($research['publication_date']) ? date('F j, Y', strtotime($research['publication_date'])) : 'Not specified';
$createdDate = !empty($research['created_at']) ? date('F j, Y', strtotime($research['created_at'])) : '';
$updatedDate = !empty($research['updated_at']) ? date('F j, Y', strtotime($research['updated_at'])) : '';

// Parse authors
$authors = [];
if (!empty($research['authors'])) {
    if (strpos($research['authors'], '[') === 0) {
        $authors = json_decode($research['authors'], true) ?: [];
    } else {
        $authors = array_map('trim', explode(',', $research['authors']));
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($research['title'] ?? 'Research Publication'); ?> - FCT CNS Admin</title>
    <style>
        :root {
            --primary: #2c5282;
            --primary-dark: #1a365d;
            --success: #38a169;
            --warning: #d69e2e;
            --danger: #e53e3e;
            --info: #4299e1;
            --purple: #9f7aea;
            --gray-50: #f7fafc;
            --gray-100: #edf2f7;
            --gray-200: #e2e8f0;
            --gray-600: #718096;
            --gray-700: #4a5568;
            --gray-800: #2d3748;
        }
        
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background-color: var(--gray-100);
            margin: 0;
            padding: 0;
        }
        
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }
        
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            flex-wrap: wrap;
            gap: 15px;
        }
        
        .header h1 {
            color: var(--gray-800);
            margin: 0;
            font-size: 1.75rem;
            line-height: 1.3;
        }
        
        .btn {
            padding: 10px 20px;
            border-radius: 6px;
            text-decoration: none;
            font-weight: 500;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            cursor: pointer;
            border: none;
            transition: all 0.2s;
        }
        
        .btn-primary {
            background: var(--primary);
            color: white;
        }
        
        .btn-secondary {
            background: var(--gray-600);
            color: white;
        }
        
        .btn-success {
            background: var(--success);
            color: white;
        }
        
        .btn-warning {
            background: var(--warning);
            color: white;
        }
        
        .btn-danger {
            background: var(--danger);
            color: white;
        }
        
        .btn-info {
            background: var(--info);
            color: white;
        }
        
        .btn:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }
        
        .btn-primary:hover { background: var(--primary-dark); }
        .btn-secondary:hover { background: var(--gray-700); }
        
        .btn-group {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }
        
        .content-card {
            background: white;
            border-radius: 8px;
            padding: 30px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            margin-bottom: 30px;
        }
        
        .badge {
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 600;
            display: inline-block;
            margin-right: 8px;
            margin-bottom: 8px;
        }
        
        .badge-published { 
            background: rgba(56, 161, 105, 0.1); 
            color: var(--success); 
            border: 1px solid rgba(56, 161, 105, 0.2);
        }
        
        .badge-draft { 
            background: rgba(214, 158, 46, 0.1); 
            color: var(--warning); 
            border: 1px solid rgba(214, 158, 46, 0.2);
        }
        
        .badge-featured {
            background: rgba(229, 62, 62, 0.1);
            color: var(--danger);
            border: 1px solid rgba(229, 62, 62, 0.2);
        }
        
        .badge-category {
            background: rgba(66, 153, 225, 0.1);
            color: var(--info);
            border: 1px solid rgba(66, 153, 225, 0.2);
        }
        
        .badge-type {
            background: rgba(159, 122, 234, 0.1);
            color: var(--purple);
            border: 1px solid rgba(159, 122, 234, 0.2);
        }
        
        .impact-badge {
            display: inline-flex;
            align-items: center;
            padding: 4px 12px;
            background: linear-gradient(135deg, #f6e05e, #d69e2e);
            color: #744210;
            border-radius: 4px;
            font-size: 0.875rem;
            font-weight: 600;
        }
        
        .metrics-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 20px;
            margin: 30px 0;
        }
        
        .metric-card {
            text-align: center;
            padding: 20px;
            background: var(--gray-50);
            border-radius: 8px;
            border-left: 4px solid var(--primary);
        }
        
        .metric-value {
            font-size: 2rem;
            font-weight: 700;
            color: var(--gray-800);
            margin-bottom: 5px;
        }
        
        .metric-label {
            font-size: 0.875rem;
            color: var(--gray-600);
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }
        
        .authors-list {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin: 15px 0;
        }
        
        .author-chip {
            background: var(--gray-100);
            padding: 8px 16px;
            border-radius: 20px;
            font-size: 0.875rem;
            color: var(--gray-700);
        }
        
        .abstract-container {
            background: var(--gray-50);
            border-radius: 8px;
            padding: 20px;
            margin: 20px 0;
            line-height: 1.6;
        }
        
        .details-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin: 20px 0;
        }
        
        .detail-item {
            margin-bottom: 15px;
        }
        
        .detail-label {
            font-weight: 600;
            color: var(--gray-700);
            margin-bottom: 5px;
            font-size: 0.875rem;
        }
        
        .detail-value {
            color: var(--gray-800);
        }
        
        .file-actions {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }
        
        .related-publications {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
            margin-top: 20px;
        }
        
        .related-card {
            background: white;
            border-radius: 8px;
            padding: 20px;
            border: 1px solid var(--gray-200);
            transition: all 0.2s;
        }
        
        .related-card:hover {
            border-color: var(--primary);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }
        
        .related-title {
            font-weight: 600;
            color: var(--gray-800);
            margin-bottom: 10px;
            line-height: 1.4;
        }
        
        .related-meta {
            font-size: 0.875rem;
            color: var(--gray-600);
        }
        
        .error-message {
            background: rgba(229, 62, 62, 0.1);
            color: var(--danger);
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 20px;
            border-left: 4px solid var(--danger);
            text-align: center;
        }
        
        @media (max-width: 768px) {
            .header {
                flex-direction: column;
                align-items: stretch;
            }
            
            .btn-group {
                justify-content: center;
            }
            
            .metrics-grid {
                grid-template-columns: 1fr 1fr;
            }
        }
        
        @media (max-width: 640px) {
            .metrics-grid {
                grid-template-columns: 1fr;
            }
            
            .details-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1><?php echo htmlspecialchars($research['title']); ?></h1>
            <div class="btn-group">
                <a href="<?php echo BASE_URL; ?>/admin/research" class="btn btn-secondary">
                    ← Back to Research
                </a>
                <a href="<?php echo BASE_URL; ?>/admin/dashboard" class="btn btn-secondary">
                    🏠 Dashboard
                </a>
                <?php if (in_array($userRole, ['admin', 'editor'])): ?>
                <a href="<?php echo BASE_URL; ?>/admin/research/edit/<?php echo $research['id']; ?>" class="btn btn-primary">
                    ✏️ Edit
                </a>
                <?php endif; ?>
            </div>
        </div>
        
        <?php if (isset($error) && $error): ?>
        <div class="error-message">
            <strong>Error:</strong> <?php echo htmlspecialchars($error); ?>
        </div>
        <?php endif; ?>
        
        <!-- Publication Status & Type -->
        <div style="margin-bottom: 20px;">
            <?php if ($research['is_published']): ?>
            <span class="badge badge-published">✅ Published</span>
            <?php else: ?>
            <span class="badge badge-draft">📝 Draft</span>
            <?php endif; ?>
            
            <?php if ($research['is_featured']): ?>
            <span class="badge badge-featured">⭐ Featured</span>
            <?php endif; ?>
            
            <?php if ($research['publication_type']): ?>
            <span class="badge badge-type">
                📄 <?php echo ucfirst($research['publication_type']); ?>
            </span>
            <?php endif; ?>
            
            <?php if ($research['category_name']): ?>
            <span class="badge badge-category">
                🏷️ <?php echo htmlspecialchars($research['category_name']); ?>
            </span>
            <?php endif; ?>
            
            <?php if ($research['impact_factor']): ?>
            <span class="impact-badge">
                📊 Impact Factor: <?php echo number_format($research['impact_factor'], 2); ?>
            </span>
            <?php endif; ?>
        </div>
        
        <!-- Metrics -->
        <div class="metrics-grid">
            <div class="metric-card">
                <div class="metric-value"><?php echo number_format($research['views_count'] ?? 0); ?></div>
                <div class="metric-label">Views</div>
            </div>
            <div class="metric-card">
                <div class="metric-value"><?php echo number_format($research['downloads_count'] ?? 0); ?></div>
                <div class="metric-label">Downloads</div>
            </div>
            <div class="metric-card">
                <div class="metric-value"><?php echo number_format($research['citations'] ?? 0); ?></div>
                <div class="metric-label">Citations</div>
            </div>
            <div class="metric-card">
                <div class="metric-value"><?php echo $publicationDate; ?></div>
                <div class="metric-label">Publication Date</div>
            </div>
        </div>
        
        <!-- Main Content Card -->
        <div class="content-card">
            <!-- Authors -->
            <div class="detail-item">
                <div class="detail-label">Authors</div>
                <div class="authors-list">
                    <?php foreach ($authors as $author): ?>
                    <div class="author-chip"><?php echo htmlspecialchars($author); ?></div>
                    <?php endforeach; ?>
                </div>
            </div>
            
            <!-- Abstract -->
            <div class="detail-item">
                <div class="detail-label">Abstract</div>
                <div class="abstract-container">
                    <?php echo nl2br(htmlspecialchars($research['abstract'] ?? 'No abstract available.')); ?>
                </div>
            </div>
            
            <!-- Publication Details -->
            <div class="details-grid">
                <?php if ($research['journal_name']): ?>
                <div class="detail-item">
                    <div class="detail-label">Journal/Conference</div>
                    <div class="detail-value"><?php echo htmlspecialchars($research['journal_name']); ?></div>
                </div>
                <?php endif; ?>
                
                <?php if ($research['publisher']): ?>
                <div class="detail-item">
                    <div class="detail-label">Publisher</div>
                    <div class="detail-value"><?php echo htmlspecialchars($research['publisher']); ?></div>
                </div>
                <?php endif; ?>
                
                <?php if ($research['volume'] || $research['issue'] || $research['pages']): ?>
                <div class="detail-item">
                    <div class="detail-label">Volume/Issue/Pages</div>
                    <div class="detail-value">
                        <?php 
                        $parts = [];
                        if ($research['volume']) $parts[] = 'Vol. ' . htmlspecialchars($research['volume']);
                        if ($research['issue']) $parts[] = 'Issue ' . htmlspecialchars($research['issue']);
                        if ($research['pages']) $parts[] = 'pp. ' . htmlspecialchars($research['pages']);
                        echo implode(', ', $parts);
                        ?>
                    </div>
                </div>
                <?php endif; ?>
                
                <?php if ($research['doi']): ?>
                <div class="detail-item">
                    <div class="detail-label">DOI</div>
                    <div class="detail-value">
                        <a href="https://doi.org/<?php echo htmlspecialchars($research['doi']); ?>" 
                           target="_blank" style="color: var(--info); text-decoration: none;">
                            <?php echo htmlspecialchars($research['doi']); ?>
                        </a>
                    </div>
                </div>
                <?php endif; ?>
                
                <?php if ($research['url']): ?>
                <div class="detail-item">
                    <div class="detail-label">URL</div>
                    <div class="detail-value">
                        <a href="<?php echo htmlspecialchars($research['url']); ?>" 
                           target="_blank" style="color: var(--info); text-decoration: none; word-break: break-all;">
                            <?php echo htmlspecialchars($research['url']); ?>
                        </a>
                    </div>
                </div>
                <?php endif; ?>
                
                <?php if ($research['keywords']): ?>
                <div class="detail-item">
                    <div class="detail-label">Keywords</div>
                    <div class="detail-value"><?php echo htmlspecialchars($research['keywords']); ?></div>
                </div>
                <?php endif; ?>
                
                <?php if ($research['created_by_name']): ?>
                <div class="detail-item">
                    <div class="detail-label">Added By</div>
                    <div class="detail-value"><?php echo htmlspecialchars($research['created_by_name']); ?></div>
                </div>
                <?php endif; ?>
                
                <div class="detail-item">
                    <div class="detail-label">Created</div>
                    <div class="detail-value"><?php echo $createdDate; ?></div>
                </div>
                
                <div class="detail-item">
                    <div class="detail-label">Last Updated</div>
                    <div class="detail-value"><?php echo $updatedDate ?: $createdDate; ?></div>
                </div>
            </div>
            
            <!-- File Actions -->
            <?php if ($research['file_path']): ?>
            <div class="detail-item">
                <div class="detail-label">Research File</div>
                <div class="file-actions">
                    <a href="<?php echo BASE_URL . '/' . $research['file_path']; ?>" 
                       target="_blank" class="btn btn-primary">
                        📄 View File
                    </a>
                    <a href="<?php echo BASE_URL . '/' . $research['file_path']; ?>" 
                       download class="btn btn-success">
                        ⬇️ Download
                    </a>
                    <div style="font-size: 0.875rem; color: var(--gray-600); margin-left: 10px; align-self: center;">
                        <?php 
                        $fileSize = $research['file_size'] ?? 0;
                        if ($fileSize > 0) {
                            $units = ['B', 'KB', 'MB', 'GB'];
                            $bytes = max($fileSize, 0);
                            $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
                            $pow = min($pow, count($units) - 1);
                            $bytes /= pow(1024, $pow);
                            echo '(' . round($bytes, 2) . ' ' . $units[$pow] . ')';
                        }
                        ?>
                    </div>
                </div>
            </div>
            <?php endif; ?>
        </div>
        
        <!-- Related Publications -->
        <?php if (!empty($relatedResearch)): ?>
        <div class="content-card">
            <h3 style="margin-top: 0; color: var(--gray-800); margin-bottom: 20px;">📚 Related Publications</h3>
            <div class="related-publications">
                <?php foreach ($relatedResearch as $related): ?>
                <div class="related-card">
                    <div class="related-title">
                        <a href="<?php echo BASE_URL; ?>/admin/research/view/<?php echo $related['id']; ?>" 
                           style="color: var(--primary); text-decoration: none;">
                            <?php echo htmlspecialchars($related['title']); ?>
                        </a>
                    </div>
                    <div class="related-meta">
                        <div><?php echo htmlspecialchars(substr($related['authors'], 0, 80)); ?>...</div>
                        <div style="margin-top: 8px;">
                            <?php if ($related['journal_name']): ?>
                            <span>📖 <?php echo htmlspecialchars(substr($related['journal_name'], 0, 50)); ?></span>
                            <?php endif; ?>
                            <?php if ($related['publication_date']): ?>
                            <span style="margin-left: 15px;">📅 <?php echo date('Y', strtotime($related['publication_date'])); ?></span>
                            <?php endif; ?>
                        </div>
                        <div style="margin-top: 8px; font-size: 0.75rem; color: var(--gray-600);">
                            <?php if ($related['citations']): ?>
                            <span>📚 <?php echo $related['citations']; ?> citations</span>
                            <?php endif; ?>
                            <?php if ($related['downloads_count']): ?>
                            <span style="margin-left: 10px;">⬇️ <?php echo $related['downloads_count']; ?> downloads</span>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
        <?php endif; ?>
        
        <!-- Quick Actions -->
        <?php if (in_array($userRole, ['admin', 'editor'])): ?>
        <div class="content-card" style="background: #f0f9ff; border: 1px solid #bee3f8;">
            <h3 style="margin-top: 0; color: var(--primary); margin-bottom: 15px;">⚡ Quick Actions</h3>
            <div class="btn-group">
                <a href="<?php echo BASE_URL; ?>/admin/research/edit/<?php echo $research['id']; ?>" class="btn btn-primary">
                    ✏️ Edit Publication
                </a>
                <button onclick="togglePublicationStatus()" class="btn btn-warning">
                    <?php echo $research['is_published'] ? '📝 Unpublish' : '📤 Publish'; ?>
                </button>
                <button onclick="duplicatePublication()" class="btn btn-info">
                    📋 Duplicate
                </button>
                <button onclick="resetViewCount()" class="btn btn-secondary">
                    🔄 Reset Views
                </button>
                <button onclick="deletePublication()" class="btn btn-danger">
                    🗑️ Delete
                </button>
            </div>
        </div>
        <?php endif; ?>
    </div>
    
    <script>
        // Publication actions
        function togglePublicationStatus() {
            const isPublished = <?php echo $research['is_published'] ? 'true' : 'false'; ?>;
            const newStatus = !isPublished;
            
            if (confirm(`${newStatus ? 'Publish' : 'Unpublish'} this publication?`)) {
                fetch(`<?php echo BASE_URL; ?>/admin/api/research/<?php echo $research['id']; ?>/status`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({ is_published: newStatus ? 1 : 0 })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert(`Publication ${newStatus ? 'published' : 'unpublished'} successfully!`);
                        location.reload();
                    } else {
                        alert('Error updating publication status: ' + data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Error updating publication status. Please try again.');
                });
            }
        }
        
        function duplicatePublication() {
            if (confirm('Create a duplicate of this publication?')) {
                fetch(`<?php echo BASE_URL; ?>/admin/api/research/<?php echo $research['id']; ?>/duplicate`, {
                    method: 'POST'
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('Publication duplicated successfully!');
                        window.location.href = `<?php echo BASE_URL; ?>/admin/research/edit/${data.id}`;
                    } else {
                        alert('Error duplicating publication: ' + data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Error duplicating publication. Please try again.');
                });
            }
        }
        
        function resetViewCount() {
            if (confirm('Reset view count to zero?')) {
                fetch(`<?php echo BASE_URL; ?>/admin/api/research/<?php echo $research['id']; ?>/reset-views`, {
                    method: 'POST'
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('View count reset successfully!');
                        location.reload();
                    } else {
                        alert('Error resetting view count: ' + data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Error resetting view count. Please try again.');
                });
            }
        }
        
        function deletePublication() {
            if (confirm('Are you sure you want to delete this publication? This action cannot be undone.')) {
                fetch(`<?php echo BASE_URL; ?>/admin/research/delete/<?php echo $research['id']; ?>`, {
                    method: 'POST',
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('Publication deleted successfully!');
                        window.location.href = '<?php echo BASE_URL; ?>/admin/research';
                    } else {
                        alert('Error deleting publication: ' + data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Error deleting publication. Please try again.');
                });
            }
        }
        
        // Keyboard shortcuts
        document.addEventListener('keydown', function(e) {
            // Ctrl/Cmd + E to edit
            if ((e.ctrlKey || e.metaKey) && e.key === 'e') {
                e.preventDefault();
                window.location.href = '<?php echo BASE_URL; ?>/admin/research/edit/<?php echo $research['id']; ?>';
            }
            
            // Ctrl/Cmd + D to duplicate
            if ((e.ctrlKey || e.metaKey) && e.key === 'd') {
                e.preventDefault();
                duplicatePublication();
            }
            
            // Ctrl/Cmd + Delete to delete
            if ((e.ctrlKey || e.metaKey) && e.key === 'Delete') {
                e.preventDefault();
                deletePublication();
            }
            
            // Esc to go back
            if (e.key === 'Escape') {
                window.location.href = '<?php echo BASE_URL; ?>/admin/research';
            }
        });
        
        // Print functionality
        function printPublication() {
            window.print();
        }
        
        // Share functionality
        function sharePublication() {
            if (navigator.share) {
                navigator.share({
                    title: '<?php echo addslashes($research['title']); ?>',
                    text: 'Check out this research publication from FCT College of Nursing Sciences',
                    url: window.location.href
                });
            } else {
                // Fallback: Copy to clipboard
                navigator.clipboard.writeText(window.location.href).then(function() {
                    alert('Link copied to clipboard!');
                });
            }
        }
        
        // Increment view count on page load (in a real implementation, this would be server-side)
        window.addEventListener('load', function() {
            // Simulate view count increment
            setTimeout(function() {
                // In a real app, you would make an API call here
                console.log('View counted for publication <?php echo $research['id']; ?>');
            }, 2000);
        });
    </script>
</body>
</html>