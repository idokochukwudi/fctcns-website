<?php
/**
 * Direct QR Code Test - No JavaScript, pure PHP
 * Access: http://yourdomain.com/test_qr_direct.php
 */

// Define paths
define('ROOT_PATH', __DIR__);
define('APP_PATH', ROOT_PATH . '/app');
define('MODELS_PATH', APP_PATH . '/models');
define('CONTROLLERS_PATH', APP_PATH . '/controllers');
define('BASE_URL', 'http://' . $_SERVER['HTTP_HOST']);

// Load the controller
require_once CONTROLLERS_PATH . '/ApplicationVerificationController.php';

$slipNumber = $_GET['slip'] ?? 'SLIP-2025-00001';
?>
<!DOCTYPE html>
<html>
<head>
    <title>Direct QR Test</title>
    <style>
        body { font-family: Arial; padding: 20px; background: #f0f0f0; }
        .test-box { background: white; padding: 20px; border-radius: 10px; max-width: 600px; margin: 0 auto; }
        .qr-image { margin: 20px 0; text-align: center; }
        img { border: 2px solid #6B4E9B; padding: 10px; background: white; }
        .success { color: green; }
        .error { color: red; }
        pre { background: #f5f5f5; padding: 10px; border-radius: 5px; }
    </style>
</head>
<body>
    <div class="test-box">
        <h1>Direct QR Code Test</h1>
        
        <?php
        try {
            echo "<h3>1. Loading Controller...</h3>";
            $controller = new ApplicationVerificationController();
            echo "<p class='success'>✅ Controller loaded successfully</p>";
            
            echo "<h3>2. Testing QR Generation Method...</h3>";
            if (method_exists($controller, 'generateQR')) {
                echo "<p class='success'>✅ generateQR method exists</p>";
            } else {
                echo "<p class='error'>❌ generateQR method not found</p>";
            }
            
            echo "<h3>3. Testing Direct QR Generation...</h3>";
            
            // Instead of calling generateQR (which outputs headers), we'll test the fallback method
            if (method_exists($controller, 'generateSimpleGDQR')) {
                echo "<p>Testing GD fallback...</p>";
                
                // Use reflection to access private method
                $reflection = new ReflectionClass($controller);
                $method = $reflection->getMethod('generateSimpleGDQR');
                $method->setAccessible(true);
                
                // Start output buffering
                ob_start();
                $method->invoke($controller, BASE_URL . '/application-verify/slip/' . urlencode($slipNumber));
                $imageData = ob_get_clean();
                
                if (!empty($imageData)) {
                    echo "<p class='success'>✅ GD fallback generated " . strlen($imageData) . " bytes</p>";
                    
                    // Save to temp file for display
                    $tempFile = __DIR__ . '/temp_qr.png';
                    file_put_contents($tempFile, $imageData);
                    
                    echo "<div class='qr-image'>";
                    echo "<img src='temp_qr.png?" . time() . "' alt='Generated QR'>";
                    echo "</div>";
                } else {
                    echo "<p class='error'>❌ GD fallback failed</p>";
                }
            }
            
        } catch (Exception $e) {
            echo "<p class='error'>Error: " . $e->getMessage() . "</p>";
            echo "<pre>" . $e->getTraceAsString() . "</pre>";
        }
        ?>
        
        <h3>4. Test Server-Side QR URL</h3>
        <p>Try accessing these URLs directly:</p>
        <ul>
            <li><a href="/application-verify/generate-qr/<?php echo urlencode($slipNumber); ?>" target="_blank">/application-verify/generate-qr/<?php echo htmlspecialchars($slipNumber); ?></a></li>
            <li><a href="/application-verify/qr/<?php echo urlencode($slipNumber); ?>" target="_blank">/application-verify/qr/<?php echo htmlspecialchars($slipNumber); ?></a></li>
        </ul>
        
        <h3>5. Alternative QR Generation (Pure PHP)</h3>
        <div class="qr-image">
            <?php
            // Try to generate QR using a different method
            $verificationUrl = BASE_URL . '/application-verify/slip/' . urlencode($slipNumber);
            
            // Method 1: Google Charts
            $googleChartsUrl = 'https://chart.googleapis.com/chart?chs=200x200&cht=qr&chl=' . urlencode($verificationUrl) . '&choe=UTF-8';
            echo "<h4>Google Charts QR:</h4>";
            echo "<img src='" . htmlspecialchars($googleChartsUrl) . "' alt='Google Charts QR' onerror='this.style.display=\"none\"'>";
            
            // Method 2: Simple GD generated
            if (extension_loaded('gd')) {
                $img = imagecreate(200, 200);
                $bg = imagecolorallocate($img, 255, 255, 255);
                $black = imagecolorallocate($img, 0, 0, 0);
                
                // Draw a simple QR-like pattern
                for ($i = 0; $i < 20; $i++) {
                    for ($j = 0; $j < 20; $j++) {
                        if (($i + $j) % 3 == 0) {
                            imagefilledrectangle($img, $i*10, $j*10, $i*10+8, $j*10+8, $black);
                        }
                    }
                }
                
                ob_start();
                imagepng($img);
                $imgData = ob_get_clean();
                imagedestroy($img);
                
                $base64 = 'data:image/png;base64,' . base64_encode($imgData);
                echo "<h4>Simple QR Pattern (GD):</h4>";
                echo "<img src='" . $base64 . "' alt='Simple QR'>";
            }
            ?>
        </div>
    </div>
</body>
</html>