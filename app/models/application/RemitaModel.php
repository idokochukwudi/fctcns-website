<?php
/**
 * Remita Model
 * 
 * Handles all Remita payment integration
 * 
 * @package FCT_CNS
 * @subpackage Application
 */

require_once MODELS_PATH . '/BaseModel.php';

class RemitaModel extends BaseModel {
    
    protected $table = 'remita_transactions';
    protected $primaryKey = 'id';
    
    // Remita API Configuration
    private $merchantId;
    private $serviceTypeId;
    private $apiKey;
    private $apiHash;
    private $publicKey;
    private $secretKey;
    private $environment;
    private $baseUrl;
    
    /**
     * Constructor - Load configuration from .env
     */
    public function __construct() {
        parent::__construct();
        
        // Load from .env (these are available via $_ENV from constants.php)
        $this->merchantId = $_ENV['REMITA_MERCHANT_ID'] ?? '2547916';
        $this->serviceTypeId = $_ENV['REMITA_SERVICE_TYPE_ID'] ?? '4430731';
        $this->apiKey = $_ENV['REMITA_API_KEY'] ?? '1946';
        $this->apiHash = $_ENV['REMITA_API_HASH'] ?? 'DEMO_API_HASH';
        $this->publicKey = $_ENV['REMITA_PUBLIC_KEY'] ?? '';
        $this->secretKey = $_ENV['REMITA_SECRET_KEY'] ?? '';
        $this->environment = $_ENV['REMITA_ENVIRONMENT'] ?? 'demo';
        
        // Set the correct API base URL based on environment
        if ($this->environment === 'live') {
            $this->baseUrl = 'https://login.remita.net/remita/exapp/api/v1/send/api';
        } else {
            $this->baseUrl = 'https://remitademo.net/remita/exapp/api/v1/send/api';
        }
        
        // Log for debugging (remove in production)
        error_log("Remita initialized with environment: " . $this->environment);
    }
    
    /**
     * Generate API hash for Remita
     */
    public function generateApiHash($rrr, $amount) {
        $string = $this->merchantId . $this->serviceTypeId . $rrr . $amount . $this->apiKey;
        return hash('sha512', $string);
    }
    
    /**
     * Generate RRR request hash
     */
    public function generateRRRHash($orderId, $amount) {
        $string = $this->merchantId . $this->serviceTypeId . $orderId . $amount . $this->apiKey;
        return hash('sha512', $string);
    }
    
    /**
     * Create transaction record
     */
    public function createTransaction($paymentId, $rrr, $orderId, $amount) {
        $apiHash = $this->generateApiHash($rrr, $amount);
        
        $data = [
            'payment_id' => $paymentId,
            'rrr' => $rrr,
            'order_id' => $orderId,
            'amount' => $amount,
            'merchant_id' => $this->merchantId,
            'service_type_id' => $this->serviceTypeId,
            'api_hash' => $apiHash,
            'status' => 'pending',
            'created_at' => date('Y-m-d H:i:s')
        ];
        
        return $this->insert($data);
    }
    
    /**
     * Get transaction by RRR
     */
    public function getByRRR($rrr) {
        return $this->fetchOne(
            "SELECT * FROM {$this->table} WHERE rrr = :rrr",
            ['rrr' => $rrr]
        );
    }
    
    /**
     * Get transaction by order ID
     */
    public function getByOrderId($orderId) {
        return $this->fetchOne(
            "SELECT * FROM {$this->table} WHERE order_id = :order_id",
            ['order_id' => $orderId]
        );
    }
    
    /**
     * Update transaction with request data
     */
    public function updateRequestData($transactionId, $requestData) {
        return $this->update(
            ['request_data' => is_array($requestData) ? json_encode($requestData) : $requestData],
            'id = :id',
            ['id' => $transactionId]
        );
    }
    
    /**
     * Update transaction with response data
     */
    public function updateResponseData($transactionId, $responseData) {
        return $this->update(
            ['response_data' => is_array($responseData) ? json_encode($responseData) : $responseData],
            'id = :id',
            ['id' => $transactionId]
        );
    }
    
    /**
     * Update transaction with payment data (after verification)
     */
    public function updatePaymentData($transactionId, $paymentData) {
        return $this->update(
            ['payment_data' => is_array($paymentData) ? json_encode($paymentData) : $paymentData],
            'id = :id',
            ['id' => $transactionId]
        );
    }
    
