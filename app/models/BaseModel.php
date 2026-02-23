<?php
/**
 * Base Model Class
 * 
 * Provides common database functionality for all models
 * 
 * @package FCT_CNS
 */

class BaseModel {
    
    protected $db;
    protected $table;
    protected $primaryKey = 'id';
    
    /**
     * Constructor - Get database connection
     */
    public function __construct() {
        // Get database instance
        require_once __DIR__ . '/../config/database.php';
        $database = Database::getInstance();
        $this->db = $database->getConnection();
    }
    
    /**
     * Find record by ID
     * 
     * @param int $id Record ID
     * @return array|false Record data or false if not found
     */
    public function find($id) {
        $sql = "SELECT * FROM {$this->table} WHERE {$this->primaryKey} = :id LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    /**
     * Get all records
     * 
     * @param string $orderBy Order by clause
     * @return array All records
     */
    public function all($orderBy = null) {
        $sql = "SELECT * FROM {$this->table}";
        if ($orderBy) {
            $sql .= " ORDER BY " . $orderBy;
        }
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    /**
     * Insert new record
     * 
     * @param array $data Data to insert
     * @return int|false Inserted ID or false on failure
     */
    public function insert($data) {
        $columns = implode(', ', array_keys($data));
        $placeholders = ':' . implode(', :', array_keys($data));
        
        $sql = "INSERT INTO {$this->table} ({$columns}) VALUES ({$placeholders})";
        $stmt = $this->db->prepare($sql);
        
        if ($stmt->execute($data)) {
            return $this->db->lastInsertId();
        }
        
        return false;
    }
    
    /**
     * Update records
     * 
     * @param array $data Data to update
     * @param string $where Where clause
     * @param array $params Parameters for where clause
     * @return int|false Number of affected rows or false on failure
     */
    public function update($data, $where, $params = []) {
        $setParts = [];
        foreach (array_keys($data) as $column) {
            $setParts[] = "{$column} = :{$column}";
        }
        $setClause = implode(', ', $setParts);
        
        $sql = "UPDATE {$this->table} SET {$setClause} WHERE {$where}";
        $stmt = $this->db->prepare($sql);
        
        // Merge data and where params
        $allParams = array_merge($data, $params);
        
        if ($stmt->execute($allParams)) {
            return $stmt->rowCount();
        }
        
        return false;
    }
    
    /**
     * Delete records
     * 
     * @param string $where Where clause
     * @param array $params Parameters for where clause
     * @return int|false Number of affected rows or false on failure
     */
    public function delete($where, $params = []) {
        $sql = "DELETE FROM {$this->table} WHERE {$where}";
        $stmt = $this->db->prepare($sql);
        
        if ($stmt->execute($params)) {
            return $stmt->rowCount();
        }
        
        return false;
    }
    
    /**
     * Count records
     * 
     * @param string $where Where clause (optional)
     * @param array $params Parameters for where clause
     * @return int Number of records
     */
    public function count($where = '1', $params = []) {
        $sql = "SELECT COUNT(*) as total FROM {$this->table} WHERE {$where}";
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['total'] ?? 0;
    }
    
    /**
     * Check if record exists
     * 
     * @param string $where Where clause
     * @param array $params Parameters for where clause
     * @return bool True if exists
     */
    public function exists($where, $params = []) {
        return $this->count($where, $params) > 0;
    }
    
    /**
     * Get records with pagination
     * 
     * @param int $page Page number
     * @param int $perPage Records per page
     * @param string $where Where clause (optional)
     * @param array $params Parameters for where clause
     * @param string $orderBy Order by clause (optional)
     * @return array Records for current page
     */
    public function paginate($page = 1, $perPage = 20, $where = '1', $params = [], $orderBy = null) {
        $offset = ($page - 1) * $perPage;
        
        $sql = "SELECT * FROM {$this->table} WHERE {$where}";
        if ($orderBy) {
            $sql .= " ORDER BY " . $orderBy;
        }
        $sql .= " LIMIT {$offset}, {$perPage}";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    /**
     * Get first record matching condition
     * 
     * @param string $where Where clause
     * @param array $params Parameters for where clause
     * @return array|false First record or false
     */
    public function firstWhere($where, $params = []) {
        $sql = "SELECT * FROM {$this->table} WHERE {$where} LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    /**
     * Get all records matching condition
     * 
     * @param string $where Where clause
     * @param array $params Parameters for where clause
     * @param string $orderBy Order by clause (optional)
     * @return array Matching records
     */
    public function where($where, $params = [], $orderBy = null) {
        $sql = "SELECT * FROM {$this->table} WHERE {$where}";
        if ($orderBy) {
            $sql .= " ORDER BY " . $orderBy;
        }
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    /**
     * Fetch a single row
     * 
     * @param string $sql SQL query
     * @param array $params Parameters
     * @return array|false Single row or false
     */
    public function fetchOne($sql, $params = []) {
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    /**
     * Fetch all rows
     * 
     * @param string $sql SQL query
     * @param array $params Parameters
     * @return array All rows
     */
    public function fetchAll($sql, $params = []) {
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    /**
     * Fetch a single column value
     * 
     * @param string $sql SQL query
     * @param array $params Parameters
     * @param int $column Column index (default 0)
     * @return mixed Column value
     */
    public function fetchColumn($sql, $params = [], $column = 0) {
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchColumn($column);
    }
    
    /**
     * Begin transaction
     */
    public function beginTransaction() {
        if ($this->db->inTransaction()) {
            error_log("BaseModel::beginTransaction - Already in transaction, skipping");
            return false;
        }
        return $this->db->beginTransaction();
    }
    
    /**
     * Commit transaction
     */
    public function commit() {
        if (!$this->db->inTransaction()) {
            error_log("BaseModel::commit - No active transaction, skipping");
            return false;
        }
        return $this->db->commit();
    }
    
    /**
     * Rollback transaction
     */
    public function rollback() {
        if (!$this->db->inTransaction()) {
            error_log("BaseModel::rollback - No active transaction, skipping");
            return false;
        }
        return $this->db->rollBack();
    }
    
    /**
     * Get last inserted ID
     * 
     * @return string Last insert ID
     */
    public function lastInsertId() {
        return $this->db->lastInsertId();
    }
    
    /**
     * Execute raw SQL query
     * 
     * @param string $sql SQL query
     * @param array $params Parameters
     * @return PDOStatement
     */
    public function query($sql, $params = []) {
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt;
    }
    
    /**
     * Get database connection
     * 
     * @return PDO Database connection
     */
    public function getConnection() {
        return $this->db;
    }
    
    /**
     * Quote identifier for SQL
     * 
     * @param string $identifier Table/column name
     * @return string Quoted identifier
     */
    public function quoteIdentifier($identifier) {
        return "`" . str_replace("`", "``", $identifier) . "`";
    }
}