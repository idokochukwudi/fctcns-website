<?php
/**
 * Exam Slip Print View
 * Optimized for printing
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
    <title>Examination Slip - <?php echo htmlspecialchars($exam_slip['slip_number'] ?? ''); ?></title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Times New Roman', Times, serif;
            background: white;
            padding: 20px;
        }
        
        .slip {
            max-width: 800px;
            margin: 0 auto;
            border: 2px solid #000;
            padding: 30px;
            position: relative;
        }
        
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #000;
            padding-bottom: 20px;
        }
        
        .header h1 {
            font-size: 28px;
            font-weight: bold;
            text-transform: uppercase;
            margin-bottom: 5px;
        }
        
        .header h2 {
            font-size: 20px;
            font-weight: normal;
            margin-bottom: 5px;
        }
        
        .header h3 {
            font-size: 22px;
            font-weight: bold;
            margin-top: 15px;
            text-decoration: underline;
        }
        
        .info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
            margin: 25px 0;
        }
        
        .info-item {
            display: flex;
            flex-direction: column;
        }
        
        .info-label {
            font-size: 12px;
            font-weight: bold;
            color: #333;
            text-transform: uppercase;
        }
        
        .info-value {
            font-size: 16px;
            border-bottom: 1px dotted #999;
            padding: 5px 0;
        }
        
        .qr-section {
            text-align: center;
            margin: 30px 0;
            padding: 20px;
            border: 1px solid #ccc;
            background: #f9f9f9;
        }
        
        .qr-code {
            display: inline-block;
            margin: 15px 0;
        }
        
        .qr-code img {
            width: 150px;
            height: 150px;
        }
        
        .qr-fallback {
            font-family: monospace;
            font-size: 12px;
            word-break: break-all;
            padding: 10px;
            background: white;
            border: 1px solid #ccc;
        }
        
        .instructions {
            margin: 30px 0;
            padding: 20px;
            border: 1px solid #000;
            background: #fff;
        }
        
        .instructions h4 {
            font-size: 16px;
            font-weight: bold;
            margin-bottom: 15px;
            text-decoration: underline;
        }
        
        .instructions ol {
            padding-left: 20px;
        }
        
        .instructions li {
            margin-bottom: 8px;
            line-height: 1.4;
        }
        
        .footer {
            text-align: center;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #ccc;
            font-size: 11px;
        }
        
        .watermark {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) rotate(-45deg);
            font-size: 60px;
            color: rgba(0,0,0,0.05);
            white-space: nowrap;
            pointer-events: none;
            z-index: -1;
        }
        
        @media print {
            body {
                padding: 0;
            }
            
            .slip {
                border: 2px solid #000;
                box-shadow: none;
            }
            
            .qr-code img {
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
        }
    </style>
</head>
<body>
    <div class="slip">
        <div class="watermark">OFFICIAL EXAMINATION SLIP</div>
        
        <div class="header">
            <h1>FCT COLLEGE OF NURSING SCIENCES</h1>
            <h2>Gwagwalada, Abuja</h2>
            <h2>2025/2026 ADMISSIONS SCREENING</h2>
            <h3>EXAMINATION SLIP</h3>
        </div>
        
        <div class="info-grid">
            <div class="info-item">
                <span class="info-label">Slip Number</span>
                <span class="info-value"><?php echo htmlspecialchars($exam_slip['slip_number'] ?? 'N/A'); ?></span>
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
                <span class="info-label">Candidate Name</span>
                <span class="info-value">
                    <?php 
                    $name = trim(
                        ($applicant['title'] ?? '') . ' ' . 
                        ($applicant['first_name'] ?? '') . ' ' . 
                        ($applicant['last_name'] ?? '')
                    );
                    echo htmlspecialchars($name ?: 'N/A');
                    ?>
                </span>
            </div>
            
            <div class="info-item">
                <span class="info-label">Programme of Study</span>
                <span class="info-value"><?php echo htmlspecialchars($application['program_choice_1'] ?? 'N/A'); ?></span>
            </div>
            
            <div class="info-item">
                <span class="info-label">Examination Date</span>
                <span class="info-value">
                    <?php echo date('l, jS F Y', strtotime($exam_slip['exam_date'] ?? date('Y-m-d'))); ?>
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
                <span class="info-value"><?php echo htmlspecialchars($exam_slip['exam_venue'] ?? 'FCT College of Nursing Sciences, Gwagwalada'); ?></span>
            </div>
            
            <div class="info-item">
                <span class="info-label">Seat Number</span>
                <span class="info-value"><?php echo htmlspecialchars($exam_slip['seat_number'] ?? 'To be assigned on exam day'); ?></span>
            </div>
        </div>
        
        <div class="qr-section">
            <h4>VERIFICATION QR CODE</h4>
            <p style="margin-bottom: 10px; font-size: 12px;">
                Scan this QR code at the examination center to verify authenticity
            </p>
            
            <div class="qr-code">
                <?php
                $baseUrl = rtrim(defined('BASE_URL') ? BASE_URL : '/', '/');
                $qrUrl = $baseUrl . '/application-verify/generate-qr/' . urlencode($exam_slip['slip_number']) . '?t=' . time();
                $verificationUrl = $baseUrl . '/application-verify/slip/' . urlencode($exam_slip['slip_number']);
                ?>
                <img src="<?php echo htmlspecialchars($qrUrl); ?>" 
                     alt="QR Code" 
                     onerror="this.onerror=null; this.parentNode.innerHTML='<div class=\'qr-fallback\'>Verification URL:<br><?php echo htmlspecialchars($verificationUrl); ?></div>';">
            </div>
            
            <p style="margin-top: 10px; font-size: 10px; font-family: monospace;">
                <?php echo htmlspecialchars($verificationUrl); ?>
            </p>
        </div>
        
        <div class="instructions">
            <h4>IMPORTANT INSTRUCTIONS</h4>
            <ol>
                <?php 
                $instructions = explode("\n", $exam_slip['instructions'] ?? '1. Arrive at least 1 hour before examination time
2. Bring this printed slip and a valid means of identification
3. Bring writing materials (biro, pencil, eraser)
4. Mobile phones and electronic devices are NOT allowed
5. Follow all instructions from examination officials');
                
                foreach ($instructions as $instruction):
                    if (trim($instruction)):
                ?>
                <li><?php echo htmlspecialchars(trim($instruction)); ?></li>
                <?php 
                    endif;
                endforeach; 
                ?>
            </ol>
        </div>
        
        <div style="margin: 20px 0; font-size: 12px;">
            <p><strong>Note:</strong> This slip is computer-generated and does not require a signature.</p>
        </div>
        
        <div class="footer">
            <p>
                Generated on: <?php echo date('jS F Y, h:i A'); ?><br>
                Download count: <?php echo $exam_slip['download_count'] ?? 0; ?>
            </p>
            <p style="margin-top: 5px;">
                This is an official document of FCT College of Nursing Sciences. Any alteration invalidates this slip.
            </p>
        </div>
    </div>
    
    <script>
        // Auto-print when page loads
        window.onload = function() {
            // Small delay to ensure images load
            setTimeout(function() {
                window.print();
            }, 500);
        };
        
        // After printing, redirect back to step 4
        window.onafterprint = function() {
            window.location.href = '/apply/step/4';
        };
        
        // Fallback for browsers that don't support onafterprint
        setTimeout(function() {
            if (document.hasFocus()) {
                // User is still here, maybe print didn't happen
                console.log('Print may not have been triggered');
            }
        }, 10000);
    </script>
</body>
</html>