<?php
// View file for showing a single research publication
// All data comes from the controller
?>
<!-- Flash Messages -->
<?php if (($flash_success ?? false) || ($flash_error ?? false)): ?>
<div class="flash-messages">
    <?php if ($flash_success ?? false): ?>
    <div class="alert alert-success">
        <svg width="20" height="20" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
        </svg>
        <?php echo htmlspecialchars($flash_success); ?>
    </div>
    <?php endif; ?>
    
    <?php if ($flash_error ?? false): ?>
    <div class="alert alert-error">
        <svg width="20" height="20" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
        </svg>
        <?php echo htmlspecialchars($flash_error); ?>
    </div>
    <?php endif; ?>
</div>
<?php endif; ?>

<!-- Publication Header -->
<div class="publication-header">
    <h2 class="publication-title"><?php echo htmlspecialchars($publication['title']); ?></h2>
    
    <div class="publication-meta">
        <div class="meta-item">
            <svg width="16" height="16" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"/>
            </svg>
            <span>Published: <?php echo $pubDate; ?></span>
        </div>
        
        <div class="meta-item">
            <svg width="16" height="16" fill="currentColor" viewBox="0 0 20 20">
                <path d="M9 4.804A7.968 7.968 0 005.5 4c-1.255 0-2.443.29-3.5.804v10A7.969 7.969 0 015.5 14c1.669 0 3.218.51 4.5 1.385A7.962 7.962 0 0114.5 14c1.255 0 2.443.29 3.5.804v-10A7.968 7.968 0 0014.5 4c-1.255 0-2.443.29-3.5.804V12a1 1 0 11-2 0V4.804z"/>
            </svg>
            <span>Type: <?php echo $pubTypeLabel; ?></span>
        </div>
        
        <div class="meta-item">
            <svg width="16" height="16" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M12.586 4.586a2 2 0 112.828 2.828l-3 3a2 2 0 01-2.828 0 1 1 0 00-1.414 1.414 4 4 0 005.656 0l3-3a4 4 0 00-5.656-5.656l-1.5 1.5a1 1 0 101.414 1.414l1.5-1.5zm-5 5a2 2 0 012.828 0 1 1 0 101.414-1.414 4 4 0 00-5.656 0l-3 3a4 4 0 105.656 5.656l1.5-1.5a1 1 0 10-1.414-1.414l-1.5 1.5a2 2 0 11-2.828-2.828l3-3z" clip-rule="evenodd"/>
            </svg>
            <span>Category: <?php echo htmlspecialchars($categoryName); ?></span>
        </div>
    </div>
    
    <div class="meta-badges">
        <span class="badge bg-primary"><?php echo strtoupper($publication['publication_type']); ?></span>
        
        <?php if ($publication['is_published']): ?>
        <span class="badge bg-success">Published</span>
        <?php else: ?>
        <span class="badge bg-warning">Draft</span>
        <?php endif; ?>
        
        <?php if ($publication['is_featured']): ?>
        <span class="badge bg-success">Featured</span>
        <?php endif; ?>
        
        <?php if (!empty($publication['doi'])): ?>
        <span class="badge bg-secondary">DOI: <?php echo htmlspecialchars($publication['doi']); ?></span>
        <?php endif; ?>
    </div>
</div>

<!-- Authors Section -->
<div class="content-section">
    <h3 class="section-title">Authors</h3>
    <div class="authors-content">
        <?php echo htmlspecialchars($publication['authors']); ?>
    </div>
</div>

<!-- Abstract Section -->
<div class="content-section">
    <h3 class="section-title">Abstract</h3>
    <div class="abstract-content">
        <?php echo nl2br(htmlspecialchars($publication['abstract'])); ?>
    </div>
</div>

