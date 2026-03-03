<?php
// ============================================================================
// LOAD ENVIRONMENT VARIABLES FROM .ENV FILE
// ============================================================================
require_once '/home2/fctcnsed/fctcns-app/load_env.php';
/**
 * Database Connection Configuration
 * 
 * @package FCT_CNS
 */

class Database {
    private static $instance = null;
    private $connection;
    
    /**
     * Private constructor to prevent direct instantiation
     */
    private function __construct() {
        try {
            // Get database credentials from environment
            $host = $_ENV['DB_HOST'] ?? 'localhost';
            $port = $_ENV['DB_PORT'] ?? 3306;
            $database = $_ENV['DB_DATABASE'] ?? 'fctcns_main';
            $username = $_ENV['DB_USERNAME'] ?? 'root';
            $password = $_ENV['DB_PASSWORD'] ?? '';
            
            // Create PDO connection with ALL necessary parameters
            $dsn = "mysql:host={$host};port={$port};dbname={$database};charset=utf8mb4";
            
            // Create PDO connection with FIXED configuration options
            $this->connection = new PDO($dsn, $username, $password, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => true, // FIXED: Added this line to prevent MySQL parameter binding errors
                PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4",
                PDO::ATTR_STRINGIFY_FETCHES => false,
                // ADD THIS LINE to prevent connection buildup:
                PDO::ATTR_PERSISTENT => false  // Set to false to prevent persistent connections
            ]);
            
            // Set additional PDO attributes for better error handling and security
            // (These are already set in the constructor, but keeping for clarity)
            $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->connection->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
            $this->connection->setAttribute(PDO::ATTR_EMULATE_PREPARES, true); // Keep consistent
            
        } catch (PDOException $e) {
            // Log error and show user-friendly message
            error_log("Database Connection Error: " . $e->getMessage());
            error_log("Database Connection Details: host={$host}, port={$port}, dbname={$database}");
            
            // Development mode: show detailed error
            if (($_ENV['APP_ENV'] ?? 'development') === 'development') {
                die("<h1>Database Connection Failed</h1>
                    <p><strong>Error:</strong> " . htmlspecialchars($e->getMessage()) . "</p>
                    <p><strong>Details:</strong> Trying to connect to {$host}:{$port}/{$database}</p>
                    <p>Please check your database configuration in the .env file.</p>");
            } else {
                // Production mode: generic error
                die("<h1>Database Connection Failed</h1>
                    <p>We're experiencing technical difficulties. Please try again later.</p>
                    <p>If the problem persists, please contact the system administrator.</p>
                    <!-- " . htmlspecialchars($e->getMessage()) . " -->");
            }
        }
    }
    
    /**
     * Get singleton instance of Database class
     */
    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new Database();
        }
        return self::$instance;
    }
    
    /**
     * Get the PDO connection
     */
    public function getConnection() {
        return $this->connection;
    }
    
    /**
     * Test and fix database connection
     */
    public static function testAndFixConnection()
    {
        try {
            $db = self::getInstance();
            $conn = $db->getConnection();
            
            // Test the connection
            $stmt = $conn->query("SELECT 1");
            if ($stmt) {
                return true;
            }
        } catch (PDOException $e) {
            error_log("Database test failed: " . $e->getMessage());
            
            // Close the broken connection
            if (isset($db)) {
                $db->close();
            }
            
            // Clear the instance to force a new connection
            self::$instance = null;
            
            // Wait a bit before retrying
            sleep(2);
            
            return false;
        }
    }
    
    /**
     * Prepare a statement with error handling
     */
    public function prepare($sql) {
        try {
            return $this->connection->prepare($sql);
        } catch (PDOException $e) {
            error_log("Prepare Statement Error: " . $e->getMessage());
            error_log("SQL: " . $sql);
            throw $e;
        }
    }
    
    /**
     * Execute a query and return result
     */
    public function query($sql, $params = []) {
        try {
            $stmt = $this->prepare($sql);
            
            // Log query for debugging in development
            if (($_ENV['APP_ENV'] ?? 'development') === 'development') {
                error_log("SQL Query: " . $sql);
                if (!empty($params)) {
                    error_log("SQL Params: " . print_r($params, true));
                }
            }
            
            $stmt->execute($params);
            return $stmt;
        } catch (PDOException $e) {
            error_log("Query Execution Error: " . $e->getMessage());
            error_log("SQL: " . $sql);
            error_log("Params: " . print_r($params, true));
            throw $e;
        }
    }
    
    /**
     * Execute a query and return all results
     */
    public function fetchAll($sql, $params = []) {
        $stmt = $this->query($sql, $params);
        return $stmt->fetchAll();
    }
    
    /**
     * Execute a query and return single result
     */
    public function fetchOne($sql, $params = []) {
        $stmt = $this->query($sql, $params);
        return $stmt->fetch();
    }
    
    /**
     * Execute a query and return single column value
     */
    public function fetchColumn($sql, $params = [], $column = 0) {
        $stmt = $this->query($sql, $params);
        return $stmt->fetchColumn($column);
    }
    
    /**
     * Check if a table exists
     */
    public function tableExists($tableName) {
        try {
            $sql = "SHOW TABLES LIKE :table";
            $stmt = $this->query($sql, ['table' => $tableName]);
            return $stmt->rowCount() > 0;
        } catch (PDOException $e) {
            error_log("Table exists check failed: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Get table structure/columns
     */
    public function getTableColumns($tableName) {
        try {
            $sql = "DESCRIBE " . $this->quoteIdentifier($tableName);
            $stmt = $this->query($sql);
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            error_log("Get table columns failed: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Safely quote identifier (table/column names)
     */
    public function quoteIdentifier($identifier) {
        return "`" . str_replace("`", "``", $identifier) . "`";
    }
    
    /**
     * Begin a transaction
     */
    public function beginTransaction() {
        try {
            return $this->connection->beginTransaction();
        } catch (PDOException $e) {
            error_log("Begin transaction failed: " . $e->getMessage());
            throw $e;
        }
    }
    
    /**
     * Commit a transaction
     */
    public function commit() {
        try {
            return $this->connection->commit();
        } catch (PDOException $e) {
            error_log("Commit transaction failed: " . $e->getMessage());
            throw $e;
        }
    }
    
    /**
     * Rollback a transaction
     */
    public function rollback() {
        try {
            return $this->connection->rollBack();
        } catch (PDOException $e) {
            error_log("Rollback transaction failed: " . $e->getMessage());
            throw $e;
        }
    }
    
    /**
     * Check if in transaction
     */
    public function inTransaction() {
        return $this->connection->inTransaction();
    }
    
    /**
     * Get the last inserted ID
     */
    public function lastInsertId($name = null) {
        try {
            return $this->connection->lastInsertId($name);
        } catch (PDOException $e) {
            error_log("Last insert ID failed: " . $e->getMessage());
            throw $e;
        }
    }
    
    /**
     * Insert a record and return the ID
     */
    public function insert($table, $data) {
        try {
            $columns = array_keys($data);
            $placeholders = array_map(function($col) {
                return ':' . $col;
            }, $columns);
            
            $sql = "INSERT INTO " . $this->quoteIdentifier($table) . 
                   " (" . implode(', ', $columns) . ") VALUES (" . 
                   implode(', ', $placeholders) . ")";
            
            $this->query($sql, $data);
            return $this->lastInsertId();
        } catch (PDOException $e) {
            error_log("Insert failed for table {$table}: " . $e->getMessage());
            throw $e;
        }
    }
    
    /**
     * Update records
     */
    public function update($table, $data, $where, $whereParams = []) {
        try {
            $setParts = [];
            foreach (array_keys($data) as $column) {
                $setParts[] = $this->quoteIdentifier($column) . " = :" . $column;
            }
            
            $sql = "UPDATE " . $this->quoteIdentifier($table) . 
                   " SET " . implode(', ', $setParts) . 
                   " WHERE " . $where;
            
            // Merge data and where parameters
            $params = array_merge($data, $whereParams);
            
            $stmt = $this->query($sql, $params);
            return $stmt->rowCount();
        } catch (PDOException $e) {
            error_log("Update failed for table {$table}: " . $e->getMessage());
            throw $e;
        }
    }
    
    /**
     * Delete records
     */
    public function delete($table, $where, $params = []) {
        try {
            $sql = "DELETE FROM " . $this->quoteIdentifier($table) . 
                   " WHERE " . $where;
            
            $stmt = $this->query($sql, $params);
            return $stmt->rowCount();
        } catch (PDOException $e) {
            error_log("Delete failed for table {$table}: " . $e->getMessage());
            throw $e;
        }
    }
    
    /**
     * Count records
     */
    public function count($table, $where = '1', $params = []) {
        try {
            $sql = "SELECT COUNT(*) FROM " . $this->quoteIdentifier($table) . 
                   " WHERE " . $where;
            
            return $this->fetchColumn($sql, $params);
        } catch (PDOException $e) {
            error_log("Count failed for table {$table}: " . $e->getMessage());
            throw $e;
        }
    }
    
    /**
     * Backup database to file
     */
    public function backup($filePath) {
        try {
            // This is a simple backup method. For production, use mysqldump command
            $tables = $this->fetchAll("SHOW TABLES");
            
            $backupSQL = "-- Database Backup\n";
            $backupSQL .= "-- Generated: " . date('Y-m-d H:i:s') . "\n";
            $backupSQL .= "-- Database: " . ($_ENV['DB_DATABASE'] ?? 'fctcns_main') . "\n\n";
            
            foreach ($tables as $tableRow) {
                $tableName = array_values($tableRow)[0];
                
                // Drop table if exists
                $backupSQL .= "DROP TABLE IF EXISTS `{$tableName}`;\n\n";
                
                // Create table
                $createTable = $this->fetchOne("SHOW CREATE TABLE `{$tableName}`");
                $backupSQL .= $createTable['Create Table'] . ";\n\n";
                
                // Insert data
                $rows = $this->fetchAll("SELECT * FROM `{$tableName}`");
                if (!empty($rows)) {
                    foreach ($rows as $row) {
                        $columns = array_keys($row);
                        $values = array_map(function($value) {
                            if ($value === null) return 'NULL';
                            return "'" . addslashes($value) . "'";
                        }, array_values($row));
                        
                        $backupSQL .= "INSERT INTO `{$tableName}` (`" . 
                                     implode('`, `', $columns) . 
                                     "`) VALUES (" . implode(', ', $values) . ");\n";
                    }
                    $backupSQL .= "\n";
                }
            }
            
            // Write to file
            file_put_contents($filePath, $backupSQL);
            
            return true;
        } catch (Exception $e) {
            error_log("Database backup failed: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Close the connection (not usually needed with PDO)
     */
    public function close() {
        if ($this->connection !== null) {
            $this->connection = null;
        }
        self::$instance = null;
    }
    
    /**
     * Destructor - ensure connection is closed
     */
    public function __destruct() {
        $this->close();
    }
}

// Helper function to get database instance
function db() {
    return Database::getInstance()->getConnection();
}

// Helper function to get database instance for operations
function getDb() {
    return Database::getInstance();
}

// Test connection on include if in development
if (($_ENV['APP_ENV'] ?? 'development') === 'development') {
    try {
        $db = Database::getInstance();
        $connection = $db->getConnection();
        
        // Test query to verify connection
        $testResult = $connection->query("SELECT 1 as test")->fetch();
        
        if ($testResult && $testResult['test'] == 1) {
            error_log("✓ Database connection successful to " . ($_ENV['DB_DATABASE'] ?? 'fctcns_main'));
        } else {
            error_log("✗ Database test query failed");
        }
    } catch (Exception $e) {
        error_log("✗ Database test failed: " . $e->getMessage());
        
        // Show error in development mode
        if (isset($_SERVER['HTTP_HOST'])) {
            echo "<div style='background: #ffcccc; padding: 10px; margin: 10px; border: 1px solid red;'>";
            echo "<strong>Database Connection Error:</strong> " . htmlspecialchars($e->getMessage());
            echo "</div>";
        }
    }
}