<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Examination Slip - <?php echo htmlspecialchars($exam_slip['slip_number']); ?></title>

    <!-- QR Code Library — synchronous, must be before body scripts -->
    <script src="https://cdn.jsdelivr.net/npm/qrcode@1.5.3/build/qrcode.min.js"></script>

    <style>
        /* ═══════════════════════════════════
           RESET
        ═══════════════════════════════════ */
        *, *::before, *::after { margin: 0; padding: 0; box-sizing: border-box; }

        :root {
            --navy:       #0d2b4e;
            --navy-mid:   #174070;
            --gold:       #b8860b;
            --gold-light: #e6c04a;
            --black:      #000;
            --g1:         #1a1a1a;
            --g2:         #4a4a4a;
            --g3:         #888;
            --gbg:        #f5f5f5;
            --rule:       #cccccc;
            --white:      #fff;
            --red:        #b00020;
        }

        /* ═══════════════════════════════════
           SCREEN BODY
        ═══════════════════════════════════ */
        body {
            font-family: 'Times New Roman', Times, serif;
            background: #a8a8a8;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 0 0 40px;
        }

        /* ═══════════════════════════════════
           TOP TOOLBAR (screen only)
        ═══════════════════════════════════ */
        .toolbar {
            width: 100%;
            background: var(--navy);
            color: #fff;
            padding: 10px 24px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            font-family: Arial, Helvetica, sans-serif;
            font-size: 13px;
            position: sticky;
            top: 0;
            z-index: 999;
            box-shadow: 0 2px 12px rgba(0,0,0,0.35);
        }

        .toolbar-title {
            display: flex;
            flex-direction: column;
            gap: 1px;
        }

        .toolbar-title strong { font-size: 14px; letter-spacing: 0.02em; }
        .toolbar-title span   { font-size: 11px; opacity: 0.65; }

        .toolbar-actions { display: flex; gap: 10px; align-items: center; }

        .tbtn {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            border: none;
            padding: 8px 18px;
            border-radius: 5px;
            font-size: 13px;
            font-weight: 700;
            cursor: pointer;
            letter-spacing: 0.02em;
            text-decoration: none;
            transition: opacity 0.15s;
        }

        .tbtn:hover { opacity: 0.85; }
        .tbtn-print { background: var(--gold); color: #fff; }
        .tbtn-close { background: rgba(255,255,255,0.18); color: #fff; }

        /* ═══════════════════════════════════
           SLIP WRAPPER — centres on screen
        ═══════════════════════════════════ */
        .slip-wrapper {
            margin-top: 28px;
            width: 100%;
            max-width: 820px;
            padding: 0 16px;
        }

        /* ═══════════════════════════════════
           SLIP — fills A4 proportions
        ═══════════════════════════════════ */
        .slip {
            background: var(--white);
            width: 100%;
            /* A4 aspect ratio: 297/210 ≈ 1.414 — enforced via padding-bottom */
            min-height: calc((100vw - 32px) * 1.414);
            max-width: 210mm;
            margin: 0 auto;
            padding: 12mm 14mm 10mm;
            position: relative;
            overflow: hidden;
            box-shadow: 0 6px 40px rgba(0,0,0,0.38);
            display: flex;
            flex-direction: column;
        }

        /* Watermark */
        .slip::before {
            content: 'OFFICIAL';
            position: absolute;
            top: 50%; left: 50%;
            transform: translate(-50%, -50%) rotate(-35deg);
            font-size: 68pt;
            font-weight: 900;
            color: rgba(13,43,78,0.04);
            letter-spacing: 0.15em;
            pointer-events: none;
            white-space: nowrap;
            z-index: 0;
        }

        /* All direct children above watermark */
        .border-frame { position: relative; z-index: 1; flex: 1; display: flex; flex-direction: column; }

        /* ── Double border frame ── */
        .border-frame {
            border: 3px solid var(--navy);
            padding: 3px;
        }

        .border-frame-inner {
            border: 1px solid var(--gold);
            padding: 10px 13px 8px;
            flex: 1;
            display: flex;
            flex-direction: column;
        }

        /* Push footer to bottom */
        .slip-spacer { flex: 1; }

        /* ═══════════════════════════════════
           INSTITUTION HEADER WITH LOGO
        ═══════════════════════════════════ */
        .institution-header {
            display: flex;
            align-items: center;
            gap: 10px;
            padding-bottom: 12px;
            border-bottom: 3px double var(--navy);
            margin-bottom: 13px;
        }

        /* Logo */
        .logo-box {
            flex-shrink: 0;
            width: 68px;
            height: 68px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .logo-box img {
            width: 68px;
            height: 68px;
            object-fit: contain;
            display: block;
        }

        .logo-fallback {
            width: 68px;
            height: 68px;
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

        .lf-top { font-size: 5.5pt; font-weight: 700; letter-spacing: 0.05em; display: block; }
        .lf-mid { font-size: 15pt;  font-weight: 900; line-height: 1; display: block; }
        .lf-btm { font-size: 4.5pt; letter-spacing: 0.04em; display: block; }

        /* Centre text */
        .institution-text {
            flex: 1;
            text-align: center;
        }

        .institution-name {
            font-size: 15pt;
            font-weight: 900;
            text-transform: uppercase;
            color: var(--navy);
            letter-spacing: 0.04em;
            line-height: 1.2;
        }

        .institution-address {
            font-size: 8.5pt;
            color: var(--g2);
            margin: 5px 0 7px;
        }

        .slip-badge {
            display: inline-block;
            background: var(--navy);
            color: var(--white);
            font-size: 7pt;
            font-weight: 700;
            letter-spacing: 0.09em;
            text-transform: uppercase;
            padding: 2px 12px;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }

        /* Mirror spacer keeps text centred */
        .logo-spacer { flex-shrink: 0; width: 68px; }

        /* ═══════════════════════════════════
           SLIP NUMBER BAR
        ═══════════════════════════════════ */
        .slip-number-bar {
            background: var(--gbg);
            border: 1px solid var(--rule);
            border-left: 4px solid var(--gold);
            padding: 7px 10px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 13px;
            font-size: 8pt;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }

        .sn-label     { color: var(--g2); }
        .sn-value     { font-weight: 900; color: var(--navy); font-size: 10pt; letter-spacing: 0.06em; }
        .sn-generated { color: var(--g3); font-size: 7.5pt; }

        /* ═══════════════════════════════════
           MEDIA ROW: PHOTO | QR | INFO
        ═══════════════════════════════════ */
        .media-row {
            display: flex;
            gap: 10px;
            margin-bottom: 13px;
        }

        /* Passport Photo */
        .photo-panel { flex: 0 0 96px; }

        .photo-box {
            width: 96px;
            height: 116px;
            border: 2px solid var(--navy);
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
            background: var(--gbg);
        }

        .photo-box img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
        }

        .photo-box .no-photo {
            font-size: 7pt;
            color: var(--g3);
            text-align: center;
            padding: 8px;
            line-height: 1.5;
        }

        .media-caption {
            text-align: center;
            font-size: 6pt;
            color: var(--g3);
            margin-top: 3px;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        /* QR Code */
        .qr-panel { flex: 0 0 96px; }

        .qr-box {
            width: 96px;
            height: 96px;
            border: 2px solid var(--navy);
            display: flex;
            align-items: center;
            justify-content: center;
            background: var(--white);
            padding: 4px;
            overflow: hidden;
        }

        /* All QR output children sized to fill box */
        #qrContainer canvas,
        #qrContainer img {
            width: 84px !important;
            height: 84px !important;
            display: block;
        }

        /* Candidate Info rows */
        .candidate-info {
            flex: 1;
            display: flex;
            flex-direction: column;
            gap: 6px;
        }

        .info-row {
            display: flex;
            border: 1px solid var(--rule);
        }

        .ir-label {
            background: var(--gbg);
            font-size: 7pt;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.04em;
            color: var(--g2);
            padding: 10px 6px;
            width: 106px;
            flex-shrink: 0;
            border-right: 1px solid var(--rule);
            display: flex;
            align-items: center;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }

        .ir-value {
            font-size: 8.5pt;
            font-weight: 700;
            color: var(--g1);
            padding: 10px 7px;
            display: flex;
            align-items: center;
            flex: 1;
        }

        .ir-value.name-field      { font-size: 9.5pt; color: var(--navy); }
        .ir-value.programme-field { color: var(--navy-mid); font-style: italic; }

        /* ═══════════════════════════════════
           EXAM DETAILS
        ═══════════════════════════════════ */
        .section-title {
            background: var(--navy);
            color: var(--white);
            font-size: 7.5pt;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            padding: 5px 10px;
            margin-bottom: 0;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }

        .exam-cols { display: flex; gap: 8px; }

        .exam-table {
            flex: 1;
            width: 100%;
            border-collapse: collapse;
            font-size: 8.5pt;
        }

        .exam-table td {
            border: 1px solid var(--rule);
            padding: 10px 7px;
            vertical-align: middle;
        }

        .et-label {
            background: var(--gbg);
            font-weight: 700;
            color: var(--g2);
            font-size: 7pt;
            text-transform: uppercase;
            letter-spacing: 0.04em;
            width: 38%;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }

        .et-value { font-weight: 600; color: var(--g1); }

        .highlight-row .et-label,
        .highlight-row .et-value {
            background: #fdf6e3;
            color: var(--navy);
            font-weight: 900;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }

        .highlight-row .et-value { font-size: 9.5pt; }
        .danger-row .et-value    { color: var(--red); font-weight: 700; }

        .seat-display {
            font-size: 15pt;
            font-weight: 900;
            color: var(--navy);
            letter-spacing: 0.08em;
        }

        /* ═══════════════════════════════════
           INSTRUCTIONS
        ═══════════════════════════════════ */
        .instructions {
            border: 1.5px solid var(--navy);
            margin-top: 13px;
            page-break-inside: avoid;
        }

        .instr-header {
            background: var(--navy);
            color: var(--white);
            font-size: 7.5pt;
            font-weight: 700;
            letter-spacing: 0.1em;
            text-transform: uppercase;
            padding: 5px 10px;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }

        .instructions ol {
            margin: 0;
            padding: 11px 8px 11px 24px;
        }

        .instructions ol li {
            font-size: 8pt;
            color: var(--g1);
            padding: 5px 0;
            line-height: 1.6;
        }

        /* ═══════════════════════════════════
           SIGNATURES
        ═══════════════════════════════════ */
        .signature-row {
            display: flex;
            justify-content: space-between;
            margin-top: 22px;
            align-items: flex-end;
        }

        .sig-block    { text-align: center; width: 150px; }
        .sig-line     { border-bottom: 1px solid var(--black); height: 38px; margin-bottom: 5px; }
        .sig-name     { font-size: 7pt; font-weight: 700; text-transform: uppercase; letter-spacing: 0.04em; color: var(--g2); }
        .sig-title    { font-size: 6.5pt; color: var(--g3); margin-top: 2px; }

        .stamp-circle {
            width: 64px;
            height: 64px;
            border-radius: 50%;
            border: 2px dashed var(--rule);
            display: flex;
            align-items: center;
            justify-content: center;
            flex-direction: column;
            color: var(--rule);
            font-size: 5pt;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            text-align: center;
            margin: 0 auto;
        }

        /* ═══════════════════════════════════
           FOOTER
        ═══════════════════════════════════ */
        .footer {
            margin-top: 20px;
            padding-top: 8px;
            border-top: 2px solid var(--navy);
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 10px;
        }

        .footer-left  { font-size: 6.5pt; color: var(--g3); line-height: 1.9; }
        .footer-right { text-align: right; font-size: 6.5pt; color: var(--g3); line-height: 1.9; }
        .verification-url { font-size: 7pt; color: var(--navy); word-break: break-all; }

        .gold-strip {
            height: 5px;
            background: linear-gradient(90deg, var(--gold) 0%, var(--gold-light) 50%, var(--gold) 100%);
            margin-top: 10px;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }

        /* ═══════════════════════════════════
           PRINT RULES
        ═══════════════════════════════════ */
        @media print {
            @page {
                size: A4 portrait;
                margin: 0;
            }

            body {
                background: white;
                padding: 0;
                margin: 0;
                display: block;
            }

            .toolbar      { display: none !important; }
            .slip-wrapper { padding: 0; max-width: 100%; margin: 0; }

            .slip {
                width: 210mm;
                min-height: 297mm;
                max-width: none;
                padding: 12mm 14mm 10mm;
                box-shadow: none;
                margin: 0;
                display: flex;
                flex-direction: column;
            }

            .border-frame,
            .border-frame-inner { flex: 1; display: flex; flex-direction: column; }

            .slip::before { opacity: 0.03; }
        }

        /* ═══════════════════════════════════
           RESPONSIVE — smaller screens
        ═══════════════════════════════════ */
        @media (max-width: 650px) {
            .slip { padding: 6mm 5mm; }
            .institution-name { font-size: 11pt; }
            .logo-box, .logo-spacer { width: 50px; }
            .logo-box img { width: 50px; height: 50px; }
            .media-row { flex-wrap: wrap; }
            .photo-panel, .qr-panel { flex: 0 0 80px; }
            .photo-box  { width: 80px; height: 96px; }
            .qr-box     { width: 80px; height: 80px; }
            #qrContainer canvas, #qrContainer img { width: 70px !important; height: 70px !important; }
        }
    </style>
</head>
<body>

<!-- ════════════════════════════════════════
     TOOLBAR — screen only, hidden on print
═════════════════════════════════════════ -->
<div class="toolbar">
    <div class="toolbar-title">
        <strong>📄 Examination Slip Preview</strong>
        <span>Slip No: <?php echo htmlspecialchars($exam_slip['slip_number']); ?></span>
    </div>
    <div class="toolbar-actions">
        <button class="tbtn tbtn-print" onclick="triggerPrint()">
            🖨&nbsp; Print / Save PDF
        </button>
        <button class="tbtn tbtn-close" onclick="window.close()">
            ✕&nbsp; Close
        </button>
    </div>
</div>

<!-- ════════════════════════════════════════
     SLIP
═════════════════════════════════════════ -->
<div class="slip-wrapper">
<div class="slip">
<div class="border-frame">
<div class="border-frame-inner">

    <!-- INSTITUTION HEADER WITH LOGO -->
    <div class="institution-header">

        <!-- Logo: path confirmed at /public/uploads/applications/print/logo.png
             NOTE: if your app is served from a subfolder e.g. /fctcns-app/public/...
             adjust the src below to match your actual web-accessible URL. -->
        <div class="logo-box">
            <img src="/uploads/applications/print/logo.png"
                 alt="FCT CNS Logo"
                 onerror="
                     this.style.display='none';
                     this.parentNode.innerHTML='<div class=\'logo-fallback\'><span class=\'lf-top\'>FCT</span><span class=\'lf-mid\'>CNS</span><span class=\'lf-btm\'>Nursing</span></div>';
                 ">
        </div>

        <div class="institution-text">
            <div class="institution-name">FCT College of Nursing Sciences</div>
            <div class="institution-address">Gwagwalada, Abuja — Federal Capital Territory</div>
            <div class="slip-badge">Official Examination Slip — 2025/2026 Admissions Screening Exercise</div>
        </div>

        <div class="logo-spacer"></div>
    </div>

    <!-- SLIP NUMBER BAR -->
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

    <!-- MEDIA ROW: PHOTO | QR | CANDIDATE INFO -->
    <div class="media-row">

        <!-- Passport Photo
             FIX: render img directly from DB web path — no file_exists() check.
             The onerror handles truly missing files gracefully. -->
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

        <!-- QR Code
             FIX: server /application-verify/qr/ returns 500 — skipped entirely.
             Chain: QRCode.js canvas → Google Charts → slip number text. -->
        <div class="qr-panel">
            <div class="qr-box" id="qrContainer">
                <div id="qrLoading" style="font-size:6.5pt;color:#999;text-align:center;line-height:1.4;">
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

    <!-- EXAMINATION DETAILS -->
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
                    <span style="font-size:7pt;font-weight:400;"> (Arrive 30 mins early)</span>
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

    <!-- INSTRUCTIONS -->
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

    <!-- SIGNATURES -->
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

    <!-- FOOTER -->
    <div class="footer">
        <div class="footer-left">
            This slip is computer-generated and does not require a handwritten signature.<br>
            Any alteration or falsification of this document is a criminal offence.<br>
            Enquiries: <a href="/cdn-cgi/l/email-protection" class="__cf_email__" data-cfemail="5637323b3f25253f3938251630352235382578333223783831">[email&#160;protected]</a> &nbsp;|&nbsp; +234 000 0000 000
        </div>
        <div class="footer-right">
            Verification URL:<br>
            <span class="verification-url"><?php echo BASE_URL; ?>/verify/slip/<?php echo $exam_slip['slip_number']; ?></span>
        </div>
    </div>

    <div class="gold-strip"></div>

</div><!-- /border-frame-inner -->
</div><!-- /border-frame -->
</div><!-- /slip -->
</div><!-- /slip-wrapper -->

<!-- ════════════════════════════════════════
     QR CODE SCRIPT
     FIX: runs on DOMContentLoaded so QRCode lib is guaranteed ready.
     Server endpoint (/application-verify/qr/) SKIPPED — returns 500.
     Chain: QRCode.js → Google Charts → slip number text fallback.
═════════════════════════════════════════ -->
<script data-cfasync="false" src="/cdn-cgi/scripts/5c5dd728/cloudflare-static/email-decode.min.js"></script><script>
document.addEventListener('DOMContentLoaded', function () {

    var slipNumber      = '<?php echo addslashes($exam_slip['slip_number']); ?>';
    var baseUrl         = '<?php echo addslashes(BASE_URL); ?>';
    var verificationUrl = baseUrl + '/verify/slip/' + encodeURIComponent(slipNumber);
    var container       = document.getElementById('qrContainer');

    function clearContainer() {
        container.innerHTML = '';
    }

    /* ── Fallback C: plain slip number text ── */
    function showTextFallback() {
        clearContainer();
        container.innerHTML =
            '<div style="font-size:6pt;color:#555;text-align:center;' +
            'padding:4px;word-break:break-all;line-height:1.4;">' +
            '<div style="font-size:5pt;color:#999;margin-bottom:2px;">Slip No.</div>' +
            slipNumber + '</div>';
    }

    /* ── Fallback B: Google Charts API ── */
    function loadGoogleQR() {
        var img = new Image();
        img.crossOrigin = 'anonymous';
        img.src = 'https://chart.googleapis.com/chart?chs=84x84&cht=qr&chl=' +
                  encodeURIComponent(verificationUrl) + '&choe=UTF-8';
        img.alt = 'QR Code';
        img.style.cssText = 'width:84px;height:84px;display:block;';
        img.onload  = function () { clearContainer(); container.appendChild(img); };
        img.onerror = showTextFallback;
    }

    /* ── Primary: QRCode.js canvas ── */
    if (typeof QRCode !== 'undefined') {
        var canvas = document.createElement('canvas');
        QRCode.toCanvas(canvas, verificationUrl, {
            width:  84,
            margin: 1,
            color:  { dark: '#0d2b4e', light: '#ffffff' }
        }, function (err) {
            clearContainer();
            if (!err) {
                canvas.style.cssText = 'width:84px;height:84px;display:block;';
                container.appendChild(canvas);
            } else {
                loadGoogleQR();
            }
        });
    } else {
        /* QRCode.js didn't load — go straight to Google Charts */
        clearContainer();
        loadGoogleQR();
    }

});

/* ── Print trigger — 1.2s delay lets QR canvas render first ── */
function triggerPrint() {
    setTimeout(function () { window.print(); }, 1200);
}

/* Auto-print when opened as popup from step4.php printExamSlip() */
window.addEventListener('load', function () {
    if (window.opener) {
        setTimeout(function () { window.print(); }, 1200);
    }
});
</script>

</body>
</html>