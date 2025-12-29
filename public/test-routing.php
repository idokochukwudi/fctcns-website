<?php
/**
 * Simple Routing Test
 * 
 * @package FCT_CNS
 */

// Simple test without complex dependencies
echo "<h1>Routing Test Page</h1>";
echo "<p>If you can see this, basic PHP is working.</p>";

// Test if we can include constants
$constantsFile = dirname(__DIR__) . '/app/config/constants.php';
if (file_exists($constantsFile)) {
    require_once $constantsFile;
    echo "<p style='color: green;'>✓ Constants.php loaded</p>";
    echo "<p>ROOT_PATH: " . ROOT_PATH . "</p>";
} else {
    echo "<p style='color: red;'>✗ Constants.php not found at: $constantsFile</p>";
}

// Test Router class
$routerFile = dirname(__DIR__) . '/app/core/Router.php';
if (file_exists($routerFile)) {
    require_once $routerFile;
    echo "<p style='color: green;'>✓ Router.php loaded</p>";
    
    // Try to create router instance
    try {
        $router = new Router();
        echo "<p style='color: green;'>✓ Router instance created successfully</p>";
        
        // Test a simple route
        $router->get('/test', function() {
            echo "Test route works!";
        });
        
        echo "<p style='color: green;'>✓ Route added successfully</p>";
        
    } catch (Exception $e) {
        echo "<p style='color: red;'>✗ Error creating router: " . $e->getMessage() . "</p>";
    }
} else {
    echo "<p style='color: red;'>✗ Router.php not found at: $routerFile</p>";
}

// Test current URL
echo "<h2>Current Request Information</h2>";
echo "<p>Request URI: " . $_SERVER['REQUEST_URI'] . "</p>";
echo "<p>Script Name: " . $_SERVER['SCRIPT_NAME'] . "</p>";
echo "<p>Request Method: " . $_SERVER['REQUEST_METHOD'] . "</p>";

// Test links
echo "<h2>Test Links</h2>";
echo "<ul>";
echo "<li><a href='/fctcns-website/public/'>Homepage</a></li>";
echo "<li><a href='/fctcns-website/public/about'>About Page</a></li>";
echo "<li><a href='/fctcns-website/public/programs'>Programs Page</a></li>";
echo "<li><a href='/fctcns-website/public/contact'>Contact Page</a></li>";
echo "</ul>";

echo "<p><a href='/fctcns-website/public/' style='background: #6B4E9B; color: white; padding: 10px; text-decoration: none;'>Back to Home</a></p>";
?>