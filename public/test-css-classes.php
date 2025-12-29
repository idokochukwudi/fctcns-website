<?php
$baseUrl = '/fctcns-website/public';
?>
<!DOCTYPE html>
<html>
<head>
    <title>CSS Classes Test</title>
    <link rel="stylesheet" href="<?php echo $baseUrl; ?>/assets/css/style.css?v=<?php echo time(); ?>">
</head>
<body>
    <h1>CSS Classes Test</h1>
    
    <!-- Test Container -->
    <div class="container" style="border: 2px solid red; padding: 20px;">
        <p>Container should have max-width: 1200px</p>
    </div>
    
    <!-- Test Grid -->
    <div class="container">
        <h2>Grid Test</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6" style="border: 2px solid blue; padding: 10px;">
            <div class="bg-primary text-white p-4">Primary Color</div>
            <div class="bg-secondary text-white p-4">Secondary Color</div>
            <div class="bg-gray-700 text-white p-4">Gray Color</div>
        </div>
    </div>
    
    <!-- Test Cards -->
    <div class="container mt-8">
        <h2>Card Test</h2>
        <div class="card" style="max-width: 300px;">
            <div class="card-body">
                <h3 class="card-title">Card Title</h3>
                <p>This should have shadow and border-radius</p>
                <a href="#" class="card-link">Card Link</a>
            </div>
        </div>
    </div>
    
    <!-- Test Buttons -->
    <div class="container mt-8">
        <h2>Button Test</h2>
        <button class="btn btn-primary">Primary Button</button>
        <button class="btn btn-secondary">Secondary Button</button>
        <button class="btn btn-outline">Outline Button</button>
    </div>
    
    <!-- Test Typography -->
    <div class="container mt-8">
        <h2>Typography Test</h2>
        <h1 class="text-4xl font-bold text-gray-800">H1 Heading</h1>
        <h2 class="text-3xl font-semibold text-gray-700">H2 Heading</h2>
        <p class="text-gray-600">This is body text with proper line height and color.</p>
    </div>
</body>
</html>