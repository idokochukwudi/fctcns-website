/**
 * Payment handling JavaScript
 * Handles RRR generation and payment verification
 * FIXED: Button IDs to match HTML (generateRRRBtn instead of payNowBtn)
 * FIXED: Added proper error handling for missing elements
 * FIXED: Remita payment button display after RRR generation
 * FIXED: Verify button visibility
 * FIXED: CopyRRR function now accepts parameter for pending payment
 */

$(document).ready(function() {
    console.log('Payment.js loaded - Document ready');
    
    // Handle Generate RRR button click - FIXED: Use correct ID from HTML
    $('#generateRRRBtn').on('click', function(e) {
        e.preventDefault();
        console.log('Generate RRR button clicked');
        initiatePayment();
    });
    
    // Handle Verify Payment button click - Keep existing handlers
    $('#verifyPaymentBtn, #verify-payment-btn, #checkStatusBtn').on('click', function(e) {
        e.preventDefault();
        console.log('Verify button clicked');
        var rrr = $(this).data('rrr') || sessionStorage.getItem('pending_rrr') || $('#generatedRRR').text() || $('#rrr-input').val();
        if (rrr) {
            verifyPayment(rrr);
        } else {
            showAlert('No RRR found. Please generate RRR first.', 'danger');
        }
    });
    
    // Handle manual RRR input verification
    $('#verify-rrr-btn').on('click', function(e) {
        e.preventDefault();
        var rrr = $('#rrr-input').val().trim();
        if (rrr) {
            verifyPayment(rrr);
        } else {
            showAlert('Please enter your RRR number', 'warning');
        }
    });
    
    // Handle copy RRR button - FIXED: Use correct button ID from HTML
    $('#copyRRRBtn, #copy-rrr-btn').on('click', function(e) {
        e.preventDefault();
        copyRRR();
    });
    
    // Check if we have a pending RRR on page load
    var pendingRRR = sessionStorage.getItem('pending_rrr');
    if (pendingRRR) {
        console.log('Found pending RRR in session:', pendingRRR);
        showRRR(pendingRRR);
        
        // Show verify button
        $('#verifyPaymentBtn, #verify-payment-btn, #checkStatusBtn').show();
    }
    
    // Log available buttons for debugging
    console.log('Generate RRR button exists:', $('#generateRRRBtn').length > 0);
    console.log('Verify button exists:', $('#verifyPaymentBtn').length > 0);
    console.log('Check status button exists:', $('#checkStatusBtn').length > 0);
});

/**
 * Get CSRF token from multiple sources
 * This function tries various places where CSRF token might be stored
 */
function getCsrfToken() {
    var token = '';
    
    // Try hidden input fields first (most reliable for this page)
    token = $('input[name="csrf_token"]').val();
    if (token) {
        console.log('CSRF token found in input[name="csrf_token"]');
        return token;
    }
    
    // Try meta tag (backup)
    token = $('meta[name="csrf-token"]').attr('content');
    if (token) {
        console.log('CSRF token found in meta tag');
        return token;
    }
    
    // Try alternative field name
    token = $('input[name="_token"]').val();
    if (token) {
        console.log('CSRF token found in input[name="_token"]');
        return token;
    }
    
    // Try any input with csrf in the name
    token = $('input[name*="csrf"]').first().val();
    if (token) {
        console.log('CSRF token found in input with csrf in name');
        return token;
    }
    
    // Try to get from cookie (if using cookie-based CSRF)
    token = getCookie('XSRF-TOKEN');
    if (token) {
        console.log('CSRF token found in cookie');
        return token;
    }
    
    console.warn('No CSRF token found in any location');
    return '';
}

/**
 * Helper function to get cookie value
 */
function getCookie(name) {
    var value = "; " + document.cookie;
    var parts = value.split("; " + name + "=");
    if (parts.length == 2) return parts.pop().split(";").shift();
    return null;
}

/**
 * Initiate payment - generate RRR
 * FIXED: Use correct button ID (generateRRRBtn)
 */
