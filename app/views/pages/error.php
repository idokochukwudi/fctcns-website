<?php
// Ensure variables exist
$error = $error ?? 'An error occurred';
$page_title = $page_title ?? 'Error';
$baseUrl = $baseUrl ?? '/';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($page_title); ?> - <?php echo defined('SITE_NAME') ? SITE_NAME : 'FCT CNS'; ?></title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
            color: #333;
            padding: 40px;
            text-align: center;
        }
        .error-container {
            max-width: 600px;
            margin: 0 auto;
            background: white;
            padding: 40px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        h1 {
            color: #dc3545;
            margin-bottom: 20px;
        }
        .error-message {
            background: #f8d7da;
            color: #721c24;
            padding: 15px;
            border-radius: 4px;
            margin: 20px 0;
            text-align: left;
        }
        .btn {
            display: inline-block;
            padding: 10px 20px;
            background: #007bff;
            color: white;
            text-decoration: none;
            border-radius: 4px;
            margin-top: 20px;
        }
        .btn:hover {
            background: #0056b3;
        }
        pre {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 4px;
            text-align: left;
            overflow-x: auto;
            font-size: 12px;
        }
    </style>
</head>
<body>
    <div class="error-container">
        <h1><?php echo htmlspecialchars($page_title); ?></h1>
        <div class="error-message">
            <?php echo htmlspecialchars($error); ?>
        </div>
        
        <?php if (defined('APP_DEBUG') && APP_DEBUG && isset($trace)): ?>
        <div style="margin-top: 20px; text-align: left;">
            <h3>Debug Information:</h3>
            <pre><?php echo htmlspecialchars($trace); ?></pre>
        </div>
        <?php endif; ?>
        
        <a href="<?php echo $baseUrl; ?>" class="btn">Return to Homepage</a>
        <a href="<?php echo $baseUrl; ?>/news" class="btn" style="background: #6c757d; margin-left: 10px;">Back to News</a>
    </div>
</body>
</html>