<?php
/**
 * License Reports View
 * Shows expiring or expired licenses reports
 */
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>License Reports - <?php echo htmlspecialchars($reportTitle); ?> | <?php echo SITE_NAME; ?></title>
    
    <!-- CSS -->
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/assets/css/fontawesome.all.min.css">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/assets/css/admin.css">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/assets/css/dataTables.bootstrap4.min.css">
    
    <style>
        .license-card {
            border-left: 4px solid;
            transition: all 0.3s ease;
        }
        
        .license-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
        
        .license-card.expiring {
            border-left-color: #ffc107;
            background-color: #fff8e1;
        }
        
        .license-card.expired {
            border-left-color: #dc3545;
            background-color: #ffe6e6;
        }
        
        .license-card.active {
            border-left-color: #28a745;
            background-color: #e8f5e9;
        }
        
        .days-badge {
            font-size: 0.8rem;
            padding: 0.25rem 0.5rem;
        }
        
        .employee-photo {
            width: 60px;
            height: 60px;
            object-fit: cover;
            border-radius: 50%;
            border: 2px solid #dee2e6;
        }
        
        .license-type-badge {
            font-size: 0.75rem;
            padding: 0.25rem 0.5rem;
            margin-right: 5px;
        }
        
        .summary-card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 20px;
        }
        
        .summary-number {
            font-size: 2.5rem;
            font-weight: bold;
            margin-bottom: 5px;
        }
        
        .summary-label {
            font-size: 0.9rem;
            opacity: 0.9;
        }
        
        .export-btn-group {
            margin-top: 20px;
        }
        
        @media print {
            .no-print {
                display: none !important;
            }
            
            .card {
                border: 1px solid #ddd !important;
                box-shadow: none !important;
            }
            
            .license-card {
                page-break-inside: avoid;
            }
            
            .dataTable-pagination {
                display: none !important;
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
                                <i class="fas fa-file-contract"></i> 
                                <?php echo htmlspecialchars($reportTitle); ?>
                            </h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="<?php echo BASE_URL; ?>/admin">Dashboard</a></li>
                                <li class="breadcrumb-item"><a href="<?php echo BASE_URL; ?>/admin/nominal-roll">Nominal Roll</a></li>
                                <li class="breadcrumb-item active">License Reports</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Main Content -->
            <section class="content">
                <div class="container-fluid">
                    <?php include VIEW_PATH . 'admin/includes/flash-messages.php'; ?>
                    
                    <!-- Summary Cards -->
                    <div class="row">
                        <div class="col-lg-3 col-md-6">
                            <div class="summary-card">
                                <div class="summary-number">
                                    <?php echo count($employees); ?>
                                </div>
                                <div class="summary-label">
                                    Total Employees
                                </div>
                                <i class="fas fa-users fa-2x float-right mt-2"></i>
                            </div>
                        </div>
                        
                        <?php if ($reportType === 'expiring_licenses'): ?>
                            <div class="col-lg-3 col-md-6">
                                <div class="summary-card" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);">
                                    <div class="summary-number">
                                        <?php 
                                        $expiringCount = 0;
                                        $currentDate = date('Y-m-d');
                                        $thresholdDate = date('Y-m-d', strtotime('+30 days'));
                                        
                                        foreach ($employees as $employee) {
                                            if ((!empty($employee['nmcn_expiry_date']) && 
                                                 $employee['nmcn_expiry_date'] >= $currentDate && 
                                                 $employee['nmcn_expiry_date'] <= $thresholdDate) ||
                                                (!empty($employee['trcn_expiry_date']) && 
                                                 $employee['trcn_expiry_date'] >= $currentDate && 
                                                 $employee['trcn_expiry_date'] <= $thresholdDate)) {
                                                $expiringCount++;
                                            }
                                        }
                                        echo $expiringCount;
                                        ?>
                                    </div>
                                    <div class="summary-label">
                                        Expiring in 30 Days
                                    </div>
                                    <i class="fas fa-clock fa-2x float-right mt-2"></i>
                                </div>
                            </div>
                        <?php elseif ($reportType === 'expired_licenses'): ?>
                            <div class="col-lg-3 col-md-6">
                                <div class="summary-card" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);">
                                    <div class="summary-number">
                                        <?php 
                                        $expiredCount = 0;
                                        $currentDate = date('Y-m-d');
                                        
                                        foreach ($employees as $employee) {
                                            if ((!empty($employee['nmcn_expiry_date']) && 
                                                 $employee['nmcn_expiry_date'] < $currentDate) ||
                                                (!empty($employee['trcn_expiry_date']) && 
                                                 $employee['trcn_expiry_date'] < $currentDate)) {
                                                $expiredCount++;
                                            }
                                        }
                                        echo $expiredCount;
                                        ?>
                                    </div>
                                    <div class="summary-label">
                                        Already Expired
                                    </div>
                                    <i class="fas fa-exclamation-triangle fa-2x float-right mt-2"></i>
                                </div>
                            </div>
                        <?php endif; ?>
                        
                        <div class="col-lg-3 col-md-6">
                            <div class="summary-card" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);">
                                <div class="summary-number">
                                    <?php 
                                    $withLicenses = 0;
                                    foreach ($employees as $employee) {
                                        if (!empty($employee['nmcn_license_number']) || !empty($employee['trcn_license_number'])) {
                                            $withLicenses++;
                                        }
                                    }
                                    echo $withLicenses;
                                    ?>
                                </div>
                                <div class="summary-label">
                                    With Licenses
                                </div>
                                <i class="fas fa-id-card fa-2x float-right mt-2"></i>
                            </div>
                        </div>
                        
                        <div class="col-lg-3 col-md-6">
                            <div class="summary-card" style="background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);">
                                <div class="summary-number">
                                    <?php 
                                    $activeCount = 0;
                                    foreach ($employees as $employee) {
                                        if (($employee['nmcn_status'] === 'Active' && !empty($employee['nmcn_license_number'])) ||
                                            ($employee['trcn_status'] === 'Active' && !empty($employee['trcn_license_number']))) {
                                            $activeCount++;
                                        }
                                    }
                                    echo $activeCount;
                                    ?>
                                </div>
                                <div class="summary-label">
                                    Active Licenses
                                </div>
                                <i class="fas fa-check-circle fa-2x float-right mt-2"></i>
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
                                            
                                            <div class="btn-group ml-2 export-btn-group">
                                                <button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown">
                                                    <i class="fas fa-download"></i> Export Report
                                                </button>
                                                <div class="dropdown-menu dropdown-menu-right">
                                                    <a class="dropdown-item" href="#" onclick="exportToExcel()">
                                                        <i class="fas fa-file-excel text-success"></i> Export to Excel
                                                    </a>
                                                    <a class="dropdown-item" href="#" onclick="exportToPDF()">
                                                        <i class="fas fa-file-pdf text-danger"></i> Export to PDF
                                                    </a>
                                                    <a class="dropdown-item" href="#" onclick="exportToCSV()">
                                                        <i class="fas fa-file-csv text-info"></i> Export to CSV
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- Date Filter (for expiring licenses) -->
                                    <?php if ($reportType === 'expiring_licenses'): ?>
                                        <div class="row mt-3">
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="daysThreshold">Show licenses expiring within:</label>
                                                    <select id="daysThreshold" class="form-control" onchange="filterByDays(this.value)">
                                                        <option value="7">7 days</option>
                                                        <option value="30" selected>30 days</option>
                                                        <option value="60">60 days</option>
                                                        <option value="90">90 days</option>
                                                        <option value="180">180 days</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="licenseType">License Type:</label>
                                                    <select id="licenseType" class="form-control" onchange="filterByLicenseType(this.value)">
                                                        <option value="all">All Licenses</option>
                                                        <option value="nmcn">NMCN Only</option>
                                                        <option value="trcn">TRCN Only</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="statusFilter">Status:</label>
                                                    <select id="statusFilter" class="form-control" onchange="filterByStatus(this.value)">
                                                        <option value="all">All Status</option>
                                                        <option value="expiring">Expiring Soon</option>
                                                        <option value="active">Active</option>
                                                        <option value="expired">Expired</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Report Table -->
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title">License Details</h3>
                                    <div class="card-tools">
                                        <span class="badge badge-light">
                                            Generated: <?php echo date('F d, Y H:i:s'); ?>
                                        </span>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <?php if (empty($employees)): ?>
                                        <div class="alert alert-info">
                                            <i class="fas fa-info-circle"></i> 
                                            No employees found with 
                                            <?php 
                                            if ($reportType === 'expiring_licenses') {
                                                echo 'licenses expiring soon';
                                            } elseif ($reportType === 'expired_licenses') {
                                                echo 'expired licenses';
                                            }
                                            ?>.
                                        </div>
                                    <?php else: ?>
                                        <div class="table-responsive">
                                            <table id="licenseReportTable" class="table table-bordered table-hover">
                                                <thead>
                                                    <tr>
                                                        <th>S/N</th>
                                                        <th>Employee</th>
                                                        <th>Employee Number</th>
                                                        <th>Department</th>
                                                        <th>License Type</th>
                                                        <th>License Number</th>
                                                        <th>Issued Date</th>
                                                        <th>Expiry Date</th>
                                                        <th>Days Left</th>
                                                        <th>Status</th>
                                                        <th class="no-print">Actions</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php 
                                                    $serial = 1;
                                                    $currentDate = date('Y-m-d');
                                                    
                                                    foreach ($employees as $employee): 
                                                        // Process NMCN License if exists
                                                        if (!empty($employee['nmcn_license_number'])): 
                                                            $expiryDate = $employee['nmcn_expiry_date'];
                                                            $status = $employee['nmcn_status'] ?? 'Active';
                                                            
                                                            // Calculate days left
                                                            $daysLeft = 'N/A';
                                                            if (!empty($expiryDate) && $expiryDate != '0000-00-00') {
                                                                $expiryTimestamp = strtotime($expiryDate);
                                                                $currentTimestamp = strtotime($currentDate);
                                                                $daysDiff = floor(($expiryTimestamp - $currentTimestamp) / (60 * 60 * 24));
                                                                
                                                                if ($daysDiff > 0) {
                                                                    $daysLeft = $daysDiff;
                                                                } elseif ($daysDiff == 0) {
                                                                    $daysLeft = 0;
                                                                    $status = 'Expired';
                                                                } else {
                                                                    $daysLeft = abs($daysDiff);
                                                                    $status = 'Expired';
                                                                }
                                                            }
                                                            
                                                            // Determine CSS class
                                                            $licenseClass = 'active';
                                                            if ($status === 'Expired') {
                                                                $licenseClass = 'expired';
                                                            } elseif ($daysLeft <= 30 && $daysLeft > 0) {
                                                                $licenseClass = 'expiring';
                                                            }
                                                    ?>
                                                    <tr class="license-card <?php echo $licenseClass; ?>" data-license-type="nmcn" data-status="<?php echo strtolower($status); ?>" data-days-left="<?php echo $daysLeft; ?>">
                                                        <td><?php echo $serial++; ?></td>
                                                        <td>
                                                            <div class="d-flex align-items-center">
                                                                <?php if (!empty($employee['passport_photo'])): ?>
                                                                    <img src="<?php echo BASE_URL . '/uploads/passports/' . htmlspecialchars($employee['passport_photo']); ?>" 
                                                                         alt="Photo" class="employee-photo mr-2">
                                                                <?php else: ?>
                                                                    <div class="employee-photo mr-2 d-flex align-items-center justify-content-center bg-light">
                                                                        <i class="fas fa-user text-muted"></i>
                                                                    </div>
                                                                <?php endif; ?>
                                                                <div>
                                                                    <strong><?php echo htmlspecialchars($employee['surname'] . ' ' . $employee['first_name'] . ' ' . ($employee['middle_name'] ?? '')); ?></strong><br>
                                                                    <small class="text-muted"><?php echo htmlspecialchars($employee['rank'] ?? 'N/A'); ?></small>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <?php echo htmlspecialchars($employee['employee_number']); ?><br>
                                                            <small class="text-muted">
                                                                <?php echo !empty($employee['ippis_number']) ? 'IPPIS: ' . htmlspecialchars($employee['ippis_number']) : ''; ?>
                                                            </small>
                                                        </td>
                                                        <td><?php echo htmlspecialchars($employee['department'] ?? 'N/A'); ?></td>
                                                        <td>
                                                            <span class="badge license-type-badge" style="background-color: #3498db;">
                                                                NMCN
                                                            </span>
                                                        </td>
                                                        <td>
                                                            <code><?php echo htmlspecialchars($employee['nmcn_license_number']); ?></code>
                                                        </td>
                                                        <td>
                                                            <?php echo !empty($employee['nmcn_issued_date']) && $employee['nmcn_issued_date'] != '0000-00-00' 
                                                                ? date('M d, Y', strtotime($employee['nmcn_issued_date'])) 
                                                                : 'N/A'; ?>
                                                        </td>
                                                        <td>
                                                            <?php echo !empty($expiryDate) && $expiryDate != '0000-00-00' 
                                                                ? date('M d, Y', strtotime($expiryDate)) 
                                                                : 'N/A'; ?>
                                                        </td>
                                                        <td>
                                                            <?php if (is_numeric($daysLeft)): 
                                                                $badgeClass = 'badge-success';
                                                                if ($daysLeft <= 30 && $daysLeft > 0) $badgeClass = 'badge-warning';
                                                                if ($daysLeft <= 0) $badgeClass = 'badge-danger';
                                                            ?>
                                                                <span class="badge <?php echo $badgeClass; ?> days-badge">
                                                                    <?php 
                                                                    if ($daysLeft > 0) {
                                                                        echo $daysLeft . ' days';
                                                                    } elseif ($daysLeft == 0) {
                                                                        echo 'Today';
                                                                    } else {
                                                                        echo abs($daysLeft) . ' days ago';
                                                                    }
                                                                    ?>
                                                                </span>
                                                            <?php else: ?>
                                                                <span class="badge badge-secondary days-badge">N/A</span>
                                                            <?php endif; ?>
                                                        </td>
                                                        <td>
                                                            <?php 
                                                            $statusBadgeClass = 'badge-secondary';
                                                            if ($status === 'Active') $statusBadgeClass = 'badge-success';
                                                            if ($status === 'Expired') $statusBadgeClass = 'badge-danger';
                                                            if ($status === 'Pending') $statusBadgeClass = 'badge-warning';
                                                            ?>
                                                            <span class="badge <?php echo $statusBadgeClass; ?>">
                                                                <?php echo htmlspecialchars($status); ?>
                                                            </span>
                                                        </td>
                                                        <td class="no-print">
                                                            <div class="btn-group btn-group-sm">
                                                                <a href="<?php echo BASE_URL; ?>/admin/nominal-roll/view/<?php echo $employee['id']; ?>" 
                                                                   class="btn btn-info" title="View Employee">
                                                                    <i class="fas fa-eye"></i>
                                                                </a>
                                                                <a href="<?php echo BASE_URL; ?>/admin/nominal-roll/edit/<?php echo $employee['id']; ?>" 
                                                                   class="btn btn-warning" title="Edit License">
                                                                    <i class="fas fa-edit"></i>
                                                                </a>
                                                                <button type="button" class="btn btn-primary" 
                                                                        onclick="sendReminder(<?php echo $employee['id']; ?>, 'nmcn')"
                                                                        title="Send Reminder">
                                                                    <i class="fas fa-envelope"></i>
                                                                </button>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    <?php endif; ?>
                                                    
                                                    <!-- Process TRCN License if exists -->
                                                    <?php if (!empty($employee['trcn_license_number'])): 
                                                        $expiryDate = $employee['trcn_expiry_date'];
                                                        $status = $employee['trcn_status'] ?? 'Active';
                                                        
                                                        // Calculate days left
                                                        $daysLeft = 'N/A';
                                                        if (!empty($expiryDate) && $expiryDate != '0000-00-00') {
                                                            $expiryTimestamp = strtotime($expiryDate);
                                                            $currentTimestamp = strtotime($currentDate);
                                                            $daysDiff = floor(($expiryTimestamp - $currentTimestamp) / (60 * 60 * 24));
                                                            
                                                            if ($daysDiff > 0) {
                                                                $daysLeft = $daysDiff;
                                                            } elseif ($daysDiff == 0) {
                                                                $daysLeft = 0;
                                                                $status = 'Expired';
                                                            } else {
                                                                $daysLeft = abs($daysDiff);
                                                                $status = 'Expired';
                                                            }
                                                        }
                                                        
                                                        // Determine CSS class
                                                        $licenseClass = 'active';
                                                        if ($status === 'Expired') {
                                                            $licenseClass = 'expired';
                                                        } elseif ($daysLeft <= 30 && $daysLeft > 0) {
                                                            $licenseClass = 'expiring';
                                                        }
                                                    ?>
                                                    <tr class="license-card <?php echo $licenseClass; ?>" data-license-type="trcn" data-status="<?php echo strtolower($status); ?>" data-days-left="<?php echo $daysLeft; ?>">
                                                        <td><?php echo $serial++; ?></td>
                                                        <td>
                                                            <div class="d-flex align-items-center">
                                                                <?php if (!empty($employee['passport_photo'])): ?>
                                                                    <img src="<?php echo BASE_URL . '/uploads/passports/' . htmlspecialchars($employee['passport_photo']); ?>" 
                                                                         alt="Photo" class="employee-photo mr-2">
                                                                <?php else: ?>
                                                                    <div class="employee-photo mr-2 d-flex align-items-center justify-content-center bg-light">
                                                                        <i class="fas fa-user text-muted"></i>
                                                                    </div>
                                                                <?php endif; ?>
                                                                <div>
                                                                    <strong><?php echo htmlspecialchars($employee['surname'] . ' ' . $employee['first_name'] . ' ' . ($employee['middle_name'] ?? '')); ?></strong><br>
                                                                    <small class="text-muted"><?php echo htmlspecialchars($employee['rank'] ?? 'N/A'); ?></small>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <?php echo htmlspecialchars($employee['employee_number']); ?><br>
                                                            <small class="text-muted">
                                                                <?php echo !empty($employee['ippis_number']) ? 'IPPIS: ' . htmlspecialchars($employee['ippis_number']) : ''; ?>
                                                            </small>
                                                        </td>
                                                        <td><?php echo htmlspecialchars($employee['department'] ?? 'N/A'); ?></td>
                                                        <td>
                                                            <span class="badge license-type-badge" style="background-color: #9b59b6;">
                                                                TRCN
                                                            </span>
                                                        </td>
                                                        <td>
                                                            <code><?php echo htmlspecialchars($employee['trcn_license_number']); ?></code>
                                                        </td>
                                                        <td>
                                                            <?php echo !empty($employee['trcn_issued_date']) && $employee['trcn_issued_date'] != '0000-00-00' 
                                                                ? date('M d, Y', strtotime($employee['trcn_issued_date'])) 
                                                                : 'N/A'; ?>
                                                        </td>
                                                        <td>
                                                            <?php echo !empty($expiryDate) && $expiryDate != '0000-00-00' 
                                                                ? date('M d, Y', strtotime($expiryDate)) 
                                                                : 'N/A'; ?>
                                                        </td>
                                                        <td>
                                                            <?php if (is_numeric($daysLeft)): 
                                                                $badgeClass = 'badge-success';
                                                                if ($daysLeft <= 30 && $daysLeft > 0) $badgeClass = 'badge-warning';
                                                                if ($daysLeft <= 0) $badgeClass = 'badge-danger';
                                                            ?>
                                                                <span class="badge <?php echo $badgeClass; ?> days-badge">
                                                                    <?php 
                                                                    if ($daysLeft > 0) {
                                                                        echo $daysLeft . ' days';
                                                                    } elseif ($daysLeft == 0) {
                                                                        echo 'Today';
                                                                    } else {
                                                                        echo abs($daysLeft) . ' days ago';
                                                                    }
                                                                    ?>
                                                                </span>
                                                            <?php else: ?>
                                                                <span class="badge badge-secondary days-badge">N/A</span>
                                                            <?php endif; ?>
                                                        </td>
                                                        <td>
                                                            <?php 
                                                            $statusBadgeClass = 'badge-secondary';
                                                            if ($status === 'Active') $statusBadgeClass = 'badge-success';
                                                            if ($status === 'Expired') $statusBadgeClass = 'badge-danger';
                                                            if ($status === 'Pending') $statusBadgeClass = 'badge-warning';
                                                            ?>
                                                            <span class="badge <?php echo $statusBadgeClass; ?>">
                                                                <?php echo htmlspecialchars($status); ?>
                                                            </span>
                                                        </td>
                                                        <td class="no-print">
                                                            <div class="btn-group btn-group-sm">
                                                                <a href="<?php echo BASE_URL; ?>/admin/nominal-roll/view/<?php echo $employee['id']; ?>" 
                                                                   class="btn btn-info" title="View Employee">
                                                                    <i class="fas fa-eye"></i>
                                                                </a>
                                                                <a href="<?php echo BASE_URL; ?>/admin/nominal-roll/edit/<?php echo $employee['id']; ?>" 
                                                                   class="btn btn-warning" title="Edit License">
                                                                    <i class="fas fa-edit"></i>
                                                                </a>
                                                                <button type="button" class="btn btn-primary" 
                                                                        onclick="sendReminder(<?php echo $employee['id']; ?>, 'trcn')"
                                                                        title="Send Reminder">
                                                                    <i class="fas fa-envelope"></i>
                                                                </button>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    <?php endif; ?>
                                                    <?php endforeach; ?>
                                                </tbody>
                                            </table>
                                        </div>
                                        
                                        <!-- Export Summary -->
                                        <div class="row mt-4 no-print">
                                            <div class="col-md-12">
                                                <div class="alert alert-info">
                                                    <i class="fas fa-info-circle"></i> 
                                                    Report Summary: 
                                                    Showing <?php echo $serial - 1; ?> license records 
                                                    for <?php echo count($employees); ?> employees.
                                                    <?php if ($reportType === 'expiring_licenses'): ?>
                                                        Filtered to show licenses expiring within 30 days.
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endif; ?>
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
    <script src="<?php echo BASE_URL; ?>/assets/js/jquery.dataTables.min.js"></script>
    <script src="<?php echo BASE_URL; ?>/assets/js/dataTables.bootstrap4.min.js"></script>
    <script src="<?php echo BASE_URL; ?>/assets/js/sweetalert2.all.min.js"></script>
    <script src="<?php echo BASE_URL; ?>/assets/js/html2canvas.min.js"></script>
    <script src="<?php echo BASE_URL; ?>/assets/js/jspdf.min.js"></script>
    <script src="<?php echo BASE_URL; ?>/assets/js/xlsx.full.min.js"></script>
    
    <script>
        // Initialize DataTable
        $(document).ready(function() {
            $('#licenseReportTable').DataTable({
                "pageLength": 25,
                "responsive": true,
                "order": [[8, 'asc']], // Sort by days left
                "columnDefs": [
                    { "orderable": false, "targets": [0, 10] }, // Disable sorting for S/N and Actions
                    { "searchable": false, "targets": [0, 10] } // Disable search for S/N and Actions
                ],
                "language": {
                    "search": "Search within report:",
                    "lengthMenu": "Show _MENU_ records per page",
                    "zeroRecords": "No matching records found",
                    "info": "Showing _START_ to _END_ of _TOTAL_ records",
                    "infoEmpty": "Showing 0 to 0 of 0 records",
                    "infoFiltered": "(filtered from _MAX_ total records)",
                    "paginate": {
                        "first": "First",
                        "last": "Last",
                        "next": "Next",
                        "previous": "Previous"
                    }
                }
            });
        });
        
        // Filter functions
        function filterByDays(days) {
            // This would typically make an AJAX call to reload with new filter
            // For now, we'll redirect with parameter
            window.location.href = '<?php echo BASE_URL; ?>/admin/nominal-roll/expiring-licenses?days=' + days;
        }
        
        function filterByLicenseType(type) {
            let table = $('#licenseReportTable').DataTable();
            
            if (type === 'all') {
                table.columns(4).search('').draw();
            } else {
                table.columns(4).search(type.toUpperCase()).draw();
            }
        }
        
        function filterByStatus(status) {
            $('tr.license-card').show();
            
            if (status !== 'all') {
                $('tr.license-card').each(function() {
                    let rowStatus = $(this).data('status');
                    let daysLeft = $(this).data('days-left');
                    
                    let showRow = false;
                    
                    if (status === 'active' && rowStatus === 'active') {
                        showRow = true;
                    } else if (status === 'expired' && rowStatus === 'expired') {
                        showRow = true;
                    } else if (status === 'expiring' && daysLeft <= 30 && daysLeft > 0) {
                        showRow = true;
                    }
                    
                    if (!showRow) {
                        $(this).hide();
                    }
                });
                
                // Update DataTable display
                $('#licenseReportTable').DataTable().draw();
            }
        }
        
        // Export functions
        function exportToExcel() {
            // Create workbook
            let wb = XLSX.utils.book_new();
            
            // Get table data
            let table = document.getElementById('licenseReportTable');
            let ws = XLSX.utils.table_to_sheet(table);
            
            // Add to workbook
            XLSX.utils.book_append_sheet(wb, ws, 'License Report');
            
            // Generate file name
            let fileName = 'license_report_<?php echo $reportType; ?>_<?php echo date("Y-m-d"); ?>.xlsx';
            
            // Save file
            XLSX.writeFile(wb, fileName);
        }
        
        function exportToCSV() {
            let csv = [];
            let rows = document.querySelectorAll('#licenseReportTable tr');
            
            for (let i = 0; i < rows.length; i++) {
                let row = [], cols = rows[i].querySelectorAll('td, th');
                
                for (let j = 0; j < cols.length; j++) {
                    // Skip action column
                    if (j === cols.length - 1 && i > 0) continue;
                    
                    // Clean up data
                    let data = cols[j].innerText.replace(/(\r\n|\n|\r)/gm, "").replace(/(\s\s)/gm, " ");
                    row.push('"' + data + '"');
                }
                
                csv.push(row.join(","));
            }
            
            // Download CSV file
            let csvContent = "data:text/csv;charset=utf-8," + csv.join("\n");
            let encodedUri = encodeURI(csvContent);
            let link = document.createElement("a");
            link.setAttribute("href", encodedUri);
            link.setAttribute("download", "license_report_<?php echo $reportType; ?>_<?php echo date("Y-m-d"); ?>.csv");
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
        }
        
        function exportToPDF() {
            Swal.fire({
                title: 'Generating PDF...',
                text: 'Please wait while we generate the PDF report.',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                    
                    // Use html2canvas to capture the report
                    html2canvas(document.querySelector('.card-body'), {
                        scale: 2,
                        useCORS: true,
                        logging: true,
                        allowTaint: true
                    }).then(canvas => {
                        const imgData = canvas.toDataURL('image/png');
                        const pdf = new jsPDF('landscape', 'mm', 'a4');
                        const imgWidth = 280;
                        const imgHeight = canvas.height * imgWidth / canvas.width;
                        
                        pdf.addImage(imgData, 'PNG', 10, 10, imgWidth, imgHeight);
                        
                        // Add report title and date
                        pdf.setFontSize(18);
                        pdf.text('<?php echo $reportTitle; ?>', 10, 5);
                        pdf.setFontSize(10);
                        pdf.text('Generated on: <?php echo date("F d, Y H:i:s"); ?>', 200, 5, null, null, 'right');
                        
                        // Save PDF
                        pdf.save('license_report_<?php echo $reportType; ?>_<?php echo date("Y-m-d"); ?>.pdf');
                        
                        Swal.close();
                    }).catch(error => {
                        Swal.fire('Error', 'Failed to generate PDF: ' + error, 'error');
                    });
                }
            });
        }
        
        // Send reminder function
        function sendReminder(employeeId, licenseType) {
            Swal.fire({
                title: 'Send Reminder',
                text: 'Send license expiry reminder to this employee?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Yes, send reminder',
                cancelButtonText: 'Cancel',
                showLoaderOnConfirm: true,
                preConfirm: () => {
                    return fetch('<?php echo BASE_URL; ?>/admin/nominal-roll/send-reminder', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded',
                        },
                        body: 'employee_id=' + employeeId + '&license_type=' + licenseType + '&csrf_token=<?php echo $csrf_token; ?>'
                    })
                    .then(response => {
                        if (!response.ok) {
                            throw new Error(response.statusText);
                        }
                        return response.json();
                    })
                    .then(data => {
                        if (!data.success) {
                            throw new Error(data.message || 'Failed to send reminder');
                        }
                        return data;
                    })
                    .catch(error => {
                        Swal.showValidationMessage(
                            'Request failed: ' + error
                        );
                    });
                },
                allowOutsideClick: () => !Swal.isLoading()
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire(
                        'Reminder Sent!',
                        'License expiry reminder has been sent to the employee.',
                        'success'
                    );
                }
            });
        }
        
        // Auto-refresh for expiring licenses report (every 5 minutes)
        <?php if ($reportType === 'expiring_licenses'): ?>
            setTimeout(function() {
                location.reload();
            }, 5 * 60 * 1000); // 5 minutes
        <?php endif; ?>
    </script>
</body>
</html>