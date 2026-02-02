<?php
/**
 * License Summary Report View
 * Shows comprehensive statistics about professional licenses
 */
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>License Summary Report | <?php echo SITE_NAME; ?></title>
    
    <!-- CSS -->
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/assets/css/fontawesome.all.min.css">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/assets/css/admin.css">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/assets/css/chart.min.css">
    
    <style>
        .summary-card {
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 20px;
            color: white;
            transition: all 0.3s ease;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }
        
        .summary-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 15px rgba(0,0,0,0.2);
        }
        
        .summary-icon {
            font-size: 2.5rem;
            opacity: 0.8;
            margin-bottom: 10px;
        }
        
        .summary-number {
            font-size: 2.2rem;
            font-weight: bold;
            margin-bottom: 5px;
        }
        
        .summary-label {
            font-size: 0.9rem;
            opacity: 0.9;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        
        .summary-percentage {
            font-size: 0.85rem;
            background: rgba(255,255,255,0.2);
            padding: 2px 8px;
            border-radius: 20px;
            margin-left: 5px;
        }
        
        .chart-container {
            position: relative;
            height: 300px;
            margin-bottom: 20px;
        }
        
        .progress-container {
            margin-bottom: 15px;
        }
        
        .progress-label {
            display: flex;
            justify-content: space-between;
            margin-bottom: 5px;
            font-weight: 500;
        }
        
        .progress {
            height: 20px;
            border-radius: 10px;
        }
        
        .progress-bar {
            border-radius: 10px;
        }
        
        .comparison-card {
            background: white;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        
        .comparison-title {
            color: #2c3e50;
            font-weight: 600;
            margin-bottom: 15px;
            padding-bottom: 10px;
            border-bottom: 2px solid #f8f9fa;
        }
        
        .comparison-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px 0;
            border-bottom: 1px solid #f8f9fa;
        }
        
        .comparison-item:last-child {
            border-bottom: none;
        }
        
        .comparison-label {
            color: #34495e;
        }
        
        .comparison-value {
            font-weight: 600;
            color: #2c3e50;
        }
        
        .department-list {
            max-height: 400px;
            overflow-y: auto;
        }
        
        .department-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 8px 0;
            border-bottom: 1px solid #f8f9fa;
        }
        
        .department-name {
            flex: 1;
        }
        
        .department-count {
            background: #e9ecef;
            padding: 2px 8px;
            border-radius: 10px;
            font-size: 0.85rem;
            font-weight: 600;
        }
        
        .license-type-tag {
            display: inline-block;
            padding: 2px 8px;
            border-radius: 4px;
            font-size: 0.75rem;
            font-weight: 600;
            margin-right: 5px;
        }
        
        .nmcn-tag {
            background-color: #3498db;
            color: white;
        }
        
        .trcn-tag {
            background-color: #9b59b6;
            color: white;
        }
        
        .status-badge {
            padding: 3px 8px;
            border-radius: 4px;
            font-size: 0.75rem;
            font-weight: 600;
        }
        
        .status-active {
            background-color: #d4edda;
            color: #155724;
        }
        
        .status-expired {
            background-color: #f8d7da;
            color: #721c24;
        }
        
        .status-pending {
            background-color: #fff3cd;
            color: #856404;
        }
        
        .trend-up {
            color: #28a745;
        }
        
        .trend-down {
            color: #dc3545;
        }
        
        .trend-neutral {
            color: #6c757d;
        }
        
        .report-section {
            margin-bottom: 30px;
            background: white;
            border-radius: 10px;
            padding: 25px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        
        .section-title {
            color: #2c3e50;
            font-weight: 600;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 3px solid #f8f9fa;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        
        .section-title i {
            color: #3498db;
            margin-right: 10px;
        }
        
        .highlight-card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-radius: 10px;
            padding: 25px;
            margin-bottom: 20px;
        }
        
        .highlight-title {
            font-size: 1.2rem;
            margin-bottom: 15px;
            opacity: 0.9;
        }
        
        .highlight-number {
            font-size: 2.5rem;
            font-weight: bold;
            margin-bottom: 10px;
        }
        
        .highlight-subtitle {
            font-size: 0.9rem;
            opacity: 0.8;
        }
        
        @media print {
            .no-print {
                display: none !important;
            }
            
            .summary-card {
                break-inside: avoid;
            }
            
            .report-section {
                border: 1px solid #ddd !important;
                box-shadow: none !important;
            }
            
            .chart-container canvas {
                max-width: 100% !important;
            }
        }
    </style>
</head>
<body class="hold-transition sidebar-mini layout-fixed">
    <div class="wrapper">
        <!-- Header -->
        <?php include VIEW_PATH . 'admin/includes/header.php'; ?>
        
        <!-- Sidebar -->
        <?php include VIEW_PATH . 'admin/includes/sidebar.php'; ?>

        <!-- Content Wrapper -->
        <div class="content-wrapper">
            <!-- Content Header -->
            <div class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1 class="m-0">
                                <i class="fas fa-chart-pie"></i> 
                                Professional Licenses Summary Report
                            </h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="<?php echo BASE_URL; ?>/admin">Dashboard</a></li>
                                <li class="breadcrumb-item"><a href="<?php echo BASE_URL; ?>/admin/nominal-roll">Nominal Roll</a></li>
                                <li class="breadcrumb-item active">License Summary</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Main Content -->
            <section class="content">
                <div class="container-fluid">
                    <?php include VIEW_PATH . 'admin/includes/flash-messages.php'; ?>
                    
                    <!-- Quick Stats Row -->
                    <div class="row">
                        <div class="col-lg-3 col-md-6">
                            <div class="summary-card" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                                <div class="summary-icon">
                                    <i class="fas fa-users"></i>
                                </div>
                                <div class="summary-number">
                                    <?php echo $summary['totalEmployees']; ?>
                                </div>
                                <div class="summary-label">
                                    Total Employees
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-lg-3 col-md-6">
                            <div class="summary-card" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);">
                                <div class="summary-icon">
                                    <i class="fas fa-id-card"></i>
                                </div>
                                <div class="summary-number">
                                    <?php echo $summary['withNMCN'] + $summary['withTRCN'] - $summary['withBoth']; ?>
                                    <span class="summary-percentage">
                                        <?php echo round((($summary['withNMCN'] + $summary['withTRCN'] - $summary['withBoth']) / $summary['totalEmployees']) * 100, 1); ?>%
                                    </span>
                                </div>
                                <div class="summary-label">
                                    Employees with Licenses
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-lg-3 col-md-6">
                            <div class="summary-card" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);">
                                <div class="summary-icon">
                                    <i class="fas fa-handshake"></i>
                                </div>
                                <div class="summary-number">
                                    <?php echo $summary['withBoth']; ?>
                                    <span class="summary-percentage">
                                        <?php echo $summary['totalEmployees'] > 0 ? round(($summary['withBoth'] / $summary['totalEmployees']) * 100, 1) : 0; ?>%
                                    </span>
                                </div>
                                <div class="summary-label">
                                    Both NMCN & TRCN
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-lg-3 col-md-6">
                            <div class="summary-card" style="background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);">
                                <div class="summary-icon">
                                    <i class="fas fa-exclamation-triangle"></i>
                                </div>
                                <div class="summary-number">
                                    <?php echo $summary['expiringSoon']; ?>
                                    <span class="summary-percentage">
                                        <?php echo $summary['totalEmployees'] > 0 ? round(($summary['expiringSoon'] / $summary['totalEmployees']) * 100, 1) : 0; ?>%
                                    </span>
                                </div>
                                <div class="summary-label">
                                    Expiring in 30 Days
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="row no-print">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title">Report Actions</h3>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <a href="<?php echo BASE_URL; ?>/admin/nominal-roll" class="btn btn-secondary">
                                                <i class="fas fa-arrow-left"></i> Back to Nominal Roll
                                            </a>
                                            
                                            <div class="btn-group ml-2">
                                                <button type="button" class="btn btn-info dropdown-toggle" data-toggle="dropdown">
                                                    <i class="fas fa-exchange-alt"></i> Switch Report
                                                </button>
                                                <div class="dropdown-menu">
                                                    <a class="dropdown-item" href="<?php echo BASE_URL; ?>/admin/nominal-roll/expiring-licenses">
                                                        <i class="fas fa-clock text-warning"></i> Expiring Soon (30 days)
                                                    </a>
                                                    <a class="dropdown-item" href="<?php echo BASE_URL; ?>/admin/nominal-roll/expired-licenses">
                                                        <i class="fas fa-exclamation-triangle text-danger"></i> Expired Licenses
                                                    </a>
                                                    <div class="dropdown-divider"></div>
                                                    <a class="dropdown-item" href="<?php echo BASE_URL; ?>/admin/nominal-roll/license-summary">
                                                        <i class="fas fa-chart-pie text-info"></i> License Summary
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="col-md-6 text-right">
                                            <button onclick="window.print()" class="btn btn-default">
                                                <i class="fas fa-print"></i> Print Report
                                            </button>
                                            
                                            <div class="btn-group ml-2">
                                                <button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown">
                                                    <i class="fas fa-download"></i> Export Report
                                                </button>
                                                <div class="dropdown-menu dropdown-menu-right">
                                                    <a class="dropdown-item" href="#" onclick="exportToPDF()">
                                                        <i class="fas fa-file-pdf text-danger"></i> Export to PDF
                                                    </a>
                                                    <a class="dropdown-item" href="#" onclick="exportToExcel()">
                                                        <i class="fas fa-file-excel text-success"></i> Export to Excel
                                                    </a>
                                                    <a class="dropdown-item" href="#" onclick="takeScreenshot()">
                                                        <i class="fas fa-image text-primary"></i> Save as Image
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- Report Period -->
                                    <div class="row mt-3">
                                        <div class="col-md-12">
                                            <div class="alert alert-info">
                                                <i class="fas fa-info-circle"></i> 
                                                Report Period: All time (as of <?php echo date('F d, Y H:i:s'); ?>)
                                                | Generated by: <?php echo $_SESSION['user']['username'] ?? 'System'; ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Main Report Content -->
                    <div class="row">
                        <!-- Left Column: Charts -->
                        <div class="col-lg-8">
                            <!-- License Distribution Chart -->
                            <div class="report-section">
                                <div class="section-title">
                                    <span><i class="fas fa-chart-pie"></i> License Distribution</span>
                                    <span class="badge badge-primary">Overview</span>
                                </div>
                                
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="chart-container">
                                            <canvas id="licenseDistributionChart"></canvas>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="chart-container">
                                            <canvas id="licenseStatusChart"></canvas>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="row mt-3">
                                    <div class="col-md-12">
                                        <div class="comparison-card">
                                            <div class="comparison-title">License Coverage Analysis</div>
                                            
                                            <div class="comparison-item">
                                                <span class="comparison-label">Employees with at least one license:</span>
                                                <span class="comparison-value">
                                                    <?php 
                                                    $withLicenses = $summary['withNMCN'] + $summary['withTRCN'] - $summary['withBoth'];
                                                    echo $withLicenses . ' (' . round(($withLicenses / $summary['totalEmployees']) * 100, 1) . '%)';
                                                    ?>
                                                </span>
                                            </div>
                                            
                                            <div class="comparison-item">
                                                <span class="comparison-label">Employees with both NMCN & TRCN:</span>
                                                <span class="comparison-value">
                                                    <?php echo $summary['withBoth'] . ' (' . round(($summary['withBoth'] / $summary['totalEmployees']) * 100, 1) . '%)'; ?>
                                                </span>
                                            </div>
                                            
                                            <div class="comparison-item">
                                                <span class="comparison-label">Employees without any license:</span>
                                                <span class="comparison-value">
                                                    <?php 
                                                    $withoutLicenses = $summary['totalEmployees'] - $withLicenses;
                                                    echo $withoutLicenses . ' (' . round(($withoutLicenses / $summary['totalEmployees']) * 100, 1) . '%)';
                                                    ?>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- License Status Analysis -->
                            <div class="report-section">
                                <div class="section-title">
                                    <span><i class="fas fa-chart-bar"></i> License Status Analysis</span>
                                    <span class="badge badge-info">Detailed View</span>
                                </div>
                                
                                <div class="row">
                                    <!-- NMCN Status -->
                                    <div class="col-md-6">
                                        <div class="comparison-card">
                                            <div class="comparison-title">
                                                <span class="license-type-tag nmcn-tag">NMCN</span> License Status
                                            </div>
                                            
                                            <div class="progress-container">
                                                <div class="progress-label">
                                                    <span>Active</span>
                                                    <span><?php echo $summary['nmcnActive']; ?> licenses</span>
                                                </div>
                                                <div class="progress">
                                                    <div class="progress-bar bg-success" 
                                                         style="width: <?php echo $summary['withNMCN'] > 0 ? ($summary['nmcnActive'] / $summary['withNMCN']) * 100 : 0; ?>%">
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            <div class="progress-container">
                                                <div class="progress-label">
                                                    <span>Expired</span>
                                                    <span><?php echo $summary['nmcnExpired']; ?> licenses</span>
                                                </div>
                                                <div class="progress">
                                                    <div class="progress-bar bg-danger" 
                                                         style="width: <?php echo $summary['withNMCN'] > 0 ? ($summary['nmcnExpired'] / $summary['withNMCN']) * 100 : 0; ?>%">
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            <div class="progress-container">
                                                <div class="progress-label">
                                                    <span>Pending</span>
                                                    <span><?php echo $summary['nmcnPending']; ?> licenses</span>
                                                </div>
                                                <div class="progress">
                                                    <div class="progress-bar bg-warning" 
                                                         style="width: <?php echo $summary['withNMCN'] > 0 ? ($summary['nmcnPending'] / $summary['withNMCN']) * 100 : 0; ?>%">
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            <div class="mt-3">
                                                <small class="text-muted">
                                                    <i class="fas fa-info-circle"></i> 
                                                    Total NMCN Licenses: <?php echo $summary['withNMCN']; ?>
                                                </small>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- TRCN Status -->
                                    <div class="col-md-6">
                                        <div class="comparison-card">
                                            <div class="comparison-title">
                                                <span class="license-type-tag trcn-tag">TRCN</span> License Status
                                            </div>
                                            
                                            <div class="progress-container">
                                                <div class="progress-label">
                                                    <span>Active</span>
                                                    <span><?php echo $summary['trcnActive']; ?> licenses</span>
                                                </div>
                                                <div class="progress">
                                                    <div class="progress-bar bg-success" 
                                                         style="width: <?php echo $summary['withTRCN'] > 0 ? ($summary['trcnActive'] / $summary['withTRCN']) * 100 : 0; ?>%">
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            <div class="progress-container">
                                                <div class="progress-label">
                                                    <span>Expired</span>
                                                    <span><?php echo $summary['trcnExpired']; ?> licenses</span>
                                                </div>
                                                <div class="progress">
                                                    <div class="progress-bar bg-danger" 
                                                         style="width: <?php echo $summary['withTRCN'] > 0 ? ($summary['trcnExpired'] / $summary['withTRCN']) * 100 : 0; ?>%">
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            <div class="progress-container">
                                                <div class="progress-label">
                                                    <span>Pending</span>
                                                    <span><?php echo $summary['trcnPending']; ?> licenses</span>
                                                </div>
                                                <div class="progress">
                                                    <div class="progress-bar bg-warning" 
                                                         style="width: <?php echo $summary['withTRCN'] > 0 ? ($summary['trcnPending'] / $summary['withTRCN']) * 100 : 0; ?>%">
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            <div class="mt-3">
                                                <small class="text-muted">
                                                    <i class="fas fa-info-circle"></i> 
                                                    Total TRCN Licenses: <?php echo $summary['withTRCN']; ?>
                                                </small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Compliance Score -->
                                <div class="row mt-3">
                                    <div class="col-md-12">
                                        <div class="highlight-card">
                                            <div class="highlight-title">Overall License Compliance Score</div>
                                            <div class="highlight-number">
                                                <?php 
                                                $totalLicenses = $summary['withNMCN'] + $summary['withTRCN'];
                                                $activeLicenses = $summary['nmcnActive'] + $summary['trcnActive'];
                                                $complianceScore = $totalLicenses > 0 ? ($activeLicenses / $totalLicenses) * 100 : 0;
                                                echo round($complianceScore, 1); 
                                                ?>%
                                            </div>
                                            <div class="highlight-subtitle">
                                                Percentage of licenses that are currently active and valid
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Right Column: Key Metrics -->
                        <div class="col-lg-4">
                            <!-- License Types Overview -->
                            <div class="report-section">
                                <div class="section-title">
                                    <span><i class="fas fa-id-card"></i> License Types</span>
                                </div>
                                
                                <div class="comparison-card">
                                    <div class="comparison-item">
                                        <span class="comparison-label">
                                            <span class="license-type-tag nmcn-tag">NMCN</span> Licenses
                                        </span>
                                        <span class="comparison-value">
                                            <?php echo $summary['withNMCN']; ?>
                                            <small class="text-muted">
                                                (<?php echo round(($summary['withNMCN'] / $summary['totalEmployees']) * 100, 1); ?>%)
                                            </small>
                                        </span>
                                    </div>
                                    
                                    <div class="comparison-item">
                                        <span class="comparison-label">
                                            <span class="license-type-tag trcn-tag">TRCN</span> Licenses
                                        </span>
                                        <span class="comparison-value">
                                            <?php echo $summary['withTRCN']; ?>
                                            <small class="text-muted">
                                                (<?php echo round(($summary['withTRCN'] / $summary['totalEmployees']) * 100, 1); ?>%)
                                            </small>
                                        </span>
                                    </div>
                                    
                                    <div class="comparison-item">
                                        <span class="comparison-label">
                                            <i class="fas fa-handshake text-primary"></i> Both Licenses
                                        </span>
                                        <span class="comparison-value">
                                            <?php echo $summary['withBoth']; ?>
                                            <small class="text-muted">
                                                (<?php echo round(($summary['withBoth'] / $summary['totalEmployees']) * 100, 1); ?>%)
                                            </small>
                                        </span>
                                    </div>
                                    
                                    <div class="comparison-item">
                                        <span class="comparison-label">
                                            <i class="fas fa-times-circle text-danger"></i> No License
                                        </span>
                                        <span class="comparison-value">
                                            <?php echo $summary['withoutLicenses']; ?>
                                            <small class="text-muted">
                                                (<?php echo round(($summary['withoutLicenses'] / $summary['totalEmployees']) * 100, 1); ?>%)
                                            </small>
                                        </span>
                                    </div>
                                </div>
                                
                                <!-- Key Insights -->
                                <div class="mt-4">
                                    <h6><i class="fas fa-lightbulb text-warning"></i> Key Insights</h6>
                                    <ul class="list-unstyled mt-2">
                                        <li class="mb-2">
                                            <small>
                                                <i class="fas fa-check-circle text-success"></i>
                                                <?php echo round(($summary['nmcnActive'] / max(1, $summary['withNMCN'])) * 100, 1); ?>% of NMCN licenses are active
                                            </small>
                                        </li>
                                        <li class="mb-2">
                                            <small>
                                                <i class="fas fa-check-circle text-success"></i>
                                                <?php echo round(($summary['trcnActive'] / max(1, $summary['withTRCN'])) * 100, 1); ?>% of TRCN licenses are active
                                            </small>
                                        </li>
                                        <li class="mb-2">
                                            <small>
                                                <i class="fas fa-exclamation-triangle text-warning"></i>
                                                <?php echo $summary['expiringSoon']; ?> licenses expiring within 30 days
                                            </small>
                                        </li>
                                        <li>
                                            <small>
                                                <i class="fas fa-chart-line text-info"></i>
                                                Compliance score: <?php echo round($complianceScore ?? 0, 1); ?>%
                                            </small>
                                        </li>
                                    </ul>
                                </div>
                            </div>

                            <!-- Expiry Timeline -->
                            <div class="report-section">
                                <div class="section-title">
                                    <span><i class="fas fa-clock"></i> Expiry Timeline</span>
                                </div>
                                
                                <div class="comparison-card">
                                    <div class="comparison-item">
                                        <span class="comparison-label">
                                            <span class="status-badge status-active">Active</span>
                                        </span>
                                        <span class="comparison-value">
                                            <?php echo ($summary['nmcnActive'] + $summary['trcnActive']); ?>
                                        </span>
                                    </div>
                                    
                                    <div class="comparison-item">
                                        <span class="comparison-label">
                                            <span class="status-badge status-expired">Expired</span>
                                        </span>
                                        <span class="comparison-value">
                                            <?php echo ($summary['nmcnExpired'] + $summary['trcnExpired']); ?>
                                        </span>
                                    </div>
                                    
                                    <div class="comparison-item">
                                        <span class="comparison-label">
                                            <span class="status-badge status-pending">Pending</span>
                                        </span>
                                        <span class="comparison-value">
                                            <?php echo ($summary['nmcnPending'] + $summary['trcnPending']); ?>
                                        </span>
                                    </div>
                                    
                                    <div class="comparison-item">
                                        <span class="comparison-label">
                                            <i class="fas fa-hourglass-half text-warning"></i> Expiring Soon
                                        </span>
                                        <span class="comparison-value">
                                            <?php echo $summary['expiringSoon']; ?>
                                            <small class="trend-up">
                                                <i class="fas fa-exclamation-circle"></i>
                                            </small>
                                        </span>
                                    </div>
                                </div>
                                
                                <!-- Recommendations -->
                                <div class="mt-4">
                                    <h6><i class="fas fa-tasks text-primary"></i> Recommended Actions</h6>
                                    <div class="alert alert-warning mt-2">
                                        <small>
                                            <i class="fas fa-bell"></i>
                                            <strong>Attention needed:</strong> 
                                            <?php echo $summary['expiringSoon']; ?> licenses require renewal within 30 days
                                        </small>
                                    </div>
                                    <div class="alert alert-info mt-2">
                                        <small>
                                            <i class="fas fa-user-check"></i>
                                            <strong>Follow up:</strong> 
                                            <?php echo ($summary['nmcnPending'] + $summary['trcnPending']); ?> pending applications need review
                                        </small>
                                    </div>
                                </div>
                            </div>

                            <!-- Report Meta -->
                            <div class="report-section">
                                <div class="section-title">
                                    <span><i class="fas fa-info-circle"></i> Report Information</span>
                                </div>
                                
                                <div class="comparison-card">
                                    <div class="comparison-item">
                                        <span class="comparison-label">
                                            <i class="fas fa-calendar-alt"></i> Report Date
                                        </span>
                                        <span class="comparison-value">
                                            <?php echo date('F d, Y'); ?>
                                        </span>
                                    </div>
                                    
                                    <div class="comparison-item">
                                        <span class="comparison-label">
                                            <i class="fas fa-clock"></i> Generated
                                        </span>
                                        <span class="comparison-value">
                                            <?php echo date('H:i:s'); ?>
                                        </span>
                                    </div>
                                    
                                    <div class="comparison-item">
                                        <span class="comparison-label">
                                            <i class="fas fa-database"></i> Data Source
                                        </span>
                                        <span class="comparison-value">
                                            Nominal Roll DB
                                        </span>
                                    </div>
                                    
                                    <div class="comparison-item">
                                        <span class="comparison-label">
                                            <i class="fas fa-user"></i> Generated By
                                        </span>
                                        <span class="comparison-value">
                                            <?php echo $_SESSION['user']['username'] ?? 'System'; ?>
                                        </span>
                                    </div>
                                    
                                    <div class="comparison-item">
                                        <span class="comparison-label">
                                            <i class="fas fa-sync-alt"></i> Next Update
                                        </span>
                                        <span class="comparison-value">
                                            <?php echo date('F d, Y', strtotime('+1 day')); ?>
                                        </span>
                                    </div>
                                </div>
                                
                                <div class="mt-3 text-center">
                                    <button class="btn btn-sm btn-outline-primary" onclick="refreshReport()">
                                        <i class="fas fa-sync-alt"></i> Refresh Report
                                    </button>
                                    <small class="d-block mt-2 text-muted">
                                        Last updated: <?php echo date('H:i:s'); ?>
                                    </small>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Footer Notes -->
                    <div class="row no-print">
                        <div class="col-md-12">
                            <div class="card mt-3">
                                <div class="card-body text-center">
                                    <small class="text-muted">
                                        <i class="fas fa-shield-alt"></i> 
                                        This report contains sensitive employee information. Handle with confidentiality.
                                        <br>
                                        <i class="fas fa-copyright"></i> 
                                        <?php echo date('Y'); ?> <?php echo SITE_NAME; ?> - Professional Licenses Management System
                                    </small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>

        <!-- Footer -->
        <?php include VIEW_PATH . 'admin/includes/footer.php'; ?>
    </div>

    <!-- JavaScript -->
    <script src="<?php echo BASE_URL; ?>/assets/js/jquery.min.js"></script>
    <script src="<?php echo BASE_URL; ?>/assets/js/bootstrap.bundle.min.js"></script>
    <script src="<?php echo BASE_URL; ?>/assets/js/chart.min.js"></script>
    <script src="<?php echo BASE_URL; ?>/assets/js/html2canvas.min.js"></script>
    <script src="<?php echo BASE_URL; ?>/assets/js/jspdf.min.js"></script>
    <script src="<?php echo BASE_URL; ?>/assets/js/xlsx.full.min.js"></script>
    <script src="<?php echo BASE_URL; ?>/assets/js/sweetalert2.all.min.js"></script>
    
    <script>
        // Initialize Charts
        $(document).ready(function() {
            // License Distribution Chart (Doughnut)
            const licenseCtx = document.getElementById('licenseDistributionChart').getContext('2d');
            const licenseChart = new Chart(licenseCtx, {
                type: 'doughnut',
                data: {
                    labels: ['NMCN Only', 'TRCN Only', 'Both', 'None'],
                    datasets: [{
                        data: [
                            <?php echo $summary['withNMCN'] - $summary['withBoth']; ?>,
                            <?php echo $summary['withTRCN'] - $summary['withBoth']; ?>,
                            <?php echo $summary['withBoth']; ?>,
                            <?php echo $summary['withoutLicenses']; ?>
                        ],
                        backgroundColor: [
                            '#3498db', // NMCN Only - Blue
                            '#9b59b6', // TRCN Only - Purple
                            '#2ecc71', // Both - Green
                            '#e74c3c'  // None - Red
                        ],
                        borderWidth: 2,
                        borderColor: '#fff'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                padding: 20,
                                usePointStyle: true,
                                font: {
                                    size: 11
                                }
                            }
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    let label = context.label || '';
                                    let value = context.raw || 0;
                                    let total = context.dataset.data.reduce((a, b) => a + b, 0);
                                    let percentage = Math.round((value / total) * 100);
                                    return `${label}: ${value} (${percentage}%)`;
                                }
                            }
                        }
                    },
                    cutout: '60%'
                }
            });
            
            // License Status Chart (Bar)
            const statusCtx = document.getElementById('licenseStatusChart').getContext('2d');
            const statusChart = new Chart(statusCtx, {
                type: 'bar',
                data: {
                    labels: ['Active', 'Expired', 'Pending'],
                    datasets: [
                        {
                            label: 'NMCN',
                            data: [
                                <?php echo $summary['nmcnActive']; ?>,
                                <?php echo $summary['nmcnExpired']; ?>,
                                <?php echo $summary['nmcnPending']; ?>
                            ],
                            backgroundColor: 'rgba(52, 152, 219, 0.7)',
                            borderColor: 'rgba(52, 152, 219, 1)',
                            borderWidth: 1
                        },
                        {
                            label: 'TRCN',
                            data: [
                                <?php echo $summary['trcnActive']; ?>,
                                <?php echo $summary['trcnExpired']; ?>,
                                <?php echo $summary['trcnPending']; ?>
                            ],
                            backgroundColor: 'rgba(155, 89, 182, 0.7)',
                            borderColor: 'rgba(155, 89, 182, 1)',
                            borderWidth: 1
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        x: {
                            grid: {
                                display: false
                            }
                        },
                        y: {
                            beginAtZero: true,
                            ticks: {
                                precision: 0
                            }
                        }
                    },
                    plugins: {
                        legend: {
                            position: 'top',
                            labels: {
                                usePointStyle: true,
                                padding: 15
                            }
                        }
                    }
                }
            });
            
            // Auto-refresh every 10 minutes
            setInterval(refreshReport, 10 * 60 * 1000);
        });
        
        // Refresh report function
        function refreshReport() {
            Swal.fire({
                title: 'Refreshing Report...',
                text: 'Please wait while we update the statistics.',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });
            
            // Simulate refresh delay
            setTimeout(() => {
                location.reload();
            }, 1000);
        }
        
        // Export functions
        function exportToPDF() {
            Swal.fire({
                title: 'Generating PDF Report...',
                text: 'This may take a moment.',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                    
                    // Use html2canvas to capture the entire report section
                    const element = document.querySelector('.content-wrapper');
                    
                    html2canvas(element, {
                        scale: 2,
                        useCORS: true,
                        logging: true,
                        windowHeight: element.scrollHeight,
                        onclone: function(clonedDoc) {
                            // Hide action buttons in clone
                            clonedDoc.querySelectorAll('.no-print').forEach(el => {
                                el.style.display = 'none';
                            });
                        }
                    }).then(canvas => {
                        const imgData = canvas.toDataURL('image/png');
                        const pdf = new jsPDF('p', 'mm', 'a4');
                        const imgWidth = 210;
                        const imgHeight = canvas.height * imgWidth / canvas.width;
                        
                        pdf.addImage(imgData, 'PNG', 0, 0, imgWidth, imgHeight);
                        
                        // Add report title and footer
                        pdf.setFontSize(16);
                        pdf.text('Professional Licenses Summary Report', 105, 10, null, null, 'center');
                        pdf.setFontSize(10);
                        pdf.text('Generated on: <?php echo date("F d, Y H:i:s"); ?>', 10, 285);
                        pdf.text('Page 1 of 1', 200, 285, null, null, 'right');
                        
                        // Save PDF
                        pdf.save('license_summary_report_<?php echo date("Y-m-d_H-i-s"); ?>.pdf');
                        
                        Swal.close();
                    }).catch(error => {
                        Swal.fire('Error', 'Failed to generate PDF: ' + error, 'error');
                    });
                }
            });
        }
        
        function exportToExcel() {
            // Create workbook
            let wb = XLSX.utils.book_new();
            
            // Summary sheet
            let summaryData = [
                ['PROFESSIONAL LICENSES SUMMARY REPORT'],
                ['Generated on:', '<?php echo date("F d, Y H:i:s"); ?>'],
                ['Total Employees:', <?php echo $summary['totalEmployees']; ?>],
                [''],
                ['LICENSE DISTRIBUTION'],
                ['Category', 'Count', 'Percentage'],
                ['NMCN Only', <?php echo $summary['withNMCN'] - $summary['withBoth']; ?>, <?php echo round((($summary['withNMCN'] - $summary['withBoth']) / $summary['totalEmployees']) * 100, 2); ?>],
                ['TRCN Only', <?php echo $summary['withTRCN'] - $summary['withBoth']; ?>, <?php echo round((($summary['withTRCN'] - $summary['withBoth']) / $summary['totalEmployees']) * 100, 2); ?>],
                ['Both NMCN & TRCN', <?php echo $summary['withBoth']; ?>, <?php echo round(($summary['withBoth'] / $summary['totalEmployees']) * 100, 2); ?>],
                ['No License', <?php echo $summary['withoutLicenses']; ?>, <?php echo round(($summary['withoutLicenses'] / $summary['totalEmployees']) * 100, 2); ?>],
                [''],
                ['LICENSE STATUS'],
                ['License Type', 'Active', 'Expired', 'Pending', 'Total'],
                ['NMCN', <?php echo $summary['nmcnActive']; ?>, <?php echo $summary['nmcnExpired']; ?>, <?php echo $summary['nmcnPending']; ?>, <?php echo $summary['withNMCN']; ?>],
                ['TRCN', <?php echo $summary['trcnActive']; ?>, <?php echo $summary['trcnExpired']; ?>, <?php echo $summary['trcnPending']; ?>, <?php echo $summary['withTRCN']; ?>],
                [''],
                ['KEY METRICS'],
                ['Expiring Soon (30 days):', <?php echo $summary['expiringSoon']; ?>],
                ['Compliance Score:', <?php echo $complianceScore ?? 0; ?>],
                [''],
                ['REPORT INFORMATION'],
                ['Generated By:', '<?php echo $_SESSION['user']['username'] ?? "System"; ?>'],
                ['Data Source:', 'Nominal Roll Database']
            ];
            
            let ws = XLSX.utils.aoa_to_sheet(summaryData);
            XLSX.utils.book_append_sheet(wb, ws, 'Summary');
            
            // Save file
            XLSX.writeFile(wb, 'license_summary_<?php echo date("Y-m-d"); ?>.xlsx');
            
            Swal.fire(
                'Excel Export Complete!',
                'The summary report has been exported to Excel.',
                'success'
            );
        }
        
        function takeScreenshot() {
            Swal.fire({
                title: 'Taking Screenshot...',
                text: 'Please wait while we capture the report.',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                    
                    // Capture the main content
                    html2canvas(document.querySelector('.content-wrapper'), {
                        scale: 2,
                        useCORS: true,
                        logging: false,
                        windowHeight: document.querySelector('.content-wrapper').scrollHeight
                    }).then(canvas => {
                        // Convert to image
                        let image = canvas.toDataURL('image/png');
                        
                        // Create download link
                        let link = document.createElement('a');
                        link.download = 'license_summary_<?php echo date("Y-m-d_H-i-s"); ?>.png';
                        link.href = image;
                        link.click();
                        
                        Swal.close();
                    }).catch(error => {
                        Swal.fire('Error', 'Failed to take screenshot: ' + error, 'error');
                    });
                }
            });
        }
        
        // Print optimization
        window.addEventListener('beforeprint', () => {
            // Hide unnecessary elements before printing
            document.querySelectorAll('.no-print').forEach(el => {
                el.style.display = 'none';
            });
        });
        
        window.addEventListener('afterprint', () => {
            // Restore elements after printing
            document.querySelectorAll('.no-print').forEach(el => {
                el.style.display = '';
            });
        });
    </script>
</body>
</html>