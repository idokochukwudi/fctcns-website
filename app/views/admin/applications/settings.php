<?php
/**
 * Admin Application Settings View
 * 
 * @package FCT_CNS
 */
?>

<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-12">
            <h1 class="h3 mb-0">Application Settings</h1>
            <p class="text-muted">Configure application portal settings and fees</p>
        </div>
    </div>

    <?php if (!empty($flash_success)): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <?php echo htmlspecialchars($flash_success); ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    <?php endif; ?>

    <?php if (!empty($flash_error)): ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <?php echo htmlspecialchars($flash_error); ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    <?php endif; ?>

    <form method="POST" action="/admin/applications/settings" class="needs-validation" novalidate>
        <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">

        <!-- General Settings -->
        <div class="card mb-4">
            <div class="card-header bg-primary text-white">
                <h5 class="card-title mb-0">
                    <i class="fas fa-cog me-2"></i>General Settings
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label for="portal_status" class="form-label">Portal Status</label>
                        <select class="form-select" id="portal_status" name="portal_status">
                            <option value="open" <?php echo ($settings['key_value']['portal_status'] ?? '') == 'open' ? 'selected' : ''; ?>>Open</option>
                            <option value="closed" <?php echo ($settings['key_value']['portal_status'] ?? '') == 'closed' ? 'selected' : ''; ?>>Closed</option>
                        </select>
                        <small class="text-muted">Set portal to open or closed for new applications</small>
                    </div>

                    <div class="col-md-4 mb-3">
                        <label for="portal_message" class="form-label">Portal Message</label>
                        <textarea class="form-control" id="portal_message" name="portal_message" rows="2"><?php echo htmlspecialchars($settings['key_value']['portal_message'] ?? ''); ?></textarea>
                        <small class="text-muted">Message shown when portal is closed</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Payment Settings -->
        <div class="card mb-4">
            <div class="card-header bg-success text-white">
                <h5 class="card-title mb-0">
                    <i class="fas fa-credit-card me-2"></i>Payment Settings
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3 mb-3">
                        <label for="application_fee" class="form-label">Application Fee (₦)</label>
                        <input type="number" class="form-control" id="application_fee" name="application_fee" 
                               value="<?php echo htmlspecialchars($settings['key_value']['application_fee'] ?? '2200'); ?>" min="0" step="100">
                    </div>

                    <div class="col-md-3 mb-3">
                        <label for="application_currency" class="form-label">Currency Symbol</label>
                        <input type="text" class="form-control" id="application_currency" name="application_currency" 
                               value="<?php echo htmlspecialchars($settings['key_value']['application_currency'] ?? '₦'); ?>" maxlength="5">
                    </div>
                </div>
            </div>
        </div>

        <!-- Date Settings -->
        <div class="card mb-4">
            <div class="card-header bg-info text-white">
                <h5 class="card-title mb-0">
                    <i class="fas fa-calendar-alt me-2"></i>Application Dates
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3 mb-3">
                        <label for="application_start_date" class="form-label">Start Date</label>
                        <input type="date" class="form-control" id="application_start_date" name="application_start_date" 
                               value="<?php echo htmlspecialchars($settings['key_value']['application_start_date'] ?? ''); ?>">
                    </div>

                    <div class="col-md-3 mb-3">
                        <label for="application_end_date" class="form-label">End Date</label>
                        <input type="date" class="form-control" id="application_end_date" name="application_end_date" 
                               value="<?php echo htmlspecialchars($settings['key_value']['application_end_date'] ?? ''); ?>">
                    </div>

                    <div class="col-md-3 mb-3">
                        <label for="cbt_start_date" class="form-label">CBT Start Date</label>
                        <input type="date" class="form-control" id="cbt_start_date" name="cbt_start_date" 
                               value="<?php echo htmlspecialchars($settings['key_value']['cbt_start_date'] ?? ''); ?>">
                    </div>

                    <div class="col-md-3 mb-3">
                        <label for="cbt_end_date" class="form-label">CBT End Date</label>
                        <input type="date" class="form-control" id="cbt_end_date" name="cbt_end_date" 
                               value="<?php echo htmlspecialchars($settings['key_value']['cbt_end_date'] ?? ''); ?>">
                    </div>
                </div>
            </div>
        </div>

        <!-- Eligibility Settings -->
        <div class="card mb-4">
            <div class="card-header bg-warning text-dark">
                <h5 class="card-title mb-0">
                    <i class="fas fa-check-circle me-2"></i>Eligibility Requirements
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3 mb-3">
                        <label for="min_utme_score" class="form-label">Minimum UTME Score</label>
                        <input type="number" class="form-control" id="min_utme_score" name="min_utme_score" 
                               value="<?php echo htmlspecialchars($settings['key_value']['min_utme_score'] ?? '170'); ?>" min="0" max="400">
                    </div>

                    <div class="col-md-3 mb-3">
                        <label for="min_age" class="form-label">Minimum Age</label>
                        <input type="number" class="form-control" id="min_age" name="min_age" 
                               value="<?php echo htmlspecialchars($settings['key_value']['min_age'] ?? '16'); ?>" min="0" max="100">
                    </div>

                    <div class="col-md-3 mb-3">
                        <label for="max_olevel_sittings" class="form-label">Max O'Level Sittings</label>
                        <input type="number" class="form-control" id="max_olevel_sittings" name="max_olevel_sittings" 
                               value="<?php echo htmlspecialchars($settings['key_value']['max_olevel_sittings'] ?? '2'); ?>" min="1" max="2">
                    </div>

                    <div class="col-md-12 mb-3">
                        <label for="program_duration" class="form-label">Program Duration Description</label>
                        <input type="text" class="form-control" id="program_duration" name="program_duration" 
                               value="<?php echo htmlspecialchars($settings['key_value']['program_duration'] ?? '4 Years (2 Yrs ND + 2 Yrs HND)'); ?>">
                    </div>
                </div>
            </div>
        </div>

        <!-- Support Settings -->
        <div class="card mb-4">
            <div class="card-header bg-secondary text-white">
                <h5 class="card-title mb-0">
                    <i class="fas fa-headset me-2"></i>Support Information
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3 mb-3">
                        <label for="support_phone_1" class="form-label">Primary Phone</label>
                        <input type="text" class="form-control" id="support_phone_1" name="support_phone_1" 
                               value="<?php echo htmlspecialchars($settings['key_value']['support_phone_1'] ?? '07039837749'); ?>">
                    </div>

                    <div class="col-md-3 mb-3">
                        <label for="support_phone_2" class="form-label">Secondary Phone</label>
                        <input type="text" class="form-control" id="support_phone_2" name="support_phone_2" 
                               value="<?php echo htmlspecialchars($settings['key_value']['support_phone_2'] ?? '08036625119'); ?>">
                    </div>

                    <div class="col-md-3 mb-3">
                        <label for="support_whatsapp" class="form-label">WhatsApp Number</label>
                        <input type="text" class="form-control" id="support_whatsapp" name="support_whatsapp" 
                               value="<?php echo htmlspecialchars($settings['key_value']['support_whatsapp'] ?? '08082775076'); ?>">
                    </div>

                    <div class="col-md-3 mb-3">
                        <label for="support_email" class="form-label">Support Email</label>
                        <input type="email" class="form-control" id="support_email" name="support_email" 
                               value="<?php echo htmlspecialchars($settings['key_value']['support_email'] ?? 'support@fctcns.edu.ng'); ?>">
                    </div>

                    <div class="col-md-4 mb-3">
                        <label for="support_hours" class="form-label">Support Hours</label>
                        <input type="text" class="form-control" id="support_hours" name="support_hours" 
                               value="<?php echo htmlspecialchars($settings['key_value']['support_hours'] ?? 'Mon–Fri, 9:00 AM – 5:00 PM'); ?>">
                    </div>

                    <div class="col-md-8 mb-3">
                        <label for="institution_address" class="form-label">Institution Address</label>
                        <input type="text" class="form-control" id="institution_address" name="institution_address" 
                               value="<?php echo htmlspecialchars($settings['key_value']['institution_address'] ?? 'FCT College of Nursing Sciences, Gwagwalada, Abuja'); ?>">
                    </div>

                    <div class="col-md-4 mb-3">
                        <label for="office_hours" class="form-label">Office Hours</label>
                        <input type="text" class="form-control" id="office_hours" name="office_hours" 
                               value="<?php echo htmlspecialchars($settings['key_value']['office_hours'] ?? 'Monday – Friday, 8:00 AM – 5:00 PM'); ?>">
                    </div>
                </div>
            </div>
        </div>

        <div class="d-flex justify-content-between mb-4">
            <a href="/admin/applications" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-2"></i>Back to Applications
            </a>
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save me-2"></i>Save Settings
            </button>
        </div>
    </form>
</div>

<script>
// Form validation
(function() {
    'use strict';
    
    var forms = document.querySelectorAll('.needs-validation');
    
    Array.prototype.slice.call(forms).forEach(function(form) {
        form.addEventListener('submit', function(event) {
            if (!form.checkValidity()) {
                event.preventDefault();
                event.stopPropagation();
            }
            
            form.classList.add('was-validated');
        }, false);
    });
})();

// Confirm before saving
document.querySelector('form').addEventListener('submit', function(e) {
    if (!confirm('Are you sure you want to update these settings?')) {
        e.preventDefault();
    }
});
</script>