<?php
/**
 * Test Payment API Directly
 * Run this after logging in to test the payment initiation
 */

session_start();

echo "<h1>Test Payment API</h1>";

// Check if logged in
if (!isset($_SESSION['applicant_id'])) {
    echo "<p style='color:red'>❌ Not logged in. Please login first at <a href='/applicant/login'>/applicant/login</a></p>";
    exit;
}

echo "<p style='color:green'>✅ Logged in as: " . ($_SESSION['applicant_id'] ?? 'Unknown') . "</p>";

// Get CSRF token from session
$csrfToken = '';
if (isset($_SESSION['csrf_tokens']) && is_array($_SESSION['csrf_tokens'])) {
    $tokens = array_keys($_SESSION['csrf_tokens']);
    $csrfToken = end($tokens); // Get the most recent token
}

if (empty($csrfToken)) {
    // Generate a new token
    $csrfToken = bin2hex(random_bytes(32));
    $_SESSION['csrf_tokens'][$csrfToken] = time();
    $_SESSION['current_csrf_token'] = $csrfToken;
}

echo "<h2>Test Payment Initiation</h2>";
echo "<form id='testForm'>";
echo "<input type='hidden' id='csrf_token' value='" . htmlspecialchars($csrfToken) . "'>";
echo "<button type='button' onclick='testInitiate()' style='padding:10px 20px; background:#4CAF50; color:white; border:none; border-radius:4px; cursor:pointer;'>Test Generate RRR</button>";
echo "</form>";

echo "<div id='result' style='margin-top:20px; padding:15px; background:#f5f5f5; border-radius:4px; font-family:monospace;'></div>";

echo "<script>
function testInitiate() {
    var result = document.getElementById('result');
    result.innerHTML = 'Sending request...';
    
    fetch('/payment/initiate', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({
            csrf_token: document.getElementById('csrf_token').value
        })
    })
    .then(response => response.json())
    .then(data => {
        result.innerHTML = '<pre>' + JSON.stringify(data, null, 2) + '</pre>';
        if (data.success && data.rrr) {
            result.innerHTML += '<p style=\"color:green\">✅ RRR Generated: ' + data.rrr + '</p>';
            result.innerHTML += '<p><a href=\"https://remitademo.net/remita/ecomm/frame/handleCCD.action?rrr=' + data.rrr + '\" target=\"_blank\" style=\"background:#2196F3; color:white; padding:8px 16px; text-decoration:none; border-radius:4px; display:inline-block; margin-top:10px;\">Pay Now on Remita Demo</a></p>';
        }
    })
    .catch(error => {
        result.innerHTML = '<pre style=\"color:red\">Error: ' + error + '</pre>';
    });
}
</script>";
?>