function initiatePayment() {
    console.log('initiatePayment() called');
    
    // Get button - FIXED: Use correct ID
    var btn = $('#generateRRRBtn');
    if (!btn.length) {
        console.error('Generate RRR button not found');
        showAlert('Technical error: Button not found. Please refresh.', 'danger');
        return;
    }
    
    var originalText = btn.html();
    
    // Show loading state
    btn.html('<i class="fas fa-spinner fa-spin"></i> Generating RRR...');
    btn.prop('disabled', true);
    
    // Show payment status area if it exists
    if ($('#paymentStatus').length) {
        $('#paymentStatus').show();
        $('#paymentMessage').text('Generating RRR...');
        $('#paymentSpinner').show();
    }
    
    // Get CSRF token - prioritize hidden input
    var csrfToken = $('input[name="csrf_token"]').val();
    
    // Fallback to meta tag if hidden input not found
    if (!csrfToken) {
        csrfToken = $('meta[name="csrf-token"]').attr('content');
    }
    
    // Fallback to our comprehensive function
    if (!csrfToken) {
        csrfToken = getCsrfToken();
    }
    
    console.log('CSRF token status:', csrfToken ? 'Found ✓' : 'Missing ✗');
    
    if (!csrfToken) {
        showAlert('Security token missing. Please refresh the page and try again.', 'danger');
        resetButton(btn, originalText);
        return;
    }
    
    // Prepare request data
    var requestData = {
        csrf_token: csrfToken
    };
    
    console.log('Sending payment initiation request to /payment/initiate...');
    
    $.ajax({
        url: '/payment/initiate',
        type: 'POST',
        contentType: 'application/json',
        data: JSON.stringify(requestData),
        dataType: 'json',
        timeout: 30000,
        success: function(response) {
            console.log('Payment initiation response:', response);
            
            if (response.success) {
                var rrr = response.rrr || response.data?.rrr || response.reference;
                
                if (rrr) {
                    // Store RRR in session storage for later use
                    sessionStorage.setItem('pending_rrr', rrr);
                    sessionStorage.setItem('payment_id', response.payment_id || '');
                    
                    // Show success message
                    showAlert('RRR generated successfully: ' + rrr, 'success');
                    
                    // Update UI with RRR
                    showRRR(rrr);
                    
                    // Show Remita payment link
                    showRemitaLink(rrr);
                    
                    // Show verify button
                    $('#verifyPaymentBtn, #verify-payment-btn, #checkStatusBtn').show();
                    
                    // Hide payment status spinner
                    if ($('#paymentSpinner').length) {
                        $('#paymentSpinner').hide();
                    }
                    $('#paymentMessage').text('RRR Generated Successfully!');
                } else {
                    console.error('RRR not found in response:', response);
                    showAlert('RRR not found in server response', 'danger');
                }
            } else {
                showAlert(response.message || 'Failed to generate RRR', 'danger');
            }
            
            // Reset button
            resetButton(btn, originalText);
        },
        error: function(xhr, status, error) {
            console.error('=== PAYMENT INITIATION ERROR ===');
            console.error('Status:', status);
            console.error('Error:', error);
            console.error('Response Text:', xhr.responseText);
            
            // Try to parse error response
            var errorMessage = 'An error occurred. Please try again.';
            
            try {
                if (xhr.responseText) {
                    var response = JSON.parse(xhr.responseText);
                    errorMessage = response.message || response.error || errorMessage;
                }
            } catch(e) {
                if (xhr.status === 500) {
                    errorMessage = 'Server error (500). Please try again later.';
                } else if (xhr.status === 404) {
                    errorMessage = 'Payment endpoint not found. Please contact support.';
                } else if (xhr.status === 403) {
                    errorMessage = 'Access denied. Please login again.';
                } else if (xhr.status === 419) {
                    errorMessage = 'Session expired. Please refresh the page.';
                } else if (status === 'timeout') {
                    errorMessage = 'Request timed out. Please try again.';
                }
            }
            
            showAlert(errorMessage, 'danger');
            resetButton(btn, originalText);
            
            // Hide payment status spinner
            if ($('#paymentSpinner').length) {
                $('#paymentSpinner').hide();
            }
        }
    });
}

/**
 * Verify payment status
 */
