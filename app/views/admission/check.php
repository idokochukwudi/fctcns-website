<?php
// app/views/admission/check.php
?>
<div class="container-fluid px-4 py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card border-0 shadow-lg">
                <div class="card-header bg-primary text-white py-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h4 class="mb-0"><i class="fas fa-user-check me-2"></i>Admission Status Check</h4>
                            <p class="mb-0 mt-1 opacity-75">Verify Candidate Admission Status</p>
                        </div>
                        <a href="<?php echo BASE_URL; ?>/admission" class="btn btn-light">
                            <i class="fas fa-arrow-left me-1"></i>Back to List
                        </a>
                    </div>
                </div>
                
                <div class="card-body p-5">
                    <!-- Registration Number Display -->
                    <div class="text-center mb-5">
                        <div class="badge bg-light text-dark fs-5 p-3 mb-3">
                            <i class="fas fa-hashtag me-2"></i>
                            Registration Number: <code class="fs-4 ms-2"><?php echo htmlspecialchars($regNumber); ?></code>
                        </div>
                    </div>
                    
                    <?php if ($found): ?>
                        <!-- Found Result -->
                        <div class="text-center mb-5">
                            <div class="mb-4">
                                <i class="fas fa-check-circle fa-5x text-success"></i>
                            </div>
                            <h2 class="text-success mb-3">Congratulations!</h2>
                            <p class="lead mb-4">Your admission has been processed successfully.</p>
                        </div>
                        
                        <!-- Admission Details Card -->
                        <div class="card border-success shadow-sm mb-5">
                            <div class="card-header bg-success text-white py-3">
                                <h5 class="mb-0"><i class="fas fa-id-card me-2"></i>Admission Details</h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6 mb-4">
                                        <div class="border rounded p-4 h-100 text-center">
                                            <div class="text-muted mb-2">Serial Number</div>
                                            <div class="display-6 fw-bold text-primary"><?php echo htmlspecialchars($admission['serial_number']); ?></div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-4">
                                        <div class="border rounded p-4 h-100 text-center">
                                            <div class="text-muted mb-2">Registration Number</div>
                                            <div class="h3 fw-bold">
                                                <code class="bg-light p-2 rounded"><?php echo htmlspecialchars($admission['registration_number']); ?></code>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="border rounded p-4 mb-4 text-center">
                                    <div class="text-muted mb-2">Candidate Name</div>
                                    <h3 class="text-primary"><?php echo htmlspecialchars($admission['candidate_name']); ?></h3>
                                </div>
                                
                                <div class="border rounded p-4 text-center">
                                    <div class="text-muted mb-2">Admission Status</div>
                                    <?php if ($admission['admission_status'] == 'Accepted'): ?>
                                        <span class="badge bg-success rounded-pill px-4 py-3 fs-5">
                                            <i class="fas fa-check-circle me-2"></i>ACCEPTED
                                        </span>
                                        <p class="text-success mt-3 mb-0">
                                            <i class="fas fa-info-circle me-1"></i>
                                            Your admission has been accepted on JAMB CAPS
                                        </p>
                                    <?php else: ?>
                                        <span class="badge bg-warning text-dark rounded-pill px-4 py-3 fs-5">
                                            <i class="fas fa-clock me-2"></i>APPROVED
                                        </span>
                                        <p class="text-warning mt-3 mb-0">
                                            <i class="fas fa-info-circle me-1"></i>
                                            Pending acceptance on JAMB CAPS
                                        </p>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Status-specific Instructions -->
                        <?php if ($admission['admission_status'] == 'Accepted'): ?>
                            <div class="alert alert-success border-success border-3">
                                <div class="d-flex">
                                    <i class="fas fa-check-circle fa-2x me-3 mt-1 text-success"></i>
                                    <div>
                                        <h5 class="alert-heading mb-3">Next Steps for ACCEPTED Candidates</h5>
                                        <ol class="mb-0 ps-3">
                                            <li class="mb-2"><strong>Print your JAMB Admission Letter</strong> (both Institution & Personal copies)</li>
                                            <li class="mb-2"><strong>Report to the College</strong> for documentation and registration</li>
                                            <li><strong>Bring the Institution copy</strong> of your admission letter with you</li>
                                        </ol>
                                    </div>
                                </div>
                            </div>
                        <?php else: ?>
                            <div class="alert alert-warning border-warning border-3">
                                <div class="d-flex">
                                    <i class="fas fa-exclamation-triangle fa-2x me-3 mt-1 text-warning"></i>
                                    <div>
                                        <h5 class="alert-heading mb-3">Urgent Action Required for APPROVED Candidates</h5>
                                        <ol class="mb-2 ps-3">
                                            <li class="mb-2"><strong>Log in to JAMB CAPS immediately</strong> to accept your admission</li>
                                            <li class="mb-2"><strong>Print your JAMB Admission Letter</strong> (both Institution & Personal copies)</li>
                                            <li><strong>Report to the College</strong> with the Institution copy for further action</li>
                                        </ol>
                                        <div class="alert alert-danger mt-3 mb-0">
                                            <i class="fas fa-calendar-times me-2"></i>
                                            <strong>Deadline: 9 January 2025</strong> - Admissions not accepted by this date will be withdrawn
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>
                        
                    <?php else: ?>
                        <!-- Not Found -->
                        <div class="text-center mb-5">
                            <div class="mb-4">
                                <i class="fas fa-search fa-5x text-warning"></i>
                            </div>
                            <h2 class="text-warning mb-3">Admission Not Found</h2>
                            <p class="lead mb-4">We couldn't find an admission record for the provided registration number.</p>
                        </div>
                        
                        <div class="alert alert-light border">
                            <h5 class="mb-3">Possible Reasons:</h5>
                            <ul class="mb-0">
                                <li class="mb-2">The registration number might be incorrect or mistyped</li>
                                <li class="mb-2">Your admission is still being processed (check back later)</li>
                                <li class="mb-2">You might need to check with a different registration number</li>
                                <li>Contact the admissions office if you believe there's an error</li>
                            </ul>
                        </div>
                        
                        <!-- Search Again Form -->
                        <div class="card border-primary mt-4">
                            <div class="card-body">
                                <h5 class="card-title mb-3">Check Another Registration Number</h5>
                                <form action="<?php echo BASE_URL; ?>/admission/check" method="GET" class="row g-3">
                                    <div class="col-md-8">
                                        <input type="text" name="reg" class="form-control" 
                                               placeholder="Enter Registration Number..." required>
                                    </div>
                                    <div class="col-md-4">
                                        <button type="submit" class="btn btn-primary w-100">
                                            <i class="fas fa-search me-1"></i>Check Again
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    <?php endif; ?>
                    
                    <!-- Action Buttons -->
                    <div class="text-center mt-5 pt-4 border-top">
                        <div class="d-flex justify-content-center gap-3">
                            <a href="<?php echo BASE_URL; ?>/admission" class="btn btn-primary btn-lg">
                                <i class="fas fa-list me-1"></i>View Full Admission List
                            </a>
                            <?php if ($found): ?>
                                <button onclick="window.print()" class="btn btn-outline-secondary btn-lg">
                                    <i class="fas fa-print me-1"></i>Print Details
                                </button>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.card {
    border-radius: 12px;
}

.badge {
    font-weight: 500;
}

.alert {
    border-radius: 10px;
}
</style>