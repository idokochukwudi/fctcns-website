<?php
// test_correct_endpoint.php
$rrr = '350799553561';
$merchantId = '27768931';
$apiKey = 'Q1dHREVNTzEyMzR8Q1dHREVNTw==';

echo "<h1>Testing Correct Remita Endpoint</h1>";

$baseUrl = 'https://remitademo.net/remita/exapp/api/v1/send/api';
$hash = hash('sha512', $merchantId . $rrr . $apiKey);
$endpoint = $baseUrl . '/echannelsvc/' . $merchantId . '/' . $rrr . '/' . $hash . '/status.reg';

echo "<p>Endpoint: $endpoint</p>";

$ch = curl_init($endpoint);
curl_setopt_array($ch, [
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_HTTPHEADER => [
        'Authorization: remitaConsumerKey=' . $merchantId . ',remitaConsumerToken=' . $hash,
    ],
]);

$response = curl_exec($ch);
$info = curl_getinfo($ch);
curl_close($ch);

echo "<pre>";
echo "HTTP Code: " . $info['http_code'] . "\n";
echo "Response: " . htmlspecialchars($response) . "\n";
echo "</pre>";
?>