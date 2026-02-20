<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Examination Slip - <?php echo htmlspecialchars($exam_slip['slip_number']); ?></title>
    <style>
        /* Print-specific styles */
        @page {
            size: A4;
            margin: 1cm;
        }
        
        body {
            font-family: 'Times New Roman', Times, serif;
            line-height: 1.4;
            color: #000;
            background: #fff;
            padding: 0;
            margin: 0;
        }
        
        .slip-container {
            max-width: 800px;
            margin: 0 auto;
            border: 2px solid #000;
            padding: 20px;
        }
        
        .header {
            text-align: center;
            border-bottom: 2px solid #000;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }
        
        .header h1 {
            font-size: 24px;
            font-weight: bold;
            margin: 5px 0;
            text-transform: uppercase;
        }
        
        .header h2 {
            font-size: 18px;
            margin: 5px 0;
        }
        
        .header h3 {
            font-size: 16px;
            margin: 5px 0;
        }
        
        .photo-qr-section {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
        }
        
        .photo-box, .qr-box {
            width: 120px;
            height: 120px;
            border: 1px solid #000;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .photo-box img, .qr-box img {
            max-width: 100%;
            max-height: 100%;
        }
        
        .details-table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        
        .details-table td, .details-table th {
            border: 1px solid #000;
            padding: 8px;
            text-align: left;
        }
        
        .details-table th {
            background: #f0f0f0;
            font-weight: bold;
            width: 40%;
        }
        
        .instructions {
            border: 1px solid #000;
            padding: 10px;
            margin: 20px 0;
            background: #f9f9f9;
        }
        
        .footer {
            text-align: center;
            font-size: 12px;
            margin-top: 20px;
            padding-top: 10px;
            border-top: 1px solid #ccc;
        }
        
        .signature {
            margin-top: 30px;
            display: flex;
            justify-content: space-between;
        }
        
        .signature-line {
            border-top: 1px solid #000;
            width: 200px;
            text-align: center;
            padding-top: 5px;
        }
        
        .badge {
            display: inline-block;
            padding: 3px 8px;
            border: 1px solid #000;
            font-size: 12px;
        }
        
        .qr-code {
            text-align: center;
        }
        
        .qr-code p {
            margin: 5px 0 0;
            font-size: 10px;
        }
        
        @media print {
            .no-print {
                display: none;
            }
        }
    </style>
</head>
<body>
    <div class="slip-container">
        <!-- Official Header -->
        <div class="header">
            <h1>FCT COLLEGE OF NURSING SCIENCES</h1>
            <h2>Gwagwalada, Abuja</h2>
            <h3>2025/2026 ADMISSIONS SCREENING</h3>
            <div style="margin-top: 10px;">
                <span class="badge">OFFICIAL EXAMINATION SLIP</span>
            </div>
        </div>
        
        <!-- Photo and QR Code Section -->
        <div class="photo-qr-section">
            <div class="photo-box">
                <?php if (!empty($application['passport_photo'])): ?>
                    <img src="<?php echo $_SERVER['DOCUMENT_ROOT'] . $application['passport_photo']; ?>" 
                         alt="Passport" style="max-width: 100%;">
                <?php else: ?>
                    <span>No Photo</span>
                <?php endif; ?>
            </div>
            
            <div class="qr-box">
                <?php if (!empty($exam_slip['qr_code'])): ?>
                    <img src="<?php echo $exam_slip['qr_code']; ?>" alt="QR Code">
                <?php else: ?>
                    <div class="qr-placeholder">
                        <!-- QR code will be generated here -->
                        <div id="qrContainer"></div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
        
        <!-- Candidate Details Table -->
        <table class="details-table">
            <tr>
                <th>Slip Number:</th>
                <td><strong><?php echo htmlspecialchars($exam_slip['slip_number']); ?></strong></td>
            </tr>
            <tr>
                <th>Application Number:</th>
                <td><?php echo htmlspecialchars($application['application_number']); ?></td>
            </tr>
            <tr>
                <th>JAMB Registration Number:</th>
                <td><?php echo htmlspecialchars($application['jamb_number']); ?></td>
            </tr>
            <tr>
                <th>Full Name:</th>
                <td><?php echo htmlspecialchars($application['first_name'] . ' ' . $application['last_name']); ?></td>
            </tr>
            <tr>
                <th>Programme of Study:</th>
                <td><?php echo htmlspecialchars($application['program_choice_1']); ?></td>
            </tr>
            <tr>
                <th>Examination Date:</th>
                <td><?php echo date('l, jS F Y', strtotime($exam_slip['exam_date'])); ?></td>
            </tr>
            <tr>
                <th>Examination Time:</th>
                <td><?php echo date('h:i A', strtotime($exam_slip['exam_time'])); ?></td>
            </tr>
            <tr>
                <th>Reporting Time:</th>
                <td><?php echo date('h:i A', strtotime($exam_slip['reporting_time'])); ?> (Arrive 30 mins early)</td>
            </tr>
            <tr>
                <th>Venue:</th>
                <td><?php echo htmlspecialchars($exam_slip['exam_venue']); ?></td>
            </tr>
            <tr>
                <th>Seat Number:</th>
                <td><strong><?php echo htmlspecialchars($exam_slip['seat_number']); ?></strong></td>
            </tr>
        </table>
        
        <!-- Important Instructions -->
        <div class="instructions">
            <strong>IMPORTANT INSTRUCTIONS:</strong>
            <ol style="margin: 10px 0 0 20px; padding: 0;">
                <li>Bring this printed slip to the examination venue</li>
                <li>Arrive at least 30 minutes before the reporting time</li>
                <li>Bring your writing materials (pen, pencil, eraser)</li>
                <li>Bring a valid means of identification (National ID, Driver's License, or International Passport)</li>
                <li>Electronic devices (phones, calculators, smartwatches) are strictly prohibited</li>
                <li>The QR code on this slip will be scanned for verification at the entrance</li>
                <li>Latecomers will not be allowed into the examination hall</li>
            </ol>
        </div>
        
        <!-- Signature and Verification Section -->
        <div class="signature">
            <div class="signature-line">
                Chairman, Admissions Committee
            </div>
            <div class="signature-line">
                Registrar
            </div>
        </div>
        
        <!-- Footer -->
        <div class="footer">
            <p>This slip is computer-generated and does not require a physical signature.</p>
            <p>Generated on: <?php echo date('jS F Y, h:i A', strtotime($exam_slip['generated_at'])); ?></p>
            <p>Download count: <?php echo $exam_slip['download_count'] ?? 0; ?></p>
            <p style="font-size: 10px; margin-top: 10px;">
                Verify this slip at: <?php echo BASE_URL; ?>/verify/slip/<?php echo $exam_slip['slip_number']; ?>
            </p>
        </div>
    </div>
    
    <!-- QR Code Generation Script (if not already generated) -->
    <?php if (empty($exam_slip['qr_code'])): ?>
    <script src="https://cdn.jsdelivr.net/npm/qrcode@1.5.1/build/qrcode.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const verificationUrl = '<?php echo BASE_URL; ?>/verify/slip/<?php echo $exam_slip['slip_number']; ?>';
            
            QRCode.toCanvas(document.getElementById('qrContainer'), verificationUrl, {
                width: 100,
                margin: 1
            }, function(error) {
                if (error) console.error(error);
            });
        });
    </script>
    <?php endif; ?>
    
    <!-- Auto-print on load -->
    <script>
        window.onload = function() {
            window.print();
        }
    </script>
</body>
</html>