<!-- Details Grid -->
<div class="details-grid">
    <!-- Publication Details -->
    <div class="content-section">
        <h3 class="section-title">Publication Details</h3>
        
        <div class="details-item">
            <span class="details-label">Publication Type</span>
            <span class="details-value"><?php echo $pubTypeLabel; ?></span>
        </div>
        
        <?php if (!empty($publication['journal_name'])): ?>
        <div class="details-item">
            <span class="details-label">Journal/Conference</span>
            <span class="details-value"><?php echo htmlspecialchars($publication['journal_name']); ?></span>
        </div>
        <?php endif; ?>
        
        <?php if (!empty($publication['volume'])): ?>
        <div class="details-item">
            <span class="details-label">Volume</span>
            <span class="details-value"><?php echo htmlspecialchars($publication['volume']); ?></span>
        </div>
        <?php endif; ?>
        
        <?php if (!empty($publication['issue'])): ?>
        <div class="details-item">
            <span class="details-label">Issue</span>
            <span class="details-value"><?php echo htmlspecialchars($publication['issue']); ?></span>
        </div>
        <?php endif; ?>
        
        <?php if (!empty($publication['pages'])): ?>
        <div class="details-item">
            <span class="details-label">Pages</span>
            <span class="details-value"><?php echo htmlspecialchars($publication['pages']); ?></span>
        </div>
        <?php endif; ?>
        
        <?php if (!empty($publication['publisher'])): ?>
        <div class="details-item">
            <span class="details-label">Publisher</span>
            <span class="details-value"><?php echo htmlspecialchars($publication['publisher']); ?></span>
        </div>
        <?php endif; ?>
        
        <div class="details-item">
            <span class="details-label">Publication Date</span>
            <span class="details-value"><?php echo $pubDate; ?></span>
        </div>
    </div>
    
    <!-- Research Information -->
    <div class="content-section">
        <h3 class="section-title">Research Information</h3>
        
        <div class="details-item">
            <span class="details-label">Research Area</span>
            <span class="details-value"><?php echo htmlspecialchars($categoryName); ?></span>
        </div>
        
        <?php if (!empty($publication['citations'])): ?>
        <div class="details-item">
            <span class="details-label">Citations</span>
            <span class="details-value"><?php echo number_format($publication['citations']); ?></span>
        </div>
        <?php endif; ?>
        
        <?php if (!empty($publication['impact_factor'])): ?>
        <div class="details-item">
            <span class="details-label">Impact Factor</span>
            <span class="details-value"><?php echo htmlspecialchars($publication['impact_factor']); ?></span>
        </div>
        <?php endif; ?>
        
        <?php if (!empty($publication['doi'])): ?>
        <div class="details-item">
            <span class="details-label">DOI</span>
            <div class="d-flex align-items-center gap-2">
                <span class="details-value"><?php echo htmlspecialchars($publication['doi']); ?></span>
                <button class="copy-btn" onclick="copyToClipboard('<?php echo htmlspecialchars($publication['doi']); ?>', this)">
                    <svg width="16" height="16" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M8 3a1 1 0 011-1h2a1 1 0 110 2H9a1 1 0 01-1-1z"/>
                        <path d="M6 3a2 2 0 00-2 2v11a2 2 0 002 2h8a2 2 0 002-2V5a2 2 0 00-2-2 3 3 0 01-3 3H9a3 3 0 01-3-3z"/>
                    </svg>
                </button>
            </div>
        </div>
        <?php endif; ?>
        
        <?php if (!empty($publication['url'])): ?>
        <div class="details-item">
            <span class="details-label">URL</span>
            <div class="d-flex align-items-center gap-2">
                <a href="<?php echo htmlspecialchars($publication['url']); ?>" target="_blank" rel="noopener" class="details-value">
                    <?php echo htmlspecialchars($publication['url']); ?>
                </a>
                <button class="copy-btn" onclick="copyToClipboard('<?php echo htmlspecialchars($publication['url']); ?>', this)">
                    <svg width="16" height="16" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M8 3a1 1 0 011-1h2a1 1 0 110 2H9a1 1 0 01-1-1z"/>
                        <path d="M6 3a2 2 0 00-2 2v11a2 2 0 002 2h8a2 2 0 002-2V5a2 2 0 00-2-2 3 3 0 01-3 3H9a3 3 0 01-3-3z"/>
                    </svg>
                </button>
            </div>
        </div>
        <?php endif; ?>
    </div>
</div>

