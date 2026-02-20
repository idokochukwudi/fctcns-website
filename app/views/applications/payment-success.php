<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Successful - FCT College of Nursing Sciences</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        .success-card {
            border: none;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            animation: slideIn 0.5s ease-out;
        }
        
        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(50px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .success-header {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            color: white;
            padding: 40px;
            text-align: center;
        }
        
        .success-icon {
            width: 100px;
            height: 100px;
            background: rgba(255,255,255,0.2);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
            font-size: 50px;
            animation: pulse 2s infinite;
        }
        
        @keyframes pulse {
            0% {
                transform: scale(1);
                box-shadow: 0 0 0 0 rgba(255,255,255,0.7);
            }
            70% {
                transform: scale(1.1);
                box-shadow: 0 0 0 10px rgba(255,255,255,0);
            }
            100% {
                transform: scale(1);
                box-shadow: 0 0 0 0 rgba(255,255,255,0);
            }
        }
        
        .success-body {
            padding: 40px;
            background: white;
        }
        
        .payment-details {
            background: #f8f9fa;
            border-radius: 15px;
            padding: 20px;
            margin: 20px 0;
        }
        
        .detail-item {
            display: flex;
            justify-content: space-between;
            padding: 10px 0;
            border-bottom: 1px solid #dee2e6;
        }
        
        .detail-item:last-child {
            border-bottom: none;
        }
        
        .detail-label {
            color: #6c757d;
            font-weight: 600;
        }
        
        .detail-value {
            color: #212529;
            font-weight: 600;
        }
        
        .rrr-code {
            background: #e9ecef;
            padding: 15px;
            border-radius: 10px;
            font-family: 'Courier New', monospace;
            font-size: 1.2rem;
            text-align: center;
            letter-spacing: 2px;
            border: 2px dashed #28a745;
        }
        
        .action-buttons {
            display: flex;
            gap: 15px;
            justify-content: center;
            margin-top: 30px;
        }
        
        .btn-action {
            padding: 12px 30px;
            border-radius: 50px;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        
        .btn-action:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.1);
        }
        
        .confetti {
            position: absolute;
            width: 10px;
            height: 10px;
            background: #f0f0f0;
            opacity: 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card success-card">
                    <div class="success-header">
                        <div class="success-icon">
                            <i class="fas fa-check"></i>
                        </div>
                        <h2 class="mb-2">Payment Successful!</h2>
                        <p class="mb-0 opacity-75">Your application fee has been received</p>
                    </div>
                    
                    <div class="success-body">
                        <div class="text-center mb-4">
                            <h4>Thank You, <?php echo htmlspecialchars($applicant_name ?? 'Applicant'); ?>!</h4>
                            <p class="text-muted">Your payment has been processed successfully</p>
                        </div>
                        
                        <div class="payment-details">
                            <h5 class="mb-3"><i class="fas fa-receipt me-2"></i>Payment Details</h5>
                            
                            <div class="detail-item">
                                <span class="detail-label">Application Number:</span>
                                <span class="detail-value"><?php echo htmlspecialchars($application['application_number'] ?? 'N/A'); ?></span>
                            </div>
                            
                            <div class="detail-item">
                                <span class="detail-label">RRR:</span>
                                <span class="detail-value text-primary"><?php echo htmlspecialchars($payment['rrr'] ?? 'N/A'); ?></span>
                            </div>
                            
                            <div class="detail-item">
                                <span class="detail-label">Amount Paid:</span>
                                <span class="detail-value text-success">₦<?php echo number_format($payment['amount'] ?? 0, 2); ?></span>
                            </div>
                            
                            <div class="detail-item">
                                <span class="detail-label">Payment Date:</span>
                                <span class="detail-value"><?php echo date('jS F Y, h:i A', strtotime($payment['payment_date'] ?? date('Y-m-d H:i:s'))); ?></span>
                            </div>
                            
                            <div class="detail-item">
                                <span class="detail-label">Transaction ID:</span>
                                <span class="detail-value"><?php echo htmlspecialchars($payment['transaction_id'] ?? 'N/A'); ?></span>
                            </div>
                        </div>
                        
                        <div class="rrr-code mb-4">
                            <small class="text-muted d-block mb-2">Your RRR Code</small>
                            <strong><?php echo htmlspecialchars($payment['rrr'] ?? 'N/A'); ?></strong>
                        </div>
                        
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-2"></i>
                            Your examination slip is now available. Click the button below to view and print it.
                        </div>
                        
                        <div class="action-buttons">
                            <a href="/apply/step/4" class="btn btn-primary btn-action">
                                <i class="fas fa-ticket-alt me-2"></i> View Exam Slip
                            </a>
                            <a href="/apply/download-exam-slip" class="btn btn-success btn-action">
                                <i class="fas fa-download me-2"></i> Download Slip
                            </a>
                            <button onclick="window.print()" class="btn btn-outline-secondary btn-action">
                                <i class="fas fa-print me-2"></i> Print
                            </button>
                        </div>
                        
                        <hr class="my-4">
                        
                        <div class="text-center">
                            <p class="text-muted small mb-2">
                                <i class="fas fa-envelope me-1"></i>
                                A confirmation email has been sent to <?php echo htmlspecialchars($applicant_email ?? 'your email'); ?>
                            </p>
                            <div class="btn-group" role="group">
                                <a href="/applicant/dashboard" class="btn btn-link">
                                    <i class="fas fa-tachometer-alt me-1"></i> Dashboard
                                </a>
                                <a href="/apply/step/4" class="btn btn-link">
                                    <i class="fas fa-eye me-1"></i> View Slip
                                </a>
                                <a href="/support" class="btn btn-link">
                                    <i class="fas fa-question-circle me-1"></i> Need Help?
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Confetti Effect -->
    <script>
        // Add confetti effect on load
        document.addEventListener('DOMContentLoaded', function() {
            for (let i = 0; i < 50; i++) {
                createConfetti();
            }
        });
        
        function createConfetti() {
            const confetti = document.createElement('div');
            confetti.className = 'confetti';
            confetti.style.left = Math.random() * 100 + '%';
            confetti.style.animation = `confetti ${Math.random() * 3 + 2}s linear infinite`;
            confetti.style.background = `hsl(${Math.random() * 360}, 50%, 50%)`;
            document.body.appendChild(confetti);
            
            setTimeout(() => confetti.remove(), 5000);
        }
    </script>
    
    <style>
        @keyframes confetti {
            0% {
                transform: translateY(-100vh) rotate(0deg);
                opacity: 1;
            }
            100% {
                transform: translateY(100vh) rotate(720deg);
                opacity: 0;
            }
        }
    </style>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>