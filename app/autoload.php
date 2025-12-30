<?php
/**
 * Simple Autoloader
 * 
 * Automatically loads classes when they're needed
 * 
 * @package FCT_CNS
 */

spl_autoload_register(function ($className) {
    // Base directory for classes
    $baseDir = __DIR__ . '/';
    
    // Remove any namespace prefix
    $originalClassName = $className;
    $className = ltrim($className, '\\');
    
    // Convert namespace separators to directory separators
    $className = str_replace('\\', DIRECTORY_SEPARATOR, $className);
    
    // Handle controller names (when called as "PageController@home")
    $isController = false;
    if (substr($className, -10) === 'Controller') {
        $isController = true;
    }
    
    // Possible file locations (in order of priority)
    $possiblePaths = [
        // Try exact match first
        $baseDir . 'controllers/' . $className . '.php',
        $baseDir . 'models/' . $className . '.php',
        $baseDir . 'core/' . $className . '.php',
        $baseDir . 'middleware/' . $className . '.php',
        $baseDir . $className . '.php',
        
        // For controllers without "Controller" suffix (when called as "Page")
        $baseDir . 'controllers/' . $className . 'Controller.php',
    ];
    
    // Try each possible path
    foreach ($possiblePaths as $filePath) {
        if (file_exists($filePath)) {
            require_once $filePath;
            
            // After loading, check if class exists
            if ($isController && !class_exists($originalClassName)) {
                // Try to find the class without namespace
                $shortClassName = basename(str_replace('\\', '/', $originalClassName));
                if (!class_exists($shortClassName)) {
                    error_log("Autoloader: Class '$originalClassName' or '$shortClassName' not found in $filePath");
                }
            }
            return;
        }
    }
    
    // If class not found and we're in debug mode, log error
    if (defined('APP_DEBUG') && APP_DEBUG) {
        error_log("Autoloader: Class '$originalClassName' not found. Searched paths:");
        foreach ($possiblePaths as $path) {
            error_log("  - $path");
        }
    }
});