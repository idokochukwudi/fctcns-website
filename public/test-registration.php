<?php
// Test Registration Flow - Production Version
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Try different possible paths for production
$possiblePaths = [
    '/home2/fctcnsed/fctcns-app/app/config/constants.php',  // Your production path
    dirname(__DIR__) . '/app/config/constants.php',          // Local development path
    __DIR__ . '/../app/config/constants.php'                 // Alternative relative path
];

$constantsLoaded = false;
foreach ($possiblePaths as $path) {
    if (file_exists($path)) {
        require_once $path;
        $constantsLoaded = true;
        echo "<!-- Loaded constants from: $path -->\n";
        break;
    }
}

if (!$constantsLoaded) {
    die('ERROR: Could not load constants.php. Tried paths: ' . implode(', ', $possiblePaths));
}

require_once APP_PATH . '/config/database.php';
require_once APP_PATH . '/models/application/ApplicantModel.php';

session_start();

$database = Database::getInstance();
$db = $database->getConnection();
$applicantModel = new ApplicantModel();

// Generate unique test data
$testEmail = 'test_' . time() . '@example.com';
$testPhone = '080' . rand(10000000, 99999999);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration Test Tool</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .path-info { background: #f0f0f0; padding: 10px; border-radius: 5px; font-family: monospace; font-size: 12px; }
    </style>
</head>
<body>
    <div class="container mt-5">
        <h1>Registration Test Tool</h1>
        
        <div class="path-info mb-3">
            <strong>Server Paths:</strong><br>
            DOCUMENT_ROOT: <?php echo $_SERVER['DOCUMENT_ROOT']; ?><br>
            SCRIPT_FILENAME: <?php echo $_SERVER['SCRIPT_FILENAME']; ?><br>
            __DIR__: <?php echo __DIR__; ?>
        </div>
        
        <div class="row mt-4">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        Test Registration
                    </div>
                    <div class="card-body">
                        <form id="testForm" onsubmit="event.preventDefault(); testRegistration();">
                            <div class="mb-3">
                                <label class="form-label">Email</label>
                                <input type="email" class="form-control" id="email" value="<?php echo $testEmail; ?>">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Phone</label>
                                <input type="text" class="form-control" id="phone" value="<?php echo $testPhone; ?>">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Password</label>
                                <input type="text" class="form-control" id="password" value="password123">
                            </div>
                            <button type="submit" class="btn btn-primary">
                                Test Registration
                            </button>
                        </form>
                        <div id="result" class="mt-3"></div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header bg-info text-white">
                        Database Check
                    </div>
                    <div class="card-body">
                        <?php
                        // Check applicants table
                        try {
                            $stmt = $db->query("SELECT COUNT(*) as count FROM applicants");
                            $count = $stmt->fetch()['count'];
                            echo "<p class='text-success'>✓ Applicants table has $count records</p>";
                            
                            // Show recent applicants
                            $stmt = $db->query("SELECT id, email, phone, email_verified, created_at FROM applicants ORDER BY id DESC LIMIT 5");
                            $recent = $stmt->fetchAll();
                            
                            if ($recent) {
                                echo "<h6>Recent Applicants:</h6>";
                                echo "<pre>" . print_r($recent, true) . "</pre>";
                            }
                        } catch (Exception $e) {
                            echo "<p class='text-danger'>Error: " . $e->getMessage() . "</p>";
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
    function testRegistration() {
        var email = document.getElementById('email').value;
        var phone = document.getElementById('phone').value;
        var password = document.getElementById('password').value;
        
        document.getElementById('result').innerHTML = '<div class="alert alert-info">Testing...</div>';
        
        var formData = new FormData();
        formData.append('email', email);
        formData.append('phone', phone);
        formData.append('password', password);
        formData.append('confirm_password', password);
        formData.append('terms', '1');
        formData.append('csrf_token', '<?php echo bin2hex(random_bytes(32)); ?>');
        
        fetch('/apply/register', {
            method: 'POST',
            body: formData
        })
        .then(response => {
            console.log('Response status:', response.status);
            console.log('Response headers:', response.headers);
            
            if (response.redirected) {
                document.getElementById('result').innerHTML = 
                    '<div class="alert alert-success">✓ Registration successful! Redirected to: ' + response.url + '</div>';
            } else {
                return response.text().then(text => {
                    return { text: text, status: response.status };
                });
            }
        })
        .then(data => {
            if (data) {
                document.getElementById('result').innerHTML = 
                    '<div class="alert alert-danger">✗ Registration failed (Status: ' + data.status + '):<br><pre>' + data.text + '</pre></div>';
            }
        })
        .catch(error => {
            document.getElementById('result').innerHTML = 
                '<div class="alert alert-danger">Error: ' + error + '</div>';
        });
    }
    </script>
</body>
</html>