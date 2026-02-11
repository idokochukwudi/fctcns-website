<?php
/**
 * Newsletter Controller - Handle AJAX subscription requests
 */
class NewsletterController extends Controller {
    
    private $newsletterModel;
    
    public function __construct() {
        parent::__construct();
        
        require_once APP_PATH . '/models/NewsletterModel.php';
        require_once APP_PATH . '/config/database.php';
        
        $database = Database::getInstance();
        $this->db = $database->getConnection();
        $this->newsletterModel = new NewsletterModel($this->db);
    }
    
    /**
     * Handle newsletter subscription (AJAX endpoint)
     */
    public function subscribe() {
        // Set JSON header
        header('Content-Type: application/json');
        
        // Only accept POST requests
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode([
                'success' => false,
                'message' => 'Method not allowed'
            ]);
            return;
        }
        
        // Get email from POST
        $email = trim($_POST['email'] ?? '');
        $source = $_POST['source'] ?? 'newsletter_sidebar';
        
        // Validate email
        if (empty($email)) {
            echo json_encode([
                'success' => false,
                'message' => 'Email address is required'
            ]);
            return;
        }
        
        // Subscribe - this now sends welcome email automatically
        $result = $this->newsletterModel->subscribe($email, $source);
        
        // Return JSON response
        echo json_encode($result);
    }
    
    /**
     * Handle unsubscribe
     */
    public function unsubscribe() {
        $email = $_GET['email'] ?? '';
        $token = $_GET['token'] ?? '';
        
        $result = $this->newsletterModel->unsubscribe($email, $token);
        
        // Show unsubscribe confirmation page
        $this->data = array_merge($this->data, [
            'success' => $result['success'],
            'message' => $result['message'],
            'email' => $email
        ]);
        
        $this->render('pages/newsletter/unsubscribe');
    }
    
    /**
     * Handle confirmation
     */
    public function confirm() {
        $email = $_GET['email'] ?? '';
        $token = $_GET['token'] ?? '';
        
        $confirmed = $this->newsletterModel->confirm($email, $token);
        
        $this->data = array_merge($this->data, [
            'confirmed' => $confirmed,
            'email' => $email
        ]);
        
        $this->render('pages/newsletter/confirm');
    }
}