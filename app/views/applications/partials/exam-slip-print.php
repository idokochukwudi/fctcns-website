<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Examination Slip - <?php echo htmlspecialchars($exam_slip['slip_number']); ?></title>

    <!-- QR Code Library — MUST load before any script references QRCode -->
    <script src="https://cdn.jsdelivr.net/npm/qrcode@1.5.3/build/qrcode.min.js"></script>

    <style>
        /* ═══════════════════════════════════════════════════════════
           RESET & BASE
        ═══════════════════════════════════════════════════════════ */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

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

        body {
            font-family: 'Times New Roman', Times, serif;
            background: white;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            min-height: 100vh;
        }

        /* ═══════════════════════════════════════════════════════════
           SLIP CONTAINER
        ═══════════════════════════════════════════════════════════ */
        .slip {
            background: var(--white);
            width: 210mm;
            min-height: 297mm;
            padding: 14mm 16mm 12mm;
            position: relative;
            overflow: hidden;
            margin: 0 auto;
            box-shadow: none;
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
            z-index: 0;
        }

        /* ── Decorative border frame ── */
        .border-frame {
            border: 3px solid var(--navy);
            padding: 2px;
            height: 100%;
            position: relative;
            z-index: 1;
            background: white;
        }

        .border-frame-inner {
            border: 1px solid var(--gold);
            padding: 12px 14px 10px;
        }

        /* ═══════════════════════════════════════════════════════════
           INSTITUTION HEADER — with logo
        ═══════════════════════════════════════════════════════════ */
        .institution-header {
            display: flex;
            align-items: center;
            gap: 12px;
            padding-bottom: 8px;
            border-bottom: 3px double var(--navy);
            margin-bottom: 10px;
        }

        /* Logo on the left */
        .logo-box {
            flex-shrink: 0;
            width: 72px;
            height: 72px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .logo-box img {
            width: 72px;
            height: 72px;
            object-fit: contain;
            display: block;
        }

        /* Text fallback circle if logo file is missing */
        .logo-fallback {
            width: 72px;
            height: 72px;
            border-radius: 50%;
            background: var(--navy);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            color: #fff;
            text-align: center;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }
        .logo-fallback .lf-top { font-size: 6pt; font-weight: 700; letter-spacing: 0.05em; }
        .logo-fallback .lf-mid { font-size: 16pt; font-weight: 900; line-height: 1; }
        .logo-fallback .lf-btm { font-size: 4.5pt; letter-spacing: 0.04em; }

        /* Centre text block */
        .institution-text {
            flex: 1;
            text-align: center;
        }

        .institution-name {
            font-size: 16pt;
            font-weight: 900;
            text-transform: uppercase;
            color: var(--navy);
            letter-spacing: 0.04em;
            line-height: 1.2;
        }

        .institution-address {
            font-size: 9pt;
            color: var(--gray-2);
            margin: 2px 0 5px;
        }

        .slip-badge {
            display: inline-block;
            background: var(--navy);
            color: var(--white);
            font-size: 7.5pt;
            font-weight: 700;
            letter-spacing: 0.09em;
            text-transform: uppercase;
            padding: 2px 14px;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }

        /* Mirror logo space on the right to keep text truly centred */
        .logo-spacer {
            flex-shrink: 0;
            width: 72px;
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
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }

        .slip-number-bar .sn-label  { color: var(--gray-2); }
        .slip-number-bar .sn-value  { font-weight: 900; color: var(--navy); font-size: 10pt; letter-spacing: 0.06em; }
        .slip-number-bar .sn-generated { color: var(--gray-3); font-size: 7.5pt; }

        /* ═══════════════════════════════════════════════════════════
           PHOTO + QR + CANDIDATE INFO
        ═══════════════════════════════════════════════════════════ */
        .media-row {
            display: flex;
            gap: 12px;
            margin-bottom: 10px;
        }

        /* Passport Photo */
        .photo-panel { flex: 0 0 100px; }

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
            line-height: 1.5;
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
        .qr-panel { flex: 0 0 100px; }

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

        /* All QR output images/canvases sized to fill the box */
        #qrContainer canvas,
        #qrContainer img {
            width: 88px !important;
            height: 88px !important;
            display: block;
        }

        /* Candidate Info */
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
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
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

        .ir-value.name-field      { font-size: 10pt; color: var(--navy); }
        .ir-value.programme-field { color: var(--navy-mid); font-style: italic; }

        /* ═══════════════════════════════════════════════════════════
           EXAM DETAILS
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
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
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
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }

        .exam-table .et-value { font-weight: 600; color: var(--gray-1); }

        .exam-table .highlight-row .et-label,
        .exam-table .highlight-row .et-value {
            background: #fdf6e3;
            color: var(--navy);
            font-weight: 900;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }

        .exam-table .highlight-row .et-value { font-size: 10pt; }
        .exam-table .danger-row .et-value    { color: var(--red); font-weight: 700; }

        .seat-display {
            font-size: 15pt;
            font-weight: 900;
            color: var(--navy);
            letter-spacing: 0.08em;
        }

        .exam-cols          { display: flex; gap: 10px; margin-top: 0; }
        .exam-cols .exam-table { flex: 1; }

        /* ═══════════════════════════════════════════════════════════
           INSTRUCTIONS
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
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
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
           SIGNATURES
        ═══════════════════════════════════════════════════════════ */
        .signature-row {
            display: flex;
            justify-content: space-between;
            margin-top: 12px;
            align-items: flex-end;
        }

        .sig-block      { text-align: center; width: 160px; }
        .sig-line       { border-bottom: 1px solid var(--black); height: 28px; margin-bottom: 4px; }
        .sig-name       { font-size: 7.5pt; font-weight: 700; text-transform: uppercase; letter-spacing: 0.04em; color: var(--gray-2); }
        .sig-title      { font-size: 6.5pt; color: var(--gray-3); margin-top: 1px; }

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

        .footer-left  { font-size: 6.5pt; color: var(--gray-3); line-height: 1.7; }
        .footer-right { text-align: right; font-size: 6.5pt; color: var(--gray-3); line-height: 1.7; }

        .verification-url { font-size: 7pt; color: var(--navy); word-break: break-all; }

        .gold-strip {
            height: 4px;
            background: linear-gradient(90deg, var(--gold) 0%, var(--gold-light) 50%, var(--gold) 100%);
            margin-top: 8px;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
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
                background: white;
                padding: 0;
                margin: 0;
                display: block;
            }

            .slip {
                width: 100%;
                min-height: 100vh;
                padding: 12mm 14mm 10mm;
                box-shadow: none;
                margin: 0;
            }

            .slip::before { opacity: 0.03; }
        }
    </style>
