<?php
// app/controllers/AuthController.php

class AuthController extends Controller
{
    public function login()
    {
        // If already logged in, redirect
        if (Session::isAuthenticated()) {
            header('Location: /admin/dashboard');
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
                <p style="text-align: center; margin-top: 20px; font-size: 12px; color: #666;">
                    Use: admin / admin123
                </p>
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
        
        // Hardcoded authentication (replace with database later)
        if ($username === 'admin' && $password === 'admin123') {
            Session::loginUser(1, 'Administrator', 'admin');
            header('Location: /admin/dashboard');
            exit;
        } else {
            Session::flash('error', 'Invalid username or password');
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