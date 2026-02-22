<?php
/**
 * Exam Slip Print View
 * SIMPLIFIED: Using only server-generated QR - User clicks Print button before print preview
 *
 * @package FCTCNS
 */

// =========================================================
// FIX: Add require for SecurityHelper and SecurityTrait
// =========================================================
require_once APP_PATH . '/helpers/SecurityHelper.php';
require_once APP_PATH . '/helpers/SecurityTrait.php';

class ExamSlipPrintView {
    use SecurityTrait;
    
    public function render($data) {
        extract($data);
        
        // Get security tokens
        $csp_nonce = $this->getCspNonce();
        $csrf_token = $this->getCsrfToken();

        $baseUrl    = defined('BASE_URL') ? rtrim(BASE_URL, '/') : '';
        $slipNumber = $exam_slip['slip_number'] ?? '';

        // ── Logo path: public/assets/images/logo/logo.png ──────────────
        $logoUrl    = $baseUrl . '/assets/images/logo/logo.png';
        ?>
        <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            
            <!-- ========================================================= -->
            <!-- 2. Add security meta tags in the head -->
            <!-- ========================================================= -->
            <?php echo $this->getSecurityMetaTags(); ?>
            
            <title>Examination Slip — <?php echo $this->e($slipNumber); ?></title>

            <!-- CSRF Token for JavaScript -->
            <meta name="csrf-token" content="<?php echo $this->e($csrf_token); ?>">

            <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">
            <meta http-equiv="Pragma" content="no-cache">
            <meta http-equiv="Expires" content="0">

            <!-- ========================================================= -->
            <!-- 3. Add CSP nonce to all style tags -->
            <!-- ========================================================= -->
            <style nonce="<?php echo $csp_nonce; ?>">
                /* ── Reset ──────────────────────────────────────────────── */
                *, *::before, *::after { margin: 0; padding: 0; box-sizing: border-box; }

                :root {
                    /* Purple brand palette */
                    --pu:         #6E026F;
                    --pu-dark:    #500150;
                    --pu-deeper:  #380038;
                    --pu-light:   #b84fb9;
                    --pu-pale:    #f9edf9;

                    /* Neutrals */
                    --black:      #000;
                    --g1:         #1a1a1a;
                    --g2:         #4a4a4a;
                    --g3:         #888;
                    --gbg:        #f5f5f5;
                    --rule:       #cccccc;
                    --white:      #fff;
                    --red:        #b00020;

                    /* Gold accent (amounts / strips) */
                    --gold:       #b8860b;
                    --gold-light: #e6c04a;
                }

                /* ── Screen body ─────────────────────────────────────────── */
                body {
                    font-family: 'Times New Roman', Times, serif;
                    background: #e8e2ec;
                    min-height: 100vh;
                    display: flex;
                    flex-direction: column;
                    align-items: center;
                    padding: 0 0 40px;
                }

                /* ── Toolbar (screen only) ───────────────────────────────── */
                .toolbar {
                    width: 100%;
                    background: var(--pu-deeper);
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
                    box-shadow: 0 2px 14px rgba(0,0,0,.4);
                    border-bottom: 2px solid rgba(255,255,255,.08);
                }

                .toolbar-title { display: flex; flex-direction: column; gap: 2px; }
                .toolbar-title strong { font-size: 14px; letter-spacing: .02em; }
                .toolbar-title span   { font-size: 11px; opacity: .62; }

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
                    letter-spacing: .02em;
                    text-decoration: none;
                    transition: opacity .15s, transform .15s;
                    font-family: Arial, Helvetica, sans-serif;
                }

                .tbtn:hover { opacity: .88; transform: translateY(-1px); }

