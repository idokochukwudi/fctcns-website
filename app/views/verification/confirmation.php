<?php
/**
 * Verification Confirmation View
 * Shows a nice confirmation when QR code is scanned
 * 
 * @package FCT_CNS
 */

// Extract data
$verification = $verificationData ?? [];
$employee = $employee ?? [];
$baseUrl = $baseUrl ?? BASE_URL;

// Determine verification status
$isValid = $verification['isValid'] ?? false;
$statusClass = $isValid ? 'valid' : 'invalid';
$statusIcon = $isValid ? 'fa-check-circle' : 'fa-times-circle';
$statusText = $isValid ? 'Verified Successfully' : 'Verification Failed';
$statusColor = $isValid ? '#28a745' : '#dc3545';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($pageTitle ?? 'Document Verification'); ?></title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        /* Reset & Base */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            color: #333;
        }
        
        /* Verification Card */
        .verification-card {
            background: white;
            border-radius: 20px;
            width: 100%;
            max-width: 800px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            overflow: hidden;
            animation: slideUp 0.5s ease-out;
        }
        
        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        /* Header */
        .verification-header {
            background: linear-gradient(135deg, #003366, #0056b3);
            color: white;
            padding: 40px;
            text-align: center;
            position: relative;
            overflow: hidden;
        }
        
        .verification-header::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(255,255,255,0.1) 1px, transparent 1px);
            background-size: 50px 50px;
            opacity: 0.1;
        }
        
        .college-logo {
            width: 80px;
            height: 80px;
            background: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
            color: #003366;
            font-size: 36px;
            font-weight: bold;
        }
        
        .status-badge {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            padding: 12px 24px;
            border-radius: 50px;
            font-weight: 600;
            margin: 20px auto;
            font-size: 18px;
            background: rgba(255, 255, 255, 0.2);
            border: 2px solid white;
        }
        
        /* Status Animation */
        .status-animation {
            text-align: center;
            margin: 30px 0;
        }
        
        .status-icon {
            width: 100px;
            height: 100px;
            background: <?php echo $statusColor; ?>;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
            color: white;
            font-size: 48px;
            animation: pulse 2s infinite;
        }
        
        @keyframes pulse {
            0% {
                transform: scale(1);
                box-shadow: 0 0 0 0 rgba(0, 123, 255, 0.7);
            }
            70% {
                transform: scale(1.05);
                box-shadow: 0 0 0 20px rgba(0, 123, 255, 0);
            }
            100% {
                transform: scale(1);
                box-shadow: 0 0 0 0 rgba(0, 123, 255, 0);
            }
        }
        
        /* Body */
        .verification-body {
            padding: 40px;
        }
        
        .employee-card {
            background: #f8f9fa;
            border-radius: 15px;
            padding: 30px;
            margin: 30px 0;
            border: 2px solid #e9ecef;
            transition: all 0.3s ease;
        }
        
        .employee-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            border-color: #003366;
        }
        
        .employee-photo {
            width: 120px;
            height: 140px;
            border-radius: 10px;
            overflow: hidden;
            border: 3px solid white;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            margin: 0 auto 20px;
        }
        
        .employee-photo img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        
        .employee-info h3 {
            font-size: 24px;
            color: #003366;
            margin-bottom: 10px;
            text-align: center;
        }
        
        .employee-title {
            font-size: 16px;
            color: #666;
            text-align: center;
            margin-bottom: 20px;
        }
        
        .employee-details {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 15px;
            margin-top: 20px;
        }
        
        .detail-item {
            display: flex;
            justify-content: space-between;
            padding: 10px 0;
            border-bottom: 1px solid #eee;
        }
        
        .detail-label {
            font-weight: 600;
            color: #555;
        }
        
        .detail-value {
            color: #003366;
            font-weight: 500;
        }
        
        /* Verification Details */
        .verification-details {
            background: #f0f8ff;
            border-radius: 10px;
            padding: 25px;
            margin: 25px 0;
            border-left: 4px solid #003366;
        }
        
        .verification-meta {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
            margin-top: 15px;
        }
        
        .meta-item {
            display: flex;
            flex-direction: column;
        }
        
        .meta-label {
            font-size: 12px;
            color: #666;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 5px;
        }
        
        .meta-value {
            font-size: 14px;
            color: #333;
            font-weight: 500;
        }
        
        /* Action Buttons */
        .action-buttons {
            display: flex;
            gap: 15px;
            justify-content: center;
            flex-wrap: wrap;
            margin-top: 30px;
        }
        
        .btn {
            padding: 12px 24px;
            border-radius: 8px;
            border: none;
            font-weight: 600;
            font-size: 14px;
            cursor: pointer;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            text-decoration: none;
        }
        
        .btn-primary {
            background: linear-gradient(135deg, #003366, #0056b3);
            color: white;
        }
        
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 51, 102, 0.3);
        }
        
        .btn-outline {
            background: white;
            color: #003366;
            border: 2px solid #003366;
        }
        
        .btn-outline:hover {
            background: #003366;
            color: white;
        }
        
        /* Footer */
        .verification-footer {
            text-align: center;
            padding: 25px;
            background: #f8f9fa;
            border-top: 1px solid #e9ecef;
            color: #666;
            font-size: 12px;
        }
        
        .security-note {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            margin-bottom: 10px;
            color: #28a745;
            font-weight: 500;
        }
        
        /* Responsive */
        @media (max-width: 768px) {
            .verification-header {
                padding: 30px 20px;
            }
            
            .verification-body {
                padding: 25px 20px;
            }
            
            .employee-card {
                padding: 20px;
            }
            
            .employee-details {
                grid-template-columns: 1fr;
            }
            
            .action-buttons {
                flex-direction: column;
            }
            
            .btn {
                width: 100%;
                justify-content: center;
            }
        }
        
        /* Print Styles */
        @media print {
            body {
                background: white !important;
                padding: 0 !important;
            }
            
            .verification-card {
                box-shadow: none !important;
                border: 1px solid #ddd !important;
                max-width: 100% !important;
            }
            
            .action-buttons,
            .btn {
                display: none !important;
            }
        }
        
        /* Loading Spinner */
        .loading-spinner {
            display: inline-block;
            width: 20px;
            height: 20px;
            border: 3px solid rgba(255,255,255,0.3);
            border-radius: 50%;
            border-top-color: white;
            animation: spin 1s ease-in-out infinite;
        }
        
        @keyframes spin {
            to { transform: rotate(360deg); }
        }
    </style>
