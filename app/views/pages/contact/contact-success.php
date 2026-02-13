<?php
/**
 * CONTACT SUCCESS PAGE
 * File: /app/views/pages/contact/contact-success.php
 *
 * Rendered inside the main layout (header + footer already present).
 * This file outputs only the page body content.
 */

extract($data ?? []);

$baseUrl    = $baseUrl ?? '';
$submission = $submission ?? null;
$reference  = $submission['id'] ?? date('Ymd') . rand(100, 999);
$name       = $submission['name'] ?? 'there';
$subject    = $submission['subject'] ?? '';
?>

<style>
    /* EMERGENCY FULL WIDTH OVERRIDE */
body .main-content {
    padding: 0 !important;
    max-width: 100vw !important;
}

.hero-section {
    width: 100vw !important;
    position: relative !important;
    left: 50% !important;
    right: 50% !important;
    margin-left: -50vw !important;
    margin-right: -50vw !important;
}
    </style>
<style>
    /* EMERGENCY FULL WIDTH OVERRIDE */
body .main-content {
    padding: 0 !important;
    max-width: 100vw !important;
}

.hero-section {
    width: 100vw !important;
    position: relative !important;
    left: 50% !important;
    right: 50% !important;
    margin-left: -50vw !important;
    margin-right: -50vw !important;
}
    </style>
