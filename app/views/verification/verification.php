<?php
/**
 * Verification Layout
 * Special layout for verification pages
 */
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $pageTitle ?? 'Document Verification'; ?></title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        /* Basic layout styles for verification pages */
    </style>
</head>
<body>
    <?php 
    // Include the actual view content
    $content = $this->content ?? '';
    echo $content;
    ?>
</body>
</html>