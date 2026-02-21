<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($pageTitle ?? 'Examination Slip Verification Portal - FCT College of Nursing Sciences'); ?></title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome 6 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --primary-color: #6B4E9B;
            --secondary-color: #4A3B6E;
            --success-color: #28a745;
            --danger-color: #dc3545;
            --warning-color: #ffc107;
            --info-color: #17a2b8;
        }
        
        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            padding: 20px 0;
        }
        
        .verification-card {
            border: none;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            animation: slideIn 0.5s ease-out;
            max-width: 900px;
            margin: 0 auto;
        }
        
        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .verification-header {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            color: white;
            padding: 40px 30px;
            text-align: center;
            position: relative;
            overflow: hidden;
        }
        
        .verification-header::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -50%;
            width: 200%;
            height: 200%;
            background: rgba(255,255,255,0.1);
            transform: rotate(45deg);
            animation: shine 3s infinite;
        }
        
        @keyframes shine {
            0% { transform: translateX(-100%) rotate(45deg); }
            100% { transform: translateX(100%) rotate(45deg); }
        }
        
        .header-icon {
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
            50% { transform: scale(1.05); }
            100% { transform: scale(1); }
        }
        
        .verification-body {
            padding: 40px;
            background: white;
        }
        
        .method-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin: 30px 0;
        }
        
        .method-card {
            border: 2px solid #e9ecef;
            border-radius: 15px;
            padding: 25px 20px;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }
        
        .method-card:hover {
            border-color: var(--primary-color);
            transform: translateY(-5px);
            box-shadow: 0 10px 30px rgba(107, 78, 155, 0.2);
        }
        
        .method-card.active {
            border-color: var(--primary-color);
            background: #f8f9ff;
            box-shadow: 0 10px 30px rgba(107, 78, 155, 0.2);
        }
        
        .method-card.active::after {
            content: '✓';
            position: absolute;
            top: 10px;
            right: 10px;
            width: 25px;
            height: 25px;
            background: var(--primary-color);
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 14px;
        }
        
        .method-icon {
            width: 70px;
            height: 70px;
            background: #f0f0f0;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 15px;
            font-size: 30px;
            color: var(--primary-color);
            transition: all 0.3s ease;
        }
        
        .method-card:hover .method-icon {
            background: var(--primary-color);
            color: white;
            transform: rotate(360deg);
        }
        
        .method-title {
            font-weight: 600;
            margin-bottom: 5px;
        }
        
        .method-description {
            font-size: 12px;
            color: #6c757d;
            line-height: 1.4;
        }
        
        .verification-form {
            display: none;
            margin-top: 30px;
            padding: 30px;
            background: #f8f9fa;
            border-radius: 15px;
            animation: fadeIn 0.5s ease;
        }
        
        .verification-form.active {
            display: block;
        }
        
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .form-title {
            font-size: 18px;
            font-weight: 600;
            margin-bottom: 20px;
            color: var(--primary-color);
        }
        
        .input-group-custom {
            position: relative;
            margin-bottom: 20px;
        }
        
        .input-group-custom i {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--primary-color);
            z-index: 10;
        }
        
        .input-group-custom input {
            padding-left: 45px;
            height: 50px;
            border: 2px solid #e9ecef;
            border-radius: 10px;
            transition: all 0.3s ease;
        }
        
        .input-group-custom input:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(107, 78, 155, 0.1);
        }
        
        .scan-area {
            border: 3px dashed #ccc;
            border-radius: 20px;
            padding: 40px;
            text-align: center;
            background: #f9f9f9;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-bottom: 20px;
        }
        
        .scan-area:hover {
            border-color: var(--primary-color);
            background: #f0f3ff;
        }
        
        .scan-area i {
            font-size: 50px;
            color: var(--primary-color);
            margin-bottom: 15px;
        }
        
        .scanner-container {
            display: none;
            margin-top: 20px;
        }
        
        .scanner-container.active {
            display: block;
        }
        
        #preview {
            width: 100%;
            border-radius: 15px;
            overflow: hidden;
            border: 3px solid var(--primary-color);
        }
        
        .btn-verify {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
            border: none;
            padding: 12px 30px;
            border-radius: 10px;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        
        .btn-verify:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 30px rgba(107, 78, 155, 0.3);
        }
        
        .btn-verify:disabled {
            opacity: 0.6;
            cursor: not-allowed;
        }
        
        .recent-verifications {
            margin-top: 30px;
            padding: 20px;
            background: #f8f9fa;
            border-radius: 15px;
        }
        
        .verification-item {
            display: flex;
            align-items: center;
            padding: 10px;
            border-bottom: 1px solid #dee2e6;
        }
        
        .verification-item:last-child {
            border-bottom: none;
        }
        
        .security-badge {
            display: inline-flex;
            align-items: center;
            padding: 5px 15px;
            background: #e8f5e9;
            color: #2e7d32;
            border-radius: 50px;
            font-size: 12px;
            font-weight: 600;
        }
        
        .stats-card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 20px;
            border-radius: 15px;
            margin-bottom: 20px;
        }
        
        .floating-help {
            position: fixed;
            bottom: 30px;
            right: 30px;
            z-index: 1000;
        }
        
        .help-button {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            background: var(--primary-color);
            color: white;
            border: none;
            box-shadow: 0 5px 20px rgba(107, 78, 155, 0.4);
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
        }
        
        .help-button:hover {
            transform: scale(1.1);
            box-shadow: 0 10px 30px rgba(107, 78, 155, 0.6);
        }
        
        .help-tooltip {
            position: absolute;
            bottom: 70px;
            right: 0;
            background: white;
            padding: 15px 25px;
            border-radius: 10px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.2);
            width: 300px;
            display: none;
        }
        
        .floating-help:hover .help-tooltip {
            display: block;
        }
        
        .alert {
            padding: 15px;
            background: #f8d7da;
            color: #721c24;
            border-radius: 10px;
            margin-bottom: 20px;
            display: none;
            align-items: center;
            gap: 10px;
        }
        
        .alert.show {
            display: flex;
        }
        
        .alert i {
            font-size: 20px;
        }
        
        @media (max-width: 768px) {
            .method-grid {
                grid-template-columns: repeat(2, 1fr);
            }
            
            .verification-body {
                padding: 20px;
            }
        }
        
        @media (max-width: 480px) {
            .method-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="verification-card">
            <div class="verification-header">
                <div class="header-icon">
                    <i class="fas fa-shield-alt"></i>
                </div>
                <h1 class="h2 mb-2">Examination Slip Verification</h1>
                <p class="mb-0 opacity-75">FCT College of Nursing Sciences - Official Verification Portal</p>
                <div class="mt-3">
                    <span class="security-badge">
                        <i class="fas fa-lock me-2"></i> Secure Verification
                    </span>
                </div>
            </div>
            
            <div class="verification-body">
                <!-- Error Alert -->
                <div class="alert" id="errorAlert">
                    <i class="fas fa-exclamation-circle"></i>
                    <span id="errorMessage"></span>
                </div>
                
                <!-- Stats Overview -->
                <div class="row g-3 mb-4">
                    <div class="col-md-4">
                        <div class="stats-card text-center">
                            <i class="fas fa-check-circle fa-2x mb-2"></i>
                            <h3 class="h4 mb-0">15,234+</h3>
                            <small>Verifications Completed</small>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="stats-card text-center" style="background: linear-gradient(135deg, #28a745, #20c997);">
                            <i class="fas fa-clock fa-2x mb-2"></i>
                            <h3 class="h4 mb-0">99.9%</h3>
                            <small>Success Rate</small>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="stats-card text-center" style="background: linear-gradient(135deg, #fd7e14, #ffc107);">
                            <i class="fas fa-bolt fa-2x mb-2"></i>
                            <h3 class="h4 mb-0">&lt; 2 sec</h3>
                            <small>Average Response</small>
                        </div>
                    </div>
                </div>

                <!-- Instruction Text -->
                <div class="text-center mb-4">
                    <p class="text-muted">Choose your preferred verification method below:</p>
                </div>
                
                <!-- Verification Methods Grid -->
                <div class="method-grid" id="methodGrid">
                    <?php foreach ($verification_methods as $key => $method): ?>
                    <div class="method-card <?php echo $method['active'] && $key === 'qr' ? 'active' : ''; ?>" 
                         onclick="switchMethod('<?php echo $key; ?>')"
                         data-method="<?php echo $key; ?>">
                        <div class="method-icon">
                            <i class="fas fa-<?php echo $method['icon']; ?>"></i>
                        </div>
                        <h6 class="method-title"><?php echo $method['title']; ?></h6>
                        <p class="method-description"><?php echo $method['description']; ?></p>
                    </div>
                    <?php endforeach; ?>
                </div>
                
                <!-- QR Code Scanner Method -->
                <div id="qrMethod" class="verification-form active">
                    <div class="form-title">
                        <i class="fas fa-qrcode me-2"></i> Scan QR Code
                    </div>
                    
                    <div class="scan-area" onclick="startScanner()">
                        <i class="fas fa-camera"></i>
                        <h5>Click to Start Camera</h5>
                        <p class="text-muted mb-0 small">Position the QR code in front of your camera</p>
                    </div>
                    
                    <div class="scanner-container" id="scannerContainer">
                        <video id="preview" style="width: 100%; border-radius: 10px;"></video>
                        <div class="text-center mt-3">
                            <button class="btn btn-secondary" onclick="stopScanner()">
                                <i class="fas fa-times me-2"></i> Cancel
                            </button>
                        </div>
                    </div>
                    
                    <div class="text-center mt-3">
                        <small class="text-muted">
                            <i class="fas fa-info-circle me-1"></i>
                            Ensure the QR code is clearly visible and well-lit
                        </small>
                    </div>
                </div>
                
                <!-- Slip Number Method -->
                <div id="slipMethod" class="verification-form">
                    <div class="form-title">
                        <i class="fas fa-ticket-alt me-2"></i> Enter Slip Number
                    </div>
                    
                    <form action="/application-verify/slip" method="GET" onsubmit="return validateSlipForm()">
                        <div class="input-group-custom">
                            <i class="fas fa-hashtag"></i>
                            <input type="text" class="form-control" id="slipNumber" name="slipNumber" 
                                   placeholder="e.g., SLIP-2025-00001" required
                                   pattern="[A-Za-z0-9\-]+" 
                                   title="Enter a valid slip number (letters, numbers, and hyphens only)">
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Verification Code</label>
                            <div class="row g-2">
                                <div class="col-md-6">
                                    <input type="text" class="form-control" id="captcha" 
                                           placeholder="Enter code" required>
                                </div>
                                <div class="col-md-6">
                                    <img src="/captcha?t=<?php echo time(); ?>" alt="CAPTCHA" 
                                         class="img-fluid rounded" style="height: 50px;">
                                </div>
                            </div>
                        </div>
                        
                        <button type="submit" class="btn btn-verify w-100">
                            <i class="fas fa-search me-2"></i> Verify Slip
                        </button>
                    </form>
                </div>
                
                <!-- JAMB Number Method -->
                <div id="jambMethod" class="verification-form">
                    <div class="form-title">
                        <i class="fas fa-graduation-cap me-2"></i> Enter JAMB Number
                    </div>
                    
                    <form action="/application-verify/jamb" method="GET" onsubmit="return validateJambForm()">
                        <div class="input-group-custom">
                            <i class="fas fa-hashtag"></i>
                            <input type="text" class="form-control" id="jambNumber" name="jambNumber" 
                                   placeholder="e.g., 202550805685FF" required
                                   pattern="[A-Z0-9]{10,14}" 
                                   title="Enter a valid JAMB registration number">
                        </div>
                        
                        <div class="mb-3 form-check">
                            <input type="checkbox" class="form-check-input" id="agreeTerms" required>
                            <label class="form-check-label" for="agreeTerms">
                                I confirm that I am verifying this document for legitimate purposes
                            </label>
                        </div>
                        
                        <button type="submit" class="btn btn-verify w-100">
                            <i class="fas fa-search me-2"></i> Verify JAMB
                        </button>
                    </form>
                </div>
                
                <!-- Application Number Method -->
                <div id="applicationMethod" class="verification-form">
                    <div class="form-title">
                        <i class="fas fa-file-alt me-2"></i> Enter Application Number
                    </div>
                    
                    <form action="/application-verify/application" method="GET" onsubmit="return validateAppForm()">
                        <div class="input-group-custom">
                            <i class="fas fa-hashtag"></i>
                            <input type="text" class="form-control" id="appNumber" name="appNumber" 
                                   placeholder="e.g., APP-2025-00001" required
                                   pattern="[A-Za-z0-9\-]+" 
                                   title="Enter a valid application number">
                        </div>
                        
                        <button type="submit" class="btn btn-verify w-100">
                            <i class="fas fa-search me-2"></i> Verify Application
                        </button>
                    </form>
                </div>
                
                <!-- Recent Verifications (Demo) -->
                <div class="recent-verifications">
                    <h6 class="mb-3">
                        <i class="fas fa-history me-2"></i> Recent Verifications
                    </h6>
                    <div class="verification-item">
                        <i class="fas fa-check-circle text-success me-3"></i>
                        <div class="flex-grow-1">
                            <small><strong>SLIP-2025-01234</strong> verified 2 minutes ago</small>
                        </div>
                        <span class="badge bg-success">Valid</span>
                    </div>
                    <div class="verification-item">
                        <i class="fas fa-check-circle text-success me-3"></i>
                        <div class="flex-grow-1">
                            <small><strong>SLIP-2025-01235</strong> verified 15 minutes ago</small>
                        </div>
                        <span class="badge bg-success">Valid</span>
                    </div>
                    <div class="verification-item">
                        <i class="fas fa-check-circle text-success me-3"></i>
                        <div class="flex-grow-1">
                            <small><strong>SLIP-2025-01236</strong> verified 1 hour ago</small>
                        </div>
                        <span class="badge bg-success">Valid</span>
                    </div>
                </div>
                
                <!-- Trust Indicators -->
                <div class="d-flex justify-content-between align-items-center mt-4 pt-3 border-top">
                    <div>
                        <i class="fas fa-shield-alt text-primary me-1"></i>
                        <small class="text-muted">SSL Encrypted</small>
                    </div>
                    <div>
                        <i class="fas fa-clock text-primary me-1"></i>
                        <small class="text-muted">Real-time Verification</small>
                    </div>
                    <div>
                        <i class="fas fa-database text-primary me-1"></i>
                        <small class="text-muted">Secure Database</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Floating Help Button -->
    <div class="floating-help">
        <button class="help-button" onclick="toggleHelp()">
            <i class="fas fa-question"></i>
        </button>
        <div class="help-tooltip" id="helpTooltip">
            <h6 class="mb-2"><i class="fas fa-headset me-2"></i> Need Help?</h6>
            <p class="small text-muted mb-2">Contact our verification support:</p>
            <p class="small mb-1">
                <i class="fas fa-phone me-2"></i> <?php echo $support_phone ?? '07039837749'; ?>
            </p>
            <p class="small mb-1">
                <i class="fas fa-envelope me-2"></i> <?php echo $support_email ?? 'verification@fctcns.edu.ng'; ?>
            </p>
            <hr>
            <p class="small text-muted mb-0">
                <i class="fas fa-clock me-2"></i> Mon-Fri: 8am - 5pm
            </p>
        </div>
    </div>
    
    <!-- QR Scanner Library -->
    <script src="https://rawgit.com/schmich/instascan-builds/master/instascan.min.js"></script>
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        let scanner = null;
        let activeScanner = false;
        
        // Switch between verification methods
        function switchMethod(method) {
            // Update active method cards
            document.querySelectorAll('.method-card').forEach(card => {
                card.classList.remove('active');
            });
            document.querySelector(`[data-method="${method}"]`).classList.add('active');
            
            // Hide all forms
            document.querySelectorAll('.verification-form').forEach(form => {
                form.classList.remove('active');
            });
            
            // Show selected form
            document.getElementById(method + 'Method').classList.add('active');
            
            // Stop scanner if active
            if (activeScanner) {
                stopScanner();
            }
        }
        
        // Start QR scanner
        function startScanner() {
            const scanArea = document.querySelector('.scan-area');
            const scannerContainer = document.getElementById('scannerContainer');
            
            scanArea.style.display = 'none';
            scannerContainer.classList.add('active');
            
            scanner = new Instascan.Scanner({ 
                video: document.getElementById('preview'),
                mirror: false,
                scanPeriod: 1
            });
            
            scanner.addListener('scan', function(content) {
                // Extract slip number from QR content
                let slipNumber = content;
                
                // If it's a URL, extract the last part
                if (content.includes('/')) {
                    const parts = content.split('/');
                    slipNumber = parts[parts.length - 1];
                }
                
                // Clean the slip number
                slipNumber = slipNumber.replace(/[^A-Za-z0-9\-]/g, '');
                
                // Stop scanner before redirect
                stopScanner();
                
                // Redirect to verification page
                window.location.href = '/application-verify/slip/' + encodeURIComponent(slipNumber);
            });
            
            Instascan.Camera.getCameras().then(function(cameras) {
                if (cameras.length > 0) {
                    // Prefer back camera if available
                    const backCamera = cameras.find(camera => camera.name.toLowerCase().includes('back'));
                    scanner.start(backCamera || cameras[0]);
                    activeScanner = true;
                } else {
                    showError('No cameras found on this device.');
                    stopScanner();
                }
            }).catch(function(e) {
                console.error(e);
                showError('Camera access denied or not available. Please ensure you have granted camera permissions.');
                stopScanner();
            });
        }
        
        // Stop QR scanner
        function stopScanner() {
            if (scanner) {
                scanner.stop();
            }
            activeScanner = false;
            
            document.getElementById('scannerContainer').classList.remove('active');
            document.querySelector('.scan-area').style.display = 'block';
        }
        
        // Show error message
        function showError(message) {
            const errorAlert = document.getElementById('errorAlert');
            const errorMessage = document.getElementById('errorMessage');
            
            errorMessage.textContent = message;
            errorAlert.classList.add('show');
            
            // Auto-hide after 5 seconds
            setTimeout(() => {
                errorAlert.classList.remove('show');
            }, 5000);
        }
        
        // Form validations
        function validateSlipForm() {
            const slipNumber = document.getElementById('slipNumber').value.trim();
            const captcha = document.getElementById('captcha').value.trim();
            
            if (!slipNumber) {
                showError('Please enter a slip number');
                return false;
            }
            
            if (!/^[A-Za-z0-9\-]+$/.test(slipNumber)) {
                showError('Invalid slip number format. Use letters, numbers, and hyphens only.');
                return false;
            }
            
            if (!captcha) {
                showError('Please enter the verification code');
                return false;
            }
            
            return true;
        }
        
        function validateJambForm() {
            const jambNumber = document.getElementById('jambNumber').value.trim();
            const agreeTerms = document.getElementById('agreeTerms').checked;
            
            if (!jambNumber) {
                showError('Please enter your JAMB number');
                return false;
            }
            
            if (jambNumber.length < 10) {
                showError('JAMB number must be at least 10 characters');
                return false;
            }
            
            if (!agreeTerms) {
                showError('You must agree to the verification terms');
                return false;
            }
            
            return true;
        }
        
        function validateAppForm() {
            const appNumber = document.getElementById('appNumber').value.trim();
            
            if (!appNumber) {
                showError('Please enter your application number');
                return false;
            }
            
            if (!/^[A-Za-z0-9\-]+$/.test(appNumber)) {
                showError('Invalid application number format. Use letters, numbers, and hyphens only.');
                return false;
            }
            
            return true;
        }
        
        // Toggle help tooltip
        function toggleHelp() {
            const tooltip = document.getElementById('helpTooltip');
            if (tooltip.style.display === 'block') {
                tooltip.style.display = 'none';
            } else {
                tooltip.style.display = 'block';
                setTimeout(() => {
                    tooltip.style.display = 'none';
                }, 5000);
            }
        }
        
        // Handle form input formatting
        document.getElementById('slipNumber')?.addEventListener('input', function(e) {
            this.value = this.value.toUpperCase();
        });
        
        document.getElementById('jambNumber')?.addEventListener('input', function(e) {
            this.value = this.value.toUpperCase();
        });
        
        document.getElementById('appNumber')?.addEventListener('input', function(e) {
            this.value = this.value.toUpperCase();
        });
        
        // Prevent form resubmission on page refresh
        if (window.history.replaceState) {
            window.history.replaceState(null, null, window.location.href);
        }
        
        // Check URL parameters for scan trigger
        document.addEventListener('DOMContentLoaded', function() {
            const urlParams = new URLSearchParams(window.location.search);
            if (urlParams.get('scan') === '1') {
                switchMethod('qr');
                setTimeout(startScanner, 500);
            }
        });
    </script>
</body>
</html>