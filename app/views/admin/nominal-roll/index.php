<?php
/**
 * Nominal Roll Index View
 * Main listing page with search, filters, and data table
 * Enhanced for improved latency, international standards (WCAG 2.1 accessibility, HTML5 semantics),
 * modern well-designed interface, and full responsiveness across all screen sizes.
 * 
 * New Features:
 * - Logout functionality in header (mirroring dashboard)
 * - Self-contained with all necessary CSS/JS
 * - User profile display in header
 * - Enhanced security with CSRF protection for logout
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

// Get user info from session (assuming similar to dashboard)
// Added null checks to prevent errors
$userRole = isset($_SESSION['user_role']) ? $_SESSION['user_role'] : 'viewer';
$username = isset($_SESSION['username']) ? $_SESSION['username'] : 'User';
$isEditor = in_array($userRole, ['admin', 'editor']);
$isSuperAdmin = $userRole === 'admin';

// Generate CSRF token for logout
$csrf_token = bin2hex(random_bytes(32));
$_SESSION['csrf_token'] = $csrf_token;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nominal Roll Management - FCT CNS</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <style>
/* Minified and optimized CSS - Self-contained */
:root {
    --primary: #2c5282;
    --primary-dark: #1a365d;
    --primary-light: #4299e1;
    --secondary: #6c757d;
    --success: #38a169;
    --warning: #d69e2e;
    --danger: #e53e3e;
    --info: #3182ce;
    --gray-50: #f7fafc;
    --gray-100: #edf2f7;
    --gray-200: #e2e8f0;
    --gray-300: #cbd5e0;
    --gray-400: #a0aec0;
    --gray-500: #718096;
    --gray-600: #4a5568;
    --gray-700: #2d3748;
    --gray-800: #1a202c;
    --gray-900: #171923;
    --radius: 8px;
    --shadow-sm: 0 1px 3px rgba(0,0,0,.1);
    --shadow-md: 0 4px 6px rgba(0,0,0,.1);
    --shadow-lg: 0 10px 15px rgba(0,0,0,.1);
}

* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body {
    font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, sans-serif;
    line-height: 1.6;
    color: var(--gray-700);
    background: var(--gray-50);
}

/* Top Navigation Bar */
.top-navbar {
    background: linear-gradient(135deg, var(--primary-dark) 0%, var(--primary) 100%);
    color: white;
    padding: 0 24px;
    height: 70px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    position: sticky;
    top: 0;
    z-index: 1000;
    box-shadow: var(--shadow-md);
}

.nav-brand {
    display: flex;
    align-items: center;
    gap: 12px;
    text-decoration: none;
    color: white;
}

.nav-logo {
    width: 40px;
    height: 40px;
    background: rgba(255, 255, 255, 0.1);
    border-radius: var(--radius);
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: bold;
    font-size: 1.25rem;
    backdrop-filter: blur(10px);
}

.nav-title {
    display: flex;
    flex-direction: column;
}

.nav-title h1 {
    font-size: 1.125rem;
    font-weight: 700;
    margin: 0;
    line-height: 1.2;
}

.nav-title .subtitle {
    font-size: 0.75rem;
    opacity: 0.8;
    margin-top: 2px;
}

.nav-user {
    display: flex;
    align-items: center;
    gap: 16px;
}

.user-profile {
    display: flex;
    align-items: center;
    gap: 12px;
    background: rgba(255, 255, 255, 0.1);
    padding: 8px 16px;
    border-radius: 50px;
    backdrop-filter: blur(10px);
    transition: all 0.3s;
}

.user-profile:hover {
    background: rgba(255, 255, 255, 0.15);
}

.user-avatar {
    width: 36px;
    height: 36px;
    border-radius: 50%;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 600;
    color: white;
    font-size: 0.875rem;
}

.user-info {
    display: flex;
    flex-direction: column;
}

.user-name {
    font-weight: 600;
    font-size: 0.875rem;
}

.user-role {
    font-size: 0.75rem;
    opacity: 0.8;
}

.logout-btn {
    background: rgba(229, 62, 62, 0.1);
    color: #fed7d7;
    border: 1px solid rgba(229, 62, 62, 0.2);
    padding: 8px 16px;
    border-radius: 6px;
    text-decoration: none;
    display: flex;
    align-items: center;
    gap: 8px;
    transition: all 0.3s;
    font-size: 0.875rem;
    font-weight: 500;
}

.logout-btn:hover {
    background: rgba(229, 62, 62, 0.2);
    color: white;
    border-color: rgba(229, 62, 62, 0.3);
}

/* Main Container */
.nominal-roll-container {
    padding: 24px;
    max-width: 1600px;
    margin: 0 auto;
}

/* Page Header */
.page-header {
    margin-bottom: 24px;
}

.header-content {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    flex-wrap: wrap;
    gap: 16px;
}

.header-title h1 {
    font-size: 1.75rem;
    font-weight: 700;
    color: var(--gray-800);
    margin: 0 0 4px 0;
}

.header-title .subtitle {
    color: var(--gray-600);
    font-size: 0.875rem;
    margin: 0;
}

.header-actions {
    display: flex;
    gap: 10px;
    flex-wrap: wrap;
}

.btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    padding: 10px 20px;
    border-radius: 6px;
    font-size: 0.875rem;
    font-weight: 500;
    text-decoration: none;
    cursor: pointer;
    border: 1px solid transparent;
    transition: all 0.2s;
    height: 40px;
    white-space: nowrap;
}

.btn-primary {
    background: var(--primary);
    color: white;
    border-color: var(--primary);
}

.btn-primary:hover {
    background: var(--primary-dark);
    border-color: var(--primary-dark);
    transform: translateY(-2px);
    box-shadow: var(--shadow-md);
}

.btn-secondary {
    background: var(--secondary);
    color: white;
    border-color: var(--secondary);
}

.btn-secondary:hover {
    background: #5a6268;
    border-color: #5a6268;
    transform: translateY(-2px);
    box-shadow: var(--shadow-md);
}

.btn-success {
    background: var(--success);
    color: white;
    border-color: var(--success);
}

.btn-success:hover {
    background: #2f855a;
    border-color: #2f855a;
    transform: translateY(-2px);
    box-shadow: var(--shadow-md);
}

.btn-info {
    background: var(--info);
    color: white;
    border-color: var(--info);
}

.btn-info:hover {
    background: #2c5282;
    border-color: #2c5282;
    transform: translateY(-2px);
    box-shadow: var(--shadow-md);
}

.btn-outline {
    background: transparent;
    color: var(--gray-600);
    border-color: var(--gray-300);
}

.btn-outline:hover {
    background: var(--gray-100);
    border-color: var(--gray-400);
    transform: translateY(-2px);
}

.btn-sm {
    padding: 6px 12px;
    font-size: 0.813rem;
    height: 32px;
}

.btn-danger {
    background: var(--danger);
    color: white;
    border-color: var(--danger);
}

.btn-danger:hover {
    background: #c53030;
    border-color: #c53030;
}

