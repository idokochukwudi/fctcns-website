<?php
/**
 * Application Model
 * 
 * Handles application data operations
 * 
 * @package FCT_CNS
 * @subpackage Application
 */

require_once MODELS_PATH . '/BaseModel.php';

class ApplicationModel extends BaseModel {
    
    protected $table = 'applications';
    protected $primaryKey = 'id';
    
    /**
     * Constructor
     */
    public function __construct() {
        parent::__construct();
    }
    
    /**
     * Generate unique application number
     */
    public function generateApplicationNumber() {
        $year = date('Y');
        $prefix = 'FCT/CNS/' . $year . '/';
        
        // Get last application number for this year
        $last = $this->fetchOne(
            "SELECT application_number FROM {$this->table} 
             WHERE application_number LIKE :prefix 
             ORDER BY id DESC LIMIT 1",
            ['prefix' => $prefix . '%']
        );
        
        if ($last && preg_match('/(\d+)$/', $last['application_number'], $matches)) {
            $number = intval($matches[1]) + 1;
        } else {
            $number = 1;
        }
        
        return $prefix . str_pad($number, 5, '0', STR_PAD_LEFT);
    }
    
    /**
     * Create new application
     */
    public function createApplication($applicantId, $jambData) {
        try {
            $this->beginTransaction();
            
            $applicationNumber = $this->generateApplicationNumber();
            
            // Check if $jambData is an array or just a string (JAMB number)
            if (is_array($jambData)) {
                // It's an array of JAMB candidate data
                $jambNumber = $jambData['jamb_number'] ?? '';
                $jambCandidateId = $jambData['id'] ?? null;
                $firstName = $jambData['first_name'] ?? '';
                $lastName = $jambData['last_name'] ?? '';
                $otherNames = $jambData['other_names'] ?? null;
                $gender = $jambData['gender'] ?? null;
                $stateOfOrigin = $jambData['state_of_origin'] ?? '';
                $lga = $jambData['lga'] ?? '';
                $programApplied = $jambData['program_applied'] ?? 'ND Nursing';
                $utmeScore = $jambData['aggregate_score'] ?? null;
            } else {
                // It's just a JAMB number string (backward compatibility)
                $jambNumber = $jambData;
                $jambCandidateId = null;
                $firstName = '';
                $lastName = '';
                $otherNames = null;
                $gender = null;
                $stateOfOrigin = '';
                $lga = '';
                $programApplied = 'ND Nursing';
                $utmeScore = null;
            }
            
            // Base required fields that definitely exist
            $data = [
                'applicant_id' => $applicantId,
                'application_number' => $applicationNumber,
                'jamb_number' => $jambNumber,
                'jamb_candidate_id' => $jambCandidateId,
                'first_name' => $firstName,
                'last_name' => $lastName,
                'email' => null,
                'phone' => null,
                'program' => $programApplied,
                'entry_year' => date('Y'),
                'status' => 'pending',
                'application_step' => 2,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ];
            
            // Add optional fields if they have values
            if (!empty($otherNames)) {
                $data['other_names'] = $otherNames;
            }
            
            if (!empty($gender)) {
                $data['gender'] = $gender;
            }
            
            if (!empty($stateOfOrigin)) {
                $data['state_of_origin'] = $stateOfOrigin;
            }
            
            if (!empty($lga)) {
                $data['lga'] = $lga;
            }
            
            if (!empty($utmeScore)) {
                $data['utme_score'] = $utmeScore;
            }
            
            // Set program_choice_1 to the program applied
            $data['program_choice_1'] = $programApplied;
            
            error_log("Inserting application with data: " . print_r($data, true));
            
            $applicationId = $this->insert($data);
            
            if (!$applicationId) {
                throw new Exception("Failed to insert application");
            }
            
            $this->commit();
            
            return $applicationId;
            
        } catch (Exception $e) {
            $this->rollback();
            error_log("ApplicationModel::createApplication - Error: " . $e->getMessage());
            error_log("Stack trace: " . $e->getTraceAsString());
            throw $e;
        }
    }
    
    /**
     * Get application by applicant ID
     */
    public function getByApplicantId($applicantId) {
        return $this->fetchOne(
            "SELECT * FROM {$this->table} WHERE applicant_id = :applicant_id",
            ['applicant_id' => $applicantId]
        );
    }
    
