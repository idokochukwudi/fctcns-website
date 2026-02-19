<?php
/**
 * Home / Landing Page View
 * Fits inside the portal layout's $content slot.
 * All PHP logic and data bindings from the original are preserved.
 */

// ── Date helpers ───────────────────────────────────────────────────────────
$start_date   = isset($settings['key_value']['application_start_date'])
    ? date('j M Y', strtotime($settings['key_value']['application_start_date'])) : '15 Sep 2025';
$end_date     = isset($settings['key_value']['application_end_date'])
    ? date('j M Y', strtotime($settings['key_value']['application_end_date']))   : '28 Sep 2025';
$cbt_start    = isset($settings['key_value']['cbt_start_date'])
    ? date('j M Y', strtotime($settings['key_value']['cbt_start_date']))         : '6 Oct 2025';
$cbt_end      = isset($settings['key_value']['cbt_end_date'])
    ? date('j M Y', strtotime($settings['key_value']['cbt_end_date']))           : '8 Oct 2025';

$currency     = htmlspecialchars($settings['key_value']['application_currency'] ?? '₦');
$fee          = number_format($settings['key_value']['application_fee']          ?? 2200);
$duration     = htmlspecialchars($settings['key_value']['program_duration']      ?? '4 Years (2 Yrs ND + 2 Yrs HND)');
$min_score    = $settings['key_value']['min_utme_score']       ?? 170;
$max_sittings = $settings['key_value']['max_olevel_sittings']  ?? 2;
$min_age      = $settings['key_value']['min_age']              ?? 16;

$ph1          = htmlspecialchars($settings['key_value']['support_phone_1']   ?? '07039837749');
$ph2          = htmlspecialchars($settings['key_value']['support_phone_2']   ?? '08036625119');
$wa           = htmlspecialchars($settings['key_value']['support_whatsapp']  ?? '08082775076');
$email        = htmlspecialchars($settings['key_value']['support_email']     ?? 'support.consap@fcthhss.abj.gov.ng');
$hours        = htmlspecialchars($settings['key_value']['support_hours']     ?? 'Mon–Fri, 9AM–5PM');

$isOpen       = isset($portal_open) && $portal_open;
?>

<style>
/* ── Page-level tokens (inherits layout's :root) ──────────────────── */
.hp {
    --gap:    clamp(1rem, 3vw, 2rem);
    --r-card: 14px;
}

/* ── Hero band ─────────────────────────────────────────────────────── */
.hp-hero {
    background: var(--navy);
    border-radius: var(--r-card) var(--r-card) 0 0;
    padding: clamp(2rem, 5vw, 3.5rem) clamp(1.5rem, 4vw, 3rem);
    position: relative;
    overflow: hidden;
    margin: -40px -40px 2rem; /* bleed to edge of .portal-body padding */
    text-align: center;
}

/* Decorative rings */
.hp-hero::before,
.hp-hero::after {
    content: '';
    position: absolute;
    border-radius: 50%;
    pointer-events: none;
}
.hp-hero::before {
    width: 420px; height: 420px;
    border: 1px solid rgba(200,150,58,0.12);
    top: -180px; right: -80px;
}
.hp-hero::after {
    width: 280px; height: 280px;
    border: 1px solid rgba(200,150,58,0.08);
    bottom: -120px; left: -60px;
}

.hp-hero-status {
    display: inline-flex;
    align-items: center;
    gap: 7px;
    background: <?php echo $isOpen ? 'rgba(29,138,122,0.18)' : 'rgba(192,57,43,0.18)'; ?>;
    border: 1px solid <?php echo $isOpen ? 'rgba(29,138,122,0.35)' : 'rgba(192,57,43,0.35)'; ?>;
    border-radius: 50px;
    padding: 5px 14px;
    font-size: 11.5px;
    font-weight: 700;
    letter-spacing: .6px;
    text-transform: uppercase;
    color: <?php echo $isOpen ? '#6ee7d8' : '#f9a8a0'; ?>;
    margin-bottom: 1.25rem;
    position: relative;
    z-index: 1;
}

.hp-hero-status-dot {
    width: 7px; height: 7px;
    border-radius: 50%;
    background: <?php echo $isOpen ? '#1D8A7A' : '#C0392B'; ?>;
    <?php if ($isOpen): ?>
    animation: pulse-dot 1.8s ease infinite;
    <?php endif; ?>
}

