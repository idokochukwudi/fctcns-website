<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Application Settings — Admin · FCT CNS</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700&family=DM+Sans:wght@300;400;500;600&family=DM+Mono:wght@400;500&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        /* =========================================================
           ROOT
        ========================================================= */
        *, *::before, *::after { margin: 0; padding: 0; box-sizing: border-box; }

        :root {
            --navy:        #0F1B35;
            --navy-mid:    #1A2D55;
            --navy-light:  #243E73;
            --gold:        #C8963A;
            --gold-light:  #E2B05F;
            --gold-pale:   #FDF6E9;
            --teal:        #1D8A7A;
            --teal-light:  #E8F7F5;
            --red:         #C0392B;
            --red-light:   #FDEEEC;
            --amber:       #D97706;
            --amber-light: #FFFBEB;
            --white:       #FFFFFF;
            --off-white:   #F8FAFD;
            --border:      #E2E8F4;
            --border-dark: #C8D3E8;
            --text-dark:   #0F1B35;
            --text-body:   #374160;
            --text-muted:  #7A86A0;
            --radius-sm:   6px;
            --radius-md:   10px;
            --radius-lg:   16px;
            --sidebar-w:   260px;
        }

        body {
            font-family: 'DM Sans', -apple-system, sans-serif;
            background: var(--off-white);
            color: var(--text-body);
            font-size: 14px;
        }

        /* =========================================================
           LAYOUT SHELL (assumes admin sidebar exists)
        ========================================================= */
        .admin-content {
            padding: 32px 36px 56px;
            max-width: 1080px;
        }

        /* =========================================================
           PAGE TITLE BAR
        ========================================================= */
        .page-titlebar {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 20px;
            margin-bottom: 32px;
            flex-wrap: wrap;
        }

        .page-titlebar-left h1 {
            font-family: 'Playfair Display', serif;
            font-size: 26px;
            font-weight: 700;
            color: var(--navy);
            margin-bottom: 4px;
            line-height: 1.2;
        }

        .page-titlebar-left p {
            font-size: 13px;
            color: var(--text-muted);
            margin: 0;
        }

        .page-breadcrumb {
            display: flex;
            align-items: center;
            gap: 6px;
            font-size: 12px;
            color: var(--text-muted);
            margin-bottom: 6px;
        }

        .page-breadcrumb a {
            color: var(--navy-mid);
            text-decoration: none;
            font-weight: 500;
        }

        .page-breadcrumb a:hover { color: var(--gold); }
        .page-breadcrumb i { font-size: 9px; opacity: 0.5; }

        /* =========================================================
           FLASH ALERTS
        ========================================================= */
        .flash-bar {
            display: flex;
            align-items: flex-start;
            gap: 12px;
            padding: 13px 16px;
            border-radius: var(--radius-md);
            margin-bottom: 24px;
            font-size: 14px;
            border: 1px solid transparent;
        }

        .flash-bar.success {
            background: var(--teal-light);
            border-color: rgba(29,138,122,.25);
            color: #145f55;
        }

        .flash-bar.error {
            background: var(--red-light);
            border-color: rgba(192,57,43,.25);
            color: #8b1a12;
        }

        .flash-bar i { margin-top: 1px; flex-shrink: 0; }
        .flash-bar.success i { color: var(--teal); }
        .flash-bar.error   i { color: var(--red); }

        /* =========================================================
           SETTINGS CARD
        ========================================================= */
        .s-card {
            background: var(--white);
            border: 1px solid var(--border);
            border-radius: var(--radius-lg);
            margin-bottom: 20px;
            overflow: hidden;
        }

        .s-card-head {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 16px 22px;
            border-bottom: 1px solid var(--border);
            background: var(--off-white);
        }

        .s-card-icon {
            width: 32px;
            height: 32px;
            border-radius: var(--radius-sm);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 13px;
            flex-shrink: 0;
            background: var(--navy);
            color: var(--gold-light);
        }

        /* Icon accent variants — subtle left border only */
        .s-card.accent-teal  .s-card-head { border-left: 3px solid var(--teal); }
        .s-card.accent-gold  .s-card-head { border-left: 3px solid var(--gold); }
        .s-card.accent-navy  .s-card-head { border-left: 3px solid var(--navy-light); }
        .s-card.accent-amber .s-card-head { border-left: 3px solid var(--amber); }
        .s-card.accent-red   .s-card-head { border-left: 3px solid var(--red); }

        .s-card.accent-teal  .s-card-icon { background: var(--teal); }
        .s-card.accent-gold  .s-card-icon { background: var(--gold); color: var(--navy); }
        .s-card.accent-amber .s-card-icon { background: var(--amber); color: #fff; }
        .s-card.accent-red   .s-card-icon { background: var(--red); color: #fff; }

        .s-card-head h3 {
            font-size: 15px;
            font-weight: 600;
            color: var(--text-dark);
            margin: 0;
        }

        .s-card-head p {
            font-size: 12px;
            color: var(--text-muted);
            margin: 0 0 0 auto;
        }

        .s-card-body {
            padding: 24px 22px;
        }

        /* =========================================================
           FORM ELEMENTS
        ========================================================= */
        .field-group { margin-bottom: 18px; }
        .field-group:last-child { margin-bottom: 0; }

        .field-label {
            display: block;
            font-size: 12px;
            font-weight: 600;
            color: var(--text-dark);
            margin-bottom: 6px;
            letter-spacing: 0.15px;
        }

        .field-label .req { color: var(--red); margin-left: 2px; }

        .field-hint {
            font-size: 11px;
            color: var(--text-muted);
            margin-top: 4px;
            line-height: 1.4;
        }

        input[type="text"],
        input[type="number"],
        input[type="date"],
        input[type="email"],
        textarea,
        select {
            width: 100%;
            border: 1.5px solid var(--border-dark);
            border-radius: var(--radius-md);
            padding: 10px 13px;
            font-size: 14px;
            font-family: 'DM Sans', sans-serif;
            color: var(--text-dark);
            background: var(--white);
            transition: border-color 0.2s, box-shadow 0.2s;
            appearance: none;
        }

        input:focus, textarea:focus, select:focus {
            border-color: var(--navy-mid);
            box-shadow: 0 0 0 3px rgba(26,45,85,0.09);
            outline: none;
        }

        textarea { resize: vertical; min-height: 72px; }

        /* Status toggle pill */
        .status-toggle {
            display: flex;
            gap: 0;
            border: 1.5px solid var(--border-dark);
            border-radius: var(--radius-md);
            overflow: hidden;
        }

        .status-toggle label {
            flex: 1;
            text-align: center;
            padding: 9px 0;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.2s, color 0.2s;
            user-select: none;
        }

        .status-toggle input[type="radio"] { display: none; }

        .status-toggle input:checked + label.open-lbl {
            background: var(--teal);
            color: #fff;
        }

        .status-toggle input:checked + label.closed-lbl {
            background: var(--red);
            color: #fff;
        }

        .status-toggle label:not(.open-lbl):not(.closed-lbl) {
            background: var(--off-white);
            color: var(--text-muted);
        }

        /* Fee input with currency prefix */
        .input-group-field {
            display: flex;
            border: 1.5px solid var(--border-dark);
            border-radius: var(--radius-md);
            overflow: hidden;
            transition: border-color 0.2s, box-shadow 0.2s;
        }

        .input-group-field:focus-within {
            border-color: var(--navy-mid);
            box-shadow: 0 0 0 3px rgba(26,45,85,0.09);
        }

        .input-prefix {
            background: var(--off-white);
            border-right: 1.5px solid var(--border-dark);
            padding: 10px 13px;
            font-size: 14px;
            font-weight: 600;
            color: var(--text-muted);
            white-space: nowrap;
            display: flex;
            align-items: center;
        }

        .input-group-field input {
            border: none;
            border-radius: 0;
            flex: 1;
            box-shadow: none !important;
        }

        .input-group-field input:focus {
            border: none;
            box-shadow: none;
            outline: none;
        }

        /* Grid layout helpers */
        .fields-row {
            display: flex;
            gap: 18px;
            flex-wrap: wrap;
        }

        .fields-row .field-group { flex: 1; min-width: 180px; }
        .field-full { flex: 0 0 100% !important; }

        /* Date pair */
        .date-pair {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 18px;
        }

        .date-pair-label {
            grid-column: 1 / -1;
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            color: var(--text-muted);
            padding-bottom: 6px;
            border-bottom: 1px solid var(--border);
            margin-bottom: -6px;
        }

        /* =========================================================
           PORTAL STATUS BADGE
        ========================================================= */
        .status-preview {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 4px 10px;
            border-radius: 50px;
            font-size: 11px;
            font-weight: 700;
            letter-spacing: 0.4px;
            text-transform: uppercase;
        }

        .status-preview.open   { background: var(--teal-light);   color: var(--teal); }
        .status-preview.closed { background: var(--red-light);    color: var(--red); }
        .status-preview i { font-size: 8px; }

        /* =========================================================
           ACTION BAR
        ========================================================= */
        .action-bar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 20px 0 0;
            border-top: 1px solid var(--border);
            margin-top: 8px;
            flex-wrap: wrap;
            gap: 12px;
        }

        .btn {
            font-family: 'DM Sans', sans-serif;
            font-size: 13px;
            font-weight: 600;
            padding: 10px 22px;
            border-radius: var(--radius-md);
            border: none;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: all 0.2s;
            text-decoration: none;
        }

        .btn-navy {
            background: var(--navy);
            color: #fff;
            box-shadow: 0 4px 12px rgba(15,27,53,0.22);
        }

        .btn-navy:hover {
            background: var(--navy-light);
            transform: translateY(-1px);
            color: #fff;
            box-shadow: 0 6px 18px rgba(15,27,53,0.28);
        }

        .btn-ghost {
            background: transparent;
            color: var(--text-body);
            border: 1.5px solid var(--border-dark);
        }

        .btn-ghost:hover {
            background: var(--off-white);
            color: var(--navy);
            border-color: var(--navy);
        }

        .btn-save {
            background: var(--gold);
            color: var(--navy);
            box-shadow: 0 4px 12px rgba(200,150,58,0.28);
        }

        .btn-save:hover {
            background: var(--gold-light);
            transform: translateY(-1px);
            box-shadow: 0 6px 18px rgba(200,150,58,0.38);
        }

        /* =========================================================
           SECTION SEPARATOR LABEL
        ========================================================= */
        .sub-section {
            margin-top: 20px;
            padding-top: 18px;
            border-top: 1px dashed var(--border);
        }

        .sub-section-label {
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            color: var(--text-muted);
            margin-bottom: 14px;
        }

        /* =========================================================
           RESPONSIVE
        ========================================================= */
        @media (max-width: 768px) {
            .admin-content { padding: 20px 16px 40px; }
            .date-pair { grid-template-columns: 1fr; }
            .fields-row .field-group { min-width: 140px; }
        }
    </style>
</head>
<body>

<!-- Assume this view is included inside the admin shell -->
<div class="admin-content">

    <!-- ===== PAGE TITLE BAR ===== -->
    <div class="page-titlebar">
        <div class="page-titlebar-left">
            <div class="page-breadcrumb">
                <a href="/admin">Dashboard</a>
                <i class="fas fa-chevron-right"></i>
                <a href="/admin/applications">Applications</a>
                <i class="fas fa-chevron-right"></i>
                <span>Settings</span>
            </div>
            <h1>Application Settings</h1>
            <p>Configure the portal, fees, dates, and eligibility rules</p>
        </div>
        <div style="display:flex;align-items:center;gap:10px;flex-shrink:0;">
            <!-- Live portal status badge -->
            <?php
                $portalStatus = $settings['key_value']['portal_status'] ?? 'closed';
            ?>
            <span class="status-preview <?php echo $portalStatus; ?>">
                <i class="fas fa-circle"></i>
                Portal <?php echo ucfirst($portalStatus); ?>
            </span>
        </div>
    </div>

    <!-- ===== FLASH MESSAGES ===== -->
    <?php if (!empty($flash_success)): ?>
    <div class="flash-bar success">
        <i class="fas fa-check-circle"></i>
        <span><?php echo htmlspecialchars($flash_success); ?></span>
    </div>
    <?php endif; ?>

    <?php if (!empty($flash_error)): ?>
    <div class="flash-bar error">
        <i class="fas fa-exclamation-circle"></i>
        <span><?php echo htmlspecialchars($flash_error); ?></span>
    </div>
    <?php endif; ?>

    <!-- ===== FORM ===== -->
    <form method="POST" action="/admin/applications/settings" class="needs-validation" novalidate id="settings-form">
        <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">

        <!-- 1. GENERAL / PORTAL STATUS -->
        <div class="s-card accent-navy">
            <div class="s-card-head">
                <div class="s-card-icon"><i class="fas fa-globe"></i></div>
                <h3>Portal Status</h3>
                <p>Visibility & messaging</p>
            </div>
            <div class="s-card-body">
                <div class="fields-row">
                    <div class="field-group" style="flex:0 0 220px;">
                        <label class="field-label">Portal Access</label>
                        <div class="status-toggle">
                            <input type="radio" id="status_open"   name="portal_status" value="open"
                                <?php echo ($settings['key_value']['portal_status'] ?? '') == 'open' ? 'checked' : ''; ?>>
                            <label for="status_open" class="open-lbl">
                                <i class="fas fa-lock-open" style="margin-right:6px;font-size:11px;"></i>Open
                            </label>
                            <input type="radio" id="status_closed" name="portal_status" value="closed"
                                <?php echo ($settings['key_value']['portal_status'] ?? 'closed') == 'closed' ? 'checked' : ''; ?>>
                            <label for="status_closed" class="closed-lbl">
                                <i class="fas fa-lock" style="margin-right:6px;font-size:11px;"></i>Closed
                            </label>
                        </div>
                        <p class="field-hint">Controls whether new applications can be submitted</p>
                    </div>
                    <div class="field-group field-full" style="flex:1;min-width:260px;">
                        <label class="field-label" for="portal_message">Closure Message</label>
                        <textarea id="portal_message" name="portal_message" rows="2"
                            placeholder="e.g. Applications for 2025/2026 are currently closed. Check back soon."
                        ><?php echo htmlspecialchars($settings['key_value']['portal_message'] ?? ''); ?></textarea>
                        <p class="field-hint">Displayed to applicants when the portal is closed</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- 2. PAYMENT -->
        <div class="s-card accent-gold">
            <div class="s-card-head">
                <div class="s-card-icon"><i class="fas fa-credit-card"></i></div>
                <h3>Payment Settings</h3>
                <p>Fees &amp; currency</p>
            </div>
            <div class="s-card-body">
                <div class="fields-row">
                    <div class="field-group" style="flex:0 0 240px;">
                        <label class="field-label" for="application_fee">Application Fee</label>
                        <div class="input-group-field">
                            <span class="input-prefix">₦</span>
                            <input type="number" id="application_fee" name="application_fee"
                                value="<?php echo htmlspecialchars($settings['key_value']['application_fee'] ?? '2200'); ?>"
                                min="0" step="100" placeholder="0">
                        </div>
                        <p class="field-hint">Amount in Naira charged per application</p>
                    </div>
                    <div class="field-group" style="flex:0 0 160px;">
                        <label class="field-label" for="application_currency">Currency Symbol</label>
                        <input type="text" id="application_currency" name="application_currency"
                            value="<?php echo htmlspecialchars($settings['key_value']['application_currency'] ?? '₦'); ?>"
                            maxlength="5">
                        <p class="field-hint">Displayed in receipts &amp; slips</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- 3. DATES -->
        <div class="s-card accent-teal">
            <div class="s-card-head">
                <div class="s-card-icon"><i class="fas fa-calendar-alt"></i></div>
                <h3>Application &amp; CBT Dates</h3>
                <p>Scheduling &amp; deadlines</p>
            </div>
            <div class="s-card-body">
                <p class="sub-section-label" style="margin-top:0;">Application Window</p>
                <div class="date-pair">
                    <div class="field-group">
                        <label class="field-label" for="application_start_date">
                            <i class="fas fa-play-circle" style="color:var(--teal);margin-right:4px;font-size:11px;"></i>
                            Start Date
                        </label>
                        <input type="date" id="application_start_date" name="application_start_date"
                            value="<?php echo htmlspecialchars($settings['key_value']['application_start_date'] ?? ''); ?>">
                    </div>
                    <div class="field-group">
                        <label class="field-label" for="application_end_date">
                            <i class="fas fa-stop-circle" style="color:var(--red);margin-right:4px;font-size:11px;"></i>
                            End Date
                        </label>
                        <input type="date" id="application_end_date" name="application_end_date"
                            value="<?php echo htmlspecialchars($settings['key_value']['application_end_date'] ?? ''); ?>">
                    </div>
                </div>

                <div class="sub-section">
                    <p class="sub-section-label">CBT Examination Window</p>
                    <div class="date-pair">
                        <div class="field-group">
                            <label class="field-label" for="cbt_start_date">
                                <i class="fas fa-play-circle" style="color:var(--teal);margin-right:4px;font-size:11px;"></i>
                                CBT Start Date
                            </label>
                            <input type="date" id="cbt_start_date" name="cbt_start_date"
                                value="<?php echo htmlspecialchars($settings['key_value']['cbt_start_date'] ?? ''); ?>">
                        </div>
                        <div class="field-group">
                            <label class="field-label" for="cbt_end_date">
                                <i class="fas fa-stop-circle" style="color:var(--red);margin-right:4px;font-size:11px;"></i>
                                CBT End Date
                            </label>
                            <input type="date" id="cbt_end_date" name="cbt_end_date"
                                value="<?php echo htmlspecialchars($settings['key_value']['cbt_end_date'] ?? ''); ?>">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- 4. ELIGIBILITY -->
        <div class="s-card accent-amber">
            <div class="s-card-head">
                <div class="s-card-icon"><i class="fas fa-check-circle"></i></div>
                <h3>Eligibility Requirements</h3>
                <p>Thresholds &amp; constraints</p>
            </div>
            <div class="s-card-body">
                <div class="fields-row">
                    <div class="field-group">
                        <label class="field-label" for="min_utme_score">Minimum UTME Score</label>
                        <input type="number" id="min_utme_score" name="min_utme_score"
                            value="<?php echo htmlspecialchars($settings['key_value']['min_utme_score'] ?? '170'); ?>"
                            min="0" max="400">
                        <p class="field-hint">Out of 400 — applicants below this are rejected</p>
                    </div>
                    <div class="field-group">
                        <label class="field-label" for="min_age">Minimum Age</label>
                        <input type="number" id="min_age" name="min_age"
                            value="<?php echo htmlspecialchars($settings['key_value']['min_age'] ?? '16'); ?>"
                            min="0" max="100">
                        <p class="field-hint">At time of application submission</p>
                    </div>
                    <div class="field-group">
                        <label class="field-label" for="max_olevel_sittings">Max O'Level Sittings</label>
                        <input type="number" id="max_olevel_sittings" name="max_olevel_sittings"
                            value="<?php echo htmlspecialchars($settings['key_value']['max_olevel_sittings'] ?? '2'); ?>"
                            min="1" max="2">
                        <p class="field-hint">Maximum number of O'Level attempts accepted (1–2)</p>
                    </div>
                    <div class="field-group field-full">
                        <label class="field-label" for="program_duration">Programme Duration Description</label>
                        <input type="text" id="program_duration" name="program_duration"
                            value="<?php echo htmlspecialchars($settings['key_value']['program_duration'] ?? '4 Years (2 Yrs ND + 2 Yrs HND)'); ?>">
                        <p class="field-hint">Shown on application landing page and admission letters</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- 5. SUPPORT -->
        <div class="s-card accent-red">
            <div class="s-card-head">
                <div class="s-card-icon"><i class="fas fa-headset"></i></div>
                <h3>Support &amp; Contact</h3>
                <p>Phone, email &amp; address</p>
            </div>
            <div class="s-card-body">
                <p class="sub-section-label" style="margin-top:0;">Contact Numbers</p>
                <div class="fields-row">
                    <div class="field-group">
                        <label class="field-label" for="support_phone_1">
                            <i class="fas fa-phone-alt" style="color:var(--teal);font-size:11px;margin-right:4px;"></i>
                            Primary Phone
                        </label>
                        <input type="text" id="support_phone_1" name="support_phone_1"
                            value="<?php echo htmlspecialchars($settings['key_value']['support_phone_1'] ?? '07039837749'); ?>">
                    </div>
                    <div class="field-group">
                        <label class="field-label" for="support_phone_2">Secondary Phone</label>
                        <input type="text" id="support_phone_2" name="support_phone_2"
                            value="<?php echo htmlspecialchars($settings['key_value']['support_phone_2'] ?? '08036625119'); ?>">
                    </div>
                    <div class="field-group">
                        <label class="field-label" for="support_whatsapp">
                            <i class="fab fa-whatsapp" style="color:#25D366;font-size:12px;margin-right:4px;"></i>
                            WhatsApp
                        </label>
                        <input type="text" id="support_whatsapp" name="support_whatsapp"
                            value="<?php echo htmlspecialchars($settings['key_value']['support_whatsapp'] ?? '08082775076'); ?>">
                    </div>
                </div>

                <div class="sub-section">
                    <p class="sub-section-label">Email &amp; Hours</p>
                    <div class="fields-row">
                        <div class="field-group">
                            <label class="field-label" for="support_email">Support Email</label>
                            <input type="email" id="support_email" name="support_email"
                                value="<?php echo htmlspecialchars($settings['key_value']['support_email'] ?? 'support@fctcns.edu.ng'); ?>">
                        </div>
                        <div class="field-group">
                            <label class="field-label" for="support_hours">Support Hours</label>
                            <input type="text" id="support_hours" name="support_hours"
                                value="<?php echo htmlspecialchars($settings['key_value']['support_hours'] ?? 'Mon–Fri, 9:00 AM – 5:00 PM'); ?>">
                        </div>
                        <div class="field-group">
                            <label class="field-label" for="office_hours">Office Hours</label>
                            <input type="text" id="office_hours" name="office_hours"
                                value="<?php echo htmlspecialchars($settings['key_value']['office_hours'] ?? 'Monday – Friday, 8:00 AM – 5:00 PM'); ?>">
                        </div>
                    </div>
                </div>

                <div class="sub-section">
                    <p class="sub-section-label">Physical Address</p>
                    <div class="field-group" style="margin-bottom:0;">
                        <label class="field-label" for="institution_address">
                            <i class="fas fa-map-marker-alt" style="color:var(--red);font-size:11px;margin-right:4px;"></i>
                            Institution Address
                        </label>
                        <input type="text" id="institution_address" name="institution_address"
                            value="<?php echo htmlspecialchars($settings['key_value']['institution_address'] ?? 'FCT College of Nursing Sciences, Gwagwalada, Abuja'); ?>">
                    </div>
                </div>
            </div>
        </div>

        <!-- ACTION BAR -->
        <div class="action-bar">
            <a href="/admin/applications" class="btn btn-ghost">
                <i class="fas fa-arrow-left"></i> Back to Applications
            </a>
            <button type="submit" class="btn btn-save" id="save-btn">
                <i class="fas fa-save"></i> Save Settings
            </button>
        </div>

    </form>
</div><!-- end admin-content -->

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // Native form validation
    (function () {
        const form = document.getElementById('settings-form');

        form.addEventListener('submit', function (e) {
            if (!confirm('Save these settings to the portal?')) {
                e.preventDefault();
                return;
            }
            if (!form.checkValidity()) {
                e.preventDefault();
                e.stopPropagation();
            }
            form.classList.add('was-validated');
        });
    })();

    // Live status badge update
    document.querySelectorAll('input[name="portal_status"]').forEach(function (radio) {
        radio.addEventListener('change', function () {
            const badge = document.querySelector('.status-preview');
            if (this.value === 'open') {
                badge.className = 'status-preview open';
                badge.querySelector('span') && (badge.querySelector('span').textContent = 'Portal Open');
            } else {
                badge.className = 'status-preview closed';
                badge.querySelector('span') && (badge.querySelector('span').textContent = 'Portal Closed');
            }
        });
    });
</script>
</body>
</html>