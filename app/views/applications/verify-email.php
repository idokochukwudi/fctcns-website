<?php
/**
 * Email Verification View
 * Fits inside the portal layout's $content slot.
 * Handles: verified, error, already-verified (message), email-sent, fallback.
 */
?>

<style>
    /* ── Shared wrapper ─────────────────────────────────────── */
    .verify-wrap {
        max-width: 520px;
        margin: 0 auto;
        display: flex;
        flex-direction: column;
        gap: 1.5rem;
    }

    /* ── State card ─────────────────────────────────────────── */
    .verify-card {
        border: 1px solid var(--border);
        border-radius: 16px;
        overflow: hidden;
    }

    /* ── Card head ──────────────────────────────────────────── */
    .verify-head {
        padding: 2rem 2rem 1.75rem;
        text-align: center;
        position: relative;
        overflow: hidden;
    }

    /* colour variants */
    .verify-head.success  { background: var(--teal);      }
    .verify-head.danger   { background: var(--red);        }
    .verify-head.info     { background: var(--navy-mid);   }
    .verify-head.primary  { background: var(--navy);       }
    .verify-head.warning  { background: #D97706;           }

    /* gold top line on all heads */
    .verify-head::before {
        content: '';
        position: absolute;
        top: 0; left: 0; right: 0;
        height: 3px;
        background: linear-gradient(90deg, var(--gold), var(--gold-light), var(--gold));
    }

    .verify-head-emblem {
        width: 60px; height: 60px;
        border-radius: 50%;
        background: rgba(255,255,255,0.12);
        border: 1.5px solid rgba(255,255,255,0.2);
        display: flex; align-items: center; justify-content: center;
        margin: 0 auto 1rem;
        font-size: 1.5rem;
        color: #fff;
    }

    .verify-head h2 {
        font-family: 'Playfair Display', Georgia, serif;
        font-size: 1.35rem;
        font-weight: 700;
        color: #fff;
        margin: 0;
        line-height: 1.3;
    }

    .verify-head p {
        font-size: .82rem;
        color: rgba(255,255,255,0.55);
        margin: .4rem 0 0;
        text-transform: uppercase;
        letter-spacing: .4px;
    }

    /* ── Card body ──────────────────────────────────────────── */
    .verify-body {
        background: #fff;
        padding: 2rem;
    }

    /* ── Greeting block ─────────────────────────────────────── */
    .verify-greeting {
        text-align: center;
        margin-bottom: 1.5rem;
    }

    .verify-greeting-icon {
        width: 52px; height: 52px;
        background: var(--teal-light);
        border: 1.5px solid rgba(29,138,122,.2);
        border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        margin: 0 auto .85rem;
        color: var(--teal);
        font-size: 1.2rem;
    }

    .verify-greeting h4 {
        font-family: 'Playfair Display', serif;
        font-size: 1.1rem;
        font-weight: 600;
        color: var(--text-dark);
        margin-bottom: .4rem;
    }

    .verify-greeting p {
        font-size: .875rem;
        color: var(--text-body);
        line-height: 1.55;
        margin: 0;
    }

    /* ── Info / alert banners ───────────────────────────────── */
    .v-banner {
        display: flex;
        align-items: flex-start;
        gap: .75rem;
        border-radius: 10px;
        padding: .9rem 1.1rem;
        font-size: .875rem;
        border: 1px solid transparent;
        margin-bottom: 1.25rem;
        line-height: 1.55;
    }

    .v-banner i { flex-shrink: 0; margin-top: 1px; font-size: .9rem; }

    .v-banner.danger  { background: var(--red-light);  border-color: rgba(192,57,43,.2);  color: #7f1d1d; }
    .v-banner.danger  i { color: var(--red); }

    .v-banner.info    { background: #EFF4FF;            border-color: rgba(37,99,235,.15); color: #1e3a8a; }
    .v-banner.info    i { color: #2563EB; }

    .v-banner.warning { background: #FFFBF0;            border-color: rgba(200,150,58,.25); color: #5a4010; border-left: 3px solid var(--gold); }
    .v-banner.warning i { color: var(--gold); }

    .v-banner.success { background: var(--teal-light);  border-color: rgba(29,138,122,.2); color: #134e42; }
    .v-banner.success i { color: var(--teal); }

    /* ── Email highlight ────────────────────────────────────── */
    .email-chip {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        background: var(--off-white);
        border: 1px solid var(--border-dark);
        border-radius: 8px;
        padding: 6px 14px;
        font-size: .88rem;
        font-weight: 600;
        color: var(--navy);
        margin: .5rem 0 1rem;
        font-family: 'DM Mono', monospace;
        word-break: break-all;
    }

    .email-chip i { color: var(--gold); flex-shrink: 0; }

    /* ── Tips list ──────────────────────────────────────────── */
    .tips-block {
        border: 1px solid var(--border);
        border-radius: 10px;
        overflow: hidden;
        margin-bottom: 1.25rem;
    }

    .tips-head {
        background: var(--off-white);
        border-bottom: 1px solid var(--border);
        padding: .6rem 1rem;
        font-size: .75rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .6px;
        color: var(--text-muted);
        display: flex;
        align-items: center;
        gap: .5rem;
    }

    .tips-list {
        list-style: none;
        padding: .75rem 1rem;
        margin: 0;
        display: flex;
        flex-direction: column;
        gap: .6rem;
    }

    .tips-list li {
        display: flex;
        align-items: flex-start;
        gap: .65rem;
        font-size: .85rem;
        color: var(--text-body);
        line-height: 1.45;
    }

    .tips-list li i {
        font-size: .8rem;
        flex-shrink: 0;
        margin-top: 2px;
        width: 14px;
    }

    /* ── Reasons list ───────────────────────────────────────── */
    .reasons-list {
        list-style: none;
        padding: 0; margin: 0 0 1.5rem;
        display: flex;
        flex-direction: column;
        gap: .5rem;
        text-align: left;
    }

    .reasons-list li {
        display: flex;
        align-items: flex-start;
        gap: .65rem;
        font-size: .875rem;
        color: var(--text-body);
        line-height: 1.45;
    }

    .reasons-list li::before {
        content: '';
        width: 18px; height: 18px;
        border-radius: 50%;
        background: var(--red-light);
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 12 12'%3E%3Cpath d='M3 3l6 6M9 3l-6 6' stroke='%23C0392B' stroke-width='1.8' stroke-linecap='round'/%3E%3C/svg%3E");
        background-size: 10px;
        background-repeat: no-repeat;
        background-position: center;
        flex-shrink: 0;
        margin-top: 1px;
    }

    /* ── Divider ────────────────────────────────────────────── */
    .v-divider {
        height: 1px;
        background: var(--border);
        margin: 1.5rem 0;
    }

    /* ── Resend block ───────────────────────────────────────── */
    .resend-block {
        background: var(--off-white);
        border: 1px solid var(--border);
        border-radius: 10px;
        padding: 1rem 1.25rem;
        text-align: center;
        margin-bottom: 1.25rem;
    }

    .resend-block p {
        font-size: .85rem;
        color: var(--text-muted);
        margin-bottom: .75rem;
    }

    /* ── Buttons ────────────────────────────────────────────── */
    .btn-v-primary {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 7px;
        width: 100%;
        padding: .85rem 1.5rem;
        background: var(--navy);
        color: #fff;
        border: none;
        border-radius: 10px;
        font-family: 'DM Sans', sans-serif;
        font-size: .9rem;
        font-weight: 600;
        text-decoration: none;
        cursor: pointer;
        transition: all .25s;
        box-shadow: 0 4px 14px rgba(15,27,53,.2);
        margin-bottom: .6rem;
    }

    .btn-v-primary:hover {
        background: var(--navy-light);
        transform: translateY(-1px);
        box-shadow: 0 8px 20px rgba(15,27,53,.28);
        color: #fff;
    }

    .btn-v-outline {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 7px;
        width: 100%;
        padding: .85rem 1.5rem;
        background: transparent;
        color: var(--navy);
        border: 1.5px solid var(--border-dark);
        border-radius: 10px;
        font-family: 'DM Sans', sans-serif;
        font-size: .9rem;
        font-weight: 600;
        text-decoration: none;
        cursor: pointer;
        transition: all .25s;
        margin-bottom: .6rem;
    }

    .btn-v-outline:hover {
        background: var(--off-white);
        border-color: var(--navy);
        color: var(--navy);
    }

    .btn-v-ghost {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
        font-size: .82rem;
        color: var(--text-muted);
        text-decoration: none;
        transition: color .2s;
        font-weight: 500;
    }

    .btn-v-ghost:hover { color: var(--navy); }

    /* ── Support footer ─────────────────────────────────────── */
    .verify-support {
        background: var(--off-white);
        border: 1px solid var(--border);
        border-radius: 12px;
        padding: 1rem 1.25rem;
        display: flex;
        align-items: center;
        gap: .75rem;
        font-size: .82rem;
        color: var(--text-muted);
        line-height: 1.5;
    }

    .verify-support-icon {
        width: 32px; height: 32px;
        background: var(--navy);
        border-radius: 8px;
        display: flex; align-items: center; justify-content: center;
        color: var(--gold-light);
        font-size: .8rem;
        flex-shrink: 0;
    }

    .verify-support a {
        color: var(--navy);
        font-weight: 600;
        text-decoration: none;
    }

    .verify-support a:hover { color: var(--gold); }

    @media (max-width: 480px) {
        .verify-body { padding: 1.5rem; }
        .verify-head { padding: 1.5rem 1.5rem 1.25rem; }
    }
</style>

<div class="verify-wrap">

    <?php if (isset($verified) && $verified): ?>
    <!-- ══ STATE 1: Email Verified Successfully ══ -->
    <div class="verify-card">
        <div class="verify-head success">
            <div class="verify-head-emblem">
                <i class="fas fa-check-circle"></i>
            </div>
            <h2>Email Verified!</h2>
            <p>FCT College of Nursing Sciences</p>
        </div>
        <div class="verify-body">
            <div class="verify-greeting">
                <div class="verify-greeting-icon">
                    <i class="fas fa-envelope-open-text"></i>
                </div>
                <h4>Welcome, <?php echo htmlspecialchars($applicant_name ?? 'Applicant'); ?>!</h4>
                <p>Your email address has been successfully verified. You are now logged in and ready to proceed.</p>
            </div>

            <div class="v-banner success">
                <i class="fas fa-check-circle"></i>
                <span>Your account is active. The next step is to verify your JAMB registration number.</span>
            </div>

            <a href="/apply/step/1" class="btn-v-primary">
                <i class="fas fa-arrow-right"></i> Continue to JAMB Verification
            </a>
        </div>
    </div>

    <?php elseif (isset($error)): ?>
    <!-- ══ STATE 2: Verification Failed with Resend Option ══ -->
    <div class="verify-card">
        <div class="verify-head danger">
            <div class="verify-head-emblem">
                <i class="fas fa-times-circle"></i>
            </div>
            <h2>Verification Failed</h2>
            <p>FCT College of Nursing Sciences</p>
        </div>
        <div class="verify-body">
            <div class="v-banner danger">
                <i class="fas fa-exclamation-circle"></i>
                <span><?php echo htmlspecialchars($error); ?></span>
            </div>

            <p style="font-size:.875rem;color:var(--text-body);margin-bottom:.75rem;font-weight:600">Possible reasons:</p>
            <ul class="reasons-list">
                <li>The verification link may have expired (links expire after 24 hours)</li>
                <li>The link may have already been used</li>
                <li>The verification token is invalid or corrupted</li>
            </ul>

            <?php if (isset($resend_email) && $resend_email): ?>
            <!-- Resend option when email is available -->
            <div class="resend-block">
                <p><i class="fas fa-paper-plane" style="margin-right:4px"></i> Need a new verification link?</p>
                <a href="/apply/resend-verification?email=<?php echo urlencode($resend_email); ?>" 
                   class="btn-v-outline" style="width:auto;padding:.65rem 1.25rem;font-size:.85rem;margin-bottom:0">
                    <i class="fas fa-redo-alt"></i> Resend Verification Email
                </a>
                <?php if (!empty($resend_email)): ?>
                <div style="margin-top:.75rem;font-size:.8rem;color:var(--text-muted)">
                    <i class="fas fa-envelope"></i> 
                    Email: <?php echo htmlspecialchars($resend_email); ?>
                </div>
                <?php endif; ?>
            </div>
            <?php endif; ?>

            <a href="/apply/register" class="btn-v-primary">
                <i class="fas fa-redo"></i> Register Again
            </a>
            <a href="/applicant/login" class="btn-v-outline">
                <i class="fas fa-sign-in-alt"></i> Try Login Instead
            </a>
        </div>
    </div>

    <?php elseif (isset($message)): ?>
    <!-- ══ STATE 3: Already Verified ══ -->
    <div class="verify-card">
        <div class="verify-head info">
            <div class="verify-head-emblem">
                <i class="fas fa-info-circle"></i>
            </div>
            <h2>Already Verified</h2>
            <p>FCT College of Nursing Sciences</p>
        </div>
        <div class="verify-body">
            <div class="v-banner info">
                <i class="fas fa-info-circle"></i>
                <span><?php echo htmlspecialchars($message); ?></span>
            </div>

            <a href="/applicant/login" class="btn-v-primary">
                <i class="fas fa-sign-in-alt"></i> Go to Login
            </a>
        </div>
    </div>

    <?php elseif (isset($email_sent) && $email_sent): ?>
    <!-- ══ STATE 4: Verification Email Sent ══ -->
    <div class="verify-card">
        <div class="verify-head primary">
            <div class="verify-head-emblem">
                <i class="fas fa-envelope"></i>
            </div>
            <h2>Check Your Inbox</h2>
            <p>FCT College of Nursing Sciences</p>
        </div>
        <div class="verify-body">
            <div class="verify-greeting">
                <div class="verify-greeting-icon" style="background:var(--navy-ghost,#EEF3FA);border-color:rgba(15,27,53,.15);color:var(--navy)">
                    <i class="fas fa-paper-plane"></i>
                </div>
                <h4>Verification link sent!</h4>
                <?php if (!empty($email)): ?>
                <p>We've sent a verification link to:</p>
                <div class="email-chip">
                    <i class="fas fa-envelope"></i>
                    <?php echo htmlspecialchars($email); ?>
                </div>
                <?php else: ?>
                <p>We've sent a verification link to your registered email address.</p>
                <?php endif; ?>
            </div>

            <div class="v-banner warning">
                <i class="fas fa-clock"></i>
                <span><strong>The link will expire in 24 hours.</strong> Please verify your email before then.</span>
            </div>

            <!-- Resend -->
            <div class="resend-block">
                <p><i class="fas fa-question-circle" style="margin-right:4px"></i> Didn't receive the email?</p>
                <a href="/apply/resend-verification?email=<?php echo urlencode($email ?? ''); ?>"
                   class="btn-v-outline" style="width:auto;padding:.65rem 1.25rem;font-size:.85rem;margin-bottom:0">
                    <i class="fas fa-redo-alt"></i> Resend Verification Email
                </a>
            </div>

            <!-- Tips -->
            <div class="tips-block">
                <div class="tips-head">
                    <i class="fas fa-lightbulb" style="color:var(--gold)"></i>
                    Common issues
                </div>
                <ul class="tips-list">
                    <li>
                        <i class="fas fa-folder" style="color:#D97706"></i>
                        Check your <strong>spam or junk</strong> folder
                    </li>
                    <li>
                        <i class="fas fa-clock" style="color:var(--navy)"></i>
                        Wait a few minutes — delivery can take time
                    </li>
                    <li>
                        <i class="fas fa-at" style="color:var(--teal)"></i>
                        Confirm you entered the <strong>correct email address</strong>
                    </li>
                </ul>
            </div>

            <div class="v-divider"></div>

            <div style="display:flex;flex-direction:column;align-items:center;gap:.6rem">
                <a href="/applicant/login" class="btn-v-ghost">
                    <i class="fas fa-sign-in-alt"></i> Already verified? Login here
                </a>
                <a href="/apply/register" class="btn-v-ghost">
                    <i class="fas fa-user-plus"></i> Register with a different email
                </a>
            </div>
        </div>
    </div>

    <?php else: ?>
    <!-- ══ STATE 5: Fallback ══ -->
    <div class="verify-card">
        <div class="verify-head warning">
            <div class="verify-head-emblem">
                <i class="fas fa-exclamation-triangle"></i>
            </div>
            <h2>Something Went Wrong</h2>
            <p>FCT College of Nursing Sciences</p>
        </div>
        <div class="verify-body">
            <div class="v-banner warning">
                <i class="fas fa-exclamation-triangle"></i>
                <span>An unexpected error occurred. Please try again or contact support if the problem persists.</span>
            </div>

            <a href="/apply/register" class="btn-v-primary">
                <i class="fas fa-redo"></i> Register Again
            </a>
            <a href="/applicant/login" class="btn-v-outline">
                <i class="fas fa-sign-in-alt"></i> Go to Login
            </a>
        </div>
    </div>
    <?php endif; ?>

    <!-- Support footer (shown on all states) -->
    <div class="verify-support">
        <div class="verify-support-icon">
            <i class="fas fa-headset"></i>
        </div>
        <div>
            Need help? Contact support at
            <a href="mailto:info@fctcns.edu.ng">info@fctcns.edu.ng</a> or call
            <a href="tel:07039837749">07039837749</a>
        </div>
    </div>

</div><!-- /verify-wrap -->

<script>
document.addEventListener('DOMContentLoaded', function () {
    // Auto-hide non-critical banners after 6 s (keep warning/info visible)
    setTimeout(function () {
        document.querySelectorAll('.v-banner.danger').forEach(function (el) {
            el.style.transition = 'opacity .4s';
            el.style.opacity = '0';
            setTimeout(function () { el.remove(); }, 400);
        });
    }, 6000);
});
</script>