@keyframes pulse-dot {
    0%, 100% { opacity: 1; transform: scale(1); }
    50%       { opacity: .5; transform: scale(1.4); }
}

.hp-hero-title {
    font-family: 'Playfair Display', Georgia, serif;
    font-size: clamp(1.5rem, 4vw, 2.4rem);
    font-weight: 700;
    color: #fff;
    line-height: 1.2;
    margin-bottom: .6rem;
    position: relative;
    z-index: 1;
}

.hp-hero-sub {
    font-size: clamp(.85rem, 2vw, 1rem);
    color: rgba(255,255,255,.5);
    margin-bottom: 0;
    position: relative;
    z-index: 1;
}

/* Gold accent line */
.hp-hero-rule {
    width: 56px;
    height: 3px;
    background: var(--gold);
    border-radius: 2px;
    margin: 1.25rem auto;
    position: relative;
    z-index: 1;
}

/* ── Key stats strip ───────────────────────────────────────────────── */
.hp-stats {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 1px;
    background: var(--border);
    border: 1px solid var(--border);
    border-radius: var(--r-card);
    overflow: hidden;
    margin-bottom: var(--gap);
}

.hp-stat {
    background: var(--off-white);
    padding: 1.25rem 1rem;
    text-align: center;
}

.hp-stat-value {
    font-family: 'Playfair Display', serif;
    font-size: clamp(1.3rem, 3vw, 1.9rem);
    font-weight: 700;
    color: var(--navy);
    line-height: 1;
    margin-bottom: .35rem;
}

.hp-stat-value span {
    color: var(--gold);
}

.hp-stat-label {
    font-size: 11px;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: .6px;
    color: var(--text-muted);
}

/* ── Info grid ─────────────────────────────────────────────────────── */
.hp-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: var(--gap);
    margin-bottom: var(--gap);
}

.hp-card {
    border: 1px solid var(--border);
    border-radius: var(--r-card);
    overflow: hidden;
}

.hp-card-head {
    display: flex;
    align-items: center;
    gap: .75rem;
    padding: .9rem 1.25rem;
    background: var(--navy);
    border-bottom: 2px solid var(--gold);
}

.hp-card-icon {
    width: 32px; height: 32px;
    background: rgba(200,150,58,0.15);
    border-radius: 7px;
    display: flex; align-items: center; justify-content: center;
    color: var(--gold-light);
    font-size: .85rem;
    flex-shrink: 0;
}

.hp-card-title {
    font-family: 'Playfair Display', serif;
    font-size: .95rem;
    font-weight: 600;
    color: #fff;
    margin: 0;
}

.hp-card-body {
    padding: 1.25rem;
    background: var(--white);
}

/* Rows inside info cards */
.hp-row {
    display: flex;
    align-items: baseline;
    gap: .5rem;
    padding: .55rem 0;
    border-bottom: 1px solid var(--border);
    font-size: .875rem;
}

.hp-row:last-child { border-bottom: none; }

.hp-row-label {
    font-weight: 600;
    color: var(--text-dark);
    white-space: nowrap;
    flex-shrink: 0;
    min-width: 110px;
}

.hp-row-value {
    color: var(--text-body);
    line-height: 1.4;
}

