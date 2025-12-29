<?php
/**
 * Admin Controller
 * Handles admin authentication and dashboard
 */
class AdminController {
    
    /**
     * Show login page
     */
    public function login() {
        // If already logged in, redirect to dashboard
        require_once __DIR__ . '/../config/session.php';
        if (Session::isAuthenticated()) {
            header('Location: ' . BASE_URL . '/admin/dashboard');
            exit;
        }

        // Show login page from app/views/admin/
        $viewPath = APP_PATH . '/views/admin/login.php';
        
        if (file_exists($viewPath)) {
            // Check for flash messages
            $error = Session::getFlash('error');
            $success = Session::getFlash('success');
            
            // Extract variables for the view
            extract([
                'error' => $error,
                'success' => $success
            ]);
            
            // Include the view
            include $viewPath;
        } else {
            // Fallback to simple login form
            $this->showSimpleLogin();
        }
    }

    /**
     * Fallback simple login form
     */
    private function showSimpleLogin() {
        ?>
        <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Admin Login - FCT College of Nursing Sciences</title>
            <style>
                body {
                    font-family: 'Inter', sans-serif;
                    background: linear-gradient(135deg, #6B4E9B, #7FB285);
                    min-height: 100vh;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    margin: 0;
                    padding: 20px;
                }
                .login-container {
                    background: white;
                    border-radius: 20px;
                    padding: 40px;
                    box-shadow: 0 20px 60px rgba(0,0,0,0.3);
                    width: 100%;
                    max-width: 400px;
                }
                .logo {
                    text-align: center;
                    margin-bottom: 30px;
                }
                .logo h1 {
                    color: #6B4E9B;
                    font-size: 1.5rem;
                    margin-bottom: 5px;
                }
                .logo p {
                    color: #718096;
                    font-size: 0.9rem;
                }
                .form-group {
                    margin-bottom: 20px;
                }
                .form-group label {
                    display: block;
                    margin-bottom: 8px;
                    color: #4A5568;
                    font-weight: 500;
                }
                .form-group input {
                    width: 100%;
                    padding: 12px 16px;
                    border: 2px solid #E2E8F0;
                    border-radius: 10px;
                    font-size: 1rem;
                    transition: all 0.3s;
                    box-sizing: border-box;
                }
                .form-group input:focus {
                    outline: none;
                    border-color: #6B4E9B;
                    box-shadow: 0 0 0 3px rgba(107, 78, 155, 0.1);
                }
                .btn-login {
                    width: 100%;
                    padding: 14px;
                    background: linear-gradient(135deg, #6B4E9B, #7FB285);
                    color: white;
                    border: none;
                    border-radius: 10px;
                    font-size: 1rem;
                    font-weight: 600;
                    cursor: pointer;
                    transition: all 0.3s;
                }
                .btn-login:hover {
                    transform: translateY(-2px);
                    box-shadow: 0 10px 20px rgba(107, 78, 155, 0.3);
                }
                .error-message {
                    background: #FED7D7;
                    color: #9B2C2C;
                    padding: 12px;
                    border-radius: 10px;
                    margin-bottom: 20px;
                    font-size: 0.9rem;
                    text-align: center;
                }
                .success-message {
                    background: #C6F6D5;
                    color: #22543D;
                    padding: 12px;
                    border-radius: 10px;
                    margin-bottom: 20px;
                    font-size: 0.9rem;
                    text-align: center;
                }
            </style>
        </head>
        <body>
            <div class="login-container">
                <div class="logo">
                    <h1>FCT College of Nursing Sciences</h1>
                    <p>Admin Portal</p>
                </div>
                
                <?php 
                require_once __DIR__ . '/../config/session.php';
                $error = Session::getFlash('error');
                $success = Session::getFlash('success');
                
                if ($error): ?>
                <div class="error-message">
                    <?php echo htmlspecialchars($error); ?>
                </div>
                <?php endif; ?>
                
                <?php if ($success): ?>
                <div class="success-message">
                    <?php echo htmlspecialchars($success); ?>
                </div>
                <?php endif; ?>
                
                <form method="POST" action="<?php echo BASE_URL; ?>/admin/login">
                    <div class="form-group">
                        <label for="username">Username or Email</label>
                        <input type="text" id="username" name="username" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="password">Password</label>
                        <input type="password" id="password" name="password" required>
                    </div>
                    
                    <button type="submit" class="btn-login">Sign In</button>
                </form>
            </div>
        </body>
        </html>
        <?php
    }

    /**
     * Process login
     */
    public function processLogin() {
        require_once __DIR__ . '/../config/database.php';
        require_once __DIR__ . '/../config/session.php';

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . '/admin');
            exit;
        }

        $username = trim($_POST['username'] ?? '');
        $password = $_POST['password'] ?? '';

        if (empty($username) || empty($password)) {
            Session::setFlash('error', 'Please enter username and password');
            header('Location: ' . BASE_URL . '/admin');
            exit;
        }

        try {
            $db = Database::getInstance();
            $conn = $db->getConnection();

            $stmt = $conn->prepare("
                SELECT id, username, email, password_hash, full_name, role, is_active
                FROM users
                WHERE username = ? OR email = ?
            ");
            $stmt->execute([$username, $username]);
            $user = $stmt->fetch();

            if ($user && password_verify($password, $user['password_hash'])) {
                if ($user['is_active']) {
                    Session::loginUser($user['id'], $user['username'], $user['role']);

                    // Update last login
                    $stmt = $conn->prepare("UPDATE users SET last_login = NOW() WHERE id = ?");
                    $stmt->execute([$user['id']]);

                    header('Location: ' . BASE_URL . '/admin/dashboard');
                    exit;
                } else {
                    Session::setFlash('error', 'Account is deactivated');
                }
            } else {
                Session::setFlash('error', 'Invalid credentials');
            }
        } catch (Exception $e) {
            error_log("Login error: " . $e->getMessage());
            Session::setFlash('error', 'Login failed. Please try again.');
        }

        header('Location: ' . BASE_URL . '/admin');
        exit;
    }

    /**
     * Logout
     */
    public function logout() {
        require_once __DIR__ . '/../config/session.php';
        
        // Debug: Log the logout attempt
        error_log("Logout called from: " . $_SERVER['REQUEST_URI']);
        
        // Destroy session
        Session::logout();
        
        // Clear output buffer
        while (ob_get_level()) {
            ob_end_clean();
        }
        
        // Use absolute URL with your site path
        $redirect_url = 'http://localhost/fctcns-website/admin';
        
        // Debug
        error_log("Redirecting to: " . $redirect_url);
        
        header('Location: ' . $redirect_url);
        exit;
    }

    /**
     * Show dashboard
     */
    public function dashboard() {
        // Require authentication
        require_once __DIR__ . '/../middleware/AuthMiddleware.php';
        AuthMiddleware::authenticate();

        // Get statistics
        require_once __DIR__ . '/../config/database.php';
        $db = Database::getInstance();
        $conn = $db->getConnection();

        $stats = [];
        $error = null;

        try {
            // Get total users
            $stmt = $conn->query("SELECT COUNT(*) as total FROM users");
            $stats['total_users'] = $stmt->fetch()['total'];

            // Get total applications
            $stmt = $conn->query("SELECT COUNT(*) as total FROM applications");
            $stats['total_applications'] = $stmt->fetch()['total'];

            // Get total research
            $stmt = $conn->query("SELECT COUNT(*) as total FROM research_publications");
            $stats['total_research'] = $stmt->fetch()['total'];

            // Get total news
            $stmt = $conn->query("SELECT COUNT(*) as total FROM news");
            $stats['total_news'] = $stmt->fetch()['total'];

        } catch (Exception $e) {
            error_log("Dashboard stats error: " . $e->getMessage());
            $error = "Unable to load statistics. Please check database connection.";
        }

        // Load dashboard view from app/views/admin/
        $viewPath = APP_PATH . '/views/admin/dashboard.php';
        
        if (file_exists($viewPath)) {
            // Extract variables for the view
            extract([
                'stats' => $stats,
                'error' => $error,
                'user' => $_SESSION
            ]);
            
            include $viewPath;
        } else {
            // Fallback to simple dashboard
            $this->showSimpleDashboard($stats, $error);
        }
    }

    /**
     * Debug page
     */
    public function debug() {
        include APP_PATH . '/views/admin/debug.php';
    }

    /**
     * Database inspection tool
     */
    public function dbInspect() {
        // Only allow admin users
        if ($_SESSION['user_role'] !== 'admin') {
            echo "Access denied. Admin privileges required.";
            exit;
        }
        
        // Load the inspection view
        include APP_PATH . '/views/admin/db_inspect.php';
    }

    /**
     * Database table creation tool
     */
    public function dbCreateTables() {
        // Only allow admin users
        if ($_SESSION['user_role'] !== 'admin') {
            echo "Access denied. Admin privileges required.";
            exit;
        }
        
        // Load the table creation view
        include APP_PATH . '/views/admin/db_create_tables.php';
    }

    /**
     * 404 Not Found page
     */
    public function notFound() {
        http_response_code(404);
        echo '<h1>404 - Admin Page Not Found</h1>';
        echo '<p>The requested admin page was not found.</p>';
        echo '<p><a href="' . BASE_URL . '/admin/dashboard">Return to Dashboard</a></p>';
    }

    /**
     * Fallback simple dashboard
     */
    private function showSimpleDashboard($stats, $error) {
        ?>
        <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Dashboard - FCT College of Nursing Sciences</title>
            <style>
                body {
                    font-family: 'Inter', sans-serif;
                    margin: 0;
                    background: #F7FAFC;
                }
                .navbar {
                    background: white;
                    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
                    padding: 1rem 2rem;
                    display: flex;
                    justify-content: space-between;
                    align-items: center;
                }
                .navbar-brand {
                    font-size: 1.25rem;
                    font-weight: bold;
                    color: #6B4E9B;
                    text-decoration: none;
                }
                .logout-btn {
                    background: #6B4E9B;
                    color: white;
                    border: none;
                    padding: 8px 16px;
                    border-radius: 6px;
                    cursor: pointer;
                    text-decoration: none;
                    display: inline-block;
                }
                .container {
                    max-width: 1200px;
                    margin: 2rem auto;
                    padding: 0 2rem;
                }
                .stats-grid {
                    display: grid;
                    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
                    gap: 1.5rem;
                    margin-bottom: 2rem;
                }
                .stat-card {
                    background: white;
                    border-radius: 12px;
                    padding: 1.5rem;
                    box-shadow: 0 4px 6px rgba(0,0,0,0.1);
                }
                .stat-card h3 {
                    margin: 0 0 1rem 0;
                    color: #4A5568;
                    font-size: 1rem;
                }
                .stat-number {
                    font-size: 2rem;
                    font-weight: bold;
                    color: #6B4E9B;
                }
                .error-alert {
                    background: #FED7D7;
                    color: #9B2C2C;
                    padding: 1rem;
                    border-radius: 8px;
                    margin-bottom: 2rem;
                }
                .action-buttons {
                    display: flex;
                    gap: 1rem;
                    margin-top: 1rem;
                    flex-wrap: wrap;
                }
                .action-btn {
                    padding: 12px 24px;
                    border-radius: 8px;
                    text-decoration: none;
                    font-weight: 500;
                    transition: transform 0.2s;
                }
                .action-btn:hover {
                    transform: translateY(-2px);
                }
            </style>
        </head>
        <body>
            <nav class="navbar">
                <a href="<?php echo BASE_URL; ?>/admin/dashboard" class="navbar-brand">FCT CNS Admin Dashboard</a>
                <div>
                    <span style="margin-right: 1rem;">Welcome, <?php echo htmlspecialchars($_SESSION['username'] ?? 'Admin'); ?></span>
                    <a href="<?php echo BASE_URL; ?>/admin/logout" class="logout-btn">Logout</a>
                </div>
            </nav>
            
            <div class="container">
                <?php if ($error): ?>
                <div class="error-alert">
                    <?php echo htmlspecialchars($error); ?>
                </div>
                <?php endif; ?>
                
                <h1>Dashboard Overview</h1>
                
                <div class="stats-grid">
                    <div class="stat-card">
                        <h3>Total Users</h3>
                        <div class="stat-number"><?php echo $stats['total_users'] ?? 0; ?></div>
                    </div>
                    <div class="stat-card">
                        <h3>Total Applications</h3>
                        <div class="stat-number"><?php echo $stats['total_applications'] ?? 0; ?></div>
                    </div>
                    <div class="stat-card">
                        <h3>Research Publications</h3>
                        <div class="stat-number"><?php echo $stats['total_research'] ?? 0; ?></div>
                    </div>
                    <div class="stat-card">
                        <h3>News Articles</h3>
                        <div class="stat-number"><?php echo $stats['total_news'] ?? 0; ?></div>
                    </div>
                </div>
                
                <div style="margin-top: 2rem;">
                    <h2>Quick Actions</h2>
                    <div class="action-buttons">
                        <a href="<?php echo BASE_URL; ?>/admin/applications" class="action-btn" style="background: #7FB285; color: white;">
                            View Applications
                        </a>
                        <a href="#" class="action-btn" style="background: #6B4E9B; color: white;">
                            Manage Users
                        </a>
                        <a href="#" class="action-btn" style="background: #4A5568; color: white;">
                            Add News
                        </a>
                        <a href="<?php echo BASE_URL; ?>" class="action-btn" style="background: #CBD5E0; color: #2D3748;">
                            View Website
                        </a>
                    </div>
                </div>
            </div>
        </body>
        </html>
        <?php
    }
}