</head>
<body>

<div class="slip">
<div class="border-frame">
<div class="border-frame-inner">

    <!-- ════════════════════════════════════════════════════════
         INSTITUTION HEADER WITH LOGO
    ═════════════════════════════════════════════════════════ -->
    <div class="institution-header">

        <!-- Logo — path: /public/uploads/applications/print/logo.png -->
        <div class="logo-box">
            <img src="/public/uploads/applications/print/logo.png"
                 alt="FCT College of Nursing Sciences"
                 onerror="this.style.display='none';
                          this.parentNode.innerHTML='<div class=\'logo-fallback\'><span class=\'lf-top\'>FCT</span><span class=\'lf-mid\'>CNS</span><span class=\'lf-btm\'>Nursing</span></div>';">
        </div>

        <!-- Centre: institution name & badge -->
        <div class="institution-text">
            <div class="institution-name">FCT College of Nursing Sciences</div>
            <div class="institution-address">Gwagwalada, Abuja — Federal Capital Territory</div>
            <div class="slip-badge">Official Examination Slip — 2025/2026 Admissions Screening Exercise</div>
        </div>

        <!-- Right spacer keeps text truly centred -->
        <div class="logo-spacer"></div>

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

        <!-- Passport Photo
             FIX: Always render the <img> tag using the web path directly.
             The file_exists() check was blocking display because DOCUMENT_ROOT
             resolution differs between web and CLI contexts. The browser will
             show a broken icon if the file is truly missing — no blank box. -->
        <div class="photo-panel">
            <div class="photo-box">
                <?php if (!empty($application['passport_photo'])): ?>
                    <img src="<?php echo htmlspecialchars($application['passport_photo']); ?>"
                         alt="Passport Photo"
                         onerror="this.outerHTML='<div class=\'no-photo\'>Photo<br>Not Found</div>';">
                <?php else: ?>
                    <div class="no-photo">Photograph<br>Not Available</div>
                <?php endif; ?>
            </div>
            <div class="media-caption">Passport Photograph</div>
        </div>

        <!-- QR Code -->
        <div class="qr-panel">
            <div class="qr-box" id="qrContainer">
                <div id="qrLoading" style="font-size:7pt;color:#999;text-align:center;">
                    Loading<br>QR...
                </div>
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

    <div class="exam-cols">

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
            <div class="stamp-circle">Official<br>Stamp</div>
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
     QR CODE — runs after DOM ready so library is guaranteed loaded