<!-- Keywords Section -->
<?php if (!empty($keywordsArray)): ?>
<div class="content-section">
    <h3 class="section-title">Keywords</h3>
    <div class="keywords-list">
        <?php foreach ($keywordsArray as $keyword): ?>
            <span class="keyword"><?php echo htmlspecialchars($keyword); ?></span>
        <?php endforeach; ?>
    </div>
</div>
<?php endif; ?>

<!-- Files Section -->
<div class="content-section">
    <h3 class="section-title">Files</h3>
    <div class="files-grid">
        <?php if (!empty($publication['file_path'])): ?>
        <?php 
        // Detect file type
        $file_extension = pathinfo($publication['file_path'], PATHINFO_EXTENSION);
        $file_extension_lower = strtolower($file_extension);
        $is_pdf = $file_extension_lower === 'pdf';
        $is_word = in_array($file_extension_lower, ['doc', 'docx', 'rtf']);
        $is_image = in_array($file_extension_lower, ['jpg', 'jpeg', 'png', 'gif', 'bmp', 'webp']);
        $is_presentation = in_array($file_extension_lower, ['ppt', 'pptx']);
        $is_excel = in_array($file_extension_lower, ['xls', 'xlsx', 'csv']);
        
        // Determine appropriate action for viewing
        $view_action = $is_pdf || $is_image ? 'target="_blank" rel="noopener"' : 'download';
        $view_text = $is_pdf || $is_image ? 'View' : 'Download to View';
        ?>
        <div class="file-card">
            <div class="file-icon">
                <?php if ($is_pdf): ?>
                <svg width="24" height="24" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z" clip-rule="evenodd"/>
                </svg>
                <?php elseif ($is_word): ?>
                <svg width="24" height="24" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z" clip-rule="evenodd"/>
                </svg>
                <?php elseif ($is_image): ?>
                <svg width="24" height="24" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z" clip-rule="evenodd"/>
                </svg>
                <?php else: ?>
                <svg width="24" height="24" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z" clip-rule="evenodd"/>
                </svg>
                <?php endif; ?>
            </div>
            <div class="file-name">
                <?php echo basename($publication['file_path']); ?>
                <span class="file-type-badge"><?php echo strtoupper($file_extension); ?></span>
            </div>
            <div class="file-actions">
                <?php if ($is_pdf || $is_image): ?>
                <a href="<?php echo BASE_URL . '/' . $publication['file_path']; ?>" target="_blank" rel="noopener" class="btn btn-outline btn-sm">
                    <svg width="16" height="16" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M10 12a2 2 0 100-4 2 2 0 000 4z"/>
                        <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd"/>
                    </svg>
                    View
                </a>
                <?php else: ?>
                <a href="<?php echo BASE_URL . '/' . $publication['file_path']; ?>" download class="btn btn-outline btn-sm">
                    <svg width="16" height="16" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM6.293 6.707a1 1 0 010-1.414l3-3a1 1 0 011.414 0l3 3a1 1 0 01-1.414 1.414L11 5.414V13a1 1 0 11-2 0V5.414L7.707 6.707a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
                    </svg>
                    Download to View
                </a>
                <?php endif; ?>
                <a href="<?php echo BASE_URL . '/' . $publication['file_path']; ?>" download class="btn btn-primary btn-sm">
                    <svg width="16" height="16" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM6.293 6.707a1 1 0 010-1.414l3-3a1 1 0 011.414 0l3 3a1 1 0 01-1.414 1.414L11 5.414V13a1 1 0 11-2 0V5.414L7.707 6.707a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
                    </svg>
                    Download
                </a>
            </div>
        </div>
        <?php endif; ?>
        
        <?php if (!empty($publication['thumbnail_path'])): ?>
        <div class="file-card">
            <div class="file-icon">
                <svg width="24" height="24" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z" clip-rule="evenodd"/>
                </svg>
            </div>
            <div class="file-name"><?php echo basename($publication['thumbnail_path']); ?></div>
            <img src="<?php echo BASE_URL . '/' . $publication['thumbnail_path']; ?>" alt="Thumbnail" class="thumbnail-preview">
            <div class="file-actions">
                <a href="<?php echo BASE_URL . '/' . $publication['thumbnail_path']; ?>" target="_blank" rel="noopener" class="btn btn-outline btn-sm">
                    <svg width="16" height="16" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M10 12a2 2 0 100-4 2 2 0 000 4z"/>
                        <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd"/>
                    </svg>
                    View
                </a>
                <a href="<?php echo BASE_URL . '/' . $publication['thumbnail_path']; ?>" download class="btn btn-primary btn-sm">
                    <svg width="16" height="16" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM6.293 6.707a1 1 0 010-1.414l3-3a1 1 0 011.414 0l3 3a1 1 0 01-1.414 1.414L11 5.414V13a1 1 0 11-2 0V5.414L7.707 6.707a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
                    </svg>
                    Download
                </a>
            </div>
        </div>
        <?php endif; ?>
        
        <?php if (empty($publication['file_path']) && empty($publication['thumbnail_path'])): ?>
        <div class="details-item">
            <span class="details-value empty">No files uploaded</span>
        </div>
        <?php endif; ?>
    </div>