<style>
    /* ── TOKENS ─────────────────────────────────────────────── */
    :root {
        --navy:        #0B1F3A;
        --navy-light:  #1A3A5C;
        --gold:        #C9963A;
        --gold-light:  #E5B96A;
        --gold-pale:   #F5E6CC;
        --teal:        #1A7F74;
        --teal-light:  #22A99B;
        --cream:       #FAF7F2;
        --white:       #FFFFFF;
        --gray-100:    #F0EDE8;
        --gray-300:    #C8C0B4;
        --gray-500:    #8A7F72;
        --gray-700:    #4A4035;
        --shadow-soft: 0 2px 20px rgba(11,31,58,0.08);
        --shadow-card: 0 8px 40px rgba(11,31,58,0.12);
        --shadow-gold: 0 4px 24px rgba(201,150,58,0.25);
    }

    /* ── SECTION WRAPPER ─────────────────────────────────────── */
    .success-section {
        background: var(--cream);
        padding: clamp(2rem, 6vw, 4rem) clamp(1rem, 5vw, 2rem);
        display: flex;
        justify-content: center;
        background-image:
            radial-gradient(ellipse 70% 40% at 15% 0%, rgba(201,150,58,0.07) 0%, transparent 60%),
            radial-gradient(ellipse 60% 40% at 85% 100%, rgba(26,127,116,0.06) 0%, transparent 60%);
    }

    /* ── CARD PANEL ──────────────────────────────────────────── */
    .sc-panel {
        width: 100%;
        max-width: 760px;
        animation: sc-riseIn 0.7s cubic-bezier(0.22,1,0.36,1) both;
    }

    /* ── HERO BLOCK ──────────────────────────────────────────── */
    .sc-hero {
        background: var(--navy);
        border-radius: 20px 20px 0 0;
        padding: clamp(2.5rem, 6vw, 4rem) clamp(1.5rem, 5vw, 3.5rem);
        position: relative;
        overflow: hidden;
        display: flex;
        flex-direction: column;
        align-items: center;
        text-align: center;
    }

    .sc-hero::before {
        content: '';
        position: absolute;
        top: -60px; right: -60px;
        width: 220px; height: 220px;
        border-radius: 50%;
        background: radial-gradient(circle, rgba(201,150,58,0.15) 0%, transparent 70%);
        pointer-events: none;
    }

    .sc-hero::after {
        content: '';
        position: absolute;
        bottom: -40px; left: -40px;
        width: 160px; height: 160px;
        border-radius: 50%;
        background: radial-gradient(circle, rgba(26,127,116,0.12) 0%, transparent 70%);
        pointer-events: none;
    }

    /* ── ANIMATED CHECK ──────────────────────────────────────── */
    .sc-check-ring {
        width: 80px;
        height: 80px;
        flex-shrink: 0;
        margin-bottom: 1.75rem;
        position: relative;
        z-index: 1;
        animation: sc-popIn 0.6s 0.3s cubic-bezier(0.34,1.56,0.64,1) both;
    }

    .sc-check-ring svg { width: 100%; height: 100%; }

    .sc-circle {
        fill: none;
        stroke: var(--gold);
        stroke-width: 2.5;
        stroke-dasharray: 220;
        stroke-dashoffset: 220;
        animation: sc-drawCircle 0.8s 0.5s ease forwards;
    }

    .sc-tick {
        fill: none;
        stroke: var(--gold-light);
        stroke-width: 3;
        stroke-linecap: round;
        stroke-linejoin: round;
        stroke-dasharray: 60;
        stroke-dashoffset: 60;
        animation: sc-drawTick 0.4s 1.1s ease forwards;
    }

    /* ── HERO TEXT ───────────────────────────────────────────── */
    .sc-label {
        font-size: 0.7rem;
        font-weight: 600;
        letter-spacing: 0.2em;
        text-transform: uppercase;
        color: var(--gold);
        margin-bottom: 0.75rem;
        text-align: center;
        width: 100%;
        position: relative;
        z-index: 1;
    }

    .sc-title {
        font-family: 'Playfair Display', Georgia, serif;
        font-size: clamp(2rem, 5vw, 3rem);
        font-weight: 700;
        color: var(--white);
        line-height: 1.15;
        margin-bottom: 1rem;
        text-align: center;
        width: 100%;
        position: relative;
        z-index: 1;
    }

    .sc-sub {
        font-size: clamp(0.9rem, 2vw, 1.05rem);
        color: var(--gray-300);
        font-weight: 300;
        max-width: 520px;
        width: 100%;
        margin: 0 auto;
        line-height: 1.75;
        text-align: center;
        position: relative;
        z-index: 1;
    }

    .sc-sub strong {
        color: var(--gold-light);
        font-weight: 600;
    }

    /* ── BODY CARD ───────────────────────────────────────────── */
    .sc-body {
        background: var(--white);
        border-radius: 0 0 20px 20px;
        padding: clamp(2rem, 5vw, 3rem) clamp(1.5rem, 5vw, 3.5rem);
        box-shadow: var(--shadow-card);
    }

    /* ── REFERENCE STRIP ─────────────────────────────────────── */
    .sc-ref-strip {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 1px;
        background: var(--gray-100);
        border-radius: 12px;
        overflow: hidden;
        margin-bottom: 2.5rem;
    }

    .sc-ref-cell {
        background: var(--white);
        padding: 1.25rem 1rem;
        text-align: center;
    }

    .sc-ref-label {
        font-size: 0.65rem;
        font-weight: 600;
        letter-spacing: 0.15em;
        text-transform: uppercase;
        color: var(--gray-500);
        margin-bottom: 0.4rem;
    }

    .sc-ref-value {
        font-family: 'DM Mono', 'Courier New', monospace;
        font-size: clamp(0.95rem, 2.5vw, 1.2rem);
        font-weight: 500;
        color: var(--navy);
    }

    .sc-ref-value.sc-highlight {
        color: var(--gold);
        font-size: clamp(1rem, 3vw, 1.35rem);
    }

    /* ── GREETING ────────────────────────────────────────────── */
    .sc-greeting {
        border-left: 3px solid var(--gold);
        padding: 1.25rem 1.5rem;
        background: var(--gold-pale);
        border-radius: 0 10px 10px 0;
        margin-bottom: 2.5rem;
    }

    .sc-greeting p {
        font-size: clamp(0.9rem, 2vw, 1rem);
        color: var(--gray-700);
        line-height: 1.75;
    }

    .sc-greeting strong { color: var(--navy); font-weight: 600; }

    /* ── SECTION LABEL ───────────────────────────────────────── */
    .sc-section-label {
        font-size: 0.65rem;
        font-weight: 700;
        letter-spacing: 0.2em;
        text-transform: uppercase;
        color: var(--gray-500);
        margin-bottom: 1.25rem;
        display: block;
    }

    /* ── PROCESS STEPS ───────────────────────────────────────── */
    .sc-steps {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 1rem;
        margin-bottom: 2.5rem;
    }

    .sc-step {
        border: 1px solid var(--gray-100);
        border-radius: 12px;
        padding: 1.25rem 1rem;
        text-align: center;
        transition: border-color 0.2s, box-shadow 0.2s;
        animation: sc-fadeUp 0.5s both;
    }

    .sc-step:nth-child(1) { animation-delay: 0.15s; }
    .sc-step:nth-child(2) { animation-delay: 0.25s; }
    .sc-step:nth-child(3) { animation-delay: 0.35s; }

    .sc-step:hover {
        border-color: var(--gold-light);
        box-shadow: var(--shadow-soft);
    }

    .sc-step-num {
        width: 32px;
        height: 32px;
        background: var(--navy);
        color: var(--gold);
        border-radius: 50%;
        font-size: 0.9rem;
        font-weight: 700;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 0.75rem;
    }

    .sc-step-title {
        font-size: 0.85rem;
        font-weight: 600;
        color: var(--navy);
        margin-bottom: 0.3rem;
    }

    .sc-step-desc {
        font-size: 0.75rem;
        color: var(--gray-500);
        line-height: 1.5;
    }

    /* ── DIVIDER ─────────────────────────────────────────────── */
    .sc-divider {
        height: 1px;
        background: linear-gradient(90deg, transparent, var(--gray-100), transparent);
        margin: 2rem 0;
    }

    /* ── ACTION BUTTONS ──────────────────────────────────────── */
    .sc-actions {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 0.75rem;
        margin-top: 1rem;
    }

    .sc-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        padding: 0.875rem 1.5rem;
        border-radius: 10px;
        text-decoration: none;
        font-size: 0.9rem;
        font-weight: 600;
        border: none;
        cursor: pointer;
        transition: all 0.25s;
        letter-spacing: 0.01em;
    }

    .sc-btn-primary {
        background: var(--navy);
        color: var(--white);
        grid-column: span 2;
    }

    .sc-btn-primary:hover {
        background: var(--navy-light);
        color: var(--white);
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(11,31,58,0.2);
    }

    .sc-btn-outline {
        background: transparent;
        color: var(--navy);
        border: 1.5px solid var(--gray-300);
    }

    .sc-btn-outline:hover {
        border-color: var(--navy);
        background: var(--gray-100);
        color: var(--navy);
        transform: translateY(-2px);
    }

    .sc-btn-gold {
        background: var(--gold);
        color: var(--white);
    }

    .sc-btn-gold:hover {
        background: var(--gold-light);
        color: var(--white);
        transform: translateY(-2px);
        box-shadow: var(--shadow-gold);
    }

    /* ── EMAIL NOTE ──────────────────────────────────────────── */
    .sc-email-note {
        margin-top: 1.5rem;
        display: flex;
        align-items: flex-start;
        gap: 10px;
        padding: 1rem 1.25rem;
        background: var(--gray-100);
        border-radius: 10px;
        font-size: 0.8rem;
        color: var(--gray-500);
        line-height: 1.6;
    }

    .sc-email-note-icon {
        font-size: 1rem;
        flex-shrink: 0;
        margin-top: 1px;
    }

    /* ── ANIMATIONS ──────────────────────────────────────────── */
    @keyframes sc-riseIn {
        from { opacity: 0; transform: translateY(28px); }
        to   { opacity: 1; transform: translateY(0); }
    }

    @keyframes sc-popIn {
        from { opacity: 0; transform: scale(0.5); }
        to   { opacity: 1; transform: scale(1); }
    }

    @keyframes sc-drawCircle {
        to { stroke-dashoffset: 0; }
    }

    @keyframes sc-drawTick {
        to { stroke-dashoffset: 0; }
    }

    @keyframes sc-fadeUp {
        from { opacity: 0; transform: translateY(16px); }
        to   { opacity: 1; transform: translateY(0); }
    }

    /* ── RESPONSIVE ──────────────────────────────────────────── */
    @media (max-width: 640px) {
        .sc-steps {
            grid-template-columns: 1fr;
            gap: 0.75rem;
        }

        .sc-step {
            display: flex;
            align-items: center;
            gap: 1rem;
            text-align: left;
            padding: 1rem 1.25rem;
        }

        .sc-step-num { margin: 0; flex-shrink: 0; }

        .sc-actions         { grid-template-columns: 1fr; }
        .sc-btn-primary     { grid-column: span 1; }
    }

    @media (max-width: 420px) {
        .sc-ref-strip   { grid-template-columns: 1fr; gap: 1px; }
        .sc-ref-cell    { padding: 1rem; }
        .sc-hero        { border-radius: 16px 16px 0 0; }
        .sc-body        { border-radius: 0 0 16px 16px; }
    }

    @media (min-width: 1024px) {
        .sc-panel { max-width: 820px; }
    }
