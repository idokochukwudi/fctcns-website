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
    <script src="https://cdn.jsdelivr.net/npm/qrcode@1.5.1/build/qrcode.min.js"></script>
    
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
            width: 120px;
            height: 140px;
            object-fit: cover;
            border: 3px solid var(--primary-color);
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
        }
        
        .photo-placeholder {
            width: 120px;
            height: 140px;
            background: #f0f0f0;
            border: 3px solid #dee2e6;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 40px;
            color: #adb5bd;
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
        
        .qr-container {
            background: white;
            padding: 15px;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            text-align: center;
        }
        
        #qrcode {
            width: 150px;
            height: 150px;
            margin: 0 auto;
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
        }
        
        .btn-action:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 30px rgba(0,0,0,0.15);
        }
        
        .btn-primary {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
        }
        
        .btn-success {
            background: linear-gradient(135deg, #28a745, #20c997);
            color: white;
        }
        
        .btn-info {
            background: linear-gradient(135deg, #17a2b8, #138496);
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
        
        .print-only {
            display: none;
        }
        
        @media print {
            .no-print {
                display: none !important;
            }
            
            .print-only {
                display: block;
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
            
            .btn-action {
                display: none;
            }
        }
        
        .floating-print {
            position: fixed;
            bottom: 30px;
            right: 30px;
            z-index: 1000;
        }
        
        .print-button {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            background: var(--primary-color);
            color: white;
            border: none;
            box-shadow: 0 5px 20px rgba(107, 78, 155, 0.4);
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
        }
        
        .print-button:hover {
            transform: scale(1.1);
            box-shadow: 0 10px 30px rgba(107, 78, 155, 0.6);
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="result-card">
            <!-- Header based on verification status -->
            <div class="result-header <?php 
                echo $status['is_valid'] ? 'verified' : 'unverified'; 
                echo !empty($status['warnings']) ? ' warning' : '';
            ?>">
                <div class="result-icon">
                    <i class="fas fa-<?php 
                        echo $status['is_valid'] ? 'check-circle' : 'times-circle'; 
                    ?>"></i>
                </div>
                <h2 class="h3 mb-2">Document Verification Result</h2>
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
                <!-- Candidate Photo and QR Code Row -->
                <div class="row mb-4">
                    <div class="col-md-4 text-center">
                        <?php if (!empty($applicant['passport_photo'])): ?>
                            <img src="<?php echo htmlspecialchars($applicant['passport_photo']); ?>" 
                                 alt="Passport" 
                                 class="candidate-photo">
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
                    
                    <div class="col-md-4">
                        <div class="qr-container bg-light">
                            <h6 class="mb-2">Verification ID</h6>
                            <p class="mb-1 small">
                                <code><?php echo $verificationData['verification_id']; ?></code>
                            </p>
                            <hr>
                            <p class="small text-muted mb-0">
                                <i class="fas fa-shield-alt me-1"></i>
                                Digitally Signed
                            </p>
                        </div>
                    </div>
                </div>
                
                <!-- Verification Summary -->
                <div class="alert alert-<?php 
                    echo $status['is_valid'] ? 'success' : 'danger'; 
                ?> mb-4">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-<?php 
                            echo $status['is_valid'] ? 'check-circle' : 'exclamation-triangle'; 
                        ?> fa-2x me-3"></i>
                        <div>
                            <strong>
                                <?php if ($status['is_valid']): ?>
                                    This document is authentic and has been verified.
                                <?php else: ?>
                                    This document could not be verified.
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
                
                <!-- Candidate Information Grid -->
                <h6 class="mb-3">
                    <i class="fas fa-user-graduate me-2 text-primary"></i>
                    Candidate Information
                </h6>
                
                <div class="info-grid">
                    <div class="info-item">
                        <div class="info-label">Full Name</div>
                        <div class="info-value">
                            <?php echo htmlspecialchars($applicant['first_name'] ?? '') . ' ' . 
                                     htmlspecialchars($applicant['last_name'] ?? ''); ?>
                        </div>
                    </div>
                    
                    <div class="info-item">
                        <div class="info-label">Application Number</div>
                        <div class="info-value"><?php echo htmlspecialchars($application['application_number']); ?></div>
                    </div>
                    
                    <div class="info-item">
                        <div class="info-label">JAMB Number</div>
                        <div class="info-value"><?php echo htmlspecialchars($application['jamb_number']); ?></div>
                    </div>
                    
                    <div class="info-item">
                        <div class="info-label">Programme</div>
                        <div class="info-value"><?php echo htmlspecialchars($application['program_choice_1']); ?></div>
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
                        <div class="info-value"><?php echo htmlspecialchars($exam_slip['slip_number']); ?></div>
                    </div>
                    
                    <div class="info-item">
                        <div class="info-label">Examination Date</div>
                        <div class="info-value">
                            <?php echo date('l, jS F Y', strtotime($exam_slip['exam_date'])); ?>
                        </div>
                    </div>
                    
                    <div class="info-item">
                        <div class="info-label">Examination Time</div>
                        <div class="info-value">
                            <?php echo date('h:i A', strtotime($exam_slip['exam_time'])); ?>
                        </div>
                    </div>
                    
                    <div class="info-item">
                        <div class="info-label">Reporting Time</div>
                        <div class="info-value">
                            <?php echo date('h:i A', strtotime($exam_slip['reporting_time'])); ?>
                        </div>
                    </div>
                    
                    <div class="info-item">
                        <div class="info-label">Venue</div>
                        <div class="info-value"><?php echo htmlspecialchars($exam_slip['exam_venue']); ?></div>
                    </div>
                    
                    <div class="info-item">
                        <div class="info-label">Seat Number</div>
                        <div class="info-value">
                            <span class="badge bg-info"><?php echo htmlspecialchars($exam_slip['seat_number']); ?></span>
                        </div>
                    </div>
                </div>
                
                <!-- Action Buttons -->
                <div class="action-buttons no-print">
                    <button class="btn-action btn-primary" onclick="window.print()">
                        <i class="fas fa-print"></i> Print Result
                    </button>
                    <button class="btn-action btn-success" onclick="downloadAsPDF()">
                        <i class="fas fa-download"></i> Save as PDF
                    </button>
                    <a href="/application-verify/portal" class="btn-action btn-info">
                        <i class="fas fa-redo-alt"></i> Verify Another
                    </a>
                </div>
                
                <!-- Verification Metadata -->
                <div class="mt-4 pt-3 border-top">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <p class="small text-muted mb-1">
                                <i class="fas fa-fingerprint me-2"></i>
                                Verification ID: <strong><?php echo $verificationData['verification_id']; ?></strong>
                            </p>
                            <p class="small text-muted mb-1">
                                <i class="fas fa-clock me-2"></i>
                                Verified on: <?php echo date('jS F Y, h:i A', strtotime($verificationData['verification_time'])); ?>
                            </p>
                        </div>
                        <div class="col-md-6">
                            <p class="small text-muted mb-1">
                                <i class="fas fa-globe me-2"></i>
                                IP Address: <?php echo htmlspecialchars($verificationData['verification_ip']); ?>
                            </p>
                            <?php if (!empty($verificationData['verifier_name'])): ?>
                            <p class="small text-muted mb-1">
                                <i class="fas fa-user me-2"></i>
                                Verified by: <?php echo htmlspecialchars($verificationData['verifier_name']); ?>
                            </p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="verification-footer">
                <img src="<?php echo $institution_logo ?? '/assets/img/college-seal.png'; ?>" 
                     alt="College Seal" class="institution-seal">
                <p class="small text-muted mb-1">
                    <strong><?php echo $institution_name; ?></strong><br>
                    <?php echo $institution_address; ?>
                </p>
                <p class="small text-muted mb-0">
                    This is an official verification from FCT College of Nursing Sciences.<br>
                    For inquiries: <a href="mailto:<?php echo $support_email; ?>"><?php echo $support_email; ?></a>
                </p>
            </div>
        </div>
    </div>
    
    <!-- Floating Print Button (Mobile) -->
    <div class="floating-print no-print">
        <button class="print-button" onclick="window.print()">
            <i class="fas fa-print"></i>
        </button>
    </div>
    
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // Generate QR Code for re-verification
        document.addEventListener('DOMContentLoaded', function() {
            const verificationUrl = '<?php echo $baseUrl; ?>/application-verify/slip/<?php echo urlencode($exam_slip['slip_number']); ?>';
            
            QRCode.toCanvas(document.getElementById('qrcode'), verificationUrl, {
                width: 150,
                margin: 1,
                color: {
                    dark: '#000000',
                    light: '#ffffff'
                }
            }, function(error) {
                if (error) console.error('QR Code generation error:', error);
            });
        });
        
        // Download as PDF function
        function downloadAsPDF() {
            // Use browser's print to PDF functionality
            window.print();
        }
        
        // Add print-specific styles
        const style = document.createElement('style');
        style.innerHTML = `
            @media print {
                body { background: white; }
                .result-header { -webkit-print-color-adjust: exact; print-color-adjust: exact; }
                .badge, .status-badge { -webkit-print-color-adjust: exact; print-color-adjust: exact; }
            }
        `;
        document.head.appendChild(style);
        
        // Track verification view
        if (navigator.sendBeacon) {
            navigator.sendBeacon('/application-verify/track-view', JSON.stringify({
                slip: '<?php echo $exam_slip['slip_number']; ?>',
                verification_id: '<?php echo $verificationData['verification_id']; ?>'
            }));
        }
    </script>
</body>
</html>