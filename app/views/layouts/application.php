<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0, viewport-fit=cover">
    <title><?php echo $pageTitle ?? 'Application Portal - FCT College of Nursing Sciences'; ?></title>
    <meta name="description" content="<?php echo $pageDescription ?? 'Apply for admission into ND/HND Nursing programme'; ?>">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <!-- Source Serif 4: highly readable, gentle on the eyes; Outfit for UI labels -->
    <link href="https://fonts.googleapis.com/css2?family=Source+Serif+4:wght@300;400;500;600;700&family=Outfit:wght@300;400;500;600;700&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        /* ═══════════════════════════════════════════════
           RESET & ROOT
        ═══════════════════════════════════════════════ */
        *, *::before, *::after { margin: 0; padding: 0; box-sizing: border-box; }

        :root {
            /* ── Purple brand system ── */
            --pu:          #6E026F;
            --pu-dark:     #500150;
            --pu-deeper:   #380038;
            --pu-mid:      #8a0d8b;
            --pu-light:    #b84fb9;
            --pu-pale:     #f9edf9;
            --pu-bg:       #f3e2f3;

            /* ── Accent gold (kept for amounts / highlights) ── */
            --gold:        #c8860a;
            --gold-light:  #e0a020;
            --gold-pale:   #fdf6e3;

            /* ── Status colours ── */
            --green:       #1a6b45;
            --green-bg:    #edf9f3;
            --green-border:#b2dfcc;
            --red:         #b91c1c;
            --red-bg:      #fdf2f2;
            --red-border:  #fca5a5;
            --blue:        #1d4ed8;
            --blue-bg:     #eff6ff;
            --blue-border: #bfdbfe;

            /* ── Neutrals ── */
            --text:        #1a0a1a;
            --text-body:   #3b1e3c;
            --text-muted:  #7a587a;
            --border:      #e2d0e2;
            --border-dark: #c9aec9;
            --bg:          #f7f0f7;
            --surface:     #ffffff;
            --off-white:   #faf5fa;

            /* ── Shadows ── */
            --shadow-sm:   0 1px 4px rgba(110,2,111,.07);
            --shadow-md:   0 4px 18px rgba(110,2,111,.10);
            --shadow-lg:   0 10px 36px rgba(110,2,111,.14);

            /* ── Radius ── */
            --radius-sm:   6px;
            --radius-md:   10px;
            --radius-lg:   16px;
            --radius-xl:   22px;

            /* ── Typography ── */
            --font-serif: 'Source Serif 4', Georgia, 'Times New Roman', serif;
            --font-ui:    'Outfit', -apple-system, sans-serif;
            --font-mono:  'JetBrains Mono', 'Courier New', monospace;
        }

        /* ═══════════════════════════════════════════════
           BASE
        ═══════════════════════════════════════════════ */
        html { font-size: 16px; -webkit-text-size-adjust: 100%; }

        body {
            font-family: var(--font-ui);
            background: var(--bg);
            background-image:
                radial-gradient(ellipse 70% 50% at 15% -5%, rgba(110,2,111,.06) 0%, transparent 65%),
                radial-gradient(ellipse 55% 45% at 85% 105%, rgba(110,2,111,.05) 0%, transparent 60%);
            color: var(--text-body);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 28px 16px 48px;
        }

        /* ═══════════════════════════════════════════════
           PORTAL WRAPPER
        ═══════════════════════════════════════════════ */
        .portal-wrap {
            width: 100%;
            max-width: 980px;
            animation: rise .45s cubic-bezier(.22,.61,.36,1) both;
        }

        @keyframes rise {
            from { opacity: 0; transform: translateY(20px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        /* ═══════════════════════════════════════════════
           HEADER
        ═══════════════════════════════════════════════ */
        .portal-header {
            background: linear-gradient(160deg, var(--pu-deeper) 0%, var(--pu-dark) 45%, var(--pu) 100%);
            border-radius: var(--radius-xl) var(--radius-xl) 0 0;
            overflow: hidden;
            position: relative;
        }

        /* Top accent stripe */
        .portal-header::before {
            content: '';
            display: block;
            height: 3px;
            background: linear-gradient(90deg, var(--pu-light) 0%, #e0a020 40%, var(--pu-light) 100%);
        }

        /* Subtle texture */
        .portal-header::after {
            content: '';
            position: absolute;
            inset: 0;
            background-image: repeating-linear-gradient(
                55deg, transparent, transparent 70px,
                rgba(255,255,255,.012) 70px, rgba(255,255,255,.012) 71px
            );
            pointer-events: none;
        }

        .portal-header-inner {
            display: flex;
            align-items: center;
            gap: 18px;
            padding: 26px 36px;
            position: relative;
            z-index: 1;
        }

        /* Emblem / crest circle */
        .portal-emblem {
            flex-shrink: 0;
            width: 60px; height: 60px;
            background: rgba(255,255,255,.08);
            border: 1.5px solid rgba(255,255,255,.22);
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            font-size: 22px;
            color: rgba(255,255,255,.85);
        }

        .portal-header-text { flex: 1; min-width: 0; }

        .portal-header-text h1 {
            font-family: var(--font-serif);
            font-size: 20px;
            font-weight: 600;
            color: #fff;
            letter-spacing: -0.2px;
            line-height: 1.25;
            margin-bottom: 3px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .portal-header-text p {
            font-family: var(--font-ui);
            font-size: 11.5px;
            color: rgba(255,255,255,.48);
            letter-spacing: 0.5px;
            text-transform: uppercase;
            margin: 0;
        }

        /* Right cluster */
        .portal-header-right {
            display: flex;
            flex-direction: column;
            align-items: flex-end;
            gap: 9px;
            flex-shrink: 0;
        }

        .portal-header-badge {
            background: rgba(255,255,255,.1);
            border: 1px solid rgba(255,255,255,.2);
            border-radius: 50px;
            padding: 5px 13px;
            font-family: var(--font-ui);
            font-size: 10.5px;
            font-weight: 600;
            color: rgba(255,255,255,.8);
            letter-spacing: 0.5px;
            white-space: nowrap;
        }

        .portal-user-row {
            display: flex;
            align-items: center;
            gap: 7px;
        }

        .portal-user-avatar {
            width: 26px; height: 26px;
            background: rgba(255,255,255,.12);
            border: 1px solid rgba(255,255,255,.18);
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            color: rgba(255,255,255,.65);
            font-size: 10px;
            flex-shrink: 0;
        }

        .portal-user-name {
            font-family: var(--font-ui);
            font-size: 12px;
            color: rgba(255,255,255,.6);
            white-space: nowrap;
        }

        .portal-user-name strong { color: rgba(255,255,255,.9); font-weight: 600; }

        .portal-logout-btn {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            padding: 5px 12px;
            background: rgba(185,28,28,.14);
            border: 1px solid rgba(185,28,28,.32);
            border-radius: 50px;
            font-family: var(--font-ui);
            font-size: 11px;
            font-weight: 600;
            color: #fca5a5;
            letter-spacing: 0.2px;
            text-decoration: none;
            transition: all .2s;
            white-space: nowrap;
        }

        .portal-logout-btn:hover {
            background: rgba(185,28,28,.28);
            border-color: rgba(185,28,28,.5);
            color: #fecaca;
        }

        .portal-logout-btn i { font-size: 9px; }

        /* ═══════════════════════════════════════════════
           PROGRESS TRACKER
        ═══════════════════════════════════════════════ */
        .progress-track {
            background: var(--pu-deeper);
            padding: 16px 36px;
            display: flex;
            align-items: center;
            border-top: 1px solid rgba(255,255,255,.06);
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
            width: 28px; height: 28px;
            border-radius: 50%;
            border: 2px solid rgba(255,255,255,.14);
            background: transparent;
            display: flex; align-items: center; justify-content: center;
            font-family: var(--font-ui);
            font-size: 10.5px;
            font-weight: 700;
            color: rgba(255,255,255,.28);
            transition: all .3s;
            flex-shrink: 0;
        }

        .track-step.completed .track-num {
            background: var(--green);
            border-color: var(--green);
            color: #fff;
        }

        .track-step.active .track-num {
            background: #e0a020;
            border-color: #e0a020;
            color: var(--pu-deeper);
            box-shadow: 0 0 0 4px rgba(224,160,32,.22);
        }

        .track-info { display: flex; flex-direction: column; min-width: 0; }

        .track-label {
            font-family: var(--font-ui);
            font-size: 10px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: rgba(255,255,255,.28);
            transition: color .3s;
            line-height: 1.3;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .track-step.active    .track-label { color: #e0c060; }
        .track-step.completed .track-label { color: rgba(255,255,255,.6); }

        .track-sublabel {
            font-family: var(--font-ui);
            font-size: 9.5px;
            color: rgba(255,255,255,.18);
            margin-top: 1px;
            transition: color .3s;
            white-space: nowrap;
        }

        .track-step.active    .track-sublabel { color: rgba(255,255,255,.42); }
        .track-step.completed .track-sublabel { color: rgba(255,255,255,.32); }

        .track-connector {
            flex: 0 0 10px;
            height: 1px;
            background: rgba(255,255,255,.08);
            margin: 0 4px;
        }

        .track-connector.done { background: var(--green); opacity: .7; }

        /* ═══════════════════════════════════════════════
           BODY / CONTENT AREA
        ═══════════════════════════════════════════════ */
        .portal-body {
            background: var(--surface);
            border-left: 1px solid var(--border);
            border-right: 1px solid var(--border);
            padding: 36px 40px;
        }

        /* ═══════════════════════════════════════════════
           FLASH MESSAGES
        ═══════════════════════════════════════════════ */
        .flash-messages { margin-bottom: 24px; }

        .flash-msg {
            display: flex;
            align-items: flex-start;
            gap: 12px;
            padding: 13px 17px;
            border-radius: var(--radius-md);
            margin-bottom: 8px;
            font-family: var(--font-ui);
            font-size: 13.5px;
            border: 1px solid transparent;
            animation: popIn .28s ease;
            line-height: 1.5;
        }

        @keyframes popIn {
            from { opacity: 0; transform: translateY(-6px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        .flash-msg.success { background: var(--green-bg);  border-color: var(--green-border); color: #145f45; }
        .flash-msg.error   { background: var(--red-bg);    border-color: var(--red-border);   color: #7f1d1d; }
        .flash-msg.info    { background: var(--blue-bg);   border-color: var(--blue-border);  color: #1e3a8a; }

        .flash-icon { font-size: 15px; margin-top: 1px; flex-shrink: 0; }
        .flash-msg.success .flash-icon { color: var(--green); }
        .flash-msg.error   .flash-icon { color: var(--red); }
        .flash-msg.info    .flash-icon { color: var(--blue); }

        /* ═══════════════════════════════════════════════
           FORM SECTIONS
        ═══════════════════════════════════════════════ */
        .form-section {
            border: 1px solid var(--border);
            border-radius: var(--radius-lg);
            margin-bottom: 26px;
            overflow: hidden;
        }

        .form-section-head {
            display: flex;
            align-items: center;
            gap: 11px;
            padding: 16px 22px;
            background: var(--pu-pale);
            border-bottom: 1px solid var(--border);
        }

        .section-icon {
            width: 34px; height: 34px;
            background: var(--pu);
            border-radius: var(--radius-sm);
            display: flex; align-items: center; justify-content: center;
            font-size: 13px;
            color: #fff;
            flex-shrink: 0;
        }

        .form-section-head h3 {
            font-family: var(--font-serif);
            font-size: 16px;
            font-weight: 600;
            color: var(--pu-dark);
            margin: 0;
        }

        .form-section-head span {
            font-family: var(--font-ui);
            font-size: 11.5px;
            color: var(--text-muted);
            margin-left: auto;
        }

        .form-section-body { padding: 26px 22px; }

        .form-group { margin-bottom: 20px; }
        .form-group:last-child { margin-bottom: 0; }

        .form-label {
            display: block;
            font-family: var(--font-ui);
            font-size: 12.5px;
            font-weight: 600;
            color: var(--pu-dark);
            margin-bottom: 6px;
            letter-spacing: 0.2px;
        }

        .form-label .required { color: var(--red); margin-left: 2px; }

        .form-control,
        .form-select {
            border: 1.5px solid var(--border-dark);
            border-radius: var(--radius-md);
            padding: 10px 13px;
            font-family: var(--font-ui);
            font-size: 14px;
            color: var(--text);
            background: var(--surface);
            transition: border-color .2s, box-shadow .2s;
            width: 100%;
            line-height: 1.5;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: var(--pu);
            box-shadow: 0 0 0 3px rgba(110,2,111,.12);
            outline: none;
        }

        .form-control.is-invalid { border-color: var(--red); }
        .form-control.is-valid   { border-color: var(--green); }

        .form-hint {
            font-family: var(--font-ui);
            font-size: 11.5px;
            color: var(--text-muted);
            margin-top: 4px;
            line-height: 1.5;
        }

        /* ═══════════════════════════════════════════════
           BUTTONS
        ═══════════════════════════════════════════════ */
        .btn {
            font-family: var(--font-ui);
            font-size: 13.5px;
            font-weight: 600;
            padding: 10px 24px;
            border-radius: var(--radius-md);
            border: none;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 7px;
            transition: all .2s;
            letter-spacing: 0.1px;
            text-decoration: none;
            line-height: 1.4;
        }

        .btn-primary {
            background: var(--pu);
            color: #fff;
            box-shadow: 0 3px 12px rgba(110,2,111,.28);
        }
        .btn-primary:hover {
            background: var(--pu-dark);
            transform: translateY(-1px);
            box-shadow: 0 5px 18px rgba(110,2,111,.36);
            color: #fff;
        }

        .btn-gold {
            background: var(--gold);
            color: #fff;
            box-shadow: 0 3px 12px rgba(200,134,10,.28);
        }
        .btn-gold:hover {
            background: #a86e08;
            transform: translateY(-1px);
            box-shadow: 0 5px 18px rgba(200,134,10,.38);
            color: #fff;
        }

        .btn-success {
            background: var(--green);
            color: #fff;
            box-shadow: 0 3px 12px rgba(26,107,69,.22);
        }
        .btn-success:hover {
            background: #14563a;
            transform: translateY(-1px);
            color: #fff;
        }

        .btn-outline {
            background: transparent;
            color: var(--pu);
            border: 1.5px solid var(--border-dark);
        }
        .btn-outline:hover {
            background: var(--pu-pale);
            border-color: var(--pu);
        }

        .btn-lg { padding: 13px 32px; font-size: 14.5px; }
        .btn-sm { padding: 7px 16px; font-size: 12.5px; }

        /* ═══════════════════════════════════════════════
           PAYMENT CARD
        ═══════════════════════════════════════════════ */
        .payment-card {
            border: 1px solid var(--border);
            border-radius: var(--radius-lg);
            overflow: hidden;
        }

        .payment-card-head {
            background: linear-gradient(135deg, var(--pu-deeper), var(--pu));
            padding: 26px 32px;
            text-align: center;
            position: relative;
        }

        .payment-card-head::after {
            content: '';
            position: absolute;
            bottom: 0; left: 0; right: 0;
            height: 2px;
            background: linear-gradient(90deg, transparent, var(--gold-light), transparent);
        }

        .payment-label {
            font-family: var(--font-ui);
            font-size: 10.5px;
            font-weight: 600;
            letter-spacing: 1.5px;
            text-transform: uppercase;
            color: rgba(255,255,255,.48);
            margin-bottom: 10px;
        }

        .payment-amount {
            font-family: var(--font-serif);
            font-size: 52px;
            font-weight: 700;
            color: var(--gold-light);
            line-height: 1;
        }

        .payment-amount sup {
            font-size: 22px;
            vertical-align: super;
            opacity: 0.7;
        }

        .payment-card-body { padding: 26px 32px; }

        .payment-rrr-box {
            background: var(--off-white);
            border: 1.5px dashed var(--border-dark);
            border-radius: var(--radius-md);
            padding: 15px 20px;
            font-family: var(--font-mono);
            font-size: 19px;
            font-weight: 500;
            color: var(--pu-dark);
            text-align: center;
            letter-spacing: 3px;
            margin-bottom: 18px;
        }

        /* ═══════════════════════════════════════════════
           EXAM SLIP (if rendered inside layout)
        ═══════════════════════════════════════════════ */
        .exam-slip {
            border: 1.5px solid var(--pu);
            border-radius: var(--radius-lg);
            overflow: hidden;
        }

        .exam-slip-head {
            background: linear-gradient(135deg, var(--pu-deeper), var(--pu));
            padding: 24px 30px;
            text-align: center;
        }

        .exam-slip-head h2 {
            font-family: var(--font-serif);
            color: #fff;
            font-size: 20px;
            margin-bottom: 4px;
        }

        .exam-slip-head p {
            font-family: var(--font-ui);
            font-size: 12px;
            color: rgba(255,255,255,.52);
            margin: 0;
        }

        .exam-slip-body { padding: 26px 30px; }

        .slip-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 12px;
        }

        .slip-item {
            background: var(--pu-pale);
            border: 1px solid var(--border);
            border-radius: var(--radius-md);
            padding: 13px 15px;
        }

        .slip-item .lbl {
            font-family: var(--font-ui);
            font-size: 9.5px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: var(--pu);
            margin-bottom: 4px;
        }

        .slip-item .val {
            font-family: var(--font-ui);
            font-size: 14px;
            font-weight: 500;
            color: var(--text);
        }

        /* ═══════════════════════════════════════════════
           DOCUMENT PREVIEW
        ═══════════════════════════════════════════════ */
        .doc-preview {
            position: relative;
            display: inline-block;
            margin: 7px;
        }

        .doc-preview img {
            width: 128px; height: 128px;
            object-fit: cover;
            border: 1.5px solid var(--border-dark);
            border-radius: var(--radius-md);
            display: block;
        }

        .doc-preview:hover img { border-color: var(--pu); }

        .doc-remove {
            position: absolute;
            top: -8px; right: -8px;
            width: 24px; height: 24px;
            background: var(--red);
            color: #fff;
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            cursor: pointer;
            font-size: 10px;
            border: 2px solid #fff;
            transition: transform .2s;
        }

        .doc-remove:hover { transform: scale(1.15); }

        /* ═══════════════════════════════════════════════
           DIVIDER
        ═══════════════════════════════════════════════ */
        .section-divider {
            border: none;
            border-top: 1px solid var(--border);
            margin: 28px 0;
        }

        /* ═══════════════════════════════════════════════
           FOOTER
        ═══════════════════════════════════════════════ */
        .portal-footer {
            background: var(--pu-deeper);
            border-radius: 0 0 var(--radius-xl) var(--radius-xl);
            padding: 20px 36px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 10px;
            border-top: 1px solid rgba(255,255,255,.06);
        }

        .portal-footer p {
            margin: 0;
            font-family: var(--font-ui);
            font-size: 12px;
            color: rgba(255,255,255,.36);
        }

        .footer-contacts {
            display: flex;
            gap: 22px;
            flex-wrap: wrap;
        }

        .footer-contact-item {
            display: flex;
            align-items: center;
            gap: 6px;
            font-family: var(--font-ui);
            font-size: 12px;
            color: rgba(255,255,255,.5);
            text-decoration: none;
            transition: color .2s;
        }

        .footer-contact-item:hover { color: rgba(255,255,255,.85); }
        .footer-contact-item i { color: var(--pu-light); font-size: 11px; }

        /* ═══════════════════════════════════════════════
           UTILITIES
        ═══════════════════════════════════════════════ */
        .text-purple { color: var(--pu) !important; }
        .text-gold   { color: var(--gold) !important; }
        .text-green  { color: var(--green) !important; }

        /* Payment button area */
        #paymentButtonArea .btn-amber {
            display: block;
            width: 100%;
            padding: 0.95rem 1.5rem;
            background: var(--gold);
            color: #fff;
            border: none;
            border-radius: var(--radius-md);
            font-family: var(--font-ui);
            font-weight: 600;
            font-size: 14px;
            text-align: center;
            text-decoration: none;
            transition: all .22s;
            cursor: pointer;
        }

        #paymentButtonArea .btn-amber:hover {
            background: #a86e08;
            transform: translateY(-1px);
            box-shadow: 0 4px 14px rgba(200,134,10,.32);
        }

        /* ═══════════════════════════════════════════════
           RESPONSIVE
        ═══════════════════════════════════════════════ */
        @media (max-width: 768px) {
            body { padding: 12px 10px 32px; }

            .portal-header-inner {
                flex-wrap: wrap;
                padding: 18px 20px;
                gap: 12px;
            }

            .portal-header-text h1 {
                font-size: 16px;
                white-space: normal;
            }

            .portal-header-right {
                width: 100%;
                flex-direction: row;
                align-items: center;
                justify-content: space-between;
            }

            .progress-track {
                padding: 13px 16px;
                flex-wrap: wrap;
                gap: 6px;
            }

            .track-connector { display: none; }

            .track-step {
                flex: 0 0 calc(50% - 3px);
                background: rgba(255,255,255,.04);
                border: 1px solid rgba(255,255,255,.07);
                border-radius: var(--radius-md);
                padding: 9px 11px;
            }

            .track-step:last-child { flex: 0 0 100%; }

            .portal-body { padding: 22px 18px; }

            .portal-footer {
                flex-direction: column;
                align-items: flex-start;
                padding: 18px 22px;
            }

            .slip-grid { grid-template-columns: 1fr; }
            .payment-amount { font-size: 40px; }
        }

        @media (max-width: 480px) {
            .portal-header-text h1 { font-size: 14.5px; }
            .portal-emblem { width: 46px; height: 46px; font-size: 17px; }
            .track-step { flex: 0 0 100%; }
            .portal-body { padding: 18px 14px; }
        }

        @media (max-width: 360px) {
            body { padding: 8px 8px 24px; }
            .portal-body { padding: 16px 12px; }
        }
    </style>
</head>
<body>
<div class="portal-wrap">

    <!-- ═══════════ HEADER ═══════════ -->
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
                    <i class="fas fa-shield-alt" style="margin-right:4px;font-size:9px;"></i>
                    Secure Portal
                </div>

                <?php
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
            $showSteps   = false;
            $currentStep = 1;

            if (isset($application) && is_array($application)) {
                $showSteps = true;

                if (!empty($application['application_step'])) {
                    $currentStep = (int)$application['application_step'];
                } else {
                    $currentStep = 1;
                    if (!empty($application['jamb_number'])) $currentStep = 2;
                    if (!empty($application['date_of_birth']) && !empty($application['phone']) && !empty($application['address'])) $currentStep = 3;
                    $hasPaid = isset($payment_status) && is_array($payment_status) && ($payment_status['status'] === 'success');
                    if ($hasPaid) $currentStep = 4;
                    if (!empty($application['exam_slip_generated'])) $currentStep = 5;
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
                                <i class="fas fa-check" style="font-size:8px;"></i>
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


    <!-- ═══════════ BODY ═══════════ -->
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


    <!-- ═══════════ FOOTER ═══════════ -->
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
    setTimeout(function () {
        document.querySelectorAll('.flash-msg').forEach(function (el) {
            el.style.transition = 'opacity 0.4s';
            el.style.opacity    = '0';
            setTimeout(function () { el.remove(); }, 400);
        });
    }, 5500);

    function confirmAction(msg) { return confirm(msg || 'Are you sure?'); }

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