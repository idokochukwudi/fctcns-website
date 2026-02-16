<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <!-- Header -->
            <div class="text-center mb-5">
                <h1 class="display-4 fw-bold text-primary">FCT College of Nursing Sciences</h1>
                <h2 class="h3 text-muted">2025/2026 Admissions Application Portal</h2>
            </div>

            <!-- Admission Details Card -->
            <div class="row mb-4">
                <div class="col-md-6">
                    <div class="card h-100 shadow-sm">
                        <div class="card-body">
                            <h5 class="card-title text-primary">
                                <i class="fas fa-calendar-alt me-2"></i>Application Period
                            </h5>
                            <p class="mb-1"><strong>Sales of Forms:</strong> 
                                <?php 
                                $start_date = isset($settings['key_value']['application_start_date']) ? date('jS M Y', strtotime($settings['key_value']['application_start_date'])) : '15th Sep 2025';
                                $end_date = isset($settings['key_value']['application_end_date']) ? date('jS M Y', strtotime($settings['key_value']['application_end_date'])) : '28th Sep 2025';
                                echo $start_date . ' – ' . $end_date;
                                ?>
                            </p>
                            <p class="mb-1"><strong>Application Fee:</strong> 
                                <?php 
                                $currency = htmlspecialchars($settings['key_value']['application_currency'] ?? '₦');
                                $fee = number_format($settings['key_value']['application_fee'] ?? 2200);
                                echo $currency . $fee . ' (Non-refundable)';
                                ?>
                            </p>
                            <p class="mb-0"><strong>Status:</strong> 
                                <span class="badge bg-<?php echo isset($portal_open) && $portal_open ? 'success' : 'danger'; ?>">
                                    <?php echo isset($portal_open) && $portal_open ? 'Open' : 'Closed'; ?>
                                </span>
                            </p>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="card h-100 shadow-sm">
                        <div class="card-body">
                            <h5 class="card-title text-primary">
                                <i class="fas fa-laptop me-2"></i>CBT Screening
                            </h5>
                            <p class="mb-1"><strong>Dates:</strong> 
                                <?php 
                                $cbt_start = isset($settings['key_value']['cbt_start_date']) ? date('jS M Y', strtotime($settings['key_value']['cbt_start_date'])) : '6th Oct 2025';
                                $cbt_end = isset($settings['key_value']['cbt_end_date']) ? date('jS M Y', strtotime($settings['key_value']['cbt_end_date'])) : '8th Oct 2025';
                                echo $cbt_start . ' – ' . $cbt_end;
                                ?>
                            </p>
                            <p class="mb-1"><strong>Venue:</strong> FCT College of Nursing Sciences, Gwagwalada (within UATH)</p>
                            <p class="mb-0"><strong>Reporting Time:</strong> 8:00 AM daily</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Programme Info & Eligibility -->
            <div class="row mb-4">
                <div class="col-md-6">
                    <div class="card h-100 shadow-sm">
                        <div class="card-body">
                            <h5 class="card-title text-primary">
                                <i class="fas fa-graduation-cap me-2"></i>Programme Information
                            </h5>
                            <p class="mb-1"><strong>Programme:</strong> ND/HND Nursing (Non-terminal)</p>
                            <p class="mb-1"><strong>Duration:</strong> 
                                <?php echo htmlspecialchars($settings['key_value']['program_duration'] ?? '4 Years (2 Yrs ND + 2 Yrs HND)'); ?>
                            </p>
                            <p class="mb-0"><strong>Accreditation:</strong> NBTE & NMCN Approved</p>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="card h-100 shadow-sm">
                        <div class="card-body">
                            <h5 class="card-title text-primary">
                                <i class="fas fa-check-circle me-2"></i>Eligibility Requirements
                            </h5>
                            <ul class="mb-0">
                                <li>Minimum UTME score of <?php echo $settings['key_value']['min_utme_score'] ?? 170; ?></li>
                                <li>FCT College of Nursing Sciences as First Choice</li>
                                <li>5 O'Level Credits in ≤ <?php echo $settings['key_value']['max_olevel_sittings'] ?? 2; ?> sittings</li>
                                <li>Minimum age of <?php echo $settings['key_value']['min_age'] ?? 16; ?> years</li>
                                <li>Valid JAMB registration number</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Application Process Steps -->
            <div class="card shadow-sm mb-4">
                <div class="card-body p-4">
                    <h3 class="text-center mb-4">Application Process</h3>
                    
                    <div class="row text-center">
                        <div class="col-3">
                            <div class="step-item">
                                <div class="bg-primary text-white rounded-circle mx-auto mb-2 d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">1</div>
                                <h6>Account Creation</h6>
                                <small class="text-muted">Register & Verify Email</small>
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="step-item">
                                <div class="bg-secondary text-white rounded-circle mx-auto mb-2 d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">2</div>
                                <h6>Application Form</h6>
                                <small class="text-muted">JAMB & Personal Details</small>
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="step-item">
                                <div class="bg-secondary text-white rounded-circle mx-auto mb-2 d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">3</div>
                                <h6>Payment</h6>
                                <small class="text-muted">₦2,200 Application Fee</small>
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="step-item">
                                <div class="bg-secondary text-white rounded-circle mx-auto mb-2 d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">4</div>
                                <h6>Exam Slip</h6>
                                <small class="text-muted">Download CBT Slip</small>
                            </div>
                        </div>
                    </div>
                    
                    <hr class="my-4">
                    
                    <!-- Action Buttons - NO JAMB FIELD HERE -->
                    <div class="text-center">
                        <?php if (isset($portal_open) && $portal_open): ?>
                            <a href="/apply/register" class="btn btn-primary btn-lg px-5 mb-3">
                                <i class="fas fa-play me-2"></i>Start Application
                            </a>
                        <?php else: ?>
                            <button class="btn btn-secondary btn-lg px-5 mb-3" disabled>
                                <i class="fas fa-ban me-2"></i>Applications Closed
                            </button>
                        <?php endif; ?>
                        
                        <p class="mb-2">
                            <small class="text-muted">
                                Already have an account? <a href="/applicant/login">Login here</a>
                            </small>
                        </p>
                        <p class="mb-0">
                            <small class="text-muted">
                                Forgot your password? <a href="/applicant/forgot-password">Reset here</a>
                            </small>
                        </p>
                    </div>
                </div>
            </div>

            <!-- Important Notice -->
            <div class="alert alert-warning">
                <i class="fas fa-exclamation-triangle me-2"></i>
                <strong>Important Notice:</strong> No extension of the application deadline. The College has NO AGENTS. 
                Beware of fraudulent websites and deal only through official channels.
            </div>

            <!-- Support Information -->
            <div class="card shadow-sm">
                <div class="card-body">
                    <h5 class="card-title text-center mb-4">Support & Enquiries</h5>
                    <div class="row">
                        <div class="col-md-3 col-6 mb-3">
                            <div class="text-center">
                                <i class="fas fa-phone fa-2x text-primary mb-2"></i>
                                <p class="mb-0 small"><?php echo htmlspecialchars($settings['key_value']['support_phone_1'] ?? '07039837749'); ?></p>
                                <p class="small"><?php echo htmlspecialchars($settings['key_value']['support_phone_2'] ?? '08036625119'); ?></p>
                            </div>
                        </div>
                        <div class="col-md-3 col-6 mb-3">
                            <div class="text-center">
                                <i class="fab fa-whatsapp fa-2x text-success mb-2"></i>
                                <p class="small"><?php echo htmlspecialchars($settings['key_value']['support_whatsapp'] ?? '08082775076'); ?></p>
                            </div>
                        </div>
                        <div class="col-md-3 col-6 mb-3">
                            <div class="text-center">
                                <i class="fas fa-envelope fa-2x text-danger mb-2"></i>
                                <p class="small"><?php echo htmlspecialchars($settings['key_value']['support_email'] ?? 'support.consap@fcthhss.abj.gov.ng'); ?></p>
                            </div>
                        </div>
                        <div class="col-md-3 col-6 mb-3">
                            <div class="text-center">
                                <i class="fas fa-clock fa-2x text-info mb-2"></i>
                                <p class="small"><?php echo htmlspecialchars($settings['key_value']['support_hours'] ?? 'Mon–Fri, 9AM–5PM'); ?></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.step-item {
    padding: 15px 5px;
    transition: all 0.3s ease;
}
.step-item:hover {
    transform: translateY(-5px);
}
.bg-primary {
    background-color: #6B4E9B !important;
}
.btn-primary {
    background-color: #6B4E9B;
    border-color: #6B4E9B;
}
.btn-primary:hover {
    background-color: #5a3d82;
    border-color: #5a3d82;
}
.text-primary {
    color: #6B4E9B !important;
}
@media (max-width: 768px) {
    .step-item h6 {
        font-size: 0.9rem;
    }
    .step-item small {
        font-size: 0.7rem;
    }
}
</style>