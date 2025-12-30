<?php
/**
 * Contact Controller
 * Handles admin-side contact management with CRUD operations
 * 
 * @package FCTCNS
 * @version 2.0
 */

class ContactController extends Controller {
    private $contactModel;
    
    /**
     * Constructor
     */
    public function __construct() {
        parent::__construct();
        
        // Require authentication
        require_once APP_PATH . '/middleware/AuthMiddleware.php';
        AuthMiddleware::authenticate();
        
        // Set admin layout
        $this->layout = 'admin';
        
        // Initialize model
        require_once APP_PATH . '/models/ContactModel.php';
        $this->contactModel = new ContactModel();
    }
    
    /**
     * List all contact submissions
     */
    public function index() {
        $status = $this->input('status', 'all');
        $search = $this->input('search', '');
        
        if (!empty($search)) {
            $submissions = $this->contactModel->searchSubmissions($search);
        } else {
            $submissions = $this->contactModel->getAllSubmissions(
                $status !== 'all' ? $status : null,
                50
            );
        }
        
        $stats = $this->contactModel->getStatistics();
        
        $this->data = array_merge($this->data, [
            'page_title' => 'Contact Management',
            'currentPage' => 'contact',
            'submissions' => $submissions,
            'stats' => $stats,
            'current_status' => $status,
            'search_term' => $search
        ]);
        
        $this->render('admin/contact/index');
    }
    
    /**
     * View single submission
     */
    public function view($id) {
        $submission = $this->contactModel->getSubmission($id);
        
        if (!$submission) {
            $this->flash('error', 'Contact submission not found.');
            $this->redirect('/admin/contact');
        }
        
        $this->data = array_merge($this->data, [
            'page_title' => 'View Contact Submission',
            'currentPage' => 'contact',
            'submission' => $submission
        ]);
        
        $this->render('admin/contact/view');
    }
    
    /**
     * Update submission status/notes
     */
    public function update($id) {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->flash('error', 'Invalid request method.');
            $this->redirect('/admin/contact');
        }
        