                .tbtn-print { background: var(--pu); color: #fff; }
                .tbtn-close { background: rgba(255,255,255,.16); color: #fff; }

                /* ── Slip wrapper ────────────────────────────────────────── */
                .slip-wrapper {
                    margin-top: 28px;
                    width: 100%;
                    max-width: 820px;
                    padding: 0 16px;
                }

                /* ── Slip document ───────────────────────────────────────── */
                .slip {
                    background: var(--white);
                    width: 100%;
                    min-height: calc((100vw - 32px) * 1.414);
                    max-width: 210mm;
                    margin: 0 auto;
                    padding: 12mm 14mm 10mm;
                    position: relative;
                    overflow: hidden;
                    box-shadow: 0 6px 40px rgba(0,0,0,.42);
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
                    color: rgba(110,2,111,.04);
                    letter-spacing: .15em;
                    pointer-events: none;
                    white-space: nowrap;
                    z-index: 0;
                }

                /* ── Double border frame ─────────────────────────────────── */
                .border-frame {
                    position: relative;
                    z-index: 1;
                    flex: 1;
                    display: flex;
                    flex-direction: column;
                    border: 3px solid var(--pu-dark);
                    padding: 3px;
                }

                .border-frame-inner {
                    border: 1.5px solid var(--pu-light);
                    padding: 10px 13px 8px;
                    flex: 1;
                    display: flex;
                    flex-direction: column;
                }

                /* ── Institution header ──────────────────────────────────── */
                .institution-header {
                    display: flex;
                    align-items: center;
                    gap: 10px;
                    padding-bottom: 12px;
                    border-bottom: 3px double var(--pu-dark);
                    margin-bottom: 13px;
                }

                .logo-box {
                    flex-shrink: 0;
                    width: 68px; height: 68px;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                }

                .logo-box img {
                    width: 68px; height: 68px;
                    object-fit: contain;
                    display: block;
                }

                /* Fallback initials badge when logo fails */
                .logo-fallback {
                    width: 68px; height: 68px;
                    border-radius: 50%;
                    background: var(--pu-dark);
                    display: flex;
                    flex-direction: column;
                    align-items: center;
                    justify-content: center;
                    color: #fff;
                    text-align: center;
                    -webkit-print-color-adjust: exact;
                    print-color-adjust: exact;
                }

                .lf-top { font-size: 5.5pt; font-weight: 700; letter-spacing: .05em; display: block; }
                .lf-mid { font-size: 15pt;  font-weight: 900; line-height: 1; display: block; }
                .lf-btm { font-size: 4.5pt; letter-spacing: .04em; display: block; }

                .institution-text { flex: 1; text-align: center; }

                .institution-name {
                    font-size: 15pt;
                    font-weight: 900;
                    text-transform: uppercase;
                    color: var(--pu-dark);
                    letter-spacing: .04em;
                    line-height: 1.2;
                }

                .institution-address {
                    font-size: 8.5pt;
                    color: var(--g2);
                    margin: 5px 0 7px;
                }

                .slip-badge {
                    display: inline-block;
                    background: var(--pu-dark);
                    color: var(--white);
                    font-size: 7pt;
                    font-weight: 700;
                    letter-spacing: .09em;
                    text-transform: uppercase;
                    padding: 2px 12px;
                    -webkit-print-color-adjust: exact;
                    print-color-adjust: exact;
                }

                .logo-spacer { flex-shrink: 0; width: 68px; }

                /* ── Slip number bar ─────────────────────────────────────── */
                .slip-number-bar {
                    background: var(--pu-pale);
                    border: 1px solid #dbb8db;
                    border-left: 4px solid var(--pu);
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
                .sn-value     { font-weight: 900; color: var(--pu-dark); font-size: 10pt; letter-spacing: .06em; }
                .sn-generated { color: var(--g3); font-size: 7.5pt; }

                /* ── Media row: photo | QR | candidate info ──────────────── */
                .media-row {
                    display: flex;
                    gap: 10px;
                    margin-bottom: 13px;
                }

                .photo-panel { flex: 0 0 96px; }

                .photo-box {
                    width: 96px; height: 116px;
                    border: 2px solid var(--pu-dark);
                    overflow: hidden;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    background: var(--gbg);
                }

                .photo-box img {
                    width: 100%; height: 100%;
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
                    letter-spacing: .05em;
                }

                /* QR */
                .qr-panel { flex: 0 0 96px; }

                .qr-box {
                    width: 96px; height: 96px;
                    border: 2px solid var(--pu-dark);
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    background: var(--white);
                    padding: 4px;
                    overflow: hidden;
                    position: relative;
                }

                /* Purple corner marks on QR */
                .qr-box::before,
                .qr-box::after {
                    content: '';
                    position: absolute;
                    width: 10px; height: 10px;
                    border-color: var(--pu);
                    border-style: solid;
                }
                .qr-box::before { top: 3px;  left: 3px;  border-width: 2px 0 0 2px; }
                .qr-box::after  { bottom: 3px; right: 3px; border-width: 0 2px 2px 0; }

                .qr-box img {
                    width: 84px; height: 84px;
                    display: block;
                    image-rendering: -webkit-optimize-contrast;
                    image-rendering: crisp-edges;
                }

                /* Candidate info */
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
                    background: var(--pu-pale);
                    font-size: 7pt;
                    font-weight: 700;
                    text-transform: uppercase;
                    letter-spacing: .04em;
                    color: var(--pu-dark);
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

                .ir-value.name-field      { font-size: 9.5pt; color: var(--pu-dark); }
                .ir-value.programme-field { color: var(--pu);  font-style: italic; }

                /* ── Exam details ────────────────────────────────────────── */
                .section-title {
                    background: var(--pu-dark);
                    color: var(--white);
                    font-size: 7.5pt;
                    font-weight: 700;
                    text-transform: uppercase;
                    letter-spacing: .1em;
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
                    background: var(--pu-pale);
                    font-weight: 700;
                    color: var(--pu-dark);
                    font-size: 7pt;
                    text-transform: uppercase;
                    letter-spacing: .04em;
                    width: 38%;
                    -webkit-print-color-adjust: exact;
                    print-color-adjust: exact;
                }

                .et-value { font-weight: 600; color: var(--g1); }

                .highlight-row .et-label,
                .highlight-row .et-value {
                    background: var(--pu-pale);
                    color: var(--pu-dark);
                    font-weight: 900;
                    -webkit-print-color-adjust: exact;
                    print-color-adjust: exact;
                }

                .highlight-row .et-value { font-size: 9.5pt; }
                .danger-row .et-value    { color: var(--red); font-weight: 700; }

                .seat-display {
                    font-size: 15pt;
                    font-weight: 900;
                    color: var(--pu-dark);
                    letter-spacing: .08em;
                }

                /* ── Instructions ────────────────────────────────────────── */
                .instructions {
                    border: 1.5px solid var(--pu-dark);
                    margin-top: 13px;
                    page-break-inside: avoid;
                }

                .instr-header {
                    background: var(--pu-dark);
                    color: var(--white);
                    font-size: 7.5pt;
                    font-weight: 700;
                    letter-spacing: .1em;
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

                /* ── Signatures ──────────────────────────────────────────── */
                .signature-row {
                    display: flex;
                    justify-content: space-between;
                    margin-top: 22px;
                    align-items: flex-end;
                }

                .sig-block    { text-align: center; width: 150px; }
                .sig-line     { border-bottom: 1px solid var(--black); height: 38px; margin-bottom: 5px; }
                .sig-name     { font-size: 7pt; font-weight: 700; text-transform: uppercase; letter-spacing: .04em; color: var(--g2); }
                .sig-title    { font-size: 6.5pt; color: var(--g3); margin-top: 2px; }

                .stamp-circle {
                    width: 64px; height: 64px;
                    border-radius: 50%;
                    border: 2px dashed #c0a0c0;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    flex-direction: column;
                    color: #c0a0c0;
                    font-size: 5pt;
                    text-transform: uppercase;
                    letter-spacing: .05em;
                    text-align: center;
                    margin: 0 auto;
                }

                /* ── Footer ──────────────────────────────────────────────── */
                .footer {
                    margin-top: 20px;
                    padding-top: 8px;
                    border-top: 2px solid var(--pu-dark);
                    display: flex;
                    align-items: flex-start;
                    justify-content: space-between;
                    gap: 10px;
                }

                .footer-left  { font-size: 6.5pt; color: var(--g3); line-height: 1.9; }
                .footer-right { text-align: right; font-size: 6.5pt; color: var(--g3); line-height: 1.9; }

                .verification-url { font-size: 7pt; color: var(--pu-dark); word-break: break-all; }

                .gold-strip {
                    height: 4px;
                    background: linear-gradient(90deg, var(--pu) 0%, var(--pu-light) 50%, var(--pu) 100%);
                    margin-top: 10px;
                    -webkit-print-color-adjust: exact;
                    print-color-adjust: exact;
                }

                /* Toast notification */
                .toast-notification {
                    position: fixed;
                    top: 20px;
                    right: 20px;
                    padding: 12px 20px;
                    border-radius: 8px;
                    color: white;
                    font-size: 14px;
                    font-weight: 500;
                    display: flex;
                    align-items: center;
                    gap: 10px;
                    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
                    z-index: 10000;
                    animation: slideInRight 0.3s ease;
                }

                @keyframes slideInRight {
                    from {
                        opacity: 0;
                        transform: translateX(100%);
                    }
                    to {
                        opacity: 1;
                        transform: translateX(0);
                    }
                }

                .toast-info { background: #17a2b8; }
                .toast-success { background: #28a745; }
                .toast-error { background: #dc3545; }

                /* ── Print rules ─────────────────────────────────────────── */
                @media print {
                    @page { size: A4 portrait; margin: 0; }

                    body {
                        background: white;
                        padding: 0; margin: 0;
                        display: block;
                    }

                    .toolbar,
                    .toast-notification { display: none !important; }
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

                    .slip::before { opacity: .025; }
                }

                /* ── Responsive ──────────────────────────────────────────── */
                @media (max-width: 650px) {
                    .slip { padding: 6mm 5mm; }
                    .institution-name { font-size: 11pt; }
                    .logo-box, .logo-spacer { width: 50px; }
                    .logo-box img { width: 50px; height: 50px; }
                    .media-row { flex-wrap: wrap; }
                    .photo-panel, .qr-panel { flex: 0 0 80px; }
                    .photo-box  { width: 80px; height: 96px; }
                    .qr-box     { width: 80px; height: 80px; }
                    .qr-box img { width: 70px; height: 70px; }
                }
            </style>
        </head>
        <body>

        <!-- ── Toolbar (screen only) ───────────────────────────────────── -->
        <div class="toolbar">
            <div class="toolbar-title">
                <strong>&#128196; Examination Slip Preview</strong>
                <span>Slip No: <?php echo $this->e($slipNumber); ?></span>
            </div>
            <div class="toolbar-actions">
                <button class="tbtn tbtn-print" onclick="triggerPrint(this)">
                    &#128424;&nbsp; Print / Save PDF
                </button>
                <button class="tbtn tbtn-close" onclick="closeWindow()">
                    &#10005;&nbsp; Close
                </button>
            </div>
        </div>

        <!-- ── Slip document ───────────────────────────────────────────── -->
        <div class="slip-wrapper">
        <div class="slip">
        <div class="border-frame">
        <div class="border-frame-inner">

            <!-- Institution header -->
            <div class="institution-header">

                <div class="logo-box">
                    <img src="<?php echo $this->e($logoUrl); ?>"
                         alt="FCT CNS Logo"
                         onerror="
                             this.style.display='none';
                             this.parentNode.innerHTML='<div class=\'logo-fallback\'><span class=\'lf-top\'>FCT</span><span class=\'lf-mid\'>CNS</span><span class=\'lf-btm\'>Nursing</span></div>';
                         ">
                </div>

                <div class="institution-text">
                    <div class="institution-name">FCT College of Nursing Sciences</div>
                    <div class="institution-address">Gwagwalada, Abuja &mdash; Federal Capital Territory</div>
                    <div class="slip-badge">Official Examination Slip &mdash; 2025/2026 Admissions Screening Exercise</div>
                </div>

                <div class="logo-spacer"></div>
            </div>

            <!-- Slip number bar -->
            <div class="slip-number-bar">
                <span>
                    <span class="sn-label">SLIP NO: </span>
                    <span class="sn-value"><?php echo $this->e($slipNumber); ?></span>
                </span>
                <span class="sn-generated">
                    Generated: <?php echo $this->e(date('d F Y, h:i A', strtotime($exam_slip['generated_at'] ?? date('Y-m-d H:i:s')))); ?>
                    &nbsp;|&nbsp; Downloads: <?php echo (int)($exam_slip['download_count'] ?? 0); ?>
                </span>
            </div>

            <!-- Media row: photo | QR | candidate info -->
            <div class="media-row">

                <!-- Passport photo -->
                <div class="photo-panel">
                    <div class="photo-box">
                        <?php if (!empty($application['passport_photo'])): ?>
                            <img src="<?php echo $this->e($application['passport_photo']); ?>"
                                 alt="Passport Photo"
                                 onerror="this.outerHTML='<div class=\'no-photo\'>Photo<br>Not Found</div>';">
                        <?php else: ?>
                            <div class="no-photo">Photograph<br>Not Available</div>
                        <?php endif; ?>
                    </div>
                    <div class="media-caption">Passport Photograph</div>
                </div>

                <!-- QR code (server-generated) -->
                <div class="qr-panel">
                    <?php
                    $qrUrl = $baseUrl . '/application-verify/generate-qr/' . urlencode($slipNumber) . '?t=' . time() . '&csrf=' . urlencode($csrf_token);
                    ?>
                    <div class="qr-box">
                        <img src="<?php echo $this->e($qrUrl); ?>"
                             alt="QR Code"
                             onerror="this.onerror=null; this.parentNode.innerHTML='<div style=\'font-size:8pt;color:#999;text-align:center;padding:10px;\'>QR<br>Unavailable</div>';">
                    </div>
                    <div class="media-caption">Scan to Verify</div>
                </div>

                <!-- Candidate info -->
                <div class="candidate-info">
                    <div class="info-row">
                        <span class="ir-label">Full Name</span>
                        <span class="ir-value name-field">
                            <?php
                            $fullName = trim(
                                ($applicant['title']      ?? '') . ' ' .
                                ($applicant['first_name'] ?? '') . ' ' .
                                ($applicant['last_name']  ?? '')
                            );
                            echo $this->e(
                                $fullName ?: ($application['first_name'] . ' ' . $application['last_name'])
                            );
                            ?>
                        </span>
                    </div>
                    <div class="info-row">
                        <span class="ir-label">Application No.</span>
                        <span class="ir-value"><?php echo $this->e($application['application_number']); ?></span>
                    </div>
                    <div class="info-row">
                        <span class="ir-label">JAMB Reg. No.</span>
                        <span class="ir-value"><?php echo $this->e($application['jamb_number']); ?></span>
                    </div>
                    <div class="info-row">
                        <span class="ir-label">Programme</span>
                        <span class="ir-value programme-field"><?php echo $this->e($application['program_choice_1']); ?></span>
                    </div>
                </div>

            </div><!-- /media-row -->

            <!-- Exam details -->
            <div class="section-title">Examination Details</div>
            <div class="exam-cols">

                <table class="exam-table">
                    <tr class="highlight-row">
                        <td class="et-label">Examination Date</td>
                        <td class="et-value"><?php echo $this->e(date('l, jS F Y', strtotime($exam_slip['exam_date'] ?? date('Y-m-d')))); ?></td>
                    </tr>
                    <tr>
                        <td class="et-label">Examination Time</td>
                        <td class="et-value"><?php echo $this->e($exam_slip['exam_time'] ?? '10:00 AM'); ?></td>
                    </tr>
                    <tr class="danger-row">
                        <td class="et-label">Reporting Time</td>
                        <td class="et-value">
                            &#9888; <?php echo $this->e($exam_slip['reporting_time'] ?? '8:30 AM'); ?>
                            <span style="font-size:7pt;font-weight:400;"> (Arrive 30 mins early)</span>
                        </td>
                    </tr>
                </table>

                <table class="exam-table">
                    <tr>
                        <td class="et-label">Venue</td>
                        <td class="et-value"><?php echo $this->e($exam_slip['exam_venue'] ?? 'FCT College of Nursing Sciences'); ?></td>
                    </tr>
                    <tr>
                        <td class="et-label">Seat Number</td>
                        <td class="et-value">
                            <span class="seat-display"><?php echo $this->e($exam_slip['seat_number'] ?? 'A001'); ?></span>
                        </td>
                    </tr>
                </table>

            </div><!-- /exam-cols -->

            <!-- Instructions -->
            <div class="instructions">
                <div class="instr-header">Important Instructions &mdash; Please Read Carefully</div>
                <ol>
                    <li>Bring this printed slip to the examination venue &mdash; it is required for entry.</li>
                    <li>Arrive at least <strong>30 minutes</strong> before the scheduled reporting time. Latecomers will not be admitted.</li>
                    <li>Come with writing materials: pen, pencil, and eraser.</li>
                    <li>Present a valid photo ID &mdash; National ID Card, Driver&rsquo;s Licence, or International Passport.</li>
                    <li>Electronic devices including mobile phones, calculators, and smartwatches are <strong>strictly prohibited</strong> inside the hall.</li>
                    <li>The QR code printed on this slip will be scanned at the entrance for identity verification.</li>
                    <li>Candidates must sit only at the seat number assigned on this slip.</li>
                </ol>
            </div>

            <!-- Signatures -->
            <div class="signature-row">
                <div class="sig-block">
                    <div class="sig-line"></div>
                    <div class="sig-name">Hall Name</div>
                    <div class="sig-title">Examination Hall</div>
                </div>
                <div class="sig-block" style="text-align:center;">
                    <div class="stamp-circle">Official<br>Stamp</div>
                </div>
                <div class="sig-block">
                    <div class="sig-line"></div>
                    <div class="sig-name">Verification Officer</div>
                    <div class="sig-title">Signature</div>
                </div>
            </div>

            <!-- Footer -->
            <div class="footer">
                <div class="footer-left">
                    This slip is computer-generated and does not require a handwritten signature.<br>
                    Any alteration or falsification of this document is a criminal offence.<br>
                    Enquiries: admissions@fctcns.edu.ng &nbsp;|&nbsp; +234 000 0000 000
                </div>
                <div class="footer-right">
                    Verification URL:<br>
                    <span class="verification-url">
                        <?php echo $baseUrl; ?>/application-verify/slip/<?php echo urlencode($slipNumber); ?>
                    </span>
                </div>
            </div>

            <div class="gold-strip"></div>

        </div><!-- /border-frame-inner -->
        </div><!-- /border-frame -->
        </div><!-- /slip -->
        </div><!-- /slip-wrapper -->

        <!-- ========================================================= -->
        <!-- 4. Add CSP nonce to all script tags -->
        <!-- ========================================================= -->
        <script nonce="<?php echo $csp_nonce; ?>">
            // ======================================================
            // Exam Slip Print JavaScript with Security Enhancements
            // ======================================================
            
            // Get CSRF token from meta tag
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

            // Show toast notification
            function showToast(msg, type = 'info') {
                // Remove existing toasts
                document.querySelectorAll('.toast-notification').forEach(t => t.remove());
                
                // Create toast element
                const toast = document.createElement('div');
                toast.className = `toast-notification toast-${type}`;
                toast.setAttribute('role', 'alert');
                
                const icon = type === 'success' ? 'fa-check-circle' : 
                            type === 'error' ? 'fa-exclamation-circle' : 'fa-info-circle';
                
                // Sanitize message to prevent XSS
                const safeMsg = String(msg).replace(/[<>]/g, '');
                
                toast.innerHTML = `<i class="fas ${icon}"></i> ${safeMsg}`;
                
                document.body.appendChild(toast);
                
                // Auto remove after 3 seconds
                setTimeout(() => {
                    toast.style.transition = 'opacity 0.3s, transform 0.3s';
                    toast.style.opacity = '0';
                    toast.style.transform = 'translateX(100%)';
                    setTimeout(() => toast.remove(), 300);
                }, 3000);
            }

            // Track print attempt
            function trackPrint() {
                if (csrfToken) {
                    fetch('/api/track-event', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrfToken
                        },
                        body: JSON.stringify({
                            event: 'exam_slip_print',
                            slipNumber: '<?php echo $this->e($slipNumber); ?>',
                            timestamp: new Date().toISOString()
                        })
                    }).catch(err => console.error('Tracking failed:', err));
                }
            }

            // Trigger print with security
            function triggerPrint(btn) {
                var orig = btn.innerHTML;
                btn.innerHTML = '&#128424; Preparing&hellip;';
                btn.disabled  = true;
                
                // Track print attempt
                trackPrint();
                
                // Show preparing message
                showToast('Preparing your document for printing...', 'info');
                
                setTimeout(function () {
                    btn.innerHTML = orig;
                    btn.disabled  = false;
                    
                    // Trigger print
                    window.print();
                    
                    showToast('Print dialog opened', 'success');
                }, 500);
            }

            // Close window securely
            function closeWindow() {
                if (confirm('Are you sure you want to close this window?')) {
                    // Track close attempt
                    if (csrfToken) {
                        fetch('/api/track-event', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': csrfToken
                            },
                            body: JSON.stringify({
                                event: 'exam_slip_close',
                                slipNumber: '<?php echo $this->e($slipNumber); ?>',
                                timestamp: new Date().toISOString()
                            })
                        }).catch(err => console.error('Tracking failed:', err));
                    }
                    
                    window.close();
                }
            }

            // Auto-print when opened as a popup from step4.php
            window.addEventListener('load', function () {
                if (window.opener) {
                    showToast('Auto-printing in 1 second...', 'info');
                    setTimeout(function () { 
                        trackPrint();
                        window.print(); 
                    }, 1000);
                }

                // Log page view
                console.log('Exam slip print view loaded:', {
                    slipNumber: '<?php echo $this->e($slipNumber); ?>',
                    timestamp: new Date().toISOString()
                });
            });

            // Handle back button cache
            window.addEventListener('pageshow', function(event) {
                if (event.persisted) {
                    console.log('Page loaded from cache');
                }
            });

            // Prevent right-click on sensitive elements
            document.querySelectorAll('.qr-box, .slip-number-bar, .verification-url').forEach(el => {
                el.addEventListener('contextmenu', e => e.preventDefault());
            });

            // Add keyboard shortcuts
            document.addEventListener('keydown', function(e) {
                // Ctrl+P or Cmd+P for print
                if ((e.ctrlKey || e.metaKey) && e.key === 'p') {
                    e.preventDefault();
                    const printBtn = document.querySelector('.tbtn-print');
                    if (printBtn) {
                        triggerPrint(printBtn);
                    }
                }
                
                // Escape key to close
                if (e.key === 'Escape') {
                    if (confirm('Close this window?')) {
                        window.close();
                    }
                }
            });

            // Add performance marking
            performance.mark('print-view-loaded');
            
            // Log performance
            window.addEventListener('load', function() {
                performance.mark('print-view-fully-loaded');
                performance.measure('print-view-load', 'print-view-loaded', 'print-view-fully-loaded');
                const measures = performance.getEntriesByType('measure');
                console.log('Page load time:', measures[0]?.duration.toFixed(0) + 'ms');
            });

            // Check if window was opened with CSRF token
            const urlParams = new URLSearchParams(window.location.search);
            const urlCsrf = urlParams.get('csrf');
            
            if (urlCsrf && urlCsrf !== csrfToken) {
                console.warn('CSRF token mismatch in URL');
                showToast('Security token mismatch. Please refresh.', 'error');
            }

            // Log successful load
            console.log('Exam slip print view initialized successfully');
        </script>

        </body>
        </html>
        <?php
    }
}

// =========================================================
// 8. Add the view instantiation at the bottom
// =========================================================
$view = new ExamSlipPrintView();
$view->render(get_defined_vars());
?>