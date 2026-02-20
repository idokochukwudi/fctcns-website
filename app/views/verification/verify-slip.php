<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verification Result - FCT College of Nursing Sciences</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
        }
        
        .result-card {
            border: none;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
        }
        
        .result-header {
            padding: 30px;
            text-align: center;
            color: white;
        }
        
        .result-header.verified {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
        }
        
        .result-header.unverified {
            background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
        }
        
        .result-icon {
            width: 80px;
            height: 80px;
            background: rgba(255,255,255,0.2);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
            font-size: 40px;
        }
        
        .result-body {
            padding: 40px;
            background: white;
        }
        
        .info-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
            margin: 20px 0;
        }
        
        .info-item {
            padding: 15px;
            background: #f8f9fa;
            border-radius: 10px;
        }
        
        .info-label {
            font-size: 12px;
            color: #6c757d;
            margin-bottom: 5px;
        }
        
        .info-value {
            font-size: 16px;
            font-weight: 600;
            color: #212529;
        }
        
        .status-badge {
            display: inline-block;
            padding: 5px 15px;
            border-radius: 50px;
            font-size: 14px;
            font-weight: 600;
        }
        
        .status-badge.verified {
            background: #d4edda;
            color: #155724;
        }
        
        .status-badge.unverified {
            background: #f8d7da;
            color: #721c24;
        }
        
        .verification-footer {
            background: #f8f9fa;
            padding: 20px;
            border-top: 1px solid #dee2e6;
        }
        
        .print-btn {
            position: fixed;
            bottom: 30px;
            right: 30px;
            width: 60px;
            height: 60px;
            border-radius: 50%;
            background: #667eea;
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            box-shadow: 0 5px 20px rgba(102, 126, 234, 0.4);
            cursor: pointer;
            transition: all 0.3s ease;
            border: none;
        }
        
        .print-btn:hover {
            transform: scale(1.1);
            box-shadow: 0 10px 30px rgba(102, 126, 234, 0.6);
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card result-card">
                    <div class="result-header <?php echo $verified ? 'verified' : 'unverified'; ?>">
                        <div class="result-icon">
                            <i class="fas <?php echo $verified ? 'fa-check-circle' : 'fa-times-circle'; ?>"></i>
                        </div>
                        <h2 class="mb-2">Document Verification Result</h2>
                        <span class="status-badge <?php echo $verified ? 'verified' : 'unverified'; ?>">
                            <?php echo $verified ? '✓ VERIFIED' : '✗ NOT VERIFIED'; ?>
                        </span>
                    </div>
                    
                    <div class="result-body">
                        <?php if ($verified): ?>
                            <div class="alert alert-success">
                                <i class="fas fa-check-circle me-2"></i>
                                This document is authentic and has been verified by FCT College of Nursing Sciences.
                            </div>
                            
                            <div class="info-grid">
                                <div class="info-item">
                                    <div class="info-label">Slip Number</div>
                                    <div class="info-value"><?php echo htmlspecialchars($exam_slip['slip_number']); ?></div>
                                </div>
                                
                                <div class="info-item">
                                    <div class="info-label">Application Number</div>
                                    <div class="info-value"><?php echo htmlspecialchars($application['application_number']); ?></div>
                                </div>
                                
                                <div class="info-item">
                                    <div class="info-label">Candidate Name</div>
                                    <div class="info-value"><?php echo htmlspecialchars($application['first_name'] . ' ' . $application['last_name']); ?></div>
                                </div>
                                
                                <div class="info-item">
                                    <div class="info-label">JAMB Number</div>
                                    <div class="info-value"><?php echo htmlspecialchars($application['jamb_number']); ?></div>
                                </div>
                                
                                <div class="info-item">
                                    <div class="info-label">Programme</div>
                                    <div class="info-value"><?php echo htmlspecialchars($application['program_choice_1']); ?></div>
                                </div>
                                
                                <div class="info-item">
                                    <div class="info-label">Examination Date</div>
                                    <div class="info-value"><?php echo date('jS F Y', strtotime($exam_slip['exam_date'])); ?></div>
                                </div>
                                
                                <div class="info-item">
                                    <div class="info-label">Examination Time</div>
                                    <div class="info-value"><?php echo date('h:i A', strtotime($exam_slip['exam_time'])); ?></div>
                                </div>
                                
                                <div class="info-item">
                                    <div class="info-label">Venue</div>
                                    <div class="info-value"><?php echo htmlspecialchars($exam_slip['exam_venue']); ?></div>
                                </div>
                                
                                <div class="info-item">
                                    <div class="info-label">Seat Number</div>
                                    <div class="info-value"><?php echo htmlspecialchars($exam_slip['seat_number']); ?></div>
                                </div>
                                
                                <div class="info-item">
                                    <div class="info-label">Payment Status</div>
                                    <div class="info-value">
                                        <?php if ($has_paid): ?>
                                            <span class="badge bg-success">Paid</span>
                                        <?php else: ?>
                                            <span class="badge bg-warning">Pending</span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="alert alert-info mt-3">
                                <div class="d-flex">
                                    <i class="fas fa-info-circle me-2 mt-1"></i>
                                    <div>
                                        <strong>Verification Details:</strong><br>
                                        <small>Verified on: <?php echo date('jS F Y, h:i A', strtotime($verification_time)); ?></small><br>
                                        <small>IP Address: <?php echo htmlspecialchars($verification_ip); ?></small>
                                    </div>
                                </div>
                            </div>
                            
                        <?php else: ?>
                            <div class="alert alert-danger">
                                <i class="fas fa-exclamation-triangle me-2"></i>
                                This document could not be verified. The information provided may be invalid or tampered with.
                            </div>
                            
                            <div class="text-center py-4">
                                <i class="fas fa-file-excel text-danger fa-5x mb-3"></i>
                                <h5>Document Not Found</h5>
                                <p class="text-muted">The document you're trying to verify does not exist in our records.</p>
                            </div>
                        <?php endif; ?>
                        
                        <hr class="my-4">
                        
                        <div class="row g-3">
                            <div class="col-md-6">
                                <button class="btn btn-outline-primary w-100" onclick="window.print()">
                                    <i class="fas fa-print me-2"></i> Print Result
                                </button>
                            </div>
                            <div class="col-md-6">
                                <a href="/verify" class="btn btn-outline-secondary w-100">
                                    <i class="fas fa-redo-alt me-2"></i> Verify Another
                                </a>
                            </div>
                        </div>
                        
                        <div class="text-center mt-4">
                            <p class="text-muted small mb-0">
                                <i class="fas fa-shield-alt me-1"></i>
                                This is an official verification from FCT College of Nursing Sciences
                            </p>
                        </div>
                    </div>
                    
                    <div class="verification-footer text-center">
                        <img src="/assets/img/college-seal.png" alt="Seal" height="40" class="mb-2">
                        <p class="small text-muted mb-0">
                            FCT College of Nursing Sciences, Gwagwalada, Abuja<br>
                            For inquiries: <a href="mailto:verification@fctcns.edu.ng">verification@fctcns.edu.ng</a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Floating Print Button -->
    <button class="print-btn" onclick="window.print()">
        <i class="fas fa-print"></i>
    </button>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>