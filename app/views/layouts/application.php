<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0, viewport-fit=cover">
    <title><?php echo $pageTitle ?? 'Application Portal - FCT College of Nursing Sciences'; ?></title>
    <meta name="description" content="<?php echo $pageDescription ?? 'Apply for admission into ND/HND Nursing programme'; ?>">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;600;700&family=DM+Sans:wght@300;400;500;600&family=DM+Mono:wght@400;500&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        /* =========================================================
           RESET & ROOT
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
            --sky:         #2070C0;
            --sky-light:   #EBF3FC;
            --white:       #FFFFFF;
            --off-white:   #F8FAFD;
            --border:      #E2E8F4;
            --border-dark: #C8D3E8;
            --text-dark:   #0F1B35;
            --text-body:   #374160;
            --text-muted:  #7A86A0;
            --shadow-sm:   0 2px 8px rgba(15,27,53,0.07);
            --shadow-md:   0 8px 24px rgba(15,27,53,0.10);
            --shadow-lg:   0 20px 48px rgba(15,27,53,0.14);
            --radius-sm:   6px;
            --radius-md:   10px;
            --radius-lg:   16px;
            --radius-xl:   24px;
        }

        body {
            font-family: 'DM Sans', -apple-system, sans-serif;
            background: var(--off-white);
            background-image:
                radial-gradient(ellipse 80% 60% at 20% -10%, rgba(15,27,53,0.06) 0%, transparent 70%),
                radial-gradient(ellipse 60% 50% at 80% 110%, rgba(200,150,58,0.07) 0%, transparent 60%);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 32px 16px 48px;
            color: var(--text-body);
        }

        /* =========================================================
           WRAPPER
        ========================================================= */
        .portal-wrap {
            width: 100%;
            max-width: 960px;
            animation: rise 0.5s cubic-bezier(0.22,0.61,0.36,1) both;
        }

        @keyframes rise {
            from { opacity: 0; transform: translateY(24px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        /* =========================================================
           HEADER
        ========================================================= */
        .portal-header {
            background: var(--navy);
            border-radius: var(--radius-xl) var(--radius-xl) 0 0;
            overflow: hidden;
            position: relative;
        }

        /* Gold top band */
        .portal-header::before {
            content: '';
            display: block;
            height: 4px;
            background: linear-gradient(90deg, var(--gold) 0%, var(--gold-light) 50%, var(--gold) 100%);
        }

        /* Diagonal texture overlay */
        .portal-header::after {
            content: '';
            position: absolute;
            inset: 0;
            background-image: repeating-linear-gradient(
                45deg, transparent, transparent 60px,
                rgba(255,255,255,0.015) 60px, rgba(255,255,255,0.015) 61px
            );
            pointer-events: none;
        }

        .portal-header-inner {
            display: flex;
            align-items: center;
            gap: 20px;
            padding: 28px 40px;
            position: relative;
            z-index: 1;
        }

        .portal-emblem {
            flex-shrink: 0;
            width: 64px; height: 64px;
            background: rgba(255,255,255,0.07);
            border: 1.5px solid rgba(200,150,58,0.45);
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            font-size: 24px;
            color: var(--gold-light);
        }

        .portal-header-text {
            flex: 1;
            min-width: 0;
        }

        .portal-header-text h1 {
            font-family: 'Playfair Display', Georgia, serif;
            font-size: 22px;
            font-weight: 700;
            color: #fff;
            letter-spacing: -0.3px;
            line-height: 1.2;
            margin-bottom: 4px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .portal-header-text p {
            font-size: 12.5px;
            color: rgba(255,255,255,0.50);
            letter-spacing: 0.4px;
            text-transform: uppercase;
            margin: 0;
        }

        /* Right side: badge + user row + logout */
        .portal-header-right {
            display: flex;
            flex-direction: column;
            align-items: flex-end;
            gap: 10px;
            flex-shrink: 0;
        }

        .portal-header-badge {
            background: rgba(200,150,58,0.15);
            border: 1px solid rgba(200,150,58,0.3);
            border-radius: 50px;
            padding: 6px 14px;
            font-size: 11px;
            font-weight: 600;
            color: var(--gold-light);
            letter-spacing: 0.5px;
            white-space: nowrap;
        }

        .portal-user-row {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .portal-user-avatar {
            width: 28px; height: 28px;
            background: rgba(255,255,255,0.1);
            border: 1px solid rgba(255,255,255,0.15);
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            color: rgba(255,255,255,0.6);
            font-size: 11px;
            flex-shrink: 0;
        }

        .portal-user-name {
            font-size: 12.5px;
            color: rgba(255,255,255,0.65);
            white-space: nowrap;
        }

        .portal-user-name strong {
            color: rgba(255,255,255,0.9);
            font-weight: 600;
        }

        .portal-logout-btn {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 5px 13px;
            background: rgba(192,57,43,0.12);
            border: 1px solid rgba(192,57,43,0.3);
            border-radius: 50px;
            font-size: 11.5px;
            font-weight: 600;
            color: #F9A89E;
            letter-spacing: 0.3px;
            text-decoration: none;
            transition: all .2s;
            white-space: nowrap;
            font-family: 'DM Sans', sans-serif;
        }

        .portal-logout-btn:hover {
            background: rgba(192,57,43,0.25);
            border-color: rgba(192,57,43,0.5);
            color: #FFC5BF;
        }

        .portal-logout-btn i { font-size: 10px; }

        /* =========================================================
           PROGRESS TRACKER — 5 steps (FIXED)
        ========================================================= */
        .progress-track {
            background: var(--navy-mid);
            padding: 18px 40px;
            display: flex;
            align-items: center;
            gap: 0;
            border-top: 1px solid rgba(255,255,255,0.06);
        }

        .track-step {
            flex: 1;
            display: flex;
            align-items: center;
            position: relative;
        }

        .track-step-inner {
            display: flex;
            align-items: center;
            gap: 8px;
            flex: 1;
            min-width: 0;
        }

        .track-num {
            width: 30px; height: 30px;
            border-radius: 50%;
            border: 2px solid rgba(255,255,255,0.15);
            background: transparent;
            display: flex; align-items: center; justify-content: center;
            font-size: 11px;
            font-weight: 600;
            color: rgba(255,255,255,0.3);
            transition: all 0.3s;
            flex-shrink: 0;
        }

        .track-step.completed .track-num {
            background: var(--teal);
            border-color: var(--teal);
            color: #fff;
        }

        .track-step.active .track-num {
            background: var(--gold);
            border-color: var(--gold);
            color: var(--navy);
            box-shadow: 0 0 0 4px rgba(200,150,58,0.25);
        }

        .track-info {
            display: flex;
            flex-direction: column;
            min-width: 0;
        }

        .track-label {
            font-size: 10px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: rgba(255,255,255,0.3);
            transition: color 0.3s;
            line-height: 1.3;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .track-step.active    .track-label { color: var(--gold-light); }
        .track-step.completed .track-label { color: rgba(255,255,255,0.65); }

        .track-sublabel {
            font-size: 10px;
            color: rgba(255,255,255,0.2);
            margin-top: 1px;
            transition: color 0.3s;
            white-space: nowrap;
        }

        .track-step.active    .track-sublabel { color: rgba(255,255,255,0.45); }
        .track-step.completed .track-sublabel { color: rgba(255,255,255,0.35); }

        .track-connector {
            flex: 0 0 10px;
            height: 1px;
            background: rgba(255,255,255,0.08);
            margin: 0 4px;
        }

        .track-connector.done { background: var(--teal); }

        /* =========================================================
           BODY SHELL
        ========================================================= */
        .portal-body {
            background: var(--white);
            border-left: 1px solid var(--border);
            border-right: 1px solid var(--border);
            padding: 40px;
        }

        /* =========================================================
           FLASH MESSAGES
        ========================================================= */
        .flash-messages { margin-bottom: 28px; }

        .flash-msg {
            display: flex;
            align-items: flex-start;
            gap: 14px;
            padding: 14px 18px;
            border-radius: var(--radius-md);
            margin-bottom: 10px;
            font-size: 14px;
            border: 1px solid transparent;
            animation: popIn 0.3s ease;
        }

        @keyframes popIn {
            from { opacity: 0; transform: translateY(-8px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        .flash-msg.success { background: var(--teal-light); border-color: rgba(29,138,122,0.25); color: #145f55; }
        .flash-msg.error   { background: var(--red-light);  border-color: rgba(192,57,43,0.25);  color: #8b1a12; }
        .flash-msg.info    { background: var(--sky-light);  border-color: rgba(32,112,192,0.25); color: #134c84; }

        .flash-icon { font-size: 16px; margin-top: 1px; flex-shrink: 0; }
        .flash-msg.success .flash-icon { color: var(--teal); }
        .flash-msg.error   .flash-icon { color: var(--red); }
        .flash-msg.info    .flash-icon { color: var(--sky); }

        /* =========================================================
           FORM SECTIONS
        ========================================================= */
        .form-section {
            border: 1px solid var(--border);
            border-radius: var(--radius-lg);
            margin-bottom: 28px;
            overflow: hidden;
        }

        .form-section-head {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 18px 24px;
            background: var(--off-white);
            border-bottom: 1px solid var(--border);
        }

        .section-icon {
            width: 36px; height: 36px;
            background: var(--navy);
            border-radius: var(--radius-sm);
            display: flex; align-items: center; justify-content: center;
            font-size: 14px;
            color: var(--gold-light);
            flex-shrink: 0;
        }

        .form-section-head h3 {
            font-family: 'Playfair Display', serif;
            font-size: 17px;
            font-weight: 600;
            color: var(--text-dark);
            margin: 0;
        }

        .form-section-head span {
            font-size: 12px;
            color: var(--text-muted);
            margin-left: auto;
        }

        .form-section-body { padding: 28px 24px; }

        .form-group { margin-bottom: 22px; }
        .form-group:last-child { margin-bottom: 0; }

        .form-label {
            display: block;
            font-size: 13px;
            font-weight: 600;
            color: var(--text-dark);
            margin-bottom: 7px;
            letter-spacing: 0.2px;
        }

        .form-label .required { color: var(--red); margin-left: 2px; }

        .form-control,
        .form-select {
            border: 1.5px solid var(--border-dark);
            border-radius: var(--radius-md);
            padding: 11px 14px;
            font-size: 14px;
            font-family: 'DM Sans', sans-serif;
            color: var(--text-dark);
            background: var(--white);
            transition: border-color 0.2s, box-shadow 0.2s;
            width: 100%;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: var(--navy-mid);
            box-shadow: 0 0 0 3px rgba(26,45,85,0.1);
            outline: none;
        }

        .form-control.is-invalid { border-color: var(--red); }
        .form-control.is-valid   { border-color: var(--teal); }

        .form-hint {
            font-size: 12px;
            color: var(--text-muted);
            margin-top: 5px;
        }

        /* =========================================================
           BUTTONS
        ========================================================= */
        .btn {
            font-family: 'DM Sans', sans-serif;
            font-size: 14px;
            font-weight: 600;
            padding: 11px 26px;
            border-radius: var(--radius-md);
            border: none;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: all 0.2s;
            letter-spacing: 0.1px;
        }

        .btn-primary {
            background: var(--navy);
            color: var(--white);
            box-shadow: 0 4px 12px rgba(15,27,53,0.25);
        }
        .btn-primary:hover {
            background: var(--navy-light);
            transform: translateY(-1px);
            box-shadow: 0 6px 18px rgba(15,27,53,0.3);
            color: var(--white);
        }

        .btn-gold {
            background: var(--gold);
            color: var(--navy);
            box-shadow: 0 4px 12px rgba(200,150,58,0.3);
        }
        .btn-gold:hover {
            background: var(--gold-light);
            transform: translateY(-1px);
            box-shadow: 0 6px 18px rgba(200,150,58,0.4);
        }

        .btn-success {
            background: var(--teal);
            color: var(--white);
        }
        .btn-success:hover {
            background: #16756A;
            transform: translateY(-1px);
            color: var(--white);
        }

        .btn-outline {
            background: transparent;
            color: var(--navy);
            border: 1.5px solid var(--border-dark);
        }
        .btn-outline:hover {
            background: var(--off-white);
            border-color: var(--navy);
        }

        .btn-lg { padding: 14px 36px; font-size: 15px; }
        .btn-sm { padding: 8px 18px; font-size: 13px; }

        /* =========================================================
           PAYMENT CARD
        ========================================================= */
        .payment-card {
            border: 1px solid var(--border);
            border-radius: var(--radius-lg);
            overflow: hidden;
        }

        .payment-card-head {
            background: var(--navy);
            padding: 28px 32px;
            text-align: center;
            position: relative;
        }

        .payment-card-head::after {
            content: '';
            position: absolute;
            bottom: 0; left: 0; right: 0;
            height: 3px;
            background: linear-gradient(90deg, transparent, var(--gold), transparent);
        }

        .payment-label {
            font-size: 11px;
            font-weight: 600;
            letter-spacing: 1.5px;
            text-transform: uppercase;
            color: rgba(255,255,255,0.5);
            margin-bottom: 12px;
        }

        .payment-amount {
            font-family: 'Playfair Display', serif;
            font-size: 56px;
            font-weight: 700;
            color: var(--gold-light);
            line-height: 1;
        }

        .payment-amount sup {
            font-size: 24px;
            vertical-align: super;
            opacity: 0.7;
        }

        .payment-card-body { padding: 28px 32px; }

        .payment-rrr-box {
            background: var(--off-white);
            border: 1.5px dashed var(--border-dark);
            border-radius: var(--radius-md);
            padding: 16px 20px;
            font-family: 'DM Mono', monospace;
            font-size: 20px;
            font-weight: 500;
            color: var(--navy);
            text-align: center;
            letter-spacing: 3px;
            margin-bottom: 20px;
        }

        /* =========================================================
           EXAM SLIP
        ========================================================= */
        .exam-slip {
            border: 1.5px solid var(--navy);
            border-radius: var(--radius-lg);
            overflow: hidden;
        }

        .exam-slip-head {
            background: var(--navy);
            padding: 28px 32px;
            text-align: center;
        }

        .exam-slip-head h2 {
            font-family: 'Playfair Display', serif;
            color: #fff;
            font-size: 22px;
            margin-bottom: 4px;
        }

        .exam-slip-head p {
            font-size: 13px;
            color: rgba(255,255,255,0.55);
            margin: 0;
        }

        .exam-slip-body { padding: 28px 32px; }

        .slip-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 14px;
        }

        .slip-item {
            background: var(--off-white);
            border: 1px solid var(--border);
            border-radius: var(--radius-md);
            padding: 14px 16px;
        }

        .slip-item .lbl {
            font-size: 10px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: var(--text-muted);
            margin-bottom: 4px;
        }

        .slip-item .val {
            font-size: 15px;
            font-weight: 500;
            color: var(--text-dark);
        }

        /* =========================================================
           DOCUMENT PREVIEW
        ========================================================= */
        .doc-preview {
            position: relative;
            display: inline-block;
            margin: 8px;
        }

        .doc-preview img {
            width: 130px; height: 130px;
            object-fit: cover;
            border: 1.5px solid var(--border-dark);
            border-radius: var(--radius-md);
            display: block;
        }

        .doc-preview:hover img { border-color: var(--navy); }

        .doc-remove {
            position: absolute;
            top: -8px; right: -8px;
            width: 26px; height: 26px;
            background: var(--red);
            color: #fff;
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            cursor: pointer;
            font-size: 11px;
            border: 2px solid #fff;
            transition: transform 0.2s;
        }

        .doc-remove:hover { transform: scale(1.15); }

        /* =========================================================
           DIVIDER
        ========================================================= */
        .section-divider {
            border: none;
            border-top: 1px solid var(--border);
            margin: 32px 0;
        }

        /* =========================================================
           FOOTER
        ========================================================= */
        .portal-footer {
            background: var(--navy);
            border-radius: 0 0 var(--radius-xl) var(--radius-xl);
            padding: 22px 40px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 12px;
        }

        .portal-footer p {
            margin: 0;
            font-size: 13px;
            color: rgba(255,255,255,0.4);
        }

        .footer-contacts {
            display: flex;
            gap: 24px;
            flex-wrap: wrap;
        }

        .footer-contact-item {
            display: flex;
            align-items: center;
            gap: 7px;
            font-size: 13px;
            color: rgba(255,255,255,0.55);
            text-decoration: none;
            transition: color 0.2s;
        }

        .footer-contact-item:hover { color: var(--gold-light); }
        .footer-contact-item i { color: var(--gold); font-size: 12px; }

        /* =========================================================
           UTILITIES
        ========================================================= */
        .text-gold { color: var(--gold) !important; }
        .text-navy { color: var(--navy) !important; }
        .text-teal { color: var(--teal) !important; }

        /* =========================================================
           PAYMENT BUTTON AREA (ADDED)
        ========================================================= */
        #paymentButtonArea .btn-amber {
            display: block;
            width: 100%;
            padding: 1rem 1.5rem;
            background: var(--amber, #D4860B);
            color: white;
            border: none;
            border-radius: var(--r-md, 8px);
            font-weight: 600;
            text-align: center;
            text-decoration: none;
            transition: all .25s;
        }
        
        #paymentButtonArea .btn-amber:hover {
            background: #b87209;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(212,134,11,0.3);
        }

        /* =========================================================
           RESPONSIVE
        ========================================================= */
        @media (max-width: 768px) {
            body { padding: 16px 12px 32px; }

            .portal-header-inner {
                flex-wrap: wrap;
                padding: 20px;
                gap: 12px;
            }

            .portal-header-text h1 {
                font-size: 17px;
                white-space: normal;
            }

            .portal-header-right {
                width: 100%;
                flex-direction: row;
                align-items: center;
                justify-content: space-between;
            }

            /* Progress tracker: wrap to 2-col cards */
            .progress-track {
                padding: 14px 16px;
                flex-wrap: wrap;
                gap: 8px;
            }

            .track-connector { display: none; }

            .track-step {
                flex: 0 0 calc(50% - 4px);
                background: rgba(255,255,255,0.04);
                border: 1px solid rgba(255,255,255,0.08);
                border-radius: var(--radius-md);
                padding: 10px 12px;
            }

            /* 5th step spans full width (odd) */
            .track-step:last-child { flex: 0 0 100%; }

            .portal-body { padding: 24px 18px; }

            .portal-footer {
                flex-direction: column;
                align-items: flex-start;
                padding: 20px 24px;
            }

            .slip-grid { grid-template-columns: 1fr; }
            .payment-amount { font-size: 44px; }
        }

        @media (max-width: 480px) {
            .portal-header-text h1 { font-size: 15px; }
            .portal-emblem { width: 48px; height: 48px; font-size: 18px; }
            /* All 5 steps full width on very small screens */
            .track-step { flex: 0 0 100%; }
        }
    </style>
</head>
<body>
<div class="portal-wrap">

    <!-- ==================== HEADER ==================== -->
    <header class="portal-header">
        <div class="portal-header-inner">

            <div class="portal-emblem">
                <i class="fas fa-star-of-life"></i>
            </div>

            <div class="portal-header-text">
                <h1>FCT College of Nursing Sciences</h1>
                <p>Admissions Application Portal &mdash; 2025 / 2026 Session</p>
            </div>

            <div class="portal-header-right">
                <div class="portal-header-badge">
                    <i class="fas fa-shield-alt" style="margin-right:5px;font-size:10px;"></i>
                    Secure Portal
                </div>

                <?php
                    // Resolve applicant name — prefer application array, fall back to $applicant_name
                    $applicantDisplayName = null;
                    if (isset($application) && is_array($application)) {
                        $fn   = trim($application['first_name'] ?? '');
                        $ln   = trim($application['last_name']  ?? '');
                        $full = trim("$fn $ln");
                        if (!empty($full)) $applicantDisplayName = $full;
                    }
                    if (!$applicantDisplayName && !empty($applicant_name)) {
                        $applicantDisplayName = trim($applicant_name);
                    }
                ?>

                <?php if ($applicantDisplayName): ?>
                <div class="portal-user-row">
                    <div class="portal-user-avatar">
                        <i class="fas fa-user"></i>
                    </div>
                    <span class="portal-user-name">
                        <strong><?php echo htmlspecialchars($applicantDisplayName); ?></strong>
                    </span>
                    <a href="/applicant/logout" class="portal-logout-btn"
                       onclick="return confirm('Are you sure you want to logout? Your progress is saved.');">
                        <i class="fas fa-sign-out-alt"></i> Logout
                    </a>
                </div>
                <?php endif; ?>
            </div>

        </div><!-- /portal-header-inner -->

        <?php
            /* ── Step tracking - FIXED for 5-step flow ───────────────────
             *
             * Steps:
             *   1 – Create Account    (User is logged in)
             *   2 – JAMB Verification (jamb_number present)
             *   3 – Application Form  (date_of_birth, phone, address present)
             *   4 – Payment           (payment_status === 'success')
             *   5 – Exam Slip         (exam_slip_generated === 1)
             */
            $showSteps   = false;
            $currentStep = 1; // Default to step 1

            if (isset($application) && is_array($application)) {
                $showSteps = true;
                
                // Check if user is logged in (always true for step 1)
                $currentStep = 1;
                
                // Step 2: JAMB Verification
                if (!empty($application['jamb_number'])) {
                    $currentStep = 2;
                }
                
                // Step 3: Application Form (personal details filled)
                if (!empty($application['date_of_birth']) && 
                    !empty($application['phone']) && 
                    !empty($application['address'])) {
                    $currentStep = 3;
                }
                
                // Step 4: Payment
                $hasPaid = false;
                if (isset($payment_status) && is_array($payment_status)) {
                    $hasPaid = ($payment_status['status'] === 'success');
                } elseif (isset($paymentModel)) {
                    // Fallback to check directly
                    $hasPaid = $this->paymentModel->hasSuccessfulPayment($application['id']);
                }
                
                if ($hasPaid) {
                    $currentStep = 4;
                }
                
                // Step 5: Exam Slip
                if (!empty($application['exam_slip_generated']) || 
                    (isset($exam_slip) && !empty($exam_slip))) {
                    $currentStep = 5;
                }
            }

            $steps = [
                1 => ['label' => 'Create Account',    'sub' => 'Register'],
                2 => ['label' => 'JAMB Verification', 'sub' => 'JAMB check'],
                3 => ['label' => 'Application Form',  'sub' => 'Fill form'],
                4 => ['label' => 'Payment',           'sub' => 'Remita RRR'],
                5 => ['label' => 'Exam Slip',         'sub' => 'Download'],
            ];
        ?>

        <?php if (!isset($hideSteps) && $showSteps): ?>
        <div class="progress-track">
            <?php foreach ($steps as $n => $step):
                $cls    = '';
                if ($currentStep >  $n) $cls = 'completed';
                if ($currentStep == $n) $cls = 'active';
                $isLast = ($n === array_key_last($steps));
            ?>
                <div class="track-step <?php echo $cls; ?>">
                    <div class="track-step-inner">
                        <div class="track-num">
                            <?php if ($currentStep > $n): ?>
                                <i class="fas fa-check" style="font-size:9px;"></i>
                            <?php else: ?>
                                <?php echo $n; ?>
                            <?php endif; ?>
                        </div>
                        <div class="track-info">
                            <span class="track-label"><?php echo htmlspecialchars($step['label']); ?></span>
                            <span class="track-sublabel"><?php echo htmlspecialchars($step['sub']); ?></span>
                        </div>
                    </div>
                </div>
                <?php if (!$isLast): ?>
                    <div class="track-connector <?php echo ($currentStep > $n) ? 'done' : ''; ?>"></div>
                <?php endif; ?>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>

    </header>
    <!-- /header -->


    <!-- ==================== BODY ==================== -->
    <main class="portal-body">

        <?php if (!empty($flash_success)): ?>
        <div class="flash-messages">
            <div class="flash-msg success">
                <i class="fas fa-check-circle flash-icon"></i>
                <span><?php echo htmlspecialchars($flash_success); ?></span>
            </div>
        </div>
        <?php endif; ?>

        <?php if (!empty($flash_error)): ?>
        <div class="flash-messages">
            <div class="flash-msg error">
                <i class="fas fa-exclamation-circle flash-icon"></i>
                <span><?php echo htmlspecialchars($flash_error); ?></span>
            </div>
        </div>
        <?php endif; ?>

        <?php if (!empty($flash_info)): ?>
        <div class="flash-messages">
            <div class="flash-msg info">
                <i class="fas fa-info-circle flash-icon"></i>
                <span><?php echo htmlspecialchars($flash_info); ?></span>
            </div>
        </div>
        <?php endif; ?>

        <?php echo $content; ?>

    </main>
    <!-- /body -->


    <!-- ==================== FOOTER ==================== -->
    <footer class="portal-footer">
        <p>&copy; <?php echo date('Y'); ?> FCT College of Nursing Sciences. All rights reserved.</p>
        <div class="footer-contacts">
            <?php
                $supportPhone = $settings['key_value']['support_phone_1'] ?? '07039837749';
                $supportEmail = $settings['key_value']['support_email']   ?? 'info@fctcns.edu.ng';
            ?>
            <a class="footer-contact-item" href="tel:<?php echo htmlspecialchars($supportPhone); ?>">
                <i class="fas fa-phone-alt"></i>
                <?php echo htmlspecialchars($supportPhone); ?>
            </a>
            <a class="footer-contact-item" href="mailto:<?php echo htmlspecialchars($supportEmail); ?>">
                <i class="fas fa-envelope"></i>
                <?php echo htmlspecialchars($supportEmail); ?>
            </a>
        </div>
    </footer>

</div><!-- /portal-wrap -->


<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="<?php echo defined('BASE_URL') ? BASE_URL : ''; ?>/assets/js/Payment.js"></script>

<script>
    // Auto-dismiss flash messages after 5.5s
    setTimeout(function () {
        document.querySelectorAll('.flash-msg').forEach(function (el) {
            el.style.transition = 'opacity 0.4s';
            el.style.opacity    = '0';
            setTimeout(function () { el.remove(); }, 400);
        });
    }, 5500);

    function confirmAction(msg) {
        return confirm(msg || 'Are you sure?');
    }

    function checkPasswordStrength(pw) {
        let s = 0;
        if (pw.length >= 8)     s++;
        if (/[a-z]/.test(pw))   s++;
        if (/[A-Z]/.test(pw))   s++;
        if (/[0-9]/.test(pw))   s++;
        if (/[$@#&!]/.test(pw)) s++;
        return s;
    }

    function previewImage(input, previewId) {
        if (input.files && input.files[0]) {
            const r = new FileReader();
            r.onload = function (e) {
                const el = document.getElementById(previewId);
                el.src           = e.target.result;
                el.style.display = 'block';
            };
            r.readAsDataURL(input.files[0]);
        }
    }

    function confirmPassportUpload(input) {
        if (input.files && input.files[0]) {
            const r = new FileReader();
            r.onload = function (e) {
                if (confirm('Is this your correct passport photograph? Click OK to upload.')) {
                    document.getElementById('passport-preview').src           = e.target.result;
                    document.getElementById('passport-preview').style.display = 'block';
                    document.getElementById('passport-confirmed').value        = '1';
                } else {
                    input.value = '';
                    document.getElementById('passport-preview').style.display = 'none';
                    document.getElementById('passport-confirmed').value        = '0';
                }
            };
            r.readAsDataURL(input.files[0]);
        }
    }
</script>
</body>
</html>