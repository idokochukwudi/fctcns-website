<?php
/**
 * Admin Controller
 * Handles admin authentication and dashboard
 * Extends the base Controller class for common functionality
 */
class AdminController extends Controller {
    
    /**
     * Constructor
     */
    public function __construct() {
        parent::__construct();
        // Set admin layout
        $this->layout = 'admin';
    }
    
    /**
     * Show login page
     */
    public function login() {
        // If already logged in, redirect to dashboard
        require_once __DIR__ . '/../config/session.php';
        if (Session::isAuthenticated()) {
            $this->redirect('/admin/dashboard');
        }

        // Get flash messages
        $error = Session::getFlash('error');
        $success = Session::getFlash('success');

        // Check if login view exists
        $viewPath = APP_PATH . '/views/admin/login.php';
        
        if (file_exists($viewPath)) {
            // Set data and render
            $this->data = [
                'error' => $error,
                'success' => $success,
                'pageTitle' => 'Admin Login - FCT College of Nursing Sciences',
                'currentPage' => 'admin-login'
            ];
            
            $this->render('admin/login');
        } else {
            // Fallback to simple login form
            $this->showSimpleLogin($error, $success);
        }
    }

    /**
     * Fallback simple login form
     */
    private function showSimpleLogin($error = null, $success = null) {
        // Set content type and output HTML directly
        header('Content-Type: text/html; charset=UTF-8');
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
                
                <?php if ($error): ?>
                <div class="error-message">
                    <?php echo htmlspecialchars($error); ?>
                </div>
                <?php endif; ?>
                
                <?php if ($success): ?>
                <div class="success-message">
                    <?php echo htmlspecialchars($success); ?>
                </div>
                <?php endif; ?>
                
                <form method="POST" action="<?php echo $this->data['baseUrl'] ?? BASE_URL; ?>/admin/process-login">
                    <div class="form-group">
                        <label for="username">Username or Email</label>
                        <input type="text" id="username" name="username" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="password">Password</label>
                        <input type="password" id="password" name="password" required>
                    </div>
                    
                    <input type="hidden" name="_csrf_token" value="<?php echo $this->csrfToken(); ?>">
                    
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
            $this->redirect('/admin');
        }

        // Validate CSRF token
        try {
            $this->validateCsrf();
        } catch (Exception $e) {
            Session::setFlash('error', 'Security token expired. Please try again.');
            $this->redirect('/admin');
        }

        $username = trim($this->input('username', ''));
        $password = $this->input('password', '');

