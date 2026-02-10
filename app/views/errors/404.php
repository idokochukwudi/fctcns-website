<?php
$baseUrl = $baseUrl ?? BASE_URL;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Page Not Found - FCT College of Nursing Sciences</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: linear-gradient(135deg, #5D4A8A, #4A3A6F);
            color: white;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
            padding: 20px;
        }
        
        .error-container {
            max-width: 600px;
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            padding: 40px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.3);
        }
        
        .error-icon {
            font-size: 80px;
            margin-bottom: 20px;
            color: #FF6B6B;
        }
        
        h1 {
            font-size: 48px;
            margin-bottom: 20px;
            font-weight: 700;
        }
        
        p {
            font-size: 18px;
            margin-bottom: 30px;
            opacity: 0.9;
            line-height: 1.6;
        }
        
        .error-code {
            background: rgba(255, 255, 255, 0.2);
            padding: 8px 20px;
            border-radius: 30px;
            font-size: 14px;
            margin-bottom: 30px;
            display: inline-block;
        }
        
        .button-group {
            display: flex;
            gap: 15px;
            justify-content: center;
            flex-wrap: wrap;
        }
        
        .btn {
            padding: 12px 30px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }
        
        .btn-primary {
            background: white;
            color: #5D4A8A;
        }
        
        .btn-primary:hover {
            background: #f0f0f0;
            transform: translateY(-2px);
        }
        
        .btn-secondary {
            background: rgba(255, 255, 255, 0.2);
            color: white;
            border: 2px solid rgba(255, 255, 255, 0.3);
        }
        
        .btn-secondary:hover {
            background: rgba(255, 255, 255, 0.3);
            transform: translateY(-2px);
        }
        
        @media (max-width: 600px) {
            .error-container {
                padding: 30px 20px;
            }
            
            h1 {
                font-size: 36px;
            }
            
            .button-group {
                flex-direction: column;
            }
            
            .btn {
                width: 100%;
                justify-content: center;
            }
        }
    </style>
</head>
<body>
    <div class="error-container">
        <div class="error-icon">🔍</div>
        <h1>Page Not Found</h1>
        <p>The page you're looking for doesn't exist or has been moved.</p>
        <div class="error-code">Error 404</div>
        <div class="button-group">
            <a href="<?php echo $baseUrl; ?>/" class="btn btn-primary">
                <i class="fas fa-home"></i> Back to Home
            </a>
            <a href="<?php echo $baseUrl; ?>/news" class="btn btn-secondary">
                <i class="fas fa-newspaper"></i> Browse News
            </a>
        </div>
    </div>
    
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
</body>
</html>