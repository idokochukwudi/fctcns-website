<?php
/**
 * Step 1 — JAMB Verification (partial view)
 *
 * This file is rendered INSIDE layout.php via $this->render('applications/step1').
 * The layout calls extract($this->data) before capturing this file, so all
 * controller-injected variables ($csp_nonce, $csrf_token, $terms, etc.) are
 * in scope here.
 */

// ── Safety: abort if rendered outside the layout (no $csp_nonce in scope) ──
if (!isset($csp_nonce)) {
    // Fallback: read from session (ApplicationBaseController always sets this)
    $csp_nonce = $_SESSION['csp_nonce'] ?? '';
}
if (!isset($csrf_token)) {
    $csrf_token = $_SESSION['csrf_token'] ?? '';
}

// ── Data defaults ──────────────────────────────────────────────────────────
$terms          = $terms          ?? [];
$settings       = $settings       ?? [];
$portal_closed  = $portal_closed  ?? false;
$portal_message = $portal_message ?? '';

// Already-verified state (controller sets these when JAMB was previously verified)
$jambAlreadyVerified = $jamb_already_verified ?? false;
$jambNumber          = $jamb_number          ?? '';
$jambName            = $jamb_name            ?? '';
$jambVerified        = $jamb_verified        ?? false;
$jambData            = $jamb_data            ?? [];

