<?php
/**
 * Nominal Roll Index View
 * Main listing page with search, filters, and data table
 */
?>
<div class="nominal-roll-container">
    <!-- Header with Stats and Actions -->
    <div class="page-header">
        <div class="header-content">
            <div class="header-title">
                <h1>Nominal Roll Management</h1>
                <p class="subtitle">Manage employee records and details</p>
            </div>
            
            <div class="header-actions">
                <?php if ($isEditor && ($editingEnabled || $isSuperAdmin)): ?>
                <a href="<?php echo $baseUrl; ?>/admin/nominal-roll/create" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Add Employee
                </a>
                <?php endif; ?>
                
                <?php if ($isEditor): ?>
                <a href="<?php echo $baseUrl; ?>/admin/nominal-roll/bulk-upload" class="btn btn-secondary">
                    <i class="fas fa-upload"></i> Bulk Upload
                </a>
                <?php endif; ?>
                
                <!-- REPORTS BUTTON ADDED HERE -->
                <a href="<?php echo $baseUrl; ?>/admin/nominal-roll/reports" class="btn btn-info">
                    <i class="fas fa-chart-bar"></i> Reports
                </a>
                
                <a href="<?php echo $baseUrl; ?>/admin/nominal-roll/export?<?php echo http_build_query($filters); ?>" 
                   class="btn btn-success" target="_blank">
                    <i class="fas fa-download"></i> Export CSV
                </a>
                
                <?php if ($isSuperAdmin): ?>
                <a href="<?php echo $baseUrl; ?>/admin/nominal-roll/settings" class="btn btn-outline">
                    <i class="fas fa-cog"></i> Settings
                </a>
                <?php endif; ?>
            </div>
        </div>
        
        <!-- Editing Status Alert -->
        <?php if (!$editingEnabled && !$isSuperAdmin): ?>
        <div class="alert alert-warning alert-dismissible">
            <i class="fas fa-lock"></i>
            <strong>Editing is disabled.</strong> Only Super Admin can modify records.
        </div>
        <?php endif; ?>
    </div>
    
    <!-- Statistics Cards -->
    <div class="stats-cards">
        <div class="stat-card">
            <div class="stat-icon bg-primary">
                <i class="fas fa-users"></i>
            </div>
            <div class="stat-content">
                <h3><?php echo number_format($stats['total_employees'] ?? ($stats['total'] ?? 0)); ?></h3>
                <p>Total Employees</p>
            </div>
        </div>
        
        <div class="stat-card">
            <div class="stat-icon bg-success">
                <i class="fas fa-venus"></i>
            </div>
            <div class="stat-content">
                <h3>
                    <?php 
                    $femaleCount = 0;
                    if (isset($stats['by_sex']) && is_array($stats['by_sex'])) {
                        foreach ($stats['by_sex'] as $sex) {
                            if (isset($sex['sex']) && $sex['sex'] === 'Female') {
                                $femaleCount = $sex['count'] ?? 0;
                                break;
                            }
                        }
                    } elseif (isset($stats['female_count'])) {
                        $femaleCount = $stats['female_count'];
                    }
                    echo number_format($femaleCount);
                    ?>
                </h3>
                <p>Female Employees</p>
            </div>
        </div>
        
        <div class="stat-card">
            <div class="stat-icon bg-info">
                <i class="fas fa-mars"></i>
            </div>
            <div class="stat-content">
                <h3>
                    <?php 
                    $maleCount = 0;
                    if (isset($stats['by_sex']) && is_array($stats['by_sex'])) {
                        foreach ($stats['by_sex'] as $sex) {
                            if (isset($sex['sex']) && $sex['sex'] === 'Male') {
                                $maleCount = $sex['count'] ?? 0;
                                break;
                            }
                        }
                    } elseif (isset($stats['male_count'])) {
                        $maleCount = $stats['male_count'];
                    }
                    echo number_format($maleCount);
                    ?>
                </h3>
                <p>Male Employees</p>
            </div>
        </div>
        
        <div class="stat-card">
            <div class="stat-icon bg-warning">
                <i class="fas fa-sync-alt"></i>
            </div>
            <div class="stat-content">
                <h3><?php echo number_format($stats['recent_updates'] ?? ($stats['updated_count'] ?? 0)); ?></h3>
                <p>Updated (7 days)</p>
            </div>
        </div>
    </div>
    
    <!-- Search and Filters -->
    <div class="search-filters-card">
        <form method="GET" action="<?php echo $baseUrl; ?>/admin/nominal-roll" class="search-form" id="searchForm">
            <!-- Hidden input to track if filters were submitted -->
            <input type="hidden" name="filtered" value="1">
            
            <div class="search-row">
                <!-- Search Input -->
                <div class="search-input-group">
                    <div class="input-with-icon">
                        <i class="fas fa-search"></i>
                        <input type="text" 
                               name="search" 
                               id="searchInput"
                               placeholder="Search by name, employee number, state..." 
                               value="<?php echo htmlspecialchars($filters['search'] ?? ''); ?>"
                               class="form-control">
                    </div>
                    <button type="submit" class="btn btn-primary btn-search">
                        <i class="fas fa-search"></i> Search
                    </button>
                    <?php if (!empty($filters['search'])): ?>
                    <a href="<?php echo $baseUrl; ?>/admin/nominal-roll?<?php echo http_build_query(array_diff_key($filters, ['search' => ''])); ?>" 
                       class="btn btn-outline btn-clear-search" title="Clear search">
                        <i class="fas fa-times"></i>
                    </a>
                    <?php endif; ?>
                </div>
                
                <!-- Advanced Filters Toggle -->
                <button type="button" class="btn btn-outline" id="toggleFilters">
                    <i class="fas fa-filter"></i> Filters
                    <span class="badge" id="activeFiltersCount">
                        <?php 
                        $activeFilters = array_filter($filters, function($value, $key) {
                            return !empty($value) && $key !== 'search' && $key !== 'page' && $key !== 'filtered';
                        }, ARRAY_FILTER_USE_BOTH);
                        $filterCount = count($activeFilters);
                        ?>
                        <?php if ($filterCount > 0): ?>
                        <?php echo $filterCount; ?>
                        <?php endif; ?>
                    </span>
                </button>
                
                <!-- Quick Actions -->
                <div class="quick-actions">
                    <a href="<?php echo $baseUrl; ?>/admin/nominal-roll/reports" class="btn btn-sm btn-outline-info" title="Generate Reports">
                        <i class="fas fa-chart-bar"></i> Quick Report
                    </a>
                </div>
            </div>
            
            <!-- Advanced Filters (Hidden by default) -->
            <div class="advanced-filters" id="advancedFilters" style="display: <?php echo !empty($filters['filtered']) ? 'block' : 'none'; ?>;">
                <div class="filter-grid">
                    <!-- State Filter -->
                    <div class="form-group">
                        <label>State</label>
                        <select name="state" class="form-control filter-select">
                            <option value="">All States</option>
                            <?php foreach ($filterOptions['states'] as $state): ?>
                            <option value="<?php echo htmlspecialchars($state); ?>" 
                                <?php echo ($filters['state'] ?? '') === $state ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($state); ?>
                            </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <!-- Grade Level Filter -->
                    <div class="form-group">
                        <label>Grade Level</label>
                        <select name="grade_level" class="form-control filter-select">
                            <option value="">All Grade Levels</option>
                            <?php foreach ($filterOptions['grade_levels'] as $grade): ?>
                            <option value="<?php echo htmlspecialchars($grade); ?>" 
                                <?php echo ($filters['grade_level'] ?? '') === $grade ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($grade); ?>
                            </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <!-- Rank Filter -->
                    <div class="form-group">
                        <label>Rank</label>
                        <select name="rank" class="form-control filter-select">
                            <option value="">All Ranks</option>
                            <?php foreach ($filterOptions['ranks'] as $rank): ?>
                            <option value="<?php echo htmlspecialchars($rank); ?>" 
                                <?php echo ($filters['rank'] ?? '') === $rank ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($rank); ?>
                            </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <!-- Sex Filter -->
                    <div class="form-group">
                        <label>Sex</label>
                        <select name="sex" class="form-control filter-select">
                            <option value="">All</option>
                            <option value="Male" <?php echo ($filters['sex'] ?? '') === 'Male' ? 'selected' : ''; ?>>Male</option>
                            <option value="Female" <?php echo ($filters['sex'] ?? '') === 'Female' ? 'selected' : ''; ?>>Female</option>
                        </select>
                    </div>
                    
                    <!-- Department Filter -->
                    <div class="form-group">
                        <label>Department</label>
                        <select name="department" class="form-control filter-select">
                            <option value="">All Departments</option>
                            <?php foreach ($filterOptions['departments'] ?? [] as $dept): ?>
                            <option value="<?php echo htmlspecialchars($dept); ?>" 
                                <?php echo ($filters['department'] ?? '') === $dept ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($dept); ?>
                            </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <!-- Status Filter -->
                    <div class="form-group">
                        <label>Status</label>
                        <select name="status" class="form-control filter-select">
                            <option value="">All Status</option>
                            <option value="active" <?php echo ($filters['status'] ?? '') === 'active' ? 'selected' : ''; ?>>Active</option>
                            <option value="inactive" <?php echo ($filters['status'] ?? '') === 'inactive' ? 'selected' : ''; ?>>Inactive</option>
                            <option value="retired" <?php echo ($filters['status'] ?? '') === 'retired' ? 'selected' : ''; ?>>Retired</option>
                        </select>
                    </div>
                </div>
                
                <div class="filter-actions">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-check"></i> Apply Filters
                    </button>
                    <a href="<?php echo $baseUrl; ?>/admin/nominal-roll" class="btn btn-outline btn-clear-filters">
                        <i class="fas fa-times"></i> Clear All
                    </a>
                    <button type="button" class="btn btn-outline" id="saveFilterSet" title="Save this filter set">
                        <i class="fas fa-save"></i> Save Filters
                    </button>
                </div>
                
                <!-- Active Filters Display -->
                <?php if ($filterCount > 0): ?>
                <div class="active-filters-display mt-3">
                    <div class="active-filters-header">
                        <small><strong>Active Filters:</strong></small>
                    </div>
                    <div class="active-filters-tags">
                        <?php foreach ($activeFilters as $key => $value): ?>
                            <?php if (!empty($value)): ?>
                                <?php 
                                $filterLabel = str_replace('_', ' ', $key);
                                $filterLabel = ucwords($filterLabel);
                                ?>
                                <span class="filter-tag">
                                    <?php echo htmlspecialchars($filterLabel); ?>: 
                                    <strong><?php echo htmlspecialchars($value); ?></strong>
                                    <a href="<?php echo $baseUrl; ?>/admin/nominal-roll?<?php echo http_build_query(array_merge($filters, [$key => ''])); ?>" 
                                       class="remove-filter" title="Remove this filter">
                                        <i class="fas fa-times"></i>
                                    </a>
                                </span>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </div>
                </div>
                <?php endif; ?>
            </div>
        </form>
    </div>
    
    <!-- Employees Table -->
    <div class="data-table-card">
        <?php if (empty($employees)): ?>
        <div class="empty-state">
            <i class="fas fa-users-slash"></i>
            <h3>No employees found</h3>
            <p>
                <?php if (!empty($filters['search']) || $filterCount > 0): ?>
                Try adjusting your search or filters
                <?php else: ?>
                No employees in the database yet
                <?php endif; ?>
            </p>
            <div class="empty-state-actions">
                <?php if ($isEditor && ($editingEnabled || $isSuperAdmin)): ?>
                <a href="<?php echo $baseUrl; ?>/admin/nominal-roll/create" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Add First Employee
                </a>
                <?php endif; ?>
                <?php if (!empty($filters['search']) || $filterCount > 0): ?>
                <a href="<?php echo $baseUrl; ?>/admin/nominal-roll" class="btn btn-outline">
                    <i class="fas fa-times"></i> Clear All Filters
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
                <div class="btn-group">
                    <button type="button" class="btn btn-sm btn-outline dropdown-toggle" data-bs-toggle="dropdown">
                        <i class="fas fa-columns"></i> Columns
                    </button>
                    <div class="dropdown-menu dropdown-menu-end">
                        <h6 class="dropdown-header">Show/Hide Columns</h6>
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
                        <div class="dropdown-item">
                            <div class="form-check">
                                <input class="form-check-input column-toggle" 
                                       type="checkbox" 
                                       id="toggle_<?php echo $col; ?>" 
                                       data-column="<?php echo $col; ?>" 
                                       checked>
                                <label class="form-check-label" for="toggle_<?php echo $col; ?>">
                                    <?php echo $columnLabels[$col] ?? ucwords(str_replace('_', ' ', $col)); ?>
                                </label>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                <a href="<?php echo $baseUrl; ?>/admin/nominal-roll/reports?preset=current" 
                   class="btn btn-sm btn-info" title="Generate report from current view">
                    <i class="fas fa-chart-bar"></i> Report This View
                </a>
            </div>
        </div>
        
        <div class="table-responsive">
            <table class="data-table" id="employeesTable">
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
                    <tr>
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
                                    <i class="fas fa-building"></i> <?php echo htmlspecialchars($employee['department']); ?>
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
                                    <i class="fas fa-calendar-alt"></i> <?php echo $yearsOfService; ?> yrs
                                </div>
                                <?php endif; ?>
                            </div>
                            <?php else: ?>
                            <span class="text-muted">-</span>
                            <?php endif; ?>
                        </td>
                        <td class="actions-column">
                            <div class="action-buttons">
                                <a href="<?php echo $baseUrl; ?>/admin/nominal-roll/view/<?php echo $employee['id']; ?>" 
                                   class="btn btn-sm btn-info" title="View Details">
                                    <i class="fas fa-eye"></i>
                                    <span class="btn-text">View</span>
                                </a>
                                
                                <?php if ($isEditor && ($editingEnabled || $isSuperAdmin)): ?>
                                <a href="<?php echo $baseUrl; ?>/admin/nominal-roll/edit/<?php echo $employee['id']; ?>" 
                                   class="btn btn-sm btn-warning" title="Edit Employee">
                                    <i class="fas fa-edit"></i>
                                    <span class="btn-text">Edit</span>
                                </a>
                                <?php endif; ?>
                                
                                <a href="<?php echo $baseUrl; ?>/admin/nominal-roll/export?format=pdf&id=<?php echo $employee['id']; ?>" 
                                   class="btn btn-sm btn-danger" title="Export as PDF" target="_blank">
                                    <i class="fas fa-file-pdf"></i>
                                    <span class="btn-text">PDF</span>
                                </a>
                                
                                <!-- Quick Report Button -->
                                <a href="<?php echo $baseUrl; ?>/admin/nominal-roll/reports?quick=<?php echo $employee['id']; ?>" 
                                   class="btn btn-sm btn-success" title="Quick Report">
                                    <i class="fas fa-chart-bar"></i>
                                    <span class="btn-text">Report</span>
                                </a>
                                
                                <?php if ($isSuperAdmin): ?>
                                <form method="POST" 
                                      action="<?php echo $baseUrl; ?>/admin/nominal-roll/delete/<?php echo $employee['id']; ?>" 
                                      class="d-inline delete-form"
                                      onsubmit="return confirmDelete(this);">
                                    <input type="hidden" name="_csrf_token" value="<?php echo $csrf_token; ?>">
                                    <button type="submit" class="btn btn-sm btn-dark" title="Delete Employee">
                                        <i class="fas fa-trash"></i>
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
        <div class="pagination">
            <div class="pagination-info">
                Page <?php echo $pagination['page']; ?> of <?php echo $pagination['total_pages']; ?>
                (<?php echo number_format($pagination['total']); ?> total records)
            </div>
            
            <div class="pagination-controls">
                <!-- First Page -->
                <?php if ($pagination['page'] > 1): ?>
                <a href="<?php echo $baseUrl; ?>/admin/nominal-roll?<?php echo http_build_query(array_merge($filters, ['page' => 1])); ?>" 
                   class="btn btn-sm btn-outline" title="First Page">
                    <i class="fas fa-angle-double-left"></i>
                </a>
                <?php endif; ?>
                
                <!-- Previous Page -->
                <?php if ($pagination['page'] > 1): ?>
                <a href="<?php echo $baseUrl; ?>/admin/nominal-roll?<?php echo http_build_query(array_merge($filters, ['page' => $pagination['page'] - 1])); ?>" 
                   class="btn btn-sm btn-outline" title="Previous Page">
                    <i class="fas fa-angle-left"></i>
                </a>
                <?php endif; ?>
                
                <!-- Page Numbers -->
                <?php 
                $startPage = max(1, $pagination['page'] - 2);
                $endPage = min($pagination['total_pages'], $pagination['page'] + 2);
                
                for ($i = $startPage; $i <= $endPage; $i++):
                ?>
                <a href="<?php echo $baseUrl; ?>/admin/nominal-roll?<?php echo http_build_query(array_merge($filters, ['page' => $i])); ?>" 
                   class="btn btn-sm <?php echo $i === $pagination['page'] ? 'btn-primary' : 'btn-outline'; ?>"
                   title="Page <?php echo $i; ?>">
                    <?php echo $i; ?>
                </a>
                <?php endfor; ?>
                
                <!-- Next Page -->
                <?php if ($pagination['page'] < $pagination['total_pages']): ?>
                <a href="<?php echo $baseUrl; ?>/admin/nominal-roll?<?php echo http_build_query(array_merge($filters, ['page' => $pagination['page'] + 1])); ?>" 
                   class="btn btn-sm btn-outline" title="Next Page">
                    <i class="fas fa-angle-right"></i>
                </a>
                <?php endif; ?>
                
                <!-- Last Page -->
                <?php if ($pagination['page'] < $pagination['total_pages']): ?>
                <a href="<?php echo $baseUrl; ?>/admin/nominal-roll?<?php echo http_build_query(array_merge($filters, ['page' => $pagination['total_pages']])); ?>" 
                   class="btn btn-sm btn-outline" title="Last Page">
                    <i class="fas fa-angle-double-right"></i>
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
                           style="width: 70px;">
                    <button class="btn btn-outline" onclick="jumpToPage()">
                        <i class="fas fa-arrow-right"></i>
                    </button>
                </div>
            </div>
        </div>
        <?php endif; ?>
        <?php endif; ?>
    </div>
