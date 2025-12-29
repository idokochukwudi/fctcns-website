<?php
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
            
            // Create PDO connection
            $dsn = "mysql:host={$host};port={$port};dbname={$database};charset=utf8mb4";
            $this->connection = new PDO($dsn, $username, $password);
            
            // Set PDO attributes for better error handling and security
            $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->connection->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
            $this->connection->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
            
            // Prevent SQL injection by disabling emulated prepares
            $this->connection->setAttribute(PDO::ATTR_STRINGIFY_FETCHES, false);
            
        } catch (PDOException $e) {
            // Log error and show user-friendly message
            error_log("Database Connection Error: " . $e->getMessage());
            die("<h1>Database Connection Failed</h1>
                <p>We're experiencing technical difficulties. Please try again later.</p>
                <!-- " . htmlspecialchars($e->getMessage()) . " -->");
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
     * Prepare a statement with error handling
     */
    public function prepare($sql) {
        try {
            return $this->connection->prepare($sql);
        } catch (PDOException $e) {
            error_log("Prepare Statement Error: " . $e->getMessage());
            throw $e;
        }
    }
    
    /**
     * Execute a query and return result
     */
    public function query($sql, $params = []) {
        try {
            $stmt = $this->prepare($sql);
            $stmt->execute($params);
            return $stmt;
        } catch (PDOException $e) {
            error_log("Query Execution Error: " . $e->getMessage());
            throw $e;
        }
    }
    
    /**
     * Begin a transaction
     */
    public function beginTransaction() {
        return $this->connection->beginTransaction();
    }
    
    /**
     * Commit a transaction
     */
    public function commit() {
        return $this->connection->commit();
    }
    
    /**
     * Rollback a transaction
     */
    public function rollback() {
        return $this->connection->rollBack();
    }
    
    /**
     * Get the last inserted ID
     */
    public function lastInsertId() {
        return $this->connection->lastInsertId();
    }
    
    /**
     * Close the connection (not usually needed with PDO)
     */
    public function close() {
        $this->connection = null;
        self::$instance = null;
    }
}

// Helper function to get database instance
function db() {
    return Database::getInstance()->getConnection();
}

// Test connection on include if in development
if (($_ENV['APP_ENV'] ?? 'development') === 'development') {
    try {
        $test = Database::getInstance();
        // Uncomment to verify connection on every page load
        // error_log("Database connection successful to " . ($_ENV['DB_DATABASE'] ?? 'fctcns_main'));
    } catch (Exception $e) {
        error_log("Database test failed: " . $e->getMessage());
    }
}
?>