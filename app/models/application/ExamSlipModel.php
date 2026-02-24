<?php
/**
 * Exam Slip Model
 * 
 * Handles exam slip data operations
 * ADDED: incrementDownloadCount() — called by ExamSlipController on every
 *        view, print, or download so the download_count column stays accurate.
 * 
 * @package FCT_CNS
 * @subpackage Application
 */

require_once MODELS_PATH . '/BaseModel.php';

class ExamSlipModel extends BaseModel {
    
    protected $table = 'exam_slips';
    protected $primaryKey = 'id';
    
    /**
     * Constructor
     */
    public function __construct() {
        parent::__construct();
    }
    
    /**
     * Generate unique slip number
     */
    public function generateSlipNumber() {
        $year = date('Y');
        $prefix = 'CNS/EXAM/' . $year . '/';
        
        $last = $this->fetchOne(
            "SELECT slip_number FROM {$this->table} 
             WHERE slip_number LIKE :prefix 
             ORDER BY id DESC LIMIT 1",
            ['prefix' => $prefix . '%']
        );
        
        if ($last && preg_match('/(\d+)$/', $last['slip_number'], $matches)) {
            $number = intval($matches[1]) + 1;
        } else {
            $number = 1;
        }
        
        return $prefix . str_pad($number, 5, '0', STR_PAD_LEFT);
    }
    
    /**
     * Create exam slip
     */
    public function create($data) {
        if (empty($data['slip_number'])) {
            $data['slip_number'] = $this->generateSlipNumber();
        }
        
        return parent::insert($data);
    }
    
    /**
     * Get exam slip by application ID
     */
    public function getByApplicationId($applicationId) {
        return $this->fetchOne(
            "SELECT * FROM {$this->table} WHERE application_id = :application_id",
            ['application_id' => $applicationId]
        );
    }
    
    /**
     * Get exam slip by slip number
     */
    public function getBySlipNumber($slipNumber) {
        return $this->fetchOne(
            "SELECT es.*, a.application_number, a.jamb_number, a.first_name, a.last_name,
                    app.email, app.phone
             FROM {$this->table} es
             JOIN applications a ON es.application_id = a.id
             JOIN applicants app ON es.applicant_id = app.id
             WHERE es.slip_number = :slip_number",
            ['slip_number' => $slipNumber]
        );
    }
    
    /**
     * Get exam slip for printing
     */
    public function getForPrint($slipNumber) {
        $slip = $this->getBySlipNumber($slipNumber);
        
        if (!$slip) {
            return null;
        }
        
        // Get O'Level results
        require_once MODELS_PATH . '/application/OlevelResultModel.php';
        $olevelModel = new OlevelResultModel();
        $slip['olevel_results'] = $olevelModel->getByApplicationId($slip['application_id']);
        
        // Get payment info
        require_once MODELS_PATH . '/application/PaymentModel.php';
        $paymentModel = new PaymentModel();
        $payments = $paymentModel->getByApplicationId($slip['application_id']);
        
        // Find the successful payment
        $slip['payment'] = null;
        foreach ($payments as $payment) {
            if ($payment['status'] === 'success') {
                $slip['payment'] = $payment;
                break;
            }
        }
        
        return $slip;
    }
    
    /**
     * Record download
     * Stores IP address and user agent alongside the count increment.
     * Use this for audit logging. For a simple counter-only update,
     * use incrementDownloadCount() instead.
     */
    public function recordDownload($slipId, $ipAddress, $userAgent) {
        // First get current download count
        $current = $this->fetchOne(
            "SELECT download_count FROM {$this->table} WHERE id = :id",
            ['id' => $slipId]
        );
        
        if (!$current) {
            return false;
        }
        
        return $this->update(
            [
                'download_count' => ($current['download_count'] ?? 0) + 1,
                'downloaded_at'  => date('Y-m-d H:i:s'),
                'ip_address'     => $ipAddress,
                'user_agent'     => $userAgent,
            ],
            'id = :id',
            ['id' => $slipId]
        );
    }