// Escape helper (inline — no $this in partial scope)
function e1($v) { return htmlspecialchars((string)$v, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8'); }
?>

<?php /* ── Scoped styles for this view only ─────────────────────────────── */ ?>
<style nonce="<?php echo e1($csp_nonce); ?>">
    /* ── Design tokens (scoped — override layout tokens only where needed) ─ */
    .sv1 {
        --sv1-primary:       #6B4E9B;
        --sv1-primary-dark:  #4A3B6B;
        --sv1-primary-light: #8A6FB0;
        --sv1-primary-soft:  #F3EAF8;
        --sv1-gold:          #C9A44A;
        --sv1-success:       #10b981;
        --sv1-success-light: #d1fae5;
        --sv1-danger:        #ef4444;
        --sv1-danger-light:  #fee2e2;
        --sv1-warning:       #f59e0b;
        --sv1-warning-light: #fef3c7;
        --sv1-info:          #3b82f6;
        --sv1-info-light:    #dbeafe;
        --sv1-border:        #E9EDF2;
        --sv1-text-dark:     #1A1F2E;
        --sv1-text-muted:    #6B7280;
        --sv1-radius-md:     12px;
        --sv1-radius-lg:     20px;
        --sv1-radius-xl:     30px;
        --sv1-shadow-primary: 0 10px 30px rgba(107,78,155,0.3);
    }

    /* ── Card ──────────────────────────────────────────────────────────── */
    .sv1 .sv1-card {
        background: #fff;
        border-radius: var(--sv1-radius-xl);
        box-shadow: 0 20px 40px rgba(0,0,0,.12);
        overflow: hidden;
    }

    .sv1 .sv1-card-head {
        background: linear-gradient(135deg, var(--sv1-primary) 0%, var(--sv1-primary-dark) 100%);
        color: #fff;
        padding: 30px;
        text-align: center;
    }

    .sv1 .sv1-card-head i   { font-size: 3rem; color: var(--sv1-gold); margin-bottom: 15px; display: block; }
    .sv1 .sv1-card-head h2  { font-size: 26px; font-weight: 700; margin-bottom: 8px; color: #fff; }
    .sv1 .sv1-card-head p   { font-size: 14px; color: rgba(255,255,255,.9); margin: 0; }

    .sv1 .sv1-card-body { padding: 36px 40px; }

    @media (max-width: 576px) {
        .sv1 .sv1-card-body  { padding: 22px 18px; }
        .sv1 .sv1-card-head  { padding: 22px; }
        .sv1 .sv1-card-head h2 { font-size: 20px; }
    }

    /* ── Form ───────────────────────────────────────────────────────────── */
    .sv1 .sv1-label {
        font-weight: 600;
        color: var(--sv1-primary-dark);
        font-size: 13.5px;
        margin-bottom: 7px;
        display: flex;
        align-items: center;
        gap: 7px;
    }

    .sv1 .sv1-input {
        border: 2px solid var(--sv1-border);
        border-radius: var(--sv1-radius-md);
        padding: 13px 15px;
        font-size: 17px;
        letter-spacing: 1px;
        width: 100%;
        transition: border-color .25s, box-shadow .25s;
        color: var(--sv1-text-dark);
        font-family: inherit;
    }

    .sv1 .sv1-input:focus {
        border-color: var(--sv1-primary);
        box-shadow: 0 0 0 3px rgba(107,78,155,.15);
        outline: none;
    }

    .sv1 .sv1-hint { font-size: 12px; color: var(--sv1-text-muted); margin-top: 5px; }

    /* ── Checkbox ───────────────────────────────────────────────────────── */
    .sv1 .sv1-check { display: flex; align-items: flex-start; gap: 10px; margin: 18px 0; }

    .sv1 .sv1-check input[type="checkbox"] {
        width: 18px;
        height: 18px;
        flex-shrink: 0;
        margin-top: 2px;
        accent-color: var(--sv1-primary);
        cursor: pointer;
    }

    .sv1 .sv1-check label {
        font-size: 14px;
        color: var(--sv1-text-dark);
        cursor: pointer;
        line-height: 1.5;
    }

    .sv1 .sv1-check label a { color: var(--sv1-primary); font-weight: 500; text-decoration: none; }
    .sv1 .sv1-check label a:hover { text-decoration: underline; }

    /* ── Info box ───────────────────────────────────────────────────────── */
    .sv1 .sv1-info {
        background: var(--sv1-info-light);
        border-left: 4px solid var(--sv1-info);
        border-radius: var(--sv1-radius-md);
        padding: 16px 18px;
        margin: 22px 0;
        font-size: 13.5px;
        color: var(--sv1-text-dark);
    }

    .sv1 .sv1-info i { color: var(--sv1-info); }
    .sv1 .sv1-info ul { margin: 8px 0 0 18px; }
    .sv1 .sv1-info li { margin-bottom: 4px; }

    /* ── Already-verified banner ────────────────────────────────────────── */
    .sv1 .sv1-verified-banner {
        background: var(--sv1-success-light);
        border: 1px solid var(--sv1-success);
        border-radius: var(--sv1-radius-md);
        padding: 18px 22px;
        margin-bottom: 22px;
        display: flex;
        align-items: flex-start;
        gap: 14px;
    }

    .sv1 .sv1-verified-banner i { color: var(--sv1-success); font-size: 22px; flex-shrink: 0; margin-top: 2px; }
    .sv1 .sv1-verified-banner h5 { font-size: 15px; font-weight: 700; color: #065f46; margin: 0 0 4px; }
    .sv1 .sv1-verified-banner p  { font-size: 13.5px; color: #064e3b; margin: 0; }

    /* ── Buttons ────────────────────────────────────────────────────────── */
    .sv1 .sv1-btn-primary {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 9px;
        width: 100%;
        padding: 14px 28px;
        background: linear-gradient(135deg, var(--sv1-primary), var(--sv1-primary-dark));
        color: #fff;
        border: none;
        border-radius: var(--sv1-radius-md);
        font-size: 16px;
        font-weight: 600;
        cursor: pointer;
        box-shadow: var(--sv1-shadow-primary);
        transition: transform .2s, box-shadow .2s;
        font-family: inherit;
    }

    .sv1 .sv1-btn-primary:hover:not(:disabled) {
        transform: translateY(-2px);
        box-shadow: 0 15px 35px rgba(107,78,155,.4);
    }

    .sv1 .sv1-btn-primary:disabled { opacity: .65; cursor: not-allowed; }

    .sv1 .sv1-btn-outline {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 11px 24px;
        background: transparent;
        border: 2px solid var(--sv1-primary);
        color: var(--sv1-primary);
        border-radius: var(--sv1-radius-md);
        font-size: 14px;
        font-weight: 600;
        text-decoration: none;
        transition: background .2s, color .2s, transform .2s;
        font-family: inherit;
        cursor: pointer;
    }

    .sv1 .sv1-btn-outline:hover {
        background: var(--sv1-primary);
        color: #fff;
        transform: translateY(-2px);
    }

    /* ── Alert ──────────────────────────────────────────────────────────── */
    .sv1 #sv1Alert {
        border-radius: var(--sv1-radius-md);
        padding: 13px 16px;
        margin-bottom: 20px;
        display: none;          /* shown by JS */
        align-items: center;
        gap: 10px;
        font-size: 13.5px;
        border-left-width: 4px;
        border-left-style: solid;
        animation: sv1SlideIn .25s ease;
    }

    @keyframes sv1SlideIn {
        from { opacity: 0; transform: translateY(-8px); }
        to   { opacity: 1; transform: translateY(0); }
    }

    .sv1 #sv1Alert.sv1-success { background: var(--sv1-success-light); border-color: var(--sv1-success);  color: #065f46; }
    .sv1 #sv1Alert.sv1-danger  { background: var(--sv1-danger-light);  border-color: var(--sv1-danger);   color: #991b1b; }
    .sv1 #sv1Alert.sv1-info    { background: var(--sv1-info-light);    border-color: var(--sv1-info);     color: #1e40af; }
    .sv1 #sv1Alert.sv1-warning { background: var(--sv1-warning-light); border-color: var(--sv1-warning);  color: #92400e; }

    /* The dismiss button */
    .sv1 #sv1AlertClose {
        margin-left: auto;
        background: none;
        border: none;
        font-size: 18px;
        cursor: pointer;
        opacity: .55;
        color: currentColor;
        line-height: 1;
        padding: 0 4px;
    }

    .sv1 #sv1AlertClose:hover { opacity: 1; }

    /* ── Divider ────────────────────────────────────────────────────────── */
    .sv1 .sv1-divider { text-align: center; margin: 28px 0; position: relative; }

    .sv1 .sv1-divider::before {
        content: '';
        position: absolute;
        top: 50%; left: 0; right: 0;
        height: 1px;
        background: var(--sv1-border);
    }

    .sv1 .sv1-divider span {
        position: relative;
        background: #fff;
        padding: 0 14px;
        color: var(--sv1-text-muted);
        font-size: 13px;
    }

    /* ── Spinner ────────────────────────────────────────────────────────── */
    .sv1-spinner {
        display: inline-block;
        width: 16px; height: 16px;
        border: 2px solid rgba(255,255,255,.4);
        border-top-color: #fff;
        border-radius: 50%;
        animation: sv1Spin .7s linear infinite;
        vertical-align: middle;
    }

    @keyframes sv1Spin { to { transform: rotate(360deg); } }

    /* ── Portal closed ──────────────────────────────────────────────────── */
    .sv1 .sv1-closed {
        background: #fff;
        border-radius: var(--sv1-radius-xl);
        padding: 40px;
        text-align: center;
    }

    .sv1 .sv1-closed i  { font-size: 4rem; color: var(--sv1-warning); margin-bottom: 20px; display: block; }
    .sv1 .sv1-closed h2 { color: var(--sv1-primary); margin-bottom: 12px; font-size: 24px; }
    .sv1 .sv1-closed p  { color: var(--sv1-text-dark); margin-bottom: 8px; }

    /* JAMB data display */
    .sv1 .jamb-data-display {
        background: var(--sv1-primary-soft);
        border-radius: var(--sv1-radius-lg);
        padding: 24px;
        margin-bottom: 24px;
    }
    
    .sv1 .jamb-data-row {
        display: flex;
        padding: 8px 0;
        border-bottom: 1px solid rgba(107,78,155,0.1);
    }
    
    .sv1 .jamb-data-label {
        width: 140px;
        font-weight: 600;
        color: var(--sv1-primary-dark);
    }
    
    .sv1 .jamb-data-value {
        flex: 1;
        color: var(--sv1-text-dark);
    }
    
    /* Score display styling */
    .sv1 .score-badge {
        display: inline-block;
        background: var(--sv1-primary);
        color: var(--sv1-gold);
        font-weight: 700;
        padding: 4px 12px;
        border-radius: 50px;
        font-size: 14px;
    }
</style>


<?php /* ── HTML ─────────────────────────────────────────────────────────── */ ?>
<div class="sv1">

    <?php /* ── Alert (populated by JS) ──────────────────────────────────── */ ?>
    <div id="sv1Alert" role="alert" aria-live="polite">
        <i id="sv1AlertIcon" class="fas fa-info-circle"></i>
        <span id="sv1AlertMsg"></span>
        <button id="sv1AlertClose" type="button" aria-label="Close">&times;</button>
    </div>

    <?php if ($portal_closed): ?>
        <div class="sv1-closed">
            <i class="fas fa-exclamation-triangle"></i>
            <h2>Application Portal Closed</h2>
            <p><?php echo e1($portal_message); ?></p>
            <p class="text-muted">The next admissions cycle will be announced on this portal.</p>
        </div>

    <?php else: ?>
        <?php /* ── Main card ─────────────────────────────────────────────── */ ?>
        <div class="sv1-card">

            <div class="sv1-card-head">
                <i class="fas fa-id-card"></i>
                <h2>JAMB Verification</h2>
                <p>Enter your JAMB registration number to begin your application</p>
            </div>

            <div class="sv1-card-body">

                <?php if ($jambAlreadyVerified || $jambVerified): ?>
                    <?php /* ── Already-verified state ──────────────────────── */ ?>
                    <div class="sv1-verified-banner">
                        <i class="fas fa-check-circle"></i>
                        <div>
                            <h5>JAMB Already Verified</h5>
                            <p>
                                Your JAMB number <strong><?php echo e1($jambNumber); ?></strong>
                                has been verified for <strong><?php echo e1($jambName); ?></strong>.
                                You cannot change it.
                            </p>
                        </div>
                    </div>
                    
                    <?php if (!empty($jambData)): ?>
                    <div class="jamb-data-display">
                        <h4 style="margin-bottom: 15px; color: var(--sv1-primary);">Verified JAMB Details:</h4>
                        <div class="jamb-data-row">
                            <div class="jamb-data-label">Full Name:</div>
                            <div class="jamb-data-value"><?php echo e1($jambData['first_name'] ?? '') . ' ' . e1($jambData['last_name'] ?? ''); ?></div>
                        </div>
                        <div class="jamb-data-row">
                            <div class="jamb-data-label">JAMB Number:</div>
                            <div class="jamb-data-value"><?php echo e1($jambData['jamb_number'] ?? $jambNumber); ?></div>
                        </div>
                        <div class="jamb-data-row">
                            <div class="jamb-data-label">Gender:</div>
                            <div class="jamb-data-value"><?php echo e1($jambData['gender'] ?? 'N/A'); ?></div>
                        </div>
                        <div class="jamb-data-row">
                            <div class="jamb-data-label">State of Origin:</div>
                            <div class="jamb-data-value"><?php echo e1($jambData['state_of_origin'] ?? 'N/A'); ?></div>
                        </div>
                        <div class="jamb-data-row">
                            <div class="jamb-data-label">UTME Score:</div>
                            <div class="jamb-data-value">
                                <?php 
                                $score = $jambData['score'] ?? '';
                                if (!empty($score)): ?>
                                    <span class="score-badge"><?php echo e1($score); ?></span>
                                <?php else: ?>
                                    <span style="color: #ef4444;">N/A</span>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>

                    <div class="text-center mt-3">
                        <a href="/apply/step/2" class="sv1-btn-outline">
                            <i class="fas fa-arrow-right"></i> Continue to Application Form
                        </a>
                    </div>

                <?php else: ?>
                    <?php /* ── JAMB Form ───────────────────────────────────── */ ?>
                    <form id="sv1Form" novalidate>
                        <input type="hidden" name="csrf_token" value="<?php echo e1($csrf_token); ?>">

                        <div class="mb-4">
                            <label class="sv1-label" for="sv1JambNumber">
                                <i class="fas fa-id-card text-primary"></i>
                                JAMB Registration Number
                            </label>
                            <input type="text"
                                   class="sv1-input"
                                   id="sv1JambNumber"
                                   name="jamb_number"
                                   placeholder="e.g. 202650000089FG"
                                   autocomplete="off"
                                   maxlength="14"
                                   inputmode="text"
                                   style="text-transform:uppercase;"
                                   required>
                            <div class="sv1-hint">
                                <i class="fas fa-info-circle"></i>
                                Enter the JAMB registration number you used for the 2025 UTME.
                            </div>
                        </div>

                        <div class="sv1-info">
                            <i class="fas fa-info-circle"></i>
                            <strong>Requirements:</strong>
                            <ul>
                                <li>Minimum UTME score of <?php echo e1($settings['key_value']['min_utme_score'] ?? '170'); ?></li>
                                <li>FCT College of Nursing Sciences selected as first choice</li>
                                <li>5 O&rsquo;Level credits (English, Maths, Biology, Chemistry, Physics)</li>
                                <li>Minimum age of <?php echo e1($settings['key_value']['min_age'] ?? '16'); ?> years</li>
                            </ul>
                        </div>

                        <button type="submit"
                                class="sv1-btn-primary"
                                id="sv1SubmitBtn"
                                disabled>
                            <span id="sv1BtnText">
                                <i class="fas fa-check-circle"></i> Verify JAMB Number
                            </span>
                            <span id="sv1BtnSpinner" style="display:none;" aria-hidden="true">
                                <span class="sv1-spinner"></span>
                                Verifying&hellip;
                            </span>
                        </button>

                        <p id="sv1NoJsMsg" class="sv1-hint text-center mt-3" style="color:#991b1b; display:none;">
                            JavaScript is required for JAMB verification. Please enable it in your browser settings.
                        </p>

                    </form>
                <?php endif; ?>

                <div class="sv1-divider"><span>OR</span></div>

                <div class="text-center">
                    <p class="mb-3" style="color:var(--sv1-text-muted); font-size:14px;">
                        Already have an account?
                    </p>
                    <a href="/applicant/login" class="sv1-btn-outline">
                        <i class="fas fa-sign-in-alt"></i> Login to Continue
                    </a>
                </div>

            </div><?php /* /sv1-card-body */ ?>
        </div><?php /* /sv1-card */ ?>

    <?php endif; /* portal not closed */ ?>

</div><?php /* /sv1 */ ?>


<?php /* ─────────────────────────────────────────────────────────────────────
   JAVASCRIPT - FIXED VERSION
   ───────────────────────────────────────────────────────────────────────── */ ?>
<script nonce="<?php echo e1($csp_nonce); ?>">
(function () {
    'use strict';

    // Only initialize if form exists (not in already-verified state)
    <?php if (!$jambAlreadyVerified && !$jambVerified): ?>
    
    // ── DOM refs ──────────────────────────────────────────────────────────
    var form        = document.getElementById('sv1Form');
    var jambInput   = document.getElementById('sv1JambNumber');
    var submitBtn   = document.getElementById('sv1SubmitBtn');
    var btnText     = document.getElementById('sv1BtnText');
    var btnSpinner  = document.getElementById('sv1BtnSpinner');
    var noJsMsg     = document.getElementById('sv1NoJsMsg');
    var alertEl     = document.getElementById('sv1Alert');
    var alertIcon   = document.getElementById('sv1AlertIcon');
    var alertMsg    = document.getElementById('sv1AlertMsg');
    var alertClose  = document.getElementById('sv1AlertClose');

    // CSRF token — read from the layout's <meta name="csrf-token"> tag
    var csrfToken = '';
    var metaTag = document.querySelector('meta[name="csrf-token"]');
    if (metaTag) {
        csrfToken = metaTag.getAttribute('content') || '';
    }

    // ── Enable submit button now that JS is running ───────────────────────
    if (submitBtn) submitBtn.disabled = false;
    if (noJsMsg)   noJsMsg.style.display = 'none';

    // ── Alert dismiss — addEventListener ──────────────────────────────────
    if (alertClose) {
        alertClose.addEventListener('click', hideAlert);
    }

    // ── JAMB input: uppercase + strip non-alphanumeric ───────────────────
    if (jambInput) {
        jambInput.addEventListener('input', function () {
            var pos = this.selectionStart;
            this.value = this.value.toUpperCase().replace(/[^0-9A-Z]/g, '');
            try { this.setSelectionRange(pos, pos); } catch (e) {}
        });
    }

    // ── Form submit ───────────────────────────────────────────────────────
    if (form) {
        form.addEventListener('submit', function (e) {
            e.preventDefault();

            var jamb = jambInput ? jambInput.value.trim().toUpperCase() : '';

            // Client-side validation
            if (!jamb) {
                showAlert('Please enter your JAMB registration number.', 'danger');
                return;
            }

            if (!/^[0-9A-Z]{10,14}$/.test(jamb)) {
                showAlert('Invalid JAMB number format. It should be 10–14 alphanumeric characters (e.g. 202650000089FG).', 'danger');
                return;
            }

            setLoading(true);

            // Get CSRF token from form
            var formCsrfToken = '';
            var csrfInput = document.querySelector('input[name="csrf_token"]');
            if (csrfInput) {
                formCsrfToken = csrfInput.value;
            }

            // Prepare form data
            var formData = new FormData();
            formData.append('jamb_number', jamb);
            formData.append('csrf_token', formCsrfToken || csrfToken);

            // Log for debugging (remove in production)
            console.log('Submitting JAMB verification for:', jamb);

            // Use AbortController for timeout
            var controller = new AbortController();
            var timeoutId = setTimeout(function() {
                controller.abort();
                setLoading(false);
                showAlert('Request timed out. Please try again.', 'danger');
            }, 30000); // 30 second timeout

            fetch('/apply/verify-jamb', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                },
                signal: controller.signal
            })
            .then(function (response) {
                clearTimeout(timeoutId);
                
                console.log('Response status:', response.status);
                
                // Check content type first
                var ct = response.headers.get('content-type') || '';
                
                if (!response.ok) {
                    return response.text().then(function (text) {
                        console.error('Server returned error:', response.status, text.substring(0, 200));
                        throw new Error('Server error (' + response.status + '). Please try again.');
                    });
                }
                
                if (!ct.includes('application/json')) {
                    return response.text().then(function (text) {
                        console.error('Server returned non-JSON response:', text.substring(0, 200));
                        throw new Error('Server error. Please try again or contact support.');
                    });
                }
                
                return response.json();
            })
            .then(function (data) {
                console.log('Response data:', data);
                
                if (!data.success) {
                    showAlert(data.message || 'Verification failed. Please check your JAMB number and try again.', 'danger');
                    setLoading(false);
                    return;
                }

                showAlert('JAMB verified successfully! Redirecting...', 'success');
                
                // Store in session storage to prevent re-verification
                try {
                    sessionStorage.setItem('jamb_verified', 'true');
                    sessionStorage.setItem('jamb_data', JSON.stringify(data.data || {}));
                } catch (e) {}
                
                // Redirect to step 2
                setTimeout(function () {
                    window.location.href = '/apply/step/2';
                }, 1400);
            })
            .catch(function (err) {
                clearTimeout(timeoutId);
                
                if (err.name === 'AbortError') {
                    console.error('Request aborted due to timeout');
                    showAlert('Request timed out. Please try again.', 'danger');
                } else {
                    console.error('[JAMB] Error:', err);
                    showAlert(err.message || 'A network error occurred. Please check your connection and try again.', 'danger');
                }
                setLoading(false);
            });
        });
    }

    // ── Helpers ───────────────────────────────────────────────────────────

    function setLoading(on) {
        if (!submitBtn) return;
        submitBtn.disabled = on;
        if (btnText)    btnText.style.display    = on ? 'none'         : 'inline-flex';
        if (btnSpinner) btnSpinner.style.display = on ? 'inline-block'  : 'none';
    }

    var alertTimer = null;

    function showAlert(message, type) {
        if (!alertEl) return;

        var iconMap = {
            success: 'fa-check-circle',
            danger:  'fa-exclamation-circle',
            info:    'fa-info-circle',
            warning: 'fa-exclamation-triangle'
        };

        alertEl.className = '';
        alertEl.classList.add('sv1-' + type);

        if (alertIcon) alertIcon.className = 'fas ' + (iconMap[type] || 'fa-info-circle');
        if (alertMsg) alertMsg.textContent = message;

        alertEl.style.display = 'flex';
        alertEl.style.opacity = '1';

        clearTimeout(alertTimer);
        alertTimer = setTimeout(hideAlert, 6000);
    }

    function hideAlert() {
        if (!alertEl) return;
        alertEl.style.transition = 'opacity .4s';
        alertEl.style.opacity = '0';
        setTimeout(function () {
            alertEl.style.display = 'none';
            alertEl.style.transition = '';
        }, 400);
    }
    
    <?php endif; ?>

}());
</script>

<?php /* Add extra JavaScript for already-verified state if needed */ ?>
<?php if ($jambAlreadyVerified || $jambVerified): ?>
<script nonce="<?php echo e1($csp_nonce); ?>">
(function() {
    // Auto-hide flash messages if any
    setTimeout(function () {
        document.querySelectorAll('.flash-msg').forEach(function (el) {
            el.style.transition = 'opacity 0.4s';
            el.style.opacity = '0';
            setTimeout(function () { 
                if (el.parentNode) el.remove(); 
            }, 400);
        });
    }, 5500);
})();
</script>
<?php endif; ?>