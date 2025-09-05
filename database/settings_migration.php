<?php
/**
 * فایل: /database/settings_migration.php
 * توضیحات: اسکریپت مهاجرت داده‌های تنظیمات از جدول قدیمی به جداول جدید
 */

require_once dirname(__DIR__) . '/config/config.php';
require_once dirname(__DIR__) . '/app/helpers/Database.php';

echo "شروع مهاجرت تنظیمات...\n";

try {
    $db = Database::getInstance();
    
    // بررسی وجود جدول قدیمی
    $oldTableExists = $db->query("SHOW TABLES LIKE 'settings'")->rowCount() > 0;
    
    if (!$oldTableExists) {
        echo "جدول قدیمی settings یافت نشد.\n";
        exit;
    }
    
    // دریافت داده‌های جدول قدیمی
    $oldSettings = $db->query("SELECT `key`, `value` FROM settings")->fetchAll();
    
    if (empty($oldSettings)) {
        echo "داده‌ای در جدول قدیمی یافت نشد.\n";
        exit;
    }
    
    echo "تعداد " . count($oldSettings) . " تنظیم برای مهاجرت یافت شد.\n";
    
    $db->beginTransaction();
    
    foreach ($oldSettings as $setting) {
        $key = $setting['key'];
        $value = $setting['value'];
        
        // تعیین جدول مقصد بر اساس کلید
        if (strpos($key, 'openai_') === 0) {
            // تنظیمات هوش مصنوعی
            $fieldMapping = [
                'openai_api_key' => 'api_key',
                'openai_model' => 'model',
                'openai_max_tokens' => 'max_tokens',
                'openai_temperature' => 'temperature'
            ];
            
            if (isset($fieldMapping[$key])) {
                $field = $fieldMapping[$key];
                $sql = "UPDATE ai_settings SET {$field} = ?, updated_at = CURRENT_TIMESTAMP WHERE provider_name = 'openai'";
                $db->query($sql, [$value]);
                echo "✓ {$key} → ai_settings.{$field}\n";
            }
            
        } elseif (strpos($key, 'sms_') === 0) {
            // تنظیمات پیامک
            $fieldMapping = [
                'sms_0098_username' => 'username',
                'sms_0098_password' => 'password',
                'sms_0098_domain' => 'domain',
                'sms_0098_line_number' => 'line_number',
                'sms_test_phone' => 'test_phone',
                'sms_test_message' => 'test_message'
            ];
            
            if (isset($fieldMapping[$key])) {
                $field = $fieldMapping[$key];
                $sql = "UPDATE sms_settings SET {$field} = ?, updated_at = CURRENT_TIMESTAMP WHERE provider_name = '0098'";
                $db->query($sql, [$value]);
                echo "✓ {$key} → sms_settings.{$field}\n";
            }
            
        } else {
            // تنظیمات عمومی
            $generalKeys = [
                'site_title', 'site_description', 'footer_text', 
                'contact_email', 'contact_phone', 'admin_email',
                'maintenance_mode', 'debug_mode'
            ];
            
            if (in_array($key, $generalKeys)) {
                // بررسی وجود تنظیم
                $exists = $db->query(
                    "SELECT COUNT(*) as count FROM general_settings WHERE setting_key = ?", 
                    [$key]
                )->fetch()['count'] > 0;
                
                if ($exists) {
                    $sql = "UPDATE general_settings SET setting_value = ?, updated_at = CURRENT_TIMESTAMP WHERE setting_key = ?";
                    $db->query($sql, [$value, $key]);
                } else {
                    $sql = "INSERT INTO general_settings (setting_key, setting_value) VALUES (?, ?)";
                    $db->query($sql, [$key, $value]);
                }
                echo "✓ {$key} → general_settings\n";
            }
        }
    }
    
    $db->commit();
    echo "\n✅ مهاجرت تنظیمات با موفقیت کامل شد!\n";
    
    // آمار نهایی
    $generalCount = $db->query("SELECT COUNT(*) as count FROM general_settings WHERE is_active = 1")->fetch()['count'];
    $smsCount = $db->query("SELECT COUNT(*) as count FROM sms_settings WHERE is_active = 1")->fetch()['count'];
    $aiCount = $db->query("SELECT COUNT(*) as count FROM ai_settings WHERE is_active = 1")->fetch()['count'];
    
    echo "\n📊 آمار نهایی:\n";
    echo "- تنظیمات عمومی: {$generalCount}\n";
    echo "- تنظیمات پیامک: {$smsCount}\n";
    echo "- تنظیمات هوش مصنوعی: {$aiCount}\n";
    
    // پیشنهاد تغییر نام جدول قدیمی
    echo "\n💡 پیشنهاد: جدول قدیمی 'settings' را می‌توانید به 'settings_old' تغییر نام دهید:\n";
    echo "RENAME TABLE settings TO settings_old;\n";
    
} catch (Exception $e) {
    if ($db && $db->inTransaction()) {
        $db->rollBack();
    }
    echo "❌ خطا در مهاجرت: " . $e->getMessage() . "\n";
    exit(1);
}
?>
