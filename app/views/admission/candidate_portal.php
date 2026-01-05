<?php
// app/views/admission/candidate_portal.php
?>
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-7">
            <!-- Header -->
            <div class="text-center mb-5">
                <div class="mb-4">
                    <h1 class="h2 fw-bold text-dark mb-2">Admission Status Verification</h1>
                    <p class="text-muted mb-0">2025/2026 ND Nursing Programme</p>
                    <p class="text-muted">FCT College of Nursing Sciences</p>
                </div>
            </div>

            <!-- Results Section (Shows at TOP when search is performed) -->
            <?php if ($searchPerformed && !empty($result)): ?>
                <!-- Results Card -->
                <div class="card border-success shadow-lg mb-5 print-container">
                    <div class="card-header bg-success text-white py-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">Admission Status Verified</h5>
                            <div class="d-flex gap-2">
                                <span class="badge bg-light text-success">
                                    <i class="fas fa-check-circle me-1"></i>Verified
                                </span>
                                <button onclick="window.print()" class="btn btn-sm btn-light">
                                    <i class="fas fa-print me-1"></i>Print
                                </button>
                            </div>
                        </div>
                    </div>
                    
                    <div class="card-body">
                        <!-- Status Banner -->
                        <div class="text-center mb-4">
                            <?php if ($result['admission_status'] == 'Accepted'): ?>
                                <div class="badge bg-success rounded-pill px-4 py-3 fs-5 mb-3">
                                    <i class="fas fa-check-circle me-2"></i>ADMISSION ACCEPTED
                                </div>
                                <p class="text-success mb-0">Your admission has been accepted on JAMB CAPS</p>
                            <?php else: ?>
                                <div class="badge bg-warning text-dark rounded-pill px-4 py-3 fs-5 mb-3">
                                    <i class="fas fa-clock me-2"></i>ADMISSION APPROVED
                                </div>
                                <p class="text-warning mb-0">Pending acceptance on JAMB CAPS</p>
                            <?php endif; ?>
                        </div>
                        
                        <!-- Candidate Details Table -->
                        <div class="table-responsive mb-4">
                            <table class="table table-bordered">
                                <tbody>
                                    <tr>
                                        <th style="width: 40%;" class="bg-light">Registration Number</th>
                                        <td class="fw-bold font-monospace"><?php echo htmlspecialchars($result['registration_number']); ?></td>
                                    </tr>
                                    <tr>
                                        <th class="bg-light">Candidate Name</th>
                                        <td class="fw-bold"><?php echo htmlspecialchars($result['candidate_name']); ?></td>
                                    </tr>
                                    <tr>
                                        <th class="bg-light">Serial Number</th>
                                        <td class="fw-bold"><?php echo htmlspecialchars($result['serial_number']); ?></td>
                                    </tr>
                                    <tr>
                                        <th class="bg-light">Admission Status</th>
                                        <td>
                                            <?php if ($result['admission_status'] == 'Accepted'): ?>
                                                <span class="badge bg-success">Accepted</span>
                                            <?php else: ?>
                                                <span class="badge bg-warning text-dark">Approved</span>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th class="bg-light">Verification Date</th>
                                        <td><?php echo date('F j, Y \a\t g:i A'); ?></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        
                        <!-- Instructions Based on Status -->
                        <div class="mb-4">
                            <?php if ($result['admission_status'] == 'Accepted'): ?>
                                <div class="alert alert-success bg-success bg-opacity-10 border-success">
                                    <h6 class="alert-heading mb-3">
                                        <i class="fas fa-check-circle me-2"></i>Next Steps for Accepted Candidates
                                    </h6>
                                    <ol class="mb-0 ps-3">
                                        <li class="mb-2">Print your JAMB Admission Letter (Institution & Personal copies)</li>
                                        <li class="mb-2">Report to the College for documentation and registration</li>
                                        <li>Bring the Institution copy of your admission letter with you</li>
                                    </ol>
                                </div>
                            <?php else: ?>
                                <div class="alert alert-warning bg-warning bg-opacity-10 border-warning">
                                    <h6 class="alert-heading mb-3">
                                        <i class="fas fa-exclamation-triangle me-2"></i>Important Action Required
                                    </h6>
                                    <ol class="mb-2 ps-3">
                                        <li class="mb-2">Log in to JAMB CAPS to accept your admission immediately</li>
                                        <li class="mb-2">Print your JAMB Admission Letter (Institution & Personal copies)</li>
                                        <li>Report to the College with the Institution copy for further action</li>
                                    </ol>
                                    <div class="alert alert-danger mt-3 mb-0">
                                        <i class="fas fa-calendar-times me-2"></i>
                                        <strong>Deadline: January 9, 2025</strong> - Admissions not accepted by this date will be withdrawn
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>
                        
                        <!-- Print Section (Hidden in normal view) -->
                        <div class="print-only d-none">
                            <div class="text-center border-top pt-4 mt-4">
                                <h6 class="text-dark mb-2">Official Verification</h6>
                                <p class="text-muted small mb-0">
                                    This document verifies the admission status as of <?php echo date('F j, Y'); ?>
                                </p>
                                <p class="text-muted small">
                                    FCT College of Nursing Sciences - Admissions Office
                                </p>
                            </div>
                        </div>
                        
                        <!-- Action Buttons -->
                        <div class="d-flex justify-content-between pt-3 border-top">
                            <button onclick="checkAnotherCandidate()" class="btn btn-outline-dark">
                                <i class="fas fa-redo me-2"></i>Check Another Candidate
                            </button>
                            <div class="d-flex gap-2">
                                <button onclick="window.print()" class="btn btn-dark">
                                    <i class="fas fa-print me-2"></i>Print Status
                                </button>
                                <a href="<?php echo BASE_URL; ?>/admission" class="btn btn-outline-secondary">
                                    <i class="fas fa-list me-2"></i>View Full List
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

            <!-- Error Message (Shows at TOP when error occurs) -->
            <?php if (!empty($error) && $searchPerformed): ?>
                <div class="card border-danger shadow-sm mb-5">
                    <div class="card-header bg-danger text-white py-3">
                        <h5 class="mb-0">Verification Result</h5>
                    </div>
                    <div class="card-body">
                        <div class="alert alert-danger border-danger">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-exclamation-circle fa-2x me-3"></i>
                                <div>
                                    <h6 class="alert-heading mb-2">Admission Record Not Found</h6>
                                    <p class="mb-2"><?php echo htmlspecialchars($error); ?></p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mt-3">
                            <h6 class="mb-3">Possible Reasons:</h6>
                            <ul class="list-unstyled">
                                <li class="mb-2">
                                    <i class="fas fa-circle text-muted fa-xs me-2"></i>
                                    The registration number may be incorrect
                                </li>
                                <li class="mb-2">
                                    <i class="fas fa-circle text-muted fa-xs me-2"></i>
                                    Admission is still being processed
                                </li>
                                <li class="mb-2">
                                    <i class="fas fa-circle text-muted fa-xs me-2"></i>
                                    Check with your application portal for correct registration number
                                </li>
                                <li>
                                    <i class="fas fa-circle text-muted fa-xs me-2"></i>
                                    Contact admissions office for assistance
                                </li>
                            </ul>
                        </div>
                        
                        <div class="text-center mt-4">
                            <button onclick="checkAnotherCandidate()" class="btn btn-primary">
                                <i class="fas fa-search me-2"></i>Try Another Search
                            </button>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

            <!-- Search Form Card (Always shows, cleared after submission) -->
            <div class="card shadow-sm border-0" id="searchFormContainer">
                <div class="card-body p-4 p-md-5">
                    <h5 class="card-title mb-4 text-dark">
                        <?php echo $searchPerformed ? 'Check Another Candidate' : 'Check Admission Status'; ?>
                    </h5>
                    
                    <form method="POST" id="statusCheckForm" onsubmit="clearSearchField()">
                        <div class="mb-4">
                            <label class="form-label fw-medium mb-3">Enter Registration Number</label>
                            <div class="input-group input-group-lg">
                                <span class="input-group-text bg-light border-end-0">
                                    <i class="fas fa-hashtag text-primary"></i>
                                </span>
                                <input type="text" 
                                       name="reg_number" 
                                       id="regNumberInput"
                                       class="form-control form-control-lg border-start-0" 
                                       placeholder="Example: 202551998000BF" 
                                       value=""
                                       required
                                       autofocus>
                            </div>
                            <div class="form-text mt-2">
                                Enter your registration number exactly as provided
                            </div>
                        </div>
                        
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="fas fa-search me-2"></i>Verify Admission Status
                            </button>
                        </div>
                    </form>
                    
                    <!-- Quick Info -->
                    <div class="mt-4 pt-4 border-top">
                        <h6 class="text-dark mb-3">Important Information</h6>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="d-flex">
                                    <div class="bg-primary bg-opacity-10 p-2 rounded me-3">
                                        <i class="fas fa-calendar text-primary"></i>
                                    </div>
                                    <div>
                                        <p class="fw-medium mb-1">Acceptance Deadline</p>
                                        <p class="text-muted small mb-0">January 9, 2025</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="d-flex">
                                    <div class="bg-primary bg-opacity-10 p-2 rounded me-3">
                                        <i class="fas fa-door-open text-primary"></i>
                                    </div>
                                    <div>
                                        <p class="fw-medium mb-1">Resumption Date</p>
                                        <p class="text-muted small mb-0">January 6, 2025</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Contact Information -->
                    <div class="mt-4 pt-4 border-top text-center">
                        <p class="text-muted small mb-2">
                            Need assistance? Contact Admissions Office
                        </p>
                        <div class="d-flex justify-content-center gap-4">
                            <div>
                                <i class="fas fa-envelope text-muted me-2"></i>
                                <a href="mailto:admissions@fctcns.edu.ng" class="text-decoration-none text-muted">
                                    admissions@fctcns.edu.ng
                                </a>
                            </div>
                            <div>
                                <i class="fas fa-phone text-muted me-2"></i>
                                <span class="text-muted">[Contact Number]</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
