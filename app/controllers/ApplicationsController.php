<?php
/**
 * Applications Controller
 * Handles application management in admin
 */
class ApplicationsController {
    
    /**
     * Show applications list
     */
    public function index() {
        // Require authentication
        require_once __DIR__ . '/../middleware/AuthMiddleware.php';
        AuthMiddleware::authenticate();

        // Get applications from database
        require_once __DIR__ . '/../config/database.php';
        $db = Database::getInstance();
        $conn = $db->getConnection();

        $applications = [];
        $error = null;

        try {
            $stmt = $conn->query("
                SELECT a.*, u.full_name as applicant_name 
                FROM applications a
                LEFT JOIN users u ON a.user_id = u.id
                ORDER BY a.created_at DESC
            ");
            $applications = $stmt->fetchAll();
        } catch (Exception $e) {
            error_log("Applications error: " . $e->getMessage());
            $error = "Unable to load applications. Please try again.";
        }

        // Load view with data
        $this->loadView('admin/applications', [
            'applications' => $applications,
            'error' => $error,
            'user' => $_SESSION
        ]);
    }

    /**
     * Show single application
     */
    public function show($id) {
        // Require authentication
        require_once __DIR__ . '/../middleware/AuthMiddleware.php';
        AuthMiddleware::authenticate();

        // Get application from database
        require_once __DIR__ . '/../config/database.php';
        $db = Database::getInstance();
        $conn = $db->getConnection();

        $application = null;
        $error = null;

        try {
            $stmt = $conn->prepare("
                SELECT a.*, u.full_name as applicant_name, u.email, u.phone
                FROM applications a
                LEFT JOIN users u ON a.user_id = u.id
                WHERE a.id = ?
            ");
            $stmt->execute([$id]);
            $application = $stmt->fetch();

            if (!$application) {
                $error = "Application not found.";
            }
        } catch (Exception $e) {
            error_log("Application show error: " . $e->getMessage());
            $error = "Unable to load application. Please try again.";
        }

        $this->loadView('admin/applications_show', [
            'application' => $application,
            'error' => $error,
            'user' => $_SESSION
        ]);
    }

    /**
     * Show edit application form
     */
    public function edit($id) {
        // Require authentication
        require_once __DIR__ . '/../middleware/AuthMiddleware.php';
        AuthMiddleware::authenticate();

        // Get application from database
        require_once __DIR__ . '/../config/database.php';
        $db = Database::getInstance();
        $conn = $db->getConnection();

        $application = null;
        $error = null;

        try {
            $stmt = $conn->prepare("
                SELECT a.*, u.full_name as applicant_name
                FROM applications a
                LEFT JOIN users u ON a.user_id = u.id
                WHERE a.id = ?
            ");
            $stmt->execute([$id]);
            $application = $stmt->fetch();

            if (!$application) {
                $error = "Application not found.";
            }
        } catch (Exception $e) {
            error_log("Application edit error: " . $e->getMessage());
            $error = "Unable to load application. Please try again.";
        }

        $this->loadView('admin/applications_edit', [
            'application' => $application,
            'error' => $error,
            'user' => $_SESSION
        ]);
    }

    /**
     * Update application
     */
    public function update($id) {
        // Require authentication
        require_once __DIR__ . '/../middleware/AuthMiddleware.php';
        AuthMiddleware::authenticate();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . '/admin/applications/' . $id . '/edit');
            exit;
        }

        $status = trim($_POST['status'] ?? '');
        $notes = trim($_POST['notes'] ?? '');
        $reviewed_by = $_SESSION['user_id'] ?? null;

        require_once __DIR__ . '/../config/database.php';
        $db = Database::getInstance();
        $conn = $db->getConnection();

        try {
            $stmt = $conn->prepare("
                UPDATE applications 
                SET status = ?, notes = ?, reviewed_by = ?, reviewed_at = NOW(), updated_at = NOW()
                WHERE id = ?
            ");
            $stmt->execute([$status, $notes, $reviewed_by, $id]);

            header('Location: ' . BASE_URL . '/admin/applications/' . $id);
            exit;
        } catch (Exception $e) {
            error_log("Application update error: " . $e->getMessage());
            
            // Reload edit form with error
            $this->edit($id);
        }
    }

    /**
     * Delete application
     */
    public function destroy($id) {
        // Require authentication
        require_once __DIR__ . '/../middleware/AuthMiddleware.php';
        AuthMiddleware::authenticate();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . '/admin/applications');
            exit;
        }

        require_once __DIR__ . '/../config/database.php';
        $db = Database::getInstance();
        $conn = $db->getConnection();

        try {
            $stmt = $conn->prepare("DELETE FROM applications WHERE id = ?");
            $stmt->execute([$id]);

            header('Location: ' . BASE_URL . '/admin/applications');
            exit;
        } catch (Exception $e) {
            error_log("Application delete error: " . $e->getMessage());
            header('Location: ' . BASE_URL . '/admin/applications');
            exit;
        }
    }

