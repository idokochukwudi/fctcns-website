<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FCT CNS - Setup Required</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Segoe UI', sans-serif; background: linear-gradient(135deg, #6B4E9B20, #7FB28520); min-height: 100vh; display: flex; align-items: center; justify-content: center; }
        .setup-container { background: white; padding: 40px; border-radius: 15px; box-shadow: 0 10px 30px rgba(0,0,0,0.1); max-width: 800px; width: 90%; }
        h1 { color: #6B4E9B; margin-bottom: 20px; border-bottom: 2px solid #7FB285; padding-bottom: 10px; }
        .step { margin: 25px 0; padding: 20px; background: #F8F9FA; border-radius: 8px; border-left: 4px solid #6B4E9B; }
        .step h3 { color: #2C3E50; margin-bottom: 10px; }
        .step-number { display: inline-block; background: #6B4E9B; color: white; width: 30px; height: 30px; border-radius: 50%; text-align: center; line-height: 30px; margin-right: 10px; }
        .btn { display: inline-block; background: #6B4E9B; color: white; padding: 12px 25px; border-radius: 5px; text-decoration: none; font-weight: 600; border: none; cursor: pointer; margin: 10px 5px; transition: all 0.3s; }
        .btn:hover { background: #5A3F8A; transform: translateY(-2px); }
        .btn-secondary { background: #7FB285; }
        .btn-secondary:hover { background: #6A9C70; }
        .btn-danger { background: #e74c3c; }
        .btn-danger:hover { background: #c0392b; }
        .url-box { background: #2C3E50; color: white; padding: 15px; border-radius: 5px; margin: 10px 0; font-family: monospace; }
        .success { color: #7FB285; font-weight: bold; }
        .error { color: #e74c3c; font-weight: bold; }
        .warning { background: #fff3cd; border-left-color: #ffc107; color: #856404; padding: 15px; border-radius: 5px; margin: 15px 0; }
        pre { background: #2C3E50; color: white; padding: 15px; border-radius: 5px; overflow: auto; margin: 10px 0; }
    </style>
</head>
<body>
    <div class="setup-container">
        <h1>FCT College of Nursing Sciences - Setup</h1>
        <p>Welcome! Let's get your website set up step by step.</p>
        
        <div class="step">
            <h3><span class="step-number">1</span> Current Status</h3>
            <p>You're accessing from: <strong><?php echo htmlspecialchars($_SERVER['REQUEST_URI']); ?></strong></p>
            <p>Document Root: <code><?php echo htmlspecialchars($_SERVER['DOCUMENT_ROOT']); ?></code></p>
            
            <div class="url-box">
                <strong>Available URLs:</strong><br>
                • <a href="/fctcns-website/public/" style="color: #7FB285;">Homepage</a><br>
                • <a href="/fctcns-website/public/database/install" style="color: #7FB285;">Database Installation</a><br>
                • <a href="/fctcns-website/public/database/test" style="color: #7FB285;">Database Test</a><br>
                • <a href="/fctcns-website/public/admin" style="color: #7FB285;">Admin Login</a>
            </div>
        </div>
        
        <div class="step">
            <h3><span class="step-number">2</span> Database Setup</h3>
            
            <?php
            // Check if .env file exists
            $envExists = file_exists(dirname(__DIR__, 2) . '/.env');
            ?>
            
            <p>.env file: 
                <?php if ($envExists): ?>
                    <span class="success">✓ Found</span>
                <?php else: ?>
                    <span class="error">✗ Missing</span> 
                    <a href="#" onclick="copyEnvTemplate()" class="btn btn-secondary" style="margin-left: 10px; padding: 5px 10px; font-size: 0.9em;">Copy Template</a>
                <?php endif; ?>
            </p>
            
            <p>
                <a href="/fctcns-website/public/database/install" class="btn">Run Database Installation</a>
                <a href="/fctcns-website/public/database/test" class="btn btn-secondary">Test Database Connection</a>
            </p>
            
            <div class="warning">
                <strong>Note:</strong> The virtual host (<code>fctcns.local</code>) may not be working. 
                Use the URLs above instead. We'll fix this if needed.
            </div>
        </div>
        
        <div class="step">
            <h3><span class="step-number">3</span> Quick Test</h3>
            <p>Test if everything is working:</p>
            <p>
                <a href="/fctcns-website/public/" class="btn">Test Homepage</a>
                <a href="/fctcns-website/public/admin" class="btn btn-secondary">Test Admin Login</a>
            </p>
        </div>
        
        <div class="step">
            <h3><span class="step-number">4</span> Troubleshooting</h3>
            
            <h4>If database installation fails:</h4>
            <ol>
                <li>Open XAMPP Control Panel</li>
                <li>Make sure MySQL is running (green "Running" status)</li>
                <li>Open phpMyAdmin: <a href="http://localhost/phpmyadmin" target="_blank">http://localhost/phpmyadmin</a></li>
                <li>Create database manually: <code>fctcns_main</code></li>
                <li>Run the installation script again</li>
            </ol>
            
            <h4>If .env file is missing:</h4>
            <pre id="envTemplate">
# APPLICATION CONFIGURATION
APP_NAME="FCT College of Nursing Sciences"
APP_ENV=development
APP_URL=http://localhost/fctcns-website/public

# DATABASE CONFIGURATION (XAMPP Defaults)
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=fctcns_main
DB_USERNAME=root
DB_PASSWORD=

# ADMIN CREDENTIALS
ADMIN_USERNAME=admin
ADMIN_EMAIL=admin@fctcns.edu.ng</pre>
            <button onclick="copyEnvTemplate()" class="btn btn-secondary">Copy .env Template</button>
        </div>
        
        <div style="text-align: center; margin-top: 30px; padding-top: 20px; border-top: 1px solid #E1E8ED;">
            <p><strong>Ready to proceed?</strong> After database setup, we'll continue with Stage 3.</p>
            <a href="/fctcns-website/public/" class="btn">Go to Homepage</a>
        </div>
    </div>
    
    <script>
    function copyEnvTemplate() {
        const template = document.getElementById('envTemplate').textContent;
        navigator.clipboard.writeText(template).then(() => {
            alert('.env template copied to clipboard! Paste it into C:/xampp/htdocs/fctcns-website/.env');
        });
    }
    
    // Test if we can reach the server
    fetch('/fctcns-website/public/')
        .then(response => {
            console.log('Server reachable:', response.status);
        })
        .catch(error => {
            console.error('Server not reachable:', error);
        });
    </script>
</body>
</html>