/* Print Styles */
@media print {
    body * {
        visibility: hidden;
    }
    
    .print-container, 
    .print-container * {
        visibility: visible;
    }
    
    .print-container {
        position: absolute;
        left: 0;
        top: 0;
        width: 100%;
        border: none !important;
        box-shadow: none !important;
    }
    
    .print-only {
        display: block !important;
    }
    
    .btn, 
    .d-print-none {
        display: none !important;
    }
    
    .card {
        border: 1px solid #dee2e6 !important;
    }
    
    .table {
        border: 1px solid #dee2e6;
    }
    
    .table th {
        background-color: #f8f9fa !important;
        -webkit-print-color-adjust: exact;
        color-adjust: exact;
    }
}

/* Screen Styles */
.card {
    border-radius: 12px;
    transition: transform 0.2s;
}

.card:hover {
    transform: translateY(-2px);
}

.input-group-text {
    border-right: none;
    background-color: #f8f9fa;
}

.form-control.border-start-0 {
    border-left: none;
    background-color: #fff;
}

.form-control:focus {
    box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.1);
    border-color: #86b7fe;
}

.badge {
    font-weight: 500;
    letter-spacing: 0.5px;
}

.alert {
    border-radius: 8px;
    border-width: 1px;
}

.table th {
    font-weight: 600;
    background-color: #f8f9fa;
}

