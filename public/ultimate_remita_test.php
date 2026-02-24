<?php
/**
 * Ultimate Remita Hash Test
 * Run: https://fctcns.edu.ng/ultimate_remita_test.php?rrr=350799553561
 */

$rrr = $_GET['rrr'] ?? '350799553561';
$cleanRrr = preg_replace('/[^0-9]/', '', $rrr);

echo "<!DOCTYPE html>
<html>
<head>
    <title>Ultimate Remita Hash Test</title>
    <style>
        body { font-family: monospace; padding: 20px; background: #f5f5f5; }
        .success { background: #d4edda; color: #155724; padding: 15px; border-radius: 5px; }
        .error { background: #f8d7da; color: #721c24; padding: 15px; border-radius: 5px; }
        pre { background: #fff; padding: 15px; border-radius: 5px; border: 1px solid #ddd; overflow: auto; }
        table { width: 100%; border-collapse: collapse; background: white; }
        th, td { padding: 10px; text-align: left; border-bottom: 1px solid #ddd; }
        th { background: #6B4E9B; color: white; }
        tr:hover { background: #f5f5f5; }
    </style>
</head>
<body>
    <h1>🔍 Ultimate Remita Hash Test</h1>
    <p>Testing RRR: <strong>$rrr</strong> (cleaned: $cleanRrr)</p>";

$merchantId = '27768931';
$serviceTypeId = '35126630';
$apiKey = 'Q1dHREVNTzEyMzR8Q1dHREVNTw==';
$decodedKey = 'CWGDEMO1234|CWGDEMO';

// Try ALL possible combinations
$testCases = [
    // Original API key variations
    ['name' => 'Original API Key (as is)', 'key' => $apiKey],
    ['name' => 'Decoded API Key', 'key' => $decodedKey],
    ['name' => 'First part only (CWGDEMO1234)', 'key' => 'CWGDEMO1234'],
    ['name' => 'Second part only (CWGDEMO)', 'key' => 'CWGDEMO'],
    ['name' => 'URL Encoded Original', 'key' => urlencode($apiKey)],
    ['name' => 'URL Encoded Decoded', 'key' => urlencode($decodedKey)],
    ['name' => 'Base64 Encoded Decoded', 'key' => base64_encode($decodedKey)],
    ['name' => 'MD5 of Decoded', 'key' => md5($decodedKey)],
    ['name' => 'SHA1 of Decoded', 'key' => sha1($decodedKey)],
    ['name' => 'Without pipe (CWGDEMO1234CWGDEMO)', 'key' => 'CWGDEMO1234CWGDEMO'],
];

echo "<h2>Testing Different API Key Formats</h2>";
echo "<table>";
echo "<tr><th>Format</th><th>Hash</th><th>Response</th></tr>";

foreach ($testCases as $test) {
    $hashString = $merchantId . $cleanRrr . $test['key'];
    $hash = hash('sha512', $hashString);
    
    $endpoint = "https://demo.remita.net/remita/exapp/api/v1/send/api/echannelsvc/$merchantId/$cleanRrr/$hash/status.reg";
    
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
    curl_close($ch);
    
    $responseData = json_decode($response, true);
    $status = $responseData['status'] ?? 'unknown';
    $message = $responseData['message'] ?? '';
    
    $color = ($status === '00') ? '#d4edda' : (($status === '013') ? '#fff3cd' : '#f8d7da');
    
    echo "<tr style='background: $color'>";
    echo "<td>" . htmlspecialchars($test['name']) . "<br><small>Key: " . substr($test['key'], 0, 20) . "...</small></td>";
    echo "<td><small>" . substr($hash, 0, 30) . "...</small></td>";
    echo "<td>HTTP: $httpCode<br>Status: $status<br>Message: " . htmlspecialchars($message) . "</td>";
    echo "</tr>";
}

echo "</table>";

// Try different hash string orders
echo "<h2>Testing Different Hash String Orders</h2>";
echo "<table>";
echo "<tr><th>Order</th><th>Hash</th><th>Response</th></tr>";

$orders = [
    ['name' => 'merchantId + rrr + apiKey', 'string' => $merchantId . $cleanRrr . $apiKey],
    ['name' => 'rrr + merchantId + apiKey', 'string' => $cleanRrr . $merchantId . $apiKey],
    ['name' => 'apiKey + merchantId + rrr', 'string' => $apiKey . $merchantId . $cleanRrr],
    ['name' => 'merchantId + apiKey + rrr', 'string' => $merchantId . $apiKey . $cleanRrr],
    ['name' => 'With serviceTypeId', 'string' => $merchantId . $serviceTypeId . $cleanRrr . $apiKey],
    ['name' => 'With amount (2200)', 'string' => $merchantId . $cleanRrr . '2200' . $apiKey],
    ['name' => 'Lowercase all', 'string' => strtolower($merchantId . $cleanRrr . $apiKey)],
    ['name' => 'Uppercase all', 'string' => strtoupper($merchantId . $cleanRrr . $apiKey)],
];

foreach ($orders as $order) {
    $hash = hash('sha512', $order['string']);
    
    $endpoint = "https://demo.remita.net/remita/exapp/api/v1/send/api/echannelsvc/$merchantId/$cleanRrr/$hash/status.reg";
    
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
    curl_close($ch);
    
    $responseData = json_decode($response, true);
    $status = $responseData['status'] ?? 'unknown';
    
    echo "<tr>";
    echo "<td>" . htmlspecialchars($order['name']) . "</td>";
    echo "<td><small>" . substr($hash, 0, 30) . "...</small></td>";
    echo "<td>HTTP: $httpCode<br>Status: $status</td>";
    echo "</tr>";
}

echo "</table>";

// Try direct POST to RRR generation endpoint
echo "<h2>Testing POST to RRR Generation Endpoint</h2>";
$postEndpoint = "https://demo.remita.net/remita/exapp/api/v1/send/api/echannelsvc/merchant/api/paymentinit";
$postData = [
    'merchantId' => $merchantId,
    'serviceTypeId' => $serviceTypeId,
    'rrr' => $cleanRrr,
    'amount' => '2200',
    'orderId' => 'TEST' . time(),
];

$ch = curl_init($postEndpoint);
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

echo "<pre>";
echo "HTTP $httpCode\n";
echo "Response: " . htmlspecialchars($response) . "\n";
echo "</pre>";

echo "</body></html>";
?>