        if (empty($username) || empty($password)) {
            Session::setFlash('error', 'Please enter username and password');
            $this->redirect('/admin');
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

                    $this->flash('success', 'Login successful!');
                    $this->redirect('/admin/dashboard');
                } else {
                    Session::setFlash('error', 'Account is deactivated');
                    $this->redirect('/admin');
                }
            } else {
                Session::setFlash('error', 'Invalid credentials');
                $this->redirect('/admin');
            }
        } catch (Exception $e) {
            error_log("Login error: " . $e->getMessage());
            Session::setFlash('error', 'Login failed. Please try again.');
            $this->redirect('/admin');
        }
    }

    /**
     * Logout
     */
    public function logout() {
        require_once __DIR__ . '/../config/session.php';
        
        // Destroy session
        Session::logout();
        
        // Clear any flash messages
        if (isset($_SESSION['flash'])) {
            unset($_SESSION['flash']);
        }
        
        // Redirect to admin login
        $this->redirect('/admin');
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

        // Check if dashboard view exists
        $viewPath = APP_PATH . '/views/admin/dashboard.php';
        
        if (file_exists($viewPath)) {
            // Set data and render
            $this->data = array_merge($this->data, [
                'stats' => $stats,
                'error' => $error,
                'user' => $_SESSION,
                'pageTitle' => 'Admin Dashboard - FCT College of Nursing Sciences',
                'currentPage' => 'dashboard'
            ]);
            
            $this->render('admin/dashboard');
        } else {
            // Fallback to simple dashboard
            $this->showSimpleDashboard($stats, $error);
        }
    }

    /**
     * Debug page
     */
    public function debug() {
        // Require authentication
        require_once __DIR__ . '/../middleware/AuthMiddleware.php';
        AuthMiddleware::authenticate();

        $this->data = array_merge($this->data, [
            'pageTitle' => 'Debug Information',
            'currentPage' => 'debug'
        ]);
        
        // Try to render debug view
        $viewPath = APP_PATH . '/views/admin/debug.php';
        if (file_exists($viewPath)) {
            $this->render('admin/debug');
        } else {
            // Show basic debug info
            echo '<h1>Debug Information</h1>';
            echo '<pre>';
            print_r([
                'session' => $_SESSION,
                'server' => $_SERVER,
                'post' => $_POST,
                'get' => $_GET
            ]);
            echo '</pre>';
        }
    }

    /**
     * Database inspection tool
     */
    public function dbInspect() {
        // Require authentication
        require_once __DIR__ . '/../middleware/AuthMiddleware.php';
        AuthMiddleware::authenticate();

        // Only allow admin users
        if ($_SESSION['user_role'] !== 'admin') {
            $this->flash('error', 'Access denied. Admin privileges required.');
            $this->redirect('/admin/dashboard');
        }
        
        // Get database information
        $tables = [];
        $error = null;
        
        try {
            require_once __DIR__ . '/../config/database.php';
            $db = Database::getInstance();
            $conn = $db->getConnection();
            
            // Get list of tables
            $stmt = $conn->query("SHOW TABLES");
            $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);
            
            // Get table details
            $tableDetails = [];
            foreach ($tables as $table) {
                $stmt = $conn->query("DESCRIBE `$table`");
                $tableDetails[$table] = $stmt->fetchAll(PDO::FETCH_ASSOC);
            }
            
        } catch (Exception $e) {
            $error = $e->getMessage();
        }
        
        $this->data = array_merge($this->data, [
            'tables' => $tables,
            'tableDetails' => $tableDetails ?? [],
            'error' => $error,
            'pageTitle' => 'Database Inspection',
            'currentPage' => 'db-inspect'
        ]);
        
        // Load the inspection view
        $viewPath = APP_PATH . '/views/admin/db_inspect.php';
        if (file_exists($viewPath)) {
            $this->render('admin/db_inspect');
        } else {
            // Show basic table info
            echo '<h1>Database Inspection</h1>';
            if ($error) {
                echo "<p style='color: red;'>Error: $error</p>";
            }
            echo '<ul>';
            foreach ($tables as $table) {
                echo "<li>$table</li>";
            }
            echo '</ul>';
        }
    }

    /**
     * Database table creation tool
     */
    public function dbCreateTables() {
        // Require authentication
        require_once __DIR__ . '/../middleware/AuthMiddleware.php';
        AuthMiddleware::authenticate();

        // Only allow admin users
        if ($_SESSION['user_role'] !== 'admin') {
            $this->flash('error', 'Access denied. Admin privileges required.');
            $this->redirect('/admin/dashboard');
        }
        
        $this->data = array_merge($this->data, [
            'pageTitle' => 'Create Database Tables',
            'currentPage' => 'db-create-tables'
        ]);
        
        // Load the table creation view
        $viewPath = APP_PATH . '/views/admin/db_create_tables.php';
        if (file_exists($viewPath)) {
            $this->render('admin/db_create_tables');
        } else {
            echo '<h1>Database Table Creation</h1>';
            echo '<p>This tool helps create database tables.</p>';
        }
    }

    /**
     * 404 Not Found page for admin
     */
    public function notFound() {
        $this->status(404);
        $this->data = array_merge($this->data, [
            'pageTitle' => '404 - Admin Page Not Found',
            'currentPage' => '404'
        ]);
        
        // Try admin 404 view first
        $adminViewPath = APP_PATH . '/views/admin/404.php';
        if (file_exists($adminViewPath)) {
            $this->render('admin/404');
        } else {
            // Try general 404 view
            $generalViewPath = APP_PATH . '/views/pages/404.php';
            if (file_exists($generalViewPath)) {
                $this->render('pages/404');
            } else {
                // Fallback
                echo '<h1>404 - Admin Page Not Found</h1>';
                echo '<p>The requested admin page was not found.</p>';
                echo '<p><a href="' . ($this->data['baseUrl'] ?? BASE_URL) . '/admin/dashboard">Return to Dashboard</a></p>';
            }
        }
    }

    /**
     * Fallback simple dashboard
     */
    private function showSimpleDashboard($stats, $error) {
        // Set content type and output HTML directly
        header('Content-Type: text/html; charset=UTF-8');
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
                <a href="<?php echo $this->data['baseUrl'] ?? BASE_URL; ?>/admin/dashboard" class="navbar-brand">FCT CNS Admin Dashboard</a>
                <div>
                    <span style="margin-right: 1rem;">Welcome, <?php echo htmlspecialchars($_SESSION['username'] ?? 'Admin'); ?></span>
                    <a href="<?php echo $this->data['baseUrl'] ?? BASE_URL; ?>/admin/logout" class="logout-btn">Logout</a>
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
                        <a href="<?php echo $this->data['baseUrl'] ?? BASE_URL; ?>/admin/applications" class="action-btn" style="background: #7FB285; color: white;">
                            View Applications
                        </a>
                        <a href="<?php echo $this->data['baseUrl'] ?? BASE_URL; ?>/admin/users" class="action-btn" style="background: #6B4E9B; color: white;">
                            Manage Users
                        </a>
                        <a href="<?php echo $this->data['baseUrl'] ?? BASE_URL; ?>/admin/news" class="action-btn" style="background: #4A5568; color: white;">
                            Manage News
                        </a>
                        <a href="<?php echo $this->data['baseUrl'] ?? BASE_URL; ?>" class="action-btn" style="background: #CBD5E0; color: #2D3748;">
                            View Website
                        </a>
                    </div>
                </div>
            </div>
        </body>
        </html>
        <?php
    }

    /**
     * Override render method for admin-specific behavior
     */
    protected function render($view = null, $data = []) {
        // Add common admin data
        $data['user'] = $_SESSION ?? [];
        $data['baseUrl'] = $this->data['baseUrl'] ?? BASE_URL;
        
        // Merge with controller data
        $this->data = array_merge($this->data, $data);
        
        // Call parent render method
        parent::render($view);
    }
}