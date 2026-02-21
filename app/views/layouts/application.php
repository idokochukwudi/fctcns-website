<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0, viewport-fit=cover">
    <title><?php echo $pageTitle ?? 'Application Portal - FCT College of Nursing Sciences'; ?></title>
    <meta name="description" content="<?php echo $pageDescription ?? 'Apply for admission into ND/HND Nursing programme'; ?>">

    <!-- Better Fonts: Inter for UI, Lora for serif, JetBrains Mono for code -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&family=Lora:ital,wght@0,400..700;1,400..700&family=JetBrains+Mono:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        /* ═══════════════════════════════════════════════
           RESET & ROOT - Refined Palette
        ═══════════════════════════════════════════════ */
        *, *::before, *::after { margin: 0; padding: 0; box-sizing: border-box; }

        :root {
            /* ── Primary (subtle purple, minimal use) ── */
            --primary-50:   #f8f5ff;
            --primary-100:  #f0ebff;
            --primary-200:  #e2d9ff;
            --primary-300:  #c7b8ff;
            --primary-400:  #a58fff;
            --primary-500:  #7c5cf0;
            --primary-600:  #6246d9;
            --primary-700:  #4f39b5;
            --primary-800:  #3f2f8f;
            --primary-900:  #31266b;

            /* ── Neutral palette (sophisticated grays) ── */
            --neutral-50:   #fafafa;
            --neutral-100:  #f5f5f5;
            --neutral-200:  #e9e9e9;
            --neutral-300:  #d4d4d4;
            --neutral-400:  #a3a3a3;
            --neutral-500:  #737373;
            --neutral-600:  #525252;
            --neutral-700:  #404040;
            --neutral-800:  #262626;
            --neutral-900:  #171717;

            /* ── Semantic colors (soft, not shouty) ── */
            --success-50:   #f0fdf4;
            --success-100:  #dcfce7;
            --success-200:  #bbf7d0;
            --success-300:  #86efac;
            --success-400:  #4ade80;
            --success-500:  #22c55e;
            --success-600:  #16a34a;
            --success-700:  #15803d;
            --success-800:  #166534;
            --success-900:  #14532d;

            --error-50:     #fef2f2;
            --error-100:    #fee2e2;
            --error-200:    #fecaca;
            --error-300:    #fca5a5;
            --error-400:    #f87171;
            --error-500:    #ef4444;
            --error-600:    #dc2626;
            --error-700:    #b91c1c;
            --error-800:    #991b1b;
            --error-900:    #7f1d1d;

            --warning-50:   #fffbeb;
            --warning-100:  #fef3c7;
            --warning-200:  #fde68a;
            --warning-300:  #fcd34d;
            --warning-400:  #fbbf24;
            --warning-500:  #f59e0b;
            --warning-600:  #d97706;
            --warning-700:  #b45309;
            --warning-800:  #92400e;
            --warning-900:  #78350f;

            /* ── Accent (subtle gold for highlights) ── */
            --accent-50:    #fefce8;
            --accent-100:   #fef9c3;
            --accent-200:   #fef08a;
            --accent-300:   #fde047;
            --accent-400:   #facc15;
            --accent-500:   #eab308;
            --accent-600:   #ca8a04;
            --accent-700:   #a16207;
            --accent-800:   #854d0e;
            --accent-900:   #713f12;

            /* ── Shadows (soft) ── */
            --shadow-xs:    0 1px 2px 0 rgba(0, 0, 0, 0.02);
            --shadow-sm:    0 1px 3px 0 rgba(0, 0, 0, 0.03), 0 1px 2px -1px rgba(0, 0, 0, 0.02);
            --shadow-md:    0 4px 6px -2px rgba(0, 0, 0, 0.02), 0 2px 4px -2px rgba(0, 0, 0, 0.03);
            --shadow-lg:    0 10px 15px -3px rgba(0, 0, 0, 0.03), 0 4px 6px -4px rgba(0, 0, 0, 0.02);
            --shadow-xl:    0 20px 25px -5px rgba(0, 0, 0, 0.03), 0 8px 10px -6px rgba(0, 0, 0, 0.02);

            /* ── Radius (subtle rounding) ── */
            --radius-xs:    4px;
            --radius-sm:    6px;
            --radius-md:    8px;
            --radius-lg:    12px;
            --radius-xl:    16px;
            --radius-2xl:   20px;
            --radius-full:  9999px;

            /* ── Typography (modern, refined) ── */
            --font-sans:    'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            --font-serif:   'Lora', Georgia, 'Times New Roman', serif;
            --font-mono:    'JetBrains Mono', 'Courier New', monospace;

            /* ── Backgrounds ── */
            --bg-body:      #ffffff;
            --bg-surface:   #ffffff;
            --bg-subtle:    #fcfcfc;
            --border-light: #f0f0f0;
            --border:       #e5e5e5;
            --border-dark:  #d4d4d4;
        }

        /* ═══════════════════════════════════════════════
           BASE
        ═══════════════════════════════════════════════ */
        html { 
            font-size: 16px; 
            -webkit-text-size-adjust: 100%; 
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }

        body {
            font-family: var(--font-sans);
            background: var(--bg-body);
            color: var(--neutral-700);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 32px 20px 48px;
            line-height: 1.5;
        }

        /* ═══════════════════════════════════════════════
           PORTAL WRAPPER
        ═══════════════════════════════════════════════ */
        .portal-wrap {
            width: 100%;
            max-width: 1080px;
            animation: fadeUp 0.4s ease-out;
        }

        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(12px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        /* ═══════════════════════════════════════════════
           HEADER - Minimal Purple
        ═══════════════════════════════════════════════ */
        .portal-header {
            background: var(--bg-surface);
            border: 1px solid var(--border);
            border-radius: var(--radius-xl) var(--radius-xl) 0 0;
            overflow: hidden;
            box-shadow: var(--shadow-sm);
        }

        .portal-header-inner {
            display: flex;
            align-items: center;
            gap: 20px;
            padding: 24px 32px;
        }

        /* Emblem / crest circle */
        .portal-emblem {
            flex-shrink: 0;
            width: 56px; 
            height: 56px;
            background: linear-gradient(145deg, var(--primary-50), var(--primary-100));
            border: 1px solid var(--primary-200);
            border-radius: var(--radius-lg);
            display: flex; 
            align-items: center; 
            justify-content: center;
            font-size: 22px;
            color: var(--primary-600);
            transition: all 0.2s ease;
        }

        .portal-header:hover .portal-emblem {
            border-color: var(--primary-300);
            color: var(--primary-700);
        }

        .portal-header-text { 
            flex: 1; 
            min-width: 0; 
        }

        .portal-header-text h1 {
            font-family: var(--font-serif);
            font-size: 22px;
            font-weight: 500;
            color: var(--neutral-900);
            letter-spacing: -0.02em;
            line-height: 1.3;
            margin-bottom: 4px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .portal-header-text p {
            font-family: var(--font-sans);
            font-size: 12px;
            font-weight: 400;
            color: var(--neutral-500);
            letter-spacing: 0.2px;
            margin: 0;
        }

        /* Right cluster */
        .portal-header-right {
            display: flex;
            flex-direction: column;
            align-items: flex-end;
            gap: 10px;
            flex-shrink: 0;
        }

        .portal-header-badge {
            background: var(--neutral-100);
            border: 1px solid var(--border);
            border-radius: var(--radius-full);
            padding: 6px 14px;
            font-family: var(--font-sans);
            font-size: 11px;
            font-weight: 500;
            color: var(--neutral-600);
            letter-spacing: 0.3px;
            white-space: nowrap;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        .portal-header-badge i {
            color: var(--primary-500);
            font-size: 10px;
        }

        .portal-user-row {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .portal-user-avatar {
            width: 32px; 
            height: 32px;
            background: linear-gradient(145deg, var(--neutral-100), var(--neutral-50));
            border: 1px solid var(--border);
            border-radius: var(--radius-full);
            display: flex; 
            align-items: center; 
            justify-content: center;
            color: var(--neutral-500);
            font-size: 12px;
            flex-shrink: 0;
            transition: all 0.2s ease;
        }

        .portal-user-row:hover .portal-user-avatar {
            border-color: var(--primary-300);
            color: var(--primary-600);
        }

        .portal-user-name {
            font-family: var(--font-sans);
            font-size: 13px;
            font-weight: 400;
            color: var(--neutral-600);
            white-space: nowrap;
        }

        .portal-user-name strong { 
            color: var(--neutral-900); 
            font-weight: 600; 
            margin-left: 4px;
        }

        .portal-logout-btn {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 6px 14px;
            background: var(--neutral-50);
            border: 1px solid var(--border);
            border-radius: var(--radius-full);
            font-family: var(--font-sans);
            font-size: 12px;
            font-weight: 500;
            color: var(--neutral-600);
            text-decoration: none;
            transition: all 0.2s ease;
            white-space: nowrap;
        }

        .portal-logout-btn:hover {
            background: var(--neutral-100);
            border-color: var(--neutral-400);
            color: var(--neutral-900);
        }

        .portal-logout-btn i { 
            font-size: 10px; 
            transition: transform 0.2s ease;
        }

        .portal-logout-btn:hover i {
            transform: translateX(2px);
        }

        /* ═══════════════════════════════════════════════
           PROGRESS TRACKER - Subtle
        ═══════════════════════════════════════════════ */
        .progress-track {
            background: var(--neutral-50);
            padding: 20px 32px;
            display: flex;
            align-items: center;
            border-top: 1px solid var(--border);
            border-bottom: 1px solid var(--border);
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
            gap: 10px;
            flex: 1;
            min-width: 0;
        }

        .track-num {
            width: 32px; 
            height: 32px;
            border-radius: var(--radius-full);
            border: 1.5px solid var(--border);
            background: var(--bg-surface);
            display: flex; 
            align-items: center; 
            justify-content: center;
            font-family: var(--font-sans);
            font-size: 12px;
            font-weight: 600;
            color: var(--neutral-400);
            transition: all 0.3s ease;
            flex-shrink: 0;
        }

        .track-step.completed .track-num {
            background: var(--success-50);
            border-color: var(--success-200);
            color: var(--success-600);
        }

        .track-step.active .track-num {
            background: var(--primary-50);
            border-color: var(--primary-300);
            color: var(--primary-600);
        }

        .track-info { 
            display: flex; 
            flex-direction: column; 
            min-width: 0; 
        }

        .track-label {
            font-family: var(--font-sans);
            font-size: 11px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.4px;
            color: var(--neutral-400);
            transition: color 0.3s;
            line-height: 1.3;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .track-step.active    .track-label { color: var(--primary-700); }
        .track-step.completed .track-label { color: var(--success-700); }

        .track-sublabel {
            font-family: var(--font-sans);
            font-size: 10px;
            font-weight: 400;
            color: var(--neutral-400);
            margin-top: 2px;
            transition: color 0.3s;
            white-space: nowrap;
        }

        .track-step.active    .track-sublabel { color: var(--neutral-500); }
        .track-step.completed .track-sublabel { color: var(--neutral-500); }

        .track-connector {
            flex: 0 0 24px;
            height: 1px;
            background: linear-gradient(90deg, var(--border), transparent);
            margin: 0 4px;
        }

        .track-connector.done { 
            background: linear-gradient(90deg, var(--success-300), transparent);
        }

        /* ═══════════════════════════════════════════════
           BODY / CONTENT AREA
        ═══════════════════════════════════════════════ */
        .portal-body {
            background: var(--bg-surface);
            border-left: 1px solid var(--border);
            border-right: 1px solid var(--border);
            padding: 40px;
        }

        /* ═══════════════════════════════════════════════
           FLASH MESSAGES - Soft
        ═══════════════════════════════════════════════ */
        .flash-messages { 
            margin-bottom: 28px; 
        }

        .flash-msg {
            display: flex;
            align-items: flex-start;
            gap: 14px;
            padding: 16px 20px;
            border-radius: var(--radius-lg);
            margin-bottom: 10px;
            font-family: var(--font-sans);
            font-size: 14px;
            border: 1px solid transparent;
            animation: slideDown 0.3s ease;
            line-height: 1.5;
        }

        @keyframes slideDown {
            from { opacity: 0; transform: translateY(-8px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        .flash-msg.success { 
            background: var(--success-50);  
            border-color: var(--success-100); 
            color: var(--success-800); 
        }
        .flash-msg.error   { 
            background: var(--error-50);    
            border-color: var(--error-100);   
            color: var(--error-800); 
        }
        .flash-msg.info    { 
            background: var(--primary-50);   
            border-color: var(--primary-100);  
            color: var(--primary-800); 
        }

        .flash-icon { 
            font-size: 16px; 
            margin-top: 1px; 
            flex-shrink: 0; 
        }
        .flash-msg.success .flash-icon { color: var(--success-500); }
        .flash-msg.error   .flash-icon { color: var(--error-500); }
        .flash-msg.info    .flash-icon { color: var(--primary-500); }

        /* ═══════════════════════════════════════════════
           FORM SECTIONS - Minimal
        ═══════════════════════════════════════════════ */
        .form-section {
            border: 1px solid var(--border);
            border-radius: var(--radius-lg);
            margin-bottom: 28px;
            overflow: hidden;
            background: var(--bg-surface);
            transition: border-color 0.2s ease;
        }

        .form-section:hover {
            border-color: var(--neutral-300);
        }

        .form-section-head {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 18px 24px;
            background: var(--neutral-50);
            border-bottom: 1px solid var(--border);
        }

        .section-icon {
            width: 36px; 
            height: 36px;
            background: var(--primary-50);
            border: 1px solid var(--primary-100);
            border-radius: var(--radius-md);
            display: flex; 
            align-items: center; 
            justify-content: center;
            font-size: 14px;
            color: var(--primary-600);
            flex-shrink: 0;
            transition: all 0.2s ease;
        }

        .form-section:hover .section-icon {
            background: var(--primary-100);
            border-color: var(--primary-200);
        }

        .form-section-head h3 {
            font-family: var(--font-serif);
            font-size: 18px;
            font-weight: 500;
            color: var(--neutral-800);
            margin: 0;
        }

        .form-section-head span {
            font-family: var(--font-sans);
            font-size: 12px;
            font-weight: 400;
            color: var(--neutral-500);
            margin-left: auto;
        }

        .form-section-body { 
            padding: 28px 24px; 
        }

        .form-group { 
            margin-bottom: 24px; 
        }
        .form-group:last-child { 
            margin-bottom: 0; 
        }

        .form-label {
            display: block;
            font-family: var(--font-sans);
            font-size: 13px;
            font-weight: 500;
            color: var(--neutral-700);
            margin-bottom: 8px;
            letter-spacing: 0.2px;
        }

        .form-label .required { 
            color: var(--error-500); 
            margin-left: 2px; 
            font-size: 12px;
        }

        .form-control,
        .form-select {
            border: 1px solid var(--border-dark);
            border-radius: var(--radius-md);
            padding: 12px 16px;
            font-family: var(--font-sans);
            font-size: 14px;
            color: var(--neutral-900);
            background: var(--bg-surface);
            transition: all 0.2s ease;
            width: 100%;
            line-height: 1.5;
        }

        .form-control::placeholder {
            color: var(--neutral-400);
            font-weight: 300;
        }

        .form-control:hover,
        .form-select:hover {
            border-color: var(--neutral-500);
        }

        .form-control:focus,
        .form-select:focus {
            border-color: var(--primary-400);
            box-shadow: 0 0 0 3px var(--primary-100);
            outline: none;
        }

        .form-control.is-invalid { 
            border-color: var(--error-500); 
        }
        .form-control.is-invalid:focus {
            box-shadow: 0 0 0 3px var(--error-100);
        }

        .form-control.is-valid   { 
            border-color: var(--success-500); 
        }
        .form-control.is-valid:focus {
            box-shadow: 0 0 0 3px var(--success-100);
        }

        .form-hint {
            font-family: var(--font-sans);
            font-size: 12px;
            font-weight: 400;
            color: var(--neutral-500);
            margin-top: 6px;
            line-height: 1.5;
        }

        /* ═══════════════════════════════════════════════
           BUTTONS - Refined
        ═══════════════════════════════════════════════ */
        .btn {
            font-family: var(--font-sans);
            font-size: 14px;
            font-weight: 500;
            padding: 12px 28px;
            border-radius: var(--radius-md);
            border: none;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
            letter-spacing: 0.2px;
            text-decoration: none;
            line-height: 1.5;
            white-space: nowrap;
        }

        .btn-primary {
            background: var(--primary-600);
            color: white;
            box-shadow: var(--shadow-sm);
        }
        .btn-primary:hover {
            background: var(--primary-700);
            transform: translateY(-1px);
            box-shadow: var(--shadow-md);
        }
        .btn-primary:active {
            transform: translateY(0);
            box-shadow: var(--shadow-xs);
        }

        .btn-secondary {
            background: var(--accent-500);
            color: white;
            box-shadow: var(--shadow-sm);
        }
        .btn-secondary:hover {
            background: var(--accent-600);
            transform: translateY(-1px);
            box-shadow: var(--shadow-md);
        }

        .btn-success {
            background: var(--success-600);
            color: white;
            box-shadow: var(--shadow-sm);
        }
        .btn-success:hover {
            background: var(--success-700);
            transform: translateY(-1px);
        }

        .btn-outline {
            background: transparent;
            color: var(--neutral-700);
            border: 1px solid var(--border-dark);
        }
        .btn-outline:hover {
            background: var(--neutral-50);
            border-color: var(--neutral-500);
            color: var(--neutral-900);
        }

        .btn-ghost {
            background: transparent;
            color: var(--neutral-600);
            border: none;
        }
        .btn-ghost:hover {
            background: var(--neutral-100);
            color: var(--neutral-900);
        }

        .btn-lg { 
            padding: 14px 36px; 
            font-size: 15px; 
        }
        .btn-sm { 
            padding: 8px 20px; 
            font-size: 13px; 
        }

        .btn:disabled,
        .btn.disabled {
            opacity: 0.5;
            pointer-events: none;
        }

        /* ═══════════════════════════════════════════════
           PAYMENT CARD - Clean
        ═══════════════════════════════════════════════ */
        .payment-card {
            border: 1px solid var(--border);
            border-radius: var(--radius-xl);
            overflow: hidden;
            background: var(--bg-surface);
            box-shadow: var(--shadow-sm);
        }

        .payment-card-head {
            background: linear-gradient(145deg, var(--neutral-50), white);
            padding: 32px;
            text-align: center;
            border-bottom: 1px solid var(--border);
            position: relative;
        }

        .payment-label {
            font-family: var(--font-sans);
            font-size: 11px;
            font-weight: 600;
            letter-spacing: 1px;
            text-transform: uppercase;
            color: var(--neutral-500);
            margin-bottom: 12px;
        }

        .payment-amount {
            font-family: var(--font-serif);
            font-size: 56px;
            font-weight: 500;
            color: var(--primary-700);
            line-height: 1;
        }

        .payment-amount sup {
            font-size: 24px;
            vertical-align: super;
            opacity: 0.7;
            top: -0.2em;
        }

        .payment-card-body { 
            padding: 28px 32px; 
        }

        .payment-rrr-box {
            background: var(--neutral-50);
            border: 1px dashed var(--border-dark);
            border-radius: var(--radius-md);
            padding: 16px 24px;
            font-family: var(--font-mono);
            font-size: 20px;
            font-weight: 500;
            color: var(--neutral-800);
            text-align: center;
            letter-spacing: 2px;
            margin-bottom: 20px;
        }

        /* ═══════════════════════════════════════════════
           EXAM SLIP
        ═══════════════════════════════════════════════ */
        .exam-slip {
            border: 1px solid var(--border);
            border-radius: var(--radius-xl);
            overflow: hidden;
            background: var(--bg-surface);
        }

        .exam-slip-head {
            background: linear-gradient(145deg, var(--neutral-50), white);
            padding: 28px 32px;
            text-align: center;
            border-bottom: 1px solid var(--border);
        }

        .exam-slip-head h2 {
            font-family: var(--font-serif);
            color: var(--neutral-800);
            font-size: 22px;
            font-weight: 500;
            margin-bottom: 6px;
        }

        .exam-slip-head p {
            font-family: var(--font-sans);
            font-size: 13px;
            color: var(--neutral-500);
            margin: 0;
        }

        .exam-slip-body { 
            padding: 28px 32px; 
        }

        .slip-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 16px;
        }

        .slip-item {
            background: var(--neutral-50);
            border: 1px solid var(--border);
            border-radius: var(--radius-lg);
            padding: 16px 18px;
            transition: all 0.2s ease;
        }

        .slip-item:hover {
            background: white;
            border-color: var(--primary-200);
        }

        .slip-item .lbl {
            font-family: var(--font-sans);
            font-size: 10px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: var(--neutral-500);
            margin-bottom: 6px;
        }

        .slip-item .val {
            font-family: var(--font-sans);
            font-size: 15px;
            font-weight: 500;
            color: var(--neutral-800);
        }

        /* ═══════════════════════════════════════════════
           DOCUMENT PREVIEW
        ═══════════════════════════════════════════════ */
        .doc-preview {
            position: relative;
            display: inline-block;
            margin: 8px;
        }

        .doc-preview img {
            width: 140px; 
            height: 140px;
            object-fit: cover;
            border: 1px solid var(--border);
            border-radius: var(--radius-md);
            display: block;
            transition: border-color 0.2s ease;
        }

        .doc-preview:hover img { 
            border-color: var(--primary-400); 
        }

        .doc-remove {
            position: absolute;
            top: -8px; 
            right: -8px;
            width: 26px; 
            height: 26px;
            background: var(--error-500);
            color: white;
            border-radius: var(--radius-full);
            display: flex; 
            align-items: center; 
            justify-content: center;
            cursor: pointer;
            font-size: 10px;
            border: 2px solid white;
            transition: all 0.2s ease;
            opacity: 0.9;
        }

        .doc-remove:hover { 
            transform: scale(1.1); 
            background: var(--error-600);
            opacity: 1;
        }

        /* ═══════════════════════════════════════════════
           DIVIDER
        ═══════════════════════════════════════════════ */
        .section-divider {
            border: none;
            height: 1px;
            background: linear-gradient(90deg, transparent, var(--border), transparent);
            margin: 32px 0;
        }

        /* ═══════════════════════════════════════════════
           FOOTER - Minimal
        ═══════════════════════════════════════════════ */
        .portal-footer {
            background: var(--neutral-50);
            border: 1px solid var(--border);
            border-radius: 0 0 var(--radius-xl) var(--radius-xl);
            padding: 24px 32px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 16px;
            border-top: none;
        }

        .portal-footer p {
            margin: 0;
            font-family: var(--font-sans);
            font-size: 13px;
            font-weight: 400;
            color: var(--neutral-500);
        }

        .footer-contacts {
            display: flex;
            gap: 24px;
            flex-wrap: wrap;
        }

        .footer-contact-item {
            display: flex;
            align-items: center;
            gap: 8px;
            font-family: var(--font-sans);
            font-size: 13px;
            font-weight: 500;
            color: var(--neutral-600);
            text-decoration: none;
            transition: color 0.2s ease;
        }

        .footer-contact-item:hover { 
            color: var(--primary-600); 
        }
        .footer-contact-item i { 
            color: var(--primary-400); 
            font-size: 12px; 
            transition: color 0.2s ease;
        }
        .footer-contact-item:hover i {
            color: var(--primary-500);
        }

        /* ═══════════════════════════════════════════════
           UTILITIES
        ═══════════════════════════════════════════════ */
        .text-primary { color: var(--primary-600) !important; }
        .text-success { color: var(--success-600) !important; }
        .text-error   { color: var(--error-600) !important; }
        .text-accent  { color: var(--accent-600) !important; }
        .text-muted   { color: var(--neutral-500) !important; }

        .bg-subtle    { background: var(--neutral-50); }

        /* Payment button area */
        #paymentButtonArea .btn-amber {
            display: block;
            width: 100%;
            padding: 14px 24px;
            background: var(--accent-500);
            color: white;
            border: none;
            border-radius: var(--radius-md);
            font-family: var(--font-sans);
            font-weight: 600;
            font-size: 15px;
            text-align: center;
            text-decoration: none;
            transition: all 0.2s ease;
            cursor: pointer;
        }

        #paymentButtonArea .btn-amber:hover {
            background: var(--accent-600);
            transform: translateY(-1px);
            box-shadow: var(--shadow-md);
        }

        /* ═══════════════════════════════════════════════
           RESPONSIVE
        ═══════════════════════════════════════════════ */
        @media (max-width: 1024px) {
            .portal-wrap {
                max-width: 100%;
            }
        }

        @media (max-width: 768px) {
            body { 
                padding: 16px 12px 32px; 
            }

            .portal-header-inner {
                flex-wrap: wrap;
                padding: 20px 24px;
                gap: 16px;
            }

            .portal-header-text h1 {
                font-size: 18px;
                white-space: normal;
            }

            .portal-header-right {
                width: 100%;
                flex-direction: row;
                align-items: center;
                justify-content: space-between;
            }

            .progress-track {
                padding: 16px 20px;
                flex-wrap: wrap;
                gap: 8px;
            }

            .track-connector { 
                display: none; 
            }

            .track-step {
                flex: 0 0 calc(50% - 8px);
                background: var(--neutral-50);
                border: 1px solid var(--border);
                border-radius: var(--radius-md);
                padding: 10px 12px;
            }

            .portal-body { 
                padding: 28px 24px; 
            }

            .portal-footer {
                flex-direction: column;
                align-items: flex-start;
                padding: 20px 24px;
            }

            .slip-grid { 
                grid-template-columns: 1fr; 
            }
            
            .payment-amount { 
                font-size: 44px; 
            }
            
            .payment-card-head,
            .payment-card-body {
                padding: 24px;
            }
        }

        @media (max-width: 480px) {
            .portal-header-text h1 { 
                font-size: 16px; 
            }
            
            .portal-emblem { 
                width: 48px; 
                height: 48px; 
                font-size: 18px; 
            }
            
            .track-step { 
                flex: 0 0 100%; 
            }
            
            .portal-body { 
                padding: 20px 16px; 
            }

            .form-section-head {
                padding: 16px 18px;
            }

            .form-section-body {
                padding: 20px 16px;
            }

            .form-control,
            .form-select {
                padding: 10px 14px;
                font-size: 14px;
            }

            .btn {
                width: 100%;
                padding: 12px 20px;
            }

            .footer-contacts {
                flex-direction: column;
                gap: 12px;
                width: 100%;
            }
        }

        @media (max-width: 360px) {
            body { 
                padding: 8px 8px 24px; 
            }
            
            .portal-body { 
                padding: 16px 12px; 
            }
        }

        /* Print Styles */
        @media print {
            .portal-header,
            .progress-track,
            .portal-footer,
            .btn,
            #paymentButtonArea {
                display: none;
            }

            .portal-body {
                border: none;
                padding: 0;
            }

            .form-section {
                border: 1px solid #000;
                page-break-inside: avoid;
            }
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
                <p>Admissions Application Portal — 2025 / 2026 Session</p>
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
                        Welcome, <strong><?php echo htmlspecialchars($applicantDisplayName); ?></strong>
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
    // Auto-hide flash messages
    setTimeout(function () {
        document.querySelectorAll('.flash-msg').forEach(function (el) {
            el.style.transition = 'opacity 0.4s, transform 0.4s';
            el.style.opacity = '0';
            el.style.transform = 'translateY(-10px)';
            setTimeout(function () { 
                if (el.parentNode) el.remove(); 
            }, 400);
        });
    }, 6000);

    // Confirmation helper
    function confirmAction(msg) { 
        return confirm(msg || 'Are you sure you want to proceed?'); 
    }

    // Password strength checker (simple)
    function checkPasswordStrength(pw) {
        let score = 0;
        if (pw.length >= 8) score++;
        if (/[a-z]/.test(pw)) score++;
        if (/[A-Z]/.test(pw)) score++;
        if (/[0-9]/.test(pw)) score++;
        if (/[^a-zA-Z0-9]/.test(pw)) score++;
        return score;
    }

    // Image preview
    function previewImage(input, previewId) {
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function (e) {
                const preview = document.getElementById(previewId);
                if (preview) {
                    preview.src = e.target.result;
                    preview.style.display = 'block';
                }
            };
            reader.readAsDataURL(input.files[0]);
        }
    }

    // Passport confirmation
    function confirmPassportUpload(input) {
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function (e) {
                if (confirm('Is this your correct passport photograph? Click OK to upload.')) {
                    const preview = document.getElementById('passport-preview');
                    if (preview) {
                        preview.src = e.target.result;
                        preview.style.display = 'block';
                    }
                    const confirmed = document.getElementById('passport-confirmed');
                    if (confirmed) confirmed.value = '1';
                } else {
                    input.value = '';
                    const preview = document.getElementById('passport-preview');
                    if (preview) preview.style.display = 'none';
                    const confirmed = document.getElementById('passport-confirmed');
                    if (confirmed) confirmed.value = '0';
                }
            };
            reader.readAsDataURL(input.files[0]);
        }
    }

    // Add loading state to forms
    document.addEventListener('DOMContentLoaded', function() {
        const forms = document.querySelectorAll('form');
        forms.forEach(form => {
            form.addEventListener('submit', function(e) {
                const submitBtn = this.querySelector('button[type="submit"], input[type="submit"]');
                if (submitBtn && !submitBtn.disabled) {
                    submitBtn.disabled = true;
                    if (submitBtn.tagName === 'BUTTON') {
                        const originalText = submitBtn.innerHTML;
                        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Processing...';
                        
                        // Re-enable after 10 seconds if stuck (safety)
                        setTimeout(() => {
                            submitBtn.disabled = false;
                            submitBtn.innerHTML = originalText;
                        }, 10000);
                    }
                }
            });
        });
    });
</script>
</body>
</html>