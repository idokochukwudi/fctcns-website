<?php
// debug-logs.php
session_start();

echo "<h1>CSRF Debug Information</h1>";
echo "<pre>";

// 1. Check Session::getCSRFToken()
echo "=== 1. Session::getCSRFToken() ===\n";
if (method_exists('Session', 'getCSRFToken')) {
    require_once __DIR__ . '/../app/config/session.php';
    $token = Session::getCSRFToken();
    echo "Session token: " . ($token ? substr($token, 0, 20) . "..." : "NULL") . "\n";
} else {
    echo "Session::getCSRFToken() method NOT FOUND\n";
}

// 2. Check $_SESSION['csrf_tokens']
echo "\n=== 2. Session CSRF Tokens ===\n";
if (isset($_SESSION['csrf_tokens'])) {
    echo "Count: " . count($_SESSION['csrf_tokens']) . "\n";
    foreach ($_SESSION['csrf_tokens'] as $token => $time) {
        $age = time() - $time;
        echo "Token: " . substr($token, 0, 20) . "...\n";
        echo "  Created: " . date('Y-m-d H:i:s', $time) . "\n";
        echo "  Age: " . $age . " seconds (" . ($age > 3600 ? "EXPIRED" : "VALID") . ")\n";
    }
} else {
    echo "No CSRF tokens in session\n";
}

// 3. Generate test token like Controller
echo "\n=== 3. Generate Test Token ===\n";
if (!isset($_SESSION['csrf_tokens'])) {
    $_SESSION['csrf_tokens'] = [];
}
$testToken = bin2hex(random_bytes(32));
$_SESSION['csrf_tokens'][$testToken] = time();
echo "Generated test token: " . substr($testToken, 0, 20) . "...\n";

// 4. Test form
echo "\n=== 4. Test Form ===\n";
echo '<form method="POST">
    <input type="hidden" name="csrf_token" value="' . $testToken . '">
    <button type="submit">Test Submit</button>
</form>';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    echo "\n=== After Submit ===\n";
    $postToken = $_POST['csrf_token'] ?? 'NOT SET';
    echo "POST token: " . ($postToken ? substr($postToken, 0, 20) . "..." : "EMPTY") . "\n";
    
    if (isset($_SESSION['csrf_tokens'][$postToken])) {
        echo "✅ Token found in session\n";
        $age = time() - $_SESSION['csrf_tokens'][$postToken];
        echo "Token age: " . $age . " seconds\n";
        
        if ($age > 3600) {
            echo "❌ Token expired (> 1 hour)\n";
        } else {
            echo "✅ Token valid\n";
        }
    } else {
        echo "❌ Token NOT found in session\n";
        echo "Available tokens: \n";
        foreach ($_SESSION['csrf_tokens'] as $t => $time) {
            echo "  " . substr($t, 0, 10) . "...\n";
        }
    }
}

echo "</pre>";