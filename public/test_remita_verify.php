<?php
/**
 * Standalone Remita Verification Test
 * Run this directly in your browser: https://fctcns.edu.ng/test_remita_verify.php?rrr=330799553560
 */

// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Get RRR from URL or use default
$rrr = $_GET['rrr'] ?? '330799553560';
$cleanRrr = preg_replace('/[^0-9]/', '', $rrr);

echo "<!DOCTYPE html>
<html>
<head>
    <title>Remita Verification Test</title>
    <style>
        body { font-family: monospace; padding: 20px; }
        .success { color: green; }
        .error { color: red; }
        pre { background: #f4f4f4; padding: 10px; border-radius: 5px; }
        table { border-collapse: collapse; width: 100%; }
        td, th { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
    </style>
</head>
<body>
    <h1>🔍 Remita Verification Test</h1>
    <p>Testing RRR: <strong>$rrr</strong> (cleaned: $cleanRrr)</p>";

// Configuration
$merchantId = '27768931';
$serviceTypeId = '35126630';
$apiKey = 'Q1dHREVNTzEyMzR8Q1dHREVNTw==';
$baseUrl = 'https://demo.remita.net/remita/exapp/api/v1/send/api';

// Try decoding the API key (it looks base64 encoded)
$decodedApiKey = base64_decode($apiKey);
$possibleApiKeys = [
    'original' => $apiKey,
    'decoded' => $decodedApiKey,
];

echo "<h2>Testing Different Hash Formats</h2>";
echo "<table>";
echo "<tr><th>Format</th><th>Hash</th><th>HTTP Code</th><th>Response</th></tr>";

$formats = [
    'merchantId + rrr + apiKey' => $merchantId . $cleanRrr . $apiKey,
    'merchantId + rrr + decodedApiKey' => $merchantId . $cleanRrr . $decodedApiKey,
    'merchantId + serviceTypeId + rrr + apiKey' => $merchantId . $serviceTypeId . $cleanRrr . $apiKey,
    'merchantId + serviceTypeId + rrr + decodedApiKey' => $merchantId . $serviceTypeId . $cleanRrr . $decodedApiKey,
    'lowercase (original key)' => strtolower($merchantId . $cleanRrr . $apiKey),
    'lowercase (decoded key)' => strtolower($merchantId . $cleanRrr . $decodedApiKey),
    'UPPERCASE (original key)' => strtoupper($merchantId . $cleanRrr . $apiKey),
    'UPPERCASE (decoded key)' => strtoupper($merchantId . $cleanRrr . $decodedApiKey),
];

foreach ($formats as $name => $hashString) {
    $hash = hash('sha512', $hashString);
    
    // Endpoint with hash in URL
    $endpoint = $baseUrl . '/echannelsvc/' . $merchantId . '/' . $cleanRrr . '/' . $hash . '/status.reg';
    
    // Initialize cURL
    $ch = curl_init($endpoint);
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_SSL_VERIFYHOST => false,
        CURLOPT_HTTPHEADER => [
            'Content-Type: application/json',
            'Accept: application/json',
            'Authorization: remitaConsumerKey=' . $merchantId . ',remitaConsumerToken=' . $hash,
        ],
    ]);
    
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $curlError = curl_error($ch);
    curl_close($ch);
    
    // Parse response
    $responseData = json_decode($response, true);
    $status = $responseData['status'] ?? 'unknown';
    $message = $responseData['message'] ?? '';
    
    $color = ($status === '00' || $status === 'success') ? 'success' : 
             ($status === '013' ? 'error' : '');
    
    echo "<tr class='$color'>";
    echo "<td>" . htmlspecialchars($name) . "</td>";
    echo "<td><small>" . substr($hash, 0, 20) . "...</small></td>";
    echo "<td>$httpCode</td>";
    echo "<td>";
    echo "Status: $status<br>";
    echo "Message: " . htmlspecialchars($message) . "<br>";
    if ($curlError) {
        echo "cURL Error: " . htmlspecialchars($curlError) . "<br>";
    }
    echo "</td>";
    echo "</tr>";
}

echo "</table>";

// Try alternative endpoint format
echo "<h2>Testing Alternative Endpoint Format</h2>";
echo "<table>";
echo "<tr><th>Format</th><th>Endpoint</th><th>HTTP Code</th><th>Response</th></tr>";

$alternativeEndpoints = [
    'Standard' => $baseUrl . '/echannelsvc/' . $merchantId . '/' . $cleanRrr . '/status.reg',
    'With Hash in Query' => $baseUrl . '/echannelsvc/' . $merchantId . '/' . $cleanRrr . '?hash=' . hash('sha512', $merchantId . $cleanRrr . $apiKey),
    'Order Status' => $baseUrl . '/echannelsvc/' . $merchantId . '/' . $cleanRrr . '/orderstatus.reg',
];

foreach ($alternativeEndpoints as $name => $endpoint) {
    $ch = curl_init($endpoint);
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_SSL_VERIFYHOST => false,
        CURLOPT_HTTPHEADER => [
            'Content-Type: application/json',
            'Accept: application/json',
            'Authorization: remitaConsumerKey=' . $merchantId . ',remitaConsumerToken=' . hash('sha512', $merchantId . $cleanRrr . $apiKey),
        ],
    ]);
    
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    $responseData = json_decode($response, true);
    $status = $responseData['status'] ?? 'unknown';
    
    echo "<tr>";
    echo "<td>" . htmlspecialchars($name) . "</td>";
    echo "<td><small>" . htmlspecialchars(substr($endpoint, 0, 50)) . "...</small></td>";
    echo "<td>$httpCode</td>";
    echo "<td>" . htmlspecialchars(substr($response, 0, 100)) . "...</td>";
    echo "</tr>";
}

echo "</table>";

// Show raw API key info
echo "<h2>API Key Information</h2>";
echo "<pre>";
echo "Original API Key: $apiKey\n";
echo "Decoded API Key: " . $decodedApiKey . "\n";
echo "Decoded length: " . strlen($decodedApiKey) . " characters\n";
echo "</pre>";

echo "</body></html>";
?>