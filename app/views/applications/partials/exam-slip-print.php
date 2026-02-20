<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Examination Slip - <?php echo htmlspecialchars($exam_slip['slip_number']); ?></title>

    <!-- QR Code Library — load early so it's ready by DOMContentLoaded -->
    <script src="https://cdn.jsdelivr.net/npm/qrcode@1.5.3/build/qrcode.min.js"></script>

    <style>
        /* ═══════════════════════════════════════════════════════════
           RESET & BASE
        ═══════════════════════════════════════════════════════════ */
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        :root {
            --navy:       #0d2b4e;
            --navy-mid:   #174070;
            --gold:       #b8860b;
            --gold-light: #e6c04a;
            --black:      #000000;
            --gray-1:     #1a1a1a;
            --gray-2:     #4a4a4a;
            --gray-3:     #888888;
            --gray-light: #f5f5f5;
            --gray-rule:  #cccccc;
            --white:      #ffffff;
            --red:        #b00020;
        }

        /* Screen preview wrapper */
        body {
            font-family: 'Times New Roman', Times, serif;
            background: #d8d8d8;
            display: flex;
            justify-content: center;
            padding: 24px;
            min-height: 100vh;
        }

        /* ═══════════════════════════════════════════════════════════
           SLIP CONTAINER — exact A4 proportions for screen preview
        ═══════════════════════════════════════════════════════════ */
        .slip {
            background: var(--white);
            width: 210mm;
            min-height: 297mm;
            padding: 14mm 16mm 12mm;
            box-shadow: 0 4px 32px rgba(0,0,0,0.28);
            position: relative;
            overflow: hidden;
        }

        /* Watermark */
        .slip::before {
            content: 'OFFICIAL';
            position: absolute;
            top: 50%; left: 50%;
            transform: translate(-50%, -50%) rotate(-35deg);
            font-size: 72pt;
            font-weight: 900;
            color: rgba(13, 43, 78, 0.045);
            letter-spacing: 0.15em;
            pointer-events: none;
            white-space: nowrap;
        }

        /* ── Decorative border frame ── */
        .border-frame {
            border: 3px solid var(--navy);
            padding: 2px;
            height: 100%;
        }

        .border-frame-inner {
            border: 1px solid var(--gold);
            padding: 12px 14px 10px;
        }

        /* ═══════════════════════════════════════════════════════════
           HEADER
        ═══════════════════════════════════════════════════════════ */
        .header {
            display: flex;
            align-items: center;
            gap: 14px;
            padding-bottom: 10px;
            border-bottom: 3px double var(--navy);
            margin-bottom: 10px;
        }

        .logo-circle {
            width: 68px;
            height: 68px;
            border-radius: 50%;
            border: 2.5px solid var(--navy);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            background: var(--navy);
            flex-shrink: 0;
            color: var(--white);
            text-align: center;
            line-height: 1.15;
        }

        .logo-circle .logo-top {
            font-size: 5.5pt;
            font-weight: 700;
            letter-spacing: 0.04em;
            text-transform: uppercase;
        }

        .logo-circle .logo-mid {
            font-size: 13pt;
            font-weight: 900;
            line-height: 1;
        }

        .logo-circle .logo-btm {
            font-size: 4.5pt;
            letter-spacing: 0.05em;
        }

        .header-text {
            flex: 1;
            text-align: center;
        }

        .header-text .institution {
            font-size: 17pt;
            font-weight: 900;
            text-transform: uppercase;
            color: var(--navy);
            letter-spacing: 0.04em;
            line-height: 1.15;
        }

        .header-text .location {
            font-size: 10pt;
            color: var(--gray-2);
            margin: 1px 0 4px;
        }

        .header-text .session-label {
            display: inline-block;
            background: var(--navy);
            color: var(--white);
            font-size: 8pt;
            font-weight: 700;
            letter-spacing: 0.09em;
            text-transform: uppercase;
            padding: 2px 10px;
        }

        .slip-badge {
            text-align: right;
            flex-shrink: 0;
        }

        .slip-badge .badge-box {
            display: inline-block;
            border: 2px solid var(--gold);
            padding: 4px 8px;
            text-align: center;
        }

        .slip-badge .badge-title {
            font-size: 6pt;
            letter-spacing: 0.1em;
            text-transform: uppercase;
            color: var(--gold);
            font-weight: 700;
            display: block;
        }

        .slip-badge .badge-main {
            font-size: 8.5pt;
            font-weight: 900;
            text-transform: uppercase;
            color: var(--navy);
            display: block;
            letter-spacing: 0.06em;
        }

        /* ═══════════════════════════════════════════════════════════
           SLIP NUMBER BANNER
        ═══════════════════════════════════════════════════════════ */
        .slip-number-bar {
            background: var(--gray-light);
            border: 1px solid var(--gray-rule);
            border-left: 4px solid var(--gold);
            padding: 4px 10px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 10px;
            font-size: 8.5pt;
        }

        .slip-number-bar .sn-label { color: var(--gray-2); }
        .slip-number-bar .sn-value { font-weight: 900; color: var(--navy); font-size: 10pt; letter-spacing: 0.06em; }
        .slip-number-bar .sn-generated { color: var(--gray-3); font-size: 7.5pt; }

        /* ═══════════════════════════════════════════════════════════
           PHOTO + QR + VERIFY PANEL
        ═══════════════════════════════════════════════════════════ */
        .media-row {
            display: flex;
            gap: 12px;
            margin-bottom: 10px;
        }

        /* Passport Photo */
        .photo-panel {
            flex: 0 0 100px;
        }

        .photo-box {
            width: 100px;
            height: 120px;
            border: 2px solid var(--navy);
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
            background: var(--gray-light);
        }

        .photo-box img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
        }

        .photo-box .no-photo {
            font-size: 7pt;
            color: var(--gray-3);
            text-align: center;
            padding: 8px;
        }

        .media-caption {
            text-align: center;
            font-size: 6.5pt;
            color: var(--gray-3);
            margin-top: 3px;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        /* QR Code */
        .qr-panel {
            flex: 0 0 100px;
        }

        .qr-box {
            width: 100px;
            height: 100px;
            border: 2px solid var(--navy);
            display: flex;
            align-items: center;
            justify-content: center;
            background: var(--white);
            padding: 4px;
        }

        #qrCanvas canvas,
        #qrCanvas img,
        #qrFallback img {
            width: 88px !important;
            height: 88px !important;
            display: block;
        }

        /* Candidate Info beside photo/qr */
        .candidate-info {
            flex: 1;
            display: flex;
            flex-direction: column;
            gap: 5px;
        }

        .info-row {
            display: flex;
            border: 1px solid var(--gray-rule);
        }

        .info-row .ir-label {
            background: var(--gray-light);
            font-size: 7.5pt;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.04em;
            color: var(--gray-2);
            padding: 4px 7px;
            width: 110px;
            flex-shrink: 0;
            border-right: 1px solid var(--gray-rule);
            display: flex;
            align-items: center;
        }

        .info-row .ir-value {
            font-size: 9pt;
            font-weight: 700;
            color: var(--gray-1);
            padding: 4px 8px;
            display: flex;
            align-items: center;
            flex: 1;
        }

        .ir-value.name-field {
            font-size: 10pt;
            color: var(--navy);
        }

        .ir-value.programme-field {
            color: var(--navy-mid);
            font-style: italic;
        }

        /* ═══════════════════════════════════════════════════════════
           EXAM DETAILS TABLE
        ═══════════════════════════════════════════════════════════ */
        .section-title {
            background: var(--navy);
            color: var(--white);
            font-size: 8pt;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            padding: 4px 10px;
            margin: 10px 0 0;
        }

        .exam-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 9pt;
        }

        .exam-table td {
            border: 1px solid var(--gray-rule);
            padding: 5px 8px;
            vertical-align: middle;
        }

        .exam-table .et-label {
            background: var(--gray-light);
            font-weight: 700;
            color: var(--gray-2);
            font-size: 8pt;
            text-transform: uppercase;
            letter-spacing: 0.04em;
            width: 35%;
        }

        .exam-table .et-value {
            font-weight: 600;
            color: var(--gray-1);
        }

        .exam-table .highlight-row .et-label,
        .exam-table .highlight-row .et-value {
            background: #fdf6e3;
            color: var(--navy);
            font-weight: 900;
        }

        .exam-table .highlight-row .et-value {
            font-size: 10pt;
        }

        .exam-table .danger-row .et-value {
            color: var(--red);
            font-weight: 700;
        }

        .seat-display {
            font-size: 15pt;
            font-weight: 900;
            color: var(--navy);
            letter-spacing: 0.08em;
        }

        /* Two-column exam info layout */
        .exam-cols {
            display: flex;
            gap: 10px;
            margin-top: 0;
        }

        .exam-cols .exam-table { flex: 1; }

        /* ═══════════════════════════════════════════════════════════
           INSTRUCTIONS BOX
        ═══════════════════════════════════════════════════════════ */
        .instructions {
            border: 1.5px solid var(--navy);
            margin: 10px 0 0;
            page-break-inside: avoid;
        }

        .instructions .instr-header {
            background: var(--navy);
            color: var(--white);
            font-size: 8pt;
            font-weight: 700;
            letter-spacing: 0.1em;
            text-transform: uppercase;
            padding: 3px 10px;
        }

        .instructions ol {
            margin: 0;
            padding: 7px 8px 7px 26px;
        }

        .instructions ol li {
            font-size: 8pt;
            color: var(--gray-1);
            padding: 1.5px 0;
            line-height: 1.45;
        }

        /* ═══════════════════════════════════════════════════════════
           SIGNATURE ROW
        ═══════════════════════════════════════════════════════════ */
        .signature-row {
            display: flex;
            justify-content: space-between;
            margin-top: 12px;
            align-items: flex-end;
        }

        .sig-block {
            text-align: center;
            width: 160px;
        }

        .sig-line {
            border-bottom: 1px solid var(--black);
            height: 28px;
            margin-bottom: 4px;
        }

        .sig-name {
            font-size: 7.5pt;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.04em;
            color: var(--gray-2);
        }

        .sig-title {
            font-size: 6.5pt;
            color: var(--gray-3);
            margin-top: 1px;
        }

        /* Official stamp placeholder */
        .stamp-circle {
            width: 70px;
            height: 70px;
            border-radius: 50%;
            border: 2px dashed var(--gray-rule);
            display: flex;
            align-items: center;
            justify-content: center;
            flex-direction: column;
            color: var(--gray-rule);
            font-size: 5.5pt;
            text-transform: uppercase;
            letter-spacing: 0.06em;
            text-align: center;
            margin: 0 auto;
        }

        /* ═══════════════════════════════════════════════════════════
           FOOTER
        ═══════════════════════════════════════════════════════════ */
        .footer {
            margin-top: 10px;
            padding-top: 7px;
            border-top: 2px solid var(--navy);
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
        }

        .footer-left {
            font-size: 6.5pt;
            color: var(--gray-3);
            line-height: 1.7;
        }

        .footer-right {
            text-align: right;
            font-size: 6.5pt;
            color: var(--gray-3);
            line-height: 1.7;
        }

        .verification-url {
            font-size: 7pt;
            color: var(--navy);
            word-break: break-all;
        }

        /* Gold bottom accent */
        .gold-strip {
            height: 4px;
            background: linear-gradient(90deg, var(--gold) 0%, var(--gold-light) 50%, var(--gold) 100%);
            margin-top: 8px;
        }

        /* ═══════════════════════════════════════════════════════════
           PRINT RULES
        ═══════════════════════════════════════════════════════════ */
        @media print {
            @page {
                size: A4;
                margin: 0;
            }

            body {
                background: #fff;
                padding: 0;
                display: block;
            }

            .slip {
                width: 100%;
                min-height: 100vh;
                padding: 12mm 14mm 10mm;
                box-shadow: none;
            }

            .no-print { display: none !important; }

            .slip::before { opacity: 1; }
        }
    </style>
