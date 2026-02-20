console.log('Payment.js loaded - MINIMAL TEST VERSION');

$(document).ready(function() {
    console.log('Document ready - MINIMAL TEST');
    
    $('#generateRRRBtn').on('click', function(e) {
        e.preventDefault();
        alert('Button clicked! Minimal test working!');
    });
});
