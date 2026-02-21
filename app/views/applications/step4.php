<?php
/**
 * Step 4 - Examination Slip View
 * 
 * @var array $application
 * @var array $exam_slip
 * @var array $applicant
 */
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($pageTitle ?? 'Examination Slip'); ?></title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 40px 20px;
        }
        
        .slip-container {
            max-width: 900px;
            margin: 0 auto;
        }
        
        .exam-slip {
            background: white;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 30px 60px rgba(0,0,0,0.3);
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
        
        .slip-header {
            background: linear-gradient(135deg, #003366, #0056b3);
            color: white;
            padding: 30px;
            text-align: center;
            position: relative;
            overflow: hidden;
        }
        
        .slip-header::before {
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
            margin: 0 auto 15px;
            color: #003366;
            font-size: 32px;
            font-weight: bold;
        }
        
        .slip-header h1 {
            font-size: 24px;
            margin-bottom: 5px;
        }
        
        .slip-header h2 {
            font-size: 20px;
            font-weight: normal;
            opacity: 0.9;
        }
        
        .slip-badge {
            display: inline-block;
            background: rgba(255,255,255,0.2);
            padding: 8px 20px;
            border-radius: 50px;
            margin-top: 15px;
            font-weight: bold;
            border: 2px solid white;
        }
        
        .slip-body {
            padding: 40px;
        }
        
        .candidate-info {
            background: #f8f9fa;
            border-radius: 15px;
            padding: 25px;
            margin-bottom: 30px;
            border: 2px solid #e9ecef;
        }
        
        .info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-top: 20px;
        }
        
        .info-item {
            display: flex;
            flex-direction: column;
        }
        
        .info-label {
            font-size: 12px;
            color: #666;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 5px;
        }
        
        .info-value {
            font-size: 16px;
            font-weight: 600;
            color: #003366;
        }
        
        .qr-section {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            border-radius: 15px;
            padding: 30px;
            margin: 30px 0;
            text-align: center;
            border: 2px dashed #003366;
        }
        
        .qr-title {
            font-size: 18px;
            font-weight: 600;
            color: #003366;
            margin-bottom: 15px;
        }
        
        .qr-description {
            color: #666;
            margin-bottom: 25px;
            font-size: 14px;
        }
        
        .qr-code-container {
            display: flex;
            justify-content: center;
            align-items: center;
            margin: 20px 0;
        }
        
        .qr-code {
            background: white;
            padding: 15px;
            border-radius: 10px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            display: inline-block;
            transition: all 0.3s ease;
        }
        
        .qr-code:hover {
            transform: scale(1.05);
            box-shadow: 0 15px 40px rgba(0,51,102,0.2);
        }
        
        .qr-code img {
            display: block;
            width: 200px;
            height: 200px;
            image-rendering: -moz-crisp-edges;
            image-rendering: -webkit-crisp-edges;
            image-rendering: pixelated;
        }
        
        .qr-fallback {
            display: inline-block;
            padding: 20px;
            background: white;
            border-radius: 10px;
            text-align: center;
            font-family: monospace;
            font-size: 14px;
            max-width: 300px;
            word-break: break-all;
        }
        
        .qr-actions {
            margin-top: 20px;
        }
        
        .qr-note {
            margin-top: 15px;
            font-size: 12px;
            color: #666;
            background: #fff3cd;
            padding: 10px;
            border-radius: 5px;
        }
        
        .instruction-box {
            background: #e8f4fd;
            border-left: 4px solid #17a2b8;
            padding: 20px;
            border-radius: 10px;
            margin: 30px 0;
        }
        
        .instruction-box h3 {
            color: #17a2b8;
            margin-bottom: 15px;
            font-size: 18px;
        }
        
        .instruction-box ul {
            list-style: none;
            padding-left: 0;
        }
        
        .instruction-box li {
            margin-bottom: 10px;
            padding-left: 25px;
            position: relative;
        }
        
        .instruction-box li:before {
            content: '✓';
            color: #17a2b8;
            position: absolute;
            left: 0;
            font-weight: bold;
        }
        
        .action-buttons {
            display: flex;
            gap: 15px;
            justify-content: center;
            flex-wrap: wrap;
            margin-top: 30px;
        }
        
        .btn {
            padding: 12px 30px;
            border-radius: 8px;
            font-weight: 600;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 10px;
            transition: all 0.3s ease;
            border: none;
            cursor: pointer;
            font-size: 14px;
        }
        
        .btn-primary {
            background: linear-gradient(135deg, #003366, #0056b3);
            color: white;
        }
        
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 30px rgba(0,51,102,0.3);
        }
        
        .btn-success {
            background: linear-gradient(135deg, #28a745, #20c997);
            color: white;
        }
        
        .btn-success:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 30px rgba(40,167,69,0.3);
        }
        
        .btn-outline {
            background: transparent;
            color: #003366;
            border: 2px solid #003366;
        }
        
        .btn-outline:hover {
            background: #003366;
            color: white;
        }
        
        .slip-footer {
            text-align: center;
            padding: 30px;
            background: #f8f9fa;
            border-top: 1px solid #e9ecef;
            color: #666;
            font-size: 12px;
        }
        
        .security-badge {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            margin-bottom: 10px;
            color: #28a745;
        }
        
        .download-count {
            font-size: 11px;
            color: #999;
            margin-top: 10px;
        }
        
        .alert {
            padding: 15px 20px;
            border-radius: 8px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .alert-info {
            background: #d1ecf1;
            color: #0c5460;
            border: 1px solid #bee5eb;
        }
        
        .alert-warning {
            background: #fff3cd;
            color: #856404;
            border: 1px solid #ffeaa7;
        }
        
        .alert-success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        
        @media (max-width: 768px) {
            .slip-body {
                padding: 25px 20px;
            }
            
            .info-grid {
                grid-template-columns: 1fr;
            }
            
            .action-buttons {
                flex-direction: column;
            }
            
            .btn {
                width: 100%;
                justify-content: center;
            }
            
            .qr-code img {
                width: 150px;
                height: 150px;
            }
        }
        
        @media print {
            body {
                background: white;
                padding: 0;
            }
            
            .slip-container {
                max-width: 100%;
            }
            
            .exam-slip {
                box-shadow: none;
                border: 1px solid #ddd;
            }
            
            .action-buttons,
            .btn {
                display: none;
            }
            
            .qr-code {
                box-shadow: none;
                border: 1px solid #ddd;
            }
        }
    </style>
</head>
<body>
    <div class="slip-container">
        <div class="exam-slip">
            <!-- Header -->
            <div class="slip-header">
                <div class="college-logo">
                    <i class="fas fa-university"></i>
                </div>
                <h1>FCT COLLEGE OF NURSING SCIENCES</h1>
                <h2>Gwagwalada, Abuja</h2>
                <div class="slip-badge">
                    <i class="fas fa-ticket-alt me-2"></i>
                    EXAMINATION SLIP
                </div>
            </div>
            
            <!-- Body -->
            <div class="slip-body">
                <!-- Success Alert -->
                <div class="alert alert-success">
                    <i class="fas fa-check-circle" style="font-size: 24px;"></i>
                    <div>
                        <strong>Payment Verified Successfully!</strong><br>
                        Your examination slip has been generated. Please print and bring to the examination center.
                    </div>
                </div>
                
                <!-- Candidate Information -->
                <div class="candidate-info">
                    <h3 style="color: #003366; margin-bottom: 20px;">
                        <i class="fas fa-user-graduate me-2"></i>
                        Candidate Information
                    </h3>
                    
                    <div class="info-grid">
                        <div class="info-item">
                            <span class="info-label">Full Name</span>
                            <span class="info-value"><?php echo htmlspecialchars($applicant_name ?? ''); ?></span>
                        </div>
                        
                        <div class="info-item">
                            <span class="info-label">Application Number</span>
                            <span class="info-value"><?php echo htmlspecialchars($application['application_number'] ?? 'N/A'); ?></span>
                        </div>
                        
                        <div class="info-item">
                            <span class="info-label">JAMB Registration Number</span>
                            <span class="info-value"><?php echo htmlspecialchars($application['jamb_number'] ?? 'N/A'); ?></span>
                        </div>
                        
                        <div class="info-item">
                            <span class="info-label">Programme of Study</span>
                            <span class="info-value"><?php echo htmlspecialchars($application['program_choice_1'] ?? 'N/A'); ?></span>
                        </div>
                    </div>
                </div>
                
                <!-- Examination Details -->
                <div style="margin: 30px 0;">
                    <h3 style="color: #003366; margin-bottom: 20px;">
                        <i class="fas fa-calendar-alt me-2"></i>
                        Examination Details
                    </h3>
                    
                    <div class="info-grid">
                        <div class="info-item">
                            <span class="info-label">Examination Slip Number</span>
                            <span class="info-value" style="font-family: monospace; font-size: 18px;">
                                <?php echo htmlspecialchars($exam_slip['slip_number'] ?? 'N/A'); ?>
                            </span>
                        </div>
                        
                        <div class="info-item">
                            <span class="info-label">Examination Date</span>
                            <span class="info-value">
                                <?php 
                                $examDate = $exam_slip['exam_date'] ?? ($exam_details['date'] ?? 'To be announced');
                                echo date('l, jS F Y', strtotime($examDate));
                                ?>
                            </span>
                        </div>
                        
                        <div class="info-item">
                            <span class="info-label">Examination Time</span>
                            <span class="info-value"><?php echo htmlspecialchars($exam_slip['exam_time'] ?? '10:00 AM'); ?></span>
                        </div>
                        
                        <div class="info-item">
                            <span class="info-label">Reporting Time</span>
                            <span class="info-value"><?php echo htmlspecialchars($exam_slip['reporting_time'] ?? '8:00 AM'); ?></span>
                        </div>
                        
                        <div class="info-item">
                            <span class="info-label">Venue</span>
                            <span class="info-value"><?php echo htmlspecialchars($exam_slip['exam_venue'] ?? $exam_details['venue'] ?? 'FCT College of Nursing Sciences'); ?></span>
                        </div>
                        
                        <div class="info-item">
                            <span class="info-label">Seat Number</span>
                            <span class="info-value"><?php echo htmlspecialchars($exam_slip['seat_number'] ?? 'To be assigned on exam day'); ?></span>
                        </div>
                    </div>
                </div>
                
                <!-- QR Code Section -->
                <div class="qr-section">
                    <div class="qr-title">
                        <i class="fas fa-qrcode me-2"></i>
                        Verification QR Code
                    </div>
                    
                    <div class="qr-description">
                        Scan this QR code at the examination center to verify your slip authenticity.
                    </div>
                    
                    <div class="qr-code-container">
                        <?php if (!empty($exam_slip['slip_number'])): ?>
                            <?php
                            // Build QR code URL - FIXED: Use the correct route
                            $baseUrl = rtrim(defined('BASE_URL') ? BASE_URL : '/', '/');
                            $qrUrl = $baseUrl . '/application-verify/generate-qr/' . urlencode($exam_slip['slip_number']);
                            $verificationUrl = $baseUrl . '/application-verify/slip/' . urlencode($exam_slip['slip_number']);
                            
                            // Add cache buster to prevent caching issues
                            $qrUrl .= '?t=' . time();
                            ?>
                            
                            <div class="qr-code">
                                <img src="<?php echo htmlspecialchars($qrUrl); ?>" 
                                     alt="QR Code for Verification"
                                     id="qrImage"
                                     onerror="this.onerror=null; handleQRError(this);"
                                     onload="console.log('QR code loaded successfully');">
                            </div>
                            
                            <div style="margin-top: 15px;">
                                <small class="text-muted">
                                    <i class="fas fa-info-circle"></i>
                                    Verification URL: <br>
                                    <span style="font-family: monospace; font-size: 11px; color: #0066cc;">
                                        <?php echo htmlspecialchars($verificationUrl); ?>
                                    </span>
                                </small>
                            </div>
                        <?php else: ?>
                            <div class="alert alert-warning">
                                <i class="fas fa-exclamation-triangle"></i>
                                QR code not available. Please contact support.
                            </div>
                        <?php endif; ?>
                    </div>
                    
                    <div class="qr-actions">
                        <button class="btn btn-outline btn-sm" onclick="showQRCode()">
                            <i class="fas fa-expand"></i> View Full Size
                        </button>
                        <button class="btn btn-outline btn-sm" onclick="refreshQR()">
                            <i class="fas fa-sync-alt"></i> Refresh QR
                        </button>
                    </div>
                    
                    <div class="qr-note">
                        <i class="fas fa-shield-alt"></i>
                        This QR code is unique to your examination slip and will be verified by exam officials.
                    </div>
                </div>
                
                <!-- Important Instructions -->
                <div class="instruction-box">
                    <h3>
                        <i class="fas fa-clipboard-list me-2"></i>
                        Important Instructions
                    </h3>
                    
                    <ul>
                        <li>Arrive at the examination venue at least 1 hour before the scheduled time</li>
                        <li>Bring this printed examination slip to the venue</li>
                        <li>Bring a valid means of identification (National ID, Driver's License, or International Passport)</li>
                        <li>Bring writing materials (biro, pencil, eraser, ruler)</li>
                        <li>Mobile phones and other electronic devices are NOT allowed in the examination hall</li>
                        <li>Smartwatches and bluetooth devices are prohibited</li>
                        <li>Follow all instructions from examination officials</li>
                    </ul>
                </div>
                
                <!-- Action Buttons -->
                <div class="action-buttons">
                    <a href="/apply/print-exam-slip" target="_blank" class="btn btn-primary">
                        <i class="fas fa-print"></i> Print Slip
                    </a>
                    <a href="/apply/download-exam-slip" class="btn btn-success">
                        <i class="fas fa-download"></i> Download
                    </a>
                    <button onclick="shareSlip()" class="btn btn-outline">
                        <i class="fas fa-share-alt"></i> Share
                    </button>
                </div>
                
                <?php if (!empty($exam_slip['download_count'])): ?>
                <div class="download-count">
                    <i class="fas fa-download"></i>
                    Downloaded <?php echo $exam_slip['download_count']; ?> time(s)
                    <?php if (!empty($exam_slip['last_downloaded_at'])): ?>
                        | Last download: <?php echo date('M j, Y, g:i a', strtotime($exam_slip['last_downloaded_at'])); ?>
                    <?php endif; ?>
                </div>
                <?php endif; ?>
            </div>
            
            <!-- Footer -->
            <div class="slip-footer">
                <div class="security-badge">
                    <i class="fas fa-shield-alt"></i>
                    <span>Digitally Generated Document</span>
                </div>
                
                <p>
                    This slip was generated on <?php echo date('F j, Y \a\t g:i a'); ?><br>
                    FCT College of Nursing Sciences © <?php echo date('Y'); ?>. All rights reserved.
                </p>
                
                <p style="font-size: 10px; color: #999; margin-top: 10px;">
                    Slip Number: <?php echo htmlspecialchars($exam_slip['slip_number'] ?? 'N/A'); ?> |
                    Version: 1.0
                </p>
            </div>
        </div>
    </div>
    
    <!-- Modal for full-size QR code -->
    <div id="qrModal" style="display: none; position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0,0,0,0.8); z-index: 9999; align-items: center; justify-content: center;">
        <div style="background: white; padding: 30px; border-radius: 15px; max-width: 500px; text-align: center; position: relative;">
            <button onclick="closeQRModal()" style="position: absolute; top: 10px; right: 10px; background: none; border: none; font-size: 24px; cursor: pointer;">&times;</button>
            
            <h3 style="margin-bottom: 20px; color: #003366;">Examination Slip QR Code</h3>
            
            <div style="margin-bottom: 20px;">
                <?php if (!empty($exam_slip['slip_number'])): ?>
                <img src="/application-verify/generate-qr/<?php echo urlencode($exam_slip['slip_number']); ?>?t=<?php echo time(); ?>" 
                     style="width: 300px; height: 300px; border: 1px solid #ddd; padding: 10px; background: white;">
                <?php endif; ?>
            </div>
            
            <p style="color: #666; margin-bottom: 15px;">
                Slip Number: <strong><?php echo htmlspecialchars($exam_slip['slip_number'] ?? 'N/A'); ?></strong>
            </p>
            
            <div style="display: flex; gap: 10px; justify-content: center;">
                <button onclick="printQRCode()" class="btn btn-primary">
                    <i class="fas fa-print"></i> Print
                </button>
                <button onclick="closeQRModal()" class="btn btn-outline">
                    <i class="fas fa-times"></i> Close
                </button>
            </div>
        </div>
    </div>
    
    <script>
        // Show full-size QR code
        function showQRCode() {
            document.getElementById('qrModal').style.display = 'flex';
        }
        
        // Close QR modal
        function closeQRModal() {
            document.getElementById('qrModal').style.display = 'none';
        }
        
        // Handle QR code error
        function handleQRError(img) {
            console.error('QR code failed to load');
            img.parentNode.innerHTML = `
                <div class="qr-fallback">
                    <i class="fas fa-exclamation-triangle" style="color: #dc3545; font-size: 24px; margin-bottom: 10px;"></i>
                    <br>QR Code temporarily unavailable
                    <br><small style="color: #666;">Use verification link below</small>
                    <br><button onclick="refreshQR()" class="btn btn-sm btn-outline mt-2">
                        <i class="fas fa-sync-alt"></i> Retry
                    </button>
                </div>
            `;
        }
        
        // Refresh QR code
        function refreshQR() {
            const qrContainer = document.querySelector('.qr-code');
            const img = document.querySelector('#qrImage');
            const baseUrl = '<?php echo rtrim(defined('BASE_URL') ? BASE_URL : '/', '/'); ?>';
            const slipNumber = '<?php echo urlencode($exam_slip['slip_number'] ?? ''); ?>';
            
            if (!img) return;
            
            // Add loading state
            img.style.opacity = '0.5';
            
            // Generate new URL with fresh timestamp
            const newSrc = baseUrl + '/application-verify/generate-qr/' + slipNumber + '?t=' + new Date().getTime();
            
            // Set up one-time load handler
            img.onload = function() {
                console.log('QR code refreshed successfully');
                img.style.opacity = '1';
            };
            
            // Set new source
            img.src = newSrc;
            
            // Timeout fallback
            setTimeout(function() {
                if (img.style.opacity === '0.5') {
                    img.style.opacity = '1';
                }
            }, 5000);
        }
        
        // Print QR code
        function printQRCode() {
            const printWindow = window.open('', '_blank');
            printWindow.document.write(`
                <html>
                <head>
                    <title>QR Code - Examination Slip</title>
                    <style>
                        body { text-align: center; padding: 50px; font-family: Arial; }
                        h2 { color: #003366; }
                        .qr { margin: 30px auto; }
                        .slip-number { font-size: 18px; margin: 20px; color: #666; }
                        @media print {
                            body { padding: 20px; }
                        }
                    </style>
                </head>
                <body>
                    <h2>FCT College of Nursing Sciences</h2>
                    <h3>Examination Slip QR Code</h3>
                    <div class="qr">
                        <img src="/application-verify/generate-qr/<?php echo urlencode($exam_slip['slip_number'] ?? ''); ?>?t=<?php echo time(); ?>" 
                             style="width: 250px; height: 250px;">
                    </div>
                    <div class="slip-number">
                        Slip Number: <?php echo htmlspecialchars($exam_slip['slip_number'] ?? 'N/A'); ?>
                    </div>
                    <p>Generated on: <?php echo date('F j, Y \a\t g:i a'); ?></p>
                    <p><small>This QR code is unique to this examination slip</small></p>
                </body>
                </html>
            `);
            printWindow.document.close();
            printWindow.focus();
            setTimeout(function() {
                printWindow.print();
            }, 500);
        }
        
        // Share slip
        function shareSlip() {
            if (navigator.share) {
                navigator.share({
                    title: 'Examination Slip - FCT College of Nursing Sciences',
                    text: 'My examination slip number: <?php echo htmlspecialchars($exam_slip['slip_number'] ?? 'N/A'); ?>',
                    url: window.location.href
                }).catch(console.error);
            } else {
                alert('Copy your slip number: <?php echo htmlspecialchars($exam_slip['slip_number'] ?? 'N/A'); ?>');
            }
        }
        
        // Keyboard shortcuts
        document.addEventListener('keydown', function(e) {
            // Ctrl+P for print
            if ((e.ctrlKey || e.metaKey) && e.key === 'p') {
                e.preventDefault();
                window.open('/apply/print-exam-slip', '_blank');
            }
            
            // Escape to close modal
            if (e.key === 'Escape') {
                closeQRModal();
            }
        });
        
        // Auto-refresh QR code if it fails to load
        document.addEventListener('DOMContentLoaded', function() {
            const qrImages = document.querySelectorAll('.qr-code img');
            qrImages.forEach(img => {
                img.onerror = function() {
                    console.log('QR code failed to load, will retry...');
                    setTimeout(() => {
                        refreshQR();
                    }, 2000);
                };
            });
        });
    </script>
</body>
</html>