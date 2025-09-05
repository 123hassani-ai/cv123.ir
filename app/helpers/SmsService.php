<?php
/**
 * فایل: /app/helpers/SmsService.php
 * توضیحات: کلاس رابط برای اتصال به سرویس پیامک ۰۰۹۸
 */

class SmsService
{
    private $username;
    private $password;
    private $sender;
    private $apiUrl = 'https://rest.0098sms.com/api';
    private $provider = '0098';
    
    /**
     * سازنده کلاس
     */
    public function __construct()
    {
        // خواندن تنظیمات از فایل
        $settings = $this->getSettings();
        
        $this->username = $settings['sms_username'] ?? SMS_USERNAME ?? '';
        $this->password = $settings['sms_password'] ?? SMS_PASSWORD ?? '';
        $this->sender = $settings['sms_sender'] ?? SMS_SENDER ?? '';
        $this->provider = $settings['sms_provider'] ?? '0098';
        
        // تنظیم URL API براساس سرویس‌دهنده
        if ($this->provider === 'sms.ir') {
            $this->apiUrl = 'https://api.sms.ir/v1';
        } elseif ($this->provider === 'kavenegar') {
            $this->apiUrl = 'https://api.kavenegar.com/v1';
        } else {
            $this->apiUrl = 'https://rest.0098sms.com/api';
        }
    }
    
    /**
     * دریافت تنظیمات از دیتابیس
     */
    private function getSettings()
    {
        try {
            // استفاده از کلاس Database برای دریافت تنظیمات از دیتابیس
            $db = Database::getInstance();
            $result = $db->query("SELECT `key`, `value` FROM settings WHERE `group` = 'sms'")->fetchAll();
            
            // تبدیل نتایج به آرایه تنظیمات
            $settings = [];
            foreach ($result as $row) {
                // تبدیل مقادیر 0 و 1 به بولین برای فیلدهای فعال/غیرفعال
                if ($row['key'] === 'enable_sms') {
                    $settings[$row['key']] = (bool)$row['value'];
                } else {
                    $settings[$row['key']] = $row['value'];
                }
            }
            
            return $settings;
        } catch (Exception $e) {
            error_log('خطا در دریافت تنظیمات پیامک: ' . $e->getMessage());
            return [];
        }
    }
    
    /**
     * ارسال پیامک
     * 
     * @param string|array $receptor شماره گیرنده یا گیرندگان
     * @param string $message متن پیام
     * @param string $sender شماره فرستنده (اختیاری)
     * @return array پاسخ API
     */
    public function send($receptor, $message, $sender = null)
    {
        // بررسی تنظیمات حساب کاربری
        if (empty($this->username) || empty($this->password)) {
            return [
                'error' => true,
                'message' => 'نام کاربری یا رمز عبور سرویس پیامک تنظیم نشده است.'
            ];
        }
        
        // تبدیل شماره گیرنده به آرایه
        if (!is_array($receptor)) {
            $receptor = [$receptor];
        }
        
        // حذف صفر ابتدای شماره‌ها و افزودن +98
        $receptor = array_map(function($number) {
            $number = ltrim($number, '0');
            if (substr($number, 0, 2) !== '98') {
                $number = '98' . $number;
            }
            return $number;
        }, $receptor);
        
        // تنظیم شماره فرستنده
        $sender = $sender ?: $this->sender;
        
        // ارسال براساس سرویس‌دهنده
        switch ($this->provider) {
            case 'sms.ir':
                return $this->sendWithSmsIr($receptor, $message, $sender);
            case 'kavenegar':
                return $this->sendWithKavenegar($receptor, $message, $sender);
            default:
                return $this->sendWith0098($receptor, $message, $sender);
        }
    }
    
