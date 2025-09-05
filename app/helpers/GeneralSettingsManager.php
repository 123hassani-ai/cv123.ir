<?php
/**
 * فایل: /app/helpers/GeneralSettingsManager.php
 * توضیحات: مدیریت تنظیمات عمومی سیستم
 */

class GeneralSettingsManager
{
    private $db;
    
    public function __construct()
    {
        $this->db = Database::getInstance();
    }
    
    /**
     * دریافت تمام تنظیمات عمومی
     */
    public function getAllSettings()
    {
        try {
            $result = $this->db->query("
                SELECT setting_key, setting_value, description, setting_type, is_active 
                FROM general_settings 
                WHERE is_active = 1 
                ORDER BY setting_key
            ")->fetchAll();
            
            $settings = [];
            foreach ($result as $row) {
                $settings[$row['setting_key']] = [
                    'value' => $this->convertValue($row['setting_value'], $row['setting_type']),
                    'description' => $row['description'],
                    'type' => $row['setting_type']
                ];
            }
            
            return $settings;
        } catch (Exception $e) {
            error_log('خطا در دریافت تنظیمات عمومی: ' . $e->getMessage());
            return [];
        }
    }
    
    /**
     * دریافت یک تنظیم خاص
     */
    public function getSetting($key, $defaultValue = null)
    {
        try {
            $result = $this->db->query(
                "SELECT setting_value, setting_type FROM general_settings WHERE setting_key = ? AND is_active = 1",
                [$key]
            )->fetch();
            
            if ($result) {
                return $this->convertValue($result['setting_value'], $result['setting_type']);
            }
            
            return $defaultValue;
        } catch (Exception $e) {
            error_log("خطا در دریافت تنظیم {$key}: " . $e->getMessage());
            return $defaultValue;
        }
    }
    
    /**
     * بروزرسانی یک تنظیم
     */
    public function updateSetting($key, $value, $description = null, $type = 'text')
    {
        try {
            $this->db->beginTransaction();
            
            $sql = "
                INSERT INTO general_settings (setting_key, setting_value, description, setting_type) 
                VALUES (?, ?, ?, ?) 
                ON DUPLICATE KEY UPDATE 
                    setting_value = VALUES(setting_value),
                    description = COALESCE(VALUES(description), description),
                    setting_type = VALUES(setting_type),
                    updated_at = CURRENT_TIMESTAMP
            ";
            
            $this->db->query($sql, [$key, $value, $description, $type]);
            $this->db->commit();
            
            return true;
        } catch (Exception $e) {
            if ($this->db->inTransaction()) {
                $this->db->rollBack();
            }
            error_log("خطا در بروزرسانی تنظیم {$key}: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * بروزرسانی چندین تنظیم به‌طور همزمان
     */
    public function updateMultipleSettings($settings)
    {
        try {
            $this->db->beginTransaction();
            
            foreach ($settings as $key => $data) {
                $value = is_array($data) ? $data['value'] : $data;
                $description = is_array($data) ? ($data['description'] ?? null) : null;
                $type = is_array($data) ? ($data['type'] ?? 'text') : 'text';
                
                $this->updateSetting($key, $value, $description, $type);
            }
            
            $this->db->commit();
            return true;
        } catch (Exception $e) {
            if ($this->db->inTransaction()) {
                $this->db->rollBack();
            }
            error_log('خطا در بروزرسانی چندگانه تنظیمات عمومی: ' . $e->getMessage());
            return false;
        }
    }
    
    /**
     * تبدیل مقدار بر اساس نوع
     */
    private function convertValue($value, $type)
    {
        switch ($type) {
            case 'boolean':
                return (bool)$value;
            case 'number':
                return is_numeric($value) ? (int)$value : 0;
            case 'json':
                return json_decode($value, true) ?: [];
            default:
                return $value;
        }
    }
    
    /**
     * حذف یک تنظیم (غیرفعال کردن)
     */
    public function deactivateSetting($key)
    {
        try {
            $this->db->query(
                "UPDATE general_settings SET is_active = 0, updated_at = CURRENT_TIMESTAMP WHERE setting_key = ?",
                [$key]
            );
            return true;
        } catch (Exception $e) {
            error_log("خطا در غیرفعال‌سازی تنظیم {$key}: " . $e->getMessage());
            return false;
        }
    }
}