    /**
     * Get application by JAMB number
     */
    public function getByJambNumber($jambNumber) {
        return $this->fetchOne(
            "SELECT a.*, app.id as applicant_id, app.email_verified 
             FROM {$this->table} a
             JOIN applicants app ON a.applicant_id = app.id
             WHERE a.jamb_number = :jamb_number",
            ['jamb_number' => $jambNumber]
        );
    }
    
    /**
     * Get application with all details
     */
    public function getWithDetails($applicationId) {
        return $this->fetchOne(
            "SELECT a.*, app.email, app.phone, app.date_of_birth, app.gender,
                    app.state_of_origin, app.lga, app.address,
                    p.status as payment_status, p.amount as payment_amount,
                    p.reference as payment_reference, p.payment_date,
                    es.slip_number, es.exam_date, es.exam_time, es.seat_number,
                    es.generated_at as exam_slip_generated_at
             FROM {$this->table} a
             JOIN applicants app ON a.applicant_id = app.id
             LEFT JOIN application_payments p ON a.id = p.application_id AND p.status = 'success'
             LEFT JOIN exam_slips es ON a.id = es.application_id
             WHERE a.id = :id
             ORDER BY p.id DESC
             LIMIT 1",
            ['id' => $applicationId]
        );
    }
    
    /**
     * Get application with all related data
     */
    public function getFullApplication($applicationId) {
        $application = $this->getWithDetails($applicationId);
        
        if (!$application) {
            return null;
        }
        
        // Get O'Level results
        require_once MODELS_PATH . '/application/OlevelResultModel.php';
        $olevelModel = new OlevelResultModel();
        $application['olevel_results'] = $olevelModel->getByApplicationId($applicationId);
        
        // Get documents
        require_once MODELS_PATH . '/application/ApplicationDocumentModel.php';
        $docModel = new ApplicationDocumentModel();
        $application['documents'] = $docModel->getByApplicationId($applicationId);
        
        // Get all payments
        require_once MODELS_PATH . '/application/PaymentModel.php';
        $paymentModel = new PaymentModel();
        $application['payments'] = $paymentModel->getByApplicationId($applicationId);
        
        return $application;
    }
    
    /**
     * Update application step
     */
    public function updateStep($applicationId, $step, $data = []) {
        $updateData = array_merge(['application_step' => $step], $data);
        
        if ($step == 4) {
            $updateData['submitted_at'] = date('Y-m-d H:i:s');
        }
        
        return $this->update($updateData, 'id = :id', ['id' => $applicationId]);
    }
    
    /**
     * Save personal information
     */
    public function savePersonalInfo($applicationId, $data) {
        $updateData = [
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'other_names' => $data['other_names'] ?? null,
            'date_of_birth' => $data['date_of_birth'],
            'gender' => $data['gender'],
            'nationality' => $data['nationality'] ?? 'Nigerian',
            'state_of_origin' => $data['state_of_origin'],
            'lga' => $data['lga'],
            'address' => $data['address'],
            'application_step' => 2
        ];
        
        return $this->update($updateData, 'id = :id', ['id' => $applicationId]);
    }
    
    /**
     * Save program choices
     */
    public function saveProgramChoices($applicationId, $data) {
        $updateData = [
            'program_choice_1' => $data['program_choice_1'],
            'program_choice_2' => $data['program_choice_2'] ?? null,
            'program_choice_3' => $data['program_choice_3'] ?? null,
            'application_step' => 2
        ];
        
        return $this->update($updateData, 'id = :id', ['id' => $applicationId]);
    }
    
    /**
     * Check if application is complete
     */
    public function isComplete($applicationId) {
        $application = $this->find($applicationId);
        
        if (!$application) {
            return false;
        }
        
        // Check if all required fields are filled
        $required = ['first_name', 'last_name', 'date_of_birth', 'gender', 
                     'state_of_origin', 'lga', 'address', 'program_choice_1'];
        
        foreach ($required as $field) {
            if (empty($application[$field])) {
                return false;
            }
        }
        
        // Check if O'Level results exist
        require_once MODELS_PATH . '/application/OlevelResultModel.php';
        $olevelModel = new OlevelResultModel();
        $results = $olevelModel->getByApplicationId($applicationId);
        
        if (empty($results)) {
            return false;
        }
        
        // Check if passport exists
        require_once MODELS_PATH . '/application/ApplicationDocumentModel.php';
        $docModel = new ApplicationDocumentModel();
        $passport = $docModel->getPassport($applicationId);
        
        if (!$passport) {
            return false;
        }
        
        return true;
    }
    
