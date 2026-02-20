<?php
// Set page title
$pageTitle = $pageTitle ?? 'Examination Slip - FCT College of Nursing Sciences';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($pageTitle); ?></title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome 6 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Custom Exam Slip CSS -->
    <link href="/assets/css/exam-slip.css" rel="stylesheet">
    <!-- QR Code Library -->
    <script src="https://cdn.jsdelivr.net/npm/qrcode@1.5.1/build/qrcode.min.js"></script>
</head>
<body class="bg-light">
    <div class="container py-4">
        <!-- Status Bar -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex flex-wrap align-items-center justify-content-between">
                            <div>
                                <h4 class="text-primary mb-1">
                                    <i class="fas fa-check-circle text-success me-2"></i>
                                    Application Complete
                                </h4>
                                <p class="text-muted mb-0">
                                    <i class="fas fa-id-card me-2"></i>
                                    Application #: <strong><?php echo htmlspecialchars($application['application_number'] ?? 'N/A'); ?></strong>
                                </p>
                            </div>
                            <div class="mt-2 mt-md-0">
                                <span class="badge bg-success px-3 py-2">
                                    <i class="fas fa-check-circle me-1"></i> PAID & VERIFIED
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="row">
            <div class="col-lg-8 mx-auto">
                <?php if (!empty($exam_slip)): ?>
                    <!-- Examination Slip Card -->
                    <div class="card border-0 shadow-lg mb-4" id="examSlipCard">
                        <div class="card-header bg-primary text-white py-3">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-ticket-alt fa-2x me-3"></i>
                                <div>
                                    <h3 class="mb-0">EXAMINATION SLIP</h3>
                                    <small class="opacity-75">2025/2026 Admission Screening</small>
                                </div>
                            </div>
                        </div>
                        
                        <div class="card-body p-4">
                            <!-- Official Header -->
                            <div class="text-center mb-4 pb-3 border-bottom">
                                <h2 class="fw-bold text-primary mb-1">FCT COLLEGE OF NURSING SCIENCES</h2>
                                <h5 class="text-secondary">Gwagwalada, Abuja</h5>
                                <div class="mt-3">
                                    <span class="badge bg-warning text-dark px-3 py-2">
                                        <i class="fas fa-certificate me-2"></i>
                                        OFFICIAL EXAMINATION SLIP
                                    </span>
                                </div>
                            </div>

                            <!-- Candidate Photo and QR Code Row -->
                            <div class="row mb-4">
                                <div class="col-md-4 text-center">
                                    <div class="candidate-photo-container border rounded p-2 bg-light">
                                        <?php if (!empty($application['passport_photo'])): ?>
                                            <img src="<?php echo htmlspecialchars($application['passport_photo']); ?>" 
                                                 alt="Passport" 
                                                 class="img-fluid rounded"
                                                 style="max-height: 150px; width: auto;">
                                        <?php else: ?>
                                            <div class="no-photo-placeholder d-flex align-items-center justify-content-center" 
                                                 style="height: 150px; background: #f8f9fa;">
                                                <i class="fas fa-user-circle fa-4x text-secondary"></i>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                    <p class="small text-muted mt-2">
                                        <i class="fas fa-camera"></i> Passport Photograph
                                    </p>
                                </div>
                                
                                <div class="col-md-4 text-center">
                                    <div class="qr-code-container bg-white p-3 rounded border">
                                        <div id="qrcode" style="width: 150px; height: 150px; margin: 0 auto;"></div>
                                        <p class="small text-muted mt-2 mb-0">
                                            <i class="fas fa-qrcode"></i> Scan to Verify
                                        </p>
                                    </div>
                                </div>
                                
                                <div class="col-md-4">
                                    <div class="verification-badge bg-success-light p-3 rounded">
                                        <h6 class="text-success mb-2">
                                            <i class="fas fa-shield-alt me-1"></i> Verified
                                        </h6>
                                        <p class="small mb-1">
                                            <i class="fas fa-check-circle text-success me-1"></i>
                                            Payment Confirmed
                                        </p>
                                        <p class="small mb-0">
                                            <i class="fas fa-clock text-info me-1"></i>
                                            Generated: <?php echo date('d/m/Y H:i', strtotime($exam_slip['generated_at'])); ?>
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <!-- Candidate Details Table -->
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <tr class="bg-light">
                                        <th width="40%">Slip Number</th>
                                        <td><strong class="text-primary"><?php echo htmlspecialchars($exam_slip['slip_number']); ?></strong></td>
                                    </tr>
                                    <tr>
                                        <th>Application Number</th>
                                        <td><?php echo htmlspecialchars($application['application_number']); ?></td>
                                    </tr>
                                    <tr>
                                        <th>JAMB Registration Number</th>
                                        <td><?php echo htmlspecialchars($application['jamb_number']); ?></td>
                                    </tr>
                                    <tr>
                                        <th>Full Name</th>
                                        <td><?php echo htmlspecialchars($application['first_name'] . ' ' . $application['last_name']); ?></td>
                                    </tr>
                                    <tr>
                                        <th>Programme of Study</th>
                                        <td><span class="badge bg-info"><?php echo htmlspecialchars($application['program_choice_1']); ?></span></td>
                                    </tr>
                                    <tr class="table-warning">
                                        <th>Examination Date</th>
                                        <td><strong><?php echo date('l, jS F Y', strtotime($exam_slip['exam_date'])); ?></strong></td>
                                    </tr>
                                    <tr>
                                        <th>Examination Time</th>
                                        <td><?php echo date('h:i A', strtotime($exam_slip['exam_time'])); ?></td>
                                    </tr>
                                    <tr>
                                        <th>Reporting Time</th>
                                        <td><span class="text-danger"><?php echo date('h:i A', strtotime($exam_slip['reporting_time'])); ?> (Arrive 30 mins early)</span></td>
                                    </tr>
                                    <tr>
                                        <th>Venue</th>
                                        <td><?php echo htmlspecialchars($exam_slip['exam_venue']); ?></td>
                                    </tr>
                                    <tr>
                                        <th>Seat Number</th>
                                        <td><h5 class="text-success mb-0"><?php echo htmlspecialchars($exam_slip['seat_number']); ?></h5></td>
                                    </tr>
                                </table>
                            </div>

                            <!-- Important Instructions -->
                            <div class="alert alert-info mt-4">
                                <div class="d-flex">
                                    <div class="me-3">
                                        <i class="fas fa-info-circle fa-2x"></i>
                                    </div>
                                    <div>
                                        <h5 class="alert-heading">Important Instructions</h5>
                                        <ul class="mb-0 small">
                                            <li>Print this slip and bring it to the examination venue</li>
                                            <li>Arrive at least 30 minutes before the reporting time</li>
                                            <li>Bring your writing materials (pen, pencil, eraser)</li>
                                            <li>Bring a valid means of identification (National ID, Driver's License, or International Passport)</li>
                                            <li>Electronic devices (phones, calculators) are not allowed in the examination hall</li>
                                            <li>The QR code on this slip will be scanned for verification at the entrance</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>

                            <!-- Footer Note -->
                            <div class="text-center mt-3">
                                <p class="text-muted small mb-0">
                                    <i class="fas fa-print"></i> This slip is computer-generated and does not require signature.<br>
                                    <i class="fas fa-qrcode"></i> QR Code contains encrypted verification data
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="row g-3">
                        <div class="col-md-3">
                            <a href="/apply/download-exam-slip" class="btn btn-success w-100 py-3" id="downloadBtn">
                                <i class="fas fa-download me-2"></i> Download PDF
                            </a>
                        </div>
                        <div class="col-md-3">
                            <button class="btn btn-primary w-100 py-3" onclick="window.print()">
                                <i class="fas fa-print me-2"></i> Print Slip
                            </button>
                        </div>
                        <div class="col-md-3">
                            <button class="btn btn-info w-100 py-3" onclick="shareSlip()">
                                <i class="fas fa-share-alt me-2"></i> Share
                            </button>
                        </div>
                        <div class="col-md-3">
                            <a href="/applicant/dashboard" class="btn btn-outline-secondary w-100 py-3">
                                <i class="fas fa-tachometer-alt me-2"></i> Dashboard
                            </a>
                        </div>
                    </div>

                    <!-- Verification Link -->
                    <div class="card mt-4 border-0 bg-light">
                        <div class="card-body text-center">
                            <p class="mb-2">
                                <i class="fas fa-link text-primary me-2"></i>
                                Public Verification Link:
                            </p>
                            <div class="input-group">
                                <input type="text" class="form-control" 
                                       value="<?php echo BASE_URL; ?>/verify/slip/<?php echo $exam_slip['slip_number']; ?>" 
                                       id="verificationLink" readonly>
                                <button class="btn btn-primary" type="button" onclick="copyVerificationLink()">
                                    <i class="fas fa-copy"></i> Copy
                                </button>
                            </div>
                        </div>
                    </div>

                <?php else: ?>
                    <!-- Error State -->
                    <div class="card border-0 shadow-lg">
                        <div class="card-body text-center py-5">
                            <div class="mb-4">
                                <i class="fas fa-exclamation-triangle text-warning fa-4x"></i>
                            </div>
                            <h3 class="mb-3">Examination Slip Not Available</h3>
                            <p class="text-muted mb-4">
                                Your examination slip is being generated. Please check back later or contact support if this persists.
                            </p>
                            <div class="d-flex justify-content-center gap-3">
                                <a href="/apply/step/3" class="btn btn-outline-primary">
                                    <i class="fas fa-arrow-left me-2"></i> Back to Payment
                                </a>
                                <button class="btn btn-primary" onclick="location.reload()">
                                    <i class="fas fa-sync-alt me-2"></i> Refresh
                                </button>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Success Modal -->
    <div class="modal fade" id="successModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title">
                        <i class="fas fa-check-circle me-2"></i> Success!
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body text-center py-4">
                    <i class="fas fa-check-circle text-success fa-4x mb-3"></i>
                    <p id="successMessage" class="mb-0">Action completed successfully!</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // Generate QR Code
        document.addEventListener('DOMContentLoaded', function() {
            <?php if (!empty($exam_slip)): ?>
            var verificationData = {
                slip: '<?php echo $exam_slip['slip_number']; ?>',
                app: '<?php echo $application['application_number']; ?>',
                jamb: '<?php echo $application['jamb_number']; ?>',
                name: '<?php echo addslashes($application['first_name'] . ' ' . $application['last_name']); ?>',
                date: '<?php echo $exam_slip['exam_date']; ?>'
            };
            
            var verificationUrl = '<?php echo BASE_URL; ?>/verify/slip/<?php echo $exam_slip['slip_number']; ?>';
            
            QRCode.toCanvas(document.getElementById('qrcode'), verificationUrl, {
                width: 150,
                margin: 1,
                color: {
                    dark: '#000000',
                    light: '#ffffff'
                }
            }, function(error) {
                if (error) console.error(error);
            });
            <?php endif; ?>
        });

        // Copy verification link
        function copyVerificationLink() {
            var linkInput = document.getElementById('verificationLink');
            linkInput.select();
            linkInput.setSelectionRange(0, 99999);
            document.execCommand('copy');
            
            showSuccess('Verification link copied to clipboard!');
        }

        // Share slip
        function shareSlip() {
            if (navigator.share) {
                navigator.share({
                    title: 'Examination Slip - FCT College of Nursing Sciences',
                    text: 'My examination slip for the 2025/2026 admission screening',
                    url: window.location.href
                }).catch(console.error);
            } else {
                copyVerificationLink();
            }
        }

        // Show success message
        function showSuccess(message) {
            document.getElementById('successMessage').textContent = message;
            var modal = new bootstrap.Modal(document.getElementById('successModal'));
            modal.show();
            setTimeout(function() {
                modal.hide();
            }, 2000);
        }

        // Download as PDF (using browser print)
        document.getElementById('downloadBtn')?.addEventListener('click', function(e) {
            e.preventDefault();
            window.print();
        });
    </script>
</body>
</html>