</div>

<!-- JavaScript for Filters, Search, and Actions -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Toggle advanced filters
    const toggleFiltersBtn = document.getElementById('toggleFilters');
    const advancedFilters = document.getElementById('advancedFilters');
    
    if (toggleFiltersBtn && advancedFilters) {
        // Show filters if they were previously active
        const urlParams = new URLSearchParams(window.location.search);
        const hasFilters = urlParams.has('state') || urlParams.has('grade_level') || 
                          urlParams.has('rank') || urlParams.has('sex') || 
                          urlParams.has('department') || urlParams.has('status');
        
        if (hasFilters) {
            advancedFilters.style.display = 'block';
            toggleFiltersBtn.innerHTML = '<i class="fas fa-filter"></i> Hide Filters';
        }
        
        toggleFiltersBtn.addEventListener('click', function() {
            if (advancedFilters.style.display === 'none') {
                advancedFilters.style.display = 'block';
                this.innerHTML = '<i class="fas fa-filter"></i> Hide Filters';
                // Store in localStorage
                localStorage.setItem('nominalFiltersVisible', 'true');
            } else {
                advancedFilters.style.display = 'none';
                this.innerHTML = '<i class="fas fa-filter"></i> Filters';
                localStorage.setItem('nominalFiltersVisible', 'false');
            }
        });
    }
    
    // Restore filter visibility from localStorage
    const filtersVisible = localStorage.getItem('nominalFiltersVisible');
    if (filtersVisible === 'true' && advancedFilters && toggleFiltersBtn) {
        advancedFilters.style.display = 'block';
        toggleFiltersBtn.innerHTML = '<i class="fas fa-filter"></i> Hide Filters';
    }
    
    // Update active filters count
    function updateActiveFiltersCount() {
        const activeFiltersCount = document.getElementById('activeFiltersCount');
        if (activeFiltersCount) {
            const form = document.querySelector('.search-form');
            const formData = new FormData(form);
            let activeCount = 0;
            
            for (let [key, value] of formData.entries()) {
                if (key !== 'search' && key !== 'page' && key !== 'filtered' && value.trim() !== '') {
                    activeCount++;
                }
            }
            
            if (activeCount > 0) {
                activeFiltersCount.textContent = activeCount;
                activeFiltersCount.style.display = 'inline-block';
                activeFiltersCount.classList.add('badge-active');
            } else {
                activeFiltersCount.style.display = 'none';
                activeFiltersCount.classList.remove('badge-active');
            }
        }
    }
    
    // Update count on filter change
    document.querySelectorAll('.advanced-filters select, #searchInput').forEach(element => {
        element.addEventListener('change', updateActiveFiltersCount);
        element.addEventListener('input', function() {
            if (this.id === 'searchInput') {
                updateActiveFiltersCount();
            }
        });
    });
    
    // Initialize count
    updateActiveFiltersCount();
    
    // Enhanced search with debounce and suggestions
    const searchInput = document.getElementById('searchInput');
    let searchTimeout;
    let lastSearchValue = '';
    
    if (searchInput) {
        // Show loading indicator on search
        const searchForm = document.getElementById('searchForm');
        const originalSubmit = searchForm.querySelector('button[type="submit"]');
        
        searchInput.addEventListener('input', function() {
            clearTimeout(searchTimeout);
            
            // Only search if value changed
            if (this.value === lastSearchValue) return;
            
            // Show loading for longer searches
            if (this.value.length >= 3) {
                searchTimeout = setTimeout(() => {
                    // Add loading class to search button
                    originalSubmit.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Searching...';
                    originalSubmit.disabled = true;
                    
                    // Submit form
                    searchForm.submit();
                }, 800);
            }
            
            lastSearchValue = this.value;
        });
        
        // Prevent form submission on Enter for instant search
        searchInput.addEventListener('keydown', function(e) {
            if (e.key === 'Enter' && this.value.length >= 1) {
                e.preventDefault();
                originalSubmit.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Searching...';
                originalSubmit.disabled = true;
                searchForm.submit();
            }
        });
        
        // Restore button state on page load
        originalSubmit.innerHTML = '<i class="fas fa-search"></i> Search';
        originalSubmit.disabled = false;
    }
    
    // Show/hide button text on mobile
    function updateButtonTextForMobile() {
        const isMobile = window.innerWidth <= 768;
        const btnTexts = document.querySelectorAll('.btn-text');
        
        btnTexts.forEach(btnText => {
            if (isMobile) {
                btnText.style.display = 'none';
            } else {
                btnText.style.display = 'inline';
            }
        });
    }
    
    // Initial update
    updateButtonTextForMobile();
    
    // Update on resize
    window.addEventListener('resize', updateButtonTextForMobile);
    
    // Enhanced delete confirmation with employee details
    window.confirmDelete = function(form) {
        const row = form.closest('tr');
        const employeeName = row.querySelector('.employee-name strong').textContent;
        const employeeNumber = row.querySelector('.employee-number strong').textContent;
        
        return Swal.fire({
            title: 'Delete Employee?',
            html: `<div class="text-left">
                <p><strong>Are you sure you want to delete this employee?</strong></p>
                <div class="alert alert-warning p-2 mb-3">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    This action cannot be undone!
                </div>
                <div class="employee-details">
                    <p><strong>Name:</strong> ${employeeName}</p>
                    <p><strong>Employee No:</strong> ${employeeNumber}</p>
                </div>
            </div>`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, Delete',
            cancelButtonText: 'Cancel',
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6'
        }).then((result) => {
            if (result.isConfirmed) {
                // Show loading
                Swal.fire({
                    title: 'Deleting...',
                    text: 'Please wait',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });
                
                // Submit the form
                form.submit();
                return true;
            }
            return false;
        });
    };
    
    // Export confirmation
    document.querySelectorAll('.btn-danger[href*="export"]').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            const url = this.href;
            Swal.fire({
                title: 'Generate PDF',
                text: 'This will generate a PDF file for this employee. Continue?',
                icon: 'info',
                showCancelButton: true,
                confirmButtonText: 'Generate PDF',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Open in new tab
                    window.open(url, '_blank');
                }
            });
        });
    });
    
    // Auto-hide filters on mobile after applying
    if (window.innerWidth <= 768 && advancedFilters) {
        const applyBtn = document.querySelector('.filter-actions .btn-primary');
        if (applyBtn) {
            applyBtn.addEventListener('click', function() {
                setTimeout(() => {
                    advancedFilters.style.display = 'none';
                    if (toggleFiltersBtn) {
                        toggleFiltersBtn.innerHTML = '<i class="fas fa-filter"></i> Filters';
                        localStorage.setItem('nominalFiltersVisible', 'false');
                    }
                }, 100);
            });
        }
    }
    
    // Column toggle functionality
    document.querySelectorAll('.column-toggle').forEach(toggle => {
        toggle.addEventListener('change', function() {
            const column = this.getAttribute('data-column');
            const columnClass = '.' + column + '-column';
            const isChecked = this.checked;
            
            // Show/hide column in table
            document.querySelectorAll(columnClass).forEach(cell => {
                if (isChecked) {
                    cell.style.display = '';
                } else {
                    cell.style.display = 'none';
                }
            });
            
            // Save preference to localStorage
            const preferences = JSON.parse(localStorage.getItem('nominalColumnPrefs') || '{}');
            preferences[column] = isChecked;
            localStorage.setItem('nominalColumnPrefs', JSON.stringify(preferences));
        });
        
        // Load saved preferences
        const preferences = JSON.parse(localStorage.getItem('nominalColumnPrefs') || '{}');
        const column = toggle.getAttribute('data-column');
        if (preferences[column] === false) {
            toggle.checked = false;
            toggle.dispatchEvent(new Event('change'));
        }
    });
    
    // Save filter set
    document.getElementById('saveFilterSet')?.addEventListener('click', function() {
        const filters = {};
        document.querySelectorAll('.filter-select').forEach(select => {
            if (select.value) {
                filters[select.name] = select.value;
            }
        });
        
        const searchValue = document.getElementById('searchInput').value;
        if (searchValue) {
            filters.search = searchValue;
        }
        
        if (Object.keys(filters).length === 0) {
            Swal.fire({
                icon: 'warning',
                title: 'No Filters',
                text: 'Please apply some filters before saving.'
            });
            return;
        }
        
        Swal.fire({
            title: 'Save Filter Set',
            input: 'text',
            inputLabel: 'Filter Set Name',
            inputPlaceholder: 'e.g., "Active Lagos Staff"',
            showCancelButton: true,
            confirmButtonText: 'Save',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed && result.value) {
                const filterSets = JSON.parse(localStorage.getItem('nominalFilterSets') || '[]');
                filterSets.push({
                    name: result.value,
                    filters: filters,
                    date: new Date().toISOString()
                });
                localStorage.setItem('nominalFilterSets', JSON.stringify(filterSets));
                
                Swal.fire({
                    icon: 'success',
                    title: 'Saved!',
                    text: 'Filter set saved successfully.'
                });
            }
        });
    });
    
    // Jump to page functionality
    window.jumpToPage = function() {
        const pageInput = document.getElementById('jumpToPage');
        const page = parseInt(pageInput.value);
        const totalPages = <?php echo $pagination['total_pages'] ?? 1; ?>;
        
        if (!page || page < 1 || page > totalPages) {
            Swal.fire({
                icon: 'error',
                title: 'Invalid Page',
                text: `Please enter a page number between 1 and ${totalPages}`
            });
            return;
        }
        
        const currentUrl = new URL(window.location.href);
        currentUrl.searchParams.set('page', page);
        window.location.href = currentUrl.toString();
    };
    
    // Quick filter badges
    document.querySelectorAll('.filter-tag .remove-filter').forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            window.location.href = this.href;
        });
    });
    
    // Clear all filters with confirmation
    document.querySelector('.btn-clear-filters')?.addEventListener('click', function(e) {
        e.preventDefault();
        const hasFilters = document.querySelectorAll('.filter-select').length > 0;
        const hasSearch = document.getElementById('searchInput').value;
        
        if (!hasFilters && !hasSearch) {
            window.location.href = this.href;
            return;
        }
        
        Swal.fire({
            title: 'Clear All Filters?',
            text: 'This will remove all applied filters and search terms.',
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Yes, Clear All',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = this.href;
            }
        }.bind(this));
    });
    
    // Quick search suggestions (optional - can be enhanced with AJAX)
    if (searchInput) {
        // Load recent searches
        const recentSearches = JSON.parse(localStorage.getItem('nominalRecentSearches') || '[]');
        
        searchInput.addEventListener('focus', function() {
            if (recentSearches.length > 0 && !this.value) {
                // Show recent searches dropdown (simplified)
                console.log('Recent searches:', recentSearches);
            }
        });
        
        searchInput.addEventListener('keyup', function(e) {
            if (e.key === 'Enter' && this.value.trim()) {
                // Add to recent searches
                const searchTerm = this.value.trim();
                const index = recentSearches.indexOf(searchTerm);
                if (index > -1) {
                    recentSearches.splice(index, 1);
                }
                recentSearches.unshift(searchTerm);
                if (recentSearches.length > 10) {
                    recentSearches.pop();
                }
                localStorage.setItem('nominalRecentSearches', JSON.stringify(recentSearches));
            }
        });
    }
    
    // Table row click to view details
    document.querySelectorAll('.data-table tbody tr').forEach(row => {
        row.addEventListener('click', function(e) {
            // Don't trigger if clicking on action buttons or links
            if (e.target.closest('a') || e.target.closest('button') || e.target.closest('form')) {
                return;
            }
            
            const viewLink = this.querySelector('.btn-info');
            if (viewLink) {
                window.location.href = viewLink.href;
            }
        });
        
        // Add hover effect
        row.addEventListener('mouseenter', function() {
            this.style.cursor = 'pointer';
        });
    });
});
</script>

