<?php
$slipNumber = $_GET['slip'] ?? 'SLIP-2025-00001';
$baseUrl = 'http://' . $_SERVER['HTTP_HOST'];
$verificationUrl = $baseUrl . '/application-verify/slip/' . urlencode($slipNumber);

echo "<h1>QR Code Debug</h1>";
echo "<p>Slip Number: " . htmlspecialchars($slipNumber) . "</p>";
echo "<p>Verification URL: <a href='" . htmlspecialchars($verificationUrl) . "'>" . htmlspecialchars($verificationUrl) . "</a></p>";
echo "<p>Test the URL: <a href='" . htmlspecialchars($verificationUrl) . "' target='_blank'>Click here to test</a></p>";

echo "<h2>QR Code Image:</h2>";
echo "<img src='/standalone_qr.php?slip=" . urlencode($slipNumber) . "' alt='QR Code'>";
