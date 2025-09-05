<?php
/**
 * فایل: /app/controllers/SettingsController.php
 * توضیحات: کنترلر تنظیمات سیستم
 */

class SettingsController
{
    /**
     * تست اتصال به OpenAI
     */
    public function testOpenAI()
    {
        // تنظیم header برای JSON response
        header('Content-Type: application/json; charset=utf-8');
        
        // بررسی دسترسی مدیر
        try {
            $this->checkAdminAccess();
        } catch (Exception $e) {
            echo json_encode([
                'success' => false,
                'message' => 'عدم دسترسی: ' . $e->getMessage()
            ], JSON_UNESCAPED_UNICODE);
            return;
        }
        
        // دریافت کلید API
        $apiKey = $_POST['api_key'] ?? '';
        
        if (empty($apiKey)) {
            echo json_encode([
                'success' => false,
                'message' => 'کلید API وارد نشده است.'
            ], JSON_UNESCAPED_UNICODE);
            return;
        }
        
        // ذخیره موقت کلید API
        $this->saveTemporaryApiKey($apiKey);
        
        // تست اتصال
        try {
            $openai = new OpenAIService();
            $result = $openai->testConnection();
            
            echo json_encode($result, JSON_UNESCAPED_UNICODE);
        } catch (Exception $e) {
            echo json_encode([
                'success' => false,
                'message' => 'خطا در تست اتصال: ' . $e->getMessage()
            ], JSON_UNESCAPED_UNICODE);
        }
        
        // اطمینان از عدم نمایش محتوای اضافی
        exit;
    }
    
    /**
     * ذخیره موقت کلید API (فقط برای تست)
     */
    private function saveTemporaryApiKey($apiKey)
    {
        $settingsFile = APP_PATH . '/../config/temp_settings.json';
        
        $settings = [
            'openai_api_key' => $apiKey,
            'ai_model' => 'gpt-3.5-turbo'
        ];
        
        file_put_contents($settingsFile, json_encode($settings));
    }
    
    /**
     * تست اتصال به سرویس پیامک
     */
    public function testSms()
    {
        // تنظیم header برای JSON response
        header('Content-Type: application/json; charset=utf-8');
        
        // بررسی دسترسی مدیر
        try {
            $this->checkAdminAccess();
        } catch (Exception $e) {
            echo json_encode([
                'success' => false,
                'message' => 'عدم دسترسی: ' . $e->getMessage()
            ], JSON_UNESCAPED_UNICODE);
            return;
        }
        
        // دریافت اطلاعات
        $provider = $_POST['provider'] ?? '0098';
        $username = $_POST['username'] ?? '';
        $password = $_POST['password'] ?? '';
        $sender = $_POST['sender'] ?? '';
        $testNumber = $_POST['test_number'] ?? null;
        
        // اعتبارسنجی ورودی‌ها
        if (empty($username) || empty($password)) {
            echo json_encode([
                'success' => false,
                'message' => 'نام کاربری یا رمز عبور وارد نشده است.'
            ], JSON_UNESCAPED_UNICODE);
            return;
        }
        
        if (empty($sender)) {
            echo json_encode([
                'success' => false,
                'message' => 'شماره فرستنده وارد نشده است.'
            ], JSON_UNESCAPED_UNICODE);
            return;
        }
        
        // ذخیره موقت اطلاعات
        $this->saveTemporarySmsSettings($provider, $username, $password, $sender);
        
        // تست اتصال
        try {
            // لاگ شروع تست
            error_log('شروع تست SMS - Provider: ' . $provider . ', Username: ' . $username);
            
            $sms = new SmsService();
            
            // دریافت تنظیمات تست از دیتابیس
            $db = Database::getInstance();
            $testMessageResult = $db->query("SELECT `value` FROM settings WHERE `key` = 'sms_test_message'")->fetch();
            $testNumberResult = $db->query("SELECT `value` FROM settings WHERE `key` = 'sms_test_number'")->fetch();
            
            // تعیین شماره تست
            $defaultTestNumber = $testNumberResult ? $testNumberResult['value'] : '09123456789';
            $phoneNumber = !empty($testNumber) ? $testNumber : $defaultTestNumber;
            
            // متن پیام تست
            $testMessage = $testMessageResult ? $testMessageResult['value'] : 'تست ارسال پیامک از سامانه CV123';
            
            // ارسال پیام تست
            $result = $sms->send($phoneNumber, $testMessage, $sender);
            
            // لاگ نتیجه
            error_log('نتیجه تست SMS: ' . json_encode($result, JSON_UNESCAPED_UNICODE));
            
            if ($result['error']) {
                echo json_encode([
                    'success' => false,
                    'message' => $result['message'],
                    'code' => $result['code'] ?? null,
                    'details' => [
                        'provider' => $provider,
                        'username' => $username,
                        'sender' => $sender,
                        'test_number' => $phoneNumber
                    ]
                ], JSON_UNESCAPED_UNICODE);
            } else {
                echo json_encode([
                    'success' => true,
                    'message' => 'تست ارسال پیامک موفق بود!',
                    'details' => [
                        'provider' => $provider,
                        'username' => $username,
                        'sender' => $sender,
                        'test_number' => $phoneNumber,
                        'response_code' => $result['code'] ?? null
                    ]
                ], JSON_UNESCAPED_UNICODE);
            }
        } catch (Exception $e) {
            // لاگ خطا
            error_log('خطا در تست SMS: ' . $e->getMessage());
            
            echo json_encode([
                'success' => false,
                'message' => 'خطا در تست اتصال: ' . $e->getMessage(),
                'details' => [
                    'provider' => $provider,
                    'username' => $username,
                    'sender' => $sender
                ]
            ], JSON_UNESCAPED_UNICODE);
        }
        
        // اطمینان از عدم نمایش محتوای اضافی
        exit;
    }
    
    /**
     * ذخیره موقت تنظیمات پیامک (فقط برای تست)
     */
    private function saveTemporarySmsSettings($provider, $username, $password, $sender)
    {
        $settingsFile = APP_PATH . '/../config/temp_settings.json';
        
        // خواندن تنظیمات فعلی (اگر وجود دارد)
        $settings = [];
        if (file_exists($settingsFile)) {
            $settings = json_decode(file_get_contents($settingsFile), true) ?: [];
        }
        
        // افزودن تنظیمات پیامک
        $settings['sms_provider'] = $provider;
        $settings['sms_username'] = $username;
        $settings['sms_password'] = $password;
        $settings['sms_sender'] = $sender;
        
        file_put_contents($settingsFile, json_encode($settings));
    }
    
    /**
     * بررسی دسترسی مدیر
     */
    private function checkAdminAccess()
    {
        // در نسخه اولیه، اگر کاربر وارد نشده باشد یا مدیر نباشد، به صفحه ورود هدایت می‌شود
        if (!isset($_SESSION['admin_id'])) {
            http_response_code(403);
            echo json_encode([
                'success' => false,
                'message' => 'دسترسی غیرمجاز'
            ]);
            exit;
        }
    }
}
