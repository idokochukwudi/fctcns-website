<?php
// test_with_stored_hash.php
$rrr = '350799553561';
$merchantId = '27768931';
$storedHash = '963e5603b4f1650261cb20d18a47544415085ba7980a920fac660e448e266d52c12388e2a4fc9e8bc448b56ae37a65ba28450c018d51d34e7280c2f6e9b47872';

echo "<h1>Testing with Stored Hash from Database</h1>";

$endpoint = "https://demo.remita.net/remita/exapp/api/v1/send/api/echannelsvc/$merchantId/$rrr/$storedHash/status.reg";

echo "<p>Endpoint: $endpoint</p>";

$ch = curl_init($endpoint);
curl_setopt_array($ch, [
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_HTTPHEADER => [
        'Authorization: remitaConsumerKey=' . $merchantId . ',remitaConsumerToken=' . $storedHash,
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