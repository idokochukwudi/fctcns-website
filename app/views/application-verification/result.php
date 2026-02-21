<?php
/**
 * Application Verification Result View
 * 
 * @var array $verificationData
 * @var array $exam_slip
 * @var array $application
 * @var array $applicant
 * @var bool $has_paid
 * @var array $status
 */

$baseUrl = defined('BASE_URL') ? rtrim(BASE_URL, '/') : '';
$institution_name = $institution_name ?? 'FCT College of Nursing Sciences';
$institution_address = $institution_address ?? 'Gwagwalada, Abuja';
$support_email = $support_email ?? 'verification@fctcns.edu.ng';

// Construct full name
$fullName = trim(
    ($applicant['title'] ?? '') . ' ' . 
    ($applicant['first_name'] ?? '') . ' ' . 
    ($applicant['last_name'] ?? '')
);

// If full name is empty, try to get from application
if (empty($fullName)) {
    $fullName = trim(
        ($application['first_name'] ?? '') . ' ' . 
        ($application['last_name'] ?? '')
    );
}

// Default if still empty
if (empty($fullName)) {
    $fullName = 'Not Provided';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($pageTitle ?? 'Verification Result'); ?></title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome 6 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- QR Code Library -->
    <script src="https://cdn.jsdelivr.net/npm/qrcode@1.5.3/build/qrcode.min.js"></script>
    
    <style>
        :root {
            --primary-color: #6B4E9B;
            --secondary-color: #4A3B6E;
            --success-color: #28a745;
            --danger-color: #dc3545;
            --warning-color: #ffc107;
            --info-color: #17a2b8;
        }
        
        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 30px 0;
        }
        
        .result-card {
            border: none;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            animation: slideIn 0.5s ease-out;
            max-width: 1000px;
            margin: 0 auto;
        }
        
        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .result-header {
            padding: 30px;
            text-align: center;
            color: white;
            position: relative;
            overflow: hidden;
        }
        
        .result-header.verified {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
        }
        
        .result-header.unverified {
            background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
        }
        
        .result-header.warning {
            background: linear-gradient(135deg, #fd7e14 0%, #ffc107 100%);
        }
        
        .result-header::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -50%;
            width: 200%;
            height: 200%;
            background: rgba(255,255,255,0.1);
            transform: rotate(45deg);
            animation: shine 3s infinite;
        }
        
        @keyframes shine {
            0% { transform: translateX(-100%) rotate(45deg); }
            100% { transform: translateX(100%) rotate(45deg); }
        }
        
        .result-icon {
            width: 80px;
            height: 80px;
            background: rgba(255,255,255,0.2);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
            font-size: 40px;
            position: relative;
            z-index: 1;
        }
        
        .verification-badge {
            display: inline-block;
            padding: 8px 20px;
            background: rgba(255,255,255,0.2);
            border-radius: 50px;
            font-weight: 600;
            font-size: 14px;
            backdrop-filter: blur(5px);
            margin-top: 15px;
        }
        
        .result-body {
            padding: 40px;
            background: white;
        }
        
        .candidate-photo {
            width: 150px;
            height: 150px;
            object-fit: cover;
            border: 3px solid var(--primary-color);
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
        }
        
        .photo-placeholder {
            width: 150px;
            height: 150px;
            background: #f0f0f0;
            border: 3px solid #dee2e6;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 60px;
            color: #adb5bd;
            margin: 0 auto;
        }
        
        .qr-container {
            background: white;
            padding: 15px;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            text-align: center;
            display: inline-block;
        }
        
        #qrcode {
            width: 150px;
            height: 150px;
            margin: 0 auto;
        }
        
        #qrcode canvas, #qrcode img {
            width: 100%;
            height: 100%;
            display: block;
        }
        
        .verification-id-box {
            height: 100%;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }
        
        .info-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 15px;
            margin: 20px 0;
        }
        
        .info-item {
            padding: 12px;
            background: #f8f9fa;
            border-radius: 10px;
            border-left: 4px solid var(--primary-color);
        }
        
        .info-label {
            font-size: 11px;
            color: #6c757d;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 5px;
        }
        
        .info-value {
            font-size: 16px;
            font-weight: 600;
            color: #212529;
            word-break: break-word;
        }
        
        .status-badge {
            display: inline-block;
            padding: 5px 15px;
            border-radius: 50px;
            font-size: 12px;
            font-weight: 600;
        }
        
        .status-badge.success {
            background: #d4edda;
            color: #155724;
        }
        
        .status-badge.danger {
            background: #f8d7da;
            color: #721c24;
        }
        
        .status-badge.warning {
            background: #fff3cd;
            color: #856404;
        }
        
        .status-badge.info {
            background: #d1ecf1;
            color: #0c5460;
        }
        
        .warnings-list {
            margin: 20px 0;
            padding: 15px;
            background: #fff3cd;
            border: 1px solid #ffeeba;
            border-radius: 10px;
        }
        
        .warning-item {
            display: flex;
            align-items: center;
            padding: 8px 0;
            border-bottom: 1px dashed #ffeeba;
        }
        
        .warning-item:last-child {
            border-bottom: none;
        }
        
        .warning-item i {
            color: #856404;
            margin-right: 10px;
            font-size: 14px;
        }
        
        .action-buttons {
            display: flex;
            gap: 15px;
            justify-content: center;
            margin-top: 30px;
            flex-wrap: wrap;
        }
        
        .btn-action {
            padding: 12px 30px;
            border-radius: 50px;
            font-weight: 600;
            transition: all 0.3s ease;
            border: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            text-decoration: none;
            cursor: pointer;
        }
        
        .btn-action:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 30px rgba(0,0,0,0.15);
        }
        
        .btn-primary {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
        }
        
        .btn-outline-primary {
            background: white;
            color: var(--primary-color);
            border: 2px solid var(--primary-color);
        }
        
        .btn-outline-primary:hover {
            background: var(--primary-color);
            color: white;
        }
        
        .verification-footer {
            background: #f8f9fa;
            padding: 20px;
            border-top: 1px solid #dee2e6;
            text-align: center;
        }
        
        .institution-seal {
            width: 60px;
            height: 60px;
            margin-bottom: 10px;
        }
        
        @media print {
            .no-print {
                display: none !important;
            }
            
            body {
                background: white;
                padding: 0;
            }
            
            .result-card {
                box-shadow: none;
                border: 2px solid #000;
            }
            
            .result-header {
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="result-card">
            <!-- Header based on verification status -->
            <div class="result-header <?php echo $status['is_valid'] ? 'verified' : 'unverified'; ?>">
                <div class="result-icon">
                    <i class="fas fa-<?php echo $status['is_valid'] ? 'check-circle' : 'times-circle'; ?>"></i>
                </div>
                <h2 class="h3 mb-2">Examination Slip Verification</h2>
                <div class="verification-badge">
                    <i class="fas fa-clock me-2"></i>
                    <?php echo date('jS F Y, h:i A', strtotime($verificationData['verification_time'])); ?>
                </div>
                
                <!-- Status Badges -->
                <div class="mt-3">
                    <?php foreach ($status['badges'] ?? [] as $badge): ?>
                    <span class="status-badge <?php echo $badge['type']; ?> me-2">
                        <?php echo $badge['text']; ?>
                    </span>
                    <?php endforeach; ?>
                </div>
            </div>
            
            <div class="result-body">
                <!-- Candidate Photo and Basic Info -->
                <div class="row mb-4">
                    <div class="col-md-3 text-center">
                        <?php 
                        $photoPath = '';
                        if (!empty($applicant['passport_photo'])) {
                            $photoPath = $applicant['passport_photo'];
                        } elseif (!empty($application['passport_photo'])) {
                            $photoPath = $application['passport_photo'];
                        }
                        
                        if (!empty($photoPath)): 
                        ?>
                            <img src="<?php echo htmlspecialchars($photoPath); ?>" 
                                 alt="Passport" 
                                 class="candidate-photo"
                                 onerror="this.onerror=null; this.src='data:image/svg+xml,%3Csvg xmlns=\'http://www.w3.org/2000/svg\' width=\'150\' height=\'150\' viewBox=\'0 0 150 150\'%3E%3Crect width=\'150\' height=\'150\' fill=\'%23f0f0f0\'/%3E%3Ccircle cx=\'75\' cy=\'75\' r=\'40\' fill=\'%23ccc\'/%3E%3Ctext x=\'75\' y=\'120\' text-anchor=\'middle\' fill=\'%23999\' font-size=\'14\' font-family=\'Arial\'%3ENo Photo%3C/text%3E%3C/svg%3E';">
                        <?php else: ?>
                            <div class="photo-placeholder">
                                <i class="fas fa-user-circle"></i>
                            </div>
                        <?php endif; ?>
                        <p class="small text-muted mt-2">
                            <i class="fas fa-camera"></i> Passport Photograph
                        </p>
                    </div>
                    
                    <div class="col-md-4 text-center">
                        <div class="qr-container">
                            <div id="qrcode"></div>
                            <p class="small text-muted mt-2 mb-0">
                                <i class="fas fa-qrcode"></i> Scan to Verify Again
                            </p>
                        </div>
                    </div>
                    
                    <div class="col-md-5">
                        <div class="verification-id-box p-3 bg-light rounded">
                            <h6 class="mb-2 text-primary">Verification Details</h6>
                            <p class="mb-1 small">
                                <strong>Verification ID:</strong><br>
                                <code><?php echo htmlspecialchars($verificationData['verification_id']); ?></code>
                            </p>
                            <hr class="my-2">
                            <p class="small text-muted mb-0">
                                <i class="fas fa-shield-alt me-1"></i>
                                Digitally Signed & Verified
                            </p>
                        </div>
                    </div>
                </div>
                
                <!-- Verification Summary -->
                <div class="alert alert-<?php echo $status['is_valid'] ? 'success' : 'danger'; ?> mb-4">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-<?php echo $status['is_valid'] ? 'check-circle' : 'exclamation-triangle'; ?> fa-2x me-3"></i>
                        <div>
                            <strong>
                                <?php if ($status['is_valid']): ?>
                                    This examination slip is authentic and has been verified.
                                <?php else: ?>
                                    This examination slip could not be verified.
                                <?php endif; ?>
                            </strong>
                            <?php if ($has_paid): ?>
                                <br><small>Payment status: Verified</small>
                            <?php else: ?>
                                <br><small class="text-danger">Payment pending verification</small>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                
                <!-- Warnings (if any) -->
                <?php if (!empty($status['warnings'])): ?>
                <div class="warnings-list">
                    <h6 class="mb-2 text-warning">
                        <i class="fas fa-exclamation-triangle me-2"></i> Warnings
                    </h6>
                    <?php foreach ($status['warnings'] as $warning): ?>
                    <div class="warning-item">
                        <i class="fas fa-exclamation-circle"></i>
                        <span><?php echo htmlspecialchars($warning); ?></span>
                    </div>
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>
                
                <!-- Candidate Information -->
                <h6 class="mb-3">
                    <i class="fas fa-user-graduate me-2 text-primary"></i>
                    Candidate Information
                </h6>
                
                <div class="info-grid">
                    <div class="info-item">
                        <div class="info-label">Full Name</div>
                        <div class="info-value">
                            <?php echo htmlspecialchars($fullName); ?>
                        </div>
                    </div>
                    
                    <div class="info-item">
                        <div class="info-label">Application Number</div>
                        <div class="info-value"><?php echo htmlspecialchars($application['application_number'] ?? 'N/A'); ?></div>
                    </div>
                    
                    <div class="info-item">
                        <div class="info-label">JAMB Number</div>
                        <div class="info-value"><?php echo htmlspecialchars($application['jamb_number'] ?? 'N/A'); ?></div>
                    </div>
                    
                    <div class="info-item">
                        <div class="info-label">Programme</div>
                        <div class="info-value"><?php echo htmlspecialchars($application['program_choice_1'] ?? 'N/A'); ?></div>
                    </div>
                </div>
                
                <!-- Examination Details -->
                <h6 class="mb-3 mt-4">
                    <i class="fas fa-calendar-alt me-2 text-primary"></i>
                    Examination Details
                </h6>
                
                <div class="info-grid">
                    <div class="info-item">
                        <div class="info-label">Slip Number</div>
                        <div class="info-value"><?php echo htmlspecialchars($exam_slip['slip_number'] ?? 'N/A'); ?></div>
                    </div>
                    
                    <div class="info-item">
                        <div class="info-label">Examination Date</div>
                        <div class="info-value">
                            <?php echo !empty($exam_slip['exam_date']) ? date('l, jS F Y', strtotime($exam_slip['exam_date'])) : 'N/A'; ?>
                        </div>
                    </div>
                    
                    <div class="info-item">
                        <div class="info-label">Examination Time</div>
                        <div class="info-value">
                            <?php echo !empty($exam_slip['exam_time']) ? date('h:i A', strtotime($exam_slip['exam_time'])) : 'N/A'; ?>
                        </div>
                    </div>
                    
                    <div class="info-item">
                        <div class="info-label">Reporting Time</div>
                        <div class="info-value">
                            <?php echo !empty($exam_slip['reporting_time']) ? date('h:i A', strtotime($exam_slip['reporting_time'])) : 'N/A'; ?>
                        </div>
                    </div>
                    
                    <div class="info-item">
                        <div class="info-label">Venue</div>
                        <div class="info-value"><?php echo htmlspecialchars($exam_slip['exam_venue'] ?? 'N/A'); ?></div>
                    </div>
                    
                    <div class="info-item">
                        <div class="info-label">Seat Number</div>
                        <div class="info-value">
                            <span class="badge bg-info"><?php echo htmlspecialchars($exam_slip['seat_number'] ?? 'N/A'); ?></span>
                        </div>
                    </div>
                </div>
                
                <!-- Action Buttons -->
                <div class="action-buttons no-print">
                    <button class="btn-action btn-primary" onclick="window.print()">
                        <i class="fas fa-print"></i> Print Result
                    </button>
                    <a href="/application-verify/portal" class="btn-action btn-outline-primary">
                        <i class="fas fa-redo-alt"></i> Verify Another
                    </a>
                </div>
                
                <!-- Verification Metadata -->
                <div class="mt-4 pt-3 border-top">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <p class="small text-muted mb-1">
                                <i class="fas fa-fingerprint me-2"></i>
                                Verification ID: <strong><?php echo htmlspecialchars($verificationData['verification_id']); ?></strong>
                            </p>
                            <p class="small text-muted mb-1">
                                <i class="fas fa-clock me-2"></i>
                                Verified on: <?php echo date('jS F Y, h:i A', strtotime($verificationData['verification_time'])); ?>
                            </p>
                        </div>
                        <div class="col-md-6">
                            <p class="small text-muted mb-1">
                                <i class="fas fa-globe me-2"></i>
                                IP Address: <?php echo htmlspecialchars($verificationData['verification_ip'] ?? $_SERVER['REMOTE_ADDR']); ?>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="verification-footer">
                <img src="/assets/img/college-seal.png" alt="College Seal" class="institution-seal" 
                     onerror="this.style.display='none'">
                <p class="small text-muted mb-1">
                    <strong><?php echo htmlspecialchars($institution_name); ?></strong><br>
                    <?php echo htmlspecialchars($institution_address); ?>
                </p>
                <p class="small text-muted mb-0">
                    This is an official verification from FCT College of Nursing Sciences.<br>
                    For inquiries: <a href="mailto:<?php echo htmlspecialchars($support_email); ?>"><?php echo htmlspecialchars($support_email); ?></a>
                </p>
            </div>
        </div>
    </div>
    
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // Generate QR Code for re-verification
        document.addEventListener('DOMContentLoaded', function() {
            const verificationUrl = '<?php echo $baseUrl; ?>/application-verify/slip/<?php echo urlencode($exam_slip['slip_number'] ?? ''); ?>';
            const qrContainer = document.getElementById('qrcode');
            
            if (qrContainer && verificationUrl) {
                // Clear container
                qrContainer.innerHTML = '';
                
                // Generate QR code
                QRCode.toCanvas(qrContainer, verificationUrl, {
                    width: 150,
                    margin: 1,
                    color: {
                        dark: '#6B4E9B',
                        light: '#ffffff'
                    }
                }, function(error) {
                    if (error) {
                        console.error('QR Code generation error:', error);
                        // Fallback to simple text
                        qrContainer.innerHTML = '<div style="font-size:10px;color:#999;">QR Code<br>unavailable</div>';
                    }
                });
            }
        });
        
        // Print functionality
        document.querySelector('.btn-action.btn-primary')?.addEventListener('click', function(e) {
            e.preventDefault();
            window.print();
        });
        
        // Add print styles
        const style = document.createElement('style');
        style.innerHTML = `
            @media print {
                body { background: white; }
                .result-header { -webkit-print-color-adjust: exact; print-color-adjust: exact; }
                .badge, .status-badge { -webkit-print-color-adjust: exact; print-color-adjust: exact; }
                .candidate-photo { -webkit-print-color-adjust: exact; print-color-adjust: exact; }
            }
        `;
        document.head.appendChild(style);
    </script>
</body>
</html>