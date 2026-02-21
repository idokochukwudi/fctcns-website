<?php
/**
 * Step 4 - Examination Slip View
 * SIMPLIFIED: Using only server-generated QR code
 *
 * @var array $application
 * @var array $exam_slip
 * @var array $applicant
 */

$pageTitle = $pageTitle ?? 'Examination Slip - FCT College of Nursing Sciences';
$baseUrl   = defined('BASE_URL') ? rtrim(BASE_URL, '/') : '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?php echo htmlspecialchars($pageTitle); ?></title>
<meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">
<meta http-equiv="Pragma" content="no-cache">
<meta http-equiv="Expires" content="0">

<!-- Fonts -->
<link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@500;600;700&family=Outfit:wght@300;400;500;600;700&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">
<!-- Icons -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<style>
/* ─── Reset & Base ─────────────────────────────────────────────── */
*, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

:root {
  --navy:        #0d1f3c;
  --navy-mid:    #1a3460;
  --navy-light:  #2a4a80;
  --gold:        #b8922a;
  --gold-light:  #d4a93a;
  --gold-pale:   #fdf6e3;
  --emerald:     #1a6b45;
  --emerald-bg:  #f0faf4;
  --slate:       #64748b;
  --slate-light: #94a3b8;
  --border:      #e2e8f0;
  --border-dark: #cbd5e1;
  --bg:          #f1f5f9;
  --surface:     #ffffff;
  --text:        #0f172a;
  --text-muted:  #64748b;
  --danger:      #b91c1c;
  --radius:      12px;
  --radius-sm:   8px;
  --shadow:      0 1px 3px rgba(0,0,0,.06), 0 4px 16px rgba(0,0,0,.07);
  --shadow-lg:   0 2px 6px rgba(0,0,0,.06), 0 8px 32px rgba(0,0,0,.10);
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

/* ─── Page Layout ───────────────────────────────────────────────── */
.shell {
  max-width: 1180px;
  margin: 0 auto;
  padding: 2rem 1.5rem 3rem;
}

/* ─── Top Banner ────────────────────────────────────────────────── */
.top-banner {
  background: var(--navy);
  border-radius: var(--radius);
  padding: 1.25rem 2rem;
  display: flex;
  align-items: center;
  justify-content: space-between;
  flex-wrap: wrap;
  gap: 1rem;
  margin-bottom: 1.75rem;
  position: relative;
  overflow: hidden;
}

.top-banner::before {
  content: '';
  position: absolute;
  top: 0; right: 0;
  width: 300px; height: 100%;
  background: linear-gradient(135deg, transparent, rgba(184,146,42,.12));
  pointer-events: none;
}

.top-banner::after {
  content: '';
  position: absolute;
  bottom: -30px; left: -30px;
  width: 160px; height: 160px;
  border-radius: 50%;
  background: rgba(255,255,255,.03);
  pointer-events: none;
}

.banner-left {
  display: flex;
  align-items: center;
  gap: 1rem;
}

.banner-icon {
  width: 44px; height: 44px;
  background: rgba(184,146,42,.2);
  border: 1px solid rgba(184,146,42,.35);
  border-radius: var(--radius-sm);
  display: flex;
  align-items: center;
  justify-content: center;
  color: var(--gold-light);
  font-size: 1.2rem;
  flex-shrink: 0;
}

.banner-title {
  font-family: 'Cormorant Garamond', serif;
  font-size: 1.35rem;
  font-weight: 600;
  color: #fff;
  line-height: 1.2;
}

.banner-sub {
  font-size: 0.8rem;
  color: rgba(255,255,255,.55);
  margin-top: 0.15rem;
  display: flex;
  align-items: center;
  gap: 0.4rem;
}

.banner-sub strong { color: rgba(255,255,255,.85); font-weight: 500; }

.badge-verified {
  display: inline-flex;
  align-items: center;
  gap: 0.45rem;
  background: linear-gradient(135deg, var(--emerald), #22934f);
  color: #fff;
  font-size: 0.78rem;
  font-weight: 600;
  letter-spacing: 0.06em;
  text-transform: uppercase;
  padding: 0.55rem 1.25rem;
  border-radius: 50px;
  box-shadow: 0 2px 12px rgba(26,107,69,.35);
}

/* ─── Grid ──────────────────────────────────────────────────────── */
.content-grid {
  display: grid;
  grid-template-columns: 1fr 320px;
  gap: 1.75rem;
  align-items: start;
}

@media (max-width: 960px) { .content-grid { grid-template-columns: 1fr; } }

/* ─── Slip Card ─────────────────────────────────────────────────── */
.slip-card {
  background: var(--surface);
  border-radius: var(--radius);
  box-shadow: var(--shadow-lg);
  overflow: hidden;
  border: 1px solid var(--border);
}

/* Card header strip */
.slip-header {
  background: linear-gradient(135deg, var(--navy), var(--navy-mid));
  padding: 1.5rem 2rem;
  display: flex;
  align-items: center;
  justify-content: space-between;
  gap: 1rem;
  flex-wrap: wrap;
  position: relative;
  overflow: hidden;
}

.slip-header::after {
  content: '';
  position: absolute;
  right: -40px; top: -40px;
  width: 200px; height: 200px;
  border-radius: 50%;
  border: 40px solid rgba(184,146,42,.08);
  pointer-events: none;
}

.slip-header-title {
  font-family: 'Cormorant Garamond', serif;
  color: #fff;
  font-size: 1.5rem;
  font-weight: 600;
  line-height: 1;
}

.slip-header-sub {
  color: rgba(255,255,255,.55);
  font-size: 0.78rem;
  margin-top: 0.3rem;
  font-weight: 400;
}

.slip-number-pill {
  background: rgba(184,146,42,.15);
  border: 1px solid rgba(184,146,42,.3);
  color: var(--gold-light);
  font-family: 'JetBrains Mono', monospace;
  font-size: 0.82rem;
  font-weight: 500;
  padding: 0.45rem 1rem;
  border-radius: 50px;
  white-space: nowrap;
}

/* Slip body */
.slip-body { padding: 2rem; }

/* ─── Top Row: Photo + QR + Badge ──────────────────────────────── */
.top-trio {
  display: grid;
  grid-template-columns: 140px 140px 1fr;
  gap: 1.5rem;
  margin-bottom: 1.75rem;
  align-items: start;
}

@media (max-width: 600px) { .top-trio { grid-template-columns: 1fr 1fr; } }
@media (max-width: 420px) { .top-trio { grid-template-columns: 1fr; } }

/* Photo box */
.photo-wrap { display: flex; flex-direction: column; align-items: center; gap: 0.6rem; }

.photo-frame {
  width: 140px; height: 140px;
  border-radius: var(--radius-sm);
  border: 2px solid var(--border);
  background: #f8fafc;
  overflow: hidden;
  display: flex;
  align-items: center;
  justify-content: center;
  position: relative;
}

.photo-frame img {
  width: 100%; height: 100%;
  object-fit: cover;
  display: block;
}

.photo-frame .no-photo {
  display: flex;
  flex-direction: column;
  align-items: center;
  gap: 0.4rem;
  color: var(--slate-light);
  font-size: 0.72rem;
}

.img-caption {
  font-size: 0.7rem;
  color: var(--text-muted);
  display: flex;
  align-items: center;
  gap: 0.3rem;
  letter-spacing: 0.04em;
  text-transform: uppercase;
  font-weight: 500;
}

/* QR box */
.qr-wrap { display: flex; flex-direction: column; align-items: center; gap: 0.6rem; }

.qr-frame {
  width: 140px; height: 140px;
  border-radius: var(--radius-sm);
  border: 2px solid var(--border);
  background: #fff;
  display: flex;
  align-items: center;
  justify-content: center;
  padding: 8px;
  position: relative;
}

/* Corner accents on QR */
.qr-frame::before,
.qr-frame::after {
  content: '';
  position: absolute;
  width: 14px; height: 14px;
  border-color: var(--navy);
  border-style: solid;
}
.qr-frame::before { top: 5px; left: 5px; border-width: 2px 0 0 2px; border-radius: 2px 0 0 0; }
.qr-frame::after  { bottom: 5px; right: 5px; border-width: 0 2px 2px 0; border-radius: 0 0 2px 0; }

.qr-frame img {
  width: 100%; height: 100%;
  object-fit: contain;
  image-rendering: crisp-edges;
  image-rendering: -webkit-optimize-contrast;
}

.qr-error {
  text-align: center;
  font-size: 0.72rem;
  color: var(--danger);
  line-height: 1.4;
  padding: 0.5rem;
}

/* Verification badge panel */
.verify-panel {
  background: var(--emerald-bg);
  border: 1.5px solid #b2dfcc;
  border-radius: var(--radius-sm);
  padding: 1.25rem;
  display: flex;
  flex-direction: column;
  gap: 0.8rem;
}

.verify-panel-title {
  font-size: 0.75rem;
  font-weight: 700;
  text-transform: uppercase;
  letter-spacing: 0.07em;
  color: var(--emerald);
  display: flex;
  align-items: center;
  gap: 0.4rem;
}

.verify-item {
  display: flex;
  align-items: flex-start;
  gap: 0.55rem;
  font-size: 0.84rem;
}

.verify-item i {
  margin-top: 0.15rem;
  flex-shrink: 0;
  width: 16px;
  text-align: center;
}

.verify-item .vi-label { color: var(--text-muted); font-size: 0.72rem; line-height: 1; margin-bottom: 0.1rem; }
.verify-item .vi-val   { color: var(--text); font-weight: 600; font-size: 0.84rem; }
.mono { font-family: 'JetBrains Mono', monospace; letter-spacing: 0.03em; }

/* ─── Details Table ─────────────────────────────────────────────── */
.details-table {
  width: 100%;
  border-collapse: collapse;
  font-size: 0.88rem;
  border: 1.5px solid var(--border);
  border-radius: var(--radius-sm);
  overflow: hidden;
}

.details-table tr { transition: background 0.12s; }
.details-table tr:not(:last-child) td,
.details-table tr:not(:last-child) th { border-bottom: 1px solid var(--border); }

.details-table th {
  background: #f8fafc;
  color: var(--text-muted);
  font-weight: 600;
  font-size: 0.75rem;
  text-transform: uppercase;
  letter-spacing: 0.05em;
  padding: 0.85rem 1.2rem;
  width: 34%;
  white-space: nowrap;
}

.details-table td {
  padding: 0.85rem 1.2rem;
  color: var(--text);
  font-weight: 500;
}

/* Highlighted rows */
.row-exam-date th { background: #fdf6e3; color: #92690c; }
.row-exam-date td { background: var(--gold-pale); font-weight: 700; color: var(--navy); font-size: 0.95rem; }
.row-seat th      { background: #f0faf4; color: var(--emerald); }
.row-seat td      { background: var(--emerald-bg); }

.seat-num {
  font-family: 'Cormorant Garamond', serif;
  font-size: 1.6rem;
  font-weight: 700;
  color: var(--emerald);
  line-height: 1;
}

.badge-program {
  display: inline-flex;
  align-items: center;
  background: #eff6ff;
  color: #1d4ed8;
  border: 1px solid #bfdbfe;
  border-radius: 50px;
  padding: 0.28rem 0.85rem;
  font-size: 0.8rem;
  font-weight: 600;
}

.text-danger { color: var(--danger); }

.report-time {
  display: inline-flex;
  align-items: center;
  gap: 0.4rem;
  color: var(--danger);
  font-weight: 600;
}

/* ─── Instructions ──────────────────────────────────────────────── */
.instructions {
  margin-top: 1.75rem;
  background: #eff6ff;
  border: 1.5px solid #bfdbfe;
  border-radius: var(--radius-sm);
  padding: 1.35rem 1.5rem;
  display: flex;
  gap: 1.1rem;
}

.instr-icon {
  color: #2563eb;
  font-size: 1.4rem;
  flex-shrink: 0;
  margin-top: 0.05rem;
}

.instructions h5 {
  font-family: 'Outfit', sans-serif;
  font-size: 0.88rem;
  font-weight: 700;
  color: #1d4ed8;
  margin-bottom: 0.7rem;
  text-transform: uppercase;
  letter-spacing: 0.05em;
}

.instructions ol {
  padding-left: 1.1rem;
  display: flex;
  flex-direction: column;
  gap: 0.35rem;
}

.instructions li {
  font-size: 0.84rem;
  color: #1e40af;
  line-height: 1.5;
}

/* ─── Slip Footer ───────────────────────────────────────────────── */
.slip-footer-line {
  margin-top: 1.5rem;
  padding-top: 1.25rem;
  border-top: 1px dashed var(--border-dark);
  display: flex;
  align-items: center;
  justify-content: space-between;
  flex-wrap: wrap;
  gap: 0.75rem;
}

.slip-footer-line span {
  font-size: 0.75rem;
  color: var(--text-muted);
  display: flex;
  align-items: center;
  gap: 0.35rem;
}

/* ─── Sidebar ────────────────────────────────────────────────────── */
.sidebar { display: flex; flex-direction: column; gap: 1.25rem; }

.side-card {
  background: var(--surface);
  border-radius: var(--radius);
  border: 1px solid var(--border);
  box-shadow: var(--shadow);
  padding: 1.5rem;
}

.side-card-label {
  font-size: 0.7rem;
  font-weight: 700;
  text-transform: uppercase;
  letter-spacing: 0.09em;
  color: var(--text-muted);
  margin-bottom: 1rem;
  padding-bottom: 0.75rem;
  border-bottom: 1px solid var(--border);
}

/* Action buttons */
.action-list { display: flex; flex-direction: column; gap: 0.55rem; }

.act-btn {
  display: flex;
  align-items: center;
  gap: 0.8rem;
  width: 100%;
  padding: 0.8rem 1rem;
  border-radius: var(--radius-sm);
  font-size: 0.88rem;
  font-weight: 600;
  border: none;
  cursor: pointer;
  text-decoration: none;
  transition: transform 0.15s, box-shadow 0.15s, background 0.15s;
  font-family: 'Outfit', sans-serif;
  position: relative;
  overflow: hidden;
}

.act-btn::after {
  content: '\f054';
  font-family: 'Font Awesome 6 Free';
  font-weight: 900;
  font-size: 0.7rem;
  margin-left: auto;
  opacity: 0.45;
}

.act-btn:hover { transform: translateY(-1px); }

.act-icon {
  width: 32px; height: 32px;
  border-radius: 6px;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 0.9rem;
  flex-shrink: 0;
}

/* PDF / Download */
.act-btn--pdf {
  background: linear-gradient(135deg, #1a6b45, #22934f);
  color: #fff;
  box-shadow: 0 2px 10px rgba(26,107,69,.25);
}
.act-btn--pdf:hover { box-shadow: 0 4px 18px rgba(26,107,69,.38); color:#fff; }
.act-btn--pdf .act-icon { background: rgba(255,255,255,.18); color: #fff; }

/* Print */
.act-btn--print {
  background: linear-gradient(135deg, var(--navy), var(--navy-mid));
  color: #fff;
  box-shadow: 0 2px 10px rgba(13,31,60,.22);
}
.act-btn--print:hover { box-shadow: 0 4px 18px rgba(13,31,60,.35); color:#fff; }
.act-btn--print .act-icon { background: rgba(255,255,255,.18); color: #fff; }

/* Share */
.act-btn--share {
  background: #f8fafc;
  color: var(--navy);
  border: 1.5px solid var(--border);
}
.act-btn--share:hover { background: #eef2f7; }
.act-btn--share .act-icon { background: var(--navy); color: #fff; }

/* Dashboard */
.act-btn--dash {
  background: #f8fafc;
  color: var(--text-muted);
  border: 1.5px solid var(--border);
}
.act-btn--dash:hover { background: #eef2f7; color: var(--text); }
.act-btn--dash .act-icon { background: #e2e8f0; color: var(--slate); }

/* ─── Copy Input Group ──────────────────────────────────────────── */
.copy-group {
  display: flex;
  gap: 0;
  border: 1.5px solid var(--border);
  border-radius: var(--radius-sm);
  overflow: hidden;
  background: #f8fafc;
}

.copy-group input {
  flex: 1;
  border: none;
  background: transparent;
  padding: 0.6rem 0.85rem;
  font-size: 0.78rem;
  color: var(--text-muted);
  font-family: 'JetBrains Mono', monospace;
  outline: none;
  min-width: 0;
}

.copy-group .copy-btn {
  background: var(--navy);
  color: #fff;
  border: none;
  padding: 0.6rem 0.95rem;
  font-size: 0.78rem;
  font-weight: 600;
  cursor: pointer;
  font-family: 'Outfit', sans-serif;
  display: flex;
  align-items: center;
  gap: 0.35rem;
  white-space: nowrap;
  transition: background 0.15s;
  flex-shrink: 0;
}

.copy-group .copy-btn:hover { background: var(--navy-mid); }
.copy-group .copy-btn.copied { background: var(--emerald); }

/* ─── Quick Summary ─────────────────────────────────────────────── */
.summary-grid { display: flex; flex-direction: column; gap: 0.85rem; }

.summary-item {
  display: flex;
  align-items: center;
  gap: 0.85rem;
  padding: 0.75rem;
  border-radius: var(--radius-sm);
  background: #f8fafc;
  border: 1px solid var(--border);
}

.summary-item-icon {
  width: 34px; height: 34px;
  border-radius: 8px;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 0.9rem;
  flex-shrink: 0;
}

.si-date  .summary-item-icon { background: #fdf6e3; color: var(--gold); }
.si-time  .summary-item-icon { background: #eff6ff; color: #2563eb; }
.si-venue .summary-item-icon { background: var(--emerald-bg); color: var(--emerald); }
.si-seat  .summary-item-icon { background: #fdf4ff; color: #7e22ce; }

.summary-item-text .si-name  { font-size: 0.72rem; color: var(--text-muted); font-weight: 500; text-transform: uppercase; letter-spacing: 0.04em; }
.summary-item-text .si-val   { font-weight: 700; font-size: 0.9rem; color: var(--text); line-height: 1.3; }

/* ─── Toast ─────────────────────────────────────────────────────── */
.toast {
  position: fixed;
  top: 1.5rem; right: 1.5rem;
  background: var(--emerald);
  color: #fff;
  padding: 0.8rem 1.4rem;
  border-radius: var(--radius-sm);
  font-size: 0.88rem;
  font-weight: 500;
  display: flex;
  align-items: center;
  gap: 0.55rem;
  box-shadow: 0 4px 20px rgba(26,107,69,.35);
  z-index: 9999;
  animation: toastIn .22s ease;
  pointer-events: none;
}

@keyframes toastIn {
  from { opacity:0; transform: translateY(-10px) scale(.97); }
  to   { opacity:1; transform: translateY(0) scale(1); }
}

/* ─── Error State ───────────────────────────────────────────────── */
.error-state {
  background: var(--surface);
  border-radius: var(--radius);
  border: 1px solid var(--border);
  padding: 4rem 2rem;
  text-align: center;
}

.error-state h3 {
  font-family: 'Cormorant Garamond', serif;
  color: var(--navy);
  font-size: 1.5rem;
  margin: 1rem 0 0.75rem;
}

.error-state p { color: var(--text-muted); font-size: 0.9rem; max-width: 400px; margin: 0 auto 2rem; }

.error-actions { display: flex; justify-content: center; gap: 0.75rem; flex-wrap: wrap; }

/* ─── Print ─────────────────────────────────────────────────────── */
@media print {
  body { background: #fff; }
  .shell { padding: 0; max-width: 100%; }
  .top-banner, .sidebar, .toast { display: none !important; }
  .content-grid { grid-template-columns: 1fr; }
  .slip-card { box-shadow: none; border: 1px solid #ccc; }
}
</style>
</head>
<body>

<div class="shell">

  <!-- ── Top Banner ──────────────────────────────────────────────── -->
  <div class="top-banner">
    <div class="banner-left">
      <div class="banner-icon"><i class="fas fa-hospital"></i></div>
      <div>
        <div class="banner-title">FCT College of Nursing Sciences</div>
        <div class="banner-sub">
          <i class="fas fa-hashtag" style="font-size:.7rem;"></i>
          Application No: <strong><?php echo htmlspecialchars($application['application_number'] ?? 'N/A'); ?></strong>
          &nbsp;&bull;&nbsp; 2025/2026 Admission Screening
        </div>
      </div>
    </div>
    <div class="badge-verified">
      <i class="fas fa-circle-check"></i> Paid &amp; Verified
    </div>
  </div>

  <!-- ── Content ─────────────────────────────────────────────────── -->
  <?php if (!empty($exam_slip)): ?>
  <?php
    $slipNum        = $exam_slip['slip_number'] ?? '';
    $qrUrl          = $baseUrl . '/application-verify/generate-qr/' . urlencode($slipNum) . '?t=' . time();
    $verificationUrl= $baseUrl . '/application-verify/slip/' . urlencode($slipNum);
  ?>

  <div class="content-grid">

    <!-- ── Exam Slip Card ──────────────────────────────────────── -->
    <div class="slip-card" id="examSlipCard">

      <!-- Header -->
      <div class="slip-header">
        <div>
          <div class="slip-header-title">Examination Admission Slip</div>
          <div class="slip-header-sub">2025/2026 Academic Session — Pre-qualification Screening</div>
        </div>
        <?php if ($slipNum): ?>
        <div class="slip-number-pill"><?php echo htmlspecialchars($slipNum); ?></div>
        <?php endif; ?>
      </div>

      <!-- Body -->
      <div class="slip-body">

        <!-- Photo / QR / Badge row -->
        <div class="top-trio">

          <!-- Passport photo -->
          <div class="photo-wrap">
            <div class="photo-frame">
              <?php if (!empty($application['passport_photo'])): ?>
                <img src="<?php echo htmlspecialchars($application['passport_photo']); ?>" alt="Passport Photo">
              <?php else: ?>
                <div class="no-photo">
                  <i class="fas fa-user-circle fa-3x"></i>
                  <span>No Photo</span>
                </div>
              <?php endif; ?>
            </div>
            <div class="img-caption"><i class="fas fa-camera"></i> Passport Photo</div>
          </div>

          <!-- QR Code -->
          <div class="qr-wrap">
            <div class="qr-frame" id="qrFrame">
              <?php if ($slipNum): ?>
                <img id="qrImg"
                     src="<?php echo htmlspecialchars($qrUrl); ?>"
                     alt="Verification QR Code"
                     onerror="document.getElementById('qrFrame').innerHTML='<div class=\'qr-error\'><i class=\'fas fa-triangle-exclamation fa-lg\' style=\'margin-bottom:6px;display:block;\'></i>QR temporarily unavailable.<br>Use link below.</div>';">
              <?php else: ?>
                <div class="qr-error">No slip number</div>
              <?php endif; ?>
            </div>
            <div class="img-caption"><i class="fas fa-qrcode"></i> Scan to Verify</div>
          </div>

          <!-- Verification panel -->
          <div class="verify-panel">
            <div class="verify-panel-title"><i class="fas fa-shield-halved"></i> Status</div>
            <div class="verify-item">
              <i class="fas fa-circle-check" style="color:var(--emerald);"></i>
              <div>
                <div class="vi-label">Payment</div>
                <div class="vi-val" style="color:var(--emerald);">Confirmed</div>
              </div>
            </div>
            <div class="verify-item">
              <i class="fas fa-hashtag" style="color:var(--gold);"></i>
              <div>
                <div class="vi-label">Slip Number</div>
                <div class="vi-val mono" style="font-size:.78rem;"><?php echo htmlspecialchars($slipNum); ?></div>
              </div>
            </div>
            <div class="verify-item">
              <i class="fas fa-clock" style="color:#2563eb;"></i>
              <div>
                <div class="vi-label">Generated</div>
                <div class="vi-val" style="font-size:.8rem;"><?php echo date('d M Y, H:i', strtotime($exam_slip['generated_at'])); ?></div>
              </div>
            </div>
          </div>

        </div><!-- /top-trio -->

        <!-- Details table -->
        <table class="details-table">
          <tbody>
            <tr>
              <th>Slip Number</th>
              <td><span class="mono" style="color:var(--navy); font-weight:700;"><?php echo htmlspecialchars($slipNum); ?></span></td>
            </tr>
            <tr>
              <th>Application Number</th>
              <td><?php echo htmlspecialchars($application['application_number']); ?></td>
            </tr>
            <tr>
              <th>JAMB Reg. Number</th>
              <td><span class="mono"><?php echo htmlspecialchars($application['jamb_number']); ?></span></td>
            </tr>
            <tr>
              <th>Full Name</th>
              <td><strong><?php echo htmlspecialchars($application['first_name'] . ' ' . $application['last_name']); ?></strong></td>
            </tr>
            <tr>
              <th>Programme</th>
              <td><span class="badge-program"><?php echo htmlspecialchars($application['program_choice_1']); ?></span></td>
            </tr>
            <tr class="row-exam-date">
              <th>Examination Date</th>
              <td><?php echo date('l, jS F Y', strtotime($exam_slip['exam_date'])); ?></td>
            </tr>
            <tr>
              <th>Examination Time</th>
              <td><?php echo date('h:i A', strtotime($exam_slip['exam_time'])); ?></td>
            </tr>
            <tr>
              <th>Reporting Time</th>
              <td>
                <span class="report-time">
                  <i class="fas fa-triangle-exclamation"></i>
                  <?php echo date('h:i A', strtotime($exam_slip['reporting_time'])); ?> — 30 mins before exam
                </span>
              </td>
            </tr>
            <tr>
              <th>Venue</th>
              <td><?php echo htmlspecialchars($exam_slip['exam_venue']); ?></td>
            </tr>
            <tr class="row-seat">
              <th>Seat Number</th>
              <td><span class="seat-num"><?php echo htmlspecialchars($exam_slip['seat_number']); ?></span></td>
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
              <li>Come with writing materials — pen, pencil, and eraser.</li>
              <li>Bring a valid government-issued ID (National ID, Driver's License or Passport).</li>
              <li>Electronic devices including mobile phones and calculators are <strong>strictly prohibited</strong>.</li>
              <li>The QR code will be scanned at the entrance for identity verification.</li>
            </ol>
          </div>
        </div>

        <!-- Footer line -->
        <div class="slip-footer-line">
          <span><i class="fas fa-print"></i> Computer-generated — no signature required</span>
          <span><i class="fas fa-lock"></i> QR contains encrypted applicant data</span>
        </div>

      </div><!-- /slip-body -->
    </div><!-- /slip-card -->

    <!-- ── Sidebar ──────────────────────────────────────────────── -->
    <div class="sidebar">

      <!-- Actions -->
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
            Go to Dashboard
          </a>

        </div>
      </div>

      <!-- Verification Links -->
      <div class="side-card">
        <div class="side-card-label">Verification Links</div>

        <!-- Public verification URL -->
        <p style="font-size:.8rem; color:var(--text-muted); margin-bottom:.65rem; line-height:1.5;">
          Share to let anyone verify this slip online:
        </p>
        <div class="copy-group" style="margin-bottom:1rem;">
          <input type="text"
                 id="verificationLink"
                 value="<?php echo htmlspecialchars($verificationUrl); ?>"
                 readonly
                 aria-label="Verification URL">
          <button class="copy-btn" onclick="copyField('verificationLink', this)">
            <i class="fas fa-copy"></i> Copy
          </button>
        </div>

        <!-- QR Source URL -->
        <p style="font-size:.8rem; color:var(--text-muted); margin-bottom:.65rem; line-height:1.5;">
          Direct QR code image link:
        </p>
        <div class="copy-group">
          <input type="text"
                 id="qrLink"
                 value="<?php echo htmlspecialchars($qrUrl); ?>"
                 readonly
                 aria-label="QR Code URL">
          <button class="copy-btn" onclick="copyField('qrLink', this)">
            <i class="fas fa-copy"></i> Copy
          </button>
        </div>
      </div>

      <!-- Quick Summary -->
      <div class="side-card">
        <div class="side-card-label">Quick Summary</div>
        <div class="summary-grid">
          <div class="summary-item si-date">
            <div class="summary-item-icon"><i class="fas fa-calendar-day"></i></div>
            <div class="summary-item-text">
              <div class="si-name">Exam Date</div>
              <div class="si-val"><?php echo date('d M Y', strtotime($exam_slip['exam_date'])); ?></div>
            </div>
          </div>
          <div class="summary-item si-time">
            <div class="summary-item-icon"><i class="fas fa-clock"></i></div>
            <div class="summary-item-text">
              <div class="si-name">Exam Time</div>
              <div class="si-val"><?php echo date('h:i A', strtotime($exam_slip['exam_time'])); ?></div>
            </div>
          </div>
          <div class="summary-item si-venue">
            <div class="summary-item-icon"><i class="fas fa-location-dot"></i></div>
            <div class="summary-item-text">
              <div class="si-name">Venue</div>
              <div class="si-val"><?php echo htmlspecialchars($exam_slip['exam_venue']); ?></div>
            </div>
          </div>
          <div class="summary-item si-seat">
            <div class="summary-item-icon"><i class="fas fa-chair"></i></div>
            <div class="summary-item-text">
              <div class="si-name">Seat Number</div>
              <div class="si-val" style="font-size:1.05rem; color:var(--emerald);"><?php echo htmlspecialchars($exam_slip['seat_number']); ?></div>
            </div>
          </div>
        </div>
      </div>

    </div><!-- /sidebar -->
  </div><!-- /content-grid -->

  <?php else: ?>
  <!-- ── Error / Not Ready State ─────────────────────────────────── -->
  <div class="error-state">
    <i class="fas fa-triangle-exclamation fa-3x" style="color:var(--gold);"></i>
    <h3>Examination Slip Not Available</h3>
    <p>Your slip is still being generated. Please check back shortly or contact support if this persists.</p>
    <div class="error-actions">
      <a href="/apply/step/3" class="act-btn act-btn--dash" style="width:auto; padding:.75rem 1.5rem; text-decoration:none;">
        <span class="act-icon"><i class="fas fa-arrow-left"></i></span>
        Back to Payment
      </a>
      <button class="act-btn act-btn--print" onclick="location.reload()" style="width:auto; padding:.75rem 1.5rem;">
        <span class="act-icon"><i class="fas fa-rotate-right"></i></span>
        Refresh Page
      </button>
    </div>
  </div>
  <?php endif; ?>

</div><!-- /shell -->

<script>
/* ── Copy field value ──────────────────────────────────────────── */
function copyField(inputId, btn) {
  const input = document.getElementById(inputId);
  if (!input) return;
  const val = input.value;

  const doCopy = () => {
    input.select();
    input.setSelectionRange(0, 99999);
    try { navigator.clipboard.writeText(val).then(onCopied).catch(fallback); }
    catch(e) { fallback(); }
  };

  const fallback = () => {
    document.execCommand('copy');
    onCopied();
  };

  const onCopied = () => {
    const origHtml = btn.innerHTML;
    btn.innerHTML = '<i class="fas fa-check"></i> Copied!';
    btn.classList.add('copied');
    showToast('Link copied to clipboard!');
    setTimeout(() => { btn.innerHTML = origHtml; btn.classList.remove('copied'); }, 2200);
  };

  doCopy();
}

/* ── Print ─────────────────────────────────────────────────────── */
function printExamSlip() {
  const pw = window.open('/apply/print-exam-slip', '_blank');
  if (pw) {
    pw.onload = () => setTimeout(() => { pw.focus(); pw.print(); }, 800);
  } else {
    showToast('Pop-up blocked. Printing current page…');
    setTimeout(() => window.print(), 1200);
  }
}

/* ── Share ─────────────────────────────────────────────────────── */
function shareSlip() {
  if (navigator.share) {
    navigator.share({
      title: 'Examination Slip — FCT College of Nursing Sciences',
      text: 'My examination slip for the 2025/2026 admission screening.',
      url: window.location.href
    }).catch(console.error);
  } else {
    copyField('verificationLink', document.querySelector('#verificationLink + .copy-btn'));
  }
}

/* ── Download button → print page ─────────────────────────────── */
document.getElementById('downloadBtn')?.addEventListener('click', function(e) {
  e.preventDefault();
  printExamSlip();
});

/* ── Keyboard shortcut: Ctrl/Cmd + P ───────────────────────────── */
document.addEventListener('keydown', function(e) {
  if ((e.ctrlKey || e.metaKey) && e.key === 'p') {
    e.preventDefault();
    printExamSlip();
  }
});

/* ── Toast ─────────────────────────────────────────────────────── */
function showToast(msg) {
  document.querySelectorAll('.toast').forEach(t => t.remove());
  const t = document.createElement('div');
  t.className = 'toast';
  t.innerHTML = `<i class="fas fa-circle-check"></i> ${msg}`;
  document.body.appendChild(t);
  setTimeout(() => {
    t.style.transition = 'opacity .3s';
    t.style.opacity = '0';
    setTimeout(() => t.remove(), 340);
  }, 2600);
}
</script>
</body>
</html>