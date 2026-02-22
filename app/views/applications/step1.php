<?php
/**
 * JAMB Verification View - Step 1
 *
 * Security fixes applied:
 *  1. All security-relevant HTTP headers (X-Frame-Options, X-XSS-Protection,
 *     Referrer-Policy, CSP) are emitted via PHP header() — NOT via <meta> tags,
 *     which browsers ignore for those directives.
 *  2. Google Fonts <link> has NO integrity attribute — Fonts CDN returns
 *     dynamic, User-Agent-specific responses that can never match a static hash.
 *  3. Version-pinned CDN assets (Bootstrap, Font Awesome) keep their SRI hashes.
 *  4. The controller that handles /apply/verify-jamb MUST send
 *     Content-Type: application/json before outputting JSON (see note below).
 *
 * @package FCTCNS
 */

require_once APP_PATH . '/helpers/SecurityHelper.php';
require_once APP_PATH . '/helpers/SecurityTrait.php';

class JambVerificationView {
    use SecurityTrait;

    public function render($data) {
        extract($data);

        // ── Security: resolve nonce + CSRF token ──────────────────────────────
        $csp_nonce  = $this->getCspNonce();
        $csrf_token = $this->getCsrfToken();

        // ── Data defaults ─────────────────────────────────────────────────────
        $terms          = $terms          ?? [];
        $settings       = $settings       ?? [];
        $portal_closed  = $portal_closed  ?? false;
        $portal_message = $portal_message ?? '';

        // Secure JSON for inline JavaScript (HTML-entity-encoded, no </script> injection)
        $secureTermsData = $this->secureJsonEncode($terms);

        // ── Step tracking ─────────────────────────────────────────────────────
        $currentStep = 1;
        if (isset($application) && !empty($application['application_step'])) {
            $currentStep = (int) $application['application_step'];
            if ($currentStep === 4 && isset($has_exam_slip) && $has_exam_slip) {
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

        // =========================================================
        // HTTP SECURITY HEADERS
        //
        // These MUST be sent as HTTP response headers, not <meta>
        // tags. The meta equivalents for X-Frame-Options and
        // X-XSS-Protection are silently ignored by all browsers.
        //
        // If your framework/router has already sent headers by the
        // time this view runs, move these calls into your bootstrap
        // file (e.g. public/index.php) or a middleware layer.
        // =========================================================
        if (!headers_sent()) {
            header('X-Frame-Options: DENY');
            header('X-Content-Type-Options: nosniff');
            header('X-XSS-Protection: 1; mode=block');
            header('Referrer-Policy: strict-origin-when-cross-origin');

            // CSP — effective only as an HTTP header, not a <meta> tag.
            // 'nonce-{value}' authorises the inline <style> and <script> blocks below.
            header(
                "Content-Security-Policy: " .
                "default-src 'self'; " .
                "script-src 'self' 'nonce-{$csp_nonce}' https://cdn.jsdelivr.net https://cdnjs.cloudflare.com; " .
                "style-src 'self' 'unsafe-inline' https://fonts.googleapis.com https://cdn.jsdelivr.net https://cdnjs.cloudflare.com; " .
                "font-src 'self' https://fonts.gstatic.com https://cdnjs.cloudflare.com; " .
                "img-src 'self' data:; " .
                "connect-src 'self';"
            );
        }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0, viewport-fit=cover">
    <meta name="description" content="JAMB Verification - FCT College of Nursing Sciences">

    <!--
        CSRF token — safe as a <meta> tag (used by JS to attach to AJAX requests).
        Security headers such as X-Frame-Options are sent as HTTP headers above;
        they have been intentionally removed from here.
    -->
    <meta name="csrf-token" content="<?php echo $this->e($csrf_token); ?>">

    <title>JAMB Verification - FCT College of Nursing Sciences</title>

    <!-- Preconnect hints for Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <!--
        Google Fonts — integrity attribute intentionally OMITTED.

        Google Fonts serves User-Agent-specific responses (the exact
        set of font formats and subset ranges varies per request), so
        the byte content changes between requests. Any pre-computed
        SRI hash will therefore never match, and the browser will
        block the resource. Omitting SRI here is the correct, secure
        approach for dynamic CDN resources.
    -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Playfair+Display:wght@400;500;600;700&display=swap"
          rel="stylesheet"
          crossorigin="anonymous">

    <!--
        Font Awesome — SRI hash retained.
        This URL is version-pinned and content-addressed; the hash
        will match consistently across requests.
    -->
    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"
          integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw=="
          crossorigin="anonymous"
          referrerpolicy="no-referrer">

    <!--
        Bootstrap CSS — SRI hash retained (version-pinned, static content).
    -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css"
          rel="stylesheet"
          integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM"
          crossorigin="anonymous">

    <style nonce="<?php echo $this->e($csp_nonce); ?>">
        /* ==========================================================================
           RESET & BASE STYLES
           ========================================================================== */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            margin: 0;
        }

        /* ==========================================================================
           DESIGN TOKENS
           ========================================================================== */
        :root {
            --primary: #6B4E9B;
            --primary-dark: #4A3B6B;
            --primary-light: #8A6FB0;
            --primary-soft: #F3EAF8;
            --gold: #C9A44A;
            --gold-light: #D8B86C;
            --success: #10b981;
            --success-light: #d1fae5;
            --danger: #ef4444;
            --danger-light: #fee2e2;
            --warning: #f59e0b;
            --warning-light: #fef3c7;
            --info: #3b82f6;
            --info-light: #dbeafe;
            --surface: #F7F9FC;
            --border: #E9EDF2;
            --white: #FFFFFF;
            --text-dark: #1A1F2E;
            --text-light: #FFFFFF;
            --text-muted: #6B7280;
            --shadow-sm: 0 4px 6px rgba(0,0,0,0.05);
            --shadow-md: 0 10px 25px rgba(0,0,0,0.1);
            --shadow-lg: 0 20px 40px rgba(0,0,0,0.15);
            --shadow-primary: 0 10px 30px rgba(107,78,155,0.3);
            --radius-sm: 8px;
            --radius-md: 12px;
            --radius-lg: 20px;
            --radius-xl: 30px;
        }

        /* ==========================================================================
           CONTAINER & LAYOUT
           ========================================================================== */
        .verification-container {
            max-width: 900px;
            width: 100%;
            margin: 0 auto;
            animation: fadeIn 0.5s ease;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        /* ==========================================================================
           HEADER
           ========================================================================== */
        .header {
            text-align: center;
            margin-bottom: 30px;
        }

        .header h1 {
            font-family: 'Playfair Display', serif;
            font-size: clamp(28px, 5vw, 42px);
            font-weight: 700;
            color: #FFFFFF !important;
            margin-bottom: 10px;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.5), 0 0 10px rgba(0,0,0,0.3);
            letter-spacing: -0.5px;
        }

        .header p {
            font-size: clamp(14px, 2vw, 16px);
            color: #FFFFFF !important;
            font-weight: 500;
            text-shadow: 1px 1px 3px rgba(0,0,0,0.5);
            opacity: 1;
            background: rgba(0,0,0,0.2);
            display: inline-block;
            padding: 8px 20px;
            border-radius: 50px;
            backdrop-filter: blur(5px);
        }

        /* ==========================================================================
           STEP INDICATOR
           ========================================================================== */
        .step-indicator {
            display: flex;
            justify-content: space-between;
            margin-bottom: 40px;
            position: relative;
            background: rgba(0, 0, 0, 0.4);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border-radius: 50px;
            padding: 20px 25px;
            border: 1px solid rgba(255, 255, 255, 0.3);
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.3);
        }

        .step-indicator::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 70px;
            right: 70px;
            height: 2px;
            background: rgba(255, 255, 255, 0.2);
            transform: translateY(-50%);
            z-index: 1;
        }