function verifyPayment(rrr) {
    console.log('verifyPayment() called with RRR:', rrr);
    
    if (!rrr) {
        rrr = sessionStorage.getItem('pending_rrr') || $('#generatedRRR').text();
        if (!rrr) {
            showAlert('No pending payment found. Please generate RRR first.', 'danger');
            return;
        }
    }
    
    // Get button
    var btn = $('#verifyPaymentBtn, #verify-payment-btn, #checkStatusBtn').first();
    var originalText = btn.html();
    
    // Show loading state
    btn.html('<i class="fas fa-spinner fa-spin"></i> Verifying...');
    btn.prop('disabled', true);
    
    // Show payment status
    if ($('#paymentStatus').length) {
        $('#paymentStatus').show();
        $('#paymentMessage').text('Verifying payment...');
        $('#paymentSpinner').show();
    }
    
    // Get CSRF token
    var csrfToken = $('input[name="csrf_token"]').val() || $('meta[name="csrf-token"]').attr('content') || getCsrfToken();
    
    if (!csrfToken) {
        showAlert('Security token missing. Please refresh the page.', 'danger');
        resetButton(btn, originalText);
        return;
    }
    
    $.ajax({
        url: '/payment/verify',
        type: 'POST',
        contentType: 'application/json',
        data: JSON.stringify({ 
            rrr: rrr,
            csrf_token: csrfToken
        }),
        dataType: 'json',
        timeout: 30000,
        success: function(response) {
            console.log('Payment verification response:', response);
            
            if ($('#paymentSpinner').length) {
                $('#paymentSpinner').hide();
            }
            
            if (response.success) {
                $('#paymentMessage').text('Payment Verified!');
                showAlert('Payment verified successfully! Redirecting...', 'success');
                
                // Clear stored RRR
                sessionStorage.removeItem('pending_rrr');
                sessionStorage.removeItem('payment_id');
                
                // Hide verify button
                $('#verifyPaymentBtn, #verify-payment-btn, #checkStatusBtn').hide();
                
                // Redirect to exam slip
                setTimeout(function() {
                    window.location.href = response.redirect || '/apply/step/4';
                }, 2000);
                
            } else {
                $('#paymentMessage').text('Verification Failed');
                showAlert(response.message || 'Payment verification failed', 'danger');
                resetButton(btn, originalText);
            }
        },
        error: function(xhr, status, error) {
            console.error('Payment verification error:', error);
            
            if ($('#paymentSpinner').length) {
                $('#paymentSpinner').hide();
            }
            $('#paymentMessage').text('Verification Error');
            
            try {
                var response = JSON.parse(xhr.responseText);
                showAlert(response.message || 'Verification failed', 'danger');
            } catch(e) {
                if (xhr.status === 419) {
                    showAlert('Session expired. Please refresh the page.', 'danger');
                } else {
                    showAlert('Verification error. Please try again.', 'danger');
                }
            }
            
            resetButton(btn, originalText);
        }
    });
}

/**
 * Check payment status without verification
 */