</head>
<body>

<!-- ── No-Print bar (screen only) ─────────────────────────── -->
<div class="no-print" style="position:fixed;top:0;left:0;right:0;background:#0d2b4e;color:#fff;padding:8px 20px;display:flex;align-items:center;justify-content:space-between;z-index:999;font-family:Arial,sans-serif;font-size:13px;">
    <span>📄 Examination Slip Preview</span>
    <div style="display:flex;gap:10px;">
        <button onclick="window.print()" style="background:#b8860b;color:#fff;border:none;padding:6px 16px;border-radius:4px;cursor:pointer;font-size:13px;font-weight:600;">🖨 Print / Save PDF</button>
        <button onclick="window.close()" style="background:rgba(255,255,255,0.15);color:#fff;border:none;padding:6px 14px;border-radius:4px;cursor:pointer;font-size:13px;">✕ Close</button>
    </div>
</div>

<div class="slip" style="margin-top:44px;" id="printSlip">
<div class="border-frame">
<div class="border-frame-inner">

    <!-- ════════════════════════════════════════════════════════
         HEADER
    ═════════════════════════════════════════════════════════ -->
    <div class="header">
        <!-- Logo -->
        <div class="logo-circle">
            <span class="logo-top">FCT</span>
            <span class="logo-mid">CNS</span>
            <span class="logo-btm">Nursing</span>
        </div>

        <!-- Institution name -->
        <div class="header-text">
            <div class="institution">FCT College of Nursing Sciences</div>
            <div class="location">Gwagwalada, Abuja — Federal Capital Territory</div>
            <div class="session-label">2025 / 2026 Admissions Screening Exercise</div>
        </div>

        <!-- Badge -->
        <div class="slip-badge">
            <div class="badge-box">
                <span class="badge-title">Document Type</span>
                <span class="badge-main">Official<br>Exam Slip</span>
            </div>
        </div>
    </div>

    <!-- ════════════════════════════════════════════════════════
         SLIP NUMBER BAR
    ═════════════════════════════════════════════════════════ -->
    <div class="slip-number-bar">
        <span>
            <span class="sn-label">SLIP NO: </span>
            <span class="sn-value"><?php echo htmlspecialchars($exam_slip['slip_number']); ?></span>
        </span>
        <span class="sn-generated">
            Generated: <?php echo date('d F Y, h:i A', strtotime($exam_slip['generated_at'])); ?>
            &nbsp;|&nbsp; Downloads: <?php echo (int)($exam_slip['download_count'] ?? 0); ?>
        </span>
    </div>

    <!-- ════════════════════════════════════════════════════════
         MEDIA ROW: PHOTO + QR + CANDIDATE INFO
    ═════════════════════════════════════════════════════════ -->
    <div class="media-row">

        <!-- Passport Photo -->
        <div class="photo-panel">
            <div class="photo-box">
                <?php if (!empty($application['passport_photo'])): ?>
                    <img src="<?php echo htmlspecialchars($_SERVER['DOCUMENT_ROOT'] . $application['passport_photo']); ?>"
                         alt="Passport Photo">
                <?php else: ?>
                    <div class="no-photo">Photograph<br>Not Available</div>
                <?php endif; ?>
            </div>
            <div class="media-caption">Passport Photograph</div>
        </div>

        <!-- QR Code -->
        <div class="qr-panel">
            <div class="qr-box">
                <?php if (!empty($exam_slip['qr_code'])): ?>
                    <img src="<?php echo htmlspecialchars($exam_slip['qr_code']); ?>" alt="QR Code" style="width:88px;height:88px;">
                <?php else: ?>
                    <div id="qrCanvas"></div>
                    <div id="qrFallback" style="display:none;">
                        <img src="/application-verify/qr/<?php echo urlencode($exam_slip['slip_number']); ?>" alt="QR Code">
                    </div>
                <?php endif; ?>
            </div>
            <div class="media-caption">Scan to Verify</div>
        </div>

        <!-- Candidate Info -->
        <div class="candidate-info">
            <div class="info-row">
                <span class="ir-label">Full Name</span>
                <span class="ir-value name-field"><?php echo htmlspecialchars($application['first_name'] . ' ' . $application['last_name']); ?></span>
            </div>
            <div class="info-row">
                <span class="ir-label">Application No.</span>
                <span class="ir-value"><?php echo htmlspecialchars($application['application_number']); ?></span>
            </div>
            <div class="info-row">
                <span class="ir-label">JAMB Reg. No.</span>
                <span class="ir-value"><?php echo htmlspecialchars($application['jamb_number']); ?></span>
            </div>
            <div class="info-row">
                <span class="ir-label">Programme</span>
                <span class="ir-value programme-field"><?php echo htmlspecialchars($application['program_choice_1']); ?></span>
            </div>
        </div>

    </div><!-- /media-row -->

    <!-- ════════════════════════════════════════════════════════
         EXAMINATION DETAILS
    ═════════════════════════════════════════════════════════ -->
    <div class="section-title">Examination Details</div>

    <div class="exam-cols" style="margin-top:0;">

        <!-- Left column -->
        <table class="exam-table">
            <tr class="highlight-row">
                <td class="et-label">Examination Date</td>
                <td class="et-value"><?php echo date('l, jS F Y', strtotime($exam_slip['exam_date'])); ?></td>
            </tr>
            <tr>
                <td class="et-label">Examination Time</td>
                <td class="et-value"><?php echo date('h:i A', strtotime($exam_slip['exam_time'])); ?></td>
            </tr>
            <tr class="danger-row">
                <td class="et-label">Reporting Time</td>
                <td class="et-value">
                    ⚠ <?php echo date('h:i A', strtotime($exam_slip['reporting_time'])); ?>
                    <span style="font-size:7.5pt;font-weight:400;"> (Arrive 30 mins early)</span>
                </td>
            </tr>
        </table>

        <!-- Right column -->
        <table class="exam-table">
            <tr>
                <td class="et-label">Venue</td>
                <td class="et-value"><?php echo htmlspecialchars($exam_slip['exam_venue']); ?></td>
            </tr>
            <tr>
                <td class="et-label">Seat Number</td>
                <td class="et-value">
                    <span class="seat-display"><?php echo htmlspecialchars($exam_slip['seat_number']); ?></span>
                </td>
            </tr>
        </table>

    </div><!-- /exam-cols -->

    <!-- ════════════════════════════════════════════════════════
         INSTRUCTIONS
    ═════════════════════════════════════════════════════════ -->
    <div class="instructions">
        <div class="instr-header">Important Instructions — Please Read Carefully</div>
        <ol>
            <li>Bring this printed slip to the examination venue — it is required for entry.</li>
            <li>Arrive at least <strong>30 minutes</strong> before the scheduled reporting time. Latecomers will not be admitted.</li>
            <li>Come with writing materials: pen, pencil, and eraser.</li>
            <li>Present a valid photo ID — National ID Card, Driver's Licence, or International Passport.</li>
            <li>Electronic devices including mobile phones, calculators, and smartwatches are <strong>strictly prohibited</strong> inside the hall.</li>
            <li>The QR code printed on this slip will be scanned at the entrance for identity verification.</li>
            <li>Candidates must sit only at the seat number assigned on this slip.</li>
        </ol>
    </div>

    <!-- ════════════════════════════════════════════════════════
         SIGNATURES
    ═════════════════════════════════════════════════════════ -->
    <div class="signature-row">
        <div class="sig-block">
            <div class="sig-line"></div>
            <div class="sig-name">Chairman</div>
            <div class="sig-title">Admissions Committee</div>
        </div>

        <div class="sig-block" style="text-align:center;">
            <div class="stamp-circle">
                Official<br>Stamp
            </div>
        </div>

        <div class="sig-block">
            <div class="sig-line"></div>
            <div class="sig-name">Registrar</div>
            <div class="sig-title">FCT College of Nursing Sciences</div>
        </div>
    </div>

    <!-- ════════════════════════════════════════════════════════
         FOOTER
    ═════════════════════════════════════════════════════════ -->
    <div class="footer">
        <div class="footer-left">
            This slip is computer-generated and does not require a handwritten signature.<br>
            Any alteration or falsification of this document is a criminal offence.<br>
            For enquiries: admissions@fctcns.edu.ng &nbsp;|&nbsp; +234 000 0000 000
        </div>
        <div class="footer-right">
            <span>Verification URL:</span><br>
            <span class="verification-url"><?php echo BASE_URL; ?>/verify/slip/<?php echo $exam_slip['slip_number']; ?></span>
        </div>
    </div>

    <div class="gold-strip"></div>

