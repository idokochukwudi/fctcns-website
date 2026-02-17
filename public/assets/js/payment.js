/**
 * Payment handling JavaScript
 * Handles RRR generation and payment verification
 * FIXED: Proper error handling and CSRF token management
 */

$(document).ready(function() {
    // Handle Pay Now button click (supports both ID variations)
    $('#payNowBtn, #pay-now-btn').on('click', function(e) {
        e.preventDefault();
        initiatePayment();
    });
    
    // Handle Verify Payment button click (supports both ID variations)
    $('#verifyPaymentBtn, #verify-payment-btn').on('click', function(e) {
        e.preventDefault();
        var rrr = $(this).data('rrr') || sessionStorage.getItem('pending_rrr') || $('#rrr-input').val();
        verifyPayment(rrr);
    });
    
    // Handle manual RRR input verification
    $('#verify-rrr-btn').on('click', function(e) {
        e.preventDefault();
        var rrr = $('#rrr-input').val().trim();
        if (rrr) {
            verifyPayment(rrr);
        } else {
            showAlert('error', 'Please enter your RRR number');
        }
    });
});

/**
 * Initiate payment - generate RRR
 */
function initiatePayment() {
    var btn = $('#payNowBtn, #pay-now-btn').first();
    var originalText = btn.html();
    
    // Show loading state
    btn.html('<i class="fas fa-spinner fa-spin"></i> Generating RRR...');
    btn.prop('disabled', true);
    
    // Show payment status area if it exists
    if ($('#paymentStatus').length) {
        $('#paymentStatus').show();
        $('#paymentMessage').text('Generating RRR...');
    }
    
    // Get CSRF token from multiple possible sources
    var csrfToken = $('meta[name="csrf-token"]').attr('content') || 
                    $('input[name="csrf_token"]').val() || 
                    $('input[name="_token"]').val() || 
                    '';
    
    console.log('Initiating payment with CSRF token:', csrfToken ? 'Present' : 'Missing');
    
    $.ajax({
        url: '/payment/initiate',
        type: 'POST',
        contentType: 'application/json',
        data: JSON.stringify({
            csrf_token: csrfToken
        }),
        dataType: 'json',
        success: function(response) {
            console.log('Payment initiation response:', response);
            
            if (response.success || response.status === 'success') {
                var rrr = response.rrr || response.data?.rrr;
                
                if (rrr) {
                    // Store RRR in session storage for later use
                    sessionStorage.setItem('pending_rrr', rrr);
                    sessionStorage.setItem('payment_id', response.payment_id || '');
                    
                    // Show success message
                    showAlert('success', 'RRR generated successfully: ' + rrr);
                    
                    // Update UI with RRR
                    showRRR(rrr);
                    
                    // Show Remita payment link
                    showRemitaLink(rrr);
                } else {
                    showAlert('error', 'RRR not found in response');
                }
            } else {
                showAlert('error', response.message || 'Failed to generate RRR');
            }
            
            // Reset button
            resetButton(btn, originalText);
        },
        error: function(xhr, status, error) {
            console.error('Payment initiation error:', error);
            console.error('Status:', status);
            console.error('Response:', xhr.responseText);
            
            // Try to parse error response
            try {
                var response = JSON.parse(xhr.responseText);
                showAlert('error', response.message || 'Server error occurred');
            } catch(e) {
                if (xhr.status === 500) {
                    showAlert('error', 'Server error (500). Please check the error logs.');
                } else if (xhr.status === 404) {
                    showAlert('error', 'Payment endpoint not found. Please check your routes.');
                } else if (xhr.status === 403) {
                    showAlert('error', 'Access denied. Please login again.');
                } else {
                    showAlert('error', 'Connection error: ' + error);
                }
            }
            
            // Reset button
            resetButton(btn, originalText);
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
    
    var btn = $('#verifyPaymentBtn, #verify-payment-btn').first();
    var originalText = btn.html();
    
    // Show loading state
    btn.html('<i class="fas fa-spinner fa-spin"></i> Verifying...');
    btn.prop('disabled', true);
    
    // Get CSRF token
    var csrfToken = $('meta[name="csrf-token"]').attr('content') || 
                    $('input[name="csrf_token"]').val() || 
                    $('input[name="_token"]').val() || 
                    '';
    
    $.ajax({
        url: '/payment/verify',
        type: 'POST',
        data: { 
            rrr: rrr,
            csrf_token: csrfToken
        },
        dataType: 'json',
        success: function(response) {
            console.log('Payment verification response:', response);
            
            if (response.success || response.status === 'success') {
                showAlert('success', 'Payment verified successfully! Redirecting...');
                
                // Clear stored RRR
                sessionStorage.removeItem('pending_rrr');
                sessionStorage.removeItem('payment_id');
                
                // Redirect to exam slip
                setTimeout(function() {
                    window.location.href = response.redirect || '/apply/step/4';
                }, 2000);
                
            } else if (response.status === 'pending') {
                showAlert('info', response.message || 'Payment is still processing. Please wait...');
                
                // Auto-retry after 10 seconds
                setTimeout(function() {
                    verifyPayment(rrr);
                }, 10000);
                
                resetButton(btn, originalText);
                
            } else {
                showAlert('error', response.message || 'Payment verification failed');
                resetButton(btn, originalText);
            }
        },
        error: function(xhr, status, error) {
            console.error('Payment verification error:', error);
            console.error('Response:', xhr.responseText);
            
            try {
                var response = JSON.parse(xhr.responseText);
                showAlert('error', response.message || 'Verification failed');
            } catch(e) {
                showAlert('error', 'Verification error: ' + error);
            }
            
            resetButton(btn, originalText);
        }
    });
}

/**
 * Check payment status without verification (for status page)
 */
function checkPaymentStatus(rrr) {
    if (!rrr) return;
    
    $.ajax({
        url: '/payment/check-status',
        type: 'GET',
        data: { rrr: rrr },
        dataType: 'json',
        success: function(response) {
            console.log('Payment status:', response);
            
            if (response.status === 'success') {
                $('#payment-status-badge').removeClass('bg-warning bg-danger')
                    .addClass('bg-success')
                    .text('Completed');
            } else if (response.status === 'pending') {
                $('#payment-status-badge').removeClass('bg-success bg-danger')
                    .addClass('bg-warning')
                    .text('Pending');
                
                // Check again after 30 seconds
                setTimeout(function() {
                    checkPaymentStatus(rrr);
                }, 30000);
            } else {
                $('#payment-status-badge').removeClass('bg-success bg-warning')
                    .addClass('bg-danger')
                    .text('Failed');
            }
        },
        error: function() {
            console.log('Failed to check payment status');
        }
    });
}

/**
 * Show RRR and payment instructions
 */
function showRRR(rrr) {
    // Update RRR display elements
    $('#paymentRRR, .rrr-display').text(rrr);
    
    // Create or update payment instructions
    var html = '<div class="alert alert-info mt-3 payment-instructions">' +
               '<h5><i class="fas fa-info-circle"></i> Payment Details</h5>' +
               '<p><strong>RRR:</strong> <span class="text-primary fw-bold">' + rrr + '</span></p>' +
               '<p><strong>Instructions:</strong></p>' +
               '<ol>' +
               '<li>Click the "Proceed to Payment" button below</li>' +
               '<li>Complete your payment on the Remita payment page</li>' +
               '<li>After payment, return here and click "I\'ve Paid, Verify"</li>' +
               '</ol>' +
               '</div>';
    
    if ($('#payment-instructions').length) {
        $('#payment-instructions').html(html);
    } else {
        $('.payment-section').prepend(html);
    }
    
    // Show verify button
    $('#verify-payment-btn, #verifyPaymentBtn').show();
}

/**
 * Show Remita payment link
 */
function showRemitaLink(rrr) {
    var remitaUrl = 'https://remitademo.net/remita/ecomm/frame/handleCCD.action?rrr=' + rrr;
    
    var html = '<div class="alert alert-warning mt-2">' +
               '<p><i class="fas fa-external-link-alt"></i> <strong>Proceed to Payment:</strong></p>' +
               '<a href="' + remitaUrl + '" target="_blank" class="btn btn-primary">' +
               '<i class="fas fa-credit-card"></i> Pay Now on Remita</a>' +
               '<p class="mt-2 small text-muted">After payment, click the "Verify Payment" button below.</p>' +
               '</div>';
    
    if ($('#remita-link').length) {
        $('#remita-link').html(html);
    } else {
        $('#payment-instructions, .payment-instructions').after(html);
    }
    
    // Optional: Open automatically with confirmation
    if (confirm('RRR generated: ' + rrr + '\n\nClick OK to proceed to Remita payment page.')) {
        window.open(remitaUrl, '_blank');
    }
}

/**
 * Reset button to original state
 */
function resetButton(btn, originalText) {
    if (btn && btn.length) {
        btn.html(originalText);
        btn.prop('disabled', false);
    }
    
    // Hide payment status after delay
    if ($('#paymentStatus').length) {
        setTimeout(function() {
            $('#paymentStatus').fadeOut();
        }, 5000);
    }
}

/**
 * Show alert message
 */
function showAlert(type, message) {
    var alertClass = type === 'success' ? 'alert-success' : 
                     type === 'error' ? 'alert-danger' : 
                     type === 'info' ? 'alert-info' : 'alert-warning';
    
    var icon = type === 'success' ? 'fa-check-circle' : 
               type === 'error' ? 'fa-exclamation-circle' : 
               type === 'info' ? 'fa-info-circle' : 'fa-exclamation-triangle';
    
    var html = '<div class="alert ' + alertClass + ' alert-dismissible fade show" role="alert">' +
               '<i class="fas ' + icon + ' me-2"></i>' +
               message +
               '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>' +
               '</div>';
    
    // Check multiple possible alert container IDs
    if ($('#payment-alerts').length) {
        $('#payment-alerts').html(html);
    } else if ($('#alertContainer').length) {
        $('#alertContainer').html(html);
    } else if ($('#paymentStatus').length) {
        $('#paymentStatus').show();
        $('#paymentMessage').html(message);
        $('#paymentStatus .alert').removeClass('alert-info alert-success alert-danger')
            .addClass(alertClass);
    } else {
        // Create alert container if none exists
        var containerHtml = '<div id="payment-alerts" class="mt-3"></div>';
        if ($('.payment-section').length) {
            $('.payment-section').prepend(containerHtml);
            $('#payment-alerts').html(html);
        } else {
            $('.container:first').prepend(containerHtml);
            $('#payment-alerts').html(html);
        }
    }
    
    // Auto-dismiss after 5 seconds (except for errors which stay longer)
    var timeout = type === 'error' ? 8000 : 5000;
    setTimeout(function() {
        $('.alert').fadeOut('slow', function() {
            $(this).remove();
        });
    }, timeout);
}

/**
 * Format currency
 */
function formatCurrency(amount) {
    return '₦' + parseFloat(amount).toLocaleString('en-NG', {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2
    });
}

/**
 * Copy RRR to clipboard
 */
function copyRRR() {
    var rrr = $('#paymentRRR').text() || sessionStorage.getItem('pending_rrr');
    
    if (!rrr) {
        showAlert('error', 'No RRR to copy');
        return;
    }
    
    // Create temporary input
    var tempInput = $('<input>');
    $('body').append(tempInput);
    tempInput.val(rrr).select();
    document.execCommand('copy');
    tempInput.remove();
    
    showAlert('success', 'RRR copied to clipboard!');
}

// Export functions for use in HTML onclick attributes
window.initiatePayment = initiatePayment;
window.verifyPayment = verifyPayment;
window.checkPaymentStatus = checkPaymentStatus;
window.copyRRR = copyRRR;