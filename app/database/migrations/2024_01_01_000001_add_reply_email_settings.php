<?php
/**
 * Migration: Add Reply Email Settings
 * 
 * This migration adds reply-to email configuration to site_settings table
 */

class AddReplyEmailSettingsMigration {
    
    /**
     * Run the migrations
     */
    public function up($connection) {
        echo "Adding reply email settings...\n";
        
        // Check if site_settings table exists
        $stmt = $connection->query("SHOW TABLES LIKE 'site_settings'");
        if ($stmt->rowCount() === 0) {
            // Create site_settings table if it doesn't exist
            $sql = "CREATE TABLE IF NOT EXISTS site_settings (
                id INT(10) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                setting_key VARCHAR(100) NOT NULL UNIQUE,
                setting_value TEXT,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                INDEX idx_setting_key (setting_key)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";
            
            $connection->exec($sql);
            echo "✓ Created site_settings table\n";
        }
        
        // Default reply email settings
        $settings = [
            [
                'key' => 'reply_to_email',
                'value' => 'noreply@fctcns.edu.ng',
                'description' => 'Default reply-to email address'
            ],
            [
                'key' => 'support_email',
                'value' => 'support@fctcns.edu.ng',
                'description' => 'Support department email'
            ],
            [
                'key' => 'billing_email',
                'value' => 'billing@fctcns.edu.ng',
                'description' => 'Billing department email'
            ],
            [
                'key' => 'admissions_email',
                'value' => 'admissions@fctcns.edu.ng',
                'description' => 'Admissions department email'
            ],
            [
                'key' => 'contact_email',
                'value' => 'info@fctcns.edu.ng',
                'description' => 'General contact email'
            ],
            [
                'key' => 'contact_phone',
                'value' => '+234 XXX XXX XXXX',
                'description' => 'Primary contact phone'
            ],
            [
                'key' => 'contact_address',
                'value' => 'FCT College of Nursing Sciences, Abuja, Nigeria',
                'description' => 'Physical address'
            ],
            [
                'key' => 'contact_hours',
                'value' => 'Monday - Friday: 8:00 AM - 5:00 PM',
                'description' => 'Working hours'
            ],
            [
                'key' => 'contact_emergency',
                'value' => '+234 XXX XXX XXXX',
                'description' => 'Emergency contact'
            ]
        ];
        
        // Insert settings
        $insertSql = "INSERT INTO site_settings (setting_key, setting_value) 
                      VALUES (:key, :value) 
                      ON DUPLICATE KEY UPDATE setting_value = VALUES(setting_value)";
        
        $stmt = $connection->prepare($insertSql);
        
        foreach ($settings as $setting) {
            try {
                $stmt->execute([
                    ':key' => $setting['key'],
                    ':value' => $setting['value']
                ]);
                echo "  ✓ Added/updated: {$setting['key']} = {$setting['value']}\n";
            } catch (PDOException $e) {
                echo "  ✗ Failed to add {$setting['key']}: " . $e->getMessage() . "\n";
            }
        }
        
        echo "✓ Reply email settings migration completed\n";
    }
    
    /**
     * Reverse the migrations
     */
    public function down($connection) {
        echo "Removing reply email settings...\n";
        
        $keys = [
            'reply_to_email',
            'support_email',
            'billing_email',
            'admissions_email'
        ];
        
        $deleteSql = "DELETE FROM site_settings WHERE setting_key = :key";
        $stmt = $connection->prepare($deleteSql);
        
        foreach ($keys as $key) {
            $stmt->execute([':key' => $key]);
            echo "  ✓ Removed: $key\n";
        }
        
        echo "✓ Reply email settings rollback completed\n";
    }
}