<!-- CSS Styles -->
<style>
.nominal-roll-container {
    padding: 20px;
}

.page-header {
    margin-bottom: 24px;
}

.header-content {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 16px;
    flex-wrap: wrap;
    gap: 16px;
}

.header-title h1 {
    font-size: 24px;
    font-weight: 600;
    color: #333;
    margin: 0 0 4px 0;
}

.header-title .subtitle {
    color: #666;
    font-size: 14px;
    margin: 0;
}

.header-actions {
    display: flex;
    gap: 10px;
    flex-wrap: wrap;
}

.header-actions .btn {
    padding: 10px 16px;
    font-size: 14px;
    height: 40px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    min-width: 120px;
}

.header-actions .btn-info {
    background: #17a2b8;
    color: white;
    border-color: #17a2b8;
}

.header-actions .btn-info:hover {
    background: #138496;
    border-color: #117a8b;
    transform: translateY(-2px);
    box-shadow: 0 4px 6px rgba(23, 162, 184, 0.2);
}

/* Stats Cards */
.stats-cards {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 16px;
    margin-bottom: 24px;
}

.stat-card {
    background: white;
    border-radius: 8px;
    padding: 16px;
    box-shadow: 0 1px 3px rgba(0,0,0,0.1);
    display: flex;
    align-items: center;
    gap: 16px;
    transition: transform 0.2s, box-shadow 0.2s;
}