</head>
<body>
    <div class="verification-card">
        <!-- Header -->
        <div class="verification-header">
            <div class="college-logo">
                <i class="fas fa-university"></i>
            </div>
            <h1>FCT College of Nursing Sciences</h1>
            <p>Official Document Verification System</p>
            
            <div class="status-badge">
                <i class="fas <?php echo $statusIcon; ?>"></i>
                <span><?php echo $statusText; ?></span>
            </div>
        </div>
        
        <!-- Status Animation -->
        <div class="status-animation">
            <div class="status-icon">
                <i class="fas <?php echo $statusIcon; ?>"></i>
            </div>
            <h2 style="color: <?php echo $statusColor; ?>;">Document <?php echo $isValid ? 'Verified' : 'Verification Issue'; ?></h2>
            <p style="color: #666; max-width: 500px; margin: 0 auto;">
                <?php if ($isValid): ?>
                This document has been successfully verified as an authentic record from FCT College of Nursing Sciences.
                <?php else: ?>
                There was an issue verifying this document. Please contact the HR department for assistance.
                <?php endif; ?>
            </p>
        </div>
        
        <!-- Body -->
        <div class="verification-body">
            <!-- Employee Card -->
            <div class="employee-card">
                <?php if (!empty($employee['passport_photo'])): ?>
                <div class="employee-photo">
                    <img src="<?php echo $baseUrl; ?>/verify/passport/<?php echo $employee['id']; ?>" 
                         alt="Passport Photo"
                         onerror="this.src='data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMTUwIiBoZWlnaHQ9IjE4MCIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj48cmVjdCB3aWR0aD0iMTUwIiBoZWlnaHQ9IjE4MCIgZmlsbD0iI2YwZjBmMCIvPjxjaXJjbGUgY3g9Ijc1IiBjeT0iNzAiIHI9IjQwIiBmaWxsPSIjY2NjIi8+PHJlY3QgeD0iNDAiIHk9IjEyMCIgd2lkdGg9IjcwIiBoZWlnaHQ9IjUwIiBmaWxsPSIjY2NjIiByeD0iNSIvPjx0ZXh0IHg9Ijc1IiB5PSIxNzAiIHRleHQtYW5jaG9yPSJtaWRkbGUiIGZvbnQtZmFtaWx5PSJBcmlhbCIgZm9udC1zaXplPSIxMiIgZmlsbD0iIzY2NiI+Tm8gUGhvdG88L3RleHQ+PC9zdmc+'; this.onerror=null;">
                </div>
                <?php endif; ?>
                
                <div class="employee-info">
                    <h3>
                        <?php echo htmlspecialchars(strtoupper($employee['surname'] ?? '')); ?>, 
                        <?php echo htmlspecialchars($employee['first_name'] ?? ''); ?>
                        <?php if (!empty($employee['middle_name'])): ?>
                        <?php echo htmlspecialchars($employee['middle_name']); ?>
                        <?php endif; ?>
                    </h3>
                    <div class="employee-title">
                        <?php echo htmlspecialchars($employee['rank'] ?? 'N/A'); ?> | 
                        Grade Level: <?php echo htmlspecialchars($employee['grade_level'] ?? 'N/A'); ?>
                    </div>
                    
                    <div class="employee-details">
                        <div class="detail-item">
                            <span class="detail-label">Employee Number:</span>
                            <span class="detail-value"><?php echo htmlspecialchars($employee['employee_number'] ?? 'N/A'); ?></span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">Department:</span>
                            <span class="detail-value"><?php echo htmlspecialchars($employee['department'] ?? 'N/A'); ?></span>
                        </div>
                        <!-- License Summary -->
                        <?php if (isset($verification['licenseStatus']) && $verification['licenseStatus']['overall_status'] !== 'none'): ?>
                        <div class="detail-item">
                            <span class="detail-label">License Status:</span>
                            <span class="detail-value">
                                <?php
                                $statusIcon = '';
                                $statusColor = '';
                                switch ($verification['licenseStatus']['overall_status']) {
                                    case 'valid':
                                        $statusIcon = 'fa-check-circle';
                                        $statusColor = '#28a745';
                                        $statusText = 'Valid';
                                        break;
                                    case 'expiring':
                                        $statusIcon = 'fa-exclamation-triangle';
                                        $statusColor = '#ffc107';
                                        $statusText = 'Expiring Soon';
                                        break;
                                    case 'expired':
                                        $statusIcon = 'fa-times-circle';
                                        $statusColor = '#dc3545';
                                        $statusText = 'Expired';
                                        break;
                                }
                                ?>
                                <i class="fas <?php echo $statusIcon; ?>" style="color: <?php echo $statusColor; ?>;"></i>
                                <span style="color: <?php echo $statusColor; ?>; font-weight: bold;"><?php echo $statusText; ?></span>
                            </span>
                        </div>
                        <?php endif; ?>
                        <div class="detail-item">
                            <span class="detail-label">Status:</span>
                            <span class="detail-value">
                                <?php if (($employee['status'] ?? '') === 'active'): ?>
                                <span style="color: #28a745; font-weight: bold;">Active</span>
                                <?php elseif (($employee['status'] ?? '') === 'draft'): ?>
                                <span style="color: #ffc107; font-weight: bold;">Draft</span>
                                <?php else: ?>
                                <span style="color: #dc3545; font-weight: bold;">Inactive</span>
                                <?php endif; ?>
                            </span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">Date of Birth:</span>
                            <span class="detail-value">
                                <?php echo !empty($employee['date_of_birth']) ? date('F j, Y', strtotime($employee['date_of_birth'])) : 'N/A'; ?>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Professional Licenses Section -->
            <?php if (isset($verification['licenseStatus']) && 
                     ($verification['licenseStatus']['nmcn']['number'] || 
                      $verification['licenseStatus']['trcn']['number'])): ?>
            <div class="verification-details" style="margin-top: 25px; border-left: 4px solid #17a2b8;">
                <h3 style="color: #17a2b8; margin-bottom: 15px;">
                    <i class="fas fa-id-card me-2"></i>Professional Licenses Status
                </h3>
                
                <div style="display: grid; grid-template-columns: 1fr; gap: 15px;">
                    <?php if ($verification['licenseStatus']['nmcn']['number']): ?>
                    <div class="license-card" style="background: #f8f9fa; padding: 15px; border-radius: 8px; border: 1px solid #e9ecef;">
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px;">
                            <h4 style="margin: 0; color: #003366;">
                                <i class="fas fa-stethoscope me-2"></i>NMCN License
                            </h4>
                            <span class="license-badge" style="
                                padding: 5px 12px;
                                border-radius: 20px;
                                font-size: 12px;
                                font-weight: bold;
                                background: <?php echo $verification['licenseStatus']['nmcn']['is_valid'] ? 
                                             ($verification['licenseStatus']['nmcn']['is_expiring'] ? '#fff3cd' : '#d4edda') : 
                                             '#f8d7da'; ?>;
                                color: <?php echo $verification['licenseStatus']['nmcn']['is_valid'] ? 
                                        ($verification['licenseStatus']['nmcn']['is_expiring'] ? '#856404' : '#155724') : 
                                        '#721c24'; ?>;
                                border: 1px solid <?php echo $verification['licenseStatus']['nmcn']['is_valid'] ? 
                                                  ($verification['licenseStatus']['nmcn']['is_expiring'] ? '#ffeaa7' : '#c3e6cb') : 
                                                  '#f5c6cb'; ?>;
                            ">
                                <?php echo $verification['licenseStatus']['nmcn']['is_valid'] ? 
                                       ($verification['licenseStatus']['nmcn']['is_expiring'] ? 'EXPIRING' : 'ACTIVE') : 
                                       'EXPIRED'; ?>
                            </span>
                        </div>
                        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 10px;">
                            <div>
                                <div style="font-size: 12px; color: #666; margin-bottom: 3px;">License Number</div>
                                <div style="font-weight: 500; color: #003366;">
                                    <?php echo htmlspecialchars($verification['licenseStatus']['nmcn']['number']); ?>
                                </div>
                            </div>
                            <?php if ($verification['licenseStatus']['nmcn']['issued_date']): ?>
                            <div>
                                <div style="font-size: 12px; color: #666; margin-bottom: 3px;">Issued Date</div>
                                <div style="font-weight: 500;">
                                    <?php echo date('M j, Y', strtotime($verification['licenseStatus']['nmcn']['issued_date'])); ?>
                                </div>
                            </div>
                            <?php endif; ?>
                            <?php if ($verification['licenseStatus']['nmcn']['expiry_date']): ?>
                            <div>
                                <div style="font-size: 12px; color: #666; margin-bottom: 3px;">Expiry Date</div>
                                <div style="font-weight: 500;">
                                    <?php echo date('M j, Y', strtotime($verification['licenseStatus']['nmcn']['expiry_date'])); ?>
                                    <?php if ($verification['licenseStatus']['nmcn']['days_remaining'] !== null): ?>
                                    <br>
                                    <small style="color: <?php echo $verification['licenseStatus']['nmcn']['is_expired'] ? '#dc3545' : 
                                                             ($verification['licenseStatus']['nmcn']['is_expiring'] ? '#ffc107' : '#28a745'); ?>;">
                                        (<?php echo $verification['licenseStatus']['nmcn']['is_expired'] ? 'Expired' : 
                                            $verification['licenseStatus']['nmcn']['days_remaining'] . ' days remaining'; ?>)
                                    </small>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                    <?php endif; ?>
                    
                    <?php if ($verification['licenseStatus']['trcn']['number']): ?>
                    <div class="license-card" style="background: #f8f9fa; padding: 15px; border-radius: 8px; border: 1px solid #e9ecef;">
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px;">
                            <h4 style="margin: 0; color: #003366;">
                                <i class="fas fa-chalkboard-teacher me-2"></i>TRCN License
                            </h4>
                            <span class="license-badge" style="
                                padding: 5px 12px;
                                border-radius: 20px;
                                font-size: 12px;
                                font-weight: bold;
                                background: <?php echo $verification['licenseStatus']['trcn']['is_valid'] ? 
                                             ($verification['licenseStatus']['trcn']['is_expiring'] ? '#fff3cd' : '#d4edda') : 
                                             '#f8d7da'; ?>;
                                color: <?php echo $verification['licenseStatus']['trcn']['is_valid'] ? 
                                        ($verification['licenseStatus']['trcn']['is_expiring'] ? '#856404' : '#155724') : 
                                        '#721c24'; ?>;
                                border: 1px solid <?php echo $verification['licenseStatus']['trcn']['is_valid'] ? 
                                                  ($verification['licenseStatus']['trcn']['is_expiring'] ? '#ffeaa7' : '#c3e6cb') : 
                                                  '#f5c6cb'; ?>;
                            ">
                                <?php echo $verification['licenseStatus']['trcn']['is_valid'] ? 
                                       ($verification['licenseStatus']['trcn']['is_expiring'] ? 'EXPIRING' : 'ACTIVE') : 
                                       'EXPIRED'; ?>
                            </span>
                        </div>
                        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 10px;">
                            <div>
                                <div style="font-size: 12px; color: #666; margin-bottom: 3px;">License Number</div>
                                <div style="font-weight: 500; color: #003366;">
                                    <?php echo htmlspecialchars($verification['licenseStatus']['trcn']['number']); ?>
                                </div>
                            </div>
                            <?php if ($verification['licenseStatus']['trcn']['issued_date']): ?>
                            <div>
                                <div style="font-size: 12px; color: #666; margin-bottom: 3px;">Issued Date</div>
                                <div style="font-weight: 500;">
                                    <?php echo date('M j, Y', strtotime($verification['licenseStatus']['trcn']['issued_date'])); ?>
                                </div>
                            </div>
                            <?php endif; ?>
                            <?php if ($verification['licenseStatus']['trcn']['expiry_date']): ?>
                            <div>
                                <div style="font-size: 12px; color: #666; margin-bottom: 3px;">Expiry Date</div>
                                <div style="font-weight: 500;">
                                    <?php echo date('M j, Y', strtotime($verification['licenseStatus']['trcn']['expiry_date'])); ?>
                                    <?php if ($verification['licenseStatus']['trcn']['days_remaining'] !== null): ?>
                                    <br>
                                    <small style="color: <?php echo $verification['licenseStatus']['trcn']['is_expired'] ? '#dc3545' : 
                                                             ($verification['licenseStatus']['trcn']['is_expiring'] ? '#ffc107' : '#28a745'); ?>;">
                                        (<?php echo $verification['licenseStatus']['trcn']['is_expired'] ? 'Expired' : 
                                            $verification['licenseStatus']['trcn']['days_remaining'] . ' days remaining'; ?>)
                                    </small>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>
                
                <?php if ($verification['licenseStatus']['overall_status'] !== 'none'): ?>
                <div style="margin-top: 15px; padding: 12px; background: #e7f1ff; border-radius: 6px; border: 1px solid #b8daff;">
                    <div style="display: flex; justify-content: space-between; align-items: center;">
                        <div>
                            <strong style="color: #004085;">Overall Professional Status:</strong>
                            <span style="margin-left: 10px;">
                                <?php
                                $statusText = '';
                                switch ($verification['licenseStatus']['overall_status']) {
                                    case 'valid':
                                        $statusText = 'Valid Professional License(s)';
                                        $statusClass = 'success';
                                        break;
                                    case 'expiring':
                                        $statusText = 'License(s) Expiring Soon';
                                        $statusClass = 'warning';
                                        break;
                                    case 'expired':
                                        $statusText = 'No Valid Professional License';
                                        $statusClass = 'danger';
                                        break;
                                }
                                ?>
                                <span style="padding: 4px 10px; border-radius: 4px; font-weight: bold;
                                    background: <?php echo $statusClass === 'success' ? '#d4edda' : 
                                                   ($statusClass === 'warning' ? '#fff3cd' : '#f8d7da'); ?>;
                                    color: <?php echo $statusClass === 'success' ? '#155724' : 
                                              ($statusClass === 'warning' ? '#856404' : '#721c24'); ?>;
                                    border: 1px solid <?php echo $statusClass === 'success' ? '#c3e6cb' : 
                                                         ($statusClass === 'warning' ? '#ffeaa7' : '#f5c6cb'); ?>;
                                ">
                                    <?php echo $statusText; ?>
                                </span>
                            </span>
                        </div>
                        <i class="fas fa-info-circle" style="color: #004085;"></i>
                    </div>
                </div>
                <?php endif; ?>
            </div>
            <?php endif; ?>
            
            <!-- Verification Details -->
            <div class="verification-details">
                <h3 style="color: #003366; margin-bottom: 15px;">
                    <i class="fas fa-info-circle me-2"></i>Verification Details
                </h3>
                
                <div class="verification-meta">
                    <div class="meta-item">
                        <span class="meta-label">Verification ID</span>
                        <span class="meta-value"><?php echo htmlspecialchars($verification['verificationId'] ?? 'N/A'); ?></span>
                    </div>
                    <div class="meta-item">
                        <span class="meta-label">Document Reference</span>
                        <span class="meta-value"><?php echo htmlspecialchars($verification['documentRef'] ?? 'N/A'); ?></span>
                    </div>
                    <div class="meta-item">
                        <span class="meta-label">Verification Date</span>
                        <span class="meta-value">
                            <?php echo !empty($verification['verificationDate']) ? 
                                date('F j, Y, g:i a', strtotime($verification['verificationDate'])) : 
                                date('F j, Y, g:i a'); ?>
                        </span>
                    </div>
                    <div class="meta-item">
                        <span class="meta-label">Verification Status</span>
                        <span class="meta-value" style="color: <?php echo $statusColor; ?>; font-weight: bold;">
                            <?php echo $isValid ? 'Valid ✓' : 'Invalid ✗'; ?>
                        </span>
                    </div>
                </div>
                
                <?php if (!empty($verification['verifierName'])): ?>
                <div class="meta-item" style="margin-top: 15px;">
                    <span class="meta-label">Verified By</span>
                    <span class="meta-value"><?php echo htmlspecialchars($verification['verifierName']); ?></span>
                </div>
                <?php endif; ?>
                
                <?php if (!empty($verification['verifierNotes'])): ?>
                <div class="meta-item" style="margin-top: 15px;">
                    <span class="meta-label">Notes</span>
                    <span class="meta-value"><?php echo htmlspecialchars($verification['verifierNotes']); ?></span>
                </div>
                <?php endif; ?>
            </div>
            
            <!-- Action Buttons -->
            <div class="action-buttons">
                <?php if ($isValid): ?>
                <button class="btn btn-primary" onclick="printVerification()">
                    <i class="fas fa-print"></i> Print Verification
                </button>
                <a href="<?php echo $baseUrl; ?>/admin/nominal-roll/print/<?php echo $employee['id']; ?>" 
                   target="_blank" class="btn btn-outline">
                    <i class="fas fa-file-alt"></i> View Full Document
                </a>
                <?php else: ?>
                <a href="<?php echo $baseUrl; ?>/admin/nominal-roll/contact" class="btn btn-primary">
                    <i class="fas fa-headset"></i> Contact Support
                </a>
                <?php endif; ?>
                <button class="btn btn-outline" onclick="window.close()">
                    <i class="fas fa-times"></i> Close
                </button>
            </div>
        </div>
        
        <!-- Footer -->
        <div class="verification-footer">
            <div class="security-note">
                <i class="fas fa-shield-alt"></i>
                <span>Secure Verification System</span>
            </div>
            <p>
                This verification confirms the authenticity of the document at the time of verification.<br>
                FCT College of Nursing Sciences © <?php echo date('Y'); ?>. All rights reserved.
            </p>
            <p style="font-size: 10px; color: #999; margin-top: 10px;">
                Verification ID: <?php echo htmlspecialchars($verification['verificationId'] ?? 'N/A'); ?> | 
                IP: <?php echo htmlspecialchars($verification['ipAddress'] ?? 'N/A'); ?>
            </p>
        </div>
    </div>
    
    <script>
        // Print verification
        function printVerification() {
            window.print();
        }
        
        // Auto-close after 30 seconds (for mobile users)
        setTimeout(function() {
            if (confirm("Close verification window?")) {
                window.close();
            }
        }, 30000);
        
        // Add confetti effect for successful verification
        <?php if ($isValid): ?>
        document.addEventListener('DOMContentLoaded', function() {
            createConfetti();
        });
        
        function createConfetti() {
            const colors = ['#003366', '#28a745', '#0056b3', '#17a2b8', '#6f42c1'];
            const confettiCount = 50;
            
            for (let i = 0; i < confettiCount; i++) {
                const confetti = document.createElement('div');
                confetti.style.position = 'fixed';
                confetti.style.width = '10px';
                confetti.style.height = '10px';
                confetti.style.background = colors[Math.floor(Math.random() * colors.length)];
                confetti.style.borderRadius = '50%';
                confetti.style.left = Math.random() * 100 + 'vw';
                confetti.style.top = '-20px';
                confetti.style.zIndex = '9999';
                confetti.style.opacity = '0';
                document.body.appendChild(confetti);
                
                // Animate confetti
                const animation = confetti.animate([
                    { 
                        transform: 'translate(0, 0) rotate(0deg)',
                        opacity: 1 
                    },
                    { 
                        transform: `translate(${Math.random() * 200 - 100}px, ${window.innerHeight}px) rotate(${Math.random() * 360}deg)`,
                        opacity: 0 
                    }
                ], {
                    duration: 3000 + Math.random() * 2000,
                    easing: 'cubic-bezier(0.215, 0.61, 0.355, 1)'
                });
                
                animation.onfinish = () => confetti.remove();
            }
        }
        <?php endif; ?>
        
        // Keyboard shortcuts
        document.addEventListener('keydown', function(e) {
            // Ctrl/Cmd + P for print
            if ((e.ctrlKey || e.metaKey) && e.key === 'p') {
                e.preventDefault();
                printVerification();
            }
            
            // Escape to close
            if (e.key === 'Escape') {
                window.close();
            }
        });
    </script>
</body>
</html>