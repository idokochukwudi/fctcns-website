<?php
/**
 * Payment Model
 * 
 * Handles payment data operations
 * FIXED: generateRRR() now returns 12-digit format for Remita
 * 
 * @package FCT_CNS
 * @subpackage Application
 */

require_once MODELS_PATH . '/BaseModel.php';

class PaymentModel extends BaseModel {
    
    protected $table = 'application_payments';
    protected $primaryKey = 'id';
    
    /**
     * Constructor
     */
    public function __construct() {
        parent::__construct();
    }
    
    /**
     * Generate unique reference
     */
    public function generateReference() {
        $prefix = 'PAY';
        $timestamp = time();
        $random = mt_rand(10000, 99999);
        return $prefix . $timestamp . $random;
    }
    
    /**
     * Generate RRR (Remita Retrieval Reference) - FIXED for 12-digit format
     */
    public function generateRRR() {
        // Format: 6 digits date (ymd) + 6 random digits = 12 digits total
        $datePart = date('ymd'); // 6 digits (e.g., 240219)
        $randomPart = rand(100000, 999999); // 6 digits
        return $datePart . $randomPart; // 12 digits total
    }
    
    /**
     * Generate Order ID
     */
    public function generateOrderId() {
        return 'ORD' . time() . mt_rand(1000, 9999);
    }
    
    /**
     * Create payment record
     */
    public function createPayment($applicationId, $applicantId, $amount) {
        $reference = $this->generateReference();
        $rrr = $this->generateRRR();
        $orderId = $this->generateOrderId();
        
        $data = [
            'application_id' => $applicationId,
            'applicant_id' => $applicantId,
            'reference' => $reference,
            'rrr' => $rrr,
            'order_id' => $orderId,
            'amount' => $amount,
            'payment_type' => 'application_fee',
            'status' => 'pending',
            'created_at' => date('Y-m-d H:i:s')
        ];
        
        $paymentId = $this->insert($data);
        
        return $this->find($paymentId);
    }
    
    /**
     * Get payments by application ID
     */
    public function getByApplicationId($applicationId) {
        return $this->fetchAll(
            "SELECT * FROM {$this->table} WHERE application_id = :application_id ORDER BY created_at DESC",
            ['application_id' => $applicationId]
        );
    }
    
    /**
     * Get payment by RRR
     */
    public function getByRRR($rrr) {
        return $this->fetchOne(
            "SELECT p.*, a.application_number, a.jamb_number, ap.first_name, ap.last_name, ap.email, ap.phone
             FROM {$this->table} p
             JOIN applications a ON p.application_id = a.id
             JOIN applicants ap ON p.applicant_id = ap.id
             WHERE p.rrr = :rrr",
            ['rrr' => $rrr]
        );
    }
    
    /**
     * Get payment by reference
     */
    public function getByReference($reference) {
        return $this->fetchOne(
            "SELECT * FROM {$this->table} WHERE reference = :reference",
            ['reference' => $reference]
        );
    }
    
    /**
     * Get payment by order ID
     */
    public function getByOrderId($orderId) {
        return $this->fetchOne(
            "SELECT * FROM {$this->table} WHERE order_id = :order_id",
            ['order_id' => $orderId]
        );
    }
    
    /**
     * Check if application has successful payment
     */
    public function hasSuccessfulPayment($applicationId) {
        $payment = $this->fetchOne(
            "SELECT id FROM {$this->table} 
             WHERE application_id = :application_id AND status = 'success' 
             LIMIT 1",
            ['application_id' => $applicationId]
        );
        
        return !empty($payment);
    }
    
    /**
     * Update payment status
     */
    public function updateStatus($paymentId, $status, $data = []) {
        $updateData = array_merge(['status' => $status], $data);
        
        if ($status === 'success') {
            $updateData['payment_date'] = date('Y-m-d H:i:s');
        }
        
        return $this->update($updateData, 'id = :id', ['id' => $paymentId]);
    }
    
    /**
     * Mark payment as success
     */
    public function markAsSuccess($paymentId, $transactionData = []) {
        return $this->updateStatus($paymentId, 'success', [
            'transaction_id' => $transactionData['transaction_id'] ?? null,
            'payment_method' => $transactionData['payment_method'] ?? 'remita',
            'payer_email' => $transactionData['payer_email'] ?? null,
            'payer_name' => $transactionData['payer_name'] ?? null,
            'payment_details' => !empty($transactionData['payment_details']) ? json_encode($transactionData['payment_details']) : null,
            'payment_date' => date('Y-m-d H:i:s')
        ]);
    }
    
