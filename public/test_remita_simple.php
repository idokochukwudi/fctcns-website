<?php
/**
 * Ultra-simple Remita Verification Test
 * Run: https://fctcns.edu.ng/test_remita_simple.php?rrr=330799553560
 */

$rrr = $_GET['rrr'] ?? '330799553560';
$cleanRrr = preg_replace('/[^0-9]/', '', $rrr);

echo "<!DOCTYPE html>
<html>
<head>
    <title>Simple Remita Test</title>
    <style>
        body { font-family: Arial; padding: 20px; }
        pre { background: #f4f4f4; padding: 10px; }
    </style>
</head>
<body>
    <h1>Simple Remita Verification Test</h1>
    <p>RRR: $rrr (cleaned: $cleanRrr)</p>";

// Configuration
$merchantId = '27768931';
$apiKey = 'Q1dHREVNTzEyMzR8Q1dHREVNTw==';

// Try the simplest possible approach - no hash in URL, only in header
$endpoints = [
    'Standard Status Endpoint' => "https://demo.remita.net/remita/exapp/api/v1/send/api/echannelsvc/$merchantId/$cleanRrr/status.reg",
    'Order Status Endpoint' => "https://demo.remita.net/remita/exapp/api/v1/send/api/echannelsvc/$merchantId/$cleanRrr/orderstatus.reg",
    'Direct Check' => "https://demo.remita.net/remita/exapp/api/v1/send/api/echannelsvc/$merchantId/$cleanRrr",
    'Payment Status' => "https://demo.remita.net/remita/exapp/api/v1/send/api/echannelsvc/$merchantId/$cleanRrr/payment/status",
];

foreach ($endpoints as $name => $endpoint) {
    echo "<h3>$name</h3>";
    
    // Try with different authorization methods
    $authMethods = [
        'Basic Auth' => base64_encode("$merchantId:$apiKey"),
        'Bearer Token' => $apiKey,
        'No Auth' => '',
    ];
    
    foreach ($authMethods as $authName => $authValue) {
        $headers = ['Content-Type: application/json', 'Accept: application/json'];
        
        if ($authName === 'Basic Auth') {
            $headers[] = "Authorization: Basic $authValue";
        } elseif ($authName === 'Bearer Token') {
            $headers[] = "Authorization: Bearer $authValue";
        }
        
        $ch = curl_init($endpoint);
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_SSL_VERIFYHOST => false,
            CURLOPT_HTTPHEADER => $headers,
        ]);
        
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        
        echo "<p><strong>$authName</strong> - HTTP $httpCode</p>";
        echo "<pre>" . htmlspecialchars(substr($response, 0, 500)) . "</pre>";
    }
    
    echo "<hr>";
}

// Try the RRR generation endpoint format but with POST
echo "<h3>POST to RRR Generation Endpoint (as status check)</h3>";
$endpoint = "https://demo.remita.net/remita/exapp/api/v1/send/api/echannelsvc/merchant/api/paymentinit";

$postData = [
    'merchantId' => $merchantId,
    'rrr' => $cleanRrr,
    'requestId' => time(),
];

$ch = curl_init($endpoint);
curl_setopt_array($ch, [
    CURLOPT_POST => true,
    CURLOPT_POSTFIELDS => json_encode($postData),
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_HTTPHEADER => [
        'Content-Type: application/json',
        'Accept: application/json',
    ],
]);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

echo "<p>HTTP $httpCode</p>";
echo "<pre>" . htmlspecialchars($response) . "</pre>";

echo "</body></html>";
?>