<?php
/**
 * فایل: /app/helpers/AiSettingsManager.php
 * توضیحات: مدیریت تنظیمات هوش مصنوعی
 */

class AiSettingsManager
{
    private $db;
    
    public function __construct()
    {
        $this->db = Database::getInstance();
    }
    
    /**
     * دریافت تنظیمات هوش مصنوعی فعال
     */
    public function getActiveSettings()
    {
        try {
            $result = $this->db->query("
                SELECT * FROM ai_settings 
                WHERE is_active = 1 
                ORDER BY id DESC 
                LIMIT 1
            ")->fetch();
            
            return $result ?: [];
        } catch (Exception $e) {
            error_log('خطا در دریافت تنظیمات هوش مصنوعی: ' . $e->getMessage());
            return [];
        }
    }
    
    /**
     * دریافت تنظیمات بر اساس ارائه‌دهنده
     */
    public function getSettingsByProvider($provider)
    {
        try {
            $result = $this->db->query(
                "SELECT * FROM ai_settings WHERE provider_name = ? AND is_active = 1 LIMIT 1",
                [$provider]
            )->fetch();
            
            return $result ?: [];
        } catch (Exception $e) {
            error_log("خطا در دریافت تنظیمات هوش مصنوعی {$provider}: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * بروزرسانی تنظیمات هوش مصنوعی
     */
    public function updateSettings($data)
    {
        try {
            $this->db->beginTransaction();
            
            // اطلاعات پیش‌فرض
            $defaults = [
                'provider_name' => 'openai',
                'model' => 'gpt-3.5-turbo',
                'max_tokens' => 1000,
                'temperature' => 0.70,
                'max_requests_per_hour' => 100,
                'timeout_seconds' => 30,
                'system_prompt' => 'شما یک دستیار هوشمند برای سیستم استخدام CV123 هستید.'
            ];
            
            // ترکیب داده‌های ورودی با پیش‌فرض‌ها
            $settingsData = array_merge($defaults, $data);
            
            // غیرفعال کردن تنظیمات قبلی
            $this->db->query("UPDATE ai_settings SET is_active = 0 WHERE provider_name = ?", [$settingsData['provider_name']]);
            
            // درج تنظیمات جدید
            $sql = "
                INSERT INTO ai_settings (
                    provider_name, api_key, model, max_tokens, temperature,
                    max_requests_per_hour, system_prompt, timeout_seconds,
                    backup_provider, is_active
                ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, 1)
            ";
            
            $this->db->query($sql, [
                $settingsData['provider_name'],
                $settingsData['api_key'] ?? '',
                $settingsData['model'],
                $settingsData['max_tokens'],
                $settingsData['temperature'],
                $settingsData['max_requests_per_hour'],
                $settingsData['system_prompt'],
                $settingsData['timeout_seconds'],
                $settingsData['backup_provider'] ?? null
            ]);
            
            $this->db->commit();
            return true;
        } catch (Exception $e) {
            if ($this->db->inTransaction()) {
                $this->db->rollBack();
            }
            error_log('خطا در بروزرسانی تنظیمات هوش مصنوعی: ' . $e->getMessage());
            return false;
        }
    }
    
    /**
     * تست اتصال به سرویس هوش مصنوعی
     */
    public function testConnection($testPrompt = 'سلام، این یک تست اتصال است.')
    {
        try {
            $settings = $this->getActiveSettings();
            if (empty($settings) || empty($settings['api_key'])) {
                return [
                    'success' => false,
                    'message' => 'کلید API هوش مصنوعی تنظیم نشده است'
                ];
            }
            
            // استفاده از OpenAIService موجود
            $openAIService = new OpenAIService();
            
            $testData = [
                'model' => $settings['model'],
                'messages' => [
                    [
                        'role' => 'system',
                        'content' => $settings['system_prompt'] ?? 'شما یک دستیار هوشمند هستید.'
                    ],
                    [
                        'role' => 'user',
                        'content' => $testPrompt
                    ]
                ],
                'max_tokens' => min($settings['max_tokens'], 100), // محدود کردن برای تست
                'temperature' => $settings['temperature']
            ];
            
            $response = $openAIService->makeRequest('chat/completions', $testData, 'POST');
            
            if ($response && isset($response['choices']) && !empty($response['choices'])) {
                return [
                    'success' => true,
                    'message' => 'اتصال به سرویس هوش مصنوعی با موفقیت برقرار شد',
                    'response' => $response['choices'][0]['message']['content'] ?? 'پاسخ دریافت شد'
                ];
            }
            
            return [
                'success' => false,
                'message' => 'پاسخ نامعتبر از سرویس هوش مصنوعی'
            ];
            
        } catch (Exception $e) {
            error_log('خطا در تست اتصال هوش مصنوعی: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => 'خطا در تست اتصال: ' . $e->getMessage()
            ];
        }
    }
    
    /**
     * دریافت آمار استفاده از هوش مصنوعی
     */
    public function getUsageStats()
    {
        // در نسخه‌های بعدی می‌توان جدول آمار هوش مصنوعی اضافه کرد
        return [
            'requests_today' => 0,
            'requests_this_month' => 0,
            'total_tokens_used' => 0,
            'last_request' => null
        ];
    }
    
    /**
     * اعتبارسنجی تنظیمات
     */
    public function validateSettings($data)
    {
        $errors = [];
        
        // بررسی کلید API
        if (empty($data['api_key'])) {
            $errors[] = 'کلید API الزامی است';
        } elseif (!preg_match('/^sk-[a-zA-Z0-9]{48}$/', $data['api_key'])) {
            $errors[] = 'فرمت کلید API صحیح نیست';
        }
        
        // بررسی محدوده توکن‌ها
        if (isset($data['max_tokens']) && ($data['max_tokens'] < 1 || $data['max_tokens'] > 4000)) {
            $errors[] = 'تعداد حداکثر توکن‌ها باید بین 1 تا 4000 باشد';
        }
        
        // بررسی دمای مدل
        if (isset($data['temperature']) && ($data['temperature'] < 0 || $data['temperature'] > 2)) {
            $errors[] = 'دمای مدل باید بین 0 تا 2 باشد';
        }
        
        return $errors;
    }
}
