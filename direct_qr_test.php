<?php
/**
 * Direct QR Test - Bypasses router
 * Access: http://yourdomain.com/direct_qr_test.php?slip=SLIP-2025-00001
 */

// Define paths
define('ROOT_PATH', __DIR__);
define('APP_PATH', ROOT_PATH . '/app');
define('MODELS_PATH', APP_PATH . '/models');
define('BASE_URL', 'http://' . $_SERVER['HTTP_HOST']);

// Load controller
require_once APP_PATH . '/core/Controller.php';
require_once APP_PATH . '/controllers/ApplicationVerificationController.php';

$slipNumber = $_GET['slip'] ?? 'SLIP-2025-00001';

try {
    $controller = new ApplicationVerificationController();
    
    // Call generateQR method directly
    $controller->generateQR($slipNumber);
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}