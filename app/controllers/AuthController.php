<?php
// app/controllers/AuthController.php

class AuthController extends Controller
{
    public function login()
    {
        // If already logged in, redirect using RoleRedirectMiddleware
        if (Session::isAuthenticated()) {
            // Direct debug output
            echo "<pre style='background: #f0f0f0; padding: 10px;'>";
            echo "=== DEBUG: Already Logged In ===\n";
            echo "Session user_role: " . ($_SESSION['user_role'] ?? 'NOT SET') . "\n";
            
            // Check if middleware exists
            $middlewarePath = APP_PATH . '/middleware/RoleRedirectMiddleware.php';
            echo "Middleware path: " . $middlewarePath . "\n";
            echo "Middleware exists: " . (file_exists($middlewarePath) ? 'YES' : 'NO') . "\n";
            
            if (file_exists($middlewarePath)) {
                require_once $middlewarePath;
                $redirectUrl = RoleRedirectMiddleware::redirect();
                echo "Redirect URL: " . $redirectUrl . "\n";
            } else {
                $redirectUrl = '/admin/dashboard';
                echo "Using default redirect: " . $redirectUrl . "\n";
            }
            
            echo "</pre>";
            
            // Add a manual redirect link for testing
            echo "<br><a href='" . $redirectUrl . "'>Click here to continue to " . $redirectUrl . "</a>";
            exit;
        }
        
        // Show simple login form WITHOUT CSRF for now
        echo '<!DOCTYPE html>
        <html>
        <head>
            <title>Login - FCT CNS Admin</title>
            <style>
                body { font-family: Arial, sans-serif; display: flex; justify-content: center; align-items: center; height: 100vh; margin: 0; background: #f5f5f5; }
                .login-box { background: white; padding: 40px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); width: 300px; }
                h1 { text-align: center; color: #2c5282; margin-bottom: 30px; }
                input { width: 100%; padding: 10px; margin: 10px 0; border: 1px solid #ddd; border-radius: 4px; }
                button { width: 100%; padding: 12px; background: #2c5282; color: white; border: none; border-radius: 4px; cursor: pointer; }
                .error { color: red; text-align: center; margin-bottom: 15px; }
                .debug { background: #f0f0f0; padding: 10px; margin: 10px 0; border-radius: 4px; font-family: monospace; }
            </style>
        </head>
        <body>
            <div class="login-box">
                <h1>FCT CNS Admin</h1>';
                
        if (Session::has('error')) {
            echo '<div class="error">' . htmlspecialchars(Session::flash('error')) . '</div>';
        }
        
        echo '<form method="POST" action="/login">
                    <input type="text" name="username" placeholder="Username" required>
                    <input type="password" name="password" placeholder="Password" required>
                    <button type="submit">Login</button>
                </form>
            </div>
        </body>
        </html>';
    }
    
    public function processLogin()
    {
        // Get credentials
        $username = $_POST['username'] ?? '';
        $password = $_POST['password'] ?? '';
        
        // Simple validation
        if (empty($username) || empty($password)) {
            Session::flash('error', 'Please enter both username and password');
            header('Location: /login');
            exit;
        }
        
        try {
            // Connect to database
            require_once APP_PATH . '/config/database.php';
            $database = Database::getInstance();
            $db = $database->getConnection();
            
            // Find user by username or email
            $stmt = $db->prepare("SELECT * FROM users WHERE username = ? OR email = ?");
            $stmt->execute([$username, $username]);
            $user = $stmt->fetch();
            
            if (!$user) {
                Session::flash('error', 'Invalid username or password');
                header('Location: /login');
                exit;
            }
            
            // Verify password
            if (!password_verify($password, $user['password_hash'])) {
                Session::flash('error', 'Invalid username or password');
                header('Location: /login');
                exit;
            }
            
            // Check if user is active
            if (!$user['is_active']) {
                Session::flash('error', 'Account is deactivated. Please contact administrator.');
                header('Location: /login');
                exit;
            }
            
            // Login successful - Set session
            Session::loginUser($user['id'], $user['username'], $user['role'], [
                'email' => $user['email'],
                'full_name' => $user['full_name']
            ]);
            
            // ========== DIRECT DEBUG OUTPUT ==========
            echo "<div class='debug' style='position: fixed; top: 10px; right: 10px; z-index: 9999;'>";
            echo "<strong>DEBUG INFO:</strong><br>";
            echo "Username: " . htmlspecialchars($username) . "<br>";
            echo "User ID: " . $user['id'] . "<br>";
            echo "Database Role: " . $user['role'] . "<br>";
            echo "Session Role: " . ($_SESSION['user_role'] ?? 'NOT SET') . "<br>";
            
            // Check middleware
            $middlewarePath = APP_PATH . '/middleware/RoleRedirectMiddleware.php';
            echo "Middleware exists: " . (file_exists($middlewarePath) ? 'YES' : 'NO') . "<br>";
            
            $finalRedirectUrl = '';
            
            if (file_exists($middlewarePath)) {
                require_once $middlewarePath;
                echo "Middleware loaded successfully<br>";
                
                $middlewareRedirect = RoleRedirectMiddleware::redirect();
                echo "Middleware redirect: " . $middlewareRedirect . "<br>";
                $finalRedirectUrl = $middlewareRedirect;
            } else {
                // Calculate manually
                echo "Calculating redirect manually<br>";
                switch ($user['role']) {
                    case 'research_manager':
                        $finalRedirectUrl = '/admin/research';
                        break;
                    case 'nominal_roll_user':
                        $finalRedirectUrl = '/admin/nominal-roll';
                        break;
                    default:
                        $finalRedirectUrl = '/admin/dashboard';
                }
                echo "Manual redirect: " . $finalRedirectUrl . "<br>";
            }
            
            // Add BASE_URL if needed
            if (defined('BASE_URL') && strpos($finalRedirectUrl, 'http') !== 0) {
                $finalRedirectUrl = BASE_URL . $finalRedirectUrl;
            }
            
            echo "<strong>Final redirect URL:</strong> " . $finalRedirectUrl . "<br>";
            echo "</div>";
            
            // Add a manual redirect link so we can see the debug info
            echo "<div style='text-align: center; margin-top: 50px;'>";
            echo "<p>If auto-redirect doesn't work, click below:</p>";
            echo "<a href='" . $finalRedirectUrl . "' style='padding: 10px 20px; background: #2c5282; color: white; text-decoration: none; border-radius: 4px;'>Continue to " . $finalRedirectUrl . "</a>";
            echo "</div>";
            
            // Wait 5 seconds before auto-redirect so we can see debug info
            echo "<script>
                setTimeout(function() {
                    window.location.href = '" . $finalRedirectUrl . "';
                }, 5000);
            </script>";
            
            exit;
            
        } catch (Exception $e) {
            // Show error on screen
            echo "<div class='debug' style='background: #ffcccc; padding: 20px; margin: 20px;'>";
            echo "<strong>LOGIN ERROR:</strong><br>";
            echo "Error: " . htmlspecialchars($e->getMessage()) . "<br>";
            echo "File: " . $e->getFile() . "<br>";
            echo "Line: " . $e->getLine() . "<br>";
            echo "</div>";
            
            Session::flash('error', 'Login failed. Please try again.');
            header('Location: /login');
            exit;
        }
    }
    
    public function logout()
    {
        Session::logout();
        header('Location: /login');
        exit;
    }
}