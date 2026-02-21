<?php
/**
 * Simple QR Code Generator using QR Server API
 */

$slipNumber = $_GET['slip'] ?? 'SLIP-2025-00001';
$slipNumber = preg_replace('/[^A-Za-z0-9\-]/', '', $slipNumber);

// Use a reliable QR code API
$verificationUrl = 'http://fctcns.edu.ng/test_verify.php?slip=' . urlencode($slipNumber);

// Use QR Server API (free, no API key required)
$qrApiUrl = 'https://api.qrserver.com/v1/create-qr-code/?size=300x300&data=' . urlencode($verificationUrl);

// Redirect to the QR server
header('Location: ' . $qrApiUrl);
exit;
