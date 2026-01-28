<?php
// View file for research publications index
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

<!-- Page Actions -->
<div class="page-actions">
    <?php if (Session::hasPermission('research_create')): ?>
    <a href="<?php echo BASE_URL; ?>/admin/research/create" class="btn btn-primary">
        <svg width="20" height="20" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd"/>
        </svg>
        Add New Publication
    </a>
    <?php endif; ?>
</div>

<!-- Statistics -->
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-title">Total Publications</div>
        <div class="stat-value"><?php echo $stats['total_publications'] ?? 0; ?></div>
    </div>
    
    <div class="stat-card success">
        <div class="stat-title">Published</div>
        <div class="stat-value"><?php echo $stats['published_count'] ?? 0; ?></div>
    </div>
    
    <div class="stat-card info">
        <div class="stat-title">Total Views</div>
        <div class="stat-value"><?php echo $stats['total_views'] ?? 0; ?></div>
    </div>
    
    <div class="stat-card warning">
        <div class="stat-title">Featured</div>
        <div class="stat-value"><?php echo $stats['featured_count'] ?? 0; ?></div>
    </div>
</div>

<!-- Filters -->
<div class="filters-section">
    <div class="filters-header">
        <h2 class="filters-title">Filters</h2>
        <a href="<?php echo BASE_URL; ?>/admin/research" class="btn btn-sm btn-outline">Clear Filters</a>
    </div>
    
    <form method="GET" class="filter-form">
        <div class="form-group">
            <label class="form-label">Status</label>
            <select name="status" class="form-control">
                <option value="">All Status</option>
                <option value="1" <?php echo ($filters['status'] ?? '') == '1' ? 'selected' : ''; ?>>Published</option>
                <option value="0" <?php echo ($filters['status'] ?? '') == '0' ? 'selected' : ''; ?>>Draft</option>
            </select>
        </div>
        
        <div class="form-group">
            <label class="form-label">Research Area</label>
            <select name="category" class="form-control">
                <option value="">All Areas</option>
                <?php foreach ($categories as $category): ?>
                <option value="<?php echo $category['slug']; ?>" <?php echo ($filters['category'] ?? '') == $category['slug'] ? 'selected' : ''; ?>>
                    <?php echo htmlspecialchars($category['name']); ?>
                </option>
                <?php endforeach; ?>
            </select>
        </div>
        
        <div class="form-group">
            <label class="form-label">Publication Type</label>
            <select name="type" class="form-control">
                <option value="">All Types</option>
                <option value="journal" <?php echo ($filters['type'] ?? '') == 'journal' ? 'selected' : ''; ?>>Journal</option>
                <option value="conference" <?php echo ($filters['type'] ?? '') == 'conference' ? 'selected' : ''; ?>>Conference</option>
                <option value="book" <?php echo ($filters['type'] ?? '') == 'book' ? 'selected' : ''; ?>>Book</option>
                <option value="thesis" <?php echo ($filters['type'] ?? '') == 'thesis' ? 'selected' : ''; ?>>Thesis</option>
                <option value="report" <?php echo ($filters['type'] ?? '') == 'report' ? 'selected' : ''; ?>>Report</option>
            </select>
        </div>
        
        <div class="form-group">
            <label class="form-label">Year</label>
            <input type="number" name="year" class="form-control" placeholder="e.g., 2024" 
                   value="<?php echo htmlspecialchars($filters['year'] ?? ''); ?>">
        </div>
        
        <div class="form-group">
            <label class="form-label">Search</label>
            <input type="text" name="search" class="form-control" placeholder="Search title, authors, keywords..."
                   value="<?php echo htmlspecialchars($filters['search'] ?? ''); ?>">
        </div>
        
        <div class="form-group">
            <label class="form-label">Order By</label>
            <select name="order_by" class="form-control">
                <option value="publication_date" <?php echo ($filters['order_by'] ?? '') == 'publication_date' ? 'selected' : ''; ?>>Date</option>
                <option value="title" <?php echo ($filters['order_by'] ?? '') == 'title' ? 'selected' : ''; ?>>Title</option>
                <option value="views_count" <?php echo ($filters['order_by'] ?? '') == 'views_count' ? 'selected' : ''; ?>>Views</option>
                <option value="citations" <?php echo ($filters['order_by'] ?? '') == 'citations' ? 'selected' : ''; ?>>Citations</option>
            </select>
        </div>
        
        <div class="form-group">
            <label class="form-label">Order Direction</label>
            <select name="order_dir" class="form-control">
                <option value="DESC" <?php echo ($filters['order_dir'] ?? '') == 'DESC' ? 'selected' : ''; ?>>Descending</option>
                <option value="ASC" <?php echo ($filters['order_dir'] ?? '') == 'ASC' ? 'selected' : ''; ?>>Ascending</option>
            </select>
        </div>
        
        <div class="form-group">
            <button type="submit" class="btn btn-primary w-100">Apply Filters</button>
        </div>
    </form>