        try {
            $this->validateCsrf();
            
            $data = [
                'status' => $this->input('status', ''),
                'admin_notes' => $this->input('admin_notes', '')
            ];
            
            $updated = $this->contactModel->updateSubmission($id, $data);
            
            if ($updated) {
                $this->flash('success', 'Submission updated successfully.');
            } else {
                $this->flash('error', 'Failed to update submission.');
            }
            
            $this->redirect('/admin/contact/view/' . $id);
            
        } catch (Exception $e) {
            error_log("Contact update error: " . $e->getMessage());
            $this->flash('error', 'An error occurred.');
            $this->redirect('/admin/contact');
        }
    }
    
    /**
     * Delete submission
     */
    public function delete($id) {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->flash('error', 'Invalid request method.');
            $this->redirect('/admin/contact');
        }
        
        try {
            $this->validateCsrf();
            
            $deleted = $this->contactModel->deleteSubmission($id);
            
            if ($deleted) {
                $this->flash('success', 'Submission deleted successfully.');
            } else {
                $this->flash('error', 'Failed to delete submission.');
            }
            
        } catch (Exception $e) {
            error_log("Contact delete error: " . $e->getMessage());
            $this->flash('error', 'An error occurred.');
        }
        
        $this->redirect('/admin/contact');
    }
    
    /**
     * Export submissions to CSV
     */
    public function export() {
        $submissions = $this->contactModel->getAllSubmissions(null, 1000);
        
        // Set headers for CSV download
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename=contact_submissions_' . date('Y-m-d') . '.csv');
        
        $output = fopen('php://output', 'w');
        
        // Add BOM for UTF-8
        fwrite($output, "\xEF\xBB\xBF");
        
        // Write headers
        fputcsv($output, [
            'ID', 'Name', 'Email', 'Phone', 'Subject', 'Message', 
            'Department', 'Status', 'Created At', 'Responded At', 'Admin Notes'
        ]);
        
        // Write data
        foreach ($submissions as $submission) {
            fputcsv($output, [
                $submission['id'],
                $submission['name'],
                $submission['email'],
                $submission['phone'] ?? '',
                $submission['subject'],
                strip_tags($submission['message']),
                $submission['department'],
                $submission['status'],
                $submission['created_at'],
                $submission['responded_at'] ?? '',
                $submission['admin_notes'] ?? ''
            ]);
        }
        
        fclose($output);
        exit;
    }
    
    /**
     * Contact settings management
     */
    public function settings() {
        // Only admin can access settings
        if ($_SESSION['user_role'] !== 'admin') {
            $this->flash('error', 'Access denied. Admin privileges required.');
            $this->redirect('/admin/contact');
        }
        
        $settings = $this->contactModel->getContactSettings();
        
        $this->data = array_merge($this->data, [
            'page_title' => 'Contact Settings',
            'currentPage' => 'contact-settings',
            'settings' => $settings,
            'flash_success' => $this->getFlash('success'),
            'flash_error' => $this->getFlash('error'),
            'csrf_token' => $this->csrfToken()
        ]);
        
        $this->render('admin/contact/settings');
    }
    
    /**
     * Save contact settings
     */
    public function saveSettings() {
        // Enable error reporting temporarily for debugging
        error_reporting(E_ALL);
        ini_set('display_errors', 1);
        
        if ($_SESSION['user_role'] !== 'admin') {
            $this->flash('error', 'Access denied.');
            $this->redirect('/admin/contact');
        }
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->flash('error', 'Invalid request method.');
            $this->redirect('/admin/contact/settings');
        }
        
        try {
            error_log("=== Contact Settings Save Started ===");
            error_log("POST data received: " . print_r($_POST, true));
            
            $this->validateCsrf();
            
            $settings = [
                'phone' => trim($this->input('phone', '')),
                'email' => trim($this->input('email', '')),
                'address' => trim($this->input('address', '')),
                'hours' => trim($this->input('hours', '')),
                'emergency' => trim($this->input('emergency', '')),
                'admissions_email' => trim($this->input('admissions_email', '')),
                'map_latitude' => trim($this->input('map_latitude', '')),
                'map_longitude' => trim($this->input('map_longitude', ''))
            ];
            
            // Debug log the processed settings
            error_log("Processed settings to save: " . print_r($settings, true));
            
            // Validate email formats
            if (!empty($settings['email']) && !filter_var($settings['email'], FILTER_VALIDATE_EMAIL)) {
                $this->flash('error', 'Invalid primary email format.');
                error_log("Validation failed: Invalid primary email");
                $this->redirect('/admin/contact/settings');
            }
            
            if (!empty($settings['admissions_email']) && !filter_var($settings['admissions_email'], FILTER_VALIDATE_EMAIL)) {
                $this->flash('error', 'Invalid admissions email format.');
                error_log("Validation failed: Invalid admissions email");
                $this->redirect('/admin/contact/settings');
            }
            
            error_log("Calling saveContactSettings with data...");
            $saved = $this->contactModel->saveContactSettings($settings);
            
            error_log("saveContactSettings returned: " . ($saved ? 'true' : 'false'));
            
            if ($saved) {
                $this->flash('success', 'Settings saved successfully.');
                error_log("Flash message set: Settings saved successfully");
            } else {
                $this->flash('error', 'Failed to save settings.');
                error_log("Flash message set: Failed to save settings");
            }
            
        } catch (Exception $e) {
            error_log("Settings save error: " . $e->getMessage() . "\n" . $e->getTraceAsString());
            $this->flash('error', 'An error occurred: ' . $e->getMessage());
        } finally {
            // Restore error reporting
            error_reporting(0);
            ini_set('display_errors', 0);
            error_log("=== Contact Settings Save Completed ===");
        }
        
        $this->redirect('/admin/contact/settings');
    }
}
?>