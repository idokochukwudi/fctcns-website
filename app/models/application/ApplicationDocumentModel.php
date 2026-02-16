<?php
/**
 * Application Document Model
 * 
 * Handles document uploads for applications
 * 
 * @package FCT_CNS
 * @subpackage Application
 */

require_once MODELS_PATH . '/BaseModel.php';

class ApplicationDocumentModel extends BaseModel {
    
    protected $table = 'application_documents';
    protected $primaryKey = 'id';
    
    /**
     * Constructor
     */
    public function __construct() {
        parent::__construct();
    }
    
    /**
     * Get documents by application ID
     * 
     * @param int $applicationId Application ID
     * @return array Documents
     */
    public function getByApplicationId($applicationId) {
        return $this->fetchAll(
            "SELECT * FROM {$this->table} WHERE application_id = :application_id ORDER BY document_type, uploaded_at DESC",
            ['application_id' => $applicationId]
        );
    }
    
    /**
     * Get passport photo for application
     * 
     * @param int $applicationId Application ID
     * @return array|false Passport document or false
     */
    public function getPassport($applicationId) {
        return $this->fetchOne(
            "SELECT * FROM {$this->table} WHERE application_id = :application_id AND document_type = 'passport' ORDER BY uploaded_at DESC LIMIT 1",
            ['application_id' => $applicationId]
        );
    }
    
    /**
     * Upload document
     * 
     * @param int $applicationId Application ID
     * @param string $documentType Document type
     * @param array $file Uploaded file data ($_FILES['field'])
     * @return int|false Document ID or false
     */
    public function uploadDocument($applicationId, $documentType, $file) {
        // Validate file
        if ($file['error'] !== UPLOAD_ERR_OK) {
            error_log("Upload error: " . $file['error']);
            return false;
        }
        
        // Create upload directory if not exists
        $uploadDir = PUBLIC_PATH . '/assets/uploads/applications/' . $applicationId;
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }
        
        // Generate unique filename
        $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
        $filename = $documentType . '_' . time() . '_' . bin2hex(random_bytes(8)) . '.' . $extension;
        $filepath = $uploadDir . '/' . $filename;
        
        // Move uploaded file
        if (move_uploaded_file($file['tmp_name'], $filepath)) {
            // Save to database
            $data = [
                'application_id' => $applicationId,
                'document_type' => $documentType,
                'file_name' => $file['name'],
                'file_path' => '/assets/uploads/applications/' . $applicationId . '/' . $filename,
                'file_size' => $file['size'],
                'mime_type' => $file['type'],
                'uploaded_at' => date('Y-m-d H:i:s')
            ];
            
            return $this->insert($data);
        }
        