</div>

<!-- Statistics Section -->
<div class="content-section">
    <h3 class="section-title">Publication Statistics</h3>
    <div class="stats-grid">
        <div class="stat-item">
            <span class="stat-value"><?php echo $publication['views'] ?? 0; ?></span>
            <span class="stat-label">Total Views</span>
        </div>
        <div class="stat-item">
            <span class="stat-value"><?php echo $publication['downloads'] ?? 0; ?></span>
            <span class="stat-label">Total Downloads</span>
        </div>
        <div class="stat-item">
            <span class="stat-value"><?php echo $publication['citations'] ?? 0; ?></span>
            <span class="stat-label">Citations</span>
        </div>
        <div class="stat-item">
            <span class="stat-value"><?php echo $publication['created_by'] ?? 'Unknown'; ?></span>
            <span class="stat-label">Created By</span>
        </div>
        <div class="stat-item">
            <span class="stat-value"><?php echo date('M d', strtotime($publication['created_at'] ?? date('Y-m-d'))); ?></span>
            <span class="stat-label">Created Date</span>
        </div>
        <div class="stat-item">
            <span class="stat-value"><?php echo $publication['is_published'] ? 'Yes' : 'No'; ?></span>
            <span class="stat-label">Published</span>
        </div>
    </div>
</div>

<!-- Action Buttons -->
<div class="content-section">
    <div class="action-buttons">
        <?php if (Session::hasPermission('research_edit')): ?>
        <a href="<?php echo BASE_URL; ?>/admin/research/<?php echo $publication['id']; ?>/edit" class="btn btn-primary">
            <svg width="20" height="20" fill="currentColor" viewBox="0 0 20 20">
                <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"/>
            </svg>
            Edit Publication
        </a>
        <?php endif; ?>
        
        <!-- FIXED: Dynamic public URL -->
        <a href="<?php echo BASE_URL; ?>/research/<?php echo $publication['id']; ?>" target="_blank" rel="noopener" class="btn btn-outline">
            <svg width="20" height="20" fill="currentColor" viewBox="0 0 20 20">
                <path d="M10 12a2 2 0 100-4 2 2 0 000 4z"/>
                <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd"/>
            </svg>
            View Public Page
        </a>
        
        <a href="<?php echo BASE_URL; ?>/admin/research" class="btn btn-outline">
            <svg width="20" height="20" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd"/>
            </svg>
            Back to List
        </a>
        
        <?php if (Session::hasPermission('research_delete')): ?>
        <a href="<?php echo BASE_URL; ?>/admin/research/<?php echo $publication['id']; ?>/delete" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this publication? This action cannot be undone.');">
            <svg width="20" height="20" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd"/>
            </svg>
            Delete
        </a>
        <?php endif; ?>
    </div>
</div>