    /**
     * Increment download counter (lightweight — no IP/UA logging).
     * Called by ExamSlipController every time the slip is viewed,
     * printed, or downloaded so the download_count stays accurate.
     *
     * @param  int  $slipId  Primary key of the exam_slips row
     * @return bool
     */
    public function incrementDownloadCount($slipId) {
        try {
            $current = $this->fetchOne(
                "SELECT download_count FROM {$this->table} WHERE id = :id",
                ['id' => (int)$slipId]
            );

            if (!$current) {
                return false;
            }

            return $this->update(
                [
                    'download_count' => ((int)($current['download_count'] ?? 0)) + 1,
                    'downloaded_at'  => date('Y-m-d H:i:s'),
                ],
                'id = :id',
                ['id' => (int)$slipId]
            );
        } catch (\Exception $e) {
            error_log('ExamSlipModel::incrementDownloadCount — ' . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Generate QR code data
     */
    public function generateQRData($slipId) {
        $slip = $this->find($slipId);
        
        if (!$slip) {
            return null;
        }
        
        $data = [
            'slip_number'      => $slip['slip_number'],
            'application_id'   => $slip['application_id'],
            'exam_date'        => $slip['exam_date'],
            'exam_time'        => $slip['exam_time'],
            'seat_number'      => $slip['seat_number'],
            'verification_url' => (defined('BASE_URL') ? BASE_URL : '') . '/verify/exam-slip/' . $slip['slip_number'],
        ];
        
        return json_encode($data);
    }
    
    /**
     * Verify exam slip
     */
    public function verifySlip($slipNumber) {
        $slip = $this->getBySlipNumber($slipNumber);
        
        if (!$slip) {
            return ['valid' => false, 'message' => 'Exam slip not found'];
        }
        
        $examDate = strtotime($slip['exam_date']);
        $today    = strtotime(date('Y-m-d'));
        
        $status = 'upcoming';
        if ($examDate < $today) {
            $status = 'past';
        } elseif ($examDate == $today) {
            $status = 'today';
        }
        
        return [
            'valid'   => true,
            'slip'    => $slip,
            'status'  => $status,
            'message' => 'Exam slip verified successfully',
        ];
    }
    
    /**
     * Get exam slips by date
     */
    public function getByDate($date) {
        return $this->fetchAll(
            "SELECT es.*, a.application_number, a.jamb_number, a.first_name, a.last_name,
                    app.email, app.phone
             FROM {$this->table} es
             JOIN applications a ON es.application_id = a.id
             JOIN applicants app ON es.applicant_id = app.id
             WHERE DATE(es.exam_date) = :date
             ORDER BY es.exam_time, es.seat_number",
            ['date' => $date]
        );
    }
    
    /**
     * Count exam slips by date
     */
    public function countByDate($date) {
        return $this->fetchColumn(
            "SELECT COUNT(*) FROM {$this->table} WHERE DATE(exam_date) = :date",
            ['date' => $date]
        );
    }
    
    /**
     * Get exam slips by venue
     */
    public function getByVenue($venue) {
        return $this->fetchAll(
            "SELECT es.*, a.application_number, a.jamb_number, a.first_name, a.last_name,
                    app.email, app.phone
             FROM {$this->table} es
             JOIN applications a ON es.application_id = a.id
             JOIN applicants app ON es.applicant_id = app.id
             WHERE es.exam_venue = :venue
             ORDER BY es.exam_date, es.exam_time, es.seat_number",
            ['venue' => $venue]
        );
    }
    
    /**
     * Get exam statistics
     */
    public function getStats() {
        $stats = $this->fetchOne("
            SELECT 
                COUNT(*)                  AS total_slips,
                COUNT(DISTINCT exam_date) AS exam_days,
                COUNT(DISTINCT exam_venue) AS venues,
                MIN(exam_date)            AS first_exam_date,
                MAX(exam_date)            AS last_exam_date,
                SUM(download_count)       AS total_downloads
            FROM {$this->table}
        ");
        
        $today = date('Y-m-d');
        $stats['today_exams'] = $this->countByDate($today);
        
        return $stats;
    }
    
    /**
     * Check if exam slip exists for application
     */
    public function existsForApplication($applicationId) {
        $count = $this->fetchColumn(
            "SELECT COUNT(*) FROM {$this->table} WHERE application_id = :application_id",
            ['application_id' => $applicationId]
        );
        
        return $count > 0;
    }
}