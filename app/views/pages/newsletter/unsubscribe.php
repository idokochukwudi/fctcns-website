<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Unsubscribe - FCT College of Nursing Sciences</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            line-height: 1.6;
            color: #333;
            margin: 0;
            padding: 0;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .container {
            max-width: 600px;
            margin: 20px;
            padding: 40px;
            background: white;
            border-radius: 10px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            text-align: center;
        }
        .success-icon {
            color: #28a745;
            font-size: 64px;
            margin-bottom: 20px;
        }
        .error-icon {
            color: #dc3545;
            font-size: 64px;
            margin-bottom: 20px;
        }
        h1 {
            color: #5D4A8A;
            margin-bottom: 20px;
        }
        p {
            margin-bottom: 20px;
            color: #666;
        }
        .btn {
            display: inline-block;
            background: #5D4A8A;
            color: white;
            padding: 12px 30px;
            text-decoration: none;
            border-radius: 5px;
            font-weight: 600;
            transition: background 0.3s;
        }
        .btn:hover {
            background: #4A3A6F;
        }
        .btn-secondary {
            background: #6c757d;
        }
        .btn-secondary:hover {
            background: #5a6268;
        }
    </style>
</head>
<body>
    <div class="container">
        <?php if ($success): ?>
            <div class="success-icon">✓</div>
            <h1>Successfully Unsubscribed</h1>
            <p><?php echo htmlspecialchars($message); ?></p>
            <p>You'll no longer receive emails from us. We're sorry to see you go!</p>
            <p style="font-size: 14px; color: #888;">Email: <?php echo htmlspecialchars($email); ?></p>
            <div style="margin-top: 30px;">
                <a href="<?php echo BASE_URL; ?>" class="btn">Return to Homepage</a>
                <a href="<?php echo BASE_URL; ?>/news" class="btn btn-secondary" style="margin-left: 10px;">Browse News</a>
            </div>
        <?php else: ?>
            <div class="error-icon">✗</div>
            <h1>Unsubscribe Failed</h1>
            <p><?php echo htmlspecialchars($message); ?></p>
            <p>If you're having trouble, please contact us directly.</p>
            <div style="margin-top: 30px;">
                <a href="<?php echo BASE_URL; ?>" class="btn">Return to Homepage</a>
                <a href="<?php echo BASE_URL; ?>/contact" class="btn btn-secondary" style="margin-left: 10px;">Contact Us</a>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>