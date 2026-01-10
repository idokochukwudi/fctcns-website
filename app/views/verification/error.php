<?php
/**
 * Verification Error View
 * Shows when verification fails
 * 
 * @package FCT_CNS
 */
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($pageTitle ?? 'Verification Error'); ?></title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: linear-gradient(135deg, #ff6b6b 0%, #ee5a52 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            color: white;
        }
        
        .error-card {
            background: white;
            border-radius: 20px;
            width: 100%;
            max-width: 500px;
            padding: 40px;
            text-align: center;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            animation: slideUp 0.5s ease-out;
        }
        
        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .error-icon {
            width: 80px;
            height: 80px;
            background: #dc3545;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
            font-size: 36px;
            color: white;
        }
        
        h1 {
            color: #dc3545;
            margin-bottom: 15px;
            font-size: 24px;
        }
        
        p {
            color: #666;
            line-height: 1.6;
            margin-bottom: 25px;
        }
        
        .error-details {
            background: #f8f9fa;
            border-radius: 10px;
            padding: 15px;
            margin: 20px 0;
            text-align: left;
            border-left: 4px solid #dc3545;
        }
        
        .error-details code {
            display: block;
            font-family: monospace;
            font-size: 12px;
            color: #666;
            word-break: break-all;
        }
        
        .btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 12px 24px;
            background: #dc3545;
            color: white;
            text-decoration: none;
            border-radius: 8px;
            font-weight: 600;
            transition: all 0.3s ease;
            border: none;
            cursor: pointer;
        }
        
        .btn:hover {
            background: #c82333;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(220, 53, 69, 0.3);
        }
        
        .btn-secondary {
            background: #6c757d;
            margin-left: 10px;
        }
        
        .btn-secondary:hover {
            background: #5a6268;
        }
    </style>
</head>
<body>
    <div class="error-card">
        <div class="error-icon">
            <i class="fas fa-exclamation-triangle"></i>
        </div>
        
        <h1>Verification Failed</h1>
        
        <p>
            <?php echo htmlspecialchars($errorMessage ?? 'There was an issue verifying this document.'); ?>
        </p>
        
        <?php if (defined('APP_DEBUG') && APP_DEBUG): ?>
        <div class="error-details">
            <strong>Debug Information:</strong>
            <code>
                Request URI: <?php echo htmlspecialchars($_SERVER['REQUEST_URI'] ?? ''); ?><br>
                Document Reference: <?php echo htmlspecialchars($_GET['ref'] ?? 'None'); ?><br>
                Timestamp: <?php echo date('Y-m-d H:i:s'); ?>
            </code>
        </div>
        <?php endif; ?>
        
        <div style="margin-top: 30px;">
            <a href="javascript:history.back()" class="btn">
                <i class="fas fa-arrow-left"></i> Go Back
            </a>
            <a href="<?php echo $baseUrl ?? '/'; ?>" class="btn btn-secondary">
                <i class="fas fa-home"></i> Go Home
            </a>
        </div>
        
        <div style="margin-top: 20px; font-size: 12px; color: #999;">
            <p>If this problem persists, please contact the HR department.</p>
        </div>
    </div>
    
    <script>
        // Auto-redirect after 10 seconds
        setTimeout(function() {
            window.location.href = '<?php echo $baseUrl ?? '/'; ?>';
        }, 10000);
    </script>
</body>
</html>