    /**
     * Helper method to load views
     */
    private function loadView($view, $data = []) {
        // Define APP_PATH if not defined
        if (!defined('APP_PATH')) {
            define('APP_PATH', dirname(__DIR__));
        }
        
        // Define BASE_URL if not defined
        if (!defined('BASE_URL')) {
            // Try to get BASE_URL from constants file
            $constantsPath = APP_PATH . '/config/constants.php';
            if (file_exists($constantsPath)) {
                require_once $constantsPath;
            } else {
                // Fallback definition
                define('BASE_URL', 'http://localhost/fctcns-website');
            }
        }
        
        // Extract data for the view
        extract($data);
        
        // Include the view file
        $viewPath = APP_PATH . '/views/' . $view . '.php';
        
        if (file_exists($viewPath)) {
            require_once $viewPath;
        } else {
            // Fallback to simple view
            if ($view === 'admin/applications') {
                $this->showSimpleApplications($data['applications'] ?? [], $data['error'] ?? null);
            } else {
                echo "<h1>View not found</h1>";
                echo "<p>View file not found: " . htmlspecialchars($viewPath) . "</p>";
                echo "<p>Looking for: " . htmlspecialchars($view) . ".php</p>";
                echo "<p><a href='" . BASE_URL . "/admin/dashboard'>Return to Dashboard</a></p>";
            }
        }
    }

    /**
     * Fallback simple applications view
     */
    private function showSimpleApplications($applications, $error) {
        // Ensure BASE_URL is defined
        if (!defined('BASE_URL')) {
            define('BASE_URL', 'http://localhost/fctcns-website');
        }
        
        ?>
        <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Applications - FCT College of Nursing Sciences</title>
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
                .error-alert {
                    background: #FED7D7;
                    color: #9B2C2C;
                    padding: 1rem;
                    border-radius: 8px;
                    margin-bottom: 2rem;
                }
                .applications-table {
                    background: white;
                    border-radius: 12px;
                    overflow: hidden;
                    box-shadow: 0 4px 6px rgba(0,0,0,0.1);
                }
                table {
                    width: 100%;
                    border-collapse: collapse;
                }
                th {
                    background: #6B4E9B;
                    color: white;
                    padding: 1rem;
                    text-align: left;
                }
                td {
                    padding: 1rem;
                    border-bottom: 1px solid #E2E8F0;
                }
                tr:hover {
                    background: #F7FAFC;
                }
                .status-badge {
                    padding: 4px 12px;
                    border-radius: 20px;
                    font-size: 0.875rem;
                    font-weight: 500;
                }
                .status-pending {
                    background: #FEF3C7;
                    color: #92400E;
                }
                .status-reviewing {
                    background: #DBEAFE;
                    color: #1E40AF;
                }
                .status-approved {
                    background: #D1FAE5;
                    color: #065F46;
                }
                .status-rejected {
                    background: #FEE2E2;
                    color: #991B1B;
                }
                .action-buttons a {
                    color: #6B4E9B;
                    text-decoration: none;
                    margin-right: 1rem;
                }
                .action-buttons a:hover {
                    text-decoration: underline;
                }
            </style>
        </head>
        <body>
            <nav class="navbar">
                <div class="navbar-brand">FCT CNS Applications</div>
                <div>
                    <a href="<?php echo BASE_URL; ?>/admin/dashboard" style="margin-right: 1rem; color: #4A5568; text-decoration: none;">Dashboard</a>
                    <a href="<?php echo BASE_URL; ?>/admin/logout" class="logout-btn">Logout</a>
                </div>
            </nav>
            
            <div class="container">
                <h1>Applications Management</h1>
                
                <?php if ($error): ?>
                <div class="error-alert">
                    <?php echo htmlspecialchars($error); ?>
                </div>
                <?php endif; ?>
                
                <div class="applications-table">
                    <?php if (empty($applications)): ?>
                        <p style="padding: 2rem; text-align: center; color: #718096;">
                            No applications found.
                        </p>
                    <?php else: ?>
                        <table>
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Applicant Name</th>
                                    <th>Program</th>
                                    <th>Status</th>
                                    <th>Applied Date</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($applications as $app): ?>
                                <tr>
                                    <td>#<?php echo htmlspecialchars($app['id']); ?></td>
                                    <td><?php echo htmlspecialchars($app['applicant_name'] ?? 'N/A'); ?></td>
                                    <td><?php echo htmlspecialchars($app['program_applied'] ?? $app['program'] ?? 'Unknown'); ?></td>
                                    <td>
                                        <?php 
                                        $status = $app['status'] ?? 'pending';
                                        $statusClass = 'status-' . $status;
                                        ?>
                                        <span class="status-badge <?php echo $statusClass; ?>">
                                            <?php echo ucfirst($status); ?>
                                        </span>
                                    </td>
                                    <td><?php echo date('M d, Y', strtotime($app['created_at'])); ?></td>
                                    <td class="action-buttons">
                                        <a href="<?php echo BASE_URL; ?>/admin/applications/<?php echo $app['id']; ?>">View</a>
                                        <a href="<?php echo BASE_URL; ?>/admin/applications/<?php echo $app['id']; ?>/edit">Edit</a>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    <?php endif; ?>
                </div>
            </div>
        </body>
        </html>
        <?php
    }
}