.print-only {
    display: none;
}

@media (max-width: 768px) {
    .d-flex.justify-content-between {
        flex-direction: column;
        gap: 1rem;
    }
    
    .d-flex.justify-content-between .btn {
        width: 100%;
    }
}
</style>

<script>
// Clear search field after submission
function clearSearchField() {
    // The field will be cleared by PHP (value="") but this ensures immediate feedback
    setTimeout(function() {
        document.getElementById('regNumberInput').value = '';
    }, 100);
}

// Check another candidate - scroll to search form and clear it
function checkAnotherCandidate() {
    const searchForm = document.getElementById('searchFormContainer');
    const inputField = document.getElementById('regNumberInput');
    
    // Clear the input field
    inputField.value = '';
    
    // Focus on the input field
    inputField.focus();
    
    // Smooth scroll to search form
    searchForm.scrollIntoView({ behavior: 'smooth' });
    
    return false;
}

// Auto-focus on input when page loads
document.addEventListener('DOMContentLoaded', function() {
    const inputField = document.getElementById('regNumberInput');
    if (inputField) {
        inputField.focus();
    }
    
    // If there are results, scroll to them
    <?php if ($searchPerformed && !empty($result)): ?>
        const resultsCard = document.querySelector('.print-container');
        if (resultsCard) {
            resultsCard.scrollIntoView({ behavior: 'smooth' });
        }
    <?php endif; ?>
});

// Prevent form resubmission on page refresh
if (window.history.replaceState) {
    window.history.replaceState(null, null, window.location.href);
}
</script>