</div>

<!-- Bulk Actions -->
<div class="bulk-actions" id="bulkActions">
    <select id="bulkAction" class="form-control">
        <option value="">Bulk Actions</option>
        <option value="publish">Publish</option>
        <option value="unpublish">Unpublish</option>
        <option value="feature">Feature</option>
        <option value="unfeature">Unfeature</option>
        <option value="delete">Delete</option>
    </select>
    <button id="applyBulkAction" class="btn btn-primary">Apply</button>
</div>

<!-- Publications List -->
<div class="publications-section">
    <?php if (empty($publications)): ?>
        <div class="empty-state">
            <svg fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-11a1 1 0 10-2 0v2H7a1 1 0 100 2h2v2a1 1 0 102 0v-2h2a1 1 0 100-2h-2V7z" clip-rule="evenodd"/>
            </svg>
            <h3>No Research Publications Found</h3>
            <p>Get started by adding your first research publication.</p>
            <?php if (Session::hasPermission('research_create')): ?>
            <a href="<?php echo BASE_URL; ?>/admin/research/create" class="btn btn-primary">
                Add First Publication
            </a>
            <?php endif; ?>
        </div>
    <?php else: ?>
        <div class="table-responsive">
            <table class="publications-table">
                <thead>
                    <tr>
                        <th style="width: 40px;">
                            <input type="checkbox" id="selectAll" class="select-all-checkbox">
                        </th>
                        <th class="publication-cell">Publication</th>
                        <th style="width: 120px;">Research Area</th>
                        <th style="width: 100px;">Type</th>
                        <th style="width: 100px;">Date</th>
                        <th style="width: 100px;">Status</th>
                        <th style="width: 120px;" class="action-cell">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($publications as $pub): ?>
                    <tr>
                        <td>
                            <input type="checkbox" class="publication-checkbox" value="<?php echo $pub['id']; ?>">
                        </td>
                        <td class="publication-cell">
                            <div class="publication-title">
                                <?php echo htmlspecialchars($pub['title'] ?? ''); ?>
                                <?php if ($pub['is_featured'] ?? false): ?>
                                <span class="badge bg-warning">Featured</span>
                                <?php endif; ?>
                            </div>
                            <div class="publication-authors">
                                <?php echo htmlspecialchars(substr($pub['authors'] ?? '', 0, 150)); ?><?php echo strlen($pub['authors'] ?? '') > 150 ? '...' : ''; ?>
                            </div>
                        </td>
                        <td>
                            <span class="badge bg-info"><?php echo htmlspecialchars($pub['category_name'] ?? $pub['research_area'] ?? ''); ?></span>
                        </td>
                        <td>
                            <span class="badge bg-secondary"><?php echo ucfirst($pub['publication_type'] ?? ''); ?></span>
                        </td>
                        <td><?php echo date('M d, Y', strtotime($pub['publication_date'] ?? date('Y-m-d'))); ?></td>
                        <td>
                            <?php if ($pub['is_published'] ?? false): ?>
                                <span class="badge bg-success">Published</span>
                            <?php else: ?>
                                <span class="badge bg-secondary">Draft</span>
                            <?php endif; ?>
                        </td>
                        <td class="action-cell">
                            <div class="action-buttons">
                                <a href="<?php echo BASE_URL; ?>/admin/research/<?php echo $pub['id']; ?>" 
                                   class="btn btn-sm btn-outline" title="View">
                                    <svg width="16" height="16" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M10 12a2 2 0 100-4 2 2 0 000 4z"/>
                                        <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd"/>
                                    </svg>
                                </a>
                                
                                <?php if (Session::hasPermission('research_edit')): ?>
                                <a href="<?php echo BASE_URL; ?>/admin/research/<?php echo $pub['id']; ?>/edit" 
                                   class="btn btn-sm btn-outline" title="Edit">
                                    <svg width="16" height="16" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"/>
                                    </svg>
                                </a>
                                <?php endif; ?>
                                
                                <?php if (Session::hasPermission('research_delete')): ?>
                                <button type="button" 
                                        class="btn btn-sm btn-danger delete-publication" 
                                        data-id="<?php echo $pub['id']; ?>" 
                                        data-title="<?php echo htmlspecialchars($pub['title'] ?? ''); ?>"
                                        title="Delete">
                                    <svg width="16" height="16" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                    </svg>
                                </button>
                                <?php endif; ?>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal-overlay" id="deleteModal">
    <div class="modal">
        <div class="modal-header">
            <h3 class="modal-title">Confirm Delete</h3>
        </div>
        <div class="modal-body">
            <p>Are you sure you want to delete "<span id="deletePublicationTitle"></span>"?</p>
            <p class="text-danger">This action cannot be undone.</p>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-outline" onclick="hideDeleteModal()">Cancel</button>
            <form id="deleteForm" method="POST" action="" style="display: inline;">
                <input type="hidden" name="csrf_token" value="<?php echo Session::getCSRFToken(); ?>">
                <button type="submit" class="btn btn-danger">Delete Publication</button>
            </form>
        </div>
    </div>
