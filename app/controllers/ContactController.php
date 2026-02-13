<?php
/**
 * Contact Controller - Updated with Reply-to Email and Webmail Links
 * 
 * @package FCTCNS
 */

class ContactController extends Controller {
    private $contactModel;
    
    public function __construct() {
        parent::__construct();
        
        require_once APP_PATH . '/middleware/AuthMiddleware.php';
        AuthMiddleware::authenticate();
        
        $this->layout = 'admin';
        
        require_once APP_PATH . '/models/ContactModel.php';
        $this->contactModel = new ContactModel();
    }
    
    /**
     * List all contact submissions
     */
    public function index() {
        $status = $this->input('status', 'all');
        $search = $this->input('search', '');
        $department = $this->input('department', '');
        
        if (!empty($search)) {
            $submissions = $this->contactModel->searchSubmissions($search);
        } elseif (!empty($department)) {
            $submissions = $this->contactModel->getSubmissionsByDepartment($department);
        } else {
            $submissions = $this->contactModel->getAllSubmissions(
                $status !== 'all' ? $status : null,
                50
            );
        }
        
        $stats = $this->contactModel->getStatistics();
        $settings = $this->contactModel->getContactSettings();
        
        $this->data = array_merge($this->data, [
            'page_title' => 'Contact Management',
            'currentPage' => 'contact',
            'submissions' => $submissions,
            'stats' => $stats,
            'settings' => $settings,
            'current_status' => $status,
            'current_department' => $department,
            'search_term' => $search,
            'csrf_token' => $this->csrfToken()
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
        
        $mailto = $this->contactModel->generateMailtoLink($submission);
        $settings = $this->contactModel->getContactSettings();
        
        $this->data = array_merge($this->data, [
            'page_title' => 'View Contact Submission',
            'currentPage' => 'contact',
            'submission' => $submission,
            'mailto' => $mailto,
            'settings' => $settings,
            'reply_to_email' => $mailto['reply_to'],
            'csrf_token' => $this->csrfToken()
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
     * Quick update - AJAX endpoint for marking as responded
     */
    public function quickUpdate($id) {
        header('Content-Type: application/json');

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['success' => false, 'message' => 'Invalid request method']);
            exit;
        }

        try {
            $raw   = file_get_contents('php://input');
            $input = json_decode($raw, true);

            $status = $input['status'] ?? 'responded';
            $notes  = 'Replied via email on ' . date('Y-m-d H:i:s');

            $data = [
                'status'      => $status,
                'admin_notes' => $notes
            ];

            $updated = $this->contactModel->updateSubmission($id, $data);

            echo json_encode([
                'success' => (bool) $updated,
                'message' => $updated ? 'Status updated successfully' : 'Failed to update status'
            ]);
            exit;

        } catch (Exception $e) {
            error_log("quickUpdate error: " . $e->getMessage());
            echo json_encode(['success' => false, 'message' => 'Server error occurred']);
            exit;
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
        $status = $this->input('status', null);
        $department = $this->input('department', null);
        
        if ($department) {
            $submissions = $this->contactModel->getSubmissionsByDepartment($department, 1000);
        } else {
            $submissions = $this->contactModel->getAllSubmissions($status, 1000);
        }
        
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename=contact_submissions_' . date('Y-m-d') . '.csv');
        
        $output = fopen('php://output', 'w');
        
        fwrite($output, "\xEF\xBB\xBF");
        
        fputcsv($output, [
            'ID', 'Name', 'Email', 'Phone', 'Subject', 'Message', 
            'Department', 'Status', 'Created At', 'Responded At', 'Admin Notes'
        ]);
        
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
        if ($_SESSION['user_role'] !== 'admin') {
            $this->flash('error', 'Access denied. Admin privileges required.');
            $this->redirect('/admin/contact');
        }
        
        $settings = $this->contactModel->getContactSettings();
        
        $this->data = array_merge($this->data, [
            'page_title' => 'Contact Settings',
            'currentPage' => 'contact-settings',
            'settings' => $settings,
            'csrf_token' => $this->csrfToken()
        ]);
        
        $this->render('admin/contact/settings');
    }
    
    /**
     * Save contact settings
     */
    public function saveSettings() {
        if ($_SESSION['user_role'] !== 'admin') {
            $this->flash('error', 'Access denied.');
            $this->redirect('/admin/contact');
        }
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->flash('error', 'Invalid request method.');
            $this->redirect('/admin/contact/settings');
        }
        
        try {
            $this->validateCsrf();
            
            $settings = [
                'reply_to_email' => trim($this->input('reply_to_email', 'noreply@fctcns.edu.ng')),
                'support_email' => trim($this->input('support_email', 'support@fctcns.edu.ng')),
                'billing_email' => trim($this->input('billing_email', 'billing@fctcns.edu.ng')),
                'admissions_email' => trim($this->input('admissions_email', 'admissions@fctcns.edu.ng')),
                'academic_email' => trim($this->input('academic_email', 'academic@fctcns.edu.ng')),
                'phone' => trim($this->input('phone', '')),
                'email' => trim($this->input('email', 'info@fctcns.edu.ng')),
                'address' => trim($this->input('address', '')),
                'hours' => trim($this->input('hours', '')),
                'emergency' => trim($this->input('emergency', '')),
                'map_latitude' => trim($this->input('map_latitude', '9.0765')),
                'map_longitude' => trim($this->input('map_longitude', '7.3986'))
            ];
            
            $emailFields = ['reply_to_email', 'support_email', 'billing_email', 'admissions_email', 'academic_email', 'email'];
            foreach ($emailFields as $field) {
                if (!empty($settings[$field]) && !filter_var($settings[$field], FILTER_VALIDATE_EMAIL)) {
                    $this->flash('error', 'Invalid email format for ' . str_replace('_', ' ', $field));
                    $this->redirect('/admin/contact/settings');
                }
            }
            
            $saved = $this->contactModel->saveContactSettings($settings);
            
            if ($saved) {
                $this->flash('success', 'Settings saved successfully.');
            } else {
                $this->flash('error', 'Failed to save settings.');
            }
            
        } catch (Exception $e) {
            error_log("Settings save error: " . $e->getMessage());
            $this->flash('error', 'An error occurred: ' . $e->getMessage());
        }
        
        $this->redirect('/admin/contact/settings');
    }
    
    /**
     * Bulk operations
     */
    public function bulkAction() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->flash('error', 'Invalid request method.');
            $this->redirect('/admin/contact');
        }
        
        try {
            $this->validateCsrf();
            
            $action = $this->input('bulk_action', '');
            $ids = $this->input('submission_ids', []);
            
            if (empty($ids)) {
                $this->flash('error', 'No submissions selected.');
                $this->redirect('/admin/contact');
            }
            
            switch ($action) {
                case 'mark_responded':
                    $this->contactModel->bulkUpdateStatus($ids, 'responded');
                    $this->flash('success', count($ids) . ' submissions marked as responded.');
                    break;
                    
                case 'mark_archived':
                    $this->contactModel->bulkUpdateStatus($ids, 'archived');
                    $this->flash('success', count($ids) . ' submissions archived.');
                    break;
                    
                case 'delete':
                    if ($_SESSION['user_role'] === 'admin') {
                        foreach ($ids as $id) {
                            $this->contactModel->deleteSubmission($id);
                        }
                        $this->flash('success', count($ids) . ' submissions deleted.');
                    } else {
                        $this->flash('error', 'Only administrators can delete submissions.');
                    }
                    break;
                    
                default:
                    $this->flash('error', 'Invalid bulk action.');
            }
            
        } catch (Exception $e) {
            error_log("Bulk action error: " . $e->getMessage());
            $this->flash('error', 'An error occurred.');
        }
        
        $this->redirect('/admin/contact');
    }
    
    /**
     * Send test email to verify settings
     */
    public function testEmail() {
        if ($_SESSION['user_role'] !== 'admin') {
            $this->jsonResponse(['success' => false, 'message' => 'Access denied.']);
            return;
        }
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->jsonResponse(['success' => false, 'message' => 'Invalid request method.']);
            return;
        }
        
        try {
            $this->validateCsrf();
            
            $email = $this->input('email', '');
            $department = $this->input('department', 'general');
            
            if (empty($email)) {
                $this->jsonResponse(['success' => false, 'message' => 'Email address is required.']);
                return;
            }
            
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $this->jsonResponse(['success' => false, 'message' => 'Invalid email format.']);
                return;
            }
            
            $settings = $this->contactModel->getContactSettings();
            $replyToEmail = $this->contactModel->getReplyToEmail($department);
            
            error_log("Test email sent to: $email, from department: $department, reply-to: $replyToEmail");
            
            $this->jsonResponse([
                'success' => true, 
                'message' => 'Test email sent successfully to ' . $email,
                'reply_to' => $replyToEmail
            ]);
            
        } catch (Exception $e) {
            error_log("Test email error: " . $e->getMessage());
            $this->jsonResponse(['success' => false, 'message' => 'Failed to send test email: ' . $e->getMessage()]);
        }
    }
    
    /**
     * JSON response helper
     */
    private function jsonResponse($data) {
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }
}