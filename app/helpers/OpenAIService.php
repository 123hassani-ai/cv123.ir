<?php
/**
 * فایل: /app/helpers/OpenAIService.php
 * توضیحات: کلاس رابط برای اتصال به سرویس هوش مصنوعی OpenAI
 */

class OpenAIService
{
    private $apiKey;
    private $apiUrl = 'https://api.openai.com/v1';
    private $model = 'gpt-3.5-turbo';
    
    /**
     * سازنده کلاس
     */
    public function __construct()
    {
        // خواندن کلید API از تنظیمات
        $settings = $this->getSettings();
        $this->apiKey = $settings['openai_api_key'] ?? OPENAI_API_KEY ?? '';
        $this->model = $settings['ai_model'] ?? 'gpt-3.5-turbo';
    }
    
    /**
     * دریافت تنظیمات از دیتابیس
     */
    private function getSettings()
    {
        try {
            // استفاده از کلاس Database برای دریافت تنظیمات از دیتابیس
            $db = Database::getInstance();
            $result = $db->query("SELECT `key`, `value` FROM settings WHERE `group` = 'ai' OR `key` = 'openai_api_key'")->fetchAll();
            
            // تبدیل نتایج به آرایه تنظیمات
            $settings = [];
            foreach ($result as $row) {
                // تبدیل مقادیر 0 و 1 به بولین برای فیلدهای فعال/غیرفعال
                if ($row['key'] === 'enable_ai') {
                    $settings[$row['key']] = (bool)$row['value'];
                } else {
                    $settings[$row['key']] = $row['value'];
                }
            }
            
            return $settings;
        } catch (Exception $e) {
            error_log('خطا در دریافت تنظیمات OpenAI: ' . $e->getMessage());
            return [];
        }
    }
    
    /**
     * ارسال پیام به OpenAI و دریافت پاسخ
     * 
     * @param string $message پیام کاربر
     * @param array $history تاریخچه گفتگو
     * @param array $options تنظیمات اضافی
     * @return array پاسخ OpenAI
     */
    public function chat($message, $history = [], $options = [])
    {
        // بررسی وجود کلید API
        if (empty($this->apiKey)) {
            return [
                'error' => true,
                'message' => 'کلید API هوش مصنوعی تنظیم نشده است.'
            ];
        }
        
        // ساخت آرایه پیام‌ها
        $messages = [];
        
        // افزودن پیام سیستم برای راهنمایی مدل
        $settings = $this->getSettings();
        $systemMessage = $options['system_message'] ?? 
                       ($settings['ai_instructions'] ?? 
                        'شما یک دستیار هوشمند استخدام برای مجتمع کامپیوتر یک دو سه هستید. به سوالات متقاضیان به صورت دقیق، مفید و مودبانه پاسخ دهید.');
        
        $messages[] = [
            'role' => 'system',
            'content' => $systemMessage
        ];
        
        // افزودن تاریخچه گفتگو
        if (!empty($history)) {
            foreach ($history as $entry) {
                $messages[] = [
                    'role' => $entry['role'] ?? 'user',
                    'content' => $entry['content'] ?? $entry['message'] ?? ''
                ];
            }
        }
        
        // افزودن پیام جدید کاربر
        $messages[] = [
            'role' => 'user',
            'content' => $message
        ];
        
        // تنظیمات درخواست
        $data = [
            'model' => $this->model,
            'messages' => $messages,
            'temperature' => $options['temperature'] ?? 0.7,
            'max_tokens' => $options['max_tokens'] ?? 500,
        ];
        
        // ارسال درخواست به API
        try {
            $response = $this->sendRequest('/chat/completions', $data);
            
            if (isset($response['error'])) {
                return [
                    'error' => true,
                    'message' => $response['error']['message'] ?? 'خطا در ارتباط با سرویس هوش مصنوعی'
                ];
            }
            
            // استخراج پاسخ از نتیجه
            $assistantResponse = $response['choices'][0]['message']['content'] ?? '';
            
            return [
                'error' => false,
                'message' => $assistantResponse
            ];
        } catch (Exception $e) {
            error_log('خطا در OpenAI: ' . $e->getMessage());
            
            return [
                'error' => true,
                'message' => 'خطا در ارتباط با سرویس هوش مصنوعی: ' . $e->getMessage()
            ];
        }
    }
    
    /**
     * ارسال درخواست به API
     * 
     * @param string $endpoint نقطه انتهایی API
     * @param array $data داده‌های درخواست
     * @return array پاسخ API
     */
    private function sendRequest($endpoint, $data)
    {
        $url = $this->apiUrl . $endpoint;
        
        $headers = [
            'Content-Type: application/json',
            'Authorization: Bearer ' . $this->apiKey
        ];
        
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        
        $response = curl_exec($ch);
        $statusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        
        if (curl_errno($ch)) {
            error_log('خطای cURL: ' . curl_error($ch));
            curl_close($ch);
            
            return [
                'error' => [
                    'message' => 'خطا در ارتباط با سرویس: ' . curl_error($ch)
                ]
            ];
        }
        
        curl_close($ch);
        
        // بررسی کد وضعیت HTTP
        if ($statusCode !== 200) {
            error_log('خطای OpenAI API: کد وضعیت ' . $statusCode . ' - پاسخ: ' . $response);
            
            return [
                'error' => [
                    'message' => 'خطا در ارتباط با سرویس (کد ' . $statusCode . ')'
                ]
            ];
        }
        
        return json_decode($response, true);
    }
    
    /**
     * تست اتصال به OpenAI
     * 
     * @return array نتیجه تست
     */
    public function testConnection()
    {
        try {
            $result = $this->chat('این یک پیام تست است. لطفاً با جمله "اتصال با موفقیت برقرار شد" پاسخ دهید.');
            
            if (isset($result['error']) && $result['error']) {
                return [
                    'success' => false,
                    'message' => $result['message']
                ];
            }
            
            return [
                'success' => true,
                'message' => 'اتصال با موفقیت برقرار شد.',
                'response' => $result['message']
            ];
        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => 'خطا در تست اتصال: ' . $e->getMessage()
            ];
        }
    }
}
