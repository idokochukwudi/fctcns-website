<?php
/**
 * Home / Landing Page View
 * Fits inside the portal layout's $content slot.
 */

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
/* ── Page-level variables ──────────────────────────────────────────── */
.hp {
    /* inherits layout :root tokens */
    --gap: clamp(.9rem, 2.5vw, 1.75rem);
    --r:   12px;
}

/* ── Hero band ─────────────────────────────────────────────────────── */
.hp-hero {
    background: linear-gradient(150deg, var(--pu-deeper, #4C1D95) 0%, var(--pu-dark, #5B21B6) 55%, var(--pu, #7C3AED) 100%);
    border-radius: var(--r) var(--r) 0 0;
    padding: clamp(2rem, 5vw, 3rem) clamp(1.5rem, 4vw, 2.5rem);
    margin: -36px -40px 1.75rem;   /* bleed into portal-body padding */
    text-align: center;
    position: relative;
    overflow: hidden;
}

/* Decorative circle rings */
.hp-hero::before,
.hp-hero::after {
    content: '';
    position: absolute;
    border-radius: 50%;
    pointer-events: none;
}
.hp-hero::before {
    width: 400px; height: 400px;
    border: 1px solid rgba(255,255,255,.06);
    top: -180px; right: -80px;
}
.hp-hero::after {
    width: 260px; height: 260px;
    border: 1px solid rgba(255,255,255,.04);
    bottom: -110px; left: -60px;
}

.hp-hero-status {
    display: inline-flex;
    align-items: center;
    gap: 7px;
    border-radius: 50px;
    padding: 5px 14px;
    font-family: var(--font-ui, 'Outfit', sans-serif);
    font-size: 11px;
    font-weight: 700;
    letter-spacing: .06em;
    text-transform: uppercase;
    margin-bottom: 1.1rem;
    position: relative;
    z-index: 1;
    background: <?php echo $isOpen ? 'rgba(26,107,69,.22)' : 'rgba(185,28,28,.22)'; ?>;
    border: 1px solid <?php echo $isOpen ? 'rgba(26,107,69,.4)' : 'rgba(185,28,28,.4)'; ?>;
    color: <?php echo $isOpen ? '#6ee7b7' : '#fca5a5'; ?>;
}

.hp-status-dot {
    width: 7px; height: 7px;
    border-radius: 50%;
    background: <?php echo $isOpen ? '#1a6b45' : '#b91c1c'; ?>;
    <?php if ($isOpen): ?>animation: pulse-dot 1.8s ease infinite;<?php endif; ?>
}

@keyframes pulse-dot {
    0%, 100% { opacity: 1; transform: scale(1); }
    50%       { opacity: .5; transform: scale(1.45); }
}

.hp-hero-title {
    font-family: var(--font-serif, 'Source Serif 4', Georgia, serif);
    font-size: clamp(1.4rem, 3.8vw, 2.2rem);
    font-weight: 600;
    color: #fff;
    line-height: 1.22;
    margin-bottom: .5rem;
    position: relative;
    z-index: 1;
}

.hp-hero-rule {
    width: 48px; height: 2px;
    background: rgba(255,255,255,.3);
    border-radius: 2px;
    margin: 1rem auto;
    position: relative;
    z-index: 1;
}

.hp-hero-sub {
    font-family: var(--font-ui, 'Outfit', sans-serif);
    font-size: clamp(.82rem, 2vw, .95rem);
    color: rgba(255,255,255,.52);
    position: relative;
    z-index: 1;
    margin: 0;
}

/* ── Stats strip ───────────────────────────────────────────────────── */
.hp-stats {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 1px;
    background: var(--border, #DDD6FE);
    border: 1px solid var(--border, #DDD6FE);
    border-radius: var(--r);
    overflow: hidden;
    margin-bottom: var(--gap);
}

.hp-stat {
    background: var(--pu-pale, #F5F3FF);
    padding: 1.2rem 1rem;
    text-align: center;
}

.hp-stat-val {
    font-family: var(--font-serif, 'Source Serif 4', Georgia, serif);
    font-size: clamp(1.3rem, 3vw, 1.8rem);
    font-weight: 700;
    color: var(--pu-dark, #5B21B6);
    line-height: 1;
    margin-bottom: .3rem;
}

.hp-stat-val em { font-style: normal; color: var(--pu, #7C3AED); }

.hp-stat-lbl {
    font-family: var(--font-ui, 'Outfit', sans-serif);
    font-size: 10.5px;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: .06em;
    color: var(--text-muted, #6D6A8A);
}

/* ── Info grid ─────────────────────────────────────────────────────── */
.hp-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: var(--gap);
    margin-bottom: var(--gap);
}

.hp-card {
    border: 1px solid var(--border, #DDD6FE);
    border-radius: var(--r);
    overflow: hidden;
}

.hp-card-head {
    display: flex;
    align-items: center;
    gap: .75rem;
    padding: .85rem 1.2rem;
    background: linear-gradient(135deg, var(--pu-deeper, #4C1D95), var(--pu-dark, #5B21B6));
}

.hp-card-icon {
    width: 30px; height: 30px;
    background: rgba(255,255,255,.12);
    border-radius: 7px;
    display: flex; align-items: center; justify-content: center;
    color: rgba(255,255,255,.85);
    font-size: .8rem;
    flex-shrink: 0;
}

.hp-card-title {
    font-family: var(--font-serif, 'Source Serif 4', Georgia, serif);
    font-size: .92rem;
    font-weight: 600;
    color: #fff;
    margin: 0;
}

.hp-card-body {
    padding: 1.1rem 1.2rem;
    background: var(--surface, #fff);
}

.hp-row {
    display: flex;
    align-items: baseline;
    gap: .5rem;
    padding: .52rem 0;
    border-bottom: 1px solid var(--border, #DDD6FE);
    font-family: var(--font-ui, 'Outfit', sans-serif);
    font-size: .86rem;
}

.hp-row:last-child { border-bottom: none; padding-bottom: 0; }

.hp-row-lbl {
    font-weight: 600;
    color: var(--pu-dark, #5B21B6);
    white-space: nowrap;
    flex-shrink: 0;
    min-width: 108px;
    font-size: .82rem;
}

.hp-row-val { color: var(--text-body, #312E81); line-height: 1.45; }

/* Status badge */
.status-pill {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    padding: 3px 10px;
    border-radius: 50px;
    font-size: 11px;
    font-weight: 700;
    letter-spacing: .03em;
    font-family: var(--font-ui, 'Outfit', sans-serif);
}
.status-pill.open   { background: var(--green-bg, #edf9f3); color: var(--green, #1a6b45); border: 1px solid #b2dfcc; }
.status-pill.closed { background: var(--red-bg,   #fdf2f2); color: var(--red,   #b91c1c); border: 1px solid #fca5a5; }

/* Eligibility list */
.eli-list {
    list-style: none;
    padding: 0; margin: 0;
    display: flex;
    flex-direction: column;
    gap: .42rem;
}

.eli-list li {
    display: flex;
    align-items: flex-start;
    gap: .6rem;
    font-family: var(--font-ui, 'Outfit', sans-serif);
    font-size: .84rem;
    color: var(--text-body, #312E81);
    line-height: 1.45;
}

.eli-list li::before {
    content: '';
    width: 17px; height: 17px;
    flex-shrink: 0;
    margin-top: 1px;
    border-radius: 50%;
    background: var(--pu-pale, #F5F3FF) url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 12 12'%3E%3Cpath d='M2 6l3 3 5-5' stroke='%236E026F' stroke-width='1.8' fill='none' stroke-linecap='round' stroke-linejoin='round'/%3E%3C/svg%3E") center/10px no-repeat;
    border: 1px solid var(--border, #DDD6FE);
}

/* ── Process section ───────────────────────────────────────────────── */
.hp-process {
    border: 1px solid var(--border, #DDD6FE);
    border-radius: var(--r);
    overflow: hidden;
    margin-bottom: var(--gap);
}

.hp-process-head {
    background: var(--pu-pale, #F5F3FF);
    border-bottom: 1px solid var(--border, #DDD6FE);
    padding: .9rem 1.25rem;
    display: flex;
    align-items: center;
    gap: .7rem;
}

.hp-process-icon {
    width: 28px; height: 28px;
    background: var(--pu, #7C3AED);
    border-radius: 7px;
    display: flex; align-items: center; justify-content: center;
    color: #fff;
    font-size: .75rem;
    flex-shrink: 0;
}

.hp-process-head h3 {
    font-family: var(--font-serif, 'Source Serif 4', Georgia, serif);
    font-size: .95rem;
    font-weight: 600;
    color: var(--pu-dark, #5B21B6);
    margin: 0;
}

.hp-process-body {
    padding: 1.75rem 1.5rem;
    background: var(--surface, #fff);
}

/* Step bubbles */
.hp-steps {
    display: grid;
    grid-template-columns: repeat(5, 1fr);
    position: relative;
    margin-bottom: 1.75rem;
}

/* Connector line */
.hp-steps::before {
    content: '';
    position: absolute;
    top: 23px;
    left: calc(10% + 22px);
    right: calc(10% + 22px);
    height: 1.5px;
    background: var(--border, #DDD6FE);
    z-index: 0;
}

.hp-step {
    display: flex;
    flex-direction: column;
    align-items: center;
    text-align: center;
    padding: 0 .4rem;
    position: relative;
    z-index: 1;
}

.hp-step-num {
    width: 46px; height: 46px;
    border-radius: 50%;
    display: flex; align-items: center; justify-content: center;
    font-family: var(--font-ui, 'Outfit', sans-serif);
    font-size: 1rem;
    font-weight: 700;
    margin-bottom: .8rem;
    transition: transform .22s;
    flex-shrink: 0;
}

.hp-step:hover .hp-step-num { transform: translateY(-3px); }

/* Step 1 = active/brand; rest = muted */
.hp-step-num.s1 {
    background: linear-gradient(135deg, var(--pu-dark, #5B21B6), var(--pu, #7C3AED));
    color: #fff;
    box-shadow: 0 3px 12px rgba(110,2,111,.28);
}

.hp-step-num.s2,
.hp-step-num.s3,
.hp-step-num.s4,
.hp-step-num.s5 {
    background: var(--pu-pale, #F5F3FF);
    color: var(--text-muted, #6D6A8A);
    border: 1.5px solid var(--border-dark, #C4B5FD);
}

.hp-step-title {
    font-family: var(--font-ui, 'Outfit', sans-serif);
    font-size: .8rem;
    font-weight: 700;
    color: var(--text, #1E1B4B);
    margin-bottom: .25rem;
    line-height: 1.3;
}

.hp-step-sub {
    font-family: var(--font-ui, 'Outfit', sans-serif);
    font-size: .73rem;
    color: var(--text-muted, #6D6A8A);
    line-height: 1.4;
}

/* ── CTA ───────────────────────────────────────────────────────────── */
.hp-cta {
    border-top: 1px solid var(--border, #DDD6FE);
    padding-top: 1.4rem;
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: .7rem;
    text-align: center;
}

.hp-cta-btn {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: .85rem 2.5rem;
    background: linear-gradient(135deg, var(--pu-dark, #5B21B6), var(--pu, #7C3AED));
    color: #fff;
    border: none;
    border-radius: 10px;
    font-family: var(--font-ui, 'Outfit', sans-serif);
    font-size: .92rem;
    font-weight: 700;
    text-decoration: none;
    cursor: pointer;
    transition: transform .2s, box-shadow .2s, opacity .2s;
    box-shadow: 0 3px 14px rgba(110,2,111,.28);
    letter-spacing: .02em;
}

.hp-cta-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 22px rgba(110,2,111,.38);
    color: #fff;
}

.hp-cta-btn.disabled {
    background: var(--border-dark, #C4B5FD);
    cursor: not-allowed;
    box-shadow: none;
    transform: none;
    opacity: .7;
}

.hp-cta-links {
    display: flex;
    gap: 1.5rem;
    flex-wrap: wrap;
    justify-content: center;
}

.hp-cta-link {
    font-family: var(--font-ui, 'Outfit', sans-serif);
    font-size: .8rem;
    color: var(--text-muted, #6D6A8A);
}

.hp-cta-link a {
    color: var(--pu, #7C3AED);
    font-weight: 600;
    text-decoration: none;
    border-bottom: 1px solid var(--border-dark, #C4B5FD);
    padding-bottom: 1px;
    transition: color .18s, border-color .18s;
}

.hp-cta-link a:hover {
    color: var(--pu-dark, #5B21B6);
    border-color: var(--pu, #7C3AED);
}

/* ── Notice ────────────────────────────────────────────────────────── */
.hp-notice {
    display: flex;
    align-items: flex-start;
    gap: .9rem;
    background: #fffbeb;
    border: 1px solid #fde68a;
    border-left: 4px solid #d97706;
    border-radius: var(--r);
    padding: .95rem 1.2rem;
    margin-bottom: var(--gap);
    font-family: var(--font-ui, 'Outfit', sans-serif);
    font-size: .85rem;
    color: #78350f;
    line-height: 1.55;
}

.hp-notice-icon { color: #d97706; font-size: .95rem; flex-shrink: 0; margin-top: 1px; }

/* ── Support grid ──────────────────────────────────────────────────── */
.hp-support {
    border: 1px solid var(--border, #DDD6FE);
    border-radius: var(--r);
    overflow: hidden;
}

.hp-support-head {
    background: var(--pu-pale, #F5F3FF);
    border-bottom: 1px solid var(--border, #DDD6FE);
    padding: .85rem 1.2rem;
    display: flex;
    align-items: center;
    gap: .7rem;
}

.hp-support-icon {
    width: 28px; height: 28px;
    background: var(--pu, #7C3AED);
    border-radius: 7px;
    display: flex; align-items: center; justify-content: center;
    color: #fff;
    font-size: .75rem;
    flex-shrink: 0;
}

.hp-support-head h3 {
    font-family: var(--font-serif, 'Source Serif 4', Georgia, serif);
    font-size: .92rem;
    font-weight: 600;
    color: var(--pu-dark, #5B21B6);
    margin: 0;
}

.hp-support-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 1px;
    background: var(--border, #DDD6FE);
}

.hp-support-item {
    background: var(--surface, #fff);
    padding: 1.2rem 1rem;
    display: flex;
    flex-direction: column;
    align-items: center;
    text-align: center;
    gap: .55rem;
    transition: background .18s;
}

.hp-support-item:hover { background: var(--pu-pale, #F5F3FF); }

.hp-support-dot {
    width: 40px; height: 40px;
    border-radius: 10px;
    display: flex; align-items: center; justify-content: center;
    font-size: 1.05rem;
    color: #fff;
    flex-shrink: 0;
}

.hp-support-dot.phone    { background: var(--pu-dark, #5B21B6); }
.hp-support-dot.whatsapp { background: #1ba950; }
.hp-support-dot.email    { background: #b91c1c; }
.hp-support-dot.hours    { background: #1d4ed8; }

.hp-support-lbl {
    font-family: var(--font-ui, 'Outfit', sans-serif);
    font-size: 9.5px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: .07em;
    color: var(--text-muted, #6D6A8A);
}

.hp-support-val {
    font-family: var(--font-ui, 'Outfit', sans-serif);
    font-size: .78rem;
    font-weight: 500;
    color: var(--text, #1E1B4B);
    line-height: 1.5;
    word-break: break-word;
}

/* ── Responsive ────────────────────────────────────────────────────── */
@media (max-width: 900px) {
    .hp-support-grid { grid-template-columns: repeat(2, 1fr); }
}

@media (max-width: 768px) {
    .hp-hero { margin: -24px -18px 1.4rem; border-radius: 0; }
    .hp-grid { grid-template-columns: 1fr; }
    .hp-steps::before { display: none; }
    .hp-steps { grid-template-columns: repeat(2, 1fr); gap: 1rem 0; }
    .hp-step:last-child { grid-column: 1 / -1; max-width: 170px; margin: 0 auto; }
}

@media (max-width: 560px) {
    .hp-stats { grid-template-columns: 1fr; }
    .hp-stat  { padding: .9rem 1rem; }

    .hp-steps { grid-template-columns: 1fr; gap: .6rem; }

    .hp-step {
        flex-direction: row;
        text-align: left;
        gap: .9rem;
        padding: .4rem 0;
    }

    .hp-step-num  { margin-bottom: 0; }
    .hp-step:last-child { grid-column: auto; max-width: none; margin: 0; }

    .hp-support-grid { grid-template-columns: repeat(2, 1fr); }
    .hp-cta-links    { flex-direction: column; gap: .4rem; }

    .hp-row-lbl { min-width: 88px; }
}

@media (max-width: 380px) {
    .hp-support-grid { grid-template-columns: 1fr; }
    .hp-hero { padding: 1.5rem 1rem; }
}
</style>

<!-- ── Hero ─────────────────────────────────────────────────────────── -->
<div class="hp-hero">
    <div class="hp-hero-status">
        <span class="hp-status-dot"></span>
        Applications <?php echo $isOpen ? 'Open' : 'Closed'; ?>
    </div>
    <h1 class="hp-hero-title">2025/2026 Admissions Application Portal</h1>
    <div class="hp-hero-rule"></div>
    <p class="hp-hero-sub">ND/HND Nursing Programme &mdash; FCT College of Nursing Sciences</p>
</div>

<!-- ── Stats ─────────────────────────────────────────────────────────── -->
<div class="hp-stats">
    <div class="hp-stat">
        <div class="hp-stat-val"><?php echo $currency; ?><em><?php echo $fee; ?></em></div>
        <div class="hp-stat-lbl">Application Fee</div>
    </div>
    <div class="hp-stat">
        <div class="hp-stat-val"><em><?php echo $min_score; ?>+</em></div>
        <div class="hp-stat-lbl">Min UTME Score</div>
    </div>
    <div class="hp-stat">
        <div class="hp-stat-val"><em>4</em> Yrs</div>
        <div class="hp-stat-lbl">Programme Duration</div>
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
                <span class="hp-row-lbl">Form Sales</span>
                <span class="hp-row-val"><?php echo $start_date; ?> &ndash; <?php echo $end_date; ?></span>
            </div>
            <div class="hp-row">
                <span class="hp-row-lbl">CBT Screening</span>
                <span class="hp-row-val"><?php echo $cbt_start; ?> &ndash; <?php echo $cbt_end; ?></span>
            </div>
            <div class="hp-row">
                <span class="hp-row-lbl">CBT Venue</span>
                <span class="hp-row-val">FCT College of Nursing Sciences, Gwagwalada (within UATH)</span>
            </div>
            <div class="hp-row">
                <span class="hp-row-lbl">Reporting Time</span>
                <span class="hp-row-val">8:00 AM daily</span>
            </div>
            <div class="hp-row">
                <span class="hp-row-lbl">Portal Status</span>
                <span class="hp-row-val">
                    <span class="status-pill <?php echo $isOpen ? 'open' : 'closed'; ?>">
                        <i class="fas fa-<?php echo $isOpen ? 'check-circle' : 'times-circle'; ?>" style="font-size:.65rem;"></i>
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
                <span class="hp-row-lbl">Programme</span>
                <span class="hp-row-val">ND/HND Nursing (Non-terminal)</span>
            </div>
            <div class="hp-row">
                <span class="hp-row-lbl">Duration</span>
                <span class="hp-row-val"><?php echo $duration; ?></span>
            </div>
            <div class="hp-row">
                <span class="hp-row-lbl">Accreditation</span>
                <span class="hp-row-val">NBTE &amp; NMCN Approved</span>
            </div>
            <div class="hp-row" style="align-items:flex-start;">
                <span class="hp-row-lbl" style="padding-top:2px;">Requirements</span>
                <ul class="eli-list" style="flex:1;">
                    <li>Minimum UTME score of <?php echo $min_score; ?></li>
                    <li>First Choice: FCT College of Nursing Sciences</li>
                    <li>5 O'Level Credits in &le; <?php echo $max_sittings; ?> sittings</li>
                    <li>Minimum age of <?php echo $min_age; ?> years</li>
                    <li>Valid JAMB registration number</li>
                </ul>
            </div>
        </div>
    </div>

</div><!-- /hp-grid -->

<!-- ── Process ───────────────────────────────────────────────────────── -->
<div class="hp-process">
    <div class="hp-process-head">
        <div class="hp-process-icon"><i class="fas fa-route"></i></div>
        <h3>Application Process</h3>
    </div>
    <div class="hp-process-body">

        <div class="hp-steps">
            <div class="hp-step">
                <div class="hp-step-num s1">1</div>
                <div>
                    <div class="hp-step-title">Create Account</div>
                    <div class="hp-step-sub">Register &amp; verify email</div>
                </div>
            </div>
            <div class="hp-step">
                <div class="hp-step-num s2">2</div>
                <div>
                    <div class="hp-step-title">JAMB Verification</div>
                    <div class="hp-step-sub">Verify your JAMB number</div>
                </div>
            </div>
            <div class="hp-step">
                <div class="hp-step-num s3">3</div>
                <div>
                    <div class="hp-step-title">Application Form</div>
                    <div class="hp-step-sub">Fill personal details</div>
                </div>
            </div>
            <div class="hp-step">
                <div class="hp-step-num s4">4</div>
                <div>
                    <div class="hp-step-title">Payment</div>
                    <div class="hp-step-sub"><?php echo $currency . $fee; ?> via Remita</div>
                </div>
            </div>
            <div class="hp-step">
                <div class="hp-step-num s5">5</div>
                <div>
                    <div class="hp-step-title">Exam Slip</div>
                    <div class="hp-step-sub">Download CBT slip</div>
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
</div><!-- /hp-process -->

<!-- ── Notice ────────────────────────────────────────────────────────── -->
<div class="hp-notice">
    <i class="fas fa-exclamation-triangle hp-notice-icon"></i>
    <div>
        <strong>Important Notice:</strong> No extension of the application deadline will be granted.
        The College has <strong>NO AGENTS</strong>. Beware of fraudulent websites and individuals —
        deal only through the official channels listed below.
    </div>
</div>

<!-- ── Support ───────────────────────────────────────────────────────── -->
<div class="hp-support">
    <div class="hp-support-head">
        <div class="hp-support-icon"><i class="fas fa-headset"></i></div>
        <h3>Support &amp; Enquiries</h3>
    </div>
    <div class="hp-support-grid">
        <div class="hp-support-item">
            <div class="hp-support-dot phone"><i class="fas fa-phone-alt"></i></div>
            <div class="hp-support-lbl">Phone</div>
            <div class="hp-support-val"><?php echo $ph1; ?><br><?php echo $ph2; ?></div>
        </div>
        <div class="hp-support-item">
            <div class="hp-support-dot whatsapp"><i class="fab fa-whatsapp"></i></div>
            <div class="hp-support-lbl">WhatsApp</div>
            <div class="hp-support-val"><?php echo $wa; ?></div>
        </div>
        <div class="hp-support-item">
            <div class="hp-support-dot email"><i class="fas fa-envelope"></i></div>
            <div class="hp-support-lbl">Email</div>
            <div class="hp-support-val"><?php echo $email; ?></div>
        </div>
        <div class="hp-support-item">
            <div class="hp-support-dot hours"><i class="fas fa-clock"></i></div>
            <div class="hp-support-lbl">Office Hours</div>
            <div class="hp-support-val"><?php echo $hours; ?></div>
        </div>
    </div>
</div>