    /**
     * Submit application
     */
    public function submit($applicationId) {
        if (!$this->isComplete($applicationId)) {
            return false;
        }
        
        return $this->update(
            [
                'submitted_at' => date('Y-m-d H:i:s'),
                'application_step' => 3,
                'status' => 'pending'
            ],
            'id = :id',
            ['id' => $applicationId]
        );
    }
    
    /**
     * Get applications by status
     */
    public function getByStatus($status, $limit = null, $offset = null) {
        $sql = "SELECT a.*, app.first_name, app.last_name, app.email, app.phone,
                       p.status as payment_status
                FROM {$this->table} a
                JOIN applicants app ON a.applicant_id = app.id
                LEFT JOIN application_payments p ON a.id = p.application_id AND p.status = 'success'
                WHERE a.status = :status
                ORDER BY a.created_at DESC";
        
        $params = ['status' => $status];
        
        if ($limit !== null) {
            $sql .= " LIMIT " . intval($limit);
            if ($offset !== null) {
                $sql .= " OFFSET " . intval($offset);
            }
        }
        
        return $this->fetchAll($sql, $params);
    }
    
    /**
     * Get applications with payment status
     */
    public function getAllWithPaymentStatus($filters = []) {
        $sql = "SELECT a.*, app.first_name, app.last_name, app.email, app.phone,
                       p.status as payment_status, p.amount, p.reference, p.payment_date
                FROM {$this->table} a
                JOIN applicants app ON a.applicant_id = app.id
                LEFT JOIN application_payments p ON a.id = p.application_id AND p.status = 'success'
                WHERE 1=1";
        
        $params = [];
        
        if (!empty($filters['status'])) {
            $sql .= " AND a.status = :status";
            $params['status'] = $filters['status'];
        }
        
        if (!empty($filters['payment_status'])) {
            if ($filters['payment_status'] === 'paid') {
                $sql .= " AND p.status = 'success'";
            } elseif ($filters['payment_status'] === 'unpaid') {
                $sql .= " AND p.status IS NULL";
            }
        }
        
        if (!empty($filters['search'])) {
            $sql .= " AND (a.application_number LIKE :search OR a.jamb_number LIKE :search 
                        OR app.first_name LIKE :search OR app.last_name LIKE :search 
                        OR app.email LIKE :search)";
            $params['search'] = '%' . $filters['search'] . '%';
        }
        
        $sql .= " ORDER BY a.created_at DESC";
        
