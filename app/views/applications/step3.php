<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Step 3: Payment - FCT College of Nursing Sciences</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- CSRF Token Meta Tag (FIXED) -->
    <meta name="csrf-token" content="<?php echo $csrf_token ?? ''; ?>">
    
    <!-- CSRF Token Hidden Input (Backup) -->
    <input type="hidden" name="csrf_token" id="csrf_token" value="<?php echo $csrf_token ?? ''; ?>">
    
    <style>
        .payment-instructions {
            background-color: #f8f9fa;
            border-left: 4px solid #6B4E9B;
        }
        .progress-step {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background-color: #e9ecef;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
        }
        .progress-step.active {
            background-color: #6B4E9B;
            color: white;
        }
        .progress-step.completed {
            background-color: #28a745;
            color: white;
        }
        .rrr-display {
            font-size: 1.2rem;
            font-weight: bold;
            color: #6B4E9B;
            background-color: #f0f0f0;
            padding: 10px;
            border-radius: 5px;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <!-- Progress Indicator -->
                <div class="text-center mb-5">
                    <h1 class="display-5 fw-bold text-primary">FCT College of Nursing Sciences</h1>
                    <p class="lead">2025/2026 Admissions Application Portal</p>
                    
                    <!-- Custom Progress Steps -->
                    <div class="d-flex justify-content-between align-items-center mt-4">
                        <div class="text-center">
                            <div class="progress-step completed mx-auto mb-2">✓</div>
                            <small>JAMB Verified</small>
                        </div>
                        <div class="flex-grow-1 mx-2">
                            <div class="progress" style="height: 4px;">
                                <div class="progress-bar bg-success" style="width: 100%"></div>
                            </div>
                        </div>
                        <div class="text-center">
                            <div class="progress-step completed mx-auto mb-2">✓</div>
                            <small>Form Completed</small>
                        </div>
                        <div class="flex-grow-1 mx-2">
                            <div class="progress" style="height: 4px;">
                                <div class="progress-bar bg-primary" style="width: 50%"></div>
                            </div>
                        </div>
                        <div class="text-center">
                            <div class="progress-step active mx-auto mb-2">3</div>
                            <small>Payment</small>
                        </div>
                        <div class="flex-grow-1 mx-2">
                            <div class="progress" style="height: 4px;">
                                <div class="progress-bar" style="width: 0%"></div>
                            </div>
                        </div>
                        <div class="text-center">
                            <div class="progress-step mx-auto mb-2">4</div>
                            <small>Exam Slip</small>
                        </div>
                    </div>
                    
                    <!-- Original Badge Progress (kept for compatibility) -->
                    <div class="d-flex justify-content-center gap-2 mt-3 d-none">
                        <span class="badge bg-success rounded-pill px-3 py-2">✓ Step 1: JAMB Verified</span>
                        <span class="badge bg-success rounded-pill px-3 py-2">✓ Step 2: Form Completed</span>
                        <span class="badge bg-primary rounded-pill px-3 py-2">Step 3: Payment</span>
                        <span class="badge bg-secondary rounded-pill px-3 py-2">Step 4: Exam Slip</span>
                    </div>
                </div>

                <!-- Alert Container -->
                <div id="alertContainer" class="mb-4"></div>

                <!-- Payment Card -->
                <div class="card shadow-lg border-0 rounded-lg">
                    <div class="card-header bg-primary text-white text-center py-4">
                        <i class="fas fa-credit-card fa-3x mb-3"></i>
                        <h2 class="h3 mb-0">Payment</h2>
                        <p class="mb-0 small">Step 3 of 4</p>
                    </div>
                    
                    <div class="card-body p-4">
                        <!-- Payment Details -->
                        <div class="text-center mb-4">
                            <h4>Application Fee</h4>
                            <div class="display-4 fw-bold text-primary mb-2">
                                <?php echo $currency ?? '₦'; ?><?php echo number_format($fee ?? 2200); ?>
                            </div>
                            <p class="text-muted">This fee is non-refundable</p>
                        </div>

                        <!-- Payment Instructions -->
                        <div class="alert alert-info payment-instructions mb-4">
                            <h5 class="alert-heading"><i class="fas fa-info-circle me-2"></i>Payment Instructions:</h5>
                            <ol class="mb-0">
                                <li>Click "Pay Now" to generate a payment RRR</li>
                                <li>You'll be redirected to Remita secure payment page</li>
                                <li>Complete payment using your card or bank</li>
                                <li>After payment, return here and click "Verify Payment"</li>
                                <li>Your exam slip will be available immediately after verification</li>
                            </ol>
                        </div>

                        <!-- Payment Status Area -->
                        <div id="paymentStatus" class="mb-4" style="display: none;">
                            <div class="card">
                                <div class="card-body text-center">
                                    <div id="paymentSpinner" class="spinner-border text-primary mb-3" role="status">
                                        <span class="visually-hidden">Loading...</span>
                                    </div>
                                    <h5 id="paymentMessage">Processing payment...</h5>
                                    <div id="paymentRRR" class="rrr-display mt-3" style="display: none;"></div>
                                    <div id="remitaLink" class="mt-3" style="display: none;"></div>
                                </div>
                            </div>
                        </div>

                        <!-- Pending Payment (if exists) -->
                        <?php if (isset($pending_payment) && $pending_payment): ?>
                        <div class="alert alert-warning mb-4">
                            <h5 class="alert-heading"><i class="fas fa-clock me-2"></i>Pending Payment</h5>
                            <p>You have a pending payment with RRR: <strong><?php echo $pending_payment['rrr']; ?></strong></p>
                            <div class="d-flex gap-2">
                                <a href="https://remitademo.net/remita/ecomm/frame/handleCCD.action?rrr=<?php echo $pending_payment['rrr']; ?>" 
                                   target="_blank" class="btn btn-primary">
                                    <i class="fas fa-external-link-alt me-2"></i>Complete Payment
                                </a>
                                <button class="btn btn-success" onclick="verifyPayment('<?php echo $pending_payment['rrr']; ?>')">
                                    <i class="fas fa-check-circle me-2"></i>I've Paid, Verify
                                </button>
                            </div>
                        </div>
                        <?php endif; ?>

                        <!-- RRR Display Area (for generated RRR) -->
                        <div id="rrrDisplayArea" class="mb-4" style="display: none;">
                            <div class="card bg-light">
                                <div class="card-body text-center">
                                    <h5 class="card-title">Your Payment RRR</h5>
                                    <div class="rrr-display mb-3" id="generatedRRR"></div>
                                    <button class="btn btn-sm btn-outline-secondary" onclick="copyRRR()">
                                        <i class="fas fa-copy me-2"></i>Copy RRR
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="d-flex flex-column gap-3">
                            <button class="btn btn-primary btn-lg" id="payNowBtn">
                                <i class="fas fa-play me-2"></i>Pay Now
                            </button>
                            
                            <button class="btn btn-success btn-lg" id="verifyPaymentBtn" style="display: none;">
                                <i class="fas fa-check-circle me-2"></i>I've Paid, Verify
                            </button>
                            
                            <div class="d-flex justify-content-between">
                                <a href="/apply/form" class="btn btn-outline-secondary">
                                    <i class="fas fa-arrow-left me-2"></i>Back to Form
                                </a>
                                <button class="btn btn-outline-primary" id="checkStatusBtn" style="display: none;">
                                    <i class="fas fa-sync me-2"></i>Check Status
                                </button>
                            </div>
                        </div>

                        <hr class="my-4">

                        <!-- Support Information -->
                        <div class="text-center">
                            <h5 class="mb-3">Payment Support</h5>
                            <div class="row">
                                <div class="col-md-4 mb-2">
                                    <i class="fas fa-phone text-primary me-2"></i>
                                    <span>07039837749</span>
                                </div>
                                <div class="col-md-4 mb-2">
                                    <i class="fab fa-whatsapp text-success me-2"></i>
                                    <span>08082775076</span>
                                </div>
                                <div class="col-md-4 mb-2">
                                    <i class="fas fa-envelope text-danger me-2"></i>
                                    <span>info@fctcns.edu.ng</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- jQuery (optional, for compatibility) -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    
    <!-- Payment JavaScript -->
    <script>
    // Get CSRF token from meta tag or hidden input
    function getCsrfToken() {
        return document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || 
               document.getElementById('csrf_token')?.value || 
               '<?php echo $csrf_token ?? ''; ?>';
    }

    // Document ready
    document.addEventListener('DOMContentLoaded', function() {
        console.log('Step 3 page loaded');
        
        // Initialize payment button
        document.getElementById('payNowBtn').addEventListener('click', function(e) {
            e.preventDefault();
            initiatePayment();
        });
        
        // Initialize verify button
        document.getElementById('verifyPaymentBtn').addEventListener('click', function(e) {
            e.preventDefault();
            var rrr = document.getElementById('generatedRRR')?.textContent || 
                     document.querySelector('.rrr-display')?.textContent ||
                     '<?php echo $pending_payment['rrr'] ?? ''; ?>';
            if (rrr) {
                verifyPayment(rrr);
            } else {
                showAlert('No RRR found. Please generate payment first.', 'warning');
            }
        });
        
        // Initialize check status button
        document.getElementById('checkStatusBtn').addEventListener('click', function(e) {
            e.preventDefault();
            var rrr = document.getElementById('generatedRRR')?.textContent || 
                     document.querySelector('.rrr-display')?.textContent ||
                     '<?php echo $pending_payment['rrr'] ?? ''; ?>';
            if (rrr) {
                checkPaymentStatus(rrr);
            } else {
                showAlert('No RRR found.', 'warning');
            }
        });
        
        // Check if we have a pending RRR in sessionStorage
        var pendingRRR = sessionStorage.getItem('pending_rrr');
        if (pendingRRR) {
            showRRR(pendingRRR);
        }
    });

    function initiatePayment() {
        console.log('Initiating payment...');
        
        // Show payment status area
        document.getElementById('paymentStatus').style.display = 'block';
        document.getElementById('payNowBtn').disabled = true;
        document.getElementById('paymentMessage').innerText = 'Generating RRR...';
        document.getElementById('paymentSpinner').style.display = 'inline-block';
        
        // Get CSRF token
        var csrfToken = getCsrfToken();
        console.log('CSRF Token:', csrfToken ? 'Found' : 'Missing');
        
        if (!csrfToken) {
            showAlert('Security token missing. Please refresh the page.', 'danger');
            resetPayment();
            return;
        }
        
        fetch('/payment/initiate', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': csrfToken
            },
            body: JSON.stringify({
                csrf_token: csrfToken
            })
        })
        .then(response => {
            console.log('Response status:', response.status);
            if (!response.ok) {
                throw new Error('HTTP error ' + response.status);
            }
            return response.json();
        })
        .then(data => {
            console.log('Payment initiation response:', data);
            
            document.getElementById('paymentSpinner').style.display = 'none';
            
            if (data.success) {
                document.getElementById('paymentMessage').innerText = 'RRR Generated Successfully!';
                
                // Store RRR
                var rrr = data.rrr;
                sessionStorage.setItem('pending_rrr', rrr);
                
                // Show RRR
                showRRR(rrr);
                
                // Show Remita link
                var remitaUrl = 'https://remitademo.net/remita/ecomm/frame/handleCCD.action?rrr=' + rrr;
                
                // Create Remita link HTML
                var remitaHtml = '<div class="mt-3">' +
                    '<p class="mb-2"><strong>Proceed to payment:</strong></p>' +
                    '<a href="' + remitaUrl + '" target="_blank" class="btn btn-warning">' +
                    '<i class="fas fa-external-link-alt me-2"></i>Pay Now on Remita</a>' +
                    '</div>';
                document.getElementById('remitaLink').innerHTML = remitaHtml;
                document.getElementById('remitaLink').style.display = 'block';
                
                // Show verify button
                document.getElementById('verifyPaymentBtn').style.display = 'block';
                document.getElementById('checkStatusBtn').style.display = 'inline-block';
                
                showAlert('RRR generated successfully: ' + rrr, 'success');
                
                // Open Remita in new window with confirmation
                if (confirm('RRR generated: ' + rrr + '\n\nClick OK to proceed to Remita payment page.')) {
                    window.open(remitaUrl, '_blank');
                }
            } else {
                showAlert(data.message || 'Failed to generate RRR', 'danger');
                resetPayment();
            }
        })
        .catch(error => {
            console.error('Fetch error:', error);
            document.getElementById('paymentSpinner').style.display = 'none';
            showAlert('Network error: ' + error.message, 'danger');
            resetPayment();
        });
    }

    function showRRR(rrr) {
        document.getElementById('generatedRRR').textContent = rrr;
        document.getElementById('rrrDisplayArea').style.display = 'block';
        
        // Also update paymentRRR element if it exists
        if (document.getElementById('paymentRRR')) {
            document.getElementById('paymentRRR').innerHTML = '<strong>RRR:</strong> ' + rrr;
            document.getElementById('paymentRRR').style.display = 'block';
        }
    }

    function verifyPayment(rrr) {
        console.log('Verifying payment for RRR:', rrr);
        
        document.getElementById('paymentStatus').style.display = 'block';
        document.getElementById('paymentMessage').innerText = 'Verifying payment...';
        document.getElementById('verifyPaymentBtn').disabled = true;
        document.getElementById('paymentSpinner').style.display = 'inline-block';
        
        var csrfToken = getCsrfToken();
        
        fetch('/payment/verify', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': csrfToken
            },
            body: JSON.stringify({ 
                rrr: rrr,
                csrf_token: csrfToken 
            })
        })
        .then(response => response.json())
        .then(data => {
            console.log('Verification response:', data);
            document.getElementById('paymentSpinner').style.display = 'none';
            
            if (data.success) {
                document.getElementById('paymentMessage').innerText = 'Payment Verified!';
                showAlert('Payment verified successfully! Redirecting...', 'success');
                
                // Clear session storage
                sessionStorage.removeItem('pending_rrr');
                
                setTimeout(function() {
                    window.location.href = data.redirect || '/apply/step/4';
                }, 2000);
            } else {
                document.getElementById('paymentMessage').innerText = 'Verification Failed';
                showAlert(data.message || 'Payment verification failed', 'danger');
                document.getElementById('verifyPaymentBtn').disabled = false;
            }
        })
        .catch(error => {
            console.error('Error:', error);
            document.getElementById('paymentSpinner').style.display = 'none';
            showAlert('Error verifying payment', 'danger');
            document.getElementById('verifyPaymentBtn').disabled = false;
        });
    }

    function checkPaymentStatus(rrr) {
        console.log('Checking payment status for RRR:', rrr);
        
        document.getElementById('paymentMessage').innerText = 'Checking payment status...';
        document.getElementById('checkStatusBtn').disabled = true;
        document.getElementById('paymentSpinner').style.display = 'inline-block';
        
        fetch('/payment/status?rrr=' + encodeURIComponent(rrr), {
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            console.log('Status response:', data);
            document.getElementById('paymentSpinner').style.display = 'none';
            
            if (data.success && data.status === 'success') {
                document.getElementById('paymentMessage').innerText = 'Payment completed!';
                showAlert('Payment verified successfully! Redirecting...', 'success');
                
                setTimeout(() => {
                    window.location.href = '/apply/step/4';
                }, 2000);
            } else if (data.status === 'pending') {
                document.getElementById('paymentMessage').innerText = 'Payment still processing. Checking again in 10 seconds...';
                document.getElementById('checkStatusBtn').disabled = false;
                
                // Check again after 10 seconds
                setTimeout(() => checkPaymentStatus(rrr), 10000);
            } else {
                document.getElementById('paymentMessage').innerText = 'Payment not yet completed';
                showAlert('Payment not completed. Please complete payment first.', 'warning');
                document.getElementById('checkStatusBtn').disabled = false;
            }
        })
        .catch(error => {
            console.error('Error:', error);
            document.getElementById('paymentSpinner').style.display = 'none';
            showAlert('Error checking payment status', 'danger');
            document.getElementById('checkStatusBtn').disabled = false;
        });
    }

    function showAlert(message, type) {
        const alertContainer = document.getElementById('alertContainer');
        const icon = type === 'success' ? 'fa-check-circle' : 
                     type === 'danger' ? 'fa-exclamation-circle' : 
                     'fa-info-circle';
        
        alertContainer.innerHTML = `
            <div class="alert alert-${type} alert-dismissible fade show" role="alert">
                <i class="fas ${icon} me-2"></i>
                ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        `;
        
        // Auto dismiss after 5 seconds (8 for errors)
        const timeout = type === 'danger' ? 8000 : 5000;
        setTimeout(() => {
            const alert = alertContainer.querySelector('.alert');
            if (alert) {
                alert.classList.remove('show');
                setTimeout(() => {
                    if (alertContainer.querySelector('.alert') === alert) {
                        alertContainer.innerHTML = '';
                    }
                }, 300);
            }
        }, timeout);
    }

    function copyRRR() {
        var rrr = document.getElementById('generatedRRR')?.textContent || 
                 document.querySelector('.rrr-display')?.textContent ||
                 sessionStorage.getItem('pending_rrr');
        
        if (!rrr) {
            showAlert('No RRR to copy', 'warning');
            return;
        }
        
        // Use modern clipboard API if available
        if (navigator.clipboard && navigator.clipboard.writeText) {
            navigator.clipboard.writeText(rrr).then(() => {
                showAlert('RRR copied to clipboard!', 'success');
            }).catch(() => {
                fallbackCopy(rrr);
            });
        } else {
            fallbackCopy(rrr);
        }
    }

    function fallbackCopy(text) {
        var textarea = document.createElement('textarea');
        textarea.value = text;
        document.body.appendChild(textarea);
        textarea.select();
        document.execCommand('copy');
        document.body.removeChild(textarea);
        showAlert('RRR copied to clipboard!', 'success');
    }

    function resetPayment() {
        document.getElementById('paymentStatus').style.display = 'none';
        document.getElementById('payNowBtn').disabled = false;
        document.getElementById('verifyPaymentBtn').style.display = 'none';
        document.getElementById('checkStatusBtn').style.display = 'none';
        document.getElementById('paymentSpinner').style.display = 'none';
    }
    </script>
</body>
</html>