<!DOCTYPE html>
<html>
<head>
    <title>CSS Test</title>
    <style>
        body { font-family: sans-serif; padding: 20px; }
        .test { padding: 10px; margin: 10px 0; border-radius: 5px; }
        .success { background: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
        .error { background: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }
    </style>
</head>
<body>
    <h1>CSS Loading Test</h1>
    
    <?php
    $cssFiles = [
        'variables.css',
        'reset.css', 
        'base.css',
        'layout.css',
        'components.css',
        'carousel.css',
        'responsive.css',
        'style.css',
        'admin.css'
    ];
    
    $basePath = '/fctcns-website/public/assets/css/';
    
    foreach ($cssFiles as $file) {
        $url = 'http://localhost' . $basePath . $file;
        $headers = @get_headers($url);
        
        if ($headers && strpos($headers[0], '200')) {
            echo "<div class='test success'>✓ $file loads successfully</div>";
        } else {
            echo "<div class='test error'>✗ $file NOT FOUND or error loading</div>";
            echo "<div>URL: <a href='$url' target='_blank'>$url</a></div>";
        }
    }
    ?>
    
    <h2>Test CSS Variables</h2>
    <div style="
        background: var(--color-primary, #6B4E9B);
        color: white;
        padding: 20px;
        border-radius: 5px;
        margin: 10px 0;
    ">
        If this is purple (#6B4E9B), CSS variables are working.
    </div>
    
    <div style="
        background: var(--color-secondary, #7FB285);
        color: white;
        padding: 20px;
        border-radius: 5px;
        margin: 10px 0;
    ">
        If this is sage green (#7FB285), CSS variables are working.
    </div>
    
    <h2>Test Components</h2>
    <button class="btn btn-primary" style="margin: 10px;">Primary Button</button>
    <button class="btn btn-secondary" style="margin: 10px;">Secondary Button</button>
    
    <h2>Test Links</h2>
    <p><a href="/fctcns-website/public/">Homepage</a></p>
    <p><a href="/fctcns-website/public/programs">Programs Page</a></p>
</body>
</html>