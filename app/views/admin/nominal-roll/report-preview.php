<?php
// views/admin/nominal-roll/report-preview.php

// Check if report data exists
if (!isset($reportData)) {
    // Try to get from session
    if (isset($_SESSION['current_report'])) {
        $report = $_SESSION['current_report'];
        $reportData = $report['data'];
        $reportConfig = $report['config'];
        $fieldLabels = $report['field_labels'];
        $statistics = $report['statistics'];
    } else {
        // Redirect to reports page
        header('Location: /admin/nominal-roll/reports');
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Report Preview - Nominal Roll</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary-color: #3498db;
            --secondary-color: #2c3e50;
            --success-color: #27ae60;
            --warning-color: #f39c12;
            --danger-color: #e74c3c;
            --light-color: #f8f9fa;
            --dark-color: #343a40;
            --border-radius: 10px;
            --box-shadow: 0 5px 15px rgba(0,0,0,0.08);
            --transition: all 0.3s ease;
        }
        
        body {
            background-color: #f8fafc;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: #333;
        }
        
        .main-container {
            padding: 20px;
            max-width: 1400px;
            margin: 0 auto;
        }
        
        /* Header Styles */
        .page-header {
            background: white;
            border-radius: var(--border-radius);
            padding: 25px 30px;
            margin-bottom: 25px;
            box-shadow: var(--box-shadow);
            border-left: 4px solid var(--primary-color);
        }
        
        .page-header h1 {
            color: var(--secondary-color);
            font-weight: 600;
            margin-bottom: 5px;
            font-size: 26px;
        }
        
        .page-header .subtitle {
            color: #6c757d;
            font-size: 15px;
            margin-bottom: 0;
        }
        
        /* Action Bar */
        .action-bar {
            background: white;
            border-radius: var(--border-radius);
            padding: 20px;
            margin-bottom: 25px;
            box-shadow: var(--box-shadow);
            border-top: 3px solid var(--primary-color);
        }
        
        .action-bar h2 {
            font-size: 18px;
            font-weight: 600;
            color: var(--secondary-color);
            margin-bottom: 10px;
        }
        
        .action-bar .badge {
            font-size: 13px;
            font-weight: 500;
            padding: 6px 12px;
            border-radius: 6px;
        }
        
        /* Stats Cards */
        .stats-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }
        
        .stat-card {
            background: white;
            border-radius: var(--border-radius);
            padding: 25px;
            box-shadow: var(--box-shadow);
            transition: var(--transition);
            position: relative;
            overflow: hidden;
            border-top: 4px solid var(--primary-color);
        }
        
        .stat-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.12);
        }
        
        .stat-card:nth-child(2) { border-top-color: var(--success-color); }
        .stat-card:nth-child(3) { border-top-color: var(--warning-color); }
        .stat-card:nth-child(4) { border-top-color: var(--secondary-color); }
        
        .stat-icon {
            width: 60px;
            height: 60px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            color: white;
            margin-bottom: 20px;
            background: linear-gradient(135deg, var(--primary-color), #2980b9);
        }
        
        .stat-card:nth-child(2) .stat-icon {
            background: linear-gradient(135deg, var(--success-color), #229954);
        }
        
        .stat-card:nth-child(3) .stat-icon {
            background: linear-gradient(135deg, var(--warning-color), #d68910);
        }
        
        .stat-card:nth-child(4) .stat-icon {
            background: linear-gradient(135deg, var(--secondary-color), #1c2833);
        }
        
        .stat-card h3 {
            font-size: 32px;
            font-weight: 700;
            margin-bottom: 5px;
            color: var(--secondary-color);
        }
        
        .stat-card p {
            color: #6c757d;
            font-size: 15px;
            margin-bottom: 0;
            font-weight: 500;
        }
        
        .stat-card small {
            color: #95a5a6;
            font-size: 13px;
        }
        
        .progress {
            height: 8px;
            border-radius: 4px;
            background-color: #e9ecef;
            overflow: hidden;
            margin-top: 10px;
        }
        
        .progress-bar {
            background: linear-gradient(to right, var(--primary-color), #2980b9);
            transition: width 0.5s ease;
        }
        
        /* Report Table */
        .report-table-container {
            background: white;
            border-radius: var(--border-radius);
            padding: 25px;
            margin-bottom: 30px;
            box-shadow: var(--box-shadow);
        }
        
        .table-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 2px solid #f0f0f0;
        }
        
        .table-header h3 {
            font-size: 18px;
            font-weight: 600;
            color: var(--secondary-color);
            margin: 0;
        }
        
        .table-actions {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }
        
        /* Table Styles */
        .table-responsive {
            border-radius: 8px;
            overflow: hidden;
            border: 1px solid #e9ecef;
        }
        
        .table {
            margin-bottom: 0;
            font-size: 14px;
        }
        
        .table thead th {
            background-color: #f8f9fa;
            border-bottom: 2px solid #dee2e6;
            font-weight: 600;
            color: var(--secondary-color);
            padding: 15px 12px;
            white-space: nowrap;
        }
        
        .table tbody td {
            padding: 12px;
            vertical-align: middle;
            border-color: #e9ecef;
        }
        
        .table tbody tr:hover {
            background-color: #f8fafc;
        }
        
        .table .serial {
            width: 60px;
            text-align: center;
            color: #6c757d;
            font-weight: 500;
        }
        
        /* Badge styles */
        .badge {
            font-weight: 500;
            padding: 5px 10px;
            border-radius: 4px;
            font-size: 12px;
        }
        
        .badge-primary {
            background-color: rgba(52, 152, 219, 0.1);
            color: var(--primary-color);
        }
        
        .badge-success {
            background-color: rgba(39, 174, 96, 0.1);
            color: var(--success-color);
        }
        
        .badge-warning {
            background-color: rgba(243, 156, 18, 0.1);
            color: var(--warning-color);
        }
        
        .badge-danger {
            background-color: rgba(231, 76, 60, 0.1);
            color: var(--danger-color);
        }
        
        .badge-info {
            background-color: rgba(52, 152, 219, 0.1);
            color: var(--primary-color);
        }
        
        /* Configuration Panel */
        .config-panel {
            background: white;
            border-radius: var(--border-radius);
            padding: 25px;
            margin-bottom: 30px;
            box-shadow: var(--box-shadow);
        }
        
        .config-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 2px solid #f0f0f0;
        }
        
        .config-header h3 {
            font-size: 18px;
            font-weight: 600;
            color: var(--secondary-color);
            margin: 0;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .config-section {
            margin-bottom: 25px;
        }
        
        .config-section:last-child {
            margin-bottom: 0;
        }
        
        .config-section h4 {
            font-size: 16px;
            font-weight: 600;
            color: var(--secondary-color);
            margin-bottom: 15px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .field-tags {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
        }
        
        .field-tag {
            background: #f8f9fa;
            border: 1px solid #e9ecef;
            padding: 8px 15px;
            border-radius: 6px;
            font-size: 13px;
            color: #495057;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        
        .filter-list {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }
        
        .filter-item {
            background: #f8f9fa;
            border-radius: 6px;
            padding: 12px 15px;
            display: flex;
            align-items: center;
            gap: 12px;
        }
        
        .filter-icon {
            width: 32px;
            height: 32px;
            background: var(--primary-color);
            color: white;
            border-radius: 6px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 14px;
        }
        
        .filter-info h5 {
            font-size: 14px;
            font-weight: 600;
            color: var(--secondary-color);
            margin: 0 0 3px 0;
        }
        
        .filter-info p {
            font-size: 13px;
            color: #6c757d;
            margin: 0;
        }
        
        .no-filters {
            text-align: center;
            padding: 30px;
            color: #95a5a6;
        }
        
        .no-filters i {
            font-size: 48px;
            margin-bottom: 15px;
            opacity: 0.5;
        }
        
        /* Buttons */
        .btn {
            border-radius: 6px;
            font-weight: 500;
            font-size: 14px;
            padding: 10px 20px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            transition: var(--transition);
        }
        
        .btn-primary {
            background: linear-gradient(135deg, var(--primary-color), #2980b9);
            border: none;
            color: white;
        }
        
        .btn-primary:hover {
            background: linear-gradient(135deg, #2980b9, var(--primary-color));
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(52, 152, 219, 0.3);
        }
        
        .btn-outline-primary {
            border: 2px solid var(--primary-color);
            color: var(--primary-color);
            background: transparent;
        }
        
        .btn-outline-primary:hover {
            background: var(--primary-color);
            color: white;
        }
        
        .btn-light {
            background: white;
            border: 1px solid #e9ecef;
            color: #495057;
        }
        
        .btn-light:hover {
            background: #f8f9fa;
            border-color: #ced4da;
        }
        
        .btn-sm {
            padding: 6px 12px;
            font-size: 13px;
        }
        
        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 60px 20px;
        }
        
        .empty-state i {
            font-size: 60px;
            color: #95a5a6;
            margin-bottom: 20px;
            opacity: 0.6;
        }
        
        .empty-state h4 {
            font-size: 18px;
            font-weight: 600;
            color: var(--secondary-color);
            margin-bottom: 10px;
        }
        
        .empty-state p {
            color: #6c757d;
            font-size: 15px;
            margin-bottom: 25px;
        }
        
        /* Footer */
        .table-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 20px 0;
            border-top: 1px solid #e9ecef;
            margin-top: 20px;
        }
        
        /* Print Styles */
        @media print {
            body {
                background: white !important;
                color: black !important;
                font-size: 11px !important;
            }
            
            .main-container {
                max-width: 100% !important;
                padding: 0 !important;
            }
            
            .no-print {
                display: none !important;
            }
            
            .page-header,
            .action-bar,
            .stats-container,
            .config-panel,
            .table-header,
            .table-footer,
            .btn,
            .badge {
                display: none !important;
            }
            
            .report-table-container {
                padding: 0 !important;
                border: none !important;
                box-shadow: none !important;
            }
            
            .table-responsive {
                border: none !important;
            }
            
            .table {
                font-size: 9px !important;
            }
            
            .table th {
                background-color: #f1f1f1 !important;
                color: black !important;
                border: 1px solid #ddd !important;
                padding: 4px !important;
            }
            
            .table td {
                border: 1px solid #ddd !important;
                padding: 3px !important;
            }
            
            @page {
                margin: 0.5cm;
                size: A4 landscape;
            }
        }
        
        /* Custom Scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }
        
        ::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 4px;
        }
        
        ::-webkit-scrollbar-thumb {
            background: #c1c1c1;
            border-radius: 4px;
        }
        
        ::-webkit-scrollbar-thumb:hover {
            background: #a1a1a1;
        }
        
        /* Responsive Design */
        @media (max-width: 768px) {
            .main-container {
                padding: 15px;
            }
            
            .stats-container {
                grid-template-columns: 1fr;
            }
            
            .table-header {
                flex-direction: column;
                gap: 15px;
                text-align: center;
            }
            
            .table-actions {
                justify-content: center;
            }
            
            .table-responsive {
                font-size: 12px;
            }
            
            .field-tags {
                justify-content: center;
            }
            
            .config-header {
                flex-direction: column;
                gap: 15px;
                text-align: center;
            }
        }
        
        /* Animations */
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .stat-card, .report-table-container, .config-panel {
            animation: fadeIn 0.5s ease-out forwards;
        }
        
        /* Tooltip */
        .tooltip {
            font-size: 12px;
        }
        
        /* Loading Spinner */
        .spinner-border {
            width: 20px;
            height: 20px;
            border-width: 2px;
        }
    </style>
</head>
<body>
    <div class="main-container">
        <!-- Page Header -->
        <div class="page-header">
            <div class="d-flex justify-content-between align-items-start flex-wrap gap-3">
                <div>
                    <h1><i class="fas fa-file-alt text-primary me-2"></i> Report Preview</h1>
                    <p class="subtitle">Review and export your generated staff report</p>
                </div>
                <div class="d-flex align-items-center gap-2">
                    <a href="/admin/nominal-roll/reports" class="btn btn-outline-primary">
                        <i class="fas fa-edit me-2"></i> Modify Report
                    </a>
                    <a href="/admin/nominal-roll" class="btn btn-light">
                        <i class="fas fa-arrow-left me-2"></i> Back
                    </a>
                </div>
            </div>
        </div>

        <!-- Action Bar -->
        <div class="action-bar">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <h2>Report Summary</h2>
                    <div class="d-flex flex-wrap gap-2">
                        <span class="badge bg-primary">
                            <i class="fas fa-calendar me-1"></i>
                            <?= date('d M Y, h:i A') ?>
                        </span>
                        <span class="badge bg-success">
                            <i class="fas fa-users me-1"></i>
                            <?= count($reportData) ?> records
                        </span>
                        <span class="badge bg-info">
                            <i class="fas fa-columns me-1"></i>
                            <?= count($reportConfig['selected_fields']) ?> fields
                        </span>
                    </div>
                </div>
                <div class="col-md-6 mt-3 mt-md-0">
                    <div class="d-flex justify-content-end gap-2">
                        <div class="btn-group">
                            <button type="button" class="btn btn-primary">
                                <i class="fas fa-download me-2"></i> Export
                            </button>
                            <button type="button" class="btn btn-primary dropdown-toggle dropdown-toggle-split" 
                                    data-bs-toggle="dropdown">
                                <span class="visually-hidden">Toggle Dropdown</span>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li>
                                    <a class="dropdown-item" href="/admin/nominal-roll/export-excel" onclick="return confirmExport('Excel')">
                                        <i class="fas fa-file-excel text-success me-2"></i> Excel (.xlsx)
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="/admin/nominal-roll/export-csv" onclick="return confirmExport('CSV')">
                                        <i class="fas fa-file-csv text-primary me-2"></i> CSV (.csv)
                                    </a>
                                </li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <a class="dropdown-item" href="#" onclick="window.print()">
                                        <i class="fas fa-print text-secondary me-2"></i> Print
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <?php if (!empty($statistics) && isset($statistics['total_records'])): ?>
        <!-- Statistics -->
        <div class="stats-container">
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-users"></i>
                </div>
                <h3><?= number_format($statistics['total_records']) ?></h3>
                <p>Total Records</p>
                <small><?= count($reportData) ?> displayed</small>
            </div>
            
            <?php if (!empty($statistics['summary']['by_sex'])): ?>
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-venus-mars"></i>
                </div>
                <h3><?= count($statistics['summary']['by_sex']) ?></h3>
                <p>Gender Groups</p>
                <small>
                    <?php 
                    $genderCounts = [];
                    foreach ($statistics['summary']['by_sex'] as $gender => $count) {
                        $genderCounts[] = "$gender: $count";
                    }
                    echo implode(', ', $genderCounts);
                    ?>
                </small>
            </div>
            <?php endif; ?>
            
            <?php if (!empty($statistics['summary']['by_state'])): ?>
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-map-marker-alt"></i>
                </div>
                <h3><?= count($statistics['summary']['by_state']) ?></h3>
                <p>States Covered</p>
                <small>
                    <?php 
                    $stateCounts = [];
                    $i = 0;
                    foreach ($statistics['summary']['by_state'] as $state => $count) {
                        if ($i++ < 3) {
                            $stateCounts[] = "$state: $count";
                        }
                    }
                    echo implode(', ', $stateCounts);
                    if (count($statistics['summary']['by_state']) > 3) {
                        echo '...';
                    }
                    ?>
                </small>
            </div>
            <?php endif; ?>
            
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-info-circle"></i>
                </div>
                <h3><?= count($reportConfig['selected_fields']) ?></h3>
                <p>Fields Selected</p>
                <small>
                    <?php 
                    $sortLabels = [
                        'surname_asc' => 'A-Z by Surname',
                        'surname_desc' => 'Z-A by Surname',
                        'employee_number_asc' => 'Asc by Emp No.',
                        'employee_number_desc' => 'Desc by Emp No.',
                        'grade_level_asc' => 'Low-High Grade',
                        'grade_level_desc' => 'High-Low Grade',
                        'date_of_first_appointment_asc' => 'Old-New Appointments',
                        'date_of_first_appointment_desc' => 'New-Old Appointments'
                    ];
                    echo $sortLabels[$reportConfig['sort_order']] ?? 'Custom Sort';
                    ?>
                </small>
            </div>
        </div>
        <?php endif; ?>

        <!-- Report Table -->
        <div class="report-table-container">
            <div class="table-header">
                <h3><i class="fas fa-table me-2"></i> Report Data</h3>
                <div class="table-actions">
                    <button class="btn btn-sm btn-outline-primary" onclick="window.scrollTo(0, 0)">
                        <i class="fas fa-arrow-up me-1"></i> Top
                    </button>
                    <button class="btn btn-sm btn-light" onclick="exportExcel()">
                        <i class="fas fa-download me-1"></i> Export
                    </button>
                    <button class="btn btn-sm btn-light" onclick="toggleFilters()">
                        <i class="fas fa-filter me-1"></i> Filters
                    </button>
                </div>
            </div>
            
            <?php if (empty($reportData)): ?>
                <div class="empty-state">
                    <i class="fas fa-search"></i>
                    <h4>No Records Found</h4>
                    <p>No staff records match your current filters.</p>
                    <a href="/admin/nominal-roll/reports" class="btn btn-primary">
                        <i class="fas fa-edit me-2"></i> Modify Filters
                    </a>
                </div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th class="serial">#</th>
                                <?php foreach ($reportConfig['selected_fields'] as $field): ?>
                                    <?php $label = $fieldLabels[$field] ?? ucwords(str_replace('_', ' ', $field)); ?>
                                    <th><?= htmlspecialchars($label) ?></th>
                                <?php endforeach; ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($reportData as $index => $row): ?>
                            <tr>
                                <td class="serial"><?= $index + 1 ?></td>
                                <?php foreach ($reportConfig['selected_fields'] as $field): ?>
                                    <td>
                                        <?php
                                        $value = $row[$field] ?? '';
                                        
                                        // Format dates
                                        if (strpos($field, 'date') !== false && !empty($value)) {
                                            if ($value === '0000-00-00' || $value === '0000-00-00 00:00:00') {
                                                $value = '<span class="text-muted">-</span>';
                                            } else {
                                                $formattedDate = date('d/m/Y', strtotime($value));
                                                $value = '<span class="text-nowrap">' . $formattedDate . '</span>';
                                            }
                                        }
                                        
                                        // Format gender
                                        elseif ($field === 'sex') {
                                            if ($value === 'M') {
                                                $value = '<span class="badge badge-primary"><i class="fas fa-mars me-1"></i>Male</span>';
                                            } elseif ($value === 'F') {
                                                $value = '<span class="badge badge-danger"><i class="fas fa-venus me-1"></i>Female</span>';
                                            } else {
                                                $value = '<span class="text-muted">-</span>';
                                            }
                                        }
                                        
                                        // Format status
                                        elseif (strpos($field, 'status') !== false) {
                                            if (strtolower($value) === 'active') {
                                                $value = '<span class="badge badge-success">Active</span>';
                                            } elseif (strtolower($value) === 'inactive') {
                                                $value = '<span class="badge badge-danger">Inactive</span>';
                                            } elseif (strtolower($value) === 'pending') {
                                                $value = '<span class="badge badge-warning">Pending</span>';
                                            }
                                        }
                                        
                                        // Truncate long text
                                        elseif (strlen($value) > 50) {
                                            $truncated = htmlspecialchars(substr($value, 0, 50));
                                            $fullText = htmlspecialchars($value);
                                            $value = '<span class="text-truncate d-inline-block" style="max-width: 200px;" 
                                                      data-bs-toggle="tooltip" title="' . $fullText . '">' . $truncated . '...</span>';
                                        }
                                        
                                        // Default display
                                        else {
                                            $value = !empty($value) ? htmlspecialchars($value) : '<span class="text-muted">-</span>';
                                        }
                                        
                                        echo $value;
                                        ?>
                                    </td>
                                <?php endforeach; ?>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                
                <div class="table-footer">
                    <div class="text-muted">
                        Showing <strong><?= count($reportData) ?></strong> records
                    </div>
                    <div class="d-flex gap-2">
                        <button class="btn btn-sm btn-outline-primary" onclick="window.scrollTo(0, 0)">
                            <i class="fas fa-arrow-up me-1"></i> Back to Top
                        </button>
                        <button class="btn btn-sm btn-primary" onclick="exportExcel()">
                            <i class="fas fa-download me-1"></i> Export Now
                        </button>
                    </div>
                </div>
            <?php endif; ?>
        </div>

        <!-- Configuration Panel -->
        <div class="config-panel">
            <div class="config-header">
                <h3><i class="fas fa-cog text-primary"></i> Report Configuration</h3>
                <a href="/admin/nominal-roll/reports" class="btn btn-outline-primary">
                    <i class="fas fa-edit me-2"></i> Edit Configuration
                </a>
            </div>
            
            <div class="row">
                <div class="col-md-6">
                    <div class="config-section">
                        <h4><i class="fas fa-columns text-primary"></i> Selected Fields</h4>
                        <div class="field-tags">
                            <?php foreach ($reportConfig['selected_fields'] as $field): ?>
                                <?php $label = $fieldLabels[$field] ?? ucwords(str_replace('_', ' ', $field)); ?>
                                <span class="field-tag">
                                    <i class="fas fa-check-circle text-success me-1"></i>
                                    <?= htmlspecialchars($label) ?>
                                </span>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="config-section">
                        <h4><i class="fas fa-filter text-primary"></i> Active Filters</h4>
                        <?php 
                        $hasFilters = false;
                        $filtersApplied = 0;
                        
                        foreach ($reportConfig['filters'] as $filterKey => $filterValue): 
                            if (!empty($filterValue)): 
                                $hasFilters = true;
                                $filtersApplied++;
                                $filterLabel = str_replace(['filter_', '_'], ['', ' '], $filterKey);
                                $filterLabel = ucwords($filterLabel);
                        ?>
                        <div class="filter-list">
                            <div class="filter-item">
                                <div class="filter-icon">
                                    <i class="fas fa-check"></i>
                                </div>
                                <div class="filter-info">
                                    <h5><?= $filterLabel ?></h5>
                                    <p><?= htmlspecialchars($filterValue) ?></p>
                                </div>
                            </div>
                        </div>
                        <?php 
                            endif;
                        endforeach; 
                        
                        if (!$hasFilters): ?>
                        <div class="no-filters">
                            <i class="fas fa-sliders-h"></i>
                            <h5>No Filters Applied</h5>
                            <p class="text-muted">All records are included in this report</p>
                        </div>
                        <?php endif; ?>
                        
                        <?php if ($hasFilters): ?>
                        <div class="mt-3">
                            <small class="text-muted"><?= $filtersApplied ?> filter(s) applied</small>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            
            <div class="config-section mt-4">
                <h4><i class="fas fa-sort-amount-down text-primary"></i> Sorting</h4>
                <div class="d-flex align-items-center gap-3">
                    <div class="badge bg-primary">
                        <i class="fas fa-sort me-1"></i>
                        <?php 
                        $sortLabels = [
                            'surname_asc' => 'Surname (A to Z)',
                            'surname_desc' => 'Surname (Z to A)',
                            'employee_number_asc' => 'Employee Number (Ascending)',
                            'employee_number_desc' => 'Employee Number (Descending)',
                            'grade_level_asc' => 'Grade Level (Low to High)',
                            'grade_level_desc' => 'Grade Level (High to Low)',
                            'state_asc' => 'State (A to Z)',
                            'state_desc' => 'State (Z to A)',
                            'date_of_first_appointment_asc' => 'Appointment Date (Oldest First)',
                            'date_of_first_appointment_desc' => 'Appointment Date (Newest First)'
                        ];
                        echo $sortLabels[$reportConfig['sort_order']] ?? 'Custom Sort';
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap & JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Export confirmation function
        function confirmExport(format) {
            const recordCount = <?= count($reportData) ?>;
            if (recordCount > 1000) {
                return confirm(`This report contains ${recordCount.toLocaleString()} records. The ${format} export may take a few moments. Continue?`);
            }
            return true;
        }

        // Export functions
        function exportExcel() {
            window.location.href = '/admin/nominal-roll/export-excel';
        }

        function exportCSV() {
            window.location.href = '/admin/nominal-roll/export-csv';
        }

        // Toggle filters visibility
        function toggleFilters() {
            const configPanel = document.querySelector('.config-panel');
            if (configPanel.style.display === 'none') {
                configPanel.style.display = 'block';
            } else {
                configPanel.style.display = 'none';
            }
        }

        // Initialize on page load
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize tooltips
            const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            const tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });

            // Add smooth scroll to top
            const scrollTopBtns = document.querySelectorAll('[onclick*="scrollTo"]');
            scrollTopBtns.forEach(btn => {
                btn.addEventListener('click', function(e) {
                    e.preventDefault();
                    window.scrollTo({
                        top: 0,
                        behavior: 'smooth'
                    });
                });
            });

            // Export confirmation for all export links
            document.querySelectorAll('a[href*="export"]').forEach(link => {
                link.addEventListener('click', function(e) {
                    if (<?= count($reportData) ?> > 1000) {
                        e.preventDefault();
                        const format = this.textContent.includes('Excel') ? 'Excel' : 'CSV';
                        const url = this.href;
                        
                        if (confirm(`This report contains ${<?= count($reportData) ?>.toLocaleString()} records. The ${format} export may take a few moments. Continue?`)) {
                            window.location.href = url;
                        }
                    }
                });
            });

            // Add row highlighting
            const tableRows = document.querySelectorAll('tbody tr');
            tableRows.forEach(row => {
                row.addEventListener('mouseenter', function() {
                    this.style.backgroundColor = '#f8fafc';
                });
                row.addEventListener('mouseleave', function() {
                    this.style.backgroundColor = '';
                });
            });
        });

        // Keyboard shortcuts
        document.addEventListener('keydown', function(e) {
            // Ctrl + P for print
            if ((e.ctrlKey || e.metaKey) && e.key === 'p') {
                e.preventDefault();
                window.print();
            }
            
            // Ctrl + E for export (Excel)
            if ((e.ctrlKey || e.metaKey) && e.key === 'e') {
                e.preventDefault();
                exportExcel();
            }
            
            // Escape to go back
            if (e.key === 'Escape') {
                window.location.href = '/admin/nominal-roll/reports';
            }
            
            // Space to scroll to top
            if (e.key === ' ' && !e.target.matches('input, textarea, select')) {
                e.preventDefault();
                window.scrollTo({
                    top: 0,
                    behavior: 'smooth'
                });
            }
        });

        // Responsive table adjustments
        function adjustTableForMobile() {
            if (window.innerWidth < 768) {
                document.querySelectorAll('.table-responsive').forEach(table => {
                    table.style.maxHeight = '400px';
                    table.style.overflowY = 'auto';
                });
            } else {
                document.querySelectorAll('.table-responsive').forEach(table => {
                    table.style.maxHeight = '';
                    table.style.overflowY = '';
                });
            }
        }

        window.addEventListener('resize', adjustTableForMobile);
        window.addEventListener('load', adjustTableForMobile);
    </script>
</body>
</html>