        return $this->fetchAll($sql, $params);
    }
    
    /**
     * Get application statistics
     */
    public function getStats() {
        $stats = $this->fetchOne("
            SELECT 
                COUNT(*) as total_applications,
                SUM(CASE WHEN status = 'pending' THEN 1 ELSE 0 END) as pending,
                SUM(CASE WHEN status = 'reviewed' THEN 1 ELSE 0 END) as reviewed,
                SUM(CASE WHEN status = 'accepted' THEN 1 ELSE 0 END) as accepted,
                SUM(CASE WHEN status = 'rejected' THEN 1 ELSE 0 END) as rejected,
                SUM(CASE WHEN application_step = 4 AND submitted_at IS NOT NULL THEN 1 ELSE 0 END) as completed,
                SUM(CASE WHEN application_step < 4 THEN 1 ELSE 0 END) as incomplete,
                SUM(CASE WHEN EXISTS (SELECT 1 FROM application_payments p WHERE p.application_id = a.id AND p.status = 'success') THEN 1 ELSE 0 END) as paid
            FROM {$this->table} a
        ");
        
        return $stats;
    }
    
    /**
     * Search applications
     */
    public function search($query) {
        $search = '%' . $query . '%';
        
        return $this->fetchAll("
            SELECT a.*, app.first_name, app.last_name, app.email, app.phone,
                   p.status as payment_status
            FROM {$this->table} a
            JOIN applicants app ON a.applicant_id = app.id
            LEFT JOIN application_payments p ON a.id = p.application_id AND p.status = 'success'
            WHERE a.application_number LIKE :search 
               OR a.jamb_number LIKE :search
               OR app.first_name LIKE :search
               OR app.last_name LIKE :search
               OR app.email LIKE :search
               OR app.phone LIKE :search
            ORDER BY a.created_at DESC",
            ['search' => $search]
        );
    }
    
    /**
     * Generate exam slip
     */
    public function generateExamSlip($applicationId) {
        $application = $this->getWithDetails($applicationId);
        
        if (!$application || $application['payment_status'] !== 'success') {
            return false;
        }
        
        try {
            $this->beginTransaction();
            
            // Check if exam slip already exists
            $existing = $this->fetchOne(
                "SELECT * FROM exam_slips WHERE application_id = :application_id",
                ['application_id' => $applicationId]
            );
            
            if ($existing) {
                $this->commit();
                return $existing;
            }
            
            // Generate exam slip data
            require_once MODELS_PATH . '/application/ExamSlipModel.php';
            $examSlipModel = new ExamSlipModel();
            $slipData = [
                'application_id' => $applicationId,
                'applicant_id' => $application['applicant_id'],
                'slip_number' => $examSlipModel->generateSlipNumber(),
                'exam_date' => $this->getExamDate(),
                'exam_time' => $this->getExamTime(),
                'exam_venue' => $this->getExamVenue(),
                'reporting_time' => $this->getReportingTime(),
                'seat_number' => $this->generateSeatNumber(),
                'qr_code' => $this->generateQRCodeData($applicationId),
                'generated_at' => date('Y-m-d H:i:s'),
                'download_count' => 0
            ];
            
            $slipId = $examSlipModel->create($slipData);
            
            // Update application
            $this->update(
                [
                    'exam_slip_generated' => 1,
                    'application_step' => 4
                ],
                'id = :id',
                ['id' => $applicationId]
            );
            
            $this->commit();
            
            return $examSlipModel->find($slipId);
            
        } catch (Exception $e) {
            $this->rollback();
            error_log("ApplicationModel::generateExamSlip - Error: " . $e->getMessage());
            throw $e;
        }
    }
    
    /**
     * Get exam date (implement based on your logic)
     */
    private function getExamDate() {
        require_once MODELS_PATH . '/application/SettingsModel.php';
        $settings = new SettingsModel();
        $startDate = $settings->get('cbt_start_date', date('Y-m-d'));
        return $startDate;
    }
    
    /**
     * Get exam time (implement based on your logic)
     */
    private function getExamTime() {
        return '10:00:00';
    }
    
    /**
     * Get exam venue
     */
    private function getExamVenue() {
        return 'FCT College of Nursing Sciences, Gwagwalada (within UATH)';
    }
    
    /**
     * Get reporting time
     */
    private function getReportingTime() {
        return '08:00:00';
    }
    
    /**
     * Generate seat number
     */
    private function generateSeatNumber() {
        $prefix = 'SEAT';
        $random = strtoupper(substr(uniqid(), -6));
        return $prefix . $random;
    }
    
    /**
     * Generate QR code data
     */
    private function generateQRCodeData($applicationId) {
        $data = [
            'app_id' => $applicationId,
            'timestamp' => time(),
            'type' => 'exam_slip'
        ];
        return json_encode($data);
    }
    
    /**
     * Count applications by status
     */
    public function countByStatus($status) {
        return $this->fetchColumn(
            "SELECT COUNT(*) FROM {$this->table} WHERE status = :status",
            ['status' => $status]
        );
    }
    
    /**
     * Get applications submitted today
     */
    public function getTodaySubmissions() {
        return $this->fetchAll(
            "SELECT a.*, app.first_name, app.last_name, app.email
             FROM {$this->table} a
             JOIN applicants app ON a.applicant_id = app.id
             WHERE DATE(a.submitted_at) = CURDATE()
             ORDER BY a.submitted_at DESC"
        );
    }
}