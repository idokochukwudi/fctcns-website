<?php
/**
 * Nominal Roll Index View
 * Main listing page with search, filters, and data table
 * Enhanced for improved latency, international standards (WCAG 2.1 accessibility, HTML5 semantics),
 * modern well-designed interface, and full responsiveness across all screen sizes.
 * 
 * Enhancements:
 * - Latency: Optimized PHP loops, removed redundant checks, minified inline CSS/JS where possible.
 *   Deferred non-critical JS, used efficient selectors.
 * - Standards: Added ARIA attributes for accessibility, semantic HTML (e.g., <section>, <article>),
 *   ensured keyboard navigation, high contrast ratios.
 * - Interface: Modernized design with cleaner typography, subtle animations, intuitive layout.
 * - Responsiveness: Enhanced media queries for mobile/tablet/desktop, used CSS Grid/Flexbox.
 *   Ensured touch-friendly elements.
 */
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nominal Roll Management</title>
    <!-- Assuming external CSS like Font Awesome and Bootstrap are linked elsewhere; inline critical styles here -->
    <style>
/* Minified and optimized CSS */
.nominal-roll-container{padding:20px;max-width:1440px;margin:0 auto}.page-header{margin-bottom:24px}.header-content{display:flex;justify-content:space-between;align-items:flex-start;flex-wrap:wrap;gap:16px}.header-title h1{font-size:24px;font-weight:600;color:#333;margin:0 0 4px 0}.header-title .subtitle{color:#666;font-size:14px;margin:0}.header-actions{display:flex;gap:10px;flex-wrap:wrap}.header-actions .btn{padding:10px 16px;font-size:14px;height:40px;display:inline-flex;align-items:center;justify-content:center;min-width:120px;transition:all .2s}.header-actions .btn:hover{transform:translateY(-2px);box-shadow:0 4px 6px rgba(0,0,0,.1)}.header-actions .btn-info{background:#17a2b8;color:#fff;border-color:#17a2b8}.header-actions .btn-info:hover{background:#138496;border-color:#117a8b}.stats-cards{display:grid;grid-template-columns:repeat(auto-fit,minmax(200px,1fr));gap:16px;margin-bottom:24px}.stat-card{background:#fff;border-radius:8px;padding:16px;box-shadow:0 1px 3px rgba(0,0,0,.1);display:flex;align-items:center;gap:16px;transition:all .2s}.stat-card:hover{transform:translateY(-2px);box-shadow:0 4px 6px rgba(0,0,0,.1)}.stat-icon{width:48px;height:48px;border-radius:8px;display:flex;align-items:center;justify-content:center;font-size:20px;color:#fff;flex-shrink:0}.stat-icon.bg-primary{background:#3490dc}.stat-icon.bg-success{background:#38a169}.stat-icon.bg-info{background:#17a2b8}.stat-icon.bg-warning{background:#d69e2e}.stat-content h3{font-size:24px;font-weight:700;margin:0 0 4px 0;color:#333;line-height:1}.stat-content p{font-size:12px;color:#666;margin:0;text-transform:uppercase;letter-spacing:.5px}.search-filters-card{background:#fff;border-radius:8px;padding:16px;box-shadow:0 1px 3px rgba(0,0,0,.1);margin-bottom:24px}.search-row{display:flex;gap:12px;align-items:center;flex-wrap:wrap}.search-input-group{flex:1;display:flex;gap:8px;min-width:300px}.input-with-icon{flex:1;position:relative}.input-with-icon i{position:absolute;left:12px;top:50%;transform:translateY(-50%);color:#999;z-index:1}.input-with-icon input{width:100%;padding:10px 12px 10px 36px;border:1px solid #ddd;border-radius:4px;font-size:14px;height:40px;box-sizing:border-box;transition:all .2s}.input-with-icon input:focus{outline:none;border-color:#3490dc;box-shadow:0 0 0 2px rgba(52,144,220,.1)}.btn-search{height:40px;padding:0 16px;min-width:100px;display:flex;align-items:center;justify-content:center}.btn-clear-search{height:40px;width:40px;padding:0;display:flex;align-items:center;justify-content:center;border-radius:4px}.quick-actions{display:flex;gap:8px}.advanced-filters{margin-top:16px;padding-top:16px;border-top:1px solid #eee}.filter-grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(200px,1fr));gap:16px;margin-bottom:16px}.filter-actions{display:flex;gap:8px;justify-content:flex-start}.filter-actions .btn{height:40px;padding:0 16px;min-width:120px;display:flex;align-items:center;justify-content:center}.active-filters-display{background:#f8f9fa;border-radius:6px;padding:12px;margin-top:16px}.active-filters-header{margin-bottom:8px}.active-filters-tags{display:flex;flex-wrap:wrap;gap:8px}.filter-tag{background:#fff;border:1px solid #dee2e6;border-radius:20px;padding:4px 12px;font-size:13px;display:inline-flex;align-items:center;gap:6px}.filter-tag .remove-filter{color:#dc3545;text-decoration:none;font-size:12px;cursor:pointer}.filter-tag .remove-filter:hover{color:#bd2130}.data-table-card{background:#fff;border-radius:8px;box-shadow:0 1px 3px rgba(0,0,0,.1);overflow:hidden}.table-header{display:flex;justify-content:space-between;align-items:center;padding:16px;border-bottom:1px solid #e2e8f0;background:#f8f9fa}.table-summary{font-size:14px;color:#666}.table-actions{display:flex;gap:8px;align-items:center}.table-responsive{overflow-x:auto}.data-table{width:100%;border-collapse:collapse}.data-table thead{background:#f7fafc}.data-table th{padding:12px 16px;text-align:left;font-weight:600;color:#4a5568;font-size:12px;text-transform:uppercase;letter-spacing:.5px;border-bottom:2px solid #e2e8f0;white-space:nowrap}.data-table td{padding:12px 16px;border-bottom:1px solid #e2e8f0;font-size:14px;vertical-align:middle}.data-table tbody tr{transition:background .2s}.data-table tbody tr:hover{background:#f8fafc;cursor:pointer}.employee-name{line-height:1.4;min-width:200px}.employee-name strong{display:block;color:#333;font-weight:600}.employee-name .small{font-size:12px;color:#666}.employee-name .extra-info{font-size:12px;color:#6c757d;margin-top:2px}.extra-info i{margin-right:4px}.state-info{line-height:1.4}.state-name{display:block;font-weight:500}.date-display{line-height:1.4}.badge{display:inline-block;padding:4px 8px;font-size:12px;font-weight:600;border-radius:4px;white-space:nowrap}.badge-sm{padding:2px 6px;font-size:11px}.badge-info{background:#e6f7ff;color:#1890ff}.badge-pink{background:#fff0f6;color:#eb2f96}.badge-secondary{background:#f8f9fa;color:#6c757d}.badge-warning{background:#fff7e6;color:#fa8c16}.badge-light{background:#f8f9fa;color:#6c757d;border:1px solid #dee2e6}.badge-active{background:#dc3545;color:#fff}.rank-badge{background:#e8f5e8;color:#28a745;padding:4px 8px;border-radius:4px;font-size:13px;font-weight:500}.action-buttons{display:flex;gap:8px;flex-wrap:wrap;min-width:250px}.action-buttons .btn{padding:8px 12px;font-size:13px;min-width:80px;height:36px;display:inline-flex;align-items:center;justify-content:center;border-radius:4px;transition:all .2s;gap:6px;text-decoration:none;border:none;cursor:pointer;white-space:nowrap}.action-buttons .btn i{font-size:12px;margin:0}.action-buttons .btn .btn-text{font-size:12px;font-weight:500}.action-buttons .btn-info{background:#3490dc;color:#fff}.action-buttons .btn-info:hover{background:#2779bd}.action-buttons .btn-warning{background:#f6993f;color:#fff}.action-buttons .btn-warning:hover{background:#e08629}.action-buttons .btn-danger{background:#e3342f;color:#fff}.action-buttons .btn-danger:hover{background:#c53030}.action-buttons .btn-success{background:#38a169;color:#fff}.action-buttons .btn-success:hover{background:#2f855a}.action-buttons .btn-dark{background:#343a40;color:#fff}.action-buttons .btn-dark:hover{background:#23272b}.action-buttons form{display:inline;margin:0}.action-buttons form button{margin:0}.pagination{display:flex;justify-content:space-between;align-items:center;padding:20px;border-top:1px solid #e2e8f0;flex-wrap:wrap;gap:16px}.pagination-info{font-size:14px;color:#666;flex:1;min-width:200px}.pagination-controls{display:flex;gap:6px;flex-wrap:wrap}.pagination-controls .btn{min-width:36px;height:36px;padding:0 8px;font-size:13px;display:flex;align-items:center;justify-content:center;border-radius:4px}.pagination-controls .btn-primary{background:#3490dc;color:#fff;border:1px solid #3490dc}.pagination-controls .btn-primary:hover{background:#2779bd;border-color:#2779bd}.pagination-controls .btn-outline{background:transparent;color:#666;border:1px solid #ddd}.pagination-controls .btn-outline:hover{background:#f8f9fa;border-color:#ccc}.pagination-jump{display:flex;align-items:center;gap:8px}.pagination-jump .input-group{width:auto}.empty-state{text-align:center;padding:60px 24px}.empty-state i{font-size:64px;color:#cbd5e0;margin-bottom:20px;opacity:.5}.empty-state h3{font-size:20px;font-weight:600;color:#4a5568;margin:0 0 12px 0}.empty-state p{color:#a0aec0;font-size:16px;margin:0 0 30px 0}.empty-state-actions{display:flex;gap:12px;justify-content:center;flex-wrap:wrap}.empty-state .btn{padding:12px 24px;font-size:16px;min-width:200px}.btn{display:inline-flex;align-items:center;justify-content:center;gap:8px;padding:10px 20px;border-radius:6px;font-size:14px;font-weight:500;text-decoration:none;cursor:pointer;border:1px solid transparent;transition:all .2s;height:40px;box-sizing:border-box;white-space:nowrap}.btn-primary{background:#3490dc;color:#fff;border-color:#3490dc}.btn-primary:hover{background:#2779bd;border-color:#2779bd}.btn-secondary{background:#6c757d;color:#fff;border-color:#6c757d}.btn-secondary:hover{background:#5a6268;border-color:#5a6268}.btn-success{background:#38a169;color:#fff;border-color:#38a169}.btn-success:hover{background:#2f855a;border-color:#2f855a}.btn-info{background:#17a2b8;color:#fff;border-color:#17a2b8}.btn-info:hover{background:#138496;border-color:#117a8b}.btn-outline{background:transparent;color:#4a5568;border-color:#e2e8f0}.btn-outline:hover{background:#f8fafc;border-color:#cbd5e0}.btn-sm{padding:6px 12px;font-size:13px;height:32px}.btn-danger{background:#e53e3e;color:#fff;border-color:#e53e3e}.btn-danger:hover{background:#c53030;border-color:#c53030}.form-control{width:100%;padding:10px 12px;border:1px solid #ddd;border-radius:4px;font-size:14px;height:40px;box-sizing:border-box;transition:all .2s}.form-control:focus{outline:none;border-color:#3490dc;box-shadow:0 0 0 2px rgba(52,144,220,.1)}.form-group{margin-bottom:0}.form-group label{display:block;margin-bottom:6px;font-size:12px;font-weight:600;color:#4a5568;text-transform:uppercase;letter-spacing:.5px}.alert{padding:12px 16px;border-radius:6px;margin-bottom:20px;display:flex;align-items:center;gap:10px;background:#fffaf0;border:1px solid #fed7d7;color:#9c4221}.alert i{font-size:16px;color:#d69e2e}@media (max-width:1024px){.stats-cards{grid-template-columns:repeat(2,1fr)}.action-buttons .btn .btn-text{display:none}.action-buttons .btn{min-width:36px;width:36px;padding:8px!important;justify-content:center}.action-buttons .btn i{margin:0}}@media (max-width:768px){.nominal-roll-container{padding:15px}.header-content{flex-direction:column;align-items:stretch;gap:12px}.header-actions{justify-content:flex-start;flex-wrap:wrap}.header-actions .btn{min-width:100px;flex:1;max-width:calc(50% - 5px)}.search-row{flex-direction:column;align-items:stretch}.search-input-group{min-width:100%;flex-direction:column}.btn-search{width:100%}.quick-actions{width:100%;justify-content:center}.stats-cards{grid-template-columns:1fr;gap:12px}.table-header{flex-direction:column;gap:12px;align-items:stretch}.table-actions{justify-content:center}.pagination{flex-direction:column;gap:16px;align-items:stretch;text-align:center}.pagination-controls{justify-content:center}.filter-grid{grid-template-columns:1fr}.filter-actions{flex-direction:column}.filter-actions .btn{width:100%}.action-buttons{gap:6px}.action-buttons .btn{flex:1;min-width:0;max-width:calc(25% - 5px)}.data-table th:nth-child(8),.data-table td:nth-child(8){display:none}}.serial-column{width:50px}.employee-number{width:150px}.name-column{min-width:250px}.sex-column{width:80px}.rank-column{width:150px}.grade-column{width:120px}.state-column{width:150px}.date-column{width:150px}.actions-column{width:300px}@media (max-width:480px){.header-actions .btn{max-width:100%;width:100%}.stats-cards{grid-template-columns:1fr}.action-buttons{flex-wrap:wrap;justify-content:center}.action-buttons .btn{flex:0 0 calc(50% - 6px);max-width:calc(50% - 6px);margin-bottom:6px}.data-table th:nth-child(7),.data-table td:nth-child(7){display:none}.employee-name{min-width:150px}.empty-state-actions{flex-direction:column}.empty-state .btn{width:100%}}@media print{.header-actions,.search-filters-card,.action-buttons .btn:not(.btn-info),.action-buttons form,.pagination,.table-header{display:none!important}.data-table-card{box-shadow:none!important;border:1px solid #ddd!important}.data-table th,.data-table td{border:1px solid #ddd!important}.badge{background:#f8f9fa!important;color:#000!important;border:1px solid #ddd!important}}
    </style>
</head>
<body>
<div class="nominal-roll-container" role="main">
    <!-- Header with Stats and Actions -->
    <section class="page-header" aria-labelledby="header-title">
        <div class="header-content">
            <div class="header-title">
                <h1 id="header-title">Nominal Roll Management</h1>
                <p class="subtitle">Manage employee records and details</p>
            </div>
            
            <div class="header-actions">
                <?php if ($isEditor && ($editingEnabled || $isSuperAdmin)): ?>
                <a href="<?php echo $baseUrl; ?>/admin/nominal-roll/create" class="btn btn-primary">
                    <i class="fas fa-plus" aria-hidden="true"></i> Add Employee
                </a>
                <?php endif; ?>
                
                <?php if ($isEditor): ?>
                <a href="<?php echo $baseUrl; ?>/admin/nominal-roll/bulk-upload" class="btn btn-secondary">
                    <i class="fas fa-upload" aria-hidden="true"></i> Bulk Upload
                </a>
                <?php endif; ?>
                
                <a href="<?php echo $baseUrl; ?>/admin/nominal-roll/reports" class="btn btn-info">
                    <i class="fas fa-chart-bar" aria-hidden="true"></i> Reports
                </a>
                
                <a href="<?php echo $baseUrl; ?>/admin/nominal-roll/export?<?php echo http_build_query($filters); ?>" 
                   class="btn btn-success" target="_blank">
                    <i class="fas fa-download" aria-hidden="true"></i> Export CSV
                </a>
                
                <?php if ($isSuperAdmin): ?>
                <a href="<?php echo $baseUrl; ?>/admin/nominal-roll/settings" class="btn btn-outline">
                    <i class="fas fa-cog" aria-hidden="true"></i> Settings
                </a>
                <?php endif; ?>
            </div>
        </div>
        
        <!-- Editing Status Alert -->
        <?php if (!$editingEnabled && !$isSuperAdmin): ?>
        <div class="alert alert-warning alert-dismissible" role="alert">
            <i class="fas fa-lock" aria-hidden="true"></i>
            <strong>Editing is disabled.</strong> Only Super Admin can modify records.
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        <?php endif; ?>
    </section>
    
    <!-- Statistics Cards -->
    <section class="stats-cards" aria-label="Employee Statistics">
        <article class="stat-card">
            <div class="stat-icon bg-primary">
                <i class="fas fa-users" aria-hidden="true"></i>
            </div>
            <div class="stat-content">
                <h3><?php echo number_format($stats['total_employees'] ?? ($stats['total'] ?? 0)); ?></h3>
                <p>Total Employees</p>
            </div>
        </article>
        
        <article class="stat-card">
            <div class="stat-icon bg-success">
                <i class="fas fa-venus" aria-hidden="true"></i>
            </div>
            <div class="stat-content">
                <h3><?php 
                    $femaleCount = $stats['female_count'] ?? (isset($stats['by_sex']) ? array_reduce($stats['by_sex'], function($carry, $item) {
                        return $item['sex'] === 'Female' ? $item['count'] : $carry;
                    }, 0) : 0);
                    echo number_format($femaleCount);
                ?></h3>
                <p>Female Employees</p>
            </div>
        </article>
        
        <article class="stat-card">
            <div class="stat-icon bg-info">
                <i class="fas fa-mars" aria-hidden="true"></i>
            </div>
            <div class="stat-content">
                <h3><?php 
                    $maleCount = $stats['male_count'] ?? (isset($stats['by_sex']) ? array_reduce($stats['by_sex'], function($carry, $item) {
                        return $item['sex'] === 'Male' ? $item['count'] : $carry;
                    }, 0) : 0);
                    echo number_format($maleCount);
                ?></h3>
                <p>Male Employees</p>
            </div>
        </article>
        
        <article class="stat-card">
            <div class="stat-icon bg-warning">
                <i class="fas fa-sync-alt" aria-hidden="true"></i>
            </div>
            <div class="stat-content">
                <h3><?php echo number_format($stats['recent_updates'] ?? ($stats['updated_count'] ?? 0)); ?></h3>
                <p>Updated (7 days)</p>
            </div>
        </article>
    </section>
    
    <!-- Search and Filters -->
    <section class="search-filters-card" aria-label="Search and Filters">
        <form method="GET" action="<?php echo $baseUrl; ?>/admin/nominal-roll" class="search-form" id="searchForm">
            <input type="hidden" name="filtered" value="1">
            
            <div class="search-row">
                <div class="search-input-group">
                    <div class="input-with-icon">
                        <i class="fas fa-search" aria-hidden="true"></i>
                        <input type="text" 
                               name="search" 
                               id="searchInput"
                               placeholder="Search by name, employee number, state..." 
                               value="<?php echo htmlspecialchars($filters['search'] ?? ''); ?>"
                               class="form-control"
                               aria-label="Search employees">
                    </div>
                    <button type="submit" class="btn btn-primary btn-search">
                        <i class="fas fa-search" aria-hidden="true"></i> Search
                    </button>
                    <?php if (!empty($filters['search'])): ?>
                    <a href="<?php echo $baseUrl; ?>/admin/nominal-roll?<?php echo http_build_query(array_diff_key($filters, ['search' => ''])); ?>" 
                       class="btn btn-outline btn-clear-search" title="Clear search" aria-label="Clear search">
                        <i class="fas fa-times" aria-hidden="true"></i>
                    </a>
                    <?php endif; ?>
                </div>
                
                <button type="button" class="btn btn-outline" id="toggleFilters" aria-expanded="false" aria-controls="advancedFilters">
                    <i class="fas fa-filter" aria-hidden="true"></i> Filters
                    <span class="badge" id="activeFiltersCount" aria-hidden="true">
                        <?php 
                        $activeFilters = array_filter($filters ?? [], fn($v, $k) => !empty($v) && !in_array($k, ['search', 'page', 'filtered']), ARRAY_FILTER_USE_BOTH);
                        echo count($activeFilters) > 0 ? count($activeFilters) : '';
                        ?>
                    </span>
                </button>
                
                <div class="quick-actions">
                    <a href="<?php echo $baseUrl; ?>/admin/nominal-roll/reports" class="btn btn-sm btn-outline-info" title="Generate Reports" aria-label="Quick Report">
                        <i class="fas fa-chart-bar" aria-hidden="true"></i> Quick Report
                    </a>
                </div>
            </div>
            
            <div class="advanced-filters" id="advancedFilters" style="display: <?php echo !empty($filters['filtered']) ? 'block' : 'none'; ?>;" role="region" aria-labelledby="toggleFilters">
                <div class="filter-grid">
                    <div class="form-group">
                        <label for="state">State</label>
                        <select name="state" id="state" class="form-control filter-select" aria-label="Filter by State">
                            <option value="">All States</option>
                            <?php foreach ($filterOptions['states'] ?? [] as $state): ?>
                            <option value="<?php echo htmlspecialchars($state); ?>" <?php echo ($filters['state'] ?? '') === $state ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($state); ?>
                            </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="grade_level">Grade Level</label>
                        <select name="grade_level" id="grade_level" class="form-control filter-select" aria-label="Filter by Grade Level">
                            <option value="">All Grade Levels</option>
                            <?php foreach ($filterOptions['grade_levels'] ?? [] as $grade): ?>
                            <option value="<?php echo htmlspecialchars($grade); ?>" <?php echo ($filters['grade_level'] ?? '') === $grade ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($grade); ?>
                            </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="rank">Rank</label>
                        <select name="rank" id="rank" class="form-control filter-select" aria-label="Filter by Rank">
                            <option value="">All Ranks</option>
                            <?php foreach ($filterOptions['ranks'] ?? [] as $rank): ?>
                            <option value="<?php echo htmlspecialchars($rank); ?>" <?php echo ($filters['rank'] ?? '') === $rank ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($rank); ?>
                            </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="sex">Sex</label>
                        <select name="sex" id="sex" class="form-control filter-select" aria-label="Filter by Sex">
                            <option value="">All</option>
                            <option value="Male" <?php echo ($filters['sex'] ?? '') === 'Male' ? 'selected' : ''; ?>>Male</option>
                            <option value="Female" <?php echo ($filters['sex'] ?? '') === 'Female' ? 'selected' : ''; ?>>Female</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="department">Department</label>
                        <select name="department" id="department" class="form-control filter-select" aria-label="Filter by Department">
                            <option value="">All Departments</option>
                            <?php foreach ($filterOptions['departments'] ?? [] as $dept): ?>
                            <option value="<?php echo htmlspecialchars($dept); ?>" <?php echo ($filters['department'] ?? '') === $dept ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($dept); ?>
                            </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="status">Status</label>
                        <select name="status" id="status" class="form-control filter-select" aria-label="Filter by Status">
                            <option value="">All Status</option>
                            <option value="active" <?php echo ($filters['status'] ?? '') === 'active' ? 'selected' : ''; ?>>Active</option>
                            <option value="inactive" <?php echo ($filters['status'] ?? '') === 'inactive' ? 'selected' : ''; ?>>Inactive</option>
                            <option value="retired" <?php echo ($filters['status'] ?? '') === 'retired' ? 'selected' : ''; ?>>Retired</option>
                        </select>
                    </div>
                </div>
                
                <div class="filter-actions">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-check" aria-hidden="true"></i> Apply Filters
                    </button>
                    <a href="<?php echo $baseUrl; ?>/admin/nominal-roll" class="btn btn-outline btn-clear-filters">
                        <i class="fas fa-times" aria-hidden="true"></i> Clear All
                    </a>
                    <button type="button" class="btn btn-outline" id="saveFilterSet" title="Save this filter set" aria-label="Save Filters">
                        <i class="fas fa-save" aria-hidden="true"></i> Save Filters
                    </button>
                </div>
                
                <?php if (count($activeFilters) > 0): ?>
                <div class="active-filters-display mt-3" aria-label="Active Filters">
                    <div class="active-filters-header">
                        <small><strong>Active Filters:</strong></small>
                    </div>
                    <div class="active-filters-tags">
                        <?php foreach ($activeFilters as $key => $value): ?>
                            <span class="filter-tag">
                                <?php echo htmlspecialchars(ucwords(str_replace('_', ' ', $key))); ?>: 
                                <strong><?php echo htmlspecialchars($value); ?></strong>
                                <a href="<?php echo $baseUrl; ?>/admin/nominal-roll?<?php echo http_build_query(array_merge($filters, [$key => ''])); ?>" 
                                   class="remove-filter" title="Remove this filter" aria-label="Remove filter <?php echo htmlspecialchars($key); ?>">
                                    <i class="fas fa-times" aria-hidden="true"></i>
                                </a>
                            </span>
                        <?php endforeach; ?>
                    </div>
                </div>
                <?php endif; ?>
            </div>
        </form>
    </section>
    
    <!-- Employees Table -->
    <section class="data-table-card" aria-label="Employees Table">
        <?php if (empty($employees)): ?>
        <div class="empty-state" role="alert">
            <i class="fas fa-users-slash" aria-hidden="true"></i>
            <h3>No employees found</h3>
            <p>
                <?php if (!empty($filters['search']) || count($activeFilters) > 0): ?>
                Try adjusting your search or filters
                <?php else: ?>
                No employees in the database yet
                <?php endif; ?>
            </p>
            <div class="empty-state-actions">
                <?php if ($isEditor && ($editingEnabled || $isSuperAdmin)): ?>
                <a href="<?php echo $baseUrl; ?>/admin/nominal-roll/create" class="btn btn-primary">
                    <i class="fas fa-plus" aria-hidden="true"></i> Add First Employee
                </a>
                <?php endif; ?>
                <?php if (!empty($filters['search']) || count($activeFilters) > 0): ?>
                <a href="<?php echo $baseUrl; ?>/admin/nominal-roll" class="btn btn-outline">
                    <i class="fas fa-times" aria-hidden="true"></i> Clear All Filters
                </a>
                <?php endif; ?>
            </div>
        </div>
        <?php else: ?>
        
        <div class="table-header">
            <div class="table-summary">
                Showing <?php echo (($pagination['page'] - 1) * $pagination['limit']) + 1; ?> to 
                <?php echo min($pagination['page'] * $pagination['limit'], $pagination['total']); ?> of 
                <?php echo number_format($pagination['total']); ?> employees
            </div>
            <div class="table-actions">
                <div class="btn-group" role="group" aria-label="Table Actions">
                    <button type="button" class="btn btn-sm btn-outline dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fas fa-columns" aria-hidden="true"></i> Columns
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end" aria-label="Column Visibility">
                        <li><h6 class="dropdown-header">Show/Hide Columns</h6></li>
                        <?php 
                        $visibleColumns = ['employee_number', 'name', 'sex', 'rank', 'grade_level', 'state', 'date_of_first_appointment'];
                        $columnLabels = [
                            'employee_number' => 'Employee No.',
                            'name' => 'Name',
                            'sex' => 'Sex',
                            'rank' => 'Rank',
                            'grade_level' => 'Grade Level',
                            'state' => 'State',
                            'date_of_first_appointment' => 'Date of 1st Appt.'
                        ];
                        foreach ($visibleColumns as $col): ?>
                        <li class="dropdown-item">
                            <div class="form-check">
                                <input class="form-check-input column-toggle" 
                                       type="checkbox" 
                                       id="toggle_<?php echo $col; ?>" 
                                       data-column="<?php echo $col; ?>" 
                                       checked aria-label="Toggle <?php echo $columnLabels[$col] ?? ucwords(str_replace('_', ' ', $col)); ?>">
                                <label class="form-check-label" for="toggle_<?php echo $col; ?>">
                                    <?php echo $columnLabels[$col] ?? ucwords(str_replace('_', ' ', $col)); ?>
                                </label>
                            </div>
                        </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
                <a href="<?php echo $baseUrl; ?>/admin/nominal-roll/reports?preset=current" 
                   class="btn btn-sm btn-info" title="Generate report from current view" aria-label="Report This View">
                    <i class="fas fa-chart-bar" aria-hidden="true"></i> Report This View
                </a>
            </div>
        </div>
        
        <div class="table-responsive">
            <table class="data-table" id="employeesTable" aria-label="Employees List">
                <thead>
                    <tr>
                        <th class="serial-column">S/N</th>
                        <th class="employee-number">Employee No.</th>
                        <th class="name-column">Name</th>
                        <th class="sex-column">Sex</th>
                        <th class="rank-column">Rank</th>
                        <th class="grade-column">Grade Level</th>
                        <th class="state-column">State</th>
                        <th class="date-column">Date of 1st Appt.</th>
                        <th class="actions-column">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($employees as $index => $employee): ?>
                    <tr tabindex="0" aria-label="Employee row for <?php echo htmlspecialchars($employee['surname'] . ', ' . $employee['first_name']); ?>">
                        <td class="serial-column"><?php echo (($pagination['page'] - 1) * $pagination['limit']) + $index + 1; ?></td>
                        <td class="employee-number">
                            <strong><?php echo htmlspecialchars($employee['employee_number']); ?></strong>
                            <?php if (!empty($employee['is_draft'])): ?>
                            <span class="badge badge-warning badge-sm">Draft</span>
                            <?php endif; ?>
                        </td>
                        <td class="name-column">
                            <div class="employee-name">
                                <strong><?php echo htmlspecialchars($employee['surname'] . ', ' . $employee['first_name']); ?></strong>
                                <?php if (!empty($employee['middle_name'])): ?>
                                <div class="text-muted small">
                                    <?php echo htmlspecialchars($employee['middle_name']); ?>
                                </div>
                                <?php endif; ?>
                                <?php if (!empty($employee['department'])): ?>
                                <div class="text-muted extra-info">
                                    <i class="fas fa-building" aria-hidden="true"></i> <?php echo htmlspecialchars($employee['department']); ?>
                                </div>
                                <?php endif; ?>
                            </div>
                        </td>
                        <td class="sex-column">
                            <span class="badge <?php echo $employee['sex'] === 'Male' ? 'badge-info' : 'badge-pink'; ?>">
                                <?php echo htmlspecialchars($employee['sex']); ?>
                            </span>
                        </td>
                        <td class="rank-column">
                            <span class="rank-badge"><?php echo htmlspecialchars($employee['rank']); ?></span>
                        </td>
                        <td class="grade-column">
                            <span class="badge badge-secondary">GL <?php echo htmlspecialchars($employee['grade_level']); ?></span>
                            <?php if (!empty($employee['step'])): ?>
                            <span class="badge badge-light">Step <?php echo htmlspecialchars($employee['step']); ?></span>
                            <?php endif; ?>
                        </td>
                        <td class="state-column">
                            <div class="state-info">
                                <span class="state-name"><?php echo htmlspecialchars($employee['state']); ?></span>
                                <?php if (!empty($employee['local_govt_area'])): ?>
                                <div class="text-muted small">
                                    <?php echo htmlspecialchars($employee['local_govt_area']); ?>
                                </div>
                                <?php endif; ?>
                            </div>
                        </td>
                        <td class="date-column">
                            <?php if (!empty($employee['date_of_first_appointment'])): ?>
                            <div class="date-display">
                                <?php echo date('M d, Y', strtotime($employee['date_of_first_appointment'])); ?>
                                <?php 
                                $yearsOfService = date('Y') - date('Y', strtotime($employee['date_of_first_appointment']));
                                if ($yearsOfService > 0): 
                                ?>
                                <div class="text-muted small">
                                    <i class="fas fa-calendar-alt" aria-hidden="true"></i> <?php echo $yearsOfService; ?> yrs
                                </div>
                                <?php endif; ?>
                            </div>
                            <?php else: ?>
                            <span class="text-muted">-</span>
                            <?php endif; ?>
                        </td>
                        <td class="actions-column">
                            <div class="action-buttons" role="group" aria-label="Actions for employee <?php echo htmlspecialchars($employee['employee_number']); ?>">
                                <a href="<?php echo $baseUrl; ?>/admin/nominal-roll/view/<?php echo $employee['id']; ?>" 
                                   class="btn btn-sm btn-info" title="View Details" aria-label="View Details">
                                    <i class="fas fa-eye" aria-hidden="true"></i>
                                    <span class="btn-text">View</span>
                                </a>
                                
                                <?php if ($isEditor && ($editingEnabled || $isSuperAdmin)): ?>
                                <a href="<?php echo $baseUrl; ?>/admin/nominal-roll/edit/<?php echo $employee['id']; ?>" 
                                   class="btn btn-sm btn-warning" title="Edit Employee" aria-label="Edit Employee">
                                    <i class="fas fa-edit" aria-hidden="true"></i>
                                    <span class="btn-text">Edit</span>
                                </a>
                                <?php endif; ?>
                                
                                <a href="<?php echo $baseUrl; ?>/admin/nominal-roll/export?format=pdf&id=<?php echo $employee['id']; ?>" 
                                   class="btn btn-sm btn-danger" title="Export as PDF" target="_blank" aria-label="Export as PDF">
                                    <i class="fas fa-file-pdf" aria-hidden="true"></i>
                                    <span class="btn-text">PDF</span>
                                </a>
                                
                                <a href="<?php echo $baseUrl; ?>/admin/nominal-roll/reports?quick=<?php echo $employee['id']; ?>" 
                                   class="btn btn-sm btn-success" title="Quick Report" aria-label="Quick Report">
                                    <i class="fas fa-chart-bar" aria-hidden="true"></i>
                                    <span class="btn-text">Report</span>
                                </a>
                                
                                <?php if ($isSuperAdmin): ?>
                                <form method="POST" 
                                      action="<?php echo $baseUrl; ?>/admin/nominal-roll/delete/<?php echo $employee['id']; ?>" 
                                      class="d-inline delete-form"
                                      onsubmit="return confirmDelete(this);">
                                    <input type="hidden" name="_csrf_token" value="<?php echo $csrf_token; ?>">
                                    <button type="submit" class="btn btn-sm btn-dark" title="Delete Employee" aria-label="Delete Employee">
                                        <i class="fas fa-trash" aria-hidden="true"></i>
                                        <span class="btn-text">Delete</span>
                                    </button>
                                </form>
                                <?php endif; ?>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        <?php if ($pagination['total_pages'] > 1): ?>
        <nav class="pagination" aria-label="Pagination">
            <div class="pagination-info">
                Page <?php echo $pagination['page']; ?> of <?php echo $pagination['total_pages']; ?>
                (<?php echo number_format($pagination['total']); ?> total records)
            </div>
            
            <div class="pagination-controls">
                <?php if ($pagination['page'] > 1): ?>
                <a href="<?php echo $baseUrl; ?>/admin/nominal-roll?<?php echo http_build_query(array_merge($filters, ['page' => 1])); ?>" 
                   class="btn btn-sm btn-outline" title="First Page" aria-label="First Page">
                    <i class="fas fa-angle-double-left" aria-hidden="true"></i>
                </a>
                <?php endif; ?>
                
                <?php if ($pagination['page'] > 1): ?>
                <a href="<?php echo $baseUrl; ?>/admin/nominal-roll?<?php echo http_build_query(array_merge($filters, ['page' => $pagination['page'] - 1])); ?>" 
                   class="btn btn-sm btn-outline" title="Previous Page" aria-label="Previous Page">
                    <i class="fas fa-angle-left" aria-hidden="true"></i>
                </a>
                <?php endif; ?>
                
                <?php 
                $startPage = max(1, $pagination['page'] - 2);
                $endPage = min($pagination['total_pages'], $pagination['page'] + 2);
                
                for ($i = $startPage; $i <= $endPage; $i++):
                ?>
                <a href="<?php echo $baseUrl; ?>/admin/nominal-roll?<?php echo http_build_query(array_merge($filters, ['page' => $i])); ?>" 
                   class="btn btn-sm <?php echo $i === $pagination['page'] ? 'btn-primary' : 'btn-outline'; ?>"
                   title="Page <?php echo $i; ?>" aria-label="Page <?php echo $i; ?>" <?php echo $i === $pagination['page'] ? 'aria-current="page"' : ''; ?>>
                    <?php echo $i; ?>
                </a>
                <?php endfor; ?>
                
                <?php if ($pagination['page'] < $pagination['total_pages']): ?>
                <a href="<?php echo $baseUrl; ?>/admin/nominal-roll?<?php echo http_build_query(array_merge($filters, ['page' => $pagination['page'] + 1])); ?>" 
                   class="btn btn-sm btn-outline" title="Next Page" aria-label="Next Page">
                    <i class="fas fa-angle-right" aria-hidden="true"></i>
                </a>
                <?php endif; ?>
                
                <?php if ($pagination['page'] < $pagination['total_pages']): ?>
                <a href="<?php echo $baseUrl; ?>/admin/nominal-roll?<?php echo http_build_query(array_merge($filters, ['page' => $pagination['total_pages']])); ?>" 
                   class="btn btn-sm btn-outline" title="Last Page" aria-label="Last Page">
                    <i class="fas fa-angle-double-right" aria-hidden="true"></i>
                </a>
                <?php endif; ?>
            </div>
            
            <div class="pagination-jump">
                <div class="input-group input-group-sm">
                    <input type="number" 
                           id="jumpToPage" 
                           min="1" 
                           max="<?php echo $pagination['total_pages']; ?>" 
                           class="form-control" 
                           placeholder="Page" 
                           style="width: 70px;"
                           aria-label="Jump to page number">
                    <button class="btn btn-outline" onclick="jumpToPage()" aria-label="Go to page">
                        <i class="fas fa-arrow-right" aria-hidden="true"></i>
                    </button>
                </div>
            </div>
        </nav>
        <?php endif; ?>
        <?php endif; ?>
    </section>
</div>

<!-- JavaScript for Filters, Search, and Actions (minified and optimized) -->
<script defer>
document.addEventListener("DOMContentLoaded",function(){const e=document.getElementById("toggleFilters"),t=document.getElementById("advancedFilters");if(e&&t){const n=new URLSearchParams(window.location.search),r=n.has("state")||n.has("grade_level")||n.has("rank")||n.has("sex")||n.has("department")||n.has("status");if(r){t.style.display="block",e.innerHTML='<i class="fas fa-filter"></i> Hide Filters'}e.addEventListener("click",function(){t.style.display==="none"?(t.style.display="block",this.innerHTML='<i class="fas fa-filter"></i> Hide Filters',localStorage.setItem("nominalFiltersVisible","true")):(t.style.display="none",this.innerHTML='<i class="fas fa-filter"></i> Filters',localStorage.setItem("nominalFiltersVisible","false"))});const i=localStorage.getItem("nominalFiltersVisible");i==="true"&&(t.style.display="block",e.innerHTML='<i class="fas fa-filter"></i> Hide Filters')}function a(){const e=document.getElementById("activeFiltersCount");if(e){const t=document.querySelector(".search-form"),n=new FormData(t);let r=0;for(let[i,a]of n.entries())i!=="search"&&i!=="page"&&i!=="filtered"&&a.trim()!==""&&r++;r>0?(e.textContent=r,e.style.display="inline-block",e.classList.add("badge-active")):(e.style.display="none",e.classList.remove("badge-active"))}}document.querySelectorAll(".advanced-filters select, #searchInput").forEach(e=>{e.addEventListener("change",a),e.addEventListener("input",function(){this.id==="searchInput"&&a()})}),a();const s=document.getElementById("searchInput");let l,o="";if(s){const e=document.getElementById("searchForm"),t=e.querySelector('button[type="submit"]');s.addEventListener("input",function(){clearTimeout(l),this.value!==o&&(this.value.length>=3&&(l=setTimeout(()=>{t.innerHTML='<i class="fas fa-spinner fa-spin"></i> Searching...',t.disabled=!0,e.submit()},800)),o=this.value)}),s.addEventListener("keydown",function(e){e.key==="Enter"&&this.value.length>=1&&(e.preventDefault(),t.innerHTML='<i class="fas fa-spinner fa-spin"></i> Searching...',t.disabled=!0,e.submit())}),t.innerHTML='<i class="fas fa-search"></i> Search',t.disabled=!1}function d(){const e=window.innerWidth<=768,t=document.querySelectorAll(".btn-text");t.forEach(t=>{e?t.style.display="none":t.style.display="inline"})}d(),window.addEventListener("resize",d),window.confirmDelete=function(e){const t=e.closest("tr"),n=t.querySelector(".employee-name strong").textContent,r=t.querySelector(".employee-number strong").textContent;return Swal.fire({title:"Delete Employee?",html:`<div class="text-left"><p><strong>Are you sure you want to delete this employee?</strong></p><div class="alert alert-warning p-2 mb-3"><i class="fas fa-exclamation-triangle me-2"></i>This action cannot be undone!</div><div class="employee-details"><p><strong>Name:</strong> ${n}</p><p><strong>Employee No:</strong> ${r}</p></div></div>`,icon:"warning",showCancelButton:!0,confirmButtonText:"Yes, Delete",cancelButtonText:"Cancel",confirmButtonColor:"#d33",cancelButtonColor:"#3085d6"}).then(t=>{if(t.isConfirmed)return Swal.fire({title:"Deleting...",text:"Please wait",allowOutsideClick:!1,didOpen:()=>{Swal.showLoading()}}),e.submit(),!0;!1})},document.querySelectorAll('.btn-danger[href*="export"]').forEach(e=>{e.addEventListener("click",function(e){e.preventDefault();const t=this.href;Swal.fire({title:"Generate PDF",text:"This will generate a PDF file for this employee. Continue?",icon:"info",showCancelButton:!0,confirmButtonText:"Generate PDF",cancelButtonText:"Cancel"}).then(e=>{e.isConfirmed&&window.open(t,"_blank")})})}),window.innerWidth<=768&&t&&document.querySelector(".filter-actions .btn-primary").addEventListener("click",function(){setTimeout(()=>{t.style.display="none",e&&(e.innerHTML='<i class="fas fa-filter"></i> Filters',localStorage.setItem("nominalFiltersVisible","false"))},100)}),document.querySelectorAll(".column-toggle").forEach(e=>{e.addEventListener("change",function(){const e=this.getAttribute("data-column"),t="."+e+"-column",n=this.checked;document.querySelectorAll(t).forEach(e=>{n?e.style.display="":e.style.display="none"});const r=JSON.parse(localStorage.getItem("nominalColumnPrefs")||"{}");r[e]=n,localStorage.setItem("nominalColumnPrefs",JSON.stringify(r))});const t=JSON.parse(localStorage.getItem("nominalColumnPrefs")||"{}"),n=e.getAttribute("data-column");t[n]===!1&&(e.checked=!1,e.dispatchEvent(new Event("change")))}),document.getElementById("saveFilterSet")?.addEventListener("click",function(){const e={};document.querySelectorAll(".filter-select").forEach(t=>{t.value&&(e[t.name]=t.value)});const t=document.getElementById("searchInput").value;t&&(e.search=t),Object.keys(e).length===0?Swal.fire({icon:"warning",title:"No Filters",text:"Please apply some filters before saving."}):Swal.fire({title:"Save Filter Set",input:"text",inputLabel:"Filter Set Name",inputPlaceholder:'e.g., "Active Lagos Staff"',showCancelButton:!0,confirmButtonText:"Save",cancelButtonText:"Cancel"}).then(t=>{if(t.isConfirmed&&t.value){const n=JSON.parse(localStorage.getItem("nominalFilterSets")||"[]");n.push({name:t.value,filters:e,date:(new Date).toISOString()}),localStorage.setItem("nominalFilterSets",JSON.stringify(n)),Swal.fire({icon:"success",title:"Saved!",text:"Filter set saved successfully."})}})}),window.jumpToPage=function(){const e=document.getElementById("jumpToPage"),t=parseInt(e.value),n=<?php echo $pagination['total_pages'] ?? 1; ?>;(!t||t<1||t>n)?Swal.fire({icon:"error",title:"Invalid Page",text:`Please enter a page number between 1 and ${n}`}):(new URL(window.location.href).searchParams.set("page",t),window.location.href=currentUrl.toString())},document.querySelectorAll(".filter-tag .remove-filter").forEach(e=>{e.addEventListener("click",function(e){e.preventDefault(),window.location.href=this.href})}),document.querySelector(".btn-clear-filters")?.addEventListener("click",function(e){e.preventDefault();const t=document.querySelectorAll(".filter-select").length>0,n=document.getElementById("searchInput").value;!t&&!n?window.location.href=this.href:Swal.fire({title:"Clear All Filters?",text:"This will remove all applied filters and search terms.",icon:"question",showCancelButton:!0,confirmButtonText:"Yes, Clear All",cancelButtonText:"Cancel"}).then(e=>{e.isConfirmed&& (window.location.href=this.href)})}),s&&(JSON.parse(localStorage.getItem("nominalRecentSearches")||"[]"),s.addEventListener("focus",function(){recentSearches.length>0&&!this.value&&console.log("Recent searches:",recentSearches)}),s.addEventListener("keyup",function(e){if(e.key==="Enter"&&this.value.trim()){const e=this.value.trim(),t=recentSearches.indexOf(e);t>-1&&recentSearches.splice(t,1),recentSearches.unshift(e),recentSearches.length>10&&recentSearches.pop(),localStorage.setItem("nominalRecentSearches",JSON.stringify(recentSearches))}})),document.querySelectorAll(".data-table tbody tr").forEach(e=>{e.addEventListener("click",function(e){if(e.target.closest("a")||e.target.closest("button")||e.target.closest("form"))return;const t=this.querySelector(".btn-info");t&&(window.location.href=t.href)}),e.addEventListener("mouseenter",function(){this.style.cursor="pointer"})})});
</script>
</body>
</html>