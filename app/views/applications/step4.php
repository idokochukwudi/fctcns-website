<?php
/**
 * Step 4 - Examination Slip View
 * @var array $application
 * @var array $exam_slip
 * @var array $applicant
 */

// =========================================================
// 1. Add the trait at the top of each view file
// =========================================================
require_once APP_PATH . '/helpers/SecurityTrait.php';

class ExamSlipView {
    use SecurityTrait;
    
    public function render($data) {
        extract($data);
        
        // Get security tokens
        $csp_nonce = $this->getCspNonce();
        $csrf_token = $this->getCsrfToken();
        
        $pageTitle = $pageTitle ?? 'Examination Slip - FCT College of Nursing Sciences';
        $baseUrl   = defined('BASE_URL') ? rtrim(BASE_URL, '/') : '';
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
            
            <title><?php echo $this->e($pageTitle); ?></title>
            
            <!-- Security Headers -->
            <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">
            <meta http-equiv="Pragma" content="no-cache">
            <meta http-equiv="Expires" content="0">
            
            <!-- CSRF Token for JavaScript -->
            <meta name="csrf-token" content="<?php echo $this->e($csrf_token); ?>">
            
            <!-- ========================================================= -->
            <!-- 3. Add CSP nonce to all style tags -->
            <!-- 7. Add SRI hashes to external scripts/styles -->
            <!-- ========================================================= -->
            <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&family=JetBrains+Mono:wght@400;500&display=swap" 
                  rel="stylesheet"
                  integrity="sha384-0pCryB3hBqYHZO9dKsIIzN8wH+Z4k5P+GZ8TlqM9m8A3TlPI9c7JZ6nG+K/t9fb"
                  crossorigin="anonymous">
            
            <link rel="stylesheet" 
                  href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"
                  integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw=="
                  crossorigin="anonymous" 
                  referrerpolicy="no-referrer">

            <style nonce="<?php echo $csp_nonce; ?>">
            /* ── Reset ────────────────────────────────────────────────────── */
            *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

            /* ── Palette: #6E026F purple ──────────────────────────────────── */
            :root {
              --pu:          #6E026F;
              --pu-dark:     #500150;
              --pu-mid:      #8a0d8b;
              --pu-light:    #b84fb9;
              --pu-pale:     #f9edf9;
              --pu-bg:       #f2dff2;

              --gold:        #c8860a;
              --gold-pale:   #fdf6e3;

              --green:       #1a6b45;
              --green-bg:    #edf9f3;

              --danger:      #c0392b;

              --blue:        #1d4ed8;
              --blue-bg:     #eff6ff;
              --blue-border: #bfdbfe;

              --text:        #1a0a1a;
              --text-muted:  #72587a;
              --border:      #e2d0e2;
              --border-dark: #c9b0c9;
              --bg:          #f7f0f7;
              --surface:     #ffffff;

              --radius:      12px;
              --radius-sm:   8px;
              --shadow:      0 1px 4px rgba(110,2,111,.07), 0 4px 18px rgba(110,2,111,.09);
              --shadow-lg:   0 2px 8px rgba(110,2,111,.08), 0 8px 36px rgba(110,2,111,.12);
            }

            html { scroll-behavior: smooth; }

            body {
              font-family: 'Outfit', sans-serif;
              background: var(--bg);
              color: var(--text);
              min-height: 100vh;
              font-size: 15px;
              line-height: 1.6;
            }

            /* ── Shell: fills the layout container, no max-width clipping ── */
            .slip-shell {
              width: 100%;
              padding: 1.25rem 1.5rem 2.5rem;
            }

            @media (max-width: 600px) { .slip-shell { padding: 0.75rem 0.75rem 2rem; } }

            /* ── Status strip ────────────────────────────────────────────── */
            .status-strip {
              display: flex;
              align-items: center;
              justify-content: space-between;
              flex-wrap: wrap;
              gap: 0.75rem;
              background: var(--surface);
              border: 1px solid var(--border);
              border-left: 4px solid var(--pu);
              border-radius: var(--radius-sm);
              padding: 0.8rem 1.2rem;
              margin-bottom: 1.35rem;
              box-shadow: var(--shadow);
            }

            .strip-left { display: flex; align-items: center; gap: 0.7rem; flex-wrap: wrap; }

            .strip-icon {
              width: 34px; height: 34px;
              background: var(--pu-pale);
              border: 1px solid var(--border);
              border-radius: var(--radius-sm);
              display: flex; align-items: center; justify-content: center;
              color: var(--pu); font-size: 0.95rem; flex-shrink: 0;
            }

            .strip-title { font-weight: 700; font-size: 0.92rem; color: var(--pu-dark); }

            .strip-sub {
              font-size: 0.76rem; color: var(--text-muted);
              display: flex; align-items: center; gap: 0.35rem; flex-wrap: wrap;
            }
            .strip-sub strong { color: var(--text); font-weight: 600; }

            .badge-verified {
              display: inline-flex;
              align-items: center;
              gap: 0.4rem;
              background: linear-gradient(135deg, #1a6b45, #22934f);
              color: #fff;
              font-size: 0.73rem;
              font-weight: 700;
              letter-spacing: 0.06em;
              text-transform: uppercase;
              padding: 0.42rem 1rem;
              border-radius: 50px;
              white-space: nowrap;
              box-shadow: 0 2px 10px rgba(26,107,69,.3);
            }

            /* ── Content grid ────────────────────────────────────────────── */
            .content-grid {
              display: grid;
              grid-template-columns: 1fr 290px;
              gap: 1.35rem;
              align-items: start;
            }

            @media (max-width: 1100px) { .content-grid { grid-template-columns: 1fr 270px; } }
            @media (max-width: 860px)  { .content-grid { grid-template-columns: 1fr; } }

            /* ── Slip card ───────────────────────────────────────────────── */
            .slip-card {
              background: var(--surface);
              border-radius: var(--radius);
              box-shadow: var(--shadow-lg);
              border: 1px solid var(--border);
              overflow: hidden;
              width: 100%;
            }

            /* Header bar */
            .slip-header {
              background: linear-gradient(135deg, var(--pu-dark) 0%, var(--pu) 60%, var(--pu-mid) 100%);
              padding: 1.1rem 1.6rem;
              display: flex;
              align-items: center;
              justify-content: space-between;
              gap: 0.75rem;
              flex-wrap: wrap;
              position: relative;
              overflow: hidden;
            }

            .slip-header::after {
              content: '';
              position: absolute; right: -50px; top: -50px;
              width: 170px; height: 170px;
              border-radius: 50%;
              border: 35px solid rgba(255,255,255,.06);
              pointer-events: none;
            }

            .slip-header-title {
              font-size: 1.05rem; font-weight: 700; color: #fff; line-height: 1.2;
            }

            .slip-header-sub {
              font-size: 0.74rem; color: rgba(255,255,255,.58); margin-top: 0.18rem;
            }

            .slip-number-pill {
              background: rgba(255,255,255,.13);
              border: 1px solid rgba(255,255,255,.28);
              color: rgba(255,255,255,.92);
              font-family: 'JetBrains Mono', monospace;
              font-size: 0.76rem;
              padding: 0.38rem 0.85rem;
              border-radius: 50px;
              white-space: nowrap;
              flex-shrink: 0;
            }

            /* Body */
            .slip-body {
              padding: 1.5rem 1.6rem;
            }

            @media (max-width: 480px) { .slip-body { padding: 1.1rem 0.9rem; } }

            /* ── Top trio ────────────────────────────────────────────────── */
            .top-trio {
              display: grid;
              grid-template-columns: 125px 125px 1fr;
              gap: 1.1rem;
              margin-bottom: 1.4rem;
              align-items: stretch;
            }

            @media (max-width: 700px) {
              .top-trio { grid-template-columns: 1fr 1fr; }
              .top-trio .verify-panel { grid-column: 1 / -1; }
            }

            @media (max-width: 380px) {
              .top-trio { grid-template-columns: 1fr; }
              .top-trio .verify-panel { grid-column: auto; }
            }

            /* Photo */
            .photo-wrap { display: flex; flex-direction: column; align-items: center; gap: 0.5rem; }

            .photo-frame {
              width: 125px; height: 125px;
              border-radius: var(--radius-sm);
              border: 2px solid var(--border);
              background: var(--pu-pale);
              overflow: hidden;
              display: flex; align-items: center; justify-content: center;
            }

            @media (max-width: 700px) { .photo-frame { width: 100%; height: auto; aspect-ratio: 1; } }

            .photo-frame img { width: 100%; height: 100%; object-fit: cover; display: block; }

            .no-photo { display: flex; flex-direction: column; align-items: center; gap: 0.4rem; color: var(--pu-light); font-size: 0.7rem; }

            .img-caption {
              font-size: 0.68rem; color: var(--text-muted); text-align: center;
              display: flex; align-items: center; justify-content: center; gap: 0.3rem;
              text-transform: uppercase; letter-spacing: 0.04em; font-weight: 500;
            }

            /* QR */
            .qr-wrap { display: flex; flex-direction: column; align-items: center; gap: 0.5rem; }

            .qr-frame {
              width: 125px; height: 125px;
              border-radius: var(--radius-sm);
              border: 2px solid var(--border);
              background: #fff;
              display: flex; align-items: center; justify-content: center;
              padding: 6px;
              position: relative;
            }

            @media (max-width: 700px) { .qr-frame { width: 100%; height: auto; aspect-ratio: 1; } }

            /* QR corner marks */
            .qr-frame::before,
            .qr-frame::after {
              content: '';
              position: absolute;
              width: 12px; height: 12px;
              border-color: var(--pu);
              border-style: solid;
            }
            .qr-frame::before { top: 4px; left: 4px; border-width: 2px 0 0 2px; border-radius: 2px 0 0 0; }
            .qr-frame::after  { bottom: 4px; right: 4px; border-width: 0 2px 2px 0; border-radius: 0 0 2px 0; }

            .qr-frame img {
              width: 100%; height: 100%; object-fit: contain;
              image-rendering: crisp-edges;
              image-rendering: -webkit-optimize-contrast;
            }

            .qr-error {
              text-align: center; font-size: 0.68rem; color: var(--danger);
              line-height: 1.45; padding: 0.5rem;
            }

            /* Verify panel */
            .verify-panel {
              background: linear-gradient(160deg, var(--pu-pale), #fff 70%);
              border: 1.5px solid var(--border);
              border-radius: var(--radius-sm);
              padding: 1rem 1.1rem;
              display: flex; flex-direction: column; gap: 0.65rem;
            }

            .verify-panel-title {
              font-size: 0.7rem; font-weight: 700; text-transform: uppercase;
              letter-spacing: 0.07em; color: var(--pu);
              display: flex; align-items: center; gap: 0.4rem;
              padding-bottom: 0.55rem; border-bottom: 1px solid var(--border);
            }

            .verify-item { display: flex; align-items: flex-start; gap: 0.5rem; }
            .verify-item i { margin-top: 0.15rem; flex-shrink: 0; width: 14px; text-align: center; }
            .vi-label { color: var(--text-muted); font-size: 0.68rem; line-height: 1; margin-bottom: 0.1rem; }
            .vi-val   { color: var(--text); font-weight: 600; font-size: 0.8rem; }
            .mono     { font-family: 'JetBrains Mono', monospace; font-size: 0.73rem !important; letter-spacing: .02em; }

            /* ── Details table ───────────────────────────────────────────── */
            .details-table {
              width: 100%;
              border-collapse: collapse;
              font-size: 0.875rem;
              border: 1.5px solid var(--border);
              border-radius: var(--radius-sm);
              overflow: hidden;
            }

            .details-table tr:not(:last-child) th,
            .details-table tr:not(:last-child) td { border-bottom: 1px solid var(--border); }

            .details-table th {
              background: var(--pu-pale);
              color: var(--pu-dark);
              font-weight: 600;
              font-size: 0.72rem;
              text-transform: uppercase;
              letter-spacing: 0.05em;
              padding: 0.78rem 1.1rem;
              width: 30%;
              white-space: nowrap;
              vertical-align: middle;
            }

            .details-table td {
              padding: 0.78rem 1.1rem;
              color: var(--text);
              font-weight: 500;
              vertical-align: middle;
            }

            @media (max-width: 520px) {
              .details-table th,
              .details-table td { padding: 0.6rem 0.7rem; font-size: 0.8rem; }
              .details-table th { width: 36%; white-space: normal; }
            }

            /* Highlighted rows */
            .row-exam-date th { background: var(--gold-pale); color: #7a540a; }
            .row-exam-date td { background: #fffbf0; font-weight: 700; font-size: 0.93rem; }

            .row-seat th { background: var(--green-bg); color: var(--green); }
            .row-seat td { background: #f2fbf6; }

            .seat-num { font-size: 1.4rem; font-weight: 800; color: var(--green); line-height: 1; }

            .badge-program {
              display: inline-flex; align-items: center;
              background: var(--blue-bg); color: var(--blue);
              border: 1px solid var(--blue-border);
              border-radius: 50px; padding: 0.22rem 0.75rem;
              font-size: 0.76rem; font-weight: 600;
            }

            .report-time {
              display: inline-flex; align-items: center; gap: 0.4rem;
              color: var(--danger); font-weight: 600;
            }

            /* ── Instructions ────────────────────────────────────────────── */
            .instructions {
              margin-top: 1.35rem;
              background: var(--blue-bg);
              border: 1.5px solid var(--blue-border);
              border-radius: var(--radius-sm);
              padding: 1.1rem 1.3rem;
              display: flex; gap: 0.9rem;
            }

            .instr-icon { color: var(--blue); font-size: 1.2rem; flex-shrink: 0; margin-top: 0.05rem; }

            .instructions h5 {
              font-size: 0.78rem; font-weight: 700; color: var(--blue);
              margin-bottom: 0.55rem; text-transform: uppercase; letter-spacing: 0.05em;
            }

            .instructions ol { padding-left: 1.05rem; display: flex; flex-direction: column; gap: 0.28rem; }
            .instructions li { font-size: 0.8rem; color: #1e3a8a; line-height: 1.5; }

            /* Footer line */
            .slip-footer-line {
              margin-top: 1.1rem; padding-top: 0.9rem;
              border-top: 1px dashed var(--border-dark);
              display: flex; align-items: center;
              justify-content: space-between; flex-wrap: wrap; gap: 0.5rem;
            }

            .slip-footer-line span {
              font-size: 0.7rem; color: var(--text-muted);
              display: flex; align-items: center; gap: 0.3rem;
            }

            /* ── Sidebar ─────────────────────────────────────────────────── */
            .sidebar { display: flex; flex-direction: column; gap: 1.1rem; }

            .side-card {
              background: var(--surface);
              border-radius: var(--radius);
              border: 1px solid var(--border);
              box-shadow: var(--shadow);
              padding: 1.2rem;
            }

            .side-card-label {
              font-size: 0.67rem; font-weight: 700; text-transform: uppercase;
              letter-spacing: 0.09em; color: var(--pu);
              margin-bottom: 0.85rem; padding-bottom: 0.65rem;
              border-bottom: 1px solid var(--border);
            }

            /* Action buttons */
            .action-list { display: flex; flex-direction: column; gap: 0.45rem; }

            .act-btn {
              display: flex; align-items: center; gap: 0.7rem;
              width: 100%; padding: 0.7rem 0.9rem;
              border-radius: var(--radius-sm);
              font-size: 0.84rem; font-weight: 600;
              border: none; cursor: pointer; text-decoration: none;
              transition: transform .15s, box-shadow .15s, opacity .15s;
              font-family: 'Outfit', sans-serif;
            }

            .act-btn::after {
              content: '\f054';
              font-family: 'Font Awesome 6 Free'; font-weight: 900;
              font-size: 0.62rem; margin-left: auto; opacity: 0.38;
            }

            .act-btn:hover { transform: translateY(-1px); }

            .act-icon {
              width: 28px; height: 28px;
              border-radius: 6px;
              display: flex; align-items: center; justify-content: center;
              font-size: 0.82rem; flex-shrink: 0;
            }

            /* PDF */
            .act-btn--pdf {
              background: linear-gradient(135deg, var(--pu-dark), var(--pu));
              color: #fff;
              box-shadow: 0 2px 10px rgba(110,2,111,.28);
            }
            .act-btn--pdf:hover { box-shadow: 0 4px 16px rgba(110,2,111,.4); color: #fff; }
            .act-btn--pdf .act-icon { background: rgba(255,255,255,.2); color: #fff; }

            /* Print */
            .act-btn--print {
              background: linear-gradient(135deg, var(--pu-mid), var(--pu-light));
              color: #fff;
              box-shadow: 0 2px 10px rgba(110,2,111,.2);
            }
            .act-btn--print:hover { box-shadow: 0 4px 16px rgba(110,2,111,.32); color: #fff; }
            .act-btn--print .act-icon { background: rgba(255,255,255,.2); color: #fff; }

            /* Share */
            .act-btn--share {
              background: var(--pu-pale);
              color: var(--pu-dark);
              border: 1.5px solid var(--border);
            }
            .act-btn--share:hover { background: var(--pu-bg); }
            .act-btn--share .act-icon { background: var(--pu); color: #fff; }

            /* Dashboard */
            .act-btn--dash {
              background: #f8f5f8; color: var(--text-muted);
              border: 1.5px solid var(--border);
            }
            .act-btn--dash:hover { background: #f0e8f0; color: var(--text); }
            .act-btn--dash .act-icon { background: var(--pu-bg); color: var(--pu-light); }

            /* ── Copy group ──────────────────────────────────────────────── */
            .copy-group {
              display: flex;
              border: 1.5px solid var(--border);
              border-radius: var(--radius-sm);
              overflow: hidden;
              background: #fafafa;
            }

            .copy-group input {
              flex: 1; border: none; background: transparent;
              padding: 0.52rem 0.7rem;
              font-size: 0.72rem; color: var(--text-muted);
              font-family: 'JetBrains Mono', monospace;
              outline: none; min-width: 0;
              overflow: hidden; text-overflow: ellipsis; white-space: nowrap;
            }

            .copy-btn {
              background: var(--pu); color: #fff; border: none;
              padding: 0.52rem 0.85rem;
              font-size: 0.74rem; font-weight: 600; cursor: pointer;
              font-family: 'Outfit', sans-serif;
              display: flex; align-items: center; gap: 0.3rem;
              white-space: nowrap; flex-shrink: 0;
              transition: background .15s;
            }
            .copy-btn:hover  { background: var(--pu-dark); }
            .copy-btn.copied { background: var(--green); }

            /* ── Quick summary ───────────────────────────────────────────── */
            .summary-grid { display: flex; flex-direction: column; gap: 0.6rem; }

            .summary-item {
              display: flex; align-items: center; gap: 0.75rem;
              padding: 0.65rem 0.85rem;
              border-radius: var(--radius-sm);
              background: var(--pu-pale);
              border: 1px solid var(--border);
            }

            .summary-item-icon {
              width: 30px; height: 30px; border-radius: 7px;
              display: flex; align-items: center; justify-content: center;
              font-size: 0.82rem; flex-shrink: 0;
            }

            .si-date  .summary-item-icon { background: var(--gold-pale); color: var(--gold); }
            .si-time  .summary-item-icon { background: var(--blue-bg);   color: var(--blue); }
            .si-venue .summary-item-icon { background: var(--green-bg);  color: var(--green); }
            .si-seat  .summary-item-icon { background: var(--pu-bg);     color: var(--pu); }

            .si-name { font-size: 0.67rem; color: var(--text-muted); font-weight: 500; text-transform: uppercase; letter-spacing: 0.04em; }
            .si-val  { font-weight: 700; font-size: 0.86rem; color: var(--text); line-height: 1.3; }

            /* ── Toast ───────────────────────────────────────────────────── */
            .toast {
              position: fixed; top: 1.25rem; right: 1.25rem;
              background: var(--green); color: #fff;
              padding: 0.72rem 1.25rem;
              border-radius: var(--radius-sm);
              font-size: 0.84rem; font-weight: 500;
              display: flex; align-items: center; gap: 0.5rem;
              box-shadow: 0 4px 20px rgba(26,107,69,.35);
              z-index: 9999;
              animation: toastIn .2s ease;
              pointer-events: none;
            }

            @keyframes toastIn {
              from { opacity: 0; transform: translateY(-8px); }
              to   { opacity: 1; transform: translateY(0); }
            }

            /* ── Error state ─────────────────────────────────────────────── */
            .error-state {
              background: var(--surface);
              border-radius: var(--radius);
              border: 1px solid var(--border);
              padding: 4rem 2rem;
              text-align: center;
              box-shadow: var(--shadow);
            }
            .error-state h3 { font-size: 1.35rem; font-weight: 700; color: var(--pu-dark); margin: 1rem 0 0.75rem; }
            .error-state p  { color: var(--text-muted); font-size: 0.86rem; max-width: 400px; margin: 0 auto 2rem; }
            .error-actions  { display: flex; justify-content: center; gap: 0.75rem; flex-wrap: wrap; }

            /* ── Print ───────────────────────────────────────────────────── */
            @media print {
              body { background: #fff; }
              .slip-shell { padding: 0; }
              .status-strip, .sidebar, .toast { display: none !important; }
              .content-grid { display: block; }
              .slip-card { box-shadow: none; border: 1px solid #ccc; }
            }
            </style>
        </head>
        <body>

        <div class="slip-shell">

          <!-- Status strip — no page title here, layout provides it -->
          <div class="status-strip">
            <div class="strip-left">
              <div class="strip-icon"><i class="fas fa-file-check"></i></div>
              <div>
                <div class="strip-title">Application Complete</div>
                <div class="strip-sub">
                  <i class="fas fa-hashtag" style="font-size:.65rem;"></i>
                  App No: <strong><?php echo $this->e($application['application_number'] ?? 'N/A'); ?></strong>
                  &nbsp;&bull;&nbsp; 2025/2026 Admission Screening
                </div>
              </div>
            </div>
            <div class="badge-verified"><i class="fas fa-circle-check"></i> Paid &amp; Verified</div>
          </div>

          <?php if (!empty($exam_slip)): ?>
          <?php
            $slipNum         = $exam_slip['slip_number'] ?? '';
            $qrUrl           = $baseUrl . '/application-verify/generate-qr/' . urlencode($slipNum) . '?t=' . time();
            $verificationUrl = $baseUrl . '/application-verify/slip/' . urlencode($slipNum);
          ?>

          <div class="content-grid">

            <!-- ── Exam Slip ───────────────────────────────────────────── -->
            <div class="slip-card" id="examSlipCard">

              <div class="slip-header">
                <div>
                  <div class="slip-header-title">Examination Admission Slip</div>
                  <div class="slip-header-sub">2025/2026 Academic Session &mdash; Pre-qualification Screening</div>
                </div>
                <?php if ($slipNum): ?>
                <div class="slip-number-pill"><?php echo $this->e($slipNum); ?></div>
                <?php endif; ?>
              </div>

              <div class="slip-body">

                <!-- Photo / QR / Verification row -->
                <div class="top-trio">

                  <div class="photo-wrap">
                    <div class="photo-frame">
                      <?php if (!empty($application['passport_photo'])): ?>
                        <img src="<?php echo $this->e($application['passport_photo']); ?>" alt="Passport Photo">
                      <?php else: ?>
                        <div class="no-photo">
                          <i class="fas fa-user-circle fa-3x"></i>
                          <span>No Photo</span>
                        </div>
                      <?php endif; ?>
                    </div>
                    <div class="img-caption"><i class="fas fa-camera"></i> Passport Photo</div>
                  </div>

                  <div class="qr-wrap">
                    <div class="qr-frame" id="qrFrame">
                      <?php if ($slipNum): ?>
                        <img src="<?php echo $this->e($qrUrl); ?>"
                             alt="Verification QR Code"
                             onerror="this.parentNode.innerHTML='<div class=\'qr-error\'><i class=\'fas fa-triangle-exclamation fa-lg\' style=\'display:block;margin-bottom:5px;\'></i>QR unavailable.<br>Use link below.</div>';">
                      <?php else: ?>
                        <div class="qr-error">Slip number missing</div>
                      <?php endif; ?>
                    </div>
                    <div class="img-caption"><i class="fas fa-qrcode"></i> Scan to Verify</div>
                  </div>

                  <div class="verify-panel">
                    <div class="verify-panel-title"><i class="fas fa-shield-halved"></i> Verification Status</div>
                    <div class="verify-item">
                      <i class="fas fa-circle-check" style="color:var(--green);"></i>
                      <div>
                        <div class="vi-label">Payment</div>
                        <div class="vi-val" style="color:var(--green);">Confirmed</div>
                      </div>
                    </div>
                    <div class="verify-item">
                      <i class="fas fa-hashtag" style="color:var(--pu);"></i>
                      <div>
                        <div class="vi-label">Slip Number</div>
                        <div class="vi-val mono"><?php echo $this->e($slipNum); ?></div>
                      </div>
                    </div>
                    <div class="verify-item">
                      <i class="fas fa-clock" style="color:var(--blue);"></i>
                      <div>
                        <div class="vi-label">Generated</div>
                        <div class="vi-val" style="font-size:.76rem;"><?php echo $this->e(date('d M Y, H:i', strtotime($exam_slip['generated_at'] ?? 'now'))); ?></div>
                      </div>
                    </div>
                  </div>

                </div><!-- /top-trio -->

                <!-- Details table -->
                <table class="details-table">
                  <tbody>
                    <tr>
                      <th>Slip Number</th>
                      <td><span class="mono" style="color:var(--pu-dark);font-weight:700;"><?php echo $this->e($slipNum); ?></span></td>
                    </tr>
                    <tr>
                      <th>Application No.</th>
                      <td><?php echo $this->e($application['application_number'] ?? ''); ?></td>
                    </tr>
                    <tr>
                      <th>JAMB Reg. No.</th>
                      <td><span class="mono"><?php echo $this->e($application['jamb_number'] ?? ''); ?></span></td>
                    </tr>
                    <tr>
                      <th>Full Name</th>
                      <td><strong><?php echo $this->e(($application['first_name'] ?? '') . ' ' . ($application['last_name'] ?? '')); ?></strong></td>
                    </tr>
                    <tr>
                      <th>Programme</th>
                      <td><span class="badge-program"><?php echo $this->e($application['program_choice_1'] ?? ''); ?></span></td>
                    </tr>
                    <tr class="row-exam-date">
                      <th>Exam Date</th>
                      <td><?php echo $this->e(date('l, jS F Y', strtotime($exam_slip['exam_date'] ?? 'now'))); ?></td>
                    </tr>
                    <tr>
                      <th>Exam Time</th>
                      <td><?php echo $this->e(date('h:i A', strtotime($exam_slip['exam_time'] ?? 'now'))); ?></td>
                    </tr>
                    <tr>
                      <th>Reporting Time</th>
                      <td>
                        <span class="report-time">
                          <i class="fas fa-triangle-exclamation"></i>
                          <?php echo $this->e(date('h:i A', strtotime($exam_slip['reporting_time'] ?? 'now'))); ?> &mdash; 30 mins early
                        </span>
                      </td>
                    </tr>
                    <tr>
                      <th>Venue</th>
                      <td><?php echo $this->e($exam_slip['exam_venue'] ?? ''); ?></td>
                    </tr>
                    <tr class="row-seat">
                      <th>Seat Number</th>
                      <td><span class="seat-num"><?php echo $this->e($exam_slip['seat_number'] ?? ''); ?></span></td>
                    </tr>
                  </tbody>
                </table>

                <!-- Instructions -->
                <div class="instructions">
                  <div class="instr-icon"><i class="fas fa-circle-info"></i></div>
                  <div>
                    <h5>Important Instructions</h5>
                    <ol>
                      <li>Print and bring this slip to the examination venue.</li>
                      <li>Arrive at least <strong>30 minutes</strong> before your reporting time.</li>
                      <li>Bring writing materials &mdash; pen, pencil, and eraser.</li>
                      <li>Bring a valid government-issued ID (National ID, Driver&rsquo;s License or Passport).</li>
                      <li>Electronic devices including phones and calculators are <strong>strictly prohibited</strong>.</li>
                      <li>The QR code will be scanned at the entrance for identity verification.</li>
                    </ol>
                  </div>
                </div>

                <div class="slip-footer-line">
                  <span><i class="fas fa-print"></i> Computer-generated &mdash; no signature required</span>
                  <span><i class="fas fa-lock"></i> QR contains encrypted applicant data</span>
                </div>

              </div><!-- /slip-body -->
            </div><!-- /slip-card -->

            <!-- ── Sidebar ────────────────────────────────────────────── -->
            <div class="sidebar">

              <div class="side-card">
                <div class="side-card-label">Actions</div>
                <div class="action-list">
                  <a href="/apply/download-exam-slip" class="act-btn act-btn--pdf" id="downloadBtn">
                    <span class="act-icon"><i class="fas fa-download"></i></span>
                    Download as PDF
                  </a>
                  <button class="act-btn act-btn--print" onclick="printExamSlip()">
                    <span class="act-icon"><i class="fas fa-print"></i></span>
                    Print Slip
                  </button>
                  <button class="act-btn act-btn--share" onclick="shareSlip()">
                    <span class="act-icon"><i class="fas fa-share-nodes"></i></span>
                    Share Slip
                  </button>
                  <a href="/applicant/dashboard" class="act-btn act-btn--dash">
                    <span class="act-icon"><i class="fas fa-gauge-high"></i></span>
                    Dashboard
                  </a>
                </div>
              </div>

              <div class="side-card">
                <div class="side-card-label">Verification Links</div>
                <p style="font-size:.76rem;color:var(--text-muted);margin-bottom:.55rem;line-height:1.5;">Public verification page:</p>
                <div class="copy-group" style="margin-bottom:.9rem;">
                  <input type="text" id="verificationLink"
                         value="<?php echo $this->e($verificationUrl); ?>"
                         readonly aria-label="Verification URL">
                  <button class="copy-btn" onclick="copyField('verificationLink', this)">
                    <i class="fas fa-copy"></i> Copy
                  </button>
                </div>
                <p style="font-size:.76rem;color:var(--text-muted);margin-bottom:.55rem;line-height:1.5;">QR code image link:</p>
                <div class="copy-group">
                  <input type="text" id="qrLink"
                         value="<?php echo $this->e($qrUrl); ?>"
                         readonly aria-label="QR Code URL">
                  <button class="copy-btn" onclick="copyField('qrLink', this)">
                    <i class="fas fa-copy"></i> Copy
                  </button>
                </div>
              </div>

              <div class="side-card">
                <div class="side-card-label">Quick Summary</div>
                <div class="summary-grid">
                  <div class="summary-item si-date">
                    <div class="summary-item-icon"><i class="fas fa-calendar-day"></i></div>
                    <div>
                      <div class="si-name">Exam Date</div>
                      <div class="si-val"><?php echo $this->e(date('d M Y', strtotime($exam_slip['exam_date'] ?? 'now'))); ?></div>
                    </div>
                  </div>
                  <div class="summary-item si-time">
                    <div class="summary-item-icon"><i class="fas fa-clock"></i></div>
                    <div>
                      <div class="si-name">Exam Time</div>
                      <div class="si-val"><?php echo $this->e(date('h:i A', strtotime($exam_slip['exam_time'] ?? 'now'))); ?></div>
                    </div>
                  </div>
                  <div class="summary-item si-venue">
                    <div class="summary-item-icon"><i class="fas fa-location-dot"></i></div>
                    <div>
                      <div class="si-name">Venue</div>
                      <div class="si-val"><?php echo $this->e($exam_slip['exam_venue'] ?? ''); ?></div>
                    </div>
                  </div>
                  <div class="summary-item si-seat">
                    <div class="summary-item-icon"><i class="fas fa-chair"></i></div>
                    <div>
                      <div class="si-name">Seat Number</div>
                      <div class="si-val" style="color:var(--green);"><?php echo $this->e($exam_slip['seat_number'] ?? ''); ?></div>
                    </div>
                  </div>
                </div>
              </div>

            </div><!-- /sidebar -->
          </div><!-- /content-grid -->

          <?php else: ?>
          <div class="error-state">
            <i class="fas fa-triangle-exclamation fa-3x" style="color:var(--pu-light);"></i>
            <h3>Examination Slip Not Available</h3>
            <p>Your slip is still being generated. Please check back shortly or contact support if this persists.</p>
            <div class="error-actions">
              <a href="/apply/step/3" class="act-btn act-btn--dash" style="width:auto;padding:.72rem 1.4rem;text-decoration:none;">
                <span class="act-icon"><i class="fas fa-arrow-left"></i></span>
                Back to Payment
              </a>
              <button class="act-btn act-btn--print" onclick="location.reload()" style="width:auto;padding:.72rem 1.4rem;">
                <span class="act-icon"><i class="fas fa-rotate-right"></i></span>
                Refresh
              </button>
            </div>
          </div>
          <?php endif; ?>

        </div><!-- /slip-shell -->

        <!-- ========================================================= -->
        <!-- 4. Add CSP nonce to all script tags -->
        <!-- ========================================================= -->
        <script nonce="<?php echo $csp_nonce; ?>">
        // ======================================================
        // Examination Slip JavaScript with Security Enhancements
        // ======================================================
        
        // Get CSRF token from meta tag
        function getCsrfToken() {
            const meta = document.querySelector('meta[name="csrf-token"]');
            return meta ? meta.getAttribute('content') : '';
        }

        // Copy field with secure clipboard handling
        function copyField(inputId, btn) {
            const input = document.getElementById(inputId);
            if (!input) return;
            
            const val = input.value;
            
            const onCopied = () => {
                const orig = btn.innerHTML;
                btn.innerHTML = '<i class="fas fa-check"></i> Copied!';
                btn.classList.add('copied');
                showToast('Copied to clipboard!');
                
                setTimeout(() => { 
                    btn.innerHTML = orig; 
                    btn.classList.remove('copied'); 
                }, 2200);
            };
            
            try {
                if (navigator.clipboard && navigator.clipboard.writeText) {
                    navigator.clipboard.writeText(val).then(onCopied).catch(() => {
                        fallbackCopy(input, val, onCopied);
                    });
                } else {
                    fallbackCopy(input, val, onCopied);
                }
            } catch(e) {
                fallbackCopy(input, val, onCopied);
            }
        }

        function fallbackCopy(input, text, callback) {
            input.select();
            input.setSelectionRange(0, 99999);
            
            try {
                if (document.execCommand('copy')) {
                    callback();
                } else {
                    showToast('Copy failed. Please copy manually.', 'error');
                }
            } catch(e) {
                showToast('Copy failed. Please copy manually.', 'error');
            }
        }

        // Print exam slip with security
        function printExamSlip() {
            // Verify with CSRF token for audit trail
            fetch('/api/verify-print-access', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': getCsrfToken()
                },
                body: JSON.stringify({ action: 'print_exam_slip' })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const pw = window.open('/apply/print-exam-slip', '_blank');
                    if (pw) {
                        pw.onload = () => setTimeout(() => { pw.focus(); pw.print(); }, 800);
                    } else {
                        showToast('Pop-up blocked — printing current page…');
                        setTimeout(() => window.print(), 1200);
                    }
                } else {
                    showToast('Unable to verify print permissions', 'error');
                }
            })
            .catch(error => {
                console.error('Print verification error:', error);
                // Fallback to direct print
                const pw = window.open('/apply/print-exam-slip', '_blank');
                if (pw) {
                    pw.onload = () => setTimeout(() => { pw.focus(); pw.print(); }, 800);
                }
            });
        }

        // Share slip with security
        function shareSlip() {
            if (navigator.share) {
                // Add timestamp to URL to prevent caching
                const shareUrl = window.location.href + (window.location.href.includes('?') ? '&' : '?') + 't=' + Date.now();
                
                navigator.share({
                    title: 'Examination Slip — FCT College of Nursing Sciences',
                    text: 'My examination slip for the 2025/2026 admission screening.',
                    url: shareUrl
                }).catch(console.error);
            } else {
                copyField('verificationLink', document.querySelector('#verificationLink ~ .copy-btn, .copy-group .copy-btn'));
            }
        }

        // Toast notification
        function showToast(msg, type = 'success') {
            document.querySelectorAll('.toast').forEach(t => t.remove());
            
            const toast = document.createElement('div');
            toast.className = 'toast';
            
            const icon = type === 'success' ? 'fa-circle-check' : 'fa-triangle-exclamation';
            toast.innerHTML = `<i class="fas ${icon}"></i> ${msg}`;
            
            document.body.appendChild(toast);
            
            setTimeout(() => {
                toast.style.transition = 'opacity .3s';
                toast.style.opacity = '0';
                setTimeout(() => toast.remove(), 320);
            }, 2600);
        }

        // Download button handler
        document.getElementById('downloadBtn')?.addEventListener('click', e => {
            e.preventDefault();
            
            // Add CSRF token to download request
            const downloadUrl = '/apply/download-exam-slip?csrf=' + encodeURIComponent(getCsrfToken()) + '&t=' + Date.now();
            
            fetch(downloadUrl, {
                method: 'GET',
                headers: {
                    'X-CSRF-TOKEN': getCsrfToken()
                }
            })
            .then(response => {
                if (response.ok) {
                    window.location.href = downloadUrl;
                } else {
                    showToast('Download failed. Please try again.', 'error');
                }
            })
            .catch(error => {
                console.error('Download error:', error);
                showToast('Download failed. Please try again.', 'error');
            });
        });

        // Print shortcut (Ctrl/Cmd + P)
        document.addEventListener('keydown', e => {
            if ((e.ctrlKey || e.metaKey) && e.key === 'p') { 
                e.preventDefault(); 
                printExamSlip(); 
            }
        });

        // Prevent right-click on sensitive elements
        document.querySelectorAll('.qr-frame, .slip-number-pill').forEach(el => {
            el.addEventListener('contextmenu', e => e.preventDefault());
        });

        // Add timestamp to all external links for cache busting
        document.querySelectorAll('a[href^="http"]').forEach(link => {
            const url = new URL(link.href);
            if (url.hostname !== window.location.hostname) {
                // External link - add warning
                link.setAttribute('target', '_blank');
                link.setAttribute('rel', 'noopener noreferrer');
            }
        });
        </script>
        </body>
        </html>
        <?php
    }
}

// =========================================================
// 8. Add the view instantiation at the bottom
// =========================================================
$view = new ExamSlipView();
$view->render(get_defined_vars());
?>