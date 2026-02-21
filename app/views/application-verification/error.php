<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($pageTitle ?? 'Verification Error'); ?></title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome 6 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            padding: 20px;
        }
        
        .error-card {
            border: none;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            animation: shake 0.5s ease-in-out;
            max-width: 600px;
            margin: 0 auto;
        }
        
        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            10%, 30%, 50%, 70%, 90% { transform: translateX(-5px); }
            20%, 40%, 60%, 80% { transform: translateX(5px); }
        }
        
        .error-header {
            background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
            color: white;
            padding: 40px 30px;
            text-align: center;
        }
        
        .error-icon {
            width: 80px;
            height: 80px;
            background: rgba(255,255,255,0.2);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
            font-size: 40px;
            animation: pulse 2s infinite;
        }
        
        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.1); }
            100% { transform: scale(1); }
        }
        
        .error-body {
            padding: 40px;
            background: white;
        }
        
        .error-message {
            font-size: 18px;
            color: #721c24;
            margin-bottom: 20px;
            text-align: center;
        }
        
        .suggestions-box {
            background: #fff3cd;
            border-left: 4px solid #ffc107;
            border-radius: 10px;
            padding: 20px;
            margin: 20px 0;
        }
        
        .suggestion-item {
            padding: 8px 0;
            border-bottom: 1px dashed #ffeeba;
        }
        
        .suggestion-item:last-child {
            border-bottom: none;
        }
        
        .action-buttons {
            display: flex;
            gap: 15px;
            justify-content: center;
            margin-top: 30px;
        }
        
        .btn-action {
            padding: 12px 30px;
            border-radius: 50px;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.3s ease;
        }
        
        .btn-primary {
            background: linear-gradient(135deg, #6B4E9B, #4A3B6E);
            color: white;
        }
        
        .btn-outline-primary {
            border: 2px solid #6B4E9B;
            color: #6B4E9B;
            background: transparent;
        }
        
        .btn-outline-primary:hover {
            background: #6B4E9B;
            color: white;
        }
        
        .error-footer {
            background: #f8f9fa;
            padding: 20px;
            border-top: 1px solid #dee2e6;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="error-card">
            <div class="error-header">
                <div class="error-icon">
                    <i class="fas fa-exclamation-triangle"></i>
                </div>
                <h2 class="h3 mb-2">Verification Failed</h2>
                <div class="small opacity-75">
                    Error Code: <?php echo htmlspecialchars($errorCode ?? 'UNKNOWN'); ?>
                </div>
            </div>
            
            <div class="error-body">
                <div class="error-message">
                    <i class="fas fa-times-circle me-2" style="color: #dc3545;"></i>
                    <?php echo htmlspecialchars($errorMessage); ?>
                </div>
                
                <div class="suggestions-box">
                    <h6 class="mb-3 text-warning">
                        <i class="fas fa-lightbulb me-2"></i> Possible Solutions:
                    </h6>
                    <?php foreach ($suggestions as $suggestion): ?>
                    <div class="suggestion-item">
                        <i class="fas fa-arrow-right me-2 text-warning"></i>
                        <?php echo htmlspecialchars($suggestion); ?>
                    </div>
                    <?php endforeach; ?>
                </div>
                
                <div class="action-buttons">
                    <a href="/application-verify/portal" class="btn-action btn-primary">
                        <i class="fas fa-redo-alt"></i> Try Again
                    </a>
                    <a href="javascript:history.back()" class="btn-action btn-outline-primary">
                        <i class="fas fa-arrow-left"></i> Go Back
                    </a>
                </div>
            </div>
            
            <div class="error-footer">
                <p class="small text-muted mb-0">
                    <strong><?php echo $institution_name ?? 'FCT College of Nursing Sciences'; ?></strong><br>
                    Need help? Contact: <a href="mailto:<?php echo $support_email; ?>"><?php echo $support_email; ?></a>
                </p>
            </div>
        </div>
    </div>
</body>
</html>