</style>

<section class="success-section">
    <div class="sc-panel">

        <!-- Hero -->
        <div class="sc-hero">
            <div class="sc-check-ring">
                <svg viewBox="0 0 80 80">
                    <circle class="sc-circle" cx="40" cy="40" r="34" transform="rotate(-90 40 40)"/>
                    <polyline class="sc-tick" points="24,41 34,52 56,30"/>
                </svg>
            </div>
            <p class="sc-label">Submission Confirmed</p>
            <h1 class="sc-title">Message Received</h1>
            <p class="sc-sub">
                Thank you, <strong><?php echo htmlspecialchars($name); ?></strong>.
                Your inquiry has been delivered to our team and will be reviewed promptly.
            </p>
        </div>

        <!-- Body -->
        <div class="sc-body">

            <!-- Reference Strip -->
            <div class="sc-ref-strip">
                <div class="sc-ref-cell">
                    <div class="sc-ref-label">Reference</div>
                    <div class="sc-ref-value sc-highlight">#<?php echo htmlspecialchars($reference); ?></div>
                </div>
                <div class="sc-ref-cell">
                    <div class="sc-ref-label">Date Submitted</div>
                    <div class="sc-ref-value"><?php echo date('d M Y'); ?></div>
                </div>
                <div class="sc-ref-cell">
                    <div class="sc-ref-label">Response Time</div>
                    <div class="sc-ref-value">24 – 48 hrs</div>
                </div>
            </div>

            <!-- Greeting -->
            <div class="sc-greeting">
                <p>
                    Dear <strong><?php echo htmlspecialchars($name); ?></strong>, your message has been logged
                    under reference <strong>#<?php echo htmlspecialchars($reference); ?></strong> and routed to the
                    appropriate department. A member of our team will respond to your
                    <?php if (!empty($subject)): ?>
                        inquiry regarding <strong>"<?php echo htmlspecialchars($subject); ?>"</strong>
                    <?php else: ?>
                        inquiry
                    <?php endif; ?>
                    within 24–48 working hours.
                </p>
            </div>

            <!-- Process Steps -->
            <span class="sc-section-label">What happens next</span>
            <div class="sc-steps">
                <div class="sc-step">
                    <div class="sc-step-num">1</div>
                    <div>
                        <div class="sc-step-title">Review</div>
                        <div class="sc-step-desc">Your submission is reviewed and assigned to the right department</div>
                    </div>
                </div>
                <div class="sc-step">
                    <div class="sc-step-num">2</div>
                    <div>
                        <div class="sc-step-title">Prepare</div>
                        <div class="sc-step-desc">A specialist prepares a thorough and accurate response</div>
                    </div>
                </div>
                <div class="sc-step">
                    <div class="sc-step-num">3</div>
                    <div>
                        <div class="sc-step-title">Respond</div>
                        <div class="sc-step-desc">Reply sent directly to your email within 24–48 hours</div>
                    </div>
                </div>
            </div>

            <div class="sc-divider"></div>

            <!-- Actions - Simple navigation buttons -->
            <div class="sc-actions">
                <a href="<?php echo htmlspecialchars($baseUrl); ?>/admissions" class="sc-btn sc-btn-gold">
                    ✏️ Apply Now
                </a>
                <a href="<?php echo htmlspecialchars($baseUrl); ?>/programs" class="sc-btn sc-btn-outline">
                    View Programs
                </a>
                <a href="<?php echo htmlspecialchars($baseUrl); ?>" class="sc-btn sc-btn-primary">
                    ← Return to Homepage
                </a>
            </div>

            <!-- Email Note -->
            <div class="sc-email-note">
                <span class="sc-email-note-icon">📬</span>
                <span>
                    A confirmation email has been sent to your inbox.
                    If you don't see it within 15 minutes, please check your spam or junk folder.
                    Quote reference <strong style="color: var(--gray-700);">#<?php echo htmlspecialchars($reference); ?></strong> in any follow-up correspondence.
                </span>
            </div>

        </div>
        <!-- /sc-body -->

    </div>
    <!-- /sc-panel -->
</section>