    /**
     * ارسال پیامک با سرویس ۰۰۹۸
     */
    private function sendWith0098($receptor, $message, $sender)
    {
        try {
            // تبدیل آرایه شماره‌ها به رشته
            $phoneNumber = $receptor[0]; // فقط اولین شماره (برای تست)
            
            // تبدیل شماره به فرمت مناسب (09xxxxxxxxx)
            if (substr($phoneNumber, 0, 2) === '98') {
                $phoneNumber = '0' . substr($phoneNumber, 2);
            }
            
            // پارامترهای ارسال براساس مستندات 0098
            $params = [
                'FROM' => $sender,
                'TO' => $phoneNumber,
                'TEXT' => $message, // حذف urlencode از اینجا
                'USERNAME' => $this->username,
                'PASSWORD' => $this->password,
                'DOMAIN' => '0098'
            ];
            
            // ساخت URL کامل با urlencode فقط برای متن
            $url = 'https://0098sms.com/sendsmslink.aspx?' . 
                   'FROM=' . urlencode($sender) .
                   '&TO=' . urlencode($phoneNumber) .
                   '&TEXT=' . urlencode($message) .
                   '&USERNAME=' . urlencode($this->username) .
                   '&PASSWORD=' . urlencode($this->password) .
                   '&DOMAIN=0098';
            
            // ارسال درخواست GET
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_TIMEOUT, 30);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'Accept-Charset: UTF-8',
                'Content-Type: application/x-www-form-urlencoded; charset=UTF-8'
            ]);
            
            $response = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            
            if (curl_errno($ch)) {
                $error = curl_error($ch);
                curl_close($ch);
                
                // لاگ خطا
                error_log('خطا در ارسال درخواست به 0098SMS: ' . $error);
                
                return [
                    'error' => true,
                    'message' => 'خطا در ارتباط با سرویس پیامک: ' . $error,
                    'code' => null
                ];
            }
            
            curl_close($ch);
            
            // بررسی کد پاسخ HTTP
            if ($httpCode !== 200) {
                error_log('خطا در پاسخ HTTP از 0098SMS: ' . $httpCode);
                
                return [
                    'error' => true,
                    'message' => 'خطای HTTP: ' . $httpCode,
                    'code' => $httpCode
                ];
            }
            
            // پردازش پاسخ (کد عددی)
            $responseCode = trim($response);
            
            // لاگ پاسخ برای دیباگ
            error_log('پاسخ 0098SMS: ' . $responseCode);
            
            // بررسی کد پاسخ براساس مستندات
            if ($responseCode == '0') {
                return [
                    'error' => false,
                    'message' => 'پیامک با موفقیت ارسال شد.',
                    'code' => $responseCode
                ];
            } else {
                // پیام خطا براساس کد
                $errorMessage = $this->get0098ErrorMessage($responseCode);
                
                return [
                    'error' => true,
                    'message' => $errorMessage,
                    'code' => $responseCode
                ];
            }
        } catch (Exception $e) {
            error_log('خطا در ارسال پیامک 0098: ' . $e->getMessage());
            
            return [
                'error' => true,
                'message' => 'خطا در ارسال پیامک: ' . $e->getMessage(),
                'code' => null
            ];
        }
    }
    
    /**
     * دریافت پیام خطا براساس کد پاسخ 0098
     */
    private function get0098ErrorMessage($code)
    {
        $errors = [
            '1' => 'شماره گیرنده اشتباه است',
            '2' => 'گیرنده تعریف نشده است',
            '3' => 'فرستنده تعریف نشده است',
            '4' => 'متن تنظیم نشده است',
            '5' => 'نام کاربری تنظیم نشده است',
            '6' => 'کلمه عبور تنظیم نشده است',
            '7' => 'نام دامین تنظیم نشده است',
            '8' => 'مجوز شما باطل شده است',
            '9' => 'اعتبار پیامک شما کافی نیست',
            '10' => 'برای این شماره لینک تعریف نشده است',
            '11' => 'عدم مجوز برای اتصال لینک',
            '12' => 'نام کاربری و کلمه عبور اشتباه است',
            '13' => 'کاراکتر غیرمجاز در متن وجود دارد',
            '14' => 'سقف ارسال روزانه پر شده است',
            '16' => 'عدم مجوز شماره برای ارسال از لینک',
            '17' => 'خطا در شماره پنل. لطفا با پشتیبانی تماس بگیرید',
            '18' => 'اتمام تاریخ اعتبار شماره پنل. برای استفاده تمدید شود',
            '19' => 'تنظیمات کد opt انجام نشده است',
            '20' => 'فرمت کد opt صحیح نیست',
            '21' => 'تنظیمات کد opt توسط ادمین تایید نشده است',
            '22' => 'اطلاعات مالک شماره ثبت و تایید نشده است',
            '23' => 'هنوز اجازه ارسال به این شماره پنل داده نشده است',
            '24' => 'ارسال از IP غیرمجاز انجام شده است'
        ];
        
        return $errors[$code] ?? 'خطای نامشخص (کد: ' . $code . ')';
    }
    
    /**
     * ارسال پیامک با سرویس SMS.ir
     */
    private function sendWithSmsIr($receptor, $message, $sender)
    {
        // پیاده‌سازی ارسال با SMS.ir
        // این متد باید براساس مستندات API سرویس SMS.ir پیاده‌سازی شود
        
        return [
            'error' => true,
            'message' => 'ارسال با SMS.ir هنوز پیاده‌سازی نشده است.'
        ];
    }
    
    /**
     * ارسال پیامک با سرویس کاوه‌نگار
     */
    private function sendWithKavenegar($receptor, $message, $sender)
    {
        // پیاده‌سازی ارسال با کاوه‌نگار
        // این متد باید براساس مستندات API سرویس کاوه‌نگار پیاده‌سازی شود
        
        return [
            'error' => true,
            'message' => 'ارسال با کاوه‌نگار هنوز پیاده‌سازی نشده است.'
        ];
    }
    
    /**
     * ارسال درخواست به API
     * 
     * @param string $url آدرس API
     * @param array $params پارامترهای درخواست
     * @return array پاسخ API
     */
    private function sendRequest($url, $params)
    {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($params));
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        
        $response = curl_exec($ch);
        
        if (curl_errno($ch)) {
            error_log('خطای cURL: ' . curl_error($ch));
            curl_close($ch);
            
            return [
                'status' => 'error',
                'message' => 'خطا در ارتباط با سرویس: ' . curl_error($ch)
            ];
        }
        
        curl_close($ch);
        
        return json_decode($response, true) ?: [
            'status' => 'error',
            'message' => 'پاسخ نامعتبر از سرویس پیامک'
        ];
    }
    
    /**
     * تست اتصال به سرویس پیامک
     * 
     * @param string $testNumber شماره موبایل برای تست
     * @return array نتیجه تست
     */
    public function testConnection($testNumber = null)
    {
        // بررسی تنظیمات حساب کاربری
        if (empty($this->username) || empty($this->password)) {
            return [
                'success' => false,
                'message' => 'نام کاربری یا رمز عبور سرویس پیامک تنظیم نشده است.'
            ];
        }
        
        // بررسی اعتبار
        try {
            // اگر شماره تست داده شده باشد، پیامک تست ارسال کن
            if ($testNumber) {
                $result = $this->send($testNumber, 'این یک پیامک تست از سامانه استخدام مجتمع کامپیوتر ۱۲۳ است.');
                
                if (isset($result['error']) && $result['error']) {
                    return [
                        'success' => false,
                        'message' => $result['message']
                    ];
                }
                
                return [
                    'success' => true,
                    'message' => 'پیامک تست با موفقیت ارسال شد.',
                    'data' => $result['data'] ?? null
                ];
            }
            
            // در غیر این صورت، فقط اعتبار حساب را بررسی کن
            // این کد برای سرویس ۰۰۹۸ است و ممکن است برای سایر سرویس‌ها متفاوت باشد
            $url = $this->apiUrl . '/credit';
            $params = [
                'username' => $this->username,
                'password' => $this->password
            ];
            
            $response = $this->sendRequest($url, $params);
            
            if (isset($response['status']) && $response['status'] === 'success') {
                return [
                    'success' => true,
                    'message' => 'اتصال با موفقیت برقرار شد.',
                    'credit' => $response['credit'] ?? 'نامشخص'
                ];
            } else {
                return [
                    'success' => false,
                    'message' => $response['message'] ?? 'خطا در برقراری ارتباط با سرویس پیامک'
                ];
            }
        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => 'خطا در تست اتصال: ' . $e->getMessage()
            ];
        }
    }
}