/* Status badge */
.status-badge {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    padding: 3px 10px;
    border-radius: 50px;
    font-size: 11.5px;
    font-weight: 700;
    letter-spacing: .3px;
}
.status-badge.open   { background: var(--teal-light);  color: #145f55; }
.status-badge.closed { background: var(--red-light);   color: #8b1a12; }

/* Eligibility list */
.hp-eli-list {
    list-style: none;
    padding: 0; margin: 0;
    display: flex;
    flex-direction: column;
    gap: .5rem;
}

.hp-eli-list li {
    display: flex;
    align-items: flex-start;
    gap: .65rem;
    font-size: .875rem;
    color: var(--text-body);
    line-height: 1.45;
}

.hp-eli-list li::before {
    content: '';
    width: 18px; height: 18px;
    border-radius: 50%;
    background: var(--teal-light);
    border: 1.5px solid rgba(29,138,122,.3);
    display: flex; align-items: center; justify-content: center;
    flex-shrink: 0;
    margin-top: 1px;
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 12 12'%3E%3Cpath d='M2 6l3 3 5-5' stroke='%231D8A7A' stroke-width='1.8' fill='none' stroke-linecap='round' stroke-linejoin='round'/%3E%3C/svg%3E");
    background-size: 11px;
    background-repeat: no-repeat;
    background-position: center;
}

/* ── Process steps ─────────────────────────────────────────────────── */
.hp-process {
    border: 1px solid var(--border);
    border-radius: var(--r-card);
    overflow: hidden;
    margin-bottom: var(--gap);
}

.hp-process-head {
    background: var(--off-white);
    border-bottom: 1px solid var(--border);
    padding: 1rem 1.5rem;
    display: flex;
    align-items: center;
    gap: .75rem;
}

.hp-process-head-icon {
    width: 30px; height: 30px;
    background: var(--navy);
    border-radius: 7px;
    display: flex; align-items: center; justify-content: center;
    color: var(--gold-light);
    font-size: .8rem;
    flex-shrink: 0;
}

.hp-process-head h3 {
    font-family: 'Playfair Display', serif;
    font-size: 1rem;
    font-weight: 600;
    color: var(--text-dark);
    margin: 0;
}

.hp-process-body {
    padding: 1.75rem 1.5rem;
    background: var(--white);
}

.hp-steps {
    display: grid;
    grid-template-columns: repeat(5, 1fr);
    gap: 0;
    position: relative;
    margin-bottom: 2rem;
}

/* Connecting line */
.hp-steps::before {
    content: '';
    position: absolute;
    top: 24px;
    left: calc(10% + 24px);
    right: calc(10% + 24px);
    height: 2px;
    background: var(--border);
    z-index: 0;
}

.hp-step {
    display: flex;
    flex-direction: column;
    align-items: center;
    text-align: center;
    padding: 0 .5rem;
    position: relative;
    z-index: 1;
}

.hp-step-num {
    width: 48px; height: 48px;
    border-radius: 50%;
    display: flex; align-items: center; justify-content: center;
    font-size: 1.1rem;
    font-weight: 700;
    margin-bottom: .85rem;
    border: 2px solid transparent;
    transition: transform .25s;
}

.hp-step:hover .hp-step-num { transform: translateY(-3px); }

.hp-step-num.s1 { background: var(--navy);     color: var(--gold-light); border-color: var(--navy); }
.hp-step-num.s2 { background: var(--off-white); color: var(--text-muted); border-color: var(--border-dark); }
.hp-step-num.s3 { background: var(--off-white); color: var(--text-muted); border-color: var(--border-dark); }
.hp-step-num.s4 { background: var(--off-white); color: var(--text-muted); border-color: var(--border-dark); }
.hp-step-num.s5 { background: var(--off-white); color: var(--text-muted); border-color: var(--border-dark); }

.hp-step-title {
    font-size: .82rem;
    font-weight: 600;
    color: var(--text-dark);
    margin-bottom: .3rem;
    line-height: 1.3;
}

.hp-step-sub {
    font-size: .75rem;
    color: var(--text-muted);
    line-height: 1.4;
}

/* ── CTA ───────────────────────────────────────────────────────────── */
.hp-cta {
    border-top: 1px solid var(--border);
    padding-top: 1.5rem;
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: .75rem;
    text-align: center;
}

.hp-cta-btn {
    display: inline-flex;
    align-items: center;
    gap: 9px;
    padding: .9rem 2.75rem;
    background: var(--navy);
    color: #fff;
    border: none;
    border-radius: 10px;
    font-family: 'DM Sans', sans-serif;
    font-size: .95rem;
    font-weight: 700;
    letter-spacing: .2px;
    text-decoration: none;
    cursor: pointer;
    transition: all .25s;
    box-shadow: 0 4px 16px rgba(15,27,53,.2);
}

.hp-cta-btn:hover {
    background: var(--navy-light);
    transform: translateY(-2px);
    box-shadow: 0 8px 24px rgba(15,27,53,.28);
    color: #fff;
}

.hp-cta-btn.disabled {
    background: var(--text-muted);
    cursor: not-allowed;
    box-shadow: none;
    transform: none;
}

.hp-cta-btn i { font-size: .9rem; }

.hp-cta-links {
    display: flex;
    gap: 1.5rem;
    flex-wrap: wrap;
    justify-content: center;
}

.hp-cta-link {
    font-size: .82rem;
    color: var(--text-muted);
    text-decoration: none;
}

.hp-cta-link a {
    color: var(--navy);
    font-weight: 600;
    text-decoration: none;
    border-bottom: 1px solid var(--border-dark);
    padding-bottom: 1px;
    transition: border-color .2s, color .2s;
}

.hp-cta-link a:hover {
    color: var(--gold);
    border-color: var(--gold);
}

/* ── Notice ────────────────────────────────────────────────────────── */
.hp-notice {
    display: flex;
    align-items: flex-start;
    gap: 1rem;
    background: #FFFBF0;
    border: 1px solid rgba(200,150,58,.3);
    border-left: 4px solid var(--gold);
    border-radius: var(--r-card);
    padding: 1rem 1.25rem;
    margin-bottom: var(--gap);
    font-size: .875rem;
    color: #5a4010;
    line-height: 1.55;
}

.hp-notice-icon {
    color: var(--gold);
    font-size: 1rem;
    flex-shrink: 0;
    margin-top: 1px;
}

/* ── Support ───────────────────────────────────────────────────────── */
.hp-support {
    border: 1px solid var(--border);
    border-radius: var(--r-card);
    overflow: hidden;
}

.hp-support-head {
    background: var(--off-white);
    border-bottom: 1px solid var(--border);
    padding: .9rem 1.25rem;
    display: flex;
    align-items: center;
    gap: .75rem;
}

.hp-support-head-icon {
    width: 30px; height: 30px;
    background: var(--navy);
    border-radius: 7px;
    display: flex; align-items: center; justify-content: center;
    color: var(--gold-light);
    font-size: .8rem;
    flex-shrink: 0;
}

.hp-support-head h3 {
    font-family: 'Playfair Display', serif;
    font-size: 1rem;
    font-weight: 600;
    color: var(--text-dark);
    margin: 0;
}

.hp-support-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 1px;
    background: var(--border);
}

.hp-support-item {
    background: var(--white);
    padding: 1.25rem 1rem;
    display: flex;
    flex-direction: column;
    align-items: center;
    text-align: center;
    gap: .6rem;
    transition: background .2s;
}

.hp-support-item:hover { background: var(--off-white); }

.hp-support-dot {
    width: 42px; height: 42px;
    border-radius: 10px;
    display: flex; align-items: center; justify-content: center;
    font-size: 1.1rem;
    color: #fff;
}

.hp-support-dot.phone    { background: var(--navy); }
.hp-support-dot.whatsapp { background: #1BA950; }
.hp-support-dot.email    { background: var(--red); }
.hp-support-dot.hours    { background: var(--sky); }

.hp-support-label {
    font-size: 10px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: .6px;
    color: var(--text-muted);
}

.hp-support-value {
    font-size: .8rem;
    font-weight: 500;
    color: var(--text-dark);
    line-height: 1.45;
    word-break: break-word;
}

/* ── Responsive ────────────────────────────────────────────────────── */
@media (max-width: 900px) {
    .hp-support-grid { grid-template-columns: repeat(2, 1fr); }
}

@media (max-width: 768px) {
    .hp-hero {
        margin: -24px -18px 1.5rem;
        border-radius: 0;
    }

    .hp-stats { grid-template-columns: repeat(3, 1fr); }

    .hp-grid  { grid-template-columns: 1fr; }

    .hp-steps::before { display: none; }

    .hp-steps {
        grid-template-columns: repeat(2, 1fr);
        gap: 1rem 0;
    }

    /* last step centred when count is odd */
    .hp-step:last-child {
        grid-column: 1 / -1;
        max-width: 180px;
        margin: 0 auto;
    }
}

@media (max-width: 540px) {
    .hp-stats {
        grid-template-columns: 1fr;
        gap: 1px;
    }

    .hp-stat { padding: 1rem; }

    .hp-steps { grid-template-columns: 1fr; gap: .75rem; }

    .hp-step {
        flex-direction: row;
        text-align: left;
        gap: 1rem;
        padding: .5rem 0;
    }

    .hp-step-num { margin-bottom: 0; flex-shrink: 0; }

    .hp-support-grid { grid-template-columns: 1fr 1fr; }

    .hp-cta-links { flex-direction: column; gap: .5rem; }

    .hp-row-label { min-width: 90px; font-size: .82rem; }
    .hp-row-value { font-size: .82rem; }
}

@media (max-width: 380px) {
    .hp-support-grid { grid-template-columns: 1fr; }
    .hp-hero-title { font-size: 1.3rem; }
}
</style>

<!-- ── Hero ──────────────────────────────────────────────────────────── -->
<div class="hp-hero">
    <div class="hp-hero-status">
        <span class="hp-hero-status-dot"></span>
        Applications <?php echo $isOpen ? 'Open' : 'Closed'; ?>
    </div>
    <h1 class="hp-hero-title">FCT College of Nursing Sciences</h1>
    <div class="hp-hero-rule"></div>
    <p class="hp-hero-sub">2025/2026 Admissions Application Portal &mdash; ND/HND Nursing Programme</p>
</div>

<!-- ── Key Stats ─────────────────────────────────────────────────────── -->
<div class="hp-stats">
    <div class="hp-stat">
        <div class="hp-stat-value"><?php echo $currency; ?><span><?php echo $fee; ?></span></div>
        <div class="hp-stat-label">Application Fee</div>
    </div>
    <div class="hp-stat">
        <div class="hp-stat-value"><span><?php echo $min_score; ?>+</span></div>
        <div class="hp-stat-label">Min UTME Score</div>
    </div>
    <div class="hp-stat">
        <div class="hp-stat-value"><span>4</span> Yrs</div>
        <div class="hp-stat-label">Programme Duration</div>
    </div>
</div>

<!-- ── Info Grid ─────────────────────────────────────────────────────── -->
<div class="hp-grid">

    <!-- Application Period -->
    <div class="hp-card">
        <div class="hp-card-head">
            <div class="hp-card-icon"><i class="fas fa-calendar-alt"></i></div>
            <h4 class="hp-card-title">Application Period</h4>
        </div>
        <div class="hp-card-body">
            <div class="hp-row">
                <span class="hp-row-label">Form Sales</span>
                <span class="hp-row-value"><?php echo $start_date; ?> &ndash; <?php echo $end_date; ?></span>
            </div>
            <div class="hp-row">
                <span class="hp-row-label">CBT Screening</span>
                <span class="hp-row-value"><?php echo $cbt_start; ?> &ndash; <?php echo $cbt_end; ?></span>
            </div>
            <div class="hp-row">
                <span class="hp-row-label">CBT Venue</span>
                <span class="hp-row-value">FCT College of Nursing Sciences, Gwagwalada (within UATH)</span>
            </div>
            <div class="hp-row">
                <span class="hp-row-label">Reporting Time</span>
                <span class="hp-row-value">8:00 AM daily</span>
            </div>
            <div class="hp-row">
                <span class="hp-row-label">Portal Status</span>
                <span class="hp-row-value">
                    <span class="status-badge <?php echo $isOpen ? 'open' : 'closed'; ?>">
                        <i class="fas fa-<?php echo $isOpen ? 'check-circle' : 'times-circle'; ?>" style="font-size:.7rem"></i>
                        <?php echo $isOpen ? 'Open' : 'Closed'; ?>
                    </span>
                </span>
            </div>
        </div>
    </div>

    <!-- Programme & Eligibility -->
    <div class="hp-card">
        <div class="hp-card-head">
            <div class="hp-card-icon"><i class="fas fa-graduation-cap"></i></div>
            <h4 class="hp-card-title">Programme &amp; Eligibility</h4>
        </div>
        <div class="hp-card-body">
            <div class="hp-row">
                <span class="hp-row-label">Programme</span>
                <span class="hp-row-value">ND/HND Nursing (Non-terminal)</span>
            </div>
            <div class="hp-row">
                <span class="hp-row-label">Duration</span>
                <span class="hp-row-value"><?php echo $duration; ?></span>
            </div>
            <div class="hp-row">
                <span class="hp-row-label">Accreditation</span>
                <span class="hp-row-value">NBTE &amp; NMCN Approved</span>
            </div>
            <div class="hp-row" style="border-bottom:none;padding-bottom:0;align-items:flex-start">
                <span class="hp-row-label" style="padding-top:2px">Requirements</span>
                <ul class="hp-eli-list" style="flex:1">
                    <li>Minimum UTME score of <?php echo $min_score; ?></li>
                    <li>First Choice: FCT College of Nursing Sciences</li>
                    <li>5 O'Level Credits in &le; <?php echo $max_sittings; ?> sittings</li>
                    <li>Minimum age of <?php echo $min_age; ?> years</li>
                    <li>Valid JAMB registration number</li>
                </ul>
            </div>
        </div>
    </div>

</div>

<!-- ── Application Process ───────────────────────────────────────────── -->
<div class="hp-process">
    <div class="hp-process-head">
        <div class="hp-process-head-icon"><i class="fas fa-route"></i></div>
        <h3>Application Process</h3>
    </div>
    <div class="hp-process-body">

        <div class="hp-steps">
            <div class="hp-step">
                <div class="hp-step-num s1">1</div>
                <div>
                    <div class="hp-step-title">Create Account</div>
                    <div class="hp-step-sub">Register &amp; verify your email address</div>
                </div>
            </div>
            <div class="hp-step">
                <div class="hp-step-num s2">2</div>
                <div>
                    <div class="hp-step-title">JAMB Verification</div>
                    <div class="hp-step-sub">Verify your JAMB registration number</div>
                </div>
            </div>
            <div class="hp-step">
                <div class="hp-step-num s3">3</div>
                <div>
                    <div class="hp-step-title">Application Form</div>
                    <div class="hp-step-sub">Fill in your personal details</div>
                </div>
            </div>
            <div class="hp-step">
                <div class="hp-step-num s4">4</div>
                <div>
                    <div class="hp-step-title">Payment</div>
                    <div class="hp-step-sub"><?php echo $currency . $fee; ?> application fee via Remita</div>
                </div>
            </div>
            <div class="hp-step">
                <div class="hp-step-num s5">5</div>
                <div>
                    <div class="hp-step-title">Exam Slip</div>
                    <div class="hp-step-sub">Download your CBT screening slip</div>
                </div>
            </div>
        </div>

        <div class="hp-cta">
            <?php if ($isOpen): ?>
                <a href="/apply/register" class="hp-cta-btn">
                    <i class="fas fa-arrow-right"></i> Start Your Application
                </a>
            <?php else: ?>
                <button class="hp-cta-btn disabled" disabled>
                    <i class="fas fa-ban"></i> Applications Currently Closed
                </button>
            <?php endif; ?>

            <div class="hp-cta-links">
                <span class="hp-cta-link">Already registered? <a href="/applicant/login">Login here</a></span>
                <span class="hp-cta-link">Forgot password? <a href="/applicant/forgot-password">Reset here</a></span>
            </div>
        </div>

    </div>
</div>

<!-- ── Important Notice ──────────────────────────────────────────────── -->
<div class="hp-notice">
    <i class="fas fa-exclamation-triangle hp-notice-icon"></i>
    <div>
        <strong>Important Notice:</strong> No extension of the application deadline will be granted.
        The College has <strong>NO AGENTS</strong>. Beware of fraudulent websites and individuals —
        deal only through official channels listed below.
    </div>
</div>

<!-- ── Support ───────────────────────────────────────────────────────── -->
<div class="hp-support">
    <div class="hp-support-head">
        <div class="hp-support-head-icon"><i class="fas fa-headset"></i></div>
        <h3>Support &amp; Enquiries</h3>
    </div>
    <div class="hp-support-grid">
        <div class="hp-support-item">
            <div class="hp-support-dot phone"><i class="fas fa-phone-alt"></i></div>
            <div class="hp-support-label">Phone</div>
            <div class="hp-support-value"><?php echo $ph1; ?><br><?php echo $ph2; ?></div>
        </div>
        <div class="hp-support-item">
            <div class="hp-support-dot whatsapp"><i class="fab fa-whatsapp"></i></div>
            <div class="hp-support-label">WhatsApp</div>
            <div class="hp-support-value"><?php echo $wa; ?></div>
        </div>
        <div class="hp-support-item">
            <div class="hp-support-dot email"><i class="fas fa-envelope"></i></div>
            <div class="hp-support-label">Email</div>
            <div class="hp-support-value"><?php echo $email; ?></div>
        </div>
        <div class="hp-support-item">
            <div class="hp-support-dot hours"><i class="fas fa-clock"></i></div>
            <div class="hp-support-label">Office Hours</div>
            <div class="hp-support-value"><?php echo $hours; ?></div>
        </div>
    </div>
</div>