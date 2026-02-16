<?php
/**
 * Settings Model
 * 
 * Handles application settings data operations
 * 
 * @package FCT_CNS
 * @subpackage Application
 */

require_once MODELS_PATH . '/BaseModel.php';

class SettingsModel extends BaseModel {
    
    protected $table = 'application_settings';
    protected $primaryKey = 'id';
    
    /**
     * Constructor
     */
    public function __construct() {
        parent::__construct();
    }
    
    /**
     * Get all settings as key-value pairs
     */
    public function getAllSettings() {
        $settings = $this->fetchAll(
            "SELECT setting_key, setting_value, data_type, `group`, description 
             FROM {$this->table} 
             ORDER BY `group`, setting_key"
        );
        
        $result = [
            'key_value' => [],
            'grouped' => []
        ];
        
        foreach ($settings as $setting) {
            // Key-value store
            $result['key_value'][$setting['setting_key']] = $this->castValue(
                $setting['setting_value'], 
                $setting['data_type']
            );
            
            // Grouped store
            if (!isset($result['grouped'][$setting['group']])) {
                $result['grouped'][$setting['group']] = [];
            }
            
            $result['grouped'][$setting['group']][$setting['setting_key']] = [
                'value' => $this->castValue($setting['setting_value'], $setting['data_type']),
                'data_type' => $setting['data_type'],
                'description' => $setting['description']
            ];
        }
        
        return $result;
    }
    
    /**
     * Cast value based on data type
     */
    private function castValue($value, $dataType) {
        if ($value === null) {
            return null;
        }
        
        switch ($dataType) {
            case 'number':
            case 'integer':
            case 'int':
                return intval($value);
            case 'float':
            case 'decimal':
                return floatval($value);
            case 'boolean':
            case 'bool':
                return filter_var($value, FILTER_VALIDATE_BOOLEAN);
            case 'date':
            case 'datetime':
                return $value; // Keep as string
            case 'json':
                return json_decode($value, true) ?: $value;
            case 'textarea':
            case 'text':
            default:
                return $value;
        }
    }
    
    /**
     * Get a single setting value
     */
    public function get($key, $default = null) {
        $setting = $this->fetchOne(
            "SELECT setting_value, data_type FROM {$this->table} WHERE setting_key = :key",
            ['key' => $key]
        );
        
        if ($setting) {
            return $this->castValue($setting['setting_value'], $setting['data_type']);
        }
        
        return $default;
    }
    
    /**
     * Set a setting value
     */
    public function set($key, $value, $dataType = 'text', $group = 'general', $description = null) {
        $existing = $this->fetchOne(
            "SELECT id FROM {$this->table} WHERE setting_key = :key",
            ['key' => $key]
        );
        
        $data = [
            'setting_value' => is_array($value) ? json_encode($value) : $value,
            'updated_at' => date('Y-m-d H:i:s')
        ];
        
        if ($description !== null) {
            $data['description'] = $description;
        }
        
        if ($existing) {
            return $this->update($data, 'setting_key = :key', ['key' => $key]);
        } else {
            $data['setting_key'] = $key;
            $data['data_type'] = $dataType;
            $data['group'] = $group;
            $data['created_at'] = date('Y-m-d H:i:s');
            if ($description !== null) {
                $data['description'] = $description;
            }
            
            return $this->insert($data);
        }
    }
    
    /**
     * Update multiple settings at once
     */
    public function updateSettings($settings) {
        $updated = 0;
        
        foreach ($settings as $key => $value) {
            if ($this->set($key, $value)) {
                $updated++;
            }
        }
        
        return $updated;
    }
    
    /**
     * Get settings by group
     */
    public function getByGroup($group) {
        $settings = $this->fetchAll(
            "SELECT setting_key, setting_value, data_type, description 
             FROM {$this->table} 
             WHERE `group` = :group 
             ORDER BY setting_key",
            ['group' => $group]
        );
        
        $result = [];
        foreach ($settings as $setting) {
            $result[$setting['setting_key']] = [
                'value' => $this->castValue($setting['setting_value'], $setting['data_type']),
                'description' => $setting['description']
            ];
        }
        
        return $result;
    }
    
    /**
     * Check if portal is open
     */
    public function isPortalOpen() {
        $status = $this->get('portal_status', 'closed');
        
        if ($status !== 'open') {
            return false;
        }
        
        $startDate = $this->get('application_start_date');
        $endDate = $this->get('application_end_date');
        $today = date('Y-m-d');
        
        if ($startDate && $today < $startDate) {
            return false;
        }
        
        if ($endDate && $today > $endDate) {
            return false;
        }
        
        return true;
    }
    
    /**
     * Get portal message
     */
    public function getPortalMessage() {
        $status = $this->get('portal_status', 'closed');
        $message = $this->get('portal_message', 'The application portal is currently closed.');
        
        if ($status === 'open') {
            $startDate = $this->get('application_start_date');
            $endDate = $this->get('application_end_date');
            
            if ($startDate && $endDate) {
                $message = "Application portal is open from " . 
                          date('jS F Y', strtotime($startDate)) . 
                          " to " . date('jS F Y', strtotime($endDate));
            }
        }
        
        return $message;
    }
    
    /**
     * Get application fee
     */
    public function getApplicationFee() {
        return $this->get('application_fee', 2200);
    }
    
    /**
     * Get currency symbol
     */
    public function getCurrency() {
        return $this->get('application_currency', '₦');
    }
    
    /**
     * Get formatted application fee
     */
    public function getFormattedFee() {
        $fee = $this->getApplicationFee();
        $currency = $this->getCurrency();
        
        return $currency . number_format($fee);
    }
    
    /**
     * Get support phone numbers
     */
    public function getSupportPhones() {
        $phones = [];
        
        $phone1 = $this->get('support_phone_1');
        $phone2 = $this->get('support_phone_2');
        $whatsapp = $this->get('support_whatsapp');
        
        if ($phone1) $phones[] = $phone1;
        if ($phone2) $phones[] = $phone2;
        if ($whatsapp) $phones[] = "WhatsApp: $whatsapp";
        
        return $phones;
    }
    
    /**
     * Get support email
     */
    public function getSupportEmail() {
        return $this->get('support_email', 'support@fctcns.edu.ng');
    }
    
    /**
     * Get institution name
     */
    public function getInstitutionName() {
        return $this->get('institution_name', 'FCT College of Nursing Sciences');
    }
    
    /**
     * Get application year
     */
    public function getApplicationYear() {
        return $this->get('application_year', date('Y') . '/' . (date('Y') + 1));
    }
}