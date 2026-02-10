<?php
// Simple test view
error_log("=== SIMPLE TEST VIEW LOADED ===");
error_log("News count: " . count($news ?? []));
error_log("Base URL: " . ($baseUrl ?? 'NOT SET'));
?>
<!DOCTYPE html>
<html>
<head>
    <title>News Test</title>
    <style>
        body { font-family: Arial, sans-serif; padding: 20px; }
        .article { border: 1px solid #ddd; padding: 15px; margin: 10px 0; }
        .debug { background: #f0f0f0; padding: 10px; margin: 10px 0; }
    </style>
</head>
<body>
    <h1>News Test Page</h1>
    
    <div class="debug">
        <h2>Debug Info:</h2>
        <p>News Count: <?php echo count($news ?? []); ?></p>
        <p>Base URL: <?php echo htmlspecialchars($baseUrl ?? 'NOT SET'); ?></p>
        <p>Page Title: <?php echo htmlspecialchars($pageTitle ?? 'NOT SET'); ?></p>
        <p>Current Page: <?php echo htmlspecialchars($currentPage ?? 'NOT SET'); ?></p>
    </div>
    
    <?php if (!empty($news) && count($news) > 0): ?>
        <h2>News Articles:</h2>
        <?php foreach ($news as $item): ?>
            <div class="article">
                <h3><?php echo htmlspecialchars($item['title'] ?? 'No Title'); ?></h3>
                <p><strong>Slug:</strong> <?php echo htmlspecialchars($item['slug'] ?? 'No Slug'); ?></p>
                <p><strong>Category:</strong> <?php echo htmlspecialchars($item['category'] ?? 'No Category'); ?></p>
                <p><strong>Published:</strong> <?php echo ($item['is_published'] ?? 0) ? 'Yes' : 'No'; ?></p>
                <p><strong>Featured:</strong> <?php echo ($item['is_featured'] ?? 0) ? 'Yes' : 'No'; ?></p>
                <p><strong>Excerpt:</strong> <?php echo htmlspecialchars(substr($item['excerpt'] ?? 'No excerpt', 0, 100)); ?>...</p>
                <p><strong>Created:</strong> <?php echo htmlspecialchars($item['created_at'] ?? 'Unknown'); ?></p>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <h2>No News Articles Found!</h2>
        <p>Check the PHP error log for debugging information.</p>
    <?php endif; ?>
    
    <hr>
    <p><a href="<?php echo $baseUrl ?? '/'; ?>/news">Go to main news page</a></p>
    <p><a href="<?php echo $baseUrl ?? '/'; ?>/news/test">Go to test page</a></p>
</body>
</html>