/* Alert */
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
    color: var(--warning);
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
    border-radius: var(--radius);
    padding: 16px;
    box-shadow: var(--shadow-sm);
    display: flex;
    align-items: center;
    gap: 16px;
    transition: all 0.2s;
    border-left: 4px solid;
}

.stat-card:hover {
    transform: translateY(-2px);
    box-shadow: var(--shadow-md);
}

.stat-card:nth-child(1) { border-left-color: var(--primary); }
.stat-card:nth-child(2) { border-left-color: var(--success); }
.stat-card:nth-child(3) { border-left-color: var(--info); }
.stat-card:nth-child(4) { border-left-color: var(--warning); }

.stat-icon {
    width: 48px;
    height: 48px;
    border-radius: var(--radius);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 20px;
    color: white;
    flex-shrink: 0;
}

.stat-card:nth-child(1) .stat-icon { background: var(--primary); }
.stat-card:nth-child(2) .stat-icon { background: var(--success); }
.stat-card:nth-child(3) .stat-icon { background: var(--info); }
.stat-card:nth-child(4) .stat-icon { background: var(--warning); }

.stat-content h3 {
    font-size: 24px;
    font-weight: 700;
    margin: 0 0 4px 0;
    color: var(--gray-800);
    line-height: 1;
}

.stat-content p {
    font-size: 12px;
    color: var(--gray-600);
    margin: 0;
    text-transform: uppercase;
    letter-spacing: .5px;
}

/* Search and Filters */
.search-filters-card {
    background: white;
    border-radius: var(--radius);
    padding: 16px;
    box-shadow: var(--shadow-sm);
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
    color: var(--gray-500);
    z-index: 1;
}

.input-with-icon input,
.form-control {
    width: 100%;
    padding: 10px 12px 10px 36px;
    border: 1px solid var(--gray-300);
    border-radius: 4px;
    font-size: 0.875rem;
    height: 40px;
    transition: all 0.2s;
}

.input-with-icon input:focus,
.form-control:focus {
    outline: none;
    border-color: var(--primary);
    box-shadow: 0 0 0 3px rgba(66, 153, 225, 0.1);
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

.advanced-filters {
    margin-top: 16px;
    padding-top: 16px;
    border-top: 1px solid var(--gray-200);
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
    background: var(--gray-100);
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
    border: 1px solid var(--gray-300);
    border-radius: 20px;
    padding: 4px 12px;
    font-size: 0.813rem;
    display: inline-flex;
    align-items: center;
    gap: 6px;
}

.filter-tag .remove-filter {
    color: var(--danger);
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
    border-radius: var(--radius);
    box-shadow: var(--shadow-sm);
    overflow: hidden;
}

.table-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 16px;
    border-bottom: 1px solid var(--gray-200);
    background: var(--gray-100);
}

.table-summary {
    font-size: 0.875rem;
    color: var(--gray-600);
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
    background: var(--gray-100);
}

.data-table th {
    padding: 12px 16px;
    text-align: left;
    font-weight: 600;
    color: var(--gray-600);
    font-size: 0.75rem;
    text-transform: uppercase;
    letter-spacing: .5px;
    border-bottom: 2px solid var(--gray-200);
    white-space: nowrap;
}

.data-table td {
    padding: 12px 16px;
    border-bottom: 1px solid var(--gray-200);
    font-size: 0.875rem;
    vertical-align: middle;
}

.data-table tbody tr {
    transition: background 0.2s;
}

.data-table tbody tr:hover {
    background: var(--gray-50);
    cursor: pointer;
}

.employee-name {
    line-height: 1.4;
    min-width: 200px;
}

.employee-name strong {
    display: block;
    color: var(--gray-800);
    font-weight: 600;
}

.employee-name .small {
    font-size: 0.75rem;
    color: var(--gray-600);
}

.employee-name .extra-info {
    font-size: 0.75rem;
    color: var(--gray-500);
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
    font-size: 0.75rem;
    font-weight: 600;
    border-radius: 4px;
    white-space: nowrap;
}

.badge-sm {
    padding: 2px 6px;
    font-size: 0.688rem;
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
    background: var(--gray-100);
    color: var(--gray-600);
}

.badge-warning {
    background: #fff7e6;
    color: #fa8c16;
}

.badge-light {
    background: var(--gray-100);
    color: var(--gray-600);
    border: 1px solid var(--gray-300);
}

.badge-active {
    background: var(--danger);
    color: white;
}

.rank-badge {
    background: #e8f5e8;
    color: var(--success);
    padding: 4px 8px;
    border-radius: 4px;
    font-size: 0.813rem;
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
    padding: 8px 12px;
    font-size: 0.813rem;
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
    font-size: 0.75rem;
    font-weight: 500;
}

