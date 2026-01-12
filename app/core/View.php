<?php
/**
 * View Renderer
 * Handles rendering of view files
 */

class View
{
    /**
     * Render a view file with data
     */
    public static function render($viewPath, $data = [])
    {
        // Extract data to variables
        extract($data);
        
        // Start output buffering
        ob_start();
        
        // Determine full path to view file
        $fullPath = VIEWS_PATH . '/' . $viewPath . '.php';
        
        if (file_exists($fullPath)) {
            require $fullPath;
        } else {
            // Try alternative path
            $altPath = APP_PATH . '/views/' . $viewPath . '.php';
            if (file_exists($altPath)) {
                require $altPath;
            } else {
                echo "View not found: {$viewPath}";
                error_log("View not found: {$viewPath}. Looked in: {$fullPath} and {$altPath}");
            }
        }
        
        // Get and return buffer content
        return ob_get_clean();
    }
    
    /**
     * Include a partial view
     */
    public static function partial($partialPath, $data = [])
    {
        extract($data);
        
        $fullPath = VIEWS_PATH . '/' . $partialPath . '.php';
        if (file_exists($fullPath)) {
            require $fullPath;
        }
    }
    
    /**
     * Escape HTML output
     */
    public static function escape($string)
    {
        return htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
    }
}