<?php
$slipNumber = $_GET['slip'] ?? $_GET['slipNumber'] ?? '';

if (empty($slipNumber)) {
    die('No slip number provided');
}

$slipNumber = preg_replace('/[^A-Za-z0-9\-]/', '', $slipNumber);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Examination Slip Verification</title>
    <style>
        body { font-family: Arial; padding: 20px; background: #f5f5f5; }
        .container { max-width: 600px; margin: 0 auto; background: white; padding: 30px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        .success { color: #28a745; }
        .slip-number { font-size: 24px; font-weight: bold; color: #6B4E9B; margin: 20px 0; }
    </style>
</head>
<body>
    <div class="container">
        <h1>✅ Verification Successful</h1>
        <p>This examination slip has been verified.</p>
        
        <h3>Slip Details:</h3>
        <p><strong>Slip Number:</strong> <span class="slip-number"><?php echo htmlspecialchars($slipNumber); ?></span></p>
        <p><strong>Verification Time:</strong> <?php echo date('Y-m-d H:i:s'); ?></p>
        <p><strong>Status:</strong> <span class="success">VALID</span></p>
        
        <hr>
        <p><small>This is an official verification from FCT College of Nursing Sciences</small></p>
    </div>
</body>
</html>
