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
    
    // Convert namespace separators to directory separators
    $className = str_replace('\\', DIRECTORY_SEPARATOR, $className);
    
    // Possible file locations
    $possiblePaths = [
        $baseDir . 'core/' . $className . '.php',
        $baseDir . 'controllers/' . $className . '.php',
        $baseDir . 'models/' . $className . '.php',
        $baseDir . 'middleware/' . $className . '.php',
        $baseDir . $className . '.php',
    ];
    
    // Try each possible path
    foreach ($possiblePaths as $filePath) {
        if (file_exists($filePath)) {
            require_once $filePath;
            return;
        }
    }
    
    // If class not found, log error
    error_log("Autoloader: Class '$className' not found");
});
?>