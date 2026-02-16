<div class="text-center mb-4">
    <h2>Step 4: Examination Slip</h2>
    <p class="text-muted">Your application is complete. Download your examination slip below.</p>
</div>

<?php if (!empty($exam_slip)): ?>
    <div class="exam-slip">
        <div class="exam-slip-header">
            <h2>FCT COLLEGE OF NURSING SCIENCES</h2>
            <h3>Gwagwalada, Abuja</h3>
            <h4>2025/2026 ADMISSIONS SCREENING</h4>
            <h5 class="text-primary">EXAMINATION SLIP</h5>
        </div>
        
        <div class="exam-slip-details">
            <div class="exam-slip-item">
                <span class="label">Slip Number:</span>
                <span class="value"><?php echo htmlspecialchars($exam_slip['slip_number']); ?></span>
            </div>
            
            <div class="exam-slip-item">
                <span class="label">Application Number:</span>
                <span class="value"><?php echo htmlspecialchars($application['application_number']); ?></span>
            </div>
            
            <div class="exam-slip-item">
                <span class="label">JAMB Number:</span>
                <span class="value"><?php echo htmlspecialchars($application['jamb_number']); ?></span>
            </div>
            
            <div class="exam-slip-item">
                <span class="label">Candidate Name:</span>
                <span class="value"><?php echo htmlspecialchars($application['first_name'] . ' ' . $application['last_name']); ?></span>
            </div>
            
            <div class="exam-slip-item">
                <span class="label">Programme:</span>
                <span class="value"><?php echo htmlspecialchars($application['program_choice_1']); ?></span>
            </div>
            
            <div class="exam-slip-item">
                <span class="label">Examination Date:</span>
                <span class="value"><?php echo date('l, jS F Y', strtotime($exam_slip['exam_date'])); ?></span>
            </div>
            
            <div class="exam-slip-item">
                <span class="label">Examination Time:</span>
                <span class="value"><?php echo date('h:i A', strtotime($exam_slip['exam_time'])); ?></span>
            </div>
            
            <div class="exam-slip-item">
                <span class="label">Reporting Time:</span>
                <span class="value"><?php echo date('h:i A', strtotime($exam_slip['reporting_time'])); ?></span>
            </div>
            
            <div class="exam-slip-item">
                <span class="label">Venue:</span>
                <span class="value"><?php echo htmlspecialchars($exam_slip['exam_venue']); ?></span>
            </div>
            
            <div class="exam-slip-item">
                <span class="label">Seat Number:</span>
                <span class="value"><?php echo htmlspecialchars($exam_slip['seat_number']); ?></span>
            </div>
        </div>
        
        <div class="text-center mt-4">
            <div style="display: inline-block; padding: 10px; background: #f8f9fa; border-radius: 8px;">
                <!-- QR Code placeholder -->
                <div style="width: 100px; height: 100px; background: #e0e0e0; margin: 0 auto;"></div>
                <p class="mt-2 small">Scan to verify</p>
            </div>
        </div>
        
        <div class="text-center mt-4">
            <p class="text-muted small">
                <i class="fas fa-info-circle"></i> This slip is computer-generated and does not require signature.<br>
                Generated on: <?php echo date('jS F Y, h:i A', strtotime($exam_slip['generated_at'])); ?>
            </p>
        </div>
    </div>
    
    <div class="text-center mt-4">
        <a href="/apply/download-exam-slip" class="btn btn-success btn-lg">
            <i class="fas fa-download"></i> Download Examination Slip
        </a>
    </div>
    
    <div class="alert alert-info mt-4">
        <h5><i class="fas fa-exclamation-triangle"></i> Important Instructions:</h5>
        <ul class="mb-0">
            <li>Print this slip and bring it to the examination venue</li>
            <li>Arrive at least 30 minutes before the reporting time</li>
            <li>Bring your writing materials (pen, pencil, eraser)</li>
            <li>Bring a valid means of identification (National ID, Driver's License, or International Passport)</li>
            <li>Electronic devices (phones, calculators) are not allowed in the examination hall</li>
        </ul>
    </div>
    
<?php else: ?>
    <div class="alert alert-warning text-center">
        <i class="fas fa-exclamation-triangle fa-3x mb-3"></i>
        <h4>Examination Slip Not Available</h4>
        <p>Your examination slip is being generated. Please check back later or contact support if this persists.</p>
    </div>
<?php endif; ?>

<div class="d-flex justify-content-between mt-4">
    <a href="/apply/step/3" class="btn btn-outline-secondary">
        <i class="fas fa-arrow-left"></i> Back to Payment
    </a>
    <a href="/applicant/logout" class="btn btn-outline-danger" onclick="return confirm('Are you sure you want to logout?');">
        <i class="fas fa-sign-out-alt"></i> Logout
    </a>
</div>