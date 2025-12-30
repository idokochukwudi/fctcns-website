<?php
require_once dirname(__DIR__) . '/app/config/constants.php';
require_once APP_PATH . '/core/Controller.php';
require_once APP_PATH . '/controllers/PageController.php';

echo "<h1>Testing PageController Directly</h1>";

try {
    // Test creating PageController
    $controller = new PageController();
    echo "<p style='color:green'>✓ PageController created successfully</p>";
    
    // Test if about method exists
    if (method_exists($controller, 'about')) {
        echo "<p style='color:green'>✓ about() method exists</p>";
        
        // Try to render about page directly
        echo "<h2>Trying to render about page:</h2>";
        ob_start();
        $controller->about();
        $output = ob_get_clean();
        echo "<div style='border: 1px solid green; padding: 10px;'>$output</div>";
    } else {
        echo "<p style='color:red'>✗ about() method not found</p>";
    }
    
    // Test view file existence
    echo "<h2>Checking view files:</h2>";
    $views = ['home', 'about', 'programs', 'admissions', 'contact'];
    foreach ($views as $view) {
        $path = PAGES_PATH . '/' . $view . '.php';
        $exists = file_exists($path);
        echo "{$view}.php: " . ($exists ? 
            "<span style='color:green'>✓ Exists ($path)</span>" : 
            "<span style='color:red'>✗ Missing ($path)</span>") . "<br>";
    }
    
} catch (Exception $e) {
    echo "<p style='color:red'>✗ Error: " . $e->getMessage() . "</p>";
    echo "<pre>" . $e->getTraceAsString() . "</pre>";
}

// Test routing
echo "<h2>Testing Router:</h2>";
try {
    require_once APP_PATH . '/core/Router.php';
    $router = new Router();
    
    // Add test route
    $router->get('/test-route', function() {
        echo "Test route works!";
    });
    
    echo "<p style='color:green'>✓ Router created and test route added</p>";
} catch (Exception $e) {
    echo "<p style='color:red'>✗ Router Error: " . $e->getMessage() . "</p>";
}
?>