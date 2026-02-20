<?php
/**
 * Test Remita Configuration
 * This script verifies that Remita environment variables are loading correctly
 */

// First, define a marker to prevent multiple includes if needed
if (!defined('CONSTANTS_LOADED')) {
    // Load constants.php (your bootstrap file)
    require_once __DIR__ . '/app/config/constants.php';
}

echo "<h1>��� Remita Configuration Test</h1>";

echo "<h2>1. Environment Variables from getenv():</h2>";
echo "<pre>";
echo "REMITA_MERCHANT_ID: " . (getenv('REMITA_MERCHANT_ID') ?: 'NOT SET') . "\n";
echo "REMITA_API_KEY: " . (getenv('REMITA_API_KEY') ? substr(getenv('REMITA_API_KEY'), 0, 4) . '****' : 'NOT SET') . "\n";
echo "REMITA_SERVICE_TYPE_ID: " . (getenv('REMITA_SERVICE_TYPE_ID') ?: 'NOT SET') . "\n";
echo "REMITA_ENVIRONMENT: " . (getenv('REMITA_ENVIRONMENT') ?: 'NOT SET') . "\n";
echo "</pre>";

echo "<h2>2. Constants defined in constants.php:</h2>";
echo "<pre>";
echo "REMITA_MERCHANT_ID: " . (defined('REMITA_MERCHANT_ID') ? REMITA_MERCHANT_ID : 'NOT DEFINED') . "\n";
echo "REMITA_API_KEY: " . (defined('REMITA_API_KEY') ? substr(REMITA_API_KEY, 0, 4) . '****' : 'NOT DEFINED') . "\n";
echo "REMITA_SERVICE_TYPE_ID: " . (defined('REMITA_SERVICE_TYPE_ID') ? REMITA_SERVICE_TYPE_ID : 'NOT DEFINED') . "\n";
echo "REMITA_ENVIRONMENT: " . (defined('REMITA_ENVIRONMENT') ? REMITA_ENVIRONMENT : 'NOT DEFINED') . "\n";
echo "</pre>";

echo "<h2>3. $_ENV superglobal:</h2>";
echo "<pre>";
echo "REMITA_MERCHANT_ID: " . ($_ENV['REMITA_MERCHANT_ID'] ?? 'NOT SET') . "\n";
echo "REMITA_API_KEY: " . (isset($_ENV['REMITA_API_KEY']) ? substr($_ENV['REMITA_API_KEY'], 0, 4) . '****' : 'NOT SET') . "\n";
echo "REMITA_SERVICE_TYPE_ID: " . ($_ENV['REMITA_SERVICE_TYPE_ID'] ?? 'NOT SET') . "\n";
echo "REMITA_ENVIRONMENT: " . ($_ENV['REMITA_ENVIRONMENT'] ?? 'NOT SET') . "\n";
echo "</pre>";

echo "<h2>4. Which .env file was loaded?</h2>";
echo "<pre>";
// Check which env file was loaded by looking at ROOT_PATH
$rootPath = defined('ROOT_PATH') ? ROOT_PATH : (getenv('ROOT_PATH') ?: $_ENV['ROOT_PATH'] ?? __DIR__);
echo "ROOT_PATH: " . $rootPath . "\n\n";

$envFiles = [
    '.env.production' => $rootPath . '/.env.production',
    '.env.local' => $rootPath . '/.env.local',
    '.env' => $rootPath . '/.env'
];

foreach ($envFiles as $name => $path) {
    if (file_exists($path)) {
        echo "$name: ✅ EXISTS\n";
        echo "  Path: $path\n";
        echo "  Size: " . filesize($path) . " bytes\n";
        echo "  Modified: " . date('Y-m-d H:i:s', filemtime($path)) . "\n";
        
        // Show Remita lines from this file
        echo "  Remita lines:\n";
        $content = file($path);
        foreach ($content as $line) {
            if (strpos($line, 'REMITA_') !== false) {
                $line = trim($line);
                if (strpos($line, 'KEY') !== false || strpos($line, 'SECRET') !== false) {
                    $parts = explode('=', $line, 2);
                    $line = $parts[0] . '=****' . substr($parts[1] ?? '', -4);
                }
                echo "    " . htmlspecialchars($line) . "\n";
            }
        }
    } else {
        echo "$name: ❌ NOT FOUND\n";
    }
}
echo "</pre>";

echo "<h2>5. RemitaModel Check</h2>";
echo "<pre>";
$remitaModelPath = __DIR__ . '/app/models/application/RemitaModel.php';
if (file_exists($remitaModelPath)) {
    echo "✅ RemitaModel found at: $remitaModelPath\n";
    
    // Try to include it and check if it can access constants
    require_once $remitaModelPath;
    
    if (class_exists('RemitaModel')) {
        echo "✅ RemitaModel class exists\n";
        
        // Try to instantiate
        try {
            $remita = new RemitaModel();
            echo "✅ RemitaModel instantiated successfully\n";
            echo "  Environment: " . $remita->getEnvironment() . "\n";
            echo "  Merchant ID: " . substr($remita->getMerchantId(), 0, 4) . "****\n";
            echo "  Service Type: " . $remita->getServiceTypeId() . "\n";
            echo "  Base URL: " . $remita->getBaseUrl() . "\n";
        } catch (Exception $e) {
            echo "❌ Failed to instantiate RemitaModel: " . $e->getMessage() . "\n";
        }
    } else {
        echo "❌ RemitaModel class not found\n";
    }
} else {
    echo "❌ RemitaModel not found at: $remitaModelPath\n";
    
    // Search for it
    echo "\nSearching for RemitaModel.php...\n";
    $files = glob(__DIR__ . '/app/models/**/*Remita*.php');
    foreach ($files as $file) {
        echo "  Found: " . str_replace(__DIR__, '', $file) . "\n";
    }
}
echo "</pre>";

echo "<h2>6. Test Complete</h2>";
echo "<p>If all values are showing correctly, your Remita configuration is working!</p>";
?>