</div>

<style>
    /* INDEX PAGE SPECIFIC STYLES */
    .page-actions {
        display: flex;
        gap: var(--spacing-sm);
        margin-bottom: var(--spacing-xl);
        max-width: 100%;
    }
    
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: var(--spacing-lg);
        margin-bottom: var(--spacing-xl);
        max-width: 100%;
    }
    
    .stat-card {
        background: white;
        border-radius: 12px;
        padding: var(--spacing-lg);
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        border-left: 4px solid var(--primary-color);
        transition: all 0.3s ease;
    }
    
    .stat-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.12);
    }
    
    .stat-card.success {
        border-left-color: var(--success-color);
    }
    
    .stat-card.info {
        border-left-color: var(--info-color);
    }
    
    .stat-card.warning {
        border-left-color: var(--warning-color);
    }
    
    .stat-title {
        font-size: 0.75rem;
        color: var(--gray-600);
        text-transform: uppercase;
        letter-spacing: 0.05em;
        margin-bottom: var(--spacing-sm);
    }
    
    .stat-value {
        font-size: 1.75rem;
        font-weight: 700;
        color: var(--gray-800);
        margin-bottom: var(--spacing-xs);
    }
    
    .filters-section {
        background: white;
        border-radius: 12px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        padding: var(--spacing-xl);
        margin-bottom: var(--spacing-xl);
        transition: all 0.3s ease;
        max-width: 100%;
    }
    
    .filters-section:hover {
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.12);
    }
    
    .filters-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: var(--spacing-xl);
        flex-wrap: wrap;
        gap: var(--spacing-md);
        max-width: 100%;
    }
    
    .filters-title {
        font-size: 1.125rem;
        font-weight: 600;
        color: var(--gray-800);
    }
    
    .filter-form {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: var(--spacing-lg);
        max-width: 100%;
    }
    
    .form-group {
        margin-bottom: 0;
        max-width: 100%;
    }
    
    .form-label {
        display: block;
        margin-bottom: var(--spacing-sm);
        font-size: 0.875rem;
        font-weight: 500;
        color: var(--gray-700);
    }
    
    .form-control {
        width: 100%;
        padding: 0.75rem 1rem;
        border: 1px solid var(--gray-300);
        border-radius: 8px;
        font-size: 0.875rem;
        transition: all 0.2s;
        background: white;
    }
    
    .form-control:focus {
        outline: none;
        border-color: var(--primary-color);
        box-shadow: 0 0 0 3px rgba(66, 153, 225, 0.15);
    }
    
    select.form-control {
        appearance: none;
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%23718096' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='M6 8l4 4 4-4'/%3e%3c/svg%3e");
        background-position: right 1rem center;
        background-repeat: no-repeat;
        background-size: 1.5em 1.5em;
        padding-right: 3rem;
    }
    
    .bulk-actions {
        display: none;
        align-items: center;
        gap: var(--spacing-md);
        background: white;
        padding: var(--spacing-lg);
        border-radius: 8px;
        margin-bottom: var(--spacing-lg);
        flex-wrap: wrap;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        animation: slideIn 0.3s ease;
        max-width: 100%;
    }
    
    .bulk-actions.show {
        display: flex;
    }
    
    .publications-section {
        background: white;
        border-radius: 12px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        overflow: hidden;
        transition: all 0.3s ease;
        max-width: 100%;
    }
    
    .publications-section:hover {
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.12);
    }
    
    .table-responsive {
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
        border-radius: 12px;
        max-width: 100%;
    }
    
    .publications-table {
        width: 100%;
        border-collapse: collapse;
        min-width: 1000px;
    }
    
    .publications-table th {
        text-align: left;
        padding: var(--spacing-lg);
        background: var(--gray-50);
        font-weight: 600;
        color: var(--gray-700);
        border-bottom: 2px solid var(--gray-200);
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        white-space: nowrap;
        position: sticky;
        top: 0;
        z-index: 10;
    }
    
    .publications-table td {
        padding: var(--spacing-lg);
        border-bottom: 1px solid var(--gray-100);
        vertical-align: middle;
        transition: background 0.2s;
    }
    
    .publications-table tr:last-child td {
        border-bottom: none;
    }
    
    .publications-table tr:hover td {
        background: var(--gray-50);
    }
    
    .publication-title {
        font-weight: 600;
        color: var(--gray-800);
        margin-bottom: 8px;
        word-break: break-word;
        line-height: 1.4;
        font-size: 1rem;
    }
    
    .publication-authors {
        font-size: 0.875rem;
        color: var(--gray-600);
        line-height: 1.5;
        word-break: break-word;
    }
    
    .badge {
        display: inline-block;
        padding: 0.25rem 0.75rem;
        font-size: 0.75rem;
        font-weight: 600;
        border-radius: 20px;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        white-space: nowrap;
    }
    
    .bg-success {
        background: rgba(56, 161, 105, 0.1);
        color: var(--success-color);
        border: 1px solid rgba(56, 161, 105, 0.2);
    }
    
    .bg-secondary {
        background: var(--gray-100);
        color: var(--gray-700);
        border: 1px solid var(--gray-200);
    }
    
    .bg-info {
        background: rgba(49, 130, 206, 0.1);
        color: var(--info-color);
        border: 1px solid rgba(49, 130, 206, 0.2);
    }
    
    .bg-warning {
        background: rgba(214, 158, 46, 0.1);
        color: var(--warning-color);
        border: 1px solid rgba(214, 158, 46, 0.2);
    }
    
    .action-cell {
        white-space: nowrap;
    }
    
    .action-buttons {
        display: flex;
        gap: var(--spacing-sm);
        flex-wrap: wrap;
    }
    
    .empty-state {
        text-align: center;
        padding: var(--spacing-2xl) var(--spacing-md);
        color: var(--gray-600);
        max-width: 100%;
    }
    
    .empty-state svg {
        width: 64px;
        height: 64px;
        margin-bottom: var(--spacing-lg);
        color: var(--gray-300);
    }
    
    .empty-state h3 {
        font-size: 1.25rem;
        margin-bottom: var(--spacing-sm);
        color: var(--gray-700);
    }
    
    .empty-state p {
        margin-bottom: var(--spacing-lg);
    }
    
    .modal-overlay {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0, 0, 0, 0.5);
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 1100;
        opacity: 0;
        visibility: hidden;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        padding: var(--spacing-md);
        backdrop-filter: blur(4px);
    }
    
    .modal-overlay.active {
        opacity: 1;
        visibility: visible;
    }
    
    .modal {
        background: white;
        border-radius: 16px;
        padding: var(--spacing-xl);
        width: 100%;
        max-width: 400px;
        max-height: 90vh;
        overflow-y: auto;
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
        animation: modalSlideIn 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }
    
    @keyframes modalSlideIn {
        from {
            opacity: 0;
            transform: translateY(-20px) scale(0.95);
        }
        to {
            opacity: 1;
            transform: translateY(0) scale(1);
        }
    }
    
    .modal-header {
        margin-bottom: var(--spacing-lg);
    }
    
    .modal-title {
        font-size: 1.25rem;
        font-weight: 600;
        color: var(--gray-800);
    }
    
    .modal-body {
        margin-bottom: var(--spacing-xl);
    }
    
    .modal-footer {
        display: flex;
        gap: var(--spacing-md);
        justify-content: flex-end;
        flex-wrap: wrap;
    }
    
    @media (max-width: 1200px) {
        .stats-grid {
            grid-template-columns: repeat(2, 1fr);
        }
        
        .filter-form {
            grid-template-columns: repeat(3, 1fr);
        }
    }
    
    @media (max-width: 768px) {
        .stats-grid {
            grid-template-columns: 1fr;
            gap: var(--spacing-md);
        }
        
        .stat-card {
            padding: var(--spacing-md);
        }
        
        .filter-form {
            grid-template-columns: 1fr;
            gap: var(--spacing-md);
        }
        
        .page-actions {
            flex-direction: column;
            align-items: stretch;
            gap: var(--spacing-sm);
        }
        
        .btn {
            width: 100%;
            justify-content: center;
        }
        
        .filters-section {
            padding: var(--spacing-lg);
        }
        
        .filters-header {
            flex-direction: column;
            align-items: stretch;
            gap: var(--spacing-md);
        }
        
        .modal-footer {
            flex-direction: column;
        }
        
        .modal-footer .btn {
            width: 100%;
        }
        
        .action-buttons {
            justify-content: center;
        }
        
        .bulk-actions {
            flex-direction: column;
            align-items: stretch;
        }
        
        .bulk-actions select,
        .bulk-actions .btn {
            width: 100%;
        }
        
        .publications-table {
            min-width: 800px;
        }
        
        .publications-table th,
        .publications-table td {
            padding: var(--spacing-md);
        }
    }
    
    @media (max-width: 640px) {
        .filters-section {
            padding: var(--spacing-md);
        }
        
        .stat-value {
            font-size: 1.5rem;
        }
    }
