<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <!-- Progress Indicator -->
            <div class="text-center mb-5">
                <h1 class="display-5 fw-bold text-primary">FCT College of Nursing Sciences</h1>
                <p class="lead">2025/2026 Admissions Application Portal</p>
                <div class="d-flex justify-content-center gap-2 mt-3">
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
                    <div class="alert alert-info mb-4">
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
                                <p id="paymentRRR" class="text-muted"></p>
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

                    <!-- Action Buttons -->
                    <div class="d-flex flex-column gap-3">
                        <button class="btn btn-primary btn-lg" id="payNowBtn">
                            <i class="fas fa-play me-2"></i>Pay Now
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

<script>
document.getElementById('payNowBtn').addEventListener('click', function() {
    initiatePayment();
});

function initiatePayment() {
    // Show payment status area
    document.getElementById('paymentStatus').style.display = 'block';
    document.getElementById('payNowBtn').disabled = true;
    document.getElementById('paymentMessage').innerText = 'Generating RRR...';
    
    fetch('/payment/initiate', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: JSON.stringify({
            csrf_token: '<?php echo $csrf_token; ?>'
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            document.getElementById('paymentMessage').innerText = 'RRR Generated Successfully!';
            document.getElementById('paymentRRR').innerHTML = 'RRR: <strong>' + data.rrr + '</strong>';
            
            // Show Remita link
            const remitaUrl = 'https://remitademo.net/remita/ecomm/frame/handleCCD.action?rrr=' + data.rrr;
            
            if (confirm('RRR generated: ' + data.rrr + '\n\nClick OK to proceed to Remita payment page.')) {
                window.open(remitaUrl, '_blank');
                
                // Show verify button after returning
                setTimeout(() => {
                    document.getElementById('checkStatusBtn').style.display = 'inline-block';
                    document.getElementById('checkStatusBtn').onclick = function() {
                        checkPaymentStatus(data.rrr);
                    };
                }, 5000);
            }
        } else {
            showAlert(data.message || 'Failed to generate RRR', 'danger');
            resetPayment();
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showAlert('Network error. Please try again.', 'danger');
        resetPayment();
    });
}

function checkPaymentStatus(rrr) {
    document.getElementById('paymentMessage').innerText = 'Verifying payment...';
    document.getElementById('checkStatusBtn').disabled = true;
    
    fetch('/payment/status?rrr=' + encodeURIComponent(rrr))
    .then(response => response.json())
    .then(data => {
        if (data.status === 'success') {
            showAlert('Payment verified successfully! Redirecting...', 'success');
            setTimeout(() => {
                window.location.href = '/apply/step/4';
            }, 2000);
        } else if (data.status === 'pending') {
            document.getElementById('paymentMessage').innerText = 'Payment still processing. Please wait...';
            document.getElementById('checkStatusBtn').disabled = false;
            
            // Check again after 10 seconds
            setTimeout(() => checkPaymentStatus(rrr), 10000);
        } else {
            showAlert('Payment not yet completed. Please complete payment first.', 'warning');
            document.getElementById('paymentMessage').innerText = 'Payment not verified';
            document.getElementById('checkStatusBtn').disabled = false;
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showAlert('Error checking payment status', 'danger');
        document.getElementById('checkStatusBtn').disabled = false;
    });
}

function verifyPayment(rrr) {
    document.getElementById('paymentStatus').style.display = 'block';
    document.getElementById('paymentMessage').innerText = 'Verifying payment...';
    
    fetch('/payment/verify', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({ rrr: rrr })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showAlert('Payment verified! Redirecting...', 'success');
            setTimeout(() => {
                window.location.href = '/apply/step/4';
            }, 2000);
        } else {
            showAlert(data.message || 'Payment verification failed', 'danger');
            resetPayment();
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showAlert('Error verifying payment', 'danger');
        resetPayment();
    });
}

function showAlert(message, type) {
    const alertContainer = document.getElementById('alertContainer');
    alertContainer.innerHTML = `
        <div class="alert alert-${type} alert-dismissible fade show" role="alert">
            <i class="fas ${type === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle'} me-2"></i>
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    `;
    
    // Auto dismiss after 5 seconds
    setTimeout(() => {
        const alert = alertContainer.querySelector('.alert');
        if (alert) {
            alert.classList.remove('show');
            setTimeout(() => alertContainer.innerHTML = '', 300);
        }
    }, 5000);
}

function resetPayment() {
    document.getElementById('paymentStatus').style.display = 'none';
    document.getElementById('payNowBtn').disabled = false;
    document.getElementById('checkStatusBtn').style.display = 'none';
}
</script>