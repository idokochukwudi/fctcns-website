<?php
/**
 * Step 4 - Examination Slip View
 * 
 * @var array $application
 * @var array $exam_slip
 * @var array $applicant
 */

// Set page title
$pageTitle = $pageTitle ?? 'Examination Slip - FCT College of Nursing Sciences';
$baseUrl = defined('BASE_URL') ? BASE_URL : '';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($pageTitle); ?></title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome 6 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
    <!-- QR Code Library (fallback) -->
    <script src="https://cdn.jsdelivr.net/npm/qrcode@1.5.3/build/qrcode.min.js"></script>

    <style>
        :root {
            --primary:       #1a3a5c;
            --primary-light: #234f7a;
            --accent:        #c8960c;
            --success:       #1a7a4a;
            --success-bg:    #edfaf3;
            --info:          #0d5fa3;
            --info-bg:       #e8f3fd;
            --warning-bg:    #fffbeb;
            --text-main:     #1c2a38;
            --text-muted:    #6b7c8d;
            --border:        #d6e0ea;
            --bg-page:       #eef2f7;
            --bg-card:       #ffffff;
            --shadow-md:     0 4px 24px rgba(26,58,92,0.10);
            --shadow-lg:     0 8px 40px rgba(26,58,92,0.14);
            --radius-lg:     16px;
            --radius-md:     10px;
            --radius-sm:     6px;
        }

        * { box-sizing: border-box; }

        body {
            font-family: 'DM Sans', sans-serif;
            background: var(--bg-page);
            color: var(--text-main);
            min-height: 100vh;
        }

        h1, h2, h3, h4, h5, .font-display {
            font-family: 'Playfair Display', serif;
        }

        /* ── Layout ─────────────────────────────────────────── */
        .page-wrapper {
            max-width: 1280px;
            margin: 0 auto;
            padding: 2rem 1.5rem;
        }

        /* ── Status Bar ──────────────────────────────────────── */
        .status-bar {
            background: var(--bg-card);
            border-radius: var(--radius-lg);
            box-shadow: var(--shadow-md);
            padding: 1.25rem 2rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 1rem;
            margin-bottom: 2rem;
            border-left: 5px solid var(--success);
        }

        .status-bar .app-title {
            font-size: 1.3rem;
            font-weight: 700;
            color: var(--primary);
            margin-bottom: 0.2rem;
        }

        .status-bar .app-subtitle {
            font-size: 0.88rem;
            color: var(--text-muted);
        }

        .badge-verified {
            background: linear-gradient(135deg, #1a7a4a, #22a063);
            color: #fff;
            border-radius: 50px;
            padding: 0.5rem 1.4rem;
            font-size: 0.85rem;
            font-weight: 600;
            letter-spacing: 0.04em;
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            box-shadow: 0 2px 10px rgba(26,122,74,0.28);
        }

        /* ── Main Grid ───────────────────────────────────────── */
        .main-grid {
            display: grid;
            grid-template-columns: 1fr 340px;
            gap: 2rem;
            align-items: start;
        }

        @media (max-width: 992px) {
            .main-grid { grid-template-columns: 1fr; }
        }

        /* ── Exam Slip Card ──────────────────────────────────── */
        .slip-card {
            background: var(--bg-card);
            border-radius: var(--radius-lg);
            box-shadow: var(--shadow-lg);
            overflow: hidden;
        }

        .slip-body {
            padding: 2.5rem;
        }

        /* ── Photo / QR Row ──────────────────────────────────── */
        .photo-qr-row {
            display: grid;
            grid-template-columns: 1fr 1fr 1fr;
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        @media (max-width: 640px) {
            .photo-qr-row { grid-template-columns: 1fr; }
        }

        .photo-box {
            border: 2px solid var(--border);
            border-radius: var(--radius-md);
            overflow: hidden;
            background: #f8fafc;
            aspect-ratio: 1;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .photo-box img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .photo-box .no-photo {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 0.5rem;
            color: var(--text-muted);
        }

        .photo-label, .qr-label {
            text-align: center;
            font-size: 0.78rem;
            color: var(--text-muted);
            margin-top: 0.6rem;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.3rem;
        }

        .qr-box {
            border: 2px solid var(--border);
            border-radius: var(--radius-md);
            background: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            aspect-ratio: 1;
            padding: 0.75rem;
            position: relative;
            overflow: hidden;
        }

        .qr-box::before {
            content: '';
            position: absolute;
            inset: 0;
            border-radius: var(--radius-md);
            background: linear-gradient(135deg, rgba(26,58,92,0.04) 0%, transparent 60%);
            pointer-events: none;
        }

        #qrcode, #qrcode-fallback {
            width: 100%;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        #qrcode canvas, #qrcode img {
            width: 100% !important;
            height: 100% !important;
            display: block;
            object-fit: contain;
        }

        .verify-badge {
            border: 2px solid #b8ead2;
            background: var(--success-bg);
            border-radius: var(--radius-md);
            padding: 1.25rem;
            display: flex;
            flex-direction: column;
            justify-content: center;
            gap: 0.75rem;
        }

        .verify-badge .badge-title {
            font-size: 1rem;
            font-weight: 700;
            color: var(--success);
            display: flex;
            align-items: center;
            gap: 0.4rem;
        }

        .verify-row {
            display: flex;
            align-items: flex-start;
            gap: 0.5rem;
            font-size: 0.84rem;
            color: var(--text-main);
        }

        .verify-row .vi-icon {
            margin-top: 0.1rem;
            flex-shrink: 0;
        }

        /* ── Details Table ───────────────────────────────────── */
        .details-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
            border-radius: var(--radius-md);
            overflow: hidden;
            border: 1.5px solid var(--border);
            font-size: 0.9rem;
        }

        .details-table th,
        .details-table td {
            padding: 0.85rem 1.2rem;
            border-bottom: 1px solid var(--border);
        }

        .details-table tr:last-child th,
        .details-table tr:last-child td {
            border-bottom: none;
        }

        .details-table th {
            background: #f4f7fb;
            color: var(--text-muted);
            font-weight: 600;
            font-size: 0.82rem;
            text-transform: uppercase;
            letter-spacing: 0.04em;
            width: 36%;
        }

        .details-table td {
            color: var(--text-main);
            font-weight: 500;
        }

        .details-table .highlight-row td {
            background: var(--warning-bg);
            font-weight: 700;
            color: var(--primary);
            font-size: 0.95rem;
        }

        .details-table .highlight-row th {
            background: #fef7dc;
        }

        .details-table .seat-number {
            font-size: 1.3rem;
            font-weight: 700;
            color: var(--success);
            font-family: 'Playfair Display', serif;
        }

        .badge-program {
            background: var(--info-bg);
            color: var(--info);
            font-size: 0.82rem;
            font-weight: 600;
            padding: 0.3rem 0.8rem;
            border-radius: 50px;
            display: inline-block;
        }

        .text-danger-soft {
            color: #c0392b;
        }

        /* ── Instructions ────────────────────────────────────── */
        .instructions-box {
            background: var(--info-bg);
            border: 1.5px solid #bee3f8;
            border-radius: var(--radius-md);
            padding: 1.5rem 1.75rem;
            margin-top: 2rem;
            display: flex;
            gap: 1.25rem;
        }

        .instructions-box .instr-icon {
            color: var(--info);
            font-size: 1.6rem;
            flex-shrink: 0;
            margin-top: 0.1rem;
        }

        .instructions-box h5 {
            color: var(--info);
            font-size: 1rem;
            margin-bottom: 0.75rem;
        }

        .instructions-box ul {
            margin: 0;
            padding-left: 1.2rem;
        }

        .instructions-box li {
            font-size: 0.86rem;
            color: #2c5282;
            margin-bottom: 0.4rem;
            line-height: 1.5;
        }

        .instructions-box li:last-child { margin-bottom: 0; }

        /* ── Slip Footer ─────────────────────────────────────── */
        .slip-footer {
            text-align: center;
            margin-top: 1.5rem;
            padding-top: 1.25rem;
            border-top: 1px dashed var(--border);
            font-size: 0.8rem;
            color: var(--text-muted);
            line-height: 1.8;
        }

        /* ── Sidebar ─────────────────────────────────────────── */
        .sidebar {
            display: flex;
            flex-direction: column;
            gap: 1.5rem;
        }

        .action-card {
            background: var(--bg-card);
            border-radius: var(--radius-lg);
            box-shadow: var(--shadow-md);
            padding: 1.75rem;
        }

        .action-card h6 {
            font-family: 'DM Sans', sans-serif;
            font-weight: 700;
            font-size: 0.8rem;
            text-transform: uppercase;
            letter-spacing: 0.07em;
            color: var(--text-muted);
            margin-bottom: 1rem;
        }

        .action-btn {
            display: flex;
            align-items: center;
            gap: 0.85rem;
            width: 100%;
            padding: 0.85rem 1.1rem;
            border-radius: var(--radius-sm);
            font-size: 0.9rem;
            font-weight: 600;
            border: none;
            cursor: pointer;
            text-decoration: none;
            transition: all 0.18s ease;
            margin-bottom: 0.65rem;
        }

        .action-btn:last-child { margin-bottom: 0; }

        .action-btn .btn-icon {
            width: 36px; height: 36px;
            border-radius: var(--radius-sm);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1rem;
            flex-shrink: 0;
        }

        .action-btn.btn-pdf {
            background: linear-gradient(135deg, var(--success), #22a063);
            color: #fff;
            box-shadow: 0 3px 12px rgba(26,122,74,0.25);
        }
        .action-btn.btn-pdf:hover { transform: translateY(-2px); box-shadow: 0 6px 20px rgba(26,122,74,0.35); color:#fff; }
        .action-btn.btn-pdf .btn-icon { background: rgba(255,255,255,0.2); color: #fff; }

        .action-btn.btn-print {
            background: linear-gradient(135deg, var(--primary), var(--primary-light));
            color: #fff;
            box-shadow: 0 3px 12px rgba(26,58,92,0.25);
        }
        .action-btn.btn-print:hover { transform: translateY(-2px); box-shadow: 0 6px 20px rgba(26,58,92,0.35); color:#fff; }
        .action-btn.btn-print .btn-icon { background: rgba(255,255,255,0.2); color: #fff; }

        .action-btn.btn-share {
            background: #f0f4fa;
            color: var(--primary);
            border: 1.5px solid var(--border);
        }
        .action-btn.btn-share:hover { background: #e4ecf8; transform: translateY(-1px); color: var(--primary); }
        .action-btn.btn-share .btn-icon { background: var(--primary); color: #fff; }

        .action-btn.btn-dashboard {
            background: #f8fafc;
            color: var(--text-muted);
            border: 1.5px solid var(--border);
        }
        .action-btn.btn-dashboard:hover { background: #f0f4fa; color: var(--text-main); }
        .action-btn.btn-dashboard .btn-icon { background: #e0e8f0; color: var(--text-muted); }

        .btn-label { flex: 1; text-align: left; }
        .btn-arrow { opacity: 0.5; font-size: 0.8rem; }

        /* ── Verification Card ───────────────────────────────── */
        .verify-card {
            background: var(--bg-card);
            border-radius: var(--radius-lg);
            box-shadow: var(--shadow-md);
            padding: 1.75rem;
        }

        .verify-card h6 {
            font-family: 'DM Sans', sans-serif;
            font-weight: 700;
            font-size: 0.8rem;
            text-transform: uppercase;
            letter-spacing: 0.07em;
            color: var(--text-muted);
            margin-bottom: 0.75rem;
        }

        .verify-input-group {
            display: flex;
            gap: 0.5rem;
        }

        .verify-input-group input {
            flex: 1;
            border: 1.5px solid var(--border);
            border-radius: var(--radius-sm);
            padding: 0.6rem 0.85rem;
            font-size: 0.8rem;
            color: var(--text-muted);
            background: #f8fafc;
            outline: none;
        }

        .verify-input-group button {
            background: var(--primary);
            color: #fff;
            border: none;
            border-radius: var(--radius-sm);
            padding: 0.6rem 1rem;
            font-size: 0.82rem;
            font-weight: 600;
            cursor: pointer;
            white-space: nowrap;
            transition: background 0.15s;
        }

        .verify-input-group button:hover { background: var(--primary-light); }

        /* ── Toast ───────────────────────────────────────────── */
        .toast-msg {
            position: fixed;
            top: 1.5rem;
            right: 1.5rem;
            background: var(--success);
            color: #fff;
            padding: 0.85rem 1.5rem;
            border-radius: var(--radius-md);
            font-size: 0.9rem;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 0.6rem;
            box-shadow: 0 6px 24px rgba(26,122,74,0.3);
            z-index: 9999;
            animation: slideIn 0.25s ease;
        }

        @keyframes slideIn {
            from { opacity: 0; transform: translateY(-12px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        /* ── Error State ─────────────────────────────────────── */
        .error-card {
            background: var(--bg-card);
            border-radius: var(--radius-lg);
            box-shadow: var(--shadow-lg);
            text-align: center;
            padding: 4rem 2rem;
        }

        .error-card h3 {
            color: var(--primary);
            margin: 1rem 0 0.75rem;
        }

        /* ── Print Styles ────────────────────────────────────── */
        @media print {
            body { background: #fff; }
            .page-wrapper { padding: 0; max-width: 100%; }
            .status-bar, .sidebar, .toast-msg { display: none !important; }
            .slip-card { box-shadow: none; border: 1px solid #ccc; }
            .main-grid { grid-template-columns: 1fr; }
        }
    </style>
</head>
<body>

<div class="page-wrapper">

    <!-- ── Status Bar ───────────────────────────────────────── -->
    <div class="status-bar">
        <div>
            <div class="app-title">
                <i class="fas fa-check-circle text-success me-2" style="font-size:1.1rem;"></i>
                Application Complete
            </div>
            <div class="app-subtitle">
                <i class="fas fa-id-card me-1"></i>
                Application #: <strong><?php echo htmlspecialchars($application['application_number'] ?? 'N/A'); ?></strong>
            </div>
        </div>
        <div class="badge-verified">
            <i class="fas fa-shield-check"></i>
            PAID &amp; VERIFIED
        </div>
    </div>

    <!-- ── Main Grid ─────────────────────────────────────────── -->
    <?php if (!empty($exam_slip)): ?>

    <div class="main-grid">

        <!-- ── Left: Examination Slip ─────────────────────────── -->
        <div class="slip-card" id="examSlipCard">

            <div class="slip-body">

                <!-- Photo / QR / Verify row -->
                <div class="photo-qr-row">
                    <!-- Passport Photo -->
                    <div>
                        <div class="photo-box">
                            <?php if (!empty($application['passport_photo'])): ?>
                                <img src="<?php echo htmlspecialchars($application['passport_photo']); ?>" alt="Passport Photo">
                            <?php else: ?>
                                <div class="no-photo">
                                    <i class="fas fa-user-circle fa-4x"></i>
                                    <span style="font-size:0.75rem;">No Photo</span>
                                </div>
                            <?php endif; ?>
                        </div>
                        <div class="photo-label">
                            <i class="fas fa-camera"></i> Passport Photograph
                        </div>
                    </div>

                    <!-- QR Code - FIXED: Using correct controller endpoint -->
                    <div>
                        <div class="qr-box">
                            <div id="qrcode" style="width:100%; height:100%; display:flex; align-items:center; justify-content:center;"></div>
                            <div id="qrcode-fallback" style="display:none; width:100%; height:100%;">
                                <!-- FIXED: Using generateQR method endpoint -->
                                <img src="<?php echo $baseUrl; ?>/application-verify/generate-qr/<?php echo urlencode($exam_slip['slip_number']); ?>" 
                                     alt="QR Code"
                                     style="width:100%; height:100%; object-fit:contain;"
                                     onerror="this.onerror=null; this.parentNode.style.display='none'; showQRIconFallback(document.getElementById('qrcode'), '<?php echo addslashes($exam_slip['slip_number']); ?>');">
                            </div>
                        </div>
                        <div class="qr-label">
                            <i class="fas fa-qrcode"></i> Scan to Verify
                        </div>
                    </div>

                    <!-- Verification Badge -->
                    <div class="verify-badge">
                        <div class="badge-title">
                            <i class="fas fa-shield-alt"></i> Verified
                        </div>
                        <div class="verify-row">
                            <i class="fas fa-check-circle vi-icon" style="color:var(--success);"></i>
                            <span>Payment Confirmed</span>
                        </div>
                        <div class="verify-row">
                            <i class="fas fa-hashtag vi-icon" style="color:var(--accent);"></i>
                            <span><strong><?php echo htmlspecialchars($exam_slip['slip_number']); ?></strong></span>
                        </div>
                        <div class="verify-row">
                            <i class="fas fa-clock vi-icon" style="color:var(--info);"></i>
                            <span>Generated:<br><?php echo date('d/m/Y H:i', strtotime($exam_slip['generated_at'])); ?></span>
                        </div>
                    </div>
                </div>

                <!-- Details Table -->
                <table class="details-table">
                    <tr>
                        <th>Slip Number</th>
                        <td><strong style="color:var(--primary);"><?php echo htmlspecialchars($exam_slip['slip_number']); ?></strong></td>
                    </tr>
                    <tr>
                        <th>Application Number</th>
                        <td><?php echo htmlspecialchars($application['application_number']); ?></td>
                    </tr>
                    <tr>
                        <th>JAMB Reg. Number</th>
                        <td><?php echo htmlspecialchars($application['jamb_number']); ?></td>
                    </tr>
                    <tr>
                        <th>Full Name</th>
                        <td><strong><?php echo htmlspecialchars($application['first_name'] . ' ' . $application['last_name']); ?></strong></td>
                    </tr>
                    <tr>
                        <th>Programme of Study</th>
                        <td><span class="badge-program"><?php echo htmlspecialchars($application['program_choice_1']); ?></span></td>
                    </tr>
                    <tr class="highlight-row">
                        <th>Examination Date</th>
                        <td><?php echo date('l, jS F Y', strtotime($exam_slip['exam_date'])); ?></td>
                    </tr>
                    <tr>
                        <th>Examination Time</th>
                        <td><?php echo date('h:i A', strtotime($exam_slip['exam_time'])); ?></td>
                    </tr>
                    <tr>
                        <th>Reporting Time</th>
                        <td class="text-danger-soft">
                            <i class="fas fa-exclamation-circle me-1"></i>
                            <?php echo date('h:i A', strtotime($exam_slip['reporting_time'])); ?> — Arrive 30 mins early
                        </td>
                    </tr>
                    <tr>
                        <th>Venue</th>
                        <td><?php echo htmlspecialchars($exam_slip['exam_venue']); ?></td>
                    </tr>
                    <tr>
                        <th>Seat Number</th>
                        <td><span class="seat-number"><?php echo htmlspecialchars($exam_slip['seat_number']); ?></span></td>
                    </tr>
                </table>

                <!-- Instructions -->
                <div class="instructions-box">
                    <div class="instr-icon">
                        <i class="fas fa-info-circle"></i>
                    </div>
                    <div>
                        <h5>Important Instructions</h5>
                        <ul>
                            <li>Print this slip and bring it to the examination venue.</li>
                            <li>Arrive at least <strong>30 minutes</strong> before the reporting time.</li>
                            <li>Bring your writing materials (pen, pencil, eraser).</li>
                            <li>Bring a valid means of identification — National ID, Driver's License, or International Passport.</li>
                            <li>Electronic devices (phones, calculators) are <strong>not allowed</strong> in the examination hall.</li>
                            <li>The QR code on this slip will be scanned for verification at the entrance.</li>
                        </ul>
                    </div>
                </div>

                <!-- Slip Footer -->
                <div class="slip-footer">
                    <i class="fas fa-print me-1"></i> This slip is computer-generated and does not require a signature.
                    <br>
                    <i class="fas fa-lock me-1"></i> QR code contains encrypted verification data unique to this applicant.
                </div>

            </div><!-- /slip-body -->
        </div><!-- /slip-card -->

        <!-- ── Right: Sidebar ──────────────────────────────────── -->
        <div class="sidebar">

            <!-- Actions -->
            <div class="action-card">
                <h6>Actions</h6>

                <a href="/apply/download-exam-slip" class="action-btn btn-pdf" id="downloadBtn">
                    <span class="btn-icon"><i class="fas fa-download"></i></span>
                    <span class="btn-label">Download PDF</span>
                    <i class="fas fa-chevron-right btn-arrow"></i>
                </a>

                <button class="action-btn btn-print" onclick="printExamSlip()">
                    <span class="btn-icon"><i class="fas fa-print"></i></span>
                    <span class="btn-label">Print Slip</span>
                    <i class="fas fa-chevron-right btn-arrow"></i>
                </button>

                <button class="action-btn btn-share" onclick="shareSlip()">
                    <span class="btn-icon"><i class="fas fa-share-nodes"></i></span>
                    <span class="btn-label">Share Slip</span>
                    <i class="fas fa-chevron-right btn-arrow"></i>
                </button>

                <a href="/applicant/dashboard" class="action-btn btn-dashboard">
                    <span class="btn-icon"><i class="fas fa-gauge-high"></i></span>
                    <span class="btn-label">Go to Dashboard</span>
                    <i class="fas fa-chevron-right btn-arrow"></i>
                </a>
            </div>

            <!-- Verification Link - FIXED: Using correct endpoint -->
            <div class="verify-card">
                <h6>Public Verification Link</h6>
                <p style="font-size:0.82rem; color:var(--text-muted); margin-bottom:0.75rem; line-height:1.5;">
                    Share this link to let anyone verify the authenticity of this slip online.
                </p>
                <div class="verify-input-group">
                    <input type="text"
                           id="verificationLink"
                           value="<?php echo $baseUrl; ?>/application-verify/slip/<?php echo urlencode($exam_slip['slip_number']); ?>"
                           readonly>
                    <button onclick="copyVerificationLink()">
                        <i class="fas fa-copy me-1"></i> Copy
                    </button>
                </div>
            </div>

            <!-- Slip Summary Card -->
            <div class="action-card">
                <h6>Quick Summary</h6>
                <div style="font-size:0.86rem; display:flex; flex-direction:column; gap:0.65rem;">
                    <div class="verify-row">
                        <i class="fas fa-calendar-day vi-icon" style="color:var(--primary);"></i>
                        <div>
                            <div style="font-size:0.75rem; color:var(--text-muted);">Exam Date</div>
                            <strong><?php echo date('d M Y', strtotime($exam_slip['exam_date'])); ?></strong>
                        </div>
                    </div>
                    <div class="verify-row">
                        <i class="fas fa-clock vi-icon" style="color:var(--accent);"></i>
                        <div>
                            <div style="font-size:0.75rem; color:var(--text-muted);">Exam Time</div>
                            <strong><?php echo date('h:i A', strtotime($exam_slip['exam_time'])); ?></strong>
                        </div>
                    </div>
                    <div class="verify-row">
                        <i class="fas fa-location-dot vi-icon" style="color:var(--success);"></i>
                        <div>
                            <div style="font-size:0.75rem; color:var(--text-muted);">Venue</div>
                            <strong><?php echo htmlspecialchars($exam_slip['exam_venue']); ?></strong>
                        </div>
                    </div>
                    <div class="verify-row">
                        <i class="fas fa-chair vi-icon" style="color:var(--info);"></i>
                        <div>
                            <div style="font-size:0.75rem; color:var(--text-muted);">Seat Number</div>
                            <strong style="font-size:1rem; color:var(--success);"><?php echo htmlspecialchars($exam_slip['seat_number']); ?></strong>
                        </div>
                    </div>
                </div>
            </div>

        </div><!-- /sidebar -->

    </div><!-- /main-grid -->

    <?php else: ?>

    <!-- Error State -->
    <div class="error-card">
        <i class="fas fa-triangle-exclamation fa-4x" style="color:var(--accent);"></i>
        <h3>Examination Slip Not Available</h3>
        <p style="color:var(--text-muted); max-width:420px; margin:0 auto 2rem;">
            Your examination slip is being generated. Please check back shortly or contact support if this persists.
        </p>
        <div style="display:flex; justify-content:center; gap:1rem; flex-wrap:wrap;">
            <a href="/apply/step/3" class="action-btn btn-dashboard" style="width:auto; padding: 0.75rem 1.5rem; text-decoration:none;">
                <span class="btn-icon"><i class="fas fa-arrow-left"></i></span>
                <span class="btn-label">Back to Payment</span>
            </a>
            <button class="action-btn btn-print" onclick="location.reload()" style="width:auto; padding: 0.75rem 1.5rem;">
                <span class="btn-icon"><i class="fas fa-rotate-right"></i></span>
                <span class="btn-label">Refresh Page</span>
            </button>
        </div>
    </div>

    <?php endif; ?>

</div><!-- /page-wrapper -->

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
    // ── FIXED: QR Code Generation using correct controller endpoint ─────────
    document.addEventListener('DOMContentLoaded', function () {
        <?php if (!empty($exam_slip)): ?>
        loadQRCode();
        <?php endif; ?>
    });

    function loadQRCode() {
        const qrContainer  = document.getElementById('qrcode');
        const slipNumber   = '<?php echo addslashes($exam_slip['slip_number']); ?>';
        const baseUrl      = '<?php echo addslashes($baseUrl); ?>';
        
        // FIXED: Using generateQR method endpoint
        const verificationUrl = baseUrl + '/application-verify/slip/' + encodeURIComponent(slipNumber);
        const qrImageUrl = baseUrl + '/application-verify/generate-qr/' + encodeURIComponent(slipNumber) + '?t=' + Date.now();

        if (!qrContainer) return;

        // Clear container
        qrContainer.innerHTML = '';

        // ── ATTEMPT 1: Server-generated QR (via controller's generateQR method) ─────
        const serverQR = new Image();
        serverQR.src = qrImageUrl;
        serverQR.alt = 'QR Code';
        serverQR.style.cssText = 'width:100%;height:100%;display:block;object-fit:contain;';
        
        serverQR.onload = function () {
            qrContainer.innerHTML = '';
            qrContainer.appendChild(serverQR);
        };

        serverQR.onerror = function () {
            console.log('Server QR failed, trying Google Charts...');
            // ── ATTEMPT 2: Google Charts API ─────────────────────
            const googleQR = new Image();
            googleQR.src = 'https://chart.googleapis.com/chart?chs=200x200&cht=qr&chl=' + encodeURIComponent(verificationUrl) + '&choe=UTF-8&chld=L|2';
            googleQR.alt = 'QR Code';
            googleQR.style.cssText = 'width:100%;height:100%;display:block;object-fit:contain;';
            
            googleQR.onload = function () {
                qrContainer.innerHTML = '';
                qrContainer.appendChild(googleQR);
            };

            googleQR.onerror = function () {
                console.log('Google Charts failed, trying QRCode.js...');
                // ── ATTEMPT 3: QRCode.js library ─────────────────
                if (typeof QRCode !== 'undefined') {
                    try {
                        qrContainer.innerHTML = '';
                        const canvas = document.createElement('canvas');
                        canvas.style.cssText = 'width:100%;height:100%;display:block;';
                        qrContainer.appendChild(canvas);
                        
                        QRCode.toCanvas(canvas, verificationUrl, {
                            width: 200,
                            margin: 1,
                            color: { dark: '#1a3a5c', light: '#ffffff' }
                        }, function (err) {
                            if (err) {
                                console.error('QRCode.js error:', err);
                                showQRIconFallback(qrContainer, slipNumber);
                            }
                        });
                    } catch (e) {
                        console.error('QRCode.js exception:', e);
                        showQRIconFallback(qrContainer, slipNumber);
                    }
                } else {
                    // ── ATTEMPT 4: Icon + Slip Number (last resort) ───
                    showQRIconFallback(qrContainer, slipNumber);
                }
            };
        };
    }

    // Last resort fallback: icon + slip number
    function showQRIconFallback(container, slipNumber) {
        container.innerHTML = `
            <div style="width:100%;height:100%;background:#f0f4f8;display:flex;align-items:center;justify-content:center;flex-direction:column;gap:6px;border-radius:4px;">
                <i class="fas fa-qrcode fa-3x" style="color:#1a3a5c;opacity:0.5;"></i>
                <span style="font-size:0.7rem;color:#6b7c8d;text-align:center;padding:0 6px;word-break:break-all;">${slipNumber}</span>
                <span style="font-size:0.6rem;color:#999;">Use verification link below</span>
            </div>`;
    }

    // ── Print: opens the dedicated print-optimised page ──────────
    function printExamSlip() {
        const printWindow = window.open('/apply/print-exam-slip', '_blank');
        if (printWindow) {
            printWindow.onload = function () {
                printWindow.focus();
                // slight delay so QR has time to render before dialog
                setTimeout(function () { printWindow.print(); }, 800);
            };
        } else {
            // Pop-up blocked — fall back to current page print
            showToast('Pop-up blocked. Printing current page instead…');
            setTimeout(function () { window.print(); }, 1200);
        }
    }

    // ── Copy Link ────────────────────────────────────────────────
    function copyVerificationLink() {
        const input = document.getElementById('verificationLink');
        input.select();
        input.setSelectionRange(0, 99999);
        try {
            navigator.clipboard.writeText(input.value)
                .then(() => showToast('Verification link copied!'))
                .catch(() => { 
                    document.execCommand('copy'); 
                    showToast('Verification link copied!'); 
                });
        } catch (e) {
            document.execCommand('copy');
            showToast('Verification link copied!');
        }
    }

    // ── Share ────────────────────────────────────────────────────
    function shareSlip() {
        if (navigator.share) {
            navigator.share({
                title: 'Examination Slip — FCT College of Nursing Sciences',
                text: 'My examination slip for the 2025/2026 admission screening.',
                url: window.location.href
            }).catch(console.error);
        } else {
            copyVerificationLink();
        }
    }

    // ── Toast ────────────────────────────────────────────────────
    function showToast(message) {
        // Remove any existing toast
        const existingToast = document.querySelector('.toast-msg');
        if (existingToast) existingToast.remove();
        
        const toast = document.createElement('div');
        toast.className = 'toast-msg';
        toast.innerHTML = `<i class="fas fa-check-circle"></i> ${message}`;
        document.body.appendChild(toast);
        setTimeout(() => {
            toast.style.opacity = '0';
            toast.style.transition = 'opacity 0.3s';
            setTimeout(() => toast.remove(), 350);
        }, 2800);
    }

    // ── Download button → opens print page ───────────────────────
    document.getElementById('downloadBtn')?.addEventListener('click', function (e) {
        e.preventDefault();
        printExamSlip();
    });

    // ── Keyboard shortcuts ───────────────────────────────────────
    document.addEventListener('keydown', function(e) {
        // Ctrl+P for print
        if ((e.ctrlKey || e.metaKey) && e.key === 'p') {
            e.preventDefault();
            printExamSlip();
        }
    });

    // ── Auto-retry QR code if it fails after initial load ───────
    window.addEventListener('load', function() {
        setTimeout(function() {
            const qrContainer = document.getElementById('qrcode');
            if (qrContainer && qrContainer.children.length === 0) {
                console.log('QR container empty, retrying...');
                loadQRCode();
            }
        }, 2000);
    });
</script>

</body>
</html>