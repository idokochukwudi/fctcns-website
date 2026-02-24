<?php
/**
 * Final Remita Verification Test
 * Run: https://fctcns.edu.ng/test_remita_final.php?rrr=330799553560
 */

error_reporting(E_ALL);
ini_set('display_errors', 1);

$rrr = $_GET['rrr'] ?? '330799553560';
$cleanRrr = preg_replace('/[^0-9]/', '', $rrr);

echo "<!DOCTYPE html>
<html>
<head>
    <title>Final Remita Verification Test</title>
    <style>
        body { font-family: Arial; padding: 20px; }
        .success { background: #d4edda; color: #155724; padding: 10px; }
        .error { background: #f8d7da; color: #721c24; padding: 10px; }
        pre { background: #f4f4f4; padding: 10px; }
    </style>
</head>
<body>
    <h1>🔍 Final Remita Verification Test</h1>
    <p>Testing RRR: <strong>$rrr</strong></p>";

$merchantId = '27768931';
$apiKey = 'Q1dHREVNTzEyMzR8Q1dHREVNTw==';
$decodedKey = 'CWGDEMO1234|CWGDEMO'; // From your test output

// Try ALL possible hash combinations
$formats = [
    // Format 1: Original API key (what we've been trying)
    'Original API Key' => $merchantId . $cleanRrr . $apiKey,
    
    // Format 2: Decoded key
    'Decoded Key' => $merchantId . $cleanRrr . $decodedKey,
    
    // Format 3: Part before pipe
    'Before Pipe' => $merchantId . $cleanRrr . 'CWGDEMO1234',
    
    // Format 4: Part after pipe
    'After Pipe' => $merchantId . $cleanRrr . 'CWGDEMO',
    
    // Format 5: Just the numeric part
    'Numeric Only' => $merchantId . $cleanRrr . '1234',
    
    // Format 6: With serviceTypeId and decoded key
    'With ServiceTypeId' => $merchantId . '35126630' . $cleanRrr . $decodedKey,
    
    // Format 7: Different order (rrr + merchantId + apiKey)
    'Different Order' => $cleanRrr . $merchantId . $decodedKey,
    
    // Format 8: With pipe but no separator
    'With Pipe' => $merchantId . $cleanRrr . 'CWGDEMO1234|CWGDEMO',
];

echo "<table border='1' cellpadding='10'>";
echo "<tr><th>Format</th><th>Hash (first 20 chars)</th><th>Response</th></tr>";

foreach ($formats as $name => $hashString) {
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
    
    $color = ($status === '00' || $status === 'success') ? '#d4edda' : 
             ($status === '013' ? '#fff3cd' : '#f8d7da');
    
    echo "<tr style='background: $color'>";
    echo "<td>" . htmlspecialchars($name) . "</td>";
    echo "<td><code>" . substr($hash, 0, 20) . "...</code></td>";
    echo "<td>";
    echo "HTTP: $httpCode<br>";
    echo "Status: $status<br>";
    echo "Message: " . htmlspecialchars($message);
    echo "</td>";
    echo "</tr>";
}

echo "</table>";

// Try direct RRR status check (no hash in URL, only in header)
echo "<h2>Testing Hash in Header Only</h2>";

$endpoint2 = "https://demo.remita.net/remita/exapp/api/v1/send/api/echannelsvc/$merchantId/$cleanRrr/status.reg";
$hash2 = hash('sha512', $merchantId . $cleanRrr . $decodedKey);

$ch = curl_init($endpoint2);
curl_setopt_array($ch, [
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_TIMEOUT => 30,
    CURLOPT_SSL_VERIFYPEER => false,
    CURLOPT_SSL_VERIFYHOST => false,
    CURLOPT_HTTPHEADER => [
        'Content-Type: application/json',
        'Accept: application/json',
        'Authorization: remitaConsumerKey=' . $merchantId . ',remitaConsumerToken=' . $hash2,
    ],
]);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

echo "<pre>";
echo "Endpoint: $endpoint2\n";
echo "HTTP: $httpCode\n";
echo "Response: " . htmlspecialchars($response) . "\n";
echo "</pre>";

// Try with POST method
echo "<h2>Testing POST Method</h2>";

$endpoint3 = "https://demo.remita.net/remita/exapp/api/v1/send/api/echannelsvc/$merchantId/$cleanRrr/status.reg";
$hash3 = hash('sha512', $merchantId . $cleanRrr . $decodedKey);

$ch = curl_init($endpoint3);
curl_setopt_array($ch, [
    CURLOPT_POST => true,
    CURLOPT_POSTFIELDS => json_encode(['rrr' => $cleanRrr]),
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_TIMEOUT => 30,
    CURLOPT_SSL_VERIFYPEER => false,
    CURLOPT_SSL_VERIFYHOST => false,
    CURLOPT_HTTPHEADER => [
        'Content-Type: application/json',
        'Accept: application/json',
        'Authorization: remitaConsumerKey=' . $merchantId . ',remitaConsumerToken=' . $hash3,
    ],
]);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

echo "<pre>";
echo "Endpoint: $endpoint3 (POST)\n";
echo "HTTP: $httpCode\n";
echo "Response: " . htmlspecialchars($response) . "\n";
echo "</pre>";

echo "</body></html>";
?>