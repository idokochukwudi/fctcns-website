<?php
/**
 * Check Controller File
 */

echo "<h1>Checking ApplicationVerificationController</h1>";

$controllerPath = __DIR__ . '/app/controllers/ApplicationVerificationController.php';

if (file_exists($controllerPath)) {
    echo "<p style='color:green'>✅ Controller file exists at: $controllerPath</p>";
    
    // Get file contents to check includes
    $content = file_get_contents($controllerPath);
    
    // Check for Controller.php include
    if (preg_match('/require_once.*Controller\.php/', $content, $matches)) {
        echo "<p>Controller include line: " . htmlspecialchars($matches[0]) . "</p>";
    }
    
    // Check for generateQR method
    if (strpos($content, 'function generateQR') !== false) {
        echo "<p style='color:green'>✅ generateQR method found</p>";
    } else {
        echo "<p style='color:red'>❌ generateQR method NOT found</p>";
    }
    
    // Try to include the file
    try {
        define('ROOT_PATH', __DIR__);
        define('APP_PATH', __DIR__ . '/app');
        define('MODELS_PATH', APP_PATH . '/models');
        
        require_once $controllerPath;
        
        if (class_exists('ApplicationVerificationController')) {
            echo "<p style='color:green'>✅ Class loads successfully</p>";
            
            // Try to instantiate
            $controller = new ApplicationVerificationController();
            echo "<p style='color:green'>✅ Controller instantiated</p>";
        } else {
            echo "<p style='color:red'>❌ Class does not exist after including file</p>";
        }
    } catch (Exception $e) {
        echo "<p style='color:red'>❌ Error loading controller: " . $e->getMessage() . "</p>";
    }
    
} else {
    echo "<p style='color:red'>❌ Controller file NOT found at: $controllerPath</p>";
}

// Check core Controller.php
$coreControllerPath = __DIR__ . '/app/core/Controller.php';
if (file_exists($coreControllerPath)) {
    echo "<p style='color:green'>✅ Core Controller exists at: $coreControllerPath</p>";
} else {
    echo "<p style='color:red'>❌ Core Controller NOT found at: $coreControllerPath</p>";
}