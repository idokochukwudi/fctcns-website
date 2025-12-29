<?php
// Get the absolute path to the root
$rootPath = dirname(__DIR__, 3);
require_once $rootPath . '/app/config/constants.php';
require_once APP_PATH . '/config/session.php';
require_once APP_PATH . '/middleware/AuthMiddleware.php';
AuthMiddleware::authenticate();

$userRole = $_SESSION['user_role'] ?? 'viewer';
$currentUserId = $_SESSION['user_id'] ?? 0;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Research Management - FCT CNS Admin</title>
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
        
        .btn-purple {
            background: var(--purple);
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
        
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }
        
        .stat-card {
            background: white;
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            border-left: 4px solid var(--purple);
        }
        
        .stat-card.published { border-left-color: var(--success); }
        .stat-card.journals { border-left-color: var(--info); }
        .stat-card.downloads { border-left-color: var(--warning); }
        .stat-card.citations { border-left-color: var(--danger); }
        
        .stat-value {
            font-size: 2rem;
            font-weight: 700;
            color: var(--gray-800);
            line-height: 1;
            margin-bottom: 5px;
        }
        
        .stat-label {
            font-size: 0.875rem;
            color: var(--gray-600);
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }
        
        .category-filter {
            background: white;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }
        
        .category-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(180px, 1fr));
            gap: 15px;
            margin-top: 15px;
        }
        
        .category-btn {
            padding: 10px 15px;
            background: var(--gray-50);
            border: 1px solid var(--gray-200);
            border-radius: 6px;
            text-decoration: none;
            color: var(--gray-700);
            text-align: center;
            transition: all 0.2s;
            cursor: pointer;
        }
        
        .category-btn:hover, .category-btn.active {
            background: var(--primary);
            color: white;
            border-color: var(--primary);
        }
        
        .category-btn .count {
            display: block;
            font-size: 0.75rem;
            color: var(--gray-500);
            margin-top: 4px;
        }
        
        .category-btn.active .count {
            color: rgba(255, 255, 255, 0.8);
        }
        
        .table-container {
            background: white;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            margin-bottom: 30px;
            overflow-x: auto;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            min-width: 1100px;
        }
        
        th, td {
            padding: 15px;
            text-align: left;
            border-bottom: 1px solid var(--gray-200);
        }
        
        th {
            background: var(--gray-50);
            font-weight: 600;
            color: var(--gray-700);
            text-transform: uppercase;
            font-size: 0.75rem;
            letter-spacing: 0.05em;
        }
        
        tr:hover {
            background: var(--gray-50);
        }
        
        .badge {
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 600;
            display: inline-block;
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
        
        .badge-journal { background: rgba(66, 153, 225, 0.1); color: var(--info); }
        .badge-conference { background: rgba(159, 122, 234, 0.1); color: var(--purple); }
        .badge-book { background: rgba(237, 137, 54, 0.1); color: var(--warning); }
        .badge-thesis { background: rgba(113, 128, 150, 0.1); color: var(--gray-600); }
        
        .badge-featured {
            background: rgba(229, 62, 62, 0.1);
            color: var(--danger);
            border: 1px solid rgba(229, 62, 62, 0.2);
            margin-left: 8px;
        }
        
        .research-title {
            font-weight: 600;
            color: var(--gray-800);
            margin-bottom: 5px;
            line-height: 1.4;
        }
        
        .research-authors {
            font-size: 0.875rem;
            color: var(--gray-600);
            margin: 5px 0;
            font-style: italic;
        }
        
        .research-meta {
            display: flex;
            align-items: center;
            gap: 15px;
            font-size: 0.75rem;
            color: var(--gray-500);
            margin-top: 8px;
            flex-wrap: wrap;
        }
        
        .research-meta span {
            display: flex;
            align-items: center;
            gap: 4px;
        }
        
        .actions {
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
        }
        
        .action-btn {
            padding: 6px 12px;
            border-radius: 4px;
            font-size: 0.75rem;
            text-decoration: none;
            border: 1px solid transparent;
            transition: all 0.2s;
            white-space: nowrap;
        }
        
        .action-view { background: var(--info); color: white; }
        .action-edit { background: var(--primary); color: white; }
        .action-publish { background: var(--success); color: white; }
        .action-download { background: var(--purple); color: white; }
        .action-delete { background: var(--danger); color: white; }
        
        .action-btn:hover {
            transform: translateY(-1px);
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }
        
        .empty-state {
            text-align: center;
            padding: 60px 20px;
            color: var(--gray-600);
        }
        
        .empty-state h3 {
            margin-bottom: 10px;
            color: var(--gray-700);
        }
        
        .empty-state p {
            margin-bottom: 20px;
            max-width: 400px;
            margin-left: auto;
            margin-right: auto;
        }
        
        .pagination {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 10px;
            margin-top: 30px;
        }
        
        .pagination-btn {
            padding: 8px 16px;
            border: 1px solid var(--gray-200);
            background: white;
            border-radius: 4px;
            text-decoration: none;
            color: var(--gray-700);
            transition: all 0.2s;
        }
        
        .pagination-btn.active {
            background: var(--primary);
            color: white;
            border-color: var(--primary);
        }
        
        .pagination-btn:hover:not(.active) {
            background: var(--gray-50);
        }
        
        .mobile-only { display: none; }
        
        @media (max-width: 768px) {
            .header { flex-direction: column; align-items: stretch; }
            .btn-group { justify-content: center; }
            .desktop-only { display: none; }
            .mobile-only { display: block; }
            
            th, td {
                padding: 12px 10px;
            }
            
            .category-grid {
                grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
            }
        }
        
        @media (max-width: 640px) {
            .stats-grid {
                grid-template-columns: 1fr;
            }
            
            .category-grid {
                grid-template-columns: 1fr 1fr;
            }
        }
        
        .impact-badge {
            display: inline-flex;
            align-items: center;
            padding: 2px 8px;
            background: linear-gradient(135deg, #f6e05e, #d69e2e);
            color: #744210;
            border-radius: 4px;
            font-size: 0.75rem;
            font-weight: 600;
            margin-left: 8px;
        }
        
        .doi-link {
            font-family: monospace;
            font-size: 0.75rem;
            color: var(--info);
            text-decoration: none;
            word-break: break-all;
        }
        
        .doi-link:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>📚 Research Publications</h1>
            <div class="btn-group">
                <a href="<?php echo BASE_URL; ?>/admin/dashboard" class="btn btn-secondary">
                    ← Dashboard
                </a>
                <?php if (in_array($userRole, ['admin', 'editor'])): ?>
                <a href="<?php echo BASE_URL; ?>/admin/research/create" class="btn btn-primary">
                    ＋ New Publication
                </a>
                <?php endif; ?>
                <button class="btn btn-purple" onclick="toggleCategories()">
                    🏷️ Categories
                </button>
            </div>
        </div>
        
        <!-- Statistics -->
        <div class="stats-grid">
            <?php
            $publishedCount = 0;
            $journalCount = 0;
            $totalDownloads = 0;
            $totalCitations = 0;
            $featuredCount = 0;
            
            foreach ($research as $pub) {
                if ($pub['is_published']) $publishedCount++;
                if ($pub['publication_type'] === 'journal') $journalCount++;
                $totalDownloads += $pub['downloads_count'];
                $totalCitations += $pub['citations'];
                if ($pub['is_featured']) $featuredCount++;
            }
            ?>
            
            <div class="stat-card">
                <div class="stat-value"><?php echo count($research); ?></div>
                <div class="stat-label">Total Publications</div>
            </div>
            
            <div class="stat-card published">
                <div class="stat-value"><?php echo $publishedCount; ?></div>
                <div class="stat-label">Published</div>
            </div>
            
            <div class="stat-card journals">
                <div class="stat-value"><?php echo $journalCount; ?></div>
                <div class="stat-label">Journal Articles</div>
            </div>
            
            <div class="stat-card downloads">
                <div class="stat-value"><?php echo number_format($totalDownloads); ?></div>
                <div class="stat-label">Total Downloads</div>
            </div>
            
            <div class="stat-card citations">
                <div class="stat-value"><?php echo number_format($totalCitations); ?></div>
                <div class="stat-label">Citations</div>
            </div>
        </div>
        
        <!-- Category Filters -->
        <div class="category-filter" id="categorySection" style="display: none;">
            <h3 style="margin-top: 0; margin-bottom: 15px; color: var(--gray-800);">Filter by Category</h3>
            <div class="category-grid">
                <div class="category-btn active" data-category="all">
                    All Categories
                    <span class="count"><?php echo count($research); ?></span>
                </div>
                
                <?php foreach ($categories as $category): 
                    $categoryCount = 0;
                    foreach ($research as $pub) {
                        if ($pub['research_area'] === $category['slug']) {
                            $categoryCount++;
                        }
                    }
                ?>
                <div class="category-btn" data-category="<?php echo htmlspecialchars($category['slug']); ?>">
                    <?php echo htmlspecialchars($category['name']); ?>
                    <span class="count"><?php echo $categoryCount; ?></span>
                </div>
                <?php endforeach; ?>
            </div>
            
            <div style="display: flex; justify-content: flex-end; gap: 10px; margin-top: 20px;">
                <button class="btn btn-secondary" onclick="clearCategoryFilter()">
                    Clear Filter
                </button>
            </div>
        </div>
        
        <!-- Research Table -->
        <div class="table-container">
            <?php if (!empty($research)): ?>
                <table>
                    <thead>
                        <tr>
                            <th>Publication</th>
                            <th class="desktop-only">Type & Category</th>
                            <th>Status</th>
                            <th class="desktop-only">Metrics</th>
                            <th class="desktop-only">Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($research as $pub): ?>
                        <tr data-category="<?php echo htmlspecialchars($pub['research_area'] ?? ''); ?>"
                            data-type="<?php echo htmlspecialchars($pub['publication_type']); ?>"
                            data-status="<?php echo $pub['is_published'] ? 'published' : 'draft'; ?>">
                            <td>
                                <div class="research-title">
                                    <?php echo htmlspecialchars($pub['title']); ?>
                                    <?php if ($pub['is_featured']): ?>
                                    <span class="badge badge-featured">Featured</span>
                                    <?php endif; ?>
                                    <?php if ($pub['impact_factor']): ?>
                                    <span class="impact-badge" title="Impact Factor">
                                        IF: <?php echo number_format($pub['impact_factor'], 2); ?>
                                    </span>
                                    <?php endif; ?>
                                </div>
                                <div class="research-authors">
                                    <?php echo htmlspecialchars(substr($pub['authors'], 0, 100)); ?>
                                    <?php if (strlen($pub['authors']) > 100): ?>...<?php endif; ?>
                                </div>
                                <div class="research-meta">
                                    <?php if ($pub['journal_name']): ?>
                                    <span title="Journal">
                                        📖 <?php echo htmlspecialchars(substr($pub['journal_name'], 0, 50)); ?>
                                    </span>
                                    <?php endif; ?>
                                    
                                    <?php if ($pub['doi']): ?>
                                    <span>
                                        <a href="https://doi.org/<?php echo htmlspecialchars($pub['doi']); ?>" 
                                           target="_blank" class="doi-link">
                                            DOI: <?php echo htmlspecialchars(substr($pub['doi'], 0, 20)); ?>...
                                        </a>
                                    </span>
                                    <?php endif; ?>
                                    
                                    <span class="mobile-only">
                                        📅 <?php echo date('M y', strtotime($pub['publication_date'])); ?>
                                    </span>
                                </div>
                            </td>
                            <td class="desktop-only">
                                <div>
                                    <span class="badge badge-<?php echo $pub['publication_type']; ?>">
                                        <?php echo ucfirst($pub['publication_type']); ?>
                                    </span>
                                </div>
                                <div style="margin-top: 8px; font-size: 0.875rem; color: var(--gray-700);">
                                    <?php if ($pub['category_name']): ?>
                                    <?php echo htmlspecialchars($pub['category_name']); ?>
                                    <?php else: ?>
                                    <span style="color: var(--gray-500); font-style: italic;">Uncategorized</span>
                                    <?php endif; ?>
                                </div>
                            </td>
                            <td>
                                <?php if ($pub['is_published']): ?>
                                <span class="badge badge-published">Published</span>
                                <?php else: ?>
                                <span class="badge badge-draft">Draft</span>
                                <?php endif; ?>
                                <div class="mobile-only" style="font-size: 0.75rem; color: var(--gray-600); margin-top: 4px;">
                                    📥 <?php echo number_format($pub['downloads_count']); ?> downloads
                                </div>
                            </td>
                            <td class="desktop-only">
                                <div style="font-size: 0.875rem;">
                                    <div>📥 <?php echo number_format($pub['downloads_count']); ?> downloads</div>
                                    <div>👁️ <?php echo number_format($pub['views_count']); ?> views</div>
                                    <div>📚 <?php echo number_format($pub['citations']); ?> citations</div>
                                </div>
                            </td>
                            <td class="desktop-only">
                                <div style="font-size: 0.875rem;">
                                    <div><?php echo date('M d, Y', strtotime($pub['publication_date'])); ?></div>
                                    <div style="color: var(--gray-500); font-size: 0.75rem;">
                                        Added: <?php echo date('M d', strtotime($pub['created_at'])); ?>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="actions">
                                    <?php if ($pub['file_path']): ?>
                                    <a href="#" class="action-btn action-download">Download</a>
                                    <?php endif; ?>
                                    
                                    <a href="#" class="action-btn action-view">View</a>
                                    
                                    <?php if (in_array($userRole, ['admin', 'editor']) && 
                                             ($pub['created_by'] == $currentUserId || $userRole == 'admin')): ?>
                                    <a href="<?php echo BASE_URL; ?>/admin/research/edit?id=<?php echo $pub['id']; ?>" 
                                       class="action-btn action-edit">Edit</a>
                                    
                                    <?php if (!$pub['is_published']): ?>
                                    <a href="#" class="action-btn action-publish">Publish</a>
                                    <?php endif; ?>
                                    
                                    <a href="#" class="action-btn action-delete">Delete</a>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                
                <!-- Pagination -->
                <div class="pagination">
                    <a href="#" class="pagination-btn">« Previous</a>
                    <a href="#" class="pagination-btn active">1</a>
                    <a href="#" class="pagination-btn">2</a>
                    <a href="#" class="pagination-btn">3</a>
                    <a href="#" class="pagination-btn">4</a>
                    <a href="#" class="pagination-btn">5</a>
                    <a href="#" class="pagination-btn">Next »</a>
                </div>
            <?php else: ?>
                <div class="empty-state">
                    <h3>No Research Publications Found</h3>
                    <p>There are no research publications in the database yet. Add your first publication to get started.</p>
                    <?php if (in_array($userRole, ['admin', 'editor'])): ?>
                    <a href="<?php echo BASE_URL; ?>/admin/research/create" class="btn btn-primary">
                        ＋ Add First Publication
                    </a>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </div>
        
        <!-- Quick Actions -->
        <?php if (in_array($userRole, ['admin', 'editor'])): ?>
        <div style="background: #f0f9ff; border: 1px solid #bee3f8; border-radius: 8px; padding: 20px; margin-top: 30px;">
            <h3 style="margin-top: 0; color: var(--primary);">🚀 Quick Actions</h3>
            <div style="display: flex; gap: 15px; flex-wrap: wrap;">
                <a href="<?php echo BASE_URL; ?>/admin/research/create" class="btn btn-primary">
                    ＋ Add New Publication
                </a>
                <button class="btn btn-success" onclick="bulkPublish()">
                    📤 Bulk Publish Selected
                </button>
                <button class="btn btn-warning" onclick="exportResearch()">
                    📥 Export to CSV
                </button>
                <button class="btn btn-purple" onclick="importBibtex()">
                    📚 Import BibTeX
                </button>
                <button class="btn btn-info" onclick="updateCitations()">
                    🔄 Update Citations
                </button>
            </div>
        </div>
        <?php endif; ?>
        
        <!-- Research Impact Summary -->
        <?php if (!empty($research)): ?>
        <div style="background: linear-gradient(135deg, #9f7aea, #667eea); color: white; border-radius: 8px; padding: 20px; margin-top: 30px;">
            <h3 style="margin-top: 0; color: white;">📊 Research Impact Summary</h3>
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(150px, 1fr)); gap: 20px; margin-top: 15px;">
                <div style="text-align: center;">
                    <div style="font-size: 1.5rem; font-weight: 700;"><?php echo number_format($totalCitations); ?></div>
                    <div style="font-size: 0.875rem; opacity: 0.9;">Total Citations</div>
                </div>
                <div style="text-align: center;">
                    <div style="font-size: 1.5rem; font-weight: 700;"><?php echo number_format($totalDownloads); ?></div>
                    <div style="font-size: 0.875rem; opacity: 0.9;">Total Downloads</div>
                </div>
                <div style="text-align: center;">
                    <div style="font-size: 1.5rem; font-weight: 700;"><?php echo count($research); ?></div>
                    <div style="font-size: 0.875rem; opacity: 0.9;">Publications</div>
                </div>
                <div style="text-align: center;">
                    <div style="font-size: 1.5rem; font-weight: 700;"><?php echo $featuredCount; ?></div>
                    <div style="font-size: 0.875rem; opacity: 0.9;">Featured</div>
                </div>
            </div>
        </div>
        <?php endif; ?>
    </div>
    
    <script>
        // Toggle categories visibility
        function toggleCategories() {
            const categorySection = document.getElementById('categorySection');
            categorySection.style.display = categorySection.style.display === 'none' ? 'block' : 'none';
        }
        
        // Category filtering
        document.querySelectorAll('.category-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                // Remove active class from all buttons
                document.querySelectorAll('.category-btn').forEach(b => {
                    b.classList.remove('active');
                });
                
                // Add active class to clicked button
                this.classList.add('active');
                
                // Get selected category
                const category = this.dataset.category;
                
                // Filter table rows
                const rows = document.querySelectorAll('tbody tr');
                rows.forEach(row => {
                    if (category === 'all') {
                        row.style.display = '';
                    } else if (row.dataset.category === category) {
                        row.style.display = '';
                    } else {
                        row.style.display = 'none';
                    }
                });
            });
        });
        
        function clearCategoryFilter() {
            document.querySelectorAll('.category-btn').forEach(btn => {
                btn.classList.remove('active');
            });
            
            // Activate "All Categories" button
            document.querySelector('.category-btn[data-category="all"]').classList.add('active');
            
            // Show all rows
            const rows = document.querySelectorAll('tbody tr');
            rows.forEach(row => {
                row.style.display = '';
            });
        }
        
        // Bulk actions
        function bulkPublish() {
            alert('Bulk publish functionality would be implemented here');
        }
        
        function exportResearch() {
            alert('Export to CSV functionality would be implemented here');
        }
        
        function importBibtex() {
            alert('BibTeX import functionality would be implemented here');
        }
        
        function updateCitations() {
            alert('Citation update functionality would be implemented here');
        }
        
        // Auto-refresh research stats every 3 minutes
        setInterval(function() {
            // In a real implementation, this would fetch updated stats via AJAX
            console.log('Auto-refreshing research statistics...');
        }, 3 * 60 * 1000);
        
        // Quick actions
        document.addEventListener('click', function(e) {
            if (e.target.classList.contains('action-publish')) {
                e.preventDefault();
                if (confirm('Publish this research publication?')) {
                    // In a real implementation, this would make an API call
                    e.target.textContent = 'Publishing...';
                    setTimeout(() => {
                        alert('Publication published successfully!');
                        location.reload();
                    }, 1000);
                }
            }
            
            if (e.target.classList.contains('action-download')) {
                e.preventDefault();
                // In a real implementation, this would initiate download
                alert('Download functionality would be implemented here');
            }
            
            if (e.target.classList.contains('action-delete')) {
                e.preventDefault();
                if (confirm('Are you sure you want to delete this publication? This action cannot be undone.')) {
                    // In a real implementation, this would make an API call
                    alert('Publication deletion would be implemented here');
                }
            }
            
            if (e.target.classList.contains('action-view')) {
                e.preventDefault();
                // In a real implementation, this would open the publication in a new tab
                alert('View publication functionality would be implemented here');
            }
        });
        
        // Add keyboard shortcuts
        document.addEventListener('keydown', function(e) {
            // Ctrl/Cmd + N for new publication
            if ((e.ctrlKey || e.metaKey) && e.key === 'n') {
                e.preventDefault();
                window.location.href = '<?php echo BASE_URL; ?>/admin/research/create';
            }
            
            // Ctrl/Cmd + F for filters
            if ((e.ctrlKey || e.metaKey) && e.key === 'f') {
                e.preventDefault();
                toggleCategories();
            }
            
            // Ctrl/Cmd + P for print
            if ((e.ctrlKey || e.metaKey) && e.key === 'p') {
                e.preventDefault();
                window.print();
            }
        });
        
        // Initialize with "All Categories" active
        clearCategoryFilter();
    </script>
</body>
</html>