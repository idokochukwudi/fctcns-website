<?php
/**
 * Applications Controller
 * Handles application management in admin
 * Extends the base Controller class for common functionality
 */
class ApplicationsController extends Controller {
    
    private $db;
    
    /**
     * Constructor
     */
    public function __construct() {
        parent::__construct();
        
        // Set admin layout
        $this->layout = 'admin';
        
        // Require authentication
        require_once __DIR__ . '/../middleware/AuthMiddleware.php';
        AuthMiddleware::authenticate();
        
        // Setup database
        require_once __DIR__ . '/../config/database.php';
        $database = Database::getInstance();
        $this->db = $database->getConnection();
        
        // Initialize common data
        $this->data = array_merge($this->data, [
            'user' => $_SESSION ?? [],
            'baseUrl' => defined('BASE_URL') ? BASE_URL : '',
            'currentPage' => 'applications'
        ]);
    }
    
    /**
     * Show applications list
     */
    public function index() {
        $applications = [];
        $error = null;
        $stats = [];

        try {
            // Get all applications with applicant info
            $stmt = $this->db->query("
                SELECT a.*, u.full_name as applicant_name, u.email, u.phone,
                       u.username as applicant_username
                FROM applications a
                LEFT JOIN users u ON a.user_id = u.id
                ORDER BY a.created_at DESC
            ");
            $applications = $stmt->fetchAll();
            
            // Get statistics
            $statsStmt = $this->db->query("
                SELECT 
                    COUNT(*) as total,
                    SUM(CASE WHEN a.status = 'pending' THEN 1 ELSE 0 END) as pending,
                    SUM(CASE WHEN a.status = 'reviewed' THEN 1 ELSE 0 END) as reviewed,
                    SUM(CASE WHEN a.status = 'approved' THEN 1 ELSE 0 END) as approved,
                    SUM(CASE WHEN a.status = 'rejected' THEN 1 ELSE 0 END) as rejected
                FROM applications a
            ");
            $stats = $statsStmt->fetch();
            
        } catch (Exception $e) {
            error_log("ApplicationsController index error: " . $e->getMessage());
            $error = "Unable to load applications. Please try again.";
        }

        // Set data for view
        $this->data = array_merge($this->data, [
            'applications' => $applications,
            'stats' => $stats,
            'error' => $error,
            'pageTitle' => 'Applications Management - FCT College of Nursing Sciences',
            'pageDescription' => 'Manage student applications'
        ]);

        // Render view
        $this->render('admin/applications/index');
    }

    /**
     * Show single application
     */
    public function show($id) {
        $application = null;
        $error = null;

        try {
            // Get application details with applicant info
            $stmt = $this->db->prepare("
                SELECT a.*, u.full_name as applicant_name, u.email, u.phone,
                       u.username as applicant_username, u.date_of_birth,
                       u.gender, u.nationality, u.address, u.city, u.state,
                       u.country, u.postal_code, u.profile_image,
                       ru.full_name as reviewer_name
                FROM applications a
                LEFT JOIN users u ON a.user_id = u.id
                LEFT JOIN users ru ON a.reviewed_by = ru.id
                WHERE a.id = ?
            ");
            $stmt->execute([$id]);
            $application = $stmt->fetch();

            if (!$application) {
                $this->flash('error', 'Application not found.');
                $this->redirect('/admin/applications');
                return;
            }
            
            // Get application documents if any
            $docsStmt = $this->db->prepare("
                SELECT * FROM application_documents 
                WHERE application_id = ? 
                ORDER BY created_at DESC
            ");
            $docsStmt->execute([$id]);
            $documents = $docsStmt->fetchAll();
            
            // Get application history
            $historyStmt = $this->db->prepare("
                SELECT ah.*, u.full_name as admin_name
                FROM application_history ah
                LEFT JOIN users u ON ah.admin_id = u.id
                WHERE ah.application_id = ?
                ORDER BY ah.created_at DESC
            ");
            $historyStmt->execute([$id]);
            $history = $historyStmt->fetchAll();
            
        } catch (Exception $e) {
            error_log("ApplicationsController show error: " . $e->getMessage());
            $error = "Unable to load application. Please try again.";
        }

        // Set data for view
        $this->data = array_merge($this->data, [
            'application' => $application,
            'documents' => $documents ?? [],
            'history' => $history ?? [],
            'error' => $error,
            'pageTitle' => 'Application Details - ' . ($application['applicant_name'] ?? 'Unknown'),
            'pageDescription' => 'View application details'
        ]);

        $this->render('admin/applications/show');
    }

    /**
     * Show edit application form
     */
    public function edit($id) {
        $application = null;
        $error = null;

        try {
            // Get application details
            $stmt = $this->db->prepare("
                SELECT a.*, u.full_name as applicant_name, u.email, u.phone
                FROM applications a
                LEFT JOIN users u ON a.user_id = u.id
                WHERE a.id = ?
            ");
            $stmt->execute([$id]);
            $application = $stmt->fetch();

            if (!$application) {
                $this->flash('error', 'Application not found.');
                $this->redirect('/admin/applications');
                return;
            }
            
            // Get status options
            $statusOptions = [
                'pending' => 'Pending',
                'reviewed' => 'Under Review',
                'approved' => 'Approved',
                'rejected' => 'Rejected',
                'waitlisted' => 'Waitlisted',
                'accepted' => 'Accepted',
                'enrolled' => 'Enrolled',
                'withdrawn' => 'Withdrawn'
            ];
            
        } catch (Exception $e) {
            error_log("ApplicationsController edit error: " . $e->getMessage());
            $error = "Unable to load application. Please try again.";
        }

        // Set data for view
        $this->data = array_merge($this->data, [
            'application' => $application,
            'error' => $error,
            'statusOptions' => $statusOptions ?? [],
            'pageTitle' => 'Edit Application - ' . ($application['applicant_name'] ?? 'Unknown'),
            'pageDescription' => 'Edit application details'
        ]);

        $this->render('admin/applications/edit');
    }

    /**
     * Update application
     */
    public function update($id) {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->flash('error', 'Invalid request method.');
            $this->redirect('/admin/applications/' . $id . '/edit');
            return;
        }

        // Validate CSRF token
        try {
            $this->validateCsrf();
        } catch (Exception $e) {
            $this->flash('error', 'Security token expired. Please try again.');
            $this->redirect('/admin/applications/' . $id . '/edit');
            return;
        }

        $status = trim($this->input('status', ''));
        $notes = trim($this->input('notes', ''));
        $review_notes = trim($this->input('review_notes', ''));
        $reviewed_by = $_SESSION['user_id'] ?? null;

        try {
            // Get current application status
            $currentStmt = $this->db->prepare("SELECT status FROM applications WHERE id = ?");
            $currentStmt->execute([$id]);
            $current = $currentStmt->fetch();
            
            if (!$current) {
                $this->flash('error', 'Application not found.');
                $this->redirect('/admin/applications');
                return;
            }

            // Update application
            $stmt = $this->db->prepare("
                UPDATE applications 
                SET status = ?, notes = ?, review_notes = ?, 
                    reviewed_by = ?, reviewed_at = NOW(), updated_at = NOW()
                WHERE id = ?
            ");
            $stmt->execute([$status, $notes, $review_notes, $reviewed_by, $id]);
            
            // Log status change if it changed
            if ($current['status'] !== $status) {
                $logStmt = $this->db->prepare("
                    INSERT INTO application_history 
                    (application_id, admin_id, old_status, new_status, notes, created_at)
                    VALUES (?, ?, ?, ?, ?, NOW())
                ");
                $logStmt->execute([$id, $reviewed_by, $current['status'], $status, $notes]);
                
                // Log activity
                $this->logActivity('application_updated', "Application #{$id} status changed from {$current['status']} to {$status}");
            } else {
                // Log general update
                $this->logActivity('application_updated', "Application #{$id} updated");
            }

            $this->flash('success', 'Application updated successfully!');
            $this->redirect('/admin/applications/' . $id);
            
        } catch (Exception $e) {
            error_log("ApplicationsController update error: " . $e->getMessage());
            $this->flash('error', 'Failed to update application: ' . $e->getMessage());
            $this->redirect('/admin/applications/' . $id . '/edit');
        }
    }

    /**
     * Delete application
     */
    public function destroy($id) {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->flash('error', 'Invalid request method.');
            $this->redirect('/admin/applications');
            return;
        }

        // Validate CSRF token
        try {
            $this->validateCsrf();
        } catch (Exception $e) {
            $this->flash('error', 'Security token expired. Please try again.');
            $this->redirect('/admin/applications');
            return;
        }

        try {
            // Start transaction
            $this->db->beginTransaction();
            
            // Delete related records first
            $this->db->prepare("DELETE FROM application_history WHERE application_id = ?")->execute([$id]);
            $this->db->prepare("DELETE FROM application_documents WHERE application_id = ?")->execute([$id]);
            
            // Delete application
            $stmt = $this->db->prepare("DELETE FROM applications WHERE id = ?");
            $stmt->execute([$id]);
            
            // Commit transaction
            $this->db->commit();
            
            // Log activity
            $this->logActivity('application_deleted', "Application #{$id} deleted");
            
            $this->flash('success', 'Application deleted successfully!');
            
        } catch (Exception $e) {
            // Rollback on error
            if ($this->db->inTransaction()) {
                $this->db->rollBack();
            }
            
            error_log("ApplicationsController destroy error: " . $e->getMessage());
            $this->flash('error', 'Failed to delete application: ' . $e->getMessage());
        }

        $this->redirect('/admin/applications');
    }
    
    /**
     * Search applications
     */
    public function search() {
        $searchTerm = $this->query('q', '');
        
        if (empty($searchTerm)) {
            $this->redirect('/admin/applications');
            return;
        }
        
        try {
            $searchTerm = "%{$searchTerm}%";
            
            $stmt = $this->db->prepare("
                SELECT a.*, u.full_name as applicant_name, u.email, u.phone
                FROM applications a
                LEFT JOIN users u ON a.user_id = u.id
                WHERE a.id LIKE ? 
                   OR u.full_name LIKE ? 
                   OR u.email LIKE ? 
                   OR u.phone LIKE ?
                   OR a.program_applied LIKE ?
                   OR a.status LIKE ?
                ORDER BY a.created_at DESC
            ");
            
            // Use same search term for all fields
            $stmt->execute([
                $searchTerm, $searchTerm, $searchTerm, 
                $searchTerm, $searchTerm, $searchTerm
            ]);
            
            $applications = $stmt->fetchAll();
            
            // Set data for view
            $this->data = array_merge($this->data, [
                'applications' => $applications,
                'searchTerm' => $this->query('q', ''),
                'pageTitle' => 'Search Results - Applications',
                'pageDescription' => 'Search applications'
            ]);
            
            $this->render('admin/applications/search');
            
        } catch (Exception $e) {
            error_log("ApplicationsController search error: " . $e->getMessage());
            $this->flash('error', 'Failed to search applications.');
            $this->redirect('/admin/applications');
        }
    }
    
    /**
     * Export applications to CSV
     */
    public function export() {
        try {
            // Get all applications
            $stmt = $this->db->query("
                SELECT a.*, u.full_name as applicant_name, u.email, u.phone,
                       u.username as applicant_username
                FROM applications a
                LEFT JOIN users u ON a.user_id = u.id
                ORDER BY a.created_at DESC
            ");
            $applications = $stmt->fetchAll();
            
            // Set headers for CSV download
            $this->header('Content-Type', 'text/csv; charset=utf-8');
            $this->header('Content-Disposition', 'attachment; filename=applications_' . date('Y-m-d') . '.csv');
            
            // Create output stream
            $output = fopen('php://output', 'w');
            
            // Add CSV headers
            fputcsv($output, [
                'ID', 'Applicant Name', 'Email', 'Phone', 'Program',
                'Status', 'Application Date', 'Reviewed Date', 'Notes'
            ]);
            
            // Add data rows
            foreach ($applications as $app) {
                fputcsv($output, [
                    $app['id'],
                    $app['applicant_name'] ?? 'N/A',
                    $app['email'] ?? 'N/A',
                    $app['phone'] ?? 'N/A',
                    $app['program_applied'] ?? $app['program'] ?? 'Unknown',
                    ucfirst($app['status'] ?? 'pending'),
                    date('Y-m-d', strtotime($app['created_at'])),
                    $app['reviewed_at'] ? date('Y-m-d', strtotime($app['reviewed_at'])) : 'Not reviewed',
                    $app['notes'] ?? ''
                ]);
            }
            
            fclose($output);
            exit;
            
        } catch (Exception $e) {
            error_log("ApplicationsController export error: " . $e->getMessage());
            $this->flash('error', 'Failed to export applications.');
            $this->redirect('/admin/applications');
        }
    }
    
    /**
     * Bulk update applications
     */
    public function bulkUpdate() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/admin/applications');
            return;
        }
        
        // Validate CSRF token
        try {
            $this->validateCsrf();
        } catch (Exception $e) {
            $this->flash('error', 'Security token expired. Please try again.');
            $this->redirect('/admin/applications');
            return;
        }
        
        $action = $this->input('action', '');
        $applicationIds = $this->input('application_ids', []);
        $newStatus = $this->input('new_status', '');
        
        if (empty($applicationIds) || !is_array($applicationIds)) {
            $this->flash('error', 'No applications selected.');
            $this->redirect('/admin/applications');
            return;
        }
        
        try {
            $updatedCount = 0;
            $adminId = $_SESSION['user_id'] ?? null;
            
            foreach ($applicationIds as $appId) {
                if ($action === 'delete') {
                    // Delete application
                    $this->db->prepare("DELETE FROM applications WHERE id = ?")->execute([$appId]);
                    $this->logActivity('application_deleted', "Application #{$appId} deleted via bulk action");
                    $updatedCount++;
                } elseif ($action === 'status' && !empty($newStatus)) {
                    // Update status
                    $currentStmt = $this->db->prepare("SELECT status FROM applications WHERE id = ?");
                    $currentStmt->execute([$appId]);
                    $current = $currentStmt->fetch();
                    
                    if ($current) {
                        $updateStmt = $this->db->prepare("
                            UPDATE applications 
                            SET status = ?, reviewed_by = ?, reviewed_at = NOW(), updated_at = NOW()
                            WHERE id = ?
                        ");
                        $updateStmt->execute([$newStatus, $adminId, $appId]);
                        
                        // Log status change
                        if ($current['status'] !== $newStatus) {
                            $logStmt = $this->db->prepare("
                                INSERT INTO application_history 
                                (application_id, admin_id, old_status, new_status, created_at)
                                VALUES (?, ?, ?, ?, NOW())
                            ");
                            $logStmt->execute([$appId, $adminId, $current['status'], $newStatus]);
                        }
                        
                        $updatedCount++;
                    }
                }
            }
            
            $this->flash('success', "Successfully updated {$updatedCount} application(s).");
            
        } catch (Exception $e) {
            error_log("ApplicationsController bulkUpdate error: " . $e->getMessage());
            $this->flash('error', 'Failed to update applications.');
        }
        
        $this->redirect('/admin/applications');
    }
    
    /**
     * Log activity
     */
    private function logActivity($action, $description) {
        try {
            $user_id = $_SESSION['user_id'] ?? null;
            $ip_address = $_SERVER['REMOTE_ADDR'] ?? '';
            $user_agent = $_SERVER['HTTP_USER_AGENT'] ?? '';
            
            $stmt = $this->db->prepare("
                INSERT INTO activity_logs 
                (user_id, action, description, ip_address, user_agent, created_at)
                VALUES (?, ?, ?, ?, ?, NOW())
            ");
            $stmt->execute([$user_id, $action, $description, $ip_address, $user_agent]);
        } catch (Exception $e) {
            error_log("Failed to log activity: " . $e->getMessage());
        }
    }
    
    /**
     * Override render method for admin-specific views
     */
    protected function render($view = null, $data = []) {
        // Add CSRF token to all forms
        $data['csrf_token'] = $this->csrfToken();
        
        // Add flash messages
        $data['flash_success'] = $this->getFlash('success');
        $data['flash_error'] = $this->getFlash('error');
        
        // Merge with controller data
        $this->data = array_merge($this->data, $data);
        
        // Call parent render method
        parent::render($view);
    }
}