        return false;
    }
    
    /**
     * Delete document
     * 
     * @param int $documentId Document ID
     * @return bool Success
     */
    public function deleteDocument($documentId) {
        $document = $this->find($documentId);
        
        if (!$document) {
            return false;
        }
        
        // Delete file
        $filePath = PUBLIC_PATH . $document['file_path'];
        if (file_exists($filePath)) {
            unlink($filePath);
        }
        
        // Delete database record
        return $this->delete('id = :id', ['id' => $documentId]);
    }
    
    /**
     * Verify document (admin function)
     * 
     * @param int $documentId Document ID
     * @param int $verifiedBy Admin user ID
     * @return bool Success
     */
    public function verifyDocument($documentId, $verifiedBy) {
        return $this->update(
            [
                'is_verified' => 1,
                'verified_at' => date('Y-m-d H:i:s'),
                'verified_by' => $verifiedBy
            ],
            'id = :id',
            ['id' => $documentId]
        );
    }
    
    /**
     * Get documents by type
     * 
     * @param int $applicationId Application ID
     * @param string $documentType Document type
     * @return array Documents
     */
    public function getByType($applicationId, $documentType) {
        return $this->fetchAll(
            "SELECT * FROM {$this->table} WHERE application_id = :application_id AND document_type = :document_type ORDER BY uploaded_at DESC",
            [
                'application_id' => $applicationId,
                'document_type' => $documentType
            ]
        );
    }
    
    /**
     * Count documents by application
     * 
     * @param int $applicationId Application ID
     * @return int Number of documents
     */
    public function countByApplication($applicationId) {
        return $this->fetchColumn(
            "SELECT COUNT(*) FROM {$this->table} WHERE application_id = :application_id",
            ['application_id' => $applicationId]
        );
    }
    
    /**
     * Check if document type exists for application
     * 
     * @param int $applicationId Application ID
     * @param string $documentType Document type
     * @return bool True if exists
     */
    public function documentTypeExists($applicationId, $documentType) {
        $count = $this->fetchColumn(
            "SELECT COUNT(*) FROM {$this->table} WHERE application_id = :application_id AND document_type = :document_type",
            [
                'application_id' => $applicationId,
                'document_type' => $documentType
            ]
        );
        
        return $count > 0;
    }
    
    /**
     * Get latest document by type
     * 
     * @param int $applicationId Application ID
     * @param string $documentType Document type
     * @return array|false Latest document or false
     */
    public function getLatestByType($applicationId, $documentType) {
        return $this->fetchOne(
            "SELECT * FROM {$this->table} 
             WHERE application_id = :application_id AND document_type = :document_type 
             ORDER BY uploaded_at DESC 
             LIMIT 1",
            [
                'application_id' => $applicationId,
                'document_type' => $documentType
            ]
        );
    }
    
    /**
     * Get all verified documents for application
     * 
     * @param int $applicationId Application ID
     * @return array Verified documents
     */
    public function getVerifiedDocuments($applicationId) {
        return $this->fetchAll(
            "SELECT * FROM {$this->table} 
             WHERE application_id = :application_id AND is_verified = 1 
             ORDER BY document_type, uploaded_at DESC",
            ['application_id' => $applicationId]
        );
    }
    
    /**
     * Get unverified documents for application
     * 
     * @param int $applicationId Application ID
     * @return array Unverified documents
     */
    public function getUnverifiedDocuments($applicationId) {
        return $this->fetchAll(
            "SELECT * FROM {$this->table} 
             WHERE application_id = :application_id AND (is_verified IS NULL OR is_verified = 0) 
             ORDER BY document_type, uploaded_at DESC",
            ['application_id' => $applicationId]
        );
    }
    
    /**
     * Delete all documents for an application
     * 
     * @param int $applicationId Application ID
     * @return int Number of deleted records
     */
    public function deleteAllForApplication($applicationId) {
        // Get all documents to delete files
        $documents = $this->getByApplicationId($applicationId);
        
        foreach ($documents as $document) {
            $filePath = PUBLIC_PATH . $document['file_path'];
            if (file_exists($filePath)) {
                unlink($filePath);
            }
        }
        
        // Delete directory if empty
        $uploadDir = PUBLIC_PATH . '/assets/uploads/applications/' . $applicationId;
        if (is_dir($uploadDir)) {
            rmdir($uploadDir);
        }
        
        // Delete database records
        return $this->delete('application_id = :application_id', ['application_id' => $applicationId]);
    }
    
    /**
     * Get document statistics
     * 
     * @param int|null $applicationId Optional application ID filter
     * @return array Statistics
     */
    public function getStats($applicationId = null) {
        $sql = "SELECT 
                COUNT(*) as total_documents,
                SUM(CASE WHEN is_verified = 1 THEN 1 ELSE 0 END) as verified_count,
                SUM(CASE WHEN is_verified IS NULL OR is_verified = 0 THEN 1 ELSE 0 END) as unverified_count,
                COUNT(DISTINCT document_type) as document_types,
                COUNT(DISTINCT application_id) as applications_with_docs
                FROM {$this->table}";
        
        $params = [];
        
        if ($applicationId) {
            $sql .= " WHERE application_id = :application_id";
            $params['application_id'] = $applicationId;
        }
        
        return $this->fetchOne($sql, $params);
    }
}