</style>

<script>
    // Delete Modal Functions
    function showDeleteModal(id, title) {
        document.getElementById('deletePublicationTitle').textContent = title;
        document.getElementById('deleteForm').action = '<?php echo BASE_URL; ?>/admin/research/' + id + '/delete';
        document.getElementById('deleteModal').classList.add('active');
        document.body.style.overflow = 'hidden';
    }
    
    function hideDeleteModal() {
        document.getElementById('deleteModal').classList.remove('active');
        document.body.style.overflow = '';
    }
    
    // Close modal when clicking outside
    document.getElementById('deleteModal').addEventListener('click', function(e) {
        if (e.target === this) {
            hideDeleteModal();
        }
    });
    
    // Close modal with Escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            hideDeleteModal();
        }
    });
    
    // Delete publication buttons
    document.querySelectorAll('.delete-publication').forEach(button => {
        button.addEventListener('click', function() {
            const id = this.getAttribute('data-id');
            const title = this.getAttribute('data-title');
            showDeleteModal(id, title);
        });
    });
    
    // Bulk Actions
    const selectAll = document.getElementById('selectAll');
    const checkboxes = document.querySelectorAll('.publication-checkbox');
    const bulkActions = document.getElementById('bulkActions');
    
    if (selectAll && checkboxes.length > 0) {
        selectAll.addEventListener('change', function() {
            checkboxes.forEach(cb => cb.checked = this.checked);
            updateBulkActions();
        });
        
        checkboxes.forEach(cb => {
            cb.addEventListener('change', updateBulkActions);
        });
        
        function updateBulkActions() {
            const checked = document.querySelectorAll('.publication-checkbox:checked');
            if (checked.length > 0) {
                bulkActions.classList.add('show');
                // Scroll bulk actions into view on mobile
                if (window.innerWidth < 768) {
                    bulkActions.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
                }
            } else {
                bulkActions.classList.remove('show');
            }
        }
        
        // Apply bulk action
        document.getElementById('applyBulkAction').addEventListener('click', function() {
            const action = document.getElementById('bulkAction').value;
            const selectedIds = Array.from(document.querySelectorAll('.publication-checkbox:checked'))
                .map(cb => cb.value);
            
            if (!action || selectedIds.length === 0) {
                alert('Please select an action and at least one publication.');
                return;
            }
            
            if (action === 'delete' && !confirm(`Are you sure you want to delete ${selectedIds.length} publication(s)?`)) {
                return;
            }
            
            // Create form and submit
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '<?php echo BASE_URL; ?>/admin/research/bulk-action';
            
            const csrfToken = document.createElement('input');
            csrfToken.type = 'hidden';
            csrfToken.name = 'csrf_token';
            csrfToken.value = '<?php echo Session::getCSRFToken(); ?>';
            form.appendChild(csrfToken);
            
            const actionInput = document.createElement('input');
            actionInput.type = 'hidden';
            actionInput.name = 'action';
            actionInput.value = action;
            form.appendChild(actionInput);
            
            selectedIds.forEach(id => {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'selected_ids[]';
                input.value = id;
                form.appendChild(input);
            });
            
            document.body.appendChild(form);
            form.submit();
        });
    }
    
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