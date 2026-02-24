<?php
echo "<h1>Environment Check</h1>";

// Check if .env file exists
$envFile = __DIR__ . '/../.env';
echo "<h2>.env file: " . (file_exists($envFile) ? '✅ EXISTS' : '❌ NOT FOUND') . "</h2>";

if (file_exists($envFile)) {
    echo "<pre>";
    $lines = file($envFile);
    foreach ($lines as $line) {
        if (strpos($line, 'REMITA') !== false) {
            echo htmlspecialchars($line);
        }
    }
    echo "</pre>";
}

// Check if environment variables are loaded
echo "<h2>\$_ENV values:</h2>";
echo "<pre>";
echo "REMITA_MERCHANT_ID: " . ($_ENV['REMITA_MERCHANT_ID'] ?? 'NOT SET') . "\n";
echo "REMITA_SERVICE_TYPE_ID: " . ($_ENV['REMITA_SERVICE_TYPE_ID'] ?? 'NOT SET') . "\n";
echo "REMITA_API_KEY: " . ($_ENV['REMITA_API_KEY'] ?? 'NOT SET') . "\n";
echo "REMITA_ENVIRONMENT: " . ($_ENV['REMITA_ENVIRONMENT'] ?? 'NOT SET') . "\n";
echo "</pre>";

// Test base64 decoding
$apiKey = $_ENV['REMITA_API_KEY'] ?? 'Q1dHREVNTzEyMzR8Q1dHREVNTw==';
$decoded = base64_decode($apiKey);
echo "<h2>API Key Test:</h2>";
echo "<pre>";
echo "Original: $apiKey\n";
echo "Decoded: $decoded\n";
echo "Length: " . strlen($decoded) . "\n";
echo "</pre>";
?>