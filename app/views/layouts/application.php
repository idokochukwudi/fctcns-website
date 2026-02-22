<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0, viewport-fit=cover">
    
    <!-- ========================================================= -->
    <!-- 1. Add security meta tags in the head -->
    <!-- ========================================================= -->
    <meta http-equiv="X-Content-Type-Options" content="nosniff">
    <meta http-equiv="X-Frame-Options" content="DENY">
    <meta http-equiv="X-XSS-Protection" content="1; mode=block">
    <meta name="referrer" content="strict-origin-when-cross-origin">
    
    <!-- ========================================================= -->
    <!-- 2. Add CSRF meta tag for JavaScript -->
    <!-- ========================================================= -->
    <meta name="csrf-token" content="<?php echo $csrf_token ?? ''; ?>">
    
    <title><?php echo $pageTitle ?? 'Application Portal - FCT College of Nursing Sciences'; ?></title>
    <meta name="description" content="<?php echo $pageDescription ?? 'Apply for admission into ND/HND Nursing programme'; ?>">

    <!-- Preconnect for performance -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    
    <!-- ========================================================= -->
    <!-- 3. Add SRI hashes to external scripts/styles -->
    <!-- ========================================================= -->
    <!-- Source Serif 4: highly readable, gentle on the eyes; Outfit for UI labels -->
    <link href="https://fonts.googleapis.com/css2?family=Source+Serif+4:wght@300;400;500;600;700&family=Outfit:wght@300;400;500;600;700&family=JetBrains+Mono:wght@400;500&display=swap" 
          rel="stylesheet"
          integrity="sha384-0pCryB3hBqYHZO9dKsIIzN8wH+Z4k5P+GZ8TlqM9m8A3TlPI9c7JZ6nG+K/t9fb"
          crossorigin="anonymous">
    
    <link rel="stylesheet" 
          href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"
          integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw=="
          crossorigin="anonymous" 
          referrerpolicy="no-referrer">

    <style nonce="<?php echo $csp_nonce ?? ''; ?>">
        /* ═══════════════════════════════════════════════
           RESET & ROOT - SOPHISTICATED GRAY PALETTE
        ═══════════════════════════════════════════════ */
        *, *::before, *::after { margin: 0; padding: 0; box-sizing: border-box; }

        :root {
            /* ── Primary brand color (subtle) ── */
            --primary-50:   #f7f7f8;
            --primary-100:  #e8e8ed;
            --primary-200:  #d1d1db;
            --primary-300:  #b3b3c2;
            --primary-400:  #9393a8;
            --primary-500:  #74748c;
            --primary-600:  #5c5c70;
            --primary-700:  #454554;
            --primary-800:  #2f2f38;
            --primary-900:  #1a1a1f;
            
            /* ── Gray palette - sophisticated neutrals ── */
            --gray-50:      #fafafa;
            --gray-100:     #f5f5f5;
            --gray-200:     #e9e9e9;
            --gray-300:     #d4d4d4;
            --gray-400:     #a3a3a3;
            --gray-500:     #737373;
            --gray-600:     #525252;
            --gray-700:     #404040;
            --gray-800:     #262626;
            --gray-900:     #171717;

            /* ── Accent (soft blue-gray for highlights) ── */
            --accent-50:    #f0f4fa;
            --accent-100:   #d9e2f0;
            --accent-200:   #b7c8e0;
            --accent-300:   #94aed0;
            --accent-400:   #6e8fb8;
            --accent-500:   #4f719b;
            --accent-600:   #3d577a;
            --accent-700:   #2d405a;
            --accent-800:   #1e2b3c;
            --accent-900:   #0f1722;

            /* ── Status colours (muted) ── */
            --green-50:     #edf7f2;
            --green-100:    #d1ebe0;
            --green-200:    #a3d7c1;
            --green-300:    #75c3a2;
            --green-400:    #47af83;
            --green-500:    #2e8b64;
            --green-600:    #236f4f;
            --green-700:    #1a533b;
            --green-800:    #123a29;
            --green-900:    #0a2218;

            --red-50:       #fef2f2;
            --red-100:      #fee2e2;
            --red-200:      #fecaca;
            --red-300:      #fca5a5;
            --red-400:      #f87171;
            --red-500:      #ef4444;
            --red-600:      #dc2626;
            --red-700:      #b91c1c;
            --red-800:      #991b1b;
            --red-900:      #7f1d1d;

            --blue-50:      #eff6ff;
            --blue-100:     #dbeafe;
            --blue-200:     #bfdbfe;
            --blue-300:     #93c5fd;
            --blue-400:     #60a5fa;
            --blue-500:     #3b82f6;
            --blue-600:     #2563eb;
            --blue-700:     #1d4ed8;
            --blue-800:     #1e40af;
            --blue-900:     #1e3a8a;

            /* ── Gold accent (kept for subtle highlights) ── */
            --gold-50:      #fefce8;
            --gold-100:     #fef9c3;
            --gold-200:     #fef08a;
            --gold-300:     #fde047;
            --gold-400:     #facc15;
            --gold-500:     #eab308;
            --gold-600:     #ca8a04;
            --gold-700:     #a16207;
            --gold-800:     #854d0e;
            --gold-900:     #713f12;

            /* ── Cloudit Technologies brand colors ── */
            --cloudit-primary: #7C75E0;
            --cloudit-secondary: #5D54C6;
            --cloudit-glow: rgba(124, 117, 224, 0.5);
            --cloudit-light: #9B96FA;

            /* ── Neutrals for text and backgrounds ── */
            --text-primary:    var(--gray-900);
            --text-secondary:  var(--gray-700);
            --text-muted:      var(--gray-500);
            --text-inverse:    #ffffff;
            
            --bg-body:         var(--gray-50);
            --bg-surface:      #ffffff;
            --bg-subtle:       var(--gray-100);
            --bg-muted:        var(--gray-200);
            
            --border-light:    var(--gray-200);
            --border:          var(--gray-300);
            --border-dark:     var(--gray-400);

            /* ── Shadows (soft) ── */
            --shadow-sm:   0 1px 2px 0 rgba(0, 0, 0, 0.03);
            --shadow-md:   0 4px 6px -1px rgba(0, 0, 0, 0.03), 0 2px 4px -1px rgba(0, 0, 0, 0.02);
            --shadow-lg:   0 10px 15px -3px rgba(0, 0, 0, 0.03), 0 4px 6px -4px rgba(0, 0, 0, 0.02);
            --shadow-xl:   0 20px 25px -5px rgba(0, 0, 0, 0.03), 0 10px 10px -5px rgba(0, 0, 0, 0.02);

            /* ── Radius ── */
            --radius-sm:   4px;
            --radius-md:   6px;
            --radius-lg:   8px;
            --radius-xl:   12px;
            --radius-2xl:  16px;
            --radius-full: 9999px;

            /* ── Typography ── */
            --font-serif: 'Source Serif 4', Georgia, 'Times New Roman', serif;
            --font-ui:    'Outfit', -apple-system, sans-serif;
            --font-mono:  'JetBrains Mono', 'Courier New', monospace;
        }

        /* ═══════════════════════════════════════════════
           BASE
        ═══════════════════════════════════════════════ */
        html { 
            font-size: 16px; 
            -webkit-text-size-adjust: 100%; 
        }

        body {
            font-family: var(--font-ui);
            background: var(--bg-body);
            color: var(--text-secondary);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 28px 16px 48px;
            line-height: 1.5;
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
           HEADER - GRAY THEME
        ═══════════════════════════════════════════════ */
        .portal-header {
            background: linear-gradient(160deg, var(--gray-900) 0%, var(--gray-800) 45%, var(--gray-700) 100%);
            border-radius: var(--radius-xl) var(--radius-xl) 0 0;
            overflow: hidden;
            position: relative;
        }

        /* Top accent stripe - subtle gold */
        .portal-header::before {
            content: '';
            display: block;
            height: 3px;
            background: linear-gradient(90deg, var(--gold-400) 0%, var(--gold-300) 40%, var(--gold-400) 100%);
        }

        /* Subtle texture */
        .portal-header::after {
            content: '';
            position: absolute;
            inset: 0;
            background-image: repeating-linear-gradient(
                55deg, transparent, transparent 70px,
                rgba(255,255,255,.03) 70px, rgba(255,255,255,.03) 71px
            );
            pointer-events: none;
        }

        .portal-header-inner {
            display: flex;
            align-items: center;
            gap: 20px;
            padding: 22px 36px;
            position: relative;
            z-index: 1;
        }

        /* Logo container with white background for visibility */
        .portal-logo {
            flex-shrink: 0;
            width: 70px;
            height: 70px;
            background: white;
            border-radius: var(--radius-md);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 8px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }

        .logo-image {
            width: 100%;
            height: 100%;
            object-fit: contain;
        }

        /* Fallback icon styling */
        .portal-logo .fallback-icon {
            font-size: 28px;
            color: var(--gray-700);
        }

        .portal-header-text { 
            flex: 1; 
            min-width: 0; 
        }

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
            color: rgba(255,255,255,.55);
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
            background: rgba(255,255,255,.08);
            border: 1px solid rgba(255,255,255,.12);
            border-radius: var(--radius-full);
            padding: 5px 13px;
            font-family: var(--font-ui);
            font-size: 10.5px;
            font-weight: 600;
            color: rgba(255,255,255,.75);
            letter-spacing: 0.5px;
            white-space: nowrap;
            display: inline-flex;
            align-items: center;
            gap: 4px;
        }

        .portal-header-badge i {
            color: var(--gold-400);
            font-size: 9px;
        }

        .portal-user-row {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .portal-user-avatar {
            width: 28px; 
            height: 28px;
            background: rgba(255,255,255,.1);
            border: 1px solid rgba(255,255,255,.15);
            border-radius: var(--radius-full);
            display: flex; 
            align-items: center; 
            justify-content: center;
            color: rgba(255,255,255,.7);
            font-size: 11px;
            flex-shrink: 0;
        }

        .portal-user-name {
            font-family: var(--font-ui);
            font-size: 12px;
            color: rgba(255,255,255,.65);
            white-space: nowrap;
        }

        .portal-user-name strong { 
            color: #fff; 
            font-weight: 600; 
        }

        .portal-logout-btn {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            padding: 5px 12px;
            background: rgba(185,28,28,.15);
            border: 1px solid rgba(185,28,28,.25);
            border-radius: var(--radius-full);
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
            background: rgba(185,28,28,.25);
            border-color: rgba(185,28,28,.4);
            color: #fecaca;
        }

        .portal-logout-btn i { font-size: 9px; }

        /* ═══════════════════════════════════════════════
           PROGRESS TRACKER - GRAY THEME WITH FIX
        ═══════════════════════════════════════════════ */
        .progress-track {
            background: var(--gray-900);
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
            width: 28px; 
            height: 28px;
            border-radius: var(--radius-full);
            border: 2px solid rgba(255,255,255,.15);
            background: transparent;
            display: flex; 
            align-items: center; 
            justify-content: center;
            font-family: var(--font-ui);
            font-size: 10.5px;
            font-weight: 700;
            color: rgba(255,255,255,.3);
            transition: all .3s;
            flex-shrink: 0;
        }

        .track-step.completed .track-num {
            background: var(--green-600);
            border-color: var(--green-500);
            color: #fff;
        }

        .track-step.active .track-num {
            background: var(--gold-600);
            border-color: var(--gold-500);
            color: #fff;
            box-shadow: 0 0 0 4px rgba(202,138,4,.2);
        }

        .track-info { display: flex; flex-direction: column; min-width: 0; }

        .track-label {
            font-family: var(--font-ui);
            font-size: 10px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: rgba(255,255,255,.3);
            transition: color .3s;
            line-height: 1.3;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .track-step.active    .track-label { color: var(--gold-300); }
        .track-step.completed .track-label { color: rgba(255,255,255,.6); }

        .track-sublabel {
            font-family: var(--font-ui);
            font-size: 9.5px;
            color: rgba(255,255,255,.2);
            margin-top: 1px;
            transition: color .3s;
            white-space: nowrap;
        }

        .track-step.active    .track-sublabel { color: rgba(255,255,255,.45); }
        .track-step.completed .track-sublabel { color: rgba(255,255,255,.35); }

        .track-connector {
            flex: 0 0 10px;
            height: 1px;
            background: rgba(255,255,255,.08);
            margin: 0 4px;
        }

        .track-connector.done { background: var(--green-600); opacity: .5; }

        /* ═══════════════════════════════════════════════
           BODY / CONTENT AREA
        ═══════════════════════════════════════════════ */
        .portal-body {
            background: var(--bg-surface);
            border-left: 1px solid var(--border);
            border-right: 1px solid var(--border);
            padding: 36px 40px;
        }

        /* ═══════════════════════════════════════════════
           FLASH MESSAGES - MUTED COLORS
        ═══════════════════════════════════════════════ */
        .flash-messages { margin-bottom: 24px; }

        .flash-msg {
            display: flex;
            align-items: flex-start;
            gap: 12px;
            padding: 14px 18px;
            border-radius: var(--radius-lg);
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

        .flash-msg.success { 
            background: var(--green-50);  
            border-color: var(--green-200); 
            color: var(--green-800); 
        }
        .flash-msg.error   { 
            background: var(--red-50);    
            border-color: var(--red-200);   
            color: var(--red-800); 
        }
        .flash-msg.info    { 
            background: var(--blue-50);   
            border-color: var(--blue-200);  
            color: var(--blue-800); 
        }

        .flash-icon { font-size: 15px; margin-top: 1px; flex-shrink: 0; }
        .flash-msg.success .flash-icon { color: var(--green-600); }
        .flash-msg.error   .flash-icon { color: var(--red-600); }
        .flash-msg.info    .flash-icon { color: var(--blue-600); }

        /* ═══════════════════════════════════════════════
           FORM SECTIONS - GRAY THEME
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
            background: var(--gray-100);
            border-bottom: 1px solid var(--border);
        }

        .section-icon {
            width: 34px; 
            height: 34px;
            background: var(--gray-700);
            border-radius: var(--radius-sm);
            display: flex; 
            align-items: center; 
            justify-content: center;
            font-size: 13px;
            color: #fff;
            flex-shrink: 0;
        }

        .form-section-head h3 {
            font-family: var(--font-serif);
            font-size: 16px;
            font-weight: 600;
            color: var(--gray-800);
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
            color: var(--gray-700);
            margin-bottom: 6px;
            letter-spacing: 0.2px;
        }

        .form-label .required { color: var(--red-600); margin-left: 2px; }

        .form-control,
        .form-select {
            border: 1.5px solid var(--border);
            border-radius: var(--radius-md);
            padding: 10px 13px;
            font-family: var(--font-ui);
            font-size: 14px;
            color: var(--text-primary);
            background: var(--bg-surface);
            transition: border-color .2s, box-shadow .2s;
            width: 100%;
            line-height: 1.5;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: var(--accent-500);
            box-shadow: 0 0 0 3px var(--accent-100);
            outline: none;
        }

        .form-control.is-invalid { border-color: var(--red-500); }
        .form-control.is-valid   { border-color: var(--green-500); }

        .form-hint {
            font-family: var(--font-ui);
            font-size: 11.5px;
            color: var(--text-muted);
            margin-top: 4px;
            line-height: 1.5;
        }

        /* ═══════════════════════════════════════════════
           BUTTONS - GRAY THEME
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
            background: var(--gray-800);
            color: #fff;
            box-shadow: var(--shadow-sm);
        }
        .btn-primary:hover {
            background: var(--gray-900);
            transform: translateY(-1px);
            box-shadow: var(--shadow-md);
        }

        .btn-gold {
            background: var(--gold-600);
            color: #fff;
            box-shadow: var(--shadow-sm);
        }
        .btn-gold:hover {
            background: var(--gold-700);
            transform: translateY(-1px);
            box-shadow: var(--shadow-md);
        }

        .btn-success {
            background: var(--green-600);
            color: #fff;
            box-shadow: var(--shadow-sm);
        }
        .btn-success:hover {
            background: var(--green-700);
            transform: translateY(-1px);
        }

        .btn-outline {
            background: transparent;
            color: var(--gray-700);
            border: 1.5px solid var(--border);
        }
        .btn-outline:hover {
            background: var(--gray-100);
            border-color: var(--gray-500);
            color: var(--gray-900);
        }

        .btn-lg { padding: 13px 32px; font-size: 14.5px; }
        .btn-sm { padding: 7px 16px; font-size: 12.5px; }

        /* ═══════════════════════════════════════════════
           PAYMENT CARD - GRAY THEME
        ═══════════════════════════════════════════════ */
        .payment-card {
            border: 1px solid var(--border);
            border-radius: var(--radius-lg);
            overflow: hidden;
        }

        .payment-card-head {
            background: linear-gradient(135deg, var(--gray-900), var(--gray-800));
            padding: 26px 32px;
            text-align: center;
            position: relative;
        }

        .payment-card-head::after {
            content: '';
            position: absolute;
            bottom: 0; left: 0; right: 0;
            height: 2px;
            background: linear-gradient(90deg, transparent, var(--gold-400), transparent);
        }

        .payment-label {
            font-family: var(--font-ui);
            font-size: 10.5px;
            font-weight: 600;
            letter-spacing: 1.5px;
            text-transform: uppercase;
            color: rgba(255,255,255,.5);
            margin-bottom: 10px;
        }

        .payment-amount {
            font-family: var(--font-serif);
            font-size: 52px;
            font-weight: 700;
            color: var(--gold-300);
            line-height: 1;
        }

        .payment-amount sup {
            font-size: 22px;
            vertical-align: super;
            opacity: 0.7;
        }

        .payment-card-body { padding: 26px 32px; }

        .payment-rrr-box {
            background: var(--gray-100);
            border: 1.5px dashed var(--border);
            border-radius: var(--radius-md);
            padding: 15px 20px;
            font-family: var(--font-mono);
            font-size: 19px;
            font-weight: 500;
            color: var(--gray-800);
            text-align: center;
            letter-spacing: 3px;
            margin-bottom: 18px;
        }

        /* ═══════════════════════════════════════════════
           EXAM SLIP - GRAY THEME
        ═══════════════════════════════════════════════ */
        .exam-slip {
            border: 1.5px solid var(--gray-400);
            border-radius: var(--radius-lg);
            overflow: hidden;
        }

        .exam-slip-head {
            background: linear-gradient(135deg, var(--gray-800), var(--gray-700));
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
            color: rgba(255,255,255,.55);
            margin: 0;
        }

        .exam-slip-body { padding: 26px 30px; }

        .slip-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 12px;
        }

        .slip-item {
            background: var(--gray-100);
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
            color: var(--gray-600);
            margin-bottom: 4px;
        }

        .slip-item .val {
            font-family: var(--font-ui);
            font-size: 14px;
            font-weight: 500;
            color: var(--text-primary);
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
            width: 128px; 
            height: 128px;
            object-fit: cover;
            border: 1.5px solid var(--border);
            border-radius: var(--radius-md);
            display: block;
        }

        .doc-preview:hover img { border-color: var(--accent-500); }

        .doc-remove {
            position: absolute;
            top: -8px; 
            right: -8px;
            width: 24px; 
            height: 24px;
            background: var(--red-600);
            color: #fff;
            border-radius: var(--radius-full);
            display: flex; 
            align-items: center; 
            justify-content: center;
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
           FOOTER - GRAY THEME WITH CLOUDIT ANIMATION (LOGO REMOVED)
        ═══════════════════════════════════════════════ */
        .portal-footer {
            background: linear-gradient(145deg, var(--gray-900), #0a0a0f);
            border-radius: 0 0 var(--radius-xl) var(--radius-xl);
            padding: 24px 36px;
            border-top: 1px solid rgba(255,255,255,.06);
            position: relative;
            overflow: hidden;
        }

        /* Animated background effect */
        .portal-footer::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -20%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(124, 117, 224, 0.03) 0%, transparent 70%);
            animation: rotate 20s linear infinite;
            pointer-events: none;
        }

        @keyframes rotate {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }

        .footer-main {
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 20px;
            position: relative;
            z-index: 2;
        }

        .portal-footer p {
            margin: 0;
            font-family: var(--font-ui);
            font-size: 12px;
            color: rgba(255,255,255,.5);
        }

        /* Powered by Cloudit Technologies - Animated */
        .powered-by-wrapper {
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .powered-by {
            display: flex;
            align-items: center;
            gap: 6px;
            padding: 6px 16px;
            background: rgba(124, 117, 224, 0.1);
            border-radius: var(--radius-full);
            border: 1px solid rgba(124, 117, 224, 0.2);
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .powered-by::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
            animation: pulseGlow 3s ease infinite;
            pointer-events: none;
        }

        @keyframes pulseGlow {
            0%, 100% { opacity: 0.3; transform: scale(1); }
            50% { opacity: 0.6; transform: scale(1.1); }
        }

        .powered-by:hover {
            background: rgba(124, 117, 224, 0.15);
            border-color: rgba(124, 117, 224, 0.4);
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(124, 117, 224, 0.3);
        }

        .powered-text {
            font-family: var(--font-ui);
            font-size: 11px;
            font-weight: 400;
            color: rgba(255,255,255,.5);
            letter-spacing: 0.5px;
            text-transform: uppercase;
        }

        .cloudit-link {
            display: flex;
            align-items: center;
            gap: 6px;
            text-decoration: none;
            position: relative;
        }

        .cloudit-name {
            font-family: var(--font-ui);
            font-size: 13px;
            font-weight: 700;
            background: linear-gradient(135deg, #fff, var(--cloudit-light));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            letter-spacing: 0.3px;
        }

        .cloud-icon {
            color: var(--cloudit-primary);
            font-size: 14px;
            filter: drop-shadow(0 0 5px var(--cloudit-glow));
            animation: floatCloud 3s ease-in-out infinite;
        }

        .lightning-icon {
            color: var(--gold-400);
            font-size: 10px;
            position: absolute;
            top: -8px;
            right: -8px;
            opacity: 0;
            animation: lightningFlash 2s ease infinite;
        }

        @keyframes floatCloud {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-3px); }
        }

        @keyframes lightningFlash {
            0%, 90%, 100% { opacity: 0; transform: scale(0.5); }
            92%, 98% { opacity: 1; transform: scale(1.2); }
            94%, 96% { opacity: 0.8; transform: scale(1); }
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
            color: rgba(255,255,255,.55);
            text-decoration: none;
            transition: color .2s;
        }

        .footer-contact-item:hover { 
            color: rgba(255,255,255,.9); 
        }
        .footer-contact-item i { 
            color: var(--gold-400); 
            font-size: 11px; 
            transition: transform 0.2s ease;
        }

        .footer-contact-item:hover i {
            transform: scale(1.2);
        }

        /* ═══════════════════════════════════════════════
           UTILITIES
        ═══════════════════════════════════════════════ */
        .text-primary { color: var(--gray-800) !important; }
        .text-gold    { color: var(--gold-600) !important; }
        .text-green   { color: var(--green-600) !important; }

        /* Payment button area */
        #paymentButtonArea .btn-amber {
            display: block;
            width: 100%;
            padding: 0.95rem 1.5rem;
            background: var(--gold-600);
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
            background: var(--gold-700);
            transform: translateY(-1px);
            box-shadow: var(--shadow-md);
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

            .footer-main {
                flex-direction: column;
                align-items: center;
                text-align: center;
                gap: 15px;
            }

            .footer-contacts {
                justify-content: center;
            }

            .powered-by-wrapper {
                width: 100%;
            }

            .portal-footer {
                padding: 20px 22px;
            }

            .slip-grid { grid-template-columns: 1fr; }
            .payment-amount { font-size: 40px; }
        }

        @media (max-width: 480px) {
            .portal-header-text h1 { font-size: 14.5px; }
            .portal-logo { width: 56px; height: 56px; }
            .track-step { flex: 0 0 100%; }
            .portal-body { padding: 18px 14px; }
            
            .footer-contacts {
                flex-direction: column;
                gap: 10px;
            }
            
            .powered-by {
                padding: 6px 12px;
            }
            
            .cloudit-name {
                font-size: 12px;
            }
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

            <!-- Logo with white background for visibility -->
            <div class="portal-logo">
                <img src="/assets/images/logo/logo.png" 
                     alt="FCT College of Nursing Sciences" 
                     class="logo-image"
                     onerror="this.onerror=null; this.src='/assets/images/logo/logo-footer.png'; this.onerror=function(){ this.style.display='none'; this.parentNode.innerHTML+='<i class=\'fas fa-star-of-life fallback-icon\'></i>'; }">
            </div>

            <div class="portal-header-text">
                <h1>FCT College of Nursing Sciences</h1>
                <p>Admissions Application Portal &mdash; 2025 / 2026 Session</p>
            </div>

            <div class="portal-header-right">
                <div class="portal-header-badge">
                    <i class="fas fa-shield-alt"></i>
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
                        <strong><?php echo htmlspecialchars($applicantDisplayName, ENT_QUOTES, 'UTF-8'); ?></strong>
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
                    
                    // FIX: If application_step is 4 AND exam slip exists, show step 5
                    if ($currentStep == 4 && isset($has_exam_slip) && $has_exam_slip) {
                        $currentStep = 5;
                    }
                } else {
                    // Fallback logic if application_step is not set
                    $currentStep = 1;
                    if (!empty($application['jamb_number'])) $currentStep = 2;
                    if (!empty($application['date_of_birth']) && !empty($application['phone']) && !empty($application['address'])) $currentStep = 3;
                    
                    // Check payment status
                    $hasPaid = false;
                    if (isset($payment_status) && is_array($payment_status) && ($payment_status['status'] === 'success')) {
                        $hasPaid = true;
                    }
                    
                    if ($hasPaid) {
                        $currentStep = 4;
                        // FIX: Check if exam slip exists
                        if (isset($has_exam_slip) && $has_exam_slip) {
                            $currentStep = 5;
                        }
                    }
                    
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
                            <span class="track-label"><?php echo htmlspecialchars($step['label'], ENT_QUOTES, 'UTF-8'); ?></span>
                            <span class="track-sublabel"><?php echo htmlspecialchars($step['sub'], ENT_QUOTES, 'UTF-8'); ?></span>
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
                <span><?php echo htmlspecialchars($flash_success, ENT_QUOTES, 'UTF-8'); ?></span>
            </div>
        </div>
        <?php endif; ?>

        <?php if (!empty($flash_error)): ?>
        <div class="flash-messages">
            <div class="flash-msg error">
                <i class="fas fa-exclamation-circle flash-icon"></i>
                <span><?php echo htmlspecialchars($flash_error, ENT_QUOTES, 'UTF-8'); ?></span>
            </div>
        </div>
        <?php endif; ?>

        <?php if (!empty($flash_info)): ?>
        <div class="flash-messages">
            <div class="flash-msg info">
                <i class="fas fa-info-circle flash-icon"></i>
                <span><?php echo htmlspecialchars($flash_info, ENT_QUOTES, 'UTF-8'); ?></span>
            </div>
        </div>
        <?php endif; ?>

        <?php echo $content; ?>

    </main>
    <!-- /body -->


    <!-- ═══════════ FOOTER ═══════════ -->
    <footer class="portal-footer">
        <div class="footer-main">
            <p>&copy; <?php echo date('Y'); ?> FCT College of Nursing Sciences. All rights reserved.</p>
            
            <!-- Powered by Cloudit Technologies with Animation -->
            <div class="powered-by-wrapper">
                <div class="powered-by">
                    <span class="powered-text">Powered by</span>
                    <a href="https://cloudit.ng" target="_blank" rel="noopener noreferrer" class="cloudit-link">
                        <span class="cloudit-name">Cloudit Technologies</span>
                        <i class="fas fa-cloud cloud-icon"></i>
                        <i class="fas fa-bolt lightning-icon"></i>
                    </a>
                </div>
            </div>
            
            <div class="footer-contacts">
                <?php
                    $supportPhone = $settings['key_value']['support_phone_1'] ?? '07039837749';
                    $supportEmail = $settings['key_value']['support_email']   ?? 'info@fctcns.edu.ng';
                ?>
                <a class="footer-contact-item" href="tel:<?php echo htmlspecialchars($supportPhone, ENT_QUOTES, 'UTF-8'); ?>" rel="noopener noreferrer">
                    <i class="fas fa-phone-alt"></i>
                    <?php echo htmlspecialchars($supportPhone, ENT_QUOTES, 'UTF-8'); ?>
                </a>
                <a class="footer-contact-item" href="mailto:<?php echo htmlspecialchars($supportEmail, ENT_QUOTES, 'UTF-8'); ?>" rel="noopener noreferrer">
                    <i class="fas fa-envelope"></i>
                    <?php echo htmlspecialchars($supportEmail, ENT_QUOTES, 'UTF-8'); ?>
                </a>
            </div>
        </div>
    </footer>

</div><!-- /portal-wrap -->


<!-- ========================================================= -->
<!-- 4. Add CSP nonce to all script tags -->
<!-- 5. Add SRI hashes to external scripts -->
<!-- ========================================================= -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz"
        crossorigin="anonymous"
        nonce="<?php echo $csp_nonce ?? ''; ?>"></script>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"
        integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4="
        crossorigin="anonymous"
        nonce="<?php echo $csp_nonce ?? ''; ?>"></script>

<!-- Check if Payment.js exists before trying to load it -->
<?php if (file_exists(__DIR__ . '/../../assets/js/Payment.js')): ?>
<script src="<?php echo defined('BASE_URL') ? BASE_URL : ''; ?>/assets/js/Payment.js"
        nonce="<?php echo $csp_nonce ?? ''; ?>"></script>
<?php endif; ?>

<script nonce="<?php echo $csp_nonce ?? ''; ?>">
    // ======================================================
    // Portal Layout JavaScript with Security Enhancements
    // ======================================================

    // Get CSRF token from meta tag
    function getCsrfToken() {
        const meta = document.querySelector('meta[name="csrf-token"]');
        return meta ? meta.getAttribute('content') : '';
    }

    setTimeout(function () {
        document.querySelectorAll('.flash-msg').forEach(function (el) {
            el.style.transition = 'opacity 0.4s';
            el.style.opacity    = '0';
            setTimeout(function () { 
                if (el.parentNode) el.remove(); 
            }, 400);
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
                if (el) {
                    el.src = e.target.result;
                    el.style.display = 'block';
                }
            };
            r.readAsDataURL(input.files[0]);
        }
    }

    function confirmPassportUpload(input) {
        if (input.files && input.files[0]) {
            const r = new FileReader();
            r.onload = function (e) {
                if (confirm('Is this your correct passport photograph? Click OK to upload.')) {
                    const preview = document.getElementById('passport-preview');
                    const confirmed = document.getElementById('passport-confirmed');
                    
                    if (preview) {
                        preview.src = e.target.result;
                        preview.style.display = 'block';
                    }
                    
                    if (confirmed) {
                        confirmed.value = '1';
                    }
                    
                    // Optional tracking
                    if (getCsrfToken()) {
                        fetch('/api/track-passport-upload', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': getCsrfToken()
                            },
                            body: JSON.stringify({
                                action: 'passport_upload_confirmed',
                                timestamp: Date.now()
                            })
                        }).catch(() => {});
                    }
                } else {
                    input.value = '';
                    const preview = document.getElementById('passport-preview');
                    const confirmed = document.getElementById('passport-confirmed');
                    
                    if (preview) {
                        preview.style.display = 'none';
                    }
                    
                    if (confirmed) {
                        confirmed.value = '0';
                    }
                }
            };
            r.readAsDataURL(input.files[0]);
        }
    }

    // External link security
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('a[href^="http"]:not([rel*="noopener"])').forEach(link => {
            if (link.hostname !== window.location.hostname) {
                link.setAttribute('target', '_blank');
                link.setAttribute('rel', 'noopener noreferrer');
            }
        });

        // Add security for tel and mailto links
        document.querySelectorAll('a[href^="tel:"], a[href^="mailto:"]').forEach(link => {
            link.addEventListener('click', function(e) {
                // Optional tracking
                if (getCsrfToken()) {
                    fetch('/api/track-contact', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': getCsrfToken()
                        },
                        body: JSON.stringify({
                            action: 'footer_contact_click',
                            type: this.href.startsWith('tel:') ? 'phone' : 'email',
                            timestamp: Date.now()
                        })
                    }).catch(() => {});
                }
            });
        });

        // Track logout attempts
        document.querySelectorAll('.portal-logout-btn').forEach(btn => {
            btn.addEventListener('click', function(e) {
                if (getCsrfToken()) {
                    fetch('/api/track-logout', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': getCsrfToken()
                        },
                        body: JSON.stringify({
                            action: 'logout_click',
                            timestamp: Date.now()
                        })
                    }).catch(() => {});
                }
            });
        });
    });
</script>
</body>
</html>