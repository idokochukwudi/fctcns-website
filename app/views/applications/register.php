<?php
/**
 * Registration View - Step 1
 * @package FCTCNS
 */

extract($data ?? []);

if (!function_exists('e')) {
    function e($text) { return htmlspecialchars($text ?? '', ENT_QUOTES, 'UTF-8'); }
}

$csrf_token    = $csrf_token    ?? '';
$terms         = $terms         ?? [];
$portal_closed = $portal_closed ?? false;
$portal_message= $portal_message?? '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0, viewport-fit=cover">
    <meta name="description" content="Create Account - FCT College of Nursing Sciences">
    <title>Create Account — FCT College of Nursing Sciences</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <!-- Source Serif 4: readable serif for headings; Outfit: clean UI sans-serif -->
    <link href="https://fonts.googleapis.com/css2?family=Source+Serif+4:wght@400;500;600;700&family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        /* ── Reset ────────────────────────────────────────────── */
        *, *::before, *::after { margin: 0; padding: 0; box-sizing: border-box; }

        /* ── Tokens ───────────────────────────────────────────── */
        :root {
            --pu:          #6E026F;
            --pu-dark:     #500150;
            --pu-deeper:   #380038;
            --pu-mid:      #8a0d8b;
            --pu-light:    #c070c1;
            --pu-pale:     #f9edf9;
            --pu-bg:       #f3e2f3;

            --gold:        #c8860a;
            --gold-light:  #e0a020;

            --green:       #1a6b45;
            --green-bg:    #edf9f3;
            --red:         #b91c1c;
            --red-bg:      #fdf2f2;
            --blue:        #1d4ed8;
            --blue-bg:     #eff6ff;
            --amber:       #d97706;
            --amber-bg:    #fffbeb;

            --text:        #1a0a1a;
            --text-body:   #3b1e3c;
            --text-muted:  #7a587a;
            --border:      #e2d0e2;
            --border-dark: #c9aec9;
            --bg:          #f4eef4;
            --surface:     #ffffff;

            --font-serif:  'Source Serif 4', Georgia, serif;
            --font-ui:     'Outfit', system-ui, sans-serif;

            --shadow:      0 2px 12px rgba(110,2,111,.08);
            --shadow-md:   0 6px 24px rgba(110,2,111,.12);
            --shadow-lg:   0 12px 40px rgba(110,2,111,.16);

            --radius-sm:   6px;
            --radius-md:   10px;
            --radius-lg:   16px;
            --radius-xl:   22px;
        }

        /* ── Base ─────────────────────────────────────────────── */
        html { font-size: 16px; -webkit-text-size-adjust: 100%; }

        body {
            font-family: var(--font-ui);
            background: var(--bg);
            /* Subtle geometric pattern instead of loud gradient */
            background-image:
                radial-gradient(ellipse 80% 60% at 10% 0%,   rgba(110,2,111,.07) 0%, transparent 60%),
                radial-gradient(ellipse 60% 50% at 90% 100%, rgba(110,2,111,.05) 0%, transparent 55%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 28px 16px 48px;
            color: var(--text-body);
        }

        /* ── Wrapper ──────────────────────────────────────────── */
        .reg-wrap {
            width: 100%;
            max-width: 520px;
            animation: rise .42s cubic-bezier(.22,.61,.36,1) both;
        }

        @keyframes rise {
            from { opacity: 0; transform: translateY(18px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        /* ── Step tracker ─────────────────────────────────────── */
        .step-track {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0;
            margin-bottom: 22px;
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: 50px;
            padding: 10px 18px;
            box-shadow: var(--shadow);
        }

        .st-step {
            display: flex;
            align-items: center;
            gap: 6px;
            flex-shrink: 0;
        }

        .st-num {
            width: 26px; height: 26px;
            border-radius: 50%;
            border: 2px solid var(--border-dark);
            background: transparent;
            display: flex; align-items: center; justify-content: center;
            font-family: var(--font-ui);
            font-size: 10.5px;
            font-weight: 700;
            color: var(--text-muted);
            transition: all .25s;
            flex-shrink: 0;
        }

        .st-step.active .st-num {
            background: var(--pu);
            border-color: var(--pu);
            color: #fff;
            box-shadow: 0 0 0 3px rgba(110,2,111,.18);
        }

        .st-step.done .st-num {
            background: var(--green);
            border-color: var(--green);
            color: #fff;
        }

        .st-label {
            font-size: 10px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: .04em;
            color: var(--text-muted);
            white-space: nowrap;
        }

        .st-step.active .st-label { color: var(--pu); }
        .st-step.done   .st-label { color: var(--green); }

        .st-line {
            flex: 1;
            height: 1px;
            background: var(--border);
            margin: 0 6px;
            min-width: 8px;
        }

        .st-line.done { background: var(--green); opacity: .6; }

        /* Hide labels on very small screens, keep numbers */
        @media (max-width: 480px) {
            .step-track { padding: 8px 12px; }
            .st-label   { display: none; }
            .st-num     { width: 24px; height: 24px; font-size: 10px; }
        }

        /* ── Main card ────────────────────────────────────────── */
        .reg-card {
            background: var(--surface);
            border-radius: var(--radius-xl);
            border: 1px solid var(--border);
            box-shadow: var(--shadow-lg);
            overflow: hidden;
        }

        /* Card header */
        .reg-card-head {
            background: linear-gradient(150deg, var(--pu-deeper) 0%, var(--pu-dark) 55%, var(--pu) 100%);
            padding: 32px 36px 28px;
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        /* Subtle decorative ring */
        .reg-card-head::after {
            content: '';
            position: absolute;
            right: -60px; top: -60px;
            width: 220px; height: 220px;
            border-radius: 50%;
            border: 40px solid rgba(255,255,255,.05);
            pointer-events: none;
        }

        .head-step-pill {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            background: rgba(255,255,255,.12);
            border: 1px solid rgba(255,255,255,.22);
            border-radius: 50px;
            padding: 5px 14px;
            font-family: var(--font-ui);
            font-size: 11px;
            font-weight: 600;
            color: rgba(255,255,255,.85);
            letter-spacing: .05em;
            text-transform: uppercase;
            margin-bottom: 14px;
        }

        .head-icon {
            width: 52px; height: 52px;
            background: rgba(255,255,255,.12);
            border: 1.5px solid rgba(255,255,255,.22);
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            margin: 0 auto 14px;
            font-size: 1.35rem;
            color: rgba(255,255,255,.9);
        }

        .reg-card-head h2 {
            font-family: var(--font-serif);
            font-size: 1.45rem;
            font-weight: 600;
            color: #fff;
            margin-bottom: 6px;
            line-height: 1.25;
        }

        .reg-card-head p {
            font-family: var(--font-ui);
            font-size: 13px;
            color: rgba(255,255,255,.6);
            margin: 0;
        }

        /* Card body */
        .reg-card-body {
            padding: 32px 36px;
        }

        @media (max-width: 480px) {
            .reg-card-head { padding: 24px 22px 20px; }
            .reg-card-body { padding: 24px 20px; }
        }

        /* Card footer */
        .reg-card-foot {
            padding: 13px 36px;
            background: var(--pu-pale);
            border-top: 1px solid var(--border);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 7px;
            font-family: var(--font-ui);
            font-size: 12px;
            color: var(--text-muted);
        }

        .reg-card-foot i { color: var(--pu-light); font-size: 11px; }

        /* ── Alerts ───────────────────────────────────────────── */
        .alert {
            display: flex;
            align-items: flex-start;
            gap: 10px;
            padding: 12px 16px;
            border-radius: var(--radius-md);
            margin-bottom: 18px;
            font-family: var(--font-ui);
            font-size: 13.5px;
            line-height: 1.5;
            border: 1px solid transparent;
            animation: popIn .25s ease;
        }

        @keyframes popIn {
            from { opacity: 0; transform: translateY(-6px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        .alert-danger  { background: var(--red-bg);   border-color: #fca5a5; color: #7f1d1d; border-left: 3px solid var(--red); }
        .alert-success { background: var(--green-bg);  border-color: #a7f3d0; color: #065f46; border-left: 3px solid var(--green); }

        .alert .alert-icon { flex-shrink: 0; margin-top: 1px; }
        .alert-close {
            margin-left: auto; flex-shrink: 0;
            background: none; border: none;
            font-size: 16px; cursor: pointer;
            opacity: .45; color: currentColor;
            line-height: 1; padding: 0;
        }
        .alert-close:hover { opacity: .9; }

        /* ── Form ─────────────────────────────────────────────── */
        .form-group { margin-bottom: 18px; }
        .form-group:last-of-type { margin-bottom: 0; }

        .form-lbl {
            display: flex;
            align-items: center;
            gap: 7px;
            font-family: var(--font-ui);
            font-size: 12.5px;
            font-weight: 600;
            color: var(--pu-dark);
            margin-bottom: 6px;
        }

        .form-lbl i { color: var(--pu-light); font-size: 11px; }
        .form-lbl .req { color: var(--red); margin-left: 1px; }

        .form-ctrl {
            width: 100%;
            border: 1.5px solid var(--border-dark);
            border-radius: var(--radius-md);
            padding: 10px 13px;
            font-family: var(--font-ui);
            font-size: 14px;
            color: var(--text);
            background: var(--surface);
            transition: border-color .2s, box-shadow .2s;
            outline: none;
            line-height: 1.5;
        }

        .form-ctrl::placeholder { color: #b09ab0; font-size: 13.5px; }

        .form-ctrl:focus {
            border-color: var(--pu);
            box-shadow: 0 0 0 3px rgba(110,2,111,.11);
        }

        .form-ctrl.invalid { border-color: var(--red); }
        .form-ctrl.valid   { border-color: var(--green); }

        .form-hint {
            font-family: var(--font-ui);
            font-size: 11.5px;
            color: var(--text-muted);
            margin-top: 4px;
            display: flex;
            align-items: center;
            gap: 4px;
        }

        /* ── Password strength ────────────────────────────────── */
        .pw-strength {
            margin-top: 8px;
            height: 3px;
            background: var(--border);
            border-radius: 2px;
            overflow: hidden;
        }

        .pw-strength-bar {
            height: 100%;
            width: 0;
            border-radius: 2px;
            transition: width .3s, background .3s;
        }

        .pw-reqs {
            display: flex;
            flex-wrap: wrap;
            gap: 10px 18px;
            margin-top: 7px;
        }

        .pw-req {
            font-family: var(--font-ui);
            font-size: 11.5px;
            color: var(--text-muted);
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .pw-req i { font-size: 9px; }
        .pw-req.met { color: var(--green); }

        /* ── Checkbox ─────────────────────────────────────────── */
        .check-group {
            display: flex;
            align-items: flex-start;
            gap: 9px;
            margin: 18px 0 22px;
        }

        .check-group input[type="checkbox"] {
            width: 17px; height: 17px;
            margin-top: 2px;
            accent-color: var(--pu);
            flex-shrink: 0;
            cursor: pointer;
        }

        .check-group label {
            font-family: var(--font-ui);
            font-size: 13.5px;
            color: var(--text-body);
            cursor: pointer;
            line-height: 1.5;
        }

        .check-group label a {
            color: var(--pu);
            text-decoration: none;
            font-weight: 600;
        }

        .check-group label a:hover { text-decoration: underline; }

        /* ── Buttons ──────────────────────────────────────────── */
        .btn-pu {
            width: 100%;
            padding: 11px 24px;
            background: linear-gradient(135deg, var(--pu-dark), var(--pu));
            color: #fff;
            border: none;
            border-radius: var(--radius-md);
            font-family: var(--font-ui);
            font-size: 14.5px;
            font-weight: 700;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            transition: transform .18s, box-shadow .18s, opacity .18s;
            box-shadow: 0 3px 14px rgba(110,2,111,.28);
            letter-spacing: .02em;
        }

        .btn-pu:hover:not(:disabled) {
            transform: translateY(-1px);
            box-shadow: 0 5px 20px rgba(110,2,111,.38);
        }

        .btn-pu:disabled { opacity: .65; cursor: not-allowed; }

        .btn-outline-pu {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            padding: 9px 22px;
            background: transparent;
            border: 1.5px solid var(--border-dark);
            border-radius: var(--radius-md);
            color: var(--pu);
            font-family: var(--font-ui);
            font-size: 13.5px;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
            transition: background .18s, border-color .18s;
        }

        .btn-outline-pu:hover {
            background: var(--pu-pale);
            border-color: var(--pu);
            color: var(--pu-dark);
        }

        /* Spinner */
        .spin {
            width: 16px; height: 16px;
            border: 2px solid rgba(255,255,255,.35);
            border-top-color: #fff;
            border-radius: 50%;
            animation: spin .7s linear infinite;
            flex-shrink: 0;
        }

        @keyframes spin { to { transform: rotate(360deg); } }

        /* ── Divider ──────────────────────────────────────────── */
        .divider {
            display: flex;
            align-items: center;
            gap: 12px;
            margin: 22px 0 18px;
            font-family: var(--font-ui);
            font-size: 12px;
            color: var(--text-muted);
        }

        .divider::before,
        .divider::after {
            content: '';
            flex: 1;
            height: 1px;
            background: var(--border);
        }

        /* ── Login link ───────────────────────────────────────── */
        .login-row {
            text-align: center;
        }

        .login-row p {
            font-family: var(--font-ui);
            font-size: 13px;
            color: var(--text-muted);
            margin-bottom: 10px;
        }

        /* ── Portal closed ────────────────────────────────────── */
        .portal-closed {
            background: var(--surface);
            border-radius: var(--radius-xl);
            border: 1px solid var(--border);
            padding: 48px 36px;
            text-align: center;
            box-shadow: var(--shadow-md);
        }

        .portal-closed .pc-icon {
            width: 64px; height: 64px;
            background: var(--amber-bg);
            border: 1px solid #fde68a;
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            margin: 0 auto 18px;
            font-size: 1.6rem;
            color: var(--amber);
        }

        .portal-closed h2 {
            font-family: var(--font-serif);
            color: var(--pu-dark);
            font-size: 1.35rem;
            margin-bottom: 10px;
        }

        .portal-closed p { font-size: 14px; color: var(--text-body); line-height: 1.6; }

        /* ── Modal ────────────────────────────────────────────── */
        .modal-content { border-radius: var(--radius-lg); overflow: hidden; border: 1px solid var(--border); }
        .modal-header  { background: linear-gradient(135deg, var(--pu-deeper), var(--pu)); border-bottom: none; }
        .modal-header .modal-title { font-family: var(--font-serif); color: #fff; font-size: 1.1rem; }
        .modal-body    { font-family: var(--font-ui); font-size: 13.5px; color: var(--text-body); line-height: 1.7; }
        .modal-footer  { border-top: 1px solid var(--border); background: var(--pu-pale); }

        /* ── Responsive ───────────────────────────────────────── */
        @media (max-width: 360px) {
            body { padding: 16px 10px 32px; }
            .reg-card-head h2 { font-size: 1.25rem; }
        }
    </style>
</head>
<body>

<div class="reg-wrap">

    <!-- Step tracker -->
    <div class="step-track" role="navigation" aria-label="Application steps">
        <div class="st-step active" aria-current="step">
            <div class="st-num">1</div>
            <span class="st-label">Account</span>
        </div>
        <div class="st-line"></div>
        <div class="st-step">
            <div class="st-num">2</div>
            <span class="st-label">JAMB</span>
        </div>
        <div class="st-line"></div>
        <div class="st-step">
            <div class="st-num">3</div>
            <span class="st-label">Form</span>
        </div>
        <div class="st-line"></div>
        <div class="st-step">
            <div class="st-num">4</div>
            <span class="st-label">Payment</span>
        </div>
        <div class="st-line"></div>
        <div class="st-step">
            <div class="st-num">5</div>
            <span class="st-label">Slip</span>
        </div>
    </div>

    <!-- Alert container -->
    <div id="alertContainer" role="alert" aria-live="polite"></div>

    <?php if ($portal_closed): ?>

        <!-- Portal closed -->
        <div class="portal-closed">
            <div class="pc-icon"><i class="fas fa-door-closed"></i></div>
            <h2>Application Portal Closed</h2>
            <p><?php echo e($portal_message); ?></p>
            <p style="color:var(--text-muted); font-size:13px; margin-top:10px;">
                The next admissions cycle will be announced on this portal.
            </p>
        </div>

    <?php else: ?>

        <!-- Main registration card -->
        <div class="reg-card">

            <!-- Header -->
            <div class="reg-card-head">
                <div class="head-step-pill">
                    <i class="fas fa-arrow-right" style="font-size:9px;"></i>
                    Step 1 of 5
                </div>
                <div class="head-icon"><i class="fas fa-user-plus"></i></div>
                <h2>Create Account</h2>
                <p>Start your application journey for 2025/2026 admissions</p>
            </div>

            <!-- Body -->
            <div class="reg-card-body">

                <!-- Server-side flash messages -->
                <?php if (!empty($_SESSION['flash_error'])): ?>
                    <div class="alert alert-danger">
                        <i class="fas fa-exclamation-circle alert-icon"></i>
                        <span><?php echo e($_SESSION['flash_error']); ?></span>
                        <button class="alert-close" onclick="this.closest('.alert').remove()" aria-label="Dismiss">&times;</button>
                    </div>
                    <?php unset($_SESSION['flash_error']); ?>
                <?php endif; ?>

                <?php if (!empty($_SESSION['flash_success'])): ?>
                    <div class="alert alert-success">
                        <i class="fas fa-check-circle alert-icon"></i>
                        <span><?php echo e($_SESSION['flash_success']); ?></span>
                        <button class="alert-close" onclick="this.closest('.alert').remove()" aria-label="Dismiss">&times;</button>
                    </div>
                    <?php unset($_SESSION['flash_success']); ?>
                <?php endif; ?>

                <!-- Registration form -->
                <form method="POST" action="/apply/register" id="registrationForm" novalidate>
                    <input type="hidden" name="csrf_token" value="<?php echo e($csrf_token); ?>">

                    <!-- Email -->
                    <div class="form-group">
                        <label class="form-lbl" for="email">
                            <i class="fas fa-envelope"></i> Email Address <span class="req">*</span>
                        </label>
                        <input type="email" class="form-ctrl" id="email" name="email"
                               value="<?php echo e($_POST['email'] ?? ''); ?>"
                               placeholder="your@email.com" required autocomplete="email">
                        <p class="form-hint"><i class="fas fa-info-circle"></i> A verification link will be sent to this address</p>
                    </div>

                    <!-- Phone -->
                    <div class="form-group">
                        <label class="form-lbl" for="phone">
                            <i class="fas fa-phone"></i> Phone Number <span class="req">*</span>
                        </label>
                        <input type="tel" class="form-ctrl" id="phone" name="phone"
                               value="<?php echo e($_POST['phone'] ?? ''); ?>"
                               placeholder="08012345678" pattern="[0-9]{11}" maxlength="11"
                               required autocomplete="tel">
                        <p class="form-hint"><i class="fas fa-info-circle"></i> 11-digit Nigerian mobile number</p>
                    </div>

                    <!-- Password -->
                    <div class="form-group">
                        <label class="form-lbl" for="password">
                            <i class="fas fa-lock"></i> Password <span class="req">*</span>
                        </label>
                        <input type="password" class="form-ctrl" id="password" name="password"
                               minlength="8" required autocomplete="new-password">
                        <div class="pw-strength"><div class="pw-strength-bar" id="pwBar"></div></div>
                        <div class="pw-reqs">
                            <span class="pw-req" id="req-len"><i class="fas fa-circle"></i> 8+ characters</span>
                            <span class="pw-req" id="req-num"><i class="fas fa-circle"></i> 1 number</span>
                            <span class="pw-req" id="req-let"><i class="fas fa-circle"></i> 1 letter</span>
                        </div>
                    </div>

                    <!-- Confirm password -->
                    <div class="form-group">
                        <label class="form-lbl" for="confirm_password">
                            <i class="fas fa-lock"></i> Confirm Password <span class="req">*</span>
                        </label>
                        <input type="password" class="form-ctrl" id="confirm_password" name="confirm_password"
                               required autocomplete="new-password">
                        <p class="form-hint" id="pwMatchHint" style="display:none; color:var(--red);">
                            <i class="fas fa-times-circle"></i> Passwords do not match
                        </p>
                    </div>

                    <!-- Terms -->
                    <div class="check-group">
                        <input type="checkbox" id="terms" name="terms" required>
                        <label for="terms">
                            I agree to the
                            <a href="#" data-bs-toggle="modal" data-bs-target="#termsModal">Terms and Conditions</a>
                            <span style="color:var(--red);">*</span>
                        </label>
                    </div>

                    <!-- Submit -->
                    <button type="submit" class="btn-pu" id="submitBtn">
                        <span id="btnText"><i class="fas fa-user-plus"></i> Create Account</span>
                        <span id="btnSpinner" style="display:none;">
                            <span class="spin"></span> Creating account&hellip;
                        </span>
                    </button>

                    <!-- Divider -->
                    <div class="divider">or</div>

                    <!-- Login link -->
                    <div class="login-row">
                        <p>Already have an account?</p>
                        <a href="/applicant/login" class="btn-outline-pu">
                            <i class="fas fa-sign-in-alt"></i> Login Here
                        </a>
                    </div>

                </form>
            </div><!-- /card-body -->

            <!-- Footer -->
            <div class="reg-card-foot">
                <i class="fas fa-shield-alt"></i>
                Your information is secure and encrypted
            </div>

        </div><!-- /reg-card -->

    <?php endif; ?>

</div><!-- /reg-wrap -->


<!-- Terms modal -->
<?php if (!empty($terms)): ?>
<div class="modal fade" id="termsModal" tabindex="-1" aria-labelledby="termsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="termsModalLabel">
                    <i class="fas fa-file-contract me-2"></i>Terms and Conditions
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <h6 style="font-family:var(--font-serif); color:var(--pu-dark); margin-bottom:12px;">
                    <?php echo e($terms['title'] ?? 'Terms and Conditions'); ?>
                </h6>
                <div><?php echo nl2br(e($terms['content'] ?? '')); ?></div>
                <hr style="border-color:var(--border); margin:16px 0;">
                <p style="font-size:12px; color:var(--text-muted); margin:0;">
                    <i class="fas fa-clock me-1"></i>
                    Version: <?php echo e($terms['version'] ?? '1.0'); ?> &nbsp;|&nbsp;
                    Effective: <?php echo isset($terms['effective_date']) ? date('jS F Y', strtotime($terms['effective_date'])) : '15th September 2025'; ?>
                </p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn-outline-pu" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
(function () {
    'use strict';

    /* ── Elements ────────────────────────────────────────────── */
    const pwInput  = document.getElementById('password');
    const cfInput  = document.getElementById('confirm_password');
    const pwBar    = document.getElementById('pwBar');
    const reqLen   = document.getElementById('req-len');
    const reqNum   = document.getElementById('req-num');
    const reqLet   = document.getElementById('req-let');
    const matchHint= document.getElementById('pwMatchHint');
    const form     = document.getElementById('registrationForm');
    const submitBtn= document.getElementById('submitBtn');
    const alertCon = document.getElementById('alertContainer');

    /* ── Password strength ───────────────────────────────────── */
    function markReq(el, met, text) {
        el.classList.toggle('met', met);
        el.innerHTML = met
            ? `<i class="fas fa-check-circle"></i> ${text}`
            : `<i class="fas fa-circle"></i> ${text}`;
    }

    pwInput.addEventListener('input', function () {
        const v = this.value;
        const hasLen = v.length >= 8;
        const hasNum = /\d/.test(v);
        const hasLet = /[a-zA-Z]/.test(v);

        markReq(reqLen, hasLen, '8+ characters');
        markReq(reqNum, hasNum, '1 number');
        markReq(reqLet, hasLet, '1 letter');

        const score = [hasLen, hasNum, hasLet].filter(Boolean).length;
        const colours = ['', '#b91c1c', '#d97706', '#1a6b45'];
        const widths  = ['0%', '33%', '66%', '100%'];
        pwBar.style.width      = widths[score];
        pwBar.style.background = colours[score];
    });

    /* ── Confirm password ─────────────────────────────────────── */
    function checkMatch() {
        const ok = cfInput.value === '' || cfInput.value === pwInput.value;
        matchHint.style.display = ok ? 'none' : 'flex';
        cfInput.classList.toggle('invalid', !ok && cfInput.value !== '');
        cfInput.classList.toggle('valid',    ok && cfInput.value !== '');
    }

    cfInput.addEventListener('input', checkMatch);
    pwInput.addEventListener('input', checkMatch);

    /* ── Phone: digits only ───────────────────────────────────── */
    document.getElementById('phone').addEventListener('input', function () {
        this.value = this.value.replace(/\D/g, '').slice(0, 11);
    });

    /* ── Show alert ───────────────────────────────────────────── */
    function showAlert(msg, type) {
        const icon = type === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle';
        alertCon.innerHTML = `
            <div class="alert alert-${type}">
                <i class="fas ${icon} alert-icon"></i>
                <span>${msg}</span>
                <button class="alert-close" onclick="this.closest('.alert').remove()" aria-label="Dismiss">&times;</button>
            </div>`;
        setTimeout(() => {
            const el = alertCon.querySelector('.alert');
            if (el) { el.style.transition = 'opacity .4s'; el.style.opacity = '0'; setTimeout(() => el.remove(), 420); }
        }, 5500);
    }

    /* ── Form submit ──────────────────────────────────────────── */
    form.addEventListener('submit', function (e) {
        const email = document.getElementById('email').value.trim();
        const phone = document.getElementById('phone').value.trim();
        const pw    = pwInput.value;
        const cf    = cfInput.value;
        const terms = document.getElementById('terms').checked;

        let err = '';

        if (!email)                                  err = 'Please enter your email address.';
        else if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) err = 'Please enter a valid email address.';
        else if (!phone)                             err = 'Please enter your phone number.';
        else if (!/^\d{11}$/.test(phone))            err = 'Phone number must be exactly 11 digits.';
        else if (pw.length < 8)                      err = 'Password must be at least 8 characters.';
        else if (pw !== cf)                          err = 'Passwords do not match.';
        else if (!terms)                             err = 'You must accept the Terms and Conditions.';

        if (err) {
            e.preventDefault();
            showAlert(err, 'danger');
            return;
        }

        /* Loading state */
        document.getElementById('btnText').style.display   = 'none';
        document.getElementById('btnSpinner').style.display= 'inline-flex';
        submitBtn.disabled = true;
    });

})();
</script>
</body>
</html>