    /**
     * Update transaction with notification data
     */
    public function updateNotificationData($transactionId, $notificationData) {
        return $this->update(
            ['notification_data' => is_array($notificationData) ? json_encode($notificationData) : $notificationData],
            'id = :id',
            ['id' => $transactionId]
        );
    }
    
    /**
     * Update transaction status
     */
    public function updateStatus($transactionId, $status) {
        return $this->update(
            ['status' => $status, 'updated_at' => date('Y-m-d H:i:s')],
            'id = :id',
            ['id' => $transactionId]
        );
    }
    
    /**
     * Generate RRR with Remita API
     */
    public function generateRRRRemita($orderId, $amount, $payerName, $payerEmail, $payerPhone) {
        $apiHash = $this->generateRRRHash($orderId, $amount);
        
        $requestData = [
            'merchantId' => $this->merchantId,
            'serviceTypeId' => $this->serviceTypeId,
            'orderId' => $orderId,
            'amount' => $amount,
            'payerName' => $payerName,
            'payerEmail' => $payerEmail,
            'payerPhone' => $payerPhone,
            'description' => 'Application Fee Payment',
            'responseUrl' => (defined('BASE_URL') ? BASE_URL : '') . '/payment/remita-response',
            'apiHash' => $apiHash
        ];
        
        // For demo, simulate successful RRR generation
        if ($this->environment === 'demo') {
            $demoRRR = 'DEMO' . time() . mt_rand(1000, 9999);
            
            return [
                'status' => 'success',
                'rrr' => $demoRRR,
                'message' => 'Demo RRR generated',
                'request_data' => $requestData
            ];
        }
        
        // Live API implementation
        try {
            $ch = curl_init($this->baseUrl . '/rrr');
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($requestData));
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'Content-Type: application/json',
                'Authorization: remitaConsumerKey=' . $this->apiKey . ', remitaConsumerToken=' . $apiHash
            ]);
            
            $response = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);
            
            if ($httpCode === 200) {
                $result = json_decode($response, true);
                
                if (!empty($result['rrr'])) {
                    return [
                        'status' => 'success',
                        'rrr' => $result['rrr'],
                        'message' => 'RRR generated successfully',
                        'request_data' => $requestData,
                        'response_data' => $result
                    ];
                }
            }
            
            return [
                'status' => 'error',
                'message' => 'Failed to generate RRR',
                'request_data' => $requestData,
                'response_data' => $response
            ];
            
        } catch (Exception $e) {
            error_log("RemitaModel::generateRRRRemita - Error: " . $e->getMessage());
            
            return [
                'status' => 'error',
                'message' => 'Exception: ' . $e->getMessage(),
                'request_data' => $requestData
            ];
        }
    }
    
    /**
     * Verify payment with Remita API
     */
    public function verifyPayment($rrr) {
        $apiHash = $this->generateApiHash($rrr, '0'); // Amount not needed for verification
        
        // For demo, simulate successful verification
        if ($this->environment === 'demo') {
            return [
                'status' => 'success',
                'message' => 'Demo payment verified',
                'payment_data' => [
                    'rrr' => $rrr,
                    'amount' => 2200,
                    'transactionId' => 'TXN' . time(),
                    'paymentDate' => date('Y-m-d H:i:s'),
                    'paymentStatus' => 'PAID',
                    'payerName' => 'Demo Payer',
                    'payerEmail' => 'demo@example.com'
                ]
            ];
        }
        
        // Live API implementation
        try {
            $ch = curl_init($this->baseUrl . '/rrr/' . $rrr . '/status');
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'Content-Type: application/json',
                'Authorization: remitaConsumerKey=' . $this->apiKey . ', remitaConsumerToken=' . $apiHash
            ]);
            
            $response = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);
            
            if ($httpCode === 200) {
                $result = json_decode($response, true);
                
                if (!empty($result['paymentStatus']) && $result['paymentStatus'] === 'PAID') {
                    return [
                        'status' => 'success',
                        'message' => 'Payment verified successfully',
                        'payment_data' => $result
                    ];
                }
            }
            
            return [
                'status' => 'error',
                'message' => 'Payment not found or not completed',
                'response_data' => $response
            ];
            
        } catch (Exception $e) {
            error_log("RemitaModel::verifyPayment - Error: " . $e->getMessage());
            
            return [
                'status' => 'error',
                'message' => 'Exception: ' . $e->getMessage()
            ];
        }
    }
    
    /**
     * Check transaction status
     */
    public function checkStatus($rrr) {
        $transaction = $this->getByRRR($rrr);
        
        if (!$transaction) {
            return ['status' => 'not_found'];
        }
        
        // If already success, return
        if ($transaction['status'] === 'success') {
            return [
                'status' => 'success',
                'transaction' => $transaction
            ];
        }
        
        // Verify with Remita
        $verification = $this->verifyPayment($rrr);
        
        if ($verification['status'] === 'success') {
            // Update transaction
            $this->updatePaymentData($transaction['id'], $verification['payment_data']);
            $this->updateStatus($transaction['id'], 'success');
            
            return [
                'status' => 'success',
                'transaction' => $this->find($transaction['id'])
            ];
        }
        
        return [
            'status' => 'pending',
            'transaction' => $transaction
        ];
    }
    
    /**
     * Get merchant ID
     */
    public function getMerchantId() {
        return $this->merchantId;
    }
    
    /**
     * Get service type ID
     */
    public function getServiceTypeId() {
        return $this->serviceTypeId;
    }
    
    /**
     * Get environment
     */
    public function getEnvironment() {
        return $this->environment;
    }
    
    /**
     * Get base URL
     */
    public function getBaseUrl() {
        return $this->baseUrl;
    }
    
    /**
     * Get transactions by payment ID
     */
    public function getByPaymentId($paymentId) {
        return $this->fetchAll(
            "SELECT * FROM {$this->table} WHERE payment_id = :payment_id ORDER BY created_at DESC",
            ['payment_id' => $paymentId]
        );
    }
    
    /**
     * Get transactions by date range
     */
    public function getByDateRange($startDate, $endDate, $status = null) {
        $sql = "SELECT * FROM {$this->table} WHERE DATE(created_at) BETWEEN :start_date AND :end_date";
        $params = [
            'start_date' => $startDate,
            'end_date' => $endDate
        ];
        
        if ($status) {
            $sql .= " AND status = :status";
            $params['status'] = $status;
        }
        
        $sql .= " ORDER BY created_at DESC";
        
        return $this->fetchAll($sql, $params);
    }
    
    /**
     * Get transaction statistics
     */
    public function getStats() {
        $stats = $this->fetchOne("
            SELECT 
                COUNT(*) as total_transactions,
                SUM(CASE WHEN status = 'success' THEN 1 ELSE 0 END) as successful,
                SUM(CASE WHEN status = 'pending' THEN 1 ELSE 0 END) as pending,
                SUM(CASE WHEN status = 'failed' THEN 1 ELSE 0 END) as failed,
                SUM(CASE WHEN status = 'success' THEN amount ELSE 0 END) as total_amount
            FROM {$this->table}
        ");
        
        $today = $this->fetchOne("
            SELECT 
                COUNT(*) as today_count,
                SUM(amount) as today_amount
            FROM {$this->table}
            WHERE status = 'success' AND DATE(created_at) = CURDATE()
        ");
        
        if (!$stats) {
            $stats = [
                'total_transactions' => 0,
                'successful' => 0,
                'pending' => 0,
                'failed' => 0,
                'total_amount' => 0
            ];
        }
        
        $stats['today_count'] = $today['today_count'] ?? 0;
        $stats['today_amount'] = $today['today_amount'] ?? 0;
        
        return $stats;
    }
    
    /**
     * Get pending transactions older than specified minutes
     */
    public function getStaleTransactions($minutes = 30) {
        $cutoff = date('Y-m-d H:i:s', strtotime("-{$minutes} minutes"));
        
        return $this->fetchAll(
            "SELECT * FROM {$this->table} 
             WHERE status = 'pending' 
             AND created_at < :cutoff
             ORDER BY created_at ASC",
            ['cutoff' => $cutoff]
        );
    }
    
    /**
     * Count transactions by status
     */
    public function countByStatus($status) {
        return $this->fetchColumn(
            "SELECT COUNT(*) FROM {$this->table} WHERE status = :status",
            ['status' => $status]
        );
    }
    
    /**
     * Check if RRR exists
     */
    public function rrrExists($rrr) {
        $count = $this->fetchColumn(
            "SELECT COUNT(*) FROM {$this->table} WHERE rrr = :rrr",
            ['rrr' => $rrr]
        );
        
        return $count > 0;
    }
    
    /**
     * Get latest transaction for payment
     */
    public function getLatestByPaymentId($paymentId) {
        return $this->fetchOne(
            "SELECT * FROM {$this->table} 
             WHERE payment_id = :payment_id 
             ORDER BY created_at DESC 
             LIMIT 1",
            ['payment_id' => $paymentId]
        );
    }
}