function checkPaymentStatus(rrr) {
    if (!rrr) {
        rrr = sessionStorage.getItem('pending_rrr') || $('#generatedRRR').text();
        if (!rrr) return;
    }
    
    console.log('Checking payment status for RRR:', rrr);
    
    $.ajax({
        url: '/payment/check-status',
        type: 'GET',
        data: { rrr: rrr },
        dataType: 'json',
        success: function(response) {
            console.log('Payment status response:', response);
            
            if (response.success && response.status === 'success') {
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
            $('#payment-status-badge').removeClass('bg-success bg-warning')
                .addClass('bg-secondary')
                .text('Unknown');
        }
    });
}

/**
 * Show RRR and payment instructions - FIXED: Ensures verify button appears
 */
function showRRR(rrr) {
    console.log('Showing RRR:', rrr);
    
    // Update RRR display elements
    $('#generatedRRR').text(rrr);
    $('#paymentRRR, .rrr-display').text(rrr).show();
    $('#rrrDisplayArea').show();
    
    // Show verify button
    $('#verifyPaymentBtn, #verify-payment-btn, #checkStatusBtn').show();
    
    // Make sure the verify button is visible
    $('#verifyPaymentBtn').css('display', 'block');
}

/**
 * Show Remita payment link - FIXED: Properly displays the payment button
 */
function showRemitaLink(rrr) {
    var remitaUrl = 'https://remitademo.net/remita/ecomm/frame/handleCCD.action?rrr=' + rrr;
    
    var linkHtml = '<div class="alert alert-warning mt-3 remita-link">' +
               '<h5><i class="fas fa-external-link-alt"></i> Proceed to Payment</h5>' +
               '<p class="mb-3">Click the button below to complete your payment on Remita secure platform:</p>' +
               '<a href="' + remitaUrl + '" target="_blank" class="btn btn--warning btn--lg w-100">' +
               '<i class="fas fa-credit-card me-2"></i> Pay Now on Remita</a>' +
               '<p class="mt-3 small text-muted">After payment, return here and click "I\'ve Paid, Verify"</p>' +
               '</div>';
    
    // Remove any existing Remita links to prevent duplicates
    $('.remita-link').remove();
    
    // Try multiple possible locations for the link
    if ($('#remitaLink').length) {
        $('#remitaLink').html(linkHtml).show();
    } else if ($('#paymentStatus').length) {
        // Append to payment status area
        $('#paymentStatus').after(linkHtml);
    } else if ($('.action-buttons').length) {
        // Insert before action buttons
        $('.action-buttons').before(linkHtml);
    } else if ($('#rrrDisplayArea').length) {
        // Insert after RRR display area
        $('#rrrDisplayArea').after(linkHtml);
    } else {
        // Last resort - append to card body
        $('.card-body').append(linkHtml);
    }
    
    // Open automatically with confirmation
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
function showAlert(message, type) {
    console.log('Alert - Type:', type, 'Message:', message);
    
    var alertClass = type === 'success' ? 'alert-success' : 
                     type === 'danger' ? 'alert-danger' : 
                     type === 'info' ? 'alert-info' : 'alert-warning';
    
    var icon = type === 'success' ? 'fa-check-circle' : 
               type === 'danger' ? 'fa-exclamation-circle' : 
               type === 'info' ? 'fa-info-circle' : 'fa-exclamation-triangle';
    
    var alertHtml = '<div class="alert ' + alertClass + ' alert-dismissible fade show" role="alert">' +
               '<i class="fas ' + icon + ' me-2"></i>' +
               message +
               '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>' +
               '</div>';
    
    // Check multiple possible alert container IDs
    if ($('#payment-alerts').length) {
        $('#payment-alerts').html(alertHtml);
    } else if ($('#alertContainer').length) {
        $('#alertContainer').html(alertHtml);
    } else if ($('#paymentStatus').length) {
        $('#paymentStatus').show();
        $('#paymentMessage').html(message);
        $('#paymentStatus .alert').remove();
        $('#paymentStatus').prepend(alertHtml);
    } else {
        // Create alert container if none exists
        var containerHtml = '<div id="payment-alerts" class="mt-3"></div>';
        if ($('.payment-section').length) {
            $('.payment-section').prepend(containerHtml);
            $('#payment-alerts').html(alertHtml);
        } else {
            $('.container:first').prepend(containerHtml);
            $('#payment-alerts').html(alertHtml);
        }
    }
    
    // Auto-dismiss after 5 seconds (except for errors which stay longer)
    var timeout = type === 'danger' ? 8000 : 5000;
    setTimeout(function() {
        $('.alert').fadeOut('slow', function() {
            $(this).remove();
        });
    }, timeout);
}

/**
 * Copy RRR to clipboard - FIXED: Accepts parameter for direct RRR
 */
function copyRRR(rrr = null) {
    if (!rrr) {
        rrr = $('#generatedRRR').text() || sessionStorage.getItem('pending_rrr');
    }
    
    if (!rrr) {
        showAlert('No RRR to copy', 'warning');
        return;
    }
    
    // Modern clipboard API
    if (navigator.clipboard && navigator.clipboard.writeText) {
        navigator.clipboard.writeText(rrr).then(function() {
            showAlert('RRR copied to clipboard!', 'success');
        }).catch(function() {
            // Fallback to older method
            fallbackCopy(rrr);
        });
    } else {
        // Fallback for older browsers
        fallbackCopy(rrr);
    }
}

/**
 * Fallback copy method for older browsers
 */
function fallbackCopy(text) {
    var tempInput = $('<input>');
    $('body').append(tempInput);
    tempInput.val(text).select();
    document.execCommand('copy');
    tempInput.remove();
    showAlert('RRR copied to clipboard!', 'success');
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

// Export functions for use in HTML onclick attributes
window.initiatePayment = initiatePayment;
window.verifyPayment = verifyPayment;
window.checkPaymentStatus = checkPaymentStatus;
window.copyRRR = copyRRR;
window.getCsrfToken = getCsrfToken;