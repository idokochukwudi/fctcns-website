<?php
// force_check.php
$rrr = '350799553561';
$merchantId = '27768931';
$apiKey = 'Q1dHREVNTzEyMzR8Q1dHREVNTw==';

echo "<h1>Force Check RRR: $rrr</h1>";

// Try all possible hash formats
$formats = [
    'Original' => $merchantId . $rrr . $apiKey,
    'Decoded' => $merchantId . $rrr . 'CWGDEMO1234|CWGDEMO',
    'First Part' => $merchantId . $rrr . 'CWGDEMO1234',
    'Second Part' => $merchantId . $rrr . 'CWGDEMO',
    'No Pipe' => $merchantId . $rrr . 'CWGDEMO1234CWGDEMO',
];

foreach ($formats as $name => $hashString) {
    $hash = hash('sha512', $hashString);
    $url = "https://demo.remita.net/remita/exapp/api/v1/send/api/echannelsvc/$merchantId/$rrr/$hash/status.reg";
    
    echo "<h3>$name</h3>";
    echo "<p>URL: $url</p>";
    
    $ch = curl_init($url);
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
    echo "HTTP: " . $info['http_code'] . "\n";
    echo "Response: " . htmlspecialchars($response) . "\n";
    echo "</pre>";
}

// Also try the order status endpoint
echo "<h2>Order Status Endpoint</h2>";
$hash = hash('sha512', $merchantId . $rrr . $apiKey);
$url = "https://demo.remita.net/remita/exapp/api/v1/send/api/echannelsvc/$merchantId/$rrr/orderstatus.reg";

$ch = curl_init($url);
curl_setopt_array($ch, [
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_HTTPHEADER => [
        'Authorization: remitaConsumerKey=' . $merchantId . ',remitaConsumerToken=' . $hash,
    ],
]);

$response = curl_exec($ch);
curl_close($ch);

echo "<pre>";
echo "Response: " . htmlspecialchars($response) . "\n";
echo "</pre>";
?>