═══════════════════════════════════════════════════════════ -->
<script>
    document.addEventListener('DOMContentLoaded', function () {

        var slipNumber      = '<?php echo addslashes($exam_slip['slip_number']); ?>';
        var baseUrl         = '<?php echo addslashes(BASE_URL); ?>';
        var verificationUrl = baseUrl + '/verify/slip/' + encodeURIComponent(slipNumber);
        var qrContainer     = document.getElementById('qrContainer');
        var loadingEl       = document.getElementById('qrLoading');

        function clearContainer() {
            qrContainer.innerHTML = '';
        }

        function showTextFallback() {
            clearContainer();
            qrContainer.innerHTML =
                '<div style="font-size:6pt;color:#666;text-align:center;padding:4px;word-break:break-all;">' +
                slipNumber + '</div>';
        }

        /* ── Method 3: Google Charts (last resort before text) ── */
        function loadGoogleQR() {
            var img  = new Image();
            img.src  = 'https://chart.googleapis.com/chart?chs=88x88&cht=qr&chl=' +
                       encodeURIComponent(verificationUrl) + '&choe=UTF-8';
            img.alt  = 'QR Code';
            img.style.cssText = 'width:88px;height:88px;display:block;';
            img.onload  = function () { clearContainer(); qrContainer.appendChild(img); };
            img.onerror = showTextFallback;
        }

        /* ── Method 2: Server-generated QR endpoint ── */
        function loadServerQR() {
            var img  = new Image();
            img.src  = '/application-verify/qr/' + encodeURIComponent(slipNumber) + '?t=' + Date.now();
            img.alt  = 'QR Code';
            img.style.cssText = 'width:88px;height:88px;display:block;';
            img.onload  = function () { clearContainer(); qrContainer.appendChild(img); };
            img.onerror = loadGoogleQR;
        }

        /* ── Method 1: QRCode.js canvas (preferred) ── */
        if (typeof QRCode !== 'undefined') {
            var canvas = document.createElement('canvas');
            QRCode.toCanvas(canvas, verificationUrl, {
                width:  88,
                margin: 1,
                color:  { dark: '#0d2b4e', light: '#ffffff' }
            }, function (err) {
                clearContainer();
                if (!err) {
                    canvas.style.cssText = 'width:88px;height:88px;display:block;';
                    qrContainer.appendChild(canvas);
                } else {
                    loadServerQR();
                }
            });
        } else {
            /* Library didn't load — fall straight to server QR */
            clearContainer();
            loadServerQR();
        }

    });
</script>

<!-- Auto-print: 1 s delay lets QR canvas render before dialog opens -->
<script>
    window.addEventListener('load', function () {
        setTimeout(function () { window.print(); }, 1000);
    });
</script>

</body>
</html>