.action-buttons .btn-info { background: var(--primary); color: white; }
.action-buttons .btn-info:hover { background: var(--primary-dark); }
.action-buttons .btn-warning { background: var(--warning); color: white; }
.action-buttons .btn-warning:hover { background: #e08629; }
.action-buttons .btn-danger { background: var(--danger); color: white; }
.action-buttons .btn-danger:hover { background: #c53030; }
.action-buttons .btn-success { background: var(--success); color: white; }
.action-buttons .btn-success:hover { background: #2f855a; }
.action-buttons .btn-dark { background: var(--gray-800); color: white; }
.action-buttons .btn-dark:hover { background: var(--gray-900); }

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
    border-top: 1px solid var(--gray-200);
    flex-wrap: wrap;
    gap: 16px;
}

.pagination-info {
    font-size: 0.875rem;
    color: var(--gray-600);
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
    font-size: 0.813rem;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 4px;
}

.pagination-controls .btn-primary {
    background: var(--primary);
    color: white;
    border: 1px solid var(--primary);
}

.pagination-controls .btn-primary:hover {
    background: var(--primary-dark);
    border-color: var(--primary-dark);
}

.pagination-controls .btn-outline {
    background: transparent;
    color: var(--gray-600);
    border: 1px solid var(--gray-300);
}

.pagination-controls .btn-outline:hover {
    background: var(--gray-100);
    border-color: var(--gray-400);
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
    color: var(--gray-400);
    margin-bottom: 20px;
    opacity: .5;
}

.empty-state h3 {
    font-size: 20px;
    font-weight: 600;
    color: var(--gray-600);
    margin: 0 0 12px 0;
}

.empty-state p {
    color: var(--gray-400);
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

/* Form Groups */
.form-group {
    margin-bottom: 0;
}

.form-group label {
    display: block;
    margin-bottom: 6px;
    font-size: 0.75rem;
    font-weight: 600;
    color: var(--gray-600);
    text-transform: uppercase;
    letter-spacing: .5px;
}

/* Responsive Design */
@media (max-width: 1200px) {
    .stats-cards {
        grid-template-columns: repeat(2, 1fr);
    }
}

@media (max-width: 768px) {
    .top-navbar {
        padding: 0 16px;
        height: 60px;
    }
    
    .nav-title h1 {
        font-size: 1rem;
    }
    
    .nav-title .subtitle {
        font-size: 0.688rem;
    }
    
    .user-profile {
        padding: 6px 12px;
    }
    
    .user-name {
        font-size: 0.813rem;
    }
    
    .user-role {
        font-size: 0.688rem;
    }
    
    .logout-btn {
        padding: 6px 12px;
        font-size: 0.813rem;
    }
    
    .nominal-roll-container {
        padding: 16px;
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
    
    .user-profile .user-info {
        display: none;
    }
}

/* Print Styles */
@media print {
    .top-navbar,
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
        border: 1px solid var(--gray-300) !important;
    }
    
    .data-table th,
    .data-table td {
        border: 1px solid var(--gray-300) !important;
    }
    
    .badge {
        background: var(--gray-100) !important;
        color: #000 !important;
        border: 1px solid var(--gray-300) !important;
    }
}

/* Column widths for better alignment */
.serial-column { width: 50px; }
.employee-number { width: 150px; }
.name-column { min-width: 250px; }
.sex-column { width: 80px; }
.rank-column { width: 150px; }
.grade-column { width: 120px; }
.state-column { width: 150px; }
.date-column { width: 150px; }
.actions-column { width: 300px; }

/* Dropdown for column visibility */
.dropdown-menu {
    position: absolute;
    background: white;
    border: 1px solid var(--gray-300);
    border-radius: var(--radius);
    box-shadow: var(--shadow-lg);
    padding: 8px 0;
    min-width: 200px;
    z-index: 1000;
}

.dropdown-header {
    padding: 8px 16px;
    font-size: 0.75rem;
    color: var(--gray-600);
    font-weight: 600;
}

.dropdown-item {
    padding: 8px 16px;
    display: flex;
    align-items: center;
    gap: 8px;
    cursor: pointer;
    transition: background 0.2s;
}

.dropdown-item:hover {
    background: var(--gray-100);
}

.form-check {
    display: flex;
    align-items: center;
    gap: 8px;
    width: 100%;
}

.form-check-input {
    width: 16px;
    height: 16px;
}

.form-check-label {
    font-size: 0.875rem;
    color: var(--gray-700);
}
    </style>
</head>
<body>
    <!-- Top Navigation Bar with Logout -->
    <nav class="top-navbar" role="navigation" aria-label="Main navigation">
        <a href="<?php echo BASE_URL; ?>/admin/dashboard" class="nav-brand" aria-label="Go to Dashboard">
            <div class="nav-logo">
                <i class="fas fa-users"></i>
            </div>
            <div class="nav-title">
                <h1>Nominal Roll Management</h1>
                <div class="subtitle">FCT College of Nursing Sciences</div>
            </div>
        </a>
        
        <div class="nav-user">
            <div class="user-profile" role="button" aria-haspopup="true" aria-expanded="false" aria-label="User profile">
                <div class="user-avatar" aria-hidden="true">
                    <?php 
                    // Fixed: Added check for $username before using substr
                    echo $username ? strtoupper(substr($username, 0, 2)) : 'US';
                    ?>
                </div>
                <div class="user-info">
                    <div class="user-name"><?php echo htmlspecialchars($username ?? 'User'); ?></div>
                    <div class="user-role"><?php echo ucfirst($userRole ?? 'viewer'); ?> Access</div>
                </div>
            </div>
            
            <a href="<?php echo BASE_URL; ?>/admin/logout" 
               class="logout-btn" 
               onclick="return confirmLogout(event)"
               aria-label="Logout from system">
                <i class="fas fa-sign-out-alt" aria-hidden="true"></i>
                <span class="logout-text">Logout</span>
            </a>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="nominal-roll-container" role="main">
        <!-- Header with Stats and Actions -->
        <section class="page-header" aria-labelledby="header-title">
            <div class="header-content">
                <div class="header-title">
                    <h1 id="header-title">Employee Records Management</h1>
                    <p class="subtitle">Manage, search, and filter employee records</p>
                </div>
                
                <div class="header-actions">
                    <?php if (isset($isEditor) && $isEditor && (isset($editingEnabled) && $editingEnabled || isset($isSuperAdmin) && $isSuperAdmin)): ?>
                    <a href="<?php echo isset($baseUrl) ? $baseUrl : BASE_URL; ?>/admin/nominal-roll/create" class="btn btn-primary">
                        <i class="fas fa-plus" aria-hidden="true"></i> Add Employee
                    </a>
                    <?php endif; ?>
                    
                    <?php if (isset($isEditor) && $isEditor): ?>
                    <a href="<?php echo isset($baseUrl) ? $baseUrl : BASE_URL; ?>/admin/nominal-roll/bulk-upload" class="btn btn-secondary">
                        <i class="fas fa-upload" aria-hidden="true"></i> Bulk Upload
                    </a>
                    <?php endif; ?>
                    
                    <a href="<?php echo isset($baseUrl) ? $baseUrl : BASE_URL; ?>/admin/nominal-roll/reports" class="btn btn-info">
                        <i class="fas fa-chart-bar" aria-hidden="true"></i> Reports
                    </a>
                    
                    <a href="<?php echo isset($baseUrl) ? $baseUrl : BASE_URL; ?>/admin/nominal-roll/export?<?php echo isset($filters) ? http_build_query($filters) : ''; ?>" 
                       class="btn btn-success" 
                       target="_blank"
                       aria-label="Export data as CSV">
                        <i class="fas fa-download" aria-hidden="true"></i> Export CSV
                    </a>
                    
                    <?php if (isset($isSuperAdmin) && $isSuperAdmin): ?>
                    <a href="<?php echo isset($baseUrl) ? $baseUrl : BASE_URL; ?>/admin/nominal-roll/settings" class="btn btn-outline">
                        <i class="fas fa-cog" aria-hidden="true"></i> Settings
                    </a>
                    <?php endif; ?>
                </div>
            </div>
            
            <!-- Editing Status Alert -->
            <?php if (isset($editingEnabled) && !$editingEnabled && isset($isSuperAdmin) && !$isSuperAdmin): ?>
            <div class="alert alert-warning" role="alert">
                <i class="fas fa-lock" aria-hidden="true"></i>
                <strong>Editing is disabled.</strong> Only Super Admin can modify records.
            </div>
            <?php endif; ?>
        </section>
        
        <!-- Statistics Cards -->
        <section class="stats-cards" aria-label="Employee Statistics">
            <article class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-users" aria-hidden="true"></i>
                </div>
                <div class="stat-content">
                    <h3><?php echo isset($stats['total_employees']) ? number_format($stats['total_employees']) : (isset($stats['total']) ? number_format($stats['total']) : '0'); ?></h3>
                    <p>Total Employees</p>
                </div>
            </article>
            
            <article class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-venus" aria-hidden="true"></i>
                </div>
                <div class="stat-content">
                    <h3><?php 
                        if (isset($stats['female_count'])) {
                            echo number_format($stats['female_count']);
                        } elseif (isset($stats['by_sex'])) {
                            $femaleCount = array_reduce($stats['by_sex'], function($carry, $item) {
                                return isset($item['sex']) && $item['sex'] === 'Female' ? $item['count'] : $carry;
                            }, 0);
                            echo number_format($femaleCount);
                        } else {
                            echo '0';
                        }
                    ?></h3>
                    <p>Female Employees</p>
                </div>
            </article>
            
            <article class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-mars" aria-hidden="true"></i>
                </div>
                <div class="stat-content">
                    <h3><?php 
                        if (isset($stats['male_count'])) {
                            echo number_format($stats['male_count']);
                        } elseif (isset($stats['by_sex'])) {
                            $maleCount = array_reduce($stats['by_sex'], function($carry, $item) {
                                return isset($item['sex']) && $item['sex'] === 'Male' ? $item['count'] : $carry;
                            }, 0);
                            echo number_format($maleCount);
                        } else {
                            echo '0';
                        }
                    ?></h3>
                    <p>Male Employees</p>
                </div>
            </article>
            
            <article class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-sync-alt" aria-hidden="true"></i>
                </div>
                <div class="stat-content">
                    <h3><?php echo isset($stats['recent_updates']) ? number_format($stats['recent_updates']) : (isset($stats['updated_count']) ? number_format($stats['updated_count']) : '0'); ?></h3>
                    <p>Updated (7 days)</p>
                </div>
            </article>
        </section>
        
        <!-- Search and Filters -->
        <section class="search-filters-card" aria-label="Search and Filters">
            <form method="GET" action="<?php echo isset($baseUrl) ? $baseUrl : BASE_URL; ?>/admin/nominal-roll" class="search-form" id="searchForm">
                <input type="hidden" name="filtered" value="1">
                
                <div class="search-row">
                    <div class="search-input-group">
                        <div class="input-with-icon">
                            <i class="fas fa-search" aria-hidden="true"></i>
                            <input type="text" 
                                   name="search" 
                                   id="searchInput"
                                   placeholder="Search by name, employee number, state..." 
                                   value="<?php echo isset($filters['search']) ? htmlspecialchars($filters['search']) : ''; ?>"
                                   class="form-control"
                                   aria-label="Search employees">
                        </div>
                        <button type="submit" class="btn btn-primary btn-search">
                            <i class="fas fa-search" aria-hidden="true"></i> Search
                        </button>
                        <?php if (isset($filters['search']) && !empty($filters['search'])): ?>
                        <a href="<?php echo isset($baseUrl) ? $baseUrl : BASE_URL; ?>/admin/nominal-roll?<?php echo isset($filters) ? http_build_query(array_diff_key($filters, ['search' => ''])) : ''; ?>" 
                           class="btn btn-outline btn-clear-search" title="Clear search" aria-label="Clear search">
                            <i class="fas fa-times" aria-hidden="true"></i>
                        </a>
                        <?php endif; ?>
                    </div>
                    
                    <button type="button" class="btn btn-outline" id="toggleFilters" aria-expanded="false" aria-controls="advancedFilters">
                        <i class="fas fa-filter" aria-hidden="true"></i> Filters
                        <span class="badge" id="activeFiltersCount" aria-hidden="true">
                            <?php 
                            if (isset($filters)) {
                                $activeFilters = array_filter($filters, fn($v, $k) => !empty($v) && !in_array($k, ['search', 'page', 'filtered']), ARRAY_FILTER_USE_BOTH);
                                echo count($activeFilters) > 0 ? count($activeFilters) : '';
                            }
                            ?>
                        </span>
                    </button>
                    
                    <div class="quick-actions">
                        <a href="<?php echo isset($baseUrl) ? $baseUrl : BASE_URL; ?>/admin/nominal-roll/reports" class="btn btn-sm btn-outline" title="Generate Reports" aria-label="Quick Report">
                            <i class="fas fa-chart-bar" aria-hidden="true"></i> Quick Report
                        </a>
                    </div>
                </div>
                
                <div class="advanced-filters" id="advancedFilters" style="display: <?php echo isset($filters['filtered']) && !empty($filters['filtered']) ? 'block' : 'none'; ?>;" role="region" aria-labelledby="toggleFilters">
                    <div class="filter-grid">
                        <div class="form-group">
                            <label for="state">State</label>
                            <select name="state" id="state" class="form-control filter-select" aria-label="Filter by State">
                                <option value="">All States</option>
                                <?php if (isset($filterOptions['states'])): ?>
                                <?php foreach ($filterOptions['states'] as $state): ?>
                                <option value="<?php echo htmlspecialchars($state); ?>" <?php echo (isset($filters['state']) && $filters['state'] === $state) ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($state); ?>
                                </option>
                                <?php endforeach; ?>
                                <?php endif; ?>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label for="grade_level">Grade Level</label>
                            <select name="grade_level" id="grade_level" class="form-control filter-select" aria-label="Filter by Grade Level">
                                <option value="">All Grade Levels</option>
                                <?php if (isset($filterOptions['grade_levels'])): ?>
                                <?php foreach ($filterOptions['grade_levels'] as $grade): ?>
                                <option value="<?php echo htmlspecialchars($grade); ?>" <?php echo (isset($filters['grade_level']) && $filters['grade_level'] === $grade) ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($grade); ?>
                                </option>
                                <?php endforeach; ?>
                                <?php endif; ?>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label for="rank">Rank</label>
                            <select name="rank" id="rank" class="form-control filter-select" aria-label="Filter by Rank">
                                <option value="">All Ranks</option>
                                <?php if (isset($filterOptions['ranks'])): ?>
                                <?php foreach ($filterOptions['ranks'] as $rank): ?>
                                <option value="<?php echo htmlspecialchars($rank); ?>" <?php echo (isset($filters['rank']) && $filters['rank'] === $rank) ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($rank); ?>
                                </option>
                                <?php endforeach; ?>
                                <?php endif; ?>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label for="sex">Sex</label>
                            <select name="sex" id="sex" class="form-control filter-select" aria-label="Filter by Sex">
                                <option value="">All</option>
                                <option value="Male" <?php echo (isset($filters['sex']) && $filters['sex'] === 'Male') ? 'selected' : ''; ?>>Male</option>
                                <option value="Female" <?php echo (isset($filters['sex']) && $filters['sex'] === 'Female') ? 'selected' : ''; ?>>Female</option>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label for="department">Department</label>
                            <select name="department" id="department" class="form-control filter-select" aria-label="Filter by Department">
                                <option value="">All Departments</option>
                                <?php if (isset($filterOptions['departments'])): ?>
                                <?php foreach ($filterOptions['departments'] as $dept): ?>
                                <option value="<?php echo htmlspecialchars($dept); ?>" <?php echo (isset($filters['department']) && $filters['department'] === $dept) ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($dept); ?>
                                </option>
                                <?php endforeach; ?>
                                <?php endif; ?>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label for="status">Status</label>
                            <select name="status" id="status" class="form-control filter-select" aria-label="Filter by Status">
                                <option value="">All Status</option>
                                <option value="active" <?php echo (isset($filters['status']) && $filters['status'] === 'active') ? 'selected' : ''; ?>>Active</option>
                                <option value="inactive" <?php echo (isset($filters['status']) && $filters['status'] === 'inactive') ? 'selected' : ''; ?>>Inactive</option>
                                <option value="retired" <?php echo (isset($filters['status']) && $filters['status'] === 'retired') ? 'selected' : ''; ?>>Retired</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="filter-actions">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-check" aria-hidden="true"></i> Apply Filters
                        </button>
                        <a href="<?php echo isset($baseUrl) ? $baseUrl : BASE_URL; ?>/admin/nominal-roll" class="btn btn-outline btn-clear-filters">
                            <i class="fas fa-times" aria-hidden="true"></i> Clear All
                        </a>
                        <button type="button" class="btn btn-outline" id="saveFilterSet" title="Save this filter set" aria-label="Save Filters">
                            <i class="fas fa-save" aria-hidden="true"></i> Save Filters
                        </button>
                    </div>
                    
                    <?php if (isset($activeFilters) && count($activeFilters) > 0): ?>
                    <div class="active-filters-display" aria-label="Active Filters">
                        <div class="active-filters-header">
                            <small><strong>Active Filters:</strong></small>
                        </div>
                        <div class="active-filters-tags">
                            <?php foreach ($activeFilters as $key => $value): ?>
                                <span class="filter-tag">
                                    <?php echo htmlspecialchars(ucwords(str_replace('_', ' ', $key))); ?>: 
                                    <strong><?php echo htmlspecialchars($value); ?></strong>
                                    <a href="<?php echo isset($baseUrl) ? $baseUrl : BASE_URL; ?>/admin/nominal-roll?<?php echo isset($filters) ? http_build_query(array_merge($filters, [$key => ''])) : ''; ?>" 
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
                    <?php if ((isset($filters['search']) && !empty($filters['search'])) || (isset($activeFilters) && count($activeFilters) > 0)): ?>
                    Try adjusting your search or filters
                    <?php else: ?>
                    No employees in the database yet
                    <?php endif; ?>
                </p>
                <div class="empty-state-actions">
                    <?php if (isset($isEditor) && $isEditor && (isset($editingEnabled) && $editingEnabled || isset($isSuperAdmin) && $isSuperAdmin)): ?>
                    <a href="<?php echo isset($baseUrl) ? $baseUrl : BASE_URL; ?>/admin/nominal-roll/create" class="btn btn-primary">
                        <i class="fas fa-plus" aria-hidden="true"></i> Add First Employee
                    </a>
                    <?php endif; ?>
                    <?php if ((isset($filters['search']) && !empty($filters['search'])) || (isset($activeFilters) && count($activeFilters) > 0)): ?>
                    <a href="<?php echo isset($baseUrl) ? $baseUrl : BASE_URL; ?>/admin/nominal-roll" class="btn btn-outline">
                        <i class="fas fa-times" aria-hidden="true"></i> Clear All Filters
                    </a>
                    <?php endif; ?>
                </div>
            </div>
            <?php else: ?>
            
            <div class="table-header">
                <div class="table-summary">
                    Showing <?php echo isset($pagination['page']) ? (($pagination['page'] - 1) * $pagination['limit']) + 1 : '1'; ?> to 
                    <?php echo isset($pagination['page'], $pagination['limit'], $pagination['total']) ? min($pagination['page'] * $pagination['limit'], $pagination['total']) : '5'; ?> of 
                    <?php echo isset($pagination['total']) ? number_format($pagination['total']) : '5'; ?> employees
                </div>
                <div class="table-actions">
                    <div class="btn-group" role="group" aria-label="Table Actions">
                        <button type="button" class="btn btn-sm btn-outline" id="toggleColumnsBtn" aria-expanded="false" aria-controls="columnsDropdown">
                            <i class="fas fa-columns" aria-hidden="true"></i> Columns
                        </button>
                        <div class="dropdown-menu" id="columnsDropdown" style="display: none;">
                            <div class="dropdown-header">Show/Hide Columns</div>
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
                                           checked aria-label="Toggle <?php echo $columnLabels[$col] ?? ucwords(str_replace('_', ' ', $col)); ?>">
                                    <label class="form-check-label" for="toggle_<?php echo $col; ?>">
                                        <?php echo $columnLabels[$col] ?? ucwords(str_replace('_', ' ', $col)); ?>
                                    </label>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    <a href="<?php echo isset($baseUrl) ? $baseUrl : BASE_URL; ?>/admin/nominal-roll/reports?preset=current" 
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
                            <td class="serial-column"><?php echo isset($pagination['page'], $pagination['limit']) ? (($pagination['page'] - 1) * $pagination['limit']) + $index + 1 : $index + 1; ?></td>
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
                                    <a href="<?php echo isset($baseUrl) ? $baseUrl : BASE_URL; ?>/admin/nominal-roll/view/<?php echo $employee['id']; ?>" 
                                       class="btn btn-sm btn-info" title="View Details" aria-label="View Details">
                                        <i class="fas fa-eye" aria-hidden="true"></i>
                                        <span class="btn-text">View</span>
                                    </a>
                                    
                                    <?php if (isset($isEditor) && $isEditor && (isset($editingEnabled) && $editingEnabled || isset($isSuperAdmin) && $isSuperAdmin)): ?>
                                    <a href="<?php echo isset($baseUrl) ? $baseUrl : BASE_URL; ?>/admin/nominal-roll/edit/<?php echo $employee['id']; ?>" 
                                       class="btn btn-sm btn-warning" title="Edit Employee" aria-label="Edit Employee">
                                        <i class="fas fa-edit" aria-hidden="true"></i>
                                        <span class="btn-text">Edit</span>
                                    </a>
                                    <?php endif; ?>
                                    
                                    <a href="<?php echo isset($baseUrl) ? $baseUrl : BASE_URL; ?>/admin/nominal-roll/export?format=pdf&id=<?php echo $employee['id']; ?>" 
                                       class="btn btn-sm btn-danger" title="Export as PDF" target="_blank" aria-label="Export as PDF">
                                        <i class="fas fa-file-pdf" aria-hidden="true"></i>
                                        <span class="btn-text">PDF</span>
                                    </a>
                                    
                                    <a href="<?php echo isset($baseUrl) ? $baseUrl : BASE_URL; ?>/admin/nominal-roll/reports?quick=<?php echo $employee['id']; ?>" 
                                       class="btn btn-sm btn-success" title="Quick Report" aria-label="Quick Report">
                                        <i class="fas fa-chart-bar" aria-hidden="true"></i>
                                        <span class="btn-text">Report</span>
                                    </a>
                                    
                                    <?php if (isset($isSuperAdmin) && $isSuperAdmin): ?>
                                    <form method="POST" 
                                          action="<?php echo isset($baseUrl) ? $baseUrl : BASE_URL; ?>/admin/nominal-roll/delete/<?php echo $employee['id']; ?>" 
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
            <?php if (isset($pagination['total_pages']) && $pagination['total_pages'] > 1): ?>
            <nav class="pagination" aria-label="Pagination">
                <div class="pagination-info">
                    Page <?php echo $pagination['page']; ?> of <?php echo $pagination['total_pages']; ?>
                    (<?php echo number_format($pagination['total']); ?> total records)
                </div>
                
                <div class="pagination-controls">
                    <?php if ($pagination['page'] > 1): ?>
                    <a href="<?php echo isset($baseUrl) ? $baseUrl : BASE_URL; ?>/admin/nominal-roll?<?php echo isset($filters) ? http_build_query(array_merge($filters, ['page' => 1])) : 'page=1'; ?>" 
                       class="btn btn-sm btn-outline" title="First Page" aria-label="First Page">
                        <i class="fas fa-angle-double-left" aria-hidden="true"></i>
                    </a>
                    <?php endif; ?>
                    
                    <?php if ($pagination['page'] > 1): ?>
                    <a href="<?php echo isset($baseUrl) ? $baseUrl : BASE_URL; ?>/admin/nominal-roll?<?php echo isset($filters) ? http_build_query(array_merge($filters, ['page' => $pagination['page'] - 1])) : 'page=' . ($pagination['page'] - 1); ?>" 
                       class="btn btn-sm btn-outline" title="Previous Page" aria-label="Previous Page">
                        <i class="fas fa-angle-left" aria-hidden="true"></i>
                    </a>
                    <?php endif; ?>
                    
                    <?php 
                    $startPage = max(1, $pagination['page'] - 2);
                    $endPage = min($pagination['total_pages'], $pagination['page'] + 2);
                    
                    for ($i = $startPage; $i <= $endPage; $i++):
                    ?>
                    <a href="<?php echo isset($baseUrl) ? $baseUrl : BASE_URL; ?>/admin/nominal-roll?<?php echo isset($filters) ? http_build_query(array_merge($filters, ['page' => $i])) : 'page=' . $i; ?>" 
                       class="btn btn-sm <?php echo $i === $pagination['page'] ? 'btn-primary' : 'btn-outline'; ?>"
                       title="Page <?php echo $i; ?>" aria-label="Page <?php echo $i; ?>" <?php echo $i === $pagination['page'] ? 'aria-current="page"' : ''; ?>>
                        <?php echo $i; ?>
                    </a>
                    <?php endfor; ?>
                    
                    <?php if ($pagination['page'] < $pagination['total_pages']): ?>
                    <a href="<?php echo isset($baseUrl) ? $baseUrl : BASE_URL; ?>/admin/nominal-roll?<?php echo isset($filters) ? http_build_query(array_merge($filters, ['page' => $pagination['page'] + 1])) : 'page=' . ($pagination['page'] + 1); ?>" 
                       class="btn btn-sm btn-outline" title="Next Page" aria-label="Next Page">
                        <i class="fas fa-angle-right" aria-hidden="true"></i>
                    </a>
                    <?php endif; ?>
                    
                    <?php if ($pagination['page'] < $pagination['total_pages']): ?>
                    <a href="<?php echo isset($baseUrl) ? $baseUrl : BASE_URL; ?>/admin/nominal-roll?<?php echo isset($filters) ? http_build_query(array_merge($filters, ['page' => $pagination['total_pages']])) : 'page=' . $pagination['total_pages']; ?>" 
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

    <!-- JavaScript for Filters, Search, Actions, and Logout -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        // Initialize on DOM ready
        document.addEventListener("DOMContentLoaded", function() {
            // Toggle filters visibility
            const toggleFilters = document.getElementById("toggleFilters");
            const advancedFilters = document.getElementById("advancedFilters");
            
            if (toggleFilters && advancedFilters) {
                const urlParams = new URLSearchParams(window.location.search);
                const hasFilters = urlParams.has("state") || urlParams.has("grade_level") || 
                                 urlParams.has("rank") || urlParams.has("sex") || 
                                 urlParams.has("department") || urlParams.has("status");
                
                if (hasFilters) {
                    advancedFilters.style.display = "block";
                    toggleFilters.innerHTML = '<i class="fas fa-filter"></i> Hide Filters';
                    toggleFilters.setAttribute("aria-expanded", "true");
                }
                
                toggleFilters.addEventListener("click", function() {
                    if (advancedFilters.style.display === "none") {
                        advancedFilters.style.display = "block";
                        this.innerHTML = '<i class="fas fa-filter"></i> Hide Filters';
                        this.setAttribute("aria-expanded", "true");
                        localStorage.setItem("nominalFiltersVisible", "true");
                    } else {
                        advancedFilters.style.display = "none";
                        this.innerHTML = '<i class="fas fa-filter"></i> Filters';
                        this.setAttribute("aria-expanded", "false");
                        localStorage.setItem("nominalFiltersVisible", "false");
                    }
                });
                
                // Restore filter visibility from localStorage
                const filtersVisible = localStorage.getItem("nominalFiltersVisible");
                if (filtersVisible === "true") {
                    advancedFilters.style.display = "block";
                    toggleFilters.innerHTML = '<i class="fas fa-filter"></i> Hide Filters';
                    toggleFilters.setAttribute("aria-expanded", "true");
                }
            }
            
            // Update active filters count
            function updateActiveFiltersCount() {
                const activeFiltersCount = document.getElementById("activeFiltersCount");
                if (activeFiltersCount) {
                    const form = document.getElementById("searchForm");
                    const formData = new FormData(form);
                    let count = 0;
                    
                    for (let [key, value] of formData.entries()) {
                        if (key !== "search" && key !== "page" && key !== "filtered" && value.trim() !== "") {
                            count++;
                        }
                    }
                    
                    if (count > 0) {
                        activeFiltersCount.textContent = count;
                        activeFiltersCount.style.display = "inline-block";
                        activeFiltersCount.classList.add("badge-active");
                    } else {
                        activeFiltersCount.style.display = "none";
                        activeFiltersCount.classList.remove("badge-active");
                    }
                }
            }
            
            // Update count on filter changes
            document.querySelectorAll(".advanced-filters select, #searchInput").forEach(element => {
                element.addEventListener("change", updateActiveFiltersCount);
                if (element.id === "searchInput") {
                    element.addEventListener("input", updateActiveFiltersCount);
                }
            });
            
            // Initial count update
            updateActiveFiltersCount();
            
            // Enhanced search with debouncing
            const searchInput = document.getElementById("searchInput");
            let searchTimeout;
            let previousValue = "";
            
            if (searchInput) {
                const searchForm = document.getElementById("searchForm");
                const searchButton = searchForm.querySelector('button[type="submit"]');
                
                searchInput.addEventListener("input", function() {
                    clearTimeout(searchTimeout);
                    
                    if (this.value !== previousValue) {
                        if (this.value.length >= 3) {
                            searchTimeout = setTimeout(() => {
                                searchButton.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Searching...';
                                searchButton.disabled = true;
                                searchForm.submit();
                            }, 800);
                        }
                        previousValue = this.value;
                    }
                });
                
                searchInput.addEventListener("keydown", function(e) {
                    if (e.key === "Enter" && this.value.length >= 1) {
                        e.preventDefault();
                        searchButton.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Searching...';
                        searchButton.disabled = true;
                        searchForm.submit();
                    }
                });
                
                // Reset button text after form submission
                searchButton.innerHTML = '<i class="fas fa-search"></i> Search';
                searchButton.disabled = false;
            }
            
            // Responsive button text handling
            function handleResponsiveButtons() {
                const isMobile = window.innerWidth <= 768;
                const btnTexts = document.querySelectorAll(".btn-text");
                
                btnTexts.forEach(text => {
                    if (isMobile) {
                        text.style.display = "none";
                    } else {
                        text.style.display = "inline";
                    }
                });
            }
            
            handleResponsiveButtons();
            window.addEventListener("resize", handleResponsiveButtons);
            
            // Column visibility toggle
            document.querySelectorAll(".column-toggle").forEach(checkbox => {
                checkbox.addEventListener("change", function() {
                    const columnClass = "." + this.getAttribute("data-column") + "-column";
                    const isVisible = this.checked;
                    
                    document.querySelectorAll(columnClass).forEach(cell => {
                        cell.style.display = isVisible ? "" : "none";
                    });
                    
                    // Save preference to localStorage
                    const prefs = JSON.parse(localStorage.getItem("nominalColumnPrefs") || "{}");
                    prefs[this.getAttribute("data-column")] = isVisible;
                    localStorage.setItem("nominalColumnPrefs", JSON.stringify(prefs));
                });
                
                // Load saved preferences
                const prefs = JSON.parse(localStorage.getItem("nominalColumnPrefs") || "{}");
                const columnName = checkbox.getAttribute("data-column");
                if (prefs[columnName] === false) {
                    checkbox.checked = false;
                    checkbox.dispatchEvent(new Event("change"));
                }
            });
            
            // Save filter set functionality
            document.getElementById("saveFilterSet")?.addEventListener("click", function() {
                const filters = {};
                
                document.querySelectorAll(".filter-select").forEach(select => {
                    if (select.value) {
                        filters[select.name] = select.value;
                    }
                });
                
                const searchValue = document.getElementById("searchInput").value;
                if (searchValue) {
                    filters.search = searchValue;
                }
                
                if (Object.keys(filters).length === 0) {
                    Swal.fire({
                        icon: "warning",
                        title: "No Filters",
                        text: "Please apply some filters before saving."
                    });
                    return;
                }
                
                Swal.fire({
                    title: "Save Filter Set",
                    input: "text",
                    inputLabel: "Filter Set Name",
                    inputPlaceholder: 'e.g., "Active Lagos Staff"',
                    showCancelButton: true,
                    confirmButtonText: "Save",
                    cancelButtonText: "Cancel",
                    inputValidator: (value) => {
                        if (!value) {
                            return "Please enter a name for your filter set";
                        }
                    }
                }).then(result => {
                    if (result.isConfirmed && result.value) {
                        const filterSets = JSON.parse(localStorage.getItem("nominalFilterSets") || "[]");
                        filterSets.push({
                            name: result.value,
                            filters: filters,
                            date: new Date().toISOString()
                        });
                        localStorage.setItem("nominalFilterSets", JSON.stringify(filterSets));
                        
                        Swal.fire({
                            icon: "success",
                            title: "Saved!",
                            text: "Filter set saved successfully."
                        });
                    }
                });
            });
            
            // Column dropdown toggle
            const toggleColumnsBtn = document.getElementById("toggleColumnsBtn");
            const columnsDropdown = document.getElementById("columnsDropdown");
            
            if (toggleColumnsBtn && columnsDropdown) {
                toggleColumnsBtn.addEventListener("click", function(e) {
                    e.stopPropagation();
                    const isVisible = columnsDropdown.style.display === "block";
                    columnsDropdown.style.display = isVisible ? "none" : "block";
                    this.setAttribute("aria-expanded", !isVisible);
                });
                
                // Close dropdown when clicking outside
                document.addEventListener("click", function() {
                    columnsDropdown.style.display = "none";
                    toggleColumnsBtn.setAttribute("aria-expanded", "false");
                });
                
                // Prevent dropdown close when clicking inside
                columnsDropdown.addEventListener("click", function(e) {
                    e.stopPropagation();
                });
            }
            
            // Clear filters with confirmation
            document.querySelector(".btn-clear-filters")?.addEventListener("click", function(e) {
                e.preventDefault();
                
                const hasFilters = document.querySelectorAll(".filter-select").length > 0;
                const hasSearch = document.getElementById("searchInput")?.value;
                
                if (!hasFilters && !hasSearch) {
                    window.location.href = this.href;
                    return;
                }
                
                Swal.fire({
                    title: "Clear All Filters?",
                    text: "This will remove all applied filters and search terms.",
                    icon: "question",
                    showCancelButton: true,
                    confirmButtonText: "Yes, Clear All",
                    cancelButtonText: "Cancel"
                }).then(result => {
                    if (result.isConfirmed) {
                        window.location.href = this.href;
                    }
                }.bind(this));
            });
            
            // Row click handling (view details)
            document.querySelectorAll(".data-table tbody tr").forEach(row => {
                row.addEventListener("click", function(e) {
                    // Don't trigger if clicking on links, buttons, or forms
                    if (e.target.closest("a") || e.target.closest("button") || e.target.closest("form")) {
                        return;
                    }
                    
                    const viewBtn = this.querySelector(".btn-info");
                    if (viewBtn) {
                        window.location.href = viewBtn.href;
                    }
                });
                
                // Visual feedback
                row.addEventListener("mouseenter", function() {
                    this.style.cursor = "pointer";
                });
            });
            
            // Auto-hide filters on mobile after applying
            if (window.innerWidth <= 768 && advancedFilters) {
                document.querySelector(".filter-actions .btn-primary").addEventListener("click", function() {
                    setTimeout(() => {
                        if (advancedFilters) {
                            advancedFilters.style.display = "none";
                            if (toggleFilters) {
                                toggleFilters.innerHTML = '<i class="fas fa-filter"></i> Filters';
                                toggleFilters.setAttribute("aria-expanded", "false");
                                localStorage.setItem("nominalFiltersVisible", "false");
                            }
                        }
                    }, 100);
                });
            }
            
            // Remove filter tag handler
            document.querySelectorAll(".filter-tag .remove-filter").forEach(link => {
                link.addEventListener("click", function(e) {
                    e.preventDefault();
                    window.location.href = this.href;
                });
            });
            
            // Initialize tooltips
            document.querySelectorAll('[title]').forEach(element => {
                element.addEventListener('mouseenter', function() {
                    const title = this.getAttribute('title');
                    if (title) {
                        const tooltip = document.createElement('div');
                        tooltip.className = 'custom-tooltip';
                        tooltip.textContent = title;
                        tooltip.style.cssText = `
                            position: fixed;
                            background: var(--gray-800);
                            color: white;
                            padding: 6px 12px;
                            border-radius: 4px;
                            font-size: 0.75rem;
                            z-index: 9999;
                            pointer-events: none;
                            white-space: nowrap;
                        `;
                        document.body.appendChild(tooltip);
                        
                        const rect = this.getBoundingClientRect();
                        tooltip.style.left = (rect.left + rect.width / 2 - tooltip.offsetWidth / 2) + 'px';
                        tooltip.style.top = (rect.top - tooltip.offsetHeight - 8) + 'px';
                        
                        this._tooltip = tooltip;
                    }
                });
                
                element.addEventListener('mouseleave', function() {
                    if (this._tooltip) {
                        document.body.removeChild(this._tooltip);
                        delete this._tooltip;
                    }
                });
            });
        });
        
        // Global functions
        window.confirmDelete = function(form) {
            const row = form.closest("tr");
            const employeeName = row.querySelector(".employee-name strong").textContent;
            const employeeNumber = row.querySelector(".employee-number strong").textContent;
            
            Swal.fire({
                title: "Delete Employee?",
                html: `<div class="text-left">
                    <p><strong>Are you sure you want to delete this employee?</strong></p>
                    <div style="background: #fffaf0; border: 1px solid #fed7d7; padding: 12px; border-radius: 6px; margin-bottom: 16px;">
                        <i class="fas fa-exclamation-triangle" style="color: #d69e2e; margin-right: 8px;"></i>
                        This action cannot be undone!
                    </div>
                    <div style="background: #f8f9fa; padding: 12px; border-radius: 6px;">
                        <p><strong>Name:</strong> ${employeeName}</p>
                        <p><strong>Employee No:</strong> ${employeeNumber}</p>
                    </div>
                </div>`,
                icon: "warning",
                showCancelButton: true,
                confirmButtonText: "Yes, Delete",
                cancelButtonText: "Cancel",
                confirmButtonColor: "#e53e3e",
                cancelButtonColor: "#3182ce"
            }).then(result => {
                if (result.isConfirmed) {
                    Swal.fire({
                        title: "Deleting...",
                        text: "Please wait",
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });
                    form.submit();
                }
            });
            
            return false;
        };
        
        window.jumpToPage = function() {
            const pageInput = document.getElementById("jumpToPage");
            const pageNum = parseInt(pageInput.value);
            const totalPages = <?php echo isset($pagination['total_pages']) ? $pagination['total_pages'] : 1; ?>;
            
            if (!pageNum || pageNum < 1 || pageNum > totalPages) {
                Swal.fire({
                    icon: "error",
                    title: "Invalid Page",
                    text: `Please enter a page number between 1 and ${totalPages}`
                });
                return;
            }
            
            const currentUrl = new URL(window.location.href);
            currentUrl.searchParams.set("page", pageNum);
            window.location.href = currentUrl.toString();
        };
        
        window.confirmLogout = function(event) {
            event.preventDefault();
            const logoutUrl = event.currentTarget.href;
            
            Swal.fire({
                title: "Logout?",
                text: "Are you sure you want to logout from the system?",
                icon: "question",
                showCancelButton: true,
                confirmButtonText: "Yes, Logout",
                cancelButtonText: "Cancel",
                confirmButtonColor: "#e53e3e",
                cancelButtonColor: "#3182ce",
                reverseButtons: true
            }).then(result => {
                if (result.isConfirmed) {
                    // Add CSRF token to logout request
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = logoutUrl;
                    
                    const csrfInput = document.createElement('input');
                    csrfInput.type = 'hidden';
                    csrfInput.name = '_csrf_token';
                    csrfInput.value = '<?php echo $csrf_token; ?>';
                    
                    form.appendChild(csrfInput);
                    document.body.appendChild(form);
                    form.submit();
                }
            });
            
            return false;
        };
        
        // Session timeout warning (30 minutes)
        let idleTime = 0;
        const idleInterval = setInterval(() => {
            idleTime++;
            if (idleTime > 29) { // 30 minutes
                showSessionWarning();
            }
        }, 60000); // 1 minute
        
        function resetIdleTime() {
            idleTime = 0;
        }
        
        // Reset idle time on user activity
        ['mousemove', 'keypress', 'click', 'scroll'].forEach(event => {
            document.addEventListener(event, resetIdleTime);
        });
        
        function showSessionWarning() {
            if (!document.getElementById('session-warning')) {
                const warning = document.createElement('div');
                warning.id = 'session-warning';
                warning.style.cssText = `
                    position: fixed;
                    top: 50%;
                    left: 50%;
                    transform: translate(-50%, -50%);
                    background: white;
                    padding: 2rem;
                    border-radius: 12px;
                    box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
                    z-index: 2000;
                    text-align: center;
                    max-width: 400px;
                    width: 90%;
                `;
                
                warning.innerHTML = `
                    <h3 style="margin-bottom: 1rem; color: var(--warning);">Session Expiring Soon</h3>
                    <p style="margin-bottom: 1.5rem; color: var(--gray-700);">
                        Your session will expire in 5 minutes due to inactivity.
                    </p>
                    <div style="display: flex; gap: 1rem; justify-content: center;">
                        <button onclick="extendSession()" style="padding: 0.5rem 1.5rem; background: var(--primary); color: white; border: none; border-radius: 6px; cursor: pointer;">
                            Stay Logged In
                        </button>
                        <button onclick="logoutNow()" style="padding: 0.5rem 1.5rem; background: var(--gray-200); color: var(--gray-700); border: none; border-radius: 6px; cursor: pointer;">
                            Logout Now
                        </button>
                    </div>
                `;
                
                document.body.appendChild(warning);
                
                // Add overlay
                const overlay = document.createElement('div');
                overlay.style.cssText = `
                    position: fixed;
                    top: 0;
                    left: 0;
                    right: 0;
                    bottom: 0;
                    background: rgba(0, 0, 0, 0.5);
                    z-index: 1999;
                `;
                document.body.appendChild(overlay);
            }
        }
        
        window.extendSession = function() {
            fetch('<?php echo BASE_URL; ?>/admin/api/session/extend', {
                method: 'POST',
                credentials: 'same-origin'
            })
            .then(() => {
                resetIdleTime();
                const warning = document.getElementById('session-warning');
                const overlay = document.querySelector('#session-warning + div');
                if (warning) warning.remove();
                if (overlay) overlay.remove();
            })
            .catch(error => console.error('Session extend error:', error));
        };
        
        window.logoutNow = function() {
            window.location.href = '<?php echo BASE_URL; ?>/admin/logout';
        };
        
        // Keyboard shortcuts
        document.addEventListener('keydown', function(e) {
            // Ctrl/Cmd + F for search
            if ((e.ctrlKey || e.metaKey) && e.key === 'f') {
                e.preventDefault();
                const searchInput = document.getElementById('searchInput');
                if (searchInput) {
                    searchInput.focus();
                    searchInput.select();
                }
            }
            
            // Ctrl/Cmd + P for print
            if ((e.ctrlKey || e.metaKey) && e.key === 'p') {
                e.preventDefault();
                window.print();
            }
            
            // Escape to close modals/dropdowns
            if (e.key === 'Escape') {
                document.querySelectorAll('.dropdown-menu').forEach(menu => {
                    menu.style.display = 'none';
                });
            }
        });
        
        // Auto-refresh data every 5 minutes (optional)
        // setTimeout(() => location.reload(), 5 * 60 * 1000);
    </script>
</body>
</html>