<?php
/**
 * Main Layout Template
 * 
 * Base layout for all MVC views. Includes header, footer, and injects view content.
 * This file is used by the Controller::render() method.
 * 
 * @package FCT_CNS
 * @version 2.0
 */

// Extract data passed from controller
extract($data ?? []);

// Set defaults
$baseUrl = $baseUrl ?? (defined('BASE_URL') ? BASE_URL : '/fctcns-website');
$page_title = $page_title ?? 'FCT College of Nursing Sciences';
$page_description = $page_description ?? 'Empowering Future Healthcare Professionals Since 1989';
$page_keywords = $page_keywords ?? 'nursing college, FCT, nursing education, healthcare professionals';
$currentPage = $currentPage ?? 'home';

// Helper function - ONLY define if not already defined
if (!function_exists('e')) {
    function e($string) {
        return htmlspecialchars($string ?? '', ENT_QUOTES, 'UTF-8');
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo e($page_title); ?></title>
    <meta name="description" content="<?php echo e($page_description); ?>">
    <meta name="keywords" content="<?php echo e($page_keywords); ?>">
    
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="<?php echo $baseUrl; ?>/assets/images/favicon.ico">
    
    <!-- CSS -->
    <link rel="stylesheet" href="<?php echo $baseUrl; ?>/assets/css/style.css">
    
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <!-- CSRF Token -->
    <meta name="csrf-token" content="<?php echo $csrf_token ?? ''; ?>">
    
    <!-- Page-specific CSS -->
    <?php if (isset($page_css)): ?>
        <style><?php echo $page_css; ?></style>
    <?php endif; ?>
</head>
<body class="<?php echo $currentPage; ?>-page">
    <!-- Include header -->
    <?php
    $headerPath = INCLUDES_PATH . '/header.php';
    if (file_exists($headerPath)) {
        // Pass data to header
        $headerData = [
            'pageTitle' => $page_title,
            'pageDescription' => $page_description,
            'pageKeywords' => $page_keywords,
            'baseUrl' => $baseUrl,
            'currentPage' => $currentPage,
            'csrf_token' => $csrf_token ?? ''
        ];
        extract($headerData);
        include $headerPath;
    } else {
        echo '<header>Header not found at: ' . $headerPath . '</header>';
    }
    ?>
    
    <!-- Main Content Area -->
    <main class="main-content">
        <?php 
        // View content is injected here by Controller::render()
        echo $content ?? '<div class="container"><p>No content available.</p></div>';
        ?>
    </main>
    
    <!-- Include footer -->
    <?php
    $footerPath = INCLUDES_PATH . '/footer.php';
    if (file_exists($footerPath)) {
        // Pass data to footer
        $footerData = [
            'baseUrl' => $baseUrl,
            'currentPage' => $currentPage
        ];
        extract($footerData);
        include $footerPath;
    } else {
        echo '<footer>Footer not found at: ' . $footerPath . '</footer>';
    }
    ?>
    
    <!-- JavaScript -->
    <script src="<?php echo $baseUrl; ?>/assets/js/main.js"></script>
    
    <!-- Page-specific JavaScript -->
    <?php if (isset($page_js)): ?>
        <script><?php echo $page_js; ?></script>
    <?php endif; ?>
    
    <!-- Flash Messages Script -->
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Close flash messages
        document.querySelectorAll('.flash-close').forEach(button => {
            button.addEventListener('click', function() {
                this.closest('.flash-message').remove();
            });
        });
        
        // Auto-dismiss flash messages after 5 seconds
        setTimeout(() => {
            document.querySelectorAll('.flash-message').forEach(msg => {
                msg.style.opacity = '0';
                setTimeout(() => msg.remove(), 300);
            });
        }, 5000);
        
        // Set global variables
        window.BASE_URL = '<?php echo $baseUrl; ?>';
        <?php if (isset($csrf_token)): ?>
        window.CSRF_TOKEN = '<?php echo $csrf_token; ?>';
        <?php endif; ?>
    });
    </script>
</body>
</html>