.stat-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 6px rgba(0,0,0,0.1);
}

.stat-icon {
    width: 48px;
    height: 48px;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 20px;
    color: white;
    flex-shrink: 0;
}

.stat-icon.bg-primary { background: #3490dc; }
.stat-icon.bg-success { background: #38a169; }
.stat-icon.bg-info { background: #17a2b8; }
.stat-icon.bg-warning { background: #d69e2e; }

.stat-content h3 {
    font-size: 24px;
    font-weight: 700;
    margin: 0 0 4px 0;
    color: #333;
    line-height: 1;
}

.stat-content p {
    font-size: 12px;
    color: #666;
    margin: 0;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

/* Search and Filters */
.search-filters-card {
    background: white;
    border-radius: 8px;
    padding: 16px;
    box-shadow: 0 1px 3px rgba(0,0,0,0.1);
    margin-bottom: 24px;
}

.search-row {
    display: flex;
    gap: 12px;
    align-items: center;
    flex-wrap: wrap;
}

.search-input-group {
    flex: 1;
    display: flex;
    gap: 8px;
    min-width: 300px;
}

.input-with-icon {
    flex: 1;
    position: relative;
}

.input-with-icon i {
    position: absolute;
    left: 12px;
    top: 50%;
    transform: translateY(-50%);
    color: #999;
    z-index: 1;
}

.input-with-icon input {
    width: 100%;
    padding: 10px 12px 10px 36px;
    border: 1px solid #ddd;
    border-radius: 4px;
    font-size: 14px;
    height: 40px;
    box-sizing: border-box;
}

.input-with-icon input:focus {
    outline: none;
    border-color: #3490dc;
    box-shadow: 0 0 0 2px rgba(52, 144, 220, 0.1);
}

.btn-search {
    height: 40px;
    padding: 0 16px;
    min-width: 100px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.btn-clear-search {
    height: 40px;
    width: 40px;
    padding: 0;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 4px;
}

.quick-actions {
    display: flex;
    gap: 8px;
}

/* Advanced Filters */
.advanced-filters {
    margin-top: 16px;
    padding-top: 16px;
    border-top: 1px solid #eee;
}

.filter-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 16px;
    margin-bottom: 16px;
}

.filter-actions {
    display: flex;
    gap: 8px;
    justify-content: flex-start;
}

.filter-actions .btn {
    height: 40px;
    padding: 0 16px;
    min-width: 120px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.active-filters-display {
    background: #f8f9fa;
    border-radius: 6px;
    padding: 12px;
    margin-top: 16px;
}

.active-filters-header {
    margin-bottom: 8px;
}

.active-filters-tags {
    display: flex;
    flex-wrap: wrap;
    gap: 8px;
}

.filter-tag {
    background: white;
    border: 1px solid #dee2e6;
    border-radius: 20px;
    padding: 4px 12px;
    font-size: 13px;
    display: inline-flex;
    align-items: center;
    gap: 6px;
}

.filter-tag .remove-filter {
    color: #dc3545;
    text-decoration: none;
    font-size: 12px;
    cursor: pointer;
}

.filter-tag .remove-filter:hover {
    color: #bd2130;
}

/* Data Table */
.data-table-card {
    background: white;
    border-radius: 8px;
    box-shadow: 0 1px 3px rgba(0,0,0,0.1);
    overflow: hidden;
}

.table-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 16px;
    border-bottom: 1px solid #e2e8f0;
    background: #f8f9fa;
}

.table-summary {
    font-size: 14px;
    color: #666;
}

.table-actions {
    display: flex;
    gap: 8px;
    align-items: center;
}

.table-responsive {
    overflow-x: auto;
}

.data-table {
    width: 100%;
    border-collapse: collapse;
}

.data-table thead {
    background: #f7fafc;
}

.data-table th {
    padding: 12px 16px;
    text-align: left;
    font-weight: 600;
    color: #4a5568;
    font-size: 12px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    border-bottom: 2px solid #e2e8f0;
    white-space: nowrap;
}

.data-table td {
    padding: 12px 16px;
    border-bottom: 1px solid #e2e8f0;
    font-size: 14px;
    vertical-align: middle;
}

.data-table tbody tr {
    transition: background-color 0.2s;
}

.data-table tbody tr:hover {
    background: #f8fafc;
}

.employee-name {
    line-height: 1.4;
    min-width: 200px;
}

.employee-name strong {
    display: block;
    color: #333;
    font-weight: 600;
}

.employee-name .small {
    font-size: 12px;
    color: #666;
}

.employee-name .extra-info {
    font-size: 12px;
    color: #6c757d;
    margin-top: 2px;
}

.extra-info i {
    margin-right: 4px;
}

.state-info {
    line-height: 1.4;
}

.state-name {
    display: block;
    font-weight: 500;
}

.date-display {
    line-height: 1.4;
}

/* Badges */
.badge {
    display: inline-block;
    padding: 4px 8px;
    font-size: 12px;
    font-weight: 600;
    border-radius: 4px;
    white-space: nowrap;
}

.badge-sm {
    padding: 2px 6px;
    font-size: 11px;
}

.badge-info {
    background: #e6f7ff;
    color: #1890ff;
}

.badge-pink {
    background: #fff0f6;
    color: #eb2f96;
}

.badge-secondary {
    background: #f8f9fa;
    color: #6c757d;
}

.badge-warning {
    background: #fff7e6;
    color: #fa8c16;
}

.badge-light {
    background: #f8f9fa;
    color: #6c757d;
    border: 1px solid #dee2e6;
}

.badge-active {
    background: #dc3545;
    color: white;
}

.rank-badge {
    background: #e8f5e8;
    color: #28a745;
    padding: 4px 8px;
    border-radius: 4px;
    font-size: 13px;
    font-weight: 500;
}

/* Action Buttons */
.action-buttons {
    display: flex;
    gap: 8px;
    flex-wrap: wrap;
    min-width: 250px;
}

.action-buttons .btn {
    padding: 8px 12px !important;
    font-size: 13px !important;
    min-width: 80px;
    height: 36px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    border-radius: 4px;
    transition: all 0.2s;
    gap: 6px;
    text-decoration: none;
    border: none;
    cursor: pointer;
    white-space: nowrap;
}

.action-buttons .btn i {
    font-size: 12px;
    margin: 0;
}

.action-buttons .btn .btn-text {
    font-size: 12px;
    font-weight: 500;
}

.action-buttons .btn-info {
    background: #3490dc;
    color: white;
}

.action-buttons .btn-info:hover {
    background: #2779bd;
    transform: translateY(-2px);
    box-shadow: 0 2px 4px rgba(52, 144, 220, 0.2);
}

.action-buttons .btn-warning {
    background: #f6993f;
    color: white;
}

.action-buttons .btn-warning:hover {
    background: #e08629;
    transform: translateY(-2px);
    box-shadow: 0 2px 4px rgba(246, 153, 63, 0.2);
}

.action-buttons .btn-danger {
    background: #e3342f;
    color: white;
}

.action-buttons .btn-danger:hover {
    background: #c53030;
    transform: translateY(-2px);
    box-shadow: 0 2px 4px rgba(227, 52, 47, 0.2);
}

.action-buttons .btn-success {
    background: #38a169;
    color: white;
}

.action-buttons .btn-success:hover {
    background: #2f855a;
    transform: translateY(-2px);
    box-shadow: 0 2px 4px rgba(56, 161, 105, 0.2);
}

.action-buttons .btn-dark {
    background: #343a40;
    color: white;
}

.action-buttons .btn-dark:hover {
    background: #23272b;
    transform: translateY(-2px);
    box-shadow: 0 2px 4px rgba(52, 58, 64, 0.2);
}

.action-buttons form {
    display: inline;
    margin: 0;
}

.action-buttons form button {
    margin: 0;
}

/* Pagination */
.pagination {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 20px;
    border-top: 1px solid #e2e8f0;
    flex-wrap: wrap;
    gap: 16px;
}

.pagination-info {
    font-size: 14px;
    color: #666;
    flex: 1;
    min-width: 200px;
}

.pagination-controls {
    display: flex;
    gap: 6px;
    flex-wrap: wrap;
}

.pagination-controls .btn {
    min-width: 36px;
    height: 36px;
    padding: 0 8px;
    font-size: 13px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 4px;
}

.pagination-controls .btn-primary {
    background: #3490dc;
    color: white;
    border: 1px solid #3490dc;
}

.pagination-controls .btn-primary:hover {
    background: #2779bd;
    border-color: #2779bd;
}

.pagination-controls .btn-outline {
    background: transparent;
    color: #666;
    border: 1px solid #ddd;
}

.pagination-controls .btn-outline:hover {
    background: #f8f9fa;
    border-color: #ccc;
}

.pagination-jump {
    display: flex;
    align-items: center;
    gap: 8px;
}

.pagination-jump .input-group {
    width: auto;
}

/* Empty State */
.empty-state {
    text-align: center;
    padding: 60px 24px;
}

.empty-state i {
    font-size: 64px;
    color: #cbd5e0;
    margin-bottom: 20px;
    opacity: 0.5;
}

.empty-state h3 {
    font-size: 20px;
    font-weight: 600;
    color: #4a5568;
    margin: 0 0 12px 0;
}

.empty-state p {
    color: #a0aec0;
    font-size: 16px;
    margin: 0 0 30px 0;
}

.empty-state-actions {
    display: flex;
    gap: 12px;
    justify-content: center;
    flex-wrap: wrap;
}

.empty-state .btn {
    padding: 12px 24px;
    font-size: 16px;
    min-width: 200px;
}

/* Buttons */
.btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    padding: 10px 20px;
    border-radius: 6px;
    font-size: 14px;
    font-weight: 500;
    text-decoration: none;
    cursor: pointer;
    border: 1px solid transparent;
    transition: all 0.2s;
    height: 40px;
    box-sizing: border-box;
    white-space: nowrap;
}

.btn-primary {
    background: #3490dc;
    color: white;
    border-color: #3490dc;
}

.btn-primary:hover {
    background: #2779bd;
    border-color: #2779bd;
    transform: translateY(-2px);
    box-shadow: 0 4px 6px rgba(52, 144, 220, 0.2);
}

.btn-secondary {
    background: #6c757d;
    color: white;
    border-color: #6c757d;
}

.btn-secondary:hover {
    background: #5a6268;
    border-color: #5a6268;
    transform: translateY(-2px);
    box-shadow: 0 4px 6px rgba(108, 117, 125, 0.2);
}

.btn-success {
    background: #38a169;
    color: white;
    border-color: #38a169;
}

.btn-success:hover {
    background: #2f855a;
    border-color: #2f855a;
    transform: translateY(-2px);
    box-shadow: 0 4px 6px rgba(56, 161, 105, 0.2);
}

.btn-info {
    background: #17a2b8;
    color: white;
    border-color: #17a2b8;
}

.btn-info:hover {
    background: #138496;
    border-color: #117a8b;
    transform: translateY(-2px);
    box-shadow: 0 4px 6px rgba(23, 162, 184, 0.2);
}

.btn-outline {
    background: transparent;
    color: #4a5568;
    border-color: #e2e8f0;
}

.btn-outline:hover {
    background: #f8fafc;
    border-color: #cbd5e0;
    transform: translateY(-2px);
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
}

.btn-sm {
    padding: 6px 12px;
    font-size: 13px;
    height: 32px;
}

.btn-danger {
    background: #e53e3e;
    color: white;
    border-color: #e53e3e;
}

.btn-danger:hover {
    background: #c53030;
    border-color: #c53030;
    transform: translateY(-2px);
    box-shadow: 0 4px 6px rgba(229, 62, 62, 0.2);
}

/* Form Controls */
.form-control {
    width: 100%;
    padding: 10px 12px;
    border: 1px solid #ddd;
    border-radius: 4px;
    font-size: 14px;
    height: 40px;
    box-sizing: border-box;
}

.form-control:focus {
    outline: none;
    border-color: #3490dc;
    box-shadow: 0 0 0 2px rgba(52, 144, 220, 0.1);
}

.form-group {
    margin-bottom: 0;
}

.form-group label {
    display: block;
    margin-bottom: 6px;
    font-size: 12px;
    font-weight: 600;
    color: #4a5568;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

/* Alerts */
.alert {
    padding: 12px 16px;
    border-radius: 6px;
    margin-bottom: 20px;
    display: flex;
    align-items: center;
    gap: 10px;
    background: #fffaf0;
    border: 1px solid #fed7d7;
    color: #9c4221;
}

.alert i {
    font-size: 16px;
    color: #d69e2e;
}

/* Responsive */
@media (max-width: 1024px) {
    .stats-cards {
        grid-template-columns: repeat(2, 1fr);
    }
    
    .action-buttons .btn .btn-text {
        display: none;
    }
    
    .action-buttons .btn {
        min-width: 36px;
        width: 36px;
        padding: 8px !important;
        justify-content: center;
    }
    
    .action-buttons .btn i {
        margin: 0;
    }
}

@media (max-width: 768px) {
    .nominal-roll-container {
        padding: 15px;
    }
    
    .header-content {
        flex-direction: column;
        align-items: stretch;
        gap: 12px;
    }
    
    .header-actions {
        justify-content: flex-start;
        flex-wrap: wrap;
    }
    
    .header-actions .btn {
        min-width: 100px;
        flex: 1;
        max-width: calc(50% - 5px);
    }
    
    .search-row {
        flex-direction: column;
        align-items: stretch;
    }
    
    .search-input-group {
        min-width: 100%;
        flex-direction: column;
    }
    
    .btn-search {
        width: 100%;
    }
    
    .quick-actions {
        width: 100%;
        justify-content: center;
    }
    
    .stats-cards {
        grid-template-columns: 1fr;
        gap: 12px;
    }
    
    .table-header {
        flex-direction: column;
        gap: 12px;
        align-items: stretch;
    }
    
    .table-actions {
        justify-content: center;
    }
    
    .pagination {
        flex-direction: column;
        gap: 16px;
        align-items: stretch;
        text-align: center;
    }
    
    .pagination-controls {
        justify-content: center;
    }
    
    .filter-grid {
        grid-template-columns: 1fr;
    }
    
    .filter-actions {
        flex-direction: column;
    }
    
    .filter-actions .btn {
        width: 100%;
    }
    
    .action-buttons {
        gap: 6px;
    }
    
    .action-buttons .btn {
        flex: 1;
        min-width: 0;
        max-width: calc(25% - 5px);
    }
    
    .data-table th:nth-child(8),
    .data-table td:nth-child(8) {
        display: none;
    }
}

@media (max-width: 480px) {
    .header-actions .btn {
        max-width: 100%;
        width: 100%;
    }
    
    .stats-cards {
        grid-template-columns: 1fr;
    }
    
    .action-buttons {
        flex-wrap: wrap;
        justify-content: center;
    }
    
    .action-buttons .btn {
        flex: 0 0 calc(50% - 6px);
        max-width: calc(50% - 6px);
        margin-bottom: 6px;
    }
    
    .data-table th:nth-child(7),
    .data-table td:nth-child(7) {
        display: none;
    }
    
    .employee-name {
        min-width: 150px;
    }
    
    .empty-state-actions {
        flex-direction: column;
    }
    
    .empty-state .btn {
        width: 100%;
    }
}

/* Print Styles */
@media print {
    .header-actions,
    .search-filters-card,
    .action-buttons .btn:not(.btn-info),
    .action-buttons form,
    .pagination,
    .table-header {
        display: none !important;
    }
    
    .data-table-card {
        box-shadow: none !important;
        border: 1px solid #ddd !important;
    }
    
    .data-table th,
    .data-table td {
        border: 1px solid #ddd !important;
    }
    
    .badge {
        background: #f8f9fa !important;
        color: #000 !important;
        border: 1px solid #ddd !important;
    }
}
</style>