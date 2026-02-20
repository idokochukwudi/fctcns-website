<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Failed - FCT College of Nursing Sciences</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        .failed-card {
            border: none;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            animation: shake 0.5s ease-in-out;
        }
        
        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            10%, 30%, 50%, 70%, 90% { transform: translateX(-5px); }
            20%, 40%, 60%, 80% { transform: translateX(5px); }
        }
        
        .failed-header {
            background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
            color: white;
            padding: 40px;
            text-align: center;
        }
        
        .failed-icon {
            width: 100px;
            height: 100px;
            background: rgba(255,255,255,0.2);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
            font-size: 50px;
            animation: bounce 1s infinite;
        }
        
        @keyframes bounce {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-10px); }
        }
        
        .failed-body {
            padding: 40px;
            background: white;
        }
        
        .error-details {
            background: #fff5f5;
            border-radius: 15px;
            padding: 20px;
            margin: 20px 0;
            border-left: 4px solid #dc3545;
        }
        
        .detail-item {
            display: flex;
            justify-content: space-between;
            padding: 10px 0;
            border-bottom: 1px solid #fde2e2;
        }
        
        .detail-item:last-child {
            border-bottom: none;
        }
        
        .detail-label {
            color: #721c24;
            font-weight: 600;
        }
        
        .detail-value {
            color: #721c24;
            font-weight: 600;
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
        
        .support-card {
            background: #f8f9fa;
            border-radius: 15px;
            padding: 20px;
            margin-top: 30px;
        }
        
        .help-option {
            transition: all 0.3s ease;
            cursor: pointer;
        }
        
        .help-option:hover {
            background: white;
            transform: translateX(5px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card failed-card">
                    <div class="failed-header">
                        <div class="failed-icon">
                            <i class="fas fa-times"></i>
                        </div>
                        <h2 class="mb-2">Payment Failed</h2>
                        <p class="mb-0 opacity-75">We couldn't process your payment</p>
                    </div>
                    
                    <div class="failed-body">
                        <div class="text-center mb-4">
                            <h4>Oops! Something went wrong</h4>
                            <p class="text-muted">Don't worry, this happens sometimes. Here's what you can do:</p>
                        </div>
                        
                        <div class="error-details">
                            <h5 class="mb-3 text-danger">
                                <i class="fas fa-exclamation-triangle me-2"></i>Error Details
                            </h5>
                            
                            <div class="detail-item">
                                <span class="detail-label">Error Message:</span>
                                <span class="detail-value"><?php echo htmlspecialchars($error_message ?? 'Payment processing failed'); ?></span>
                            </div>
                            
                            <div class="detail-item">
                                <span class="detail-label">Reference:</span>
                                <span class="detail-value"><?php echo htmlspecialchars($payment_reference ?? 'N/A'); ?></span>
                            </div>
                            
                            <div class="detail-item">
                                <span class="detail-label">Date/Time:</span>
                                <span class="detail-value"><?php echo date('jS F Y, h:i A'); ?></span>
                            </div>
                        </div>
                        
                        <div class="action-buttons">
                            <button onclick="retryPayment()" class="btn btn-primary btn-action">
                                <i class="fas fa-redo-alt me-2"></i> Retry Payment
                            </button>
                            <a href="/apply/step/3" class="btn btn-outline-primary btn-action">
                                <i class="fas fa-arrow-left me-2"></i> Go Back
                            </a>
                        </div>
                        
                        <div class="support-card">
                            <h5 class="mb-3"><i class="fas fa-headset me-2"></i>Need Help?</h5>
                            <p class="text-muted small mb-3">Choose how you'd like to get support:</p>
                            
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <div class="help-option p-3 border rounded text-center" onclick="window.location.href='tel:07039837749'">
                                        <i class="fas fa-phone-alt text-primary fa-2x mb-2"></i>
                                        <h6 class="mb-0">Call Us</h6>
                                        <small class="text-muted">07039837749</small>
                                    </div>
                                </div>
                                
                                <div class="col-md-4">
                                    <div class="help-option p-3 border rounded text-center" onclick="window.location.href='https://wa.me/2347039837749'">
                                        <i class="fab fa-whatsapp text-success fa-2x mb-2"></i>
                                        <h6 class="mb-0">WhatsApp</h6>
                                        <small class="text-muted">Chat with us</small>
                                    </div>
                                </div>
                                
                                <div class="col-md-4">
                                    <div class="help-option p-3 border rounded text-center" onclick="window.location.href='mailto:support@fctcns.edu.ng'">
                                        <i class="fas fa-envelope text-info fa-2x mb-2"></i>
                                        <h6 class="mb-0">Email</h6>
                                        <small class="text-muted">support@fctcns.edu.ng</small>
                                    </div>
                                </div>
                            </div>
                            
                            <hr class="my-4">
                            
                            <div class="text-center">
                                <p class="text-muted small mb-0">
                                    <i class="fas fa-clock me-1"></i>
                                    Response time: Within 24 hours
                                </p>
                            </div>
                        </div>
                        
                        <div class="text-center mt-4">
                            <a href="/" class="text-decoration-none">
                                <i class="fas fa-home me-1"></i> Return to Homepage
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Retry Modal -->
    <div class="modal fade" id="retryModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title">
                        <i class="fas fa-redo-alt me-2"></i> Retry Payment
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body text-center py-4">
                    <div class="spinner-border text-primary mb-3" role="status" id="retrySpinner" style="display: none;">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <i class="fas fa-exclamation-circle text-warning fa-4x mb-3" id="retryIcon"></i>
                    <p id="retryMessage">Are you sure you want to retry the payment?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" onclick="confirmRetry()" id="confirmRetryBtn">
                        Yes, Retry Payment
                    </button>
                </div>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        let retryModal;
        
        document.addEventListener('DOMContentLoaded', function() {
            retryModal = new bootstrap.Modal(document.getElementById('retryModal'));
        });
        
        function retryPayment() {
            retryModal.show();
        }
        
        function confirmRetry() {
            const spinner = document.getElementById('retrySpinner');
            const icon = document.getElementById('retryIcon');
            const message = document.getElementById('retryMessage');
            const btn = document.getElementById('confirmRetryBtn');
            
            spinner.style.display = 'inline-block';
            icon.style.display = 'none';
            message.textContent = 'Processing...';
            btn.disabled = true;
            
            // Simulate processing
            setTimeout(function() {
                window.location.href = '/apply/step/3';
            }, 2000);
        }
        
        // Auto-dismiss alerts after 5 seconds
        setTimeout(function() {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(function(alert) {
                alert.style.transition = 'opacity 0.5s ease';
                alert.style.opacity = '0';
                setTimeout(() => alert.remove(), 500);
            });
        }, 5000);
    </script>
</body>
</html>