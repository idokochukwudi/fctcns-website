<?php
/**
 * Portal Closed View
 * Fits inside the portal layout's $content slot.
 */
?>

<style>
    .closed-wrap {
        display: flex;
        flex-direction: column;
        align-items: center;
        text-align: center;
        padding: 1rem 0 .5rem;
    }

    /* ── Icon badge ─────────────────────────────────────────── */
    .closed-icon-wrap {
        width: 80px; height: 80px;
        background: #FEF3C7;
        border: 1.5px solid rgba(245,158,11,.25);
        border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        font-size: 2rem;
        color: #D97706;
        margin-bottom: 1.5rem;
        flex-shrink: 0;
    }

    /* ── Heading ─────────────────────────────────────────────── */
    .closed-title {
        font-family: 'Playfair Display', Georgia, serif;
        font-size: clamp(1.4rem, 3vw, 1.9rem);
        font-weight: 700;
        color: var(--text-dark);
        margin-bottom: .75rem;
    }

    .closed-message {
        font-size: .95rem;
        color: var(--text-body);
        max-width: 520px;
        line-height: 1.6;
        margin-bottom: 1.75rem;
    }

    /* ── Notice banner ───────────────────────────────────────── */
    .closed-notice {
        display: flex;
        align-items: flex-start;
        gap: .75rem;
        background: #FFFBF0;
        border: 1px solid rgba(200,150,58,.25);
        border-left: 4px solid var(--gold);
        border-radius: 12px;
        padding: 1rem 1.25rem;
        text-align: left;
        max-width: 560px;
        width: 100%;
        margin-bottom: 2rem;
        font-size: .875rem;
        color: #5a4010;
        line-height: 1.6;
    }

    .closed-notice i {
        color: var(--gold);
        font-size: 1rem;
        flex-shrink: 0;
        margin-top: 2px;
    }

    .closed-notice strong { color: #3d2a00; }

    /* ── Divider ─────────────────────────────────────────────── */
    .closed-divider {
        width: 100%;
        max-width: 560px;
        height: 1px;
        background: var(--border);
        margin-bottom: 2rem;
    }

    /* ── Next cycle card ─────────────────────────────────────── */
    .closed-next-card {
        width: 100%;
        max-width: 420px;
        border: 1px solid var(--border);
        border-radius: 14px;
        overflow: hidden;
        margin-bottom: 2rem;
        text-align: left;
    }

    .closed-next-head {
        background: var(--navy);
        padding: .85rem 1.25rem;
        display: flex;
        align-items: center;
        gap: .65rem;
        border-bottom: 2px solid var(--gold);
    }

    .closed-next-head-icon {
        width: 28px; height: 28px;
        background: rgba(200,150,58,0.15);
        border-radius: 6px;
        display: flex; align-items: center; justify-content: center;
        color: var(--gold-light);
        font-size: .75rem;
        flex-shrink: 0;
    }

    .closed-next-head h5 {
        font-family: 'Playfair Display', serif;
        font-size: .95rem;
        font-weight: 600;
        color: #fff;
        margin: 0;
    }

    .closed-next-body {
        background: var(--off-white);
        padding: 1.25rem;
    }

    .closed-next-body p {
        font-size: .875rem;
        color: var(--text-body);
        line-height: 1.6;
        margin-bottom: .6rem;
    }

    .closed-next-body p:last-child { margin-bottom: 0; }

    /* ── Action buttons ──────────────────────────────────────── */
    .closed-actions {
        display: flex;
        gap: .75rem;
        flex-wrap: wrap;
        justify-content: center;
    }

    .btn-closed-primary {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        padding: .75rem 1.75rem;
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
    }

    .btn-closed-primary:hover {
        background: var(--navy-light);
        transform: translateY(-1px);
        box-shadow: 0 8px 20px rgba(15,27,53,.28);
        color: #fff;
    }

    .btn-closed-outline {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        padding: .75rem 1.75rem;
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
    }

    .btn-closed-outline:hover {
        background: var(--off-white);
        border-color: var(--navy);
        color: var(--navy);
    }

    @media (max-width: 480px) {
        .closed-actions { flex-direction: column; width: 100%; max-width: 320px; }
        .btn-closed-primary,
        .btn-closed-outline { width: 100%; justify-content: center; }
    }
</style>

<div class="closed-wrap">

    <!-- Icon -->
    <div class="closed-icon-wrap">
        <i class="fas fa-clock"></i>
    </div>

    <!-- Heading -->
    <h2 class="closed-title">Application Portal is Currently Closed</h2>
    <p class="closed-message"><?php echo htmlspecialchars($portal_message); ?></p>

    <!-- Notice -->
    <div class="closed-notice">
        <i class="fas fa-exclamation-triangle"></i>
        <div>
            <strong>Important Notice:</strong> No extension of the application deadline will be granted.
            The College has <strong>NO AGENTS</strong>. Beware of fraudulent websites and individuals —
            deal only through official channels.
        </div>
    </div>

    <div class="closed-divider"></div>

    <!-- Next admissions cycle -->
    <div class="closed-next-card">
        <div class="closed-next-head">
            <div class="closed-next-head-icon">
                <i class="fas fa-calendar-alt"></i>
            </div>
            <h5>Next Admissions Cycle</h5>
        </div>
        <div class="closed-next-body">
            <p>The next admissions cycle will be for the <strong>2026/2027 academic session</strong>.</p>
            <p>Check back regularly for updates and announcements regarding the next admissions cycle.</p>
        </div>
    </div>

    <!-- Actions -->
    <div class="closed-actions">
        <a href="/" class="btn-closed-primary">
            <i class="fas fa-home"></i> Return to Homepage
        </a>
        <a href="/contact" class="btn-closed-outline">
            <i class="fas fa-envelope"></i> Contact Admissions
        </a>
    </div>

</div>