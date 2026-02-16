/**
 * Payment handling JavaScript
 * Handles RRR generation and payment verification
 */

$(document).ready(function() {
    // Handle Pay Now button click
    $('#pay-now-btn').on('click', function(e) {
        e.preventDefault();
        initiatePayment();
    });
    
    // Handle Verify Payment button click
    $('#verify-payment-btn').on('click', function(e) {
        e.preventDefault();
        var rrr = $(this).data('rrr');
        verifyPayment(rrr);
    });
});

/**
 * Initiate payment - generate RRR
 */
function initiatePayment() {
    var btn = $('#pay-now-btn');
    var originalText = btn.html();
    btn.html('<i class="fas fa-spinner fa-spin"></i> Generating RRR...');
    btn.prop('disabled', true);
    
    $.ajax({
        url: '/payment/initiate',
        type: 'POST',
        dataType: 'json',
        success: function(response) {
            if (response.status === 'success') {
                showRRR(response.rrr);
                sessionStorage.setItem('pending_rrr', response.rrr);
                showAlert('success', 'RRR generated successfully!');
                
                var remitaUrl = 'https://remitademo.net/remita/ecomm/frame/handleCCD.action?rrr=' + response.rrr;
                
                if (confirm('RRR generated: ' + response.rrr + '\n\nClick OK to proceed to payment page.')) {
                    window.open(remitaUrl, '_blank');
                }
                
                btn.html(originalText);
                btn.prop('disabled', false);
            } else {
                showAlert('error', response.message || 'Failed to generate RRR');
                btn.html(originalText);
                btn.prop('disabled', false);
            }
        },
        error: function(xhr, status, error) {
            console.error('Payment initiation error:', error);
            showAlert('error', 'An error occurred. Please try again.');
            btn.html(originalText);
            btn.prop('disabled', false);
        }
    });
}

/**
 * Verify payment status
 */
function verifyPayment(rrr) {
    if (!rrr) {
        rrr = sessionStorage.getItem('pending_rrr');
        if (!rrr) {
            showAlert('error', 'No pending payment found. Please generate RRR first.');
            return;
        }
    }
    
    var btn = $('#verify-payment-btn');
    var originalText = btn.html();
    btn.html('<i class="fas fa-spinner fa-spin"></i> Verifying...');
    btn.prop('disabled', true);
    
    $.ajax({
        url: '/payment/verify',
        type: 'POST',
        data: { rrr: rrr },
        dataType: 'json',
        success: function(response) {
            if (response.status === 'success') {
                showAlert('success', 'Payment verified successfully! Redirecting...');
                sessionStorage.removeItem('pending_rrr');
                
                setTimeout(function() {
                    window.location.href = response.redirect || '/apply/step/4';
                }, 2000);
                
            } else if (response.status === 'pending') {
                showAlert('info', response.message || 'Payment is still processing. Please wait...');
                
                setTimeout(function() {
                    verifyPayment(rrr);
                }, 10000);
                
                btn.html(originalText);
                btn.prop('disabled', false);
                
            } else {
                showAlert('error', response.message || 'Payment verification failed');
                btn.html(originalText);
                btn.prop('disabled', false);
            }
        },
        error: function(xhr, status, error) {
            console.error('Payment verification error:', error);
            showAlert('error', 'An error occurred. Please try again.');
            btn.html(originalText);
            btn.prop('disabled', false);
        }
    });
}

/**
 * Show RRR and payment instructions
 */
function showRRR(rrr) {
    var html = '<div class="alert alert-info mt-3">' +
               '<h5><i class="fas fa-info-circle"></i> Payment Details</h5>' +
               '<p><strong>RRR:</strong> <span class="text-primary">' + rrr + '</span></p>' +
               '<p><strong>Instructions:</strong></p>' +
               '<ol>' +
               '<li>Click the "Pay Now" button that opened in a new tab</li>' +
               '<li>Complete your payment on the Remita page</li>' +
               '<li>After payment, return here and click "I\'ve Paid, Verify"</li>' +
               '</ol>' +
               '</div>';
    
    $('#payment-instructions').html(html);
}

/**
 * Show alert message
 */
function showAlert(type, message) {
    var alertClass = type === 'success' ? 'alert-success' : 
                     type === 'error' ? 'alert-danger' : 'alert-info';
    
    var html = '<div class="alert ' + alertClass + ' alert-dismissible fade show" role="alert">' +
               message +
               '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>' +
               '</div>';
    
    $('#payment-alerts').html(html);
    
    setTimeout(function() {
        $('.alert').fadeOut('slow', function() {
            $(this).remove();
        });
    }, 5000);
}