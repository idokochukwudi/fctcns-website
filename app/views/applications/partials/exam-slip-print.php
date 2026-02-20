<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Examination Slip — <?php echo htmlspecialchars($exam_slip['slip_number']); ?></title>

    <script src="https://cdn.jsdelivr.net/npm/qrcode@1.5.3/build/qrcode.min.js"></script>

    <style>
        /* ═══════════════════════════════════════
           PAGE & PRINT SETUP
        ═══════════════════════════════════════ */
        @page {
            size: A4 portrait;
            margin: 0;
        }

        *, *::before, *::after {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        :root {
            --navy:     #0d2b4e;
            --navy-mid: #174070;
            --gold:     #b8860b;
            --gold-lt:  #d4a017;
            --red:      #9b1c1c;
            --g1:       #111111;
            --g2:       #444444;
            --g3:       #888888;
            --gbg:      #f4f4f4;
            --rule:     #c8c8c8;
            --white:    #ffffff;
        }

        /* ── Screen preview ── */
        body {
            font-family: 'Times New Roman', Times, serif;
            background: #b0b0b0;
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 30px 20px;
            min-height: 100vh;
            color: var(--g1);
        }

        /* ── Preview toolbar (hidden on print) ── */
        .toolbar {
            width: 210mm;
            background: var(--navy);
            color: var(--white);
            padding: 10px 18px;
            border-radius: 6px 6px 0 0;
            display: flex;
            align-items: center;
            justify-content: space-between;
            font-family: Arial, sans-serif;
            font-size: 13px;
        }

        .toolbar span { opacity: 0.85; }
        .toolbar-btns { display: flex; gap: 8px; }

        .tbtn {
            border: none;
            padding: 6px 16px;
            border-radius: 4px;
            font-size: 12px;
            font-weight: 700;
            cursor: pointer;
            letter-spacing: 0.03em;
        }

        .tbtn-print { background: var(--gold); color: var(--white); }
        .tbtn-close { background: rgba(255,255,255,0.15); color: var(--white); }

        /* ═══════════════════════════════════════
           A4 SLIP — fixed 210×297mm, content fills page
        ═══════════════════════════════════════ */
        .slip {
            width: 210mm;
            height: 297mm;
            background: var(--white);
            position: relative;
            overflow: hidden;
            box-shadow: 0 6px 40px rgba(0,0,0,0.35);
            display: flex;
            flex-direction: column;
        }

        /* Watermark */
        .slip::before {
            content: 'OFFICIAL DOCUMENT';
            position: absolute;
            top: 50%; left: 50%;
            transform: translate(-50%, -50%) rotate(-38deg);
            font-size: 54pt;
            font-weight: 900;
            color: rgba(13,43,78,0.04);
            letter-spacing: 0.12em;
            white-space: nowrap;
            pointer-events: none;
            z-index: 0;
        }

        .slip > * { position: relative; z-index: 1; }

        /* ── Top colour bar ── */
        .top-bar {
            height: 7mm;
            background: linear-gradient(90deg, var(--navy) 0%, var(--navy-mid) 65%, var(--gold) 100%);
            flex-shrink: 0;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }

        /* ── HEADER ── */
        .header {
            padding: 4mm 8mm 3.5mm;
            display: flex;
            align-items: center;
            gap: 5mm;
            border-bottom: 0.5mm solid var(--gold);
            flex-shrink: 0;
        }

        .logo-wrap {
            flex-shrink: 0;
            width: 20mm;
            height: 20mm;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .logo-wrap img {
            width: 100%;
            height: 100%;
            object-fit: contain;
            display: block;
        }

        .logo-fallback {
            width: 20mm;
            height: 20mm;
            border-radius: 50%;
            background: var(--navy);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            color: var(--white);
            text-align: center;
            flex-shrink: 0;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }

        .logo-fallback .lf-top { font-size: 5.5pt; font-weight: 700; letter-spacing: 0.05em; }
        .logo-fallback .lf-mid { font-size: 14pt; font-weight: 900; line-height: 1; }
        .logo-fallback .lf-btm { font-size: 4pt; letter-spacing: 0.04em; }

        .header-centre {
            flex: 1;
            text-align: center;
        }

        .inst-name {
            font-size: 15.5pt;
            font-weight: 900;
            text-transform: uppercase;
            color: var(--navy);
            letter-spacing: 0.03em;
            line-height: 1.15;
        }

        .inst-sub {
            font-size: 9pt;
            color: var(--g2);
            margin: 1.5px 0 3px;
        }

        .session-pill {
            display: inline-block;
            background: var(--navy);
            color: var(--white);
            font-size: 7pt;
            font-weight: 700;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            padding: 2px 10px;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }

        .official-box {
            border: 1.5px solid var(--gold);
            padding: 3px 8px;
            text-align: center;
            flex-shrink: 0;
        }

        .ob-label {
            font-size: 5.5pt;
            color: var(--gold);
            font-weight: 700;
            letter-spacing: 0.1em;
            text-transform: uppercase;
            display: block;
        }

        .ob-main {
            font-size: 8pt;
            font-weight: 900;
            color: var(--navy);
            text-transform: uppercase;
            letter-spacing: 0.05em;
            display: block;
            line-height: 1.3;
        }

        /* ── SLIP NUMBER BAR ── */
        .slip-bar {
            background: var(--gbg);
            border-top: 0.3mm solid var(--rule);
            border-bottom: 0.3mm solid var(--rule);
            border-left: 4px solid var(--gold);
            padding: 2mm 6mm;
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-shrink: 0;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }

        .sb-left { font-size: 8.5pt; }
        .sb-no   { font-size: 11pt; font-weight: 900; color: var(--navy); letter-spacing: 0.06em; }
        .sb-right { font-size: 7pt; color: var(--g3); text-align: right; line-height: 1.6; }

        /* ── BODY fills remaining height ── */
        .body {
            flex: 1;
            padding: 3mm 8mm 2mm;
            display: flex;
            flex-direction: column;
            gap: 2.5mm;
            overflow: hidden;
        }

        /* ── MEDIA ROW ── */
        .media-row {
            display: flex;
            gap: 5mm;
            align-items: flex-start;
            flex-shrink: 0;
        }

        .photo-col { flex: 0 0 28mm; }

        .photo-box {
            width: 28mm;
            height: 35mm;
            border: 0.5mm solid var(--navy);
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
            font-size: 6.5pt;
            color: var(--g3);
            text-align: center;
            padding: 4px;
            line-height: 1.5;
        }

        .media-cap {
            text-align: center;
            font-size: 6pt;
            color: var(--g3);
            margin-top: 1.5mm;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .qr-col { flex: 0 0 28mm; }

        .qr-box {
            width: 28mm;
            height: 28mm;
            border: 0.5mm solid var(--navy);
            display: flex;
            align-items: center;
            justify-content: center;
            background: var(--white);
            padding: 1.5mm;
        }

        #qrCanvas, #qrFallback {
            width: 100%;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        #qrCanvas canvas,
        #qrCanvas img,
        #qrFallback img {
            width: 100% !important;
            height: 100% !important;
            object-fit: contain;
            display: block;
        }

        .info-col {
            flex: 1;
            display: flex;
            flex-direction: column;
            gap: 1.5mm;
        }

        .irow {
            display: flex;
            border: 0.3mm solid var(--rule);
        }

        .irow-lbl {
            background: var(--gbg);
            font-size: 7pt;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.04em;
            color: var(--g2);
            padding: 2mm 3mm;
            width: 30mm;
            flex-shrink: 0;
            border-right: 0.3mm solid var(--rule);
            display: flex;
            align-items: center;
            line-height: 1.3;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }

        .irow-val {
            font-size: 8.5pt;
            font-weight: 700;
            color: var(--g1);
            padding: 2mm 3mm;
            flex: 1;
            display: flex;
            align-items: center;
        }

        .irow-val.name { font-size: 10pt; color: var(--navy); }
        .irow-val.prog { font-style: italic; color: var(--navy-mid); }

        /* ── SECTION HEADING ── */
        .section-hd {
            background: var(--navy);
            color: var(--white);
            font-size: 7.5pt;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            padding: 2mm 4mm;
            flex-shrink: 0;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }

        /* ── EXAM DETAILS — 2 columns ── */
        .exam-grid {
            display: flex;
            gap: 4mm;
            flex-shrink: 0;
        }

        .etable {
            flex: 1;
            border-collapse: collapse;
            font-size: 8.5pt;
            width: 100%;
        }

        .etable td {
            border: 0.3mm solid var(--rule);
            padding: 2.2mm 3mm;
            vertical-align: middle;
        }

        .etable .el {
            background: var(--gbg);
            font-weight: 700;
            color: var(--g2);
            font-size: 7pt;
            text-transform: uppercase;
            letter-spacing: 0.04em;
            width: 42%;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }

        .etable .ev { font-weight: 600; color: var(--g1); }

        .etable .hi .el,
        .etable .hi .ev {
            background: #fdf6e3;
            color: var(--navy);
            font-weight: 900;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }

        .etable .hi .ev { font-size: 9.5pt; }
        .etable .danger .ev { color: var(--red); font-weight: 700; }

        .seat-val {
            font-size: 20pt;
            font-weight: 900;
            color: var(--navy);
            letter-spacing: 0.06em;
        }

        /* ── INSTRUCTIONS ── */
        .instructions {
            border: 0.4mm solid var(--navy);
            flex-shrink: 0;
        }

        .instr-hd {
            background: var(--navy);
            color: var(--white);
            font-size: 7.5pt;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            padding: 2mm 4mm;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }

        .instructions ol {
            margin: 0;
            padding: 2mm 4mm 2mm 13mm;
        }

        .instructions ol li {
            font-size: 7.5pt;
            color: var(--g1);
            padding: 0.8mm 0;
            line-height: 1.45;
        }

        .instructions ol li strong { color: var(--navy); }

        /* ── SIGNATURES ── */
        .sig-row {
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
            flex-shrink: 0;
            padding-top: 1mm;
        }

        .sig-block { text-align: center; width: 48mm; }

        .sig-line {
            border-bottom: 0.3mm solid var(--g1);
            height: 9mm;
            margin-bottom: 1.5mm;
        }

        .sig-name  { font-size: 7.5pt; font-weight: 700; text-transform: uppercase; letter-spacing: 0.04em; color: var(--g2); }
        .sig-title { font-size: 6.5pt; color: var(--g3); margin-top: 0.5mm; }

        .stamp {
            width: 20mm;
            height: 20mm;
            border-radius: 50%;
            border: 0.5mm dashed var(--rule);
            display: flex;
            align-items: center;
            justify-content: center;
            flex-direction: column;
            color: var(--rule);
            font-size: 5pt;
            text-transform: uppercase;
            letter-spacing: 0.06em;
            text-align: center;
            margin: 0 auto;
        }

        /* ── FOOTER ── */
        .footer {
            border-top: 0.5mm solid var(--navy);
            padding: 2mm 0 0;
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: 5mm;
            flex-shrink: 0;
        }

        .footer-l { font-size: 6pt; color: var(--g3); line-height: 1.7; }
        .footer-r { font-size: 6pt; color: var(--g3); text-align: right; line-height: 1.7; }
        .footer-r .ver-url { font-size: 6.5pt; color: var(--navy); word-break: break-all; }

        /* ── Bottom gold bar ── */
        .bottom-bar {
            height: 5mm;
            background: linear-gradient(90deg, var(--gold) 0%, var(--gold-lt) 50%, var(--gold) 100%);
            flex-shrink: 0;
            margin-top: auto;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }

        /* ═══════════════════════════════════════
           PRINT OVERRIDES
        ═══════════════════════════════════════ */
        @media print {
            body {
                background: none;
                padding: 0;
                display: block;
            }

            .toolbar { display: none !important; }

            .slip {
                width: 210mm;
                height: 297mm;
                box-shadow: none;
            }
        }
    </style>
</head>
<body>

<!-- Toolbar (screen only) -->
<div class="toolbar">
    <span>📄 Examination Slip Preview — <?php echo htmlspecialchars($exam_slip['slip_number']); ?></span>
    <div class="toolbar-btns">
        <button class="tbtn tbtn-print" onclick="triggerPrint()">🖨 Print / Save as PDF</button>
        <button class="tbtn tbtn-close" onclick="window.close()">✕ Close</button>
    </div>
</div>

<!-- ═══════════════ A4 SLIP ═══════════════ -->
<div class="slip">

    <div class="top-bar"></div>

    <!-- HEADER -->
    <div class="header">

        <div class="logo-wrap">
            <img src="/public/uploads/applications/print/logo.png"
                 alt="Logo"
                 onerror="this.style.display='none'; this.parentNode.innerHTML='<div class=\'logo-fallback\'><span class=\'lf-top\'>FCT</span><span class=\'lf-mid\'>CNS</span><span class=\'lf-btm\'>Nursing</span></div>';">
        </div>

        <div class="header-centre">
            <div class="inst-name">FCT College of Nursing Sciences</div>
            <div class="inst-sub">Gwagwalada, Abuja — Federal Capital Territory</div>
            <div class="session-pill">Official Examination Slip &mdash; 2025 / 2026 Admissions Screening</div>
        </div>

        <div class="official-box">
            <span class="ob-label">Document Type</span>
            <span class="ob-main">Exam<br>Slip</span>
        </div>

    </div>

    <!-- SLIP NUMBER BAR -->
    <div class="slip-bar">
        <div class="sb-left">
            SLIP NO:&nbsp;<span class="sb-no"><?php echo htmlspecialchars($exam_slip['slip_number']); ?></span>
        </div>
        <div class="sb-right">
            Generated: <?php echo date('d F Y, h:i A', strtotime($exam_slip['generated_at'])); ?><br>
            Downloads: <?php echo (int)($exam_slip['download_count'] ?? 0); ?>
        </div>
    </div>

    <!-- BODY -->
    <div class="body">

        <!-- MEDIA ROW -->
        <div class="media-row">

            <div class="photo-col">
                <div class="photo-box">
                    <?php if (!empty($application['passport_photo'])): ?>
                        <img src="<?php echo htmlspecialchars($_SERVER['DOCUMENT_ROOT'] . $application['passport_photo']); ?>"
                             alt="Passport Photo">
                    <?php else: ?>
                        <div class="no-photo">Photograph<br>Not<br>Available</div>
                    <?php endif; ?>
                </div>
                <div class="media-cap">Passport Photo</div>
            </div>

            <div class="qr-col">
                <div class="qr-box">
                    <?php if (!empty($exam_slip['qr_code'])): ?>
                        <img src="<?php echo htmlspecialchars($exam_slip['qr_code']); ?>" alt="QR Code">
                    <?php else: ?>
                        <div id="qrCanvas"></div>
                        <div id="qrFallback" style="display:none;">
                            <img src="/application-verify/qr/<?php echo urlencode($exam_slip['slip_number']); ?>" alt="QR Code">
                        </div>
                    <?php endif; ?>
                </div>
                <div class="media-cap">Scan to Verify</div>
            </div>

            <div class="info-col">
                <div class="irow">
                    <span class="irow-lbl">Full Name</span>
                    <span class="irow-val name"><?php echo htmlspecialchars($application['first_name'] . ' ' . $application['last_name']); ?></span>
                </div>
                <div class="irow">
                    <span class="irow-lbl">Application No.</span>
                    <span class="irow-val"><?php echo htmlspecialchars($application['application_number']); ?></span>
                </div>
                <div class="irow">
                    <span class="irow-lbl">JAMB Reg. No.</span>
                    <span class="irow-val"><?php echo htmlspecialchars($application['jamb_number']); ?></span>
                </div>
                <div class="irow">
                    <span class="irow-lbl">Programme</span>
                    <span class="irow-val prog"><?php echo htmlspecialchars($application['program_choice_1']); ?></span>
                </div>
            </div>

        </div><!-- /media-row -->

        <!-- EXAM DETAILS -->
        <div class="section-hd">Examination Details</div>

        <div class="exam-grid">
            <table class="etable">
                <tr class="hi">
                    <td class="el">Examination Date</td>
                    <td class="ev"><?php echo date('l, jS F Y', strtotime($exam_slip['exam_date'])); ?></td>
                </tr>
                <tr>
                    <td class="el">Examination Time</td>
                    <td class="ev"><?php echo date('h:i A', strtotime($exam_slip['exam_time'])); ?></td>
                </tr>
                <tr class="danger">
                    <td class="el">Reporting Time</td>
                    <td class="ev">
                        ⚠ <?php echo date('h:i A', strtotime($exam_slip['reporting_time'])); ?>
                        <span style="font-size:7pt;font-weight:400;"> — arrive 30 mins early</span>
                    </td>
                </tr>
            </table>

            <table class="etable">
                <tr>
                    <td class="el">Venue</td>
                    <td class="ev"><?php echo htmlspecialchars($exam_slip['exam_venue']); ?></td>
                </tr>
                <tr>
                    <td class="el">Seat Number</td>
                    <td class="ev"><span class="seat-val"><?php echo htmlspecialchars($exam_slip['seat_number']); ?></span></td>
                </tr>
                <tr>
                    <td class="el">Status</td>
                    <td class="ev" style="color:#1a6b3a;font-weight:900;">✔ Payment Confirmed</td>
                </tr>
            </table>
        </div>

        <!-- INSTRUCTIONS -->
        <div class="instructions">
            <div class="instr-hd">Important Instructions — Please Read Carefully Before the Examination</div>
            <ol>
                <li>Bring this <strong>printed slip</strong> to the examination venue — it is required for entry and must not be folded or damaged.</li>
                <li>Arrive at least <strong>30 minutes</strong> before the scheduled reporting time. <strong>Latecomers will not be admitted.</strong></li>
                <li>Come with your writing materials: pen (blue or black ink), pencil, and eraser.</li>
                <li>Present a valid government-issued photo ID — National ID Card, Driver's Licence, or International Passport.</li>
                <li>Electronic devices including <strong>mobile phones, calculators, and smartwatches</strong> are strictly prohibited inside the examination hall.</li>
                <li>The QR code on this slip will be scanned at the entrance for identity and payment verification.</li>
                <li>Candidates must occupy <strong>only the seat number</strong> assigned on this slip. Seat swapping is a disqualifiable offence.</li>
            </ol>
        </div>

        <!-- SIGNATURES -->
        <div class="sig-row">
            <div class="sig-block">
                <div class="sig-line"></div>
                <div class="sig-name">Chairman</div>
                <div class="sig-title">Admissions Committee</div>
            </div>
            <div class="sig-block">
                <div class="stamp">Official<br>Stamp</div>
            </div>
            <div class="sig-block">
                <div class="sig-line"></div>
                <div class="sig-name">Registrar</div>
                <div class="sig-title">FCT College of Nursing Sciences</div>
            </div>
        </div>

        <!-- FOOTER -->
        <div class="footer">
            <div class="footer-l">
                This slip is computer-generated and does not require a handwritten signature.<br>
                Any alteration or falsification of this document is a criminal offence.<br>
                Enquiries: admissions@fctcns.edu.ng &nbsp;|&nbsp; +234 000 0000 000
            </div>
            <div class="footer-r">
                Verify at:<br>
                <span class="ver-url"><?php echo BASE_URL; ?>/verify/slip/<?php echo $exam_slip['slip_number']; ?></span>
            </div>
        </div>

    </div><!-- /body -->

    <div class="bottom-bar"></div>

</div><!-- /slip -->

<!-- SCRIPTS -->
<?php if (empty($exam_slip['qr_code'])): ?>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        generateQR();
    });

    function generateQR() {
        var slipNumber = '<?php echo addslashes(urlencode($exam_slip['slip_number'])); ?>';
        var verUrl     = '<?php echo addslashes(BASE_URL); ?>/verify/slip/<?php echo urlencode($exam_slip['slip_number']); ?>';
        var container  = document.getElementById('qrCanvas');
        var fallback   = document.getElementById('qrFallback');

        if (!container) return;

        // Attempt 1: server endpoint
        var serverImg = new Image();
        serverImg.src = '/application-verify/qr/' + slipNumber + '?t=' + Date.now();
        serverImg.style.cssText = 'width:100%;height:100%;object-fit:contain;display:block;';

        serverImg.onload = function () {
            container.innerHTML = '';
            container.appendChild(serverImg);
        };

        serverImg.onerror = function () {
            // Attempt 2: Google Charts
            var gImg = new Image();
            gImg.src = 'https://chart.googleapis.com/chart?chs=200x200&cht=qr&chl=' + encodeURIComponent(verUrl) + '&choe=UTF-8';
            gImg.style.cssText = 'width:100%;height:100%;object-fit:contain;display:block;';

            gImg.onload = function () {
                container.innerHTML = '';
                container.appendChild(gImg);
            };

            gImg.onerror = function () {
                // Attempt 3: QRCode.js
                if (typeof QRCode !== 'undefined') {
                    container.innerHTML = '';
                    var cv = document.createElement('canvas');
                    container.appendChild(cv);
                    var sz = container.parentElement ? container.parentElement.offsetWidth : 100;
                    QRCode.toCanvas(cv, verUrl, {
                        width: sz, margin: 1,
                        color: { dark: '#0d2b4e', light: '#ffffff' }
                    }, function (err) {
                        if (err && fallback) {
                            container.style.display = 'none';
                            fallback.style.display = 'flex';
                        }
                    });
                } else if (fallback) {
                    container.style.display = 'none';
                    fallback.style.display = 'flex';
                }
            };
        };
    }
</script>
<?php endif; ?>

<script>
    function triggerPrint() {
        setTimeout(function () { window.print(); }, 800);
    }

    // Auto-print when opened as popup from printExamSlip()
    window.addEventListener('load', function () {
        if (window.opener) {
            setTimeout(function () { window.print(); }, 900);
        }
    });
</script>

</body>
</html>