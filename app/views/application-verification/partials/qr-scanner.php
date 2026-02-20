<?php
/**
 * QR Scanner Partial
 * Reusable QR code scanner component for application verification
 * 
 * @package FCT_CNS
 * @var array $params - Configuration parameters (optional)
 *   - scanner_id: Unique ID for the scanner instance (default: 'qr-scanner')
 *   - container_id: Container element ID (default: 'qr-scanner-container')
 *   - preview_id: Video preview element ID (default: 'qr-preview')
 *   - on_scan: JavaScript callback function name (default: 'handleQRScan')
 *   - auto_start: Whether to auto-start scanner (default: false)
 *   - width: Scanner width (default: '100%')
 *   - height: Scanner height (default: 'auto')
 *   - show_controls: Show camera controls (default: true)
 */

// Set default parameters
$scannerId = $params['scanner_id'] ?? 'qr-scanner-' . uniqid();
$containerId = $params['container_id'] ?? 'qr-scanner-container-' . uniqid();
$previewId = $params['preview_id'] ?? 'qr-preview-' . uniqid();
$onScanCallback = $params['on_scan'] ?? 'handleQRScan';
$autoStart = $params['auto_start'] ?? false;
$width = $params['width'] ?? '100%';
$height = $params['height'] ?? 'auto';
$showControls = $params['show_controls'] ?? true;
$buttonText = $params['button_text'] ?? 'Scan QR Code';
$cameraMessage = $params['camera_message'] ?? 'Position the QR code in front of your camera';
?>

