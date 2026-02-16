<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-lg border-0 rounded-lg">
                <div class="card-header bg-primary text-white text-center py-4">
                    <i class="fas fa-file-alt fa-3x mb-3"></i>
                    <h2 class="mb-0">Application Form</h2>
                    <p class="mb-0">Step 2 of 4</p>
                </div>
                
                <div class="card-body p-4">
                    <?php if (isset($flash_success)): ?>
                        <div class="alert alert-success"><?php echo $flash_success; ?></div>
                    <?php endif; ?>
                    
                    <?php if (isset($flash_error)): ?>
                        <div class="alert alert-danger"><?php echo $flash_error; ?></div>
                    <?php endif; ?>
                    
                    <form method="POST" action="/apply/verify-jamb" class="mb-4">
                        <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">
                        
                        <div class="card bg-light mb-4">
                            <div class="card-body">
                                <h5 class="card-title">JAMB Verification</h5>
                                <div class="input-group">
                                    <input type="text" name="jamb_number" class="form-control" 
                                           placeholder="Enter JAMB Registration Number" required>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-check me-2"></i>Verify
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                    
                    <?php if (isset($jamb_verified) && $jamb_verified): ?>
                        <form method="POST" action="/apply/save-application" enctype="multipart/form-data">
                            <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">
                            
                            <!-- Personal Details -->
                            <h5 class="mb-3">Personal Information</h5>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label>First Name</label>
                                    <input type="text" class="form-control" name="first_name" 
                                           value="<?php echo $jamb_data['first_name'] ?? ''; ?>" readonly>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label>Last Name</label>
                                    <input type="text" class="form-control" name="last_name" 
                                           value="<?php echo $jamb_data['last_name'] ?? ''; ?>" readonly>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label>Date of Birth</label>
                                    <input type="date" class="form-control" name="date_of_birth" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label>Gender</label>
                                    <input type="text" class="form-control" value="<?php echo $jamb_data['gender'] ?? ''; ?>" readonly>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label>State of Origin</label>
                                    <input type="text" class="form-control" value="<?php echo $jamb_data['state_of_origin'] ?? ''; ?>" readonly>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label>LGA</label>
                                    <input type="text" class="form-control" value="<?php echo $jamb_data['lga'] ?? ''; ?>" readonly>
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <label>Address</label>
                                <textarea class="form-control" name="address" rows="2" required></textarea>
                            </div>
                            
                            <!-- Documents Upload -->
                            <h5 class="mb-3 mt-4">Documents Upload</h5>
                            
                            <div class="mb-3">
                                <label>Passport Photograph (max 200KB)</label>
                                <input type="file" class="form-control" name="passport" accept="image/*" required>
                                <small class="text-muted">Accepted formats: JPG, PNG</small>
                            </div>
                            
                            <div class="mb-3">
                                <label>O'Level Results (WAEC/NECO/NABTEB)</label>
                                <input type="file" class="form-control" name="olevel" accept=".pdf,.jpg,.png" required>
                                <small class="text-muted">Upload scanned copy (PDF, JPG, PNG)</small>
                            </div>
                            
                            <button type="submit" class="btn btn-success w-100 py-2 mt-3">
                                <i class="fas fa-save me-2"></i>Save and Continue to Payment
                            </button>
                        </form>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>