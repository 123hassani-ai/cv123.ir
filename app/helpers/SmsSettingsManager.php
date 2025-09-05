<?php
/**
 * فایل: /app/helpers/SmsSettingsManager.php
 * توضیحات: مدیریت تنظیمات پیامک
 */

class SmsSettingsManager
{
    private $db;
    
    public function __construct()
    {
        $this->db = Database::getInstance();
    }
    
    /**
     * دریافت تنظیمات پیامک فعال
     */
    public function getActiveSettings()
    {
        try {
            $result = $this->db->query("
                SELECT * FROM sms_settings 
                WHERE is_active = 1 
                ORDER BY id DESC 
                LIMIT 1
            ")->fetch();
            
            return $result ?: [];
        } catch (Exception $e) {
            error_log('خطا در دریافت تنظیمات پیامک: ' . $e->getMessage());
            return [];
        }
    }
    
    /**
     * دریافت تنظیمات پیامک بر اساس ارائه‌دهنده
     */
    public function getSettingsByProvider($provider)
    {
        try {
            $result = $this->db->query(
                "SELECT * FROM sms_settings WHERE provider_name = ? AND is_active = 1 LIMIT 1",
                [$provider]
            )->fetch();
            
            return $result ?: [];
        } catch (Exception $e) {
            error_log("خطا در دریافت تنظیمات پیامک {$provider}: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * بروزرسانی تنظیمات پیامک
     */
    public function updateSettings($data)
    {
        try {
            $this->db->beginTransaction();
            
            // اطلاعات پیش‌فرض از فایل مستندات
            $defaults = [
                'provider_name' => '0098',
                'domain' => '0098',
                'api_endpoint' => 'https://0098sms.com/sendsmslink.aspx',
                'test_phone' => '09123456789',
                'test_message' => 'پیام تست از سامانه CV123',
                'max_daily_sms' => 1000
            ];
            
            // ترکیب داده‌های ورودی با پیش‌فرض‌ها
            $settingsData = array_merge($defaults, $data);
            
            // غیرفعال کردن تنظیمات قبلی
            $this->db->query("UPDATE sms_settings SET is_active = 0 WHERE provider_name = ?", [$settingsData['provider_name']]);
            
            // درج تنظیمات جدید
            $sql = "
                INSERT INTO sms_settings (
                    provider_name, username, password, domain, line_number, 
                    test_phone, test_message, max_daily_sms, api_endpoint, 
                    backup_provider, is_active
                ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 1)
            ";
            
            $this->db->query($sql, [
                $settingsData['provider_name'],
                $settingsData['username'] ?? '',
                $settingsData['password'] ?? '',
                $settingsData['domain'],
                $settingsData['line_number'] ?? '',
                $settingsData['test_phone'],
                $settingsData['test_message'],
                $settingsData['max_daily_sms'],
                $settingsData['api_endpoint'],
                $settingsData['backup_provider'] ?? null
            ]);
            
            $this->db->commit();
            return true;
        } catch (Exception $e) {
            if ($this->db->inTransaction()) {
                $this->db->rollBack();
            }
            error_log('خطا در بروزرسانی تنظیمات پیامک: ' . $e->getMessage());
            return false;
        }
    }
    
    /**
     * دریافت تنظیمات تست پیامک
     */
    public function getTestSettings()
    {
        try {
            $settings = $this->getActiveSettings();
            return [
                'test_phone' => $settings['test_phone'] ?? '09123456789',
                'test_message' => $settings['test_message'] ?? 'پیام تست از سامانه CV123'
            ];
        } catch (Exception $e) {
            error_log('خطا در دریافت تنظیمات تست پیامک: ' . $e->getMessage());
            return [
                'test_phone' => '09123456789',
                'test_message' => 'پیام تست از سامانه CV123'
            ];
        }
    }
    
    /**
     * بروزرسانی تنظیمات تست
     */
    public function updateTestSettings($testPhone, $testMessage)
    {
        try {
            $this->db->query(
                "UPDATE sms_settings SET test_phone = ?, test_message = ?, updated_at = CURRENT_TIMESTAMP WHERE is_active = 1",
                [$testPhone, $testMessage]
            );
            return true;
        } catch (Exception $e) {
            error_log('خطا در بروزرسانی تنظیمات تست پیامک: ' . $e->getMessage());
            return false;
        }
    }
    
    /**
     * تست اتصال پیامک با تنظیمات موجود
     */
    public function testConnection($phoneNumber = null, $message = null)
    {
        try {
            $settings = $this->getActiveSettings();
            if (empty($settings)) {
                return [
                    'success' => false,
                    'message' => 'تنظیمات پیامک یافت نشد'
                ];
            }
            
            // استفاده از SmsService موجود
            $smsService = new SmsService();
            
            $testPhone = $phoneNumber ?: $settings['test_phone'];
            $testMessage = $message ?: $settings['test_message'];
            
            return $smsService->sendSms($testPhone, $testMessage);
            
        } catch (Exception $e) {
            error_log('خطا در تست اتصال پیامک: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => 'خطا در تست اتصال: ' . $e->getMessage()
            ];
        }
    }
    
    /**
     * دریافت آمار استفاده از پیامک
     */
    public function getUsageStats()
    {
        // در نسخه‌های بعدی می‌توان جدول آمار پیامک اضافه کرد
        return [
            'sent_today' => 0,
            'sent_this_month' => 0,
            'remaining_credits' => 'نامشخص',
            'last_sent' => null
        ];
    }
}