<!-- QR Scanner Styles -->
<style>
    .qr-scanner-container {
        width: <?php echo $width; ?>;
        max-width: 600px;
        margin: 0 auto;
        background: #fff;
        border-radius: 15px;
        overflow: hidden;
        box-shadow: 0 10px 30px rgba(0,0,0,0.1);
    }
    
    .qr-scanner-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 15px 20px;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }
    
    .qr-scanner-header h6 {
        margin: 0;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    
    .qr-scanner-header i {
        font-size: 20px;
    }
    
    .scanner-status {
        background: rgba(255,255,255,0.2);
        padding: 5px 12px;
        border-radius: 50px;
        font-size: 12px;
        display: flex;
        align-items: center;
        gap: 5px;
    }
    
    .status-dot {
        width: 8px;
        height: 8px;
        border-radius: 50%;
        background: #28a745;
        animation: pulse 1.5s infinite;
    }
    
    .status-dot.inactive {
        background: #dc3545;
        animation: none;
    }
    
    @keyframes pulse {
        0% { opacity: 1; transform: scale(1); }
        50% { opacity: 0.5; transform: scale(1.2); }
        100% { opacity: 1; transform: scale(1); }
    }
    
    .qr-preview-container {
        background: #000;
        position: relative;
        min-height: 300px;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    #<?php echo $previewId; ?> {
        width: 100%;
        height: <?php echo $height; ?>;
        min-height: 300px;
        object-fit: cover;
        background: #1a1a1a;
    }
    
    .scanner-overlay {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        pointer-events: none;
        border: 3px solid rgba(255,255,255,0.3);
        box-shadow: 0 0 0 9999px rgba(0,0,0,0.5);
    }
    
    .scanning-area {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        width: 200px;
        height: 200px;
        border: 3px solid #28a745;
        box-shadow: 0 0 20px rgba(40, 167, 69, 0.5);
        animation: scan 2s infinite;
    }
    
    @keyframes scan {
        0% { box-shadow: 0 0 20px rgba(40, 167, 69, 0.5); }
        50% { box-shadow: 0 0 40px rgba(40, 167, 69, 0.8); }
        100% { box-shadow: 0 0 20px rgba(40, 167, 69, 0.5); }
    }
    
    .scan-line {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 3px;
        background: linear-gradient(90deg, transparent, #28a745, transparent);
        animation: scan-line 2s linear infinite;
    }
    
    @keyframes scan-line {
        0% { top: 0; }
        50% { top: 100%; }
        100% { top: 0; }
    }
    
    .scanner-placeholder {
        text-align: center;
        padding: 50px 20px;
        background: #f8f9fa;
        border-radius: 10px;
    }
    
    .scanner-placeholder i {
        font-size: 60px;
        color: #6c757d;
        margin-bottom: 15px;
    }
    
    .camera-selector {
        padding: 15px;
        background: #f8f9fa;
        border-top: 1px solid #dee2e6;
    }
    
    .camera-selector select {
        width: 100%;
        padding: 8px 12px;
        border: 2px solid #e9ecef;
        border-radius: 8px;
        font-size: 14px;
    }
    
    .scanner-controls {
        padding: 15px;
        display: flex;
        gap: 10px;
        justify-content: center;
        background: #f8f9fa;
        border-top: 1px solid #dee2e6;
        flex-wrap: wrap;
    }
    
    .btn-scanner {
        padding: 10px 20px;
        border: none;
        border-radius: 8px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }
    
    .btn-scanner-primary {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
    }
    
    .btn-scanner-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 30px rgba(102, 126, 234, 0.3);
    }
    
    .btn-scanner-secondary {
        background: #6c757d;
        color: white;
    }
    
    .btn-scanner-secondary:hover {
        background: #5a6268;
    }
    
    .btn-scanner-success {
        background: #28a745;
        color: white;
    }
    
    .btn-scanner-success:hover {
        background: #218838;
    }
    
    .btn-scanner-danger {
        background: #dc3545;
        color: white;
    }
    
    .btn-scanner-danger:hover {
        background: #c82333;
    }
    
    .scanner-instructions {
        padding: 10px 15px;
        background: #e8f4fd;
        border-radius: 8px;
        margin: 10px 15px;
        font-size: 13px;
    }
    
    .scanner-instructions i {
        color: #007bff;
        margin-right: 8px;
    }
    
    .scan-result {
        position: fixed;
        top: 20px;
        right: 20px;
        z-index: 9999;
        min-width: 300px;
        animation: slideInRight 0.3s ease;
    }
    
    @keyframes slideInRight {
        from {
            transform: translateX(100%);
            opacity: 0;
        }
        to {
            transform: translateX(0);
            opacity: 1;
        }
    }
    
    .recent-scans {
        padding: 15px;
        background: white;
        border-top: 1px solid #dee2e6;
    }
    
    .recent-scan-item {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 8px;
        border-bottom: 1px solid #f0f0f0;
        font-size: 13px;
    }
    
    .recent-scan-item:last-child {
        border-bottom: none;
    }
    
    .recent-scan-item i {
        color: #28a745;
        margin-right: 8px;
    }
    
    .recent-scan-time {
        color: #6c757d;
        font-size: 11px;
    }
</style>

<!-- QR Scanner Container -->
<div id="<?php echo $containerId; ?>" class="qr-scanner-container">
    <div class="qr-scanner-header">
        <h6>
            <i class="fas fa-qrcode"></i>
            QR Code Scanner
        </h6>
        <div class="scanner-status" id="scanner-status-<?php echo $scannerId; ?>">
            <span class="status-dot inactive" id="status-dot-<?php echo $scannerId; ?>"></span>
            <span id="status-text-<?php echo $scannerId; ?>">Inactive</span>
        </div>
    </div>
    
    <!-- Scanner Preview Area -->
    <div class="qr-preview-container" id="preview-container-<?php echo $scannerId; ?>">
        <video id="<?php echo $previewId; ?>" playsinline></video>
        <div class="scanner-overlay"></div>
        <div class="scanning-area" id="scanning-area-<?php echo $scannerId; ?>" style="display: none;">
            <div class="scan-line"></div>
        </div>
    </div>
    
    <!-- Camera Selector -->
    <?php if ($showControls): ?>
    <div class="camera-selector">
        <select id="camera-select-<?php echo $scannerId; ?>" class="form-select">
            <option value="">Loading cameras...</option>
        </select>
    </div>
    <?php endif; ?>
    
    <!-- Scanner Instructions -->
    <div class="scanner-instructions">
        <i class="fas fa-info-circle"></i>
        <?php echo $cameraMessage; ?>
    </div>
    
    <!-- Control Buttons -->
    <div class="scanner-controls">
        <button class="btn-scanner btn-scanner-primary" onclick="startScanner('<?php echo $scannerId; ?>', '<?php echo $previewId; ?>')">
            <i class="fas fa-play"></i> Start Scanner
        </button>
        <button class="btn-scanner btn-scanner-secondary" onclick="stopScanner('<?php echo $scannerId; ?>')">
            <i class="fas fa-stop"></i> Stop
        </button>
        <button class="btn-scanner btn-scanner-success" onclick="switchCamera('<?php echo $scannerId; ?>')">
            <i class="fas fa-sync-alt"></i> Switch Camera
        </button>
    </div>
    
    <!-- Recent Scans (optional) -->
    <div class="recent-scans" id="recent-scans-<?php echo $scannerId; ?>" style="display: none;">
        <small class="text-muted d-block mb-2">
            <i class="fas fa-history me-1"></i> Recent Scans
        </small>
        <div id="recent-scans-list-<?php echo $scannerId; ?>"></div>
    </div>
</div>

<!-- QR Scanner JavaScript -->
<script src="https://rawgit.com/schmich/instascan-builds/master/instascan.min.js"></script>

<script>
(function() {
    // Scanner instances storage
    window.qrScanners = window.qrScanners || {};
    
    // Initialize scanner
    function initScanner(scannerId, previewId) {
        if (window.qrScanners[scannerId]) {
            return window.qrScanners[scannerId];
        }
        
        const scanner = new Instascan.Scanner({
            video: document.getElementById(previewId),
            mirror: false,
            scanPeriod: 1,
            continuous: true
        });
        
        window.qrScanners[scannerId] = scanner;
        return scanner;
    }
    
    // Start scanner
    window.startScanner = function(scannerId, previewId) {
        const scanner = initScanner(scannerId, previewId);
        const statusDot = document.getElementById('status-dot-' + scannerId);
        const statusText = document.getElementById('status-text-' + scannerId);
        const scanningArea = document.getElementById('scanning-area-' + scannerId);
        
        Instascan.Camera.getCameras().then(cameras => {
            if (cameras.length > 0) {
                // Update camera selector
                const select = document.getElementById('camera-select-' + scannerId);
                if (select) {
                    select.innerHTML = '';
                    cameras.forEach((camera, index) => {
                        const option = document.createElement('option');
                        option.value = index;
                        option.text = camera.name || `Camera ${index + 1}`;
                        select.appendChild(option);
                    });
                }
                
                // Start with back camera if available
                const backCamera = cameras.find(camera => 
                    camera.name.toLowerCase().includes('back') ||
                    camera.name.toLowerCase().includes('rear')
                );
                
                scanner.start(backCamera || cameras[0]).then(() => {
                    statusDot.className = 'status-dot';
                    statusText.textContent = 'Active';
                    if (scanningArea) scanningArea.style.display = 'block';
                    
                    // Show scanning area animation
                    document.querySelector('.scanning-area').style.display = 'block';
                });
            } else {
                alert('No cameras found on this device.');
                statusDot.className = 'status-dot inactive';
                statusText.textContent = 'No Camera';
            }
        }).catch(error => {
            console.error('Camera access error:', error);
            alert('Error accessing camera. Please ensure you have granted camera permissions.');
            statusDot.className = 'status-dot inactive';
            statusText.textContent = 'Error';
        });
        
        // Handle scan
        scanner.addListener('scan', function(content) {
            // Update status
            statusDot.style.animation = 'none';
            statusDot.style.backgroundColor = '#28a745';
            setTimeout(() => {
                statusDot.style.animation = 'pulse 1.5s infinite';
            }, 1000);
            
            // Add to recent scans
            addRecentScan(scannerId, content);
            
            // FIXED: Removed the problematic audio beep
            
            // Call the callback function
            if (typeof window.<?php echo $onScanCallback; ?> === 'function') {
                window.<?php echo $onScanCallback; ?>(content, scannerId);
            } else {
                // Default handling
                console.log('QR Code scanned:', content);
                
                // Try to extract slip number
                let slipNumber = content;
                if (content.includes('/')) {
                    const parts = content.split('/');
                    slipNumber = parts[parts.length - 1];
                }
                
                // Clean the slip number
                slipNumber = slipNumber.replace(/[^A-Za-z0-9\-]/g, '');
                
                // Redirect if it looks like a valid slip
                if (slipNumber && slipNumber.length > 5) {
                    if (confirm('Redirect to verification page for: ' + slipNumber)) {
                        window.location.href = '/application-verify/slip/' + encodeURIComponent(slipNumber);
                    }
                }
            }
        });
    }
    
    // Stop scanner
    window.stopScanner = function(scannerId) {
        if (window.qrScanners[scannerId]) {
            window.qrScanners[scannerId].stop();
        }
        
        const statusDot = document.getElementById('status-dot-' + scannerId);
        const statusText = document.getElementById('status-text-' + scannerId);
        const scanningArea = document.getElementById('scanning-area-' + scannerId);
        
        statusDot.className = 'status-dot inactive';
        statusText.textContent = 'Inactive';
        if (scanningArea) scanningArea.style.display = 'none';
    };
    
    // Switch camera
    window.switchCamera = function(scannerId) {
        const select = document.getElementById('camera-select-' + scannerId);
        if (!select || !window.qrScanners[scannerId]) return;
        
        const selectedIndex = parseInt(select.value);
        if (isNaN(selectedIndex)) return;
        
        Instascan.Camera.getCameras().then(cameras => {
            if (cameras.length > selectedIndex) {
                window.qrScanners[scannerId].start(cameras[selectedIndex]);
            }
        });
    };
    
    // Add recent scan
    function addRecentScan(scannerId, content) {
        const recentScansDiv = document.getElementById('recent-scans-' + scannerId);
        const recentList = document.getElementById('recent-scans-list-' + scannerId);
        
        if (!recentScansDiv || !recentList) return;
        
        recentScansDiv.style.display = 'block';
        
        const scanItem = document.createElement('div');
        scanItem.className = 'recent-scan-item';
        
        let displayContent = content;
        if (content.length > 30) {
            displayContent = content.substring(0, 27) + '...';
        }
        
        scanItem.innerHTML = `
            <div>
                <i class="fas fa-qrcode"></i>
                <span>${displayContent}</span>
            </div>
            <span class="recent-scan-time">just now</span>
        `;
        
        recentList.insertBefore(scanItem, recentList.firstChild);
        
        // Keep only last 5 scans
        while (recentList.children.length > 5) {
            recentList.removeChild(recentList.lastChild);
        }
    }
    
    // Auto-start if enabled
    <?php if ($autoStart): ?>
    document.addEventListener('DOMContentLoaded', function() {
        setTimeout(function() {
            startScanner('<?php echo $scannerId; ?>', '<?php echo $previewId; ?>');
        }, 500);
    });
    <?php endif; ?>
})();
</script>