<style>
    /* SHOW PAGE SPECIFIC STYLES */
    .publication-header {
        background: white;
        border-radius: 12px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        padding: var(--spacing-xl);
        margin-bottom: var(--spacing-xl);
        transition: all 0.3s ease;
        max-width: 100%;
    }
    
    .publication-header:hover {
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.12);
    }
    
    .publication-title {
        font-size: 1.75rem;
        font-weight: 700;
        color: var(--gray-800);
        line-height: 1.3;
        margin-bottom: var(--spacing-lg);
        word-break: break-word;
    }
    
    .publication-meta {
        display: flex;
        flex-wrap: wrap;
        gap: var(--spacing-lg);
        margin-bottom: var(--spacing-lg);
        padding-bottom: var(--spacing-lg);
        border-bottom: 1px solid var(--gray-200);
        max-width: 100%;
    }
    
    .meta-item {
        display: flex;
        align-items: center;
        gap: var(--spacing-sm);
        color: var(--gray-600);
        font-size: 0.875rem;
        flex-wrap: wrap;
    }
    
    .meta-badges {
        display: flex;
        gap: var(--spacing-sm);
        flex-wrap: wrap;
    }
    
    .badge {
        display: inline-block;
        padding: 0.25rem 0.75rem;
        font-size: 0.75rem;
        font-weight: 600;
        border-radius: 9999px;
        text-transform: uppercase;
        letter-spacing: 0.025em;
        white-space: nowrap;
    }
    
    .bg-primary {
        background: rgba(49, 130, 206, 0.1);
        color: var(--primary-color);
    }
    
    .bg-success {
        background: rgba(56, 161, 105, 0.1);
        color: var(--success-color);
    }
    
    .bg-warning {
        background: rgba(214, 158, 46, 0.1);
        color: var(--warning-color);
    }
    
    .bg-secondary {
        background: var(--gray-100);
        color: var(--gray-700);
    }
    
    .content-section {
        background: white;
        border-radius: 12px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        padding: var(--spacing-xl);
        margin-bottom: var(--spacing-xl);
        transition: all 0.3s ease;
        max-width: 100%;
    }
    
    .content-section:hover {
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.12);
    }
    
    .section-title {
        font-size: 1.25rem;
        font-weight: 600;
        color: var(--gray-800);
        margin-bottom: var(--spacing-lg);
        padding-bottom: var(--spacing-md);
        border-bottom: 1px solid var(--gray-200);
    }
    
    .authors-content {
        color: var(--gray-700);
        line-height: 1.8;
        word-break: break-word;
    }
    
    /* FIXED: Abstract content is now justified */
    .abstract-content {
        color: var(--gray-700);
        line-height: 1.8;
        white-space: pre-wrap;
        word-break: break-word;
        text-align: justify;
    }
    
    .details-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: var(--spacing-xl);
        max-width: 100%;
    }
    
    .details-item {
        margin-bottom: var(--spacing-lg);
        display: flex;
        flex-direction: column;
    }
    
    .details-label {
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        color: var(--gray-600);
        margin-bottom: var(--spacing-xs);
    }
    
    .details-value {
        font-size: 0.875rem;
        color: var(--gray-800);
        font-weight: 500;
        word-break: break-word;
    }
    
    .details-value.empty {
        color: var(--gray-400);
        font-style: italic;
    }
    
    .keywords-list {
        display: flex;
        flex-wrap: wrap;
        gap: var(--spacing-sm);
        max-width: 100%;
    }
    
    .keyword {
        background: var(--gray-100);
        color: var(--gray-700);
        padding: 0.375rem 0.75rem;
        border-radius: 6px;
        font-size: 0.875rem;
    }
    
    .files-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: var(--spacing-lg);
        max-width: 100%;
    }
    
    .file-card {
        border: 1px solid var(--gray-200);
        border-radius: 8px;
        padding: var(--spacing-lg);
        display: flex;
        flex-direction: column;
        align-items: center;
        text-align: center;
        transition: all 0.2s;
        max-width: 100%;
    }
    
    .file-card:hover {
        border-color: var(--primary-color);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
    }
    
    .file-icon {
        width: 48px;
        height: 48px;
        background: var(--primary-color);
        color: white;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: var(--spacing-md);
    }
    
    .file-name {
        font-weight: 600;
        color: var(--gray-800);
        margin-bottom: var(--spacing-sm);
        word-break: break-all;
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 0.5rem;
    }
    
    .file-type-badge {
        background: var(--gray-100);
        color: var(--gray-600);
        padding: 0.125rem 0.5rem;
        border-radius: 4px;
        font-size: 0.75rem;
        font-weight: 600;
    }
    
    .file-actions {
        display: flex;
        gap: var(--spacing-sm);
        margin-top: var(--spacing-md);
        flex-wrap: wrap;
        justify-content: center;
        max-width: 100%;
    }
    
    .thumbnail-preview {
        width: 100%;
        max-width: 300px;
        height: auto;
        border-radius: 8px;
        border: 1px solid var(--gray-200);
        margin-top: var(--spacing-md);
    }
    
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
        gap: var(--spacing-lg);
        max-width: 100%;
    }
    
    .stat-item {
        text-align: center;
        max-width: 100%;
    }
    
    .stat-value {
        font-size: 2rem;
        font-weight: 700;
        color: var(--primary-color);
        display: block;
        line-height: 1;
    }
    
    .stat-label {
        font-size: 0.875rem;
        color: var(--gray-600);
        margin-top: var(--spacing-sm);
        display: block;
    }
    
    .action-buttons {
        display: flex;
        gap: var(--spacing-md);
        margin-top: var(--spacing-xl);
        padding-top: var(--spacing-xl);
        border-top: 1px solid var(--gray-200);
        flex-wrap: wrap;
        max-width: 100%;
    }
    
    .action-buttons .btn {
        flex: 1;
        min-width: 150px;
    }
    
    .copy-btn {
        background: none;
        border: none;
        color: var(--gray-500);
        cursor: pointer;
        padding: 0.25rem;
        border-radius: 4px;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.2s;
    }
    
    .copy-btn:hover {
        color: var(--primary-color);
        background: var(--gray-100);
    }
    
    @media (max-width: 1024px) {
        .details-grid {
            grid-template-columns: 1fr;
        }
        
        .files-grid {
            grid-template-columns: 1fr;
        }
    }
    
    @media (max-width: 768px) {
        .publication-header,
        .content-section {
            padding: var(--spacing-lg);
        }
        
        .publication-title {
            font-size: 1.5rem;
        }
        
        .publication-meta {
            flex-direction: column;
            gap: var(--spacing-md);
        }
        
        .action-buttons {
            flex-direction: column;
        }
        
        .action-buttons .btn {
            width: 100%;
        }
        
        .stats-grid {
            grid-template-columns: repeat(2, 1fr);
        }
        
        .file-actions {
            flex-direction: column;
            width: 100%;
        }
        
        .file-actions .btn {
            width: 100%;
        }
    }
    
    @media (max-width: 640px) {
        .stats-grid {
            grid-template-columns: 1fr;
        }
        
        .publication-header,
        .content-section {
            padding: var(--spacing-md);
        }
        
        .section-title {
            font-size: 1.125rem;
        }
    }
    
    @media (max-width: 480px) {
        .publication-title {
            font-size: 1.25rem;
        }
        
        .stat-value {
            font-size: 1.5rem;
        }
    }
</style>

<script>
    // Copy to clipboard function
    window.copyToClipboard = function(text, button) {
        navigator.clipboard.writeText(text).then(function() {
            // Show success feedback
            const originalHTML = button.innerHTML;
            button.style.color = 'var(--success-color)';
            button.innerHTML = `
                <svg width="16" height="16" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                </svg>
            `;
            
            // Reset after 2 seconds
            setTimeout(function() {
                button.style.color = '';
                button.innerHTML = originalHTML;
            }, 2000);
        }).catch(function(err) {
            console.error('Failed to copy: ', err);
            alert('Failed to copy to clipboard');
        });
    };
    
    // Auto-hide flash messages after 5 seconds
    setTimeout(function() {
        const alerts = document.querySelectorAll('.alert');
        alerts.forEach(alert => {
            alert.style.opacity = '0';
            alert.style.transition = 'opacity 0.5s';
            setTimeout(() => alert.remove(), 500);
        });
    }, 5000);
</script>