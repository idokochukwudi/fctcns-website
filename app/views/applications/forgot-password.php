<?php
/**
 * Forgot Password View
 * Redesigned to match portal navy/gold design system.
 *
 * @package FCTCNS
 */

extract($data ?? []);

if (!function_exists('e')) {
    function e($text) {
        return htmlspecialchars($text ?? '', ENT_QUOTES, 'UTF-8');
    }
}

$csrf_token = $csrf_token ?? '';
$baseUrl    = $baseUrl    ?? '/';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0">
    <title>Forgot Password &mdash; FCT College of Nursing Sciences</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600;700&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        /* ── Reset ─────────────────────────────────────────────── */
        *, *::before, *::after { margin: 0; padding: 0; box-sizing: border-box; }

        /* ── Tokens ─────────────────────────────────────────────── */
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
            --white:       #FFFFFF;
            --off-white:   #F8FAFD;
            --border:      #E2E8F4;
            --border-dark: #C8D3E8;
            --text-dark:   #0F1B35;
            --text-body:   #374160;
            --text-muted:  #7A86A0;
        }

        /* ── Body ───────────────────────────────────────────────── */
        html, body { height: 100%; }

        body {
            font-family: 'DM Sans', -apple-system, sans-serif;
            background: var(--navy);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 24px 16px;
            position: relative;
            overflow-x: hidden;
        }

        /* Decorative rings */
        body::before,
        body::after {
            content: '';
            position: fixed;
            border-radius: 50%;
            pointer-events: none;
        }
        body::before {
            width: 600px; height: 600px;
            border: 1px solid rgba(200,150,58,0.08);
            top: -200px; right: -150px;
        }
        body::after {
            width: 400px; height: 400px;
            border: 1px solid rgba(200,150,58,0.06);
            bottom: -150px; left: -100px;
        }

        /* ── Wrapper ─────────────────────────────────────────────── */
        .forgot-wrap {
            width: 100%;
            max-width: 440px;
            margin: 0 auto;
            position: relative;
            z-index: 1;
            animation: rise 0.45s cubic-bezier(0.22,0.61,0.36,1) both;
        }

        @keyframes rise {
            from { opacity: 0; transform: translateY(20px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        /* ── Card ────────────────────────────────────────────────── */
        .forgot-card {
            background: var(--white);
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 32px 80px rgba(0,0,0,0.4), 0 0 0 1px rgba(255,255,255,0.05);
        }

        /* ── Card Header ─────────────────────────────────────────── */
        .card-head {
            background: var(--navy-mid);
            padding: 32px 36px 28px;
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        .card-head::before {
            content: '';
            position: absolute;
            top: 0; left: 0; right: 0;
            height: 3px;
            background: linear-gradient(90deg, var(--gold), var(--gold-light), var(--gold));
        }

        .card-head::after {
            content: '';
            position: absolute;
            inset: 0;
            background-image: repeating-linear-gradient(
                45deg, transparent, transparent 40px,
                rgba(255,255,255,0.012) 40px, rgba(255,255,255,0.012) 41px
            );
            pointer-events: none;
        }

        .card-head-emblem {
            position: relative;
            z-index: 1;
            width: 56px; height: 56px;
            background: rgba(200,150,58,0.12);
            border: 1.5px solid rgba(200,150,58,0.35);
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            margin: 0 auto 14px;
            color: var(--gold-light);
            font-size: 1.3rem;
        }

        .card-head h1 {
            position: relative;
            z-index: 1;
            font-family: 'Playfair Display', Georgia, serif;
            font-size: 1.25rem;
            font-weight: 700;
            color: #fff;
            line-height: 1.3;
            margin-bottom: 6px;
        }

        .card-head p {
            position: relative;
            z-index: 1;
            font-size: 12px;
            color: rgba(255,255,255,0.45);
            margin: 0;
            line-height: 1.5;
        }

        .card-head-rule {
            position: relative;
            z-index: 1;
            width: 36px;
            height: 2px;
            background: var(--gold);
            border-radius: 2px;
            margin: 12px auto 0;
        }

        /* ── Card Body ───────────────────────────────────────────── */
        .card-body {
            padding: 32px 36px 28px;
        }

        /* ── Info banner ─────────────────────────────────────────── */
        .info-banner {
            display: flex;
            align-items: flex-start;
            gap: 10px;
            background: var(--off-white);
            border: 1px solid var(--border);
            border-left: 3px solid var(--gold);
            border-radius: 10px;
            padding: 12px 14px;
            margin-bottom: 22px;
            font-size: 13px;
            color: var(--text-body);
            line-height: 1.5;
        }

        .info-banner i {
            color: var(--gold);
            font-size: .9rem;
            flex-shrink: 0;
            margin-top: 1px;
        }

        /* ── Alerts ──────────────────────────────────────────────── */
        .alert {
            display: flex;
            align-items: flex-start;
            gap: 10px;
            padding: 12px 14px;
            border-radius: 10px;
            margin-bottom: 20px;
            font-size: 13.5px;
            border: 1px solid transparent;
            animation: popIn .3s ease;
        }

        @keyframes popIn {
            from { opacity: 0; transform: translateY(-6px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        .alert-danger  { background: var(--red-light);  border-color: rgba(192,57,43,.2);  color: #7f1d1d; }
        .alert-success { background: var(--teal-light); border-color: rgba(29,138,122,.2); color: #134e42; }
        .alert-info    { background: #EFF4FF;           border-color: rgba(37,99,235,.15); color: #1e3a8a; }

        .alert i { font-size: .95rem; flex-shrink: 0; margin-top: 1px; }
        .alert-danger  i { color: var(--red); }
        .alert-success i { color: var(--teal); }
        .alert-info    i { color: #2563EB; }

        .alert-close {
            margin-left: auto;
            background: none;
            border: none;
            cursor: pointer;
            color: inherit;
            opacity: .45;
            font-size: 1rem;
            line-height: 1;
            padding: 0;
            flex-shrink: 0;
        }
        .alert-close:hover { opacity: .9; }

        /* ── Form ────────────────────────────────────────────────── */
        .form-group { margin-bottom: 18px; }

        .form-label {
            display: block;
            font-size: 12.5px;
            font-weight: 600;
            color: var(--text-dark);
            margin-bottom: 6px;
            letter-spacing: .2px;
        }

        .form-label .req { color: var(--red); margin-left: 2px; }

        .input-wrap {
            position: relative;
            display: flex;
            align-items: center;
        }

        .input-wrap .input-icon {
            position: absolute;
            left: 13px;
            color: var(--text-muted);
            font-size: .85rem;
            pointer-events: none;
        }

        .form-control {
            width: 100%;
            padding: 11px 14px 11px 36px;
            border: 1.5px solid var(--border-dark);
            border-radius: 10px;
            font-size: 13.5px;
            font-family: 'DM Sans', sans-serif;
            color: var(--text-dark);
            background: var(--white);
            transition: border-color .2s, box-shadow .2s;
        }

        .form-control::placeholder { color: var(--text-muted); font-size: 13px; }

        .form-control:focus {
            border-color: var(--navy-mid);
            box-shadow: 0 0 0 3px rgba(26,45,85,.1);
            outline: none;
        }

        .form-control.is-invalid {
            border-color: var(--red);
            background: #fff8f8;
        }

        .invalid-msg {
            font-size: 11.5px;
            color: var(--red);
            margin-top: 4px;
            display: none;
        }

        /* ── Submit button ───────────────────────────────────────── */
        .btn-reset {
            width: 100%;
            padding: 12px;
            background: var(--navy);
            color: #fff;
            border: none;
            border-radius: 10px;
            font-family: 'DM Sans', sans-serif;
            font-size: .95rem;
            font-weight: 600;
            cursor: pointer;
            display: flex; align-items: center; justify-content: center; gap: 8px;
            transition: all .25s;
            box-shadow: 0 4px 14px rgba(15,27,53,.25);
            letter-spacing: .2px;
            margin-top: 4px;
        }

        .btn-reset:hover:not(:disabled) {
            background: var(--navy-light);
            transform: translateY(-1px);
            box-shadow: 0 8px 22px rgba(15,27,53,.3);
        }

        .btn-reset:disabled { opacity: .6; cursor: not-allowed; transform: none; }

        /* ── Spinner ─────────────────────────────────────────────── */
        .spinner {
            display: inline-block;
            width: 14px; height: 14px;
            border: 2px solid rgba(255,255,255,.35);
            border-top-color: #fff;
            border-radius: 50%;
            animation: spin .7s linear infinite;
        }

        @keyframes spin { to { transform: rotate(360deg); } }

        /* ── Divider ─────────────────────────────────────────────── */
        .divider {
            display: flex;
            align-items: center;
            gap: 12px;
            margin: 22px 0 18px;
            color: var(--text-muted);
            font-size: 12px;
        }

        .divider::before,
        .divider::after {
            content: '';
            flex: 1;
            height: 1px;
            background: var(--border);
        }

        /* ── Secondary actions ───────────────────────────────────── */
        .actions-block {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .btn-outline {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 7px;
            width: 100%;
            padding: 11px;
            background: transparent;
            color: var(--navy);
            border: 1.5px solid var(--border-dark);
            border-radius: 10px;
            font-family: 'DM Sans', sans-serif;
            font-size: .88rem;
            font-weight: 600;
            text-decoration: none;
            cursor: pointer;
            transition: all .2s;
        }

        .btn-outline:hover {
            background: var(--off-white);
            border-color: var(--navy);
            color: var(--navy);
        }

        /* ── Return link ─────────────────────────────────────────── */
        .page-foot {
            margin-top: 16px;
            text-align: center;
        }

        .page-foot a {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            font-size: 12.5px;
            font-weight: 600;
            color: var(--gold);
            text-decoration: none;
            transition: color .2s;
        }

        .page-foot a:hover { color: var(--gold-light); }

        /* ── Responsive ──────────────────────────────────────────── */
        @media (max-width: 480px) {
            .card-body { padding: 24px 22px 22px; }
            .card-head { padding: 26px 22px 22px; }
        }
    </style>
</head>
<body>

    <div class="forgot-wrap">

        <div class="forgot-card">

            <!-- Header -->
            <div class="card-head">
                <div class="card-head-emblem">
                    <i class="fas fa-key"></i>
                </div>
                <h1>Forgot Password</h1>
                <p>Reset your password to regain access to your account</p>
                <div class="card-head-rule"></div>
            </div>

            <!-- Body -->
            <div class="card-body">

                <!-- Info banner -->
                <div class="info-banner">
                    <i class="fas fa-info-circle"></i>
                    <span>Enter your registered email address and we'll send you a link to reset your password.</span>
                </div>

                <!-- Flash messages -->
                <?php if (!empty($_SESSION['flash_success'])): ?>
                <div class="alert alert-success" role="alert">
                    <i class="fas fa-check-circle"></i>
                    <span><?php echo e($_SESSION['flash_success']); ?></span>
                    <button class="alert-close" onclick="this.closest('.alert').remove()" aria-label="Close">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <?php unset($_SESSION['flash_success']); ?>
                <?php endif; ?>

                <?php if (!empty($_SESSION['flash_error'])): ?>
                <div class="alert alert-danger" role="alert">
                    <i class="fas fa-exclamation-circle"></i>
                    <span><?php echo e($_SESSION['flash_error']); ?></span>
                    <button class="alert-close" onclick="this.closest('.alert').remove()" aria-label="Close">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <?php unset($_SESSION['flash_error']); ?>
                <?php endif; ?>

                <!-- Form -->
                <form method="POST" action="/applicant/forgot-password" id="forgotForm" novalidate>
                    <input type="hidden" name="csrf_token" value="<?php echo e($csrf_token); ?>">

                    <div class="form-group">
                        <label for="email" class="form-label">
                            Email Address <span class="req">*</span>
                        </label>
                        <div class="input-wrap">
                            <i class="fas fa-envelope input-icon"></i>
                            <input type="email"
                                   class="form-control"
                                   id="email"
                                   name="email"
                                   value="<?php echo e($_SESSION['email_value'] ?? ''); ?>"
                                   placeholder="Enter your registered email address"
                                   autocomplete="email"
                                   required>
                        </div>
                        <div class="invalid-msg" id="emailError">Please enter a valid email address.</div>
                    </div>

                    <button type="submit" class="btn-reset" id="resetBtn">
                        <span id="resetText">
                            <i class="fas fa-paper-plane"></i> Send Reset Link
                        </span>
                        <span id="resetSpinner" style="display:none">
                            <span class="spinner"></span> Sending&hellip;
                        </span>
                    </button>
                </form>

                <!-- Secondary actions -->
                <div class="divider">OR</div>

                <div class="actions-block">
                    <a href="/applicant/login" class="btn-outline">
                        <i class="fas fa-arrow-left"></i> Back to Login
                    </a>
                    <a href="/apply/register" class="btn-outline">
                        <i class="fas fa-user-plus"></i> Start New Application
                    </a>
                </div>

            </div><!-- /card-body -->
        </div><!-- /forgot-card -->

        <!-- Return to Home -->
        <div class="page-foot">
            <a href="/">
                <i class="fas fa-arrow-left" style="font-size:.7rem"></i> Return to Home
            </a>
        </div>

    </div><!-- /forgot-wrap -->


    <script>
        document.getElementById('forgotForm').addEventListener('submit', function (e) {
            const email     = document.getElementById('email');
            const errorMsg  = document.getElementById('emailError');
            const val       = email.value.trim();
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

            email.classList.remove('is-invalid');
            errorMsg.style.display = 'none';

            if (!val || !emailRegex.test(val)) {
                email.classList.add('is-invalid');
                errorMsg.style.display = 'block';
                e.preventDefault();
                return;
            }

            // Loading state
            document.getElementById('resetText').style.display   = 'none';
            document.getElementById('resetSpinner').style.display = 'inline-flex';
            document.getElementById('resetBtn').disabled          = true;
        });

        // Auto-dismiss alerts after 5.5 s
        setTimeout(function () {
            document.querySelectorAll('.alert').forEach(function (el) {
                el.style.transition = 'opacity .4s';
                el.style.opacity    = '0';
                setTimeout(function () { el.remove(); }, 400);
            });
        }, 5500);

        <?php unset($_SESSION['email_value']); ?>
    </script>
</body>
</html>