        .step {
            position: relative;
            z-index: 2;
            text-align: center;
            flex: 1;
            padding: 5px 0;
        }

        .step-number {
            width: 40px;
            height: 40px;
            background: rgba(255, 255, 255, 0.2);
            border: 2px solid rgba(255, 255, 255, 0.5);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 8px;
            font-weight: 700;
            font-size: 18px;
            color: #FFFFFF !important;
            transition: all 0.3s;
            backdrop-filter: blur(5px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.3);
        }

        .step.active .step-number {
            background: var(--primary);
            border-color: #FFD700;
            box-shadow: 0 0 20px rgba(107, 78, 155, 0.6);
            color: #FFFFFF !important;
            transform: scale(1.1);
        }

        .step.completed .step-number {
            background: var(--success);
            border-color: #FFFFFF;
            color: #FFFFFF !important;
        }

        .step-label {
            font-size: 12px;
            font-weight: 600;
            color: #FFFFFF !important;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            text-shadow: 2px 2px 3px rgba(0, 0, 0, 0.6);
            white-space: nowrap;
        }

        .step-sub {
            font-size: 10px;
            color: rgba(255, 255, 255, 0.8) !important;
            text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.5);
            margin-top: 2px;
        }

        .step.active .step-label {
            color: #FFD700 !important;
            font-weight: 700;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.6);
        }

        .step.active .step-sub {
            color: rgba(255, 215, 0, 0.9) !important;
        }

        @media (max-width: 768px) {
            .step-indicator {
                flex-wrap: wrap;
                gap: 12px;
                background: rgba(0, 0, 0, 0.6);
                backdrop-filter: blur(10px);
                padding: 15px;
                border-radius: 30px;
            }

            .step-indicator::before { display: none; }

            .step {
                flex: 0 0 calc(50% - 6px);
                padding: 8px 5px;
                background: rgba(255, 255, 255, 0.1);
                border-radius: 20px;
                backdrop-filter: blur(5px);
                border: 1px solid rgba(255, 255, 255, 0.15);
            }

            .step-number { width: 32px; height: 32px; font-size: 14px; margin-bottom: 4px; }
            .step-label  { font-size: 10px; white-space: normal; }
            .step-sub    { font-size: 8px; }
        }

        /* ==========================================================================
           MAIN CARD
           ========================================================================== */
        .main-card {
            background: var(--white);
            border-radius: var(--radius-xl);
            box-shadow: var(--shadow-lg);
            overflow: hidden;
            transition: all 0.3s;
        }

        .main-card:hover {
            box-shadow: var(--shadow-lg), var(--shadow-primary);
        }

        .card-header {
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            color: white;
            padding: 30px;
            text-align: center;
            border-bottom: none;
        }

        .card-header i { font-size: 3rem; margin-bottom: 15px; color: var(--gold); }

        .card-header h2 {
            font-size: 28px;
            font-weight: 700;
            margin-bottom: 10px;
            color: #FFFFFF !important;
            text-shadow: 1px 1px 2px rgba(0,0,0,0.2);
        }

        .card-header p {
            font-size: 14px;
            margin: 0;
            color: rgba(255,255,255,0.95) !important;
        }

        .card-body { padding: 40px; }

        @media (max-width: 768px) { .card-body { padding: 25px; } }

        /* ==========================================================================
           TERMS CARD
           ========================================================================== */
        .terms-card {
            background: var(--primary-soft);
            border-radius: var(--radius-lg);
            margin-bottom: 30px;
            border: 1px solid var(--primary-light);
            overflow: hidden;
        }

        .terms-header {
            background: linear-gradient(135deg, var(--gold) 0%, #B48C3A 100%);
            color: white;
            padding: 15px 20px;
        }

        .terms-header h5 {
            margin: 0;
            font-size: 16px;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 10px;
            color: #FFFFFF !important;
            text-shadow: 1px 1px 2px rgba(0,0,0,0.2);
        }

        .terms-body {
            padding: 20px;
            max-height: 300px;
            overflow-y: auto;
            background: white;
        }

        .terms-body h6 { color: var(--primary-dark); font-weight: 600; margin-bottom: 15px; font-size: 16px; }

        .terms-content {
            font-size: 14px;
            color: var(--text-dark);
            line-height: 1.7;
        }

        .terms-content ol,
        .terms-content ul { padding-left: 20px; margin-bottom: 15px; }

        .terms-content li { margin-bottom: 8px; color: var(--text-dark); }

        .terms-footer {
            background: #f8f9fa;
            padding: 12px 20px;
            border-top: 1px solid var(--border);
            font-size: 12px;
            color: var(--text-muted);
        }

        /* ==========================================================================
           FORM ELEMENTS
           ========================================================================== */
        .form-label {
            font-weight: 600;
            color: var(--primary-dark);
            margin-bottom: 8px;
            font-size: 14px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .form-control {
            border: 2px solid var(--border);
            border-radius: var(--radius-md);
            padding: 14px 16px;
            font-size: 15px;
            transition: all 0.3s;
            font-family: 'Inter', sans-serif;
            color: var(--text-dark);
        }

        .form-control:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(107,78,155,0.15);
            outline: none;
        }

        .form-control-lg { font-size: 18px; letter-spacing: 1px; }

        .form-control::placeholder { color: #9CA3AF; font-size: 14px; letter-spacing: normal; }

        .form-text { font-size: 12px; color: var(--text-muted); margin-top: 5px; }

        .form-check { margin: 20px 0; }

        .form-check-input {
            width: 18px;
            height: 18px;
            margin-top: 2px;
            border: 2px solid var(--border);
        }

        .form-check-input:checked { background-color: var(--primary); border-color: var(--primary); }

        .form-check-label { font-size: 14px; color: var(--text-dark); margin-left: 5px; }
        .form-check-label a { color: var(--primary); text-decoration: none; font-weight: 500; }
        .form-check-label a:hover { text-decoration: underline; }

        /* ==========================================================================
           INFO ALERT
           ========================================================================== */
        .info-alert {
            background: var(--info-light);
            border-left: 4px solid var(--info);
            border-radius: var(--radius-md);
            padding: 20px;
            margin: 25px 0;
        }

        .info-alert i { color: var(--info); font-size: 18px; }
        .info-alert strong { color: var(--text-dark); }
        .info-alert ul { margin: 10px 0 0 20px; color: var(--text-dark); font-size: 14px; }
        .info-alert li { margin-bottom: 5px; color: var(--text-dark); }

        /* ==========================================================================
           BUTTONS
           ========================================================================== */
        .btn {
            padding: 14px 30px;
            border-radius: var(--radius-md);
            font-weight: 600;
            font-size: 16px;
            transition: all 0.3s;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            border: none;
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            color: white;
            box-shadow: var(--shadow-primary);
        }

        .btn-primary:hover:not(:disabled) {
            transform: translateY(-2px);
            box-shadow: 0 15px 35px rgba(107,78,155,0.4);
            color: white;
        }

        .btn-primary:disabled { opacity: 0.7; cursor: not-allowed; }

        .btn-outline-primary {
            background: transparent;
            border: 2px solid var(--primary);
            color: var(--primary);
        }

        .btn-outline-primary:hover {
            background: var(--primary);
            color: white;
            transform: translateY(-2px);
        }

        .btn-lg { width: 100%; }

        .spinner-border { width: 18px; height: 18px; border-width: 2px; margin-right: 5px; }

        /* ==========================================================================
           ALERTS
           ========================================================================== */
        .alert {
            border-radius: var(--radius-md);
            padding: 15px 20px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
            border: none;
            animation: slideIn 0.3s ease;
        }

        @keyframes slideIn {
            from { opacity: 0; transform: translateY(-10px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        .alert-success { background: var(--success-light); color: #065f46; border-left: 4px solid var(--success); }
        .alert-danger  { background: var(--danger-light);  color: #991b1b; border-left: 4px solid var(--danger); }
        .alert-warning { background: var(--warning-light); color: #92400e; border-left: 4px solid var(--warning); }
        .alert-info    { background: var(--info-light);    color: #1e40af; border-left: 4px solid var(--info); }

        .alert .btn-close {
            margin-left: auto;
            background: none;
            border: none;
            font-size: 20px;
            cursor: pointer;
            opacity: 0.5;
            color: currentColor;
        }

        .alert .btn-close:hover { opacity: 1; }

        /* ==========================================================================
           DIVIDER
           ========================================================================== */
        .divider { text-align: center; margin: 30px 0; position: relative; }

        .divider::before {
            content: '';
            position: absolute;
            top: 50%; left: 0; right: 0;
            height: 1px;
            background: var(--border);
            z-index: 1;
        }

        .divider span {
            background: var(--white);
            padding: 0 15px;
            color: var(--text-muted);
            font-size: 14px;
            position: relative;
            z-index: 2;
        }

        /* ==========================================================================
           FOOTER
           ========================================================================== */
        .app-footer {
            text-align: center;
            margin-top: 30px;
            color: #FFFFFF !important;
            font-size: 13px;
            padding: 20px;
            background: rgba(0, 0, 0, 0.4);
            border-radius: var(--radius-lg);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .app-footer p   { color: #FFFFFF !important; margin-bottom: 5px; text-shadow: 1px 1px 2px rgba(0,0,0,0.3); }
        .app-footer a   { color: #FFFFFF !important; text-decoration: none; font-weight: 500; border-bottom: 1px dotted rgba(255,255,255,0.6); }
        .app-footer a:hover { border-bottom-color: #FFFFFF; }
        .app-footer i   { margin: 0 5px; color: #FFFFFF !important; }

        /* ==========================================================================
           PORTAL CLOSED
           ========================================================================== */
        .portal-closed { background: var(--white); border-radius: var(--radius-xl); padding: 40px; text-align: center; }
        .portal-closed i  { font-size: 4rem; color: var(--warning); margin-bottom: 20px; }
        .portal-closed h2 { color: var(--primary); margin-bottom: 15px; }
        .portal-closed p  { color: var(--text-dark); margin-bottom: 10px; }

        /* ==========================================================================
           RESPONSIVE
           ========================================================================== */
        @media (max-width: 576px) {
            body { padding: 10px; }
            .card-header { padding: 20px; }
            .card-header i { font-size: 2.5rem; }
            .card-header h2 { font-size: 22px; }
            .btn { padding: 12px 20px; font-size: 14px; }
            .form-control-lg { font-size: 16px; padding: 12px; }
            .app-footer { padding: 15px; font-size: 12px; }
        }
    </style>
</head>
<body>
    <div class="verification-container">

        <!-- ── Page heading ─────────────────────────────────────────────── -->
        <div class="header">
            <h1>FCT College of Nursing Sciences</h1>
            <p>2025/2026 Admissions Application Portal</p>
        </div>

        <!-- ── Step indicator ───────────────────────────────────────────── -->
        <div class="step-indicator">
            <?php foreach ($steps as $num => $step):
                $stepClass = '';
                if ($num < $currentStep) $stepClass = 'completed';
                elseif ($num === $currentStep) $stepClass = 'active';
            ?>
            <div class="step <?php echo $this->e($stepClass); ?>">
                <div class="step-number">
                    <?php if ($num < $currentStep): ?>
                        <i class="fas fa-check"></i>
                    <?php else: ?>
                        <?php echo (int) $num; ?>
                    <?php endif; ?>
                </div>
                <div class="step-label"><?php echo $this->e($step['label']); ?></div>
                <div class="step-sub"><?php echo $this->e($step['sub']); ?></div>
            </div>
            <?php endforeach; ?>
        </div>

        <!-- ── Alert container (populated by JS) ───────────────────────── -->
        <div id="alertContainer" role="alert" aria-live="polite"></div>

        <?php if ($portal_closed): ?>
            <!-- Portal closed state -->
            <div class="portal-closed">
                <i class="fas fa-exclamation-triangle"></i>
                <h2>Application Portal Closed</h2>
                <p><?php echo $this->e($portal_message); ?></p>
                <p class="text-muted mt-3">The next admissions cycle will be announced on this portal.</p>
            </div>

        <?php else: ?>
            <!-- ── Main card ─────────────────────────────────────────────── -->
            <div class="main-card">
                <div class="card-header">
                    <i class="fas fa-id-card"></i>
                    <h2>JAMB Verification</h2>
                    <p>Enter your JAMB registration number to begin your application</p>
                </div>

                <div class="card-body">

                    <?php if (empty($terms)): ?>
                        <div class="alert alert-warning">
                            <i class="fas fa-exclamation-triangle"></i>
                            Terms and conditions are not available at the moment. Please try again later.
                        </div>
                    <?php else: ?>

                    <!-- Terms and Conditions Card -->
                    <div class="terms-card">
                        <div class="terms-header">
                            <h5><i class="fas fa-file-contract"></i> Terms and Conditions</h5>
                        </div>
                        <div class="terms-body">
                            <h6><?php echo $this->e($terms['title'] ?? 'Terms and Conditions'); ?></h6>
                            <div class="terms-content">
                                <?php echo nl2br($this->e($terms['content'] ?? '')); ?>
                            </div>
                        </div>
                        <div class="terms-footer">
                            <i class="fas fa-clock me-1"></i>
                            Version: <?php echo $this->e($terms['version'] ?? '1.0'); ?> |
                            Effective: <?php echo $this->e(isset($terms['effective_date']) ? date('jS F Y', strtotime($terms['effective_date'])) : '15th September 2025'); ?>
                        </div>
                    </div>

                    <!-- JAMB Verification Form -->
                    <form id="jambVerificationForm" novalidate>
                        <!--
                            CSRF token hidden field.
                            The same token is also read from the <meta> tag by JS
                            and attached as the X-CSRF-Token request header.
                        -->
                        <input type="hidden" name="csrf_token" value="<?php echo $this->e($csrf_token); ?>">

                        <div class="mb-4">
                            <label for="jamb_number" class="form-label">
                                <i class="fas fa-id-card text-primary"></i>
                                JAMB Registration Number
                            </label>
                            <input type="text"
                                   class="form-control form-control-lg"
                                   id="jamb_number"
                                   name="jamb_number"
                                   placeholder="e.g., 202650000089FG"
                                   style="text-transform: uppercase;"
                                   autocomplete="off"
                                   maxlength="14"
                                   pattern="[0-9A-Za-z]{10,14}"
                                   required
                                   aria-describedby="jambHelp">
                            <div class="form-text" id="jambHelp">
                                <i class="fas fa-info-circle"></i>
                                Enter the JAMB registration number you used for the 2025 UTME.
                            </div>
                        </div>

                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" id="accept_terms" name="accept_terms" required>
                            <label class="form-check-label" for="accept_terms">
                                I have read and agree to the
                                <a href="#" data-bs-toggle="modal" data-bs-target="#termsModal">Terms and Conditions</a>
                            </label>
                        </div>

                        <!-- Requirements summary -->
                        <div class="info-alert">
                            <i class="fas fa-info-circle"></i>
                            <strong>By proceeding, you confirm that:</strong>
                            <ul class="mb-0">
                                <li>You have a minimum UTME score of <?php echo $this->e($settings['key_value']['min_utme_score'] ?? '170'); ?></li>
                                <li>You selected FCT College of Nursing Sciences as your first choice</li>
                                <li>You have the required O&rsquo;Level credits (5 credits including English, Maths, Biology, Chemistry, Physics)</li>
                                <li>You are at least <?php echo $this->e($settings['key_value']['min_age'] ?? '16'); ?> years old</li>
                            </ul>
                        </div>

                        <button type="submit" class="btn btn-primary btn-lg" id="verifyBtn">
                            <span id="btnText"><i class="fas fa-check-circle"></i> Verify JAMB Number</span>
                            <span id="btnSpinner" style="display: none;" aria-hidden="true">
                                <span class="spinner-border" role="status"></span>
                                Verifying&hellip;
                            </span>
                        </button>
                    </form>

                    <?php endif; /* terms not empty */ ?>

                    <div class="divider"><span>OR</span></div>

                    <div class="text-center">
                        <p class="mb-2" style="color: var(--text-muted);">Already have an account?</p>
                        <a href="/applicant/login" class="btn btn-outline-primary">
                            <i class="fas fa-sign-in-alt"></i> Login to Continue Application
                        </a>
                    </div>

                </div><!-- /card-body -->
            </div><!-- /main-card -->

        <?php endif; /* portal not closed */ ?>

        <!-- ── Footer ───────────────────────────────────────────────────── -->
        <div class="app-footer">
            <p>&copy; <?php echo date('Y'); ?> FCT College of Nursing Sciences. All rights reserved.</p>
            <p>
                <i class="fas fa-phone-alt"></i> Support: 07039837749 |
                <i class="fas fa-envelope"></i> Email: <a href="mailto:info@fctcns.edu.ng">info@fctcns.edu.ng</a>
            </p>
        </div>

    </div><!-- /verification-container -->


    <!-- ── Terms Modal ──────────────────────────────────────────────────── -->
    <?php if (!empty($terms)): ?>
    <div class="modal fade" id="termsModal" tabindex="-1" aria-labelledby="termsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="termsModalLabel">
                        <i class="fas fa-file-contract me-2"></i>Terms and Conditions
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <h6><?php echo $this->e($terms['title'] ?? 'Terms and Conditions'); ?></h6>
                    <div class="terms-content">
                        <?php echo nl2br($this->e($terms['content'] ?? '')); ?>
                    </div>
                    <hr>
                    <p class="text-muted small mb-0">
                        <i class="fas fa-clock me-1"></i>
                        Version: <?php echo $this->e($terms['version'] ?? '1.0'); ?> |
                        Effective: <?php echo $this->e(isset($terms['effective_date']) ? date('jS F Y', strtotime($terms['effective_date'])) : '15th September 2025'); ?>
                    </p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>


    <!--
        Bootstrap JS — SRI hash retained (version-pinned, static CDN content).
        The nonce attribute pairs with the 'nonce-{value}' directive in the
        Content-Security-Policy HTTP header sent above.
    -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"
            integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz"
            crossorigin="anonymous"
            nonce="<?php echo $this->e($csp_nonce); ?>"></script>

    <script nonce="<?php echo $this->e($csp_nonce); ?>">
    /*
     * ===================================================================
     * IMPORTANT — JSON parse error root cause & fix
     * ===================================================================
     * The console error "Unexpected token '<', '<!DOCTYPE'... is not
     * valid JSON" means /apply/verify-jamb returned an HTML page instead
     * of JSON (typically a PHP error page, a redirect, or a 404).
     *
     * Fix in your controller method that handles POST /apply/verify-jamb:
     *
     *   // 1. Send the JSON content-type header BEFORE any output
     *   header('Content-Type: application/json; charset=utf-8');
     *
     *   // 2. Always echo valid JSON and exit — never fall through
     *   echo json_encode(['success' => true, 'data' => $payload]);
     *   exit;
     *
     *   // On error:
     *   http_response_code(422);
     *   echo json_encode(['success' => false, 'message' => 'Your error message']);
     *   exit;
     *
     * To diagnose: Open DevTools → Network → find the POST request →
     * click it → Response tab. Whatever is shown there is what the
     * browser received instead of JSON.
     * ===================================================================
     */

    // Read CSRF token from the <meta> tag placed in <head>
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    // Secure terms data passed from PHP (already JSON-encoded server-side)
    const TERMS_DATA = <?php echo $secureTermsData; ?>;

    // ── Auto-format JAMB field ───────────────────────────────────────────
    document.getElementById('jamb_number').addEventListener('input', function () {
        // Allow only alphanumeric uppercase characters
        this.value = this.value.toUpperCase().replace(/[^0-9A-Z]/g, '');
    });

    // ── Form submission ──────────────────────────────────────────────────
    document.getElementById('jambVerificationForm').addEventListener('submit', async function (e) {
        e.preventDefault();

        const jambNumber  = document.getElementById('jamb_number').value.trim().toUpperCase();
        const acceptTerms = document.getElementById('accept_terms').checked;

        // Client-side validation
        if (!jambNumber) {
            showAlert('Please enter your JAMB number.', 'danger');
            return;
        }

        if (!/^[0-9A-Z]{10,14}$/.test(jambNumber)) {
            showAlert('Invalid JAMB number format. It should be 10–14 alphanumeric characters.', 'danger');
            return;
        }

        if (!acceptTerms) {
            showAlert('You must accept the terms and conditions to proceed.', 'danger');
            return;
        }

        setLoading(true);

        try {
            const formData = new FormData(this);

            // ── POST to the verifyJamb() controller endpoint ──────────────
            //
            // The route MUST be registered in your router as:
            //   POST /apply/verify-jamb  →  PublicApplicationController@verifyJamb
            //
            // The controller method already sends header('Content-Type: application/json')
            // at its very first line, so the response will always be JSON.
            //
            let response;
            try {
                response = await fetch('/apply/verify-jamb', {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-Token': csrfToken
                    }
                });
            } catch (networkErr) {
                // fetch() itself threw — network failure, CORS, or CSP block
                console.error('[JAMB] fetch() threw:', networkErr);
                throw new Error('fetch_failed');
            }

            // ── Detect non-JSON response (HTML error page, redirect, 404) ──
            //
            // If you see this error:
            //   "Expected JSON but received text/html"
            //
            // Open DevTools → Network → find the POST to /apply/verify-jamb
            // → Response tab. That HTML is the actual error.
            //
            // Most likely causes:
            //   1. Route not registered → check your routes file for:
            //        ['POST', '/apply/verify-jamb', 'PublicApplicationController@verifyJamb']
            //   2. Session expired → controller redirects to login page (HTML)
            //   3. PHP error before header() call → check error_log
            //
            const contentType = response.headers.get('content-type') || '';
            if (!contentType.includes('application/json')) {
                const raw = await response.text();
                console.error(
                    '[JAMB] Expected JSON, got:', contentType,
                    '\nHTTP status:', response.status,
                    '\nFirst 500 chars of response:', raw.substring(0, 500),
                    '\n\nFIX: Ensure the route POST /apply/verify-jamb is registered',
                    'and that verifyJamb() sends header("Content-Type: application/json")',
                    'as its very first statement.'
                );
                // Give the developer the HTTP status to help diagnose
                if (response.status === 404) {
                    throw new Error('route_not_found');
                } else if (response.status === 302 || response.status === 301) {
                    throw new Error('redirected'); // session expired → login
                } else {
                    throw new Error('server_html');
                }
            }

            const data = await response.json();

            if (!response.ok || !data.success) {
                showAlert(data.message || 'Verification failed. Please check your JAMB number and try again.', 'danger');
                setLoading(false);
                return;
            }

            // Store verified data for the next step
            sessionStorage.setItem('jamb_data',    JSON.stringify(data.data));
            sessionStorage.setItem('jamb_verified', 'true');

            showAlert('JAMB verified successfully! Redirecting\u2026', 'success');

            setTimeout(function () {
                window.location.href = '/apply/step/2';
            }, 1500);

        } catch (error) {
            console.error('[JAMB verification] Error:', error.message);

            var msgMap = {
                'fetch_failed':    'Network error. Please check your internet connection and try again.',
                'route_not_found': 'Endpoint not found (404). Please contact support \u2014 the verification route is not registered.',
                'redirected':      'Your session may have expired. Please refresh the page and log in again.',
                'server_html':     'Server returned an unexpected response. Check the browser console for details.'
            };

            var msg = msgMap[error.message] || 'An unexpected error occurred. Please try again.';
            showAlert(msg, 'danger');
            setLoading(false);
        }
    });

    // ── Helpers ──────────────────────────────────────────────────────────

    function setLoading(loading) {
        document.getElementById('btnText').style.display    = loading ? 'none'         : 'inline-block';
        document.getElementById('btnSpinner').style.display = loading ? 'inline-block'  : 'none';
        document.getElementById('verifyBtn').disabled       = loading;
    }

    function showAlert(message, type) {
        const alertContainer = document.getElementById('alertContainer');

        const iconMap = { success: 'fa-check-circle', danger: 'fa-exclamation-circle', info: 'fa-info-circle', warning: 'fa-exclamation-triangle' };
        const icon    = iconMap[type] || 'fa-info-circle';

        // Sanitise the message — only allow plain text plus a handful of safe HTML entities
        const safeMessage = String(message).replace(/[<>]/g, function (ch) {
            return ch === '<' ? '&lt;' : '&gt;';
        // Allow pre-escaped entities like &hellip; from our own code
        }).replace(/&amp;(#?[a-zA-Z0-9]+;)/g, '&$1');

        alertContainer.innerHTML =
            '<div class="alert alert-' + type + ' alert-dismissible fade show" role="alert">' +
                '<i class="fas ' + icon + '"></i>' +
                '<span>' + safeMessage + '</span>' +
                '<button type="button" class="btn-close" onclick="this.parentElement.remove()" aria-label="Close">&times;</button>' +
            '</div>';

        // Auto-dismiss after 5 s
        setTimeout(function () {
            const el = alertContainer.querySelector('.alert');
            if (el) {
                el.style.transition = 'opacity 0.5s ease';
                el.style.opacity = '0';
                setTimeout(function () { if (el.parentNode) el.remove(); }, 500);
            }
        }, 5000);
    }

    // ── Guard against stale session data from a previous navigation ──────
    document.addEventListener('DOMContentLoaded', function () {
        const alreadyVerified = sessionStorage.getItem('jamb_verified') === 'true';
        const hasData         = !!sessionStorage.getItem('jamb_data');
        const fromVerify      = document.referrer.includes('/apply/verify-jamb');

        if (alreadyVerified && hasData && fromVerify) {
            showAlert('You already have verified JAMB data. Redirecting to application form&hellip;', 'info');
            setTimeout(function () { window.location.href = '/apply/step/2'; }, 2000);
        }
    });

    // Clear session data when user navigates back (bfcache restore)
    window.addEventListener('pageshow', function (event) {
        if (event.persisted) {
            sessionStorage.removeItem('jamb_verified');
            sessionStorage.removeItem('jamb_data');
        }
    });
    </script>
</body>
</html>
<?php
    } // end render()
}

// Instantiate and render
$view = new JambVerificationView();
$view->render(get_defined_vars());