</div><!-- /border-frame-inner -->
</div><!-- /border-frame -->
</div><!-- /slip -->

<!-- ════════════════════════════════════════════════════════════
     QR CODE GENERATION
═══════════════════════════════════════════════════════════ -->
<?php if (empty($exam_slip['qr_code'])): ?>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        var verificationUrl = '<?php echo addslashes(BASE_URL); ?>/verify/slip/<?php echo urlencode($exam_slip['slip_number']); ?>';
        var container = document.getElementById('qrCanvas');
        var fallback  = document.getElementById('qrFallback');

        if (!container) return;

        if (typeof QRCode !== 'undefined') {
            var canvas = document.createElement('canvas');
            container.appendChild(canvas);

            QRCode.toCanvas(canvas, verificationUrl, {
                width:  88,
                margin: 1,
                color:  { dark: '#0d2b4e', light: '#ffffff' }
            }, function (err) {
                if (err) {
                    console.error('QR error:', err);
                    container.style.display = 'none';
                    if (fallback) fallback.style.display = 'block';
                }
            });
        } else {
            container.style.display = 'none';
            if (fallback) fallback.style.display = 'block';
        }
    });
</script>
<?php endif; ?>

<!-- Auto-print on load -->
<script>
    window.addEventListener('load', function () {
        // Small delay ensures QR canvas has rendered before print dialog
        setTimeout(function () {
            window.print();
        }, 800);
    });
</script>

</body>
</html>