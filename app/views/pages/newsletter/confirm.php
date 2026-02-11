<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Subscription Confirmed - FCT College of Nursing Sciences</title>
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
    </style>
</head>
<body>
    <div class="container">
        <?php if ($confirmed): ?>
            <div class="success-icon">✓</div>
            <h1>Subscription Confirmed!</h1>
            <p>Thank you for confirming your email address. You're now officially subscribed to our newsletter.</p>
            <p>You'll receive the latest news and updates from FCT College of Nursing Sciences directly in your inbox.</p>
            <a href="<?php echo BASE_URL; ?>" class="btn">Return to Homepage</a>
        <?php else: ?>
            <div class="error-icon">✗</div>
            <h1>Confirmation Failed</h1>
            <p>We couldn't confirm your subscription. The link may be invalid or expired.</p>
            <p>Please try subscribing again or contact us for assistance.</p>
            <a href="<?php echo BASE_URL; ?>/news" class="btn">Browse News</a>
        <?php endif; ?>
    </div>
</body>
</html>