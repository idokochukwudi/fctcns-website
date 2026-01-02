<?php
// Test the controller system directly

// Load constants
require_once dirname(__DIR__) . '/app/config/constants.php';

// Test the controller
class TestController extends Controller {
    public function test() {
        error_log("TestController: Rendering test view");
        
        $this->data = [
            'page_title' => 'Test Page',
            'currentPage' => 'test',
            'baseUrl' => BASE_URL
        ];
        
        $this->render('home');
    }
}

// Run test
$controller = new TestController();
$controller->test();
?>