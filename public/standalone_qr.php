<?php
/**
 * Standalone QR Code Generator
 * Access: http://fctcns.edu.ng/standalone_qr.php?slip=SLIP-2025-00001
 */

$slipNumber = $_GET['slip'] ?? 'SLIP-2025-00001';
$slipNumber = preg_replace('/[^A-Za-z0-9\-]/', '', $slipNumber);

$baseUrl = 'http://' . $_SERVER['HTTP_HOST'];
$verificationUrl = $baseUrl . '/application-verify/slip/' . urlencode($slipNumber);

header('Content-Type: image/png');

// Try Google Charts API
$googleUrl = 'https://chart.googleapis.com/chart?chs=300x300&cht=qr&chl=' . urlencode($verificationUrl) . '&choe=UTF-8';

if (function_exists('curl_init')) {
    $ch = curl_init($googleUrl);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    $imageData = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    if ($httpCode === 200 && $imageData && strlen($imageData) > 100) {
        echo $imageData;
        exit;
    }
}

// Fallback to GD
if (extension_loaded('gd')) {
    $size = 300;
    $im = imagecreate($size, $size);
    
    $white = imagecolorallocate($im, 255, 255, 255);
    $black = imagecolorallocate($im, 0, 0, 0);
    $blue = imagecolorallocate($im, 107, 78, 155);
    
    imagefill($im, 0, 0, $white);
    imagerectangle($im, 0, 0, $size-1, $size-1, $blue);
    
    // Generate pattern
    $hash = md5($verificationUrl);
    for ($i = 0; $i < 20; $i++) {
        for ($j = 0; $j < 20; $j++) {
            if (hexdec($hash[($i*20+$j) % 32]) % 2 == 0) {
                imagefilledrectangle($im, $i*15, $j*15, $i*15+12, $j*15+12, $black);
            }
        }
    }
    
    imagepng($im);
    imagedestroy($im);
    exit;
}

echo "QR generation failed";
