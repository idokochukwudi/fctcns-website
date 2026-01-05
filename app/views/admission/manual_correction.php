<?php
// app/views/admission/manual_correction.php
?>
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <!-- Header -->
            <div class="mb-5">
                <h1 class="h3 fw-bold text-dark mb-3">Manual Status Correction</h1>
                <p class="text-muted">Admin override for admission status updates</p>
                
                <!-- Current Configuration -->
                <div class="alert alert-info bg-light border mt-3">
                    <h6 class="mb-2">Current Update Rules:</h6>
                    <ul class="mb-0">
                        <li>Normal updates: 
                            <?php echo $config['allowed_transitions']['Approved'][0] ?? 'None'; ?> 
                            (Approved → <?php echo $config['allowed_transitions']['Approved'][0] ?? 'None'; ?>)
                        </li>
                        <li>Reverse updates allowed: 
                            <?php echo $config['allow_reverse_updates'] ? 'Yes' : 'No'; ?>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Messages -->
            <?php if (!empty($message)): ?>
                <div class="alert alert-success border-success mb-4">
                    <?php echo htmlspecialchars($message); ?>
                </div>
            <?php endif; ?>

            <?php if (!empty($error)): ?>
                <div class="alert alert-danger border-danger mb-4">
                    <?php echo htmlspecialchars($error); ?>
                </div>
            <?php endif; ?>

            <!-- Manual Correction Form -->
            <div class="card shadow-sm mb-5">
                <div class="card-header bg-light py-3">
                    <h6 class="mb-0 text-dark">Manual Status Update</h6>
                </div>
                <div class="card-body">
                    <form method="POST">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label fw-medium">Registration Number</label>
                                <input type="text" class="form-control" name="reg_number" 
                                       placeholder="e.g., 202551998000BF" required>
                                <div class="form-text">Enter exact registration number</div>
                            </div>
                            
                            <div class="col-md-6">
                                <label class="form-label fw-medium">New Status</label>
                                <select class="form-control" name="new_status" required>
                                    <option value="">Select Status</option>
                                    <option value="Accepted">Accepted</option>
                                    <option value="Approved">Approved</option>
                                </select>
                            </div>
                            
                            <div class="col-md-6">
                                <label class="form-label fw-medium">Admin Name</label>
                                <input type="text" class="form-control" name="admin_name" 
                                       placeholder="Your name" value="System">
                            </div>
                            
                            <div class="col-md-6">
                                <label class="form-label fw-medium">Reason for Correction</label>
                                <select class="form-control" name="reason">
                                    <option value="">Select reason</option>
                                    <option value="Data entry error">Data entry error</option>
                                    <option value="JAMB update">JAMB update</option>
                                    <option value="Student request">Student request</option>
                                    <option value="System error">System error</option>
                                    <option value="Other">Other</option>
                                </select>
                            </div>
                            
                            <div class="col-12">
                                <label class="form-label fw-medium">Additional Notes</label>
                                <textarea class="form-control" name="notes" rows="2" 
                                          placeholder="Any additional information..."></textarea>
                            </div>
                        </div>
                        
                        <div class="mt-4 pt-3 border-top">
                            <div class="d-flex gap-3">
                                <button type="submit" class="btn btn-dark">
                                    <i class="fas fa-check-circle me-2"></i>Apply Correction
                                </button>
                                <a href="<?php echo BASE_URL; ?>/admin/admission/update" class="btn btn-outline-dark">
                                    <i class="fas fa-upload me-2"></i>Bulk Update
                                </a>
                                <a href="<?php echo BASE_URL; ?>/admission" class="btn btn-outline-secondary">
                                    <i class="fas fa-list me-2"></i>Admission List
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Update Results -->
            <?php if (isset($result) && $result['success']): ?>
                <div class="card shadow-sm">
                    <div class="card-header bg-success text-white py-3">
                        <h6 class="mb-0">Update Successful</h6>
                    </div>
                    <div class="card-body">
                        <div class="alert alert-success bg-success bg-opacity-10">
                            <div class="d-flex">
                                <i class="fas fa-check-circle text-success fa-lg me-3 mt-1"></i>
                                <div>
                                    <h6 class="mb-2">Status Updated</h6>
                                    <p class="mb-1">Registration: <code><?php echo htmlspecialchars($_POST['reg_number']); ?></code></p>
                                    <p class="mb-0">Changed from <span class="badge bg-secondary"><?php echo $result['previous_status']; ?></span> 
                                    to <span class="badge bg-success"><?php echo $result['new_status']; ?></span></p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mt-3">
                            <a href="<?php echo BASE_URL; ?>/admission/check?reg=<?php echo urlencode($_POST['reg_number']); ?>" 
                               class="btn btn-outline-dark btn-sm">
                                <i class="fas fa-eye me-1"></i>View Candidate Details
                            </a>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>