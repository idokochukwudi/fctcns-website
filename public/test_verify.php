<?php
// test_verify.php - place in public directory
require_once __DIR__ . '/../app/config/bootstrap.php';
require_once MODELS_PATH . '/application/RemitaModel.php';

$rrr = '330799553560'; // Use the RRR from your log
$remita = new RemitaModel();

echo "<h1>Testing Verification for RRR: $rrr</h1>";
echo "<pre>";

// Test different hash formats
$merchantId = '27768931';
$apiKey = 'Q1dHREVNTzEyMzR8Q1dHREVNTw==';
$decodedApiKey = base64_decode($apiKey);

$formats = [
    'merchantId + rrr + apiKey' => $merchantId . $rrr . $apiKey,
    'merchantId + rrr + decodedApiKey' => $merchantId . $rrr . $decodedApiKey,
    'merchantId + serviceTypeId + rrr + apiKey' => $merchantId . '35126630' . $rrr . $apiKey,
    'lowercase version' => strtolower($merchantId . $rrr . $apiKey),
];

foreach ($formats as $name => $hashString) {
    $hash = hash('sha512', $hashString);
    echo "\n=== $name ===\n";
    echo "Hash: $hash\n";
    
    $endpoint = "https://demo.remita.net/remita/exapp/api/v1/send/api/echannelsvc/$merchantId/$rrr/$hash/status.reg";
    echo "Endpoint: $endpoint\n";
    
    $ch = curl_init($endpoint);
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HTTPHEADER => [
            'Content-Type: application/json',
            'Authorization: remitaConsumerKey=' . $merchantId . ',remitaConsumerToken=' . $hash,
        ],
    ]);
    
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    echo "HTTP $httpCode\n";
    echo "Response: $response\n";
}

echo "</pre>";
?>