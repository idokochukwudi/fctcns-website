<?php
/**
 * Simple Migration System for Custom MVC
 * 
 * @package FCT_CNS
 */

class Migration {
    private $db;
    private $connection;
    
    public function __construct() {
        require_once APP_PATH . '/config/database.php';
        $database = Database::getInstance();
        $this->connection = $database->getConnection();
        $this->db = $database;
    }
    
    /**
     * Create migrations table if it doesn't exist
     */
    public function createMigrationsTable() {
        $sql = "CREATE TABLE IF NOT EXISTS migrations (
            id INT(10) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            migration VARCHAR(255) NOT NULL,
            batch INT(11) NOT NULL,
            executed_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";
        
        $this->connection->exec($sql);
        echo "✓ Migrations table created or already exists\n";
    }
    
    /**
     * Get all executed migrations
     */
    public function getExecutedMigrations() {
        $sql = "SELECT migration FROM migrations ORDER BY id ASC";
        $stmt = $this->connection->query($sql);
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }
    
    /**
     * Add migration record
     */
    public function addMigration($migration, $batch) {
        $sql = "INSERT INTO migrations (migration, batch) VALUES (:migration, :batch)";
        $stmt = $this->connection->prepare($sql);
        $stmt->execute([
            ':migration' => $migration,
            ':batch' => $batch
        ]);
    }
    
    /**
     * Remove migration record
     */
    public function removeMigration($migration) {
        $sql = "DELETE FROM migrations WHERE migration = :migration";
        $stmt = $this->connection->prepare($sql);
        $stmt->execute([':migration' => $migration]);
    }
    
    /**
     * Get current batch number
     */
    public function getCurrentBatch() {
        $sql = "SELECT MAX(batch) as batch FROM migrations";
        $stmt = $this->connection->query($sql);
        $result = $stmt->fetch();
        return $result['batch'] ?? 0;
    }
    
    /**
     * Run all pending migrations
     */
    public function migrate() {
        $this->createMigrationsTable();
        
        $executed = $this->getExecutedMigrations();
        $batch = $this->getCurrentBatch() + 1;
        $migrationFiles = glob(APP_PATH . '/database/migrations/*.php');
        
        $count = 0;
        foreach ($migrationFiles as $file) {
            $migrationName = basename($file, '.php');
            
            if (!in_array($migrationName, $executed)) {
                echo "Running migration: $migrationName\n";
                
                require_once $file;
                $className = $this->getMigrationClassName($migrationName);
                
                if (class_exists($className)) {
                    $migration = new $className();
                    $migration->up($this->connection);
                    $this->addMigration($migrationName, $batch);
                    echo "✓ Completed: $migrationName\n";
                    $count++;
                }
            }
        }
        
        if ($count === 0) {
            echo "No pending migrations.\n";
        } else {
            echo "✓ Migrated $count migration(s) successfully.\n";
        }
    }
    
    /**
     * Rollback last batch of migrations
     */
    public function rollback() {
        $this->createMigrationsTable();
        
        $batch = $this->getCurrentBatch();
        if ($batch === 0) {
            echo "No migrations to rollback.\n";
            return;
        }
        
        $sql = "SELECT migration FROM migrations WHERE batch = :batch ORDER BY id DESC";
        $stmt = $this->connection->prepare($sql);
        $stmt->execute([':batch' => $batch]);
        $migrations = $stmt->fetchAll(PDO::FETCH_COLUMN);
        
        $count = 0;
        foreach ($migrations as $migrationName) {
            echo "Rolling back: $migrationName\n";
            
            $file = APP_PATH . '/database/migrations/' . $migrationName . '.php';
            if (file_exists($file)) {
                require_once $file;
                $className = $this->getMigrationClassName($migrationName);
                
                if (class_exists($className)) {
                    $migration = new $className();
                    $migration->down($this->connection);
                    $this->removeMigration($migrationName);
                    echo "✓ Rolled back: $migrationName\n";
                    $count++;
                }
            }
        }
        
        echo "✓ Rolled back $count migration(s) successfully.\n";
    }
    
    /**
     * Get migration class name from filename
     */
    private function getMigrationClassName($filename) {
        // Remove timestamp prefix (e.g., 2024_01_15_000000_)
        $name = preg_replace('/^\d{4}_\d{2}_\d{2}_\d{6}_/', '', $filename);
        // Convert to StudlyCase
        $name = str_replace('_', '', ucwords($name, '_'));
        return $name . 'Migration';
    }
    
    /**
     * Reset and re-run all migrations
     */
    public function fresh() {
        $this->rollback();
        $this->migrate();
    }
    
    /**
     * Create a new migration file
     */
    public function create($name) {
        $timestamp = date('Y_m_d_His');
        $filename = $timestamp . '_' . $name . '.php';
        $path = APP_PATH . '/database/migrations/' . $filename;
        
        // Create migrations directory if it doesn't exist
        if (!is_dir(APP_PATH . '/database/migrations')) {
            mkdir(APP_PATH . '/database/migrations', 0755, true);
        }
        
        $className = $this->getMigrationClassName($timestamp . '_' . $name);
        
        $template = $this->getMigrationTemplate($className);
        file_put_contents($path, $template);
        
        echo "✓ Created migration: $filename\n";
    }
    
    /**
     * Migration template
     */
    private function getMigrationTemplate($className) {
        return "<?php\n\n/**\n * Migration: $className\n */\n\nclass $className {\n    \n    /**\n     * Run the migrations\n     */\n    public function up(\$connection) {\n        // Add your migration code here\n    }\n    \n    /**\n     * Reverse the migrations\n     */\n    public function down(\$connection) {\n        // Add your rollback code here\n    }\n}\n";
    }
}