    /**
     * Mark payment as failed
     */
    public function markAsFailed($paymentId, $reason = null) {
        return $this->updateStatus($paymentId, 'failed', [
            'payment_details' => $reason ? json_encode(['error' => $reason]) : null
        ]);
    }
    
    /**
     * Update payment with RRR
     */
    public function updateRRR($paymentId, $rrr) {
        return $this->update(
            ['rrr' => $rrr],
            'id = :id',
            ['id' => $paymentId]
        );
    }
    
    /**
     * Get payment statistics
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
     * Get payments by date range
     */
    public function getByDateRange($startDate, $endDate, $status = null) {
        $sql = "SELECT p.*, a.application_number, a.jamb_number,
                       ap.first_name, ap.last_name, ap.email, ap.phone
                FROM {$this->table} p
                JOIN applications a ON p.application_id = a.id
                JOIN applicants ap ON p.applicant_id = ap.id
                WHERE DATE(p.created_at) BETWEEN :start_date AND :end_date";
        
        $params = [
            'start_date' => $startDate,
            'end_date' => $endDate
        ];
        
        if ($status) {
            $sql .= " AND p.status = :status";
            $params['status'] = $status;
        }
        
        $sql .= " ORDER BY p.created_at DESC";
        
        return $this->fetchAll($sql, $params);
    }
    
    /**
     * Get pending payments
     */
    public function getPendingPayments($limit = null) {
        $sql = "SELECT p.*, a.application_number, a.jamb_number,
                       ap.first_name, ap.last_name, ap.email, ap.phone
                FROM {$this->table} p
                JOIN applications a ON p.application_id = a.id
                JOIN applicants ap ON p.applicant_id = ap.id
                WHERE p.status = 'pending'
                ORDER BY p.created_at DESC";
        
        if ($limit) {
            $sql .= " LIMIT " . (int)$limit;
        }
        
        return $this->fetchAll($sql);
    }
    
    /**
     * Get successful payments
     */
    public function getSuccessfulPayments($limit = null) {
        $sql = "SELECT p.*, a.application_number, a.jamb_number,
                       ap.first_name, ap.last_name, ap.email, ap.phone
                FROM {$this->table} p
                JOIN applications a ON p.application_id = a.id
                JOIN applicants ap ON p.applicant_id = ap.id
                WHERE p.status = 'success'
                ORDER BY p.payment_date DESC";
        
        if ($limit) {
            $sql .= " LIMIT " . (int)$limit;
        }
        
        return $this->fetchAll($sql);
    }
    
    /**
     * Get total amount collected
     */
    public function getTotalCollected() {
        $result = $this->fetchOne(
            "SELECT SUM(amount) as total FROM {$this->table} WHERE status = 'success'"
        );
        
        return $result['total'] ?? 0;
    }
    
    /**
     * Get payment by transaction ID
     */
    public function getByTransactionId($transactionId) {
        return $this->fetchOne(
            "SELECT * FROM {$this->table} WHERE transaction_id = :transaction_id",
            ['transaction_id' => $transactionId]
        );
    }
    
    /**
     * Update payment with Remita response data
     */
    public function updateWithRemitaResponse($paymentId, $responseData) {
        $updateData = [
            'payment_details' => json_encode($responseData)
        ];
        
        if (isset($responseData['transactionId'])) {
            $updateData['transaction_id'] = $responseData['transactionId'];
        }
        
        if (isset($responseData['paymentStatus']) && $responseData['paymentStatus'] === 'PAID') {
            $updateData['status'] = 'success';
            $updateData['payment_date'] = date('Y-m-d H:i:s');
        }
        
        return $this->update($updateData, 'id = :id', ['id' => $paymentId]);
    }
    
    /**
     * Count payments by status
     */
    public function countByStatus($status) {
        return $this->fetchColumn(
            "SELECT COUNT(*) FROM {$this->table} WHERE status = :status",
            ['status' => $status]
        );
    }
    
    /**
     * Get payment summary for application
     */
    public function getApplicationPaymentSummary($applicationId) {
        return $this->fetchOne(
            "SELECT 
                COUNT(*) as total_attempts,
                SUM(CASE WHEN status = 'success' THEN 1 ELSE 0 END) as successful_count,
                SUM(CASE WHEN status = 'pending' THEN 1 ELSE 0 END) as pending_count,
                SUM(CASE WHEN status = 'failed' THEN 1 ELSE 0 END) as failed_count,
                MAX(CASE WHEN status = 'success' THEN created_at END) as last_successful_date,
                MAX(created_at) as last_attempt_date
             FROM {$this->table}
             WHERE application_id = :application_id",
            ['application_id' => $applicationId]
        );
    }
}