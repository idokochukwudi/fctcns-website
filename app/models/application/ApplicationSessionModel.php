<?php
/**
 * Application Session Model
 * 
 * Handles application session data operations
 * 
 * @package FCT_CNS
 * @subpackage Application
 */

require_once MODELS_PATH . '/BaseModel.php';

class ApplicationSessionModel extends BaseModel {
    
    protected $table = 'application_sessions';
    protected $primaryKey = 'id';
    
    /**
     * Constructor
     */
    public function __construct() {
        parent::__construct();
    }
    
    /**
     * Create a new session record
     * 
     * @param string $sessionId PHP Session ID
     * @param string $ipAddress IP address
     * @param string $userAgent User agent string
     * @param int|null $applicantId Applicant ID (null for guest)
     * @return int|false Session ID or false
     */
    public function createSession($sessionId, $ipAddress, $userAgent, $applicantId = null) {
        // Check if session already exists
        $existing = $this->getSession($sessionId);
        
        if ($existing) {
            // Update existing session
            return $this->update(
                [
                    'applicant_id' => $applicantId,
                    'ip_address' => $ipAddress,
                    'user_agent' => $userAgent,
                    'last_activity' => date('Y-m-d H:i:s')
                ],
                'session_id = :session_id',
                ['session_id' => $sessionId]
            );
        }
        
        // Create new session
        $data = [
            'session_id' => $sessionId,
            'applicant_id' => $applicantId,
            'ip_address' => $ipAddress,
            'user_agent' => $userAgent,
            'current_step' => 1,
            'last_activity' => date('Y-m-d H:i:s'),
            'created_at' => date('Y-m-d H:i:s')
        ];
        
        return $this->insert($data);
    }
    
    /**
     * Get session by session ID
     * 
     * @param string $sessionId PHP Session ID
     * @return array|false Session data or false
     */
    public function getSession($sessionId) {
        return $this->fetchOne(
            "SELECT * FROM {$this->table} WHERE session_id = :session_id",
            ['session_id' => $sessionId]
        );
    }
    
    /**
     * Update session activity
     * 
     * @param string $sessionId PHP Session ID
     * @param array $data Additional data to update
     * @return bool Success
     */
    public function updateActivity($sessionId, $data = []) {
        $updateData = array_merge(
            ['last_activity' => date('Y-m-d H:i:s')],
            $data
        );
        
        return $this->update(
            $updateData,
            'session_id = :session_id',
            ['session_id' => $sessionId]
        );
    }
    
    /**
     * Update current step
     * 
     * @param string $sessionId PHP Session ID
     * @param int $step Current step
     * @return bool Success
     */
    public function updateStep($sessionId, $step) {
        return $this->update(
            [
                'current_step' => $step,
                'last_activity' => date('Y-m-d H:i:s')
            ],
            'session_id = :session_id',
            ['session_id' => $sessionId]
        );
    }
    
    /**
     * Update session data
     * 
     * @param string $sessionId PHP Session ID
     * @param mixed $sessionData Data to store
     * @return bool Success
     */
    public function updateSessionData($sessionId, $sessionData) {
        return $this->update(
            [
                'session_data' => is_array($sessionData) ? json_encode($sessionData) : $sessionData,
                'last_activity' => date('Y-m-d H:i:s')
            ],
            'session_id = :session_id',
            ['session_id' => $sessionId]
        );
    }
    
    /**
     * Delete session
     * 
     * @param string $sessionId PHP Session ID
     * @return bool Success
     */
    public function deleteSession($sessionId) {
        return $this->delete('session_id = :session_id', ['session_id' => $sessionId]);
    }
    
    /**
     * Clean up old sessions (older than specified hours)
     * 
     * @param int $hours Hours to keep (default 24)
     * @return int Number of deleted sessions
     */
    public function cleanOldSessions($hours = 24) {
        $cutoff = date('Y-m-d H:i:s', strtotime("-{$hours} hours"));
        
        return $this->delete('last_activity < :cutoff', ['cutoff' => $cutoff]);
    }
    
    /**
     * Get active sessions count
     * 
     * @param int $minutes Minutes to consider active (default 15)
     * @return int Active sessions count
     */
    public function getActiveSessionsCount($minutes = 15) {
        $cutoff = date('Y-m-d H:i:s', strtotime("-{$minutes} minutes"));
        
        return $this->fetchColumn(
            "SELECT COUNT(*) FROM {$this->table} WHERE last_activity > :cutoff",
            ['cutoff' => $cutoff]
        );
    }
    
    /**
     * Get sessions by applicant
     * 
     * @param int $applicantId Applicant ID
     * @return array Sessions
     */
    public function getByApplicant($applicantId) {
        return $this->fetchAll(
            "SELECT * FROM {$this->table} WHERE applicant_id = :applicant_id ORDER BY last_activity DESC",
            ['applicant_id' => $applicantId]
        );
    }
    
    /**
     * End all sessions for an applicant (force logout)
     * 
     * @param int $applicantId Applicant ID
     * @return int Number of deleted sessions
     */
    public function endApplicantSessions($applicantId) {
        return $this->delete('applicant_id = :applicant_id', ['applicant_id' => $applicantId]);
    }
    
    /**
     * Get session by applicant ID
     * 
     * @param int $applicantId Applicant ID
     * @return array|false Session data or false
     */
    public function getByApplicantId($applicantId) {
        return $this->fetchOne(
            "SELECT * FROM {$this->table} WHERE applicant_id = :applicant_id ORDER BY last_activity DESC LIMIT 1",
            ['applicant_id' => $applicantId]
        );
    }
    
    /**
     * Check if session is active
     * 
     * @param string $sessionId PHP Session ID
     * @param int $timeout Timeout in minutes (default 30)
     * @return bool True if active
     */
    public function isSessionActive($sessionId, $timeout = 30) {
        $cutoff = date('Y-m-d H:i:s', strtotime("-{$timeout} minutes"));
        
        $count = $this->fetchColumn(
            "SELECT COUNT(*) FROM {$this->table} 
             WHERE session_id = :session_id AND last_activity > :cutoff",
            ['session_id' => $sessionId, 'cutoff' => $cutoff]
        );
        
        return $count > 0;
    }
}